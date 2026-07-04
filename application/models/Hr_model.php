<?php
class Hr_model extends CI_Model
{


	/////////////////  allowance & deductions start  ///////////////////
	function add_allowances_data()
	{
		$data = array(
			'allowance_type' => $this->input->post('allowance_type'),
			'allowance_name' => $this->input->post('allowance_name'),
			'created_by' => $this->session->userdata('user_id'),
			'created_date' => date('Y-m-d'),
		);
		$this->db->insert('allowance_master', $data);
		$insert_id = $this->db->insert_id();
		if ($insert_id) {
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 1, $page_name[1], 'allowance_master', 'sno', $insert_id);
		}
		return $insert_id;
	}

	function update_allowances($id)
	{
		$data = array(
			'allowance_type' => $this->input->post('allowance_type'),
			'allowance_name' => $this->input->post('allowance_name'),
		);
		$this->db->where('sno', $id);
		$this->db->update('allowance_master', $data);
		if ($id) {
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 2, $page_name[1], 'allowance_master', 'sno', $id);
		}
		return $id;
	}
	function get_allowances_by_id($id)
	{
		$query = $this->db->query("select * from allowance_master where sno='$id'");
		return $query->result();
	}

	function get_allowances_list()
	{
		$query = $this->db->query("SELECT * FROM allowance_master ORDER BY allowance_type, allowance_name");
		return $query->result();
	}


	function delete_allowance($id)
	{
		$this->db->where('sno', $id);
		$this->db->delete('allowance_master');
	}
	/////////////////  employee_leave start  ///////////////////
	function add_employee_leave_data()
	{

		$prifix = 'LV';
		$this->load->model('Setup_model');
		$num = $this->Setup_model->get_next_code($prifix, 'leave_code', 'employee_leave', 3) + 1;
		$digit = sprintf("%1$04d", $num);
		$LA_code = $prifix . $digit;

		$data = array(
			'employee_id' => $this->input->post('employee_id'),
			'leave_code' => $LA_code,
			'leave_type' => $this->input->post('ltype_id'),
			'start_date' => date('Y-m-d', strtotime($this->input->post('start_date'))),
			'end_date' => date('Y-m-d', strtotime($this->input->post('end_date'))),
			'reason' => $this->input->post('reason'),
			'replcement' => $this->input->post('replcement'),
			'application_date' => date('Y-m-d', strtotime($this->input->post('application_date'))),
			'outside_contact' => $this->input->post('outside_contact'),
			'joindate_fromlastLeave' => date('Y-m-d', strtotime($this->input->post('last_date'))),
			'created_by' => $this->session->userdata('user_id'),
			'created_date' => date('Y-m-d'),
		);

		// Check if the record already exists
		$this->db->where('employee_id', $data['employee_id']);
		$this->db->where('start_date', $data['start_date']);
		$this->db->where('end_date', $data['end_date']);
		$query = $this->db->get('employee_leave');

		if ($query->num_rows() > 0) {
			// Record already exists, display flash message
			$this->session->set_flashdata('error', 'Leave record already exists for the selected employee and dates.');
		} else {
			// Record does not exist, insert into the database
			$this->db->insert('employee_leave', $data);
			$insert_id = $this->db->insert_id();
		}


		/////////////////// file upload ////////////////////
		if ($insert_id && !empty($_FILES["documents"]["name"][0])) {

    $allowedExts = array("jpeg", "jpg", "png", "doc", "pdf");

    for ($i = 0; $i < count($_FILES['documents']["name"]); $i++) {

        if (!empty($_FILES['documents']["name"][$i])) {

            $temp = explode(".", $_FILES["documents"]["name"][$i]);
            $extension = strtolower(end($temp));

            if ($_FILES["documents"]["size"][$i] < 15728640 && in_array($extension, $allowedExts)) {

                if ($_FILES["documents"]["error"][$i] == 0) {

                    $file_tmp = $_FILES["documents"]["tmp_name"][$i];

                    $file_name = time() . "_" . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['documents']['name'][$i]);

                    $upload_path = FCPATH . 'public/uploaded_documents/';

                    if (!is_dir($upload_path)) {
                        mkdir($upload_path, 0777, true);
                    }

                    move_uploaded_file($file_tmp, $upload_path . $file_name);

                    $data1 = array(
                        'leave_id' => $insert_id,
                        'employee_id' => $this->session->userdata('user_id'),
                        'document_path' => $file_name,
                    );

                    $this->db->insert('employee_leave_documents', $data1);
                }
            }
        }
    }
}
		$user_se_id = $this->session->userdata('user_id');
		$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
		$ci = get_instance();
		$ci->load->helper('log');
		$log_msg = add_log_entry($user_se_id, 1, $page_name[1], 'employee_leave', 'leave_id', $insert_id);

		return $insert_id;
	}

	function update_employee_leave($id)
	{
		$data_leave = array(
			'employee_id' => $this->input->post('employee_id'),
			'leave_type' => $this->input->post('leave_type'),
			'start_date' => date('Y-m-d', strtotime($this->input->post('start_date'))),
			'end_date' => date('Y-m-d', strtotime($this->input->post('end_date'))),
			'reason' => $this->input->post('reason'),
			'replcement' => $this->input->post('replcement'),
			// 'application_date' => date('Y-m-d', strtotime($this->input->post('application_date'))),
			'joindate_fromlastLeave' => date('Y-m-d', strtotime($this->input->post('joindate_fromlastLeave'))),
			'outside_contact' => $this->input->post('outside_contact'),
		);

		$this->db->where('leave_id', $id);
		$this->db->update('employee_leave', $data_leave);

		

		/////////////////// file upload ////////////////////
		if ($id) {
			if ($_FILES["documents"]) {
				$allowedExts = array("jpeg", "jpg", "png", "doc", "pdf");
				for ($i = 0; $i < count($_FILES['documents']["name"]); $i++) {
					if ($_FILES['documents']["name"][$i] != '') {
						$data['file_name'] = $_FILES["documents"]["name"][$i];

						$fname = $_FILES["documents"]["name"][$i];
						$temp = explode(".", $_FILES["documents"]["name"][$i]);
						$extension = end($temp);
						$other_file = '';
						if (($_FILES["documents"]["size"][$i] < 15728640) && in_array($extension, $allowedExts)) {
							if ($_FILES["documents"]["error"][$i] > 0) {
								$this->session->set_flashdata('error', 'Failed to upload - Please check file size and file format');
							} else {
								$timestamp1 = time();
								$file_tmp = $_FILES["documents"]["tmp_name"][$i];
								$other_file = $timestamp1 . "_" . $_FILES['documents']['name'][$i];
move_uploaded_file($file_tmp, FCPATH . 'public/uploaded_documents/' . $other_file);
								$data1 = array(
									'leave_id' => $id,
									'employee_id' => $this->input->post('employee_id'),
									'document_path' => $other_file,
								);
								$this->db->insert('employee_leave_documents', $data1);
							}
						}
					}
				}
			}
		}
		// Log entry
		if ($id) {
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 2, $page_name[1], 'employee_leave', 'leave_id', $id);
		}

		return $id;
	}

	// function get_employee_leave_by_id($id)
	// {
	// 	$query = $this->db->query("select  j.*, u.*,u.user_name as name from employee_leave j, users u where j.employee_id=u.user_id AND leave_id='$id'  order by application_date desc ");
	// 	return $query->result();
	// }

function get_employee_leave_by_id($id)
{
    $this->db->select('
        j.*,
        u.employee_name as employee_name,
        r.employee_name as replacement_name
    ');

    $this->db->from('employee_leave j');

    // Main employee
    $this->db->join('employee_master u', 'u.employee_id = j.employee_id', 'left');

    // Replacement employee
    $this->db->join('employee_master r', 'r.employee_id = j.replcement', 'left');

    $this->db->where('j.leave_id', $id);

    return $this->db->get()->row();
}


	function get_employee_leave_doc_id($id)
	{
		$query = $this->db->query("select  * from employee_leave_documents  where leave_id='$id' ");
		return $query->result();
	}

	// function get_employee_leave_list()
	// {
	// 	$query = $this->db->query("select  la.*,j.*, u.user_name as name,u.user_id from employee_leave j, leave_approval la, users u where j.employee_id=u.user_id  and la.leave_id=j.leave_id order by application_date desc ");
	// 	return $query->result();
	// }
	function get_employee_leave_list()
	{
		$query = $this->db->query("
        SELECT * 
        FROM employee_leave AS e 
        JOIN employee_master AS u ON e.employee_id = u.employee_id 
        LEFT JOIN leave_approval AS la ON e.leave_id = la.approval_leave_id 
        ORDER BY e.application_date DESC
    ");
		return $query->result();
	}

	function get_leave_approval_list()
	{
		$query = $this->db->query("select * from leave_approval ");
		return $query->result();
	}

	function delete_leave_application($id)
	{
		$this->db->where('approval_leave_id', $id);
		$this->db->delete('leave_approval');
		$this->db->where('leave_id', $id);
		$this->db->delete('employee_leave');
	}

	/////////////////////////approval////////////////////////////////////////////////////

	public function add_approval_leave()
{
    $data = array(
        'approval_leave_id' => $this->input->post('hide_leave_id'),
        'approved_date' => date('Y-m-d', strtotime($this->input->post('approve_date'))),
        'leave_status' => $this->input->post('leave_status'),
        'approve_start_date' => date('Y-m-d', strtotime($this->input->post('approve_start_date'))),
        'approve_end_date' => date('Y-m-d', strtotime($this->input->post('approve_end_date'))),
        'remark' => $this->input->post('approve_remark'),
        'admin_md' => $this->input->post('approve_admin'),
        'hr' => $this->input->post('approve_hr'),
    );

    // 1. INSERT approval history (log)
    $this->db->insert('leave_approval', $data);
    $insert_id = $this->db->insert_id();

    // 2. UPDATE main leave record (IMPORTANT FIX)
    $leave_id = $this->input->post('hide_leave_id');

    // $update = array(
    //     'leave_status' => $this->input->post('leave_status')
    // );

    // $this->db->where('leave_id', $leave_id);
    // $this->db->update('employee_leave', $update);

    // log
    if ($insert_id) {
        $user_se_id = $this->session->userdata('user_id');
        $page_name = explode('index.php/', $_SERVER['PHP_SELF']);
        $ci = get_instance();
        $ci->load->helper('log');
        add_log_entry($user_se_id, 2, $page_name[1], 'leave_approval', 'app_id', $insert_id);
    }

    return $insert_id;
}
	public function leave_approval_list()
	{
		$query = $this->db->query("SELECT * FROM leave_approval ORDER BY approved_date DESC ");
		return $query->result();
	}

	public function leave_hr_admin_list()
	{
		$query = $this->db->query("
        SELECT 
            a.*,
            u1.user_name AS hr_user_name, 
            u1.user_id AS hr_user_id, 
            u2.user_name AS admin_md_user_name, 
            u2.user_id AS admin_md_user_id
        FROM approval_setup a
        JOIN users u1 ON a.approve_hr = u1.user_id
        JOIN users u2 ON a.approve_admin_md = u2.user_id and approve_type='Leave'
    ");
		return $query->result();
	}


	/////////////////  Joining start  ///////////////////
	function add_joining_application_data()
	{
		$prifix = 'JA';
		$this->load->model('Setup_model');
		$num = $this->Setup_model->get_next_code($prifix, 'joining_code	', 'employee_joining', 3) + 1;
		$digit = sprintf("%1$04d", $num);
		$JA_code = $prifix . $digit;


		$data = array(
			'employee_id' => $this->input->post('employee_id'),
			'joining_code' => $JA_code,
			'joining_type' => $this->input->post('joining_type'),
			'joining_date' => date('Y-m-d', strtotime($this->input->post('joining_date'))),
			'offer_letter' => $this->input->post('offer_letter'),
			'remark' => $this->input->post('remark'),
			'created_by' => $this->session->userdata('user_id'),
			'created_date' => date('Y-m-d'),
		);
		$this->db->insert('employee_joining', $data);
		$insert_id = $this->db->insert_id();
		if ($insert_id) {
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 2, $page_name[1], 'employee_joining', 'jid', $insert_id);
		}
		return $insert_id;
	}

	function update_joining_application($id)
	{
		$data = array(
			'employee_id' => $this->input->post('employee_id_hidden'),
			'joining_type' => $this->input->post('joining_type'),
			'joining_date' => date('Y-m-d', strtotime($this->input->post('joining_date'))),
			'offer_letter' => $this->input->post('offer_letter'),
			//'created_by'  => $this->session->userdata('user_id'),
			'created_date' => date('Y-m-d'),

		);
		$this->db->where('jid', $id);
		$this->db->update('employee_joining', $data);
		if ($id) {
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 2, $page_name[1], 'employee_joining', 'jid', $id);
		}
		return $id;
	}
	public function get_employee_joining_by_id($id)
{
    $this->db->select('j.*, COALESCE(u.user_name, j.employee_id) as user_name');
    $this->db->from('employee_joining j');
    $this->db->join('users u', 'j.employee_id = u.user_id', 'left');
    $this->db->where('j.jid', $id);
    return $this->db->get()->row();
}

	// function get_employee_joining_list()
	// {
	// 	$query = $this->db->query("select j.*, u.user_name as name from employee_joining j, users u where j.employee_id=u.user_id order by joining_date desc ");
	// 	return $query->result();
	// }

	public function get_employee_joining_list()
{
    $this->db->select('j.*, u.user_name as name');
    $this->db->from('employee_joining j');
    $this->db->join('users u', 'j.employee_id = u.user_id', 'left');
    $this->db->order_by('j.joining_date', 'desc');
    return $this->db->get()->result();
}
	function get_joining_new_list()
{
    $query = $this->db->query("
        SELECT user_id, user_name
        FROM users
        WHERE user_id NOT IN (
            SELECT employee_id
            FROM employee_joining
            WHERE employee_id IS NOT NULL
        )
        ORDER BY user_name ASC
    ");
    return $query->result();
}



	function delete_joining_application($id)
	{
		$this->db->where('jid', $id);
		$this->db->delete('employee_joining');
	}

	//////////////////////////////////////////function start resignation application//////////////////

	public function add_resignation()
	{

		$prifix = 'RA';
		$this->load->model('Setup_model');
		$num = $this->Setup_model->get_next_code($prifix, 'resign_code	', 'employee_resignation', 3) + 1;
		$digit = sprintf("%1$04d", $num);
		$RA_code = $prifix . $digit;



		$data = array(
			'employee_id' => $this->input->post('employee_id'),
			'resign_code' => $RA_code,
			'resignation_date' => date('Y-m-d', strtotime($this->input->post('resignation_date'))),
			'last_working_date' => date('Y-m-d', strtotime($this->input->post('last_working_date'))),
			'notice_days' => $this->input->post('notice_days'),
			'reason' => $this->input->post('reason'),
		);

		$this->db->insert('employee_resignation', $data);
		$insert_id = $this->db->insert_id();

		if ($insert_id) {
			if (!empty($_FILES["documents_res"]["name"][0])) {

    $allowedExts = array("jpeg", "jpg", "png", "doc", "pdf");
    $upload_path = FCPATH . 'public/uploaded_documents/';

    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0777, true);
    }

    foreach ($_FILES['documents_res']["name"] as $key => $filename) {

        if (!empty($filename)) {

            $temp = explode(".", $filename);
            $extension = strtolower(end($temp));

            if (in_array($extension, $allowedExts)) {

                $file_tmp = $_FILES["documents_res"]["tmp_name"][$key];

                $other_file = time() . "_" . rand(1000,9999) . "_" . $filename;

                move_uploaded_file($file_tmp, $upload_path . $other_file);

                $data1 = array(
                    'resig_id' => $insert_id,
                    'employee_id' => $this->input->post('employee_id'),
                    'document_name' => $this->input->post('document_types')[$key],
                    'document_path' => $other_file,
                );

                $this->db->insert('employee_resignation_documents', $data1);
            }
        }
    }
}
		}

		if ($insert_id) {
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 1, $page_name[1], 'employee_resignation', 'resig_id', $insert_id);
		}
		return $insert_id;
	}

	function update_resigning_application($id)
	{
		$data = array(
			'employee_id' => $this->input->post('employee_id'),
			'resignation_date' => date('Y-m-d', strtotime($this->input->post('resignation_date'))),
			'last_working_date' => date('Y-m-d', strtotime($this->input->post('last_working_date'))),
			'notice_days' => $this->input->post('notice_days'),
			'reason' => $this->input->post('reason'),
		);

		$this->db->where('resig_id', $id);
		$res = $this->db->update('employee_resignation', $data);

		if ($id) {
			if (!empty($_FILES["documents_res"]["name"][0])) {

    $allowedExts = array("jpeg", "jpg", "png", "doc", "pdf");
    $upload_path = FCPATH . 'public/uploaded_documents/';

    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0777, true);
    }

    foreach ($_FILES['documents_res']["name"] as $key => $filename) {

        if (!empty($filename)) {

            $temp = explode(".", $filename);
            $extension = strtolower(end($temp));

            if (in_array($extension, $allowedExts)) {

                $file_tmp = $_FILES["documents_res"]["tmp_name"][$key];

                $other_file = time() . "_" . rand(1000,9999) . "_" . $filename;

                move_uploaded_file($file_tmp, $upload_path . $other_file);

                $data1 = array(
                    'resig_id' => $id,
                    'employee_id' => $this->input->post('employee_id_hidden'),
                    'document_name' => $this->input->post('document_types')[$key],
                    'document_path' => $other_file,
                );

                $this->db->insert('employee_resignation_documents', $data1);
            }
        }
    }
}
		}

		if ($res) {
			// Log the update operation
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 2, $page_name[1], 'employee_resignation', 'resig_id', $id);

			return true;
		} else {
			// Handle the case where the update operation fails
			return false;
		}
	}


	function get_employee_resignation_list()
{
    $this->db->select('r.*, u.employee_name as name');
    $this->db->from('employee_resignation r');
    $this->db->join('employee_master u', 'r.employee_id = u.employee_id', 'inner');
    $this->db->order_by('r.resignation_date', 'desc');
    $query = $this->db->get();
    return $query->result();
}



	function get_employee_document_doc_id($id)
	{
		$query = $this->db->query("select  * from employee_resignation_documents  where resig_id='$id' ");
		return $query->result();
	}


	function get_resignation_active_list()
	{
		$query = $this->db->query("
        SELECT *, user_id
        FROM users
        WHERE user_id NOT IN (
            SELECT employee_id
            FROM employee_resignation
        )
        ORDER BY user_name
    ");
		return $query->result();
	}


	// function get_employee_resigning_by_id($id)
	// {
	// 	$query = $this->db->query("SELECT r.*, u.*, u.joining_date as jdate
	// 							   FROM employee_resignation AS r
	// 							   INNER JOIN users AS u 
	// 							   WHERE resig_id = '$id'
	// 							   ORDER BY r.resignation_date DESC");

	// 	return $query->result();
	// }

	function get_employee_resigning_by_id($id)
{
    $this->db->select('r.*, u.*, u.joining_date as jdate');
    $this->db->from('employee_resignation r');
    $this->db->join('employee_master u', 'r.employee_id = u.employee_id', 'inner');
    $this->db->where('r.resig_id', $id);
    $query = $this->db->get();

    return $query->row(); // return a single row, not an array
}

	function delete_resignation_application($id)
	{
		$this->db->where('resig_id', $id);
		$this->db->delete('employee_resignation');
	}

	///this is new inseration passport relese data/////////////////////////////////////////////////////

	function add_passport_release()
	{
		$data = array(
			'emp_id' => $this->input->post('user_id'),
			'document_name' => 'passport',
			'status' => 'passport release',
			'document_number' => $this->input->post('doc_no'),
			'issue_date' => date('Y-m-d', strtotime($this->input->post('issue_date'))),
			'expiry_date' => date('Y-m-d', strtotime($this->input->post('exp_date'))),
			'outdate' => date('Y-m-d', strtotime($this->input->post('outdate'))),
			'indate' => date('Y-m-d', strtotime($this->input->post('indate'))),
			'posession' => $this->input->post('location'),
			'reason' => $this->input->post('reason'),
			'remark ' => $this->input->post('remark'),
		);
		$this->db->insert('employee_document_details', $data);
		$insert_id = $this->db->insert_id();

		if ($insert_id) {
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 1, $page_name[1], 'employee_document_details', 'emp_docId', $insert_id);
		}
		return $insert_id;
	}

	function update_passport_re($id)
	{
		$data = array(
			'emp_id' => $this->input->post('employee_id_hidden'),
			'document_name' => 'passport',
			'status' => 'passport release',
			'outdate' => date('Y-m-d', strtotime($this->input->post('outdate'))),
			'indate' => date('Y-m-d', strtotime($this->input->post('indate'))),
			'posession' => $this->input->post('location'),
			'reason' => $this->input->post('reason'),
			'remark' => $this->input->post('remark'),
		);

		$this->db->where('emp_docId', $id);
		$res = $this->db->update('employee_document_details', $data);

		if ($res) {
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 2, $page_name[1], 'employee_document_details', 'emp_docId', $id);
			return true;
		} else {
			return false;
		}
	}


	function get_passport_release_list()
	{
		$query = $this->db->query("SELECT e.*, u.* FROM employee_document_details AS e JOIN employee_master AS u ON e.emp_id = u.employee_id WHERE e.status = 'passport release' ORDER BY e.emp_docId DESC ");
		return $query->result();
	}
	function get_passport_release_list_by_id($id)
{
    $query = $this->db->query("
        SELECT e.*, u.*, e.remark as rem 
        FROM employee_document_details AS e 
        JOIN employee_master AS u ON e.emp_id = u.employee_id 
        WHERE e.status = 'passport release' 
          AND e.emp_docId = '$id'
        ORDER BY e.emp_docId DESC 
        LIMIT 1
    ");
    
    return $query->row(); // <-- return single object
}




	function get_user_record_by_id($id)
	{
		$query = $this->db->query("SELECT u.*, e.* FROM users u JOIN employee_document_details e ON u.user_id = e.emp_id WHERE e.emp_docId = '$id' AND document_name ='passport' ORDER BY emp_docId DESC LIMIT 1");
		return $query->result();
	}



	function delete_passport_release($id)
	{
		$this->db->where('emp_docId', $id);
		$this->db->delete('employee_document_details');
	}
	
	///////////////////////////////////////////start overtime form model /////////////////////////////////

	function add_emp_overtime_data()
	{
		$data = array(
			'employee_id' => $this->input->post('employee_id'),
			'date_ot' => date('Y-m-d', strtotime($this->input->post('date_ot'))),
			'overtime' => $this->input->post('ot'),
			// 'ot_type' => $this->input->post('ot_type'),     
			'remark' => $this->input->post('remark'),
		);


		// Check if a record with the same employee ID and overtime date already exists
		$this->db->where('employee_id', $data['employee_id']);
		$this->db->where('date_ot', $data['date_ot']);
		$query = $this->db->get('employee_overtime');

		if ($query->num_rows() > 0) {
			// Record already exists, display flash message
			$this->session->set_flashdata('error', 'Employee overtime record already exists.');
			return false;
		} else {
			// Record does not exist, insert into the database
			$this->db->insert('employee_overtime', $data);
			$insert_id = $this->db->insert_id();

			if ($insert_id) {
				$user_se_id = $this->session->userdata('user_id');
				$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
				$ci = get_instance();
				$ci->load->helper('log');
				$log_msg = add_log_entry($user_se_id, 1, $page_name[1], 'employee_overtime', 'emp_oid', $insert_id);
			}
			return $insert_id;
		}
	}



	function update_emp_overtime($id)
	{
		$data = array(
			'employee_id' => $this->input->post('employee_id'),		
			'date_ot' => date('Y-m-d', strtotime($this->input->post('date_ot'))),
			'overtime' => $this->input->post('overtime'),
			// 'ot_type' => $this->input->post('ot_type'),     
			'remark' => $this->input->post('remark'),
		);

		$this->db->where('emp_oid', $id);
		$res = $this->db->update('employee_overtime', $data);

		if ($res) {
			// Log the update operation
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 2, $page_name[1], 'employee_overtime', 'emp_oid', $id);
			return true;
		} else {
			// Handle the case where the update operation fails
			return false;
		}
	}


	function get_emp_overtime_list()
	{
		$query = $this->db->query("select r.*, u.employee_name as name from employee_overtime r, employee_master u where r.employee_id=u.employee_id order by date_ot desc ");
		return $query->result();
	}

	function get_emp_overtime_by_id($id)
	{
		$query = $this->db->query("SELECT o.*, u.*,o.remark as rem
								   FROM employee_overtime AS o
								    JOIN employee_master AS u ON o.employee_id = u.employee_id
								   WHERE emp_oid = '$id'
								   ORDER BY o.date_ot DESC");

		 return $query->row();
	}

	

	function delete_emp_overtime($id)
	{
		$this->db->where('emp_oid', $id);
		$this->db->delete('employee_overtime');
	}
	//////////////////////////////////////start attendance/////////////////////////////////////////////
	function add_emp_attendance_data()
	{
		$attendance = $this->input->post('attendance');

		$data = array(
			'employee_id' => $this->input->post('employee_id'),
			'Attendance_date' => date('Y-m-d', strtotime($this->input->post('Attendance_date'))),
			'attendence' => $attendance,
			'remark' => $this->input->post('remark')
		);

		// Check if attendance is present, then add in_time and out_time
		if ($attendance === 'present') {
			$data['in_time'] = $this->input->post('in_time');
			$data['out_time'] = $this->input->post('out_time');
		}

		// Check if a record with the same attendance date and employee ID already exists
		$this->db->where('employee_id', $data['employee_id']);
		$this->db->where('Attendance_date', $data['Attendance_date']);
		$query = $this->db->get('employee_attendance');

		if ($query->num_rows() > 0) {
			// Record already exists, display flash message
			$this->session->set_flashdata('error', 'Employee Attendance record already exists.');
			return false;
		} else {
			// Record does not exist, insert into the database
			$this->db->insert('employee_attendance', $data);
			$insert_id = $this->db->insert_id();

			if ($insert_id) {
				$user_se_id = $this->session->userdata('user_id');
				$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
				$ci = get_instance();
				$ci->load->helper('log');
				$log_msg = add_log_entry($user_se_id, 1, $page_name[1], 'employee_attendance', 'emp_aId', $insert_id);
			}
			return $insert_id;
		}
	}


	function update_emp_attendance($id)
	{
		$attendance = $this->input->post('attendance');


		$data = array(
			'employee_id' => $this->input->post('employee_id_hidden'),
			'Attendance_date' =>$this->input->post('attendance_date'),
			'attendence' => $attendance,
			'remark' => $this->input->post('remark')
		);

		// Check if attendance is present, then add in_time and out_time
		if ($attendance === 'present') {
			$data['in_time'] = $this->input->post('in_time');
			$data['out_time'] = $this->input->post('out_time');
		}

		$this->db->where('emp_aId', $id);
		$res = $this->db->update('employee_attendance', $data);
		


		if ($res) {
			// Log the update operation
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 2, $page_name[1], 'employee_attendance', 'emp_aId', $id);

			return true;
		} else {
			// Handle the case where the update operation fails
			return false;
		}
	}


	function get_emp_attendance_list()
	{
		$query = $this->db->query("select r.*, u.employee_name as name from employee_attendance r, employee_master u where r.employee_id=u.employee_id order by Attendance_date desc ");
		return $query->result();
	}

	// function get_emp_attendance_by_id($id)
	// {
	// 	$query = $this->db->query("SELECT a.*, u.*,a.remark as rem
	// 							   FROM employee_attendance AS a
	// 							   INNER JOIN users AS u ON a.employee_id = u.user_id
	// 							   WHERE emp_aId = '$id'
	// 							   ORDER BY a.Attendance_date DESC");

	// 	return $query->result();
	// }

	function get_emp_attendance_by_id($id)
	{
		$query = $this->db->query("SELECT a.*, u.*,a.remark as rem
								   FROM employee_attendance AS a
								    JOIN employee_master AS u ON a.employee_id = u.employee_id 

								   WHERE emp_aId = '$id'
								   ORDER BY a.Attendance_date DESC");

		  return $query->row(); // <-- return single object

	}


	function delete_attendance_emp($id)
	{
		$this->db->where('emp_aId', $id);
		$this->db->delete('employee_attendance');
	}


	////////////////////////////start vehicle details/////////////////////////////////////////////////


	function add_vehicle_details()
	{

		if ($_FILES["file_doc"]) {
			$allowedExts = array("jpeg", "jpg", "png", "doc", "pdf");
			$fname = $_FILES["file_doc"]["name"];
			$temp = explode(".", $fname);
			$extension = end($temp);
			$other_file = '';

			if (($_FILES["file_doc"]["size"] < 15728640) && in_array($extension, $allowedExts)) {
				if ($_FILES["file_doc"]["error"] > 0) {
					$this->session->set_flashdata('error', 'Failed to upload - Please check file size and file format');
				} else {
					$timestamp1 = time();
					$file_tmp = $_FILES["file_doc"]["tmp_name"];
					$other_file = $timestamp1 . "_" . $fname;
					move_uploaded_file($file_tmp, "/home/webadmin/gen/empire_oasis/public/uploded_documents/" . $other_file);



					$data = array(
						'vehicle_name' => $this->input->post('vehicle_name'),
						'traffic_no' => $this->input->post('vehicle_no'),
						'license_expdate' => date('Y-m-d', strtotime($this->input->post('vl_exp'))),
						'exp_reminder' => $this->input->post('exp_reminder'),
						'insurance_no' => $this->input->post('insurance_no'),
						'insurance_date' => date('Y-m-d', strtotime($this->input->post('insurance_date'))),
						'insurance_expdate' => date('Y-m-d', strtotime($this->input->post('insurance_expdate'))),

						'remark' => $this->input->post('remark'),
						'document' => $other_file,
						'created_by' => $this->session->userdata('user_id'),
					);


					$this->db->insert('vehicle_details', $data);
					$insert_id = $this->db->insert_id();

					if ($insert_id) {
						$user_se_id = $this->session->userdata('user_id');
						$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
						$ci = get_instance();
						$ci->load->helper('log');
						$log_msg = add_log_entry($user_se_id, 1, $page_name[1], 'vehicle_details', 'v_id', $insert_id);
					}
					return $insert_id;
				}
			}
		}
	}


	function update_vehicle_details($id)
	{
		// Check if file is uploaded
		if (!empty($_FILES["file_doc"]["name"])) {
			$allowedExts = array("jpeg", "jpg", "png", "doc", "pdf");
			$fname = $_FILES["file_doc"]["name"];
			$temp = explode(".", $fname);
			$extension = end($temp);
			$other_file = '';

			// Validate file size and extension
			if ($_FILES["file_doc"]["size"] < 15728640 && in_array(strtolower($extension), $allowedExts)) {
				if ($_FILES["file_doc"]["error"] > 0) {
					$this->session->set_flashdata('error', 'Failed to upload - Please check file size and file format');
					return false; // Exit function on upload error
				} else {
					$timestamp1 = time();
					$file_tmp = $_FILES["file_doc"]["tmp_name"];
					$other_file = $timestamp1 . "_" . $fname;
					$upload_path = "/home/webadmin/gen/empire_oasis/public/uploded_documents/" . $other_file;

					// Move uploaded file to destination directory
					if (!move_uploaded_file($file_tmp, $upload_path)) {
						$this->session->set_flashdata('error', 'Failed to move uploaded file');
						return false;
					}
				}
			} else {
				$this->session->set_flashdata('error', 'Invalid file format or exceeds file size limit');
				return false;
			}
		}

		// Prepare data for database update
		$data = array(
			'vehicle_name' => $this->input->post('vehicle_name'),
			'traffic_no' => $this->input->post('vehicle_no'),
			'license_expdate' => date('Y-m-d', strtotime($this->input->post('vl_exp'))),
			'exp_reminder' => $this->input->post('exp_reminder'),
			'remark' => $this->input->post('remark'),
			'insurance_no' => $this->input->post('insurance_no'),
			'insurance_date' => date('Y-m-d', strtotime($this->input->post('insurance_date'))),
			'insurance_expdate' => date('Y-m-d', strtotime($this->input->post('insurance_expdate'))),
		);

		// Add document field to data if uploaded
		if (!empty($other_file)) {
			$data['document'] = $other_file;
		}

		// Perform database update
		$this->db->where('v_id', $id);
		$res = $this->db->update('vehicle_details', $data);

		if ($res) {
			// Log the update operation
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 2, $page_name[1], 'vehicle_details', 'v_id', $id);

			return true;
		} else {
			// Handle the case where the update operation fails
			$this->session->set_flashdata('error', 'Failed to update vehicle details');
			return false;
		}
	}


	function get_vehicle_list()
	{
		$query = $this->db->query("SELECT * FROM vehicle_details ORDER BY license_expdate DESC");
		return $query->result();
	}


	function get_vehicle_details_by_id($id)
	{
		$query = $this->db->query("select * from vehicle_details where v_id = '$id'  order by license_expdate desc ");
		return $query->result();
	}

	function delete_vehicle_data($id)
	{
		$this->db->where('v_id', $id);
		$this->db->delete('vehicle_details');
	}

	////////////////////start corporate file models///////////////////////////////////////////////////

	function add_corporate_file_data()
	{
		$data = array(
			'document_name' => $this->input->post('doc_name'),
			'card_no' => $this->input->post('card_no'),
			'expiry_date' => date('Y-m-d', strtotime($this->input->post('exp_date'))),
			'remark' => $this->input->post('remark'),
			'created_by' => $this->session->userdata('user_id'),
		);
		$this->db->insert('corporate_file', $data);
		$insert_id = $this->db->insert_id();

		/////////////////// file upload ////////////////////
		if ($insert_id) {
			if (!empty($_FILES["documents"]["name"][0])) {

    $allowedExts = array("jpeg", "jpg", "png", "doc", "pdf");
    $upload_path = FCPATH . 'public/uploaded_documents/';

    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0777, true);
    }

    for ($i = 0; $i < count($_FILES['documents']["name"]); $i++) {

        if ($_FILES['documents']["name"][$i] != '') {

            $temp = explode(".", $_FILES["documents"]["name"][$i]);
            $extension = strtolower(end($temp));

            if (
                $_FILES["documents"]["size"][$i] < 15728640 &&
                in_array($extension, $allowedExts)
            ) {

                if ($_FILES["documents"]["error"][$i] > 0) {

                    $this->session->set_flashdata(
                        'error',
                        'Failed to upload - Please check file size and file format'
                    );

                } else {

                    $file_tmp = $_FILES["documents"]["tmp_name"][$i];

                    $other_file = time() . "_" . rand(1000,9999) . "_" . $_FILES['documents']['name'][$i];

                    move_uploaded_file($file_tmp, $upload_path . $other_file);

                    $data1 = array(
                        'cop_id' => $insert_id,
                        'employee_id' => $this->session->userdata('user_id'),
                        'document_path' => $other_file,
                    );

                    $this->db->insert('employee_corporate_documents', $data1);
                }
            }
        }
    }
}
		}

		if ($insert_id) {
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 1, $page_name[1], 'corporate_file', 'cop_id', $insert_id);
		}
		return $insert_id;
	}

	function update_corporate_file_data($id)
	{
		$data = array(
			'document_name' => $this->input->post('doc_name'),
			'card_no' => $this->input->post('card_no'),
			'expiry_date' => date('Y-m-d', strtotime($this->input->post('exp_date'))),
			'remark' => $this->input->post('remark'),
		);
		$this->db->where('cop_id', $id);
		$res = $this->db->update('corporate_file', $data);

		/////////////////// file upload ////////////////////
		if ($id) {
			if (!empty($_FILES["documents"]["name"][0])) {

    $allowedExts = array("jpeg", "jpg", "png", "doc", "pdf");
    $upload_path = FCPATH . 'public/uploaded_documents/';

    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0777, true);
    }

    for ($i = 0; $i < count($_FILES['documents']["name"]); $i++) {

        if ($_FILES['documents']["name"][$i] != '') {

            $temp = explode(".", $_FILES["documents"]["name"][$i]);
            $extension = strtolower(end($temp));

            if (
                $_FILES["documents"]["size"][$i] < 15728640 &&
                in_array($extension, $allowedExts)
            ) {

                if ($_FILES["documents"]["error"][$i] > 0) {

                    $this->session->set_flashdata(
                        'error',
                        'Failed to upload - Please check file size and file format'
                    );

                } else {

                    $file_tmp = $_FILES["documents"]["tmp_name"][$i];

                    $other_file = time() . "_" . rand(1000,9999) . "_" . $_FILES['documents']['name'][$i];

                    move_uploaded_file($file_tmp, $upload_path . $other_file);

                    $data1 = array(
                        'cop_id' => $id, // EDIT case ID
                        'employee_id' => $this->session->userdata('user_id'),
                        'document_path' => $other_file,
                    );

                    $this->db->insert('employee_corporate_documents', $data1);
                }
            }
        }
    }
}
		}



		if ($res) {
			// Log the update operation
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 2, $page_name[1], 'corporate_file', 'cop_id', $id);

			return true;
		} else {
			// Handle the case where the update operation fails
			return false;
		}
	}
	function get_corporate_file_list()
	{
		$query = $this->db->query("select * from corporate_file order by expiry_date desc");
		return $query->result();
	}

	function get_corporate_file_id($id)
	{
		$query = $this->db->query("select * from corporate_file where cop_id ='$id' order by expiry_date");
		return $query->result();
	}


	function get_employee_corporate_doc_id($id)
	{
		$query = $this->db->query("select  * from employee_corporate_documents  where cop_id='$id' ");
		return $query->result();
	}

	function delete_corporate_file_data($id)
	{
		$this->db->where('cop_id', $id);
		$this->db->delete('corporate_file');
	}

	//////////////////////salary structure start///////////////////////////////////////////////////////
	function add_salary_structure()
	{

		$salary_structure_data = array(
			'emp_id' => $this->input->post('employee_id'),
			'effective_date' => date('Y-m-d', strtotime($this->input->post('effctive_date'))),
			'basic_salary' => $this->input->post('bsalary'),
			'total_allowances' => $this->input->post('t_allowance'),
			'total_deductions' => $this->input->post('t_deduction'),
			'gross_salary' => $this->input->post('gross_salary'),
			'remark' => $this->input->post('remark'),
		);

		$this->db->insert('salary_structure', $salary_structure_data);
		$insert_id = $this->db->insert_id();

		if ($insert_id) {
			// Allowance details insertion
			if (isset($_POST['allowance_type'])) {
				for ($i = 0; $i < count($_POST['allowance_type']); $i++) {
					$allowance_data = array(
						'sid' => $insert_id,
						'allowance_id' => $_POST['allowance_type'][$i],
						'amount' => $_POST['a_amount'][$i],
					);
					$this->db->insert('salary_structure_details', $allowance_data);
				}
			}

			// Deduction details insertion
			if (isset($_POST['deduction_type'])) {
				for ($i = 0; $i < count($_POST['deduction_type']); $i++) {
					$deduction_data = array(
						'sid' => $insert_id,
						'allowance_id' => $_POST['deduction_type'][$i],
						'amount' => $_POST['d_amount'][$i],
					);
					$this->db->insert('salary_structure_details', $deduction_data);
				}
			}

			// Logging
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 1, $page_name[1], 'salary_structure', 'sid', $insert_id);

			return $insert_id;
		}

		return false; // Return false if insertion fails 
	}
	/////update data
	function update_salary_structure($id)
	{
		$old_date = date('Y-m-d', strtotime($this->input->post('old_date')));
		$new_date = date('Y-m-d', strtotime($this->input->post('effctive_date')));

		if ($old_date == $new_date) {
			$data = array(
				'emp_id' => $this->input->post('employee_id'),
				'effective_date' => date('Y-m-d', strtotime($this->input->post('old_date'))),
				'basic_salary' => $this->input->post('bsalary'),
				'total_allowances' => $this->input->post('t_allowance'),
				'total_deductions' => $this->input->post('t_deduction'),
				'gross_salary' => $this->input->post('gross_salary'),
				'remark' => $this->input->post('remark'),
			);

			$this->db->where('sid', $id);
			$res = $this->db->update('salary_structure', $data);

			if ($res) {

			// Delete existing allowance/deduction details first
    $this->db->where('sid', $id)->delete('salary_structure_details');
				// Allowance details insertion
				if (isset($_POST['allowance_type'])) {
    foreach ($_POST['allowance_type'] as $index => $allowance_type) {

        if (!empty($allowance_type) && !empty($_POST['a_amount'][$index])) {

            $allowance_data = array(
                'sid' => $id,
                'allowance_id' => $allowance_type,
                'amount' => $_POST['a_amount'][$index],
            );

            $this->db->insert('salary_structure_details', $allowance_data);
        }
    }
}

				// Deduction details insertion
				if (isset($_POST['deduction_type'])) {
    foreach ($_POST['deduction_type'] as $index => $deduction_type) {

        if (!empty($deduction_type) && !empty($_POST['d_amount'][$index])) {

            $deduction_data = array(
                'sid' => $id,
                'allowance_id' => $deduction_type,
                'amount' => $_POST['d_amount'][$index],
            );

            $this->db->insert('salary_structure_details', $deduction_data);
        }
    }
}

				// Logging
				$user_se_id = $this->session->userdata('user_id');
				$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
				$ci = get_instance();
				$ci->load->helper('log');
				$log_msg = add_log_entry($user_se_id, 2, $page_name[1], 'salary_structure', 'sid', $id);

				return true;
			} else {
				return false; // Handle the case where the update operation fails
			}
		} else {
			// Insert new record
			$salary_structure_data = array(
				'emp_id' => $this->input->post('employee_id'),
				'effective_date' => date('Y-m-d', strtotime($this->input->post('effctive_date'))),
				'basic_salary' => $this->input->post('bsalary'),
				'total_allowances' => $this->input->post('t_allowance'),
				'total_deductions' => $this->input->post('t_deduction'),
				'gross_salary' => $this->input->post('gross_salary'),
				'remark' => $this->input->post('remark'),
			);

			$this->db->insert('salary_structure', $salary_structure_data);
			$insert_id = $this->db->insert_id();

			if ($insert_id) {
				// Allowance details insertion
				if (isset($_POST['allowance_type'])) {
					foreach ($_POST['allowance_type'] as $index => $allowance_type) {
						$allowance_data = array(
							'sid' => $insert_id,
							'allowance_id' => $allowance_type,
							'amount' => $_POST['a_amount'][$index],
						);
						$this->db->insert('salary_structure_details', $allowance_data);
					}
				}

				// Deduction details insertion
				if (isset($_POST['deduction_type'])) {
					foreach ($_POST['deduction_type'] as $index => $deduction_type) {
						$deduction_data = array(
							'sid' => $insert_id,
							'allowance_id' => $deduction_type,
							'amount' => $_POST['d_amount'][$index],
						);
						$this->db->insert('salary_structure_details', $deduction_data);
					}
				}

				// Logging
				$user_se_id = $this->session->userdata('user_id');
				$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
				$ci = get_instance();
				$ci->load->helper('log');
				$log_msg = add_log_entry($user_se_id, 1, $page_name[1], 'salary_structure', 'sid', $insert_id);

				return $insert_id;
			}

			return false; // Return false if insertion fails  
		}
	}



	function get_salary_structure_list()
	{
		$query = $this->db->query("select  j.*, u.employee_name as name from salary_structure j, employee_master u where j.emp_id=u.employee_id  order by effective_date desc ");
		return $query->result();
	}

	function get_salary_allowance_details($id)
{
    $query = $this->db->query(
        "SELECT d.*, a.*
         FROM salary_structure_details d
         JOIN allowance_master a ON d.allowance_id = a.sno
         WHERE d.sid = ?",
        array($id)
    );

    return $query->result(); // multiple allowances
}



	function get_active_basic_salary()
{
    $query = $this->db->query("
        SELECT *
        FROM employee_master
        WHERE employee_id NOT IN (
            SELECT emp_id
            FROM salary_structure
        )
        ORDER BY employee_name
    ");

    return $query->result();
}
	function get_salary_structure_by_id($id)
{
    return $this->db->query("
        SELECT j.*, u.employee_name AS name, j.remark AS rem
        FROM salary_structure j
        JOIN employee_master u ON j.emp_id = u.employee_id
        WHERE j.sid = $id
        ORDER BY j.effective_date DESC
    ")->row();   // ✅ row(), not result()
}



	function delete_salary_structure($id)
	{
		// Delete record from the 'salary_structur_details' table
		$this->db->where('sid', $id);
		$this->db->delete('salary_structure_details');

		// Delete record from the 'salary_structure' table
		$this->db->where('sid', $id);
		$this->db->delete('salary_structure');
	}

	////////////////////////////monthly salary////////employee_overtime
	// function get_salary_monthly_by_id($id)

	// {

	// 	$query = $this->db->query("SELECT overtime, date_ot FROM employee_overtime WHERE employee_id = '$id'");
	// 	$result1 = $query->result();
	// 	$totalhour = 0; // Initialize the count

	// 	foreach ($result1 as $row) {

	// 		$totalhour = $totalhour + $row->overtime;
	// 	}
	// 	$query = $this->db->query("SELECT attendence, Attendance_date FROM employee_attendance WHERE employee_id = '$id'");
	// 	$result = $query->result();

	// 	$count = 0; // Initialize the count

	// 	foreach ($result as $row) {
	// 		if ($row->attendence == "present") {
	// 			$count++;
	// 		}
	// 	}
	// 	$query = $this->db->query("SELECT j.*, u.user_name AS name, j.remark AS rem,o.overtime,a.attendence
	//                             FROM salary_structure j
	//                             JOIN users u ON j.emp_id = u.user_id
	//                             LEFT JOIN employee_overtime o ON u.user_id = o.employee_id
	//                             LEFT JOIN employee_attendance a ON u.user_id = a.employee_id
	//                             WHERE j.emp_id = $id 
	//                             ORDER BY j.effective_date DESC 
	//                             ");
	// 	return $query->result();
	// }

	// 	$data['from'] = date('Y-m-d', strtotime($this->input->post('from')));
	// 	$data['to'] = date('Y-m-d', strtotime($this->input->post('to')));
	// 	$cid = $this->input->post('customer_id');
	// 	if ($cid == '')
	// 		  $condition = "";
	// 	else
	// 		  $condition = "and cust_id=$cid";

	// 	$query = $this->db->query("SELECT enquiry_id,enquiry_code,enq_type,enq_date,customer_id,cust_name,client_ref,enq_source FROM enquiry_master e JOIN
	// customer_master c ON e.cust_id = c.customer_id WHERE enq_date BETWEEN '{$data['from']}' AND '{$data['to']}'  $condition ORDER BY enq_date");
	// 	return $query->result();


	function get_salary_structure_data()
{
    $effective_date = $this->input->post('effective_date');
    $selected_month_year = date('Y-m', strtotime($effective_date));
    $emp_id = $this->input->post('user_id');

    $end_date = date('Y-m-t', strtotime($selected_month_year));

    $query = $this->db->query("
        SELECT * 
        FROM salary_structure 
        WHERE emp_id = '$emp_id'
        AND effective_date <= '$end_date'
        ORDER BY effective_date DESC
        LIMIT 1
    ");

    return $query->result();
}

public function get_salary_structure_data_new($emp_id)
{
    $effective_date = $this->input->post('effective_date', true);

    // ✅ SAFE fallback (IMPORTANT)
    if (empty($effective_date)) {
        $effective_date = date('Y-m');
    }

    $selected_month = date('Y-m', strtotime($effective_date));
    $end_date = date('Y-m-t', strtotime($selected_month));

    $query = $this->db->query("
        SELECT * 
        FROM salary_structure 
        WHERE emp_id = '$emp_id'
        AND effective_date <= '$end_date'
        ORDER BY effective_date DESC
        LIMIT 1
    ");

    return $query->row();
}
	function get_salary_structure_details($id)
	{
		$query = $this->db->query("select * from salary_structure_details s, allowance_master am where s.allowance_id=am.sno and  s.sid='$id' ");
		return $query->result();
	}

	function get_attendance_details($employee_id, $start_date, $end_date)
{
    $query = $this->db->query("
        SELECT 
            COALESCE(SUM(CASE WHEN attendence = 'present' THEN 1 ELSE 0 END),0) AS present_count,
            COALESCE(SUM(CASE WHEN attendence = 'half_day' THEN 1 ELSE 0 END),0) AS half_count,
            COALESCE(SUM(CASE WHEN attendence = 'absent' THEN 1 ELSE 0 END),0) AS absent_count
        FROM employee_attendance
        WHERE employee_id = ?
        AND Attendance_date BETWEEN ? AND ?
    ", [$employee_id, $start_date, $end_date]);

    return $query->row();
}
	function add_basic_enquiry()
	{
		$basic_enq_data = array(
			'enquiry_id' => '1',
			'budget' => $this->input->post('budget'),
			'privious_spend' => $this->input->post('privious_spend'),
			'buildup_days' => $this->input->post('build_days'),
			'height_restriction' => $this->input->post('height_restriction'),
			'standno' => $this->input->post('stand_no'),
			'hallno' => $this->input->post('hall_no'),
			'plaform_size' => $this->input->post('plaform_size'),
			'side_open' => $this->input->post('side_open'),
			'mezzanine_size' => $this->input->post('mezzanine_size'),
			'Dimensions' => $this->input->post('dimensions'),
			'decker' => $this->input->post('decker'),
			'floorplan' => $this->input->post('floorplan_attached'),
			'other_considerations' => $this->input->post('consideration'),
			'brand_guidelines' => $this->input->post('guidline_attached'),
			'organizer' => $this->input->post('organizer'),
			'other_information' => $this->input->post('info_attached'),
		);

		$this->db->insert('basic_info_enq', $basic_enq_data); // Insert into basic_info_enq table
		$insert_id = $this->db->insert_id();

		if ($insert_id) {

			if (isset($_POST['bid'])) {
				for ($i = 0; $i < count($_POST['bid']); $i++) {
					$basic_info = array(
						'bid' => $_POST['bid'][$i],
						'details' => $_POST['details'][$i],
						'remark' => $_POST['remark'][$i],
					);
					$this->db->insert('basic_enquiry_master', $basic_info); // Insert into basic_enquiry_master table
				}
			}
			// Logging
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 1, $page_name[1], 'basic_enquiry_master', 'bid', $insert_id);

			return $insert_id;
		}

		return false; // Return false if insertion fails 


	}

	///////////////////////////////employee corner modelleave///////////////

	function get_employee_leave_corner_list()
	{
		$current_user_id = $this->session->userdata('user_id');
		$query = $this->db->query("select  j.*, u.user_name as name from employee_leave j, users u where j.employee_id=u.user_id AND j.employee_id='$current_user_id' order by application_date desc ");
		return $query->result();
	}


	function get_employee_regignation_corner_list()
	{
		$current_user_id = $this->session->userdata('user_id');
		$query = $this->db->query("select r.*, u.user_name as name from employee_resignation r, users u where r.employee_id=u.user_id AND r.employee_id='$current_user_id' order by resignation_date desc ");
		return $query->result();
	}

	function add_emp_monthly_salary()
	{

	$overtime_hours = $this->input->post('t_overtime');

// convert hours → HH:MM:SS
$hours = floor($overtime_hours);
$minutes = ($overtime_hours - $hours) * 60;

$overtime_time = sprintf('%02d:%02d:00', $hours, $minutes);

		$data = array(
			'emp_id' => $this->input->post('empid'),
			'salary_month' => date('Y-m-01', strtotime($this->input->post('effective_date'))),
			'working_days' => $this->input->post('working_days'),
			'leave_days' => $this->input->post('leave_days'),
			'present_days' => $this->input->post('present_days'),
			'paid_leave' => $this->input->post('paid_leave'),
			'payment_days' => $this->input->post('payment_days'),
'overtime' => $overtime_time,
			'overtime_amt' => $this->input->post('amt_overtime'),
			'basic_salary' => $this->input->post('basic_salary'),
			'daily_basic' => $this->input->post('daily_basic'),
			'total_allowance' => $this->input->post('total_allowances'),
			'total_deduction' => $this->input->post('total_deduction'),
			'gross_salary' => $this->input->post('gross_salary'),
			'net_salary' => $this->input->post('net_pay'),
			'remark' => $this->input->post('remark'),
			'created_by' => $this->session->userdata('user_id'),
			'created_data' => date('Y-m-d'),
		);
		$this->db->insert('employee_monthly_salary', $data);
		$insert_id = $this->db->insert_id();

		if ($insert_id) {
			if (isset($_POST['allowance_amt'])) {
				for ($i = 0; $i < count($_POST['allowance_amt']); $i++) {
					$allowance_data = array(
						'sid' => $insert_id,
						'allowance_id' => $_POST['allowance_id'][$i],
						'amount' => $_POST['allowance_amt'][$i],
					);
					$this->db->insert('employee_monthly_salary_details', $allowance_data);
				}
			}

			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 1, $page_name[1], 'employee_monthly_salary', 'sid', $insert_id);
		}
		return $insert_id;
	}

	// function get_emp_monthly_salary_list()
	// {
	// 	$query = $this->db->query("select * from employee_monthly_salary s, users u where s.emp_id=u.user_id order by salary_month  desc");
	// 	return $query->result();
	// }
function get_emp_monthly_salary_list($from)
{
    if (empty($from)) {
        return [];
    }

    // Convert input to Y-m-01 format (first day of month)
    $month_start = date('Y-m-01', strtotime($from));
    $month_end   = date('Y-m-t', strtotime($from)); // last day of month

    $sql = "
        SELECT s.*, u.employee_name, u.user_code, d.designation_name, dep.dept_name
        FROM employee_monthly_salary s
        JOIN employee_master u ON s.emp_id = u.employee_id
        LEFT JOIN designation_master d ON u.designation_id = d.id
        LEFT JOIN department_master dep ON u.department_id = dep.dept_id
        WHERE s.salary_month BETWEEN ? AND ?
        ORDER BY s.salary_month DESC
    ";

    $query = $this->db->query($sql, [$month_start, $month_end]);
    return $query->result();
}


	// function get_emp_monthly_salary_list($from)
	// {
	// 	$from_date = date('Y-m-01', strtotime($from));
	// 	$to_date = date('Y-m-t', strtotime($from_date));

	// 	$data['from'] = $from_date;
	// 	$data['to'] = $to_date;

	// 	$query = $this->db->query("
    //     SELECT * 
    //     FROM employee_monthly_salary s, users u 
    //     WHERE s.emp_id = u.user_id 
    //     AND s.salary_month BETWEEN '{$data['from']}' AND '{$data['to']}' 
    //     ORDER BY s.salary_month DESC");

	// 	return $query->result();
	// }






	function get_approval_setup_list()
	{
		$query = $this->db->query("select * from approval_setup ");
		return $query->result();
	}

	function add_approve_data()
	{
		$data = array(
			'approve_type' => $this->input->post('approve_type'),
			'approve_hr' => $this->input->post('approve_hr'),
			'approve_admin_md' => $this->input->post('approve_admin_md'),
			'created_by' => $this->session->userdata('user_id'),

		);

		$this->db->insert('approval_setup', $data);
		$insert_id = $this->db->insert_id();
		if ($insert_id) {
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 1, $page_name[1], 'approval_setup', 'approve_id', $insert_id);
		}
		return $insert_id;
	}

	function get_employee_monthlypayslip_by_id($sid)
	{
		$query = $this->db->query("select one.*,  two.dept_name, three.designation_name from (select e.*, joining_date,employee_name, department_id,designation_id, user_code from employee_monthly_salary e, employee_master u where e.emp_id=u.employee_id and sid=$sid )as one left join(select * from department_master)as two on(one.department_id=two.dept_id) left join(select * from designation_master)as three on(one.designation_id=three.id)");
		return $query->result();
	}
	function get_monthly_salary_details($sid)
	{
		$query = $this->db->query("select * from employee_monthly_salary_details s, allowance_master am where s.allowance_id=am.sno and sid=$sid");
		return $query->result();
	}

	//////////////////////////////////////start advance salay////////////////////////////////////////
	function add_advance_salary()
	{

		$prifix = 'AS';
		$this->load->model('Setup_model');
		$num = $this->Setup_model->get_next_code($prifix, 'as_code', 'advance_salary', 3) + 1;
		$digit = sprintf("%1$04d", $num);
		$AS_code = $prifix . $digit;

		$data = array(
			'emp_id' => $this->input->post('employee_id'),
			'as_code' => $AS_code,
			'form_date' => date('Y-m-d', strtotime($this->input->post('from_date'))),
			'to_date' => date('Y-m-d', strtotime($this->input->post('to_date'))),
			'deduction_amount' => $this->input->post('deduction_amount'),
			'remark' => $this->input->post('remark'),
		);
		$this->db->insert('advance_salary', $data);
		$insert_id = $this->db->insert_id();

		$user_se_id = $this->session->userdata('user_id');
		$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
		$ci = get_instance();
		$ci->load->helper('log');
		$log_msg = add_log_entry($user_se_id, 1, $page_name[1], 'advance_salary', 'as_id', $insert_id);

		return $insert_id;
	}

	function update_advance_salary($id)
	{

		$data = array(
			'emp_id' => $this->input->post('employee_id_hidden'),
			'form_date' => date('Y-m-d', strtotime($this->input->post('from_date'))),
			'to_date' => date('Y-m-d', strtotime($this->input->post('to_date'))),
			'deduction_amount' => $this->input->post('deduction_amount'),
			'remark' => $this->input->post('remark'),
		);

		$this->db->where('as_id', $id);
		$res = $this->db->update('advance_salary', $data);

		if ($res) {
			// Log the update operation
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 2, $page_name[1], 'advance_salary', 'as_id', $id);

			return true;
		} else {
			// Handle the case where the update operation fails
			return false;
		}
	}



	function get_user_list()
	{
		$query = $this->db->query("select * from users  order by user_name");

		return $query->result();
	}

	function get_advance_salary_list()
	{
		$query = $this->db->query("select * from users u, advance_salary a where a.emp_id=u.user_id order by to_date");
		return $query->result();
	}

	function get_advance_salary_list_by_id($id)
	{
		$query = $this->db->query("select * from advance_salary where  as_id = $id ");
		return $query->result();
	}

	function delete_advance_salary($id)
	{
		$this->db->where('as_id', $id);
		$this->db->delete('advance_salary');
	}
	public function get_upcoming_leaves()
    {
        $query = $this->db->query("
            SELECT 
                l.leave_id,
                u.user_name AS employee_name,
                l.leave_type,
                l.start_date,
                l.end_date,
                l.reason,
                l.created_date,
                CASE 
                    WHEN l.start_date > CURDATE() THEN 'Upcoming'
                    WHEN CURDATE() BETWEEN l.start_date AND l.end_date THEN 'On Leave'
                    ELSE 'Completed'
                END AS status
            FROM employee_leave l
            LEFT JOIN users u ON u.user_id = l.employee_id
            WHERE l.start_date >= CURDATE()
            ORDER BY l.start_date ASC
            LIMIT 10
        ");

        return $query->result();
    }
	public function check_salary_exist($user_id, $effective_date)
	{
		return $this->db
			->where('emp_id', $user_id)
			->where('salary_month', $effective_date)
			->get('employee_monthly_salary')
			->num_rows();
	}
	 function get_overtime_by_id($user_id,$ot_date)
{
    $query = $this->db
                  ->select('overtime')
                  ->from('employee_overtime')
                  ->where('employee_id', $user_id)
				  ->where('DATE_FORMAT(date_ot, "%Y-%m") =', $ot_date)
                  ->get();

    return $query->row()->overtime ?? 0;
}

//overtime type
// function get_overtime_type_by_id($user_id,$ot_date)
// {
//     $query = $this->db
//                   ->select('ot_type')
//                   ->from('employee_overtime')
//                   ->where('employee_id', $user_id)
// 				  ->where('DATE_FORMAT(date_ot, "%Y-%m") =', $ot_date)
//                   ->get();

//     return $query->row()->ot_type ?? 'NORMAL';
// }
// basic salary view by user id
function get_salary_structure_datas($user_id, $end_date)
{
    $query = $this->db->query("
        SELECT * 
        FROM salary_structure 
        WHERE emp_id = ? 
        AND effective_date <= ?
        ORDER BY effective_date DESC 
        LIMIT 1
    ", [$user_id, $end_date]);

    return $query->result();
}
function get_employee_list()
	{
		$query = $this->db->query("select e.*  from employee_master e  order by employee_id");
		return $query->result();
	}

	public function get_employee_passport_info($employee_id)
{
    return $this->db
        ->select('
            employee_id,
            user_code,
            passport_number,
            passport_issue_date,
            passport_expiry_date
        ')
        ->from('employee_master')
        ->where('employee_id', $employee_id)
        ->get()
        ->row();
}

// Hr_model.php
public function get_employee_by_id($employee_id)
{
    return $this->db->where('employee_id', $employee_id)
                    ->get('employee_master')
                    ->row(); // returns object
}
public function get_leave_latest_status($leave_id)
{
    $this->db->select('leave_status');
    $this->db->from('leave_approval');
    $this->db->where('approval_leave_id', $leave_id);
    $this->db->order_by('app_id', 'DESC');
    $this->db->limit(1);

    return $this->db->get()->row();
}

public function get_salary_structure_by_employee($employee_id)
{
    $this->db->select('*');
    $this->db->from('salary_structure');
    $this->db->where('emp_id', $employee_id);
    return $this->db->get()->row();
}

}
