<section class="content invoice">
  <div class="row no-print mt-3">
    <div class="col-md-4">
      <!-- Sales Order Dropdown -->
      <select name="sales_order_id" id="sales_order" class="form-control sales_order">
        <option value="">-- Select Sales Order --</option>
        <?php if (!empty($sales_order_list)): ?>
          <?php foreach ($sales_order_list as $so): ?>
            <?php 
              $so_id   = is_array($so) ? $so['so_id'] : $so->so_id;
              $so_code = is_array($so) ? $so['so_code'] : $so->so_code;
             // $selected = ($so_id == $current_so_id) ? 'selected' : ''; // mark current SO selected
            ?>
            <option value="<?= $so_id ?>" <?php //$selected ?>>
              <?= htmlspecialchars($so_code) ?>
            </option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
    </div>

    <div class="col-md-8 text-right">
      <!-- Action Buttons -->
       <div id="print_so"></div>
      

      <button type="button" id="create_delivery_note"class="btn btn-success">
        <i class="fa fa-truck"></i> Create Delivery Note
      </button>
      <button type="button" class="btn btn-success"
        onclick="window.location.href='<?= base_url('index.php/Sales/create_delivery_note/' ) ?>'">
         Create New Sales Order
      </button>
    </div>
  </div>
</section>
<form action="<?= base_url() ?>index.php/Sales/update_sales_order" method="post">
<input type="hidden" name="quotation_id" value="<?= $qtn_master['quotation_id'] ?>">
<input type="hidden" name="enquiry_id" value="<?= $enquiry_data['enquiry_id'] ?>">
<input type="hidden" name="estimation_id" value="<?= $qtn_master['estimation_id'] ?>">
<input type="hidden" name="so_id_upd" value="">

<table class="table table-bordered" style="width:100%; border-collapse:collapse; font-size:13px; margin-bottom:20px;">
  <tr>
    <th style="width:20%;">Sales Order date </th>
    <td style="width:30%;"><input type="date" name="so_edit_date" class="form-control so_edit_date" ></td>
    <th style="width:20%;">Sales Order No</th>
    <td style="width:30%;"><input type="text" name="so_edit_code" value="" class="form-control so_edit_code" readonly></td>
  </tr>
  <tr>
  <th>Quotation No</th>
  <td><input type="text" class="form-control" value="<?= $qtn_master['quotation_code'] ?>" readonly></td>
  <th>Quotation Date</th>
  <td><input type="text" class="form-control" value="<?= date('d-m-Y', strtotime($qtn_master['quotation_date'])) ?>" readonly></td>
</tr>
<tr>
  <th>Branch</th>
  <td><input type="text" class="form-control" value="<?= $enquiry_data['branch_name'] ?>" readonly></td>
  <th>Prepared By</th>
  <td><input type="text" class="form-control" value="<?= $enquiry_data['user_name'] ?>" readonly></td>
</tr>
<tr>
  <th>Project Name</th>
  <td><input type="text" class="form-control" value="<?= $enquiry_data['project_name'] ?>" readonly></td>
  <th>Customer</th>
  <td><input type="text" class="form-control" value="<?= $enquiry_data['customer_name'] ?>" readonly></td>
  <!-- <th>Project Location</th>
  <td><input type="text" class="form-control" value="<?= $enquiry_data['project_location'] ?>" readonly></td> -->
</tr>
<!-- <tr>
  <th>Customer</th>
  <td><input type="text" class="form-control" value="<?= $enquiry_data['customer_name'] ?>" readonly></td>
  <th>Customer TRN</th>
  <td><input type="text" class="form-control" value="<?= $enquiry_data['customer_TR_no'] ?>" readonly></td>
</tr> -->
</table>
<!-- Product List -->
  <div class="row">
    <div class="col-12 table-responsive">
        <table id="so_products_table" class="table table-bordered table-striped">
         
          
        </table>
    </div>
  </div>

  <!-- Financial Summary -->
  <div class="row">
    <div class="col-md-6">&nbsp;</div>
    <div class="col-md-6">
      <div class="table-responsive">
        <table class="table">
          <tr>
            <th style="width:50%">Subtotal:</th>
            <td><input type="text" name="so_edit_subtotal"
                       value=""
                       class="form-control so_edit_subtotal" readonly></td>
          </tr>
          <tr>
            <th>Discount <br>(<p class="so_edit_add_discount_percentage"></p>)%:</th>
            <td>
              <input type="hidden" name="so_edit_add_discount_percentage"
                     value="" class="so_edit_add_discount_percentage" >
              <input type="text" name="so_edit_add_discount_amount"
                     value=""
                     class="form-control so_edit_discount_amount" readonly>
            </td>
          </tr>
          <tr>
            <th>Total before VAT</th>
            <td>
              <input type="text" name="so_edit_totalbefore_vat_amount"
                     value=""
                     class="form-control so_edit_totalbefore_vat_amount" readonly>
            </td>
          </tr>
            <tr>
              <th>VAT <br>(<p class="so_edit_vat_percentage"></p>)%:</th>
              <td>
                <input type="hidden" name="so_edit_vat_percentage"
                       value="">
                <input type="text" name="so_edit_vat_amount"
                       value=""
                       class="form-control so_edit_vat_amount" readonly>
              </td>
            </tr>
          <tr>
            <th>Grand Total:</th>
            <td>
              <input type="text" name="so_edit_grand_total"
                     value=""
                     class="form-control so_edit_grand_total" readonly>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>

  <!-- Address Section -->
  <div class="checkbox">
    <label>
      <input type="checkbox" id="copyAddress" class="flat">
      Shipping address is same as Billing address
    </label>
  </div>

  <table class="table" style="width:100%; font-size:13px;">
    <thead>
      <tr>
        <th style="width:50%;">Billing Address</th>
        <th style="width:50%;">Shipping Address</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <!-- Billing -->
        <td>
          <div class="form-group">
            <label>Name</label>
            <input type="text" name="so_edit_billing_name"
                   value="" class="form-control">
          </div>
          <div class="form-group">
            <label>Address</label>
            <input type="text" name="so_edit_billing_address"
                   value="" class="form-control">
          </div>
          <div class="form-group">
            <label>Emirate</label>
            <input type="text" name="so_edit_billing_city"
                   value="" class="form-control">
          </div>
          <div class="form-group">
            <label>Phone</label>
            <input type="text" name="so_edit_billing_phone"
                   value="" class="form-control">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="text" name="so_edit_billing_email"
                   value="" class="form-control">
          </div>
        </td>

        <!-- Shipping -->
        <td>
          <div class="form-group">
            <label>Name</label>
            <input type="text" name="so_edit_shipping_name"
                   value="" class="form-control">
          </div>
          <div class="form-group">
            <label>Address</label>
            <input type="text" name="so_edit_shipping_address"
                   value="" class="form-control">
          </div>
          <div class="form-group">
            <label>Emirate</label>
            <input type="text" name="so_edit_shipping_city"
                   value="" class="form-control">
          </div>
          <div class="form-group">
            <label>Phone</label>
            <input type="text" name="so_edit_shipping_phone"
                   value="" class="form-control">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="text" name="so_edit_shipping_email"
                   value="" class="form-control">
          </div>
        </td>
      </tr>
    </tbody>
  </table>

  <!-- Terms & Remarks -->
  <div class="row mt-4">
    <div class="col-12">
      <h5>Terms & Conditions</h5>
      <table class="table table-bordered" style="width:100%; font-size:13px;">
        <tbody>
          <tr>
            <th style="width:20%;">Payment Term</th>
            <td><input type="text" name="so_edit_payment_term"
                       value="" class="form-control"></td>
          </tr>
          <tr>
            <th>Validity</th>
            <td><input type="text" name="so_edit_validity"
                       value="" class="form-control"></td>
          </tr>
          <tr>
            <th>Delivery Term</th>
            <td>
              <textarea name="so_edit_delivery_term" class="form-control estimation_edit"
                        rows="3"></textarea>
            </td>
          </tr>
          <tr>
            <th>General Terms & Conditions</th>
            <td>
              <textarea name="so_edit_terms_condition" class="form-control estimation_edit"
                        rows="4"></textarea>
            </td>
          </tr>
          <tr>
            <th>Remarks</th>
            <td>
              <textarea name="so_edit_remarks" rows="2"
                        class="form-control"></textarea>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  

  <div class="text-right mt-3">
    <button type="submit" class="btn btn-success">Update Sales Order</button>
  </div>
</form>
<script>
  $(".sales_order").on("change", function() {
    let so_id = $(this).val();
    let enq_id = <?= $enquiry_data['enquiry_id'] ?>;
    let qtn_id = <?= $qtn_master['quotation_id'] ?>;
    
        $('#print_so').html('<a href="<?= base_url("index.php/Document_controller/print_sales_order/") ?>'+so_id+'/'+enq_id+'" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>');

   $.ajax({
    url: "<?= base_url('index.php/Sales/get_sales_order_partial') ?>",
    type: "POST",
    data: { so_id: so_id,enq_id :enq_id,qtn_id :qtn_id},
    dataType: "json",
    success: function(res) {
        console.log("Full response:", res);
        console.log("so_master:", res.so_master);
        console.log("sub_total:", res.so_master ? res.so_master.sub_total : "missing");


        

        $('input[name="so_id_upd"]').val(so_id);
        $('#so_products_table').html(res.products_html);
        $('input[name="so_edit_code"]').val(res.so_master[0].so_code);
        $('.so_edit_date').val(res.so_date);
        $('.so_edit_subtotal').val(res.so_master[0].sub_total);
        $('input[name="so_edit_add_discount_amount"]').val(res.so_master[0].discount_amount);

        $('.so_edit_add_discount_percentage').val(res.so_master[0].discount_percentage); // hidden input
        $('.so_edit_add_discount_percentage').text(res.so_master[0].discount_percentage); // <p> inside <th>

        $('input[name="so_edit_totalbefore_vat_amount"]').val(res.so_master[0].total_before_vat);
        
        $('.so_edit_vat_percentage').val(res.so_master[0].vat_percentage); // hidden input
        $('.so_edit_vat_percentage').text(res.so_master[0].vat_percentage); // <p> inside <th>

        $('input[name="so_edit_vat_amount"]').val(res.so_master[0].vat_amount);
        $('input[name="so_edit_grand_total"]').val(res.so_master[0].grand_total);

        // billing/shipping addresses
        $('input[name="so_edit_billing_name"]').val(res.so_address.billing_customer_name);
        $('input[name="so_edit_billing_address"]').val(res.so_address.billing_customer_address);
        $('input[name="so_edit_billing_city"]').val(res.so_address.billing_emirates);
        $('input[name="so_edit_billing_phone"]').val(res.so_address.billing_contact);
        $('input[name="so_edit_billing_email"]').val(res.so_address.billing_email);

        $('input[name="so_edit_shipping_name"]').val(res.so_address.shipping_customer);
        $('input[name="so_edit_shipping_address"]').val(res.so_address.shipping_address);
        $('input[name="so_edit_shipping_city"]').val(res.so_address.shipping_emirate);
        $('input[name="so_edit_shipping_phone"]').val(res.so_address.shipping_contact);
        $('input[name="so_edit_shipping_email"]').val(res.so_address.shipping_email);

        // terms & remarks
        $('input[name="so_edit_payment_term"]').val(res.so_master[0].payment_term);
        $('input[name="so_edit_validity"]').val(res.so_master[0].validity);
        //$('textarea[name="so_edit_delivery_term"]').val(res.so_master[0].delivery_term);
        //$('textarea[name="so_edit_terms_condition"]').val(res.so_master[0].terms_and_condition);
        $('textarea[name="so_edit_remarks"]').val(res.so_master[0].remarks);
        if (CKEDITOR.instances['so_edit_delivery_term']) {
            CKEDITOR.instances['so_edit_delivery_term'].setData(res.so_master[0].delivery_term);
        }

        // Terms & Conditions
        if (CKEDITOR.instances['so_edit_terms_condition']) {
            CKEDITOR.instances['so_edit_terms_condition'].setData(res.so_master[0].terms_and_condition);
        }
    }
});
});

function recalculateTotalsEdit() {
    let subtotal = 0;
    let totalDiscount = 0;
    let totalTaxable = 0;

    $("table.table tbody tr").each(function () {
        let qty = parseFloat($(this).find(".so_edit_qty").val()) || 0;
        let unitp = parseFloat($(this).find(".so_edit_unitp").val()) || 0;
        let discount = parseFloat($(this).find(".so_edit_discount").val()) || 0;

        // Recalculate row amount
        let amount = qty * unitp;
        $(this).find(".so_edit_amount").val(amount.toFixed(2));

        // Taxable amount = amount - discount
        let taxable = amount - discount;
        $(this).find(".so_edit_taxable").val(taxable.toFixed(2));

        subtotal += taxable;
        totalDiscount += discount;
        totalTaxable += taxable;
    });

    // Update subtotal
    $(".so_edit_subtotal").val(subtotal.toFixed(2));

    // Additional discount (percentage)
    let discountPercentage = parseFloat($("input[name='so_edit_add_discount_percentage']").val()) || 0;
    let discountAmount = subtotal * (discountPercentage / 100);
    let totalBeforeVat = subtotal - discountAmount;

    $(".so_edit_discount_amount").val(discountAmount.toFixed(2));
    $(".so_edit_totalbefore_vat_amount").val(totalBeforeVat.toFixed(2));

    // VAT
    let vatAmount = 0;
    let vatPercentage = parseFloat($(".so_edit_vat_percentage").val()) || 0;
    if (vatPercentage > 0) {
        vatAmount = totalBeforeVat * (vatPercentage / 100);
        $(".so_edit_vat_amount").val(vatAmount.toFixed(2));
    }

    // Grand total
    let grandTotal = subtotal - discountAmount + vatAmount;
    $(".so_edit_grand_total").val(grandTotal.toFixed(2));
}

// Quantity or unit price change
$(document).on("input", ".so_edit_qty, .so_edit_unitp, .so_edit_discount", function () {
    let row = $(this).closest("tr");
    let qtyField = row.find(".so_edit_qty");
    let maxQty = parseFloat(qtyField.data("maxqty")) || 0;
    let qty = parseFloat(qtyField.val()) || 0;

    if (qty > maxQty) {
        alert("Entered quantity cannot exceed available quantity (" + maxQty + ")");
        qtyField.val(maxQty);
    }

    recalculateTotalsEdit();
});

// Delete row and recalc
function deleteRowEdit(btn) {
    if (confirm("Are you sure you want to remove this product from the list?")) {
        $(btn).closest("tr").remove();
        recalculateTotalsEdit();
    }
}



// Attach event listener (vanilla JS fallback)
document.addEventListener("input", function(e) {
  if (e.target.classList.contains("so_edit_qty") || e.target.classList.contains("so_edit_unitp")) {
    let row = e.target.closest("tr");

    let qty = parseFloat(row.querySelector(".so_edit_qty").value) || 0;
    let qtyField = row.querySelector(".so_edit_qty");
    let maxQty = parseFloat(qtyField.dataset.maxqty) || 0;
    let unitp = parseFloat(row.querySelector(".so_edit_unitp").value) || 0;
    let amountField = row.querySelector(".so_edit_amount");

     if (qty > maxQty) {
      alert("Entered quantity cannot exceed available qty (" + maxQty + ")");
      qty = maxQty;
      qtyField.value = maxQty; // reset to max
    }

    let amount = qty * unitp;
    amountField.value = amount.toFixed(2);
  }
});

$('#create_delivery_note').on('click', function() {
    var enquiry_id = <?= $enquiry_data['enquiry_id'] ?>;

    $.ajax({
        url: '<?= base_url("index.php/Sales/update_delivery_notes_status") ?>',
        type: 'POST',
        data: { enquiry_id: enquiry_id },
        success: function(response) {
            // Optionally, redirect to create delivery note page
            location.reload();
        },
        error: function() {
            alert('Failed to update enquiry status.');
        }
    });
});


</script>