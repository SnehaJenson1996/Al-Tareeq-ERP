<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Purchase_Model extends CI_Model
{

	////////////////// RFQ start ///////////////
	function add_direct_rfq_records()
	{

		$this->load->model('Setup_model');

		$prifix = 'AVE/RFQ/';
		$num = $this->Setup_model->get_next_code($prifix, 'rfq_code', 'purchase_rfq', 12) + 1;
		$digit = sprintf("%1$05d", $num);
		$Code = $prifix . date('y') . '/' . $digit;

		$data = array(
			'rfq_code' 		=> $this->input->post('rfq_code'),
			'rev_version'  	=> 1,
			'rfq_date'  	 	=> date('Y-m-d', strtotime($this->input->post('rfq_date'))),
			'branch_id'    	=> $this->input->post('branch_id'),
			'supplier_id'  	=> $this->input->post('supplier_id'),
			'rfq_type'  	 	=> 'direct',
			'status'		 	=>  '0',
			'sales_person' 	=> $this->input->post('user_id'),
			'subject'      	=> $this->input->post('subject'),
			'project'		 	=>	$this->input->post('project'),
			'ref' 		 	=> $this->input->post('ref'),
			'remark'	   		=> $this->input->post('remarks'),
			'created_by'   	=> $this->session->userdata('user_id'),
		);
		$this->db->insert('purchase_rfq', $data);
		$insert_id = $this->db->insert_id();

		for ($i = 0; $i < count($_POST['description']); $i++) {

			$data = array(
				'rfq_master_id' => $insert_id,
				'rfq_version' => 1,
				'srno'		  => $i + 1,
				'product_id'  => $_POST['item'][$i],
				'prod_desc'   => $_POST['description'][$i],
				'brand'       => $_POST['item_brand'][$i] ?? '',
				'unit' 		  => $_POST['unit'][$i],
				'quantity'    => $_POST['quantity'][$i],

			);
			$this->db->insert('purchase_RFQ_transaction', $data);
		}
		// if($insert_id)
		// {
		// 	$user_se_id=$this->session->userdata('user_id');
		// 	$page_name=explode('index.php/', $_SERVER['PHP_SELF']);
		// 	$ci = get_instance();
		// 	$ci->load->helper('log');
		// 	$log_msg=add_log_entry($user_se_id,1,$page_name[1],'purchase_rfq','rfq_id',$insert_id);

		// }
		return $insert_id;
	}

	function update_rfq_records()
	{

		$rfq_id = $this->input->post('rfq_id');

		$data = array(
			'rfq_date'     => date('Y-m-d', strtotime($this->input->post('rfq_date'))),
			'branch_id'    => $this->input->post('branch_id'),
			'supplier_id'  => $this->input->post('supplier_id'),
			'sales_person' => $this->input->post('user_id'),
			'subject'      => $this->input->post('subject'),
			'project'	   => $this->input->post('project'),
			'ref' 		   => $this->input->post('ref'),
			'remark'	   => $this->input->post('remarks'),
			'created_by'   => $this->session->userdata('user_id'),
		);

		// Perform update
		$this->db->where('rfq_id', $rfq_id);
		$this->db->update('purchase_rfq', $data);

		$this->db->where('rfq_master_id', $rfq_id);
		$this->db->delete('purchase_RFQ_transaction');

		for ($i = 0; $i < count($_POST['description']); $i++) {

			$data = array(
				'rfq_master_id' => $rfq_id,
				'rfq_version' => 1,
				'product_id'  => $_POST['item'][$i],
				'prod_desc'   => $_POST['description'][$i],
				'unit' 		  => $_POST['unit'][$i],
				'quantity'    => $_POST['quantity'][$i],

			);
			$this->db->insert('purchase_RFQ_transaction', $data);
		}

		return $insert_id;
	}

	function delete_rfq($rfq_id)
	{
		$this->db->query("delete from purchase_RFQ_transaction where rfq_master_id='$rfq_id'");
		$this->db->query("delete from purchase_rfq where rfq_id='$rfq_id'");

		// $user_se_id=$this->session->userdata('user_id');
		// $page_name=explode('index.php/', $_SERVER['PHP_SELF']);
		// $ci = get_instance();
		// $ci->load->helper('log');
		// $log_msg=add_log_entry($user_se_id,3,$page_name[1],'grn_master','grn_id',$grn_id);
		// return 1;
	}

	function get_RFQ_list()
	{
		$query = $this->db->query("SELECT r.*, em.user_name AS rfq_created_by, sp.supplier_name FROM purchase_rfq r JOIN users em ON r.created_by = em.user_id  JOIN supplier_master sp ON r.supplier_id = sp.supplier_id WHERE r.status = 0    ORDER BY rfq_date DESC;");
		//    echo $this->db->last_query();exit;
		return $query->result();
	}

	function get_quotation_list()
	{
		$query = $this->db->query("
        SELECT p.*, s.supplier_name
        FROM purchase_quotation_master p
        LEFT JOIN supplier_master s 
            ON p.supplier_id = s.supplier_id
        ORDER BY p.quotation_id DESC
    ");

		return $query->result();
	}

	function get_purchase_rfq_tr($rfq_id)
	{
		$this->db->select("
			r.*,
			im.product_id,
			im.product_code,
			im.product_name,
			im.retail_price,
			im.description AS item_description,
			im.unit_id,
			um.unit_name
		");

		$this->db->from('purchase_RFQ_transaction r');
		$this->db->join('item_master im', 'im.product_id = r.product_id', 'left');
		$this->db->join('unit_master um', 'um.unit_id = im.unit_id', 'left');

		$this->db->where('r.rfq_master_id', $rfq_id);
		$this->db->order_by('CAST(r.srno AS UNSIGNED)', 'ASC');

		return $this->db->get()->result();
	}

	function get_purchase_rfq_by_id($id)
	{
		$this->db->select('r.*, 
			em.user_name AS rfq_created_by, 
			s.supplier_name, s.supplier_code, 
			s.billing_address, s.billing_city, s.billing_state, 
			s.billing_po_box, s.billing_country,
			s.supplier_email, s.contact_number,
			bm.branch_id, bm.branch_name,
			two.enquiry_id, 
			three.user_name AS sales_person_name');
		$this->db->from('purchase_rfq r');
		$this->db->join('users em', 'r.created_by = em.user_id');
		$this->db->join('supplier_master s', 'r.supplier_id = s.supplier_id');
		$this->db->join('branch_master bm', 'r.branch_id = bm.branch_id', 'left');
		$this->db->join('enquiry_master two', 'r.indent_id = two.enquiry_id', 'left');
		$this->db->join('users three', 'r.sales_person = three.user_id', 'left');
		$this->db->where('r.status', 0);
		$this->db->where('r.rfq_id', $id);
		$this->db->order_by('r.rfq_date', 'DESC');
		$query = $this->db->get();

		// $query=$this->db->query("select one.*, three.user_name from (select r.*, em.user_name as rfq_created_by,s.supplier_name,s.supplier_code, s.billing_address, s.billing_city, s.billing_state, s.billing_po_box, s.billing_country,s.supplier_email, s.contact_number from purchase_rfq r, users em, supplier_master s where r.created_by=em.user_id and r.supplier_id=s.supplier_id and r.status=0 and rfq_id=$id order by r.rfq_date desc)as one left join(select * from enquiry_master)as two on(one.indent_id=two.enquiry_id) left join(select * from users)as three on(one.sales_person=three.user_id) ");

		return $query->result();
	}

	function add_purchase_quotation()
	{

		$prifix = 'AVE/SQT/';
		$this->load->model('Setup_model');
		$num 	= $this->Setup_model->get_next_code($prifix, 'quotation_code', 'purchase_quotation_master', 12) + 1;
		$digit	= sprintf("%1$05d", $num);
		$code 	= $prifix . date("y") . '/' . $digit;
		$s_id 	= $this->input->post('supplier_id');


		/* =========================
		FILE UPLOAD (NEW)
		========================== */
		$doc_name = null;

		$upload_path = FCPATH . 'public/uploaded_documents/';

		if (!empty($_FILES['quote_doc']['name'])) {

			$ext = pathinfo($_FILES['quote_doc']['name'], PATHINFO_EXTENSION);
			$doc_name = time() . '_' . rand(1000, 9999) . '.' . $ext;

			if (!is_dir($upload_path)) {
				mkdir($upload_path, 0775, true);
			}

			if (is_writable($upload_path)) {

				if (!move_uploaded_file($_FILES['quote_doc']['tmp_name'], $upload_path . $doc_name)) {
					$doc_name = null; // prevent wrong DB entry
				}
			} else {
				log_message('error', 'Upload folder not writable: ' . $upload_path);
				$doc_name = null;
			}
		}

		$data 	= array(
			'quotation_date' 	 => date('Y-m-d', strtotime($this->input->post('quotation_date'))),
			'revision'			 => 0,
			'revision_date'		 => date('Y-m-d', strtotime($this->input->post('quotation_date'))),
			'rfq_master_id' 	 => $this->input->post('rfq_id') ?? 0,
			'quotation_code' 	 => $code,
			'branch_id' 		 => $this->input->post('branch_id'),
			'supplier_id' 		 => $s_id,
			'project'			 => $this->input->post('project'),
			'reference' 		 => $this->input->post('ref_no'),
			'subtotal' 			 => $this->input->post('sub_total'),
			'vat_amt' 			 => $this->input->post('vat_amount'),
			'vat_percent' 		 => $this->input->post('vat_per'),
			// 'discount_percent' =>$this->input->post('discount_per'),
			// 'discount' => $this->input->post('discount_amt'),
			// 'currency_id' => $this->input->post('cid'),
			// 'currency_rate' => $this->input->post('crate'),	 
			'grand_total' 		=> $this->input->post('grand_total'),
			'payment_term' 		=> $this->input->post('payment_terms'),
			'delivery_term' 	=> $this->input->post('delivery_terms'),
			'general_term' 		=> $this->input->post('general_terms'),
			'validity' 			=> $this->input->post('validity'),
			'quote_doc'        => $doc_name,
			'created_by' 		=> $this->session->userdata('user_id'),
			'created_date' 		=> date('Y-m-d H:i:s')
		);

		$this->db->insert('purchase_quotation_master', $data);
		$insert_id = $this->db->insert_id();



		if ($insert_id) {

			for ($i = 0; $i < count($_POST['item_id']); $i++) {
				$data = array(
					'qtn_master_id' 	=> $insert_id,
					'product_id' 		=> $_POST['item_id'][$i],
					'desc' 				=> $_POST['item_description'][$i],
					'brand'             => isset($_POST['item_brand'][$i]) ? $_POST['item_brand'][$i] : '',
					'unit_id' 			=> $_POST['item_unit'][$i],
					// 'packing_id' =>$_POST['item_packing'][$i],
					'quantity'  		=> $_POST['item_quantity'][$i],
					'price'  			=> $_POST['unit_price'][$i],
					'total'  			=> $_POST['total_price'][$i],
					'dis_per'  			=> $_POST['dis_per'][$i],
					'dis_amt'  			=> $_POST['dis_amt'][$i],
					//'dis_per2'  => $_POST['dis_per2'][$i],
					//'dis_amt2'  => $_POST['dis_amt2'][$i],
					'unit_price' 		=> $_POST['final_unit_price'][$i],
				);
				$this->db->insert('purchase_qtn_transaction', $data);
				if (!$this->db->affected_rows()) {
					echo "<pre>";
					print_r($this->db->error());
					exit;
				}
				$this->update_supplier_prices($s_id, $_POST['item_id'][$i], $_POST['item_unit'][$i], $_POST['final_unit_price'][$i]);
			}
		}
		return $insert_id;
	}

	function update_supplier_prices($sid, $pcode, $unit, $unit_price)
	{

		$this->db->where('supplier_id', $sid);
		$this->db->where('product_id', $pcode);
		$this->db->where('unit', $unit);
		$qry = $this->db->get('supplier_prices');

		if ($qry->num_rows() > 0) {
			$price_data = array(
				'price' => $unit_price,
			);
			$this->db->where('supplier_id', $sid);
			$this->db->where('product_id', $pcode);
			$this->db->where('unit', $unit);
			$this->db->update('supplier_prices', $price_data);
		} else {
			$price_data = array(
				'supplier_id' => $sid,
				'product_id' => $pcode,
				'unit' => $unit,
				'price' => $unit_price,
			);
			$this->db->insert('supplier_prices', $price_data);
		}
		//updating item master
		$price_data = array(
			'retail_price' => $unit_price,
		);
		$this->db->where('product_id', $pcode);
		$this->db->update('item_master', $price_data);
	}

	//   function get_quotation_list(){
	// 	$query=$this->db->query("select one.* from (select p.*,s.supplier_name from purchase_quotation_master p, supplier_master s where p.supplier_id=s.supplier_id )as one order by quotation_date desc, quotation_id desc; ");
	// 	return $query->result();
	//   }

	function get_pur_qtn_master_by_id($id)
	{
		//$query=$this->db->query("SELECT qtn.*, rfq.rfq_id, rfq.rfq_code, rfq.created_by AS rfq_created_by_id, u1.user_name AS sales_person,  u2.user_name AS rfq_created_by_name, sm.supplier_code, sm.supplier_name,sm.contact_number,sm.billing_address, sm.supplier_email, sm.contact_number FROM purchase_quotation_master qtn LEFT JOIN purchase_rfq rfq ON qtn.rfq_master_id = rfq.rfq_id LEFT JOIN supplier_master sm ON qtn.supplier_id = sm.supplier_id LEFT JOIN users u1 ON u1.user_id = qtn.created_by  LEFT JOIN users u2 ON u2.user_id = rfq.created_by  WHERE quotation_id = '$id';;");
		$query = $this->db->query("
			SELECT 
				qtn.*, 
				rfq.rfq_id, 
				rfq.rfq_code, 
				rfq.created_by AS rfq_created_by_id, 
				u1.user_name AS sales_person,  
				u2.user_name AS rfq_created_by_name, 
				sm.supplier_code, 
				sm.supplier_name,
				sm.contact_number,
				sm.billing_address, 
				sm.supplier_email, 
				sm.contact_number,
				bm.branch_name,
				c.currency_abbr
			FROM purchase_quotation_master qtn
			LEFT JOIN purchase_rfq rfq ON qtn.rfq_master_id = rfq.rfq_id
			LEFT JOIN supplier_master sm ON qtn.supplier_id = sm.supplier_id
			LEFT JOIN users u1 ON u1.user_id = qtn.created_by
			LEFT JOIN users u2 ON u2.user_id = rfq.created_by
			LEFT JOIN branch_master bm ON qtn.branch_id = bm.branch_id
			LEFT JOIN currency_master c ON sm.currency_id = c.currency_id								

			WHERE qtn.quotation_id = '$id'
		");

		return $query->result();
	}

	function get_pur_qtn_tr_by_id($id)
	{
		$query = $this->db->query("
			SELECT
				pqt.*,
				p.*,
				u.unit_name
			FROM purchase_qtn_transaction pqt
			LEFT JOIN item_master p
				ON pqt.product_id = p.product_id
			LEFT JOIN unit_master u
				ON pqt.unit_id = u.unit_id
			WHERE pqt.qtn_master_id = '$id'
		");

		return $query->result();
	}

	function update_purchase_quotation()
	{
		$quotation_id = $this->input->post("quotation_id");
		$doc_name = $this->input->post('existing_quote_doc'); // hidden field
		if (!empty($_FILES['quote_doc']['name'])) {

			$upload_path = FCPATH . 'public/uploaded_documents/';

			$ext = pathinfo($_FILES['quote_doc']['name'], PATHINFO_EXTENSION);
			$new_file = time() . '_' . rand(1000, 9999) . '.' . $ext;

			if (move_uploaded_file($_FILES['quote_doc']['tmp_name'], $upload_path . $new_file)) {

				// delete old file (optional but recommended)
				if (!empty($doc_name) && file_exists($upload_path . $doc_name)) {
					unlink($upload_path . $doc_name);
				}

				$doc_name = $new_file;
			}
		}
		$data = array(
			'quotation_date' => date('Y-m-d', strtotime($this->input->post('quotation_date'))),
			'project' 		 => $this->input->post('project'),
			'reference' 	 => $this->input->post('ref_no'),
			'subtotal' 		 => $this->input->post('sub_total'),
			'vat_amt' 		 => $this->input->post('vat_amount'),
			'vat_percent' 	 => $this->input->post('vat_per'),

			'grand_total'    => $this->input->post('grand_total'),
			'payment_term' 	 => $this->input->post('payment_terms'),
			'delivery_term'  => $this->input->post('delivery_terms'),
			'general_term'   => $this->input->post('general_terms'),
			'validity' 		 => $this->input->post('validity'),
			'quote_doc' => $doc_name,
			// 'revision' => 0,
			// 'revision_date'=> date('Y-m-d',strtotime($this->input->post('quotation_date'))),
			// 'discount_percent' =>$this->input->post('discount_per'),
			// 'discount' => $this->input->post('discount_amt'),
			// 'currency_id' => $this->input->post('cid'),
			// 'currency_rate' => $this->input->post('crate'),

		);
		$this->db->where('quotation_id', $quotation_id);
		$res = $this->db->update('purchase_quotation_master', $data);

		$this->db->select('*');
		$this->db->where('qtn_master_id', $quotation_id);
		$res = $this->db->delete('purchase_qtn_transaction');


		for ($i = 0; $i < count($_POST['item_id']); $i++) {
			$data = array(
				'qtn_master_id' 	=> $quotation_id,
				'product_id' 		=> $_POST['item_id'][$i],
				'desc' 			    => $_POST['item_description'][$i],
				'brand'             => $_POST['item_brand'][$i] ?? '',
				'unit_id'           => $_POST['item_unit'][$i],
				'quantity'  		=> $_POST['item_quantity'][$i],
				'price' 	 		=> $_POST['unit_price'][$i],
				'total'  			=> $_POST['total_price'][$i],
				'dis_per'  		    => $_POST['dis_per'][$i],
				'dis_amt'  		    => $_POST['dis_amt'][$i],
				//'dis_per2'  		=> $_POST['dis_per2'][$i],
				//'dis_amt2'  		=> $_POST['dis_amt2'][$i],
				'unit_price' 		=> $_POST['final_unit_price'][$i]
			);
			$this->db->insert('purchase_qtn_transaction', $data);
		}
	}

	public function create_revision_purchase_quotation()
	{
		$original_id = $this->input->post('quotation_id');

		$this->db->where('quotation_id', $original_id);
		$original = $this->db->get('purchase_quotation_master')->row_array();
		if (!$original) {
			return false;
		}

		unset($original['quotation_id']); // remove PK for insert
		$original['quotation_date'] 	= date('Y-m-d', strtotime($this->input->post('quotation_date')));
		$original['project'] 			= $this->input->post('project');
		$original['reference'] 			= $this->input->post('ref_no');
		$original['subtotal'] 			= $this->input->post('sub_total');
		$original['vat_amt'] 			= $this->input->post('vat_amount');
		$original['vat_percent'] 		= $this->input->post('vat_per');
		$original['grand_total'] 		= $this->input->post('grand_total');
		$original['payment_term'] 		= $this->input->post('payment_terms');
		$original['delivery_term'] 		= $this->input->post('delivery_terms');
		$original['general_term'] 		= $this->input->post('general_terms');
		$original['validity'] 			= $this->input->post('validity');
		$original['quotation_code'] 	= $this->input->post('quotation_code');

		$original['revision'] 			= $original['revision'] + 1;
		$original['created_date'] 		= date('Y-m-d H:i:s');
		$this->db->insert('purchase_quotation_master', $original);
		$new_quotation_id 			= $this->db->insert_id();

		for ($i = 0; $i < count($_POST['item_id']); $i++) {
			$item = array(
				'qtn_master_id' 	=> $new_quotation_id,
				'product_id' 		=> $_POST['item_id'][$i],
				'desc' 				=> $_POST['item_description'][$i],
				'brand' 			=> $_POST['item_brand'][$i] ?? '',
				'unit_id' 			=> $_POST['item_unit'][$i],
				'quantity' 			=> $_POST['item_quantity'][$i],
				'price'  			=> $_POST['unit_price'][$i],
				'total'  			=> $_POST['total_price'][$i],
				'dis_per'  			=> $_POST['dis_per'][$i],
				'dis_amt'  			=> $_POST['dis_amt'][$i],
				//'dis_per2'  		=> $_POST['dis_per2'][$i],
				//'dis_amt2'  		=> $_POST['dis_amt2'][$i],
				'unit_price' 		=> $_POST['final_unit_price'][$i]
			);
			$this->db->insert('purchase_qtn_transaction', $item);
		}


		$allowedExts 		= array("jpeg", "jpg", "png", "doc", "pdf");
		$fileName 			= $_FILES["quote_doc"]["name"];
		$temp 				= explode(".", $fileName);
		$extension 			= end($temp);

		if (!empty($fileName) && ($_FILES["quote_doc"]["size"] < 15728640) && in_array($extension, $allowedExts)) {
			if ($_FILES["quote_doc"]["error"] == 0) {
				$timestamp1 		= time();
				$file_tmp 			= $_FILES["quote_doc"]["tmp_name"];
				$uploaded_file 		= $timestamp1 . "_" . $fileName;
				move_uploaded_file($file_tmp, "public/uploaded_documents/" . $uploaded_file);

				$doc_data = array(
					'doc_master_id' 	=> $new_quotation_id,
					'doc_type' 			=> "Quote File",
					'doc_path' 			=> $uploaded_file
				);

				$this->db->insert('purchase_documents', $doc_data);
			}
		}

		return $new_quotation_id;
	}

	function delete_quote($quote_id)
	{
		$this->db->query("delete from purchase_qtn_transaction where qtn_master_id='$quote_id'");
		$this->db->query("delete from purchase_quotation_master where quotation_id='$quote_id'");

		// $user_se_id=$this->session->userdata('user_id');
		// $page_name=explode('index.php/', $_SERVER['PHP_SELF']);
		// $ci = get_instance();
		// $ci->load->helper('log');
		// $log_msg=add_log_entry($user_se_id,3,$page_name[1],'grn_master','grn_id',$grn_id);
		// 
	}

	function add_purchase_order()
	{
		$prifix = 'AVE/POD/';
		$this->load->model('Setup_model');
		$num = $this->Setup_model->get_next_code($prifix, 'po_code', 'purchase_order_master', 12) + 1;
		$digit = sprintf("%1$04d", $num);
		$code = $prifix . date("y") . '/' . $digit;


		$data = array(
			'po_code' 			=> $code,
			'po_date' 			=> date('Y-m-d', strtotime($this->input->post('po_date'))),
			'revision_date'		=> date('Y-m-d', strtotime($this->input->post('po_date'))),
			'qtn_id' 			=> $this->input->post('quotation_id') ?? 0,
			'supplier_ref' 		=> $this->input->post('ref_no'),
			'branch_id' 		=> $this->input->post('Branch_id'),
			'supplier_id' 		=> $this->input->post('supplier_id'),
			'subject' 			=> $this->input->post('subject'),
			'sub_total' 		=> $this->input->post('sub_total'),
			'vat_amt' 			=> $this->input->post('vat_amount'),
			'vat_percent' 		=> $this->input->post('vat_per'),
			'discount_percent'  => $this->input->post('discount_per'),
			'discount' 			=> $this->input->post('discount_amt'),
			'project' 			=> $this->input->post('project'),

			'freight_mode' 		=> $this->input->post('freight_mode'),
			'trans_charge' 		=> $this->input->post('transportation_charge'),
			'cust_charge' 		=> $this->input->post('customs_charge'),
			'add_charge' 		=> $this->input->post('other_charge'),
			'total_beforevat' 	=> $this->input->post('total_beforvat'),
			'grand_total' 		=> $this->input->post('grand_total'),

			'validity' 			=> $this->input->post('validity'),
			'payment_term' 		=> $this->input->post('payment_terms'),
			'delivery_term' 	=> $this->input->post('delivery_terms'),
			'general_term' 		=> $this->input->post('general_terms'),
			'po_status' 		=> 0,

			'prepared_by'       => $this->input->post('employee_prepared'),
			'checked_by'        => $this->input->post('employee_checked'),
			'approved_by'       => $this->input->post('employee_approved'),
			'created_by' 		=> $this->session->userdata('user_id'),
			'created_date' 		=> date('Y-m-d H:i:s')
			//   'currency_id' => $this->input->post('cid'),
			//   'currency_rate' => $this->input->post('crate'),
		);
		$this->db->insert('purchase_order_master', $data);
		$insert_id = $this->db->insert_id();

		$allowedExts = array("jpeg", "jpg", "png", "doc", "pdf");
		$data['file_name'] = $_FILES["po_doc"]["name"];
		$temp = explode(".", $_FILES["po_doc"]["name"]);
		$extension = end($temp);
		if ((!empty($data['file_name'])) && ($_FILES["po_doc"]["size"] < 15728640) && in_array($extension, $allowedExts)) {
			if ($_FILES["po_doc"]["error"] > 0) {
				$this->session->set_flashdata('error', 'Failed to upload - Please check file size and file format');
			} else {
				$timestamp1 = time();
				$file_tmp = $_FILES["po_doc"]["tmp_name"];
				$other_file = $timestamp1 . "_" . $_FILES['po_doc']['name'];

				//move_uploaded_file($file_tmp,"/home/webadmin/gen/avengers_erp/public/uploaded_documents/".$other_file);
				move_uploaded_file($file_tmp, "public/uploaded_documents/" . $other_file);

				$data = array(
					'doc_master_id' => $insert_id,
					'doc_type' => "PO File",
					'doc_path' =>  $other_file,
				);
				$this->db->insert('purchase_documents', $data);
			}
		}
		if ($insert_id) {
			for ($i = 0; $i < count($_POST['item_id']); $i++) {
				$data = array(
					'po_master_id' => $insert_id,
					'product_id' => $_POST['item_id'][$i],
					'desc' => $_POST['item_description'][$i],
					'brand' => $_POST['item_brand'][$i] ?? '',
					'unit_id' => $_POST['item_unit'][$i],
					// 'packing_id' =>$_POST['item_packing'][$i],
					'quantity'  => $_POST['item_quantity'][$i],
					'price'  => $_POST['unit_price'][$i],
					'total'  => $_POST['total_price'][$i],
					// 'dis_per'  => $_POST['dis_per'][$i],
					// 'dis_amt'  => $_POST['dis_amt'][$i],
					//'dis_per2'  => $_POST['dis_per2'][$i],
					//'dis_amt2'  => $_POST['dis_amt2'][$i],
					// 'unit_price' => $_POST['final_unit_price'][$i]
				);

				$this->db->insert('purchase_order_transaction', $data);
			}
		}
		if (!empty($this->input->post('quotation_id'))) {
			$this->db->where('quotation_id', $this->input->post('quotation_id'));
			$this->db->update('purchase_quotation_master', ['po_created' => 1]);
		}

		return $insert_id;
	}

	function add_purchase_order_direct()
	{
		$prifix = 'AVE/POD/';
		$this->load->model('Setup_model');
		$num = $this->Setup_model->get_next_code($prifix, 'po_code', 'purchase_order_master', 12) + 1;
		$digit = sprintf("%1$04d", $num);
		$code = $prifix . date("y") . '/' . $digit;


		$data = array(
			'po_type' 			=> "direct",
			'po_code' 			=> $code,
			'po_date' 			=> date('Y-m-d', strtotime($this->input->post('po_date'))),
			'revision_date'		=> date('Y-m-d', strtotime($this->input->post('po_date'))),
			'qtn_id' 			=> $this->input->post('quotation_id') ?? 0,
			'supplier_ref' 		=> $this->input->post('ref_no'),
			'branch_id' 		=> $this->input->post('branch_id'),
			'supplier_id' 		=> $this->input->post('supplier_id'),
			'subject' 			=> $this->input->post('subject'),
			'sub_total' 		=> $this->input->post('sub_total'),
			'vat_amt' 			=> $this->input->post('vat_amount'),
			'vat_percent' 		=> $this->input->post('vat_per'),
			'discount_percent'  => $this->input->post('discount_per'),
			'discount' 			=> $this->input->post('discount_amt'),
			'project' 			=> $this->input->post('project'),

			'freight_mode' 		=> $this->input->post('freight_mode'),
			'trans_charge' 		=> $this->input->post('transportation_charge'),
			'cust_charge' 		=> $this->input->post('customs_charge'),
			'add_charge' 		=> $this->input->post('other_charge'),
			'total_beforevat' 	=> $this->input->post('total_beforvat'),
			'grand_total' 		=> $this->input->post('grand_total'),
			'conversion_rate'   => $this->input->post('conversion_rate'),
			'base_currency_grand_total' => $this->input->post('base_currency_grand_total'),

			'validity' 			=> $this->input->post('validity'),
			'payment_term' 		=> $this->input->post('payment_terms'),
			'delivery_term' 	=> $this->input->post('delivery_terms'),
			'general_term' 		=> $this->input->post('general_terms'),
			'po_status' 		=> 0,
			'prepared_by'       => $this->input->post('employee_prepared'),
			'checked_by'        => $this->input->post('employee_checked'),
			'approved_by'       => $this->input->post('employee_approved'),
			'created_by' 		=> $this->session->userdata('user_id'),
			'created_date' 		=> date('Y-m-d H:i:s'),
			// 'prepared_by' => $this->session->userdata('user_name')

			//    'approved_by'  => $this->input->post('approved_by')


			//   'currency_id' => $this->input->post('cid'),
			//   'currency_rate' => $this->input->post('crate'),
		);


		$this->db->insert('purchase_order_master', $data);
		$insert_id = $this->db->insert_id();

		$allowedExts = array("jpeg", "jpg", "png", "doc", "pdf");
		$data['file_name'] = $_FILES["po_doc"]["name"];
		$temp = explode(".", $_FILES["po_doc"]["name"]);
		$extension = end($temp);
		if ((!empty($data['file_name'])) && ($_FILES["po_doc"]["size"] < 15728640) && in_array($extension, $allowedExts)) {
			if ($_FILES["po_doc"]["error"] > 0) {
				$this->session->set_flashdata('error', 'Failed to upload - Please check file size and file format');
			} else {
				$timestamp1 = time();
				$file_tmp = $_FILES["po_doc"]["tmp_name"];
				$other_file = $timestamp1 . "_" . $_FILES['po_doc']['name'];

				//move_uploaded_file($file_tmp,"/home/webadmin/gen/avengers_erp/public/uploaded_documents/".$other_file);
				move_uploaded_file($file_tmp, "public/uploaded_documents/" . $other_file);

				$data = array(
					'doc_master_id'	 => $insert_id,
					'doc_type' 		 => "PO File",
					'doc_path' 		 =>  $other_file,
				);
				$this->db->insert('purchase_documents', $data);
			}
		}
		if ($insert_id) {
			for ($i = 0; $i < count($_POST['item_id']); $i++) {
				$data = array(
					'po_master_id' 	 => $insert_id,
					'product_id'	 => $_POST['item_id'][$i],
					'desc' 			 => $_POST['item_description'][$i],
					'brand'          => $_POST['item_brand'][$i] ?? '',
					'unit_id'        => $_POST['item_unit'][$i],
					// 'packing_id' =>$_POST['item_packing'][$i],
					'quantity'  	 => $_POST['item_quantity'][$i],
					'price'  		 => $_POST['unit_price'][$i],
					'total'  		 => $_POST['total_price'][$i],
					// 'dis_per'  		 => $_POST['dis_per'][$i],
					// 'dis_amt'  		 => $_POST['dis_amt'][$i],
					//'dis_per2'  => $_POST['dis_per2'][$i],
					//'dis_amt2'  => $_POST['dis_amt2'][$i],
					// 'unit_price' 	=> $_POST['final_unit_price'][$i]
				);

				$this->db->insert('purchase_order_transaction', $data);
			}
		}
		if (!empty($this->input->post('quotation_id'))) {
			$this->db->where('quotation_id', $this->input->post('quotation_id'));
			$this->db->update('purchase_quotation_master', ['po_created' => 1]);
		}

		return $insert_id;
	}

	function get_po_list()
	{
		$query = $this->db->query("select r.*, s.supplier_name,d.doc_path from purchase_order_master r left join purchase_documents d on r.po_id = d.doc_master_id left join supplier_master s on r.supplier_id=s.supplier_id order by r.po_id desc;");
		return $query->result();
	}

	function get_approved_po_list()
	{
		$query = $this->db->query("
        SELECT r.*, s.supplier_name
        FROM purchase_order_master r
        JOIN supplier_master s 
            ON r.supplier_id = s.supplier_id
        WHERE r.po_status = 1
            AND r.grn_status = 0
        ORDER BY r.po_id DESC
    ");

		return $query->result();
	}
	// function get_approved_po_list()
	// {
	// 	$query=$this->db->query("select r.*, s.supplier_name from purchase_order_master r, supplier_master s where r.supplier_id=s.supplier_id and grn_status=0 and r.po_status=1 order by po_id desc");
	// 	return $query->result();
	// }
	/*function get_po_master_by_id($po_id)
	{
		$query=$this->db->query("select one.*, three.user_name, qtn.quotation_code from (select po.*, supplier_name,contact_number, s.supplier_email, billing_address, s.billing_city, s.billing_state, s.billing_po_box, s.billing_country, s.shipping_address, s.shipping_city, s.shipping_po_box, s.shipping_country,s.shipping_state,s.contact_person,s.contact_person_number from purchase_order_master po, supplier_master s where po.supplier_id=s.supplier_id and po.po_id=$po_id)as one left join(select * from users)as three on(one.approved_person=three.user_id) left join purchase_quotation_master qtn on qtn.quotation_id=one.qtn_id; ");
		return $query->result();
	}*/

	function get_po_direct_master_by_id($po_id)
	{
		$query = $this->db->query("
			SELECT 
				po.*, 
				s.supplier_name, 
				s.contact_number, 
				s.supplier_email, 
				s.billing_address, 
				s.billing_city, 
				s.billing_state, 
				s.billing_po_box, 
				s.billing_country, 
				s.shipping_address, 
				s.shipping_city, 
				s.shipping_state, 
				s.shipping_po_box, 
				s.shipping_country, 
				s.contact_person, 
				s.contact_person_number,
				c.currency_abbr,
				u.user_name AS approved_person_name,
				qtn.quotation_code,
				b.branch_header, 
				b.branch_footer, 
				b.branch_logo, 
				b.branch_address, 
				b.branch_location, 
				b.branch_trn, 
				b.branch_web, 
				b.branch_email, 
				b.branch_contact, 
				b.branch_manager, 
				b.branch_name
			FROM purchase_order_master po
			INNER JOIN supplier_master s 
				ON po.supplier_id = s.supplier_id
			LEFT JOIN currency_master c 
				ON s.currency_id = c.currency_id
			INNER JOIN branch_master b 
				ON po.branch_id = b.branch_id
			LEFT JOIN users u 
				ON po.approved_person = u.user_id
			LEFT JOIN purchase_quotation_master qtn 
				ON po.qtn_id = qtn.quotation_id
			WHERE po.po_id = $po_id
		");

		return $query->result();
	}

	function get_po_master_by_id($po_id)
	{
		$query = $this->db->query("
			SELECT 
				one.*, 
				three.user_name, 
				qtn.quotation_code,
				b.branch_name
			FROM 
			(
				SELECT 
					po.*, 
					s.supplier_name,
					s.contact_number, 
					s.supplier_email, 
					s.billing_address, 
					s.billing_city, 
					s.billing_state, 
					s.billing_po_box, 
					s.billing_country, 
					s.shipping_address, 
					s.shipping_city, 
					s.shipping_po_box, 
					s.shipping_country,
					s.shipping_state,
					s.contact_person,
					s.contact_person_number,
					c.currency_abbr
				FROM 
					purchase_order_master po
				JOIN supplier_master s 
					ON po.supplier_id = s.supplier_id
				JOIN purchase_quotation_master q 
					ON po.qtn_id = q.quotation_id
				LEFT JOIN currency_master c 
					ON s.currency_id = c.currency_id
				WHERE po.po_id = $po_id
			) AS one
			LEFT JOIN users AS three 
				ON one.approved_person = three.user_id
			LEFT JOIN branch_master AS b 
				ON one.branch_id = b.branch_id
			LEFT JOIN purchase_quotation_master AS qtn 
				ON qtn.quotation_id = one.qtn_id
		");
		return $query->result();
	}

	function get_po_master_by_id_for_direct($po_id, $po_type = 'direct')
	{
		// Base select
		$this->db->select("
        po.*,
        s.supplier_name,
        s.contact_number,
        s.supplier_email,
        s.billing_address,
        s.billing_city,
        s.billing_state,
        s.billing_po_box,
        s.billing_country,
        s.shipping_address,
        s.shipping_city,
        s.shipping_po_box,
        s.shipping_country,
        s.shipping_state,
        s.contact_person,
        s.contact_person_number,
        u.user_name AS approved_person_name,
        b.branch_name
    ");
		$this->db->from('purchase_order_master po');
		$this->db->join('supplier_master s', 'po.supplier_id = s.supplier_id', 'left');
		$this->db->join('branch_master b', 'po.branch_id = b.branch_id', 'left');
		$this->db->join('users u', 'po.approved_person = u.user_id', 'left');

		// Only join quotation if type is not direct
		if ($po_type !== 'direct') {
			$this->db->join('purchase_quotation_master q', 'po.qtn_id = q.quotation_id', 'left');
			$this->db->select('q.quotation_code');
		}

		$this->db->where('po.po_id', $po_id);
		$query = $this->db->get();

		return $query->result();
	}


	/*function get_po_tr_by_id($po_id)
	{
		//$query=$this->db->query("select * from purchase_order_transaction tr left join item_master pm on tr.product_id = pm.item_id left join unit_master um on pm.item_unit = um.unit_id  where  tr.po_master_id='$po_id'");
		$query = $this->db->query("
			SELECT 
				tr.*, 
				pm.*, 
				um.unit_name, 
				bm.brand_name
			FROM purchase_order_transaction tr
			LEFT JOIN item_master pm ON tr.product_id = pm.item_id
			LEFT JOIN unit_master um ON pm.item_unit = um.unit_id
			LEFT JOIN brand_master bm ON pm.item_brand = bm.brand_id
			WHERE tr.po_master_id = '$po_id'
		");

		return $query->result();
	}
	public function get_received_qty_by_poid($po_id)
	{
		$this->db->select('gt.product_id, SUM(gt.quantity) AS received_qty');
		$this->db->from('grn_master gm');
		$this->db->join('grn_transaction gt', 'gm.grn_id = gt.grn_master_id');
		$this->db->where('gm.po_id', $po_id);
		$this->db->group_by('gt.product_id');

		$query = $this->db->get();
		return $query->result_array();
	}*/

	public function get_po_tr_by_id($po_id)
	{
		$query = $this->db->query("
		SELECT 
			tr.*, 
			pm.*, 
			um.unit_name, 
			IFNULL(SUM(gt.rec_quantity), 0) AS received_qty
		FROM purchase_order_transaction tr
		LEFT JOIN item_master pm 
			ON tr.product_id = pm.product_id
		LEFT JOIN unit_master um 
			ON pm.unit_id = um.unit_id
		LEFT JOIN purchase_grn_master gm 
			ON gm.po_id = tr.po_master_id
		LEFT JOIN purchase_grn_transaction gt 
			ON gt.grn_master_id = gm.grn_id 
			AND gt.product_id = tr.product_id
		WHERE tr.po_master_id = '$po_id'
		GROUP BY tr.product_id
		ORDER BY tr.product_id ASC
		");
		return $query->result();
	}



	// function approve_purchase_order($po_id)
	// {
	// 	$this->db->where('po_id', $po_id);
	// 	$this->db->update('purchase_order_master', ['po_status' => 1, 'approved_person' => $this->input->post('user_id')]);
	// 	return ($this->db->affected_rows() > 0);
	// }


	function approve_purchase_order($po_id, $approved_by)
	{
		$this->db->where('po_id', $po_id);
		$this->db->update('purchase_order_master', [
			'po_status' => 1,
			'approved_person' => $approved_by
		]);

		return true;
	}

	function update_purchase_order()
	{

		$po_id = $this->input->post('po_id');

		$data = array(

			'po_date' 				=> date('Y-m-d', strtotime($this->input->post('po_date'))),
			'revision_date'			=> date('Y-m-d', strtotime($this->input->post('po_date'))),
			//'qtn_id' 				=> $this->input->post('quotation_id'),
			'supplier_ref' 			=> $this->input->post('ref_no'),
			'branch_id' 			=> $this->input->post('Branch_id'),
			'supplier_id'			=> $this->input->post('supplier_id'),
			'subject' 				=> $this->input->post('subject'),
			'sub_total' 			=> $this->input->post('sub_total'),
			'vat_amt' 				=> $this->input->post('vat_amount'),
			'vat_percent' 			=> $this->input->post('vat_per'),
			'discount_percent' 		=> $this->input->post('discount_per'),
			'discount' 				=> $this->input->post('discount_amt'),
			'freight_mode'			=> $this->input->post('freight_mode'),
			//   'currency_id' => $this->input->post('cid'),
			//   'currency_rate' => $this->input->post('crate'),

			'trans_charge' 			=> $this->input->post('transportation_charge'),
			'cust_charge' 			=> $this->input->post('customs_charge'),
			'add_charge' 			=> $this->input->post('other_charge'),
			'total_beforevat' 		=> $this->input->post('total_beforvat'),
			'grand_total' 			=> $this->input->post('grand_total'),


			'validity' 				=> $this->input->post('validity'),
			'payment_term' 			=> $this->input->post('payment_terms'),
			'delivery_term' 		=> $this->input->post('delivery_terms'),
			'general_term' 			=> $this->input->post('general_terms'),

			'created_by' 			=> $this->session->userdata('user_id'),
			'created_date' 			=> date('Y-m-d H:i:s'),
			// 'prepared_by' 			=> $this->input->post('prepared_by'),
			'prepared_by' 			=> $this->input->post('employee_prepared'),
			'checked_by' 			=> $this->input->post('employee_checked'),
			'approved_by' 			=> $this->input->post('employee_approved'),
		);

		$this->db->where('po_id', $po_id);
		$res = $this->db->update('purchase_order_master', $data);

		$this->db->select('*');
		$this->db->where('po_master_id', $po_id);
		$res = $this->db->delete('purchase_order_transaction');

		$allowedExts = array("jpeg", "jpg", "png", "doc", "pdf");
		$data['file_name'] = $_FILES["po_doc"]["name"];
		$temp = explode(".", $_FILES["po_doc"]["name"]);
		$extension = end($temp);
		if ((!empty($data['file_name'])) && ($_FILES["po_doc"]["size"] < 15728640) && in_array($extension, $allowedExts)) {
			if ($_FILES["po_doc"]["error"] > 0) {
				$this->session->set_flashdata('error', 'Failed to upload - Please check file size and file format');
			} else {
				$timestamp1 = time();
				$file_tmp = $_FILES["po_doc"]["tmp_name"];
				$other_file = $timestamp1 . "_" . $_FILES['po_doc']['name'];

				//move_uploaded_file($file_tmp,"/home/webadmin/gen/avengers_erp/public/uploaded_documents/".$other_file);
				move_uploaded_file($file_tmp, "public/uploaded_documents/" . $other_file);

				$data = array(
					'doc_master_id' => $po_id,
					'doc_type'      => "PO File",
					'doc_path' 	  =>  $other_file,
				);
				$this->db->delete('purchase_documents', ['doc_master_id' => $po_id, 'doc_type' => 'PO File']);
				$this->db->insert('purchase_documents', $data);
			}
		}
		for ($i = 0; $i < count($_POST['item_id']); $i++) {
			$data = array(
				'po_master_id'  => $po_id,
				'product_id' 	=> $_POST['item_id'][$i],
				'desc' 			=> $_POST['item_description'][$i],
				'brand'         => $_POST['item_brand'][$i] ?? '',
				'unit_id' 		=> $_POST['item_unit'][$i],
				// 'packing_id' 	=> $_POST['item_packing'][$i],
				'quantity'  	=> $_POST['item_quantity'][$i],
				'price'  		=> $_POST['unit_price'][$i],
				'total'  		=> $_POST['total_price'][$i],
				// 'dis_per'  		=> $_POST['dis_per'][$i],
				// 'dis_amt'  		=> $_POST['dis_amt'][$i],
				// 'unit_price'    => $_POST['final_unit_price'][$i]
			);

			$this->db->insert('purchase_order_transaction', $data);
		}
		return $res;
	}

	function get_quote_doc($doc_id, $doc_type)
	{
		$query = $this->db->query("select * from purchase_documents where doc_master_id=$doc_id and doc_type='$doc_type'");
		return $query->result();
	}

	// GRN and stock entry 
	function add_grn_records()
	{

		$this->db->trans_begin(); // 🔐 START TRANSACTION

		$user_id = $this->session->userdata('user_id');
		$po_id   = $this->input->post('po_id');

		/* ================= GRN MASTER ================= */
		$data = array(
			'grn_code'          => $this->input->post('grn_code'),
			'grn_date'          => date('Y-m-d', strtotime($this->input->post('grn_date'))),
			'branch_id'         => $this->input->post('branch_id'),
			'supplier_id'       => $this->input->post('supplier_id'),
			'warehouse_id'      => $this->input->post('warehouse_id'),
			'po_id'             => $po_id,
			'delivery_details'  => $this->input->post('remarks'),
			'sub_total'         => $this->input->post('sub_total'),
			'vat_amt'           => $this->input->post('vat_amount'),
			'vat_percent'       => $this->input->post('vat_per'),
			'discount_percent'  => $this->input->post('discount_per'),
			'discount'          => $this->input->post('discount_amt'),
			'currency'          => $this->input->post('currency'),
			'currency_rate'     => $this->input->post('currancy_value'),
			'other_expence'     => $this->input->post('other_expence'),
			'total_beforevat'   => $this->input->post('total_beforevat'),
			'grand_total'       => $this->input->post('grand_total'),
			'converted_total'   => $this->input->post('converted_total'),
			'created_by'        => $user_id
		);

		$this->db->insert('purchase_grn_master', $data);
		$insert_id = $this->db->insert_id();

		/* ================= GRN ITEMS & STOCK ================= */
		foreach ($_POST['item_id'] as $i => $product_id) {

			// ---- GRN TRANSACTION
			$this->db->insert('purchase_grn_transaction', [
				'grn_master_id' => $insert_id,
				'srn'           => $i + 1,
				'product_id'    => $product_id,
				'ord_quantity'  => $_POST['item_quantity'][$i],
				'rec_quantity'  => $_POST['rec_quantity'][$i],
				'price'         => $_POST['unit_price'][$i],
				'unit'          => $_POST['item_unit'][$i],
				'landing_price' => $_POST['landing_price'][$i],
				'total'         => $_POST['total_price'][$i]
			]);

			$rec_qty = (int) $_POST['rec_quantity'][$i];

			/* ===== ALLOCATE TO PENDING REQUESTS ===== */
			if ($rec_qty > 0) {


				$pendingRows = $this->db
					->where('product_id', $product_id)
					->where('pending_quantity >', 0)
					->order_by('reserve_priority', 'ASC')
					->get('stock_details')
					->result();
				foreach ($pendingRows as $row) {

					if ($rec_qty <= 0) break;

					$pending  = (int) $row->pending_quantity;
					$reserved = (int) $row->reserved_quantity;

					if ($rec_qty >= $pending) {
						$new_reserved = $reserved + $pending;
						$new_pending  = 0;
						$rec_qty     -= $pending;
					} else {
						$new_reserved = $reserved + $rec_qty;
						$new_pending  = $pending - $rec_qty;
						$rec_qty      = 0;
					}

					$this->db->where('stock_id', $row->stock_id)
						->update('stock_details', [
							'reserved_quantity' => $new_reserved,
							'pending_quantity'  => $new_pending
						]);
				}
			}

			/* ===== INSERT REMAINING STOCK ===== */
			for ($s = 0; $s < $rec_qty; $s++) {
				$this->db->insert('stock_details', [
					'trans_id'     => $insert_id,
					'stock_date'   => date('Y-m-d', strtotime($this->input->post('grn_date'))),
					'year'         => date('Y', strtotime($this->input->post('grn_date'))),
					'stock_type'   => 'IN',
					'warehouse_id' => $this->input->post('warehouse_id'),
					'product_id'   => $product_id,
					'unit_id'      => $_POST['item_unit'][$i],
					'quantity'     => 1,
					'price'        => $_POST['unit_price'][$i],
					'remark'       => 'Purchase GRN',
					'created_by'   => $user_id,
					'created_date' => date('Y-m-d H:i:s')
				]);
			}
		}

		/* ================= UPDATE PO ================= */
		$status = $this->input->post('po_status');
		$query = $this->db->query("update purchase_order_master set grn_status=$status where po_id=$po_id;");

		// $this->db->where('po_id', $po_id)
		// 	->update('purchase_order_master', [
		// 		'grn_status' => 1, 
		// 		'grn_id'     => $insert_id
		// 	]);

		/* ================= ACCOUNT ENTRY ================= */
		$vid = null;
		$AccountCode = $this->input->post('grn_code');
		$vdate = $this->input->post('date');
		$vtime = date('H:i:s');

		foreach ($_POST['inv_debtor'] as $i => $debtor) {
			if ($_POST['inv_dr_amount'][$i] > 0) {
				$this->db->insert('voucher_transaction', [
					'voucher_code' => $AccountCode,
					'voucher_date' => date('Y-m-d H:i:s', strtotime("$vdate $vtime")),
					'voucher_type' => 'G',
					'customer_id'  => $this->input->post('supplier_id'),
					'account_id'   => $debtor,
					'amount'       => $_POST['inv_dr_amount'][$i],
					'drcr_type'    => 'Dr',
					'trans_id'     => $insert_id,
					'trans_type'   => 'G',
					'recordCreatedBy' => $user_id
				]);
				$vid = $this->db->insert_id();
			}
		}

		foreach ($_POST['inv_creditor'] as $i => $creditor) {
			if ($_POST['inv_cr_amount'][$i] > 0) {
				$this->db->insert('voucher_transaction', [
					'voucher_code' => $AccountCode,
					'voucher_date' => date('Y-m-d H:i:s', strtotime("$vdate $vtime")),
					'voucher_type' => 'G',
					'customer_id'  => $this->input->post('supplier_id'),
					'account_id'   => $creditor,
					'amount'       => $_POST['inv_cr_amount'][$i],
					'drcr_type'    => 'Cr',
					'trans_id'     => $insert_id,
					'trans_type'   => 'G',
					'recordCreatedBy' => $user_id
				]);
				$vid = $this->db->insert_id();
			}
		}

		/* ================= LOGGING ================= */
		if ($insert_id) {
			$ci = get_instance();
			$ci->load->helper('log');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			add_log_entry($user_id, 1, $page_name[1], 'purchase_grn_master', 'grn_id', $insert_id);
		}

		/* ================= COMMIT / ROLLBACK ================= */
		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();
		return $insert_id;
	}
	function add_grn_records_old()
	{
		$po_id = $this->input->post('po_id');

		$data = array(
			'grn_code' 			=> $this->input->post('grn_code'),
			'grn_date'  		=> date('Y-m-d', strtotime($this->input->post('grn_date'))),
			'branch_id' 		=> $this->input->post('branch_id'),
			'supplier_id' 		=> $this->input->post('supplier_id'),
			'warehouse_id' 		=> $this->input->post('warehouse_id'),
			'po_id'  			=> $po_id,
			'delivery_details'  => $this->input->post('remarks'),
			'sub_total' 		=> $this->input->post('sub_total'),
			'vat_amt' 			=> $this->input->post('vat_amount'),
			'vat_percent' 		=> $this->input->post('vat_per'),
			'discount_percent' 	=> $this->input->post('discount_per'),
			'discount' 			=> $this->input->post('discount_amt'),
			'currency' 			=> $this->input->post('currency'),
			'currency_rate' 	=> $this->input->post('currancy_value'),
			'other_expence'		=> $this->input->post('other_expence'),
			'total_beforevat'	=> $this->input->post('total_beforevat'),
			'grand_total'		=> $this->input->post('grand_total'),
			'converted_total' 	=> $this->input->post('converted_total'),
			'created_by'   		=> $this->session->userdata('user_id'),
		);

		$this->db->insert('purchase_grn_master', $data);
		$insert_id = $this->db->insert_id();

		// === Insert GRN Item Details & Stock ===
		foreach ($_POST["item_id"] as $i => $product_id) {
			$data1 = array(
				'grn_master_id' => $insert_id,
				'srn'           => $i + 1,
				'product_id'    => $product_id,
				'ord_quantity'  => $_POST['item_quantity'][$i],
				'rec_quantity'  => $_POST['rec_quantity'][$i],
				'price'         => $_POST['unit_price'][$i],
				'unit'          => $_POST['item_unit'][$i],
				'landing_price' => $_POST['landing_price'][$i],
				'total'         => $_POST['total_price'][$i],
				//'storage'       => $_POST['storage'][$i],
				//'alloc_qty'     => $_POST['alloc_quantity'][$i],
			);

			$this->db->insert('purchase_grn_transaction', $data1);

			// Stock IN entries
			$rec_qty = (int)$_POST['rec_quantity'][$i];
			for ($s = 0; $s < $rec_qty; $s++) {
				$data2 = array(
					'trans_id'         => $insert_id,
					'stock_date'       => date('Y-m-d', strtotime($this->input->post('grn_date'))),
					'year'             => date('Y', strtotime($this->input->post('grn_date'))),
					'stock_type'       => 'IN',
					'warehouse_id'     => $this->input->post('warehouse_id'),
					'product_id'       => $product_id,
					'unit_id'          => $_POST['item_unit'][$i],
					'quantity'         => 1,
					'price'            => $_POST["unit_price"][$i],
					'remark'           => 'Purchase GRN',
					//'inv_type'         => $_POST['inv_type'][$i],
					//'storage_location' => $_POST["storage"][$i],
					'created_by'       => $this->session->userdata('user_id'),
					'created_date'     => date('Y-m-d H:i:s'),
				);
				$this->db->insert('stock_details', $data2);
			}
		}

		// === Update PO Status ===
		$status = $this->input->post('po_status');
		$this->db->query("UPDATE purchase_order_master SET grn_status = $status, grn_id = $insert_id WHERE po_id = $po_id");

		// === ACCOUNT ENTRY for GRN ===
		$AccountCode = $this->input->post('grn_code');
		$vdate = $this->input->post('date');
		$vtime = date('h:i:s');

		/// debit entry 
		for ($i = 0; $i < count($_POST['inv_debtor']); $i++) {
			$debtor = $_POST['inv_debtor'][$i];
			$dr_amount = $_POST['inv_dr_amount'][$i];
			if ($dr_amount > 0) {
				$data = array(
					'voucher_code' => $AccountCode,
					'voucher_date' => date('Y-m-d h:i:s', strtotime("$vdate $vtime")),
					'voucher_type' => 'G',  /// po invoice  entry
					'customer_id' => $this->input->post('supplier_id'),
					'account_id' => $debtor,
					'amount' => $dr_amount,
					'drcr_type' => 'Dr',
					//'narration' => $this->input->post('narration'),
					'trans_id' => $insert_id,
					'trans_type' => 'G',
					'recordCreatedBy' => $this->session->userdata('user_id')
				);
				$this->db->insert('voucher_transaction', $data);
				$vid = $this->db->insert_id();
			}
		}
		// credit entry
		for ($i = 0; $i < count($_POST['inv_creditor']); $i++) {
			$creditor = $_POST['inv_creditor'][$i];
			$cr_amount = $_POST['inv_cr_amount'][$i];
			if ($cr_amount > 0) {
				$data = array(
					'voucher_code' => $AccountCode,
					'voucher_date' => date('Y-m-d h:i:s', strtotime("$vdate $vtime")),
					'voucher_type' => 'G',  /// po invoice  entry
					'customer_id' => $this->input->post('supplier_id'),
					'account_id' => $creditor,
					'amount' => $cr_amount,
					'drcr_type' => 'Cr',
					//'narration' => $this->input->post('narration'),
					'trans_id' => $insert_id,
					'trans_type' => 'G',
					'recordCreatedBy' => $this->session->userdata('user_id')
				);
				$this->db->insert('voucher_transaction', $data);
				$vid = $this->db->insert_id();
			}
		}

		if ($vid) {
			$user_se_id = $this->session->userdata('session_id');
			$uid = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($uid, 1, $page_name[1], 'voucher_transaction', 'voucher_id', $vid);
			return $insert_id;
		}



		// === Logging (optional) ===
		if ($insert_id) {
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			add_log_entry($user_id, 1, $page_name[1], 'purchase_grn_master', 'grn_id', $insert_id);
		}

		return $insert_id;
	}



	function get_grn_list()
	{
		$query = $this->db->query("select r.*, s.supplier_name from purchase_grn_master r, supplier_master s where r.supplier_id=s.supplier_id  order by grn_id desc");
		return $query->result();
	}
	function get_grn_master_by_id($grn_id)
	{
		$query = $this->db->query("select one.*, three.user_name from (select po.*, supplier_name,contact_number, s.supplier_email, s.contact_person,s.contact_person_number,s.billing_address from purchase_grn_master po, supplier_master s where po.supplier_id=s.supplier_id and po.grn_id=$grn_id)as one left join(select * from users)as three on(one.created_by=three.user_id); ");
		return $query->result();
	}
	function get_grn_tr_by_id($grn_id)
	{
		$query = $this->db->query("select * from purchase_grn_transaction tr left join item_master pm on tr.product_id = pm.product_id left join unit_master um on pm.unit_id = um.unit_id  where  grn_master_id=$grn_id ");
		return $query->result();
	}
	function delete_grn($grn_id)
	{
		$this->db->query("delete from purchase_grn_transaction where grn_master_id='$grn_id'");
		$this->db->query("delete from purchase_grn_master where grn_id='$grn_id'");
		$this->db->query("delete from stock_details where trans_id='$grn_id'");
		$this->db->query("update purchase_order_master set grn_status=0 where grn_id='$grn_id'");
		// $user_se_id=$this->session->userdata('user_id');
		// $page_name=explode('index.php/', $_SERVER['PHP_SELF']);
		// $ci = get_instance();
		// $ci->load->helper('log');
		// $log_msg=add_log_entry($user_se_id,3,$page_name[1],'grn_master','grn_id',$grn_id);
		// return 1;
	}
	function get_po_details_by_id($po_id)
	{
		// Always use query bindings to prevent SQL injection
		$sql = "
        SELECT 
            one.*, 
            three.user_name,  
            four.currency_abbr 
        FROM (
            SELECT 
                po.*, 
                s.supplier_name,
                s.contact_number, 
                s.supplier_email, 
                s.billing_address, 
                s.billing_city, 
                s.billing_state, 
                s.billing_po_box, 
                s.billing_country, 
                s.shipping_address, 
                s.shipping_city, 
                s.shipping_po_box, 
                s.shipping_country,
                s.shipping_state,
                s.trn_no  
            FROM purchase_order_master po
            INNER JOIN supplier_master s ON po.supplier_id = s.supplier_id
            WHERE po.po_id = ?
        ) AS one
        LEFT JOIN (
            SELECT user_id,  user_name FROM users
        ) AS three ON one.approved_person = three.user_id
        LEFT JOIN (
            SELECT currency_id , currency_abbr FROM currency_master
        ) AS four ON one.currency_id = four.currency_id 
    ";

		$query = $this->db->query($sql, [$po_id]);
		return $query->result();
	}
	public function get_purchase_vat_summary($from_date = null, $to_date = null)
	{
		$this->db->select("
        SUM(sub_total) AS taxable,
        SUM(vat_amt) AS vat,
        SUM(sub_total + vat_amt) AS total
    ");
		$this->db->from('grn_master');
		//$this->db->where('cancelled', 0); // optional: only if you use this flag

		if (!empty($from_date) && !empty($to_date)) {
			$this->db->where('grn_date >=', $from_date);
			$this->db->where('grn_date <=', $to_date);
		}

		$query = $this->db->get();
		return $query->row(); // single summary row
	}

	public function get_purchase_vat_details($from_date, $to_date)
	{
		$this->db->select("g.grn_code, g.grn_date, g.sub_total AS taxable, g.vat_amt AS vat, 
                       (g.sub_total + g.vat_amt) AS total, g.supplier_id, g.invoice_no, s.supplier_name");
		$this->db->from('grn_master g');
		$this->db->join('supplier_master s', 'g.supplier_id = s.supplier_id', 'left'); // Left join in case some GRNs have no supplier
		$this->db->where('g.grn_date >=', $from_date);
		$this->db->where('g.grn_date <=', $to_date);
		$this->db->order_by('g.grn_date', 'ASC');
		$query = $this->db->get();
		return $query->result();
	}

	//PR start

	public function get_purchase_request_by_id($pr_id)
	{
		$this->db->select('pr.*, b.branch_name, s.supplier_name, u.user_name as created_by_name');
		$this->db->from('purchase_requests pr');
		$this->db->join('branch_master b', 'b.branch_id=pr.branch_id', 'left');
		$this->db->join('supplier_master s', 's.supplier_id=pr.supplier_id', 'left');
		$this->db->join('users u', 'u.user_id=pr.created_by', 'left');
		$this->db->where('pr.pr_id', $pr_id);
		return $this->db->get()->row();
	}


	public function get_pr_items($pr_id)
	{
		$this->db->select('
			pri.*,
			im.product_name,
			im.product_code,
			im.description,
			im.retail_price,
			im.unit_id,
			u.unit_name
		');

		$this->db->from('purchase_request_items pri');

		$this->db->join(
			'item_master im',
			'im.product_id = pri.product_id',
			'left'
		);

		$this->db->join(
			'unit_master u',
			'u.unit_id = im.unit_id',
			'left'
		);

		$this->db->where('pri.pr_id', $pr_id);

		return $this->db->get()->result();
	}

	public function get_PR_list()
	{
		$this->db->select('pr_id, pr_code');
		$this->db->from('purchase_requests');
		// $this->db->where('status', 'approved'); // optional
		$query = $this->db->get();
		return $query->result();
	}
	//pending quantity Purchase request
	public function get_issued_mi_with_pending_qty()
	{
		return $this->db->distinct()
			->select('mi.mi_id, mi.mi_code')
			->from('material_issue mi')
			->join('material_issue_items mii', 'mii.mi_id = mi.mi_id', 'inner')
			->where('mi.status', 'Issued')
			->where('mii.pending_qty >', 0)
			->get()
			->result();
	}
	public function get_pr_from_mi_list()
	{
		return $this->db->select('
                pr.pr_id,
                pr.pr_code,
                pr.pr_date,
                mi.mi_code,
                b.branch_name,
                s.supplier_name
            ')
			->from('purchase_requests pr')
			->join('material_issue mi', 'mi.mi_id = pr.mi_id', 'left')
			->join('branch_master b', 'b.branch_id = pr.branch_id', 'left')
			->join('supplier_master s', 's.supplier_id = pr.supplier_id', 'left')
			->order_by('pr.pr_id', 'DESC')
			->get()
			->result();
	}
	public function get_pr_by_id($pr_id)
	{
		return $this->db->get_where('purchase_requests', ['pr_id' => $pr_id])->row();
	}

	public function get_pr_items_by_pr_id($pr_id)
	{
		$this->db->select("
			pri.*,
			im.product_name AS item_name,
			im.description AS item_description,
			u.unit_name
		");

		$this->db->from('purchase_request_items pri');
		$this->db->join('item_master im', 'im.product_id = pri.product_id', 'left');
		$this->db->join('unit_master u', 'u.unit_id = pri.unit_id', 'left');
		$this->db->where('pri.pr_id', $pr_id);

		return $this->db->get()->result();
	}
	// public function get_pr_items_by_pr_id($pr_id)
	// {
	// 	$this->db->select('
	//     pri.*, 
	//     im.item_name, 
	//     im.item_description, 
	//     bm.brand_name,
	//     u.unit_name
	// ');
	// 	$this->db->from('purchase_request_items pri');
	// 	$this->db->join('item_master im', 'im.item_id = pri.product_id', 'left');
	// 	$this->db->join('unit_master u', 'u.unit_id = pri.unit_id', 'left');
	// 	$this->db->join('brand_master bm', 'bm.brand_id = im.item_brand', 'left');
	// 	$this->db->where('pri.pr_id', $pr_id);
	// 	return $this->db->get()->result();
	// }

	public function delete_po($po_id)
	{
		if (!$po_id) return false;

		// Begin transaction
		$this->db->trans_start();

		// Delete child transactions first
		$this->db->delete('purchase_order_transaction', ['po_master_id' => $po_id]);

		// Delete master record
		$this->db->delete('purchase_order_master', ['po_id' => $po_id]);

		// Complete transaction
		$this->db->trans_complete();

		return $this->db->trans_status(); // returns true if all queries succeeded
	}

	function get_po_supplier_details($po_id)
	{
		$query = $this->db->query("
			SELECT
				po.po_id,
				po.po_code,
				po.supplier_id,

				s.supplier_name,
				s.contact_number,
				s.supplier_email,

				s.billing_address,
				s.billing_city,
				s.billing_state,
				s.billing_po_box,
				s.billing_country,

				s.shipping_address,
				s.shipping_city,
				s.shipping_state,
				s.shipping_po_box,
				s.shipping_country,

				s.contact_person,
				s.contact_person_number,

				c.currency_id,
				c.currency_abbr

			FROM purchase_order_master po

			INNER JOIN supplier_master s
				ON po.supplier_id = s.supplier_id

			LEFT JOIN currency_master c
				ON s.currency_id = c.currency_id

			WHERE po.po_id = ?
		", [$po_id]);

		return $query->row();
	}
}
