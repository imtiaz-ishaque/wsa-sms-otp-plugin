<?php
// Shortcode for displaying the OTP form

// Register the shortcode [wsa_sms_otp_form]
add_shortcode('wsa_sms_otp_form', 'sms_otp_form_shortcode');

// Function to handle the shortcode
function sms_otp_form_shortcode()
{
    // Enqueue JavaScript with localized variables
    wp_enqueue_script('smsapi-script', plugin_dir_url(__FILE__) . 'smsapi-script.js', array('jquery'), '1.0', true);

    // Pass the necessary variables to JavaScript securely
    wp_localize_script('smsapi-script', 'smsapi_params', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('smsapi-nonce'),
    ));
}
