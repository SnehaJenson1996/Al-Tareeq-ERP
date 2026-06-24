<?php
class Sales_order_model extends CI_Model
{

    public function __construct() {}

    public function insert_sales_order_master($master_data)
    {
        $this->db->insert('sales_order_master', $master_data);
        return $this->db->insert_id();
    }
    public function insert_sales_order_products_batch($products_batch)
    {
        return $this->db->insert_batch('sales_order_products', $products_batch);
    }
    public function insert_sales_order_address($address_data)
    {
        return $this->db->insert('sales_order_addres', $address_data);
    }
    public function get_sales_order_list_data()
    {
        $this->db->select('so.so_id,so.so_code,so.so_date');
        $this->db->from('sales_order_master so');
        // $this->db->where('so.enquiry_id', $enquiry_id);
        // $this->db->where('so.qtn_id', $quotation_id);
        $this->db->where('so.active', 1);
        $query = $this->db->get();

        return $query->result_array(); // return single row
    }
    public function get_sales_order_master($so_id)
    {
        return $this->db
            ->select('
            so.*,
            q.qtn_id as quotation_id, 
            q.quotation_code, 
            q.quotation_date, 
            c.customer_name, 
            c.customer_TR_no, 
            c.contact_number, 
            c.emirate, 
            c.customer_address, 
            c.customer_email, 
            enq.project_name, 
            b.branch_name, 
            em.employee_name AS prepared_by
        ')
            ->from('sales_order_master so')
            ->join('quotation_master q', 'q.qtn_id = so.qtn_id', 'left')
            ->join('enquiry_master enq', 'enq.enquiry_id = q.enquiry_id', 'left')
            ->join('customer_master c', 'c.customer_id = enq.enquiry_customer', 'left')
            ->join('branch_master b', 'b.branch_id = enq.branch_id', 'left')
            ->join('users u', 'u.user_id = so.created_by', 'left')
            ->join('employee_master em', 'em.employee_id = u.employee_id', 'left')
            ->where('so.so_id', $so_id)
            ->get()
            ->row_array(); // ✅ single associative array
    }

    public function get_so_address($so_id)
    {
        return $this->db->where('so_id', $so_id)
            ->get('sales_order_addres')
            ->row_array();
    }

    public function get_so_products($so_id)
    {
        return $this->db
            ->select('sp.*, im.item_name as product_name, um.unit_name') // select product fields + item/unit names
            ->where('sp.so_id', $so_id)
            ->order_by('sp.product_table_id', 'asc')
            ->join("item_master im", "im.item_id = sp.product_id", "left")
            ->join("unit_master um", "um.unit_id = sp.unit_id", "left")
            ->get('sales_order_products sp')
            ->result_array();
    }
    public function get_available_quantities($enquiry_id, $qtn_id, $current_so_id = null)
    {
        // 1) Get quotation products with product details
        $qtn_products = $this->db
            ->select('
            qp.prd_id,
            qp.qty AS qtn_qty,
            qp.prd_description,
            qp.unit_price,
             qp.unit_id,
            (qp.qty * qp.unit_price) AS qtn_total,
            qp.amount,
            qp.dicount_amount,
            qp.taxable_amount,
            p.item_name,
            q.quotation_code,
            u.unit_name
        ')
            ->from('quotation_products qp')
            ->join('item_master p', 'p.item_id = qp.prd_id', 'left')
            ->join('quotation_master q', 'q.qtn_id = qp.qtn_id', 'left')
            ->join('unit_master u', 'qp.unit_id = u.unit_id', 'left')
            ->where('qp.qtn_id', $qtn_id)
            ->get()
            ->result_array();

        // 2) Get SO ids for this enquiry + quotation
        $so_rows = $this->db
            ->select('so_id, so_code')
            ->from('sales_order_master')
            ->where('enquiry_id', $enquiry_id)
            ->where('qtn_id', $qtn_id)
            ->get()
            ->result_array();

        if (empty($so_rows)) {
            // no SOs yet — available = quotation qty
            foreach ($qtn_products as &$p) {
                $p['so_qty'] = 0;
                $p['available_qty'] = (float)$p['qtn_qty'];
                $p['so_codes'] = ''; // no SO references
            }
            return $qtn_products;
        }

        $so_ids   = array_column($so_rows, 'so_id');
        $so_codes = array_column($so_rows, 'so_code');

        // 3) Sum quantities across existing SO products
        $so_sums = $this->db
            ->select('product_id, SUM(quantity) AS so_qty')
            ->from('sales_order_products')
            ->where_in('so_id', $so_ids)
            ->group_by('product_id')
            ->get()
            ->result_array();

        // 4) If editing, subtract current SO's quantities
        $current_so_map = [];
        if ($current_so_id) {
            $current_so_products = $this->db
                ->select('product_id, quantity')
                ->from('sales_order_products')
                ->where('so_id', $current_so_id)
                ->get()
                ->result_array();
            foreach ($current_so_products as $r) {
                $current_so_map[$r['product_id']] = (float)$r['quantity'];
            }
        }


        // Map product_id => so_qty
        // $so_map = [];
        // foreach ($so_sums as $r) {
        //     $so_map[$r['product_id']] = (float)$r['so_qty'];
        // }
        $so_map = [];
        foreach ($so_sums as $r) {
            $so_qty = (float)$r['so_qty']; // total in all SOs

            if ($current_so_id && isset($current_so_map[$r['product_id']])) {
                // subtract the quantity from the current SO
                $so_qty -= $current_so_map[$r['product_id']];
            }

            $so_map[$r['product_id']] = max(0, $so_qty);
        }


        // 5) Compute available + add extras
        foreach ($qtn_products as &$p) {
            $prod_id = $p['prd_id'];
            $so_qty  = isset($so_map[$prod_id]) ? $so_map[$prod_id] : 0;
            $p['so_qty'] = $so_qty;
            $avail = (float)$p['qtn_qty'] - $so_qty;
            $p['available_qty'] = $avail > 0 ? $avail : 0;

            // extra fields
            $p['available_total'] = $p['available_qty'] * $p['unit_price'];
            $p['so_codes'] = implode(', ', $so_codes);
            $p['so_discount_amount'] = $p['dicount_amount'];
            // $p['so_taxable_amount'] = $p['taxable_amount'];
        }

        return $qtn_products;
    }
    public function get_edit_so_quantities($so_id, $enquiry_id, $qtn_id)
    {
        // 1) Get all products from quotation with item + unit details
        $this->db->select('
        qi.prd_id,
        qi.unit_price,
        qi.qty AS quotation_qty,
        qi.unit_id,
        im.item_name,
        um.unit_name
    ');
        $this->db->from('quotation_products qi');
        $this->db->join('item_master im', 'im.item_id = qi.prd_id', 'left');
        $this->db->join('unit_master um', 'um.unit_id = qi.unit_id', 'left');
        $this->db->where('qi.qtn_id', $qtn_id);
        $quotation_items = $this->db->get()->result_array();

        $result = [];
        $all_zero = true; // <-- flag to track if all available_qty = 0

        foreach ($quotation_items as $item) {
            $item_id = $item['prd_id'];
            $quotation_qty = (float)$item['quotation_qty'];

            // 2) Get total qty from other SOs (exclude current one)
            $this->db->select_sum('soi.quantity', 'used_qty');
            $this->db->from('sales_order_products soi');
            $this->db->join('sales_order_master som', 'som.so_id = soi.so_id');
            $this->db->where('som.enquiry_id', $enquiry_id);
            $this->db->where('som.qtn_id', $qtn_id);
            $this->db->where('soi.product_id', $item_id);
            $this->db->where('soi.so_id !=', $so_id);
            $used_qty = (float)($this->db->get()->row()->used_qty ?? 0);

            // 3) Current SO values
            $this->db->select('soi.quantity AS so_qty, soi.amount AS so_amount, soi.discount_amount');
            $this->db->from('sales_order_products soi');
            $this->db->where('soi.so_id', $so_id);
            $this->db->where('soi.product_id', $item_id);
            $current = $this->db->get()->row_array();

            $so_qty      = isset($current['so_qty']) ? (float)$current['so_qty'] : 0;
            $so_amount   = isset($current['so_amount']) ? (float)$current['so_amount'] : 0;
            $so_discount = isset($current['discount_amount']) ? (float)$current['discount_amount'] : 0;

            // 4) Compute available + max
            $available_qty = $quotation_qty - $used_qty;
            $max_qty = $available_qty + $so_qty;

            if ($max_qty > $quotation_qty) {
                $max_qty = $quotation_qty;
            }

            // check flag
            if ($available_qty > 0) {
                $all_zero = false; // at least one item available
            }

            $result[] = [
                'item_id'            => $item_id,
                'item_name'          => $item['item_name'],
                'unit_price'         => $item['unit_price'],
                'unit_id'            => $item['unit_id'],
                'unit_name'          => $item['unit_name'],
                'quotation_qty'      => $quotation_qty,
                'used_qty'           => $used_qty,
                'available_qty'      => $available_qty,
                'max_qty'            => $max_qty,
                'so_qty'             => $so_qty,
                'so_amount'          => $so_amount,
                'so_discount_amount' => $so_discount,
            ];
        }

        // Return both product data and flag
        return [
            'products' => $result,
            'can_create_dc' => !$all_zero // true if at least one available
        ];
    }

    // In Sales_order_model.php
    public function update_sales_order_master($so_id, $data)
    {
        $this->db->where('so_id', $so_id);
        return $this->db->update('sales_order_master', $data);
    }

    public function delete_sales_order_products($so_id)
    {
        $this->db->where('so_id', $so_id);
        return $this->db->delete('sales_order_products');
    }

    public function update_sales_order_address($so_id, $data)
    {
        $this->db->where('so_id', $so_id);
        return $this->db->update('sales_order_addres', $data);
    }
    public function get_last_delivery_code()
    {
        $this->db->select('delivery_code');
        $this->db->order_by('del_id', 'DESC'); // assuming primary key 'id'
        $this->db->limit(1);
        $query = $this->db->get('delivery_master');

        if ($query->num_rows() > 0) {
            return $query->row()->delivery_code;
        }
        return null;
    }
    public function get_delivery_products($del_master_id)
    {
        $this->db->select('dp.product_table_id, dp.del_master_id, dp.product_id, dp.quantity, dp.created_on,
                           p.item_name as product_name');
        $this->db->from('delivery_products dp');
        $this->db->join('item_master p', 'dp.product_id = p.item_id', 'left');
        //$this->db->join('unit_master um', 'um.unit_id = qi.unit_id', 'left');
        $this->db->where('dp.del_master_id', $del_master_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function get_delivery_master($delivery_id)
    {
        $this->db->select('
        dm.del_id,
        dm.delivery_code,
        dm.so_id,
        s.enquiry_id,
        s.qtn_id as quotation_id,
        dm.delivery_mode,
        dm.deliverd_by,
        dm.item_issued_by,
        dm.delivery_date,
        dm.shipping_address,
        dm.shipping_city,
        dm.contact,
        dm.email,
        dm.remark,
        u.user_name as issued_by
    ');
        $this->db->from('delivery_master dm');
        $this->db->join('sales_order_master s', 's.so_id = dm.so_id', 'left');
        $this->db->join('users u', 'u.user_id = dm.item_issued_by', 'left');
        $this->db->where('dm.del_id', $delivery_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row_array(); // single record
        }
        return [];
    }
    public function get_delivery_challan_list_data($enquiry_id = null, $quotation_id = null)
    {
        $this->db->select('*');
        $this->db->from('delivery_master');

        if ($quotation_id !== null) {
            $this->db->where('quotation_id', $quotation_id);
        }

        if ($enquiry_id !== null) {
            $this->db->where('enquiry_id', $enquiry_id);
        }

        // ✅ Only fetch challans without invoice
        $this->db->where('invoice_status', 0);

        $query = $this->db->get();
        return $query->result_array();
    }

    // Insert invoice master
    public function insert_invoice_master($data)
    {
        $this->db->insert('invoice_master', $data);
        return $this->db->insert_id();
    }

    // Insert multiple products
    public function insert_invoice_products($products)
    {
        return $this->db->insert_batch('invoice_product', $products);
    }

    // Fetch invoice master by ID
    public function get_invoice_master($invoice_id)
    {
        return $this->db->where('invoice_id', $invoice_id)
            ->get('invoice_master')
            ->row_array();
    }


    // Fetch invoice products
    public function get_invoice_products($invoice_id)
    {
        return $this->db->select('ip.*, im.item_name, im.item_code, um.unit_name')
            ->from('invoice_product ip')
            ->join('item_master im', 'im.item_id = ip.product_id', 'left')
            ->join('unit_master um', 'um.unit_id = ip.unit_id', 'left')
            ->where('ip.invoice_id', $invoice_id)
            ->get()
            ->result_array();
    }

    public function list_all_sales_order()
    {
        $this->db->select('so.*,qm.quotation_code,em.enquiry_code');
        $this->db->from('sales_order_master so');
        $this->db->join('quotation_master qm', 'so.qtn_id = qm.qtn_id', 'left');
        $this->db->join('enquiry_master em', 'so.enquiry_id = em.enquiry_id', 'left');
        //$this->db->where('so.enquiry_id', $enquiry_id);
        //$this->db->where('so.qtn_id', $quotation_id);
        $this->db->where('so.active', 1);
        $query = $this->db->get();

        return $query->result_array(); // return single row
    }
    public function list_all_delivery_challan()
    {
        $this->db->select('
         dm.del_id,
        dm.delivery_code,
        dm.so_id,
        dm.delivery_mode,
        dm.deliverd_by,
        dm.item_issued_by,
        dm.delivery_date,
        dm.shipping_address,
        dm.shipping_city,
        dm.contact,
        dm.email,
        dm.remark,
        dm.invoice_status,
        dm.created_on,
        dm.created_by,
        qm.quotation_code,
        em.enquiry_code,
        som.so_code,
        som.enquiry_id,
        som.qtn_id
    ');
        $this->db->from('delivery_master dm');
        $this->db->join('sales_order_master som', 'som.so_id = dm.so_id', 'left');
        $this->db->join('quotation_master qm', 'qm.qtn_id = som.qtn_id', 'left');
        $this->db->join('enquiry_master em', 'som.enquiry_id = em.enquiry_id', 'left');
        // $this->db->where('so.qtn_id', $quotation_id);
        // $this->db->where('so.active', 1);

        $query = $this->db->get();
        return $query->result_array(); // returns all rows as array
    }

    public function list_all_invoices()
{
    $this->db->select('
        im.invoice_id,
        im.invoice_code,
        im.invoice_type,
        im.invoice_date,
        im.delivery_id,
        im.so_id,
        im.invoice_customer,
        im.branch_id,
        im.sub_total,
        im.discount_percentage,
        im.discount_amount,
        im.total_beforevat,
        im.vat_percentage,
        im.vat_amount,
        im.grand_total,
        im.payment_mode,
        im.advance_amount_percentage,
        im.advance_amount,
        im.balance_amount,
        im.validity,
        im.payment_term,
        im.terms_and_condition,
        im.delivery_term,
        im.remark,
        im.invoice_status,
        im.cancelled,
        im.sales_return,
        im.fully_delivered,
        im.bank_id,
        im.created_by,
        im.created_at,
        im.updated_on,
        im.updated_by,
        im.po_number,
        qm.quotation_code,
        em.enquiry_code,
        so.so_code,
        so.enquiry_id,
        so.estimation_id,
        dm.delivery_code
    ');
    $this->db->from('invoice_master im');
    $this->db->join('quotation_master qm', 'qm.qtn_id = im.quotation_id', 'left');
    $this->db->join('enquiry_master em', 'em.enquiry_id = im.enquiry_id', 'left');
    $this->db->join('sales_order_master so', 'so.so_id = im.so_id', 'left');
    $this->db->join('delivery_master dm', 'dm.del_id = im.delivery_id', 'left');
    $this->db->where('im.invoice_status', 'Active');
    $this->db->where('im.sales_return !=', 1);

    $query = $this->db->get();
    return $query->result_array(); // returns all rows
}

    public function list_Cancel_invoices()
    {
        $this->db->select('im.*,qm.quotation_code,em.enquiry_code,so.so_code,dm.delivery_code');
        $this->db->from('invoice_master im');
        $this->db->join('quotation_master qm', 'qm.qtn_id  = im.quotation_id', 'left');
        $this->db->join('enquiry_master em', 'em.enquiry_id = im.enquiry_id', 'left');
        $this->db->join('sales_order_master so', 'so.so_id = im.so_id', 'left');
        $this->db->join('delivery_master dm', 'dm.del_id = im.delivery_id', 'left');
        //$this->db->where('so.qtn_id', $quotation_id);
        $this->db->where('im.invoice_status', "Cancel");
        $query = $this->db->get();

        return $query->result_array(); // return single row
    }
    public function list_Sales_retun_invoices()
    {
        $this->db->select('im.*,qm.quotation_code,em.enquiry_code,so.so_code,dm.delivery_code');
        $this->db->from('invoice_master im');
        $this->db->join('quotation_master qm', 'qm.qtn_id  = im.quotation_id', 'left');
        $this->db->join('enquiry_master em', 'em.enquiry_id = im.enquiry_id', 'left');
        $this->db->join('sales_order_master so', 'so.so_id = im.so_id', 'left');
        $this->db->join('delivery_master dm', 'dm.del_id = im.delivery_id', 'left');
        //$this->db->where('so.qtn_id', $quotation_id);
        $this->db->where('im.sales_return', 1);
        $query = $this->db->get();

        return $query->result_array(); // return single row
    }
    public function list_direct_invoices()
    {
        $this->db->select('im.*, cm.customer_name');
        $this->db->from('invoice_master im');
        $this->db->join('customer_master cm', 'cm.customer_id = im.invoice_customer', 'left');
        $this->db->where('im.invoice_type', "Direct");
        $query = $this->db->get();

        return $query->result_array();
    }
}
