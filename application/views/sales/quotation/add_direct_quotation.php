<style>
    label,
    h4 {
        color: black;
        font-weight: bold;
    }

    .ck-editor__editable_inline {
        min-height: 250px;
        /* approximately 10 rows */
    }
    .ck-editor__editable {
    min-height: 80px !important; 
  }  /* Reduce height */
/* Reduce CKEditor Width */
.product_editor + .ck-editor {
    max-width: 300px !important;   /* 👈 Change width here */
}

.product_editor + .ck-editor .ck-editor__editable {
    width: 100% !important;
}
</style>
<div class="clearfix"></div>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">
            <div class="x_title">
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-check-circle"></i></strong>
                        <?= $this->session->flashdata('success'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-exclamation-circle"></i></strong>
                        <?= $this->session->flashdata('error'); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form action="<?= base_url() ?>index.php/Sales/save_direct_quotation" method="post">
                    <input type="hidden" name="quotation_type" value="DIRECT">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row align-items-center"> <label class="col-sm-4 col-form-label">Select Branch:</label>
                                <div class="col-sm-8"> <select name="quotation_branch_id" id="quotation_branch_id" class="form-control select2">
                                        <option value=''>Select</option> <?php foreach ($branch_list as $branch): ?> <option value="<?= $branch->branch_id ?>"><?= $branch->branch_name ?></option> <?php endforeach; ?>
                                    </select> </div>
                            </div>
                        </div>

                       <div class="col-md-6">
                            <div class="form-group row align-items-center"> <label class="col-sm-4 col-form-label">Project Name:</label>
                                <div class="col-sm-8"> <input type="text" id="project_name" name="project_name" value="<?= isset($enquiry_data['project_name']) ? $enquiry_data['project_name'] : "" ?>" class="form-control"> </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row align-items-center"> <label class="col-sm-4 col-form-label">Select Customer:</label>
                                <div class="col-sm-8"> <select name="quotation_customer" id="quotation_customer" class="form-control select2" required> 
                                  <option value=''>Select</option> <?php foreach ($customer_list as $c): ?> <option value="<?= $c->customer_id ?>"><?= $c->customer_name ?></option> <?php endforeach; ?>

                                </select> </div>
                            </div>
                        </div> 
                        <div class="col-md-6">
                         <div class="form-group row align-items-center">
                           <label class="col-sm-4 col-form-label">Project Location:</label>
                            <div class="col-sm-8">
                                 <input type="text" name="project_location" id="project_location"
                                  value="<?= isset($enquiry_data['project_location']) ? $enquiry_data['project_location'] : '' ?>"
                                  class="form-control" placeholder="Enter Project Location">
                            </div>
                          </div>

</div>
                    </div>
                    <div class="row"> <!-- Quotation Code -->
                        <div class="col-md-6">
                            <div class="form-group row align-items-center"> <label class="col-sm-4 col-form-label">Quotation Code:</label>
                                <div class="col-sm-8"> <input type="text" id="quotation_code" name="quotation_code" value="<?= isset($quotation_code) ? $quotation_code : "" ?>" class="form-control" readonly> </div>
                            </div>
                        </div> <!-- Quotation Date -->
                        <div class="col-md-6">
                            <div class="form-group row align-items-center"> <label class="col-sm-4 col-form-label">Quotation Date:</label>
                                <div class="col-sm-8"> <input type="date" id="quotation_date" name="quotation_date" value="<?= date('Y-m-d') ?>" class="form-control" required> </div>
                            </div>
                        </div>
                    </div> 
                    
                   

<table class="table table-bordered">
    <thead>
        <tr>
            <th width="35">#</th>
            <th>Item</th>
            <th width="100">Qty</th>
            <th width="150">Price</th>
            <th width="150">Amount</th>
            <th width="120">
    <button type="button" 
            class="btn btn-success btn-xs"
            id="addNewItem">
        <i class="fa fa-plus"></i>
    </button>
</th>
        </tr>
    </thead>

    <tbody id="selectedCartItems">

    <?php if(!empty($cart_items)) { ?>

<?php 
$i = 1;
foreach($cart_items as $item) { 
?>
        <tr>


<td>
    <?= $i++ ?>
</td>

            <td>
                <?= $item->product_name ?>

                <input type="hidden"
                       name="item_id[]"
                       value="<?= $item->product_id ?>">
                       <input type="hidden"
       name="product_name[]"
       value="<?= $item->product_name ?>">
            </td>

            <td>
                <input type="number"
                       class="form-control form-control-sm cart_qty"
                       name="qty[]"
                       value="<?= $item->qty ?>"
                       style="width:70px">
            </td>

            <td>
                <input type="number"
                       class="form-control form-control-sm cart_price"
                       name="price[]"
                       value="<?= $item->price ?>"
                       step="0.01"
                       style="width:100px">
            </td>

            <td>

                <span class="amount_display">
                    <?= number_format($item->amount,2) ?>
                </span>

                <input type="hidden"
                       class="amount_input"
                       name="amount[]"
                       value="<?= $item->amount ?>">

            </td>

            <td>

                <button type="button"
                        class="btn btn-danger btn-sm removeCartItem">
                    <i class="fa fa-trash"></i>
                </button>

            </td>

        </tr>

        <?php } ?>

    <?php } ?>

    </tbody>
</table>



                    <!-- Summary -->
                   <div class="row justify-content-center">
    <div class="col-md-10">
        <div class="row">

            <!-- Left Column -->
      <div class="row justify-content-center">
    <div class="col-md-10">
        <div class="row">

            <!-- Gross -->
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Gross</label>
                    <div class="col-sm-8">
                        <input type="text" 
                               name="qtn_sub_total" 
                               id="qtn_sub_total" 
                               class="form-control qtn_sub_total" readonly
                               value="<?= isset($master['sub_total']) ? $master['sub_total'] : "" ?>">
                    </div>
                </div>
            </div>

            <!-- Discount -->
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Discount</label>

                    <div class="col-sm-4">
                        <input type="text" 
                               name="qtn_add_discount_percentage" 
                               id="qtn_add_discount_percentage" 
                               class="form-control"
                               placeholder="%">
                    </div>

                    <div class="col-sm-4">
                        <input type="text" 
                               name="qtn_add_discount_amount" 
                               id="qtn_add_discount_amount" 
                               class="form-control"
                               placeholder="Amount">
                    </div>

                </div>
            </div>

            <!-- VAT -->
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Apply VAT</label>
                    <div class="col-sm-8">
                        <input type="checkbox" id="qtn_apply_vat" name="qtn_apply_vat">

                        <input type="number" 
                               name="qtn_vat_percentage" 
                               id="qtn_vat_percentage" 
                               value="5"
                               class="form-control mt-2"
                               style="width:100px;">
                    </div>
                </div>
            </div>

            <!-- VAT Amount -->
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">VAT Amount</label>
                    <div class="col-sm-8">
                        <input type="text" 
                               name="qtn_vat_amount" 
                               id="qtn_vat_amount" 
                               class="form-control">
                    </div>
                </div>
            </div>

            <!-- Net -->
            <div class="col-md-6">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Net</label>
                    <div class="col-sm-8">
                        <input type="text" 
                               name="qtn_grand_total" 
                               id="qtn_grand_total" 
                               class="form-control"
                               readonly>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
        </div>
    </div>
    
        </div>
    </div>

                            <!-- Payment and Terms -->
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label class="col-form-label">Payment Term</label>
                                    <textarea name="payment_term" id="payment_term"
    class="form-control estimation_edit"><?= isset($master['payment_term']) ? $master['payment_term'] : "" ?></textarea>
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-form-label">Validity</label>
                                    <input type="text" name="validity" id="validity"
                                        class="form-control estimation_edit"
                                        value="<?= isset($master['validity']) ? $master['validity'] : "" ?>">
                                </div>
                            </div>

                             <div class="form-group row">
    <div class="col-sm-6">
        <label class="col-form-label">Warranty</label>
        <input type="text" name="warranty" id="warranty"
            class="form-control estimation_edit"
            value="<?= isset($master['warranty']) ? $master['warranty'] : "" ?>">
    </div>

    <div class="col-sm-6">
        <label class="col-form-label">Warranty Description</label>
        <textarea name="warranty_description" id="warranty_description"
            class="form-control estimation_edit"><?= isset($master['warranty_description']) ? $master['warranty_description'] : "" ?></textarea>
    </div>
</div>

                            <div class="form-group row">
                                <div class="col-sm-6">
                                    <label class="col-form-label">Delivery Term</label>
                                    <textarea name="delivery_term" id="delivery_term"
                                        class="form-control estimation_edit"></textarea>
                                </div>

                                <div class="col-sm-6">
                                    <label class="col-form-label">Terms & Conditions</label>
                                    <textarea name="terms_condition" id="terms_condition"
                                        class="form-control estimation_edit"></textarea>
                                </div>
                            </div>
                            <div class="row mt-3">
    <div class="col-md-12">
        <label>Notes</label>
        <textarea class="form-control"
                  name="notes"
                  id="notes"
                  rows="4"></textarea>
    </div>
</div>
 <div class="row mt-3">
      <div class="col-md-4">
        <!-- Employee Name -->
        <div class="item form-group">
            <label class="col-form-label col-md-4 col-sm-4 label-align">Prepared By:</label>
            <div class="col-md-6 col-sm-6 ">
              <select class="form-control select2" 
                id="employee_prepared" name="employee_prepared">
                <option value="">Select</option>
                <?php foreach ($employees as $s) { ?>
                <option value="<?php echo $s->employee_id  ?>"><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                <?php } ?>
              </select>
            </div>
        </div>
      </div>

       <!-- <div class="col-md-4"> -->
        <!-- Employee Name -->
        <!-- <div class="item form-group">
            <label class="col-form-label col-md-4 col-sm-4 label-align">Approved By:</label>
            <div class="col-md-6 col-sm-6 ">
              <select class="form-control select2" 
                id="employee_approved" name="employee_approved" required>
                <option value="">Select</option>
                <?php foreach ($employees as $s) { ?>
                <option value="<?php echo $s->employee_id  ?>"><?php echo $s->user_code . ' ' . $s->employee_name; ?></option>
                <?php } ?>
              </select>
            </div>
        </div>
      </div>      
    </div>  -->


                           <div class="row justify-content-center mt-3">

    <button type="submit" 
            class="btn btn-warning mr-2"
            name="action"
            value="draft">
        Save Draft
    </button>


    <button type="submit" 
            class="btn btn-success"
            name="action"
            value="quotation">
        Confirm Quotation
    </button>

</div>
                        </div>
                    </div>

                    <div class="modal fade" id="newItemModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Select Item</h5>

                <button type="button" 
                        class="close" 
                        data-dismiss="modal">
                    ×
                </button>
            </div>


            <div class="modal-body">

                <input type="text"
                       id="new_item_search"
                       class="form-control"
                       placeholder="Search item name or code">


                <table class="table table-bordered mt-3">

                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th width="120">Qty</th>
                            <th>Select</th>
                        </tr>
                    </thead>


                    <tbody id="new_item_result">

                    </tbody>


                </table>


            </div>


            <div class="modal-footer">

                <button type="button"
                        class="btn btn-success"
                        id="addSelectedNewItem">
                    Add To Cart
                </button>

            </div>

        </div>
    </div>
</div>

                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    
    CKEDITOR.replace('delivery_term');
  var termsEditor = CKEDITOR.replace('terms_condition');
  CKEDITOR.replace('payment_term', {
    height: 120
});

CKEDITOR.replace('notes', {
    height: 120
});


       
      

    // Prevent accidental form submit on Enter
$(document).on("keydown", "form input, form select", function(e) {
    if (e.key === "Enter") {
        e.preventDefault();
        return false;
    }
});

$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select",
        allowClear: true,
        width: '100%'
    });
});



// Load when page opens
$(document).ready(function () {
    calculateQuotationTotal();
    calculateTotals();
});

   
     $(document).on('keyup change', '.cart_qty, .cart_price', function () {

    var row = $(this).closest('tr');

    var qty = parseFloat(row.find('.cart_qty').val()) || 0;
    var price = parseFloat(row.find('.cart_price').val()) || 0;

    var amount = qty * price;

    row.find('.amount_display').text(amount.toFixed(2));
    row.find('.amount_input').val(amount.toFixed(2));

    calculateQuotationTotal();
});

function calculateQuotationTotal()
{
    var subtotal = 0;

    $('.amount_input').each(function () {
        subtotal += parseFloat($(this).val()) || 0;
    });

    $('#qtn_sub_total').val(subtotal.toFixed(2));

    var discount = parseFloat($('#qtn_add_discount_percentage').val()) || 0;
    var discountAmount = subtotal * discount / 100;

    $('#qtn_add_discount_amount').val(discountAmount.toFixed(2));

    $('#qtn_total').val((subtotal - discountAmount).toFixed(2));
}

$('#qtn_add_discount_percentage').on('keyup change', function () {
    calculateQuotationTotal();
});
// Discount % -> Discount Amount
$('#qtn_add_discount_percentage').on('keyup change', function () {

    var gross = parseFloat($('#qtn_sub_total').val()) || 0;
    var per = parseFloat($(this).val()) || 0;

    var amount = (gross * per) / 100;

    $('#qtn_add_discount_amount').val(amount.toFixed(2));

    calculateTotals();
});

// Discount Amount -> Discount %
$('#qtn_add_discount_amount').on('keyup change', function () {

    var gross = parseFloat($('#qtn_sub_total').val()) || 0;
    var amount = parseFloat($(this).val()) || 0;

    var per = 0;
    if (gross > 0) {
        per = (amount / gross) * 100;
    }

    $('#qtn_add_discount_percentage').val(per.toFixed(2));

    calculateTotals();
});
function calculateTotals() {

    var subtotal = parseFloat($('#qtn_sub_total').val()) || 0;
    var discount = parseFloat($('#qtn_add_discount_amount').val()) || 0;

    // Before VAT calculation
    var taxable_amount = subtotal - discount;

    var vat_amount = 0;

    if ($('#qtn_apply_vat').is(':checked')) {

        var vat_percentage = parseFloat($('#qtn_vat_percentage').val()) || 0;

        vat_amount = (taxable_amount * vat_percentage) / 100;

        $('#qtn_vat_amount').val(vat_amount.toFixed(2));

    } else {

        $('#qtn_vat_amount').val('0.00');

    }

    // Net Amount = Sub Total - Discount + VAT
    var net_amount = taxable_amount + vat_amount;

    $('#qtn_grand_total').val(net_amount.toFixed(2));
}

$('#qtn_apply_vat, #qtn_vat_percentage').on('keyup change', function () {
    calculateTotals();
});

$('#addCartRow').click(function(){

    var row = `
    <tr>

        <td>
            <input type="text"
                   class="form-control form-control-sm"
                   name="product_name[]"
                   placeholder="Item Name">

            <input type="hidden"
                   name="item_id[]"
                   value="">
        </td>

        <td>
            <input type="number"
                   class="form-control form-control-sm cart_qty"
                   name="qty[]"
                   value="1"
                   style="width:70px">
        </td>

        <td>
            <input type="number"
                   class="form-control form-control-sm cart_price"
                   name="price[]"
                   value="0"
                   step="0.01"
                   style="width:100px">
        </td>

        <td>

            <span class="amount_display">0.00</span>

            <input type="hidden"
                   class="amount_input"
                   name="amount[]"
                   value="0">

        </td>

        <td>

            <button type="button"
                    class="btn btn-danger btn-sm removeCartItem">
                <i class="fa fa-trash"></i>
            </button>

        </td>

    </tr>`;

    $('#selectedCartItems').append(row);

});


$('#addNewItem').click(function(){

    $('#newItemModal').modal('show');

});

$('#new_item_search').keyup(function(){

    let keyword = $(this).val();


    if(keyword.length < 2)
    {
        $('#new_item_result').html('');
        return;
    }


    $.ajax({

        url:"<?= base_url('index.php/Sales/search_items') ?>",

        type:"POST",

        data:{
            keyword:keyword
        },

        dataType:"json",

        success:function(data){

            let html='';


            $.each(data,function(i,item){


                html += `

                <tr>

                <td>

                ${item.product_name}

                <input type="hidden"
                       class="new_item_id"
                       value="${item.product_id}">


                <input type="hidden"
                       class="new_item_name"
                       value="${item.product_name}">


                </td>


                <td>

                ${item.total_price}

                <input type="hidden"
                       class="new_item_price"
                       value="${item.total_price}">

                </td>


                <td>

                <input type="number"
                       class="new_item_qty form-control"
                       value="1"
                       min="1">

                </td>


                <td>

                <input type="checkbox"
                       class="new_item_check">

                </td>


                </tr>

                `;

            });


            $('#new_item_result').html(html);

        }

    });


});

$('#addSelectedNewItem').click(function(){

    $('#new_item_result tr').each(function(){

        if($(this).find('.new_item_check').is(':checked'))
        {

            let id = $(this).find('.new_item_id').val();
            let name = $(this).find('.new_item_name').val();
            let price = parseFloat($(this).find('.new_item_price').val()) || 0;
            let qty = parseFloat($(this).find('.new_item_qty').val()) || 0;


            // Check item already exists
            let existingRow = $('#selectedCartItems')
                .find('input[name="item_id[]"][value="'+id+'"]')
                .closest('tr');


            if(existingRow.length > 0)
            {

                // Update existing quantity
                let oldQty = parseFloat(existingRow.find('.cart_qty').val()) || 0;

                let newQty = oldQty + qty;

                existingRow.find('.cart_qty').val(newQty);


                let amount = newQty * price;

                existingRow.find('.amount_display')
                           .text(amount.toFixed(2));

                existingRow.find('.amount_input')
                           .val(amount.toFixed(2));


            }
            else
            {

                // Add new row

                let amount = qty * price;


                $('#selectedCartItems').append(`

                <tr>
                <td>
        ${$('#selectedCartItems tr').length + 1}
    </td>

                    <td>
                        ${name}

                        <input type="hidden"
                               name="item_id[]"
                               value="${id}">
                    </td>


                    <td>
                        <input type="number"
                               class="form-control cart_qty"
                               name="qty[]"
                               value="${qty}"
                               style="width:70px">
                    </td>


                    <td>
                        <input type="number"
                               class="form-control cart_price"
                               name="price[]"
                               value="${price}">
                    </td>


                    <td>

                        <span class="amount_display">
                            ${amount.toFixed(2)}
                        </span>

                        <input type="hidden"
                               class="amount_input"
                               name="amount[]"
                               value="${amount.toFixed(2)}">

                    </td>


                    <td>
                        <button type="button"
                                class="btn btn-danger btn-sm removeCartItem">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>

                </tr>

                `);

            }

        }

    });


    calculateQuotationTotal();
    calculateTotals();

    $('#newItemModal').modal('hide');

});
$(document).off('click', '.removeCartItem');
function updateSerialNo()
{
    $('#selectedCartItems tr').each(function(index){
        $(this).find('td:first').text(index + 1);
    });
}

$(document).on('click', '.removeCartItem', function(e){

    e.preventDefault();

    let row = $(this).closest('tr');

    let itemName = row.find('td:first').text().trim();

    if(confirm("Are you sure you want to remove this item?\n\n" + itemName))
    {
        row.remove();
        updateSerialNo();

        calculateQuotationTotal();
        calculateTotals();
    }

});

$('#enquiry_id').change(function(){

    var enquiry_id = $(this).val();

    if(enquiry_id == '')
    {
        return;
    }


    $.ajax({

        url:"<?= base_url('index.php/Sales/get_enquiry_details') ?>",

        type:"POST",

        data:{
            enquiry_id: enquiry_id
        },

        dataType:"json",

       success:function(data){

    $('#enquiry_code').val(data.enquiry_code);
    $('#branch_name').val(data.branch_name);
    $('#project_name').val(data.project_name);
    $('#customer_name').val(data.customer_name);

    $('#quotation_branch_id').val(data.branch_id);
    $('#quotation_customer').val(data.enquiry_customer);


    // Load enquiry items
    $('#selectedCartItems').html('');


    $.each(data.cart_items,function(i,item){

        let amount = parseFloat(item.qty) * parseFloat(item.price);


        $('#selectedCartItems').append(`

        <tr>

            <td>
                ${item.product_name}

                <input type="hidden"
                       name="item_id[]"
                       value="${item.product_id}">
            </td>


            <td>
                <input type="number"
                       class="form-control cart_qty"
                       name="qty[]"
                       value="${item.qty}"
                       style="width:70px">
            </td>


            <td>
                <input type="number"
                       class="form-control cart_price"
                       name="price[]"
                       value="${item.price}"
                       step="0.01"
                       style="width:100px">
            </td>


            <td>

                <span class="amount_display">
                    ${amount.toFixed(2)}
                </span>

                <input type="hidden"
                       class="amount_input"
                       name="amount[]"
                       value="${amount.toFixed(2)}">

            </td>


            <td>

                <button type="button"
                        class="btn btn-danger btn-sm removeCartItem">
                    <i class="fa fa-trash"></i>
                </button>

            </td>


        </tr>

        `);

    });


    calculateQuotationTotal();
    calculateTotals();

}

    });

});
</script>