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

print_heading('Check Settings');
module_config::print_settings_form(
    array(
         array(
            'key'=>'payment_method_check_enabled',
            'default'=>0,
             'type'=>'checkbox',
             'description'=>'Enable Payment Method',
         ),
         array(
            'key'=>'payment_method_check_label',
            'default'=>'Check',
             'type'=>'text',
             'description'=>'Name this payment method',
         ),
    )
);

print_heading('Check Templates');
echo module_template::link_open_popup('paymethod_check');
echo module_template::link_open_popup('paymethod_check_details');
?>
