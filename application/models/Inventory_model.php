<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_model extends CI_Model
{
    ///////////////////// Inventory Dashboard Code Start ///////////////////////////
    public function get_product_count()
    {
        return $this->db->count_all('item_master');
    }

    public function get_material_issue_count()
    {
        return $this->db->count_all('material_issue');
    }

    public function get_stock_ledger_count()
    {
        return $this->db->count_all('stock_details');
    }

    public function get_minimum_stock_count()
    {
        $this->db->where('reorder_level >', 0);
        return $this->db->count_all_results('item_master');
    }

    public function get_total_stock_in()
    {
        $this->db->select('COALESCE(SUM(quantity),0) total');
        $this->db->where('stock_type', 'IN');

        $row = $this->db->get('stock_details')->row();

        return $row->total;
    }

    public function get_total_stock_out()
    {
        $this->db->select('COALESCE(SUM(quantity),0) total');
        $this->db->where('stock_type', 'OUT');

        $row = $this->db->get('stock_details')->row();

        return $row->total;
    }

    public function get_reserved_stock_total()
    {
        $this->db->select('COALESCE(SUM(allocation),0) total');

        $row = $this->db->get('stock_details')->row();

        return $row->total;
    }

    public function get_available_stock()
    {
        $sql = "
            SELECT
            SUM(stock)
            total_stock
            FROM
                (
                    SELECT
                    product_id,
                    SUM(CASE WHEN stock_type='IN' THEN quantity ELSE -quantity END)
                    stock
                    FROM stock_details
                    GROUP BY product_id
                ) x

            ";

        $row = $this->db->query($sql)->row();

        return $row->total_stock;
    }

    public function today_material_issue()
    {
        $today = date('Y-m-d');

        $this->db->where('DATE(issue_date)', $today);

        return $this->db->count_all_results('material_issue');
    }

    public function today_stock_in()
    {
        $today = date('Y-m-d');

        $this->db->where('stock_type', 'IN');
        $this->db->where('DATE(created_date)', $today);

        return $this->db->count_all_results('stock_details');
    }

    public function today_stock_out()
    {
        $today = date('Y-m-d');

        $this->db->where('stock_type', 'OUT');
        $this->db->where('DATE(created_date)', $today);

        return $this->db->count_all_results('stock_details');
    }

    public function today_stock_adjustment()
    {
        $today = date('Y-m-d');

        $this->db->where('DATE(created_date)', $today);

        return $this->db->count_all_results('stock_adjustment');
    }

    public function recent_material_issue()
    {
        $this->db->select('
            mi.mi_code,
            mi.issue_date,
            mi.customer_name,
            w.warehouse_name,
            mi.status
        ');

        $this->db->from('material_issue mi');

        $this->db->join(
            'warehouse_master w',
            'w.warehouse_id = mi.warehouse_id',
            'left'
        );

        $this->db->order_by('mi.mi_id', 'DESC');

        $this->db->limit(10);

        return $this->db->get()->result();
    }

    public function recent_stock_ledger()
    {
        $this->db->select('
            s.created_date,
            i.product_code,
            i.product_name,
            s.stock_type,
            s.quantity,
            w.warehouse_name
        ');

        $this->db->from('stock_details s');

        $this->db->join(
            'item_master i',
            'i.product_id=s.product_id',
            'left'
        );

        $this->db->join(
            'warehouse_master w',
            'w.warehouse_id=s.warehouse_id',
            'left'
        );

        $this->db->order_by('s.stock_id', 'DESC');

        $this->db->limit(10);

        return $this->db->get()->result();
    }

    public function low_stock_items()
    {
        $sql = "
            SELECT
                i.product_code,
                i.product_name,
                i.reorder_level,
                IFNULL(stock.stock_qty,0) stock_qty
            FROM item_master i
            LEFT JOIN
            (
                SELECT
                    product_id,
                    SUM(CASE
                        WHEN stock_type='IN'
                        THEN quantity
                        ELSE -quantity
                    END)
                    stock_qty
                FROM stock_details
                GROUP BY product_id
            ) stock
            ON stock.product_id=i.product_id
            HAVING stock_qty<=reorder_level
            ORDER BY stock_qty ASC
            LIMIT 10
            ";

        return $this->db->query($sql)->result();
    }

    public function warehouse_summary()
    {
        $sql = "
            SELECT
                w.warehouse_name,
                COUNT(DISTINCT s.product_id) total_items,
                SUM(CASE
                        WHEN s.stock_type='IN'
                        THEN s.quantity
                        ELSE -s.quantity
                    END) available_stock,
                SUM(s.allocation) reserved_stock
            FROM warehouse_master w
            LEFT JOIN stock_details s
                ON s.warehouse_id=w.warehouse_id
            GROUP BY w.warehouse_id
            ORDER BY w.warehouse_name
            ";

        return $this->db->query($sql)->result();
    }
    ///////////////////// Inventory Dashboard Code End ///////////////////////////

    public function get_reserved_stock($product_id, $mr_id)
    {
        $row = $this->db->select('SUM(reserved_qty) as reserved_qty, SUM(pending_qty) as pending_qty')
            ->where('product_id', $product_id)
            ->where('allocation_for', 'MR')
            ->where('allocation_id', $mr_id)
            ->get('stock_details')
            ->row_array();

        return $row;
    }

    public function insert_mi($data)
    {
        $this->db->insert('material_issues', $data);
        return $this->db->insert_id();
    }


    public function update_mi($mi_id, $data)
    {
        $this->db->where('mi_id', $mi_id)->update('material_issues', $data);
    }


    public function save_mi_items($items)
    {
        $this->db->insert_batch('material_issue_items', $items);
    }

    // public function allocate_stock_for_mi($product_id, $issue_qty, $mr_id)
    // {
    //     if ($issue_qty <= 0) return;

    //     $stocks = $this->db->where('product_id', $product_id)
    //         ->where('allocation_id', $mr_id)
    //         ->where('stock_type', 'RESERVE')
    //         ->where('status', 1)
    //         ->order_by('reserve_priority', 'ASC')
    //         ->get('stock_details')
    //         ->result();

    //     $remaining = $issue_qty;

    //     foreach ($stocks as $stock) {
    //         if ($remaining <= 0) break;

    //         $available = $stock->reserved_quantity;
    //         if ($available <= 0) continue;

    //         $deduct = min($available, $remaining);

    //         $this->db->where('stock_id', $stock->stock_id)->update('stock_details', [
    //             'reserved_quantity' => $stock->reserved_quantity - $deduct,
    //             'pending_quantity'  => $stock->pending_quantity,
    //             'stock_type'        => ($stock->reserved_quantity - $deduct <= 0)
    //                                     ? 'IN'
    //                                     : 'RESERVE'
    //         ]);

    //         $remaining -= $deduct;
    //     }

    //     if ($remaining > 0) {
    //         $this->db->insert('stock_details', [
    //             'product_id'        => $product_id,
    //             'stock_type'        => 'RESERVE',
    //             'pending_quantity'  => $remaining,
    //             'allocation_id'     => $mr_id,
    //             'created_date'      => date('Y-m-d H:i:s'),
    //             'status'            => 1
    //         ]);
    //     }
    // }

    public function allocate_stock_for_mi($product_id, $issue_qty, $mi_id, $warehouse_id, $store_id)
    {
        if ($issue_qty <= 0) {
            return;
        }

        $user_id = $this->session->userdata('user_id');

        // Fetch available IN stock (FIFO)
        $stocks = $this->db
            ->where('warehouse_id', $warehouse_id)
            ->where('store_id', $store_id)
            ->where('product_id', $product_id)
            ->where('stock_type', 'IN')
            ->where('balance_qty >', 0)
            ->order_by('stock_id', 'ASC')
            ->get('stock_details')
            ->result();

        $remaining = $issue_qty;

        foreach ($stocks as $stock) {

            if ($remaining <= 0) {
                break;
            }

            $available = (float)$stock->balance_qty;

            if ($available <= 0) {
                continue;
            }

            $deduct = min($available, $remaining);

            // Reduce available balance from original IN stock
            $this->db->where('stock_id', $stock->stock_id)
                ->update('stock_details', [
                    'balance_qty' => $available - $deduct
                ]);

            // Create NEW OUT transaction
            $this->db->insert('stock_details', [
                'parent_stock_id' => $stock->stock_id,
                'grn_id'          => $stock->grn_id,
                'warehouse_id'    => $stock->warehouse_id,
                'store_id'        => $stock->store_id,
                'stock_type'      => 'OUT',
                'trans_id'        => $mi_id,
                'stock_date'      => date('Y-m-d'),
                'year'            => date('Y'),
                'product_id'      => $stock->product_id,
                'unit_id'         => $stock->unit_id,
                'quantity'        => $deduct,
                'balance_qty'     => 0,
                'price'           => $stock->price,
                'stock_value'     => $deduct * $stock->price,
                'remark'          => 'Material Issue',
                'item_remark'     => 'MI',
                'created_by'      => $user_id,
                'created_date'    => date('Y-m-d H:i:s'),
                'status'          => 1,
                'allocation_for'  => 'MI',
                'allocation_id'   => $mi_id
            ]);

            $remaining -= $deduct;
        }

        if ($remaining > 0) {

            log_message(
                'error',
                'Material Issue failed. Product ID: ' .
                    $product_id .
                    ', Required: ' .
                    $issue_qty .
                    ', Available: ' .
                    ($issue_qty - $remaining)
            );

            return false;
        }

        return true;
    }

    public function get_all_material_issues()
    {

        $this->db->select('mi.*, mr.mr_code, p.project_name, w.warehouse_name, s.store_name');
        $this->db->from('material_issue mi');
        $this->db->join('material_requests mr', 'mr.mr_id = mi.mr_id', 'left');
        $this->db->join('project_master p', 'p.project_id = mi.project_id', 'left');
        $this->db->join('warehouse_master w', 'w.warehouse_id = mi.warehouse_id', 'left');
        $this->db->join('store_master s', 's.store_id = mi.store_id', 'left');
        $this->db->order_by('mi.mi_id', 'DESC');
        $issues = $this->db->get()->result_array();


        foreach ($issues as &$issue) {
            $issue['items'] = $this->db
                ->select('mii.*, im.product_name')
                ->from('material_issue_items mii')
                ->join('item_master im', 'im.product_id = mii.product_id', 'left')
                ->where('mii.mi_id', $issue['mi_id'])
                ->get()
                ->result_array();
        }

        return $issues;
    }



    public function get_itemwise_stock_summary()
    {
        return $this->db
            ->select("
                im.product_id,
                im.product_code,
                im.product_name,

                SUM(
                    CASE
                        WHEN sd.stock_type='IN' AND sd.status=1
                        THEN sd.quantity
                        ELSE 0
                    END
                ) AS total_stock,

                SUM(
                    CASE
                        WHEN sd.stock_type='RESERVE' AND sd.status=1
                        THEN sd.reserved_quantity
                        ELSE 0
                    END
                ) AS total_reserved,

                SUM(
                    CASE
                        WHEN sd.stock_type='RESERVE' AND sd.status=1
                        THEN sd.pending_quantity
                        ELSE 0
                    END
                ) AS total_pending
            ")
            ->from('item_master im')
            ->join('stock_details sd', 'sd.product_id=im.product_id', 'left')
            ->where('im.is_inactive', 0)
            ->where('im.is_marked_delete', 0)
            ->group_by('im.product_id')
            ->order_by('im.product_name', 'ASC')
            ->get()
            ->result_array();
    }

    public function get_item_reservation_list($item_id)
    {
        $this->db->select("
            sd.stock_id,
            sd.reserved_quantity,
            sd.pending_quantity,
            sd.reserve_priority,

            som.so_id,
            som.so_code,
            som.reserved_date,
            som.stock_status,

            cm.customer_name,
            cm.customer_code,

            bm.branch_name
        ")
            ->from('stock_details sd')
            ->join('sales_order_master som', 'som.so_id = sd.allocation_id', 'LEFT')
            // Join quotation_master to get quotation_customer if direct quotation
            ->join('quotation_master qm', 'qm.qtn_id = som.qtn_id', 'LEFT')
            // Join enquiry_master to get enquiry_customer if enquiry-based SO
            ->join('enquiry_master em', 'em.enquiry_id = som.enquiry_id', 'LEFT')
            // Join customer_master dynamically: either quotation_customer or enquiry_customer
            ->join('customer_master cm', "
            cm.customer_id = COALESCE(qm.quotation_customer, em.enquiry_customer)
        ", 'LEFT')
            ->join('branch_master bm', 'bm.branch_id = cm.branch_id', 'LEFT')
            ->where('sd.product_id', $item_id)
            ->where('sd.stock_type', 'RESERVE')
            ->where('sd.status', 1)
            ->order_by('sd.reserve_priority', 'ASC')
            ->order_by('som.reserved_date', 'ASC');

        return $this->db->get()->result_array();
    }


    public function get_item_details($item_id)
    {
        return $this->db
            ->select('
                im.*,
                um.unit_name,
                um.unit_abbr
            ')
            ->from('item_master im')
            ->join('unit_master um', 'um.unit_id = im.unit_id', 'left')
            ->where('im.product_id', $item_id)
            ->where('im.is_inactive', 0)
            ->get()
            ->row_array();
    }


    //   MI VIEW
    public function get_material_issue_by_id($mi_id)
    {
        $this->db->select('mi.*, mr.mr_code, p.project_name, p.project_code, mr.customer_name, mr.branch_name, w.warehouse_name, s.store_name');
        $this->db->from('material_issue mi');
        $this->db->join('material_requests mr', 'mr.mr_id = mi.mr_id', 'left');
        $this->db->join('project_master p', 'p.project_id = mi.project_id', 'left');
        $this->db->join('warehouse_master w', 'w.warehouse_id = mi.warehouse_id', 'left');
        $this->db->join('store_master s', 's.store_id = mi.store_id', 'left');
        $this->db->where('mi.mi_id', $mi_id);
        return $this->db->get()->row_array();
    }

    public function get_material_issue_items($mi_id)
    {
        return $this->db->get_where('material_issue_items', ['mi_id' => $mi_id])->result_array();
    }

    public function delete_material_issue($mi_id)
    {
        $this->db->trans_begin();

        $stocks = $this->db
            ->where('trans_id', $mi_id)
            ->where('stock_type', 'OUT')
            ->get('stock_details')
            ->result();

        foreach ($stocks as $out) {
            if ($out->parent_stock_id != NULL) {
                $this->db
                    ->set('balance_qty', 'balance_qty + ' . $out->quantity, FALSE)
                    ->where('stock_id', $out->parent_stock_id)
                    ->update('stock_details');
            }

            $this->db->where('stock_id', $out->stock_id)->delete('stock_details');
        }

        $this->db->where('mi_id', $mi_id)->delete('material_issue_items');
        $this->db->where('mi_id', $mi_id)->delete('material_issue');

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

    ///// Stock Ledger /////
    public function get_stock_ledger()
    {
        $this->db->select("
            sd.*,
            im.product_code,
            im.product_name,
            wm.warehouse_name,
            sm.store_name,
            um.unit_name,
            u.user_name
        ");

        $this->db->from('stock_details sd');

        $this->db->join(
            'item_master im',
            'im.product_id=sd.product_id',
            'left'
        );

        $this->db->join(
            'warehouse_master wm',
            'wm.warehouse_id=sd.warehouse_id',
            'left'
        );

        $this->db->join(
            'store_master sm',
            'sm.store_id=sd.store_id',
            'left'
        );

        $this->db->join(
            'unit_master um',
            'um.unit_id=sd.unit_id',
            'left'
        );

        $this->db->join(
            'users u',
            'u.user_id=sd.created_by',
            'left'
        );

        $this->db->order_by('sd.created_date', 'DESC');

        return $this->db->get()->result();
    }

    ///// Stock Transfer /////
    public function get_stock_transfer_list()
    {
        $this->db->select("
            stm.*,

            fw.warehouse_name AS from_warehouse,
            tw.warehouse_name AS to_warehouse,

            fs.store_name AS from_store,
            ts.store_name AS to_store,

            fb.branch_name AS from_branch,
            tb.branch_name AS to_branch,

            u.user_name
        ");

        $this->db->from('stock_transfer_master stm');

        // From Warehouse
        $this->db->join(
            'warehouse_master fw',
            'fw.warehouse_id = stm.from_warehouse_id',
            'left'
        );

        // To Warehouse
        $this->db->join(
            'warehouse_master tw',
            'tw.warehouse_id = stm.to_warehouse_id',
            'left'
        );

        // From Store
        $this->db->join(
            'store_master fs',
            'fs.store_id = stm.from_store_id',
            'left'
        );

        // To Store
        $this->db->join(
            'store_master ts',
            'ts.store_id = stm.to_store_id',
            'left'
        );

        // From Branch
        $this->db->join(
            'branch_master fb',
            'fb.branch_id = stm.from_branch_id',
            'left'
        );

        // To Branch
        $this->db->join(
            'branch_master tb',
            'tb.branch_id = stm.to_branch_id',
            'left'
        );

        // User
        $this->db->join(
            'users u',
            'u.user_id = stm.created_by',
            'left'
        );

        $this->db->order_by('stm.transfer_id', 'DESC');

        return $this->db->get()->result();
    }
}
