<?php 
$this->load->helper('menu_helper.php');
?>

<html>
<head>
  <title>Tax Invoice</title>

  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 13px;
  margin: 5px 5px 5px 5px;
      color: #333;
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

  .footer {
    position: fixed;
    bottom: -50px;
    left: 0;
    right: 0;
}


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
    margin: 1mm 12mm 12mm 12mm; /* top right bottom left */
}
     .header-img {
    margin-top: -2mm;
}
  </style>
</head>

<body>

<!-- HEADER -->
<table style="width:100%; border-collapse:collapse; margin-bottom:5px;">
  <tr>

    <td style="width:60%; padding:5px 5px 5px 0;">
      <img src="<?= $headerPath ?>" class="header-img" style="max-height:120px;">
    </td>

    <td style="width:40%; text-align:right; padding:5px 5px 5px 5px;">
      <div style="text-align:right; color:#2e6da4;">
        <b>Invoice No:</b> <?= $invoice_master['invoice_code'] ?><br>
        <b>Date:</b> <?= date('d-M-Y', strtotime($invoice_master['invoice_date'])) ?><br>
        <!-- <b>Currency:</b> AED -->
      </div>
    </td>

  </tr>
</table>
<div class="title">TAX INVOICE</div>

<!-- MAIN TABLE -->
<table cellpadding="2" cellspacing="0" border="1" width="100%"
style="border-collapse: collapse; table-layout:fixed; font-size:12px; margin-top:3px;">

  <tbody>

    <!-- TOP -->
    <tr>
      <td rowspan="3" width="50%" valign="top" style="padding:4px; line-height:16px;">
        <strong>Company:</strong><br>
        <?= htmlspecialchars($branch_name) ?><br>
        Location: <?= htmlspecialchars($branch_location) ?><br>
TRN: <?= htmlspecialchars($branch_trn ?? '') ?><br>        E-Mail: <?= htmlspecialchars($branch_email) ?>
      </td>

      <td width="25%" valign="top" style="padding:4px;">
        <strong>Invoice No:</strong><br>
        <?= htmlspecialchars($invoice_master['invoice_code']) ?>
      </td>

      <td width="25%" valign="top" style="padding:4px;">
        <strong>Dated:</strong><br>
        <?= date('d-M-Y', strtotime($invoice_master['invoice_date'])) ?>
      </td>
    </tr>

    <!-- PAYMENT -->
    <tr>
      <td colspan="2" style="padding:4px;">
        <strong>Payment Terms:</strong><br>
        <ol style="margin:0; padding-left:14px; line-height:15px;">
          <?php 
          foreach (explode("\n", $invoice_master['payment_term']) as $term) {
            $term = trim($term);
            if (!empty($term)) {
              echo '<li>' . htmlspecialchars($term) . '</li>';
            }
          }
          ?>
        </ol>
      </td>
    </tr>

    <!-- REFERENCES -->
   <tr>
  <td style="padding:4px;">
    <strong>Supplier Ref:</strong><br>
    <?= htmlspecialchars($invoice_master['supplier_ref'] ?? '') ?>
  </td>

  <td style="padding:4px;">
    <strong>Other Ref:</strong><br>
    <?= htmlspecialchars($invoice_master['other_reference'] ?? '') ?>
  </td>
</tr>

    <!-- BUYER + ORDER -->
    <tr>
      <td rowspan="2" width="50%" valign="top" style="padding:4px; line-height:16px;">
        <strong>Buyer:</strong><br>
        <?= htmlspecialchars($customer_name) ?><br>
        <?= htmlspecialchars($customer_address) ?><br>
        <?= htmlspecialchars($emirate) ?><br>
        TRN: <?= htmlspecialchars($customer_trn) ?><br>
        E-Mail: <?= htmlspecialchars($customer_email) ?>
      </td>

      <td style="padding:4px;">
        <strong>Buyer's Order No:</strong><br>
        <?= htmlspecialchars($invoice_master['buyers_order_no'] ?? '') ?>
      </td>

      <td style="padding:4px;">
        <strong>Dated:</strong><br>
        <?= htmlspecialchars($invoice_master['buyers_order_date'] ?? '') ?>
      </td>
    </tr>

    <!-- DELIVERY -->
    <tr>
      <td colspan="2" style="padding:4px;">
        <strong>Delivery Terms:</strong><br>
        <ol style="margin:0; padding-left:14px; line-height:15px;">
          <?php 
          foreach (explode("\n", $invoice_master['delivery_term']) as $term) {
            $term = trim($term);
            if (!empty($term)) {
              echo '<li>' . htmlspecialchars($term) . '</li>';
            }
          }
          ?>
        </ol>
      </td>
    </tr>

  </tbody>
</table>
<!-- ITEMS -->
<table class="items-table">
  <thead>
    <tr>
      <th>Sl No.</th>
      <th>Product</th>
       <th>Description</th>
      <th>Qty</th>
      <th>Unit</th>
      <th>Unit Price</th>
      <!-- <th>Discount</th> -->
      <th>Amount</th>
      
      <!-- <th>Taxable</th> -->
    </tr>
  </thead>
  <tbody>
    <?php $i = 1;
    foreach ($invoice_products as $detail): ?>
      <tr>
        <td style="text-align:center;"><?= $i ?></td>
        <td><?= $detail['item_name'] ?></td>
        <td><?= $detail['product_description'] ?></td>

        <td style="text-align:center;"><?= $detail['deliver_quantity'] ?></td>
        <td style="text-align:center;"><?= $detail['unit_name'] ?></td>
        <td style="text-align:right;"><?= number_format($detail['unit_price'], 2) ?></td>
                <!-- <td style="text-align:right;"><?= number_format($detail['discount_amount'], 2) ?></td> -->

        <td style="text-align:right;"><?= number_format($detail['total_amount'], 2) ?></td>
        <!-- <td style="text-align:right;"><?= number_format($detail['taxable_amount'], 2) ?></td> -->
      </tr>
    <?php $i++; endforeach; ?>
  </tbody>
</table>

<!-- TOTALS -->
<table class="summary-table">
  <tr>
    <td width="80%">Sub Total:</td>
    <td width="20%"><?= number_format($invoice_master['sub_total'], 2) ?></td>
  </tr>
 <?php if ($invoice_master['discount_amount'] > 0): ?>
    <tr>
        <td>Additional Discount:</td>
        <td><?= number_format($invoice_master['discount_amount'], 2) ?></td>
    </tr>
    <?php endif; ?>
    <?php if ($invoice_master['advance_amount'] > 0): ?>
    <tr>
      <td>Payment/Advance Received:</td>
      <td><?= number_format($invoice_master['advance_amount'], 2) ?></td>
    </tr>
    <?php endif; ?>
    <?php if ($invoice_master['retention_amount'] > 0): ?>    
    <tr>
      <td>Retention:</td>
      <td><?= number_format($invoice_master['retention_amount'], 2) ?></td>
    </tr>
    <?php endif; ?>
    <?php if ($invoice_master['vat_amount'] > 0): ?>
    <tr>
      <td>VAT (<?= $invoice_master['vat_per'] ?? 5 ?>%):</td>
      <td><?= number_format($invoice_master['vat_amount'], 2) ?></td>
    </tr>
    <?php endif; ?>
  <tr>
    <td><b>Grand Total:</b></td>
    <td><b style="color:#2e6da4;"><?= number_format($invoice_master['grand_total'], 2) ?></b></td>
  </tr>
</table>

<!-- AMOUNT IN WORDS -->
<div style="margin-top:10px;">
  <strong>Amount in Words:</strong>
  <?= numberToWordsAED($invoice_master['grand_total']); ?>
</div>

<!-- BANK -->
<!-- BANK -->
<?php if (!empty($invoice_bank_data) && !empty($invoice_bank_data->bank_name)) : ?>

  <div style="margin-top:10px; width:100%; text-align:right;">

    <div style="font-weight:bold; margin-bottom:6px; color:#2e6da4;">
      Company's Bank Details
    </div>

    <div style="display:inline-block; text-align:left; line-height:18px;">

      <div><strong style="display:inline-block; width:110px;">Bank Name</strong>: <?= $invoice_bank_data->bank_name ?></div>

      <div><strong style="display:inline-block; width:110px;">Account No</strong>: <?= $invoice_bank_data->bank_account ?></div>

      <div><strong style="display:inline-block; width:110px;">IBAN</strong>: <?= $invoice_bank_data->bank_iban ?></div>

      <div><strong style="display:inline-block; width:110px;">Swift Code</strong>: <?= $invoice_bank_data->bank_swift ?></div>

    </div>

  </div>

<?php endif; ?>
<div style="margin-top:15px; font-size:12px;">
  <strong><u>Declaration<u></strong><br><br>
  We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.
</div>
<div style="width:100%; margin-top:40px;">

    <div style="text-align:right;">
        for AL ADEL AUTOMATIC DOORS TR LLC
    </div>

    <br><br>

    <table width="100%" style="border-collapse:collapse; table-layout:fixed;">
        <tr>

            <!-- LEFT -->
            <td style="text-align:left; vertical-align:top; width:33%;">
                <strong>Prepared By:</strong><br>

                <?php if (!empty($prepared_signature)) { ?>
                    <img src="<?= base_url('public/employee/' . $prepared_signature) ?>"
                         style="height:60px; margin-top:5px;"><br>
                <?php } ?>

                <span><?= htmlspecialchars($prepared_by_name ?? '') ?></span>
            </td>

            <!-- CENTER -->
            <td style="text-align:center; vertical-align:top; width:33%;">

                <?php if (!empty($branch_stamp)) { ?>
                  <?php
$path = FCPATH . ltrim($branch_stamp, './');

$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
?>

<img src="<?= $base64 ?>" style="max-width:200px; max-height:140px;">
                <?php } ?>
            </td>

            <!-- RIGHT -->
            <td style="text-align:right; vertical-align:top; width:33%;">
                <strong>Received By:</strong><br>

                <!-- <?php if (!empty($received_signature)) { ?>
                    <img src="<?= base_url('public/employee/' . $received_signature) ?>"
                         style="height:60px; margin-top:5px;"><br>
                <?php } ?>

                <span><?= htmlspecialchars($received_by_name ?? '') ?></span> -->
            </td>

        </tr>
    </table>

</div>
              
<!-- FOOTER -->
<div class="footer">
  <img src="<?= $footerPath ?>">
</div>

<script>
window.onload = function () {
  window.print();
}
</script>

</body>
</html>