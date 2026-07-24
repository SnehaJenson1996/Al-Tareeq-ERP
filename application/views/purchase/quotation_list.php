   <!-- page content -->
   <form id="main" method="post" action="<?php echo base_url() . 'index.php/'; ?>Purchase/add_direct_rfq_records" autocomplete="off" enctype="multipart/form-data">

     <div class="form-group" role="main">
       <div class="">
         <div class="page-title">
         </div>
         <div class="clearfix"></div>

         <div class="x_content">

           <?php if ($this->session->flashdata('success')): ?>
             <div class="alert alert-success alert-dismissible fade in" role="alert">
               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
               </button>
               <strong><i class="fa fa-check-circle"></i></strong>
               <?php echo $this->session->flashdata('success'); ?>
             </div>
           <?php endif; ?>

           <?php if ($this->session->flashdata('error')): ?>
             <div class="alert alert-danger alert-dismissible fade in" role="alert">
               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
               </button>
               <strong><i class="fa fa-exclamation-triangle"></i></strong>
               <?php echo $this->session->flashdata('error'); ?>
             </div>
           <?php endif; ?>
           <div class="well" style="overflow: auto">


             <div class="dt-responsive table-responsive">
               <table id="datatable" class="table table-striped" data-toggle="data-table">
                 <thead>
                   <tr>
                     <th>Sl. No</th>
                     <th>Quotation Code</th>
                     <th>Date</th>
                     <th>Supplier</th>
                     <th>Amount</th>
                     <th>Action</th>
                   </tr>
                 </thead>

                 <tbody>
                   <?php $i = 1;
                    foreach ($records as $row) : ?>
                     <tr>
                       <td>
                         <?php echo $i++; ?>
                       </td>


                       <td>
                         <?php echo $row->quotation_code; ?>

                       </td>
                       <td><?php echo date('d-M-Y', strtotime($row->quotation_date)); ?></td>
                       <td>
                         <a title="View customer details" target='blank' href="<?php echo base_url() . 'index.php/Users/edit_supplier/' . $row->supplier_id; ?>">
                           <?php echo $row->supplier_name; ?>
                         </a>
                       </td>
                       <td align="center"><?php echo $row->grand_total; ?></td>
                       <td>
                        <!-- <a href="" class="badge badge-success" style="margin-right:10px;">Approve</a> -->

                        <a href="<?php echo base_url() . 'index.php/Purchase/edit_quotation/' . $row->quotation_id . '/0'; ?>" title="Edit" style="margin-right:10px;">
                          <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                        </a>

                        <!-- <a target="_blank" href="<?php echo base_url() . 'index.php/Purchase/print_quote/' . $row->quotation_id . '/1'; ?>" style="margin-right:10px;">
                            <i class="fa fa-print"></i>
                        </a> -->

                         <a href="#" onclick="printQuote('<?php echo $row->quotation_id; ?>'); return false;" style="margin-right:10px;">
                           <i class="fa fa-print" style="font-size:18px;"></i>
                         </a>

                        <a href="#" title="Delete" class="delete" data-id="<?php echo $row->quotation_id; ?>" onclick="openPasswordModal(<?php echo $row->quotation_id; ?>); return false;">
                           <i class="glyphicon glyphicon-trash"></i>&nbsp;&nbsp;
                        </a>

                       </td>
                     </tr>
                   <?php endforeach; ?>
                 </tbody>

               </table>
             </div>

           </div>
         </div>
       </div>
     </div>

     <!-- /page content -->
   </form>
   <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog">
     <div class="modal-dialog">
       <form id="deleteForm">
         <div class="modal-content">
           <div class="modal-header">
             <h5 class="modal-title">Enter Password to Delete</h5>
           </div>
           <div class="modal-body">
             <input type="password" class="form-control" id="deletePassword" placeholder="Enter password" required>
             <input type="hidden" id="quotationId" name="quotation_id">
           </div>
           <div class="modal-footer">
             <button type="submit" class="btn btn-danger">Delete</button>
             <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
           </div>
         </div>
       </form>
     </div>
   </div>
   <script>
     function openPasswordModal(quotationId) {
       $('#quotationId').val(quotationId);
       $('#passwordModal').modal('show');
     }

     $('#deleteForm').on('submit', function(e) {
       e.preventDefault();

       const quotationId = $('#quotationId').val();
       const password = $('#deletePassword').val();

       $.ajax({
         url: "<?php echo base_url('index.php/Purchase/delete_quote_protected'); ?>",
         type: "POST",
         data: {
           quotation_id: quotationId,
           password: password
         },
         success: function(response) {
           if (response === 'success') {
             alert('Quotation deleted.');
             location.reload();
           } else {
             alert('Incorrect password.');
           }
         }
       });
     });

     function printQuote(quotation_id) {
       // Open the print page in a new tab
       var url = "<?php echo base_url(); ?>index.php/Purchase/print_quote/" + quotation_id + "/1";
       var printWindow = window.open(url, '_blank');

       // Wait for the new window to load
       printWindow.onload = function() {
         printWindow.focus(); // focus on the new tab
         printWindow.print(); // trigger system print dialog
       };
     }

     $('#datatable').DataTable({
       ordering: false
     });
   </script>