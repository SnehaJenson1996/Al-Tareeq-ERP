<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Project extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('is_logged_in')) {
            redirect('Login/login');
        }

        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        $this->output->set_header("Cache-Control: post-check=0, pre-check=0", false);
        $this->output->set_header("Pragma: no-cache");

        $this->load->model('Project_model');
        $this->load->model('Sales_model'); 
        $this->load->model('Company_model');
        $this->load->model('Item_model');
    }

    // Add/Edit Project Page
// public function add($project_id = null)
// {
//     $data['title'] = !empty($project_id) ? 'Edit Project' : 'Add Project';

//     $data['project'] = [];
//     $data['project_items'] = [];

//     $data['sales_orders'] = $this->Sales_model->get_all_sales_orders();

//       // Fetch employees and designations for Technician Assignment
//     $data['employees'] = $this->Project_model->get_employees();
//     $data['designations'] = $this->Project_model->get_designations();

//     if ($project_id) {
//         $data['project'] = $this->Project_model->get_project_by_id($project_id);
//         $data['project_items'] = $this->Project_model->get_project_items($project_id);
//     }

//     $data['main_content'] = 'project/add_edit_project';
//     $this->load->view('includes/template', $data);
// }


/* ---------------- ADD PROJECT ---------------- */
    public function add_project()
    {
        $data['title'] = 'Add Project';

        $data['project'] = [];
        $data['project_items'] = [];
        $data['project_technicians'] = [];
        //$data['sales_orders'] = $this->Sales_model->get_all_sales_orders();
        // Check if SO ID is passed in URL
        $eq_id = $this->input->get('eq_id');
        $data['selected_eq_id'] = $eq_id ?? null;

        $data['enquires']   = $this->Project_model->get_enquiries();
        $data['employees']  = $this->Project_model->get_employees();
        $data['designations'] = $this->Project_model->get_designations();
        $data['users']      = $this->db->get('users')->result_array();

        $data['main_content'] = 'project/add_project';
        $this->load->view('includes/template', $data);
    }

    /* ---------------- EDIT PROJECT ---------------- */
    public function edit_project($project_id)
    {
        if (!$project_id) {
            redirect('Project/add');
        }

        $data['title'] = 'Edit Project';

        $data['project'] = $this->Project_model->get_project_by_id($project_id);
        $enq_id = $data['project']['fk_enq_id'];
        $data['enquires']   = $this->Project_model->get_enquiries();
        $data['quotation'] = $this->Project_model->getQuotationByEnquiry($enq_id);
        $data['project_items'] = $this->Project_model->get_project_items_list($project_id);
        $data['project_technicians'] = $this->Project_model->get_project_technicians($project_id);
        $data['sales_orders'] = $this->Sales_model->get_all_sales_orders();
        $data['employees'] = $this->Project_model->get_employees();
        $data['designations'] = $this->Project_model->get_designations();
        $data['users'] = $this->Project_model->get_active_users();
        $data['logged_in_user_id'] = $this->session->userdata('user_id');


        if (empty($data['project'])) {
            show_404();
        }

        $data['main_content'] = 'project/edit_project';
        $this->load->view('includes/template', $data);
    }



public function fetch_so_details()
{
    $so_id = $this->input->post('so_id');

    $so_master = $this->Sales_model->get_sales_order_by_id($so_id);
    // $so_products = $this->Sales_model->get_all_products($so_master['qtn_id']);
    $so_products = $this->Sales_model->get_all_products_by_so($so_id); 

    echo json_encode([
        'so_master'   => $so_master,
        'so_products' => $so_products
    ]);
}


public function save_project()
{
    $this->load->model('Project_model');
    $project_id = $this->input->post('project_id');
    $projectData = [
        //'so_id'            => $this->input->post('so_id'),
        'fk_enq_id'        => $this->input->post('e_id'),
        'fk_quot_id'       => $this->input->post('quotation_id'),
        'project_name'     => $this->input->post('project_name'),
        'project_location' => $this->input->post('project_location'),
        'customer_name'    => $this->input->post('customer_name'),
        'branch_name'      => $this->input->post('branch_name'),
        'start_date'       => $this->input->post('start_date'),
        'end_date'         => $this->input->post('end_date'),
        'duration'         => $this->input->post('duration'),
        'subtotal'         => $this->input->post('subtotal'),
        'vat_percentage'   => $this->input->post('vat_percentage'),
        'vat_amount'       => $this->input->post('vat_amount'),
        'grand_total'      => $this->input->post('grand_total'),
        'remarks'          => $this->input->post('remarks'),
        'approver_id'      => $this->input->post('approver_id'),
        'po_number'        => $this->input->post('po_number'),
        'loa_received'     => $this->input->post('loa_received'),
        'loa_date'         => $this->input->post('loa_date'),
        'subject'          => $this->input->post('subject')
        
    ];

    $this->db->trans_start();
    if ($project_id) {

        $this->Project_model->update_project($project_id, $projectData);
        $message = 'Project updated successfully';
    } else {

        $project_id = $this->Project_model->insert_project($projectData);

        $project_code = 'PRJ-' . str_pad($project_id, 6, '0', STR_PAD_LEFT);
        $this->Project_model->update_project($project_id, [
            'project_code' => $project_code
        ]);

        $message = 'Project saved successfully';
    }

    
    $products   = $this->input->post('product_id');
    $quantities = $this->input->post('quantity');
    $prices     = $this->input->post('unit_price');

    if (!empty($products)) {
        $items = [];
        foreach ($products as $i => $pid) {
            if (!$pid) continue;

            $qty   = $quantities[$i] ?? 0;
            $price = $prices[$i] ?? 0;

            $items[] = [
                'project_id' => $project_id,
                'product_id' => $pid,
                'quantity'   => $qty,
                'unit_price' => $price,
                'total'      => $qty * $price
            ];
        }
        $this->Project_model->save_project_items($project_id, $items);
    }
    

    $tech_ids    = $this->input->post('technician_id');
    $des_ids     = $this->input->post('designation_id');
    $start_dates = $this->input->post('assignment_start');
    $end_dates   = $this->input->post('assignment_end');

    if (!empty($tech_ids)) {
    $techs = [];
    foreach ($tech_ids as $i => $tid) {
        if (!$tid) continue;

         $start = $start_dates[$i] ?? null;
        $end   = $end_dates[$i] ?? null;

        // ------------------------------
        // 1️⃣ Validate Start <= End
        // ------------------------------
        if ($start && $end && strtotime($start) > strtotime($end)) {
            $this->db->trans_rollback(); 
            $this->session->set_flashdata(
                'error', 
                'Technician "' . $this->Project_model->get_technician_name($tid) . 
                '" has Start Date later than End Date.'
            );
            redirect('Project/add/' . $project_id);
            return; // stop saving
        }

       
        $available = $this->Project_model->is_technician_available(
            $tid,
            $start_dates[$i] ?? null,
            $end_dates[$i] ?? null,
            $project_id
        );

        if (!$available) {
            $this->db->trans_rollback(); 
            $this->session->set_flashdata('error', 'Technician "' . $this->Project_model->get_technician_name($tid) . '" is not available for the selected dates.');
            redirect('Project/add/' . $project_id); 
            return;
        }

        $techs[] = [
            'project_id'       => $project_id,
            'technician_id'    => $tid,
            'designation_id'   => $des_ids[$i] ?? null,
            'assignment_start' => $start_dates[$i] ?? null,
            'assignment_end'   => $end_dates[$i] ?? null,
        ];
    }

    $this->Project_model->save_project_technicians($project_id, $techs);
}
    $this->db->trans_complete(); 

    if ($this->db->trans_status() === FALSE) {
        $this->session->set_flashdata('error', 'Something went wrong');
        redirect('Project/add');
    }

    $this->session->set_flashdata('success', $message);
    // redirect('Project/add/' . $project_id);
    redirect('Project/get_project_list');

}

public function update_project()
{
    $this->load->model('Project_model');

    $project_id = $this->input->post('project_id');

    $projectData = [
        'fk_enq_id'        => $this->input->post('e_id'),
        'fk_quot_id'       => $this->input->post('quotation_id'),
        'project_name'     => $this->input->post('project_name'),
        'project_location' => $this->input->post('project_location'),
        'customer_name'    => $this->input->post('customer_name'),
        'branch_name'      => $this->input->post('branch_name'),
        'start_date'       => $this->input->post('start_date'),
        'end_date'         => $this->input->post('end_date'),
        'duration'         => $this->input->post('duration'),
        'subtotal'         => $this->input->post('subtotal'),
        'vat_percentage'   => $this->input->post('vat_percentage'),
        'vat_amount'       => $this->input->post('vat_amount'),
        'grand_total'      => $this->input->post('grand_total'),
        'remarks'          => $this->input->post('remarks'),
        'approver_id'      => $this->input->post('approver_id'),
        'po_number'        => $this->input->post('po_number'),
        'loa_received'     => $this->input->post('loa_received'),
        'loa_date'         => $this->input->post('loa_date'),
        'subject'          => $this->input->post('subject'),
        'approver_id'      => $this->input->post('approver_id'),
    ];

    $this->db->trans_start();

    $this->Project_model->update_project($project_id, $projectData);
    $products   = $this->input->post('product_id');
    $quantities = $this->input->post('quantity');
    $prices     = $this->input->post('unit_price');

    $items = [];
    if (!empty($products)) {
        foreach ($products as $i => $pid) {
            if (!$pid) continue;

            $items[] = [
                'project_id' => $project_id,
                'product_id' => $pid,
                'quantity'   => $quantities[$i] ?? 0,
                'unit_price' => $prices[$i] ?? 0,
                'total'      => ($quantities[$i] ?? 0) * ($prices[$i] ?? 0)
            ];
        }
    }

    $this->Project_model->save_project_items($project_id, $items);

    // UPDATE TECHNICIANS WITH AVAILABILITY CHECK
    $tech_ids    = $this->input->post('technician_id');
    $des_ids     = $this->input->post('designation_id');
    $start_dates = $this->input->post('assignment_start');
    $end_dates   = $this->input->post('assignment_end');

    $techs = [];
    if (!empty($tech_ids)) {
        foreach ($tech_ids as $i => $tid) {
            if (!$tid) continue;

            $available = $this->Project_model->is_technician_available(
                $tid,
                $start_dates[$i] ?? null,
                $end_dates[$i] ?? null,
                $project_id
            );

            if (!$available) {
                $this->db->trans_rollback();
                echo json_encode([
                    'status'  => 'error',
                    'message' => 'Technician "' . $this->Project_model->get_technician_name($tid) . '" is not available for the selected dates.'
                ]);
                return;
            }

            $techs[] = [
                'project_id'       => $project_id,
                'technician_id'    => $tid,
                'designation_id'   => $des_ids[$i] ?? null,
                'assignment_start' => $start_dates[$i] ?? null,
                'assignment_end'   => $end_dates[$i] ?? null
            ];
        }
    }

    $this->Project_model->save_project_technicians($project_id, $techs);

    $this->db->trans_complete(); // 🔐 End transaction

    if ($this->db->trans_status() === FALSE) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Something went wrong'
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'message' => 'Project updated successfully'
        ]);
    } 
}


//Project List

public function get_project_list()
{
    $data['title'] = 'Project List';
    $data['projects'] = $this->Project_model->get_all_projects();
    $data['main_content'] = 'project/project_list';
    $this->load->view('includes/template', $data);
}

public function delete($project_id)
{
    // Load the Project model
    $this->load->model('Project_model');

    // Attempt to delete the project
    if ($this->Project_model->delete_project($project_id)) {
        $this->session->set_flashdata('success', 'Project deleted successfully.');
    } else {
        $this->session->set_flashdata('error', 'Failed to delete the project.');
    }

    // Redirect back to project list
    redirect('Project/get_project_list');
}


//Technician availabilty check
public function check_technician_availability()
{
    $technician_id = $this->input->post('technician_id');
    $start_date    = $this->input->post('start_date');
    $end_date      = $this->input->post('end_date');
    $project_id    = $this->input->post('project_id'); // for edit

    if (!$technician_id || !$start_date || !$end_date) {
        echo json_encode(['status' => 'unknown']);
        return;
    }

    $this->load->model('Project_model');

    $status = $this->Project_model->get_technician_availability(
        $technician_id,
        $start_date,
        $end_date,
        $project_id
    );

    echo json_encode(['status' => $status]);
}


public function approve_project()
{
    $project_id = $this->input->post('project_id');
    $user_id    = $this->session->userdata('user_id');

    $project = $this->Project_model->get_project_by_id($project_id);

    if ($project['approver_id'] != $user_id) {
        echo json_encode(['status' => 'error', 'message' => 'You are not authorized to approve this project.']);
        return;
    }

    $this->Project_model->update_project($project_id, ['status' => 'Approved']);
    echo json_encode(['status' => 'success', 'message' => 'Project approved successfully.']);
}

public function reject_project()
{
    $project_id = $this->input->post('project_id');
    $user_id    = $this->session->userdata('user_id');

    $project = $this->Project_model->get_project_by_id($project_id);

    if ($project['approver_id'] != $user_id) {
        echo json_encode([
            'status' => 'error',
            'message' => 'You are not authorized to reject this project.'
        ]);
        return;
    }

    $this->Project_model->update_project($project_id, [
        'status' => 'Rejected'
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Project rejected successfully.'
    ]);
}

//------------------MATERIAL REQUEST START---------------------//

public function create_material_request($project_id = null)
{
    $this->load->model('Project_model');

    $data['approved_projects'] = $this->Project_model->get_approved_projects();
    $data['users'] = $this->Project_model->get_active_users();
    $data['units'] = $this->Project_model->get_all_units();
    $data['pitems'] = $this->Project_model->get_all_items();
    $data['mitems']= [];

    $data['selected_project_id'] = $project_id; 
    $data['title'] = 'Create Material Request';
    $data['user_id'] = $this->session->userdata('user_id');

    $data['main_content'] = 'material_request/add_material_request';
    $this->load->view('includes/template', $data);
}




public function save_material_request()
{
    $this->load->model('Project_model');

    $this->db->trans_start(); 

    $mrData = [
        'project_id'    => $this->input->post('project_id'),
        'project_code'  => $this->input->post('project_code'),
        'customer_name' => $this->input->post('customer_name'),  
        'branch_name'   => $this->input->post('branch_name'),    
        'requested_date'=> $this->input->post('requested_date'),
        'required_date' => $this->input->post('required_date'),  
        'initiated_by'  => $this->input->post('initiated_by') ?? $this->session->userdata('user_id'),
        'status'        => 'Pending'
    ];


    $mr_id = $this->Project_model->insert_mr($mrData);
    $mr_code = 'MR-' . str_pad($mr_id, 6, '0', STR_PAD_LEFT);
    $this->Project_model->update_mr($mr_id, ['mr_code' => $mr_code]);

    $products = $this->input->post('product');
    $desc     = $this->input->post('desc');
    $qtys     = $this->input->post('pdt_qty');
    $item_remark    = $this->input->post('item_remark');

    $items = [];
    foreach ($products as $i => $pid) {
        if (!$pid) continue; 
        $items[] = [
            'mr_id'      => $mr_id,
            'fk_item_id' => $pid,
            'item_desc'  => $desc[$i] ?? null,
            'item_qty'   => $qtys[$i] ?? null,
            'item_remarks'       => $item_remark[$i] ?? null
        ];
    }

    if (!empty($items)) {
        $this->Project_model->save_items($items);
    }

    $this->db->trans_complete(); 

    $this->session->set_flashdata('success', 'Material Request created successfully');
    redirect('Project/list_material_request');
}

// public function save_material_request()
// {
//     $this->load->model('Project_model');

//     $this->db->trans_start(); // Begin transaction

//     // Prepare MR master data
//     $mrData = [
//         'project_id'    => $this->input->post('project_id'),
//         'project_code'  => $this->input->post('project_code'),
//         'customer_name' => $this->input->post('customer_name'),  // optional
//         'branch_name'   => $this->input->post('branch_name'),    // optional
//         'requested_date'=> $this->input->post('requested_date'),
//         'required_date' => $this->input->post('required_date'),  // NEW
//         'initiated_by'  => $this->input->post('initiated_by') ?? $this->session->userdata('user_id'),
//         'status'        => 'Pending'
//     ];

//     // Insert MR master
//     $mr_id = $this->Project_model->insert_mr($mrData);

//     // Auto-generate MR Code
//     $mr_code = 'MR-' . str_pad($mr_id, 6, '0', STR_PAD_LEFT);
//     $this->Project_model->update_mr($mr_id, ['mr_code' => $mr_code]);

//     // Save MR Items
//     $products = $this->input->post('product_id');
//     $qtys     = $this->input->post('quantity');
//     $units    = $this->input->post('unit_id');

//     $items = [];
//     foreach ($products as $i => $pid) {
//         if (!$pid) continue; // skip invalid
//         $items[] = [
//             'mr_id'      => $mr_id,
//             'product_id' => $pid,
//             'quantity'   => $qtys[$i],
//             'unit'       => $units[$i] ?? null
//         ];
//     }

//     if (!empty($items)) {
//         $this->Project_model->save_items($items);
//     }

//     // 🔹 Reserve stock for MR items
//     foreach ($items as $item) {
//         $required_qty = $item['quantity'];
//         $stocks = $this->db
//             ->where('product_id', $item['product_id'])
//             ->where('stock_type', 'IN')
//             ->where('status', 0)
//             ->order_by('stock_date', 'ASC')
//             ->get('stock_details')
//             ->result();

//         $remaining = $required_qty;

//         foreach ($stocks as $stock) {
//             if ($remaining <= 0) break;

//             $available = $stock->quantity - $stock->reserved_qty - $stock->alloc_qty;
//             if ($available <= 0) continue;

//             $reserve_qty = min($available, $remaining);

//             // Update stock
//             $this->db->where('stock_id', $stock->stock_id)
//                 ->update('stock_details', [
//                     'reserved_qty'    => $stock->reserved_qty + $reserve_qty,
//                     'pending_qty'     => max(0, $required_qty - $reserve_qty),
//                     // 'stock_type'      => 'RESERVED',
//                     'trans_id'        => $mr_id,
//                     'allocation_for'  => 'MR'
//                 ]);

//             $remaining -= $reserve_qty;
//         }
//     }

//     $this->db->trans_complete(); // Commit transaction

//     $this->session->set_flashdata('success', 'Material Request created successfully');
//     redirect('Project/list_material_request');
// }

public function get_project_details_ajax()
{
    $project_id = $this->input->post('project_id');
    $this->load->model('Project_model');

    $project = $this->Project_model->get_project_by_id($project_id);
    $items   = $this->Project_model->get_project_items_list_mr($project_id);

    echo json_encode([
        'project' => $project,
        'items'   => $items
    ]);
}

public function list_material_request()
{
    $data['title'] = 'Material Request';
    $this->load->model('Project_model');

    $data['material_requests'] = $this->Project_model->get_all_mrs(); 
    $data['main_content'] = 'material_request/list_material_request';
    $this->load->view('includes/template', $data);
}

public function edit_material_request($mr_id)
{
    $data['title'] = 'Edit Material Request'; 
    $this->load->model('Project_model');

    // Get MR master data
    $data['mr'] = $this->Project_model->get_mr_by_id($mr_id);

    if (!$data['mr']) {
        $this->session->set_flashdata('error', 'Material Request not found');
        redirect('Project/list_material_request');
    }

    // Get MR items
    $data['mitems'] = $this->Project_model->get_mr_items($mr_id);
    $data['pitems'] = $this->Project_model->get_all_items();

    // Get projects and users for dropdowns
    $data['approved_projects'] = $this->Project_model->get_approved_projects();
    $data['users'] = $this->Project_model->get_active_users();
    $data['units'] = $this->Project_model->get_all_units();

    $data['main_content'] = 'material_request/edit_material_request';
    $this->load->view('includes/template', $data);
}

public function update_material_request()
{
    $this->load->model('Project_model');

    $mr_id = $this->input->post('mr_id');

    $mrData = [
        'project_id'    => $this->input->post('project_id'),
        'project_code'  => $this->input->post('project_code'),
        'customer_name' => $this->input->post('customer_name'),
        'branch_name'   => $this->input->post('branch_name'),
        'requested_date'=> $this->input->post('requested_date'),
        'required_date' => $this->input->post('required_date'),
        'initiated_by'  => $this->input->post('initiated_by'),
    ];

    $this->db->trans_start();

    $this->Project_model->update_mr($mr_id, $mrData);


    //$this->Project_model->delete_mr_items($mr_id);
    $this->Project_model->delete_mr_items($mr_id);

    $products = $this->input->post('product');
    $desc     = $this->input->post('desc');
    $qtys     = $this->input->post('pdt_qty');
    $item_remark    = $this->input->post('item_remark');

    $items = [];
    foreach ($products as $i => $pid) {
        if (!$pid) continue; 
        $items[] = [
            'mr_id'      => $mr_id,
            'fk_item_id' => $pid,
            'item_desc'  => $desc[$i] ?? null,
            'item_qty'   => $qtys[$i] ?? null,
            'item_remarks'       => $item_remark[$i] ?? null
        ];
    }

    if (!empty($items)) {
        $this->Project_model->save_items($items);
    }

    $this->db->trans_complete();

    $this->session->set_flashdata('success', 'Material Request updated successfully');
    redirect('Project/list_material_request');
}

public function delete_material_request($mr_id)
{
    $this->load->model('Project_model');

    $mr = $this->Project_model->get_mr_by_id($mr_id);
    if (!$mr) {
        $this->session->set_flashdata('error', 'Material Request not found');
        redirect('Project/list_material_request');
    }

    $this->Project_model->delete_mr($mr_id);

    $this->session->set_flashdata('success', 'Material Request deleted successfully');
    redirect('Project/list_material_request');
}


//--------------Project progress START------------------


public function project_progress_list()
{
    $data['title'] = 'Project Progress';
    $this->load->model('Project_model');

    $data['projects'] = $this->Project_model->get_projects_with_progress();
    $data['main_content'] = 'project_progress/project_progress_list';
    $this->load->view('includes/template', $data);
}

public function project_progress($project_id)
{
    $this->load->model('Project_model');

    $data['project'] = $this->Project_model->get_project_by_id($project_id);
    $data['progress'] = $this->Project_model->get_project_progress($project_id);
    $data['logs'] = $this->Project_model->get_project_progress_logs($project_id);
     // ✅ Get last progress log
    $data['last_log'] = $this->Project_model->get_last_progress_log($project_id);

    $data['title'] = 'Project Progress';
    $data['main_content'] = 'project_progress/project_progress_manage';
    $this->load->view('includes/template', $data);
}


public function save_project_progress()
{
    $this->load->model('Project_model');

    $upload_path = FCPATH . 'public/stamp/';
    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0775, true);
        chmod($upload_path, 0775);
    }

    $files_array = [];
    if (!empty($_FILES['site_files']['name'][0])) {
        $files_count = count($_FILES['site_files']['name']);
        for ($i = 0; $i < $files_count; $i++) {
            $_FILES['file']['name']     = $_FILES['site_files']['name'][$i];
            $_FILES['file']['type']     = $_FILES['site_files']['type'][$i];
            $_FILES['file']['tmp_name'] = $_FILES['site_files']['tmp_name'][$i];
            $_FILES['file']['error']    = $_FILES['site_files']['error'][$i];
            $_FILES['file']['size']     = $_FILES['site_files']['size'][$i];

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx|xls|xlsx';
            $config['encrypt_name']  = true;
            $config['max_size']      = 10240; // 10MB

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('file')) {
                $files_array[] = $this->upload->data('file_name');
            } else {
                log_message('error', 'File upload failed: ' . $this->upload->display_errors());
            }
        }
    }

    $data = [
        'project_id'          => $this->input->post('project_id'),
        'log_date'            => $this->input->post('log_date'),
        'start_time'          => $this->input->post('start_time'),
        'end_time'            => $this->input->post('end_time'),
        'milestone'           => $this->input->post('milestone'),
        'progress_percentage' => $this->input->post('progress_percentage'),
        'current_status'      => $this->input->post('current_status'),
        'remarks'             => $this->input->post('remarks'),
        'site_files'          => !empty($files_array) ? json_encode($files_array) : null
    ];

    $this->Project_model->save_progress_log($data);
    $this->Project_model->update_project_progress(
        $data['project_id'], 
        $data['progress_percentage'], 
        $data['current_status']
    );

    $this->session->set_flashdata('success', 'Progress updated successfully');
    redirect('Project/project_progress/' . $data['project_id']);
}


public function delete_progress_log()
{
    $log_id = $this->input->post('log_id');

    if(!$log_id){
        echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
        return;
    }

    // Optional: fetch log before delete (for validation)
    $log = $this->db->get_where('project_progress_logs', ['log_id' => $log_id])->row();

    if(!$log){
        echo json_encode(['status' => 'error', 'message' => 'Record not found']);
        return;
    }

    // Delete files if needed
    if(!empty($log->site_files)){
        $files = json_decode($log->site_files, true);
        foreach($files as $file){
            $path = FCPATH . 'public/stamp/' . $file;
            if(file_exists($path)){
                unlink($path);
            }
        }
    }

    // Delete record
    $this->db->where('log_id', $log_id);
    $this->db->delete('project_progress_logs');

    echo json_encode(['status' => 'success']);
}

    public function getQuotationByEnquiry()
    {
        $enquiry_id = $this->input->post('enquiry_id');
        $result = $this->Project_model->getQuotationByEnquiry($enquiry_id);
        echo json_encode($result);
    }

    public function getProjectDetailsByEnquiry()
    {
        $enquiry_id = $this->input->post('enquiry_id');
        $result = $this->Project_model->getProjectDetailsByEnquiry($enquiry_id);
        echo json_encode($result);
    }
    public function getcustomerDetails()
    {
        $quotation_id = $this->input->post('quotation_id');
        $result = $this->Project_model->getcustomerDetailsByQuotation($quotation_id);
        $final = [];
        $final['customer'] = $result['customer_name'];
        $result_b = $this->Project_model->getBranchDetailsByQuotation($quotation_id);
        $final['branch'] = $result_b['branch_name'];

        echo json_encode($final);
    }
    
    public function fetch_quotation_details(){

        $q_id = $this->input->post('q_id');
        //$so_master = $this->Sales_model->get_sales_order_by_id($_id);
        $q_products = $this->Project_model->get_all_products_by_quotation($q_id); 

        echo json_encode([
            'q_products' => $q_products
        ]);
    }

    //project's popup
    function project_popup_materials(){
        $this->load->model('Setup_model');
        $id = $_POST['id'];
        $rows = $this->Project_model->get_projectRawmaterials($id);
       // build HTML content
       $html = '';

        foreach ($rows as $product) {

            $html .= '<style>.bg-primary1{background-color:#ed7498;}</style><div class="card mb-3 border">
                <div class="card-header bg-primary text-white">
                    <div class="row">
                        <div class="col-md-8">
                            <strong>Product :</strong> '.$product['pname'].'
                        </div>
                        <div class="col-md-4 text-end">
                            <strong>Quantity :</strong> '.$product['quantity'].'
                        </div>
                    </div>
                </div><div class="card-body p-0"><table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="10%">Code</th>
                            <th width="35%">Material</th>
                            <th width="20%">Required Qty</th>
                            <th width="15%">Cost</th>
                            <th width="20%">Unit</th>
                        </tr>
                    </thead>
                    <tbody>';

            if (!empty($product['raw'])) {

                foreach ($product['raw'] as $raw) {

                    $html .= '<tr>
                            <td>'.$raw->material_code.'</td>
                            <td>'.$raw->material_name.'</td>
                            <td class="text-end">'.(float)$raw->quantity_required.'</td>
                            <td class="text-end">'.$raw->cost.'</td>
                            <td>'.$raw->unit.'</td>
                        </tr>';

                }

            } else {

                $html .= '<tr>
                        <td colspan="5" class="text-center text-danger">
                            No Raw Materials Found
                        </td>
                    </tr>';

            }

            $html .= '</tbody>
                </table>
                </div>
            </div>';
        }

        echo json_encode([
            "title" => "Raw Materials",
            "html"  => $html
        ]);
        exit;

    }
    //Task assignment
    public function assign_task($project_id)
    {
        if (!$project_id) {
            redirect('Project/add');
        }

        $data['title'] = 'Assign Task';

        $data['project'] = $this->Project_model->get_project_by_id($project_id);
        //echo "<pre>";print_r($data['project']);
        $enq_id = $data['project']['fk_enq_id'];
        $data['enquires']   = $this->Project_model->get_enquiries();
        $data['task_categories']  =   $this->Project_model->get_tasks();
        $data['milestones']  =   $this->Project_model->get_milestones();
        $data['designation_list'] = $this->Project_model->get_designations();
        $data['quotation'] = $this->Project_model->getQuotationByEnquiry($enq_id);
        $data['employees'] = $this->Project_model->get_employees();
        $data['users'] = $this->Project_model->get_active_users();
        $data['logged_in_user_id'] = $this->session->userdata('user_id');

        if (empty($data['project'])) {
            show_404();
        }

        $data['main_content'] = 'project/assign_task';
        $this->load->view('includes/template', $data);
    }
    //act_assign_task = action page
    public function act_assign_task()
    {
        $project_id      = $this->input->post('project_id');
        $project_task_id = $this->input->post('project_task_id'); // hidden field

        $taskData = array(
            'project_id'  => $project_id,
            'remarks'     => $this->input->post('remarks'),
            'approved_by' => $this->input->post('approver')
        );

        $this->db->trans_start();

        // Update main task table
        $project_task_id = $this->Project_model->save_project_task($taskData);

        // Get task item arrays
        $categories   = $this->input->post('task_category');
        $task_names   = $this->input->post('task_name');
        $milestones   = $this->input->post('milestone');
        $designations = $this->input->post('designation_id');
        $employees    = $this->input->post('employee_id');
        $priorities   = $this->input->post('priority');
        $start_dates  = $this->input->post('start_date');
        $end_dates    = $this->input->post('end_date');
        $statuses     = $this->input->post('status');
        $descriptions = $this->input->post('task_description');

        $items = array();
        if (!empty($task_names)) {
            
            foreach ($task_names as $i => $task_name) {

                if (trim($task_name) == '')
                    continue;

                $items[] = array(
                    'project_task_id' => $project_task_id,
                    'task_category_id'=> $categories[$i] ?? NULL,
                    'task_name'       => $task_name,
                    'milestone_id'    => $milestones[$i] ?? NULL,
                    'designation_id'  => $designations[$i] ?? NULL,
                    'employee_id'     => $employees[$i] ?? NULL,
                    'priority'        => $priorities[$i] ?? 'Medium',
                    'start_date'      => $start_dates[$i] ?? NULL,
                    'end_date'        => $end_dates[$i] ?? NULL,
                    'status'          => $statuses[$i] ?? 'not_started',
                    'task_description'=> $descriptions[$i] ?? ''
                );
            }
        }

        // Delete old items and insert new ones
        $this->Project_model->insert_project_task_items($items);

        $this->db->trans_complete();
       
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Something went wrong, please try again');
            redirect('Project/assign_task');
        } else {
            $this->session->set_flashdata('success', 'Task added successfully');
            redirect('Project/get_project_list');
        }
    }    

    //task update
    public function update_projecttask($project_id) {
   
        if (!$project_id) {
            redirect('Project/add');
        }
        $data['title'] = 'Update Task';
        $data['project'] = $this->Project_model->get_project_by_id($project_id);
        $data['task'] = $this->Project_model->get_project_task($project_id);
        $data['task_items'] = $this->Project_model->get_project_task_items($data['task']['id']);
        $data['enquires']   = $this->Project_model->get_enquiries();
        $data['task_categories']  =   $this->Project_model->get_tasks();
        $data['milestones']  =   $this->Project_model->get_milestones();
        $data['designation_list'] = $this->Project_model->get_designations();
        //$data['quotation'] = $this->Project_model->getQuotationByEnquiry($enq_id);
        $data['employees'] = $this->Project_model->get_employees();
        $data['users'] = $this->Project_model->get_active_users();
        $data['logged_in_user_id'] = $this->session->userdata('user_id');
        

        if (empty($data['project'])) {
            show_404();
        }

        $data['main_content'] = 'project/edit_task';
        $this->load->view('includes/template', $data);
    }
    public function getEmployeesByDesignation()
    {

        $designation_id = $this->input->post('designation_id');

        $employees = $this->db
            ->select('employee_id,employee_name')
            ->where('designation_id',$designation_id)
            //->where('bit_active',1)
            ->order_by('employee_name','ASC')
            ->get('employee_master')
            ->result_array();

        echo json_encode($employees);

    }

    //update task
    
    public function update_task()
    {
        
        $project_id = $this->input->post('project_id');
        $projectData = [
            'fk_enq_id'        => $this->input->post('e_id'),
            'fk_quot_id'       => $this->input->post('quotation_id'),
            'project_name'     => $this->input->post('project_name'),
            'project_location' => $this->input->post('project_location'),
            'customer_name'    => $this->input->post('customer_name'),
            'branch_name'      => $this->input->post('branch_name'),
            'start_date'       => $this->input->post('start_date'),
            'end_date'         => $this->input->post('end_date'),
            'duration'         => $this->input->post('duration'),
            'subtotal'         => $this->input->post('subtotal'),
            'vat_percentage'   => $this->input->post('vat_percentage'),
            'vat_amount'       => $this->input->post('vat_amount'),
            'grand_total'      => $this->input->post('grand_total'),
            'remarks'          => $this->input->post('remarks'),
            'approver_id'      => $this->input->post('approver_id'),
            'po_number'        => $this->input->post('po_number'),
            'loa_received'     => $this->input->post('loa_received'),
            'loa_date'         => $this->input->post('loa_date'),
            'subject'          => $this->input->post('subject'),
            'approver_id'      => $this->input->post('approver'),
        ];

        $this->db->trans_start();

        $this->Project_model->update_project($project_id, $projectData);
        $products   = $this->input->post('product_id');
        $quantities = $this->input->post('quantity');
        $prices     = $this->input->post('unit_price');

        $items = [];
        if (!empty($products)) {
            foreach ($products as $i => $pid) {
                if (!$pid) continue;

                $items[] = [
                    'project_id' => $project_id,
                    'product_id' => $pid,
                    'quantity'   => $quantities[$i] ?? 0,
                    'unit_price' => $prices[$i] ?? 0,
                    'total'      => ($quantities[$i] ?? 0) * ($prices[$i] ?? 0)
                ];
            }
        }

        $this->Project_model->save_project_items($project_id, $items);

        // UPDATE TECHNICIANS WITH AVAILABILITY CHECK
        $tech_ids    = $this->input->post('technician_id');
        $des_ids     = $this->input->post('designation_id');
        $start_dates = $this->input->post('assignment_start');
        $end_dates   = $this->input->post('assignment_end');

        $techs = [];
        if (!empty($tech_ids)) {
            foreach ($tech_ids as $i => $tid) {
                if (!$tid) continue;

                $available = $this->Project_model->is_technician_available(
                    $tid,
                    $start_dates[$i] ?? null,
                    $end_dates[$i] ?? null,
                    $project_id
                );

                if (!$available) {
                    $this->db->trans_rollback();
                    echo json_encode([
                        'status'  => 'error',
                        'message' => 'Technician "' . $this->Project_model->get_technician_name($tid) . '" is not available for the selected dates.'
                    ]);
                    return;
                }

                $techs[] = [
                    'project_id'       => $project_id,
                    'technician_id'    => $tid,
                    'designation_id'   => $des_ids[$i] ?? null,
                    'assignment_start' => $start_dates[$i] ?? null,
                    'assignment_end'   => $end_dates[$i] ?? null
                ];
            }
        }

        $this->Project_model->save_project_technicians($project_id, $techs);

        $this->db->trans_complete(); // 🔐 End transaction

        if ($this->db->trans_status() === FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Something went wrong'
            ]);
        } else {
            echo json_encode([
                'status' => 'success',
                'message' => 'Project updated successfully'
            ]);
        } 
    }
    
   
    /***
     * Project task Modules
     */
    public function edit_task($project_id)
    {
        // Project details
        $data['project'] = $this->Project_model->get_project_by_id($project_id);

        // Main task record
        $data['project_task'] = $this->Project_model->get_project_task($project_id);

        if (!empty($data['project_task'])) {

            // Task items
            $data['task_items'] = $this->Project_model->get_project_task_items($data['project_task']['id']);

        } else {

            $data['task_items'] = array();
        }

        // Dropdown data
        //$data['task_categories'] = $this->Project_model->get_task_categories();
        $data['milestones']       = $this->Project_model->get_milestones();
        $data['designation_list'] = $this->Project_model->get_designations();
        $data['employees']        = $this->Project_model->get_employees();
       // $data['users']            = $this->Project_model->get_users();

        $this->load->view('project/edit_task', $data);
         $data['main_content'] = 'project/edit_task';
        $this->load->view('includes/template', $data);
    }

     //update task
    public function act_update_task()
    {
        $project_id      = $this->input->post('project_id');
        $project_task_id = $this->input->post('project_task_id'); // hidden field

        $taskData = array(
            'project_id'  => $project_id,
            'remarks'     => $this->input->post('remarks'),
            'approved_by' => $this->input->post('approver')
        );

        $this->db->trans_start();

        // Update main task table
        $this->Project_model->update_project_task($project_task_id, $taskData);

        // Get task item arrays
        $categories   = $this->input->post('task_category');
        $task_names   = $this->input->post('task_name');
        $milestones   = $this->input->post('milestone');
        $designations = $this->input->post('designation_id');
        $employees    = $this->input->post('employee_id');
        $priorities   = $this->input->post('priority');
        $start_dates  = $this->input->post('start_date');
        $end_dates    = $this->input->post('end_date');
        $statuses     = $this->input->post('status');
        $descriptions = $this->input->post('task_description');

        $items = array();

        if (!empty($task_names)) {

            foreach ($task_names as $i => $task_name) {

                if (trim($task_name) == '')
                    continue;

                $items[] = array(
                    'project_task_id' => $project_task_id,
                    'task_category_id'=> $categories[$i] ?? NULL,
                    'task_name'       => $task_name,
                    'milestone_id'    => $milestones[$i] ?? NULL,
                    'designation_id'  => $designations[$i] ?? NULL,
                    'employee_id'     => $employees[$i] ?? NULL,
                    'priority'        => $priorities[$i] ?? 'Medium',
                    'start_date'      => $start_dates[$i] ?? NULL,
                    'end_date'        => $end_dates[$i] ?? NULL,
                    'status'          => $statuses[$i] ?? 'not_started',
                    'task_description'=> $descriptions[$i] ?? ''
                );
            }
        }

        // Delete old items and insert new ones
        $this->Project_model->save_project_task_items($project_task_id, $items);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Something went wrong');
            redirect('Project/update_projecttask/'.$project_id);

        } else {
            $this->session->set_flashdata('success', 'Task updated successfully');
            redirect('Project/get_project_list');
        }
    }
    //project task's popup
     function project_task_popup(){
        $id = $_POST['id'];
        $data = $this->Project_model->get_projects_tasks($id);
        $name_ary = $this->Project_model->get_projectName($id);       
        // build HTML content
        $html = '';
        $html .= '
        <div class="card border mb-3">
            <div class="card-header bg-primary text-white">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Remark :</strong> '.($data['remark'] ?? '-').'
                    </div>
                    <div class="col-md-6 text-end">
                        <strong>Approved By :</strong> '.(!empty($data['approved']) ? $data['approved'] : '').'
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Task</th>
                            <th>Employee</th>
                            <th>Designation</th>
                            <th>Milestone</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                        </tr>
                    </thead>
                    <tbody>';

        if (!empty($data['tasks'])) {

            $i = 1;

            foreach ($data['tasks'] as $task) {

                $html .= '
                    <tr>
                        <td>'.$i++.'</td>
                        <td>'.$task['task_name'].'</td>
                        <td>'.$task['employee_name'].'</td>
                        <td>'.$task['designation_name'].'</td>
                        <td>'.$task['milestone_name'].'</td>
                        <td>'.$task['priority'].'</td>
                        <td>'.$task['status'].'</td>
                        <td>'.$task['start_date'].'</td>
                        <td>'.$task['end_date'].'</td>
                    </tr>';
            }

        } else {

            $html .= '
                <tr>
                    <td colspan="9" class="text-center text-danger">
                        No Tasks Found
                    </td>
                </tr>';
        }

        $html .= '
                    </tbody>
                </table>
            </div>
        </div>';
        $pname = $name_ary[0]['project_name']." (".$name_ary[0]['project_code'].")";
        echo json_encode([
            'title' => 'Task Assignment - '.$pname,
            'html'  => $html
        ]);
        exit;

    }

    //list project tasks
    public function list_task($id)
    {
        $data['project_id'] = $id;
        $data['project_task'] = $this->Project_model->get_projects_tasks($id); 
        $p_detatil = $this->Project_model->get_projectName($id);
        $pname = $p_detatil[0]['project_name'];
        $data['title'] = 'List Task - '.$pname;
        $data['main_content'] = 'project/task_list';
        $this->load->view('includes/template', $data);
    }

    public function delete_task_item($pid,$task_id)
    {
        $this->Project_model->delete_taskitem($task_id);

        $this->session->set_flashdata('success', 'Task item deleted successfully');
        redirect('Project/list_task/'.$pid);
    }

    /**** 
     * 
     * Resource Planning
     * 
     ****/
    //Machines
    public function list_machines() {
   
        $data['title'] = 'Machines';
        $data['all_machines'] = $this->Project_model->get_all_machines();
        $data['main_content'] = 'Project/list_machines.php';
        $this->load->view('includes/template', $data);
    }    
    public function add_machine()
    {
        // GET LAST CODE
        $last = $this->db->select('machine_code')
                        ->from('machine_master')
                        ->order_by('machine_id', 'DESC')
                 ->limit(1)
                 ->get()
                 ->row();

        if ($last && !empty($last->map_code)) {

            $num = (int) substr($last->map_code, 4); // Skip "MOM-"
            $num++;

            $code = 'MA-' . str_pad($num, 4, '0', STR_PAD_LEFT);

        } else {

            $code = 'MA-0001';

        }

        $data['title'] = 'Machine Master';
        $data['main_content'] = 'project/machine_add.php';

        // PASS AUTO CODE TO VIEW
        $data['auto_code'] = $code;

        $this->load->view('includes/template.php', $data);
    }
	
    public function add_machine_data()
    {
        $result = $this->Project_model->add_machine_data();

        if ($result) {
            echo 'Added';
        } else {
            echo 'Not Added';
        }

        redirect('Project/list_machines');
    }
    
    public function edit_machine($id)
    {

            $data['title'] = 'Edit Machine';

            $data['area'] = $this->db->where('machine_id', $id)
                                    ->get('machine_master')
                                    ->row();

            $data['main_content'] = 'project/machine_edit.php';


        $this->load->view('includes/template', $data);
    }

    public function update_machine_data($id)
    {
        $machine_name = trim($this->input->post('machine_name'));

        // DUPLICATE CHECK (exclude current record)
        $exists = $this->db->where('LOWER(machine_name)', strtolower($machine_name))
                        ->where('machine_id !=', $id)
                        ->get('machine_master')
                        ->row();

        if (!empty($exists)) {
            $this->session->set_flashdata('error', 'Machine Name already exists!');
            redirect('Project/edit_machine/' . $id);
            return;
        }

        $data = array(
            'machine_name' => $machine_name,
            'description'     => $this->input->post('description'),
            'updated_at'      => date('Y-m-d H:i:s')
        );

        $this->db->where('machine_id', $id);
        $this->db->update('machine_master', $data);

        $this->session->set_flashdata('success', 'Machine Updated Successfully');
        redirect('Project/list_machines');
    }

    public function delete_machine($id)
    {

        $this->db->where('machine_id', $id);
        $this->db->delete('machine_master');

        $this->session->set_flashdata('success', 'Machine Deleted Successfully');

        redirect('Project/list_machines');
    }

    //tools
    public function list_tools() {
   
        $data['title'] = 'Tools';
        $data['all_tools'] = $this->Project_model->get_all_tools();
        $data['main_content'] = 'Project/list_tools.php';
        $this->load->view('includes/template', $data);
    }    
    public function add_tool()
    {
        // GET LAST CODE
       $last = $this->db->select('tool_code')
                        ->from('tool_master')
                        ->order_by('tool_id', 'DESC')
                 ->limit(1)
                 ->get()
                 ->row();

        if ($last && !empty($last->map_code)) {

            $num = (int) substr($last->map_code, 4); // Skip "MOM-"
            $num++;

            $code = 'TL-' . str_pad($num, 4, '0', STR_PAD_LEFT);

        } else {

            $code = 'TL-0001';

        }

        $data['title'] = 'Tool Master';
        $data['main_content'] = 'project/tool_add.php';

        // PASS AUTO CODE TO VIEW
        $data['auto_code'] = $code;

        $this->load->view('includes/template.php', $data);
    }
	
    public function add_tool_data()
    {
        $result = $this->Project_model->add_tool_data();

        if ($result) {
            echo 'Added';
        } else {
            echo 'Not Added';
        }

        redirect('Project/list_tools');
    }
    
    public function edit_tool($id)
    {

            $data['title'] = 'Edit tool';

            $data['area'] = $this->db->where('tool_id', $id)
                                    ->get('tool_master')
                                    ->row();

            $data['main_content'] = 'project/tool_edit.php';


        $this->load->view('includes/template', $data);
    }

    public function update_tool_data($id)
    {
        $tool_name = trim($this->input->post('tool_name'));

        // DUPLICATE CHECK (exclude current record)
        $exists = $this->db->where('LOWER(tool_name)', strtolower($tool_name))
                        ->where('tool_id !=', $id)
                        ->get('tool_master')
                        ->row();

        if (!empty($exists)) {
            $this->session->set_flashdata('error', 'Tool Name already exists!');
            redirect('Project/edit_tool/' . $id);
            return;
        }

        $data = array(
            'tool_name' => $tool_name,
            'description'     => $this->input->post('description'),
            'updated_at'      => date('Y-m-d H:i:s')
        );

        $this->db->where('tool_id', $id);
        $this->db->update('tool_master', $data);

        $this->session->set_flashdata('success', 'Tool Updated Successfully');
        redirect('Project/list_tools');
    }

    public function delete_tool($id)
    {

        $this->db->where('tool_id', $id);
        $this->db->delete('tool_master');

        $this->session->set_flashdata('success', 'Tool Deleted Successfully');

        redirect('Project/list_tools');
    }

    //machine operator mapping
    public function list_machineop_map() {
        $data['title'] = 'Machine Operator Mapping';
        $data['employees']  =   $this->Project_model->get_employees($id=1); //technicians
        $data['machines']  =   $this->Project_model->get_machines();
        $data['all_map']  =   $this->Project_model->get_all_employyee_mapping();
        $data['main_content'] = 'Project/list_machine_operator.php';
        $this->load->view('includes/template', $data);
    }    
    public function add_machineop_map()
    {
        
        $data['employees']  =   $this->Project_model->get_employees($id=1);
        $data['machines']   =   $this->Project_model->get_machines();
        $data['machine_op'] =   [];
        // GET LAST CODE
      $last = $this->db->select('map_code')
                 ->from('machine_operator_mapping')
                 ->order_by('mapping_id', 'DESC')
                 ->limit(1)
                 ->get()
                 ->row();

        if ($last && !empty($last->map_code)) {

            $num = (int) substr($last->map_code, 4); // Skip "MOM-"
            $num++;

            $code = 'MOM-' . str_pad($num, 4, '0', STR_PAD_LEFT);

        } else {

            $code = 'MOM-0001';

        }

        $data['title'] = 'Add Machine Operator Mapping';
        $data['main_content'] = 'project/machine_operator_add.php';

        // PASS AUTO CODE TO VIEW
        $data['auto_code'] = $code;
        $this->load->view('includes/template.php', $data);
    }
	
    public function add_machineop_map_data()
    {
        $result = $this->Project_model->add_mom_data();

        if ($result) {
            echo 'Added';
        } else {
            echo 'Not Added';
        }

        redirect('Project/list_machineop_map');
    }
    
    public function edit_machineop_map($id)
    {
        if (!$id) {
            redirect('Project/list_machineop_map');
        }

        $data['title'] = 'Edit Machine Operator Mapping';
        $data['machine_op'] = $this->Project_model->get_machine_operator($id);
        $data['machines'] = $this->Project_model->get_machines();
        $data['employees'] = $this->Project_model->get_employees();

        $data['main_content'] = 'project/machine_operator_edit';
        $this->load->view('includes/template', $data);
    }

    public function update_machineop_map_data()
    {
        $id = $this->input->post('mapping_id');

        if ($this->Project_model->update_mom_data($id)) {
            $this->session->set_flashdata('success','Machine Operator Mapping Updated Successfully');
        } else {
            $this->session->set_flashdata('error','Failed to Update');
        }

        redirect('Project/list_machineop_map');
    }

    public function delete_machineop_map($id)
    {

        $this->db->where('mapping_id', $id);
        $this->db->delete('machine_operator_mapping');

        $this->session->set_flashdata('success', 'Machine Operator Mapping Deleted Successfully');

        redirect('Project/list_machineop_map');
    }

    //Resource planning
    public function list_resource_planning($id)
    {
        $data['title'] = 'Resource Planning';

        $data['resources'] = $this->Project_model->get_all_resource_planning($id);
        $data['project_id'] = $id;
        $data['main_content'] = 'project/list_resource_planning';

        $this->load->view('includes/template', $data);
    } 
    // ===============================
    // ADD RESOURCE PLANNING
    // ===============================
    public function add_resource_planning($id)
    {
        $data['projects']  = $this->Project_model->get_project_by_id($id);
        $data['employees'] = $this->Project_model->get_employees();
        $data['machines']  = $this->Project_model->get_machines();
        $data['machine_operator'] = $this->Project_model->get_machine_operator_mapping();
        $data['resource']  = [];
        $data['project_id'] = $id;

        // Generate Resource Code
        $last = $this->db->select('resource_code')
                        ->from('project_machine_resource')
                        ->order_by('resource_id', 'DESC')
                        ->limit(1)
                        ->get()
                        ->row();

        if ($last && !empty($last->resource_code)) {

            $num = (int) substr($last->resource_code, 4); // Skip "MRP-"
            $num++;

            $code = 'MRP-' . str_pad($num, 4, '0', STR_PAD_LEFT);

        } else {

            $code = 'MRP-0001';

        }

        $data['auto_code'] = $code;
        $data['title'] = 'Add Resource Planning';
        $data['main_content'] = 'project/resource_planning_add';

        $this->load->view('includes/template', $data);
    }
	
   public function add_resource_planning_data()
    {
       
        $id     = $this->input->post('project_id');
        if ($this->Project_model->add_machine_resource()) {

            $this->session->set_flashdata(
                'success',
                'Resource Planning Added Successfully'
            );

        } else {

            $this->session->set_flashdata(
                'error',
                'Unable to Save Resource Planning'
            );

        }

        redirect('Project/list_resource_planning/'.$id);
    }
    
    public function edit_resource_planning($pid,$id)
    {
        if (!$id) {
            redirect('Project/list_resource_planning');
        }
        $data['project_id'] = $pid;
        $data['title'] = 'Edit Resource Planning';
        $data['resource']  = $this->Project_model->get_machine_resource($id);
        $data['projects']  = $this->Project_model->get_project_by_id($pid);
        $data['machines']  = $this->Project_model->get_machines();
        $data['employees'] = $this->Project_model->get_employees();
        $data['machine_operator'] = $this->Project_model->get_machine_operator_mapping();
        $data['main_content'] = 'project/resource_planning_edit';

        $this->load->view('includes/template', $data);
    }

    public function update_resource_planning_data()
    {
        $id     = $this->input->post('resource_id');
        $pid    = $this->input->post('project_id');
        if ($this->Project_model->update_machine_resource($id)) {

            $this->session->set_flashdata(
                'success',
                'Resource Planning Updated Successfully'
            );

        } else {

            $this->session->set_flashdata(
                'error',
                'Unable to Update Resource Planning'
            );

        }

        redirect('Project/list_resource_planning/'.$pid);
    }

    public function delete_resource_planning($pid,$id)
    {
        $this->db->where('resource_id', $id);
        $this->db->delete('project_machine_resource');

        $this->session->set_flashdata(
            'success',
            'Resource Planning Deleted Successfully'
        );

        redirect('Project/list_resource_planning/'.$pid);
    }




}
