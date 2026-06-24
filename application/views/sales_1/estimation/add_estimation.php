    <style>
        .form-control{
            padding:0 px;
        }
        .select2{
            font-size:0.8 rem;
        }
        .table-bordered{
            font-size:0.7 rem;
        }
        .table-responsive-custom {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch; /* for smooth scrolling on iOS */
    }

    .table-responsive-custom table {
        min-width: 1500px; /* or however wide your columns require */
    }
    body {
        font-family: "Franklin Gothic Book", "Franklin Gothic Medium", Arial, sans-serif;
        font-size:13px;
    }
    </style>
    <div class="clearfix"></div>
        
        <form action='<?php echo base_url().'index.php/'; ?>Sales/add_estimation_data' method='post' >
        <div class="x_content">
            <div class="item form-group">
                <label class="col-form-label col-md-1 col-sm-1 label-align" >Enquiry <span class="required">*</span></label>
                <div class="col-md-6 col-sm-6 ">
                    <select class="form-control" name="enquiry_id" id="enquiry_id" onchange='get_enquiry_by_id()'>
                        <option value="">Select Enquiry</option>
                        <?php foreach($enquiries_for_estimation as $enquiry) { ?>
                            <option value="<?php echo $enquiry->enquiry_id;?>"><?php echo $enquiry->enquiry_code.'('.$enquiry->customer_name.')';?></option>
                        <?php } ?>
                    </select>
                </div>
                
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="x_content" style='display:none;' id='enquiry_details'>
            <ul class="stats-overview">
                <li>
                    <span class="name"> Customer </span>
                    <span class="value text-success" id='enquiry_customer'>  </span>
                </li>
                <li>
                    <span class="name"> Enquiry date </span>
                    <span class="value text-success" id='enquiry_date'>  </span>
                </li>
            
                <li role="presentation" class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">Attachments<span class="caret"></span></a>
                        <div class="dropdown-menu" id='attachment_dropdown' aria-labelledby="dropdownMenuButton">
                        </div>
                </li>
            
            </ul>
        </div>

        <div class="clearfix"></div>
        <div class='table-responsive-custom'>
            <div class="x_content" id='estimation_table' style='display:none;'>
                <table class="table-striped table-bordered" style="width:100%;font-size:10px">
                            <thead>
                                <tr>
                                    <td style='width:2%'>Sl No<a href='javascript:add_row()' title='Add row' class='btn'><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a></td>
                                    <td style='width:10%'>Model</td>
                                    <td style='width:3%'>Brand</td>
                                    <td style='width:15%'>Description</td>
                                    <td style='width:5%'>Qty</td>
                                    <td style='width:5%'>Unit</td>
                                    <td style='width:3%'>AVE Stock</td>
                                    <td style='width:7%'>Actual Price</td>
                                    <td style='width:5%'>Disc1 (%)</td>
                                    <td style='width:5%'>Disc1 (AED)</td>
                                    <td style='width:5%'>Disc2 (%)</td>
                                    <td style='width:5%'>Disc2 (AED)</td>
                                    <td style='width:7%'>Unit Price</td>
                                    <td style='width:7%'>Taxable Amt</td>
                                    <td style='width:5%'>VAT </td>
                                    <td style='width:5%'>Total (AED)</td>
                                    <td style='width:10%'>Title</td>
                                </tr>
                            </thead>
                            <tbody id='mytbody'>
                                <tr id='addr0'>
                                    <td style='text-align:center'><span class='serial-number'>1</span><a href='javascript:remove_row(0)' title='Remove row' class='btn'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></a></td>
                                    <td>
                                        <select class="form-control item-select2" name="item[]" id='item0' onchange='get_item_by_id(0)'>
                                        <option value=''>Select</option>
                                        
                                        </select>
                                    </td>
                                    <td id='brand0'><span id='brand_name0'></span><input type='hidden' class='form-control' name='discount1_limit[]' id='discount1_limit0' /></td>
                                    <td id='description0'></td>
                                    <td><input type='text' class='form-control' name='quantity[]' id='quantity0' oninput=calculate_row_values(0) /></td>
                                    <td>
                                        <select class="form-control" name="unit[]" id='unit0'>
                                        <option value=''>Select</option>
                                        <?php foreach($active_units as $unit){ ?>
                                            <option value='<?php echo $unit->unit_id ?>'><?php echo $unit->unit_name; ?></option>
                                        <?php } ?>
                                        </select>
                                    </td>
                                    <td id='stock0'></td>
                                    <td><input type='text' class='form-control' name='quote_price[]' id='quote_price0' oninput=calculate_row_values(0) /><input type='hidden' class='form-control' name='actual_price[]' id='actual_price0' /></td>
                                    <td><input type='text' class='form-control' name='discount1[]' id='discount1_0' oninput=calculate_row_values(0) /></td>
                                    <td><input type='text' class='form-control' name='discount1_amount[]' id='discount1_amount_0' /></td>
                                    <td><input type='text' class='form-control' name='discount2[]' id='discount2_0' oninput=calculate_row_values(0) /></td>
                                    <td><input type='text' class='form-control discount2' name='discount2_amount[]' id='discount2_amount_0' /></td>
                                    <td><input type='text' class='form-control' name='unit_price[]' id='unit_price_0' /></td>
                                    <td><input type='text' class='form-control taxable_amount' name='taxable_amount[]' id='taxable_amount_0' /></td>
                                    <td><input type='text' class='form-control vat_amount' name='vat_amount[]' id='vat_amount_0' /></td>
                                    <td><input type='text' class='form-control' name='total[]' id='total_0' /></td>
                                    <td><input type='text' class='form-control' name='section_title[]' value='' /></td>
                                </tr>
                                                
                            </tbody>
                </table>     
                <input type='hidden' name='row_count' id='row_count' value=1 />
                <input type='hidden' name='next_id' id='next_id' value=1 />

                <select id="unit-options-template" style="display: none;">
                        <option value="">Select</option>
                        <?php foreach($active_units as $unit){ ?>
                            <option value="<?php echo $unit->unit_id ?>"><?php echo $unit->unit_name; ?></option>
                        <?php } ?>
                </select>
                <div class="clearfix"></div>
                <br>

                <div class="item form-group">
                        
                            <div class="col-md-8"></div>
                            <label class="col-form-label col-md-1 col-sm-1 label-align" >Subtotal <span class="required">*</span></label>
                            <div class="col-md-2"><input type='text' name='subtotal' id='subtotal' class='form-control' readonly /></div>
                </div>   
                <div class="item form-group">
                            <div class="col-md-7"></div>
                            <label class="col-form-label col-md-2 col-sm-2 label-align" >Additional discount <span class="required">*</span></label>
                            <div class="col-md-2"><input type='text' name='total_discount2' id='total_discount2' class='form-control' readonly /></div>
                </div>
                <div class="item form-group">
                            <div class="col-md-8"></div>
                            <label class="col-form-label col-md-1 col-sm-1 label-align" >VAT <span class="required">*</span></label>
                            <div class="col-md-1">
                                <select name='vat_percent' id='vat_percent' class='form-control' onchange='change_vat_percent()'><option value=5>5%</option><option value=0>0%</option></select>
                            </div>
                            <div class="col-md-1">
                            <input type='text' name='total_vat_amount' id='total_vat_amount' class='form-control' readonly />
                            </div>
                            
                </div>
                <div class="item form-group">
                            <div class="col-md-8"></div>
                                
                            <label class="col-form-label col-md-1 col-sm-1 label-align" >Total <span class="required">*</span></label>
                            <div class="col-md-1">
                                <select name='currency_id' id='currency_id' class='form-control' onchange='convert_currency()'><?php foreach($active_currencies as $currency){ ?><option value=<?php echo $currency->currency_id; ?> data-rate=<?php echo $currency->conversion_rate; ?>><?php echo $currency->currency_abbr; ?></option><?php } ?></select>
                            </div>
                            <div class="col-md-1">
                                <input type='text' name='grand_total' id='grand_total' class='form-control' readonly />
                            </div>
                </div>
            </div>
        </div>
        <div class="x_content" id='estimation_details' style='display:none;'>
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >Customer Contact <span class="required">*</span></label>
                        <div class="col-md-7">
                        <select name='customer_contact' id='customer_contact' class='form-control' required >
                            
                        </select>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >Notes <span class="required">*</span></label>
                        <div class="col-md-7">
                        <textarea name='notes' rows=2 cols=25 class='form-control' required>Charges are given for Supply only. Cable , Network components, Rack and fixed connectors are excluded.</textarea>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >PAYMENT <span class="required">*</span></label>
                        <div class="col-md-7">
                        <input type='text' name='payment' class='form-control'  value='ADVANCE PAYMENT' required />
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >DELIVERY <span class="required">*</span></label>
                        <div class="col-md-7">
                        <input type='text' name='delivery' class='form-control'  value='DUBAI' required />
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >AVAILABILITY </label>
                        <div class="col-md-7">
                            <input type='text' name='availability' class='form-control'  value='AVAILABILITY SUBJECT TO THE PRIOR SALES AND FACTORY STOCK' required />
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >Warranty</label>
                        <div class="col-md-11 col-sm-11 ">
                            <input type='text' name='warranty' class='form-control'  value='STANDARD MANUFACTURER WARRANTY' required />

                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >Conditions</label>
                        <div class="col-md-11 col-sm-11 ">
                        <textarea name='conditions' rows="6" cols="60" class='form-control' required >1. Any changes to the existing duties, taxes or levies at time of billing will be charged additional.
2. Above quotation is based on the specifications given. Any variation on the quantities and design will affect the total cost and will be charged accordingly.
3. At the time of invoicing, any duties, taxes or levies introduced by the UAE government including but not limited to VAT will be charged on actuals.
4. Cheque should be issued under the name of "AVENGER ELECTRONICS LLC".
                        </textarea>

                        </div>
                    </div>
                        <br>
                    <div class="item form-group">  
                        <div class="col-md-6"> 
                        <input type='hidden' name='enquiry_code' id='enquiry_code' value='' />
                        </div>         
                        <div class="col-md-6">
                        <button type="submit" class="btn btn-success btn-primary">Save</button>
                        </div>
                    </div>
        </div>
        </div>

        <div class="clearfix"></div>
        </div>
        </form>                                
    

    

<script>
    $(document).ready(function () {
    let newSelect = $('#item0');
    initializeSelect2(newSelect);
});
  
 var unitOptions = `<option value="">Select</option>`;
  <?php foreach ($active_units as $unit): ?>
    unitOptions += `<option value="<?= $unit->unit_id ?>"><?= $unit->unit_name ?></option>`;
  <?php endforeach; ?>
  
  function add_row(){

    var row_count = $('#row_count').val();
    var i = parseInt($('#next_id').val());
    // Build the row HTML
    let rowContent = `
        <tr id='addr${i}'>
            <td style='text-align:center'><span class='serial-number'></span><a href='javascript:remove_row(${i})' title='Remove row' class='btn'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></a></td>
            <td>
                <select class='form-control item-select2' name='item[]' id='item${i}' onchange='get_item_by_id(${i})'>
                </select>
            </td>
            <td id='brand${i}'>
                <span id='brand_name${i}'></span>
                <input type='hidden' class='form-control' name='discount1_limit[]' id='discount1_limit${i}' />
            </td>
            <td id='description${i}'></td>
            <td><input type='text' class='form-control' name='quantity[]' id='quantity${i}' oninput='calculate_row_values(${i})' /></td>
            <td>
                <select class='form-control' name='unit[]' id='unit${i}'>
                    ${unitOptions}
                </select>
            </td>
            <td id='stock${i}'></td>
            <td>
                <input type='text' class='form-control' name='quote_price[]' id='quote_price${i}' oninput='calculate_row_values(${i})' />
                <input type='hidden' class='form-control' name='actual_price[]' id='actual_price${i}' />
            </td>
            <td><input type='text' class='form-control' name='discount1[]' id='discount1_${i}' oninput='calculate_row_values(${i})' /></td>
            <td><input type='text' class='form-control' name='discount1_amount[]' id='discount1_amount_${i}' /></td>
            <td><input type='text' class='form-control' name='discount2[]' id='discount2_${i}' oninput='calculate_row_values(${i})' /></td>
            <td><input type='text' class='form-control discount2' name='discount2_amount[]' id='discount2_amount_${i}' /></td>
            <td><input type='text' class='form-control' name='unit_price[]' id='unit_price_${i}' /></td>
            <td><input type='text' class='form-control taxable_amount' name='taxable_amount[]' id='taxable_amount_${i}' /></td>
            <td><input type='text' class='form-control vat_amount' name='vat_amount[]' id='vat_amount_${i}' /></td>
            <td><input type='text' class='form-control' name='total[]' id='total_${i}' /></td>
            <td><input type='text' class='form-control' name='section_title[]' value='' /></td>
        </tr>
    `;

    $('#mytbody').append(rowContent);
    
    const $newSelect = $('#item' + i);

    setTimeout(() => {
    if ($newSelect.length && !$newSelect.hasClass('select2-hidden-accessible')) {
        initializeSelect2($newSelect);
    }
    }, 0);
     row_count++;
    $('#row_count').val(row_count);
    i++;
    $('#next_id').val(i);
    updateSerialNumbers();
  }

  function remove_row(row){
    $('#addr'+row).remove();
    updateSerialNumbers();
    calculate_subtotal();
    calculate_total_additional_discount();
    calculate_total_vat_amount();
    calculate_grand_total();
  }
  
    function updateSerialNumbers() {
    const rows = document.querySelectorAll("#mytbody tr");
    rows.forEach((row, index) => {
		
        if(row.querySelector(".serial-number")){
			row.querySelector(".serial-number").textContent = index + 1;
		}
    });
    
    }

    function get_enquiry_by_id(){
        var enquiry_id = $('#enquiry_id').val();
        if(enquiry_id != ''){
            $.ajax({
                url: '<?= base_url("index.php/Sales/get_enquiry_by_id") ?>', 
                type: 'POST',
                data: { enquiry_id: enquiry_id },
                dataType:"json",
                success: function(response) {
                    var base_url = "<?php echo base_url(); ?>";
                    
                    $('#enquiry_customer').text(response.enquiry_details.customer_name);
                    $('#enquiry_date').text(response.enquiry_details.enquiry_date);
                     $('#enquiry_code').val(response.enquiry_details.enquiry_code);
                    const container = $('#attachment_dropdown'); 

                    response.enquiry_files.forEach(file => {
                        const aTag = `<a class="dropdown-item" target='_blank' href="${base_url}public/uploaded_documents/enquiry_files/${file.file_path}" >${file.file_title}</a><br>`;
                        container.append(aTag); 
                    });
                    const container2 = $('#customer_contact'); 

                    response.customer_contacts.forEach(contact => {
                        const option = `<option value="${contact.contact_id}" >${contact.contact_name}</a><br>`;
                        container2.append(option); 
                    });
                    $('#enquiry_details').show();
                    $('#estimation_table').show();
                    $('#estimation_details').show();
                    initializeSelect2($('#item0'));
                    
                }
            });
        }
    }

    function get_item_by_id(row_no){
        var item_id = $('#item'+row_no).val();
        
        if(item_id != ''){
            $.ajax({
                url: '<?= base_url("index.php/Item/get_item_by_id") ?>', 
                type: 'POST',
                data: { item_id: item_id },
                dataType:"json",
                success: function(response) {
                    var quote_price = parseFloat(response.mrp_aed);
                    var conversion_rate = parseFloat($('#currency_id option:selected').data('rate'));
                    if(conversion_rate != 1){
                        var quote_price = quote_price * conversion_rate;
                    }
                    $('#brand_name'+row_no).text(response.brand_name);
                    $('#discount1_limit'+row_no).val(response.discount_limit);
                    $('#description'+row_no).text(response.item_description);
                    $('#unit'+row_no).val(response.item_unit).change();
                    $('#quote_price'+row_no).val(quote_price.toFixed(2));
                    $('#actual_price'+row_no).val(response.mrp_aed);
                    $('#unit'+row_no).prop('required',true);
                    $('#stock'+row_no).text(response.stock);
                    $('#quantity'+row_no).prop('required',true);
                    $('#actual_price'+row_no).prop('required',true);
                    $('#quantity'+row_no).prop('required',true);
                    var nextRow = document.getElementById('addr'+row_no).nextElementSibling;
                    
                    if(!nextRow ) 
                        add_row();
                    
                }
            });
        }
        else{
            $('#brand_name'+row_no).text('');
            $('#discount1_limit'+row_no).val(0);
            $('#description'+row_no).text('');
            $('#unit'+row_no).val('').change();
            $('#actual_price'+row_no).val('');
            $('#unit'+row_no).prop('required',false);
            $('#quantity'+row_no).prop('required',false);
            $('#actual_price'+row_no).prop('required',false);
            $('#quantity'+row_no).prop('required',false);
        }
    }

    function calculate_row_values(row_no){
        var quantity = $('#quantity'+ row_no).val() || 0;
        var quote_price = $('#quote_price'+row_no).val() || 0;        
        var discount1_percent = $('#discount1_'+row_no).val() || 0;
        var discount2_percent = $('#discount2_'+row_no).val() || 0;
        var vat_percent = $('#vat_percent').val();

        //calculaitng unit price
        var unit_price = quote_price - (quote_price*(discount1_percent/100));
        $('#unit_price_'+row_no).val(unit_price.toFixed(2));

        //calculating discount1
        var discount1_amount = quote_price * (discount1_percent/100) * quantity;
        $('#discount1_amount_'+row_no).val(discount1_amount.toFixed(2));

         //calculating discount2
        var total = (quote_price * quantity) - discount1_amount;
        var discount2_amount = total * (discount2_percent/100);
        $('#discount2_amount_'+row_no).val(discount2_amount.toFixed(2));

        //calculating taxable amount
        var taxable_amount = unit_price * quantity;
        $('#taxable_amount_'+row_no).val(taxable_amount.toFixed(2));

        //calculating vat
        var vat_amount = ((quote_price * quantity)-(discount1_amount + discount2_amount))*(vat_percent/100);
        $('#vat_amount_'+row_no).val(vat_amount.toFixed(2));

        //calculate total
        var total = parseFloat(taxable_amount) + parseFloat(vat_amount);
        $('#total_'+row_no).val(total.toFixed(2));

        calculate_subtotal();
        calculate_total_additional_discount();
        calculate_total_vat_amount();
        calculate_grand_total();
    }

    function calculate_subtotal(){
        var i_value=0;i_total=0;
        $('.taxable_amount').each(function()
        {
            i_value=$(this).val();
            if(i_value=='')
                i_value = 0;
            else{
                i_total+=parseFloat(i_value);
            }
                
        });
        $('#subtotal').val(i_total.toFixed(2));
    }

    function calculate_total_additional_discount(){
        var i_value=0;i_total=0;
        $('.discount2').each(function()
        {
            i_value=$(this).val();
            if(i_value=='')
                i_value = 0;
            else{
                i_total+=parseFloat(i_value);
            }
                
        });
        $('#total_discount2').val(i_total.toFixed(2));
    }

    function calculate_total_vat_amount(){
        var i_value=0;i_total=0;
        $('.vat_amount').each(function()
        {
            i_value=$(this).val();
            if(i_value=='')
                i_value = 0;
            else{
                i_total+=parseFloat(i_value);
            }
                
        });
        $('#total_vat_amount').val(i_total.toFixed(2));
    }

    function calculate_grand_total(){
        var subtotal = parseFloat($('#subtotal').val());
        var total_discount2 = parseFloat($('#total_discount2').val());
        var vat_amount = parseFloat($('#total_vat_amount').val());
        var grand_total = (subtotal - total_discount2)+vat_amount;
        
        $('#grand_total').val(grand_total.toFixed(2));
    }

    function change_vat_percent(){
        var vat_percent = $('#vat_percent').val();
        var row_count = $('#row_count').val();
        
        for(var i = 0 ; i < row_count ; i++){
            var taxable_amount = $('#taxable_amount_'+i).val();
            if(taxable_amount > 0){
                var vat_amount = taxable_amount * (vat_percent/100);
                var total = parseFloat(taxable_amount) + parseFloat(vat_amount);
                $('#vat_amount_'+i).val(vat_amount);
                $('#total_'+i).val(total);
            }
        }
        calculate_total_vat_amount();
        calculate_grand_total();
    }

    function convert_currency(){
        var conversion_rate = parseFloat($('#currency_id option:selected').data('rate'));
        var row_count = $('#row_count').val();

        for(var i=0; i<row_count; i++){
            var actual_price = parseFloat($('#actual_price'+i).val()||0);
            var quote_price = actual_price * conversion_rate;
            $('#quote_price'+i).val(quote_price.toFixed(2));
            calculate_row_values(i);
        }
    }
</script>   
      
            