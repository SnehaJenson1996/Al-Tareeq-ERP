<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends CI_Controller {

	
	public function __construct() {
		parent::__construct();

		if (!$this->session->userdata('is_logged_in')) {
			redirect('Login/login');
		}
		$this->load->model('Setup_model');
		$this->load->helper('menu_helper');
		// Prevent browser caching
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
		$this->output->set_header("Pragma: no-cache");

	}

	//company_details
	function company_details(){
		$user = $this->session->userdata('user_id');
		if (!has_view_access($user,'Setup/company_details')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
			
		}
		else{
		$data['title']='Company Details';
		$data['company_details'] = $this->Setup_model->get_company_details();
		$data['bank_details'] = $this->Setup_model->get_company_bank_details();
		$data['main_content']='setup/company_details.php';
		}
		$this->load->view('includes/template',$data);
	}

	function add_company_details_data(){
		 $result = $this->Setup_model->add_company_details_data();
		
		if($result){
			echo 'Added';
		}
		else{
			echo 'Not Added';
		}
		redirect('Setup/company_details');
	}
	//users
	public function add_user(){		
        $user = $this->session->userdata('user_id');
		if (!has_access($user,'Setup/list_users','A')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
			
		}
		else{
			$data['title']='Add User';
			$data['main_content']='setup/user/add_user.php';
		}
		$this->load->view('includes/template',$data);
	}

	public function add_user_data(){
		
        $result = $this->Setup_model->add_user_data();
		
		if($result){
			echo 'Added';
		}
		else{
			echo 'Not Added';
		}
		redirect('Setup/list_users');
	}

	public function list_users(){
		$user = $this->session->userdata('user_id');
		if (!has_view_access($user,'Setup/list_users')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
			
		}
		else{
			$data['title']='Users List';
			$data['all_users'] = $this->Setup_model->get_all_user_list();
			$data['main_content']='setup/user/list_users.php';
		}
		
		$this->load->view('includes/template',$data);
	}

	public function edit_user(){
		$user = $this->session->userdata('user_id');
		if(!has_access($user,'Setup/list_users','E')){
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
			$data['title']='Edit User';
			$user_id = $this->uri->segment('3');
			$data['user'] = $this->Setup_model->get_user_by_id($user_id);

			$data['main_content']='setup/user/edit_user.php';
		}
		$this->load->view('includes/template',$data);
	}

	public function edit_user_data(){
        $result = $this->Setup_model->edit_user_data();

		if($result){
			echo 'Updated';
		}
		else{
			echo 'Not Updated';
		}
		redirect('Setup/list_users');
	}

	public function check_user_login_duplicate(){
		$result = $this->Setup_model->check_user_login_duplicate();
		echo json_encode($result);
	}

	//User access

	public function add_user_access()
	{
		$user = $this->session->userdata('user_id');
		if(!has_view_access($user,'Setup/add_user_access')){
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
			$data['title']='User Access Control';
			$data['users'] = $this->Setup_model->get_active_user_list();
			$data['user_id'] = $this->session->userdata('user_id');
			if(isset($_POST['user_id'])){
				$data['menu'] = $this->Setup_model->get_active_menu_list();
				$data['user_id'] = $_POST['user_id'];
			}
			else if($data['user_id'] != 1){
				$data['menu'] = $this->Setup_model->get_active_menu_list();
			}

			$data['main_content']='setup/user/add_user_access.php';
		}
		$this->load->view('includes/template',$data);
	}

	function add_user_access_data()
    {
		$insert_id = $this->Setup_model->user_access_data();

		if($insert_id!='')
		{
			$this->session->set_flashdata('success', 'Data Saved Successfully..');
			redirect('Setup/add_user_access');
		}

    }

	public function add_extra_access()
	{
		$user = $this->session->userdata('user_id');
		if(!has_view_access($user,'Setup/add_user_access')){
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
			$data['title']='User Access Control';
			$data['users'] = $this->Setup_model->get_active_user_list();
			$data['user_id'] = $this->session->userdata('user_id');			
			$data['menu'] = $this->Setup_model->get_extra_access_list();
			
			$data['main_content']='setup/user/add_extra_access.php';
		}
		$this->load->view('includes/template',$data);
	}
	
public function add_user_extra_access()
	{
		$user = $this->session->userdata('user_id');
		if(!has_view_access($user,'Setup/add_user_access')){
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
			$data['title']='User Access Control';
			$data['users'] = $this->Setup_model->get_active_user_list();
			$data['user_id'] = $this->input->post('user_id') ?? $this->session->userdata('user_id');
			$data['menu'] = $this->Setup_model->get_extra_access_list();
			$data['main_content']='setup/user/add_extra_access.php';
		}
		$this->load->view('includes/template',$data);
	}
function add_user_extra_access_data(){
	$result = $this->Setup_model->add_user_extra_access_data();
	redirect('Setup/add_extra_access');
}	

	


public function add_sales_area()
{
    // GET LAST CODE
    $last = $this->db->select('sales_area_code')
                     ->from('sales_area_master')
                     ->order_by('sales_area_id', 'DESC')
                     ->get()
                     ->row();

    if (!empty($last->sales_area_code)) {

        $num = (int) substr($last->sales_area_code, 3);
        $num++;
        $code = 'SA-' . str_pad($num, 4, '0', STR_PAD_LEFT);

    } else {
        $code = 'SA-0001';
    }

    $data['title'] = 'Sales Area Master';
    $data['main_content'] = 'setup/sales_area_add.php';

    // PASS AUTO CODE TO VIEW
    $data['auto_code'] = $code;

    $this->load->view('includes/template.php', $data);
}
	
public function add_sales_area_data()
{
    $result = $this->Setup_model->add_sales_area_data();

    if ($result) {
        echo 'Added';
    } else {
        echo 'Not Added';
    }

    redirect('Setup/list_sales_area');
}
	
public function list_sales_area()
{
   
        $data['title'] = 'Sales Area List';

        $data['all_sales_area'] = $this->Setup_model->get_all_sales_area_list();

        $data['main_content'] = 'Setup/list_sales_area.php';
    

    $this->load->view('includes/template', $data);
}

public function edit_sales_area($id)
{

        $data['title'] = 'Edit Sales Area';

        $data['area'] = $this->db->where('sales_area_id', $id)
                                 ->get('sales_area_master')
                                 ->row();

        $data['main_content'] = 'setup/sales_area_edit.php';


    $this->load->view('includes/template', $data);
}

public function update_sales_area_data($id)
{
    $sales_area_name = trim($this->input->post('sales_area_name'));

    // DUPLICATE CHECK (exclude current record)
    $exists = $this->db->where('LOWER(sales_area_name)', strtolower($sales_area_name))
                       ->where('sales_area_id !=', $id)
                       ->get('sales_area_master')
                       ->row();

    if (!empty($exists)) {
        $this->session->set_flashdata('error', 'Sales Area Name already exists!');
        redirect('Setup/edit_sales_area/' . $id);
        return;
    }

    $data = array(
        'sales_area_name' => $sales_area_name,
        'description'     => $this->input->post('description'),
        'updated_at'      => date('Y-m-d H:i:s')
    );

    $this->db->where('sales_area_id', $id);
    $this->db->update('sales_area_master', $data);

    $this->session->set_flashdata('success', 'Sales Area Updated Successfully');
    redirect('Setup/list_sales_area');
}

public function delete_sales_area($id)
{

    $this->db->where('sales_area_id', $id);
    $this->db->delete('sales_area_master');

    $this->session->set_flashdata('success', 'Sales Area Deleted Successfully');

    redirect('Setup/list_sales_area');
}

public function add_commission_group()
{
    // GET LAST CODE
    $last = $this->db->select('commission_group_code')
                     ->from('commission_group_master')
                     ->order_by('commission_group_id', 'DESC')
                     ->get()
                     ->row();

    if (!empty($last->commission_group_code)) {

        $num = (int) substr($last->commission_group_code, 3);
        $num++;
        $code = 'CG-' . str_pad($num, 4, '0', STR_PAD_LEFT);

    } else {
        $code = 'CG-0001';
    }

    $data['title'] = 'Commission Group Master';
    $data['main_content'] = 'setup/commission_group_add.php';
    $data['auto_code'] = $code;

    $this->load->view('includes/template.php', $data);
}

public function add_commission_group_data()
{
    $result = $this->Setup_model->add_commission_group_data();

    if ($result) {
        $this->session->set_flashdata('success', 'Commission Group Added Successfully');
    } else {
        $this->session->set_flashdata('error', 'Commission Group Not Added');
    }

    redirect('Setup/list_commission_group');
}

public function list_commission_group()
{
    $data['title'] = 'Commission Group List';
    $data['all_commission_group'] = $this->Setup_model->get_all_commission_group_list();
    $data['main_content'] = 'Setup/commission_group_list.php';

    $this->load->view('includes/template', $data);
}

public function edit_commission_group($id)
{
    $data['title'] = 'Edit Commission Group';

    $data['group'] = $this->db->where('commission_group_id', $id)
                              ->get('commission_group_master')
                              ->row();

    $data['main_content'] = 'setup/commission_group_edit.php';

    $this->load->view('includes/template', $data);
}

public function update_commission_group_data($id)
{
    $name = trim($this->input->post('commission_group_name'));

    // DUPLICATE CHECK (exclude current)
    $exists = $this->db->where('LOWER(commission_group_name)', strtolower($name))
                       ->where('commission_group_id !=', $id)
                       ->get('commission_group_master')
                       ->row();

    if (!empty($exists)) {
        $this->session->set_flashdata('error', 'Commission Group already exists!');
        redirect('Setup/edit_commission_group/' . $id);
        return;
    }

    $data = array(
        'commission_group_name' => $name,
        'description'           => $this->input->post('description'),
        'updated_at'            => date('Y-m-d H:i:s')
    );

    $this->db->where('commission_group_id', $id);
    $this->db->update('commission_group_master', $data);

    $this->session->set_flashdata('success', 'Commission Group Updated Successfully');

    redirect('Setup/list_commission_group');
}

public function delete_commission_group($id)
{
    $this->db->where('commission_group_id', $id);
    $this->db->delete('commission_group_master');

    $this->session->set_flashdata('success', 'Commission Group Deleted Successfully');

    redirect('Setup/list_commission_group');
}

public function add_customer_group()
{
    // GET LAST CODE
    $last = $this->db->select('customer_group_code')
                     ->from('customer_group_master')
                     ->order_by('customer_group_id', 'DESC')
                     ->get()
                     ->row();

    if (!empty($last->customer_group_code)) {

        $num = (int) substr($last->customer_group_code, 3);
        $num++;
        $code = 'CG-' . str_pad($num, 4, '0', STR_PAD_LEFT);

    } else {
        $code = 'CG-0001';
    }

    $data['title'] = 'Customer Group Master';
    $data['auto_code'] = $code;
    $data['main_content'] = 'setup/customer_group_add.php';

    $this->load->view('includes/template.php', $data);
}
public function add_customer_group_data()
{
    $result = $this->Setup_model->add_customer_group_data();

    if ($result) {
        $this->session->set_flashdata('success', 'Customer Group Added Successfully');
    } else {
        $this->session->set_flashdata('error', 'Customer Group Not Added');
    }

    redirect('Setup/list_customer_group');
}
public function list_customer_group()
{
    $data['title'] = 'Customer Group List';
    $data['all_customer_group'] = $this->Setup_model->get_all_customer_group_list();
    $data['main_content'] = 'setup/customer_group_list.php';

    $this->load->view('includes/template', $data);
}
public function edit_customer_group($id)
{
    $data['title'] = 'Edit Customer Group';

    $data['group'] = $this->db->where('customer_group_id', $id)
                              ->get('customer_group_master')
                              ->row();

    $data['main_content'] = 'setup/customer_group_edit.php';

    $this->load->view('includes/template', $data);
}
public function update_customer_group_data($id)
{
    $name = trim($this->input->post('customer_group_name'));

    // DUPLICATE CHECK
    $exists = $this->db->where('LOWER(customer_group_name)', strtolower($name))
                       ->where('customer_group_id !=', $id)
                       ->get('customer_group_master')
                       ->row();

    if (!empty($exists)) {
        $this->session->set_flashdata('error', 'Customer Group already exists!');
        redirect('Setup/edit_customer_group/' . $id);
        return;
    }

    $data = array(
        'customer_group_name' => $name,
        'description'         => $this->input->post('description'),
        'updated_at'          => date('Y-m-d H:i:s')
    );

    $this->db->where('customer_group_id', $id);
    $this->db->update('customer_group_master', $data);

    $this->session->set_flashdata('success', 'Customer Group Updated Successfully');

    redirect('Setup/list_customer_group');
}
public function delete_customer_group($id)
{
    $this->db->where('customer_group_id', $id);
    $this->db->delete('customer_group_master');

    $this->session->set_flashdata('success', 'Customer Group Deleted Successfully');

    redirect('Setup/list_customer_group');
}

public function add_sales_rep()
{
    $last = $this->Setup_model->get_last_sales_rep_code();

    if (!empty($last->sales_rep_code)) {
        $num = (int) substr($last->sales_rep_code, 3);
        $num++;
        $code = 'SR-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    } else {
        $code = 'SR-0001';
    }

    $data['auto_code'] = $code;
    $data['employees'] = $this->Setup_model->get_all_employees();
    $data['commission_group'] = $this->Setup_model->get_all_commission_groups();

    $data['main_content'] = 'setup/sales_rep_add.php';
    $data['title'] = 'Sales Rep Master';

    $this->load->view('includes/template', $data);
}
public function add_sales_rep_data()
{
    $data = array(
        'sales_rep_code'        => $this->input->post('sales_rep_code'),
        'sales_rep_name'        => $this->input->post('sales_rep_name'),
        'emp_id'                => $this->input->post('emp_id'),
        'target_amount'         => $this->input->post('target_amount'),
        'commission_percent'    => $this->input->post('commission_percent'),
        'sales_discount_percent'=> $this->input->post('sales_discount_percent'),
        'commission_group_id'   => $this->input->post('commission_group_id'),
        'is_blocked'            => isset($_POST['is_blocked']) ? 1 : 0,
        'created_at'            => date('Y-m-d H:i:s')
    );

    $this->Setup_model->add_sales_rep_data($data);

    $this->session->set_flashdata('success', 'Sales Rep Added Successfully');

    redirect('Setup/list_sales_rep');
}

public function edit_sales_rep($id)
{
    $data['title'] = 'Edit Sales Rep';

    // SINGLE RECORD
    $data['rep'] = $this->Setup_model->get_sales_rep_by_id($id);

    // DROPDOWNS
    $data['employees'] = $this->Setup_model->get_all_employees();
    $data['commission_group'] = $this->Setup_model->get_all_commission_groups();

    $data['main_content'] = 'setup/sales_rep_edit.php';

    $this->load->view('includes/template.php', $data);
}
public function update_sales_rep_data($id)
{
    $data = array(
        'sales_rep_name'        => $this->input->post('sales_rep_name'),
        'emp_id'                => $this->input->post('emp_id'),
        'target_amount'         => $this->input->post('target_amount'),
        'commission_percent'    => $this->input->post('commission_percent'),
        'sales_discount_percent'=> $this->input->post('sales_discount_percent'),
        'commission_group_id'   => $this->input->post('commission_group_id'),
        'is_blocked'            => isset($_POST['is_blocked']) ? 1 : 0,
        'updated_at'            => date('Y-m-d H:i:s')
    );

    $this->Setup_model->update_sales_rep_data($id, $data);

    $this->session->set_flashdata('success', 'Sales Rep Updated Successfully');

    redirect('Setup/list_sales_rep');
}
public function list_sales_rep()
{
    $data['title'] = 'Sales Rep List';

    // 🔴 ALL QUERY IN MODEL
    $data['all_sales_rep'] = $this->Setup_model->get_all_sales_rep_list();

    $data['main_content'] = 'setup/sales_rep_list.php';

    $this->load->view('includes/template', $data);
}
public function add_customer()
{
    $data['title'] = 'Add Customer';

    $this->load->model('Setup_model');

    $data['customer_groups'] = $this->Setup_model->get_all_customer_group_list();
   $data['customer_code'] = $this->Setup_model->get_customer_code();

    $data['sales_rep_list'] = $this->Setup_model->get_all_sales_rep_list();
    $data['sales_area_list'] = $this->Setup_model->get_all_sales_area_list();

    $data['main_content'] = 'setup/add_customer';

    $this->load->view('includes/template', $data);
}

public function save_customer()
{
    $result = $this->Setup_model->save_customer();

    if ($result) {
        $this->session->set_flashdata('success', 'Customer Added Successfully');
    } else {
        $this->session->set_flashdata('error', 'Failed To Add Customer');
    }

    redirect('Setup/list_customers');
}

public function list_customers()
{
    $data['title'] = 'Customer List';

    $data['all_customers'] = $this->Setup_model->get_all_customers();

    $data['main_content'] = 'setup/list_customers';

    $this->load->view('includes/template', $data);
}
public function edit_customer($customer_id)
{
    $data['title'] = 'Edit Customer';

    $data['customer'] = $this->Setup_model->get_customer_by_id($customer_id);

    $data['contacts'] = $this->Setup_model->get_customer_contacts($customer_id);

    $data['customer_groups'] = $this->Setup_model->get_all_customer_group_list();

    $data['sales_rep_list'] = $this->Setup_model->get_all_sales_rep_list();

    $data['sales_area_list'] = $this->Setup_model->get_all_sales_area_list();

    $data['main_content'] = 'setup/edit_customer';

    $this->load->view('includes/template', $data);
}
public function update_customer($customer_id)
{
    $result = $this->Company_model->update_customer($customer_id);

    if ($result) {
        $this->session->set_flashdata('success', 'Customer Updated Successfully');
    } else {
        $this->session->set_flashdata('error', 'Failed To Update Customer');
    }

    redirect('Setup/list_customers');
}
public function delete_customer($customer_id)
{
    $result = $this->Company_model->delete_customer($customer_id);

    if ($result) {
        $this->session->set_flashdata('success', 'Customer Deleted Successfully');
    } else {
        $this->session->set_flashdata('error', 'Failed To Delete Customer');
    }

    redirect('Setup/list_customers');
}

public function list_unit() {
		$data['title'] = "list units";
        $data['units'] = $this->Setup_model->get_all_units();
        $data['main_content']='setup/list_units.php';
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
			$data['main_content'] = 'setup/add_unit.php';
			$this->load->view('includes/template', $data);
		}
    }
	private function save_unit(){  
		$this->load->model('Setup_model');
		$unit_name = $this->input->post('unit_name', true);
		$unit_code = $this->input->post('unit_code', true);
		if (empty($unit_name) || empty($unit_code)) {
			$data['error'] = "Please fill in all required fields.";
			$data['title'] = 'Add Unit';
			$data['main_content'] = 'setup/add_unit.php';
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
		$insert_id = $this->Setup_model->insert_unit($insert_data);

		if ($insert_id) {
			// Set success message and redirect to add_unit (or list page)
			$this->session->set_flashdata('success', 'Unit saved successfully!');
			redirect('Setup/list_unit'); // change redirect as needed
		} else {
			// Show error message and reload form
			$data['error'] = "Failed to save unit. Please try again.";
			$data['title'] = 'Add Unit';
			$data['main_content'] = 'setup/add_unit.php';
			$this->load->view('includes/template', $data);
		}
	}
	public function edit_unit($unit_id){
		$data['unit']=$this->Setup_model->get_unit_by_id($unit_id);
		$data['title'] = 'Edit Unit';
		$data['main_content'] = 'setup/add_unit.php';
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
		$updated = $this->Setup_model->update_unit($data,$unit_id);

		if ($updated) {
			// Set success message and redirect to add_unit (or list page)
			$this->session->set_flashdata('success', 'Unit updated successfully!');
			redirect('Setup/list_unit'); // change redirect as needed
		} else {
			// Show error message and reload form
			$data['error'] = "Failed to update unit. Please try again.";
			$data['title'] = 'Add Unit';
			$data['main_content'] = 'setup/add_unit.php';
			$this->load->view('includes/template', $data);
		}

	}
	function delete_unit(){
		$unit_id=$this->input->post('id');
		$deleted=$this->Setup_model->delete_unit($unit_id);
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

    public function list_items(){
		
			$data['title']='Items List';        
			$data['all_items'] = $this->Setup_model->get_all_item_list();
			//echo $this->db->last_query();exit();
			$data['main_content']='setup/list_items.php';
		
		$this->load->view('includes/template',$data);
		
	}

	public function add_item(){
		
			$data['title']='Add Item';
			    $data['categories'] = $this->Setup_model->get_all_categories();

			$data['active_units'] = $this->Setup_model->get_all_units();	
			$data['main_content']='setup/add_item.php';

	
		$this->load->view('includes/template',$data);
	}
	public function add_item_data()
{
    $this->load->library(['upload']);

    $item_data = [
        'product_name'       => $this->input->post('product_name', true),
        'product_code'       => $this->input->post('product_code', true),
        'unit_id'       => $this->input->post('unit_id', true),

        'category_id'     => $this->input->post('category_id', true),
        'group_code'      => $this->input->post('group_code', true),

        'retail_price'      => $this->input->post('retail_price', true),

        'min_level'       => $this->input->post('min_level', true),
        'max_level'       => $this->input->post('max_level', true),
        'reorder_level'   => $this->input->post('reorder_level', true),

        'hs_code'         => $this->input->post('hs_code', true),
        'description'=> $this->input->post('description', true),

        // FLAGS
        'tax_applicable'      => $this->input->post('tax_applicable') ? 1 : 0,
        'is_finished_product' => $this->input->post('is_finished_product') ? 1 : 0,
        'is_custom_made'      => $this->input->post('is_custom_made') ? 1 : 0,
        'is_non_standard'     => $this->input->post('is_non_standard') ? 1 : 0,
        'is_inactive'         => $this->input->post('is_inactive') ? 1 : 0,
        'is_marked_delete'    => $this->input->post('is_marked_delete') ? 1 : 0,
    ];

    /* IMAGE UPLOAD */
    if (!empty($_FILES['product_image']['name'])) {

        $config['upload_path']   = './public/items/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name']     = time() . '_' . $_FILES['product_image']['name'];

        $this->upload->initialize($config);

        if ($this->upload->do_upload('product_image')) {
            $upload = $this->upload->data();
            $item_data['product_image'] = $upload['file_name'];
        }
    }

    /* INSERT VIA MODEL */
    $insert_id = $this->Setup_model->insert_item($item_data);

    if ($insert_id) {
        $this->session->set_flashdata('success', 'Item added successfully');
        redirect('Setup/list_items');
    } else {
        $this->session->set_flashdata('error', 'Failed to add item');
        redirect('Setup/item_form');
    }
}

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
		$data['product'] = $this->Setup_model->get_item_by_id($item_id);	
		$data['active_units'] = $this->Setup_model->get_all_units();
            $data['categories'] = $this->Setup_model->get_all_categories();
	
		$data['main_content']='setup/add_item.php';
		$this->load->view('includes/template',$data);

	}
	public function update_item()
{
    $item_id = $this->uri->segment(3);

    $this->load->library('upload');

    /* =========================
       GET POST DATA (CONSISTENT)
    ==========================*/
    $data = [
        'product_name'     => $this->input->post('product_name', true),
        'product_code'     => $this->input->post('product_code', true),
        'description'      => $this->input->post('description', true),

        'unit_id'         => $this->input->post('unit_id', true),
        'retail_price'    => $this->input->post('retail_price', true),
        'group_code'      => $this->input->post('group_code', true),
        'category_id'     => $this->input->post('category_id', true),

        'min_level'       => $this->input->post('min_level', true),
        'max_level'       => $this->input->post('max_level', true),
        'reorder_level'   => $this->input->post('reorder_level', true),

        'hs_code'         => $this->input->post('hs_code', true),

        /* FLAGS */
        'tax_applicable'      => $this->input->post('tax_applicable') ? 1 : 0,
        'is_finished_product' => $this->input->post('is_finished_product') ? 1 : 0,
        'is_custom_made'      => $this->input->post('is_custom_made') ? 1 : 0,
        'is_non_standard'     => $this->input->post('is_non_standard') ? 1 : 0,
        'is_inactive'         => $this->input->post('is_inactive') ? 1 : 0,
        'is_marked_delete'    => $this->input->post('is_marked_delete') ? 1 : 0,

        'updated_at'         => date('Y-m-d H:i:s')
    ];

    /* =========================
       GET EXISTING ITEM
    ==========================*/
    $existing_item = $this->Setup_model->get_item_by_id($item_id);

    $product_image = $existing_item['product_image'];

    /* =========================
       IMAGE UPLOAD
    ==========================*/
    if (!empty($_FILES['product_image']['name'])) {

        $config['upload_path']   = './public/items/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['file_name']     = time() . '_' . $_FILES['product_image']['name'];

        $this->upload->initialize($config);

        if ($this->upload->do_upload('product_image')) {

            $upload = $this->upload->data();
            $data['product_image'] = $upload['file_name'];

            // delete old image
            if (!empty($existing_item['product_image']) &&
                file_exists('./public/items/'.$existing_item['product_image'])) {
                unlink('./public/items/'.$existing_item['product_image']);
            }

        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors());
            redirect('Setup/edit_item/'.$item_id);
            return;
        }
    }

    /* =========================
       UPDATE DB
    ==========================*/
    $this->Setup_model->update_item($item_id, $data);

    $this->session->set_flashdata('success', 'Item updated successfully');
    redirect('Setup/list_items');
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

public function delete_item($id)
{
    if (!$id) {
        $this->session->set_flashdata('error', 'Invalid Item ID');
        redirect('Setup/list_items');
        return;
    }

    $this->db->where('product_id', $id);
    $this->db->update('item_master', [
        'is_marked_delete' => 1,
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    $this->session->set_flashdata('success', 'Item moved to trash successfully');
    redirect('Setup/list_items');
}

public function add_item_category($id = null)
{
    			$data['title']='Add Item Category';

    if ($id) {
        $data['category'] = $this->Setup_model->get_category($id);
    } else {
        $data['category_code'] = $this->Setup_model->get_category_code();
    }

    $data['main_content']='setup/item_category_add.php';

	
		$this->load->view('includes/template',$data);

}

public function add_category_data()
{
    $data = [
        'category_code'  => $this->input->post('category_code', true),
        'category_name'  => $this->input->post('category_name', true),
        'description'    => $this->input->post('description', true),

        'is_active'      => $this->input->post('is_active') ? 1 : 0,
        'is_marked_delete'=> $this->input->post('is_marked_delete') ? 1 : 0,
    ];

    $this->Setup_model->insert_item_category($data);

    $this->session->set_flashdata('success', 'Category added successfully');
    redirect('Setup/list_item_category');
}

public function update_item_category($id)
{
    $data = [
        'category_code'  => $this->input->post('category_code', true),
        'category_name'  => $this->input->post('category_name', true),
        'description'    => $this->input->post('description', true),

        'is_active'      => $this->input->post('is_active') ? 1 : 0,
        'is_marked_delete'=> $this->input->post('is_marked_delete') ? 1 : 0,
    ];

    $this->Setup_model->update_item_category($id, $data);

    $this->session->set_flashdata('success', 'Category updated successfully');
    redirect('Setup/add_item_category/'.$id);
}
public function list_item_category()
{
    $data['title']='Items Category List';        

    $data['categories'] = $this->Setup_model->get_all_categories();

    $data['main_content']='setup/list_item_category.php';

		$this->load->view('includes/template',$data);
}
public function delete_category($id)
{
    $result = $this->Setup_model->delete_category($id);

    if ($result) {
        $this->session->set_flashdata('success', 'Category deleted successfully.');
    } else {
        $this->session->set_flashdata('error', 'Failed to delete category.');
    }

    redirect('Setup/list_item_category');
}

public function update_category($id)
{
    $data = [
        'category_name' => $this->input->post('category_name', true),
        'description'   => $this->input->post('description', true),
    ];

    $result = $this->Setup_model->update_category($id, $data);

    if ($result) {
        $this->session->set_flashdata('success', 'Category updated successfully.');
    } else {
        $this->session->set_flashdata('error', 'Failed to update category.');
    }

    redirect('Setup/list_item_category');
}
		
public function add_agent()
{
    $data['title'] = 'Add Agent';
    $data['agent_code'] = $this->Setup_model->generate_agent_code();
    $data['main_content'] = 'setup/add_agent';

    $this->load->view('includes/template', $data);
}
public function add_agent_data()
{
    $data = [
        'agent_code' => $this->input->post('agent_code', true),
        'agent_name' => $this->input->post('agent_name', true),
        'phone'      => $this->input->post('phone', true),
        'email'      => $this->input->post('email', true),
        'address'    => $this->input->post('address', true)
        // 'is_active'  => $this->input->post('is_active') ? 1 : 0
    ];

    $insert = $this->Setup_model->insert_agent($data);

    if ($insert) {
        $this->session->set_flashdata('success', 'Agent added successfully.');
    } else {
        $this->session->set_flashdata('error', 'Failed to add agent.');
    }

    redirect('Setup/list_agents');
}
public function edit_agent($id)
{
    $data['title'] = 'Edit Agent';
    $data['agent'] = $this->Setup_model->get_agent_by_id($id);
    $data['main_content'] = 'setup/add_agent';

    $this->load->view('includes/template', $data);
}

public function update_agent($id)
{
    $data = [
        'agent_name' => $this->input->post('agent_name', true),
        'phone'      => $this->input->post('phone', true),
        'email'      => $this->input->post('email', true),
        'address'    => $this->input->post('address', true),
        // 'is_active'  => $this->input->post('is_active') ? 1 : 0,
        'updated_at' => date('Y-m-d H:i:s')
    ];

    $this->Setup_model->update_agent($id, $data);

    $this->session->set_flashdata('success', 'Agent updated successfully.');
    redirect('Setup/list_agents');
}

public function list_agents()
{
    $data['title'] = 'Agent List';
    $data['agents'] = $this->Setup_model->get_all_agents();
    $data['main_content'] = 'setup/list_agents';

    $this->load->view('includes/template', $data);
}
public function delete_agent($id)
{
    $this->db->where('agent_id', $id);
    $this->db->update('agent_master', [
        'is_marked_delete' => 1,
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    $this->session->set_flashdata('success', 'Agent deleted successfully.');
    redirect('Setup/list_agents');
}

}
