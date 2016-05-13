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


if(!module_config::can_i('view','Settings') || !module_faq::can_i('edit','FAQ')){
    redirect_browser(_BASE_HREF);
}

$faqs = module_faq::get_faqs();

if(isset($_REQUEST['faq_id']) && $_REQUEST['faq_id']){
    $show_other_settings=false;
    $faq_id = (int)$_REQUEST['faq_id'];
    if($faq_id > 0){
        $faq = module_faq::get_faq($faq_id);
    }else{
        $faq = array();
    }
    if(!$faq){
        $faq = array(
            'question' => '',
            'answer' => '',
            'faq_product_ids' => array(),
        );
    }
	?>


	<form action="" method="post">
	    <input type="hidden" name="_process" value="save_faq">
	    <input type="hidden" name="faq_id" value="<?php echo $faq_id; ?>" />

		<?php

		$fieldset_data = array(
		    'heading' => array(
		        'type' => 'h3',
		        'title' => 'Edit FAQ',
		    ),
		    'class' => 'tableclass tableclass_form tableclass_full',
		    'elements' => array(),
		);
		$fieldset_data['elements'][] = array(
		    'title' => 'Question',
		    'fields' => array(
		        array(
		            'type' => 'text',
		            'name' => 'question',
		            'value' => $faq['question'],
		        ),
		    )
		);
		$fieldset_data['elements'][] = array(
		    'title' => 'Answer',
		    'fields' => array(
		        array(
		            'type' => 'wysiwyg',
		            'name' => 'answer',
		            'value' => $faq['answer'],
		        ),
		    )
		);
		$fieldset_data['elements'][] = array(
		    'title' => 'Linked Products',
		    'fields' => array(
		        function() use($faq){
			        $default_types = module_ticket::get_types();
	                foreach(module_faq::get_faq_products_rel() as $faq_product_id => $product_name){
		                $faq_product = module_faq::get_faq_product($faq_product_id);
		                ?>
		                <div>
		                    <input type="checkbox" name="faq_product_ids[]" value="<?php echo $faq_product_id;?>" id="multi_<?php echo $faq_product_id;?>" <?php echo in_array($faq_product_id,$faq['faq_product_ids']) ? ' checked' : '';?>>
		                    <label for="multi_<?php echo $faq_product_id;?>"><?php echo htmlspecialchars($product_name);?> (<?php echo ($faq_product['default_type_id']) ? $default_types[$faq_product['default_type_id']] : _l('N/A');?>)</label>
		                    <a href="<?php echo module_faq::link_open_faq_product($faq_product_id,false);?>">(edit)</a>
		                    <br/>
		                </div>
	                <?php } ?>
	                <div>
	                    <input type="checkbox" name="new_product_go" value="1"> <input type="text" name="new_product_name"> (new)
	                </div>
			        <?php
		        }
		    )
		);
		if($faq_id>0){

			$fieldset_data['elements'][] = array(
			    'title' => 'Public Link',
			    'fields' => array(
			        array(
			            'type' => 'html',
			            'value' => '<a href="'.module_faq::link_open_public($faq_id).'" target="_blank">'. _l('Open') .'</a>',
			        ),
			    )
			);
		}

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
		            'ignore' => !(int)$faq_id,
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

	<?php
}else{

	$header = array(
	    'title' => _l('FAQs'),
	    'type' => 'h2',
	    'main' => true,
	    'button' => array(),
	);
	if(module_faq::can_i('create','FAQ')){
	    $header['button'] = array(
	        'url' => module_faq::link_open('new'),
	        'title' => _l('Add New FAQ'),
	        'type' => 'add',
	    );
	}
	print_heading($header);

	$products = module_faq::get_faq_products_rel();

	/** START TABLE LAYOUT **/
	$table_manager = module_theme::new_table_manager();
	$columns = array();
	$columns['question'] = array(
	        'title' => _l('Question'),
	        'callback' => function($faq){
	            echo module_faq::link_open($faq['faq_id'],true);
	        },
	        'cell_class' => 'row_action',
	    );
	$columns['linked_products'] = array(
	        'title' => _l('Linked FAQ Products'),
	        'callback' => function($faq){
		        $faq = module_faq::get_faq($faq['faq_id']);
	            foreach($faq['faq_product_ids'] as $faq_product_id){
                    echo module_faq::link_open_faq_product($faq_product_id,true)." ";
                }
	        },
	    );
	$table_manager->set_id('faq_list');
	$table_manager->set_columns($columns);
	$table_manager->set_rows($faqs);
	$table_manager->pagination = true;
	$table_manager->print_table();
	/** END TABLE LAYOUT **/

}
