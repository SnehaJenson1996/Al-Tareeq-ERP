<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?php base_url()."public/assets/images/favicon.ico"; ?>" type="image/ico" />

    <title><?php echo $title; ?></title>

    <!-- Bootstrap -->
    <link href="<?php echo base_url()."vendors/bootstrap/dist/css/bootstrap.min.css"; ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url()."vendors/font-awesome/css/font-awesome.min.css"; ?>" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url()."vendors/nprogress/nprogress.css"; ?>" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?php echo base_url()."vendors/iCheck/skins/flat/green.css"; ?>" rel="stylesheet">

    <!-- Datatables -->
    
    <link href=<?php echo base_url()."/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css";?> rel="stylesheet">
    <link href=<?php echo base_url()."/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css";?> rel="stylesheet">
    <link href=<?php echo base_url()."/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css";?> rel="stylesheet">
    <link href=<?php echo base_url()."/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css";?> rel="stylesheet">
    <link href=<?php echo base_url()."/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css";?> rel="stylesheet">

    <!-- bootstrap-progressbar -->
    <link href="<?php echo base_url()."vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css"; ?>" rel="stylesheet">
    <!-- JQVMap -->
    <link href="<?php echo base_url()."vendors/jqvmap/dist/jqvmap.min.css"; ?>" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="<?php echo base_url()."vendors/bootstrap-daterangepicker/daterangepicker.css"; ?>" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="<?php echo base_url()."public/build/css/custom.min.css"; ?>" rel="stylesheet">
    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
           <div class="navbar nav_title" style="border:0; padding:15px 10px;">
    <a href="<?= base_url('index.php/Login/Dashboard'); ?>" class="site_title"
       style="display:flex; align-items:center; justify-content:center; gap:15px; height:auto;">

        <img src="<?= base_url('public/assets/images/altariq_logo.jpeg'); ?>"
             alt="Logo"
             style="height:90px; width:90px; object-fit:contain; border-radius:50%;">

        <span style="font-size:22px; font-weight:bold; color:#fff; line-height:1.2;">
            AL TAREEQ
        </span>
    </a>
</div>



            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <!-- <div class="profile_pic">
                <img src="<?php base_url()."public/assests/images/img.jpg"; ?>" alt="..." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2>John Doe</h2>
              </div> -->
            </div>