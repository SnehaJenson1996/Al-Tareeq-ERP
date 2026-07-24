<?php
class CRM extends CI_Controller
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

		$this->load->model('Crm_model');
		$this->load->model('Sales_order_model');

		$this->load->model('Company_model');
		$this->load->helper('menu_helper');
	}

	function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		if (!isset($is_logged_in) || $is_logged_in != true) {
			echo 'You don\'t have permission to access this page. <a href="../login">Login</a>';
			die();
		}
	}
	
	public function save_survey()
	{
		$resurvey   = $this->input->post('re_survey_id');
		$enquiry_id = $this->input->post('enquiry_id');

		// Prepare data for insertion
		$data = [
			'enquiry_id'         => $enquiry_id,
			'survey_status'      => 1, // assigned for survey
			'assigned_person_id' => $this->input->post('employee_survey'),
			'scheduled_date'     => $this->input->post('survey_date'),
			'start_time'         => $this->input->post('survey_start_datetime'),
			'end_time'           => trim($this->input->post('survey_end_datetime')),
			'scheduled_hours'    => trim($this->input->post('total_hours')),
			'remarks'            => $this->input->post('remarks'),
			'active'             => 1,
			'created_on'         => date('Y-m-d H:i:s')
		];

		// Insert site survey
		$inserted = $this->Crm_model->insert_site_survey($data);

		if ($inserted) {

			// Update enquiry status
			$enqrydata = [
				'enquiry_status' => 1, // Site survey
				'updated_on'     => date('Y-m-d H:i:s')
			];

			if (!empty($resurvey)) {
				$enqrydata['reschedule_survey'] = 1;
			}

			$updated = $this->Crm_model->update_enquiry_data($enquiry_id, $enqrydata);

			// Success messages
			if (!empty($resurvey)) {
				$this->session->set_flashdata('success', 'Survey rescheduled successfully.');
			} else {
				$this->session->set_flashdata('success', 'Enquiry saved successfully.');
			}
			redirect('CRM/view_enquiry/' . $enquiry_id . '/edit');
		} else {
			// Failure message
			$this->session->set_flashdata('error', 'Failed to save enquiry.');
			redirect('CRM/add_enquiry/');
		}
	}

	/********** Sub-function: Prepare enquiry data*********/
	private function _prepare_enquiry_data()
	{
		$site_survey = !empty($this->input->post('allow_site_survey')) ? 1 : 0;

		return [
			'project_name'      => $this->input->post('project_name', TRUE),
			'project_subject'   => $this->input->post('project_subject', TRUE),
			'project_location'  => $this->input->post('project_location', TRUE),
			'branch_id'         => $this->input->post('branch'),
			'enquiry_code'      => trim($this->input->post('enquiry_code')),
			'enquiry_date'      => $this->input->post('enquiry_date'),
			'enquiry_source'    => $this->input->post('enquiry_source'),
			'enquiry_category'  => $this->input->post('enquiry_category'),
			'enquiry_customer'  => $this->input->post('customer_id'),
            'enquiry_status' => ($site_survey == 0) ? 3 : 1,
			'site_survey'       => $site_survey,
			'created_by'        => $this->session->userdata('user_id'),
			'client_ref_no'     => $this->input->post('client_ref_no'),
			'comments'          => $this->input->post('comments'),
			'created_at'        => date('Y-m-d H:i:s')
		];
	}


	public function view_enquiry($mode = "edit")
{
    $enquiry = $this->uri->segment(3);
    $mode = $this->uri->segment(4) ?? 'view';
    $menu_status = $this->uri->segment(5) ?? 1;

    $data['mode'] = $mode;
    $data['menu_status'] = $menu_status;

    $this->load->model('Company_model');
    $this->load->model('Item_model');

    $enquiry_details = $this->Crm_model->get_enquiry_by_id($enquiry);

    $branch_id = $enquiry_details['branch_id'];
    $data['title'] = "Enquiry Details";
    $data['Resurvey'] = $enquiry_details['reschedule_survey'];
    $data['customer_list'] = $this->Company_model->get_customers_by_branch($branch_id);
    $data['all_products'] = $this->Item_model->get_all_item_list();
    $data['active_units'] = $this->Item_model->get_all_units();

    $des_id = 2; // DESIGNATION SITE ENGINEER
    $data['employee_list'] = $this->Company_model->get_all_employees_designation_id($des_id, $branch_id);
    $data['enquiry_data'] = $enquiry_details;

    $enquiry_revision = $enquiry_details['enquiry_revision'];
    if ($enquiry_revision == 1) {
        $data['old_survey_data'] = $this->Crm_model->get_survey_old_data($enquiry);
    }

    // Initialize estimation arrays
    $data['master'] = [];
    $data['estimation'] = [];
    $data['mainIndex'] = 0;
// ===== Survey block =====
$survey_details = $this->Crm_model->get_survey_by_enquiry_id($enquiry);

// Active survey data (for form)
$data['survey_data'] = $survey_details ?? [];

// Files for the active survey
$data['survey_files'] = !empty($survey_details) 
    ? $this->Crm_model->get_survey_files_by_id($survey_details['survey_id'])
    : [];

// Admin sees data but cannot edit
$data['survey_readonly'] = true; 

// Old/inactive surveys for history buttons
$data['old_survey_data'] = $this->Crm_model->get_survey_old_data($enquiry);

// Reschedule flag: if no active survey exists, it's a reschedule
$data['Resurvey'] = empty($survey_details) ? 1 : 0;

    // ===== Load estimation regardless of survey =====
    if ($enquiry_details['enquiry_status'] >= 3) {
        $Enquiry_details = $this->estimation_master_data($enquiry);
        if (!empty($Enquiry_details)) {
            $data['master'] = $Enquiry_details['master'] ?? [];
            $data['estimation'] = $Enquiry_details['estimation'] ?? [];
            $data['mainIndex'] = count($Enquiry_details['estimation'] ?? []);
        }
    }

    $data['main_content'] = 'crm/enquiry/view_enquiry.php';
    $this->load->view('includes/template', $data);
}

public function re_schedule_survey()
{
    $enquiry_id = $this->uri->segment(3);
	echo "<pre>";
print_r($_POST);
exit;

    // ✅ GET NEW ASSIGNED PERSON FROM FORM (IMPORTANT FIX)
    $assigned_person = $this->input->post('employee_survey');
	echo $this->input->post('employee_survey');
exit;

    // fallback if empty
    if (empty($assigned_person)) {

        // optional fallback to last survey
        $last_survey = $this->db
            ->where('enquiry_id', $enquiry_id)
            ->order_by('survey_id', 'DESC')
            ->get('site_survey_master')
            ->row_array();

        $assigned_person = $last_survey['assigned_person_id'] ?? null;
    }

    // 1. Deactivate old survey
    $this->db->where('enquiry_id', $enquiry_id)
        ->where('active', 1)
        ->update('site_survey_master', [
            'active' => 0,
            're_survey_status' => 1,
            'updated_on' => date('Y-m-d H:i:s')
        ]);

    // 2. Update enquiry
    $this->db->where('enquiry_id', $enquiry_id)
        ->update('enquiry_master', [
            'enquiry_status' => 1,
            'reschedule_survey' => 1,
            'updated_on' => date('Y-m-d H:i:s')
        ]);

    // 3. Insert NEW survey with SELECTED employee
    $this->db->insert('site_survey_master', [
        'enquiry_id'         => $enquiry_id,
        'assigned_person_id' => $assigned_person, // ✅ NOW CORRECT
        'scheduled_date'     => date('Y-m-d'),
        'start_time'         => date('Y-m-d H:i:s'),
        'end_time'           => null,
        'scheduled_hours'    => null,
        'active'             => 1,
        'created_on'         => date('Y-m-d H:i:s')
    ]);

    redirect('CRM/view_enquiry/' . $enquiry_id . '/edit/2');
}
	public function accept_site_survey()
{
    $enquiry_id = $this->uri->segment(3);
    $survey_id  = $this->input->post('survey_table_id');
    $assigned_person_id = $this->input->post('employee_survey');

    $re_survey = $this->input->post('re_survey') ? 1 : 0;

    if (empty($enquiry_id)) {
        $this->session->set_flashdata('error', 'Invalid enquiry ID.');
        redirect('CRM/enquiry_list');
        return;
    }

    // =========================
    // 1. UPDATE ENQUIRY STATUS
    // =========================
    $this->db->where('enquiry_id', $enquiry_id);
    $this->db->update('enquiry_master', [
        'enquiry_status' => 3
    ]);

    // ======================================
    // 2. RESCHEDULE LOGIC (IMPORTANT FIX)
    // ======================================
    if ($re_survey == 1 && !empty($survey_id)) {

        // ❌ OLD SURVEY → make inactive
        $this->db->where('survey_id', $survey_id);
        $this->db->update('site_survey_master', [
            'active' => 0,
            'updated_on' => date('Y-m-d H:i:s')
        ]);

        // ✅ INSERT NEW SURVEY (history)
       $insert_data = [
    'enquiry_id'          => $enquiry_id,
    'assigned_person_id'  => $assigned_person_id,
    'scheduled_date'      => $this->input->post('survey_date'),

    // ✅ correct DB columns
    'start_time' => date('Y-m-d H:i:s', strtotime($this->input->post('survey_start_datetime'))),
    'end_time'   => date('Y-m-d H:i:s', strtotime($this->input->post('survey_end_datetime'))),

    'scheduled_hours'   => $this->input->post('total_hours'),
    'remarks'           => $this->input->post('remarks'),

    'active'            => 1,
    're_survey_status'  => !empty($this->input->post('re_survey')) ? 1 : 0,
    'created_on'        => date('Y-m-d H:i:s')
];

        $this->db->insert('site_survey_master', $insert_data);

        $survey_id = $this->db->insert_id(); // new survey id
    }
    else {
        // ======================================
        // NORMAL UPDATE (NO RESCHEDULE)
        // ======================================
        if (!empty($survey_id)) {

            $this->db->where('survey_id', $survey_id);
            $this->db->update('site_survey_master', [
                'assigned_person_id' => $assigned_person_id,
                'actual_date'        => $this->input->post('actual_date'),
                'actual_start_time'  => $this->input->post('actual_start_time'),
                'actual_end_time'    => $this->input->post('actual_end_time'),
                'actual_hours'       => $this->input->post('actual_hours'),
                'survey_comments'    => $this->input->post('survey_comments'),
                'material_details'   => $this->input->post('material_details'),
                'updated_on'         => date('Y-m-d H:i:s')
            ]);
        }
    }

    // =========================
    // 3. FILE UPLOAD (same)
    // =========================
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
                $config['max_size']      = 20480;
                $config['encrypt_name']  = TRUE;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')) {

                    $fileData = $this->upload->data();

                    $data = [
                        'survey_id'   => $survey_id,
                        'file_name'   => $fileData['file_name'],
                        'uploaded_on' => date("Y-m-d H:i:s")
                    ];

                    $this->db->insert('survey_files', $data);
                }
            }
        }
    }

    // =========================
    // 4. REDIRECT
    // =========================
    $this->session->set_flashdata('success', 'Survey updated successfully.');
    redirect('CRM/view_enquiry/' . $enquiry_id);
}

	//Estimation 
	public function save_estimation()
	{
		$enquiry_id         = $this->input->post('enquiry_id');
		$sub_total          = $this->input->post('sub_total') ?? 0;
		$margin_percent     = $this->input->post('margin_percent') ?? 0;
		$margin_amount      = $this->input->post('margin_amount') ?? 0;
		$freight_percent    = $this->input->post('freight_percent') ?? 0;
		$freight_amount     = $this->input->post('freight_amount') ?? 0;
		$bank_charge        = $this->input->post('bank_charge') ?? 0;
		$travel_expense     = $this->input->post('travel_expense') ?? 0;
		$other_expense      = $this->input->post('other_expense') ?? 0;
		$inspection_cost    = $this->input->post('inspection_cost') ?? 0;
		$total_cost         = $this->input->post('total_cost') ?? 0;

		// 2️⃣ Generate a temporary estimation code
		$estimation_code    = "EST-" . date('YmdHis'); // you can change format later

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
			'other_expense'       => $other_expense,
			'inspection_cost'     => $inspection_cost,
			'grand_total'         => $total_cost,
			'created_on'          => date('Y-m-d H:i:s')
		];

		$this->db->insert('estimation_master', $masterData);
		$estimation_id   = $this->db->insert_id();
		if ($estimation_id) {
			$status = $this->save_estimation_details($estimation_id);
			if ($status) {
				$this->db->where('enquiry_id', $enquiry_id);
				$this->db->update('enquiry_master', ['enquiry_status' => 4]); //Estimation Completed
				redirect("CRM/view_enquiry/$enquiry_id/edit");
			}
		} else {
			redirect("CRM/view_enquiry/$enquiry_id/edit");
		}
	}
	private function save_estimation_details($estimation_master_id)
	{
		
		$main_headings = $this->input->post('main_heading'); // main heading texts
		$main_details  = $this->input->post('main_details'); // main details
		$sub_headings  = $this->input->post('sub_heading');  // nested array of subheadings
		$products      = $this->input->post('products');     // nested array of products
		// print_r($products);exit();
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

	public function save_estimation_dummy()
	{
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
		} else {
			echo json_encode(['status' => 'error']);
		}
	}



	public function update_estimation()
	{
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
					redirect("CRM/view_enquiry/$enquiry_id");
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
	public function update_estimation_main_data($estimation_id)
	{
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
	public function approve_estimation()
	{
		$estimation_id = $this->input->post('estimation_id');

		if (empty($estimation_id)) {
			echo json_encode(['status' => 'error', 'message' => 'Missing estimation_id']);
			return;
		}

		// Get enquiry_id from estimation
		$enquiry_id = $this->get_enquiry_id_by_estimation($estimation_id);

		if (empty($enquiry_id)) {
			echo json_encode(['status' => 'error', 'message' => 'Invalid estimation']);
			return;
		}

		$current_time = date('Y-m-d H:i:s');
		$user_id = $this->session->userdata('user_id');

		$this->db->trans_start();

		// Update enquiry_master
		$this->db->where('enquiry_id', $enquiry_id);
		$this->db->update('enquiry_master', [
			'enquiry_status' => 5,
			'updated_on'     => $current_time
		]);

		// Update estimation_master
		$this->db->where('estimation_id', $estimation_id);
		$this->db->update('estimation_master', [
			'approval'    => 1,
			'approved_by' => $user_id,
			'approved_on' => $current_time
		]);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
		} else {
			echo json_encode([
				'status' => 'success',
				'message' => 'Estimation approved successfully',
				'enquiry_id' => $enquiry_id  // ✅ important line for redirect
			]);
		}
	}

	function list_site_survey()
	{
		$user = $this->session->userdata('user_id');

		$user_data = $this->Company_model->get_user_by_id($user);
		// echo $this->db->last_query();exit();
		if (!empty($user_data['employee_id'])) {
			$data['title']			= "Enquiry for survey";
			$data['enquiry_list']   = $this->Crm_model->get_enquiry_for_survey($user_data['employee_id']);
			//echo $this->db->last_query();exit();
			$data['main_content']   = 'crm/technicianapp/list_survey_enquiry.php';
			$this->load->view('includes/template', $data);
		} else {
			$data['title']			= 'Access Denied';
			$data['main_content']	= 'errors/access_control.php';
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
		$existing = $this->Crm_model->get_by_enquiry($enquiry_id);
		$data = [
			'actual_date'      => $actual_date,
			'actual_start_time' => $actual_start,
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
			$this->Crm_model->update_survey($existing->survey_id, $data);
			$survey_id = $existing->survey_id;

			// Update enquiry status
			$this->db->where('enquiry_id', $enquiry_id);
			$this->db->update('enquiry_master', [
				'enquiry_status' 		 => 2,
				'reschedule_survey'		 => 0,
				'updated_on'      		 => date('Y-m-d H:i:s')
			]);

			$this->session->set_flashdata('success', 'Survey updated successfully.');
		} else { // Insert new survey
			$data['created_on'] = date('Y-m-d H:i:s');
			$data['created_by'] = $this->session->userdata('user_id');
			$survey_id = $this->Crm_model->insert_survey($data);
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
					'file_name'     => time() . '_' . $i
				];

				$this->upload->initialize($config);

				if ($this->upload->do_upload('file')) {
					$uploadData = $this->upload->data();

					// Insert into survey_files table
					$this->db->insert('survey_files', [
						'survey_id' => $survey_id,
						'enquiry_id' => $enquiry_id,
						'file_name' => $uploadData['file_name']
					]);
				}
			}
		}

		redirect('CRM/list_technicians_surveys');
	}

	/**************List enquiry**********/
	
	public function list_surveys()
	{
		$data['title'] = "Survey Reports";
		$data['survey_list'] = $this->Crm_model->list_survey_reports();
		$data['main_content'] = 'crm/list_survey_reports.php';
		$this->load->view('includes/template', $data);
	}
	public function list_technicians_surveys()
	{
		$data['title'] = "Survey Reports";
		$user = $this->session->userdata('employee_id');
		$data['survey_list'] = $this->Crm_model->list_survey_reports_by_technicians_id($user);
		$data['main_content'] = 'crm/list_technicians_reports.php';
		$this->load->view('includes/template', $data);
	}
	public function list_estimations()
	{
		$data['title'] 			= 'Estimation List';
		$data['estimations'] 	= $this->Crm_model->get_estimation_master();
		$data['main_content']	= 'crm/list_estimation.php';
		$this->load->view('includes/template', $data);
	}
	public function view_estimation($estimation_id)
	{
		// Get only this estimation's details
		$estimations_raw = $this->Crm_model->get_all_estimation_details($estimation_id);

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
		$data['main_content'] = 'crm/list_estimation_details.php';
		$this->load->view('includes/template', $data);
	}
	/*********************validation*************/
	/*** 🔹 Sub-function: Validate enquiry form**/
	private function _validate_enquiry_form()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('branch', 'Branch', 'required|trim');
		$this->form_validation->set_rules('project_name', 'Project Name', 'required|trim');
		$this->form_validation->set_rules('project_subject', 'Project Subject', 'required|trim');
		$this->form_validation->set_rules('project_location', 'Project Location', 'required|trim');
		$this->form_validation->set_rules('enquiry_category', 'Enquiry Category', 'required');
		$this->form_validation->set_rules('enquiry_code', 'Enquiry Code', 'required|trim');
		$this->form_validation->set_rules('enquiry_date', 'Enquiry Date', 'required');
		$this->form_validation->set_rules('enquiry_source', 'Enquiry Source', 'required');
		$this->form_validation->set_rules('customer_id', 'Customer', 'required');

		return $this->form_validation->run();
	}
}
