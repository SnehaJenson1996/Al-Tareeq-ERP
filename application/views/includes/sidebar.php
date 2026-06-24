 <!-- sidebar menu -->
 <?php 
  $this->load->helper('menu'); 
  $user_id=$this->session->userdata('user_id');
  $access_menu = get_menu($user_id);	
?>
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu" style="margin-top:50px;">
              <div class="menu_section">
                
                <ul class="nav side-menu">
                <?php if(!empty($access_menu)) {
							    foreach($access_menu as $node)
							    {
								    $temppid = $node->menu_pid;
								    $num_rows = get_menu_sid_count($temppid);
								    if($num_rows > 0){?>	
											<li><a><i class="fa fa-home"></i> <?php echo $node->menu_name; ?> <span class="fa fa-chevron-down"></span></a>
                          <ul class="nav child_menu">
                          <?php 
                          $res = get_menu_sid($temppid,$user_id);
                          foreach($res as $row) { ?>
                          <li><a href="<?php echo base_url().'index.php/'.$row->menu_url;?>"><?php echo $row->menu_name; ?></a></li>
                          <?php } ?>
                          </ul>
                      </li>          
								    <?php }
								    else{?>
											                    
								    <?php } 
							    } //end foreach 
						    }?>	 
                </ul>
              </div>
              

</div>
            <!-- /sidebar menu -->