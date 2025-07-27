<?php
/**
 * Open House Sign-In EntryPoint
 * Provides a public-facing digital sign-in form for open houses
 */

if (!defined('sugarEntry')) define('sugarEntry', true);

require_once('include/utils.php');
require_once('include/SugarTheme/SugarTheme.php');
require_once('modules/Leads/Lead.php');
require_once('modules/Properties/Properties.php');

class OpenHouseSignIn
{
    private $property_id;
    private $token;
    private $property;
    private $errors = array();
    
    public function __construct()
    {
        global $sugar_config;
        
        // Initialize theme for CSS/JS resources
        SugarThemeRegistry::buildRegistry();
        $theme = SugarThemeRegistry::current();
        
        // Get parameters
        $this->property_id = isset($_REQUEST['property_id']) ? $_REQUEST['property_id'] : '';
        $this->token = isset($_REQUEST['token']) ? $_REQUEST['token'] : '';
        
        // Rate limiting check
        $this->checkRateLimit();
    }
    
    public function display()
    {
        // Validate property and token
        if (!$this->validateRequest()) {
            $this->showError('Invalid or expired sign-in link. Please contact the listing agent.');
            return;
        }
        
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleSubmission();
        } else {
            $this->showForm();
        }
    }
    
    private function validateRequest()
    {
        if (empty($this->property_id) || empty($this->token)) {
            return false;
        }
        
        // Load property
        $this->property = BeanFactory::getBean('Properties', $this->property_id);
        if (empty($this->property->id)) {
            return false;
        }
        
        // Validate token
        $expectedToken = $this->generateToken($this->property_id);
        if ($this->token !== $expectedToken) {
            return false;
        }
        
        return true;
    }
    
    private function generateToken($property_id)
    {
        global $sugar_config;
        $secret = isset($sugar_config['unique_key']) ? $sugar_config['unique_key'] : 'default_secret';
        return substr(md5($property_id . $secret), 0, 16);
    }
    
    private function showForm()
    {
        $property_name = $this->property->name;
        $property_address = $this->property->address_street . ', ' . 
                           $this->property->address_city . ', ' . 
                           $this->property->address_state . ' ' . 
                           $this->property->address_postalcode;
        
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open House Sign-In - <?php echo htmlspecialchars($property_name); ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .sign-in-container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .property-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
        }
        .property-header h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .property-address {
            color: #666;
            font-size: 16px;
        }
        .form-group label {
            font-weight: 600;
            color: #555;
        }
        .btn-sign-in {
            background-color: #28a745;
            border-color: #28a745;
            font-size: 18px;
            padding: 12px 30px;
            width: 100%;
            margin-top: 20px;
        }
        .btn-sign-in:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .success-message {
            text-align: center;
            padding: 40px;
        }
        .success-message .glyphicon {
            font-size: 60px;
            color: #28a745;
            margin-bottom: 20px;
        }
        .error-alert {
            margin-bottom: 20px;
        }
        .required {
            color: #dc3545;
        }
        .privacy-note {
            font-size: 12px;
            color: #666;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sign-in-container">
            <div class="property-header">
                <h1>Welcome to the Open House</h1>
                <div class="property-address">
                    <strong><?php echo htmlspecialchars($property_name); ?></strong><br>
                    <?php echo htmlspecialchars($property_address); ?>
                </div>
            </div>
            
            <?php if (!empty($this->errors)): ?>
                <div class="alert alert-danger error-alert">
                    <?php foreach ($this->errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="openHouseForm">
                <input type="hidden" name="property_id" value="<?php echo htmlspecialchars($this->property_id); ?>">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($this->token); ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $this->generateCSRFToken(); ?>">
                
                <div class="form-group">
                    <label for="first_name">First Name <span class="required">*</span></label>
                    <input type="text" class="form-control" id="first_name" name="first_name" required 
                           value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="last_name">Last Name <span class="required">*</span></label>
                    <input type="text" class="form-control" id="last_name" name="last_name" required
                           value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address <span class="required">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" required
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" 
                           placeholder="(555) 555-5555"
                           value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="notes">Questions or Comments</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3" 
                              placeholder="Let us know if you have any questions about this property..."><?php echo isset($_POST['notes']) ? htmlspecialchars($_POST['notes']) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="working_with_agent" value="1" 
                               <?php echo isset($_POST['working_with_agent']) ? 'checked' : ''; ?>>
                        I'm already working with a real estate agent
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary btn-sign-in">
                    <span class="glyphicon glyphicon-check"></span> Sign In
                </button>
                
                <p class="privacy-note">
                    Your information will only be used to contact you about this property 
                    and other similar listings. We respect your privacy.
                </p>
            </form>
        </div>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Phone number formatting
            $('#phone').on('input', function() {
                var number = $(this).val().replace(/[^\d]/g, '');
                if (number.length >= 6) {
                    number = number.replace(/(\d{3})(\d{3})(\d{0,4})/, '($1) $2-$3');
                } else if (number.length >= 3) {
                    number = number.replace(/(\d{3})(\d{0,3})/, '($1) $2');
                }
                $(this).val(number);
            });
            
            // Form validation
            $('#openHouseForm').on('submit', function(e) {
                var isValid = true;
                
                // Check required fields
                $(this).find('[required]').each(function() {
                    if (!$(this).val().trim()) {
                        $(this).addClass('has-error');
                        isValid = false;
                    } else {
                        $(this).removeClass('has-error');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                }
            });
        });
    </script>
</body>
</html>
        <?php
    }
    
    private function handleSubmission()
    {
        // Validate CSRF token
        if (!$this->validateCSRFToken($_POST['csrf_token'])) {
            $this->errors[] = 'Invalid form submission. Please try again.';
            $this->showForm();
            return;
        }
        
        // Validate required fields
        $required_fields = ['first_name', 'last_name', 'email'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $this->errors[] = 'Please fill in all required fields.';
                $this->showForm();
                return;
            }
        }
        
        // Validate email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Please enter a valid email address.';
            $this->showForm();
            return;
        }
        
        // Create lead
        if ($this->createLead()) {
            $this->showSuccessPage();
        } else {
            $this->errors[] = 'An error occurred while saving your information. Please try again.';
            $this->showForm();
        }
    }
    
    private function createLead()
    {
        global $timedate;
        
        try {
            $lead = BeanFactory::newBean('Leads');
            
            // Set lead fields
            $lead->first_name = $_POST['first_name'];
            $lead->last_name = $_POST['last_name'];
            $lead->email1 = $_POST['email'];
            $lead->phone_mobile = $_POST['phone'];
            $lead->description = "Open House Sign-In for: " . $this->property->name . "\n\n";
            
            if (!empty($_POST['notes'])) {
                $lead->description .= "Visitor Notes:\n" . $_POST['notes'] . "\n\n";
            }
            
            if (!empty($_POST['working_with_agent'])) {
                $lead->description .= "Note: Already working with another agent.\n";
            }
            
            $lead->lead_source = 'Open House';
            $lead->status = 'New';
            $lead->assigned_user_id = $this->property->assigned_user_id;
            
            // Set the property relationship field if it exists
            if (property_exists($lead, 'property_id_c')) {
                $lead->property_id_c = $this->property->id;
            }
            
            $lead->save();
            
            // Create relationship with property if relationship exists
            if ($lead->load_relationship('properties')) {
                $lead->properties->add($this->property->id);
            }
            
            // Send notification email to agent
            $this->sendAgentNotification($lead);
            
            // Log the sign-in
            $this->logSignIn($lead->id);
            
            return true;
            
        } catch (Exception $e) {
            $GLOBALS['log']->error('Open House Sign-In Error: ' . $e->getMessage());
            return false;
        }
    }
    
    private function sendAgentNotification($lead)
    {
        // Get agent info
        $agent = BeanFactory::getBean('Users', $this->property->assigned_user_id);
        if (empty($agent->email1)) {
            return;
        }
        
        // Prepare email
        require_once('modules/Emails/Email.php');
        require_once('include/SugarPHPMailer.php');
        
        $emailObj = new Email();
        $emailObj->to_addrs = $agent->email1;
        $emailObj->from_addr = 'noreply@' . $_SERVER['HTTP_HOST'];
        $emailObj->from_name = 'Open House Sign-In System';
        $emailObj->name = 'New Open House Visitor: ' . $lead->first_name . ' ' . $lead->last_name;
        
        $body = "A new visitor has signed in at your open house:\n\n";
        $body .= "Property: " . $this->property->name . "\n";
        $body .= "Visitor: " . $lead->first_name . ' ' . $lead->last_name . "\n";
        $body .= "Email: " . $lead->email1 . "\n";
        $body .= "Phone: " . $lead->phone_mobile . "\n";
        if (!empty($_POST['notes'])) {
            $body .= "Notes: " . $_POST['notes'] . "\n";
        }
        if (!empty($_POST['working_with_agent'])) {
            $body .= "\nNote: Visitor is already working with another agent.\n";
        }
        $body .= "\nView Lead: " . $GLOBALS['sugar_config']['site_url'] . "/index.php?module=Leads&action=DetailView&record=" . $lead->id;
        
        $emailObj->description_html = nl2br($body);
        $emailObj->description = $body;
        
        // Send email
        $emailObj->send();
    }
    
    private function showSuccessPage()
    {
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - Open House Sign-In</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .sign-in-container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .success-message {
            text-align: center;
            padding: 40px 20px;
        }
        .success-message .glyphicon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
        }
        .success-message h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .success-message p {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
        }
        .property-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sign-in-container">
            <div class="success-message">
                <span class="glyphicon glyphicon-ok-circle"></span>
                <h2>Thank You for Visiting!</h2>
                <p>Your information has been recorded. The listing agent will be in touch with you soon.</p>
                
                <div class="property-info">
                    <h4><?php echo htmlspecialchars($this->property->name); ?></h4>
                    <p><?php echo htmlspecialchars($this->property->address_street . ', ' . 
                                                  $this->property->address_city . ', ' . 
                                                  $this->property->address_state . ' ' . 
                                                  $this->property->address_postalcode); ?></p>
                </div>
                
                <p style="margin-top: 30px;">
                    <a href="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" class="btn btn-default">
                        Sign In Another Visitor
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
        <?php
    }
    
    private function showError($message)
    {
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Open House Sign-In</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
        }
        .error-container {
            max-width: 500px;
            margin: 0 auto;
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .error-container .glyphicon {
            font-size: 60px;
            color: #dc3545;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-container">
            <span class="glyphicon glyphicon-exclamation-sign"></span>
            <h2>Oops!</h2>
            <p><?php echo htmlspecialchars($message); ?></p>
        </div>
    </div>
</body>
</html>
        <?php
    }
    
    private function generateCSRFToken()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if (!isset($_SESSION['open_house_csrf'])) {
            $_SESSION['open_house_csrf'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['open_house_csrf'];
    }
    
    private function validateCSRFToken($token)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        return isset($_SESSION['open_house_csrf']) && $token === $_SESSION['open_house_csrf'];
    }
    
    private function checkRateLimit()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $ip = $_SERVER['REMOTE_ADDR'];
        $key = 'open_house_' . $ip;
        $limit = 10; // Max 10 submissions per hour
        $window = 3600; // 1 hour
        
        // Initialize or get current count
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = array(
                'count' => 0,
                'reset' => time() + $window
            );
        }
        
        // Reset if window expired
        if (time() > $_SESSION[$key]['reset']) {
            $_SESSION[$key] = array(
                'count' => 0,
                'reset' => time() + $window
            );
        }
        
        // Check limit
        if ($_SESSION[$key]['count'] >= $limit) {
            $this->showError('Too many submissions. Please try again later.');
            exit;
        }
        
        // Increment counter on POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION[$key]['count']++;
        }
    }
    
    private function logSignIn($lead_id)
    {
        $log_entry = date('Y-m-d H:i:s') . ' - ' . 
                    'Property: ' . $this->property_id . ' - ' .
                    'Lead: ' . $lead_id . ' - ' .
                    'IP: ' . $_SERVER['REMOTE_ADDR'] . "\n";
        
        $log_file = 'cache/open_house_signins.log';
        file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
    }
}

// Execute the entry point
$openHouse = new OpenHouseSignIn();
$openHouse->display();