<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Purchase extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        $this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
        $this->output->set_header("Pragma: no-cache");
        $this->load->model('Setup_model');
        $this->load->helper('menu_helper');
        $this->load->model('Purchase_Model');
        $this->load->model('Item_model');
        $this->load->model('Stock_model');
        $this->load->model('Sales_model');
        // $this->load->helper('menu_helper');

    }

    /////////////////////Direct RFQ Start  ////////////////////////
    function add_direct_rfq()
    {
        $this->load->model('Item_model');
        $this->load->model('Company_model');
        $data['title'] = 'Request For Quotation(RFQ)-Direct';


        $prifix                     = 'ALA/RFQ/';
        $num                        = $this->Setup_model->get_next_code($prifix, 'rfq_code', 'purchase_rfq', 12) + 1;
        $digit                      = sprintf("%1$05d", $num);
        $data['Code']               = $prifix . date('y') . '/' . $digit;
        $data['branch_records']     = $this->Company_model->get_all_branches();
        $data['supplier_records']   = $this->Setup_model->get_active_supplier_list();

        $data['active_items']       = $this->Setup_model->get_active_item_list();
        $data['active_units']       = $this->Setup_model->get_active_unit_list();
        $data['main_content']       = 'purchase/rfq_direct_add.php';
        $this->load->view('includes/template.php', $data);
    }

    public function add_direct_rfq_records()
    {
        $action = $this->input->post('submit_action'); // "save" or "create_quote"
        $this->load->model('Purchase_Model');

        $id = $this->Purchase_Model->add_direct_rfq_records();

        if ($id) {
            $this->session->set_flashdata('success', 'Data Saved Successfully..');

            if ($action === 'create_quote') {
                // redirect to add_quote_from_supplier with the new rfq id
                redirect('Purchase/add_quote_from_supplier/' . $id);
            } else {
                redirect('Purchase/list_direct_rfq');
            }
        } else {
            $this->session->set_flashdata('error', 'Data Not Saveded..');
            redirect('Purchase/add_direct_rfq');
        }
    }

    function list_direct_rfq()
    {
        $data['title'] = 'Request For Quotation(RFQ)';
        $this->load->model('Purchase_Model');
        $data['records'] = $this->Purchase_Model->get_RFQ_list();
        $data['main_content'] = 'purchase/rfq_direct_list.php';
        $this->load->view('includes/template.php', $data);
    }

    function delete_rfq()
    {
        $rfq_id = $this->uri->segment('3');

        $this->load->model('Purchase_Model');
        $res = $this->Purchase_Model->delete_rfq($rfq_id);
        redirect('Purchase/list_direct_rfq');
    }

    function edit_rfq()
    {
        $this->load->model('Company_model');
        // if(!has_access($user,'Purchase/list_rfq','E')){
        //     $data['title'] = 'Access Denied';
        //     $data['main_content']='errors/access_control.php';
        // }
        // else{
        $this->load->model('Setup_model');
        $rfq_id = $this->uri->segment('3');
        $data['view_only'] = $this->uri->segment('4');

        if ($data['view_only'] == 0) {
            $data['title'] = 'Edit RFQ';
        } else {
            $data['title'] = 'View RFQ';
        }
        $data['branch_records']     = $this->Company_model->get_all_branches();
        $data['active_items']       = $this->Setup_model->get_active_item_list();
        $data['active_units']       = $this->Setup_model->get_active_unit_list();
        $data['supplier_records']   = $this->Setup_model->get_all_supplier_list();
        $data['records1']           = $this->Purchase_Model->get_purchase_rfq_by_id($rfq_id);
        $data['records2']           = $this->Purchase_Model->get_purchase_rfq_tr($rfq_id);
        $data['main_content']       = 'purchase/rfq_direct_edit.php';
        // }
        $this->load->view('includes/template.php', $data);
    }

    function update_rfq()
    {
        $this->Purchase_Model->update_rfq_records();
        $this->session->set_flashdata('success', 'Data Saved Successfully..');
        redirect('Purchase/list_direct_rfq');
    }

    ///////////// Supplier Quotation////////////////////
    public function add_quote_from_supplier($rfq_id = null)
    {
        $this->load->model('Setup_model');
        $this->load->model('Purchase_Model');

        $data['title']              = 'Quote From Supplier';
        $prifix                     = 'AVE/SQT/';
        $num                        = $this->Setup_model->get_next_code($prifix, 'quotation_code', 'purchase_quotation_master', 12) + 1;
        $digit                      = sprintf("%1$04d", $num);
        $data['Code']               = $prifix . date("y") . '/' . $digit;
        $data['records']            = $this->Purchase_Model->get_RFQ_list('direct');
        $data['purchase_requests'] = $this->Purchase_Model->get_PR_list(); // <--- added
        $data['supplier_records']   = $this->Setup_model->get_active_supplier_list();

        // pass selected rfq id to the view
        $data['selected_rfq_id']    = $rfq_id;

        $data['main_content']       = 'purchase/quotation_add.php';
        $this->load->view('includes/template.php', $data);
    }

    // application/controllers/Purchase.php
    public function add_purchase_quotation_records()
    {
        $action = $this->input->post('submit_action'); // 'save' or 'create_po'
        $this->load->model('Purchase_Model');

        $quotation_id = $this->Purchase_Model->add_purchase_quotation();

        if ($quotation_id) {
            $this->session->set_flashdata('success', 'Data Saved Successfully..');

            if ($action === 'create_po') {
                // Redirect to add_purchase_order and pass the new quotation id
                redirect('Purchase/add_purchase_order/' . $quotation_id);
            } else {
                redirect('Purchase/purchase_quotation_list');
            }
        } else {
            $this->session->set_flashdata('error', 'Data Not Saved..');
            redirect('Purchase/add_quote_from_supplier');
        }
    }

    function purchase_quotation_list()
    {
        $data['title'] = 'Purchase Quotation';
        $this->load->model('Purchase_Model');
        $data['records'] = $this->Purchase_Model->get_quotation_list();

        $data['main_content'] = 'purchase/quotation_list.php';
        $this->load->view('includes/template.php', $data);
    }

    function edit_quotation()
    {

        $this->load->model('Setup_model');
        $this->load->model('Company_model');
        $quotation_id           = $this->uri->segment('3');
        $data['view_only']      = $this->uri->segment('4');

        if ($data['view_only'] == 0) {
            $data['title'] = 'Edit Quotation';
        } else {
            $data['title'] = 'View Quotation';
        }

        //master
        $data['records1']           = $this->Purchase_Model->get_pur_qtn_master_by_id($quotation_id);
        $data['branch_records']     = $this->Company_model->get_all_branches();
        // $data['supplier_records']     = $this->Company_model->get_supplier_by_branch($data['records1'][0]->branch_id);	


        $data['records2']           = $this->Purchase_Model->get_pur_qtn_tr_by_id($quotation_id);

        $data['quote_doc']          = $this->Purchase_Model->get_quote_doc($quotation_id, "Quote File");
        $data['main_content']       = 'purchase/quotation_edit.php';
        // }
        $this->load->view('includes/template.php', $data);
    }

    function update_purchase_quotation()
    {
        $create_revision = $this->input->post('create_revision');
        if ($create_revision) {
            $this->Purchase_Model->create_revision_purchase_quotation();
        } else {
            $this->Purchase_Model->update_purchase_quotation();
        }

        $this->session->set_flashdata('success', 'Data Saved Successfully..');
        redirect('Purchase/purchase_quotation_list');
    }

    function purchase_quotation_details()
    {
        $data['title'] = 'Purchase Quotation';
        $quotation_id = $this->uri->segment('3');
        $version = $this->uri->segment('4');
        $data['edit_flag'] = $this->uri->segment('5');

        $this->load->model('Setup_Model');
        $data['item_records'] = $this->Setup_Model->get_active_item_list();
        $data['unit_records'] = $this->Setup_Model->get_unit_list();
        $data['supplier_records'] = $this->Setup_Model->get_supplier_list();

        $this->load->model('Purchase_Model');
        $data['records1'] = $this->Purchase_Model->get_pruchase_quotation_by_id($quotation_id);
        $data['records2'] = $this->Purchase_Model->get_pruchase_quotation_tr_by_id($quotation_id, $version);
        $data['main_content'] = 'purchase/quotation_details.php';
        $this->load->view('includes/template.php', $data);
    }

    function print_quote()
    {
        $this->load->model('Company_model');
        $user             = $this->session->userdata('user_id');

        $quotation_id     = $this->uri->segment(3);
        $data['quote_tr'] = $this->Purchase_Model->get_pur_qtn_tr_by_id($quotation_id);
        $data['quote']    = $this->Purchase_Model->get_pur_qtn_master_by_id($quotation_id);

        $branch_id        = $data['quote'][0]->branch_id;
        $branch_data      = $this->Company_model->get_branch_by_id($branch_id);
        $data['branch_header'] = $branch_data->branch_header;
        $data['branch_footer'] = $branch_data->branch_footer;

        $this->load->view('purchase/print/quotation_print.php', $data);
        // }
    }
    function delete_quote($quote_id)
    {
        // $quote_id = $this->uri->segment('3');	
        $this->load->model('Purchase_Model');
        $res = $this->Purchase_Model->delete_quote($quote_id);
        redirect('Purchase/purchase_quotation_list');
    }
    public function delete_quote_protected()
    {
        $quotation_id = $this->input->post('quotation_id');
        $password = $this->input->post('password');
        $correct_password = 'abc123';

        if ($password === $correct_password) {
            $this->load->model('Purchase_model');
            $this->Purchase_model->delete_quote($quotation_id);
            echo 'success';
        } else {
            echo 'error';
        }
    }
    function accept_purchase_quotation()
    {
        $data['title'] = 'Purchase Quotation';
        $qid = $this->uri->segment('3');
        $version = $this->uri->segment('4');
        $this->load->model('Purchase_Model');
        $this->Purchase_Model->accept_purchase_quotation($qid, $version);

        $this->session->set_flashdata('success', 'Data Saved Successfully..');
        redirect('Purchase/purchase_quotation_list');
    }

    /////////////////////// Purchase Order////////////////////////////////////////////////
    public function add_purchase_order($selected_quotation_id = null)
    {
        $user = $this->session->userdata('user_id');

        if (!has_access($user,'Purchase/purchase_order_list','A'))
        {
            $data['title'] = 'Access Denied';
            $data['main_content'] = 'errors/access_control.php';
            $this->load->view('includes/template',$data);
            return;
        }
        $data['title'] = 'Purchase Order';
        $prifix = 'ALA/POD/';
        $this->load->model('Setup_model');
        $num = $this->Setup_model->get_next_code($prifix, 'po_code', 'purchase_order_master', 12) + 1;
        $digit = sprintf("%1$04d", $num);
        $data['Code'] = $prifix . date("y") . '/' . $digit;

        $this->load->model('Purchase_Model');
        $data['records'] = $this->Purchase_Model->get_quotation_list();

        // pass selected quotation id to view
        $data['selected_quotation_id'] = $selected_quotation_id;
        $this->load->model('Hr_model');
        $data['employees'] = $this->Hr_model->get_employee_list();
        $data['main_content'] = 'purchase/po_add.php';
        $this->load->view('includes/template.php', $data);
    }

    //   function add_po_records()
    // {
    //     $data['title'] = 'Purchase Order';
    //     $this->load->model('Purchase_Model');

    //     // Save the PO record
    //     $po_id = $this->Purchase_Model->add_purchase_order(); // <-- make sure this returns the inserted PO ID

    //     $this->session->set_flashdata('success', 'PO Saved Successfully.');

    //     // Check which button was clicked
    //     $action = $this->input->post('action');

    //     if ($action === 'save_and_grn') {
    //         // Redirect to add_grn with the new PO ID
    //         redirect('Purchase/add_grn/'.$po_id);
    //     } else {
    //         // Default redirect to PO list
    //         redirect('Purchase/purchase_order_list');
    //     }
    // }

    public function add_po_records()
    {
        $data['title'] = 'Purchase Order';
        $this->load->model('Purchase_Model');

        // Save PO
        $po_id = $this->Purchase_Model->add_purchase_order();

        $this->session->set_flashdata('success', 'PO Saved Successfully.');

        // Redirect to PO list
        redirect('Purchase/purchase_order_list');
    }

    function purchase_order_list()
    {
        $user = $this->session->userdata('user_id');

        if (!has_view_access($user,'Purchase/purchase_order_list'))
        {
            $data['title'] = 'Access Denied';
            $data['main_content'] = 'errors/access_control.php';
            $this->load->view('includes/template',$data);
            return;
        }
        $data['title'] = 'Purchase Order List';
        $this->load->model('Purchase_Model');
        $data['records'] = $this->Purchase_Model->get_po_list();
        // echo $this->db->last_query();exit();
        $data['main_content'] = 'purchase/po_list.php';
        $this->load->view('includes/template.php', $data);
    }

    /*function print_po(){
    $user = $this->session->userdata('user_id');
        
    $po_id = $this->uri->segment('3');
    $po_type = $this->uri->segment('4');//2 direct 1 quotation 
    $data['po_tr'] = $this->Purchase_Model->get_po_tr_by_id($po_id);	
    if($po_type==1)
        $data['po'] = $this->Purchase_Model->get_po_master_by_id($po_id);
    elseif($po_type==2)
        $data['po'] = $this->Purchase_Model->get_po_master_by_id($po_id);


    // $data['branch_id']          = $enquiry_data['branch_id'];
    // $data['branch_name']        = $enquiry_data['branch_name'];
    // $data['branch_header']      = $enquiry_data['branch_header'];
    // $data['branch_footer']      = $enquiry_data['branch_footer'];
    // $data['branch_contact']     = $enquiry_data['branch_contact'];
    // $data['branch_email']       = $enquiry_data['branch_email'];
    // $data['branch_location']    = $enquiry_data['branch_location'];
    // $data['branch_address']     = $enquiry_data['branch_address'];
    // $data['branch_website']     = $enquiry_data['branch_web'];

    $this->load->view('purchase/print/po_print.php',$data);
              
    }*/

    public function print_po($po_id, $po_type)
    {
        $user = $this->session->userdata('user_id');

        if (!has_view_access($user,'Purchase/purchase_order_list'))
        {
            $data['title'] = 'Access Denied';
            $data['main_content'] = 'errors/access_control.php';
            $this->load->view('includes/template',$data);
            return;
        }

        $this->load->model('Company_model');
        $user = $this->session->userdata('user_id');

        // 1. Get PO Master + Products based on type
        if ($po_type == 1) { // PO via quotation
            $po_master = $this->Purchase_Model->get_po_master_by_id($po_id);
        } elseif ($po_type == 2) { // Direct PO
            $po_master = $this->Purchase_Model->get_po_direct_master_by_id($po_id);
        }
        // echo "<pre>";print_r($po_master);exit;
        $po_tr = $this->Purchase_Model->get_po_tr_by_id($po_id);
        $this->load->model('Company_model');
        $prepared_by_id = $po_master[0]->prepared_by ?? null;
        $checked_by_id = $po_master[0]->checked_by ?? null;
        $approved_by_id = $po_master[0]->approved_by ?? null;

        $prepared_by_name = '';
        $checked_by_name = '';
        $approved_by_name = '';
        $prepared_signature = '';
        $checked_signature = '';
        $approved_signature = '';

        if (!empty($prepared_by_id)) {
            $prepared_emp = $this->Company_model->get_employee_by_id($prepared_by_id);
            $prepared_by_name = $prepared_emp->employee_name ?? '';
            $prepared_signature = $prepared_emp->signature_file ?? '';
        }
        if (!empty($checked_by_id)) {
            $checked_emp = $this->Company_model->get_employee_by_id($checked_by_id);
            $checked_by_name = $checked_emp->employee_name ?? '';
            $checked_signature = $checked_emp->signature_file ?? '';
        }
        if (!empty($approved_by_id)) {
            $approved_emp = $this->Company_model->get_employee_by_id($approved_by_id);
            $approved_by_name = $approved_emp->employee_name ?? '';
            $approved_signature = $approved_emp->signature_file ?? '';
        }
        // 2. Get Branch Details (join branch_master in model if needed)
        $branch_id = $po_master[0]->branch_id;
        $branch = $this->Company_model->get_branch_by_id($branch_id); // create this model function if not exists

        // 3. Prepare data for view
        $data['po'] = $po_master[0];
        $data['po_tr'] = $po_tr;

        $data['branch_id']       = $branch->branch_id;
        $data['branch_name']     = $branch->branch_name;
        $data['branch_header']   = $branch->branch_header;
        $data['branch_footer']   = $branch->branch_footer;
        $data['branch_logo']     = $branch->branch_logo;
        $data['branch_address']  = $branch->branch_address;
        $data['branch_location'] = $branch->branch_location;
        $data['branch_trn']      = $branch->branch_trn;
        $data['branch_web']      = $branch->branch_web;
        $data['branch_email']    = $branch->branch_email;
        $data['branch_contact']  = $branch->branch_contact;
        $data['branch_manager']  = $branch->branch_manager;
        $data['branch_stamp']  = $branch->branch_stamp;

        $data['prepared_by_name'] = $prepared_by_name;
        $data['checked_by_name'] = $checked_by_name;
        $data['approved_by_name'] = $approved_by_name;
        $data['prepared_signature'] = $prepared_signature ?? '';
        $data['checked_signature'] = $checked_signature ?? '';
        $data['approved_signature'] = $approved_signature ?? '';

        // echo "<pre>";print_r($data);exit;

        // 4. Dompdf Config
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', realpath('C:/xampp/htdocs/aladel_erp/public/'));
        $dompdf = new \Dompdf\Dompdf($options);

        $data['headerPath'] = base_url(ltrim($data['branch_header'], '/'));
        $data['footerPath'] = base_url(ltrim($data['branch_footer'], '/'));
        // echo "<pre>";
        // print_r($data);exit;
        if ($po_type == 1) {
            $html = $this->load->view('purchase/print/po_print.php', $data, true);
        } elseif ($po_type == 2) {
            $html = $this->load->view('purchase/print/po_direct_print.php', $data, true);
        }


        // 6. Generate PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("purchase_order_$po_id.pdf", array("Attachment" => 0));
    }

    function approve_po()
    {
        $user = $this->session->userdata('user_id');

        if (!has_access($user,'Purchase/purchase_order_list','E'))
        {
            $data['title'] = 'Access Denied';
            $data['main_content'] = 'errors/access_control.php';
            $this->load->view('includes/template',$data);
            return;
        }
        $po_id = $this->uri->segment('3');
        $this->Purchase_Model->approve_purchase_order($po_id);
        $this->session->set_flashdata('success', 'Approved Successfully..');
        redirect('Purchase/purchase_order_list');
    }

    function edit_po()
    {
        $user = $this->session->userdata('user_id');

        if (!has_access($user,'Purchase/purchase_order_list','E'))
        {
            $data['title'] = 'Access Denied';
            $data['main_content'] = 'errors/access_control.php';
            $this->load->view('includes/template',$data);
            return;
        }

        $this->load->model('Setup_model');
        $this->load->model('Company_model');

        $po_id = $this->uri->segment('3');
        $po_type = $this->uri->segment('5');
        $data['view_only'] = $this->uri->segment('4');


        if ($data['view_only'] == 0) {
            $data['title'] = 'Edit Purchase Order';
        } else {
            $data['title'] = 'View Purchase Order';
        }
        if ($po_type == 1) {
            $data['records1'] = $this->Purchase_Model->get_po_master_by_id($po_id);
        } elseif ($po_type == 2) {
            $data['records1'] = $this->Purchase_Model->get_po_direct_master_by_id($po_id);
        }
        //echo $this->db->last_query();exit();
        $data['active_items']       = $this->Setup_model->get_active_item_list();
        $data['active_units']       = $this->Setup_model->get_active_unit_list();
        $data['branch_records']     = $this->Company_model->get_all_branches();
        $data['records2']           = $this->Purchase_Model->get_po_tr_by_id($po_id);

        $data['po_doc']             = $this->Purchase_Model->get_quote_doc($po_id, "PO File");
        $data['supplier_records']   = $this->Setup_model->get_active_supplier_list();

        $this->load->model('Hr_model');
        $data['employees'] = $this->Hr_model->get_employee_list();
        if ($po_type == 1) {
            $data['main_content'] = 'purchase/po_edit.php';
        } else {
            $data['main_content'] = 'purchase/po_direct_edit.php';
        }

        //  echo '<pre>';print_r($data);exit;
        // }
        $this->load->view('includes/template.php', $data);
    }

    function update_purchase_order()
    {
        $action = $this->input->post('action');

        if ($action == 'approve') {
            // Only Approve
            $po_id = $this->input->post('po_id');
            $approved_by = $this->session->userdata('user_id');

            $this->Purchase_Model->approve_purchase_order($po_id, $approved_by);

            $this->session->set_flashdata('success', 'PO Approved Successfully..');
        } else {
            // Normal Update
            $this->Purchase_Model->update_purchase_order();

            $this->session->set_flashdata('success', 'Data Saved Successfully..');
        }

        redirect('Purchase/purchase_order_list');
    }
    
    function add_PO_direct_from_reorder()
    {
        $this->load->model('Purchase_Model');
        $this->load->model('Company_model');
        $this->load->model('Setup_model');
        error_reporting(0);
        $data['title']              = 'Purchase Order-Stock';
        $prifix                     = 'ALA/POD/';
        $num                        = $this->Setup_model->get_next_code($prifix, 'po_code', 'purchase_order_master', 12) + 1;
        $digit                      = sprintf("%1$04d", $num);
        $data['Code']               = $prifix . date("y") . '/' . $digit;
        $data['branch_records']     = $this->Company_model->get_all_branches();
        $data['records']            = $this->Purchase_Model->get_RFQ_list('direct');
        $data['active_items']       = $this->Setup_model->get_active_item_list();
        $data['active_units']       = $this->Setup_model->get_active_unit_list();

        $data['supplier_records']   = $this->Setup_model->get_active_supplier_list();
        $data['reorder_list']       = $this->Stock_model->get_reorder_stock_for_PO();
        $data['prepared_by'] = $this->session->userdata('user_name');
        $this->load->model('Hr_model');
        $data['employees'] = $this->Hr_model->get_employee_list();
        $data['main_content']       = 'purchase/po_direct_add.php';
        $this->load->view('includes/template.php', $data);
    }

    function add_po_direct_records()
    {
        $data['title'] = 'Direct Purchase Order';
        $this->load->model('Purchase_Model');
        // Save the PO record
        $po_id = $this->Purchase_Model->add_purchase_order_direct(); // <-- make sure this returns the inserted PO ID
        $this->session->set_flashdata('success', 'PO Saved Successfully.');
        // Check which button was clicked
        $action = $this->input->post('action');
        if ($action === 'save_and_grn') {
            // Redirect to add_grn with the new PO ID
            redirect('Purchase/add_grn/' . $po_id);
        } else {
            // Default redirect to PO list
            redirect('Purchase/purchase_order_list');
        }
    }

    ///////////////// GRN ////////////////////////////////////
    function add_grn($po_id = null)
    {
        $this->load->model('Setup_model');
        $this->load->model('Purchase_Model');
        $this->load->model('Company_model');

        $data['title']           = 'Good Received Note';
        $prifix                  = 'AVE/GRN/';
        $num                     = $this->Setup_model->get_next_code($prifix, 'grn_code', 'purchase_grn_master', 12) + 1;
        $digit                   = sprintf("%1$04d", $num);
        $data['Code']            = $prifix . date("y") . '/' . $digit;

        $data['warehouse_list']  = $this->Setup_model->get_warehouse_list();
        $data['records']         = $this->Purchase_Model->get_approved_po_list();

        $data['supplier']        = $this->Purchase_Model->get_po_supplier_details($po_id);

        // Pass selected PO ID if coming from create GRN
        $data['selected_po_id']  = $po_id;

        $data['currency_list'] = $this->Company_model->get_currency_list();

        // =======================Accounts=============================
        $this->load->model('Accounts_model');
        $data['sundry_accounts1'] = $this->Accounts_model->get_general_ledger_by_group('Purchase Accounts');
        $data['sundry_accounts2'] = $this->Accounts_model->get_gen_ledger_creditors_records();
        $data['sundry_accounts3'] = $this->Accounts_model->get_all_general_ledger_accounts();

        $data['main_content'] = 'purchase/grn_add.php';
        $this->load->view('includes/template.php', $data);
    }

    function add_grn_records()
    {
        $this->Purchase_Model->add_grn_records();
        $this->session->set_flashdata('success', 'Data Saved Successfully..');
        redirect('Purchase/purchase_grn_list');
    }

    function purchase_grn_list()
    {
        $data['title'] = 'Purchase GRN List';
        $data['records'] = $this->Purchase_Model->get_grn_list();
        $data['main_content'] = 'purchase/grn_list.php';
        $this->load->view('includes/template.php', $data);
    }

    function print_grn()
    {
        $user = $this->session->userdata('user_id');
        // if(!has_view_access($user,'Purchase/purchase_grn_list')){
        //     $data['title'] = 'Access Denied';
        //     $data['main_content']='errors/access_control.php';
        //     $this->load->view('includes/template',$data);
        // }
        // else{
        $grn_id = $this->uri->segment('3');
        $data['grn_tr'] = $this->Purchase_Model->get_grn_tr_by_id($grn_id);
        $data['grn'] = $this->Purchase_Model->get_grn_master_by_id($grn_id);
        // echo '<pre>';print_r($data);exit;
        $this->load->view('purchase/print/grn_print.php', $data);


        // }
    }

    function print_grn_barcode()
    {
        $user = $this->session->userdata('user_id');
        // if(!has_view_access($user,'Purchase/purchase_grn_list')){
        //     $data['title'] = 'Access Denied';
        //     $data['main_content']='errors/access_control.php';
        //     $this->load->view('includes/template',$data);
        // }
        // else{
        $grn_id = $this->uri->segment('3');
        $data['grn_tr'] = $this->Purchase_Model->get_grn_tr_by_id($grn_id);
        $data['grn'] = $this->Purchase_Model->get_grn_master_by_id($grn_id);

        $this->load->view('print_grngrn_barcode_print.php', $data);


        // }
    }

    function delete_grn()
    {
        $grn_id = $this->input->post('grn_id');
        $this->load->model('Purchase_Model');
        $this->Purchase_Model->delete_grn($grn_id);
        redirect('Purchase/purchase_grn_list');
    }
    
    function direct_po()
    {
        $data['title'] = 'Direct Purchase Order';

        $prifix = 'AVE/POD/';
        $this->load->model('Setup_model');
        $num = $this->Setup_model->get_next_code($prifix, 'po_code', 'purchase_order_master', 12) + 1;
        $digit = sprintf("%1$04d", $num);
        $data['Code'] = $prifix . date("y") . '/' . $digit;

        $data['records'] = $this->Purchase_Model->get_quotation_list();
        $this->load->model('Setup_model');
        $this->load->model('Item_model');
        $data['active_items'] = $this->Setup_model->get_active_item_list();
        $data['active_units'] = $this->Setup_model->get_active_unit_list();


        $data['supplier_records'] = $this->Setup_model->get_active_supplier_list();
        $data['main_content'] = 'purchase/po_direct_add.php';
        $this->load->view('includes/template.php', $data);
    }

    function direct_quote()
    {
        $data['title'] = 'Direct Supplier Quote';
        $prifix = 'AVE/QTN/';
        $this->load->model('Setup_model');
        $num = $this->Setup_model->get_next_code($prifix, 'quotation_code', 'purchase_quotation_master', 12) + 1;
        $digit = sprintf("%1$04d", $num);
        $data['Code'] = $prifix . date("y") . '/' . $digit;

        $this->load->model('Setup_model');
        $this->load->model('Item_model');
        $data['active_items'] = $this->Setup_model->get_active_item_list();
        $data['active_units'] = $this->Setup_model->get_active_unit_list();

        $data['supplier_records'] = $this->Setup_model->get_active_supplier_list();
        $data['main_content'] = 'purchase/quote_direct_add.php';
        $this->load->view('includes/template.php', $data);
    }

    ///////////////////// PURCHASE REQUEST ////////////////////////

    //      public function add_pr_from_mi()
    // {
    //     $this->load->model('Item_model');
    //     $this->load->model('Company_model');
    //     $this->load->model('Project_model');
    //     $data['title'] = 'Purchase Request from Material Issue';

    //     $prifix = 'ALA/PR/';
    //     $num = $this->Setup_model->get_next_code($prifix, 'pr_code', 'purchase_requests', 12) + 1;
    //     $digit = sprintf("%1$05d", $num);
    //     $data['Code'] = $prifix . date('y') . '/' . $digit;

    //     $data['branch_records']   = $this->Company_model->get_all_branches();
    //     $data['supplier_records'] = $this->Setup_model->get_active_supplier_list();

    //     $data['active_units'] = $this->Setup_model->get_active_unit_list();

    //     $data['material_issues'] = $this->db->select('mi_id, mi_code')
    //                                         ->from('material_issue')
    //                                         ->where('status', 'Issued')
    //                                         ->get()
    //                                         ->result();

    //     $data['main_content'] = 'purchase/pr_from_mi_add.php';
    //     $this->load->view('includes/template.php', $data);
    // }

    public function add_pr_from_mi()
    {
        $this->load->model('Item_model');
        $this->load->model('Company_model');
        $this->load->model('Project_model');
        $data['title'] = 'Purchase Request from Material Issue';

        $prifix = 'ALA/PR/';
        $num = $this->Setup_model->get_next_code($prifix, 'pr_code', 'purchase_requests', 12) + 1;
        $digit = sprintf("%1$05d", $num);
        $data['Code'] = $prifix . date('y') . '/' . $digit;

        $data['branch_records']   = $this->Company_model->get_all_branches();
        $data['supplier_records'] = $this->Setup_model->get_active_supplier_list();

        $data['active_units'] = $this->Setup_model->get_active_unit_list();

        // $data['material_issues'] = $this->db->select('mi_id, mi_code')
        //                                     ->from('material_issue')
        //                                     ->where('status', 'Issued')
        //                                     ->get()
        //                                     ->result();

        $data['material_issues'] = $this->Purchase_Model->get_issued_mi_with_pending_qty();

        $data['main_content'] = 'purchase/pr_from_mi_add.php';
        $this->load->view('includes/template.php', $data);
    }
    public function pr_from_mi_list()
    {
        $this->load->model('Purchase_model');

        $data['title'] = 'PR From Material Issue List';
        $data['pr_list'] = $this->Purchase_model->get_pr_from_mi_list();

        $data['main_content'] = 'purchase/pr_from_mi_list.php';
        $this->load->view('includes/template.php', $data);
    }
    public function delete_pr($pr_id)
    {
        $this->db->where('pr_id', $pr_id)->delete('purchase_requests');
        $this->session->set_flashdata('success', 'Purchase Request deleted successfully.');
        redirect('Purchase/pr_from_mi_list');
    }
    public function edit_pr_from_mi($pr_id)
    {
        $this->load->model('Item_model');
        $this->load->model('Company_model');
        $this->load->model('Project_model');

        $data['title'] = 'Edit Purchase Request';

        $data['pr'] = $this->Purchase_Model->get_pr_by_id($pr_id);

        if (!$data['pr']) {
            $this->session->set_flashdata('error', 'Invalid Purchase Request ID.');
            redirect('Purchase/pr_list');
        }

        $data['pr_items'] = $this->Purchase_Model->get_pr_items_by_pr_id($pr_id);

        $data['branch_records']   = $this->Company_model->get_all_branches();
        $data['supplier_records'] = $this->Setup_model->get_active_supplier_list();
        $data['active_units']     = $this->Setup_model->get_active_unit_list();
        $data['material_issues']  = $this->Purchase_Model->get_issued_mi_with_pending_qty();

        $data['main_content'] = 'purchase/pr_from_mi_edit.php';
        $this->load->view('includes/template.php', $data);
    }


    public function update_pr_from_mi($pr_id)
    {
        $this->load->database();
        $this->load->library('session');

        $pr_data = [
            'pr_date'     => $this->input->post('pr_date'),
            'branch_id'   => $this->input->post('branch_id'),
            'supplier_id' => $this->input->post('supplier_id'),
            'mi_id'       => $this->input->post('mi_id') ?: NULL,
            'subject'     => $this->input->post('subject'),
            'project'     => $this->input->post('project'),
            'ref'         => $this->input->post('ref'),
            'remarks'     => $this->input->post('remarks'),
            'updated_by'  => $this->session->userdata('user_id'),
            'updated_at'  => date('Y-m-d H:i:s')
        ];

        $this->db->trans_start();

        $this->db->where('pr_id', $pr_id);
        $this->db->update('purchase_requests', $pr_data);

        $this->db->where('pr_id', $pr_id);
        $this->db->delete('purchase_request_items');


        $product_ids = $this->input->post('item_id');
        $units       = $this->input->post('unit');
        $quantities  = $this->input->post('quantity');

        if (!$product_ids || count($product_ids) == 0) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('error', 'At least one item is required.');
            redirect('Purchase/edit_pr_from_mi/' . $pr_id);
            return;
        }

        foreach ($product_ids as $key => $product_id) {

            if (!isset($quantities[$key]) || $quantities[$key] <= 0) {
                continue;
            }

            $item_data = [
                'pr_id'      => $pr_id,
                'product_id' => $product_id,
                'unit_id'    => $units[$key],
                'quantity'   => $quantities[$key]
            ];

            $this->db->insert('purchase_request_items', $item_data);

            /*
        // ---------- OPTIONAL: update MI pending qty ----------
        if ($this->input->post('mi_id')) {
            $this->db->set('pending_qty', 'pending_qty - '.$quantities[$key], FALSE);
            $this->db->where('mi_id', $this->input->post('mi_id'));
            $this->db->where('product_id', $product_id);
            $this->db->update('material_issue_items');
        }
        */
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Failed to update Purchase Request.');
        } else {
            $this->session->set_flashdata('success', 'Purchase Request updated successfully.');
        }

        redirect('Purchase/pr_from_mi_list');
    }



    public function save_pr_from_mi()
    {
        $this->load->database();
        $this->load->library('session');

        $pr_data = [
            'pr_code'     => $this->input->post('pr_code'),
            'pr_date'     => $this->input->post('pr_date'),
            'branch_id'   => $this->input->post('branch_id'),
            'supplier_id' => $this->input->post('supplier_id'),
            'mi_id'       => $this->input->post('mi_id') ?: NULL,
            'subject'     => $this->input->post('subject'),
            'project'     => $this->input->post('project'),
            'ref'         => $this->input->post('ref'),
            'remarks'     => $this->input->post('remarks'),
            'created_by'  => $this->session->userdata('user_id'),
            'updated_by'  => $this->session->userdata('user_id'),
            'updated_at'  => date('Y-m-d H:i:s')
        ];

        $this->db->trans_start();

        $this->db->insert('purchase_requests', $pr_data);
        $pr_id = $this->db->insert_id();

        $product_ids = $this->input->post('product_id');
        $units       = $this->input->post('unit');
        $quantities  = $this->input->post('quantity');

        if ($product_ids && count($product_ids) > 0) {
            foreach ($product_ids as $key => $product_id) {

                if (isset($quantities[$key]) && $quantities[$key] > 0) {
                    $item_data = [
                        'pr_id'      => $pr_id,
                        'product_id' => $product_id,
                        'unit_id'    => $units[$key],
                        'quantity'   => $quantities[$key]
                    ];
                    $this->db->insert('purchase_request_items', $item_data);
                }
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Failed to save Purchase Request.');
        } else {
            $this->session->set_flashdata('success', 'Purchase Request saved successfully.');
        }

        redirect('Purchase/pr_from_mi_list');
    }
    // File: application/controllers/Purchase.php

    public function delete_po($po_id)
    {
        $user = $this->session->userdata('user_id');

        if (!has_access($user,'Purchase/purchase_order_list','D'))
        {
            $data['title'] = 'Access Denied';
            $data['main_content'] = 'errors/access_control.php';
            $this->load->view('includes/template',$data);
            return;
        }
        $this->load->model('Purchase_Model');

        // Call delete method
        $deleted = $this->Purchase_Model->delete_po($po_id);

        if ($deleted) {
            $this->session->set_flashdata('success', 'PO deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete PO.');
        }

        // Redirect back to PO list
        redirect('Purchase/purchase_order_list');
    }
}
