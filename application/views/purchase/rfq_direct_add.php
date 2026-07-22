   <!-- page content -->
   <form id="main" method="post" action="<?php echo base_url() . 'index.php/'; ?>Purchase/add_direct_rfq_records" autocomplete="off" enctype="multipart/form-data">

     <div class="form-group" role="main">
       <div class="">
         <div class="page-title"></div>


         <div class="clearfix"></div>

         <div class="x_content">


           <div class="well" style="overflow: auto">
             <div class="col-md-6">
               <label class="control-label col-md-3 col-sm-3 col-xs-3">RFQ Code</label>
               <div class="col-sm-9 col-xs-9">
                 <input type="text" class="form-control" name="rfq_code" id="rfq_code" readonly value="<?php echo $Code; ?>">

               </div>
             </div>
             <div class="col-md-6">
               <label class="control-label col-md-3 col-sm-3 col-xs-3">RFQ Date</label>
               <div class="col-md-9 col-sm-9 col-xs-9">
                 <input type="date" class="form-control" data-inputmask="'mask' : '99/99/9999'" tabindex="1" name="rfq_date" id="rfq_date" value="<?php echo date('Y-m-d'); ?>">
               </div>
             </div>
             <br /> <br /> <br />
             <div class="col-md-6">
               <label class="control-label col-md-3 col-sm-3 col-xs-3 ">Select Branch</label>
               <div class="col-md-9 col-sm-9 col-xs-9">
                 <select name="branch_id" id="branch_id" class="form-control select2" required tabindex="1">
                   <option value="">Please select branch</option>
                   <?php foreach ($branch_records as $b) { ?>
                     <option value="<?php echo $b->branch_id; ?>"><?php echo $b->branch_name; ?></option>
                   <?php } ?>
                 </select>
               </div>
             </div>
             <br /><br /><br />

             <div class="col-md-6">
               <label class="control-label col-md-3 col-sm-3 col-xs-3">Select Supplier</label>
               <div class="col-md-9 col-sm-9 col-xs-9">
                 <div id="supplier_dropdown_wrapper">
                   <select name="supplier_id" id="supplier_id" class="form-control select2" required tabindex="2">
                     <option value="">Please select name</option>
                     <?php foreach ($supplier_records as $g) { ?>
                       <option value="<?php echo $g->supplier_id; ?>"><?php echo $g->supplier_code . ' ' . $g->supplier_name; ?></option>
                     <?php } ?>
                   </select>
                 </div>
                 <small><a href="#" id="add_supplier_link">+ Add New Supplier</a></small>
               </div>
             </div>

             <div class="col-md-6">
               <label class="control-label col-md-3 col-sm-3 col-xs-3">Subject</label>
               <div class="col-md-9 col-sm-9 col-xs-9">
                 <input type="text" class="form-control" name="subject" id="subject">
               </div>

             </div>
             <br /> <br /> <br />
             <div class="col-md-6">
               <label class="control-label col-md-3 col-sm-3 col-xs-3">Project Name</label>
               <div class="col-md-9 col-sm-6 col-xs-6">
                 <input type="text" class="form-control" name="project" id="project">
               </div>
             </div>
             <div class="col-md-6">
               <label class="control-label col-md-3 col-sm-3 col-xs-3">Reference</label>
               <div class="col-md-9 col-sm-6 col-xs-6">
                 <input type="text" class="form-control" name="ref" id="ref">
               </div>
             </div>

           </div>
         </div>


         <div class="row col-md-12 col-sm-12" style="overflow: scroll;">
           <!-- form color picker -->
           <div class="x_content">
             <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
               <thead>
                 <tr>
                   <th>Product Code</th>
                   <th>Brand</th>
                   <th>Description</th>
                   <th>Unit</th>
                   <th>Quantity</th>
                   <th>Actions</th>
                 </tr>
               </thead>
               <tbody>
                 <tr>
                   <td>
                     <select class="form-control select2" name="item[]" id='item0' onchange='get_item_by_id(0)'>
                       <option value=''>Select</option>
                       <option value='new'>+ Add New Product</option>
                       <?php foreach ($active_items as $item) { ?>
                         <option value='<?php echo $item->product_id ?>'><?php echo $item->product_name; ?></option>
                       <?php } ?>
                     </select>
                   </td>
                   <td><input class="form-control" type="text" name="brand[]" id="brand0"></td>
                   <td><input class="form-control" type="text" name="description[]" id="description0"></td>
                   <td>
                     <select class="form-control" name="unit[]" id='unit0'>
                       <option value=''>Select</option>
                       <?php foreach ($active_units as $unit) { ?>
                         <option value='<?php echo $unit->unit_id ?>'><?php echo $unit->unit_name; ?></option>
                       <?php } ?>
                     </select>
                   </td>
                   <td><input class="form-control" type="number" name="quantity[]" id="quantity0"></td>
                   <td>
                     <button class="addRow"><i class="fa fa-plus"></i></button>
                     <button class="deleteRow"><i class="fa fa-search-minus"></i></button>
                   </td>
                 </tr>
               </tbody>
             </table>

           </div>
         </div>

         <br><br><br><br><br><br><br><br><br>
         <div class="x_content well">


           <div class="row col-md-12 col-sm-12">

             <label class="control-label col-md-2 col-sm-3 col-xs-3">Remarks</label>
             <div class="col-md-3 col-sm-9 col-xs-9">
               <textarea class="form-control" name="remarks" id="remarks">  </textarea>
             </div>

             <!-- inside your RFQ form, where submit button currently is -->
             <div class="col-md-12">
               <button type="submit" name="submit_action" value="save" class="btn btn-success">Save</button>

               <!-- New: save and open quotation form -->
               <button type="submit" name="submit_action" value="create_quote" class="btn btn-primary">
                 Save &amp; Create Supplier Quote
               </button>
             </div>

           </div>
         </div>



         <!--  -->
       </div>
     </div>

     </div>
     </div>

     <!-- /page content -->
   </form>

   <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg" role="document">
       <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title" id="addItemModalLabel">Add New Product</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body" id="addItemModalContent">
           <!-- Item form will be loaded here via AJAX -->
         </div>
       </div>
     </div>
   </div>

   <div id="newSupplierModal" class="modal fade mymodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">
       <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title">Supplier Details</h5>
           <button type="button" class="close" data-dismiss="modal">
              <span>&times;</span>
          </button>
         </div>
         <div id="supplier-success-alert" class="alert alert-success m-3" style="display: none;">
           Supplier saved successfully!
         </div>
         <div class="modal-body" id="modal-body-content">
           Loading...
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
         </div>
       </div>
     </div>
   </div>

   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


   <script>
     function initializeSelect2(selectElement) {
       selectElement.select2({

       });
     }

     $(document).ready(function() {
       initializeSelect2($('.item-select2'));
     });

     $(document).ready(function() {
       let rowIndex = 1; // Start from 1 since 0 is already present

       // Add row
       $(document).on('click', '.addRow', function(e) {
         e.preventDefault();
         const newRow = `
            <tr>
                <td>
                    <select class="form-control" name="item[]" id="item${rowIndex}" onchange="get_item_by_id(${rowIndex})">
                        <option value="">Select</option>
                        <option value="new">+ Add New Product</option>
                        <?php foreach ($active_items as $item) { ?>
                            <option value="<?php echo $item->product_id ?>"><?php echo $item->product_name; ?></option>
                        <?php } ?>
                    </select>
                </td>
                 <td><input class="form-control" type="text" name="brand[]" id="brand${rowIndex}"></td>
                <td><input class="form-control" type="text" name="description[]" id="description${rowIndex}"></td>
                <td>
                 <select class="form-control select2" name="unit[]" id='unit${rowIndex}'>
                        <option value=''>Select</option><?php foreach ($active_units as $unit) { ?><option value='<?php echo $unit->unit_id ?>'><?php echo $unit->unit_name; ?></option><?php } ?>
                        </select>
                </td>
                <td><input class="form-control" type="number" name="quantity[]" id="quantity${rowIndex}"></td>
                <td>
                    <button class="addRow"><i class="fa fa-plus"></i></button>
                    <button class="deleteRow"><i class="fa fa-search-minus"></i></button>                        
                </td>
            </tr>`;

         $('#datatable-responsive tbody').append(newRow);
         //$(`#item${rowIndex}`).select2(); // Reinitialize select2 for the new element
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

             $('#brand' + row_no).val(response.brand_name);
             $('#description' + row_no).val(response.item_description);
             $('#unit' + row_no).val(response.item_unit).change();
             $('#actual_price' + row_no).val(response.mrp_aed);
             $('#unit' + row_no).prop('required', true);
             $('#quantity' + row_no).prop('required', true);
             $('#actual_price' + row_no).prop('required', true);
             $('#quantity' + row_no).prop('required', true);
             var nextRow = document.getElementById('addr' + row_no).nextElementSibling;

             if (!nextRow)
               add_row();

           }
         });
       } else {
         $('#brand' + row_no).text('');
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
     $(document).on('change', 'select[name="item[]"]', function() {
       if ($(this).val() === 'new') {
         var currentSelect = $(this); // Save the select element to update later

         // Load the form via AJAX
         $.ajax({
           url: '<?= base_url("index.php/Item/add_item_form") ?>', // controller method that returns the item form HTML
           type: 'GET',
           success: function(html) {
             $('#addItemModalContent').html(html);
             $('#addItemModal').modal('show');

             // After form submission inside modal, reload dropdown
             $('#addItemForm').on('submit', function(e) {
               e.preventDefault();
               var formData = new FormData(this);

               $.ajax({
                 url: $(this).attr('action'),
                 type: 'POST',
                 data: formData,
                 processData: false,
                 contentType: false,
                 dataType: 'json',
                 success: function(response) {
                   if (response.status === 'success') {
                     // Close modal
                     $('#addItemModal').modal('hide');

                     // Add new item to the select
                     currentSelect.append(
                       $('<option>', {
                         value: response.product_id,
                         text: response.product_name
                       })
                     );

                     // Select the new item
                     currentSelect.val(response.product_id).trigger('change');
                   } else {
                     alert(response.message); // show errors if any
                   }
                 }
               });
             });
           }
         });

         // Reset dropdown back to default until new item is saved
         $(this).val('').trigger('change');
       }
     });

     $(document).ready(function() {
       $('#add_supplier_link').on('click', function(e) {
         e.preventDefault();

         $('#modal-body-content').html('Loading...');
         $('#newSupplierModal').modal('show');

         $.ajax({
           url: '<?= base_url('index.php/Ajax/add_new_supplier') ?>',
           type: 'POST',
           success: function(response) {
             $('#modal-body-content').html(response);
           },
           error: function(xhr, status, error) {
             console.error('AJAX Error:', status, error, xhr.responseText);
             alert('Failed to load supplier form. Error: ' + error);
           }
         });
       });
     });

     $(document).ready(function() {
       // Form submission validation
       $('#main').on('submit', function(e) {
         let valid = true;
         let errorMsg = '';

         // Validate RFQ Date
         if ($('#rfq_date').val() === '') {
           valid = false;
           errorMsg += 'RFQ Date is required.\n';
           $('#rfq_date').focus();
         }

         // Validate Branch
         if ($('#branch_id').val() === '') {
           valid = false;
           errorMsg += 'Please select a branch.\n';
           $('#branch_id').focus();
         }

         // Validate Supplier
         if ($('#supplier_id').val() === '') {
           valid = false;
           errorMsg += 'Please select a supplier.\n';
           $('#supplier_id').focus();
         }

         // Validate table rows
         $('#datatable-responsive tbody tr').each(function(index, row) {
           const item = $(row).find('select[name="item[]"]').val();
           const unit = $(row).find('select[name="unit[]"]').val();
           const qty = $(row).find('input[name="quantity[]"]').val();

           if (item === '' || item === null) {
             valid = false;
             errorMsg += `Row ${index+1}: Please select an item.\n`;
           }
           if (unit === '' || unit === null) {
             valid = false;
             errorMsg += `Row ${index+1}: Please select unit.\n`;
           }
           if (qty === '' || qty <= 0) {
             valid = false;
             errorMsg += `Row ${index+1}: Quantity must be greater than 0.\n`;
           }
         });

         if (!valid) {
           e.preventDefault(); // Stop form submission
           alert(errorMsg);
           return false;
         }
       });

       // Optional: Validate quantity input on keyup
       $(document).on('input', 'input[name="quantity[]"]', function() {
         const val = $(this).val();
         if (val <= 0) {
           $(this).css('border', '1px solid red');
         } else {
           $(this).css('border', '');
         }
       });
     });
   </script>