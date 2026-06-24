<div class="x_panel">
    <div class="x_title">
        <h2>Bank Reconciliation</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
        </ul>
        <div class="clearfix"></div>
    </div>

    <div class="x_content">
        <form class="form-horizontal form-label-left" 
              action="<?php echo base_url().'index.php/Accounts/view_bank_reconciliation'; ?>" 
              id="receipt" 
              method="post" 
              name="receipt" 
              autocomplete="off">

            <!-- Row 1: Account & Date Range -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Select Account: <span class="text-danger">*</span></label>
                <div class="col-sm-2">
                    <select tabindex="1" class="form-select form-control-sm select2" id="account_id" name="account_id" required onchange="get_doc_list()">
                        <option value="">Select Code</option>
                        <?php foreach($account_ledgers as $s) { ?>
                        <option value="<?php echo $s->account_id; ?>" <?php if($s->account_id==$account_id) echo 'selected'; ?>>
                            <?php echo $s->account_name; ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                 </div>
 <div class="form-group row">
                <label class="col-sm-2 col-form-label">From:</label>
                <div class="col-sm-2">
                    <input type="date" class="form-control form-control-sm" id="from_date" name="from_date" onchange="get_doc_list()">
                </div>

                <label class="col-sm-2 col-form-label">To:</label>
                <div class="col-sm-2">
                    <input type="date" class="form-control form-control-sm" id="to_date" name="to_date" onchange="get_doc_list()">
                </div>
            </div>

            <!-- Document List (dynamic) -->
            <div class="form-group row" id="reco_list">
                <!-- AJAX content will be loaded here -->
            </div>

            <!-- Remarks -->
            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Remarks:</label>
                <div class="col-sm-6">
                    <textarea id="remark" name="remark" rows="2" class="form-control form-control-sm" placeholder="Remark" tabindex="5"></textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-group row">
                <div class="col-sm-2"></div>
                <div class="col-sm-10">
                    <button type="submit" id="add" class="btn btn-primary">Submit</button>
                </div>
            </div>

        </form>
    </div>
</div>


<script>
function get_doc_list()
{
	var account_id=document.getElementById('account_id').value;

    var from_date=document.getElementById('from_date').value;
    var to_date=document.getElementById('to_date').value;
	if(account_id!='')
	{
		$.ajax
		({
			url: "<?php echo site_url('Ajax/get_reco_list'); ?>",
			type: 'POST',
			 data: {
                account_id: account_id,
                from_date: from_date,
                to_date: to_date
            },
			success: function(msg) {
				document.getElementById('reco_list').innerHTML=msg;
			}
		});
	}
	else
	{
		document.getElementById('reco_list').innerHTML='';
	}
}

</script>

































	</div>
<script>

    $(document).ready(function () {
    $('.select2').select2({
        width: '100%',
        placeholder: "Select Account",
        allowClear: true
    });
});
</script>
