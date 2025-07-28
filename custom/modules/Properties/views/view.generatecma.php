<?php
/**
 * GenerateCMA View for Properties Module
 * Handles CMA generation requests
 */

require_once('include/MVC/View/SugarView.php');
require_once('modules/MockMLS/MockMLS.php');
require_once('vendor/tecnickcom/tcpdf/tcpdf.php');

class PropertiesViewGenerateCMA extends SugarView
{
    public function display()
    {
        global $mod_strings, $app_strings, $sugar_config;
        
        // Get the property ID from the request
        $propertyId = $_REQUEST['record'] ?? '';
        
        if (empty($propertyId)) {
            SugarApplication::appendErrorMessage($app_strings['ERR_NO_RECORD']);
            SugarApplication::redirect('index.php?module=Properties&action=index');
            return;
        }
        
        // Load the property record
        $property = BeanFactory::getBean('Properties', $propertyId);
        
        if (empty($property->id)) {
            SugarApplication::appendErrorMessage($app_strings['ERR_RECORD_NOT_FOUND']);
            SugarApplication::redirect('index.php?module=Properties&action=index');
            return;
        }
        
        // Check if this is a configuration request or generation request
        if (!empty($_REQUEST['generate']) && $_REQUEST['generate'] == '1') {
            // Generate the CMA
            $this->generateCMA($property);
        } else {
            // Show configuration form
            $this->showConfigurationForm($property);
        }
    }
    
    /**
     * Show CMA configuration form
     */
    private function showConfigurationForm($property)
    {
        global $mod_strings, $app_strings;
        
        $this->ss->assign('property', $property);
        $this->ss->assign('mod_strings', $mod_strings);
        $this->ss->assign('app_strings', $app_strings);
        
        // Default configuration values
        $this->ss->assign('search_radius', 5); // 5 mile radius
        $this->ss->assign('price_range', 15); // +/- 15%
        $this->ss->assign('sqft_range', 20); // +/- 20%
        $this->ss->assign('bedroom_range', 1); // +/- 1 bedroom
        $this->ss->assign('bathroom_range', 0.5); // +/- 0.5 bathroom
        $this->ss->assign('max_comparables', 5); // Max 5 comparables
        $this->ss->assign('sold_only', true); // Only sold properties
        $this->ss->assign('date_range_months', 6); // Last 6 months
        
        echo $this->ss->fetch('custom/modules/Properties/tpls/generateCMAConfig.tpl');
    }
    
    /**
     * Generate the CMA PDF
     */
    private function generateCMA($property)
    {
        global $mod_strings, $app_strings, $current_user, $sugar_config;
        
        // Get configuration from request
        $config = [
            'price_range' => $_REQUEST['price_range'] ?? 15,
            'sqft_range' => $_REQUEST['sqft_range'] ?? 20,
            'bedroom_range' => $_REQUEST['bedroom_range'] ?? 1,
            'bathroom_range' => $_REQUEST['bathroom_range'] ?? 0.5,
            'max_comparables' => $_REQUEST['max_comparables'] ?? 5,
            'sold_only' => !empty($_REQUEST['sold_only']),
            'date_range_months' => $_REQUEST['date_range_months'] ?? 6,
        ];
        
        // Find comparable properties
        $result = $this->findComparables($property, $config);
        $comparables = $result['properties'];
        $used_fallback = $result['used_fallback'];
        
        if (empty($comparables)) {
            SugarApplication::appendErrorMessage('No comparable properties found in ZIP code ' . $property->zip_code . '.');
            SugarApplication::redirect("index.php?module=Properties&action=DetailView&record={$property->id}");
            return;
        }
        
        // Add message if fallback was used
        if ($used_fallback) {
            SugarApplication::appendSuccessMessage('No exact matches found with your criteria. Showing the 5 closest properties by price in ZIP ' . $property->zip_code . '.');
        }
        
        // Generate PDF
        $pdfPath = $this->generatePDF($property, $comparables, $config);
        
        if ($pdfPath) {
            // Create document record
            $documentId = $this->createDocumentRecord($property, $pdfPath);
            
            if ($documentId) {
                SugarApplication::appendSuccessMessage('CMA generated successfully.');
            }
            
            // Redirect back to property detail view
            SugarApplication::redirect("index.php?module=Properties&action=DetailView&record={$property->id}");
        } else {
            SugarApplication::appendErrorMessage('Failed to generate CMA PDF.');
            SugarApplication::redirect("index.php?module=Properties&action=DetailView&record={$property->id}");
        }
    }
    
    /**
     * Find comparable properties based on criteria
     */
    private function findComparables($property, $config)
    {
        $mockMLS = new MockMLS();
        
        // Build criteria for search
        $criteria = [
            'zip' => $property->zip_code,
            'property_type' => $this->mapPropertyType($property->property_type),
            'bedrooms' => $property->bedrooms,
            'bathrooms' => $property->bathrooms,
            'square_footage' => $property->square_footage,
            'price' => $property->price,
            'sold_only' => $config['sold_only'],
            'date_range_months' => $config['date_range_months'],
        ];
        
        // Use the MockMLS Bean's findComparables method (returns array with 'properties' and 'used_fallback')
        return $mockMLS->findComparables($criteria, $config['max_comparables']);
    }
    
    /**
     * Map property type from Properties module to MockMLS format
     */
    private function mapPropertyType($propertyType)
    {
        $mapping = [
            'residential' => 'single_family',
            'condo' => 'condo',
            'townhouse' => 'townhouse',
            'multi_unit' => 'multi_family',
            'land' => 'land',
            'commercial' => 'commercial',
        ];
        
        return $mapping[$propertyType] ?? 'other';
    }
    
    /**
     * Generate the CMA PDF report
     */
    private function generatePDF($property, $comparables, $config)
    {
        global $current_user, $sugar_config;
        
        try {
            // Create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            
            // Set document information
            $pdf->SetCreator('SuiteCRM');
            $pdf->SetAuthor($current_user->full_name);
            $pdf->SetTitle('Comparative Market Analysis - ' . $property->name);
            $pdf->SetSubject('CMA Report');
            
            // Remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            
            // Set margins
            $pdf->SetMargins(15, 15, 15);
            $pdf->SetAutoPageBreak(TRUE, 15);
            
            // Add a page
            $pdf->AddPage();
            
            // Set font
            $pdf->SetFont('helvetica', '', 10);
            
            // Generate CMA content
            $html = $this->generateCMAContent($property, $comparables, $config);
            
            // Print content
            $pdf->writeHTML($html, true, false, true, false, '');
            
            // Create upload directory if it doesn't exist
            $uploadDir = getcwd() . '/upload/cma_reports/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Generate filename
            $filename = 'CMA_' . $property->id . '_' . date('Y-m-d_His') . '.pdf';
            $filepath = $uploadDir . $filename;
            
            // Save PDF
            $pdf->Output($filepath, 'F');
            
            return $filepath;
            
        } catch (Exception $e) {
            $GLOBALS['log']->error('CMA Generation Error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generate HTML content for the CMA report
     */
    private function generateCMAContent($property, $comparables, $config)
    {
        global $current_user, $sugar_config;
        
        $html = '<h1 style="text-align: center; color: #333;">Comparative Market Analysis</h1>';
        $html .= '<p style="text-align: center; color: #666;">Prepared by: ' . $current_user->full_name . '</p>';
        $html .= '<p style="text-align: center; color: #666;">Date: ' . date('F j, Y') . '</p>';
        $html .= '<hr>';
        
        // Subject Property Section
        $html .= '<h2 style="color: #2c5aa0;">Subject Property</h2>';
        $html .= '<table cellpadding="5" cellspacing="0" border="0">';
        $html .= '<tr><td width="30%"><strong>Address:</strong></td><td>' . $property->street_address . ', ' . $property->city . ', ' . $property->state . ' ' . $property->zip_code . '</td></tr>';
        $html .= '<tr><td><strong>Property Type:</strong></td><td>' . ucfirst($property->property_type) . '</td></tr>';
        $html .= '<tr><td><strong>Bedrooms:</strong></td><td>' . $property->bedrooms . '</td></tr>';
        $html .= '<tr><td><strong>Bathrooms:</strong></td><td>' . $property->bathrooms . '</td></tr>';
        $html .= '<tr><td><strong>Square Footage:</strong></td><td>' . number_format($property->square_footage) . ' sq ft</td></tr>';
        $html .= '<tr><td><strong>Lot Size:</strong></td><td>' . $property->lot_size . ' acres</td></tr>';
        $html .= '<tr><td><strong>Year Built:</strong></td><td>' . $property->year_built . '</td></tr>';
        $html .= '<tr><td><strong>List Price:</strong></td><td style="font-size: 14px; color: #2c5aa0;"><strong>$' . number_format($property->price, 2) . '</strong></td></tr>';
        $html .= '</table>';
        
        // Comparable Properties Section
        $html .= '<h2 style="color: #2c5aa0; margin-top: 30px;">Comparable Properties</h2>';
        
        $totalSoldPrice = 0;
        $totalPricePerSqft = 0;
        $compCount = 0;
        
        foreach ($comparables as $comp) {
            $compCount++;
            // Handle both sold and unsold properties
            $price = !empty($comp->sold_price) ? $comp->sold_price : $comp->list_price;
            $pricePerSqft = $comp->square_footage > 0 ? $price / $comp->square_footage : 0;
            $totalSoldPrice += $price;
            $totalPricePerSqft += $pricePerSqft;
            
            $html .= '<div style="border: 1px solid #ddd; padding: 10px; margin-bottom: 15px;">';
            $html .= '<h3 style="color: #555; margin: 0 0 10px 0;">Comparable #' . $compCount . '</h3>';
            $html .= '<table cellpadding="3" cellspacing="0" border="0">';
            $html .= '<tr><td width="30%"><strong>Address:</strong></td><td>' . $comp->address . ', ' . $comp->city . ', ' . $comp->state . ' ' . $comp->zip . '</td></tr>';
            $html .= '<tr><td><strong>Property Type:</strong></td><td>' . ucfirst(str_replace('_', ' ', $comp->property_type)) . '</td></tr>';
            $html .= '<tr><td><strong>Bedrooms/Bathrooms:</strong></td><td>' . $comp->bedrooms . ' / ' . $comp->bathrooms . '</td></tr>';
            $html .= '<tr><td><strong>Square Footage:</strong></td><td>' . number_format($comp->square_footage) . ' sq ft</td></tr>';
            $html .= '<tr><td><strong>Year Built:</strong></td><td>' . $comp->year_built . '</td></tr>';
            $html .= '<tr><td><strong>List Price:</strong></td><td>$' . number_format($comp->list_price, 2) . '</td></tr>';
            if (!empty($comp->sold_price)) {
                $html .= '<tr><td><strong>Sold Price:</strong></td><td style="color: #2c5aa0;"><strong>$' . number_format($comp->sold_price, 2) . '</strong></td></tr>';
            }
            $html .= '<tr><td><strong>Price/Sq Ft:</strong></td><td>$' . number_format($pricePerSqft, 2) . '</td></tr>';
            if (!empty($comp->days_on_market)) {
                $html .= '<tr><td><strong>Days on Market:</strong></td><td>' . $comp->days_on_market . ' days</td></tr>';
            }
            if (!empty($comp->sold_date)) {
                $html .= '<tr><td><strong>Sold Date:</strong></td><td>' . date('m/d/Y', strtotime($comp->sold_date)) . '</td></tr>';
            }
            $html .= '</table>';
            $html .= '</div>';
        }
        
        // Market Analysis Summary
        $html .= '<h2 style="color: #2c5aa0; margin-top: 30px;">Market Analysis Summary</h2>';
        
        $avgSoldPrice = $compCount > 0 ? $totalSoldPrice / $compCount : 0;
        $avgPricePerSqft = $compCount > 0 ? $totalPricePerSqft / $compCount : 0;
        $subjectPricePerSqft = $property->square_footage > 0 ? $property->price / $property->square_footage : 0;
        $suggestedPrice = $avgPricePerSqft * $property->square_footage;
        
        $html .= '<table cellpadding="5" cellspacing="0" border="1" style="border-color: #ddd;">';
        $html .= '<tr style="background-color: #f5f5f5;">';
        $html .= '<th width="40%">Metric</th>';
        $html .= '<th width="30%">Subject Property</th>';
        $html .= '<th width="30%">Comparable Average</th>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>List/Sold Price</td>';
        $html .= '<td>$' . number_format($property->list_price, 2) . '</td>';
        $html .= '<td>$' . number_format($avgSoldPrice, 2) . '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>Price per Sq Ft</td>';
        $html .= '<td>$' . number_format($subjectPricePerSqft, 2) . '</td>';
        $html .= '<td>$' . number_format($avgPricePerSqft, 2) . '</td>';
        $html .= '</tr>';
        $html .= '<tr style="background-color: #e8f4f8;">';
        $html .= '<td><strong>Suggested List Price</strong></td>';
        $html .= '<td colspan="2" style="text-align: center; font-size: 14px;"><strong>$' . number_format($suggestedPrice, 2) . '</strong></td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        // Disclaimer
        $html .= '<p style="margin-top: 30px; font-size: 9px; color: #666; text-align: justify;">';
        $html .= '<strong>Disclaimer:</strong> This Comparative Market Analysis (CMA) is provided for informational purposes only and should not be considered as a formal appraisal. ';
        $html .= 'The suggested price is based on recent comparable sales and current market conditions. Actual market value may vary based on property condition, ';
        $html .= 'unique features, market changes, and other factors not reflected in this analysis. This report was generated on ' . date('F j, Y') . '.';
        $html .= '</p>';
        
        return $html;
    }
    
    /**
     * Create a document record for the generated CMA
     */
    private function createDocumentRecord($property, $pdfPath)
    {
        global $current_user;
        
        try {
            $document = BeanFactory::newBean('Documents');
            $document->document_name = 'CMA Report - ' . $property->name;
            $document->category_id = 'Marketing';
            $document->subcategory_id = '';
            $document->status_id = 'Active';
            $document->active_date = date('Y-m-d');
            $document->assigned_user_id = $current_user->id;
            $document->description = 'Comparative Market Analysis for ' . $property->street_address;
            $document->save();
            
            // Create document revision
            $docRevision = BeanFactory::newBean('DocumentRevisions');
            $docRevision->filename = basename($pdfPath);
            $docRevision->file_ext = 'pdf';
            $docRevision->file_mime_type = 'application/pdf';
            $docRevision->revision = '1';
            $docRevision->document_id = $document->id;
            $docRevision->save();
            
            // Move file to proper location
            $uploadFile = "upload/{$docRevision->id}";
            if (rename($pdfPath, $uploadFile)) {
                // Update document with latest revision
                $document->document_revision_id = $docRevision->id;
                $document->save();
                
                // Link document to property (if relationship exists)
                if ($document->load_relationship('properties')) {
                    $document->properties->add($property->id);
                }
                
                // Also try linking from property side
                if ($property->load_relationship('documents')) {
                    $property->documents->add($document->id);
                }
                
                // Link document to related contacts
                if ($property->load_relationship('properties_contacts')) {
                    $contacts = $property->properties_contacts->getBeans();
                    $document->load_relationship('contacts');
                    foreach ($contacts as $contact) {
                        $document->contacts->add($contact->id);
                    }
                }
                
                return $document->id;
            }
            
        } catch (Exception $e) {
            $GLOBALS['log']->error('Error creating CMA document: ' . $e->getMessage());
        }
        
        return false;
    }
}