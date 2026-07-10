<div class="card">
    <div class="card-header">
        <h4>Commission Transaction</h4>
    </div>

    <div class="card-body">
        <form method="post"
            action="<?php echo base_url(); ?>index.php/Hr/save_commission_transaction">
            <div class="row">
                <div class="col-md-6">
                    <label>Invoice</label>
                    <select id="invoice_id" name="invoice_id" class="form-control" required>
                        <option value="">Select Invoice</option>
                        <?php
                        foreach ($invoice_list as $row) {
                        ?>
                            <option value="<?php echo $row->invoice_id; ?>">
                                <?php echo $row->invoice_code; ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>

                </div>

                <div class="col-md-6">

                    <label>Sales Rep</label>

                    <select id="sales_rep_id" name="sales_rep_id" class="form-control" required>

                        <option value="">Select Sales Rep</option>

                        <?php

                        foreach ($sales_rep as $row) {

                        ?>

                            <option value="<?php echo $row->sales_rep_id; ?>">

                                <?php echo $row->sales_rep_name; ?>

                            </option>

                        <?php

                        }

                        ?>

                    </select>

                </div>

            </div>

            <br>

            <div class="row">

                <div class="col-md-4">
                    <label>Invoice Amount</label>
                    <input type="text" id="invoice_amount" name="invoice_amount" class="form-control" readonly>
                </div>

                <div class="col-md-4">
                    <label>Commission %</label>
                    <input type="text" id="commission_percent" name="commission_percent" class="form-control" readonly>
                </div>

                <div class="col-md-4">
                    <label>Commission Amount</label>
                    <input type="text" id="commission_amount" name="commission_amount" class="form-control" readonly>
                </div>

            </div>

            <br>

            <div class="row">

                <div class="col-md-4">

                    <label>Eligible Date</label>

                    <input type="date"

                        name="eligible_date"

                        class="form-control">

                </div>

                <div class="col-md-4">

                    <label>Status</label>

                    <select name="status"

                        class="form-control">

                        <option>Pending</option>

                        <option>Eligible</option>

                        <option>Approved</option>

                        <option>Paid</option>

                        <option>Rejected</option>

                    </select>

                </div>

            </div>

            <br>

            <label>Remarks</label>

            <textarea name="remarks"

                class="form-control"></textarea>

            <br>

            <button class="btn btn-success">

                Save

            </button>

            <a href="<?php echo base_url(); ?>index.php/Hr/view_commission_transaction_list"

                class="btn btn-secondary">

                Cancel

            </a>

        </form>

    </div>

</div>

<script>

    function calculateCommission()
    {
        var invoice=parseFloat($("#invoice_amount").val())||0;
        var percent=parseFloat($("#commission_percent").val())||0;
        var commission=(invoice*percent)/100;
        $("#commission_amount").val(commission.toFixed(2));
    }

    $("#invoice_id").change(function(){
        $.ajax({
            url:"<?=base_url()?>index.php/Ajax/ajax_get_invoice_details",
            type:"POST",
            data:{
                invoice_id:$(this).val()
            },
            dataType:"json",
            success:function(res){
                $("#invoice_amount").val(res.grand_total);
                calculateCommission();
            }
        });
    });

    $("#sales_rep_id").change(function(){
        $.ajax({
            url:"<?=base_url()?>index.php/Ajax/ajax_get_sales_rep_details",
            type:"POST",
            data:{
                sales_rep_id:$(this).val()
            },
            dataType:"json",
            success:function(res){
                $("#commission_percent").val(res.commission_percent);
                calculateCommission();
            }
        });
    });

</script>