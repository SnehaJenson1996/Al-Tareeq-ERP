<div class="x_panel">

    <div class="x_title">

        <ul class="nav navbar-right panel_toolbox">

            <li>
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
            </li>

            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-wrench"></i>
                </a>

                <ul class="dropdown-menu">
                    <li><a href="#">Export Excel</a></li>
                    <li><a href="#">Export PDF</a></li>
                </ul>
            </li>

            <li>
                <a class="close-link">
                    <i class="fa fa-close"></i>
                </a>
            </li>

        </ul>


        <div class="clearfix"></div>


        <?php if($this->session->flashdata('success')) { ?>

        <div class="alert alert-success alert-dismissible">

            <i class="fa fa-check-circle"></i>

            <?= $this->session->flashdata('success'); ?>

            <button type="button" 
                    class="close" 
                    data-dismiss="alert">
                ×
            </button>

        </div>

        <?php } ?>


        <?php if($this->session->flashdata('error')) { ?>

        <div class="alert alert-danger alert-dismissible">

            <i class="fa fa-exclamation-circle"></i>

            <?= $this->session->flashdata('error'); ?>

            <button type="button" 
                    class="close" 
                    data-dismiss="alert">
                ×
            </button>

        </div>

        <?php } ?>


    </div>


    <div class="x_content">


        <div class="card-box table-responsive">


            <table id="datatable-responsive"
                   class="table table-striped table-bordered dt-responsive nowrap"
                   width="100%">


                <thead>

                    <tr style="background:#2A3F54;color:white;">

                        <th>#</th>

                        <th>Quotation Code</th>

                        <th>Type</th>

                        <th>Revision</th>

                        <th>Date</th>

                        <th>Enquiry</th>

                        <th>Branch</th>

                        <th>Customer</th>

                        <th style="text-align:right;">
                            Grand Total
                        </th>

                        <th>Status</th>

                        <th>Created On</th>

                        <th>Actions</th>

                    </tr>

                </thead>



                <tbody>


                <?php if(!empty($quotations)) { ?>


                    <?php 
                    $i=1;

                    foreach($quotations as $qtn)
                    { 
                    ?>


                    <tr>


                        <td>
                            <?= $i++; ?>
                        </td>



                        <td>

                            <a href="<?= base_url('index.php/Sales/view_quotation/'.$qtn['qtn_id']); ?>">

                                <strong>
                                    <?= $qtn['quotation_code']; ?>
                                </strong>

                            </a>

                        </td>



                        <td>
                            <?= strtoupper($qtn['quotation_type']); ?>
                        </td>



                        <td>
                            <?= $qtn['quotation_revision']; ?>
                        </td>



                        <td>
                            <?= date("d-m-Y",strtotime($qtn['quotation_date'])); ?>
                        </td>



                        <td>

                            <?php if(!empty($qtn['enquiry_id'])) { ?>

                            <a href="<?=base_url('index.php/CRM/view_enquiry/'.$qtn['enquiry_id']);?>">

                                <?= $qtn['enquiry_code']; ?>

                            </a>

                            <?php } ?>

                        </td>



                        <td>
                            <?= !empty($qtn['branch_name']) 
                                ? $qtn['branch_name'] 
                                : '-'; ?>
                        </td>



                        <td>
                            <?= !empty($qtn['customer_name']) 
                                ? $qtn['customer_name'] 
                                : '-'; ?>
                        </td>



                        <td style="text-align:right;">

                            <?= number_format($qtn['grand_total'],2); ?>

                        </td>




                        <td>

                            <?php 

                            if($qtn['quotation_status']=='Draft')
                            {

                                echo '<span class="badge bg-warning">
                                        Draft
                                      </span>';

                            }
                            else
                            {

                                echo '<span class="badge bg-success">
                                        Confirmed
                                      </span>';

                            }

                            ?>

                        </td>




                        <td>

                            <?= date("d-m-Y H:i:s",
                            strtotime($qtn['created_on'])); ?>

                        </td>




                        <td>


                            <!-- Edit -->

                            <a href="<?=base_url('index.php/Sales/edit_quotation/'.$qtn['qtn_id']);?>"
                               class="btn btn-primary btn-xs"
                               title="Edit">

                                <i class="fa fa-edit"></i>

                            </a>



                            <!-- Print -->

                            <a target="_blank"
                               href="<?=base_url('index.php/Sales/print_quotation/'.$qtn['qtn_id']);?>"
                               class="btn btn-info btn-xs"
                               title="Print">

                                <i class="fa fa-print"></i>

                            </a>



                            <!-- Delete -->

                            <a href="<?=base_url('index.php/Sales/delete_quotation/'.$qtn['qtn_id']);?>"
                               class="btn btn-danger btn-xs"
                               onclick="return confirm('Are you sure you want to delete this quotation?');"
                               title="Delete">

                                <i class="fa fa-trash"></i>

                            </a>


                        </td>


                    </tr>


                    <?php } ?>



                <?php } else { ?>


                    <tr>

                        <td colspan="12" 
                            class="text-center text-muted">

                            No quotations found

                        </td>

                    </tr>


                <?php } ?>


                </tbody>


            </table>


        </div>


    </div>


</div>