<div class="card">

    <div class="card-header">
        <h4>Commission Payment</h4>
    </div>

    <div class="card-body">
        <form method="post"
              action="<?=base_url()?>index.php/Hr/save_commission_payment">
            <input type="hidden"
                   name="transaction_id"
                   value="<?= $record->transaction_id;?>">
            <div class="row">
                <div class="col-md-6">
                    <label>Invoice</label>
                    <input type="text"
                           class="form-control"
                           value="<?= $record->invoice_code;?>"
                           readonly>
                </div>

                <div class="col-md-6">
                    <label>Sales Representative</label>
                    <input type="text"
                           class="form-control"
                           value="<?= $record->sales_rep_name;?>"
                           readonly>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-4">
                    <label>Invoice Amount</label>
                    <input type="text"
                           class="form-control"
                           value="<?= number_format($record->invoice_amount,2);?>"
                           readonly>
                </div>

                <div class="col-md-4">
                    <label>Commission %</label>
                    <input type="text"
                           class="form-control"
                           value="<?= $record->commission_percent;?>"
                           readonly>
                </div>

                <div class="col-md-4">
                    <label>Commission Amount</label>
                    <input type="text"
                           class="form-control"
                           value="<?= number_format($record->commission_amount,2);?>"
                           readonly>
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col-md-4">
                    <label>Payment Date</label>
                    <input type="date"
                           name="payment_date"
                           class="form-control"
                           value="<?= date('Y-m-d');?>"
                           required>
                </div>

                <div class="col-md-4">
                    <label>Payment Mode</label>
                    <select name="payment_mode"
                            class="form-control"
                            required>

                        <option value="">Select</option>
                        <option>Cash</option>
                        <option>Bank Transfer</option>
                        <option>Cheque</option>
                        <option>Online Transfer</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Reference No</label>
                    <input type="text"
                           name="payment_reference"
                           class="form-control">
                </div>
            </div>

            <br>

            <label>Remarks</label>

            <textarea
                name="remarks"
                class="form-control"
                rows="4"><?= $record->remarks;?></textarea>

            <br>

            <button class="btn btn-success">
                Save Payment
            </button>

            <a href="<?=base_url()?>index.php/Hr/view_commission_payment_list"
               class="btn btn-secondary">
                Cancel
            </a>
        </form>
    </div>
</div>