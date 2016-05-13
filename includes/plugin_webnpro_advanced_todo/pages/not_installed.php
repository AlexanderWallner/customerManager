<?php

/*
 *  Module: webNpro Advanced Todo v2.0
 *  Copyright: Kőrösi Zoltán | webNpro - hosting and design | http://webnpro.com | info@webnpro.com
 *  Contact / Feature request: info@webnpro.com (Hungarian / English)
 *  License: Please check CodeCanyon.net for license details.
 *  More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/
 */

$info = parse_ini_file(dirname(__FILE__) . '/../plugin.info');
$module->page_title = $info['fullname'];

print_heading(array(
    'main' => true,
    'type' => 'h2',
    'title' => $info['fullname'],
));

echo '<br/><center><a style="color: red"; font-weight: bold;" href="' . _BASE_HREF . '?m[0]=config&p[0]=config_admin&m[1]=config&p[1]=config_upgrade&run_upgrade=true ">' . _l('Please click here to finish the installation of the plugin!') . '</a></center>';
echo '<br><center>' . _l("The manual upgrade procedure will starting and finishing the installation procedure. When it's done you can make your first todo item...") . '</center>';
