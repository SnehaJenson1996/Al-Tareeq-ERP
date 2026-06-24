<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory extends CI_Controller
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
        $this->load->model('Inventory_model');
    }



    public function list_material_request()
    {
        $data['material_requests'] = $this->Project_model->get_all_material_requests();
        $data['title'] = 'Material Requests';
        $data['main_content'] = 'inventory/list_material_request';
        $this->load->view('includes/template', $data);
    }

    public function create_material_issue($mr_id = null)
    {
        $data['material_requests'] = $this->Project_model->get_pending_material_requests();
        $data['units'] = $this->Project_model->get_all_units();
        $data['selected_mr_id'] = $mr_id;
        $data['title'] = 'Create Material Issue';
        $data['main_content'] = 'inventory/add_material_issue';
        $this->load->view('includes/template', $data);
    }


// public function get_mr_details_ajax()
// {
//     $mr_id = $this->input->post('mr_id');
//     $mr = $this->Project_model->get_mr_by_id($mr_id);
//     $items = $this->Project_model->get_mr_items($mr_id);

//     $result_items = [];

//     foreach ($items as $item) {
//         $product_id = $item['product_id'];

//         // TOTAL IN only 
//         $available_qty = (float) ($this->db
//             ->select_sum('quantity')
//             ->where('product_id', $product_id)
//             ->where('stock_type', 'IN')
//             ->get('stock_details')
//             ->row()->quantity ?? 0);

//         // Reserved quantity for this MR
//         $reserved_qty = (float) ($this->db
//             ->select_sum('reserved_quantity')
//             ->where('product_id', $product_id)
//             ->where('allocation_id', $mr_id)
//             ->where('stock_type', 'RESERVE')
//             ->get('stock_details')
//             ->row()->reserved_quantity ?? 0);

//         // Pending = Requested - Reserved
//         $pending_qty = max(0, $item['quantity'] - $reserved_qty);

//         $result_items[] = [
//             'product_id'    => $product_id,
//             'product_name'  => $item['product_name'],
//             'item_unit'     => $item['unit'],
//             'requested_qty' => (float) $item['quantity'],
//             'available_qty' => $available_qty,   
//             'reserved_qty'  => $reserved_qty,
//             'issue_qty'     => $reserved_qty,
//             'pending_qty'   => $pending_qty,
//         ];
//     }

//     echo json_encode([
//         'mr'    => $mr,
//         'items' => $result_items
//     ]);
// }

public function get_mr_details_ajax()
{
    $mr_id = $this->input->post('mr_id');
    $mr = $this->Project_model->get_mr_by_id($mr_id);
    $items = $this->Project_model->get_mr_items($mr_id);

    $result_items = [];

    foreach ($items as $item) {
        $product_id = $item['product_id'];

        // ✅ TOTAL IN only (ignore OUT and RESERVED)
        $available_qty = (float) ($this->db
            ->select_sum('quantity')
            ->where('product_id', $product_id)
            ->where('stock_type', 'IN')
            ->get('stock_details')
            ->row()->quantity ?? 0);

        // Reserved quantity for this MR
        $reserved_qty = (float) ($this->db
            ->select_sum('reserved_quantity')
            ->where('product_id', $product_id)
            ->where('allocation_id', $mr_id)
            ->where('stock_type', 'RESERVE')
            ->get('stock_details')
            ->row()->reserved_quantity ?? 0);

        // Pending = Requested - Reserved
        $pending_qty = max(0, $item['quantity'] - $reserved_qty);

        // Total issued for this product
      $total_issued = $this->Project_model->get_total_issued_qty($mr_id, $product_id);

      $result_items[] = [
    'product_id'       => $product_id,
    'product_name'     => $item['product_name'],
    'item_unit'        => $item['unit'],
    'requested_qty'    => (float) $item['quantity'],
    'available_qty'    => $available_qty,
    'reserved_qty'     => $reserved_qty,
    'issue_qty'        => $reserved_qty,
    'pending_qty'      => max(0, $item['quantity'] - $reserved_qty),
    'issued_qty_total' => $total_issued,
];
    }

    echo json_encode([
        'mr'    => $mr,
        'items' => $result_items
    ]);
}


public function save_material_issue()
{
    $this->db->trans_start();

    $miData = [
        'mr_id' => $this->input->post('mr_id'),
        'project_id' => $this->input->post('project_id'),
        'project_code' => $this->input->post('project_code'),
        'customer_name' => $this->input->post('customer_name'),
        'branch_name' => $this->input->post('branch_name'),
        'issued_by' => $this->session->userdata('user_id'),
        'issue_date' => date('Y-m-d H:i:s'),
        'status' => 'Issued'
    ];

    // Insert MI master
    $this->db->insert('material_issue', $miData);
    $mi_id = $this->db->insert_id();

    // Generate MI code
    $mi_code = 'MI-' . str_pad($mi_id, 6, '0', STR_PAD_LEFT);
    $this->db->where('mi_id', $mi_id)->update('material_issue', ['mi_code' => $mi_code]);

    // Get all arrays
    $products = $this->input->post('product_id');
    $units = $this->input->post('unit_id');
    $requested_qtys = $this->input->post('requested_qty');
    $issue_qtys = $this->input->post('issue_qty');
    $pending_qtys = $this->input->post('pending_qty');
    $previously_issued = $this->input->post('previously_issued');
    $item_checks = $this->input->post('item_check');

if (!empty($item_checks)) {
        // Get checked row indexes
        $checked_indexes = array_keys($item_checks);

        foreach ($checked_indexes as $i) {
            $itemData = [
                'mi_id' => $mi_id,
                'product_id' => $products[$i] ?? null,
                'unit_id' => $units[$i] ?? null,
                'requested_qty' => $requested_qtys[$i] ?? 0,
                'issued_qty' => $issue_qtys[$i] ?? 0,
                'pending_qty' => $pending_qtys[$i] ?? 0,
                'previously_issued_qty' => $previously_issued[$i] ?? 0,
            ];

            // Skip if product_id or issued_qty is null
            if (!$itemData['product_id'] || $itemData['issued_qty'] === null) continue;

            $this->db->insert('material_issue_items', $itemData);

            // Allocate stock
            $this->Inventory_model->allocate_stock_for_mi($products[$i], $issue_qtys[$i], $this->input->post('mr_id'));
        }
    }

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
        $this->session->set_flashdata('error', 'Failed to create Material Issue');
    } else {
        $this->session->set_flashdata('success', 'Material Issue created successfully');
    }

    redirect('Inventory/list_material_issue');
}

//view MI
public function view_material_issue($mi_id)
{
$mi = $this->Inventory_model->get_material_issue_by_id($mi_id);

if (!$mi) {
    $this->session->set_flashdata('error', 'Material Issue not found');
    redirect('Inventory/list_material_issue');
}

// Load MI items
$items = $this->Inventory_model->get_material_issue_items($mi_id);

foreach ($items as &$item) {
    $product = $this->Inventory_model->get_item_details($item['product_id']);
    $item['product_name'] = $product['item_name'] ?? '';
    $item['available_qty'] = $product['total_stock'] ?? 0;
}

$data['mi'] = $mi;
$data['items'] = $items;
$data['units'] = $this->Project_model->get_all_units();
$data['title'] = 'View Material Issue';


    $data['main_content'] = 'inventory/view_material_issue';

    $this->load->view('includes/template', $data);
}

//     public function save_material_issue()
// {
//     $this->db->trans_start();

//     $miData = [
//         'mr_id' => $this->input->post('mr_id'),
//         'project_id' => $this->input->post('project_id'),
//         'project_code' => $this->input->post('project_code'),
//         'customer_name' => $this->input->post('customer_name'),
//         'branch_name' => $this->input->post('branch_name'),
//         'issued_by' => $this->session->userdata('user_id'),
//         'issue_date' => date('Y-m-d H:i:s'),
//         'status' => 'Issued'
//     ];

//     // Insert MI master
//     $this->db->insert('material_issue', $miData);
//     $mi_id = $this->db->insert_id();

//     // Generate MI code
//     $mi_code = 'MI-' . str_pad($mi_id, 6, '0', STR_PAD_LEFT);
//     $this->db->where('mi_id', $mi_id)->update('material_issue', ['mi_code' => $mi_code]);

//     // Insert MI items
//     $products = $this->input->post('product_id');
//     $units = $this->input->post('unit_id');
//     $requested_qtys = $this->input->post('requested_qty');
//     $issue_qtys = $this->input->post('issue_qty');
//     $pending_qtys = $this->input->post('pending_qty');
//     $item_checks = $this->input->post('item_check');

//     foreach ($products as $i => $pid) {
//          if (!isset($item_checks[$i])) {
//         continue;
//     }
//         $itemData = [
//             'mi_id' => $mi_id,
//             'product_id' => $pid,
//             'unit_id' => $units[$i],
//             'requested_qty' => $requested_qtys[$i],
//             'issued_qty' => $issue_qtys[$i],
//             'pending_qty' => $pending_qtys[$i],
//         ];

//         $this->db->insert('material_issue_items', $itemData);

//         // Optional: update stock allocation here
//         $this->Inventory_model->allocate_stock_for_mi($pid, $issue_qtys[$i], $this->input->post('mr_id'));
//     }

//     $this->db->trans_complete();

//     if ($this->db->trans_status() === FALSE) {
//         $this->session->set_flashdata('error', 'Failed to create Material Issue');
//     } else {
//         $this->session->set_flashdata('success', 'Material Issue created successfully');
//     }

//     redirect('Inventory/list_material_issue');
// }


    public function list_material_issue()
    {
        $data['material_issues'] = $this->Inventory_model->get_all_material_issues();
        $data['title'] = 'Material Issues';
        $data['main_content'] = 'inventory/list_material_issue';
        $this->load->view('includes/template', $data);
    }

    

    public function itemwise_stock_summary()
    {
        $data['stock'] = $this->Inventory_model->get_itemwise_stock_summary();
        $data['title'] = 'Item wise stock summary';
        $data['main_content'] = 'inventory/stock_details';
        $this->load->view('includes/template', $data);
    }
    public function item_reservation_detail($item_id)
    {
        $data['title'] = 'Item wise stock detailed';
        $data['item'] = $this->Inventory_model->get_item_details($item_id);
        $data['reservations'] = $this->Inventory_model->get_item_reservation_list($item_id);
        $data['main_content'] = 'inventory/item_reservation_detail';
        $this->load->view('includes/template', $data);
    }
    public function update_stock_priority()
    {
        $stock_id     = (int) $this->input->post('stock_id');
        $newPriority  = (int) $this->input->post('priority');

        // Get current stock row
        $current = $this->db->get_where('stock_details', [
            'stock_id' => $stock_id,
            'status'   => 1
        ])->row_array();
// print_r($current);exit();
        if (!$current ) {
            echo json_encode(['status' => 'error', 'msg' => 'Invalid stock']);
            return;
        }

        $product_id    = $current['product_id'];
        $old_priority  = $current['reserve_priority'];

        $this->db->trans_start();

        // Check if another reservation already has this priority for the SAME product
        $existing = $this->db
            ->where([
                'product_id' => $product_id,
                'reserve_priority' => $newPriority,
                'status' => 1
            ])
            ->where('stock_id !=', $stock_id)
            ->get('stock_details')
            ->row_array();

        // 🔄 Swap priorities if exists
        if ($existing) {
            $this->db->where('stock_id', $existing['stock_id'])
                ->update('stock_details', [
                    'reserve_priority' => $old_priority
                ]);
        }

        // Update requested stock row
        $this->db->where('stock_id', $stock_id)
            ->update('stock_details', [
                'reserve_priority' => $newPriority
            ]);

        $this->db->trans_complete();

        echo json_encode(['status' => 'success']);
    }


    public function release_partial_old()
    {
        $stock_id    = $this->input->post('stock_id');
        $release_qty = (int) $this->input->post('release_qty');

        $row = $this->db->get_where('stock_details', [
            'stock_id' => $stock_id
        ])->row_array();

        if (!$row || $release_qty > $row['reserved_quantity']) {
            echo json_encode(['status' => 'error']);
            return;
        }

        $new_reserved = $row['reserved_quantity'] - $release_qty;
        $new_pending  = $row['pending_quantity'] + $release_qty;

        $update = [
            'reserved_quantity' => $new_reserved,
            'pending_quantity'  => $new_pending
        ];

        // If fully released
        if ($new_reserved == 0) {
            $update['reserve_priority'] = NULL;
            $update['allocation'] = 'NO';
        }

        $this->db->where('stock_id', $stock_id)
            ->update('stock_details', $update);

        echo json_encode(['status' => 'success']);
    }
    public function release_partial()
    {
        $stock_id    = (int) $this->input->post('stock_id');
        $release_qty = (int) $this->input->post('release_qty');

        $row = $this->db->get_where('stock_details', [
            'stock_id' => $stock_id,
            'status'   => 1
        ])->row_array();

        if (!$row || $release_qty <= 0 || $release_qty > $row['reserved_quantity']) {
            echo json_encode(['status' => 'error', 'msg' => 'Invalid quantity']);
            return;
        }

        // 🔄 MOVE reserved → pending
        $new_reserved = $row['reserved_quantity'] - $release_qty;
        $new_pending  = $row['pending_quantity'] + $release_qty;

        $update = [
            'reserved_quantity' => $new_reserved,
            'pending_quantity'  => $new_pending
        ];

        // 🧹 FULL RELEASE CLEANUP
        if ($new_reserved == 0) {
            $update['reserve_priority'] = NULL;
            $update['allocation']      = NULL;
            $update['allocation_id']   = NULL;
        }

        $this->db->where('stock_id', $stock_id)->update('stock_details', $update);

        echo json_encode(['status' => 'success']);
    }

    private function resequence_priority($product_id)
    {
        $rows = $this->db->select('stock_id')
            ->from('stock_details')
            ->where([
                'product_id' => $product_id,
                'status'     => 1
            ])
            ->where('reserved_quantity >', 0)
            ->order_by('reserve_priority', 'ASC')
            ->get()->result_array();

        $p = 1;
        foreach ($rows as $r) {
            $this->db->where('stock_id', $r['stock_id'])
                ->update('stock_details', ['reserve_priority' => $p++]);
        }
    }

    public function delete_material_issue($mi_id = null)
{
    if (!$mi_id) {
        $this->session->set_flashdata('error', 'Invalid Material Issue ID.');
        redirect('Inventory/list_material_issue');
    }

    $this->db->trans_start();

    // Optionally: revert allocated stock before deleting items
    $items = $this->db->get_where('material_issue_items', ['mi_id' => $mi_id])->result();
    foreach ($items as $item) {
        // Revert stock (if you track stock allocations)
        // $this->Inventory_model->revert_stock($item->product_id, $item->issued_qty, $this->db->get_where('material_issue', ['mi_id'=>$mi_id])->row()->mr_id);
    }

    // Delete child items
    $this->db->where('mi_id', $mi_id)->delete('material_issue_items');

    // Delete master MI record
    $this->db->where('mi_id', $mi_id)->delete('material_issue');

    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
        $this->session->set_flashdata('error', 'Failed to delete Material Issue.');
    } else {
        $this->session->set_flashdata('success', 'Material Issue deleted successfully.');
    }

    redirect('Inventory/list_material_issue');
}
}
