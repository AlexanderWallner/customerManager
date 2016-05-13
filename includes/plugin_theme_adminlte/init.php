<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55, 7c3ba354-e0b5-4f02-8a97-854fab16f4b1
  * Package Date: 2015-02-04 02:18:43 
  * IP Address: 185.17.207.18
  */

if(!isset($_REQUEST['display_mode']) || (isset($_REQUEST['display_mode']) && $_REQUEST['display_mode']!='iframe' && $_REQUEST['display_mode']!='ajax')){
    $_REQUEST['display_mode'] = 'adminlte';
}
require_once(module_theme::include_ucm('includes/plugin_theme_adminlte/functions.php'));

module_config::register_css('theme','bootstrap.min.css',full_link('/includes/plugin_theme_adminlte/css/bootstrap.min.css'),11);
module_config::register_css('theme','select2.css',full_link('/includes/plugin_theme_adminlte/css/select2/select2.css'),11);
module_config::register_css('theme','plugins.css',full_link('/includes/plugin_theme_adminlte/css/plugins.css'),11);
module_config::register_css('theme','layout.css',full_link('/includes/plugin_theme_adminlte/css/layout.css'),11);
module_config::register_css('theme','font-awesome.min.css',full_link('/includes/plugin_theme_adminlte/css/font-awesome.min.css'),11);
module_config::register_css('theme','custom.css',full_link('/includes/plugin_theme_adminlte/css/custom.css'),11);
module_config::register_css('theme','jquery.ui.min.css',full_link('/includes/plugin_theme_adminlte/css/jquery-ui-1.10.3.custom.css'),5);
module_config::register_css('theme','styles.css',full_link('/console_chat/css/styles.css'),11);
//module_config::register_css('theme','jquery.ui.structure.min.css',full_link('/includes/plugin_theme_adminlte/css/jquery-ui.structure.min.css'),6);
//module_config::register_css('theme','jquery.ui.theme.min.css',full_link('/includes/plugin_theme_adminlte/css/jquery-ui.theme.min.css'),7);
module_config::register_css('theme','AdminLTE.css',full_link('/includes/plugin_theme_adminlte/css/AdminLTE.css'),12);

if(isset($_SERVER['REQUEST_URI']) && (strpos($_SERVER['REQUEST_URI'],_EXTERNAL_TUNNEL) || strpos($_SERVER['REQUEST_URI'],_EXTERNAL_TUNNEL_REWRITE))){
    module_config::register_css('theme','external.css',full_link('/includes/plugin_theme_adminlte/css/external.css'),100);
}


module_config::register_js('theme','jquery.min.js',full_link('/includes/plugin_theme_adminlte/js/jquery.min.js'),1);
module_config::register_js('theme','select2.min.js',full_link('/includes/plugin_theme_adminlte/css/select2/select2.min.js'),7);
module_config::register_js('theme','jquery-ui.min.js',full_link('/includes/plugin_theme_adminlte/js/jquery-ui-1.10.3.custom.min.js'),2);
module_config::register_js('theme','cookie.js',full_link('/js/cookie.js'),3);
module_config::register_js('theme','javascript.js',full_link('/js/javascript.js'),4);
module_config::register_js('theme','bootstrap.min.js',full_link('/includes/plugin_theme_adminlte/js/bootstrap.min.js'),6);

module_config::register_js('theme','metronic.js',full_link('/includes/plugin_theme_adminlte/js/metronic.js'),9);
module_config::register_js('theme','scritpts.js',full_link('/console_chat/js/scritpts.js'),10);
module_config::register_js('theme','jquery.slimscroll.min.js',full_link('/includes/plugin_theme_adminlte/css/jquery-slimscroll/jquery.slimscroll.min.js'),14);

module_config::register_js('theme','app.js',full_link('/includes/plugin_theme_adminlte/js/AdminLTE/app.js'));
module_config::register_js('theme','adminlte.js',full_link('/includes/plugin_theme_adminlte/js/adminlte.js'));

function adminlte_dashboard_widgets() {
	$widgets = array();

	// the 4 column widget areas:
	foreach(glob(dirname(__FILE__).'/dashboard_widgets/widget_*.php') as $dashboard_widget_file){
		@include($dashboard_widget_file);
	}

	return $widgets;
} // end hook function
hook_add( 'dashboard_widgets', 'adminlte_dashboard_widgets' );
