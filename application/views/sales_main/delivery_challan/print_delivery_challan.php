<!DOCTYPE html>
<html>
<head>
    <title>Delivery Challan</title>
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
            text-align: center;
            font-size: 12px;
            position: fixed;
            bottom: 0;
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

        @media print {
            .header, .footer { page-break-inside: avoid; }
        }
    </style>
</head>
<body>

<!-- Header Logo -->
<table style="width:100%; border-collapse:collapse; margin-bottom:5px;">
    <tr>
        <td style="width:100%; vertical-align:middle;">
            <img src="<?= $headerPath ?>" alt="Company Logo" style="width:100%; height:auto;">
        </td>
    </tr>
</table>

<!-- Challan Title & Details -->
<table style="width:100%; border-collapse:collapse; margin-bottom:15px;">
    <tr>
        <td style="width:50%; vertical-align:middle; padding:10px;">
            <div style="font-size:14px;">Enquiry No: <?= $enquiry_code ?></div>
            <div style="font-size:14px;">Project: <?= $project_name ?></div>
            <div style="font-size:14px;">Location: <?= $project_location ?></div>
        </td>
        <td style="width:50%; text-align:right; vertical-align:middle; padding:10px;">
            <div style="font-size:20px; font-weight:bold; color:#2C2C2C;">Delivery Challan</div>
            <div style="font-size:12px;">No: <?= $del_master['delivery_code'] ?></div>
            <div style="font-size:12px;"><?= date('d-m-Y', strtotime($del_master['delivery_date'])) ?></div>
        </td>
    </tr>
</table>

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
                <?= $customer_TRN ?><br>
                <?= $customer_name ?><br>
                <?= $contact_number ?><br>
                <?= $customer_email ?><br>
                <?= $customer_address ?>
            </div>
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
        </tr>
    </thead>
    <tbody>
        <?php 
        $i = 1;
        foreach ($del_products as $prd) { ?>
        <tr>
            <td><?= $i ?></td>
            <td style="text-align:left;"><?= $prd['product_name'] ?></td>
            <td><?= $prd['quantity'] ?></td>
        </tr>
        <?php $i++; } ?>
    </tbody>
</table>

<!-- Delivery Details -->
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
    <img src="<?= $footerPath ?>" alt="Logo">
    Thank you for your business.<br>
    <?= $branch_website ?>
</div>

</body>
</html>
