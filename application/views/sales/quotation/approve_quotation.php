<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_content">
                <form action="<?php echo base_url().'index.php/'; ?>Sales/save_quotation_approval" method="post" autocomplete='off' enctype="multipart/form-data">
                    <div class="item form-group">
                        <label class="col-form-label col-md-2 col-sm-1 label-align" for="quotation_code">Quotation Reference <span class="required">*</span></label>
                        <div class="col-md-5 col-sm-4 ">
                            <input type="text" id="quotation_code" name="quotation_code" readonly value='<?php echo $quotation[0]['quotation_code']; ?>' class="form-control" />
                            <input type="hidden" id="quotation_id" name="quotation_id" value='<?php echo $quotation[0]['qtn_id']; ?>' />
                        </div>
                    </div> 

                    <div class="item form-group">
                        <label class="col-form-label col-md-2 col-sm-1 label-align" for="lpo_date">LPO Date <span class="required">*</span></label>
                        <div class="col-md-5 col-sm-5 ">
                            <input type="date" id="lpo_date" name="lpo_date" required="required" value=<?php echo date('Y-m-d'); ?> class="form-control ">
                        </div>
                    </div>
                    
                    <div class="item form-group">
                        <label class="col-form-label col-md-2 col-sm-1 label-align" for="lpo_number">LPO Reference</label>
                        <div class="col-md-6 col-sm-6 ">
                            <input type='text' id="lpo_number" name="lpo_number"  required="required"  class="form-control " />
                        </div>
                    </div>  

                    <div class="item form-group">
                        <label class="col-form-label col-md-2 col-sm-1 label-align" for="lpo_total">LPO Total</label>
                        <div class="col-md-6 col-sm-6 ">
                            
                            <input type=number step=any  id="lpo_total" name="lpo_total" required="required" value='' class="form-control" />
                        </div>
                    </div> 

                    <div class="item form-group">
                        <label class="col-form-label col-md-2 col-sm-1 label-align" for="lpo_total">Remarks</label>
                        <div class="col-md-6 col-sm-6 ">
                            <textarea id="approval_remarks" name="approval_remarks" class="form-control "></textarea>
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

