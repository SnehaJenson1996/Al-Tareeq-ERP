<?php
$this->load->helper('menu_helper.php');

// Company details
$company = $comapny_records; 

// Quotation details
$record = $records1[0] ?? null;
$amc_discount = $record->amc_discount ?? 0;
$quotation_date = !empty($record->revision_date) 
    ? date('d-M-Y', strtotime($record->revision_date)) 
    : date('d-M-Y', strtotime($record->quotation_date));
?>

<html>
<head>
    <title>AMC Quotation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            margin: 0 25px;   /* left right space */
            padding: 0;
            color: #333;
            margin-top: 0px;
        }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #BFC9CA; }
        .text-right { text-align: right; }
        .no-border,
        .no-border th,
        .no-border td {
            border: none !important;
        }

        .page-break {
            page-break-before: always;
            clear: both;
        }

        .footer {
            position: fixed;
            bottom: -100px;
            left: 0;
            right: 0;
        }
        .footer img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        /* Running Header Setup */
        .header {
            position: fixed;
            top: -120px;
            left: 0;
            right: 0;
            height: 120px;
        }

        .cover-page {
            page-break-after: always;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            margin: 0 !important;
            padding: 0 !important;
            box-sizing: border-box;
        }

        .cover-img {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
        }

        .cover-img img {
            width: 100%;
            height: 100%;
            object-fit: fill;
            display: block;
        }
        
        .cover-details {
            position: absolute;
            top: 200px;
            left: 45px;
            right: 45px;
            z-index: 2;
            color: #000;
            line-height: 1.8;
        }
        
        @page {
            margin-top: 140px;
            margin-right: 20px;
            margin-bottom: 110px;
            margin-left: 20px;
        }

        /* Zero out margins and HIDE the fixed header on the very first page */
        @page :first {
            margin: 0 !important;
            .header {
                display: none !important;
                opacity: 0 !important;
                top: -1000px !important; 
            }
        }

        @media print {
            body {
                margin: 0 !important;
                padding: 0 !important;
            }
            .main-content {
                margin-top: 0;
            }
        } 
    </style>
</head>
<body>

    <div class="header">
        <table class="no-border" style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="width:60%; padding:0 10px;">
                    <img src="<?= $headerPath ?>" style="max-height:120px;">
                </td>
                <td style="width:40%; text-align:right; font-size:13px; font-family: Arial, sans-serif; padding-right:10px;">
                    <b>Ref No:</b> <?= $record->quotation_code ?><br>
                    <b>Date:</b> <?= $quotation_date ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="cover-page">
        <div class="cover-img">
            <img src="<?= base_url('public/quotation_cover/Quotation_cover_page.jpeg') ?>">
        </div>

        <div class="cover-details">
            <h3><?= $company['company_name'] ?></h3>
            <?= nl2br($company['company_address']) ?>
            <?= $company['company_state'] ?>, <?= $company['company_country'] ?><br>
            <?= $company['company_telephone'] ?>

            <?php if(!empty($company['company_telephone_alt'])) { ?>
                 <?= $company['company_telephone_alt'] ?>
            <?php } ?>
            <br>
            <a href="mailto:<?= $company['company_email_id'] ?>" style="color: #0066cc; text-decoration: underline; font-weight: normal;"><?= $company['company_email_id'] ?></a><br>
            <a href="https://<?= str_replace(['http://', 'https://'], '', $company['company_website']) ?>" target="_blank" style="color: #0066cc; text-decoration: underline; font-weight: normal;"><?= $company['company_website'] ?></a>
        </div>
    </div>

    <div class="main-content">
        <h2 align="center">ANNUAL MAINTENANCE PROPOSAL</h2>

        <table width="100%" border="1" cellspacing="0" cellpadding="6" style="border-collapse:collapse; font-size:13px; margin-top:10px;">
            <tr>
               <td width="15%"><b>To</b></td>
                <td width="35%">
                    <b><?php echo $record->customer_name ?? ''; ?></b><br>
                    <b>Contact Person:</b><?= $record->contact_name ?><br>
                    <b>Phone:</b>  <?php echo $record->contact_number ?? ''; ?><br>
                    <b>Email:</b> <?php echo $record->customer_email ?? ''; ?>
                </td>
                <td width="15%"><b>From</b></td>
                <td width="35%">
                    Al Adel Automatic Doors Tr. L.L.C.
                </td>
            </tr>
            <tr>
                <td><b>Project Location</b></td>
                <td><?= $record->project_location ?></td>
                <td><b>Subject</b></td>
                <td><?= $record->subject ?></td>
            </tr>
        </table>

        <p style="margin-top:10px; font-size:13px; text-align:justify;">
            The maintenance contract for the above project as discussed, please read the following details:
        </p>

        <p style="margin-top:10px; font-size:14px; font-weight:bold; text-decoration:underline;">
            SCOPE OF WORK
        </p>
        <p style="margin-top:5px; font-size:13px; text-align:justify;">
            <?= nl2br($record->scope_work); ?>
        </p>
        <p style="margin-top:10px; font-size:14px; font-weight:bold; text-decoration:underline;">
            NUMBER OF VISITS
        </p>
        <p style="margin-top:5px; font-size:13px; text-align:justify;">
            <?= nl2br($record->ppm_details); ?>
        </p>

        <?php if(!empty($sla_records)) { ?>
        SLA Response Time
        <table width="100%" border="1" cellspacing="0" cellpadding="6" style="border-collapse:collapse; font-size:13px; margin-top:10px;">
            <tr style="background:#2e6da4; color:#fff; font-weight:bold; text-align:center;">
                <td>SERVICE ITEM</td>
                <td>SERVICE AVAILABILITY</td>
                <td>RESPONSE TIME</td>
                <td>RESTORATION TIME</td>
                <td>RESOLUTION TIME</td>
            </tr>
            <?php foreach($sla_records as $s) { ?>
            <tr>
                <td><?php echo $s->service_item; ?></td>
                <td><?php echo $s->service_availability_period; ?></td>
                <td><?php echo $s->response_time; ?></td>
                <td><?php echo $s->restoration_time; ?></td>
                <td><?php echo $s->resolution_time; ?></td>
            </tr>
            <?php } ?>
        </table>
        <?php if(!empty($sla_records)) { ?>
            <div class="page-break"></div>
        <?php } ?>
        <?php } ?>

        <br><br>
        Our offer for Annual maintenance is as follows:
        <br>

        <p style="margin-top:15px; font-size:14px; font-weight:bold; text-decoration:underline;">
            AMC COST DETAILS
        </p>

        <table width="100%" border="1" cellspacing="0" cellpadding="6" style="border-collapse:collapse; font-size:13px; margin-top:10px;">
            <tr style="background:#2e6da4; color:#fff; text-align:center; font-weight:bold;">
                <th>SYSTEM</th>
                <th>PRICE (AED)</th>
                <th>QTY</th>
                <?php
                $period_count = 0;
                if ($quotation_info->contract_type == 'Yearly') {
                    $period_count = $quotation_info->no_of_years;
                    for ($i = 1; $i <= $period_count; $i++) {
                        echo "<th>Total Price {$i} Year</th>";
                    }
                } else {
                    $period_count = $quotation_info->no_of_quarters;
                    for ($i = 1; $i <= $period_count; $i++) {
                        echo "<th>Q{$i}</th>";
                    }
                }
                ?>
                <th>FINAL TOTAL</th>
            </tr>

            <?php
            $grand_total = 0;
            foreach ($records2 as $row2) {
                $rate = $row2->price;
                $qty  = $row2->quantity;
                $base_total = $rate * $qty;
                $row_total  = $base_total * $period_count;
                $grand_total += $row_total;
            ?>
                <tr>
                    <td><?= $row2->product_id ?></td>
                    <td style="text-align:right;"><?= number_format($rate,2) ?></td>
                    <td style="text-align:center;"><?= $qty ?></td>
                    <?php for ($p = 1; $p <= $period_count; $p++) { ?>
                        <td style="text-align:right;">
                            <?= number_format($base_total, 2) ?>
                        </td>
                    <?php } ?>
                    <td style="text-align:right; font-weight:bold;">
                        <?= number_format($row_total, 2) ?>
                    </td>
                </tr>
            <?php } ?>
            <tr style="font-weight:bold;">
                <td colspan="<?= 3 + $period_count ?>" style="text-align:right;">Sub Total</td>
                <td style="text-align:right;">
                    <?= number_format($record->sub_total, 2) ?>
                </td>
            </tr>

            <?php if (!empty($record->amc_discount) && $record->amc_discount > 0) { ?>
            <tr style="font-weight:bold;">
                <td colspan="<?= 3 + $period_count ?>" style="text-align:right;">AMC Discount</td>
                <td style="text-align:right;">
                    - <?= number_format($record->amc_discount, 2) ?>
                </td>
            </tr>
            <?php } ?>
            <?php if (!empty($record->discount_amt) && $record->discount_amt > 0) { ?>
            <tr style="font-weight:bold;">
                <td colspan="<?= 3 + $period_count ?>" style="text-align:right;">
                    Discount (<?= $record->discount_percent ?>%)
                </td>
                <td style="text-align:right;">
                    - <?= number_format($record->discount_amt, 2) ?>
                </td>
            </tr>
            <?php } ?>
            <?php if (!empty($record->vat_amt) && $record->vat_amt > 0) { ?>
            <tr style="font-weight:bold;">
                <td colspan="<?= 3 + $period_count ?>" style="text-align:right;">
                    VAT (<?= $record->vat_percent ?>%)
                </td>
                <td style="text-align:right;">
                    <?= number_format($record->vat_amt, 2) ?>
                </td>
            </tr>
            <?php } ?>
            <tr style="background:#f2f2f2; font-weight:bold;">
                <td colspan="<?= 3 + $period_count ?>" style="text-align:right;">
                    GRAND TOTAL
                </td>
                <td style="text-align:right;">
                    <?= number_format($record->grand_total, 2) ?>
                </td>
            </tr>
        </table>

        <p style="margin-top:10px; font-size:14px; font-weight:bold; text-decoration:underline;">
            Payment Terms:
        </p>
        <p style="margin-top:5px; font-size:13px; text-align:justify;">
            <?= nl2br($record->payment_term); ?>
        </p>
        <br><br>
        <table width="100%" style="font-size:13px; margin-top:20px; border:none;">
            <tr>
                <td style="width:50%; text-align:left;border:none;">
                    _______________________<br><br>
                    <b>Authorized Personnel Signature</b>
                </td>
                <td style="width:50%; text-align:right;border:none;">
                    <b>Date:</b> <?= date('d-m-Y') ?>
                </td>
            </tr>
        </table>
        <br><br>
        <p style="margin-top:10px; font-size:14px; font-weight:bold; text-decoration:underline;">
            ACCEPTANCE
        </p>
        The above prices, Specifications and conditions are satisfactory and here by accepted. You are
        authorized to specify the work as specified. Payment will be made as outlined above.

        <br><br>
        <table width="100%" style="font-size:13px; margin-top:20px; border:none;">
            <tr>
                <td style="width:50%; text-align:left;border:none;">
                    _______________________<br><br>
                    <b>Customer Signature</b>
                </td>
                <td style="width:50%; text-align:right;border:none;">
                    ______________<br><br>
                    <b>Date of Acceptance</b>
                </td>
            </tr>
        </table>

        <?php if(!empty($annexure_records)) { ?>
        <div style="page-break-before: always;"></div>
        <h3 style="text-align:center; margin-bottom:20px;">
            ANNEXURE
        </h3>

        <table width="100%" border="1" cellspacing="0" cellpadding="6" style="border-collapse:collapse; font-size:13px;">
            <tr style="background:#2e6da4; color:#fff; font-weight:bold; text-align:center;">
                <td width="10%">Sl No</td>
                <td width="35%">Type</td>
                <td width="35%">Location</td>
                <td width="20%">Quantity</td>
            </tr>
            <?php 
            $total_qty = 0;
            foreach($annexure_records as $a) { 
                $total_qty += $a->quantity;
            ?>
            <tr>
                <td style="text-align:center;">
                    <?= $a->sl_no ?>
                </td>
                <td><?= $a->type ?></td>
                <td><?= $a->location ?></td>
                <td style="text-align:center;">
                    <?= $a->quantity ?>
                </td>
            </tr>
            <?php } ?>
            <tr style="font-weight:bold; background:#f2f2f2;">
                <td colspan="3" style="text-align:right;">
                    Total Quantity
                </td>
                <td style="text-align:center;">
                    <?= $total_qty ?>
                </td>
            </tr>
        </table>
        <?php } ?>
    </div>

    <div class="footer">
        <img src="<?= $footerPath ?>" alt="Footer">
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(function () {
            window.focus();
            window.print();
        }, 1200);
    });
    </script>
</body>
</html>