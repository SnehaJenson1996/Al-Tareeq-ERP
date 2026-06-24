<?php 

function get_po_incoming_stock($order_code,$size)
{
    $CI =& get_instance();
    
     $query=$CI->db->query("select concat((qty-allocation),' / ',allocation)as stock from (select Coalesce(sum(quantity),0)as qty, sum(allocation)as allocation  from purchase_order p, purchase_order_transaction tr where p.po_id=tr.po_master_id and p.revision=tr.trans_revision and grn_status=0 and p.remark='direct'  and order_code='$order_code' and size='$size' and p.cancelled=0)as tmp");
    return $query->row('stock');
}
function get_last_grn_quantity($po_id,$pid,$pkg)
{
    $CI =& get_instance();
    
     $query=$CI->db->query("select COALESCE(sum(quantity),0)as quantity from grn_master m, grn_transaction tr where m.grn_id=tr.grn_master_id and po_id='$po_id' and product_id='$pid' and packing_id='$pkg'");
    return $query->row('quantity');
}

function get_product_current_stock($order_code,$size,$warehouse)
{
    $CI =& get_instance();
    
     $query=$CI->db->query("select concat((qty-allocation),' / ',allocation)as stock from (select sum(quantity)as qty, sum(allocation)as allocation from stock_details where order_code='$order_code' and size='$size' and stock_type='IN' and status='0')as tmp");
    return $query->row('stock');
}

function get_product_avg_price($order_code,$size,$warehouse)
{
    $CI =& get_instance();
    
     $query=$CI->db->query("select avg(price) as price from (select price from stock_details where stock_type='IN' and order_code='$order_code' and size='$size' and status=0 )as tmp ");
    return $query->row('price');
}

function get_product_last_price($order_code,$size,$warehouse)
{
    $CI =& get_instance();
    
     $query=$CI->db->query("select price as price from (select price from stock_details where stock_type='IN' and order_code='$order_code' and size='$size' and status='0' order by stock_date desc limit 1)as tmp ");
    return $query->row('price');
}
function get_bill_entry_fromstock($warehouse)
{
    $CI =& get_instance();
    
     $query=$CI->db->query("select *, sum(quantity)as stock, group_concat(stock_id)as stk_id from stock_details where stock_type='IN' and status='0' group by model_code,bill_no,order_ref_no,box_no;");
    return $query->result();
}
function get_allocation_details($model_code,$bill_no,$order_ref_no,$box_no)
{
    $CI =& get_instance();
    
     $query=$CI->db->query("select one.*, petrostar_ref from(select allocation_for, sum(quantity)as quantity from stock_details where model_code='$model_code' and bill_no='$bill_no' and order_ref_no='$order_ref_no' and box_no='$box_no' and allocation='1' and stock_type='IN' and status='0' group by allocation_for)as one left join(select petrostar_ref,quote_id from sales_quotation_master)as two on(one.allocation_for=two.quote_id);");
    return $query->result();
}
function get_product_opening_stock($model_code,$from_date,$warehouse)
{
    $CI =& get_instance();
    $from_date =date('Y-m-d',strtotime($from_date));
    
     $query=$CI->db->query("select (coalesce(one.in_qty,0)-coalesce(two.out_qty,0)) as stock, (coalesce(one.in_price,0)-coalesce(two.out_price,0)) as total_price from (select * from stock_details where warehouse_id='$warehouse' and model_code='$model_code' and stock_date<'$from_date' group by order_code,size)as zero left join (select coalesce(sum(quantity),0)as in_qty, coalesce(sum(quantity*price),0)as in_price, order_code,model_code,size from stock_details where stock_type='IN' and stock_date<'$from_date' group by order_code,size)as one on(zero.model_code=one.model_code) left join(select coalesce(sum(quantity),0)as out_qty, coalesce(sum(quantity*price),0)as out_price, order_code,item_desc,size from stock_details where stock_type='OUT' and stock_date<'$from_date' group by order_code,size)as two on(zero.order_code=two.order_code and zero.size=two.size) ");
    return $query->result();
}
function get_sales_dc($id)
{
	$CI =& get_instance();
    
     	$query=$CI->db->query("select c.cust_name as perticular_name, concat(invoice_code,' ', petrostar_ref_no)as vch_no from delivery_order  e, customer_master c, invoice_master i where e.customer_id=c.customer_id and e.invoice_id=i.invoice_id and e.dc_id=$id");
    	return $query->result();
}
function get_purchase_grn($id)
{
	$CI =& get_instance();
    
     	$query=$CI->db->query("select two.supplier_name as perticular_name, concat(grn_code,' ', invoice_no)as vch_no from (select g.* from grn_master g  where grn_id=$id)as one left join(select * from supplier_master)as two on(one.supplier_id=two.supplier_id)");
    	return $query->result();
}
?>
