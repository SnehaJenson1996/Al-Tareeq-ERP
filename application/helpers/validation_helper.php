<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Validate quotation data
 * @param array $data
 * @return array [status => bool, errors => array]
 */
function validate_quotation($data) {
    $errors = [];

    // Mandatory fields
    if (empty($data['enquiry_id'])) {
        $errors[] = 'Enquiry is required.';
    }
    if (empty($data['quotation_date'])) {
        $errors[] = 'Quotation date is required.';
    }
    if (empty($data['quotation_code'])) {
        $errors[] = 'Quotation code is required.';
    }

    // Numeric fields validation
    if (isset($data['qtn_sub_total']) && !is_numeric($data['qtn_sub_total'])) {
        $errors[] = 'Sub total must be numeric.';
    }
    if (isset($data['qtn_grand_total']) && !is_numeric($data['qtn_grand_total'])) {
        $errors[] = 'Grand total must be numeric.';
    }

    // Add more checks as needed...

    return [
        'status' => empty($errors),
        'errors' => $errors
    ];
}
