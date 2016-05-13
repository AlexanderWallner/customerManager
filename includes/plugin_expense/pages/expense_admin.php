<?php 
/** 

  */ 
  
 
$links = array();
$menu_position = 1;
  
array_unshift($links,array(
    "name"=>"All expenses",
    'm' => 'expense',
    'p' => 'expense_admin',
    'default_page' => 'expense_list',
    'order' => $menu_position++,
    'menu_include_parent' => 0,
    'allow_nesting' => 0,
    'args' => array('expense_id'=>false),
));

// include(module_theme::include_ucm("includes/plugin_expense/pages/expense_list.php"));