<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passport Release</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 17px;
        }

        table {
            border: 1px solid black;
            border-collapse: collapse;
            font-size: 11px;
        }

        th,
        td {
            padding: 2px;
        }

        .border-all {
            border: 1px solid black;
            margin-bottom: 10px;
        }

        .text_font {
            font-size: 14px;
        }
    </style>
</head>

<body onload="window.print();">

    <?php if (!empty($record1)) : ?>
        <div class="border-all">
            <table width="100%">
                <tr>
                    <td colspan="2">
                        <div class="border-all">
                            <table cellspacing="0" cellpadding="0" width="100%" class="text_font"
                                style="background-color: #ff0080; color: white;">
                                <tr>
                                    <td align="center" style="font-size:25px;">Passport Release Application</td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>

                <!-- General Information -->
                <tr>
                    <td colspan="2">
                        <div class="border-all">
                            <table cellspacing="0" cellpadding="0" width="100%" class="text_font">
                                <tr>
                                    <th>General Information</th>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>

                <!-- Employee Details -->
                <tr>
                    <td colspan="2">
                        <div class="border-all">
                            <table cellspacing="0" cellpadding="0" width="100%" class="table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Employee Name:</th>
                                        <td><?= isset($record1->employee_name) ? $record1->employee_name : ''; ?></td>
                                        <th>Passport No:</th>
                                        <td><?= isset($record1->passport_number) ? $record1->passport_number : ''; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Employee Number:</th>
                                        <td><?= isset($record1->user_code) ? $record1->user_code : ''; ?></td>
                                        <th>Accommodation:</th>
                                        <td><?= isset($record1->Accomodation_provided) ? $record1->Accomodation_provided : ''; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Mobile No:</th>
                                        <td><?= isset($record1->contact_no) ? $record1->contact_no : ''; ?></td>
                                        <th>Department/Project:</th>
                                        <td>
                                            <?php
                                            if (!empty($dept_list)) {
                                                foreach ($dept_list as $s) {
                                                    if (isset($record1->dept_id) && $s->dept_id == $record1->dept_id) {
                                                        echo $s->dept_name;
                                                    }
                                                }
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>

                <!-- Passport Release Purpose -->
                <tr>
                    <td colspan="2">
                        <div class="border-all">
                            <table cellspacing="0" cellpadding="0" width="100%" class="text_font">
                                <tr>
                                    <th>Passport Release Purpose</th>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <div class="border-all">
                            <table cellspacing="0" cellpadding="0" width="100%" class="table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Please Specify:</th>
                                        <td><?= isset($record1->reason) ? $record1->reason : ''; ?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>

                <!-- Passport Release Details -->
                <tr>
                    <td colspan="2">
                        <div class="border-all">
                            <table cellspacing="0" cellpadding="0" width="100%" class="text_font">
                                <tr>
                                    <th>Passport Release Details</th>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <div class="border-all">
                            <table cellspacing="0" cellpadding="0" width="100%" class="table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Release Date:</th>
                                        <td><?= isset($record1->outdate) ? date('d-M-Y', strtotime($record1->outdate)) : ''; ?></td>
                                        <th>Return Date:</th>
                                        <td><?= isset($record1->indate) ? date('d-M-Y', strtotime($record1->indate)) : ''; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Total Days:</th>
                                        <td colspan="3">
                                            <?php
                                            if (!empty($record1->outdate) && !empty($record1->indate)) {
                                                $outdate = new DateTime($record1->outdate);
                                                $indate = new DateTime($record1->indate);
                                                echo $outdate->diff($indate)->days;
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>

                <!-- Signatures -->
                <tr>
                    <td colspan="2">
                        <div class="border-all">
                            <table cellspacing="0" cellpadding="0" width="100%" class="table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Employee Signature:</th>
                                        <td></td>
                                        <th>Camp In Charge Signature:</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>HR Signature:</th>
                                        <td></td>
                                        <th>MD Signature:</th>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>

            </table>
        </div>
    <?php endif; ?>

</body>
</html>
