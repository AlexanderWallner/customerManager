<?php 
/** 

  */ 
 
  
print_heading(array(
    'title' => _l('profits'),
    'type' => 'h2',
    'main' => true,

));

$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : array();
if( !isset($search['date_start_after']) ){
    $search['date_start_after'] = print_date(strtotime('-7 days'));
}

$params = array();
$jobs = array();
if(isset($_REQUEST['job_id']) && $_REQUEST['job_id'] && $_REQUEST['job_id']!='new'){
	$jobs[] = module_job::get_job( $_REQUEST['job_id'] );
} else {
	$jobs = module_job::get_jobs($search); 
}



foreach($jobs as $job) {
	module_job::set_auto_expenses($job['job_id']);
	module_job::set_auto_invoices($job['job_id']);
	$profits[$job['name']] = module_profit::get_profit($job['job_id']);
	$profits[$job['name']]['job_id'] = $job['job_id'];

}

?>

<form action="" method="post" id="profit_form">

    <?php
    
    $search_bar = array(
		'elements' => array(
            'due_date' => array(
                'title' => _l('Date:'),
                'fields' => array(
                    array(
                        'type' => 'date',
                        'name' => 'search[date_start_after]',
						'style' => 'width: 100px;',
                        'value' => isset($search['date_start_after'])?$search['date_start_after']:'',
                    ),
                    _l('to'),
                    array(
                        'type' => 'date',
                        'name' => 'search[date_start_before]',
						'style' => 'width: 100px;',
                        'value' => isset($search['date_start_before'])?$search['date_start_before']:'',
                    ),
                )
            ),
		)
        
    );

    echo module_form::search_bar($search_bar); ?>


</form>

 <table class="tableclass tableclass_rows tableclass_full">
   <thead>
	<tr class="title">
		<th><?php echo _l('Order'); ?></th>
		<th><?php echo _l('Invoice'); ?></th>
		<th><?php echo _l('Expense'); ?></th>
		<th><?php echo _l('Profit'); ?></th>
	</tr>
	</thead>
	<tbody>
		<?php
		$c=0;
		foreach($profits as $job_name=>$profit){
			?>
			<tr class="<?php echo ($c++%2)?"odd":"even"; ?>">
				<td class="row_action">
					<?php echo module_job::link_open($profit['job_id'],true); ?>
				</td>
				<td>
					<span class="success_text"><?php echo "+".$profit['invoice']." €"; ?></span>
				</td>
				<td>
					<span class="error_text"><?php echo "-".$profit['expense']." €"; ?></span>
				</td>
				<td>
					<span class="<? echo $profit['total'] > 0 ? "success_text" : "error_text" ?>"><?php echo $profit['total']." €"; ?></span>
				</td>				
			</tr>
		<?php } ?>
	</tbody>
</table>
