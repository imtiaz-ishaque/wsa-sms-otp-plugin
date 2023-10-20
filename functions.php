<?php
// Functions for SMS OTP functionality
add_action('admin_enqueue_scripts', 'load_assets_sms_otp');
function load_assets_sms_otp()
{
    $current_screen = get_current_screen();
    if (strpos($current_screen->base, 'sms-otp-settings') === false) {
        return;
    } else {
        wp_enqueue_style("smsapi-style", plugins_url('smsapi-style.css', __FILE__));
    }
}

// Hook the function to run when WordPress is loaded
add_action('init', 'send_sms_via_smsapi');

function send_sms_via_smsapi()
{
    // Use SMS API to send SMS
    $auth_token = get_option('auth_token');
    $from = get_option('from');
    $message_type = get_option('message_type');

    // Check if this is an AJAX request
    if (isset($_POST['action']) && $_POST['action'] === 'send_sms') {
        // Get the fullPhoneNumber value from the AJAX request
        $fullPhoneNumber = sanitize_text_field($_POST['fullPhoneNumber']);

        // Modify your SMS sending code accordingly
        $url = 'https://api.smsapi.com/mfa/codes';
        $ch = curl_init($url);

        // Check if cURL initialization was successful
        if ($ch === false) {
            die('cURL initialization failed');
        }

        $params = array(
            'phone_number' => $fullPhoneNumber,  // Use the fullPhoneNumber here
            'from'         => $from,
            'content'      => 'Your code: [%code%]',
            'fast'         => $message_type
        );

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $auth_token"));

        $result = curl_exec($ch);
        curl_setopt($ch, CURLOPT_HEADER, true);
        if ($result === false) {
            echo 'cURL error: ' . curl_error($ch);
        } else {
            // Handle the response here
            echo 'Response: ' . $result;
        }

        curl_close($ch);

        // Always exit to avoid further execution
        exit();
    }
}

// Hook the function to a WordPress action or filter, e.g., 'init'
add_action('init', 'send_smsapi_verification');

function send_smsapi_verification()
{
    // Use SMS API to send SMS
    $auth_token = get_option('auth_token');
    
    if (isset($_POST['action']) && $_POST['action'] === 'verify_otp') {
        $codeToVerify = sanitize_text_field($_POST['userEnteredOTP']);
        $fullPhoneNumber = sanitize_text_field($_POST['fullPhoneNumber']);

        // Retrieve fullPhoneNumber from the AJAX request
        $url = 'https://api.smsapi.com/mfa/codes/verifications';
        $ch = curl_init($url);

        $params = array(
            'phone_number' => $fullPhoneNumber,    // Use phone number 
            'code'         => $codeToVerify          // code to check
        );

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $auth_token"));

        $result = curl_exec($ch);
        curl_setopt($ch, CURLOPT_HEADER, true);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode === 204) {
            echo "OTP Verified Successfully!";
        } elseif ($httpCode === 404) {
            echo "Wrong OTP. Please try again.";
        } else {
            echo "An error occurred while verifying OTP.";
        }
        curl_close($ch);
        // Always exit to avoid further execution
        exit();
    }
}