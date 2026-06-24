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

        /* Supplier & Buyer box */
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
            /* gold */
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

        /* Product Table */
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

        table.products td img {
            display: block;
            margin: auto;
        }

        table.products tr:nth-child(even) td {
            background-color: #f9f9f9;
        }

        /* Totals Table */
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

        /* Terms & Conditions */
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

            .header,
            .footer {
                page-break-inside: avoid;
            }
        }
    </style>

    <style>
        @page {
            margin: 0;
            /* remove default page margins */
        }

        body {
            margin: 0;
            padding: 0;
        }

        .cover-page {
            width: 100%;
            height: 100%;
            /* full viewport height */
            page-break-after: always;
            position: relative;
        }

        .cover-page img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* scale image to fill page without stretching */
            display: block;
        }

        .main-content {
            /* space from page edges */
            padding: 15px;
            /* space inside the border */
            border: 2px solid #2C2C2C;
            /* border color and thickness */
            border-radius: 5px;
            /* optional rounded corners */
            /* page-break-before: always;
            /* ensure it starts on new page */
        }
    </style>
</head>

<body>
    <!-- COVER PAGE -->
    <div class="cover-page">
        <img src="<?= base_url('public/quotation_cover/Quotation cover page1.JPG') ?>" alt="Cover Page">
    </div>


    <!-- Header Logo -->
    <table style="width:100%; border-collapse:collapse;">
        <tr>
            <td style="width:100%; vertical-align:middle;">
                <img src="<?= $headerPath ?>" alt="Company Logo" style="width:100%; ">
            </td>
        </tr>
    </table>

    <!-- Quotation Title & Details -->
    <table style="width:100%; border-collapse:collapse; margin-bottom:15px;">
        <tr>
            <td style="width:100%; text-align:right; vertical-align:middle; padding:10px;">
                <div style="font-size:20px; font-weight:bold; color:#2C2C2C;">Sales Quotation</div>
                <div style="font-size:12px;"><?= $quotation_details['quotation_code'] ?></div>
                <div style="font-size:12px;"><?= date('d-m-Y', strtotime($quotation_details['quotation_date'])) ?></div>
            </td>
        </tr>
    </table>

    <!-- Supplier / Buyer -->
    <table class="party-table" style="width:100%; border-collapse:collapse;">
        <tr>
            <td width="50%" style="border:1px solid #000; padding:10px;">
                <div class="section-title">Supplier</div>
                <div class="info">
                    <?= $branch_name ?><br>
                    <?= $branch_contact ?><br>
                    <?= $branch_address ?><br>
                    <?= $branch_location ?>
                </div>
            </td>

            <td width="50%" style="border:1px solid #000; padding:10px;">
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

    <div class="main-content">
        <!-- Products -->
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
                    <th>Discount</th>
                    <th>Taxable</th>
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
                        <td style="font-weight:bold;">
                            <?= $products['item_name'] ?><br>
                            <?= $products['prd_description'] ?>
                        </td>
                        <td><?= $products['unit_name'] ?></td>
                        <td><?= number_format($products['qty'], 2) ?></td>
                        <td><?= number_format($products['unit_price'], 2) ?></td>
                        <td><?= number_format($products['qty'] * $products['unit_price'], 2) ?></td>
                        <td><?= number_format($products['dicount_amount'], 2) ?></td>
                        <td><?= number_format($products['taxable_amount'], 2) ?></td>
                    </tr>
                <?php $i++;
                } ?>
            </tbody>
        </table>

        <!-- Totals -->
        <table class="totals">
            <tr>
                <td class="label">Subtotal:</td>
                <td><?= number_format($quotation_details['sub_total'], 2) ?></td>
            </tr>
            <tr>
                <td class="label">Discount:</td>
                <td><?= number_format($quotation_details['discount_amount'], 2) ?></td>
            </tr>
            <tr>
                <td class="label">VAT:</td>
                <td><?= number_format($quotation_details['vat_amount'], 2) ?></td>
            </tr>
            <tr>
                <td class="label total">Grand Total:</td>
                <td class="total"><?= number_format($quotation_details['grand_total'], 2) ?></td>
            </tr>
        </table>

        <div style="clear: both;"></div>
    </div>
    <!-- Terms -->
    <div class="terms-title">Terms and Conditions</div>
    <table class="terms-table">
        <tr>
            <td>Validity</td>
            <td><?= $quotation_details['validity'] ?></td>
        </tr>
        <tr>
            <td>Payment terms</td>
            <td><?= $quotation_details['payment_term'] ?></td>
        </tr>
        <tr>
            <td>Delivery terms</td>
            <td><?= $quotation_details['delivery_term'] ?></td>
        </tr>
        <tr>
            <td>Other Conditions</td>
            <td><?= $quotation_details['terms_condition'] ?></td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <img src="<?= $footerPath ?>" alt="Logo">
        Thank you for giving us the opportunity to quote you.<br>
        <?= $branch_website ?>
    </div>

</body>

</html>