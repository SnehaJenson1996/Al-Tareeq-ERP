<style>
  label,
  h4 {
    color: black;
    font-weight: bold;
  }
</style>

<div class="clearfix"></div>
<div class="row">
  <div class="col-md-12 col-sm-12">
    <div class="x_panel">
      <div class="x_title">
        <div class="clearfix"></div>
        <?php if ($this->session->flashdata('success')): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

      </div>
      <div class="x_content">
        <!-- Update Sales Order Form -->
        <form action="<?= base_url() ?>index.php/Sales/update_sales_order" method="post">
          <input type="hidden" name="quotation_id" value="<?= $qtn_master['quotation_id'] ?>">
          <input type="hidden" name="enquiry_id" value="<?= $enquiry_data['enquiry_id'] ?>">
          <input type="hidden" name="estimation_id" value="<?= $qtn_master['estimation_id'] ?>">
          <input type="hidden" name="so_id_upd" value="">

          <!-- Sales Order Header Table -->
          <table class="table table-bordered" style="width:100%; font-size:13px; margin-bottom:20px;">
            <tr>
              <th style="width:20%;">Sales Order Date</th>
              <td style="width:30%;"><input type="date" name="so_edit_date" class="form-control so_edit_date"></td>
              <th style="width:20%;">Sales Order No</th>
              <td style="width:30%;"><input type="text" name="so_edit_code" class="form-control so_edit_code" readonly></td>
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
            </tr>
          </table>

          <!-- Product List Table -->
          <div class="row">
            <div class="col-12 table-responsive">
              <table id="so_products_table" class="table table-bordered table-striped">
                <!-- Populated dynamically via JS -->
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
                    <th style="width:50%;">Subtotal:</th>
                    <td><input type="text" name="so_edit_subtotal" class="form-control so_edit_subtotal" readonly></td>
                  </tr>
                  <tr>
                    <th>Discount (<span class="so_edit_add_discount_percentage"></span> %):</th>
                    <td>
                      <input type="hidden" name="so_edit_add_discount_percentage" class="so_edit_add_discount_percentage">
                      <input type="text" name="so_edit_add_discount_amount" class="form-control so_edit_discount_amount" readonly>
                    </td>
                  </tr>
                  <tr>
                    <th>Total before VAT:</th>
                    <td><input type="text" name="so_edit_totalbefore_vat_amount" class="form-control so_edit_totalbefore_vat_amount" readonly></td>
                  </tr>
                  <tr>
                    <th>VAT (<span class="so_edit_vat_percentage"></span> %):</th>
                    <td>
                      <input type="hidden" name="so_edit_vat_percentage">
                      <input type="text" name="so_edit_vat_amount" class="form-control so_edit_vat_amount" readonly>
                    </td>
                  </tr>
                  <tr>
                    <th>Grand Total:</th>
                    <td><input type="text" name="so_edit_grand_total" class="form-control so_edit_grand_total" readonly></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>

          <!-- Address Section -->
          <div class="checkbox">
            <label>
              <input type="checkbox" id="copyAddress" class="flat"> Shipping address is same as Billing address
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
                  <div class="form-group"><label>Name</label><input type="text" name="so_edit_billing_name" class="form-control"></div>
                  <div class="form-group"><label>Address</label><input type="text" name="so_edit_billing_address" class="form-control"></div>
                  <div class="form-group"><label>Emirate</label><input type="text" name="so_edit_billing_city" class="form-control"></div>
                  <div class="form-group"><label>Phone</label><input type="text" name="so_edit_billing_phone" class="form-control"></div>
                  <div class="form-group"><label>Email</label><input type="text" name="so_edit_billing_email" class="form-control"></div>
                </td>

                <!-- Shipping -->
                <td>
                  <div class="form-group"><label>Name</label><input type="text" name="so_edit_shipping_name" class="form-control"></div>
                  <div class="form-group"><label>Address</label><input type="text" name="so_edit_shipping_address" class="form-control"></div>
                  <div class="form-group"><label>Emirate</label><input type="text" name="so_edit_shipping_city" class="form-control"></div>
                  <div class="form-group"><label>Phone</label><input type="text" name="so_edit_shipping_phone" class="form-control"></div>
                  <div class="form-group"><label>Email</label><input type="text" name="so_edit_shipping_email" class="form-control"></div>
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
                    <td><input type="text" name="so_edit_payment_term" class="form-control"></td>
                  </tr>
                  <tr>
                    <th>Validity</th>
                    <td><input type="text" name="so_edit_validity" class="form-control"></td>
                  </tr>
                  <tr>
                    <th>Delivery Term</th>
                    <td><textarea name="so_edit_delivery_term" class="form-control estimation_edit" rows="3"></textarea></td>
                  </tr>
                  <tr>
                    <th>General Terms & Conditions</th>
                    <td><textarea name="so_edit_terms_condition" class="form-control estimation_edit" rows="4"></textarea></td>
                  </tr>
                  <tr>
                    <th>Remarks</th>
                    <td><textarea name="so_edit_remarks" class="form-control" rows="2"></textarea></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="text-right mt-3">
            <button type="submit" class="btn btn-success">Update Sales Order</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
<script>


  function recalculateTotalsEdit() {
    let subtotal = 0;
    let totalDiscount = 0;
    let totalTaxable = 0;

    $("table.table tbody tr").each(function() {
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
  $(document).on("input", ".so_edit_qty, .so_edit_unitp, .so_edit_discount", function() {
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
      data: {
        enquiry_id: enquiry_id
      },
      success: function(response) {
        // Optionally, redirect to create delivery note page
        location.reload();
      },
      error: function() {
        alert('Failed to update enquiry status.');
      }
    });
  });

  function update_enquiry_for_sales_order(enquiry_id) {
    if (!enquiry_id) {
      alert("Invalid enquiry ID");
      return;
    }

    $.ajax({
      url: "<?= base_url('index.php/Sales/update_enquiry_for_so') ?>",
      type: "POST",
      data: {
        enquiry_id: enquiry_id
      },
      dataType: "json",
      success: function(res) {
        if (res.status === "success") {
          location.reload(); // ✅ reload page
        } else {
          alert("Failed to update enquiry.");
        }
      },
      error: function() {
        alert("Error: Could not update enquiry.");
      }
    });
  }
</script>