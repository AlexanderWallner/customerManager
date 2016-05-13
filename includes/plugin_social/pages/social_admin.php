<!-- show a list of tabs for all the different social methods, as menu hooks -->

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

$module->page_title = _l('Social');


$links = array();
if(module_social::can_i('view','Combined Comments','Social','social')){
	$links [] = array(
        "name"=>_l('Inbox'),
        'm' => 'social',
        'p' => 'social_messages',
		'args' => array(
			'combined' => 1,
			'social_twitter_id' => false,
			'social_facebook_id' => false,
		),
        'force_current_check' => true,
        //'current' => isset($_GET['combined']),
        'order' => 1, // at start
        'menu_include_parent' => 0,
        'allow_nesting' => 1,
    );

	//if(isset($_GET['combined'])){
	//	include('social_messages.php');
	//}
}