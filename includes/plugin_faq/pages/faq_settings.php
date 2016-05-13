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

if(!module_config::can_i('view','Settings') || !module_faq::can_i('edit','FAQ')){
    redirect_browser(_BASE_HREF);
}

$module->page_title = 'FAQ Settings';

$links = array(
    array(
        "name"=>'FAQ Products',
        'm' => 'faq',
        'p' => 'faq_products',
        'force_current_check' => true,
        'order' => 1, // at start.
        'menu_include_parent' => 1,
        'allow_nesting' => 1,
        'args'=>array('faq_id'=>false,'faq_product_id'=>false),
    ),
    array(
        "name"=>'Questions & Answers',
        'm' => 'faq',
        'p' => 'faq_questions',
        'force_current_check' => true,
        'order' => 2, // at start.
        'menu_include_parent' => 1,
        'allow_nesting' => 1,
        'args'=>array('faq_id'=>false,'faq_product_id'=>false),
    ),
    array(
        "name"=>'Settings',
        'm' => 'faq',
        'p' => 'faq_settings_basic',
        'force_current_check' => true,
        'order' => 3, // at start.
        'menu_include_parent' => 1,
        'allow_nesting' => 1,
        'args'=>array('faq_id'=>false,'faq_product_id'=>false),
    ),
);
