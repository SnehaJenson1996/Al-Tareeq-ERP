<?php $this->load->helper('menu_helper.php'); ?>
<html>
<head>
  <title>Tax Invoice</title>
  <style>
    body { 
        font-family: Arial, sans-serif; 
        font-size: 13px; 
        margin: 20px; 
        color: #333; 
    }

    /* Header */
    .header { 
        width: 100%; 
        border-bottom: 3px solid #2e6da4; /* Dark blue border under header */
        padding-bottom: 10px; 
        margin-bottom: 20px; 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
    }
    .header img { max-width: 200px; }

    /* Title */
    .title { 
        text-align: center; 
        font-size: 20px; 
        font-weight: bold; 
        margin: 15px 0; 
        color: #2e6da4; /* Matches header line */
    }

    /* Tables */
    .info-table, .items-table, .summary-table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-top: 15px; 
    }

    .info-table td, 
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

    .items-table td { vertical-align: top; }

    .summary-table td { text-align: right; }
    .summary-table tr td:first-child { background: #f9f9f9; }

    /* Section Title */
    .section-title { 
        font-weight: bold; 
        margin-top: 20px; 
        color: #2e6da4; 
        border-bottom: 1px solid #2e6da4; 
        padding-bottom: 3px;
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

    /* Buyer / Delivery Info Headings */
.info-heading {
  background: #2e6da4;   /* same blue as header */
  color: #fff;
  font-weight: bold;
  text-align: center;
  padding: 6px;
  border: 1px solid #2e6da4;
}

    @media print {
        .header, .footer { page-break-inside: avoid; }
    }
  </style>
</head>
<body>

  <!-- Header -->
  <table style="width:100%; border-collapse:collapse; margin-bottom:5px;">
    <tr>
      <td style="width:100%; vertical-align:middle;">
        <img src="<?= $headerPath ?>" alt="Company Logo" style="width:100%; height:auto;">
      </td>
    </tr>
  </table>

  <div style="text-align:right; color:#2e6da4;">
    <b>Invoice No:</b> <?= $invoice_master['invoice_code'] ?><br>
    <b>Date:</b> <?= date('d-M-Y', strtotime($invoice_master['invoice_date'])) ?><br>
    <b>Currency:</b> AED
  </div>

 <!-- Customer / Delivery Info -->
<table class="info-table">
  <tr>
    <td width="100%" class="info-heading">Buyer</td>
    <!-- <td width="50%" class="info-heading">Delivery Info</td> -->
  </tr>
  <tr>
    <td>
      <?= $customer_name ?><br>
      <?= $customer_address ?><br>
      Emirate: <?= $emirate ?><br>
      Email: <?= $customer_email ?><br>
      TRN: <?= $customer_trn ?>
    </td>
    <!-- <td>
      Mode: <?= $delivery_challan_data['delivery_mode'] ?><br>
      Delivered By: <?= $delivery_challan_data['deliverd_by'] ?><br>
      Delivery Terms: <?= $invoice_master['delivery_term'] ?><br>
    </td> -->
  </tr>
</table>
  <!-- Items -->
  <table class="items-table">
    <thead>
      <tr>
        <th>#</th>
        <th>Product</th>
        <th>Qty</th>
        <th>Unit</th>
        <th>Unit Price</th>
        <th>Amount</th>
        <th>Discount</th>
        <th>Taxable</th>
      </tr>
    </thead>
    <tbody>
      <?php $i=1; foreach($invoice_products as $detail): ?>
        <tr>
          <td style="text-align:center;"><?= $i ?></td>
          <td><?= $detail['item_name'] ?></td>
          <td style="text-align:center;"><?= $detail['deliver_quantity'] ?></td>
          <td style="text-align:center;"><?= $detail['unit_name'] ?></td>
          <td style="text-align:right;"><?= number_format($detail['unit_price'], 2) ?></td>
          <td style="text-align:right;"><?= number_format($detail['total_amount'], 2) ?></td>
          <td style="text-align:right;"><?= number_format($detail['discount_amount'], 2) ?></td>
          <td style="text-align:right;"><?= number_format($detail['taxable_amount'], 2) ?></td>
        </tr>
      <?php $i++; endforeach; ?>
    </tbody>
  </table>

  <!-- Totals -->
  <table class="summary-table">
    <tr>
      <td width="80%">Sub Total:</td>
      <td width="20%"><?= number_format($invoice_master['sub_total'], 2) ?></td>
    </tr>
    <tr>
      <td>Additional Discount:</td>
      <td><?= number_format($invoice_master['discount_amount'], 2) ?></td>
    </tr>
    <tr>
      <td>VAT (<?= $invoice_master['vat_per'] ?? 5 ?>%):</td>
      <td><?= number_format($invoice_master['vat_amount'], 2) ?></td>
    </tr>
    <tr>
      <td><b>Grand Total:</b></td>
      <td><b style="color:#2e6da4;"><?= number_format($invoice_master['grand_total'], 2) ?></b></td>
    </tr>
  </table>
  
  <!-- <div class="section-title"></div> -->
  <?= numberToWords($invoice_master['grand_total']); ?><br>
  <!-- VAT in Words: <?= numberToWords($invoice_master['vat_amount']); ?> -->

  <!-- Bank & Notes -->
  <div class="section-title">Bank Details</div>
  Bank Name: <?= $invoice_bank_data->bank_name ?><br>
  Account No: <?= $invoice_bank_data->bank_account ?><br>
  IBAN: <?= $invoice_bank_data->bank_iban ?><br>
  Swift: <?= $invoice_bank_data->bank_swift ?><br><br>


  <!-- Footer -->
  <div class="footer">
    <img src="<?= $footerPath ?>" alt="Footer Logo">
    Thank you for your business.<br>
    <?= $branch_website ?>
  </div>

  <script>
    window.onload = function(){ window.print(); }
  </script>
</body>
</html>
