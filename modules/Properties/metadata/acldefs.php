<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $beanList;

$acldefs['Properties'] = array(
    'forms' => array(
        'by_module' => array(
            'enabled' => true,
            'module' => 'Properties',
        ),
    ),
);