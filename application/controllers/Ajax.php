<?php
class Ajax extends CI_Controller
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
		}
	}
    function get_enquirydata_for_survey(){
         $enquiry_id = $this->input->post('enquiry_id');
         $this->load->model('Sales_model');
         $data = $this->Sales_model->get_enquiry_for_survey_by_id($enquiry_id);
    if ($data) {
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    } else {
        echo json_encode([
            'success' => false
        ]);
    }
    }

    public function get_survey_details($survey_id)
    {
        $this->db->select('sm.*, GROUP_CONCAT(sf.file_name) as file_names, em.employee_name');
        $this->db->from('site_survey_master sm');
        $this->db->join('survey_files sf','sm.survey_id = sf.survey_id','left');
        $this->db->join('employee_master em','sm.assigned_person_id = em.employee_id','left');
        $this->db->where('sm.survey_id', $survey_id);
        $this->db->group_by('sm.survey_id');
        $query = $this->db->get()->row_array();

        // convert to array
        if (!empty($query['file_names'])) {
            $query['file_names'] = explode(',', $query['file_names']);
        } else {
            $query['file_names'] = [];
        }

        $this->load->view('crm/enquiry/survey_detail_modal', ['survey' => $query]);

    }


    public function save_product_ajax() {
            $this->load->model('Item_model');
            $this->load->library('upload'); // ✅ make sure upload lib is loaded
            $item_name  =    $this->input->post('item_name');
            $item_code  =    $this->input->post('item_code');
            $unit_price =    $this->input->post('unit_price');

            $unit = $this->input->post('item_unit');
            if ($unit == "__new_unit__") {
                $insert_data = [
                    'unit_abbr'  => $this->input->post('new_unit_name'),
                    'active'     => 1,
                    'unit_name'  => $this->input->post('new_unit_code'),
                    'created_on' => date('Y-m-d H:i:s'),
                ];
                $unit_id = $this->Item_model->insert_unit($insert_data);
                $unit_name = $this->input->post('new_unit_code');
            } else {
                $unit_id  = $unit;
                $unitdata =$this->Item_model->get_unit_by_id($unit_id);
                $unit_name=$unitdata[0]->unit_name;
            }

            $item_data = [
                'item_name'        => $item_name,
                'item_code'        => $item_code,
                'item_unit'        => $unit_id,
                'unit_price'       => $unit_price,
                'item_description' => $this->input->post('item_description'),
            ];

            // ✅ Handle image upload
            if (!empty($_FILES['item_image']['name'])) {
                $config['upload_path']   = './public/items/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size']      = 2048; // 2 MB
                $config['file_name']     = time() . '_' . preg_replace('/\s+/', '_', $_FILES['item_image']['name']);
                $config['detect_mime']   = true;
                $config['max_filename']  = 255;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('item_image')) {
                    echo json_encode([
                        'status'  => 'error',
                        'message' => $this->upload->display_errors()
                    ]);
                    return;
                } else {
                    $upload_data = $this->upload->data();
                    $item_data['item_image'] = $upload_data['file_name'];
                }
            }

            $item_id = $this->Item_model->insert_item($item_data);
            if ($item_id) {
                echo json_encode([
                                "status"    => "success",
                                "item_id"   => $item_id,
                                "item_name" => $item_name,
                                "unit_id"   => $unit_id,       // newly saved product's unit id
                                "unit_name" => $unit_name,   // readable text for dropdown
                                "unit_price"=> $unit_price
                            ]);

            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to save product']);
            }
}
public function restore_estimation_items() {
    // 1. Get all main headings
    $mainHeadings = $this->db->get('estimation_main_heading_dummy')->result_array();

    $html = '';

    foreach ($mainHeadings as $mainIndex => $main) {
        $mainId = $main['Main_heading_id'];

        $html .= '
        <div id="main_heading_block_'.$mainIndex.'" class="border p-2 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div style="width: 40%;">
                    <label>Main Heading</label>
                    <input type="text" name="main_heading['.$mainIndex.']" 
                        value="'.htmlspecialchars($main['main_heading']).'" 
                        class="form-control form-control-sm" required>
                </div>
                <div style="width: 45%;">
                    <label>Details</label>
                    <textarea name="main_details['.$mainIndex.']" class="form-control form-control-sm">'.htmlspecialchars($main['main_details']).'</textarea>
                </div>
                <div class="mt-4">
                    <button type="button" class="btn btn-success btn-sm addSubHeading" data-main="'.$mainIndex.'">+ Sub Heading</button>
                    <button type="button" class="btn btn-danger btn-sm removeMainHeading" data-main="'.$mainIndex.'">🗑</button>
                </div>
            </div>
            <div class="subHeadingContainer" id="subHeadingContainer_'.$mainIndex.'">';

        // 2. Get subheadings for this main heading
        $subHeadings = $this->db->where('main_heading_id', $mainId)->get('estimation_sub_heading_dummy')->result_array();

        foreach ($subHeadings as $subIndex => $sub) {
            $subId = $sub['sub_heading_id'];

            $html .= '
            <div class="border p-2 mb-2 subHeadingContainer" data-main="'.$mainIndex.'" data-sub="'.$subIndex.'">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <input type="text" name="sub_heading['.$mainIndex.']['.$subIndex.']" 
                        value="'.htmlspecialchars($sub['sub_heading']).'" 
                        class="form-control form-control-sm w-75">
                    <button type="button" class="btn btn-danger btn-sm removeSubHeading">🗑</button>
                </div>
                <table class="table table-bordered productTable mb-0">
                    <thead>
                        <tr style="background-color:#f8f9fa;">
                            <th>Product</th>
                            <th style="width:100px;">Unit</th>
                            <th style="width:100px;">Qty</th>
                            <th style="width:120px;">Unit Price</th>
                            <th style="width:120px;">Amount</th>
                            <th style="width:60px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>';

            // 3. Get products for this subheading
            $products = $this->db->where('sub_heading_id', $subId)->get('estimation_product_details_dummy')->result_array();

            foreach ($products as $rowId => $prod) {
                $html .= '
                <tr>
                    <td>
                        <select name="products['.$mainIndex.']['.$subIndex.']['.$rowId.'][product_id]" class="form-control form-control-sm product-select select2">
                            <option value="">-- Select Product --</option>';
                
                foreach ($this->db->get('item_master')->result_array() as $p) {
                    $selected = ($p['item_id'] == $prod['product_id']) ? 'selected' : '';
                    $html .= '<option value="'.$p['item_id'].'" '.$selected.'>'.$p['item_name'].'</option>';
                }

                $html .= '
                        </select><br/><br/>
                        <textarea name="products['.$mainIndex.']['.$subIndex.']['.$rowId.'][product_description]" class="form-control form-control-sm">'.htmlspecialchars($prod['product_description']).'</textarea>
                    </td>
                    <td>
                        <select name="products['.$mainIndex.']['.$subIndex.']['.$rowId.'][unit_id]" class="form-control form-control-sm unit-select select2">';
                
                foreach ($this->db->get('unit_master')->result_array() as $u) {
                    $selected = ($u['unit_id'] == $prod['unit_id']) ? 'selected' : '';
                    $html .= '<option value="'.$u['unit_id'].'" '.$selected.'>'.$u['unit_name'].'</option>';
                }

                $html .= '</select>
                    </td>
                    <td><input type="number" step="0.01" name="products['.$mainIndex.']['.$subIndex.']['.$rowId.'][quantity]" value="'.$prod['quantity'].'" class="form-control form-control-sm qty"></td>
                    <td><input type="number" step="0.01" name="products['.$mainIndex.']['.$subIndex.']['.$rowId.'][unit_price]" value="'.$prod['unit_price'].'" class="form-control form-control-sm unitPrice"></td>
                    <td><input type="number" step="0.01" name="products['.$mainIndex.']['.$subIndex.']['.$rowId.'][amount]" value="'.$prod['amount'].'" class="form-control form-control-sm amount" readonly></td>
                    <td><button type="button" class="btn btn-success btn-sm addProductRow">+</button></td>
                </tr>';
            }

            $html .= '</tbody></table></div>';
        }

        $html .= '</div></div>';
    }

    echo $html;
}



    /////
    function ajax_get_rfq_info(){
        $this->load->model('Purchase_Model');
        $value = array();
		$rfq_id = $this->input->post('rfq_id');		
		$data['records'] = $this->Purchase_Model->get_purchase_rfq_by_id($rfq_id);
		foreach ($data['records'] as $row) {
			$value = array(
                            'supplier_id'          => $row->supplier_id,
                            'supplier_name'        => $row->supplier_name,
                            'branch_id'            => $row->branch_id,
                            'branch_name'          => $row->branch_name,
                            'sales_person_name'    => $row->sales_person_name,
                            'subject'              => $row->subject,
                            'project'              => $row->project,
                            'ref'                  => $row->ref
                            
                        );
		}
		echo json_encode($value);
    }
	function get_rfq_items_for_quote(){
		
		$rfq_id = $this->input->post('rfq_id');
		$this->load->model('Purchase_Model');
		$data['records2']=$this->Purchase_Model->get_purchase_rfq_tr($rfq_id);
		$this->load->view('ajax/purchase_rfq_items_for_quote',$data);
	}

    //PR

function ajax_get_pr_info() {
    $this->load->model('Purchase_Model');
    $pr_id = $this->input->post('pr_id');

    $pr = $this->Purchase_Model->get_purchase_request_by_id($pr_id);
    $value = array(
        'branch_id' => $pr->branch_id,
        'branch_name' => $pr->branch_name,
        'supplier_id' => $pr->supplier_id,
        'supplier_name' => $pr->supplier_name,
        'project' => $pr->project,
        'pr_code' => $pr->pr_code,
        'created_by_name' => $pr->created_by_name // optional
    );

    echo json_encode($value);
}
public function get_pr_items_for_quote() {
    $pr_id = $this->input->post('pr_id'); // get from AJAX POST
    $this->load->model('Purchase_Model');
    $data['records'] = $this->Purchase_Model->get_pr_items($pr_id);
    $this->load->view('ajax/purchase_request_items_for_quote', $data);
}

//PR END
	// function ajax_get_quote_info(){
    //     $value = array();
	// 	$quotation_id = $this->input->post('quotation_id');

	// 	$this->load->model('Purchase_Model');
	// 	$data['records'] = $this->Purchase_Model->get_pur_qtn_master_by_id($quotation_id);
	// 	foreach ($data['records'] as $row) {
	// 		$value = array('supplier_id' => $row->supplier_id, 'supplier_name' => $row->supplier_name,'contact_number'=>$row->contact_number,'branch_id'=>$row->branch_id,'branch_name'=>$row->branch_name,'subtotal'=>$row->subtotal,'discount_percent'=>$row->discount_percent,'discount'=>$row->discount,'vat_percent'=>$row->vat_percent,'vat_amt'=>$row->vat_amt,'grand_total'=>$row->grand_total,'validity'=>$row->validity,'payment_term'=>$row->payment_term,'general_term'=>$row->general_term,'delivery_term'=>$row->delivery_term,'reference'=>$row->reference,'project'=>$row->project);
	// 	}
	// 	echo json_encode($value);
    // }

    function ajax_get_quote_info(){
        $value = array();
		$quotation_id = $this->input->post('quotation_id');

		$this->load->model('Purchase_Model');
		$data['records'] = $this->Purchase_Model->get_pur_qtn_master_by_id($quotation_id);
		foreach ($data['records'] as $row) {
			$value = array('supplier_id' => $row->supplier_id, 'supplier_name' => $row->supplier_name,'contact_number'=>$row->contact_number,'branch_id'=>$row->branch_id,'branch_name'=>$row->branch_name,'subtotal'=>$row->subtotal,'discount_percent'=>$row->discount_percent,'discount'=>$row->discount,'vat_percent'=>$row->vat_percent,'vat_amt'=>$row->vat_amt,'grand_total'=>$row->grand_total,'validity'=>$row->validity,'payment_term'=>$row->payment_term,'general_term'=>$row->general_term,'delivery_term'=>$row->delivery_term,'reference'=>$row->reference,'project'=>$row->project,'currency'=>$row->currency_abbr);
		}
		echo json_encode($value);
    }

	function get_quote_items_for_po(){
		
		$quotation_id = $this->input->post('quotation_id');
		$this->load->model('Purchase_Model');
		$data['records2']=$this->Purchase_Model->get_pur_qtn_tr_by_id($quotation_id);
		$this->load->view('ajax/purchase_quote_items_for_po',$data);
	}
	function ajax_get_po_info(){
        $value = array();
		$po_id = $this->input->post('po_id');
        $po_type = $this->input->post('po_type');

		$this->load->model('Purchase_Model');
        if( $po_type=="direct"){
            $data['records'] = $this->Purchase_Model->get_po_master_by_id_for_direct($po_id);

        }else{
            $data['records'] = $this->Purchase_Model->get_po_master_by_id($po_id);
        }
		foreach ($data['records'] as $row) {
			//$value = array('branch_name'=>$row->branch_name,'branch_id'=>$row->branch_id,'supplier_id' => $row->supplier_id,'supplier_contact' => $row->contact_number, 'supplier_name' => $row->supplier_name,'subtotal'=>$row->sub_total,'discount_percent'=>$row->discount_percent,'discount'=>$row->discount,'vat_percent'=>$row->vat_percent,'vat_amt'=>$row->vat_amt,'grand_total'=>$row->grand_total);
            $other_charge = ($row->trans_charge ?? 0) + ($row->cust_charge ?? 0) + ($row->add_charge ?? 0);
            //$total_beforevat = ($row->sub_total ?? 0) - ($row->discount ?? 0) + $other_charge;

                $value = array(
                    'branch_name'       => $row->branch_name,
                    'branch_id'         => $row->branch_id,
                    'supplier_id'       => $row->supplier_id,
                    'supplier_contact'  => $row->contact_number,
                    'supplier_name'     => $row->supplier_name,
                    'subtotal'          => $row->sub_total,
                    'discount_percent'  => $row->discount_percent,
                    'discount'          => $row->discount,
                    'vat_percent'       => $row->vat_percent,
                    'vat_amt'           => $row->vat_amt,
                    'grand_total'       => $row->grand_total,
                    'other_charge'      => $other_charge,       // sum of trans, freight, other
                    'total_beforevat'   => $row->total_beforevat,
                     'conversion_rate'   => $row->conversion_rate,
                    'base_currency_grand_total'       => $row->base_currency_grand_total    
                );

        }
		echo json_encode($value);
    }
	function get_po_items_for_grn(){

        $this->load->model('Purchase_Model');
		$this->load->model('Sales_model');
		$this->load->model('Setup_model');

		$po_id                          = $this->input->post('po_id');
		
		$data['active_units']           = $this->Setup_model->get_active_unit_list();	
		//$data['approved_quotations']    = $this->Sales_model->get_approved_quotation_list();
       // $received_data = $this->Purchase_Model->get_received_qty_by_poid($po_id);
        
        $data['approved_quotations']    = $this->Purchase_Model->get_quotation_list();
		$data['records2']               = $this->Purchase_Model->get_po_tr_by_id($po_id);
		$this->load->view('ajax/purchase_po_items_for_grn',$data);
	}
	function add_new_customer(){
		$this->load->model('Company_model');
		$data['customer_code'] = $this->Company_model->generate_customer_code();
        $data['branch_list']=$this->Company_model->get_all_branches();
		$this->load->view('ajax/add_customer_modal', $data);

	}

    public function save_customer_ajax() 
    {
        // Validate required fields
        $this->load->library('form_validation');
        $this->form_validation->set_rules('supplier_name', 'Supplier Name', 'required');
        if ($this->form_validation->run() == FALSE) {
            $errors = validation_errors();
            if ($this->input->is_ajax_request()) {
                echo json_encode(['success' => false, 'message' => $errors]);
                return;
            }
            $this->session->set_flashdata('error', $errors);
            redirect('Company/add_supplier');
            return;
        }

        // Ensure Company_model is loaded
        $this->load->model('Company_model');

        // Handle Trade License File Upload
        $trade_license_file = '';
        $trade_license_type = '';

        if (!empty($_FILES['trade_license_file']['name'])) {
            $config['upload_path']   = './public/supplier/';
            $config['allowed_types'] = 'pdf|jpg|jpeg|png|doc|docx';
            $config['max_size']      = 2048; // 2MB
            $config['encrypt_name']  = TRUE;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('trade_license_file')) {
                $upload_data = $this->upload->data();
                $trade_license_file = $upload_data['file_name'];
                $trade_license_type = $upload_data['file_type'];
            } else {
                $err = $this->upload->display_errors();
                if ($this->input->is_ajax_request()) {
                    echo json_encode(['success' => false, 'message' => $err]);
                    return;
                }
                $this->session->set_flashdata('error', $err);
                redirect('Company/add_supplier');
            }
        }

        // Build main supplier data array
        $data = array(
            'branch_id'             => $this->input->post('branch'),
            'supplier_code'         => $this->input->post('supplier_code'),
            'supplier_name'         => $this->input->post('supplier_name'),
            'supplier_type'         => $this->input->post('supplier_type'),
            'trn_no'                => $this->input->post('trn_no'),
            'remarks'               => "Remarks",
            'website'               => $this->input->post('company_website'),
            'supplier_email'        => $this->input->post('supplier_email'),
            'contact_number'        => $this->input->post('supplier_contact'),
            'billing_address'       => $this->input->post('billing_address'),
            'billing_city'          => $this->input->post('billing_city'),
            'billing_state'         => $this->input->post('billing_state'),
            'billing_country'       => $this->input->post('billing_country'),
            'billing_po_box'        => $this->input->post('billing_pobox'),
            'shipping_address'      => $this->input->post('shipping_address'),
            'shipping_city'         => $this->input->post('shipping_city'),
            'shipping_state'        => $this->input->post('shipping_state'),
            'shipping_country'      => $this->input->post('shipping_country'),
            'shipping_po_box'       => $this->input->post('shipping_pobox'),
            'active'                => 1,
            'created_by'            => $this->session->userdata('user_id'),
            'created_date'          => date('Y-m-d H:i:s'),
            'bank_name'             => $this->input->post('bank_name'),
            'bank_account'          => $this->input->post('account_no'),
            'bank_branch'           => $this->input->post('branch_name'),
            'bank_IBAN'             => $this->input->post('iban'),
            'bank_swift'            => $this->input->post('swift'),
            'intermidiate_Bname'    => $this->input->post('inter_bank_name'),
            'intermidiate_Bacc'     => $this->input->post('inter_account_no'),
            'intermidiate_Bbranch'  => $this->input->post('inter_branch'),
            'intermidiate_IBAN'     => $this->input->post('inter_iban'),
            'intermidiate_swift'    => $this->input->post('inter_swift'),
            'trade_licence_no'      => $this->input->post('trade_license_no'),
            'trl_issued_date'       => $this->input->post('issued_date'),
            'trl_expire_date'       => $this->input->post('expiry_date'),
            'tr_file'               => $trade_license_file,
            'tr_file_type'          => $trade_license_type,
            'currency_id'           => $this->input->post('currency'),
            'created_on'            => date('Y-m-d H:i:s')
        );

        $exists = $this->db
                ->where('supplier_code',$data['supplier_code'])
                ->count_all_results('supplier_master');

        if($exists>0){

            echo json_encode([
                'success'=>false,
                'message'=>'Supplier code already exists'
            ]);

            return;
        }

        $supplier_id = $this->Company_model->insert_supplier($data);

        // ✅ Create General Ledger Entry for Supplier
        if ($supplier_id) {
            $grp_no = 29; // Supplier group number
            $supplier_name = $this->input->post('supplier_name');
            $supplier_code = $this->input->post('supplier_code');

            $ledger_data = array(
                'account_name'     => $supplier_name . ' ' . $supplier_code,
                'group_no'         => $grp_no,
                'supplier_id'      => $supplier_id,
                'opening_bal_type' => 'Dr',
            );

            $this->db->insert('general_ledger', $ledger_data);
            $ledger_id = $this->db->insert_id();

            // 🔄 Optional: update supplier with ledger_id if needed
            // $this->db->update('suppliers', ['ledger_id' => $ledger_id], ['id' => $supplier_id]);
        }

        // ✅ Insert contact persons if any
        $contact_names  = $this->input->post('contact_person');
        $contact_phones = $this->input->post('contact_mobile');
        $contact_emails = $this->input->post('contact_email');

        if (!empty($contact_names)) {
            $contacts_data = [];
            for ($i = 0; $i < count($contact_names); $i++) {
                if (!empty($contact_names[$i])) {
                    $contacts_data[] = [
                        'supplier_id'   => $supplier_id,
                        'contact_name'  => $contact_names[$i],
                        'contact_phone' => $contact_phones[$i] ?? '',
                        'contact_email' => $contact_emails[$i] ?? '',
                    ];
                }
            }

            if (!empty($contacts_data)) {
                $this->Company_model->insert_supplier_contacts($contacts_data);
            }
        }

        $this->session->set_flashdata('success', 'Supplier and ledger saved successfully.');
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => true, 'supplier_id' => $supplier_id]);
            return;
        }
        redirect('Company/list_supplier');
    }

public function load_customer_dropdown_html() {
    $this->load->model('Company_model');
    $customer_list = $this->Company_model->get_all_customer_list();

    // Get max customer_id (assumes higher ID = latest customer)
    $last_customer_id = 0;
    if (!empty($customer_list)) {
        $last_customer_id = max(array_column($customer_list, 'customer_id'));
    }

    $html = '<select name="customer_id" class="form-control select2" required>';
    $html .= '<option value="">-- Select Customer --</option>';

    foreach ($customer_list as $c) {
        $selected = ($c->customer_id == $last_customer_id) ? 'selected' : '';
        $html .= '<option value="' . $c->customer_id . '" ' . $selected . '>' .
                 $c->customer_name . ' (' . $c->customer_code . ') => ' . $c->contact_number .
                 '</option>';
    }

    $html .= '</select>';

    echo $html;
}
function ajax_get_supplier_accountId_from_po()
	{
		$po_id = $this->input->post('po_id');

		$this->load->model('Purchase_Model');
		$data['records1'] = $this->Purchase_Model->get_po_details_by_id($po_id);
		foreach ($data['records1'] as $v) {
			$supplier_id = $v->supplier_id;
		}

		$this->load->model('Accounts_model');
		$data['accountId'] = $this->Accounts_model->get_supplier_account_Id($supplier_id);

		echo $data['accountId'];
	}
//     function get_invoice_list()
// 	{
// 		$value=array();
// 		$account_id = $this->input->post('account_id');
// 		$data['records1']='';
// 		$this->load->model('Ajax_model');
// 		$data['record'] = $this->Ajax_model->get_general_ledger_list_by_id($account_id);
// 		foreach ($data['record'] as $v) {
// 			$customer_id = $v->customer_id;
// 			$this->load->model('Sales_model');
// 			$data['records1']=$this->Sales_model->get_debt_invoice_list($customer_id,$account_id);
//             //echo $this->db->last_query();exit();
// 		}

// 		$this->load->view('ajax/inv_list_debtors.php',$data);
//    }

function get_invoice_list()
	{
		$value=array();
		$account_id = $this->input->post('account_id');
        $data['records1'] = array();
		$this->load->model('Ajax_model');
		$data['record'] = $this->Ajax_model->get_general_ledger_list_by_id($account_id);
        if (!empty($data['record'])) {
            foreach ($data['record'] as $v) {
                $customer_id = $v->customer_id;
                $this->load->model('Sales_model');
                $data['records1'] = $this->Sales_model->get_debt_invoice_list($customer_id, $account_id);
                // if multiple records are expected you may want to merge results instead of overwriting
            }
        }

		$this->load->view('ajax/inv_list_debtors.php',$data);
    }

public function get_grn_list()
{
    $account_id = $this->input->post('supplier_id');

    if (empty($account_id)) {
        echo "Supplier ID is required.";
        return;
    }

    $this->load->model('Accounts_model');
    $supplier_id = $this->Accounts_model->get_supp_id_from_account_id($account_id);

    if (empty($supplier_id)) {
        echo "No supplier found for this account.";
        return;
    }
    

    $this->load->model('Sales_model');
    $data['records1'] = $this->Sales_model->get_grn_master_data($supplier_id, $account_id);
    $this->load->view('ajax/grn_list_payment_entry.php', $data);
}
 public function ajax_get_user_passport_info()
{
    $this->load->model('Hr_model');
    $employee_id = $this->input->post('user_id');

    if ($employee_id) {

        $data = $this->Hr_model->get_employee_passport_info($employee_id);

        if ($data) {
            echo json_encode([
                'user_code'       => $data->user_code ?? '',
                'passport_number' => $data->passport_number ?? '',
                'issue_date'      => !empty($data->passport_issue_date)
                                    ? date('Y-m-d', strtotime($data->passport_issue_date))
                                    : '',
                'expiry_date'     => !empty($data->passport_expiry_date)
                                    ? date('Y-m-d', strtotime($data->passport_expiry_date))
                                    : ''
            ]);
        } else {
            echo json_encode([]);
        }
    } else {
        echo json_encode([]);
    }
}

function delete_record()
    	{
		$this->load->model('Ajax_model');
		$res = $this->Ajax_model->delete_record();
		
			if($res)
				echo 1;
			else 
				echo 0;
    	}

function ajax_get_cust_accountId_from_cust_id()
    {
        $customer_id = $this->input->post('customer_id');
	
		$this->load->model('Sales_model');
		
		$this->load->model('Accounts_model');
		$data['accountId']=$this->Accounts_model->get_cust_account_Id($customer_id);
		
		echo $data['accountId'];
    }
	
public function get_mi_details_ajax()
{
    $mi_id = $this->input->post('mi_id');

    $this->load->model('Ajax_model');

    $data = $this->Ajax_model->get_mi_details($mi_id);

    echo json_encode($data);
}

public function get_mi_items_ajax()
{
    $mi_id = $this->input->post('mi_id');

    $this->load->model('Ajax_model');

    $items = $this->Ajax_model->get_mi_items($mi_id);

    echo json_encode($items);
}
function delete_amc_quotation()
    	{
		$this->load->model('Ajax_model');
		$res = $this->Ajax_model->delete_amc_quotation();
		if($res)
			echo 1;
		else 
			echo 0;
    	}

public function ajax_get_amc_quotation_info()
{
    $qid = $this->input->post('qid');
    if(empty($qid)) {
        echo json_encode(['status'=>'error','message'=>'Quotation ID missing']);
        return;
    }

    $this->load->model(['Setup_model','Amc_model','Sales_model']);

    // Generate next invoice code
    $prefix = 'STK'.date('y');
    $num = $this->Setup_model->get_next_code($prefix,'invoice_code','invoice_master',8) + 1;
    $digit = sprintf("%04d", $num);
    $catalyst_ref_no = $prefix.date('m').$digit;

    // Fetch quotation master
    $records1 = $this->Amc_model->get_quotation_master_by_id($qid);
    if(empty($records1)) {
        echo json_encode(['status'=>'error','message'=>"No quotation found for QID $qid"]);
        return;
    }

    // Get the latest revision
    $version = $records1[0]->revision ?? 0;

    // Fetch quotation line items and unit records
    $records2 = $this->Amc_model->get_amc_quotation_balance_tr_by_id($qid, $version) ?: [];
    // $unit_records = $this->Sales_model->get_units() ?: [];

     $sla_records = $this->Amc_model->get_quotation_sla_by_id($qid);
    $annexure_records = $this->Amc_model->get_quotation_annexure_by_id($qid);

    // Optional: fetch currency list if your view needs it
    $currency_list = $this->Setup_model->get_currency_list() ?: [];

    // Pass all data to the view
    $data = [
        'catalyst_ref_no' => $catalyst_ref_no,
        'records1' => $records1,
        'records2' => $records2,
        // 'unit_records' => $unit_records,
        'currency_list' => $currency_list,
        'sla_records'       => $sla_records,
        'annexure_records'  => $annexure_records
    ];

    $this->load->view('amc/amc_quote_details', $data);
}



   function ajax_get_cust_accountId_from_quote()
{
    $qid = $this->input->post('qid');

    $this->load->model('Sales_Model');
    $data['records1'] = $this->Sales_Model->get_quotation_master_by_id($qid);

    // DEBUG: check what you got
    // echo '<pre>'; print_r($data); exit;

    $customer_id = $data['records1'][0]->customer_id ?? 0;

    $this->load->model('Accounts_model');
    $data['accountId'] = $this->Accounts_model->get_cust_account_Id($customer_id);

    echo json_encode($data);  // send JSON back to JS for debugging
}

 public function check_duplicate_exist2()
    {
        $table = $this->input->post('table_name');
        $col1  = $this->input->post('column_name1');
        $val1  = $this->input->post('post_id1');
        $col2  = $this->input->post('column_name2');
        $val2  = $this->input->post('post_id2');

        $this->db->where($col1, $val1);
        $this->db->where($col2, $val2);
        $query = $this->db->get($table);

        echo $query->num_rows(); // 🔥 IMPORTANT
    }

    function get_reco_list()
{
    $account_id = $this->input->post('account_id');
    $from_date = $this->input->post('from_date');
    $to_date = $this->input->post('to_date');

    $this->load->model('Accounts_model');
    $data['records'] = $this->Accounts_model->get_reco_list($account_id, $from_date, $to_date);
// echo '<pre>';
// print_r($_POST);
// exit;

    $this->load->view('ajax/reco_list.php', $data);
}
function ajax_get_supplier_info(){
        $value = array();
		$supplier_id = $this->input->post('supplier_id');

		$this->load->model('Setup_model');
		$row = $this->Setup_model->get_supplier_by_id($supplier_id);
		echo json_encode($row);
    }    

	function add_new_supplier(){
		$this->load->model('Company_model');
        $data['supplier_code']      = $this->Company_model->generate_supplier_code();          
        $data['branch_list']        = $this->Company_model->get_all_branches();
        $this->load->model('Currency_model');
        $data['currency_list']      = $this->Currency_model->get_all_currency_list();
		$this->load->view('ajax/add_supplier_modal', $data);        
	}    

    ///////////////////////////////////////////COMMISSION SETUP START//////////////////////////////////////////

    public function ajax_get_invoice_details()
    {
        $invoice_id=$this->input->post('invoice_id');
        $this->load->model('Hr_model');
        $row=$this->Hr_model->get_invoice_details($invoice_id);
        echo json_encode($row);
    }

    public function ajax_get_sales_rep_details()
    {
        $sales_rep_id=$this->input->post('sales_rep_id');
        $this->load->model('Hr_model');
        $row=$this->Hr_model->get_sales_rep_details($sales_rep_id);
        echo json_encode($row);
    }

    ///////////////////////////////////////////COMMISSION SETUP ENDS//////////////////////////////////////////


function popup_materials(){
    $this->load->model('Setup_model');
    $id = $_POST['id'];
    $rows = $this->Setup_model->get_rawmaterials($id);

    // build HTML content
   $html = '<table class="table table-bordered">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Cost</th>
                    <th>Unit</th>
                </tr>
            </thead>
            <tbody>';
    if(!empty($rows)){
        foreach ($rows as $row) {
            $html.= '<tr>
                        <td>'.$row->material_code.'</td>
                        <td>'.$row->material_name.'</td>
                        <td>'.$row->quantity_required.'</td>
                        <td>'.$row->cost.'</td>
                        <td>'.$row->unit.'</td>
                    </tr>';
        }
    }else{
         $html.= '<tr>
                    <td colspan="5">No data</td>
                   </tr>';
    }

    $html.= '</tbody></table>';

    echo json_encode([
        "title" => "Raw Materials",
        "html"  => $html
    ]);
    exit;

}

}

