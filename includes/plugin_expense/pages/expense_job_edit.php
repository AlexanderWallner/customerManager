<?php 
/** 

  */
  
if( isset($_REQUEST['job_id']) && (int)$_REQUEST['job_id'] > 0 ) {
	
	$job_expenses = module_expense::get_expenses(array('job_id'=>(int)$_REQUEST['job_id']));
	//$job_expenses = module_expense::get_expenses();

	ob_start();
	?>

	<div class="content_box_wheader">
		<table class="tableclass tableclass_rows tableclass_full">
		   <thead>
			<tr class="title">
				<th><?php echo _l('Date'); ?></th>
				<th><?php echo _l('Name'); ?></th>
				<th><?php echo _l('Description'); ?></th>
				<th><?php echo _l('Amount'); ?></th>
			</tr>
			</thead>
			<tbody>
				<?php
				$c=0;
				foreach($job_expenses as $job_expense){
					?>
					<tr class="<?php echo ($c++%2)?"odd":"even"; ?>">
						<td class="row_action">
							<?php echo print_date($job_expense['transaction_date']); ?>
						</td>
						<td>
							<a href="<?php echo module_expense::link_generate($job_expense['expense_id']);?>"><?php echo !trim($job_expense['name']) ? 'N/A' :    htmlspecialchars($job_expense['name']);?></a>
						</td>
						<td>
							<?php echo $job_expense['description']; ?>
						</td>
						<td>
							<span class="error_text"><?php echo '-'.dollar($job_expense['amount'],true,$job_expense['currency_id']) ;?></span>
						</td>

				<?php } ?>
			</tbody>
		</table>
		<div style="text-align:right;">
					Total expenses: <span class="error_text"><?php echo "-".$job_profit['expense']." €"; ?></span>
        </div>
	</div>
	<?php

	$fieldset_data = array(
		'heading' =>   array(
				'title'=>'Order Expenses:',
				'type'=>'h3',
			),
		'elements_before' => ob_get_clean(),
	);



	//if( module_expense::can_i('Create','expense') ){
		$fieldset_data['heading']['button']=array(
					array(
						'title'=>_l('Create New Expense'),
						'url'=> module_expense::link_generate('new')."&job_id=".(int)$_REQUEST['job_id']."&customer_id=".(int)$_REQUEST['customer_id'],
						'id'=>'job_generate_expense_button',
					),
					array(
						'title'=>_l('Refresh expenses'),
						'url'=> module_job::link_open($job_id)."&_process=refresh_auto_expenses"."&job_id=".(int)$_REQUEST['job_id']."&customer_id=".(int)$_REQUEST['customer_id'],
						'id'=>'job_generate_expense_button',
					),
		);
	//}
	echo module_form::generate_fieldset($fieldset_data);
}

