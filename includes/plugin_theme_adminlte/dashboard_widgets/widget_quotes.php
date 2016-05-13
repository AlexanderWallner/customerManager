<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55, 7c3ba354-e0b5-4f02-8a97-854fab16f4b1
  * Package Date: 2015-02-04 02:18:43 
  * IP Address: 185.17.207.18
  */
if(class_exists('module_quote',false) && module_ticket::can_i('view','Quotes') && module_security::can_user(module_security::get_loggedin_id(),'Show Dashboard Widgets')){
	// find out how many open tickets are left..
	$quote_count = module_quote::get_quotes(array('u.date_approved'=>'0000-00-00'),array('columns'=>'u.quote_id'));
	ob_start();
	// icons from http://ionicons.com/
	?>

	<div class="small-box bg-green">
	    <div class="inner">
	        <h3>
	            <?php echo count($quote_count); ?>
	        </h3>
	        <p>
	            <?php _e('Open Quotes');?>
	        </p>
	    </div>
	    <div class="icon"><i class="ion ion-ios7-pricetag-outline"></i></div>
	    <a href="<?php echo module_quote::link_open(false);?>" class="small-box-footer">
	        <?php _e('View Quotes');?> <i class="fa fa-arrow-circle-right"></i>
	    </a>
	</div>

	<?php
	$widgets[] = array(
		'id'      => 'open_quotes',
		'columns' => 4,
		'raw' => true,
		'content' => ob_get_clean(),
	);
}