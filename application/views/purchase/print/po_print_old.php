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
<table style="width:100%; border-collapse:collapse; margin-bottom:15px;">
    <tr>
          <td style="width:60%; padding:30px 20px 20px 20px;">
            <img src="<?= $headerPath ?>" class="header-img" style="max-height:120px;">
        </td>
   
        <td style="width:40%; text-align:right; vertical-align:top; padding:30px 20px 20px 20px;">
            <div style="font-size:20px; font-weight:bold; color:#2C2C2C;">Purchase Order</div>
            <div style="font-size:12px;"><?= $po->po_code ?></div>
            <div style="font-size:12px;"><?= date('d-m-Y', strtotime($po->po_date)) ?></div>
        </td>
    </tr>
</table>

<!-- Supplier / Branch Info -->
<table class="party-table">
    <tr>
        <td width="50%">
            <div class="section-title">Supplier</div>
            <div class="info">
                <?= $po->supplier_name ?><br>
                <?= $po->contact_number ?><br>
                <?= $po->supplier_email ?><br>
                <?= $po->billing_address ?><br>
                <?= $po->billing_city ?>, <?= $po->billing_state ?>, <?= $po->billing_country ?>
            </div>
        </td>
        <td width="50%">
            <div class="section-title">Branch</div>
            <div class="info">
                <?= $branch_name ?><br>
                <?= $branch_contact ?><br>
                <?= $branch_email ?><br>
                <?= $branch_address ?><br>
                <?= $branch_location ?><br>
                TRN: <?= $branch_trn ?><br>
                Web: <?= $branch_web ?>
            </div>
        </td>
    </tr>
</table>

<!-- PO Info -->
<!-- <table class="party-table">
    <tr>
        <td>Date: <strong><?= date('d-m-Y', strtotime($po->po_date)) ?></strong></td>
        <td>PO No: <strong><?= $po->po_code ?></strong></td>
        <td>Prepared By: <strong><?= $po->created_by ?></strong></td>
        <td>Approved By: <strong><?= $po->user_name ?? '-' ?></strong></td>
    </tr>
</table> -->

<!-- Products -->
<table class="products">
    <thead>
        <tr>
            <th>#</th>
            <th>Model</th>
            <th>Description</th>
            <th>Qty</th>
            <th>Unit</th>
            <th>Price</th>
            <th>Discount</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $sl = 1; 
        $total_before_vat = 0;
        $total_discount = 0;
        $grand_total = 0;

        foreach ($po_tr as $item): 
            $total_before_vat += $item->price * $item->quantity;
            $total_discount += $item->dis_amt;
            $grand_total += $item->total;
        ?>
        <tr>
            <td><?= $sl++ ?></td>
            <td><?= $item->item_name ?></td>
            <td><?= $item->item_description ?></td>
            <td><?= $item->quantity ?></td>
            <td><?= $item->unit_name ?></td>
            <td><?= number_format($item->price, 2) ?></td>
            <td><?= number_format($item->dis_amt, 2) ?></td>
            <td><?= number_format($item->total, 2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Totals -->
<table class="totals">
    <tr>
        <td class="label">Total Before VAT</td>
        <td><?= number_format($total_before_vat, 2) ?></td>
    </tr>
    <tr>
        <td class="label">Discount Amount</td>
        <td><?= number_format($total_discount, 2) ?></td>
    </tr>
   <tr>
    <td class="label">VAT Amount</td>
    <td><?= number_format($po->vat_amt, 2) ?></td>
</tr>
    <tr>
        <td class="label total">Grand Total</td>
        <td class="total"><?= number_format($po->grand_total, 2) ?></td>
    </tr>
</table>
<div style="clear: both;"></div>
<!-- Terms -->
<div class="terms-title">Terms & Conditions</div>
<table class="terms-table">
    <tr>
        <td>Validity</td>
        <td><?= $po->validity ?? '-' ?></td>
    </tr>
    <tr>
        <td>Payment Terms</td>
        <td><?= $po->payment_term ?? '-' ?></td>
    </tr>
    <tr>
        <td>Delivery Terms</td>
        <td><?= $po->delivery_term ?? '-' ?></td>
    </tr>
    <tr>
        <td>Other Conditions</td>
        <td><?= $po->terms_and_condition ?? '-' ?></td>
    </tr>
    <tr>
        <td>Remarks</td>
        <td><?= $po->remarks ?? '-' ?></td>
    </tr>
</table>

<!-- Footer -->
<div class="footer">
    <img src="<?= $footerPath ?>" alt="Logo">
  
</div>

</body>
</html>
