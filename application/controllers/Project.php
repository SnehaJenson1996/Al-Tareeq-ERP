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

        $data['sales_orders'] = $this->Sales_model->get_all_sales_orders();

         // Check if SO ID is passed in URL
    $so_id = $this->input->get('so_id');
    $data['selected_so_id'] = $so_id ?? null;

    
        $data['employees'] = $this->Project_model->get_employees();
        $data['designations'] = $this->Project_model->get_designations();
        $data['users'] = $this->db->get('users')->result_array();

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
        'so_id'            => $this->input->post('so_id'),
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
        'so_id'            => $this->input->post('so_id'),
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
        'approver_id'      => $this->input->post('approver_id')
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

    $products = $this->input->post('product_id');
    $qtys     = $this->input->post('quantity');
     $units    = $this->input->post('unit_id');

    $items = [];
    foreach ($products as $i => $pid) {
        if (!$pid) continue; 
        $items[] = [
            'mr_id'      => $mr_id,
            'product_id' => $pid,
            'quantity'   => $qtys[$i],
            'unit'       => $units[$i] ?? null
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
    $data['mr_items'] = $this->Project_model->get_mr_items($mr_id);

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


    $this->Project_model->delete_mr_items($mr_id);

    $products = $this->input->post('product_id');
    $qtys     = $this->input->post('quantity');
    $units    = $this->input->post('unit_id');

    $items = [];
    foreach ($products as $i => $pid) {
        if (!$pid) continue;
        $items[] = [
            'mr_id'      => $mr_id,
            'product_id' => $pid,
            'quantity'   => $qtys[$i],
            'unit'       => $units[$i] ?? null
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



}
