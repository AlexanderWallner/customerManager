<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55
  * Package Date: 2015-02-04 02:18:43 
  * IP Address: 185.17.207.18
  */

$page_type = 'Vendors';
$page_type_single = 'Vendor';

if(!module_vendor::can_i('view',$page_type)){
    redirect_browser(_BASE_HREF);
}

if(isset($vendor_id)){
	// we're coming here a second time
}
$links = array();

$vendor_id = $_REQUEST['vendor_id'];
if($vendor_id && $vendor_id != 'new'){
	$vendor = module_vendor::get_vendor($vendor_id);
	// we have to load the menu here for the sub plugins under vendor
	// set default links to show in the bottom holder area.

    if(!$vendor || $vendor['vendor_id'] != $vendor_id){
        redirect_browser('');
    }
    $class = '';
    if(isset($vendor['vendor_status'])){
         switch($vendor['vendor_status']){
             case _VENDOR_STATUS_OVERDUE:
                 $class = 'vendor_overdue error_text';
                 break;
             case _VENDOR_STATUS_OWING:
                 $class = 'vendor_owing';
                 break;
             case _VENDOR_STATUS_PAID:
                 $class = 'vendor_paid success_text';
                 break;
         }
    }
	array_unshift($links,array(
		"name"=>_l(''.$page_type_single.': %s','<strong class="'.$class.'">'.htmlspecialchars($vendor['vendor_name']).'</strong>'),
		'm' => 'vendor',
		'p' => 'vendor_admin_open',
		'default_page' => 'vendor_admin_edit',
		'order' => 1,
		'menu_include_parent' => 0,
	));
}else{
	$vendor = array(
		'name' => 'New '.$page_type_single,
	);
	array_unshift($links,array(
		"name"=>'New '.$page_type_single.' Details',
		'm' => 'vendor',
		'p' => 'vendor_admin_open',
		'default_page' => 'vendor_admin_edit',
		'order' => 1,
		'menu_include_parent' => 0,
	));
}
