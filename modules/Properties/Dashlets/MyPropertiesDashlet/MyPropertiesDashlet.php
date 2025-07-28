<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/Properties/Properties.php');

class MyPropertiesDashlet extends DashletGeneric
{
    public function __construct($id, $def = null)
    {
        global $current_user, $app_strings;
        require('modules/Properties/metadata/dashletviewdefs.php');

        parent::__construct($id, $def);

        if (empty($def['title'])) {
            $this->title = translate('LBL_MY_PROPERTIES', 'Properties');
        }

        $this->searchFields = $dashletData['MyPropertiesDashlet']['searchFields'];
        $this->columns = $dashletData['MyPropertiesDashlet']['columns'];

        $this->seedBean = new Properties();
    }
}