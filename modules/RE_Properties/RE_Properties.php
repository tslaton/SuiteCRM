<?php
require_once('modules/RE_Properties/RE_Properties_sugar.php');

class RE_Properties extends RE_Properties_sugar
{
    public function __construct()
    {
        parent::__construct();
    }

    public function save($check_notify = false)
    {
        // Handle cover image upload if present
        if (!empty($_FILES['cover_image']['name'])) {
            $this->handleImageUpload();
        }

        // Set property name/title based on address if not provided
        if (empty($this->name) && !empty($this->property_address)) {
            $this->name = $this->property_address . ', ' . $this->property_city . ', ' . $this->property_state;
        }

        // Update status dates
        if ($this->status == 'sold' && empty($this->sold_date)) {
            $this->sold_date = date('Y-m-d');
        }
        
        if ($this->status == 'active' && empty($this->listing_date)) {
            $this->listing_date = date('Y-m-d');
        }

        return parent::save($check_notify);
    }

    /**
     * Handle property image upload
     */
    protected function handleImageUpload()
    {
        global $sugar_config;
        
        $upload_dir = 'upload/';
        $valid_extensions = array('jpg', 'jpeg', 'png', 'gif');
        
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
            $file_name = $_FILES['cover_image']['name'];
            $file_tmp = $_FILES['cover_image']['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
            if (in_array($file_ext, $valid_extensions)) {
                // Generate unique filename
                $new_file_name = $this->id . '_property_' . time() . '.' . $file_ext;
                $upload_path = $upload_dir . $new_file_name;
                
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    // Delete old image if exists
                    if (!empty($this->cover_image) && file_exists($upload_dir . $this->cover_image)) {
                        unlink($upload_dir . $this->cover_image);
                    }
                    
                    $this->cover_image = $new_file_name;
                }
            }
        }
    }

    /**
     * Get full property address as single string
     */
    public function getFullAddress()
    {
        $address_parts = array();
        
        if (!empty($this->property_address)) {
            $address_parts[] = $this->property_address;
        }
        if (!empty($this->property_city)) {
            $address_parts[] = $this->property_city;
        }
        if (!empty($this->property_state)) {
            $address_parts[] = $this->property_state;
        }
        if (!empty($this->property_postalcode)) {
            $address_parts[] = $this->property_postalcode;
        }
        if (!empty($this->property_country)) {
            $address_parts[] = $this->property_country;
        }
        
        return implode(', ', $address_parts);
    }

    /**
     * Get property status label
     */
    public function getStatusLabel()
    {
        global $app_list_strings;
        
        if (isset($app_list_strings['property_status_list'][$this->status])) {
            return $app_list_strings['property_status_list'][$this->status];
        }
        
        return $this->status;
    }

    /**
     * Get property type label
     */
    public function getPropertyTypeLabel()
    {
        global $app_list_strings;
        
        if (isset($app_list_strings['property_type_list'][$this->property_type])) {
            return $app_list_strings['property_type_list'][$this->property_type];
        }
        
        return $this->property_type;
    }

    /**
     * Get formatted listing price
     */
    public function getFormattedListingPrice()
    {
        global $locale;
        
        if (!empty($this->listing_price)) {
            return $locale->getCurrencySymbol() . number_format($this->listing_price, 0);
        }
        
        return '';
    }

    /**
     * Get property summary for list views
     */
    public function getPropertySummary()
    {
        $summary = array();
        
        if (!empty($this->bedrooms)) {
            $summary[] = $this->bedrooms . ' bed';
        }
        if (!empty($this->bathrooms)) {
            $summary[] = $this->bathrooms . ' bath';
        }
        if (!empty($this->square_footage)) {
            $summary[] = number_format($this->square_footage) . ' sqft';
        }
        
        return implode(' | ', $summary);
    }
}