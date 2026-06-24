<?php

    class amc_model extends CI_Model {
 
    function add_new_amc_enquiry()
{
    /* ================= START TRANSACTION ================= */
    $this->db->trans_start();

    $enquiry_code = $this->input->post('amc_enq_code');
    $cust_id      = $this->input->post('customer_id');
    $inv_no       = $this->input->post('inv_no') ?? '';

    /* ================= CUSTOMER HANDLING ================= */
    if ($cust_id == 'new') {
        $prifix1 = 'CM';
        $num1    = $this->Setup_model->get_next_code($prifix1, 'cust_code', 'customer_master', 3);
        $Code1   = $prifix1 . sprintf("%04d", $num1);

        $data1 = array(
            'cust_code'    => $Code1,
            'cust_name'    => $this->input->post('customer_name'),
            'contact_no'   => $this->input->post('cust_mobile'),
            'email_id'     => $this->input->post('cust_email'),
            'created_by'   => $this->session->userdata('user_id'),
            'created_date' => date('Y-m-d H:i:s')
        );

        $this->db->insert('customer_master', $data1);
        $customer_id = $this->db->insert_id();
    } else {
        $customer_id = $cust_id;
    }

    /* ================= FILE UPLOAD HANDLING ================= */
    $other_file = '';
    if (!empty($_FILES['other_file']['name'])) {
        $allowedExts = array("jpeg","jpg","png","pdf","doc","docx");
        $temp        = explode(".", $_FILES["other_file"]["name"]);
        $extension   = strtolower(end($temp));

        if (in_array($extension, $allowedExts) && $_FILES["other_file"]["size"] <= 15728640) { // 15MB max
            $timestamp   = time();
            $other_file  = $timestamp . "_" . $_FILES['other_file']['name'];
            $file_tmp    = $_FILES["other_file"]["tmp_name"];
if (!move_uploaded_file($file_tmp, FCPATH . "public/uploaded_documents/" . $other_file)) {
                $this->session->set_flashdata('error', 'Failed to upload document. Please check permissions.');
            }
        } else {
            $this->session->set_flashdata('error', 'Invalid file format or size exceeds 15MB.');
        }
    }

    /* ================= ENQUIRY MASTER ================= */
    $data = array(
        'amc_enq_code'  => $enquiry_code,
        'enq_date'      => date('Y-m-d', strtotime($this->input->post('enq_date'))),
        'revision_date' => date('Y-m-d', strtotime($this->input->post('enq_date'))),
        'cust_id'       => $customer_id,
        'enq_type'      => $this->input->post('enquiry_type'),
        'client_ref'    => $this->input->post('client_ref'),
        'remark'        => $this->input->post('remark'),
        'invoice_no'    => $inv_no,
        'project_name'  => $this->input->post('project_name'),
        'other_file'    => $other_file,  // <-- store uploaded file name
        'created_by'    => $this->session->userdata('user_id'),
        'created_date'  => date('Y-m-d H:i:s')
    );

    $this->db->insert('amc_enquiry_master', $data);
    $enquiry_id = $this->db->insert_id();

    /* ================= ENQUIRY ITEMS ================= */
    $prod_ids = $this->input->post('prod_id');
    $brands   = $this->input->post('brand');
    $models   = $this->input->post('model');
    $qtys     = $this->input->post('qty');

    for ($i = 0; $i < count($qtys); $i++) {
        if (empty($qtys[$i]) || $qtys[$i] <= 0) continue;

        $item_data = array(
            'enquiry_id' => $enquiry_id,
            'product_id' => $prod_ids[$i] ?? '',
            'brand'      => $brands[$i] ?? '',
            'model'      => $models[$i] ?? '',
            'quantity'   => $qtys[$i],
            'approved'   => 1
        );
        $this->db->insert('amc_enquiry_transaction', $item_data);
    }

    /* ================= LOG ================= */
    $this->load->helper('log');
    add_log_entry(
        $this->session->userdata('user_id'),
        1,
        uri_string(),
        'amc_enquiry_master',
        'amc_enq_id',
        $enquiry_id
    );

    /* ================= COMPLETE TRANSACTION ================= */
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
        return false;
    }

    return $enquiry_id;
}


    function update_enquiry_data() 
{
    $id = $this->input->post('amc_enq_id');  

    // Delete old transactions
    $this->db->where('enquiry_id', $id);
    $this->db->delete('amc_enquiry_transaction');

    // Update main enquiry
    $data = array(
        'amc_enq_code'  => $this->input->post('amc_enq_code'),
        'enq_date'      => date('Y-m-d', strtotime($this->input->post('enq_date'))),
        'revision_date' => date('Y-m-d', strtotime($this->input->post('enq_date'))),
        'cust_id'       => $this->input->post('cust_id'),
        'enq_type'      => $this->input->post('enquiry_type'),
        'client_ref'    => $this->input->post('client_ref'),
        'remark'        => $this->input->post('remark'),
        'created_by'    => $this->session->userdata('user_id'),
        'invoice_no'    => $this->input->post('inv_no'),
        'sales_person'  => $this->input->post('user_id'),
        'project_name'  => $this->input->post('project_name'),
        'created_date'  => date('Y-m-d H:i:s')
    );
    $this->db->where('amc_enq_id', $id);
    $res = $this->db->update('amc_enquiry_master', $data);

    // Handle file upload
    if (!empty($_FILES["other_file"]["name"])) {
        $allowedExts = array("jpeg","jpg","png","doc","pdf");
        $temp = explode(".", $_FILES["other_file"]["name"]);
        $extension = strtolower(end($temp));

        if (in_array($extension, $allowedExts) && $_FILES["other_file"]["size"] < 15728640) {
            if ($_FILES["other_file"]["error"] == 0) {
                $timestamp1 = time();
                $file_tmp = $_FILES["other_file"]["tmp_name"];
                $other_file = $timestamp1 . "_" . $_FILES['other_file']['name'];
move_uploaded_file($file_tmp, FCPATH."public/uploaded_documents/".$other_file);

                $this->db->where('amc_enq_id', $id);
                $this->db->update('amc_enquiry_master', ['other_file' => $other_file]);
            }
        } else {
            $this->session->set_flashdata('error','Invalid file or size exceeded');
        }
    }

    // Insert new transactions
    $prod_ids = $this->input->post('prod_id');
    $brands   = $this->input->post('brand');
    $models   = $this->input->post('model');
    $qtys     = $this->input->post('qty');

    if (!empty($prod_ids)) {
        for ($i = 0; $i < count($prod_ids); $i++) {
            if (!empty($prod_ids[$i]) && !empty($qtys[$i])) {
                $tdata = array(
                    'enquiry_id' => $id,
                    'product_id' => $prod_ids[$i],
                    'brand'      => $brands[$i],
                    'model'      => $models[$i],
                    'quantity'   => $qtys[$i],
                    'approved'   => 1
                );
                $this->db->insert('amc_enquiry_transaction', $tdata);
            }
        }
    }

    // Add log entry
    $user_se_id = $this->session->userdata('user_id');
    $page_name  = explode('index.php/', $_SERVER['PHP_SELF']);
    $this->load->helper('log');
    add_log_entry($user_se_id, 2, $page_name[1], 'amc_enquiry_master', 'amc_enq_id', $id);

    if ($res) return true;
}

	
    function get_enquiry_list()
	{
		$query=$this->db->query("select * from amc_enquiry_master  e, customer_master c where e.cust_id=c.customer_id order by enq_date desc, amc_enq_code desc");
		//echo $this->db->last_query();exit;
        return $query->result();
	}
 public function get_enquiry_record_by_id($id)
{
    $query = $this->db->select('e.*, c.customer_name as cust_name, c.customer_code as cust_code')
                      ->from('amc_enquiry_master e')
                      ->join('customer_master c', 'e.cust_id = c.customer_id', 'left')
                      ->where('e.amc_enq_id', $id)
                      ->get();

    return $query->result();
}


    function get_enquiry_trans_by_id($id)
	{
		$query=$this->db->query("select * from amc_enquiry_transaction where enquiry_id='$id'");
        return $query->result();
	}
    function get_amc_enquiry_list_for_qtn()
	{
		$query=$this->db->query("select * from amc_enquiry_master  e, customer_master c where e.cust_id=c.customer_id and feasibility in(1,2) and cancelled=0 and order_status=0 order by enq_date desc");
		return $query->result();
	}
    function get_enquiry_trans_for_quote($enq_id){
		$query=$this->db->query("select * from amc_enquiry_transaction  where enquiry_id='$enq_id'");
		return $query->result();
	}
    function get_amc_enquiry_record_by_id($id)
	{
		$query=$this->db->query("select e.*, c.cust_code, c.cust_name from amc_enquiry_master e, customer_master c where e.cust_id=c.customer_id and amc_enq_id='$id' ");
		return $query->result();
	}
    	//// Quotation start /////////////
	function add_quotation_data()
	{
		//echo '<pre>';print_r($_POST);exit;
		
		$enq_id = $this->input->post('enq_id');
		$enq_type = $this->input->post('enq_type');
		$code = $this->input->post('qcode');
		$cp_select=$this->input->post('cp_select');
		$data = array(
		'quotation_code' => $code,
		'quotation_date' => date('Y-m-d',strtotime($this->input->post('qdate'))),
		'revision_date'=> date('Y-m-d',strtotime($this->input->post('qdate'))),
		'enq_master_id' => $enq_id,
		'customer_id' => $this->input->post('customer_id'),
		'sub_total' => $this->input->post('sub_total'),
		'vat_amt' => $this->input->post('vat_amt'),
		'vat_percent' => $this->input->post('vat_percent'),
		'discount_percent' =>$this->input->post('discount'),
		'discount' => $this->input->post('discount_amt'),
		'amc_discount' => $this->input->post('amc_discount'),
		'currency_id' => $this->input->post('cid'),
		'currency_rate' => $this->input->post('crate'),		
		'grand_total' => $this->input->post('grand_total'),
		'payment_term' => $this->input->post('term1'),
		
		// 'amc_start_date'=> date('Y-m-d',strtotime($this->input->post('amc_start_datea'))),
        // 'amc_end_date'  => date('Y-m-d',strtotime($this->input->post('amc_end_datea')))  ,
		'validity'       => $this->input->post('validity'),
		'scope_work'     =>$this->input->post('scope_work'),
		'project_name'   =>$this->input->post('project_name'),
		'billing_addr'   => $this->input->post('billing_addr1'),
		'billing_city'   => $this->input->post('billing_city'),
		'billing_state'  => $this->input->post('billing_state'),
		'billing_pincode'=> $this->input->post('billing_po'),
		'billing_country'=> $this->input->post('billing_country'),
		'service_scheme' => $this->input->post('service_scheme'),	
		'bank_id' 		 =>  $this->input->post('bank'),
		'comp_id'  		 => $this->input->post('cmp_id'),		
		'cp_name' => $this->input->post("cp_name$cp_select"),
		'cp_mobile' => $this->input->post("cp_mobile$cp_select"),
		'cp_email' => $this->input->post("cp_email$cp_select"),
		'sales_person'=>$this->input->post('user_id'),	
		'created_by' => $this->session->userdata('user_id'),
		'created_date' => date('Y-m-d H:i:s')
		);
		$this->db->insert('amc_quotation_master', $data);
		$insert_id = $this->db->insert_id();
		
		if($this->input->post("cp_new$cp_select")==1)
		{
			$data = array(
			'cust_id' =>$this->input->post('customer_id'),
			'cp_name' => $this->input->post("cp_name$cp_select"),
			'cp_mobile' => $this->input->post("cp_mobile$cp_select"),
			'cp_email' => $this->input->post("cp_email$cp_select"),
		    );
	      	$this->db->insert('customer_contact_person', $data);
	    }
	        
		$query=$this->db->query("update amc_enquiry_master set order_status=1 where amc_enq_id=$enq_id");
		
		if($insert_id)
		{	
			
				for ($i = 0; $i < count($_POST['product_id']); $i++)
		        {		
			      $data1 = array(
					'quote_master_id' => $insert_id,
					'product_id' => $_POST['product_id'][$i],
                   // 'desc'  	=> $_POST['desc'][$i],
					'brand' 	=> $_POST['brand'][$i],
					//'capacity' 	=> $_POST['capacity'][$i],
					'quantity'   => $_POST['qty'][$i],	
					//'balance_qty'=> $_POST['qty'][$i],	
					'price'      => $_POST['price'][$i],
					//'dis_per'    => $_POST['dis_per'][$i],
					//'dis_val'    => $_POST['dis_val'][$i],
					'total'  	 => $_POST['total'][$i],
			      );
				 
			     $this->db->insert('amc_quotation_transaction', $data1);	
				
				}

                
                
			$data3 = array(
			'enq_id' => $enq_id,
			'status' => "Quotation generated $code",
			'status_date' =>  date('Y-m-d H:i:s'),
			);
			$this->db->insert('sales_order_status', $data3);
			
			$this->load->model('Users_model');
			$data['user_records']=$this->Users_model->get_active_user_list();
      
			$user_se_id=$this->session->userdata('user_id');
			$page_name=explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg=add_log_entry($user_se_id,1,$page_name[1],'amc_quotation_master','quote_id',$insert_id);
			/* notification */ 
			foreach($data['user_records'] as $r)
   			{
				$notice=add_notification($insert_id,$r->user_id,"AMC Quotation generated $code","amc/edit_quotation/$insert_id/1/0");
			}
			 /* end notification */
		}
		return $insert_id;
	}


    function update_quotation_data()
	{
        $enq_id = $this->input->post('enq_id');
		$enq_type = $this->input->post('enq_type');
		$code = $this->input->post('qcode');
		$cp_select=$this->input->post('cp_select');
        $qid = $this->input->post('qid');
		//echo '<pre>';print_r($this->input->post());exit;
		$data = array(
		'quotation_code' => $code,
		'quotation_date' => date('Y-m-d',strtotime($this->input->post('qdate'))),
		'revision_date'=> date('Y-m-d',strtotime($this->input->post('qdate'))),
		'enq_master_id' => $enq_id,
		'customer_id' => $this->input->post('customer_id'),
		'sub_total' => $this->input->post('sub_total'),
		'vat_amt' => $this->input->post('vat_amt'),
		'vat_percent' => $this->input->post('vat_percent'),
		'discount_percent' =>$this->input->post('discount'),
		'discount' => $this->input->post('discount_amt'),
		'amc_discount' => $this->input->post('amc_discount'),
		'currency_id' => $this->input->post('cid'),
		'currency_rate' => $this->input->post('crate'),		
		'grand_total' => $this->input->post('grand_total'),
		'payment_term' => $this->input->post('term1'),
		'comp_id'  => $this->input->post('cmp_id)'),
		// 'amc_start_date'=> date('Y-m-d',strtotime($this->input->post('amc_start_date'))),
        // 'amc_end_date'  => date('Y-m-d',strtotime($this->input->post('amc_end_date')))  ,
		'validity' => $this->input->post('validity'),
		'scope_work' =>$this->input->post('scope_work'),
		'billing_addr' => $this->input->post('billing_addr1'),
		'billing_city' => $this->input->post('billing_city'),
		'billing_state' => $this->input->post('billing_state'),
		'billing_pincode' => $this->input->post('billing_po'),
		'billing_country' => $this->input->post('billing_country'),
		'service_scheme' => $this->input->post('service_scheme'),	
		'bank_id' =>  $this->input->post('bank'),
		
		'cp_name' => $this->input->post("cp_name$cp_select"),
		'cp_mobile' => $this->input->post("cp_mobile$cp_select"),
		'cp_email' => $this->input->post("cp_email$cp_select"),
		'sales_person'=>$this->input->post('user_id'),	
		'created_by' => $this->session->userdata('user_id'),
		'created_date' => date('Y-m-d H:i:s')
		);
		

        $this->db->where('quote_id',$qid);
		$insert_id = $this->db->update('amc_quotation_master', $data);
		
        $this->db->query("delete from amc_quotation_transaction where quote_master_id=$qid"); 
        $this->db->query("delete from amc_workscope_quot where quote_master_id=$qid"); 
		
		
		if($insert_id)
		{	
			
				for ($i = 0; $i < count($_POST['product_id']); $i++)
		        {		
			      $data1 = array(
					'quote_master_id' => $qid ,
					'product_id' => $_POST['product_id'][$i],                   
					'brand' => $_POST['brand'][$i],
					// 'capacity' => $_POST['capacity'][$i],
					'quantity'   => $_POST['qty'][$i],	
					'price'      => $_POST['price'][$i],
					'total'  	 => $_POST['total'][$i],
			      );
				 
			     $this->db->insert('amc_quotation_transaction', $data1);	
				
				}

                
                
			
		}
		return $insert_id;
	}
    function get_quotation_list()
	{
		$query=$this->db->query("select e.*, c.customer_code, c.customer_name,i.amc_enq_code, i.enq_date, i.client_ref, i.enq_type from amc_quotation_master  e, customer_master c,amc_enquiry_master i  where e.customer_id=c.customer_id and e.enq_master_id=i.amc_enq_id and approval=0 order by quotation_date desc, quotation_code desc");
        return $query->result();
	}
 function get_quotation_master_by_id($id)
{   
    $sql = "
        SELECT 
            one.*,
            three.user_name,
            three.contact_no,
            four.currency_abbr,
            five.branch_name,
			five.branch_id,
             six.contact_name
        FROM (
            SELECT 
                e.*,
                c.customer_code,
                c.customer_name,
                c.customer_TR_no,
                 c.customer_email,
                c.contact_number,
                i.amc_enq_code,
                i.enq_date,
                i.client_ref,
                i.enq_type
            FROM amc_quotation_master e
            JOIN customer_master c 
                ON e.customer_id = c.customer_id
            JOIN amc_enquiry_master i 
                ON e.enq_master_id = i.amc_enq_id
            WHERE e.quote_id = ?
        ) AS one
        LEFT JOIN users AS three 
            ON one.sales_person = three.user_id
        LEFT JOIN currency_master AS four 
            ON one.currency_id = four.currency_id
        LEFT JOIN branch_master AS five 
            ON one.branch_id = five.branch_id

             LEFT JOIN customer_contact_details AS six
        ON one.customer_id = six.customer_id
    ";
    
    $query = $this->db->query($sql, [$id]);

    return $query->result();
}


    function get_quotation_tr_by_id($id)
	{
		$query=$this->db->query("select * from amc_quotation_transaction where quote_master_id='$id'  ");
		//echo $this->db->last_query();exit;
        return $query->result();
	}
    function get_quotation_scope_by_id($id){
        $query=$this->db->query("select * from amc_workscope_quot where quote_master_id='$id'");
       
        return $query->result();

    }
    function get_amc_quotations_for_invoice()
	{
		$query=$this->db->query("SELECT e.*, c.customer_code, c.customer_name, u.user_name FROM amc_quotation_master e LEFT JOIN customer_master c ON e.customer_id = c.customer_id LEFT JOIN users u ON e.created_by = u.user_id WHERE e.invoice_id = 0 ORDER BY e.quotation_date DESC;");
		//echo $this->db->last_query();exit;
        return $query->result();
	}
    function get_amc_quotation_balance_tr_by_id($id,$rev)
	{
		$query = $this->db->query("SELECT * FROM amc_quotation_transaction qt left join brand_master bm on bm.brand_id = qt.brand  WHERE qt.quote_master_id = '$id' AND qt.trans_revision = '$rev'  ORDER BY CAST(qt.srn AS UNSIGNED), qt.srn ASC;");
		//echo $this->db->last_query();exit;
		return $query->result();
	}
    	
	public function add_invoice_data()
{
    $type   = $this->input->post('inv_type');
    $prifix = $type . date('y');

    $this->load->model('Setup_model');

    // Generate next invoice code
    $numm  = $this->Setup_model->get_next_code($prifix, 'invoice_code', 'amc_invoice_master', 7);
    $num   = intval($numm) + 1;
    $digit = sprintf("%04d", $num);
    $code  = $prifix . date('m') . $digit;

    /* ================= MASTER INSERT ================= */
    $data = array(
        'invoice_code'      => $this->input->post('invcode'),
        'invoice_date'      => date('Y-m-d', strtotime($this->input->post('invdate'))),
        'enq_id'            => $this->input->post('enq_id'),
        'quote_id'          => $this->input->post('qid'),
        'customer_id'       => $this->input->post('customer_id'),
        'delivery_to'       => $this->input->post('customer_id'),
        'sub_total'         => $this->input->post('sub_total'),
		'amc_discount' => $this->input->post('amc_discount'),
        'discount_percent'  => $this->input->post('discount_percent'),
        'discount_amt'      => $this->input->post('discount_amt'),
        'vat_amt'           => $this->input->post('vat_amt'),
        'vat_percent'       => $this->input->post('vat_percent'),
        'grand_total'       => $this->input->post('grand_total'),
        'currency_id'       => $this->input->post('cid'),
        'currency_rate'     => $this->input->post('crate'),
        'paid_amt'          => $this->input->post('received_amt'),
        'received_percent'  => $this->input->post('received_percent'),
        'payment_mode'      => $this->input->post('payment_mode'),
        'payment_term'      => $this->input->post('term1'),
        'manufacture'       => $this->input->post('manufacture'),
        'origin'            => $this->input->post('origin'),
        'bank_id'           => $this->input->post('bank'),
        'sales_person'      => $this->input->post('user_id'),
        'created_by'        => $this->session->userdata('user_id'),
        'created_date'      => date('Y-m-d H:i:s'),
        'amc_start_date'    => $this->input->post('amc_start_date'),
        'amc_end_date'      => $this->input->post('amc_end_date'),
        'project_name'      => $this->input->post('project_name'),
        'conditions'        => $this->input->post('conditions'),
        'exclusions'        => $this->input->post('exclusions')
    );

    $this->db->insert('amc_invoice_master', $data);
    $insert_id = $this->db->insert_id();

    if (!$insert_id) {
        return false;
    }

    /* ================= TRANSACTION ITEMS ================= */
    $item_ids  = $this->input->post('product_id');
    $qtys      = $this->input->post('qty');
    $prices    = $this->input->post('price');
    $brands    = $this->input->post('brand');
    $trans_ids = $this->input->post('trans_id');
	$item_desc = $this->input->post('item_desc');

    if (!empty($item_ids) && is_array($item_ids)) {
        foreach ($item_ids as $i => $pid) {
            $item = array(
                'inv_master_id' => $insert_id,
				// 'trans_id'      => $trans_ids[$i] ?? null,
                'product_id'    => $pid,
                'brand'         => $brands[$i] ?? null,
                'quantity'      => $qtys[$i] ?? 0,
                'balance_qty'   => $qtys[$i] ?? 0,
                'price'         => $prices[$i] ?? 0,
                'total'         => ($prices[$i] ?? 0) * ($qtys[$i] ?? 0),
				'brand'         => $brands[$i] ?? '',
            );

            $this->db->insert('amc_invoice_transaction', $item);

            // Update quotation balance if trans_id exists
            if (!empty($trans_ids[$i])) {
                $this->db->where('trans_id', $trans_ids[$i])
                         ->update('amc_quotation_transaction', ['balance_qty' => 0]);
            }
        }
    }

	/* ================= SLA DETAILS ================= */
$sla_enabled = $this->input->post('sla_enabled');

if (!empty($sla_enabled) && $insert_id) {

    $service_item      = $this->input->post('service_item');
    $availability      = $this->input->post('service_availability_period');
    $response_time     = $this->input->post('response_time');
    $restoration_time  = $this->input->post('restoration_time');
    $resolution_time   = $this->input->post('resolution_time');

    if (!empty($service_item) && is_array($service_item)) {

        foreach ($service_item as $i => $item) {

            if (!empty($item)) {

                $sla = array(
                    'inv_master_id'              => $insert_id,
                    'service_item'               => $item,
                    'service_availability_period'=> $availability[$i] ?? '',
                    'response_time'              => $response_time[$i] ?? '',
                    'restoration_time'           => $restoration_time[$i] ?? '',
                    'resolution_time'            => $resolution_time[$i] ?? ''
                );

                $this->db->insert('amc_invoice_sla', $sla);
            }
        }
    }
}

/* ================= ANNEXURE DETAILS ================= */
$annexure_enabled = $this->input->post('annexure_enabled');

if (!empty($annexure_enabled) && $insert_id) {

    $sl_no    = $this->input->post('sl_no');
    $type     = $this->input->post('type');
    $location = $this->input->post('location');
    $qty      = $this->input->post('annex_qty');

    if (!empty($type) && is_array($type)) {

        foreach ($type as $i => $val) {

            if (!empty($val)) {

                $data = array(
                    'inv_master_id' => $insert_id,
                    'sl_no'         => $sl_no[$i] ?? ($i+1),
                    'type'          => $val,
                    'location'      => $location[$i] ?? '',
                    'quantity'      => $qty[$i] ?? 0
                );

                $this->db->insert('amc_invoice_annexure', $data);
            }
        }
    }
}
    /* ================= PAYMENT TERMS ================= */
    $from_dates = $this->input->post('from');
    if (!empty($from_dates) && is_array($from_dates)) {
        foreach ($from_dates as $i => $from) {
            $data1 = array(
                'inv_master_id'      => $insert_id,
                'ins_name'           => $this->input->post('ins_name')[$i] ?? '',
                'from_date'          => $from,
                'to_date'            => $this->input->post('to')[$i],
                'pay_date'           => $this->input->post('payment_date')[$i],
                'installment_amount' => $this->input->post('installment_amount')[$i] ?? 0
            );
            $this->db->insert('amc_payment_terms', $data1);
        }
    }

    /* ================= UPDATE ENQUIRY ================= */
    $enqid = $this->input->post('enq_id');
    if (!empty($enqid)) {
        $this->db->where('amc_enq_id', $enqid)
                 ->update('amc_enquiry_master', ['order_status' => 2]);
    }

    /* ================= LOG + NOTIFICATION ================= */
    $this->load->model('Users_model');
    $users = $this->Users_model->get_active_user_list();

    $user_se_id = $this->session->userdata('user_id');
    $page_name  = explode('index.php/', $_SERVER['PHP_SELF']);

    $ci = get_instance();
    $ci->load->helper('log');
    add_log_entry($user_se_id, 1, $page_name[1], 'amc_invoice_master', 'invoice_id', $insert_id);

    foreach ($users as $r) {
        add_notification(
            $insert_id,
            $r->user_id,
            "$type Invoice generated $code",
            "sales/edit_invoice/$insert_id/0"
        );
    }

    return $insert_id;
}

	function update_invoice_data($inv_id)
	{
		

		$data = array(
			'customer_id' 		=> $this->input->post('customer_id'),
			'delivery_to'		=>$this->input->post('customer_id'),
			'sub_total' 		=> $this->input->post('sub_total'),
			'discount_percent' 	=>$this->input->post('discount'),
			'discount_amt' 		=> $this->input->post('discount_amt'),
			'vat_amt' 			=> $this->input->post('vat_amt'),
			'vat_percent' 		=> $this->input->post('vat_percent'),	
			'grand_total' 		=> $this->input->post('grand_total'),			
			'currency_id' 		=>$this->input->post('cid'),
			'currency_rate' 	=> $this->input->post('crate'),
			'paid_amt' 			=> $this->input->post('received_amt'),
			'received_percent' 	=> $this->input->post('received_percent'),
			'payment_mode' 		=> $this->input->post('payment_mode'),
			'payment_term' 		=> $this->input->post('term1'),			
			
			'manufacture' 		=> $this->input->post('manufacture'),
			'origin' 			=> $this->input->post('origin'),
			'bank_id'			=> $this->input->post('bank'),	
			'sales_person'		=> $this->input->post('user_id'),	
			'scope_work'		=> $this->input->post('scope_work'),
			'service_scheme' 	=> $this->input->post('service_scheme'),		
			'created_by' 		=> $this->session->userdata('user_id'),
            'amc_start_date'	=> $this->input->post('amc_start_date'),
            'amc_end_date'  	=> $this->input->post('amc_end_date'),
			'project_name'      => $this->input->post('project_name'),
			'conditions'		=> $this->input->post('conditions'),
			'exclusions'		=> $this->input->post('exclusions')
		);
		
		
		$this->db->where('invoice_id',$inv_id);
		$this->db->update('amc_invoice_master', $data);

		$query=$this->db->query("delete from amc_invoice_transaction where inv_master_id='$inv_id' ");
			for ($i = 0; $i < count($_POST['product_id']); $i++)
		        {	
			      $data = array(
					'inv_master_id' => $inv_id,
					'product_id' 	=> $_POST['product_id'][$i],
					//'item_desc'  	=> $_POST['desc'][$i],
					'brand' 		=> $_POST['brand'][$i],
					//'capacity' 		=> $_POST['capacity'][$i],
					'quantity'  	=> $_POST['qty'][$i],							
					'balance_qty'  	=> $_POST['qty'][$i],
					'price'  		=> $_POST['price'][$i],
					//'brand' 		=> $_POST['brand'][$i],
					'total'  		=> $_POST['total'][$i],
					//'capacity'  	=> $_POST['capacity'][$i],
			      );
			      $this->db->insert('amc_invoice_transaction', $data);	
			      $trans_id1 = $this->db->insert_id();	
			     
                $trans_id=$_POST['trans_id'][$i];
                $bal_qty=0;
                $query=$this->db->query("update amc_quotation_transaction set balance_qty='$bal_qty' where trans_id='$trans_id'");
			    $append_id=$_POST['append_id'][$i];
			      
		        }
				$query=$this->db->query("delete from amc_payment_terms where inv_master_id='$inv_id' ");
				$ins_count = count($_POST['from']);
				
				if ($ins_count>0){
                for ($i = 0; $i < $ins_count; $i++)
		        {	
					
			      $data1 = array(
                    'inv_master_id' 	 => $inv_id,
					'ins_name'			 => $_POST['ins_name'][$i],
                    'from_date'			 => $_POST['from'][$i],
					'to_date' 			 => $_POST['to'][$i],
					'pay_date' 			 => $_POST['payment_date'][$i],
                    'installment_amount' => $_POST['installment_amount'][$i], 
			      );
				 
			      $this->db->insert('amc_payment_terms', $data1);	
			     
		        }}
		        	 
			$enqid= $this->input->post('enq_id');
			$revision= $this->input->post('revision');
			$qid=$this->input->post('qid');
			$query=$this->db->query("update amc_enquiry_master set order_status=2 where amc_enq_id='$enqid'");
		
			
			$this->load->model('Users_model');
			$data['user_records']=$this->Users_model->get_active_user_list();
      
			$user_se_id=$this->session->userdata('user_id');
			$page_name=explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg=add_log_entry($user_se_id,1,$page_name[1],'amc_invoice_master','invoice_id',$inv_id);
			
		
		return $inv_id;
	}
	function get_reminder_list()
       { 
            $query=$this->db->query("SELECT i.invoice_code, i.invoice_date, i.customer_id, i.amc_start_date, i.amc_end_date,c.customer_name, c.customer_email, i.grand_total, i.project_name FROM amc_invoice_master i JOIN customer_master c ON i.customer_id = c.customer_id WHERE i.amc_end_date <= DATE_ADD(CURDATE(), INTERVAL 2 MONTH) ORDER BY i.amc_end_date DESC LIMIT 50;"); 
            //echo $this->db->last_query();exit;
			return $query->result();
        }
		function get_ppm_rem_list()
{
    $query = $this->db->query("
        SELECT 
            pm.ppm_id,
            pm.ppm_code,
            ps.ppm_num,
            pm.quote_id,

            ps.id AS ppm_summary_id,

            ps.ppm_sch_date,
            ps.ppm_status,
            ps.completed_date,
            ps.invoice_generated,
            ps.invoice_id,

            qm.project_name

        FROM amc_ppm_master pm

        JOIN amc_ppm_summary ps 
            ON ps.ppm_master_id = pm.ppm_id

        LEFT JOIN amc_quotation_master qm 
            ON qm.quote_id = pm.quote_id

        ORDER BY ps.ppm_sch_date DESC

        LIMIT 50
    "); 

    return $query->result();
}
    //     function get_amc_list()
    //    { 
    //         $query=$this->db->query("select i.invoice_id, i.invoice_code,i.invoice_date,i.customer_id,i.amc_start_date,i.amc_end_date,c.customer_code,c.customer_name,i.grand_total,i.project_name,i.quote_id from amc_invoice_master i, customer_master c where i.customer_id = c.customer_id order by invoice_id desc;"); 
    //         return $query->result();
    //     }

	function get_amc_list()
{
    $this->db->select('
        q.quote_id,
        q.project_name,
        c.customer_name,
		c.customer_code,

        q.quotation_code,
        i.amc_start_date,
		i.amc_end_date,
		i.invoice_code,
		i.grand_total,
		i.invoice_id

    ');

    $this->db->from('amc_quotation_master q');
    $this->db->join('customer_master c', 'q.customer_id = c.customer_id');
    $this->db->join('amc_invoice_master i', 'i.quote_id = q.quote_id', 'inner');

    $this->db->where('q.project_name !=', '');
    $this->db->order_by('q.quote_id', 'DESC');

    return $this->db->get()->result();
}
		function get_amc_count()
		{ 
			 $query=$this->db->query("select count(*) as amc_count from amc_invoice_master  where  amc_end_date >= CURDATE();"); 
			 return $query->row_array(); 
		 }
function get_invoice_master_by_id($id)
{
    $query = $this->db->query("
        SELECT 
            e.*, 
            c.customer_name, c.customer_TR_no, c.contact_number, c.customer_email,
            m.quotation_code, m.quotation_date, m.revision, m.comp_id,m.project_name,m.quot_print_type,m.project_location, m.subject,m.scope_work,m.ppm_details,m.payment_term,m.branch_id,
            bb.*, 
            u.user_name, u.contact_no,
            cur.currency_abbr,
			cd.contact_name
        FROM amc_invoice_master e
        JOIN customer_master c ON e.delivery_to = c.customer_id
        JOIN amc_quotation_master m ON e.quote_id = m.quote_id
        JOIN amc_enquiry_master em ON e.enq_id = em.amc_enq_id
        LEFT JOIN branch_bank_details bb ON e.bank_id = bb.bid
        LEFT JOIN users u ON e.sales_person = u.user_id
        LEFT JOIN currency_master cur ON e.currency_id = cur.currency_id
		LEFT JOIN customer_contact_details cd  ON cd.customer_id = c.customer_id
        WHERE e.invoice_id = '$id'
        LIMIT 1
    ");

    return $query->row();   // ⭐ IMPORTANT (not result)
}

        function get_invoice_tr_by_id($id)
        {
            $query=$this->db->query("select * from amc_invoice_transaction  ir left join brand_master bm on bm.brand_id = ir.brand where ir.inv_master_id='$id' order by cast(srn as UNSIGNED) asc ");
            //echo $this->db->last_query();exit;
			return $query->result();
        }
        function get_payment_terms_by_id($id){
            $query=$this->db->query("select * from amc_payment_terms  where inv_master_id='$id'");
            return $query->result();
        }
        function get_amc_rem(){
            $query = $this->db->query("SELECT cm.cust_name,DATE_FORMAT(am.amc_start_date, '%Y-%m-%d') as amc_start_date,DATE_FORMAT(am.amc_end_date, '%Y-%m-%d') as amc_end_date,am.grand_total,am.project_name FROM amc_invoice_master am, customer_master cm WHERE am.customer_id = cm.customer_id and am.amc_end_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 100 DAY) limit 5;");
          //echo $this->db->last_query();exit;
            return $query->result();
        }
        function delete_enquiry($enquiry_id)
	{
		$query=$this->db->query("select count(*)as tcnt from amc_quotation_master where enq_master_id='$enquiry_id'");
		$tcnt = $query->row('tcnt');
		if($tcnt==0)
		{
			$query=$this->db->query("delete from amc_enquiry_transaction where enquiry_id='$enquiry_id'");
			$query=$this->db->query("delete from amc_enquiry_master where amc_enq_id='$enquiry_id'");
			
			$user_se_id=$this->session->userdata('user_id');
			$page_name=explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg=add_log_entry($user_se_id,3,$page_name[1],'amc_enquiry_master','amc_enq_id',$enquiry_id);
			return 1;
		}
		else 
			return 0;
	}
	function get_amc_customer_list(){
		$query=$this->db->query("select a.customer_id,b.customer_code,b.customer_name from amc_quotation_master a,customer_master b where a.customer_id=b.customer_id group by a.customer_id;");
		//echo $this->db->last_query();exit;
		 return $query->result();
	}
	function get_amc_project_list(){
    $query = $this->db->query("
        SELECT DISTINCT quote_id, project_name 
        FROM amc_quotation_master 
        ORDER BY project_name DESC
    ");
    return $query->result();
}
	function get_amc_quotation_list(){
		$query=$this->db->query("select quotation_code,quote_id,quotation_date from amc_quotation_master order by quote_id desc;");
		// echo $this->db->last_query();exit;
		 return $query->result();
		
	}
	function get_amc_quotation_report() {
    $start_date   = $this->input->post('from');
    $end_date     = $this->input->post('to');
    $cust_id      = $this->input->post('customer_id');
    $status       = $this->input->post('status');
    $project_name = $this->input->post('project_name');
    $date_type    = $this->input->post('date_type'); // if you want to use it later

    $where = [];

    if (!empty($cust_id)) {
        $where[] = "aqm.customer_id = " . $this->db->escape($cust_id);
    }

    if (!empty($start_date)) {
        $where[] = "aqm.quotation_date >= " . $this->db->escape($start_date);
    }

    if (!empty($end_date)) {
        $where[] = "aqm.quotation_date <= " . $this->db->escape($end_date);
    }

    if ($status !== '' && $status !== null) {
    if ($status == '0') {
        $where[] = "aqm.amc_discount > 0";
    } elseif ($status == '1') {
        $where[] = "(aqm.amc_discount = 0 OR aqm.amc_discount IS NULL)";
    }
		// No condition needed for status == '2' (All)
	}

    if (!empty($project_name)) {
        $where[] = "aqm.project_name LIKE " . $this->db->escape('%' . $project_name . '%');
    }

    $whereSQL = '';
    if (!empty($where)) {
        $whereSQL = 'WHERE ' . implode(' AND ', $where);
    }

    $query = $this->db->query("
        SELECT 
            aqm.quotation_code, aqm.quote_id, aqm.quotation_date,
            aqm.grand_total, aqm.amc_discount, aqm.revision,
            c.customer_id, c.cust_name
        FROM amc_quotation_master aqm
        LEFT JOIN customer_master c ON aqm.customer_id = c.customer_id
        $whereSQL
        ORDER BY aqm.quote_id DESC
    ");

    return $query->result();
}


	// function get_amc_report()
	// { 
	// 	$date_type = $this->input->post('date_type');
	// 	$start_date = $this->input->post('from');
	// 	$end_date = $this->input->post('to');
	// 	$cust_id = $this->input->post('customer_id');
	// 	$project_name = $this->input->post('project_name');
	// 	$rpt_type =$this->input->post('rpt_type');
	// 	if ($rpt_type =="Summary"){
	// 		$sql = "SELECT MONTHNAME(p.pay_date) AS month, i.project_name,i.amc_start_date,i.amc_end_date, SUM(i.grand_total) AS total_grand_total FROM amc_invoice_master i JOIN customer_master c ON i.customer_id = c.customer_id";
	// 	}
	// 	else if ($rpt_type =="Monthly Summary"){
	// 		$sql = "SELECT MONTHNAME(p.pay_date) AS month, i.project_name,i.amc_start_date,i.amc_end_date, SUM(i.grand_total) AS total_grand_total FROM amc_invoice_master i  JOIN customer_master c ON i.customer_id = c.customer_id";
	// 	}
		
	// 	else if (($rpt_type =="Detailed") && ($date_type == 'pay_date')){
	// 		$sql = "SELECT i.invoice_id, i.invoice_code, i.invoice_date, i.customer_id, i.amc_start_date, i.amc_end_date, c.cust_name, i.grand_total,i.project_name,p.*
	// 		FROM amc_invoice_master i join amc_payment_terms p on i.invoice_id = p.inv_master_id JOIN customer_master c ON i.customer_id = c.customer_id";
	// 	}else{
			
	// 			$sql = "SELECT '' as ins_name,'' as pay_date,'' as installment_amount,i.invoice_id, i.invoice_code, i.invoice_date, i.customer_id, i.amc_start_date, i.amc_end_date, c.cust_name, i.grand_total,i.project_name
	// 			FROM amc_invoice_master i JOIN customer_master c ON i.customer_id = c.customer_id";
			
	// 	}
		

	// 	$whereClause = [];

	// 	if (!empty($start_date) && !empty($end_date)) {
	// 		if ($date_type == 'amc_end_date') {
	// 			$whereClause[] = "i.amc_end_date BETWEEN '$start_date' AND '$end_date'";
	// 		} elseif ($date_type == 'invoice_date') {
	// 			$whereClause[] = "i.invoice_date BETWEEN '$start_date' AND '$end_date'";
	// 		}
	// 		elseif ($date_type == 'amc_start_date') {
	// 			$whereClause[] = "i.amc_start_date BETWEEN '$start_date' AND '$end_date'";
	// 		}
	// 		elseif ($date_type == 'pay_date') {
	// 			$whereClause[] = "p.pay_date BETWEEN '$start_date' AND '$end_date'";
	// 		}
	// 	}
		
	// 	if (!empty($cust_id)) {
	// 		$whereClause[] = "i.customer_id = '$cust_id'";
	// 	}

	// 	if (!empty($project_name)) {
	// 		$whereClause[] = "i.project_name = '$project_name'";
	// 	}

	// 	if (!empty($whereClause)) {
	// 		$sql .= " WHERE " . implode(" AND ", $whereClause);
	// 	}
	// 	if ($rpt_type=='Monthly Summary'){
	// 		$sql .= " GROUP BY DATE_FORMAT(p.pay_date, '%Y-%m')";
	// 	}
		
	// 	$sql .= " ORDER BY i.invoice_date DESC";
		

	// 	$query = $this->db->query($sql);
	// 	//echo $this->db->last_query();exit;
	// 	return $query->result();

	//  }

function get_amc_report()
{
    $date_type    = $this->input->post('date_type');
    $start_date   = $this->input->post('from');
    $end_date     = $this->input->post('to');
    $cust_id      = $this->input->post('customer_id');
    $project_id   = $this->input->post('project_name');
    $rpt_type     = $this->input->post('rpt_type');

    $this->db->from('amc_invoice_master i');
    $this->db->join('customer_master c', 'c.customer_id = i.customer_id', 'left');
    $this->db->join('amc_quotation_master qm', 'qm.quote_id = i.quote_id', 'left');

    // 🔥 COMMON FILTERS
    if (!empty($start_date) && !empty($end_date)) {

        if ($date_type == 'invoice_date') {
            $this->db->where('i.invoice_date >=', $start_date);
            $this->db->where('i.invoice_date <=', $end_date);
        }

        if ($date_type == 'amc_start_date') {
            $this->db->where('i.amc_start_date >=', $start_date);
            $this->db->where('i.amc_start_date <=', $end_date);
        }

        if ($date_type == 'amc_end_date') {
            $this->db->where('i.amc_end_date >=', $start_date);
            $this->db->where('i.amc_end_date <=', $end_date);
        }
    }

    if (!empty($cust_id)) {
        $this->db->where('i.customer_id', $cust_id);
    }

    if (!empty($project_id)) {
        $this->db->where('i.quote_id', $project_id);
    }

    // 🔥 SUMMARY MODE
    if ($rpt_type == 'Summary') {

        $this->db->select('
            qm.project_name,
            i.quote_id,
            SUM(i.grand_total) AS total_grand_total,
            MIN(i.amc_start_date) AS amc_start_date,
            MAX(i.amc_end_date) AS amc_end_date,
            DATE_FORMAT(MIN(i.invoice_date), "%M %Y") AS month
        ');

        $this->db->group_by('i.quote_id');

    } 
    // 🔥 DETAILED MODE
    else {

        $this->db->select('
            i.invoice_id,
            i.invoice_code,
            i.invoice_date,
            qm.project_name,
            i.amc_start_date,
            i.amc_end_date,
            i.grand_total,
            c.customer_name,
            DATE_FORMAT(i.invoice_date, "%M %Y") AS month
        ');

        $this->db->order_by('i.invoice_date', 'DESC');
    }

    return $this->db->get()->result();
}
	 function get_scope_of_work(){
		$query=$this->db->query("select distinct category from amc_work_scope_master ;");
		
		 return $query->result();
	 }
	 function get_service_schemes(){
		$query=$this->db->query("select distinct scheme_name from service_schemes ;");
		 return $query->result();
	 }
	 function get_work_scope_by_id($id){
		$query=$this->db->query("select * from amc_work_scope_master where category ='$id' ;");
		//echo $this->db->last_query();exit;
	    return $query->result();
	 }
	 function get_ser_sch($id){
		$query=$this->db->query("select * from service_schemes where scheme_name ='$id' ;");
		//echo $this->db->last_query();exit;
	    return $query->result();
	 }
	
	 function delete_invoice($quote_id,$invoice_id)
	 {

			 $query=$this->db->query("delete from amc_invoice_transaction where inv_master_id='$invoice_id'");
			 $query=$this->db->query("delete from amc_invoice_details where invoice_id='$invoice_id'");
			 $query=$this->db->query("delete from amc_invoice_master where invoice_id='$invoice_id'");
			 
			 $query=$this->db->query("update amc_quotation_master set invoice_id=0 where quote_id=$quote_id");
			 $query=$this->db->query("update amc_quotation_transaction set balance_qty=quantity where quote_master_id=$quote_id");
			 
			//  $user_se_id=$this->session->userdata('user_id');
			//  $page_name=explode('index.php/', $_SERVER['PHP_SELF']);
			//  $ci = get_instance();
			//  $ci->load->helper('log');
			//  $log_msg=add_log_entry($user_se_id,3,$page_name[1],'amc_invoice_master','invoice_id',$invoice_id);
			 return 1;
		
	 }

	 function add_complaint_data()
	 {
		$pname = $this->input->post('project_name');
		if ($pname == 'new'){
			$project_name = $this->input->post('newproject_name');
		}else{
			$project_name = $this->input->post('project_name');
		}
		 
		$allowedExts = array("jpeg","jpg","png");
		$data['file_name']=$_FILES["cmp_file"]["name"];
		$temp = explode(".", $_FILES["cmp_file"]["name"]);
		$extension = end($temp);

		$allowedExts2 = array("doc","pdf","docx");

$doc_file = "";

if (!empty($_FILES["doc_file"]["name"])) {

    $temp2 = explode(".", $_FILES["doc_file"]["name"]);
    $extension2 = strtolower(end($temp2));

    if ($_FILES["doc_file"]["size"] < 15728640 && in_array($extension2, $allowedExts2)) {

        if ($_FILES["doc_file"]["error"] == 0) {

            $timestamp1 = time();
            $file_tmp2 = $_FILES["doc_file"]["tmp_name"];

            $doc_file = $timestamp1 . "_" . preg_replace('/[^A-Za-z0-9.\-_]/', '_', $_FILES['doc_file']['name']);

            move_uploaded_file($file_tmp2, "public/uploaded_documents/" . $doc_file);
        }
    }
} 
		if (($_FILES["cmp_file"]["size"] < 15728640) && in_array($extension, $allowedExts))
		{	
		      if (($_FILES["cmp_file"]["error"] > 0))
		      {
				
				echo $_FILES["cmp_file"]["error"];
				$this->session->set_flashdata('error','Failed to upload - Please check file size and file format');
		      }
		      else
		      {
				echo "test";
				$timestamp1=time();				
				$file_tmp2 = $_FILES["cmp_file"]["tmp_name"];				
				$pic_file  = $timestamp1."_".$_FILES['cmp_file']['name'];	
				move_uploaded_file($file_tmp2,"public/uploaded_documents/".$pic_file);
				
		      }
		} 
		
			 $data = array(
			 'cmp_code'			=> $this->input->post('cmp_code'),
			 'project_name' 	=> $this->input->post('project_name'),
			 'cmp_date' 		=> date('Y-m-d',strtotime($this->input->post('cmp_date'))),
			 'flat_no'			=> $this->input->post('flat_no'),
			 'cmp_status'		=> $this->input->post('cmp_status'),
			 'receive_date'		=> $this->input->post('receive_date'),
			 //'visit_date'		=> $this->input->post('visit_date'),
			 'close_date'		=> $this->input->post('close_date'),
			 'cmp_file'			=> !empty($pic_file) ? $pic_file : '',
			 'doc_file'			=> !empty($doc_file)? $doc_file: '',
			 
			 'remarks' 			=> $this->input->post('cmp_remarks'),
			 'description' 		=> $this->input->post('description'),
			 'location' 		=> $this->input->post('location'),
			 'cmp_type' 		=> $this->input->post('cmp_type'),
			//  'cmp_eqp_type' 	=> $this->input->post('cmp_eqp_type'),
			 'mat_cost'			=> $this->input->post('mat_cost'),
			 'lab_cost'			=> $this->input->post('labor_cost')
			 
			 
		 );
		 $this->db->insert('amc_complaint_master', $data);
		 $insert_id = $this->db->insert_id();

		 if($insert_id)
		 {
			if (count($_POST['visit_date']) > 0){

			 for ($i = 0; $i < count($_POST['visit_date']); $i++)
				 {	
				   $data = array(
					'cmp_id' => $insert_id,
					'visit_date' => $_POST['visit_date'][$i],
					'technician'  => $_POST['technician'][$i],
					'expense'  	=> $_POST['expense'][$i],
					'remarks'  	=> $_POST['remarks'][$i],
					'hours'		=> $_POST['hours'][$i],	
				   );
				   $this->db->insert('cmp_visit_details', $data);	
				    
				 }
				}
				if(count($_POST['material']) > 0){
				 for ($i = 0; $i < count($_POST['material']); $i++)
				 {	
				   $data = array(
					'cmp_id' => $insert_id,
					'material' => $_POST['material'][$i],
					'qty'  	   => $_POST['qty'][$i],
					'cost'     => $_POST['cost'][$i],
					'total'    => $_POST['total'][$i],
				   );
				   $this->db->insert('cmp_mat_details', $data);	
				   
				 }
				 }
		 }
		 return $insert_id;
	 }
	 function get_complaint_list(){
			$query=$this->db->query("select * from amc_complaint_master order by cmp_id desc;"); 
            return $query->result();
	 }

	 function get_all_cmp_count(){
		$query=$this->db->query("select count(*) as cmp_count from amc_complaint_master ;"); 
		return $query->row_array();
	}
	function get_close_cmp_count(){
		$query=$this->db->query("select count(*) as cmp_close_count from amc_complaint_master where cmp_status='Closed' ;"); 
		return $query->row_array();
	}
	 function get_complaint_list_with_id($id){
			$query=$this->db->query("select * from amc_complaint_master where cmp_id=$id;"); 
			return $query->result();
	 }
	 function get_cmp_details1($id){
		$query=$this->db->query("select * from cmp_visit_details where cmp_id=$id;"); 
		return $query->result();
	}
	function get_cmp_details2($id){
		$query=$this->db->query("select * from cmp_mat_details where cmp_id=$id;"); 
		return $query->result();
	}
	function update_complaint_data($id)
	{

		$pic_file  = $doc_file ='';
		// 	 echo '<pre>';print_r($_POST);exit;	
		$allowedExts = array("jpeg","jpg","png");
		$data1['file_name']=$_FILES["cmp_file"]["name"];
		$temp = explode(".", $_FILES["cmp_file"]["name"]);
		$extension = end($temp);

		$allowedExts2 = array("doc","pdf");
		$data1['file_name2']=$_FILES["doc_file"]["name"];
		$temp2 = explode(".", $_FILES["doc_file"]["name"]);
		$extension2 = end($temp2);
		$data = array();
		if ((($_FILES["cmp_file"]["size"] < 15728640) && in_array($extension, $allowedExts)) || 
			(($_FILES["doc_file"]["size"] < 15728640) && in_array($extension2, $allowedExts2))) 
		{
			if (($_FILES["cmp_file"]["error"] > 0)) 
			{
				$this->session->set_flashdata('error', 'Failed to upload - Please check file size and file format');
			} 
			else 
			{
				$timestamp1 = time();
				if (!empty($data1['file_name'])) 
				{
					$file_tmp = $_FILES["cmp_file"]["tmp_name"];
					$pic_file = $timestamp1 . "_" . $_FILES['cmp_file']['name'];
					move_uploaded_file($file_tmp, "public/uploaded_documents/" . $pic_file);
					$data['cmp_file'] = $pic_file;
				}
			}
			if ($_FILES["doc_file"]["error"] > 0)
			{
				$this->session->set_flashdata('error', 'Failed to upload - Please check file size and file format');
			} 
			else 
			{	
				$timestamp1 = time();
				if (!empty($data1['file_name2'])) 
				{
					
					$file_tmp2 = $_FILES["doc_file"]["tmp_name"];
					$doc_file = $timestamp1 . "_" . $_FILES['doc_file']['name'];
					move_uploaded_file($file_tmp2, "public/uploaded_documents/" . $doc_file);
					$data['doc_file'] = $doc_file;
				}
				
			}
		}
			
				$data['cmp_code']		= $this->input->post('cmp_code');
				$data['project_name'] 	= $this->input->post('project_name');
				$data['cmp_date'] 		= date('Y-m-d',strtotime($this->input->post('cmp_date')));
				$data['flat_no']		= $this->input->post('flat_no');
				$data['cmp_status']		= $this->input->post('cmp_status');
				$data['receive_date']	= $this->input->post('receive_date');
				$data['close_date']		= $this->input->post('close_date');
				$data['remarks'] 		= $this->input->post('cmp_remarks');
				$data['description'] 	= $this->input->post('description');
				$data['location'] 		= $this->input->post('location');
				$data['cmp_type'] 		= $this->input->post('cmp_type');
				$data['cmp_eqp_type'] 	= $this->input->post('cmp_eqp_type');
				$data['mat_cost']		= $this->input->post('mat_cost');
				$data['lab_cost']		= $this->input->post('labor_cost');

		$this->db->where('cmp_id',$id);
		$res = $this->db->update('amc_complaint_master', $data);
		$this->db->query("delete from cmp_visit_details where cmp_id=$id"); 
		$this->db->query("delete from cmp_mat_details where cmp_id=$id"); 
		
		if($res)
		{
			if (isset($_POST['visit_date']) && is_array($_POST['visit_date']) && count($_POST['visit_date']) > 0) {
			for ($i = 0; $i < count($_POST['visit_date']); $i++)
			{	
			  $data = array(
			   'cmp_id'     => $id,
			   'visit_date' => $_POST['visit_date'][$i],
			   'technician' => $_POST['technician'][$i],
			   'expense' 	=> $_POST['expense'][$i],
			   'remarks'    => $_POST['remarks'][$i],
			   'hours'		=> $_POST['hours'][$i],	
			  );
			  $this->db->insert('cmp_visit_details', $data);	
			 // $trans_id1 = $this->db->insert_id();	 
			}}
			if (isset($_POST['material']) && is_array($_POST['material']) && count($_POST['material']) > 0) {
			for ($i = 0; $i < count($_POST['material']); $i++)
			{	
			  $data = array(
			   'cmp_id' => $id,
			   'material' => $_POST['material'][$i],
			   'qty'  	  => $_POST['qty'][$i],
			   'cost'     => $_POST['cost'][$i],
			   'total'    => $_POST['total'][$i],
			  );
			  $this->db->insert('cmp_mat_details', $data);	
			  //$trans_id2 = $this->db->insert_id();	 
			}}
		}
			
		
		return $id;
	}
	function get_complaint_records_project(){
    $query = $this->db->query("
        SELECT DISTINCT qm.quote_id, qm.project_name
        FROM amc_quotation_master qm
        JOIN amc_complaint_master cm 
            ON cm.project_name = qm.project_name
        ORDER BY qm.project_name ASC
    ");
    return $query->result();
}
	function get_complaint_records()
{
    // Safely handle 'from' and 'to' dates
    $from_post = $this->input->post('from');
    $to_post   = $this->input->post('to');

    $from = !empty($from_post) ? date('Y-m-d', strtotime($from_post)) : null;
    $to   = !empty($to_post) ? date('Y-m-d', strtotime($to_post)) : null;

    $product_code = $this->input->post('product_code'); 
    $prd_condition = "";
    $project      = $this->input->post('project_name'); 
    $pro_condition = "";
    $status       = $this->input->post('status'); 
    $status_condition = "";
    $technician   = $this->input->post('technician'); 
    $tech_condition = "";
    $rpt_type     = $this->input->post('rpt_type');

    if ($status != "")
        $status_condition = " AND a.cmp_status='$status' ";

    if ($product_code != "")
        $prd_condition = " AND b.material='$product_code' ";

    if ($project != "")
        $pro_condition = " AND a.project_name='$project' ";

    if ($technician != "")
        $tech_condition = " AND c.technician='$technician' ";

    // If dates are null, avoid breaking the SQL query
    $date_condition = "";
    if ($from && $to) {
        $date_condition = " AND a.cmp_date BETWEEN '$from' AND '$to' ";
    }

    if ($rpt_type == "Summary") {
        $sql = "SELECT * 
                FROM amc_complaint_master a, cmp_mat_details b, cmp_visit_details c 
                WHERE a.cmp_id = b.cmp_id 
                  AND a.cmp_id = c.cmp_id
                  $date_condition
                  $status_condition
                  $pro_condition
                  $prd_condition
                  $tech_condition 
                GROUP BY a.cmp_code 
                ORDER BY a.cmp_date DESC";
    } else {
        $sql = "SELECT a.*, b.*, c.*, u.user_name, p.product_code 
                FROM amc_complaint_master a
                JOIN cmp_mat_details b ON a.cmp_id = b.cmp_id
                JOIN cmp_visit_details c ON a.cmp_id = c.cmp_id
                LEFT JOIN users u ON c.technician = u.user_id
                LEFT JOIN product_master p ON p.product_id = b.material
                WHERE 1=1 
                  $date_condition
                  $status_condition
                  $pro_condition
                  $prd_condition
                  $tech_condition
                ORDER BY a.cmp_date DESC";
    }

    $query = $this->db->query($sql);

    return $query->result();
}

	function add_ppm_data()
	 {
		$pname = $this->input->post('project_name');

			 $data = array(
			 'ppm_code'			=> $this->input->post('ppm_code'),
			//  'project_name' 	=> $this->input->post('project_name'),
'quote_id' => $this->input->post('project_name'),
			 'ppm_date' 		=> date('Y-m-d',strtotime($this->input->post('ppm_date'))),
			 'remarks' 			=> $this->input->post('remarks'),
			 'no_of_sch' 		=> $this->input->post('no_of_sch'));
		 $this->db->insert('amc_ppm_master', $data);
		 $insert_id = $this->db->insert_id();

		 if($insert_id)
		 {
			if (!empty($_POST['ppm_num']) && is_array($_POST['ppm_num'])) {
    for ($i = 0; $i < count($_POST['ppm_num']); $i++) {
        $data = [
            'ppm_master_id'  => $insert_id,
            'ppm_num'        => $_POST['ppm_num'][$i] ?? null,
            'ppm_sch_date'   => $_POST['ppm_sch_date'][$i] ?? null,
            'ppm_finish_date'=> $_POST['ppm_finish_date'][$i] ?? null,
            'ppm_amt'        => $_POST['ppm_amt'][$i] ?? 0,
            'ppm_remarks'    => $_POST['ppm_remarks'][$i] ?? '',
            'ppm_status'     => $_POST['ppm_status'][$i] ?? 'Scheduled',
        ];
        $this->db->insert('amc_ppm_summary', $data);
    }
}

				 for ($i = 0; $i < count($_POST['ppmnum']); $i++)
				 {	
					$data = array(
						'ppm_master_id' => $insert_id,
						'sr_no' 		=> $_POST['sr_no'][$i],
						'ppmnum'		=> $_POST['ppmnum'][$i],
						'ppm_building'	=> $_POST['ppm_building'][$i],
						'ppm_flat'		=> $_POST['ppm_flat'][$i],
						'ppm_visit_date'=> $_POST['ppm_visit_date'][$i],
						'ppm_cost'		=> $_POST['ppm_cost'][$i],
						'ppm_acnum'		=> $_POST['ppm_acnum'][$i],
						'ppm_rate'		=> $_POST['ppm_rate'][$i],
						'ppm_tech'		=> $_POST['ppm_tech'][$i],
						'ppm_notes'		=> $_POST['ppm_notes'][$i],
						'ppmstatus'		=> $_POST['ppmstatus'][$i]
					);
				   $this->db->insert('amc_ppm_details', $data);	
				   $trans_id1 = $this->db->insert_id();	
  
				 }
			
			
		 }
		 return $insert_id;
	 }
// function get_ppm_list(){
// 		$query=$this->db->query("select * from amc_ppm_master order by ppm_id desc;"); 
// 		return $query->result();
//  }

function get_ppm_list()
{
    $this->db->select('
        p.*,
        q.project_name,
        q.quotation_code,
        c.customer_name
    ');

    $this->db->from('amc_ppm_master p');

    // ✅ FIXED JOIN
    $this->db->join('amc_quotation_master q', 'p.quote_id = q.quote_id', 'left');
    $this->db->join('customer_master c', 'q.customer_id = c.customer_id', 'left');

    $this->db->order_by('p.ppm_id', 'DESC');

    return $this->db->get()->result();
}
//  function get_ppm_with_id($id){
// 	$query=$this->db->query("select * from amc_ppm_master where ppm_id=$id;"); 
// 	return $query->result();
// }

function get_ppm_with_id($id)
{
    $this->db->select('
        p.*,
        q.project_name,
        q.quotation_code,
        c.customer_name
    ');

    $this->db->from('amc_ppm_master p');
    $this->db->join('amc_quotation_master q', 'p.quote_id = q.quote_id', 'left');
    $this->db->join('customer_master c', 'q.customer_id = c.customer_id', 'left');

    $this->db->where('p.ppm_id', $id);

    return $this->db->get()->result();
}
 function get_ppm_summ_with_id($id){
		$query=$this->db->query("select * from 	amc_ppm_summary where ppm_master_id=$id;"); 
		return $query->result();
 }
 function get_ppm_details_with_id($id){
		$query=$this->db->query("select * from amc_ppm_details where ppm_master_id=$id;"); 
		return $query->result();
 }
 function update_ppm_data()
{
    $id = $this->input->post('ppm_id');

    $data = array(
        // 'project_name' => $this->input->post('project_name'),
		'quote_id' => $this->input->post('quote_id'),
        'ppm_date'     => date('Y-m-d', strtotime($this->input->post('ppm_date'))),
        'remarks'      => $this->input->post('remarks'),
        'no_of_sch'    => $this->input->post('no_of_sch')
    );

    $this->db->where('ppm_id', $id);
    $res = $this->db->update('amc_ppm_master', $data);

    // SAFE DELETE (CI style)
    $this->db->where('ppm_master_id', $id)->delete('amc_ppm_summary');
    $this->db->where('ppm_master_id', $id)->delete('amc_ppm_details');

    if ($res) {

        // ======================
        // SUMMARY TABLE (SAFE)
        // ======================
        $ppm_num = $this->input->post('ppm_num');

        if (!empty($ppm_num) && is_array($ppm_num)) {

            for ($i = 0; $i < count($ppm_num); $i++) {

                if (!empty($ppm_num[$i])) {

                    $data = array(
                        'ppm_master_id'  => $id,
                        'ppm_num'        => $ppm_num[$i],
                        'ppm_sch_date'   => $_POST['ppm_sch_date'][$i] ?? null,
                        'ppm_finish_date'=> $_POST['ppm_finish_date'][$i] ?? null,
                        'ppm_amt'        => $_POST['ppm_amt'][$i] ?? 0,
                        'ppm_remarks'    => $_POST['ppm_remarks'][$i] ?? '',
                        'ppm_status'     => $_POST['ppm_status'][$i] ?? 'Scheduled',
						'completed_date' => (!empty($_POST['ppm_status'][$i]) && $_POST['ppm_status'][$i] == 'Finished')
    ? date('Y-m-d')
    : null,
                    );

                    $this->db->insert('amc_ppm_summary', $data);
                }
            }
        }

        // ======================
        // DETAILS TABLE (SAFE)
        // ======================
        $ppmnum = $this->input->post('ppmnum');

        if (!empty($ppmnum) && is_array($ppmnum)) {

            for ($i = 0; $i < count($ppmnum); $i++) {

                if (!empty($ppmnum[$i])) {

                    $data = array(
                        'ppm_master_id' => $id,
                        'sr_no'         => $_POST['sr_no'][$i] ?? '',
                        'ppmnum'        => $ppmnum[$i],
                        'ppm_building'  => $_POST['ppm_building'][$i] ?? '',
                        'ppm_flat'      => $_POST['ppm_flat'][$i] ?? '',
                        'ppm_visit_date'=> $_POST['ppm_visit_date'][$i] ?? '',
                        'ppm_cost'      => $_POST['ppm_cost'][$i] ?? 0,
                        'ppm_acnum'     => $_POST['ppm_acnum'][$i] ?? 0,
                        'ppm_rate'      => $_POST['ppm_rate'][$i] ?? 0,
                        'ppm_tech'      => $_POST['ppm_tech'][$i] ?? '',
                        'ppm_notes'     => $_POST['ppm_notes'][$i] ?? '',
                        'ppmstatus'     => $_POST['ppmstatus'][$i] ?? 'Scheduled'
						
                    );

                    $this->db->insert('amc_ppm_details', $data);
                }
            }
        }
    }

    return $id;
}
	 function delete_ppm($id)
	 {
		$query1=$this->db->query("delete from amc_ppm_summary where ppm_master_id='$id'");
		$query2=$this->db->query("delete from amc_ppm_details where ppm_master_id='$id'");
		$query=$this->db->query("delete from amc_ppm_master where ppm_id='$id'");
		return 1;
	 }
	 function delete_cmp($id)
	 {
		$query=$this->db->query("delete from cmp_mat_details where cmp_id='$id'");
		$query1=$this->db->query("delete from cmp_visit_details where cmp_id='$id'");
		$query2=$this->db->query("delete from amc_complaint_master where cmp_id='$id'");
		return 1;
	 }
	 function get_amc_company($id) {
		$query = $this->db->query("select * from amc_company where company_id=$id");
		return $query->result();
	}
	function get_amc_payment($id){
		$query = $this->db->query("select * from amc_payment_terms where inv_master_id=$id");
		return $query->result();
	}

	/////////////////////// Direct Qout Start////////////////////////////////
	function add_quot_direct()
{ 
    $post_data = $this->input->post();
    $result = $this->add_direct_enquiry_from_quot($post_data);

    $enq_id = $result['enquiry_id'];

    if ($enq_id)
    {
        $code = $this->input->post('qcode');

        // ================= MASTER DATA =================
        $data = array(
            'quotation_code' => $code,
            'quotation_date' => date('Y-m-d', strtotime($this->input->post('qdate'))),
            'revision_date'  => date('Y-m-d', strtotime($this->input->post('qdate'))),

            'enq_master_id'  => $enq_id,
            'customer_id'    => $result['customer_id'],

            // Financials
            'sub_total'      => $this->input->post('sub_total'),
            'vat_amt'        => $this->input->post('vat_amt'),
            'vat_percent'    => $this->input->post('vat_percent'),
            'amc_discount'   => $this->input->post('amc_discount'),
            'discount_percent'=> $this->input->post('discount'),
            'discount'       => $this->input->post('discount_amt'),
            'grand_total'    => $this->input->post('grand_total'),

            // Currency
            'currency_id'    => $this->input->post('cid'),
            'currency_rate'  => $this->input->post('crate'),

            // Terms
            'payment_term'   => $this->input->post('term1'),
            'quot_print_type'=> $this->input->post('quot_print_type'),
            'scope_work'     => $this->input->post('scope_work'),
            'exclusion_terms'=> $this->input->post('exclusion_terms'),
            'ppm_details'    => $this->input->post('ppm_details'),

            // Project
            'project_name'   => $this->input->post('project_name'),
            'project_location'=> $this->input->post('project_location'),

            // Billing
            'billing_addr'   => $this->input->post('billing_addr1'),
            'billing_city'   => $this->input->post('billing_city'),
            'billing_state'  => $this->input->post('billing_state'),
            'billing_pincode'=> $this->input->post('billing_po'),
            'billing_country'=> $this->input->post('billing_country'),

            // Contact
            'cp_name'   => $this->input->post('cp_name'),
            'cp_mobile' => $this->input->post('cp_mobile'),
            'cp_email'  => $this->input->post('cp_email'),

            // Others
            'branch_id'   => $this->input->post('branch'),
            'subject'     => $this->input->post('subject'),
            'sales_person'=> $this->input->post('user_id'),

            // ✅ CONTRACT INFO (IMPORTANT)
            'contract_type'  => $this->input->post('contract_type'),
            'no_of_years'    => $this->input->post('no_of_years'),
            'no_of_quarters' => $this->input->post('no_of_quarters'),

            'created_by'   => $this->session->userdata('user_id'),
            'created_date' => date('Y-m-d H:i:s')
        );

        $this->db->insert('amc_quotation_master', $data);
        $insert_id = $this->db->insert_id();

        // ================= UPDATE ENQUIRY =================
        $this->db->query("
            UPDATE amc_enquiry_master 
            SET order_status = 1 
            WHERE amc_enq_id = $enq_id
        ");

        // ================= DETAIL SAVE =================
        if ($insert_id)
        {
            $type = $this->input->post('contract_type');

            $count = 0;
            if ($type == 'Yearly') {
                $count = $this->input->post('no_of_years');
            } elseif ($type == 'Quarterly') {
                $count = $this->input->post('no_of_quarters');
            }

            $prod_ids = $this->input->post('prod_id');
            $qtys     = $this->input->post('qty');
            $prices   = $this->input->post('price');
            $totals   = $this->input->post('final_total');

           for ($i = 0; $i < count($_POST['prod_id']); $i++)
{
    $prod  = $_POST['prod_id'][$i] ?? null;
    $price = $_POST['price'][$i] ?? 0;
    $qty   = $_POST['qty'][$i] ?? 0;

    if (!$prod) continue;

    $final_total = $_POST['final_total'][$i] ?? ($price * $qty);

    $data1 = array(
        'quote_master_id' => $insert_id,
        'product_id'      => $prod,
        'quantity'        => $qty,
        'price'           => $price,
        'total'           => $final_total
    );

    $this->db->insert('amc_quotation_transaction', $data1);
}

/* ================= SLA DETAILS ================= */
$sla_enabled = $this->input->post('sla_enabled');

if (!empty($sla_enabled) && $insert_id) {

    $service_item      = $this->input->post('service_item');
    $availability      = $this->input->post('service_availability_period');
    $response_time     = $this->input->post('response_time');
    $restoration_time  = $this->input->post('restoration_time');
    $resolution_time   = $this->input->post('resolution_time');

    if (!empty($service_item) && is_array($service_item)) {

        foreach ($service_item as $i => $item) {

            if (!empty($item)) {

                $sla = array(
                    'quote_id' => $insert_id,
                    'service_item'               => $item,
                    'service_availability_period'=> $availability[$i] ?? '',
                    'response_time'              => $response_time[$i] ?? '',
                    'restoration_time'           => $restoration_time[$i] ?? '',
                    'resolution_time'            => $resolution_time[$i] ?? ''
                );

                $this->db->insert('amc_invoice_sla', $sla);
            }
        }
    }
}

/* ================= ANNEXURE DETAILS ================= */
$annexure_enabled = $this->input->post('annexure_enabled');

if (!empty($annexure_enabled) && $insert_id) {

    $sl_no    = $this->input->post('sl_no');
    $type     = $this->input->post('type');
    $location = $this->input->post('location');
    $qty      = $this->input->post('annex_qty');

    if (!empty($type) && is_array($type)) {

        foreach ($type as $i => $val) {

            if (!empty($val)) {

                $data = array(
                    'quote_id' => $insert_id,
                    'sl_no'         => $sl_no[$i] ?? ($i+1),
                    'type'          => $val,
                    'location'      => $location[$i] ?? '',
                    'quantity'      => $qty[$i] ?? 0
                );

                $this->db->insert('amc_invoice_annexure', $data);
            }
        }
    }
}

            // ================= STATUS LOG =================
            $data3 = array(
                'enq_id'      => $enq_id,
                'status'      => "Quotation generated $code",
                'status_date' => date('Y-m-d H:i:s'),
            );

            $this->db->insert('sales_order_status', $data3);

            // ================= LOG =================
            $this->load->model('Users_model');
            $data['user_records'] = $this->Users_model->get_active_user_list();

            $user_se_id = $this->session->userdata('user_id');
            $page_name  = explode('index.php/', $_SERVER['PHP_SELF']);

            $ci = get_instance();
            $ci->load->helper('log');

            add_log_entry(
                $user_se_id,
                1,
                $page_name[1],
                'amc_quotation_master',
                'quote_id',
                $insert_id
            );

            // ================= NOTIFICATION =================
            foreach ($data['user_records'] as $r)
            {
                add_notification(
                    $insert_id,
                    $r->user_id,
                    "AMC Quotation generated $code",
                    "amc/edit_quotation/$insert_id/1/0"
                );
            }
        }
    }

    return $insert_id;
}

	function add_direct_enquiry_from_quot($data){
		
		$data['title']='Add New Enquiry(AMC)';
		$prifix='ADL/ENQ/';
		$num = $this->Setup_model->get_next_code($prifix,'amc_enq_code','amc_enquiry_master',9)+1;
		$digit=sprintf("%1$04d",$num);
		$enquiry_code =$prifix.$digit;

        $cust_id = $this->input->post('customer_id');
        if (!empty($this->input->post('inv_no')) ) 
        {
            $inv_no = $this->input->post('inv_no');
        }  
        else{
            $inv_no = '';
        }
		if($cust_id=='new')
		{
			$prifix1='CM';
			$num1 = $this->Setup_model->get_next_code($prifix1,'cust_code','customer_master',3)+1;
			$digit1=sprintf("%1$04d",$num1);
			$Code1 =$prifix1.$digit1;

			$data1 = array(
			'cust_code' => $Code1,
			'cust_name' =>$this->input->post('customer_name'),
			'contact_no'  => $this->input->post('cust_mobile'),
			'email_id' => $this->input->post('cust_email'),
			'created_by'  => $this->session->userdata('user_id'),
			'created_date' =>  date('Y-m-d H:i:s'),
			);
			$this->db->insert('customer_master', $data1);
			//echo $this->db->last_query();exit;
			$customer_id = $this->db->insert_id();
		}
		else
		{
			$customer_id = $cust_id;
		}
		$result['customer_id'] = $customer_id;
		$data = array(
            'amc_enq_code'  => $enquiry_code,
            'enq_date'      => date('Y-m-d',strtotime($this->input->post('qdate'))),
            'revision_date' => date('Y-m-d',strtotime($this->input->post('qdate'))),
            'cust_id'       => $customer_id,
            'enq_type'      => $this->input->post('enquiry_type'),
            //'client_ref'    => $this->input->post('client_ref'),
            //'remark'        => $this->input->post('remark'),
            // 'amc_start_date'=> date('Y-m-d',strtotime($this->input->post('amc_start_date'))),
            // 'amc_end_date'  => date('Y-m-d',strtotime($this->input->post('amc_end_date')))  ,
            'created_by'    => $this->session->userdata('user_id'),
            'invoice_no'    => $inv_no,
            'sales_person'  => $this->session->userdata('user_id'),
            'created_date'  => date('Y-m-d H:i:s'),
			'project_name'  => $this->input->post('project_name')
		);
        
		$this->db->insert('amc_enquiry_master', $data);
		$insert_id = $this->db->insert_id();
        $result['enquiry_id'] = $insert_id;

		$approved=0;
		if($insert_id)
		{     
            $approved=1;
            for ($i = 0; $i < count($_POST['prod_id']); $i++)
            {	    
                try{
                
                $data = array(
                'enquiry_id' => $insert_id,
                'product_id' => $_POST['prod_id'][$i],
                // 'desc' => $_POST['desc'][$i],
				// 'brand' => $_POST['brand'][$i],
				// 'model' => $_POST['model'][$i],
				//'capacity' => $_POST['capacity'][$i],
                'quantity'  => $_POST['qty'][$i],
                'approved'  => $approved,
                );
                $this->db->insert('amc_enquiry_transaction', $data);
               
                }
                catch(Exception $e){
                    return 'duplicate';
                }
            }
           
			$this->load->model('Users_model');
			$data['user_records']=$this->Users_model->get_active_user_list();
      
			$user_se_id=$this->session->userdata('user_id');
			$page_name=explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg=add_log_entry($user_se_id,1,$page_name[1],'amc_enquiry_master','amc_enq_id',$insert_id);
			// /* notification */ 
			// foreach($data['user_records'] as $r)
   			// {
			// 	$notice=add_notification($insert_id,$r->user_id,"New $etype Enquiry Received $enquiry_code","sales/edit_order/$insert_id/0");
			// }
			 /* end notification */
			return $result;
		 }
		}

		public function update_direct_quotation_data(){

		
			$enq_id = $this->input->post('enq_id');
			$enq_type = $this->input->post('enq_type');
			$code = $this->input->post('qcode');
			$cp_select=$this->input->post('cp_select');
			$qid = $this->input->post('qid');
			//echo '<pre>';print_r($this->input->post());exit;
			$data = array(
				'branch_id' => $this->input->post('branch'),
			'quotation_code' => $code,
			'quotation_date' => date('Y-m-d',strtotime($this->input->post('qdate'))),
			'revision_date'=> date('Y-m-d',strtotime($this->input->post('qdate'))),
			'enq_master_id' => $enq_id,
			'customer_id' => $this->input->post('customer_id'),
			'sub_total' => $this->input->post('sub_total'),
			'vat_amt' => $this->input->post('vat_amt'),
			'vat_percent' => $this->input->post('vat_percent'),
			'discount_percent' =>$this->input->post('discount'),
			'discount' => $this->input->post('discount_amt'),
			'amc_discount' => $this->input->post('amc_discount'),
			'currency_id' => $this->input->post('cid'),
			'currency_rate' => $this->input->post('crate'),		
			'grand_total' => $this->input->post('grand_total'),
			'payment_term' => $this->input->post('term1'),
			'exclusion_terms' => $this->input->post('exclusion_terms'),
			'scope_work' => $this->input->post('scope_work'),
			'ppm_details' => $this->input->post('ppm_details'),
			'comp_id'  => $this->input->post('cmp_id)'),
			'quot_print_type' => $this->input->post('quot_print_type'),
			// 'amc_start_date'=> date('Y-m-d',strtotime($this->input->post('amc_start_date'))),
			// 'amc_end_date'  => date('Y-m-d',strtotime($this->input->post('amc_end_date')))  ,
			'validity' => $this->input->post('validity'),
			'scope_work' =>$this->input->post('scope_work'),
			'billing_addr' => $this->input->post('billing_addr1'),
			'billing_city' => $this->input->post('billing_city'),
			'billing_state' => $this->input->post('billing_state'),
			'billing_pincode' => $this->input->post('billing_po'),
			'billing_country' => $this->input->post('billing_country'),
			'service_scheme' => $this->input->post('service_scheme'),	
			'bank_id' =>  $this->input->post('bank'),
			
			'cp_name' => $this->input->post("cp_name$cp_select"),
			'cp_mobile' => $this->input->post("cp_mobile$cp_select"),
			'cp_email' => $this->input->post("cp_email$cp_select"),
			'sales_person'=>$this->input->post('user_id'),	
			'created_by' => $this->session->userdata('user_id'),
			'created_date' => date('Y-m-d H:i:s')
			);
			

			$this->db->where('quote_id',$qid);
			$insert_id = $this->db->update('amc_quotation_master', $data);
			
			$this->db->query("delete from amc_quotation_transaction where quote_master_id=$qid"); 
			$this->db->query("delete from amc_workscope_quot where quote_master_id=$qid"); 
			
			
			if($insert_id)
			{	
				
					for ($i = 0; $i < count($_POST['product_id']); $i++)
					{		
					$data1 = array(
						'quote_master_id' => $qid ,
						'product_id' => $_POST['product_id'][$i],                   
						// 'brand' => $_POST['brand'][$i],
						// 'model' => $_POST['model'][$i],
						'quantity'   => $_POST['qty'][$i],	
						'price'      => $_POST['price'][$i],
						'total'  	 => $_POST['total'][$i],
					);
					
					$this->db->insert('amc_quotation_transaction', $data1);	
					
					}

					
					
				
			}
			return $insert_id;
		}
		function get_quote_doc($id){
			$query = $this->db->query("select doc_path from amc_documents where doc_master_id=$id");
			return $query->result();
		}

public function get_ppm_records($from = null, $to = null)
{
    $this->db->select('
        ppm_master.ppm_id,
        ppm_master.ppm_code,
        quotation.project_name,
		quotation.project_location,
		quotation.subject,
		
        customer.customer_name,
        ppm_master.ppm_date,
        ppm_master.no_of_sch,
        ppm_master.remarks
    ');

    $this->db->from('amc_ppm_master ppm_master');

    // join quotation
    $this->db->join(
        'amc_quotation_master quotation',
        'quotation.quote_id = ppm_master.quote_id',
        'left'
    );

    // join customer
    $this->db->join(
        'customer_master customer',
        'customer.customer_id = quotation.customer_id',
        'left'
    );

    // filters
    if (!empty($project)) {
        $this->db->where('ppm_master.quote_id', $project);
    }

    if (!empty($ppm_code)) {
        $this->db->like('ppm_master.ppm_code', $ppm_code);
    }

    if (!empty($from) && !empty($to)) {
        $this->db->where('ppm_master.ppm_date >=', date('Y-m-d', strtotime($from)));
        $this->db->where('ppm_master.ppm_date <=', date('Y-m-d', strtotime($to)));
    }

    return $this->db->order_by('ppm_master.ppm_id', 'DESC')->get()->result();
}
public function get_ppm_project_list()
{
    $this->db->select('quote_id, project_name');
    $this->db->from('amc_quotation_master');
    $this->db->group_by('quote_id');
    $this->db->order_by('project_name', 'ASC');

    return $this->db->get()->result();
}
		
public function get_ppm_details($ppm_id)
{
    return $this->db
        ->where('ppm_master_id', $ppm_id)
        ->get('amc_ppm_details')
        ->result();
}

    public function get_amc_alert($days = 7)
{
    $today  = date('Y-m-d');
    $future = date('Y-m-d', strtotime("+$days days"));

    $this->db->select('
        i.invoice_id,
        i.invoice_code,
        i.amc_end_date,
        q.project_name,
        c.customer_name
    ');

    $this->db->from('amc_invoice_master i');

    // AMC → quotation
    $this->db->join(
        'amc_quotation_master q',
        'q.quote_id = i.quote_id',
        'left'
    );

    // quotation → customer
    $this->db->join(
        'customer_master c',
        'c.customer_id = q.customer_id',
        'left'
    );

    // filters
    $this->db->where('i.amc_end_date IS NOT NULL', null, false);
    $this->db->where('i.amc_end_date !=', '0000-00-00');

    $this->db->where('i.amc_end_date >=', $today);
    $this->db->where('i.amc_end_date <=', $future);

    $this->db->order_by('i.amc_end_date', 'ASC');

    return $this->db->get()->result();
}

public function get_ppm_scheduled_alerts($days = 7)
{
    $today  = date('Y-m-d');
    $future = date('Y-m-d', strtotime("+$days days"));

    $query = $this->db->query("
        SELECT 
            pm.ppm_id,
			pm.ppm_code,
            pm.quote_id,
            ps.ppm_sch_date,
            ps.ppm_status,
            ps.ppm_master_id,
            qm.project_name
        FROM amc_ppm_master pm
        JOIN amc_ppm_summary ps 
            ON ps.ppm_master_id = pm.ppm_id
        LEFT JOIN amc_quotation_master qm 
            ON qm.quote_id = pm.quote_id
        WHERE ps.ppm_status != 'Finished'
          AND ps.ppm_sch_date != '0000-00-00'
          AND ps.ppm_sch_date IS NOT NULL
        ORDER BY ps.ppm_sch_date ASC
    ");

    $result = $query->result();

    $grouped = [
        'overdue'   => [],
        'due_soon'  => [],
        'upcoming'  => []
    ];

    foreach ($result as $row) {

        if ($row->ppm_sch_date < date('Y-m-d')) {
            $grouped['overdue'][] = $row;
        }
        elseif ($row->ppm_sch_date <= date('Y-m-d', strtotime("+7 days"))) {
            $grouped['due_soon'][] = $row;
        }
        else {
            $grouped['upcoming'][] = $row;
        }
    }

    return $grouped;
}

public function get_ppm_invoice_data($ppm_summary_id)
{
    $query = $this->db->query("
        SELECT 
            ps.id AS ppm_summary_id,
            ps.*,
            pm.ppm_id,
            pm.quote_id,
            qm.project_name,
            qm.customer_id
        FROM amc_ppm_summary ps
        JOIN amc_ppm_master pm 
            ON pm.ppm_id = ps.ppm_master_id
        LEFT JOIN amc_quotation_master qm
            ON qm.quote_id = pm.quote_id
        WHERE ps.id = '$ppm_summary_id'
    ");

    return $query->row();
}

public function get_ppm_invoice_by_id($invoice_id)
{
    $query = $this->db->query("
        SELECT *
        FROM ppm_invoice_master
        WHERE invoice_id = '$invoice_id'
    ");

    return $query->row();
}

public function get_ppm_summary_by_invoice($invoice_id)
{
    $query = $this->db->query("
        SELECT 
            ps.*,
            pm.ppm_code
        FROM ppm_invoice_master pi
        JOIN amc_ppm_summary ps 
            ON ps.id = pi.ppm_summary_id
        JOIN amc_ppm_master pm 
            ON pm.ppm_id = pi.ppm_id
        WHERE pi.invoice_id = '$invoice_id'
        LIMIT 1
    ");

    return $query->row();   // ✅ IMPORTANT
}

public function get_invoice_sla_by_id($invoice_id)
{
    return $this->db
        ->where('inv_master_id', $invoice_id)
        ->get('amc_invoice_sla')
        ->result();
}

public function get_invoice_annexure_by_id($id)
{
    return $this->db->where('inv_master_id', $id)
                    ->get('amc_invoice_annexure')
                    ->result();
}
public function get_amc_quotation_info($quote_id)
{
    return $this->db
        ->where('quote_id', $quote_id)
        ->get('amc_quotation_master')
        ->row();
}

public function get_quotation_sla_by_id($quote_id)
{
    return $this->db
        ->where('quote_id', $quote_id)
        ->get('amc_invoice_sla')
        ->result();
}

public function get_quotation_annexure_by_id($quote_id)
{
    return $this->db
        ->where('quote_id', $quote_id)
        ->get('amc_invoice_annexure')
        ->result();
}
}

?>