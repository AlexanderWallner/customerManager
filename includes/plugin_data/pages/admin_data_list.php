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

// include this file to list some type of data
// supports different types of lists, everything from a major table list down to a select dropdown list

$display_type = 'table';
$allow_search = true;


switch($display_type){
	case 'table':
		
		$data_types = $module->get_data_types();
		foreach($data_types as $data_type){
			$data_type_id = $data_type['data_type_id'];
            if(isset($_REQUEST['data_type_id']) && $data_type_id != $_REQUEST['data_type_id'])continue;

            include('admin_data_list_type.php');

		}
		
		break;
	case 'select':
		
		break;
	default:
		echo 'Display type: '.$display_type.' unknown.';
		break;
}