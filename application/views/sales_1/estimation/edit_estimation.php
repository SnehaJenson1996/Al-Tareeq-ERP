<?php 
    $page_name='Sales/list_estimations';
	$page_name2='Sales/list_quotations';
	$user = $this->session->userdata('user_id');
?>
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
        
        <form id='myForm' action='<?php echo base_url().'index.php/'; ?>Sales/update_estimation_data' method='post' >
        <div class="x_content">
            <div class="item form-group">
                <label class="col-form-label col-md-2 col-sm-2 label-align" >Estimation code</label>
                <div class="col-md-3 col-sm-3 ">
                    <input type='text' name='estimation_code' class='form-control' readonly value='<?php echo $estimation['estimation_code'];?>' />
                </div>

                <label class="col-form-label col-md-1 col-sm-1 label-align" >Revision</label>
                <div class="col-md-3 col-sm-3 ">
                    <input type='text' name='estimation_revision' class='form-control' readonly value='<?php echo $estimation['estimation_revision'];?>' />
                </div>
            </div>
            <div class="item form-group">
                <input type='hidden' name='estimation_id' value='<?php echo $estimation['estimation_id']; ?>' />
                <input type='hidden' name='estimation_approval' id='estimation_approval' value='<?php echo $estimation['approval']; ?>' />
                <input type='hidden' name='latest_revision' value='<?php echo $estimation['estimation_revision']; ?>' />
                <input type='hidden' name='enquiry_id' value='<?php echo $estimation['enquiry_id']; ?>' />
                <input type='hidden' name='quotation_status' value='<?php echo empty($quotation_status)? 0 : 1; ?>' />
                <?php if(!empty($quotation_status)){?>
                    <input type='hidden' name='quotation_code' value='<?php echo $quotation_status['quotation_code']; ?>' />
                    <input type='hidden' name='quotation_revision' value='<?php echo $quotation_status['quotation_revision']; ?>' />
                    <input type='hidden' name='quoted_estimation_id' value='<?php echo $quotation_status['estimation_id']; ?>' />
                <?php } ?>
            </div>
                        
        </div>
        <div class="clearfix"></div>
        <div class="x_content" id='enquiry_details'>
            <ul class="stats-overview">
                <li style='width:20%'>
                    <span class="name"> Enquiry </span>
                    <span class="value text-success" id='enquiry_code'> <?php echo $estimation['enquiry_code'];?> </span>
                </li>
                <li style='width:20%'>
                    <span class="name"> Customer </span>
                    <span class="value text-success" id='enquiry_customer'> <?php echo $estimation['customer_name'];?> </span>
                </li>
                <li style='width:20%'>
                    <span class="name"> Enquiry date </span>
                    <span class="value text-success" id='enquiry_date'> <?php echo $estimation['enquiry_date'];?> </span>
                </li>
            
                <li role="presentation" class="dropdown" style='width:20%'>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">Attachments<span class="caret"></span></a>
                        <div class="dropdown-menu" id='attachment_dropdown' aria-labelledby="dropdownMenuButton">
                            <?php foreach($enquiry_files as $file){ ?>
                                <a class='dropdown-item' target='_blank' href=<?php echo base_url().'public/uploaded_documents/enquiry_files/'.$file->file_path ?> ><?php echo $file->file_title; ?></a>
                            <?php } ?>
                        </div>
                </li>
               
            
            </ul>
        </div>

        <div class="clearfix"></div>
        <div class='table-responsive-custom'>
            <div class="x_content" id='estimation_table' style='overflow: auto;'>

                        <table class="table-striped table-bordered" style="width:100%;font-size:10px;">
                            <thead>
                                <tr>
                                    <td style='width:2%'>
                                        Sl No
                                        <?php if($view_only==0){ ?><span class="value text-success"> <a href='javascript:add_row()' title='Add row' class='btn'><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>  </span><?php } ?>
                                    </td>
                                    <td style='width:10%'>Model</td>
                                    <td style='width:3%'>Brand</td>
                                    <td style='width:18%'>Description</td>
                                    <td style='width:5%'>Qty</td>
                                    <td style='width:5%'>Unit</td>
                                    <td style='width:3%'>AVE Stock</td>
                                    <td style='width:7%'>Actual Price</td>
                                    <td style='width:5%'>Disc1 (%)</td>
                                    <td style='width:5%'>Disc1 (AED)</td>
                                    <td style='width:5%'>Disc2 (%)</td>
                                    <td style='width:5%'>Disc2 (AED)</td>
                                    <td style='width:5%'>Unit Price</td>
                                    <td style='width:7%'>Taxable Amt</td>
                                    <td style='width:5%'>VAT </td>
                                    <td style='width:5%'>Total (AED)</td>
                                    <td style='width:5%'>Title</td>
                                </tr>
                            </thead>
                            <tbody id='mytbody'>
                                <?php $i=0;$subtotal=0;$additional_discount=0;$total_vat=0;
                                foreach($estimation_details as $detail){ ?>
                                <?php 
                                    $discount1_amount=0;$discount2_amount=0;$unit_price=0;$taxable_amount;$vat_amount=0;$total=0; 
                                    $vat_percent = $estimation['vat_percent']/100;
                                    $actual_price = $detail->actual_price;
                                    $discount1_percent = $detail->discount1_percent/100;  
                                    $discount2_percent = $detail->discount2_percent/100; 
                                    $quantity = $detail->quantity; 
                                    if($discount1_percent > 0)
                                        $discount1_amount = ($actual_price * $discount1_percent) * $quantity;
                                    if($discount2_percent > 0)
                                        $discount2_amount = (($actual_price * $quantity)-$discount1_amount) * $discount2_percent ;
                                    $unit_price = $actual_price - ($actual_price*$discount1_percent);
                                    $taxable_amount = $unit_price * $quantity;
                                    $vat_amount = $taxable_amount * $vat_percent;
                                    $total = $taxable_amount + $vat_amount;
                                    $subtotal += $taxable_amount;
                                    $additional_discount += $discount2_amount;
                                    $total_vat+=$vat_amount;
                                ?>
                                <tr id='<?php echo 'addr'.$i; ?>'>
                                    <td style='text-align:center'>
                                        <span class='serial-number'><?php echo $i+1; ?></span><a href='javascript:remove_row(<?= $i; ?>)' title='Remove row' class='btn'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></a>
                                    </td>
                                    <td>
                                        <select class="form-control item-select2" name="item[]" id='<?php echo 'item'.$i; ?>' onchange='get_item_by_id(<?php echo $i; ?>)' <?php $view_only??'disabled' ?>>
                                        <option value=''>Select</option>
                                        <option value='<?php echo $detail->item_id ?>'  selected><?php echo $detail->item_model; ?></option>
                                        
                                        </select>
                                        <input type='hidden' name='detail_id[]' id='<?php echo 'detail_id'.$i; ?>' value=<?php echo $detail->detail_id; ?> />
                                    </td>
                                    <td id='<?php echo 'brand'.$i; ?>'><span id='<?php echo 'brand_name'.$i; ?>'><?php echo $detail->brand_name; ?></span><input type='hidden' class='form-control' name='discount1_limit[]' id='<?php echo 'discount1_limit'.$i; ?>' value='<?php echo $detail->discount_limit; ?>' /></td>
                                    <td id='<?php echo 'description'.$i; ?>'><?php echo $detail->item_description; ?></td>
                                    <td><input type='text' class='form-control' name='quantity[]' id='<?php echo 'quantity'.$i; ?>' oninput='calculate_row_values(<?php echo $i; ?>)' value='<?php echo $detail->quantity; ?>' <?php $view_only??'readonly' ?> /></td>
                                    <td>
                                        <select class="form-control" name="unit[]" id='<?php echo 'unit'.$i; ?>' <?php $view_only??'disabled' ?>>
                                            <option value=''>Select</option>
                                            <?php foreach($active_units as $unit){ ?>
                                                <option value='<?php echo $unit->unit_id ?>' <?php if($detail->unit_id == $unit->unit_id) echo "selected"; ?>><?php echo $unit->unit_name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td id='<?php echo 'stock'.$i; ?>'><?php echo $detail->current_stock; ?></td>
                                    <td><input type='text' class='form-control' name='quote_price[]' id='<?php echo 'quote_price'.$i; ?>' value='<?php echo $actual_price; ?>' oninput='calculate_row_values(<?php echo $i; ?>)' <?php $view_only??'readonly' ?> /><input type='hidden' class='form-control' name='actual_price[]' id='<?php echo 'actual_price'.$i; ?>' value='<?php echo $detail->mrp_aed; ?>' <?php $view_only??'readonly' ?> /></td>
                                    <td><input type='text' class='form-control' name='discount1[]' id='<?php echo 'discount1_'.$i; ?>' oninput='calculate_row_values(<?php echo $i; ?>)' value='<?php echo $detail->discount1_percent; ?>' <?php $view_only??'readonly' ?> /></td>
                                    <td><input type='text' class='form-control' name='discount1_amount[]' id='<?php echo 'discount1_amount_'.$i; ?>' value='<?php echo $discount1_amount; ?>' <?php $view_only??'readonly' ?> /></td>
                                    <td><input type='text' class='form-control' name='discount2[]' id='<?php echo 'discount2_'.$i; ?>' oninput='calculate_row_values(<?php echo $i; ?>)' value='<?php echo $detail->discount2_percent; ?>' <?php $view_only??'readonly' ?> /></td>
                                    <td><input type='text' class='form-control discount2' name='discount2_amount[]' id='<?php echo 'discount2_amount_'.$i; ?>' value='<?php echo $discount2_amount; ?>' <?php $view_only??'readonly' ?> /></td>
                                    <td><input type='text' class='form-control' name='unit_price[]' id='<?php echo 'unit_price_'.$i; ?>' value='<?php echo $unit_price; ?>' <?php $view_only??'readonly' ?> /></td>
                                    <td><input type='text' class='form-control taxable_amount' name='taxable_amount[]' id='<?php echo 'taxable_amount_'.$i; ?>' value='<?php echo $taxable_amount; ?>' <?php $view_only??'readonly' ?> /></td>
                                    <td><input type='text' class='form-control vat_amount' name='vat_amount[]' id='<?php echo 'vat_amount_'.$i; ?>' value='<?php echo $vat_amount; ?>' <?php $view_only??'readonly' ?> /></td>
                                    <td><input type='text' class='form-control' name='total[]' id='<?php echo 'total_'.$i; ?>' value='<?php echo $total; ?>' <?php $view_only??'readonly' ?> /></td>
                                    <td><input type='text' class='form-control' name='section_title[]' value='<?php echo $detail->section_title; ?>' /></td>
                                </tr>   
                                <?php $i++; }?>      
                            </tbody>
                        </table>     
                        <input type='hidden' name='row_count' id='row_count' value=<?php echo $i; ?> />
                        <input type='hidden' name='next_id' id='next_id' value=<?php echo $i; ?> />
                        <select id="unit-options-template" style="display: none;">
                        <option value="">Select</option>
                        <?php foreach($active_units as $unit){ ?>
                            <option value="<?php echo $unit->unit_id ?>"><?php echo $unit->unit_name; ?></option>
                        <?php } ?>
                        </select>
                        <div class="clearfix"></div>
                        <br>
                        <div class="item form-group">
                            <div class="col-md-9"></div>
                            <label class="col-form-label col-md-1 col-sm-1 label-align" >Subtotal <span class="required">*</span></label>
                            <div class="col-md-2"><input type='text' name='subtotal' id='subtotal' class='form-control' value='<?php echo number_format($subtotal,2); ?>' readonly /></div>
                        </div>   
                        <div class="item form-group">
                            <div class="col-md-8"></div>
                            <label class="col-form-label col-md-2 col-sm-2 label-align" >Additional discount <span class="required">*</span></label>
                            <div class="col-md-2"><input type='text' name='total_discount2' id='total_discount2' class='form-control' readonly value='<?php echo number_format($additional_discount,2); ?>'/></div>
                        </div>
                        <div class="item form-group">
                            <div class="col-md-8"></div>
                            <label class="col-form-label col-md-1 col-sm-1 label-align" >VAT <span class="required">*</span></label>
                            <div class="col-md-1">
                                <select name='vat_percent' id='vat_percent' class='form-control' onchange='change_vat_percent()'><option value=5 <?php if($estimation['vat_percent']=='5') echo 'selected'; ?>>5%</option><option value=0 <?php if($estimation['vat_percent']=='0') echo 'selected'; ?> >0%</option></select>
                            </div>
                            <div class="col-md-2">
                            <input type='text' name='total_vat_amount' id='total_vat_amount' class='form-control' readonly value='<?php echo number_format($total_vat,2); ?>'/>
                            </div>
                        </div>
                        
                        <div class="item form-group">
                            <div class="col-md-8"></div>
                            <label class="col-form-label col-md-1 col-sm-1 label-align" >Total <span class="required">*</span></label>
                            <div class="col-md-1">
                                <select name='currency_id' id='currency_id' class='form-control' onchange='convert_currency()'><?php foreach($active_currencies as $currency){ ?><option value=<?php echo $currency->currency_id; ?> data-rate=<?php echo $currency->conversion_rate; ?> <?php if($estimation['currency']==$currency->currency_id) echo 'selected'; ?>><?php echo $currency->currency_abbr; ?></option><?php } ?></select>
                            </div>
                            <div class="col-md-2">
                                <input type='text' name='grand_total' id='grand_total' class='form-control' readonly value='<?php echo $estimation['grand_total'];?>' /></div>
                        </div>
            </div>
        </div>
        <div class="x_content" id='estimation_details' >
        <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >Customer Contact <span class="required">*</span></label>
                        <div class="col-md-7">
                        <select name='customer_contact' id='customer_contact' class='form-control' required >
                            <?php foreach($customer_contacts as $contact){ ?>
                                <option value='<?php echo $contact->contact_id;?>' <?php if($estimation['customer_contact_id'] == $contact->contact_id) echo 'selected'; ?>><?php echo $contact->contact_name;?></option>
                            <?php } ?>
                        </select>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >Notes</label>
                        <div class="col-md-7">
                        <textarea name='notes' rows=2 cols=25 class='form-control'><?php echo $estimation['notes']; ?></textarea>
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >PAYMENT <span class="required">*</span></label>
                        <div class="col-md-7">
                        <input type='text' name='payment' class='form-control'  value='<?php echo $estimation['payment']; ?>' required />
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >DELIVERY <span class="required">*</span></label>
                        <div class="col-md-7">
                        <input type='text' name='delivery' class='form-control'  value='<?php echo $estimation['delivery']; ?>' required />
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >AVAILABILITY </label>
                        <div class="col-md-7">
                            <input type='text' name='availability' class='form-control'  value='<?php echo $estimation['availability']; ?>' required />
                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >Warranty</label>
                        <div class="col-md-11 col-sm-11 ">
                            <input type='text' name='warranty' class='form-control'  value='<?php echo $estimation['warranty']; ?>' required />

                        </div>
                    </div>
                    <div class="item form-group">
                        <label class="col-form-label col-md-1 col-sm-1 label-align" >Conditions</label>
                        <div class="col-md-11 col-sm-11 ">
                        <textarea name='conditions' rows="6" cols="60" class='form-control' required ><?php echo $estimation['conditions']; ?></textarea>

                        </div>
                    </div>

                    <?php if($view_only==0){ ?>
                        <div class="item form-group">  
                            <div class="col-md-3"></div>
                            <div class="col-md-3">
                            <button type="button" onclick='revise_estimation()' class="btn btn-success btn-primary">Revise Estimation</button>
                            </div>   
                               
                            <div class="col-md-3">
                                <?php if($estimation['approval']  && has_access($user,$page_name2,'A')){ ?> 
                                    <?php if(empty($quotation_status)){?>
                                        <button type="button" onclick='add_quotation()' class="btn btn-success btn-primary quotation_buttons">Generate Quotation</button>
                                    <?php } else if($quotation_status['estimation_id'] != $estimation['estimation_id']){?>
                                        <?php if(has_access($user,$page_name2,'E')){ ?>
                                        <button type="button" onclick='revise_quotation()' class="btn btn-success btn-primary quotation_buttons">Revise Quotation</button>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                                <?php if(has_access($user,$page_name,'E')){ ?>
                                    <button type="submit" style='display:none' id='update_estimation' class="btn btn-success btn-primary">Save changes</button> 
                                <?php } ?>
                                <?php if($estimation['approval'] == 0){ ?>
                                <?php if(has_access($user,$page_name,'E')){ ?>
                                    <button type="button" onclick='approve_estimation()' id='approve_buttons' class="btn btn-success btn-primary">Approve Estimation</button>
                                <?php } ?>
                                <?php } ?>
                                
                            </div>
                            <div class="col-md-3">
                            
                            </div>
                            
                        </div>
                        
                        <?php } ?>
        </div>
                           
                       
           

        <div class="clearfix"></div>
        
        </form>                                
    

    

<script>
 $(document).ready(function () {
    const form = document.getElementById('myForm');
    let originalData = new FormData(form);
    const checkFormChanges = () => {
        const currentData = new FormData(form);
        for (let [key, value] of currentData.entries()) {
        if (value !== originalData.get(key)) {
            const btn1 = document.getElementById('update_estimation');
            if (btn1) {
                btn1.style.display = 'block';
            } 
            const qtn_btn = document.querySelectorAll('.quotation_buttons');
            qtn_btn.forEach(function(btn) {
                btn.style.display = 'none'; // Hides the button
            });

            const btn3 = document.getElementById('approve_buttons');
            if (btn3) {
                btn3.style.display = 'none';
            } 

            return;
        }
        }
    
    };

    // Listen for changes on all form elements
    form.addEventListener('input', checkFormChanges);
    form.addEventListener('change', checkFormChanges);
    $('.select2').on('change select2:select select2:unselect', function () {
        checkFormChanges(); // Direct call or trigger on form
    });
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
        var row_count = $('#row_count').val();
        row_count--;
        $('#row_count').val(row_count);
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
            $('#quote_price'+row_no).val('');
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
        var total = taxable_amount + vat_amount;
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

    function revise_estimation(){
        document.getElementById('myForm').action = "<?php echo base_url().'index.php/Sales/revise_estimation_data'; ?>";
        $('#myForm').submit();
    }

    function add_quotation(){
        document.getElementById('myForm').action = "<?php echo base_url().'index.php/Sales/add_quotation_data'; ?>";
        $('#myForm').submit();
    }

    function approve_estimation(){
        document.getElementById('myForm').action = "<?php echo base_url().'index.php/Sales/approve_estimation_data'; ?>";
        $('#myForm').submit();
    }

    function revise_quotation(){
        document.getElementById('myForm').action = "<?php echo base_url().'index.php/Sales/revise_quotation_data'; ?>";
        $('#myForm').submit();
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
      
            