<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Stock extends CI_Controller
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
    /////////////////////Stock Adjustment  ////////////////////////
   function stock_adjustment()
   {
        $data['title']='Stock Adjustment';		
		
		$this->load->model('Setup_model');
        $data['products'] = $this->Setup_model->get_active_item_list();
        $data['active_units'] = $this->Setup_model->get_active_unit_list();	
        $data['store_records'] = $this->Setup_model->get_warehouse_list();
		$data['main_content']='stock/stock_adjustment_add.php';
		$this->load->view('includes/template.php',$data);
        
    }
    function stock_adjustment_details()
  {
        $data['title']='Stock Adjustment';	    
        $this->Stock_model->stock_adjustment_details(); 
        $this->session->set_flashdata('success', 'Data Saved Successfully..');
        redirect('Stock/list_stock_adjustment');
  }
   

  function list_stock_adjustment()
  {
        $data['title']='Stock Adjustment List';
        $data['records']=$this->Stock_model->get_stock_adjustment_list();
        $data['main_content']='stock/stock_adjustment_list.php';
        $this->load->view('includes/template.php',$data);
  }

  
   function edit_stock_adjustment()
    {
    
        // if(!has_access($user,'Purchase/list_rfq','E')){
        //     $data['title'] = 'Access Denied';
        //     $data['main_content']='errors/access_control.php';
        // }
        // else{
        $data['title']='Stock Adjustment Edit';
        $doc_id = $this->uri->segment(3);
        $data['products'] = $this->Item_model->get_active_item_list();
        $data['active_units'] = $this->Item_model->get_active_unit_list();	
        $data['store_records'] = $this->Setup_model->get_warehouse_list();
        $data['records1'] = $this->Stock_model->get_stock_adjustment_by_id($doc_id);
        $data['records2'] = $this->Stock_model->get_stock_adjustment_tr($doc_id);
        $data['main_content'] = 'stock/stock_adjustment_edit.php';
        // }
        $this->load->view('includes/template.php', $data);
    }
function update_stock_adjustment_records()
{  
    $this->Stock_model->update_stock_adjustment_records();
    $this->session->set_flashdata('success', 'Data Saved Successfully..');
    redirect('Stock/list_stock_adjustment');
}
  
/////////////////////Minimum Stock////////////////////////
   function min_stock()
   {
        $data['title']='Minimum Stock';		
		
		$this->load->model('Setup_model');
        $data['active_items'] = $this->Setup_model->get_active_item_list();
        $data['active_units'] = $this->Setup_model->get_active_unit_list();	
		$data['main_content']='stock/min_stock_add.php';
		$this->load->view('includes/template.php',$data);
        
    }
    function add_min_stock_records()
  {
        $data['title']='Minimum Stock';	    
        $this->Stock_model->min_stock_add_records(); 
        $this->session->set_flashdata('success', 'Data Saved Successfully..');
        redirect('Stock/list_min_stock');
  }
   

  function list_min_stock()
  {
        $data['title']='Minimum Stock';
        $data['records']=$this->Stock_model->get_min_stock_list();
        $data['main_content']='stock/min_stock_list.php';
        $this->load->view('includes/template.php',$data);
  }

  
   function edit_min_stock()
    {
    
        // if(!has_access($user,'Purchase/list_rfq','E')){
        //     $data['title'] = 'Access Denied';
        //     $data['main_content']='errors/access_control.php';
        // }
        // else{
        $data['title']='Minimum Stock';
        $doc_id = $this->uri->segment(3);
        $data['active_items'] = $this->Item_model->get_active_item_list();
        $data['active_units'] = $this->Item_model->get_active_unit_list();	
        $data['records1'] = $this->Stock_model->get_stock_adjustment_by_id($doc_id);
        $data['records2'] = $this->Stock_model->get_stock_adjustment_tr($doc_id);
        $data['main_content'] = 'Stock/min_stock_edit.php';
        // }
        $this->load->view('includes/template.php', $data);
    }
    function delete_min_stock(){
        $id = $this->uri->segment(3);
        $res = $this->Stock_model->delete_min_stock($id);
	    echo $res;

    }
function update_min_stock_records()
{  
    $this->Stock_model->update_stock_adjustment_records();
    $this->session->set_flashdata('success', 'Data Saved Successfully..');
    redirect('Stock/list_min_stock');
}
function reorder_list()
  {
    $data['title'] = 'Reorder Stock Details';
    $data['warehouse_id'] = 1;
    $data['item_id'] = 1;
    $data['records'] = $this->Stock_model->get_reorder_stock_list();

    $data['main_content'] = 'stock/reorder_stock_details';
    $this->load->view('includes/template.php', $data);
  }

  //Stock Allocation
    public function list_stock_allocations(){
		$user = $this->session->userdata('user_id');
		if (!has_view_access($user,'Stock/list_stock_allocations')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
			
		}
		else{
			$data['title']='Stock Allocations List';

			$data['all_stock_allocations'] = $this->Stock_model->get_all_stock_allocations();

			$data['main_content']='stock/stock_allocations/list_stock_allocations.php';
			
		}
		$this->load->view('includes/template',$data);
		
	}

    public function edit_stock_allocation(){
		$user = $this->session->userdata('user_id');
		if (!has_access($user,'Stock/list_stock_allocations','E')) {
			$data['title'] = 'Access Denied';
			$data['main_content']='errors/access_control.php';
			
		}
		else{
			$data['title']='Edit Stock Allocation';
            $allocation_id = $this->uri->segment('3');
			$data['stock_allocation'] = $this->Stock_model->get_stock_allocation_by_id($allocation_id);
            $data['allocation_details'] = $this->Stock_model->get_stock_allocation_details_by_id($allocation_id);
			$data['main_content']='stock/stock_allocations/edit_stock_allocation.php';
			
		}
		$this->load->view('includes/template',$data);
		
	}

     public function update_stock_allocation_data(){
		$result = $this->Stock_model->update_stock_allocation_data();

		if($result){
			echo 'Added';
		}
		else{
			echo 'Not Added';
		}
		redirect('Stock/list_stock_allocations');
		
	}

    public function get_allocated_stock_details_by_id(){
        $allocation_detail_id = $_POST['allocation_detail_id'];
        $res = $this->Stock_model->get_allocated_stock_details_by_id($allocation_detail_id);
        echo json_encode($res);
    }

    public function update_allocation_details_data(){
       
        $res = $this->Stock_model->update_allocation_details_data();
        echo $res;
    }

    public function get_stock_item_status_by_serial_number(){
        $serial_no = $_POST['serial_no'];
        $res = $this->Stock_model->get_stock_item_status_by_serial_number($serial_no);
        echo $res;
    }

    public function get_item_detail_by_serial_number(){
        $serial_no = $_POST['serial_no'];
        $res = $this->Stock_model->get_item_detail_by_serial_number($serial_no);
        echo json_encode($res);
    }
}