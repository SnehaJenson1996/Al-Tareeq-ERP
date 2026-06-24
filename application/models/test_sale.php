


	///////////////////////////////////////////
    public function add_enquiry_data(){
		$prefix='AV'.date("y").'-ENQ';
		$this->load->model('Setup_model');
		$num = $this->Setup_model->get_next_code($prefix,'enquiry_code','enquiry_master',11)+1;
		$digit=sprintf("%1$05d",$num);
		$enquiry_code =$prefix.$digit;

		
        $data=array(
            'enquiry_code' => $enquiry_code,
            'enquiry_date' => date('Y-m-d', strtotime($_POST['enquiry_date'])),
            'enquiry_customer' => $_POST['enquiry_customer'],
			'project_name' => $_POST['project_name'],
            'enquiry_scope' => $_POST['enquiry_scope'],
			'sales_person' => $_POST['sales_person'],
			'created_by' => $this->session->userdata('user_id'),
			'created_at' => time(),
        );
        $res = $this->db->insert('enquiry_master',$data);
		$enquiry_id = $this->db->insert_id();
		
        if($res)
		{
			$i=0;
			$maxFileSize = 2 * 1024 * 1024; // 2MB
			$allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
			foreach ($_FILES['enquiry_file']['name'] as $key => $name) {

				 $error = $_FILES['enquiry_file']['error'][$key];
				$tmpName = $_FILES['enquiry_file']['tmp_name'][$key];
				$size = $_FILES['enquiry_file']['size'][$key];
    
				if ($error === UPLOAD_ERR_NO_FILE) {
					continue;
				}

				// General error check
				if ($error !== UPLOAD_ERR_OK) {
					$res=0;
					break;
				}

				// Validate file size
				if ($size > $maxFileSize) {
					$res=0;
					break;
				}

				// Validate file extension and MIME type
				$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

				if (!in_array($ext, $allowedExtensions)) {
					echo "Invalid file type: $name ($mimeType)<br>";
					continue;
				}

				// Sanitize file name (remove special characters and spaces)
				$safeName = preg_replace("/[^a-zA-Z0-9\._-]/", "_", basename($name));
				$finalName = uniqid('', true). '_' . $safeName;
				$destination = FCPATH . 'public/uploaded_documents/enquiry_files/'.$finalName;
				if (move_uploaded_file($_FILES['enquiry_file']['tmp_name'][$key], $destination)) {
					$data=array(
						'enquiry_id' => $enquiry_id,
						'file_title' => $_POST['file_title'][$i],
						'file_path' => $finalName,
					);
					$this->db->insert('enquiry_attachments',$data);

					$res=1;
					$i++;
					continue;
				} else {
					$res=0;
					break;
				}
			}
		}
		
		return $res;
        
    }

    function get_enquiry_by_status($status)
    {
        $this->db->select('em.*,cm.customer_name');
        $this->db->from('enquiry_master em');
		$this->db->join('customer_master cm','em.enquiry_customer=cm.customer_id');
        $this->db->where('enquiry_status',$status);
		$this->db->order_by('enquiry_code','DESC');
        $result = $this->db->get()->result();
        return $result;      
    }

	

	public function get_enquiry_files($enquiry_id){
		$this->db->select('*');
        $this->db->from('enquiry_attachments ea');
		$this->db->where('enquiry_id',$enquiry_id);
        $result = $this->db->get()->result();
        return $result;
	}

	/*function update_enquiry_data(){   
		 
		$res = 0;
		$data=array(
			'enquiry_date' => $_POST['enquiry_date'],
			'enquiry_customer' => $_POST['enquiry_customer'],
			'enquiry_scope' => $_POST['enquiry_scope'],
			'project_name' => $_POST['project_name'],
			'requested_by' => $_POST['requested_by'],
		);
		$this->db->where('enquiry_id',$_POST['enquiry_id']);
		$res = $this->db->update('enquiry_master',$data);

		if($res && !empty($_POST['deleted_attachments'])){
			$deleted_ids_array = explode(',', $_POST['deleted_attachments']);
			foreach($deleted_ids_array as $attachment){
				$file_details = $this->db->where('attachment_id',$attachment)->get('enquiry_attachments')->row_array();
				$file = 'public/uploaded_documents/enquiry_files/'.$file_details['file_path'];
				if (file_exists($file)) {
					if (unlink($file)) {
						$res = $this->db->where('attachment_id', $attachment)->delete('enquiry_attachments');
						$res = 1;
					} else {
						$res = 0;
					}
				}
				else{
					$res = 0;
				}
			}
		}
		if($res && isset($_POST['file_title'])){
			if ($_FILES["enquiry_file"]) 
            {
					$allowedExts = array("jpeg", "jpg", "png", "doc", "pdf");
					for ($i = 0; $i < count($_FILES['enquiry_file']["name"]); $i++) {
						if ($_FILES['enquiry_file']["name"][$i] != '') {
							$file_name = $_FILES["enquiry_file"]["name"][$i];		
							$pattern = '/[^a-zA-Z0-9_]/';
							$fname = preg_replace($pattern, '_', $file_name);
							$fname=str_replace(' ', '_', $file_name);					
							$temp = explode(".", $fname);
							$extension = end($temp);
							$enquiry_file = '';
							if (($_FILES["enquiry_file"]["size"][$i] < 15728640) && in_array($extension, $allowedExts)) {
								if ($_FILES["enquiry_file"]["error"][$i] > 0) {
									$this->session->set_flashdata('error', 'Failed to upload - Please check file size and file format');
								} else {
									$timestamp1 = time();
									$file_tmp = $_FILES["enquiry_file"]["tmp_name"][$i];
									$enquiry_file = $timestamp1 . "_" . $fname;
									$dest = FCPATH . 'public/uploaded_documents/enquiry_files/'.$enquiry_file;	
							 		move_uploaded_file($file_tmp,$dest);
									$data1 = array(
										'enquiry_id' => $_POST['enquiry_id'],
                                        'file_title' => $_POST['file_title'][$i],
										'file_path' => $enquiry_file,
									);
									$this->db->insert('enquiry_attachments', $data1);
									
								}
							}
						}
					}
			}
		}

	
		
	}*/

	public function update_enquiry_data($enquiry_id, $data)
	{
		$this->db->where('enquiry_id', $enquiry_id);
		return $this->db->update('enquiry_master', $data);
	}

	function get_enquiry_count($firstDay,$lastDay){
		$res = $this->db->select('count(*) as enquiry_count')->where("created_at BETWEEN '$firstDay' AND '$lastDay'")->get('enquiry_master')->row('enquiry_count');
		return $res;
	}

	//estimation
	public function get_estimation_latest_revisions(){
		$this->db->select('em.*,enq.enquiry_code,cm.customer_name,qtn.quotation_approval');
		$this->db->from('estimation_master em');
		$this->db->join(
			'(SELECT estimation_code, MAX(estimation_revision) AS max_revision FROM estimation_master GROUP BY estimation_code) latest',
			'em.estimation_code = latest.estimation_code AND em.estimation_revision = latest.max_revision'
		);
		$this->db->join('sales_quotation_master qtn','em.estimation_id = qtn.estimation_id','left');
		$this->db->join('enquiry_master enq','em.enquiry_id = enq.enquiry_id','left');
		$this->db->join('customer_master cm','enq.enquiry_customer = cm.customer_id','left');
		$this->db->order_by('em.estimation_code','DESC');
		$query = $this->db->get();
		$result = $query->result_array(); 

		return $result;
	}

	public function get_estimation_revisions_grouped(){
		$revisions = $this->db->from('estimation_master')
					->order_by('estimation_revision', 'DESC')
					->get()
					->result();

		$all_revisions = [];
		foreach ($revisions as $rev) {
			$all_revisions[$rev->estimation_code][] = $rev;
		}
		
		return $all_revisions;
	}

	public function get_all_estimation_list(){

		$this->db->select('est.estimation_revision,est.grand_total,enq.*,cm.customer_name');
        $this->db->from('estimation_master est');
		$this->db->join('enquiry_master enq','est.enquiry_id=enq.enquiry_id');
		$this->db->join('customer_master cm','enq.enquiry_customer=cm.customer_id');
		$this->db->order_by('em.estimation_code','DESC');
        $result = $this->db->get()->result();

		return $result;
         
	}

	public function add_estimation_data(){
		$prefix = $prefix='AV'.date("y").'-EST';
		$this->load->model('Setup_model');
		$num = $this->Setup_model->get_next_code($prefix,'estimation_code','estimation_master',11)+1;
		$digit=sprintf("%1$05d",$num);
		$estimation_code =$prefix.$digit;

		$discount2_limit = $this->Setup_model->get_discount_limit();
		$approval = 1;
		$data=array(
			'estimation_code' => $estimation_code,
			'estimation_revision' => 0,
			'enquiry_id' => $_POST['enquiry_id'],
			'vat_percent' => $_POST['vat_percent'],
			'grand_total' => $_POST['grand_total'],
			'customer_contact_id' => $_POST['customer_contact'],
			'notes' => $_POST['notes'],
			'currency'=> $_POST['currency_id'],
			'payment'=> $_POST['payment'],
			'delivery'=> $_POST['delivery'],
			'availability'=> $_POST['availability'],
			'warranty'=> $_POST['warranty'],
			'conditions'=> $_POST['conditions'],
			'created_by' => $this->session->userdata('user_id'),
		);
		$this->db->insert('estimation_master',$data);
		$estimation_id = $this->db->insert_id();

		
		for($i=0 ; $i < $_POST['row_count'] ; $i++){

			if($_POST['item'][$i] != ''){
				if($approval && ($_POST['discount1'][$i] > $_POST['discount1_limit'][$i])){
					$approval = 0;
				}
				if($approval && ($_POST['discount2'][$i] > $discount2_limit['setting_value'])){
					$approval = 0;
				}
				$data=array(
					'estimation_id' => $estimation_id,
					'item_id' => $_POST['item'][$i],
					'quantity' => $_POST['quantity'][$i],
					'unit_id' => $_POST['unit'][$i],
					'actual_price'  => $_POST['quote_price'][$i],
					'discount1_percent' => $_POST['discount1'][$i],
					'discount2_percent' => $_POST['discount2'][$i],
					'section_title' => $_POST['section_title'][$i],
				);
				$this->db->insert('estimation_details',$data);
			}
			
		}

		//update the approval status for estimation
		$this->db->set('approval',$approval);
		$this->db->where('estimation_id',$estimation_id);
		$this->db->update('estimation_master');

		return $estimation_id;
	}

	public function get_estimation_by_id($estimation_id){
		$this->db->select('est.*,enq.*,cm.customer_name');
		$this->db->from('estimation_master est');
		$this->db->join('enquiry_master enq','est.enquiry_id=enq.enquiry_id');
		$this->db->join('customer_master cm','enq.enquiry_customer=cm.customer_id');
		$this->db->where('estimation_id',$estimation_id);
		$result = $this->db->get()->row_array();

		return $result;
	}

	public function get_estimation_details_1($estimation_id){
		$this->db->select('est.*,im.*,bm.brand_name,
		IFNULL(sum(sd.quantity),0) AS current_stock');
		$this->db->from('estimation_details est');
		$this->db->join('item_master im','est.item_id=im.item_id','left');
		$this->db->join('brand_master bm','im.item_brand=bm.brand_id','left');
		$this->db->join('stock_details sd',"est.item_id=sd.product_id and sd.status=0 and sd.inv_type='Actual Stock'",'left');
		$this->db->where('estimation_id',$estimation_id);
		$this->db->group_by('est.item_id');
		
		$result = $this->db->get()->result();

		return $result;
	}
	


	public function delete_estimation_by_id($estimation_id,$enquiry_id){
		$this->db->select('*');
		$this->db->where('estimation_id',$estimation_id);
		$res = $this->db->delete('estimation_details');
		echo $res;
		if($res){
			$this->db->select('*');
			$this->db->where('estimation_id',$estimation_id);
			$res = $this->db->delete('estimation_master');
			echo $res;
		}
		if($res){
			$this->db->set('enquiry_status',0);
			$this->db->where('enquiry_id',$enquiry_id);
			$res = $this->db->update('enquiry_master');
			echo $res;
		}
		return $res;
	}

	function update_estimation_data(){   

		$this->load->model('Setup_model');
		$discount2_limit = $this->Setup_model->get_discount_limit();
		$estimation_id = $_POST['estimation_id'];
		$approval = $_POST['estimation_approval'];
		$res = 0;
		for($i=0 ; $i < $_POST['row_count'] ; $i++){
			if($_POST['item'][$i] != ''){
				
				$data=array(
					'item_id' => $_POST['item'][$i],
					'quantity' => $_POST['quantity'][$i],
					'unit_id' => $_POST['unit'][$i],
					'actual_price'  => $_POST['quote_price'][$i],
					'discount1_percent' => $_POST['discount1'][$i],
					'discount2_percent' => $_POST['discount2'][$i],
					'section_title' => $_POST['section_title'][$i],
				);
				
				
				if($_POST['detail_id'][$i] > 0){
					if($approval){
						$discount_values = $this->db->where('detail_id',$_POST['detail_id'][$i])->get('estimation_details')->row_array();
						if(($discount_values['discount1_percent'] != $_POST['discount1'][$i]) && ($_POST['discount1'][$i] > $_POST['discount1_limit'][$i]))
							$approval = 0;
						else if(($discount_values['discount2_percent'] != $_POST['discount2'][$i]) && $_POST['discount2'][$i] > $discount2_limit['setting_value'])
							$approval = 0;
					}
					$this->db->where('detail_id',$_POST['detail_id'][$i]);
					$res = $this->db->update('estimation_details',$data);
				}
				else{
					if($approval && ($_POST['discount1'][$i] > $_POST['discount1_limit'][$i])){
						$approval = 0;
					}
					if($approval && ($_POST['discount2'][$i] > $discount2_limit['setting_value'])){
						$approval = 0;
					}
					$data['estimation_id'] = $_POST['estimation_id'] ; 
					$this->db->insert('estimation_details',$data);
				}
			}
			
		}
		if($res){
			$data=array(
				'vat_percent' => $_POST['vat_percent'],
				'grand_total' => $_POST['grand_total'],
				'customer_contact_id' => $_POST['customer_contact'],
				'notes' => $_POST['notes'],
				'currency'=> $_POST['currency_id'],
				'payment' => $_POST['payment'],
				'delivery' => $_POST['delivery'],
				'availability'=> $_POST['availability'],
				'warranty'=> $_POST['warranty'],
				'conditions'=> $_POST['conditions'],
				'approval' => $approval,
			);
			$this->db->where('estimation_id',$_POST['estimation_id']);
			$res = $this->db->update('estimation_master',$data);
		}

		//update the approval of quotation if the estimation update is after quotation approval
		if($res){
			$this->db->set('quotation_approval',0);
			$this->db->where('estimation_id',$_POST['estimation_id']);
			$res = $this->db->update('sales_quotation_master');
		}
		

		return $_POST['estimation_id'];
		
	}

	function revise_estimation_data(){
		
		$this->load->model('Setup_model');
		$discount2_limit = $this->Setup_model->get_discount_limit();
		$approval = 1;
		$enquiry_id = $_POST['enquiry_id'];
		$current_revision = $_POST['latest_revision'];
		$res = 0;
		$data=array(
			'estimation_code' => $_POST['estimation_code'],
			'estimation_revision' => $current_revision+1,
			'enquiry_id' => $_POST['enquiry_id'],
			'vat_percent' => $_POST['vat_percent'],
			'grand_total' => $_POST['grand_total'],
			'customer_contact_id' => $_POST['customer_contact'],
			'notes' => $_POST['notes'],
			'currency'=> $_POST['currency_id'],
			'payment'=> $_POST['payment'],
			'delivery'=> $_POST['delivery'],
			'availability'=> $_POST['availability'],
			'warranty'=> $_POST['warranty'],
			'conditions'=> $_POST['conditions'],
			'created_by' => $this->session->userdata('user_id'),
		);
		$this->db->insert('estimation_master',$data);
		$estimation_id = $this->db->insert_id();

		if($estimation_id){
			for($i=0 ; $i < $_POST['row_count'] ; $i++){

				if($_POST['item'][$i] != ''){
					
					$data=array(
						'estimation_id' => $estimation_id,
						'item_id' => $_POST['item'][$i],
						'quantity' => $_POST['quantity'][$i],
						'unit_id' => $_POST['unit'][$i],
						'actual_price'  => $_POST['quote_price'][$i],
						'discount1_percent' => $_POST['discount1'][$i],
						'discount2_percent' => $_POST['discount2'][$i],
					);
					if($_POST['detail_id'][$i] > 0){
						if($approval){
							$discount_values = $this->db->where('detail_id',$_POST['detail_id'][$i])->get('estimation_details')->row_array();
							if(($discount_values['discount1_percent'] != $_POST['discount1'][$i]) && ($_POST['discount1'][$i] > $_POST['discount1_limit'][$i]))
								$approval = 0;
							else if(($discount_values['discount2_percent'] != $_POST['discount2'][$i]) && $_POST['discount2'][$i] > $discount2_limit['setting_value'])
								$approval = 0;
						}
					}
					else{
						if($approval && ($_POST['discount1'][$i] > $_POST['discount1_limit'][$i])){
							$approval = 0;
						}
						if($approval && ($_POST['discount2'][$i] > $discount2_limit['setting_value'])){
							$approval = 0;
						}
					}
					$res = $this->db->insert('estimation_details',$data);
				}
				
			}
		}

		//update estimation approval
		$this->db->set('approval',$approval);
		$this->db->where('estimation_id',$estimation_id);
		$this->db->update('estimation_master');

		return $estimation_id;
	}

	function approve_estimation_data(){

		$res = 0;
		$estimation_id = $_POST['estimation_id'];

		$this->db->set('approval',1);
		$this->db->where('estimation_id',$estimation_id);
		$res = $this->db->update('estimation_master');

		//update the quotation if generated
		if($res && $_POST['quotation_status'] && $_POST['quoted_estimation_id'] == $estimation_id){
			$data=array(
				'additional_discount' => $_POST['total_discount2'],
				'vat_percent' => $_POST['vat_percent'],
				'grand_total' => $_POST['grand_total'],
			);
			$this->db->where('estimation_id',$estimation_id);
			$res = $this->db->update('sales_quotation_master',$data);
		}

		
		if($res)
			return $estimation_id;
		else
			return $res;
	}

	public function get_quotation_status_for_estimation($estimation_code){
		//checking if quotation is generated for estimation
		$this->db->from('sales_quotation_master');
		$this->db->where('estimation_code', $estimation_code);
		$this->db->order_by('quotation_revision', 'DESC');
		$this->db->limit(1);
		
		
		$result = $this->db->get()->row_array();
		
		return $result;
	}

	//quotations
	function add_quotation_data(){   
		
		$prefix='AV'.date("y").'-';
		$this->load->model('Setup_model');
		$num = $this->Setup_model->get_next_code($prefix,'quotation_code','sales_quotation_master',7)+1;
		$digit=sprintf("%1$05d",$num);
		$quotation_code =$prefix.$digit;
		$quotation_date = date('Y-m-d');
		$data=array(
			'quotation_code' => $quotation_code,
			'quotation_revision' => 0,
			'quotation_date' => $quotation_date,
			'estimation_id' => $_POST['estimation_id'],
			'estimation_code' => $_POST['estimation_code'],
			'additional_discount' => $_POST['total_discount2'],
			'vat_percent' => $_POST['vat_percent'],
			'validity' => date('Y-m-d', strtotime($quotation_date . ' +15 days')),
			'grand_total' => $_POST['grand_total'],
			'created_by' => $this->session->userdata('user_id'),
		);
		$res = $this->db->insert('sales_quotation_master',$data);
		$quotation_id = $this->db->insert_id();

		

		return $res;
	}

	public function get_quotation_latest_revisions(){
		$this->db->select('sqm.*,enq.enquiry_code,cm.customer_name,est.approval');
		$this->db->from('sales_quotation_master sqm');
		$this->db->join(
			'(SELECT estimation_code, MAX(quotation_revision) AS max_revision FROM sales_quotation_master GROUP BY estimation_code) latest',
			'sqm.estimation_code = latest.estimation_code AND sqm.quotation_revision = latest.max_revision'
		);
		$this->db->join('estimation_master est','sqm.estimation_id = est.estimation_id','left');
		$this->db->join('enquiry_master enq','est.enquiry_id = enq.enquiry_id','left');
		$this->db->join('customer_master cm','enq.enquiry_customer = cm.customer_id','left');
		$this->db->order_by('est.enquiry_id','DESC');
		$query = $this->db->get();
		$result = $query->result_array(); 

		return $result;
	}

	public function get_quotation_revisions_grouped(){
		$revisions = $this->db->from('sales_quotation_master')
					->order_by('quotation_revision', 'DESC')
					->get()
					->result();

		$all_revisions = [];
		foreach ($revisions as $rev) {
			$all_revisions[$rev->estimation_code][] = $rev;
		}
		
		return $all_revisions;
	}

	function get_quotation_by_id($quotation_id){
		$this->db->select('sqm.*,em.* ,enq.enquiry_customer,enq.project_name,cm.customer_name,cm.customer_address,cm.customer_code,cc.contact_name,cc.contact_phone,cc.contact_email,u.user_name as quotation_by,u2.user_name as enquiry_by,curr.currency_abbr');
		$this->db->from('sales_quotation_master sqm');
		$this->db->join('estimation_master em','sqm.estimation_id=em.estimation_id','left');
		$this->db->join('enquiry_master enq','em.enquiry_id=enq.enquiry_id','left');
		$this->db->join('customer_master cm','enq.enquiry_customer=cm.customer_id','left');
		$this->db->join('customer_contact_details cc','em.customer_contact_id=cc.contact_id','left');
		$this->db->join('currency_master curr','em.currency=curr.currency_id','left');
		$this->db->join('users u','sqm.created_by=u.user_id','left');
		$this->db->join('users u2','enq.created_by=u2.user_id','left');
		$this->db->where('sqm.quotation_id',$quotation_id);
		$result = $this->db->get()->row_array();

		return $result;
	}

	function get_quotation_details($estimation_id){
		$this->db->select('ed.*,sqm.vat_percent,im.*,um.unit_name,bm.brand_name,IFNULL(sum(sd.quantity),0) AS current_stock');
		$this->db->from('estimation_details ed');
		$this->db->join('sales_quotation_master sqm','ed.estimation_id=sqm.estimation_id','left');
		$this->db->join('item_master im','ed.item_id=im.item_id','left');
		$this->db->join('brand_master bm','im.item_brand=bm.brand_id','left');
		$this->db->join('unit_master um','ed.unit_id=um.unit_id','left');
		$this->db->join('stock_details sd',"ed.item_id=sd.product_id and sd.status=0 and sd.inv_type='Actual Stock'",'left');
		$this->db->where('ed.estimation_id',$estimation_id);
		$this->db->group_by('ed.item_id');
		$result = $this->db->get()->result();

		return $result;
	}

	function get_quotation_details_sectioned($estimation_id){
		$this->db->select('ed.*,im.*,um.unit_name,bm.brand_name,IFNULL(sum(sd.quantity),0) AS current_stock');
		$this->db->from('estimation_details ed');
		$this->db->join('item_master im','ed.item_id=im.item_id','left');
		$this->db->join('brand_master bm','im.item_brand=bm.brand_id','left');
		$this->db->join('unit_master um','ed.unit_id=um.unit_id','left');
		$this->db->join('stock_details sd',"ed.item_id=sd.product_id and sd.status=0 and sd.inv_type='Actual Stock'",'left');
		$this->db->where('ed.estimation_id',$estimation_id);
		$this->db->group_by('ed.item_id');
		$this->db->order_by('ed.section_title', 'ASC'); 
		$result = $this->db->get()->result();

		// Group the results manually
		$grouped = [];
		foreach ($result as $row) {
			$grouped[$row->section_title][] = $row;
		}

		return $grouped;
	}

	function update_quotation_data(){
		$quotation_id = $_POST['quotation_id'];
		$estimation_id = $_POST['estimation_id'];
		
		$data=array(
			'quotation_date' => $_POST['quotation_date'],
			'validity'=> $_POST['quotation_validity'],
			'print_coo'=>$_POST['print_coo']??0,
			'print_hsc'=>$_POST['print_hsc']??0,
			'print_stock'=>$_POST['print_stock']??0,
			'print_sections'=>$_POST['print_sections']??0,
		);
		$this->db->where('quotation_id',$quotation_id);
		$res = $this->db->update('sales_quotation_master',$data);

		$data=array(
			'customer_contact_id' => $_POST['customer_contact'],
			'notes' => $_POST['notes'],
			'payment' => $_POST['payment'],
			'delivery' => $_POST['delivery'],
			'availability'=> $_POST['availability'],
			'warranty'=> $_POST['warranty'],
			'conditions'=> $_POST['conditions'],
		);
		$this->db->where('estimation_id',$_POST['estimation_id']);
		$res = $this->db->update('estimation_master',$data);
		

		return $res;
	}

	function revise_quotation_data(){
		
		//revise the quotation if generated
		
		$estimation_id = $_POST['estimation_id'];
		$current_quotation_revision = $_POST['quotation_revision'];
		$res = 0;

		$data=array(
				'quotation_code' => $_POST['quotation_code'],
				'estimation_id' => $estimation_id,
				'estimation_code' => $_POST['estimation_code'],
				'quotation_revision' => $current_quotation_revision+1,
				'additional_discount' => $_POST['total_discount2'],
				'vat_percent' => $_POST['vat_percent'],
				'grand_total' => $_POST['grand_total'],
				'validity' => date('Y-m-d', strtotime(date('Y-m-d') . ' +15 days')),
		);
		$res = $this->db->insert('sales_quotation_master',$data);
		 
		return $res;

	}

	function approve_quotation_data(){
		$quotation_id = $_POST['quotation_id'];
		$data=array(
			'quotation_approval' => 1,
			'lpo_date'=>$_POST['lpo_date'],
			'lpo_number'=>$_POST['lpo_number'],
			'lpo_total'=>$_POST['lpo_total'],
			'approval_remarks'=>$_POST['approval_remarks'],
			'approved_by'=>$_POST['approved_by'],
		);
		$this->db->where('quotation_id',$quotation_id);
		$res = $this->db->update('sales_quotation_master',$data);

		return $res;
	}

	function get_approved_quotation_list(){
		$this->db->select('qtn.*');
		$this->db->from('sales_quotation_master qtn');
		$this->db->where('quotation_approval',1);
		$this->db->order_by('qtn.quotation_code','DESC');
		$res = $this->db->get()->result();

		return $res;
	}

	//function to check if any of the revisions of a quotation is approved
	function get_quotation_approval_status($estimation_code){
		$this->db->where('estimation_code',$estimation_code);
		$this->db->where('quotation_approval',1);
		$approval = $this->db->get('sales_quotation_master')->num_rows();

		return $approval;
	}

	function get_quotation_details_for_sales_order($estimation_id){
		
		// pi Quantity Subquery
		$this->db->select('pd.quotation_detail_id');
		$this->db->select('SUM(CASE WHEN pm.status != -1 AND pd.detail_status != -1 THEN pd.pi_quantity ELSE 0 END) AS pending_quantity');
		$this->db->from('pi_details pd');
		$this->db->join('pi_master pm', 'pd.pi_master_id = pm.pi_id', 'left');
		$this->db->group_by('pd.quotation_detail_id');
		$pi_subquery = $this->db->get_compiled_select();

		// Stock Quantity Subquery
		$this->db->select('product_id');
		$this->db->select('SUM(quantity) AS current_stock');
		$this->db->from('stock_details');
		$this->db->where('status', '0');
		$this->db->where('inv_type', 'Actual Stock');
		$this->db->group_by('product_id');
		$stock_subquery = $this->db->get_compiled_select();

		// Main Query
		$this->db->select('ed.*');
		$this->db->select('i.item_model, i.item_description, um.unit_name');
		$this->db->select('(ed.quantity - IFNULL(pd.pending_quantity, 0)) AS pending_quantity');
		$this->db->select('IFNULL(stk.current_stock,0) AS current_stock');

		$this->db->from('estimation_details ed');
		$this->db->join("($pi_subquery) pd", 'ed.detail_id = pd.quotation_detail_id', 'left');
		$this->db->join('item_master i', 'ed.item_id = i.item_id', 'left');
		$this->db->join('unit_master um', 'ed.unit_id = um.unit_id', 'left');
		$this->db->join("($stock_subquery) stk", 'ed.item_id = stk.product_id', 'left');
		$this->db->where('ed.estimation_id',$estimation_id);
		$this->db->group_by('ed.detail_id');

		$result = $this->db->get()->result();

		return $result;
	}
	

	function cancel_quotation_by_id($quotation_id){
		$this->db->set('quotation_status',-1);
		$this->db->where('quotation_id',$quotation_id);
		$res = $this->db->update('sales_quotation_master');

		return $res;
	}

	function get_quotation_count($firstDay,$lastDay){
		$res = $this->db->select('count(*) as quotation_count')->where("created_at BETWEEN '$firstDay' AND '$lastDay'")->get('sales_quotation_master')->row('quotation_count');
		return $res;
	}

	//sales order

	function add_sales_order_data(){

		$quotation_id = $_POST['quotation_id'];
		$prefix='AVPI#';
		$this->load->model('Setup_model');
		$num = $this->Setup_model->get_next_code($prefix,'pi_code','pi_master',6)+1;
		$digit=sprintf("%1$05d",$num);
		$pi_code =$prefix.$digit.'_'.date('y');
		$data=array(
			'pi_code' => $pi_code,
			'pi_date' => date('Y-m-d'),
			'quotation_id' => $quotation_id,
			'vat_percent' => $_POST['vat_percent'],
			'total_before_vat' => $_POST['subtotal']-$_POST['total_discount2'],
			'grand_total' => $_POST['grand_total'],
			'supplier_ref' => $_POST['supplier_ref'],
			'other_ref' => $_POST['other_ref'],
			'dispatch_document_number' => $_POST['dispatch_document_number'],
			'payment_terms' => $_POST['payment_terms'],
			'dispatch_through' => $_POST['dispatch_through'],
			'destination' => $_POST['destination'],
			'delivery_terms' => $_POST['delivery_terms'],
			'created_by' => $this->session->userdata('user_id'),
		);
		$this->db->insert('pi_master',$data);
		$pi_id = $this->db->insert_id();
		
		//allocation master entry
		$prefix='ALLOC/';
		$this->load->model('Setup_model');
		$num = $this->Setup_model->get_next_code($prefix,'allocation_code','stock_allocation_master',7)+1;
		$digit=sprintf("%1$05d",$num);
		$allocation_code =$prefix.$digit;
		$data=array(
			'allocation_code'=> $allocation_code,
			'allocation_date'=> date('Y-m-d'),
			'pi_master_id' => $pi_id
		);
		$this->db->insert('stock_allocation_master',$data);
		$allocation_master_id = $this->db->insert_id();

		for($i=0 ; $i <= $_POST['row_count'] ; $i++){

			if($_POST['quantity'][$i] > 0){
					
					$data=array(
						'pi_master_id' => $pi_id,
						'quotation_detail_id' => $_POST['detail_id'][$i],
						'pi_quantity' => $_POST['quantity'][$i],
					);
					$this->db->insert('pi_details',$data);
					$pi_detail_id = $this->db->insert_id();

					//allocation from stock
					$allocated_quantity = 0;
					$data=array(
						'allocation_master_id'=> $allocation_master_id,
						'pi_detail_id'=> $pi_detail_id,
						'allocated_quantity' => $allocated_quantity
					);
					$this->db->insert('stock_allocation_details',$data);
					$allocation_detail_id = $this->db->insert_id();

						$this->db->select('*');
						$this->db->from('stock_details sd');
						$this->db->where('sd.product_id',$_POST['item_id'][$i]);
						$this->db->where('sd.inv_type','Actual Stock');
						$this->db->where('sd.status',0);
						$this->db->group_start()
							->where('sd.project', 0)
							->or_where('sd.project', $quotation_id)
						->group_end();
						$this->db->order_by('sd.project','DESC');
						$this->db->order_by('stock_date');
						$res = $this->db->get()->result_array();
						
						if(!empty($res)){
							$order_quantity = $_POST['quantity'][$i];
							foreach($res as $row){
							
								if($order_quantity > 0){
									if($order_quantity >= $row['quantity']){
										$order_quantity = $order_quantity - $row['quantity'];
										//update status
										$data=array(
											'status' => 1,
											'allocation_id' => $allocation_detail_id,
										);
										$this->db->where('stock_id',$row['stock_id']);
										$this->db->update('stock_details',$data);
										$allocated_quantity += $row['quantity'];

										//insert stock out
										// $stock_out = $row;
										// unset($stock_out['stock_id']);
										// $stock_out['stock_type'] = 'OUT';
										// $stock_out['stock_date'] = date('Y-m-d');
										// $stock_out['status'] = 2;
										// $stock_out['allocation_id'] = $allocation_detail_id;
										// $this->db->insert('stock_details',$stock_out);

									}
									else if($row['quantity'] > $order_quantity){
										$remaining = $row['quantity'] - $order_quantity;

										//update stock status
										$data=array(
											'quantity' => $order_quantity,
											'status' => 1,
											'allocation_id' => $allocation_detail_id,
										);
										$this->db->where('stock_id',$row['stock_id']);
										$this->db->update('stock_details',$data);
										$allocated_quantity += $order_quantity;
										

										//insert remaing stock
										$remaining_stock = $row;
										unset($remaining_stock['stock_id']);
										$remaining_stock['quantity'] = $remaining;
										$this->db->insert('stock_details',$remaining_stock);

										
										
										//insert stock out
										// $stock_out = $row;
										// unset($stock_out['stock_id']);
										// $stock_out['stock_type'] = 'OUT';
										// $stock_out['stock_date'] = date('Y-m-d');
										// $stock_out['quantity'] = $order_quantity;
										// $stock_out['status'] = 2;
										// $stock_out['allocation_id'] = $allocation_detail_id;
										// $this->db->insert('stock_details',$stock_out);

										$order_quantity = 0;
									}
								}
							
							}
						}
					$this->db->set('allocated_quantity',$allocated_quantity)->where('allocation_detail_id', $allocation_detail_id)->update('stock_allocation_details');
			
			}
		
		}
	}

	function update_sales_order_data(){
		$res = 0;
		$allocation_master_id = $_POST['allocation_id'];
		$quotation_id = $_POST['quotation_id'];
		

		//free allocated stock
		$stock_deallocation=array('status'=>0,'allocation_id'=>0);
		for($i=0 ; $i < $_POST['row_count'] ; $i++){

			$allocation_detail_id = $_POST['allocation_detail_id'][$i];

			$this->db->where('allocation_id',$allocation_detail_id);
			$this->db->update('stock_details',$stock_deallocation);

			if($_POST['quantity'][$i] > 0){
				$allocated_quantity = 0;
				$data=array(
					'pi_quantity' => $_POST['quantity'][$i],
				);
				$this->db->where('pi_detail_id',$_POST['pi_detail_id'][$i]);
				$res = $this->db->update('pi_details',$data);

				$this->db->select('*');
				$this->db->from('stock_details sd');
				$this->db->where('sd.product_id',$_POST['item_id'][$i]);
				$this->db->where('sd.inv_type','Actual Stock');
				$this->db->where('sd.status',0);
				$this->db->group_start()
					->where('sd.project', 0)
					->or_where('sd.project', $quotation_id)
				->group_end();
				$this->db->order_by('sd.project','DESC');
				$this->db->order_by('stock_date');
				$res = $this->db->get()->result_array();
						
				if(!empty($res)){
					$order_quantity = $_POST['quantity'][$i];
					foreach($res as $row){
								if($order_quantity > 0){
									if($order_quantity >= $row['quantity']){
										$order_quantity = $order_quantity - $row['quantity'];
										//update status
										$data=array(
											'status' => 1,
											'allocation_id' => $allocation_detail_id,
										);
										$this->db->where('stock_id',$row['stock_id']);
										$this->db->update('stock_details',$data);
										$allocated_quantity += $row['quantity'];

										//insert stock out
										// $stock_out = $row;
										// unset($stock_out['stock_id']);
										// $stock_out['stock_type'] = 'OUT';
										// $stock_out['stock_date'] = date('Y-m-d');
										// $stock_out['status'] = 2;
										// $stock_out['allocation_id'] = $allocation_detail_id;
										// $this->db->insert('stock_details',$stock_out);

									}
									else if($row['quantity'] > $order_quantity){
										$remaining = $row['quantity'] - $order_quantity;

										//update stock status
										$data=array(
											'quantity' => $order_quantity,
											'status' => 1,
											'allocation_id' => $allocation_detail_id,
										);
										$this->db->where('stock_id',$row['stock_id']);
										$this->db->update('stock_details',$data);
										$allocated_quantity += $order_quantity;
										

										//insert remaing stock
										$remaining_stock = $row;
										unset($remaining_stock['stock_id']);
										$remaining_stock['quantity'] = $remaining;
										$this->db->insert('stock_details',$remaining_stock);

										
										
										//insert stock out
										// $stock_out = $row;
										// unset($stock_out['stock_id']);
										// $stock_out['stock_type'] = 'OUT';
										// $stock_out['stock_date'] = date('Y-m-d');
										// $stock_out['quantity'] = $order_quantity;
										// $stock_out['status'] = 2;
										// $stock_out['allocation_id'] = $allocation_detail_id;
										// $this->db->insert('stock_details',$stock_out);

										$order_quantity = 0;
									}
								}
					}
				}
				$this->db->set('allocated_quantity',$allocated_quantity)->where('allocation_detail_id', $allocation_detail_id)->update('stock_allocation_details');
				
			}	
			else{
				//delete pi_detail and stock_allocation detail
				$res = $this->db->where('allocation_detail_id',$allocation_detail_id)->delete('stock_allocation_details');
				$res = $this->db->where('pi_detail_id',$_POST['pi_detail_id'][$i])->delete('pi_details');
			}	
		}
		if($res){
			$data=array(
				'total_before_vat' => $_POST['subtotal']-$_POST['total_discount2'],
				'grand_total'=> $_POST['grand_total'],
				'supplier_ref' => $_POST['supplier_ref'],
				'other_ref' => $_POST['other_ref'],
				'dispatch_document_number' => $_POST['dispatch_document_number'],
				'payment_terms' => $_POST['payment_terms'],
				'dispatch_through' => $_POST['dispatch_through'],
				'destination' => $_POST['destination'],
				'delivery_terms' => $_POST['delivery_terms'],
			);
			$this->db->where('pi_id',$_POST['pi_id']);
			$res = $this->db->update('pi_master',$data);
		}

		return $res;
	}

	function get_sales_orders_list($status=''){
		$this->db->select('pi.*,sqm.quotation_code,sqm.quotation_revision,cust.customer_name');
		$this->db->from('pi_master pi');
		$this->db->join('sales_quotation_master sqm','pi.quotation_id=sqm.quotation_id');
		$this->db->join('estimation_master est','sqm.estimation_id=est.estimation_id');
		$this->db->join('enquiry_master enq','est.enquiry_id=enq.enquiry_id');
		$this->db->join('customer_master cust','enq.enquiry_customer=cust.customer_id');
		if($status != '')
			$this->db->where('pi.status',$status);
		$this->db->order_by('pi.pi_code','DESC');
		$res = $this->db->get()->result_array();

		return $res;
	}

	function get_active_sales_orders_list(){
		$this->db->select('pi.*,sqm.quotation_code,sqm.quotation_revision,cust.customer_name');
		$this->db->from('pi_master pi');
		$this->db->join('sales_quotation_master sqm','pi.quotation_id=sqm.quotation_id');
		$this->db->join('estimation_master est','sqm.estimation_id=est.estimation_id');
		$this->db->join('enquiry_master enq','est.enquiry_id=enq.enquiry_id');
		$this->db->join('customer_master cust','enq.enquiry_customer=cust.customer_id');
		$this->db->where('status >=',0);
		$this->db->order_by('pi.pi_code','DESC');
		$res = $this->db->get()->result_array();

		return $res;
	}

	function get_sales_order_by_id($pi_id){
		$this->db->select('pi.*,cust.*,sam.allocation_id');
		$this->db->from('pi_master pi');
		$this->db->join('stock_allocation_master sam','pi.pi_id=sam.pi_master_id');
		$this->db->join('sales_quotation_master sqm','pi.quotation_id=sqm.quotation_id');
		$this->db->join('estimation_master est','sqm.estimation_id=est.estimation_id');
		$this->db->join('enquiry_master enq','est.enquiry_id=enq.enquiry_id');
		$this->db->join('customer_master cust','enq.enquiry_customer=cust.customer_id');
		$this->db->where('pi_id',$pi_id);

		$res = $this->db->get()->row_array();
		return $res;
	}

	function get_pi_details($pi_id){
		
		$this->db->select('pd.*,sad.*,ed.*,im.item_id,im.item_model,im.item_description,um.unit_name,coalesce(sum(invoice_quantity),0) as invoiced_qty,
		(ed.quantity - (SELECT COALESCE(SUM(pd2.pi_quantity), 0) FROM pi_details pd2 WHERE pd2.quotation_detail_id = pd.quotation_detail_id AND pd2.detail_status >= 0)) AS pending_pi_qty');
		$this->db->from('pi_details pd');
		$this->db->join('invoice_details id','pd.pi_detail_id=id.pi_detail_id','left');
		$this->db->join('stock_allocation_details sad','sad.pi_detail_id=pd.pi_detail_id','left');
		$this->db->join('estimation_details ed','pd.quotation_detail_id=ed.detail_id','left');
		$this->db->join('item_master im','ed.item_id=im.item_id','left');
		$this->db->join('unit_master um','ed.unit_id=um.unit_id','left');
		$this->db->where('pd.detail_status >=',0);
		$this->db->where('pd.pi_master_id',$pi_id);
		$this->db->group_by('pd.pi_detail_id');
		$res = $this->db->get()->result();

		return $res;
	}

	function get_pi_details_for_invoice($pi_id,$quotation_id){
		

		// Invoice Quantity Subquery
		$this->db->select('id.pi_detail_id');
		$this->db->select('SUM(CASE WHEN im.invoice_status != -1 AND id.invoice_detail_status != -1 THEN id.invoice_quantity ELSE 0 END) AS invoiced_qty');
		$this->db->from('invoice_details id');
		$this->db->join('invoice_master im', 'id.invoice_master_id = im.invoice_id', 'left');
		$this->db->group_by('id.pi_detail_id');
		$invoice_subquery = $this->db->get_compiled_select();

		// Stock Quantity Subquery
		$this->db->select('sd.product_id');
		$this->db->select('SUM(quantity) AS current_stock');
		$this->db->from('stock_details sd');
		$this->db->join('stock_allocation_details sad','sd.allocation_id = sad.allocation_detail_id','left');
		$this->db->join('pi_details pd','sad.pi_detail_id = pd.pi_detail_id','left');
		$this->db->where('sd.stock_type', 'IN');
		$this->db->where('sd.inv_type', 'Actual Stock');
		$this->db->group_start()
			->group_start()
				->where('sd.status', '0')
				->group_start()
					->where('sd.project', 0)
					->or_where('sd.project', $quotation_id)
				->group_end()
			->group_end()
			->or_group_start()
				->where('sd.status', '1')
				->where('pd.pi_master_id', $pi_id)
			->group_end()
		->group_end();
		$this->db->group_by('sd.product_id');
		$stock_subquery = $this->db->get_compiled_select();
		// Main Query
		$this->db->select('pd.*');
		$this->db->select('ed.*');
		$this->db->select('i.item_model, i.item_description, um.unit_name, sad.allocated_quantity');
		$this->db->select('(pd.pi_quantity - IFNULL(inv.invoiced_qty, 0)) AS pending_quantity');
		$this->db->select('IFNULL(stk.current_stock,0) AS current_stock');
		$this->db->select('IFNULL(SUM(sd.quantity),0) AS allocated_stock');

		$this->db->from('pi_details pd');

		$this->db->join('stock_allocation_details sad', 'pd.pi_detail_id = sad.pi_detail_id', 'left');
		$this->db->join('estimation_details ed', 'pd.quotation_detail_id = ed.detail_id', 'left');
		$this->db->join('item_master i', 'ed.item_id = i.item_id', 'left');
		$this->db->join('unit_master um', 'ed.unit_id = um.unit_id', 'left');
		$this->db->join('stock_details sd', 'pd.pi_detail_id = sd.allocation_id', 'left');
		$this->db->join("($invoice_subquery) inv", 'pd.pi_detail_id = inv.pi_detail_id', 'left');
		$this->db->join("($stock_subquery) stk", 'ed.item_id = stk.product_id', 'left');
		$this->db->where('pd.detail_status >=', 0);
		$this->db->where('pd.pi_master_id', $pi_id);
		$this->db->group_by('sd.allocation_id');
		$res = $this->db->get()->result();
		return $res;
	}

	

	function get_sales_order_status_for_quotation($quotation_id){
		$this->db->select('*');
		$this->db->from('pi_master');
		$this->db->where('status >=',0);
		$this->db->where('quotation_id',$quotation_id);
		$result = $this->db->get()->result_array();
		if(empty($result)){
			return ['status' => '0', 'message' => ''];
		}
		else{
			$invoice_generated = false;
			foreach ($result as $row) {
				$invoices = $this->db->where('pi_id',$row['pi_id'])->get('invoice_master')->result_array();
			//print_r($invoices);exit;
				if(!empty($invoices)){
					foreach($invoices as $inv){
						if($inv['invoice_status'] >= 0){
							$invoice_generated = true;
							break;
						}
					}
				}
				
			}
			if ($invoice_generated) {
				return ['status' => '1', 'message' => 'Invoice Generated. Return the invoices to change the order'];
			} else {
				return ['status' => '2', 'message' => 'Editing the order will cancel the existing sales order(s).
Do you want to continue?'];
			}
		}
	}

	function get_pi_detail_status($detail_id){
		$this->db->select('im.invoice_status');
		$this->db->from('invoice_details id');
		$this->db->join('invoice_master im','id.invoice_master_id=im.invoice_id','left');
		$this->db->where('pi_detail_id',$detail_id);
		$res = $this->db->get()->row_array('im.invoice_status');

		return $res;
	}

	function cancel_sales_orders_by_quotation($quotation_id){
		$this->db->set('status',-1);
		$this->db->where('quotation_id',$quotation_id);
		$res = $this->db->update('pi_master');

		return $res;
	}

	//invoices
	function add_invoice_data(){
		$prefix='AVI#';
		$this->load->model('Setup_model');
		$num = $this->Setup_model->get_next_code($prefix,'invoice_code','invoice_master',5)+1;
		$digit=sprintf("%1$05d",$num);
		$invoice_code =$prefix.$digit.'_'.date('y');
		$data=array(
			'invoice_code' => $invoice_code,
			'invoice_date' => date('Y-m-d'),
			'pi_id' => $_POST['pi_id'],
			'bank_id' => $_POST['bank'],
			'total_before_vat' => $_POST['subtotal']-$_POST['total_discount2'],
			'vat_percent' => $_POST['vat_percent'],
			'grand_total' => $_POST['grand_total'],
			'created_by' => $this->session->userdata('user_id'),
		);
		$res = $this->db->insert('invoice_master',$data);
		$invoice_id = $this->db->insert_id();

		for($i=0 ; $i < $_POST['row_count'] ; $i++){

			if($_POST['quantity'][$i] > 0){
				
				$data=array(
					'invoice_master_id' => $invoice_id,
					'pi_detail_id' => $_POST['detail_id'][$i],
					'invoice_quantity' => $_POST['quantity'][$i],
				);
				$this->db->insert('invoice_details',$data);
				$invoice_detail_id = $this->db->insert_id();

				//stock out the invoiced qty
				$stock_out_qty = $_POST['quantity'][$i];

				$allocation_details = $this->db->select('*')->from('stock_allocation_details')->where('pi_detail_id',$_POST['detail_id'][$i])->get()->row_array();
				$allocated_quantity = $allocation_details?$allocation_details['allocated_quantity']-$allocation_details['issued_quantity']:0;

				
				if($allocated_quantity > 0){
					//issue allocated qty
					$issue_qty=$allocated_quantity;
					$issued_quantity = 0;
					if($stock_out_qty < $issue_qty){
						$issue_qty = $stock_out_qty;
					}

					$this->db->select('*');
					$this->db->from('stock_details sd');
					$this->db->where('allocation_id',$allocation_details['allocation_detail_id']);
					//$this->db->limit($issue_qty);
					$issue_stock = $this->db->get()->result_array();

					if (!empty($issue_stock)) {

						// $ids_to_update = array_column($issue_stock, 'stock_id');

						// $this->db->where_in('stock_id', $ids_to_update);
						// $this->db->set([
						// 	'status' => '2',
						// 	'invoice_id' => $invoice_detail_id
						// ]);
						// $res = $this->db->update('stock_details');
						foreach($issue_stock as $row){
								if($issue_qty > 0){
									if($issue_qty >= $row['quantity']){
										$issue_qty = $issue_qty - $row['quantity'];
										//update status
										$data=array(
											'status' => 2,
											'invoice_id' => $invoice_detail_id,
										);
										$this->db->where('stock_id',$row['stock_id']);
										$this->db->update('stock_details',$data);
										$issued_quantity += $row['quantity'];

										//insert stock out
										$stock_out = $row;
										unset($stock_out['stock_id']);
										$stock_out['stock_type'] = 'OUT';
										$stock_out['stock_date'] = date('Y-m-d');
										$stock_out['status'] = 2;
										$stock_out['invoice_id'] = $invoice_detail_id;
										$stock_out['remark'] = 'Invoice';
										$this->db->insert('stock_details',$stock_out);

									}
									else if($row['quantity'] > $issue_qty){
										$remaining = $row['quantity'] - $issue_qty;

										//update stock status
										$data=array(
											'quantity' => $issue_qty,
											'status' => 2,
											'invoice_id' => $invoice_detail_id,
										);
										$this->db->where('stock_id',$row['stock_id']);
										$this->db->update('stock_details',$data);

										$issued_quantity += $issue_qty;
										

										//insert remaing stock
										$remaining_stock = $row;
										unset($remaining_stock['stock_id']);
										$remaining_stock['quantity'] = $remaining;
										$this->db->insert('stock_details',$remaining_stock);

										
										
										//insert stock out
										$stock_out = $row;
										unset($stock_out['stock_id']);
										$stock_out['stock_type'] = 'OUT';
										$stock_out['stock_date'] = date('Y-m-d');
										$stock_out['quantity'] = $issue_qty;
										$stock_out['status'] = 2;
										$stock_out['invoice_id'] = $invoice_detail_id;
										$stock_out['remark'] = 'Invoice';
										$this->db->insert('stock_details',$stock_out);

										$issue_qty = 0;
									}
								}
						}
					}

					//update allocation details master as allocated
					$this->db->set('issued_quantity', "issued_quantity + {$issued_quantity}", FALSE)->where('allocation_detail_id',$allocation_details['allocation_detail_id'])->update('stock_allocation_details');
				}

				if($stock_out_qty > $allocated_quantity){
					//issue unallocated qty
					$issue_qty=$stock_out_qty - $allocated_quantity;

					$this->db->select('*');
					$this->db->from('stock_details sd');
					$this->db->where('sd.product_id',$_POST['item_id'][$i]);
					$this->db->where('sd.inv_type','Actual Stock');
					$this->db->where('sd.status',0);
					$this->db->group_start()
							->where('sd.project', 0)
							->or_where('sd.project', $quotation_id)
						->group_end();
					$this->db->order_by('stock_date');
					//$this->db->limit($issue_qty);
					$issue_stock = $this->db->get()->result_array();

					if (!empty($issue_stock)) {
						// $ids_to_update = array_column($issue_stock, 'stock_id');

						// $this->db->where_in('stock_id', $ids_to_update);
						// $this->db->set([
						// 	'status' => '2',
						// 	'invoice_id' => $invoice_detail_id
						// ]);
						// $res = $this->db->update('stock_details');
						foreach($issue_stock as $row){
								if($issue_qty > 0){
									if($issue_qty >= $row['quantity']){
										$issue_qty = $issue_qty - $row['quantity'];
										//update status
										$data=array(
											'status' => 2,
											'invoice_id' => $invoice_detail_id,
										);
										$this->db->where('stock_id',$row['stock_id']);
										$this->db->update('stock_details',$data);
										$issued_quantity += $row['quantity'];

										//insert stock out
										$stock_out = $row;
										unset($stock_out['stock_id']);
										$stock_out['stock_type'] = 'OUT';
										$stock_out['stock_date'] = date('Y-m-d');
										$stock_out['status'] = 2;
										$stock_out['invoice_id'] = $invoice_detail_id;
										$stock_out['remark'] = 'Invoice';
										$this->db->insert('stock_details',$stock_out);

									}
									else if($row['quantity'] > $issue_qty){
										$remaining = $row['quantity'] - $issue_qty;

										//update stock status
										$data=array(
											'quantity' => $issue_qty,
											'status' => 2,
											'invoice_id' => $invoice_detail_id,
										);
										$this->db->where('stock_id',$row['stock_id']);
										$this->db->update('stock_details',$data);

										$issued_quantity += $issue_qty;
										

										//insert remaing stock
										$remaining_stock = $row;
										unset($remaining_stock['stock_id']);
										$remaining_stock['quantity'] = $remaining;
										$this->db->insert('stock_details',$remaining_stock);

										
										
										//insert stock out
										$stock_out = $row;
										unset($stock_out['stock_id']);
										$stock_out['stock_type'] = 'OUT';
										$stock_out['stock_date'] = date('Y-m-d');
										$stock_out['quantity'] = $issue_qty;
										$stock_out['status'] = 2;
										$stock_out['invoice_id'] = $invoice_detail_id;
										$stock_out['remark'] = 'Invoice';
										$this->db->insert('stock_details',$stock_out);

										$issue_qty = 0;
									}
								}
						}
					}
				}
				
				
			}
		}
		return $res;
	}

	function update_invoice_data(){
		$res = 0;
		// $allocation_master_id = $_POST['allocation_id'];
		// $quotation_id = $_POST['quotation_id'];
		
		
		for($i=0 ; $i < $_POST['row_count'] ; $i++){

			$invoice_detail_id = $_POST['invoice_detail_id'][$i];
			$pi_detail_id = $_POST['pi_detail_id'][$i];
			$issued_quantity = 0;

			//reset stock
			$this->db->where('stock_type','OUT');
			$this->db->where('invoice_id',$invoice_detail_id);
			$this->db->delete('stock_details');

			$stock = $this->db->where('stock_type','IN')->where('invoice_id',$invoice_detail_id)->get('stock_details')->result_array();
			foreach($stock as $row){
				$data = array();
				if($row['allocation_id'] > 0){
					$data['status']= 1;
					$issued_quantity++;
				}
				else{
					$data['status']= 0;
				}
				$data['invoice_id'] = 0;
				$this->db->where('stock_id',$row['stock_id'])->update('stock_details',$data);
			}

			//deduct issued qty in  allocation details 
			$this->db->set('issued_quantity', "issued_quantity - {$issued_quantity}", FALSE)->where('pi_detail_id',$pi_detail_id)->update('stock_allocation_details');
			// if($_POST['quantity'][$i] > 0){
			// 	$allocated_quantity = 0;
			// 	$data=array(
			// 		'pi_quantity' => $_POST['quantity'][$i],
			// 	);
			// 	$this->db->where('pi_detail_id',$_POST['pi_detail_id'][$i]);
			// 	$res = $this->db->update('pi_details',$data);

			// 	$this->db->select('*');
			// 	$this->db->from('stock_details sd');
			// 	$this->db->where('sd.product_id',$_POST['item_id'][$i]);
			// 	$this->db->where('sd.inv_type','Actual Stock');
			// 	$this->db->where('sd.status',0);
			// 	$this->db->group_start()
			// 		->where('sd.project', 0)
			// 		->or_where('sd.project', $quotation_id)
			// 	->group_end();
			// 	$this->db->order_by('sd.project','DESC');
			// 	$this->db->order_by('stock_date');
			// 	$res = $this->db->get()->result_array();
						
			// 	if(!empty($res)){
			// 		$order_quantity = $_POST['quantity'][$i];
			// 		foreach($res as $row){
			// 					if($order_quantity > 0){
			// 						if($order_quantity >= $row['quantity']){
			// 							$order_quantity = $order_quantity - $row['quantity'];
			// 							//update status
			// 							$data=array(
			// 								'status' => 1,
			// 								'allocation_id' => $allocation_detail_id,
			// 							);
			// 							$this->db->where('stock_id',$row['stock_id']);
			// 							$this->db->update('stock_details',$data);
			// 							$allocated_quantity += $row['quantity'];

			// 							//insert stock out
			// 							// $stock_out = $row;
			// 							// unset($stock_out['stock_id']);
			// 							// $stock_out['stock_type'] = 'OUT';
			// 							// $stock_out['stock_date'] = date('Y-m-d');
			// 							// $stock_out['status'] = 2;
			// 							// $stock_out['allocation_id'] = $allocation_detail_id;
			// 							// $this->db->insert('stock_details',$stock_out);

			// 						}
			// 						else if($row['quantity'] > $order_quantity){
			// 							$remaining = $row['quantity'] - $order_quantity;

			// 							//update stock status
			// 							$data=array(
			// 								'quantity' => $order_quantity,
			// 								'status' => 1,
			// 								'allocation_id' => $allocation_detail_id,
			// 							);
			// 							$this->db->where('stock_id',$row['stock_id']);
			// 							$this->db->update('stock_details',$data);
			// 							$allocated_quantity += $order_quantity;
										

			// 							//insert remaing stock
			// 							$remaining_stock = $row;
			// 							unset($remaining_stock['stock_id']);
			// 							$remaining_stock['quantity'] = $remaining;
			// 							$this->db->insert('stock_details',$remaining_stock);

										
										
			// 							//insert stock out
			// 							// $stock_out = $row;
			// 							// unset($stock_out['stock_id']);
			// 							// $stock_out['stock_type'] = 'OUT';
			// 							// $stock_out['stock_date'] = date('Y-m-d');
			// 							// $stock_out['quantity'] = $order_quantity;
			// 							// $stock_out['status'] = 2;
			// 							// $stock_out['allocation_id'] = $allocation_detail_id;
			// 							// $this->db->insert('stock_details',$stock_out);

			// 							$order_quantity = 0;
			// 						}
			// 					}
			// 		}
			// 	}
			// 	$this->db->set('allocated_quantity',$allocated_quantity)->where('allocation_detail_id', $allocation_detail_id)->update('stock_allocation_details');
				
			// }	
			// else{
			// 	//delete pi_detail and stock_allocation detail
			// 	$res = $this->db->where('allocation_detail_id',$allocation_detail_id)->delete('stock_allocation_details');
			// 	$res = $this->db->where('pi_detail_id',$_POST['pi_detail_id'][$i])->delete('pi_details');
			// }	
			if($_POST['quantity'][$i] > 0){
				$data=array(
					'invoice_quantity' => $_POST['quantity'][$i],
				);
				$this->db->where('invoice_detail_id',$invoice_detail_id);
				$this->db->update('invoice_details',$data);

				//stock out the invoiced qty
				$stock_out_qty = $_POST['quantity'][$i];

				$allocation_details = $this->db->select('*')->from('stock_allocation_details')->where('pi_detail_id',$pi_detail_id)->get()->row_array();
				$allocated_quantity = $allocation_details?$allocation_details['allocated_quantity']-$allocation_details['issued_quantity']:0;

				if($allocated_quantity > 0){
					//issue allocated qty
					$issue_qty=$allocated_quantity;
					$issued_quantity = 0;
					if($stock_out_qty < $issue_qty){
						$issue_qty = $stock_out_qty;
					}

					$this->db->select('*');
					$this->db->from('stock_details sd');
					$this->db->where('allocation_id',$allocation_details['allocation_detail_id']);
					$issue_stock = $this->db->get()->result_array();

					if (!empty($issue_stock)) {
						
						foreach($issue_stock as $row){
								if($issue_qty > 0){
									if($issue_qty >= $row['quantity']){
										$issue_qty = $issue_qty - $row['quantity'];
										//update status
										$data=array(
											'status' => 2,
											'invoice_id' => $invoice_detail_id,
										);
										$this->db->where('stock_id',$row['stock_id']);
										$this->db->update('stock_details',$data);
										$issued_quantity += $row['quantity'];

										//insert stock out
										$stock_out = $row;
										unset($stock_out['stock_id']);
										$stock_out['stock_type'] = 'OUT';
										$stock_out['stock_date'] = date('Y-m-d');
										$stock_out['status'] = 2;
										$stock_out['invoice_id'] = $invoice_detail_id;
										$stock_out['remark'] = 'Invoice';
										$this->db->insert('stock_details',$stock_out);

									}
									else if($row['quantity'] > $issue_qty){
										$remaining = $row['quantity'] - $issue_qty;

										//update stock status
										$data=array(
											'quantity' => $issue_qty,
											'status' => 2,
											'invoice_id' => $invoice_detail_id,
										);
										$this->db->where('stock_id',$row['stock_id']);
										$this->db->update('stock_details',$data);

										$issued_quantity += $issue_qty;
										

										//insert remaing stock
										$remaining_stock = $row;
										unset($remaining_stock['stock_id']);
										$remaining_stock['quantity'] = $remaining;
										$this->db->insert('stock_details',$remaining_stock);

										
										
										//insert stock out
										$stock_out = $row;
										unset($stock_out['stock_id']);
										$stock_out['stock_type'] = 'OUT';
										$stock_out['stock_date'] = date('Y-m-d');
										$stock_out['quantity'] = $issue_qty;
										$stock_out['status'] = 2;
										$stock_out['invoice_id'] = $invoice_detail_id;
										$stock_out['remark'] = 'Invoice';
										$this->db->insert('stock_details',$stock_out);

										$issue_qty = 0;
									}
								}
						}
					}

					//update allocation details master as allocated
					$this->db->set('issued_quantity', "issued_quantity + {$issued_quantity}", FALSE)->where('pi_detail_id',$pi_detail_id)->update('stock_allocation_details');
				}

				if($stock_out_qty > $allocated_quantity){
					//issue unallocated qty
					$issue_qty=$stock_out_qty - $allocated_quantity;

					$this->db->select('*');
					$this->db->from('stock_details sd');
					$this->db->where('sd.product_id',$_POST['item_id'][$i]);
					$this->db->where('sd.inv_type','Actual Stock');
					$this->db->where('sd.status',0);
					$this->db->group_start()
							->where('sd.project', 0)
							->or_where('sd.project', $quotation_id)
						->group_end();
					$this->db->order_by('stock_date');
					//$this->db->limit($issue_qty);
					$issue_stock = $this->db->get()->result_array();

					if (!empty($issue_stock)) {
						
						foreach($issue_stock as $row){
								if($issue_qty > 0){
									if($issue_qty >= $row['quantity']){
										$issue_qty = $issue_qty - $row['quantity'];
										//update status
										$data=array(
											'status' => 2,
											'invoice_id' => $invoice_detail_id,
										);
										$this->db->where('stock_id',$row['stock_id']);
										$this->db->update('stock_details',$data);
										$issued_quantity += $row['quantity'];

										//insert stock out
										$stock_out = $row;
										unset($stock_out['stock_id']);
										$stock_out['stock_type'] = 'OUT';
										$stock_out['stock_date'] = date('Y-m-d');
										$stock_out['status'] = 2;
										$stock_out['invoice_id'] = $invoice_detail_id;
										$stock_out['remark'] = 'Invoice';
										$this->db->insert('stock_details',$stock_out);

									}
									else if($row['quantity'] > $issue_qty){
										$remaining = $row['quantity'] - $issue_qty;

										//update stock status
										$data=array(
											'quantity' => $issue_qty,
											'status' => 2,
											'invoice_id' => $invoice_detail_id,
										);
										$this->db->where('stock_id',$row['stock_id']);
										$this->db->update('stock_details',$data);

										$issued_quantity += $issue_qty;
										

										//insert remaing stock
										$remaining_stock = $row;
										unset($remaining_stock['stock_id']);
										$remaining_stock['quantity'] = $remaining;
										$this->db->insert('stock_details',$remaining_stock);

										
										
										//insert stock out
										$stock_out = $row;
										unset($stock_out['stock_id']);
										$stock_out['stock_type'] = 'OUT';
										$stock_out['stock_date'] = date('Y-m-d');
										$stock_out['quantity'] = $issue_qty;
										$stock_out['status'] = 2;
										$stock_out['invoice_id'] = $invoice_detail_id;
										$stock_out['remark'] = 'Invoice';
										$this->db->insert('stock_details',$stock_out);

										$issue_qty = 0;
									}
								}
						}
					}
				}

			}
			else{
				//delete inv_detail 
				$res = $this->db->where('invoice_detail_id',$invoice_detail_id)->delete('invoice_details');

			}
				
				
				
		}
		if($res){
			$data=array(
				'total_before_vat' => $_POST['subtotal']-$_POST['total_discount2'],
				'grand_total'=> $_POST['grand_total'],
				'supplier_ref' => $_POST['supplier_ref'],
				'other_ref' => $_POST['other_ref'],
				'dispatch_document_number' => $_POST['dispatch_document_number'],
				'payment_terms' => $_POST['payment_terms'],
				'dispatch_through' => $_POST['dispatch_through'],
				'destination' => $_POST['destination'],
				'delivery_terms' => $_POST['delivery_terms'],
			);
			$this->db->where('pi_id',$_POST['pi_id']);
			$res = $this->db->update('pi_master',$data);
		}

		return $res;
	}
	

	function get_invoices_list($status=''){
		$this->db->select('im.*,pm.pi_code,cust.customer_name');
		$this->db->from('invoice_master im');
		$this->db->join('pi_master pm','im.pi_id=pm.pi_id','left');
		$this->db->join('sales_quotation_master sqm','pm.quotation_id=sqm.quotation_id','left');
		$this->db->join('estimation_master est','sqm.estimation_id=est.estimation_id','left');
		$this->db->join('enquiry_master enq','est.enquiry_id=enq.enquiry_id','left');
		$this->db->join('customer_master cust','enq.enquiry_customer=cust.customer_id','left');
		if($status != ''){
			$this->db->where('im.invoice_status',$status);
		}
		$this->db->order_by('im.invoice_code','DESC');
		$res = $this->db->get()->result_array();
		return $res;
	}

	function get_invoice_by_id($invoice_id){
		$this->db->select('im.*,im.vat_percent as invoice_vat,est.*,cust.*,pi.*,bank.*');
		$this->db->from('invoice_master im');
		$this->db->join('pi_master pi','im.pi_id = pi.pi_id','left');
		$this->db->join('sales_quotation_master sqm','pi.quotation_id=sqm.quotation_id','left');
		$this->db->join('estimation_master est','sqm.estimation_id=est.estimation_id','left');
		$this->db->join('enquiry_master enq','est.enquiry_id=enq.enquiry_id','left');
		$this->db->join('customer_master cust','enq.enquiry_customer=cust.customer_id','left');
		$this->db->join('company_bank_details bank','im.bank_id=bank.bid','left');
		$this->db->where('im.invoice_id',$invoice_id);
		$res = $this->db->get()->row_array();
		return $res;
	}

	function get_invoice_details($invoice_id,$type){
		
		$this->db->select('id.*,im.*,ed.*,bm.brand_name,um.unit_name,pd.pi_quantity,sad.allocated_quantity,coalesce(sum(delivery_quantity),0) as delivery_qty,
		(pd.pi_quantity - COALESCE((SELECT SUM(id2.invoice_quantity) FROM invoice_details id2 WHERE id2.pi_detail_id = pd.pi_detail_id AND id2.	invoice_detail_status >= 0), 0)) AS pending_invoice_qty');
		$this->db->from('invoice_details id');
		$this->db->join('dn_details dd','id.invoice_detail_id=dd.invoice_detail_id','left');
		$this->db->join('pi_details pd','id.pi_detail_id=pd.pi_detail_id','left');
		$this->db->join('stock_allocation_details sad','pd.pi_detail_id=sad.pi_detail_id','left');
		$this->db->join('estimation_details ed','pd.quotation_detail_id=ed.detail_id','left');
		$this->db->join('item_master im','ed.item_id=im.item_id','left');
		$this->db->join('brand_master bm','im.item_brand=bm.brand_id','left');
		$this->db->join('unit_master um','ed.unit_id=um.unit_id','left');
		if($type==1)
			$this->db->where('invoice_detail_status >=',0);
		$this->db->where('invoice_master_id',$invoice_id);
		$this->db->group_by('id.invoice_detail_id');
		$res = $this->db->get()->result();

		return $res;
	}

	function get_invoice_details_for_dn($invoice_id){

		$this->db->select('GROUP_CONCAT(sd.stock_id ORDER BY sd.stock_id) AS stock_ids,sd.invoice_id');
		$this->db->from('stock_details sd');
		$this->db->join('invoice_details id','sd.invoice_id=id.invoice_detail_id and sd.status = 2');
		$this->db->where('id.invoice_detail_status >=',0);
		$this->db->where('id.invoice_master_id',$invoice_id);
		$this->db->group_by('id.invoice_detail_id');
		$allocated_query = $this->db->get_compiled_select();
		
		$this->db->select('id.*,
		(
        id.invoice_quantity - IFNULL(SUM(
            CASE 
                WHEN dm.dn_status != -1 AND dd.dn_detail_status != -1 
                THEN dd.delivery_quantity 
                ELSE 0 
            END
        	), 0)
    	) AS pending_quantity,sd.*,
		 
		ed.*,i.item_model,i.item_description,um.unit_name,bm.brand_name');
		
		$this->db->from('invoice_details id');
		$this->db->join('dn_details dd','id.invoice_detail_id =dd.invoice_detail_id ','left');
		$this->db->join('dn_master dm','dd.dn_master_id =dm.dn_id','left');
		$this->db->join('pi_details pd','id.pi_detail_id=pd.pi_detail_id','left');
		// $this->db->join('stock_allocation_details sad', 'sad.pi_detail_id = pd.pi_detail_id', 'left');
		// $this->db->join('stock_details sd', 'sd.allocation_id = sad.allocation_detail_id', 'left');
		//$this->db->join('stock_details sd', 'sd.invoice_id = id.invoice_detail_id and status = 2', 'left');
		$this->db->join("($allocated_query) sd", 'sd.invoice_id = id.invoice_detail_id', 'left');
		$this->db->join('estimation_details ed','pd.quotation_detail_id=ed.detail_id','left');
		$this->db->join('item_master i','ed.item_id=i.item_id','left');
		$this->db->join('brand_master bm','i.item_brand=bm.brand_id','left');
		$this->db->join('unit_master um','ed.unit_id=um.unit_id','left');
		$this->db->where('id.invoice_detail_status >=',0);
		$this->db->where('id.invoice_master_id',$invoice_id);
		$this->db->group_by('id.invoice_detail_id');

		
		$res = $this->db->get()->result();
		return $res;

	}
	

	function cancel_invoice_by_id($invoice_id){
		$this->db->set('invoice_status',-1);
		$this->db->where('invoice_id',$invoice_id);
		$res = $this->db->update('invoice_master');

		return $res;
	}

	function get_cancellation_documents_for_invoice($invoice_id){
		$this->db->select('srm.*');
		$this->db->from('sales_return_master srm');
		$this->db->join('dn_master dm','srm.dn_id=dm.dn_id','left');
		$this->db->join('invoice_master im','dm.invoice_id=im.invoice_id','left');
		$this->db->where('im.invoice_id',$invoice_id);
		$res = $this->db->get()->result_array();

		return $res;
	}

	function get_invoice_amount($firstDay,$lastDay,$currency){
		$res = $this->db->select('COALESCE(sum(im.total_before_vat),0) as invoice_amount')->from('invoice_master im')->join('pi_master pm','im.pi_id=pm.pi_id','left')->join('sales_quotation_master sqm','pm.quotation_id=sqm.quotation_id','left')->join('estimation_master em','sqm.estimation_id=em.estimation_id','left')->where('em.currency',$currency)->where("im.created_at BETWEEN '$firstDay' AND '$lastDay'")->get()->row('invoice_amount');
		return $res;
	}
	//delivery notes
	function get_delivery_note_list($status=''){
		$this->db->select('dm.*,im.invoice_code,cust.customer_name');
		$this->db->from('dn_master dm');
		$this->db->join('invoice_master im','im.invoice_id=dm.invoice_id');
		$this->db->join('pi_master pm','im.pi_id=pm.pi_id');
		$this->db->join('sales_quotation_master sqm','pm.quotation_id=sqm.quotation_id');
		$this->db->join('estimation_master est','sqm.estimation_id=est.estimation_id');
		$this->db->join('enquiry_master enq','est.enquiry_id=enq.enquiry_id');
		$this->db->join('customer_master cust','enq.enquiry_customer=cust.customer_id');
		if($status != '')
		$this->db->where('dn_status',$status);
		$this->db->order_by('dm.dn_code','DESC');
		$res = $this->db->get()->result_array();

		return $res;
	}
	function add_delivery_note_data(){
		$prefix='AVDN#';
		$this->load->model('Setup_model');
		$num = $this->Setup_model->get_next_code($prefix,'dn_code','dn_master',6)+1;
		$digit=sprintf("%1$05d",$num);
		$dn_code =$prefix.$digit.'_'.date('y');
		$data=array(
			'dn_code' => $dn_code,
			'dn_date' => date('Y-m-d'),
			'invoice_id' => $_POST['invoice_id'],
			'created_by' => $this->session->userdata('user_id'),
		);
		$res = $this->db->insert('dn_master',$data);
		$dn_id = $this->db->insert_id();
		

		for($i=0 ; $i < $_POST['row_count'] ; $i++){

			if($_POST['delivery_quantity'][$i] > 0){

				$delivered_stock = json_decode($_POST['scannedSerials'][$i], true);
				$data=array(
					'dn_master_id' => $dn_id,
					'invoice_detail_id' => $_POST['invoice_detail_id'][$i],
					'delivery_quantity' => $_POST['delivery_quantity'][$i],
					'delivery_remarks' => $_POST['remarks'][$i],
				);
				$res = $this->db->insert('dn_details',$data);
				$delivery_detail_id = $this->db->insert_id();

				foreach($delivered_stock as $serial_no){
					$data=array('status'=>3,'dc_id'=>$delivery_detail_id);
					$this->db->where('stock_id',$serial_no);
					$res = $this->db->update('stock_details',$data);
				}
			}
			
		}
		
		return $res;
	}

	function get_dn_by_id($dn_id){
		
		$this->db->select('dm.*,est.*,cust.*,pi.*,im.*');
		$this->db->from('dn_master dm');
		$this->db->join('invoice_master im','dm.invoice_id=im.invoice_id','left');
		$this->db->join('pi_master pi','im.pi_id = pi.pi_id','left');
		$this->db->join('sales_quotation_master sqm','pi.quotation_id=sqm.quotation_id','left');
		$this->db->join('estimation_master est','sqm.estimation_id=est.estimation_id','left');
		$this->db->join('enquiry_master enq','est.enquiry_id=enq.enquiry_id','left');
		$this->db->join('customer_master cust','enq.enquiry_customer=cust.customer_id','left');
		$this->db->where('dm.dn_id',$dn_id);
		$res = $this->db->get()->row_array();
		return $res;
	}

	function get_dn_details($dn_id,$type){
		
		$this->db->select('dd.*,id.invoice_detail_id,pd.pi_detail_id,im.*,ed.*,bm.brand_name,um.unit_name, GROUP_CONCAT(sd.stock_id ORDER BY sd.stock_id) AS stock_ids');
		$this->db->from('dn_details dd');
		$this->db->join('stock_details sd', 'sd.dc_id = dd.dn_detail_id', 'left');
		$this->db->join('invoice_details id','dd.invoice_detail_id=id.invoice_detail_id');
		$this->db->join('pi_details pd','id.pi_detail_id=pd.pi_detail_id','left');
		$this->db->join('estimation_details ed','pd.quotation_detail_id=ed.detail_id','left');
		$this->db->join('item_master im','ed.item_id=im.item_id','left');
		$this->db->join('brand_master bm','im.item_brand=bm.brand_id','left');
		$this->db->join('unit_master um','ed.unit_id=um.unit_id','left');
		if($type==1)
			$this->db->where('dn_detail_status >=',0);
		$this->db->where('dn_master_id',$dn_id);
		$this->db->group_by('dd.dn_detail_id');
		$res = $this->db->get()->result();

		return $res;
	}

	//sales return
	function get_sales_return_list(){
		$this->db->select('sm.*,dm.dn_code,dm.dn_date,im.invoice_code,im.invoice_date,pm.pi_code,pm.grand_total,cust.customer_name');
		$this->db->from('sales_return_master sm');
		$this->db->join('dn_master dm','dm.dn_id=sm.dn_id','left');
		$this->db->join('invoice_master im','im.invoice_id=dm.invoice_id','left');
		$this->db->join('pi_master pm','im.pi_id=pm.pi_id','left');
		$this->db->join('sales_quotation_master sqm','pm.quotation_id=sqm.quotation_id','left');
		$this->db->join('estimation_master est','sqm.estimation_id=est.estimation_id','left');
		$this->db->join('enquiry_master enq','est.enquiry_id=enq.enquiry_id','left');
		$this->db->join('customer_master cust','enq.enquiry_customer=cust.customer_id','left');
		$res = $this->db->get()->result_array();

		return $res;
	}
   
	function add_sales_return_data(){
		
		$data=array(
			'return_date' => date('Y-m-d'),
			'dn_id' => $_POST['dn_id'],
			'notes' => $_POST['notes'],
			'created_by' => $this->session->userdata('user_id'),
		);
		
		$res = $this->db->insert('sales_return_master',$data);
		$insert_id = $this->db->insert_id();

		$total_return_amount=0;

		for($i=0 ; $i < $_POST['row_count'] ; $i++){
			if($_POST['return_quantity'][$i] > 0){
				$invoice_detail_id = $_POST['invoice_detail_id'][$i];
				$dn_detail_id = $_POST['dn_detail_id'][$i];
				$pi_detail_id = $_POST['pi_detail_id'][$i];
				$data=array(
					'return_master_id' => $insert_id,
					'dn_detail_id' => $dn_detail_id,
					'return_quantity' => $_POST['return_quantity'][$i],
				);
				$res = $this->db->insert('sales_return_details',$data);

				$return_amount = $_POST['return_quantity'][$i]*($_POST['rate'][$i]-($_POST['rate'][$i]*($_POST['discount2_percent'][$i]/100)));
				$total_return_amount += $return_amount;
				if($_POST['item_total'][$i]>0){
					$this->db->set('delivery_quantity', 'delivery_quantity -' . $_POST['return_quantity'][$i], FALSE);
					$this->db->where('dn_detail_id',$dn_detail_id);
					$res = $this->db->update('dn_details');
					
					$this->db->set('invoice_quantity', 'invoice_quantity -' . $_POST['return_quantity'][$i], FALSE);
					$this->db->where('invoice_detail_id',$invoice_detail_id);
					$res = $this->db->update('invoice_details');

					$this->db->set('pi_quantity', 'pi_quantity -' . $_POST['return_quantity'][$i], FALSE);
					$this->db->where('pi_detail_id',$pi_detail_id);
					$res = $this->db->update('pi_details');
				}
				else{
					$this->db->set('dn_detail_status', -1);
					$this->db->where('dn_detail_id',$dn_detail_id);
					$res = $this->db->update('dn_details');

					$this->db->set('invoice_detail_status', -1);
					$this->db->where('invoice_quantity',$_POST['return_quantity'][$i]);
					$this->db->where('invoice_detail_id',$invoice_detail_id);
					$res = $this->db->update('invoice_details');

					$this->db->set('detail_status', -1);
					$this->db->where('pi_quantity',$_POST['return_quantity'][$i]);
					$this->db->where('pi_detail_id',$pi_detail_id);
					$res = $this->db->update('pi_details');
				}
			}
			
		}
		

		if($res){
			if($_POST['grand_total']>0){
				//need to update invoice and pi_master grand_total
				$this->db->set('total_before_vat', 'total_before_vat -' . $total_return_amount, FALSE);
				$this->db->where('invoice_id',$_POST['invoice_id']);
				$res = $this->db->update('invoice_master');

				$this->db->set('total_before_vat', 'total_before_vat -' . $total_return_amount, FALSE);
				$this->db->where('pi_id',$_POST['pi_id']);
				$res = $this->db->update('pi_master');

				
			}
			else{
				//to cancel delivery_note,invoice and pi if all items are cancelled
				$this->db->set('dn_status',-1);
				$this->db->set('cancel_remarks','Return');
				$this->db->where('dn_id',$_POST['dn_id']);
				$res = $this->db->update('dn_master');

				$this->db->set('total_before_vat', 'total_before_vat -' . $total_return_amount, FALSE);
				$this->db->where('invoice_id',$_POST['invoice_id']);
				$this->db->where('total_before_vat >', $total_return_amount);
				$res = $this->db->update('invoice_master');

				if($this->db->affected_rows() == 0){
					$this->db->set('invoice_status',-1);
					$this->db->set('cancel_remarks','Return');
					$this->db->where('invoice_id',$_POST['invoice_id']);
					$res = $this->db->update('invoice_master');
				}

				$this->db->set('total_before_vat', 'total_before_vat -' . $total_return_amount, FALSE);
				$this->db->where('pi_id', $_POST['pi_id']);
				$this->db->where('total_before_vat >', $total_return_amount);
				$res = $this->db->update('pi_master');

				if($this->db->affected_rows() == 0){
					$this->db->set('status',-1);
					$this->db->where('pi_id',$_POST['pi_id']);
					$res = $this->db->update('pi_master');
				}
			}
		}


		return $res;

	}

	public function get_sales_return_by_id($return_id){
		//$this->db->select('dm.*,est.*,cust.*,pi.*,im.*');
		$this->db->from('sales_return_master srm');
		$this->db->join('dn_master dm','dm.dn_id=srm.dn_id','left');
		$this->db->join('invoice_master im','dm.invoice_id=im.invoice_id','left');
		$this->db->join('pi_master pi','im.pi_id = pi.pi_id','left');
		$this->db->join('sales_quotation_master sqm','pi.quotation_id=sqm.quotation_id','left');
		$this->db->join('estimation_master est','sqm.estimation_id=est.estimation_id','left');
		$this->db->join('enquiry_master enq','est.enquiry_id=enq.enquiry_id','left');
		$this->db->join('customer_master cust','enq.enquiry_customer=cust.customer_id','left');
		$this->db->where('srm.return_id',$return_id);
		$res = $this->db->get()->row_array();
		return $res;
	}

	function get_return_details($return_id){
		
		$this->db->select('srd.*,im.*,ed.*,bm.brand_name,um.unit_name');
		$this->db->from('sales_return_details srd');
		$this->db->join('dn_details dd','srd.dn_detail_id=dd.dn_detail_id');
		$this->db->join('invoice_details id','dd.invoice_detail_id=id.invoice_detail_id');
		$this->db->join('pi_details pd','id.pi_detail_id=pd.pi_detail_id','left');
		$this->db->join('estimation_details ed','pd.quotation_detail_id=ed.detail_id','left');
		$this->db->join('item_master im','ed.item_id=im.item_id','left');
		$this->db->join('brand_master bm','im.item_brand=bm.brand_id','left');
		$this->db->join('unit_master um','ed.unit_id=um.unit_id','left');
		$this->db->where('return_master_id',$return_id);
		$res = $this->db->get()->result();

		return $res;
	}
	

	//sales report
	public function get_sales_report(){
		$from_date = $_POST['from_date']??date('Y-m-d');
		$to_date = $_POST['to_date']??date('Y-m-d');
		$model = $_POST['item_model']??'';
		$brand = $_POST['item_brand']??'';
		// $customer = $_POST['customer']??'';
		// $sales_person = $_POST['sales_person']??'';
		// $vat_option = $_POST['vat_option'];

		$this->db->select('im.invoice_id,im.invoice_code,im.vat_percent,itm.item_model,bm.brand_name,sum(id.invoice_quantity) as sales_quantity,sum(ed.actual_price * id.invoice_quantity) as sales_amount');
		$this->db->from('invoice_details id');
		$this->db->join('invoice_master im','id.invoice_master_id=im.invoice_id','left');
		$this->db->join('pi_details pd','id.pi_detail_id=pd.pi_detail_id','left');
		$this->db->join('estimation_details ed','pd.quotation_detail_id=ed.detail_id','left');
		$this->db->join('item_master itm','ed.item_id=itm.item_id','left');
		$this->db->join('brand_master bm','itm.item_brand=bm.brand_id','left');
		$this->db->join('unit_master um','ed.unit_id=um.unit_id','left');
		if($model != ''){
			$this->db->where('ed.item_id',$model);
		}
		if($brand != ''){
			$this->db->where('itm.item_brand',$brand);
		}
		$this->db->where('invoice_detail_status >=',0);
		$this->db->where("im.invoice_date BETWEEN '$from_date' AND '$to_date'", null, false);
		$this->db->group_by('ed.item_id');
		$res = $this->db->get()->result();

		return $res;

