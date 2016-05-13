<?php 
/** 

  */

$locked = false;

$job_id = 0;
if( isset($_REQUEST['job_id']) && (int)$_REQUEST['job_id'] > 0 ) {
	$new_job_id =  (int)$_REQUEST['job_id'];	
}

$customer_id = 0;
if( isset($_REQUEST['customer_id']) && (int)$_REQUEST['customer_id'] > 0 ) {
	$new_customer_id =  (int)$_REQUEST['customer_id'];	
}

$linked_staff_members = array();
foreach(module_user::get_staff_members() as $staff){
    $linked_staff_members[$staff['user_id']] = $staff['name'].' '.$staff['last_name'];
}

$tasks_list = array();
$tasks_jobs = array();

$all_jobs = module_job::get_jobs();



foreach($all_jobs as $job){
	$tasks = module_job::get_tasks($job['job_id']);
	foreach($tasks as $task){ 
		$tasks_list[$task['task_id']] = $task['description'];
		$tasks_jobs[$task['task_id']] = $task['job_id'];
	}
}


$expense_id = (int)$_REQUEST['expense_id'];
$expense = module_expense::get_expense($expense_id);
if(!isset($expense['expense_id']) || $expense['expense_id'] != $expense_id){
	$expense_id=0;	
}

if($expense_id <= 0){
	$expense['customer_id']  = $new_customer_id;
	$expense['job_id']  = $new_job_id;

}else{
   
    $module->page_title = $expense['name'];
}

// check permissions.
if(class_exists('module_security',false)){
    if( $expense_id>0 && $expense['expense_id']==$expense_id ){
        // if they are not allowed to "edit" a page, but the "view" permission exists
        // then we automatically grab the page and regex all the crap out of it that they are not allowed to change
        // eg: form elements, submit buttons, etc..
        module_security::check_page(array(
            'category' => 'Expense',
            'page_name' => 'expense',
            'module' => 'expense',
            'feature' => 'edit',
        ));
    }else{
        module_security::check_page(array(
            'category' => 'Expense',
            'page_name' => 'expense',
            'module' => 'expense',
            'feature' => 'create',
        ));
    }
    module_security::sanatise_data('expense',$expense);
}

 echo "<pre>";
 
//print_r($expense);
 echo "</pre>";

?>

<form action="" method="post">

      <?php
module_form::prevent_exit(array(
    'valid_exits' => array(
        // selectors for the valid ways to exit this form.
        '.submit_button',
    ))
);



?>

    
	<input type="hidden" name="_process" value="save_expense" />
	<input type="hidden" name="expense_id" value="<?php echo $expense_id; ?>" />

<?php

	$fieldset_data = array(
        'heading' => array(
            'title' => _l('Edit Expense'),
            'type' => 'h2',
            'main' => true,
        ),
        'elements' => array(),
        'extra_settings' => array(
            'owner_table' => 'expense',
            'owner_key' => 'expense_id',
            'owner_id' => isset($expense['expense_id']) ? $expense['expense_id'] : false,
            'layout' => 'table_row',
            'allow_new' => module_expense::can_i('create','expense'),
            'allow_edit' => module_expense::can_i('edit','expense'),
        ),
    );


	$fieldset_data['elements'][] = array(
	    'title'  => 'Date',
	    'field' => array(
			'type' => 'text',
		    'name' => 'transaction_date',
			'id'   => 'transaction_date',
			'class'   => 'date_field',
		    'value' => print_date($expense['transaction_date']),
			
	    ),
    );	
	$fieldset_data['elements'][] = array(
	    'title'  => 'Name',
	    'field' => array(
		    'type' => 'text',
		    'name' => 'name',
		    'value' => $expense['name'],
	    ),
    );
	$fieldset_data['elements'][] = array(
	    'title'  => 'Description',
	    'field' => array(
		    'type' => 'textarea',
		    'name' => 'description',
		    'value' => $expense['description'],
		    //'style' => 'width:350px; height: 100px;', // todo: move this to stylesheet
	    ),
    );
	

	$element_fields = array(
		'type' => 'currency',
		'currency_id' => $expense['currency_id'],
		'name' => 'amount',
		'id' => 'expense_total_amount',
		'value' => number_out($expense['amount']),
	);
	
	$fieldset_data['elements'][] = array(
	    'title'  => 'Total Amount',
	    'field' => $element_fields,
    );


$fieldset_data['elements'][] = array(
    'title' => 'Generate name',
    'field' => array(
        'type' => 'checkbox',
        'name' => 'generate_name',
        'value' => 0,
        'checked' => 0,
        'id' => 'generate-name',
    ),
);

$fieldset_data['elements'][] = array(
   
    'title' => 'Status',
    'field' => array(
        'type' => 'select',
        'name' => 'status',
        'value' => $expense['status'],
        'options' => module_file::get_stat(),
        'allow_new' => false,
        'id' => 'status-file',
    ),
);

$fieldset_data['elements'][] = array(
    'title' => 'Kilinikum',
    'row_title_class' => 'show-generate-name',
    'row_data_class' => 'show-generate-name',
    'field' => array(
        'type' => 'select',
        'name' => 'clinic',
        'value' => $expense['clinic'],
        'options' => module_file::get_clinics(),
        'allow_new' => true,
    ),
);

$fieldset_data['elements'][] = array(
    'title' => 'Arzt',
    'row_title_class' => 'show-generate-name',
    'row_data_class' => 'show-generate-name',
    'field' => array(
        'type' => 'select',
        'name' => 'arzt',
        'value' => $expense['arzt'],
        'options' => module_file::get_arzts(),
        'allow_new' => true,
    ),
);

$fieldset_data['elements'][] = array(
    'title' => 'Art der Leistung',
    'row_title_class' => 'show-generate-name',
    'row_data_class' => 'show-generate-name',
    'field' => array(
        'type' => 'select',
        'name' => 'art_der_leistung',
        'value' => $expense['art_der_leistung'],
        'options' => module_file::get_art_der_leistungs(),
        'allow_new' => true,
    ),
);



$fieldset_data['elements'][] = array(
    'title' => 'Rechnungsnummer',
    'row_title_class' => 'show-invoice-name',
    'row_data_class' => 'show-invoice-name',
    'field' => array(
        'type' => 'text',
        'name' => 'invoice_name',
        'value' => $expense['invoice_name'],
    ),
);

$fieldset_data['elements'][] = array(
    'title' => _l('Rechnungsdatum'),
    'row_title_class' => 'show-invoice-name',
    'row_data_class' => 'show-invoice-name',
    'field' => array(
        'class' => 'date_invoice',
        'type' => 'date',
        'name' => 'date_invoice',
        'value' => print_date($expense['date_invoice']),

    ),

);




$fieldset_data['elements'][] = array(
	    /*'title'  => 'Currency',
	    'field' => array(
		    'type' => 'select',
		    'name' => 'currency_id',
		    'options' => get_multiple('currency','','currency_id'),
		    'options_array_id' => 'code',
		    'value' => $expense['currency_id'],
	    ),*/
    );
	
	
	if(class_exists('module_company',false) && module_company::can_i('view','Company') && module_company::is_enabled()) {
		$companys     = module_company::get_companys();
		$companys_rel = array();
		foreach ( $companys as $company ) {
			$companys_rel[ $company['company_id'] ] = $company['name'];
		}
		$fieldset_data['elements'][] = array(
		    'title'  => 'Company',
		    'field' => array(
				'type'    => 'select',
				'name'    => 'company_id',
				'value'   => isset( $expense['company_id'] ) ? $expense['company_id'] : '',
				'options' => $companys_rel,
				'blank'   => _l( ' - Default - ' ),
				'help'    => 'Link this expense item with an individual company. It is better to select a Customer below and assign the Customer to a Company.',
		    ),
	    );
	}
	
	
	if( module_job::can_i('view','Jobs') ){
		$fieldset_data['elements'][] = array(
		    'title'  => 'Linked Customer',
		    'fields' => array(
			    function() use (&$expense,$locked){
				    echo print_select_box(module_customer::get_customers(),'customer_id',$expense['customer_id'],'',_l(' - None - '),'customer_name'); ?>
                        <script type="text/javascript">
                        $(function(){
                            $('#customer_id').change(function(){
                                // change our customer id.
                                var new_customer_id = $(this).val();
                                $.ajax({
                                    type: 'POST',
                                    url: '<?php echo module_job::link_generate(false);?>',
                                    data: {
                                        '_process': 'ajax_job_list',
                                        'customer_id': new_customer_id
                                    },
                                    dataType: 'json',
                                    success: function(newOptions){
                                        $('#job_id').find('option:gt(0)').remove();
                                        $.each(newOptions, function(value, key) {
                                            $('#job_id').append($("<option></option>")
                                                .attr("value", value).text(key));
                                        });
                                    },
                                    fail: function(){
                                        alert('Changing customer failed, please refresh and try again.');
                                    }
                                });
                            });
                        });
                    </script>
	                <?php
			    },
		    ),
	    );
		
	
		
		$fieldset_data['elements'][] = array(
		    'title'  => 'Linked Job',
		    'fields' => array(
			    function() use (&$expense,$locked){
				    $d = array();
                    if($expense['customer_id']){
                        $jobs = module_job::get_jobs(array('customer_id'=>$expense['customer_id']));
                        foreach($jobs as $job){
                            $d[$job['job_id']] = $job['name'];
                        }
                    }

                    echo print_select_box($d, 'job_id', isset($expense['job_id']) ? $expense['job_id'] : 0, '', _l(' - None - '));

				},
                function() use (&$expense){
                    if($expense['job_id']){
                        echo ' ';
                        echo '<a href="'.module_job::link_open($expense['job_id'],false).'">'._l('Open Job &raquo;').'</a>';
                    }
                }
		    ),
	    );		

		$fieldset_data['elements'][] = array(
		    'title' => 'Linked Task',
            'fields' => array(
                array(
                    'type' => 'select',
                    'name' => 'task_id',
					'id' => 'task_id',
                    'options' => $tasks_list,
                    'value' => isset($expense['task_id']) ? $expense['task_id'] : 0,
                ),
			),
		);
	}
	
	
	if(count($linked_staff_members)){
		$fieldset_data['elements'][] = array(
		    'title'  => 'Linked Staff',
		    'fields' => array(
			    function() use (&$expense,$locked,$linked_staff_members){
                    echo print_select_box($linked_staff_members, 'staff_id', isset($expense['staff_id']) ? $expense['staff_id'] : 0, '', _l(' - None - '));

			    },
		    ),
	    );
	}




// files
if(class_exists('module_file',false)) {
    ob_start();
    $files = module_file::get_files( array( 'expense_id' => $expense['expense_id'] ), true );
    if ( count( $files ) > 0 ) {
        ?>
        <a href="<?php

        echo module_file::link_generate( false, array(
            'arguments' => array(
                'expense_id' => $expense['expense_id'],
            ),
            'data'      => array(
                // we do this to stop the 'customer_id' coming through
                // so we link to the full job page, not the customer job page.

                'expense_id' => $expense['expense_id'],
            ),
        ) );?>"><?php echo _l( 'View all %d files in this expense', count( $files ) ); ?></a>
    <?php
    } else {
        echo _l( "This expense has %d files", count( $files ) );
    }
    echo '<br/>';
    ?>
    <a href="<?php echo module_file::link_generate( 'new', array(
        'arguments' => array(
            'expense_id' => $expense['expense_id'],
            'job_id' => $expense['job_id'],
            'status' => $expense['status'],
            'clinic' => $expense['clinic'],
            'arzt' => $expense['arzt'],
            'art_der_leistung' => $expense['art_der_leistung'],
            'date_invoice' => $expense['date_invoice'],
            'invoice_name' => $expense['invoice_name'],
        )
    ) ); ?>"><?php _e( 'Add New File' ); ?></a>
    <?php
    $fieldset_data['elements']['files'] = array(
        'title'  => 'Files',
        'fields' => array(
            ob_get_clean(),
        ),
    );
}



	echo module_form::generate_fieldset($fieldset_data);
    unset($fieldset_data);

    $form_actions = array(
        'class' => 'action_bar action_bar_left',
        'elements' => array(
	        array(
                'type' => 'save_button',
                'name' => 'butt_save_return',
                'value' => _l('Save and Return'),
            ),
            array(
                'type' => 'save_button',
                'name' => 'butt_save',
                'value' => _l('Save'),
            ),
        ),
    );

    if((int)$expense_id>0){
        $form_actions['elements'][] = array(
            'type' => 'delete_button',
            'name' => 'butt_del',
            'value' => _l('Delete'),
            'onclick' => "return confirm('". _l('Really delete this record?')."');",
        );
    }
    if(count($linked_expenses)){ // || count($linked_invoice_payments))
        $form_actions['elements'][] = array(
            'type' => 'submit',
            'name' => 'butt_unlink',
            'value' => _l('Unlink'),
        );
    }
    $form_actions['elements'][] = array(
            'type' => 'button',
            'name' => 'cancel',
            'value' => _l('Cancel'),
            'class' => 'submit_button',
            'onclick' => "window.location.href='".module_expense::link_generate(false)."';",
        );
    echo module_form::generate_form_actions($form_actions);

?>
</form>
<script type="text/javascript">
	
	var tasks_jobs = <?= json_encode($tasks_jobs);?>;
	var tasks_list  = $('#task_id').html();
	var first_task_element = $('#task_id option:first')
	
	function set_tasks() {
		$('#task_id').html(tasks_list);		
		$('#task_id option').each(function() {
			var current_job_id = $('#job_id option:selected').val();
			if(tasks_jobs[ $(this).val() ] != current_job_id ) $(this).remove();
		});
		$("#task_id").prepend( first_task_element );
	
	}	

	set_tasks();
        
	$('#job_id').change(function() {		
		set_tasks();
		$('#task_id [value = ""]').attr("selected", "selected");
	});
        


</script>



