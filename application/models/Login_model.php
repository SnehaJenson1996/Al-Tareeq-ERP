<?php
class Login_model extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    function validate()
    {
        $user_login =$this->input->post('user_login');
        $user_password =$this->input->post('user_password');      

		$sanitized_user_login =   mysqli_real_escape_string($this->db->conn_id, $user_login); 
	      
		$sanitized_user_password =  mysqli_real_escape_string($this->db->conn_id, $user_password);
	    
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('user_login',$sanitized_user_login);
        $this->db->where('user_password',$sanitized_user_password);
        $this->db->where('active',1);
        $query = $this->db->get()->result();
        return $query;      
    }
}