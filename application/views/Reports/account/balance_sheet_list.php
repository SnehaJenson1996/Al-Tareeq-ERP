<style>
    @media print {
        body {
            margin: 0;
            background: #fff;
        }

        .btn,
        form,
        .bs-status {
            display: none !important;
        }

        .bs-container {
            display: flex;
            gap: 10px;
            width: 100%;
            align-items: flex-start;
        }

        .bs-column {
            flex: 1;
            width: 49.5%;
        }

        .bs-column table {
            width: 100% !important;
        }

        .card-body {
            width: 100%;
            padding: 10px;
        }

        .row {
            margin-left: 0;
            margin-right: 0;
        }
    }

    body.modal-open {
        overflow: hidden;
        padding-right: 0 !important;
    }

    html {
        overflow-y: scroll;
    }

    thead {
        display: table-header-group;
    }

    tfoot {
        display: table-footer-group;
    }

    tfoot td {
        height: 0;
        padding: 0;
    }

    /* Page Background */
    .card-body {
        background: #f4f6f9;
        padding: 20px;
    }

    /* Header */
    .bs-report-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .bs-report-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
    }

    .bs-report-header p {
        margin: 5px 0 0;
        color: #777;
        font-size: 13px;
    }

    /* Layout */
    .bs-container {
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .bs-column {
        width: 50%;
    }

    /* Card Style */
    .bs-column table {
        background: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
        border-radius: 6px;
        overflow: hidden;
    }

    /* Table */
    .bs-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .bs-table th {
        background: #34495e;
        color: #fff;
        font-weight: 600;
        padding: 10px;
        border: 1px solid #34495e;
        text-transform: uppercase;
    }

    .bs-table td {
        border: 1px solid #e4e7ea;
        padding: 7px 10px;
    }

    .bs-table tbody tr:nth-child(even) {
        background: #fafafa;
    }

    .bs-table tbody tr:hover {
        background: #f5f9ff;
    }

    .text-right {
        text-align: right;
    }

    /* Group Rows */
    .tree tr td:first-child {
        font-weight: 500;
    }

    .drilldown-link {
        color: #007bff;
        text-decoration: none;
    }

    .drilldown-link:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    /* Total Section */
    .divider {
        border-bottom: 2px solid #000 !important;
        height: 5px;
        padding: 0 !important;
    }

    .bs-table tfoot th {
        background: #f1f3f5;
        color: #000;
        font-size: 14px;
        font-weight: 700;
    }

    /* Balance Status */
    .bs-status {
        margin-top: 20px;
        text-align: center;
        font-size: 15px;
        font-weight: bold;
    }

    .bs-status .ok {
        display: inline-block;
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: 10px 20px;
        border-radius: 4px;
    }

    .bs-status .error {
        display: inline-block;
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        padding: 10px 20px;
        border-radius: 4px;
    }

    /* Form */
    .form-group {
        background: #fff;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
        box-shadow: 0 1px 5px rgba(0, 0, 0, .08);
    }

    .btn-primary {
        padding: 6px 20px;
    }

    .btn-success {
        padding: 6px 20px;
    }

    /* Modal */
    .modal-content {
        border-radius: 8px;
    }

    .modal-header {
        background: #34495e;
        color: #fff;
    }

    .modal-title {
        color: #fff;
    }
</style>
<?php $this->load->helper('Account_helper.php'); ?>
<div class="card-body">

    <form id="main" method="post" action="<?php echo base_url() . 'index.php/'; ?>Accounts/view_balance_sheet" class="form-horizontal" autocomplete="off" name="question" id="question" enctype="multipart/form-data">
        <div class="form-group row">
            <!-- <label class="col-sm-1 col-form-label">From <span style="color: red;">*</span></label> -->
            <div class="col-sm-2">
                <input tabindex="1" type="hidden" class="form-control" id="from" name="from" value="<?php echo $from; ?>"
                    required>
            </div>

            <label class="col-sm-1 col-form-label">Till Date <span style="color: red;">*</span></label>
            <div class="col-sm-2">
                <input tabindex="2" type="date" class="form-control" id="to" name="to" value="<?php echo $to; ?>"
                    required>
            </div>

            <label class="col-sm-1 col-form-label">Branch</label>
            <div class="col-sm-2">
                <select class="form-control" name="branch_id" id="branch_id">
                    <option value="">All Branches</option>
                    <?php foreach ($branch_list as $b) { ?>
                        <option value="<?= $b->branch_id ?>"
                            <?= ($branch_id == $b->branch_id) ? 'selected' : '' ?>>
                            <?= $b->branch_name ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-3">
                <button type="submit"
                        id="view"
                        name="go"
                        class="btn btn-primary btn-sm"
                        style="margin-right:10px;">

                    <i class="fa fa-search" style="margin-right:5px;"></i>
                    Go
                </button>

                <button type="button"
                        onclick="printBalanceSheet()"
                        class="btn btn-warning btn-sm">

                    <i class="fa fa-print"
                    style="color:#000;margin-right:5px;"></i>

                    <span style="color:#000;">Print</span>

                </button>
            </div>
        </div>
    </form>

    <div class="row">

        <?php
        $asset_total = array_sum(array_map(fn($g) => $g->balance, $assets));
        $liab_total  = array_sum(array_map(fn($g) => $g->balance, $liabilities));
        ?>
        <div id="printArea">

            <!-- HEADER -->
            <div class="bs-report-header">
                <h2>BALANCE SHEET</h2>
                <p>
                    Period : <?php echo date('d-M-Y', strtotime($from)); ?>
                    To
                    <?php echo date('d-M-Y', strtotime($to)); ?>
                </p>
            </div>

            <!-- TWO COLUMN LAYOUT -->
            <div class="bs-container">
                <!-- LIABILITIES -->
                <div class="bs-column">
                    <table class="bs-table tree">
                        <thead>
                            <tr>
                                <th>Liabilities</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $row_id = 1000;
                            if (empty($liabilities)): ?>
                                <tr>
                                    <td colspan="2">No Data</td>
                                </tr>
                            <?php endif;

                            render_tree_rows($liabilities, $row_id);
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="divider"></td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <th class="text-right"><?php echo number_format($liab_total, 2); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- ASSETS -->
                <div class="bs-column">
                    <table class="bs-table tree">
                        <thead>
                            <tr>
                                <th>Assets</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $row_id = 1;
                            if (empty($assets)): ?>
                                <tr>
                                    <td colspan="2">No Data</td>
                                </tr>
                            <?php endif;

                            render_tree_rows($assets, $row_id);
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="divider"></td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <th class="text-right"><?php echo number_format($asset_total, 2); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>



            </div>

            <!-- STATUS -->
            <div class="bs-status">
                <?php if (round($asset_total, 2) == round($liab_total, 2)): ?>
                    <span class="ok">✔ Balance Sheet Tallies</span>
                <?php else: ?>
                    <span class="error">✖ Not Tally (Difference: <?php echo number_format(abs($asset_total - $liab_total), 2); ?>)</span>
                <?php endif; ?>
            </div>

        </div>


        <!-- DRILLDOWN MODAL -->
        <div id="drillModal" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">


                    <div class="modal-header">

                        <h4 class="modal-title">Ledger Drilldown</h4>
                    </div>


                    <div class="modal-body">
                        <div id="drillContent">Loading...</div>
                    </div>
                    <!-- FOOTER -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-danger" data-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- Static Table End -->

<?php

function render_tree_rows($groups, &$row_id = 1, $parent_id = 0, $level = 0)
{

    foreach ($groups as $group) {
        if (round($group->balance, 2) == 0) {
            continue;
        }
        $current_id = $row_id++;
        $parent_class = $parent_id ? "treegrid-parent-$parent_id" : "";

        echo "<tr class='treegrid-$current_id $parent_class'>";

        echo "<td style='padding-left:" . ($level * 20) . "px; font-weight:bold;'>";
        echo $group->group_name;
        echo "</td>";

        echo "<td style='text-align:right; font-weight:bold;'>";
        echo number_format(abs($group->balance), 2);
        echo "</td>";

        echo "</tr>";

        // Ledgers
        if (!empty($group->ledgers)) {
            foreach ($group->ledgers as $ledger) {

                if (round($ledger->balance, 2) == 0) {
                    continue; // skip zero balance
                }

                $ledger_id = $row_id++;

                echo "<tr class='treegrid-$ledger_id treegrid-parent-$current_id'>";

                echo "<td style='padding-left:" . (($level + 1) * 20) . "px'>";
                global $from, $to;
                echo "<a href='javascript:void(0);' 
        class='drilldown-link' 
        data-id='" . $ledger->account_id . "' 
        data-from='" . $from . "' 
        data-to='" . $to . "'>";

                echo $ledger->name;

                echo "</a>";



                echo "</td>";

                echo "<td style='text-align:right'>";
                echo ($ledger->balance < 0 ? '(' : '') . number_format(abs($ledger->balance), 2) . ($ledger->balance < 0 ? ')' : '');
                echo "</td>";

                echo "</tr>";
            }
        }

        // Children
        if (!empty($group->children)) {
            render_tree_rows($group->children, $row_id, $current_id, $level + 1);
        }
    }
}


?>

<script type="text/javascript">
    function printBalanceSheet() {

        if ($.fn.treegrid) {
            $('.tree').treegrid('expandAll');
        }

        var content = document.getElementById('printArea').innerHTML;

        var myWindow = window.open('', '', 'width=1000,height=700');

        myWindow.document.write(`
        <html>
        <head>
            <title>Balance Sheet</title>
            <style>
                body{
                font-family:Arial;
                font-size:13px;
                margin:20px;
                color:#000;
            }

            h2{
                text-align:center;
                margin-bottom:5px;
            }

            table{
                width:100%;
                border-collapse:collapse;
            }

            th{
                background:#e9ecef;
                border:1px solid #000;
                padding:8px;
                text-align:center;
                font-weight:bold;
            }

            td{
                border:1px solid #000;
                padding:6px 8px;
            }

            td:nth-child(2),
            td:nth-child(4){
                text-align:right;
            }

            .section-title{
                background:#f5f5f5;
                font-weight:bold;
            }

            .total{
                border-top:2px solid #000;
                font-weight:bold;
            }

            .grand-total{
                background:#e9ecef;
                font-weight:bold;
            }

            /* Header */

            .print-header{
                text-align:center;
                margin-bottom:15px;
            }

            .print-header img{
                width:220px;
                height:auto;
                display:block;
                margin:0 auto;
            }

            /* Page */

            @page{
                size:A4 landscape;
                margin:15mm 10mm 25mm 10mm;

                @top-center{
                    content:element(pageHeader);
                }

                @bottom-left{
                    content:"©<?= date('Y'); ?> For Al Tareeq Kitchen Equipment Industry LLC, Designed and developed by Concepts 360 Plus";
                    font-size:10px;
                }

                @bottom-right{
                    content:"Page " counter(page) " of " counter(pages);
                    font-size:10px;
                }
            }

            @media print{

                body{
                    margin:0;
                }

                .print-header{
                    position:running(pageHeader);
                }

                thead{
                    display:table-header-group;
                }

                tfoot{
                    display:table-footer-group;
                }

                tr{
                    page-break-inside:avoid;
                }
            }
            </style>
        </head>
        <body>
            <div class="print-header">
                <img src="<?php echo base_url('public/assets/images/altariq_logo.jpeg'); ?>" alt="Company Logo">
            </div>

            <div style="text-align:center;margin-bottom:20px;">
                <h2 style="margin:5px 0;">BALANCE SHEET</h2>

                <strong>Period :</strong>
                ${document.getElementById('from').value}
                To
                ${document.getElementById('to').value}
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Assets</th>
                        <th class="text-right">Amount</th>
                        <th>Liabilities & Equity</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    ${formatSingleTable()}
                </tbody>
            </table>

        </body>
        </html>
    `);

        myWindow.document.close();

        myWindow.onload = function() {
            myWindow.focus();
            myWindow.print();
            myWindow.close();
        };
    }

    function formatSingleTable() {

        let assetRows = [];
        let liabRows = [];

        // Include BOTH tbody + tfoot
        document.querySelectorAll('.bs-column:nth-child(1) table tr').forEach(tr => {
            assetRows.push(tr.innerHTML);
        });

        document.querySelectorAll('.bs-column:nth-child(2) table tr').forEach(tr => {
            liabRows.push(tr.innerHTML);
        });

        let max = Math.max(assetRows.length, liabRows.length);
        let html = '';

        for (let i = 0; i < max; i++) {
            html += '<tr>';

            html += assetRows[i] ?
                assetRows[i] :
                '<td></td><td></td>';

            html += liabRows[i] ?
                liabRows[i] :
                '<td></td><td></td>';

            html += '</tr>';
        }

        return html;
    }
    $(document).ready(function() {

        $('.tree').treegrid({
            initialState: 'collapsed',
            expanderExpandedClass: 'glyphicon glyphicon-minus',
            expanderCollapsedClass: 'glyphicon glyphicon-plus'
        });

        if (!$('#from').val()) {
            let today = new Date();
            let year = today.getFullYear();

            $('#from').val(year + '-01-01');
            $('#to').val(today.toISOString().split('T')[0]);
        }
    });

    $(document).on('click', '.drilldown-link', function() {

        let account_id = $(this).data('id');
        let from = $(this).data('from');
        let to = $(this).data('to');

        $('#drillModal').modal('show');
        $('#drillContent').html('Loading...');

        $.ajax({
            url: "<?php echo base_url('index.php/Accounts/drilldown_balance_sheet'); ?>",
            type: "GET",
            data: {
                account_id: account_id,
                from: from,
                to: to
            },
            success: function(response) {
                $('#drillContent').html(response);
            },
            error: function() {
                $('#drillContent').html('<p style="color:red;">Error loading data</p>');
            }
        });

    });
    $(document).on('click', '.modal .close, .modal .btn-default', function() {
        $('#drillModal').modal('hide');
    });
</script>