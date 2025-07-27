<?php
/**
 * Test script for commission calculator logic hook
 */

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

require_once('include/entryPoint.php');
require_once('modules/Opportunities/Opportunity.php');

global $current_user, $sugar_config;

// Check if we're running from CLI
if (php_sapi_name() !== 'cli') {
    die('This script must be run from the command line.');
}

// Set admin user
$current_user = BeanFactory::getBean('Users', '1');

echo "\n=== TESTING COMMISSION CALCULATOR ===\n\n";

// Test 1: Simple commission calculation
echo "Test 1: Simple commission calculation\n";
echo "--------------------------------------\n";
$opp1 = BeanFactory::newBean('Opportunities');
$opp1->name = 'Test Commission - Single Agent';
$opp1->amount = 500000; // $500,000 sale
$opp1->sales_stage = 'Inquiry'; // Starting stage
$opp1->date_closed = date('Y-m-d', strtotime('+30 days'));
$opp1->assigned_user_id = $current_user->id;
$opp1->save();

echo "Created opportunity with ID: {$opp1->id}\n";
echo "Initial state - Amount: \${$opp1->amount}, Stage: {$opp1->sales_stage}\n";
echo "Commission before closing: \${$opp1->commission_c}\n";

// Now close the deal
$opp1->sales_stage = 'Closed';
$opp1->save();

// Reload to get calculated values
$opp1 = BeanFactory::getBean('Opportunities', $opp1->id);
echo "After closing - Stage: {$opp1->sales_stage}\n";
echo "Commission calculated: \${$opp1->commission_c}\n";
echo "Expected (2.5% of \$500,000): \$12,500\n";
echo "Result: " . ($opp1->commission_c == 12500 ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 2: Custom commission rate
echo "Test 2: Custom commission rate (3%)\n";
echo "-----------------------------------\n";
$opp2 = BeanFactory::newBean('Opportunities');
$opp2->name = 'Test Commission - Custom Rate';
$opp2->amount = 400000; // $400,000 sale
$opp2->commission_rate_c = 3.0; // 3% commission
$opp2->sales_stage = 'Showing';
$opp2->date_closed = date('Y-m-d', strtotime('+45 days'));
$opp2->assigned_user_id = $current_user->id;
$opp2->save();

echo "Created opportunity with custom rate: {$opp2->commission_rate_c}%\n";
$opp2->sales_stage = 'Closed';
$opp2->save();

$opp2 = BeanFactory::getBean('Opportunities', $opp2->id);
echo "Commission calculated: \${$opp2->commission_c}\n";
echo "Expected (3% of \$400,000): \$12,000\n";
echo "Result: " . ($opp2->commission_c == 12000 ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 3: Split commission
echo "Test 3: Split commission (60/40)\n";
echo "--------------------------------\n";
$opp3 = BeanFactory::newBean('Opportunities');
$opp3->name = 'Test Commission - Split';
$opp3->amount = 600000; // $600,000 sale
$opp3->sales_stage = 'Under Contract';
$opp3->date_closed = date('Y-m-d', strtotime('+60 days'));
$opp3->assigned_user_id = $current_user->id;
$opp3->secondary_agent_id_c = $current_user->id; // Using same user for test
$opp3->commission_split_c = 60; // Primary gets 60%
$opp3->save();

echo "Created opportunity with split commission\n";
echo "Primary agent split: {$opp3->commission_split_c}%\n";
$opp3->sales_stage = 'Closed';
$opp3->save();

$opp3 = BeanFactory::getBean('Opportunities', $opp3->id);
$totalCommission = $opp3->amount * 0.025; // 2.5% of $600,000 = $15,000
echo "Total commission: \${$totalCommission}\n";
echo "Primary agent commission: \${$opp3->commission_c}\n";
echo "Expected (60% of \$15,000): \$9,000\n";
echo "Primary result: " . ($opp3->commission_c == 9000 ? "✓ PASS" : "✗ FAIL") . "\n";
echo "Secondary agent commission: \${$opp3->secondary_commission_c}\n";
echo "Expected (40% of \$15,000): \$6,000\n";
echo "Secondary result: " . ($opp3->secondary_commission_c == 6000 ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 4: Edge case - Zero amount
echo "Test 4: Edge case - Zero amount\n";
echo "-------------------------------\n";
$opp4 = BeanFactory::newBean('Opportunities');
$opp4->name = 'Test Commission - Zero Amount';
$opp4->amount = 0;
$opp4->sales_stage = 'Offer Made';
$opp4->date_closed = date('Y-m-d', strtotime('+15 days'));
$opp4->assigned_user_id = $current_user->id;
$opp4->save();

$opp4->sales_stage = 'Closed';
$opp4->save();

$opp4 = BeanFactory::getBean('Opportunities', $opp4->id);
echo "Commission on \$0 sale: \${$opp4->commission_c}\n";
echo "Result: " . ($opp4->commission_c == 0 ? "✓ PASS" : "✗ FAIL") . "\n\n";

// Test 5: Already closed - no recalculation
echo "Test 5: Already closed - no recalculation\n";
echo "----------------------------------------\n";
$opp5 = BeanFactory::getBean('Opportunities', $opp1->id);
$originalCommission = $opp5->commission_c;
echo "Original commission: \${$originalCommission}\n";
$opp5->name = 'Updated Name - Should not recalculate';
$opp5->save();

$opp5 = BeanFactory::getBean('Opportunities', $opp5->id);
echo "Commission after name update: \${$opp5->commission_c}\n";
echo "Result: " . ($opp5->commission_c == $originalCommission ? "✓ PASS (no change)" : "✗ FAIL (changed)") . "\n\n";

// Clean up test records
echo "Cleaning up test records...\n";
$opp1->mark_deleted($opp1->id);
$opp2->mark_deleted($opp2->id);
$opp3->mark_deleted($opp3->id);
$opp4->mark_deleted($opp4->id);

echo "\n=== COMMISSION CALCULATOR TESTS COMPLETED ===\n";