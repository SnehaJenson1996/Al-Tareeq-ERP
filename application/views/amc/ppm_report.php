<?php
$project_name = $project_name ?? '';
$status = $status ?? '';
$ppm_code = $ppm_code ?? '';
?>

<div class="page-body">
  <div class="row">
    <div class="col-sm-12">
      <div class="card" style="font-size:12px;">

        <div class="card-block">

          <!-- FILTER FORM -->
          <form class="form-horizontal"
                action="<?php echo base_url().'index.php/AMC/get_ppm_report'; ?>"
                method="post">

            <div class="form-group row">

              <!-- FROM -->
              <label class="col-sm-1 col-form-label">From</label>
              <div class="col-sm-2">
                <input type="date" class="form-control"
                       name="from"
                       value="<?php echo $from; ?>" required>
              </div>

              <!-- TO -->
              <label class="col-sm-1 col-form-label">To</label>
              <div class="col-sm-2">
                <input type="date" class="form-control"
                       name="to"
                       value="<?php echo $to; ?>" required>
              </div>

              <!-- PROJECT -->
             <!-- <label class="col-sm-1 col-form-label">Project</label>
<div class="col-sm-2">
    <select name="project_name" class="form-control select2">
        <option value="">All</option>

        <?php foreach($projects as $p) { ?>
            <option value="<?= $p->quote_id ?>"
                <?= ($project_name == $p->quote_id) ? 'selected' : '' ?>>
                <?= $p->project_name ?>
            </option>
        <?php } ?>

    </select>
</div> -->
              <!-- TYPE -->
              <!-- <div class="col-sm-2">
                <select name="rpt_type" class="form-control">
                  <option value="Summary" <?php if($rpt_type=='Summary') echo 'selected'; ?>>Summary</option>
                  <option value="Detailed" <?php if($rpt_type=='Detailed') echo 'selected'; ?>>Detailed</option>
                </select>
              </div> -->

              <!-- BUTTONS -->
              <div class="col-sm-3">
                <table>
                  <tr>
                    <td>
                      <input type="submit"
                             class="btn btn-primary btn-sm"
                             value="Go">
                              </form>
                    </td>

                    <td>&nbsp;</td>

                    <td>
                      <form target="_blank"
                            action="<?php echo base_url().'index.php/AMC/print_ppm_report'; ?>"
                            method="post">

                        <input type="hidden" name="from" value="<?php echo $from; ?>">
                        <input type="hidden" name="to" value="<?php echo $to; ?>">
                        <!-- <input type="hidden" name="project_name" value="<?php echo $project_name; ?>"> -->
                        <!-- <input type="hidden" name="rpt_type" value="<?php echo $rpt_type; ?>"> -->

                        <input type="submit"
                               class="btn btn-warning btn-sm"
                               value="Print">
                      </form>
                    </td>

                  </tr>
                </table>
              </div>

            </div>
          </form>

          <!-- SUMMARY VIEW -->
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Sr</th>
                  <th>PPM Code</th>
                  <th>Customer</th>
                  <th>Project</th>
                  <th>Location</th>
                  <th>Subject</th>


                  <th>Date</th>
                  <th>No of Schedules</th>
                  <th>Remarks</th>
                </tr>
              </thead>

              <tbody>
                <?php $i=1; foreach($records as $r) { ?>
                <tr>
                  <td><?php echo $i++; ?></td>
                 <td>
  <a href="<?php echo base_url('index.php/AMC/edit_ppm/'.$r->ppm_id); ?>">
    <?php echo $r->ppm_code; ?>
  </a>
</td>
                <td><?php echo $r->customer_name; ?></td>

                  <td><?php echo $r->project_name; ?></td>
                   <td><?php echo $r->project_location; ?></td>
                    <td><?php echo $r->subject; ?></td>
                  <td><?php echo date('d-M-Y', strtotime($r->ppm_date)); ?></td>
                  <td><?php echo $r->no_of_sch; ?></td>
                  <td><?php echo $r->remarks; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>

          <!--  -->
           
        </div>
      </div>
    </div>
  </div>
</div>