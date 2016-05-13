<?php 
/** 

  */ 
 
  
print_heading(array(
    'title' => _l('Expenses'),
    'type' => 'h2',
    'main' => true,
    'button' => array(
        'title' => _l('Add New'),
        'url' => module_expense::link_generate('new'),
        'type' => 'add',
    )
));

$params = array();
if(isset($_REQUEST['customer_id']) && $_REQUEST['customer_id'] && $_REQUEST['customer_id']!='new'){
	$params['customer_id'] = $_REQUEST['customer_id'];
}
$expenses = module_expense::get_expenses($params);

if(class_exists('module_table_sort',false)){
   module_table_sort::enable_pagination_hook(
        array(
            //'table_id' => 'expense_list',
            'sortable'=>array(
                // these are the "ID" values of the <th> in our table.
                // we use jquery to add the up/down arrows after page loads.
                'sort_date' => array(
                    'field' => 'transaction_date',
                    'current' => 2, // 1 asc, 2 desc
                ),
                'sort_name' => array(
                    'field' => 'name',
                ),
                'sort_amount' => array(
                    'field' => 'amount',
                ),
			),
        )
    );
}

 

 

$table_manager = module_theme::new_table_manager();
$columns = array();

$columns['sort_date'] = array(
    'title' => 'Date',
    'callback' => function($expense){
	    if(!isset($expense['transaction_date']))return false;
		echo isset($expense['transaction_date']) && $expense['transaction_date'] ?  print_date($expense['transaction_date']) : '';
    },
);
$columns['sort_name'] = array(
    'title' => 'Name',
    'callback' => function($expense){
	    if(!isset($expense['transaction_date']))return false;
	    ?> <a href="<?php echo module_expense::link_generate($expense['expense_id']);?>">
				<?php echo !trim($expense['name']) ? 'N/A' :    htmlspecialchars($expense['name']);?>
			</a>
		<?php
    },
);

$columns['expense_customer'] = array(
    'title' => 'Customer',
    'callback' => function($expense){
	    if(!isset($expense['transaction_date']))return false;
		echo isset($expense['customer_id']) && $expense['customer_id'] ? module_customer::link_open($expense['customer_id'],true) : '';
    },
);
$columns['sort_amount'] = array(
	'title'    => 'Amount',
	'callback' => function ( $expense ) {
	    if(!isset($expense['transaction_date']))return false;
		?> <span class="error_text"><?php echo $expense['amount'] > 0 ? '-'.dollar($expense['amount'],true,$expense['currency_id']) : ''; ?></span> <?php
	},
);

$table_manager->set_columns($columns);

$table_manager->set_rows($expenses);

$table_manager->pagination = true;
$table_manager->print_table();
