 <!DOCTYPE html>
<html>
<head>
    <title>Quotation</title>
    <style>
        body {
            font-family: "Franklin Gothic Book", Arial, sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center; /* ✅ Keeps logo and quotation aligned vertically */
            padding: 5px 20px;
            position: fixed;
        }

        .header-left .logo {
            height: 70px;   /* Adjust as per your logo */
            display: block;
        }

        .header-right {
            text-align: right;
            margin-top: 0;  /* ✅ Removes extra space */
        }

        .quotation-title {
            font-size: 20px;
            font-weight: bold;
            line-height: 1.2; /* ✅ Keeps spacing tight */
        }

        .quotation-number {
            font-size: 12px;
            margin-top: 2px;
        }
        /* Title */
        .title {
            text-align: center;
            margin: 20px 0 10px 0;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Supplier/Buyer Section */
        .party-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .party-table td {
            vertical-align: top;
            padding: 10px;
        }
        .party-table .section-title {
            background-color: #656c69ff;
            color:#e8b41a;
            padding: 5px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }
        .party-table .info {
            font-size: 12px;
            text-transform: uppercase;
        }

        /* Quotation Details Table */
        table.products {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 20px;
        }
        table.products th {
            background-color: #656b68;
            color: #e8b41a;
            font-weight: bold;
            padding: 8px;
            border: 1px solid #000;
        }
        table.products td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            font-size: 12px;
        }
        table.products td img {
            display: block;
            margin: auto;
        }

        /* Totals */
        table.totals {
            width: 40%;
            float: right;
            border-collapse: collapse;
            font-size: 12px;
        }
        table.totals td {
            border: 1px solid #000;
            padding: 5px;
            text-align: right;
        }
        table.totals td.label {
            font-weight: bold;
        }
        table.totals td.total {
            background-color: #5e6462ff;
            color: #e8b41a;
            font-weight: bold;
        }

        /* Terms */
        .terms {
            margin-top: 20px;
            font-size: 12px;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            position: fixed;
        }

        /* Print */
        @media print {
            .header, .footer { page-break-inside: avoid; }
        }
    </style>
</head>
<body>

<table style="width:100%; border-collapse:collapse; margin-bottom:5px;">
    <tr>
        <!-- Left side: Company Logo -->
        <td style="width:50%; vertical-align:middle;">
            <img src="<?= $headerPath ?>" alt="Company Logo" style="height:70px;">
        </td>

        <!-- Right side: Quotation Title and Number -->
        <td style="width:50%; text-align:right; vertical-align:middle;">
            <div style="font-size:20px; font-weight:bold;">Sales Quotation</div>
            <div style="font-size:12px;"><?= $quotation_details['quotation_code'] ?></div>
            <div style="font-size:12px;"><?= $quotation_details['quotation_date'] ?></div>
        </td>
    </tr>
</table>


<hr style="margin:5px 0; border:1px solid #000;">
    <!-- Title -->
    <div class="title"></div>

    <!-- Supplier / Buyer -->
    <table class="party-table">
        <tr>
            <!-- Supplier -->
            <td width="50%">
                <div class="section-title">Supplier</div>
                <div class="info">
                    <?= $branch_name ?><br>
                    <?= $branch_contact ?><br>
                    <?= $branch_address ?><br>
                    <?= $branch_location ?>
                </div>
            </td>
            <!-- Buyer -->
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
        <tr>
            <td width="50%"></td>
        </tr>
    </table>
    <table class="party-table">
         <tr>
            <td width="50%">
                Project name :<strong><?= $project_name ?></strong>
            </td>
            <td width="50%"> Location :<strong><?= $project_location?></strong></td>
         </tr>
    </table>

    <!-- Product Table -->
    <table class="products">
        <thead>
            <tr>
                <th>#</th>
                <th>Reference</th>
                <th>Product</th>
                <th>Unit</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1;
            foreach ($quotation_products as $products) { ?>
            <tr>
                <td><?= $i ?></td>
                <td>
                    <?php if (!empty($products['item_image'])) { ?>
                        <img src="<?= base_url() ?>public/items/<?= $products['item_image'] ?>" width="50" height="50">
                    <?php } ?>
                </td>
                <td style="font-weight:bold;"><?= $products['item_name'] ?></td>
                <td><?= $products['unit_name'] ?></td>
                <td><?= $products['qty'] ?></td>
                <td><?= $products['unit_price'] ?></td>
                <td><?= $products['qty'] * $products['unit_price'] ?></td>
            </tr>
            <?php $i++; } ?>
        </tbody>
    </table>

    <!-- Totals -->
    <table class="totals">
        <tr>
            <td class="label">Subtotal:</td>
            <td><?= $quotation_details['sub_total'] ?></td>
        </tr>
        <tr>
            <td class="label">Discount:</td>
            <td><?= $quotation_details['discount_amount'] ?></td>
        </tr>
        <tr>
            <td class="label">VAT:</td>
            <td><?= $quotation_details['vat_amount'] ?></td>
        </tr>
        <tr>
            <td class="label total">Grand Total:</td>
            <td class="total"><?= $quotation_details['grand_total'] ?></td>
        </tr>
    </table>

    <div style="clear: both;"></div>

<!-- Payment & Delivery Terms -->
<table class="party-table">
        <tr>
            <!-- Supplier -->
            <td width="50%">
                <div class="section-title">Payment Terms</div>
                <div class="info">
                        <?= $quotation_details['payment_term'] ?>
                </div>
            </td>
            <td width="50%">
                <div class="section-title">Delivery Terms</div>
                <div class="info">            
                    <?= $quotation_details['delivery_term'] ?>
                </div>
            </td>
    </tr>
</table>


<!-- Terms & Conditions -->
<div class="terms">
    <strong>Terms & Conditions:</strong><br>
    <?= $quotation_details['terms_condition'] ?>
</div>

    <!-- Footer -->
    <div class="footer">
        <img src="<?= $footerPath ?>" alt="Logo" width="100%">
        Thank you for giving us the opportunity to quote you.<br>
        <?= $branch_website ?>
    </div>

</body>
</html>
