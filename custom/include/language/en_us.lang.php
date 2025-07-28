<?php
/**
 * Custom application strings
 * Overrides for Opportunities module to use Transactions terminology
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

// Override module names in various lists
$app_list_strings['moduleList']['Opportunities'] = 'Transactions';
$app_list_strings['moduleListSingular']['Opportunities'] = 'Transaction';

// Override in parent_type_display
$app_list_strings['parent_type_display']['Opportunities'] = 'Transaction';

// Override in record_type_display and related lists
$app_list_strings['record_type_display']['Opportunities'] = 'Transaction';
$app_list_strings['record_type_display_notes']['Opportunities'] = 'Transaction';

// Sales stage dropdown for real estate transactions
$app_list_strings['sales_stage_dom'] = array(
    '' => '',
    'Inquiry' => 'Inquiry',
    'Showing' => 'Showing',
    'Offer Made' => 'Offer Made',
    'Under Contract' => 'Under Contract',
    'Inspection/Appraisal' => 'Inspection/Appraisal',
    'Clear to Close' => 'Clear to Close',
    'Closed' => 'Closed',
);

// Probability mappings for new stages
$app_list_strings['sales_probability_dom'] = array(
    'Inquiry' => '10',
    'Showing' => '20',
    'Offer Made' => '40',
    'Under Contract' => '60',
    'Inspection/Appraisal' => '75',
    'Clear to Close' => '90',
    'Closed' => '100',
);

// Update Type dropdown for real estate specific types
$app_list_strings['opportunity_type_dom'] = array(
    '' => '',
    'Buyer Representation' => 'Buyer Representation',
    'Seller Representation' => 'Seller Representation',
    'Dual Agency' => 'Dual Agency',
    'Rental' => 'Rental',
    'Commercial' => 'Commercial',
    'Land' => 'Land',
);

// Override subpanel title
$app_strings['LBL_OPPORTUNITIES'] = 'Transactions';