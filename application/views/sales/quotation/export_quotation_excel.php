<style>
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
}

table th,
table td {
    border: 1px solid #000;
    padding: 5px;
    vertical-align: top;
}

th {
    background: #d9d9d9;
    text-align: center;
}
</style>

<!-- ================= QUOTATION HEADER ================= -->
<table style="width:100%; border-collapse:collapse; table-layout:fixed;" border="1">
    <tr>
        <th colspan="6">QUOTATION</th>
    </tr>

   <tr>
    <td style="padding:6px; width:15%;"><b>Quotation No</b></td>
    <td style="padding:6px; width:20%;"><?= $quotation_details['quotation_code'] ?></td>

    <td style="padding:6px; width:10%;"><b>Date</b></td>
    <td style="padding:6px; width:20%;"><?= date('d-m-Y', strtotime($quotation_details['quotation_date'])) ?></td>

    <td style="padding:6px; width:17%;"></td>
    <td style="padding:6px; width:18%;"></td>
</tr>
</table>
<!-- ================= COMPANY / BUYER ================= -->
<table style="width:100%; border-collapse:collapse; table-layout:fixed;" border="1">
    <tr>
        <td colspan="3" style="padding:8px; vertical-align:top;">
            <b>Company</b><br><br>
            <strong>AL ADEL AUTOMATIC DOORS TR LLC</strong><br>
            Al Aref Building, Office No. 14,<br>
            Industrial Area 18,<br>
            Sharjah, UAE<br>
            +97165442460, +971502789725
        </td>

        <td colspan="3" style="padding:8px; vertical-align:top;">
            <b>Buyer</b><br><br>
            <?= $quotation_details['customer_name'] ?><br>
            <?= $quotation_details['contact_number'] ?><br>
            <?= $quotation_details['customer_email'] ?><br>
            <?= $quotation_details['customer_address'] ?>
        </td>
    </tr>
</table>
<!-- ================= PROJECT ================= -->
<table style="width:100%; border-collapse:collapse; table-layout:fixed;" border="1">
    <tr>
        <td colspan="3">
            Project Name : <b><?= $quotation_details['project_name'] ?></b>
        </td>

        <td colspan="3">
            Location : <b><?= $quotation_details['project_location'] ?></b>
        </td>
    </tr>
</table>

<!-- ================= PRODUCTS ================= -->
<table>
    <tr>
        <th>#</th>
        <th>Reference</th>
        <th>Product</th>
        <th>Qty</th>
        <th>Unit Price</th>
        <th>Amount</th>
    </tr>

    <?php $i = 1; foreach ($quotation_products as $row) { ?>
    <tr>
        <td><?= $i++ ?></td>
        <td><?= $row['item_code'] ?></td>
        <td>
            <b><?= $row['item_name'] ?></b><br>
            <?= trim(strip_tags($row['prd_description'])) ?>
        </td>
        <td><?= number_format($row['qty'], 2) ?></td>
        <td><?= number_format($row['unit_price'], 2) ?></td>
        <td><?= number_format($row['qty'] * $row['unit_price'], 2) ?></td>
    </tr>
    <?php } ?>
</table>

<!-- ================= AMOUNT IN WORDS ================= -->
<table>
    <tr>
        <td colspan="5"><b>Amount in Words</b></td>
        <td>
            <?= numberToWords((float)($quotation_details['grand_total'] ?? 0)); ?>
        </td>
    </tr>
</table>

<!-- ================= TOTALS ================= -->
<table>
    <tr>
        <td colspan="5" align="right"><b>Sub Total</b></td>
        <td><?= number_format($quotation_details['sub_total'], 2) ?></td>
    </tr>

    <tr>
        <td colspan="5" align="right"><b>Other Charges</b></td>
        <td><?= number_format($quotation_details['other_charge'], 2) ?></td>
    </tr>

    <tr>
        <td colspan="5" align="right">
            <b>Discount (<?= $quotation_details['discount_percentage'] ?? 0 ?>%)</b>
        </td>
        <td><?= number_format($quotation_details['discount_amount'] ?? 0, 2) ?></td>
    </tr>

    <tr>
        <td colspan="5" align="right">
            <b>VAT (<?= $quotation_details['vat_percentage'] ?? 0 ?>%)</b>
        </td>
        <td><?= number_format($quotation_details['vat_amount'] ?? 0, 2) ?></td>
    </tr>

    <tr>
        <td colspan="5" align="right"><b>Grand Total</b></td>
        <td><b><?= number_format($quotation_details['grand_total'], 2) ?></b></td>
    </tr>
</table>

<!-- ================= TERMS ================= -->
<table>
    <tr>
        <th colspan="6">Terms & Conditions</th>
    </tr>

    <tr>
        <td colspan="2"><b>Validity</b></td>
        <td colspan="4"><?= $quotation_details['validity'] ?></td>
    </tr>

    <tr>
        <td colspan="2"><b>Warranty</b></td>
        <td colspan="4"><?= $quotation_details['warranty'] ?></td>
    </tr>

    <tr>
        <td colspan="2"><b>Payment Terms</b></td>
        <td colspan="4"><?= $quotation_details['payment_term'] ?></td>
    </tr>

    <tr>
        <td colspan="2"><b>Delivery Terms</b></td>
        <td colspan="4"><?= $quotation_details['delivery_term'] ?></td>
    </tr>

    <tr>
        <td colspan="2"><b>Other Conditions</b></td>
        <td colspan="4"><?= $quotation_details['terms_condition'] ?></td>
    </tr>
</table>