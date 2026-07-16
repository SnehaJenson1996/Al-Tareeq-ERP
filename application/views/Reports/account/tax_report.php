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
    printWindow.document.write('<html><head><title>VAT Report</title>');

    printWindow.document.write(`
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
    <style>
      body {
        font-family: Arial, sans-serif;
        margin: 20px;
        font-size: 12px;
      }

      table {
        width: 100%;
        border-collapse: collapse;
      }

      th, td {
        padding: 5px;
        border: 1px solid #000;
        vertical-align: top;
        font-size: 12px;
      }

      th {
        text-align: center;
        background-color: #f8f9fa;
      }

      td.amount, td.numeric, td.text-end, td.text-right {
        text-align: right !important;
      }

      tr.total-row td {
        font-weight: bold;
        border-top: 2px solid #000;
      }

      /* Header and footer sections */
      .print-header, .print-footer {
        text-align: center;
        width: 100%;
      }

      .print-header{
          text-align:center;
          margin-bottom:10px;
      }

      .print-header img{
          width:200px;      /* adjust if needed */
          height:auto;
          display:block;
          margin:0 auto;
      }

      // .print-footer img {
      //   width: 100%;
      // }

      /* Ensure header and footer appear correctly in print */
      @media print {
        body {
          margin: 15mm 10mm;
        }

        /* Repeat header image on every printed page */
        .print-header {
          position: running(pageHeader);
        }

        @page {
          @top-center {
            content: element(pageHeader);
          }
          margin-top: 40mm;
          margin-bottom: 30mm;
        }

        /* Keep footer at bottom */
        .print-footer {
          position: fixed;
          bottom: 0;
          left: 0;
          right: 0;
        }

        /* Ensure table headers repeat on each page */
        thead {
          display: table-header-group;
        }

        tfoot {
          display: table-footer-group;
        }
      }
    </style>
  `);

    printWindow.document.write('</head><body>');

    // HEADER IMAGE (will repeat due to CSS rule)
    printWindow.document.write(`
    <div class="print-header">
      <img src="<?php echo base_url('public/assets/images/altariq_logo.jpeg'); ?>" alt="Header">
    </div>
  `);

    // REPORT CONTENT
    printWindow.document.write(reportContent);

    // FOOTER IMAGE
  //   printWindow.document.write(`
  //   <div class="print-footer">
  //     <img src="<?php echo base_url('public/footer/2.png'); ?>" alt="Footer">
  //   </div>
  // `);

    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
  }
</script>