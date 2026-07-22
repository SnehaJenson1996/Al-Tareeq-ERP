   <!-- page content -->
   <form id="main" method="post" action="<?php echo base_url() . 'index.php/'; ?>Purchase/add_direct_rfq_records" autocomplete="off" enctype="multipart/form-data">

     <div class="form-group" role="main">
       <div class="">
         <div class="page-title">
           <?php if ($this->session->flashdata('success')): ?>
             <div class="alert alert-success alert-dismissible fade show" role="alert">
               <strong><i class="fa fa-check-circle"></i></strong>
               <?= $this->session->flashdata('success'); ?>
               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
               </button>
             </div>
           <?php endif; ?>

           <?php if ($this->session->flashdata('error')): ?>
             <div class="alert alert-danger alert-dismissible fade show" role="alert">
               <strong><i class="fa fa-exclamation-circle"></i></strong>
               <?= $this->session->flashdata('error'); ?>
               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
               </button>
             </div>
           <?php endif; ?>

           <div class="clearfix"></div>

           <div class="x_content">


             <div class="well" style="overflow: auto">


               <div class="dt-responsive table-responsive">
                 <table id="datatable" class="table table-striped" data-toggle="data-table">
                   <thead>
                     <tr>
                       <th>Sr.no</th>
                       <th>RFQ Code</th>
                       <th>Date</th>
                       <th>Supplier</th>
                       <th>Action</th>
                     </tr>
                   </thead>

                   <tbody>
                     <?php $i = 1;
                      foreach ($records as $row) : ?>
                       <tr>
                         <td><?php echo $i;
                              $i++; ?></td>
                         <td>

                           <a href="<?php echo base_url() . 'index.php/Purchase/edit_rfq/' . $row->rfq_id . '/' . $row->rev_version . '/1'; ?>" title="edit">

                             <?php echo $row->rfq_code; ?>
                           </a>
                         </td>
                         <td><?php echo date('d-M-Y', strtotime($row->rfq_date)); ?></td>
                         <td>
                           <a title="View customer details" target='blank' href="<?php echo base_url() . 'index.php/Users/edit_supplier/' . $row->supplier_id; ?>">
                             <?php echo $row->supplier_name; ?>
                           </a>
                         </td>

                         <td>
                           <!-- <div class="fa-hover col-md-3 col-sm-4  "><a target='_blank' href="<?php //echo base_url().'index.php/Purchase/print_rfq/'.$row->rfq_id.'/1';
                                                                                                    ?>"><i class="fa fa-print"></i></a>
              </div> -->

                           <a href="<?php echo base_url() . 'index.php/Purchase/edit_rfq/' . $row->rfq_id . '/0'; ?>" title="Edit" style="margin-right:10px;">
                             <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                           </a>
                           <a href='<?php echo base_url() . 'index.php/Purchase/delete_rfq/' . $row->rfq_id; ?>' title="Delete" class="delete" id="delete">
                             <i class="glyphicon glyphicon-trash"></i>
                           </a>
                         </td>
                       </tr>
                     <?php endforeach; ?>
                   </tbody>

                 </table>
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
   <script>
     $(document).ready(function() {
       // Add row
       $(document).on('click', '.addRow', function() {
         const newRow = `<tr>
        <td><input type="text" name="product_name" value=""></td>
        <td><input type="text" name="description" value=""></td>
        <td><input type="number" name="quantity" value=""></td>
        <td><input type="text" name="unit" value=""></td>
        <td><input type="text" name="packing" value=""></td>
        <td>
          <button class="addRow">Add</button>
          <button class="deleteRow">Delete</button>
        </td>
      </tr>`;
         $('#datatable-responsive tbody').append(newRow);
       });

       // Delete row
       $(document).on('click', '.deleteRow', function() {
         $(this).closest('tr').remove();
       });
     });

     function handleKeyPress(event, row) {
       var suggestionDiv = $('#display' + row);
       var selected = suggestionDiv.find('.selected');
       var suggestions = suggestionDiv.find('.suggestion');

       if (event.key === "ArrowUp" || event.key === "ArrowDown" || event.key === "Enter") {

         event.preventDefault();

         if (event.key === "ArrowUp") {
           if (selected.length === 0) {
             suggestions.eq(-1).addClass('selected');
           } else {
             var prev = selected.removeClass('selected').prev('.suggestion');
             if (prev.length > 0) {
               prev.addClass('selected');
             } else {
               suggestions.eq(-1).addClass('selected');
             }
           }
           // console.log("Up arrow key pressed");
         } else if (event.key === "ArrowDown") {

           if (selected.length === 0) {
             suggestions.eq(0).addClass('selected');
           } else {
             var next = selected.removeClass('selected').next('.suggestion');
             if (next.length > 0) {
               next.addClass('selected');
             } else {
               suggestions.eq(0).addClass('selected');
             }
           }
           // console.log("Down arrow key pressed");
         } else if (event.key === "Enter") {

           if (selected.length > 0) {
             var productName = selected.text();
             var productID = selected.data('productId');
             $('#search' + row).val(productName);
             $('#pro_id' + row).val(productID);
             suggestionDiv.hide();
             get_product_info(row);
           }
         }
       }
     }



     function showsugg(row, event) {
       var name = $('#search' + row).val();
       if (name.length > 4) {
         $.ajax({
           type: "POST",
           url: "<?php echo site_url('Product/ajax_product_search'); ?>",
           dataType: 'json',
           data: {
             search_key: name
           },
           success: function(data) {

             document.getElementById('display' + row).innerHTML = '';
             var parentDiv = document.getElementById('display' + row);
             if (data.length > 0) {
               data.forEach(function(product) {
                 var option = document.createElement('div');
                 option.textContent = product.product_name;
                 option.classList.add('suggestion');
                 option.dataset.productId = product.product_id;
                 option.addEventListener('click', function() {
                   document.getElementById('search' + row).value = product.product_name;
                   document.getElementById('pro_id' + row).value = product.product_id;
                   parentDiv.innerHTML = ''; // Clear search results
                   get_product_info(row);

                 });
                 parentDiv.appendChild(option);

               });
             } else {
               var option = document.createElement('div');
               option.textContent = 'No products found';
               option.classList.add('suggestion');
               parentDiv.appendChild(option);

             }
             $('#display' + row).show();
           }
         });

       } else {
         document.getElementById('display' + row).innerHTML = '';
       }

     }

     $(document).ready(function() {
       // Confirmation before delete
       $(document).on('click', '.delete', function(e) {
         e.preventDefault(); // prevent default navigation

         var url = $(this).attr('href'); // get delete URL

         if (confirm("Are you sure you want to delete this RFQ?")) {
           window.location.href = url; // go to delete URL if confirmed
         }
       });
     });
   </script>