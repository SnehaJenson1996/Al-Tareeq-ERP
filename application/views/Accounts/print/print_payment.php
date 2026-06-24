<?php
// PHP variables from your data (adjust as needed)
$this->load->helper('menu_helper.php');
$this->load->helper('myopeningbalance_helper.php');

$company         = $logo_details[0] ?? null;
$company_name    = $company->company_name ?? '';
$company_add1    = $company->company_address ?? '';
$company_city    = $company->company_city ?? '';
$company_pin     = $company->company_pincode ?? '';
$company_state   = $company->company_state ?? '';
$company_website = $company->company_website ?? '';
$company_email   = $company->company_email_id ?? '';

// Receipt header info
$receipt_no       = $header->voucher_code ?? '';
$receipt_date     = $header->voucher_date ?? '';
$voucher_type     = $header->voucher_type ?? '';
$cust_code        = $header->customer_id ?? '';
$customer_name    = $header->customer_name ?? '';
$total_amount     = $header->amount ?? 0;
$transaction_type = $header->transaction_type ?? '';
$transaction_no   = $header->transaction_no ?? '';
$bank_name        = $header->bank_name ?? '';
$credit_account   = $header->credit_account_name ?? '';
$remark           = $header->narration ?? '';
?>

<style>
  /* body {
    font-family: Arial, sans-serif;
    font-size: 14px;
  } */

  html,
  body {
    margin: 0;
    padding: 0;
  }

  body {
    font-family: "Franklin Gothic Book", Arial, sans-serif;
    font-size: 12px;
    color: #333;
  }
  .header-wrapper {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
  }

  /* header,
  footer {
    position: fixed;
    width: 100%;
  } */

  .logo {
    width: 120px;
  }

  .logo img {
    width: 100%;
    height: auto;
  }

  .company-info {
    flex-grow: 1;
    text-align: center;
    font-size: 12px;
    line-height: 1.2;
    font-weight: 600;
  }

  .logo-container {
    text-align: center;
    margin-bottom: 10px;
  }

  .logo-container img {
    max-width: 150px;
    height: auto;
    display: inline-block;
  }

  table {
    border-collapse: collapse;
    width: 100%;
  }

  table,
  th,
  td {
    border: 1px solid #ddd;
  }

  .header-table,
  .header-table td {
    border: none;
  }

  .header-table {
    margin-bottom: 0;
  }

  header {
    background: white;
  }

  th {
    background-color: #f0f0f0;
    text-align: center;
    padding: 8px;
  }

  td {
    padding: 8px;
  }

  .right-align {
    text-align: right;
  }

  .center-align {
    text-align: center;
  }

  .title {
    text-align: center;
    font-weight: bold;
    font-size: 18px;
    margin-top: 10px;
  }

  .footer {
      text-align: center;
      font-size: 12px;
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      width: 100%;
      margin: 0;
      padding: 0;
  }
  .footer img {
      max-width: 100%;
      height: auto;
      display: block;
      margin: 0;
  }

  .signature-block {
      margin-top: 20px;
  }

  /* .footer {
    margin-top: 60px;
  }

  .footer-wrapper {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
  } */

  .center-align {
    text-align: center;
  }

  .right-align {
    text-align: right;
  }

  @media print {
    html,
    body {
      margin: 0;
      padding: 0;
    }

    header {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      width: 100%;
      z-index: 1000;
    }

    footer {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      width: 100%;
    }

    main {
      margin-top: 130px;
      margin-bottom: 0;
      page-break-inside: avoid;
    }
  }
  .header-img {
    margin-top: 0;
  }
</style>

<!-- <header style="margin-bottom: 20px;width:90%"> -->
  <!-- <div class="header-wrapper"> -->

    <!-- <img src="<?= base_url('public/header/2.png'); ?> " width="100%" /> -->

  <!-- </div> -->

<!-- </header> -->
<!-- <br><br><br><br> -->

<!-- Header -->
<header>
  <table class="header-table" style="width:100%; border:none; border-collapse:collapse; margin-bottom:5px;">
  <tr>
    <td style="width:60%; padding:0 5px 5px 0;">
      <img src="<?= $headerPath ?>" class="header-img" style="max-height:120px;">
    </td>
    <td style="width:40%; text-align:right; padding:5px 5px 5px 5px;">
      <!-- <div style="text-align:right; color:#2e6da4;">
        <b>Invoice No:</b> <?= $invoice_master['invoice_code'] ?><br>
        <b>Date:</b> <?= date('d-M-Y', strtotime($invoice_master['invoice_date'])) ?><br>
        <b>Currency:</b> AED -->
      </div>
    </td>
  </tr>
</table>
</header>

<main>
  <h3>Payment Voucher</h3>

  <table style="margin-top: 10px;">
    <tr>
      <td><strong>No.:</strong> <?= htmlspecialchars($receipt_no); ?></td>
      <td class="right-align"><strong>Dated:</strong> <?= date('d-M-Y', strtotime($receipt_date)); ?></td>
    </tr>
    <!-- <tr>
      <td rowspan="2"><strong><?= htmlspecialchars($supplier_name); ?></strong></td>
    </tr> -->
  </table>

<?php 
$sl = 0;
$total_invoice_amount = 0;
if (!empty($payment_details) && count($payment_details) > 0) { ?>
<table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
    <thead>
        <tr style="background-color: #f0f0f0; border: 1px solid #ddd;">
            <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">SL.No</th>
            <th style="border: 1px solid #ddd; padding: 8px;">Particulars</th>
            <th style="border: 1px solid #ddd; padding: 8px;">Date</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: right;">Amount (AED)</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($payment_details as $i => $detail): 
          $sl++;
          $total_invoice_amount += $detail->invoice_amount;        
        ?>

            <tr>
                <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                    <?= $i + 1 ?>
                </td>

                <td style="border: 1px solid #ddd; padding: 8px;">
                    <?= htmlspecialchars($detail->party_name ?? $detail->account_name ?? '') ?>
                </td>

                <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                    <?= !empty($detail->invoice_date) ? date('d-M-Y', strtotime($detail->invoice_date)) : '' ?>
                </td>

                <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">
                    <?= number_format(floatval(trim($detail->invoice_amount ?? 0)), 2) ?>
                </td>
            </tr>

        <?php endforeach; ?>

        <!-- Total Row -->
        <tr style="font-weight: bold; background-color: #eaeaea;">
            <td colspan="3" style="border: 1px solid #ddd; padding: 8px; text-align: right;">
                Total
            </td>

            <td style="border: 1px solid #ddd; padding: 8px; text-align: right;">
                <?= number_format($total_invoice_amount, 2); ?>
            </td>
        </tr>

    </tbody>
</table>
<?php } else { ?>
<p>No Data Found.</p>
<?php } ?>

  <!-- <table>
      <?php
      $sl = 0;
      $total_invoice_amount = 0;
      if (!empty($payment_details)) {
        foreach ($payment_details as $detail) {
          $sl++;
          $total_invoice_amount += $detail->invoice_amount;
      ?>
          <tr>
            <td> <?= htmlspecialchars($detail->party_name ?? $detail->account_name ?? ''); ?>
              <?= !empty($detail->invoice_no) ? ' - ' . htmlspecialchars($detail->invoice_no) : ''; ?></td>
            <td class="amount-right"><?= number_format($detail->invoice_amount, 2); ?></td>
          </tr>
      <?php }
      } ?>
      <tr class="total-row">
        <td>Total</td>
        <td class="amount-right"><?= number_format($total_invoice_amount, 2); ?></td>
      </tr>
  </table> -->

  <p style="margin-top: 20px;">
    <strong>Through:</strong>
    <?= ucwords(htmlspecialchars($transaction_type)); ?>
    <?= !empty($transaction_no) ? ' - ' . htmlspecialchars($transaction_no) : ''; ?>
    <?= !empty($bank_name) ? ' (' . htmlspecialchars($bank_name) . ')' : ''; ?>
    <?= !empty($credit_account) ? ' via ' . htmlspecialchars($credit_account) : ''; ?>
  </p>

 <?php if (($transaction_type ?? '') == 'cheque'): ?>
  <p>
    <strong>Cheque Number:</strong>
    <?= htmlspecialchars($transaction_no ?? ''); ?>
  </p>
<?php endif; ?>

  <p>
    <strong>Amount in words:</strong>
    <?= function_exists('numberToWordsAED') ? numberToWordsAED($total_amount) : 'Function missing'; ?>
  </p>

  <table width="100%" style="border-collapse:collapse; table-layout:fixed; border:none;">
        <tr>

            <!-- LEFT -->
            <td style="text-align:left; vertical-align:top; width:33%; border:none;">
                <strong>Prepared By:</strong><br>

                <?php if (!empty($prepared_signature)) { ?>
                    <img src="<?= base_url('public/employee/' . $prepared_signature) ?>"
                         style="height:60px; margin-top:5px;"><br>
                <?php } ?>

                <span><?= htmlspecialchars($prepared_by_name ?? '') ?></span>
            </td>

            <!-- CENTER -->
            <td style="text-align:center; vertical-align:top; width:33%; border:none;">

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
            <td style="text-align:right; vertical-align:top; width:33%; border:none;">
                <strong>Approved By:</strong><br>

                <?php if (!empty($approved_signature)) { ?>
                    <img src="<?= base_url('public/employee/' . $approved_signature) ?>"
                         style="height:60px; margin-top:5px;"><br>
                <?php } ?>

                <span><?= htmlspecialchars($approved_by_name ?? '') ?></span>
            </td>

        </tr>
    </table>

</main>
<!-- Footer -->
<div class="footer">
    <img src="<?= $footerPath ?>">
</div>

<script>
  window.onload = function() {
    window.print();
  };
</script>