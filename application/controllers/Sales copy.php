<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Dompdf\Dompdf;
use Dompdf\Options;
class Sales extends CI_Controller {

	
	public function __construct() {
		parent::__construct();

		if (!$this->session->userdata('is_logged_in')) {
			redirect('Login/login');
		}
	
		// Prevent browser caching
		$this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		$this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
		$this->output->set_header("Pragma: no-cache");

		$this->load->model('Sales_model');
		$this->load->model('Company_model');
		$this->load->helper('menu_helper');
	}

	//enquiries
	public function list_enquiries(){
		$user = $this->session->userdata('user_id');
		if (!has_view_access($user,'Sales/list_enquiries')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
			
		}
		else{
			$data['title']='Enquiry List';

			$data['all_enquiries'] = $this->Sales_model->get_all_enquiry_list();

			$data['main_content']='sales/enquiry/list_enquiries.php';
			
		}
		$this->load->view('includes/template',$data);
		
	}

	function add_enquiry($status="",$enqid="",$survey_id=""){
		$user = $this->session->userdata('user_id');
		if (!has_access($user,'Sales/list_enquiries','A')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
			$this->load->view('includes/template',$data);
			
		}else{
			 switch ($status) {
            case "": // CASE 1: status is empty
					$data['title']='New Enquiry ';        
					$prefix='AL'.date("y").'-ENQ';
					$data['status']=1;
					$data['menu_status']=1;
					$this->load->model('Setup_model');
					$num = $this->Setup_model->get_next_code($prefix,'enquiry_code','enquiry_master',11)+1;
					$digit=sprintf("%1$05d",$num);
					$data['enquiry_code'] =$prefix.$digit;
					$this->load->model('Company_model');
					$data['customer_list'] = $this->Company_model->get_all_customer_list();
					$data['branch_list']=$this->Company_model->get_all_branches();
					//$data['active_customers'] = $this->Setup_model->get_active_customer_list();
					$data['active_users'] = $this->Setup_model->get_active_user_list();
					$this->load->model('Item_model');
					$data['all_products']=$this->Item_model->get_all_item_list();
					$data['active_units']=$this->Item_model->get_all_units();
					$data['main_content']='sales/enquiry/add_enquiry.php';
					$this->load->view('includes/template',$data);
					break;

            case "1": // CASE 2: status is 1	
					$enquiry_data=$this->Sales_model->get_enquiry_by_id($enqid);
					$branch_id= $enquiry_data['branch_id'];
					$data['title'] = $enquiry_data['enquiry_code'];
					$data['menu_status']=2;
					$data['status_info'] = "New";
					$data['enquiry_data']=$enquiry_data;
					$data['enquiryid']=$enqid;
					$this->load->model('Company_model');
					$data['customer_list'] = $this->Company_model->get_customers_by_branch($branch_id);
					$this->load->model('Item_model');
					$data['all_products']=$this->Item_model->get_all_item_list();
					$data['active_units']=$this->Item_model->get_all_units();
					///Nedd to get employee by designation 
					$des_id=2;
					$data['employee_list'] = $this->Company_model->get_all_employees_designation_id($des_id,$branch_id);	
					$data['main_content'] = 'sales/enquiry/add_enquiry.php';
					$this->load->view('includes/template',$data);
					break;
			case "2": // CASE 2: status is 2 inserted site survey	
					$enquiry_data=$this->Sales_model->get_enquiry_by_id($enqid);
					$branch_id= $enquiry_data['branch_id'];
					$survey_data=$this->Sales_model->get_survey_by_id($survey_id);
					$data['title'] = $enquiry_data['enquiry_code'];
					$data['status_info'] = "New";
					$data['menu_status']=2;
					$data['enquiry_data']=$enquiry_data;
					$data['survey_data']=$survey_data;
					$data['enquiryid']=$enqid;
					$this->load->model('Company_model');
					$data['customer_list'] = $this->Company_model->get_all_customer_list();
					$this->load->model('Item_model');
					$data['all_products']=$this->Item_model->get_all_item_list();
					$data['active_units']=$this->Item_model->get_all_units();
					$data['survey_files']=$this->Sales_model->get_survey_files_by_id($survey_data['survey_id']);
					///Nedd to get employee by designation 
					$des_id=2;
					$data['employee_list'] = $this->Company_model->get_all_employees_designation_id($des_id,$branch_id);	
					$data['main_content'] = 'sales/enquiry/add_enquiry.php';
					$this->load->view('includes/template',$data);
					break;
			case "3":
					$data['title'] = "Estimation";
					$enquiry_data=$this->Sales_model->get_enquiry_by_id($enqid);
					$branch_id= $enquiry_data['branch_id'];
					$data['title'] = $enquiry_data['enquiry_code'];
					$data['status_info'] = "New";
					$data['menu_status']=3;
					$data['enquiry_data']=$enquiry_data;
					$data['enquiryid']=$enqid;
					$this->load->model('Company_model');
					$data['customer_list'] = $this->Company_model->get_all_customer_list();
					$this->load->model('Item_model');
					$data['all_products']=$this->Item_model->get_all_item_list();
					$data['active_units']=$this->Item_model->get_all_units();
					$data['main_content'] = 'sales/enquiry/add_enquiry.php';
					$this->load->view('includes/template',$data);
					break;
        }
			
		}
		
		
		
	}
	function save_enquiry(){
		$site_survey=!empty($this->input->post('allow_site_survey'))?1:0;	
		 // Prepare data for insertion
		$data = [
			'project_name'      => $this->input->post('project_name'),
			'project_subject'   => $this->input->post('project_subject'),
			'project_location'  => $this->input->post('project_location'),
			//'enquiry_type'      => $this->input->post('enquiry_type'),
			'branch_id'      	=> $this->input->post('branch'),
			'enquiry_code'      => trim($this->input->post('enquiry_code')),
			'enquiry_date'      => $this->input->post('enquiry_date'),
			'enquiry_source'    => $this->input->post('enquiry_source'),
			'enquiry_category'  => $this->input->post('enquiry_category'),
			'enquiry_customer'	=> $this->input->post('customer_id'),
			'enquiry_status'	=> 1,//New
			'site_survey'		=> $site_survey,
			'created_by'		=> $this->session->userdata('user_id'),
			'client_ref_no'     => $this->input->post('client_ref_no'),
			'comments'          => $this->input->post('comments'),
			'created_at'        => date('Y-m-d H:i:s')
		];
		// Insert into the database
		$inserted = $this->Sales_model->insert_enquiry($data);
		$status=!empty($site_survey)?1:3;
		if ($inserted) {
				$this->session->set_flashdata('success', 'Enquiry saved successfully.');
				redirect('Sales/add_enquiry/'.$status.'/'.$inserted);
			} else {
				$this->session->set_flashdata('error', 'Failed to save enquiry.');
				redirect('Sales/add_enquiry');
			}
		// Redirect back to add enquiry form
		
       
   }
	function save_survey(){		
		$resurvey=$this->input->post('re_survey_id');
		$enquiry_id=$this->input->post('enquiry_id');
		 // Prepare data for insertion
			$data = [
			'enquiry_id'		 => $enquiry_id,
			'survey_status'		 => 1,//assigned for survey
			'assigned_person_id' => $this->input->post('employee_survey'),
			'scheduled_date'     => $this->input->post('survey_date'),
			'start_time'         => $this->input->post('survey_start_datetime'),
			'end_time'           => trim($this->input->post('survey_end_datetime')),
			'scheduled_hours'    => trim($this->input->post('total_hours')), 
			'remarks'            => $this->input->post('remarks'),
			'active'             => 1,
			'created_on'         => date('Y-m-d H:i:s')
		];
	//'enquiry_status'	=>2;//sitesurvey	
    $inserted = $this->Sales_model->insert_site_survey($data);
		
		if ($inserted) {
			$enqrydata['enquiry_status']=2;//Sitesurvey
			$enqrydata['updated_on']= date('Y-m-d H:i:s');
			//Update enquiry status
			if(!empty($resurvey)){
				$enqrydata['reschedule_survey']=1;
			}
			$updated = $this->Sales_model->update_enquiry_data($enquiry_id,$enqrydata);
			
			if(!empty($resurvey)){
				$this->session->set_flashdata('success', 'Survey Rescheduled  successfully.');
				redirect('Sales/view_enquiry/'.$enquiry_id);
			}else{
				$this->session->set_flashdata('success', 'Enquiry saved successfully.');
				redirect('Sales/add_enquiry/2/'.$enquiry_id.'/'.$inserted);
			}
			
		} else {
			$this->session->set_flashdata('error', 'Failed to save enquiry.');
			redirect('Sales/add_enquiry');
		}
		// Redirect back to add enquiry form
		
       
	}
	function list_site_survey(){
		$user = $this->session->userdata('user_id');

		$user_data=$this->Company_model->get_user_by_id($user);
		if(!empty($user_data['employee_id'])){
			$data['title']="Enquiry for survey";
			$data['enquiry_list']= $this->Sales_model->get_enquiry_for_survey($user_data['employee_id']);
			$data['main_content'] = 'sales/technicianapp/list_survey_enquiry.php';
			$this->load->view('includes/template',$data);
		}else{
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
		}

	}
	public function save_survey_details()
	{
		
		$enquiry_id       = $this->input->post('enquiry_id');
		$actual_date      = $this->input->post('actual_date');
		$actual_start     = $this->input->post('actual_start');
		$actual_end       = $this->input->post('actual_end');
		$actual_hours     = $this->input->post('actual_hours');
		$survey_comments  = $this->input->post('survey_comments');
		$material_details = $this->input->post('material_details');

		$uploadPath = './public/survey_files/';
		if (!is_dir($uploadPath)) {
			mkdir($uploadPath, 0777, true);
		}

		$this->load->library('upload');

		// Check if survey already exists
		$existing = $this->Sales_model->get_by_enquiry($enquiry_id);
		$data = [
			'actual_date'      => $actual_date,
			'actual_start_time'=> $actual_start,
			'actual_end_time'  => $actual_end,
			'actual_hours'     => $actual_hours,
			'survey_comments'  => $survey_comments,
			'material_details' => $material_details,
			'active'		   => 1
		];

		if ($existing) { // Update existing survey
			$data['updated_on'] = date('Y-m-d H:i:s');
			$data['survey_status'] = 2;
			$data['updated_by'] = $this->session->userdata('user_id');
			$this->Sales_model->update_survey($existing->survey_id, $data);
			$survey_id = $existing->survey_id;

			// Update enquiry status
			$this->db->where('enquiry_id', $enquiry_id);
				$this->db->update('enquiry_master', [
					'enquiry_status'  => 2 ,
					'reschedule_survey'=> 0,
					'updated_on'      => date('Y-m-d H:i:s')
				]);

			$this->session->set_flashdata('success', 'Survey updated successfully.');
		} else { // Insert new survey
			$data['created_on'] = date('Y-m-d H:i:s');
			$data['created_by'] = $this->session->userdata('user_id');
			$survey_id = $this->Sales_model->insert_survey($data);
			$this->session->set_flashdata('success', 'Survey added successfully.');
		}

		// Handle multiple attachments (array: attachment[])
		if (!empty($_FILES['attachment']['name'][0])) {
			$files = $_FILES['attachment'];

			for ($i = 0; $i < count($files['name']); $i++) {
				$_FILES['file']['name']     = $files['name'][$i];
				$_FILES['file']['type']     = $files['type'][$i];
				$_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
				$_FILES['file']['error']    = $files['error'][$i];
				$_FILES['file']['size']     = $files['size'][$i];

				$config = [
					'upload_path'   => $uploadPath,
					'allowed_types' => 'jpg|jpeg|png|pdf',
					'max_size'      => 2048,
					'file_name'     => time().'_'.$i
				];

				$this->upload->initialize($config);

				if ($this->upload->do_upload('file')) {
					$uploadData = $this->upload->data();

					// Insert into survey_files table
					$this->db->insert('survey_files', [
						'survey_id' => $survey_id,
						'enquiry_id'=> $enquiry_id,
						'file_name' => $uploadData['file_name']
					]);
				}
			}
		}

		redirect('Sales/list_site_survey');
	}	

	public function view_enquiry(){
		$enquiry=$this->uri->segment(3);
		$data['title']="Enquiry Details";
		$enquiry_details=$this->Sales_model->get_enquiry_by_id($enquiry);
		$branch_id = $enquiry_details['branch_id'];
		$data['Resurvey']  =  $enquiry_details['reschedule_survey'];
		$this->load->model('Company_model');
		$this->load->model('Item_model');
		$data['customer_list'] = $this->Company_model->get_customers_by_branch($branch_id);
		$data['all_products']=$this->Item_model->get_all_item_list();
		$data['active_units']=$this->Item_model->get_all_units();
		$des_id=2;//DESIGNATION SITE ENGINEER
		$data['employee_list'] = $this->Company_model->get_all_employees_designation_id($des_id,$branch_id);	
		$data['enquiry_data']=$enquiry_details;
		if ($enquiry_details['enquiry_status'] == 2 || $enquiry_details['enquiry_status'] == 3 || $enquiry_details['enquiry_status'] == 4 ) {
			$survey_details = $this->Sales_model->get_survey_by_enquiry_id($enquiry);
			if(!empty($survey_details)){
					$data['survey_data']=$survey_details;
					$data['survey_files']=$this->Sales_model->get_survey_files_by_id($survey_details['survey_id']);
					$data['old_survey_data']=$this->Sales_model->get_survey_old_data($enquiry);	
					// print_r($data['old_survey_data']);exit();
					if($enquiry_details['enquiry_status'] == 4 ){
						$Enquiry_details=$this->estimation_master_data($enquiry);
						$data['master']=$Enquiry_details['master'];
						//print_r($Enquiry_details);exit();
						$i=0;
						foreach ($Enquiry_details['estimation'] as  $main ){
							$i++;
						}
						$data['mainIndex']=$i;
						$data['estimation']=$Enquiry_details['estimation'];	
						//print_r($data['estimation']);exit();

					}
			}else{
				$data['Resurvey']=1;
			}
			
		}
		$data['main_content'] = 'sales/enquiry/view_enquiry.php';
		//$data['main_content'] = 'sales/estimation/test_estimation.php';
		$this->load->view('includes/template',$data);
	}
	public function accept_site_survey(){
		$enquiry_id   = $this->uri->segment(3);
		$survey_id    = $this->input->post('survey_table_id'); // coming from form hidden field

		if (!empty($enquiry_id)) {
			// 1. Update enquiry status
			$this->db->where('enquiry_id', $enquiry_id);
			$this->db->update('enquiry_master', ['enquiry_status' => 3]);

			// 2. Handle dynamic attachments if uploaded
			if (!empty($_FILES['attachments']['name'][0])) {
				$filesCount = count($_FILES['attachments']['name']);

				for ($i = 0; $i < $filesCount; $i++) {
					if (!empty($_FILES['attachments']['name'][$i])) {
						$_FILES['file']['name']     = $_FILES['attachments']['name'][$i];
						$_FILES['file']['type']     = $_FILES['attachments']['type'][$i];
						$_FILES['file']['tmp_name'] = $_FILES['attachments']['tmp_name'][$i];
						$_FILES['file']['error']    = $_FILES['attachments']['error'][$i];
						$_FILES['file']['size']     = $_FILES['attachments']['size'][$i];

						$uploadPath = './public/survey_files/';
						if (!is_dir($uploadPath)) {
							mkdir($uploadPath, 0777, true);
						}

						$config['upload_path']   = $uploadPath;
						$config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx';
						$config['max_size']      = 20480; // 20 MB
						$config['encrypt_name']  = TRUE; // rename to avoid conflicts

						$this->load->library('upload', $config);
						$this->upload->initialize($config);

						if ($this->upload->do_upload('file')) {
							$fileData = $this->upload->data();

							// Save record in survey_files table
							$data = [
								'survey_id' => $survey_id,       // link to survey
								'file_name' => $fileData['file_name'],
								'uploaded_on' => date("Y-m-d H:i:s"),
							];

							$this->db->insert('survey_files', $data);
						}
					}
				}
			}

			// 3. Redirect based on result
			if ($this->db->affected_rows() > 0) {
				$this->session->set_flashdata('success', 'Site survey accepted successfully with attachments.');
				redirect('Sales/view_enquiry/' . $enquiry_id);
			} else {
				$this->session->set_flashdata('error', 'No record updated or files uploaded.');
				redirect('Sales/view_enquiry/' . $enquiry_id);
			}
		} else {
			$this->session->set_flashdata('error', 'Invalid enquiry ID.');
			redirect('Sales/enquiry_list');
		}
	}
	public function re_schedule_survey()
	{
		$enquiry_id = $this->uri->segment(3);

		// Deactivate the current active survey for this enquiry
		$this->db->where('enquiry_id', $enquiry_id);
		$this->db->where('active', 1); // only active survey
		$this->db->update('site_survey_master', [
			'active'          => 0,
			're_survey_status'=> 1,
			'updated_on'      => date('Y-m-d H:i:s')
		]);

		$this->db->where('enquiry_id', $enquiry_id);
		$this->db->update('enquiry_master', [
			'enquiry_status'  => 1,
			'reschedule_survey'=> 1,
			'updated_on'      => date('Y-m-d H:i:s')
		]);
		
		redirect('Sales/view_enquiry/'.$enquiry_id);
		// Insert a new row for the re-scheduled survey (example)
		/*$new_data = [
			'enquiry_id'      => $enquiry_id,
			'status'          => 1,   // make this new one active
			're_survey_status'=> 1,
			'created_on'      => date('Y-m-d H:i:s')
		];
		$this->db->insert('site_survey_master', $new_data);*/
	}
    public function estimation_master_data($enquiry_id){
		$rows=$this->Sales_model->get_estimation_details($enquiry_id);
		$data['estimation'] = [];
    	$data['master'] = null;

			foreach ($rows as $row) {
				if ($data['master'] === null) {
					// only once - master details
					$data['master'] = [
						'estimation_id'   		=> $row['estimation_id'],
						'enquiry_id'      		=> $row['enquiry_id'],
						'estimation_date' 		=> $row['estimation_date'],
						'sub_total'       		=> $row['sub_total'],
						'grand_total'     		=> $row['grand_total'],
						'margin_percentage'     => $row['margin_percentage'],
						'margin_amount'     	=> $row['margin_amount'],
						'freight_percentage'    => $row['freight_percentage'],
						'freight_amount'     	=> $row['freight_amount'],
						'bank_charge'    	 	=> $row['bank_charge'],
						'travel_expense'     	=> $row['travel_expense'],
						'inspection_cost'     	=> $row['inspection_cost'],
						'other_expense'         => $row['other_expense'],
						'approval'        		=> $row['approval']
					];
				}

				// group by main heading
				if (!isset($data['estimation'][$row['main_heading_id']])) {
					$data['estimation'][$row['main_heading_id']] = [
						'main_heading_id' => $row['main_heading_id'],
						'main_heading'    => $row['main_heading'],
						'main_details'    => $row['main_details'],
						'sub_headings'    => []
					];
				}

				// group by sub heading
				if (!empty($row['sub_heading_id'])) {
					if (!isset($data['estimation'][$row['main_heading_id']]['sub_headings'][$row['sub_heading_id']])) {
						$data['estimation'][$row['main_heading_id']]['sub_headings'][$row['sub_heading_id']] = [
							'sub_heading_id' => $row['sub_heading_id'],
							'sub_heading'    => $row['sub_heading'],
							'products'       => []
						];
					}

					// push product
					if (!empty($row['product_table_id'])) {
						$data['estimation'][$row['main_heading_id']]['sub_headings'][$row['sub_heading_id']]['products'][] = [
							'product_table_id' 		=> $row['product_table_id'],
							'item_name'        		=> $row['item_name'],
							'product_id'        	=> $row['product_id'],
							'product_description'   => $row['product_description'],
							'unit_name'        		=> $row['unit_name'],
							'unit_id'        		=> $row['unit_id'],
							'quantity'        	 	=> $row['quantity'],
							'unit_price'       		=> $row['unit_price'],
							'amount'           		=> $row['amount'],
						];
					}
				}
			}
		return $data;
	}

	public function save_estimation_dummy(){
			 $products = $this->input->post('products');
		
    		// Only proceed if products exist and contain at least 1 row
   			 if (!empty($products) && is_array($products) && count($products, COUNT_RECURSIVE) > 1) {
					$this->db->truncate('estimation_product_details_dummy');
					$this->db->truncate('estimation_sub_heading_dummy');
					$this->db->truncate('estimation_main_heading_dummy');

					$main_headings 	   = $this->input->post('main_heading'); // main heading texts
					$main_details  	   = $this->input->post('main_details'); // main details
					$sub_headings  	   = $this->input->post('sub_heading');  // nested array of subheadings
					$products          = $this->input->post('products');     // nested array of products
					// Loop through main headings
					foreach ($main_headings as $mainIndex => $mainText) {
						$mainData = [
							'estimation_master_id' => 1,
							'main_heading'         => $mainText,
							'main_details'         => $main_details[$mainIndex] ?? null
						];
						$this->db->insert('estimation_main_heading_dummy', $mainData);
						$main_id = $this->db->insert_id(); // get DB ID of inserted main heading

						// Check if subheadings exist for this main heading
						if (!empty($sub_headings[$mainIndex])) {
							foreach ($sub_headings[$mainIndex] as $subIndex => $subText) {
								$subData = [
									'main_heading_id' => $main_id,
									'sub_heading'     => $subText
								];
								$this->db->insert('estimation_sub_heading_dummy', $subData);
								$sub_id = $this->db->insert_id(); // get DB ID of inserted subheading
								
								// Check if products exist for this subheading
								if (!empty($products[$mainIndex][$subIndex])) {
									foreach ($products[$mainIndex][$subIndex] as $rowData) {
										// Only insert if product_id exists
										if (!empty($rowData['product_id'])) {
											$prodData = [
												'sub_heading_id'       => $sub_id,
												'product_id'           => $rowData['product_id'],
												'product_description'  => $rowData['product_description'],
												'unit_id'              => $rowData['unit_id'],
												'quantity'             => $rowData['quantity'],
												'unit_price'           => $rowData['unit_price'],
												'amount'               => $rowData['amount'],
											];
											$this->db->insert('estimation_product_details_dummy', $prodData);
										}
									}
								}
							}
						}
					}

					echo json_encode(['status' => 'success']);
			}else{
				echo json_encode(['status' => 'error']);
			}
		
	}
public function print_quotation($enquiry_id){
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true); // allows base_url() http paths
    $options->set('chroot', realpath('C:/xampp/htdocs/aladel_erp/public/')); 
    $dompdf = new Dompdf($options);

    $data['headerPath'] = base_url('public/header/header_aladel.jpg'); // use full URL
	$data['footerPath'] = 'C:/xampp/htdocs/aladel_erp/public/footer/footer.jpg';
	$data['image']		= base_url('public/items/item1.jpg'); // use full URL
	$data['image']		= base_url('public/items/item1.jpg'); // use full URL

    $Enquiry_details = $this->estimation_master_data($enquiry_id);
    $data['master'] = $Enquiry_details['master'];
    $data['estimation'] = $Enquiry_details['estimation'];

    $html = $this->load->view('sales/quotation/print_quotation.php', $data, true);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("estimation_$enquiry_id.pdf", array("Attachment" => 0));
}

	/*public function save_estimation() {
		
			$this->db->trans_start();

			// 1. Save estimation_master
			$masterData = [
				'enquiry_id'         => $this->input->post('enquiry_id'),
				'estimation_date'    => date('Y-m-d'),
				'sub_total'          => $this->input->post('sub_total'),
				'margin_percentage'  => $this->input->post('margin_percent'),
				'margin_amount'		 => $this->input->post('margin_amount'),
				'freight_percentage' => $this->input->post('freight_percent'),
				'freight_amount'     => $this->input->post('freight_amount'),
				'bank_charge'        => $this->input->post('bank_charge'),
				'travel_expense'     => $this->input->post('travel_expense'),
				'inspection_cost'    => $this->input->post('inspection_cost'),
				'grand_total'        => $this->input->post('total_cost'),
				'created_by'         => $this->session->userdata('user_id')
			];
			//print_r($masterData);exit();
			$this->db->insert('estimation_master', $masterData);
			$estimation_master_id = $this->db->insert_id();

			// 2. Save Main Headings, Subheadings, and Product Details
			$mainHeadings = $this->input->post('main_heading');   // array
				$mainDetails  = $this->input->post('main_details');   // array
				$subHeadings  = $this->input->post('sub_heading');    // 2D array
				$products     = $this->input->post('products');       // 3D array

				foreach ($mainHeadings as $mIndex => $mHeading) {
					// insert main heading
					$this->db->insert('estimation_main_headings', [
						'estimation_id' => $estimation_id,
						'heading'       => $mHeading,
						'details'       => $mainDetails[$mIndex] ?? null
					]);
					$main_heading_id = $this->db->insert_id();

					// loop sub headings under this main
					if (!empty($subHeadings[$mIndex])) {
						foreach ($subHeadings[$mIndex] as $sIndex => $sHeading) {
							$this->db->insert('estimation_sub_headings', [
								'main_heading_id' => $main_heading_id,
								'sub_heading'     => $sHeading
							]);
							$sub_heading_id = $this->db->insert_id();

							// loop products under this sub
							if (!empty($products[$mIndex][$sIndex])) {
								foreach ($products[$mIndex][$sIndex] as $pIndex => $product) {
									$this->db->insert('estimation_product_details', [
										'sub_heading_id' => $sub_heading_id,
										'product_id'     => $product['product_id'] ?? null,
										'unit_id'        => $product['unit_id'] ?? null,
										'quantity'       => $product['quantity'] ?? 0,
										'unit_price'     => $product['unit_price'] ?? 0,
										'amount'         => $product['amount'] ?? 0
									]);
								}
							}
						}
					}
				}

			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE) {
				return ['status' => 'error', 'message' => 'DB Save Failed'];
			}
			return ['status' => 'success', 'message' => 'Estimation saved'];
			exit();
		}*/
	function save_estimation(){
			$enquiry_id = $this->input->post('enquiry_id');
			$sub_total = $this->input->post('sub_total') ?? 0;
			$margin_percent = $this->input->post('margin_percent') ?? 0;
			$margin_amount = $this->input->post('margin_amount') ?? 0;
			$freight_percent = $this->input->post('freight_percent') ?? 0;
			$freight_amount = $this->input->post('freight_amount') ?? 0;
			$bank_charge = $this->input->post('bank_charge') ?? 0;
			$travel_expense = $this->input->post('travel_expense') ?? 0;
			$other_expense = $this->input->post('other_expense') ?? 0;
			$inspection_cost = $this->input->post('inspection_cost') ?? 0;
			$total_cost = $this->input->post('total_cost') ?? 0;

			// 2️⃣ Generate a temporary estimation code
			$estimation_code = "EST-" . date('YmdHis'); // you can change format later

			// 3️⃣ Prepare master data array
			$masterData = [
				'estimation_code'     => $estimation_code,
				'enquiry_id'          => $enquiry_id,
				'estimation_revision' => 1, // initial revision
				'estimation_date'     => date('Y-m-d'),
				'sub_total'           => $sub_total,
				'margin_percentage'   => $margin_percent,
				'margin_amount'       => $margin_amount,
				'freight_percentage'  => $freight_percent,
				'freight_amount'      => $freight_amount,
				'bank_charge'         => $bank_charge,
				'travel_expense'      => $travel_expense,
				'active'      		  => 1,
				//'other_expense'       => $other_expense,
				'inspection_cost'     => $inspection_cost,
				'grand_total'         => $total_cost,
				'created_on'          => date('Y-m-d H:i:s')
			];
   
   		$this->db->insert('estimation_master', $masterData);
		$estimation_id = $this->db->insert_id();
		if($estimation_id){
			$status=$this->save_estimation_details($estimation_id);
			if($status){
				$this->db->where('enquiry_id', $enquiry_id);
    			$this->db->update('enquiry_master', ['enquiry_status' => 4]);//Estimation Completed
				redirect("Sales/view_enquiry/$enquiry_id");
			}
		}else{
			redirect("Sales/view_enquiry/$enquiry_id");
		}

		
	}
	private function save_estimation_details($estimation_master_id)
		{
			$main_headings = $this->input->post('main_heading'); // main heading texts
			$main_details  = $this->input->post('main_details'); // main details
			$sub_headings  = $this->input->post('sub_heading');  // nested array of subheadings
			$products      = $this->input->post('products');     // nested array of products
			// Loop through main headings
			foreach ($main_headings as $mainIndex => $mainText) {
				$mainData = [
					'estimation_master_id' => $estimation_master_id,
					'main_heading'         => $mainText,
					'main_details'         => $main_details[$mainIndex] ?? null
				];
				$this->db->insert('estimation_main_heading', $mainData);
				$main_id = $this->db->insert_id(); // get DB ID of inserted main heading
				// Check if subheadings exist for this main heading
				if (!empty($sub_headings[$mainIndex])) {
					foreach ($sub_headings[$mainIndex] as $subIndex => $subText) {
						$subData = [
							'main_heading_id' => $main_id,
							'sub_heading'     => $subText
						];
						$this->db->insert('estimation_sub_heading', $subData);
						$sub_id = $this->db->insert_id(); // get DB ID of inserted subheading
						
						// Check if products exist for this subheading
						
						if (!empty($products[$mainIndex][$subIndex])) {
							foreach ($products[$mainIndex][$subIndex] as $rowData) {
								// Only insert if product_id exists
								if (!empty($rowData)) {
									$prodData = [
										'sub_heading_id'      => $sub_id,
										'product_id'          => !empty($rowData['product_id']) ? $rowData['product_id'] : null,
										//'product_name'        => !empty($rowData['name']) ? $rowData['name'] : null,
										'unit_id'             => !empty($rowData['unit_id']) ? $rowData['unit_id'] : (!empty($rowData['unit']) ? $rowData['unit'] : null),
										'product_description' => $rowData['product_description'],
										'quantity'            => $rowData['quantity'],
										'unit_price'          => $rowData['unit_price'],
										'amount'              => $rowData['amount'],
									];

									$this->db->insert('estimation_product_details', $prodData);
								}
							}
						}
					}
				}
			}
			return true; // all saved successfully
		}
		public function update_estimation() {
				$estimation_id = $this->input->post('estimation_id');

				// Start transaction
				$this->db->trans_begin();

				$deleted = $this->delete_estimation($estimation_id);

				if ($deleted) {
					$return = $this->update_estimation_main_data($estimation_id);

					if ($return) {
						$enquiry_id = $this->get_enquiry_id_by_estimation($estimation_id);

						if ($this->db->trans_status() === FALSE) {
							$this->db->trans_rollback();
							echo "Transaction failed.";
						} else {
							$this->db->trans_commit();
							redirect("Sales/view_enquiry/$enquiry_id");
						}
					} else {
						$this->db->trans_rollback();
						echo "Error updating estimation main data.";
					}
				} else {
					$this->db->trans_rollback();
					echo "Error deleting estimation data.";
				}
			}

		public function delete_estimation($estimation_id)
			{
				$this->load->database();
				$this->db->trans_start(); // start transaction

				// 1. Get main_heading_ids for this estimation_master_id
				$this->db->select('main_heading_id');
				$this->db->from('estimation_main_heading');
				$this->db->where('estimation_master_id', $estimation_id);
				$main_ids = array_column($this->db->get()->result_array(), 'main_heading_id');

				if (!empty($main_ids)) {
					// 2. Get sub_heading_ids under these main headings
					$this->db->select('sub_heading_id');
					$this->db->from('estimation_sub_heading');
					$this->db->where_in('main_heading_id', $main_ids);
					$sub_ids = array_column($this->db->get()->result_array(), 'sub_heading_id');

					if (!empty($sub_ids)) {
						// 3. Delete product details linked to sub_headings
						$this->db->where_in('sub_heading_id', $sub_ids);
						$this->db->delete('estimation_product_details');

						// 4. Delete subheadings
						$this->db->where_in('sub_heading_id', $sub_ids);
						$this->db->delete('estimation_sub_heading');
					}

					// 5. Delete main headings
					$this->db->where_in('main_heading_id', $main_ids);
					$this->db->delete('estimation_main_heading');
				}

				$this->db->trans_complete(); // commit transaction

				return $this->db->trans_status(); // true if success, false if rollback
			}

	public function get_enquiry_id_by_estimation($estimation_id)
		{
			return $this->db
				->select('enquiry_id')
				->where('estimation_id', $estimation_id)
				->get('estimation_master')
				->row('enquiry_id');
		}
	public function update_estimation_main_data($estimation_id) {
			$sub_total       = $this->input->post('sub_total') ?? 0;
			$margin_percent  = $this->input->post('margin_percentage') ?? 0;
			$margin_amount   = $this->input->post('margin_amount') ?? 0;
			$freight_percent = $this->input->post('freight_percentage') ?? 0;
			$freight_amount  = $this->input->post('freight_amount') ?? 0;
			$bank_charge     = $this->input->post('bank_charge') ?? 0;
			$travel_expense  = $this->input->post('travel_expense') ?? 0;
			$other_expense   = $this->input->post('other_expense') ?? 0;
			$inspection_cost = $this->input->post('inspection_cost') ?? 0;
			$total_cost      = $this->input->post('total_cost') ?? 0;

			$masterData = [
				'estimation_revision' => 1,
				'estimation_date'     => date('Y-m-d'),
				'sub_total'           => $sub_total,
				'margin_percentage'   => $margin_percent,
				'margin_amount'       => $margin_amount,
				'freight_percentage'  => $freight_percent,
				'freight_amount'      => $freight_amount,
				'bank_charge'         => $bank_charge,
				'travel_expense'      => $travel_expense,
				'active'              => 1,
				'other_expense'       => $other_expense,
				'inspection_cost'     => $inspection_cost,
				'grand_total'         => $total_cost,
				'updated_on'          => date('Y-m-d H:i:s')
			];
			$this->db->where('estimation_id', $estimation_id);
			$this->db->update('estimation_master', $masterData);

			if ($this->db->affected_rows() >= 0) {
				return $this->save_estimation_details($estimation_id);
			} else {
				return false;
			}
		}


	}


		


