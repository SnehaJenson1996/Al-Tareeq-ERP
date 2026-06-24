<?php


function calculate_opening_bal($from_date1, $account_id, $branch_id = null)
{
    $CI =& get_instance();

    $from_date = date('Y-m-d', strtotime($from_date1));

    $branch_condition = "";
    if (!empty($branch_id)) {
        $branch_condition = " AND v.branch_id = ".$CI->db->escape($branch_id);
    }

    $query = $CI->db->query("
        SELECT COALESCE(two1.opening_sum,0) + COALESCE(two.opening,0) AS opening_bal
        FROM (
            SELECT 
                r.account_id,
                CASE 
                    WHEN opening_bal_type = 'Dr' THEN opening_balance 
                    ELSE -opening_balance 
                END AS opening
            FROM general_ledger r 
            WHERE r.account_id = '$account_id'
        ) AS two
        LEFT JOIN (
            SELECT 
                account_id,
                SUM(
                    CASE 
                        WHEN drcr_type = 'Dr' THEN amount 
                        ELSE -amount 
                    END
                ) AS opening_sum
            FROM voucher_transaction v
            WHERE cancel = 0
            AND DATE(v.voucher_date) <= '$from_date'
            AND v.account_id = '$account_id'
            $branch_condition
            GROUP BY account_id
        ) AS two1 
        ON two.account_id = two1.account_id
    ");

    return $query->row('opening_bal');
}

/*function calculate_todays_order_wise_opening_bal($from_date1,$account_id, $order_id)
{
	$CI =& get_instance();

	$from_date = date('Y-m-d',strtotime($from_date1));
	$query=$CI->db->query("select coalesce(two1.opening_sum,0)+coalesce(two.opening,0) as opening_bal from (select r.account_id,r.customer_id, Case when opening_bal_type ='DR' THEN opening_balance ELSE -opening_balance END as opening from general_ledger r where r.account_id='$account_id') as two LEFT JOIN (select account_id,customer_id,SUM(CASE WHEN drcr_type= 'DR' THEN amount ELSE (-amount) end) AS opening_sum from voucher_transaction v where cancel=0 and date(v.voucher_date) <= '$from_date' and v.account_id='$account_id' and v.order_id=$order_id group by account_id) as two1 on (two.account_id=two1.account_id);");
	return $query->row('opening_bal');
}*/

function get_accountname_by_id($account_id)
{

	$CI = & get_instance();
	$query=$CI->db->query(" select account_name from general_ledger where account_id='$account_id' ");
	return $query->row('account_name');
}

///account receipt related entry
function get_paid_invoice_amount($type, $id, $account_id)
{
	$CI = & get_instance();
	if($type=='customer')
	{
		$query=$CI->db->query("select two.amount as paid_amt from (select grand_total from invoice_master where invoice_id='$id')as one left join(select coalesce(sum(amount),0)as amount from voucher_transaction  where trans_id=$id and account_id=$account_id and drcr_type='Cr')as two on(1=1) ");
		return $query->row('paid_amt');
	}
	else
	{
		$query=$CI->db->query("select two.amount as paid_amt from (select grand_total from GRN_master where grn_id='$id')as one left join(select coalesce(sum(amount),0)as amount from voucher_transaction  where trans_id=$id and account_id=$account_id and drcr_type='Dr')as two on(1=1) ");
		return $query->row('paid_amt');
	}
}
?>
