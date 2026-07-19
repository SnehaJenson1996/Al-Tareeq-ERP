<?php
class Project_model extends CI_Model
{
    public function insert_project($data)
    {
        $this->db->insert('project_master', $data);
        return $this->db->insert_id();
    }

    public function update_project($project_id, $data)
    {
        $this->db->where('project_id', $project_id);
        return $this->db->update('project_master', $data);
    }

    public function save_project_items($project_id, $items)
    {
        // Delete old items
        $this->db->where('project_id', $project_id)->delete('project_items');
        // Insert new items
        if(!empty($items)) $this->db->insert_batch('project_items', $items);
    }

    public function save_project_technicians($project_id, $techs)
    {
        // Delete old technicians
        $this->db->where('project_id', $project_id)->delete('project_technicians');
        // Insert new technicians
        if(!empty($techs)) $this->db->insert_batch('project_technicians', $techs);
    }
/*
public function get_project_by_id($id)
{
    return $this->db
        ->select('p.*, s.so_code')
        ->from('project_master p')
        ->join('sales_order_master s', 's.so_id = p.so_id', 'left')
        ->where('p.project_id', $id)
        ->get()
        ->row_array();
}
*/
public function get_project_by_id($id)
{
    return $this->db
        ->select('p.*')
        ->from('project_master p')
       // ->join('sales_order_master s', 's.so_id = p.so_id', 'left')
        ->where('p.project_id', $id)
        ->get()
        ->row_array();
}


    public function get_project_items($project_id)
    {
        return $this->db->get_where('project_items', ['project_id' => $project_id])->result_array();
    }


     // Fetch all active employees
    public function get_employees($id="") {
        $this->db->select('employee_id, employee_name');
        $this->db->from('employee_master');
        if($id)
            $this->db->where('designation_id',$id); //technician
        $this->db->order_by('employee_name', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    // Fetch all active designations
    public function get_designations() {
        $this->db->select('id, designation_name');
        $this->db->from('designation_master');
        $this->db->where('status', 'Active'); // only active designations
        $this->db->order_by('designation_name', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

public function get_project_technicians($project_id)
{
    return $this->db
        ->select('pt.*, em.employee_id, em.employee_name, dm.id as designation_id, dm.designation_name')
        ->from('project_technicians pt')
        ->join('employee_master em', 'em.employee_id = pt.technician_id', 'left')
        ->join('designation_master dm', 'dm.id = pt.designation_id', 'left')
        ->where('pt.project_id', $project_id)
        ->get()
        ->result_array();
}



public function get_all_projects()
{
    $this->db->select('
        p.project_id,
        p.project_code,
        p.project_name,
        p.customer_name,
        p.start_date,
        p.end_date,
        p.duration,
        p.grand_total,
        p.status
    ');
    $this->db->from('project_master p');
    $this->db->order_by('p.project_id', 'DESC');
    return $this->db->get()->result_array();
}

public function get_project_items_list($project_id)
{
     return $this->db
        ->select('pi.*, im.product_name as product_name')
        ->from('project_items pi')
        ->join('item_master im', 'im.product_id = pi.product_id', 'left')
        // ->join('unit_master u', 'u.unit_id = pi.unit_id', 'left') // if you also store unit_id in project_items
        ->where('pi.project_id', $project_id)
        ->get()
        ->result_array();
}



public function delete_project($project_id)
{
    // Optional: Delete related items or technicians if needed
    $this->db->where('project_id', $project_id);
    $this->db->delete('project_items');
    $this->db->where('project_id', $project_id);
    $this->db->delete('project_technicians');
    $this->db->where('project_id', $project_id);
    return $this->db->delete('project_master'); // Replace 'projects' with your actual table name
}

    
// TECHNICIAN AVAILABILITY
public function get_technician_availability($technician_id, $start_date, $end_date, $exclude_project_id = null)
{
    $this->db->from('project_technicians');
    $this->db->where('technician_id', $technician_id);
    $this->db->where('assignment_start <=', $end_date);
    $this->db->where('assignment_end >=', $start_date);

    if (!empty($exclude_project_id)) {
        $this->db->where('project_id !=', $exclude_project_id);
    }

    return $this->db->count_all_results() > 0 ? 'Not Available' : 'Available';
}


// public function is_technician_available($technician_id, $start_date, $end_date, $current_project_id = null)
// {
//     $this->db->select('*');
//     $this->db->from('project_technicians pt');
//     $this->db->join('project_master pm', 'pm.project_id = pt.project_id');
//     $this->db->where('pt.technician_id', $technician_id);

//     // Exclude the current project (for updates)
//     if ($current_project_id) {
//         $this->db->where('pt.project_id !=', $current_project_id);
//     }

//     // Check overlapping date ranges
//     $this->db->where('(pm.start_date <=', $end_date);
//     $this->db->where('pm.end_date >=', $start_date . ')', false); // false to prevent automatic escaping

//     $query = $this->db->get();

//     if ($query->num_rows() > 0) {
//         return false; // Technician is already booked
//     }
//     return true; // Technician is available
// }


public function is_technician_available($technician_id, $start_date, $end_date, $current_project_id = null)
{
    if (!$technician_id || !$start_date || !$end_date) {
        return true; // assume available if dates missing
    }

    $this->db->select('pt.id');
    $this->db->from('project_technicians pt');
    $this->db->where('pt.technician_id', $technician_id);

    // Exclude current project while editing
    if (!empty($current_project_id)) {
        $this->db->where('pt.project_id !=', $current_project_id);
    }

    // ✅ CORRECT overlap logic (assignment dates)
    $this->db->where('pt.assignment_start <=', $end_date);
    $this->db->where('pt.assignment_end >=', $start_date);

    $query = $this->db->get();

    // If any row exists → overlapping → NOT available
    return $query->num_rows() === 0;
}


public function get_technician_name($technician_id)
{
    $row = $this->db
        ->select('employee_name')
        ->from('employee_master')
        ->where('employee_id', $technician_id)
        ->get()
        ->row();

    return $row ? $row->employee_name : 'Unknown';
}

public function get_active_users()
{
    return $this->db->select('user_id, user_name')
                    ->from('users')
                    ->where('active', 1)
                    ->get()
                    ->result_array();
}

//------------------MATERIAL REQUEST START---------------------//

// Insert MR master record
public function insert_mr($data)
{
    $this->db->insert('material_requests', $data);
    return $this->db->insert_id();
}

// Update MR (used for MR code)
// public function update_mr($mr_id, $data)
// {
//     $this->db->where('mr_id', $mr_id);
//     $this->db->update('material_requests', $data);
// }

// Save MR items
public function save_items($items)
{
    //$this->db->insert_batch('material_request_items', $items);
    $this->db->insert_batch('project_material_items', $items);

}
public function get_approved_projects()
{
    return $this->db->where('status', 'Approved')->get('project_master')->result_array();
}

public function get_all_mrs()
{
    return $this->db->select('mr.*, p.project_name, p.project_code, u.user_name as initiated_by_name')
                    ->from('material_requests mr')
                    ->join('project_master p', 'mr.project_id = p.project_id', 'left')
                    ->join('users u', 'mr.initiated_by = u.user_id', 'left')
                    ->order_by('mr.mr_id', 'DESC')
                    ->get()
                    ->result_array();
}


public function update_mr($mr_id, $data)
{
    return $this->db->where('mr_id', $mr_id)
                    ->update('material_requests', $data);
}



public function get_mr_by_id($mr_id)
{
    return $this->db->select('mr.*, p.project_name, p.project_code, u.user_name as initiated_by_name')
                    ->from('material_requests mr')
                    ->join('project_master p', 'mr.project_id = p.project_id', 'left')
                    ->join('users u', 'mr.initiated_by = u.user_id', 'left')
                    ->where('mr.mr_id', $mr_id)
                    ->get()
                    ->row_array();
}
/*
public function get_mr_items($mr_id)
{
    return $this->db
        ->select('mi.*, im.item_name as product_name')
        ->from('material_request_items mi')
        ->join('item_master im', 'im.item_id = mi.product_id', 'left')  
        ->where('mi.mr_id', $mr_id)
        ->get()
        ->result_array();
}
*/
public function get_mr_items($mr_id)
{
     return $this->db
        ->select('mi.*, im.product_name as product_name')
        ->from('project_material_items mi')
        ->join('item_master im', 'im.product_id = mi.fk_item_id', 'left')  
        ->where('mi.mr_id', $mr_id)
        ->get()
        ->result_array();
        //echo $this->db->last_query();
        //exit;
}
public function get_project_items_list_mr($project_id)
{
    return $this->db
        ->select('pi.*, im.product_name as product_name, im.unit_id') 
        ->from('project_items pi')
        ->join('item_master im', 'im.product_id = pi.product_id', 'left')
        ->where('pi.project_id', $project_id)
        ->get()
        ->result_array();
}
public function get_all_units()
{
    return $this->db->where('active', 1)->get('unit_master')->result_array();
}

// Delete MR master
public function delete_mr($mr_id)
{
    return $this->db->delete('material_requests', ['mr_id' => $mr_id]);
}

/*
public function delete_mr_items($mr_id)
{
    return $this->db->delete('material_request_items', ['mr_id' => $mr_id]);
}
    */
public function delete_mr_items($mr_id)
{
    return $this->db->delete('project_material_items', ['mr_id' => $mr_id]);
}



////----------------PROJECT PROGRESS START--------------

public function get_projects_with_progress()
{
    return $this->db
        ->select('p.*, pp.progress_percentage, pp.current_status')
        ->from('project_master p')
        ->join('project_progress pp', 'pp.project_id = p.project_id', 'left')
        ->where('p.status', 'Approved')
        ->get()
        ->result_array();
}

public function get_project_progress($project_id)
{
    return $this->db
        ->where('project_id', $project_id)
        ->get('project_progress')
        ->row_array();
}

public function get_project_progress_logs($project_id)
{
    return $this->db
        ->where('project_id', $project_id)
        ->order_by('log_date', 'DESC')
        ->get('project_progress_logs')
        ->result_array();
}

public function save_progress_log($data)
{
    $this->db->insert('project_progress_logs', $data);
}

public function update_project_progress($project_id, $percentage, $current_status)
{
    $exists = $this->db->where('project_id', $project_id)
                       ->get('project_progress')
                       ->row();

    if ($exists) {
        $this->db->where('project_id', $project_id)
                 ->update('project_progress', [
                     'progress_percentage' => $percentage,
                     'current_status'     => $current_status,
                     'last_updated' => date('Y-m-d H:i:s')
                 ]);
    } else {
        $this->db->insert('project_progress', [
            'project_id' => $project_id,
            'progress_percentage' => $percentage
        ]);
    }
}

// --------------------MATERIAL ISSUE----------------

// Get all pending material requests
public function get_pending_material_requests()
{
    $this->db->select('mr.*, p.project_name');
    $this->db->from('material_requests mr');
    $this->db->join('project_master p', 'p.project_id = mr.project_id', 'left');
    $this->db->where('mr.status', 'Pending'); // Only pending MRs
    $this->db->order_by('mr.requested_date', 'DESC');
    $query = $this->db->get();
    return $query->result_array();
}
public function get_total_issued_qty($mr_id, $product_id)
{
    $total_issued = (float) $this->db
        ->select_sum('issued_qty', 'total_issued')
        ->from('material_issue_items mii')
        ->join('material_issue mi', 'mi.mi_id = mii.mi_id')
        ->where('mi.mr_id', $mr_id)
        ->where('mii.product_id', $product_id)
        ->where('mi.status', 'Issued')
        ->get()
        ->row()->total_issued ?? 0;

    return $total_issued;
}

//NOTIFICATION

 public function get_overdue_projects()
    {
        $this->db->select("
            pm.project_id,
            pm.project_code,
            pm.project_name,
            pm.end_date,
            pp.current_status,
            DATEDIFF(CURDATE(), pm.end_date) AS overdue_days
        ");
        $this->db->from('project_master pm');
        $this->db->join('project_progress pp', 'pp.project_id = pm.project_id');
        $this->db->where('pm.end_date <', date('Y-m-d'));
        $this->db->where('pp.current_status !=', 'Completed');

        return $this->db->get()->result();
    }

        // SO IF ALREADY CREATED PROJECT
    public function is_project_created_for_so($so_id)
{
    $this->db->select('project_id');
    $this->db->from('project_master');
    $this->db->where('so_id', $so_id);
    $result = $this->db->get()->row();

    return !empty($result);
}

//PROJECT PROGRESS LOG
public function get_last_progress_log($project_id)
{
    return $this->db
        ->where('project_id', $project_id)
        ->order_by('created_at', 'DESC')
        ->limit(1)
        ->get('project_progress_logs')
        ->row_array();
}
    //Enquiry
    
    public function get_enquiries(){
        return $this->db
            ->select('e.enquiry_id,e.enquiry_code, c.customer_name')
            ->from('enquiry_master e')
            ->join('customer_master c', 'e.enquiry_customer = c.customer_id', 'left')
            ->where('e.active', 1)
            ->get()
            ->result_array();
    }

    public function getQuotationByEnquiry($eid){
        return $this->db
            ->select('qtn_id,quotation_code')
            ->where('enquiry_id', $eid)
            ->order_by('created_on', 'DESC')
            ->get('quotation_master')
            ->result_array();

    }

    public function getProjectDetailsByEnquiry($eid){
        return $this->db
            ->select('e.project_name,e.project_location')
            ->where('enquiry_id', $eid)
            ->get('enquiry_master e')
            ->row_array();
    }

    public function getBranchDetailsByQuotation($qid){
        return $this->db
            ->select('b.branch_name')
            ->from('branch_master b')
            ->join('quotation_master q', 'q.quotation_branch_id = b.branch_id', 'left')
            ->where('q.qtn_id',$qid)
            ->get()
            ->row_array();
    }

     public function getcustomerDetailsByQuotation($qid){
        return $this->db
            ->select('c.customer_name')
            ->from('customer_master c')
            ->join('quotation_master q', 'q.quotation_customer = c.customer_id', 'left')
            ->where('q.qtn_id',$qid)
            ->get()
            ->row_array();
    }
    public function get_all_products_by_quotation($q_id) {
   
        $this->db->select('qp.qty,qp.prd_id, qp.unit_price, im.product_name, u.unit_name');
        $this->db->from('quotation_products qp');
        $this->db->join('item_master im', 'im.product_id = qp.prd_id', 'left');
        $this->db->join('unit_master u', 'u.unit_id = qp.unit_id', 'left');
        $this->db->where('qp.qtn_id', $q_id);
        return $this->db->get()->result_array();
    }

    public function get_project_items_list_quotation($project_id){

        return $this->db
            ->select('pi.*, im.project_name as product_name, im.item_unit') 
            ->from('project_items pi')
            ->join('item_master im', 'im.product_id = pi.product_id', 'left')
            ->where('pi.project_id', $project_id)
            ->get()
            ->result_array();
    }

    public function get_all_items(){
        $where = array('is_inactive'=>0,'is_marked_delete'=>'0');
        return $this->db->select('product_id, product_name,product_code')
          ->from('item_master')
          ->where($where)
          ->get()->result_array();
          
    }

    public function get_projectRawmaterials($id){
        if($id){
           
        $sql =  "SELECT pj.product_id,pj.quantity,im.product_code,im.product_name,im.product_id as it_id FROM project_items pj
                LEFT JOIN item_master im
                ON im.product_id = pj.product_id 
                 where pj.project_id=?
            ";
        $query = $this->db->query($sql, array($id));
        $result = $query->result_array();
        $item_array = [];
        foreach($result as $k=>$res){
            $this->load->model('Setup_model');
            $rid = $res['it_id'];
            $item_array[$k]['item_id']  =  $res['it_id'];
            $item_array[$k]['pname']    =  $res['product_name']."(".$res['product_code'].")";
            $item_array[$k]['quantity'] =  $res['quantity'];
            $item_array[$k]['quantity'] =  $res['quantity'];
            $rows = $this->Setup_model->get_rawmaterials($rid);
            $item_array[$k]['raw'] = $rows;
        
        }
            return $item_array;
        }else
            return false;
    }

    //get tasks
    public function get_tasks(){
        return $this->db
            ->select('t.project_task_id,t.project_task_name')
            ->from('project_task_category t')
            ->where('t.bit_active', 1)
            ->get()
            ->result_array();
    }
    //get milestone
    public function get_milestones(){
        return $this->db
            ->select('m.milestone_id,m.milestone_name')
            ->from('project_milestone m')
            ->where('m.bit_active', 1)
            ->get()
            ->result_array();
    }
    //get designations
    public function getDesignation(){
        return $this->db
        ->select('designation_id, designation_name')
        ->where('bit_active',1)
        ->order_by('designation_name','ASC')
        ->get('designation_master')
        ->result_array();
    }
    //save project task
    public function save_project_task($data){
        $this->db->insert('project_task', $data);
        return $this->db->insert_id();
    }
    //update project task
    public function update_project_task($project_task_id, $data){
        $this->db->where('id', $project_task_id);
        return $this->db->update('project_task', $data);
    }
    //insert project task
    public function insert_project_task_items($items){
        if (!empty($items)) {
            return $data =$this->db->insert_batch('project_task_items', $items);
        }
    }
    //update project task
    public function save_project_task_items($project_task_id, $items){

        $this->db->where('project_task_id', $project_task_id);
        $this->db->delete('project_task_items');

        if (!empty($items)) {
            $this->db->insert_batch('project_task_items', $items);
        }
    }
    public function get_project_task($project_id)
    {
        return $this->db
            ->where('project_id',$project_id)
            ->get('project_task')
            ->row_array();
    }

    public function get_project_task_items($project_task_id)
    {
        return $this->db
            ->where('project_task_id',$project_task_id)
            ->get('project_task_items')
            ->result_array(); 
    }

    //get project's tasks
    public function get_projects_tasks($project_id){
        $query = $this->db->select('pt.id, pt.remarks, u.user_name')
            ->from('project_task pt')
            ->join('users u', 'u.user_id = pt.approved_by','left')
            ->where('pt.project_id', $project_id)
            ->get();
        $final = [];
        if ($query->num_rows() > 0) {
            $result = $query->row_array();   
            $final['remark'] = $result['remarks'] ?? '';
            $final['approved'] = $result['approved_by'] ?? '';
            $query = $this->db->query("SELECT  pti.id,pti.task_name,`em`.`employee_id`,pti.priority,pti.start_date,pti.end_date,pti.status,pti.task_description, `pm`.`milestone_name`, `dm`.`designation_name`, `em`.`employee_name`
                    FROM `project_task` pt
                    LEFT JOIN `project_task_items` `pti` ON `pti`.`project_task_id` = `pt`.`id`
                    LEFT JOIN `project_milestone` `pm` ON `pm`.`milestone_id` = `pti`.`milestone_id`
                    LEFT JOIN `designation_master` `dm` ON `dm`.`id` = `pti`.`designation_id`
                    LEFT JOIN `employee_master` `em` ON `em`.`employee_id` = `pti`.`employee_id`
                    LEFT JOIN  project_task_category pc on pc.project_task_id=pti.task_category_id
                    WHERE `pt`.`project_id`=$project_id");
            $result_tasks = $query->result_array();
            $final['tasks'] = $result_tasks;
            return $final;

        }else{
            return $final;
        }
    }

    //get project name
    public function get_projectName($id){
        return $this->db
            ->select('p.project_code,p.project_name')
            ->from('project_master p')
            ->where('p.project_id', $id)
            ->get()
            ->result_array();
    }
    //delete task item
    public function delete_taskitem($item_id)
    {
        return $this->db->delete('project_task_items', ['id' => $item_id]);
    }
    /****
     * Resource Management
     */

    public function get_all_machines()
    {
        return $this->db->get('machine_master')->result();
    }

    public function add_machine_data()
    {
        $machine_name = trim($this->input->post('machine_name'));

        // CHECK DUPLICATE
        $exists = $this->db->where('machine_name', $machine_name)
                        ->get('machine_master')
                        ->row();

        if (!empty($exists)) {

            $this->session->set_flashdata('error', 'Machine name already exists!');
            redirect('Project/list_machines');
            return;
        }

        // AUTO CODE GENERATION
        $last = $this->db->select('machine_code')
                        ->from('machine_master')
                        ->order_by('machine_id', 'DESC')
                        ->get()
                        ->row();

        if (!empty($last->machine_code)) {
            $num = (int) substr($last->machine_code, 3);
            $num++;
            $code = 'MA-' . str_pad($num, 4, '0', STR_PAD_LEFT);
        } else {
            $code = 'MA-0001';
        }

        // INSERT
        $data = array(
            'machine_code' => $code,
            'machine_name' => $machine_name,
            'description'     => $this->input->post('description'),
            'created_at'      => date('Y-m-d H:i:s')
        );

        $this->db->insert('machine_master', $data);

        $this->session->set_flashdata('success', 'Machine Added Successfully');
        redirect('Project/list_machines');
    }

    //Tools
    public function get_all_tools()
    {
        return $this->db->get('tool_master')->result();
    }

    public function add_tool_data()
    {
        $tool_name = trim($this->input->post('tool_name'));

        // CHECK DUPLICATE
        $exists = $this->db->where('tool_name', $tool_name)
                        ->get('tool_master')
                        ->row();

        if (!empty($exists)) {

            $this->session->set_flashdata('error', 'Tool name already exists!');
            redirect('Project/list_tools');
            return;
        }

        // AUTO CODE GENERATION
        $last = $this->db->select('tool_code')
                        ->from('tool_master')
                        ->order_by('tool_id', 'DESC')
                        ->get()
                        ->row();

        if (!empty($last->tool_code)) {
            $num = (int) substr($last->tool_code, 3);
            $num++;
            $code = 'TL-' . str_pad($num, 4, '0', STR_PAD_LEFT);
        } else {
            $code = 'TL-0001';
        }

        // INSERT
        $data = array(
            'tool_code' => $code,
            'tool_name' => $tool_name,
            'description'     => $this->input->post('description'),
            'created_at'      => date('Y-m-d H:i:s')
        );

        $this->db->insert('tool_master', $data);

        $this->session->set_flashdata('success', 'Tool Added Successfully');
        redirect('Project/list_tools');
    }

    //get machines

    public function get_machines(){

      return $res = $this->db->select('machine_id,machine_name')
            ->from('machine_master')
            ->where('active','1')
            ->order_by('machine_id', 'DESC')
            ->get()
            ->result_array();    
    }
    //Machine operator mapping
    public function add_mom_data(){
        $machine_id     = $this->input->post('machine_id');
        $employee_id    = $this->input->post('employee_id');
        $skill_level    = $this->input->post('skill_level');
        $bit_active     = $this->input->post('bit_active');
        $remarks        = $this->input->post('description');
        $code           = $this->input->post('map_code');

        $data = array(
            'machine_id' => $code,
            'machine_id' => $machine_id,
            'employee_id' => $employee_id,
            'skill_level' => $skill_level,
            'bit_active'  => $bit_active,
            'remarks'     => $remarks,
            'map_code'    => $code,
            'created_date'  => date('Y-m-d H:i:s')
        );

        $this->db->insert('machine_operator_mapping', $data);
        $this->session->set_flashdata('success', 'Machine Operator Mapping Added Successfully');
        redirect('Project/list_machineop_map');

    }

    public function get_machine_operator($id)
    {
        return $this->db
                ->where('mapping_id',$id)
                ->get('machine_operator_mapping')
                ->row_array();
    }
    public function update_mom_data($id)
    {
        $data = array(
            'machine_id'   => $this->input->post('machine_id'),
            'employee_id'  => $this->input->post('employee_id'),
            'skill_level'  => $this->input->post('skill_level'),
            'remarks'      => $this->input->post('description'),
            'bit_active'   => $this->input->post('bit_active'),
            'updated_at'   => date('Y-m-d H:i:s')
        );

        $this->db->where('mapping_id',$id);

        return $this->db->update('machine_operator_mapping',$data);
    }

    public function get_all_employyee_mapping(){
        $query = $this->db->select('m.mapping_id, m.map_code,e.employee_name, mc.machine_name,m.skill_level,m.bit_active,m.remarks')
            ->from('machine_operator_mapping m')
            ->join('employee_master e', 'e.employee_id = m.employee_id','left')
            ->join('machine_master mc', 'mc.machine_id = m.machine_id','left')
            //->where('pt.project_id', $project_id)
            ->get();
            
        $final = [];
        if ($query->num_rows() > 0) {
            return $final = $query->result();  
        }else{
            return $final;
        }
    }

    /***
     * Resource Planning
    ***/
    public function get_all_resource_planning($id){
        $this->db->select('
            pmr.resource_id,
            pmr.resource_code,
            pm.project_name,
            mm.machine_name,
            em.employee_name,
            mom.skill_level,
            pmr.operation_name,
            pmr.hours_needed,
            pmr.start_date,
            pmr.end_date,
            pmr.status,
            pmr.remarks
        ');

        $this->db->from('project_machine_resource pmr');
        $this->db->join('project_master pm', 'pm.project_id = pmr.project_id', 'left');
        $this->db->join('machine_operator_mapping mom', 'mom.mapping_id = pmr.mapping_id', 'left');
        $this->db->join('machine_master mm', 'mm.machine_id = mom.machine_id', 'left');
        $this->db->join('employee_master em', 'em.employee_id = mom.employee_id', 'left');
        $this->db->where('pmr.project_id', $id);
        $query = $this->db->get();
        $final = [];
        if ($query->num_rows() > 0) {
            return $final = $query->result();  
        }else{
            return $final;
        }
    }
    
    public function add_machine_resource()
    {
        $project_id     = $this->input->post('project_id');
        //$machine_id     = $this->input->post('machine_id');
        //$employee_id    = $this->input->post('employee_id');
        $map_id         = $this->input->post('machine_id');
        $operation_name = $this->input->post('operation_name');
        $hours_needed   = $this->input->post('hours_needed');
        $start_date     = date('Y-m-d', strtotime($this->input->post('start_date')));
        $end_date       = date('Y-m-d', strtotime($this->input->post('end_date')));
        $duration       = (int) $this->input->post('duration');
        $status         = $this->input->post('status');
        $remarks        = $this->input->post('description');
        //$resource_code  = $this->input->post('resource_code');
        $last = $this->db->select('resource_code')
                 ->from('project_machine_resource')
                 ->order_by('resource_id', 'DESC')
                 ->limit(1)
                 ->get()
                 ->row();

            if ($last && !empty($last->resource_code)) {

                $num = (int) substr($last->resource_code, 4); // Skip "MRP-"
                $num++;

                $resource_code = 'MRP-' . str_pad($num, 4, '0', STR_PAD_LEFT);

            } else {

                $resource_code = 'MRP-0001';

            }

        $data = array(
            'project_id'     => $project_id,
            //'machine_id'     => $machine_id,
            //'employee_id'    => $employee_id,
            'mapping_id'         => $map_id,
            'operation_name' => $operation_name,
            'hours_needed'   => $hours_needed,
            'start_date'     => $start_date,
            'end_date'       => $end_date,
            'status'         => $status,
            'remarks'        => $remarks,
            'resource_code'  => $resource_code,
            'created_at'     => date('Y-m-d H:i:s')
        );

        if ($this->db->field_exists('duration', 'project_machine_resource')) {
            $data['duration'] = $duration;
        }

        $this->db->insert('project_machine_resource', $data);

        $this->session->set_flashdata('success', 'Machine Resource Added Successfully');

        redirect('Project/list_resource_planning/' . $project_id);
    }

    public function get_machine_resource($id)
    {
        return $this->db
                ->where('resource_id', $id)
                ->get('project_machine_resource')
                ->row_array();
    }

    public function update_machine_resource($id)
    {
        $data = array(
            'project_id'     => $this->input->post('project_id'),
            'machine_id'     => $this->input->post('machine_id'),
            'employee_id'    => $this->input->post('employee_id'),
            'operation_name' => $this->input->post('operation_name'),
            'hours_needed'   => $this->input->post('hours_needed'),
            'start_date'     => date('Y-m-d', strtotime($this->input->post('start_date'))),
            'end_date'       => date('Y-m-d', strtotime($this->input->post('end_date'))),
            'status'         => $this->input->post('status'),
            'remarks'        => $this->input->post('description'),
            'updated_at'     => date('Y-m-d H:i:s')
        );

        if ($this->db->field_exists('duration', 'project_machine_resource')) {
            $data['duration'] = (int) $this->input->post('duration');
        }

        $this->db->where('resource_id', $id);
        return $this->db->update('project_machine_resource', $data);
    }

    public function get_machine_operator_mapping()
    {
        return $res = $this->db
            ->select('
                m.mapping_id,
                m.machine_id,
                m.employee_id,
                m.skill_level,
                mc.machine_name,
                e.employee_name
            ')
            ->from('machine_operator_mapping m')
            ->join('machine_master mc', 'mc.machine_id = m.machine_id')
            ->join('employee_master e', 'e.employee_id = m.employee_id')
            ->where('m.bit_active', 1)
            ->order_by('mc.machine_name')
            ->get()
            ->result_array();
    }
    //delete 
    public function delete_machine_resource($pid,$id)
    {
        return $this->db->delete('project_machine_resource', ['resource_id' => $id]);
    }

}
