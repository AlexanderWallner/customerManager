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

if(!module_social::can_i('edit','Facebook','Social','social')){
    die('No access to Facebook accounts');
}

$social_facebook_id = isset($_REQUEST['social_facebook_id']) ? (int)$_REQUEST['social_facebook_id'] : 0;
$facebook = new ucm_facebook_account($social_facebook_id);

$facebook_page_id = isset($_REQUEST['facebook_page_id']) ? (int)$_REQUEST['facebook_page_id'] : 0;

/* @var $pages ucm_facebook_page[] */
$pages = $facebook->get('pages');
if(!$facebook_page_id || !$pages || !isset($pages[$facebook_page_id])){
	die('No pages found to refresh');
}
?>
Manually refreshing page data...
<?php

$pages[$facebook_page_id]->graph_load_latest_page_data();
