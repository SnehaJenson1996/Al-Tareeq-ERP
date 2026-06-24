<!DOCTYPE html>
<html>
<head>
    <title>Warranty Certificate</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            font-size: 13px;
            color:#000;
              margin: 0;
            padding: 0;
        }

        .header{
            text-align:center;
            margin-bottom:20px;
        }

        .title{
            font-size:18px;
            font-weight:bold;
            text-decoration: underline;
        }

        .content{
            margin:20px;
            line-height:1.6;
        }

        table{
            width:100%;
            border-collapse: collapse;
            margin-top:15px;
        }

        table, th, td{
            border:1px solid #000;
        }

        th, td{
            padding:8px;
            text-align:left;
        }

       .footer {
             position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
        }
        .footer img {
             max-width: 100%;
      height: auto;
      display: block;
      margin: 0 auto;
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

<table style="width:100%; border-collapse:collapse; margin-bottom:5px; border:none;">
  <tr>
    <td style="width:60%; padding:30px 20px 20px 20px; border:none;">
        <img src="<?= $headerPath ?>" class="header-img" style="max-height:120px;">
    </td>
  </tr>
</table>

<div class="header">
    <div class="title">WARRANTY CERTIFICATE</div>
</div>

<div class="content">

    <p>
        <b>Al Adel Automatic Doors Tr. LLC</b> assures that the equipment installed
        for <b>M/s. <?= $record->customer_name ?></b>
        at site <b><?= $record->site_location ?></b>
        is warranted against all manufacturing defects for a period of
        <b><?= $record->warranty_period ?> years</b>
        from the date of installation
        <b>(<?= date('d-m-Y', strtotime($record->installation_date)) ?>)</b>.
    </p>

    <p>
        <b>Equipment Covered:</b><br>
        BENINCA Brand Italy KBOB.3024 Swing Gate Operator (As per Invoice <?= $record->invoice_code ?>)
    </p>

    <h4><u>However, warranty does not cover:</u></h4>

    <div>
        <?= $record->terms_conditions ?>
    </div>
<div style="margin-top:30px;">

    <p>
        <strong>[Warranty Reference Number: <?= $record->invoice_code ?>]</strong>
    </p>

    <table style="width:100%; border:none; margin-top:20px;">
        <tr>
            <td style="width:70%; border:none; vertical-align:bottom;">

                <strong>Al Adel Automatic Doors Tr. LLC</strong><br>
                <?= date('d-m-Y', strtotime($record->installation_date)) ?>

            </td>

            <td style="width:30%; border:none; text-align:center;">

                <?php if (!empty($branch_stamp)) { ?>

                    <?php
                    $path = FCPATH . ltrim($branch_stamp, './');

                    if (file_exists($path)) {
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    ?>
                        <img src="<?= $base64 ?>" style="max-width:150px; max-height:120px;">
                    <?php } ?>

                <?php } ?>

            </td>
        </tr>
    </table>

</div>

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