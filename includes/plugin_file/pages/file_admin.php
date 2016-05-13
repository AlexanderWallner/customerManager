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

$file_safe = true;
$file_id = isset($_REQUEST['file_id']) ? (int)$_REQUEST['file_id'] : false;

if($file_id && isset($_REQUEST['email'])){

    include(module_theme::include_ucm('includes/plugin_file/pages/file_admin_email.php'));

}else if(isset($_REQUEST['file_id'])){


	$ucm_file = new ucm_file( $file_id );
	$ucm_file->check_page_permissions();
	$file    = $ucm_file->get_data();
	$file_id = (int) $file['file_id']; // sanatisation/permission check

	if(isset($_REQUEST['bucket']) || (isset($file['bucket']) && $file['bucket'])){
	    include(module_theme::include_ucm('includes/plugin_file/pages/file_admin_bucket.php'));
	}else{
		include(module_theme::include_ucm('includes/plugin_file/pages/file_admin_edit.php'));
	}


}else{
	
    include(module_theme::include_ucm('includes/plugin_file/pages/file_admin_list.php'));
	
} 

