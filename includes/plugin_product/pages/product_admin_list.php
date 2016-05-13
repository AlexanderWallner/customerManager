<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55, 92d36d3b-0276-47ef-93d8-00df5dc17d5e, 3dac064b-a466-4931-93f0-51c086616894, 0b59530f-164b-4ec2-975b-6a3038423838, 7c3ba354-e0b5-4f02-8a97-854fab16f4b1, c1fe4c24-77fe-44eb-a399-426ebdf540b9, ef74c493-5050-4b88-9be1-92e54c0e34aa, a59dba67-f1e1-4085-943c-c3ded9fc5667, c46a4f2a-d54d-4778-9c2d-c61f41691e34, 68ccf1c5-a309-443a-b04e-69d266cb348f
  * Package Date: 2015-02-04 02:18:43 
  * IP Address: 91.221.59.205
  */


if(!$module->can_i('view','Products') || !$module->can_i('edit','Products')){
    redirect_browser(_BASE_HREF);
}

// hack to add a "group" option to the pagination results.
if(class_exists('module_group',false) && module_product::can_i('edit','Products')){
    module_group::enable_pagination_hook(
        // what fields do we pass to the group module from this customers?
        array(
            'bulk_actions'=>array(
                'delete'=>array(
                    'label'=>'Delete selected products',
                    'type'=>'delete',
                    'callback'=>'module_product::bulk_handle_delete',
                ),
            ),
        )
    );
}

$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : array();
$products = module_product::get_products($search);


$heading = array(
    'title' => 'Products',
    'type' => 'h2',
    'main' => true,
    'button' => array(),
);
if(module_product::can_i('create','Products')) {
    if(class_exists('module_import_export',false)) {
        $link = module_import_export::import_link(
            array(
                'callback'   => 'module_product::handle_import',
                'name'       => 'Products',
                'return_url' => $_SERVER['REQUEST_URI'],
                'fields'     => array(
                    'Product ID'       => 'product_id',
                    'Product Name'     => 'name',
                    'Product Category' => 'category_name',
                    'Hours/Qty'        => 'quantity',
                    'Amount'           => 'amount',
                    'Description'      => 'description',
                ),
            )
        );
        $heading['button'][] = array(
            'title' => "Import Products",
            'type' => 'add',
            'url' => $link,
        );
    }
    $heading['button'][] = array(
        'title' => "Create New Product",
        'type'  => 'add',
        'url'   => module_product::link_open( 'new' ),
    );
}
print_heading($heading);
?>


<form action="" method="post">

<?php

/** START TABLE LAYOUT **/
$table_manager = module_theme::new_table_manager();
$columns = array();
$columns['product_name'] = array(
        'title' => _l('Product Name'),
        'callback' => function($product){
            echo module_product::link_open($product['product_id'],true,$product);
        },
        'cell_class' => 'row_action',
    );
$columns['product_category_name'] = array(
        'title' => _l('Category Name'),
    );
$columns['quantity'] = array(
        'title' => _l('Hours/Quantity'),
    );
$columns['amount'] = array(
        'title' => _l('Amount'),
        'callback' => function($product){
            echo dollar($product['amount']);
        }
    );
if(module_product::can_i('edit','Products')) {
    $columns['bulk_action'] = array(
        'title'    => ' ',
        'callback' => function ( $product ) {
            echo '<input type="checkbox" name="bulk_operation[' . $product['product_id'] . ']" value="yes">';
        }
    );
}
$table_manager->set_id('product_list');
$table_manager->set_columns($columns);
$table_manager->set_rows($products);
$table_manager->pagination = true;
$table_manager->print_table();
/** END TABLE LAYOUT **/

?>
</form>