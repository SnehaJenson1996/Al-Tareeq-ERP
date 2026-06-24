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

	//customer master
	public function list_customers(){
		$user = $this->session->userdata('user_id');
		if(!has_view_access($user,'Setup/list_customers')){
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
			$data['title']='Customers List';
        
			$data['all_customers'] = $this->Setup_model->get_all_customer_list();

			$data['main_content']='setup/customer/list_customers.php';
		}
		
		$this->load->view('includes/template',$data);
	}

	public function add_customer(){
		$user = $this->session->userdata('user_id');
		if(!has_access($user,'Setup/list_customers','A')){
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
			$data['title']='Add Customer';
        
        	$data['main_content']='setup/customer/add_customer.php';
		}
		
		$this->load->view('includes/template',$data);
	}

	public function add_customer_data(){
        $result = $this->Setup_model->add_customer_data();

		if($result){
			echo 'Added';
		}
		else{
			echo 'Not Added';
		}
		redirect('Setup/list_customers');
	}

	//discount limit
	public function discount_limit(){
		$user = $this->session->userdata('user_id');
		if(!has_view_access($user,'Setup/discount_limit')){
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
			$data['title']='Discount Limit';
			$data['discount_limit'] = $this->Setup_model->get_discount_limit();
        	$data['main_content']='setup/discount_limit.php';
		}
		
		$this->load->view('includes/template',$data);
	}

	public function set_discount_limit(){
		$result = $this->Setup_model->set_discount_limit();

		if($result){
			echo 'Added';
		}
		else{
			echo 'Not Added';
		}
		redirect('Setup/discount_limit');
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
}
