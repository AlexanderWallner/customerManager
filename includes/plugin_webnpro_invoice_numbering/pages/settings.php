<?php

/*
 *  Module: webNpro Invoice Numbering v1.0
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
if (!module_invoice::can_i('edit', 'Invoice Settings', 'Config')) {
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
    array(
        'key' => 'invoice_number_custom_format',
        'default' => '{NUM3}/{YEAR}',
        'type' => 'text',
        'description' => _l('Custom Invoice Numbering Format'),
        'size' => '100',
        'help' => _l('Please check the Help below or leave it empty to use the default UCM methods.'),
    ),
    array(
        'key' => 'use_custom_invoice_numbering_format',
        'default' => '1',
        'type' => 'checkbox',
        'description' => _l('Use the custom invoice numbering'),
        'help' => _l('Please check this if you would like to use the custom invoice numbering format or leave it unchecked to use the default UCM methods.'),
    ),
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

// Print the license and help informations
echo '<br/>';
echo $license;
echo '
<br/>
<h2>Shortcodes</h2>
<ul>
<li><strong>{PREFIX}</strong>  ' . _l('Customer invoice prefix.') . '</li>
<li><strong>{CUSTOMERID}</strong>  ' . _l('Customer ID') . '</li>
<li><strong>{JOBID}</strong>  ' . _l('Job ID') . '</li>
<li><strong>{YEAR}</strong>  ' . _l('Year (4 digits)') . '</li>
<li><strong>{YEAR2}</strong>  ' . _l('Year (2 digits)') . '</li>
<li><strong>{MONTH}</strong>  ' . _l('Month (2 digits)') . '</li>
<li><strong>{DAY}</strong>  ' . _l('Day (2 digits)') . '</li>
<li><strong>{NUM} / {NUM1}</strong>  ' . _l('0..9 - auto incremental') . '</li>
<li><strong>{NUM2}</strong>  ' . _l('00..99 - auto incremental') . '</li>
<li><strong>{NUM3}</strong>  ' . _l('000..999 - auto incremental') . '</li>
<li><strong>{NUM4}</strong>  ' . _l('0000..9999 - auto incremental') . '</li>
<li><strong>{NUM5}</strong>  ' . _l('00000..99999 - auto incremental') . '</li>
<li><strong>{HEX} / {HEX1}</strong>  ' . _l('0..F - auto incremental') . '</li>
<li><strong>{HEX2}</strong>  ' . _l('00..FF - auto incremental') . '</li>
<li><strong>{HEX3}</strong>  ' . _l('000..FFF - auto incremental') . '</li>
<li><strong>{HEX4}</strong>  ' . _l('0000..FFFF - auto incremental') . '</li>
<li><strong>{HEX5}</strong>  ' . _l('00000..FFFFF - auto incremental') . '</li>
<li><strong>{RANDNUM} / {RANDNUM1}</strong>  ' . _l('0..9 - random') . '</li>
<li><strong>{RANDNUM2}</strong>  ' . _l('00..99 - random') . '</li>
<li><strong>{RANDNUM3}</strong>  ' . _l('000..999 - random') . '</li>
<li><strong>{RANDNUM4}</strong>  ' . _l('0000..9999 - random') . '</li>
<li><strong>{RANDNUM5}</strong>  ' . _l('00000..99999 - random') . '</li>
<li><strong>{RANDHEX}</strong>  ' . _l('0..F - random') . '</li>
<li><strong>{RANDHEX2}</strong>  ' . _l('00..FF - random') . '</li>
<li><strong>{RANDHEX3}</strong>  ' . _l('000..FFF - random') . '</li>
<li><strong>{RANDHEX4}</strong>  ' . _l('0000..FFFF - random') . '</li>
<li><strong>{RANDHEX5}</strong>  ' . _l('00000..FFFFF - random') . '</li>
</ul>';
?>