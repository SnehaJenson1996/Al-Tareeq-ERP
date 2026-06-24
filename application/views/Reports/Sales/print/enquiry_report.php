<html>

<head>
    <title>Enquiry Report</title>
    <style>
        @page {
            margin: 10mm 10mm 25mm 10mm;

            @bottom-right {
                content: "Page " counter(page) " of " counter(pages);
            }

            @bottom-left {
                content: "©<?php echo date('Y'); ?> For Al Adel Automatic Doors TR. LLC, Designed and developed by Concepts 360 Plus";
            }
        }

        body {
            margin: 0; /* remove top margin to push header to the top */
            font-family: Arial, sans-serif;
            font-size: 12px;
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 6px;
            font-size: 12px;
        }

        thead tr {
            background-color: #f2f2f2;
        }

        .header-img {
            width: 100%;
            height: 200px; /* adjust height as needed */
            object-fit: fill;
            display: block;
        }

        .report-title {
            margin: 10px 0;
            font-weight: bold;
        }

        .separator {
            height: 5px;
            background-color: #525453;
        }

        .content {
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <!-- Header Image at top -->
    <img src="<?= $headerPath ?>" alt="Company Logo" class="header-img">

    <div class="content">

        <!-- Report Title -->
        <div class="report-title">
            Enquiry Report from <?= isset($from_date) ? $from_date : '' ?> 
            to <?= isset($to_date) ? $to_date : '' ?>
        </div>

        <!-- Separator -->
        <div class="separator"></div>

        <!-- Enquiry Table -->
<table style="width:100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size:12px;" cellpadding="5">
    <thead>
        <tr style="background-color: #f2f2f2; text-align: center; font-weight: bold;">
            <th style="border: 1px solid #000; padding: 8px; width:5%;">Sl. No</th>
            <th style="border: 1px solid #000; padding: 8px; width:15%;">Enquiry Code</th>
            <th style="border: 1px solid #000; padding: 8px; width:10%;">Date</th>
            <th style="border: 1px solid #000; padding: 8px; width:25%;">Customer</th>
            <th style="border: 1px solid #000; padding: 8px; width:25%;">Project</th>
            <th style="border: 1px solid #000; padding: 8px; width:10%;">Created By</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; foreach ($records as $row): ?>
        <tr style="text-align: left;">
            <td style="border: 1px solid #000; text-align:center; padding: 6px;"><?= $i++ ?></td>
            <td style="border: 1px solid #000; padding: 6px;"><?= $row->enquiry_code ?></td>
            <td style="border: 1px solid #000; padding: 6px;"><?= $row->enquiry_date ?></td>
            <td style="border: 1px solid #000; padding: 6px;"><?= $row->customer_name ?></td>
            <td style="border: 1px solid #000; padding: 6px;"><?= $row->project_name ?></td>
            <td style="border: 1px solid #000; padding: 6px;"><?= $row->created ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if(empty($records)): ?>
        <tr>
            <td colspan="7" style="border:1px solid #000; text-align:center; padding:8px;">No records found</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>


    </div>
</body>
</html>
