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

$search = (isset($_REQUEST['search']) && is_array($_REQUEST['search'])) ? $_REQUEST['search'] : array();

$newsletter_templates = module_newsletter::get_templates($search);


$header = array(
    'title' => _l('Newsletter Templates'),
    'type' => 'h2',
    'main' => true,
    'button' => array(),
);
$header['button'] = array(
    'url' => module_newsletter::link_open_template('new'),
    'title' => _l('Add New Template'),
    'type' => 'add',
);
print_heading($header);


$table_manager = module_theme::new_table_manager();
$columns = array();
$columns['newsletter_template_name'] = array(
    'title' => 'Template Name',
    'callback' => function($newsletter_template){
		echo module_newsletter::link_open_template($newsletter_template['newsletter_template_id'],true);
    },
    'cell_class' => 'row_action',
);
$columns['newsletter_template_action'] = array(
    'title' => 'Action',
    'callback' => function($newsletter_template){
		?> <a href="<?php echo module_newsletter::link_open_template($newsletter_template['newsletter_template_id']);?>">Edit</a> <?php
    },
);
$table_manager->set_columns($columns);
$table_manager->set_rows($newsletter_templates);
$table_manager->pagination = false;
$table_manager->print_table();

?>

