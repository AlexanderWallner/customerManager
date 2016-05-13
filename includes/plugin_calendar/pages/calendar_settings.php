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

if(!module_config::can_i('view','Settings')){
    redirect_browser(_BASE_HREF);
}

print_heading('Google Calendar Integration');

?>

<p><?php _e('<a href="%s" target="_blank">Click here</a> to see how to add a URL to your Google Calendar. You can choose which calendars to import below:','http://www.google.com/support/calendar/bin/answer.py?answer=37100');?></p>

<p><?php _e('Here is the URL for <strong>Your Alerts</strong> (ie: the listing from the dashbaord).');?></p>
<p>
    <a href="<?php echo module_calendar::link_calendar('alerts',array());?>" target="_blank"><?php echo module_calendar::link_calendar('alerts',array());?></a>
</p>
<hr>

<?php if(is_file('includes/plugin_finance/pages/finance.php')){ ?>
<p><?php _e('Here are the URL\'s for <strong>Completed Finance Transactions</strong> (ie: ones you see in the <a href="%s">transaction listing</a> page).',module_finance::link_open(false));?></p>
<p>
    Credit Transactions: <a href="<?php echo module_calendar::link_calendar('finance_transactions',array('credit'=>1));?>" target="_blank"><?php echo module_calendar::link_calendar('finance_transactions',array('credit'=>1));?></a> <br/>
    Debit Transactions: <a href="<?php echo module_calendar::link_calendar('finance_transactions',array('debit'=>1));?>" target="_blank"><?php echo module_calendar::link_calendar('finance_transactions',array('debit'=>1));?></a>
</p>
<hr>

<p><?php _e('Here are the URL\'s for <strong>Recurring Finance Transactions</strong> (ie: anything upcoming in the <a href="%s">recurring transactions listing</a> page).',module_finance::link_open_recurring(false));?></p>
<p>
    Credit Transactions: <a href="<?php echo module_calendar::link_calendar('finance_recurring',array('credit'=>1));?>" target="_blank"><?php echo module_calendar::link_calendar('finance_recurring',array('credit'=>1));?></a> <br/>
    Debit Transactions: <a href="<?php echo module_calendar::link_calendar('finance_recurring',array('debit'=>1));?>" target="_blank"><?php echo module_calendar::link_calendar('finance_recurring',array('debit'=>1));?></a>
</p>
<hr>
<p><?php _e('Here is the URL for <strong>Weekly Finance Summary</strong> (ie: amount invoiced, earnt and spent at the end of each week).');?></p>
<p>
    <a href="<?php echo module_calendar::link_calendar('finance_summary',array());?>" target="_blank"><?php echo module_calendar::link_calendar('finance_summary',array());?></a>
</p>

<?php }else{ ?>

    <p>
        <?php _e('Please install the <a href="%s" target="_blank">Finance Plugin</a> to receive more financial reporting in the calendar.','http://codecanyon.net/item/ucm-plugin-finance-manager/1396831?ref=dtbaker');?>
    </p>

<?php } ?>



    