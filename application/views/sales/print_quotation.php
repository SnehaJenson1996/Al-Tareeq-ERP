<!DOCTYPE html>
<html>
<head>

<title>Quotation</title>

<style>

body{
    font-family: Arial, sans-serif;
    font-size:13px;
    color:#000;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th,
table td{
    border:1px solid #000;
    padding:8px;
}

.header-table td{
    border:none;
}

.company-name{
    font-size:22px;
    font-weight:bold;
}

.title{
    text-align:center;
    font-size:20px;
    font-weight:bold;
    margin:15px 0;
}

.label{
    font-weight:bold;
}

.no-border td{
    border:none;
    padding:4px;
}

.summary td:first-child{
    text-align:right;
    font-weight:bold;
}

.terms{
    border:1px solid #000;
    padding:10px;
    min-height:60px;
}


@media print {

    .print-btn{
        display:none;
    }

}

</style>

</head>


<body>





<!-- HEADER -->

<table style="width: 100%; border-collapse: collapse; border: none;">
    <tr>
        <!-- LEFT SIDE: Company Logo -->
        <td width="30%" style="vertical-align: top; border: none; padding: 0;">
            <?php if(!empty($company['company_logo'])) { ?>
                <img src="<?= base_url($company['company_logo']) ?>" 
                     style="width:300px; height:auto; max-height:150px; object-fit:contain; display:block;">
            <?php } ?>
        </td>

        <!-- RIGHT SIDE: Barcode Section -->
        <td width="70%" style="text-align: right; vertical-align: top; border: none; padding: 0;">
            <div class="barcode-container" style="display: inline-block; text-align: right;">
                <!-- Renders the actual barcode image using your path -->
                <img src="<?= base_url('uploads/company/barcode.png') ?>" 
                     alt="Barcode" 
                     style="height: 70px; width: 100px; max-width: 350px; display: block; margin-left: auto;">
                     <div style="font-family: Arial, sans-serif; font-size: 12px; color: #333; margin-top: 5px; font-weight: bold; text-align: right;">
                        TRN: <?= $company['company_trn'] ?>
                    </div>
            </div>
        </td>
    </tr>
</table>

<!-- Customer Information Section -->
<div class="customer-info-section" style="width: 100%; margin-top: 20px; font-family: Arial, sans-serif; font-size: 13px; line-height: 1.6; color: #333;">
    <table style="width: 100%; border-collapse: collapse; border: none;">
        <tr>
            <!-- LEFT SIDE: Metadata & Contacts (Object Syntax) -->
            <td style="width: 50%; vertical-align: top; border: none; padding: 0;">
                <table style="width: 100%; border-collapse: collapse; border: none;">
                    <?php if(!empty($quotation->customer_code)) { ?>
                    <tr>
                        <td style="width: 100px; font-weight: bold; padding: 2px 0; border: none;">Cust. Code</td>
                        <td style="padding: 2px 0; border: none;">: <?= $quotation->customer_code ?></td>
                    </tr>
                    <?php } ?>
                    
                 <tr>
    <td style="font-weight: bold; padding: 2px 0; border: none; width: 100px; vertical-align: top;">M/s</td>
    <td style="padding: 2px 0; border: none; vertical-align: top;">: <?= !empty($quotation->customer_name) ? $quotation->customer_name : '' ?></td>
</tr>

<?php if(!empty($quotation->customer_address)) { ?>
<tr>
    <td style="font-weight: bold; padding: 2px 0; border: none; vertical-align: top;">Address</td>
    <td style="padding: 2px 0; border: none; vertical-align: top;">: <?= nl2br($quotation->customer_address) ?></td>
</tr>
<?php } ?>
                    
                    <?php if(!empty($quotation->office_telephone)) { ?>
                    <tr>
                        <td style="font-weight: bold; padding: 2px 0; border: none;">Tel</td>
                        <td style="padding: 2px 0; border: none;">: <?= $quotation->office_telephone ?></td>
                    </tr>
                    <?php } ?>
                    
                    <?php if(!empty($quotation->office_fax)) { ?>
                    <tr>
                        <td style="font-weight: bold; padding: 2px 0; border: none;">Fax</td>
                        <td style="padding: 2px 0; border: none;">: <?= $quotation->office_fax ?></td>
                    </tr>
                    <?php } ?>
                    
                    <?php if(!empty($quotation->tax_registration_no)) { ?>
                    <tr>
                        <td style="font-weight: bold; padding: 2px 0; border: none;">TRN</td>
                        <td style="padding: 2px 0; border: none;">: <?= $quotation->tax_registration_no ?></td>
                    </tr>
                    <?php } ?>
                    
                    <?php if(!empty($quotation->customer_email)) { ?>
                    <tr>
                        <td style="font-weight: bold; padding: 2px 0; border: none;">Contact</td>
                        <td style="padding: 2px 0; border: none;">: <?= $quotation->customer_email ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </td>

      <!-- RIGHT SIDE: Document & Transaction Details -->
            <td style="width: 50%; vertical-align: top; border: none; padding: 0;">
                <table style="width: 280px; margin-left: auto; margin-top: -15px; border-collapse: collapse; border: none;">
                    
                    <!-- Title Section -->
                    <tr>
                        <td colspan="2" style="border: none; padding-bottom: 12px;">
                            <!-- Styled to look like a premium document header -->
                            <div style="font-family: Arial, sans-serif; font-size: 22px; font-weight: bold; color: #111; letter-spacing: 2px; text-transform: uppercase; border-bottom: 2px solid #111; padding-bottom: 4px; display: inline-block; width: 100%;">
                                QUOTATION
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Reference Number -->
                    <tr>
                        <td style="width: 110px; font-family: Arial, sans-serif; font-size: 13px; font-weight: bold; padding: 2px 0; border: none;">Reference No</td>
                        <td style="font-family: Arial, sans-serif; font-size: 13px; padding: 2px 0; border: none;">: <?= !empty($quotation->quotation_code) ? $quotation->quotation_code : '' ?></td>
                    </tr>
                    
                    <!-- Quotation Date -->
                    <tr>
                        <td style="font-family: Arial, sans-serif; font-size: 13px; font-weight: bold; padding: 2px 0; border: none;">Date</td>
                        <td style="font-family: Arial, sans-serif; font-size: 13px; padding: 2px 0; border: none;">: <?= !empty($quotation->quotation_date) ? date('d/m/Y', strtotime($quotation->quotation_date)) : '' ?></td>
                    </tr>
                    
                    <!-- Validity / Terms -->
                    <tr>
                        <td style="font-family: Arial, sans-serif; font-size: 13px; font-weight: bold; padding: 2px 0; border: none;">Validity</td>
                        <td style="font-family: Arial, sans-serif; font-size: 13px; padding: 2px 0; border: none;">: <?= !empty($quotation->validity) ? $quotation->validity : '' ?></td>
                    </tr>
                    
                    <!-- Sales Representative / Prepared By -->
                    <tr>
                        <td style="font-family: Arial, sans-serif; font-size: 13px; font-weight: bold; padding: 2px 0; border: none;">Rep.</td>
                        <td style="font-family: Arial, sans-serif; font-size: 13px; padding: 2px 0; border: none;">: <?= !empty($quotation->sales_rep_id) ? $quotation->sales_rep_id : '' ?></td>
                    </tr>
                    
                    <!-- Local Purchase Order Number -->
                    <tr>
                        <td style="font-family: Arial, sans-serif; font-size: 13px; font-weight: bold; padding: 2px 0; border: none;">L.P.O Number</td>
                        <td style="font-family: Arial, sans-serif; font-size: 13px; padding: 2px 0; border: none;">: <?= !empty($quotation->lpo_no) ? $quotation->lpo_no : '' ?></td>
                    </tr>
                    
                    <!-- Document Currency -->
                    <tr>
                        <td style="font-family: Arial, sans-serif; font-size: 13px; font-weight: bold; padding: 2px 0; border: none;">Currency</td>
                        <td style="font-family: Arial, sans-serif; font-size: 13px; padding: 2px 0; border: none;">: <?= !empty($quotation->currency) ? $quotation->currency : 'AED' ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

<!-- <table class="header-table">

<tr>



<td width="70%" align="right">

<div class="company-name">

<?= $company['company_name'] ?>

</div>


<?= $company['company_address'] ?><br>

Phone :
<?= $company['company_telephone'] ?><br>

Email :
<?= $company['company_email_id'] ?>


</td>


</tr>

</table>



<hr> -->






<!-- ITEM DETAILS -->


<!-- ITEMS TABLE -->
<table style="width: 100%; border-collapse: collapse; margin-top: 25px; font-family: Arial, sans-serif; font-size: 13px; color: #333;">
    <thead>
        <tr style="background-color: #f7f7f7; border-top: 1px solid #ddd; border-bottom: 2px solid #ddd;">
            <th width="5%" style="padding: 8px 5px; font-weight: bold; text-align: center;">#</th>
            <th style="padding: 8px 10px; font-weight: bold; text-align: left;">Item Description</th>
            <th width="12%" style="padding: 8px 5px; font-weight: bold; text-align: center;">Qty</th>
            <th width="15%" style="padding: 8px 10px; font-weight: bold; text-align: right;">Rate</th>
            <th width="15%" style="padding: 8px 10px; font-weight: bold; text-align: right;">Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $i = 1;
        foreach($cart_items as $item) {
        ?>
        <tr style="border-bottom: 1px solid #eee;">
            <td style="padding: 8px 5px; text-align: center; vertical-align: top;"><?= $i++ ?></td>
            <td style="padding: 8px 10px; text-align: left; vertical-align: top;">
                <span style="font-weight: bold; color: #111;"><?= $item->product_name ?></span>
            </td>
            <td style="padding: 8px 5px; text-align: center; vertical-align: top;"><?= $item->qty ?></td>
            <td style="padding: 8px 10px; text-align: right; vertical-align: top;"><?= number_format($item->price, 2) ?></td>
            <td style="padding: 8px 10px; text-align: right; vertical-align: top;"><?= number_format($item->amount, 2) ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<br>

<!-- TOTAL SUMMARY SECTION -->
<table style="width: 100%; border-collapse: collapse; margin-top: 15px; font-family: Arial, sans-serif; font-size: 13px; color: #333;">
    <tr>
        <!-- Left spacer column to push summary right -->
        <td style="width: 50%; border: none;"></td>
        
        <!-- Right summary table column -->
        <td style="width: 50%; vertical-align: top; border: none; padding: 0;">
            <table style="width: 280px; margin-left: auto; border-collapse: collapse;">
                
                <!-- Gross Total -->
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 6px 0; font-weight: bold; text-align: left;">Gross</td>
                    <td style="padding: 6px 0; text-align: right;">: <?= number_format($quotation->sub_total, 2) ?></td>
                </tr>

                <!-- Discount -->
                <?php if(!empty($quotation->discount_amount) && $quotation->discount_amount > 0) { ?>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 6px 0; font-weight: bold; text-align: left;">Discount</td>
                    <td style="padding: 6px 0; text-align: right;">: <?= number_format($quotation->discount_amount, 2) ?></td>
                </tr>
                <?php } ?>

                <!-- VAT -->
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 6px 0; font-weight: bold; text-align: left;">VAT</td>
                    <td style="padding: 6px 0; text-align: right;">: <?= number_format($quotation->vat_amount, 2) ?></td>
                </tr>

                <!-- Grand Total -->
                <tr style="border-bottom: 2px double #111;">
                    <td style="padding: 8px 0; font-weight: bold; font-size: 15px; color: #000; text-align: left;">Net Total</td>
                    <td style="padding: 8px 0; font-weight: bold; font-size: 15px; color: #000; text-align: right;">
                        AED : <?= number_format($quotation->grand_total, 2) ?>
                    </td>
                </tr>
                
            </table>
        </td>
    </tr>
</table>


<br>



<!-- TERMS -->


<!-- TERMS, CONDITIONS & WARRANTY SECTION -->
<table style="width: 100%; border-collapse: collapse; border: none; margin-top: 25px; font-family: Arial, sans-serif; font-size: 13px; line-height: 1.5; color: #333;">
    <!-- Row 1: Payment Term & Validity -->
    <tr>
        <td style="width: 48%; vertical-align: top; border: none; padding: 0 0 15px 0;">
            <b style="color: #000; text-transform: uppercase; font-size: 12px; display: block; margin-bottom: 4px;">Payment Term</b>
            <div class="terms" style="color: #555;">
                <?= !empty($quotation->payment_term) ? nl2br($quotation->payment_term) : '---' ?>
            </div>
        </td>
        <td style="width: 4%; border: none;"></td> <!-- Spacer -->
        <td style="width: 48%; vertical-align: top; border: none; padding: 0 0 15px 0;">
            <b style="color: #000; text-transform: uppercase; font-size: 12px; display: block; margin-bottom: 4px;">Validity</b>
            <div class="terms" style="color: #555;">
                <?= !empty($quotation->validity) ? nl2br($quotation->validity) : '---' ?>
            </div>
        </td>
    </tr>

    <!-- Row 2: Warranty & Warranty Description -->
    <tr>
        <td style="vertical-align: top; border: none; padding: 0 0 15px 0;">
            <b style="color: #000; text-transform: uppercase; font-size: 12px; display: block; margin-bottom: 4px;">Warranty</b>
            <div class="terms" style="color: #555;">
                <?= !empty($quotation->warranty) ? nl2br($quotation->warranty) : '---' ?>
            </div>
        </td>
        <td style="border: none;"></td> <!-- Spacer -->
        <td style="vertical-align: top; border: none; padding: 0 0 15px 0;">
            <b style="color: #000; text-transform: uppercase; font-size: 12px; display: block; margin-bottom: 4px;">Warranty Description</b>
            <div class="terms" style="color: #555;">
                <?= !empty($quotation->warranty_description) ? nl2br($quotation->warranty_description) : '---' ?>
            </div>
        </td>
    </tr>

    <!-- Row 3: Delivery Term & Terms & Conditions -->
    <tr>
        <td style="vertical-align: top; border: none; padding: 0 0 15px 0;">
            <b style="color: #000; text-transform: uppercase; font-size: 12px; display: block; margin-bottom: 4px;">Delivery Term</b>
            <div class="terms" style="color: #555;">
                <?= !empty($quotation->delivery_term) ? nl2br($quotation->delivery_term) : '---' ?>
            </div>
        </td>
        <td style="border: none;"></td> <!-- Spacer -->
        <td style="vertical-align: top; border: none; padding: 0 0 15px 0;">
            <b style="color: #000; text-transform: uppercase; font-size: 12px; display: block; margin-bottom: 4px;">Terms & Conditions</b>
            <div class="terms" style="color: #555;">
                <?= !empty($quotation->terms_condition) ? nl2br($quotation->terms_condition) : '---' ?>
            </div>
        </td>
    </tr>
</table>

<!-- NOTES SECTION -->
<?php if(!empty($quotation->notes)) { ?>
<div class="notes-section" style="width: 100%; margin-top: 15px; font-family: Arial, sans-serif; font-size: 13px; line-height: 1.5;">
    <b style="color: #000; text-transform: uppercase; font-size: 12px; display: block; margin-bottom: 4px;">Notes</b>
    <div class="terms" style="color: #555;">
        <?= nl2br($quotation->notes) ?>
    </div>
</div>
<?php } ?>

<br><br>



<table class="no-border">


<tr>

<td width="50%">

Prepared By:

<br><br><br>

____________________

</td>


<td width="50%" align="right">

Approved By:

<br><br><br>

____________________

</td>


</tr>


</table>

<div class="footer">
     <img src="<?= base_url($company['company_footer']) ?>">
</div>

</body>

</html>

<!-- Automatically trigger print preview on load -->
<script type="text/javascript">
    window.addEventListener('DOMContentLoaded', (event) => {
        // Short delay ensures all CSS and layouts have rendered before preview opens
        setTimeout(function() {
            window.print();
        }, 500);
    });
</script>