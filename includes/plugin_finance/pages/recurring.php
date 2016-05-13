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


if(!module_finance::can_i('view','Finance Upcoming')){
    redirect_browser(_BASE_HREF);
}

if(isset($_REQUEST['finance_recurring_id']) && $_REQUEST['finance_recurring_id'] && isset($_REQUEST['record_new'])){
    include(module_theme::include_ucm(dirname(__FILE__).'/finance_edit.php'));
}else if(isset($_REQUEST['finance_recurring_id']) && $_REQUEST['finance_recurring_id']){
    //include("recurring_edit.php");
    include(module_theme::include_ucm(dirname(__FILE__).'/recurring_edit.php'));
}else{
    //include("recurring_list.php");
    include(module_theme::include_ucm(dirname(__FILE__).'/recurring_list.php'));
}