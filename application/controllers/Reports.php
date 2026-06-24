<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Reports extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();

    $this->load->model('Setup_model');
    $this->load->model('Reports_model');
    
    
  }


  ///////////////  RFQ Report ////////////////////
  function rfq_report()
  {
    $data['from'] = date('01-m-Y');
    $data['to'] = date('d-m-Y');
    $data['status'] = "";
    $data['title'] = "RFQ Report";
    $data['records'] = array();
    $data['supplier_id'] = "";
    $data['user_list'] = $this->Setup_model->get_active_user_list();
    $data['supplier_records'] = $this->Setup_model->get_active_supplier_list();
    $data['main_content'] = 'Reports/Purchase/rfq_report.php';
    $this->load->view('includes/template.php', $data);
  }
  function get_rfq_report()
  {
    $data['from'] = $this->input->post('from_date');
    $data['to'] = $this->input->post('to_date');
    $data['title'] = "RFQ Report";
    $data['created_by'] = $this->input->post('created_by');
    $data['supplier_id'] = $this->input->post('supplier_id');
      
    $data['supplier_records'] = $this->Setup_model->get_active_supplier_list();  
    $data['records'] = $this->Reports_model->get_rfq_report_records();
    $data['main_content'] = 'Reports/Purchase/rfq_report.php';
    $this->load->view('includes/template.php', $data);
  }
  public function print_rfq_report() {
    $from_date = $this->input->get('from_date');
    $to_date = $this->input->get('to_date');
    $supplier_id = $this->input->get('supplier_id');
    $data['from'] = $from_date;
    $data['to'] = $to_date;
    $data['supplier_id'] = $supplier_id;
    // Fetch filtered records again
    $data['records'] = $this->Reports_model->get_rfq_report_records();

    $data['supplier_id'] = $supplier_id;
    $this->load->model('Company_model');

 $branch_id = 1; // replace with dynamic branch_id if available
    $branch = $this->Company_model->get_branch_by_id($branch_id);
    $data['headerPath'] = !empty($branch->branch_header) ? base_url(ltrim($branch->branch_header, '/')) : '';

    $this->load->view('Reports/Purchase/Print/print_rfq_report', $data);
    
}


  ///////////////  PO Report ////////////////////
  function po_report()
  {
    $data['from'] = date('01-m-Y');
    $data['to'] = date('d-m-Y');
    $data['status'] = "";
    $data['title'] = "Purchase Order Report";
    $data['supplier_id'] = "";
    $data['records'] = array();
    $data['user_list'] = $this->Setup_model->get_active_user_list();
    $data['supplier_records'] = $this->Setup_model->get_active_supplier_list();
    $data['main_content'] = 'Reports/Purchase/po_report.php';
    $this->load->view('includes/template.php', $data);
  }
  function get_po_report()
  {
    $data['from'] = $this->input->post('from_date');
    $data['to'] = $this->input->post('to_date');
    $data['title'] = "Purchase Order Report";
    $data['created_by'] = $this->input->post('created_by');
    $data['supplier_id'] = $this->input->post('supplier_id');
      
    $data['supplier_records'] = $this->Setup_model->get_active_supplier_list();  
    $data['records'] = $this->Reports_model->get_po_report_records();
    $data['main_content'] = 'Reports/Purchase/po_report.php';
    $this->load->view('includes/template.php', $data);
  }
  public function print_po_report() {
    $from_date = $this->input->get('from_date');
    $to_date = $this->input->get('to_date');
    $supplier_id = $this->input->get('supplier_id');
    $data['from'] = $from_date;
    $data['to'] = $to_date;
    $data['supplier_id'] = $supplier_id;
    // Fetch filtered records again
    $data['records'] = $this->Reports_model->get_po_report_records();

    $data['supplier_id'] = $supplier_id;

    $this->load->model('Company_model');

 $branch_id = 1; // replace with dynamic branch_id if available
    $branch = $this->Company_model->get_branch_by_id($branch_id);
    $data['headerPath'] = !empty($branch->branch_header) ? base_url(ltrim($branch->branch_header, '/')) : '';


    $this->load->view('Reports/Purchase/Print/print_po_report', $data);
    
}
///////////////  GRN Report ////////////////////
function grn_report()
{
  $data['from'] = date('01-m-Y');
  $data['to'] = date('d-m-Y');
  $data['status'] = "";
  $data['title'] = "Goods Received Note Report";
  $data['supplier_id'] = "";
  $data['records'] = array();
  $data['user_list'] = $this->Setup_model->get_active_user_list();
  $data['supplier_records'] = $this->Setup_model->get_active_supplier_list();
  $data['main_content'] = 'Reports/Purchase/grn_report.php';
  $this->load->view('includes/template.php', $data);
}
function get_grn_report()
{
  $data['from'] = $this->input->post('from_date');
  $data['to'] = $this->input->post('to_date');
  $data['title'] = "Goods Received Note Report";
  $data['created_by'] = $this->input->post('created_by');
  $data['supplier_id'] = $this->input->post('supplier_id');
    
  $data['supplier_records'] = $this->Setup_model->get_active_supplier_list();  
  $data['records'] = $this->Reports_model->get_grn_report_records();
  $data['main_content'] = 'Reports/Purchase/grn_report.php';
  $this->load->view('includes/template.php', $data);
}
public function print_grn_report() {
  $from_date = $this->input->get('from_date');
  $to_date = $this->input->get('to_date');
  $supplier_id = $this->input->get('supplier_id');
  $data['from'] = $from_date;
  $data['to'] = $to_date;
  $data['supplier_id'] = $supplier_id;
  // Fetch filtered records again
  $data['records'] = $this->Reports_model->get_grn_report_records();

  $data['supplier_id'] = $supplier_id;

  $this->load->model('Company_model');

 $branch_id = 1; // replace with dynamic branch_id if available
    $branch = $this->Company_model->get_branch_by_id($branch_id);
    $data['headerPath'] = !empty($branch->branch_header) ? base_url(ltrim($branch->branch_header, '/')) : '';


  $this->load->view('Reports/Purchase/Print/print_grn_report', $data);
  
}

 public function enquiry_report()
	{
		$user = $this->session->userdata('user_id');
		if (!has_view_access($user, 'Reports/enquiry_report')) {
			$data['title'] = 'Access Denied';
			$data['main_content'] = 'errors/access_control.php';
		} else {
			$data['title'] = 'Enquiry Report';
			$this->load->model('Sales_model');

			$data['from_date'] = $_POST['from_date'] ?? date('Y-m-d');
			$data['to_date'] = $_POST['to_date'] ?? date('Y-m-d');
			$data['sales_person'] = $_POST['sales_person'] ?? '';
			$data['customer_id'] = $_POST['customer'] ?? '';
			$print = $_POST['print_option'] ?? 0;
			//echo $print;exit;
			$data['all_users'] = $this->Setup_model->get_active_user_list();
			$data['all_customers'] = $this->Setup_model->get_all_customer_list();

			// Get the logged-in user ID from session
			$data['logged_in_user_id'] = $this->session->userdata('user_id');
			
			// Define who can view all salespersons
			$data['admin_users'] = [1, 6, 9, 10, 11];

			if (isset($_POST['from_date'])) {
				$data['records'] = $this->Reports_model->get_enquiry_report();
			}
			if ($print == 0)
				$data['main_content'] = 'Reports/Sales/enquiry_report.php';
		}
		if ($print == 0)
			$this->load->view('includes/template', $data);
		else
			$this->load->view('Reports/Sales/print/enquiry_report.php', $data);
	}

	///////////////////QUOTATION REPORT////////////////////
	public function quotation_report()
	{
		$user = $this->session->userdata('user_id');
		if (!has_view_access($user, 'Reports/quotation_report')) {
			$data['title'] = 'Access Denied';
			$data['main_content'] = 'errors/access_control.php';
		} else {
			$data['title'] = 'Quotation Report';
			$this->load->model('Sales_model');
			$this->load->model('Setup_model');
			$data['logged_in_user_id'] = $this->session->userdata('user_id');
			$data['admin_users'] = [14, 15, 16, 17];

			$data['from_date'] = $_POST['from_date'] ?? date('Y-m-d');
			$data['to_date'] = $_POST['to_date'] ?? date('Y-m-d');
			$data['sales_person'] = $_POST['sales_person'] ?? '';
			$data['status'] = $_POST['status'] ?? '123';
			$data['customer_id'] = $_POST['customer'] ?? '';

			$data['all_users'] = $this->Setup_model->get_active_user_list();
			$data['all_customers'] = $this->Setup_model->get_all_customer_list();


			if (isset($_POST['from_date'])) {
				$data['records'] = $this->Reports_model->get_quotation_report();
			}

			$data['main_content'] = 'Reports/Sales/quotation_report.php';
		}
		$this->load->view('includes/template', $data);
	}


	public function custom_quotation_report()
	{
		$user = $this->session->userdata('user_id');
		if (!has_view_access($user, 'Reports/custom_quotation_report')) {
			$data['title'] = 'Access Denied';
			$data['main_content'] = 'errors/access_control.php';
		} else {
			$data['title'] = 'Quotation Report';
			$this->load->model('Sales_model');
			$this->load->model('Setup_model');

			$data['quotation_id'] = $_POST['quotation_id'] ?? '';

			$data['approved_quotations'] = $this->Sales_model->get_approved_quotation_list();


			if (isset($_POST['quotation_id'])) {
				$data['records'] = $this->Reports_model->custom_quotation_report();
			}

			$data['main_content'] = 'Reports/Sales/custom_quotation_report.php';
		}
		$this->load->view('includes/template', $data);
	}

	///////////////////PI REPORT////////////////////
	public function pi_report()
	{
		$user = $this->session->userdata('user_id');
		if (!has_view_access($user, 'Reports/pi_report')) {
			$data['title'] = 'Access Denied';
			$data['main_content'] = 'errors/access_control.php';
		} else {
			$data['title'] = 'Sales order Report';
			$this->load->model('Sales_model');
			$this->load->model('Setup_model');

			$data['from_date'] = $_POST['from_date'] ?? date('Y-m-d');
			$data['to_date'] = $_POST['to_date'] ?? date('Y-m-d');
			$data['sales_person'] = $_POST['sales_person'] ?? '';
			$data['status'] = $_POST['status'] ?? '';
			$data['quotation'] = $_POST['quotation'] ?? '';

			$data['all_users'] = $this->Setup_model->get_active_user_list();
			$data['all_customers'] = $this->Setup_model->get_all_customer_list();
			// $data['approved_quotations'] = $this->Sales_model->get_approved_quotation_list();


			if (isset($_POST['from_date'])) {
				$data['records'] = $this->Reports_model->pi_report();
			}

			$data['main_content'] = 'Reports/Sales/pi_report.php';
		}
		$this->load->view('includes/template', $data);
	}

	///////////////////INVOICE REPORT////////////////////
	public function invoice_report()
	{

		$user = $this->session->userdata('user_id');
		if (!has_view_access($user, 'Reports/invoice_report')) {
			$data['title'] = 'Access Denied';
			$data['main_content'] = 'errors/access_control.php';
		} else {
			$data['title'] = 'Invoice Report';
			$this->load->model('Item_model');
			$data['from_date'] = $_POST['from_date'] ?? date('Y-m-d');
			$data['to_date'] = $_POST['to_date'] ?? date('Y-m-d');

			$data['customer_id'] = $_POST['customer'] ?? '';
			$data['status'] = $_POST['status'] ?? '123';
			//echo $data['status'];exit;
			$data['all_customers'] = $this->Setup_model->get_all_customer_list();
			if (isset($_POST['from_date'])) {
				$data['records'] = $this->Sales_order_model->list_all_invoices();
			}

			$data['main_content'] = 'Reports/Sales/invoice_report.php';
		}
		$this->load->view('includes/template', $data);
	}

	public function custom_invoice_report()
{
    $user = $this->session->userdata('user_id');

    // Access control
    if (!has_view_access($user, 'Reports/custom_invoice_report')) {
        $data['title'] = 'Access Denied';
        $data['main_content'] = 'errors/access_control.php';
        $this->load->view('includes/template', $data);
        return;
    }

    $data['title'] = 'Invoice Report';

    // Load required models ONLY
    $this->load->model('Sales_order_model');
    $this->load->model('Setup_model');

    // Filters
    $data['from_date']    = $this->input->post('from_date') ?? date('Y-m-d');
    $data['to_date']      = $this->input->post('to_date') ?? date('Y-m-d');
    $data['sales_person'] = $this->input->post('sales_person') ?? '';
    $data['customer_id']  = $this->input->post('customer') ?? '';

    // Dropdown data
    $data['all_users']     = $this->Setup_model->get_active_user_list();
    $data['all_customers'] = $this->Setup_model->get_all_customer_list();

    // Fetch records ONLY after submit
    if ($this->input->post()) {
        $data['records'] = $this->Sales_order_model->get_filtered_invoices(
            $data['from_date'],
            $data['to_date'],
            $data['customer_id'],
            $data['sales_person']
        );
    } else {
        $data['records'] = [];
    }

    // Load view
    $data['main_content'] = 'Reports/Sales/custom_invoice_report.php';
    $this->load->view('includes/template', $data);
}

public function print_custom_invoice_report()
{
    $user = $this->session->userdata('user_id');

    if (!has_view_access($user, 'Reports/custom_invoice_report')) {
        echo "Access Denied";
        return;
    }

    $this->load->model('Sales_order_model');
    $this->load->model('Setup_model');

    // Get parameters from GET
    $from_date    = $_GET['from_date'] ?? date('Y-m-d');
    $to_date      = $_GET['to_date'] ?? date('Y-m-d');
    $sales_person = $_GET['sales_person'] ?? '';
    $customer_id  = $_GET['customer'] ?? '';

    // Fetch filtered invoices
    $data['records'] = $this->Sales_order_model->get_filtered_invoices(
        $from_date,
        $to_date,
        $customer_id,
        $sales_person
    );

    $data['from_date'] = $from_date;
    $data['to_date'] = $to_date;
 $this->load->model('Company_model');

 $branch_id = 1; // replace with dynamic branch_id if available
    $branch = $this->Company_model->get_branch_by_id($branch_id);
    $data['headerPath'] = !empty($branch->branch_header) ? base_url(ltrim($branch->branch_header, '/')) : '';

    // Load print view
    $this->load->view('Reports/Sales/print/custom_invoice_report_print', $data);
}

	public function delivery_report()
{
    $user = $this->session->userdata('user_id');
    if (!has_view_access($user, 'Reports/delivery_report')) {
        $data['title'] = 'Access Denied';
        $data['main_content'] = 'errors/access_control.php';
    } else {
        $data['title'] = 'Delivery Note Report';
        $this->load->model('Item_model');

        // Get filter values
        $from_date = $_POST['from_date'] ?? date('Y-m-d');
        $to_date   = $_POST['to_date'] ?? date('Y-m-d');
        $customer  = $_POST['customer'] ?? '';
        $status    = $_POST['status'] ?? '123';

        $data['from_date'] = $from_date;
        $data['to_date']   = $to_date;
        $data['customer_id'] = $customer;
        $data['status']    = $status;

        $data['all_customers'] = $this->Setup_model->get_all_customer_list();

        // Pass filter values to the model
        if (isset($_POST['from_date'])) {
            $data['records'] = $this->Reports_model->delivery_report();

			}

        $data['main_content'] = 'Reports/Sales/delivery_report.php';
    }

    $this->load->view('includes/template', $data);
}


public function print_enquiry_report()
{
    $user = $this->session->userdata('user_id');

    $data['title'] = 'Enquiry Report';
    $this->load->model('Sales_model');
    $this->load->model('Company_model'); // to get branch header

    $data['from_date'] = $_GET['from_date'] ?? date('Y-m-d');
    $data['to_date'] = $_GET['to_date'] ?? date('Y-m-d');
    $data['sales_person'] = $_GET['sales_person'] ?? '';
    $data['customer_id'] = $_GET['customer'] ?? '';

    // Get enquiry records
    $data['records'] = $this->Reports_model->print_enquiry_report();

    // Example: get default branch header
    // If you have a branch_id from session or user, use it instead
    $branch_id = 1; // replace with dynamic branch_id if available
    $branch = $this->Company_model->get_branch_by_id($branch_id);
    $data['headerPath'] = !empty($branch->branch_header) ? base_url(ltrim($branch->branch_header, '/')) : '';

    // Log for debugging
    log_message('info', 'Enquiry Report Data: ' . json_encode($data['records']));
    log_message('info', 'Header Path: ' . $data['headerPath']);

    // Load view
    $this->load->view('Reports/Sales/print/enquiry_report', $data);


}

	public function print_quotation_report()
{
    $user = $this->session->userdata('user_id');

    if (!has_view_access($user, 'Reports/quotation_report')) {
        $data['title'] = 'Access Denied';
        $data['main_content'] = 'errors/access_control.php';
        $this->load->view('includes/template', $data);
        return;
    }

    $this->load->model('Reports_model');
    $this->load->model('Setup_model');
    $this->load->model('Company_model');

    $filters = [
        'from_date'    => $this->input->get('from_date', true),
        'to_date'      => $this->input->get('to_date', true),
        'customer'     => $this->input->get('customer', true),
        'status'       => $this->input->get('status', true),
        'sales_person' => $this->input->get('sales_person', true),
    ];

    $data['records'] = $this->Reports_model->get_print_quotation_report($filters);

    $data['all_users']     = $this->Setup_model->get_active_user_list();
    $data['all_customers'] = $this->Setup_model->get_all_customer_list();

   $branch_id = 1; // replace with dynamic branch_id if available
    $branch = $this->Company_model->get_branch_by_id($branch_id);
    $data['headerPath'] = !empty($branch->branch_header) ? base_url(ltrim($branch->branch_header, '/')) : '';

    $data['title'] = 'Quotation Report';

    $this->load->view('Reports/Sales/print/quotation_report.php', $data);
}

	public function print_pi_report()
	{
		$user = $this->session->userdata('user_id');
		if (!has_view_access($user, 'Reports/pi_report')) {
			$data['title'] = 'Access Denied';
			$data['main_content'] = 'errors/access_control.php';
		} else {
			$data['title'] = 'Sales order Report';
			$this->load->model('Sales_model');
			$this->load->model('Setup_model');

			$data['from_date'] = $_POST['from_date'] ?? date('Y-m-d');
			$data['to_date'] = $_POST['to_date'] ?? date('Y-m-d');
			$data['sales_person'] = $_POST['sales_person'] ?? '';
			$data['status'] = $_POST['status'] ?? '';
			$data['quotation'] = $_POST['quotation'] ?? '';

			$data['all_users'] = $this->Setup_model->get_active_user_list();
			$data['all_customers'] = $this->Setup_model->get_all_customer_list();
			// $data['approved_quotations'] = $this->Sales_model->get_approved_quotation_list();



			$data['records'] = $this->Reports_model->print_pi_report();
 $this->load->model('Company_model');

 $branch_id = 1; // replace with dynamic branch_id if available
    $branch = $this->Company_model->get_branch_by_id($branch_id);
    $data['headerPath'] = !empty($branch->branch_header) ? base_url(ltrim($branch->branch_header, '/')) : '';


			$this->load->view('Reports/Sales/print/pi_report.php', $data);
		}
	}


function stock_inventory_report()
  {
    $data['title'] = 'Inventory Report';
    $data['warehouse_id'] = 3;
    $data['item_id'] = 1;

    $this->load->model('Stock_model');
    $data['products'] = $this->Stock_model->get_stock_code_list();

    $this->load->model('Setup_model');
    $data['store_records'] = $this->Setup_model->get_warehouse_list();

    $data['records'] = $this->Stock_model->get_stock_inventory_report();

    $data['main_content'] = 'Reports/Stock/stock_inventory_report.php';
    $this->load->view('includes/template.php', $data);
  }

  function get_stock_inventory_report()
  {
    $data['title'] = 'Inventory Report';
    $data['warehouse_id'] = $this->input->post('warehouse_id');
    $data['item_id'] = $this->input->post('product_id');

    $this->load->model('Stock_model');
    $data['products'] = $this->Stock_model->get_stock_code_list();

    $this->load->model('Setup_model');
    $data['store_records'] = $this->Setup_model->get_warehouse_list();

    $data['records'] = $this->Stock_model->get_stock_inventory_report();

    $data['main_content'] = 'Reports/Stock/stock_inventory_report.php';
    $this->load->view('includes/template.php', $data);
  }



}
?>