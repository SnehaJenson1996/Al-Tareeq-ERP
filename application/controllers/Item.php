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

	public function list_unit() {
		$data['title'] = "list units";
        $data['units'] = $this->Item_model->get_all_units();
        $data['main_content']='item/item/list_units.php';
		$this->load->view('includes/template',$data);
    }
	public function list_unit_ajax() {
		$this->db->select('unit_id, unit_name');
    	$this->db->from('unit_master'); // your unit table
    	$units = $this->db->get()->result_array();
    	echo json_encode($units);
    }
	public function add_unit() {
		$action = $this->input->post('action');
		if ($action == 'save') {
			$this->save_unit();
		} elseif ($action == 'update') {
			$this->update_unit();
		} else {
			$data['title'] = 'Add Unit';
			$data['main_content'] = 'item/item/add_unit.php';
			$this->load->view('includes/template', $data);
		}
    }
	private function save_unit(){  
		$this->load->model('Item_model');
		$unit_name = $this->input->post('unit_name', true);
		$unit_code = $this->input->post('unit_code', true);
		if (empty($unit_name) || empty($unit_code)) {
			$data['error'] = "Please fill in all required fields.";
			$data['title'] = 'Add Unit';
			$data['main_content'] = 'item/item/add_unit.php';
			$this->load->view('includes/template', $data);
			return;
		}
		$insert_data = [
			'unit_abbr' => $unit_name,
			'active'	=>1,
			'unit_name' => $unit_code,
			'created_on' => date('Y-m-d H:i:s'),
		];

		// Insert using model
		$insert_id = $this->Item_model->insert_unit($insert_data);

		if ($insert_id) {
			// Set success message and redirect to add_unit (or list page)
			$this->session->set_flashdata('success', 'Unit saved successfully!');
			redirect('Item/list_unit'); // change redirect as needed
		} else {
			// Show error message and reload form
			$data['error'] = "Failed to save unit. Please try again.";
			$data['title'] = 'Add Unit';
			$data['main_content'] = 'item/item/add_unit.php';
			$this->load->view('includes/template', $data);
		}
	}
	public function edit_unit($unit_id){
		$data['unit']=$this->Item_model->get_unit_by_id($unit_id);
		$data['title'] = 'Edit Unit';
		$data['main_content'] = 'item/item/add_unit.php';
		$this->load->view('includes/template', $data);
	}
	public function update_unit(){
		$unit_id=$this->input->post('unit_id');
		$data = [
			'unit_abbr' => $this->input->post('unit_name'),
			'unit_name' => $this->input->post('unit_code'),
			'updatd_on' => date('Y-m-d H:i:s'),
		];

		// Insert using model
		$updated = $this->Item_model->update_unit($data,$unit_id);

		if ($updated) {
			// Set success message and redirect to add_unit (or list page)
			$this->session->set_flashdata('success', 'Unit updated successfully!');
			redirect('Item/list_unit'); // change redirect as needed
		} else {
			// Show error message and reload form
			$data['error'] = "Failed to update unit. Please try again.";
			$data['title'] = 'Add Unit';
			$data['main_content'] = 'item/item/add_unit.php';
			$this->load->view('includes/template', $data);
		}

	}
	function delete_unit(){
		$unit_id=$this->input->post('id');
		$deleted=$this->Item_model->delete_unit($unit_id);
		if ($deleted) {
                echo json_encode([
                    'status' => 1,
                    'message' => 'unit deleted successfully.'
                ]);
            } else {
                echo json_encode([
                    'status' => 0,
                    'message' => 'Failed to delete unit. Try again.'
                ]);
            }
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


public function add_item_data_purchase()
{
    header('Content-Type: application/json');
    if (ob_get_length()) {
        ob_clean();
    }

    // Load Item_model if not already loaded
    $this->load->model('Item_model');

    // Collect POST data
    $data = [
        'item_name'        => $this->input->post('item_name', true),
        'item_code'        => $this->input->post('item_code', true),
        'item_unit'        => $this->input->post('unit', true),
        'item_brand'       => $this->input->post('brand', true),
        'unit_price'       => $this->input->post('unit_price', true),
        'item_description' => $this->input->post('item_description', true)
    ];

    // Handle file upload (optional)
    if (!empty($_FILES['item_image']['name'])) {
        $config['upload_path']   = './public/items/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name']     = time().'_'.preg_replace('/\s+/', '_', $_FILES['item_image']['name']);
        $config['overwrite']     = true;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('item_image')) {
            $uploadData = $this->upload->data();
            $data['item_image'] = $uploadData['file_name'];
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => $this->upload->display_errors()
            ]);
            exit;
        }
    }

    // Insert item
    $item_id = $this->Item_model->insert_item($data);

    if ($item_id) {
        echo json_encode([
            'status'    => 'success',
            'item_id'   => $item_id,
            'item_name' => $data['item_name']
        ]);
    } else {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Unable to save item'
        ]);
    }
    exit;
}


	public function edit_item(){
		$item_id = $this->uri->segment(3);
		$data['title']='Edit Item';
		$data['item'] = $this->Item_model->get_item_by_id($item_id);	
		$data['active_units'] = $this->Item_model->get_all_units();	
		$data['active_brands'] = $this->Item_model->get_active_brands();
		$data['main_content']='item/item/add_item.php';
		$this->load->view('includes/template',$data);

	}
	public function update_item(){
		$item_id = $this->uri->segment(3);
		$this->load->library('upload');
    	//$this->load->model('Item_model');

    // Collect form inputs
    $item_name        = $this->input->post('item_name');
    $item_code        = $this->input->post('item_code');
	$item_brand       = $this->input->post('item_brand');
    $unit             = $this->input->post('unit');
    $unit_price       = $this->input->post('unit_price');
    $item_description = $this->input->post('item_description');

    // Get existing item (to handle old image)
    $existing_item = $this->Item_model->get_item_by_id($item_id);

    $item_image = $existing_item['item_image']; // keep old image by default

    // Check if new image uploaded
    if (!empty($_FILES['item_image']['name'])) {
        $config['upload_path']   = './public/items/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name']     = time() . '_' . $_FILES['item_image']['name'];
        $config['overwrite']     = true;

        $this->upload->initialize($config);

        if ($this->upload->do_upload('item_image')) {
            $uploadData = $this->upload->data();
            $item_image = $uploadData['file_name'];

            // Delete old image if exists
            if (!empty($existing_item['item_image']) && file_exists('./public/items/'.$existing_item['item_image'])) {
                unlink('./public/items/'.$existing_item['item_image']);
            }
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('Item/edit_item/'.$item_id);
        }
    }

    // Prepare update data
    $data = array(
        'item_name'        => $item_name,
        'item_code'        => $item_code,
		'item_brand'       => $item_brand,
        'item_unit'        => $unit,
        'unit_price'       => $unit_price,
        'item_description' => $item_description,
        'item_image'       => $item_image,
        'updated_on'       => date('Y-m-d H:i:s')
    );

    // Update DB
    $this->Item_model->update_item($item_id, $data);

    $this->session->set_flashdata('success', 'Item updated successfully!');
    redirect('Item/list_items'); // adjust redirect path as per your app
	}
	public function get_item_by_id(){
		$item_id = $_POST['item_id'];
		$result = $this->Item_model->get_item_by_id($item_id);
		echo json_encode($result);
	}

	public function check_item_code_duplicate(){
		$result = $this->Item_model->check_item_code_duplicate();
		echo json_encode($result);
	}

	
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

	public function search_item()
	{
		$term = $this->input->get('q');
		$this->load->model('Item_model');
		$results = $this->Item_model->search_items($term);
		echo json_encode($results);
	}

	public function add_item_form() {
	$data['active_brand'] = $this->Item_model->get_active_brand_list();
    $data['active_units'] = $this->Item_model->get_all_units();
    $this->load->view('item/item/add_item_modal', $data); // create a view specifically for modal content
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

public function delete_item($item_id = null) {
    if(!$item_id) {
        $this->session->set_flashdata('error', 'Invalid Item ID');
        redirect('Item/list_items'); // adjust your redirect
    }

    // Optional: check user permissions here

    $this->db->where('item_id', $item_id);
    $deleted = $this->db->delete('item_master'); // replace 'brands' with your table name

    if($deleted){
        $this->session->set_flashdata('success', 'Item deleted successfully.');
    } else {
        $this->session->set_flashdata('error', 'Failed to delete brand.');
    }

    redirect('Item/list_items');
}
}
