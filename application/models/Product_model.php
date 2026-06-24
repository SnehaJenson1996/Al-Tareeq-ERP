<?php

    class Product_model extends CI_Model {
 
		function add_category_data() 
		{
			$query=$this->db->query("select coalesce(max(category_id),0)+1 as pid from category_master");
			$pid= $query->row('pid');
			
			$data = array(
			'category_name' => $this->input->post('cat_name'),
			'category_type' => $this->input->post('ctype'),
			'parent_id' => $pid,
			'child_id' => 0,
			'created_by' => $this->session->userdata('user_id'),
			'created_date' => date('Y-m-d H:i:s')
			);
			$this->db->insert('category_master', $data);
			$insert_id = $this->db->insert_id();
	
			/*if($insert_id)
			{
				
				if(isset($_POST['sub_atr_name']))
				{
				for ($i = 0; $i < count($_POST['sub_atr_name']); $i++)
					{
						$query=$this->db->query("select coalesce(max(category_id),0)+1 as pid from category_master");
					$pid1= $query->row('pid');
						try{
					  $data = array(
					'category_code' => $_POST['prefix'][$i],
					'category_name' => $_POST['sub_atr_name'][$i],
					'category_type' => $this->input->post('ctype'),
					'parent_id' => $pid1,
					'child_id' => $insert_id,
					'created_by' => $this->session->userdata('user_id'),
					'created_date' => date('Y-m-d H:i:s')
			
					  );
					  $this->db->insert('category_master', $data);
					  }
					  catch(Exception $e){
						  return 'duplicate';
					  }
					}
					}
				$user_se_id=$this->session->userdata('user_id');
				$page_name=explode('index.php/', $_SERVER['PHP_SELF']);
				$ci = get_instance();
				$ci->load->helper('log');
				$log_msg=add_log_entry($user_se_id,1,$page_name[1],'category_master','category_id',$insert_id);
			}*/
			return $insert_id;
		}
			
		   function update_attribute_data($cat_id) 
		{
			$data = array(
			'category_name' => $this->input->post('cat_name'),
			);
			$this->db->where('category_id',$cat_id);
			$res = $this->db->update('category_master', $data);
	
			if($res)
			{
				if(isset($_POST['sub_atr_name']))
				{
				for ($i = 0; $i < count($_POST['sub_atr_name']); $i++)
					{
						$query=$this->db->query("select coalesce(max(category_id),0)+1 as pid from category_master");
					$pid1= $query->row('pid');
					
					  $data = array(
					'category_code' => $_POST['prefix'][$i],
					'category_name' => $_POST['sub_atr_name'][$i],
					'category_type' => $this->input->post('ctype'),
					'parent_id' => $pid1,
					'child_id' => $cat_id,
					'created_by' => $this->session->userdata('user_id'),
					'created_date' => date('Y-m-d H:i:s')
					  );
					  $this->db->insert('category_master', $data);
					}
					}
					if(isset($_POST['sub_atr_name_old']))
				{
					for ($i = 0; $i < count($_POST['sub_atr_name_old']); $i++)
					{
						$trans_id= $_POST['trans_id'][$i];
						  $data = array(
					'category_name' => $_POST['sub_atr_name_old'][$i],
					'category_code' => $_POST['prefix_old'][$i],
						  );
					$this->db->where('category_id',$trans_id);
					$res = $this->db->update('category_master', $data);
					  
					}
					}
				$user_se_id=$this->session->userdata('user_id');
				$page_name=explode('index.php/', $_SERVER['PHP_SELF']);
				$ci = get_instance();
				$ci->load->helper('log');
				$log_msg=add_log_entry($user_se_id,2,$page_name[1],'category_master','category_id',$cat_id);
				return true;
			}
			else
			{
				return false;
			}
		}
		
			
		function get_category_code_by_id($cat_id)
		{
			$query=$this->db->query("select category_code from category_master where category_id=$cat_id");
			return $category_code= $query->row('category_code');
		}
		function get_max_cat_code()
		{
			$query=$this->db->query("select max(category_code)+1 as category_code from category_master");
			return $query->row('category_code');
		}
		function get_main_category_list()
		{
			$query=$this->db->query("select * from category_master where child_id=0 and is_cancelled =0 order by category_type,category_name");
			return $query->result();
		}
	
		function get_category_record_by_id($id)
		{
			$query=$this->db->query("select * from category_master where category_id='$id' ");
			return $query->result();
		}
		function get_attribute_transaction_by_id($id)
		{
			$query=$this->db->query("select * from category_master where child_id='$id' and is_cancelled=0 ");
			return $query->result();
		}
		function get_sub_category_list()
		{
			$query=$this->db->query("select one.*, two.category_name as pname from(select * from category_master where child_id!=0 and is_cancelled =0 order by category_name)as one left join(select * from category_master where child_id=0)as two on(one.child_id=two.parent_id)");
			return $query->result();
		}
		
		function update_sub_category($attr_id) 
		{
			$data = array(
			'category_name' => $this->input->post('attr_name'),
			);
			$this->db->where('category_id',$attr_id);
			$res = $this->db->update('category_master', $data);
	
			if($res)
			{
				$user_se_id=$this->session->userdata('user_id');
				$page_name=explode('index.php/', $_SERVER['PHP_SELF']);
				$ci = get_instance();
				$ci->load->helper('log');
				$log_msg=add_log_entry($user_se_id,2,$page_name[1],'category_master','category_id',$cat_id);
				return true;
			}
		}
	/////////////////  Product master start  ///////////////////
	// function add_product_data()
	// {

	// 	// product _image
	// 	$prd_img1='';
	// 	$prd_img2='';
	// 	$allowedExts = array("jpeg","jpg","png");
	// 	$file_name1=$_FILES["prd_img1"]["name"];
	// 	$file_name2=$_FILES["prd_img2"]["name"];
	// 	$brand_name = $this->input->post('brand_name');

	// 	// Check if brand exists
	// 	$this->db->where('brand_name', $brand_name);
	// 	$existing_brand = $this->db->get('brand_master')->row();

	// 	if ($existing_brand) {
	// 		$brand_id = $existing_brand->brand_id;
	// 	} else {
	// 		$brand_id = $this->add_brand_data($brand_name);
	// 	}

	// 	$data['file_name1']=str_replace(' ', '_', $file_name1);
	// 	$data['file_name2']=str_replace(' ', '_', $file_name2);
	// 	$temp1 = explode(".", $_FILES["prd_img1"]["name"]);
	// 	$temp2 = explode(".", $_FILES["prd_img2"]["name"]);
	// 	$extension1 = end($temp1); 
	// 	$extension2 = end($temp2); 
	// 	if (!empty($file_name1) || !empty($file_name2)){
	// 		if ((($_FILES["prd_img1"]["size"] < 15728640) && in_array($extension1, $allowedExts))||(($_FILES["prd_img2"]["size"] < 15728640) && in_array($extension2, $allowedExts)))
	// 		{
			
	// 			if ($_FILES["prd_img1"]["error"] > 0)
	// 			{
	// 				$this->session->set_flashdata('error','Failed to upload - Please check file size and file format');
	// 			}
	// 			else
	// 			{
	// 				$timestamp1=time();
	// 				$file_tmp = $_FILES["prd_img1"]["tmp_name"];
	// 				$prd_img1 = $timestamp1."_".$_FILES['prd_img1']['name'];	
	// 				$dest = FCPATH . 'public/uploaded_documents/prd_imgs/' . $prd_img1 ;			
	// 				move_uploaded_file($file_tmp,$dest);
	// 			}
			
	// 			if ($_FILES["prd_img2"]["error"] > 0)
	// 			{
	// 				$this->session->set_flashdata('error','Failed to upload - Please check file size and file format');
	// 			}
	// 			else
	// 			{
	// 				$timestamp1=time();
	// 				$file_tmp = $_FILES["prd_img2"]["tmp_name"];
	// 				$prd_img2 = $timestamp1."_".$_FILES['prd_img2']['name'];	
	// 				$dest = FCPATH . 'public/uploaded_documents/prd_imgs/' . $prd_img2;			
	// 				move_uploaded_file($file_tmp,$dest);
	// 			}
	// 		} 
	// 	}
		
	// 	/////////////
	// 	$data = array(
	// 	'product_code' 		=> $this->input->post('pcode'),
	// 	'product_number' 	=> $this->input->post('prd_number'),
	// 	'product_name' 		=> $this->input->post('iname'),
	// 	'product_category' 	=> $this->input->post('cat_id'),
	// 	'product_desc' 		=> $this->input->post('desc'),
	// 	'product_brand_id' 	=> $brand_id,
	// 	'brand_name'		=> $brand_name,
	// 	'product_made_in'   => $this->input->post('made_in'),
	// 	'product_model_name' => $this->input->post('model_name'),
	// 	'product_tech_data' => $this->input->post('tech_data'),
	// 	'product_unit_id' 	=> $this->input->post('unit_id'),
	// 	'product_type' 		=> $this->input->post('product_type'),
	// 	'created_date' 		=> date('Y-m-d'),
	// 	'created_by'    	=> $this->session->userdata('user_id'),
	// 	'prd_img1' 			=> $prd_img1,
	// 	'prd_img2' 			=> $prd_img2,

	// 	);
	// 	$this->db->insert('product_master', $data);
	// 	$insert_id = $this->db->insert_id();
	// 	 if($insert_id)
	// 	 {	
	// 		$user_se_id=$this->session->userdata('user_id');
	// 		$page_name=explode('index.php/', $_SERVER['PHP_SELF']);
	// 		$ci = get_instance();
	// 		$ci->load->helper('log');
	// 		$log_msg=add_log_entry($user_se_id,1,$page_name[1],'product_master','product_id',$insert_id);
	// 	}
	// 	return $insert_id;
	// }

	function add_product_data()
	{

		// product _image
		$prd_img1 = '';
		$prd_img2 = '';
		$allowedExts = array("jpeg", "jpg", "png");
		$file_name1 = $_FILES["prd_img1"]["name"];
		$file_name2 = $_FILES["prd_img2"]["name"];
		$brand_name = $this->input->post('brand_name');

		// Check if brand exists
		$this->db->where('brand_name', $brand_name);
		$existing_brand = $this->db->get('brand_master')->row();

		if ($existing_brand) {
			$brand_id = $existing_brand->brand_id;
		} else {
			$brand_id = $this->add_brand_data($brand_name);
		}

		$data['file_name1'] = str_replace(' ', '_', $file_name1);
		$data['file_name2'] = str_replace(' ', '_', $file_name2);
		$temp1 = explode(".", $_FILES["prd_img1"]["name"]);
		$temp2 = explode(".", $_FILES["prd_img2"]["name"]);
		$extension1 = end($temp1);
		$extension2 = end($temp2);
		if (!empty($file_name1) || !empty($file_name2)) {
			if ((($_FILES["prd_img1"]["size"] < 15728640) && in_array($extension1, $allowedExts)) || (($_FILES["prd_img2"]["size"] < 15728640) && in_array($extension2, $allowedExts))) {

				if ($_FILES["prd_img1"]["error"] > 0) {
					$this->session->set_flashdata('error', 'Failed to upload - Please check file size and file format');
				} else {
					$timestamp1 = time();
					$file_tmp = $_FILES["prd_img1"]["tmp_name"];
					$prd_img1 = $timestamp1 . "_" . $_FILES['prd_img1']['name'];
					$dest = FCPATH . 'public/uploaded_documents/prd_imgs/' . $prd_img1;
					move_uploaded_file($file_tmp, $dest);
				}

				if ($_FILES["prd_img2"]["error"] > 0) {
					$this->session->set_flashdata('error', 'Failed to upload - Please check file size and file format');
				} else {
					$timestamp1 = time();
					$file_tmp = $_FILES["prd_img2"]["tmp_name"];
					$prd_img2 = $timestamp1 . "_" . $_FILES['prd_img2']['name'];
					$dest = FCPATH . 'public/uploaded_documents/prd_imgs/' . $prd_img2;
					move_uploaded_file($file_tmp, $dest);
				}
			}
		}

		/////////////
		$data = array(
			'product_code' 		=> $this->input->post('pcode'),
			'product_number' 	=> $this->input->post('prd_number'),
			'product_name' 		=> $this->input->post('iname'),
			'product_category' 	=> $this->input->post('cat_id'),
			'product_desc' 		=> $this->input->post('desc'),
			'product_brand_id' 	=> $brand_id,
			'brand_name'		=> $brand_name,
			'product_made_in'   => $this->input->post('made_in'),
			'product_model_name' => $this->input->post('model_name'),
			'product_tech_data' => $this->input->post('tech_data'),
			'product_unit_id' 	=> $this->input->post('unit_id'),
			'product_type' 		=> $this->input->post('product_type'),
			'created_date' 		=> date('Y-m-d'),
			'created_by'    	=> $this->session->userdata('user_id'),
			'prd_img1' 			=> $prd_img1,
			'prd_img2' 			=> $prd_img2,
			'product_price' =>  $this->input->post('price'),

		);
		$this->db->insert('product_master', $data);
		$insert_id = $this->db->insert_id();
		if ($insert_id) {
			$user_se_id = $this->session->userdata('user_id');
			$page_name = explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg = add_log_entry($user_se_id, 1, $page_name[1], 'product_master', 'product_id', $insert_id);
		}
		return $insert_id;
	}
	function update_product_data($id)
	{
		
		// product _image
		$prd_img1='';
		$prd_img2='';
		$allowedExts = array("jpeg","jpg","png");
		$file_name1=$_FILES["prd_img1"]["name"];
		$file_name2=$_FILES["prd_img2"]["name"];
		$brand_name = $this->input->post('brand_name');

		// Check if brand exists
		$this->db->where('brand_name', $brand_name);
		$existing_brand = $this->db->get('brand_master')->row();

		if ($existing_brand) {
			$brand_id = $existing_brand->brand_id;
		} else {
			$brand_id = $this->add_brand_data($brand_name);
		}

		$data['file_name1']=str_replace(' ', '_', $file_name1);
		$data['file_name2']=str_replace(' ', '_', $file_name2);
		$temp1 = explode(".", $_FILES["prd_img1"]["name"]);
		$temp2 = explode(".", $_FILES["prd_img2"]["name"]);
		$extension1 = end($temp1); 
		$extension2 = end($temp2); 
		if (!empty($file_name1) || !empty($file_name2)){
			if ((($_FILES["prd_img1"]["size"] < 15728640) && in_array($extension1, $allowedExts))||(($_FILES["prd_img2"]["size"] < 15728640) && in_array($extension2, $allowedExts)))
			{
				if ($_FILES["prd_img1"]["error"] > 0)
				{
					$this->session->set_flashdata('error','Failed to upload - Please check file size and file format');
				}
				else
				{
					$timestamp1=time();
					$file_tmp = $_FILES["prd_img1"]["tmp_name"];
					$prd_img1 = $timestamp1."_".$_FILES['prd_img1']['name'];	
					$dest = FCPATH . 'public/uploaded_documents/prd_imgs/' . $prd_img1 ;			
					move_uploaded_file($file_tmp,$dest);
				}
			} 
			
				if ($_FILES["prd_img2"]["error"] > 0)
				{
					$this->session->set_flashdata('error','Failed to upload - Please check file size and file format');
				}
				else
				{
					$timestamp1=time();
					$file_tmp = $_FILES["prd_img2"]["tmp_name"];
					$prd_img2 = $timestamp1."_".$_FILES['prd_img2']['name'];	
					$dest = FCPATH . 'public/uploaded_documents/prd_imgs/' . $prd_img2;			
					move_uploaded_file($file_tmp,$dest);
				}
			
		}
		$data = array(
			'product_name' 		=> $this->input->post('iname'),
			'product_number' 	=> $this->input->post('prd_number'),
			'product_price' 	=> $this->input->post('price'),
			'product_type' 		=> $this->input->post('product_type'),
			'product_model_name'=> $this->input->post('model_name'),
			'product_made_in' 	=> $this->input->post('made_in'),
			'product_category' 	=> $this->input->post('cat_id'),
			'product_desc' 		=> $this->input->post('desc'),
			'product_tech_data' => $this->input->post('tech_data'),
			'product_brand_id' 	=> $brand_id,
			'brand_name'		=> $brand_name,
			'product_unit_id'	=> $this->input->post('unit_id'),
			'prd_img1' 			=> $prd_img1,
			'prd_img2' 			=> $prd_img2,
		);
		
		$this->db->where('product_id',$id);
		$res = $this->db->update('product_master', $data);
		// echo $this->db->last_query();exit;
		
		if($res)
		{
			$user_se_id=$this->session->userdata('user_id');
			$page_name=explode('index.php/', $_SERVER['PHP_SELF']);
			$ci = get_instance();
			$ci->load->helper('log');
			$log_msg=add_log_entry($user_se_id,2,$page_name[1],'product_master','product_id',$id);
		}
		return $res;
	}
	

	function get_product_by_id($id)
	{
		
	$query=$this->db->query("SELECT * FROM product_master p LEFT JOIN unit_master u ON p.product_unit_id = u.unit_id LEFT JOIN brand_master m ON p.product_brand_id = m.brand_id WHERE p.product_id='$id'");
	return $query->result();
	}
	function get_products_list()
	{
	$query=$this->db->query("select * from product_master left join unit_master on product_master.product_unit_id = unit_master.unit_id order by product_name");
	return $query->result();
	}

	// function get_product_list($limit, $start)
	// {
		
	// 	$this->db->select('*');
	// 	$this->db->from('product_master');
	// 	$this->db->join('unit_master', 'product_master.product_unit_id = unit_master.unit_id', 'left');
	// 	$this->db->limit($limit, $start);
        
    //     // Example search functionality
    //     $search_term = $this->input->get('search_term'); // Assuming search term is posted from the form
    //     if (!empty($search_term)) {
    //         $this->db->like('product_name', $search_term);
    //     }
        
    //     $query = $this->db->get();
    //     if ($query->num_rows() > 0) {
    //         return $query->result_array();
    //     }
    //     return array();
		
	// }

	function get_product_list()
	{
	$query=$this->db->query("SELECT p.*,u.unit_abbr,m.brand_name,c.country_name,d.category_name FROM product_master p LEFT JOIN unit_master u ON p.product_unit_id = u.unit_id LEFT JOIN brand_master m ON p.product_brand_id = m.brand_id LEFT JOIN country_master c ON c.country_code = p.product_made_in LEFT JOIN category_master d ON d.category_id = p.product_category ORDER BY p.product_name;");
	return $query->result_array();
	}

	function get_products_count(){
		$this->db->select('*');
	    $this->db->from('product_master');
	    $query_result = $this->db->get();
	    $result = $query_result->num_rows();
	    return $result;
	}
	function get_limited_product_list()
	{
		$query=$this->db->query("select (product_name)as product_name,product_id, product_code from product_master left join unit_master on product_master.product_unit_id = unit_master.unit_id order by product_code limit 2000");
		return $query->result();
	}
	function get_product_code_list()
	{
	$query=$this->db->query("select product_id,product_code from product_master order by product_code limit 100");
	return $query->result();
	}
	function get_active_product_list()
	{
		$query=$this->db->query("select * from product_master pm left join unit_master um on pm.product_unit_id = um.unit_id order by product_code");
		return $query->result();
	}
	
	function get_product_description($prefix)
	{
		$pcode_details='';
	   	//$prefix = str_replace('-', '', $prefix);
	   	$mystr = explode(',',$prefix);
		$length= count($mystr);
		for($i=0; $i<$length;$i++)
		{
			$sequenceno = $i+1;
			
		 	if($sequenceno==10)		 		
	   			$mystr = str_replace('-', '', $mystr);
	   			
		 	$myPrefix = $mystr[$i];		 	
	   			
			$query=$this->db->query(" select attr_id from attribute_master where sequence_id=$sequenceno");
			$attr_id= $query->row('attr_id');
			
			$query=$this->db->query(" select attribute_name from attribute_master where sequence_id=$sequenceno");
			$attribute_name= $query->row('attribute_name');
			
			$query=$this->db->query("select sub_attribute from attribute_transaction where attr_id='$attr_id' and prefix='$myPrefix' and is_cancelled=0");
			$desc= $query->row('sub_attribute');
			
			if($sequenceno==5 || $sequenceno==6 || $sequenceno==7 || $sequenceno==8 || $sequenceno==9)
			 $att_name= $attribute_name.': ';
			 else
			 $att_name='';
			$pcode_details=$pcode_details.' '.$att_name.''.$desc.' , ';
		}
		return $pcode_details;
	}

	

	public function get_product_prices($pid=''){
		if($pid == ''){
			return [];
		}
		else{
			$query = $this->db->query("select sp.*,sm.supplier_name from supplier_prices sp left join supplier_master sm on sp.supplier_id = sm.supplier_id where product_id = '$pid' order by price");
			return $query->result_array();
		}
		
	}

	public function get_previous_prices($pid=''){
		if($pid == ''){
			return [];
		}
		else{
			$query = $this->db->query("SELECT a.*, b.cust_name FROM ( SELECT t.inv_master_id, t.price, m.customer_id FROM invoice_transaction t LEFT JOIN invoice_master m ON t.inv_master_id = m.invoice_id WHERE t.product_id = '$pid' ) a LEFT JOIN customer_master b ON a.customer_id = b.customer_id ORDER BY a.inv_master_id DESC LIMIT 3;");
			return $query->result_array();
		}
		
	}
	

		// public function get_product_suggestions($key){

		// 	if(ctype_digit($key)){
		// 		$this->db->select('*');
		// 		$this->db->from('product_master');
		// 		$this->db->join('unit_master', 'product_master.product_unit_id = unit_master.unit_id', 'left');
		// 		$this->db->where("product_code LIKE '$key%'");
			
		// 	}
		// 	else{
		// 		$searchTerms = explode(' ', $key);
		// 	$this->db->select('*');
		// 	$this->db->from('product_master');
		// 	$this->db->join('unit_master', 'product_master.product_unit_id = unit_master.unit_id', 'left');
		// 	foreach ($searchTerms as $term) {
		// 		$this->db->where("product_name LIKE '%$term%'");
		// 	}
		// 	}
			
		// 	$query = $this->db->get();
		// 	 return $query->result_array() ;
			
		// }

		public function get_product_suggestions($key)
	{
		if (ctype_digit($key)) {

			$this->db->select('*');
			$this->db->from('product_master');
			$this->db->join('unit_master', 'product_master.product_unit_id = unit_master.unit_id', 'left');
			$this->db->like('product_code', $key, 'after'); // safer

		} else {

			$searchTerms = explode(' ', trim($key));

			$this->db->select('*');
			$this->db->from('product_master');
			$this->db->join('unit_master', 'product_master.product_unit_id = unit_master.unit_id', 'left');

			foreach ($searchTerms as $term) {
				$this->db->group_start()
					->like('product_name', $term)
					->or_like('product_model_name', $term)
					->group_end();
			}
		}

		$query = $this->db->get();
		return $query->result_array();
	}
		
	

	public function search_items($search_term) {
		$this->db->select('*');
		$this->db->from('product_master');
		$this->db->join('unit_master', 'product_master.product_unit_id = unit_master.unit_id', 'left');
        
        $this->db->like('product_name', $search_term,'both');
        
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

	function add_brand_data() 
	{
		
		$data = array(
		'brand_name' => $this->input->post('brand_name'),
		);
		$this->db->insert('brand_master', $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	function get_brand_list(){
		$query=$this->db->query("select brand_id,brand_name from brand_master WHERE is_cancelled =0 order by brand_id desc");
		return $query->result();
	}
	/************************AMC**************************** */
	function get_amc_product_list()
	{
	//$query=$this->db->query("select * from product_master pm left join category_master cm on pm.prd_category=cm.category_id where cm.category_name='AMC Materials' order by product_id; ");
	$query=$this->db->query("select * from product_master pm order by product_id; ");
	return $query->result();
	}

}?>
