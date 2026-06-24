<div class="x_content">

 

    <!-- Quotation / Sales Order Header -->
<?php if (!empty($enquiry_data['enquiry_status']) && ($enquiry_data['enquiry_status'] == 7)): ?>    

    <!-- Sales Order Header -->
<form  action="<?= base_url()?>index.php/Sales/save_sales_order" method="post">
<input type="hidden" name="quotation_id" value="<?= $qtn_master['quotation_id'] ?>">
<input type="hidden" name="enquiry_id" value="<?= $enquiry_data['enquiry_id'] ?>">
<input type="hidden" name="estimation_id" value="<?= $qtn_master['estimation_id'] ?>">

<table class="table table-bordered" style="width:100%; border-collapse:collapse; font-size:13px; margin-bottom:20px;">
  <tr>
    <th style="width:20%;">Sales Order date </th>
    <td style="width:30%;"><input type="date" name="so_date" class="form-control" ></td>
    <th style="width:20%;">Sales Order No</th>
    <td style="width:30%;"><input type="text" name="so_code" value="<?= isset($so_code)?$so_code:0 ?>" class="form-control" readonly></td>
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

    <hr>

    <!-- Product List -->
    <div class="row">
      <div class="col-12 table-responsive">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Product</th>
              <th>Unit</th>
              <th>Qty</th>
              <th>Unit Price</th>
              <th>Amount</th>
              <th>Discount amount</th>
              <th>Taxable amount</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($qtn_products)): ?>
              <?php foreach ($qtn_products as $i => $item): ?>
                <tr>
                  <td><?= $i+1 ?></td>
                  <td>
                    <?= $item['item_name'] ?><br>
                    <?= $item['prd_description'] ?>
                    <input type="hidden" name="product_id[]" value="<?= $item['prd_id'] ?>">
                    <input type="hidden" name="product_desc[]" value="<?= $item['prd_description'] ?>">
                  </td>
                  <td>
                    <?= $item['unit_name'] ?>
                    <input type="hidden" name="unit_id[]" value="<?= $item['unit_id'] ?>">
                  </td>
                  <td>
                    <input type="text" name="so_qty[]" 
                          value="<?= $item['qty'] ?>"  
                          data-maxqty="<?= $item['qty'] ?>"  
                          class="form-control so_qty">
                    <input type="hidden" name="so_max_qty[]" value="<?= $item['qty'] ?>">
                  </td>
                  <td>
                    <input type="text" name="so_unitp[]" 
                          value="<?= $item['unit_price'] ?>" 
                          class="form-control so_unitp" readonly>
                  </td>
                  <td>
                    <input type="text" name="so_amount[]" 
                          value="<?= number_format($item['amount'],2) ?>" 
                          class="form-control so_amount" readonly>
                    <!-- <input type="hidden" name="so_max_amount[]" value="<?= $item['amount'] ?>"> -->
                    
                  </td>
                  <td>
                    <input type="text" name="so_discount[]" 
                          value="<?= $item['dicount_amount'] ?>" 
                          class="form-control so_discount" >
                    <!-- <input type="hidden" name="so_max_discount[]" value="<?= $item['dicount_amount'] ?>"> -->
                  </td>
                  <td>
                    <input type="text" name="so_taxable[]" 
                          value="<?= number_format($item['taxable_amount'],2) ?>" 
                          class="form-control so_taxable" readonly>
                  </td>
                  <td>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">
                      <i class="fa fa-trash"></i>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>

        </table>
      </div>
    </div>

    <!-- Financial Summary -->
    <div class="col-md-6"></div>
      <div class="col-md-6">
        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">Subtotal:</th>
              <td><input type="text" name="so_subtotal" value="<?= number_format($qtn_master['sub_total'],2) ?>" class="form-control so_subtotal"readonly></td>
            </tr>
            <tr>
              <th>Discount(<?= $qtn_master['discount_percentage'] ?>)%:</th>
              <td>
                <input type="hidden" name="so_add_discount_percentage"  value="<?= $qtn_master['discount_percentage'] ?>">
               <input type="text" name="so_add_discount_amount" value="<?= number_format($qtn_master['discount_amount'],2) ?>" class="form-control so_discount_amount"readonly>
              </td>
            </tr>
            <tr>
              <th>Total before vat</th>
              <td>
               <input type="text" name="so_totalbefore_vat_amount" value="<?= number_format($qtn_master['total_before_vat'],2) ?>" class="form-control so_totalbefore_vat_amount"readonly>
              </td>
            </tr>
            <?php if(!empty($qtn_master['vat_required'])){?>
              <tr>
                <th>VAT(<?= $qtn_master['vat_percentage']?>):</th>
                 
                <td>

                  <input type="hidden" name="so_vat_percentage"  value="<?= $qtn_master['vat_percentage'] ?>" class="so_vat_percentage">
                  <input type="text" name="so_vat_amount" value="<?= number_format($qtn_master['vat_amount'],2) ?>" class="form-control so_vat_amount" readonly></td>
              </tr>
            <?php } ?>
            <tr>
              <th>Grand Total:</th>
              <td>
                <input type="text" name="so_grand_total" value="<?= number_format($qtn_master['grand_total'],2) ?>" class="form-control so_grand_total" readonly>
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <!----------Adress------------->
    <div class="checkbox">
			<label class="">
			  <div class="icheckbox_flat-green" style="position: relative;">
          <input type="checkbox" id="copyAddress"  class="flat"  style="position: absolute; opacity: 0;">
          <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
        </div> 
         Shipping address is same as Billing address
			</label>
		</div>
  <table class="table" style="width:100%; border-collapse:collapse; font-size:13px;">
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
          <input type="text" id="billing_name" name="billing_name" value="<?= $enquiry_data['customer_name'] ?>" class="form-control" />
        </div>
        <div class="form-group">
          <label>Address</label>
          <input type="text" id="billing_address" name="billing_address" value="<?= $enquiry_data['customer_address'] ?>"  class="form-control" />
        </div>
        <div class="form-group">
          <label>Emirate</label>
          <input type="text" id="billing_city" name="billing_city"  value="<?= $enquiry_data['emirate'] ?>"  class="form-control" />
        </div>
        <div class="form-group">
          <label>Phone</label>
          <input type="text" id="billing_phone" name="billing_phone"  value="<?= $enquiry_data['contact_number'] ?>" class="form-control" />
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="text" id="billing_email" name="billing_email" value="<?= $enquiry_data['customer_email'] ?>" class="form-control" />
        </div>
      </td>

      <!-- Shipping -->
      <td>
        <div class="form-group">
          <label>Name</label>
          <input type="text" id="shipping_name" name="shipping_name" class="form-control" />
        </div>
        <div class="form-group">
          <label>Address</label>
          <input type="text" id="shipping_address" name="shipping_address" class="form-control" />
        </div>
        <div class="form-group">
          <label>Emirate</label>
          <input type="text" id="shipping_city" name="shipping_city" class="form-control" />
        </div>
        <div class="form-group">
          <label>Phone</label>
          <input type="text" id="shipping_phone" name="shipping_phone" class="form-control" />
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="text" id="shipping_email" name="shipping_email" class="form-control" />
        </div>
      </td>
    </tr>
  </tbody>
</table>
<!-- Terms & Remarks Section -->
<!-- Terms & Remarks Section -->
<div class="row mt-4">
  <div class="col-12">
    <h5>Terms & Conditions</h5>
    <table class="table table-bordered" style="width:100%; border-collapse:collapse; font-size:13px;">
  <tbody>
    <tr>
      <th style="width:20%;">Payment Term</th>
      <td>
        <input type="text" name="so_payment_term" class="form-control" 
               value="<?= isset($qtn_master['payment_term']) ? $qtn_master['payment_term'] : '' ?>">
      </td>
    </tr>

    <tr>
      <th style="width:20%;">Validity</th>
      <td>
        <input type="text" name="so_validity" class="form-control" 
               value="<?= isset($qtn_master['validity']) ? $qtn_master['validity'] : '' ?>">
      </td>
    </tr>

    <tr>
      <th style="width:20%;">Delivery Term</th>
      <td>
        <textarea name="so_delivery_term" id="so_delivery_term" 
                  class="form-control estimation_edit" rows="3"><?= isset($qtn_master['delivery_term']) ? $qtn_master['delivery_term'] : '' ?></textarea>
      </td>
    </tr>

    <tr>
      <th style="width:20%;">General Terms & Conditions</th>
      <td>
        <textarea name="so_terms_condition" id="so_terms_condition" 
                  class="form-control estimation_edit" rows="4"><?= isset($qtn_master['terms_condition']) ? $qtn_master['terms_condition'] : '' ?></textarea>
      </td>
    </tr>

    <tr>
      <th style="width:20%;">Remarks</th>
      <td>
        <textarea name="so_remarks" rows="2" class="form-control"></textarea>
      </td>
    </tr>
  </tbody>
</table>

<input type="submit" name="save" value="save" class="btn btn-success"> 
    </form>
<?php elseif (!empty($enquiry_data['enquiry_status']) && $enquiry_data['enquiry_status'] >= 8): ?>
   <?php $this->load->view('sales/include/sales_order_edit.php') ?>
<?php endif; ?>
  </div>
</div>
    <!-- Action Buttons -->
    

  </section>
</div>

<script>

function recalculateTotals() {
    let subtotal = 0;
    let totalDiscount = 0;
    let totalTaxable = 0;

    $("table.table tbody tr").each(function () {
        let qty = parseFloat($(this).find(".so_qty").val()) || 0;
        let unitp = parseFloat($(this).find(".so_unitp").val()) || 0;
        let discount = parseFloat($(this).find(".so_discount").val()) || 0;
        // Recalculate row amounts
        let amount = qty * unitp;
        $(this).find(".so_amount").val(amount.toFixed(2));
        // Taxable amount = amount - discount
        let taxable = amount - discount;

        $(this).find(".so_taxable").val(taxable.toFixed(2));

        subtotal += taxable;        
        totalDiscount += discount;
        totalTaxable += taxable;
    });


    // Update subtotal
    $(".so_subtotal").val(subtotal.toFixed(2));

    // Discount
    let discountPercentage = parseFloat($("input[name='so_add_discount_percentage']").val()) || 0;
    let discountAmount = subtotal * (discountPercentage / 100);
    let totalbforevat = subtotal - discountAmount;

    $(".so_discount_amount").val(discountAmount.toFixed(2));
    $(".so_totalbefore_vat_amount").val(totalbforevat.toFixed(2));
    // VAT
    let vatAmount = 0;
    let vatPercentage = parseFloat($(".so_vat_percentage").val()) || 0;
    if (vatPercentage > 0) {
        vatAmount = (totalbforevat) * (vatPercentage / 100);
        $(".so_vat_amount").val(vatAmount.toFixed(2));
    }

    // Grand total
    let grandTotal = subtotal - discountAmount + vatAmount;
    $(".so_grand_total").val(grandTotal.toFixed(2));
}

// Quantity or unit price change
$(document).on("input", ".so_qty, .so_unitp,.so_discount", function () {
    let row = $(this).closest("tr");
    let qtyField = row.find(".so_qty");
    let maxQty = parseFloat(qtyField.data("maxqty")) || 0;
    let qty = parseFloat(qtyField.val()) || 0;

    if (qty > maxQty) {
        alert("Entered quantity cannot exceed available qty (" + maxQty + ")");
        qtyField.val(maxQty);
    }

    recalculateTotals();
});

// Delete row and recalc
function deleteRow(btn) {
    if (confirm("Are you sure you want to remove this product from the list?")) {
        $(btn).closest("tr").remove();
        recalculateTotals();
    }
}

// Initial calculation on page load

// Attach event listener to quantity and unit price fields
document.addEventListener("input", function(e) {
  if (e.target.classList.contains("so_qty") || e.target.classList.contains("so_unitp")) {
    let row = e.target.closest("tr");

    let qty = parseFloat(row.querySelector(".so_qty").value) || 0;
    let qtyField = row.querySelector(".so_qty");
    let maxQty = parseFloat(qtyField.dataset.maxqty) || 0;
    let unitp = parseFloat(row.querySelector(".so_unitp").value) || 0;
    let amountField = row.querySelector(".so_amount");

     if (qty > maxQty) {
      alert("Entered quantity cannot exceed available qty (" + maxQty + ")");
      qty = maxQty;
      qtyField.value = maxQty; // reset to max
    }

    let amount = qty * unitp;
    amountField.value = amount.toFixed(2);
  }
});


$(document).ready(function(){
  $("form").on("submit", function() {
    $("#so_delivery_term").val($("#delivery-editor").html()); 
    $("#so_terms_condition").val($("#terms-editor").html());
  });

    $('#copyAddress').on('ifChecked', function () {
        $("#shipping_name").val($("#billing_name").val());
        $("#shipping_address").val($("#billing_address").val());
        $("#shipping_city").val($("#billing_city").val());
        $("#shipping_phone").val($("#billing_phone").val());
        $("#shipping_email").val($("#billing_email").val());
    });

    $('#copyAddress').on('ifUnchecked', function () {
        $("#shipping_name, #shipping_address, #shipping_city, #shipping_phone, #shipping_email").val('');

    });
});

</script>
