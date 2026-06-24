<!DOCTYPE html>
<html>
<head>
    <title>E-Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #333;
        }

        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 10px 0;
        }

        .box {
            width: 100%;
            margin-bottom: 10px;
        }

        .box td {
            vertical-align: top;
            padding: 8px;
            border: 1px solid #ddd;
        }

        .header-table {
            width: 100%;
            margin-bottom: 10px;
        }

        .products {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .products th {
            background: #2C2C2C;
            color: #fff;
            padding: 8px;
            border: 1px solid #444;
        }

        .products td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: center;
        }

        .total-box {
            text-align: right;
            margin-top: 10px;
            font-weight: bold;
        }

        .qr-box {
            text-align: right;
        }
    </style>
</head>

<body>

<!-- HEADER -->
<table class="header-table">
    <tr>
        <td style="width:60%;">
            <img src="<?= $headerPath ?>" style="max-height:100px;">
        </td>
        <td style="width:40%; text-align:right;">
            <div class="title">E-INVOICE</div>
            <div><b>Invoice No:</b> <?= $invoice_master['invoice_code'] ?></div>
            <div><b>Date:</b> <?= date('d-m-Y', strtotime($invoice_master['invoice_date'])) ?></div>
            <div><b>TRN:</b> <?= $customer_trn ?></div>
        </td>
    </tr>
</table>

<!-- CUSTOMER -->
<table class="box">
    <tr>
        <td>
            <b>Customer</b><br>
            <?= $customer_name ?><br>
            <?= $customer_address ?><br>
            <?= $customer_email ?><br>
            <?= $contact_number ?>
        </td>

        <td class="qr-box">
            <!-- QR PLACEHOLDER -->
            <img src="<?= $qr_code ?? '' ?>" style="height:120px;">
        </td>
    </tr>
</table>

<!-- PRODUCTS -->
<table class="products">
    <thead>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>Qty</th>
            <th>Rate</th>
            <th>Amount</th>
        </tr>
    </thead>

    <tbody>
        <?php 
        $i = 1;
        $grand_total = 0;

        foreach ($invoice_products as $p) {

            $qty = $p['quantity'];
            $rate = $p['unit_price'] ?? 0;
            $amt = $qty * $rate;

            $grand_total += $amt;
        ?>
        <tr>
            <td><?= $i++ ?></td>
            <td style="text-align:left;"><?= $p['product_name'] ?></td>
            <td><?= $qty ?></td>
            <td><?= number_format($rate, 2) ?></td>
            <td><?= number_format($amt, 2) ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<!-- TOTAL -->
<div class="total-box">
    Grand Total: <?= number_format($grand_total, 2) ?>
</div>

<!-- FOOTER -->
<div style="margin-top:20px; font-size:11px; text-align:center;">
    This is a system generated E-Invoice (VAT included if applicable)
</div>

</body>
</html>