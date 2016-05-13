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
if(isset($_REQUEST['go'])){
    ob_end_clean();
    echo '<pre>';
    _e("Checking for bounces, please wait...");
    echo "\n\n";
    module_newsletter::check_bounces(true);
    echo "\n\n";
    _e("done.");
    echo '</pre>';

    exit;
}

$module->page_title = _l('Newsletter Bounce Checking');
print_heading('Newsletter Bounce Checking');

?>
<p><?php _e('Bounces are checked automatically using the CRON job, however if you want to check for bounces manually (ie: to see any error) please click the button below.');?></p>
<form action="" method="post">
<input type="submit" name="go" value="<?php _e('Check for bounces');?>">
</form>