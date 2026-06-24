<?php
$printed_by = $this->session->userdata('user_name') ?? $this->session->userdata('email');
?>

<html>
<head>
    <title>Joining Application</title>

    <style>
        body { font-family: Arial; margin:0; padding:0; }
        main { padding: 10px 20px; }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 14px;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .section-header {
            background-color: #8AB645;
            color: #fff;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            padding: 6px;
            margin-top: 10px;
        }

        .sub-header {
            background-color: #f2f2f2;
            font-weight: bold;
            padding: 5px;
            margin-top: 15px;
        }

        @media print {
            @page { margin: 20mm 15mm 35mm 15mm; }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>

<body onload="window.print();">

<main>

<?php if (!empty($records)) : ?>
<?php $row = $records; ?>

<div>

    <!-- TITLE -->
    <div class="section-header">Joining Application</div>

    <!-- GENERAL INFORMATION -->
    <div class="sub-header">General Information</div>

    <table>
        <tr>
            <th>Employee Name</th>
            <td><?php echo $row->user_name; ?></td>

            <th>Direct Manager</th>
            <td></td>
        </tr>

        <tr>
            <th>Employee ID</th>
            <td><?php echo $row->employee_id; ?></td>

            <th>Department/Project</th>
            <td>
                <?php
                if (!empty($dept_list)) {
                    foreach ($dept_list as $d) {
                        if ($d->dept_id == $row->dept_id) {
                            echo $d->dept_name;
                        }
                    }
                }
                ?>
            </td>
        </tr>

        <tr>
            <th>Designation</th>
            <td><?php echo $row->designation_id ?? ''; ?></td>

            <th>Passport No</th>
            <td><?php echo $row->document_number ?? ''; ?></td>
        </tr>

        <tr>
            <th>Mobile No</th>
            <td><?php echo $row->contact_no ?? ''; ?></td>

            <th>Accommodation</th>
            <td><?php echo $row->accomodation_provided ?? ''; ?></td>
        </tr>

        <tr>
            <th>Application Date</th>
            <td>
                <?php
                echo (!empty($row->created_date))
                    ? date('d-M-Y', strtotime($row->created_date))
                    : '';
                ?>
            </td>

            <th>Offer Letter</th>
            <td><?php echo ($row->offer_letter == '1') ? 'Yes' : 'No'; ?></td>
        </tr>
    </table>

    <!-- JOINING TYPE -->
    <div class="sub-header">Joining Type</div>

    <table>
        <tr>
            <th>Resuming After Leave</th>
            <td><?php echo ($row->joining_type == 'Resuming After Leave') ? '✔' : ''; ?></td>

            <th>Observation Period</th>
            <td><?php echo ($row->joining_type == 'Observation Period') ? '✔' : ''; ?></td>
        </tr>

        <tr>
            <th>Newly Join</th>
            <td><?php echo ($row->joining_type == 'Newly Join') ? '✔' : ''; ?></td>

            <th>Other</th>
            <td><?php echo ($row->joining_type == 'Other') ? '✔' : ''; ?></td>
        </tr>
    </table>

    <p><b>If Other, Please Specify:</b></p>

    <!-- JOINING DATE -->
    <div class="sub-header">Actual First Joining Date</div>

    <table>
        <tr>
            <th>Joining Date</th>
            <td>
                <?php
                echo (!empty($row->joining_date))
                    ? date('d-M-Y', strtotime($row->joining_date))
                    : '';
                ?>
            </td>
        </tr>
    </table>

    <!-- SIGNATURES -->
    <div class="sub-header">Signatures</div>

    <table>
        <tr>
            <th>Employee Signature</th>
            <td></td>

            <th>PM Signature</th>
            <td></td>
        </tr>

        <tr>
            <th>HR Signature</th>
            <td></td>

            <th>MD Signature</th>
            <td></td>
        </tr>
    </table>

</div>

<?php endif; ?>

</main>

</body>
</html>