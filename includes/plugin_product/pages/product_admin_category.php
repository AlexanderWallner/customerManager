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


if(isset($_REQUEST['product_category_id']) && $_REQUEST['product_category_id'] != ''){
    $product_category_id = (int)$_REQUEST['product_category_id'];
    $product_category = module_product::get_product_category($product_category_id);
    include('product_admin_category_edit.php');
}else{
	include('product_admin_category_list.php');
}
