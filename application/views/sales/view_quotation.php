<style>
    label,
    h4 {
        color: black;
        font-weight: bold;
    }

    .view-label {
        font-weight: bold;
        color: #000;
    }

    .quotation-box {
        border: 1px solid #ddd;
        padding: 15px;
    }
</style>

<div class="text-end mb-3">
    <button type="button"
        class="btn btn-primary"
        onclick="window.open('<?= base_url('index.php/Sales/print_quotation/' . $quotation->qtn_id . '/' . $quotation->enquiry_id) ?>', '_blank');">
        🖨 Print
    </button>


    <a href="<?= base_url('index.php/Sales/edit_quotation/' . $quotation->qtn_id); ?>"
        class="btn btn-primary btn-xs"
        data-toggle="tooltip" title="Edit">
        <i class="fa fa-edit"></i>
    </a>
</div>

<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">

        <div class="x_panel">

           

            <div class="x_content">


                <!-- Header Details -->

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 view-label">
                                Quotation Code:
                            </label>

                            <div class="col-sm-8">
                                <?= $quotation->quotation_code ?>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">

                        <div class="form-group row">

                            <label class="col-sm-4 view-label">
                                Quotation Date:
                            </label>

                            <div class="col-sm-8">
                                <?= date('d-m-Y',strtotime($quotation->quotation_date)) ?>
                            </div>

                        </div>

                    </div>

                </div>



                <div class="row">


                    <div class="col-md-6">

                        <div class="form-group row">

                            <label class="col-sm-4 view-label">
                                Enquiry Code:
                            </label>

                            <div class="col-sm-8">
                                <?= $quotation->enquiry_code ?>
                            </div>

                        </div>

                    </div>



                    <div class="col-md-6">

                        <div class="form-group row">

                            <label class="col-sm-4 view-label">
                                Branch:
                            </label>

                            <div class="col-sm-8">
                                <?= $quotation->branch_name ?>
                            </div>

                        </div>

                    </div>


                </div>



                <div class="row">


                    <div class="col-md-6">

                        <div class="form-group row">

                            <label class="col-sm-4 view-label">
                                Project:
                            </label>

                            <div class="col-sm-8">
                                <?= $quotation->project_name ?>
                            </div>

                        </div>

                    </div>



                    <div class="col-md-6">

                        <div class="form-group row">

                            <label class="col-sm-4 view-label">
                                Customer:
                            </label>

                            <div class="col-sm-8">
                                <?= $quotation->customer_name ?>
                            </div>

                        </div>

                    </div>


                </div>



                <hr>



                <!-- Items -->

                <h4>Items</h4>


                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th width="50">#</th>
                            <th>Item</th>
                            <th width="120">Qty</th>
                            <th width="150">Price</th>
                            <th width="150">Amount</th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php 
                    $i=1;
                    foreach($cart_items as $item)
                    {
                    ?>

                        <tr>

                            <td>
                                <?= $i++ ?>
                            </td>


                            <td>
                                <?= $item->product_name ?>
                            </td>


                            <td>
                                <?= $item->qty ?>
                            </td>


                            <td>
                                <?= number_format($item->price,2) ?>
                            </td>


                            <td>
                                <?= number_format($item->amount,2) ?>
                            </td>


                        </tr>


                    <?php } ?>


                    </tbody>


                </table>



                <br>



                <!-- Summary -->


                <table class="table table-bordered">

                    <tr>

                        <td width="70%" align="right">
                            <b>Gross</b>
                        </td>

                        <td>
                            <?= number_format($quotation->sub_total,2) ?>
                        </td>

                    </tr>



                    <tr>

                        <td align="right">
                            <b>Discount</b>
                        </td>

                        <td>

                            <?= number_format($quotation->discount_amount,2) ?>

                        </td>

                    </tr>



                    <tr>

                        <td align="right">

                            <b>VAT</b>

                        </td>


                        <td>

                            <?= number_format($quotation->vat_amount,2) ?>

                        </td>


                    </tr>



                    <tr>

                        <td align="right">

                            <b>Net Total</b>

                        </td>


                        <td>

                            <b>
                            <?= number_format($quotation->grand_total,2) ?>
                            </b>

                        </td>


                    </tr>


                </table>




                <hr>



                <!-- Terms -->


                <div class="row">


                    <div class="col-md-6">

                        <label>
                            Payment Term
                        </label>

                        <div class="quotation-box">

                            <?= $quotation->payment_term ?>

                        </div>


                    </div>



                    <div class="col-md-6">

                        <label>
                            Validity
                        </label>

                        <div class="quotation-box">

                            <?= $quotation->validity ?>

                        </div>


                    </div>


                </div>



                <br>



                <div class="row">


                    <div class="col-md-6">

                        <label>
                            Warranty
                        </label>


                        <div class="quotation-box">

                            <?= $quotation->warranty ?>

                        </div>


                    </div>



                    <div class="col-md-6">


                        <label>
                            Warranty Description
                        </label>


                        <div class="quotation-box">

                            <?= $quotation->warranty_description ?>

                        </div>


                    </div>


                </div>



                <br>



                <div class="row">


                    <div class="col-md-6">

                        <label>
                            Delivery Term
                        </label>


                        <div class="quotation-box">

                            <?= $quotation->delivery_term ?>

                        </div>


                    </div>



                    <div class="col-md-6">


                        <label>
                            Terms & Conditions
                        </label>


                        <div class="quotation-box">

                            <?= $quotation->terms_condition ?>

                        </div>


                    </div>


                </div>



                <br>



                <div class="row">


                    <div class="col-md-12">

                        <label>
                            Notes
                        </label>


                        <div class="quotation-box">

                            <?= $quotation->notes ?>

                        </div>


                    </div>


                </div>



                <br>


                <div class="text-center">

                    <a href="<?= base_url('index.php/Sales/list_quotations') ?>"
                       class="btn btn-secondary">

                        Back

                    </a>


                </div>



            </div>

        </div>

    </div>
</div>