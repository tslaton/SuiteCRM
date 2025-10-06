<?php
$module_name = 'RE_Properties';
$viewdefs [$module_name] = array(
    'DetailView' => array(
        'templateMeta' => array(
            'form' => array(
                'buttons' => array(
                    'EDIT',
                    'DUPLICATE',
                    'DELETE',
                    'FIND_DUPLICATES',
                ),
            ),
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
        ),
        'panels' => array(
            'lbl_property_information' => array(
                array(
                    'name',
                    'status',
                ),
                array(
                    'property_type',
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
                        'label' => 'LBL_PROPERTY_ADDRESS',
                    ),
                ),
                array(
                    'property_city',
                    'property_state',
                ),
                array(
                    'property_postalcode',
                    'property_country',
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
                        'studio' => 'visible',
                        'label' => 'LBL_AMENITIES',
                    ),
                ),
            ),
            'lbl_listing_information' => array(
                array(
                    array(
                        'name' => 'listing_price',
                        'label' => '{$MOD.LBL_LISTING_PRICE} ({$CURRENCY})',
                    ),
                    'listing_date',
                ),
                array(
                    'description',
                ),
            ),
            'lbl_sales_information' => array(
                array(
                    array(
                        'name' => 'sold_price',
                        'label' => '{$MOD.LBL_SOLD_PRICE} ({$CURRENCY})',
                    ),
                    'sold_date',
                ),
            ),
            'lbl_media_information' => array(
                array(
                    array(
                        'name' => 'cover_image',
                        'studio' => 'visible',
                        'label' => 'LBL_COVER_IMAGE',
                        'type' => 'image',
                        'displayParams' => array(
                            'width' => 200,
                        ),
                    ),
                ),
                array(
                    array(
                        'name' => 'virtual_tour_link',
                        'type' => 'url',
                        'label' => 'LBL_VIRTUAL_TOUR_LINK',
                        'displayParams' => array(
                            'link_target' => '_blank',
                        ),
                    ),
                ),
            ),
        ),
    ),
);