<div class="clearfix"></div>
<div id="print_del"></div>
<form action="<?= base_url()?>index.php/Sales/create_delivery_challan" method="post">
    <input type="hidden" name="quotation_id" value="<?= isset($qtn_master['quotation_id'])?$qtn_master['quotation_id']:""?>">
    <input type="hidden" name="enquiry_id" value="<?= $enquiry_data['enquiry_id'] ?>">
<div class="x_content">
  <div class="well" style="overflow: auto">
    
    <!-- Invoice and Delivery Date -->
    <div class="col-md-6">
      <label class="control-label col-md-3 col-sm-3 col-xs-3">Select order</label>
      <div class="col-sm-9 col-xs-9">
        <select class="form-control" name="sales_order_del" id="sales_order_del">
            <option value="">--Select sales order --</option>
            <?php if (!empty($sales_order_list)): ?>
          <?php foreach ($sales_order_list as $so): ?>
            <?php 
              $so_id   = is_array($so) ? $so['so_id'] : $so->so_id;
              $so_code = is_array($so) ? $so['so_code'] : $so->so_code;
            ?>
            <option value="<?= $so_id ?>" <?php //$selected ?>>
              <?= htmlspecialchars($so_code) ?>
            </option>
          <?php endforeach; ?>
        <?php endif; ?>
        </select>
      </div>
    </div>
    
    <div class="col-md-6">
      <label class="control-label col-md-3 col-sm-3 col-xs-3">Delivery Date</label>
      <div class="col-md-9 col-sm-9 col-xs-9">
        <input type="date" class="form-control" data-inputmask="'mask' : '99/99/9999'" name="dc_date" id="dc_date" >
      </div>
    </div>
    
    <div class="clearfix"></div><br/><br/>
    
    <!-- Delivery Code and Warehouse -->
    <div class="col-md-6">
      <label class="control-label col-md-3 col-sm-3 col-xs-3">Delivery Code</label>
      <div class="col-md-9 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="delivery_code" id="delivery_code" value="<?= isset($delivery_code)?$delivery_code:""?>" readonly>  
      </div>
    </div>
    <div class="col-md-6">
      <label class="control-label col-md-3 col-sm-3 col-xs-3">Delivery Mode</label>
      <div class="col-sm-9 col-xs-9">
        <select class="form-control" id="delivery_mode" name="delivery_mode" >
            <option value="">--Select--</option>
            <option value="Road">Road</option>
            <option value="Air">Air</option>
            <option value="Sea">Sea</option>
            <option value="Courier">Courier</option>
            <option value="Rail">Rail</option>
            <option value="Other">Other</option>
        </select>
      </div>
    </div>  
    
    
    
    <div class="clearfix"></div><br/><br/>
    <!-- Delivery mode and Deliverd by  -->
    <div class="col-md-6">
      <label class="control-label col-md-3 col-sm-3 col-xs-3">Deliverd by</label>
      <div class="col-md-9 col-sm-9 col-xs-9">
       <input type="text" class="form-control" name="deliverd_by" id="deliverd_by" value="" >  
      </div>
    </div>
    <div class="col-md-6">
      <label class="control-label col-md-3 col-sm-3 col-xs-3">Customer</label>
      <div class="col-md-9 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="del_customer" id="del_customer" value="<?= $enquiry_data['customer_name'] ?>" readonly>
      </div>
    </div>

    
    <!-- PO Code and PO Date -->
    <!-- <div class="col-md-6">
      <label class="control-label col-md-3 col-sm-3 col-xs-3">PO Code</label>
      <div class="col-md-9 col-sm-9 col-xs-9">
        <input type="text" class="form-control" name="po_code" id="po_code">
      </div>
    </div>
    
    <div class="col-md-6">
      <label class="control-label col-md-3 col-sm-3 col-xs-3">PO Date</label>
      <div class="col-md-9 col-sm-9 col-xs-9">
        <input type="date" class="form-control" name="po_date" id="po_date">
      </div>
    </div> -->

  </div>
</div> <!-- End of top x_content -->

<!-- Table Section -->
<div class="row col-md-12 col-sm-12" style="overflow: scroll;">
  <div class="x_content">
    <table id="del_products_table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
      <thead>
        <tr>
           <th>#</th>
          <th>Product</th>
          <th>Qty</th>          
        </tr>
      </thead>
      <tbody>
        
      </tbody>
    </table>
  </div>
</div>

<!-- Shipping Address Section -->
<div class="x_content well">
  
  <div class="row col-md-12 col-sm-12">
    <label class="control-label col-md-2 col-sm-3 col-xs-3">Shipping Address</label>
    <div class="col-md-3 col-sm-9 col-xs-9">
      <input type="text" class="form-control" name="del_shipping_address" id="del_shipping_address">  
    </div>
    
    <label class="control-label col-md-2 col-sm-3 col-xs-3">Shipping City</label>
    <div class="col-md-3 col-sm-9 col-xs-9">
      <input type="text" class="form-control" name="del_shipping_city" id="del_shipping_city">  
    </div>
  </div>
  
  <div class="clearfix"></div><br/><br/>
  
  <div class="row col-md-12 col-sm-12">
    <label class="control-label col-md-2 col-sm-3 col-xs-3">Contact no</label>
    <div class="col-md-3 col-sm-9 col-xs-9">
      <input type="text" class="form-control" name="del_shipping_contact" id="del_shipping_contact">  
    </div>
    
    <label class="control-label col-md-2 col-sm-3 col-xs-3">Email</label>
    <div class="col-md-3 col-sm-9 col-xs-9">
      <input type="text" class="form-control" name="del_shipping_email" id="del_shipping_email">  
    </div>
  </div>
  
  <div class="clearfix"></div><br/><br/>
 
  
  <div class="clearfix"></div><br/><br/>
  <div class="row col-md-12 col-sm-12">
    <label class="control-label col-md-2 col-sm-3 col-xs-3">Remark</label>
    <div class="col-md-3 col-sm-9 col-xs-9">
      <textarea name="del_remark" id="del_remark" class="form-control del_remark"></textarea>  
    </div>
  </div>
  
  
  <div class="col-md-12 submit_div">
    <button type="submit" class="btn btn-success">Create new Delivery challan</button>
  </div>
          </form>
</div> <!-- End of shipping x_content well -->
<script>
   $("#sales_order_del").on("change", function () {
    let so_id = $(this).val();
    let enq_id = <?= $enquiry_data['enquiry_id'] ?>;
    let qtn_id = <?= isset($qtn_master['quotation_id'])?$qtn_master['quotation_id']:0 ?>;

    $.ajax({
        url: "<?= base_url('index.php/Sales/get_sales_order_partial_del') ?>",
        type: "POST",
        data: { so_id: so_id, enq_id: enq_id, qtn_id: qtn_id },
        dataType: "json",
        success: function (res) {
            console.log("Full response:", res);

            if (!res || res.error) {
                alert("No data found for this Sales Order.");
                return;
            }
            alert(res.so_master.remark);
            if (res.source === "sales_order") {
                $('#del_products_table').html(res.products_html);

                $('input[name="del_shipping_address"]').val(res.so_address.shipping_address || '');
                $('input[name="del_shipping_city"]').val(res.so_address.shipping_emirate || '');
                $('input[name="del_shipping_contact"]').val(res.so_address.shipping_contact || '');
                $('input[name="del_shipping_email"]').val(res.so_address.shipping_email || '');

                $('.submit_div').show();

            } else if (res.source === "delivery") {
                
                $('#print_del').html('<a href="<?= base_url("index.php/Document_controller/print_delivery_challan/") ?>'+ res.so_master.del_id +'/'+enq_id+'" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>');

                $('#del_products_table').html(res.products_html);

                $('input[name="dc_date"]').val(res.so_date || '');
                $('#delivery_mode').val(res.so_master.delivery_mode || '');
                $('input[name="deliverd_by"]').val(res.so_master.deliverd_by || '');

                $('input[name="del_shipping_address"]').val(res.so_master.shipping_address || '');
                $('input[name="del_shipping_city"]').val(res.so_master.shipping_city || '');
                $('input[name="del_shipping_contact"]').val(res.so_master.contact || '');
                $('input[name="del_shipping_email"]').val(res.so_master.email || '');
                $('.del_remark').val(res.so_master.remark || '');

                $('.submit_div').hide(); // ✅ optional if you don’t want editing
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);
            alert("Failed to fetch Sales Order / Delivery details.");
        }
    });
});

</script>
  