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


    public function get_project_items($project_id)
    {
        return $this->db->get_where('project_items', ['project_id' => $project_id])->result_array();
    }


     // Fetch all active employees
    public function get_employees() {
        $this->db->select('employee_id, employee_name');
        $this->db->from('employee_master');
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
        ->select('pi.*, im.item_name as product_name')
        ->from('project_items pi')
        ->join('item_master im', 'im.item_id = pi.product_id', 'left')
        // ->join('unit_master u', 'u.unit_id = pi.unit_id', 'left') // if you also store unit_id in project_items
        ->where('pi.project_id', $project_id)
        ->get()
        ->result_array();
}



public function delete_project($project_id)
{
    // Optional: Delete related items or technicians if needed
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
    $this->db->insert_batch('material_request_items', $items);
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
public function get_project_items_list_mr($project_id)
{
    return $this->db
        ->select('pi.*, im.item_name as product_name, im.item_unit') 
        ->from('project_items pi')
        ->join('item_master im', 'im.item_id = pi.product_id', 'left')
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


public function delete_mr_items($mr_id)
{
    return $this->db->delete('material_request_items', ['mr_id' => $mr_id]);
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

}
