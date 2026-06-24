<div class="card">
  <div class="card-body">
    <h5>
      Tax Summary 
      (<?php echo isset($from_date) ? date('d-M-Y', strtotime($from_date)) : ''; ?> 
       to 
       <?php echo isset($to_date) ? date('d-M-Y', strtotime($to_date)) : ''; ?>)
    </h5>

     <!-- Voucher VAT Summary -->
    <h6 class="mt-4">Voucher VAT Summary</h6>
    <div class="dt-responsive table-responsive">
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Type</th>
            <th>Taxable Amount</th>
            <th>VAT</th>
            <th>Total (Taxable + VAT)</th>
          </tr>
        </thead>
        <tbody>
          <?php if (isset($voucher_summary) && !empty($voucher_summary)) { ?>
          <tr>
            <td>Input VAT (Dr)</td>
            <td class="text-end"><?php echo number_format($voucher_summary->input['taxable'], 2); ?></td>
            <td class="text-end"><?php echo number_format($voucher_summary->input['vat'], 2); ?></td>
            <td class="text-end"><?php echo number_format($voucher_summary->input['total'], 2); ?></td>
          </tr>
          <tr>
            <td>Output VAT (Cr)</td>
            <td class="text-end"><?php echo number_format($voucher_summary->output['taxable'], 2); ?></td>
            <td class="text-end"><?php echo number_format($voucher_summary->output['vat'], 2); ?></td>
            <td class="text-end"><?php echo number_format($voucher_summary->output['total'], 2); ?></td>
          </tr>
          <?php } else { ?>
          <tr>
            <td colspan="4" class="text-center text-muted">No voucher VAT data found.</td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <!-- Output VAT (Sales) -->
    <h6 class="mt-4">Output VAT (Sales)</h6>
   <div class="dt-responsive table-responsive">
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>Sr.No</th>
        <th>Taxable Amount</th>
        <th>VAT</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if (isset($sales_records) && !empty($sales_records)) {
          // since now it’s a single summary row (not multiple dates)
          $taxable = isset($sales_records->taxable) ? $sales_records->taxable : 0;
          $vat = isset($sales_records->vat) ? $sales_records->vat : 0;
      ?>
      <tr>
        <td>1</td>
        <td class="text-end"><?php echo number_format($taxable, 2); ?></td>
        <td class="text-end"><?php echo number_format($vat, 2); ?></td>
      </tr>
      <?php
      } else {
      ?>
      <tr>
        <td colspan="3" class="text-center text-muted">No sales records found.</td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

    <!-- Input VAT (Purchases / GRN) -->
<h6 class="mt-4">Input VAT (Purchases)</h6>
<div class="dt-responsive table-responsive">
  <table class="table table-striped table-bordered">
    <thead>
      <tr>
        <th>Sr.No</th>
        <th>Taxable Amount</th>
        <th>VAT</th>
        <th>Total (Taxable + VAT)</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if (isset($purchase_summary) && !empty($purchase_summary)) {
          $i = 1;
          $taxable = isset($purchase_summary->taxable) ? $purchase_summary->taxable : 0;
          $vat     = isset($purchase_summary->vat) ? $purchase_summary->vat : 0;
          $total   = isset($purchase_summary->total) ? $purchase_summary->total : ($taxable + $vat);
      ?>
      <tr>
        <td><?php echo $i++; ?></td>
        <td class="text-end"><?php echo number_format($taxable, 2); ?></td>
        <td class="text-end"><?php echo number_format($vat, 2); ?></td>
        <td class="text-end"><?php echo number_format($total, 2); ?></td>
      </tr>
      <?php
      } else {
      ?>
      <tr>
        <td colspan="4" class="text-center text-muted">No purchase records found.</td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

  </div>
</div>
