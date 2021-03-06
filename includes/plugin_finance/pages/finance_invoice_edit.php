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
$transactions = module_finance::get_finances(array('invoice_id'=>$invoice_id));
ob_start();
?>

    <div id="invoice_finances">
        <div class="content_box_wheader">
        <table class="tableclass tableclass_rows tableclass_full">
           <thead>
            <tr class="title">
                <th><?php echo _l('Date'); ?></th>
                <th><?php echo _l('Name'); ?></th>
                <th><?php echo _l('Description'); ?></th>
                <th><?php echo _l('Credit'); ?></th>
                <th><?php echo _l('Debit'); ?></th>
            </tr>
            </thead>
            <tbody>
                <?php
                $c=0;
                foreach($transactions as $transaction){
                    ?>
                    <tr class="<?php echo ($c++%2)?"odd":"even"; ?>">
                        <td class="row_action">
                            <?php echo print_date($transaction['transaction_date']); ?>
                        </td>
                        <td>

                                    <a href="<?php echo $transaction['url'];?>"><?php echo !trim($transaction['name']) ? 'N/A' :    htmlspecialchars($transaction['name']);?></a>
                        </td>
                        <td>
                            <?php echo $transaction['description']; ?>
                        </td>
                        <td>
                            <span class="success_text"><?php echo $transaction['credit'] > 0 ? '+'.dollar($transaction['credit'],true,$transaction['currency_id']) : '';?></span>
                        </td>
                        <td>
                            <span class="error_text"><?php echo $transaction['debit'] > 0 ? '-'.dollar($transaction['debit'],true,$transaction['currency_id']) : '';?></span>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
            </div>
    </div>

<?php

$fieldset_data = array(
    'heading' =>  array(
        'title'=>'Invoice Finances:',
        'type'=>'h3',
        'button'=>array(
            'title'=>_l('Add New'),
            'url'=>module_finance::link_open('new').'&from_invoice_id='.$invoice_id,
        )
    ),
    'elements_before' => ob_get_clean(),
);
echo module_form::generate_fieldset($fieldset_data);

