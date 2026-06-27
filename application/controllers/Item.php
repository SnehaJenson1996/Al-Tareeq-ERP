<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends CI_Controller {

	
	public function __construct() {
		parent::__construct();

		if (!$this->session->userdata('is_logged_in')) {
			redirect('Login/login');
		}
	
		// Prevent browser caching
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
		$this->output->set_header("Pragma: no-cache");

		$this->load->model('Item_model');
		$this->load->helper('menu_helper');
	}

	//brand_master
	public function list_brands()
	{
		$user = $this->session->userdata('user_id');
		if (!has_view_access($user, 'Item/list_brands')) {
			$data['title'] = 'Access Denied';
			$data['main_content'] = 'errors/access_control.php';
		} else {
			$data['title'] = 'Brands List';

			$data['all_brands'] = $this->Item_model->get_all_brand_list();

			$data['main_content'] = 'item/brand/list_brands.php';
		}

		$this->load->view('includes/template', $data);
	}

	function add_brand()
	{
		$user = $this->session->userdata('user_id');
		if (!has_access($user, 'Item/list_brands', 'A')) {
			$data['title'] = 'Access Denied';
			$data['main_content'] = 'errors/access_control.php';
		} else {
			$data['title'] = 'Add Brand ';


			$data['main_content'] = 'item/brand/add_brand.php';
		}
		$this->load->view('includes/template', $data);
	}

	function add_brand_data()
	{
		$source = $_POST['source'];
		$brand_name = $_POST['brand_name'];

		$id = $this->Item_model->add_brand_data();

		if ($id && $source == 0)
			redirect('Item/list_brands');
		else {
			echo json_encode([
				'status' => 'success',
				'id'     => $id,
				'name'   => $brand_name
			]);
		}
	}

	function edit_brand()
	{
		$user = $this->session->userdata('user_id');
		if (!has_access($user, 'Item/list_brands', 'E')) {
			$data['title'] = 'Access Denied';
			$data['main_content'] = 'errors/access_control.php';
		} else {
			$data['title'] = 'Edit Brand ';
			$brand_id = $this->uri->segment('3');
			$data['brand_details'] = $this->Item_model->get_brand_by_id($brand_id);

			$data['main_content'] = 'item/brand/edit_brand.php';
		}
		$this->load->view('includes/template', $data);
	}

	function update_brand_data()
	{
		$result = $this->Item_model->update_brand_data();

		if ($result) {
			echo 'Added';
		} else {
			echo 'Not Added';
		}
		redirect('Item/list_brands');
	}

	
	//items

	public function list_items(){
		$user = $this->session->userdata('user_id');
		if(!has_view_access($user,'Item/list_items')){
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
			$data['title']='Items List';        
			$data['all_items'] = $this->Item_model->get_all_item_list();
			//echo $this->db->last_query();exit();
			$data['main_content']='item/item/list_items.php';
		}
		$this->load->view('includes/template',$data);
		
	}

	public function add_item(){
		$user = $this->session->userdata('user_id');
		if(!has_access($user,'Item/list_items','A')){
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
			$data['title']='Add Item';
			
			//$data['active_brands'] = $this->Item_model->get_active_brand_list();
			$data['active_units'] = $this->Item_model->get_all_units();	
			$data['active_brands'] = $this->Item_model->get_active_brands();
			$data['main_content']='item/item/add_item.php';

		}
		$this->load->view('includes/template',$data);
	}
	public function add_item_data(){
			$this->load->helper(['form', 'url']);
			$this->load->library('upload');
			$this->load->library('form_validation');

			// Validation rules
			// $this->form_validation->set_rules('brand', 'Brand', 'required');
			// $this->form_validation->set_rules('item_name', 'Item Name', 'required');
			// $this->form_validation->set_rules('item_code', 'Item Code', 'required');
			// $this->form_validation->set_rules('unit', 'Unit', 'required');
			// $this->form_validation->set_rules('unit_price', 'Unit Price', 'required|numeric');

			// if ($this->form_validation->run() == FALSE) {
			// 	$data['active_units'] = $this->Item_model->get_all_units();	
			// 	redirect('Item/list_items');
			// } else {
				$item_data = [
					'item_name' => $this->input->post('item_name', true),
					'item_code' => $this->input->post('item_code', true),
					'item_brand'       => $this->input->post('item_brand', true),
					'item_unit'   => $this->input->post('unit', true),
					'unit_price'=> $this->input->post('unit_price', true),
					'item_description' => $this->input->post('item_description', true),
				];

				if (!empty($_FILES['item_image']['name'])) {
					$config['upload_path'] = './public/items/';
					$config['allowed_types'] = 'jpg|jpeg|png|gif';
					$config['max_size'] = 2048; // 2 MB
					$config['file_name'] = time() . '_' . preg_replace('/\s+/', '_', $_FILES['item_image']['name']);
					$config['detect_mime'] = true;  // Detect mime to restrict file types better
					$config['max_filename'] = 255;

					$this->upload->initialize($config);

					if (!$this->upload->do_upload('item_image')) {
						// $data['upload_error'] = $this->upload->display_errors();
						// $data['active_units'] = $this->Item_model->get_active_units();
						$this->session->set_flashdata('error', 'Failed to Upload file. Please try again.');
						redirect('Item/list_items');
						return;
					} else {
						$upload_data = $this->upload->data();
						$item_data['item_image'] = $upload_data['file_name'];
					}
				}

				$insert_id = $this->Item_model->insert_item($item_data);

				if ($insert_id) {
					$this->session->set_flashdata('success', 'Item added successfully!');
					redirect('Item/list_items');
				} else {
					$this->session->set_flashdata('error', 'Failed to add item. Please try again.');
					$data['active_units'] = $this->Item_model->get_active_units();
					$this->load->view('item/add_item_form', $data);
				}
			//}
		}
// public function add_item_data_purchase()
// {
//     // Load Item_model if not already loaded
//     $this->load->model('Item_model');

//     // Collect POST data
//     $data = [
//         'item_name'        => $this->input->post('item_name', true),
//         'item_code'        => $this->input->post('item_code', true),
//         'item_unit'        => $this->input->post('unit', true),
//         'item_brand'       => $this->input->post('brand', true),
//         'unit_price'       => $this->input->post('unit_price', true),
//         'item_description' => $this->input->post('item_description', true)
//     ];

//     // Handle file upload (optional)
//     if (!empty($_FILES['item_image']['name'])) {
//         $config['upload_path']   = './public/items/';
//         $config['allowed_types'] = 'jpg|jpeg|png|gif';
//         $config['file_name']     = time().'_'.$_FILES['item_image']['name'];
//         $config['overwrite']     = true;

//         $this->load->library('upload', $config);

//         if ($this->upload->do_upload('item_image')) {
//             $uploadData = $this->upload->data();
//             $data['item_image'] = $uploadData['file_name'];
//         } else {
//             echo json_encode([
//                 'status' => 'error',
//                 'message' => $this->upload->display_errors()
//             ]);
//             return;
//         }
//     }

//     // Insert item
//     $item_id = $this->Item_model->insert_item($data);

//     if ($item_id) {
//         echo json_encode([
//             'status'    => 'success',
//             'item_id'   => $item_id,
//             'item_name' => $data['item_name']
//         ]);
//     } else {
//         echo json_encode([
//             'status'  => 'error',
//             'message' => 'Unable to save item'
//         ]);
//     }
// }


	
	function import_item_master(){
		$data['title']='Import Items';
        $data['processed']='';
		if(isset($_FILES['item_file']['name'])){
			$this->load->library('upload');			
				$config['upload_path'] = './public/item_master_imports/';
				$config['allowed_types'] = 'csv';
				$this->upload->initialize($config);		
				if ($this->upload->do_upload('item_file')) {
					$file_data = $this->upload->data();
					$processed = FCPATH ."public/item_master_imports/" . $file_data['file_name'];
					$res = $this->Item_model->import_item_master($processed);
					echo $res;
				}	
		}
		else{
			$data['main_content']='item/import_item_master.php';
		 	$this->load->view('includes/template',$data);
		}
	}

	

public function delete_brand($brand_id = null) {
    if(!$brand_id) {
        $this->session->set_flashdata('error', 'Invalid Brand ID');
        redirect('Item/list_brands'); // adjust your redirect
    }

    // Optional: check user permissions here

    $this->db->where('brand_id', $brand_id);
    $deleted = $this->db->delete('brand_master'); // replace 'brands' with your table name

    if($deleted){
        $this->session->set_flashdata('success', 'Brand deleted successfully.');
    } else {
        $this->session->set_flashdata('error', 'Failed to delete brand.');
    }

    redirect('Item/list_brands');
}


}
