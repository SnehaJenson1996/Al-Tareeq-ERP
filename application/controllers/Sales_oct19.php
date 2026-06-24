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
		$this->load->model('Crm_model');
		$this->load->model('Item_model');
		$this->load->model('Accounts_model');
		$this->load->model('Sales_order_model');
		
		$this->load->model('Company_model');
		$this->load->helper('menu_helper');
	}

	
	public function view_enquiry(){
		$enquiry=$this->uri->segment(3);
		$this->load->model('Company_model');
		$this->load->model('Item_model');
		$enquiry_details=$this->Sales_model->get_enquiry_by_id($enquiry);
		
		$branch_id				= $enquiry_details['branch_id'];

		$data['title']			=  "Enquiry Details";
		$data['Resurvey']  		=  $enquiry_details['reschedule_survey'];		
		$data['customer_list']  =  $this->Company_model->get_customers_by_branch($branch_id);
		$data['all_products']	=  $this->Item_model->get_all_item_list();
		$data['active_units']	=  $this->Item_model->get_all_units();
		$des_id					=  2;//DESIGNATION SITE ENGINEER
		$data['employee_list']  =  $this->Company_model->get_all_employees_designation_id($des_id,$branch_id);	
		$data['enquiry_data']   =  $enquiry_details;
		$enquiry_revision       =  $enquiry_details['enquiry_revision'];
		$customer_id            =  $enquiry_details['enquiry_customer'];
		if($enquiry_revision==1){
			$data['old_survey_data'] = $this->Sales_model->get_survey_old_data($enquiry);	
		}
		// echo $enquiry_details['enquiry_status'];exit();
		if ($enquiry_details['enquiry_status'] >= 2 ) {
			$survey_details = $this->Sales_model->get_survey_by_enquiry_id($enquiry);
				if(!empty($survey_details)){
					$data['enquiry_id']		 = $enquiry_details['enquiry_id'];
					$data['survey_data']	 = $survey_details;
					$data['survey_files']	 = $this->Sales_model->get_survey_files_by_id($survey_details['survey_id']);
					$data['old_survey_data'] = $this->Sales_model->get_survey_old_data($enquiry);	
					//Resurvey from Quotation
					if($enquiry_revision==1){
						$data['estimation_revisions'] = $this->Sales_model->get_estimation_ids($enquiry);	
					}
					if($enquiry_details['enquiry_status'] >= 4 ){
						// echo "here";exit();
						$Enquiry_details     =  $this->estimation_master_data($enquiry);
						$data['master']      =  $Enquiry_details['master'];
						// print_r($data['master']);exit();
						$i=0;
						foreach ($Enquiry_details['estimation'] as  $main ){
							$i++;
						}
						$data['mainIndex']   = $i;
						$data['estimation']  = $Enquiry_details['estimation'];	
						if($enquiry_details['enquiry_status'] == 5){//Register quotation
							$customer_id    			 = $enquiry_details['enquiry_customer'];
							$data['Customer_contacts']   = $this->Company_model->get_customer_contact_by_cust_id($customer_id);
							$data['quotation_code']		 = "QTN-" .'0'.$enquiry_details['branch_id']."-". date('Ymd');
						}
						if($enquiry_details['enquiry_status'] == 6 ){
							$quotation_details			= $this->get_quotation_master_data($enquiry);
							$data['qtn_master']			= $quotation_details['qtn_master'];
							$data['qtn_details']        = $quotation_details['quotation'];
							$data['qtn_revisions']		= $this->Sales_model->get_all_qtn_revisions($enquiry);

						}
						if($enquiry_details['enquiry_status'] == 7){//Creating new  sales order							
									
							$quotation_details	 	= $this->get_quotation_master_data($enquiry,1);
							$data['so_code']    	= $this->Sales_model->generate_so_code();
							$data['qtn_master']  	= $quotation_details['qtn_master'];
							$qtn_id				 	= $data['qtn_master']['quotation_id'];
							$products_with_avail	= $this->Sales_order_model->get_available_quantities($enquiry,$qtn_id);
							// echo $this->db->last_query();exit();
							$data['qtn_products']	= $products_with_avail;
							//print_r($data['qtn_products']);exit();
							$data['qtn_revisions']	= $this->Sales_model->get_all_qtn_revisions($enquiry);
							
						}
						if($enquiry_details['enquiry_status'] == 8){
							$quotation_details=$this->get_quotation_master_data($enquiry,1);
							$data['so_code']     = $this->Sales_model->generate_so_code();
							$data['qtn_master']  = $quotation_details['qtn_master'];
							$data['qtn_details'] = $quotation_details['quotation'];
							$data['qtn_products']= $this->Sales_model->get_all_products($data['qtn_master']['quotation_id']);
							$data['sales_order_list'] = $this->Sales_order_model->get_sales_order_list_data($data['enquiry_id'],$data['qtn_master']['quotation_id']);
							$data['qtn_revisions'] = $this->Sales_model->get_all_qtn_revisions($enquiry);
						}
						if($enquiry_details['enquiry_status'] == 9){
						
							$data['qtn_revisions'] = $this->Sales_model->get_all_qtn_revisions($enquiry);
							$quotation_details=$this->get_quotation_master_data($enquiry,1);
							$data['so_code']     			= $this->Sales_model->generate_so_code();
							$data['qtn_master']  			= $quotation_details['qtn_master'];
							$data['qtn_details'] 			= $quotation_details['quotation'];
							$data['qtn_products']			= $this->Sales_model->get_all_products($data['qtn_master']['quotation_id']);
							$data['sundry_accounts1']   	= $this->Accounts_model->get_gen_ledger_detors_records();
							$data['sundry_accounts2']   	= $this->Accounts_model->get_general_ledger_by_group('Sales Accounts');
							$data['sundry_accounts3']   	= $this->Accounts_model->get_all_general_ledger_accounts();
							$data['enquiry_customer_id']    = $this->Accounts_model->get_cust_account_Id($customer_id);
							$last_code = $this->Sales_order_model->get_last_delivery_code(); 
							if ($last_code) {
									// Extract numeric part
									$number = (int) substr($last_code, 2); // Assuming prefix 'DN'
									$number++; // Increment by 1
								} else {
									$number = 1; // First delivery
								}

								// Format with leading zeros, e.g., DN00013
							$data['delivery_code'] = 'DN' . str_pad($number, 5, '0', STR_PAD_LEFT);
							$data['sales_order_list'] = $this->Sales_order_model->get_sales_order_list_data($data['enquiry_id'],$data['qtn_master']['quotation_id']);
							$data['delivery_challan_list'] = $this->Sales_order_model->get_delivery_challan_list_data($data['enquiry_id'],$data['qtn_master']['quotation_id']);
							
						}

					}
			}else{
				$data['Resurvey']=1;
			}
			
		}
		$data['main_content'] = 'sales/view_quotation.php';
		//$data['main_content'] = 'sales/estimation/test_estimation.php';
		$this->load->view('includes/template',$data);
	}


	public function create_quotation(){
		$estimation_id=$this->input->post('estimation_id');
		$enquiry_id=$this->get_enquiry_id_by_estimation($estimation_id);
		$this->db->where('enquiry_id', $enquiry_id);
				$this->db->update('enquiry_master', [
					'enquiry_status'  => 5 ,
					'updated_on'      => date('Y-m-d H:i:s')
				]);
		redirect("Sales/view_enquiry/$enquiry_id");

	}
	public function add_quotation() {
		$this->db->trans_begin(); // Start transaction

		try {
			$enquiry_id = $this->input->post('enquiry_id');

			$masterData = [
				'enquiry_id'           => $enquiry_id,
				'estimation_id'        => $this->input->post('estimation_id'),
				'quotation_code'       => $this->input->post('quotation_code'),
				'quotation_date'       => $this->input->post('quotation_date'),

				'sub_total'            => $this->input->post('qtn_sub_total'),
				'estimation_amount'    => $this->input->post('qtn_estimation_cost'),
				'total_before_discount'=> $this->input->post('qtn_sub_total'),
				'discount_percentage'  => $this->input->post('qtn_add_discount_percentage'),
				'discount_amount'      => $this->input->post('qtn_add_discount_amount'),
				'total_before_vat'     => $this->input->post('total_before_vat'),

				'vat_required'         => $this->input->post('qtn_apply_vat') ? 1 : 0,
				'vat_percentage'       => $this->input->post('qtn_vat_percentage'),
				'vat_amount'           => $this->input->post('qtn_vat_amount'),

				'grand_total'          => $this->input->post('qtn_grand_total'),

				'payment_term'         => $this->input->post('payment_term'),
				'validity'             => $this->input->post('validity'),
				'delivery_term'        => $this->input->post('delivery_term'),
				'terms_condition'      => $this->input->post('terms_condition'),

				'active'			   =>1,
				'created_on'           => date('Y-m-d H:i:s'),
				'created_by'           => $this->session->userdata('user_id'),
			];

			$qtn_id = $this->Sales_model->insert_quotation($masterData);

			// Save Main Headings
			if ($this->input->post('main_heading')) {
				foreach ($this->input->post('main_heading') as $i => $main_heading) {
					$mainData = [
									'qtn_id'       => $qtn_id,
									'main_heading' => $main_heading,
									'description'  => $this->input->post('main_details')[$i] ?? ''
								];
					$main_heading_id = $this->Sales_model->insert_main_heading($mainData);

					// Save Subheadings
					if (isset($_POST['sub_heading'][$i])) {
						foreach ($_POST['sub_heading'][$i] as $j => $sub_heading) {
							$subData = [
								'main_heading_id'   => $main_heading_id,
								'qtn_id'       		=> $qtn_id,
								'subheading'        => $sub_heading
							];
							$sub_id = $this->Sales_model->insert_subheading($subData);
							
							// Save Products
							if (isset($_POST['products'][$i][$j])) {
								foreach ($_POST['products'][$i][$j] as $prod) {
									$prodData = [
										'qtn_id'     		=> $qtn_id,
										'sub_heading_id' 	=> $sub_id,
										'prd_id'     		=> $prod['product_id'],
										'unit_id'    		=> $prod['unit'],
										'unit_price' 		=> $prod['unit_price'],
										'qty'       		=> $prod['quantity'],
										'amount'     		=> $prod['amount'],
										'dicount_amount' 	=> $prod['discount_amount'],
										'taxable_amount' 	=> $prod['taxable_amount']
									];
									$this->Sales_model->insert_product($prodData);
								}
							}
						}
					}
				}
			}

			// Update enquiry status
			$this->db->where('enquiry_id', $enquiry_id);
			$this->db->update('enquiry_master', ['enquiry_status' => 6]);

			// ✅ Commit if everything is fine
			if ($this->db->trans_status() === FALSE) {
				throw new Exception('Transaction failed');
			}

			$this->db->trans_commit();
			$this->session->set_flashdata('success', 'Quotation created successfully');

		} catch (Exception $e) {
			// ❌ Rollback on error
			$this->db->trans_rollback();
			log_message('error', 'Quotation Insert Failed: ' . $e->getMessage());
			$this->session->set_flashdata('error', 'Something went wrong while creating quotation.');
		}

		redirect('Sales/view_enquiry/'.$enquiry_id);
	}

	public function get_quotation_master_data($enquiry_id,$status=""){
		if(!empty($status)){
			$rows=$this->Sales_model->get_quotation_full_details($enquiry_id,$status);
		}else{
			$rows=$this->Sales_model->get_quotation_full_details($enquiry_id);
		}
		
		//echo $this->db->last_query();exit();
		$data['quotation'] = [];
    	$data['qtn_master'] = null;

			foreach ($rows as $row) {
				if ($data['qtn_master'] === null) {
					// only once - master details
					$data['qtn_master'] = [
						'quotation_id'   		=> $row['qtn_id'],
						'quotation_code'   		=> $row['quotation_code'],
						'quotation_date'      	=> $row['quotation_date'],

						'enquiry_id' 			=> $row['enquiry_id'],
						'enquiry_code'        	=> $row['enquiry_code'],
						'project_name'			=> $row['project_name'],
						'project_name'			=> $row['project_name'],

						'estimation_id'       	=> $row['est_id'],
						'estimation_code'       => $row['estimation_code'],
						'estimation_amount'     => $row['estimation_amount'],


						'sub_total'     		=> $row['sub_total'],						
						'discount_percentage'   => $row['discount_percentage'],
						'discount_amount'   => $row['discount_amount'],
						'vat_required'    		=> $row['vat_required'],
						'total_before_vat'     	=> $row['total_before_vat'],
						'vat_percentage'    	=> $row['vat_percentage'],
						'vat_amount'     		=> $row['vat_amount'],
						'grand_total'     		=> $row['grand_total'],

						'payment_term'         	=> $row['payment_term'],
						'terms_condition'       => $row['terms_condition'],
						'validity'        		=> $row['validity'],
						'delivery_term'        	=> $row['delivery_term'],
						

						'created_by'        	=> $row['created_by'],

						'quotation_revision'    => $row['quotation_revision'],
						'aproval'				=> $row['aproval']						
					];
				}

				// group by main heading
				if (!isset($data['quotation'][$row['main_heading_id']])) {
					$data['quotation'][$row['main_heading_id']] = [
						'main_heading_id' => $row['main_heading_id'],
						'main_heading'    => $row['main_heading'],
						'main_details'    => $row['description'],
						'sub_headings'    => []
					];
				}

				// group by sub heading
				if (!empty($row['subheading_id'])) {
					if (!isset($data['quotation'][$row['main_heading_id']]['sub_headings'][$row['subheading_id']])) {
						$data['quotation'][$row['main_heading_id']]['sub_headings'][$row['subheading_id']] = [
							'sub_heading_id' => $row['subheading_id'],
							'sub_heading'    => $row['subheading'],
							'products'       => []
						];
					}

					// push product
					if (!empty($row['quotation_product_id'])) {
						$data['quotation'][$row['main_heading_id']]['sub_headings'][$row['subheading_id']]['products'][] = [
							'quotation_product_id' 		=> $row['quotation_product_id'],
							'item_name'        		=> $row['item_name'],
							'product_id'        	=> $row['prd_id'],
							'product_description'   => $row['prd_description'],
							'unit_name'        		=> $row['unit_name'],
							'unit_id'        		=> $row['unit_id'],
							'quantity'        	 	=> $row['qty'],
							'unit_price'       		=> $row['unit_price'],
							'amount'           		=> $row['amount'],
							'dicount_amount'           		=> $row['dicount_amount'],
							'taxable_amount'           		=> $row['taxable_amount']
						];
					}
				}
			}
		return $data;
	}

	public function update_quotation()	{
		$this->db->trans_begin(); 

		try {
			$qtn_id     = $this->input->post('quotation_id');
			$enquiry_id = $this->input->post('enquiry_id');
			$is_new_revision = $this->input->post('qtn_new_revision'); // checkbox

			// Prepare master data
			$masterData = [
				'enquiry_id'           => $enquiry_id,
				'estimation_id'        => $this->input->post('estimation_id'),
				'quotation_code'       => $this->input->post('quotation_code'),
				'quotation_date'       => $this->input->post('quotation_date'),

				'sub_total'            => $this->input->post('qtn_sub_total'),
				'estimation_amount'    => $this->input->post('qtn_estimation_cost'),
				'total_before_discount'=> $this->input->post('qtn_sub_total'),
				'discount_percentage'  => $this->input->post('qtn_add_discount_percentage'),
				'discount_amount'      => $this->input->post('qtn_add_discount_amount'),
				'total_before_vat'     => $this->input->post('qtn_total_before_vat'),

				'vat_required'         => $this->input->post('apply_vat') ? 1 : 0,
				//'vat_percentage'       => $this->input->post('qtn_vat_percentage'),
				'vat_amount'           => $this->input->post('qtn_vat_amount'),

				'grand_total'          => $this->input->post('qtn_grand_total'),

				'payment_term'         => $this->input->post('payment_term'),
				'validity'             => $this->input->post('validity'),
				'delivery_term'        => $this->input->post('delivery_term'),
				'terms_condition'      => $this->input->post('terms_condition')				
			];

			if ($is_new_revision) {
					$max_revision = $this->Sales_model->get_max_revision($enquiry_id);
					$new_revision = $max_revision + 1;

					//Update old quotation inactive and new revision
					$updateData = [
									'active'     => 0,
									'quotation_revision'   => $new_revision,   
									'updated_on' => date('Y-m-d H:i:s'),
									'updated_by' => $this->session->userdata('user_id')
								];
				$this->Sales_model->update_quotation($qtn_id, $updateData);

				$masterData['active']     = 1;
				$masterData['created_on'] = date('Y-m-d H:i:s');
				$masterData['created_by'] = $this->session->userdata('user_id');
				$masterData['quotation_revision']   = 0;   // always 0 for newly created revision

				$new_qtn_id = $this->Sales_model->insert_quotation($masterData);

				$this->_save_qtn_details($new_qtn_id);

				$this->session->set_flashdata('success', 'New quotation revision created successfully.');
				$redirect_qtn_id = $new_qtn_id;
			} else {
				$masterData['active']     = 1;
				$masterData['updated_on'] = date('Y-m-d H:i:s');
				$masterData['updated_by'] = $this->session->userdata('user_id');
				
				$this->Sales_model->update_quotation($qtn_id, $masterData);
				// Delete old details
				$this->Sales_model->delete_qtn_main_headings($qtn_id);
				$this->Sales_model->delete_qtn_sub_headings($qtn_id);
				$this->Sales_model->delete_qtn_products($qtn_id);

				// Re-insert updated details
				$this->_save_qtn_details($qtn_id);

				$this->session->set_flashdata('success', 'Quotation updated successfully.');
				$redirect_qtn_id = $qtn_id;
			}

			// ✅ Commit if fine
			if ($this->db->trans_status() === FALSE) {
				throw new Exception('Transaction failed');
			}
			$this->db->trans_commit();

		} catch (Exception $e) {
			// ❌ Rollback
			$this->db->trans_rollback();
			log_message('error', 'Quotation Update Failed: ' . $e->getMessage());
			$this->session->set_flashdata('error', 'Something went wrong while updating quotation.');
		}

		redirect('Sales/view_enquiry/'.$enquiry_id);
	}

	private function _save_qtn_details($qtn_id)	{
		$this->db->trans_start(); // 🔹 Start transaction

		if ($this->input->post('main_heading')) {
			foreach ($this->input->post('main_heading') as $i => $main_heading) {
				$mainData = [
					'qtn_id'       => $qtn_id,
					'main_heading' => $main_heading,
					'description'  => $this->input->post('main_details')[$i] ?? ''
				];
				$main_heading_id = $this->Sales_model->insert_main_heading($mainData);

				if (isset($_POST['sub_heading'][$i])) {
					foreach ($_POST['sub_heading'][$i] as $j => $sub_heading) {
						$subData = [
							'qtn_id'       	  => $qtn_id,
							'main_heading_id' => $main_heading_id,
							'subheading'      => $sub_heading
						];
						$sub_id = $this->Sales_model->insert_subheading($subData);
						if (isset($_POST['products'][$i][$j])) {
							foreach ($_POST['products'][$i][$j] as $prod) {
								$prodData = [
									'qtn_id'         => $qtn_id,
									'sub_heading_id' => $sub_id,
									'prd_id'         => $prod['product_id'],
									'unit_id'        => $prod['unit'],
									'unit_price'     => $prod['unit_price'],
									'qty'            => $prod['quantity'],
									'amount'         => $prod['amount'],
									'dicount_amount'           		=> $prod['discount_amount'],
									'taxable_amount'           		=> $prod['taxable_amount']
								];
								$this->Sales_model->insert_product($prodData);
							}
						}
					}
				}
			}
		}

		$this->db->trans_complete(); // 🔹 Commit or Rollback automatically

		if ($this->db->trans_status() === FALSE) {
			// Rollback happened
			return false;
		} else {
			return true;
		}
	}


    public function approve_quotation()	{
		$quote_id   = $this->input->post('quotation_id');
		$approved_by = $this->input->post('approved_by');

		$updateData = [
			'aproval'    => 1,
			'approved_by' => $approved_by,
			'approved_on' => date('Y-m-d H:i:s')
		];

		$this->db->where('qtn_id', $quote_id);
		$this->db->update('quotation_master', $updateData);

		echo json_encode(['status' => 'success']);
	}

	
	public function print_quotation($qtn_id,$enquiry_id){

	$enquiry_data           	=	$this->Sales_model->get_enquiry_by_id($enquiry_id);	
	$data['enquiry_code']		=	$enquiry_data['enquiry_code'];
	$data['enquiry_category']	=	$enquiry_data['enquiry_category'];
	$data['enquiry_date']		=	$enquiry_data['enquiry_date'];
	$data['project_name']		=	$enquiry_data['project_name'];
	$data['project_location']	=	$enquiry_data['project_location'];

	$data['branch_id']			=	$enquiry_data['branch_id'];
	$data['branch_name']		=	$enquiry_data['branch_name'];
	$data['branch_header']		=	$enquiry_data['branch_header'];
	$data['branch_footer']		=	$enquiry_data['branch_footer'];
	$data['branch_contact']		=	$enquiry_data['branch_contact'];
	$data['branch_email']		=	$enquiry_data['branch_email'];
	$data['branch_location']	=	$enquiry_data['branch_location'];
	$data['branch_address']		=	$enquiry_data['branch_address'];
	$data['branch_website']		=	$enquiry_data['branch_web'];

	$data['customer_name']		=	$enquiry_data['customer_name'];
	$data['customer_address']	=	$enquiry_data['customer_address'];
	$data['customer_email']		=	$enquiry_data['customer_email'];
	$data['contact_number']		=	$enquiry_data['contact_number'];
	$data['emirate']			=	$enquiry_data['emirate'];

	$data['sales_person']		=	$enquiry_data['user_name'];
	

    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true); // allows base_url() http paths
    $options->set('chroot', realpath('C:/xampp/htdocs/aladel_erp/public/')); 
    $dompdf = new Dompdf($options);



    //$data['headerPath'] = base_url('public/header/header_aladel.jpg'); // use full URL
	//$data['footerPath'] = 'C:/xampp/htdocs/aladel_erp/public/footer/footer.jpg';
	
	$data['headerPath'] = base_url(ltrim($data['branch_header'], '/'));
	$data['footerPath'] =  base_url(ltrim($data['branch_footer'], '/'));
	

    $quotation_details  = $this->Sales_model->get_quotation_details_by_id($qtn_id);
	//print_r($quotation_details);exit();
	$data['quotation_details']=array_shift($quotation_details);
	$data['quotation_products'] = $this->Sales_model->get_quotation_products_by_id($qtn_id);
    $html = $this->load->view('sales/quotation/print_quotation.php', $data, true);
	
	print_r($html);exit();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("estimation_$enquiry_id.pdf", array("Attachment" => 0));
}

public function Resurvey_from_qtn(){
    $quote_id       = $this->input->post('quotation_id');
    $enquiry_id     = $this->input->post('enquiry_id');
    $estimation_id  = $this->input->post('estimation_id');
    $created_by     = $this->input->post('created_by');

    // --- update quotation master ---
    $max_revision   = $this->Sales_model->get_max_revision($enquiry_id);
    $new_revision   = $max_revision + 1;
    $updateQtnData  = [
        'active'             => 0,
        'quotation_revision' => $new_revision,   
        'updated_on'         => date('Y-m-d H:i:s'),
        'updated_by'         => $created_by
    ];
    $qtnUpdated = $this->Sales_model->update_quotation($quote_id, $updateQtnData);

    // --- update estimation master ---
    $max_revision_est  = $this->Sales_model->get_max_revision_est($enquiry_id);
    $new_revision_est  = $max_revision_est + 1;
    $updateEstData = [
        'active'              => 0,
        'estimation_revision' => $new_revision_est,   
        'updated_on'          => date('Y-m-d H:i:s'),
        'updated_by'          => $created_by
    ];
    $estUpdated = $this->Sales_model->update_estimation($estimation_id, $updateEstData);

    // --- update site survey master ---
    $updateSurveyData = [
        'active'           => 0,
        're_survey_status' => 1 ,  
        'updated_on'       => date('Y-m-d H:i:s'),
        'updated_by'       => $created_by
    ];
    $surveyUpdated = $this->Sales_model->update_survey_master($enquiry_id, $updateSurveyData);

    // --- update enquiry master ---
    $updateEnqData = [
        'enquiry_status'    => 1,
        'reschedule_survey' => 1,
		'enquiry_revision'  => 1,
        'updated_on'        => date('Y-m-d H:i:s'),
        'updated_by'        => $created_by
    ];
    $enqUpdated = $this->Sales_model->update_enquiry_master($enquiry_id, $updateEnqData);

    // --- check all updates ---
    if ($qtnUpdated && $estUpdated && $surveyUpdated && $enqUpdated) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'One or more updates failed.']);
    }
}
public function estimation_revision($estimation_id,$enquiry_id){
	$data['estimation_id']=$estimation_id;
	$data['enquiry_id']=$enquiry_id;
	$data['enquiry_data'] = $this->Sales_model->get_enquiry_by_id($enquiry_id);
	$estimation_data=$this->estimation_revision_master_data($estimation_id);
	$data['master']=$estimation_data['revision_master'];
	$data['estimation']=$estimation_data['revision_estimation'];
	// print_r($data['estimation']);exit();	
	$data['title']="Estimation revision";
	$data['main_content'] = 'sales/estimation/view_estimation.php';
	$this->load->view('includes/template',$data);
	
}

public function estimation_revision_master_data($estimation_id){
		$rows=$this->Sales_model->get_estimation_revisions_data($estimation_id);
		//echo $this->db->last_query();exit();
		$data['revision_estimation'] = [];
    	$data['revision_master'] = null;

			foreach ($rows as $row) {
				if ($data['revision_master'] === null) {
					// only once - master details
					$data['revision_master'] = [
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
				if (!isset($data['revision_estimation'][$row['main_heading_id']])) {
					$data['revision_estimation'][$row['main_heading_id']] = [
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
						$data['revision_estimation'][$row['main_heading_id']]['sub_headings'][$row['sub_heading_id']]['products'][] = [
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
	public function print_estimation($estimation_id,$enquiry_id,$estimationtype=""){
		$enquiry_data           	=	$this->Sales_model->get_enquiry_by_id($enquiry_id);	
		
		$options = new Options();
		$options->set('isHtml5ParserEnabled', true);
		$options->set('isRemoteEnabled', true); // allows base_url() http paths
		$options->set('chroot', realpath('C:/xampp/htdocs/aladel_erp/public/')); 
		$dompdf = new Dompdf($options);
		
		$data['headerPath'] = base_url(ltrim($enquiry_data['branch_header'], '/'));
		$data['footerPath'] =  base_url(ltrim($enquiry_data['branch_footer'], '/'));
		
		$data['enquiry_data'] = $enquiry_data;
		$estimation_data=$this->estimation_revision_master_data($estimation_id,$estimationtype);
		$data['master']=$estimation_data['revision_master'];
		$data['estimation']=$estimation_data['revision_estimation'];
		
		$html = $this->load->view('crm/estimation/print_estimation.php', $data, true);
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$dompdf->stream("estimation_$enquiry_id.pdf", array("Attachment" => 0));

	}
	public function list_surveys(){
		$data['title']="survey Reports";
		$data['survey_list']= $this->Sales_model->list_survey_reports();
		$data['main_content'] = 'sales/survey_reports/list_survey_reports.php';
		$this->load->view('includes/template',$data);
		
		
	}

	public function print_survey($survey_id, $enquiry_id)
	{
		// Get enquiry data (branch + customer details)
		$enquiry_data = $this->Sales_model->get_enquiry_by_id($enquiry_id);

		$data['enquiry_code']       = $enquiry_data['enquiry_code'];
		$data['enquiry_category']   = $enquiry_data['enquiry_category'];
		$data['enquiry_date']       = $enquiry_data['enquiry_date'];
		$data['project_name']       = $enquiry_data['project_name'];
		$data['project_location']   = $enquiry_data['project_location'];

		$data['branch_id']          = $enquiry_data['branch_id'];
		$data['branch_name']        = $enquiry_data['branch_name'];
		$data['branch_header']      = $enquiry_data['branch_header'];
		$data['branch_footer']      = $enquiry_data['branch_footer'];
		$data['branch_contact']     = $enquiry_data['branch_contact'];
		$data['branch_email']       = $enquiry_data['branch_email'];
		$data['branch_location']    = $enquiry_data['branch_location'];
		$data['branch_address']     = $enquiry_data['branch_address'];
		$data['branch_website']     = $enquiry_data['branch_web'];

		$data['customer_name']      = $enquiry_data['customer_name'];
		$data['customer_address']   = $enquiry_data['customer_address'];
		$data['customer_email']     = $enquiry_data['customer_email'];
		$data['contact_number']     = $enquiry_data['contact_number'];
		$data['emirate']            = $enquiry_data['emirate'];

		$data['sales_person']       = $enquiry_data['user_name'];

		// Dompdf setup
		$options = new Options();
		$options->set('isHtml5ParserEnabled', true);
		$options->set('isRemoteEnabled', true);
		$options->set('chroot', realpath('C:/xampp/htdocs/aladel_erp/public/'));
		$dompdf = new Dompdf($options);

		// Header/Footer paths
		$data['headerPath'] = base_url(ltrim($data['branch_header'], '/'));
		$data['footerPath'] = base_url(ltrim($data['branch_footer'], '/'));

		// Get survey details
		$survey_data = $this->Sales_model->list_survey_reports($survey_id);
		$data['survey'] = $survey_data;

		$html = $this->load->view('sales/survey_reports/print_survey.php', $data, true);

		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$dompdf->stream("survey_$survey_id.pdf", array("Attachment" => 0));
	}
	public function list_estimations(){
		$data['title'] = 'Estimation List';
		$data['estimations'] = $this->Sales_model->get_estimation_master();
		$data['main_content'] = 'sales/estimation/list_estimation.php';
		$this->load->view('includes/template',$data);

	}
	public function view_estimation($estimation_id)
	{
		// Get only this estimation's details
		$estimations_raw = $this->Sales_model->get_all_estimation_details($estimation_id);

		if(empty($estimations_raw)){
			show_404(); // or redirect with message
		}

		// Group main headings, subheadings, and products (same as before)
		$estimation = [];
		foreach($estimations_raw as $row){
			$est_id = $row['estimation_id'];

			if(!isset($estimation[$est_id])){
				$estimation[$est_id] = [
					'estimation_id' 	=> $row['estimation_id'],
					'enquiry_id' 		=> $row['enquiry_id'],
					'estimation_date' 	=> $row['estimation_date'],
					'enquiry_date' 		=> $row['enquiry_date'],
					'enquiry_code' 		=> $row['enquiry_code'] ?? '',
					'customer_name'	 	=> $row['customer_name'] ?? '',
					'preparedby'	 	=> $row['preparedby'] ?? '',
					'branch_name' 		=> $row['branch_name'] ?? '',
					'main_headings'		=> [],
					'totals' => [
						'sub_total' 	     => $row['sub_total'],
						'margin_percentage'  => $row['margin_percentage'],
						'margin_amount'   	 => $row['margin_amount'],
						'freight_percentage' => $row['freight_percentage'],
						'freight_amount' 	 => $row['freight_amount'],
						'bank_charge'     	 => $row['bank_charge'],
						'travel_expense'  	 => $row['travel_expense'],
						'inspection_cost' 	 => $row['inspection_cost'],
						'other_expense'   	 => $row['other_expense'],
						'grand_total' 	  	 => $row['grand_total']
					]
				];
			}

			$main_id = $row['main_heading_id'];
			if(!isset($estimation[$est_id]['main_headings'][$main_id])){
				$estimation[$est_id]['main_headings'][$main_id] = [
					'main_heading' => $row['main_heading'],
					'main_details' => $row['main_details'],
					'sub_headings' => []
				];
			}

			$sub_id = $row['sub_heading_id'];
			if(!isset($estimation[$est_id]['main_headings'][$main_id]['sub_headings'][$sub_id])){
				$estimation[$est_id]['main_headings'][$main_id]['sub_headings'][$sub_id] = [
					'sub_heading' => $row['sub_heading'],
					'products' => []
				];
			}

			$estimation[$est_id]['main_headings'][$main_id]['sub_headings'][$sub_id]['products'][] = [
				'product_description' => $row['product_description'],
				'unit_name' => $row['unit_name'],
				'quantity' => $row['quantity'],
				'unit_price' => $row['unit_price'],
				'amount' => $row['amount']
			];
		}

		// Reindex arrays to remove keys
		$estimation = array_values($estimation)[0]; // only one estimation
		$data['estimations'] = $estimation;

		$data['title'] = 'Estimation Details';
		$data['main_content'] = 'crm/estimation/list_estimation_details.php';
		$this->load->view('includes/template', $data);
	}
	public function list_quotations()
{
    $data['title'] = 'Quotation List';
    $data['quotations'] = $this->Sales_model->get_quotation_master();
    $data['main_content'] = 'sales/quotation/list_quotation.php';
    $this->load->view('includes/template', $data);
}

public function view_quotation($qtn_id)
{
    // Get only this quotation's details
    $quotations_raw = $this->Sales_model->get_all_quotation_details($qtn_id);

    if (empty($quotations_raw)) {
        show_404(); // or redirect with flash message
    }

    $quotation = [];
    foreach ($quotations_raw as $row) {
        $qid = $row['qtn_id'];

        if (!isset($quotation[$qid])) {
            $quotation[$qid] = [
                'qtn_id'            => $row['qtn_id'],
                'quotation_code'    => $row['quotation_code'],
                'quotation_date'    => $row['quotation_date'],
                'enquiry_id'        => $row['enquiry_id'],
				'enquiry_code'      => $row['enquiry_code'],
                'estimation_id'     => $row['estimation_id'],
				'estimation_code'	=> $row['estimation_code'],
                'customer_name'     => $row['customer_name'] ?? '',
                'branch_name'       => $row['branch_name'] ?? '',
                'preparedby'        => $row['preparedby'] ?? '',
                'quotation_status'  => $row['quotation_status'] ?? '',
                'quotation_revision'=> $row['quotation_revision'] ?? '',
				'vat_required'      => $row['vat_required'] ?? '',
                'main_headings'     => [],
                'totals' => [
                    'sub_total'             => $row['sub_total'],
                    'estimation_amount'     => $row['estimation_amount'],
                    'total_before_discount' => $row['total_before_discount'],
                    'discount_percentage'   => $row['discount_percentage'],
                    'discount_amount'       => $row['discount_amount'],
                    'vat_required'          => $row['vat_required'],
                    'total_before_vat'      => $row['total_before_vat'],
                    'vat_percentage'        => $row['vat_percentage'],
                    'vat_amount'            => $row['vat_amount'],
                    'grand_total'           => $row['grand_total'],
                ],
                'terms' => [
                    'payment_term'   => $row['payment_term'],
                    'delivery_term'  => $row['delivery_term'],
                    'terms_condition'=> $row['terms_condition'],
                    'validity'       => $row['validity'],
                ]
            ];
        }

        $main_id = $row['main_heading_id'];
        if (!isset($quotation[$qid]['main_headings'][$main_id])) {
            $quotation[$qid]['main_headings'][$main_id] = [
                'main_heading' => $row['main_heading'],
                'description' => $row['description'],
                'sub_headings' => []
            ];
        }

        $sub_id = $row['sub_heading_id'];
        if (!isset($quotation[$qid]['main_headings'][$main_id]['sub_headings'][$sub_id])) {
            $quotation[$qid]['main_headings'][$main_id]['sub_headings'][$sub_id] = [
                'sub_heading' => $row['sub_heading'],
                'products' => []
            ];
        }

        $quotation[$qid]['main_headings'][$main_id]['sub_headings'][$sub_id]['products'][] = [
			 'product_name' 	  => $row['item_name'],
            'product_description' => $row['product_description'],
            'unit_name'           => $row['unit_name'],
            'quantity'            => $row['quantity'],
            'unit_price'          => $row['unit_price'],
            'amount'              => $row['amount']
        ];
    }

    // Only one quotation expected
    $quotation = array_values($quotation)[0];
    $data['quotation'] = $quotation;
	//print_r($data['quotation']);exit();
    $data['title'] = 'Quotation Details';
    $data['main_content'] = 'sales/quotation/quotation_view.php';
    $this->load->view('includes/template', $data);
}
public function view_quotation_details($qtn_id)
{
    // Load models
//$this->load->model('Quotation_model');

    // Quotation Master (header + totals + terms)
    $data['quotation'] = $this->Sales_model->get_quotation($qtn_id);

    // Main Headings
    $data['main_headings'] = $this->Sales_model->get_main_headings($qtn_id);

    // Sub Headings (grouped by main_heading_id)
    $data['sub_headings'] = [];
    foreach ($data['main_headings'] as $main) {
        $data['sub_headings'][$main['id']] = $this->Sales_model->get_sub_headings($qtn_id, $main['id']);
    }

    // Products (grouped by subheading_id)
    $data['products'] = [];
    foreach ($data['sub_headings'] as $subList) {
        foreach ($subList as $sub) {
            $data['products'][$sub['id']] = $this->Sales_model->get_products($qtn_id, $sub['id']);
        }
    }

    // Load view
    
	$data['title'] = 'Quotation Details';
    $data['main_content'] = 'sales/quotation/quotation_view.php';
    $this->load->view('includes/template', $data);
}

	public function convert_to_order($quotation_id, $enquiry_id)
{
    // get logged-in user
    $created_by = $this->session->userdata('user_id');

    // update enquiry status to "converted"
    $updateEnqData = [
        'enquiry_status' => 7, // converted to sales order
        'updated_on'     => date('Y-m-d H:i:s'),
        'updated_by'     => $created_by
    ];

    $enqUpdated = $this->Sales_model->update_enquiry_master($enquiry_id, $updateEnqData);

    if ($enqUpdated) {
        // optional: also update quotation table if needed
        // $this->Sales_model->update_quotation_status($quotation_id, ['status' => 'Converted']);

        redirect('Sales/view_enquiry/' . $enquiry_id);
    } else {
        // handle failure
        $this->session->set_flashdata('error', 'Failed to convert to Sales Order.');
        redirect('Sales/view_quotation/' . $enquiry_id);
    }
}
	public function save_sales_order()
	{
		//print_r($this->input->post());exit();
		if ($this->input->post()) {
			$created_by = $this->session->userdata('user_id');
			$created_on = date('Y-m-d H:i:s');
			$enquiry_id = $this->input->post('enquiry_id');
			$qtn_id = $this->input->post('quotation_id');

			// Start Transaction
			$this->db->trans_start();

			// 2. Find max revision for this quotation
			$this->db->select_max('so_revision');
			$this->db->where('qtn_id', $qtn_id);
			$max_revision_row = $this->db->get('sales_order_master')->row_array();

			$max_revision = isset($max_revision_row['so_revision']) ? (int)$max_revision_row['so_revision'] : 0;
			// --- Master Data ---
			$master_data = [
				'so_code'             => $this->input->post('so_code'),
				'enquiry_id'          => $enquiry_id,
				'estimation_id'       => $this->input->post('estimation_id'),
				'qtn_id'              => $qtn_id,
				'so_date'             => $this->input->post('so_date'),
				'so_revision'         => $max_revision + 1,
				'validity'            => $this->input->post('so_validity'),
				'payment_term'        => $this->input->post('so_payment_term'),
				'delivery_term'       => $this->input->post('so_delivery_term'),
				'terms_and_condition' => $this->input->post('so_terms_condition'),
				'remarks'             => $this->input->post('so_remarks'),
				'sub_total'           => $this->input->post('so_subtotal'),
				'discount_percentage' => $this->input->post('so_add_discount_percentage'),
				'discount_amount'     => $this->input->post('so_add_discount_amount'),
				'total_before_vat'    => $this->input->post('so_totalbefore_vat_amount'),
				'vat_required'        => 1,
				'vat_percentage'      => $this->input->post('so_vat_percentage'),
				'vat_amount'          => $this->input->post('so_vat_amount'),
				'grand_total'         => $this->input->post('so_grand_total'),
				'created_on'          => $created_on,
				'created_by'          => $created_by,
			];
			$so_id = $this->Sales_order_model->insert_sales_order_master($master_data);

			// --- Products ---
			if (!empty($_POST['product_id'])) {
				$products_batch = [];

				foreach ($this->input->post('product_id') as $k => $prd_id) {
					$products_batch[] = [
										'so_id'      	  => $so_id,
										'product_id' 	  => $prd_id,
										'unit_id'    	  => $this->input->post('unit_id')[$k],
										'quantity'		  => $this->input->post('so_qty')[$k],
										'unit_price' 	  => str_replace(',', '', $this->input->post('so_unitp')[$k]),
										'amount'     	  => str_replace(',', '', $this->input->post('so_amount')[$k]),
										'discount_amount' => str_replace(',', '', $this->input->post('so_discount')[$k]),
										'taxable_amount'  => str_replace(',', '', $this->input->post('so_taxable')[$k]),
									];
				}

				if (!empty($products_batch)) {
					$this->Sales_order_model->insert_sales_order_products_batch($products_batch);
				}
			}

			// --- Address ---
			$address_data = [
				'so_id'                   => $so_id,
				'billing_customer_name'   => $this->input->post('billing_name'),
				'billing_customer_address'=> $this->input->post('billing_address'),
				'billing_emirates'        => $this->input->post('billing_city'),
				'billing_contact'         => $this->input->post('billing_phone'),
				'billing_email'           => $this->input->post('billing_email'),
				'shipping_customer'       => $this->input->post('shipping_name'),
				'shipping_address'        => $this->input->post('shipping_address'),
				'shipping_emirate'        => $this->input->post('shipping_city'),
				'shipping_contact'        => $this->input->post('shipping_phone'),
				'shipping_email'          => $this->input->post('shipping_email'),
				'created_on'              => $created_on
			];

			$this->Sales_order_model->insert_sales_order_address($address_data);

			$updateEnqData = [
								'enquiry_status' => 8, // converted to sales order
								'updated_on'     => date('Y-m-d H:i:s'),
								'updated_by'     => $created_by
							];

    	$enqUpdated = $this->Sales_model->update_enquiry_master($enquiry_id, $updateEnqData);
			// Complete Transaction
			$this->db->trans_complete();

			// Check transaction status
			if ($this->db->trans_status() === FALSE) {
				// Rollback occurred
				$this->session->set_flashdata('error', 'Failed to save Sales Order, please try again.');
				redirect('Sales/view_enquiry/'. $enquiry_id);
			} else {
				// Success
				$this->session->set_flashdata('success', 'Sales Order saved successfully!');
				redirect('Sales/view_enquiry/' . $enquiry_id);
			}
		} else {
			show_error('Invalid request');
		}
	}

	public function get_sales_order_partial()
{
    $so_id  = $this->input->post('so_id');
    $enq_id = $this->input->post('enq_id');
    $qtn_id = $this->input->post('qtn_id');

    if (!$so_id) {
        echo '';
        return;
    }

    $data['so_master']  = $this->Sales_order_model->get_sales_order_master($so_id);
    $so_date = !empty($data['so_master']['so_date'])
        ? date('Y-m-d', strtotime($data['so_master']['so_date']))
        : date('Y-m-d');

    $data['so_address'] = $this->Sales_order_model->get_so_address($so_id);

    // get products + flag
    $so_result = $this->Sales_order_model->get_edit_so_quantities($so_id, $enq_id, $qtn_id);
    $data['so_products'] = $so_result['products'];
    $can_create_dc = $so_result['can_create_dc'];

    $html = $this->load->view('sales/include/so_product_table.php', $data, true);

    echo json_encode([
        'products_html' => $html,
        'so_date'       => $so_date,
        'so_master'     => $data['so_master'],
        'so_address'    => $data['so_address'],
        'can_create_dc' => $can_create_dc // <-- send flag to frontend
    ]);
    exit();
}


	public function update_sales_order()
{
	
    if ($this->input->post()) {
        $updated_by = $this->session->userdata('user_id');
        $updated_on = date('Y-m-d H:i:s');

        $so_id      = $this->input->post('so_id_upd'); // hidden field
        $enquiry_id = $this->input->post('enquiry_id');
        $qtn_id     = $this->input->post('quotation_id');

        // Start Transaction
        $this->db->trans_start();

        // --- Master Data ---
        $master_data = [
            'so_code'             => $this->input->post('so_edit_code'),
            'enquiry_id'          => $enquiry_id,
            'estimation_id'       => $this->input->post('estimation_id'),
            'qtn_id'              => $qtn_id,
            'so_date'             => $this->input->post('so_edit_date'),
            'validity'            => $this->input->post('so_edit_validity'),
            'payment_term'        => $this->input->post('so_edit_payment_term'),
            'delivery_term'       => $this->input->post('so_edit_delivery_term'),
            'terms_and_condition' => $this->input->post('so_edit_terms_condition'),
            'remarks'             => $this->input->post('so_edit_remarks'),
            'sub_total'           => $this->input->post('so_edit_subtotal'),
            'discount_percentage' => $this->input->post('so_edit_add_discount_percentage'),
            'discount_amount'     => $this->input->post('so_edit_add_discount_amount'),
            'total_before_vat'    => $this->input->post('so_edit_totalbefore_vat_amount'),
            'vat_required'        => 1,
            'vat_percentage'      => $this->input->post('so_edit_vat_percentage'),
            'vat_amount'          => $this->input->post('so_edit_vat_amount'),
            'grand_total'         => $this->input->post('so_edit_grand_total'),
            'updated_on'          => $updated_on,
            'updated_by'          => $updated_by,
        ];

        $this->Sales_order_model->update_sales_order_master($so_id, $master_data);

        // --- Products ---
        // Clear existing products and re-insert (simpler than partial updates)
        $this->Sales_order_model->delete_sales_order_products($so_id);

        if (!empty($_POST['so_edit_qty'])) {
            $products_batch = [];

            foreach ($this->input->post('so_edit_qty') as $k => $qty) {
                $products_batch[] = [
                    'so_id'           => $so_id,
                    'product_id'      => $this->input->post('product_id')[$k],
                    'unit_id'         => $this->input->post('unit_id')[$k],
                    'quantity'        => $qty,
                    'unit_price'      => str_replace(',', '', $this->input->post('so_edit_unitp')[$k]),
                    'amount'          => str_replace(',', '', $this->input->post('so_edit_amount')[$k]),
                    'discount_amount' => str_replace(',', '', $this->input->post('so_edit_discount')[$k]),
                    'taxable_amount'  => str_replace(',', '', $this->input->post('so_edit_taxable')[$k]),
                ];
            }

            if (!empty($products_batch)) {
                $this->Sales_order_model->insert_sales_order_products_batch($products_batch);
            }
        }

        // --- Address ---
        $address_data = [
            'billing_customer_name'    => $this->input->post('so_edit_billing_name'),
            'billing_customer_address' => $this->input->post('so_edit_billing_address'),
            'billing_emirates'         => $this->input->post('so_edit_billing_city'),
            'billing_contact'          => $this->input->post('so_edit_billing_phone'),
            'billing_email'            => $this->input->post('so_edit_billing_email'),
            'shipping_customer'        => $this->input->post('so_edit_shipping_name'),
            'shipping_address'         => $this->input->post('so_edit_shipping_address'),
            'shipping_emirate'         => $this->input->post('so_edit_shipping_city'),
            'shipping_contact'         => $this->input->post('so_edit_shipping_phone'),
            'shipping_email'           => $this->input->post('so_edit_shipping_email'),
            'updated_on'               => $updated_on
        ];

        $this->Sales_order_model->update_sales_order_address($so_id, $address_data);

        // Complete Transaction
        $this->db->trans_complete();

        // Check transaction status
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Failed to update Sales Order, please try again.');
            redirect('Sales/view_enquiry/'. $enquiry_id);
        } else {
            $this->session->set_flashdata('success', 'Sales Order updated successfully!');
            redirect('Sales/view_enquiry/' . $enquiry_id);
        }
    } else {
        show_error('Invalid request');
    }
}

public function update_delivery_notes_status() {
    $enquiry_id = $this->input->post('enquiry_id');
    if ($enquiry_id) {
        $this->db->where('enquiry_id', $enquiry_id);
        $this->db->update('enquiry_master', [
            'enquiry_status' => 9,
            'updated_by'     => $this->session->userdata('user_id'),
            'updated_on'     => date('Y-m-d H:i:s')
        ]);
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

public function update_enquiry_for_so() {
    $enquiry_id = $this->input->post('enquiry_id');
    if ($enquiry_id) {
        $this->db->where('enquiry_id', $enquiry_id);
        $this->db->update('enquiry_master', [
            'enquiry_status' => 7,
            'updated_by'     => $this->session->userdata('user_id'),
            'updated_on'     => date('Y-m-d H:i:s')
        ]);
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

public function get_sales_order_partial_del()
{
    $so_id  = $this->input->post('so_id');
    $enq_id = $this->input->post('enq_id');
    $qtn_id = $this->input->post('qtn_id');

    if (!$so_id) {
        echo '';
        return;
    }

    // ✅ Step 1: check in delivery_master
    $this->db->where('so_id', $so_id);
    $delivery_master = $this->db->get('delivery_master')->row_array();
    if ($delivery_master) {
        // If delivery exists → fetch from delivery tables
        $data['so_master']   = $delivery_master; 
       // $data['so_address']  = $this->Delivery_model->get_delivery_address($delivery_master['delivery_id']);
        $data['so_products'] = $this->Sales_order_model->get_delivery_products($delivery_master['del_id']);
    //    echo $this->db->last_query();exit();
        if (!empty($delivery_master['delivery_date'])) {
            $so_date = date('Y-m-d', strtotime($delivery_master['delivery_date']));
        } else {
            $so_date = date('Y-m-d');
        }

        $source = 'delivery'; // so you know where it came from
    } else {
        // If no delivery yet → fallback to sales order
        $data['so_master']   = $this->Sales_order_model->get_sales_order_master($so_id);

        if (!empty($data['so_master']['so_date'])) {
            $so_date = date('Y-m-d', strtotime($data['so_master']['so_date']));
        } else {
            $so_date = date('Y-m-d');
        }

        $data['so_address']  = $this->Sales_order_model->get_so_address($so_id);
        $data['so_products'] = $this->Sales_order_model->get_so_products($so_id);

        $source = 'sales_order';
    }

    // ✅ Render product table
    $html = $this->load->view('sales/include/del_product_table.php', $data, true);
	if($source=="delivery"){
				echo json_encode([
						'products_html' => $html,
						'so_date'       => $so_date,
						'so_master'     => $data['so_master'],
						'source'        => $source
					]);
	}else{
				echo json_encode([
				'products_html' => $html,
				'so_date'       => $so_date,
				'so_master'     => $data['so_master'],
				'so_address'    => $data['so_address'],
				'source'        => $source
			]);
	}
    
    exit();
}


	public function create_delivery_challan()
{
    $this->db->trans_begin();
    $enquiry_id = $this->input->post('enquiry_id');
    $so_id      = $this->input->post('sales_order_del');
    try {
        // === 1. Insert into delivery_master ===
        $delivery_master = [
            'delivery_code'   => $this->input->post('delivery_code'),
            'so_id'           => $so_id,
            'enquiry_id'      => $enquiry_id,
            'quotation_id'    => $this->input->post('quotation_id'),
            'delivery_mode'   => $this->input->post('delivery_mode'),
            'deliverd_by'     => $this->input->post('deliverd_by'),
            'item_issued_by'  => $this->session->userdata('user_id'),
            'delivery_date'   => $this->input->post('dc_date'),
            'created_by'      => $this->session->userdata('user_id'),
            'created_on'      => date('Y-m-d H:i:s')
        ];
        $this->db->insert('delivery_master', $delivery_master);
        $del_master_id = $this->db->insert_id();

        // === 2. Insert products ===
        $products  = $this->input->post('product_id');
        $quantities = $this->input->post('quantity');
        if (!empty($products)) {
            foreach ($products as $i => $pid) {
                $qty = isset($quantities[$i]) ? $quantities[$i] : 0;
                if ($pid && $qty > 0) {
                    $this->db->insert('delivery_products', [
                        'del_master_id' => $del_master_id,
                        'product_id'    => $pid,
                        'quantity'      => $qty,
                        'created_on'    => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }

        // === 3. Update SO status ===
        $this->Sales_order_model->update_sales_order_master($so_id, [
            'delivery_status' => 1,
            'updated_on'      => date('Y-m-d H:i:s'),
            'updated_by'      => $this->session->userdata('user_id')
        ]);

        // === 4. Commit + Redirect ===
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Failed to save delivery challan');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Delivery challan saved successfully');
        }

    } catch (Exception $e) {
        $this->db->trans_rollback();
        $this->session->set_flashdata('error', $e->getMessage());
    }

    // ✅ Redirect always here
    redirect('Sales/view_enquiry/' . $enquiry_id);
	}
	public function get_delivery_challan_invoice(){
			$del_id   = $this->input->post('del_id');
			$so_id    = $this->input->post('so_id');
			$qtn_id   = $this->input->post('qtn_id');
			$enquiry  = $this->input->post('enq_id');

			if (!$del_id) {
				echo '';
				return;
			}

			$enquiry_data					= $this->Sales_model->get_enquiry_by_id($enquiry);
			$branch_data['branch_bank']		= $this->Company_model->get_branch_bank_by_id($enquiry_data['branch_id']);
			$html_branch 					= $this->load->view('sales/include/branch_bank_table_invoice.php', $branch_data, true);
			$data['enquiry_data']			= $enquiry_data;
			$data['quotation_data'] 		= $this->Sales_model->get_quotation($qtn_id);
			$invoice_code 					= 'INV-' . str_pad($del_id, 4, '0', STR_PAD_LEFT);
			// Delivery Master
			$data['del_master']   			= $this->Sales_order_model->get_delivery_master($del_id);
			// Sales Order Master & Address
			$so_master						= $this->Sales_order_model->get_sales_order_master($so_id);
			$data['so_master']    			= array_shift($so_master);
			$data['so_address']   			= $this->Sales_order_model->get_so_address($so_id);
			// Products
			$so_result  			= $this->Sales_order_model->get_edit_so_quantities($so_id, $data['so_master']['enquiry_id'], $qtn_id);
			$data['so_products'] = $so_result['products'];  // ✅ only product array
			$data['can_create_dc'] = $so_result['can_create_dc']; // ✅ separate flag
			// Quotation Products
			$data['qtn_products'] 			= $this->Sales_model->get_quotation_products_by_id($qtn_id);
			//print_r($data['so_products']);exit();
			// Load view for products table (we can combine SO + QTN)
			$html = $this->load->view('sales/include/delivery_products_table_invoice.php', $data, true);
			echo json_encode([
								'products_html' => $html,
								'bank_table'    => $html_branch,
								'del_master'    => $data['del_master'],
								'so_master'     => $data['so_master'],
								'so_address'    => $data['so_address'],
								'invoice_code'  => $invoice_code,
								'enquiry_master'=> $data['enquiry_data'],
								'qtn_products'  => $data['qtn_products'],
								'qtn_master'    => $data['quotation_data'],
							]);
			exit();
			}

	public function create_invoice()
{
	//print_r($this->input->post('selected_bank'));exit();
    $this->db->trans_begin();

    try {
        // --- 1. Collect master data ---
        $data_master = [
            'invoice_code'              => $this->input->post('invoice_code'),
            'invoice_date'              => date('Y-m-d', strtotime($this->input->post('invoice_date'))),
            'delivery_id'               => $this->input->post('delivery_challan_id_inv'),
            'so_id'                     => $this->input->post('so_id_inv'),
            'quotation_id'              => $this->input->post('quotation_id_inv'),
            'enquiry_id'                => $this->input->post('enquiry_id_inv'),
            'branch_id'                 => $this->input->post('branch_id_inv'),
            'sub_total'                 => $this->input->post('inv_sub_total'),
            'discount_percentage'       => $this->input->post('inv_discount_per'),
            'discount_amount'           => $this->input->post('inv_discount_amt'),
            'vat_percentage'            => $this->input->post('inv_vat_per'),
            'vat_amount'                => $this->input->post('inv_vat_amount'),
            'grand_total'               => $this->input->post('inv_grand_total'),
            'payment_mode'              => $this->input->post('inv_payment_mode'),
            'advance_amount_percentage' => $this->input->post('inv_advance_per'),
            'advance_amount'            => $this->input->post('inv_advance_amt'),
            'balance_amount'            => $this->input->post('inv_balance_amt'),
            'validity'                  => $this->input->post('inv_validity'),
            'delivery_term'             => $this->input->post('inv_delivery_terms'),
            'payment_term'              => $this->input->post('inv_payment_terms'),
            'terms_and_condition'       => $this->input->post('inv_general_terms'),
            'remark'                    => $this->input->post('inv_remarks'),
            'bank_id'                   => $this->input->post('selected_bank'),
            'created_by'                => $this->session->userdata('user_id'),
            'created_at'                => date('Y-m-d H:i:s'),
        ];

        // --- 2. Insert master ---
        $invoice_id = $this->Sales_order_model->insert_invoice_master($data_master);

        // --- 3. Collect products ---
        $products = [];
        $product_ids      = $this->input->post('product_id');
        $product_descs    = $this->input->post('product_desc');
        $ordered_qtys     = $this->input->post('product_orderd_qty');
        $delivered_qtys   = $this->input->post('product_deliverd_qty');
        $unit_ids         = $this->input->post('product_unit_id');
        $unit_prices      = $this->input->post('unit_price');
        $total_amounts    = $this->input->post('total_amount');
        $discount_amounts = $this->input->post('discount_amount');
        $taxable_amounts  = $this->input->post('taxable_amount');

        if (!empty($product_ids)) {
            foreach ($product_ids as $i => $pid) {
                $products[] = [
                    'invoice_id'          => $invoice_id,
                    'product_id'          => $pid,
                    'product_description' => $product_descs[$i],
                    'order_quantity'      => $ordered_qtys[$i],
                    'deliver_quantity'    => $delivered_qtys[$i],
                    'unit_id'             => $unit_ids[$i],
                    'unit_price'          => $unit_prices[$i],
                    'total_amount'        => $total_amounts[$i],
                    'discount_amount'     => $discount_amounts[$i],
                    'taxable_amount'      => $taxable_amounts[$i],
                    'created_on'          => date('Y-m-d H:i:s'),
                ];
            }

            if (!empty($products)) {
                $this->Sales_order_model->insert_invoice_products($products);
            }
        }

		//Accounts ebntry
		for ($i = 0; $i < count($_POST['inv_creditor']); $i++) {
					$creditor = $_POST['inv_creditor'][$i];
					$cr_amount = $_POST['inv_cr_amount'][$i];
					if ($cr_amount > 0) {
						$data = array(
							'voucher_code' 		=> $this->input->post('invoice_code'),
							'voucher_date' 		=> date('Y-m-d h:i:s', strtotime("$vdate $vtime")),
							'voucher_type' 		=> 'S',
							/// Sales invoice  entry
							'customer_id' 		=> $this->input->post('customer_id'),
							'account_id' 		=> $creditor,
							'amount' 			=> $cr_amount,
							'drcr_type' 		=> 'Cr',
							//'narration' => $this->input->post('narration'),
							'trans_id' 			=> $invoice_id,
							'trans_type' 		=> 'S',
							'recordCreatedBy'   => $this->session->userdata('user_id')
						);
						$this->db->insert('voucher_transaction', $data);
						$vid = $this->db->insert_id();
					}
				}
		///-------

        // --- 4. Update delivery challan invoice_status ---
        $delivery_id = $this->input->post('delivery_challan_id_inv');
        if ($delivery_id) {
            $this->db->where('del_id', $delivery_id);
            $this->db->update('delivery_master', ['invoice_status' => 1]);
        }

        // --- 5. Commit or Rollback ---
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'Invoice could not be saved!');
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata('success', 'Invoice created successfully!');
        }
    } catch (Exception $e) {
        $this->db->trans_rollback();
        $this->session->set_flashdata('error', 'Error: ' . $e->getMessage());
    }

    redirect('Sales/view_enquiry/' . $this->input->post('enquiry_id_inv'));
}
public function list_sales_orders()
{
    $data['title']				 = 'Sales Order List';
    $data['active_sales_orders'] = $this->Sales_order_model->list_all_sales_order();
    $data['main_content']		 = 'sales/sales_order/list_sales_orders.php';
    $this->load->view('includes/template', $data);
}
public function list_delivery_challan(){
    $data['title'] = 'Delivery challan List';
    $data['active_delivery_challan'] = $this->Sales_order_model->list_all_delivery_challan();
    $data['main_content'] = 'sales/delivery_challan/list_delivery_notes.php';
    $this->load->view('includes/template', $data);
}
public function list_invoices(){
    $data['title'] = 'Invoice List';
    $data['active_invoice_list'] 	= $this->Sales_order_model->list_all_invoices();
	$data['cancelled_invoices']  	= $this->Sales_order_model->list_Cancel_invoices();
	$data['sales_return_invoices']  = $this->Sales_order_model->list_Sales_retun_invoices();
	$data['direct_invoices']  		= $this->Sales_order_model->list_direct_invoices();
	// echo $this->db->last_query();exit();
    $data['main_content'] = 'sales/invoices/list_invoices.php';
    $this->load->view('includes/template', $data);
}
 public function estimation_master_data($enquiry_id){
		$rows=$this->Crm_model->get_estimation_details($enquiry_id);
		$data['estimation'] = [];
    	$data['master'] = null;

			foreach ($rows as $row) {
				if ($data['master'] === null) {
					// only once - master details
					$data['master'] = [
						'estimation_id'   		=> $row['estimation_id'],
						'estimation_date' 		=> $row['estimation_date'],						
						'enquiry_id'      		=> $row['enquiry_id'],
						'sub_total'       		=> $row['sub_total'],
						'grand_total'     		=> $row['grand_total'],
						'margin_percentage'     => $row['margin_percentage'],						
						'margin_amount'     	=> $row['margin_amount'],
						//'discount_percentage'   => $row['discount_percentage'],
						//'discount_amount'       => $row['discount_amount'],
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

	public function cancel_invoice() {

    $invoice_id = $this->input->post('invoice_id');

    // 1. Get invoice header
    $invoice = $this->Sales_model->get_invoice_details($invoice_id);
    if (!$invoice) {
        echo json_encode(['status' => 'error', 'message' => 'Invoice not found.']);
        return;
    }

    // 2. Get invoice products
    $products = $this->Sales_model->get_invoice_products($invoice_id);
    if (empty($products)) {
        echo json_encode(['status' => 'error', 'message' => 'No products found in invoice.']);
        return;
    }

    // 3. Update invoice status
    $updateData = array(
        'invoice_status' => 'Cancel',
        'updated_on'     => date('Y-m-d H:i:s'),
        'updated_by'     => $this->session->userdata('user_id')
    );

    $update = $this->Sales_model->update_invoice_status($updateData, $invoice_id);

    if ($update) {
        // 4. Insert stock entries for each product
        foreach ($products as $prod) {
            $stockData = array(
                'stock_type'   => 'IN', // since cancelling, add stock back
                'trans_id'     => $invoice['invoice_code'],
                'stock_date'   => date('Y-m-d'),
                'year'         => date('Y'),
                'product_id'   => $prod['product_id'],
                'item_desc'    => $prod['product_description'],
                'unit_id'      => $prod['unit_id'],
                'quantity'     => $prod['deliver_quantity'], // return delivered qty
                'packing'      => null,
                'price'        => $prod['unit_price'],
                'remark'       => 'Invoice Cancelled - '.$invoice['invoice_code'],
                'created_by'   => $this->session->userdata('user_id'),
                'created_date' => date('Y-m-d H:i:s'),
                'status'       => 1,
                'invoice_id'   => $invoice_id,
                'dc_id'        => $invoice['delivery_id'] ?? null
            );

            $this->Sales_model->insert_stock_entry($stockData);
        }

        echo json_encode(['status' => 'success', 'message' => 'Invoice cancelled & stock updated.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to cancel invoice.']);
    }
}
public function sales_return($invoice_id) {
	$data['title']   			= "Sales Return";
	$data['invoice_id']			= $invoice_id;
    // Get invoice header
    $data['invoice']  			= $this->Sales_model->get_invoice_complete_details($invoice_id);
    // Get invoice products
    $data['products'] 			= $this->Sales_model->get_invoice_products($invoice_id);
	$data['invoice_address']  	= $this->Sales_model->get_invoice_address_details($invoice_id);
    // Load view with return form
	$data['main_content'] = 'sales/invoices/sales_return.php';
    $this->load->view('includes/template', $data);
}
public function save_sales_return($invoice_id) {
    $post = $this->input->post();

    // Load model
    $this->load->model('Sales_model');

    // Call model function
    $result = $this->Sales_model->saveSalesReturn($invoice_id, $post);

    if($result) {
        $this->session->set_flashdata('success', 'Sales return saved successfully!');
        redirect('Sales/list_invoices/');
    } else {
        $this->session->set_flashdata('error', 'Error saving sales return.');
        redirect('Sales/sales_return/'.$invoice_id);
    }
}
public function list_direct_invoice(){
	$data['invoice_code']   = 'DIR-' . str_pad(1, 4, '0', STR_PAD_LEFT);
	$data['title']		    = 'Direct Invoice';
	$data['branch_list']    = $this->Company_model->get_all_branches();	
	$data['products']		=  $this->Item_model->get_all_item_list();
	$data['active_units']	=  $this->Item_model->get_all_units();
	$data['main_content']   = 'sales/invoices/direct_invoice.php';
    $this->load->view('includes/template', $data);
}
public function direct_invoice(){
	$data['invoice_code']   = 'DIR-' . str_pad(1, 4, '0', STR_PAD_LEFT);
	$data['title']		    = 'Direct Invoice';
	$data['branch_list']    = $this->Company_model->get_all_branches();	
	$data['products']		=  $this->Item_model->get_all_item_list();
	$data['active_units']	=  $this->Item_model->get_all_units();
	$data['main_content']   = 'sales/invoices/direct_invoice.php';
    $this->load->view('includes/template', $data);
}
public function save_direct_invoice() {
   // $this->load->model('Invoice_model');

    // 1️⃣ Prepare invoice master data
    $masterData = [
        'invoice_code'        => $this->input->post('invoice_code'),
		'invoice_type'        => 'direct',
        'invoice_date'        => $this->input->post('invoice_date'),
        'branch_id'           => $this->input->post('branch_id'),
        'invoice_customer'    => $this->input->post('customer_id'),
        'sub_total'           => $this->input->post('sub_total'),
        'discount_percentage' => $this->input->post('add_discount_per'),
        'discount_amount'     => $this->input->post('add_discount_amt'),
        'vat_percentage'      => $this->input->post('vat_per'),
        'vat_amount'          => $this->input->post('vat_amount'),
        'grand_total'         => $this->input->post('grand_total'),
        'remark'              => $this->input->post('remarks'),
        //'direct_invoice'      => 1,
        'invoice_status'      => 'Draft',
        'sales_return'        => 0,
        'created_by'          => $this->session->userdata('user_id'),
        'created_at'          => date('Y-m-d H:i:s')
    ];

    // 2️⃣ Insert invoice master
    $invoice_id = $this->Sales_order_model->insert_invoice_master($masterData);

    // 3️⃣ Prepare product details for batch insert
    $products = [];
    $product_ids   = $this->input->post('product_id');
    $descriptions  = $this->input->post('product_desc');
    $units         = $this->input->post('unit');
    $quantities    = $this->input->post('quantity');
    $prices        = $this->input->post('unit_price');
    $discounts     = $this->input->post('discount');
    $totals        = $this->input->post('total');

    if (!empty($product_ids)) {
        foreach ($product_ids as $i => $prod_id) {
            if (!empty($prod_id)) {
                $products[] = [
                    'invoice_id'   			=> $invoice_id,
                    'product_id'   			=> $prod_id,
                    'product_description'   => $descriptions[$i],
                    'unit_id'         		=> $units[$i],
                    'deliver_quantity'     	=> $quantities[$i],
                    'unit_price'   			=> $prices[$i],
                    'discount_amount'     	=> $discounts[$i],
                    'total_amount'        	=> $totals[$i],
                ];
            }
        }
    }

    // 4️⃣ Insert all products in one go
    if (!empty($products)) {
        $this->Sales_order_model->insert_invoice_products($products);
    }

    // 5️⃣ Redirect
    $this->session->set_flashdata('success', 'Direct Invoice Created Successfully');
    redirect('Sales/list_invoices');
}



}

		


