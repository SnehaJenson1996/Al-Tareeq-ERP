<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- CUSTOMER / QUOTATION DETAILS -->
<?php if (!empty($records1)) { foreach ($records1 as $row) { ?>

<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered bg-soft-green">
            <tr>
                <th width="25%">Customer</th>
                <td><?php echo $row->customer_name; ?></td>
            </tr>
            <tr>
                <th>Project Name</th>
                <td><?php echo $row->project_name; ?></td>
            </tr>
        </table>

        <!-- Hidden Fields -->
        <input type="hidden" name="customer_id" value="<?php echo $row->customer_id; ?>">
        <input type="hidden" name="enq_id" value="<?php echo $row->enq_master_id; ?>">
        <input type="hidden" name="quote_id" value="<?php echo $row->quote_id; ?>">
        <input type="hidden" name="revision" value="<?php echo $row->revision; ?>">
        <input type="hidden" id="branch_id_ajax" value="<?php echo $row->branch_id; ?>">
    </div>
</div>

<?php } } ?>

<hr>

<!-- ITEMS TABLE -->
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered table-striped">
           <thead>
<tr>
    <th>#</th>
    <th>System</th>
    <th>Price (AED + VAT)</th>
    <th>Qty</th>

    <?php
    if($row->contract_type == 'Yearly')
    {
        for($i=1;$i<=$row->no_of_years;$i++)
        {
            echo "<th>Total Price {$i} Year</th>";
        }
    }
    else
    {
        for($i=1;$i<=$row->no_of_quarters;$i++)
        {
            echo "<th>Q{$i}</th>";
        }
    }
    ?>

    <th>Final Total Rates</th>
</tr>
</thead>

            <tbody id="mytbbody">
            <?php
            $i = 1;
            $row_id = 5000;

            if (!empty($records2)) {
                foreach ($records2 as $r) {
            ?>
                <tr id="addr<?php echo $row_id; ?>">
                    <td><?php echo $i; ?></td>

                    <td>
                        <input type="hidden" name="product_id[]" value="<?php echo $r->product_id; ?>">
                        <input type="text" class="form-control" value="<?php echo $r->product_name ?? $r->product_id; ?>" readonly>
                    </td>

                    <td>
                        <input type="text" class="form-control qty" name="qty[]" value="<?php echo $r->quantity; ?>">
                    </td>

                    <td>
                        <input type="text" class="form-control" name="price[]" value="<?php echo $r->price; ?>">
                    </td>

                   <?php
$period_count = ($row->contract_type=='Yearly')
                ? $row->no_of_years
                : $row->no_of_quarters;

$period_total = $r->quantity * $r->price;

for($p=1;$p<=$period_count;$p++)
{
?>
    <td>
        <input type="text"
               class="form-control"
               value="<?php echo number_format($period_total,2); ?>"
               readonly>
    </td>
<?php
}
?>

<td>
    <input type="text"
           class="form-control"
           value="<?php echo number_format($period_total * $period_count,2); ?>"
           readonly>
</td>
                    <td class="text-center">
                        <input type="hidden" name="brand[]" value="<?php echo $r->brand ?? ''; ?>">
                        <input type="hidden" name="trans_id[]" value="<?php echo $r->trans_id ?? ''; ?>">
                        <button type="button" class="btn btn-danger btn-sm" onclick="remove_row('<?php echo $row_id; ?>')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php
                $i++;
                $row_id++;
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<hr>

<!-- TOTAL SECTION -->
<?php if (!empty($records1)) { foreach ($records1 as $row) { ?>
<div class="row">
    <div class="col-md-4 offset-md-8">
        <table class="table table-bordered">
            <tr>
                <th>Sub Total</th>
                <td>
                    <input type="text" class="form-control" name="sub_total" value="<?php echo $row->sub_total; ?>">
                </td>
            </tr>
             <tr>
                <th>AMC Discount</th>
                <td>
                    <input type="text" class="form-control" name="amc_discount" value="<?php echo $row->amc_discount; ?>">
                </td>
            </tr>

            <tr>
    <th>Discount %</th>
    <td>
        <input type="text" class="form-control mb-1"
               name="discount_percent"
               value="<?= $row->discount_percent ?? 0 ?>">

        <input type="text" class="form-control"
               name="discount_amt"
               value="<?= $row->discount ?? 0 ?>">
               
    </td>
</tr>


            <tr>
                <th>VAT %</th>
                 <td>
        <input type="text" class="form-control mb-1"
               name="vat_percent"
               value="<?= $row->vat_percent ?? 0 ?>">

        <input type="text" class="form-control"
               name="vat_amt"
               value="<?= $row->vat_amt ?? 0 ?>">
               
    </td>
               
            </tr>

          
            <tr>
                <th>Grand Total </th>
                <td>
                    <input type="text" class="form-control" name="grand_total" value="<?php echo $row->grand_total; ?>">
                </td>
            </tr>
        </table>
    </div>
</div>
<?php } } ?>

<script>
function remove_row(id)
{
    if (confirm('Remove this row?')) {
        document.getElementById('addr' + id).remove();
    }
}
</script>
