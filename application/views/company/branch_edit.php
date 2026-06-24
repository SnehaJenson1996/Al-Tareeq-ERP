
<div class="row">
   <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_content">
                <br />
                  <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger">
                      <?php echo $this->session->flashdata('error'); ?>
                    </div>
                  <?php endif; ?>
                 <form action="<?php echo base_url().'index.php/Company/update_branch_data'; ?>" method="post" autocomplete="off" enctype="multipart/form-data">
                      <!-- Flash error -->
                      <?php if ($this->session->flashdata('error')): ?>
                          <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                      <?php endif; ?>
                      <input type="hidden" name="branch_id" value="<?= $branch_id ?>" />

                      <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label>Branch Code <span class="text-danger">*</span></label>
                                  <input type="text" name="branch_code" class="form-control" value="<?= $branch_data->branch_code?>" readonly>
                              </div>
                              <div class="form-group">
                                  <label>Branch Manager <span class="text-danger">*</span></label>
                                  <input type="text" name="branch_manager" value="<?= $branch_data->branch_manager?>" class="form-control">
                              </div>
                              <div class="form-group">
                                  <label>Branch Email <span class="text-danger">*</span></label>
                                  <input type="email" name="branch_email" value="<?= $branch_data->branch_email?>" class="form-control">
                              </div>
                              <div class="form-group">
                                  <label>Branch Website</label>
                                  <input type="text" name="branch_website" value="<?= $branch_data->branch_web?>"  class="form-control">
                              </div>
                             <div class="form-group">
    <label>Branch Header</label>
    <input type="file" name="branch_header" class="form-control">

    <?php if (!empty($branch_data->branch_header)) { ?>
        <br>
        <a href="<?= base_url($branch_data->branch_header); ?>" target="_blank" class="btn btn-sm btn-info">
            View Header
        </a>
    <?php } ?>
</div>
                             <div class="form-group">
    <label>Branch Stamp</label>
    <input type="file" name="branch_stamp" class="form-control">

    <?php if (!empty($branch_data->branch_stamp)) { ?>
        <br>
        <a href="<?= base_url($branch_data->branch_stamp); ?>" target="_blank" class="btn btn-sm btn-info">
             View Stamp
        </a>
    <?php } ?>
</div>
                          </div>

                          <div class="col-md-6">
                              <div class="form-group">
                                  <label>Branch Name <span class="text-danger">*</span></label>
                                  <input type="text" name="branch_name" value="<?= $branch_data->branch_name?>" class="form-control">
                              </div>
                              <div class="form-group">
                                  <label>Branch Contact Number <span class="text-danger">*</span></label>
                                  <input type="text" name="branch_contact" value="<?= $branch_data->branch_contact?>" class="form-control">
                              </div>
                              <div class="form-group">
                                <label>Branch Location <span class="text-danger">*</span></label>
                                <select name="branch_location" class="form-control">
                                    <option value="">Select Location</option>
                                    <option value="Abu Dhabi" <?= ($branch_data->branch_location == 'Abu Dhabi') ? 'selected' : '' ?>>Abu Dhabi</option>
                                    <option value="Dubai" <?= ($branch_data->branch_location == 'Dubai') ? 'selected' : '' ?>>Dubai</option>
                                    <option value="Sharjah" <?= ($branch_data->branch_location == 'Sharjah') ? 'selected' : '' ?>>Sharjah</option>
                                    <option value="Ajman" <?= ($branch_data->branch_location == 'Ajman') ? 'selected' : '' ?>>Ajman</option>
                                    <option value="Umm Al Quwain" <?= ($branch_data->branch_location == 'Umm Al Quwain') ? 'selected' : '' ?>>Umm Al Quwain</option>
                                    <option value="Ras Al Khaimah" <?= ($branch_data->branch_location == 'Ras Al Khaimah') ? 'selected' : '' ?>>Ras Al Khaimah</option>
                                    <option value="Fujairah" <?= ($branch_data->branch_location == 'Fujairah') ? 'selected' : '' ?>>Fujairah</option>
                                </select>
                                </div>

                              <div class="form-group">
                                  <label>TRN NO<span class="text-danger">*</span></label>
                                  <input type="text" name="branch_trn" value="<?=$branch_data->branch_trn?>" class="form-control">
                              </div>
                              <div class="form-group">
    <label>Branch Logo</label>
    <input type="file" name="branch_logo" class="form-control">

    <?php if (!empty($branch_data->branch_logo)) { ?>
        <br>
        <a href="<?= base_url($branch_data->branch_logo); ?>" target="_blank" class="btn btn-sm btn-info">
            View Logo
        </a>
    <?php } ?>
</div>


                              <div class="form-group">
    <label>Branch Footer</label>
    <input type="file" name="branch_footer" class="form-control">

    <?php if (!empty($branch_data->branch_footer)) { ?>
        <br>
        <a href="<?= base_url($branch_data->branch_footer); ?>" target="_blank" class="btn btn-sm btn-info">
             View Footer
        </a>
    <?php } ?>
</div>
                          </div>
                      </div>

                      <!-- Address -->
                      <div class="form-group">
                          <label>Branch Address</label>
                          <textarea name="branch_address" class="form-control"><?= $branch_data->branch_address?></textarea>
                      </div>

                      <!-- BANK DETAILS TABLE -->
                      <h5><strong>Bank Account Details</strong></h5>
                      <table class="table table-bordered table-hover" id="tab_logic">
                            <thead>
                                <tr>
                                    <th>Bank Name</th>
                                    <th>Account No</th>
                                    <th>Branch</th>
                                    <th>IBAN</th>
                                    <th>SWIFT</th>
                                    <th width="30px"><a id="add_row" class="btn btn-xs bg-orange"><span class="fa fa-plus"></span></a></th>
                                </tr>
                            </thead>
                            <tbody id="mytbbody">
                                <?php if (!empty($branch_bank)) {
                                    $i = 0;
                                    foreach ($branch_bank as $bank) { ?>
                                        <tr id="addr<?= $i ?>">
                                            <td><input type="text" name="bname[]" class="form-control" value="<?= htmlspecialchars($bank->bank_name) ?>"></td>
                                            <td><input type="text" name="bacc[]" class="form-control" value="<?= htmlspecialchars($bank->bank_account) ?>"></td>
                                            <td><input type="text" name="bbranch[]" class="form-control" value="<?= htmlspecialchars($bank->bank_branch) ?>"></td>
                                            <td><input type="text" name="biban[]" class="form-control" value="<?= htmlspecialchars($bank->bank_iban) ?>"></td>
                                            <td><input type="text" name="bswift[]" class="form-control" value="<?= htmlspecialchars($bank->bank_swift) ?>"></td>
                                            <td>
                                                <a class="btn btn-xs bg-orange" onclick="remove_row(<?= $i ?>)"><span class="fa fa-trash"></span></a>
                                            </td>
                                        </tr>
                                <?php $i++; }
                                } else { ?>
                                    <!-- Default empty row when no bank data -->
                                    <tr id="addr0">
                                        <td><input type="text" name="bname[]" class="form-control"></td>
                                        <td><input type="text" name="bacc[]" class="form-control"></td>
                                        <td><input type="text" name="bbranch[]" class="form-control"></td>
                                        <td><input type="text" name="biban[]" class="form-control"></td>
                                        <td><input type="text" name="bswift[]" class="form-control"></td>
                                        <td><a class="btn btn-xs bg-orange" onclick="remove_row(0)"><span class="fa fa-trash"></span></a></td>
                                    </tr>
                                <?php } ?>
                                <!-- One empty row for dynamic row addition -->
                                <!-- <tr id="addr<?= isset($i) ? $i : 1 ?>"></tr> -->
                            </tbody>

                        </table>


                      <div class="form-group text-center">
                          <button type="submit" class="btn btn-success">Update</button>
                      </div>

                  </form>
            </div>
        </div>
    </div>
  <div class="col-md-12 col-sm-12 ">
    
<script>
    let i = $('#mytbbody tr').length;

    $('#add_row').click(function () {
        let row = `
        <tr id="addr${i}">
            <td><input type="text" name="bname[]" class="form-control"></td>
            <td><input type="text" name="bacc[]" class="form-control"></td>
            <td><input type="text" name="bbranch[]" class="form-control"></td>
            <td><input type="text" name="biban[]" class="form-control"></td>
            <td><input type="text" name="bswift[]" class="form-control"></td>
            <td><a class="btn btn-xs bg-orange" onclick="remove_row(${i})"><span class="fa fa-trash"></span></a></td>
        </tr>`;
        $('#mytbbody').append(row);
        i++;
    });

    function remove_row(rowId) {
        $('#addr' + rowId).remove();
    }

    // Required field names (excluding file inputs)
    const requiredFields = [
        'branch_code',
        'branch_manager',
        'branch_email',
        'branch_name',
        'branch_contact',
        'branch_location',
        'branch_trn'
    ];

    $('form').on('submit', function (e) {
        e.preventDefault(); // Stop normal submission

        let formValid = true;
        let missingFields = [];

        // Validate each required field
        requiredFields.forEach(function (name) {
            let field = $('[name="' + name + '"]');
            if (!field.val() || field.val().trim() === '') {
                formValid = false;
                let label = field.closest('.form-group').find('label').text();
                missingFields.push(label);
            }
        });

        // Ensure at least one bank row is filled
        let hasBank = false;
        $('#tab_logic tbody tr').each(function () {
            if ($(this).find('input[name="bname[]"]').val().trim() !== '') {
                hasBank = true;
            }
        });

        if (!hasBank) {
            formValid = false;
            missingFields.push("At least one Bank Account row");
        }

        if (!formValid) {
            alert("Please fill the following required fields:\n- " + missingFields.join("\n- "));
            return false;
        }

        // Submit normally
        this.submit();
    });
</script>
