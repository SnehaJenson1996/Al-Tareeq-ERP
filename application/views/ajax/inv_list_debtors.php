<table class="table table-bordered table-hover" id="dr_table">
    <thead>
        <tr>
            <th width='10%'>Select</th>
            <th title="Item">Invoice Date</th> 
            <th title="Item">Invoice No</th> 
            <th title="Item">Total Amount</th> 
            <th title="Item">Balance Amount</th> 
            <th title="Item">Debit Amount</th>  
        </tr>
    </thead>
    <tbody id="dr_body">
        <tr><td colspan="6"><input type="hidden" name="selected_tr" id="selected_tr"></td></tr>

        <?php 
        if (isset($records1) && !empty($records1)) {
            foreach ($records1 as $r) { ?>
                <tr id='dr_addr0'>
                    <td>
                        <input type="checkbox" id="invoiceID" class="case" name="invoiceID[]" 
                            value="<?php echo $r->invoice_id; ?>" 
                            data-invoice-code="<?= $r->invoice_code ?>" 
                            onclick="p_check();" />
                    </td>

                    <td><?php echo $r->invoice_date; ?></td>   
                    <td>
                        <?php echo $r->invoice_code; ?>
                        <input type="hidden" name="invoice_code[<?= $r->invoice_id ?>]" 
                            value="<?php echo htmlspecialchars($r->invoice_code); ?>" />
                    </td>   

                    <td><?php echo $r->grand_total; ?></td>   
                    <td><?php echo ($r->grand_total - $r->paid_amt); ?></td>   

                    <td>
                        <input type="number" step="0.01"
                            name="dr_amount[<?= $r->invoice_id ?>]"  
                            id="dr_amount<?= $r->invoice_id ?>" 
                            class="form-control form-control-sm debit_sum" 
                            required 
                            min="0" 
                            max="<?php echo ($r->grand_total - $r->paid_amt); ?>" 
                            onkeyup="calculate_grand_total()" />
                        <input type="hidden" name="customer_id" id="customer_id" 
                            value="<?php echo $r->invoice_customer; ?>"> 
                    </td>
                </tr>
        <?php 
            } 
        } else { ?>
            <tr>
                <td colspan="6" class="text-center text-muted">No invoices found</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script>
function calculate_grand_total() {
    let total = 0;
    alert('ddd');
    document.querySelectorAll('.debit_sum').forEach(function(input) {
        if (!input.disabled && input.value) {
            total += parseFloat(input.value) || 0;
        }
    });
    document.getElementById('debit_total').value = total.toFixed(2);
}
</script>