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


if(isset($_REQUEST['user_id'])){


    $user_safe = true;
    include(module_theme::include_ucm("includes/plugin_user/pages/contact_admin_edit.php"));
    //include("contact_admin_edit.php");

}else{ 
	
    include(module_theme::include_ucm("includes/plugin_user/pages/contact_admin_list.php"));
    //include("contact_admin_list.php");

} 

