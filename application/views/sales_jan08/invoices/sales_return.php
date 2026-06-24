<!-- invoice_form.php -->

<div class="clearfix"></div>

<div class="x_panel">
  <div class="x_content">

    <form class="form-horizontal" id="invoice_form" method="post" action="<?= base_url('index.php/Sales/save_sales_return/'.$invoice_id) ?>" onsubmit="return validateReturnForm();"> 

    <!-- First Row: Delivery Challan, Sales Order, Invoice Code -->

 <div class="container p-3 border rounded" style="background:#f9f9f9;">

  <div class="row mb-3">
    <div class="col-md-6">
      <label>Select Delivery Challan</label>
      <input type="text" class="form-control" name="sales_order" value="<?= $invoice['delivery_code'] ?>" readonly>
    </div>
    <div class="col-md-6">
      <label>Sales Order</label>
      <input type="text" class="form-control" name="sales_order" value="<?= $invoice['so_code'] ?>" readonly>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-6">
      <label>Invoice Code</label>
      <input type="text" class="form-control" name="invoice_code" value="<?= $invoice['invoice_code'] ?>" readonly>
    </div>
    <div class="col-md-6">
      <label>Invoice Date</label>
      <input type="date" class="form-control" name="invoice_date"  value="<?= $invoice['invoice_date'] ?>" readonly>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-6">
      <label>Quotation Code</label>
      <input type="text" class="form-control" name="quotation_code" value="<?= $invoice['quotation_code'] ?>" readonly>
    </div>
    <div class="col-md-6">
      <label>Quotation Date</label>
      <input type="date" class="form-control" name="quotation_date" value="<?= $invoice['quotation_date'] ?>" readonly>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-6">
      <label>Customer Name</label>
      <input type="text" class="form-control" name="customer_name" value="<?= $invoice['customer_name'] ?>" readonly>
    </div>
    <div class="col-md-6">
      <label>Customer TRN</label>
      <input type="text" class="form-control" name="customer_trn" value="<?= $invoice['customer_TR_no'] ?>" readonly>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-6">
      <label>Delivery Mode</label>
      <input type="text" class="form-control" name="delivery_mode" value="<?= $invoice['delivery_mode'] ?>" readonly>
    </div>
    <div class="col-md-6">
      <label>Delivered By</label>
      <input type="text" class="form-control" name="delivered_by" value="<?= $invoice['deliverd_by'] ?>" readonly>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-6">
      <label>Branch</label>
      <input type="text" class="form-control" name="branch" value="<?= $invoice['customer_TR_no'] ?>" readonly>
    </div>
  </div>

</div>

      <!-- Product Table -->
      <div class="row mb-3">
        <div class="table-responsive">
                <table class="table table-striped table-bordered" id="sales_return_table">
                    <thead>
                    <tr>
                        <th>Product Code</th>
                        <th>Description</th>
                        <th>Delivered Quantity</th>
                        <th>Unit</th>
                        <th>Unit Price</th>
                         <th>Total</th>
                        <th>Return Quantity</th> 
                        <th>Reason</th>
                        <th>Product Condition</th>                
                        <th>Remark</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($products)): ?>
                        <?php foreach($products as $prod): ?>
                        <tr>
                            <td><?= $prod['item_name'] ?> (<?= $prod['item_code'] ?>)</td>
                            <td><?= $prod['product_description']?></td>
                            <td><?= $prod['order_quantity'] ?></td>
                            <td><?= $prod['unit_name'] ?? $prod['unit_id'] ?></td>
                            <td>
                              <?= number_format($prod['unit_price'], 2) ?>
                              <input type="hidden" name="unit_price[<?= $prod['product_id'] ?>]" value="<?= $prod['unit_price'] ?>">
                            </td>
                            <td class="total_amount" data-unit-price="<?= $prod['unit_price'] ?>" data-delivered-qty="<?= $prod['order_quantity'] ?>">
                    <?= number_format($prod['total_amount'], 2) ?>
                          </td>
                            <td>
                            <input type="number" name="return_quantity[<?= $prod['product_id'] ?>]" 
                                    class="form-control" max="<?= $prod['order_quantity'] ?>" value="0">
                            </td>
                            <td>
                            <select class="form-control" name="return_reason[<?= $prod['product_id'] ?>]">
                                <option value="">--Select Reason--</option>
                                <option value="Damaged">Damaged</option>
                                <option value="Defective">Defective</option>
                                <option value="Wrong Product">Wrong Product</option>
                                <option value="Changed Mind">Changed Mind</option>
                                <option value="Late Delivery">Late Delivery</option>
                                <option value="Other">Other</option>
                            </select>
                            <!-- <textarea class="form-control mt-1" name="return_remark[<?= $prod['product_id'] ?>]" 
                                        placeholder="Specify reason if Other" rows="2"></textarea> -->
                            </td>
                            <td>
                            <select class="form-control" name="product_condition[<?= $prod['product_id'] ?>]">
                                <option value="">--Select Condition--</option>
                                <!-- <option value="New">New</option> -->
                                <!-- <option value="Opened">Opened / Like New</option> -->
                                <option value="Damaged">Damaged</option>
                                <!-- <option value="Defective">Defective</option> -->
                                <!-- <option value="Expired">Expired / Outdated</option> -->
                                <option value="Good">Good</option>
                            </select>
                            </td>
                            <td><textarea class="form-control mt-1" name="remark[<?= $prod['product_id'] ?>]" 
                                        placeholder="Specify reason if Other" rows="2"></textarea></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                        <td colspan="9" class="text-center">No products found</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
                </div>

      </div>

      <!-- Totals Row -->
      <div class="row mb-3">
        <div class="col-md-3">
          <label>Sub Total</label>
          <input type="text" class="form-control" name="sub_total" value="<?= $invoice['sub_total'] ?? '' ?>" readonly>
        </div>
        <div class="col-md-1">
          <label>Discount %</label>
          <input type="text" class="form-control" name="discount_per" value="<?= $invoice['discount_percentage'] ?? '' ?>" readonly>
        </div>
        <div class="col-md-2">
          <label>Discount Amt</label>
          <input type="text" class="form-control" name="discount_amt" value="<?= $invoice['discount_amount'] ?? '' ?>" readonly>
        </div>
        <div class="col-md-1">
          <label>VAT %</label>
          <input type="text" class="form-control" name="vat_per" value="<?= $invoice['vat_percentage'] ?? '' ?>" readonly>
        </div>
        <div class="col-md-2">
          <label>VAT Amt</label>
          <input type="text" class="form-control" name="vat_amount" value="<?= $invoice['vat_amount'] ?? '' ?>" readonly>
        </div>
        <div class="col-md-3">
          <label>Grand Total</label>
          <input type="text" class="form-control" name="grand_total" value="<?= $invoice['grand_total'] ?? '' ?>" readonly>
        </div>
      </div>

  <!-- Terms -->
      <div class="row mb-3">
        <div class="col-md-6">
          <label>Validity</label>
          <textarea class="form-control" name="validity" rows="2" readonly><?= $invoice['validity'] ?? '' ?></textarea>
        </div>
        <div class="col-md-6">
          <label>Payment Terms</label>
          <textarea class="form-control" name="payment_terms" rows="2" readonly><?= $invoice['payment_term'] ?? '' ?></textarea>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label>Delivery Terms</label>
          <textarea class="form-control" name="delivery_terms" rows="2" readonly><?= $invoice['delivery_term'] ?? '' ?></textarea>
        </div>
        <div class="col-md-6">
          <label>General Terms</label>
          <textarea class="form-control" name="general_terms" rows="2" readonly><?= $invoice['terms_and_condition'] ?? '' ?></textarea>
        </div>
      </div>

      <!-- Sales Person & Remarks -->
      <div class="row mb-3">
        <div class="col-md-6">
          <label>Remarks</label>
          <textarea class="form-control" name="remarks" rows="3" readonly><?= $invoice['remark'] ?? '' ?></textarea>
        </div>
        <!-- <div class="col-md-6">
          <label>Sales Person</label>
          <input type="text" class="form-control" name="sales_person" 
                value="<?= $invoice['sales_person'] ?? '' ?>">
        </div> -->
        
      </div>


      <!-- Billing & Shipping -->
<div class="row mb-3">
  <div class="col-md-6">
    <label>Billing Address</label>
    <textarea class="form-control" name="billing_address" rows="2" readonly><?= $invoice_address['billing_customer_address'] ?? '' ?></textarea>
  </div>
  <div class="col-md-6">
    <label>Shipping Address</label>
    <textarea class="form-control" name="shipping_address" rows="2" readonly><?= $invoice_address['shipping_address'] ?? '' ?></textarea>
  </div>
</div>

<div class="row mb-3">
  <div class="col-md-6">
    <label>Billing City</label>
    <input type="text" class="form-control" name="billing_city" value="<?= $invoice_address['billing_emirates'] ?? '' ?>" readonly>
  </div>
  <div class="col-md-6">
    <label>Shipping City</label>
    <input type="text" class="form-control" name="shipping_city" value="<?= $invoice_address['shipping_emirate'] ?? '' ?>" readonly>
  </div>
</div>

      <!-- Submit Buttons -->
      <div class="row">
        <div class="col-md-12 text-end">
          <button type="reset" class="btn btn-primary">Cancel</button>
          <button type="submit" class="btn btn-success">Submit</button>
        </div>
      </div>

    </form>
  </div>
</div>
<script>
function validateReturnForm() {
    let valid = true;
    let errorMsg = "";
    let firstInvalid = null;

    document.querySelectorAll("input[name^='return_quantity']").forEach((quantity, index) => {
        let row = quantity.closest("tr");
        let reason    = row.querySelector("select[name^='return_reason']");
        let condition = row.querySelector("select[name^='product_condition']");
        let maxQty = parseInt(quantity.getAttribute('max')) || 0;
        let qty = parseInt(quantity.value);

        // Check if quantity is empty or invalid
        if (isNaN(qty) || qty < 0) {
            valid = false;
            errorMsg += `Row ${index+1}: Please enter valid return quantity.\n`;
            quantity.classList.add("is-invalid");
            if (!firstInvalid) firstInvalid = quantity;
        } else if (qty > maxQty) {
            valid = false;
            errorMsg += `Row ${index+1}: Return quantity cannot exceed delivered quantity (${maxQty}).\n`;
            quantity.classList.add("is-invalid");
            if (!firstInvalid) firstInvalid = quantity;
        } else {
            quantity.classList.remove("is-invalid");
        }

        // Validate reason & condition only if quantity > 0
        if (qty > 0) {
            if (!reason || reason.value.trim() === "") {
                valid = false;
                errorMsg += `Row ${index+1}: Please select return reason.\n`;
                reason.classList.add("is-invalid");
                if (!firstInvalid) firstInvalid = reason;
            } else {
                reason.classList.remove("is-invalid");
            }

            if (!condition || condition.value.trim() === "") {
                valid = false;
                errorMsg += `Row ${index+1}: Please select product condition.\n`;
                condition.classList.add("is-invalid");
                if (!firstInvalid) firstInvalid = condition;
            } else {
                condition.classList.remove("is-invalid");
            }
        }
    });

    if (!valid) {
        alert(errorMsg);
        if (firstInvalid) firstInvalid.focus();
        return false;
    }

    return true;
}
function calculateTotals() {
    let subTotal = 0;

    document.querySelectorAll("#sales_return_table tbody tr").forEach(row => {
        let deliveredQty = parseFloat(row.querySelector('.total_amount').dataset.deliveredQty) || 0;
        let unitPrice = parseFloat(row.querySelector('.total_amount').dataset.unitPrice) || 0;
        let returnQtyInput = row.querySelector("input[name^='return_quantity']");
        let returnQty = parseFloat(returnQtyInput.value) || 0;

        // Ensure return quantity <= delivered quantity
        if (returnQty > deliveredQty) {
            returnQty = deliveredQty;
            returnQtyInput.value = deliveredQty; // auto-correct
            alert("Return quantity cannot exceed delivered quantity!");
        }

        let rowTotal = (returnQty) * unitPrice;
        row.querySelector('.total_amount').textContent = rowTotal.toFixed(2);

        subTotal += rowTotal;
    });

    let discountPer = parseFloat(document.querySelector("input[name='discount_per']").value) || 0;
    let vatPer = parseFloat(document.querySelector("input[name='vat_per']").value) || 0;

    let discountAmt = subTotal * discountPer / 100;
    let vatAmt = (subTotal - discountAmt) * vatPer / 100;
    let grandTotal = subTotal - discountAmt + vatAmt;

    document.querySelector("input[name='sub_total']").value = subTotal.toFixed(2);
    document.querySelector("input[name='discount_amt']").value = discountAmt.toFixed(2);
    document.querySelector("input[name='vat_amount']").value = vatAmt.toFixed(2);
    document.querySelector("input[name='grand_total']").value = grandTotal.toFixed(2);
}

// Trigger on input change
document.querySelectorAll("input[name^='return_quantity']").forEach(input => {
    input.addEventListener('input', calculateTotals);
});

// Initial calculation
calculateTotals();
</script>

