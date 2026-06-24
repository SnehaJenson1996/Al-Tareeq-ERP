<?php
class Setup_model extends CI_Model {

    public function __construct()
    {
        
    }
	//function to get next serial number
	function get_next_code($prifix,$column,$table,$sublen)
  	{
		
	    $query=$this->db->query("select max(substr($column,$sublen,5))as count from $table where $column like '%$prifix%'");
	    return $query->row('count');
  	}
	//company details

	public function get_company_details(){
		$res = $this->db->select('*')->where('company_id',1)->get('company_master')->row_array();
		return $res;
	}

	public function get_company_bank_details()
{
    $res = $this->db->select('*')
                    ->where('company_id',1)
                    ->get('company_bank_details')
                    ->result();

    return $res;
}
	public function add_company_details_data()
{
    $data = array(
        'company_name'          => $this->input->post('company_name'),
        'company_address'       => $this->input->post('company_address'),
        'company_city'          => $this->input->post('company_city'),
        'company_state'         => $this->input->post('company_state'),
        'company_po'            => $this->input->post('company_po'),
        'company_country'       => $this->input->post('company_country'),
        'company_email_id'      => $this->input->post('company_email_id'),
        'company_telephone'     => $this->input->post('company_telephone'),
        'company_telephone_alt' => $this->input->post('company_telephone_alt'),
        'company_trn'           => $this->input->post('company_trn'),
        'company_website'       => $this->input->post('company_website'),
        'contact_person'        => $this->input->post('contact_person')
    );

    $upload_path = './uploads/company/';

    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0777, true);
    }

    $config['upload_path']   = $upload_path;
    $config['allowed_types'] = 'jpg|jpeg|png|gif|pdf';

    $this->load->library('upload');

    // Company Logo
    if (!empty($_FILES['company_logo']['name'])) {

        $config['file_name'] = time().'_logo';
        $this->upload->initialize($config);

        if ($this->upload->do_upload('company_logo')) {
            $upload_data = $this->upload->data();
            $data['company_logo'] = 'uploads/company/'.$upload_data['file_name'];
        }
    }

    // Company Header
    if (!empty($_FILES['company_header']['name'])) {

        $config['file_name'] = time().'_header';
        $this->upload->initialize($config);

        if ($this->upload->do_upload('company_header')) {
            $upload_data = $this->upload->data();
            $data['company_header'] = 'uploads/company/'.$upload_data['file_name'];
        }
    }

    // Company Footer
    if (!empty($_FILES['company_footer']['name'])) {

        $config['file_name'] = time().'_footer';
        $this->upload->initialize($config);

        if ($this->upload->do_upload('company_footer')) {
            $upload_data = $this->upload->data();
            $data['company_footer'] = 'uploads/company/'.$upload_data['file_name'];
        }
    }

    // Update Company
    $this->db->where('company_id', 1);
    $this->db->update('company_master', $data);

    // Save Bank Details
    if (!empty($_POST['bname'])) {

        foreach ($_POST['bname'] as $key => $bank_name) {

            if (!empty($bank_name)) {

                $bank_data = array(
                    'company_id'   => 1,
                    'bank_name'    => $bank_name,
                    'bank_account' => $_POST['bacc'][$key],
                    'bank_branch'  => $_POST['bbranch'][$key],
                    'bank_iban'    => $_POST['biban'][$key],
                    'bank_swift'   => $_POST['bswift'][$key]
                );

                $this->db->insert('company_bank_details', $bank_data);
            }
        }
    }

    return true;
}
	//users
    public function add_user_data(){
        $data=array(
            'user_name'=>$_POST['user_name'],
            'user_login'=>$_POST['user_login'],
            'user_password'=>$_POST['user_password'],
            'gender'=>$_POST['gender'],
            'dob'=>$_POST['dob']
        );
        $res = $this->db->insert('users',$data);
        return $res;
    }

    function get_active_user_list()
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('active',1);
        $query = $this->db->get()->result();
        return $query;      
    }

	public function get_all_user_list(){
		$this->db->select('*');
        $this->db->from('users');
        $query = $this->db->get()->result();
        return $query; 
	}

	public function get_user_by_id($user_id){
		$this->db->select('*');
        $this->db->from('users');
		$this->db->where('user_id',$user_id);
        $query = $this->db->get()->row_array();
        return $query;
	}

	public function edit_user_data(){
		$data=array(
			'user_name'=>$_POST['user_name'],
			'user_login'=>$_POST['user_login'],
			'gender'=>$_POST['gender'],
			'dob'=>$_POST['dob'],
		);
		$this->db->where('user_id',$_POST['user_id']);
		$res = $this->db->update('users',$data);
		return $res;
	}

	public  function check_user_login_duplicate(){
        $user_login = $this->input->post('user_login');
        $this->db->where('user_login', $user_login);
        $query = $this->db->get('users');

        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
}
	//menu
    function get_active_menu_list()
    {
        $this->db->select('*');
        $this->db->from('menu_access');
        $this->db->where('menu_sid',0);
        $this->db->where('active',1);
        $query = $this->db->get()->result();
        return $query;      
    }

    function user_access_data()
	{
		$uid=$this->input->post('user_id');
		$query= $this->db->query("delete from user_access where user_id='$uid' and resource_type='M'");
		$query= $this->db->query("delete from page_access where user_id='$uid'");

		if(isset($_POST['check']))
		{
			for ($i = 0; $i < count($_POST['check']); $i++)
			{
			$access_id=$_POST['check'][$i];

			$data = array(
			'user_id' => $uid,
			'access_id'   => $access_id,
			'resource_type'   => 'M',
			);
			$this->db->insert('user_access', $data);
			}
			$insert_id = $this->db->insert_id() ;
		}

		if(isset($_POST['check_add']))
		{
			for ($i = 0; $i < count($_POST['check_add']); $i++)
			{
				$access_id=$_POST['check_add'][$i];
				$page_id= $this->get_page_id($access_id,'add');

				$data = array(
				'user_id' => $uid,
				'menu_id'   => $access_id,
				'page_id'   => $page_id,
				'attribute'   => 'A',
				);
				$this->db->insert('page_access', $data);
			}
		}

		if(isset($_POST['check_edit']))
		{
			for ($i = 0; $i < count($_POST['check_edit']); $i++)
			{
			$access_id=$_POST['check_edit'][$i];
			$page_id= $this->get_page_id($access_id,'edit');

			$data = array(
				'user_id' => $uid,
				'menu_id'   => $access_id,
				'page_id'   => $page_id,
				'attribute'   => 'E',

			);
			$this->db->insert('page_access', $data);
			}
		}

		if(isset($_POST['check_delete']))
		{
			for ($i = 0; $i < count($_POST['check_delete']); $i++)
			{
			$access_id=$_POST['check_delete'][$i];
			$page_id= $this->get_page_id($access_id,'list');

			$data = array(
			'user_id' => $uid,
			'menu_id'   => $access_id,
			'page_id'   => $page_id,
			'attribute'   => 'D',

			);
			$this->db->insert('page_access', $data);
			}
		}
		
		if($insert_id)
		{
			$user_se_id=$this->session->userdata('user_id');
			$page_name=explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg=add_log_entry($user_se_id,1,$page_name[1],'user_access','access_id',$insert_id);
		}
		
		return $uid;
	}

	function get_page_id($menu_id,$page_type)
	{
		// $query= $this->db->query("select menu_url from menu_access where menu_id='$menu_id'");
		// $menu_url=$query->row('menu_url');
		// $query= $this->db->query("select page_name from breadcrumb where page_url='$menu_url'");
		// $page_name=$query->row('page_name');
		// if($page_name!=''){
		// 	$query= $this->db->query("select page_id from breadcrumb where page_name='$page_name' and page_type='$page_type' ");
		// 	$page_id=$query->row('page_id');
		// 	return $page_id;
		// }
		return 0;
	}

	//customer

	public function get_all_customer_list(){
		$this->db->select('*');
        $this->db->from('customer_master');
        $query = $this->db->get()->result();
        return $query; 
	}

    public function add_customer_data(){
        $data=array(
            'customer_name'=>$_POST['customer_name'],
            'customer_code'=>$_POST['customer_code'],
            'customer_email'=>$_POST['customer_email'],
            'contact_number'=>$_POST['contact_number'],
            'customer_address'=>$_POST['customer_address']
        );
		
        $res = $this->db->insert('customer_master',$data);
		$customer_id = $this->db->insert_id();
		if($res){
			$num_of_contacts = $_POST['num_rows'];
			for($i = 0 ; $i <= $num_of_contacts ; $i++){
				$data = array(
					'customer_id' => $customer_id,
					'contact_name' => $_POST['contact_name'][$i],
					'contact_phone' => $_POST['contact_phone'][$i],
					'contact_email' => $_POST['contact_email'][$i],
				);
				$this->db->insert('customer_contact_details', $data);
			}
		}
        return $res;
    }

    function get_active_customer_list()
    {
        $this->db->select('*');
        $this->db->from('customer_master');
        $this->db->where('active',1);
        $query = $this->db->get()->result();
        return $query;      
    }


	function get_active_customer_contacts($customer_id)
    {
        $this->db->select('*');
        $this->db->from('customer_contact_details');
        $this->db->where('customer_id',$customer_id);
        $query = $this->db->get()->result();
        return $query;      
    }

	// public function edit_user_data(){
	// 	$data=array(
	// 		'user_name'=>$_POST['user_name'],
	// 		'user_login'=>$_POST['user_login'],
	// 		'gender'=>$_POST['gender'],
	// 		'dob'=>$_POST['dob'],
	// 	);
	// 	$this->db->where('user_id',$_POST['user_id']);
	// 	$res = $this->db->update('users',$data);
	// 	return $res;
	// }

	public function set_discount_limit(){
		$res = $this->db->set('setting_value',$_POST['discount_limit'])
		->where('setting_name','discount_limit')
		->update('other_settings');

		return $res;
	}

	public function get_discount_limit(){
		$res = $this->db->where('setting_name','discount_limit')->get('other_settings')->row_array();
		return $res;
	}
	//currency
	function get_active_currency_list()
    {
        $this->db->select('*');
        $this->db->from('currency_master');
        $this->db->where('active',1);
        $query = $this->db->get()->result();
        return $query;      
    }

	//supplier
	function get_active_supplier_list()
    {
        $this->db->select('*');
        $this->db->from('supplier_master');
        $query = $this->db->get()->result();
        return $query;
	}

	public function get_all_supplier_list(){
		$this->db->select('*');
        $this->db->from('supplier_master');
        $query = $this->db->get()->result();
        return $query; 
	}

    public function add_supplier_data(){
        $data=array(
            'supplier_name'=>$_POST['supplier_name'],
            'supplier_code'=>$_POST['supplier_code'],
            'supplier_email'=>$_POST['supplier_email'],
            'contact_number'=>$_POST['contact_number'],
            'billing_address'=>$_POST['supplier_address']
        );
		
        $res = $this->db->insert('supplier_master',$data);
		$supplier_id = $this->db->insert_id();
		if($res){
			$num_of_contacts = $_POST['num_rows'];
			for($i = 0 ; $i <= $num_of_contacts ; $i++){
				$data = array(
					'supplier_id' => $supplier_id,
					'contact_name' => $_POST['contact_name'][$i],
					'contact_phone' => $_POST['contact_phone'][$i],
					'contact_email' => $_POST['contact_email'][$i],
				);
				$this->db->insert('supplier_contact_details', $data);
			}
		}
        return $res;
    }
	function delete_supplier($id){
		$this->db->query("delete from supplier_master where supplier_id='$id'");
		$this->db->query("delete from supplier_contact_details where supplier_id='$id'");
	}
	function get_warehouse_list(){
		$this->db->select('*');
        $this->db->from('warehouse_master');
        $query = $this->db->get()->result();
        return $query;
	}
function get_active_department_list()
	{
		$query=$this->db->query("select * from department_master where status=0 order by dept_name");
		return $query->result();
	}
function get_designation_list()
	{
		$query=$this->db->query("select * from designation_master order by designation_name");
		return $query->result();
	}

	function get_company_master_list() {
	    $query = $this->db->query("select * from company_master ");
	    return $query->result();
        }

		function get_vat_for_calculation()
	{
		$query=$this->db->query("select vat_percent from vat_master order by applicable_date desc limit 1");
		return $query->row('vat_percent');
	}
	function get_currency_list() {
		$query = $this->db->query("select * from currency_master");
		return $query->result();
	}

public function get_company_bank_list($branch_id)
{
    return $this->db
        ->where('branch_id', $branch_id)
        ->get('branch_bank_details')
        ->result();
}

public function get_supplier_by_id($supplier_id){
		$this->db->select('supplier_master.*, currency_master.currency_abbr');
        $this->db->from('supplier_master');
		$this->db->join('currency_master', 'supplier_master.currency_id = currency_master.currency_id', 'left');
		$this->db->where('supplier_master.supplier_id',$supplier_id);
        $query = $this->db->get()->row_array();
        return $query;
	}	

public function add_sales_area_data()
{
    $sales_area_name = trim($this->input->post('sales_area_name'));

    // CHECK DUPLICATE
    $exists = $this->db->where('sales_area_name', $sales_area_name)
                       ->get('sales_area_master')
                       ->row();

    if (!empty($exists)) {

        $this->session->set_flashdata('error', 'Sales Area Name already exists!');
        redirect('Setup/list_sales_area');
        return;
    }

    // AUTO CODE GENERATION
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

    // INSERT
    $data = array(
        'sales_area_code' => $code,
        'sales_area_name' => $sales_area_name,
        'description'     => $this->input->post('description'),
        'created_at'      => date('Y-m-d H:i:s')
    );

    $this->db->insert('sales_area_master', $data);

    $this->session->set_flashdata('success', 'Sales Area Added Successfully');
    redirect('Setup/list_sales_area');
}
public function get_all_sales_area_list()
{
    return $this->db->get('sales_area_master')->result();
}

public function add_commission_group_data()
{
    $data = array(
        'commission_group_code' => $this->input->post('commission_group_code'),
        'commission_group_name' => $this->input->post('commission_group_name'),
        'description'           => $this->input->post('description'),
        'created_at'            => date('Y-m-d H:i:s')
    );

    return $this->db->insert('commission_group_master', $data);
}
public function get_all_commission_group_list()
{
    return $this->db->get('commission_group_master')->result();
}

public function update_commission_group_data($id)
{
    $data = array(
        'commission_group_name' => $this->input->post('commission_group_name'),
        'description'           => $this->input->post('description'),
        'updated_at'            => date('Y-m-d H:i:s')
    );

    return $this->db->where('commission_group_id', $id)
                    ->update('commission_group_master', $data);
}
public function add_customer_group_data()
{
    $data = array(
        'customer_group_code' => $this->input->post('customer_group_code'),
        'customer_group_name' => $this->input->post('customer_group_name'),
        'description'         => $this->input->post('description'),
        'created_at'          => date('Y-m-d H:i:s')
    );

    return $this->db->insert('customer_group_master', $data);
}
public function get_all_customer_group_list()
{
    return $this->db->get('customer_group_master')->result();
}
public function get_customer_group_by_id($id)
{
    return $this->db->where('customer_group_id', $id)
                    ->get('customer_group_master')
                    ->row();
}
public function update_customer_group_data($id, $data)
{
    return $this->db->where('customer_group_id', $id)
                    ->update('customer_group_master', $data);
}
public function delete_customer_group($id)
{
    return $this->db->where('customer_group_id', $id)
                    ->delete('customer_group_master');
}
public function check_duplicate_customer_group($name, $id = null)
{
    $this->db->where('LOWER(customer_group_name)', strtolower($name));

    if ($id != null) {
        $this->db->where('customer_group_id !=', $id);
    }

    return $this->db->get('customer_group_master')->row();
}
public function get_last_sales_rep_code()
{
    return $this->db->select('sales_rep_code')
                    ->from('sales_rep_master')
                    ->order_by('sales_rep_id', 'DESC')
                    ->get()
                    ->row();
}
public function get_all_commission_groups()
{
    return $this->db->get('commission_group_master')->result();
}
public function add_sales_rep_data($data)
{
    return $this->db->insert('sales_rep_master', $data);
}
public function get_all_sales_rep_list()
{
    $this->db->select('sr.*, e.employee_name, cg.commission_group_name');
    $this->db->from('sales_rep_master sr');
    $this->db->join('employee_master e', 'e.employee_id = sr.emp_id', 'left');
    $this->db->join('commission_group_master cg', 'cg.commission_group_id = sr.commission_group_id', 'left');

    return $this->db->get()->result();
}
public function get_sales_rep_by_id($id)
{
    return $this->db->where('sales_rep_id', $id)
                    ->get('sales_rep_master')
                    ->row();
}
public function update_sales_rep_data($id, $data)
{
    return $this->db->where('sales_rep_id', $id)
                    ->update('sales_rep_master', $data);
}
public function get_all_employees()
{
    return $this->db->get('employee_master')->result();
}
}