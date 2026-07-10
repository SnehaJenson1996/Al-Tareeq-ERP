<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Sales extends CI_Controller
{


	public function __construct()
	{
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
		$this->load->model('Setup_model');
		$this->load->model('Project_model');

		$this->load->model('Company_model');
		$this->load->helper('menu_helper');
		//$this->load->helper('menu_helper');
	}

/***New code for Al Tareeq */

public function list_enquiries()
	{
		$user				 		 = $this->session->userdata('user_id');
		
			$data['title']			= 'Enquiry List';

			$data['all_enquiries']  = $this->Sales_model->get_all_enquiry_list();

			$data['main_content']   = 'sales/list_enquiries.php';
	
		$this->load->view('includes/template', $data);
	}


public function add_enquiry()
{
    $data['title'] = 'Add Enquiry';
	$prefix                 = 'AL' . date("y") . '-ENQ';
    $num                    = $this->Setup_model->get_next_code($prefix, 'enquiry_code', 'enquiry_master', 11) + 1;
	$digit                  = sprintf("%1$05d", $num);
	$data['enquiry_code']   = $prefix . $digit;
	$data['customer_list']  = $this->Setup_model->get_all_customer_list();
	$data['branch_list']    = $this->Setup_model->get_all_branches();
	$data['active_users']   = $this->Setup_model->get_active_user_list();

	$data['all_products']   = $this->Setup_model->get_all_item_list();
	$data['active_units']   = $this->Setup_model->get_all_units();
    $data['main_content'] = 'sales/add_enquiry';

    $this->load->view('includes/template', $data);
}



	public function add_enquiry_data()
{
    $this->load->model('Sales_model');

    $data = array(
        'branch_id'          => $this->input->post('branch'),
        'project_name'       => $this->input->post('project_name'),
        'project_subject'    => $this->input->post('project_subject'),
        'project_location'   => $this->input->post('project_location'),
        'enquiry_category'   => $this->input->post('enquiry_category'),
        'enquiry_code'       => $this->input->post('enquiry_code'),
        'enquiry_date'       => $this->input->post('enquiry_date'),
        'enquiry_source'     => $this->input->post('enquiry_source'),
        'enquiry_customer'   => $this->input->post('customer_id'),
        'client_ref_no'      => $this->input->post('client_ref_no'),
        'comments'           => $this->input->post('comments'),

        // Optional fields
        'created_by'         => $this->session->userdata('user_id'),
        'created_at'       => date('Y-m-d H:i:s')
    );

    $result = $this->Sales_model->add_enquiry_data($data);

    if($result)
    {
        $this->session->set_flashdata('success','Enquiry Added Successfully.');
    }
    else
    {
        $this->session->set_flashdata('error','Something went wrong.');
    }

    redirect('Sales/list_enquiries');
}


public function edit_enquiry($id)
{
	 $data['title'] = 'Edit Enquiry';
    $this->load->model('Sales_model');

   $data['customer_list']  = $this->Setup_model->get_all_customer_list();
	$data['branch_list']    = $this->Setup_model->get_all_branches();
	$data['active_users']   = $this->Setup_model->get_active_user_list();

	$data['all_products']   = $this->Setup_model->get_all_item_list();
	$data['active_units']   = $this->Setup_model->get_all_units();
   
    $data['enquiry_data']  = $this->Sales_model->get_enquiry_details($id);

    if (!$data['enquiry_data']) {
        show_404();
    }
	 $data['main_content'] = 'sales/edit_enquiry';

    $this->load->view('includes/template', $data);
}


public function update_enquiry_data()
{
    $this->load->model('Sales_model');

    $id = $this->input->post('enquiry_id');

    $data = array(
        'branch_id'         => $this->input->post('branch'),
        'enquiry_category'  => $this->input->post('enquiry_category'),
        'enquiry_date'      => $this->input->post('enquiry_date'),
        'enquiry_source'    => $this->input->post('enquiry_source'),
        'enquiry_customer'  => $this->input->post('customer_id'),
        'client_ref_no'     => $this->input->post('client_ref_no'),
        'project_name'      => $this->input->post('project_name'),
        'project_subject'   => $this->input->post('project_subject'),
        'project_location'  => $this->input->post('project_location'),
        'comments'          => $this->input->post('comments')
    );

        $data['updated_by'] = $this->session->userdata('user_id');
        $data['updated_on'] = date('Y-m-d H:i:s');

        $this->Sales_model->update_enquiry($id, $data);

        $this->session->set_flashdata('success', 'Enquiry Updated Successfully.');
    

    redirect('Sales/list_enquiries');
}


	public function view_enquiry()
	{
		$enquiry = $this->uri->segment(3);
		$this->load->model('Company_model');
		$this->load->model('Item_model');
		$enquiry_details = $this->Sales_model->get_enquiry_by_id($enquiry);

		$branch_id				= $enquiry_details['branch_id'];

		$data['title']			=  "Enquiry Details";
		$data['Resurvey']  		=  $enquiry_details['reschedule_survey'];
		$data['customer_list']  =  $this->Company_model->get_customers_by_branch($branch_id);
		$data['all_products']	=  $this->Item_model->get_all_item_list();
		$data['active_units']	=  $this->Item_model->get_all_units();
		$des_id					=  2; //DESIGNATION SITE ENGINEER
		$data['employee_list']  =  $this->Company_model->get_all_employees_designation_id($des_id, $branch_id);
		$data['enquiry_data']   =  $enquiry_details;
		$enquiry_revision       =  $enquiry_details['enquiry_revision'];
		$customer_id            =  $enquiry_details['enquiry_customer'];
		if ($enquiry_revision == 1) {
			$data['old_survey_data'] = $this->Sales_model->get_survey_old_data($enquiry);
		}
		// echo $enquiry_details['enquiry_status'];exit();
		if ($enquiry_details['enquiry_status'] >= 2) {
			$survey_details = $this->Sales_model->get_survey_by_enquiry_id($enquiry);
			if (!empty($survey_details)) {
				$data['enquiry_id']		 = $enquiry_details['enquiry_id'];
				$data['survey_data']	 = $survey_details;
				$data['survey_files']	 = $this->Sales_model->get_survey_files_by_id($survey_details['survey_id']);
				$data['old_survey_data'] = $this->Sales_model->get_survey_old_data($enquiry);
				//Resurvey from Quotation
				if ($enquiry_revision == 1) {
					$data['estimation_revisions'] = $this->Sales_model->get_estimation_ids($enquiry);
				}
				if ($enquiry_details['enquiry_status'] >= 4) {
					// echo "here";exit();
					$Enquiry_details     =  $this->estimation_master_data($enquiry);
					$data['master']      =  $Enquiry_details['master'];
					// print_r($data['master']);exit();
					$i = 0;
					foreach ($Enquiry_details['estimation'] as  $main) {
						$i++;
					}
					$data['mainIndex']   = $i;
					$data['estimation']  = $Enquiry_details['estimation'];
					if ($enquiry_details['enquiry_status'] == 5) { //Register quotation
						$customer_id    			 = $enquiry_details['enquiry_customer'];
						$data['Customer_contacts']   = $this->Company_model->get_customer_contact_by_cust_id($customer_id);
						$data['quotation_code']		 = "QTN-" . '0' . $enquiry_details['branch_id'] . "-" . date('Ymd');
					}
					if ($enquiry_details['enquiry_status'] == 6) {
						$quotation_details			= $this->get_quotation_master_data($enquiry);
						$data['qtn_master']			= $quotation_details['qtn_master'];
						$data['qtn_details']        = $quotation_details['quotation'];
						$data['qtn_revisions']		= $this->Sales_model->get_all_qtn_revisions($enquiry);
					}
					if ($enquiry_details['enquiry_status'] == 7) { //Creating new  sales order							

						$quotation_details	 	= $this->get_quotation_master_data($enquiry, 1);
						$data['so_code']    	= $this->Sales_model->generate_so_code();
						$data['qtn_master']  	= $quotation_details['qtn_master'];
						$qtn_id				 	= $data['qtn_master']['quotation_id'];
						$products_with_avail	= $this->Sales_order_model->get_available_quantities($enquiry, $qtn_id);
						// echo $this->db->last_query();exit();
						$data['qtn_products']	= $products_with_avail;
						//print_r($data['qtn_products']);exit();
						$data['qtn_revisions']	= $this->Sales_model->get_all_qtn_revisions($enquiry);
					}
					if ($enquiry_details['enquiry_status'] == 8) {
						$quotation_details = $this->get_quotation_master_data($enquiry, 1);
						$data['so_code']     = $this->Sales_model->generate_so_code();
						$data['qtn_master']  = $quotation_details['qtn_master'];
						$data['qtn_details'] = $quotation_details['quotation'];
						$data['qtn_products'] = $this->Sales_model->get_all_products($data['qtn_master']['quotation_id']);
						$data['sales_order_list'] = $this->Sales_order_model->get_sales_order_list_data($data['enquiry_id'], $data['qtn_master']['quotation_id']);
						$data['qtn_revisions'] = $this->Sales_model->get_all_qtn_revisions($enquiry);
					}
					if ($enquiry_details['enquiry_status'] == 9) {

						$data['qtn_revisions'] = $this->Sales_model->get_all_qtn_revisions($enquiry);
						$quotation_details = $this->get_quotation_master_data($enquiry, 1);
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
						$data['sales_order_list'] = $this->Sales_order_model->get_sales_order_list_data($data['enquiry_id'], $data['qtn_master']['quotation_id']);
						$data['delivery_challan_list'] = $this->Sales_order_model->get_delivery_challan_list_data($data['enquiry_id'], $data['qtn_master']['quotation_id']);
					}
				}
			} else {
				$data['Resurvey'] = 1;
			}
		}
		$data['main_content'] = 'sales/view_quotation.php';
		//$data['main_content'] = 'sales/estimation/test_estimation.php';
		$this->load->view('includes/template', $data);
	}


	public function create_quotation()
	{
		$estimation_id = $this->input->post('estimation_id');
		$enquiry_id = $this->get_enquiry_id_by_estimation($estimation_id);
		$this->db->where('enquiry_id', $enquiry_id);
		$this->db->update('enquiry_master', [
			'enquiry_status'  => 5,
			'updated_on'      => date('Y-m-d H:i:s')
		]);
		redirect("Sales/view_enquiry/$enquiry_id");
	}
	public function add_quotation_old()
	{
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
				'total_before_discount' => $this->input->post('qtn_sub_total'),
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

				'active'			   => 1,
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
										'discount_amount' 	=> $prod['discount_amount'],
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

		redirect('Sales/view_enquiry/' . $enquiry_id);
	}


	public function update_quotation_old()
	{
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
				'total_before_discount' => $this->input->post('qtn_sub_total'),
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

		redirect('Sales/view_enquiry/' . $enquiry_id);
	}
	/*private function _save_qtn_details($qtn_id)
	{
		$main_headings = $this->input->post('main_heading') ?? [];
		$main_details  = $this->input->post('main_details') ?? [];
		$sub_headings  = $this->input->post('sub_heading') ?? [];
		$products      = $this->input->post('products') ?? [];

		foreach ($main_headings as $i => $main_heading) {

			// Save Main Heading
			$mainData = [
				'qtn_id'       => $qtn_id,
				'main_heading' => $main_heading,
				'description'  => $main_details[$i] ?? ''
			];
			$main_heading_id = $this->Sales_model->insert_main_heading($mainData);

			if (empty($sub_headings[$i])) continue;

			foreach ($sub_headings[$i] as $j => $sub_heading) {

				// Save Sub Heading
				$subData = [
					'qtn_id'          => $qtn_id,
					'main_heading_id' => $main_heading_id,
					'subheading'      => $sub_heading
				];
				$sub_id = $this->Sales_model->insert_subheading($subData);

				if (empty($products[$i][$j])) continue;

				foreach ($products[$i][$j] as $prod) {

					if (empty($prod['product_id'])) continue;

					$prodData = [
						'qtn_id'         => $qtn_id,
						'sub_heading_id' => $sub_id,
						'prd_id'         => $prod['product_id'],
						'unit_id'        => $prod['unit'],
						'unit_price'     => $prod['unit_price'],
						'qty'            => $prod['quantity'],
						'amount'         => $prod['amount'],
						'discount_amount' => $prod['discount_amount'],
						'taxable_amount' => $prod['taxable_amount']
					];

					$this->Sales_model->insert_product($prodData);
				}
			}
		}
	}*/

	// private function _save_qtn_details($qtn_id)
	// {
	// 	$this->db->trans_start(); // 🔹 Start transaction

	// 	if ($this->input->post('main_heading')) {
	// 		foreach ($this->input->post('main_heading') as $i => $main_heading) {
	// 			$mainData = [
	// 				'qtn_id'       => $qtn_id,
	// 				'main_heading' => $main_heading,
	// 				'description'  => $this->input->post('main_details')[$i] ?? ''
	// 			];
	// 			$main_heading_id = $this->Sales_model->insert_main_heading($mainData);

	// 			if (isset($_POST['sub_heading'][$i])) {
	// 				foreach ($_POST['sub_heading'][$i] as $j => $sub_heading) {
	// 					$subData = [
	// 						'qtn_id'       	  => $qtn_id,
	// 						'main_heading_id' => $main_heading_id,
	// 						'subheading'      => $sub_heading
	// 					];
	// 					$sub_id = $this->Sales_model->insert_subheading($subData);
	// 					if (isset($_POST['products'][$i][$j])) {
	// 						foreach ($_POST['products'][$i][$j] as $prod) {
	// 							$prodData = [
	// 								'qtn_id'         => $qtn_id,
	// 								'sub_heading_id' => $sub_id,
	// 								'prd_id'         => $prod['product_id'],
	// 								'unit_id'        => $prod['unit'],
	// 								'unit_price'     => $prod['unit_price'],
	// 								'qty'            => $prod['quantity'],
	// 								'amount'         => $prod['amount'],
	// 								'discount_amount'  		=> $prod['discount_amount'],
	// 								'discount_percent' => $prod['discount_percentage'] ?? $prod['discount_percent'] ?? 0,
	// 								'taxable_amount'        => $prod['taxable_amount']
	// 							];
	// 							$this->Sales_model->insert_product($prodData);
	// 						}
	// 					}
	// 				}
	// 			}
	// 		}
	// 	}

	// 	$this->db->trans_complete(); // 🔹 Commit or Rollback automatically

	// 	if ($this->db->trans_status() === FALSE) {
	// 		// Rollback happened
	// 		return false;
	// 	} else {
	// 		return true;
	// 	}
	// }

	private function _save_qtn_details($qtn_id)
	{
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
									'prd_description' => $prod['product_description'],
									'discount_amount'  		=> $prod['discount_amount'],
									'discount_percent' => $prod['discount_percentage'] ?? $prod['discount_percent'] ?? 0,
									'taxable_amount'        => $prod['taxable_amount'],
									'warranty'       => $prod['warranty'] ?? ''
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

	/*public function print_quotation($qtn_id, $enquiry_id="")
	{

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
		$data['quotation_details'] = $quotation_details;
		$data['quotation_products'] = $this->Sales_model->get_quotation_products_by_id($qtn_id);
		$html = $this->load->view('sales/quotation/print_quotation.php', $data, true);


		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$dompdf->stream("estimation_$enquiry_id.pdf", array("Attachment" => 0));
	}*/


public function print_quotation($qtn_id, $enquiry_id = "")
{
    // -----------------------------
    // Fetch quotation master details
    // -----------------------------
    $quotation = $this->Sales_model->get_quotation_master_by_id($qtn_id);

    if (empty($quotation)) show_404();
    $quotation = $quotation[0];

    // -----------------------------
    // Branch & Customer info
    // -----------------------------
    $data['project_name']     = $quotation['project_name'];
    $data['project_location'] = $quotation['project_location'] ?? '';

    $data['branch_id']        = $quotation['quotation_type'] == 'direct' ? $quotation['quotation_branch_id'] : ($quotation['branch_id'] ?? '');
    $data['branch_name']      = $quotation['quotation_type'] == 'direct' ? null : ($quotation['branch_name'] ?? '');
    $data['branch_contact']   = $quotation['branch_contact'] ?? '';
    $data['branch_email']     = $quotation['branch_email'] ?? '';
    $data['branch_location']  = $quotation['branch_location'] ?? '';
    $data['branch_address']   = $quotation['branch_address'] ?? '';
    $data['branch_website']   = $quotation['branch_web'] ?? '';
	 $data['branch_stamp']  = $quotation['branch_stamp'] ?? '';


    $data['customer_name']    = $quotation['customer_name'] ?? '';
    $data['customer_address'] = $quotation['customer_address'] ?? '';
    $data['customer_email']   = $quotation['customer_email'] ?? '';
    $data['contact_number']   = $quotation['contact_number'] ?? '';
	 $data['customer_TR_no']   = $quotation['customer_TR_no'] ?? '';
	  $data['contact_name']   = $quotation['contact_name'] ?? '';
    $data['emirate']          = $quotation['emirate'] ?? '';

    $data['sales_person']     = $quotation['user_name'] ?? '';

	$this->load->model('Company_model');
    $prepared_by_id =  $quotation['prepared_by'] ?? '';
    $approved_by_id = $quotation['approved_by'] ?? '';

    $prepared_by_name = '';
    $approved_by_name = '';
    $prepared_signature = '';
     $approved_signature = '';
	
	  if (!empty($prepared_by_id)) {
        $prepared_emp = $this->Company_model->get_employee_by_id($prepared_by_id);
        $prepared_by_name = $prepared_emp->employee_name ?? '';
        $prepared_signature = $prepared_emp->signature_file ?? '';
		$prepared_by_contact = $prepared_emp->mobile ?? '';

    }

	if (!empty($approved_by_id)) {
        $approved_emp = $this->Company_model->get_employee_by_id($approved_by_id);
        $approved_by_name = $approved_emp->employee_name ?? '';
        $approved_signature = $approved_emp->signature_file ?? '';

    }
    // -----------------------------
    // Header & Footer Paths
    // -----------------------------
    $headerFile = $quotation['branch_header'] ?? '';
    $footerFile = $quotation['branch_footer'] ?? '';

    // Absolute server paths for Dompdf
    $fullHeaderPath = !empty($headerFile) ? FCPATH . ltrim($headerFile, './') : '';
    $fullFooterPath = !empty($footerFile) ? FCPATH . ltrim($footerFile, './') : '';

    // Check if header/footer exist, log if missing
    if (!empty($fullHeaderPath) && !file_exists($fullHeaderPath)) {
        log_message('error', "Header file not found: $fullHeaderPath");
        $fullHeaderPath = '';
    }
    if (!empty($fullFooterPath) && !file_exists($fullFooterPath)) {
        log_message('error', "Footer file not found: $fullFooterPath");
        $fullFooterPath = '';
    }

    // For view: use full server path (Dompdf needs absolute path)
    $data['headerPath'] = $fullHeaderPath;
    $data['footerPath'] = $fullFooterPath;

	 $data['prepared_by_name'] = $prepared_by_name;
    $data['approved_by_name'] = $approved_by_name;
     $data['prepared_signature'] = $prepared_signature ?? '';
      $data['approved_signature'] = $approved_signature ?? '';
	  	 $data['prepared_by_contact'] = $prepared_by_contact ?? '';


    // -----------------------------
    // Quotation details/products
    // -----------------------------
    $data['quotation_details']  = $this->Sales_model->get_quotation_details_by_id($qtn_id);
    $data['quotation_products'] = $this->Sales_model->get_quotation_products_by_id($qtn_id);
    $this->load->model('Setup_model');

	    $data['comapny_records'] = $this->Setup_model->get_company_details();

    // -----------------------------
    // Load main HTML view
    // -----------------------------
    $main_html = $this->load->view('sales/quotation/print_quotation.php', $data, true);

    // -----------------------------
    // Dompdf setup
    // -----------------------------
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true); // allow external images
    $options->set('chroot', realpath(FCPATH)); // restrict to public folder

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($main_html);
    $dompdf->setPaper('A4', 'portrait');

    // -----------------------------
    // Render and stream PDF
    // -----------------------------
    $dompdf->render();
    $dompdf->stream("quotation_$qtn_id.pdf", ["Attachment" => 0]);
}
	public function estimation_revision($estimation_id, $enquiry_id)
	{
		$data['estimation_id'] = $estimation_id;
		$data['enquiry_id'] = $enquiry_id;
		$data['enquiry_data'] = $this->Sales_model->get_enquiry_by_id($enquiry_id);
		$estimation_data = $this->estimation_revision_master_data($estimation_id);
		$data['master'] = $estimation_data['revision_master'];
		$data['estimation'] = $estimation_data['revision_estimation'];
		// print_r($data['estimation']);exit();	
		$data['title'] = "Estimation revision";
		$data['main_content'] = 'sales/estimation/view_estimation.php';
		$this->load->view('includes/template', $data);
	}

	public function estimation_revision_master_data($estimation_id)
	{
		$rows = $this->Sales_model->get_estimation_revisions_data($estimation_id);
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
	public function print_estimation($estimation_id, $enquiry_id, $estimationtype = "")
	{
		$enquiry_data           	=	$this->Sales_model->get_enquiry_by_id($enquiry_id);

		$options = new Options();
		$options->set('isHtml5ParserEnabled', true);
		$options->set('isRemoteEnabled', true); // allows base_url() http paths
		$options->set('chroot', realpath('C:/xampp/htdocs/aladel_erp/public/'));
		$dompdf = new Dompdf($options);

		$data['headerPath'] = base_url(ltrim($enquiry_data['branch_header'], '/'));
		$data['footerPath'] =  base_url(ltrim($enquiry_data['branch_footer'], '/'));

		$data['enquiry_data'] = $enquiry_data;
		$estimation_data = $this->estimation_revision_master_data($estimation_id, $estimationtype);
		$data['master'] = $estimation_data['revision_master'];
		$data['estimation'] = $estimation_data['revision_estimation'];

		$html = $this->load->view('crm/estimation/print_estimation.php', $data, true);
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$dompdf->stream("estimation_$enquiry_id.pdf", array("Attachment" => 0));
	}
	public function list_surveys()
	{
		$data['title'] = "survey Reports";
		$data['survey_list'] = $this->Sales_model->list_survey_reports();
		$data['main_content'] = 'sales/survey_reports/list_survey_reports.php';
		$this->load->view('includes/template', $data);
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
	public function list_estimations()
	{
		$data['title'] = 'Estimation List';
		$data['estimations'] = $this->Sales_model->get_estimation_master();
		$data['main_content'] = 'sales/estimation/list_estimation.php';
		$this->load->view('includes/template', $data);
	}
	public function view_estimation($estimation_id)
	{
		// Get only this estimation's details
		$estimations_raw = $this->Sales_model->get_all_estimation_details($estimation_id);

		if (empty($estimations_raw)) {
			show_404(); // or redirect with message
		}

		// Group main headings, subheadings, and products (same as before)
		$estimation = [];
		foreach ($estimations_raw as $row) {
			$est_id = $row['estimation_id'];

			if (!isset($estimation[$est_id])) {
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
			if (!isset($estimation[$est_id]['main_headings'][$main_id])) {
				$estimation[$est_id]['main_headings'][$main_id] = [
					'main_heading' => $row['main_heading'],
					'main_details' => $row['main_details'],
					'sub_headings' => []
				];
			}

			$sub_id = $row['sub_heading_id'];
			if (!isset($estimation[$est_id]['main_headings'][$main_id]['sub_headings'][$sub_id])) {
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

		// Get quotation type
		$data['quotation_type'] = $quotations_raw[0]['quotation_type'];
		$is_direct = ($data['quotation_type'] === 'direct');

		$quotation = [];

		foreach ($quotations_raw as $row) {
			$qid = $row['qtn_id'];

			if (!isset($quotation[$qid])) {
				$quotation[$qid] = [
					'qtn_id'            => $row['qtn_id'],
					'quotation_code'    => $row['quotation_code'],
					'quotation_date'    => $row['quotation_date'],
					'enquiry_id'        => $row['enquiry_id'],
					'enquiry_code'      => $is_direct ? null : ($row['enquiry_code'] ?? null),
					'estimation_id'     => $row['estimation_id'],
					'estimation_code'   => $is_direct ? null : ($row['estimation_code'] ?? null),
					'customer_name'     => $row['customer_name'] ?? '',
					'branch_name'       => $row['branch_name'] ?? '',
					'project_name'      => $row['project_name'] ?? '', // for direct quotation
					'preparedby'        => $row['preparedby'] ?? '',
					'quotation_status'  => $row['quotation_status'] ?? '',
					'quotation_revision' => $row['quotation_revision'] ?? '',
					'vat_required'      => $row['vat_required'] ?? '',
					'main_headings'     => [],
					'totals' => [
						'sub_total'             => $row['sub_total'],
						'estimation_amount'     => $row['estimation_amount'],
						'total_before_discount' => $row['total_before_discount'],
						'discount_percentage' => $row['master_discount_percentage'] ?? 0,
                        'discount_amount'     => $row['master_discount_amount'] ?? 0,
						'vat_required'          => $row['vat_required'],
						'total_before_vat'      => $row['total_before_vat'],
						'vat_percentage'        => $row['vat_percentage'],
						'vat_amount'            => $row['vat_amount'],
						'grand_total'           => $row['grand_total'],
						    'other_charge'         => $row['other_charge'] ?? 0,


					],
					'terms' => [
						'payment_term'   => $row['payment_term'],
						'delivery_term'  => $row['delivery_term'],
						'terms_condition' => $row['terms_condition'],
						'validity'       => $row['validity'],
					]
				];
			}

			$main_id = $row['main_heading_id'];
			if (!isset($quotation[$qid]['main_headings'][$main_id])) {
				$quotation[$qid]['main_headings'][$main_id] = [
					'main_heading' => $row['main_heading'],
					'description'  => $row['description'],
					'sub_headings' => []
				];
			}

			$sub_id = $row['sub_heading_id'];
			if (!isset($quotation[$qid]['main_headings'][$main_id]['sub_headings'][$sub_id])) {
				$quotation[$qid]['main_headings'][$main_id]['sub_headings'][$sub_id] = [
					'sub_heading' => $row['sub_heading'],
					'products'    => []
				];
			}

			$quotation[$qid]['main_headings'][$main_id]['sub_headings'][$sub_id]['products'][] = [
				'product_name'         => $row['item_name'],
				'product_description'  => $row['product_description'],
				'unit_name'            => $row['unit_name'],
				'quantity'             => $row['quantity'],
				'unit_price'           => $row['unit_price'],
				'discount_amount'               => $row['discount_amount'],
				'amount'               => $row['amount'],
				'taxable_amount'    => $row['taxable_amount']
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
	public function process_sales_action($so_id)
	{
		$action = $this->input->post('action');

		if ($action == 'reserve_products') {
			$this->reserve_stock($so_id);
		} elseif ($action == 'delivery_challan') {

			redirect('Sales/add_delivery_challan?so_id=' . $so_id);
		}

		// Redirect back to the sales order page after action
		redirect('Sales/view_sales_order/' . $so_id);
	}
	public function reserve_stock($so_id)
	{
		$result = $this->reserve_products($so_id);

		if ($result['type'] === 'success') {
			$this->session->set_flashdata(
				'success',
				$result['message'] . ' (Priority: ' . $result['priority'] . ')'
			);
		} elseif ($result['type'] === 'warning') {
			$this->session->set_flashdata(
				'error', // or 'warning' if you add warning alert
				$result['message'] . ' (Priority: ' . $result['priority'] . ')'
			);
		} else {
			$this->session->set_flashdata(
				'error',
				$result['message']
			);
		}

		redirect('Sales/view_sales_order/' . $so_id);
	}

	private function reserve_products($so_id)
	{
		// echo "here";exit();
		$this->db->trans_start();

		$so_master = $this->Sales_order_model->get_sales_order_master($so_id);
		$quotation_details = $this->Sales_model->get_all_quotation_details($so_master['qtn_id']);

		$customer_id = isset($quotation_details[0]['quotation_customer'])
			? $quotation_details[0]['quotation_customer']
			: $quotation_details[0]['enquiry_customer'];

		$project      = $quotation_details[0]['project_name'];
		$reserve_code = 'RES-' . date('Ymd') . '-' . $so_id;

		$so_products = $this->db
			->get_where('sales_order_products', ['so_id' => $so_id])
			->result_array();

		$has_pending = false;

		/* =====================================================
       🔑 CALCULATE ONE PRIORITY FOR THIS SALES ORDER
       ===================================================== */

		$product_ids = array_column($so_products, 'product_id');

		$max_priority = $this->db
			->select_max('reserve_priority')
			->where_in('product_id', $product_ids)
			->where('status', 1)
			->get('stock_details')
			->row()
			->reserve_priority;

		$new_priority = ($max_priority !== null) ? ($max_priority + 1) : 1;

		/* =====================================================
       🔁 RESERVE PRODUCTS (SAME PRIORITY)
       ===================================================== */

		foreach ($so_products as $item) {

			$required_qty  = (float) $item['quantity'];
			$current_stock = $this->get_current_stock($item['product_id']);
			if ($current_stock >= $required_qty) {
				$reserved_qty = $required_qty;
				$pending_qty  = 0;
			} elseif ($current_stock > 0) {
				$reserved_qty = $current_stock;
				$pending_qty  = $required_qty - $current_stock;
				$has_pending  = true;
			} else {
				$reserved_qty = 0;
				$pending_qty  = $required_qty;
				$has_pending  = true;
			}

			$this->db->insert('stock_details', [
				'product_id'        => $item['product_id'],
				'quantity'          => $required_qty,
				'reserved_quantity' => $reserved_qty,
				'pending_quantity'  => $pending_qty,
				'stock_type'        => 'RESERVE',
				'reserve_priority'  => $new_priority, // 🔑 SAME FOR ALL
				'allocation'        => $reserve_code,
				'allocation_for'    => $customer_id,
				'allocation_id'     => $so_id,
				'trans_id'          => $so_id,
				'project'           => $project,
				'created_by'        => $this->session->userdata('user_id'),
				'created_date'      => date('Y-m-d H:i:s'),
				'status'            => 1
			]);
		}

		/* =====================================================
       🔄 UPDATE SALES ORDER
       ===================================================== */

		$this->db->where('so_id', $so_id)->update('sales_order_master', [
			'reserved_status' => 1,
			'reserve_code'    => $reserve_code,
			'reserved_date'   => date('Y-m-d H:i:s'),
			'stock_status'    => $has_pending ? 'PARTIAL' : 'FULL'
		]);

		$this->db->trans_complete();

		/* =====================================================
       ✅ SUCCESS MESSAGE
       ===================================================== */

		if ($this->db->trans_status() === FALSE) {
			return [
				'status'  => false,
				'message' => 'Stock reservation failed. Please try again.'
			];
		}

		return [
			'status'   => true,
			'priority' => $new_priority,
			'message'  => $has_pending
				? 'Stock partially reserved. Pending quantities exist.'
				: 'Stock successfully reserved for this sales order.'
		];
	}


	private function get_current_stock($product_id)
	{
		$in = $this->db->select_sum('quantity')
			->where('product_id', $product_id)
			->where('stock_type', 'IN')

			->get('stock_details')->row()->quantity ?? 0;

		$out = $this->db->select_sum('quantity')
			->where('product_id', $product_id)
			->where('stock_type', 'OUT')

			->get('stock_details')->row()->quantity ?? 0;

		$reserve = $this->db->select_sum('quantity')
			->where('product_id', $product_id)
			->where('stock_type', 'RESERVE')

			->get('stock_details')->row()->quantity ?? 0;

		return max(0, (float)$in - (float)$out - (float)$reserve);
	}


	public function update_delivery_notes_status()
	{
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

	public function update_enquiry_for_so()
	{
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

	//New Function
	//Quotation

	public function add_quotations()
	{
		$user = $this->session->userdata('user_id');
		if (!has_access($user, 'Sales/list_quotations', 'A')) {
			$data['title'] = 'Access Denied';
			$data['main_content'] = 'errors/access_control.php';
		} else {
			$data['title'] = 'Add Quotation';
			$data['enquiry_list'] = $this->Sales_model->get_all_enquiry_list();

			// Check if enquiry_id passed in URL
			$enquiry_id = $this->input->get('enquiry_id');
			$data['selected_enquiry_id'] = $enquiry_id ? $enquiry_id : '';
			 $this->load->model('Hr_model');
    $data['employees'] = $this->Hr_model->get_employee_list();

			$data['main_content'] = 'sales/quotation/add_quotation.php';
		}

		$this->load->view('includes/template', $data);
	}


	public function get_enquiry_details()
	{
		$enquiry_id = $this->input->post('enquiry_id');
		if (empty($enquiry_id)) {
			echo "<div class='alert alert-danger'>Invalid enquiry selected.</div>";
			return;
		}

		// 🔹 Get enquiry basic info
		$enquiry = $this->Sales_model->get_enquiry_by_id($enquiry_id);
		if (empty($enquiry)) {
			echo "<div class='alert alert-warning'>No enquiry details found.</div>";
			return;
		}
		$quotation_code = $this->Sales_model->generate_quotation_code();
		//$quotation_code		 = "QTN-" . '0' . $enquiry['branch_id'] . "-" . date('Ymd');
		// 🔹 Get estimation structure
		$estimation_data = $this->estimation_master_data($enquiry_id);
		$data['master'] = $estimation_data['master'];
		$data['estimation'] = $estimation_data['estimation'];

		// 🔹 Load dropdown reference data
		$data['all_products'] = $this->Item_model->get_active_item_list();     // must return list of products (id + name)
		$data['active_units'] = $this->Item_model->get_all_units();     // must return list of units (id + name)

		// 🔹 Load and return HTML preview
		$html = $this->load->view('sales/quotation/quotation_preview.php', $data, TRUE);
		//print_r($html);exit();sub_total
		$response = [
			'branch_id'        => $enquiry['branch_id'],
			'customer_id'      => $enquiry['customer_id'],
			'enquiry_id'      => $enquiry['enquiry_id'],
			'enquiry_code'    => $enquiry['enquiry_code'],
			'branch_name'     => $enquiry['branch_name'],
			'project_name'    => $enquiry['project_name'],
			'customer_name'   => $enquiry['customer_name'],
			'quotation_code'  => $quotation_code,
			'estimation_id'   => $estimation_data['master']['estimation_id'] ?? 0,
			'estimation_cost' => $estimation_data['master']['grand_total'] ?? 0,
			'sub_total'  	  => $estimation_data['master']['sub_total'] ?? 0,
			'html'      	  => $html,
			'all_products'    => $this->Item_model->get_active_item_list(),
			'active_units'    => $this->Item_model->get_all_units()
		];

		echo json_encode($response);
	}
	public function save_quotation()
	{
		$quotationData = $this->input->post();
		$action = $this->input->post('action'); // detect which button was clicked

		$this->db->trans_begin();

		try {
			// --- Extract and calculate values (your existing code remains same) ---
$enquiry_id = $this->input->post('enquiry_id');

$enquiry = $this->db->get_where('enquiry_master', ['enquiry_id' => $enquiry_id])->row();

if (!$enquiry) {
    throw new Exception('Enquiry not found.');
}

// Use enquiry data for project name/location
// $project_name = $enquiry->project_name ?? '';
$project_location = $enquiry->project_location ?? ''; // assuming you have a project_location column


			$quotation_date = $this->input->post('quotation_date');
			$discount_percent = floatval($this->input->post('qtn_add_discount_percentage')) ?: 0;
			$vat_required  = $this->input->post('qtn_apply_vat') ? 1 : 0;
			$vat_percent   = floatval($this->input->post('qtn_vat_percentage')) ?: 0;
			$other_charges = floatval($this->input->post('other_charges')) ?: 0;

			$project_name        = $this->input->post('project_name_hidden');
			$quotation_customer  = $this->input->post('quotation_customer');
			$quotation_branch_id = $this->input->post('quotation_branch_id');

			if (!$quotation_date) {
				throw new Exception('Quotation Date is required.');
			}

			$subtotal = 0;
			$main_headings = $this->input->post('main_heading');
			$products      = $this->input->post('products');

			if (!empty($main_headings) && !empty($products)) {
				foreach ($main_headings as $i => $main) {
					if (!isset($products[$i])) continue;

					foreach ($products[$i] as $j => $subProducts) {
						foreach ($subProducts as $prod) {
							$qty   = floatval($prod['quantity'] ?? 0);
							$price = floatval($prod['unit_price'] ?? 0);
							$discount = floatval($prod['discount_amount'] ?? 0);

							$amount = $qty * $price;
							if ($discount > $amount) $discount = $amount;

							$taxable = $amount - $discount;
							$subtotal += $taxable;
						}
					}
				}
			}

			// --- Perform Calculations (same as before) ---
			$addDiscountAmount = (($subtotal + $other_charges) * $discount_percent) / 100;
			$totalBeforeVat    = $subtotal + $other_charges - $addDiscountAmount;
			$vatAmount         = $vat_required ? ($totalBeforeVat * $vat_percent / 100) : 0;
			$grandTotal        = $totalBeforeVat + $vatAmount;

			// --- Determine approval requirement ---
			$approval_status = ($grandTotal > 10000) ? 0 : 1;

			// --- Prepare and insert master record ---
			$masterData = [
				'enquiry_id'            => $enquiry_id,
				'estimation_id'         => $this->input->post('estimation_id'),
				'quotation_code'        => $this->input->post('quotation_code'),
				'quotation_date'        => $quotation_date,
				'project_name'          => $project_name,
				 'project_location'      => $project_location,
				'quotation_customer'    => $quotation_customer,
				'quotation_branch_id'   => $quotation_branch_id,
				'sub_total'             => $subtotal,
				'other_charge'          => $other_charges,
				'discount_percentage'   => $discount_percent,
				'discount_amount'       => $addDiscountAmount,
				'total_before_vat'      => $totalBeforeVat,
				'vat_required'          => $vat_required,
				'vat_percentage'        => $vat_percent,
				'vat_amount'            => $vatAmount,
				'grand_total'           => $grandTotal,
				'payment_term'          => $this->input->post('payment_term'),
				'validity'              => $this->input->post('validity'),
				'warranty'              => $this->input->post('warranty'),
				'warranty_description'              => $this->input->post('warranty_description'),

				'delivery_term'         => $this->input->post('delivery_term'),
				'terms_condition'       => $this->input->post('terms_condition'),
				'aproval'              => 0,
				'active'                => 1,
				'prepared_by'       => $this->input->post('employee_prepared'),
				'approved_by'       => $this->input->post('employee_approved'),
				'notes'          => $this->input->post('notes'),

				'created_on'            => date('Y-m-d H:i:s'),
				'created_by'            => $this->session->userdata('user_id'),
			];


			$qtn_id = $this->Sales_model->insert_quotation($masterData);

			// --- Save quotation details ---
			$this->_save_qtn_details($qtn_id);

			// --- Update enquiry status ---
			$this->db->where('enquiry_id', $enquiry_id);
			$this->db->update('enquiry_master', ['enquiry_status' => 6]);

			if ($this->db->trans_status() === FALSE) {
				throw new Exception('Transaction failed.');
			}

			$this->db->trans_commit();
			$this->session->set_flashdata('success', 'Quotation created successfully.');

			// ✅ Redirect based on button pressed
			if ($action === 'sales_order') {
				redirect(base_url('index.php/Sales/add_sales_order?quotation_id=' . $qtn_id));
			} else {
				redirect('Sales/edit_quotation/' . $qtn_id);
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			log_message('error', 'Quotation Insert Failed: ' . $e->getMessage());
			$this->session->set_flashdata('error', 'Something went wrong while creating quotation. ' . $e->getMessage());
			redirect('Sales/add_quotations');
		}
	}

	public function edit_quotation($qtn_id)
	{
		$user                       = $this->session->userdata('user_id');
		if (!has_access($user, 'Sales/list_quotations', 'E')) {
			$data['title']          = 'Access Denied';
			$data['main_content']   = 'errors/access_control.php';
		} else {
			$data['title']          	= 'Edit Quotation';
			$data['Quotation_id']       =  $qtn_id;
			$quotation_details			= $this->get_quotation_master_data($qtn_id);
			$data['qtn_master']			= $quotation_details['qtn_master'];
			$data['qtn_details']        = $quotation_details['quotation'];
			//print_r($data['qtn_details']);exit();
			$data['qtn_revisions']		= $this->Sales_model->get_all_qtn_revisions($qtn_id);
			$data['all_products'] 		= $this->Item_model->get_active_item_list();     // must return list of products (id + name)
			$data['active_units'] 		= $this->Item_model->get_all_units();     // must return list of units (id + name)
            $this->load->model('Hr_model');
            $data['employees'] = $this->Hr_model->get_employee_list();
			// $data['customer_code']  = $this->Company_model->generate_customer_code();
			$data['enquiry_list']    = $this->Sales_model->get_all_enquiry_list();
			$data['main_content']   = 'sales/quotation/edit_quotation.php';
		}

		$this->load->view('includes/template', $data);
	}
	public function update_quotation()
	{
		$action = $this->input->post('action'); // detect which button was clicked
		$this->db->trans_begin();

		try {
			$qtn_id          = $this->input->post('quotation_id');
			$enquiry_id      = $this->input->post('enquiry_id');
			$is_new_revision = $this->input->post('create_revision'); // checkbox

			// --- Basic Validation ---
			$quotation_date = $this->input->post('quotation_date');
			if (!$quotation_date) {
				throw new Exception('Quotation Date is required.');
			}

			// --- Prepare master data ---
			$masterData = [
				'enquiry_id'             => $enquiry_id,
				'estimation_id'          => $this->input->post('estimation_id'),
				'quotation_code'         => $this->input->post('quotation_code'),
				'quotation_date'         => $quotation_date,
				'discount_percentage'    => $this->input->post('qtn_add_discount_percentage') ?: 0,
				'discount_amount'        => $this->input->post('qtn_add_discount_amount') ?: 0,
				'vat_required' => $this->input->post('qtn_apply_vat') ? 1 : 0,
				'vat_percentage'         => $this->input->post('qtn_vat_percentage') ?: 5,
				'payment_term'           => $this->input->post('payment_term'),
				'validity'               => $this->input->post('validity'),
				'delivery_term'          => $this->input->post('delivery_term'),
				'warranty'               => $this->input->post('warranty'),
				'warranty_description'          => $this->input->post('warranty_description'),
				'terms_condition'        => $this->input->post('terms_condition'),
				'notes'        => $this->input->post('notes'),
				'prepared_by' 			=> $this->input->post('employee_prepared'),
				'approved_by' 			=> $this->input->post('employee_approved'),

			];

			// --- Calculate Totals Server-Side ---
			$subtotal = 0;
			$main_headings = $this->input->post('main_heading');
			$sub_headings  = $this->input->post('sub_heading');
			$products      = $this->input->post('products');

			if (!empty($main_headings) && !empty($products)) {
				foreach ($main_headings as $i => $main) {
					foreach ($products[$i] as $j => $subProducts) {
						foreach ($subProducts as $k => $prod) {
							$qty   = floatval($prod['quantity'] ?? 0);
							$price = floatval($prod['unit_price'] ?? 0);
							$discount = floatval($prod['discount_amount'] ?? 0);
							$amount = $qty * $price;
							if ($discount > $amount) $discount = $amount;
							$taxable = $amount - $discount;
							$subtotal += $taxable;
						}
					}
				}
			}

			$addDiscountAmount = ($subtotal * $masterData['discount_percentage']) / 100;
			$totalBeforeVat    = $subtotal - $addDiscountAmount;
			$vatAmount         = $masterData['vat_required'] ? ($totalBeforeVat * $masterData['vat_percentage'] / 100) : 0;
			$grandTotal        = $totalBeforeVat + $vatAmount;

			// Assign calculated totals
			$masterData['sub_total']         = $subtotal;
			$masterData['total_before_discount'] = $subtotal;
			$masterData['discount_amount']   = $addDiscountAmount;
			$masterData['total_before_vat']  = $totalBeforeVat;
			$masterData['vat_amount']        = $vatAmount;
			$masterData['grand_total']       = $grandTotal;

			// Reset approvals always on update
$masterData['aproval'] = 'Pending';

if ($grandTotal >= 10000) {
    $masterData['internal_approval'] = 'Pending';
} else {
    $masterData['internal_approval'] = null; // or 'Not Required'
}

			// --- Handle New Revision ---
			if ($is_new_revision) {

    $max_revision = $this->Sales_model->get_max_revision($enquiry_id);
    $new_revision = $max_revision + 1;

    // Mark old quotation inactive
    $this->Sales_model->update_quotation($qtn_id, [
        'active' => 0,
        'updated_on' => date('Y-m-d H:i:s'),
        'updated_by' => $this->session->userdata('user_id')
    ]);

    // 🔥 GET OLD DATA (FIXED)
    $oldArr = $this->Sales_model->get_quotation_master_by_id($qtn_id);
    $old = $oldArr[0] ?? null;

    if (!$old) {
        throw new Exception("Old quotation not found");
    }

    // 🚨 FORCE REQUIRED FIELDS (PREVENT NULL INSERT)
    $masterData['quotation_branch_id'] = $old['quotation_branch_id'] ?? 0;
    $masterData['quotation_customer']  = $old['quotation_customer'] ?? 0;
    $masterData['project_name']        = $old['project_name'] ?? '';
    $masterData['quotation_type']      = $old['quotation_type'] ?? '';
    $masterData['project_location']    = $old['project_location'] ?? '';

    $masterData['enquiry_id']          = $old['enquiry_id'] ?? 0;
    $masterData['estimation_id']       = $old['estimation_id'] ?? 0;

    // revision info
    $masterData['active']              = 1;
    $masterData['created_on']          = date('Y-m-d H:i:s');
    $masterData['created_by']          = $this->session->userdata('user_id');
    $masterData['quotation_revision']  = $new_revision;

    $new_qtn_id = $this->Sales_model->insert_quotation($masterData);

    $this->_save_qtn_details($new_qtn_id);

    $this->session->set_flashdata('success', 'New quotation revision created successfully.');
    $redirect_qtn_id = $new_qtn_id;
}
			// --- Update Existing Quotation ---
			else {
				$masterData['active']     = 1;
				$masterData['updated_on'] = date('Y-m-d H:i:s');
				$masterData['updated_by'] = $this->session->userdata('user_id');

				// Update master
				$this->Sales_model->update_quotation($qtn_id, $masterData);

				// Delete old details
				$this->Sales_model->delete_qtn_main_headings($qtn_id);
				$this->Sales_model->delete_qtn_sub_headings($qtn_id);
				$this->Sales_model->delete_qtn_products($qtn_id);

				// Insert updated details
				$this->_save_qtn_details($qtn_id);

				$this->session->set_flashdata('success', 'Quotation updated successfully.');
				$redirect_qtn_id = $qtn_id;
			}

			// --- Commit Transaction ---
			if ($this->db->trans_status() === FALSE) {
				throw new Exception('Database transaction failed.');
			}
			$this->db->trans_commit();
		} catch (Exception $e) {
			$this->db->trans_rollback();
			log_message('error', 'Quotation Update Failed: ' . $e->getMessage());
			$this->session->set_flashdata('error', 'Something went wrong while updating quotation. ' . $e->getMessage());
		}

		if ($action === 'sales_order') {
			redirect(base_url('index.php/Sales/add_sales_order?quotation_id=' . $redirect_qtn_id));
		} else {
			redirect('Sales/edit_quotation/' . $redirect_qtn_id);
		}
	}
	public function approve_quotation($quotation_id)
	{
		//echo $quotation_id;exit();
		$data['title']			=  "Approve Quotation";
		$data['quotation'] = $this->Sales_model->get_quotation_master_by_id($quotation_id);
		if (!$data['quotation']) {
			show_error('Quotation not found');
		}

		$data['main_content']   = 'sales/quotation/approve_quotation.php';
		$this->load->view('includes/template', $data);
	}
	public function approve_quotation_without_LPO()
	{
		//echo "here";exit();
		$quotation_id = $this->uri->segment(3);

		if (!$quotation_id) {
			$this->session->set_flashdata('error', 'Invalid quotation');
			redirect('Sales/quotation_list');
			return;
		}

		/* ================= GET ENQUIRY ID ================= */
		$quotation =  $this->Sales_model->get_quotation_master_by_id($quotation_id);

		if (!$quotation) {
			$this->session->set_flashdata('error', 'Quotation not found');
			redirect('Sales/quotation_list');
			return;
		}
		$enquiry_id = $quotation[0]['enquiry_id'];
		/* ================= START TRANSACTION ================= */
		$this->db->trans_start();

		/* RESET OTHER QUOTATIONS */
		$this->Sales_model->reset_quotation_approval_by_enquiry($enquiry_id);
		/* APPROVE CURRENT QUOTATION */
		$this->Sales_model->approve_quotation($quotation_id, $this->session->userdata('user_id'), 'without LPO');

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Quotation approval failed');
			redirect('Sales/edit_quotation/' . $quotation_id);
			return;
		}

		$this->session->set_flashdata('success', 'Quotation approved successfully');
		redirect('Sales/edit_quotation/' . $quotation_id);
	}


	//////////////////////////////////////////////////
	public function save_quotation_approval()
	{
		$quotation_id = $this->input->post('quotation_id');

		if (!$quotation_id) {
			$this->session->set_flashdata('error', 'Invalid quotation');
			redirect('Sales/quotation_list');
			return;
		}

		/* ===== GET ENQUIRY ID ===== */
		/* ================= GET ENQUIRY ID ================= */
		$quotation =  $this->Sales_model->get_quotation_master_by_id($quotation_id);

		if (!$quotation) {
			$this->session->set_flashdata('error', 'Quotation not found');
			redirect('Sales/quotation_list');
			return;
		}

		$enquiry_id =  $quotation[0]['enquiry_id'];

		/* ===== PREPARE UPDATE DATA ===== */
		$updateData = [
			'aproval'          => 1,
			'approved_by'      => $this->session->userdata('user_id'),
			'approved_on'      => date('Y-m-d H:i:s'),
			'lpo_date'         => $this->input->post('lpo_date'),
			'lpo_number'       => $this->input->post('lpo_number'),
			'lpo_total'        => $this->input->post('lpo_total'),
			'approval_remarks' => $this->input->post('approval_remarks')
		];

		/* ===== START TRANSACTION ===== */
		$this->db->trans_start();

		/* RESET OTHER QUOTATIONS UNDER SAME ENQUIRY */
		$this->Sales_model->reset_quotation_approval_by_enquiry($enquiry_id);

		/* APPROVE CURRENT QUOTATION WITH LPO */
		$this->Sales_model->approve_quotation_with_lpo($quotation_id, $updateData);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			$this->session->set_flashdata('error', 'Quotation approval failed');
			redirect('Sales/edit_quotation/' . $quotation_id);
			return;
		}

		$this->session->set_flashdata('success', 'Quotation approved successfully');
		redirect('Sales/edit_quotation/' . $quotation_id);
	}
	private function _save_qtn_details_old($qtn_id)
	{
		echo "here";
		exit();
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
							'main_heading_id' => $main_heading_id,
							'qtn_id'          => $qtn_id,
							'subheading'      => $sub_heading
						];
						$sub_id = $this->Sales_model->insert_subheading($subData);

						if (isset($_POST['products'][$i][$j])) {
							foreach ($_POST['products'][$i][$j] as $prod) {
								$prodData = [
									'qtn_id'          => $qtn_id,
									'sub_heading_id'  => $sub_id,
									'prd_id'          => $prod['product_id'],
									'unit_id'         => $prod['unit'],
									'unit_price'      => $prod['unit_price'],
									'qty'             => $prod['quantity'],
									'amount'          => $prod['amount'],
									'discount_amount'  => $prod['qtn_discount_amount'],
									'discount_percent'  => $prod['qtn_discount_percent'],
									'taxable_amount'  => $prod['taxable_amount']
								];
								$this->Sales_model->insert_product($prodData);
							}
						}
					}
				}
			}
		}
	}
	public function get_quotation_master_data($enquiry_id, $status = "")
	{

		if (!empty($status)) {
			$rows = $this->Sales_model->get_quotation_full_details($enquiry_id, $status);
		} else {
			$rows = $this->Sales_model->get_quotation_full_details($enquiry_id);
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
					'quotation_type'      	=> $row['quotation_type'],

					'enquiry_id' 			=> $row['enquiry_id'],
					'enquiry_code'        	=> $row['enquiry_code'],
					'project_name'			=> $row['project_name'],
					'branch_name'			=> $row['branch_name'],
					'customer_name'			=> $row['customer_name'],

					'estimation_id'       	=> $row['est_id'],
					'estimation_code'       => $row['estimation_code'],
					'estimation_amount'     => $row['estimation_amount'],


					'sub_total'     		=> $row['sub_total'],
					'other_charge'     		=> $row['other_charge'],
					'discount_percentage'   => $row['master_discount_percentage'],
					'discount_amount'       => $row['master_discount_amount'],
					'vat_required'    		=> $row['vat_required'],
					'total_before_vat'     	=> $row['total_before_vat'],
					'vat_percentage'    	=> $row['vat_percentage'],
					'vat_amount'     		=> $row['vat_amount'],
					'grand_total'     		=> $row['grand_total'],

					'payment_term'         	=> $row['payment_term'],
					'terms_condition'       => $row['terms_condition'],
					'validity'        		=> $row['validity'],
					'delivery_term'        	=> $row['delivery_term'],
					'notes'        	=> $row['notes'],

					'warranty'        		=> $row['warranty'],
					'warranty_description'  => $row['warranty_description'],
					'prepared_by'        	=> $row['prepared_by'],
					'approved_by'        	=> $row['approved_by'],

					'created_by'        	=> $row['created_by'],

					'quotation_revision'    => $row['quotation_revision'],
					'aproval'				=> $row['aproval'],
					'internal_approval' => $row['internal_approval']
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
						'discount_percent'   	=> $row['discount_percent'],
						'discount_amount'       => $row['discount_amount'],
						'taxable_amount'        => $row['taxable_amount'],
						'warranty'             => $row['warranty'] ?? '' 
					];
				}
			}
		}
		return $data;
	}
	public function Resurvey_from_qtn($quotation_id)
	{


		//$quote_id       = $this->input->post('quotation_id');
		$enquiry_id     = $this->input->post('enquiry_id_res');
		$estimation_id  = $this->input->post('estimation_id_res');
		$created_by     =  $this->session->userdata('user_id');

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
			're_survey_status' => 1,
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
			redirect('CRM/view_enquiry/' . $enquiry_id); // or 'CRM/your_target_method'
		} else {
			$this->session->set_flashdata('error', 'One or more updates failed.');
			redirect('Sales/quotation_list'); // or another page
		}
	}



	//-------------Sales Order
	public function list_sales_orders()
	{
		$data['title']				 = 'Sales Order List';
		$data['active_sales_orders'] = $this->Sales_order_model->list_all_sales_order();
		// echo $this->db->last_query();exit();
		$data['main_content']		 = 'sales/sales_order/list_sales_orders.php';
		$this->load->view('includes/template', $data);
	}
public function add_sales_order($quotation_id = null)
{
    $user = $this->session->userdata('user_id');

    if (!has_access($user, 'Sales/list_sales_orders', 'A')) {
        $data['title'] = 'Access Denied';
        $data['main_content'] = 'errors/access_control.php';
    } else {
        $data['title'] = 'Add Sales Order';
        $data['approved_quotations'] = $this->Sales_model->get_all_aproved_quotations();
        $data['so_code'] = $this->Sales_model->generate_so_code();

        if ($quotation_id) {
            $data['selected_quotation'] = $quotation_id;

            // Fetch full quotation data
            $data['quotation_master'] = $this->Sales_model->get_quotation_master_by_id($quotation_id);
            $data['quotation_products'] = $this->Sales_model->get_quotation_products_by_id($quotation_id);
        }

        $data['main_content'] = 'sales/sales_order/add_sales_order.php';
    }

    $this->load->view('includes/template', $data);
}
	public function get_quotation_details()
	{
		$qtn_id = $this->input->post('qtn_id', true);
		if (empty($qtn_id)) {
			echo json_encode([
				'status'  => false,
				'message' => 'No quotation selected.'
			]);
			return;
		}
		// Get quotation master details
		$quotation = $this->Sales_model->get_quotation_details_by_id($qtn_id);

$quotation['delivery_term'] = strip_tags($quotation['delivery_term'] ?? '');
$quotation['terms_condition'] = strip_tags($quotation['terms_condition'] ?? '');
		if (empty($quotation)) {
			echo json_encode([
				'status'  => false,
				'message' => 'Quotation not found.'
			]);
			return;
		}

		// Fetch products with available quantities
		$enquiry_id = $quotation['enquiry_id'] ?? null;
		$products_with_avail = $this->Sales_order_model->get_available_quantities($enquiry_id, $qtn_id);

		// Load partial view for product table
		$table_html = $this->load->view(
			'sales/sales_order/sales_order_products.php',
			['qtn_products' => $products_with_avail],
			true
		);
		// Final response
		echo json_encode([
			'status'     => true,
			'quotation'  => $quotation,
			'table_html' => $table_html
		]);
	}
	public function save_sales_order()
	{
		//print_r($this->input->post());exit();
		if ($this->input->post()) {
			$created_by = $this->session->userdata('user_id');
			$created_on = date('Y-m-d H:i:s');
			$enquiry_id = $this->input->post('enquiry_id');
			$qtn_id     = $this->input->post('quotation_id');

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
				'billing_customer_address' => $this->input->post('billing_address'),
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
			$action = $this->input->post('action');

			// Complete Transaction
			$this->db->trans_complete();

			// Check transaction status
			if ($this->db->trans_status() === FALSE) {
				// Rollback occurred
				$this->session->set_flashdata('error', 'Failed to save Sales Order, please try again.');
				redirect('Sales/list_sales_orders/');
			} else {
				// Success
				$this->session->set_flashdata('success', 'Sales Order saved successfully!');

				if ($action == 'delivery_challan') {
					// Redirect to add delivery challan page with the new SO ID
					redirect('Sales/add_delivery_challan?so_id=' . $so_id);
				} else {
					// Just redirect to Sales Order list
					redirect('Sales/view_sales_order/' . $so_id);
				}
			}
		} else {
			show_error('Invalid request');
		}
	}
	public function edit_sales_order($so_id)
	{
		$user = $this->session->userdata('user_id');
		if (!has_access($user, 'Sales/list_quotations', 'E')) {
			$data['title'] = 'Access Denied';
			$data['main_content'] = 'errors/access_control.php';
		} else {
			$data['title'] = 'Edit Sales Order';

			// ✅ Fetch all data
			$data['sales_order_master']   = $this->Sales_order_model->get_sales_order_master($so_id);
			//echo $this->db->last_query();exit();
			$data['so_products'] 		  = $this->Sales_order_model->get_so_products($so_id);
			$data['sales_order_address']  = $this->Sales_order_model->get_so_address($so_id);
			$data['all_products']         = $this->Item_model->get_active_item_list();
			$data['active_units']         = $this->Item_model->get_all_units();

			// ✅ View file
			$data['main_content'] = 'sales/sales_order/edit_sales_order';
		}

		$this->load->view('includes/template', $data);
	}

	public function update_sales_order()
	{

		if ($this->input->post()) {
			$updated_by = $this->session->userdata('user_id');
			$updated_on = date('Y-m-d H:i:s');

			$so_id      = $this->input->post('so_id'); // hidden field
			$enquiry_id = $this->input->post('enquiry_id');
			$qtn_id     = $this->input->post('quotation_id');

			// Start Transaction
			$this->db->trans_start();

			// --- Master Data ---
			$master_data = [
				//'so_code'             => $this->input->post('so_edit_code'),
				//'enquiry_id'          => $enquiry_id,
				//'estimation_id'       => $this->input->post('estimation_id'),
				//'qtn_id'              => $qtn_id,
				'so_date'             => $this->input->post('so_date'),
				'validity'            => $this->input->post('so_validity'),
				'payment_term'        => $this->input->post('so_payment_term'),
				'delivery_term'       => $this->input->post('so_delivery_term'),
				'terms_and_condition' => $this->input->post('so_terms_condition'),
				'remarks'             => $this->input->post('so_remarks'),
				//'sub_total'           => $this->input->post('so_edit_subtotal'),
				//'discount_percentage' => $this->input->post('so_edit_add_discount_percentage'),
				//'discount_amount'     => $this->input->post('so_edit_add_discount_amount'),
				// 'total_before_vat'    => $this->input->post('so_edit_totalbefore_vat_amount'),
				// 'vat_required'        => 1,
				// 'vat_percentage'      => $this->input->post('so_edit_vat_percentage'),
				// 'vat_amount'          => $this->input->post('so_edit_vat_amount'),
				// 'grand_total'         => $this->input->post('so_edit_grand_total'),
				'updated_on'          => $updated_on,
				'updated_by'          => $updated_by,
			];

			$this->Sales_order_model->update_sales_order_master($so_id, $master_data);

			// --- Products ---
			// Clear existing products and re-insert (simpler than partial updates)
			// $this->Sales_order_model->delete_sales_order_products($so_id);

			// if (!empty($_POST['so_edit_qty'])) {
			// 	$products_batch = [];

			// 	foreach ($this->input->post('so_edit_qty') as $k => $qty) {
			// 		$products_batch[] = [
			// 			'so_id'           => $so_id,
			// 			'product_id'      => $this->input->post('product_id')[$k],
			// 			'unit_id'         => $this->input->post('unit_id')[$k],
			// 			'quantity'        => $qty,
			// 			'unit_price'      => str_replace(',', '', $this->input->post('so_edit_unitp')[$k]),
			// 			'amount'          => str_replace(',', '', $this->input->post('so_edit_amount')[$k]),
			// 			'discount_amount' => str_replace(',', '', $this->input->post('so_edit_discount')[$k]),
			// 			'taxable_amount'  => str_replace(',', '', $this->input->post('so_edit_taxable')[$k]),
			// 		];
			// 	}

			// 	if (!empty($products_batch)) {
			// 		$this->Sales_order_model->insert_sales_order_products_batch($products_batch);
			// 	}
			// }

			// --- Address ---
			$address_data = [
				'billing_customer_name'    => $this->input->post('billing_name'),
				'billing_customer_address' => $this->input->post('billing_address'),
				'billing_emirates'         => $this->input->post('billing_city'),
				'billing_contact'          => $this->input->post('billing_phone'),
				'billing_email'            => $this->input->post('billing_email'),

				'shipping_customer'        => $this->input->post('shipping_name'),
				'shipping_address'         => $this->input->post('shipping_address'),
				'shipping_emirate'         => $this->input->post('shipping_city'),
				'shipping_contact'         => $this->input->post('shipping_phone'),
				'shipping_email'           => $this->input->post('shipping_email'),
				'updated_on'               => $updated_on
			];

			$this->Sales_order_model->update_sales_order_address($so_id, $address_data);

			// Complete Transaction
			$this->db->trans_complete();

			// Check transaction status
			if ($this->db->trans_status() === FALSE) {
				$this->session->set_flashdata('error', 'Failed to update Sales Order, please try again.');
				redirect('Sales/edit_sales_order/' . $so_id);
			} else {
				$this->session->set_flashdata('success', 'Sales Order updated successfully!');
				redirect('Sales/edit_sales_order/' . $so_id);
			}
		} else {
			show_error('Invalid request');
		}
	}
	// public function view_sales_order($so_id)
	// {
	// 	$this->load->model('Sales_model');
	// 	$data['title'] = 'View Sales Order';
	// 	$data['so'] = $this->Sales_order_model->get_sales_order_master_details($so_id);
	// 	$data['so_products'] = $this->Sales_order_model->get_so_products($so_id);
	// 	// ---- CHECK IF PROJECT ALREADY CREATED ----
	// 	$data['project_created'] = $this->Project_model->is_project_created_for_so($so_id);

	// 	//print_r($data);exit();

	// 	if (empty($data['so'])) {
	// 		show_404();
	// 	}
	// 	$data['main_content'] = 'sales/sales_order/sales_order_view';
	// 	$this->load->view('includes/template', $data);
	// }

	public function view_sales_order($so_id = null)
{
    if (!$so_id) {
        show_404(); // or redirect somewhere safe
    }

    $this->load->model('Sales_model');
    $data['title'] = 'View Sales Order';
    $data['so'] = $this->Sales_order_model->get_sales_order_master_details($so_id);
    $data['so_products'] = $this->Sales_order_model->get_so_products($so_id);
    $data['project_created'] = $this->Project_model->is_project_created_for_so($so_id);

    if (empty($data['so'])) {
        show_404();
    }
    $data['main_content'] = 'sales/sales_order/sales_order_view';
    $this->load->view('includes/template', $data);
}


	//--------------DeliveryChallan
	public function list_delivery_challan()
	{
		$data['title'] = 'Delivery Note List';
		$data['active_delivery_challan'] = $this->Sales_order_model->list_all_delivery_challan();
		$data['main_content'] = 'sales/delivery_challan/list_delivery_notes.php';
		$this->load->view('includes/template', $data);
	}
	public function add_delivery_challan()
	{
		$user = $this->session->userdata('user_id');

		// Access control check
		if (!has_access($user, 'Sales/list_sales_orders', 'A')) {
			$data['title'] = 'Access Denied';
			$data['main_content'] = 'errors/access_control.php';
		} else {
			$data['title'] = 'Add Delivery Note';

			// Get last delivery code
			$last_code = $this->Sales_order_model->get_last_delivery_code();

			if ($last_code) {
				// Extract numeric part assuming prefix 'DN'
				$number = (int) substr($last_code, 2);
				$number++; // Increment for new code
			} else {
				$number = 1; // First delivery
			}

			// Generate new delivery code with prefix 'DN'
			$data['delivery_code'] = 'DN' . str_pad($number, 5, '0', STR_PAD_LEFT);

			// Fetch list of sales orders for selection
			$data['sales_order_list'] = $this->Sales_order_model->get_sales_order_list_data();
			$so_id = $this->input->get('so_id');
			if ($so_id) {
				$data['selected_so'] = $so_id;
				$data['sales_order'] = $this->Sales_order_model->get_sales_order_by_id($so_id);

			}

			// Load add delivery challan view
			$data['main_content'] = 'sales/delivery_challan/add_delivery_challan.php';
		}

		// Load template view
		$this->load->view('includes/template', $data);
	}

	public function get_sales_order_partial_del()
	{
		$so_id  = $this->input->post('so_id');

		if (!$so_id) {
			echo '';
			return;
		}

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


		// ✅ Render product table
		$html = $this->load->view('sales/include/del_product_table.php', $data, true);
		if ($source == "delivery") {
			echo json_encode([
				'products_html' => $html,
				'so_date'       => $so_date,
				'so_master'     => $data['so_master'],
				'source'        => $source
			]);
		} else {
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
		try {
			$so_id = $this->input->post('sales_order_del');

			$delivery_master = [
				'delivery_code' => $this->input->post('delivery_code'),
				'so_id' => $so_id,
				'delivery_mode' => $this->input->post('delivery_mode'),
				'deliverd_by' => $this->input->post('deliverd_by'),
				'item_issued_by' => $this->session->userdata('user_id'),
				'delivery_date' => $this->input->post('dc_date'),
				 'shipping_address' => $this->input->post('del_shipping_address'),
    'shipping_city'    => $this->input->post('del_shipping_city'),
    'contact'          => $this->input->post('del_shipping_contact'),
    'email'            => $this->input->post('del_shipping_email'),
    'remark'           => $this->input->post('del_remark'),
				'created_by' => $this->session->userdata('user_id'),
				'created_on' => date('Y-m-d H:i:s')
			];
			$this->db->insert('delivery_master', $delivery_master);
			$del_master_id = $this->db->insert_id();

			$products = $this->input->post('product_id');
			$quantities = $this->input->post('quantity');
			if (!empty($products)) {
				$insert_data = [];
				foreach ($products as $i => $pid) {
					$qty = $quantities[$i] ?? 0;
					if ($pid && $qty > 0) {
						$insert_data[] = [
							'del_master_id' => $del_master_id,
							'product_id' => $pid,
							'quantity' => $qty,
							'created_on' => date('Y-m-d H:i:s')
						];
					}
				}
				if (!empty($insert_data)) $this->db->insert_batch('delivery_products', $insert_data);
			}

			$this->Sales_order_model->update_sales_order_master($so_id, [
				'delivery_status' => 1,
				'updated_on' => date('Y-m-d H:i:s'),
				'updated_by' => $this->session->userdata('user_id')
			]);

			$action = $this->input->post('action');

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', 'Failed to save delivery challan');
				redirect('Sales/list_delivery_challan/');
			} else {
				$this->db->trans_commit();
				$this->session->set_flashdata('success', 'Delivery challan saved successfully');
				if ($action == 'create_invoice') {
					redirect('Sales/add_invoice?del_id=' . $del_master_id);
				} else {
					redirect('Sales/list_delivery_challan/');
				}
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			$this->session->set_flashdata('error', $e->getMessage());
			redirect('Sales/list_delivery_challan/');
		}
	}

	//---------------Invoice
	public function add_invoice()
	{
		$user                       = $this->session->userdata('user_id');
		if (!has_access($user, 'Sales/list_sales_orders', 'A')) {
			$data['title']          = 'Access Denied';
			$data['main_content']   = 'errors/access_control.php';
		} else {
			$data['title']          	 = 'Create Invoice';
			$data['delivery_challan_list'] = $this->Sales_order_model->get_delivery_challan_list_data();
			$data['sundry_accounts1']   	= $this->Accounts_model->get_gen_ledger_detors_records();
			$data['sundry_accounts2']   	= $this->Accounts_model->get_general_ledger_by_group('Sales Accounts');
			$data['sundry_accounts3']   	= $this->Accounts_model->get_all_general_ledger_accounts();
			//$data['enquiry_customer_id']    = $this->Accounts_model->get_cust_account_Id($customer_id);

			$del_id = $this->input->get('del_id');
			if ($del_id) {
				$data['delivery_challan_selected'] = $del_id;
			}
			$this->load->model('Hr_model');
			$data['employees'] = $this->Hr_model->get_employee_list();
			$data['main_content']   	 = 'sales/invoices/add_invoice.php';
		}

		$this->load->view('includes/template', $data);
	}

public function get_delivery_challan_invoice()
{
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    $del_id = $this->input->post('del_id');

    if (empty($del_id)) {
        echo json_encode(['error' => 'Delivery ID missing']);
        return;
    }

    // =======================
    // Delivery Master
    // =======================
    $del_master = $this->Sales_order_model->get_delivery_master($del_id);

    if (empty($del_master)) {
        echo json_encode(['error' => 'Invalid Delivery Challan']);
        return;
    }

    // =======================
    // Sales Order
    // =======================
    $so_id = $del_master['so_id'] ?? 0;

    $so_master  = $this->Sales_order_model->get_sales_order_master($so_id) ?? [];
    $so_address = $this->Sales_order_model->get_so_address($so_id) ?? [];

    // =======================
    // Quotation (SAFE)
    // =======================
    $qtn_id = $del_master['quotation_id'] ?? 0;

    $quotation_data = [];
    $qtn_products   = [];

    if (!empty($qtn_id)) {
        $quotation_data = $this->Sales_model->get_quotation($qtn_id) ?? [];
        $qtn_products   = $this->Sales_model->get_quotation_products_by_id($qtn_id) ?? [];
    }

    // =======================
    // CUSTOMER FIX (IMPORTANT FIX HERE)
    // =======================
    $enquiry_id = $del_master['enquiry_id'] ?? 0;

    if (!empty($enquiry_id)) {
        $enquiry_data = $this->Sales_model->get_enquiry_by_id($enquiry_id) ?? [];
    } else {
        // direct quotation fallback (SAFE KEYS ONLY)
        $enquiry_data = [
            'customer_name'  => $quotation_data['customer_name'] ?? '',
            'customer_TR_no' => $quotation_data['customer_TR_no'] ?? '',
            'branch_id'      => $quotation_data['branch_id'] ?? 0,
            'branch_name'    => $quotation_data['branch_name'] ?? '',
            'customer_email' => $quotation_data['customer_email'] ?? '',
            'contact_number' => $quotation_data['contact_number'] ?? ''
        ];
    }

  // =======================
// BANK DETAILS (FINAL FIX)
// =======================

$branch_id = $quotation_data['quotation_branch_id']
          ?? ($enquiry_data['branch_id'] ?? 0);

$branch_data = [
    'branch_bank' => $this->Company_model->get_branch_bank_by_id($branch_id) ?? []
];

$html_branch = $this->load->view(
    'sales/include/branch_bank_table_invoice.php',
    $branch_data,
    true
);

    // =======================
    // INVOICE CODE
    // =======================
    $invoice_code = $this->Sales_model->generate_invoice_code();

    // =======================
    // PRODUCTS
    // =======================
    $so_result = $this->Sales_order_model->get_edit_so_quantities(
        $so_id,
        $qtn_id
    );

    $products = $so_result['products'] ?? [];



    // =======================
    // HTML
    // =======================
    $data = [
        'so_master'      => $so_master,
        'so_address'     => $so_address,
        'quotation_data' => $quotation_data,
        'qtn_products'   => $qtn_products,
         'so_products'    => $products,

    // 🔥 FIX for old view dependency
    'so_p'           => $products,
        'can_create_dc'  => $so_result['can_create_dc'] ?? false,
        'del_master'     => $del_master,
        'enquiry_data'   => $enquiry_data
    ];

    $html = $this->load->view(
        'sales/include/delivery_products_table_invoice.php',
        $data,
        true
    );

    // =======================
    // RESPONSE
    // =======================
    echo json_encode([
        'products_html'  => $html,
        'bank_table'     => $html_branch,
        'del_master'     => $del_master,
        'qtn_customer'   => $this->get_cust_accountId_from_quote($qtn_id),
        'so_master'      => $so_master,
        'so_address'     => $so_address,
        'invoice_code'   => $invoice_code,
        'enquiry_master' => $enquiry_data,
        'qtn_master'     => $quotation_data,
        'qtn_products'   => $qtn_products
    ]);
}

	function get_cust_accountId_from_quote($qtn_id)
{
    $this->load->model('Sales_model');
    $this->load->model('Accounts_model');

    $records = $this->Sales_model->get_all_quotation_details($qtn_id);

    $customer_id = 0;

    if (!empty($records)) {
        foreach ($records as $v) {

            // 🔥 SAFE fallback logic
            $customer_id = $v['enquiry_customer']
                ?? $v['quotation_customer']
                ?? 0;

            break; // only need first record
        }
    }

    if (empty($customer_id)) {
        return null;
    }

    $accountId = $this->Accounts_model->get_cust_account_Id($customer_id);

    return $accountId;
}
	public function create_invoice()
	{
		$this->db->trans_begin();

		try {
			// --- 1. Collect master data ---
			$data_master = [
				'invoice_code'              => $this->input->post('invoice_code'),
				'invoice_date'              => date('Y-m-d', strtotime($this->input->post('invoice_date'))),
				'delivery_id'               => $this->input->post('delivery_challan_id'),
				'so_id'                     => $this->input->post('so_id_inv'),
				'quotation_id'              => $this->input->post('quotation_id_inv'),
				'enquiry_id'                => $this->input->post('enquiry_id_inv'),
				'branch_id'                 => $this->input->post('branch_id_inv'),
				'supplier_ref'        => $this->input->post('supplier_ref'),
	         'other_reference'     => $this->input->post('other_reference'),
	         'buyers_order_no'     => $this->input->post('buyers_order_no'),
	          'buyers_order_date'   => $this->input->post('buyers_order_date'),
				'sub_total'                 => $this->input->post('inv_sub_total'),
				'discount_percentage'       => $this->input->post('inv_discount_per'),
				'discount_amount'           => $this->input->post('inv_discount_amt'),
				'vat_percentage'            => $this->input->post('inv_vat_per'),
				'vat_amount'                => $this->input->post('inv_vat_amount'),
				'grand_total'               => $this->input->post('inv_grand_total'),
				'payment_mode'              => $this->input->post('inv_payment_mode'),
				'advance_amount_percentage' => $this->input->post('inv_advance_per'),
				'advance_amount'            => $this->input->post('inv_advance_amt'),
				'retention_amount'            => $this->input->post('inv_retention_amt'),
				'balance_amount'            => $this->input->post('inv_balance_amt'),
				'validity'                  => $this->input->post('inv_validity'),
				'delivery_term'             => $this->input->post('inv_delivery_terms'),
				'payment_term'              => $this->input->post('inv_payment_terms'),
				'terms_and_condition'       => $this->input->post('inv_general_terms'),
				'remark'                    => $this->input->post('inv_remarks'),
				'bank_id'                   => $this->input->post('selected_bank'),
				'prepared_by'               => $this->input->post('employee_prepared'),
				'received_by'               => $this->input->post('employee_received'),
				'created_by'                => $this->session->userdata('user_id'),
				'created_at'                => date('Y-m-d H:i:s'),
			];
			// --- 2. Insert master ---
			$invoice_id = $this->Sales_order_model->insert_invoice_master($data_master);

			// --- 3. Collect products ---
			$products = [];
			$stock_entries = [];
			$current_date = date('Y-m-d H:i:s');
			$current_year = date('Y');
			$created_by = $this->session->userdata('user_id');

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
					// Invoice product entry
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
						'created_on'          => $current_date,
					];

					// Stock OUT entry
					$stock_entries[] = [
						'stock_type'    => 'OUT',
						'trans_id'      => $invoice_id,
						'stock_date'    => $current_date,
						'year'          => $current_year,
						'product_id'    => $pid,
						'item_desc'     => $product_descs[$i],
						'unit_id'       => $unit_ids[$i],
						'quantity'      => $delivered_qtys[$i],
						'packing'       => '', // optional, if you have packing field
						'price'         => $unit_prices[$i],
						'stock_value'   => $delivered_qtys[$i] * $unit_prices[$i],
						'remark'        => 'Invoice',
						'created_by'    => $created_by,
						'created_date'  => $current_date,
						'status'        => 1,
						'invoice_id'    => $invoice_id,
						//'stock_details' => 'Issued through Invoice #' . $invoice_id,
					];
				}

				if (!empty($products)) {
					$this->Sales_order_model->insert_invoice_products($products);
				}

				// 🔹 Insert stock OUT entries
				if (!empty($stock_entries)) {
					$this->Sales_model->insert_stock_details($stock_entries);
				}
			}

			// --- 4. Accounts entry ---
			for ($i = 0; $i < count($_POST['inv_creditor']); $i++) {
				$creditor = $_POST['inv_creditor'][$i];
				$cr_amount = $_POST['inv_cr_amount'][$i];
				if ($cr_amount > 0) {
					$data = array(
						'voucher_code' 		=> $this->input->post('invoice_code'),
						'voucher_date' 		=> date('Y-m-d H:i:s'),
						'voucher_type' 		=> 'S',
						'customer_id' 		=> $this->input->post('customer_id'),
						'account_id' 		=> $creditor,
						'amount' 			=> $cr_amount,
						'drcr_type' 		=> 'Cr',
						'trans_id' 			=> $invoice_id,
						'trans_type' 		=> 'S',
						'recordCreatedBy'   => $this->session->userdata('user_id')
					);
					$this->db->insert('voucher_transaction', $data);
				}
			}

			// --- 5. Update delivery challan invoice_status ---
			$delivery_id = $this->input->post('delivery_challan_id_inv');
			if ($delivery_id) {
				$this->db->where('del_id', $delivery_id);
				$this->db->update('delivery_master', ['invoice_status' => 1]);
			}

			// --- 6. Commit or Rollback ---
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

		redirect('Sales/list_invoices/');
	}
	public function list_direct_invoice()
	{
		$data['invoice_code']   = 'DIR-' . str_pad(1, 4, '0', STR_PAD_LEFT);
		$data['title']		    = 'Direct Invoice';
		$data['branch_list']    = $this->Company_model->get_all_branches();
		$data['products']		=  $this->Item_model->get_all_item_list();
		$data['active_units']	=  $this->Item_model->get_all_units();
		$data['main_content']   = 'sales/invoices/direct_invoice.php';
		$this->load->view('includes/template', $data);
	}
	public function direct_invoice()
	{
		$data['invoice_code']   = $this->Sales_model->generate_invoice_code("DIR");
		$data['title']		    = 'Direct Invoice';
		$data['branch_list']    = $this->Company_model->get_all_branches();
		$data['products']		=  $this->Item_model->get_all_item_list();
		$data['active_units']	=  $this->Item_model->get_all_units();
		//Accounts 
		$data['sundry_accounts1']   	= $this->Accounts_model->get_gen_ledger_detors_records();
		$data['sundry_accounts2']   	= $this->Accounts_model->get_general_ledger_by_group('Sales Accounts');
		$data['sundry_accounts3']   	= $this->Accounts_model->get_all_general_ledger_accounts();
		$this->load->model('Hr_model');
		$data['employees'] = $this->Hr_model->get_employee_list();
		$data['main_content']   = 'sales/invoices/direct_invoice.php';
		$this->load->view('includes/template', $data);
	}
	public function save_direct_invoice()
	{
		// $this->load->model('Invoice_model');

		// 1️⃣ Prepare invoice master data
		$masterData = [
			'invoice_code'        => $this->input->post('invoice_code'),
			'invoice_type'        => 'direct',
			'invoice_date'        => $this->input->post('invoice_date'),
			'branch_id'           => $this->input->post('branch_id'),
			'invoice_customer'    => $this->input->post('customer_id'),
			'supplier_ref'        => $this->input->post('supplier_ref'),
	         'other_reference'     => $this->input->post('other_reference'),
	         'buyers_order_no'     => $this->input->post('buyers_order_no'),
	          'buyers_order_date'   => $this->input->post('buyers_order_date'),
			'sub_total'           => $this->input->post('sub_total'),
			'discount_percentage' => $this->input->post('add_discount_per'),
			'discount_amount'     => $this->input->post('add_discount_amt'),
			'advance_amount_percentage' => $this->input->post('inv_advance_per'),
				'advance_amount'            => $this->input->post('inv_advance_amt'),
				'retention_amount'            => $this->input->post('inv_retention_amt'),
			'total_beforevat'     => $this->input->post('total_before_vat'),
			'vat_percentage'      => $this->input->post('vat_per'),
			'vat_amount'          => $this->input->post('vat_amount'),
			'grand_total'         => $this->input->post('grand_total'),
			'remark'              => $this->input->post('remarks'),
			'bank_id'             => $this->input->post('selected_bank'),
			'invoice_status'      => 'Draft',
			'sales_return'        => 0,
			'prepared_by'         => $this->input->post('employee_prepared'),
			'received_by'         => $this->input->post('employee_received'),			
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

		for ($i = 0; $i < count($_POST['inv_creditor']); $i++) {
			$creditor = $_POST['inv_creditor'][$i];
			$cr_amount = $_POST['inv_cr_amount'][$i];
			if ($cr_amount > 0) {
				$data = array(
					'voucher_code' 		=> $this->input->post('invoice_code'),
					'voucher_date' 		=> date('Y-m-d H:i:s'),
					'voucher_type' 		=> 'S',
					'customer_id' 		=> $this->input->post('customer_id'),
					'account_id' 		=> $creditor,
					'amount' 			=> $cr_amount,
					'drcr_type' 		=> 'Cr',
					'trans_id' 			=> $invoice_id,
					'trans_type' 		=> 'S',
					'recordCreatedBy'   => $this->session->userdata('user_id')
				);
				$this->db->insert('voucher_transaction', $data);
			}
		}

		// 5️⃣ Redirect
		$this->session->set_flashdata('success', 'Direct Invoice Created Successfully');
		redirect('Sales/list_invoices');
	}
	public function cancel_invoice()
	{

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
					'remark'       => 'Invoice Cancelled - ' . $invoice['invoice_code'],
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
	public function sales_return($invoice_id)
	{
		$data['title']   			= "Sales Return";
		$data['invoice_id']			= $invoice_id;
		// Get invoice header
		$data['invoice']  			= $this->Sales_model->get_invoice_complete_details($invoice_id);
		// Get invoice products
	// 	 echo "<pre>";
    // print_r($data['invoice']);
    // exit;
		$data['products'] 			= $this->Sales_model->get_invoice_products($invoice_id);
		$data['invoice_address']  	= $this->Sales_model->get_invoice_address_details($invoice_id);
		// Load view with return form
		$data['main_content'] = 'sales/invoices/sales_return.php';
		$this->load->view('includes/template', $data);
	}
	public function save_sales_return($invoice_id)
	{
		$post = $this->input->post();

		// Load model
		$this->load->model('Sales_model');

		// Call model function
		$result = $this->Sales_model->saveSalesReturn($invoice_id, $post);

		if ($result) {
			$this->session->set_flashdata('success', 'Sales return saved successfully!');
			redirect('Sales/list_invoices/');
		} else {
			$this->session->set_flashdata('error', 'Error saving sales return.');
			redirect('Sales/sales_return/' . $invoice_id);
		}
	}
	public function list_invoices()
	{
		$data['title'] = 'Invoice List';
		$data['active_invoice_list'] 	= $this->Sales_order_model->list_all_invoices();
		$data['cancelled_invoices']  	= $this->Sales_order_model->list_Cancel_invoices();
		$data['sales_return_invoices']  = $this->Sales_order_model->list_Sales_retun_invoices();
		$data['direct_invoices']  		= $this->Sales_order_model->list_direct_invoices();
		// echo $this->db->last_query();exit();
		$data['main_content'] = 'sales/invoices/list_invoices.php';
		$this->load->view('includes/template', $data);
	}
	public function estimation_master_data($enquiry_id)
	{
		$rows = $this->Crm_model->get_estimation_details($enquiry_id);
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
	public function get_branch_related_data()
	{
		$branch_id = $this->input->post('branch_id');

		if ($branch_id) {
			// Get customers
			$customers = $this->Company_model->get_customers_by_branch($branch_id);

			// Get branch bank details and load view as HTML
			$branch_data['branch_bank'] = $this->Company_model->get_branch_bank_by_id($branch_id);
			$html_branch = $this->load->view('sales/include/branch_bank_table_invoice.php', $branch_data, true);

			// Send both in one JSON
			echo json_encode([
				'customers' => $customers,
				'bank_html' => $html_branch
			]);
		} else {
			echo json_encode([
				'customers' => [],
				'bank_html' => ''
			]);
		}
	}
	public function approve_quotation_1()
	{
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
	//------directQuotation




	public function edit_direct_quotation($quotation_id)
	{
		$data['quotation'] = $this->Quotation_model->get_quotation_by_id($quotation_id);
		$data['quotation_items'] = $this->Quotation_model->get_quotation_items($quotation_id);
		$data['customers'] = $this->Quotation_model->get_customers(); // For dropdowns, if needed
		$this->load->view('quotation/direct_quotation_form', $data);
	}
	/* ================= EDIT PAGE ================= */
	public function edit_invoice($invoice_id)
	{
		$data['title']		    = 'Edit Invoice';
		$data['invoice']          = $this->Sales_model->get_invoice_by_id($invoice_id);
		$data['invoice_products'] = $this->Sales_model->get_invoice_products($invoice_id);

		if (empty($data['invoice'])) {
			show_404();
		}
		$this->load->model('Hr_model');
		$data['employees'] = $this->Hr_model->get_employee_list();
		$data['main_content'] = 'sales/invoices/edit_invoice.php';
		$this->load->view('includes/template', $data);
	}

	/* ================= UPDATE ================= */
	public function update_invoice()
	{
		$invoice_id = $this->input->post('invoice_id');
		$invoice    = $this->Sales_model->get_invoice_by_id($invoice_id);

		if (!$invoice) {
			show_404();
		}

		// 🚫 Prevent financial edits if POSTED
		$updateData = [
			'invoice_date'  => $this->input->post('invoice_date'),
			'remark'        => $this->input->post('remark'),
			'payment_term'  => $this->input->post('payment_term'),
			'delivery_term' => $this->input->post('delivery_term'),
			'validity'      => $this->input->post('validity'),
			'prepared_by'   => $this->input->post('employee_prepared'),
			'received_by'   => $this->input->post('employee_received'),
			'updated_on'    => date('Y-m-d H:i:s'),
			'updated_by'    => $this->session->userdata('user_id')
		];

		// ✅ FIXED VARIABLE
		$update = $this->Sales_model->update_invoice($invoice_id, $updateData);

		if ($update) {
			$this->session->set_flashdata('success', 'Invoice updated successfully ✅');
		} else {
			$this->session->set_flashdata('error', 'Invoice update failed ❌');
		}
		//print_r($this->session->set_flashdata());exit();
		redirect('Sales/list_invoices');
	}

	public function internal_approve($qtn_id)
{
    $data = [
        'internal_approval' => 'Approved',
        'updated_on' => date('Y-m-d H:i:s'),
        'updated_by' => $this->session->userdata('user_id')
    ];

    $this->Sales_model->update_quotation($qtn_id, $data);

    $this->session->set_flashdata('success', 'Internal Approval Completed.');

    redirect('Sales/edit_quotation/'.$qtn_id);
}


public function delete_quotation($qtn_id)
{
    // Delete child tables first
    $this->db->where('qtn_id', $qtn_id);
    $this->db->delete('quotation_products');

    $this->db->where('qtn_id', $qtn_id);
    $this->db->delete('quotation_subheading');

    $this->db->where('qtn_id', $qtn_id);
    $this->db->delete('quotation_main_heading');

    // Delete master table last
    $this->db->where('qtn_id', $qtn_id);
    $this->db->delete('quotation_master');

    $this->session->set_flashdata('success', 'Quotation deleted successfully');

    redirect('Sales/list_quotations');
}
public function export_quotation_excel($qtn_id)
{
    $quotation = $this->Sales_model->get_quotation_master_by_id($qtn_id);

    if (empty($quotation)) {
        show_404();
    }

    $quotation = $quotation[0];

    $data['quotation_details']  = $quotation;
    $data['quotation_products'] = $this->Sales_model->get_quotation_products_by_id($qtn_id);

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Quotation_" . $quotation['quotation_code'] . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    $this->load->view('sales/quotation/export_quotation_excel', $data);
}

public function add_warranty()
{
    $data['title'] = "Warranty Certificate";

    $this->load->model('Sales_model');

    // Invoice dropdown
    $data['invoice_list'] = $this->Sales_model->get_invoice_list();

   $data['main_content'] = 'sales/add_warranty.php';
  $this->load->view('includes/template', $data);
}
public function get_invoice_warranty_details()
{
    $invoice_id = $this->input->post('invoice_id');

    $this->load->model('Sales_model');

    $result = $this->Sales_model->get_invoice_warranty_details($invoice_id);

    if (!$result) {
        $result = array(
            'customer_name' => '',
            'invoice_date'  => '',
            'site_location' => ''
        );
    }

    echo json_encode($result);
}

public function save_warranty()
{
    $this->load->model('Sales_model');

    $invoice_id = $this->input->post('invoice_id');

    $data = array(
        'invoice_id'        => $invoice_id,
        'customer_name'     => $this->input->post('customer_name'),
        'invoice_date'      => date('Y-m-d', strtotime($this->input->post('invoice_date'))),
        'site_location'     => $this->input->post('site_location'),
        'installation_date'  => $this->input->post('installation_date'),
        'warranty_period'   => $this->input->post('warranty_period'),
        'terms_conditions'  => $this->input->post('terms_conditions'), // CKEditor HTML
        'created_by'       => $this->session->userdata('login_id'),
        'created_at'       => date('Y-m-d H:i:s')
    );

    $this->db->insert('warranty_master', $data);

    $this->session->set_flashdata('success', 'Warranty Certificate saved successfully');

    redirect('Sales/warranty_list');
}

public function warranty_list()
{
    $this->load->model('Sales_model');

    $data['title'] = "Warranty List";
    $data['records'] = $this->Sales_model->get_warranty_list();

    $data['main_content'] = 'sales/warranty_list';
    $this->load->view('includes/template', $data);
}

public function print_warranty($id)
{
    $this->load->model('Sales_model');
	    $this->load->model('Company_model');


    $data['record'] = $this->Sales_model->get_warranty_by_id($id);
	 $branch_id = 1;
    $branch_details = $this->Company_model->get_branch_by_id($branch_id);

	$header = ltrim(str_replace('./', '', $branch_details->branch_header), '/');
$footer = ltrim(str_replace('./', '', $branch_details->branch_footer), '/');

        $data['branch_stamp'] = $branch_details->branch_stamp ?? '';


$data['headerPath'] = base_url($header);
$data['footerPath'] = base_url($footer);

    $this->load->view('Print/print_warranty', $data);
}
public function generate_einvoice_xml($invoice_id)
{
    $invoice_master = $this->Sales_order_model->get_invoice_master($invoice_id);

    if (empty($invoice_master)) {
        show_error("Invoice not found");
    }

    $so = $this->Sales_order_model->get_sales_order_master($invoice_master['so_id']);
    $qtn_id = $so['qtn_id'];

    $quotation = $this->Sales_order_model->get_quotation_print_data($qtn_id);

    $items = $this->Sales_order_model->get_invoice_products($invoice_id);

    if (empty($items)) {
        show_error("Invoice items not found");
    }

    // pass correct data
    $xml = $this->build_invoice_xml($invoice_master, $quotation, $items);

    header('Content-Type: application/xml');
    header('Content-Disposition: attachment; filename="einvoice_'.$invoice_id.'.xml"');

    echo $xml;
}

private function build_invoice_xml($invoice, $quotation, $items)
{
    $xml = new SimpleXMLElement('<Invoice/>');

    // Customer (from quotation)
    $customer = $xml->addChild('Customer');
    $customer->addChild('Name', $quotation['customer_name'] ?? '');
	$customer->addChild('Address', $quotation['customer_address'] ?? '');
	$customer->addChild('Address', $quotation['emirate'] ?? '');
    $customer->addChild('TRN', $quotation['customer_TR_no'] ?? '');
    $customer->addChild('Address', $quotation['customer_email'] ?? '');


    // Items
    $itemsNode = $xml->addChild('Items');

    $net_total = 0;
    $vat_total = 0;

    foreach ($items as $row) {

        $qty  = $row['deliver_quantity'];
        $rate = $row['unit_price'] ?? 0;

        $net = $qty * $rate;
        $vat = $net * 0.05;

        $net_total += $net;
        $vat_total += $vat;

        $item = $itemsNode->addChild('Item');
        $item->addChild('Product', $row['item_name']);
        $item->addChild('Qty', $qty);
        $item->addChild('Rate', $rate);
        $item->addChild('Net', $net);
        $item->addChild('VAT', $vat);
    }

    // Totals
    $total = $xml->addChild('Total');
    $total->addChild('NetTotal', $net_total);
    $total->addChild('VATTotal', $vat_total);
    $total->addChild('GrandTotal', $net_total + $vat_total);

    return $xml->asXML();
}
}
