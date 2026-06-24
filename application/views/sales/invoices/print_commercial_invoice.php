<!DOCTYPE html>
<html>
<head>
    <title>Commercial Invoice</title>
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

        .footer {
        
            position: fixed;
            bottom: -50px;
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
        @media print {
            .header, .footer { page-break-inside: avoid; }
        }
        .header-img {
    margin-top: -2mm;
}
        
    </style>
</head>
<body>

<!-- Header -->
<table style="width:100%; border-collapse:collapse; margin-bottom:5px;">
    <tr>
    <td style="width:60%; padding:5px 5px 5px 0;">
<img src="<?= $headerPath ?>" class="header-img" style="max-height:120px;">     
  </td>
          <td style="width:40%; text-align:right; padding:5px 5px 5px 5px;">
 <div style="font-size:20px; font-weight:bold;">Commercial Invoice</div>
            <div style="font-size:12px;">No: <?= $del_master['delivery_code'] ?></div>
            <div style="font-size:12px;">
                <?= date('d-m-Y', strtotime($del_master['delivery_date'])) ?>
            </div>
</td>
    </tr>
</table>


<!-- Title & Reference -->
<!-- <table style="width:100%; border-collapse:collapse; margin-bottom:15px;">
    <tr>
        <td style="width:50%; padding:10px;">
            <div style="font-size:14px;">
                <?= $reference_type === 'Direct Quotation' ? 'Quotation No' : 'Enquiry No' ?> :
                <strong><?= $reference_code ?></strong>
            </div>
            <div style="font-size:14px;">Project: <?= $project_name ?></div>
            <div style="font-size:14px;">Location: <?= $project_location ?></div>
        </td>

        <td style="width:50%; text-align:right; padding:10px;">
            <div style="font-size:20px; font-weight:bold;">Commercial Invoice</div>
            <div style="font-size:12px;">No: <?= $del_master['delivery_code'] ?></div>
            <div style="font-size:12px;">
                <?= date('d-m-Y', strtotime($del_master['delivery_date'])) ?>
            </div>
        </td>
    </tr>
</table> -->

<!-- Supplier / Customer -->
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
            <div class="section-title">Customer</div>
            <div class="info">
                TRN: <?= $customer_TRN ?><br>
                <?= $customer_name ?><br>
                <?= $contact_number ?><br>
                <?= $customer_email ?><br>
                <?= $customer_address ?>
            </div>
        </td>
    </tr>
</table>

<table class="party-table" style="width:100%; border-collapse:collapse; margin-top:10px;">
        <tr>
            <td width="50%" style="border:1px solid #000; padding:8px;">
                Project name : <strong><?= $project_name ?></strong>
            </td>
            <td width="50%" style="border:1px solid #000; padding:8px;">
                Location : <strong><?= $project_location ?></strong>
            </td>
        </tr>
    </table>

<!-- Products -->
<table class="products">
    <thead>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>Delivered Qty</th>
             <th>Rate</th>
            <th>Amount</th>
        </tr>
    </thead>
     <tbody>
       <?php 
$i = 1;
$grand_total = 0;

foreach ($del_products as $prd) {

    $product_id = $prd['product_id'] ?? 0;
    $qty        = $prd['quantity'] ?? 0;

    // rate from item_master
    $rate = $item_master[$product_id]['unit_price'] ?? 0;

    $amount = $qty * $rate;
    $grand_total += $amount;
?>
<tr>
    <td><?= $i ?></td>
    <td style="text-align:left;"><?= $item_master[$product_id]['item_name'] ?? '' ?></td>
    <td><?= $qty ?></td>
    <td><?= number_format($rate, 2) ?></td>
    <td><?= number_format($amount, 2) ?></td>
</tr>
<?php $i++; } ?>
    </tbody>

    <tfoot>
        <tr>
            <td colspan="4" style="text-align:right; font-weight:bold;">Grand Total</td>
            <td><b><?= number_format($grand_total, 2) ?></b></td>
        </tr>
    </tfoot>
</table>
<!-- Delivery Info -->
<table class="party-table" style="margin-top:20px;">
    <tr>
        <td width="50%">
            <strong>Delivery Mode:</strong> <?= $del_master['delivery_mode'] ?><br>
            <strong>Delivered By:</strong> <?= $del_master['deliverd_by'] ?><br>
            <strong>Issued By:</strong> <?= $del_master['issued_by'] ?>
        </td>
        <td width="50%">
            <strong>Shipping Address:</strong> <?= $del_master['shipping_address'] ?><br>
            <strong>City:</strong> <?= $del_master['shipping_city'] ?><br>
            <strong>Contact:</strong> <?= $del_master['contact'] ?><br>
            <strong>Email:</strong> <?= $del_master['email'] ?>
        </td>
    </tr>
</table>

<!-- Footer -->
<div class="footer">
    <img src="<?= $footerPath ?>">
  
</div>

</body>
</html>
