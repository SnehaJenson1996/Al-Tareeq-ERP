<div class="card">
  <div class="card-body">
    <h5>
      Tax Details 
      (<?php echo isset($from_date) ? date('d-M-Y', strtotime($from_date)) : ''; ?> 
       to 
       <?php echo isset($to_date) ? date('d-M-Y', strtotime($to_date)) : ''; ?>)
    </h5>

  <!-- Output VAT (CR) -->
<h6 class="mt-5">Voucher / Journal Output VAT (CR)</h6>
<div class="dt-responsive table-responsive">
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>Sr.No</th>
        <th>Date</th>
        <th>Voucher Code</th>
        <th>Voucher Type</th>
        <th>Customer/Supplier</th>
        <th>Taxable Amount</th>
        <th>VAT</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if (!empty($voucher_records->output)) {
          $i = 1;
          $grand_tax = 0;
          $grand_vat  = 0;

          foreach ($voucher_records->output as $row) {
              $taxable = $row->taxable_amount;
              $vat     = $row->vat_amount;
              $total   = $row->total;

              $grand_tax += $taxable;
              $grand_vat  += $vat;
      ?>
      <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo date('d-M-Y', strtotime($row->voucher_date)); ?></td>
        <td><?php echo $row->voucher_code; ?></td>
        <td><?php echo $row->voucher_type; ?></td>
        <td>-</td>
        <td class="text-end"><?php echo number_format($taxable, 2); ?></td>
        <td class="text-end"><?php echo number_format($vat, 2); ?></td>
        <td class="text-end"><?php echo number_format($total, 2); ?></td>
      </tr>
      <?php
          }
      } else {
      ?>
      <tr>
        <td colspan="8" class="text-center text-muted">No Output (CR) vouchers found.</td>
      </tr>
      <?php } ?>
    </tbody>
    <?php if (!empty($voucher_records->output)) { ?>
    <tfoot>
      <tr class="fw-bold">
        <td colspan="5">Total</td>
        <td class="text-end"><?php echo number_format($grand_tax, 2); ?></td>
        <td class="text-end"><?php echo number_format($grand_vat, 2); ?></td>
        <td class="text-end"><?php echo number_format($grand_tax + $grand_vat, 2); ?></td>
      </tr>
    </tfoot>
    <?php } ?>
  </table>
</div>

<!-- Input VAT (DR) -->
<h6 class="mt-5">Voucher / Journal Input VAT (DR)</h6>
<div class="dt-responsive table-responsive">
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>Sr.No</th>
        <th>Date</th>
        <th>Voucher Code</th>
        <th>Voucher Type</th>
        <th>Customer/Supplier</th>
        <th>Taxable Amount</th>
        <th>VAT</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if (!empty($voucher_records->input)) {
          $i = 1;
          $grand_tax = 0;
          $grand_vat  = 0;

          foreach ($voucher_records->input as $row) {
              $taxable = $row->taxable_amount;
              $vat     = $row->vat_amount;
              $total   = $row->total;

              $grand_tax += $taxable;
              $grand_vat  += $vat;
      ?>
      <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo date('d-M-Y', strtotime($row->voucher_date)); ?></td>
        <td><?php echo $row->voucher_code; ?></td>
        <td><?php echo $row->voucher_type; ?></td>
        <td>-</td>
        <td class="text-end"><?php echo number_format($taxable, 2); ?></td>
        <td class="text-end"><?php echo number_format($vat, 2); ?></td>
        <td class="text-end"><?php echo number_format($total, 2); ?></td>
      </tr>
      <?php
          }
      } else {
      ?>
      <tr>
        <td colspan="8" class="text-center text-muted">No Input (DR) vouchers found.</td>
      </tr>
      <?php } ?>
    </tbody>
    <?php if (!empty($voucher_records->input)) { ?>
    <tfoot>
      <tr class="fw-bold">
        <td colspan="5">Total</td>
        <td class="text-end"><?php echo number_format($grand_tax, 2); ?></td>
        <td class="text-end"><?php echo number_format($grand_vat, 2); ?></td>
        <td class="text-end"><?php echo number_format($grand_tax + $grand_vat, 2); ?></td>
      </tr>
    </tfoot>
    <?php } ?>
  </table>
</div>



    <!-- Sales / Output VAT -->
    <h6 class="mt-3">Sales / Output VAT</h6>
    <div class="dt-responsive table-responsive">
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Sr.No</th>
            <th>Date</th>
            <th>Invoice No</th>
            <th>Customer</th>
            <th>Taxable Amount</th>
            <th>VAT</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (isset($sales_records) && !empty($sales_records)) {
              $i = 1;
              $grand_tax = 0;
              $grand_vat = 0;

              foreach ($sales_records as $row) {
                  $taxable = isset($row->taxable) ? $row->taxable : 0;
                  $vat = isset($row->vat) ? $row->vat : 0;
                  $total = $taxable + $vat;

                  $grand_tax += $taxable;
                  $grand_vat += $vat;
          ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo isset($row->invoice_date) ? date('d-M-Y', strtotime($row->invoice_date)) : ''; ?></td>
            <td><?php echo isset($row->invoice_code) ? $row->invoice_code : ''; ?></td>
            <td><?php echo isset($row->customer_name) ? $row->customer_name : ''; ?></td>
            <td class="text-end"><?php echo number_format($taxable, 2); ?></td>
            <td class="text-end"><?php echo number_format($vat, 2); ?></td>
            <td class="text-end"><?php echo number_format($total, 2); ?></td>
          </tr>
          <?php
              }
          } else {
          ?>
          <tr>
            <td colspan="7" class="text-center text-muted">No sales records found for selected date range.</td>
          </tr>
          <?php } ?>
        </tbody>
        <?php if (isset($sales_records) && !empty($sales_records)) { ?>
        <tfoot>
          <tr class="fw-bold">
            <td colspan="4">Total</td>
            <td class="text-end"><?php echo number_format($grand_tax, 2); ?></td>
            <td class="text-end"><?php echo number_format($grand_vat, 2); ?></td>
            <td class="text-end"><?php echo number_format($grand_tax + $grand_vat, 2); ?></td>
          </tr>
        </tfoot>
        <?php } ?>
      </table>
    </div>

    <!-- Purchase / Input VAT -->
    <h6 class="mt-5">Purchase / Input VAT</h6>
    <div class="dt-responsive table-responsive">
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Sr.No</th>
            <th>Date</th>
            <th>GRN / PO No</th>
            <th>Supplier</th>
            <th>Taxable Amount</th>
            <th>VAT</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (isset($purchase_records) && !empty($purchase_records)) {
              $i = 1;
              $grand_tax = 0;
              $grand_vat = 0;

              foreach ($purchase_records as $row) {
                  $taxable = isset($row->taxable) ? $row->taxable : 0;
                  $vat = isset($row->vat) ? $row->vat : 0;
                  $total = $taxable + $vat;

                  $grand_tax += $taxable;
                  $grand_vat += $vat;
          ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo isset($row->grn_date) ? date('d-M-Y', strtotime($row->grn_date)) : ''; ?></td>
            <td><?php echo isset($row->grn_code) ? $row->grn_code : ''; ?></td>
            <td><?php echo isset($row->supplier_name) ? $row->supplier_name : ''; ?></td>
            <td class="text-end"><?php echo number_format($taxable, 2); ?></td>
            <td class="text-end"><?php echo number_format($vat, 2); ?></td>
            <td class="text-end"><?php echo number_format($total, 2); ?></td>
          </tr>
          <?php
              }
          } else {
          ?>
          <tr>
            <td colspan="7" class="text-center text-muted">No purchase records found for selected date range.</td>
          </tr>
          <?php } ?>
        </tbody>
        <?php if (isset($purchase_summary) && !empty($purchase_summary)) { ?>
        <tfoot>
          <tr class="fw-bold">
            <td colspan="4">Total</td>
            <td class="text-end"><?php echo number_format($grand_tax, 2); ?></td>
            <td class="text-end"><?php echo number_format($grand_vat, 2); ?></td>
            <td class="text-end"><?php echo number_format($grand_tax + $grand_vat, 2); ?></td>
          </tr>
        </tfoot>
        <?php } ?>
      </table>
    </div>
  </div>
</div>
