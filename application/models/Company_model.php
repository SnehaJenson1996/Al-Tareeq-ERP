<?php
class Company_model extends CI_Model {
     public function __construct()
    {
        
    }

    // DEPARTMENT CODE START//
    public function get_all_departments($column = null, $value = null)
    {
        $this->db->from('department_master');

        if (!empty($column) && $value != '') {
            $this->db->like($column, $value);
        }

        $this->db->order_by('dept_id', 'DESC');

        return $this->db->get()->result();
    }

	public function add_department_data() 
	{
		$data = array(
		'dept_name' => $this->input->post('dept_name'),
		'remark' => $this->input->post('remark'),
		'created_by' => $this->session->userdata('user_id')
		);
		$this->db->insert('department_master', $data);
		$insert_id = $this->db->insert_id();

		if($insert_id)
		{
		$user_se_id=$this->session->userdata('user_id');
		$page_name=explode('index.php/', $_SERVER['PHP_SELF']);
		$ci = get_instance();
		$ci->load->helper('log');
		$log_msg=add_log_entry($user_se_id,1,$page_name[1],'department_master','dept_id',$insert_id);
		}
		return $insert_id;
	}

	public function update_department_data($dept_id) 
	{
		$data = array(
            'dept_name' => $this->input->post('dept_name'),
            'remark' => $this->input->post('remark'),
            'status' => $this->input->post('status'),
		);
		$this->db->where('dept_id',$dept_id);
		$res = $this->db->update('department_master', $data);

		if($res)
		{
            $user_se_id=$this->session->userdata('user_id');
            $page_name=explode('index.php/', $_SERVER['PHP_SELF']);
            $ci = get_instance();
            $ci->load->helper('log');
            $log_msg=add_log_entry($user_se_id,2,$page_name[1],'department_master','dept_id',$dept_id);
            return true;
		}else{
		    return false;
		}
	}

	public function get_department_list()
	{
		$query=$this->db->query("select * from department_master order by dept_name");
		return $query->result();
	}

	public function get_active_department_list()
	{
		$query=$this->db->query("select * from department_master where status=0 order by dept_name");
		return $query->result();
	}

	public function get_department_record_by_id($dept_id)
	{
		$query=$this->db->query("select * from department_master where dept_id='$dept_id' ");
		return $query->result();
	}

    // DEPARTMENT CODE END//

    public function generate_branch_code() {
        $this->db->select_max('branch_id'); // assuming branch_id is auto-increment primary key
        $query = $this->db->get('branch_master')->row();

        $next_id = $query->branch_id + 1;
        return str_pad($next_id, 2, '0', STR_PAD_LEFT); // e.g., 1 → 01, 2 → 02
    }
   
   
    public function get_all_branches($filter_type="",$filter_value=""){
		$this->db->select('*');
        $this->db->from('branch_master');
        if(!empty($filter_type)&&!empty($filter_value)){
            $this->db->like($filter_type, $filter_value);
        }
        $query = $this->db->get()->result();
        return $query; 
	}
   
    //Customer
    public function get_all_customer_list() {
        $this->db->select('customer_master.*, branch_master.branch_name');
        $this->db->from('customer_master');
        $this->db->join('branch_master', 'customer_master.branch_id = branch_master.branch_id');
        $query = $this->db->get()->result();
        return $query;
    }
    public function generate_customer_code() {
        $this->db->select_max('customer_id'); // assuming customer_id is auto-increment primary key
        $query = $this->db->get('customer_master')->row();

        $next_id = $query->customer_id + 1;
       return 'CUST' . str_pad($next_id, 3, '0', STR_PAD_LEFT);
    }
    public function insert_customer($data){
		$this->db->insert('customer_master', $data);        
        return $this->db->insert_id();
	}
    public function insert_customer_contacts($contacts) {
        $this->db->insert_batch('customer_contact_details', $contacts); 
        return $this->db->insert_id();   
    }
    public function get_customer_by_id($id) {
        $this->db->select('customer_master.*');
        $this->db->from('customer_master');
        $this->db->where('customer_id',$id);
        $query = $this->db->get()->result();
        return $query;
    }
     public function get_customer_contact_by_cust_id($id) {
        $this->db->select('*');
        $this->db->from('customer_contact_details');
        $this->db->where('customer_id',$id);
        $query = $this->db->get()->result();
        return $query;
    }
    public function update_customer($id, $data) {
        $this->db->where('customer_id', $id);
        return $this->db->update('customer_master', $data);
    }
    public function delete_customer_contacts($customer_id) {
        $this->db->where('customer_id', $customer_id);
        return $this->db->delete('customer_contact_details');
    }
    public function delete_customer($customer_id) {
        $this->db->where('customer_id', $customer_id);
        return $this->db->delete('customer_master');
    }
    public function get_customers_by_branch($branch_id)
    {
        return $this->db->select('customer_id, customer_name, customer_code, contact_number,customer_TR_no')
                    ->from('customer_master')
                    ->where('branch_id', $branch_id)
                    ->get()
                    ->result();
    }
    public function get_supplier_by_branch($branch_id)
    {
        return $this->db->select('supplier_id, supplier_name, supplier_code, contact_number,trn_no')
                    ->from('supplier_master')
                    ->where('branch_id', $branch_id)
                    ->get()
                    ->result();
    }
    ///User
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
    public function insert_user($data){        
        $res = $this->db->insert('users',$data);
        return $res;
    }
    public function update_user($id, $data) {
        $this->db->where('user_id ', $id);
        return $this->db->update('users', $data);
    }
    // public function delete_user($id) {
    //     return $this->db->delete('users', ['id' => $id]);
    // }

    public function delete_user($user_id)
{
    return $this->db->delete('users', ['user_id' => $user_id]);
}
    //Supplier
    public function generate_supplier_code() {
        $this->db->select_max('supplier_id'); // assuming customer_id is auto-increment primary key
        $query = $this->db->get('supplier_master')->row();

        $next_id = $query->supplier_id + 1;
       return 'CUST' . str_pad($next_id, 3, '0', STR_PAD_LEFT);
    }
    public function insert_supplier($data){
        $this->db->insert('supplier_master', $data);
        return $this->db->insert_id();
    }
    public function insert_supplier_contacts($contacts) {
        $this->db->insert_batch('supplier_contact_details', $contacts); 
        return $this->db->insert_id();   
    }
    public function get_all_supplier_list(){
		$this->db->select('*');
        $this->db->from('supplier_master');
        $query = $this->db->get()->result();
        return $query; 
	}
    public function get_supplier_by_id($id){
		$this->db->select('*');
        $this->db->from('supplier_master');
        $this->db->where('supplier_id',$id);
        $query = $this->db->get()->result();
        return $query; 
	}
    public function get_supplier_contact_by_sup_id($id){
		$this->db->select('*');
        $this->db->from('supplier_contact_details');
        $this->db->where('supplier_id',$id);
        $query = $this->db->get()->result();
        return $query; 
	}
    public function update_supplier($id, $data) {
        $this->db->where('supplier_id', $id);
        $this->db->update('supplier_master', $data);
    }
    public function delete_supplier_contact($id){
        $this->db->where('supplier_id', $id);
        return $this->db->delete('supplier_contact_details');
    }
    public function delete_supplier($id){
        $this->db->where('supplier_id', $id);
        return $this->db->delete('supplier_master');
    }
    //Employee
    public function insert_employee($data) {
        $this->db->insert('employee_master', $data);
        return $this->db->insert_id(); // returns the inserted employee_id
    }
    public function get_all_employees($filter_type="", $filter_value="") {
        //$this->db->select('e.*, b.branch_name, d.designation_name');
        $this->db->select('e.*, b.branch_name');
        $this->db->from('employee_master e');
        $this->db->join('branch_master b', 'b.branch_id = e.branch_id', 'left');
       // $this->db->join('designation_master d', 'd.id = e.designation_id', 'left');
       if(!empty($filter_type)&&!empty($filter_value)){
        $this->db->like($filter_type, $filter_value);
       }
        $this->db->order_by('e.employee_name', 'ASC');
        return $this->db->get()->result();
    }
    public function get_employee_by_id($id) {
        return $this->db->get_where('employee_master', ['employee_id' => $id])->row();
    }
    public function get_all_employees_designation_id($id,$des_id) {
        return $this->db
                ->get_where('employee_master', ['designation_id' => $id,'branch_id' => $des_id])
                ->result(); // list of employee objects
    }
    
   public function update_employee($id, $data){
        $this->db->where('employee_id', $id);
        return $this->db->update('employee_master', $data); // returns true/false
    } 

    public function delete_employee($id) {
        return $this->db->delete('employee_master', ['employee_id' => $id]);
    }
    public function get_last_employee_id(){
    $this->db->select('employee_id');
    $this->db->order_by('employee_id', 'DESC');
    $this->db->limit(1);
    $query = $this->db->get('employee_master');
    if($query->num_rows() > 0){
        return $query->row()->employee_id;
    } else {
        return 0;
    }
}


    //Designation
    public function get_all_designations($column = "", $filter_value = "") {
        if (!empty($column) && !empty($filter_value)) {
            $this->db->where($column, $filter_value);
        }
        return $this->db->get('designation_master')->result();
    }
    public function generate_designation_code() {
        $this->db->select_max('id'); 
        $query = $this->db->get('designation_master')->row();

        $next_id = $query->id + 1;
        return 'DESG' . str_pad($next_id, 3, '0', STR_PAD_LEFT);
    }
    public function insert_designation($data) {
        $this->db->insert('designation_master', $data);
    }
    public function get_designation_by_id($id) {
        return $this->db->get_where('designation_master', ['id' => $id])->row();
    }
    public function update_designation($id, $data){
        $this->db->where('id', $id);
        return $this->db->update('designation_master', $data);
    }
    public function delete_designation($id) {
        return $this->db->delete('designation_master', ['id' => $id]);
    }
    public function get_currency_list($only_active = true)
    {
        $this->db->select('currency_id, currency_abbr, currency_name, active');
        $this->db->from('currency_master');

        // Optionally fetch only active currencies
        if ($only_active) {
            $this->db->where('active', 1);
        }

        $this->db->order_by('currency_abbr', 'ASC');
        $query = $this->db->get();

        return $query->result();
    }

      public function get_branch_bank_by_branch_id_new($branch_id)
{
    return $this->db
        ->get_where('branch_bank_details', ['branch_id' => $branch_id])
        ->row();
}

}