<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inventory_model extends CI_Model
{

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

    public function allocate_stock_for_mi($product_id, $issue_qty, $mr_id)
    {
        if ($issue_qty <= 0) return;

        // Fetch only IN stock for the product
        $stocks = $this->db
            ->where('product_id', $product_id)
            ->where('stock_type', 'IN')
            // ->where('status', 1)
            ->order_by('stock_id', 'ASC')
            ->get('stock_details')
            ->result();

        $remaining = $issue_qty;

        foreach ($stocks as $stock) {
            if ($remaining <= 0) break;

            $available = $stock->quantity;
            if ($available <= 0) continue;

            $deduct = min($available, $remaining);

            $this->db->where('stock_id', $stock->stock_id)->update('stock_details', [
                'stock_type'   => 'OUT',
                'quantity'     => $deduct,
                'item_remark'  => 'MI',
                'allocation_id' => $mr_id,
                'created_date' => date('Y-m-d H:i:s')
            ]);

            $remaining -= $deduct;
        }

        if ($remaining > 0) {
            log_message('error', "Not enough IN stock to issue product_id: $product_id, remaining: $remaining");
        }
    }

    public function get_all_material_issues()
    {

        $this->db->select('mi.*, mr.mr_code, p.project_name');
        $this->db->from('material_issue mi');
        $this->db->join('material_requests mr', 'mr.mr_id = mi.mr_id', 'left');
        $this->db->join('project_master p', 'p.project_id = mi.project_id', 'left');
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
            ->join('stock_details sd','sd.product_id=im.product_id','left')
            ->where('im.is_inactive',0)
            ->where('im.is_marked_delete',0)
            ->group_by('im.product_id')
            ->order_by('im.product_name','ASC')
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
            bm.brand_name,
            bm.discount_limit,
            um.unit_name,
            um.unit_abbr
        ')
            ->from('item_master im')
            // ->join('brand_master bm', 'bm.brand_id = im.item_brand', 'left')
            ->join('unit_master um', 'um.unit_id = im.item_unit', 'left')
            ->where('im.product_id', $item_id)
            ->where('im.active', 1)
            ->get()
            ->row_array();
    }


    //   MI VIEW
    public function get_material_issue_by_id($mi_id)
    {
        $this->db->select('mi.*, mr.mr_code, p.project_name, p.project_code, mr.customer_name, mr.branch_name');
        $this->db->from('material_issue mi');
        $this->db->join('material_requests mr', 'mr.mr_id = mi.mr_id', 'left');
        $this->db->join('project_master p', 'p.project_id = mi.project_id', 'left');
        $this->db->where('mi.mi_id', $mi_id);
        return $this->db->get()->row_array();
    }

    public function get_material_issue_items($mi_id)
    {
        return $this->db->get_where('material_issue_items', ['mi_id' => $mi_id])->result_array();
    }
}
