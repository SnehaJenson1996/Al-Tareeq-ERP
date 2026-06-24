<!DOCTYPE html>
<html>
<head>
    <title>Survey Report</title>
    <style>
        body { font-family: "Franklin Gothic Book", Arial, sans-serif; font-size: 13px; }
        .header { text-align: center; margin-bottom: 20px; }
          .footer {
    text-align: center;
    font-size: 12px;
    position: fixed;
    bottom: 0;          /* Stick to bottom of page */
    left: 0;
    right: 0;
    width: 100%;
}
.footer img {
    max-width: 100%;    /* Prevent image from stretching */
    height: auto;
    display: block;
    margin: 0 auto;
}
        /* Print */
        @media print {
            .header, .footer { page-break-inside: avoid; }
        }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table th, table td { border: 1px solid #000; padding: 6px; font-size: 12px; }
        .section-title { font-weight: bold; margin-top: 15px; margin-bottom: 5px; }
    </style>
</head>
<body>

<!-- Branch Header -->
<?php if (!empty($headerPath)): ?>
    <div style="text-align:center;">
        <img src="<?= $headerPath ?>" alt="Header" style="width:100%; max-height:100px;">
    </div>
<?php endif; ?>

<div class="header">
    <h2>Survey Report</h2>
    <h4><?= $branch_name ?></h4>
</div>

<table style="width:100%; border-collapse:collapse; margin-bottom:15px;">
    <tr>
        <td style="width:50%; padding:5px; border:none;">
            <p><strong>Enquiry Date:</strong> <?= $enquiry_date ?></p>
            <p><strong>Project Name:</strong> <?= $project_name ?> (<?= $enquiry_category ?>)</p>
            <p><strong>Customer:</strong> <?= $customer_name ?> (<?= $contact_number ?>)</p> 
            <p><strong>Sales Person :</strong> <?= $survey['preparedby'] ?? '' ?></p>
        </td>
        <td style="width:50%; padding:5px; border:none;">
            <p><strong>Enquiry Code:</strong> <?= $enquiry_code ?></p>
            <p><strong>Location:</strong> <?= $project_location ?></p>
            <p><strong>Submitted On:</strong> <?= $survey['updated_on'] ?></p>
            <p><strong>Surveyor:</strong> <?= $survey['surveyor'] ?? '' ?></p>
        </td>
    </tr>
</table>

<h4 class="section-title">Schedule Details</h4>
<table>
    <tr>
        <th>Scheduled Date</th><th>Start</th><th>End</th><th>Hours</th>
    </tr>
    <tr>
        <td><?= $survey['scheduled_date'] ?></td>
        <td><?= $survey['start_time'] ?></td>
        <td><?= $survey['end_time'] ?></td>
        <td><?= $survey['scheduled_hours'] ?></td>
    </tr>
</table>

<h4 class="section-title">Actual Survey</h4>
<table>
    <tr>
        <th>Date</th><th>Start</th><th>End</th><th>Hours</th>
    </tr>
    <tr>
        <td><?= $survey['actual_date'] ?></td>
        <td><?= $survey['actual_start_time'] ?></td>
        <td><?= $survey['actual_end_time'] ?></td>
        <td><?= $survey['actual_hours'] ?></td>
    </tr>
</table>

<h4 class="section-title">Comments</h4>
<p><?= $survey['survey_comments'] ?></p>

<h4 class="section-title">Materials</h4>
<p><?= $survey['material_details'] ?></p>

<h4 class="section-title">Files</h4><br><br>
<?php if (!empty($survey['file_names'])): ?>
    <?php foreach (explode(',', $survey['file_names']) as $file): ?>
        <?php 
            $file = trim($file);
            $file_url = base_url('public/survey_files/' . $file);
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        ?>
        <?php if (in_array($ext, ['jpg','jpeg','png','gif','webp'])): ?>
            <!-- Show as image -->
            <div style="margin:5px; display:inline-block;">
                <img src="<?= $file_url ?>" alt="Survey File" style="max-width:150px; max-height:150px; border:1px solid #ccc; padding:3px;">
            </div>
        <?php else: ?>
            <!-- Show as download link for non-images -->
            <p><a href="<?= $file_url ?>" target="_blank"><?= $file ?></a></p>
        <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
    <p>No files attached.</p>
<?php endif; ?>


<!-- Branch Footer -->
<?php if (!empty($footerPath)): ?>
    <div class="footer">
        <img src="<?= $footerPath ?>" alt="Footer" style="width:100%; max-height:80px;">
    </div>
<?php endif; ?>

</body>
</html>
