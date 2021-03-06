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

// done in product_admin
//$product_id = (int)$_REQUEST['product_id'];
//$product = array();
//$product = module_product::get_product($product_id);

// check permissions.
if(class_exists('module_security',false)){
    if($product_id>0 && $product['product_id']==$product_id){
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
	module_security::sanatise_data('product',$product);
}

?>
<form action="" method="post" id="product_form">
	<input type="hidden" name="_process" value="save_product" />
                <!--input type="hidden" name="default_task_type" value="1" /-->
	<input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />

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
	            'name' => 'name',
	            'value' => $product['name'],
	        ),
	    )
	);
	$fieldset_data['elements'][] = array(
	    'title' => 'Category',
	    'fields' => array(
	        array(
	            'type' => 'select',
	            'name' => 'product_category_id',
		        'options' => module_product::get_product_categories(),
		        'options_array_id' => 'product_category_name',
	            'value' => $product['product_category_id'],
	        ),
	    )
	);
	$fieldset_data['elements'][] = array(
	    'title' => 'Hours/Quantity',
	    'fields' => array(
	        array(
	            'type' => 'text',
	            'name' => 'quantity',
	            'value' => $product['quantity'],
	        ),
	    )
	);
	$fieldset_data['elements'][] = array(
	    'title' => 'Amount',
	    'fields' => array(
	        array(
	            'type' => 'currency',
	            'name' => 'amount',
	            'value' => $product['amount'],
	        ),
	    )
	);
	$fieldset_data['elements'][] = array(
	    'title' => 'Description',
	    'fields' => array(
	        array(
	            'type' => 'textarea',
	            'name' => 'description',
	            'value' => $product['description'],
	        ),
	    )
	);
    $types = module_job::get_task_types();
    $types['-1'] = _l('Default');
	$fieldset_data['elements'][] = array(
	    'title' => 'Task Type',
	    'fields' => array(
	        array(
	            'type' => 'select',
                            'name' => 'default_task_type',
		        'options' => $types,
	            'value' => isset($product['default_task_type']) ? $product['default_task_type'] : 1,
		        'blank' => false,



            ),
	    )
	);
	$fieldset_data['elements'][] = array(
	    'title' => 'Task Art',
	    'fields' => array(
	        array(
	            'type' => 'select',
	            'name' => 'task_art',
		        'options' => array('M'=> 'M','D' => 'D' ,'I' => 'I', 'T' => 'T'),
	            'value' => isset($product['task_art']) ? $product['task_art'] : 'M',
		        'blank' => false,
                'help' => 'M - medizinische Leistung; D - Transfer; I - Dolmetscher; T - Übersetzer. Durch dieses Attribut werden Ausgaben sowie der Inhalt von Formularen gesteuert',

            ),
	    )
	);
	$fieldset_data['elements'][] = array(
	    'title' => 'Billable',
	    'fields' => array(
	        array(
	            'type' => 'checkbox',
	            'name' => 'billable',
	            'value' => isset($product['billable']) ? $product['billable'] : 1,
                'help' => 'Beim aktivierten Checkbox wird diese Leistung in die Rechnung gestellt. Diese Einstellung kann auf Positionsebene geändert werden.',

            ),
	    )
	);
	$fieldset_data['elements'][] = array(
	    'title' => 'Taxable',
	    'fields' => array(
	        array(
	            'type' => 'checkbox',
	            'name' => 'taxable',
	            'value' => isset($product['taxable']) ? $product['taxable'] : 1,
                'help' => 'Beim aktivierten Checkbox wird diese Leistung mit MwSt berechnet. Diese Einstellung kann auf Positionsebene geändert werden.',

            ),
	    )
	);

	$fieldset_data['elements'][] = array(
	    'title' => 'Expense',
	    'fields' => array(
	        array(
	            'type' => 'checkbox',
	            'name' => 'expense',
	            'value' => isset($product['expense']) ? $product['expense'] : 1,
                'help' => 'Beim aktivierten Checkbox wird für diese Leistung eine (Auto-) Ausgabe-Position generiert. Abhängig vom Serviceart wird die Ausgabe, nach dem die Stunden bzw. Seiten eingetragen sind, automatisch mit Verrechnungsatz multiplizert. Die Provision wird mit dem Wert 0 generiert. Sollte der Kunde vom Vermittler kommen, wird der Auftragswert mit dem Provi-Anteil aus dem Vermittlerstammsatz multipliziert. Diese Einstellung kann auf Positionsebene NICHT geändert werden.',

            ),
	    )
	);

	$fieldset_data['elements'][] = array(
	    'title' => 'Fee',
	    'fields' => array(
	        array(
	            'type' => 'checkbox',
	            'name' => 'fee',
	            'value' => isset($product['fee']) ? $product['fee'] : 1,
                'help' => 'Beim aktivierten Checkbox wird für diese Leistung eine (Auto-) Einkommen-Position generiert. Diese Einstellung kann auf Positionsebene NICHT geändert werden.',

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
	            'ignore' => !(int)$product_id,
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

