<html>
<head>
<title>PPM Invoice</title>

<style>
body {
    font-family: Arial;
    font-size: 13px;
    margin: 5px;
    color: #333;
}

.title {
    text-align: center;
    font-size: 20px;
    font-weight: bold;
    margin: 15px 0;
    color: #2e6da4;
}

.items-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.items-table th,
.items-table td {
    border: 1px solid #ccc;
    padding: 6px;
    font-size: 12px;
}

.items-table th {
    background: #2e6da4;
    color: #fff;
    text-align: center;
}

.summary-table {
    width: 100%;
    margin-top: 15px;
    border-collapse: collapse;
}

.summary-table td {
    padding: 6px;
    border: 1px solid #ccc;
}

.summary-table tr td:first-child {
    background: #f9f9f9;
}

.footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    text-align: center;
}

.footer img {
    width: 100%;
}

@page {
    margin: 10mm;
}
</style>
</head>

<body>

<!-- HEADER -->
<table style="width:100%;">
<tr>
<td style="width:60%;">
    <img src="<?= $headerPath ?>" style="max-height:100px;">
</td>

<td style="width:40%; text-align:right;">
    <b>Invoice No:</b> <?= $invoice->invoice_no ?><br>
    <b>Date:</b> <?= date('d-M-Y', strtotime($invoice->invoice_date)) ?><br>
    <b>Type:</b> PPM Invoice
</td>
</tr>
</table>

<div class="title">PPM INVOICE</div>

<!-- CUSTOMER INFO -->
<table class="items-table">
<tr>
    <td>
        <b>Customer:</b><br>
        <?= $invoice->project_name ?><br>
        Customer ID: <?= $invoice->customer_id ?>
    </td>

    <td>
        <b>PPM Code:</b><br>
        <?= $ppm_details->ppm_code ?>
    </td>

    <td>
        <b>Status:</b><br>
        <?= $invoice->status ?>
    </td>
</tr>
</table>

<!-- PPM DETAILS -->
<table class="items-table">
<thead>
<tr>
    <th>#</th>
    <th>PPM No</th>
    <th>Schedule Date</th>
    <th>Completed Date</th>
    <th>Status</th>
    <th>Amount</th>
</tr>
</thead>

<tbody>

<?php $i=1; ?>
<tr>
    <td><?= $i++; ?></td>
    <td><?= $ppm_details->ppm_num ?></td>
    <td><?= date('d-M-Y', strtotime($ppm_details->ppm_sch_date)) ?></td>
    <td>
        <?= !empty($ppm_details->completed_date) 
            ? date('d-M-Y', strtotime($ppm_details->completed_date)) 
            : '-' ?>
    </td>
    <td><?= $ppm_details->ppm_status ?></td>
    <td style="text-align:right;">
        <?= number_format($invoice->amount, 2) ?>
    </td>
</tr>

</tbody>
</table>

<!-- TOTAL -->
<table class="summary-table">
<tr>
    <td width="80%"><b>Total Amount</b></td>
    <td width="20%" style="text-align:right;">
        <b><?= number_format($invoice->amount, 2) ?></b>
    </td>
</tr>
</table>

<!-- BANK (optional reuse from your system) -->
<?php if (!empty($invoice_bank_data)) { ?>
<div style="margin-top:15px; text-align:right;">
    <b>Bank Details</b><br>
    <?= $invoice_bank_data->bank_name ?><br>
    <?= $invoice_bank_data->bank_account ?><br>
    IBAN: <?= $invoice_bank_data->bank_iban ?>
</div>
<?php } ?>

<!-- FOOTER -->
<div class="footer">
    <img src="<?= $footerPath ?>">
</div>

<script>
window.onload = function(){
    window.print();
}
</script>

</body>
</html>