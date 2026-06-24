<form id="main" method="post" action="<?php echo base_url().'index.php/'; ?>Purchase/add_purchase_quotation_records" autocomplete="off" enctype="multipart/form-data">

  <div class="form-group" role="main">
    <div class="x_content">
      <div class="well">

        <!-- Row 1: Quotation Code + RFQ -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="quotation_code" class="form-label">Quotation Code</label>
            <input type="text" class="form-control" name="quotation_code" id="quotation_code" readonly value="<?php echo $Code; ?>">
          </div>
          <div class="col-md-6">
            <label for="rfq_id" class="form-label">Select RFQ/PR</label>
            <select class="form-control" id="rfq_id" name="rfq_id" required onchange="get_enquiry_info()">
    <option value="">Select</option>

    <!-- RFQs -->
    <?php foreach ($records as $s) { ?>
        <option value="RFQ-<?php echo $s->rfq_id; ?>"
            <?php echo (isset($selected_rfq_id) && $selected_rfq_id == $s->rfq_id) ? 'selected' : ''; ?>>
            <?php echo $s->rfq_code; ?> (RFQ)
        </option>
    <?php } ?>

    <!-- Purchase Requests -->
    <?php foreach ($purchase_requests as $pr) { ?>
        <option value="PR-<?php echo $pr->pr_id; ?>"
            <?php echo (isset($selected_pr_id) && $selected_pr_id == $pr->pr_id) ? 'selected' : ''; ?>>
            <?php echo $pr->pr_code; ?> (PR)
        </option>
    <?php } ?>
</select>


          </div>
        </div>

        <!-- Row 2: Date + Branch -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="quotation_date" class="form-label">Date</label>
            <input type="date" class="form-control" name="quotation_date" id="quotation_date" value="<?php echo date('Y-m-d'); ?>">
          </div>
          <div class="col-md-6">
            <label for="branch_name" class="form-label">Branch</label>
            <input type="text" readonly name="branch_name" id="branch_name" class="form-control">
            <input type="hidden" name="branch_id" id="branch_id">
          </div>
        </div>

        <!-- Row 3: Supplier + Reference -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="supplier_name" class="form-label">Supplier</label>
            <input type="text" readonly name="supplier_name" id="supplier_name" class="form-control">
            <input type="hidden" name="supplier_id" id="supplier_id">
          </div>
          <div class="col-md-6">
            <label for="ref_no" class="form-label">Reference</label>
            <input type="text" class="form-control" name="ref_no" id="ref_no">
          </div>
        </div>

        <!-- Row 4: Project Name + Upload Document -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="project" class="form-label">Project Name</label>
            <input type="text" class="form-control" name="project" id="project">
          </div>
          <div class="col-md-6">
            <label for="quote_doc" class="form-label">Upload Document</label>
            <input type="file" class="form-control" name="quote_doc" id="quote_doc">
          </div>
        </div>

        <!-- Row 5: RFQ Created By -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="rfq_by" class="form-label">RFQ/PR Created By</label>
            <input type="text" class="form-control" name="rfq_by" id="rfq_by">
          </div>
        </div>

        <!-- RFQ Items -->
        <div class="row mb-3">
          <div class="col-12" id="rfq_items_list" style="overflow-x: auto;"></div>
        </div>

        <!-- Tax, VAT, Grand Total -->
        <div class="row mb-3">
          <div class="col-md-2">
            <label for="sub_total" class="form-label">Taxable Amount</label>
            <input type="text" class="form-control" name="sub_total" id="sub_total" readonly>
          </div>
          <div class="col-md-2">
            <label for="vat_per" class="form-label">VAT (%)</label>
            <input type="text" class="form-control" name="vat_per" id="vat_per" value="5">
          </div>
          <div class="col-md-2">
            <label for="vat_amount" class="form-label">Tax Amount</label>
            <input type="text" class="form-control" name="vat_amount" id="vat_amount">
          </div>
          <div class="col-md-2">
            <label for="grand_total" class="form-label">Grand Total</label>
            <input type="text" class="form-control" name="grand_total" id="grand_total">
          </div>
        </div>

        <!-- Prepared By + Approved By -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="sales_person" class="form-label">Prepared By</label>
            <input type="text" class="form-control" name="sales_person" id="sales_person" value="<?php echo $this->session->userdata('user_name'); ?>">
          </div>
          <div class="col-md-6">
            <label for="approved_by" class="form-label">Approved By</label>
            <input type="text" class="form-control" name="approved_by" id="approved_by">
          </div>
        </div>

        <!-- Validity + Payment Terms -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="validity" class="form-label">Validity</label>
            <input type="text" class="form-control" name="validity" id="validity">
          </div>
          <div class="col-md-6">
            <label for="payment_terms" class="form-label">Payment Terms</label>
            <input type="text" class="form-control" name="payment_terms" id="payment_terms">
          </div>
        </div>

        <!-- Delivery Terms + General Terms -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="delivery_terms" class="form-label">Delivery Terms</label>
            <textarea class="form-control" name="delivery_terms" id="delivery_terms"></textarea>
          </div>
          <div class="col-md-6">
            <label for="general_terms" class="form-label">General Terms</label>
            <textarea class="form-control" name="general_terms" id="general_terms"></textarea>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="row">
          <div class="col-12">
            <button type="submit" name="submit_action" value="save" class="btn btn-success">Save</button>
            <button type="submit" name="submit_action" value="create_po" class="btn btn-primary">Save &amp; Create PO</button>
          </div>
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

let selected = '<?php echo isset($selected_rfq_id) ? $selected_rfq_id : ''; ?>';

/* ================= INIT ================= */
$(document).ready(function () {

    if (selected) {
        $('#rfq_id').val(selected).trigger('change');
        setTimeout(function () {
            get_enquiry_info();
        }, 200);
    }

    /* live calculation */
    $(document).on('input change', '.qty, .unit_price, .dis_per, .dis_amt, .dis_per2, .dis_amt2', function () {
        let $row = $(this).closest('tr');
        calculateRow($row);
        calculateAll();
    });

    /* VAT change */
    $('#vat_per').on('input change', function () {
        calculateAll();
    });

});

/* ================= RFQ / PR INFO ================= */
function get_enquiry_info() {

    let selected = $('#rfq_id').val();
    if (!selected) return;

    let type = selected.split('-')[0];
    let id   = selected.split('-')[1];

    if (type === "RFQ") {

        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>index.php/Ajax/ajax_get_rfq_info",
            data: { rfq_id: id },
            dataType: "json",
            success: function (msg) {

                $('#branch_id').val(msg.branch_id);
                $('#branch_name').val(msg.branch_name);
                $('#supplier_name').val(msg.supplier_name);
                $('#supplier_id').val(msg.supplier_id);
                $('#rfq_by').val(msg.sales_person_name);
                $('#project').val(msg.project);
                $('#ref_no').val(msg.ref);

                get_rfq_items_list(id);
            }
        });

    } else {

        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>index.php/Ajax/ajax_get_pr_info",
            data: { pr_id: id },
            dataType: "json",
            success: function (msg) {

                $('#branch_id').val(msg.branch_id);
                $('#branch_name').val(msg.branch_name);
                $('#supplier_name').val(msg.supplier_name);
                $('#supplier_id').val(msg.supplier_id);
                $('#rfq_by').val(msg.created_by_name);
                $('#project').val(msg.project);
                $('#ref_no').val(msg.pr_code);

                get_pr_items_list(id);
            }
        });
    }
}

/* ================= LOAD RFQ ITEMS ================= */
function get_rfq_items_list(rfq_id) {

    $.ajax({
        type: "POST",
        url: "<?php echo base_url()?>index.php/Ajax/get_rfq_items_for_quote",
        data: { rfq_id: rfq_id },
        success: function (html) {

            $('#rfq_items_list').html(html);

            recalcAfterLoad();
        }
    });
}

/* ================= LOAD PR ITEMS ================= */
function get_pr_items_list(pr_id) {

    $.ajax({
        type: "POST",
        url: "<?php echo base_url()?>index.php/Ajax/get_pr_items_for_quote",
        data: { pr_id: pr_id },
        success: function (html) {

            $('#rfq_items_list').html(html);

            recalcAfterLoad();
        }
    });
}

/* ================= AFTER AJAX LOAD ================= */
function recalcAfterLoad() {

    setTimeout(function () {

        $('#rfq_items_list tbody tr').each(function () {
            calculateRow($(this));
        });

        calculateAll();

    }, 150);
}

/* ================= ROW CALCULATION ================= */
function calculateRow($row) {

    let qty   = parseFloat($row.find('.qty').val()) || 0;
    let price = parseFloat($row.find('.unit_price').val()) || 0;

    let dis1 = parseFloat($row.find('.dis_per').val()) || 0;
    let amt1 = (qty * price * dis1) / 100;

    let sub1 = (qty * price) - amt1;

    let dis2 = parseFloat($row.find('.dis_per2').val()) || 0;
    let amt2 = (sub1 * dis2) / 100;

    let total = sub1 - amt2;

    $row.find('.dis_amt').val(amt1.toFixed(2));
    $row.find('.dis_amt2').val(amt2.toFixed(2));

    let unit = qty > 0 ? total / qty : 0;

    $row.find('.final_unit_price').val(unit.toFixed(2));
    $row.find('.total_price').val(total.toFixed(2));
}

/* ================= GRAND TOTAL ================= */
function calculateAll() {

    let total = 0;

    $('#rfq_items_list tbody tr').each(function () {
        total += parseFloat($(this).find('.total_price').val()) || 0;
    });

    $('#sub_total').val(total.toFixed(2));

    let vat = parseFloat($('#vat_per').val()) || 0;
    let vatAmt = (total * vat) / 100;

    $('#vat_amount').val(vatAmt.toFixed(2));
    $('#grand_total').val((total + vatAmt).toFixed(2));
}

/* ================= FORM VALIDATION ================= */
$('#main').on('submit', function (e) {

    let valid = true;
    let msg = '';

    if (!$('#rfq_id').val()) {
        valid = false;
        msg += "Select RFQ/PR\n";
    }

    if (!$('#quotation_date').val()) {
        valid = false;
        msg += "Quotation Date required\n";
    }

    if (!valid) {
        e.preventDefault();
        alert(msg);
        return false;
    }
});

</script>