<?php
$leave = $leave;

function safe_date($dateString, $format = 'd-M-Y') {
    if (!empty($dateString) && $dateString != '0000-00-00') {
        return date($format, strtotime($dateString));
    }
    return 'N/A';
}
?>

<html>
<head>
<title>Leave Print</title>

<style>
body { font-family: Arial; font-size: 13px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
th, td { border: 1px solid #ccc; padding: 6px; }
th { background: #f0f0f0; text-align: left; }
.section-title { background: #8AB645; color: #fff; text-align: center; }
</style>
</head>

<body>

<table>

<tr>
    <th colspan="4" class="section-title">Leave Application</th>
</tr>

<tr>
    <th>Employee Name</th>
    <td><?= $leave->employee_name ?? 'N/A'; ?></td>

    <!-- <th>Employee Code</th>
    <td><?= $leave->user_code ?? 'N/A'; ?></td> -->
</tr>

<tr>
    <th>Leave Code</th>
    <td><?= $leave->leave_code ?? 'N/A'; ?></td>

    <th>Application Date</th>
    <td><?= safe_date($leave->application_date); ?></td>
</tr>

<tr>
    <th>Leave Type</th>
    <td colspan="3"><?= $leave->leave_type ?? 'N/A'; ?></td>
</tr>

<tr>
    <th>From Date</th>
    <td><?= safe_date($leave->start_date); ?></td>

    <th>To Date</th>
    <td><?= safe_date($leave->end_date); ?></td>
</tr>

<tr>
    <th>Reason</th>
    <td colspan="3"><?= $leave->reason ?? 'N/A'; ?></td>
</tr>

<tr>
    <th>Replacement</th>
    <td colspan="3"><?= $leave->replacement_name ?? 'N/A'; ?></td>
</tr>

<?php
$total_days = 'N/A';
if (!empty($leave->start_date) && !empty($leave->end_date)) {
    $total_days = (new DateTime($leave->start_date))
        ->diff(new DateTime($leave->end_date))
        ->days + 1;
}
?>

<tr>
    <th>Total Days</th>
    <td colspan="3"><?= $total_days; ?></td>
</tr>

<!-- <tr>
    <th>Documents</th>
    <td colspan="3">

        <?php if (!empty($file_records)) : ?>
            <ul>
                <?php foreach ($file_records as $doc): ?>
                    <li>
                        <a href="<?= base_url('uploded_documents/'.$doc->document_path); ?>" target="_blank">
                            <?= basename($doc->document_path); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            N/A
        <?php endif; ?>

    </td>
</tr> -->

<tr>
    <th>Employee Signature</th>
    <td></td>
    <th>HR Signature</th>
    <td></td>
</tr>

<tr>
    <th>MD Signature</th>
    <td></td>
    <th>PM Signature</th>
    <td></td>
</tr>

</table>

</body>
</html>