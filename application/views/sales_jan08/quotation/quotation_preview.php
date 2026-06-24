<?php if (isset($estimation)): $i = 0; ?>
    <?php foreach ($estimation as $main): ?>
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
                        placeholder="Enter Details"><?= $main['main_details'] ?></textarea>
                </div>
            </div>
        </div>

        <?php $j = 0;
        foreach ($main['sub_headings'] as $sub): ?>
            <div class="border p-2 mb-2 subHeadingContainer" data-main="<?= $i ?>" data-sub="<?= $j ?>">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <input type="text"
                        name="sub_heading[<?= $i ?>][<?= $j ?>]"
                        value="<?= $sub['sub_heading'] ?>"
                        class="form-control form-control-sm w-75"
                        placeholder="Enter Sub Heading">
                </div>

                <table class="table table-bordered qtn_productTable mb-0" cellspacing="0" width="100%">
                    <thead>
                        <tr style="background-color:#f8f9fa;">
                            <th  style="width:220px;">Product</th>
                            <th style="width:100px;">Unit</th>
                            <th style="width:100px;">Qty</th>
                            <th style="width:120px;">Unit Price</th>
                            <th style="width:120px;">Amount</th>
                            <th style="width:60px;">Discount</th>
                            <th style="width:60px;">Taxable Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $k = 0;
                        foreach ($sub['products'] as $prod): ?>
                            <tr>
                                <td>
                                    <select name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][product_id]"
                                        class="form-control form-control-sm estimation_edit select2">
                                        <option value="">-- Select Product --</option>
                                        <?php foreach ($all_products as $p): ?>
                                            <option value="<?= $p->item_id ?>"
                                                <?= ($p->item_id == $prod['product_id']) ? 'selected' : '' ?>>
                                                <?= $p->item_name ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <br><br>
                                    <textarea name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][product_description]"
                                        class="form-control form-control-sm estimation_edit"><?= $prod['product_description'] ?></textarea>
                                </td>

                                <td>
                                    <select name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][unit]"
                                        class="form-control estimation_edit">
                                        <option value="">-- Select Unit --</option>
                                        <?php foreach ($active_units as $unit): ?>
                                            <option value="<?= $unit->unit_id ?>"
                                                <?= ($prod['unit_id'] == $unit->unit_id) ? 'selected' : '' ?>>
                                                <?= $unit->unit_name ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>

                                <td>
                                    <input type="number"
                                        name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][quantity]"
                                        value="<?= $prod['quantity'] ?>"
                                        class="form-control quotation_edit qtn_qty"
                                        readonly>
                                </td>

                                <td>
                                    <input type="number" step="0.01"
                                        name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][unit_price]"
                                        value="<?= $prod['unit_price'] ?>"
                                        class="form-control quotation_edit qtn_unitPrice"
                                        readonly>
                                </td>

                                <td>
                                    <input type="number" step="0.01"
                                        name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][amount]"
                                        value="<?= $prod['amount'] ?>"
                                        class="form-control quotation_edit qtn_amount"
                                        readonly>
                                </td>

                                <td>
                                    <input type="text"
                                        name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][discount_amount]"
                                        value=""
                                        class="form-control quotation_edit qtn_discount_amount">
                                </td>

                                <td>
                                    <input type="number" step="0.01"
                                        name="products[<?= $i ?>][<?= $j ?>][<?= $k ?>][taxable_amount]"
                                        value=""
                                        class="form-control quotation_edit qtn_taxable_amount"
                                        readonly>
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
<?php endif; ?>