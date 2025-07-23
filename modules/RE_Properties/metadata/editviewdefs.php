<?php
$module_name = 'RE_Properties';
$viewdefs [$module_name] = array(
    'EditView' => array(
        'templateMeta' => array(
            'maxColumns' => '2',
            'widths' => array(
                array('label' => '10', 'field' => '30'),
                array('label' => '10', 'field' => '30'),
            ),
            'useTabs' => true,
            'tabDefs' => array(
                'LBL_PROPERTY_INFORMATION' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
                'LBL_ADDRESS_INFORMATION' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_PROPERTY_DETAILS' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_LISTING_INFORMATION' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
                'LBL_SALES_INFORMATION' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_MEDIA_INFORMATION' => array(
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
            'form' => array(
                'enctype' => 'multipart/form-data',
            ),
        ),
        'panels' => array(
            'lbl_property_information' => array(
                array(
                    array(
                        'name' => 'name',
                        'displayParams' => array(
                            'size' => 60,
                            'required' => false,
                        ),
                    ),
                    array(
                        'name' => 'status',
                        'displayParams' => array(
                            'required' => true,
                        ),
                    ),
                ),
                array(
                    array(
                        'name' => 'property_type',
                        'displayParams' => array(
                            'required' => true,
                        ),
                    ),
                    'mls_id',
                ),
                array(
                    'assigned_user_name',
                    '',
                ),
            ),
            'lbl_address_information' => array(
                array(
                    array(
                        'name' => 'property_address',
                        'displayParams' => array(
                            'size' => 60,
                            'required' => true,
                        ),
                    ),
                ),
                array(
                    array(
                        'name' => 'property_city',
                        'displayParams' => array(
                            'required' => true,
                        ),
                    ),
                    array(
                        'name' => 'property_state',
                        'displayParams' => array(
                            'required' => true,
                        ),
                    ),
                ),
                array(
                    'property_postalcode',
                    array(
                        'name' => 'property_country',
                        'displayParams' => array(
                            'size' => 30,
                        ),
                    ),
                ),
            ),
            'lbl_property_details' => array(
                array(
                    'square_footage',
                    'lot_size',
                ),
                array(
                    'bedrooms',
                    'bathrooms',
                ),
                array(
                    'year_built',
                    'garage_spaces',
                ),
                array(
                    array(
                        'name' => 'amenities',
                        'displayParams' => array(
                            'cols' => 60,
                            'rows' => 4,
                        ),
                    ),
                ),
            ),
            'lbl_listing_information' => array(
                array(
                    array(
                        'name' => 'listing_price',
                        'displayParams' => array(
                            'required' => true,
                        ),
                    ),
                    'listing_date',
                ),
                array(
                    array(
                        'name' => 'description',
                        'displayParams' => array(
                            'cols' => 60,
                            'rows' => 6,
                        ),
                    ),
                ),
            ),
            'lbl_sales_information' => array(
                array(
                    'sold_price',
                    'sold_date',
                ),
            ),
            'lbl_media_information' => array(
                array(
                    array(
                        'name' => 'cover_image',
                        'type' => 'file',
                        'displayParams' => array(
                            'onchangeSetFileNameTo' => 'cover_image_name',
                        ),
                    ),
                ),
                array(
                    array(
                        'name' => 'virtual_tour_link',
                        'type' => 'url',
                        'displayParams' => array(
                            'size' => 60,
                        ),
                    ),
                ),
            ),
        ),
    ),
);