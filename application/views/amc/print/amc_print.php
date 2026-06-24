<?php
$this->load->helper('menu_helper.php');

$row = $records1;

// $enquiry_code     = $row->amc_enq_code;
// $enquiry_date     = date('d-M-Y', strtotime($row->enq_date));
$revision         = $row->revision;
$invoice_date     = date('d-M-Y', strtotime($row->invoice_date));
$project_name     = $row->project_name;
$quotation_code   = $row->invoice_code;
$customer_name    = $row->customer_name;
$cp_mobile        = $row->contact_number;
$cp_email         = $row->customer_email;
$sub_total        = $row->sub_total;
$discount_amt     = $row->discount_amt;
$vat_percent      = $row->vat_percent;
$vat_amt          = $row->vat_amt;
$grand_total      = $row->grand_total;

$amc_start_date   = date('d-m-Y', strtotime($row->amc_start_date));
$amc_end_date     = date('d-m-Y', strtotime($row->amc_end_date));

$conditions       = nl2br($row->conditions);
$exclusions       = nl2br($row->exclusions);

$sales_person     = $row->user_name;
$currabrev        = $row->currency_abbr;

$revtext = ($revision > 0) ? 'Rev -'.$revision : '';
?>
<html>
<head>
  <title>AMC Contract</title>

  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 13px;
    margin: 0 25px;   /* left right space */

    padding: 0;
      color: #333;
          margin-top: 0px;

    }

    .title {
      text-align: center;
      font-size: 20px;
      font-weight: bold;
      margin: 5px 0;
      color: #2e6da4;
    }

    .items-table,
    .summary-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 5px !important;
    }

    .items-table th,
    .items-table td,
    .summary-table td {
      border: 1px solid #ccc;
      padding: 6px;
      font-size: 12px;
    }

    .items-table th {
      background: #2e6da4;
      color: #fff;
      text-align: center;
    }

    .items-table td {
      vertical-align: top;
    }

    .summary-table td {
      text-align: right;
    }

    .summary-table tr td:first-child {
      background: #f9f9f9;
    }

    /* @page {
      margin: 5mm 10mm 10mm 10mm;
    }

    .header-img {
      margin-top: 0mm;
    } */

    

    .footer img {
      max-width: 100%;
      height: auto;
      display: block;
      margin: 0 auto;
    }

    table {
      margin-top: 5px !important;
    }
   @page {
    margin-top: 140px;
    margin-right: 20px;
    margin-bottom: 110px;
    margin-left: 20px;
}

.header {
    position: fixed;
    top: -120px;
    left: 0;
    right: 0;
}

.footer {
    position: fixed;
    bottom: -100px;
    left: 0;
    right: 0;
}

     .header-img {
    margin-top: -2mm;
}

.page-break {
    page-break-before: always;
    clear: both;
}
  </style>
</head>

<body>


<!-- HEADER -->
<div style="position: fixed; top: -120px; left: 0; right: 0; height: 120px;">
            <table style="width:100%; border-collapse:collapse;">
        <tr>
<td style="width:60%; padding:0 10px;">
                    <img src="<?= $headerPath ?>" style="max-height:120px;">
            </td>
            <td style="width:40%; text-align:right;">
                <b>Ref No:</b> <?= $quotation_code ?><br>
                <b>Date:</b> <?= $invoice_date ?>
            </td>
        </tr>
    </table>
</div>
<div style="text-align:center; margin-top:10px;">

    <div style="font-size:16px; font-weight:bold; text-transform:uppercase;">
        <?= trim($print_type_text) ?>
    </div>

    <div style="font-size:14px; font-weight:bold; margin-top:5px;">
        ANNUAL MAINTENANCE CONTRACT NO: <?= $quotation_code ?>
    </div>

    <div style="font-size:13px; margin-top:3px;">
        DATED <?= strtoupper(date('dS F Y', strtotime($invoice_date))) ?>
    </div>

</div>

<table width="100%" border="1" cellspacing="0" cellpadding="6" style="border-collapse:collapse; font-size:13px; margin-top:10px;">

    <tr>
       <td width="15%"><b>To</b></td>
<td width="35%">
    <b><?= $customer_name ?></b><br>
    <b>Contact Person:</b> <?= $row->contact_name ?><br>
    <b>Phone:</b> <?= $cp_mobile ?><br>
    <b>Email:</b> <?= $cp_email ?>
</td>

        <td width="15%"><b>From</b></td>
        <td width="35%">
            Al Adel Automatic Doors Tr. L.L.C.
        </td>
    </tr>

    <tr>
     <td><b>Project Location</b></td>
<td>
    <?= $row->project_location ?>
</td>

        <td><b>Subject</b></td>
        <td>
          <?= $row->subject ?>
        </td>
    </tr>

    <tr>
        <td><b>Period</b></td>
        <td colspan="3">
            <?= $amc_start_date ?> TO <?= $amc_end_date ?>
        </td>
    </tr>

</table>

<p style="margin-top:10px; font-size:13px; text-align:justify;">
    The maintenance contract for the above project as discussed, please read the following details:
</p>

<p style="margin-top:10px; font-size:14px; font-weight:bold; text-decoration:underline;">
    SCOPE OF WORK
</p>
<p style="margin-top:5px; font-size:13px; text-align:justify;">
    <?= nl2br($row->scope_work); ?>
</p>
<p style="margin-top:10px; font-size:14px; font-weight:bold; text-decoration:underline;">
    NUMBER OF VISITS
</p>
<p style="margin-top:5px; font-size:13px; text-align:justify;">
    <?= nl2br($row->ppm_details); ?>
   
</p>
<?php if(!empty($sla_records)) { ?>


SLA Response Time
 
 
<table width="100%" border="1" cellspacing="0" cellpadding="6" 
style="border-collapse:collapse; font-size:13px; margin-top:10px;">

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
<!-- <div style="page-break-before: always;"></div> -->

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

<table width="100%" border="1" cellspacing="0" cellpadding="6"
       style="border-collapse:collapse; font-size:13px; margin-top:10px;">

    <!-- HEADER -->
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

    <!-- BODY -->
    <?php
    $grand_total = 0;

    foreach ($records2 as $row2)
    {
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
        <?= number_format($sub_total, 2) ?>
    </td>
</tr>

    <?php if (!empty($records1->amc_discount) && $records1->amc_discount > 0) { ?>
<tr style="font-weight:bold;">
    <td colspan="<?= 3 + $period_count ?>" style="text-align:right;">AMC Discount</td>
    <td style="text-align:right;">
        - <?= number_format($records1->amc_discount, 2) ?>
    </td>
</tr>
<?php } ?>
<?php if (!empty($records1->discount_amt) && $records1->discount_amt > 0) { ?>
<tr style="font-weight:bold;">
    <td colspan="<?= 3 + $period_count ?>" style="text-align:right;">
        Discount (<?= $records1->discount_percent ?>%)
    </td>
    <td style="text-align:right;">
        - <?= number_format($records1->discount_amt, 2) ?>
    </td>
</tr>
<?php } ?>
<?php if (!empty($records1->vat_amt) && $records1->vat_amt > 0) { ?>
<tr style="font-weight:bold;">
    <td colspan="<?= 3 + $period_count ?>" style="text-align:right;">
        VAT (<?= $records1->vat_percent ?>%)
    </td>
    <td style="text-align:right;">
        <?= number_format($records1->vat_amt, 2) ?>
    </td>
</tr>
<?php } ?>
    <!-- GRAND TOTAL -->
    <tr style="background:#f2f2f2; font-weight:bold;">
        <td colspan="<?= 3 + $period_count ?>" style="text-align:right;">
            GRAND TOTAL
        </td>
        <td style="text-align:right;">
            <?= number_format($records1->grand_total, 2) ?>
        </td>
    </tr>

</table>
<p style="margin-top:10px; font-size:14px; font-weight:bold; text-decoration:underline;">
    Payment Terms:
</p>
<p style="margin-top:5px; font-size:13px; text-align:justify;">
    <?= nl2br($row->payment_term); ?>
   
</p>
<br><br>
  <table width="100%" style="font-size:13px; margin-top:20px;">
    <tr>
        <td style="width:50%; text-align:left;">
            _______________________<br><br>
            <b>Authorized Personnel Signature</b>
        </td>

        <td style="width:50%; text-align:right;">
            
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
  <table width="100%" style="font-size:13px; margin-top:20px;">
    <tr>
        <td style="width:50%; text-align:left;">
            _______________________<br><br>
            <b>Customer Signature</b>
        </td>

        <td style="width:50%; text-align:right;">
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

<table width="100%" border="1" cellspacing="0" cellpadding="6"
style="border-collapse:collapse; font-size:13px;">

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

        <td>
            <?= $a->type ?>
        </td>

        <td>
            <?= $a->location ?>
        </td>

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
