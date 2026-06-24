<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Document_controller extends CI_Controller
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
        $this->load->model('Sales_order_model');

        $this->load->model('Company_model');
        $this->load->helper('menu_helper');
    }

   public function print_sales_order($so_id, $enquiry_id = 0)
{
    // 1. Get Sales Order Master + Products first
    $so_master    = $this->Sales_order_model->get_sales_order_master($so_id);
    $so_products  = $this->Sales_order_model->get_so_products($so_id);

    if (empty($so_master)) {
        show_error('Sales Order not found', 404);
    }

    // Default values
    $data['enquiry_code']       = '';
    $data['enquiry_category']   = '';
    $data['enquiry_date']       = '';
   $data['project_name']     = $so_master['project_name'] ?? '';
    $data['project_location'] = $so_master['project_location'] ?? '';
    // If enquiry exists
    if (!empty($enquiry_id) && $enquiry_id != 0) {

        $enquiry_data = $this->Sales_model->get_enquiry_by_id($enquiry_id);

        if (!empty($enquiry_data)) {

            $data['enquiry_code']       = $enquiry_data['enquiry_code'];
            $data['enquiry_category']   = $enquiry_data['enquiry_category'];
            $data['enquiry_date']       = $enquiry_data['enquiry_date'];
            // $data['project_name']       = $enquiry_data['project_name'];
            // $data['project_location']   = $enquiry_data['project_location'];

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
        }
    }

    // Fallback for direct quotation cases
    if (empty($data['branch_name'])) {

        $data['branch_name']      = $so_master['branch_name'] ?? '';
        $data['branch_header']    = $so_master['branch_header'] ?? '';
        $data['branch_footer']    = $so_master['branch_footer'] ?? '';
        $data['branch_contact']   = $so_master['branch_contact'] ?? '';
        $data['branch_email']     = $so_master['branch_email'] ?? '';
        $data['branch_location']  = $so_master['branch_location'] ?? '';
        $data['branch_address']   = $so_master['branch_address'] ?? '';
        $data['branch_website']   = $so_master['branch_web'] ?? '';

        $data['customer_name']    = $so_master['customer_name'] ?? '';
        $data['customer_address'] = $so_master['customer_address'] ?? '';
        $data['customer_email']   = $so_master['customer_email'] ?? '';
        $data['contact_number']   = $so_master['contact_number'] ?? '';
        $data['emirate']          = $so_master['emirate'] ?? '';

        $data['sales_person']     = $so_master['prepared_by'] ?? '';
    }

    // Dompdf config
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);

    $data['headerPath'] = base_url(ltrim($data['branch_header'], '/'));
    $data['footerPath'] = base_url(ltrim($data['branch_footer'], '/'));

    $data['so_master']   = $so_master;
    $data['so_products'] = $so_products;

    // Load view
    $html = $this->load->view('sales/sales_order/print_sales_order.php', $data, true);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("sales_order_$so_id.pdf", array("Attachment" => 0));
}
   public function print_proforma_invoice($so_id, $enquiry_id = 0)
{
    // =========================
    // 1. Sales Order Data FIRST
    // =========================
    $so_master   = $this->Sales_order_model->get_sales_order_master($so_id);
    $so_products = $this->Sales_order_model->get_so_products($so_id);

    if (empty($so_master)) {
        show_error('Sales Order not found', 404);
    }

    // =========================
    // DEFAULT FROM SO / QUOTATION
    // =========================
    $data['project_name']     = $so_master['project_name'] ?? '';
    $data['project_location'] = $so_master['project_location'] ?? '';

    $data['branch_name']      = $so_master['branch_name'] ?? '';
    $data['branch_header']    = $so_master['branch_header'] ?? '';
    $data['branch_footer']    = $so_master['branch_footer'] ?? '';
    $data['branch_contact']   = $so_master['branch_contact'] ?? '';
    $data['branch_email']     = $so_master['branch_email'] ?? '';
    $data['branch_location']  = $so_master['branch_location'] ?? '';
    $data['branch_address']   = $so_master['branch_address'] ?? '';
    $data['branch_website']   = $so_master['branch_web'] ?? '';

    $data['customer_name']    = $so_master['customer_name'] ?? '';
    $data['customer_address'] = $so_master['customer_address'] ?? '';
    $data['customer_email']   = $so_master['customer_email'] ?? '';
    $data['contact_number']   = $so_master['contact_number'] ?? '';
    $data['emirate']          = $so_master['emirate'] ?? '';
    $data['sales_person']     = $so_master['prepared_by'] ?? '';

    $data['enquiry_code']     = '';
    $data['enquiry_category'] = '';
    $data['enquiry_date']     = '';

    // =========================
    // 2. OPTIONAL ENQUIRY (ONLY IF EXISTS)
    // =========================
    if (!empty($enquiry_id)) {

        $enquiry_data = $this->Sales_model->get_enquiry_by_id($enquiry_id);

        if (!empty($enquiry_data)) {

            $data['enquiry_code']     = $enquiry_data['enquiry_code'];
            $data['enquiry_category'] = $enquiry_data['enquiry_category'];
            $data['enquiry_date']     = $enquiry_data['enquiry_date'];

            // override only if needed
            if (empty($data['project_name'])) {
                $data['project_name'] = $enquiry_data['project_name'];
            }

            if (empty($data['project_location'])) {
                $data['project_location'] = $enquiry_data['project_location'];
            }
        }
    }

    // =========================
    // 3. DOMPDF
    // =========================
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);

    $data['headerPath'] = base_url(ltrim($data['branch_header'], '/'));
    $data['footerPath'] = base_url(ltrim($data['branch_footer'], '/'));

    $data['so_master']   = $so_master;
    $data['so_products'] = $so_products;

    $html = $this->load->view('sales/invoices/print_proforma_invoice.php', $data, true);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("proforma_invoice_$so_id.pdf", ["Attachment" => 0]);
}
   public function print_delivery_challan($del_id)
{
    // -----------------------------
    // 1. Delivery Master
    // -----------------------------
    $del_master = $this->Sales_order_model->get_delivery_master($del_id);

    if (empty($del_master)) {
        show_error("Delivery not found");
        return;
    }

    // -----------------------------
    // 2. Sales Order (MAIN LINK)
    // -----------------------------
    $so = $this->Sales_order_model->get_sales_order_by_id($del_master['so_id']);

    if (empty($so)) {
        show_error("Sales Order not found");
        return;
    }

    // -----------------------------
    // 3. Quotation (PRIMARY SOURCE)
    // -----------------------------
    $qtn = $this->Sales_model->get_quotation_details_by_id($so['qtn_id']);

    if (empty($qtn)) {
        show_error("Quotation not found");
        return;
    }

    // -----------------------------
    // 4. Enquiry (OPTIONAL)
    // -----------------------------
    $enquiry = [];
    if (!empty($so['enquiry_id'])) {
        $enquiry = $this->Sales_model->get_enquiry_by_id($so['enquiry_id']);
    }

    // -----------------------------
    // 5. MAP DATA SAFELY
    // -----------------------------

    // Project
    $data['project_name']     = $qtn['project_name'] ?? '';
    $data['project_location'] = $enquiry['project_location'] ?? '';

    // Branch
    $data['branch_name']     = $qtn['branch_name'] ?? '';
    $data['branch_contact']  = $qtn['branch_contact'] ?? '';
    $data['branch_address']  = $qtn['branch_address'] ?? '';
    $data['branch_location'] = $qtn['branch_location'] ?? '';
    $data['branch_email']    = $qtn['branch_email'] ?? '';

    // Customer (always from quotation)
    $data['customer_name']    = $qtn['customer_name'] ?? '';
    $data['customer_TRN']     = $qtn['customer_TR_no'] ?? '';
    $data['customer_address'] = $qtn['customer_address'] ?? '';
    $data['customer_email']   = $qtn['customer_email'] ?? '';
    $data['contact_number']   = $qtn['contact_number'] ?? '';
    $data['emirate']          = $qtn['emirate'] ?? '';

    // Sales person
    $data['sales_person'] = $qtn['prepared_by'] ?? '';

    // -----------------------------
    // 6. Delivery Data
    // -----------------------------
    $data['del_master']   = $del_master;
    $data['del_products'] = $this->Sales_order_model->get_delivery_products($del_id);

    // -----------------------------
    // 7. Header / Footer (SAFE)
    // -----------------------------
    $data['headerPath'] = !empty($qtn['branch_header'])
        ? base_url(ltrim($qtn['branch_header'], '/'))
        : '';

    $data['footerPath'] = !empty($qtn['branch_footer'])
        ? base_url(ltrim($qtn['branch_footer'], '/'))
        : '';

    // -----------------------------
    // 8. PDF GENERATION
    // -----------------------------
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);

    $html = $this->load->view(
        'sales/delivery_challan/print_delivery_challan.php',
        $data,
        true
    );

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("delivery_challan_$del_id.pdf", ["Attachment" => 0]);
}
    public function print_commercial_invoice($del_id, $enquiry_id = null)
    {
        /* ===============================
       1. DELIVERY MASTER
       =============================== */
        $del_master = $this->Sales_order_model->get_delivery_master($del_id);
        $item_master = $this->Sales_order_model->get_item_master();

        /* ===============================
       2. GET REFERENCE DATA
       (Enquiry OR Direct Quotation)
       =============================== */
        $ref_data = $this->Sales_model->get_reference_data(
            $del_master['enquiry_id'] ?? null,
            $del_master['quotation_id'] ?? null
        );

        if (empty($ref_data)) {
            show_error('Reference data not found');
        }

        $item_master_map = [];

foreach ($item_master as $item) {
    $item_master_map[$item['item_id']] = $item;
}
        /* ===============================
       3. ASSIGN DATA FOR VIEW
       =============================== */

        // Reference details
        $data['reference_code']     = $ref_data['reference_code'];
        $data['reference_type']     = $ref_data['reference_type'];
        $data['reference_date']     = $ref_data['reference_date'];
        $data['project_name']       = $ref_data['project_name'];
        $data['project_location']   = $ref_data['project_location'];

        // Branch details
        $data['branch_id']          = $ref_data['branch_id'];
        $data['branch_name']        = $ref_data['branch_name'];
        $data['branch_header']      = $ref_data['branch_header'];
        $data['branch_footer']      = $ref_data['branch_footer'];
        $data['branch_contact']     = $ref_data['branch_contact'];
        $data['branch_email']       = $ref_data['branch_email'];
        $data['branch_location']    = $ref_data['branch_location'];
        $data['branch_address']     = $ref_data['branch_address'];
        $data['branch_website']     = $ref_data['branch_web'];

        // Customer details
        $data['customer_name']      = $ref_data['customer_name'];
        $data['customer_TRN']       = $ref_data['customer_TR_no'];
        $data['customer_address']   = $ref_data['customer_address'];
        $data['customer_email']     = $ref_data['customer_email'];
        $data['contact_number']     = $ref_data['contact_number'];
        $data['emirate']            = $ref_data['emirate'];

        // Sales person
        $data['sales_person']       = $ref_data['user_name'];
        $data['item_master'] = $item_master_map;

        /* ===============================
       4. DOMPDF CONFIG
       =============================== */
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', realpath('C:/xampp/htdocs/aladel_erp/public/'));

        $dompdf = new Dompdf($options);

        $data['headerPath'] = base_url(ltrim($data['branch_header'], '/'));
        $data['footerPath'] = base_url(ltrim($data['branch_footer'], '/'));

        /* ===============================
       5. DELIVERY PRODUCTS
       =============================== */
        $data['del_master']   = $del_master;
        $data['del_products'] = $this->Sales_order_model->get_delivery_products($del_id);

        /* ===============================
       6. LOAD VIEW & GENERATE PDF
       =============================== */
        $html = $this->load->view(
            'sales/invoices/print_commercial_invoice.php',
            $data,
            true
        );

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream(
            "commercial_invoice_{$del_id}.pdf",
            array("Attachment" => 0)
        );
    }

   

public function print_invoice($invoice_id)
{
    // 1. Invoice Master
    $invoice_master = $this->Sales_order_model->get_invoice_master($invoice_id);

    if (empty($invoice_master)) {
        show_error('Invoice not found', 404);
    }

    $so_id  = $invoice_master['so_id'];
    $so     = $this->Sales_order_model->get_sales_order_master($so_id);

    if (empty($so)) {
        show_error('Sales Order not found', 404);
    }

    $qtn_id = $so['qtn_id'];

    // 2. ALWAYS GET FROM QUOTATION
    $quotation = $this->Sales_order_model->get_quotation_print_data($qtn_id);

    if (empty($quotation)) {
        show_error('Quotation not found', 404);
    }

    // 3. DATA (ONLY QUOTATION USED FOR HEADER/FOOTER/CUSTOMER)
    $prepared_by_id = $invoice_master['prepared_by'] ?? null;
    $received_by_id = $invoice_master['received_by'] ?? null;

     $prepared_by_name = '';
$received_by_name = '';
$prepared_signature = '';
$received_signature = '';

        if (!empty($prepared_by_id)) {
            $prepared_emp = $this->Company_model->get_employee_by_id($prepared_by_id);
            $prepared_by_name = $prepared_emp->employee_name ?? '';
             $prepared_signature = $prepared_emp->signature_file ?? '';
        }
        if (!empty($received_by_id)) {
            $received_emp = $this->Company_model->get_employee_by_id($received_by_id);
            $received_by_name = $received_emp->employee_name ?? '';
            $received_signature = $received_emp->signature_file ?? '';
        }

    $data = [
        'branch_name'     => $quotation['branch_name'],
        'branch_header'   => $quotation['branch_header'],
        'branch_footer'   => $quotation['branch_footer'],
        'branch_contact'  => $quotation['branch_contact'],
        'branch_email'    => $quotation['branch_email'],
        'branch_location' => $quotation['branch_location'],
        'branch_address'  => $quotation['branch_address'],
        'branch_website'  => $quotation['branch_web'],
       'branch_stamp'=> $quotation['branch_stamp'],

        'customer_name'    => $quotation['customer_name'],
        'customer_address' => $quotation['customer_address'],
        'customer_email'   => $quotation['customer_email'],
        'customer_trn'   => $quotation['customer_TR_no'],
        'contact_number'   => $quotation['contact_number'],
        'emirate'          => $quotation['emirate'],

        'project_name'     => $quotation['project_name'],
        'project_location' => $quotation['project_location'],

        'sales_person'      => $so['prepared_by'] ?? '',
        'prepared_by_name'  => $prepared_by_name,
        'received_by_name'  => $received_by_name,
        'prepared_signature' => $prepared_signature ?? '',
        'received_signature' => $received_signature ?? '',
    ];

    // 4. CHILD DATA
    $data['invoice_master']   = $invoice_master;
    $data['so_address']       = $this->Sales_order_model->get_so_address($so_id);
    $data['invoice_products'] = $this->Sales_order_model->get_invoice_products($invoice_id);

    $bank = $this->Company_model->get_branch_bank_by_bank_id($invoice_master['bank_id']);
    $data['invoice_bank_data'] = $bank[0] ?? [];

    // 5. DOMPDF
    $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', realpath('C:/xampp/htdocs/aladel_erp/public/'));
        $dompdf = new Dompdf($options);

        $data['headerPath'] = base_url(ltrim($data['branch_header'], '/'));
        $data['footerPath'] = base_url(ltrim($data['branch_footer'], '/'));

    $html = $this->load->view('sales/invoices/print_invoice.php', $data, true);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("invoice_$invoice_id.pdf", ["Attachment" => 0]);
}
public function print_direct_invoice($invoice_id)
    {
        // 1. Get enquiry details (branch, customer, etc.)
        $invoice_master    = $this->Sales_order_model->get_invoice_master($invoice_id);
        
        $branch_id = $invoice_master['branch_id'];
        $customer_id = $invoice_master['invoice_customer'];

        $branch_details = $this->Company_model->get_branch_by_id($branch_id);
        $customer_details = $this->Company_model->get_customer_by_id($customer_id);
        // print_r($customer_details);exit();
        $prepared_by_id = $invoice_master['prepared_by'] ?? null;
        $received_by_id = $invoice_master['received_by'] ?? null;

        $prepared_by_name = '';
$received_by_name = '';
$prepared_signature = '';
$received_signature = '';

        if (!empty($prepared_by_id)) {
            $prepared_emp = $this->Company_model->get_employee_by_id($prepared_by_id);
            $prepared_by_name = $prepared_emp->employee_name ?? '';
             $prepared_signature = $prepared_emp->signature_file ?? '';
        }
        if (!empty($received_by_id)) {
            $received_emp = $this->Company_model->get_employee_by_id($received_by_id);
            $received_by_name = $received_emp->employee_name ?? '';
            $received_signature = $received_emp->signature_file ?? '';
        }
        $data['branch_id']       = $branch_details->branch_id;
        $data['branch_name']     = $branch_details->branch_name;
        $data['branch_header']   = $branch_details->branch_header;
        $data['branch_footer']   = $branch_details->branch_footer;
        $data['branch_contact']  = $branch_details->branch_contact;
        $data['branch_email']    = $branch_details->branch_email;
        $data['branch_location'] = $branch_details->branch_location;
        $data['branch_address']  = $branch_details->branch_address;
        $data['branch_website']  = $branch_details->branch_web;
        $data['branch_trn']  = $branch_details->branch_trn;
        $data['branch_stamp'] = $branch_details->branch_stamp ?? '';

        $data['customer_name']      = $customer_details[0]->customer_name;
        $data['customer_address']   = $customer_details[0]->customer_address;
        $data['customer_email']     = $customer_details[0]->customer_email;
        $data['contact_number']     = $customer_details[0]->contact_number;
        $data['emirate']            = $customer_details[0]->emirate;
        $data['customer_trn']       = $customer_details[0]->customer_TR_no;
        //$data['sales_person']       = $enquiry_data['user_name'];
        $data['prepared_by_name']  = $prepared_by_name;
        $data['received_by_name']  = $received_by_name;
        $data['prepared_signature'] = $prepared_signature ?? '';
        $data['received_signature'] = $received_signature ?? '';
        // 2. Dompdf config
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', realpath('C:/xampp/htdocs/aladel_erp/public/'));
        $dompdf = new Dompdf($options);

        $data['headerPath'] = base_url(ltrim($data['branch_header'], '/'));
        $data['footerPath'] = base_url(ltrim($data['branch_footer'], '/'));

        // 3. Get Sales Order Master + Products

        $so_address   = $this->Sales_order_model->get_so_address($invoice_master['so_id']);
        $invoice_products  = $this->Sales_order_model->get_direct_invoice_products($invoice_id);
        $delivery_challan_data = $this->Sales_order_model->get_delivery_master($invoice_master['delivery_id']);
        $data['invoice_master']   = $invoice_master;
        $data['so_address']  = $so_address;
        $data['invoice_products'] = $invoice_products;
        // $data['delivery_challan_data'] = $delivery_challan_data;
        // $bank_data = $this->Company_model->get_branch_bank_by_bank_id($data['invoice_master']['bank_id']);
        // $data['invoice_bank_data'] = array_shift($bank_data);
 $bank_data = $this->Company_model
    ->get_branch_bank_by_branch_id_new($invoice_master['branch_id']);

$data['invoice_bank_data'] = $bank_data;

        // 4. Load Sales Order print view
        $html = $this->load->view('sales/invoices/print_direct_invoice.php', $data, true);
        // 5. Generate PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("invoice_$invoice_id.pdf", array("Attachment" => 0));
    }
    public function print_estimation($estimation_id, $enquiry_id, $estimationtype = "")
    {
        $enquiry_data               =    $this->Crm_model->get_enquiry_by_id($enquiry_id);

        $options                    = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); // allows base_url() http paths
        $options->set('chroot', realpath('C:/xampp/htdocs/aladel_erp/public/'));

        $dompdf                     = new Dompdf($options);

        $data['headerPath']         = base_url(ltrim($enquiry_data['branch_header'], '/'));
        $data['footerPath']         =  base_url(ltrim($enquiry_data['branch_footer'], '/'));

        $data['enquiry_data']       = $enquiry_data;
        $estimation_data            = $this->estimation_revision_master_data($estimation_id, $estimationtype);
        $data['master']             = $estimation_data['revision_master'];
        $data['estimation']         = $estimation_data['revision_estimation'];

        $html                       = $this->load->view('crm/print/print_estimation.php', $data, true);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("estimation_$enquiry_id.pdf", array("Attachment" => 0));
    }
    public function estimation_revision_master_data($estimation_id)
    {
        $rows = $this->Crm_model->get_estimation_revisions_data($estimation_id);
        //echo $this->db->last_query();exit();
        $data['revision_estimation'] = [];
        $data['revision_master'] = null;

        foreach ($rows as $row) {
            if ($data['revision_master'] === null) {
                // only once - master details
                $data['revision_master'] = [
                    'estimation_id'           => $row['estimation_id'],
                    'enquiry_id'              => $row['enquiry_id'],
                    'estimation_date'         => $row['estimation_date'],
                    'sub_total'               => $row['sub_total'],
                    'grand_total'             => $row['grand_total'],
                    'margin_percentage'     => $row['margin_percentage'],
                    'margin_amount'         => $row['margin_amount'],
                    'freight_percentage'    => $row['freight_percentage'],
                    'freight_amount'         => $row['freight_amount'],
                    'bank_charge'             => $row['bank_charge'],
                    'travel_expense'         => $row['travel_expense'],
                    'inspection_cost'         => $row['inspection_cost'],
                    'other_expense'         => $row['other_expense'],
                    'approval'                => $row['approval']
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
                        'product_table_id'         => $row['product_table_id'],
                        'item_name'                => $row['item_name'],
                        'product_id'            => $row['product_id'],
                        'product_description'   => $row['product_description'],
                        'unit_name'                => $row['unit_name'],
                        'unit_id'                => $row['unit_id'],
                        'quantity'                 => $row['quantity'],
                        'unit_price'               => $row['unit_price'],
                        'amount'                   => $row['amount'],
                    ];
                }
            }
        }
        return $data;
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
        

        $html = $this->load->view('crm/print/print_survey.php', $data, true);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("survey_$survey_id.pdf", array("Attachment" => 0));
    }

    public function einvoice($invoice_id)
{
    $invoice_master = $this->Sales_order_model->get_invoice_master($invoice_id);

    if (empty($invoice_master)) {
        show_error('Invoice not found', 404);
    }

    $data['invoice_master'] = $invoice_master;

    $data['invoice_products'] = $this->Sales_order_model->get_invoice_products($invoice_id);

    // OPTIONAL: QR / VAT logic later
    // $data['qr'] = generate_qr($invoice_master);

    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);

    $html = $this->load->view(
        'sales/invoices/print_einvoice.php',
        $data,
        true
    );

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $dompdf->stream("einvoice_$invoice_id.pdf", ["Attachment" => 0]);
}
}
