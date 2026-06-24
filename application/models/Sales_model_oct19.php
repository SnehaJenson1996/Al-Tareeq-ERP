<?php
class Sales_model extends CI_Model {

    public function __construct()
    {
        
    }

    public function insert_enquiry($data){
		$this->db->insert('enquiry_master', $data);

		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id(); // returns the auto-increment ID
		} else {
			return false; // insert failed
		}

	}
	public function insert_site_survey($data){
		$this->db->insert('site_survey_master', $data);

		if ($this->db->affected_rows() > 0) {
			return $this->db->insert_id(); // returns the auto-increment ID
		} else {
			return false; // insert failed
		}

	}
	public function get_survey_by_id($survey_id){
		$this->db->select('*');
        $this->db->from('site_survey_master');		
		$this->db->where('survey_id',$survey_id);
		$result = $this->db->get()->row_array();
        return $result;

	}
	public function get_survey_files_by_id($survey_id){
		$this->db->select('sf.*');
        $this->db->from('survey_files sf');		
		$this->db->where('sm.survey_id',$survey_id);
		$this->db->join('site_survey_master sm','sm.survey_id = sf.survey_id');	
		$this->db->where('sm.active',1);
		$result = $this->db->get()->result();
        return $result;

	}
	public function get_survey_old_data($enquiry_id){
		$this->db->select('sm.*, sf.file_name');
$this->db->from('site_survey_master sm');
$this->db->join('(SELECT survey_id, MAX(file_name) as file_name 
                  FROM survey_files 
                  GROUP BY survey_id) sf',
                'sm.survey_id = sf.survey_id','left');
$this->db->where('sm.enquiry_id',$enquiry_id);
$this->db->where('sm.active',0);
$this->db->where('sm.re_survey_status',1);
$this->db->order_by('sm.survey_id','DESC');
return $this->db->get()->result();
	}

	//By assigned Person
	public function get_enquiry_for_survey($userid) {
		$this->db->select('SM.*, EM.*'); 
		$this->db->from('site_survey_master as SM');	
		$this->db->join('enquiry_master as EM', 'EM.enquiry_id = SM.enquiry_id');	
		$this->db->where('SM.assigned_person_id', $userid);
		$this->db->where('SM.survey_status', 1);
		$this->db->where('SM.active', 1);
		return $this->db->get()->result();
   }
   public function get_enquiry_for_survey_by_id($userid) {
		$this->db->select('SM.*, EM.*'); 
		$this->db->from('site_survey_master as SM');	
		$this->db->join('enquiry_master as EM', 'EM.enquiry_id = SM.enquiry_id');	
		$this->db->where('EM.enquiry_id', $userid);
		$this->db->where('SM.active', 1);
		 return $this->db->get()->row_array();
   }
    public function update_survey($survey_id, $data)
	{
		$this->db->where('survey_id', $survey_id)
				->update('site_survey_master', $data);
		return $this->db->affected_rows();
	}
	public function get_by_enquiry($enquiry_id)
	{
		return $this->db->where('enquiry_id', $enquiry_id)
						->where('active', 1)  // active = 1 condition
						->get('site_survey_master')
						->row();
	}



	public function get_all_enquiry_list(){
		$this->db->select('em.*,cm.customer_name');
        $this->db->from('enquiry_master em');
		$this->db->join('customer_master cm','em.enquiry_customer=cm.customer_id');
		$this->db->order_by('enquiry_code','DESC');
        $result = $this->db->get()->result();
         return $result; 
	}

	public function get_enquiry_by_id($enquiry_id){
		$this->db->select('em.*,cm.customer_name,cm.customer_TR_no,br.branch_name,br.branch_header,br.branch_footer,br.branch_contact,br.branch_email,br.branch_location,br.branch_address,br.branch_web,cm.customer_email,cm.contact_number,cm.emirate,cm.customer_address,u.user_name');
        $this->db->from('enquiry_master em');
		$this->db->join('customer_master cm','em.enquiry_customer=cm.customer_id');
		$this->db->join('branch_master br','em.branch_id=br.branch_id');
		$this->db->join('users u','u.user_id=em.created_by');
		$this->db->where('enquiry_id',$enquiry_id);
        $result = $this->db->get()->row_array();
        return $result;
	}
	public function get_survey_by_enquiry_id($enquiry_id){
		$this->db->select('sm.*,u.user_name');
        $this->db->from('site_survey_master sm');	
		$this->db->join('users u','u.employee_id = sm.assigned_person_id');	
		$this->db->where('sm.enquiry_id',$enquiry_id);
		$this->db->where('sm.active',1);
        $result = $this->db->get()->row_array();
        return $result;

	}

	public function get_estimation_details($enquiry_id)
{
    $this->db->select("
        em.estimation_id, em.enquiry_id, em.estimation_date,
        em.sub_total, em.margin_percentage, em.margin_amount,
        em.discount_percentage,em.discount_amount,em.freight_percentage, em.freight_amount,
        em.bank_charge, em.travel_expense, em.inspection_cost,
        em.grand_total,em.other_expense, em.approval,

        mh.main_heading_id, mh.main_heading, mh.main_details,

        sh.sub_heading_id, sh.sub_heading,

        pd.product_table_id, pd.product_description,pd.product_id, pm.item_name,
        pd.unit_id, um.unit_name,
        pd.quantity, pd.unit_price, pd.amount
    ");
    $this->db->from("estimation_master em");
    $this->db->join("estimation_main_heading mh", "em.estimation_id  = mh.estimation_master_id", "left");
    $this->db->join("estimation_sub_heading sh", "mh.main_heading_id = sh.main_heading_id", "left");
    $this->db->join("estimation_product_details pd", "sh.sub_heading_id = pd.sub_heading_id", "left");
    $this->db->join("item_master pm", "pd.product_id = pm.item_id", "left");
    $this->db->join("unit_master um", "pd.unit_id = um.unit_id", "left");

    $this->db->where("em.enquiry_id", $enquiry_id);
    $this->db->where("em.active", 1);

    $this->db->order_by("mh.main_heading_id, sh.sub_heading_id, pd.product_table_id");

    return $this->db->get()->result_array();
}

//Quotation 
	public function insert_quotation($data) {
		$this->db->insert('quotation_master', $data);
		return $this->db->insert_id();
	}

	public function insert_main_heading($data) {
		$this->db->insert('quotation_main_heading', $data);
		return $this->db->insert_id();
	}

	public function insert_subheading($data) {
		$this->db->insert('quotation_subheading', $data);
		return $this->db->insert_id();
	}

	public function insert_product($data) {
		$this->db->insert('quotation_products', $data);
		return $this->db->insert_id();
	}

	public function get_quotation_full_details($enquiry_id)
{
    $this->db->select("
        qm.qtn_id, qm.quotation_code, qm.quotation_date, qm.enquiry_id, qm.estimation_id,
        qm.sub_total, qm.estimation_amount, qm.total_before_discount, qm.discount_percentage, qm.discount_amount,
        qm.vat_required, qm.total_before_vat, qm.vat_percentage, qm.vat_amount, qm.grand_total,
        qm.payment_term, qm.delivery_term, qm.terms_condition, qm.validity, qm.created_by, qm.active,
        qm.aproval, qm.approved_by, qm.quotation_status, qm.quotation_revision, qm.created_on, qm.updated_on,

        qmh.id as main_heading_id, qmh.main_heading, qmh.description,

        qsh.id as subheading_id, qsh.subheading,

        qp.id as quotation_product_id, qp.prd_id,qp.prd_description, qp.unit_id, qp.unit_price, qp.qty, qp.amount,qp.taxable_amount,qp.dicount_amount,

        im.item_name, im.item_code,
        um.unit_name,

        em.estimation_id as est_id, em.estimation_code,

        enq.enquiry_id as enq_id, enq.branch_id, enq.enquiry_code, enq.enquiry_category,
        enq.project_name, enq.project_subject, enq.project_location
    ");

    $this->db->from("quotation_master qm");
    $this->db->join("quotation_main_heading qmh", "qm.qtn_id = qmh.qtn_id", "left");
    $this->db->join("quotation_subheading qsh", "qmh.id = qsh.main_heading_id", "left");
    $this->db->join("quotation_products qp", "qsh.id = qp.sub_heading_id", "left");
    $this->db->join("item_master im", "qp.prd_id = im.item_id", "left");
    $this->db->join("unit_master um", "qp.unit_id = um.unit_id", "left");
    $this->db->join("estimation_master em", "qm.estimation_id = em.estimation_id", "left");
    $this->db->join("enquiry_master enq", "qm.enquiry_id = enq.enquiry_id", "left");
    //$this->db->join("users u", "qm.approved_by = enq.user_id", "left");

    // filters
    $this->db->where("qm.qtn_id", $enquiry_id);
    $this->db->where("qm.active", 1);

    $query = $this->db->get();
    return $query->result_array();
}

public function update_quotation($quotation_id, $data)
    {
        $this->db->where('qtn_id', $quotation_id);
        return $this->db->update('quotation_master', $data);
    }

    
	public function get_max_revision($enq_master_id)
	{
		$this->db->select_max('quotation_revision');
		$this->db->where('enquiry_id', $enq_master_id);
		$query = $this->db->get('quotation_master');
		$row = $query->row();
		return $row ? (int) $row->quotation_revision : 0;
	}
    public function get_all_qtn_revisions($enq_master_id)
    {
        $this->db->where('enquiry_id', $enq_master_id);
        $this->db->where('active', 0);
        $this->db->where('quotation_revision !=', 0);
        $this->db->order_by('quotation_revision', 'ASC'); // or DESC for latest first
        $query = $this->db->get('quotation_master');
        return $query->result(); // returns all revision rows
    }


	public function delete_qtn_products($quotation_id)
    {
        $this->db->where('qtn_id', $quotation_id);
        $this->db->delete('quotation_products');
    }
	public function delete_qtn_sub_headings($quotation_id)
    {
        $this->db->where('qtn_id', $quotation_id);
        $this->db->delete('quotation_subheading');
    }
	public function delete_qtn_main_headings($quotation_id)
    {
        $this->db->where('qtn_id', $quotation_id);
        $this->db->delete('quotation_main_heading');
    }
    public function get_quotation_details_by_id($qtn_id){
		 $this->db->select("qm.*");
		$this->db->from("quotation_master qm");
		$this->db->where("qm.qtn_id", $qtn_id);
		$query = $this->db->get();
    	return $query->result_array();
	}
	 public function get_quotation_products_by_id($qtn_id){
		 $this->db->select("qp.*,um.unit_name,im.item_name,im.item_image");
		$this->db->from("quotation_products qp");
		$this->db->join("item_master im", "im.item_id  = qp.prd_id", "left");
		$this->db->join("unit_master um", "um.unit_id  = qp.unit_id", "left");
		$this->db->where("qp.qtn_id", $qtn_id);
		$query = $this->db->get();
    	return $query->result_array();
	}

	//Calculate estimation revision
	public function get_max_revision_est($enq_master_id)
	{
		$this->db->select_max('estimation_revision');
		$this->db->where('enquiry_id', $enq_master_id);
		$query = $this->db->get('estimation_master');
		$row = $query->row();
		return $row ? (int) $row->estimation_revision : 0;
	}

	public function update_estimation($estimation_id , $data)
    {
        $this->db->where('estimation_id', $estimation_id);
        return $this->db->update('estimation_master', $data);
    }

	
	public function update_survey_master($enquiry_id,$data)
	{
		$this->db->where('enquiry_id', $enquiry_id);
		$this->db->where('active', 1); // only active survey
		return $this->db->update('site_survey_master',$data);
			
	}
	public function update_enquiry_master($quotation_id, $data)
    {
        $this->db->where('enquiry_id', $quotation_id);
        return $this->db->update('enquiry_master', $data);
    }

	 public function get_estimation_ids($enquiry_id) {
        $this->db->select('estimation_id');
        $this->db->from('estimation_master'); // use your correct table name
        $this->db->where('active', 0);
        $this->db->where('enquiry_id', $enquiry_id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return array_column($query->result_array(), 'estimation_id');
        } else {
            return []; // return empty array if no records
        }
    }

	public function get_estimation_revisions_data($estimation_id,$estimationtype="")
	{
			$this->db->select("
				em.estimation_id, em.enquiry_id, em.estimation_date,
				em.sub_total, em.margin_percentage, em.margin_amount,
				em.freight_percentage, em.freight_amount,
				em.bank_charge, em.travel_expense, em.inspection_cost,
				em.grand_total,em.other_expense, em.approval,

				mh.main_heading_id, mh.main_heading, mh.main_details,

				sh.sub_heading_id, sh.sub_heading,

				pd.product_table_id, pd.product_description,pd.product_id, pm.item_name,
				pd.unit_id, um.unit_name,
				pd.quantity, pd.unit_price, pd.amount
			");
			$this->db->from("estimation_master em");
			$this->db->join("estimation_main_heading mh", "em.estimation_id  = mh.estimation_master_id", "left");
			$this->db->join("estimation_sub_heading sh", "mh.main_heading_id = sh.main_heading_id", "left");
			$this->db->join("estimation_product_details pd", "sh.sub_heading_id = pd.sub_heading_id", "left");
			$this->db->join("item_master pm", "pd.product_id = pm.item_id", "left");
			$this->db->join("unit_master um", "pd.unit_id = um.unit_id", "left");

			$this->db->where("em.estimation_id", $estimation_id);
			
			

			$this->db->order_by("mh.main_heading_id, sh.sub_heading_id, pd.product_table_id");

			return $this->db->get()->result_array();
		}

	
public function update_enquiry_data($enquiry_id, $data)
	{
		$this->db->where('enquiry_id', $enquiry_id);
		return $this->db->update('enquiry_master', $data);
	}

public function list_survey_reports($survey_id = null)
	{
		$this->db->select('
			em.enquiry_id,
			em.branch_id,
			em.enquiry_code,
			em.enquiry_category,
			em.enquiry_date,
			em.enquiry_source,
			em.enquiry_customer,
			em.project_name,
			em.project_subject,
			em.project_location,

			ssm.survey_id,
			ssm.scheduled_date,
			ssm.start_time,
			ssm.end_time,
			ssm.scheduled_hours,
			ssm.actual_date,
			ssm.actual_start_time,
			ssm.actual_end_time,
			ssm.actual_hours,
			ssm.survey_comments,
			ssm.material_details,
			ssm.updated_on,

			bm.branch_name,
			u.user_name as preparedby,
			emps.employee_name as surveyor,
			GROUP_CONCAT(sf.file_name) as file_names
		');
		$this->db->from('enquiry_master em');
		$this->db->join('site_survey_master ssm', 'ssm.enquiry_id = em.enquiry_id', 'inner');
		$this->db->join('survey_files sf', 'sf.survey_id = ssm.survey_id', 'left');
		$this->db->join('branch_master bm', 'bm.branch_id = em.branch_id', 'left');
		$this->db->join('users u', 'u.user_id  = em.created_by', 'left');
		$this->db->join('employee_master emps', 'emps.employee_id = ssm.assigned_person_id', 'left');
		$this->db->where('em.enquiry_status !=', 1);
		$this->db->where('em.site_survey', 1);

		if ($survey_id !== null) {
			$this->db->where('ssm.survey_id', $survey_id);
		}

		$this->db->group_by('ssm.survey_id');
		$this->db->order_by('ssm.updated_on', 'DESC');

		$query = $this->db->get();

		if ($survey_id !== null) {
			return $query->row_array();  // single record
		}
		return $query->result_array();   // multiple
	}
public function get_all_estimation_details($estimation_id = null)
{
    $this->db->select("
        em.estimation_id, 
        em.enquiry_id,
        em.estimation_date,
        em.sub_total,
        em.grand_total,
        em.approval,

        em.margin_percentage,
        em.margin_amount,

        em.freight_amount,
		em.freight_percentage,

        em.bank_charge,
        em.travel_expense,
        em.inspection_cost,
        em.other_expense,

        en.enquiry_code,
        en.enquiry_date,
        en.enquiry_category, 

        c.customer_name,
        b.branch_name,

        mh.main_heading_id,
        mh.main_heading,
        mh.main_details,

        sh.sub_heading_id,
        sh.sub_heading,

        pd.product_table_id,
        pd.product_description,        
        pd.unit_id,        
        pd.quantity,
        pd.unit_price,        
        pd.amount,

        pm.item_name,
        um.unit_name,
		u.user_name as preparedby
    ");
    $this->db->from("estimation_master em");
    $this->db->join("estimation_main_heading mh", "em.estimation_id  = mh.estimation_master_id", "left");
    $this->db->join("estimation_sub_heading sh", "mh.main_heading_id = sh.main_heading_id", "left");
    $this->db->join("estimation_product_details pd", "sh.sub_heading_id = pd.sub_heading_id", "left");
    $this->db->join("item_master pm", "pd.product_id = pm.item_id", "left");
    $this->db->join("unit_master um", "pd.unit_id = um.unit_id", "left");
    $this->db->join("enquiry_master en", "em.enquiry_id = en.enquiry_id", "left");
    $this->db->join("customer_master c", "en.enquiry_customer = c.customer_id", "left");
    $this->db->join("branch_master b", "en.branch_id = b.branch_id", "left");
	$this->db->join('users u', 'u.user_id  = em.created_by', 'left');


    if($estimation_id !== null){
        $this->db->where("em.estimation_id", $estimation_id);
    }

    $this->db->order_by("em.estimation_id, mh.main_heading_id, sh.sub_heading_id, pd.product_table_id");

    return $this->db->get()->result_array();
}

public function get_estimation_master(){
    $this->db->select("
        em.estimation_id, 
        em.enquiry_id,
        em.estimation_date,
        em.sub_total,
        em.grand_total,
        em.approval,
        em.discount_amount,
        em.margin_amount,
        em.freight_amount,
        em.bank_charge,
        em.travel_expense,
        em.inspection_cost,
        em.other_expense,

        en.enquiry_code,
        en.enquiry_date,
        en.enquiry_category, 

        c.customer_name,
        b.branch_name,
        u.user_name as preparedby
    ");
    $this->db->from("estimation_master em");
    $this->db->join("enquiry_master en", "em.enquiry_id = en.enquiry_id", "left");
    $this->db->join("customer_master c", "en.enquiry_customer = c.customer_id", "left");
    $this->db->join("branch_master b", "en.branch_id = b.branch_id", "left");
    $this->db->join('users u', 'u.user_id  = en.created_by', 'left');

    $this->db->order_by('em.estimation_id', 'DESC');  // latest first

    return $this->db->get()->result_array();
}

public function get_quotation_master()
{
    $this->db->select("
        qm.qtn_id,
        qm.quotation_code,
        qm.quotation_date,
        qm.enquiry_id,
        qm.estimation_id,
        qm.sub_total,
        qm.estimation_amount,
        qm.total_before_discount,
        qm.discount_percentage,
        qm.discount_amount,
        qm.vat_required,
        qm.total_before_vat,
        qm.vat_percentage,
        qm.vat_amount,
        qm.grand_total,
        qm.payment_term,
        qm.delivery_term,
        qm.terms_condition,
        qm.validity,
        qm.quotation_status,
        qm.quotation_revision,
		qm.created_on,

        en.enquiry_code,
        en.enquiry_date,
        c.customer_name,
        b.branch_name,
        u.user_name as preparedby
    ");
    $this->db->from("quotation_master qm");
    $this->db->join("enquiry_master en", "qm.enquiry_id = en.enquiry_id", "left");
    $this->db->join("customer_master c", "en.enquiry_customer = c.customer_id", "left");
    $this->db->join("branch_master b", "en.branch_id = b.branch_id", "left");
    $this->db->join("users u", "u.user_id = qm.created_by", "left");
    $this->db->where("qm.active", 1);
    $this->db->order_by("qm.qtn_id", "DESC");

    return $this->db->get()->result_array();
}

public function get_all_quotation_details($qtn_id)
{
    $this->db->select("
        qm.qtn_id,
        qm.quotation_code,
        qm.quotation_date,
        qm.enquiry_id,
        qm.estimation_id,
        qm.sub_total,
        qm.estimation_amount,
        qm.total_before_discount,
        qm.discount_percentage,
        qm.discount_amount,
        qm.vat_required,
        qm.total_before_vat,
        qm.vat_percentage,
        qm.vat_amount,
        qm.grand_total,
        qm.payment_term,
        qm.delivery_term,
        qm.terms_condition,
        qm.validity,
        qm.quotation_status,
        qm.quotation_revision,

        en.enquiry_code,
        en.enquiry_date,
        es.estimation_code,
        c.customer_name,
        b.branch_name,
        u.user_name as preparedby,

        mh.id as main_heading_id,
        mh.main_heading,
        mh.description,

        sh.id as sub_heading_id,
        sh.subheading as sub_heading,

        pd.id,
        pd.prd_description as product_description,
        pd.unit_id,
        pd.qty as quantity,
        pd.unit_price,
        pd.amount,

        pm.item_name,
        um.unit_name
    ");
    $this->db->from("quotation_master qm");
    $this->db->join("quotation_main_heading mh", "qm.qtn_id = mh.qtn_id", "left");
    $this->db->join("quotation_subheading sh", "mh.id = sh.main_heading_id", "left");
    $this->db->join("quotation_products pd", "sh.id = pd.sub_heading_id", "left");
    $this->db->join("item_master pm", "pd.prd_id = pm.item_id", "left");
    $this->db->join("unit_master um", "pd.unit_id = um.unit_id", "left");
    $this->db->join("enquiry_master en", "qm.enquiry_id = en.enquiry_id", "left");
    $this->db->join("estimation_master es", "qm.estimation_id = es.estimation_id", "left");
    $this->db->join("customer_master c", "en.enquiry_customer = c.customer_id", "left");
    $this->db->join("branch_master b", "en.branch_id = b.branch_id", "left");
    $this->db->join("users u", "u.user_id = qm.created_by", "left");

    $this->db->where("qm.qtn_id", $qtn_id);
    $this->db->where("qm.active", 1);
    $this->db->order_by("qm.qtn_id, mh.id, sh.id, pd.id");

    return $this->db->get()->result_array();
}

public function get_quotation($qtn_id) {
        $this->db->select('qm.*, 
                           c.customer_name, c.customer_code, c.customer_TR_no, c.customer_address  as customer_address,
                           b.branch_name, b.branch_code,
                           e.enquiry_code, e.enquiry_date');
        $this->db->from('quotation_master qm');
        $this->db->join('customer_master c', 'c.customer_id = qm.enquiry_id', 'left'); // adjust if enquiry has customer_id
        $this->db->join('enquiry_master e', 'e.enquiry_id = qm.enquiry_id', 'left');
        $this->db->join('branch_master b', 'b.branch_id = e.branch_id', 'left');

        $this->db->where('qm.qtn_id', $qtn_id);
        return $this->db->get()->row_array();
    }

    /**
     * Get all Main Headings for a Quotation
     */
    public function get_main_headings($qtn_id) {
        return $this->db->where('qtn_id', $qtn_id)
                        ->order_by('id', 'asc')
                        ->get('quotation_main_heading')
                        ->result_array();
    }

    /**
     * Get Sub Headings under a Main Heading
     */
    public function get_sub_headings($qtn_id, $main_id) {
        return $this->db->where('qtn_id', $qtn_id)
                        ->where('main_heading_id', $main_id)
                        ->order_by('id', 'asc')
                        ->get('quotation_subheading')
                        ->result_array();
    }

    /**
     * Get Products under a Sub Heading
     */
    public function get_products($qtn_id, $sub_id) {
        $this->db->select('qp.*, p.item_name as product_name, u.unit_name');
        $this->db->from('quotation_products qp');
        $this->db->join('item_master p', 'p.item_id  = qp.prd_id', 'left');
        $this->db->join('unit_master u', 'u.unit_id = qp.unit_id', 'left');
        $this->db->where('qp.qtn_id', $qtn_id);
        $this->db->where('qp.sub_heading_id', $sub_id);
        $this->db->order_by('qp.id', 'asc');
        return $this->db->get()->result_array();
    }

    /**
     * Get all products (flat list, without grouping)
     */
    public function get_all_products($qtn_id) {
        $this->db->select('qp.*, p.item_name, u.unit_name');
        $this->db->from('quotation_products qp');
        $this->db->join('item_master p', 'p.item_id  = qp.prd_id', 'left');
        $this->db->join('unit_master u', 'u.unit_id = qp.unit_id', 'left');
        $this->db->where('qp.qtn_id', $qtn_id);
        return $this->db->get()->result_array();
    }

    public function generate_so_code() {
    // get the latest SO code
    $this->db->select('so_code');
    $this->db->from('sales_order_master');
    $this->db->order_by('so_id', 'DESC'); // assuming 'id' is your PK
    $this->db->limit(1);
    $query = $this->db->get();

    $year = date('Y'); // current year
    $prefix = "SO{$year}-"; // prefix format

    if ($query->num_rows() > 0) {
        $last_code = $query->row()->so_code;
        // Extract the number part
        $last_number = (int)substr($last_code, -4);
        $new_number = str_pad($last_number + 1, 4, '0', STR_PAD_LEFT);
    } else {
        $new_number = "0001";
    }

    return $prefix . $new_number;
}
public function update_invoice_status($data, $invoice_id) {
    $this->db->where('invoice_id', $invoice_id);
    $this->db->update('invoice_master', $data);

    return $this->db->affected_rows() > 0;
}
public function get_invoice_details($invoice_id) {
    return $this->db->where('invoice_id', $invoice_id)
                    ->get('invoice_master')
                    ->row_array();
}

public function get_invoice_products($invoice_id) {
    $this->db->select('ip.*, im.item_name, im.item_code'); // Select columns from both tables
    $this->db->from('invoice_product ip');               // Main table
    $this->db->join('item_master im', 'ip.product_id = im.item_id', 'left'); // Join item_master
    $this->db->where('ip.invoice_id', $invoice_id);     // Filter by invoice
    return $this->db->get()->result_array();            // Return as array
}

public function insert_stock_entry($data) {
    return $this->db->insert('stock_details', $data);
}
public function get_invoice_complete_details($invoice_id) {
    $select = '
        i.*,
        d.del_id, d.delivery_code, d.delivery_date, d.deliverd_by, d.delivery_mode,
        so.so_id, so.so_code, so.so_date, so.so_revision,
        q.qtn_id, q.quotation_code, q.quotation_date,
        e.enquiry_id, e.branch_id, e.enquiry_customer, e.project_name, e.project_subject, e.project_location,
        c.customer_id, c.customer_name, c.customer_TR_no
    ';

    $this->db->select($select);
    $this->db->from('invoice_master i');
    $this->db->join('delivery_master d',      'd.del_id = i.delivery_id', 'left');
    $this->db->join('sales_order_master so', 'so.so_id = i.so_id',       'left');
    $this->db->join('quotation_master q',    'q.qtn_id = i.quotation_id',      'left');
    $this->db->join('enquiry_master e',      'e.enquiry_id = i.enquiry_id','left');
    $this->db->join('customer_master c',     'c.customer_id = e.enquiry_customer','left');
    $this->db->where('i.invoice_id', $invoice_id);

    return $this->db->get()->row_array();
}
public function get_invoice_address_details($invoice_id)
{
    $select = '
        so.billing_customer_address, 
        so.billing_emirates, 
        so.shipping_address, 
        so.shipping_emirate
    ';

    $this->db->select($select);
    $this->db->from('invoice_master i');
    $this->db->join('sales_order_addres so', 'so.so_id = i.so_id', 'left');
    $this->db->where('i.invoice_id', $invoice_id);

    return $this->db->get()->row_array();
}
 public function saveSalesReturn($invoice_id, $data) {
    $this->db->trans_start(); // Start transaction

    // 1. Insert master
    $master = [
        'invoice_id'            => $invoice_id,
        'sales_return_date'     => date('Y-m-d'),
        'sub_total'             => $data['sub_total'],
        'discount_percentage'   => $data['discount_per'],
        'discount_amount'       => $data['discount_amt'],
        'vat_percentage'        => $data['vat_per'],
        'vat_amount'            => $data['vat_amount'],
        'grand_total'           => $data['grand_total'],
        'created_on'            => date('Y-m-d H:i:s'),
        'created_by'            => $this->session->userdata('user_id')
    ];
    $this->db->insert('sales_return_master', $master);
    $sales_return_id = $this->db->insert_id();

    // 2. Loop through products
    foreach($data['return_quantity'] as $product_id => $qty) {
        $unit_price     = $data['unit_price'][$product_id]; // pass from hidden field
        $total_amount   = $unit_price * $qty;
        $condition      = $data['product_condition'][$product_id];
        $reason         = $data['return_reason'][$product_id];
        $remark         = $data['remark'][$product_id];

        // 2a. Insert into sales_return_products
        $productData = [
            'sales_return_id'   => $sales_return_id,
            'product_id'        => $product_id,
            'unit_price'        => $unit_price,
            'return_quantity'   => $qty,
            'total_amount'      => $total_amount,
            'reason'            => $reason,
            'product_condition' => $condition,
            'remark'            => $remark
        ];
        $this->db->insert('sales_return_products', $productData);

        // 2b. Insert into stock_details if GOOD
        if($condition != 'Damaged') {
            $stock = [
                'stock_type'    => 'IN',
                'trans_id'      => $sales_return_id,
                'stock_date'    => date('Y-m-d'),
                'year'          => date('Y'),
                'product_id'    => $product_id,
                'item_desc'     => $remark,
                'quantity'      => $qty,
                'price'         => $unit_price,
                'stock_value'   => $qty * $unit_price,
                'remark'        => 'Sales return',
                'item_remark'   => $remark,
                'created_by'    => $this->session->userdata('user_id'),
                'created_date'  => date('Y-m-d H:i:s'),
                'invoice_id'    => $invoice_id
            ];
            $this->db->insert('stock_details', $stock);
        } else {
            // 2c. Insert into scrap_stock
            $scrap = [
                'product_id'       => $product_id,
                'quantity'         => $qty,
                'unit_price'       => $unit_price,
                'date'             => date('Y-m-d'),
                'remark'           => $reason,
                'transaction_id'   => $sales_return_id
            ];
            $this->db->insert('scrap_stock_details', $scrap);
        }
    }

    // 3. Update invoice_master to mark sales_return as done
    $this->db->where('invoice_id', $invoice_id)
             ->update('invoice_master', [
                 'sales_return' => 1,
                 'updated_on'   => date('Y-m-d H:i:s'),
                 'updated_by'   => $this->session->userdata('user_id')
             ]);

    $this->db->trans_complete(); // Commit transaction

    return $this->db->trans_status();
}
function get_tax_invoice_list()
	{
		$query=$this->db->query("select * from invoice_master  e, customer_master c, enquiry_master i where i.enquiry_customer=c.customer_id and e.enquiry_id=i.enquiry_id and invoice_type in('sales','direct') and fully_delivered=0 order by invoice_date desc");
		return $query->result();
	}
/*function get_debt_invoice_list($id, $account_id)
	{
		$query = $this->db->query("select one.*, two.paid_amt from (select * from invoice_master where invoice_customer=$id and cancelled=0 order by invoice_date desc , invoice_code desc)as one left join(select trans_id, sum(amount)as paid_amt from voucher_transaction where cancel=0 and voucher_type='R' and drcr_type='Cr' and account_id=$account_id group by trans_id )as two on(one.invoice_id=two.trans_id) group by invoice_code ");
		return $query->result();

	}*/
    function get_debt_invoice_list($id, $account_id)
{
    $sql = "
      SELECT one.*, two.paid_amt
      FROM (
        SELECT * 
        FROM invoice_master 
        WHERE invoice_customer = ? 
          AND cancelled = 0
      ) AS one
      LEFT JOIN (
        SELECT trans_id, SUM(amount) AS paid_amt
        FROM voucher_transaction
        WHERE cancel = 0 
          AND voucher_type = 'R' 
          AND drcr_type = 'Cr' 
          AND account_id = ?
        GROUP BY trans_id
      ) AS two
      ON one.invoice_id = two.trans_id
      GROUP BY one.invoice_code
      ORDER BY one.invoice_date DESC, one.invoice_code DESC
    ";

    $query = $this->db->query($sql, array($id, $account_id));
    return $query->result();
}
function get_grn_master_data($id, $account_id)
	{
		$query = $this->db->query("select one.*, two.paid_amt from (select * from purchase_grn_master where supplier_id=$id order by grn_date desc , grn_code desc)as one left join(select trans_id, sum(amount)as paid_amt from voucher_transaction where cancel=0 and voucher_type='P' and drcr_type='Dr' and account_id=$account_id group by trans_id)as two on(one.grn_id=two.trans_id) group by grn_code ");

		return $query->result();
	}

    public function get_tax_detailed($from_date = null, $to_date = null)
{
    $this->db->select('
        i.invoice_id,
        i.invoice_code,
        i.invoice_date,
        i.total_beforevat AS taxable,
        i.vat_amount AS vat,
        c.customer_id,
        c.customer_name AS customer_name,
        c.emirate AS emirate
    ');
    $this->db->from('invoice_master i');
    $this->db->join('customer_master c', 'i.invoice_customer = c.customer_id', 'left');
   // $this->db->where_in('i.invoice_type', ['TI', 'DI']);
    $this->db->where('i.cancelled', 0);

    if (!empty($from_date) && !empty($to_date)) {
        $this->db->where('i.invoice_date >=', $from_date);
        $this->db->where('i.invoice_date <=', $to_date);
    }

    $this->db->order_by('c.emirate', 'ASC');
    $this->db->order_by('c.customer_name', 'ASC');
    $this->db->order_by('i.invoice_date', 'ASC');

    $query = $this->db->get();
    return $query->result();
}
public function get_tax_summary($from_date = null, $to_date = null)
		{
			$this->db->select('
				SUM(total_beforevat) AS taxable, 
				SUM(vat_amount) AS vat
			');
			$this->db->from('invoice_master');
			//$this->db->where_in('inv_type', ['TI', 'DI']);
			$this->db->where('cancelled', 0);

			if (!empty($from_date) && !empty($to_date)) {
				$this->db->where('invoice_date >=', $from_date);
				$this->db->where('invoice_date <=', $to_date);
			}

			$query = $this->db->get();
			return $query->row(); // single summary row
		}


}