<?php
/*
Plugin Name: SMS OTP Plugin
Plugin URI: https://websolapp.net/
Description: The SMS OTP WordPress plugin adds an extra layer of security to your website by implementing two-factor authentication through one-time passcodes sent via text message. Users receive a unique code on their mobile phones, ensuring that only authenticated individuals can access sensitive areas of your site. It's a simple yet effective way to enhance login security and protect user accounts from unauthorized access.
Version: 1.0
Author: Imtiaz Ahmed Ishaque.
Author URI: https://github.com/imtiaz-ishaque
Update URI: https://websolapp.net/
Text Domain: wsa-sms-otp-plugin
*/

// Define constants
define('SMS_OTP_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Include necessary files
require_once SMS_OTP_PLUGIN_DIR . 'functions.php';
require_once SMS_OTP_PLUGIN_DIR . 'shortcode.php';
require_once SMS_OTP_PLUGIN_DIR . 'admin-settings.php';