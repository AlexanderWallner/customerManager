<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55, 92d36d3b-0276-47ef-93d8-00df5dc17d5e, 3dac064b-a466-4931-93f0-51c086616894, 0b59530f-164b-4ec2-975b-6a3038423838, 7c3ba354-e0b5-4f02-8a97-854fab16f4b1, c1fe4c24-77fe-44eb-a399-426ebdf540b9, ef74c493-5050-4b88-9be1-92e54c0e34aa, a59dba67-f1e1-4085-943c-c3ded9fc5667, c46a4f2a-d54d-4778-9c2d-c61f41691e34, 68ccf1c5-a309-443a-b04e-69d266cb348f
  * Package Date: 2015-03-08 05:40:21 
  * IP Address: 185.17.207.69
  */


if(!module_config::can_i('view','Settings')){
    redirect_browser(_BASE_HREF);
}

print_heading('FAQ Settings');
$c = array();
$customers = module_customer::get_customers();
foreach($customers as $customer){
    $c[$customer['customer_id']] = $customer['customer_name'];
}

module_config::print_settings_form(
    array(
        array(
            'key'=>'faq_ticket_show_product_selection',
            'default'=>1,
            'type'=>'checkbox',
            'description'=>'Show product selection on ticket submit form.',
        ),
    )
);

?>

<?php

print_heading('FAQ Embed');
?>
<p>
    <?php _e('Place this in an iframe on your website, or as a link on your website, and people can view FAQ tickets.'); ?>
</p>
<p><a href="<?php echo module_faq::link_open_public(-1);?>?show_search=1&show_header=1&show_product=1" target="_blank"><?php echo module_faq::link_open_public(-1);?>?show_search=1&show_header=1&show_product=1</a></p>

<?php

print_heading('FAQ WordPress Plugin');
?>
<p>
    You can use this basic WordPress plugin to embed FAQ items onto your WordPress blog. Some PHP knowledge is required, this is a slightly advanced technique. https://github.com/dtbaker/ucm-wordpress
</p>
