<?php
/**
 * Generate QR Code View for Properties
 * Creates a QR code for the open house sign-in URL
 */

require_once('include/MVC/View/SugarView.php');

class PropertiesViewGenerateqr extends SugarView
{
    public function display()
    {
        global $sugar_config;
        
        // Get property
        $property_id = $this->bean->id;
        if (empty($property_id)) {
            sugar_die('Invalid property ID');
        }
        
        // Generate token
        $secret = isset($sugar_config['unique_key']) ? $sugar_config['unique_key'] : 'default_secret';
        $token = substr(md5($property_id . $secret), 0, 16);
        
        // Build URL
        $base_url = $sugar_config['site_url'];
        $sign_in_url = $base_url . '/index.php?entryPoint=OpenHouseSignIn&property_id=' . $property_id . '&token=' . $token;
        
        ?>
<!DOCTYPE html>
<html>
<head>
    <title>Open House QR Code - <?php echo htmlspecialchars($this->bean->name); ?></title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .qr-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .property-info {
            text-align: center;
            margin-bottom: 30px;
        }
        .property-info h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .property-address {
            color: #666;
            font-size: 18px;
        }
        .qr-section {
            text-align: center;
            margin: 30px 0;
        }
        .qr-code {
            border: 10px solid white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            display: inline-block;
            margin: 20px 0;
            background: white;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .qr-code:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        #qrcode {
            display: inline-block;
        }
        #qrcode img {
            display: block;
        }
        a:hover {
            text-decoration: none;
        }
        .url-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .url-section input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 3px;
            margin-top: 10px;
        }
        .instructions {
            margin-top: 30px;
            padding: 20px;
            background-color: #e8f4f8;
            border-radius: 5px;
            text-align: left;
        }
        .instructions h3 {
            margin-top: 0;
            color: #2c5282;
        }
        .instructions ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .instructions li {
            margin: 8px 0;
            line-height: 1.6;
        }
        .btn-print {
            background-color: #4CAF50;
            color: white;
            padding: 12px 30px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px;
        }
        .btn-print:hover {
            background-color: #45a049;
        }
        .btn-close {
            background-color: #6c757d;
            color: white;
            padding: 12px 30px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 10px;
        }
        .btn-close:hover {
            background-color: #5a6268;
        }
        @media print {
            body {
                background-color: white;
            }
            .qr-container {
                box-shadow: none;
                max-width: 100%;
            }
            .btn-print, .btn-close, .url-section {
                display: none;
            }
            .instructions {
                page-break-before: always;
            }
        }
        .print-header {
            display: none;
            text-align: center;
            margin-bottom: 20px;
        }
        @media print {
            .print-header {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="qr-container">
        <div class="print-header">
            <h2>Open House Sign-In</h2>
        </div>
        
        <div class="property-info">
            <h1><?php echo htmlspecialchars($this->bean->name); ?></h1>
            <div class="property-address">
                <?php 
                echo htmlspecialchars($this->bean->address_street);
                if ($this->bean->address_city) {
                    echo ', ' . htmlspecialchars($this->bean->address_city);
                }
                if ($this->bean->address_state) {
                    echo ', ' . htmlspecialchars($this->bean->address_state);
                }
                if ($this->bean->address_postalcode) {
                    echo ' ' . htmlspecialchars($this->bean->address_postalcode);
                }
                ?>
            </div>
        </div>
        
        <div class="qr-section">
            <h2>QR Code for Open House Sign-In</h2>
            <p>Visitors can scan this code with their smartphone to sign in</p>
            <a href="<?php echo htmlspecialchars($sign_in_url); ?>" target="_blank" title="Click to open sign-in form">
                <div class="qr-code" id="qrcode"></div>
            </a>
            <p style="font-size: 14px; color: #666; margin-top: 10px;">Click the QR code to preview the sign-in form</p>
        </div>
        
        <div class="url-section">
            <h3>Direct Sign-In URL:</h3>
            <input type="text" value="<?php echo htmlspecialchars($sign_in_url); ?>" readonly onclick="this.select();">
            <p style="margin-top: 10px; font-size: 14px; color: #666;">
                Click the URL above to select it, then copy and share it with visitors
            </p>
        </div>
        
        <div class="instructions">
            <h3>How to Use This QR Code:</h3>
            <ol>
                <li><strong>Print this page</strong> and display it at the property entrance or on a sign-in table</li>
                <li><strong>Visitors scan the code</strong> with their smartphone camera or QR code app</li>
                <li><strong>They fill out the form</strong> with their contact information</li>
                <li><strong>You receive notifications</strong> instantly when someone signs in</li>
                <li><strong>Leads are automatically created</strong> in your CRM with the visitor's information</li>
            </ol>
            
            <h3>Tips for Success:</h3>
            <ul>
                <li>Place the QR code sign at eye level near the entrance</li>
                <li>Have a tablet or laptop available as a backup sign-in option</li>
                <li>Test the QR code before the open house begins</li>
                <li>Consider printing multiple copies for different areas of the property</li>
            </ul>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <button class="btn-print" onclick="window.print();">
                <span class="glyphicon glyphicon-print"></span> Print QR Code
            </button>
            <button class="btn-close" onclick="window.close();">
                <span class="glyphicon glyphicon-remove"></span> Close Window
            </button>
        </div>
    </div>
    
    <script>
        // Generate QR code
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "<?php echo $sign_in_url; ?>",
            width: 300,
            height: 300,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
        
        // Auto-select URL when clicked
        document.querySelector('input[type="text"]').addEventListener('click', function() {
            this.select();
        });
    </script>
</body>
</html>
        <?php
        
        // Don't display the standard SugarCRM chrome
        $this->options['show_header'] = false;
        $this->options['show_footer'] = false;
        $this->options['show_javascript'] = false;
        $this->options['show_subpanels'] = false;
        $this->options['show_search'] = false;
    }
}