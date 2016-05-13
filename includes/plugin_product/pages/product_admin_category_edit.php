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

// check permissions.
if(class_exists('module_security',false)){
    if($product_category_id>0 && $product_category['product_category_id']==$product_category_id){
        // if they are not allowed to "edit" a page, but the "view" permission exists
        // then we automatically grab the page and regex all the crap out of it that they are not allowed to change
        // eg: form elements, submit buttons, etc..
		module_security::check_page(array(
            'category' => 'Product',
            'page_name' => 'Products',
            'module' => 'product',
            'feature' => 'Edit',
		));
    }else{
		module_security::check_page(array(
			'category' => 'Product',
            'page_name' => 'Products',
            'module' => 'product',
            'feature' => 'Create',
		));
	}
	module_security::sanatise_data('product',$product_category);
}

?>
<form action="" method="post" id="product_category_form">
	<input type="hidden" name="_process" value="save_product_category" />
	<input type="hidden" name="product_category_id" value="<?php echo (int)$product_category_id; ?>" />

    <?php
    module_form::set_required(array(
        'fields' => array(
            'name' => 'Name',
        ))
    );
    module_form::prevent_exit(array(
        'valid_exits' => array(
            // selectors for the valid ways to exit this form.
            '.submit_button',
        ))
    );

    $fieldset_data = array(
	    'heading' => array(
	        'type' => 'h3',
	        'title' => 'Product Information',
	    ),
	    'class' => 'tableclass tableclass_form tableclass_full',
	    'elements' => array(),
	);
	$fieldset_data['elements'][] = array(
	    'title' => 'Name',
	    'fields' => array(
	        array(
	            'type' => 'text',
	            'name' => 'product_category_name',
	            'value' => $product_category['product_category_name'],
	        ),
	    )
	);

	echo module_form::generate_fieldset($fieldset_data);
	unset($fieldset_data);


    $form_actions = array(
        'class' => 'action_bar action_bar_center',
        'elements' => array(
            array(
                'type' => 'save_button',
                'name' => 'butt_save',
                'value' => _l('Save'),
            ),
            array(
	            'ignore' => !(int)$product_category_id,
                'type' => 'delete_button',
                'name' => 'butt_del',
                'value' => _l('Delete'),
            ),
            array(
                'type' => 'button',
                'name' => 'cancel',
                'value' => _l('Cancel'),
                'class' => 'submit_button',
                'onclick' => "window.location.href='".$module->link_open(false)."';",
            ),
        ),
    );
    echo module_form::generate_form_actions($form_actions);
    ?>

</form>

