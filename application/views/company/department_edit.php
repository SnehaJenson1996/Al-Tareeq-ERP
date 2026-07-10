<div class="card-body">
          <form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>Company/update_department" id="addform" autocomplete="off" >
            <?php  foreach($records as $row) :?>
              <div class="form-group row">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Department Name <span style="color: red;"> * </span></label>
                <div class="col-xs-12 col-sm-9 col-md-3 col-lg-3">
                  <input tabindex="1" type="text" name="dept_name" id="dept_name" class="form-control" placeholder="" value="<?php echo $row->dept_name; ?>" required>
                </div>
                <div class='col-sm-1 col-md-1'></div>
              </div>

              <div class="form-group row">
                <label class="col-xs-12 col-sm-9 col-md-2 col-lg-2 col-form-label">Status</label>
                <div class="col-xs-12 col-sm-9 col-md-3 col-lg-3">
                  <select tabindex="2" name="status" id="status" class="form-control select2" >
                    <option <?php if($row->status==0) echo 'selected' ?> value="0">Active</option>
                    <option <?php if($row->status==1) echo 'selected' ?> value="1">InAtcive</option>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-form-label">Remarks</label>
                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                  <textarea tabindex="3" name="remark" id="remark" class="form-control" rows="5" cols="3" placeholder="Enter remark"><?php echo $row->remark; ?></textarea>
                </div>
              </div>
              <!-- <input type="hidden" id="id"  name="id" value="<?php echo $row->dept_id;?>" > -->
              <input type="hidden" name="dept_id" id="dept_id" value="<?php echo $row->dept_id; ?>">
            <?php endforeach; ?>

            <div class="form-group row">
              <label class="col-sm-2"></label>
              <div class="col-sm-10">
                <button type="submit" tabindex="4" id="edit" class="btn btn-primary m-b-0">Submit</button>
              </div>
            </div>
          </form>

        </div>
    </div>
</div>
</div>
</div>
</div>

<script>

$(function(){
  $(".js-example-basic-multiple").select2();
  $(".js-example-placeholder-multiple").select2({placeholder:"Select parameters"});
});
</script>
