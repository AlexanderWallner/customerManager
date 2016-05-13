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

$quote_safe = true; // stop including files directly.
if(!module_quote::can_i('view','Quotes')){
    echo 'permission denied';
    return;
}

if(isset($_REQUEST['quote_id'])){

    if(isset($_REQUEST['email_staff'])){
        include(module_theme::include_ucm("includes/plugin_quote/pages/quote_admin_email_staff.php"));

    }else if(isset($_REQUEST['email'])){
        include(module_theme::include_ucm("includes/plugin_quote/pages/quote_admin_email.php"));

    }else if((int)$_REQUEST['quote_id'] > 0){
        include(module_theme::include_ucm("includes/plugin_quote/pages/quote_admin_edit.php"));
        //include("quote_admin_edit.php");
    }else{
        include(module_theme::include_ucm("includes/plugin_quote/pages/quote_admin_create.php"));
        //include("quote_admin_create.php");
    }

}else{

    include(module_theme::include_ucm("includes/plugin_quote/pages/quote_admin_list.php"));
	//include("quote_admin_list.php");
	
} 

