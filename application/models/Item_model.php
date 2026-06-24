<?php
class Item_model extends CI_Model {

    public function __construct()
    {
        
    }

    //brand master
	


    public function insert_unit($data)
        {
            $this->db->insert('unit_master', $data);  // Replace 'units' with your actual table name
            return $this->db->insert_id();
        }

    public function get_all_units(){
		$this->db->select('*');
        $this->db->from('unit_master');
        $query = $this->db->get()->result();
        return $query; 
	}
    public function get_unit_by_id($unit_id){
		$this->db->select('*');
        $this->db->from('unit_master');
        $this->db->where('unit_id ',$unit_id);
        $query = $this->db->get()->result();
        return $query; 
	}
    public function update_unit($data,$unit_id){        
        $this->db->where('unit_id',$unit_id);
        $res = $this->db->update('unit_master',$data);
        return $res;
    }
    public function delete_unit($unit_id){        
        $this->db->where('unit_id',$unit_id);
        $res = $this->db->delete('unit_master');
        return $res;
    }
    //////////Item
     public function insert_item($data)
    {
        $this->db->insert('item_master', $data);  // Replace 'units' with your actual table name
        return $this->db->insert_id();
    }
    //brand master
    public function add_brand_data(){
        $data=array(
            'brand_name' => $_POST['brand_name'],
            'discount_limit' => $_POST['discount_limit'],
           
        );
        $res = $this->db->insert('brand_master',$data);
        return $res;
    }

    function get_active_brand_list()
    {
        $this->db->select('*');
        $this->db->from('brand_master');
        $this->db->where('active',1);
        $query = $this->db->get()->result();
        return $query;      
    }

	public function get_all_brand_list(){
		$this->db->select('*');
        $this->db->from('brand_master');
        $query = $this->db->get()->result();
        return $query; 
	}

    public function get_brand_id_by_name($brand_name){
        $this->db->select('brand_id');
        $this->db->from('brand_master');
        $this->db->where('brand_name',$brand_name);
        $result = $this->db->get()->row('brand_id');
        return $result;
    }

    public function get_brand_by_id($brand_id){

        $this->db->select('*');
        $this->db->from('brand_master bm');
        $this->db->where('bm.brand_id',$brand_id);
        $query = $this->db->get()->row_array();
    
        return $query;
    }

    public function update_brand_data(){
        $data=array(
            'brand_name'=>$_POST['brand_name'],
            'discount_limit' => $_POST['discount_limit'],
        );
        $this->db->where('brand_id',$_POST['brand_id']);
        $res = $this->db->update('brand_master',$data);
        return $res;
    }

    

	// public function get_user_by_id($user_id){
	// 	$this->db->select('*');
    //     $this->db->from('users');
	// 	$this->db->where('user_id',$user_id);
    //     $query = $this->db->get()->row_array();
    //     return $query;
	// }

	// public function edit_user_data(){
	// 	$data=array(
	// 		'user_name'=>$_POST['user_name'],
	// 		'user_login'=>$_POST['user_login'],
	// 		'gender'=>$_POST['gender'],
	// 		'dob'=>$_POST['dob'],
	// 	);
	// 	$this->db->where('user_id',$_POST['user_id']);
	// 	$res = $this->db->update('users',$data);
	// 	return $res;
	// }

    //item master
public function get_all_item_list(){
        $this->db->select('im.*,um.unit_name, um.unit_id');
        $this->db->from('item_master im');
        $this->db->join('unit_master um','im.item_unit=um.unit_id');
        $query = $this->db->get()->result();
        return $query; 
}

public function get_active_item_list()
{
       $this->db->select('*');
       $this->db->from('item_master im');
       $this->db->join('brand_master bm','im.item_brand=bm.brand_id');
       $this->db->where('im.active',1);
       $query = $this->db->get()->result();
       return $query;      
}

public  function check_item_code_duplicate(){
        $item_code = $this->input->post('item_code');
        $this->db->where('item_code', $item_code);
        $query = $this->db->get('item_master');

        if ($query->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
}

public function add_item_data(){
    $data=array(
        'item_brand'=>$_POST['item_brand'],
        'item_code'=>$_POST['item_code'],
        'item_model'=>$_POST['item_model'],
        'item_description'=>$_POST['item_description'],
        'item_unit'=>$_POST['item_unit'],
        'mrp_qar'=>$_POST['mrp_qar'],
        'mrp_aed'=>$_POST['mrp_aed'],
       
    );
    $res = $this->db->insert('item_master',$data);
    return $res;
}
public function get_item_by_id($item_id){
    $this->db->select('im.*, bm.brand_name');
    $this->db->from('item_master im');
    $this->db->join('brand_master bm', 'bm.brand_id = im.item_brand', 'left'); // use left join if some items may not have a brand
    $this->db->where('im.item_id', $item_id);
    
    return $this->db->get()->row_array();
}
public function update_item($item_id, $data)
{
    $this->db->where('item_id', $item_id);
    return $this->db->update('item_master', $data);
}
/*public function get_item_by_id($item_id){

    $this->db->select('im.item_id,im.item_code,im.item_brand,im.item_model,im.item_description,im.item_unit,im.c_o_o,im.hs_code,im.mrp_aed,bm.brand_name,bm.discount_limit,um.unit_name,COALESCE(sum(sd.quantity),0) as stock');
    $this->db->from('item_master im');
    $this->db->join(
    'stock_details sd',
     "im.item_id = sd.product_id AND sd.status = 0 AND sd.inv_type = 'Actual Stock'",
    'left'
    );
    $this->db->join('brand_master bm','im.item_brand=bm.brand_id','left');
    $this->db->join('unit_master um','im.item_unit=um.unit_id','left');
    $this->db->where('im.item_id',$item_id);
    $this->db->group_by('im.item_id');
    $query = $this->db->get()->row_array();
    return $query;
}*/

    function import_item_master($item_master){
        $file_handle ='';
        if(isset($item_master)){
            $file_handle = fopen($item_master, "r");
            if ($file_handle !== false) {
                    
                while (($values = fgetcsv($file_handle)) !== false) { 
                        
                    if($values[0] == '1'){
                        //$brand_id = $this->get_brand_id_by_name($values[1]);
                        $unit_id = $this->get_unit_id_by_name($values[5]);
                        // $height = $values[9];
                        // preg_match('/([\d.]+)\s*mm\s*\/\s*([\d.]+)\s*inches/', $height, $matches);

                        // $height_mm = isset($matches[1]) ? $matches[1] : null;
                        // $height_inch = isset($matches[2]) ? $matches[2] : null;
                        // // Convert mm to cm
                        // $height_cm = $height_mm !== null ? $height_mm / 10 : null;
                        // $width = $values[10];
                        // preg_match('/([\d.]+)\s*mm\s*\/\s*([\d.]+)\s*inches/', $width, $matches);

                        // $width_mm = isset($matches[1]) ? $matches[1] : null;
                        // $width_inch = isset($matches[2]) ? $matches[2] : null;
                        // // Convert mm to cm
                        // $width_cm = $width_mm !== null ? $width_mm / 10 : null;
                        // $depth = $values[11];
                        // preg_match('/([\d.]+)\s*mm\s*\/\s*([\d.]+)\s*inches/', $depth, $matches);

                        // $depth_mm = isset($matches[1]) ? $matches[1] : null;
                        // $depth_inch = isset($matches[2]) ? $matches[2] : null;
                        // // Convert mm to cm
                        // $depth_cm = $depth_mm !== null ? $depth_mm / 10 : null;
                        // $weight = $values[12];
                        // preg_match('/([\d.]+)\s*kg\s*\/\s*([\d.]+)\s*lbs/', $weight, $matches);

                        // $weight_kg = isset($matches[1]) ? $matches[1] : null;
                        // $weight_lbs = isset($matches[2]) ? $matches[2] : null;
                        
                        $data = array(
                            'item_code' => $values[2],
                            'item_brand' => $values[1],
                            'item_model'  => $values[3],
                            //'model_group'  => 'spare parts',
                            'item_description' => $values[4],
                            'item_unit' => $unit_id,
                            'c_o_o' => $values[6],
                            'hs_code'=> $values[7],
                            'package_height_cm' => $values[9]??0,
                            'package_height_inch' => $values[8]??0,
                            'package_width_cm' => $values[13]??0,
                            'package_width_inch' =>$values[12]??0,
                            //'package_depth_cm' => $values[11]??0,
                            //'package_depth_inch' => 0,
                            //'package_weight_kg' => $weight_kg??0,
                            //'package_weight_lbs' => $weight_lbs??0,
                            'gross_weight' => $values[14]??0,
                            //'net_weight' => $values[13]??0,
                            'mrp_qar'  => $values[15],
                            'mrp_aed'  => $values[16],					
                            );
                        
                            $this->db->insert('item_master', $data);
                        
                    }
                    
                        
                }
                fclose($file_handle);
            }
                
        }
    }

    function import_item_master2($item_master){
        $file_handle ='';$count=0;
        if(isset($item_master)){
            $file_handle = fopen($item_master, "r");
            if ($file_handle !== false) {
                    
                while (($values = fgetcsv($file_handle)) !== false) { 
                        
                    if($values[0] == '1'){
                        
                        $item_code = trim($values[1]);
                        $qar_price = trim($values[14]);
                        $aed_price = trim($values[15]);
                        
                        
                        $data=array('mrp_qar'=>$qar_price,'mrp_aed'=>$aed_price);
                        $this->db->set($data);
                        $this->db->where('item_code',$item_code);
                        $this->db->where('item_brand','7');
                        // $res = $this->db->update('item_master');
                        // if($res){
                        //     $count++;
                        // }
                        $sql = $this->db->get_compiled_update('item_master',$data);
                        echo $sql;
                        break;
                    }
                    // else    
                    //     echo $values[0];
                    
                    
                        
                }
                fclose($file_handle);
            }
                
        }
        return $count;
    }

    public function search_items($term)
    {
        if($term != ''){
            $this->db->like('item_model', $term);
        }
        $this->db->limit(20); // limit results to avoid too much data
        $query = $this->db->get('item_master');
        return $query->result_array();
        
    }

   //unit master
   
   public function get_active_unit_list()
   {
       $this->db->select('*');
       $this->db->from('unit_master');
       $this->db->where('active',1);
       $query = $this->db->get()->result();
       return $query;      
   }

   public function get_unit_id_by_name($unit_name){
    $this->db->select('unit_id');
    $this->db->from('unit_master');
    $this->db->where('unit_name',$unit_name);
    $result = $this->db->get()->row('unit_id');
    return $result;
}

//brand master

public function get_active_brands()
{
    $this->db->where('active', 1);
    return $this->db->get('brand_master')->result();
}

}