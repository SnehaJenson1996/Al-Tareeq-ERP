<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends CI_Controller {

public function __construct() {
		parent::__construct();

        if (!$this->session->userdata('is_logged_in')) {
			redirect('Login/login');
		}
        $this->load->model('Company_model');
        $this->load->helper('menu_helper');
		
		// Prevent browser caching
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
		$this->output->set_header("Pragma: no-cache");
	}
//Branch
function list_branch(){   
        $data['title'] = 'Branch Management';
        $raw_input = $this->input->post('filter');  
        
        if (!empty($raw_input)) {
        $filter_type = '';
        $filter_value = '';

        if (strpos($raw_input, ':') !== false) {
            list($filter_type, $filter_value) = explode(':', $raw_input, 2);
            $filter_type = trim($filter_type);
            $filter_value = trim($filter_value);
            $column_map = [
                            'Branch Name' => 'branch_name',
                            'Branch Code' => 'branch_code',
                            'Branch Manager'=>'branch_manager',
                            'TRN'=>'branch_trn',
                            'Contact Number'=>'branch_contact'
                        ];

             $column = isset($column_map[$filter_type]) ? $column_map[$filter_type] : null;
             $data['AllBranches']=$this->Company_model->get_all_branches($column,$filter_value);
        }else{
            $data['AllBranches']=$this->Company_model->get_all_branches();
        }
    }else{
         $data['AllBranches']=$this->Company_model->get_all_branches();
    }        
      
        $data['main_content']='company/list_branch.php';
        $this->load->view('includes/template',$data);
    }
function branch(){   
        $data['title'] = 'Branch Management';
        //$data['AllBranches']=$this->Company_model->get_all_branches();
      //  $data['filterdata']=
        $data['branch_code'] = $this->Company_model->generate_branch_code();
        $data['main_content']='company/branch_manager.php';
        $this->load->view('includes/template',$data);
    }
public function add_branch_data() {
    $user = $this->session->userdata('user_id');

    if (!has_access($user, 'Company/list_branch', 'A')) {
        $data['title'] = 'Access Denied';
        $data['main_content'] = 'errors/access_control.php';
        $this->load->view('includes/template', $data);
        return;
    }else{    
    // if (!$this->validate_branch_data()) {
    //     $this->session->set_flashdata('error', validation_errors());
    //     redirect('Company/branch');
    // }

    $data = [
        'branch_code'     => $this->Company_model->generate_branch_code(),
        'branch_name'     => $this->input->post('branch_name'),
        'branch_manager'  => $this->input->post('branch_manager'),
        'branch_contact'  => $this->input->post('branch_contact'),
        'branch_email'    => $this->input->post('branch_email'),
        'branch_web'    => $this->input->post('branch_website'),
        'branch_trn'    => $this->input->post('branch_trn'),
        'branch_location' => $this->input->post('branch_location'),
        'branch_address'  => $this->input->post('branch_address'),
        'created_on'      => date('Y-m-d H:i:s')
    ];
    $file_fields = ['branch_logo', 'branch_header', 'branch_footer', 'branch_stamp'];
    foreach ($file_fields as $field) {
        if (!empty($_FILES[$field]['name'])) {
            $uploaded_file = $this->upload_branch_file($field, $data['branch_code']);
            if ($uploaded_file) {
                $data[$field] = $uploaded_file;
            } else {
                $this->session->set_flashdata('error', "Failed to upload $field. Check file format/size.");
                redirect('Company/branch');
                return;
            }
        }
    }

    $branch_id = $this->Company_model->insert_branch_data($data);

    if ($branch_id) {
        // Collect bank account fields
        $bank_names     = $this->input->post('bname');
        $bank_accs      = $this->input->post('bacc');
        $bank_branches  = $this->input->post('bbranch');
        $bank_ibans     = $this->input->post('biban');
        $bank_swifts    = $this->input->post('bswift');

        $bank_data = [];

        // Loop through banks
        for ($i = 0; $i < count($bank_names); $i++) {
            if (!empty($bank_names[$i])) {
                $bank_data[] = [
                    'branch_id'    => $branch_id,
                    'bank_name'    => $bank_names[$i],
                    'bank_account' => $bank_accs[$i] ?? '',
                    'bank_branch'  => $bank_branches[$i] ?? '',
                    'bank_iban'    => $bank_ibans[$i] ?? '',
                    'bank_swift'   => $bank_swifts[$i] ?? ''
                ];
            }
        }
        // Insert bank data if available
        if (!empty($bank_data)) {
            $insert_status = $this->Company_model->insert_branch_bank_details($bank_data);
            if ($insert_status) {
                $this->session->set_flashdata('success', 'Branch added successfully.');
            } else {
                $this->session->set_flashdata('error', 'Branch created, but failed to add bank details.');
            }
        } else {
            $this->session->set_flashdata('success', 'Branch added successfully (no bank data).');
        }

    } else {
        $this->session->set_flashdata('error', 'Failed to add branch. Try again.');
    }

    redirect('Company/list_branch');
    }
}
function edit_branch($id){   
     $user = $this->session->userdata('user_id');
    if (!has_access($user, 'Company/list_branch', 'E')) {
        $data['title'] = 'Access Denied';
        $data['main_content'] = 'errors/access_control.php';
        $this->load->view('includes/template', $data);
        return;
    }else{  
        $data['title'] = 'Branch Management';     
        $data['branch_data']=$this->Company_model->get_branch_by_id($id);
        $data['branch_bank']=$this->Company_model->get_branch_bank_by_id($id);
        $data['branch_id']=$id;
        // print_r($data['branch_bank']);exit();
        $data['main_content']='company/branch_edit.php';
        $this->load->view('includes/template',$data);
    }
}
public function update_branch_data() {
    $branch_id = $this->input->post('branch_id');

    // Validate form data
    // if (!$this->validate_branch_data($branch_id)) {
    //     $this->session->set_flashdata('error', validation_errors());
    //     redirect('Company/edit_branch/' . $branch_id);
    // }

    // Upload files if provided
    $branch_logo = $this->upload_branch_file('branch_logo', 'branch_logo_' . $branch_id);
    $branch_header = $this->upload_branch_file('branch_header', 'header_' . $branch_id);
    $branch_footer = $this->upload_branch_file('branch_footer', 'footer_' . $branch_id);
    $branch_stamp = $this->upload_branch_file('branch_stamp', 'stamp_' . $branch_id);

    // Branch main data
    $branch_data = [
        'branch_code'     => $this->input->post('branch_code'),
        'branch_name'     => $this->input->post('branch_name'),
        'branch_manager'  => $this->input->post('branch_manager'),
        'branch_contact'  => $this->input->post('branch_contact'),
        'branch_email'    => $this->input->post('branch_email'),
        'branch_web'      => $this->input->post('branch_website'),
        'branch_trn'      => $this->input->post('branch_trn'),
        'branch_location' => $this->input->post('branch_location'),
        'branch_address'  => $this->input->post('branch_address'),
        'updated_on'      => date('Y-m-d H:i:s')
    ];

    if ($branch_logo) $branch_data['branch_logo'] = $branch_logo;
    if ($branch_header) $branch_data['branch_header'] = $branch_header;
    if ($branch_footer) $branch_data['branch_footer'] = $branch_footer;
    if ($branch_stamp) $branch_data['branch_stamp'] = $branch_stamp;

    // Update branch table
    $this->Company_model->update_branch($branch_id, $branch_data);

    // Delete old bank records and insert new ones
    $this->Company_model->delete_branch_bank($branch_id);

    $bname   = $this->input->post('bname');
    $bacc    = $this->input->post('bacc');
    $bbranch = $this->input->post('bbranch');
    $biban   = $this->input->post('biban');
    $bswift  = $this->input->post('bswift');

    $bank_data = [];
    for ($i = 0; $i < count($bname); $i++) {
        if (!empty($bname[$i])) {
            $bank_data[] = [
                'branch_id'    => $branch_id,
                'bank_name'    => $bname[$i],
                'bank_account' => $bacc[$i],
                'bank_branch'  => $bbranch[$i],
                'bank_iban'    => $biban[$i],
                'bank_swift'   => $bswift[$i]
            ];
        }
    }
    if (!empty($bank_data)) {
        $this->Company_model->insert_branch_bank_details($bank_data);
    }

    $this->session->set_flashdata('success', 'Branch updated successfully.');
    redirect('Company/list_branch');
}
private function upload_branch_file($field_name, $branch_code) {
    $this->load->library('upload');
    $folder_name = '';
    if (strpos($field_name, 'branch_header') !== false) {
        $folder_name = 'header';
    } elseif (strpos($field_name, 'branch_footer') !== false) {
        $folder_name = 'footer';
    } elseif (strpos($field_name, 'branch_stamp') !== false) {
        $folder_name = 'stamp';
    } else {
        $folder_name = 'branch_logo'; 
    }
    $upload_dir = './public/' . $folder_name . '_' . $branch_code . '/';

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $config = [
        'upload_path'   => $upload_dir,
        'allowed_types' => 'jpeg|jpg|png|pdf',
        'max_size'      => 2048, // 2MB
        'encrypt_name'  => TRUE,
        'overwrite'     => FALSE,
    ];

    $this->upload->initialize($config);

    if ($this->upload->do_upload($field_name)) {
        $file_data = $this->upload->data();
        return $upload_dir . $file_data['file_name']; // relative path
    } else {
        log_message('error', $this->upload->display_errors()); // optional: log for debugging
        return false;
    }
}
function upload_branch_logo(){
        $this->load->library('upload');
        $config['upload_path'] = './public/branch_logo/';
        $config['allowed_types'] = 'jpeg|jpg|pdf|png';
        $config['max_size'] = 2048; // optional, in KB
        $this->upload->initialize($config);

            if ($this->upload->do_upload('branch_logo')) {
                $file_data = $this->upload->data();
                return $file_data['file_name']; // Return uploaded filename
            } else {
                 return false;
            }
    }

public function get_branch_by_id() {
    $branch_id = $this->input->post('id');
    $branch_data_by_id = $this->Company_model->get_branch_by_id($branch_id); 

    if (!empty($branch_data_by_id)) {
        $data['branch_data_by_id'] = is_array($branch_data_by_id) ? array_shift($branch_data_by_id) : $branch_data_by_id;
        $this->load->view('Company/branch_edit_modal', $data);
    } else {
        $this->session->set_flashdata('error', "Data does not exist. Wrong ID.");
        redirect('Company/branch');
    }
}

public function delete_branch_record() {
    $user = $this->session->userdata('user_id');
    if (!has_access($user,'Company/list_branch','D')) {
        $data['title'] = 'Access Denied';
		$data['main_content']='errors/access_control.php';
    }else{    
            $id = $this->input->post("id");
            $is_exist=$this->Company_model->get_branch_by_id($id);
            if (empty($id) || empty($is_exist)) {
                echo json_encode([
                    'status' => 0,
                    'message' => 'Invalid branch ID or branch does not exist.'
                ]);
                return;
            }

            // if ($this->Company_model->is_branch_used_in_supplier($id)) {
            //     echo json_encode([
            //         'status' => 0,
            //         'message' => 'Cannot delete. Supplier(s) exist under this branch.'
            //     ]);
            //     return;
            // }

            // if ($this->Company_model->is_branch_used_in_customer($id)) {
            //     echo json_encode([
            //         'status' => 0,
            //         'message' => 'Cannot delete. Customer(s) exist under this branch.'
            //     ]);
            //     return;
            // }
             $this->Company_model->delete_branch_bank($id);
            $deleted = $this->Company_model->delete_branch_record($id);
            if ($deleted) {
                echo json_encode([
                    'status' => 1,
                    'message' => 'Branch deleted successfully.'
                ]);
            } else {
                echo json_encode([
                    'status' => 0,
                    'message' => 'Failed to delete branch. Try again.'
                ]);
            }
        }
}
// Customer
public function list_customers(){
		$user = $this->session->userdata('user_id');
		if(!has_view_access($user,'Company/list_customers')){
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
			$data['title']='Customers List';        
			$data['all_customers'] = $this->Company_model->get_all_customer_list();
			$data['main_content']='Company/list_customers.php';
		}
		
		$this->load->view('includes/template',$data);
}
public function add_customer(){
		$user = $this->session->userdata('user_id');
		if(!has_access($user,'Company/list_customers','A')){
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
			$data['title']='Add Customer';
            $data['customer_code'] = $this->Company_model->generate_customer_code();
            $data['branch_list']=$this->Company_model->get_all_branches();
        	$data['main_content']='Company/add_customer.php';
		}
		
		$this->load->view('includes/template',$data);
	}
public function save_customer() {
    $this->validate_customer();
    if ($this->form_validation->run() == FALSE) {
        $data['title'] = 'Add Customer';
        $data['customer_code'] = $this->Company_model->generate_customer_code();
        $data['branch_list'] = $this->Company_model->get_all_branches();
        $data['main_content'] = 'Company/add_customer.php';
        $this->load->view('includes/template', $data);
    } else {
        // File Upload
        $file_type = '';
        $licence_file_name = '';
        if (!empty($_FILES['trade_license_file']['name'])) {
            $config['upload_path']   = './public/customer/';
            $config['allowed_types'] = 'pdf|jpg|jpeg|png';
            $config['max_size']      = 2048;
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('trade_license_file')) {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect('Company/add_customer');
            } else {
                $upload_data = $this->upload->data();
                $licence_file_name = $upload_data['file_name'];
                $ext = strtolower($upload_data['file_ext']);

                $file_type = ($ext == '.pdf') ? 'pdf' : 'image';
            }
        }
        // Prepare Customer Data
        $data = [
            'branch_id'          => $this->input->post('branch'),
            'customer_name'      => $this->input->post('customer_name'),
            'customer_code'      => $this->input->post('customer_code'),
            'customer_email'     => $this->input->post('customer_email'),
            'contact_number'     => $this->input->post('customer_number'),
            'emirate'            => $this->input->post('emirate'),
            'customer_address'   => $this->input->post('customer_address'),
            'customer_TR_no'     => $this->input->post('trn_no'),
            'customer_TL_no'     => $this->input->post('trade_license_no'),
            'licence_issue_date' => $this->input->post('license_issue_date'),
            'licence_exp_date'   => $this->input->post('license_expiry_date'),
            'file_type'          => $file_type,
            'licence_file'       => $licence_file_name
        ];

        $customer_id = $this->Company_model->insert_customer($data);

        if ($customer_id) {
            $contact_names  = $this->input->post('contact_name');
            $contact_phones = $this->input->post('contact_phone');
            $contact_emails = $this->input->post('contact_email');

            $contacts = [];
            for ($i = 0; $i < count($contact_names); $i++) {
                if (!empty($contact_names[$i])) {
                    $contacts[] = [
                        'customer_id'   => $customer_id,
                        'contact_name'  => $contact_names[$i],
                        'contact_phone' => $contact_phones[$i] ?? '',
                        'contact_email' => $contact_emails[$i] ?? ''
                    ];
                }
            }

            if (!empty($contacts)) {
                $status = $this->Company_model->insert_customer_contacts($contacts);
                if ($status) {
                    $this->session->set_flashdata('success', 'Customer inserted successfully.');
                } else {
                    $this->session->set_flashdata('error', 'Customer saved, but contact person(s) failed.');
                }
            } else {
                $this->session->set_flashdata('success', 'Customer inserted successfully.');
            }
        } else {
            $this->session->set_flashdata('error', 'An error occurred while saving the customer.');
        }

        redirect('Company/list_customers');
    }
}

public function edit_customer($id){
    $user = $this->session->userdata('user_id');
		if(!has_access($user,'Company/list_customers','E')){
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
            $data['title']='Edit Customer';
            $data['customer_id']=$id;
            $data['branch_list']=$this->Company_model->get_all_branches();
            $customer = $this->Company_model->get_customer_by_id($id);
            $data['customer_data'] = array_shift($customer);
            $data['contact_data'] = $this->Company_model->get_customer_contact_by_cust_id($id);            
            $data['main_content']='Company/edit_customer.php';

            $this->load->view('includes/template',$data);
        }
    
}
public function update_customer() {
    
    $customer_id = $this->input->post('customer_id');
    // Collect core form values
    $data = [
        'branch_id'           => $this->input->post('branch'),
        'customer_name'       => $this->input->post('customer_name'),
        'customer_code'       => $this->input->post('customer_code'),
        'customer_email'      => $this->input->post('customer_email'),
        'contact_number'      => $this->input->post('customer_number'),
        'customer_address'    => $this->input->post('customer_address'),
        'customer_TR_no'      => $this->input->post('trn_no'),
        'customer_TL_no'      => $this->input->post('trade_license_no'),
        'licence_issue_date'  => $this->input->post('license_issue_date'),
        'licence_exp_date'    => $this->input->post('license_expiry_date'),
        'emirate'             => $this->input->post('emirate'),
        'customer_address'    => $this->input->post('customer_address')
    ];
    
    // Handle file upload
    if (!empty($_FILES['trade_license_file']['name'])) {
        $config['upload_path']   = './public/customer/';
        $config['allowed_types'] = 'pdf|jpg|jpeg|png';
        $config['max_size']      = 2048; // 2MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);
        if ($this->upload->do_upload('trade_license_file')) {
            $upload_data = $this->upload->data();
            $data['licence_file'] = $upload_data['file_name'];

            // Determine file type
            $mime = $upload_data['file_type'];
            if (in_array($mime, ['application/pdf'])) {
                $data['file_type'] = 'pdf';
            } else if (in_array($mime, ['image/jpeg', 'image/png', 'image/jpg'])) {
                $data['file_type'] = 'image';
            }
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('Company/edit_customer/' . $customer_id);
            return;
        }
    }
    // Update customer
    $this->Company_model->update_customer($customer_id, $data);

    // Update contact persons
    $this->Company_model->delete_customer_contacts($customer_id);

    $contact_names  = $this->input->post('contact_name');
    $contact_phones = $this->input->post('contact_phone');
    $contact_emails = $this->input->post('contact_email');

    $contacts = [];
    for ($i = 0; $i < count($contact_names); $i++) {
        if (!empty($contact_names[$i])) {
            $contacts[] = [
                'customer_id'   => $customer_id,
                'contact_name'  => $contact_names[$i],
                'contact_phone' => $contact_phones[$i] ?? '',
                'contact_email' => $contact_emails[$i] ?? ''
            ];
        }
    }

    if (!empty($contacts)) {
        $this->Company_model->insert_customer_contacts($contacts);
    }

    $this->session->set_flashdata('success', 'Customer updated successfully.');
    redirect('Company/list_customers');
}

public function delete_customer() {
    $user = $this->session->userdata('user_id');
    if (!has_access($user,'Company/branch','D')) {
        $data['title'] = 'Access Denied';
		$data['main_content']='errors/access_control.php';
    }else{    
            $id = $this->input->post("id");
            $is_exist=$this->Company_model->get_customer_by_id($id);
            if (empty($id) || empty($is_exist)) {
                echo json_encode([
                    'status' => 0,
                    'message' => 'Invalid branch ID or branch does not exist.'
                ]);
                return;
            }

            // if ($this->Company_model->is_branch_used_in_supplier($id)) {
            //     echo json_encode([
            //         'status' => 0,
            //         'message' => 'Cannot delete. Supplier(s) exist under this branch.'
            //     ]);
            //     return;
            // }

            // if ($this->Company_model->is_branch_used_in_customer($id)) {
            //     echo json_encode([
            //         'status' => 0,
            //         'message' => 'Cannot delete. Customer(s) exist under this branch.'
            //     ]);
            //     return;
            // }
           $this->Company_model->delete_customer_contacts($id);
            $deleted = $this->Company_model->delete_customer($id);

            if ($deleted) {
                echo json_encode([
                    'status' => 1,
                    'message' => 'Customer deleted successfully.'
                ]);
            } else {
                echo json_encode([
                    'status' => 0,
                    'message' => 'Failed to delete Customer. Try again.'
                ]);
            }
        }
}

public function get_customers_by_branch()
{
    $branch_id = $this->input->post('branch_id');
    $customers = $this->Company_model->get_customers_by_branch($branch_id);
    echo json_encode($customers);
}
//Supplier
public function list_supplier(){
    $user = $this->session->userdata('user_id');
    if(!has_view_access($user,'Company/list_supplier')){
        $data['title'] = 'Access Denied';
        $data['main_content']='errors/access_control.php';
    }
    else{
        $data['title']='Supplier List';        
        $data['all_suppliers'] = $this->Company_model->get_all_supplier_list();
        $data['main_content']='Company/list_supplier.php';
    }
    
    $this->load->view('includes/template',$data);
}
public function add_supplier(){
		$user = $this->session->userdata('user_id');
		if(!has_access($user,'Company/list_supplier','A')){
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
			$data['title']='Add Supplier';  
            $data['supplier_code'] = $this->Company_model->generate_supplier_code();          
            $data['branch_list']=$this->Company_model->get_all_branches();
        	$data['main_content']='Company/add_supplier.php';
		}
		
		$this->load->view('includes/template',$data);
	}
public function save_supplier() {
    // Handle Trade License File Upload
    $trade_license_file = '';
    $trade_license_type = '';

    if (!empty($_FILES['trade_license_file']['name'])) {
        $config['upload_path']   = './public/supplier/';
        $config['allowed_types'] = 'pdf|jpg|jpeg|png|doc|docx';
        $config['max_size']      = 2048; // 2MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('trade_license_file')) {
            $upload_data = $this->upload->data();
            $trade_license_file = $upload_data['file_name'];
            $trade_license_type = $upload_data['file_type'];
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('Company/add_supplier'); // or wherever your form is
        }
    }

    // Build main supplier data array
    $data = array(
        'supplier_code'         => $this->input->post('supplier_code'),
        'supplier_name'         => $this->input->post('supplier_name'),
        'supplier_type'         => $this->input->post('supplier_type'),
        'trn_no'                => $this->input->post('trn_no'),
        'remarks'               => "Remarks",
        'website'               => $this->input->post('company_website'),
        'supplier_email'        => $this->input->post('supplier_email'),
        'contact_number'        => $this->input->post('supplier_contact'),
        'billing_address'       => $this->input->post('billing_address'),
        'billing_city'          => $this->input->post('billing_city'),
        'billing_state'         => $this->input->post('billing_state'),
        'billing_country'       => $this->input->post('billing_country'),
        'billing_po_box'        => $this->input->post('billing_pobox'),
        'shipping_address'      => $this->input->post('shipping_address'),
        'shipping_city'         => $this->input->post('shipping_city'),
        'shipping_state'        => $this->input->post('shipping_state'),
        'shipping_country'      => $this->input->post('shipping_country'),
        'shipping_po_box'       => $this->input->post('shipping_pobox'),
        'active'                => 1,
        'created_by'            => $this->session->userdata('user_id'),
        'created_date'          => date('Y-m-d H:i:s'),
        'bank_name'             => $this->input->post('bank_name'),
        'bank_account'          => $this->input->post('account_no'),
        'bank_branch'           => $this->input->post('branch_name'),
        'bank_IBAN'             => $this->input->post('iban'),
        'bank_swift'            => $this->input->post('swift'),
        'intermidiate_Bname'    => $this->input->post('inter_bank_name'),
        'intermidiate_Bacc'     => $this->input->post('inter_account_no'),
        'intermidiate_Bbranch'  => $this->input->post('inter_branch'),
        'intermidiate_IBAN'     => $this->input->post('inter_iban'),
        'intermidiate_swift'    => $this->input->post('inter_swift'),

        // New Trade License Fields
        'trade_licence_no'      => $this->input->post('trade_license_no'),
        'trl_issued_date'       => $this->input->post('issued_date'),
        'trl_expire_date'       => $this->input->post('expiry_date'),
        'tr_file'               => $trade_license_file,
        'tr_file_type'          => $trade_license_type,
        'created_on'            => date('Y-m-d H:i:s')
    );

    $supplier_id = $this->Company_model->insert_supplier($data);

    // Insert contact persons if any
    $contact_names  = $this->input->post('contact_person');
    $contact_phones = $this->input->post('contact_mobile');
    $contact_emails = $this->input->post('contact_email');

    if (!empty($contact_names)) {
        $contacts_data = [];
        for ($i = 0; $i < count($contact_names); $i++) {
            if (!empty($contact_names[$i])) {
                $contacts_data[] = [
                    'supplier_id'   => $supplier_id,
                    'contact_name'  => $contact_names[$i],
                    'contact_phone' => $contact_phones[$i] ?? '',
                    'contact_email' => $contact_emails[$i] ?? '',
                ];
            }
        }

        if (!empty($contacts_data)) {
            $this->Company_model->insert_supplier_contacts($contacts_data);
        }
    }

    $this->session->set_flashdata('success', 'Supplier saved successfully.');
    redirect('Company/list_supplier');
}

public function edit_supplier($id){
    $user = $this->session->userdata('user_id');
		if(!has_access($user,'Company/list_supplier','E')){
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
            $data['title']='Edit Supplier';
            $data['supplier_id']=$id;
            $data['branch_list']=$this->Company_model->get_all_branches();
            $customer = $this->Company_model->get_supplier_by_id($id);
            $data['supplier'] = array_shift($customer);
            $data['contacts'] = $this->Company_model->get_supplier_contact_by_sup_id($id);
            $data['main_content']='Company/edit_supplier.php';
            $this->load->view('includes/template',$data);
        }
    
}
public function update_supplier() {
    $supplier_id = $this->input->post('supplierid');

    // Handle trade license file upload
    $tr_file = '';
    $tr_file_type = '';
    if (!empty($_FILES['trade_license_file']['name'])) {
        $config['upload_path'] = './public/supplier/';
        $config['allowed_types'] = 'pdf|jpg|jpeg|png';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = TRUE;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('trade_license_file')) {
            $upload_data = $this->upload->data();
            $tr_file = $upload_data['file_name'];
            $tr_file_type = $upload_data['file_ext'];
        } else {
            $this->session->set_flashdata('error', 'Trade License file upload failed: ' . $this->upload->display_errors());
            redirect('Company/edit_supplier/' . $supplier_id);
        }
    } else {
        // Keep existing file if no new upload
        $tr_file = $this->input->post('existing_trade_file');
        $tr_file_type = pathinfo($tr_file, PATHINFO_EXTENSION);
    }

    $data = array(
        'supplier_code'         => $this->input->post('supplier_code'),
        'supplier_name'         => $this->input->post('supplier_name'),
        'supplier_type'         => $this->input->post('supplier_type'),
        'trn_no'                => $this->input->post('trn_no'),
        'remarks'               => "Remarks",
        'website'               => $this->input->post('company_website'),
        'supplier_email'        => $this->input->post('supplier_email'),
        'contact_number'        => $this->input->post('supplier_contact'),
        'billing_address'       => $this->input->post('billing_address'),
        'billing_city'          => $this->input->post('billing_city'),
        'billing_state'         => $this->input->post('billing_state'),
        'billing_country'       => $this->input->post('billing_country'),
        'billing_po_box'        => $this->input->post('billing_pobox'),
        'shipping_address'      => $this->input->post('shipping_address'),
        'shipping_city'         => $this->input->post('shipping_city'),
        'shipping_state'        => $this->input->post('shipping_state'),
        'shipping_country'      => $this->input->post('shipping_country'),
        'shipping_po_box'       => $this->input->post('shipping_pobox'),
        'remark'                => "Remarks",
        'active'                => 1,
        'created_by'            => $this->session->userdata('user_id'),
        'created_date'          => date('Y-m-d H:i:s'),
        'bank_name'             => $this->input->post('bank_name'),
        'bank_account'          => $this->input->post('account_no'),
        'bank_branch'           => $this->input->post('branch_name'),
        'bank_IBAN'             => $this->input->post('iban'),
        'bank_swift'            => $this->input->post('swift'),
        'intermidiate_Bname'    => $this->input->post('inter_bank_name'),
        'intermidiate_Bacc'     => $this->input->post('inter_account_no'),
        'intermidiate_Bbranch'  => $this->input->post('inter_branch'),
        'intermidiate_IBAN'     => $this->input->post('inter_iban'),
        'intermidiate_swift'    => $this->input->post('inter_swift'),

        // New Trade License fields
        'trade_licence_no'      => $this->input->post('trade_license_no'),
        'trl_issued_date'       => $this->input->post('issued_date'),
        'trl_expire_date'       => $this->input->post('expiry_date'),
        'tr_file'               => $tr_file,
        'tr_file_type'          => $tr_file_type,
    );
    $this->Company_model->update_supplier($supplier_id, $data);

    // Update contacts
    $this->Company_model->delete_supplier_contact($supplier_id);
    $contact_names  = $this->input->post('contact_person');
    $contact_phones = $this->input->post('contact_mobile');
    $contact_emails = $this->input->post('contact_email');

    if (!empty($contact_names)) {
        $contacts_data = [];

        for ($i = 0; $i < count($contact_names); $i++) {
            if (!empty($contact_names[$i])) {
                $contacts_data[] = [
                    'supplier_id'   => $supplier_id,
                    'contact_name'  => $contact_names[$i],
                    'contact_phone' => $contact_phones[$i] ?? '',
                    'contact_email' => $contact_emails[$i] ?? '',
                ];
            }
        }

        if (!empty($contacts_data)) {
            $this->Company_model->insert_supplier_contacts($contacts_data);
        }
    }

    $this->session->set_flashdata('success', 'Supplier updated successfully.');
    redirect('Company/list_supplier');
}

public function delete_supplier() {
    $user = $this->session->userdata('user_id');
    if (!has_access($user,'Company/list_supplier','D')) {
        $data['title'] = 'Access Denied';
		$data['main_content']='errors/access_control.php';
    }else{    
            $id = $this->input->post("id");
            $is_exist=$this->Company_model->get_supplier_by_id($id);
            if (empty($id) || empty($is_exist)) {
                echo json_encode([
                    'status' => 0,
                    'message' => 'Invalid supplier ID or supplier does not exist.'
                ]);
                return;
            }

            // if ($this->Company_model->is_branch_used_in_supplier($id)) {
            //     echo json_encode([
            //         'status' => 0,
            //         'message' => 'Cannot delete. Supplier(s) exist under this branch.'
            //     ]);
            //     return;
            // }

            // if ($this->Company_model->is_branch_used_in_customer($id)) {
            //     echo json_encode([
            //         'status' => 0,
            //         'message' => 'Cannot delete. Customer(s) exist under this branch.'
            //     ]);
            //     return;
            // }
           $this->Company_model->delete_supplier_contact($id);
            $deleted = $this->Company_model->delete_supplier($id);

            if ($deleted) {
                echo json_encode([
                    'status' => 1,
                    'message' => 'Supplier deleted successfully.'
                ]);
            } else {
                echo json_encode([
                    'status' => 0,
                    'message' => 'Failed to delete Supplier. Try again.'
                ]);
            }
        }
}
//Employee
public function list_employee() {
    $user = $this->session->userdata('user_id');
    if(!has_view_access($user,'Company/list_supplier')){
        $data['title'] = 'Access Denied';
        $data['main_content']='errors/access_control.php';
    }
    else{
        $data['title'] = 'Employee List'; 
        $raw_input = $this->input->post('filter');

        if (!empty($raw_input)) {
            list($filter_type, $filter_value) = explode(':', $raw_input, 2);
            $filter_type = trim($filter_type);
            $filter_value = trim($filter_value);

            $column_map = [
                'Employee Name' => 'employee_name',
                'Employee Code' => 'employee_code'
                // No 'Designation' here, handled separately
            ];

            if ($filter_type === 'Designation') {
                // Get the designation ID
                $designation = $this->db->where('designation_name', $filter_value)
                                        ->get('designation_master')
                                        ->row();

                if ($designation) {
                    // Fetch using the ID condition
                    $data['employee_list'] = $this->Company_model->get_all_employees('designation_id', $designation->id);
                } else {
                    // No match, return empty list
                    $data['employee_list'] = [];
                }

            } else {
                // Use column mapping for name/code
                $column = isset($column_map[$filter_type]) ? $column_map[$filter_type] : null;
                if ($column && $filter_value !== '') {
                    $data['employee_list'] = $this->Company_model->get_all_employees($column, $filter_value);
                } else {
                    $data['employee_list'] = $this->Company_model->get_all_employees();
                }
            }

        } else {
            // No filter input — fetch all employees
            $data['employee_list'] = $this->Company_model->get_all_employees();
        }

        $data['main_content']='Company/list_employees.php';
    }
    
    $this->load->view('includes/template',$data);
}
public function add_employee(){
    $user = $this->session->userdata('user_id');
		if(!has_access($user,'Company/list_employee','A')){
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
			$data['title']='Add Employee';
            $data['branch_list']=$this->Company_model->get_all_branches();
             $data['designation_list']=$this->Company_model->get_all_designations();
        	$data['main_content']='Company/add_employee.php';
		}
		
		$this->load->view('includes/template',$data);
}
public function save_employee() {
    $post = $this->input->post();
    
    $photo = $this->_upload_file('employee_photo');
    $labor_card = $this->_upload_file('labor_card_image');
    $eid = $this->_upload_file('eid_image');

    
    $employee_data = [
        'employee_name'        => $post['employee_name'],
        'branch_id'            => $post['branch_id'],
        'mobile'               => $post['mobile'],
        'gender'               => $post['gender'],
        'birth_date'           => $post['birth_date'],
        'nationality'          => $post['nationality'],
        'joining_date'         => $post['joining_date'],
        'employee_photo'       => $photo,
        'designation_id'       => $post['designation_id'],
        'uid_number'           => $post['uid_number'],

        // Passport Details
        'passport_name'        => $post['passport_name'],
        'passport_number'      => $post['passport_number'],
        'passport_issue_date'  => $post['passport_issue_date'],
        'passport_expiry_date' => $post['passport_expiry_date'],
        'passport_issue_place' => $post['passport_issue_place'],

        // Labor Card
        'work_permit_no'       => $post['work_permit_no'],
        'personal_id_no'       => $post['personal_id_no'],
        'labor_issue_date'     => $post['labor_issue_date'],
        'labor_expiry_date'    => $post['labor_expiry_date'],
        'labor_card_image'     => $labor_card,

        // Emirates ID
        'eid_number'           => $post['eid_number'],
        'eid_issue_date'       => $post['eid_issue_date'],
        'eid_expiry_date'      => $post['eid_expiry_date'],
        'eid_image'            => $eid,

        // Salary
        'salary_mode'          => $post['salary_mode'],
        'card_number'          => $post['card_number'],

        'created_on'           => date('Y-m-d H:i:s'),
    ];
    // Save to DB
    $this->Company_model->insert_employee($employee_data);
    $this->session->set_flashdata('success', 'Employee registered successfully!');
    redirect('Company/list_employee'); // Or wherever you list employees
}
private function _upload_file($field_name) {
    if (!empty($_FILES[$field_name]['name'])) {
        $config['upload_path']   = './public/employee/';
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['max_size']      = 2048;
        $config['encrypt_name']  = TRUE;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($field_name)) {
            $this->session->set_flashdata('error', 'File upload error: ' . $this->upload->display_errors());
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            return $this->upload->data('file_name');
        }
    }
    return null;
}
public function edit_employee($id){
    $user = $this->session->userdata('user_id');
		if(!has_access($user,'Company/list_employee','E')){
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
            $data['title']='Edit Employee';
            $data['employee_id']=$id;
            //$data['branch_list']=$this->Company_model->get_all_branches();
            $employee = $this->Company_model->get_employee_by_id($id);
            $data['employee'] = $employee ;
            $data['branch_list'] = $this->Company_model->get_all_branches();
            $data['designation_list']=$this->Company_model->get_all_designations();
            $data['main_content']='Company/edit_employee.php';
            $this->load->view('includes/template',$data);
        }
    
}
public function update_employee($id){

    $data = [
        'employee_name'         => $this->input->post('employee_name'),
        'branch_id'             => $this->input->post('branch_id'),
        'mobile'                => $this->input->post('mobile'),
        'gender'                => $this->input->post('gender'),
        'birth_date'            => $this->input->post('birth_date'),
        'uid_number'            => $this->input->post('uid_number'),
        'nationality'           => $this->input->post('nationality'),
        'joining_date'          => $this->input->post('joining_date'),
        'designation_id'        => $this->input->post('designation_id'),
        'passport_name'         => $this->input->post('passport_name'),
        'passport_number'       => $this->input->post('passport_number'),
        'passport_issue_date'   => $this->input->post('passport_issue_date'),
        'passport_expiry_date'  => $this->input->post('passport_expiry_date'),
        'passport_issue_place'  => $this->input->post('passport_issue_place'),
        'work_permit_no'        => $this->input->post('work_permit_no'),
        'personal_id_no'        => $this->input->post('personal_id_no'),
        'labor_issue_date'      => $this->input->post('labor_issue_date'),
        'labor_expiry_date'     => $this->input->post('labor_expiry_date'),
        'eid_number'            => $this->input->post('eid_number'),
        'eid_issue_date'        => $this->input->post('eid_issue_date'),
        'eid_expiry_date'       => $this->input->post('eid_expiry_date'),
        'salary_mode'           => $this->input->post('salary_mode'),
        'card_number'           => $this->input->post('card_number'),
        'updated_on'            => date('Y-m-d H:i:s')
    ];

            if (!empty($_FILES['employee_photo']['name'])) {
                $photo = $this->_upload_file('employee_photo');
                if ($photo) {
                    $data['employee_photo'] = $photo;
                }
            }

            if (!empty($_FILES['labor_card_image']['name'])) {
                $labor_card = $this->_upload_file('labor_card_image');
                if ($labor_card) {
                    $data['labor_card_image'] = $labor_card;
                }
            }

            if (!empty($_FILES['eid_image']['name'])) {
                $eid = $this->_upload_file('eid_image');
                if ($eid) {
                    $data['eid_image'] = $eid;
                }
            }

    $update_status=$this->Company_model->update_employee($id,$data);
            if($update_status)
                    $this->session->set_flashdata('success', 'Employee updated successfully.');
            else
                 $this->session->set_flashdata('error', 'an error occured while updating .');
    redirect('Company/list_employee');
}

public function delete_employee() {
    $user = $this->session->userdata('user_id');
    if (!has_access($user,'Company/list_employee','D')) {
        $data['title'] = 'Access Denied';
		$data['main_content']='errors/access_control.php';
    }else{    
            $id = $this->input->post("id");
            $is_exist=$this->Company_model->get_employee_by_id($id);
            if (empty($id) || empty($is_exist)) {
                echo json_encode([
                    'status' => 0,
                    'message' => 'Invalid employee ID or employee does not exist.'
                ]);
                return;
            }

            $deleted = $this->Company_model->delete_employee($id);

            if ($deleted) {
                echo json_encode([
                    'status' => 1,
                    'message' => 'Employee deleted successfully.'
                ]);
            } else {
                echo json_encode([
                    'status' => 0,
                    'message' => 'Failed to delete employee. Try again.'
                ]);
            }
        }
}
//users
public function list_users(){
		$user = $this->session->userdata('user_id');
		if (!has_view_access($user,'Company/list_users')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';			
		}
		else{
			$data['title']='Users List';
			$data['all_users'] = $this->Company_model->get_all_user_list();
			$data['main_content']='company/list_users.php';
		}		
		$this->load->view('includes/template',$data);
}

public function add_user(){		
        $user = $this->session->userdata('user_id');
		if (!has_access($user,'Company/list_users','A')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
			
		}
		else{
			$data['title']='Add User';
			$data['main_content']='company/add_user.php';
		}
		$this->load->view('includes/template',$data);
}

public function save_user(){
    $data = [
        'user_name'         => $this->input->post('user_name'),
        'user_login'             => $this->input->post('user_login'),
        'user_password'                => $this->input->post('user_password'),
        'gender'                => $this->input->post('gender'),
        'dob'            => $this->input->post('dob'),
        'active'                => 1
    ];
		
        $insert_status = $this->Company_model->insert_user($data);
		
		if($insert_status){
			$this->session->set_flashdata('success', 'Employee updated successfully.');
		}
		else{
			 $this->session->set_flashdata('error', 'an error occured while adding new user .');
		}
		redirect('Company/list_users');
}
public function edit_user(){
		$user = $this->session->userdata('user_id');
		if(!has_access($user,'Company/list_users','E')){
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
			$data['title']='Edit User';
			$user_id = $this->uri->segment('3');
			$data['user'] = $this->Company_model->get_user_by_id($user_id);
            $data['user_id']=$user_id;
			$data['main_content']='company/edit_user.php';
		}
		$this->load->view('includes/template',$data);
}
public function update_user() {

    // Fetch and sanitize input
    $user_id     = $this->input->post('user_id');
    
    // Prepare data array
    $data = array(
        'user_name'  => $this->input->post('user_name'),
        'user_login' => $this->input->post('user_login'),
        'gender'     => $this->input->post('gender'),
        'dob'        => $this->input->post('dob')
    );

    // Update the data using model
    $update_status = $this->Company_model->update_user($user_id, $data);

    if ($update_status) {
        $this->session->set_flashdata('success', 'User details updated successfully.');
    } else {
        $this->session->set_flashdata('error', 'an error occured while updating  user ');
    }

    redirect('company/list_users'); 
}
public function check_user_login_duplicate(){
		$result = $this->Company_model->check_user_login_duplicate();
		echo json_encode($result);
}
public function delete_user() {
    $user = $this->session->userdata('user_id');
    if (!has_access($user,'Company/list_users','D')) {
        $data['title'] = 'Access Denied';
		$data['main_content']='errors/access_control.php';
    }else{    
            $id = $this->input->post("id");
            $is_exist=$this->Company_model->get_user_by_id($id);
            if (empty($id) || empty($is_exist)) {
                echo json_encode([
                    'status' => 0,
                    'message' => 'Invalid user ID or user does not exist.'
                ]);
                return;
            }

            $deleted = $this->Company_model->delete_user($id);

            if ($deleted) {
                echo json_encode([
                    'status' => 1,
                    'message' => 'User deleted successfully.'
                ]);
            } else {
                echo json_encode([
                    'status' => 0,
                    'message' => 'Failed to delete user. Try again.'
                ]);
            }
        }
}
//Designation
public function list_designation(){
		$user = $this->session->userdata('user_id');
		if (!has_view_access($user,'Company/list_users')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';			
		}
		else{
			$data['title']='List Designation';
            $raw_input = $this->input->post('filter');  
        
        if (!empty($raw_input)) {
        $filter_type = '';
        $filter_value = '';

                if (strpos($raw_input, ':') !== false) {
                    list($filter_type, $filter_value) = explode(':', $raw_input, 2);
                    $filter_type = trim($filter_type);
                    $filter_value = trim($filter_value);
                    $column_map = [
                                    'Designation Code' => 'designation_code',
                                    'Designation Name' => 'designation_name',
                                    'Department'=>'department',
                                    'Reporting To'=>'reporting_to',
                                    'Level'=>'level',
                                    'Type'=>'employment_type',
                                    'Location'=>'location',
                                    'Status'=>'status'
                                ];

                    $column = isset($column_map[$filter_type]) ? $column_map[$filter_type] : null;
                    $data['designation_list']=$this->Company_model->get_all_designations($column,$filter_value);
                }else{
                    $data['designation_list'] = $this->Company_model->get_all_designations();
            }			
		}else{
			$data['designation_list'] = $this->Company_model->get_all_designations();
         }
        $data['main_content']='company/list_designation.php';	
		$this->load->view('includes/template',$data);
    }
}
public function add_designation(){		
        $user = $this->session->userdata('user_id');
		if (!has_access($user,'Company/list_designation','A')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';			
		}else{
			$data['title']='Add Designation';
            $data['designation_code']=$this->Company_model->generate_designation_code();
			$data['main_content']='company/add_designation.php';
		}
		$this->load->view('includes/template',$data);
}
public function save_designation(){
    // Load form validation library if not auto-loaded
   // $this->load->library('form_validation');

    // Set validation rules
   // $this->form_validation->set_rules('designation_code', 'Designation Code', 'required|trim|is_unique[designation_master.designation_code]');
   // $this->form_validation->set_rules('designation_name', 'Designation Name', 'required|trim');
    
    // If validation fails, reload form with errors
    //if ($this->form_validation->run() == FALSE) {
       // $this->load->view('designation/add_designation'); // adjust to your actual view
    //} else {
        // Collect data
        $data = array(
            'designation_code'    => $this->input->post('designation_code', TRUE),
            'designation_name'    => $this->input->post('designation_name', TRUE),
            'department'          => $this->input->post('department', TRUE),
            'reporting_to'        => $this->input->post('reporting_to', TRUE),
            'level'               => $this->input->post('level', TRUE),
            'employment_type'     => $this->input->post('employment_type', TRUE),
            'location'            => $this->input->post('location', TRUE),
            'job_description'     => $this->input->post('job_description', TRUE),
            'responsibilities'    => $this->input->post('responsibilities', TRUE),
            'skills'              => $this->input->post('skills', TRUE),
            'qualification'       => $this->input->post('qualification', TRUE),
            'experience'          => $this->input->post('experience', TRUE),
            'status'              => $this->input->post('status', TRUE),
            'created_on'          => date('Y-m-d H:i:s')
        );
        $insert_status=$this->Company_model->insert_designation($data);
       if($insert_status){
             $this->session->set_flashdata('success', 'Designation saved successfully!');
       }else{
            $this->session->set_flashdata('error', 'error occured while saving designation!');
       }
       
       
        redirect('Company/add_designation');
   // }
}
public function edit_designation(){
    $user = $this->session->userdata('user_id');
		if(!has_access($user,'Company/list_designation','E')){
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
			$data['title']='Edit Designation';
			$designation_id = $this->uri->segment('3');
			$data['designation'] = $this->Company_model->get_designation_by_id($designation_id);
            $data['designation_id'] =$designation_id;
			$data['main_content']='company/edit_designation.php';
		}
		$this->load->view('includes/template',$data);
}
public function update_designation(){
            $designation_id=$this->input->post('designation_id');
            $data = array(
                'designation_name'    => $this->input->post('designation_name', TRUE),
                'department'          => $this->input->post('department', TRUE),
                'reporting_to'        => $this->input->post('reporting_to', TRUE),
                'level'               => $this->input->post('level', TRUE),
                'employment_type'     => $this->input->post('employment_type', TRUE),
                'location'            => $this->input->post('location', TRUE),
                'job_description'     => $this->input->post('job_description', TRUE),
                'responsibilities'    => $this->input->post('responsibilities', TRUE),
                'skills'              => $this->input->post('skills', TRUE),
                'qualification'       => $this->input->post('qualification', TRUE),
                'experience'          => $this->input->post('experience', TRUE),
                'status'              => $this->input->post('status', TRUE),
                'updated_on'          => date('Y-m-d H:i:s')
            );

            $update_status=$this->Company_model->update_designation($designation_id,$data);
            if($update_status)
                $this->session->set_flashdata('success', 'Designation updated successfully.');
            else
                $this->session->set_flashdata('error', 'Error occured while updating');
            
            redirect('Company/list_designation'); // or redirect to wherever you want
}


//----------validation-------------------//
private function validate_branch_data($id = "") {
    // Always validate branch_name
    if (!empty($id)) {
        $this->form_validation->set_rules('branch_name', 'Branch Name', 'required|callback_check_unique_branch');
    } else {
        $this->form_validation->set_rules('branch_name', 'Branch Name', 'required|is_unique[branch_master.branch_name]', [
            'is_unique' => 'This Branch Name already exists.'
        ]);
    }

    // Validate logo if file is uploaded
    if (!empty($_FILES['branch_logo']['name'])) {
        $this->form_validation->set_rules('branch_logo', 'Branch Logo', 'callback_validate_logo');
    }

    return $this->form_validation->run();
}
public function check_unique_branch($branch_name) {
    $id = $this->input->post('branch_id');
    $exists = $this->Company_model->check_branch_name_unique($branch_name, $id);
    if ($exists) {
        $this->form_validation->set_message('check_unique_branch', 'This Branch Name already exists.');
        return FALSE;
    }else 
        return TRUE;
}
public function validate_logo($str) {
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
    $max_size = 2 * 1024 * 1024; // 2 MB

    $file = $_FILES['branch_logo'];

    if ($file['error'] !== 0) {
        $this->form_validation->set_message('validate_logo', 'Error uploading file.');
        return FALSE;
    }

    if (!in_array($file['type'], $allowed_types)) {
        $this->form_validation->set_message('validate_logo', 'Only JPG, JPEG, PNG, and PDF files are allowed.');
        return FALSE;
    }

    if ($file['size'] > $max_size) {
        $this->form_validation->set_message('validate_logo', 'File size must not exceed 2 MB.');
        return FALSE;
    }

    return TRUE;
}
public function validate_customer() {
    $this->form_validation->set_rules('branch', 'Branch', 'required');
    $this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
    $this->form_validation->set_rules('customer_code', 'Customer Code', 'required|is_unique[customer_master.customer_code]');
    $this->form_validation->set_rules('customer_email', 'Email', 'required');
    $this->form_validation->set_rules('customer_number', 'Customer Number', 'required');
    $this->form_validation->set_rules('customer_address', 'Customer Address', 'required');

    // For dynamic contact person rows
    if (!$this->input->post('contact_name') || count($this->input->post('contact_name')) == 0) {
        $this->form_validation->set_rules('contact_name[]', 'Contact Person Name', 'required');
    }
    return $this->form_validation->run();
}

}
