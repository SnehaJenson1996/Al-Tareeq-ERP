<div class="card">
  <div class="card-body">
    <form id="ledger_report_form" method="post" action="<?php echo base_url('index.php/Accounts/tax_report_details'); ?>">

      <div class="form-group row mb-4">
        <!-- From Date -->
        <label class="col-md-2 col-form-label text-end">From Date</label>
        <div class="col-md-3">
          <div class="input-group">
            <input type="date" class="form-control form-control-sm datepicker"
              id="from_date" name="from_date"
              value="<?php echo date('d-M-Y', strtotime($from_date)); ?>" required>
            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
          </div>
        </div>

        <!-- To Date -->
        <label class="col-md-2 col-form-label text-end">To Date</label>
        <div class="col-md-3">
          <div class="input-group">
            <input type="date" class="form-control form-control-sm datepicker"
              id="to_date" name="to_date"
              value="<?php echo date('d-M-Y', strtotime($to_date)); ?>" required>
            <span class="input-group-text"><i class="fa fa-calendar"></i></span>
          </div>
        </div>
      </div>

      <div class="form-group row mb-4">
        <!-- Report Type -->
        <label class="col-md-2 col-form-label text-end">Report Type</label>
        <div class="col-md-3">
          <select name="report_type" id="report_type" class="form-control form-select-sm" required>
            <option value="">Select Type</option>
            <option value="summary">Summary</option>
            <option value="detailed">Detailed</option>
          </select>
        </div>

        <div class="col-md-5">
            <button type="submit"
                    id="generate_report"
                    class="btn btn-primary btn-sm"
                    style="margin-right:10px;">
                <i class="fa fa-search" style="margin-right:5px;"></i>
                Generate Report
            </button>

            <button type="button"
                    class="btn btn-warning btn-sm"
                    onclick="printReport()">
                <i class="fa fa-print" style="color:#000;margin-right:5px;"></i>
                <span style="color:#000;">Print Report</span>
            </button>
        </div>

        <!-- Submit Button -->
      </div>
    </form>
    <!-- Report Display Section -->
    <div id="report_result" class="mt-4"></div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#ledger_report_form').on('submit', function(e) {
      e.preventDefault(); // Prevent page reload

      var formData = $(this).serialize(); // Convert form data to string

      // Show a loading message
      $('#report_result').html('<p class="text-center">Loading report...</p>');

      $.ajax({
        url: $(this).attr('action'), // form action
        type: 'POST',
        data: formData,
        success: function(response) {
          // Replace the report container with returned HTML
          $('#report_result').html(response);
        },
        error: function() {
          $('#report_result').html('<p class="text-danger text-center">Error fetching report.</p>');
        }
      });
    });
  });

  function printReport() {

    var reportContent = document.getElementById('report_result').innerHTML;

    var printWindow = window.open('', '_blank', 'height=900,width=1200');

    printWindow.document.write(`
    <html>
    <head>
        <title>VAT Report</title>

        <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">

        <style>

            body{
                font-family:Arial,sans-serif;
                font-size:12px;
                margin:20px;
            }

            table{
                width:100%;
                border-collapse:collapse;
            }

            th,
            td{
                border:1px solid #000;
                padding:5px;
                font-size:12px;
                vertical-align:top;
            }

            th{
                background:#f8f9fa;
                text-align:center;
            }

            td.amount,
            td.numeric,
            td.text-right,
            td.text-end{
                text-align:right !important;
            }

            .total-row td{
                font-weight:bold;
                border-top:2px solid #000;
            }

            /* ---------- HEADER ---------- */

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

            /* ---------- PAGE SETUP ---------- */

            @page{

                size:A4 portrait;

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
            <img src="<?php echo base_url('public/assets/images/altariq_logo.jpeg'); ?>" alt="Company Header">
        </div>

        ${reportContent}

    </body>

    </html>
    `);

    printWindow.document.close();

    printWindow.onload = function () {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    };

}
</script>