<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
use Dompdf\Options;
class SampleRequest extends CI_Controller {
    public function __construct() {
		parent::__construct();

		if (!$this->session->userdata('is_logged_in')) {
			redirect('Login/login');
		}
	
		// Prevent browser caching
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
		$this->output->set_header("Pragma: no-cache");

		$this->load->model('SampleRequest_model');
		$this->load->helper('menu_helper');
	}

    //sample requests
	function list_sample_requests(){
		$user = $this->session->userdata('user_id');
		if (!has_view_access($user,'SampleRequest/list_sample_requests')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
		 	$data['title'] = 'Sample Request';
			$data['sample_requests'] = $this->SampleRequest_model->list_sample_requests();
			$data['main_content']='sample_request/list_sample_requests.php';
		}
		$this->load->view('includes/template',$data);
	}

	function add_sample_request(){
		$user = $this->session->userdata('user_id');
		if (!has_access($user,'SampleRequest/list_sample_requests','A')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
		 	$data['title'] = 'Add Sample Request';
			$prefix='AVSRF#';
			$this->load->model('Setup_model');
            $this->load->model('Sales_model');
			$num = $this->Setup_model->get_next_code($prefix,'request_code','sample_request_master',7)+1;
			$digit=sprintf("%1$05d",$num);
			$data['return_code'] =$prefix.$digit.'_'.date('y');
			$data['all_enquiries'] = $this->Sales_model->get_all_enquiry_list();
			if(isset($_POST['enquiry_id'])){
				$this->load->model('Item_model');
				$data['enquiry'] = $this->Sales_model->get_enquiry_by_id($_POST['enquiry_id']);
				$data['active_units'] = $this->Item_model->get_active_unit_list();
			}
			$data['main_content']='sample_request/add_sample_request.php';
		}
		$this->load->view('includes/template',$data);
	}

	function add_sample_request_data(){
		$result = $this->SampleRequest_model->add_sample_request_data();

		if($result){
			echo 'Added';
		}
		else{
			echo 'Not Added';
		}
		redirect('SampleRequest/list_sample_requests');
	}

	public function edit_sample_request(){
		$user = $this->session->userdata('user_id');
		if(!has_access($user,'SampleRequest/list_sample_requests','E')){
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
			$data['title'] = 'Edit Sample Request';
			$request_id = $this->uri->segment('3');
			$data['request'] = $this->SampleRequest_model->get_sample_request_by_id($request_id);
			$data['request_details'] = $this->SampleRequest_model->get_sample_request_details($request_id,1);
			$data['main_content']='sample_request/edit_sample_request';
		}
		$this->load->view('includes/template',$data);
		
	}

	function update_sample_request_data(){
		$result = $this->SampleRequest_model->update_sample_request_data();

		if($result){
			echo 'Added';
		}
		else{
			echo 'Not Added';
		}
		redirect('SampleRequest/list_sample_requests');
	}

    function approve_sample_request(){
        $request_id = $_POST['request_id'];
        $result = $this->SampleRequest_model->approve_sample_request($request_id);
        echo $result;
    }

    function issue_request(){
        $user = $this->session->userdata('user_id');
		if (!has_access($user,'SampleRequest/list_issued_requests','A')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
		 	$data['title'] = 'Issue Sample Request';
			$data['approved_requests'] = $this->SampleRequest_model->list_sample_requests(1);
			if(isset($_POST['request_id'])){
				$data['request'] = $this->SampleRequest_model->get_sample_request_by_id($_POST['request_id']);
				$data['request_details'] = $this->SampleRequest_model->get_sample_request_details($_POST['request_id'],1);
			}
			$data['main_content']='sample_request/issue_request.php';
		}
		$this->load->view('includes/template',$data);
    }

    function issue_request_data(){
        $result = $this->SampleRequest_model->issue_request_data();

		if($result){
			echo 'Added';
		}
		else{
			echo 'Not Added';
		}
		redirect('SampleRequest/list_issued_requests');
    }

	function list_issued_requests(){
		$user = $this->session->userdata('user_id');
		if (!has_view_access($user,'SampleRequest/list_sample_requests')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
		 	$data['title'] = 'Issued Requests';
			$data['sample_requests'] = $this->SampleRequest_model->list_sample_requests(2);
			$data['main_content']='sample_request/list_sample_requests.php';
		}
		$this->load->view('includes/template',$data);
	}

	function print_sample_request(){
		$user = $this->session->userdata('user_id');
		if(!has_view_access($user,'SampleRequest/list_sample_requests')){
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
			$this->load->view('includes/template',$data);
		}
		else{
			$this->load->model('Setup_model');
			$request_id = $this->uri->segment('3');
			$data['request'] = $this->SampleRequest_model->get_sample_request_by_id($request_id);
			$data['request_details'] = $this->SampleRequest_model->get_sample_request_details($request_id,2);
			$data['total_rows'] = 10;
			$html = $this->load->view('sample_request/print_sample_request', $data, true);
			$options = new \Dompdf\Options();
			$options->set('isRemoteEnabled', true);

			$dompdf = new \Dompdf\Dompdf($options);
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'portrait'); // or landscape
			$dompdf->render();

			$dompdf->stream("document.pdf", array("Attachment" => false));
			//$this->load->view('sample_request/print_sample_request',$data);
		}
	}

	function list_returned_samples(){
		$user = $this->session->userdata('user_id');
		if (!has_view_access($user,'SampleRequest/list_returned_samples')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
		 	$data['title'] = 'Returned Requests';
			$data['sample_requests'] = $this->SampleRequest_model->list_sample_requests(3);
			$data['main_content']='sample_request/list_sample_requests.php';
		}
		$this->load->view('includes/template',$data);
	}

	function add_returned_sample(){
        $user = $this->session->userdata('user_id');
		if (!has_access($user,'SampleRequest/list_returned_samples','A')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}
		else{
		 	$data['title'] = 'Return Sample';
			$data['issued_requests'] = $this->SampleRequest_model->list_sample_requests(2);
			if(isset($_POST['request_id'])){
				$this->load->model('Item_model');
				$data['request'] = $this->SampleRequest_model->get_sample_request_by_id($_POST['request_id']);
				$data['request_details'] = $this->SampleRequest_model->get_sample_request_details($_POST['request_id'],2);
			}
			$data['main_content']='sample_request/return_sample.php';
		}
		$this->load->view('includes/template',$data);
    }

	function add_returned_sample_data(){
        $result = $this->SampleRequest_model->add_returned_sample_data();

		if($result){
			echo 'Added';
		}
		else{
			echo 'Not Added';
		}
		redirect('SampleRequest/list_returned_samples');
    }

	// function add_sample_invoice(){
    //     $user = $this->session->userdata('user_id');
	// 	if (!has_access($user,'SampleRequest/add_sample_invoice','A')) {
	// 		$data['title'] = 'Access Denied';
	// 		$data['main_content']='errors/access_control.php';
	// 	}
	// 	else{
	// 	 	$data['title'] = 'Invoices';
	// 		if(isset($_POST['request_id'])){
	// 			$prefix='AVI#';
	// 			$this->load->model('Setup_model');
	// 			$num = $this->Setup_model->get_next_code($prefix,'invoice_code','invoice_master',5)+1;
	// 			$digit=sprintf("%1$05d",$num);
	// 			$data['invoice_code'] =$prefix.$digit.'_'.date('y');
	// 			$data['request'] = $this->SampleRequest_model->get_sample_request_by_id($_POST['request_id']);
	// 			$data['request_details'] = $this->SampleRequest_model->get_sample_request_details($_POST['request_id'],3);
	// 		}
	// 		$data['sample_requests'] = $this->SampleRequest_model->list_sample_requests(3);
	// 		$data['main_content']='sample_request/add_sample_invoice.php';
	// 	}
		
	// 	$this->load->view('includes/template',$data);
    // }


	
}