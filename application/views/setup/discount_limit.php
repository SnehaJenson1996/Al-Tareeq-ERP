<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_content">
                <br />

                <form action="<?php echo base_url().'index.php/'; ?>Setup/set_discount_limit" method="post" autocomplete='off'>
                    <div class="item form-group">
                        <label class="col-form-label col-md-3 col-sm-3 label-align" for="discount_limit">Discount Limit(%) <span class="required">*</span></label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type="number" step='any' id="discount_limit" name="discount_limit" required="required" value='<?php echo $discount_limit['setting_value']; ?>' class="form-control ">
                        </div>
                    </div> 
                    
                    
                    
                    <div class="ln_solid"></div>
                    <div class="item form-group">
                        <div class="col-md-6 col-sm-6 offset-md-3">
                            
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </div>  
                </form>
            </div>
        </div>
    </div>
</div>