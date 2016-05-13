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


if(_DEMO_MODE){
	?>
	<p>Demo Mode Notice: <strong>This is a public demo. Please only use TEST accounts here as others will see them.</strong></p>
	<?php
}


if(isset($_REQUEST['social_facebook_id']) && !empty($_REQUEST['social_facebook_id'])){
    $social_facebook_id = (int)$_REQUEST['social_facebook_id'];
	$social_facebook = module_social_facebook::get($social_facebook_id);
    include('facebook_account_edit.php');
}else{
	include('facebook_account_list.php');
}
