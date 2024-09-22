<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('product_model');
		$this->load->model('order_model');
	}

	// ================ PRODUCTS ======================================================================================

	public function products($id = null)
	{
		// Determine the HTTP request method
		$method = $this->input->server('REQUEST_METHOD');

		switch ($method) {
			case 'GET':
				$this->getProducts($id);
				break;

			case 'POST':
				$this->createProduct();
				break;

			case 'PUT':
				$this->updateProduct($id);
				break;

			case 'DELETE':
				$this->deleteProduct($id);
				break;

			default:
				// Return a 405 Method Not Allowed if not GET or POST
				return $this->jsonResponse(405, ['message' => 'Method Not Allowed']);
		}
	}

	private function getProducts($id)
	{
		// Fetch all products from the model
		$data = $this->product_model->getProduct($id);
		if ($id && !$data) {
			return $this->jsonResponse(404, ['message' => 'Product not found']);
		}

		$message = $id ? 'Product Detail' : 'Product List';

		// Structure the response
		$response = [
			'message' => $message,
			'data' => $data
		];

		// Return as JSON
		return $this->jsonResponse(200, $response);
	}

	private function createProduct()
	{
		// Get the POST data
		$input = $this->getInputData();

		// Define validation rules
		$rules = [
			'name' => 'required|is_unique[m_product.name]',
			'price' => 'required|numeric|greater_than_equal_to[1]',
			'stock' => 'required|numeric|greater_than_equal_to[0]'
		];

		// Validate the input
		$validationResponse = $this->validateInput($input, $rules);
		if ($validationResponse) {
			return $validationResponse; // Return validation errors
		}

		// If validation passes, call the model method to insert data
		$data = [
			'name' => $input['name'],
			'price' => $input['price'],
			'stock' => $input['stock']
		];

		$product = $this->product_model->createProduct($data);

		if (!$product) {
			// Return response
			return $this->jsonResponse(500, ['message' => 'Error while inserting product']);
		}
		// Return success response
		$response = [
			'message' => 'Product created successfully',
			'data' => $product
		];

		return $this->jsonResponse(201, $response);
	}

	private function updateProduct($id)
	{
		// Load the product model
		$this->load->model('product_model');

		$input = $this->getInputData();

		// Define validation rules
		$rules = [
			'price' => 'numeric|greater_than_equal_to[1]',
			'stock' => 'numeric|greater_than_equal_to[0]'
		];

		// Validate the input
		$validationResponse = $this->validateInput($input, $rules);
		if ($validationResponse) {
			return $validationResponse; // Return validation errors
		}

		// Update the product
		$updatedProduct = $this->product_model->updateProduct($id, $input);

		if ($updatedProduct['status'] === 200) {
			$response = [
				'message' => 'Product updated successfully',
				'data' => $updatedProduct['data']
			];
			return $this->jsonResponse($updatedProduct['status'], $response);
		} elseif ($updatedProduct['status'] === 500) {
			return $this->jsonResponse($updatedProduct['status'], ['message' => 'Error while updating product']);
		} else {
			return $this->jsonResponse($updatedProduct['status'], ['message' => 'Product not found']);
		}
	}

	private function deleteProduct($id)
	{
		// Load the product model
		$this->load->model('product_model');

		// Delete the product
		$deletedProduct = $this->product_model->deleteProduct($id);

		if ($deletedProduct['status'] === 200) {
			$response = [
				'message' => 'Product deleted successfully',
				'data' => $deletedProduct['data']
			];
			return $this->jsonResponse($deletedProduct['status'], $response);
		} elseif ($deletedProduct['status'] === 500) {
			return $this->jsonResponse($deletedProduct['status'], ['message' => 'Error while deleting product']);
		} else {
			return $this->jsonResponse($deletedProduct['status'], ['message' => 'Product not found']);
		}
	}

	// ================ ORDERS ======================================================================================

	public function orders($id = null)
	{
		// Determine the HTTP request method
		$method = $this->input->server('REQUEST_METHOD');

		switch ($method) {
			case 'GET':
				$this->getOrders($id);
				break;

			case 'POST':
				$this->createOrder();
				break;

			case 'DELETE':
				$this->deleteOrder($id);
				break;

			default:
				// Return a 405 Method Not Allowed if not GET or POST
				return $this->jsonResponse(405, ['message' => 'Method Not Allowed']);
		}
	}

	private function getOrders($id)
	{
		// Fetch all products from the model
		$data = $this->order_model->getOrder($id);
		if ($id && !$data) {
			return $this->jsonResponse(404, ['message' => 'Order not found']);
		}

		$message = $id ? 'Order Detail' : 'Order List';

		// Structure the response
		$response = [
			'message' => $message,
			'data' => $data
		];

		// Return as JSON
		return $this->jsonResponse(200, $response);
	}

	private function createOrder()
	{
		// Get the POST data
		$input = $this->getInputData();
		// var_dump($input);
		// die();

		// Initialize an array for validation errors
		$errors = [];

		// MANUAL BECAUSE PHP VALIDATION NOT WORKING PROPERLY
		// Manual validation for the 'products' field
		if (!isset($input['products']) || !is_array($input['products']) || count($input['products']) < 1) {
			$errors['products'] = 'The products field is required and must contain at least one product.';
		} else {
			foreach ($input['products'] as $index => $product) {
				// Check if 'id' is set and valid
				if (!isset($product['id']) || !is_numeric($product['id']) || $product['id'] < 1) {
					$errors["products.$index.id"] = 'Product ID is required and must be a positive number.';
				}

				// Check if 'quantity' is set and valid
				if (!isset($product['quantity']) || !is_numeric($product['quantity']) || $product['quantity'] < 1) {
					$errors["products.$index.quantity"] = 'Quantity is required and must be a positive number.';
				}
			}
		}

		// If there are any validation errors, return them
		if (!empty($errors)) {
			return $this->jsonResponse(400, [
				'message' => 'Validation failed',
				'errors' => $errors
			]);
		}
		// If validation passes, call the model method to insert data
		$data = [
			'products' => $input['products']
		];

		$order = $this->order_model->createOrder($data);

		if ($order['status'] === 400) {
			// Return response
			return $this->jsonResponse($order['status'], ['message' => 'Product out of stock']);
		} elseif ($order['status'] === 404) {
			// Return response
			return $this->jsonResponse($order['status'], ['message' => 'Product not found']);
		} elseif ($order['status'] === 500) {
			return $this->jsonResponse($order['status'], ['message' => 'Error while inserting order']);
		}

		$data = $this->order_model->getOrder($order['id_order']);

		// Return success response
		$response = [
			'message' => 'Order created',
			'data' => $data
		];

		return $this->jsonResponse(200, $response);
	}

	private function deleteOrder($id)
	{
		// Load the order model
		$this->load->model('order_model');

		// Delete the order
		$deletedOrder = $this->order_model->deleteOrder($id);

		if ($deletedOrder['status'] === 200) {
			$response = [
				'message' => 'Order deleted successfully',
				'data' => $deletedOrder['data']
			];
			return $this->jsonResponse($deletedOrder['status'], $response);
		} elseif ($deletedOrder['status'] === 500) {
			return $this->jsonResponse($deletedOrder['status'], ['message' => 'Error while deleting order']);
		} else {
			return $this->jsonResponse($deletedOrder['status'], ['message' => 'Order not found']);
		}
	}

	private function jsonResponse($statusCode, $response)
	{
		return $this->output
			->set_content_type('application/json')
			->set_status_header($statusCode)
			->set_output(json_encode($response));
	}

	private function getInputData()
	{
		// Check if the request is JSON
		if ($this->input->server('CONTENT_TYPE') == 'application/json') {
			// Handle raw JSON input
			return json_decode($this->input->raw_input_stream, true);
		} else {
			// Handle form-encoded or form-data input
			return $this->input->post();
		}
	}

	private function validateInput($input, $rules)
	{
		// Load the form_validation library
		$this->load->library('form_validation');

		// Set data for validation
		$this->form_validation->set_data($input);

		// Set validation rules
		foreach ($rules as $field => $fieldRules) {
			$this->form_validation->set_rules($field, ucfirst($field), $fieldRules);
		}

		// Validate the input data
		if ($this->form_validation->run() == FALSE) {
			// Validation failed, return errors
			$response = [
				'message' => 'Validation failed',
				'errors' => $this->form_validation->error_array()
			];
			return $this->jsonResponse(422, $response);
		}

		return null; // Return null if validation is successful
	}
}
