 <!-- top navigation -->
 <div class="top_nav">
          <div class="nav_menu">
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>
              <nav class="nav navbar-nav">
              <ul class=" navbar-right">
                <li class="nav-item dropdown open" style="padding-left: 15px;">
                  <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo base_url()?>public/assets/images/user.png" alt=""><?php echo $this->session->userdata('user_name'); ?>
                  </a>
                  <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item"  href="javascript:;"> Profile</a>
                      <a class="dropdown-item"  href="javascript:;">
                        <span class="badge bg-red pull-right">50%</span>
                        <span>Settings</span>
                      </a>
                    <a class="dropdown-item"  href="javascript:;">Help</a>
                    <a class="dropdown-item"  href="<?php echo base_url().'index.php/Login/logout'; ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a>
                  </div>
                </li>

                <li role="presentation" class="nav-item dropdown open">
                  <a href="javascript:;" class="dropdown-toggle info-number" id="navbarDropdown1" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope-o"></i>
                    <?php if(!empty($overdue_count)): ?>
    <span class="badge bg-red"><?= $overdue_count ?></span>
<?php endif; ?>

                  </a>
                  <ul class="dropdown-menu list-unstyled msg_list" role="menu">

<?php if(!empty($overdue_projects)): ?>
    <?php foreach($overdue_projects as $proj): ?>
        <li class="nav-item">
            <a class="dropdown-item">
                <span>
                    <strong><?= $proj->project_code ?></strong>
                    <span class="time text-danger">
                        <?= $proj->overdue_days ?> days overdue
                    </span>
                </span>
                <span class="message">
                    <?= $proj->project_name ?><br>
                    End Date: <?= date('d-M-Y', strtotime($proj->end_date)) ?>
                </span>
            </a>
        </li>
    <?php endforeach; ?>

    <!-- <li class="nav-item">
        <div class="text-center">
            <a class="dropdown-item">
                <strong>View All Projects</strong>
                <i class="fa fa-angle-right"></i>
            </a>
        </div>
    </li> -->
<?php else: ?>
    <li class="nav-item text-center">
        <span class="dropdown-item">No overdue projects</span>
    </li>
<?php endif; ?>

</ul>

                </li>
              </ul>
            </nav>
          </div>
  </div>
        <!-- /top navigation -->