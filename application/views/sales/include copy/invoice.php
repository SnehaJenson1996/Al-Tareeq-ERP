<form action="<?= base_url() ?>index.php/Sales/create_invoice" method="post">
  <div class="x_content">
    <div class="well" style="overflow: auto">
      <input type="hidden" name="enquiry_id_inv" id="enquiry_id_inv" value="">
      <input type="hidden" name="quotation_id_inv" id="quotation_id_inv" value="">
      <input type="hidden" name="so_id_inv" id="so_id_inv" value="">
      <input type="hidden" name="delivery_challan_id_inv" id="delivery_challan_id_inv" value="">
      <input type="hidden" name="branch_id_inv" id="branch_id_inv" value="">
      <input type="hidden" name="selected_bank" id="selected_bank_hidden">

      <div class="row">
        <div class="col-md-6">
          <label class="control-label col-md-3 col-sm-3 col-xs-3">Select Delivery challan</label>
          <div class="col-sm-9 col-xs-9">
            <select class="form-control" name="delivery_challan_id" id="delivery_challan_id">
              <option>--Select --</option>
              <?php if (!empty($delivery_challan_list)): ?>
                <?php foreach ($delivery_challan_list as $del): ?>
                  <option
                    value="<?= $del['del_id'] ?>"
                    data-so_id="<?= $del['so_id'] ?>"
                    data-qtn_id="<?= $del['quotation_id'] ?>"
                    data-enq_id="<?= $del['enquiry_id'] ?>">
                    <?= htmlspecialchars($del['delivery_code']) ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <label class="control-label col-md-2 col-sm-3 col-xs-3">Sales order</label>
          <div class="col-sm-9 col-xs-9">
            <input type="text" class="form-control" name="sales_order_code" id="sales_order_code" readonly>
          </div>


        </div>
      </div><!-- row -->

      <div class="row mt-3">
        <div class="col-md-6">
          <label class="control-label col-md-3 col-sm-3 col-xs-3">Invoice Code</label>
          <div class="col-md-9 col-sm-9 col-xs-9">
            <input type="text" class="form-control" name="invoice_code" id="invoice_code" readonly>
          </div>
        </div>
        <div class="col-md-6">
          <label class="control-label col-md-2 col-sm-3 col-xs-3">Invoice Date</label>
          <div class="col-md-9 col-sm-9 col-xs-9">
            <input type="date" class="form-control" name="invoice_date" id="invoice_date">
          </div>
        </div>


      </div><!-- row -->

      <div class="row mt-3">
        <div class="col-md-6">
          <label class="control-label col-md-3 col-sm-3 col-xs-3">Quotation Code</label>
          <div class="col-md-9 col-sm-9 col-xs-9">
            <input type="text" class="form-control" name="inv_uotation_code" id="inv_uotation_code">
          </div>
        </div>

        <div class="col-md-6">
          <label class="control-label col-md-2 col-sm-3 col-xs-3">Quotation Date</label>
          <div class="col-md-9 col-sm-9 col-xs-9">
            <input type="date" class="form-control" name="inv_quotation_date" id="inv_quotation_date">
          </div>
        </div>

      </div><!-- row -->

      <div class="row mt-3">
        <div class="col-md-6">
          <label class="control-label col-md-3 col-sm-3 col-xs-3">Customer Name</label>
          <div class="col-md-9 col-sm-9 col-xs-9">
            <input type="text" class="form-control" name="inv_customer_name" id="inv_customer_name">
          </div>
        </div>

        <div class="col-md-6">
          <label class="control-label col-md-2 col-sm-3 col-xs-3">Customer TRN </label>
          <div class="col-md-9 col-sm-9 col-xs-9">
            <input type="text" class="form-control" name="inv_customer_trn" id="inv_customer_trn">
          </div>
        </div>
      </div><!-- row -->

      <div class="row mt-3">
        <div class="col-md-6">
          <label class="control-label col-md-3 col-sm-3 col-xs-3">Delivery Mode</label>
          <div class="col-md-9 col-sm-9 col-xs-9">
            <input type="text" class="form-control" name="inv_delivery_mode" id="inv_delivery_mode">
          </div>
        </div>

        <div class="col-md-6">
          <label class="control-label col-md-2 col-sm-3 col-xs-3">Deliverd By</label>
          <div class="col-md-9 col-sm-9 col-xs-9">
            <input type="text" class="form-control" name="inv_deliverd_by" id="inv_deliverd_by"></select>
          </div>
        </div>
      </div><!-- row -->
      <div class="row mt-3">
        <div class="col-md-6">
          <label class="control-label col-md-3 col-sm-3 col-xs-3">Branch</label>
          <div class="col-md-9 col-sm-9 col-xs-9">
            <input type="text" class="form-control" name="inv_branch_name" id="inv_branch_name">
          </div>
        </div>


      </div><!-- row -->

    </div><!-- /.well -->
  </div><!-- /.x_content -->
  <div class="row">
    <div class="col-md-12" style="overflow:scroll;">
      <div id="products_table_div" class="x_content">

      </div>
    </div>
  </div><!-- row -->


  <div class="x_content mt-3">
    <!-- 🔹 Row 1: Subtotal, Discount, Total Before VAT -->
    <div class="row mb-2">
      <!-- Sub Total -->
      <label class="control-label col-md-1 col-sm-3 col-xs-3">Sub Total</label>
      <div class="col-md-2 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_sub_total" id="inv_sub_total" readonly>
      </div>

      <!-- Discount (%) -->
      <label class="control-label col-md-1 col-sm-3 col-xs-3">Discount(%)</label>
      <div class="col-md-1 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_discount_per" id="inv_discount_per">
      </div>

      <!-- Discount Amount -->
      <div class="col-md-1 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_discount_amt" id="inv_discount_amt">
      </div>

      <!-- Total Before VAT -->
      <label class="control-label col-md-1 col-sm-3 col-xs-3">Total Before VAT</label>
      <div class="col-md-2 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_total_before_vat" id="inv_total_before_vat" readonly>
      </div>
    </div>

    <!-- 🔹 Row 2: VAT and Grand Total -->
    <div class="row">
      <!-- VAT (%) -->
      <label class="control-label col-md-1 col-sm-3 col-xs-3">VAT(%)</label>
      <div class="col-md-1 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_vat_per" id="inv_vat_per">
      </div>

      <!-- VAT Amount -->
      <div class="col-md-1 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_vat_amount" id="inv_vat_amount" readonly>
      </div>

      <!-- Grand Total -->
      <label class="control-label col-md-1 col-sm-3 col-xs-3">Grand Total</label>
      <div class="col-md-2 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_grand_total" id="inv_grand_total" readonly>
      </div>
    </div>
  </div>




  <div class="row mt-3">
    <!-- Payment Mode -->
    <label class="control-label col-md-1 col-sm-3 col-xs-3">Payment Mode</label>
    <div class="col-md-2 col-sm-9 col-xs-9">
      <select class="form-control" name="inv_payment_mode" id="inv_payment_mode">
        <option value="">-- Select --</option>
        <option value="cash">Cash</option>
        <option value="card">Card</option>
        <option value="bank">Bank Transfer</option>
        <option value="cheque">Cheque</option>
      </select>
    </div>

    <!-- Advance (Percentage + Amount in same row) -->
    <label class="control-label col-md-2 col-sm-3 col-xs-3">Payment/Advance Received</label>
    <div class="col-md-3 col-sm-9 col-xs-9 d-flex">
      <input type="number" class="form-control" name="inv_advance_per" id="inv_advance_per" placeholder="%" style="width: 30%; margin-right:5px;">
      <input type="text" class="form-control" name="inv_advance_amt" id="inv_advance_amt" placeholder="0.00" style="width: 70%;">
    </div>


    Balance
    <label class="control-label col-md-1 col-sm-3 col-xs-3">Balance</label>
    <div class="col-md-2 col-sm-9 col-xs-9">
      <input type="text" class="form-control" name="inv_balance_amt" id="inv_balance_amt" readonly>
    </div>
  </div>
  <!-- </div> -->
  <!--Bank account details--->
  <div class="x_content well mt-4 inv_bank_details">

  </div>


  <div class="x_content well mt-4">
    <h5><strong>General Terms</strong></h5>
    <div class="row">
      <label class="control-label col-md-2 col-sm-3 col-xs-3">Validity</label>
      <div class="col-md-3 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_validity" id="inv_validity">
      </div>

      <label class="control-label col-md-2 col-sm-3 col-xs-3">Payment Terms</label>
      <div class="col-md-3 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_payment_terms" id="inv_payment_terms">
      </div>
    </div>

    <div class="row mt-3">
      <label class="control-label col-md-2 col-sm-3 col-xs-3">Delivery Terms</label>
      <div class="col-md-3 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_delivery_terms" id="inv_delivery_terms">
      </div>

      <label class="control-label col-md-2 col-sm-3 col-xs-3">Terms and condition</label>
      <div class="col-md-3 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_general_terms" id="inv_general_terms">
      </div>
    </div>

    <div class="row mt-3">
      <label class="control-label col-md-2 col-sm-3 col-xs-3">Sales person</label>
      <div class="col-md-3 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_sales_person" id="inv_sales_person">
      </div>

      <label class="control-label col-md-2 col-sm-3 col-xs-3">Remarks</label>
      <div class="col-md-3 col-sm-9 col-xs-9">
        <textarea class="form-control" name="inv_remarks" id="inv_remarks"></textarea>
      </div>
    </div>
  </div>
  <!-- ========================Accounts entry=============================== -->

  <div class="x_content well mt-4">
    <h5><strong>Sales Invoice Account Entry</strong></h5>

    <div class="row mt-3">
      <!-- Debit Table -->
      <div class="col-md-6 col-sm-12">
        <label class="control-label"><strong>Debit Customer (Dr)</strong></label>
        <table class="table table-bordered table-hover" id="inv_dr_table">
          <thead>
            <tr>
              <th>Account</th>
              <th>Amount (AED)</th>
            </tr>
          </thead>
          <tbody id="inv_dr_body">
            <tr id="inv_dr_addr0">
              <td>
                <select class="form-control select2" id="inv_debtor0" name="inv_debtor[]">
                  <option value="">Select</option>
                  <?php foreach ($sundry_accounts1 as $row) { ?>
                    <!-- <option <?php //if ($row->account_id == 1125) echo 'selected'; 
                                  ?>  -->
                    <option <?php if ($row->account_id == $enquiry_customer_id) echo 'selected'; ?> value="<?php echo $row->account_id; ?>">
                      <?php echo $row->account_name; ?>
                    </option>
                  <?php } ?>
                </select>
              </td>
              <td>
                <input type="number" step="0.001" name="inv_dr_amount[]" id="inv_dr_amount0"
                  class="form-control debit_sum" min="0">
              </td>
            </tr>
            <tr id="inv_dr_addr1"></tr>
          </tbody>
        </table>
      </div>

      <!-- Credit Table -->
      <div class="col-md-6 col-sm-12">
        <label class="control-label"><strong>Credit Account (Cr)</strong></label>
        <table class="table table-bordered table-hover" id="inv_cr_table">
          <thead>
            <tr>
              <th>Account</th>
              <th>Amount (AED)</th>
              <th width="10%">
                <a id="inv_cr_add_row" title="Add" class="btn btn-sm bg-orange">
                  <span class="fa fa-plus"></span>
                </a>
              </th>
            </tr>
          </thead>
          <tbody id="inv_cr_body">
            <tr id="inv_cr_addr0">
              <td>
                <select class="form-control select2" id="inv_creditor0" name="inv_creditor[]">
                  <option value="">Select</option>
                  <?php foreach ($sundry_accounts2 as $row) { ?>
                    <option <?php if ($row->account_id == 1125) echo 'selected'; ?>
                      value="<?php echo $row->account_id; ?>">
                      <?php echo $row->account_name; ?>
                    </option>
                  <?php } ?>
                </select>
              </td>
              <td>
                <input type="number" step="0.001" name="inv_cr_amount[]" id="inv_cr_amount0"
                  class="form-control credit_sum" min="0">
              </td>
              <td>
                <a title="Delete" onclick="remove_row_inv_cr(0)" class="btn btn-xs bg-orange">
                  <span class="fa fa-trash"></span>
                </a>
              </td>
            </tr>

            <tr id="inv_cr_addr1">
              <td>
                <select class="form-control select2" id="inv_creditor1" name="inv_creditor[]">
                  <option value="">Select</option>
                  <?php foreach ($sundry_accounts3 as $row) { ?>
                    <option <?php if ($row->account_id == 228) echo 'selected'; ?>
                      value="<?php echo $row->account_id; ?>">
                      <?php echo $row->account_name; ?>
                    </option>
                  <?php } ?>
                </select>
              </td>
              <td>
                <input type="number" step="0.001" name="inv_cr_amount[]" id="inv_cr_amount1"
                  class="form-control credit_sum" min="0">
              </td>
              <td>
                <a title="Delete" onclick="remove_row_inv_cr(1)" class="btn btn-xs bg-orange">
                  <span class="fa fa-trash"></span>
                </a>
              </td>
            </tr>

            <tr id="inv_cr_addr2">
              <td>
                <select class="form-control select2" id="inv_creditor2" name="inv_creditor[]">
                  <option value="">Select</option>
                  <?php foreach ($sundry_accounts3 as $row) { ?>
                    <option <?php if ($row->account_id == 1122) echo 'selected'; ?>
                      value="<?php echo $row->account_id; ?>">
                      <?php echo $row->account_name; ?>
                    </option>
                  <?php } ?>
                </select>
              </td>
              <td>
                <input type="number" step="0.001" name="inv_cr_amount[]" id="inv_cr_amount2"
                  class="form-control credit_sum" min="0">
              </td>
              <td>
                <a title="Delete" onclick="remove_row_inv_cr(2)" class="btn btn-xs bg-orange">
                  <span class="fa fa-trash"></span>
                </a>
              </td>
            </tr>

            <tr id="inv_cr_addr3">
              <td>
                <select class="form-control select2" id="inv_creditor3" name="inv_creditor[]">
                  <option value="">Select</option>
                  <?php foreach ($sundry_accounts3 as $row) { ?>
                    <option <?php if ($row->account_id == 694) echo 'selected'; ?>
                      value="<?php echo $row->account_id; ?>">
                      <?php echo $row->account_name; ?>
                    </option>
                  <?php } ?>
                </select>
              </td>
              <td>
                <input type="number" step="0.001" name="inv_cr_amount[]" id="inv_cr_amount3"
                  class="form-control credit_sum" min="0" value="0">
              </td>
              <td>
                <a title="Delete" onclick="remove_row_inv_cr(3)" class="btn btn-xs bg-orange">
                  <span class="fa fa-trash"></span>
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- ============================================================= -->

  <div class="x_content well mt-4">
    <h5><strong>Billing Details</strong></h5>
    <div class="row">
      <label class="control-label col-md-2 col-sm-3 col-xs-3">Billing Customer name</label>
      <div class="col-md-3 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_billing_customer" id="inv_billing_customer">
      </div>

      <label class="control-label col-md-2 col-sm-3 col-xs-3">Shipping Customer name</label>
      <div class="col-md-3 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_shipping_customer" id="inv_shipping_customer">
      </div>
    </div>
    <div class="row mt-3">
      <label class="control-label col-md-2 col-sm-3 col-xs-3">Billing Address</label>
      <div class="col-md-3 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_billing_address" id="inv_billing_address">
      </div>

      <label class="control-label col-md-2 col-sm-3 col-xs-3">Shipping Address</label>
      <div class="col-md-3 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_shipping_address" id="inv_shipping_address">
      </div>
    </div>

    <div class="row mt-3">
      <label class="control-label col-md-2 col-sm-3 col-xs-3">Billing Contact</label>
      <div class="col-md-3 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_billing_contact" id="inv_billing_contact">
      </div>

      <label class="control-label col-md-2 col-sm-3 col-xs-3">Shipping contact</label>
      <div class="col-md-3 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_shipping_contact" id="inv_shipping_contact">
      </div>
    </div>

    <div class="row mt-3">
      <label class="control-label col-md-2 col-sm-3 col-xs-3">Billing email</label>
      <div class="col-md-3 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_billing_email" id="inv_billing_email">
      </div>

      <label class="control-label col-md-2 col-sm-3 col-xs-3">Shipping email</label>
      <div class="col-md-3 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_shipping_email" id="inv_shipping_email">
      </div>
    </div>


    <div class="row mt-3">
      <label class="control-label col-md-2 col-sm-3 col-xs-3">Billing City</label>
      <div class="col-md-3 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_billing_city" id="inv_billing_city">
      </div>

      <label class="control-label col-md-2 col-sm-3 col-xs-3">Shipping City</label>
      <div class="col-md-3 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="inv_shipping_city" id="inv_shipping_city">
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-md-12">
        <button type="submit" class="btn btn-primary">Cancel</button>
        <button type="submit" class="btn btn-success">Submit</button>
      </div>
    </div>
  </div>
</form>
<script>
  $('#delivery_challan_id').change(function() {
    var del_id = $(this).val();
    var so_id = $(this).find(':selected').data('so_id');
    var qtn_id = $(this).find(':selected').data('qtn_id');
    var enq_id = $(this).find(':selected').data('enq_id');

    if (del_id) {
      $.ajax({
        url: '<?= base_url("index.php/sales/get_delivery_challan_invoice") ?>',
        type: 'POST',
        data: {
          del_id: del_id,
          so_id: so_id,
          qtn_id: qtn_id,
          enq_id: enq_id
        },
        dataType: 'json',
        success: function(res) {
          let subTotal = parseFloat(res.so_master.sub_total) || 0;
          let discountAmt = parseFloat(res.so_master.discount_amount) || 0;
          let totalBeforeVat = subTotal - discountAmt;

          // Main invoice details
          //alert(res.enquiry_master.branch_id);
          $('#delivery_challan_id_inv').val(del_id);
          $('#enquiry_id_inv').val(enq_id);
          $('#quotation_id_inv').val(qtn_id);
          $('#branch_id_inv').val(res.enquiry_master.branch_id);

          $('#so_id_inv').val(so_id);
          $('#products_table_div').html(res.products_html);
          $('#inv_remarks').html(res.del_master.remark);
          $('.inv_bank_details').html(res.bank_table);
          $('#sales_order_code').val(res.so_master.so_code || '');
          $('#invoice_code').val(res.invoice_code || '');

          $('#inv_sub_total').val(subTotal.toFixed(2));
          //$('#inv_sub_total').val(res.so_master.sub_total || '');

          $('#inv_discount_per').val(res.so_master.discount_percentage || '');
          // $('#inv_discount_amt').val(res.so_master.discount_amount || '');
          $("#inv_discount_amt").val(discountAmt.toFixed(2));

          $("#inv_total_before_vat").val(totalBeforeVat.toFixed(2));
          $('#payment_terms').val(res.so_master.total_before_vat || '');
          $('#inv_vat_per').val(res.so_master.vat_percentage || '');
          $('#inv_vat_amount').val(res.so_master.vat_amount || '');
          $('#inv_grand_total').val(res.so_master.grand_total || '');
          $('#remarks').val(res.so_master.remarks || '');
          $('#inv_general_terms').val(res.so_master.terms_and_condition || '');
          $('#inv_delivery_terms').val(res.so_master.delivery_term || '');
          $('#inv_payment_terms').val(res.so_master.payment_term || '');
          $('#inv_validity').val(res.so_master.validity || '');

          ///Accounts data


          $("#inv_dr_amount0").val(res.so_master.grand_total || '');
          $("#inv_cr_amount0").val(res.so_master.sub_total || '');
          $("#inv_cr_amount1").val(res.so_master.vat_amount || '');
          $("#inv_cr_amount2").val(res.so_master.discount_amount || '');
          //-----------

          $('#inv_billing_customer').val(res.so_address.billing_customer_name || '');
          $('#inv_billing_address').val(res.so_address.billing_customer_address || '');
          $('#inv_billing_city').val(res.so_address.billing_emirates || '');
          $('#inv_billing_contact').val(res.so_address.billing_contact || '');
          $('#inv_billing_email').val(res.so_address.billing_email || '');

          $('#inv_shipping_customer').val(res.so_address.shipping_customer || '');
          $('#inv_shipping_address').val(res.so_address.shipping_address || '');
          $('#inv_shipping_city').val(res.so_address.shipping_emirate || '');
          $('#inv_shipping_contact').val(res.so_address.shipping_contact || '');
          $('#inv_shipping_email').val(res.so_address.shipping_email || '');


          $('#customer_name').val(res.enquiry_master.enquiry_code || '');
          $('#inv_customer_name').val(res.enquiry_master.customer_name || '');
          $('#inv_branch_name').val(res.enquiry_master.branch_name || '');
          $('#inv_customer_trn').val(res.enquiry_master.customer_TR_no || '');

          $('#inv_delivery_mode').val(res.del_master.delivery_mode || '');
          $('#inv_deliverd_by').val(res.del_master.deliverd_by || '');
          $('#inv_sales_person').val(res.enquiry_master.user_name || '');
          $('#inv_uotation_code').val(res.qtn_master.quotation_code || '');
          $('#inv_quotation_date').val(res.qtn_master.quotation_date || '');

        }
      });
    }
  });
  $(document).ready(function() {
    // When percentage changes → update amount & balance
    $("#inv_advance_per").on("input", function() {
      let grandTotal = parseFloat($("#inv_grand_total").val()) || 0;
      let advancePer = parseFloat($(this).val()) || 0;

      // Cap percentage to 100
      if (advancePer > 100) {
        advancePer = 100;
        $(this).val(100);
      }

      let advanceAmt = (grandTotal * advancePer / 100).toFixed(2);
      $("#inv_advance_amt").val(advanceAmt);

      // Balance = Grand total - Advance
      let balanceAmt = (grandTotal - advanceAmt).toFixed(2);
      $("#inv_balance_amt").val(balanceAmt);
    });

    // When advance amount changes → update percentage & balance
    $("#inv_advance_amt").on("input", function() {
      let grandTotal = parseFloat($("#inv_grand_total").val()) || 0;
      let advanceAmt = parseFloat($(this).val()) || 0;

      if (advanceAmt > grandTotal) {
        advanceAmt = grandTotal;
        $(this).val(advanceAmt.toFixed(2));
      }

      let advancePer = ((advanceAmt / grandTotal) * 100).toFixed(2);
      $("#inv_advance_per").val(advancePer);

      // Balance
      let balanceAmt = (grandTotal - advanceAmt).toFixed(2);
      $("#inv_balance_amt").val(balanceAmt);
    });
  });
  // Recalculate totals dynamically
  function calculateTotals() {
    let subTotal = parseFloat($("#inv_sub_total").val()) || 0;
    let discountPer = parseFloat($("#inv_discount_per").val()) || 0;
    let discountAmt = parseFloat($("#inv_discount_amt").val()) || 0;

    // If percentage entered, calculate discount amount
    if (discountPer > 0) {
      discountAmt = (subTotal * discountPer) / 100;
      $("#inv_discount_amt").val(discountAmt.toFixed(2));
      //Accounts discount amount
      $("#inv_cr_amount2").val(discountAmt.toFixed(2));
    }

    // Calculate total before VAT
    let totalBeforeVat = subTotal - discountAmt;
    $("#inv_total_before_vat").val(totalBeforeVat.toFixed(2));

    // VAT calculation
    let vatPer = parseFloat($("#inv_vat_per").val()) || 0;
    let vatAmt = (totalBeforeVat * vatPer) / 100;
    $("#inv_vat_amount").val(vatAmt.toFixed(2));
    $("#inv_cr_amount1").val(vatAmt.toFixed(2));

    // Grand total
    let grandTotal = totalBeforeVat + vatAmt;
    $("#inv_grand_total").val(grandTotal.toFixed(2));
    $("#inv_dr_amount0").val(grandTotal.toFixed(2));



  }

  // Trigger recalculation on input
  $("#inv_discount_per, #inv_discount_amt, #inv_vat_per").on("input", calculateTotals);
  $(document).on('change', "input[name='selected_bank']", function() {
    $('#selected_bank_hidden').val($(this).val());
  });
</script>