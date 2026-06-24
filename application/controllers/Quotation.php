<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Quotation extends CI_Controller
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

		$this->load->model('Company_model');
		$this->load->helper('menu_helper');
	}
	public function add_direct_quotation()
	{
		$user = $this->session->userdata('user_id');
		if (!has_access($user, 'Sales/list_quotations', 'A')) {
			$data['title'] = 'Access Denied';
			$data['main_content'] = 'errors/access_control.php';
		} else {
			$data['title'] = 'Add Quotation';
			$data['customer_list'] = $this->Company_model->get_all_customer_list();
			$data['branch_list']   = $this->Company_model->get_all_branches();
			$data['active_users']  = $this->Setup_model->get_active_user_list();
			$data['all_products']  = $this->Item_model->get_all_item_list();
			$data['active_units']  = $this->Item_model->get_all_units();
			//print_r( $this->session->userdata());exit();
			//$branch_id = $this->session->userdata('branch_id');
			$data['quotation_code']		 = $this->Sales_model->generate_quotation_code(1);

			$this->load->model('Hr_model');
            $data['employees'] = $this->Hr_model->get_employee_list();

			$data['main_content'] = 'sales/quotation/add_direct_quotation.php';
		}

		$this->load->view('includes/template', $data);
	}
	

	public function save_direct_quotation()
	{
		$this->load->model('Sales_model');
		$this->load->helper('date');

		$post = $this->input->post();
		$action = $this->input->post('action'); // To detect which button (Save / Save & Sales Order)

		$this->db->trans_begin();

		try {
			// --- Basic Validations ---
			if (empty($post['quotation_date'])) {
				throw new Exception('Quotation Date is required.');
			}

			// --- Extract numeric values safely ---
			$subtotal          = floatval($post['sub_total'] ?? 0);
			$marginPercent     = floatval($post['margin_percent'] ?? 0);
			$marginAmount      = floatval($post['margin_amount'] ?? 0);
			$freightPercent    = floatval($post['freight_percent'] ?? 0);
			$freightAmount     = floatval($post['freight_amount'] ?? 0);
			$bankCharge        = floatval($post['bank_charge'] ?? 0);
			$travelExpense     = floatval($post['travel_expense'] ?? 0);
			$inspectionCost    = floatval($post['inspection_cost'] ?? 0);
			$otherExpense      = floatval($post['other_expense'] ?? 0);
			$discountPercent   = floatval($post['qtn_add_discount_percentage'] ?? 0);
			$vatRequired       = isset($post['qtn_apply_vat']) ? 1 : 0;
			$vatPercent        = floatval($post['qtn_vat_percentage'] ?? 0);

			// --- Calculation Logic ---
			$baseTotal         = $subtotal + $marginAmount + $freightAmount + $bankCharge + $travelExpense + $inspectionCost + $otherExpense;
			$discountAmount    = ($baseTotal * $discountPercent) / 100;
			$totalBeforeVat    = $baseTotal - $discountAmount;
			$vatAmount         = $vatRequired ? ($totalBeforeVat * $vatPercent / 100) : 0;
			$grandTotal        = $totalBeforeVat + $vatAmount;

			// --- Approval rule (optional) ---
			$approval_status = ($grandTotal > 10000) ? 0 : 1;

			// --- Master Table Data ---
			$masterData = [
				'quotation_type'       => 'direct',
				'quotation_code'       => $post['quotation_code'],
				'quotation_date'       => $post['quotation_date'],
				'quotation_branch_id'  => $post['branch'],
				'quotation_customer'   => $post['customer_id'],
				'project_name'         => $post['project_name'],
				'sub_total'            => $subtotal,
				'margin_percent'       => $marginPercent,
				'margin_amount'        => $marginAmount,
				'freight_percent'      => $freightPercent,
				'freight_amount'       => $freightAmount,
				'bank_charge'          => $bankCharge,
				'travel_expense'       => $travelExpense,
				'inspection_cost'      => $inspectionCost,
				'other_charge'         => $otherExpense,
				'discount_percentage'  => $discountPercent,
				'discount_amount'      => $discountAmount,
				'total_before_vat'     => $totalBeforeVat,
				'vat_required'         => $vatRequired,
				'vat_percentage'       => $vatPercent,
				'vat_amount'           => $vatAmount,
				'grand_total'          => $grandTotal,
				'payment_term'         => $post['payment_term'] ?? '',
				'delivery_term'        => $post['delivery_term'] ?? '',
				'terms_condition'      => $post['terms_condition'] ?? '',
				'validity'             => $post['validity'] ?? '',
				'quotation_status'     => 'Draft',
				'quotation_revision'   => 0,
				'aproval'             => 0,
				'active'               => 1,
				'created_by'           => $this->session->userdata('user_id') ?? 1,
				'created_on'           => date('Y-m-d H:i:s'),
				'warranty'      => $post['warranty'] ?? '',
				'warranty_description'      => $post['warranty_description'] ?? '',
				 'project_location' => $post['project_location'],
				'prepared_by' 			=> $post['employee_prepared'],
				'approved_by' 			=> $post['employee_approved'],
				'notes'      => $post['notes'] ?? '',


			];
			// --- Insert Master Record ---
			$qtn_id = $this->Sales_model->insert_quotation($masterData);

			// --- Save Details (optional: if your direct quotation has headings/products) ---
			if (!empty($post['main_heading'])) {
				$this->_save_direct_qtn_details($qtn_id); // reuse same private function
			}

			// --- Commit Transaction ---
			if ($this->db->trans_status() === FALSE) {
				throw new Exception('Transaction failed.');
			}

			$this->db->trans_commit();
			$this->session->set_flashdata('success', 'Direct Quotation created successfully.');

			// --- Redirect based on button ---
			if ($action === 'sales_order') {
				redirect(base_url('index.php/Sales/add_sales_order?quotation_id=' . $qtn_id));
			} else {
				redirect('Sales/list_quotations');
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			log_message('error', 'Direct Quotation Save Failed: ' . $e->getMessage());
			$this->session->set_flashdata('error', 'Failed to save Direct Quotation. ' . $e->getMessage());
			redirect('Quotation/add_direct_quotation');
		}
	}
	/*private function _save_direct_qtn_details($qtn_id)
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
						print_r($_POST['products']);
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
									'dicount_amount' => $prod['discount_amount'],
									'taxable_amount' => $prod['amount']
								];
								$this->Sales_model->insert_product($prodData);
							}
						}
					}exit();
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

	}*/
	private function _save_direct_qtn_details($qtn_id)
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
									'prd_description' => $prod['description'],

									'discount_percent'   => $prod['discount_percent'],
									'discount_amount'    => $prod['discount_amount'],
									'taxable_amount'     => $prod['amount'],
									'warranty'     => $prod['warranty']
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
}
