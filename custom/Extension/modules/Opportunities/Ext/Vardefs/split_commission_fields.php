<?php
/**
 * Split commission fields for Opportunities
 */

// Secondary agent for split commissions
$dictionary['Opportunity']['fields']['secondary_agent_c'] = array(
    'name' => 'secondary_agent_c',
    'vname' => 'LBL_SECONDARY_AGENT',
    'type' => 'relate',
    'module' => 'Users',
    'table' => 'users',
    'id_name' => 'secondary_agent_id_c',
    'rname' => 'name',
    'source' => 'non-db',
    'len' => 36,
    'link' => 'secondary_agent_link',
    'unified_search' => true,
    'importable' => 'true',
    'studio' => true,
);

$dictionary['Opportunity']['fields']['secondary_agent_id_c'] = array(
    'name' => 'secondary_agent_id_c',
    'vname' => 'LBL_SECONDARY_AGENT_ID',
    'type' => 'id',
    'len' => 36,
    'reportable' => false,
    'studio' => false,
);

// Commission split percentage for primary agent
$dictionary['Opportunity']['fields']['commission_split_c'] = array(
    'name' => 'commission_split_c',
    'vname' => 'LBL_COMMISSION_SPLIT',
    'type' => 'decimal',
    'len' => '5,2',
    'size' => '20',
    'precision' => '2',
    'comment' => 'Primary agent commission split percentage',
    'audited' => true,
    'reportable' => true,
    'default' => '100',
    'help' => 'Percentage of commission for primary agent (e.g., 50 for 50/50 split)',
    'studio' => true,
);

// Secondary agent commission amount
$dictionary['Opportunity']['fields']['secondary_commission_c'] = array(
    'name' => 'secondary_commission_c',
    'vname' => 'LBL_SECONDARY_COMMISSION',
    'type' => 'currency',
    'dbType' => 'decimal',
    'len' => '26,6',
    'size' => '20',
    'precision' => '6',
    'comment' => 'Commission amount for secondary agent',
    'audited' => true,
    'reportable' => true,
    'duplicate_merge' => 'enabled',
    'importable' => 'true',
    'default' => '0.000000',
);

// Link for secondary agent relationship
$dictionary['Opportunity']['fields']['secondary_agent_link'] = array(
    'name' => 'secondary_agent_link',
    'type' => 'link',
    'relationship' => 'opportunity_secondary_agent',
    'module' => 'Users',
    'bean_name' => 'User',
    'source' => 'non-db',
    'vname' => 'LBL_SECONDARY_AGENT_LINK',
);

// Relationship definition
$dictionary['Opportunity']['relationships']['opportunity_secondary_agent'] = array(
    'lhs_module' => 'Opportunities',
    'lhs_table' => 'opportunities',
    'lhs_key' => 'secondary_agent_id_c',
    'rhs_module' => 'Users',
    'rhs_table' => 'users',
    'rhs_key' => 'id',
    'relationship_type' => 'one-to-many',
);