<!DOCTYPE html>
<html>
<head>
    <title>Sales Order</title>
    <style>
        body {
            font-family: "Franklin Gothic Book", Arial, sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .title {
            text-align: center;
            margin: 20px 0 15px 0;
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
            color: #2C2C2C;
        }

        .party-table {
            width: 100%;
            margin-bottom: 20px;
            border-spacing: 0;
        }
        .party-table td {
            vertical-align: top;
            padding: 10px;
        }
        .party-table .section-title {
            background-color: #2C2C2C;
            color: #C49A00;
            padding: 6px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 13px;
        }
        .party-table .info {
            font-size: 12px;
            line-height: 1.5;
            text-transform: uppercase;
        }

        table.products {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 20px;
        }
        table.products th {
            background-color: #2C2C2C;
            color: #C49A00;
            font-weight: bold;
            padding: 8px;
            border: 1px solid #555;
        }
        table.products td {
            border: 1px solid #aaa;
            padding: 6px;
            text-align: center;
            font-size: 12px;
        }
        table.products tr:nth-child(even) td {
            background-color: #f9f9f9;
        }

        table.totals {
            width: 40%;
            float: right;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 20px;
        }
        table.totals td {
            border: 1px solid #aaa;
            padding: 6px;
            text-align: right;
        }
        table.totals td.label {
            font-weight: bold;
            background-color: #f2f2f2;
        }
        table.totals td.total {
            background-color: #2C2C2C;
            color: #C49A00;
            font-weight: bold;
            font-size: 13px;
        }

        .terms-title {
            background-color: #2C2C2C;
            color: #C49A00;
            padding: 6px;
            font-weight: bold;
            font-size: 13px;
            margin-top: 15px;
            text-transform: uppercase;
        }
        .terms-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 20px;
        }
        .terms-table td {
            padding: 6px;
            border: 1px solid #ddd;
        }
        .terms-table td:first-child {
            font-weight: bold;
            width: 25%;
            background-color: #f9f9f9;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            position: fixed;
            bottom: -10px;
            left: 0;
            right: 0;
            width: 100%;
        }
        .footer img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }
         @page {
    margin: 1mm 12mm 12mm 12mm; /* top right bottom left */
}
  .header-img {
    margin-top: -2mm;
}
    </style>
</head>
<body>

<!-- Header -->
<!-- Header + Sales Order aligned in one row -->
<table style="width:100%; border-collapse:collapse; margin-bottom:15px;">
    <tr>
        <!-- Left: Logo -->
        <td style="width:60%; padding:30px 20px 20px 20px;">
            <img src="<?= $headerPath ?>" class="header-img" style="max-height:120px;">
        </td>

        <!-- Right: Sales Order Info -->
        <td style="width:40%; text-align:right; vertical-align:top; padding:30px 20px 20px 20px;">
            <div style="font-size:24px; font-weight:bold; color:#2C2C2C;">Sales Order</div>
            <div style="font-size:14px;"><?= $so_master['so_code'] ?> (Rev <?= $so_master['so_revision'] ?>)</div>
            <div style="font-size:14px;"><?= date('d-m-Y', strtotime($so_master['so_date'])) ?></div>
        </td>
    </tr>
</table>
<!-- Supplier / Buyer -->
<table class="party-table">
    <tr>
        <td width="50%">
            <div class="section-title">Supplier</div>
            <div class="info">
                <?= $branch_name ?><br>
                <?= $branch_contact ?><br>
                <?= $branch_address ?><br>
                <?= $branch_location ?>
            </div>
        </td>
        <td width="50%">
            <div class="section-title">Buyer</div>
            <div class="info">
                <?= $customer_name ?><br>
                <?= $contact_number ?><br>
                <?= $customer_email ?><br>
                <?= $customer_address ?>
            </div>
        </td>
    </tr>
</table>

<!-- Project Info -->
<table class="party-table">
    <tr>
        <td width="50%">Project name : <strong><?= $project_name ?></strong></td>
        <td width="50%">Location : <strong><?= $project_location ?></strong></td>
    </tr>
</table>

<!-- Products -->
<table class="products">
    <thead>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>Unit</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Amount</th>
            <th>Discount</th>
            <th>Taxable</th>
        </tr>
    </thead>
    <tbody>
        <?php $i=1; foreach ($so_products as $prd) { ?>
        <tr>
            <td><?= $i ?></td>
            <td style="font-weight:bold;"><?= $prd['product_name'] ?></td>
            <td><?= $prd['unit_name'] ?></td>
            <td><?= number_format($prd['quantity'], 2) ?></td>
            <td><?= number_format($prd['unit_price'], 2) ?></td>
            <td><?= number_format($prd['amount'], 2) ?></td>
            <td><?= number_format($prd['discount_amount'], 2) ?></td>
            <td><?= number_format($prd['taxable_amount'], 2) ?></td>
        </tr>
        <?php $i++; } ?>
    </tbody>
</table>

<!-- Totals -->
<table class="totals">
    <tr>
        <td class="label">Subtotal:</td>
        <td><?= number_format($so_master['sub_total'], 2) ?></td>
    </tr>
    <tr>
        <td class="label">Discount:</td>
        <td><?= number_format($so_master['discount_amount'], 2) ?></td>
    </tr>
    <tr>
        <td class="label">VAT:</td>
        <td><?= number_format($so_master['vat_amount'], 2) ?></td>
    </tr>
    <tr>
        <td class="label total">Grand Total:</td>
        <td class="total"><?= number_format($so_master['grand_total'], 2) ?></td>
    </tr>
</table>

<div style="clear: both;"></div>

<!-- Terms -->
<div class="terms-title">Terms and Conditions</div>
<table class="terms-table">
    <tr>
        <td>Validity</td>
        <td><?= $so_master['validity'] ?></td>
    </tr>
    <tr>
        <td>Payment terms</td>
        <td><?= $so_master['payment_term'] ?></td>
    </tr>
    <tr>
        <td>Delivery terms</td>
        <td><?= $so_master['delivery_term'] ?></td>
    </tr>
    <tr>
        <td>Other Conditions</td>
        <td><?= $so_master['terms_and_condition'] ?></td>
    </tr>
    <tr>
        <td>Remarks</td>
        <td><?= $so_master['remarks'] ?></td>
    </tr>
</table>

<!-- Footer -->
<div class="footer">
    <img src="<?= $footerPath ?>" alt="Logo">
  
</div>

</body>
</html>
