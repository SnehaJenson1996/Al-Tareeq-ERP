<?php
class Hr extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
	}

	function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		if (!isset($is_logged_in) || $is_logged_in != true) {
			echo 'You don\'t have permission to access this page. <a href="../login">Login</a>';

			die();
			//$this->load->view('login/login_form');
		}
	}

	///////////////////////////////////////Allowances////////////////////////////////////////////// 

	function add_allowances()
	{
		$data['title'] = "Allowances & Deductions Master";
		$data['main_content'] = 'hr/allowances_deductions_add.php';
		$this->load->view('includes/template', $data);
	}
	function view_allowances_list()
	{
		$data['title'] = "Allowances & Deductions Master List";
		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_allowances_list();
		$data['main_content'] = 'hr/allowances_deductions_list.php';
		$this->load->view('includes/template', $data);
	}

	public function add_allowances_data()
{
    $data['title'] = "Allowances & Deductions Master";
    $this->load->model('Hr_model');

    // Get posted values
    $atype = $this->input->post('allowance_type');
    $aname = $this->input->post('allowance_name');

    // Check for duplicates
    $this->db->where('allowance_type', $atype);
    $this->db->where('allowance_name', $aname);
    $exists = $this->db->get('allowance_master')->num_rows();

    if ($exists > 0) {
        // Duplicate exists
        $this->session->set_flashdata('warning', 'Allowance Name Already Exists');
        redirect('Hr/add_allowances'); // reload form
        exit;
    }

    // No duplicate, proceed to save
    $flag = $this->Hr_model->add_allowances_data();
    if ($flag) {
        $this->session->set_flashdata('success', 'Record Successfully Saved');
        redirect('Hr/view_allowances_list');
    } else {
        $this->session->set_flashdata('warning', 'Error Saving Record');
        redirect('Hr/add_allowances');
    }
}
	function edit_allowances()
	{
		$data['title'] = "Edit Allowances & Deductions Master";
		$id = $this->uri->segment('3');

		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_allowances_by_id($id);
		$data['main_content'] = 'hr/allowances_deductions_edit.php';
		$this->load->view('includes/template', $data);
	}

	function update_allowances()
	{
		$data['title'] = "Allowances & Deductions Master";
		$id = $this->input->post('id');
		$this->load->model('Hr_model');
		$res = $this->Hr_model->update_allowances($id);
		if ($res) {
			$this->session->set_flashdata('success', 'Record Successfully Updated');
			redirect('Hr/view_allowances_list');
		}
	}
	function delete_Allowances()
	{
		$id = $this->uri->segment('3');

		$this->load->model('Hr_model');
		$data['user_records'] = $this->Hr_model->delete_allowance($id);

		$this->session->set_flashdata('success', 'Record Delete Successfully');
		redirect('Hr/view_allowances_list');
	}
	///////////////////////////////////////Leave application ////////////////////////////////////////////// 

	function add_leave_application()
	{
		$data['title'] = "Leave application";
		// $this->load->model('Users_model');
		// $data['user_records'] = $this->Users_model->get_user_list();
		$this->load->model('Hr_model');

		$data['records'] = $this->Hr_model->get_employee_list();
		
		$data['main_content'] = 'hr/leave_allocation_add.php';
		$this->load->view('includes/template', $data);
	}
	function view_leave_application_list()
	{
		$data['title'] = "Leave application";


		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_employee_leave_list();
		$data['record1'] = $this->Hr_model->leave_approval_list();
		$data['record2'] = $this->Hr_model->get_user_list();
		// print_r($data['records']);
		// exit;
		$data['main_content'] = 'hr/leave_allocation_list.php';
		$this->load->view('includes/template', $data);
	}

	function add_leave_application_data()
	{
		$data['title'] = "Leave application";
		$this->load->model('Hr_model');
		$flag = $this->Hr_model->add_employee_leave_data();
		if ($flag) {
			$this->session->set_flashdata('success', 'Record Successfully Saved');
			redirect('Hr/view_leave_application_list');
		} else {
			$this->session->set_flashdata('warning', 'Supplier Company Name Already Exist');
			redirect('Hr/view_leave_application_list');
		}
	}

	function edit_leave_application()
{
    $data['title'] = "Edit Leave application";
    $id = $this->uri->segment('3');

    $this->load->model('Hr_model');

    // Employee list
    $data['records'] = $this->Hr_model->get_employee_list();

    // Leave record
    $data['leave'] = $this->Hr_model->get_employee_leave_by_id($id);

    // Files
    $data['file_records'] = $this->Hr_model->get_employee_leave_doc_id($id);

    // HR list
    $data['admin_hr'] = $this->Hr_model->leave_hr_admin_list();

    // 🔥 ADD THIS (IMPORTANT FIX)
    $data['leave_status'] = $this->Hr_model->get_leave_latest_status($id);

    $data['main_content'] = 'hr/leave_allocation_edit';
    $this->load->view('includes/template', $data);
}

	function update_leave_application()
	{
		$data['title'] = "Edit Leave application";
		$id = $this->input->post('id');
		$this->load->model('Hr_model');
		$res = $this->Hr_model->update_employee_leave($id);
		if ($res) {
			$this->session->set_flashdata('success', 'Record Successfully Updated');
			redirect('Hr/view_leave_application_list');
		}
	}

	function print_leave_application()
{
    $id = $this->uri->segment('3');

    $this->load->model('Hr_model');
    $this->load->model('Setup_model');
$data['records'] = $this->Hr_model->get_employee_list();

    // Single leave record
    $data['leave'] = $this->Hr_model->get_employee_leave_by_id($id);

    // Documents
    $data['file_records'] = $this->Hr_model->get_employee_leave_doc_id($id);

    // Dropdown helpers (optional)
    $data['dept_list'] = $this->Setup_model->get_active_department_list();
    $data['desig_list'] = $this->Setup_model->get_designation_list();

    $this->load->view('hr/print/print_leave_application.php', $data);
}

	function delete_leave_application()
	{


		$this->load->model('Hr_model');
		$data['user_records'] = $this->Hr_model->delete_leave_application($id);

		$this->session->set_flashdata('success', 'Record Delete Successfully');
		redirect('Hr/view_leave_application_list');
	}

	//approvalcontroler//////////////////leave_id
	function add_leave_approval()
	{

		$this->load->model('Hr_model');
		$flag = $this->Hr_model->add_approval_leave();
		if ($flag) {
			$this->session->set_flashdata('success', 'Record Successfully Saved');
			redirect('Hr/view_leave_application_list');
		}
	}
	///////////////////////////////////////Joining Application////////////////////////////////////////////// 

	function add_joining_application()
	{
		$this->load->model('Hr_model');


		$data['user_records'] = $this->Hr_model->get_joining_new_list();


		$data['title'] = "Joining Application";
		$data['main_content'] = 'hr/joining_allocation_add.php';
		$this->load->view('includes/template', $data);
	}
	function view_joining_application_list()
	{
		$data['title'] = "Joining Application List";
		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_employee_joining_list();
		$data['main_content'] = 'hr/joining_allocation_list.php';
		$this->load->view('includes/template', $data);
	}

	function add_joining_application_data()
	{
		$data['title'] = "Joining Application";
		$this->load->model('Hr_model');
		$flag = $this->Hr_model->add_joining_application_data();
		if ($flag) {
			$this->session->set_flashdata('success', 'Record Successfully Saved');
			redirect('Hr/view_joining_application_list');
		} else {
			$this->session->set_flashdata('warning', 'Supplier Company Name Already Exist');
			redirect('Hr/view_joining_application_list');
		}
	}

	function edit_joining_application()
	{
		$data['title'] = "Joining Application Edit";
		$id = $this->uri->segment('3');

		$this->load->model('Users_model');
		$data['user_records'] = $this->Users_model->get_user_list();
		$this->load->model('Hr_model');
		// $data['records'] = $this->Hr_model->get_employee_joining_by_id($id);
		$data['record'] = $this->Hr_model->get_employee_joining_by_id($id);

		$data['main_content'] = 'hr/joining_allocation_edit.php';
		$this->load->view('includes/template', $data);
	}

	function update_joining_application()
	{
		$data['title'] = "Joining Application";
		$id = $this->input->post('id');
		$this->load->model('Hr_model');
		$res = $this->Hr_model->update_joining_application($id);
		if ($res) {
			$this->session->set_flashdata('success', 'Record Successfully Updated');
			redirect('Hr/view_joining_application_list');
		}
	}

	function print_joining_application()
{
    $id = $this->uri->segment(3);

    $this->load->model('Hr_model');
    $data['records'] = $this->Hr_model->get_employee_joining_by_id($id);

    // ❌ REMOVE THIS (not needed)
    // $this->load->model('Users_model');
    // $data['record1'] = $this->Users_model->get_user_record_by_id_pass($id);

    $this->load->model('Setup_model');
    $data['dept_list'] = $this->Setup_model->get_active_department_list();

    $this->load->view('hr/print/print_joining_application.php', $data);
}

	function delete_joining_application()
	{
		$id = $this->uri->segment('3');

		$this->load->model('Hr_model');
		$data['user_records'] = $this->Hr_model->delete_joining_application($id);

		$this->session->set_flashdata('success', 'Delete Record Successfully');
		redirect('Hr/view_joining_application_list');
	}
	///////////////////////////////////////salary_structure////////////////////////////////////////////// 

	function add_emp_salary_structure()
	{
		$data['title'] = "Employee Salary Structure";

		$this->load->model('Hr_model');
		$data['record1'] = $this->Hr_model->get_allowances_list();
				$data['records'] = $this->Hr_model->get_active_basic_salary();

		// $data['user_records'] = $this->Hr_model->get_active_basic_salary();

		$data['main_content'] = 'hr/basic_salary_add.php';
		$this->load->view('includes/template', $data);
	}
	function view_salary_structure_list()
	{
		$data['title'] = "Employee Salary List";
		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_salary_structure_list();

		$data['main_content'] = 'hr/basic_salary_list.php';
		$this->load->view('includes/template', $data);
	}

	function add_salary_structure_data()
	{
		$data['title'] = "Add Salary Structure";
		$this->load->model('Hr_model');
		$flag = $this->Hr_model->add_salary_structure();
		if ($flag) {
			$this->session->set_flashdata('success', 'Record Successfully Saved');
			redirect('Hr/view_salary_structure_list');
		} else {
			$this->session->set_flashdata('warning', 'data Already Exist');
			redirect('Hr/add_allowances');
		}
	}

	function edit_salary_structure()
	{
		$data['title'] = "Edit Salary Structure";
		$id = $this->uri->segment('3');

		$this->load->model('Hr_model');
		// $data['user_records'] = $this->Users_model->get_user_list();
		$data['user_records'] = $this->Hr_model->get_employee_list();


		$this->load->model('Hr_model');
$data['records'] = $this->Hr_model->get_salary_structure_by_id($id);
		$data['record1'] = $this->Hr_model->get_allowances_list();
		$data['details'] = $this->Hr_model->get_salary_allowance_details($id);

		// print_r($data['details']);
		// die;
		$data['main_content'] = 'hr/basic_salary_edit.php';
		$this->load->view('includes/template', $data);
	}

	function update_salary_structure()
	{
		$data['title'] = "Update Salary Structure";
		$id = $this->input->post('id');
		$this->load->model('Hr_model');
		$this->load->model('Users_model');
		$data['user_records'] = $this->Users_model->get_user_list();
		$res = $this->Hr_model->update_salary_structure($id);
		if ($res) {
			$this->session->set_flashdata('success', 'Record Successfully Updated');
			redirect('Hr/view_salary_structure_list');
		}
	}

	function delete_basic_salary()
	{
		$id = $this->uri->segment('3');

		$this->load->model('Hr_model');
		$data['user_records'] = $this->Hr_model->delete_salary_structure($id);

		$this->session->set_flashdata('success', 'Delete Record Successfully');
		redirect('Hr/view_salary_structure_list');
	}
	///////////////////////////////////////emp_attendance////////////////////////////////////////////// 

	function add_emp_attendance()
	{
		$data['title'] = "Employee Attendance";

		// $this->load->model('Users_model');
		// $data['records'] = $this->Users_model->get_user_list();
		$this->load->model('Hr_model');

		$data['records'] = $this->Hr_model->get_employee_list();
		$data['main_content'] = 'hr/employee_attendance_add.php';
		$this->load->view('includes/template', $data);
	}
	function view_emp_attendance_list()
	{
		$data['title'] = "Employee Attendance List";
		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_emp_attendance_list();
		$data['main_content'] = 'hr/employee_attendance_list.php';
		$this->load->view('includes/template', $data);
	}

	function add_emp_attendance_data()
	{
		$data['title'] = "Add Employee Attendance";
		$this->load->model('Hr_model');
		$flag = $this->Hr_model->add_emp_attendance_data();
		if ($flag) {
			$this->session->set_flashdata('success', 'Record Successfully Saved');
			redirect('Hr/view_emp_attendance_list');
		} else {
			$this->session->set_flashdata('warning', 'Employee Name Already Exist');
			redirect('Hr/add_emp_attendance');
		}
	}

	function edit_emp_attendance()
	{
		$data['title'] = "Edit Employee Attendance";
		$id = $this->uri->segment('3');

		// $this->load->model('Users_model');
		// $data['records'] = $this->Users_model->get_user_list(); // Employee list for dropdown
    $this->load->model('Hr_model');
    $data['records'] = $this->Hr_model->get_employee_list();

		$this->load->model('Hr_model');
		$data['record1'] = $this->Hr_model->get_emp_attendance_by_id($id);

		  // Employee master details for pre-filling passport info
    if (!empty($data['record1']->employee_id)) {
        $data['record'] = $this->Hr_model->get_employee_by_id($data['record1']->employee_id);
    }


		$data['main_content'] = 'hr/employee_attendance_edit.php';
		$this->load->view('includes/template', $data);
	}

	function update_emp_attendance()
	{
		$data['title'] = "Attendance Data";
		$id = $this->input->post('emp_aId');
		$this->load->model('Hr_model');
		$res = $this->Hr_model->update_emp_attendance($id);
		if ($res) {
			$this->session->set_flashdata('success', 'Record Successfully Updated');
			redirect('Hr/view_emp_attendance_list');
		}
	}
	function delete_attendance_emp()
	{
		$id = $this->uri->segment('3');

		$this->load->model('Hr_model');
		$data['user_records'] = $this->Hr_model->delete_attendance_emp($id);

		$this->session->set_flashdata('success', 'Delete Record Successfully');
		redirect('Hr/view_emp_attendance_list');
	}
	///////////////////////////////////////add_emp_overtime////////////////////////////////////////////// 

	function add_emp_overtime()
	{
		$data['title'] = "Employee Overtime";

		// $this->load->model('Users_model');
		// $data['records'] = $this->Users_model->get_user_list();

		$this->load->model('Hr_model');

		$data['records'] = $this->Hr_model->get_employee_list();

		$data['main_content'] = 'hr/employee_overtime_add.php';
		$this->load->view('includes/template', $data);
	}
	function view_emp_overtime_list()
	{
		$data['title'] = "Employee Overtime List";
		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_emp_overtime_list();
		$data['main_content'] = 'hr/emp_overtime_list.php';
		$this->load->view('includes/template', $data);
	}

	function add_emp_overtime_data()
	{
		$data['title'] = "Add Overtime Data";
		$this->load->model('Hr_model');
		$flag = $this->Hr_model->add_emp_overtime_data();
		if ($flag) {
			$this->session->set_flashdata('success', 'Record Successfully Saved');
			redirect('Hr/view_emp_overtime_list');
		} else {
			$this->session->set_flashdata('warning', ' Record Already Exist');
			redirect('Hr/add_emp_overtime');
		}
	}

	public function edit_emp_overtime()
{
    $data['title'] = "Edit Employee Overtime";
    $id = $this->uri->segment(3);

    $this->load->model('Hr_model');

    // Get the single row of overtime to edit
    $data['row'] = $this->Hr_model->get_emp_overtime_by_id($id);

    // Get list of all employees for dropdown
    $data['records'] = $this->Hr_model->get_employee_list();

    $data['main_content'] = 'hr/emp_overtime_edit.php';
    $this->load->view('includes/template', $data);
}

	function update_emp_overtime()
	{
		$data['title'] = "Supplier Details";
		$id = $this->input->post('id');
		$this->load->model('Hr_model');
		$res = $this->Hr_model->update_emp_overtime($id);
		if ($res) {
			$this->session->set_flashdata('success', 'Record Successfully Updated');
			redirect('Hr/view_emp_overtime_list');
		}
	}
	function delete_overtime_emp()
	{
		$id = $this->uri->segment('3');

		$this->load->model('Hr_model');
		$data['user_records'] = $this->Hr_model->delete_emp_overtime($id);

		$this->session->set_flashdata('success', 'Delete Record Successfully');
		redirect('Hr/view_emp_overtime_list');
	}
	///////////////////////////////////////add_resignation////////////////////////////////////////////// 

	function add_resignation()
	{
		$data['title'] = "Add Resignation";

		$this->load->model('Hr_model');

		$data['records'] = $this->Hr_model->get_employee_list();

		// $this->load->model('Users_model');
		// 		$data['user_records'] = $this->Users_model->get_user_list();

		// $data['user_records'] = $this->Hr_model->get_resignation_active_list();

		$data['main_content'] = 'hr/resignation_emp_add.php';
		$this->load->view('includes/template', $data);
	}
	function view_emp_resignation_list()
	{
		$data['title'] = "Resignation List";
		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_employee_resignation_list();
		$data['main_content'] = 'hr/resignation_emp_list.php';
		$this->load->view('includes/template', $data);
	}

	function add_emp_resignation_data()
{
    $this->load->model('Hr_model');

    $employee_id = $this->input->post('employee_id');
    $resignation_date = $this->input->post('resignation_date');
    $last_working_date = $this->input->post('last_working_date');

    // Convert to comparable format (Y-m-d)
    $resignation_date = date('Y-m-d', strtotime($resignation_date));
    $last_working_date = date('Y-m-d', strtotime($last_working_date));

    // ✅ VALIDATION CHECK
    if (strtotime($last_working_date) < strtotime($resignation_date)) {
        $this->session->set_flashdata(
            'warning',
            'Effective Last Working Date cannot be earlier than the Resignation Date.'
        );
        redirect('Hr/add_resignation');
        return;
    }

    // Save data
    $flag = $this->Hr_model->add_resignation();

    if ($flag) {
        $this->session->set_flashdata('success', 'Record Successfully Saved');
        redirect('Hr/view_emp_resignation_list');
    } else {
        $this->session->set_flashdata('warning', 'Record Already Exists or Error Occurred');
        redirect('Hr/add_resignation');
    }
}

	function edit_emp_resignation()
{
    $data['title'] = "Edit Resignation";
    $id = $this->uri->segment(3);

    $this->load->model('Users_model');
    // $data['user_records'] = $this->Users_model->get_user_list();

    $this->load->model('Hr_model');
	  // Employee list (dropdown)
$data['user_records'] = $this->Hr_model->get_employee_list();

    $data['record'] = $this->Hr_model->get_employee_resigning_by_id($id); // single row
    $data['file_records'] = $this->Hr_model->get_employee_document_doc_id($id);

    $data['main_content'] = 'hr/resignation_emp_edit';
    $this->load->view('includes/template', $data);
}
	function update_emp_resignation()
	{
		$data['title'] = "Update Resignation";
		$id = $this->input->post('id');
		$this->load->model('Hr_model');
		$res = $this->Hr_model->update_resigning_application($id);
		if ($res) {
			$this->session->set_flashdata('success', 'Record Successfully Updated');
			redirect('Hr/view_emp_resignation_list');
		}
	}


	function print_resignation_application()
	{
		$id = $this->uri->segment('3');

		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_employee_list();

		$data['resignation'] = $this->Hr_model->get_employee_resigning_by_id($id);

		$this->load->model('Users_model');
		$data['record1'] = $this->Users_model->get_user_record_by_id_pass($id);

		$this->load->model('Setup_model');
		$data['dept_list'] = $this->Setup_model->get_active_department_list();

		$this->load->view('hr/print/print_resigning_application.php', $data);
	}

	function delete_resignation_application()
	{
		$id = $this->uri->segment('3');

		$this->load->model('Hr_model');
		$data['user_records'] = $this->Hr_model->delete_resignation_application($id);

		$this->session->set_flashdata('success', 'Delete Record Successfully');
		redirect('Hr/view_emp_resignation_list');
	}
	///////////////////////////////////////add_passport_release////////////////////////////////////////////// 

	function add_passport_release()
	{
		$data['title'] = "Passport Release";

		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_employee_list();


		$data['main_content'] = 'hr/passport_relese_add.php';
		$this->load->view('includes/template', $data);
	}
	function view_passport_release_list()
	{
		$data['title'] = "Passport Release List";
		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_passport_release_list();

		$data['main_content'] = 'hr/passport_relese_list.php';
		$this->load->view('includes/template', $data);
	}

	function add_emp_passport_release()
	{
		$data['title'] = "Passport Relese Add";
		$this->load->model('Hr_model');
		$flag = $this->Hr_model->add_passport_release();
		if ($flag) {
			$this->session->set_flashdata('success', 'Record Successfully Saved');
			redirect('Hr/view_passport_release_list');
		} else {
			$this->session->set_flashdata('warning', 'Name Already Exist');
			redirect('Hr/view_passport_release_list');
		}
	}
function edit_passport_release()
{
    $data['title'] = "Edit Release Passport";
    $id = $this->uri->segment(3);

    // Employee list for dropdown
    $this->load->model('Hr_model');
    $data['records'] = $this->Hr_model->get_employee_list();

    // Passport release record
    $this->load->model('Hr_model');
    $data['record1'] = $this->Hr_model->get_passport_release_list_by_id($id);

    // Employee master details for pre-filling passport info
    if (!empty($data['record1']->employee_id)) {
        $data['record'] = $this->Hr_model->get_employee_by_id($data['record1']->employee_id);
    }

    $data['main_content'] = 'hr/passport_release_edit.php';
    $this->load->view('includes/template', $data);
}

	function update_passport_release()
	{
		$data['title'] = "Update_Release Passport";
		$id = $this->input->post('id');

		$this->load->model('Hr_model');
		$res = $this->Hr_model->update_passport_re($id);
		if ($res) {
			$this->session->set_flashdata('success', 'Record Successfully Updated');
			redirect('Hr/view_passport_release_list');
		}
	}

	function print_passport_release()
	{
		$id = $this->uri->segment('3');
		$this->load->model('Hr_model');
		$data['record1'] = $this->Hr_model->get_passport_release_list_by_id($id);
		$data['records'] = $this->Hr_model->get_user_record_by_id($id);

		$this->load->model('Setup_model');
		$data['dept_list'] = $this->Setup_model->get_active_department_list();

		$this->load->view('hr/print/print_passport_release.php', $data);
	}

	function delete_passport_release()
	{
		$id = $this->uri->segment('3');

		$this->load->model('Hr_model');
		$data['user_records'] = $this->Hr_model->delete_passport_release($id);
		$this->session->set_flashdata('success', 'Delete Record Successfully');
		redirect('Hr/view_passport_release_list');
	}
	///////////////////////////////////////add_corporate_file////////////////////////////////////////////// 

	function add_corporate_file()
	{
		$data['title'] = "Corporate File";
		$data['main_content'] = 'hr/corporate_file_add.php';
		$this->load->view('includes/template', $data);
	}
	function view_corporate_file_list()
	{
		$data['title'] = "Corporate File List";
		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_corporate_file_list();
		$data['main_content'] = 'hr/corporate_file_list.php';
		$this->load->view('includes/template', $data);
	}

	function add_corporate_file_data()
	{
		$data['title'] = "Corporate File ";
		$this->load->model('Hr_model');
		$flag = $this->Hr_model->add_corporate_file_data();
		if ($flag) {
			$this->session->set_flashdata('success', 'Record Successfully Saved');
			redirect('Hr/view_corporate_file_list');
		} else {
			$this->session->set_flashdata('warning', 'Name Already Exist');
			redirect('Hr/add_corporate_file');
		}
	}

	function edit_corporate_file()
	{
		$data['title'] = "Corporate File Edit";
		$id = $this->uri->segment('3');

		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_corporate_file_id($id);
		$data['file_records'] = $this->Hr_model->get_employee_corporate_doc_id($id);
		$data['main_content'] = 'hr/corporate_file_edit.php';
		$this->load->view('includes/template', $data);
	}

	function update_corporate_file()
	{
		$data['title'] = "Update Corporate File";
		$id = $this->input->post('id');
		$this->load->model('Hr_model');
		$res = $this->Hr_model->update_corporate_file_data($id);
		if ($res) {
			$this->session->set_flashdata('success', 'Record Successfully Updated');
			redirect('Hr/view_corporate_file_list');
		}
	}

	function delete_corporate_file()
	{
		$id = $this->uri->segment('3');

		$this->load->model('Hr_model');
		$data['user_records'] = $this->Hr_model->delete_corporate_file_data($id);
		$this->session->set_flashdata('success', 'Delete Record Successfully');
		redirect('Hr/view_corporate_file_list');
	}
	///////////////////////////////////////add_vehicles////////////////////////////////////////////// 

	function add_vehicles()
	{
		$data['title'] = "Vehicle Details";
		$data['main_content'] = 'hr/vehicle_add.php';
		$this->load->view('includes/template', $data);
	}
	function view_vehicles_list()
	{
		$data['title'] = "Vehicle Details List";
		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_vehicle_list();
		$data['main_content'] = 'hr/vehicle_details_list.php';
		$this->load->view('includes/template', $data);
	}

	function add_vehicles_details()
	{
		$data['title'] = " Add Vehicle Details";
		$this->load->model('Hr_model');
		$flag = $this->Hr_model->add_vehicle_details();
		if ($flag) {
			$this->session->set_flashdata('success', 'Record Successfully Saved');
			redirect('Hr/view_vehicles_list');
		} else {
			$this->session->set_flashdata('warning', 'Vehicle Details Name Already Exist');
			redirect('Hr/add_vehicles');
		}
	}

	function edit_vehicles()
	{
		$data['title'] = " Edit Vehicle Details";
		$id = $this->uri->segment('3');

		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_vehicle_details_by_id($id);
		$data['main_content'] = 'hr/vehicle_details_edit.php';
		$this->load->view('includes/template', $data);
	}

	function update_vehicles()
	{
		$data['title'] = "Update Vehicle Details";
		$id = $this->input->post('id');
		$this->load->model('Hr_model');
		$res = $this->Hr_model->update_vehicle_details($id);
		if ($res) {
			$this->session->set_flashdata('success', 'Record Successfully Updated');
			redirect('Hr/view_vehicles_list');
		}
	}
	function delete_vehicle_details()
	{
		$id = $this->uri->segment('3');

		$this->load->model('Hr_model');
		$data['user_records'] = $this->Hr_model->delete_vehicle_data($id);
		$this->session->set_flashdata('success', 'Delete Record Successfully');
		redirect('Hr/view_vehicles_list');
	}
	///////////////////////////////////////add_monthly_salary////////////////////////////////////////////// 

	// function add_monthly_salary()
	// {
	// 	$data['title'] = "Monthly Salary";
	// 	$data['effective_date'] = date('M-Y');
	// 	$data['user_id'] = $this->input->post('user_id');
	// 	$this->load->model('Users_model');
	// 	$data['records'] = $this->Users_model->get_user_list();
	// 	if ($data['user_id'] == '')
	// 		$data['record1'] = array();
	// 	else {
	// 		$data['effective_date'] = $this->input->post('effective_date');
	// 		$data['user_id'] = $this->input->post('user_id');

	// 		$effective_date = $this->input->post('effective_date');
	// 		$selected_month_year = date('Y-m', strtotime($effective_date));
	// 		$start_date = date('Y-m-01', strtotime($selected_month_year));
	// 		$end_date = date('Y-m-t', strtotime($selected_month_year));
	// 		$data['days_in_month'] = date('t', strtotime($selected_month_year));


	// 		$this->load->model('Users_model');
	// 		$data['records'] = $this->Users_model->get_user_list();

	// 		$this->load->model('Hr_model');
	// 		$data['record1'] = $this->Hr_model->get_salary_structure_data();
	// 		foreach ($data['record1'] as $r) {
	// 			$data['record2'] = $this->Hr_model->get_salary_structure_details($r->sid);
	// 		}
	// 		$data['absent'] = $this->Hr_model->get_attendance_details();
	// 	}
	// 	$data['main_content'] = 'hr/emp_monthly_salary_add.php';
	// 	$this->load->view('includes/template', $data);
	// }
public function add_monthly_salary()
{
    $data['title'] = "Monthly Salary Report";

    $this->load->model('Hr_model');

    // ======================
    // MONTH SELECTION
    // ======================
    $effective_date = $this->input->post('effective_date');

    if (empty($effective_date)) {
        $effective_date = date('Y-m');
    }

    $data['effective_date'] = $effective_date;

    $selected_month = date('Y-m', strtotime($effective_date));
    $start_date     = date('Y-m-01', strtotime($selected_month));
    $end_date       = date('Y-m-t', strtotime($selected_month));
    $days_in_month  = date('t', strtotime($selected_month));

    // ======================
    // EMPLOYEE LIST
    // ======================
    $employees = $this->Hr_model->get_employee_list();

    $result = [];

    // ======================
    // LOOP EMPLOYEES
    // ======================
    foreach ($employees as $emp) {

        $emp_id = $emp->employee_id;

        // ======================
        // SKIP IF ALREADY GENERATED
        // ======================
        $exists = $this->Hr_model->check_salary_exist($emp_id, $start_date);

        if ($exists > 0) {
            continue;
        }

        // ======================
        // ATTENDANCE
        // ======================
        $attendance = $this->Hr_model->get_attendance_details($emp_id, $start_date, $end_date);

        $present_count = isset($attendance->present_count) ? (float)$attendance->present_count : 0;
        $half_count    = isset($attendance->half_count) ? (float)$attendance->half_count : 0;

        $present_days = ($present_count * 1) + ($half_count * 0.5);
        $leave_days   = max(0, $days_in_month - $present_days);

        // ======================
        // SALARY STRUCTURE
        // ======================
        $emp_structure = $this->Hr_model->get_salary_structure_data_new($emp_id);

        $basic_salary     = 0;
        $total_allowances = 0;
        $total_deductions = 0;

        if (!empty($emp_structure)) {

            $basic_salary = (float)$emp_structure->basic_salary;

            $details = $this->Hr_model->get_salary_structure_details($emp_structure->sid);

            foreach ($details as $row) {
                if ($row->allowance_type == 'A') {
                    $total_allowances += $row->amount;
                } else {
                    $total_deductions += $row->amount;
                }
            }
        }

        // ======================
        // SALARY CALCULATION (FIXED)
        // ======================
        $per_day = ($days_in_month > 0 && $basic_salary > 0)
            ? ($basic_salary / $days_in_month)
            : 0;

        if ($present_days <= 0) {

            // ❌ No attendance → no salary
            $monthly_basic = 0;
            $gross = 0;
            $net   = 0;

        } else {

            $monthly_basic = $per_day * $present_days;

            $gross = $monthly_basic + $total_allowances;

            $net   = $gross - $total_deductions;
        }

        // ======================
        // RESULT
        // ======================
        $result[] = (object)[
            'employee_id'   => $emp_id,
            'employee_name' => $emp->employee_name,
            'working_days'  => $days_in_month,
            'present_days'  => $present_days,
            'leave_days'    => $leave_days,
            'basic_salary'  => $basic_salary,
            'allowances'    => $total_allowances,
            'deductions'    => $total_deductions,
            'overtime'      => 0,
            'gross_salary'  => $gross,
            'net_pay'       => $net
        ];
    }

    // ======================
    // PASS TO VIEW
    // ======================
    $data['employee_salary_data'] = $result;

    $data['main_content'] = 'hr/emp_monthly_salary_add';
    $this->load->view('includes/template', $data);
}
	/*function add_monthly_salary_data()
	   {
		   $data['title'] = "Monthly Salary";

		   $data['effective_date'] =$this->input->post('effective_date');
		   $data['user_id'] = $this->input->post('user_id');
		   
		   $effective_date = $this->input->post('effective_date');
		   $selected_month_year = date('Y-m', strtotime($effective_date));
		   $start_date = date('Y-m-01', strtotime($selected_month_year));
		   $end_date = date('Y-m-t', strtotime($selected_month_year));
		   $data['days_in_month'] = date('t', strtotime($selected_month_year));
		   

		   $this->load->model('Users_model');
		   $data['records'] = $this->Users_model->get_user_list();

		   $this->load->model('Hr_model');
		   $data['record1'] = $this->Hr_model->get_salary_structure_data();
		   foreach($data['record1'] as $r)
		   {
			   $data['record2'] = $this->Hr_model->get_salary_structure_details($r->sid);
		   }
		   $data['absent'] = $this->Hr_model->get_attendance_details();

		   $data['main_content'] = 'hr/emp_monthly_salary_add.php';
		   $this->load->view('includes/template', $data);
	   }*/


	function view_emp_monthly_salary_list()
	{
		$data['title'] = "Monthly Salary List";
		$data['from'] = date('M-Y');
		// $data['to'] = ('Y-m-t');


		if ($this->input->post('from') != '') {
			$data['from'] = $this->input->post('from');
		}

		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_emp_monthly_salary_list($data['from']);
		$data['main_content'] = 'hr/emp_monthly_salary_list.php';
		$this->load->view('includes/template', $data);
	}

	




	function add_emp_monthly_salary()
	{
		$data['title'] = "Add Monthly Salary";
		$this->load->model('Hr_model');
		$flag = $this->Hr_model->add_emp_monthly_salary();
		if ($flag) {
			$this->session->set_flashdata('success', 'Record Successfully Saved');
			redirect('Hr/view_emp_monthly_salary_list');
		} else {
			$this->session->set_flashdata('warning', 'Name Already Exist');
			redirect('Hr/add_monthly_salary');
		}
	}

	function edit_emp_monthly_salary()
	{
		$data['title'] = "Edit Monthly Salary";
		$id = $this->uri->segment('3');

		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_emp_monthly_salary_by_id($id);
		$data['main_content'] = 'hr/emp_montly_salary_edit.php';
		$this->load->view('includes/template', $data);
	}

	function update_emp_monthly_salary()
	{
		$data['title'] = "Supplier Details";
		$id = $this->input->post('id');
		$this->load->model('Hr_model');
		$res = $this->Hr_model->update_emp_monthly_salary($id);
		if ($res) {
			$this->session->set_flashdata('success', 'Record Successfully Updated');
			redirect('Hr/view_emp_monthly_salary_list');
		}
	}
	function print_monthly_payslip()
	{
		$id = $this->uri->segment('3');

		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_employee_monthlypayslip_by_id($id);
		$data['record2'] = $this->Hr_model->get_monthly_salary_details($id);
		$this->load->view('hr/print/print_payslip.php', $data);
	}

	function print_monthly_record()
	{
		$data['from'] = $this->input->post('from');
		$data['to'] = ('Y-m-t');


		if ($this->input->post('from') != '') {
			$data['from'] = $this->input->post('from');
		}

		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_emp_monthly_salary_list($data['from']);
		$this->load->view('hr/print/print_payslip_record.php', $data);

	}


	function export_monthly_record()
	{

		$data['from'] = $this->input->post('from');
		$data['to'] = ('Y-m-t');


		if ($this->input->post('from') != '') {
			$data['from'] = $this->input->post('from');
		}

		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_emp_monthly_salary_list($data['from']);

		$this->load->view('hr/print/export_payslip_record.php', $data);
	}


	////////////////////////////////////////gratuaty-start/////////////////////////////////////////////
	function add_gratuity()
	{
		$data['title'] = "Gratuity Details";

		$this->load->model('Users_model');
		$data['user_records'] = $this->Users_model->get_user_list();
		$this->load->model('Hr_model');
		$data['record1'] = $this->Hr_model->get_allowances_list();

		$data['main_content'] = 'hr/add_gratuity_details.php';
		$this->load->view('includes/template', $data);
	}
	function view_gratuity_list()
	{
		$data['title'] = "Gratuity Details List";
		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_vehicle_list();
		$data['main_content'] = 'hr/gratuity_detail_list.php';
		$this->load->view('includes/template', $data);
	}

	function add_gratuity_details()
	{
		$data['title'] = " Add Gratuity Details";
		$this->load->model('Hr_model');
		$flag = $this->Hr_model->add_vehicle_details();
		if ($flag) {
			$this->session->set_flashdata('success', 'Record Successfully Saved');
			redirect('Hr/view_gratuity_list');
		} else {
			$this->session->set_flashdata('warning', 'Gratuity Details Name Already Exist');
			redirect('Hr/add_gratuity');
		}
	}

	function edit_gratuity_details()
	{
		$data['title'] = " Edit Gratuity Details";
		$id = $this->uri->segment('3');

		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_vehicle_details_by_id($id);
		$data['main_content'] = 'hr/edit_gratuity_details.php';
		$this->load->view('includes/template', $data);
	}

	function update_gratuity()
	{
		$data['title'] = "Update Gratuity Details";
		$id = $this->input->post('id');
		$this->load->model('Hr_model');
		$res = $this->Hr_model->update_vehicle_details($id);
		if ($res) {
			$this->session->set_flashdata('success', 'Record Successfully Updated');
			redirect('Hr/view_gratuity_list');
		}
	}
	function delete_gratuity_details()
	{
		$id = $this->uri->segment('3');

		$this->load->model('Hr_model');
		$data['user_records'] = $this->Hr_model->delete_vehicle_data($id);
		$this->session->set_flashdata('success', 'Delete Record Successfully');
		redirect('Hr/view_gratuity_list');
	}



	///employee_corner
	//////////////////////////////employee corner //////////////////
	/////////////////////////////////leavev///////////////////////////
	function add_leave_corner_application()
	{
		$data['title'] = "Leave application";
		$this->load->model('Users_model');
		$data['user_records'] = $this->Users_model->get_user_list();
		$data['main_content'] = 'hr/leave_corner_allocation_add.php';
		$this->load->view('includes/template', $data);
	}
	function view_leave_corner_application_list()
	{
		$data['title'] = "Leave application";
		$id = $this->uri->segment('3');
		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_employee_leave_corner_list();
		// $data['records'] = $this->Hr_model->get_employee_leave_by_id($id);
		$data['record1'] = $this->Hr_model->leave_approval_list();
		$data['main_content'] = 'hr/leave_corner_allocation_list.php';
		$this->load->view('includes/template', $data);
	}

	function add_leave_corner_application_data()
	{
		$data['title'] = "Leave application";
		$this->load->model('Hr_model');
		$flag = $this->Hr_model->add_employee_leave_data();
		if ($flag) {
			$this->session->set_flashdata('success', 'Record Successfully Saved');
			redirect('Hr/view_leave_corner_application_list');
		} else {
			$this->session->set_flashdata('warning', 'employee Company Name Already Exist');
			redirect('Hr/view_leave_corner_application_list');
		}
	}

	function edit_leave_corner_application()
	{
		$data['title'] = "Edit Leave application";
		$id = $this->uri->segment('3');

		$this->load->model('Users_model');
		$data['user_records'] = $this->Users_model->get_user_list();
		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_employee_leave_by_id($id);
		$data['file_records'] = $this->Hr_model->get_employee_leave_doc_id($id);
		$data['main_content'] = 'hr/leave_corner_allocation_edit.php';
		$this->load->view('includes/template', $data);
	}

	function update_leave_corner_application()
	{
		$data['title'] = "Edit Leave application";
		$id = $this->input->post('id');
		$this->load->model('Hr_model');
		$res = $this->Hr_model->update_employee_leave($id);
		if ($res) {
			$this->session->set_flashdata('success', 'Record Successfully Updated');
			redirect('Hr/view_leave_corner_application_list');
		}
	}
	function delete_leave_corner_application()
	{
		$id = $this->uri->segment('3');

		$this->load->model('Hr_model');
		$data['user_records'] = $this->Hr_model->delete_leave_application($id);

		$this->session->set_flashdata('success', 'Record Delete Successfully');
		redirect('Hr/view_leave_corner_application_list');
	}
	////////////////////////////////emd employee leave employee corner//////////////////////
	/// start resigignation///////////


	///////////////////////////////////////add_resignation////////////////////////////////////////////// 

	function add_regignation_corner()
	{
		$data['title'] = "Add Resignaion";

		$this->load->model('Hr_model');
		$data['user_records'] = $this->Hr_model->get_resignation_active_list();

		$data['main_content'] = 'hr/resignation_corner_emp_add.php';
		$this->load->view('includes/template', $data);
	}
	function view_emp_regignation_corner_list()
	{
		$data['title'] = "Resignation List";
		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_employee_regignation_corner_list();
		$data['main_content'] = 'hr/resignation_corner_emp_list.php';
		$this->load->view('includes/template', $data);
	}

	function add_emp_regignation_corner_data()
	{
		$data['title'] = "Add resignation";
		$this->load->model('Hr_model');
		$flag = $this->Hr_model->add_resignation();
		if ($flag) {
			$this->session->set_flashdata('success', 'Record Successfully Saved');
			redirect('Hr/view_emp_regignation_corner_list');
		} else {
			$this->session->set_flashdata('warning', 'employee  Name Already Exist');
			redirect('Hr/add_regignation_corner');
		}
	}

	function edit_emp_regignation_corner()
	{
		$data['title'] = "Edit Resignation";
		$id = $this->uri->segment('3');

		$this->load->model('Users_model');
		$data['user_records'] = $this->Users_model->get_user_list();

		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_employee_resigning_by_id($id);
		$data['file_records'] = $this->Hr_model->get_employee_document_doc_id($id);

		$data['main_content'] = 'hr/resignation_corner_emp_edit.php';
		$this->load->view('includes/template', $data);
	}
	function update_emp_regignation_corner()
	{
		$data['title'] = "Update Resignation";
		$id = $this->input->post('id');
		$this->load->model('Hr_model');
		$res = $this->Hr_model->update_resigning_application($id);
		if ($res) {
			$this->session->set_flashdata('success', 'Record Successfully Updated');
			redirect('Hr/view_emp_regignation_corner_list');
		}
	}
	/////////////////////////////// start approval setp///////////////////////////////////
	function approval_setup()
	{
		$data['title'] = "Approval Setup";

		$this->load->model('Users_model');
		$data['user_records'] = $this->Users_model->get_user_list();
		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_approval_setup_list();

		$data['main_content'] = 'hr/approval_setup.php';
		$this->load->view('includes/template', $data);
	}
	function add_approve_data()
	{
		$data['title'] = "Approval Setup";
		$this->load->model('Hr_model');
		$flag = $this->Hr_model->add_approve_data();
		if ($flag) {
			$this->session->set_flashdata('success', 'Record Successfully Saved');
			redirect('Hr/approval_setup');
		} else {
			$this->session->set_flashdata('warning', ' data Already Exist');
			redirect('Hr/approval_setup');
		}
	}

	///////////////////////////////////////start Advance Salary//////////////////////////////////////////

	function add_advance_salary()
	{
		$data['title'] = "Advance Salary";

		$this->load->model('Hr_model');
		$data['user_records'] = $this->Hr_model->get_user_list();

		$data['main_content'] = 'hr/add_advance_salary.php';
		$this->load->view('includes/template', $data);
	}
	function view_advance_salary_list()
	{
		$data['title'] = "Advance Salary List";
		$this->load->model('Hr_model');
		$data['records'] = $this->Hr_model->get_advance_salary_list();
		$data['main_content'] = 'hr/list_advance_salary.php';
		$this->load->view('includes/template', $data);
	}

	function add_advance_salary_details()
	{
		$data['title'] = " Add Advance Salary";
		$this->load->model('Hr_model');
		$flag = $this->Hr_model->add_advance_salary();
		if ($flag) {
			$this->session->set_flashdata('success', 'Record Successfully Saved');
			redirect('Hr/view_advance_salary_list');
		} else {
			$this->session->set_flashdata('warning', 'Record Already Exist');
			redirect('Hr/add_advance_salary');
		}
	}

	function edit_advance_salary()
	{
		$data['title'] = " Edit Advance Salary ";
		$id = $this->uri->segment('3');

		$this->load->model('Hr_model');
		$data['user_records'] = $this->Hr_model->get_user_list();
		$data['records'] = $this->Hr_model->get_advance_salary_list_by_id($id);
		$data['main_content'] = 'hr/edit_advance_salary.php';
		$this->load->view('includes/template', $data);
	}

	function update_advance_salary()
	{
		$data['title'] = "Update Advance Salary";
		$id = $this->input->post('id');
		$this->load->model('Hr_model');
		$res = $this->Hr_model->update_advance_salary($id);
		if ($res) {
			$this->session->set_flashdata('success', 'Record Successfully Updated');
			redirect('Hr/view_advance_salary_list');
		}
	}
	function delete_advance_salary()
	{
		$id = $this->uri->segment('3');

		$this->load->model('Hr_model');
		$data['user_records'] = $this->Hr_model->delete_advance_salary($id);
		$this->session->set_flashdata('success', 'Delete Record Successfully');
		redirect('Hr/view_advance_salary_list');
	}


	public function delete_resignation_document()
{
    $doc_id = $this->input->post('doc_id');

    $file = $this->db->get_where('employee_resignation_documents', [
        'doc_id' => $doc_id
    ])->row();

    if ($file) {

        $path = FCPATH . 'public/uploaded_documents/' . $file->document_path;

        if (file_exists($path)) {
            unlink($path);
        }

        $this->db->where('doc_id', $doc_id);
        $this->db->delete('employee_resignation_documents');
    }

    echo 1;
}

public function add_monthly_salary_data()
{
    $this->load->model('Hr_model');

    // =======================
    // POST DATA DEBUG
    // =======================
    $employee_ids   = $this->input->post('employee_ids');
    $effective_date = $this->input->post('effective_date');

    log_message('debug', 'EMPLOYEE IDS: ' . print_r($employee_ids, true));
    log_message('debug', 'EFFECTIVE DATE: ' . $effective_date);

    if (empty($employee_ids)) {
        $this->session->set_flashdata('error', 'No employees selected');
        redirect('Hr/add_monthly_salary');
    }

    // =======================
    // MONTH FIX + DEBUG
    // =======================
    if (empty($effective_date)) {
        $effective_date = date('Y-m');
    }

    $month = date('Y-m', strtotime($effective_date . '-01'));
    $start_date = $month . '-01';
    $end_date   = date('Y-m-t', strtotime($start_date));
    $days_in_month = date('t', strtotime($start_date));

    log_message('debug', "MONTH: $month | START: $start_date | END: $end_date | DAYS: $days_in_month");

    foreach ($employee_ids as $emp_id)
    {
        log_message('debug', "PROCESSING EMPLOYEE: $emp_id");

        // =======================
        // EMPLOYEE DATA
        // =======================
        $emp = $this->Hr_model->get_employee_by_id($emp_id);

        if (!$emp) {
            log_message('error', "Employee not found: $emp_id");
            continue;
        }

        // =======================
        // ATTENDANCE DEBUG
        // =======================
        $attendance = $this->Hr_model->get_attendance_details($emp_id, $start_date, $end_date);

        log_message('debug', 'ATTENDANCE RAW: ' . print_r($attendance, true));

        $present = isset($attendance->present_count) ? (float)$attendance->present_count : 0;
        $half    = isset($attendance->half_count) ? (float)$attendance->half_count : 0;

        $present_days = ($present * 1) + ($half * 0.5);
        $leave_days   = max(0, $days_in_month - $present_days);

        log_message('debug', "PRESENT: $present | HALF: $half | TOTAL: $present_days");

        // =======================
        // SALARY STRUCTURE DEBUG
        // =======================
        $structure = $this->Hr_model->get_salary_structure_by_employee($emp_id);

        log_message('debug', 'STRUCTURE: ' . print_r($structure, true));

        $basic_salary = 0;
        $sid = 0;

        if (!empty($structure)) {
            $basic_salary = (float)$structure->basic_salary;
            $sid = $structure->sid;
        }

        log_message('debug', "BASIC SALARY: $basic_salary | SID: $sid");

        // =======================
        // STRUCTURE DETAILS
        // =======================
        $details = $this->Hr_model->get_salary_structure_details($sid);

        log_message('debug', 'STRUCTURE DETAILS: ' . print_r($details, true));

        $total_allowance = 0;
        $total_deduction = 0;

        foreach ($details as $row) {
            if ($row->allowance_type == 'A') {
                $total_allowance += $row->amount;
            } else {
                $total_deduction += $row->amount;
            }
        }

        log_message('debug', "ALLOWANCE: $total_allowance | DEDUCTION: $total_deduction");

        // =======================
        // SALARY CALCULATION
        // =======================
        $per_day = ($days_in_month > 0) ? ($basic_salary / $days_in_month) : 0;
        $monthly_basic = $per_day * $present_days;

        $gross = $monthly_basic + $total_allowance;
        $net   = $gross - $total_deduction;

        log_message('debug', "PER DAY: $per_day | MONTH BASIC: $monthly_basic | GROSS: $gross | NET: $net");

        // =======================
        // INSERT DATA
        // =======================
        $data = [
            'emp_id'          => $emp_id,
            'salary_month'    => $start_date,
            'working_days'    => $days_in_month,
            'present_days'    => $present_days,
            'leave_days'      => $leave_days,
            'basic_salary'    => $basic_salary,
            'total_allowance' => $total_allowance,
            'total_deduction' => $total_deduction,
            'overtime'        => 0,
            'gross_salary'    => $gross,
            'net_salary'      => $net,
            'created_data'    => date('Y-m-d H:i:s')
        ];

        log_message('debug', 'FINAL INSERT: ' . print_r($data, true));

        $this->db->insert('employee_monthly_salary', $data);
    }

    $this->session->set_flashdata('success', 'Salary generated successfully');
    redirect('Hr/view_emp_monthly_salary_list');
}
	///////////////////////////////////////////End advance salary//////////////////////////////////////////


}
