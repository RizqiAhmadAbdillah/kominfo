<?php
class Order_model extends CI_Model
{
    protected $now;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('product_model'); // Load the product model

        // Set the timezone
        date_default_timezone_set('Asia/Jakarta'); // Change to your timezone if needed

        // Set the current time with timezone
        $dateTime = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $this->now = $dateTime->format('Y-m-d H:i:s');
    }

    public function getOrder($id = null, $all = false)
    {
        $this->db->select('o.id AS order_id, 
            od.id_product, 
            p.name, 
            p.price, 
            od.quantity, 
            p.stock, 
            p.sold, 
            p.created_at AS product_created_at, 
            p.updated_at AS product_updated_at, 
            o.created_at AS order_created_at, 
            o.updated_at AS order_updated_at');
        $this->db->from('t_orders AS o');
        $this->db->join('t_order_details AS od', 'od.id_order = o.id', 'left');
        $this->db->join('m_product AS p', 'p.id = od.id_product', 'left');

        // Apply filtering by order ID if provided
        if ($id !== null) {
            $this->db->where('o.id', $id);
        }
        if (!$all) {
            $this->db->where('o.deleted_at IS NULL');
        }

        $query = $this->db->get();
        $queryResult = $query->result_array(); // Fetch results as an associative array

        $orders = []; // Initialize an empty array for orders

        foreach ($queryResult as $row) {
            // If the order ID does not exist in the orders array, initialize it
            if (!isset($orders[$row['order_id']])) {
                $orders[$row['order_id']] = [
                    'id' => $row['order_id'],
                    'products' => [],
                    'created_at' => $row['order_created_at'],
                    'updated_at' => $row['order_updated_at'],
                ];
            }

            // Add the product details to the products array
            if (!empty($row['id_product'])) { // Ensure product exists
                $orders[$row['order_id']]['products'][] = [
                    'id' => $row['id_product'],
                    'name' => $row['name'],
                    'price' => $row['price'],
                    'quantity' => $row['quantity'],
                    'stock' => $row['stock'],
                    'sold' => $row['sold'],
                    'created_at' => $row['product_created_at'],
                    'updated_at' => $row['product_updated_at'],
                ];
            }
        }

        // Convert the orders array to a regular array
        $finalOrders = array_values($orders);

        return $finalOrders; // Return the grouped orders
    }

    public function createOrder($data)
    {
        // Start a database transaction
        $this->db->trans_start();

        // Initialize status
        $status = 200; // Success status
        $total_spent = 0; // Initialize total spent

        // Validate products before inserting the order
        foreach ($data['products'] as $product) {
            // Check product existence
            $productData = $this->product_model->getProduct($product['id']);
            if (!$productData) {
                // Product not found
                $this->db->trans_rollback(); // Rollback transaction
                return ['status' => 404]; // Return product not found
            }

            if ($productData['stock'] < $product['quantity']) {
                // Insufficient stock
                $this->db->trans_rollback(); // Rollback transaction
                return ['status' => 400]; // Return insufficient stock
            }

            // Add product price * quantity to the total spent
            $total_spent += $productData['price'] * $product['quantity'];
        }

        // If all validations pass, insert the order into the t_orders table
        $orderData = [
            'total_spent' => $total_spent,
            'created_at' => $this->now,
            'updated_at' => $this->now,
        ];

        $this->db->insert('t_orders', $orderData);
        $idOrder = $this->db->insert_id();

        // Loop to insert validated products into t_order_details
        foreach ($data['products'] as $product) {
            $newStock = 0;
            $newSold = 0;
            // Prepare the order detail data
            $orderDetailData = [
                'id_order' => $idOrder,
                'id_product' => $product['id'],
                'quantity' => $product['quantity'],
                'created_at' => $this->now,
                'updated_at' => $this->now,
            ];

            // Insert into t_order_details
            $this->db->insert('t_order_details', $orderDetailData);

            // Update product stock
            $newStock = $productData['stock'] - $product['quantity'];
            $newSold = $productData['sold'] + $product['quantity'];
            $this->db->where('id', $product['id']);
            $this->db->update('m_product', [
                'stock' => $newStock,
                'sold' => $newSold, // Update sold field
                'updated_at' => $this->now
            ]);
        }

        // Complete the transaction
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return ['status' => 500]; // Transaction failed
        }

        return ['status' => $status, 'id_order' => $idOrder]; // Return the status and order ID
    }

    public function deleteOrder($id)
    {
        // Start a database transaction
        $this->db->trans_start();

        // First, check if the order exists
        $existingData = $this->getOrder($id);

        if (!$existingData) {
            return ['status' => 404]; // Order not found
        }

        // Retrieve the products associated with the order from t_order_details
        $this->db->where('id_order', $id);
        $orderDetailsQuery = $this->db->get('t_order_details');
        $orderDetails = $orderDetailsQuery->result_array();

        // Loop through each product in the order details
        foreach ($orderDetails as $orderDetail) {
            $productId = $orderDetail['id_product'];
            $quantity = $orderDetail['quantity'];

            $productData = $this->product_model->getProduct($productId);

            if ($productData) {
                $newStock = 0;
                $newSold = 0;
                $newStock = $productData['stock'] + $quantity; // Return the quantity to stock
                $newSold = $productData['sold'] - $quantity; // Reduce the sold quantity

                // Ensure sold doesn't go below 0
                if ($newSold < 0) {
                    $newSold = 0;
                }

                // Update the product in m_product
                $this->db->where('id', $productId);
                $this->db->update('m_product', [
                    'stock' => $newStock,
                    'sold' => $newSold,
                    'updated_at' => $this->now // Updating stock and sold, so we update updated_at here
                ]);
            }
        }

        // Perform soft delete by updating the deleted_at field for the order
        $this->db->where('id', $id);
        $this->db->update('t_orders', ['deleted_at' => $this->now]);

        // Perform soft delete for the associated order details (if needed)
        $this->db->where('id_order', $id);
        $this->db->update('t_order_details', ['deleted_at' => $this->now]);

        // Complete the transaction
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return ['status' => 500]; // Transaction failed
        }

        $newData = $this->getOrder($id, true);

        // Return success status with deleted order data
        return [
            'status' => 200,
            'data' => $newData
        ];
    }
}
