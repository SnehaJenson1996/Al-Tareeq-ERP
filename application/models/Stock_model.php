<?php
defined('BASEPATH') OR exit('No direct script access allowed');
Class Stock_Model extends CI_Model
{
 function stock_adjustment_details()
   {
        $d1= date('Y');
	$prifix='Adj/'.$d1.'/';
	$this->load->model('Setup_model');
	$num= $this->Setup_model->get_next_code($prifix,'stock_code','stock_adjustment',10)+1;
	$digit=sprintf("%1$04d",$num);
	$data['Code'] =$prifix.$digit;
	//echo '<pre>';print_r($_POST);exit;
	$data = array(
	    'stock_code' => $data['Code'],
	    'stock_date'  => date('Y-m-d',strtotime($this->input->post('date'))),
	    'warehouse_id'  => $this->input->post("warehouse_id"),
	    'stock_type' => $this->input->post("inward_type"),
	    'product_id'  => $this->input->post("product_id"),
	    //'order_code'  => $this->input->post("order_code"),
   	    'item_desc'  => $this->input->post("desc"),
   	    //'brand'  => $this->input->post("brand"),
   	   // 'model_code'  => $this->input->post("stock_code"),
	    'remark'  => $this->input->post("remark"),
	    'created_by'    => $this->session->userdata('user_id'),
	    'created_date' =>  date('Y-m-d H:i:s'),
	);
	$this->db->insert('stock_adjustment', $data);
	$insert_id = $this->db->insert_id();
	
	if($this->input->post("min_stock_qty")>0)
	{
		$this->add_min_stock_qty();
	}
	
	if($this->input->post("inward_type")=='Opening' ||  $this->input->post("inward_type")=='IN')
		$intype='IN';
	else
		 $intype='OUT';
  	//for ($i = 0; $i < count($_POST["bill_entry"]); $i++)
	//{
		//for($k = 0; $k < $_POST['qty'][$i]; $k++)
		//{
		$data2 = array(
		    'trans_id' => $insert_id,
		    'stock_date' => date('Y-m-d',strtotime($this->input->post('date'))),
		    'stock_type' => $intype,
	    	'warehouse_id' => $this->input->post("warehouse_id"),
		    'product_id'  => $this->input->post("product_id"),
		    //'year'  => $_POST['year'][$i],
    		//'order_code'  => $this->input->post("order_code"),
    		'item_desc'  => $this->input->post("desc"),
   	    	//'brand'  => $this->input->post("brand"),
   	        //'model_code'  => $this->input->post("stock_code"),
	   	    'bill_no'  => $_POST['bill_entry'],// $_POST['bill_entry'][$i],
	   	    'order_ref_no'  => $_POST['ref_no'],//$_POST['ref_no'][$i],
	   	    //'box_no'  => $_POST['box_no'][$i],
	   	    'quantity'  => $_POST['qty'],//1,
	   	    'price'  => $_POST['price'],//$_POST['price'][$i],
	   	    'storage_location' => $_POST['storage_location'],//$_POST['storage_location'][$i],
	   	    'item_remark' =>  $_POST['item_remark'],//$_POST['item_remark'][$i],
		    'remark' => 'stock adjustment-'.$this->input->post("inward_type"),
	    	'created_by'    => $this->session->userdata('user_id'),
		    'created_date' =>  date('Y-m-d H:i:s'),
		);
		$this->db->insert('stock_details', $data2);
		//}
	//}//end for
	if($insert_id)
        {
            $user_se_id=$this->session->userdata('user_id');
            $page_name=explode('index.php/', $_SERVER['PHP_SELF']);
            $ci = get_instance();
            $ci->load->helper('log');
            $log_msg=add_log_entry($user_se_id,1,$page_name[1],'stock_adjustment','sno',$insert_id);

        }
	return $insert_id;
   }
     //end for
        // if($insert_id)
        //     {
        //         $user_se_id=$this->session->userdata('user_id');
        //         $page_name=explode('index.php/', $_SERVER['PHP_SELF']);
        //         $ci = get_instance();
        //         $ci->load->helper('log');
        //         $log_msg=add_log_entry($user_se_id,1,$page_name[1],'stock_adjustment','sno',$insert_id);

        //     }
       
    function get_stock_adjustment_list(){
        $query=$this->db->query("SELECT a.*,i.item_code,i.item_model FROM stock_adjustment a left join item_master i on a.product_id=i.item_id order by a.stock_date desc");
	    return $query->result();
    }
    // function get_stock_adjustment_by_id($doc_id){
    //     $query = $this->db->query("select * from stock_adjustment where sno =$doc_id");
    //     return $query->result();
    // }

    function get_stock_adjustment_by_id($doc_id)
{
    $query = $this->db->query("
        SELECT sa.*, 
               im.item_name,
               im.item_model,
               im.item_description,
               um.unit_name,
               bm.brand_name
        FROM stock_adjustment sa
        LEFT JOIN item_master im ON sa.product_id = im.item_id
        LEFT JOIN unit_master um ON im.item_unit = um.unit_id
        LEFT JOIN brand_master bm ON im.item_brand = bm.brand_id
        WHERE sa.sno = '$doc_id'
    ");

    return $query->result();
}
    function get_stock_adjustment_tr($id){
       $query=$this->db->query("select *, coalesce(sum(quantity),0)as total_qty from stock_details where trans_id =$id and remark like 'stock adjustment%' group by bill_no,order_ref_no");
        return $query->result();
    }
    function update_stock_adjustment_records(){
        $stock_code = $this->input->post("stock_code");
        $sno = $this->input->post("sno");
        $data = array(
            
            'stock_date'  => date('Y-m-d',strtotime($this->input->post('stock_date'))),
            'warehouse_id'  => $this->input->post("warehouse_id"),
            'stock_type' => $this->input->post("inward_type"),
            'product_id'  => $this->input->post("item"),
            'item_desc'  => $this->input->post("description"),
            'remark'  => $this->input->post("remark"),
        );

        $this->db->where('sno',$sno);
		$res = $this->db->update('stock_adjustment',$data);
        $query=$this->db->query("delete from stock_details where trans_id=$sno and status=0;");	
        // if($this->input->post("min_stock_qty")>0)
        // {
        //     $this->add_min_stock_qty();
        // }
        
        if($this->input->post("inward_type")=='Opening' ||  $this->input->post("inward_type")=='IN')
            $intype='IN';
        else
            $intype='OUT';
        for ($i = 0; $i < count($_POST["bill_entry"]); $i++)
        {
            for($k = 0; $k < $_POST['qty'][$i]; $k++)
            {
            $data2 = array(
                'trans_id'      => $insert_id,
                'stock_date'    => date('Y-m-d',strtotime($this->input->post('date'))),
                'stock_type'    => $intype,
                'warehouse_id'  => $this->input->post("warehouse_id"),
                'product_id'    => $this->input->post("item"),             
                'item_desc'     => $this->input->post("description"),
                'bill_no'       => $_POST['bill_entry'][$i],
                'order_ref_no'  => $_POST['ref_no'][$i],               
                'quantity'      => $_POST['qty'][$i],
                'price'         => $_POST['price'][$i],
                'storage_location' => $_POST['storage_location'][$i],
                'item_remark'   =>  $_POST['item_remark'][$i],
                'remark'        => 'stock adjustment-'.$this->input->post("inward_type"),
                'created_by'    => $this->session->userdata('user_id'),
                'created_date'  =>  date('Y-m-d H:i:s'),
            );
            $this->db->insert('stock_details', $data2);
            }
         }
        }
    function min_stock_add_records()
    {
       
	    	$data = array(
			'item_id' => $this->input->post("item"),
	   	    'min_stock_qty' => $this->input->post("min_stock_qty"),
		    'created_by'    => $this->session->userdata('user_id'),
		    'created_on' =>  date('Y-m-d H:i:s'),
		);
		$this->db->insert('min_stock_qty', $data);
		$insert_id = $this->db->insert_id();
		// if($insert_id)
		// {
		//     $user_se_id=$this->session->userdata('user_id');
		//     $page_name=explode('index.php/', $_SERVER['PHP_SELF']);
		//     $ci = get_instance();
		//     $ci->load->helper('log');
		//     $log_msg=add_log_entry($user_se_id,1,$page_name[1],'min_stock_qty','sno',$insert_id);

		// }
		return $insert_id;
	}
    function get_min_stock_list(){
        $query = $this->db->query("SELECT * FROM min_stock_qty a left join item_master b on a.item_id=b.item_id;");
        return $query->result();
    }
	function get_reorder_stock_list()
   {
    	$warehouse_id= $this->input->post("warehouse_id");
   		$query=$this->db->query("SELECT * FROM (SELECT two.item_desc,ONE.item_id, ONE.min_stock_qty,COALESCE(two.inv_stock, 0) AS invstock, COALESCE(three.po_stock, 0) AS postock, COALESCE(COALESCE(two.inv_stock, 0) + COALESCE(three.po_stock, 0), 0) AS total_stock FROM min_stock_qty AS ONE LEFT JOIN( SELECT SUM(quantity) AS inv_stock, product_id,item_desc FROM stock_details WHERE stock_type = 'IN' AND STATUS = '0' GROUP BY product_id, item_desc ) AS two ON ONE.item_id = two.product_id LEFT JOIN(SELECT COALESCE(SUM(quantity), 0) AS po_stock, product_id FROM purchase_order_master p JOIN purchase_order_transaction tr ON p.po_id = tr.po_master_id WHERE p.grn_status = 0 AND p.cancelled = 0 GROUP BY product_id) AS three ON ONE.item_id = three.product_id) AS tmp LEFT JOIN item_master pm ON tmp.item_id = pm.item_id WHERE tmp.total_stock <= tmp.min_stock_qty;");
   	    return $query->result();
   }
   function get_reorder_stock_for_PO()
    {
    	$model_code = $this->input->post("selected_tr"); 
    	$tmp ='';
    	$x= explode(',',$model_code);
    	for($k=0; $k<count($x);$k++)
    	{
    		$tmp = $tmp."'".$x[$k]."',";
    	}
        $model_code= $tmp."' '";
        
    	$query=$this->db->query("select r.order_code, p.description from reorder_stock_qty r, item_master p where r.product_id=p.product_id and r.product_id in($model_code)");
        return $query->result();
    }
    function delete_min_stock($id){
        $this->db->query("delete from min_stock_qty where item_id='$id'");
    }
//     function get_stock_inventory_report($wh_id,$item_id)
//     {
    	
//         $warehouse_id = !empty($wh_id) ? $wh_id : $this->input->post("warehouse_id");
//         $model_code   = !empty($item_id) ? $item_id : $this->input->post("product_id");
    	
//     	$itemcondition='';
//     	if($model_code!=''){
// 			$itemcondition="and s.product_id='$model_code'";
// 		}else{
// 			$itemcondition="and s.product_id is not null";
// 		}
    	 
// 	$query = $this->db->query("SELECT zero.*,(COALESCE(one.in_qty, 0) - COALESCE(two.out_qty, 0)) AS stock,four.allocation,five.price AS costprice,six.price as saleprice FROM (SELECT s.*, i.item_code, i.item_model FROM stock_details s JOIN item_master i ON s.product_id = i.item_id WHERE s.warehouse_id = '' $itemcondition GROUP BY s.product_id) AS zero LEFT JOIN (SELECT COALESCE(SUM(quantity), 0) AS in_qty, product_id FROM stock_details WHERE stock_type = 'IN' GROUP BY product_id) AS one ON zero.product_id = one.product_id LEFT JOIN (SELECT COALESCE(SUM(quantity), 0) AS out_qty, product_id FROM stock_details WHERE stock_type = 'OUT' GROUP BY product_id) AS two ON zero.product_id = two.product_id LEFT JOIN (SELECT COALESCE(SUM(allocation), 0) AS allocation, s.product_id FROM stock_details s JOIN item_master i ON s.product_id = i.item_id WHERE s.stock_type = 'IN' AND s.status = '0' GROUP BY s.product_id) AS four ON zero.product_id = four.product_id LEFT JOIN (SELECT product_id, price FROM stock_details WHERE stock_type = 'IN' ORDER BY stock_date DESC LIMIT 1) AS five ON zero.product_id = five.product_id LEFT JOIN (SELECT product_id, price FROM stock_details WHERE stock_type = 'OUT' ORDER BY stock_date DESC LIMIT 1) AS six ON zero.product_id = six.product_id;");
// //    echo $this->db->last_query();
// //    exit;
// 	return $query->result();
//     }

 function get_stock_inventory_report()
    {
    	
		$warehouse_id = $this->input->post("warehouse_id");
		$model_code = $this->input->post("product_id");
		$size = $this->input->post("size");

		$itemcondition = '';
		if ($model_code != '')
			$itemcondition = "and i.item_id='$model_code'";

		$query = $this->db->query("select zero.*, (coalesce(one.in_qty,0)-coalesce(two.out_qty,0)) as stock, four.allocation from (select s.*, i.item_code,i.item_name from stock_details s, item_master i where s.product_id=i.item_id and warehouse_id='$warehouse_id' $itemcondition group by product_id)as zero left join (select coalesce(sum(quantity),0)as in_qty,model_code,product_id from stock_details where stock_type='IN' group by product_id)as one on(zero.product_id=one.product_id) left join(select coalesce(sum(quantity),0)as out_qty, item_desc,sd.product_id from stock_details sd where stock_type='OUT' group by sd.product_id)as two on(zero.product_id=two.product_id) left join(select coalesce(sum(allocation),0)as allocation,s.product_id, model_code, item_code, item_name from stock_details s, item_master i where s.product_id=i.item_id and stock_type='IN' and status='0' group by i.item_id)as four on(zero.product_id=four.product_id)");
		//echo $this->db->last_query();exit;
		return $query->result();
    }


     //Stock Allocation
    function get_all_stock_allocations(){
        $this->db->select();
        $this->db->from('stock_allocation_master sam');
        $this->db->join('pi_master pi','sam.pi_master_id=pi.pi_id','left');
        $this->db->order_by('allocation_code','DESC');
        $res = $this->db->get()->result();

        return $res;
    }

    function get_stock_allocation_by_id($allocation_id){
        $this->db->select();
        $this->db->from('stock_allocation_master sam');
        $this->db->join('pi_master pi','sam.pi_master_id=pi.pi_id','left');
        $this->db->where('allocation_id',$allocation_id);
        $res = $this->db->get()->row_array();

        return $res;
    }

    function get_stock_allocation_details_by_id($allocation_id){
        
        $this->db->select();
		$this->db->from('stock_allocation_details sad');
        $this->db->join('stock_allocation_master sam','sad.allocation_master_id=sam.allocation_id');
        $this->db->join('pi_details pd','sad.pi_detail_id=pd.pi_detail_id','left');
		$this->db->join('estimation_details ed','pd.quotation_detail_id=ed.detail_id','left');
		$this->db->join('item_master im','ed.item_id=im.item_id','left');
        $this->db->join('brand_master bm','im.item_brand=bm.brand_id','left');
		$this->db->join('unit_master um','ed.unit_id=um.unit_id','left');
		$this->db->where('pd.detail_status >=',0);
		$this->db->where('sad.allocation_master_id',$allocation_id);
		$res = $this->db->get()->result();

		return $res;
    }

    function update_stock_allocation_data(){
        for($i=0 ; $i <= $_POST['row_count'] ; $i++){
            $allocation_detail_id = $_POST['allocation_detail_id'][$i];
            $new_allocation_quantity = $_POST['quantity'][$i];

            $this->db->select(); 
            $this->db->from('stock_details');
            $this->db->where('allocation_id', $allocation_detail_id);
            $query = $this->db->get();
            $rows = $query->result_array();
            $previous_allocation_quantity = count($rows);
            if($previous_allocation_quantity >  $new_allocation_quantity){
                $keep_ids = array_column(array_slice($rows, 0, $new_allocation_quantity), 'stock_id'); // IDs to keep as is
                $update_ids = array_column(array_slice($rows, $new_allocation_quantity), 'stock_id');  // IDs to update to alloc_id = 0     

                if (!empty($update_ids)) {
                    $this->db->where_in('stock_id', $update_ids);
                    $res = $this->db->update('stock_details', ['status'=>0,'allocation_id' => 0]);
                }
            }
            else if($previous_allocation_quantity <  $new_allocation_quantity){
                $added_quantity = $new_allocation_quantity - $previous_allocation_quantity;
                $this->db->select('*');
				$this->db->from('stock_details sd');
				$this->db->where('sd.product_id',$_POST['item_id'][$i]);
				$this->db->where('sd.status',0);
				$this->db->order_by('stock_date');
				$this->db->limit($added_quantity);
				$res = $this->db->get()->result_array();
                if(empty($res)){
                    	$allocated_quantity = 0;
				}
				else{
					$allocated_quantity = count($res);
                    foreach($res as $row){
							$data=array(
								'status' => 1,
								'allocation_id' => $allocation_detail_id,
							);
							$this->db->where('stock_id',$row['stock_id']);
							$this->db->update('stock_details',$data);
							
						}
				}
                $new_allocation_quantity = $previous_allocation_quantity + $allocated_quantity;
            }

            //update stock allocation detail table

            $this->db->where('allocation_detail_id', $allocation_detail_id);
            $res = $this->db->update('stock_allocation_details', ['allocated_quantity' => $new_allocation_quantity]);
        }
        return $res;
    }

    public function get_allocated_stock_details_by_id($allocation_detail_id){
        $this->db->select('serial_number,project');
        $this->db->from('stock_details');
        $this->db->where('status',1);
        $this->db->where('allocation_id',$allocation_detail_id);
        $res = $this->db->get()->result_array();

        return $res;
    }

    public function update_allocation_details_data(){
        $allocation_id   = $this->input->post('allocation_detail_id');
        $scanned_serials = $this->input->post('scanned_serial');   
        $allocated_qty = 0;
        $this->db->set('status',0);
        $this->db->set('allocation_id',0);
        $this->db->where('status',1);
        $this->db->where('allocation_id',$allocation_id);
        $res = $this->db->update('stock_details');

        foreach ($scanned_serials as $scan) {       
            if($scan != ''){
                $this->db->set('status',1);
                $this->db->set('allocation_id',$allocation_id);
                $this->db->where('serial_number',$scan);
                $res = $this->db->update('stock_details');
                $allocated_qty++;
            }
            
        }

        
        $res = $this->db->set('allocated_quantity',$allocated_qty)->where('allocation_detail_id',$allocation_id)->update('stock_allocation_details');

        return $res;
   
    }

    public function get_stock_item_status_by_serial_number($serial_no){
       
        $this->db->select('status');
        //$this->db->where('serial_no',$serial_no);
        $this->db->where('stock_id',$serial_no);
        $res = $this->db->get('stock_details')->row_array();
        
        return $res['status'];
    }

    public function get_item_detail_by_serial_number($serial_no){
       
        $this->db->select('item.item_model,item.item_description,item.item_id,im.invoice_id,im.invoice_code,sd.status,sd.inv_type');
        $this->db->from('stock_details sd');
        $this->db->join('item_master item','sd.product_id = item.item_id','left');
        $this->db->join('dn_details dd','sd.dc_id = dd.dn_detail_id','left');
        $this->db->join('invoice_details id','dd.invoice_detail_id = id.invoice_detail_id','left');
        $this->db->join('invoice_master im','id.invoice_master_id = im.invoice_id','left');
        $this->db->where('stock_id',$serial_no);
        $res = $this->db->get()->row_array();
        
        return $res;
    }

    function get_stock_code_list()
{
    $query = $this->db->query("
        SELECT 
            s.product_id,
            SUM(s.quantity) AS qty,
            i.item_id,
            i.item_code,
            i.item_name
        FROM stock_details s
        JOIN item_master i ON s.product_id = i.item_id
        GROUP BY s.product_id, i.item_code, i.item_name
    ");

    return $query->result();
}
    
}