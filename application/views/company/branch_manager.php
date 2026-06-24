<style>
label,h4 {
  color: black;
   font-weight: bold;
}
</style>
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
                 
                 <form action="<?php echo base_url().'index.php/Company/add_branch_data'; ?>" method="post" autocomplete="off" enctype="multipart/form-data" id="branch">
                      <!-- Flash error -->
                      <?php if ($this->session->flashdata('error')): ?>
                          <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                      <?php endif; ?>
                      <div class="row">
                          <div class="col-md-6">
                              <div class="form-group">
                                  <label>Branch Code <span class="text-danger">*</span></label>
                                  <input type="text" name="branch_code" class="form-control" value="<?=$branch_code?>" readonly>
                              </div>
                              <div class="form-group">
                                  <label>Branch Manager <span class="text-danger">*</span></label>
                                  <input type="text" name="branch_manager" class="form-control">
                              </div>
                              <div class="form-group">
                                  <label>Branch Email <span class="text-danger">*</span></label>
                                  <input type="email" name="branch_email" class="form-control">
                              </div>
                              <div class="form-group">
                                  <label>Branch Website</label>
                                  <input type="text" name="branch_website" class="form-control">
                              </div>
                              <div class="form-group">
                                  <label>Branch Header<span class="text-danger">*</span></label>
                                  <input type="file" name="branch_header" class="form-control">
                              </div>
                              <div class="form-group">
                                  <label>Branch Stamp</label>
                                  <input type="file" name="branch_stamp" class="form-control">
                              </div>
                          </div>

                          <div class="col-md-6">
                              <div class="form-group">
                                  <label>Branch Name <span class="text-danger">*</span></label>
                                  <input type="text" name="branch_name" class="form-control">
                              </div>
                              <div class="form-group">
                                  <label>Branch Contact Number <span class="text-danger">*</span></label>
                                  <input type="text" name="branch_contact" class="form-control">
                              </div>
                              <div class="form-group">
                                  <label>Branch Location <span class="text-danger">*</span></label>
                                  <select name="branch_location" class="form-control">
                                      <option value="">Select Location</option>
                                      <option value="Abu Dhabi">Abu Dhabi</option>
                                      <option value="Dubai">Dubai</option>
                                      <option value="Sharjah">Sharjah</option>
                                      <option value="Ajman">Ajman</option>
                                      <option value="Umm Al Quwain">Umm Al Quwain</option>
                                      <option value="Ras Al Khaimah">Ras Al Khaimah</option>
                                      <option value="Fujairah">Fujairah</option>
                                  </select>
                              </div>
                              <div class="form-group">
                                  <label>TRN NO<span class="text-danger">*</span></label>
                                  <input type="text" name="branch_trn" class="form-control">
                              </div>
                              <div class="form-group">
                                  <label>Branch Logo<span class="text-danger">*</label></label>
                                  <input type="file" name="branch_logo" class="form-control">
                              </div>
                              <div class="form-group">
                                  <label>Branch Footer<span class="text-danger">*</label></label>
                                  <input type="file" name="branch_footer" class="form-control">
                              </div>
                          </div>
                      </div>

                      <!-- Address -->
                      <div class="form-group">
                          <label>Branch Address</label>
                          <textarea name="branch_address" class="form-control"></textarea>
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
                              <tr id="addr0">
                                  <td><input type="text" name="bname[]" class="form-control"></td>
                                  <td><input type="text" name="bacc[]" class="form-control"></td>
                                  <td><input type="text" name="bbranch[]" class="form-control"></td>
                                  <td><input type="text" name="biban[]" class="form-control"></td>
                                  <td><input type="text" name="bswift[]" class="form-control"></td>
                                  <td><a class="btn btn-xs bg-orange" onclick="remove_row(0)"><span class="fa fa-trash"></span></a></td>
                              </tr>
                              <tr id="addr1"></tr>
                          </tbody>
                      </table>

                      <div class="form-group text-center">
                          <button type="submit" id="saveBtn" class="btn btn-success">Submit</button>
                      </div>

                  </form>
            </div>
        </div>
    </div>
  <div class="col-md-12 col-sm-12 ">
    
<script>
    let i = 1;
    $('#add_row').click(function () {
        $('#addr' + i).html(`
            <td><input type="text" name="bname[]" class="form-control"></td>
            <td><input type="text" name="bacc[]" class="form-control"></td>
            <td><input type="text" name="bbranch[]" class="form-control"></td>
            <td><input type="text" name="biban[]" class="form-control"></td>
            <td><input type="text" name="bswift[]" class="form-control"></td>
            <td><a class="btn btn-xs bg-orange" onclick="remove_row(${i})"><span class="fa fa-trash"></span></a></td>
        `);
        $('#tab_logic tbody').append(`<tr id="addr${i + 1}"></tr>`);
        i++;
    });

    function remove_row(rowId) {
        $('#addr' + rowId).remove();
    }

    const requiredFields = [
        'branch_code',
        'branch_manager',
        'branch_email',
        'branch_name',
        'branch_contact',
        'branch_location',
        'branch_trn'
    ];

    const requiredFiles = [
        'branch_logo',
        'branch_header',
        'branch_footer'
    ];

    $('form').on('submit', function (e) {
        e.preventDefault(); // Stop default form submission

        let formValid = true;
        let missingFields = [];

        // Validate regular input/select fields
        requiredFields.forEach(function (name) {
            let field = $('[name="' + name + '"]');
            if (!field.val().trim()) {
                formValid = false;
                missingFields.push(field.closest('.form-group').find('label').text());
            }
        });

        // Validate file inputs
        requiredFiles.forEach(function (name) {
            let fileInput = $('[name="' + name + '"]');
            if (fileInput.get(0).files.length === 0) {
                formValid = false;
                missingFields.push(fileInput.closest('.form-group').find('label').text());
            }
        });

        // Bank row check
        let hasBank = false;
        $('#tab_logic tbody tr').each(function () {
    const bankInput = $(this).find('input[name="bname[]"]');
    if (bankInput.length && bankInput.val() && bankInput.val().trim() !== '') {
        hasBank = true;
    }
});

        if (!hasBank) {
            formValid = false;
            missingFields.push("At least one Bank Account row");
        }

        if (!formValid) {
            alert("Please fill the following required fields:\n- " + missingFields.join("\n- "));
            return;
        }

        // Submit via AJAX
        const formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                alert('Branch saved successfully!');
                window.location.href = "<?= base_url('index.php/Company/list_branch') ?>";
            },
            error: function () {
                alert('Something went wrong while saving.');
            }
        });
    });

    document.getElementById("branch").addEventListener("submit", function (e) {

    var btn = document.getElementById("saveBtn");

    // Prevent multiple submissions
    if (btn.disabled) {
        e.preventDefault();
        return false;
    }

    // Disable immediately
    btn.disabled = true;
    btn.innerHTML = "Processing...";

});
</script>





