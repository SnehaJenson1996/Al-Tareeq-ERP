<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order</title>
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
 .page-header {
            width: 100%;
            background: #fff;
            z-index: 999;
        }
         .page-content {
            margin-top: 20px;
            margin-bottom: 80px;
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
            margin-bottom: 8px;
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
        .approval-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 20px;
            font-size: 12px;
            page-break-inside: avoid;
        }
        .approval-table td {
            width: 33.3333%;
            vertical-align: top;
            padding: 8px;
        }
        .approval-title {
            font-weight: bold;
            margin-bottom: 8px;
            display: block;
        }
        .footer {
             position: fixed;
    bottom: -70px;
    left: 0;
    right: 0;
        }
        .footer img {
             max-width: 100%;
      height: auto;
      display: block;
      margin: 0 auto;
        }
          @page {
            margin: 0mm 12mm 20mm 12mm; /* top right bottom left */
        }
        @media print {
            .page-header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                margin-top: 0;
            }
            .page-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
            }
            .page-content {
                margin-top: 80px;
                margin-bottom: 10px;
            }
        }
  .header-img {
    margin-top: -2mm;
}
 .no-break {
    page-break-inside: avoid;
    break-inside: avoid;
}
    </style>
</head>
<body>

<!-- Header -->
<header class="page-header">
    <table style="width:100%; border-collapse:collapse; margin-bottom:0;">
        <tr>
            <td style="width:60%;vertical-align:top;">
                <img src="<?= $headerPath ?>" class="header-img" style="max-height:100px;">
            </td>
            <td style="width:40%; text-align:right; vertical-align:top;">
                <div style="font-size:20px; font-weight:bold; color:#2C2C2C;">Purchase Order</div>
                <div style="font-size:12px;"><?= $po->po_code ?></div>
                <div style="font-size:12px;"><?= date('d-m-Y', strtotime($po->po_date)) ?></div>
                <div style="font-size:12px;"><?= $po->quotation_code ?></div>            
            </td>
        </tr>
    </table>
</header>
<div class="page-content">

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
                <?= $po->branch_name ?><br>
                <?= $po->branch_contact ?><br>
                <?= $po->branch_email ?><br>
                <?= $po->branch_address ?><br>
                <?= $po->branch_location ?><br>
                TRN: <?= $po->branch_trn ?><br>
                Web: <?= $po->branch_web ?>
            </div>
        </td>
    </tr>
</table>

<!-- Billing Address / Shipping Address Info -->
<table class="party-table">
    <tr>
        <td width="50%">
            <div class="section-title">Billing Address</div>
            <div class="info">
                <?= $po->billing_address ?><br>
                <?= $po->billing_city ?>, <?= $po->billing_state ?>, <?= $po->billing_country ?>
            </div>
        </td>
        <td width="50%">
            <div class="section-title">Shipping Address</div>
            <div class="info">
                <?= $po->shipping_address ?><br>
                <?= $po->shipping_city ?>, <?= $po->shipping_state ?>, <?= $po->shipping_country ?>
            </div>
        </td>
    </tr>
</table>

<!-- Products -->
<table class="products">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Description</th>
            <th>Qty</th>
            <th>Unit</th>
            <th>Price</th>
            <th>Currency</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php $i=1; 
        $total_before_vat = 0;
        $total_discount = 0;
        $grand_total = 0;
        foreach($po_tr as $item): 
            $total_before_vat += $item->price * $item->quantity;
            $total_discount += $item->dis_amt;
            $grand_total += $item->total;
        ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= $item->product_name ?></td>
            <td><?= $item->description ?></td>
            <td><?= $item->quantity ?></td>
            <td><?= $item->unit_name ?></td>
            <td><?= number_format($item->price,2) ?></td>
            <!-- <td><?= number_format($item->dis_amt,2) ?></td> -->
            <td><?= $po->currency_abbr ?></td>
            <td><?= number_format($item->total,2) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Totals -->
 <table width="100%" style="margin-top:10px;">
    <tr>

        <!-- LEFT: Amount in Words -->
        <td style="width:60%; vertical-align:top; padding-right:10px;">
            <strong>Amount in Words:</strong><br>
            <?= numberToWordsAED((float)$po->grand_total); ?> 
        </td>

        <!-- RIGHT: Totals -->
        <td style="width:40%; vertical-align:top;">
            <table class="totals" style="width:100%;">
                <tr>
                    <td class="label">Total Before VAT</td>
                    <td><?= number_format($total_before_vat, 2) ?></td>
                </tr>

               <?php if (!empty($total_discount) && $total_discount > 0) { ?>
<tr>
    <td class="label">Discount Amount</td>
    <td><?= number_format($total_discount, 2) ?></td>
</tr>
<?php } ?>

<?php if (!empty($po->vat_amt) && $po->vat_amt > 0) { ?>
<tr>
    <td class="label">VAT Amount</td>
    <td><?= number_format($po->vat_amt, 2) ?></td>
</tr>
<?php } ?>

                <tr>
                    <td class="label total">Grand Total</td>
                    <td class="total"><?= number_format($po->grand_total, 2) ?></td>
                </tr>
            </table>
        </td>

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
<table width="100%" style="border-collapse:collapse; table-layout:fixed; margin-top:20px;">
    <tr>

        <!-- Prepared By -->
        <td style="text-align:left; vertical-align:top; width:25%; padding:0 5px;">
            <strong>Prepared By:</strong><br>

            <?php if (!empty($prepared_signature)) { ?>
                <img src="<?= base_url('public/employee/' . $prepared_signature) ?>"
                     style="height:60px; margin-top:5px;"><br>
            <?php } ?>

            <span><?= htmlspecialchars($prepared_by_name ?? '') ?></span>
        </td>

        <!-- Checked By -->
        <td style="text-align:center; vertical-align:top; width:25%; padding:0 5px;">
            <strong>Checked By:</strong><br>

            <?php if (!empty($checked_signature)) { ?>
                <img src="<?= base_url('public/employee/' . $checked_signature) ?>"
                     style="height:60px; margin-top:5px;"><br>
            <?php } ?>

            <span><?= htmlspecialchars($checked_by_name ?? '') ?></span>
        </td>

        <!-- Approved By -->
        <td style="text-align:center; vertical-align:top; width:25%; padding:0 5px;">
            <strong>Approved By:</strong><br>

            <?php if (!empty($approved_signature)) { ?>
                <img src="<?= base_url('public/employee/' . $approved_signature) ?>"
                     style="height:60px; margin-top:5px;"><br>
            <?php } ?>

            <span><?= htmlspecialchars($approved_by_name ?? '') ?></span>
        </td>

        <!-- Stamp -->
        <td style="text-align:right; vertical-align:top; width:25%;">

            <?php if (!empty($branch_stamp)) { ?>

                <?php
                $path = FCPATH . ltrim($branch_stamp, './');

                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                ?>
                   <img src="<?= $base64 ?>"
     style="max-width:140px; max-height:100px; margin-top:-10px;">
                <?php } ?>

            <?php } ?>

        </td>

    </tr>
</table>

</div>

    <!-- Footer -->
    <div class="footer">
        <img src="<?= $footerPath ?>" alt="Logo">
    </div>
</body>
</html>
