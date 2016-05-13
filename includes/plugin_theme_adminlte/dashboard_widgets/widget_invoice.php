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
if(class_exists('module_invoice',false) && module_invoice::can_i('view','Invoices') && module_security::can_user(module_security::get_loggedin_id(),'Show Dashboard Widgets')){
	// find out how many open invoices are left..
	$count = 0;
	$invoices = module_invoice::get_invoices(array(), array(
        'custom_where' => " AND u.date_due != '0000-00-00' AND u.date_due <= '".date('Y-m-d',strtotime('+'.module_config::c('alert_days_in_future',5).' days'))."' AND u.date_paid = '0000-00-00'",
    ));

    foreach($invoices as $invoice) {
	    // needs 'overdue' and stuff which are unfortunately calculated.
	    $invoice = module_invoice::get_invoice( $invoice['invoice_id'] );
	    if ( ! $invoice || $invoice['invoice_id'] != $invoice['invoice_id'] ) {
		    continue;
	    }
	    if ( isset( $invoice['date_cancel'] ) && $invoice['date_cancel'] != '0000-00-00' ) {
		    continue;
	    }
	    $count ++;
    }
	ob_start();
	// icons from http://ionicons.com/
	?>

	<div class="small-box bg-red">
	    <div class="inner">
	        <h3>
	            <?php echo $count; ?>
	        </h3>
	        <p>
	            <?php _e('Overdue Invoices');?>
	        </p>
	    </div>
	    <div class="icon">
	        <i class="ion ion-stats-bars"></i>
	    </div>
	    <a href="<?php echo module_invoice::link_open(false);?>" class="small-box-footer">
	        <?php _e('View Invoices');?> <i class="fa fa-arrow-circle-right"></i>
	    </a>
	</div>

	<?php
	$widgets[] = array(
		'id'      => 'open_invoices',
		'columns' => 4,
		'raw' => true,
		'content' => ob_get_clean(),
	);
}