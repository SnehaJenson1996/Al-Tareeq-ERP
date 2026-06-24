<?php date_default_timezone_set('Asia/Kolkata');
		//use Dompdf\Dompdf;
		//require_once FCPATH . 'vendor/autoload.php';

	use Dompdf\Dompdf;
use Dompdf\Options;
    class AMC extends CI_Controller {
        
        function __construct() {
             parent::__construct();
             $this->is_logged_in();
			 $this->load->model('Amc_model');
			 $this->load->model('Product_model');
			 $this->load->model('Users_model');
			 $this->load->model('Setup_model');
			 $this->load->model('Company_model');
			// $this->load->model('Sales_Model');
			 $this->load->model('Amc_model');
        }

        function is_logged_in() {
            $is_logged_in = $this->session->userdata('is_logged_in');
            if(!isset($is_logged_in) || $is_logged_in != true)
            {
                echo 'You don\'t have permission to access this page. <a href="../login">Login</a>';
                die();
                $this->load->view('login/login_form');
            }
        }
        
        /////////////////////// New user  /////////////////////////////////////
	function add_enquiry()
	{
		$data['title']='Add New Enquiry(AMC)';
		$prifix='ADL/ENQ/';
		$num = $this->Setup_model->get_next_code($prifix,'amc_enq_code','amc_enquiry_master',9)+1;
		$digit=sprintf("%1$04d",$num);
		$code =$prifix.$digit;
		$data['code'] =$code;

		$this->load->model('Product_model');
		// $data['products1']=$this->Product_model->get_product_list_by_category('1');
		// $data['products3']=$this->Product_model->get_product_list_by_category('3');
		$data['brand_list']=$this->Product_model->get_brand_list();
		$this->load->model('Users_model');
		$data['cust_records'] = $this->Users_model->get_active_customer_list();
		$data['user_records']=$this->Users_model->get_user_list();
		
		$data['enq_records']=$this->Amc_model->get_enquiry_list();
		$data['branch_list']    = $this->Company_model->get_all_branches();
		$data['main_content']='amc/enquiry_add.php';

		$this->load->view('includes/template.php',$data);
	}
	function add_new_enquiry()
	{
		$data['title']='Add New Enquiry';
		
		$insert_id = $this->Amc_model->add_new_amc_enquiry();
		
		if($insert_id!=''){
			$this->session->set_flashdata('success', 'Data Saved Successfully..');
			redirect('AMC/view_enquiry_list');
		}
	}
	function view_enquiry_list()
	{
		$data['title']='AMC Enquiry List';
		$data['records']=$this->Amc_model->get_enquiry_list();

		$data['main_content']='amc/enquiry_list.php';
		$this->load->view('includes/template.php',$data);
	}
	function edit_enquiry()
	{
		$data['title']='Enquiry Edit';
		$id = $this->uri->segment('3');
		$data['edit_flag'] = $this->uri->segment('4');
		$version = $this->uri->segment('5');
		$data['records']=$this->Amc_model->get_enquiry_record_by_id($id);
		$data['trans_records']=$this->Amc_model->get_enquiry_trans_by_id($id);
		$data['supplier_records']=$this->Users_model->get_supplier_list();
		$data['brand_list']=$this->Product_model->get_brand_list();
		$data['user_records']=$this->Users_model->get_user_list();		
		$data['cust_records'] = $this->Users_model->get_active_customer_list();
		$data['main_content']='amc/enquiry_edit.php';
		//echo '<pre>';print_R($data);exit;
		$this->load->view('includes/template.php',$data);
	}

	function update_enquiry_data()
	{
		$data['title']='New Enquiry';
		$gid=$this->input->post('amc_enq_id');

		$this->load->model('Sales_model');
		$this->Amc_model->update_enquiry_data($gid);
		echo $this->db->last_query()."<br>";

		$this->session->set_flashdata('success', 'Data Updated Successfully..');
		redirect('AMC/view_enquiry_list');
	}
	function delete_enquiry()
	{
		$enquiry_id=$this->input->post('enquiry_id');
		$res = $this->Amc_model->delete_enquiry($enquiry_id);
		echo $res;
	}
	/////////////////////// New quotation /////////////////////////////////////
	function add_quotation()
	{
		$data['title']='Add New AMC ';

		
		$data['enq_records']=$this->Amc_model->get_amc_enquiry_list_for_qtn();
		// $data['products']=$this->Product_model->get_product_list();
		$prifix='ADL/AQT/';
		$num = $this->Setup_model->get_next_code($prifix,'quotation_code','amc_quotation_master',9)+1;
		$digit=sprintf("%1$04d",$num);
		$code =$prifix.$digit;
		$data['code'] =$code;
		$data['vat_percent']=$this->Setup_model->get_vat_for_calculation();
		// $data['currency_list']=$this->Setup_model->get_currency_list();
	    // $data['bank_details']=$this->Setup_model->get_company_bank_list();	
		// $data['terms_rec']=$this->Setup_model->get_terms_all_details();
		$data['user_records']=$this->Users_model->get_user_list();
		// $data['scope_records']=$this->Amc_model->get_scope_of_work();
		// $data['service_scheme_records'] = $this->Amc_model->get_service_schemes();
		$this->load->model('Sales_model');
		// $data['qtn_records']=$this->Sales_model->get_all_quotation_list();
		// $data['products']=$this->Product_model->get_product_list();		
		$data['main_content']='amc/quotation_add.php';
		// echo '<pre>';print_r($data);exit;		
		$this->load->view('includes/template.php',$data);
	}
	function add_quotation_data()
	{
		$this->load->model('Sales_model');
		$insert_id = $this->Amc_model->add_quotation_data();

		if($insert_id!=''){
			$this->session->set_flashdata('success', 'Data Saved Successfully..');
			redirect('AMC/view_quotation_list');
		}
	}
	function view_quotation_list()
	{
		$data['title']='AMC Quotation List';
		$this->load->model('Sales_model');
		$data['records']=$this->Amc_model->get_quotation_list();

		$data['main_content']='amc/quotation_list.php';
		$this->load->view('includes/template.php',$data);
	}
	function print_quotation()
{
    $data['title'] = 'Quotation Print';

    $id = $this->uri->segment('3');  
    $data['rev_version'] = $this->uri->segment('4');  
    $enq_type = $this->uri->segment('5');    
    $data['disc'] = $this->uri->segment('6'); 
    $data['l_head'] = $this->uri->segment('7'); 

    $this->load->model('Setup_model');

    $data['vat_percent'] = $this->Setup_model->get_vat_for_calculation();

    $data['records1'] = $this->Amc_model->get_quotation_master_by_id($id);

    if (empty($data['records1'])) {
        show_error("Quotation not found");
    }

    // ✔ take first record correctly
    $master = $data['records1'][0];
		$data['quotation_info'] = $this->Amc_model->get_amc_quotation_info($master->quote_id);


    $data['comapny_records'] = $this->Setup_model->get_company_details();
    $data['records2'] = $this->Amc_model->get_quotation_tr_by_id($id, $data['rev_version']);
    $data['records3'] = $this->Amc_model->get_work_scope_by_id($master->scope_work);

	$data['sla_records'] = $this->Amc_model->get_quotation_sla_by_id($id);
$data['annexure_records'] = $this->Amc_model->get_quotation_annexure_by_id($id);
    // ✔ correct branch id usage
    $branch_id = $master->branch_id;
    $branch_details = $this->Company_model->get_branch_by_id($branch_id);

	$header = ltrim(str_replace('./', '', $branch_details->branch_header), '/');
$footer = ltrim(str_replace('./', '', $branch_details->branch_footer), '/');

$data['headerPath'] = base_url($header);
$data['footerPath'] = base_url($footer);

//    $data['headerPath'] = base_url() . ltrim($branch_details->branch_header, '/');
// $data['footerPath'] = base_url() . ltrim($branch_details->branch_footer, '/');
	// DOMPDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);

    $html = $this->load->view('amc/print/amc_quotation_print', $data, true);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $dompdf->stream("amc_$id.pdf", ["Attachment" => 0]);

    // $this->load->view('amc/print/amc_quotation_print.php', $data);
}
	function edit_quotation()
	{
		$data['title']='Edit Quotation';
		$id = $this->uri->segment('3');
		$version = $this->uri->segment('4');
		$data['edit_flag'] = $this->uri->segment('5');

		$this->load->model('Users_model');
		$data['cust_records'] = $this->Users_model->get_active_customer_list();
		$this->load->model('Setup_model');
		$data['vat_percent']=$this->Setup_model->get_vat_for_calculation();
		$data['currency_list']=$this->Setup_model->get_currency_list();
	    $data['bank_details']=$this->Setup_model->get_company_bank_list();
		$data['terms_rec']=$this->Setup_model->get_terms_details();
		$data['payment_terms']=$this->Setup_model->get_payment_terms();
		$data['records1']=$this->Amc_model->get_quotation_master_by_id($id);		
		$data['records2']=$this->Amc_model->get_quotation_tr_by_id($id,$version);
		$data['scope_records']=$this->Amc_model->get_scope_of_work();
		$data['records3']=$this->Amc_model->get_quotation_scope_by_id($id);
		$data['service_scheme_records'] = $this->Amc_model->get_service_schemes();
		$enq_type = $data['records1'][0]->enq_type;
		$this->load->model('Product_model');
		//$data['products']=$this->Product_model->get_product_list_by_category($enq_type);
		// echo '<pre>';print_r($data);exit;

		$data['main_content']='amc/quotation_edit.php';
		$this->load->view('includes/template.php',$data);
	}

	function update_quotation_data()
	{
		$qid=$this->input->post('quote_id');
		$this->Amc_model->update_quotation_data($qid);
		$this->session->set_flashdata('success', 'Data Updated Successfully..');
		redirect('AMC/view_quotation_list');
	}
	function get_invoice_code()
	{
	  	$prifix='DEX/AI/'.date('y').'/';
		$this->load->model('Setup_model');
		$num = $this->Setup_model->get_next_code($prifix,'invoice_code','amc_invoice_master',11)+1;
		$digit=sprintf("%1$04d",$num);
		$data['code'] =$prifix.$digit;
		 echo $data['code'];
	}
	public function add_invoice()
{
    $data['title']='Generate AMC Agreement';
    $data['records']=$this->Amc_model->get_amc_quotations_for_invoice();

    $prifix='ADL/AI/'.date('y').'/';
    $this->load->model('Setup_model');
    $num = $this->Setup_model->get_next_code($prifix,'invoice_code','amc_invoice_master',11)+1;
    $digit=sprintf("%1$04d",$num);
    $data['code'] =$prifix.$digit;        

    $data['vat_percent']=$this->Setup_model->get_vat_for_calculation();
    $data['currency_list']=$this->Setup_model->get_currency_list();

    // ❌ DO NOT load banks here
    $data['bank_details'] = [];  

    $this->load->model('Users_model');
    $data['user_records']=$this->Users_model->get_user_list();
    $data['cust_records'] = $this->Users_model->get_active_customer_list();

    $data['main_content']='amc/invoice_add.php';
    $this->load->view('includes/template.php',$data);
}
	function add_invoice_data()
	{
		$data['title']='Add New Invoice';
		$insert_id = $this->Amc_model->add_invoice_data();

		if($insert_id!=''){
			$this->session->set_flashdata('success', 'Data Saved Successfully..');
			redirect('AMC/view_invoice_list');
		}
	}

	function edit_amc()
{
    $data['title'] = 'Edit Invoice';

    $id = $this->uri->segment(3);
    $data['edit_flag'] = $this->uri->segment(4);

    // ✅ master + items structure
    $data['records1'] = $this->Amc_model->get_invoice_master_by_id($id);
	$quote_id = !empty($data['records1']) ? $data['records1']->quote_id : 0;
	$data['quotation_info'] = $this->Amc_model->get_amc_quotation_info($quote_id);
	$branch_id = !empty($data['records1']) ? $data['records1']->branch_id : null;

    // (optional: if still using separate query for safety, keep it)
    $data['records2'] = $this->Amc_model->get_invoice_tr_by_id($id);
	$data['sla_records'] = $this->Amc_model->get_invoice_sla_by_id($id);
$data['annexure_records'] = $this->Amc_model->get_invoice_annexure_by_id($id);
    $this->load->model('Setup_model');

    $data['vat_percent']   = $this->Setup_model->get_vat_for_calculation();
    $data['currency_list'] = $this->Setup_model->get_currency_list();
    $data['bank_details']  = $this->Setup_model->get_company_bank_list($branch_id);

    $this->load->model('Users_model');
    $this->load->model('Product_model');

    $data['user_records'] = $this->Users_model->get_user_list();
    $data['cust_records'] = $this->Users_model->get_active_customer_list();
    $data['brand_list']   = $this->Product_model->get_brand_list();

    $data['main_content'] = 'amc/invoice_edit.php';

    $this->load->view('includes/template.php', $data);
}

	function update_invoice_data()
	{
		$data['title']='Edit Invoice';
		$gid=$this->input->post('invoice_id');

		$this->Amc_model->update_invoice_data($gid);
		$this->session->set_flashdata('success', 'Data Updated Successfully..');
		redirect('AMC/view_invoice_list');
	}
	function reminders(){
		$data['title']='Reminder List';
		$data['records']=$this->Amc_model->get_reminder_list();
		$data['ppmrecords']=$this->Amc_model->get_ppm_rem_list();
		$data['main_content']='amc/amc_reminder_list.php';
		$this->load->view('includes/template.php',$data);
	}
	function reports(){
		$data['title']		='AMC Reports';
		$data['from']		= date('01-m-Y');
		$data['to']			= date('d-m-Y');
		$data['date_type'] 	= '';
		$data['cust_id'] 	= '';
		$data['project_name'] 	= '';
		$data['rpt_type'] = '';
		$data['records']=$this->Amc_model->get_reminder_list();
		$data['amc_customer_list']=$this->Amc_model->get_amc_customer_list();
		$data['amc_project_list']=$this->Amc_model->get_amc_project_list();
		$data['main_content']='amc/amc_reports.php';
		//echo '<pre>';print_r($data);exit;
		$this->load->view('includes/template.php',$data);
	}
	function get_reports(){
		$data['title']='AMC Reports';	
		$data['from'] 		= $this->input->post('from');
		$data['to'] 		= $this->input->post('to');
		//$data['cmp_type'] = $this->input->post('cmp_type');
		$data['date_type'] 	= $this->input->post('date_type');
		$data['cust_id'] 	= $this->input->post('customer_id');
$data['project_name'] = $this->input->post('project_name') ?? '';
		$data['rpt_type'] = $this->input->post('rpt_type');
		$data['amc_customer_list']=$this->Amc_model->get_amc_customer_list();
		$data['amc_quotation_list']=$this->Amc_model->get_amc_quotation_list();	
		$data['amc_project_list']=$this->Amc_model->get_amc_project_list();
		$data['amc_list']=$this->Amc_model->get_amc_report();
		$data['main_content']='amc/amc_reports.php';
		//echo '<pre>';print_r($data);exit;
		$this->load->view('includes/template.php',$data);
	}
function quotation_reports(){
		$data['title']		='AMC Quotation Reports';
		$data['from']		= date('01-m-Y');
		$data['to']			= date('d-m-Y');
		$data['date_type'] 	= '';
		$data['customer_id'] 	= '';
		$data['project_name'] 	= '';
		$data['status'] = $this->input->post('status');
		
		$data['amc_customer_list']=$this->Amc_model->get_amc_customer_list();
		$data['amc_quotation_list'] = []; 
		
		$data['main_content']='amc/amc_quotation_report.php';
		//echo '<pre>';print_r($data);exit;
		$this->load->view('includes/template.php',$data);
	}
	function get_quotation_report(){
		$data['title']='AMC Quotation Reports';	
		$data['from'] 		= $this->input->post('from');
		$data['to'] 		= $this->input->post('to');
		
		$data['date_type'] 	= $this->input->post('date_type');
		$data['customer_id'] 	= $this->input->post('customer_id');
		$data['project_name'] 	= $this->input->post('project_name');
		 $data['status'] = $this->input->post('status');
		$data['amc_customer_list']=$this->Amc_model->get_amc_customer_list();
		$data['amc_quotation_list']=$this->Amc_model->get_amc_quotation_report();	
		
		$data['main_content']='amc/amc_quotation_report.php';
		//echo '<pre>';print_r($data);exit;
		$this->load->view('includes/template.php',$data);
	}
function print_quotation_report()
  {
    $data['from'] = $this->input->post('from');
    $data['to'] = $this->input->post('to');
    $data['status'] = $this->input->post('status');
    $data['customer_id'] = $this->input->post('customer_id');
    $data['quotation_id'] = $this->input->post('quotation_id');

    $this->load->model('Setup_model');
    $data['customer_list'] = $this->Users_model->get_customer_list();
    $data['comapny_records'] = $this->Setup_model->get_company_master_list();
   
    $this->load->model('Report_Model');
    $data['records'] =$this->Amc_model->get_amc_quotation_report();

    $this->load->view('Print/quotation_report_print.php', $data);
  }

  function export_quotation_report()
  {
    $data['from'] = $this->input->post('from');
    $data['to'] = $this->input->post('to');
    $data['status'] = $this->input->post('status');
    $data['customer_id'] = $this->input->post('customer_id');
    $data['quotation_id'] = $this->input->post('quotation_id');

    $this->load->model('Setup_model');
   // $data['customer_list'] = $this->Setup_model->get_customer_list();

    $this->load->model('Report_Model');
    $data['records'] = $this->Amc_model->get_amc_quotation_report();

    $this->load->view('excel_reports/amc_quotation_report_export.php', $data);
  }

	function view_invoice_list(){
		$data['title']='AMC List';
		$data['records']=$this->Amc_model->get_amc_list();
		$data['main_content']='amc/amc_list.php';
		$this->load->view('includes/template.php',$data);
	}
public function print_amc()
{
    $id = $this->uri->segment(3);

    // ❌ REMOVE URI PRINT TYPE (better from DB)
    $records1 = $this->Amc_model->get_invoice_master_by_id($id);

    if (!$records1) {
        show_error('AMC record not found');
    }
	$quote_id = $records1->quote_id;

    $data['records1'] = $records1;
		 $data['quotation_info'] = $this->Amc_model->get_amc_quotation_info($quote_id);

	$data['sla_records'] = $this->Amc_model->get_invoice_sla_by_id($id);
$data['annexure_records'] = $this->Amc_model->get_invoice_annexure_by_id($id);

    // ✔ PRINT TYPE FROM DB (quotation table)
    $data['print_type_text'] = ($records1->quot_print_type == 1)
        ? 'NON-COMPREHENSIVE'
        : 'COMPREHENSIVE';

    $data['records2'] = $this->Amc_model->get_invoice_tr_by_id($id, 0);
    $data['vat_percent'] = $this->Setup_model->get_vat_for_calculation();

    // Branch
    $branch_id = $records1->branch_id;
    $branch_details = $this->Company_model->get_branch_by_id($branch_id);
	

    if ($branch_details && !empty($branch_details->branch_header)) {
    $data['headerPath'] = base_url($branch_details->branch_header);
} else {
    $data['headerPath'] = ''; // or default header image
}

if ($branch_details && !empty($branch_details->branch_footer)) {
    $data['footerPath'] = base_url($branch_details->branch_footer);
} else {
    $data['footerPath'] = ''; // or default footer image
}



    // DOMPDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);

    $html = $this->load->view('amc/print/amc_print', $data, true);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $dompdf->stream("amc_$id.pdf", ["Attachment" => 0]);
}
	function delete_amc(){
		$invoice_id = $this->input->post('invoice_id');
		$quote_id 	= $this->input->post('quote_id');
		$res = $this->Amc_model->delete_invoice($quote_id,$invoice_id);
		echo $res;
	}
	function add_complaint()
	{

		$data['title']='Complaint Registration';
		$data['records']=$this->Amc_model->get_amc_list();
		//echo '<pre>';print_r($data['records']);exit;
		$prifix='ADL/CP/'.date('y').'/';
		$this->load->model('Setup_model');
		$num = $this->Setup_model->get_next_code($prifix,'cmp_code','amc_complaint_master',11)+1;
		$digit=sprintf("%1$04d",$num);
		$data['code'] =$prifix.$digit;		
		$data['user_records']=$this->Users_model->get_user_list();
		$data['amc_products']=$this->Product_model->get_amc_product_list();
		$data['main_content']='amc/complaint_add.php';
		$this->load->view('includes/template.php',$data);
	}

	function add_complaint_data(){
		$data['title']='Add Complaint Data';
		$insert_id = $this->Amc_model->add_complaint_data();

		if($insert_id!=''){
			$this->session->set_flashdata('success', 'Data Saved Successfully..');
			redirect('AMC/view_complaint');
		}
	}
	function view_complaint(){
		$data['title']='Complaint List';
		$data['records']=$this->Amc_model->get_complaint_list();
		$data['main_content']='amc/complaint_list.php';
		$this->load->view('includes/template.php',$data);
	}
	function edit_complaint(){
		$data['title']='Complaint Edit';
		$id = $this->uri->segment('3');
		$data['records']=$this->Amc_model->get_complaint_list_with_id($id);
		$data['tr_records1']=$this->Amc_model->get_cmp_details1($id);
		$data['tr_records2']=$this->Amc_model->get_cmp_details2($id);
		$data['user_records']=$this->Users_model->get_user_list();
		$data['amc_products']=$this->Product_model->get_amc_product_list();
		$data['main_content']='amc/complaint_edit.php';
		//echo '<pre>';print_r($data);exit;
		$this->load->view('includes/template.php',$data);
	}
	function update_complaint_data()
	{
		$data['title']='Update Complaint';
		$cid=$this->input->post('cmp_id');

		$this->Amc_model->update_complaint_data($cid);
		

		$this->session->set_flashdata('success', 'Data Updated Successfully..');
		redirect('AMC/view_complaint');
	}
	function complaint_report()
	{
		$data['title'] = "Complaint Report";
		$data['from']=date('01-m-Y');
		$data['to']=date('d-m-Y');
		$data['status'] ="";
		$data['project_name'] =""; 
		$data['technician'] =""; 
		$data['product_code'] ="";
		$data['rpt_type'] = "";
		$data['customer_list']=$this->Users_model->get_customer_list();

		$this->load->model('Reports_model');
		// $data['user_records']=$this->Users_model->get_user_list_id(2);
		$data['records']=array();
		$data['projects']=$this->Amc_model->get_complaint_records_project();
		// $data['amc_products']=$this->Product_model->get_amc_product_list();
		$data['main_content']='amc/complaint_report.php';
		$this->load->view('includes/template.php',$data);
	}
	function get_complaint_report()
	{
		$data['title'] = "Complaint Report";
		$data['from'] = $this->input->post('from');
		$data['to'] = $this->input->post('to');
		$data['status'] =$this->input->post('status');
		$data['rpt_type'] = $this->input->post('rpt_type');
		//$data['cmp_type'] =$this->input->post('cmp_type');
		$data['project_name'] =$this->input->post('project_name');
		$data['technician'] =$this->input->post('technician');
		$data['product_code'] =$this->input->post('product_code');
		$data['user_records']=$this->Users_model->get_user_list();	
		if($data['from']==''){
			$data['from'] = $this->uri->segment('3');
			$data['to'] = $this->uri->segment('4');}
		
		$data['customer_list']=$this->Users_model->get_customer_list();
		$data['records']=$this->Amc_model->get_complaint_records();
		$data['projects']=$this->Amc_model->get_complaint_records_project();
		$data['amc_products']=$this->Product_model->get_amc_product_list();
		$data['main_content']='amc/complaint_report.php';
		$this->load->view('includes/template.php',$data);
	}
	function print_complaint_report()
	{
	$data['from'] = $this->input->post('from');
	$data['to'] = $this->input->post('to');
	$data['status'] =$this->input->post('status');
	$data['rpt_type'] = $this->input->post('rpt_type');

	$this->load->model('Users_model');
	$data['customer_list']=$this->Users_model->get_customer_list();
	$data['supplier_records']=$this->Users_model->get_supplier_list();
	$data['comapny_records']=$this->Setup_model->get_company_master_list();
	$data['records']=$this->Amc_model->get_complaint_records();
	$this->load->view('amc/print/print_complaint_report.php',$data);
	}

	function print_amc_report()
	{
	$data['from'] 		= $this->input->post('from');
	$data['to'] 		= $this->input->post('to');
	$data['date_type'] 	=$this->input->post('date_type');
	$data['cust_id'] 	= $this->input->post('customer_id');
	$data['quote_id'] 	= $this->input->post('quotation_id');
	$data['amc_customer_list']=$this->Amc_model->get_amc_customer_list();
	$data['amc_quotation_list']=$this->Amc_model->get_amc_quotation_list();	
	$data['records']=$this->Amc_model->get_amc_report();
	$this->load->model('Users_model');
	$data['amc_list']=$this->Amc_model->get_amc_report();
	$data['customer_list']=$this->Users_model->get_customer_list();
	
	$data['comapny_records']=$this->Setup_model->get_company_master_list();
	$this->load->view('amc/print/print_amc_report.php',$data);
	}

	function delete_cmp()
	{
		$id=$this->input->post('cmp_id');
		$res = $this->Amc_model->delete_cmp($id);
		echo $res;
	}
	function add_ppm(){
		$data['title']='AMC PPM Schedule';
		$data['records']=$this->Amc_model->get_amc_list();
		//echo '<pre>';print_r($data['records']);exit;
		$prifix='ADL/PP/'.date('y').'/';
		$this->load->model('Setup_model');
		$num = $this->Setup_model->get_next_code($prifix,'ppm_code','amc_ppm_master',11)+1;
		$digit=sprintf("%1$04d",$num);
		$data['code'] =$prifix.$digit;		
		// $data['user_records']=$this->Users_model->get_user_list_id(2);
		$data['main_content']='amc/ppm_add.php';
		$this->load->view('includes/template.php',$data);
	}
	function add_ppm_data(){
		$data['title']='Add PPM Data';
		$insert_id = $this->Amc_model->add_ppm_data();

		if($insert_id!=''){
			$this->session->set_flashdata('success', 'Data Saved Successfully..');
			redirect('AMC/view_ppm');
		}
	}
	function view_ppm(){
		$data['title']='PPM List';
		$data['records']=$this->Amc_model->get_ppm_list();
		$data['main_content']='amc/ppm_list.php';
		$this->load->view('includes/template.php',$data);
	}
	

	function edit_ppm(){
    $data['title'] = 'PPM Edit';

    $id = $this->uri->segment(3);
    $data['source'] = $this->uri->segment(4); // ppm_list or reminder

    $data['records'] = $this->Amc_model->get_ppm_with_id($id);
    $data['summary_records'] = $this->Amc_model->get_ppm_summ_with_id($id);
    $data['detail_records'] = $this->Amc_model->get_ppm_details_with_id($id);

    $data['main_content'] = 'amc/ppm_edit.php';
    $this->load->view('includes/template.php', $data);
}
	function update_ppm_data()
{
    $data['title'] = 'PPM Update';

    $pid = $this->input->post('ppm_id');

    // update model
    $this->Amc_model->update_ppm_data($pid);

    $this->session->set_flashdata('success', 'Data Updated Successfully..');

    // 👇 get source (reminder / ppm_list)
    $source = $this->input->post('source');

    if ($source == 'reminder') {
        redirect('AMC/reminders');
    } else {
        redirect('AMC/view_ppm');
    }

    exit;
}
	function delete_ppm()
	{
		$id=$this->input->post('id');
		$res = $this->Amc_model->delete_ppm($id);
		echo $res;
	}

	////////////////////// QUOT direct ///////////////////////////
	
	function add_direct_quotation()
	{
		$data['title']='Direct AMC Quotation';
		
		$this->load->model('Setup_model');
		
		$prifix='ADL/AQT/';
		$num = $this->Setup_model->get_next_code($prifix,'quotation_code','amc_quotation_master',9)+1;
		$digit=sprintf("%1$04d",$num);
		$code =$prifix.$digit;
		$data['Code'] =$code;
		
		$this->load->model('Company_model');
				$this->load->model('Item_model');
						$this->load->model('Setup_model');

			$data['branch_list']   = $this->Company_model->get_all_branches();
			$data['customer_list'] = $this->Company_model->get_all_customer_list();

		// $data['products1']=$this->Product_model->get_product_list_by_category('1');
		// $data['products3']=$this->Product_model->get_product_list_by_category('3');
		// $data['brand_list']=$this->Product_model->get_brand_list();
		$this->load->model('Users_model');
		// $data['cust_records'] = $this->Users_model->get_active_customer_list();
		$data['user_records']=$this->Users_model->get_user_list();

			$data['active_users']  = $this->Setup_model->get_active_user_list();
			$data['all_products']  = $this->Item_model->get_all_item_list();
			$data['active_units']  = $this->Item_model->get_all_units();

		$data['vat_percent']=$this->Setup_model->get_vat_for_calculation();
		// $data['currency_list']=$this->Setup_model->get_currency_list();
		// $data['bank_details']=$this->Setup_model->get_company_bank_list();
		// $data['terms_rec']=$this->Setup_model->get_terms_details();
		// $data['stamp_details']=$this->Setup_model->get_company_stamp_list();
		// $data['unit_list']=$this->Setup_model->get_unit_list();

		$this->load->model('Users_model');
		$data['user_records']=$this->Users_model->get_user_list();
		//$data['supplier_records']=$this->Users_model->get_supplier_list();
		$data['cust_records'] = $this->Users_model->get_active_customer_list();

		$this->load->model('Product_model');
		// $data['packing_list']=$this->Setup_model->get_packing_type_list();
		// $data['products']=$this->Product_model->get_product_list();
		// $data['pterm_records']=$this->Setup_model->get_payment_terms();
		// $data['dterm_records']=$this->Setup_model->get_delivery_terms();
		// $data['gterm_records']=$this->Setup_model->get_general_terms();
		// $data['country_list']=$this->Setup_model->get_country_list();
		// $data['brand_list']=$this->Product_model->get_brand_list();
		// $data['cat_records']=$this->Product_model->get_main_category_list();
		
		$data['main_content']='amc/quotation_add_direct.php';
		$this->load->view('includes/template.php',$data);
	}

	function add_quot_direct()
  	{    
    	$data['title']='Direct Quotation Create';
	   $this->Amc_model->add_quot_direct();
	    
	   $this->session->set_flashdata('success', 'Data Saved Successfully..');
	   redirect('AMC/quotation_direct_list');
   }

   	function quotation_direct_list()
	{
		$data['title']='AMC Quotation List';
		$this->load->model('Sales_model');
		$data['records']=$this->Amc_model->get_quotation_list();

		$data['main_content']='amc/quotation_list_direct.php';
		$this->load->view('includes/template.php',$data);
	}

	function edit_direct_quotation()
	{
		$data['title']='Edit Quotation';
		$id = $this->uri->segment('3');
		$version = $this->uri->segment('4');
		$data['edit_flag'] = $this->uri->segment('5');

		$this->load->model('Users_model');
		// $data['cust_records'] = $this->Users_model->get_active_customer_list();
		$this->load->model('Setup_model');
		$data['vat_percent']=$this->Setup_model->get_vat_for_calculation();
		// $data['currency_list']=$this->Setup_model->get_currency_list();
	    // $data['bank_details']=$this->Setup_model->get_company_bank_list();
		// $data['terms_rec']=$this->Setup_model->get_terms_details();
		// $data['payment_terms']=$this->Setup_model->get_payment_terms();
		$data['records1']=$this->Amc_model->get_quotation_master_by_id($id);
		$data['sla_records'] = $this->Amc_model->get_quotation_sla_by_id($id);
$data['annexure_records'] = $this->Amc_model->get_quotation_annexure_by_id($id);
				
		$data['records2']=$this->Amc_model->get_quotation_tr_by_id($id,$version);
		$data['scope_records']=$this->Amc_model->get_scope_of_work();
		$data['records3']=$this->Amc_model->get_quotation_scope_by_id($id);
		// $data['service_scheme_records'] = $this->Amc_model->get_service_schemes();
		$enq_type = $data['records1'][0]->enq_type;
		$this->load->model('Product_model');
		//$data['products']=$this->Product_model->get_product_list_by_category($enq_type);
		// echo '<pre>';print_r($data);exit;

		$data['main_content']='amc/quotation_edit_direct.php';
		$this->load->view('includes/template.php',$data);
	}

	function update_quot_direct()
	{
		$data['title']='Update Direct Quotation';
		$qid=$this->input->post('quote_id');

		$this->load->model('Amc_Model');
		$this->Amc_Model->update_direct_quotation_data($qid);

		$this->session->set_flashdata('success', 'Data Updated Successfully..');
		redirect('AMC/quotation_direct_list');
	}

	public function get_branch_banks()
{
    $branch_id = $this->input->post('branch_id');
			$this->load->model('Setup_model');


    $bank_details = $this->Setup_model->get_company_bank_list($branch_id);
	

    if (!empty($bank_details)) {
        foreach ($bank_details as $r) {
            echo "<tr style='font-size:13px;'>
                    <td width='30px'>
                        <input type='radio' name='bank' value='{$r->bid}' checked>
                        <input type='hidden' name='trans_id[]' value='{$r->bid}'>
                    </td>
                    <td><input type='text' class='form-control' value='{$r->bank_name}' readonly></td>
                    <td><input type='text' class='form-control' value='{$r->bank_account}' readonly></td>
                    <td><input type='text' class='form-control' value='{$r->bank_branch}' readonly></td>
                    <td><input type='text' class='form-control' value='{$r->bank_iban}' readonly></td>
                    <td><input type='text' class='form-control' value='{$r->bank_swift}' readonly></td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6' class='text-center text-danger'>No banks found for this branch</td></tr>";
    }
}
function ppm_report()
{
    $data['title'] = "PPM Report";

    $data['from'] = $this->input->post('from') ?? date('Y-m-01');
    $data['to']   = $this->input->post('to') ?? date('Y-m-d');

    $data['project_name'] = "";
    $data['status'] = "";
    $data['ppm_code'] = "";

    // FIX DEFAULT HERE ALSO
    $data['rpt_type'] = $this->input->post('rpt_type') ?? "Summary";

    $this->load->model('Amc_model');

    $data['projects'] = $this->Amc_model->get_ppm_project_list();
    $data['records']  = $this->Amc_model->get_ppm_records();
    $data['main_content'] = 'amc/ppm_report.php';
    $this->load->view('includes/template.php', $data);
}

function get_ppm_report()
{
     $data['title'] = "PPM Report";

     $data['from'] = $this->input->post('from') ?? date('Y-m-01');
    $data['to']   = $this->input->post('to') ?? date('Y-m-d');

    $data['status']       = $this->input->post('status');
    $data['project_name'] = $this->input->post('project_name');
    $data['ppm_code']     = $this->input->post('ppm_code');

    $data['rpt_type'] = $this->input->post('rpt_type');

    if (empty($data['rpt_type'])) {
        $data['rpt_type'] = "Summary";
    }

    $this->load->model('Amc_model');

    $data['projects'] = $this->Amc_model->get_ppm_project_list();

    $data['records'] = $this->Amc_model->get_ppm_records(
        $data['from'],
        $data['to']
    );

    // attach details for detailed view
    foreach ($data['records'] as $r) {
        $r->details = $this->Amc_model->get_ppm_details($r->ppm_id);
    }
    $data['main_content'] = 'amc/ppm_report.php';
    $this->load->view('includes/template.php', $data);
}

function print_ppm_report()
	{
	$data['from'] = $this->input->post('from');
	$data['to'] = $this->input->post('to');
	// $data['status'] =$this->input->post('status');
	// $data['rpt_type'] = $this->input->post('rpt_type');
    $this->load->model('Amc_model');

	$data['records'] = $this->Amc_model->get_ppm_records(
        $data['from'],
        $data['to']
    );

	$this->load->model('Users_model');
	$data['customer_list']=$this->Users_model->get_customer_list();
	$data['supplier_records']=$this->Users_model->get_supplier_list();
	$data['comapny_records']=$this->Setup_model->get_company_master_list();
	// $data['records']=$this->Amc_model->get_complaint_records();
	 $branch_id = 1;
    $branch_details = $this->Company_model->get_branch_by_id($branch_id);

	$header = ltrim(str_replace('./', '', $branch_details->branch_header), '/');
$footer = ltrim(str_replace('./', '', $branch_details->branch_footer), '/');

$data['headerPath'] = base_url($header);
$data['footerPath'] = base_url($footer);
	$this->load->view('amc/print/print_ppm_report.php',$data);
	}

public function generate_ppm_invoice()
{
    $ppm_summary_id = $this->input->post('ppm_summary_id');

    $this->load->model('AMC_model');

    $ppm = $this->AMC_model->get_ppm_invoice_data($ppm_summary_id);

    if(empty($ppm)){
        echo json_encode([
            'status' => false,
            'message' => 'Data not found'
        ]);
        return;
    }

    // prevent duplicate invoice
    if($ppm->invoice_generated == 1){
        echo json_encode([
            'status' => false,
            'message' => 'Invoice already generated'
        ]);
        return;
    }

    $invoice_no = 'PPMINV/'.date('y').'/'.rand(1000,9999);

    $this->db->insert('ppm_invoice_master', array(
        'ppm_summary_id' => $ppm->id,
        'ppm_id'         => $ppm->ppm_id,
        'quote_id'       => $ppm->quote_id,
        'invoice_no'     => $invoice_no,
        'invoice_date'   => date('Y-m-d'),
        'customer_id'    => $ppm->customer_id,
        'project_name'   => $ppm->project_name,
        'amount'         => $ppm->ppm_amt,
        'created_by'     => $this->session->userdata('user_id')
    ));

    $invoice_id = $this->db->insert_id();

    $this->db->where('id', $ppm_summary_id);
    $this->db->update('amc_ppm_summary', array(
        'invoice_generated' => 1,
        'invoice_id'        => $invoice_id
    ));

    echo json_encode([
        'status' => true,
        'message' => 'Invoice Generated Successfully',
        'invoice_id' => $invoice_id
    ]);
}

public function print_ppm_invoice($invoice_id)
{
    $this->load->model('AMC_model');

    // invoice header
    $data['invoice'] = $this->AMC_model->get_ppm_invoice_by_id($invoice_id);

    if (empty($data['invoice'])) {
        show_404();
    }

    // ppm details
    $data['ppm_details'] = $this->AMC_model->get_ppm_summary_by_invoice($invoice_id);

    // branch details
    $branch_id = 1;
    $branch_details = $this->Company_model->get_branch_by_id($branch_id);

    if (!empty($branch_details)) {

        $header = ltrim(str_replace('./', '', $branch_details->branch_header), '/');
        $footer = ltrim(str_replace('./', '', $branch_details->branch_footer), '/');

        $data['headerPath'] = base_url($header);
        $data['footerPath'] = base_url($footer);

    } else {
        $data['headerPath'] = '';
        $data['footerPath'] = '';
    }

    $data['title'] = 'PPM Invoice Print';

    $this->load->view('amc/print/print_ppm_invoice', $data);
}
}