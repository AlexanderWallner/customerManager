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


// show all datas.
if(isset($_REQUEST['search_form'])){

	include("admin_data_search.php");

}else if(isset($_REQUEST['data_new'])){

	include("admin_data_new.php");
	
}else if(isset($_REQUEST['data_record_id']) && $_REQUEST['data_record_id'] ){
	//&& isset($_REQUEST['data_type_id']) && $_REQUEST['data_type_id']
	
	include("admin_data_open.php");
	
}else{
	
	include("admin_data_list.php");
}

