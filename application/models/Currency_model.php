<?php
class Currency_model extends CI_Model {
     public function __construct()
    {
        
    }    
    public function get_all_currency_list(){
		$this->db->select('*');
        $this->db->from('currency_master');
        $query = $this->db->get()->result();
        return $query; 
	}
    public function get_currency_by_id($id){
		$this->db->select('*');
        $this->db->from('currency_master');
        $this->db->where('currency_id',$id);
        $query = $this->db->get()->result();
        return $query; 
	}
}