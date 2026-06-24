<!DOCTYPE html>
<html>
<head>
    <title>Estimation</title>
    <style>
        body {
            font-family: "Franklin Gothic Book", Arial, sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 0;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 20px;
        }
        .header-left .logo {
            height: 70px;
        }
        .header-right {
            text-align: right;
        }

        .title {
            text-align: center;
            margin: 20px 0;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Party Section */
        .party-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .party-table td, .party-table th {
            padding: 5px;
            text-align: left;
        }

        /* Products Table */
        table.products {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 12px;
            margin-bottom: 20px;
        }
        table.products th, table.products td {
            border: 1px solid #000;
            padding: 5px;
            word-wrap: break-word;
        }
        table.products th:nth-child(3), table.products td:nth-child(3) {
            text-align: left; /* Product description */
        }
        table.products th, table.products td {
            text-align: center;
        }

        /* Totals */
        tfoot td {
            font-weight: bold;
            text-align: right;
        }
        tfoot tr.total-row {
            background-color: #cce0ff;
            color: #003366;
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
         .header-img {
    margin-top: -2mm;
}
  @page {
    margin: 1mm 12mm 12mm 12mm; /* top right bottom left */
}
    </style>
</head>
<body>
<div class="header">
    <img src="<?= $headerPath ?>" style="max-height:120px; width:auto;">
</div>
<!-- Title -->
<div class="title">Estimation</div>

<!-- Enquiry / Customer Details -->
<table class="party-table">
    
    <tr>
        <th style="width:20%;">Enquiry No</th>
        <td style="width:30%;"><?= $enquiry_data['enquiry_code'] ?? '' ?></td>
        <th style="width:20%;">Enquiry Date</th>
        <td style="width:30%;"><?= $enquiry_data['enquiry_date'] ?? '' ?></td>
    </tr>
    <tr>
        <th>Branch</th>
        <td><?= $enquiry_data['branch_name'] ?? '' ?></td>
        <th>Customer</th>
        <td><?= $enquiry_data['customer_name'] ?? '' ?></td>
    </tr>
    <tr>
        <th>Project / Reference</th>
        <td><?= $enquiry_data['project_name'] ?? '' ?></td>
        <th>Sales Person</th>
        <td><?= $enquiry_data['user_name'] ?? '' ?></td>
    </tr>
</table>

<!-- Products / Estimation -->
<?php if (isset($estimation)): ?>
<table class="products">
    <thead>
        <tr style="background:#0070C0; color:#fff;">
            <th style="width:20%;">Main Heading</th>
            <th style="width:20%;">Sub Heading</th>
            <th style="width:25%;">Product</th>
            <th style="width:10%;">Unit</th>
            <th style="width:10%;">Qty</th>
            <th style="width:10%;">Unit Price</th>
            <th style="width:15%;">Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($estimation as $main): ?>
        <tr style="background:#f2f2f2; font-weight:bold;">
            <td><?= $main['main_heading'] ?></td>
            <td colspan="6"><?= $main['main_details'] ?></td>
        </tr>
        <?php foreach ($main['sub_headings'] as $sub): ?>
        <tr style="background:#e8f1ff; font-style:italic;">
            <td></td>
            <td colspan="6"><?= $sub['sub_heading'] ?? '' ?></td>
        </tr>
        <?php foreach ($sub['products'] as $prod): ?>
        <tr>
            <td></td>
            <td></td>
            <td><?= $prod['item_name'] ?><br><?= $prod['product_description'] ?></td>
            <td><?= $prod['unit_name'] ?></td>
            <td><?= $prod['quantity'] ?></td>
            <td><?= number_format($prod['unit_price'], 2) ?></td>
            <td><?= number_format($prod['amount'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr><td colspan="6">Sub Total</td><td><?= number_format($master['sub_total'] ?? 0,2) ?></td></tr>
       <?php if (!empty($master['margin_percentage'])): ?>
<tr>
    <td colspan="6">Margin %</td>
    <td><?= number_format($master['margin_percentage'], 2) ?></td>
</tr>
<?php endif; ?>

<?php if (!empty($master['margin_amount'])): ?>
<tr>
    <td colspan="6">Margin Amount</td>
    <td><?= number_format($master['margin_amount'], 2) ?></td>
</tr>
<?php endif; ?>

<?php if (!empty($master['freight_percentage'])): ?>
<tr>
    <td colspan="6">Freight %</td>
    <td><?= number_format($master['freight_percentage'], 2) ?></td>
</tr>
<?php endif; ?>

<?php if (!empty($master['freight_amount'])): ?>
<tr>
    <td colspan="6">Freight Amount</td>
    <td><?= number_format($master['freight_amount'], 2) ?></td>
</tr>
<?php endif; ?>

<?php if (!empty($master['bank_charge'])): ?>
<tr>
    <td colspan="6">Bank Charge</td>
    <td><?= number_format($master['bank_charge'], 2) ?></td>
</tr>
<?php endif; ?>

<?php if (!empty($master['travel_expense'])): ?>
<tr>
    <td colspan="6">Travel Expense</td>
    <td><?= number_format($master['travel_expense'], 2) ?></td>
</tr>
<?php endif; ?>

<?php if (!empty($master['inspection_cost'])): ?>
<tr>
    <td colspan="6">Inspection Cost</td>
    <td><?= number_format($master['inspection_cost'], 2) ?></td>
</tr>
<?php endif; ?>

<?php if (!empty($master['other_expense'])): ?>
<tr>
    <td colspan="6">Other Expense</td>
    <td><?= number_format($master['other_expense'], 2) ?></td>
</tr>
<?php endif; ?>
        <tr class="total-row"><td colspan="6">Total Amount</td><td><?= number_format($master['grand_total'] ?? 0,2) ?></td></tr>
    </tfoot>
</table>
<?php endif; ?>

<div class="footer">
    <img src="<?= $footerPath ?>" alt="Footer Logo">
    
  </div>

</body>
</html>