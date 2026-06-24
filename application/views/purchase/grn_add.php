   <style>
    .form-control{
      font-size:13px !important;
    }
  </style>


<form id="main" onsubmit="return check_total();" method="post" action="<?php echo base_url().'index.php/'; ?>Purchase/add_grn_records" autocomplete="off" enctype="multipart/form-data">

  <div class="form-group" role="main">
    <div class="">
      <div class="page-title"></div>
      <div class="clearfix"></div>

      <div class="x_content">
        <div class="well" style="overflow: auto">

          <!-- Row 1: PO Info -->
          <div class="row mb-3">
            <div class="col-md-1">
              <label class="control-label">Select PO</label>
            </div>
            <div class="col-md-3">
              <select class="form-control" name="po_id" id="po_id" required onchange="get_po_info()">
                <option value="">Select</option>
                <?php foreach ($records as $s) { ?>
                    <option value="<?php echo $s->po_id ?>" data-po-type="<?php echo $s->po_type; ?>">
                        <?php echo $s->po_code; ?>
                    </option>
                <?php } ?>
            </select>
            </div>

            <div class="col-md-1">
              <label class="control-label">GRN Code</label>
            </div>
            <div class="col-md-3">
              <input type="text" class="form-control" name="grn_code" id="grn_code" readonly value="<?php echo $Code; ?>">
            </div>

            <div class="col-md-1">
              <label class="control-label">GRN Date</label>
            </div>
            <div class="col-md-3">
              <input type="date" class="form-control" name="grn_date" id="grn_date" value="<?php echo date('Y-m-d'); ?>">
            </div>
          </div>

          <!-- Row 2: Supplier Info -->
          <div class="row mb-3">
            <div class="col-md-1">
              <label class="control-label">Branch</label>
            </div>
            <div class="col-md-3">
              <input type="text" class="form-control" name="branch_name" id="branch_name" readonly>
              <input type="hidden" class="form-control" name="branch_id" id="branch_id">
            </div>

            <div class="col-md-1">
              <label class="control-label">Supplier</label>
            </div>
            <div class="col-md-3">
              <input type="text" class="form-control" name="supplier_name" id="supplier_name" readonly  onchange="get_supplier_info()">
              <input type="hidden" class="form-control" name="supplier_id" id="supplier_id">
            </div>

            <div class="col-md-1">
              <label class="control-label">Reference</label>
            </div>
            <div class="col-md-3">
              <input type="text" class="form-control" name="ref_no" id="ref_no">
            </div>
          </div>

          <!-- Row 3: Warehouse and PO Status -->
          <div class="row mb-3">
            <div class="col-md-1">
              <label class="control-label">Select Warehouse</label>
            </div>
            <div class="col-md-3">
              <select class="form-control" name="warehouse_id" id="warehouse_id">
                <option value="">Select warehouse</option>
                <?php foreach($warehouse_list as $g) { ?>
                  <option value="<?php echo $g->warehouse_id; ?>"><?php echo $g->warehouse_name; ?></option>
                <?php } ?>
              </select>
            </div>

            <div class="col-md-1">
              <label class="control-label">Close PO</label>
            </div>
            <div class="col-md-3">
              <select class="form-control" name="po_status" id="po_status">
                <option value="1">Yes</option>
                <option value="0" selected>No</option>
              </select>
            </div>
          </div>

        </div>
      </div>

      <!-- Row 4: PO Items Table -->
      <div class="row mb-4">
        <div class="col-md-12" id="po_items_list" style="overflow: auto;"></div>
      </div>

   <div class="x_content">
            <!-- Row 1: Currency + Subtotal -->
            <div class="row mb-3 align-items-center">
              <!-- Currency -->
              <!-- <div class="col-md-2 col-sm-3">
                <label class="control-label">Currency</label>
              </div>
              <div class="col-md-3 col-sm-9 d-flex align-items-center">
                <select class="form-control me-2" name="currency" id="currency" required>
                  <option value="">Select Currency</option>
                  <?php foreach ($currency_list as $cur) { ?>
                    <option value="<?php echo $cur->currency_abbr; ?>" 
                      <?php echo ($cur->currency_abbr == 'AED') ? 'selected' : ''; ?>>
                      <?php echo $cur->currency_abbr . ' - ' . $cur->currency_name; ?>
                    </option>
                  <?php } ?>
                </select>
                <input type="text" class="form-control w-50" name="currancy_value" id="currancy_value" value="1">
              </div> -->

              <div class="col-md-2 col-sm-3">
                <label class="control-label">Currency</label>
              </div>
              <div class="col-md-3 col-sm-9 d-flex align-items-center">
                <input type="text" class="form-control" name="currency" id="currency" readonly>
                <input type="text" class="form-control" name="conversion_rate" id="conversion_rate" readonly>
              </div>

              <!-- Sub Total -->
              <div class="col-md-2">
                <label class="control-label">Sub Total</label>
              </div>
              <div class="col-md-2 d-flex gap-1">
                <input type="text" class="form-control" name="sub_total" id="sub_total" readonly>
                <input type="text" class="form-control w-50" name="sub_total_fc" id="sub_total_fc" readonly placeholder="FC">
              </div>
            </div>

            <!-- Row 2: Discount + Other Expenses -->
            <div class="row mb-3 align-items-center">
              <div class="col-md-2">
                <label class="control-label">Discount(%)</label>
              </div>
              <div class="col-md-1">
                <input type="text" class="form-control" name="discount_per" id="discount_per" readonly>
              </div>

              <div class="col-md-2 d-flex gap-1">
                <input type="text" class="form-control" name="discount_amt" id="discount_amt" readonly>
                <input type="text" class="form-control w-50" name="discount_amt_fc" id="discount_amt_fc" readonly placeholder="FC">
              </div>

              <div class="col-md-2">
                <label class="control-label">Other Expenses</label>
              </div>
              <div class="col-md-2 d-flex gap-1">
                <input type="text" class="form-control" name="other_expence" id="other_expence" readonly>
                <input type="text" class="form-control w-50" name="other_expence_fc" id="other_expence_fc" readonly placeholder="FC">
              </div>
            </div>

            <!-- Row 3: VAT + Total Before VAT -->
            <div class="row mb-3 align-items-center">
              <div class="col-md-2">
                <label class="control-label">VAT(%)</label>
              </div>
              <div class="col-md-1">
                <input type="text" class="form-control" name="vat_per" id="vat_per" readonly>
              </div>

              <div class="col-md-2 d-flex gap-1">
                <input type="text" class="form-control" name="vat_amount" id="vat_amount" readonly>
                <input type="text" class="form-control w-50" name="vat_amount_fc" id="vat_amount_fc" readonly placeholder="FC">
              </div>

              <div class="col-md-2">
                <label class="control-label">Total Before VAT</label>
              </div>
              <div class="col-md-2 d-flex gap-1">
                <input type="text" class="form-control" name="total_beforevat" id="total_beforevat" readonly>
                <input type="text" class="form-control w-50" name="total_beforevat_fc" id="total_beforevat_fc" readonly placeholder="FC">
              </div>
            </div>

            <!-- Row 4: Grand Total + Converted Total -->
            <div class="row mb-3 align-items-center">
              <div class="col-md-2">
                <label class="control-label">Grand Total</label>
              </div>
              <div class="col-md-2 d-flex gap-1">
                <input type="text" class="form-control" name="grand_total" id="grand_total" readonly value="1000">
                <!-- <input type="text" class="form-control w-50" name="grand_total_fc" id="grand_total_fc" readonly placeholder="FC"> -->
                <input type="text" class="form-control w-50" name="base_currency_grand_total" id="base_currency_grand_total" readonly >
                <!-- placeholder="Base Currency Grand Total" -->
              </div>

              <!-- <div class="col-md-2">
                <label class="control-label">Converted Total</label>
              </div>
              <div class="col-md-2 d-flex gap-1">
                <input type="text" class="form-control" name="converted_total" id="converted_total" readonly>
                <input type="text" class="form-control w-50" name="converted_total_fc" id="converted_total_fc" readonly placeholder="FC">
              </div> -->
            </div>
          </div>
        <!-- Row 6: Remarks and Prepared By -->
        <div class="row mb-3">
          <div class="col-md-2">
            <label class="control-label">Remarks</label>
          </div>
          <div class="col-md-4">
            <textarea class="form-control" name="remarks" id="remarks"></textarea>
          </div>

          <div class="col-md-1">
            <label class="control-label">Prepared By</label>
          </div>
          <div class="col-md-3">
            <input type="text" class="form-control" name="sales_person" id="sales_person" value="<?php echo $this->session->userdata('user_name');?>" readonly>
          </div>
        </div>
<!-- ==================== Accounts Section ==================== -->
        <div class="x_panel">
          <div class="x_title">
            <h5><strong>Purchase Invoice Account Entry</strong></h5>
            <div class="clearfix"></div>
          </div>

          <div class="x_content well" style="overflow:auto;">

            <div class="row">
              <!-- Debit Table -->
              <div class="col-md-6 col-sm-12 mb-3">
                <div class="x_panel tile fixed_height_320 overflow_hidden">
                  <div class="x_title">
                    <h6><strong>Debit Purchase (Dr)</strong></h6>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                    <table class="table table-bordered table-hover table-sm" id="inv_dr_table">
                      <thead class="bg-light">
                        <tr>
                          <th width="70%">Account</th>
                          <th>Amount (AED)</th>
                        </tr>
                      </thead>
                      <tbody id="inv_dr_body">
                        <tr id='inv_dr_addr0'>
                          <td>
                            <select class="form-control form-control-sm select2" id="inv_debtor0" name="inv_debtor[]">
                              <option value="">Select</option>
                              <?php foreach ($sundry_accounts1 as $row) { ?>
                                <option <?php if ($row->account_id == 1120) echo 'selected'; ?>
                                  value="<?php echo $row->account_id; ?>"><?php echo $row->account_name; ?>
                                </option>
                              <?php } ?>
                            </select>
                          </td>
                          <td>
                            <input type="number" step='0.001' name="inv_dr_amount[]" id="inv_dr_amount0"
                              class="form-control form-control-sm debit_sum" onkeyup="check_total()" min="0">
                          </td>
                        </tr>

                        <tr id='inv_dr_addr1'>
                          <td>
                            <select class="form-control form-control-sm select2" id="inv_debtor1" name="inv_debtor[]">
                              <option value="">Select</option>
                              <?php foreach ($sundry_accounts3 as $row) { ?>
                                <option <?php if ($row->account_id == 1122) echo 'selected'; ?>
                                  value="<?php echo $row->account_id; ?>"><?php echo $row->account_name; ?>
                                </option>
                              <?php } ?>
                            </select>
                          </td>
                          <td>
                            <input type="number" step='0.001' name="inv_dr_amount[]" id="inv_dr_amount1"
                              class="form-control form-control-sm debit_sum" onkeyup="check_total()">
                          </td>
                        </tr>

                        <tr id='inv_dr_addr2'>
                          <td>
                            <select class="form-control form-control-sm select2" id="inv_debtor2" name="inv_debtor[]">
                              <option value="">Select</option>
                              <?php foreach ($sundry_accounts3 as $row) { ?>
                                <option <?php if ($row->account_id == 226) echo 'selected'; ?>
                                  value="<?php echo $row->account_id; ?>"><?php echo $row->account_name; ?>
                                </option>
                              <?php } ?>
                            </select>
                          </td>
                          <td>
                            <input type="number" step='0.001' name="inv_dr_amount[]" id="inv_dr_amount2"
                              class="form-control form-control-sm debit_sum" min="0" onkeyup="check_total()">
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <!-- Credit Table -->
              <div class="col-md-6 col-sm-12 mb-3">
                <div class="x_panel tile fixed_height_320 overflow_hidden">
                  <div class="x_title">
                    <h6><strong>Credit Supplier (Cr)</strong></h6>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                    <table class="table table-bordered table-hover table-sm" id="inv_cr_table">
                      <thead class="bg-light">
                        <tr>
                          <th width="70%">Account</th>
                          <th>Amount (AED)</th>
                        </tr>
                      </thead>
                      <tbody id="inv_cr_body">
                        <tr id='inv_cr_addr0'>
                          <td>
                            <select class="form-control form-control-sm select2" id="inv_creditor0" name="inv_creditor[]">
                              <option value="">Select</option>
                              <?php foreach ($sundry_accounts2 as $row) { ?>
                                <option <?php if ($row->account_id == 228) echo 'selected'; ?>
                                  value="<?php echo $row->account_id; ?>"><?php echo $row->account_name; ?>
                                </option>
                              <?php } ?>
                            </select>
                          </td>
                          <td>
                            <input type="number" step='0.001' name="inv_cr_amount[]" id="inv_cr_amount0"
                              class="form-control form-control-sm credit_sum" onkeyup="check_total()" required min="0">
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div> <!-- /row -->

          </div>
        </div>
<!-- ==================== End Accounts Section ==================== -->


        <!-- Row 7: Submit -->
        <div class="row mb-3">
          <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-success">Submit</button>
          </div>
        </div>
      </div>

    </div>
  </div>

</form>


<script>

//   function updateDrCr(grandTotal, discountAmt, vatAmt, rate = 1) {
//     // Debit (Dr) split: subtotal, discount, VAT
//     const subTotal = grandTotal - vatAmt; // approximate
//     $('#inv_dr_amount0').val((subTotal * rate).toFixed(2));
//     $('#inv_dr_amount1').val((discountAmt * rate).toFixed(2));
//     $('#inv_dr_amount2').val((vatAmt * rate).toFixed(2));

//     // Credit (Cr) = grand total
//     $('#inv_cr_amount0').val((grandTotal * rate).toFixed(2));
// }

function updateDrCr(subTotal, discountAmt, vatAmt, rate = 1) {

    subTotal = parseFloat(subTotal) || 0;
    discountAmt = parseFloat(discountAmt) || 0;
    vatAmt = parseFloat(vatAmt) || 0;

    // Purchase (Debit)
    $('#inv_dr_amount0').val((subTotal * rate).toFixed(2));

    // Discount MUST be negative (contra entry)
    $('#inv_dr_amount1').val(discountAmt > 0 ? (-discountAmt * rate).toFixed(2) : '');

    // VAT (if input VAT is debit)
    $('#inv_dr_amount2').val(vatAmt > 0 ? (vatAmt * rate).toFixed(2) : '');

    // Net payable (Credit)
    const net = subTotal - discountAmt + vatAmt;

    $('#inv_cr_amount0').val((net * rate).toFixed(2));
}
// Trigger this after subtotal recalculation
function calculateAllAndUpdateDrCr() {
    calculateAll(); // your existing function
    updateDrCr();
}
$(document).ready(function() {
  var selected_po_id = <?php echo json_encode(isset($selected_po_id) ? $selected_po_id : ''); ?>;
  if (selected_po_id) {
    $('#po_id').val(selected_po_id).trigger('change');
  }
});

 function get_po_info() {
   var select = document.getElementById("po_id");
  var po_id = document.getElementById("po_id").value.trim();
  var po_type = select.options[select.selectedIndex].getAttribute('data-po-type');

  if (po_id !== '') {
    $.ajax({
      type: "POST",
      url: "<?php echo base_url('index.php/Ajax/ajax_get_po_info'); ?>",
     data: { 
                po_id: po_id,
                po_type: po_type  // <-- include po_type here
            },
      dataType: "json",
      success: function (msg) {
        if (!msg) {
          console.error("No PO info returned.");
          return;
        }

        // Fill in general PO details
        $("#branch_id").val(msg.branch_id);
        $("#branch_name").val(msg.branch_name);
        $("#supplier_id").val(msg.supplier_id);
        $("#supplier_name").val(msg.supplier_name + ' => ' + msg.supplier_contact);
        // Trigger change event to show alert and fetch supplier info
        $("#supplier_name").trigger('change');

        // Fetch PO items
        get_po_items_list(po_id);
        // Totals and amounts
        $("#sub_total").val(msg.subtotal);
        $("#sub_total_fc").val(msg.subtotal);

        $("#discount_per").val(msg.discount_percent);
        $("#discount_amt").val(msg.discount);
        $("#discount_amt_fc").val(msg.discount);

        $("#other_expence").val(msg.other_charge);
        $("#other_expence_fc").val(msg.other_charge);

        $("#total_beforevat").val(msg.total_beforevat);
        $("#total_beforevat_fc").val(msg.total_beforevat);

        $("#vat_per").val(msg.vat_percent);
        $("#vat_amount").val(msg.vat_amt);
        $("#vat_amount_fc").val(msg.vat_amt);

        $("#grand_total").val(msg.grand_total);  
        $("#grand_total_fc").val(msg.grand_total);

        $("#conversion_rate").val(msg.conversion_rate);
        $("#base_currency_grand_total").val(msg.base_currency_grand_total);

        // Second AJAX call: get supplier account ID
        $.ajax({
          type: "POST",
          url: "<?php echo base_url('index.php/Ajax/ajax_get_supplier_accountId_from_po'); ?>",
          data: { po_id: po_id },
          success: function (accid) {
            
            $("#inv_creditor0").val(accid);

            var grand_total  = parseFloat($("#grand_total").val()) || 0;
             var discount_amt = parseFloat($("#discount_amt").val()) || 0;
            // var sub_total    = (parseFloat($("#total_beforevat").val()) || 0)-discount_amt;
           
                var sub_total = parseFloat($("#sub_total").val()) || 0;

            var vat_amt      = parseFloat($("#vat_amount").val()) || 0;
            var crate        = parseFloat($("#crate").val()) || 1;

            // alert(vat_amt);
            // Calculate values safely
            var grand_converted = (grand_total * crate).toFixed(2);
            $("#inv_cr_amount0").val(grand_converted);
            $("#inv_dr_amount0").val((sub_total * crate).toFixed(2));
            $("#inv_dr_amount1").val((discount_amt * crate).toFixed(2));
            $("#inv_dr_amount2").val((vat_amt * crate).toFixed(2));
          },
          error: function () {
            console.error("Error fetching supplier account ID.");
          }
        });
      },
      error: function () {
        console.error("Error fetching PO info.");
      }
    });
  } else {
    $("#po_items_list").html('');
  }
}


  function get_po_items_list(po_id) {  
  $.ajax({
    type: "POST",
    url: "<?php echo base_url() ?>index.php/Ajax/get_po_items_for_grn",
    data: { po_id: po_id },
    success: function (msg) {
      // alert(msg);
      document.getElementById('po_items_list').innerHTML = msg;
     
    }
  });
}

$(document).ready(function () {
   
    $('.barcode-input').on('keypress', function (e) {
        if (e.which === 13) { 
            e.preventDefault(); 
            const next = $(this).closest('tr').next().find('.barcode-input');
            if (next.length) {
                next.focus();
            }
        }
    });

    // auto-fetch item info
    $('.barcode-input').on('change', function () {
        const scannedCode = $(this).val().trim();
        console.log("Scanned Barcode:", scannedCode);

    });
});

function handleBarcodeScan(inputElement) {
    let barcode = inputElement.value.trim();

    // Optional: check if barcode is a specific length before acting
    if (barcode.length >= 8) { // adjust as per your barcode format
        fetchSerialFromBarcode(barcode, inputElement);
    }
}

function fetchSerialFromBarcode(barcode, inputElement) {
    // Example AJAX call to PHP to get serial number
    fetch('get_serial.php?barcode=' + encodeURIComponent(barcode))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Find matching serial input next to this barcode input
                let serialInput = inputElement.closest('div').querySelector('.serial-input');
                if (serialInput) {
                    serialInput.value = data.serial;
                }
            } else {
                alert("Serial not found for barcode: " + barcode);
            }
        })
        .catch(error => {
            console.error('Error fetching serial:', error);
        });
}

$(document).ready(function () {

function calculateRow($row) {
    const qty = parseFloat($row.find('.rec_quantity').val()) || 0;
    const price = parseFloat($row.find('.unit_price').val()) || 0;
    let disPer = parseFloat($row.find('.dis_per').val()) || 0;
    let disAmt = parseFloat($row.find('.dis_amt').val()) || 0;

    const itemQtyInput = $row.find('.qty'); // Ordered quantity
    const orderedQty = parseFloat(itemQtyInput.val()) || 0;
    const errorMsg = $row.find('.error-msg'); // Small tag for error

    // ✅ Validation: Received should not exceed ordered
    if (qty > orderedQty) {
        // Show error
        if (errorMsg.length === 0) {
            // Add error message dynamically if not present
            $row.find('.rec_quantity').after(`<small class="text-danger error-msg">❌ Received quantity cannot exceed ordered quantity.</small>`);
        } else {
            errorMsg.text('❌ Received quantity cannot exceed ordered quantity.');
            errorMsg.show();
        }

        $row.find('.rec_quantity').addClass('is-invalid');
        return 0; // Stop calculation for invalid row
    } else {
        $row.find('.rec_quantity').removeClass('is-invalid');
        errorMsg.hide();
    }

    // ✅ Continue with calculation
    const rowTotal = qty * price;

    const isEditingPer = $row.find('.dis_per').is(':focus');
    const isEditingAmt = $row.find('.dis_amt').is(':focus');

    if (isEditingPer) {
        disAmt = (rowTotal * disPer) / 100;
        $row.find('.dis_amt').val(disAmt.toFixed(2));
    } else if (isEditingAmt) {
        disPer = (rowTotal === 0) ? 0 : (disAmt / rowTotal) * 100;
        $row.find('.dis_per').val(disPer.toFixed(2));
    } else {
        disAmt = (rowTotal * disPer) / 100;
        $row.find('.dis_amt').val(disAmt.toFixed(2));
    }

    const finalRowTotal = rowTotal - disAmt;
    $row.find('.total_price').val(finalRowTotal.toFixed(2));

    return finalRowTotal;
}

// function calculateAll() {
//     let rowSubtotal = 0;

//     // Sum all row totals
//     $('tbody tr').each(function () {
//         rowSubtotal += calculateRow($(this));
//     });

//     $('#sub_total').val(rowSubtotal.toFixed(2));

//     // Handle global discount
//     const isGlobalPer = $('#discount_per').is(':focus');
//     const isGlobalAmt = $('#discount_amt').is(':focus');

//     let globalDiscountPer = parseFloat($('#discount_per').val()) || 0;
//     let globalDiscountAmt = parseFloat($('#discount_amt').val()) || 0;

//     if (isGlobalPer) {
//         globalDiscountAmt = (rowSubtotal * globalDiscountPer) / 100;
//         $('#discount_amt').val(globalDiscountAmt.toFixed(2));
//     } else if (isGlobalAmt) {
//         globalDiscountPer = (rowSubtotal === 0) ? 0 : (globalDiscountAmt / rowSubtotal) * 100;
//         $('#discount_per').val(globalDiscountPer.toFixed(2));
//     } else {
//         globalDiscountAmt = (rowSubtotal * globalDiscountPer) / 100;
//         $('#discount_amt').val(globalDiscountAmt.toFixed(2));
//     }

//     const afterDiscount = rowSubtotal - globalDiscountAmt;

//     // Apply VAT
//     const vatPer = parseFloat($('#vat_per').val()) || 0;
//     const vatAmt = (afterDiscount * vatPer) / 100;
//     $('#vat_amount').val(vatAmt.toFixed(2));

//     const grandTotal = afterDiscount + vatAmt;
//     $('#grand_total').val(grandTotal.toFixed(2));
// }

function calculateAll() {
    let rowSubtotal = 0;
     let totalBeforeVAT = 0; 

    // Sum all row totals from each row
    $('tbody tr').each(function () {
        rowSubtotal += calculateRow($(this));
    });

    // ✅ AED Subtotal
    $('#sub_total').val(rowSubtotal.toFixed(2));

    // ✅ FC Subtotal
    const currencyRate = parseFloat($('#currency_rate').val()) || 1; // Default 1 (AED)
    const rowSubtotalFC = rowSubtotal * currencyRate;
    $('#sub_total_fc').val(rowSubtotalFC.toFixed(2));


// ✅ Update total_beforevat fields
    $('#total_beforevat').val(totalBeforeVAT.toFixed(2));
    $('#total_beforevat_fc').val((totalBeforeVAT * currencyRate).toFixed(2));



    // Handle global discount
    const isGlobalPer = $('#discount_per').is(':focus');
    const isGlobalAmt = $('#discount_amt').is(':focus');

    let globalDiscountPer = parseFloat($('#discount_per').val()) || 0;
    let globalDiscountAmt = parseFloat($('#discount_amt').val()) || 0;

    if (isGlobalPer) {
        globalDiscountAmt = (rowSubtotal * globalDiscountPer) / 100;
        $('#discount_amt').val(globalDiscountAmt.toFixed(2));
    } else if (isGlobalAmt) {
        globalDiscountPer = (rowSubtotal === 0) ? 0 : (globalDiscountAmt / rowSubtotal) * 100;
        $('#discount_per').val(globalDiscountPer.toFixed(2));
    } else {
        globalDiscountAmt = (rowSubtotal * globalDiscountPer) / 100;
        $('#discount_amt').val(globalDiscountAmt.toFixed(2));
    }

    // AED after discount
    const afterDiscount = rowSubtotal - globalDiscountAmt;
    // FC after discount
    const afterDiscountFC = afterDiscount * currencyRate;

    // Apply VAT
    const vatPer = parseFloat($('#vat_per').val()) || 0;
    const vatAmt = (afterDiscount * vatPer) / 100;
    const vatAmtFC = vatAmt * currencyRate;

    $('#vat_amount').val(vatAmt.toFixed(2));
    $('#vat_amount_fc').val(vatAmtFC.toFixed(2));

    // Grand total
    const grandTotal = afterDiscount + vatAmt;
    const grandTotalFC = afterDiscountFC + vatAmtFC;

    $('#grand_total').val(grandTotal.toFixed(2));
    $('#grand_total_fc').val(grandTotalFC.toFixed(2));

        updateDrCr(rowSubtotal, globalDiscountAmt, vatAmt, currencyRate);

}

// Trigger calculations
$(document).on('keyup change', '.rec_quantity, .unit_price, .dis_per, .dis_amt', calculateAll);
$('#discount_per, #discount_amt, #vat_per').on('keyup change', calculateAll);

// On load
calculateAll();
});
document.addEventListener('input', function (e) {

    if (!e.target.classList.contains('rec_quantity')) return;

    const input = e.target;
    const idSuffix = input.id.replace('rec_quantity', '');

    const orderedInput = document.getElementById('item_quantity' + idSuffix);
    const preInput = document.getElementById('pre_quantity' + idSuffix);
    const errorMsg = document.getElementById('error_msg' + idSuffix);

    const orderedQty = parseFloat(orderedInput?.value) || 0;
    const preQty = parseFloat(preInput?.value) || 0;
    const receivedQty = parseFloat(input.value) || 0;

    const remainingQty = orderedQty - preQty;

    console.log("DEBUG:", {
        orderedQty,
        preQty,
        remainingQty,
        receivedQty
    });

    if (receivedQty > remainingQty) {
        errorMsg.textContent = "❌ Max allowed: " + remainingQty;
        errorMsg.style.display = "block";
        input.classList.add('is-invalid');
    } else {
        errorMsg.textContent = "";
        errorMsg.style.display = "none";
        input.classList.remove('is-invalid');
    }

});

function test(event) {
    const input = event.target;
    const index = input.dataset.index;
    const quantity = parseInt(input.value) || 0;
    const container = document.getElementById(`serial_container${index}`);

    container.innerHTML = ''; // Clear existing serials

    for (let i = 0; i < quantity; i++) {
        const inputEl = document.createElement('input');
        inputEl.type = 'text';
        inputEl.name = `serial[${index}][]`; // Very important!
        inputEl.className = 'form-control serial-input mt-1';
        inputEl.placeholder = `Serial ${i + 1}`;
        inputEl.autocomplete = 'off';
        container.appendChild(inputEl);
    }
}

// Handle Enter key navigation
document.addEventListener('keypress', function (e) {
  if (e.target.classList.contains('serial-input') && e.key === 'Enter') {
    e.preventDefault();

    const container = e.target.closest('.serial-container');
    const inputs = container.querySelectorAll('.serial-input');

    for (let input of inputs) {
      if (!input.value.trim()) {
        input.focus();
        break;
      }
    }
  }
});
$(document).ready(function() {

    $("#main").on("submit", function(e) {
        let isValid = true;
        let errorMsg = "";

        // --- Basic field validation ---
        if ($("#po_id").val() === "") {
            errorMsg += "• Please select a Purchase Order.\n";
            isValid = false;
        }

        if ($("#branch_id").val() === "") {
            errorMsg += "• Branch information is missing.\n";
            isValid = false;
        }

        if ($("#supplier_id").val() === "") {
            errorMsg += "• Supplier information is missing.\n";
            isValid = false;
        }

        if ($("#warehouse_id").val() === "") {
            errorMsg += "• Please select a Warehouse.\n";
            isValid = false;
        }

        if ($("#grn_date").val() === "") {
            errorMsg += "• Please enter GRN Date.\n";
            isValid = false;
        }

        // --- Item row validation ---
        let hasValidItem = false;

        $("#po_items_list tbody tr").each(function(index) {
            const $row = $(this);
            const itemName = $row.find(".item_name, select[name='item_id[]']").val();
            const orderedQty = parseFloat($row.find(".qty, .item_quantity").val()) || 0;
            const receivedQty = parseFloat($row.find(".rec_quantity").val()) || 0;
            const unitPrice = parseFloat($row.find(".unit_price").val()) || 0;

            // Skip empty rows
            if (!itemName && receivedQty === 0 && unitPrice === 0) return;

            // alert(receivedQty);

            // Check quantity
            if (isNaN(receivedQty) || receivedQty <= 0) {
                errorMsg += `• Row ${index + 1}: Enter valid received quantity.\n`;
                isValid = false;
            } else if (receivedQty > orderedQty) {
                errorMsg += `• Row ${index + 1}: Received quantity cannot exceed ordered quantity (${orderedQty}).\n`;
                isValid = false;
            }

            // Check price
            if (isNaN(unitPrice) || unitPrice <= 0) {
                errorMsg += `• Row ${index + 1}: Enter valid unit price.\n`;
                isValid = false;
            }

            // If everything is valid for this row
            if (itemName && receivedQty > 0 && unitPrice > 0 && receivedQty <= orderedQty) {
                hasValidItem = true;
            }
        });

        // if (!hasValidItem) {
        //     errorMsg += "• Please add at least one valid item with received quantity and price.\n";
        //     isValid = false;
        // }

        // --- Grand total validation ---
        const grandTotal = parseFloat($("#grand_total").val()) || 0;
        if (grandTotal <= 0) {
            errorMsg += "• Grand Total must be greater than 0.\n";
            isValid = false;
        }

        // --- Final decision ---
        if (!isValid) {
            e.preventDefault();
            alert("Please correct the following errors:\n\n" + errorMsg);
        }
    });

    // Prevent negative numbers
    $(document).on("input", "input[type='number']", function() {
        if (parseFloat($(this).val()) < 0) $(this).val("");
    });
});
// function check_total() {
	

// 		var a1 = parseFloat(document.getElementById("inv_dr_amount0").value);
// 		var a2 = parseFloat(document.getElementById("inv_dr_amount1").value);
// 		var a3 = parseFloat(document.getElementById("inv_dr_amount2").value);
// 		var k_total = parseFloat(a1 + a2 - a3).toFixed(2);

// 		var dr_total = $('#inv_cr_amount0').val();

// 		if (parseFloat(k_total) != parseFloat(dr_total)) {
// 			alert("Both debit total and credit total must match");
// 		}
// 	}

// function check_total() {
//     // Sum all debit inputs
//     let debit_total = 0;
//     $('.debit_sum').each(function() {
//         let val = parseFloat($(this).val()) || 0;
//         debit_total += val;
//     });

//     // Sum all credit inputs
//     let credit_total = 0;
//     $('.credit_sum').each(function() {
//         let val = parseFloat($(this).val()) || 0;
//         credit_total += val;
//     });

//     // Round to 2 decimals
//     debit_total = debit_total.toFixed(2);
//     credit_total = credit_total.toFixed(2);

//     // Show alert if mismatch
//     if (parseFloat(debit_total) !== parseFloat(credit_total)) {
//         alert("Both debit total and credit total must match.\nDebit: " + debit_total + "\nCredit: " + credit_total);
//     }
// }

function check_total() {

    let debit_total = 0;
    $('.debit_sum').each(function () {
        debit_total += parseFloat($(this).val()) || 0;
    });

    let credit_total = 0;
    $('.credit_sum').each(function () {
        credit_total += parseFloat($(this).val()) || 0;
    });

    // round properly
    debit_total = Number(debit_total.toFixed(2));
    credit_total = Number(credit_total.toFixed(2));

    // compare
    if (debit_total !== credit_total) {
        alert(
            "Both debit total and credit total must match.\n" +
            "Debit: " + debit_total.toFixed(2) + "\n" +
            "Credit: " + credit_total.toFixed(2)
        );
        return false;
    }

    return true;
}
  
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const currencyEl = document.getElementById("currency");
  const rateEl = document.getElementById("currancy_value");

  // Attach event listeners
  currencyEl.addEventListener("change", updateAllFCValues);
  rateEl.addEventListener("input", updateAllFCValues);

  // Trigger initial conversion on load (optional)
  updateAllFCValues();
});

function updateAllFCValues() {
  const currency = document.getElementById("currency")?.value || "";
  const rate = parseFloat(document.getElementById("currancy_value")?.value) || 1;

  const convert = (baseValue) => {
    const val = parseFloat(baseValue) || 0;
    return (currency === "AED" || currency === "") 
      ? val 
      : parseFloat((val * rate).toFixed(2));
  };

  const fields = [
    "sub_total",
    "discount_amt",
    "other_expence",
    "vat_amount",
    "total_beforevat",
    "grand_total"
  ];

  fields.forEach(id => {
    const base = document.getElementById(id);
    const fc = document.getElementById(id + "_fc");

    if (base && fc) {
      const baseVal = base.value.trim();
      if (baseVal !== "") {
        fc.value = convert(baseVal);
      } else {
        fc.value = "";
      }
    }
  });
}

  function get_supplier_info() {
		var supplier_id = document.getElementById("supplier_id").value;

		if (supplier_id != '') {
			$.ajax({
				async: "false",
				type: "POST",
				url: "<?php echo base_url() ?>index.php/Ajax/ajax_get_supplier_info",
				data: { supplier_id: supplier_id },
				dataType: "json",
				success: function (result) {
            document.getElementById("currency").value = result.currency_abbr;
				}
			});
		}
		else {
      document.getElementById("currency").value = '';
		}
	} 

</script>
