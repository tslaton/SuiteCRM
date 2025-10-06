<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('include/MVC/Controller/SugarController.php');

class RE_PropertiesController extends SugarController
{
    /**
     * Override to handle image upload for cover image
     */
    public function action_save()
    {
        // Handle file upload if present
        if (!empty($_FILES['cover_image']['name'])) {
            // Image upload is handled in the bean's save method
        }
        
        parent::action_save();
    }
    
    /**
     * Action to display property details in popup
     */
    public function action_DetailViewPopup()
    {
        $this->view = 'detail';
    }
    
    /**
     * Action to generate property listing PDF
     */
    public function action_generatePDF()
    {
        global $sugar_config, $current_user;
        
        if (!empty($this->bean->id)) {
            require_once('modules/AOS_PDF_Templates/AOS_PDF_Templates.php');
            require_once('modules/AOS_PDF_Templates/templateParser.php');
            require_once('modules/AOS_PDF_Templates/sendEmail.php');
            require_once('modules/AOS_PDF_Templates/formLetterPdf.php');
            
            // You would need to create a PDF template for properties
            // This is just a placeholder for the functionality
            $template = new AOS_PDF_Templates();
            $template->retrieve_by_string_fields(array(
                'type' => 'RE_Properties',
                'deleted' => '0'
            ));
            
            if ($template->id) {
                formLetterPdf($this->bean, $template, false);
            } else {
                SugarApplication::appendErrorMessage('No PDF template found for Properties');
                SugarApplication::redirect("index.php?module=RE_Properties&action=DetailView&record={$this->bean->id}");
            }
        }
    }
}