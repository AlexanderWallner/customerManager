<?php

/*
 *  Module: webNpro Advanced Todo v2.0
 *  Copyright: Kőrösi Zoltán | webNpro - hosting and design | http://webnpro.com | info@webnpro.com
 *  Contact / Feature request: info@webnpro.com (Hungarian / English)
 *  License: Please check CodeCanyon.net for license details.
 *  More license clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/
 */

// Get the infos about the plugin from the plugin.info file
$info = parse_ini_file(dirname(__FILE__) . '/../plugin.info');
$plugin_full_name = $info['fullname'];
$plugin_name = $info['modulename'];
$plugin_id = $info['id'];
$plugin_ver = $info['version'];

// Check permissions
if (!module_webnpro_advanced_todo::can_i('edit', 'webNpro Advanced Todo Settings', 'Config')) {
    redirect_browser(_BASE_HREF);
}

// Include the update class
require_once(dirname(__FILE__) . '/../update/updateclass.php');

// Get license infos
$update = new update;
$license = $update->get_license_info();
unset($update);

// Header buttons
$header_buttons[] = array(
    'url' => _BASE_HREF . '?m[0]=' . $plugin_name . '&p[0]=documentation',
    'title' => _l('Read Documentation')
);

// Settings
$settings = array(
    // We don't need this two anymore
    /*    array(
        'key' => 'js_combine',
        'default' => '1',
        'type' => 'checkbox',
        'description' => _l('Enable js_combine function'),
        'help' => _l('The js_combine function should be enabled. The compatibility problem is solved in the v1.1.3 version.')
    ),
    array(
        'key' => 'css_combine',
        'default' => '1',
        'type' => 'checkbox',
        'description' => _l('Enable css_combine function'),
        'help' => _l('The css_combine function should be enabled. The compatibility problem is solved in the v1.1.3 version.')
    ),*/
    array(
        'key' => $plugin_name . '_envato_license_number',
        'default' => '',
        'type' => 'text',
        'description' => _l('Plugin License key'),
        'size' => '100',
        'help' => _l('Please copy your license key here. They called it purchase code on the envato marketplaces. For more information about your license keys location check <a href="http://webnpro.com/images/envato_purchase_code_help.png" target="_blank">this image (...CLICK HERE...)</a>.'),
    ),
);

// Print the heading with the header buttons
print_heading(array(
    'type' => 'h2',
    'main' => true,
    'title' => _l($plugin_full_name) . ' v' . $plugin_ver,
    'button' => $header_buttons,
));

// Print the setting form
module_config::print_settings_form(
        array(
            'settings' => $settings,
        )
);

// Print the license informations
echo '<br/>';
echo $license;
?>