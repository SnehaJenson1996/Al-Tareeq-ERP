<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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
        
    }
    /////////////////////Direct RFQ Start  ////////////////////////
   function add_direct_rfq()
   {
        $data['title']='Request For Quotation(RFQ)-Direct';		
			

        $prifix='AVG/RFQ/';		
        $num = $this->Setup_model->get_next_code($prifix,'rfq_code','purchase_rfq',12)+1;
        $digit=sprintf("%1$05d",$num);
        $data['Code'] =$prifix.date('y').'/'.$digit;	

		$this->load->model('Item_model');
        $data['active_items'] = $this->Item_model->get_active_item_list();
        $data['active_units'] = $this->Item_model->get_active_unit_list();	
		$data['main_content']='Purchase/rfq_direct_add.php';
		$this->load->view('includes/template.php',$data);
        
    }
    function add_direct_rfq_records()
   {    
	   $data['title']='Request For Quotation(RFQ)';
	   $this->load->model('Purchase_Model');
	   $id=$this->Purchase_Model->add_direct_rfq_records();
	   
	   if($id) 
	   {
		$this->session->set_flashdata('success', 'Data Saved Successfully..');
	        redirect('Purchase/list_direct_rfq');
	   }	   
	   else
	   {
		$this->session->set_flashdata('error', 'Data Not Saveded..');
	        redirect('Purchase/add_rfq');
	   }
   }

  function list_direct_rfq()
  {
        $data['title']='Request For Quotation(RFQ)';
        $this->load->model('Purchase_Model');
        $data['records']=$this->Purchase_Model->get_RFQ_list();
        $data['main_content']='Purchase/rfq_direct_list.php';
        $this->load->view('includes/template.php',$data);
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
   
    // if(!has_access($user,'Purchase/list_rfq','E')){
    //     $data['title'] = 'Access Denied';
    //     $data['main_content']='errors/access_control.php';
    // }
    // else{
        $this->load->model('Setup_model');
        $rfq_id = $this->uri->segment('3');
        $data['view_only'] = $this->uri->segment('4');

        if($data['view_only'] == 0){
            $data['title']='Edit RFQ';
        }else{
            $data['title']='View RFQ';
        }
    $data['active_items'] = $this->Item_model->get_active_item_list();
    $data['active_units'] = $this->Item_model->get_active_unit_list();	
    $data['records1'] = $this->Purchase_Model->get_purchase_rfq_by_id($rfq_id);
    $data['records2'] = $this->Purchase_Model->get_purchase_rfq_tr($rfq_id);
    $data['main_content'] = 'Purchase/rfq_direct_edit.php';
    // }
    $this->load->view('includes/template.php', $data);
}
function update_rfq()
{  
    $this->Purchase_Model->update_rfq_records();
    $this->session->set_flashdata('success', 'Data Saved Successfully..');
    redirect('Purchase/purchase_order_list');
}
  
///////////// Supplier Quotation
function add_quote_from_supplier()
{
    $data['title'] = 'Quote From Supplier';
  
    $prifix='AVG/SQT/';
    $this->load->model('Setup_model');
    $num = $this->Setup_model->get_next_code($prifix,'quotation_code','purchase_quotation_master',12)+1;
    $digit=sprintf("%1$04d",$num);
    $data['Code'] =$prifix.date("y").'/'.$digit;
    $this->load->model('Purchase_Model');
    $data['records'] = $this->Purchase_Model->get_RFQ_list('direct');
    $this->load->model('Setup_model');
     $data['supplier_records'] = $this->Setup_model->get_active_supplier_list();
    $data['main_content'] = 'Purchase/quotation_add.php';
    $this->load->view('includes/template.php', $data);

}
function add_purchase_quotation_records()
{
    $data['title'] = 'Purchase Quotation';
    $this->load->model('Purchase_Model');
    $this->Purchase_Model->add_purchase_quotation();

    $this->session->set_flashdata('success', 'Data Saved Successfully..');
    redirect('Purchase/purchase_quotation_list');
}

function purchase_quotation_list()
{
    $data['title'] = 'Purchase Quotation';
    $this->load->model('Purchase_Model');
    $data['records'] = $this->Purchase_Model->get_quotation_list();

    $data['main_content'] = 'Purchase/quotation_list.php';
    $this->load->view('includes/template.php', $data);
}
function edit_quotation()
{
   
    // if(!has_access($user,'Sales/list_quotations','E')){
    //     $data['title'] = 'Access Denied';
    //     $data['main_content']='errors/access_control.php';
    // }
    // else{
        $this->load->model('Setup_model');
        $quotation_id = $this->uri->segment('3');
        $data['view_only'] = $this->uri->segment('4');

        if($data['view_only'] == 0){
            $data['title']='Edit Quotation';
        }else{
            $data['title']='View Quotation';
        }
   
    $data['records1'] = $this->Purchase_Model->get_pur_qtn_master_by_id($quotation_id);
    $data['records2'] = $this->Purchase_Model->get_pur_qtn_tr_by_id($quotation_id);
    $data['quote_doc'] = $this->Purchase_Model->get_quote_doc($quotation_id,"Quote File");
    $data['main_content'] = 'Purchase/quotation_edit.php';
    // }
    $this->load->view('includes/template.php', $data);
}
function update_purchase_quotation()
{  
    $this->Purchase_Model->update_purchase_quotation();
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
    $data['main_content'] = 'Purchase/quotation_details.php';
    $this->load->view('includes/template.php', $data);
}
function print_quote()
   {	
        $user = $this->session->userdata('user_id');
        // if(!has_view_access($user,'Purchase/list_direct_rfq')){
        //     $data['title'] = 'Access Denied';
        //     $data['main_content']='errors/access_control.php';
        //     $this->load->view('includes/template',$data);
        // }
        // else{
            $quotation_id = $this->uri->segment('3');
            $data['quote_tr'] = $this->Purchase_Model->get_pur_qtn_tr_by_id($quotation_id);	
            $data['quote'] = $this->Purchase_Model->get_pur_qtn_master_by_id($quotation_id);
           // echo '<pre>';print_r($data);exit;
            $this->load->view('Purchase/print/quotation_print.php',$data);
            
            
        // }
    }
  function delete_quote()
  {
    $quote_id = $this->uri->segment('3');	
	$this->load->model('Purchase_Model');
	$res = $this->Purchase_Model->delete_quote($quote_id);
	redirect('Purchase/purchase_quotation_list');
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
   ///////////////////////////////////////////////////////////////
  function add_purchase_order()
  {
    $data['title'] = 'Purchase Order';
    $prifix='AVG/POD/';
    $this->load->model('Setup_model');
    $num = $this->Setup_model->get_next_code($prifix,'po_code','purchase_order_master',12)+1;
    $digit=sprintf("%1$04d",$num);
    $data['Code'] =$prifix.date("y").'/'.$digit;
    	
	$data['records']=$this->Purchase_Model->get_quotation_list();
	$this->load->model('Setup_model');

	$data['main_content']='Purchase/po_add.php';
	$this->load->view('includes/template.php',$data);
  }
  function add_po_records()
  {    
       $data['title']='Purchase Order';
	   $this->load->model('Purchase_Model');
	   $this->Purchase_Model->add_purchase_order();
	    
	   $this->session->set_flashdata('success', 'Data Saved Successfully..');
	   redirect('Purchase/purchase_order_list');
   }

  function purchase_order_list()
  {
        $data['title']='Purchase Order List';
	    $this->load->model('Purchase_Model');
        $data['records']=$this->Purchase_Model->get_po_list();
	    
	    $data['main_content']='Purchase/po_list.php';
	    $this->load->view('includes/template.php',$data);
  }
  function print_po(){
    $user = $this->session->userdata('user_id');
        // if(!has_view_access($user,'Purchase/purchase_order_list')){
        //     $data['title'] = 'Access Denied';
        //     $data['main_content']='errors/access_control.php';
        //     $this->load->view('includes/template',$data);
        // }
        // else{
            $po_id = $this->uri->segment('3');
            $data['po_tr'] = $this->Purchase_Model->get_po_tr_by_id($po_id);	
            $data['po'] = $this->Purchase_Model->get_po_master_by_id($po_id);
            
            $this->load->view('Purchase/print/po_print.php',$data);
            
            
        // }
  }
  function approve_po(){
    $po_id = $this->uri->segment('3');
    $this->Purchase_Model->approve_purchase_order($po_id);
    $this->session->set_flashdata('success', 'Approved Successfully..');
    redirect('Purchase/purchase_order_list');
  }
  function edit_po()
{
   
    // if(!has_access($user,'Purchase/purchase_order_list','E')){
    //     $data['title'] = 'Access Denied';
    //     $data['main_content']='errors/access_control.php';
    // }
    // else{
        $this->load->model('Setup_model');
        $po_id = $this->uri->segment('3');
        $data['view_only'] = $this->uri->segment('4');

        if($data['view_only'] == 0){
            $data['title']='Edit Purchase Order';
        }else{
            $data['title']='View Purchase Order';
        }
   
    $data['records1'] = $this->Purchase_Model->get_po_master_by_id($po_id);
    $data['records2'] = $this->Purchase_Model->get_po_tr_by_id($po_id);
    $data['po_doc']   = $this->Purchase_Model->get_quote_doc($po_id,"PO File");
    $data['main_content'] = 'Purchase/po_edit.php';
    //  echo '<pre>';print_r($data);exit;
    // }
    $this->load->view('includes/template.php', $data);
}
function update_purchase_order()
{  
    $this->Purchase_Model->update_purchase_order();
    $this->session->set_flashdata('success', 'Data Saved Successfully..');
    redirect('Purchase/purchase_order_list');
}
function add_PO_direct_from_reorder()
  {
   	$data['title']='Purchase Order-Stock';
    error_reporting(0);

	$this->load->model('Purchase_Model');
	$data['records']=$this->Purchase_Model->get_RFQ_list('direct');	
    $data['active_items'] = $this->Item_model->get_active_item_list();
	$data['active_units'] = $this->Item_model->get_active_unit_list();  
    $this->load->model('Setup_model');
    $data['supplier_records'] = $this->Setup_model->get_active_supplier_list();
    $data['reorder_list']=$this->Stock_model->get_reorder_stock_for_PO();
    echo '<pre>';print_r($data);exit;
	$data['main_content']='Purchase/po_direct_add.php';
	$this->load->view('includes/template.php',$data);
  }
function add_grn()
  {
    $data['title'] = 'Good Received Note';
    $prifix='AVG/GRN/';
    $this->load->model('Setup_model');
    $num = $this->Setup_model->get_next_code($prifix,'grn_code','purchase_grn_master',12)+1;
    $digit=sprintf("%1$04d",$num);
    $data['Code'] =$prifix.date("y").'/'.$digit;
    $data['warehouse_list'] = $this->Setup_model->get_warehouse_list();
	$data['records']=$this->Purchase_Model->get_approved_po_list();
	$this->load->model('Setup_model');

	$data['main_content']='Purchase/grn_add.php';
	$this->load->view('includes/template.php',$data);
  }
  function add_grn_records()
  {    
	   $this->Purchase_Model->add_grn_records();	    
	   $this->session->set_flashdata('success', 'Data Saved Successfully..');
	   redirect('Purchase/purchase_grn_list');
   }
   function purchase_grn_list(){
        $data['title']='Purchase GRN List';
        $data['records']=$this->Purchase_Model->get_grn_list();	    
	    $data['main_content']='Purchase/grn_list.php';
	    $this->load->view('includes/template.php',$data);
   }
   function print_grn(){
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
            $this->load->view('Purchase/print/grn_print.php',$data);
            
            
        // }
  }
  function print_grn_barcode(){
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
            
            $this->load->view('Purchase/print/grn_barcode_print.php',$data);
            
            
        // }
  }
  function delete_grn()
  {
	$grn_id=$this->input->post('grn_id');
	$this->load->model('Purchase_Model');
	$res = $this->Purchase_Model->delete_grn($grn_id);
	echo $res;
  }
}