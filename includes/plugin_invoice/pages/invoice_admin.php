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

$invoice_safe = true;

if(isset($_REQUEST['print'])){
    include(module_theme::include_ucm("includes/plugin_invoice/pages/invoice_admin_print.php"));
        //include('invoice_admin_print.php');
}else if(isset($_REQUEST['invoice_id'])){

    if(isset($_REQUEST['email'])){
        include(module_theme::include_ucm("includes/plugin_invoice/pages/invoice_admin_email.php"));
        //include('invoice_admin_email.php');
    }else{
        /*if(module_security::getlevel() > 1){
            include('invoice_customer_view.php');
        }else{*/
            include(module_theme::include_ucm("includes/plugin_invoice/pages/invoice_admin_edit.php"));
            //include("invoice_admin_edit.php");
        /*}*/
    }

}else{

    include(module_theme::include_ucm("includes/plugin_invoice/pages/invoice_admin_list.php"));
	//include("invoice_admin_list.php");
	
} 

