<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_content">
                <br />

                <form action="<?php echo base_url().'index.php/'; ?>Item/add_brand_data" method="post" autocomplete='off' id="brand">
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Name <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="text" id="brand_name" name="brand_name" required="required"  class="form-control ">
                        </div>
                    </div> 
                    
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="first-name">Discount Limit(%)</label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="number" step='any' id="discount_limit" name="discount_limit" value=0  class="form-control ">
                        </div>
                    </div> 
                    
                    <div class="ln_solid"></div>
                    <div class="item form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
                            
                            <button type="submit" id="saveBtn" class="btn btn-success">Submit</button>
                        </div>
                    </div>  
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById("brand").addEventListener("submit", function (e) {

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
