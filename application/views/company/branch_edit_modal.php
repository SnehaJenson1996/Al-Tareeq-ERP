<?php if (!empty($branch_data_by_id)): ?>   
    <div class="alert alert-danger validation-error" style="display:none;"></div>

<form id="updateBranch">
    <table class="table table-bordered">
        <thead class="table-info">
            <input type="hidden" name="branch_id" value="<?= $branch_data_by_id->branch_id ?>">

            <!-- Branch name -->
            <tr>
                <th>Date</th>
                <td colspan="4">
                    <input type="text" class="form-control form-control-sm col-sm-5" id="branch_name" name="branch_name" 
                           value="<?= $branch_data_by_id->branch_name?>" tabindex="4">
                    <label id="leave_exists" style="color: red; font-size: 0.8rem;"></label>
                </td>
            </tr>

            <!-- Branch Logo -->
            <tr>
                <th>Branch Logo</th>
                <td colspan="4"><input type="file" step='any' id="branch_logo" name="branch_logo" value=0  class="form-control "></td>
            </tr>

            <!-- Branch Address -->
            <tr>
                <th>Branch Address</th>
                <td colspan="4">
                    <textarea name="branch_address" id="branch_address"><?= $branch_data_by_id->branch_address ?></textarea>
                </td>
            </tr>

            
        </thead>
    </table>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
<?php else: ?>
    <p>No employees found for this selection.</p>
<?php endif; ?>
<script>

$(document).ready(function() {
    $('#updateBranch').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this); // collect file + all inputs

        $.ajax({
            url: "<?= base_url('index.php/Company/update_branch_data') ?>",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                    var res = JSON.parse(response);
                    if (res.status == 1) {
                         alert(res.message);
                         $('#updateBranchModal').modal('hide');
                        location.reload();
                    } else {
                        $('.validation-error').html(res.error).show();
                    }
            }
        });
    });
});


</script>


