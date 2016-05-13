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
if(class_exists('module_job',false) && module_job::can_i('view','Jobs') && module_security::can_user(module_security::get_loggedin_id(),'Show Dashboard Widgets')){
	// find out how many open jobs are left..
	$jobs = module_job::get_jobs(array('completed'=>3),array('columns'=>'u.job_id'));
	ob_start();
	// icons from http://ionicons.com/
	?>

	<div class="small-box bg-aqua">
	    <div class="inner">
	        <h3>
	            <?php echo count($jobs); ?>
	        </h3>
	        <p>
	            <?php _e('Incomplete Jobs');?>
	        </p>
	    </div>
	    <div class="icon">
	        <i class="ion ion-document-text"></i>
	    </div>
	    <a href="<?php echo module_job::link_open(false);?>" class="small-box-footer">
	        <?php _e('View Jobs');?> <i class="fa fa-arrow-circle-right"></i>
	    </a>
	</div>

	<?php
	$widgets[] = array(
		'id'      => 'open_jobs',
		'columns' => 4,
		'raw' => true,
		'content' => ob_get_clean(),
	);
}