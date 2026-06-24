<?php
class SampleRequest_model extends CI_Model {

    public function __construct()
    {
        
    }

    //sample request

	function list_sample_requests($status=''){
		$this->db->select('*');
		$this->db->from('sample_request_master srm');
		$this->db->join('enquiry_master enq','srm.enquiry_id=enq.enquiry_id','left');
		$this->db->join('customer_master cust','enq.enquiry_customer=cust.customer_id','left');
		if($status != ''){
			$this->db->where('request_status',$status);
		}
		$this->db->order_by('srm.request_code','DESC');
		$res = $this->db->get()->result_array();
		return $res;
	}

    // function get_all_approved_requests(){
    //     $this->db->select('*');
	// 	$this->db->from('sample_request_master srm');
	// 	$this->db->join('enquiry_master enq','srm.enquiry_id=enq.enquiry_id','left');
	// 	$this->db->join('customer_master cust','enq.enquiry_customer=cust.customer_id','left');
    //     $this->db->where('request_status',1);
	// 	$res = $this->db->get()->result();
	// 	return $res;
    // }

	function add_sample_request_data(){
		$prefix='AVSRF#';
		$this->load->model('Setup_model');
		$num = $this->Setup_model->get_next_code($prefix,'request_code','sample_request_master',7)+1;
		$digit=sprintf("%1$05d",$num);
		$return_code =$prefix.$digit.'_'.date('y');

		$data=array(
			'request_code' => $return_code,
			'request_date' => date('Y-m-d'),
			'enquiry_id' => $_POST['enquiry_id'],
			'return_date' => $_POST['return_date'],
			'created_by' => $this->session->userdata('user_id'),
		);
		$res = $this->db->insert('sample_request_master',$data);
		$request_id = $this->db->insert_id();

		for($i=0 ; $i < $_POST['row_count'] ; $i++){

			if($_POST['quantity'][$i] > 0){
				$data=array(
					'request_master_id' => $request_id,
					'item_id' => $_POST['item'][$i],
					'quantity' => $_POST['quantity'][$i],
				);
				$res = $this->db->insert('sample_request_details',$data);
			}
			
		}
		
		return $res;
	}

	function get_sample_request_by_id($request_id){
		$this->db->select('srm.*,enq.enquiry_code,cust.customer_name,u1.user_name as approved_by,u2.user_name as issued_by,u3.user_name as requested_by');
		$this->db->from('sample_request_master srm');
		$this->db->join('enquiry_master enq','srm.enquiry_id=enq.enquiry_id','left');
		$this->db->join('customer_master cust','enq.enquiry_customer=cust.customer_id','left');
		$this->db->join('users u1','srm.approved_by=u1.user_id','left');
		$this->db->join('users u2','srm.issued_by=u2.user_id','left');
		$this->db->join('users u3','srm.created_by=u3.user_id','left');
		$this->db->where('request_id',$request_id);
		$res = $this->db->get()->row_array();

		return $res;
	}

	function get_sample_request_details($request_id,$type){
		if($type == 1){
			$this->db->select('srd.*,im.item_model,im.item_description,um.unit_name,bm.brand_name,sum(COALESCE(sd.quantity,0)) as stock');
		}
		else{
			$this->db->select('srd.*,im.item_model,im.item_description,um.unit_name,bm.brand_name,GROUP_CONCAT(issued.serial_number ORDER BY issued.serial_number) AS stock_ids');
		}
		$this->db->from('sample_request_details srd');
		$this->db->join('item_master im','srd.item_id=im.item_id','left');
		$this->db->join('unit_master um','im.item_unit=um.unit_id','left');
		$this->db->join('brand_master bm','im.item_brand=bm.brand_id','left');
		if($type == 1){
			$this->db->join('stock_details sd','srd.item_id=sd.product_id and status = 0','left');
        	$this->db->group_by('sd.product_id');
		}
		else{
			$this->db->join('sample_request_issued_units issued', 'issued.request_detail_id = srd.request_detail_id', 'left');
			$this->db->where('srd.issued_qty >',0);
			$this->db->group_by('srd.request_detail_id');
		}
        
		$this->db->where('request_master_id',$request_id);

		$res = $this->db->get()->result();

		return $res;
	}
	

    function update_sample_request_data(){
        for($i=0 ; $i < $_POST['row_count'] ; $i++){

			if($_POST['quantity'][$i] > 0){
				$data=array(
					'quantity' => $_POST['quantity'][$i],
				);
				$this->db->where('request_detail_id',$_POST['request_detail_id'][$i]);
                $res = $this->db->update('sample_request_details',$data);
			}
            else{
                $this->db->where('request_detail_id',$_POST['request_detail_id'][$i])->delete('sample_request_details');
            }
			
		}
    }

    function approve_sample_request($request_id){

        $this->db->set('request_status',1);
		$this->db->set('approved_by',$this->session->userdata('user_id'));
		$this->db->set('approval_date',date('Y-m-d'));
        $this->db->where('request_id',$request_id);
        $res = $this->db->update('sample_request_master');

        return $res;
    }

    function issue_request_data(){
        $request_id = $_POST['request_id'];
        for($i=0 ; $i <= $_POST['row_count'] ; $i++){
			$issued_qty = $_POST['quantity'][$i];
			if($issued_qty > 0){
				$this->db->select('*');
                $this->db->from('stock_details sd');
                $this->db->where('product_id',$_POST['item_id'][$i]);
                $this->db->where('status',0);
                $this->db->order_by('stock_date');
                $this->db->limit($issued_qty);
                $stock = $this->db->get()->result();
                foreach($stock as $row){
                    $this->db->where('stock_id',$row->stock_id);
                    $res = $this->db->update('stock_details',['status'=>3,'request_id'=>$_POST['request_detail_id'][$i]]);

					$this->db->insert('sample_request_issued_units',['request_detail_id' =>$_POST['request_detail_id'][$i],'serial_number' => $row->stock_id]);
                }
			}
			$res = $this->db->set('issued_qty', "issued_qty + {$issued_qty}", FALSE)->where('request_detail_id',$_POST['request_detail_id'][$i])->update('sample_request_details');
        	
		}
		$this->db->where('request_id',$request_id);
		$res = $this->db->update('sample_request_master',['request_status'=>2,'issued_by'=>$this->session->userdata('user_id')]);

		return $res;
    }

	function add_returned_sample_data(){
		for($i=0 ; $i <= $_POST['row_count'] ; $i++){
			$returned_stock = json_decode($_POST['scannedSerials'][$i], true);
			$returned_qty = $_POST['return_quantity'][$i];
			foreach($returned_stock as $return){
					$data=array('status'=>0,'request_id'=>0);
					$this->db->where('stock_id',$return);
					$res = $this->db->update('stock_details',$data);

					$this->db->where('serial_number',$return);
					$this->db->where('request_detail_id',$_POST['request_detail_id'][$i]);
					$res = $this->db->update('sample_request_issued_units',['return_status' => 1]);
			}
			$this->db->set('returned_qty', "returned_qty + {$returned_qty}", FALSE)->where('request_detail_id',$_POST['request_detail_id'][$i])->update('sample_request_details');
			
		}
		if($_POST['return_status'] == 'complete'){

			$res = $this->db->update('sample_request_master',['request_status'=>3]);
		}
	}

}