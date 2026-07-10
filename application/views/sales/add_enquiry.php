<form action="<?= base_url('index.php/Sales/add_enquiry_data') ?>"
      method="post"
      enctype="multipart/form-data"
      autocomplete="off">

    <div class="row">

        <!-- Branch -->
        <div class="col-md-6 col-sm-6 form-group">
            <label>Branch <span class="text-danger">*</span></label>
            <?= form_error('branch', '<small class="text-danger">', '</small>') ?>
            <select name="branch" id="branch" class="form-control" required>
                <option value="">Select</option>
                <?php foreach($branch_list as $branch): ?>
                    <option value="<?= $branch->branch_id ?>"
                        <?= isset($enquiry_data) && $enquiry_data['branch_id'] == $branch->branch_id ? "selected" : "" ?>>
                        <?= $branch->branch_name ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Project Name -->
        <div class="col-md-6 col-sm-6 form-group">
            <label>Project Name <span class="required">*</span></label>
            <input type="text"
                   name="project_name"
                   class="form-control"
                   value="<?= isset($enquiry_data) ? $enquiry_data['project_name'] : "" ?>"
                   required>
        </div>

        <!-- Project Subject -->
        <div class="col-md-6 col-sm-6 form-group">
            <label>Project Subject <span class="required">*</span></label>
            <input type="text"
                   name="project_subject"
                   class="form-control"
                   value="<?= isset($enquiry_data) ? $enquiry_data['project_subject'] : "" ?>"
                   required>
        </div>

        <!-- Project Location -->
        <div class="col-md-6 col-sm-6 form-group">
            <label>Project Location</label>
            <input type="text"
                   name="project_location"
                   class="form-control"
                   value="<?= isset($enquiry_data) ? $enquiry_data['project_location'] : "" ?>">
        </div>

        <!-- Enquiry Category -->
        <div class="col-md-6 col-sm-6 form-group">
            <label>Enquiry Category <span class="required">*</span></label>
            <select name="enquiry_category" class="form-control" required>
                <option value="">-- Select --</option>
                <option value="Project" <?= (isset($enquiry_data['enquiry_category']) && $enquiry_data['enquiry_category']=='Project')?'selected':''; ?>>Project</option>
                <option value="Trading" <?= (isset($enquiry_data['enquiry_category']) && $enquiry_data['enquiry_category']=='Trading')?'selected':''; ?>>Trading</option>
                <option value="Service" <?= (isset($enquiry_data['enquiry_category']) && $enquiry_data['enquiry_category']=='Service')?'selected':''; ?>>Service</option>
                <option value="Others" <?= (isset($enquiry_data['enquiry_category']) && $enquiry_data['enquiry_category']=='Others')?'selected':''; ?>>Others</option>
            </select>
        </div>

        <!-- Enquiry Code -->
        <div class="col-md-6 col-sm-6 form-group">
            <label>Enquiry Code <span class="required">*</span></label>
            <input type="text"
                   name="enquiry_code"
                   class="form-control"
                   value="<?= isset($enquiry_data) ? $enquiry_data['enquiry_code'] : $enquiry_code ?>"
                   readonly>
        </div>

        <!-- Enquiry Date -->
        <div class="col-md-6 col-sm-6 form-group">
            <label>Enquiry Date <span class="required">*</span></label>
            <input type="date"
                   name="enquiry_date"
                   class="form-control"
                   value="<?= isset($enquiry_data['enquiry_date']) ? date('Y-m-d', strtotime($enquiry_data['enquiry_date'])) : date('Y-m-d') ?>"
                   required>
        </div>

        <!-- Enquiry Source -->
        <div class="col-md-6 col-sm-6 form-group">
            <label>Enquiry Source <span class="required">*</span></label>
            <select name="enquiry_source" class="form-control" required>
                <option value="">-- Select --</option>
                <option value="Email" <?= (isset($enquiry_data['enquiry_source']) && $enquiry_data['enquiry_source']=='Email')?'selected':''; ?>>Email</option>
                <option value="Phone" <?= (isset($enquiry_data['enquiry_source']) && $enquiry_data['enquiry_source']=='Phone')?'selected':''; ?>>Phone</option>
                <option value="Meeting" <?= (isset($enquiry_data['enquiry_source']) && $enquiry_data['enquiry_source']=='Meeting')?'selected':''; ?>>Meeting</option>
                <option value="Website" <?= (isset($enquiry_data['enquiry_source']) && $enquiry_data['enquiry_source']=='Website')?'selected':''; ?>>Website</option>
                <option value="Referral" <?= (isset($enquiry_data['enquiry_source']) && $enquiry_data['enquiry_source']=='Referral')?'selected':''; ?>>Referral</option>
            </select>
        </div>

    </div>

    <!-- Customer Section -->
    <div class="row">
        <div class="col-md-12">
            <h4>Customer Details</h4>
            <hr>
        </div>

        <div class="col-md-6 col-sm-6 form-group">
            <label>Select Customer <span class="required">*</span></label>
            <div id="customer_dropdown_wrapper">
                <select name="customer_id" class="form-control select2" required>
                    <option value="">-- Select Customer --</option>
                    <?php foreach ($customer_list as $c): ?>
                        <option value="<?= $c->customer_id ?>"
                            <?= (isset($enquiry_data['enquiry_customer']) && $enquiry_data['enquiry_customer'] == $c->customer_id) ? 'selected' : '' ?>>
                            <?= $c->customer_name ?> (<?= $c->customer_code ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <small>
                <a href="" target="_blank" class="view-employees" data-bs-toggle="modal"
                   data-bs-target="#myModal" data-id="1">+ Add New Customer</a>
            </small>
        </div>

        <div class="col-md-6 col-sm-6 form-group">
            <label>Client Ref No</label>
            <input type="text"
                   name="client_ref_no"
                   class="form-control"
                   value="<?= isset($enquiry_data['client_ref_no']) ? $enquiry_data['client_ref_no'] : "" ?>">
        </div>

        <div class="col-md-12 form-group">
            <label>Remarks / Comments</label>
            <textarea name="comments" class="form-control" rows="4"><?= isset($enquiry_data['comments']) ? $enquiry_data['comments'] : "" ?></textarea>
        </div>
    </div>

    <div class="row">
    <div class="col-md-12">
        <h4>Item Details</h4>
        <hr>

        <button type="button" 
                class="btn btn-info"
                data-toggle="modal"
                data-target="#itemCartModal">
            <i class="fa fa-shopping-cart"></i> Add Items
        </button>

    </div>
</div>


<div class="row mt-3">
    <div class="col-md-12">

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item</th>
                    <th width="100">Qty</th>
                    <th width="150">Price</th>
                    <th width="150">Amount</th>
                </tr>
            </thead>

            <tbody id="selectedCartItems">

            </tbody>

        </table>

    </div>
</div>

    <div class="ln_solid"></div>

      <div class="form-group text-center">
                          <button type="submit" id="saveBtn" class="btn btn-success">Submit</button>
                      </div>
         <div class="modal fade" id="itemCartModal">

<div class="modal-dialog modal-lg">

<div class="modal-content">


<div class="modal-header">
<h5 class="modal-title">
Select Items
</h5>

<button type="button" class="close" data-dismiss="modal">
×
</button>

</div>


<div class="modal-body">


<table class="table table-bordered">

<thead>

<tr>
<th>Item</th>
<th>Price</th>
<th width="200">Quantity</th>
</tr>

</thead>


<tbody>

<?php foreach($all_products as $item): ?>

<tr>

<td>
<?= $item->product_name ?>

<input type="hidden"
class="item_name"
value="<?= $item->product_name ?>">

<input type="hidden"
class="item_id"
value="<?= $item->product_code ?>">

</td>


<td>

<?= $item->retail_price ?>

<input type="hidden"
class="item_price"
value="<?= $item->retail_price ?>">

</td>


<td>

<button type="button"
class="btn btn-danger btn-sm minus">
-
</button>


<input type="text"
class="form-control d-inline qty"
style="width:60px"
value="0">


<button type="button"
class="btn btn-success btn-sm plus">
+
</button>


</td>


</tr>


<?php endforeach; ?>


</tbody>


</table>


</div>


<div class="modal-footer">

<button type="button"
class="btn btn-success"
id="addCart">
Add To Cart
</button>


</div>


</div>

</div>

</div>             

</form>
<script>
$(document).on('click','.plus',function(){

    let qty=$(this).siblings('.qty');

    qty.val(parseInt(qty.val())+1);

});


$(document).on('click','.minus',function(){

    let qty=$(this).siblings('.qty');

    let value=parseInt(qty.val());

    if(value>0)
    {
        qty.val(value-1);
    }

});
$('#addCart').click(function(){

    $('#selectedCartItems').html('');

    $('.qty').each(function(){

        let qty=parseInt($(this).val());

        if(qty>0)
        {

            let row=$(this).closest('tr');

            let name=row.find('.item_name').val();
            let id=row.find('.item_id').val();
            let price=row.find('.item_price').val();


            $('#selectedCartItems').append(`

            <tr>

            <td>
            ${name}

            <input type="hidden"
            name="item_id[]"
            value="${id}">
            
            </td>


            <td>
            ${qty}

            <input type="hidden"
            name="qty[]"
            value="${qty}">
            
            </td>


            <td>
            ${price}
            </td>


            <td>
            ${(qty*price).toFixed(2)}
            </td>


            </tr>

            `);

        }

    });


    $('#itemCartModal').modal('hide');

});
</script>