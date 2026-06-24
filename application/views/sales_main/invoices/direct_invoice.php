<!-- direct_invoice_form.php -->

<div class="clearfix"></div>

<div class="x_panel">
  <div class="x_content">

    <form class="form-horizontal" id="direct_invoice_form" method="post" action="<?= base_url('index.php/Sales/save_direct_invoice') ?>">

      <!-- Invoice Details -->
      <div class="container p-3 border rounded" style="background:#f9f9f9;">
        <div class="row mb-3">
          <div class="col-md-6">
            <label>Invoice Code</label>
            <input type="text" class="form-control" name="invoice_code" value="<?= $invoice_code ?? '' ?>" readonly>
          </div>
          <div class="col-md-6">
            <label>Invoice Date</label>
            <input type="date" class="form-control" name="invoice_date" value="<?= date('Y-m-d') ?>">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <label>Branch</label>
            <select name="branch_id" id="branch_id" class="form-control" required>
              <option value="">--Select Branch--</option>
              <?php foreach($branch_list as $branch): ?>
                <option value="<?= $branch->branch_id ?>"><?= $branch->branch_name ?></option>
              <?php endforeach; ?>
            </select>
          </div>
         </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label>Customer</label>
            <select name="customer_id" id="customer_id" class="form-control" required>
              <option value="">--Select Customer--</option>
              
            </select>
          </div>
          <div class="col-md-6">
            <label>Customer TRN</label>
            <input type="text" class="form-control" id="customer_trn" name="customer_trn">
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label>Delivery Mode</label>
            <select class="form-control" id="delivery_mode" name="delivery_mode">
                        <option value="">--Select--</option>
                        <option value="Road">Road</option>
                        <option value="Air">Air</option>
                        <option value="Sea">Sea</option>
                        <option value="Courier">Courier</option>
                        <option value="Rail">Rail</option>
                        <option value="Other">Other</option>
                    </select>
          </div>
          <div class="col-md-6">
            <label>Delivered By</label>
            <input type="text" class="form-control" name="deliverd_by" id="deliverd_by">
          </div>
        </div>

      </div>

      <!-- Product Table -->
      <div class="row mb-3">
        <div class="table-responsive">
          <table class="table table-striped table-bordered" id="direct_invoice_table">
            <thead>
              <tr>
                <th>Product</th>
                <th>Description</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Dicount</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <select name="product_id[]" class="form-control product_select" required>
                    <option value="">--Select Product--</option>
                    <?php foreach($products as $prod): ?>
                    <option 
                      value="<?= $prod->item_id ?>" 
                      data-unit="<?= $prod->unit_name ?>" 
                      data-price="<?= $prod->unit_price ?>"
                      data-item_description="<?= $prod->item_description ?>"
                      >
                      <?= $prod->item_name ?> (<?= $prod->item_code ?>)
                    </option>
                  <?php endforeach; ?>
                  </select>
                </td>
                <td><input type="text" class="form-control product_desc" name="product_desc[]" readonly></td>
                <td>
                  <select name="unit[]" class="form-control unit_select" readonly>
                    <option value="">--Select Unit--</option>
                  </select>
                </td>
                <td><input type="number" class="form-control qty" name="quantity[]" value="1" min="1"></td>
                <td><input type="text" class="form-control unit_price" name="unit_price[]" readonly></td>
                <td><input type="text" class="form-control discount" name="discount[]" ></td>
                <td><input type="text" class="form-control total" name="total[]" readonly></td>
              </tr>
            </tbody>
          </table>
          <button type="button" class="btn btn-secondary" id="add_row">Add Product</button>
        </div>
      </div>

      <!-- Totals -->
      <div class="row mb-3">
        <div class="col-md-2">
          <label>Sub Total</label>
          <input type="text" class="form-control" name="sub_total" readonly>
        </div>
        <div class="col-md-2">
          <label>Discount %</label>
          <input type="text" class="form-control add_discount" name="add_discount_per" value="0">
        </div>
        <div class="col-md-2">
          <label>Discount Amt</label>
          <input type="text" class="form-control add_discount_amount" name="add_discount_amt" readonly>
        </div>
        <div class="col-md-2">
          <label>VAT %</label>
          <input type="text" class="form-control" name="vat_per" value="5">
        </div>
        <div class="col-md-2">
          <label>VAT Amt</label>
          <input type="text" class="form-control" name="vat_amount" readonly>
        </div>
        <div class="col-md-2">
          <label>Grand Total</label>
          <input type="text" class="form-control" name="grand_total" readonly>
        </div>
      </div>

      <!-- Remarks -->
      <div class="row mb-3">
        <div class="col-md-12">
          <label>Remarks</label>
          <textarea class="form-control" name="remarks"></textarea>
        </div>
      </div>

      <input type="hidden" name="direct_invoice" value="1">

      <!-- Submit -->
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
// Function to calculate row total
function calculateRowTotal(row) {
    let qty = parseFloat(row.find('.qty').val()) || 0;
    let price = parseFloat(row.find('.unit_price').val()) || 0;
    let discount = parseFloat(row.find('.discount').val()) || 0;

    // Total for this row after discount
    let total = (qty * price) - discount;
    row.find('.total').val(total.toFixed(2));

    // Recalculate invoice totals
    calculateInvoiceTotals();
}

// Function to calculate invoice totals
function calculateInvoiceTotals() {
    let subTotal = 0;

    $('#direct_invoice_table tbody tr').each(function() {
        let rowTotal = parseFloat($(this).find('.total').val()) || 0;
        subTotal += rowTotal;
    });

    $('input[name="sub_total"]').val(subTotal.toFixed(2));

    // Invoice-level discount %
    let discount_per = parseFloat($('input[name="add_discount_per"]').val()) || 0;
    let discount_amt = subTotal * discount_per / 100;
    $('input[name="add_discount_amt"]').val(discount_amt.toFixed(2));

    // VAT
    let vat_per = parseFloat($('input[name="vat_per"]').val()) || 0;
    let vat_amt = (subTotal - discount_amt) * vat_per / 100;
    $('input[name="vat_amount"]').val(vat_amt.toFixed(2));

    // Grand total
    let grand_total = subTotal - discount_amt + vat_amt;
    $('input[name="grand_total"]').val(grand_total.toFixed(2));
}

// Recalculate row total when quantity or row discount changes
$(document).on('input', '.qty, .discount', function() {
    let row = $(this).closest('tr');
    calculateRowTotal(row);
});

// Update product selection (description, unit, price)
$(document).on('change', '.product_select', function() {
    let row = $(this).closest('tr');
    let selected = $(this).find(':selected');
    let item_description = selected.data('item_description');

    // Update description
    row.find('.product_desc').val(item_description);

    // Update unit
    let unitVal = selected.data('unit');
    let unitSelect = row.find('.unit_select');
    unitSelect.empty().append(`<option value="${unitVal}" selected>${unitVal}</option>`);

    // Update unit price
    let price = parseFloat(selected.data('price')) || 0;
    row.find('.unit_price').val(price.toFixed(2));

    // Recalculate row total
    calculateRowTotal(row);
});

// Add new row
$('#add_row').click(function() {
    let newRow = $('#direct_invoice_table tbody tr:first').clone();
    newRow.find('input').val('');
    newRow.find('select').val('');
    newRow.find('.total').val('0.00');
    $('#direct_invoice_table tbody').append(newRow);
});

// 🔹 Recalculate totals when additional discount % changes
$(document).on('input blur', 'input[name="add_discount_per"], input[name="vat_per"]', function() {
    calculateInvoiceTotals();
});
 $('#branch_id').change(function() {
        var branch_id = $(this).val();

        if(branch_id) {
            $.ajax({
                url: '<?= base_url("index.php/Company/get_customers_by_branch") ?>',
                type: 'POST',
                data: {branch_id: branch_id},
                dataType: 'json',
               success: function(data) {
                      $('#customer_id').empty().append('<option value="">-- Select Customer --</option>');
                      $.each(data, function(index, customer) {
                          $('#customer_id').append(
                              '<option value="' + customer.customer_id + '" ' +
                              'data-tr="' + customer.customer_TR_no + '">' + 
                              customer.customer_name + ' (' + customer.customer_code + ') => ' + customer.contact_number +
                              '</option>'
                          );
                      });
                      $('#customer_id').trigger('change'); // Refresh select2 if used
                  }
            });
        } else {
            $('#customer_id').empty().append('<option value="">-- Select Customer --</option>');
        }
    });

$('#customer_id').change(function() {
    var trn = $(this).find(':selected').data('tr');  // get TR attribute
    if(trn){
        $('#customer_trn').val(trn);  // fill into input field (example)
    }
});
</script>

