<div class="container-fluid">

    <div class="card">
       

        <div class="card-body">

            <form method="post" action="<?= base_url('index.php/Sales/save_warranty') ?>">

                <div class="row">

                    <div class="col-md-4">
                        <label>Invoice No <span class="text-danger">*</span></label>
                        <select class="form-control" name="invoice_id" id="invoice_id" required>
                            <option value="">Select Invoice</option>
                            <?php foreach($invoice_list as $row){ ?>
                                <option value="<?= $row->invoice_id ?>">
                                    <?= $row->invoice_code ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Customer Name</label>
                        <input type="text" class="form-control" name="customer_name" id="customer_name" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Invoice Date</label>
                        <input type="text" class="form-control" name="invoice_date" id="invoice_date" readonly>
                    </div>

                </div>

                <br>

                <div class="row">

                    <div class="col-md-6">
                        <label>Site Location</label>
                        <textarea class="form-control" rows="3" name="site_location" id="site_location" readonly></textarea>
                    </div>

                    <div class="col-md-3">
                        <label>Installation Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="installation_date" required>
                    </div>

                    <div class="col-md-3">
                        <label>Warranty Period <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control"
                               name="warranty_period"
                               placeholder="Eg: 1 Year"
                               required>
                    </div>

                </div>

                <br>

                <div class="row">
                    <div class="col-md-12">
                        <label>Terms & Conditions</label>

                        <textarea class="form-control editor"
                                  name="terms_conditions"
                                  id="terms_conditions"
                                  rows="10">

However, warranty does not cover: <br>

• Fault of the device caused due to imprudence and/or negligence by third parties.<br>

• Physical damage.<br>

• Misuse or mishandling of the equipment installed.<br>

• Electrical issues such as high voltage supply input, short circuit, rapid voltage fluctuations, electrical spikes, earth faults, etc.<br>

• Force majeure events including earthquakes, floods, cyclones, thunder and lightning strikes.<br>

• Pests / rodent attack.<br>

• Any work external to the installed equipment.<br>

• Repair or damage resulting from accidents, transportation, neglect or misuse.<br>

• Breakdown resulting from failure of power supply wherein conditions are not maintained as per equipment specifications.<br>

• Nuclear reaction or radioactive contamination.

                        </textarea>
                    </div>
                </div>

                <br>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                        Save Warranty Certificate
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
<script src="https://cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>

<script>
$(document).ready(function(){

    $('#invoice_id').change(function(){

        var invoice_id = $(this).val();

        if(invoice_id != '')
        {
            $.ajax({
url: "<?= base_url('index.php/Sales/get_invoice_warranty_details') ?>",
                type : "POST",
                data : {invoice_id:invoice_id},
                dataType : "json",
                success : function(data)
                {
                    $('#customer_name').val(data.customer_name);
                    $('#invoice_date').val(data.invoice_date);
                    $('#site_location').val(data.site_location);
                }
            });
        }
    });

});

$(document).ready(function () {

    CKEDITOR.replace('terms_conditions');

});
</script>



