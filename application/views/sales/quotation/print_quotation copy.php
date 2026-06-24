<!DOCTYPE html>
<html>
<head>
    <title>Quotation</title>
    <link rel="stylesheet" href="<?= base_url('public/assets/quotation_print_styles.css') ?>" />
    <style>
        body {
            font-family: "Franklin Gothic Book", "Franklin Gothic Medium", Arial, sans-serif;
            font-size: 13px;
            text-align: center;
        }

        .gray-row {
            background-color: #e8e8e8;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
        }

        table {
            border-collapse: collapse;
        }

        

        .terms {
            background-color: #656b68ff;
            color: #e8b41a;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <table width="100%" style="border: 0;">
        <!-- Header Image -->
        <thead>
            <tr>
                <th>
                    <img src="<?= $headerPath ?>" alt="Header Image" width="100%">
                </th>
            </tr>
        </thead>

        <tbody id="table-body">
            <!-- Supplier & Buyer -->
            <tr>
                <td>
                    <table cellpadding="5" border="0" width="100%">
                        <tr>
                            <!-- Supplier -->
                            <td width="50%">
                                <table width="100%" style="border-collapse: collapse; border:0; font-size:12px;">
                                    <tr><td style="text-transform: uppercase;">Supplier</td></tr>
                                    <tr><td style="text-transform: uppercase;"><?= $branch_name ?></td></tr>
                                    <tr><td style="text-transform: uppercase;"><?= $branch_contact ?></td></tr>
                                    <tr><td style="text-transform: uppercase;"><?= $branch_address ?></td></tr>
                                    <tr><td style="text-transform: uppercase;"><?= $branch_location ?></td></tr>
                                </table>
                            </td>

                            <!-- Buyer -->
                            <td width="50%">
                                <table width="100%" style="border-collapse: collapse; border:0; font-size:12px;">
                                    <tr><td style="text-transform: uppercase;">Buyer</td></tr>
                                    <tr><td  style="text-transform: uppercase;"><?= $customer_name ?></td></tr>
                                    <tr><td style="text-transform: uppercase;"><?= $contact_number ?></td></tr>
                                    <tr><td style="text-transform: uppercase;"><?= $customer_email ?></td></tr>
                                    <tr><td style="text-transform: uppercase;"><?= $customer_address ?></td></tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Separator -->
            <tr class="calc" height="5px" style="background-color: #525453;">
                <td></td>
            </tr>

            <!-- Prepared by / Project Details -->
            <tr class="calc">
                <td>
                    <table cellpadding="0" border="0" width="100%" style="border-collapse: collapse; font-size:10px;">
                        <tr>
                            <td>Prepared by: <?= $branch_name ?></td>
                            <!-- <td>Sales Person: <?= $sales_person ?></td> -->
                            <td>Project Details: <?= $project_name ?></td>
                            <td><?= $project_location ?></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Product Table -->
            <tr>
                <td>
                    <table style="width:100%; border-collapse:collapse; border:1px solid #000; font-size:12px;">
                    <thead>
                        <tr style="background-color: #656b68ff; color: #e8b41a; font-weight: bold;">
                            <th >#</th>
                            <th >Reference</th>
                            <th >Product</th>
                            <th >Unit</th>
                            <th >Qty</th>
                            <th >Price</th>
                            <th >Amount</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:10px;">
                        <?php 
                        $i = 1;
                        foreach ($quotation_products as $products) { ?>
                        <tr style="background-color:#e8e8e8; border:1px solid #000;text-align: center;">
                            <td ><?= $i ?></td>
                            <td >
                                <?php if (!empty($products['item_image'])) { ?>
                                    <img src="<?= base_url() ?>public/items/<?= $products['item_image'] ?>" width="50" height="50">
                                <?php } ?>
                            </td>
                            <td style=" font-weight:bold;"><?= $products['item_name'] ?></td>
                            <td><?= $products['unit_name'] ?></td>
                            <td><?= $products['qty'] ?></td>
                            <td><?= $products['unit_price'] ?></td>
                            <td><?= $products['qty'] * $products['unit_price'] ?></td>
                        </tr>
                        <?php $i++; } ?>
                    </tbody>
                    <tfoot>
                        <tr style="background-color:#e8e8e8; text-align:center; font-weight:bold; border:1px solid #000;">
                            <td colspan="6" style="border:1px solid #000;">Subtotal</td>
                            <td style="border:1px solid #000;"><?= $quotation_details['sub_total'] ?></td>
                        </tr>
                        <tr style="background-color:#e8e8e8; text-align:center; font-weight:bold; border:1px solid #000;">
                            <td colspan="6" style="border:1px solid #000;">Discount</td>
                            <td style="border:1px solid #000;"><?= $quotation_details['discount_amount'] ?></td>
                        </tr>
                        <tr style="background-color:#e8e8e8; text-align:center; font-weight:bold; border:1px solid #000;">
                            <td colspan="6" style="border:1px solid #000;">VAT</td>
                            <td style="border:1px solid #000;"><?= $quotation_details['vat_amount'] ?></td>
                        </tr>
                        <tr style="background-color:#e8e8e8; text-align:center; font-weight:bold; border:1px solid #000;">
                            <td colspan="6" style="border:1px solid #000;">Grand Total</td>
                            <td style="border:1px solid #000;"><?= $quotation_details['grand_total'] ?></td>
                        </tr>
                    </tfoot>
                </table>
                </td>
            </tr>

            

            <!-- Terms & Conditions -->
            <tr class="calc">
                <td>
                    <table border="0" cellpadding="15" width="100%" style="border-collapse: collapse; border:0;">
                        <tr>
                            <td width="100%" >TERMS AND CONDITIONS:<?= $quotation_details['terms_condition'] ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <img src="<?= $footerPath ?>" alt="Footer Image" width="100%">
        <div>Thank you for giving us the opportunity to quote you.</div>
    </div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        window.print();
    });
</script>

</body>
</html>
