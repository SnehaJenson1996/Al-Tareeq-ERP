<style>
    .nav-link.active {
        background-color: #007FFF !important;
        /* set background */
        color: white !important;
        /* make text readable */
    }

    .form-control-sm {
        height: 30px;
        padding: 3px 6px;
        font-size: 13px;
    }
</style>

<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="x_panel">

            <!-- Flash message section -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('success'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php elseif ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('error'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Panel header -->
            <div class="x_title">
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="#">Quotation</a>
                            <a class="dropdown-item" href="#">Sales Order</a>
                            <a class="dropdown-item" href="#">Delivery Notes</a>
                            <a class="dropdown-item" href="#">Invoice</a>
                        </div>
                    </li>
                    <li>
                        <a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">

                <!-- Tabs -->
                <ul class="nav nav-tabs bar_tabs" id="myTab" role="tablist">

                    <!-- Quotation -->
                    <li class="nav-item">
                        <a class="nav-link <?= in_array($enquiry_data['enquiry_status'], [5, 6]) ? 'active' : '' ?>"
                            id="quotation-tab"
                            data-toggle="tab"
                            href="#quotation"
                            role="tab"
                            aria-controls="quotation"
                            aria-selected="<?= in_array($enquiry_data['enquiry_status'], [5, 6]) ? 'true' : 'false' ?>">
                            Quotation
                        </a>
                    </li>

                    <!-- Sales Order -->
                    <li class="nav-item">
                        <a class="nav-link <?= in_array($enquiry_data['enquiry_status'], [7, 8]) ? 'active' : '' ?>"
                            id="sales_order-tab"
                            data-toggle="tab"
                            href="#sales_order"
                            role="tab"
                            aria-controls="sales_order"
                            aria-selected="<?= in_array($enquiry_data['enquiry_status'], [7, 8]) ? 'true' : 'false' ?>">
                            Sales Order
                        </a>
                    </li>

                    <!-- Delivery Notes -->
                    <li class="nav-item">
                        <a class="nav-link <?= ($enquiry_data['enquiry_status'] == 9) ? 'active' : '' ?>"
                            id="delivery_notes-tab"
                            data-toggle="tab"
                            href="#delivery_notes"
                            role="tab"
                            aria-controls="delivery_notes"
                            aria-selected="<?= ($enquiry_data['enquiry_status'] == 9) ? 'true' : 'false' ?>">
                            Delivery Challan
                        </a>
                    </li>

                    <!-- Invoice -->
                    <li class="nav-item">
                        <a class="nav-link <?= ($enquiry_data['enquiry_status'] >= 10) ? 'active' : '' ?>"
                            id="invoice-tab"
                            data-toggle="tab"
                            href="#invoice"
                            role="tab"
                            aria-controls="invoice"
                            aria-selected="<?= ($enquiry_data['enquiry_status'] >= 10) ? 'true' : 'false' ?>">
                            Invoice
                        </a>
                    </li>

                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="myTabContent">

                    <!-- Quotation -->
                    <div class="tab-pane fade <?= in_array($enquiry_data['enquiry_status'], [5, 6]) ? 'active show' : '' ?>"
                        id="quotation"
                        role="tabpanel"
                        aria-labelledby="quotation-tab">
                        <!-----Quotation---->
                        <?php if (!empty($enquiry_data['enquiry_status']) && ($enquiry_data['enquiry_status'] == 5)): ?>
                            <form action="<?= base_url() ?>index.php/Sales/add_quotation" method="post">
                                <input type="hidden" name="enquiry_id" value="<?= isset($enquiry_id) ? $enquiry_id : "" ?>">
                                <input type="hidden" name="estimation_id" value="<?= isset($master['estimation_id']) ? $master['estimation_id'] : "" ?>">

                                <div class="row">
                                    <!-- Enquiry Code -->
                                    <div class="col-md-6">
                                        <div class="form-group row align-items-center">
                                            <label class="col-sm-4 col-form-label">Enquiry Code:</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="enquiry_code" name="enquiry_code"
                                                    value="<?= $enquiry_data['enquiry_code'] ?>" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Enquiry Branch -->
                                    <div class="col-md-6">
                                        <div class="form-group row align-items-center">
                                            <label class="col-sm-4 col-form-label">Enquiry Branch:</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="branch_name" name="branch_name"
                                                    value="<?= $enquiry_data['branch_name'] ?>" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Project Name -->
                                    <div class="col-md-6">
                                        <div class="form-group row align-items-center">
                                            <label class="col-sm-4 col-form-label">Project Name:</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="project_name" name="project_name"
                                                    value="<?= $enquiry_data['project_name'] ?>" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Customer Name -->
                                    <div class="col-md-6">
                                        <div class="form-group row align-items-center">
                                            <label class="col-sm-4 col-form-label">Customer:</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="customer_name" name="customer_name"
                                                    value="<?= $enquiry_data['customer_name'] ?>" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- Quotation Code -->
                                    <div class="col-md-6">
                                        <div class="form-group row align-items-center">
                                            <label class="col-sm-4 col-form-label">Quotation Code:</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="quotation_code" name="quotation_code"
                                                    value="<?= $quotation_code ?>" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quotation Date -->
                                    <div class="col-md-6">
                                        <div class="form-group row align-items-center">
                                            <label class="col-sm-4 col-form-label">Quotation Date:</label>
                                            <div class="col-sm-8">
                                                <input type="date" id="quotation_date" name="quotation_date"
                                                    value="" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php if (isset($estimation)): $i = 0; ?>
                                    <?php foreach ($estimation as  $main): ?>
                                        <div id="main_heading_block_<?= $i ?>" class="border p-2 mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div style="width: 40%;">
                                                    <label>Main Heading</label>
                                                    <input type="text"
                                                        name="main_heading[<?= $i ?>]"
                                                        value="<?= $main['main_heading'] ?>"
                                                        class="estimation_edit form-control"
                                                        placeholder="Enter Main Heading">
                                                </div>
                                                <div style="width: 45%;">
                                                    <label>Details</label>
                                                    <textarea name="main_details[<?= $i ?>]"
                                                        class="estimation_edit form-control"
                                                        placeholder="Enter Details">
                                            <?= $main['main_details'] ?></textarea>
                                                </div>


                                            </div>

                                        </div>
                                        <?php $j = 0;
                                        foreach ($main['sub_headings'] as $sub): ?>
                                            <div class="border p-2 mb-2 subHeadingContainer" data-main="<?= $i ?>" data-sub="<?= $j ?>">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <input type="text" name="sub_heading[<?= $i ?>][<?= $j ?>]" value="<?= $sub['sub_heading'] ?>" class="form-control form-control-sm w-75" placeholder="Enter Sub Heading">
                                                </div>

                                                <table class="table table-bordered qtn_productTable mb-0" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr style="background-color:#f8f9fa;">
                                                            <th>Product</th>
                                                            <th style="width:100px;">Unit</th>
                                                            <th style="width:100px;">Qty</th>
                                                            <th style="width:120px;">Unit Price</th>
                                                            <th style="width:120px;">Amount</th>
                                                            <th style="width:60px;">discount</th>
                                                            <th style="width:60px;">Taxable amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $k = 0;
                                                        foreach ($sub['products'] as $prod): ?>
                                                            <tr>
                                                                <td>
                                                                    <select name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][product_id]"
                                                                        class="form-control form-control-sm estimation_edit product-select">
                                                                        <option value="">-- Select Product --</option>
                                                                        <?php foreach ($all_products as $p): ?>
                                                                            <option value="<?= $p->item_id  ?>"
                                                                                <?= ($p->item_id  == $prod['product_id']) ? 'selected' : '' ?>>
                                                                                <?= $p->item_name ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    </select><br><br>

                                                                    <textarea name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][product_description]" class="form-control form-control-sm estimation_edit"><?= $prod['product_description'] ?></textarea>
                                                                </td>
                                                                <td>
                                                                    <select name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][unit]" class="form-control estimation_edit ">
                                                                        <option value="">-- Select Unit --</option>
                                                                        <?php foreach ($active_units as $unit): ?>
                                                                            <option value="<?= $unit->unit_id ?>"
                                                                                <?= ($prod['unit_id'] == $unit->unit_id) ? 'selected' : '' ?>>
                                                                                <?= $unit->unit_name ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </td>

                                                                <td><input type="number" name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][quantity]" value="<?= $prod['quantity'] ?>" class="form-control quotation_edit qtn_qty" readonly></td>
                                                                <td><input type="number" step="0.01" name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][unit_price]" value="<?= $prod['unit_price'] ?>" class="form-control quotation_edit qtn_unitPrice" readonly></td>
                                                                <td><input type="number" step="0.01" name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][amount]" value="<?= $prod['amount'] ?>" class="form-control quotation_edit qtn_amount" readonly></td>
                                                                <td>
                                                                    <input type="text" name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][discount_amount]" value="" class="form-control quotation_edit qtn_discount_amount">
                                                                </td>
                                                                <td>
                                                                    <input type="number" step="0.01" name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][taxable_amount]" value="" class="form-control quotation_edit qtn_taxable_amount" readonly>

                                                                </td>
                                                            </tr>
                                                        <?php $k++;
                                                        endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php $j++;

                                        endforeach; ?>

                                    <?php $i++;
                                    endforeach; ?>
                                    <?php endif; ?>>


                                    <!-- Summary -->
                                    <div class="row justify-content-center">
                                        <div class="col-md-10">
                                            <div class="row">

                                                <!-- Left Column -->
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label">Sub Total</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="qtn_sub_total" id="qtn_sub_total" class="form-control qtn_sub_total" value="<?= isset($master['sub_total']) ? $master['sub_total'] : "" ?>">
                                                        </div>
                                                    </div>


                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label">Additional Discount (%)</label>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="qtn_add_discount_percentage" id="qtn_add_discount_percentage" class="form-control " value="">
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="text" name="qtn_add_discount_amount" id="qtn_add_discount_amount" class="form-control " value="">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label">Apply VAT</label>
                                                        <div class="col-sm-8">
                                                            <input type="checkbox" id="qtn_apply_vat" name="qtn_apply_vat">
                                                            <input type="number" name="qtn_vat_percentage" id="qtn_vat_percentage" value="5" class="form-control mt-2" style="width:100px;" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label">VAT Amount</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="qtn_vat_amount" id="qtn_vat_amount" class="form-control estimation_edit" value="" readonly>
                                                        </div>
                                                    </div>


                                                </div>

                                                <!-- Right Column -->
                                                <div class="col-md-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label font-weight-bold">Estimation Cost</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="qtn_estimation_cost" id="estimation_cost" class="form-control font-weight-bold" value="<?= isset($master['grand_total']) ? $master['grand_total'] : "" ?>" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label">total before vat</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="total_before_vat" id="total_before_vat" class="form-control estimation_edit" value="<?= isset($master['grand_total']) ? $master['grand_total'] : "" ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label">Grand Total</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="qtn_grand_total" id="qtn_grand_total" class="form-control" value="<?= isset($master['sub_total']) ? $master['sub_total'] : "" ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Row 1 -->
                                            <div class="form-group row">
                                                <!-- Payment Term -->
                                                <div class="col-sm-6">
                                                    <label class="col-form-label">Payment Term</label>
                                                    <input type="text" name="payment_term" id="payment_term"
                                                        class="form-control estimation_edit"
                                                        value="<?= isset($master['payment_term']) ? $master['payment_term'] : "" ?>">
                                                </div>
                                                <!-- Validity -->
                                                <div class="col-sm-6">
                                                    <label class="col-form-label">Validity</label>
                                                    <input type="text" name="validity" id="validity"
                                                        class="form-control estimation_edit"
                                                        value="<?= isset($master['validity']) ? $master['validity'] : "" ?>">
                                                </div>

                                            </div>

                                            <!-- Row 2 -->
                                            <div class="form-group row">

                                                <!-- Delivery Term (CKEditor) -->
                                                <div class="col-sm-6">
                                                    <label class="col-form-label">Delivery Term</label>
                                                    <textarea name="delivery_term" id="delivery_term"
                                                        class="form-control estimation_edit"><?= isset($master['delivery_term']) ? $master['delivery_term'] : "" ?></textarea>
                                                </div>

                                                <!-- Terms & Conditions (CKEditor) -->
                                                <div class="col-sm-6">
                                                    <label class="col-form-label">Terms & Conditions</label>
                                                    <textarea name="terms_condition" id="terms_condition"
                                                        class="form-control estimation_edit"><?= isset($master['terms_condition']) ? $master['terms_condition'] : "" ?></textarea>
                                                </div>
                                            </div>

                                            <!-- Centered Button -->
                                            <div class="row justify-content-center mt-3">
                                                <button type="submit" class="btn btn-success">Create Quotation</button>
                                            </div>

                                        </div>
                                    </div>
                            </form>
                        <?php elseif (!empty($enquiry_data['enquiry_status']) && $enquiry_data['enquiry_status'] >= 6): ?>

                        <?php endif; ?>
                        <!-- </div> -->
                        <!-- </div> -->
                    </div>


                </div>

                <!-- Sales Order -->
                <div class="tab-pane fade <?= in_array($enquiry_data['enquiry_status'], [7, 8]) ? 'active show' : '' ?>"
                    id="sales_order"
                    role="tabpanel"
                    aria-labelledby="sales_order-tab">
                    <div class="x_content">
                        <!-- Quotation / Sales Order Header -->
                        <?php if (!empty($enquiry_data['enquiry_status']) && ($enquiry_data['enquiry_status'] == 7)): ?>

                            <!-- Sales Order Header -->
                            <form action="<?= base_url() ?>index.php/Sales/save_sales_order" method="post">
                                <input type="hidden" name="quotation_id" value="<?= $qtn_master['quotation_id'] ?>">
                                <input type="hidden" name="enquiry_id" value="<?= $enquiry_data['enquiry_id'] ?>">
                                <input type="hidden" name="estimation_id" value="<?= $qtn_master['estimation_id'] ?>">

                                <table class="table table-bordered" style="width:100%; border-collapse:collapse; font-size:13px; margin-bottom:20px;">
                                    <tr>
                                        <th style="width:20%;">Sales Order date </th>
                                        <td style="width:30%;"><input type="date" name="so_date" class="form-control"></td>
                                        <th style="width:20%;">Sales Order No</th>
                                        <td style="width:30%;"><input type="text" name="so_code" value="<?= isset($so_code) ? $so_code : 0 ?>" class="form-control" readonly></td>
                                    </tr>
                                    <tr>
                                        <th>Quotation No</th>
                                        <td><input type="text" class="form-control" value="<?= $qtn_master['quotation_code'] ?>" readonly></td>
                                        <th>Quotation Date</th>
                                        <td><input type="text" class="form-control" value="<?= date('d-m-Y', strtotime($qtn_master['quotation_date'])) ?>" readonly></td>
                                    </tr>
                                    <tr>
                                        <th>Branch</th>
                                        <td><input type="text" class="form-control" value="<?= $enquiry_data['branch_name'] ?>" readonly></td>
                                        <th>Prepared By</th>
                                        <td><input type="text" class="form-control" value="<?= $enquiry_data['user_name'] ?>" readonly></td>
                                    </tr>
                                    <tr>
                                        <th>Project Name</th>
                                        <td><input type="text" class="form-control" value="<?= $enquiry_data['project_name'] ?>" readonly></td>
                                        <th>Customer</th>
                                        <td><input type="text" class="form-control" value="<?= $enquiry_data['customer_name'] ?>" readonly></td>
                                    </tr>

                                </table>

                                <hr>

                                <!-- Product List -->
                                <div class="row">
                                    <div class="col-12 table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product</th>
                                                    <th>Unit</th>
                                                    <th>Qty</th>
                                                    <th>Unit Price</th>
                                                    <th>Amount</th>
                                                    <th>Discount amount</th>
                                                    <th>Taxable amount</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($qtn_products)): ?>
                                                    <?php foreach ($qtn_products as $i => $item): ?>
                                                        <tr>
                                                            <td><?= $i + 1 ?></td>
                                                            <td>
                                                                <?= $item['item_name'] ?><br>
                                                                <?= $item['prd_description'] ?>
                                                                <input type="hidden" name="product_id[]" value="<?= $item['prd_id'] ?>">
                                                                <input type="hidden" name="product_desc[]" value="<?= $item['prd_description'] ?>">
                                                            </td>
                                                            <td>
                                                                <?= $item['unit_name'] ?>
                                                                <input type="hidden" name="unit_id[]" value="<?= $item['unit_id'] ?>">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="so_qty[]"
                                                                    value="<?= $item['available_qty'] ?>"
                                                                    data-maxqty="<?= $item['available_qty'] ?>"
                                                                    class="form-control so_qty">
                                                                <input type="hidden" name="so_max_qty[]" value="<?= $item['available_qty'] ?>">
                                                            </td>
                                                            <td>
                                                                <input type="text" name="so_unitp[]"
                                                                    value="<?= $item['unit_price'] ?>"
                                                                    class="form-control so_unitp" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="text" name="so_amount[]"
                                                                    value="<?= number_format(($item['available_qty'] * $item['unit_price']), 2) ?>"
                                                                    class="form-control so_amount" readonly>
                                                                <!-- <input type="hidden" name="so_max_amount[]" value="<?= $item['amount'] ?>"> -->

                                                            </td>
                                                            <td>
                                                                <input type="text" name="so_discount[]"
                                                                    value="<?= $item['dicount_amount'] ?>"
                                                                    class="form-control so_discount">
                                                                <!-- <input type="hidden" name="so_max_discount[]" value="<?= $item['dicount_amount'] ?>"> -->
                                                            </td>
                                                            <td>
                                                                <input type="text" name="so_taxable[]"
                                                                    value="<?= number_format((($item['available_qty'] * $item['unit_price']) - $item['dicount_amount']), 2) ?>"
                                                                    class="form-control so_taxable" readonly>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>

                                <!-- Financial Summary -->
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <th style="width:50%">Subtotal:</th>
                                                <td><input type="text" name="so_subtotal" value="<?= number_format($qtn_master['sub_total'], 2) ?>" class="form-control so_subtotal" readonly></td>
                                            </tr>
                                            <tr>
                                                <th>Discount(<?= $qtn_master['discount_percentage'] ?>)%:</th>
                                                <td>
                                                    <input type="hidden" name="so_add_discount_percentage" value="<?= $qtn_master['discount_percentage'] ?>">
                                                    <input type="text" name="so_add_discount_amount" value="<?= number_format($qtn_master['discount_amount'], 2) ?>" class="form-control so_discount_amount" readonly>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Total before vat</th>
                                                <td>
                                                    <input type="text" name="so_totalbefore_vat_amount" value="<?= number_format($qtn_master['total_before_vat'], 2) ?>" class="form-control so_totalbefore_vat_amount" readonly>
                                                </td>
                                            </tr>
                                            <?php if (!empty($qtn_master['vat_required'])) { ?>
                                                <tr>
                                                    <th>VAT(<?= $qtn_master['vat_percentage'] ?>):</th>

                                                    <td>

                                                        <input type="hidden" name="so_vat_percentage" value="<?= $qtn_master['vat_percentage'] ?>" class="so_vat_percentage">
                                                        <input type="text" name="so_vat_amount" value="<?= number_format($qtn_master['vat_amount'], 2) ?>" class="form-control so_vat_amount" readonly>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <th>Grand Total:</th>
                                                <td>
                                                    <input type="text" name="so_grand_total" value="<?= number_format($qtn_master['grand_total'], 2) ?>" class="form-control so_grand_total" readonly>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <!-- </div> -->
                                <!----------Adress------------->
                                <div class="checkbox">
                                    <label class="">
                                        <div class="icheckbox_flat-green" style="position: relative;">
                                            <input type="checkbox" id="copyAddress" class="flat" style="position: absolute; opacity: 0;">
                                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                        </div>
                                        Shipping address is same as Billing address
                                    </label>
                                </div>
                                <table class="table" style="width:100%; border-collapse:collapse; font-size:13px;">
                                    <thead>
                                        <tr>
                                            <th style="width:50%;">Billing Address</th>
                                            <th style="width:50%;">Shipping Address</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <!-- Billing -->
                                            <td>
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input type="text" id="billing_name" name="billing_name" value="<?= $enquiry_data['customer_name'] ?>" class="form-control" />
                                                </div>
                                                <div class="form-group">
                                                    <label>Address</label>
                                                    <input type="text" id="billing_address" name="billing_address" value="<?= $enquiry_data['customer_address'] ?>" class="form-control" />
                                                </div>
                                                <div class="form-group">
                                                    <label>Emirate</label>
                                                    <input type="text" id="billing_city" name="billing_city" value="<?= $enquiry_data['emirate'] ?>" class="form-control" />
                                                </div>
                                                <div class="form-group">
                                                    <label>Phone</label>
                                                    <input type="text" id="billing_phone" name="billing_phone" value="<?= $enquiry_data['contact_number'] ?>" class="form-control" />
                                                </div>
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="text" id="billing_email" name="billing_email" value="<?= $enquiry_data['customer_email'] ?>" class="form-control" />
                                                </div>
                                            </td>

                                            <!-- Shipping -->
                                            <td>
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input type="text" id="shipping_name" name="shipping_name" class="form-control" />
                                                </div>
                                                <div class="form-group">
                                                    <label>Address</label>
                                                    <input type="text" id="shipping_address" name="shipping_address" class="form-control" />
                                                </div>
                                                <div class="form-group">
                                                    <label>Emirate</label>
                                                    <input type="text" id="shipping_city" name="shipping_city" class="form-control" />
                                                </div>
                                                <div class="form-group">
                                                    <label>Phone</label>
                                                    <input type="text" id="shipping_phone" name="shipping_phone" class="form-control" />
                                                </div>
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="text" id="shipping_email" name="shipping_email" class="form-control" />
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- Terms & Remarks Section -->
                                <!-- Terms & Remarks Section -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h5>Terms & Conditions</h5>
                                        <table class="table table-bordered" style="width:100%; border-collapse:collapse; font-size:13px;">
                                            <tbody>
                                                <tr>
                                                    <th style="width:20%;">Payment Term</th>
                                                    <td>
                                                        <input type="text" name="so_payment_term" class="form-control"
                                                            value="<?= isset($qtn_master['payment_term']) ? $qtn_master['payment_term'] : '' ?>">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th style="width:20%;">Validity</th>
                                                    <td>
                                                        <input type="text" name="so_validity" class="form-control"
                                                            value="<?= isset($qtn_master['validity']) ? $qtn_master['validity'] : '' ?>">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th style="width:20%;">Delivery Term</th>
                                                    <td>
                                                        <textarea name="so_delivery_term" id="so_delivery_term"
                                                            class="form-control estimation_edit" rows="3"><?= isset($qtn_master['delivery_term']) ? $qtn_master['delivery_term'] : '' ?></textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th style="width:20%;">General Terms & Conditions</th>
                                                    <td>
                                                        <textarea name="so_terms_condition" id="so_terms_condition"
                                                            class="form-control estimation_edit" rows="4"><?= isset($qtn_master['terms_condition']) ? $qtn_master['terms_condition'] : '' ?></textarea>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th style="width:20%;">Remarks</th>
                                                    <td>
                                                        <textarea name="so_remarks" rows="2" class="form-control"></textarea>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <input type="submit" name="save" value="save" class="btn btn-success">
                            </form>
                        <?php elseif (!empty($enquiry_data['enquiry_status']) && $enquiry_data['enquiry_status'] >= 8): ?>


                            <section class="content invoice">
                                <!-- Sales Order Dropdown & Action Buttons -->
                                <div class="row no-print mt-3">
                                    <div class="col-md-4">
                                        <select name="sales_order_id" id="sales_order" class="form-control sales_order">
                                            <option value="">-- Select Sales Order --</option>
                                            <?php if (!empty($sales_order_list)): ?>
                                                <?php foreach ($sales_order_list as $so):
                                                    $so_id   = is_array($so) ? $so['so_id'] : $so->so_id;
                                                    $so_code = is_array($so) ? $so['so_code'] : $so->so_code;
                                                ?>
                                                    <option value="<?= $so_id ?>"><?= htmlspecialchars($so_code) ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-8 text-right">
                                        <div id="print_so"></div>
                                        <button type="button" id="create_delivery_note" class="btn btn-success" style="display:none;">
                                            <i class="fa fa-truck"></i> Create Delivery Challan
                                        </button>

                                        <button type="button" class="btn btn-success" id="create_new_sales_order"
                                            onclick="update_enquiry_for_sales_order(<?= $enquiry_data['enquiry_id'] ?>)" style="display:none;">
                                            Create New Sales Order
                                        </button>
                                    </div>
                                </div>
                            </section>

                            <!-- Update Sales Order Form -->
                            <form action="<?= base_url() ?>index.php/Sales/update_sales_order" method="post">
                                <input type="hidden" name="quotation_id" value="<?= $qtn_master['quotation_id'] ?>">
                                <input type="hidden" name="enquiry_id" value="<?= $enquiry_data['enquiry_id'] ?>">
                                <input type="hidden" name="estimation_id" value="<?= $qtn_master['estimation_id'] ?>">
                                <input type="hidden" name="so_id_upd" value="">

                                <!-- Sales Order Header Table -->
                                <table class="table table-bordered" style="width:100%; font-size:13px; margin-bottom:20px;">
                                    <tr>
                                        <th style="width:20%;">Sales Order Date</th>
                                        <td style="width:30%;"><input type="date" name="so_edit_date" class="form-control so_edit_date"></td>
                                        <th style="width:20%;">Sales Order No</th>
                                        <td style="width:30%;"><input type="text" name="so_edit_code" class="form-control so_edit_code" readonly></td>
                                    </tr>
                                    <tr>
                                        <th>Quotation No</th>
                                        <td><input type="text" class="form-control" value="<?= $qtn_master['quotation_code'] ?>" readonly></td>
                                        <th>Quotation Date</th>
                                        <td><input type="text" class="form-control" value="<?= date('d-m-Y', strtotime($qtn_master['quotation_date'])) ?>" readonly></td>
                                    </tr>
                                    <tr>
                                        <th>Branch</th>
                                        <td><input type="text" class="form-control" value="<?= $enquiry_data['branch_name'] ?>" readonly></td>
                                        <th>Prepared By</th>
                                        <td><input type="text" class="form-control" value="<?= $enquiry_data['user_name'] ?>" readonly></td>
                                    </tr>
                                    <tr>
                                        <th>Project Name</th>
                                        <td><input type="text" class="form-control" value="<?= $enquiry_data['project_name'] ?>" readonly></td>
                                        <th>Customer</th>
                                        <td><input type="text" class="form-control" value="<?= $enquiry_data['customer_name'] ?>" readonly></td>
                                    </tr>
                                </table>

                                <!-- Product List Table -->
                                <div class="row">
                                    <div class="col-12 table-responsive">
                                        <table id="so_products_table" class="table table-bordered table-striped">
                                            <!-- Populated dynamically via JS -->
                                        </table>
                                    </div>
                                </div>

                                <!-- Financial Summary -->
                                <div class="row">
                                    <div class="col-md-6">&nbsp;</div>
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <tr>
                                                    <th style="width:50%;">Subtotal:</th>
                                                    <td><input type="text" name="so_edit_subtotal" class="form-control so_edit_subtotal" readonly></td>
                                                </tr>
                                                <tr>
                                                    <th>Discount (<span class="so_edit_add_discount_percentage"></span> %):</th>
                                                    <td>
                                                        <input type="hidden" name="so_edit_add_discount_percentage" class="so_edit_add_discount_percentage">
                                                        <input type="text" name="so_edit_add_discount_amount" class="form-control so_edit_discount_amount" readonly>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Total before VAT:</th>
                                                    <td><input type="text" name="so_edit_totalbefore_vat_amount" class="form-control so_edit_totalbefore_vat_amount" readonly></td>
                                                </tr>
                                                <tr>
                                                    <th>VAT (<span class="so_edit_vat_percentage"></span> %):</th>
                                                    <td>
                                                        <input type="hidden" name="so_edit_vat_percentage">
                                                        <input type="text" name="so_edit_vat_amount" class="form-control so_edit_vat_amount" readonly>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Grand Total:</th>
                                                    <td><input type="text" name="so_edit_grand_total" class="form-control so_edit_grand_total" readonly></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Address Section -->
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="copyAddress" class="flat"> Shipping address is same as Billing address
                                    </label>
                                </div>

                                <table class="table" style="width:100%; font-size:13px;">
                                    <thead>
                                        <tr>
                                            <th style="width:50%;">Billing Address</th>
                                            <th style="width:50%;">Shipping Address</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <!-- Billing -->
                                            <td>
                                                <div class="form-group"><label>Name</label><input type="text" name="so_edit_billing_name" class="form-control"></div>
                                                <div class="form-group"><label>Address</label><input type="text" name="so_edit_billing_address" class="form-control"></div>
                                                <div class="form-group"><label>Emirate</label><input type="text" name="so_edit_billing_city" class="form-control"></div>
                                                <div class="form-group"><label>Phone</label><input type="text" name="so_edit_billing_phone" class="form-control"></div>
                                                <div class="form-group"><label>Email</label><input type="text" name="so_edit_billing_email" class="form-control"></div>
                                            </td>

                                            <!-- Shipping -->
                                            <td>
                                                <div class="form-group"><label>Name</label><input type="text" name="so_edit_shipping_name" class="form-control"></div>
                                                <div class="form-group"><label>Address</label><input type="text" name="so_edit_shipping_address" class="form-control"></div>
                                                <div class="form-group"><label>Emirate</label><input type="text" name="so_edit_shipping_city" class="form-control"></div>
                                                <div class="form-group"><label>Phone</label><input type="text" name="so_edit_shipping_phone" class="form-control"></div>
                                                <div class="form-group"><label>Email</label><input type="text" name="so_edit_shipping_email" class="form-control"></div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Terms & Remarks -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h5>Terms & Conditions</h5>
                                        <table class="table table-bordered" style="width:100%; font-size:13px;">
                                            <tbody>
                                                <tr>
                                                    <th style="width:20%;">Payment Term</th>
                                                    <td><input type="text" name="so_edit_payment_term" class="form-control"></td>
                                                </tr>
                                                <tr>
                                                    <th>Validity</th>
                                                    <td><input type="text" name="so_edit_validity" class="form-control"></td>
                                                </tr>
                                                <tr>
                                                    <th>Delivery Term</th>
                                                    <td><textarea name="so_edit_delivery_term" class="form-control estimation_edit" rows="3"></textarea></td>
                                                </tr>
                                                <tr>
                                                    <th>General Terms & Conditions</th>
                                                    <td><textarea name="so_edit_terms_condition" class="form-control estimation_edit" rows="4"></textarea></td>
                                                </tr>
                                                <tr>
                                                    <th>Remarks</th>
                                                    <td><textarea name="so_edit_remarks" class="form-control" rows="2"></textarea></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="text-right mt-3">
                                    <button type="submit" class="btn btn-success">Update Sales Order</button>
                                </div>
                            </form>


                        <?php endif; ?>
                    </div>
                    <!-- </div> -->
                    <!-- Action Buttons -->
                </div>

                <!-- Delivery Notes -->
                <div class="tab-pane fade <?= ($enquiry_data['enquiry_status'] == 9) ? 'active show' : '' ?>"
                    id="delivery_notes"
                    role="tabpanel"
                    aria-labelledby="delivery_notes-tab">
                    <form action="<?= base_url() ?>index.php/Sales/create_delivery_challan" method="post">
                        <input type="hidden" name="quotation_id" value="<?= isset($qtn_master['quotation_id']) ? $qtn_master['quotation_id'] : "" ?>">
                        <input type="hidden" name="enquiry_id" value="<?= $enquiry_data['enquiry_id'] ?>">

                        <div class="x_content well" style="overflow: auto">
                            <!-- Invoice and Delivery Date -->
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label col-md-3">Select Order</label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="sales_order_del" id="sales_order_del">
                                            <option value="">--Select sales order--</option>
                                            <?php if (!empty($sales_order_list)): ?>
                                                <?php foreach ($sales_order_list as $so):
                                                    $so_id = is_array($so) ? $so['so_id'] : $so->so_id;
                                                    $so_code = is_array($so) ? $so['so_code'] : $so->so_code;
                                                ?>
                                                    <option value="<?= $so_id ?>"><?= htmlspecialchars($so_code) ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="control-label col-md-3">Delivery Date</label>
                                    <div class="col-md-9">
                                        <input type="date" class="form-control" name="dc_date" id="dc_date">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="control-label col-md-3">Delivery Code</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="delivery_code" id="delivery_code" value="<?= isset($delivery_code) ? $delivery_code : "" ?>" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="control-label col-md-3">Delivery Mode</label>
                                    <div class="col-md-9">
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
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="control-label col-md-3">Delivered By</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="deliverd_by" id="deliverd_by">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="control-label col-md-3">Customer</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="del_customer" id="del_customer" value="<?= $enquiry_data['customer_name'] ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Products Table -->
                        <div class="x_content mt-3" style="overflow-x:auto;">
                            <table id="del_products_table" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Qty</th>
                                        </tr>
                                    </thead>
                                <tbody>
                                    <?php if (!empty($so_products)): $i = 1; ?>
                                        <?php foreach ($so_products as $item): ?>
                                            <tr>
                                                <td><?= $i++ ?></td>

                                                <td>
                                                    <input type="text" class="form-control" value="<?= isset($item['product_name']) ? $item['product_name'] : '' ?>" readonly>
                                                    <input type="hidden" name="product_id[]" value="<?= isset($item['product_id']) ? $item['product_id'] : '' ?>">
                                                    <!-- <input type="hidden" name="product_desc[]" value="<?= isset($item['prd_description']) ? $item['prd_description'] : '' ?>"> -->
                                                </td>

                                                <td>
                                                    <input type="number" name="quantity[]" class="form-control so_edit_qty"
                                                        value="<?= isset($item['quantity']) ? $item['quantity'] : 0 ?>" readonly>
                                                </td>

                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center">No items found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>


                                </tbody>
                            </table>
                        </div>

                        <!-- Shipping Address Section -->
                        <div class="x_content well mt-3">
                            <div class="row">
                                <label class="control-label col-md-2">Shipping Address</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="del_shipping_address" id="del_shipping_address">
                                </div>

                                <label class="control-label col-md-2">Shipping City</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="del_shipping_city" id="del_shipping_city">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <label class="control-label col-md-2">Contact No</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="del_shipping_contact" id="del_shipping_contact">
                                </div>

                                <label class="control-label col-md-2">Email</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="del_shipping_email" id="del_shipping_email">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <label class="control-label col-md-2">Remark</label>
                                <div class="col-md-6">
                                    <textarea name="del_remark" id="del_remark" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row mt-3">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-success">Create new Delivery Challan</button>
                            </div>
                        </div>
                    </form>



                </div>

                <!-- Invoice -->
                <div class="tab-pane fade <?= ($enquiry_data['enquiry_status'] >= 10) ? 'active show' : '' ?>"
                    id="invoice"
                    role="tabpanel"
                    aria-labelledby="invoice-tab">
                    <div class="clearfix"></div>
                    <form action="<?= base_url() ?>index.php/Sales/create_invoice" method="post">
                        <div class="x_content">
                            <div class="well" style="overflow: auto">
                                <input type="hidden" name="enquiry_id_inv" id="enquiry_id_inv" value="">
                                <input type="hidden" name="quotation_id_inv" id="quotation_id_inv" value="">
                                <input type="hidden" name="so_id_inv" id="so_id_inv" value="">
                                <input type="hidden" name="delivery_challan_id_inv" id="delivery_challan_id_inv" value="">
                                <input type="hidden" name="branch_id_inv" id="branch_id_inv" value="">
                                <input type="hidden" name="selected_bank" id="selected_bank_hidden">

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-3">Select Delivery challan</label>
                                        <div class="col-sm-9 col-xs-9">
                                            <select class="form-control" name="delivery_challan_id" id="delivery_challan_id">
                                                <option>--Select --</option>
                                                <?php if (!empty($delivery_challan_list)): ?>
                                                    <?php foreach ($delivery_challan_list as $del): ?>
                                                        <option
                                                            value="<?= $del['del_id'] ?>"
                                                            data-so_id="<?= $del['so_id'] ?>"
                                                            data-qtn_id="<?= $del['quotation_id'] ?>"
                                                            data-enq_id="<?= $del['enquiry_id'] ?>">
                                                            <?= htmlspecialchars($del['delivery_code']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label col-md-2 col-sm-3 col-xs-3">Sales order</label>
                                        <div class="col-sm-9 col-xs-9">
                                            <input type="text" class="form-control" name="sales_order_code" id="sales_order_code" readonly>
                                        </div>


                                    </div>
                                </div><!-- row -->

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-3">Invoice Code</label>
                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                            <input type="text" class="form-control" name="invoice_code" id="invoice_code" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label col-md-2 col-sm-3 col-xs-3">Invoice Date</label>
                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                            <input type="date" class="form-control" name="invoice_date" id="invoice_date">
                                        </div>
                                    </div>


                                </div><!-- row -->

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-3">Quotation Code</label>
                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                            <input type="text" class="form-control" name="inv_uotation_code" id="inv_uotation_code">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="control-label col-md-2 col-sm-3 col-xs-3">Quotation Date</label>
                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                            <input type="date" class="form-control" name="inv_quotation_date" id="inv_quotation_date">
                                        </div>
                                    </div>

                                </div><!-- row -->

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-3">Customer Name</label>
                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                            <input type="text" class="form-control" name="inv_customer_name" id="inv_customer_name">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="control-label col-md-2 col-sm-3 col-xs-3">Customer TRN </label>
                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                            <input type="text" class="form-control" name="inv_customer_trn" id="inv_customer_trn">
                                        </div>
                                    </div>
                                </div><!-- row -->

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-3">Delivery Mode</label>
                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                            <input type="text" class="form-control" name="inv_delivery_mode" id="inv_delivery_mode">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="control-label col-md-2 col-sm-3 col-xs-3">Deliverd By</label>
                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                            <input type="text" class="form-control" name="inv_deliverd_by" id="inv_deliverd_by"></select>
                                        </div>
                                    </div>
                                </div><!-- row -->
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-3">Branch</label>
                                        <div class="col-md-9 col-sm-9 col-xs-9">
                                            <input type="text" class="form-control" name="inv_branch_name" id="inv_branch_name">
                                        </div>
                                    </div>


                                </div><!-- row -->

                            </div><!-- /.well -->
                        </div><!-- /.x_content -->


                        <div class="row">
                            <div class="col-md-12" style="overflow:scroll;">
                                <div id="products_table_div" class="x_content">

                                </div>
                            </div>
                        </div><!-- row -->


                        <div class="x_content mt-3">
                            <!-- 🔹 Row 1: Subtotal, Discount, Total Before VAT -->
                            <div class="row mb-2">
                                <!-- Sub Total -->
                                <label class="control-label col-md-1 col-sm-3 col-xs-3">Sub Total</label>
                                <div class="col-md-2 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_sub_total" id="inv_sub_total" readonly>
                                </div>

                                <!-- Discount (%) -->
                                <label class="control-label col-md-1 col-sm-3 col-xs-3">Discount(%)</label>
                                <div class="col-md-1 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_discount_per" id="inv_discount_per">
                                </div>

                                <!-- Discount Amount -->
                                <div class="col-md-1 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_discount_amt" id="inv_discount_amt">
                                </div>

                                <!-- Total Before VAT -->
                                <label class="control-label col-md-1 col-sm-3 col-xs-3">Total Before VAT</label>
                                <div class="col-md-2 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_total_before_vat" id="inv_total_before_vat" readonly>
                                </div>
                            </div>

                            <!-- 🔹 Row 2: VAT and Grand Total -->
                            <div class="row">
                                <!-- VAT (%) -->
                                <label class="control-label col-md-1 col-sm-3 col-xs-3">VAT(%)</label>
                                <div class="col-md-1 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_vat_per" id="inv_vat_per">
                                </div>

                                <!-- VAT Amount -->
                                <div class="col-md-1 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_vat_amount" id="inv_vat_amount" readonly>
                                </div>

                                <!-- Grand Total -->
                                <label class="control-label col-md-1 col-sm-3 col-xs-3">Grand Total</label>
                                <div class="col-md-2 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_grand_total" id="inv_grand_total" readonly>
                                </div>
                            </div>
                        </div>




                        <div class="row mt-3">
                            <!-- Payment Mode -->
                            <label class="control-label col-md-1 col-sm-3 col-xs-3">Payment Mode</label>
                            <div class="col-md-2 col-sm-9 col-xs-9">
                                <select class="form-control" name="inv_payment_mode" id="inv_payment_mode">
                                    <option value="">-- Select --</option>
                                    <option value="cash">Cash</option>
                                    <option value="card">Card</option>
                                    <option value="bank">Bank Transfer</option>
                                    <option value="cheque">Cheque</option>
                                </select>
                            </div>

                            <!-- Advance (Percentage + Amount in same row) -->
                            <label class="control-label col-md-2 col-sm-3 col-xs-3">Payment/Advance Received</label>
                            <div class="col-md-3 col-sm-9 col-xs-9 d-flex">
                                <input type="number" class="form-control" name="inv_advance_per" id="inv_advance_per" placeholder="%" style="width: 30%; margin-right:5px;">
                                <input type="text" class="form-control" name="inv_advance_amt" id="inv_advance_amt" placeholder="0.00" style="width: 70%;">
                            </div>


                            Balance
                            <label class="control-label col-md-1 col-sm-3 col-xs-3">Balance</label>
                            <div class="col-md-2 col-sm-9 col-xs-9">
                                <input type="text" class="form-control" name="inv_balance_amt" id="inv_balance_amt" readonly>
                            </div>
                        </div>
                        <!--Bank account details--->
                        <div class="x_content well mt-4 inv_bank_details"></div>


                        <div class="x_content well mt-4">
                            <h5><strong>General Terms</strong></h5>
                            <div class="row">
                                <label class="control-label col-md-2 col-sm-3 col-xs-3">Validity</label>
                                <div class="col-md-3 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_validity" id="inv_validity">
                                </div>

                                <label class="control-label col-md-2 col-sm-3 col-xs-3">Payment Terms</label>
                                <div class="col-md-3 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_payment_terms" id="inv_payment_terms">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <label class="control-label col-md-2 col-sm-3 col-xs-3">Delivery Terms</label>
                                <div class="col-md-3 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_delivery_terms" id="inv_delivery_terms">
                                </div>

                                <label class="control-label col-md-2 col-sm-3 col-xs-3">Terms and condition</label>
                                <div class="col-md-3 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_general_terms" id="inv_general_terms">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <label class="control-label col-md-2 col-sm-3 col-xs-3">Sales person</label>
                                <div class="col-md-3 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_sales_person" id="inv_sales_person">
                                </div>

                                <label class="control-label col-md-2 col-sm-3 col-xs-3">Remarks</label>
                                <div class="col-md-3 col-sm-9 col-xs-9">
                                    <textarea class="form-control" name="inv_remarks" id="inv_remarks"></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- ========================Accounts entry=============================== -->

                        <div class="x_content well mt-4">
                            <h5><strong>Sales Invoice Account Entry</strong></h5>

                            <div class="row mt-3">
                                <!-- Debit Table -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="control-label"><strong>Debit Customer (Dr)</strong></label>
                                    <table class="table table-bordered table-hover" id="inv_dr_table">
                                        <thead>
                                            <tr>
                                                <th>Account</th>
                                                <th>Amount (AED)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="inv_dr_body">
                                            <tr id="inv_dr_addr0">
                                                <td>
                                                    <select class="form-control select2" id="inv_debtor0" name="inv_debtor[]">
                                                        <option value="">Select</option>
                                                        <?php foreach ($sundry_accounts1 as $row) { ?>
                                                            <!-- <option <?php //if ($row->account_id == 1125) echo 'selected'; 
                                                                            ?>  -->
                                                            <option <?php if ($row->account_id == $enquiry_customer_id) echo 'selected'; ?> value="<?php echo $row->account_id; ?>">
                                                                <?php echo $row->account_name; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.001" name="inv_dr_amount[]" id="inv_dr_amount0"
                                                        class="form-control debit_sum" min="0">
                                                </td>
                                            </tr>
                                            <tr id="inv_dr_addr1"></tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Credit Table -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="control-label"><strong>Credit Account (Cr)</strong></label>
                                    <table class="table table-bordered table-hover" id="inv_cr_table">
                                        <thead>
                                            <tr>
                                                <th>Account</th>
                                                <th>Amount (AED)</th>
                                                <th width="10%">
                                                    <a id="inv_cr_add_row" title="Add" class="btn btn-sm bg-orange">
                                                        <span class="fa fa-plus"></span>
                                                    </a>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="inv_cr_body">
                                            <tr id="inv_cr_addr0">
                                                <td>
                                                    <select class="form-control select2" id="inv_creditor0" name="inv_creditor[]">
                                                        <option value="">Select</option>
                                                        <?php foreach ($sundry_accounts2 as $row) { ?>
                                                            <option <?php if ($row->account_id == 1125) echo 'selected'; ?>
                                                                value="<?php echo $row->account_id; ?>">
                                                                <?php echo $row->account_name; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.001" name="inv_cr_amount[]" id="inv_cr_amount0"
                                                        class="form-control credit_sum" min="0">
                                                </td>
                                                <td>
                                                    <a title="Delete" onclick="remove_row_inv_cr(0)" class="btn btn-xs bg-orange">
                                                        <span class="fa fa-trash"></span>
                                                    </a>
                                                </td>
                                            </tr>

                                            <tr id="inv_cr_addr1">
                                                <td>
                                                    <select class="form-control select2" id="inv_creditor1" name="inv_creditor[]">
                                                        <option value="">Select</option>
                                                        <?php foreach ($sundry_accounts3 as $row) { ?>
                                                            <option <?php if ($row->account_id == 228) echo 'selected'; ?>
                                                                value="<?php echo $row->account_id; ?>">
                                                                <?php echo $row->account_name; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.001" name="inv_cr_amount[]" id="inv_cr_amount1"
                                                        class="form-control credit_sum" min="0">
                                                </td>
                                                <td>
                                                    <a title="Delete" onclick="remove_row_inv_cr(1)" class="btn btn-xs bg-orange">
                                                        <span class="fa fa-trash"></span>
                                                    </a>
                                                </td>
                                            </tr>

                                            <tr id="inv_cr_addr2">
                                                <td>
                                                    <select class="form-control select2" id="inv_creditor2" name="inv_creditor[]">
                                                        <option value="">Select</option>
                                                        <?php foreach ($sundry_accounts3 as $row) { ?>
                                                            <option <?php if ($row->account_id == 1122) echo 'selected'; ?>
                                                                value="<?php echo $row->account_id; ?>">
                                                                <?php echo $row->account_name; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.001" name="inv_cr_amount[]" id="inv_cr_amount2"
                                                        class="form-control credit_sum" min="0">
                                                </td>
                                                <td>
                                                    <a title="Delete" onclick="remove_row_inv_cr(2)" class="btn btn-xs bg-orange">
                                                        <span class="fa fa-trash"></span>
                                                    </a>
                                                </td>
                                            </tr>

                                            <tr id="inv_cr_addr3">
                                                <td>
                                                    <select class="form-control select2" id="inv_creditor3" name="inv_creditor[]">
                                                        <option value="">Select</option>
                                                        <?php foreach ($sundry_accounts3 as $row) { ?>
                                                            <option <?php if ($row->account_id == 694) echo 'selected'; ?>
                                                                value="<?php echo $row->account_id; ?>">
                                                                <?php echo $row->account_name; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.001" name="inv_cr_amount[]" id="inv_cr_amount3"
                                                        class="form-control credit_sum" min="0" value="0">
                                                </td>
                                                <td>
                                                    <a title="Delete" onclick="remove_row_inv_cr(3)" class="btn btn-xs bg-orange">
                                                        <span class="fa fa-trash"></span>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- ============================================================= -->

                        <div class="x_content well mt-4">
                            <h5><strong>Billing Details</strong></h5>
                            <div class="row">
                                <label class="control-label col-md-2 col-sm-3 col-xs-3">Billing Customer name</label>
                                <div class="col-md-3 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_billing_customer" id="inv_billing_customer">
                                </div>

                                <label class="control-label col-md-2 col-sm-3 col-xs-3">Shipping Customer name</label>
                                <div class="col-md-3 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_shipping_customer" id="inv_shipping_customer">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <label class="control-label col-md-2 col-sm-3 col-xs-3">Billing Address</label>
                                <div class="col-md-3 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_billing_address" id="inv_billing_address">
                                </div>

                                <label class="control-label col-md-2 col-sm-3 col-xs-3">Shipping Address</label>
                                <div class="col-md-3 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_shipping_address" id="inv_shipping_address">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <label class="control-label col-md-2 col-sm-3 col-xs-3">Billing Contact</label>
                                <div class="col-md-3 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_billing_contact" id="inv_billing_contact">
                                </div>

                                <label class="control-label col-md-2 col-sm-3 col-xs-3">Shipping contact</label>
                                <div class="col-md-3 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_shipping_contact" id="inv_shipping_contact">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <label class="control-label col-md-2 col-sm-3 col-xs-3">Billing email</label>
                                <div class="col-md-3 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_billing_email" id="inv_billing_email">
                                </div>

                                <label class="control-label col-md-2 col-sm-3 col-xs-3">Shipping email</label>
                                <div class="col-md-3 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_shipping_email" id="inv_shipping_email">
                                </div>
                            </div>


                            <div class="row mt-3">
                                <label class="control-label col-md-2 col-sm-3 col-xs-3">Billing City</label>
                                <div class="col-md-3 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_billing_city" id="inv_billing_city">
                                </div>

                                <label class="control-label col-md-2 col-sm-3 col-xs-3">Shipping City</label>
                                <div class="col-md-3 col-sm-9 col-xs-9">
                                    <input type="text" class="form-control" name="inv_shipping_city" id="inv_shipping_city">
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">Cancel</button>
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>


                </div><!-- /.tab-content -->

            </div><!-- /.x_content -->
        </div><!-- /.x_panel -->
    </div><!-- /.col -->
</div><!-- /.row -->





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    ['delivery_term', 'terms_condition', 'so_delivery_term',
        'so_terms_condition', 'so_edit_delivery_term', 'so_edit_terms_condition'
    ]
    .forEach(function(id) {
        var el = document.getElementById(id);
        if (el && el.tagName.toLowerCase() === 'textarea') {
            CKEDITOR.replace(id);
        }
    });
</script>
<script>
    // ------------------ 7. Auto-calculate Amount ------------------
    // Qty or Unit Price change → Update Amount
    $(document).on("input", ".qty, .unitPrice", function() {
        let row = $(this).closest("tr");
        let qty = parseFloat(row.find(".qty").val()) || 0;
        let price = parseFloat(row.find(".unitPrice").val()) || 0;
        row.find(".amount").val((qty * price).toFixed(2));
    });
    $(document).ready(function() {
        $(".product-select").select2({
            placeholder: "Select Product",
            allowClear: true
        });
    });
    // ------------------ 6. Auto-fill Unit & Price on Product Change ------------------
    function onProductChange(selectElement) {
        let $select = $(selectElement);
        let productId = $select.val();
        let row = $select.closest("tr");

        // If "Add New Product" is selected
        if (productId === "__new__") {
            $select.val(""); // reset dropdown
            $("#productMessage").hide().text("");
            $("#addProductModal").modal("show");
            $("#addProductModal").data("targetSelect", selectElement);
            return;
        }

        // Normal product selected
        if (productId) {
            let product = productsList.find(p => p.item_id == productId);
            if (product) {
                let unitSelect = row.find("select.unit-select");

                // Add unit option if not already there
                if (!unitSelect.find(`option[value='${product.item_unit}']`).length) {
                    let unitName = unitsList.find(u => u.unit_id == product.item_unit)?.unit_name || "Unknown Unit";
                    unitSelect.append(new Option(unitName, product.item_unit, true, true));
                }

                // Set unit
                unitSelect.val(product.item_unit).trigger('change'); // trigger if using select2

                // Set unit price
                row.find("input.unitPrice").val(product.unit_price);
            } else {
                row.find("select.unit-select").val("").trigger('change');
                row.find("input.unitPrice").val("");
            }
        } else {
            row.find("select.unit-select").val("").trigger('change');
            row.find("input.unitPrice").val("");
        }
    }


    $(document).on("input", "input[name^='qty']", function() {
        let row = $(this).closest("tr");
        let qty = parseFloat($(this).val()) || 0;
        let unitPrice = parseFloat(row.find("input[name^='unit_price']").val()) || 0;

        row.find("input[name^='amount']").val((qty * unitPrice).toFixed(2));
        //calculateSubtotal();
    });

    // $(document).on("input", "input[name^='qty']", function () {
    //     let row = $(this).closest("tr");
    //     let qty = parseFloat($(this).val()) || 0;
    //     let unitPrice = parseFloat(row.find("input[name^='unit_price']").val()) || 0;

    //      row.find("input[name^='amount']").val((qty * unitPrice).toFixed(2));
    //    //calculateSubtotal();
    // });

    $(document).on("input", ".qty, .unitPrice", function() {
        let row = $(this).closest("tr");
        let qty = parseFloat(row.find(".qty").val()) || 0;
        let price = parseFloat(row.find(".unitPrice").val()) || 0;
        row.find(".amount").val((qty * price).toFixed(2));

        calculateSubtotal();
    });

    function calculateSubtotal() {
        let subtotal = 0;
        $(".amount").each(function() {
            subtotal += parseFloat($(this).val()) || 0;
        });

        console.log("Subtotal:", subtotal); // debug
        $("#subtotal").val(subtotal.toFixed(2));
        calculateTotalCost();
    }
    // Collect all fields safely
    const subtotalField = document.getElementById('subtotal');
    const marginPercentField = document.getElementById('marginPercent');
    const marginAmountField = document.getElementById('marginAmount');
    const freightPercentField = document.getElementById('freightPercent');
    const freightAmountField = document.getElementById('freightAmount');
    const bankChargeField = document.getElementById('bankCharge');
    const travelExpenseField = document.getElementById('travelExpense');
    const otherExpenseField = document.getElementById('otherExpense');
    const inspectionCostField = document.getElementById('inspectionCost');
    const totalCostField = document.getElementById('totalCost');

    // Calculation function (only runs if required fields exist)
    function calculateTotalCost() {
        if (!subtotalField || !marginPercentField || !marginAmountField ||
            !freightPercentField || !freightAmountField || !bankChargeField ||
            !travelExpenseField || !otherExpenseField || !inspectionCostField ||
            !totalCostField) {
            return; // exit safely if estimation fields not loaded
        }

        const subtotal = parseFloat(subtotalField.value) || 0;

        // Margin
        const marginPercent = parseFloat(marginPercentField.value) || 0;
        const marginAmount = subtotal * marginPercent / 100;
        marginAmountField.value = marginAmount.toFixed(2);

        // Freight
        const freightPercent = parseFloat(freightPercentField.value) || 0;
        const freightAmount = subtotal * freightPercent / 100;
        freightAmountField.value = freightAmount.toFixed(2);

        // Other Expenses
        const bankCharge = parseFloat(bankChargeField.value) || 0;
        const travelExpense = parseFloat(travelExpenseField.value) || 0;
        const otherExpense = parseFloat(otherExpenseField.value) || 0;
        const inspectionCost = parseFloat(inspectionCostField.value) || 0;

        // Total
        const total = subtotal + marginAmount + freightAmount +
            bankCharge + travelExpense + otherExpense + inspectionCost;
        totalCostField.value = total.toFixed(2);
    }

    // Safely add event listeners only if field exists
    [
        subtotalField,
        marginPercentField,
        freightPercentField,
        bankChargeField,
        travelExpenseField,
        otherExpenseField,
        inspectionCostField
    ].forEach(field => {
        if (field) {
            field.addEventListener('input', calculateTotalCost);
        }
    });

    $(document).ready(function() {
        let attachmentCount = 1; // tracks number of attachments

        $('#add-attachment').click(function() {
            attachmentCount++;

            let attachmentHtml = `
            <div class="col-md-4 mb-2">
                <label>Attachment ${attachmentCount}</label>
                <input type="file" name="attachment[]" class="form-control">
            </div>
        `;

            $('#attachments-wrapper').append(attachmentHtml);
        });
    });
    $(document).on("click", ".viewSurveyBtn", function() {
        let surveyId = $(this).data("id");
        $("#surveyModalBody").html('<div class="text-center p-3"><span class="spinner-border"></span> Loading...</div>');
        $("#surveyModal").modal("show");
        $.ajax({
            url: "<?= base_url('index.php/Ajax/get_survey_details/') ?>" + surveyId,
            type: "GET",
            success: function(response) {
                $("#surveyModalBody").html(response);
            },
            error: function() {
                $("#surveyModalBody").html("<p class='text-danger'>Failed to load survey details.</p>");
            }
        });
    });

    //Autosave
    setInterval(function() {
        autosaveFormData();
    }, 50000);

    // Auto-save form data every 30 seconds
    function autosaveFormData() {
        var formData = $('#estimationForm').serialize();

        $.ajax({
            url: '<?php echo base_url("index.php/Sales/save_estimation_dummy"); ?>',
            type: 'POST',
            data: formData,
            dataType: 'json', // 👈 this makes jQuery expect JSON
            success: function(response) {
                if (response.status === 'success') {
                    console.log('Form data autosaved to database!');
                } else {
                    console.warn('Autosave failed:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error saving form data:', error);
            }
        });
    }

    function restoreProducts() {

        document.getElementById('mainMenuContainer').innerHTML = '';
        $.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>index.php/Ajax/restore_estimation_items",
            success: function(msg) {
                document.getElementById('mainMenuContainer').innerHTML = msg;
                $('.select2').select2({});
                calculateSubtotal()
            }
        });
    }

    $(document).ready(function() {
        $('#Action_estimation').on('change', function() {
            var action = $(this).val();
            var estimation_id = $('#estimation_id').val(); // hidden input with current estimation_id

            if (action === 'edit') {
                // Make fields editable
                $('.estimation_edit').prop('readonly', false);

                // Add update button if not already present
                if ($('#updateBtn').length === 0) {
                    $('#actionButtons').html(
                        '<button type="submit" id="updateBtn" class="btn btn-success">Update Estimation</button>'
                    );
                }
            } else if (action === 'approve') {
                // Confirm before approve
                if (!confirm("Are you sure you want to approve this estimation?")) {
                    return;
                }

                $.ajax({
                    url: "<?= base_url('index.php/Sales/approve_estimation') ?>",
                    type: "POST",
                    data: {
                        estimation_id: <?= isset($master['estimation_id']) ? $master['estimation_id'] : 0 ?>
                    },
                    dataType: "json",
                    success: function(res) {
                        if (res.status === "success") {
                            alert("Estimation approved successfully!");
                            location.reload();
                        } else {
                            alert("Failed: " + res.message);
                        }
                    },
                    error: function() {
                        alert("Error while approving estimation.");
                    }
                });
            } else {
                // Reset to readonly
                $('.estimation_edit').prop('readonly', true);

                // Remove update button
                $('#actionButtons').empty();
            }
        });

    });
    $(document).ready(function() {
        function calculateDiscount() {
            let estimationCost = parseFloat($("#qtn_estimation_cost").val()) || 0;
            let discountPercentage = parseFloat($("#qtn_discount_percentage").val()) || 0;
            let discountAmount = parseFloat($("#qtn_discount_amount").val()) || 0;

            // If % is entered → calculate amount
            if ($("#qtn_discount_percentage").is(":focus")) {
                discountAmount = (estimationCost * discountPercentage / 100).toFixed(2);
                $("#qtn_discount_amount").val(discountAmount);
            }

            // If amount is entered → calculate %
            if ($("#qtn_discount_amount").is(":focus")) {
                if (estimationCost > 0) {
                    discountPercentage = ((discountAmount / estimationCost) * 100).toFixed(2);
                    $("#qtn_discount_percentage").val(discountPercentage);
                }
            }

            // Update total before VAT
            let totalBeforeVat = estimationCost - discountAmount;
            $("#total_before_vat").val(totalBeforeVat.toFixed(2));
            updateTotals()
        }

        // Trigger calculation when typing in either field
        $("#qtn_discount_percentage, #qtn_discount_amount").on("input", function() {
            calculateDiscount();
        });

        function updateTotals() {
            let totalBeforeVat = parseFloat($("#total_before_vat").val()) || 0;
            let vatPercentage = parseFloat($("#vat_percentage").val()) || 0;
            let vatAmount = 0;
            let grandTotal = totalBeforeVat;

            if ($("#apply_vat").is(":checked")) {
                vatAmount = (totalBeforeVat * vatPercentage / 100).toFixed(2);
                grandTotal = (totalBeforeVat + parseFloat(vatAmount)).toFixed(2);
                $("#vat_percentage").show(); // show field if checked
            } else {
                $("#vat_percentage").hide(); // hide field if unchecked
            }

            $("#vat").val(vatAmount);
            $("#qtn_grand_total").val(grandTotal);
        }

        // Trigger calculation on checkbox change
        $("#apply_vat").on("change", function() {
            updateTotals();
        });

        // Also recalc whenever total_before_vat changes
        $("#total_before_vat").on("input", function() {
            updateTotals();
        });
    });
    $(document).ready(function() {
        $('#Action_quotation').on('change', function() {
            var action = $(this).val();

            if (action === 'edit') {
                // ✅ Enable normal input, select, textarea fields
                $('.quotation_edit, .quotation_input').prop('readonly', false).prop('disabled', false);

                // ✅ Enable CKEditor fields
                $('.quotation_edit').each(function() {
                    var editorId = $(this).attr('id');
                    if (CKEDITOR.instances[editorId]) {
                        CKEDITOR.instances[editorId].setReadOnly(false);
                    }
                });

                // ✅ Add checkbox first, then update button if not already there
                if ($('#qtn_new_revision').length === 0) {
                    $('#action_button_quotation').html(
                        '<div class="form-check d-inline-block me-3">' +
                        '<input class="form-check-input" type="checkbox" id="qtn_new_revision" name="qtn_new_revision" value="1">' +
                        '<label class="form-check-label" for="qtn_new_revision">Create New Revision</label>' +
                        '</div>' +
                        '<button type="submit" id="updateQtnBtn" class="btn btn-success">Update Quotation</button>'
                    );
                }
            } else if (action === 'approve') {
                $.ajax({
                    url: "<?= base_url('index.php/Sales/approve_quotation') ?>",
                    type: "POST",
                    data: {
                        quotation_id: "<?= isset($qtn_master['quotation_id']) ? $qtn_master['quotation_id'] : "" ?>",
                        approved_by: "<?= $this->session->userdata('user_id') ?>"
                    },
                    success: function(res) {
                        alert("Quotation approved successfully!");
                        location.reload();
                    },
                    error: function() {
                        alert("Error while approving quotation.");
                    }
                });

            } else if (action === 'resurvey') {
                $.ajax({
                    url: "<?= base_url('index.php/Sales/resurvey_from_qtn') ?>",
                    type: "POST",
                    dataType: "json", // expect JSON response
                    data: {
                        enquiry_id: "<?= isset($enquiry_data['enquiry_id']) ? $enquiry_data['enquiry_id'] : "" ?>",
                        estimation_id: "<?= isset($qtn_master['estimation_id']) ? $qtn_master['estimation_id'] : "" ?>",
                        quotation_id: "<?= isset($qtn_master['quotation_id']) ? $qtn_master['quotation_id'] : "" ?>",
                        created_by: "<?= $this->session->userdata('user_id') ?>"
                    },
                    success: function(res) {
                        if (res.status === "success") {
                            alert("Rescheduled Survey successfully!");
                            location.reload(true);
                        } else {
                            alert("Something went wrong while rescheduling survey.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        alert("Error while rescheduling survey. Please try again.");
                    }
                });


            } else {
                // ❌ Lock normal fields again
                $('.quotation_edit, .quotation_input').prop('readonly', true).prop('disabled', true);

                // ❌ Lock CKEditor fields again
                $('.quotation_edit').each(function() {
                    var editorId = $(this).attr('id');
                    if (CKEDITOR.instances[editorId]) {
                        CKEDITOR.instances[editorId].setReadOnly(true);
                    }
                });

                // ❌ Remove checkbox + button
                $('#action_button_quotation').empty();
            }
        });
    });



    // Initial calculation on page load
    // Qtn_calculateTotals();
    //});
</script>

<script>
    function Qtn_calculateTotals() {
        //alert("here");
        let subtotal = 0;

        // Loop through each product row
        $(".qtn_productTable tbody tr").each(function() {
            let qty = parseFloat($(this).find(".qtn_qty").val()) || 0;
            let price = parseFloat($(this).find(".qtn_unitPrice").val()) || 0;
            let amount = qty * price;

            // Update amount field
            $(this).find(".qtn_amount").val(amount.toFixed(2));

            // Line discount (amount only)
            let lineDiscountAmount = parseFloat($(this).find(".qtn_discount_amount").val()) || 0;
            //alert(lineDiscountAmount);
            if (lineDiscountAmount > amount) lineDiscountAmount = amount;

            let taxableAmount = amount - lineDiscountAmount;

            // Update fields
            //$(this).find(".qtn_discount_amount").val(lineDiscountAmount.toFixed(2));
            $(this).find(".qtn_taxable_amount").val(taxableAmount.toFixed(2));
            //alert(taxableAmount);
            // Add to subtotal
            subtotal += taxableAmount;
        });

        // Update Subtotal
        $(".qtn_sub_total").val(subtotal.toFixed(2));


        let addDiscountPercent = parseFloat($("#qtn_add_discount_percentage").val()) || 0;
        // Calculate amount
        let addDiscountAmount = (subtotal * addDiscountPercent) / 100;

        // Total after overall discount
        let totalAfterDiscount = subtotal - addDiscountAmount;

        // Update overall discount amount field
        $("#qtn_add_discount_amount").val(addDiscountAmount.toFixed(2));


        // VAT
        let applyVat = $("#qtn_apply_vat").is(":checked");
        let vatPercent = parseFloat($("#qtn_vat_percentage").val()) || 0;
        let vatAmount = applyVat ? totalAfterDiscount * (vatPercent / 100) : 0;
        $("#qtn_vat_amount").val(vatAmount.toFixed(2));

        // Total before VAT
        $("#total_before_vat").val(totalAfterDiscount.toFixed(2));

        // Grand Total
        let grandTotal = totalAfterDiscount + vatAmount;
        $("#qtn_grand_total").val(grandTotal.toFixed(2));
    }

    // 🔹 Event Bindings
    $(document).on("input", ".qtn_qty, .qtn_unitPrice, .qtn_discount_amount, #qtn_add_discount_percentage, #vat_percentage", Qtn_calculateTotals);
    $(document).on("change", "#qtn_apply_vat", Qtn_calculateTotals);

    // Initial Load
    $(document).ready(Qtn_calculateTotals);
</script>
<script>
    function Qtn_calculateTotals() {
        //alert("here");
        let subtotal = 0;

        // Loop through each product row
        $(".qtn_productTable tbody tr").each(function() {
            let qty = parseFloat($(this).find(".qtn_qty").val()) || 0;
            let price = parseFloat($(this).find(".qtn_unitPrice").val()) || 0;
            let amount = qty * price;

            // Update amount field
            $(this).find(".qtn_amount").val(amount.toFixed(2));

            // Line discount (amount only)
            let lineDiscountAmount = parseFloat($(this).find(".qtn_discount_amount").val()) || 0;
            //alert(lineDiscountAmount);
            if (lineDiscountAmount > amount) lineDiscountAmount = amount;

            let taxableAmount = amount - lineDiscountAmount;

            // Update fields
            //$(this).find(".qtn_discount_amount").val(lineDiscountAmount.toFixed(2));
            $(this).find(".qtn_taxable_amount").val(taxableAmount.toFixed(2));
            //alert(taxableAmount);
            // Add to subtotal
            subtotal += taxableAmount;
        });

        // Update Subtotal
        $(".qtn_sub_total").val(subtotal.toFixed(2));


        let addDiscountPercent = parseFloat($("#qtn_add_discount_percentage").val()) || 0;
        // Calculate amount
        let addDiscountAmount = (subtotal * addDiscountPercent) / 100;

        // Total after overall discount
        let totalAfterDiscount = subtotal - addDiscountAmount;

        // Update overall discount amount field
        $("#qtn_add_discount_amount").val(addDiscountAmount.toFixed(2));


        // VAT
        let applyVat = $("#qtn_apply_vat").is(":checked");
        let vatPercent = parseFloat($("#qtn_vat_percentage").val()) || 0;
        let vatAmount = applyVat ? totalAfterDiscount * (vatPercent / 100) : 0;
        $("#qtn_vat_amount").val(vatAmount.toFixed(2));

        // Total before VAT
        $("#total_before_vat").val(totalAfterDiscount.toFixed(2));

        // Grand Total
        let grandTotal = totalAfterDiscount + vatAmount;
        $("#qtn_grand_total").val(grandTotal.toFixed(2));
    }

    // 🔹 Event Bindings
    $(document).on("input", ".qtn_qty, .qtn_unitPrice, .qtn_discount_amount, #qtn_add_discount_percentage, #vat_percentage", Qtn_calculateTotals);
    $(document).on("change", "#qtn_apply_vat", Qtn_calculateTotals);

    // Initial Load
    $(document).ready(Qtn_calculateTotals);
</script>
<script>
    function recalculateTotals() {
        let subtotal = 0;
        let totalDiscount = 0;
        let totalTaxable = 0;

        $("table.table tbody tr").each(function() {
            let qty = parseFloat($(this).find(".so_qty").val()) || 0;
            let unitp = parseFloat($(this).find(".so_unitp").val()) || 0;
            let discount = parseFloat($(this).find(".so_discount").val()) || 0;
            // Recalculate row amounts
            let amount = qty * unitp;
            $(this).find(".so_amount").val(amount.toFixed(2));
            // Taxable amount = amount - discount
            let taxable = amount - discount;

            $(this).find(".so_taxable").val(taxable.toFixed(2));

            subtotal += taxable;
            totalDiscount += discount;
            totalTaxable += taxable;
        });


        // Update subtotal
        $(".so_subtotal").val(subtotal.toFixed(2));

        // Discount
        let discountPercentage = parseFloat($("input[name='so_add_discount_percentage']").val()) || 0;
        let discountAmount = subtotal * (discountPercentage / 100);
        let totalbforevat = subtotal - discountAmount;

        $(".so_discount_amount").val(discountAmount.toFixed(2));
        $(".so_totalbefore_vat_amount").val(totalbforevat.toFixed(2));
        // VAT
        let vatAmount = 0;
        let vatPercentage = parseFloat($(".so_vat_percentage").val()) || 0;
        if (vatPercentage > 0) {
            vatAmount = (totalbforevat) * (vatPercentage / 100);
            $(".so_vat_amount").val(vatAmount.toFixed(2));
        }

        // Grand total
        let grandTotal = subtotal - discountAmount + vatAmount;
        $(".so_grand_total").val(grandTotal.toFixed(2));
    }

    // Quantity or unit price change
    $(document).on("input", ".so_qty, .so_unitp,.so_discount", function() {
        let row = $(this).closest("tr");
        let qtyField = row.find(".so_qty");
        let maxQty = parseFloat(qtyField.data("maxqty")) || 0;
        let qty = parseFloat(qtyField.val()) || 0;

        if (qty > maxQty) {
            alert("Entered quantity cannot exceed available qty (" + maxQty + ")");
            qtyField.val(maxQty);
        }

        recalculateTotals();
    });

    // Delete row and recalc
    function deleteRow(btn) {
        if (confirm("Are you sure you want to remove this product from the list?")) {
            $(btn).closest("tr").remove();
            recalculateTotals();
        }
    }

    // Initial calculation on page load

    // Attach event listener to quantity and unit price fields
    document.addEventListener("input", function(e) {
        if (e.target.classList.contains("so_qty") || e.target.classList.contains("so_unitp")) {
            let row = e.target.closest("tr");

            let qty = parseFloat(row.querySelector(".so_qty").value) || 0;
            let qtyField = row.querySelector(".so_qty");
            let maxQty = parseFloat(qtyField.dataset.maxqty) || 0;
            let unitp = parseFloat(row.querySelector(".so_unitp").value) || 0;
            let amountField = row.querySelector(".so_amount");

            if (qty > maxQty) {
                alert("Entered quantity cannot exceed available qty (" + maxQty + ")");
                qty = maxQty;
                qtyField.value = maxQty; // reset to max
            }

            let amount = qty * unitp;
            amountField.value = amount.toFixed(2);
        }
    });

    $(document).ready(function() {
        recalculateTotals();
    });

    $(document).ready(function() {
        $("form").on("submit", function() {
            $("#so_delivery_term").val($("#delivery-editor").html());
            $("#so_terms_condition").val($("#terms-editor").html());
        });

        $('#copyAddress').on('ifChecked', function() {
            $("#shipping_name").val($("#billing_name").val());
            $("#shipping_address").val($("#billing_address").val());
            $("#shipping_city").val($("#billing_city").val());
            $("#shipping_phone").val($("#billing_phone").val());
            $("#shipping_email").val($("#billing_email").val());
        });

        $('#copyAddress').on('ifUnchecked', function() {
            $("#shipping_name, #shipping_address, #shipping_city, #shipping_phone, #shipping_email").val('');

        });
    });
</script>
<script>
    $(".sales_order").on("change", function() {
        let so_id = $(this).val();
        let enq_id = <?= $enquiry_data['enquiry_id'] ?>;
        let qtn_id = <?= $qtn_master['quotation_id'] ?>;

        $('#print_so').html('<a href="<?= base_url("index.php/Document_controller/print_sales_order/") ?>' + so_id + '/' + enq_id + '" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>');

        $.ajax({
            url: "<?= base_url('index.php/Sales/get_sales_order_partial') ?>",
            type: "POST",
            data: {
                so_id: so_id,
                enq_id: enq_id,
                qtn_id: qtn_id
            },
            dataType: "json",
            success: function(res) {
                console.log("Full response:", res);

                // Fill SO fields
                $('input[name="so_id_upd"]').val(so_id);
                $('#so_products_table').html(res.products_html);
                $('input[name="so_edit_code"]').val(res.so_master[0].so_code);
                $('.so_edit_date').val(res.so_date);
                $('.so_edit_subtotal').val(res.so_master[0].sub_total);
                $('input[name="so_edit_add_discount_amount"]').val(res.so_master[0].discount_amount);
                $('.so_edit_add_discount_percentage').val(res.so_master[0].discount_percentage);
                $('.so_edit_add_discount_percentage').text(res.so_master[0].discount_percentage);
                $('input[name="so_edit_totalbefore_vat_amount"]').val(res.so_master[0].total_before_vat);
                $('.so_edit_vat_percentage').val(res.so_master[0].vat_percentage);
                $('.so_edit_vat_percentage').text(res.so_master[0].vat_percentage);
                $('input[name="so_edit_vat_amount"]').val(res.so_master[0].vat_amount);
                $('input[name="so_edit_grand_total"]').val(res.so_master[0].grand_total);

                // Address fields
                $('input[name="so_edit_billing_name"]').val(res.so_address.billing_customer_name);
                $('input[name="so_edit_billing_address"]').val(res.so_address.billing_customer_address);
                $('input[name="so_edit_billing_city"]').val(res.so_address.billing_emirates);
                $('input[name="so_edit_billing_phone"]').val(res.so_address.billing_contact);
                $('input[name="so_edit_billing_email"]').val(res.so_address.billing_email);

                $('input[name="so_edit_shipping_name"]').val(res.so_address.shipping_customer);
                $('input[name="so_edit_shipping_address"]').val(res.so_address.shipping_address);
                $('input[name="so_edit_shipping_city"]').val(res.so_address.shipping_emirate);
                $('input[name="so_edit_shipping_phone"]').val(res.so_address.shipping_contact);
                $('input[name="so_edit_shipping_email"]').val(res.so_address.shipping_email);

                // Terms & Remarks
                $('input[name="so_edit_payment_term"]').val(res.so_master[0].payment_term);
                $('input[name="so_edit_validity"]').val(res.so_master[0].validity);
                $('textarea[name="so_edit_remarks"]').val(res.so_master[0].remarks);

                if (CKEDITOR.instances['so_edit_delivery_term']) {
                    CKEDITOR.instances['so_edit_delivery_term'].setData(res.so_master[0].delivery_term);
                }

                if (CKEDITOR.instances['so_edit_terms_condition']) {
                    CKEDITOR.instances['so_edit_terms_condition'].setData(res.so_master[0].terms_and_condition);
                }

                // ✅ NEW SECTION — Control Delivery Note Button
                if (res.can_create_dc === false) {
                    $("#create_delivery_note").hide();
                    $("#create_new_sales_order").hide();
                } else {
                    $("#create_delivery_note").show();
                    $("#create_new_sales_order").show();

                }
            }
        });
    });


    function recalculateTotalsEdit() {
        let subtotal = 0;
        let totalDiscount = 0;
        let totalTaxable = 0;

        $("table.table tbody tr").each(function() {
            let qty = parseFloat($(this).find(".so_edit_qty").val()) || 0;
            let unitp = parseFloat($(this).find(".so_edit_unitp").val()) || 0;
            let discount = parseFloat($(this).find(".so_edit_discount").val()) || 0;

            // Recalculate row amount
            let amount = qty * unitp;
            $(this).find(".so_edit_amount").val(amount.toFixed(2));

            // Taxable amount = amount - discount
            let taxable = amount - discount;
            $(this).find(".so_edit_taxable").val(taxable.toFixed(2));

            subtotal += taxable;
            totalDiscount += discount;
            totalTaxable += taxable;
        });

        // Update subtotal
        $(".so_edit_subtotal").val(subtotal.toFixed(2));

        // Additional discount (percentage)
        let discountPercentage = parseFloat($("input[name='so_edit_add_discount_percentage']").val()) || 0;
        let discountAmount = subtotal * (discountPercentage / 100);
        let totalBeforeVat = subtotal - discountAmount;

        $(".so_edit_discount_amount").val(discountAmount.toFixed(2));
        $(".so_edit_totalbefore_vat_amount").val(totalBeforeVat.toFixed(2));

        // VAT
        let vatAmount = 0;
        let vatPercentage = parseFloat($(".so_edit_vat_percentage").val()) || 0;
        if (vatPercentage > 0) {
            vatAmount = totalBeforeVat * (vatPercentage / 100);
            $(".so_edit_vat_amount").val(vatAmount.toFixed(2));
        }

        // Grand total
        let grandTotal = subtotal - discountAmount + vatAmount;
        $(".so_edit_grand_total").val(grandTotal.toFixed(2));
    }

    // Quantity or unit price change
    $(document).on("input", ".so_edit_qty, .so_edit_unitp, .so_edit_discount", function() {
        let row = $(this).closest("tr");
        let qtyField = row.find(".so_edit_qty");
        let maxQty = parseFloat(qtyField.data("maxqty")) || 0;
        let qty = parseFloat(qtyField.val()) || 0;

        if (qty > maxQty) {
            alert("Entered quantity cannot exceed available quantity (" + maxQty + ")");
            qtyField.val(maxQty);
        }

        recalculateTotalsEdit();
    });

    // Delete row and recalc
    function deleteRowEdit(btn) {
        if (confirm("Are you sure you want to remove this product from the list?")) {
            $(btn).closest("tr").remove();
            recalculateTotalsEdit();
        }
    }



    // Attach event listener (vanilla JS fallback)
    document.addEventListener("input", function(e) {
        if (e.target.classList.contains("so_edit_qty") || e.target.classList.contains("so_edit_unitp")) {
            let row = e.target.closest("tr");

            let qty = parseFloat(row.querySelector(".so_edit_qty").value) || 0;
            let qtyField = row.querySelector(".so_edit_qty");
            let maxQty = parseFloat(qtyField.dataset.maxqty) || 0;
            let unitp = parseFloat(row.querySelector(".so_edit_unitp").value) || 0;
            let amountField = row.querySelector(".so_edit_amount");

            if (qty > maxQty) {
                alert("Entered quantity cannot exceed available qty (" + maxQty + ")");
                qty = maxQty;
                qtyField.value = maxQty; // reset to max
            }

            let amount = qty * unitp;
            amountField.value = amount.toFixed(2);
        }
    });

    $('#create_delivery_note').on('click', function() {
        var enquiry_id = <?= $enquiry_data['enquiry_id'] ?>;

        $.ajax({
            url: '<?= base_url("index.php/Sales/update_delivery_notes_status") ?>',
            type: 'POST',
            data: {
                enquiry_id: enquiry_id
            },
            success: function(response) {
                // Optionally, redirect to create delivery note page
                location.reload();
            },
            error: function() {
                alert('Failed to update enquiry status.');
            }
        });
    });

    function update_enquiry_for_sales_order(enquiry_id) {
        if (!enquiry_id) {
            alert("Invalid enquiry ID");
            return;
        }

        $.ajax({
            url: "<?= base_url('index.php/Sales/update_enquiry_for_so') ?>",
            type: "POST",
            data: {
                enquiry_id: enquiry_id
            },
            dataType: "json",
            success: function(res) {
                if (res.status === "success") {
                    location.reload(); // ✅ reload page
                } else {
                    alert("Failed to update enquiry.");
                }
            },
            error: function() {
                alert("Error: Could not update enquiry.");
            }
        });
    }
</script>
<script>
    $("#sales_order_del").on("change", function() {
        let so_id = $(this).val();
        let enq_id = <?= $enquiry_data['enquiry_id'] ?>;
        let qtn_id = <?= isset($qtn_master['quotation_id']) ? $qtn_master['quotation_id'] : 0 ?>;

        $.ajax({
            url: "<?= base_url('index.php/Sales/get_sales_order_partial_del') ?>",
            type: "POST",
            data: {
                so_id: so_id,
                enq_id: enq_id,
                qtn_id: qtn_id
            },
            dataType: "json",
            success: function(res) {
                console.log("Full response:", res);

                if (!res || res.error) {
                    alert("No data found for this Sales Order.");
                    return;
                }
                // alert(res.so_master.remark);
                if (res.source === "sales_order") {
                    $('#del_products_table').html(res.products_html);

                    $('input[name="del_shipping_address"]').val(res.so_address.shipping_address || '');
                    $('input[name="del_shipping_city"]').val(res.so_address.shipping_emirate || '');
                    $('input[name="del_shipping_contact"]').val(res.so_address.shipping_contact || '');
                    $('input[name="del_shipping_email"]').val(res.so_address.shipping_email || '');

                    $('.submit_div').show();

                } else if (res.source === "delivery") {

                    $('#print_del').html('<a href="<?= base_url("index.php/Document_controller/print_delivery_challan/") ?>' + res.so_master.del_id + '/' + enq_id + '" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>');

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
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                alert("Failed to fetch Sales Order / Delivery details.");
            }
        });
    });
</script>
<script>
    $('#delivery_challan_id').change(function() {
        var del_id = $(this).val();
        var so_id = $(this).find(':selected').data('so_id');
        var qtn_id = $(this).find(':selected').data('qtn_id');
        var enq_id = $(this).find(':selected').data('enq_id');

        if (del_id) {
            $.ajax({
                url: '<?= base_url("index.php/sales/get_delivery_challan_invoice") ?>',
                type: 'POST',
                data: {
                    del_id: del_id,
                    so_id: so_id,
                    qtn_id: qtn_id,
                    enq_id: enq_id
                },
                dataType: 'json',
                success: function(res) {
                    let subTotal = parseFloat(res.so_master.sub_total) || 0;
                    let discountAmt = parseFloat(res.so_master.discount_amount) || 0;
                    let totalBeforeVat = subTotal - discountAmt;

                    // Main invoice details
                    //alert(res.enquiry_master.branch_id);
                    $('#delivery_challan_id_inv').val(del_id);
                    $('#enquiry_id_inv').val(enq_id);
                    $('#quotation_id_inv').val(qtn_id);
                    $('#branch_id_inv').val(res.enquiry_master.branch_id);

                    $('#so_id_inv').val(so_id);
                    $('#products_table_div').html(res.products_html);
                    $('#inv_remarks').html(res.del_master.remark);
                    $('.inv_bank_details').html(res.bank_table);
                    $('#sales_order_code').val(res.so_master.so_code || '');
                    $('#invoice_code').val(res.invoice_code || '');

                    $('#inv_sub_total').val(subTotal.toFixed(2));
                    //$('#inv_sub_total').val(res.so_master.sub_total || '');

                    $('#inv_discount_per').val(res.so_master.discount_percentage || '');
                    // $('#inv_discount_amt').val(res.so_master.discount_amount || '');
                    $("#inv_discount_amt").val(discountAmt.toFixed(2));

                    $("#inv_total_before_vat").val(totalBeforeVat.toFixed(2));
                    $('#payment_terms').val(res.so_master.total_before_vat || '');
                    $('#inv_vat_per').val(res.so_master.vat_percentage || '');
                    $('#inv_vat_amount').val(res.so_master.vat_amount || '');
                    $('#inv_grand_total').val(res.so_master.grand_total || '');
                    $('#remarks').val(res.so_master.remarks || '');
                    $('#inv_general_terms').val(res.so_master.terms_and_condition || '');
                    $('#inv_delivery_terms').val(res.so_master.delivery_term || '');
                    $('#inv_payment_terms').val(res.so_master.payment_term || '');
                    $('#inv_validity').val(res.so_master.validity || '');

                    ///Accounts data


                    $("#inv_dr_amount0").val(res.so_master.grand_total || '');
                    $("#inv_cr_amount0").val(res.so_master.sub_total || '');
                    $("#inv_cr_amount1").val(res.so_master.vat_amount || '');
                    $("#inv_cr_amount2").val(res.so_master.discount_amount || '');
                    //-----------

                    $('#inv_billing_customer').val(res.so_address.billing_customer_name || '');
                    $('#inv_billing_address').val(res.so_address.billing_customer_address || '');
                    $('#inv_billing_city').val(res.so_address.billing_emirates || '');
                    $('#inv_billing_contact').val(res.so_address.billing_contact || '');
                    $('#inv_billing_email').val(res.so_address.billing_email || '');

                    $('#inv_shipping_customer').val(res.so_address.shipping_customer || '');
                    $('#inv_shipping_address').val(res.so_address.shipping_address || '');
                    $('#inv_shipping_city').val(res.so_address.shipping_emirate || '');
                    $('#inv_shipping_contact').val(res.so_address.shipping_contact || '');
                    $('#inv_shipping_email').val(res.so_address.shipping_email || '');


                    $('#customer_name').val(res.enquiry_master.enquiry_code || '');
                    $('#inv_customer_name').val(res.enquiry_master.customer_name || '');
                    $('#inv_branch_name').val(res.enquiry_master.branch_name || '');
                    $('#inv_customer_trn').val(res.enquiry_master.customer_TR_no || '');

                    $('#inv_delivery_mode').val(res.del_master.delivery_mode || '');
                    $('#inv_deliverd_by').val(res.del_master.deliverd_by || '');
                    $('#inv_sales_person').val(res.enquiry_master.user_name || '');
                    $('#inv_uotation_code').val(res.qtn_master.quotation_code || '');
                    $('#inv_quotation_date').val(res.qtn_master.quotation_date || '');

                }
            });
        }
    });
    $(document).ready(function() {
        // When percentage changes → update amount & balance
        $("#inv_advance_per").on("input", function() {
            let grandTotal = parseFloat($("#inv_grand_total").val()) || 0;
            let advancePer = parseFloat($(this).val()) || 0;

            // Cap percentage to 100
            if (advancePer > 100) {
                advancePer = 100;
                $(this).val(100);
            }

            let advanceAmt = (grandTotal * advancePer / 100).toFixed(2);
            $("#inv_advance_amt").val(advanceAmt);

            // Balance = Grand total - Advance
            let balanceAmt = (grandTotal - advanceAmt).toFixed(2);
            $("#inv_balance_amt").val(balanceAmt);
        });

        // When advance amount changes → update percentage & balance
        $("#inv_advance_amt").on("input", function() {
            let grandTotal = parseFloat($("#inv_grand_total").val()) || 0;
            let advanceAmt = parseFloat($(this).val()) || 0;

            if (advanceAmt > grandTotal) {
                advanceAmt = grandTotal;
                $(this).val(advanceAmt.toFixed(2));
            }

            let advancePer = ((advanceAmt / grandTotal) * 100).toFixed(2);
            $("#inv_advance_per").val(advancePer);

            // Balance
            let balanceAmt = (grandTotal - advanceAmt).toFixed(2);
            $("#inv_balance_amt").val(balanceAmt);
        });
    });
    // Recalculate totals dynamically
    function calculateTotals() {
        let subTotal = parseFloat($("#inv_sub_total").val()) || 0;
        let discountPer = parseFloat($("#inv_discount_per").val()) || 0;
        let discountAmt = parseFloat($("#inv_discount_amt").val()) || 0;

        // If percentage entered, calculate discount amount
        if (discountPer > 0) {
            discountAmt = (subTotal * discountPer) / 100;
            $("#inv_discount_amt").val(discountAmt.toFixed(2));
            //Accounts discount amount
            $("#inv_cr_amount2").val(discountAmt.toFixed(2));
        }

        // Calculate total before VAT
        let totalBeforeVat = subTotal - discountAmt;
        $("#inv_total_before_vat").val(totalBeforeVat.toFixed(2));

        // VAT calculation
        let vatPer = parseFloat($("#inv_vat_per").val()) || 0;
        let vatAmt = (totalBeforeVat * vatPer) / 100;
        $("#inv_vat_amount").val(vatAmt.toFixed(2));
        $("#inv_cr_amount1").val(vatAmt.toFixed(2));

        // Grand total
        let grandTotal = totalBeforeVat + vatAmt;
        $("#inv_grand_total").val(grandTotal.toFixed(2));
        $("#inv_dr_amount0").val(grandTotal.toFixed(2));



    }

    // Trigger recalculation on input
    $("#inv_discount_per, #inv_discount_amt, #inv_vat_per").on("input", calculateTotals);
    $(document).on('change', "input[name='selected_bank']", function() {
        $('#selected_bank_hidden').val($(this).val());
    });
</script>