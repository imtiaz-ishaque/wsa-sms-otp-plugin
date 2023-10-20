<?php
// Admin settings for SMS API OTP plugin

// Register the settings menu
add_action('admin_menu', 'sms_otp_plugin_menu');

function sms_otp_plugin_menu()
{
    add_menu_page(
        'SMS OTP Plugin Settings',
        'SMS API OTP Settings',
        'manage_options',
        'sms-otp-settings',
        'sms_otp_plugin_settings_page'
    );
}

// Register settings
add_action('admin_init', 'sms_otp_plugin_settings');

function sms_otp_plugin_settings()
{
    register_setting('sms-otp-settings-group', 'auth_token');
    register_setting('sms-otp-settings-group', 'from');
    register_setting('sms-otp-settings-group', 'message_type');
}

// Function to display the settings page
function sms_otp_plugin_settings_page()
{
?>
    <link rel="stylesheet" href="<?php echo plugin_dir_url("/style.css") ?>">
    <div class="wrap">
        <h2>SMS API OTP Plugin Settings</h2>

        <form method="post" action="options.php">
            <?php settings_fields('sms-otp-settings-group'); ?>
            <?php do_settings_sections('sms-otp-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Auth Token:</th>
                    <td><input type="text" class="text-width-50" name="auth_token" value="<?php echo esc_attr(get_option('auth_token')); ?>" />
                        <br>
                        <small>You can obtain an API token from the <a href="https://ssl.smsapi.com/" target="_blank">website</a>.</small>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">From:</th>
                    <td><input type="text" class="text-width-50" name="from" value="<?php echo esc_attr(get_option('from')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Fast mode:</th>
                    <td><input type="text" class="text-width-50" name="message_type" value="<?php echo esc_attr(get_option('message_type')); ?>" />
                        <br>
                        <small>Setting the priority of the sent message, by default fast=1 with quickest possible time of delivery, costs 50% more than normal message</small>
                    </td>
                </tr>
            </table>
            <hr>
            <p>
                <strong>Usage:</strong>
            <ul>
                <li>Create a new page or post.</li>
                <li>Add the shortcode <strong>[wsa_sms_otp_form]</strong> to the content.</li>
            </ul>
            </p>
            <?php submit_button(); ?>
        </form>

    </div>
<?php
}
