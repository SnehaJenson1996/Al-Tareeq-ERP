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
        $this->load->model('Setup_model');
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
        $this->load->model('Setup_model');
        $data['warehouse_list'] = $this->Setup_model->get_warehouse_list();
        $data['store_list'] = [];

        $data['material_requests'] = $this->Project_model->get_pending_material_requests();
        $data['units'] = $this->Project_model->get_all_units();
        $data['selected_mr_id'] = $mr_id;
        $data['title'] = 'Create Material Issue';
        $data['main_content'] = 'inventory/add_material_issue';
        $this->load->view('includes/template', $data);
    }

    public function save_material_issue()
    {
        $warehouse_id = $this->input->post('warehouse_id');
        $store_id     = $this->input->post('store_id');

        $products            = $this->input->post('product_id');
        $units               = $this->input->post('unit_id');
        $requested_qtys      = $this->input->post('requested_qty');
        $issue_qtys          = $this->input->post('issue_qty');
        $pending_qtys        = $this->input->post('pending_qty');
        $previously_issued   = $this->input->post('previously_issued');
        $item_checks         = $this->input->post('item_check');

        if (empty($warehouse_id) || empty($store_id)) {

            $this->session->set_flashdata(
                'error',
                'Please select Warehouse and Store.'
            );

            redirect('Inventory/create_material_issue');
            return;
        }

        if (!empty($item_checks)) {
            $checked_indexes = array_keys($item_checks);

            foreach ($checked_indexes as $i) {
                $product_id = $products[$i];
                $issue_qty  = (float)$issue_qtys[$i];

                if ($issue_qty <= 0)
                    continue;

                $available = $this->db
                    ->select_sum('balance_qty')
                    ->where('warehouse_id', $warehouse_id)
                    ->where('store_id', $store_id)
                    ->where('product_id', $product_id)
                    ->where('stock_type', 'IN')
                    ->get('stock_details')
                    ->row();

                $available_qty = (float)($available->balance_qty ?? 0);

                if ($issue_qty > $available_qty) {
                    $product = $this->db
                        ->select('product_name')
                        ->where('product_id', $product_id)
                        ->get('item_master')
                        ->row();
                    $product_name = $product ? $product->product_name : 'Selected Product';
                    $this->session->set_flashdata(
                        'error',
                        $product_name .
                            ' has only ' .
                            $available_qty .
                            ' Qty available in the selected Warehouse/Store.'
                    );
                    redirect('Inventory/create_material_issue');
                    return;
                }
            }
        }

        $this->db->trans_begin();

        $miData = [
            'mr_id'         => $this->input->post('mr_id'),
            'project_id'    => $this->input->post('project_id'),
            'project_code'  => $this->input->post('project_code'),
            'customer_name' => $this->input->post('customer_name'),
            'branch_name'   => $this->input->post('branch_name'),
            'warehouse_id'  => $warehouse_id,
            'store_id'      => $store_id,
            'issued_by'     => $this->session->userdata('user_id'),
            'issue_date'    => date('Y-m-d H:i:s'),
            'status'        => 'Issued'
        ];

        $this->db->insert('material_issue', $miData);
        $mi_id = $this->db->insert_id();
        $mi_code = 'MI-' . str_pad($mi_id, 6, '0', STR_PAD_LEFT);

        $this->db
            ->where('mi_id', $mi_id)
            ->update('material_issue', [
                'mi_code' => $mi_code
            ]);

        if (!empty($item_checks)) {
            $checked_indexes = array_keys($item_checks);
            foreach ($checked_indexes as $i) {
                $itemData = [
                    'mi_id'                    => $mi_id,
                    'product_id'               => $products[$i],
                    'unit_id'                  => $units[$i],
                    'requested_qty'            => $requested_qtys[$i],
                    'issued_qty'               => $issue_qtys[$i],
                    'pending_qty'              => $pending_qtys[$i],
                    'previously_issued_qty'    => $previously_issued[$i]
                ];

                $this->db->insert(
                    'material_issue_items',
                    $itemData
                );

                $result = $this->Inventory_model->allocate_stock_for_mi(
                    $products[$i],
                    $issue_qtys[$i],
                    $mi_id,
                    $warehouse_id,
                    $store_id
                );

                if (!$result) {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata(
                        'error',
                        'Stock allocation failed.'
                    );
                    redirect('Inventory/create_material_issue');
                    return;
                }
            }
        }

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata(
                'error',
                'Failed to create Material Issue.'
            );
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata(
                'success',
                'Material Issue created successfully.'
            );
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
            $item['product_name'] = $product['product_name'] ?? '';
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
    /////////////////////// DIRECT MATERIAL ISSUE START ////////////////////////
    public function add_direct_material_issue()
    {
        $data['approved_projects'] = $this->Project_model->get_approved_projects();
        $data['branch_records']    = $this->Company_model->get_all_branches();
        $data['warehouse_list']    = $this->Setup_model->get_warehouse_list();
        $data['products']          = $this->Setup_model->get_active_item_list();
        $data['units']             = $this->Setup_model->get_active_unit_list();
        $data['store_list']        = [];

        $data['title'] = "Direct Material Issue";
        $data['main_content'] = "inventory/add_direct_material_issue";

        $this->load->view('includes/template', $data);
    }

    public function save_direct_material_issue()
    {
        $warehouse_id = $this->input->post('warehouse_id');
        $store_id     = $this->input->post('store_id');

        $products           = $this->input->post('product_id');
        $units              = $this->input->post('unit_id');
        $requested_qtys     = $this->input->post('requested_qty');
        $issue_qtys         = $this->input->post('issue_qty');
        $pending_qtys       = $this->input->post('pending_qty');
        $previously_issued  = $this->input->post('previously_issued');

        if (empty($warehouse_id) || empty($store_id)) {

            $this->session->set_flashdata(
                'error',
                'Please select Warehouse and Store.'
            );

            redirect('Inventory/add_direct_material_issue');
            return;
        }

        if (empty($products)) {

            $this->session->set_flashdata(
                'error',
                'Please add at least one product.'
            );

            redirect('Inventory/add_direct_material_issue');
            return;
        }

        foreach ($products as $i => $product_id) {
            if (empty($product_id))
                continue;

            $issue_qty = (float)$issue_qtys[$i];

            if ($issue_qty <= 0)
                continue;

            $available = $this->db
                ->select_sum('balance_qty')
                ->where('warehouse_id', $warehouse_id)
                ->where('store_id', $store_id)
                ->where('product_id', $product_id)
                ->where('stock_type', 'IN')
                ->get('stock_details')
                ->row();

            $available_qty = (float)($available->balance_qty ?? 0);

            if ($issue_qty > $available_qty) {

                $product = $this->db
                    ->select('product_name')
                    ->where('product_id', $product_id)
                    ->get('item_master')
                    ->row();

                $product_name = $product ? $product->product_name : 'Selected Product';

                $this->session->set_flashdata(
                    'error',
                    $product_name .
                        ' has only ' .
                        $available_qty .
                        ' Qty available in the selected Warehouse/Store.'
                );

                redirect('Inventory/add_direct_material_issue');
                return;
            }
        }

        $this->db->trans_begin();

        $miData = [
            'issue_type'    => 'DIRECT',
            'mr_id'         => NULL,
            'project_id'    => $this->input->post('project_id'),
            'project_code'  => $this->input->post('project_code'),
            'customer_name' => $this->input->post('customer_name'),
            'branch_name'   => $this->input->post('branch_name'),
            'warehouse_id'  => $warehouse_id,
            'store_id'      => $store_id,
            'issued_by'     => $this->session->userdata('user_id'),
            'issue_date'    => date('Y-m-d H:i:s'),
            'status'        => 'Issued'
        ];

        $this->db->insert('material_issue', $miData);

        $mi_id = $this->db->insert_id();
        $mi_code = 'MI-' . str_pad($mi_id, 6, '0', STR_PAD_LEFT);

        $this->db
            ->where('mi_id', $mi_id)
            ->update('material_issue', [
                'mi_code' => $mi_code
            ]);


        foreach ($products as $i => $product_id) {
            if (empty($product_id))
                continue;

            $issue_qty = (float)$issue_qtys[$i];

            if ($issue_qty <= 0)
                continue;

            $itemData = [
                'mi_id'                 => $mi_id,
                'product_id'            => $product_id,
                'unit_id'               => $units[$i],
                'requested_qty'         => $requested_qtys[$i],
                'issued_qty'            => $issue_qty,
                'pending_qty'           => $pending_qtys[$i],
                'previously_issued_qty' => $previously_issued[$i]

            ];

            $this->db->insert(
                'material_issue_items',
                $itemData
            );

            $result = $this->Inventory_model->allocate_stock_for_mi(
                $product_id,
                $issue_qty,
                $mi_id,
                $warehouse_id,
                $store_id
            );

            if (!$result) {
                $this->db->trans_rollback();
                $this->session->set_flashdata(
                    'error',
                    'Stock allocation failed.'
                );
                redirect('Inventory/add_direct_material_issue');
                return;
            }
        }

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            $this->session->set_flashdata(
                'error',
                'Failed to create Material Issue.'
            );
        } else {
            $this->db->trans_commit();
            $this->session->set_flashdata(
                'success',
                'Direct Material Issue created successfully.'
            );
        }

        redirect('Inventory/list_material_issue');
    }
    /////////////////////// DIRECT MATERIAL ISSUE END ////////////////////////

    /////////////////////// STOCK TRANFSER CODE START ////////////////////////

    public function list_stock_transfer()
    {
        $user = $this->session->userdata('user_id');

        if (!has_access($user, 'Inventory/list_stock_transfer', 'A')) {
            $data['title'] = 'Access Denied';
            $data['main_content'] = 'errors/access_control.php';
            $this->load->view('includes/template', $data);
            return;
        }

        $this->load->model('Inventory_model');

        $data['title'] = 'Stock Transfer';

        $data['records'] = $this->Inventory_model->get_stock_transfer_list();

        $data['main_content'] = 'inventory/list_stock_transfer';

        $this->load->view('includes/template', $data);
    }

    public function add_stock_transfer()
    {
        $user = $this->session->userdata('user_id');

        if (!has_access($user, 'Inventory/list_stock_transfer', 'A')) {
            $data['title'] = 'Access Denied';
            $data['main_content'] = 'errors/access_control.php';
            $this->load->view('includes/template', $data);
            return;
        }

        $this->load->model('Company_model');
        $this->load->model('Setup_model');

        $data['title'] = 'Stock Transfer';

        $data['branch_records'] = $this->Company_model->get_all_branches();
        $data['warehouse_list'] = $this->Setup_model->get_warehouse_list();
        $data['store_list'] = [];

        $data['products'] = $this->Setup_model->get_active_item_list();
        $data['units'] = $this->Setup_model->get_active_unit_list();
        $data['main_content'] = 'inventory/add_stock_transfer';

        $this->load->view('includes/template', $data);
    }

    public function save_stock_transfer()
    {
        $this->db->trans_begin();

        // We'll implement saving logic after designing the UI.

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'Failed to save Stock Transfer.'
            );
        } else {
            $this->db->trans_commit();

            $this->session->set_flashdata(
                'success',
                'Stock Transfer created successfully.'
            );
        }

        redirect('Inventory/list_stock_transfer');
    }
    /////////////////////// STOCK TRANFSER CODE END  ////////////////////////

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
        if (!$current) {
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

    // public function delete_material_issue($mi_id = null)
    // {
    //     if (!$mi_id) {
    //         $this->session->set_flashdata('error', 'Invalid Material Issue ID.');
    //         redirect('Inventory/list_material_issue');
    //     }

    //     $this->db->trans_start();

    //     // Optionally: revert allocated stock before deleting items
    //     $items = $this->db->get_where('material_issue_items', ['mi_id' => $mi_id])->result();
    //     foreach ($items as $item) {
    //         // Revert stock (if you track stock allocations)
    //         // $this->Inventory_model->revert_stock($item->product_id, $item->issued_qty, $this->db->get_where('material_issue', ['mi_id'=>$mi_id])->row()->mr_id);
    //     }

    //     // Delete child items
    //     $this->db->where('mi_id', $mi_id)->delete('material_issue_items');

    //     // Delete master MI record
    //     $this->db->where('mi_id', $mi_id)->delete('material_issue');

    //     $this->db->trans_complete();

    //     if ($this->db->trans_status() === FALSE) {
    //         $this->session->set_flashdata('error', 'Failed to delete Material Issue.');
    //     } else {
    //         $this->session->set_flashdata('success', 'Material Issue deleted successfully.');
    //     }

    //     redirect('Inventory/list_material_issue');
    // }

    public function delete_material_issue($mi_id = null)
    {
        if (!$mi_id) {
            $this->session->set_flashdata('error', 'Invalid Material Issue ID.');
            redirect('Inventory/list_material_issue');
        }

        $result = $this->Inventory_model->delete_material_issue($mi_id);

        if ($result) {
            $this->session->set_flashdata(
                'success',
                'Material Issue deleted successfully.'
            );
        } else {
            $this->session->set_flashdata(
                'error',
                'Unable to delete Material Issue.'
            );
        }

        redirect('Inventory/list_material_issue');
    }

    public function stock_ledger()
    {
        $data['title'] = 'Stock Ledger';

        $this->load->model('Inventory_model');

        $data['records'] = $this->Inventory_model->get_stock_ledger();

        $data['main_content'] = 'inventory/stock_ledger';

        $this->load->view('includes/template', $data);
    }
}
