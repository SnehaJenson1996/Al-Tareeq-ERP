
<form method="post"
      action="<?= base_url('index.php/Sales/update_quotation') ?>">
      <div class="row">

    <!-- Enquiry Code -->
    <div class="col-md-6">
        <div class="form-group row align-items-center">
            <label class="col-sm-4 col-form-label">
                Enquiry Code:
            </label>

            <div class="col-sm-8">
                <input type="text"
                    id="enquiry_code"
                    name="enquiry_code"
                    value="<?= $quotation->enquiry_code ?>"
                    class="form-control"
                    readonly>
            </div>
        </div>
    </div>


    <!-- Enquiry Branch -->
    <div class="col-md-6">
        <div class="form-group row align-items-center">

            <label class="col-sm-4 col-form-label">
                Enquiry Branch:
            </label>

            <div class="col-sm-8">

                <input type="text"
                    id="branch_name"
                    name="branch_name"
                    value="<?= $quotation->branch_name ?>"
                    class="form-control"
                    readonly>

            </div>

        </div>
    </div>

</div>



<div class="row">


    <!-- Project -->
    <div class="col-md-6">

        <div class="form-group row align-items-center">

            <label class="col-sm-4 col-form-label">
                Project Name:
            </label>


            <div class="col-sm-8">

                <input type="text"
                    id="project_name"
                    name="project_name"
                    value="<?= $quotation->project_name ?>"
                    class="form-control"
                    readonly>

            </div>

        </div>

    </div>



    <!-- Customer -->

    <div class="col-md-6">

        <div class="form-group row align-items-center">

            <label class="col-sm-4 col-form-label">
                Customer:
            </label>


            <div class="col-sm-8">

                <input type="text"
                    id="customer_name"
                    name="customer_name"
                    value="<?= $quotation->customer_name ?>"
                    class="form-control"
                    readonly>

            </div>

        </div>

    </div>


</div>




<div class="row">


    <!-- Quotation Code -->

    <div class="col-md-6">

        <div class="form-group row align-items-center">

            <label class="col-sm-4 col-form-label">
                Quotation Code:
            </label>


            <div class="col-sm-8">

                <input type="text"
                    name="quotation_code"
                    class="form-control"
                    value="<?= $quotation->quotation_code ?>"
                    readonly>

            </div>

        </div>

    </div>



    <!-- Quotation Date -->

    <div class="col-md-6">

        <div class="form-group row align-items-center">

            <label class="col-sm-4 col-form-label">
                Quotation Date:
            </label>


            <div class="col-sm-8">

                <input type="date"
                    id="quotation_date"
                    name="quotation_date"
                    value="<?= date('Y-m-d',strtotime($quotation->quotation_date)) ?>"
                    class="form-control">

            </div>

        </div>

    </div>


</div>



<input type="hidden"
       name="qtn_id"
       value="<?= $quotation->qtn_id ?>">


<input type="hidden"
       name="quotation_customer"
       id="quotation_customer"
       value="<?= $quotation->quotation_customer ?>">


<input type="hidden"
       name="quotation_branch_id"
       id="quotation_branch_id"
       value="<?= $quotation->quotation_branch_id ?>">


<input type="hidden"
       name="enquiry_id"
       value="<?= $quotation->enquiry_id ?>">

       <table class="table table-bordered">

    <thead>
        <tr>
            <th width="35">#</th>
            <th>Item</th>
            <th width="100">Qty</th>
            <th width="150">Price</th>
            <th width="150">Amount</th>
            <th width="80">
                <button type="button" 
                        class="btn btn-success btn-xs"
                        id="addNewItem">
                    <i class="fa fa-plus"></i>
                </button>
            </th>
        </tr>
    </thead>


    <tbody id="selectedCartItems">

<?php 
$i=1;
if(!empty($cart_items)) { 

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
           value="<?= $item->item_id ?>">

    <input type="hidden"
           name="product_name[]"
           value="<?= $item->product_name ?>">

</td>



<td>

<input type="number"
       class="form-control form-control-sm cart_qty"
       name="qty[]"
       value="<?= $item->qty ?>"
       min="1"
       style="width:80px">

</td>




<td>

<input type="number"
       class="form-control form-control-sm cart_price"
       name="price[]"
       value="<?= number_format($item->price,2,'.','') ?>"
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


<?php } } ?>


    </tbody>


</table>

<!-- Summary -->
<div class="row justify-content-center">

    <div class="col-md-10">

        <div class="row">


            <!-- Gross -->
            <div class="col-md-6">

                <div class="form-group row">

                    <label class="col-sm-4 col-form-label">
                        Gross
                    </label>

                    <div class="col-sm-8">

                        <input type="text"
                               name="qtn_sub_total"
                               id="qtn_sub_total"
                               class="form-control qtn_sub_total"
                               value="<?= number_format($quotation->sub_total,2,'.','') ?>"
                               readonly>

                    </div>

                </div>

            </div>




            <!-- Discount -->
            <div class="col-md-6">

                <div class="form-group row">

                    <label class="col-sm-4 col-form-label">
                        Discount
                    </label>


                    <div class="col-sm-4">

                        <input type="text"
                               name="qtn_add_discount_percentage"
                               id="qtn_add_discount_percentage"
                               class="form-control"
                               value="<?= isset($quotation->discount_percentage) ? $quotation->discount_percentage : 0 ?>"
                               placeholder="%">

                    </div>


                    <div class="col-sm-4">

                        <input type="text"
                               name="qtn_add_discount_amount"
                               id="qtn_add_discount_amount"
                               class="form-control"
                               value="<?= number_format($quotation->discount_amount,2,'.','') ?>"
                               placeholder="Amount">

                    </div>


                </div>

            </div>





            <!-- VAT -->
            <div class="col-md-6">

                <div class="form-group row">


                    <label class="col-sm-4 col-form-label">
                        Apply VAT
                    </label>


                    <div class="col-sm-8">


                        <input type="checkbox"
                               id="qtn_apply_vat"
                               name="qtn_apply_vat"
                               <?= ($quotation->vat_amount > 0) ? 'checked' : '' ?>>



                        <input type="number"
                               name="qtn_vat_percentage"
                               id="qtn_vat_percentage"
                               value="<?= isset($quotation->vat_percentage) ? $quotation->vat_percentage : 5 ?>"
                               class="form-control mt-2"
                               style="width:100px;">


                    </div>


                </div>


            </div>






            <!-- VAT Amount -->
            <div class="col-md-6">


                <div class="form-group row">


                    <label class="col-sm-4 col-form-label">
                        VAT Amount
                    </label>


                    <div class="col-sm-8">


                        <input type="text"
                               name="qtn_vat_amount"
                               id="qtn_vat_amount"
                               class="form-control"
                               value="<?= number_format($quotation->vat_amount,2,'.','') ?>">


                    </div>


                </div>


            </div>






            <!-- Net -->
            <div class="col-md-6">


                <div class="form-group row">


                    <label class="col-sm-4 col-form-label">
                        Net
                    </label>


                    <div class="col-sm-8">


                        <input type="text"
                               name="qtn_grand_total"
                               id="qtn_grand_total"
                               class="form-control"
                               value="<?= number_format($quotation->grand_total,2,'.','') ?>"
                               readonly>


                    </div>


                </div>


            </div>



        </div>

    </div>

</div>

<!-- Payment and Terms -->

<div class="form-group row">

    <div class="col-sm-6">

        <label class="col-form-label">
            Payment Term
        </label>

        <textarea name="payment_term"
                  id="payment_term"
                  class="form-control estimation_edit"><?= 
                  isset($quotation->payment_term) ? $quotation->payment_term : '' 
                  ?></textarea>

    </div>



    <div class="col-sm-6">

        <label class="col-form-label">
            Validity
        </label>

        <input type="text"
               name="validity"
               id="validity"
               class="form-control estimation_edit"
               value="<?= isset($quotation->validity) ? $quotation->validity : '' ?>">

    </div>

</div>




<div class="form-group row">


    <div class="col-sm-6">

        <label class="col-form-label">
            Warranty
        </label>


        <input type="text"
               name="warranty"
               id="warranty"
               class="form-control estimation_edit"
               value="<?= isset($quotation->warranty) ? $quotation->warranty : '' ?>">


    </div>




    <div class="col-sm-6">


        <label class="col-form-label">
            Warranty Description
        </label>


        <textarea name="warranty_description"
                  id="warranty_description"
                  class="form-control estimation_edit"><?= 
                  isset($quotation->warranty_description) ? $quotation->warranty_description : '' 
                  ?></textarea>


    </div>


</div>





<div class="form-group row">


    <div class="col-sm-6">


        <label class="col-form-label">
            Delivery Term
        </label>


        <textarea name="delivery_term"
                  id="delivery_term"
                  class="form-control estimation_edit"><?= 
                  isset($quotation->delivery_term) ? $quotation->delivery_term : '' 
                  ?></textarea>


    </div>





    <div class="col-sm-6">


        <label class="col-form-label">
            Terms & Conditions
        </label>


        <textarea name="terms_condition"
                  id="terms_condition"
                  class="form-control estimation_edit"><?= 
                  isset($quotation->terms_condition) ? $quotation->terms_condition : '' 
                  ?></textarea>


    </div>


</div>





<div class="row mt-3">

    <div class="col-md-12">

        <label>
            Notes
        </label>


        <textarea class="form-control"
                  name="notes"
                  id="notes"
                  rows="4"><?= 
                  isset($quotation->notes) ? $quotation->notes : '' 
                  ?></textarea>


    </div>

</div>





<div class="row mt-3">


<div class="col-md-4">


<div class="item form-group">


<label class="col-form-label col-md-4 col-sm-4 label-align">
Prepared By:
</label>


<div class="col-md-6 col-sm-6">


<select class="form-control select2"
        id="employee_prepared"
        name="employee_prepared">


<option value="">
Select
</option>



<?php foreach ($employees as $s) { ?>


<option value="<?= $s->employee_id ?>"
<?= ($quotation->employee_prepared == $s->employee_id) ? 'selected' : '' ?>>
<?= $s->user_code.' '.$s->employee_name ?>
</option>


<?php } ?>


</select>


</div>


</div>


</div>


</div>

<div class="row mt-4">
    <div class="col-md-12 text-center">

        <input type="hidden" 
               name="qtn_id" 
               value="<?= $quotation->qtn_id ?>">

        <button type="submit" 
                class="btn btn-success">
            <i class="fa fa-save"></i> Update Quotation
        </button>


        <a href="<?= base_url('index.php/Sales/view_quotation/'.$quotation->qtn_id) ?>"
           class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Cancel
        </a>

    </div>
</div>


<div class="modal fade" id="newItemModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add Product</h5>
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
            </div>

            <div class="modal-body">

                <input type="text" 
                       id="new_item_search"
                       class="form-control"
                       placeholder="Search product">

                <table class="table table-bordered mt-3">

                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Select</th>
                        </tr>
                    </thead>

                    <tbody id="new_item_result">

                    </tbody>

                </table>

            </div>


            <div class="modal-footer">

                <button type="button"
                        id="addSelectedNewItem"
                        class="btn btn-success">
                    Add Selected
                </button>

            </div>

        </div>
    </div>
</div>

</form>

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
    calculateTotals();
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

    <input type="hidden"
           name="product_name[]"
           value="${name}">
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

$(document).on('click', '.removeCartItem', function(e){

    e.preventDefault();

    let row = $(this).closest('tr');

    let itemName = row.find('td:first').text().trim();

    if(confirm("Are you sure you want to remove this item?\n\n" + itemName))
    {
        row.remove();

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

    <input type="hidden"
           name="product_name[]"
           value="${item.product_name}">
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