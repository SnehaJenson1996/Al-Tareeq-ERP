<?php
class Reports_model extends CI_Model {

    public function __construct()
    {
        
    }
    public function get_rfq_report_records()
    {
        $from = isset($_REQUEST['from_date']) ? date('Y-m-d', strtotime($_REQUEST['from_date'])) : '';
        $to = isset($_REQUEST['to_date']) ? date('Y-m-d', strtotime($_REQUEST['to_date'])) : '';

        // Fail early if no date filters
        if (empty($from) || empty($to)) {
            return [];
        }

        $created_by = isset($_REQUEST['created_by']) ? $_REQUEST['created_by'] : '';
        $supplier_id = isset($_REQUEST['supplier_id']) ? $_REQUEST['supplier_id'] : '';

        $user_condition = '';
        $supplier_condition = '';

        if ($created_by != '') {
            $user_condition = " AND r.created_by = '$created_by'";
        }

        if ($supplier_id != '') {
            $supplier_condition = " AND r.supplier_id = '$supplier_id'";
        }

        $query = $this->db->query("
            SELECT 
                r.rfq_id,
                r.rfq_code,
                r.rfq_date,
                r.rev_version,
                r.supplier_id,
                CONCAT(em.user_name) AS rfq_created_by,
                supplier_name 
            FROM 
                purchase_rfq r
            JOIN users em ON r.created_by = em.user_id
            JOIN supplier_master s ON r.supplier_id = s.supplier_id
            WHERE 
                r.rfq_date BETWEEN '$from' AND '$to'
                $user_condition 
                $supplier_condition 
            ORDER BY 
                r.rfq_date DESC
        ");

        return $query->result();
    }

    function get_po_report_records(){
        $from = isset($_REQUEST['from_date']) ? date('Y-m-d', strtotime($_REQUEST['from_date'])) : '';
        $to = isset($_REQUEST['to_date']) ? date('Y-m-d', strtotime($_REQUEST['to_date'])) : '';

        // Fail early if no date filters
        if (empty($from) || empty($to)) {
            return [];
        }

        $created_by = isset($_REQUEST['created_by']) ? $_REQUEST['created_by'] : '';
        $supplier_id = isset($_REQUEST['supplier_id']) ? $_REQUEST['supplier_id'] : '';

        $user_condition = '';
        $supplier_condition = '';

        if ($created_by != '') {
            $user_condition = " AND r.created_by = '$created_by'";
        }

        if ($supplier_id != '') {
            $supplier_condition = " AND r.supplier_id = '$supplier_id'";
        }

    
        $query=$this->db->query("select r.po_id,r.po_code,r.po_date,r.po_type, concat(em.user_name)as rfq_created_by, supplier_name,r.grand_total,r.po_status from purchase_order_master r, users em, supplier_master s where r.grn_status=0 and r.created_by=em.user_id and r.supplier_id=s.supplier_id and r.po_date between '$from' and '$to'  $user_condition $supplier_condition order by r.po_date desc;");
        return $query->result();
    }
    function get_grn_report_records(){
        $from = isset($_REQUEST['from_date']) ? date('Y-m-d', strtotime($_REQUEST['from_date'])) : '';
        $to = isset($_REQUEST['to_date']) ? date('Y-m-d', strtotime($_REQUEST['to_date'])) : '';

        // Fail early if no date filters
        if (empty($from) || empty($to)) {
            return [];
        }

        $created_by = isset($_REQUEST['created_by']) ? $_REQUEST['created_by'] : '';
        $supplier_id = isset($_REQUEST['supplier_id']) ? $_REQUEST['supplier_id'] : '';

        $user_condition = '';
        $supplier_condition = '';

        if ($created_by != '') {
            $user_condition = " AND r.created_by = '$created_by'";
        }

        if ($supplier_id != '') {
            $supplier_condition = " AND r.supplier_id = '$supplier_id'";
        }

    
        $query=$this->db->query("select r.grn_id,r.grn_code,r.grn_date, concat(em.user_name)as grn_created_by, supplier_name,r.grand_total from purchase_grn_master r, users em, supplier_master s where r.created_by=em.user_id and r.supplier_id=s.supplier_id and r.grn_date between '$from' and '$to'  $user_condition $supplier_condition order by r.grn_date desc;");
        return $query->result();
    }

    public function get_enquiry_report()
	{

		$from_date = $_POST['from_date'] ?? date('Y-m-d');
		$to_date = $_POST['to_date'] ?? date('Y-m-d');
		$customer = $_POST['customer'] ?? '';
		$sales_person = $_POST['sales_person'] ?? '';

		$this->db->select('em.*,cm.customer_name,u.user_name as sales_person,u2.user_name as created,u3.user_name as last_updated');
		$this->db->from('enquiry_master em');
		$this->db->join('customer_master cm', 'em.enquiry_customer=cm.customer_id', 'left');
$this->db->join('users u', 'em.created_by=u.user_id', 'left');
		$this->db->join('users u2', 'em.created_by=u2.user_id', 'left');
		$this->db->join('users u3', 'em.updated_by=u3.user_id', 'left');

		if ($sales_person != '') {
			$this->db->where('em.created_by', $sales_person);
		}
		if ($customer != '') {
			$this->db->where('em.enquiry_customer', $customer);
		}
		$this->db->where("em.enquiry_date BETWEEN '$from_date' AND '$to_date'", null, false);
		$res = $this->db->get()->result();

		return $res;
	}


    public function print_enquiry_report()
	{
		$from_date1 = $_GET['from_date'] ?? date('Y-m-d');
		$to_date1 = $_GET['to_date'] ?? date('Y-m-d');
		$customer = $_GET['customer'] ?? '';
		$sales_person = $_GET['sales_person'] ?? '';

		$from_date = date('Y-m-d', strtotime($from_date1));
		$to_date = date('Y-m-d', strtotime($to_date1));


		$this->db->select('em.*, cm.customer_name, u.user_name as sales_person, u2.user_name as created, u3.user_name as last_updated');
		$this->db->from('enquiry_master em');
		$this->db->join('customer_master cm', 'em.enquiry_customer = cm.customer_id', 'left');
		$this->db->join('users u', 'em.sales_person = u.user_id', 'left');
		$this->db->join('users u2', 'em.created_by = u2.user_id', 'left');
		$this->db->join('users u3', 'em.updated_by = u3.user_id', 'left');

		if (!empty($sales_person)) {
			$this->db->where('em.created_by', $sales_person);
		}

		if (!empty($customer)) {
			$this->db->where('em.enquiry_customer', $customer);
		}

		$this->db->where("em.enquiry_date BETWEEN '$from_date' AND '$to_date'", null, false);

		$res = $this->db->get()->result();


		//   echo $this->db->last_query();

		return $res;
	}

    public function get_quotation_report()
{
    $from_date     = $_POST['from_date'] ?? date('Y-m-d');
    $to_date       = $_POST['to_date'] ?? date('Y-m-d');
    $customer      = $_POST['customer'] ?? '';
    $status        = $_POST['status'] ?? '123';
    $sales_person  = $_POST['sales_person'] ?? '';

    $this->db->select('
        qm.*, 
        c.customer_name,
        COALESCE(u.user_name, "-") as created, 
        COALESCE(u2.user_name, "-") as last_updated
    ');

    $this->db->from('quotation_master qm');

    // ✅ Correct enquiry join
    $this->db->join(
        'enquiry_master enq',
        'qm.enquiry_id = enq.enquiry_id AND qm.quotation_type != "direct"',
        'left'
    );

    // ✅ Customer join (correct)
    $this->db->join(
        "customer_master c",
        "(
            (qm.quotation_type != 'direct' AND enq.enquiry_customer = c.customer_id) OR
            (qm.quotation_type = 'direct' AND qm.quotation_customer = c.customer_id)
        )",
        "left"
    );

    $this->db->join('users u', 'qm.created_by = u.user_id', 'left');
    $this->db->join('users u2', 'qm.updated_by = u2.user_id', 'left');

    // Status filter
    if ($status != '123') {
        $this->db->where('qm.aproval', $status);
    }

    // ✅ Customer filter
    if (!empty($customer)) {
        $this->db->group_start();
            $this->db->where('qm.quotation_customer', $customer);
            $this->db->or_where('enq.enquiry_customer', $customer);
        $this->db->group_end();
    }

    // ✅ Sales person filter
    if (!empty($sales_person)) {
        $this->db->where('qm.created_by', $sales_person);
    }

    // Date filter
    $this->db->where("qm.quotation_date >=", $from_date);
    $this->db->where("qm.quotation_date <=", $to_date);

    // ✅ ADD THIS
$this->db->where("
    qm.quotation_revision = (
        SELECT MAX(q2.quotation_revision)
        FROM quotation_master q2
        WHERE q2.quotation_code = qm.quotation_code
    )
", null, false);

    $this->db->order_by('qm.qtn_id', 'DESC');

    return $this->db->get()->result();
}
public function get_print_quotation_report($filters = [])
{
    $from_date    = $filters['from_date'] ?? date('Y-m-d');
    $to_date      = $filters['to_date'] ?? date('Y-m-d');
    $customer     = $filters['customer'] ?? '';
    $status       = $filters['status'] ?? '123';
    $sales_person = $filters['sales_person'] ?? '';

    $this->db->select('
        qm.*,
        c.customer_name,
        COALESCE(u.user_name,"-") as created,
        COALESCE(u2.user_name,"-") as last_updated
    ');

    $this->db->from('quotation_master qm');

    // COMMON JOINS (ALWAYS OUTSIDE CONDITION)
    $this->db->join('users u', 'qm.created_by = u.user_id', 'left');
    $this->db->join('users u2', 'qm.updated_by = u2.user_id', 'left');
    $this->db->join('estimation_master em', 'qm.estimation_id = em.estimation_id', 'left');
    $this->db->join('enquiry_master enq', 'em.enquiry_id = enq.enquiry_id', 'left');

    // CUSTOMER JOIN (UNIFIED)
    $this->db->join('customer_master c', "
        (
            (qm.quotation_type = 'direct' AND qm.quotation_customer = c.customer_id)
            OR
            (qm.quotation_type != 'direct' AND enq.enquiry_customer = c.customer_id)
        )
    ", 'left');

    // STATUS
    if ($status != '123') {
        $this->db->where('qm.aproval', $status);
    }

    // DATE FILTER
    $this->db->where('qm.quotation_date >=', $from_date);
    $this->db->where('qm.quotation_date <=', $to_date);

    // CUSTOMER FILTER
    if (!empty($customer)) {
        $this->db->group_start();
        $this->db->where('qm.quotation_customer', $customer);
        $this->db->or_where('enq.enquiry_customer', $customer);
        $this->db->group_end();
    }

    // SALES PERSON FILTER (IMPORTANT FIX)
    if (!empty($sales_person)) {
        $this->db->group_start();
        $this->db->where('qm.created_by', $sales_person);
        $this->db->or_where('enq.sales_person', $sales_person);
        $this->db->group_end();
    }

    // 🔥 FIX DUPLICATE REVISION ISSUE
    $this->db->where("
        qm.quotation_revision = (
            SELECT MAX(q2.quotation_revision)
            FROM quotation_master q2
            WHERE q2.quotation_code = qm.quotation_code
        )
    ", null, false);

    $this->db->order_by('qm.qtn_id', 'DESC');

    return $this->db->get()->result();
}

public function pi_report()
	{

		$from_date = $_POST['from_date'] ?? date('Y-m-d');
		$to_date = $_POST['to_date'] ?? date('Y-m-d');
		$customer = $_POST['customer'] ?? '';
		$quotation = $_POST['quotation'] ?? '';
		$status = $_POST['status'] ?? '';
		$sales_person = $_REQUEST['sales_person'] ?? '';

		$this->db->select('so.*,cm.customer_name,sqm.quotation_code,sqm.quotation_revision,u.user_name as created,u2.user_name as last_updated');
		$this->db->from('sales_order_master so');
		$this->db->join('quotation_master sqm', 'so.qtn_id=sqm.qtn_id', 'left');
		$this->db->join('estimation_master em', 'sqm.estimation_id=em.estimation_id', 'left');
		$this->db->join('enquiry_master enq', 'em.enquiry_id=enq.enquiry_id', 'left');
		$this->db->join('customer_master cm', 'enq.enquiry_customer=cm.customer_id', 'left');
		$this->db->join('users u', 'so.created_by=u.user_id', 'left');
		$this->db->join('users u2', 'so.updated_by=u2.user_id', 'left');

		if ($status != '') {
			$this->db->where('so.active', $status);
		}
		if ($quotation != '') {
			$this->db->where('so.qtn_id', $quotation);
		}
		if ($customer != '') {
			$this->db->where('enq.enquiry_customer', $customer);
		}
		 if ($sales_person != '') {
        $this->db->where('so.created_by', $sales_person); 
    }
		$this->db->where("so.so_date BETWEEN '$from_date' AND '$to_date'", null, false);
		$res = $this->db->get()->result();

		return $res;
	}
public function print_pi_report()
	{

		$from_date = $_GET['from_date'] ?? date('Y-m-d');
		$to_date = $_GET['to_date'] ?? date('Y-m-d');
		$customer = $_GET['customer'] ?? '';
		$quotation = $_GET['quotation'] ?? '';
		$status = $_GET['status'] ?? '';
		$sales_person = $_GET['sales_person'] ?? '';

		$this->db->select('so.*,cm.customer_name,sqm.quotation_code,sqm.quotation_revision,u.user_name as created,u2.user_name as last_updated');
		$this->db->from('sales_order_master so');
		$this->db->join('quotation_master sqm', 'so.qtn_id=sqm.qtn_id', 'left');
		$this->db->join('estimation_master em', 'sqm.estimation_id=em.estimation_id', 'left');
		$this->db->join('enquiry_master enq', 'em.enquiry_id=enq.enquiry_id', 'left');
		$this->db->join('customer_master cm', 'enq.enquiry_customer=cm.customer_id', 'left');
		$this->db->join('users u', 'so.created_by=u.user_id', 'left');
		$this->db->join('users u2', 'so.updated_by=u2.user_id', 'left');

		if ($status != '') {
			$this->db->where('so.active', $status);
		}
		if ($quotation != '') {
			$this->db->where('so.qtn_id', $quotation);
		}
		if ($customer != '') {
			$this->db->where('enq.enquiry_customer', $customer);
		}
		if ($sales_person != '') {
        $this->db->where('so.created_by', $sales_person); 
    }
		$this->db->where("so.so_date BETWEEN '$from_date' AND '$to_date'", null, false);
		$res = $this->db->get()->result();

		return $res;
	}

  
}?>