<?php
class Warranty_model extends CI_Model {

    public function __construct()
    {
        
    }

    public function get_all_warranty_claims($status){
        $this->db->select('*');
        $this->db->from('warranaty_claim_master');
        $this->db->where('warranty_status',$status);
        $this->db->order_by('warranty_code','DESC');
        $res = $this->db->get()->result();

        return $res;
    }

    public function add_warranty_claim_data(){
        
        $prefix='AVE/WAR/';
		$this->load->model('Setup_model');
		$num = $this->Setup_model->get_next_code($prefix,'warranty_code','warranaty_claim_master',9)+1;
		$digit=sprintf("%1$05d",$num);
		$warranty_code =$prefix.$digit;
       
        $data = array(
            'warranty_code' => $warranty_code,
            'warranty_date'  => date('Y-m-d'),
            'created_by'    => $this->session->userdata('user_id'),
        );
        $this->db->insert('warranaty_claim_master', $data);
        $warranty_id = $this->db->insert_id();

        for($i=0 ; $i <= $_POST['row_count'] ; $i++){
            $data=array(
					'warranty_master_id' => $warranty_id,
					'serial_number' => $_POST['scanned_serial'][$i],
					'invoice_id' => $_POST['invoice_id'][$i],
					'remarks' => $_POST['remarks'][$i],
					
				);
				$this->db->insert('warranty_details',$data);
        }
    }

    function get_warranty_by_id($warranty_id){
        $this->db->select('*');
        $this->db->from('warranaty_claim_master');
        $res = $this->db->get()->row_array();

        return $res;
    }

    function get_warranty_details_by_id($warranty_id,$type){
        if($type==0){
        $this->db->select('wd.*,item.item_model,item_description,item.item_id,im.invoice_code');
        }
        else{
        $this->db->select('wd.*,item.item_model,item_description,item.item_id,im.invoice_code,sd2.serial_number AS issued_serial');

        }
        $this->db->from('warranty_details wd');
        $this->db->join('stock_details sd','wd.serial_number=sd.stock_id','left');
        $this->db->join('item_master item','sd.product_id=item.item_id','left');
        $this->db->join('invoice_master im','wd.invoice_id=im.invoice_id','left');
        if($type==1){
            $this->db->join('stock_details sd2','wd.warranty_detail_id=sd2.warranty_id','left');
        }
        $this->db->where('warranty_master_id',$warranty_id);
        $res = $this->db->get()->result();

        return $res;
    }

     public function update_warranty_claim_data(){
        
        $warranty_id = $this->db->insert_id();

        for($i=0 ; $i <= $_POST['row_count'] ; $i++){
            $data=array(
					'serial_number' => $_POST['scanned_serial'][$i],
					'invoice_id' => $_POST['invoice_id'][$i],
					'remarks' => $_POST['remarks'][$i],
					
				);
                $this->db->where('warranty_detail_id',$_POST['warranty_detail_id'][$i]);
				$this->db->update('warranty_details',$data);
        }
    }

    function issue_warranty_data(){

        $warranty_id = $_POST['warranty_id'];
        $res = 0;
        for($i=0 ; $i <= $_POST['row_count'] ; $i++){
            //fetch item details from stock
            $this->db->select('*');
            $this->db->from('stock_details sd');
            $this->db->where('serial_number',$_POST['scanned_serial'][$i]);
            $this->db->where('inv_type','Warranty');
            $this->db->where('status','0');
            $this->db->order_by('stock_date');
            $this->db->limit(1);
            $stock = $this->db->get()->row_array();
            if(!empty( $stock)){
                $this->db->set('status','2');
                $this->db->set('warranty_id',$_POST['warranty_detail_id'][$i]);
                $this->db->where('stock_id',$stock['stock_id']);
                $res = $this->db->update('stock_details');

                $res = $this->db->set('issue_status',1)->where('warranty_detail_id',$_POST['warranty_detail_id'][$i])->update('warranty_details');
            }

            if($_POST['issue_status']==1){
                $this->db->set('warranty_status',1);
                $this->db->set('issued_by',$this->session->userdata('user_id'));
                $this->db->set('issued_at',date('Y-m-d'));
                $this->db->where('warranty_id',$warranty_id);
                $res = $this->db->update('warranaty_claim_master');
            }
            
        }

        return $res;
        
    }
}