<?php

class Users_model extends CI_Model
{

	function add_new_user()
	{
		$photo_file = '';
		$allowedExts = array("jpeg", "jpg", "png", "doc", "pdf");
		$file_name = $_FILES["emp_photo"]["name"];

		$data['file_name'] = str_replace(' ', '_', $file_name);
		$temp = explode(".", $_FILES["emp_photo"]["name"]);
		$extension = end($temp);
		$other_file = '';
		if (($_FILES["emp_photo"]["size"] < 15728640) && in_array($extension, $allowedExts)) {
			if ($_FILES["emp_photo"]["error"] > 0) {
				$this->session->set_flashdata('error', 'Failed to upload - Please check file size and file format');
			} else {
				$timestamp1 = time();
				$file_tmp = $_FILES["emp_photo"]["tmp_name"];
				$photo_file = $timestamp1 . "_" . $_FILES['emp_photo']['name'];
				$dest = FCPATH . 'public/uploded_documents/emp_photos/' . $photo_file;
				move_uploaded_file($file_tmp, $dest);
			}
		}
		$access = 0;
		if ($this->input->post('access') == 1) {
			$access = 1;
		}

		$data = array(
			'user_name' 		 	 => $this->input->post('first_name'),
			'user_last_name'		 => $this->input->post('last_name'),
			'passport_name'			 => $this->input->post('passport_name'),
			'branch'				 => $this->input->post('branch'),
			'software_access'		 => $access,
			'email_id'				 => $this->input->post('company_email'),
			'password'				 => $this->input->post('password'),
			'contact_no'			 => $this->input->post('mobile1'),
			'desig_id'			 	 => $this->input->post('desig_id'),
			'gender'			 	 => $this->input->post('gender'),
			'bdate'					 => date('Y-m-d', strtotime($this->input->post('bdate'))),
			'nationality'			 => $this->input->post('nationality'),
			'emp_photo'				 => $photo_file,
			'joining_date'			 => date('Y-m-d', strtotime($this->input->post('joining_date'))),
			'salary_withdraw_via' 	 => $this->input->post('salary_withdraw_via'),
			'card_no' 				 => $this->input->post('card_no'),
			'personal_mol_no'		 => $this->input->post('personal_mol_no'),
			'created_by'			 => $this->session->userdata('user_id'),
			'created_date'			 => date('Y-m-d H:i')
		);
		if ($_POST['branch'] == 0) {
			$data['company_name'] 		= $_POST['compny_name'];
			$data['wrkg_hours']			= $_POST['wrkg_hrs'];
		}
		$this->db->insert('users', $data);
		$insert_id = $this->db->insert_id();
		//insert passsport
		$pp_img = '';
		$allowedExts = array("jpeg", "jpg", "png", "doc", "pdf");
		$file_name = $_FILES["emp_passport"]["name"];

		$data['file_name'] = str_replace(' ', '_', $file_name);
		$temp = explode(".", $_FILES["emp_passport"]["name"]);
		$extension = end($temp);
		if (($_FILES["emp_passport"]["size"] < 15728640) && in_array($extension, $allowedExts)) {
			if ($_FILES["emp_passport"]["error"] > 0) {
				$this->session->set_flashdata('error', 'Failed to upload - Please check file size and file format');
			} else {
				$timestamp1 = time();
				$file_tmp = $_FILES["emp_passport"]["tmp_name"];
				$pp_img = $timestamp1 . "_" . $_FILES['emp_passport']['name'];
				$dest = FCPATH . 'public/uploded_documents/emp_passport/' . $pp_img;
				move_uploaded_file($file_tmp, $dest);
			}
		}
		$data = array(
			'emp_id'		     => $insert_id,
			'document_name'		 => 'passport',
			'document_number'    => $this->input->post('passport_number'),
			'issue_date'		 => date('Y-m-d', strtotime($this->input->post('passport_date'))),
			'expiry_date'		 => date('Y-m-d', strtotime($this->input->post('passport_expdate'))),
			'issue_place'		 => $this->input->post('passport_issue_place'),
			'image_url'			 => $pp_img,
		);
		$this->db->insert('employee_document_details', $data);



		// labour insert
		$lbr_img = '';
		$allowedExts = array("jpeg", "jpg", "png", "doc", "pdf");
		$file_name = $_FILES["emp_labourcard"]["name"];

		$data['file_name'] = str_replace(' ', '_', $file_name);
		$temp = explode(".", $_FILES["emp_labourcard"]["name"]);
		$extension = end($temp);
		if (($_FILES["emp_labourcard"]["size"] < 15728640) && in_array($extension, $allowedExts)) {
			if ($_FILES["emp_labourcard"]["error"] > 0) {
				$this->session->set_flashdata('error', 'Failed to upload - Please check file size and file format');
			} else {
				$timestamp1 = time();
				$file_tmp = $_FILES["emp_labourcard"]["tmp_name"];
				$lbr_img = $timestamp1 . "_" . $_FILES['emp_labourcard']['name'];
				$dest = FCPATH . 'public/uploded_documents/emp_labourcard/' . $lbr_img;
				move_uploaded_file($file_tmp, $dest);
			}
		}
		$data = array(
			'emp_id' 			 => $insert_id,
			'document_name' 	 => 'labour',
			'document_number'    => $this->input->post('wrk_permit_no'),
			'issue_date' 		 => date('Y-m-d', strtotime($this->input->post('labor_date'))),
			'expiry_date'		 => date('Y-m-d', strtotime($this->input->post('labor_expdate'))),
			'image_url'			 => $lbr_img,
		);
		$this->db->insert('employee_document_details', $data);

		//Emirates insert
		$eid_img = '';
		$allowedExts = array("jpeg", "jpg", "png", "doc", "pdf");
		$file_name = $_FILES["emp_eid"]["name"];

		$data['file_name'] = str_replace(' ', '_', $file_name);
		$temp 			   = explode(".", $_FILES["emp_eid"]["name"]);
		$extension = end($temp);
		if (($_FILES["emp_eid"]["size"] < 15728640) && in_array($extension, $allowedExts)) {
			if ($_FILES["emp_eid"]["error"] > 0) {
				$this->session->set_flashdata('error', 'Failed to upload - Please check file size and file format');
			} else {
				$timestamp1 = time();
				$file_tmp = $_FILES["emp_eid"]["tmp_name"];
				$eid_img = $timestamp1 . "_" . $_FILES['emp_eid']['name'];
				$dest = FCPATH . 'public/uploded_documents/emp_eid/' . $eid_img;
				move_uploaded_file($file_tmp, $dest);
			}
		}
		$data = array(
			'emp_id'  		   => $insert_id,
			'document_name'	   => 'eid',
			'document_number'  => $this->input->post('EmiratesID'),
			'issue_date'	   => date('Y-m-d', strtotime($this->input->post('emirate_issue_date'))),
			'expiry_date'	   => date('Y-m-d', strtotime($this->input->post('emirate_expdate'))),
			'image_url'	       => $eid_img,
		);
		$this->db->insert('employee_document_details', $data);


		// //salary insert
		// $data = array(
		// 	'emp_id'  => $insert_id,
		// 	'gross_salary' => $this->input->post('GSalary'),
		// 	'basic_salary' => $this->input->post('BSalary'),
		// 	//'total_allowances' => $this->input->post('aallowance'),
		// 	'total_allowances' => $this->input->post('tallowance'),
		// 	//'total_allowances' => $this->input->post('oallowance'),
		// 	//'total_deductions' => $this->input->post('Transportation_provided'),
		// 	//'total_deductions' => $this->input->post('Accomodation_provided'),
		// 	'overtime' => $this->input->post('OT'),

		// 	//'issue_date' => date('Y-m-d', strtotime($this->input->post('labor_date'))),
		// 	//'expiry_date' => date('Y-m-d', strtotime($this->input->post('emirate_expdate'))),
		// );
		// $this->db->insert('salary_structure', $data);


		if ($insert_id) {
			//Accounts entry
			$grp_no = 11;
			$data1 = array(
							'account_name' => $this->input->post('first_name') . ' ' . $insert_id,
							'group_no' => $grp_no,
							'customer_id' => $insert_id,
							'opening_bal_type' => 'Dr',
						);
			$this->db->insert('general_ledger', $data1);
			$ledger_id = $this->db->insert_id();


			$user_se_id 		 = $this->session->userdata('user_id');
			$page_name			 = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci					 = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 1, $page_name[1], 'users', 'user_id', $insert_id);
		}
		return $insert_id;
	}



	function update_user_data($user_id)
	{
		$signatureData = $_POST['signature-data'];
		if ($signatureData) {
			$signatureData = str_replace('data:image/png;base64,', '', $signatureData);
			$signatureData = base64_decode($signatureData);
			$sign_fileName = 'public/uploded_documents/signatures/signature_' . $user_id . '.png';
			file_put_contents($sign_fileName, $signatureData);
		} else if (isset($_FILES["employee_sign"])) {

			//////////////
			$sign_file = '';
			$allowedExts = array("png");

			$temp = explode(".", $_FILES["employee_sign"]["name"]);
			$extension = end($temp);
			if (($_FILES["employee_sign"]["size"] < 15728640) && in_array($extension, $allowedExts)) {
				if ($_FILES["employee_sign"]["error"] > 0) {
					$this->session->set_flashdata('error', 'Failed to upload - Please check file size and file format');
				} else {

					$sign_file = "signature_" . $user_id . '.png';
					$dest =  FCPATH . 'public/uploded_documents/signatures/' . $sign_file;
					$this->removeBackground($dest);
				}
			}
		}


		//photo update		
		if (isset($_FILES["emp_photo"])) {

			$photo_file = '';
			$allowedExts = array("jpeg", "jpg", "png", "doc", "pdf");
			$file_name = $_FILES["emp_photo"]["name"];

			$data['file_name'] = str_replace(' ', '_', $file_name);
			$temp = explode(".", $_FILES["emp_photo"]["name"]);
			$extension = end($temp);
			$other_file = '';
			if (($_FILES["emp_photo"]["size"] < 15728640) && in_array($extension, $allowedExts)) {
				if ($_FILES["emp_photo"]["error"] > 0) {
					$this->session->set_flashdata('error', 'Failed to upload - Please check file size and file format');
				} else {
					$timestamp1 = time();
					$file_tmp = $_FILES["emp_photo"]["tmp_name"];
					$photo_file = $timestamp1 . "_" . $_FILES['emp_photo']['name'];
					$dest = FCPATH . 'public/uploded_documents/emp_photos/' . $photo_file;
					//move_uploaded_file($file_tmp,$dest);
					if (move_uploaded_file($file_tmp, $dest)) {
						$query = $this->db->query("update users set emp_photo='$photo_file' where user_id=$user_id");
					}
				}
			}
		}
		$access = 0;
		if ($this->input->post('access') == 1) {
			$access = 1;
		}
		$data = array(
			'user_name' => $this->input->post('first_name'),
			'user_last_name' => $this->input->post('last_name'),
			'passport_name' => $this->input->post('passport_name'),
			'branch' => $this->input->post('branch'),
			'software_access' => $access,
			'email_id' => $this->input->post('company_email'),
			'password' => $this->input->post('password'),
			'desig_id' => $this->input->post('desig_id'),
			'contact_no' => $this->input->post('mobile1'),
			'nationality' => $this->input->post('nationality'),
			'joining_date' => date('Y-m-d', strtotime($this->input->post('joining_date'))),
			'salary_withdraw_via' => $this->input->post('salary_withdraw_via'),
			'card_no' => $this->input->post('card_no'),
			'personal_mol_no' => $this->input->post('personal_mol_no'),
			'gender' => $this->input->post('gender'),
			'bdate' => date('Y-m-d', strtotime($this->input->post('bdate'))),
		);
		if ($_POST['branch'] == 0) {
			$data['company_name'] = $_POST['compny_name'];
			$data['wrkg_hours'] = $_POST['wrkg_hrs'];
		}
		$this->db->where('user_id', $user_id);
		$res = $this->db->update('users', $data);



		//passport 

		//photo
		if (isset($_FILES["emp_passport"])) {

			$photo_file = '';
			$allowedExts = array("jpeg", "jpg", "png", "doc", "pdf");
			$file_name = $_FILES["emp_passport"]["name"];

			$data['file_name'] = str_replace(' ', '_', $file_name);
			$temp = explode(".", $_FILES["emp_passport"]["name"]);
			$extension = end($temp);
			if (($_FILES["emp_passport"]["size"] < 15728640) && in_array($extension, $allowedExts)) {
				if ($_FILES["emp_passport"]["error"] > 0) {
					$this->session->set_flashdata('error', 'Failed to upload - Please check file size and file format');
				} else {
					$timestamp1 = time();
					$file_tmp = $_FILES["emp_passport"]["tmp_name"];
					$photo_file = $timestamp1 . "_" . $_FILES['emp_passport']['name'];
					$dest = FCPATH . 'public/uploded_documents/emp_passport/' . $photo_file;
					//$dest = "C:/xampp-new/htdocs/green_oasis/public/uploded_documents/emp_passport/".$photo_file;	
					if (move_uploaded_file($file_tmp, $dest)) {
						$data = array(
							'image_url'  => $photo_file,
						);
						$this->db->where('emp_id', $user_id);
						$this->db->where('document_name', 'passport');
						$this->db->update('employee_document_details', $data);
						//$query=$this->db->query("update employee_document_details set image_url='123' where emp_id='$user_id' and document_name='passport'");
					}
				}
			}
		}
		$data = array(

			'emp_id'  => $user_id,
			'document_number' => $this->input->post('passport_number'),
			'issue_date' => date('Y-m-d', strtotime($this->input->post('passport_date'))),
			'expiry_date' => date('Y-m-d', strtotime($this->input->post('passport_expdate'))),
			'issue_place' => $this->input->post('passport_issue_place'),

		);
		$this->db->where('emp_id', $user_id);
		$this->db->where('document_name', 'passport');
		$res1 = $this->db->update('employee_document_details', $data);

		// visa details

		//photo
		// if(isset($_FILES["emp_visa"])){

		// 	$photo_file='';
		// 	$allowedExts = array("jpeg","jpg","png","doc","pdf");
		// 	$file_name=$_FILES["emp_visa"]["name"];

		// 	$data['file_name']=str_replace(' ', '_', $file_name);
		// 	$temp = explode(".", $_FILES["emp_visa"]["name"]);
		// 	$extension = end($temp); $other_file='';
		// 	if (($_FILES["emp_visa"]["size"] < 15728640) && in_array($extension, $allowedExts))
		// 	{
		// 		if ($_FILES["emp_visa"]["error"] > 0)
		// 		{
		// 			$this->session->set_flashdata('error','Failed to upload - Please check file size and file format');
		// 		}
		// 		else
		// 		{
		// 			$timestamp1=time();
		// 			$file_tmp = $_FILES["emp_visa"]["tmp_name"];
		// 			$photo_file = $timestamp1."_".$_FILES['emp_visa']['name'];		
		// 			//$dest = "C:/xampp-new/htdocs/green_oasis/public/uploded_documents/emp_visa/".$photo_file;	
		// 			$dest = FCPATH . 'public/uploded_documents/emp_visa/' . $photo_file;
		// 			if(move_uploaded_file($file_tmp,$dest)){
		// 				$query=$this->db->query("update employee_document_details set image_url='$photo_file' where emp_id='$user_id' and document_name='visa'");
		// 			}
		// 		}
		// 	} 
		// }
		// $data = array(
		// 	'emp_id'  => $user_id,
		// 	'posession' => $this->input->post('visa'),
		// 	'status' => $this->input->post('visa_stamping'),
		// 	'issue_date' => date('Y-m-d', strtotime($this->input->post('visa_date'))),
		// 	'expiry_date' => date('Y-m-d', strtotime($this->input->post('visa_expdate'))),
		// );
		// $this->db->where('emp_id', $user_id);
		// $this->db->where('document_name', 'visa');
		// $this->db->update('employee_document_details', $data);


		// labour insert

		//photo
		if (isset($_FILES["emp_labourcard"])) {

			$photo_file = '';
			$allowedExts = array("jpeg", "jpg", "png", "doc", "pdf");
			$file_name = $_FILES["emp_labourcard"]["name"];

			$data['file_name'] = str_replace(' ', '_', $file_name);
			$temp = explode(".", $_FILES["emp_labourcard"]["name"]);
			$extension = end($temp);
			$other_file = '';
			if (($_FILES["emp_labourcard"]["size"] < 15728640) && in_array($extension, $allowedExts)) {
				if ($_FILES["emp_labourcard"]["error"] > 0) {
					$this->session->set_flashdata('error', 'Failed to upload - Please check file size and file format');
				} else {
					$timestamp1 = time();
					$file_tmp = $_FILES["emp_labourcard"]["tmp_name"];
					$photo_file = $timestamp1 . "_" . $_FILES['emp_labourcard']['name'];
					//$dest = "C:/xampp-new/htdocs/green_oasis/public/uploded_documents/emp_labourcard/".$photo_file;	
					$dest = FCPATH . 'public/uploded_documents/emp_labourcard/' . $photo_file;
					if (move_uploaded_file($file_tmp, $dest)) {
						$query = $this->db->query("update employee_document_details set image_url='$photo_file' where emp_id='$user_id' and document_name='labour'");
					}
				}
			}
		}

		$data = array(
			'emp_id'  => $user_id,
			'document_number' => $this->input->post('wrk_permit_no'),
			'issue_date' => date('Y-m-d', strtotime($this->input->post('labor_date'))),
			'expiry_date' => date('Y-m-d', strtotime($this->input->post('labor_expdate'))),
		);
		$this->db->where('emp_id', $user_id);
		$this->db->where('document_name', 'labour');
		$this->db->update('employee_document_details', $data);


		//Emirates insert


		//photo
		if (isset($_FILES["emp_eid"])) {

			$photo_file = '';
			$allowedExts = array("jpeg", "jpg", "png", "doc", "pdf");
			$file_name = $_FILES["emp_eid"]["name"];

			$data['file_name'] = str_replace(' ', '_', $file_name);
			$temp = explode(".", $_FILES["emp_eid"]["name"]);
			$extension = end($temp);
			$other_file = '';
			if (($_FILES["emp_eid"]["size"] < 15728640) && in_array($extension, $allowedExts)) {
				if ($_FILES["emp_eid"]["error"] > 0) {
					$this->session->set_flashdata('error', 'Failed to upload - Please check file size and file format');
				} else {
					$timestamp1 = time();
					$file_tmp = $_FILES["emp_eid"]["tmp_name"];
					$photo_file = $timestamp1 . "_" . $_FILES['emp_eid']['name'];
					//$dest = "C:/xampp-new/htdocs/green_oasis/public/uploded_documents/emp_eid/".$photo_file;
					$dest = FCPATH . 'public/uploded_documents/emp_eid/' . $photo_file;
					if (move_uploaded_file($file_tmp, $dest)) {
						$query = $this->db->query("update employee_document_details set image_url='$photo_file' where emp_id='$user_id' and document_name='eid'");
					}
				}
			}
		}
		$data = array(
			'emp_id'  => $user_id,
			'document_number' => $this->input->post('EmiratesID'),
			'issue_date' => date('Y-m-d', strtotime($this->input->post('emirate_issue_date'))),
			'expiry_date' => date('Y-m-d', strtotime($this->input->post('emirate_expdate'))),
		);
		$this->db->where('emp_id', $user_id);
		$this->db->where('document_name', 'eid');
		$this->db->update('employee_document_details', $data);

		//salary insert
		// $data = array(
		// 	'emp_id'  => $user_id,
		// 	'gross_salary' => $this->input->post('GSalary'),
		// 	'basic_salary' => $this->input->post('BSalary'),
		// 	//'total_allowances' => $this->input->post('aallowance'),
		// 	//'total_allowances' => $this->input->post('tallowance'),
		// 	//'total_allowances' => $this->input->post('oallowance'),
		// 	//'total_deductions' => $this->input->post('Transportation_provided'),
		// 	//'total_deductions' => $this->input->post('Accomodation_provided'),
		// 	'overtime' => $this->input->post('OT'),

		// 	//'issue_date' => date('Y-m-d', strtotime($this->input->post('labor_date'))),
		// 	//'expiry_date' => date('Y-m-d', strtotime($this->input->post('emirate_expdate'))),
		// );
		// $this->db->insert('salary_structure', $data);

		if ($res) {
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 2, $page_name[1], 'users', 'user_id', $user_id);
			return true;
		} else {
			return false;
		}
	}
	function get_user_list()
	{
		$query = $this->db->query("select u.*  from users u  order by user_id");
		return $query->result();
	}

	function get_available_employees2($date, $lbr_type)
	{

		$this->db->select('u.user_id,u.user_name,dm.date');
		$this->db->from('users u');
		$this->db->join('dps_company_lbr_details dps', 'u.user_id = dps.emp_id', 'left');
		$this->db->join('dps_master dm', 'dps.dps_id = dm.dps_id', 'left');
		if ($lbr_type == 0) {
			$this->db->where('u.branch', 0);
		} else {
			$this->db->where('u.branch >', 0);
		}
		$this->db->group_start();
		$this->db->where('dm.date !=', $date);
		$this->db->or_where('dm.date IS NULL');
		$this->db->group_end();

		$query = $this->db->get();
		return $query->result();
	}

	function get_available_employees($date, $time, $lbr_type, $driver_id = '', $vehicle_id = '')
	{

		$cond = ($lbr_type == 0) ? 'u.branch = 0' : 'u.branch > 0';

		$cond2 = '';
		if ($driver_id != '') {
			$cond2 .= ' AND dm.driver_id = ' . $driver_id;
		}
		if ($vehicle_id != '') {
			$cond2 .= ' AND dm.vehicle_id = ' . $vehicle_id;
		}

		$this->db->select('u.user_id, u.user_name, u.user_last_name, u.branch');
		$this->db->from('users u');

		$this->db->where($cond);

		// Adjusted subquery with end_time check
		$this->db->where("NOT EXISTS (
		SELECT 1 
		FROM dps_company_lbr_details dps 
		LEFT JOIN dps_master dm ON dps.dps_id = dm.dps_id 
		WHERE 
			u.user_id = dps.emp_id 
			AND dm.date = " . $this->db->escape($date) . " 
			AND " . $this->db->escape($time) . " <= dm.end_time
			$cond2
	)");

		$query = $this->db->get();
		return $query->result();
	}


	function get_employees_by_designation($desg)
	{
		$query = $this->db->query("SELECT u.*, d.designation_name FROM users u JOIN designation_master d ON u.desig_id = d.did WHERE d.designation_name = '$desg'");
		return $query->result();
	}

	function get_driver_list()
	{
		$query = $this->db->query("SELECT u.*, d.designation_name FROM users u JOIN designation_master d ON u.desig_id = d.did WHERE d.designation_name = 'driver'");
		return $query->result();
	}

	function get_forman_list()
	{
		$query = $this->db->query("SELECT u.*, d.designation_name FROM users u JOIN designation_master d ON u.desig_id = d.did WHERE d.designation_name = 'Forman'");
		return $query->result();
	}

	function get_labour_list()
	{
		$query = $this->db->query("SELECT u.*, d.designation_name FROM users u JOIN designation_master d ON u.desig_id = d.did WHERE d.designation_name = 'Carpenter' OR d.designation_name = 'Painter' OR d.designation_name = 'Helper' OR d.designation_name = 'Driver'  ");
		return $query->result();
	}

	function get_country_list()
	{
		$query = $this->db->query("select * from country_master");
		return $query->result();
	}

	function get_active_user_list()
	{
		$query = $this->db->query("select u.* from users u where active=0 order by user_name");
		return $query->result();
	}

	function get_user_record_by_id($user_id)
	{
		$query = $this->db->query("SELECT * FROM users WHERE user_id = '$user_id'");
		return $query->result();
	}
	function get_user_record_by_id_pass($user_id)
	{
		$query = $this->db->query("SELECT document_number,emp_id,document_name, issue_date, expiry_date, emp_document_path FROM employee_document_details WHERE emp_id = '$user_id' AND document_name ='passport' ORDER BY emp_docId DESC LIMIT 1");

		return $query->result();
	}
	function get_user_record_by_id_visa($user_id)
	{
		$query = $this->db->query("SELECT issue_date,emp_id,posession,document_name,status,expiry_date,emp_document_path FROM employee_document_details WHERE emp_id = '$user_id' AND document_name ='visa' ORDER BY emp_docId DESC LIMIT 1");
		return $query->result();
	}
	function get_user_record_by_id_labor($user_id)
	{
		$query = $this->db->query("SELECT document_number,issue_date,expiry_date,emp_document_path FROM employee_document_details WHERE emp_id = '$user_id' AND document_name ='labour' ORDER BY emp_docId DESC LIMIT 1");
		return $query->result();
	}
	function get_user_record_by_id_emirat($user_id)
	{
		$query = $this->db->query("SELECT document_number,issue_date,expiry_date,emp_id,emp_document_path FROM employee_document_details WHERE emp_id = '$user_id' AND document_name ='eid' ORDER BY emp_docId DESC LIMIT 1");
		return $query->result();
	}
	function get_user_record_by_id_salary($user_id)
	{
		$query = $this->db->query("SELECT gross_salary,basic_salary,overtime FROM salary_structure WHERE emp_id = '$user_id' ORDER BY sid DESC LIMIT 1");
		return $query->result();
	}

	function delete_user_record($user_id)
	{
		// Delete record from the 'users' table
		$this->db->where('user_id', $user_id);
		$this->db->delete('users');

		// Delete record from the 'employee_document_details' table where document_name is 'emirats'
		$this->db->where('emp_id', $user_id);
		$this->db->where('document_name', 'emirats');
		$this->db->order_by('emp_docId', 'DESC');
		$this->db->limit(1);
		$this->db->delete('employee_document_details');

		// Delete record from the 'employee_document_details' table where document_name is 'laboar'
		$this->db->where('emp_id', $user_id);
		$this->db->where('document_name', 'laboar');
		$this->db->order_by('emp_docId', 'DESC');
		$this->db->limit(1);
		$this->db->delete('employee_document_details');

		// Delete record from the 'employee_document_details' table where document_name is 'visa'
		$this->db->where('emp_id', $user_id);
		$this->db->where('document_name', 'visa');
		$this->db->order_by('emp_docId', 'DESC');
		$this->db->limit(1);
		$this->db->delete('employee_document_details');

		// Delete record from the 'employee_document_details' table where document_name is 'passport'
		$this->db->where('emp_id', $user_id);
		$this->db->where('document_name', 'passport');
		$this->db->order_by('emp_docId', 'DESC');
		$this->db->limit(1);
		$this->db->delete('employee_document_details');

		// Delete record from the 'salary_structure' table
		$this->db->where('emp_id', $user_id);
		$this->db->delete('salary_structure');

		// You might not need to return anything here, depending on your use case.
	}

	function get_user_pp_info($usr_id)
	{
		$this->db->select('*');
		$this->db->where('emp_id', $usr_id);
		$this->db->where('document_name', 'passport');
		$res = $this->db->get('employee_document_details')->row_array();

		return $res;
	}
	/////////////////  Customer master start  ///////////////////
	function add_customer_data()
	{
		//$prifix='CM'.date('y');
		$prifix = 'CM';
		$this->load->model('Setup_model');
		$num = $this->Setup_model->get_next_code($prifix, 'cust_code', 'customer_master', 3) + 1;
		$digit = sprintf("%1$04d", $num);
		$Code = $prifix . $digit;

		$data = array(
			'cust_code' => $Code,
			'cust_name' => $this->input->post('cust_name'),
			'email_id' => $this->input->post('email'),
			'contact_no' => $this->input->post('contact_no'),
			'trn_no' => $this->input->post('trn_no'),
			//'btype' => $this->input->post('btype'),
			//'ctype' => $this->input->post('ctype'),
			'billing_address' => $this->input->post('billing_addr1'),
			'billing_city' => $this->input->post('billing_city'),
			'billing_state' => $this->input->post('billing_state'),
			'billing_country' => $this->input->post('billing_country'),
			'billing_po_box' => $this->input->post('billing_po'),

			'shipping_address' => $this->input->post('shipping_addr1'),
			'shipping_city' => $this->input->post('shipping_city'),
			'shipping_state' => $this->input->post('shipping_state'),
			'shipping_country' => $this->input->post('shipping_country'),
			'shipping_po_box' => $this->input->post('shipping_po'),

			//'remark' => $this->input->post('remark'),
			'created_date' => date('Y-m-d H:i:s'),
			'created_by'    => $this->session->userdata('user_id'),
		);
		$this->db->insert('customer_master', $data);
		$insert_id = $this->db->insert_id();

		$grp_no = 30;
		$data1 = array(
			'account_name' => $this->input->post('cust_name') . ' ' . $Code,
			'group_no' => $grp_no,
			'customer_id' => $insert_id,
			'opening_bal_type' => 'Dr',
		);
		$this->db->insert('general_ledger', $data1);
		$ledger_id = $this->db->insert_id();

		if (isset($_POST['cp_name'])) {
			for ($i = 0; $i < count($_POST['cp_name']); $i++) {
				if ($_POST['cp_name'][$i] != '') {
					$data = array(
						'cust_id' => $insert_id,
						'cp_name' => $_POST['cp_name'][$i],
						'cp_desig' => $_POST['cp_desig'][$i],
						'cp_mobile' => $_POST['cp_mobile'][$i],
						'cp_email' => $_POST['cp_email'][$i],
					);
					$this->db->insert('customer_contact_person', $data);
				}
			}
		}

		if ($insert_id) {




			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 1, $page_name[1], 'customer_master', 'customer_id', $insert_id);
		}
		return $insert_id;
	}

	function update_customer_data($id)
	{
		$data = array(
			'cust_name' => $this->input->post('cust_name'),
			'email_id' => $this->input->post('email'),
			'contact_no' => $this->input->post('contact_no'),
			'trn_no' => $this->input->post('trn_no'),
			//'btype' => $this->input->post('btype'),
			//'ctype' => $this->input->post('ctype'),
			'billing_address' => $this->input->post('billing_addr1'),
			'billing_city' => $this->input->post('billing_city'),
			'billing_state' => $this->input->post('billing_state'),
			'billing_country' => $this->input->post('billing_country'),
			'billing_po_box' => $this->input->post('billing_po'),

			'shipping_address' => $this->input->post('shipping_addr1'),
			'shipping_city' => $this->input->post('shipping_city'),
			'shipping_state' => $this->input->post('shipping_state'),
			'shipping_country' => $this->input->post('shipping_country'),
			'shipping_po_box' => $this->input->post('shipping_po'),

			//'active' => $this->input->post('active'),
		);
		$this->db->where('customer_id', $id);
		$res = $this->db->update('customer_master', $data);

		if (isset($_POST['cp_name'])) {
			for ($i = 0; $i < count($_POST['cp_name']); $i++) {
				if ($_POST['cp_name'][$i] != '') {
					$data = array(
						'cust_id' => $id,
						'cp_name' => $_POST['cp_name'][$i],
						'cp_desig' => $_POST['cp_desig'][$i],
						'cp_mobile' => $_POST['cp_mobile'][$i],
						'cp_email' => $_POST['cp_email'][$i],
					);
					$this->db->insert('customer_contact_person', $data);
				}
			}
		}
		if (isset($_POST['cp_name_old'])) {
			for ($i = 0; $i < count($_POST['cp_name_old']); $i++) {
				$trans_id = $_POST['trans_id'][$i];
				$data = array(
					'cust_id' => $id,
					'cp_name' => $_POST['cp_name_old'][$i],
					'cp_desig' => $_POST['cp_desig_old'][$i],
					'cp_mobile' => $_POST['cp_mobile_old'][$i],
					'cp_email' => $_POST['cp_email_old'][$i],
				);
				$this->db->where('cp_id', $trans_id);
				$res = $this->db->update('customer_contact_person', $data);
			}
		}
		if ($res) {
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 2, $page_name[1], 'customer_master', 'customer_id', $id);
			return true;
		} else {
			return false;
		}
	}

	function get_customer_by_id($id)
	{
		$query = $this->db->query("select * from customer_master where customer_id='$id'");
		return $query->result();
	}

	function get_customer_cp_details($id)
	{
		$query = $this->db->query("select * from customer_contact_person where cust_id='$id'");
		return $query->result();
	}
	function get_customer_cp_details_id($id)
	{
		$query = $this->db->query("select * from customer_contact_person where cp_id ='$id'");
		return $query->result();
	}
	function update_cp_details_id($id, $data)
	{
		$this->db->where('cp_id', $id);
		return $this->db->update('customer_contact_person', $data);
	}


	function get_customer_list()
	{
		$query = $this->db->query("select * from customer_master order by customer_name");
		return $query->result();
	}

	function get_active_customer_list()
	{
		$query = $this->db->query("select * from customer_master where active=0 order by customer_name");
		return $query->result();
	}

	/////////////////  Supplier master start  ///////////////////
	function add_supplier_data()
	{
		//$prifix='CM'.date('y');
		$prifix = 'SP';
		$this->load->model('Setup_model');
		$num = $this->Setup_model->get_next_code($prifix, 'supplier_code', 'supplier_master', 3) + 1;
		$digit = sprintf("%1$04d", $num);
		$Code = $prifix . $digit;

		$data = array(
			'supplier_code' => $Code,
			'supplier_name' => $this->input->post('cust_name'),
			'supplier_type' => $this->input->post('stype'),
			'website' => $this->input->post('website'),
			'email_id' => $this->input->post('email'),
			'contact_no' => $this->input->post('contact_no'),
			'trn_no' => $this->input->post('trn_no'),
			'billing_address' => $this->input->post('billing_addr1'),
			'billing_city' => $this->input->post('billing_city'),
			'billing_state' => $this->input->post('billing_state'),
			'billing_country' => $this->input->post('billing_country'),
			'billing_po_box' => $this->input->post('billing_po'),

			'shipping_address' => $this->input->post('shipping_addr1'),
			'shipping_city' => $this->input->post('shipping_city'),
			'shipping_state' => $this->input->post('shipping_state'),
			'shipping_country' => $this->input->post('shipping_country'),
			'shipping_po_box' => $this->input->post('shipping_po'),

			'bank_name' => $this->input->post('bname'),
			'bank_account' => $this->input->post('acc_no'),
			'bank_branch' => $this->input->post('branch'),
			'bank_IBAN' => $this->input->post('iban'),
			'bank_swift' => $this->input->post('swift'),

			'intermidiate_Bname' => $this->input->post('int_bname'),
			'intermidiate_Bacc' => $this->input->post('int_acc_no'),
			'intermidiate_Bbranch' => $this->input->post('int_branch'),
			'intermidiate_IBAN' => $this->input->post('int_iban'),
			'intermidiate_swift' => $this->input->post('int_swift'),
			//'payment_terms' => $this->input->post('pterms'),
			//'remark' => $this->input->post('remark'),
			'created_date' => date('Y-m-d H:i'),
			'created_by'    => $this->session->userdata('user_id'),
		);
		$this->db->insert('supplier_master', $data);
		$insert_id = $this->db->insert_id();
		if (isset($_POST['cp_name'])) {
			for ($i = 0; $i < count($_POST['cp_name']); $i++) {
				if ($_POST['cp_name'][$i] != '') {
					$data = array(
						'supp_id' => $insert_id,
						'sc_name' => $_POST['cp_name'][$i],
						'sc_mobile' => $_POST['cp_mobile'][$i],
						'sc_email' => $_POST['cp_email'][$i],
					);
					$this->db->insert('supplier_contacts', $data);
				}
			}
		}

		if ($insert_id) {

			$grp_no = 29;
			$data1 = array(
				'account_name' => $this->input->post('cust_name') . ' ' . $Code,
				'group_no' => $grp_no,
				'supplier_id' => $insert_id,
				'opening_bal_type' => 'Dr',
			);
			$this->db->insert('general_ledger', $data1);
			$ledger_id = $this->db->insert_id();


			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 1, $page_name[1], 'supplier_master', 'supplier_id', $insert_id);
		}
		return $insert_id;
	}

	function update_supplier_data($id)
	{
		$data = array(
			'supplier_name' => $this->input->post('cust_name'),
			'email_id' => $this->input->post('email'),
			'contact_no' => $this->input->post('contact_no'),
			'trn_no' => $this->input->post('trn_no'),
			'billing_address' => $this->input->post('billing_addr1'),
			'billing_city' => $this->input->post('billing_city'),
			'billing_state' => $this->input->post('billing_state'),
			'billing_country' => $this->input->post('billing_country'),
			'billing_po_box' => $this->input->post('billing_po'),

			'shipping_address' => $this->input->post('shipping_addr1'),
			'shipping_city' => $this->input->post('shipping_city'),
			'shipping_state' => $this->input->post('shipping_state'),
			'shipping_country' => $this->input->post('shipping_country'),
			'shipping_po_box' => $this->input->post('shipping_po'),


			'bank_name' => $this->input->post('bname'),
			'bank_account' => $this->input->post('acc_no'),
			'bank_branch' => $this->input->post('branch'),
			'bank_IBAN' => $this->input->post('iban'),
			'bank_swift' => $this->input->post('swift'),

			'intermidiate_Bname' => $this->input->post('int_bname'),
			'intermidiate_Bacc' => $this->input->post('int_acc_no'),
			'intermidiate_Bbranch' => $this->input->post('int_branch'),
			'intermidiate_IBAN' => $this->input->post('int_iban'),
			'intermidiate_swift' => $this->input->post('int_swift'),
			'active' => $this->input->post('active'),
		);
		$this->db->where('supplier_id', $id);
		$this->db->update('supplier_master', $data);
		if (isset($_POST['cp_name'])) {
			for ($i = 0; $i < count($_POST['cp_name']); $i++) {
				if ($_POST['cp_name'][$i] != '') {
					$data = array(
						'supp_id' => $id,
						'sc_name' => $_POST['cp_name'][$i],
						'sc_mobile' => $_POST['cp_mobile'][$i],
						'sc_email' => $_POST['cp_email'][$i],
					);
					$this->db->insert('supplier_contacts', $data);
				}
			}
		}
		if ($res) {
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 2, $page_name[1], 'supplier_master', 'supplier_id', $id);
			return true;
		} else {
			return false;
		}
	}

	function get_supplier_by_id($id)
	{
		$query = $this->db->query("select * from supplier_master where supplier_id='$id'");
		return $query->result();
	}

	function get_supplier_list()
	{
		$query = $this->db->query("select * from supplier_master order by supplier_name");
		return $query->result();
	}

	function get_active_supplier_list()
	{
		$query = $this->db->query("select * from supplier_master where active=0 order by supplier_name");
		return $query->result();
	}

	function get_supplier_contacts($id)
	{
		$query = $this->db->query("select * from supplier_contacts where supp_id='$id' order by sc_name");
		return $query->result();
	}

	function removeBackground($outputPath)
	{
		$apiKey = 'oGbViEZNi2kAkQqwXbEQQ1ZU'; // Replace with your API key

		if (isset($_FILES['employee_sign']) && $_FILES['employee_sign']['error'] === UPLOAD_ERR_OK) {
			$tempFile = $_FILES['employee_sign']['tmp_name'];
			$originalName = pathinfo($_FILES['employee_sign']['name'], PATHINFO_FILENAME);

			// Prepare request to Remove.bg
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://api.remove.bg/v1.0/removebg');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, [
				'image_file' => new CURLFile($tempFile),
				'size' => 'auto'
			]);
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'X-Api-Key: ' . $apiKey
			]);

			$response = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			if ($httpCode === 200) {
				// Create the folder if it doesn't exist
				// $folder = dirname($outputFile);
				// if (!is_dir($folder)) {
				// 	mkdir($folder, 0777, true);
				// }

				// Save the image
				file_put_contents($outputPath, $response);
				echo "Background removed and saved to: <strong>$outputPath</strong>";
			} else {
				echo "Remove.bg API error: " . curl_error($ch) . "<br>";
				echo "Response: " . $response;
			}

			curl_close($ch);
		} else {
			echo "No file uploaded or upload error.";
		}
	}

	
}
