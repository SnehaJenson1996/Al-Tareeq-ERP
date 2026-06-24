<?php
      $path=$_SERVER['PHP_SELF'];     
      $page_name=str_replace("/aladel_erp/index.php/","",$path);

	if($this->uri->segment(6))
	{
	  	 $last=$this->uri->segment(6);
		 $page_name=str_replace("/$last","",$page_name);
	}
	if($this->uri->segment(5))
	{
	  	 $last=$this->uri->segment(5);
		 $page_name=str_replace("/$last","",$page_name);
	}
	if($this->uri->segment(4))
	{
	  	 $last=$this->uri->segment(4);
		 $page_name=str_replace("/$last","",$page_name);
	}
	if($this->uri->segment(3))
	{
	  	 $last=$this->uri->segment(3);
		 $page_name=str_replace("/$last","",$page_name);
	}

	$page_name=$this->uri->segment(1).'/'.$this->uri->segment(2);
	$user = $this->session->userdata('user_id');
?>
<div class="right_col " role="main">
    <div class="col-md-12 col-sm-12 ">
        <div class="page-title">
			<div class="title_left">
				<h3><?php echo $title; ?></h3>
			</div>
		
			<?php
				$r_add_page=get_add_menu_pageaccess($page_name,'add');
				$add_page='';$link_status='';
				foreach($r_add_page as $result_add_page)
				{
								$add_page=$result_add_page->page_url;
								$link_status=$result_add_page->link_status;
				}
							$list_page='';$link_status_list='';
							$r_list_page= get_add_menu_pageaccess($page_name,'list');
							foreach($r_list_page as $result_list_page)
									{
									$list_page=$result_list_page->page_url;
									$link_status_list=$result_list_page->link_status;
							}

			?>
					 	
		
			<div class="title_right">
				<div class="col-md-4 col-sm-4  form-group pull-right">
				<div class="btn-group">

    <?php if(!empty($r_add_page)){ ?>
        <?php if(has_access($user,$page_name,'A')){ ?> 
            <a href="<?php echo base_url().'index.php/'.$add_page; ?>" class="btn btn-secondary" type="button">
                Add New
            </a>&nbsp;
        <?php } ?>
    <?php } ?>

    <?php 
    if(empty($r_list_page)){
        $list_page = $page_name;
    }
    ?>

    <?php if(strtolower($page_name) != 'accounts/view_account_transaction_details'){ ?>
        <a href="<?php echo base_url().'index.php/'.$list_page; ?>" class="btn btn-secondary" type="button">
            List
        </a>
    <?php } ?>

</div>
						
				</div>
			</div>
		</div>
					</div>
       
       