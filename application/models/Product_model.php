<?php
class Product_model extends CI_Model
{
    protected $now;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();

        // Set the timezone
        date_default_timezone_set('Asia/Jakarta'); // Change to your timezone if needed

        // Set the current time with timezone
        $dateTime = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $this->now = $dateTime->format('Y-m-d H:i:s');
    }

    public function getProduct($id = null, $all = false)
    {
        // Get the product by ID
        if ($id) {
            $this->db->where('id', $id);
        }
        if (!$all) {
            $this->db->where('m.deleted_at IS NULL');
        }
        $this->db->select('m.id, m.name, m.price, m.stock, m.sold, m.created_at, m.updated_at'); // List all fields except deleted_at
        $this->db->from('m_product AS m');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array(); // Return as associative array
        }

        return null; // Return null if not found
    }


    public function createProduct($data)
    {
        // Add default values
        $data['sold'] = 0;
        $data['created_at'] = $this->now;
        $data['updated_at'] = $this->now;

        // Insert the data into the m_product table
        $inserted = $this->db->insert('m_product', $data);

        // Check if the insert was successful
        if ($inserted) {
            // Get the inserted product's ID
            $inserted_id = $this->db->insert_id();

            // Retrieve the newly created product data
            $this->db->where('id', $inserted_id);
            $query = $this->db->get('m_product');

            // Return the product data
            return $query->row_array(); // Return as associative array
        }

        // Return false if insertion failed
        return false;
    }

    public function updateProduct($id, $data)
    {
        // Add timestamp for updated_at
        $data['updated_at'] = $this->now;

        // First, check if the product exists
        $existingData = $this->getProduct($id);

        if (!$existingData) {
            return ['status' => 404]; // Product not found
        }

        // Update the product in the m_product table
        $this->db->where('id', $id);
        $updated = $this->db->update('m_product', $data);

        if ($updated) {
            // Return the updated product data
            return [
                'status' => 200,
                'data' => $this->getProduct($id)
            ];
        }

        return ['status' => 500]; // Updating failed
    }

    public function deleteProduct($id)
    {
        // Add timestamp for deleted_at
        $data['deleted_at'] = $this->now;

        // First, check if the product exists
        $existingData = $this->getProduct($id);

        if (!$existingData) {
            return ['status' => 404]; // Product not found
        }

        // Perform the soft delete by updating the deleted_at field
        $this->db->where('id', $id);
        $deleted = $this->db->update('m_product', $data); // Only update the deleted_at field

        $newData = $this->getProduct($id, true);

        if ($deleted) {
            // Return the soft-deleted product data
            return [
                'status' => 200,
                'data' => $newData
            ];
        }

        return ['status' => 500]; // Deletion failed
    }
}
