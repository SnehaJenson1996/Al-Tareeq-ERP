<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warranty extends CI_Controller {

	
	public function __construct() {
		parent::__construct();

		if (!$this->session->userdata('is_logged_in')) {
			redirect('Login/login');
		}
	
		// Prevent browser caching
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
		$this->output->set_header("Pragma: no-cache");

		$this->load->model('Warranty_model');
        $this->load->helper('menu_helper');
	}

    public function list_warranty_claims(){
		$user = $this->session->userdata('user_id');
		if (!has_view_access($user,'Warranty/list_warranty_claims')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
			
		}
		else{
			$data['title']='Warranty List';

			$data['warraty_claims'] = $this->Warranty_model->get_all_warranty_claims(0);

			$data['main_content']='warranty/list_warranty_claims.php';
			
		}
		$this->load->view('includes/template',$data);
		
	}

    public function add_warranty_claim(){
		$user = $this->session->userdata('user_id');
		if (!has_access($user,'Warranty/list_warranty_claims','A')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
			
		}
		else{
			$data['title']='Add Warranty Claim';

			$data['main_content']='warranty/add_warranty_claim.php';
			
		}
		$this->load->view('includes/template',$data);
		
	}

	public function add_warranty_claim_data(){
		$result = $this->Warranty_model->add_warranty_claim_data();

		if($result){
			echo 'Added';
		}
		else{
			echo 'Not Added';
		}
		redirect('Warranty/list_warranty_claims');
	}

	public function edit_warranty_claim(){
		$user = $this->session->userdata('user_id');
		if (!has_access($user,'Warranty/list_warranty_claims','E')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
			
		}
		else{
			$data['title']='Edit Warranty Claim';

			$warranty_id = $this->uri->segment('3');
			$data['warranty']= $this->Warranty_model->get_warranty_by_id($warranty_id);
			$data['warranty_details']= $this->Warranty_model->get_warranty_details_by_id($warranty_id,0);
			$data['main_content']='warranty/edit_warranty_claim.php';
			
		}
		$this->load->view('includes/template',$data);
		
	}

	public function update_warranty_claim_data(){
		$result = $this->Warranty_model->update_warranty_claim_data();

		if($result){
			echo 'Added';
		}
		else{
			echo 'Not Added';
		}
		redirect('Warranty/list_warranty_claims');
		
	}

	public function list_issued_claims(){
		$user = $this->session->userdata('user_id');
		if (!has_view_access($user,'Warranty/list_issued_claims')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
			
		}
		else{
			$data['title']='Warranty List';

			$data['warraty_claims'] = $this->Warranty_model->get_all_warranty_claims(1);

			$data['main_content']='warranty/list_warranty_claims.php';
			
		}
		$this->load->view('includes/template',$data);
	}
    
	public function issue_warranty_claim(){
		$user = $this->session->userdata('user_id');
		if (!has_access($user,'Warranty/list_issued_claims','A')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
			
		}
		else{
			$data['title']='Add Warranty Issue';

			$data['warraty_claims'] = $this->Warranty_model->get_all_warranty_claims(0);
			if(isset($_POST['warranty_id'])){
				$data['warranty'] = $this->Warranty_model->get_warranty_by_id($_POST['warranty_id']);
				$data['warranty_details'] = $this->Warranty_model->get_warranty_details_by_id($_POST['warranty_id'],0);
			}

			$data['main_content']='warranty/issue_warranty.php';
			
		}
		$this->load->view('includes/template',$data);
	}

	public function issue_warranty_data(){
		$result = $this->Warranty_model->issue_warranty_data();

		if($result){
			echo 'Added';
		}
		else{
			echo 'Not Added';
		}
		redirect('Warranty/list_issued_claims');
	}

	public function print_warranty_claim(){
		$user = $this->session->userdata('user_id');
		if (!has_view_access($user,'Warranty/list_issued_claims')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
			$this->load->view('includes/template',$data);
		}
		else{
			$this->load->model('Setup_model');
			$warranty_id = $this->uri->segment('3');

			$data['warranty'] = $this->Warranty_model->get_warranty_by_id($warranty_id);
			$data['warranty_details'] = $this->Warranty_model->get_warranty_details_by_id($warranty_id,1);
			//echo '<pre>';print_r($data['warranty_details']);exit;
			$this->load->view('warranty/print_warranty',$data);
		}
	}
}