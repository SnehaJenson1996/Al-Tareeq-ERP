<?php
$this->load->helper('menu_helper.php');

// Company details
$company = $comapny_records; 
?>

<!DOCTYPE html>
<html>

<head>
    <title>Quotation</title>
    <style>
        /* ================= PAGE SETUP ================= */
      @page {
    /* Top margin MUST be larger than your header height (25mm header + padding) */
    margin-top: 45mm; 
    margin-right: 20px;
    margin-bottom: 12mm; 
    margin-left: 20px;
}

        body {
            font-family: "Franklin Gothic Book", Arial, sans-serif;
            font-size: 13px;
            margin: 0;
            padding-bottom: 20px;
            color: #333;
        }

        /* ================= COVER PAGE ================= */
        .cover-page {
            page-break-after: always;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            margin: 0 !important;
            padding: 0 !important;
            box-sizing: border-box;
        }

        .cover-img {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
        }
        
        .cover-img img {
            width: 100%;
            height: 100%;
            object-fit: fill;
            display: block;
        }
        
        .cover-details {
            position: absolute;
            top: 200px;
            left: 45px;
            right: 45px;
            z-index: 2;
            color: #000;
            line-height: 1.8;
        }
        
        @page {
            margin-top: 100px;
            margin-right: 20px;
            margin-bottom: 12mm; /* Allocate space at the bottom for footer */
            margin-left: 20px;
        }

        /* Zero out margins and HIDE the fixed header on the very first page */
        @page :first {
            margin: 0 !important;
            .header {
                display: none !important;
                opacity: 0 !important;
                top: -1000px !important; 
            }
        }

        @media print {
            body {
                margin: 0 !important;
                padding: 0 !important;
            }
            .main-content {
                margin-top: 0;
            }
        } 

        /* ================= COMMON ================= */
        table {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
            box-sizing: border-box;
        }

        /* ================= PARTY TABLE ================= */
        .party-table {
            margin-bottom: 15px;
        }

        .party-table td {
            vertical-align: top;
            padding: 0;
        }

        .section-title {
            background: #000;
            color: #C49A00;
            font-weight: bold;
            text-align: center;
            padding: 6px;
            text-transform: uppercase;
            font-size: 13px;
        }

        .info {
            font-size: 12px;
            line-height: 18px;
            padding: 8px;
        }

        /* ================= PRODUCTS TABLE ================= */
        table.products {
            font-size: 12px;
            margin-bottom: 20px;
            table-layout: fixed;
        }

        table.products th {
            background-color: #2C2C2C;
            color: #C49A00;
            padding: 8px;
            border: 1px solid #555;
            text-align: center;
        }

        table.products td {
            border: 1px solid #aaa;
            padding: 7px;
            text-align: center;
            word-wrap: break-word;
        }

        table.products td:nth-child(3) {
            text-align: left;
            font-weight: bold;
        }

        table.products tr:nth-child(even) td {
            background-color: #f9f9f9;
        }

        .product-description {
            font-size: 11px;
            font-weight: normal;
            color: #555;
            margin-top: 4px;
            display: block;
            line-height: 1.4;
        }

        /* ================= MAIN CONTENT ================= */
        .main-content {
            padding: 10px;
            border: none;
            box-sizing: border-box;
        }

        /* ================= TERMS ================= */
        .terms-wrapper {
            page-break-inside: auto;
        }

        .terms-section {
            break-inside: auto;
            page-break-inside: auto;
            margin-top: 15px;
        }

        .terms-title {
            page-break-after: avoid;
            break-after: avoid;
            background-color: #2C2C2C;
            color: #C49A00;
            padding: 6px;
            font-weight: bold;
            font-size: 13px;
            margin-top: 15px;
            text-transform: uppercase;
        }

        .terms-table {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .terms-table td {
            font-size: 11px;
            line-height: 1.3;
            border: 1px solid #ddd;
            padding: 6px;
        }

        .terms-table td:first-child {
            width: 25%;
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .products {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            break-inside: auto;
            page-break-inside: auto;
        }

        .products thead {
            display: table-header-group;
        }

        .products tbody {
            display: table-row-group;
        }

        .products tr {
            page-break-inside: avoid !important;
            break-inside: avoid;
        }

        .products th,
        .products td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 12px;
            vertical-align: top;
            word-wrap: break-word;
        }

       .header {
    position: fixed;
    top: -30mm; /* Moves the header up into the 45mm blank margin space */
    left: 0;
    right: 0;
    height: 25mm;
}

        .page-block {
            page-break-inside: avoid;
            break-inside: avoid;
            margin-top: 10px;
        }

       /* Container that forces Terms, Conditions, and Signatures to stick together */
.final-documentation-block {
    page-break-inside: avoid !important;
    break-inside: avoid !important;
    margin-top: 20px;
    padding-bottom: 70px; /* Creates physical space for the footer image below it */
    position: relative;
}
/* Ensure the signature table itself doesn't cause individual internal breaks */
.signatures {
    margin-top: 30px;
    page-break-inside: avoid !important;
    break-inside: avoid !important;
}
/* Forces the complete summary segment to move to the next page as one piece if it doesn't fit */
.unbreakable-totals-container {
    page-break-inside: avoid !important;
    break-inside: avoid !important;
    margin-top: -1px; /* Glues seamlessly to the items table above it */
    margin-bottom: 25px;
    width: 100%;
}

        /* ================= ABSOLUTE BOTTOM LAST PAGE FOOTER ================= */
       .last-page-footer-anchor {
    position: absolute;
    /* Pushes it past the new 12mm margin, straight into the safe printable bleed edge */
    bottom: -10mm; 
    left: 0;
    right: 0;

    page-break-inside: avoid;
    break-inside: avoid;
}

.last-page-footer-anchor img {
    width: 100%;
    height: auto;
   
    display: block;
    margin: 0 auto;
}

        .product-description-editor {
            font-size: 11px;
            font-weight: normal !important; /* Resets structural bold from parent cell */
            color: #555;
            margin-top: 6px;
            display: block;
            line-height: 1.5;
            text-align: left;
        }

        .product-description-editor p {
            margin-top: 0;
            margin-bottom: 8px; /* Adds spacing between paragraphs when user presses Enter */
            padding: 0;
        }

        .product-description-editor ul, 
        .product-description-editor ol {
            margin-top: 4px;
            margin-bottom: 8px;
            padding-left: 20px;
        }

        .product-description-editor li {
            margin-bottom: 6px; /* Adds space between list items */
        }

        /* Re-enforce explicit styling for deep PDF layers */
        .product-description-editor strong, 
        .product-description-editor b {
            font-weight: bold !important;
        }

        .product-description-editor em, 
        .product-description-editor i {
            font-style: italic !important;
        }

        .product-description-editor u {
            text-decoration: underline !important;
        }
        /* Rich Text Formatting Styles for Notes Editor */
        .notes-description-editor {
            font-size: 11px;
            font-weight: normal !important;
            color: #333;
            line-height: 1.5;
            text-align: left;
        }

        .notes-description-editor p {
            margin-top: 0;
            margin-bottom: 8px; /* Adds clean spacing between paragraphs on Enter */
            padding: 0;
        }

        .notes-description-editor ul, 
        .notes-description-editor ol {
            margin-top: 4px;
            margin-bottom: 8px;
            padding-left: 20px;
        }

        .notes-description-editor li {
            margin-bottom: 6px; /* Adds comfortable spacing between bullet points */
        }

        /* Ensure styling passes through to deep PDF layers correctly */
        .notes-description-editor strong, 
        .notes-description-editor b {
            font-weight: bold !important;
        }

        .notes-description-editor em, 
        .notes-description-editor i {
            font-style: italic !important;
        }

        .notes-description-editor u {
            text-decoration: underline !important;
        }
    </style>
</head>

<body>
    <div class="cover-page">
        <div class="cover-img">
            <img src="<?= base_url('public/quotation_cover/Quotation_cover_page.jpeg') ?>">
        </div>

        <div class="cover-details">
            <h3><?= $company['company_name'] ?></h3>
            <?= nl2br($company['company_address']) ?>
            <?= $company['company_state'] ?>, <?= $company['company_country'] ?><br>
            <?= $company['company_telephone'] ?>

            <?php if(!empty($company['company_telephone_alt'])) { ?>
                 <?= $company['company_telephone_alt'] ?>
            <?php } ?>
            <br>
            <a href="mailto:<?= $company['company_email_id'] ?>" style="color: #0066cc; text-decoration: underline; font-weight: normal;"><?= $company['company_email_id'] ?></a><br>
            <a href="https://<?= str_replace(['http://', 'https://'], '', $company['company_website']) ?>" target="_blank; color: #0066cc; text-decoration: underline; font-weight: normal;"><?= $company['company_website'] ?></a>
        </div>
    </div>

    <div class="header">
        <table style="width:100%;">
            <tr>
                <td width="50%">
                    <img src="<?= $headerPath ?>" style="max-height:100px;">
                </td>
                <td width="50%" align="right">
                    <h2 style="margin:0;">Sales Quotation</h2>
                    <strong>Quotation No:</strong> <?= $quotation_details['quotation_code'] ?><br>
                    <strong>Date:</strong> <?= date('d-m-Y', strtotime($quotation_details['quotation_date'])) ?>
                </td>
            </tr>
        </table>
    </div>

    <div style="height:20px;"></div>

    <table class="party-table" style="width:100%; border-collapse:collapse; margin-bottom:15px;">
        <tr>
            <td width="50%" style="padding:0; border:1px solid #d9d9d9;">
                <div class="section-title">BUYER</div>
                <div class="info" style="padding:8px;">
                    <strong>Client:<?= $customer_name ?></strong><br>
                    Contact:<?= $contact_number ?><br>
                    Email:<?= $customer_email ?><br>
                    Address:<?= $customer_address ?><br>
                    Attn To:<?= $contact_name ?><br>
                    TRN No:<?= $customer_TR_no ?>
                </div>
            </td>

            <td width="50%" style="padding:0; border:1px solid #d9d9d9;">
                <div class="section-title">SELLER</div>
                <div class="info" style="padding:8px;">
                    <strong>AL ADEL AUTOMATIC DOORS TR LLC</strong><br>
                    +97165442460<br>
                    info@adelautodoors.com<br>
                    AL AREF BUILDING OFFICE NO.14 INDUSTRIAL 18,<br>
                    SHARJAH, UNITED ARAB EMIRATES<br>
                    TRN: 100353567900003
                </div>
            </td>
        </tr>
    </table>

    <table class="party-table" style="width:100%; border-collapse:collapse; margin-top:10px;">
        <tr>
            <td width="50%" style="border:1px solid #000; padding:8px;">
                Project name : <strong><?= $project_name ?></strong>
            </td>
            <td width="50%" style="border:1px solid #000; padding:8px;">
                Location : <strong><?= $project_location ?></strong>
            </td>
        </tr>
    </table>

    <div class="main-content">
        <table class="products" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="width:4%;">#</th>
                    <th style="width:10%;">Reference</th>
                    <th style="width:40%;">Item Description</th>
                    <th style="width:10%;">Unit Price</th>
                    <th style="width:8%;">Qty</th>
                    <th style="width:12%;">Amount</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $i = 1;
            foreach ($quotation_products as $products) {
            ?>
                <tr>
                    <td><?= $i++ ?></td>
                    <td><?= $products['item_code'] ?></td>
                   <td style="text-align:left; font-weight:bold; vertical-align:top;">
                        <?= $products['item_name'] ?><br>
                        <div class="product-description-editor">
                            <?= $products['prd_description'] ?>
                        </div>
                    </td>
                    <td align="right"><?= number_format($products['unit_price'],2) ?></td>
                    <td align="center"><?= number_format($products['qty'],2) ?></td>
                    <td align="right">
                        <?= number_format($products['qty'] * $products['unit_price'],2) ?>
                    </td>
                </tr>
            <?php } ?> </tbody>
        </table> 
        
        <div class="unbreakable-totals-container" style="border:1px solid #000; padding:0; margin:0;">
    <table style="width:100%; border-collapse:collapse; table-layout:fixed;">
        <tr>
            <td style="width:54%; vertical-align:middle; padding:12px; text-align:left;">
                <strong>Amount in Words:</strong><br><br>
                <span style="font-size:12px; line-height:1.4;">
                    <?= numberToWordsAED((float)($quotation_details['grand_total'] ?? 0)); ?>
                </span>
            </td>

            <td style="width:46%; padding:0; vertical-align:top;">
                <table style="width:100%; border-collapse:collapse; margin:0; ">
                    
                    <tr>
                        <td style="font-weight:bold; text-align:right; background-color:#f2f2f2; padding:7px; border-bottom:1px solid #aaa; width:60%;">Gross</td>
                        <td style="text-align:right; padding:7px; background-color:#f2f2f2;border-bottom:1px solid #aaa; font-weight:bold;">
                            <?= number_format($quotation_details['sub_total'],2) ?>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-weight:bold; text-align:right; background-color:#f2f2f2; padding:7px; border-bottom:1px solid #aaa;">Discount</td>
                        <td style="text-align:right; padding:7px; border-bottom:1px solid #aaa;background-color:#f2f2f2;">
                            <?= number_format($quotation_details['discount_amount'],2) ?>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-weight:bold; text-align:right; background-color:#e6e6e6; padding:7px; border-bottom:1px solid #aaa;">NET</td>
                        <td style="text-align:right; font-weight:bold; padding:7px; background-color:#e6e6e6;border-bottom:1px solid #aaa;">
                            <?= number_format((float)$quotation_details['sub_total'] - (float)$quotation_details['discount_amount'], 2) ?>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-weight:bold; text-align:right; background-color:#f2f2f2; padding:7px; border-bottom:1px solid #aaa;">VAT (<?= $quotation_details['vat_percentage'] ?>%)</td>
                        <td style="text-align:right; padding:7px;border-bottom:1px solid #aaa;background-color:#f2f2f2;">
                            <?= number_format($quotation_details['vat_amount'],2) ?>
                        </td>
                    </tr>

                    <tr>
                        <td style="font-weight:bold; background:#2C2C2C; color:#C49A00; text-align:right; padding:8px;">Total Amount</td>
                        <td style="text-align:right; font-weight:bold; background:#2C2C2C; color:#C49A00; padding:8px;">
                            <?= number_format($quotation_details['grand_total'],2) ?>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</div>
        <div class="terms-wrapper">
           <!-- NOTES -->
            <div class="terms-section" style="margin-top:15px;">
                <div class="terms-title">NOTES</div>
                <table class="terms-table" style="width:100%;">
                    <tr>
                        <td>
                            <!-- Wrapper to parse and style editor formatting correctly -->
                            <div class="notes-description-editor">
                                <?= $quotation_details['notes'] ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
             </div>

           <!-- ================= BUNDLED TERMS, CONDITIONS & SIGNATURES ================= -->
            <div class="final-documentation-block">

                <!-- Default Terms and Conditions Table -->
                <div class="terms-section">
                    <div class="terms-title">Terms and Conditions</div>
                    <table class="terms-table">
                        <tr>
                            <td>Validity</td>
                            <td><?= $quotation_details['validity'] ?></td>
                        </tr>
                        <tr>
                            <td>Warranty</td>
                            <td><?= $quotation_details['warranty'] ?></td>
                        </tr>
                        <tr>
                            <td>Payment terms</td>
                            <td><?= $quotation_details['payment_term'] ?></td>
                        </tr>
                        <tr>
                            <td>Delivery terms</td>
                            <td><?= $quotation_details['delivery_term'] ?></td>
                        </tr>
                    </table>
                </div>

                <!-- Custom/Dynamic Editor Terms Conditions -->
                <div class="page-block">
                    <?= $quotation_details['terms_condition'] ?>
                </div>
                
                <!-- Signatures Section -->
                <table width="100%" class="signatures" style="border-collapse:collapse; table-layout:fixed;">
                    <tr>
                        <!-- Prepared By (LEFT) -->
                        <td style="text-align:left; vertical-align:top; width:33%; padding-top:10px; padding-left:5px; padding-right:5px;">
                            <strong>Prepared By:</strong><br>
                            <?php if (!empty($prepared_signature)) { ?>
                                <img src="<?= base_url('public/employee/' . $prepared_signature) ?>" style="height:70px; margin-top:5px;"><br>
                            <?php } ?>
                            <span><?= htmlspecialchars($prepared_by_name ?? '') ?></span><br>
                            <?php if (!empty($prepared_by_contact)) { ?>
                                <span><?= htmlspecialchars($prepared_by_contact) ?></span>
                            <?php } ?>
                        </td>

                        <!-- Stamp (CENTER) -->
                        <td colspan="3" style="text-align:center; padding-top:20px;">
                            <?php if (!empty($branch_stamp)) { 
                                $path = FCPATH . ltrim($branch_stamp, './');
                                if (file_exists($path)) {
                                    $type = pathinfo($path, PATHINFO_EXTENSION);
                                    $data = file_get_contents($path);
                                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                ?>
                                    <img src="<?= $base64 ?>" style="max-width:200px; max-height:140px;">
                                <?php } ?>
                            <?php } ?>
                        </td>

                        <!-- Approved By (RIGHT) -->
                        <td style="text-align:right; vertical-align:top; width:33%; padding:0 5px;">
                            <strong>Approved By:</strong><br>
                            <?php if (!empty($approved_signature)) { ?>
                                <img src="<?= base_url('public/employee/' . $approved_signature) ?>" style="height:70px; margin-top:5px;"><br>
                            <?php } ?>
                            <span><?= htmlspecialchars($approved_by_name ?? '') ?></span>
                        </td>
                    </tr>
                </table>

            </div> <!-- End of .final-documentation-block -->
        </div> <!-- End of .terms-wrapper -->
        
        <!-- ================= FIXED FOOT SECTION OF LAST PAGE ONLY ================= -->
        <div class="last-page-footer-anchor">
            <img src="<?= $footerPath ?>">
        </div>

    </div> <!-- End of .main-content -->
</body>
</html>