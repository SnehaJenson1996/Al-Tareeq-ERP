<?php date_default_timezone_set('Asia/Kolkata');

class Accounts_model extends CI_Model
{


	function get_account_balance($account_id, $from_date)
	{

		$query = $this->db->query("select coalesce(two1.opening_sum,0)+coalesce(two.opening,0)as opening_bal from (select r.account_id, Case when opening_bal_type ='Dr' THEN opening_balance ELSE -opening_balance END as opening from general_ledger r where r.account_id='$account_id') as two LEFT JOIN (select account_id,SUM(CASE WHEN drcr_type= 'Dr' THEN amount ELSE (-amount) end) AS opening_sum from voucher_transaction v where cancel=0 and date(v.voucher_date) <= '$from_date' and v.account_id='$account_id' group by account_id) as two1 on (two.account_id=two1.account_id);");
		return $query->row('opening_bal');
	}

	function get_gen_ledger_detors_records()
	{ // General ledger Accounts Name (Customers)

		$query = $this->db->query("select gl.*, ag.group_name from general_ledger gl, account_group ag where gl.group_no=ag.group_no and group_name='Sundry Debtors' order by account_name; ");

		return $query->result();
	}

	function get_gen_ledger_creditors_records()
	{ // General ledger Accounts Name 

		$query = $this->db->query("select gl.*, ag.group_name from general_ledger gl, account_group ag where gl.group_no=ag.group_no and group_name='Sundry Creditors' order by account_name;");

		return $query->result();
	}

	function get_general_ledger_by_group($gname)
	{
		$query = $this->db->query("select account_id, account_name from general_ledger gl,account_group ag where gl.group_no=ag.group_no and group_name='$gname'");
		return $query->result();
	}
	function get_general_ledger_accounts($cond1, $cond2)
	{
		$query = $this->db->query("select account_id, account_name,gl.customer_id from general_ledger gl,account_group ag where gl.group_no=ag.group_no and (sno like '%$cond1%' or sno like '%$cond2%' or sno like '%1%')");
		return $query->result();
	}
	function get_all_general_ledger_accounts()
	{
		$query = $this->db->query("select * from general_ledger order by account_name");
		return $query->result();
	}

	function get_cust_account_Id($cid)
	{
		$query = $this->db->query("select account_id from general_ledger where group_no=30 and customer_id='$cid'");
		return $query->row('account_id');
	}

	function get_supplier_account_Id($sid)
	{
		$query = $this->db->query("select account_id from general_ledger where group_no=29 and supplier_id='$sid'");
		return $query->row('account_id');
	}
	function view_account_transaction_details($voucher_id)
	{
		$query = $this->db->query("select voucher_code from voucher_transaction where voucher_id=$voucher_id");
		$vcode = $query->row('voucher_code');

		//$query = $this->db->query("select one.*, two.account_name from(select * from  voucher_transaction where voucher_code='$vcode')as one left join(select account_id, account_name from general_ledger)as two on(one.account_id=two.account_id);");
		$query = $this->db->query("SELECT one.*, two.account_name, i.invoice_code FROM (SELECT * FROM voucher_transaction WHERE voucher_code = '$vcode') AS one LEFT JOIN (SELECT account_id, account_name FROM general_ledger) AS two ON one.account_id = two.account_id LEFT JOIN invoice_master i ON one.trans_id = i.invoice_id;");
		return $query->result();
	}
	////////////////////////////////// Accounts Group Start ///////////////////////////////////
	function get_account_group_list()
	{
		//	$query = $this->db->query("select one.*, two.group_name as parent from (select * from account_group where parent_group!=0 order by group_code)as one left join (select * from account_group)as two on(one.parent_group=two.group_no) ");
		$query = $this->db->query("select  one.*, two.group_name as parent from (select * from account_group  order by group_code)as one left join (select * from account_group)as two on(one.parent_group=two.group_no) order by group_code;");
		return $query->result();
	}

	function get_account_group_list_by_id()
	{
		$g_no = $this->uri->segment('3');
		$query = $this->db->query("select  * from account_group where group_no='$g_no'");
		return $query->result();
	}

	function get_pandl_value($sno)
	{
		$query = $this->db->query("select pandl from account_group where group_no='$sno'");
		return $query->row('pandl');
	}

	function add_account_group_addition()
	{
		$parent_group = $this->input->post('p_group');
		$sno = $this->input->post('sec_in_account');
		$PandL = $this->get_pandl_value($sno);
		$account_grp = $this->input->post('ac_group');

		$flag = 0;
		$query = $this->db->query("select * from account_group where group_name = '$account_grp'");

		if ($query->num_rows() >= 1) {
			$flag = 1;
			return $flag;
		}

		if ($parent_group == 0) {
			$parent_group = $this->input->post('sec_in_account');
		} else {
			$parent_group = $this->input->post('p_group');
		}
		$data = array(
			'group_name' 	=> $this->input->post('ac_group'),
			'pandl' 		=> $PandL,
			'sno' 			=> $sno,
			'parent_group'  => $parent_group,
			'isdeleteable'  => 'Y'
		);
		$this->db->insert('account_group', $data);
		$insert_id = $this->db->insert_id();
		if ($insert_id) {
			$num 			= $insert_id % 100000;
			$digit 			= sprintf("%1$08d", $num);
			$group_code 	= '' . $digit;
			$this->db->query("update account_group set group_code = '$group_code' where group_no = $insert_id");

			$user_se_id 	= $this->session->userdata('session_id');
			$uid 			= $this->session->userdata('user_id');
			$page_name 		= explode('index.php/', $_SERVER['PHP_SELF']);
			$ci 			= get_instance();
			$ci->load->helper('log');
			$log_msg 		= add_log_entry($uid, 1, $page_name[1], 'account_group', 'group_no', $insert_id);
			return $flag;
		}
	}

	function update_account_group()
	{
		$parent_group = $this->input->post('p_group');
		$sno = $this->input->post('sec_in_account');
		$PandL = $this->get_pandl_value($sno);
		if ($parent_group == 0) {
			$parent_group = $this->input->post('sec_in_account');
		} else {
			$parent_group = $this->input->post('p_group');
		}
		$g_id = $this->input->post('ag_id');

		$data = array(
			'group_name' 	=> $this->input->post('ac_group'),
			'pandl' 	 	=> $PandL,
			'sno' 		  	=> $sno,
			'parent_group'  => $parent_group,
		);
		$this->db->where('group_no', $g_id);
		$this->db->update('account_group', $data);
		$insert_id = $g_id;
		if ($insert_id) {
			$user_se_id 	= $this->session->userdata('session_id');
			$uid 			= $this->session->userdata('user_id');
			$page_name 		= explode('index.php/', $_SERVER['PHP_SELF']);
			$ci 			= get_instance();
			$ci->load->helper('log');
			$log_msg 		= add_log_entry($uid, 2, $page_name[1], 'account_group', 'group_no', $insert_id);
			return $insert_id;
		}
	}

	function get_account_group_parent()
	{
		$query = $this->db->query("select * from account_group where parent_group!=0 ");
		return $query->result();
	}

	function get_account_section()
	{
		$query = $this->db->query("select * from account_group where parent_group=0 ");
		return $query->result();
	}

	function get_acc_group_record($id)
	{
		$query = $this->db->query("select group_name from account_group where group_no='$id'");
		return $query->row('group_name');
	}


	function delete_group_record($id)
	{
		$group = "group_no :" . $this->get_acc_group_record($id);
		$this->db->where('group_no', $id);
		$res = $this->db->delete('account_group');
		if ($res) {
			$user_se_id 	= $this->session->userdata('session_id');
			$uid 			= $this->session->userdata('user_id');
			$page_name 		= explode('index.php/', $_SERVER['PHP_SELF']);
			$ci 			= get_instance();
			$ci->load->helper('log');
			$log_msg 		= add_log_entry($uid, 3, $page_name[1], 'account_group', 'group_no', $id);

			return true;
		} else {
			return false;
		}

	}

	///////////////////////////////// Accounts Group End //////////////////////////////////

	/*------------------------------ General ledger Starts -----------------------------*/
	function get_account_group()
	{
		//	$query = $this->db->query("select * from account_group where parent_group!=0");
		$query = $this->db->query(" select * from account_group where parent_group!=0 order by group_name asc;");
		return $query->result();
	}

	function get_general_ledger_list()
	{ //general ledger list
		$query = $this->db->query("select a.*,g.group_name from general_ledger a,account_group g where a.group_no = g.group_no order by account_id ");
		return $query->result();
	}
	function get_ledger_list()
	{
		//$to_date=$this->input->post('to_date');
		//$from_date=$this->input->post('from_date');
		$query = $this->db->query("select * from ledger_master l,transactions_type t where t.trans_id=l.ledger_type ");
		return $query->result();
	}

	function get_general_ledger_list_by_id()
	{ //general ledger for edit
		$gn_led = $this->uri->segment('3');
		$query = $this->db->query("select  * from general_ledger where account_id='$gn_led'");
		return $query->result();
	}

	function get_opening_balance_by_id()
	{
		$gn_led = $this->uri->segment('3');
		$query = $this->db->query("select * from  voucher_transaction t where t.account_id='$gn_led'");
		return $query->result();
	}

	function get_gl_code_count($group_no)
	{
		$query = $this->db->query("select count(*)+1 as tcount from general_ledger where group_no='$group_no' ");
		return $query->row('tcount');
	}

	function get_customer_record()
	{ //in use
		$query = $this->db->query("select customer_id as occupier_id, customer_name as occu_name from customer_master");
		return $query->result();
	}

	function get_supplier_record()
	{ // in use
		// $query = $this->db->query("select supp_id,supp_name from supplier_data_entity");
		// return $query->result();
		return true;
	}

	function get_bank_records()
	{ // in use
		$query = $this->db->query("select account_id,account_name from general_ledger where group_no=5 ");
		return $query->result();
	}

	function get_cash_records()
	{ // in use
		$query = $this->db->query("select account_id,account_name from general_ledger where group_no=6 ");
		return $query->result();
	}

	function add_general_leadger()
	{ //in use
		$account_type = $this->input->post('account_type');
		$ac_group = $this->input->post('ac_group');
		$gl_code = $this->get_gl_code_count($ac_group);

		if ($account_type == 'CUS') {

			$ac_name 	= $this->input->post('CUS');
			$acc 		= explode(',', $ac_name);
			$name 		= $acc[0];
			$id 		= $acc[1];
			$s_id 		= "";
		} else if ($account_type == 'SUPP') {
			$sp_name 	= $this->input->post('SUPP');
			$acc_sp 	= explode(',', $sp_name);
			$name 		= $acc_sp[0];
			$s_id 		= $acc_sp[1];
			$id 		= "";
		}
		if ($account_type == 'OTHER') {
			$name = $this->input->post('ac_name');

		}

		$flag = 0;
		$query = $this->db->query("select * from general_ledger where account_name = '$name'");

		if ($query->num_rows() >= 1) {
			$flag = 1;
			return $flag;
		}

		$data = array(
			'gl_code' => $gl_code,
			//	'acc_type_id' => $user_id,
			'customer_id' 		=> $id,
			'supplier_id' 		=> $s_id,
			'account_name'  	=> $name,
			'group_no' 			=> $ac_group,
			'opening_balance'   => $this->input->post('opening_bal'),
			'opening_bal_type'  => $this->input->post('dr_cr_type'),
			'isdeleteable' 		=> 'Y',
            'branch_id'   => $this->input->post('branch_id'),


		);
		$this->db->insert('general_ledger', $data);
		$insert_id = $this->db->insert_id();
		if ($insert_id) {

			$num 		 = $insert_id % 100000;
			$yr 		 = date('Y/m');
			$digit 		 = sprintf("%1$06d", $num);
			$ledger_code = $yr . '/' . $digit;
			$this->db->query("update general_ledger set gl_code = '$ledger_code' where account_id = $insert_id");

			$user_se_id  	= $this->session->userdata('session_id');
			$uid 			= $this->session->userdata('user_id');
			$page_name 		= explode('index.php/', $_SERVER['PHP_SELF']);
			$ci 			= get_instance();
			$ci->load->helper('log');
			$log_msg 		= add_log_entry($uid, 1, $page_name[1], 'general_ledger', 'account_id', $insert_id);
			return $flag;
		}
	}

	function update_general_ledger()
	{ //in use
		$account_id = $this->input->post('account_id');

		$data = array(
			'opening_balance' => $this->input->post('opening_bal'),
			'opening_bal_type' => $this->input->post('dr_cr_type'),

		);
		$this->db->where('account_id', $account_id);
		$this->db->update('general_ledger', $data);
		if ($account_id) {
			$user_se_id = $this->session->userdata('session_id');
			$uid = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($uid, 2, $page_name[1], 'general_ledger', 'account_id', $account_id);
			return $account_id;
		}
	}
	function delete_ledger($id)
	{
		$this->db->where('account_id', $id);
		$this->db->delete('general_ledger');

		$user_se_id = $this->session->userdata('session_id');
		$uid = $this->session->userdata('user_id');
		$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
		$ci = get_instance();
		$ci->load->helper('log');
		$log_msg = add_log_entry($uid, 3, $page_name[1], 'general_ledger', 'account_id', $id);
		return true;
	}
	function get_customer_account_id($customer_id)
	{
		$query = $this->db->query("select account_id from general_ledger where customer_id = '$customer_id'");
		return $query->row('account_id');
	}
	function get_account_id_using_name($name)
	{ // from general ledger Accounts Name (TDS, security deposite)
		$query = $this->db->query("select account_id from general_ledger where account_name like '%$name%' and customer_id is null ");
		return $query->row('account_id');
	}
	function get_account_code_count($code, $voucher_type)
	{
		$l1 = strlen($code);
		$query = $this->db->query("select coalesce(MAX(SUBSTR(voucher_code,$l1+1,5)),0)as count from voucher_transaction where voucher_code like '$code%' and voucher_type='$voucher_type' ");
		return $query->row('count');
	}
	/*--------------------------- General leadger Ends ---------------------------------*/


	//////////////////////////////////////  Contra Entry Start/////////////////////////////
	function add_contra_entry()
	{
		$code_prifix = "PVF/N/" . date('y') . "/";
		$this->load->model('Accounts_model');
		$num = ($this->Accounts_model->get_account_code_count($code_prifix, 'N')) + 1;
		$digit = sprintf("%1$05d", $num);
		$AccountCode = $code_prifix . $digit;

		$vdate = $this->input->post('v_date');
		$vtime = $this->input->post('vtime');

		/// debit entry 
		for ($i = 0; $i < count($_POST['debtor']); $i++) {
			$debtor = $_POST['debtor'][$i];
			$dr_amount = $_POST['dr_amount'][$i];

			$data = array(
				'voucher_code' 		=> $AccountCode,
				'voucher_date' 		=> date('Y-m-d h:i:s', strtotime("$vdate $vtime")),
				'voucher_type' 		=> 'N',
				/// N stands for contra entry
				//'customer_id' => $cust_id,
				'account_id' 		=> $debtor,
				'amount' 			=> $dr_amount,
				'drcr_type' 		=> 'Dr',
				'narration' 		=> $this->input->post('narration'),
				'trans_type' 		=> 'N',
				'recordCreatedBy'  => $this->session->userdata('user_id'),
                'branch_id' => $this->input->post('branch_id')

			);
			$this->db->insert('voucher_transaction', $data);
			$insert_id = $this->db->insert_id();
		}

		// credit entry
		for ($i = 0; $i < count($_POST['creditor']); $i++) {
			$creditor = $_POST['creditor'][$i];
			$cr_amount = $_POST['cr_amount'][$i];

			$data = array(
				'voucher_code' 	 => $AccountCode,
				'voucher_date'	 => date('Y-m-d h:i:s', strtotime("$vdate $vtime")),
				'voucher_type' 	 => 'N',
				/// N stands for contra entry
				//'customer_id' => $cust_id,
				'account_id' 	=> $creditor,
				'amount' 		=> $cr_amount,
				'drcr_type'  	=> 'Cr',
				'narration' 	=> $this->input->post('narration'),
				'trans_type' 	=> 'N',
				'recordCreatedBy' => $this->session->userdata('user_id'),
                'branch_id' => $this->input->post('branch_id')

			);
			$this->db->insert('voucher_transaction', $data);
			$insert_id = $this->db->insert_id();
		}

		if ($insert_id) {
			$user_se_id 	= $this->session->userdata('session_id');
			$uid 			= $this->session->userdata('user_id');
			$page_name 		= explode('index.php/', $_SERVER['PHP_SELF']);
			$ci 			= get_instance();
			$ci->load->helper('log');
			$log_msg 		= add_log_entry($uid, 1, $page_name[1], 'voucher_transaction', 'voucher_id', $insert_id);
			return $insert_id;
		}
	}


	function get_contra_entry_records($from, $to)
	{ //in use
		$from_date 		= date('Y-01-01', strtotime($from));
		$to_date 		= date('Y-12-31', strtotime($to));

		$query 			= $this->db->query("select * from  voucher_transaction where voucher_type='N' and date(voucher_date) between '$from_date' and '$to_date' group by voucher_code order by date(voucher_date) desc, voucher_code desc");
		return $query->result();
	}
	////////////////////////////////////// Contra Entry End//////////////////////////////////

	////////////////////////////////////// Journal Start/////////////////////////////
	function add_journal()
	{
		$this->load->model('Accounts_model');
		$code_prifix 		= "PVF/J/" . date('y') . "/";		
		$num 				= ($this->Accounts_model->get_account_code_count($code_prifix, 'J')) + 1;
		$digit 				= sprintf("%1$05d", $num);
		$AccountCode 		= $code_prifix . $digit;

		$vdate 				= $this->input->post('v_date');
		$vtime 				= $this->input->post('vtime');

		/// debit entry 
		for ($i = 0; $i < count($_POST['debtor']); $i++) {
			$debtor 		= $_POST['debtor'][$i];
			$dr_amount 		= $_POST['dr_amount'][$i];


			$data = array(
				'voucher_code' 		=> $AccountCode,
				'voucher_date' 		=> date('Y-m-d h:i:s', strtotime("$vdate $vtime")),
				'voucher_type' 		=> 'J',
				/// J stands for Journal
				//'customer_id' => $cust_id,
				'account_id' 		=> $debtor,
				'amount' 			=> $dr_amount,
				'drcr_type' 		=> 'Dr',
				'narration'			=> $this->input->post('narration'),
				'trans_type' 		=> 'J',
				'recordCreatedBy' 	=> $this->session->userdata('user_id'),
                'branch_id' => $this->input->post('branch_id')

			);
			$this->db->insert('voucher_transaction', $data);
			$insert_id = $this->db->insert_id();
		}

		// credit entry
		for ($i = 0; $i < count($_POST['creditor']); $i++) {
			$creditor = $_POST['creditor'][$i];
			$cr_amount = $_POST['cr_amount'][$i];

			$data = array(
				'voucher_code' 		=> $AccountCode,
				'voucher_date' 		=> date('Y-m-d h:i:s', strtotime("$vdate $vtime")),
				'voucher_type' 		=> 'J',
				/// J stands for Journal
				//'customer_id' => $cust_id,
				'account_id' 		=> $creditor,
				'amount' 			=> $cr_amount,
				'drcr_type' 		=> 'Cr',
				'narration' 		=> $this->input->post('narration'),
				'trans_type' 		=> 'J',
				'recordCreatedBy'   => $this->session->userdata('user_id'),
                'branch_id' => $this->input->post('branch_id')

			);
			$this->db->insert('voucher_transaction', $data);
			$insert_id = $this->db->insert_id();
		}

		if ($insert_id) {
			$user_se_id 		= $this->session->userdata('session_id');
			$uid 				= $this->session->userdata('user_id');
			$page_name 			= explode('index.php/', $_SERVER['PHP_SELF']);
			$ci 				= get_instance();
			$ci->load->helper('log');
			$log_msg 			= add_log_entry($uid, 1, $page_name[1], 'voucher_transaction', 'voucher_id', $insert_id);
			return $insert_id;
		}
	}


	function get_journal_records($from, $to)
	{ //in use
		$from_date = date('Y-01-01', strtotime($from));
		$to_date = date('Y-12-31', strtotime($to));

		$query = $this->db->query("select * from  voucher_transaction where voucher_type='J' and date(voucher_date) between '$from_date' and '$to_date' group by voucher_code order by date(voucher_date) desc, voucher_code desc");
		return $query->result();
	}
	//////////////////////////////////////journal End//////////////////////////////////

	//////////////////////////////////////Debit Note Start/////////////////////////////

	function add_debit_note()
	{
		$this->load->model('Accounts_model');
		$code_prifix 		= "PVF/D/" . date('y') . "/";
		
		$num 				= ($this->Accounts_model->get_account_code_count($code_prifix, 'D')) + 1;
		$digit				= sprintf("%1$05d", $num);
		$AccountCode 		= $code_prifix . $digit;

		$vdate 				= $this->input->post('v_date');
		$vtime 				= $this->input->post('vtime');

		/// debit entry 
		for ($i = 0; $i < count($_POST['debtor']); $i++) {
			$debtor 		= $_POST['debtor'][$i];
			$dr_amount 		= $_POST['dr_amount'][$i];

			$data = array(
				'voucher_code' 		=> $AccountCode,
				'voucher_date' 		=> date('Y-m-d h:i:s', strtotime("$vdate $vtime")),
				'voucher_type' 		=> 'D',
				/// C stands for debit note
				//'customer_id' => $cust_id,
				'account_id' 		=> $debtor,
				'amount' 			=> $dr_amount,
				'drcr_type' 		=> 'Dr',
				'narration' 		=> $this->input->post('narration'),
				'trans_type' 		=> 'D',
				'recordCreatedBy' 	=> $this->session->userdata('user_id'),
                'branch_id' => $this->input->post('branch_id')

			);
			$this->db->insert('voucher_transaction', $data);
			$insert_id = $this->db->insert_id();
		}

		// credit entry
		for ($i = 0; $i < count($_POST['creditor']); $i++) {
			$creditor 			= $_POST['creditor'][$i];
			$cr_amount 			= $_POST['cr_amount'][$i];

			$data = array(
				'voucher_code' 		=> $AccountCode,
				'voucher_date' 		=> date('Y-m-d h:i:s', strtotime("$vdate $vtime")),
				'voucher_type' 		=> 'D',
				/// C stands for debit note
				//'customer_id' => $cust_id,
				'account_id' 		=> $creditor,
				'amount' 			=> $cr_amount,
				'drcr_type' 		=> 'Cr',
				'narration' 		=> $this->input->post('narration'),
				'trans_type' 		=> 'D',
				'recordCreatedBy'	=> $this->session->userdata('user_id'),
                'branch_id' => $this->input->post('branch_id')

			);
			$this->db->insert('voucher_transaction', $data);
			$insert_id = $this->db->insert_id();
		}

		if ($insert_id) {
			$user_se_id 		= $this->session->userdata('session_id');
			$uid 				= $this->session->userdata('user_id');
			$page_name 			= explode('index.php/', $_SERVER['PHP_SELF']);
			$ci 				= get_instance();
			$ci->load->helper('log');
			$log_msg 			= add_log_entry($uid, 1, $page_name[1], 'voucher_transaction', 'voucher_id', $insert_id);
			return $insert_id;
		}
	}


	function get_debit_note_records($from, $to)
	{ //in use
		$from_date = date('Y-01-01', strtotime($from));
		$to_date = date('Y-12-31', strtotime($to));

		$query = $this->db->query("select * from  voucher_transaction where voucher_type='D' and date(voucher_date) between '$from_date' and '$to_date' group by voucher_code order by date(voucher_date) desc, voucher_code desc");
		return $query->result();
	}
	//////////////////////////////////////Debit Note End//////////////////////////////////

	//////////////////////////////////////Credit Note Start/////////////////////////////////

	function add_credit_note()
	{
		$code_prifix = "PVF/C/" . date('y') . "/";
		$this->load->model('Accounts_model');
		$num = ($this->Accounts_model->get_account_code_count($code_prifix, 'C')) + 1;
		$digit = sprintf("%1$05d", $num);
		$AccountCode = $code_prifix . $digit;

		$vdate = $this->input->post('v_date');
		$vtime = $this->input->post('vtime');

		/// debit entry 
		for ($i = 0; $i < count($_POST['debtor']); $i++) {
			$debtor = $_POST['debtor'][$i];
			$dr_amount = $_POST['dr_amount'][$i];

			$data = array(
				'voucher_code' => $AccountCode,
				'voucher_date' => date('Y-m-d h:i:s', strtotime("$vdate $vtime")),
				'voucher_type' => 'C',
				/// C stands for Credit note
				//'customer_id' => $cust_id,
				'account_id' => $debtor,
				'amount' => $dr_amount,
				'drcr_type' => 'Dr',
				'narration' => $this->input->post('narration'),
				'trans_type' => 'C',
				'recordCreatedBy' => $this->session->userdata('user_id'),
                'branch_id' => $this->input->post('branch_id')

			);
			$this->db->insert('voucher_transaction', $data);
			$insert_id = $this->db->insert_id();
		}

		// credit entry
		for ($i = 0; $i < count($_POST['creditor']); $i++) {
			$creditor = $_POST['creditor'][$i];
			$cr_amount = $_POST['cr_amount'][$i];

			$data = array(
				'voucher_code' => $AccountCode,
				'voucher_date' => date('Y-m-d h:i:s', strtotime("$vdate $vtime")),
				'voucher_type' => 'C',
				/// C stands for Credit note
				//'customer_id' => $cust_id,
				'account_id' => $creditor,
				'amount' => $cr_amount,
				'drcr_type' => 'Cr',
				'narration' => $this->input->post('narration'),
				'trans_type' => 'C',
				'recordCreatedBy' => $this->session->userdata('user_id'),
                'branch_id' => $this->input->post('branch_id')
			);
			$this->db->insert('voucher_transaction', $data);
			$insert_id = $this->db->insert_id();
		}

		if ($insert_id) {
			$user_se_id = $this->session->userdata('session_id');
			$uid = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($uid, 1, $page_name[1], 'voucher_transaction', 'voucher_id', $insert_id);
			return $insert_id;
		}
	}

	function get_credit_note_records($from, $to)
	{
		$from_date = date('Y-01-01', strtotime($from));
		$to_date = date('Y-12-31', strtotime($to));

		$query = $this->db->query("select * from  voucher_transaction where voucher_type='C' and date(voucher_date) between '$from_date' and '$to_date' group by voucher_code order by date(voucher_date) desc, voucher_code desc");
		return $query->result();
	}
	///////////////////////////////////////Credit note End////////////////////////////////////////


public function add_new_receipt()
{
	$this->load->model('Accounts_model');
    $code_prefix 			= "BES/R/" . date('y') . "/";    
    $num 					= $this->Accounts_model->get_account_code_count($code_prefix, 'R') + 1;
    $voucher_code   		= $code_prefix . sprintf("%05d", $num);

    // Get posted data safely
    $vdate 					= $this->input->post('v_date');
    $vtime 					= $this->input->post('vtime');
    $cust_id 				= $this->input->post('customer_id');
    $debtor_account_id 		= $this->input->post('debtor'); 
    $narration 				= $this->input->post('narration');
    $transaction_type 		= $this->input->post('transaction_type');
    $transaction_no 		= $this->input->post('transaction_no');
    $user_id 				= $this->session->userdata('user_id');

    $invoiceIDs 			= $this->input->post('invoiceID');          
    $dr_amounts 			= $this->input->post('dr_amount');          
    $invoice_codes 			= $this->input->post('invoice_code');  
	$prepared_by         	= $this->input->post('employee_prepared');
	$approved_by         	= $this->input->post('employee_approved');  
    $branch_id         	= $this->input->post('branch_id');  


    // Convert date & time to MySQL datetime format
    $voucher_datetime 		= date('Y-m-d H:i:s', strtotime("$vdate $vtime"));

    // =========================
    // Insert Credit entries - one per invoice
    // =========================
    if (!empty($invoiceIDs) && is_array($invoiceIDs)) {
        foreach ($invoiceIDs as $inv_id) {
            $dr_amount = isset($dr_amounts[$inv_id]) ? (float)$dr_amounts[$inv_id] : 0;
            $invoice_code = isset($invoice_codes[$inv_id]) ? $invoice_codes[$inv_id] : '';

            if ($dr_amount > 0) {
                $data_cr = array(
                    'voucher_code'     => $voucher_code,
                    'voucher_date'     => $voucher_datetime,
                    'voucher_type'     => 'R',
                    'customer_id'      => $cust_id,
                    'account_id'       => $debtor_account_id,
                    'amount'           => $dr_amount,
                    'drcr_type'        => 'Cr',
                    'narration'        => $narration,
                    'trans_id'         => $inv_id,
                    'invoice_code'     => $invoice_code,
                    'invoice_amount'   => $dr_amount,
                    'trans_type'       => 'R',
                    'transaction_type' => $transaction_type,
                    'transaction_no'   => $transaction_no,
                    'recordCreatedBy'  => $user_id,
					'prepared_by'      => $prepared_by,
					'approved_by'      => $approved_by,
                    'branch_id'   => $branch_id
                );

                $this->db->insert('voucher_transaction', $data_cr);

                // Update invoice_master paid amount safely
                $this->db->set('paid_amt', 'paid_amt + ' . $dr_amount, false);
                $this->db->where('invoice_id', $inv_id);
                $this->db->update('invoice_master');
            }
        }
    }

    // =========================
    // Insert Debit entries - payment accounts (bank/cash)
    // =========================
    $creditors 				= $this->input->post('creditor');      // array of account_ids for payment
    $cr_amounts 			= $this->input->post('cr_amount');    // array of amounts for payment

    if (!empty($creditors) && is_array($creditors)) {
        foreach ($creditors as $key => $creditor_account_id) {
            $cr_amount = isset($cr_amounts[$key]) ? (float)$cr_amounts[$key] : 0;

            if ($cr_amount > 0) {
                $data_dr = array(
                    'voucher_code'     => $voucher_code,
                    'voucher_date'     => $voucher_datetime,
                    'voucher_type'     => 'R',
                    'customer_id'      => $cust_id,
                    'account_id'       => $creditor_account_id,
                    'amount'           => $cr_amount,
                    'drcr_type'        => 'Dr',
                    'narration'        => $narration,
                    'trans_id'         => NULL,
                    'trans_type'       => 'R',
                    'transaction_type' => $transaction_type,
                    'transaction_no'   => $transaction_no,
                    'recordCreatedBy'  => $user_id,
					'prepared_by'      => $prepared_by,
					'approved_by'      => $approved_by,
                    'branch_id'   => $branch_id
                );

                $this->db->insert('voucher_transaction', $data_dr);
            }
        }
    }

    return $voucher_code;
}
	function get_receipt_list($from, $to)
	{
		$from_date = date('Y-01-01', strtotime($from));
		$to_date = date('Y-12-31', strtotime($to));

		$query = $this->db->query("select * from  voucher_transaction where voucher_type='R' and date(voucher_date) between '$from_date' and '$to_date' group by voucher_code order by date(voucher_date) desc, voucher_code desc");
		return $query->result();
	}
	//////////////////////////////////////////////////////////

	//////////// Add new Payments ///////////////////
	// function add_new_payment() //in use
	// {
	// 	// receipt entry Cr to customer dr to bank/cash

	// 	$code_prifix = "PVF/P/" . date('y') . "/";
	// 	$this->load->model('Accounts_model');
	// 	$num = ($this->Accounts_model->get_account_code_count($code_prifix, 'P')) + 1;
	// 	$digit = sprintf("%1$05d", $num);
	// 	$AccountCode = $code_prifix . $digit;

	// 	$vdate = $this->input->post('v_date');
	// 	$vtime = $this->input->post('vtime');
	// 	$inv_id = $this->input->post('invoice_id');
	// 	/// debit entry 
	// 	for ($i = 0; $i < count($_POST['debtor']); $i++) {
	// 		$debtor = $_POST['debtor'][$i];
	// 		$dr_amount = $_POST['dr_amount'][$i];

	// 		$data = array(
	// 			'voucher_code' => $AccountCode,
	// 			'voucher_date' => date('Y-m-d h:i:s', strtotime("$vdate $vtime")),
	// 			'voucher_type' => 'P',
	// 			/// P stands for Payment
	// 			//'customer_id' => $cust_id,
	// 			'account_id' => $debtor,
	// 			'amount' => $dr_amount,
	// 			'drcr_type' => 'Dr',
	// 			'narration' => $this->input->post('narration'),
	// 			'trans_type' => 'P',
	// 			'trans_id' => $inv_id,
	// 			'recordCreatedBy' => $this->session->userdata('user_id')
	// 		);
	// 		$this->db->insert('voucher_transaction', $data);
	// 		$insert_id = $this->db->insert_id();
	// 	}

	// 	// credit entry
	// 	for ($i = 0; $i < count($_POST['creditor']); $i++) {
	// 		$creditor = $_POST['creditor'][$i];
	// 		$cr_amount = $_POST['cr_amount'][$i];

	// 		$data = array(
	// 			'voucher_code' => $AccountCode,
	// 			'voucher_date' => date('Y-m-d h:i:s', strtotime("$vdate $vtime")),
	// 			'voucher_type' => 'P',
	// 			/// P stands for Payment
	// 			//'customer_id' => $cust_id,
	// 			'account_id' => $creditor,
	// 			'amount' => $cr_amount,
	// 			'drcr_type' => 'Cr',
	// 			'narration' => $this->input->post('narration'),
	// 			'trans_type' => 'P',
	// 			'trans_id' => $inv_id,
	// 			'recordCreatedBy' => $this->session->userdata('user_id')
	// 		);
	// 		$this->db->insert('voucher_transaction', $data);
	// 		$insert_id = $this->db->insert_id();
	// 	}

	// 	if ($insert_id) {
	// 		$user_se_id = $this->session->userdata('session_id');
	// 		$uid = $this->session->userdata('user_id');
	// 		$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
	// 		$ci = get_instance();
	// 		$ci->load->helper('log');
	// 		$log_msg = add_log_entry($uid, 1, $page_name[1], 'voucher_transaction', 'voucher_id', $insert_id);
	// 		return $insert_id;
	// 	}

	// }


	public function add_new_payment_data()
{
    $code_prifix = "BES/P/" . date('y') . "/";
    $this->load->model('Accounts_model');
    
    $num = ($this->Accounts_model->get_account_code_count($code_prifix, 'P')) + 1;
    $digit = sprintf("%05d", $num);
    $AccountCode = $code_prifix . $digit;

    $vdate = $this->input->post('v_date');
    $vtime = $this->input->post('vtime');
    $supplier_id = $this->input->post('debtor');  // Your select supplier input name is "debtor"
    $transaction_type = $this->input->post('transaction_type');
    $transaction_no = $this->input->post('transaction_no');
    $narration = $this->input->post('narration');

    $invoiceIDs = $this->input->post('invoiceID');       // Array of selected invoice IDs
    $dr_amounts = $this->input->post('dr_amount');       // Associative array: invoiceID => amount
    $grn_codes = $this->input->post('grn_code');         // Associative array: invoiceID => invoice code

    // Compose voucher datetime
    $voucher_datetime = date('Y-m-d H:i:s', strtotime("$vdate $vtime"));

	$prepared_by         	= $this->input->post('employee_prepared');
	$approved_by         	= $this->input->post('employee_approved');
    $branch_id         	= $this->input->post('branch_id');
    $currency_id         	= $this->input->post('currency_id');


    // Insert debit entries (payment details for invoices)
    if (!empty($invoiceIDs) && is_array($invoiceIDs)) {
        foreach ($invoiceIDs as $inv_id) {
            $dr_amount = isset($dr_amounts[$inv_id]) ? $dr_amounts[$inv_id] : 0;
            $invoice_code = isset($grn_codes[$inv_id]) ? $grn_codes[$inv_id] : '';

            if ($dr_amount <= 0) {
                // Skip zero or negative amounts
                continue;
            }

            $data = [
                'voucher_code'      => $AccountCode,
                'voucher_date'      => $voucher_datetime,
                'voucher_type'      => 'P',
                'customer_id'       => $supplier_id,
                'account_id'        => $supplier_id,    // Assuming debtor is the account here; adjust if needed
                'amount'            => $dr_amount,
                'drcr_type'         => 'Dr',
                'narration'         => $narration,
                'trans_id'          => $inv_id,
                'invoice_code'      => $invoice_code,
                'invoice_amount'    => $dr_amount,
                'trans_type'        => 'P',
                'transaction_type'  => $transaction_type,
                'transaction_no'    => $transaction_no,
                'recordCreatedBy'   => $this->session->userdata('user_id'),
				'prepared_by'       => $prepared_by,
				'approved_by'       => $approved_by,
                'branch_id'			=> $branch_id,
                'currency_id'       => $currency_id
            ];

            $this->db->insert('voucher_transaction', $data);

            // Update paid amount in purchase_grn_master by adding current payment
            $this->db->set('paid_amt', 'paid_amt + ' . (float)$dr_amount, FALSE)
                     ->where('grn_id', $inv_id)
                     ->update('purchase_grn_master');
        }
    }

    // Insert credit entries (creditors/payment source)
    $creditors = $this->input->post('creditor');
    $cr_amounts = $this->input->post('cr_amount');

    if (!empty($creditors) && is_array($creditors)) {
        foreach ($creditors as $key => $creditor_id) {
            $cr_amount = isset($cr_amounts[$key]) ? $cr_amounts[$key] : 0;

            if ($cr_amount <= 0) {
                continue;
            }

            $data = [
                'voucher_code'      => $AccountCode,
                'voucher_date'      => $voucher_datetime,
                'voucher_type'      => 'P',
                'customer_id'       => $supplier_id,
                'account_id'        => $creditor_id,
                'amount'            => $cr_amount,
                'drcr_type'         => 'Cr',
                'narration'         => $narration,
                'trans_id'          => 0,
                'invoice_code'      => '',
                'invoice_amount'    => 0,
                'trans_type'        => 'P',
                'transaction_type'  => $transaction_type,
                'transaction_no'    => $transaction_no,
                'recordCreatedBy'   => $this->session->userdata('user_id'),
				'prepared_by'       => $prepared_by,
				'approved_by'       => $approved_by,
                'branch_id'			=> $branch_id,
                'currency_id'       => $currency_id		
            ];

            $this->db->insert('voucher_transaction', $data);
        }
    }

    $insert_id = $this->db->insert_id();

    if ($insert_id) {
        $user_id = $this->session->userdata('user_id');
        $page_name = explode('index.php/', $_SERVER['PHP_SELF']);
        $ci = get_instance();
        $ci->load->helper('log');
        add_log_entry($user_id, 1, $page_name[1], 'voucher_transaction', 'voucher_id', $insert_id);

        return $insert_id;
    }

    return false;
}


	function add_new_payment_data1() //in use
	{

		$code_prifix = "BES/P/" . date('y') . "/";
		$this->load->model('Accounts_model');
		$num = ($this->Accounts_model->get_account_code_count($code_prifix, 'P')) + 1;
		$digit = sprintf("%1$05d", $num);
		$AccountCode = $code_prifix . $digit;


		$vdate = $this->input->post('v_date');
		$vtime = $this->input->post('vtime');
		$supplier_id = $this->input->post('supplier_id');
		$transaction_type = $this->input->post('transaction_type');
        $transaction_no   = $this->input->post('transaction_no');
		/// debit entry 
		for ($i = 0; $i < count($_POST['inv_id']); $i++) 
		{

			$inv_id = $_POST['inv_id'][$i];
			$dr_amount =  $this->input->post("dr_amount$inv_id");
			$data = array(
				'voucher_code' => $AccountCode,
				'voucher_date' => date('Y-m-d h:i:s', strtotime("$vdate $vtime")),
				'voucher_type' => 'P',
				/// R stands for Receipt
				'customer_id' => $supplier_id,
				'account_id' => $this->input->post('debtor'),
				'amount' => $dr_amount,
				'drcr_type' => 'Dr',
				'narration' => $this->input->post('narration'),
				'trans_id' => $inv_id,
				'trans_type' => 'P',
				'transaction_type' => $transaction_type,
                                'transaction_no'   => $transaction_no,
				'recordCreatedBy' => $this->session->userdata('user_id')
			);
		
			$this->db->insert('voucher_transaction', $data);
			$insert_id = $this->db->insert_id();
			$this->db->query("update purchase_grn_master set paid_amt=$dr_amount where grn_id=$inv_id");

		}

		// debit entry
		for ($i = 0; $i < count($_POST['creditor']); $i++) {
			$creditor = $_POST['creditor'][$i];
			$cr_amount = $_POST['cr_amount'][$i];

			$data = array(
				'voucher_code' => $AccountCode,
				'voucher_date' => date('Y-m-d h:i:s', strtotime("$vdate $vtime")),
				'voucher_type' => 'P',
				/// R stands for Receipt
				'customer_id' => $supplier_id,
				'account_id' => $creditor,
				'amount' => $cr_amount,
				'drcr_type' => 'Cr',
				'narration' => $this->input->post('narration'),
				'trans_id' => $inv_id,
				'trans_type' => 'P',
				'transaction_type' => $transaction_type,
				'transaction_no'   => $transaction_no,

				'recordCreatedBy' => $this->session->userdata('user_id')
			);
								//echo "Callin her2"; exit;

			$this->db->insert('voucher_transaction', $data);
			$insert_id = $this->db->insert_id();
		}

		if ($insert_id) {
			$user_se_id = $this->session->userdata('session_id');
			$uid = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($uid, 1, $page_name[1], 'voucher_transaction', 'voucher_id', $insert_id);
			return $insert_id;
		}


	}



	function get_payment_list($from, $to)
	{
		$from_date = date('Y-01-01', strtotime($from));
		$to_date = date('Y-12-31', strtotime($to));

		$query = $this->db->query("select * from  voucher_transaction where voucher_type='P' and date(voucher_date) between '$from_date' and '$to_date' group by voucher_code order by date(voucher_date) desc, voucher_code desc");
		return $query->result();
	}


	function delete_trans_entry($voucher_code)
	{
		$query = $this->db->query("update voucher_transaction set cancel=1 where voucher_code='$voucher_code' ");
		return true;
	}

	//////////////////////////////////////////////////////////

	function get_customer_id_using_accountid($account_id)
	{
		$query = $this->db->query("select if(customer_id ,customer_id, supplier_id) as customer_id from general_ledger where account_id = '$account_id'");
		return $query->row('customer_id');
	}

	function update_transaction_details()
	{
		for ($i = 0; $i < count($_POST['amount']); $i++) {
			$amount = $_POST['amount'][$i];
			$voucher_id = $_POST['voucher_id'][$i];

			$data = array(
				'voucher_date' => date('Y-m-d', strtotime($this->input->post('v_date'))),
				'amount' => $amount,
				'narration' => $this->input->post('narration'),
			);
			$this->db->where('voucher_id', $voucher_id);
			$this->db->update('voucher_transaction', $data);
		}
		return true;
	}
	function get_acc_details()
	{
		$type = $this->input->post('type');

		$query = $this->db->query("select one.id, concat(coalesce(two.cust_code,''),' ',one.value)as value from (select gl.account_id as id, gl.gl_code, gl.account_name as value, gl.group_no, gl.customer_id, gl.supplier_id, gl.isdeleteable, gl.opening_balance, gl.opening_bal_type, gl.date, ag.group_name from general_ledger gl, account_group ag  where gl.group_no=ag.group_no and ag.group_no='$type') as one left join (select * from customer_master) as two on(one.customer_id=two.customer_id)");
		return $query->result();
	}

	// function get_ledger_report($acc_id, $from_date, $to_date)
	// {

	// 	if (empty($acc_id) || empty($from_date) || empty($to_date)) {
    //     return [];
    //     }
	// 	$from_date = date('Y-m-d', strtotime($from_date));
	// 	$to_date = date('Y-m-d', strtotime($to_date));
	// 	$condition = "";

	// 	$query = $this->db->query("select customer_id, supplier_id, group_no from general_ledger where account_id=$acc_id ");
	// 	$res = $query->result();

	// 	foreach ($res as $r) {
	// 		if ($r->group_no == 29) //sundry creditors
	// 		{
	// 			$query = $this->db->query("select one.*, invoice_no as ref_no, invoice_date as invoice_date, po_code as po_code  from(select v.*,g. account_name,g.opening_balance from general_ledger g,voucher_transaction v where g.account_id=$acc_id and v.account_id=g.account_id and date(v.voucher_date) between '$from_date' and '$to_date' and v.cancel=0 $condition order by v.voucher_date, voucher_id)as one  left join(select g.invoice_no,grn_id,g.invoice_date, o.po_code from grn_master g, purchase_order o where g.po_id=o.po_id)as two on(one.trans_id=two.grn_id) ");
	// 		} else if ($r->group_no == 30) //sundry debitors
	// 		{
	// 			$query = $this->db->query("select one.*, three.invoice_code as ref_no, three.invoice_date as invoice_date, three.po_number as po_code from(select v.*,g. account_name,g.opening_balance from general_ledger g,voucher_transaction v where g.account_id=$acc_id and v.account_id=g.account_id and date(v.voucher_date) between '$from_date' and '$to_date' and v.cancel=0 $condition order by v.voucher_date, voucher_id)as one left join(select * from invoice_master)as three on(one.trans_id=three.invoice_id)");
	// 		} else //others
	// 		{
	// 			$query = $this->db->query("select one.*, if(one.voucher_type='G',two.invoice_no,three.invoice_code)as ref_no, if(one.voucher_type='G',two.invoice_date,three.invoice_date)as invoice_date, if(one.voucher_type='G',two.po_code, three.po_number)as po_code from(select v.*,g. account_name,g.opening_balance from general_ledger g,voucher_transaction v where g.account_id=$acc_id and v.account_id=g.account_id and date(v.voucher_date) between '$from_date' and '$to_date' and v.cancel=0 $condition order by v.voucher_date, voucher_id)as one  left join(select g.invoice_no,grn_id,g.invoice_date, o.po_code from grn_master g, purchase_order o where g.po_id=o.po_id)as two on(one.trans_id=two.grn_id) left join(select * from invoice_master)as three on(one.trans_id=three.invoice_id)");
	// 		}
	// 	}
	// 	return $query->result();

	// }


	function get_ledger_report($acc_id, $from_date, $to_date, $branch_id = '')

{
    if (empty($acc_id) || empty($from_date) || empty($to_date)) {
        return [];
    }

    $from_date = date('Y-m-d', strtotime($from_date));
    $to_date = date('Y-m-d', strtotime($to_date));
    $condition = "";

     // Branch Filter
    if (!empty($branch_id)) {
        $condition .= " AND v.branch_id = ".$this->db->escape($branch_id);
    }

    // Get group info safely
    $query = $this->db->query("SELECT customer_id, supplier_id, group_no FROM general_ledger WHERE account_id = ?", [$acc_id]);
    $res = $query->result();
    if (empty($res)) {
        return [];
    }

    $group_no = $res[0]->group_no;

    // Common base SQL
    $base_sql = "
        SELECT v.*, g.account_name, g.opening_balance
        FROM general_ledger g
        JOIN voucher_transaction v ON v.account_id = g.account_id
        WHERE v.account_id = ?
        AND DATE(v.voucher_date) BETWEEN ? AND ?
        AND v.cancel = 0
        $condition
        ORDER BY v.voucher_date, voucher_id
    ";

    if ($group_no == 29) {
        // Sundry Creditors
        $query = $this->db->query("
            SELECT one.*, '' as  ref_no, two.invoice_date, two.po_code
            FROM ($base_sql) AS one
            LEFT JOIN (
                SELECT  g.grn_id, g.invoice_date, o.po_code
                FROM purchase_grn_master g
                JOIN purchase_order_master o ON g.po_id = o.po_id
            ) AS two ON one.trans_id = two.grn_id
        ", [$acc_id, $from_date, $to_date]);

    } elseif ($group_no == 30) {
        // Sundry Debtors
        $query = $this->db->query("
            SELECT one.*, three.invoice_code AS ref_no, three.invoice_date, three.po_number AS po_code
            FROM ($base_sql) AS one
            LEFT JOIN invoice_master AS three ON one.trans_id = three.invoice_id
        ", [$acc_id, $from_date, $to_date]);

    } else {
        // Others
        $query = $this->db->query("
            SELECT one.*, 
                   IF(one.voucher_type = 'G', two.invoice_no, three.invoice_code) AS ref_no,
                   IF(one.voucher_type = 'G', two.invoice_date, three.invoice_date) AS invoice_date,
                   IF(one.voucher_type = 'G', two.po_code, three.po_number) AS po_code
            FROM ($base_sql) AS one
            LEFT JOIN (
                SELECT g.invoice_no, g.grn_id, g.invoice_date, o.po_code
                FROM purchase_grn_master g
                JOIN purchase_order_master o ON g.po_id = o.po_id
            ) AS two ON one.trans_id = two.grn_id
            LEFT JOIN invoice_master AS three ON one.trans_id = three.invoice_id
        ", [$acc_id, $from_date, $to_date]);
    }

    return $query->result();
}

	function get_cust_id_from_account_id($acc_id)
	{
		$query = $this->db->query("select customer_id from general_ledger where account_id=$acc_id and group_no=30");
		return $query->row('customer_id');
	}
	function get_supp_id_from_account_id($acc_id)
	{
		if (empty($acc_id)) {
			return false; // or null, or throw an error
		}

		$query = $this->db->query("select supplier_id from general_ledger where account_id = ? and group_no = 29", array($acc_id));
		return $query->row('supplier_id');
	}



	// function get_invoice_list()
	// {

	// 	$query = $this->db->query("SELECT * FROM invoice_master i JOIN customer_master c ON i.customer_id = c.customer_id JOIN sales_quotation_master sm ON i.customer_id = sm.customer_id;");
	// 	return $query->result();
	// }

	function get_Purchase_invoice_list()
	{

		$query = $this->db->query("select grn_code, grn_id, c.supplier_id, supplier_name , grand_total  from purchase_grn_master  e, supplier_master c where e.supplier_id=c.supplier_id  order by invoice_date desc");
		return $query->result();
	}

	function get_Purchase_invoice_list_by_id($id)
	{
		//$query = $this->db->query("SELECT grn_code,grn_id,e.supplier_id,supplier_name,grand_total,COALESCE(two.amount, 0) AS amount FROM grn_master e JOIN supplier_master c ON e.supplier_id = c.supplier_id LEFT JOIN (SELECT grn_master_id,COALESCE(SUM(total), 0) AS amount FROM GRN_transaction WHERE grn_master_id = $id) AS two ON e.grn_id = two.grn_master_id WHERE grn_id = $id ORDER BY invoice_date DESC");
		$query = $this->db->query("select * from (select grand_total from grn_master where grn_id='$id')as one left join(select coalesce(sum(amount),0)as amount from voucher_transaction  where voucher_type='P' and trans_id=$id and drcr_type='Cr')as two on(1=1) ");
		return $query->result();
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function get_all_outstanding_report_accounts()
	{
		$query = $this->db->query("select * from voucher_transaction ");
		return $query->result();
	}



function get_outstanding_report($from_date, $to_date, $ledger_id = null, $branch_id = null)
{
    $params = [$from_date, $to_date];
    $ledger_condition = "";

    

    $branch_condition = "";
$params = [$from_date, $to_date];

if (!empty($ledger_id)) {
   $ledger_condition = " AND v.account_id = ? ";
    $params[] = $ledger_id;
} else {
    $ledger_condition = "";
}

if (!empty($branch_id)) {
    $branch_condition = " AND v.branch_id = ? ";
    $params[] = $branch_id;
}

   $sql = "
    SELECT
        v.account_id,
        g.account_name AS cust_name,
        v.trans_id,
        v.voucher_code,
        v.voucher_date,
        ROUND(SUM(v.amount), 2) AS sum_amt,
        ROUND(COALESCE(SUM(r.received_amt), 0), 2) AS sum_received_amt,
        ROUND(SUM(v.amount) - COALESCE(SUM(r.received_amt), 0), 2) AS sum_due_amt,
        DATEDIFF(CURDATE(), v.voucher_date) AS due_days
    FROM voucher_transaction v
    JOIN general_ledger g ON v.account_id = g.account_id

    LEFT JOIN (
        SELECT trans_id, account_id, SUM(amount) AS received_amt
        FROM voucher_transaction
        WHERE voucher_type = 'R'
        GROUP BY trans_id, account_id
    ) r ON v.trans_id = r.trans_id AND v.account_id = r.account_id

    WHERE
        g.group_no = 30
        AND v.voucher_type = 'S'
        AND v.cancel = 0
        AND DATE(v.voucher_date) BETWEEN ? AND ?
        $ledger_condition
        $branch_condition

    GROUP BY
        v.account_id, v.trans_id, v.voucher_code, v.voucher_date, g.account_name

    HAVING sum_due_amt > 0
    ORDER BY v.voucher_date ASC
";

    return $this->db->query($sql, $params)->result();
}



function get_sundry_creditors_outstanding($from_date, $to_date, $ledger_id = null, $branch_id = null)
{
    $branch_condition = "";
$params = [$from_date, $to_date];

if (!empty($ledger_id)) {
    $ledger_condition = " AND g.supplier_id = ? ";
    $params[] = $ledger_id;
} else {
    $ledger_condition = "";
}

if (!empty($branch_id)) {
    $branch_condition = " AND v.branch_id = ? ";
    $params[] = $branch_id;
}
   $sql = "
    SELECT 
        v.account_id, 
        g.account_name, 
        v.trans_id, 
        v.voucher_code, 
        v.voucher_date, 
        SUM(v.amount) AS sum_amt, 
        COALESCE(SUM(paid.paid_amt), 0) AS sum_paid_amt,  
        SUM(v.amount) - COALESCE(SUM(paid.paid_amt), 0) AS sum_due_amt, 
        DATEDIFF(CURDATE(), v.voucher_date) AS due_days
    FROM voucher_transaction v
    JOIN general_ledger g ON v.account_id = g.account_id

    LEFT JOIN (
        SELECT 
            trans_id, 
            account_id, 
            SUM(amount) AS paid_amt
        FROM voucher_transaction
        WHERE voucher_type = 'P'
        GROUP BY trans_id, account_id
    ) AS paid 
        ON v.trans_id = paid.trans_id 
       AND v.account_id = paid.account_id

    WHERE 
        g.group_no = 29
        AND v.voucher_type = 'G'
        AND v.cancel = 0
        AND DATE(v.voucher_date) BETWEEN ? AND ?
        $ledger_condition
        $branch_condition

    GROUP BY 
        v.account_id, v.trans_id, v.voucher_code, v.voucher_date, g.account_name

    HAVING sum_due_amt > 0

    ORDER BY v.voucher_date;
";

    return $this->db->query($sql, $params)->result();
}

function get_ledgers_by_group($group_no) {
    if ($group_no == 29) {
        $sql = "SELECT supplier_id AS account_id,account_name FROM general_ledger WHERE group_no = 29 ";
    } elseif ($group_no == 30) {
        $sql = " SELECT customer_id AS account_id, account_name FROM general_ledger WHERE group_no = 30 ";
    } else {
        return [];
    }

    $query = $this->db->query($sql);
    return $query->result();
}


public function get_outstanding_report111($voucher_date, $request_type)
{
    $voucher_date = date('Y-m-d', strtotime($voucher_date));

    if ($request_type === 'Sundry Debtors') {
        $group_no = 30;
        $main_voucher_type = 'S';
        $adjust_voucher_type = 'R';
    } elseif ($request_type === 'Sundry Creditors') {
        $group_no = 29;
        $main_voucher_type = 'P';
        $adjust_voucher_type = 'P';
    } else {
        return [];
    }

    $sql = "
        SELECT 
            v.account_id,
            g.account_name,
            v.trans_id,
            v.voucher_code,
            v.voucher_date,
            SUM(v.amount) AS sum_amt,
            SUM(v.amount - COALESCE(paid.received_amt, 0)) AS sum_due_amt
        FROM 
            voucher_transaction v
        JOIN 
            general_ledger g ON v.account_id = g.account_id
        LEFT JOIN 
            (
                SELECT 
                    trans_id, 
                    account_id, 
                    SUM(amount) AS received_amt
                FROM 
                    voucher_transaction
                WHERE 
                    voucher_type = ?
                GROUP BY 
                    trans_id, account_id
            ) AS paid 
            ON v.trans_id = paid.trans_id AND v.account_id = paid.account_id
        WHERE 
            g.group_no = ?
            AND v.voucher_type = ?
            AND v.cancel = 0
            AND DATE(v.voucher_date) < ?
        GROUP BY 
            v.account_id, v.trans_id
        HAVING 
            sum_due_amt > 0
        ORDER BY 
            v.voucher_date
    ";

    $query = $this->db->query($sql, [$adjust_voucher_type, $group_no, $main_voucher_type, $voucher_date]);
    return $query->result();
}


// function get_outstanding_individual_ledger($id,$from_date,$to_date)
// {
//  $sql = "
//         SELECT * FROM (
//             SELECT 
//                 one.*, 
//                 (one.amount - COALESCE(two.received_amt, 0)) AS due_amount 
//             FROM (
//                 SELECT 
//                     v.account_id, 
//                     v.trans_id, 
//                     v.voucher_code, 
//                     v.voucher_date, 
//                     v.amount, 
//                     c.cust_name 
//                 FROM 
//                     voucher_transaction v
//                 JOIN 
//                     customer_master c ON v.customer_id = c.customer_id
//                 JOIN 
//                     general_ledger g ON v.account_id = g.account_id
//                 WHERE 
//                     g.group_no = 30
//                     AND v.voucher_type = 'S'
//                     AND v.cancel = 0
//                     AND DATE(v.voucher_date) BETWEEN ? AND ?
//             ) AS one
//             LEFT JOIN (
//                 SELECT 
//                     trans_id, 
//                     account_id, 
//                     COALESCE(SUM(amount), 0) AS received_amt 
//                 FROM 
//                     voucher_transaction 
//                 WHERE 
//                     voucher_type = 'R' 
//                 GROUP BY 
//                     trans_id, account_id
//             ) AS two 
//             ON (one.trans_id = two.trans_id AND one.account_id = two.account_id)
//         ) AS three 
//         WHERE 
//             due_amount > 0 
//             AND account_id = ?
//     ";

//     $query = $this->db->query($sql, array($from_date, $to_date, $id));
//     return $query->result();
	
// }
	public function get_outstanding_individual_ledger($id, $from_date, $to_date)
{
    $sql = "
        SELECT * FROM (
            SELECT 
                one.*,
                (one.amount - COALESCE(two.received_amt, 0)) AS due_amount
            FROM (
                SELECT 
                    v.account_id,
                    v.trans_id,
                    v.voucher_code,
                    v.voucher_date,
                    v.amount,
                    g.group_no,
                    CASE 
                        WHEN g.group_no = 30 THEN c.cust_name 
                        WHEN g.group_no = 29 THEN s.supplier_name 
                        ELSE 'Unknown' 
                    END AS party_name
                FROM 
                    voucher_transaction v
                JOIN 
                    general_ledger g ON v.account_id = g.account_id
                LEFT JOIN 
                    customer_master c ON v.customer_id = c.customer_id
                LEFT JOIN 
                    supplier_master s ON v.account_id = s.supplier_id
                WHERE 
                    v.account_id = ?
                    AND v.cancel = 0
                    AND DATE(v.voucher_date) BETWEEN ? AND ?
                    AND v.voucher_type IN ('S', 'P') -- Sales for Debtors, Purchase for Creditors
            ) AS one
            LEFT JOIN (
                SELECT 
                    trans_id, account_id, SUM(amount) AS received_amt
                FROM 
                    voucher_transaction
                WHERE 
                    voucher_type = 'R' -- Receipt (you can adjust if needed)
                GROUP BY 
                    trans_id, account_id
            ) AS two
            ON (one.trans_id = two.trans_id AND one.account_id = two.account_id)
        ) AS three
        WHERE due_amount > 0
    ";

    $query = $this->db->query($sql, [$id, $from_date, $to_date]);
    return $query->result();
}


// public function get_receipt_header($voucher_code) {
//         $this->db->select('vt.*, cm.cust_name as customer_name, gl.account_name as credit_account_name');
//         $this->db->from('voucher_transaction vt');
//         $this->db->join('customer_master cm', 'cm.customer_id = vt.customer_id', 'left');
//         $this->db->join('general_ledger gl', 'gl.account_id = vt.account_id', 'left'); // Assuming account_id references credit account
//         $this->db->where('vt.voucher_code', $voucher_code);
//         $query = $this->db->get();

//         return $query->row(); 
// }
public function get_receipt_header($voucher_code)
{
    $this->db->select('
        vt.voucher_code,
        vt.voucher_date,
        vt.voucher_type,
        SUM(vt.amount) as amount,
        vt.customer_id,
        cm.customer_name as customer_name,
        vt.transaction_type,
        vt.transaction_no,
		vt.prepared_by,
		vt.approved_by,
        gl.account_name as credit_account_name,
        vt.narration,
        GROUP_CONCAT(cr.invoice_code SEPARATOR ", ") AS invoice_codes,
        GROUP_CONCAT(cr.invoice_amount SEPARATOR ", ") AS invoice_amounts
    ');
    $this->db->from('voucher_transaction vt');
    $this->db->join('customer_master cm', 'cm.customer_id = vt.customer_id', 'left');
    $this->db->join('general_ledger gl', 'gl.account_id = vt.account_id', 'left');

    // Join subquery to get Cr rows for same voucher_code
    $this->db->join("(SELECT 
                        voucher_code, 
                        invoice_code, 
                        invoice_amount 
                    FROM voucher_transaction 
                    WHERE drcr_type = 'Cr') cr", 'cr.voucher_code = vt.voucher_code', 'left');

    $this->db->where('vt.voucher_code', $voucher_code);
    $this->db->where('vt.voucher_type', 'R');
    $this->db->where('vt.drcr_type', 'Dr'); // Only fetch main Debit row for header

    $this->db->group_by('vt.voucher_code, vt.voucher_date, vt.voucher_type, vt.customer_id, cm.customer_name, vt.transaction_type, vt.transaction_no, gl.account_name, vt.narration');
 //   echo $this->db->last_query(); exit;
    return $this->db->get()->row();
}
public function get_receipt_invoices($voucher_code)
{
   $this->db->select('vt.amount, vt.voucher_code, vt.trans_id, vt.customer_id, cm.cust_name as customer_name');
    $this->db->from('voucher_transaction vt');
    $this->db->join('customer_master cm', 'cm.customer_id = vt.customer_id', 'left'); // ensure LEFT JOIN
    $this->db->where('vt.voucher_code', $voucher_code);
    $this->db->where('vt.voucher_type', 'R');
    $this->db->where('vt.drcr_type', 'Dr');
    $query = $this->db->get();
    return $query->result();
}



public function get_receipt_details($voucher_code)
{
    $this->db->select('
        vt.voucher_code,
        vt.amount,
        cm.customer_name as customer_name,
        im.invoice_code,im.invoice_date
    ');
    $this->db->from('voucher_transaction vt');
    $this->db->join('customer_master cm', 'cm.customer_id = vt.customer_id', 'left');
    $this->db->join('invoice_master im', 'im.invoice_id = vt.trans_id', 'left');
    $this->db->where('vt.voucher_code', $voucher_code);
    $this->db->where('vt.voucher_type', 'R');
    $this->db->where('vt.cancel', 0);

    return $this->db->get()->result();
}



function get_receipt_records($id){
	$query = $this->db->query("SELECT a.*, c.*, i.invoice_code FROM voucher_transaction a LEFT JOIN customer_master c ON a.customer_id = c.customer_id LEFT JOIN invoice_master i ON a.trans_id = i.invoice_id WHERE a.voucher_code = '$id'; ");
	return $query->result();
}

public function get_payment_record($voucher_code) {
    $sql = "
        SELECT 
            vt.voucher_id,
            vt.voucher_code,
            vt.voucher_type,
            vt.voucher_date,
            vt.customer_id,
            sm.supplier_name AS particulars,
            vt.amount,
            vt.transaction_type,
            vt.transaction_no,
            vt.drcr_type,
            vt.trans_id,
            vt.invoice_code,
            vt.invoice_amount,
			gm.invoice_no,
			vt.prepared_by,
			vt.approved_by,
            gl_cr.account_name AS credit_account_name,
            gl_dr.account_name AS party_name
        FROM voucher_transaction vt
        LEFT JOIN supplier_master sm ON sm.supplier_id = vt.customer_id
        LEFT JOIN general_ledger gl_cr ON vt.account_id = gl_cr.account_id
        LEFT JOIN general_ledger gl_dr ON vt.account_id = gl_dr.account_id
		LEFT JOIN purchase_grn_master gm ON vt.invoice_code = gm.grn_code
        WHERE vt.voucher_code = ? AND vt.voucher_type = 'P'
        ORDER BY vt.drcr_type DESC, vt.voucher_id
    ";

    $query = $this->db->query($sql, [$voucher_code]);
    return $query->result();
}



public function add_bank_reconciliation_details()
{
    $inv_ids     = $this->input->post('inv_id');
    $remark      = $this->input->post('remark');
    $account_id  = $this->input->post('account_id');
    $instrument_dates = $this->input->post('instrument_date');

    if (empty($inv_ids)) {
        return false;
    }

    // STEP 1: CALCULATE TOTAL AMOUNT
    $this->db->select_sum('amount');
    $this->db->where_in('voucher_id', $inv_ids);
    $query = $this->db->get('voucher_transaction');
    $row = $query->row();
    $total_amount = $row->amount ?? 0;

    // STEP 2: SAFE INSTRUMENT DATE (FIXED)
    $instrument_date = null;

    if (!empty($inv_ids) && !empty($instrument_dates)) {
        foreach ($inv_ids as $id) {
            if (!empty($instrument_dates[$id])) {
                $instrument_date = $instrument_dates[$id];
                break;
            }
        }
    }

    // STEP 3: INSERT HEADER
    $reco_data = [
        'instrument_no'   => implode(',', $inv_ids),
        'instrument_date' => $instrument_date,
        'amount_no'       => $total_amount,
        'instrument_type' => 'BANK_RECO',
        'remark'          => $remark,
        'created_by'      => $this->session->userdata('user_id'),
        'created_date'    => date('Y-m-d H:i:s'),
        'bank_account_id' => $account_id
    ];

    $this->db->insert('bank_reconciliation', $reco_data);

    // STEP 4: UPDATE VOUCHERS
    foreach ($inv_ids as $id) {
        $this->db->where('voucher_id', $id);
        $this->db->update('voucher_transaction', [
            'reco' => 1
        ]);
    }

    return true;
}
function update_bank_reconciliation_data($id)
{
	$data = array(
		'instrument_no' => $this->input->post('instrument_no'),
		'instrument_date' => date('Y-m-d', strtotime($this->input->post('date'))),
		'amount_no' => $this->input->post('amount_no'),
		'instrument_type' => $this->input->post('instrument_type'),
		'remark' => $this->input->post('remark'),
		'created_by'  => $this->session->userdata('user_id'),
		'created_date' => date('Y-m-d', strtotime($this->input->post('date'))),
		
	);

	$this->db->where('reconciliation_id', $id);
	$res = $this->db->update('bank_reconciliation', $data);


	if ($res) {
		$user_se_id = $this->session->userdata('user_id');
		$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
		$ci = get_instance();
		$ci->load->helper('log');
		$log_msg = add_log_entry($user_se_id, 2, $page_name[1], 'bank_reconciliation', 'reconciliation_id', $id);

		return true;
	} else {
		return false;
	}
}





function get_bank_reconciliation_list()
{
$query = $this->db->query("SELECT * FROM bank_reconciliation ORDER BY instrument_no DESC");
return $query->result();
}


function get_bank_reconciliation_by_id($id)
{
	$query = $this->db->query("SELECT * FROM bank_reconciliation WHERE reconciliation_id = '$id'");		
	return $query->result();
}

/////////////Balance sheet data

public function get_balance_sheet_data($from_date, $to_date, $group_no) {
        // Query to get balances grouped by account group and ledger
        // Adjust table/field names as per your DB schema
$this->db->select("
        gl.group_no,
        ag.group_name,
        gl.account_id,
        gl.account_name,
        IFNULL(SUM(CASE WHEN vt.voucher_date < '{$from_date}' THEN 
            CASE WHEN vt.drcr_type = 'Dr' THEN vt.amount ELSE -vt.amount END
        ELSE 0 END), 0) AS opening_balance,
        IFNULL(SUM(CASE WHEN vt.voucher_date BETWEEN '{$from_date}' AND '{$to_date}' AND vt.drcr_type = 'Dr' THEN vt.amount ELSE 0 END), 0) AS debit,
        IFNULL(SUM(CASE WHEN vt.voucher_date BETWEEN '{$from_date}' AND '{$to_date}' AND vt.drcr_type = 'Cr' THEN vt.amount ELSE 0 END), 0) AS credit,
        (
            IFNULL(SUM(CASE WHEN vt.voucher_date < '{$from_date}' THEN 
                CASE WHEN vt.drcr_type = 'Dr' THEN vt.amount ELSE -vt.amount END
            ELSE 0 END), 0)
            +
            IFNULL(SUM(CASE WHEN vt.voucher_date BETWEEN '{$from_date}' AND '{$to_date}' AND vt.drcr_type = 'Dr' THEN vt.amount ELSE 0 END), 0)
            -
            IFNULL(SUM(CASE WHEN vt.voucher_date BETWEEN '{$from_date}' AND '{$to_date}' AND vt.drcr_type = 'Cr' THEN vt.amount ELSE 0 END), 0)
        ) AS closing_balance
    ");
    $this->db->from('general_ledger gl');
    $this->db->join('voucher_transaction vt', 'gl.account_id = vt.account_id', 'left');
    $this->db->join('account_group ag', 'gl.group_no = ag.group_no', 'left');
    $this->db->where('gl.group_no', $group_no);
    $this->db->group_by('gl.account_id');
    $this->db->order_by('ag.group_name, gl.account_name');

    $query = $this->db->get();
	 //   print_r('debug', 'Last Query: ' . $this->db->last_query());

    return $query->result();
    }
	public function get_account_name_by_id($account_id) {
    $this->db->select('account_name');
    $this->db->from('general_ledger');  // correct table name
    $this->db->where('account_id', $account_id);
    $query = $this->db->get();
    if ($query->num_rows() > 0) {
        return $query->row()->account_name;
    }
    return null;
}

// function get_reco_list() {
//     $account_id = $this->input->post('account_id');
//     $from = $this->input->post('from_date');
//     $to = $this->input->post('to_date');

//     $this->db->select('vt.*, gl.account_name');
//     $this->db->from('voucher_transaction vt');
//     $this->db->join('general_ledger gl', 'vt.customer_id = gl.account_id', 'left');
    
//     $this->db->where_in('vt.voucher_type', ['P', 'R']);
//     $this->db->where('vt.account_id', $account_id);

//     // Fix: Group the reco condition properly
//     $this->db->group_start();
//     $this->db->where('vt.reco <>', '1');
//     $this->db->or_where('vt.reco IS NULL', null, false);
//     $this->db->group_end();

//     if (!empty($from) && !empty($to)) {
//         $this->db->where('vt.voucher_date >=', $from);
//         $this->db->where('vt.voucher_date <=', $to);
//     }

//     $this->db->order_by('vt.voucher_date', 'DESC');

//     $query = $this->db->get();
//     return $query->result();
// }

public function get_account_trial_balance($from_date, $to_date, $branch_id = null)
{
    $params = [$from_date, $to_date];

    $branch_condition = "";

    if (!empty($branch_id)) {
        $branch_condition = " AND vt.branch_id = ? ";
        $params[] = $branch_id;
    }

    $sql = "
        SELECT
            ag.group_name,
            gl.account_name,
            SUM(CASE WHEN vt.drcr_type = 'Dr' THEN vt.amount ELSE 0 END) AS debit,
            SUM(CASE WHEN vt.drcr_type = 'Cr' THEN vt.amount ELSE 0 END) AS credit
        FROM voucher_transaction vt
        JOIN general_ledger gl ON gl.account_id = vt.account_id
        LEFT JOIN account_group ag ON ag.group_no = gl.group_no
        WHERE vt.cancel = 0
            AND vt.voucher_date BETWEEN ? AND ?
            $branch_condition
        GROUP BY vt.account_id, ag.group_name, gl.account_name
        HAVING debit <> 0 OR credit <> 0
        ORDER BY ag.group_name, gl.account_name
    ";

    $query = $this->db->query($sql, $params);

    return $query->result_array();
}
	 public function get_group_totals($from_date, $to_date, $branch_id = null)
{
    $params = [$from_date, $to_date];

    $branch_condition = "";

    if (!empty($branch_id)) {
        $branch_condition = " AND vt.branch_id = ? ";
        $params[] = $branch_id;
    }

    $sql = "
        SELECT
            ag.group_name,
            SUM(CASE WHEN vt.drcr_type = 'Dr' THEN vt.amount ELSE 0 END) AS debit,
            SUM(CASE WHEN vt.drcr_type = 'Cr' THEN vt.amount ELSE 0 END) AS credit
        FROM voucher_transaction vt
        JOIN general_ledger gl ON gl.account_id = vt.account_id
        LEFT JOIN account_group ag ON ag.group_no = gl.group_no
        WHERE vt.cancel = 0
            AND vt.voucher_date BETWEEN ? AND ?
            $branch_condition
        GROUP BY ag.group_name
        ORDER BY ag.group_name
    ";

    $query = $this->db->query($sql, $params);

    $result = $query->result_array();

    $group_totals = [];
    foreach ($result as $row) {
        $group_totals[$row['group_name']] = $row;
    }

    return $group_totals;
}

	public function get_purchase_vat_summary($from_date = null, $to_date = null)
		{
			$this->db->select("
				drcr_type,
				SUM(amount) AS total_amount
			");
			$this->db->from('voucher_transaction');
    $this->db->where_in('voucher_type', ['N', 'J', 'D', 'C', 'EX']);
			$this->db->where('cancel', 0);

		 if (!empty($from_date) && !empty($to_date)) {
        $this->db->where('voucher_date >=', $from_date . " 00:00:00");
        $this->db->where('voucher_date <=', $to_date . " 23:59:59");
    }


			$this->db->group_by('drcr_type');
			$query = $this->db->get();
			$rows = $query->result();

			// Prepare summary
			$summary = [
				'input' => ['taxable' => 0, 'vat' => 0, 'total' => 0],
				'output' => ['taxable' => 0, 'vat' => 0, 'total' => 0]
			];

			foreach ($rows as $row) {
				$total = $row->total_amount;
				$taxable = $total / 1.05; // Assuming 5% VAT rate
				$vat = $total - $taxable;

				if (strtoupper($row->drcr_type) === 'DR') {
					$summary['input'] = [
						'taxable' => $taxable,
						'vat' => $vat,
						'total' => $total
					];
				} elseif (strtoupper($row->drcr_type) === 'CR') {
					$summary['output'] = [
						'taxable' => $taxable,
						'vat' => $vat,
						'total' => $total
					];
				}
			}

			return (object) $summary;
		}

public function get_voucher_vat_details($from_date = null, $to_date = null)
{
    $this->db->select("
        voucher_id,
        voucher_code,
        voucher_date,
        voucher_type,
        drcr_type,
        amount
    ");
    $this->db->from('voucher_transaction');
    $this->db->where_in('voucher_type', ['N', 'J', 'D', 'C', 'EX']);
    $this->db->where('cancel', 0);

   
		 if (!empty($from_date) && !empty($to_date)) {
        $this->db->where('voucher_date >=', $from_date . " 00:00:00");
        $this->db->where('voucher_date <=', $to_date . " 23:59:59");
    }

    $this->db->order_by('voucher_date', 'ASC');
    $query = $this->db->get();
    $rows = $query->result();

    $data = [
        'input' => [],
        'output' => [],
        'totals' => [
            'input_taxable' => 0,
            'input_vat' => 0,
            'output_taxable' => 0,
            'output_vat' => 0,
        ]
    ];

    foreach ($rows as $row) {
        $total = $row->amount;
        $taxable = $total / 1.05; // Assuming 5% VAT
        $vat = $total - $taxable;

        $entry = (object)[
            'voucher_code'   => $row->voucher_code,
            'voucher_date'   => $row->voucher_date,
            'voucher_type'   => $row->voucher_type,
            'taxable_amount' => round($taxable, 2),
            'vat_amount'     => round($vat, 2),
            'total'          => round($total, 2)
        ];

        if (strtoupper($row->drcr_type) === 'DR') {
            $data['input'][] = $entry;
            $data['totals']['input_taxable'] += $taxable;
            $data['totals']['input_vat'] += $vat;
        } elseif (strtoupper($row->drcr_type) === 'CR') {
            $data['output'][] = $entry;
            $data['totals']['output_taxable'] += $taxable;
            $data['totals']['output_vat'] += $vat;
        }
    }

    return (object)$data;
}

public function get_cashflow_summary($from_date, $to_date, $branch_id = null)
{
    $branch_sql = "";

    if (!empty($branch_id)) {
        $branch_sql = " AND vt.branch_id = " . $this->db->escape($branch_id);
    }

    $sql = "
        SELECT
            DATE(vt.voucher_date) AS trx_date,
            COALESCE(ag.group_name, 'Ungrouped') AS account_group,
            gl.account_name,

            SUM(CASE WHEN LOWER(vt.drcr_type) = 'cr' THEN vt.amount ELSE 0 END) AS cash_inflows,
            SUM(CASE WHEN LOWER(vt.drcr_type) = 'dr' THEN vt.amount ELSE 0 END) AS cash_outflows,

            SUM(CASE WHEN LOWER(vt.drcr_type) = 'cr' THEN vt.amount ELSE 0 END) -
            SUM(CASE WHEN LOWER(vt.drcr_type) = 'dr' THEN vt.amount ELSE 0 END) AS net_cashflow

        FROM voucher_transaction vt
        LEFT JOIN general_ledger gl ON vt.account_id = gl.account_id
        LEFT JOIN account_group ag ON gl.group_no = ag.group_no

        WHERE vt.voucher_date BETWEEN ? AND ?
        $branch_sql

        GROUP BY
            trx_date,
            account_group,
            gl.account_name

        ORDER BY
            trx_date,
            account_group,
            gl.account_name
    ";

    return $this->db->query($sql, [$from_date, $to_date])->result();
}

public function delete_account_group($id)
{
    $this->db->where('group_no', $id);
    $this->db->delete('account_group');
}

function get_reco_list($account_id, $from = null, $to = null)
{
    $this->db->select('vt.*, gl.account_name');
    $this->db->from('voucher_transaction vt');
    $this->db->join('general_ledger gl', 'vt.customer_id = gl.account_id', 'left');

    $this->db->where_in('vt.voucher_type', ['P', 'R']);

    $this->db->group_start();
    $this->db->where('vt.account_id', $account_id);
    $this->db->or_where('vt.customer_id', $account_id);
    $this->db->group_end();

    // FIXED reco logic
    $this->db->where("(vt.reco = '0' OR vt.reco IS NULL)");

    if (!empty($from)) {
        $this->db->where('DATE(vt.voucher_date) >=', $from);
    }

    if (!empty($to)) {
        $this->db->where('DATE(vt.voucher_date) <=', $to);
    }

    $this->db->order_by('vt.voucher_date', 'DESC');

    return $this->db->get()->result();
}

public function prepare_balance_sheet($to_date, $branch_id = null)
	{
		$rows = $this->get_balance_sheet_raw($to_date, $branch_id);

		$groups = [];

		foreach ($rows as $row) {

			if (!isset($groups[$row->group_no])) {
				$groups[$row->group_no] = (object)[
					'group_no' => $row->group_no,
					'group_name' => $row->group_name,
					'parent_group' => $row->parent_group,
					'root_type' => $row->root_type, // ✅ FIXE
					'balance' => 0,
					'children' => [],
					'ledgers' => []
				];
			}

			if ($row->account_id) {

				$balance = $row->closing_balance; // ✅ USE SQL VALUE

				$groups[$row->group_no]->ledgers[] = (object)[
					'name' => $row->account_name,
					'balance' => $balance,
					'account_id' => $row->account_id
				];

				$groups[$row->group_no]->balance += $balance;
			}
		}

		$tree = $this->build_tree($groups);

		$this->calculate_totals($tree);
		// echo '<pre>';
		// print_r($tree);
		// exit;
		return $tree;
	}

	public function get_balance_sheet_raw($to_date, $branch_id = null)
{
    
    $branch_condition = "";

    if (!empty($branch_id)) {
        $branch_condition = " AND v.branch_id = " . $this->db->escape($branch_id);
    }

    $sql = "SELECT 
        ag.group_no,
        ag.group_name,
        root.group_name AS root_type,
        ag.parent_group,
       
        gl.account_id,
        gl.account_name,
        gl.opening_bal_type,

        COALESCE(vt.trans_balance, 0) AS trans_balance,

        (
            CASE 
                WHEN gl.opening_bal_type = 'Dr' THEN gl.opening_balance
                ELSE -gl.opening_balance
            END
            + COALESCE(vt.trans_balance, 0)
        ) AS closing_balance

    FROM account_group ag

    LEFT JOIN general_ledger gl 
        ON gl.group_no = ag.group_no

    LEFT JOIN account_group root 
        ON root.group_no = (
            CASE 
                WHEN ag.parent_group = 0 THEN ag.group_no
                ELSE ag.parent_group
            END
        )

   LEFT JOIN (
    SELECT 
        vt.account_id,
        SUM(
            CASE 
                WHEN vt.drcr_type = 'Dr' THEN vt.amount
                WHEN vt.drcr_type = 'Cr' THEN -vt.amount
            END
        ) AS trans_balance
    FROM voucher_transaction vt
    INNER JOIN general_ledger gl
        ON gl.account_id = vt.account_id
    WHERE vt.cancel = 0
      AND DATE(vt.voucher_date) <= " . $this->db->escape($to_date) . "
      " . (!empty($branch_id) ? " AND gl.branch_id = " . $this->db->escape($branch_id) : "") . "
    GROUP BY vt.account_id
) vt 
ON vt.account_id = gl.account_id

    WHERE (ag.pandl = 0 OR ag.pandl IS NULL)
    ";

    return $this->db->query($sql)->result();
}

private function build_tree($groups)
	{
		$tree = [];
		foreach ($groups as $group) {
			if ($group->parent_group == 0) {
				$tree[$group->group_no] = $group;
			} else {
				if ($group->parent_group != 0 && isset($groups[$group->parent_group])) {
					$groups[$group->parent_group]->children[] = $group;
				}
			}
		}
		return array_values($tree);
	}

	private function calculate_totals(&$groups)
	{
		foreach ($groups as &$group) {
			if (!empty($group->children)) {
				$this->calculate_totals($group->children);
				foreach ($group->children as $child) {
					$group->balance += $child->balance;
				}
			}
		}
	}
function get_profit_loss($from_date, $to_date)
	{
		$sql = "
    SELECT 
        SUM(
            CASE 
                WHEN vt.drcr_type = 'Dr' THEN vt.amount 
                ELSE -vt.amount 
            END
        ) AS profit_loss
    FROM voucher_transaction vt
    JOIN general_ledger gl ON gl.account_id = vt.account_id
    JOIN account_group ag ON ag.group_no = gl.group_no
    WHERE ag.pandl = 1
    AND vt.cancel = 0
    AND vt.voucher_date BETWEEN '$from_date' AND '$to_date'
    ";

		return $this->db->query($sql)->row()->profit_loss ?? 0;
	}

public function get_ledger_transactions($account_id, $from, $to)
	{
		return $this->db->query("
        SELECT 
            v.voucher_date AS date,
            g.account_name AS ledger_name,
            CASE 
                WHEN v.drcr_type = 'Dr' THEN v.amount
                ELSE -v.amount
            END AS amount
        FROM voucher_transaction v
        JOIN general_ledger g ON g.account_id = v.account_id
        WHERE v.account_id = '$account_id'
        AND v.cancel = 0
        AND DATE(v.voucher_date) BETWEEN '$from' AND '$to'
        ORDER BY v.voucher_date ASC
    ")->result();
	}
public function get_income($from, $to)
{
    return $this->db->query("
        SELECT 
            g.account_id, 
            g.account_name,
            COALESCE(
                SUM(CASE WHEN v.drcr_type = 'Cr' THEN v.amount ELSE 0 END) -
                SUM(CASE WHEN v.drcr_type = 'Dr' THEN v.amount ELSE 0 END)
            ,0) AS total

        FROM general_ledger g

        LEFT JOIN voucher_transaction v 
            ON v.account_id = g.account_id
            AND v.cancel = 0
            AND v.voucher_date >= '$from 00:00:00'
            AND v.voucher_date <= '$to 23:59:59'

        WHERE g.group_no IN (
            SELECT group_no FROM account_group 
            WHERE parent_group = 3 OR group_no = 3
        )

        GROUP BY g.account_id
    ")->result();
}

public function get_expense($from, $to)
{
    return $this->db->query("
        SELECT 
            g.account_id, 
            g.account_name,
            COALESCE(
                ABS(
                    SUM(CASE WHEN v.drcr_type = 'Dr' THEN v.amount ELSE 0 END) -
                    SUM(CASE WHEN v.drcr_type = 'Cr' THEN v.amount ELSE 0 END)
                )
            ,0) AS total

        FROM general_ledger g

        LEFT JOIN voucher_transaction v 
            ON v.account_id = g.account_id
            AND v.cancel = 0
            AND v.voucher_date >= '$from 00:00:00'
            AND v.voucher_date <= '$to 23:59:59'

        WHERE g.group_no IN (
            SELECT group_no FROM account_group 
            WHERE parent_group = 4 OR group_no = 4
        )

        GROUP BY g.account_id
    ")->result();
}

public function get_account_trial_balance_new($from_date, $to_date)
{
    $sql = "
        SELECT 
            ag.group_name,
            gl.account_name,
            SUM(CASE WHEN vt.drcr_type = 'Dr' THEN vt.amount ELSE 0 END) AS debit,
            SUM(CASE WHEN vt.drcr_type = 'Cr' THEN vt.amount ELSE 0 END) AS credit
        FROM voucher_transaction vt
        JOIN general_ledger gl ON gl.account_id = vt.account_id
        LEFT JOIN account_group ag ON ag.group_no = gl.group_no
        WHERE vt.cancel = 0
            AND DATE(vt.voucher_date) BETWEEN ? AND ?
        GROUP BY vt.account_id, ag.group_name, gl.account_name
        HAVING debit <> 0 OR credit <> 0
        ORDER BY ag.group_name, gl.account_name
    ";

    $query = $this->db->query($sql, [$from_date, $to_date]);
    return $query->result_array();
}

public function get_sales_accounts($from, $to, $branch_id = null)
{
    $branch_condition = "";

    if (!empty($branch_id)) {
        $branch_condition = " AND v.branch_id = " . $this->db->escape($branch_id);
    }

    return $this->db->query("
        SELECT 
            g.account_id, 
            g.account_name,
            SUM(CASE WHEN v.drcr_type = 'Cr' THEN v.amount ELSE 0 END) -
            SUM(CASE WHEN v.drcr_type = 'Dr' THEN v.amount ELSE 0 END) AS total
        FROM general_ledger g
        JOIN voucher_transaction v 
            ON v.account_id = g.account_id
        WHERE g.group_no = 14
        AND v.cancel = 0
        AND DATE(v.voucher_date) BETWEEN '$from' AND '$to'
        $branch_condition
        GROUP BY g.account_id
    ")->result();
}
    public function get_purchase_accounts($from, $to, $branch_id = null)
{
    $branch_condition = "";

    if (!empty($branch_id)) {
        $branch_condition = " AND v.branch_id = " . $this->db->escape($branch_id);
    }

    return $this->db->query("
        SELECT 
            g.account_id, 
            g.account_name,
            SUM(CASE WHEN v.drcr_type = 'Dr' THEN v.amount ELSE 0 END) -
            SUM(CASE WHEN v.drcr_type = 'Cr' THEN v.amount ELSE 0 END) AS total
        FROM general_ledger g
        JOIN voucher_transaction v 
            ON v.account_id = g.account_id
        WHERE g.group_no = 13
        AND v.cancel = 0
        AND DATE(v.voucher_date) BETWEEN '$from' AND '$to'
        $branch_condition
        GROUP BY g.account_id
    ")->result();
}

   public function get_direct_income($from, $to, $branch_id = null)
{
    $branch_condition = "";

    if (!empty($branch_id)) {
        $branch_condition = " AND v.branch_id = " . $this->db->escape($branch_id);
    }

    return $this->db->query("
        SELECT g.account_id, g.account_name,
        SUM(CASE WHEN v.drcr_type = 'Cr' THEN v.amount ELSE 0 END) -
        SUM(CASE WHEN v.drcr_type = 'Dr' THEN v.amount ELSE 0 END) AS total
        FROM general_ledger g
        JOIN voucher_transaction v ON v.account_id = g.account_id
        WHERE g.group_no = 10
        AND v.cancel = 0
        AND DATE(v.voucher_date) BETWEEN '$from' AND '$to'
        $branch_condition
        GROUP BY g.account_id
    ")->result();
}
   public function get_direct_expense($from, $to, $branch_id = null)
{
    $branch_condition = "";

    if (!empty($branch_id)) {
        $branch_condition = " AND v.branch_id = " . $this->db->escape($branch_id);
    }

    return $this->db->query("
        SELECT g.account_id, g.account_name,
        SUM(CASE WHEN v.drcr_type = 'Dr' THEN v.amount ELSE 0 END) -
        SUM(CASE WHEN v.drcr_type = 'Cr' THEN v.amount ELSE 0 END) AS total
        FROM general_ledger g
        JOIN voucher_transaction v ON v.account_id = g.account_id
        WHERE g.group_no = 9
        AND v.cancel = 0
        AND DATE(v.voucher_date) BETWEEN '$from' AND '$to'
        $branch_condition
        GROUP BY g.account_id
    ")->result();
}

    // --- PROFIT & LOSS QUERIES (BOTTOM HALF) ---

  public function get_indirect_income($from, $to, $branch_id = null)
{
    $branch_condition = "";

    if (!empty($branch_id)) {
        $branch_condition = " AND v.branch_id = " . $this->db->escape($branch_id);
    }

    return $this->db->query("
        SELECT g.account_id, g.account_name,
        SUM(CASE WHEN v.drcr_type = 'Cr' THEN v.amount ELSE 0 END) -
        SUM(CASE WHEN v.drcr_type = 'Dr' THEN v.amount ELSE 0 END) AS total
        FROM general_ledger g
        JOIN voucher_transaction v ON v.account_id = g.account_id
        WHERE g.group_no = 12
        AND v.cancel = 0
        AND DATE(v.voucher_date) BETWEEN '$from' AND '$to'
        $branch_condition
        GROUP BY g.account_id
    ")->result();
}
   public function get_indirect_expense($from, $to, $branch_id = null)
{
    $branch_condition = "";

    if (!empty($branch_id)) {
        $branch_condition = " AND v.branch_id = " . $this->db->escape($branch_id);
    }

    return $this->db->query("
        SELECT g.account_id, g.account_name,
        SUM(CASE WHEN v.drcr_type = 'Dr' THEN v.amount ELSE 0 END) -
        SUM(CASE WHEN v.drcr_type = 'Cr' THEN v.amount ELSE 0 END) AS total
        FROM general_ledger g
        JOIN voucher_transaction v ON v.account_id = g.account_id
        WHERE g.group_no = 11
        AND v.cancel = 0
        AND DATE(v.voucher_date) BETWEEN '$from' AND '$to'
        $branch_condition
        GROUP BY g.account_id
    ")->result();
}
public function get_daybook($from_date, $to_date)
{
    $this->db->select("
        DATE(v.voucher_date) AS trx_date,
        v.voucher_code,
        v.voucher_type,
        gl.account_name,
        v.amount,
        v.drcr_type,
        v.narration
    ");

    $this->db->from('voucher_transaction v');
    $this->db->join('general_ledger gl', 'v.account_id = gl.account_id');

    $this->db->where('DATE(v.voucher_date) >=', $from_date);
    $this->db->where('DATE(v.voucher_date) <=', $to_date);

    $this->db->order_by('v.voucher_date', 'ASC');
    $this->db->order_by('v.voucher_code', 'ASC');

    return $this->db->get()->result();
}

function add_expense_entry()
{
    $this->load->model('Accounts_model');

    // =========================
    // EXPENSE VOUCHER CODE
    // =========================
    $code_prifix = "PVF/EX/" . date('y') . "/";

    $num = ($this->Accounts_model->get_account_code_count($code_prifix, 'EX')) + 1;

    $digit = sprintf("%1$05d", $num);

    $AccountCode = $code_prifix . $digit;

    // =========================
    // BASIC DATA
    // =========================
    $vdate = $this->input->post('v_date');
    $vtime = $this->input->post('vtime');
    $narration = $this->input->post('narration');
     $branch_id = $this->input->post('branch_id');
      $currency_id = $this->input->post('currency_id');
    $user_id = $this->session->userdata('user_id');

    $insert_id = 0;

    // =========================
    // DEBIT ENTRIES (Expense + VAT 226)
    // =========================
    if (!empty($_POST['debtor']))
    {
        for ($i = 0; $i < count($_POST['debtor']); $i++)
        {
            $debtor = $_POST['debtor'][$i];
            $dr_amount = $_POST['dr_amount'][$i];

            if ($dr_amount <= 0) continue;

            $data = array(
                'voucher_code'      => $AccountCode,
                'voucher_date'      => date('Y-m-d H:i:s', strtotime("$vdate $vtime")),
                'voucher_type'      => 'EX',   // ⭐ IMPORTANT CHANGE
                'account_id'        => $debtor,
                'amount'            => $dr_amount,
                'drcr_type'         => 'Dr',
                'narration'         => $narration,
                'trans_type'        => 'EX',
                'recordCreatedBy'   => $user_id,
                'branch_id'         => $branch_id,
                 'currency_id'         => $currency_id

            );

            $this->db->insert('voucher_transaction', $data);
            $insert_id = $this->db->insert_id();
        }
    }

    // =========================
    // CREDIT ENTRIES
    // =========================
    if (!empty($_POST['creditor']))
    {
        for ($i = 0; $i < count($_POST['creditor']); $i++)
        {
            $creditor = $_POST['creditor'][$i];
            $cr_amount = $_POST['cr_amount'][$i];

            if ($cr_amount <= 0) continue;

            $data = array(
                'voucher_code'      => $AccountCode,
                'voucher_date'      => date('Y-m-d H:i:s', strtotime("$vdate $vtime")),
                'voucher_type'      => 'EX',   // ⭐ IMPORTANT CHANGE
                'account_id'        => $creditor,
                'amount'            => $cr_amount,
                'drcr_type'         => 'Cr',
                'narration'         => $narration,
                'trans_type'        => 'EX',
                'recordCreatedBy'   => $user_id,
                'branch_id'         => $branch_id,
                 'currency_id'         => $currency_id
            );

            $this->db->insert('voucher_transaction', $data);
            $insert_id = $this->db->insert_id();
        }
    }

    // =========================
    // LOG ENTRY
    // =========================
    if ($insert_id)
    {
        $page_name = explode('index.php/', $_SERVER['PHP_SELF']);

        $ci = get_instance();
        $ci->load->helper('log');

        add_log_entry($user_id, 1, $page_name[1], 'voucher_transaction', 'voucher_id', $insert_id);

        return $insert_id;
    }
}

function get_expense_entry_records($from, $to)
{
    $from_date = date('Y-01-01', strtotime($from));
    $to_date   = date('Y-12-31', strtotime($to));

    $query = $this->db->query("
        SELECT
            MIN(voucher_id) AS voucher_id,
            voucher_code,
            voucher_date,
            narration,
            cancel,

            SUM(CASE
                    WHEN drcr_type='Dr' AND account_id <> 226
                    THEN amount
                    ELSE 0
                END) AS expense,

            SUM(CASE
                    WHEN drcr_type='Dr' AND account_id = 226
                    THEN amount
                    ELSE 0
                END) AS vat,

            SUM(CASE
                    WHEN drcr_type='Cr'
                    THEN amount
                    ELSE 0
                END) AS total

        FROM voucher_transaction

        WHERE voucher_type='EX'
        AND DATE(voucher_date) BETWEEN '$from_date' AND '$to_date'

        GROUP BY voucher_code

        ORDER BY DATE(voucher_date) DESC, voucher_code DESC
    ");

    return $query->result();
}
/*********************************  End CI Model **************************************************/
}
?>
