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




if(isset($_REQUEST['social_twitter_id']) && !empty($_REQUEST['social_twitter_id'])){
    $social_twitter_id = (int)$_REQUEST['social_twitter_id'];
	$social_twitter = module_social_twitter::get($social_twitter_id);
    include('twitter_account_edit.php');
}else{

	if(_DEMO_MODE){
		?>
		<p>Demo Mode Notice: <strong>This is a public demo. Please only use TEST accounts here as others will see them.</strong></p>
		<?php
	}

	print_heading('Twitter Settings');
	?>
	<p>Please go to <a href="https://apps.twitter.com/" target="_blank">https://apps.twitter.com/</a> and create an app. Enter your API Keys below: <?php _h('Signin with Twitter at https://apps.twitter.com/ and click the Create New App button. Enter a Name, Description, Website and in the Callback URL just put your website address. Once created, go to Permissions and choose "Read, write, and direct messages" then go to API Keys and copy your API Key and API Secret from here into the below form.'); ?></p>
	<?php
	module_config::print_settings_form(
	    array(
	         array(
	            'key'=>'social_twitter_api_key',
	            'default'=>'',
	             'type'=>'text',
	             'description'=>'App API Key ',
	             'help'=>'The API key obtained from creating your app on dev.twitter.com ',
	         ),
	         array(
	            'key'=>'social_twitter_api_secret',
	            'default'=>'',
	             'type'=>'text',
	             'description'=>'App API Secret ',
	             'help'=>'The API secret obtained from creating your app on dev.twitter.com ',
	         ),
	    )
	);

	// show twitter app settings here.
	include('twitter_account_list.php');
}
