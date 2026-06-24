<div class="page-body">
  <div class="row">
    <div class="col-sm-12">
      <div class="card" style="font-size:12px;">
        <div class="card-header table-card-header">
         
        </div>
        <div class="card-block">
          <form class="form-horizontal" action="<?php echo base_url().'index.php/'; ?>AMC/get_complaint_report" id="question" method="post" name="question" >
            <div class="form-group row">
              <label class="col-xs-6 col-sm-1 col-md-1 col-lg-1 col-form-label">From <span style="color: red;"> * </span></label>
              <div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
                
                  <input tabindex="1" type="date" class="form-control date_today" id="from" name="from" value="<?php echo $from; ?>" required autocomplete="off">
                 
                
              </div>

              <label class="col-xs-6 col-sm-1 col-md-1 col-lg-1 col-form-label">To <span style="color: red;"> * </span></label>
              <div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
                
                <input tabindex="2" type="date" class="form-control date_today" id="to" name="to" value="<?php echo $to; ?>" required autocomplete="off">
               
              
            </div>

              <label class="col-xs-6 col-sm-2 col-md-2 col-lg-1 col-form-label">Project</label>
              <div class="col-sm-2">
                <select name="project_name" id="project_name" class="form-control select2" tabindex="3" >
                  <option value="">Please select</option>
                  <?php foreach($projects as $g) {?>
  <option value="<?php echo $g->quote_id; ?>"
            <?php if($project_name == $g->quote_id) echo 'selected'; ?>>
            <?php echo $g->project_name; ?>
        </option>                  <?php } ?>
                </select>
              </div>
              <div class="col-xs-12 col-sm-9 col-md-2 col-lg-2">
              <select name ="rpt_type" class="form-control select2" tabindex="4">
                  <option <?php if($rpt_type == 'Summary') echo 'selected';?> value="Summary">Summary</option>
                  <option <?php if($rpt_type == 'Detailed') echo 'selected';?> value="Detailed">Detailed</option>
                </select>

                  </div>
            </div>

            <div class="form-group row">  

              <label class="col-xs-6 col-sm-1 col-md-1 col-lg-1 col-form-label">Status</label>
              <div class="col-sm-2">
                <select tabindex="5" name="status" id="status" class="form-control">
                  <option value="">Please select</option>
                    <option <?php if($status == 'Open') echo 'selected';?> value="Open">Open</option>
                    <option <?php if($status == 'Visited') echo 'selected';?> value="Visited">Visited</option>
                    <option <?php if($status == 'In Progress') echo 'selected';?> value="In Progress">In Progress</option>
                    <option <?php if($status == 'Closed') echo 'selected';?> value="Closed">Closed</option>
                </select>
              </div>
              <!-- <label class="col-xs-6 col-sm-1 col-md-1 col-lg-1 col-form-label">Materials</label>
              <div class="col-sm-2">
                <select tabindex="5" name="product_code" id="product_code" class="form-control">
                  <option value="">Please select</option>
                  <?php foreach($amc_products as $g) {?>
                    <option <?php if($product_code==$g->product_id) echo 'selected'; ?> value="<?php echo $g->product_id ?>"><?php echo $g->product_code;?> </option>
                  <?php } ?>
                </select>
              </div>

              <label class="col-xs-6 col-sm-1 col-md-1 col-lg-1 col-form-label">Technician</label>
              <div class="col-sm-2">
                <select tabindex="5" name="technician" id="technician" class="form-control">
                  <option value="">Please select</option>
                  <?php
                  foreach($user_records as $s) {?>
                  <option <?php if($technician==$s->user_id) echo 'selected'; ?> value="<?php echo $s->user_id ?>"><?php echo $s->user_name;?></option>
                  <?php } ?>
                 
                </select>
              </div> -->

              <div class="col-sm-2">
                <table>
                  <tr>
                    <td>
                      <input tabindex="6" type="submit" id="view" name="go" value="Go" class="btn btn-sm btn-primary m-b-0" />
                    </form>
                  </td>
                  <td>&nbsp;</td>
                  <td>
                    <form target="_blank" action="<?php echo base_url().'index.php/'; ?>AMC/print_complaint_report" id="ques1" method="post" name="ques1" >
                      <input type="hidden" name="from" value="<?php echo $from; ?>" />
                      <input type="hidden" name="to" value="<?php echo $to; ?>" />
                      <input type="hidden" name="project_name" value="<?php echo $project_name; ?>" />
                      <input type="hidden" name="status" value="<?php echo $status; ?>" />
                      <!-- <input type="hidden" name="technician" value="<?php echo $technician; ?>" /> -->
                      <input type="hidden" name="rpt_type" value="<?php echo $rpt_type; ?>" />
                     
                      <input type="submit" id="print" value="Print" class="btn btn-warning btn-sm" tabindex="7" />
                    </form></td>
                   
                  </tr>
                </table>
              </div>
            </div>
            <?php if ($rpt_type == 'Summary') { ?>
            <div class="dt-responsive table-responsive">
              <table id="basic-btn" class="table table-striped table-bordered nowrap">
                <thead>
                  <tr>
                    <th>Sr. No</th>
              			<th>Complaint Code</th>
                    <th>Project</th>
              			<th>Dates</th>
              			<th>Status</th>
              			<th>Total Cost</th>
                  </tr>
                </thead>
                <tbody>
                <?php $i=1; foreach($records as $row) :?>
                <tr>
                  <td><?php echo  $i; $i++;?></td>
                  <td><a href="<?php echo base_url().'index.php/AMC/edit_complaint/'.$row->cmp_id.'';?>"><?php echo $row->cmp_code;?></a></td>
                  <td><?php echo $row->project_name; ?><br/><?php echo $row->description; ?></td>
                  <td>
                    Create Date: <?php echo date('d-M-Y',strtotime($row->cmp_date)); ?><br/>
                    Receive Date: <?php echo date('d-M-Y',strtotime($row->receive_date)); ?><br/>
                    Closing Date: <?php echo date('d-M-Y',strtotime($row->close_date)); ?><br/>
                  </td>
                  <td><?php echo $row->cmp_status; ?></td>
                  <td><?php echo $row->mat_cost+$row->lab_cost; ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>

                <tfoot>
                  <tr>
                  <th>Sr. No</th>
              			<th>Complaint Code</th>
                    <th>Project</th>
              			<th>Dates</th>
              			<th>Status</th>
              			<th>Total Cost</th>
              			
                  </tr>
                </tfoot>
              </table>
            </div>
            <?php } else { ?>
              <div class="dt-responsive table-responsive">
              <table id="basic-btn" class="table table-striped table-bordered nowrap">
                <thead>
                  <tr>
                    <th>Sr. No</th>
              			<th>Complaint Code</th>
                    <th>Project</th>
              			<th>Details</th>
              			<th>Labour Cost</th>
              			<!-- <th>Materials</th> -->
                    <th>Material Cost</th>
                  </tr>
                </thead>
                <tbody>
                <?php $i=1; foreach($records as $row) :?>
                <tr>
                  <td><?php echo  $i; $i++;?></td>
                  <td><a href="<?php echo base_url().'index.php/AMC/edit_complaint/'.$row->cmp_id.'';?>"><?php echo $row->cmp_code;?></a></td>
                  <td><?php echo $row->project_name; ?><br/><?php echo $row->description; ?></td>
                  <td>
                    Flat: <?php echo $row->flat_no; ?><br/>
                    Technician: <?php echo $row->user_name; ?><br/>
                    Visit Date: <?php echo date('d-M-Y',strtotime($row->visit_date)); ?><br/>
                    Hours: <?php echo $row->hours; ?><br/>
                  </td>
                  <td><?php echo $row->expense; ?></td>
                  <!-- <td><?php echo $row->product_code; ?></td> -->
                  <td><?php echo $row->total; ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>

                <tfoot>
                  <tr>
                  <th>Sr. No</th>
              			<th>Complaint Code</th>
                    <th>Project</th>
              			<th>Dates</th>
              			<th>Status</th>
              			<th>Total Cost</th>
              			
                  </tr>
                </tfoot>
              </table>
            </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div> <!-- End Page Body -->
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</body>
</html>
