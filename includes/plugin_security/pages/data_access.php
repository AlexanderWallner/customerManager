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

$access = true;


switch($table_name){
    case 'invoice':
    default:
        // check if current user can access this invoice.
        if($data && isset($data['customer_id']) && (int)$data['customer_id']>0){
            $valid_customer_ids = module_security::get_customer_restrictions();
            if($valid_customer_ids){
                $access = isset($valid_customer_ids[$data['customer_id']]);
                if(!$access)return false;
            }
        }
        break;
}