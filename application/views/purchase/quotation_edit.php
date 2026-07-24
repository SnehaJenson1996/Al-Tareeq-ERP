<?php
$page_name2 = 'Purchase/purchase_quotation_list';
$user = $this->session->userdata('user_id');
?>
<style>
  .form-control {
    font-size: 12px;
  }
</style>
<form id="main" method="post" action="<?php echo base_url() . 'index.php/'; ?>Purchase/update_purchase_quotation" autocomplete="off" enctype="multipart/form-data">


  <!-- page content -->
  <div class="form-group" role="main">
    <div class="">
      <div class="page-title"></div>
      <div class="clearfix"></div>
      <div class="x_content" style="font-size:12px;">
        <div class="well">

          <!-- Row 1: Select RFQ, Code, Revision -->
          <div class="row mb-3">
            <div class="col-md-4">
              <label for="rfq_id" class="form-label">Select RFQ</label>
              <select class="form-control" id="rfq_id" name="rfq_id" readonly>
                <option value="<?php echo $records1[0]->reference; ?>">
                  <?php echo $records1[0]->reference; ?>
                </option>
              </select>
            </div>
            <div class="col-md-4">
              <label for="quotation_code" class="form-label">Code</label>
              <input type="text" class="form-control" name="quotation_code" id="quotation_code"
                readonly value="<?php echo $records1[0]->quotation_code; ?>">
              <input type="hidden" name="quotation_id" id="quotation_id"
                value="<?php echo $records1[0]->quotation_id; ?>">
            </div>
            <div class="col-md-4">
              <label for="revision" class="form-label">Revision</label>
              <input type="text" class="form-control" name="revision" id="revision"
                readonly value="<?php echo $records1[0]->revision; ?>">
            </div>
          </div>

          <!-- Row 2: Branch and Date -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="quotation_date" class="form-label">Date</label>
              <input type="date" class="form-control" name="quotation_date" id="quotation_date"
                value="<?php echo $records1[0]->quotation_date; ?>">
            </div>
            <div class="col-md-6">
              <label for="branch_id" class="form-label">Branch</label>
              <select name="branch_id" id="branch_id" class="form-control select2" required tabindex="1" readonly>
                <option value="">Please select branch</option>
                <?php foreach ($branch_records as $b) { ?>
                  <option value="<?php echo $b->branch_id; ?>"
                    <?= ($records1[0]->branch_id == $b->branch_id) ? "selected" : "" ?>>
                    <?php echo $b->branch_name; ?>
                  </option>
                <?php } ?>
              </select>
            </div>

          </div>

          <!-- Row 3: Supplier and Reference -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="supplier_name" class="form-label">Supplier</label>
              <input type="text" readonly name="supplier_name" id="supplier_name" class="form-control"
                value="<?php echo $records1[0]->supplier_name; ?>">
              <input type="hidden" readonly name="supplier_id" id="supplier_id"
                value="<?php echo $records1[0]->supplier_id; ?>">
            </div>
            <div class="col-md-6">
              <label for="ref_no" class="form-label">Reference</label>
              <input type="text" class="form-control" name="ref_no" id="ref_no"
                value="<?php echo $records1[0]->reference; ?>">
            </div>
          </div>

          <!-- Row 4: Project Name and Doc Upload -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="project" class="form-label">Project Name</label>
              <input type="text" class="form-control" name="project" id="project"
                value="<?php echo $records1[0]->project; ?>">
            </div>
            <div class="col-md-6">
              <label for="quote_doc" class="form-label">Doc Upload</label>
              <input type="file" class="form-control" name="quote_doc" id="quote_doc">

              <?php if (!empty($records1[0]->quote_doc)) { ?>

                <?php
                $file = $records1[0]->quote_doc;
                $url = base_url('public/uploaded_documents/' . $file);
                ?>

                <div style="margin-top:8px;">
                  <a href="<?= $url ?>" target="_blank">
                    View File
                  </a>
                </div>

              <?php } ?>
            </div>

            <input type="hidden" name="existing_quote_doc"
              value="<?php echo $records1[0]->quote_doc; ?>">
            <!-- Row 5: RFQ By -->
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="rfq_by" class="form-label">RFQ By</label>
                <input type="text" class="form-control" name="rfq_by" id="rfq_by"
                  value="<?php echo $records1[0]->rfq_created_by_name; ?>">
              </div>
            </div>

          </div>
        </div>
      </div>



      <div class="row col-md-12 col-sm-12" style="overflow: scroll;">
        <div class="x_content" id="rfq_items_list">
          <table class="table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th>Product Code</th>
                <!-- <th>Brand</th> -->
                <th>Description</th>
                <th style="width:70px;">Qty</th>

                <!-- UNIT SMALL -->
                <th style="width:60px;">Unit</th>

                <th style="width:70px;">Packing</th>

                <th style="width:90px;">Price</th>
                <th style="width:80px;">Dis 1%</th>
                <th style="width:80px;">Dis</th>
                <th style="width:90px;">Unit Price</th>
                <th style="width:90px;">Total</th>

              </tr>
            </thead>
            <tbody>
              <?php
              $i = 5000;
              $up = 0;
              $itot = 0;
              $subtot = 0;
              $ivat = 0;
              foreach ($records2 as $r) { ?>
                <tr>
                  <td>
                    <input type="text" class="form-control" name="item_model[]" value="<?php echo $r->product_name; ?>" />
                    <input type="hidden" class="form-control" name="item_id[]" value="<?php echo $r->product_id; ?>" />
                  </td>
                  <!-- <td><input type="text" class="form-control" name="item_brand[]" value="<?php echo $r->brand_name; ?>" /></td> -->
                  <td><input type="text" class="form-control" name="item_description[]" value="<?php echo $r->description; ?>" /></td>
                  <td><input type="number" class="form-control qty" name="item_quantity[]" id="item_quantity<?php echo $i; ?>" value="<?php echo $r->quantity; ?>" /></td>
                  <td><select class="form-control" name="item_unit[]" value="<?php echo $r->unit_name; ?>" /></td>
                  <td><select class="form-control" name="item_packing[]">
                      <option>CTN</option>
                    </select></td>

                  <td><input type="number" class="form-control unit_price" name="unit_price[]" step='any' id="unit_price<?php echo $i; ?>" value="<?php echo $r->price; ?>" /></td>
                  <td><input type="number" class="form-control dis_per" id="discount_per<?php echo $i; ?>" step='any' name="dis_per[]" value="<?php echo $r->dis_per; ?>" /></td>
                  <td><input type="number" class="form-control dis_amt" id="discount_amt<?php echo $i; ?>" step='any' name="dis_amt[]" value="<?php echo $r->dis_amt; ?>" /></td>
                  <!-- <td><input type="number" class="form-control dis_per2" id="discount_per2<?php echo $i; ?>" step='any' name="dis_per2[]" value="<?php echo $r->dis_per2; ?>"/></td> -->
                  <!-- <td><input type="number" class="form-control dis_amt2" id="discount_amt2<?php echo $i; ?>" step='any' name="dis_amt2[]" value="<?php echo $r->dis_amt2; ?>"/></td> -->
                  <td><input type="number" class="form-control final_unit_price" name="final_unit_price[]" step='any' id="final_unit_price<?php echo $i; ?>" value="<?php echo $r->unit_price; ?>" /></td>

                  <td><input type="number" class="form-control total_price" id="total_price<?php echo $i; ?>" step='any' name="total_price[]" value="<?php echo $r->total; ?>" /></td>

                </tr>
              <?php $i++;
              } ?>
            </tbody>
          </table>


        </div>
      </div>

      <div class="x_content">
        <!-- Row 1: Amounts -->
        <div class="row mb-3">
          <div class="col-md-3">
            <label class="form-label">Taxable Amount</label>
            <input type="text" class="form-control" name="sub_total" id="sub_total"
              value="<?php echo $records1[0]->subtotal; ?>" readonly>
          </div>
          <div class="col-md-2">
            <label class="form-label">VAT(%)</label>
            <input type="text" class="form-control" name="vat_per" id="vat_per"
              value="<?php echo $records1[0]->vat_percent; ?>">
          </div>
          <div class="col-md-3">
            <label class="form-label">Tax Amount</label>
            <input type="text" class="form-control" name="vat_amount" id="vat_amount"
              value="<?php echo $records1[0]->vat_amt; ?>">
          </div>
          <div class="col-md-4">
            <label class="form-label">Grand Total</label>
            <input type="text" class="form-control" name="grand_total" id="grand_total"
              value="<?php echo $records1[0]->grand_total; ?>">
          </div>
        </div>

        <!-- Row 2: Prepared/Approved -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Prepared By</label>
            <input type="text" class="form-control" name="sales_person" id="sales_person"
              value="<?php echo $records1[0]->sales_person; ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Approved By</label>
            <input type="text" class="form-control" name="approved_by" id="approved_by">
          </div>
        </div>



        <!-- Row 3: Validity & Payment -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Validity</label>
            <input type="text" class="form-control" name="validity" id="validity"
              value="<?php echo $records1[0]->validity; ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">Payment Terms</label>
            <input type="text" class="form-control" name="payment_terms" id="payment_terms"
              value="<?php echo $records1[0]->payment_term; ?>">
          </div>
        </div>

        <!-- Row 4: Delivery & General -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Delivery Terms</label>
            <textarea class="form-control" name="delivery_terms" id="delivery_terms">
                          <?php echo $records1[0]->delivery_term; ?>
                      </textarea>
          </div>
          <div class="col-md-6">
            <label class="form-label">General Terms</label>
            <textarea class="form-control" name="general_terms" id="general_terms"><?php echo $records1[0]->general_term; ?></textarea>
          </div>
        </div>
        <!-- Checkbox and Submit Button in same row -->
        <div class="row mb-3 align-items-center">
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="1" id="createRevision" name="create_revision">
              <label class="form-check-label" for="createRevision">Create New Revision</label>
            </div>
          </div>
          <div class="col-md-6 text-end">
            <button type="submit" class="btn btn-success">Update</button>
          </div>
        </div>
      </div>

    </div>

</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
  ['delivery_terms', 'general_terms'].forEach(function(id) {
    var el = document.getElementById(id);
    if (el && el.tagName.toLowerCase() === 'textarea') {
      CKEDITOR.replace(id);
    }
  });

  function get_enquiry_info() {
    var rfq_id = document.getElementById("rfq_id").value;

    if (rfq_id != '') {
      $.ajax({
        async: "false",
        type: "POST",
        url: "<?php echo base_url() ?>index.php/Ajax/ajax_get_rfq_info",
        data: {
          rfq_id: rfq_id
        },
        dataType: "json",
        success: function(msg) {
          document.getElementById("supplier_id").value = msg.supplier_id;
          document.getElementById("supplier_name").value = msg.supplier_code + ' ' + msg.supplier_name;
          get_rfq_items_list(rfq_id);
        }
      });
    } else {
      document.getElementById("enq_code").innerHTML = '';
      document.getElementById("enq_date").value = '';
      document.getElementById("customer_id").value = '';
      document.getElementById("cust_name").value = '';

      document.getElementById('item_list_id').innerHTML = '';
    }
  }

  function get_rfq_items_list(rfq_id) {

    $.ajax({
      type: "POST",
      url: "<?php echo base_url() ?>index.php/Ajax/get_rfq_items_for_quote",
      data: {
        rfq_id: rfq_id
      },
      success: function(msg) {
        document.getElementById('rfq_items_list').innerHTML = msg;
      }
    });

  }


  $(document).ready(function() {
    // Event listener for input changes
    $(document).on('input change', '.qty, .unit_price, .dis_per, .dis_amt, .dis_per2, .dis_amt2', function() {
      var row_id = $(this).closest('tr');

      calculateRow(row_id);
      calculateAll();
    });

    // Function to calculate row total
    function calculateRow(row_id) {
      var qty = parseFloat(row_id.find('.qty').val()) || 0;
      var price = parseFloat(row_id.find('.unit_price').val()) || 0;

      // First discount
      var disPer1 = parseFloat(row_id.find('.dis_per').val()) || 0;
      var disAmt1 = parseFloat(row_id.find('.dis_amt').val()) || 0;

      // Second discount
      var disPer2 = parseFloat(row_id.find('.dis_per2').val()) || 0;
      var disAmt2 = parseFloat(row_id.find('.dis_amt2').val()) || 0;

      var rowTotal = qty * price;

      // Apply first discount
      if (row_id.find('.dis_per').is(':focus')) {
        disAmt1 = (rowTotal * disPer1) / 100;
        row_id.find('.dis_amt').val(disAmt1.toFixed(2));
      } else if (row_id.find('.dis_amt').is(':focus')) {
        disPer1 = (rowTotal === 0) ? 0 : (disAmt1 / rowTotal) * 100;
        row_id.find('.dis_per').val(disPer1.toFixed(2));
      } else {
        disAmt1 = (rowTotal * disPer1) / 100;
        row_id.find('.dis_amt').val(disAmt1.toFixed(2));
      }

      var subtotalAfterFirstDiscount = rowTotal - disAmt1;

      // Apply second discount
      if (row_id.find('.dis_per2').is(':focus')) {
        disAmt2 = (subtotalAfterFirstDiscount * disPer2) / 100;
        row_id.find('.dis_amt2').val(disAmt2.toFixed(2));
      } else if (row_id.find('.dis_amt2').is(':focus')) {
        disPer2 = (subtotalAfterFirstDiscount === 0) ? 0 : (disAmt2 / subtotalAfterFirstDiscount) * 100;
        row_id.find('.dis_per2').val(disPer2.toFixed(2));
      } else {
        disAmt2 = (subtotalAfterFirstDiscount * disPer2) / 100;
        row_id.find('.dis_amt2').val(disAmt2.toFixed(2));
      }

      var finalRowTotal = subtotalAfterFirstDiscount - disAmt2;
      row_id.find('.total_price').val(finalRowTotal.toFixed(2));
    }

    // Function to calculate all rows total
    function calculateAll() {
      var grandTotal = 0;
      $('tbody tr').each(function() {
        var rowTotal = parseFloat($(this).find('.total_price').val()) || 0;
        grandTotal += rowTotal;
      });

      // Apply VAT
      var vatPer = parseFloat($('#vat_per').val()) || 0;
      var vatAmount = (grandTotal * vatPer) / 100;

      // Calculate grand total
      var grandTotalWithVAT = grandTotal + vatAmount;
      $('#grand_total').val(grandTotalWithVAT.toFixed(2));
    }
  });
</script>






</script>