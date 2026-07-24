   <!-- page content -->
   <form id="main" method="post" action="<?php echo base_url() . 'index.php/'; ?>Stock/add_min_stock_records" autocomplete="off" enctype="multipart/form-data">

     <!-- page content -->
     <div class="form-group" role="main">
       <div class="">
         <div class="page-title"></div>
         <div class="clearfix"></div>
         <div class="x_content">
           <div class="well" style="overflow: auto">
             <div class="col-md-12">


               <label class="control-label col-md-2 col-sm-3 col-xs-3">Select Item</label>
               <div class="col-md-2 col-sm-9 col-xs-9">
                 <select class="form-control " name="item" id='item'>
                   <option value=''>Select</option>
                   <?php foreach ($active_items as $item) { ?>
                     <option value='<?php echo $item->product_id ?>'><?php echo $item->product_name; ?></option>
                   <?php } ?>
                 </select>
               </div>


               <br /><br /><br />



               <label class="control-label col-md-2 col-sm-3 col-xs-3">Minimum Stock Qty</label>
               <div class="col-md-2 col-sm-9 col-xs-9">
                 <input type="text" name="min_stock_qty" id="min_stock_qty" class="form-control form-control-sm">
               </div>

             </div>
             <div class="col-md-12">
               <button type="submit" class="btn btn-success">Submit</button>
             </div>
           </div>
         </div>
       </div>

     </div>
     </div>



     <!-- /page content -->
   </form>
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   <script>
     $(document).ready(function() {

       // Disable HTML5 default validation to use custom JS validation
       $("#main").attr("novalidate", true);

       $("#main").on("submit", function(e) {
         let isValid = true;
         let errorMsg = "";

         const item = $("#item").val().trim();
         const minStockQty = $("#min_stock_qty").val().trim();

         // --- Item Validation ---
         if (item === "") {
           errorMsg += "• Please select an item.\n";
           isValid = false;
         }

         // --- Minimum Stock Quantity Validation ---
         if (minStockQty === "") {
           errorMsg += "• Please enter the minimum stock quantity.\n";
           isValid = false;
         } else if (isNaN(minStockQty) || parseFloat(minStockQty) <= 0) {
           errorMsg += "• Minimum stock quantity must be a valid positive number.\n";
           isValid = false;
         }

         // --- Stop submission if invalid ---
         if (!isValid) {
           e.preventDefault();
           alert("Please correct the following errors:\n\n" + errorMsg);
         }
       });

       // Optional: prevent negative or non-numeric input
       $("#min_stock_qty").on("input", function() {
         this.value = this.value.replace(/[^0-9.]/g, ""); // allow only numbers and dot
       });

     });
   </script>