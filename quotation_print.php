<?php
$this->load->helper('menu_helper.php');

// Company Details
foreach ($comapny_records as $row) {
	$company_name = $row->company_name;
	$company_address = $row->company_address;
	$company_city = $row->company_city;
	$company_pincode = $row->company_pincode;
	$company_country = $row->company_country;
	$company_email_id = $row->company_email_id;
	$company_telephone = $row->company_telephone;
	$company_website = $row->company_website;
	$company_TRN = $row->company_TRN;
}

// Quotation Details
foreach ($records1 as $row) {
	$qtn_code = $row->quotation_code;
	$customer_name = $row->cust_name;
	$project_name = $row->project_name;
	$city = $row->billing_city;
	$country = $row->billing_country;
	$location = $row->location;
	$qtn_date = date('d-M-Y', strtotime($row->quotation_date));
	$client_ref = $row->client_ref;
	$curr = $row->currabrev;
	$subject = $row->subject;
	$vat_percent = $row->vat_percent;
	$dis_percent = $row->discount_percent;
	$dis_amt = $row->discount;
	$validity = $row->validity;
	$availability = $row->availability;
	$recommendation = $row->recommendation;
	$remark = $row->remark;
	$cust_state = $row->billing_state;
	$cust_country = $row->billing_country;
	$description = $row->description;
	$pterm = $row->payment_term1;
	$dterm = $row->delivery_term;
	$price_term = $row->price_term;
	$gterm = $row->gen_term;
	$note = $row->note_terms;
	$cp_name = $row->cp_name;
	$cp_mobile = $row->cp_mobile;
	$cp_email = $row->cp_email;
	$enq_date = date('d-M-Y', strtotime($row->enq_date));
	$username = $row->user_name;
	$contact_no = $row->contact_no;
	$quot_doc = $row->quot_doc_path;
	$sub_total = $row->sub_total;
	$grand_total = $row->grand_total;
	$profit_amt = $row->profit_amt;
    $profit_percent = $row->profit_percent;
$asset_no = $row->asset_no ?? '';
$counter_no = $row->counter_no ?? '';
}
$labour_total = 0;
$labour_lines = [];

if (!empty($charges)) {
    foreach ($charges as $ch) {
        $labour_total += (float)$ch->amount;
$labour_amount= $ch->amount;
$labour_rate= $ch->rate;

        $line = htmlspecialchars($ch->description);
        if (!empty($ch->hours)) {
             $line .= ' - ' . rtrim(rtrim(number_format($ch->hours, 2), '0'), '.') . ' Hrs';
        }

        $labour_lines[] = $line;
    }
}

$expense_total = 0;
$expense_lines = [];

if (!empty($expenses)) {
    foreach ($expenses as $exp) {
        $expense_total += (float)$exp->amount;
        $exp_amount= $exp->amount;
        $exp_rate=$exp->rate;
        $line = htmlspecialchars($exp->description);

        // if (!empty($exp->qty) && !empty($exp->rate)) {
        //     $line .= ' (' . $exp->qty . ' × ' . number_format($exp->rate, 2) . ')';
        // }

        $expense_lines[] = $line;
    }
}

//$grand_total = $grand_total + $labour_total + $expense_total;

// $product_row_count = 0;
// foreach ($records2 as $productss) {
//     $product_row_count += count($productss);
// }

$total_lines = 0;

/* PRODUCTS */
foreach ($records2 as $category => $products) {

    if (!empty($category)) {
        $total_lines += 1; // category title
    }

    foreach ($products as $p) {

        $total_lines += 1; // base product row

        if (!empty($p['brand']))  $total_lines += 1;
        if (!empty($p['model']))  $total_lines += 1;
        if (!empty($p['country_name'])) $total_lines += 1;

        if (!empty($p['tech_data'])) {
            // approx 40 chars per line
            $total_lines += ceil(strlen(strip_tags($p['tech_data'])) / 40);
        }
    }
}

/* LABOUR */
if (!empty($labour_lines)) {
    $total_lines += 1; // Labour title
    $total_lines += count($labour_lines);
}

/* EXPENSE */
if (!empty($expense_lines)) {
    $total_lines += 1; // Expense title
    $total_lines += count($expense_lines);
}
$stretch_required = ($total_lines < 10);


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>

body {
    font-family: Arial, sans-serif;
    font-size: 12px;
    margin: 0;
    padding: 0;
}
/* Remove border only from product rows */ 
/* -----------------------------------------------------------
   PRINT SETTINGS
------------------------------------------------------------- */
@media print {

    /* SPACE RESERVED FOR HEADER */
    .header-space { height: 130px; }

    /* SPACE RESERVED FOR FOOTER */
    .footer-space { height: 90px !important; }

    /* FIXED HEADER */
    .fixed-header {
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
    }

    /* FIXED FOOTER */
    .fixed-footer {
        position: fixed;
        bottom: 0;
        width: 100%;
        z-index: 1000;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    th, td {
        padding: 6px;
        vertical-align: top;
        /* border-right:1px solid #9a9494ff; */
    }

    /* Prevent row break */
    tr { 
        page-break-inside: avoid !important;
    }

    thead { display: table-header-group; }
    tfoot { display: table-footer-group; }

    /* MAIN CONTENT AREA */
    .content-wrapper {
        margin-top: 20px;
        margin-bottom: 190px !important;
    }
     .page-break {
        page-break-before: always;
    }
    /* Stretch table when only one product */
.stretch-row td {
    height: 200px;   
}

}
.product-table tbody tr td {
    border-top: 0 !important;
    border-bottom: 0 !important;
}
.print-bg {
    background:#80A940;
    border:2px solid #80A940;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
}
</style>
</head>

<body>

<!-- ============================================================
     FIXED HEADER OUTSIDE TABLE STRUCTURE
=============================================================== -->
<div class="fixed-header">
    <table style="border:0;">
        <tr>
            <td style="border:0;">
                <img src="<?= base_url() ?>public/logo/print_header_new.jpg" width="300">
            </td>

            <td style="border:0; text-align:right; font-size:14px; line-height:22px;">
				<div style="float: left; width: 14px; height: 100px; background: #80a940; margin-left: 45px;">&nbsp;</div>
                <div style="margin-bottom:5px;">
                    <span style="background:#80A940; color:#fff; padding:5px 10px; font-size:18px;">
                        QUOTATION
                    </span>
                    <?= ($rev_version > 0) ? $qtn_code . "-" . $rev_version : $qtn_code ?>
                </div>

                <b><?= $qtn_date ?></b><br>
                <b>VALIDITY : <?= $validity ?></b>
            </td>
        </tr>
    </table>
</div>

<!-- ============================================================
     FIXED FOOTER
=============================================================== -->


<!-- ============================================================
     MAIN PAGE FRAME (reserves header & footer)
=============================================================== -->
<table>
    <thead>
        <tr><td class="header-space"></td></tr>
    </thead>

    <tbody>
        <tr>
            <td>

                <!-- ======================================================
                     MAIN CONTENT AREA
                ======================================================= -->
                <div class="content-wrapper">

                   	<!-- Client Info -->
                     <div style="line-height: 1.4;">
                        <p><b>To</b><br>
                        <?php if (!empty($customer_name)): ?>
                        <?= $customer_name ?> - 
                         <?php if (!empty($location)): ?>
                                <?= $location ?><br>
                        <?php endif; ?>                   
                          <?php endif; ?>
                         <?php if (!empty($billing_address)): ?>
                            <?= $billing_address ?><br>
                        <?php endif; ?>

                        <?php if (!empty($customer_contact_no)): ?>
                            <?= $customer_contact_no ?><br>
                        <?php endif; ?> 
                         <?php if (!empty($email_id)): ?>
                            <?= $email_id ?><br>
                        <?php endif; ?>                    
                        <?php if (!empty($trn_no)): ?>
                            
                            TRN No:<?= $trn_no ?>
                            
                        <?php endif; ?>  <br>            
                                
                        <?php if (!empty($cp_display)): ?>
                            Kind Attn: <?= $cp_display ?>
                        <?php endif; ?>

                        </div>

                    <p>
                        Dear Sir,<br>
                        We thank you for your enquiry and are pleased to quote our best prices for your requirements.
                    </p>

                    <h4 style="margin-bottom:4px;"><?= $project_name ?></h4>
                    <?php if (!empty($asset_no)): ?>
                    <div style="line-height:1.5;">
                        <b>ASSET NO:</b> <?= $asset_no ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($counter_no)): ?>
                    <div style="line-height:1.5;">
                        <b>COUNTER NO:</b> <?= $counter_no ?>
                    </div>
                <?php endif; ?>

                    <!-- ==================================================
                         PRODUCT TABLE
                    ==================================================== -->
                   <table class="product-table" cellpadding="10" border="1" width="95%"
       style="border-collapse:collapse; margin-top:12px;">
                        <thead>
                            <tr>
                                <th>S.N</th>

                                <?php $colspan = ($pt_flag == 0 && $eo_flag != 1) ? 1 : 2; ?>
                                <th colspan="<?= $colspan ?>">WORK DESCRIPTION</th>

                                <?php if ($pt_flag == 0 && $eo_flag != 1): ?>
                                    <th>IMAGE</th>
                                <?php endif; ?>

                                <th>QTY</th>
                                <th>PRICE</th>
                                <th>TOTAL</th>
                            </tr>
                        </thead>

                        <tbody style="border-left:1px solid #9a9494ff;border-right:1px solid #9a9494ff;border-bottom:1px solid #9a9494ff;">
                        <?php
                        $i = 1;
                        $net_total = 0;
                        $net_vat = 0;

                        foreach ($records2 as $category => $products):


                             foreach ($products as $p): ?>

                                    <tr>
                                    <td ><?= $i++ ?></td>
                                    <td colspan="<?= $colspan ?>" style="line-height:1.3;">
                                    <?php if (!empty($p['brand'])): ?>
                                        BRAND: <?= htmlspecialchars($p['brand']) ?><br>
                                    <?php endif; ?>

                                    <?php if (!empty($p['model'])): ?>
                                        MODEL: <?= htmlspecialchars($p['model']) ?>
                                    <?php endif; ?>
                                </td>
                                    <td ></td>
                                    <td ></td>
                                    <td ></td>
                                    
                                </tr>

                              <?php    endforeach;  
                            if (!empty($category)):
                        ?>
                               <tr>
                                    <td ></td>
                                    <td colspan="<?= $colspan ?>"><b><?= $category ?></b></td>
                                    <td ></td>
                                    <td ></td>
                                    <td ></td>
                                    
                                </tr>
                        <?php
                            endif;

                            foreach ($products as $p):
                                $total = $p['total'];
                                $vat = $total * ($vat_percent/100);
                                $net_total += $total;
                                //$net_vat += $vat;
                                
                        ?>
                               <tr>
                                    <td></td>

                                   <td colspan="<?= $colspan ?>" style="line-height:1.2; padding-top:4px; padding-bottom:4px;">
    <strong><?= $p['product_name'] ?></strong><br>


    <?php if (!empty($p['country_name'])): ?>
        Made in <?= htmlspecialchars($p['country_name']) ?><br>
    <?php endif; ?>

<?= nl2br(htmlspecialchars(trim($p['tech_data'] ?? '')), false) ?>
</td>


                                    <?php if ($pt_flag == 0 && $eo_flag != 1): ?>
                                        <td>
                                            <img src="<?= base_url('public/uploaded_documents/prd_imgs/'.$p['prd_img']); ?>" width="60" height="50">
                                        </td>
                                    <?php endif; ?>

                                    <td><?= $p['quantity'] ?></td>
                                    <td style="text-align:right"><?= $p['price'] ?></td>
                                    <td style="text-align:right"><?= number_format($total, 2) ?></td>
                                </tr>
                        <?php endforeach; endforeach; ?>

<?php if ($labour_total > 0  || $expense_total > 0) { ?>
<tr>
    <td></td>
     <td colspan="<?= $colspan ?>" style="text-align:left; padding-left:10px;">

       <?php if ($labour_total > 0) { ?>
    <div style="margin-bottom:4px;">
        <b>Labour Charges :</b>
    </div>

    <?php foreach ($labour_lines as $line) { ?>
        <div style="padding-left:15px; margin-bottom:2px;">
            • <?= $line ?>
        </div>
    <?php } ?>
<?php } ?>

       <?php if ($expense_total > 0) { ?>
    <div style="margin-top:6px; margin-bottom:4px;">
        <b>Other Expenses :</b>
    </div>

    <?php foreach ($expense_lines as $line) { ?>
        <div style="padding-left:15px; margin-bottom:2px;">
            • <?= $line ?>
        </div>
    <?php } ?>
<?php } ?>

    </td>

    <?php if ($pt_flag == 0 && $eo_flag != 1): ?>
                                    <td></td>
                                <?php endif; ?>
       
    

    <td></td>
    <td style="text-align:right">
        <?php if ($labour_total > 0) { ?>
            <div>&nbsp;</div>
            <?php foreach ($charges as $ch) { ?>
                <div style="margin-bottom:2px;">
                    <?= number_format($ch->rate, 2) ?>
                </div>
            <?php } ?>
        <?php } ?>
    
    <?php if ($expense_total > 0) { ?>
            <div style="margin-top:6px;">&nbsp;</div>
            <?php foreach ($expenses as $exp) { ?>
                <div style="margin-bottom:2px;">
                    <?= number_format($exp->rate, 2) ?>
                </div>
            <?php } ?>
        <?php } ?>
</td>
    <td style="text-align:right; vertical-align:top;">

    <?php if (!empty($charges)) { ?>
        <div>&nbsp;</div> <!-- aligns with "Labour Charges :" title -->

        <?php foreach ($charges as $ch) { ?>
            <div style="margin-bottom:2px;">
                <?= number_format($ch->amount, 2) ?>
            </div>
        <?php } ?>
    <?php } ?>

    <?php if (!empty($expenses)) { ?>
        <div style="margin-top:6px;">&nbsp;</div> <!-- aligns with "Other Expenses :" title -->

        <?php foreach ($expenses as $exp) { ?>
            <div style="margin-bottom:2px;">
                <?= number_format($exp->amount, 2) ?>
            </div>
        <?php } ?>
    <?php } ?>

</td>

</tr>
<?php } ?>
<?php if ($stretch_required) { ?>
<tr class="stretch-row">
    <td></td>
    <td colspan="<?= $colspan ?>"></td>

    <?php if ($pt_flag == 0 && $eo_flag != 1): ?>
        <td></td>
    <?php endif; ?>

    <td></td>
    <td></td>
    <td></td>
</tr>
<?php } ?>



<?php

$taxable_amount = $net_total + $labour_total + $expense_total;


if ($dis_amt > 0) {
    $taxable_amount -= $dis_amt;
}

$net_vat   = 0;
$profit_amt = 0;

if ($eo_flag == 1) {

    if ($profit_percent > 0) {
        $profit_amt = $taxable_amount * ($profit_percent / 100);
    }

    $grand_total = $taxable_amount + $profit_amt;

} else {

    if ($vat_percent > 0) {
        $net_vat = $taxable_amount * ($vat_percent / 100);
    }

    $grand_total = $taxable_amount + $net_vat;
}

?>



                        <tr style="border-top:1px solid #9a9494ff;">
                            <td colspan="5" style="text-align:right;"><b>TOTAL AMOUNT</b></td>
                            <td style="text-align:right"><?= number_format($sub_total, 2) ?></td>
                        </tr>

                        <?php if ($dis_amt > 0): ?>
                        <tr style="border-top:1px solid #9a9494ff;">
                            <td colspan="5" style="text-align:right;"><b>SPECIAL DISCOUNT</b></td>
                            <td style="text-align:right"><?= number_format($dis_amt,2) ?></td>
                        </tr>
                        <?php endif; ?>

                       <?php if ($eo_flag == 0) { ?>
                        <tr style="border-top:1px solid #9a9494ff;">
                            <td colspan="5" style="text-align:right;"><b>VAT <?php echo $vat_percent; ?>%</b></td>
                            <td style="text-align:right"><?php echo number_format($net_vat, 2); ?></td>
                        </tr>
                        <?php } ?>
                        <?php if ($eo_flag == 1) { ?>
                        <tr style="border-top:1px solid #9a9494ff;">
                            <td colspan="5" style="text-align:right;"><b>Profit: - Supervision, Profit and Overhead <?php echo number_format($profit_percent, 2) . '%'; ?></b></td>
                            <td style="text-align:right"><?php echo number_format($profit_amt, 2); ?></td>
                        </tr>
                        <?php } ?>
               
                        <tr style="border-top:1px solid #9a9494ff;">
                            <td colspan="5" style="text-align:right;"><b>GRAND TOTAL</b></td>
                            <td style="text-align:right"><?= number_format($grand_total, 2) ?></td>
                        </tr>

                        <tr style="border-top:1px solid #9a9494ff;">
                            <td colspan="6">AED *** <b><?= convert_number_to_words($grand_total) ?></b></td>
                        </tr>

                        </tbody>
                    </table>

                    <br><br>

                    <!-- Terms -->
                    
                 <div class="page-break"></div>

                    <!-- GENERAL TERMS -->
                    <h3>GENERAL TERMS & CONDITIONS</h3>

                    <p>1. Terms of Payment : <?= $pterm ?></p>
                    <p>2. Delivery : <?= $dterm ?></p>
                    <p>3. Validity : <?= $validity ?></p>

                    <?php 
                    foreach (explode("\n", trim($note)) as $n):
                        echo "<p>".nl2br(htmlspecialchars(trim($n)))."</p>";
                    endforeach;
                    ?>

                    <br><table style="width:100%; border-collapse: collapse;">
                            <tr>
                                <td style="width:50%; vertical-align: top; padding:5px;">
                                    <p>
                                        <!-- <b>Contact Persons:</b><br> -->
                                        <!-- Mr. Nikhil – 052 875 2410<br>
                                        Mr. Akbar – 056 4140 893<br> -->
                                        Sales Person : <?php echo $username; ?>
                                    </p>
                                </td>

                                <td style="width:50%; text-align:right; vertical-align: top; padding:5px;">
                                    <img src="<?= base_url('public/logo/stamp.jpeg'); ?>" width="200" height="90">
                                </td>
                            </tr>
                        </table>


                </div>

            </td>
        </tr>
      <tr>
    <td style="padding-top:5px;">
        <?php if (!empty($quot_doc)):

            $filePath = base_url('public/uploded_documents/' . $quot_doc);
            $fileExt  = strtolower(pathinfo($quot_doc, PATHINFO_EXTENSION));
        ?>

        <?php if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif'])): ?>

            <img src="<?= $filePath; ?>" style="width:100%; height:750px; display:block;" />

      <?php elseif ($fileExt === 'pdf'): ?>

        <iframe
            src="<?= $filePath; ?>#toolbar=0&navpanes=0&scrollbar=0"
            width="100%"
            height="790px"
            style="border:none;"
        ></iframe>

        <?php endif; ?>

        <?php endif; ?>
    </td>
</tr>

    </tbody>

    <tfoot>
        <tr><td class="footer-space">
			<div class="fixed-footer">
				<table style="width:100%; border:0;">
					<tr>
						<td style="border:0;">
							<img src="<?= base_url().'public/logo/print_footer_new.jpg' ?>" width="120" style="float:left;">
						</td>

						<td style="border:0; text-align:right;">
							<img src="<?= base_url().'public/logo/print_footer.png' ?>" width="380">
						</td>
					</tr>
				</table>
			</div>
		</td></tr>
    </tfoot>
</table>


</body>
</html>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        window.print();
    });
</script>
