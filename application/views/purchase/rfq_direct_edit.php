<!-- page content -->
<form id="main" method="post" action="<?php echo base_url() . 'index.php/'; ?>Purchase/update_rfq"
  autocomplete="off" enctype="multipart/form-data">

  <div class="form-group" role="main">
    <div class="">
      <div class="page-title"></div>
      <div class="clearfix"></div>
      <div class="x_content">
        <div class="well">

          <!-- Row 1: RFQ Code & RFQ Date -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="control-label col-md-3 col-sm-3 col-xs-3">RFQ Code</label>
              <div class="col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control" name="rfq_code" id="rfq_code" readonly
                  value="<?php echo $records1[0]->rfq_code; ?>">
                <input type="hidden" name="rfq_id" id="rfq_id"
                  value="<?php echo $records1[0]->rfq_id; ?>">
              </div>
            </div>

            <div class="col-md-6">
              <label class="control-label col-md-3 col-sm-3 col-xs-3">RFQ Date</label>
              <div class="col-md-9 col-sm-9 col-xs-9">
                <input type="date" class="form-control" name="rfq_date" id="rfq_date"
                  value="<?php echo $records1[0]->rfq_date; ?>">
              </div>
            </div>
          </div>

          <!-- Row 2: Branch -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="control-label col-md-3 col-sm-3 col-xs-3">Branch</label>
              <div class="col-md-9 col-sm-9 col-xs-9">
                <select name="branch_id" id="branch_id" class="form-control" required tabindex="1">
                  <option value="">Please select branch</option>
                  <?php foreach ($branch_records as $b) { ?>
                    <option value="<?php echo $b->branch_id; ?>" <?php if ($b->branch_id == $records1[0]->branch_id) echo 'selected'; ?>>
                      <?php echo $b->branch_name; ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>

          <!-- Row 3: Supplier & Subject -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="control-label col-md-3 col-sm-3 col-xs-3">Supplier</label>
              <div class="col-md-9 col-sm-9 col-xs-9">
                <select name="supplier_id" id="supplier_id" class="form-control" required>
                  <?php foreach ($supplier_records as $g) { ?>
                    <option <?php if ($g->supplier_id == $records1[0]->supplier_id) echo 'selected'; ?>
                      value="<?php echo $g->supplier_id; ?>">
                      <?php echo $g->supplier_code . ' ' . $g->supplier_name; ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <label class="control-label col-md-3 col-sm-3 col-xs-3">Subject</label>
              <div class="col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control" name="subject" id="subject"
                  value="<?php echo $records1[0]->subject; ?>">
              </div>
            </div>
          </div>

          <!-- Row 4: Project Name & Reference -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="control-label col-md-3 col-sm-3 col-xs-3">Project Name</label>
              <div class="col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control" name="project" id="project"
                  value="<?php echo $records1[0]->project; ?>">
              </div>
            </div>

            <div class="col-md-6">
              <label class="control-label col-md-3 col-sm-3 col-xs-3">Reference</label>
              <div class="col-md-9 col-sm-9 col-xs-9">
                <input type="text" class="form-control" name="ref" id="ref"
                  value="<?php echo $records1[0]->ref; ?>">
              </div>
            </div>
          </div>

        </div>
      </div>


      <!-- Product Table -->
      <div class="row col-md-12 col-sm-12" style="overflow-x: auto;">
        <div class="x_content">
          <table class="table table-striped table-bordered dt-responsive nowrap" id="datatable-responsive" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th>Product Code</th>
                <th>Description</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 5000;
              foreach ($records2 as $r) { ?>
                <tr>
                  <td>
                    <select class="form-control" name="item[]" id="item<?php echo $i; ?>"
                      onchange="get_item_by_id(<?php echo $i; ?>)">
                      <?php foreach ($active_items as $item) { ?>
                        <option value="<?php echo $item->product_id; ?>"
                          <?php if ($r->product_id == $item->product_id) echo 'selected'; ?>>
                          <?php echo $item->product_name; ?>
                        </option>
                      <?php } ?>
                    </select>
                  </td>

                  <td><input class="form-control" type="text" name="description[]" id="description<?php echo $i; ?>"
                      value="<?php echo $r->item_description; ?>"></td>

                  <td>
                    <select class="form-control" name="unit[]" id="unit<?php echo $i; ?>">
                      <option value="">Select</option>
                      <?php foreach ($active_units as $unit) { ?>
                        <option <?php if ($r->unit == $unit->unit_id) echo 'selected'; ?>
                          value="<?php echo $unit->unit_id; ?>">
                          <?php echo $unit->unit_name; ?>
                        </option>
                      <?php } ?>
                    </select>
                  </td>

                  <td><input class="form-control" type="number" name="quantity[]" id="quantity<?php echo $i; ?>"
                      value="<?php echo $r->quantity; ?>"></td>

                  <td>
                    <button type="button" class="btn btn-success addRow"><i class="fa fa-plus"></i></button>
                    <button type="button" class="btn btn-danger deleteRow"><i class="fa fa-minus"></i></button>
                  </td>
                </tr>
              <?php $i++;
              } ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Remarks & Buttons -->
      <div class="x_content well mt-4">
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="control-label col-md-3 col-sm-3 col-xs-3">Remarks</label>
            <div class="col-md-9 col-sm-9 col-xs-9">
              <textarea class="form-control" name="remarks" id="remarks"
                rows="3"><?php echo $records1[0]->remark ?? ''; ?></textarea>
            </div>
          </div>
        </div>

        <div class="row mt-4">
          <div class="col-md-12 text-end">
            <button type="button" class="btn btn-secondary" onclick="window.history.back();">Cancel</button>
            <button type="submit" class="btn btn-success">Submit</button>
          </div>
        </div>
      </div>

    </div>
  </div>

</form>
<!-- /page content -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
  function initializeSelect2(selectElement) {
    selectElement.select2({

    });
  }

  $(document).ready(function() {
    initializeSelect2($('.select2'));
  });

  $(document).ready(function() {
    let rowIndex = 1; // Start from 1 since 0 is already present

    // Add row
    $(document).on('click', '.addRow', function(e) {
      e.preventDefault();
      const newRow = `
            <tr>
                <td>
                    <select class="form-control select2" name="item[]" id="item${rowIndex}" onchange="get_item_by_id(${rowIndex})">
                        <option value="">Select</option>
                        <?php foreach ($active_items as $item) { ?>
                            <option value="<?php echo $item->product_id ?>"><?php echo $item->product_name; ?></option>
                        <?php } ?>
                    </select>
                </td>

                <td><input class="form-control" type="text" name="description[]" id="description${rowIndex}"></td>
                <td>
                 <select class="form-control select2" name="unit[]" id='unit${rowIndex}'>
                        <option value=''>Select</option><?php foreach ($active_units as $unit) { ?><option value='<?php echo $unit->unit_id ?>'><?php echo $unit->unit_name; ?></option><?php } ?>
                        </select>
                </td>
                <td><input class="form-control" type="number" name="quantity[]" id="quantity${rowIndex}"></td>
                <td>
                    <button class="btn btn-success addRow"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-danger deleteRow"><i class="fa fa-minus"></i></button>                        
                </td>
            </tr>`;

      $('#datatable-responsive tbody').append(newRow);
      $(`#item${rowIndex}`).select2(); // Reinitialize select2 for the new element
      rowIndex++;
    });

    // Delete row
    $(document).on('click', '.deleteRow', function(e) {
      e.preventDefault();
      $(this).closest('tr').remove();
    });
  });


  function get_item_by_id(row_no) {
    var product_id = $('#item' + row_no).val();

    if (product_id != '') {
      $.ajax({
        url: '<?= base_url("index.php/Item/get_item_by_id") ?>', // update with your controller path
        type: 'POST',
        data: {
          product_id: product_id
        },
        dataType: "json",
        success: function(response) {
          $('#description' + row_no).val(response.item_description);
          $('#unit' + row_no).val(response.item_unit).change();
          // $('#actual_price'+row_no).val(response.mrp_aed);
          $('#unit' + row_no).prop('required', true);
          $('#quantity' + row_no).prop('required', true);

          var nextRow = document.getElementById('addr' + row_no).nextElementSibling;

          if (!nextRow)
            add_row();

        }
      });
    } else {
      $('#description' + row_no).text('');
      $('#unit' + row_no).val('').change();
      $('#actual_price' + row_no).val('');
      $('#unit' + row_no).prop('required', false);
      $('#quantity' + row_no).prop('required', false);
      $('#actual_price' + row_no).prop('required', false);
      $('#quantity' + row_no).prop('required', false);
    }
  }
  $('#branch_id').change(function() {
    var branch_id = $(this).val();

    if (branch_id) {
      $.ajax({
        url: '<?= base_url("index.php/Company/get_supplier_by_branch") ?>',
        type: 'POST',
        data: {
          branch_id: branch_id
        },
        dataType: 'json',
        success: function(data) {
          $('#supplier_id').empty().append('<option value="">-- Select Supplier --</option>');
          $.each(data, function(index, supplier) {
            $('#supplier_id').append(
              '<option value="' + supplier.supplier_id + '" ' +
              'data-tr="' + supplier.trn_no + '">' +
              supplier.supplier_name + ' (' + supplier.supplier_code + ') => ' + supplier.contact_number +
              '</option>'
            );
          });
          $('#supplier_id').trigger('change'); // Refresh select2 if used
        }
      });
    } else {
      $('#supplier_id').empty().append('<option value="">-- Select Customer --</option>');
    }
  });
</script>