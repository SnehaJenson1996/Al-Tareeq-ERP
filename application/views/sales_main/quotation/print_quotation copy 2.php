<!DOCTYPE html>
<html>
<head>
    <title>Estimation PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; }
        .header img, .footer img { width: 100%; }
        .main-heading { margin-bottom: 15px; }
        .main-heading h3 { margin: 0; font-size: 14px; background-color: #f0f0f0; padding: 5px; }
        .details { margin-bottom: 10px; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 15px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 5px; text-align: center; font-size: 12px; }
        th { background-color: #f8f9fa; }
        .footer { text-align:center; margin-top: 20px; font-size:11px; }
    </style>
</head>
<body>

<!-- Header -->
<div class="header">
    <img src="<?= $headerPath ?>" alt="Header Image">
</div>

<?php if(isset($estimation)): ?>
    <?php foreach($estimation as $mainIndex => $main): ?>
        <div class="main-heading">
            <h3>Main Heading: <?= $main['main_heading'] ?></h3>
            <div class="details">Details: <?= $main['main_details'] ?></div>

            <?php foreach($main['sub_headings'] as $subIndex => $sub): ?>
                <h4>Sub Heading: <?= $sub['sub_heading'] ?></h4>
                <table cellpadding=10 width=100% style='font-size: 12px; border-collapse:collapse;border:2px solid'>
                    <thead>
                        <tr  class='calc' style="background-color: #3812e1ff;color:#e8b41a;font-style:bold">
                            <th>Product</th>
                            <th>Unit</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($sub['products'] as $prod): ?>
                            <tr>
                                <td><?= $prod['item_name'] ?></td>
                                <td><?= $prod['unit_name'] ?></td>
                                <td><?= $prod['quantity'] ?></td>
                                <td><?= number_format($prod['unit_price'], 2) ?></td>
                                <td><?= number_format($prod['amount'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Footer -->
<div class="footer">
    <img src="<?= $footerPath ?>" alt="Footer Image">
    <div>Thank you for giving us the opportunity to quote you. We appreciate your business.</div>
</div>

</body>
</html>
