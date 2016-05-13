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

// UPDATE::: to edit the "quote task list" please go to Settings > Templates and look for the new "quote_task_list" entry.

if(!isset($quote)&&isset($quote_data))$quote = $quote_data;


ob_start();
?>
<table cellpadding="4" cellspacing="0" style="width:100%" class="table tableclass tableclass_rows">

        <tr>
            {QUOTE_SUMMARY}
        </tr>

</table>

<?php
module_template::init_template('quote_summary',ob_get_clean(),'Used when displaying the quote tasks.','code');
$t = false;
if(isset($quote_template_suffix) && strlen($quote_template_suffix) > 0){
	$t = module_template::get_template_by_key('quote_summary'.$quote_template_suffix);
	if(!$t->template_id){
		$t = false;
	}
}
if(!$t){
	$t = module_template::get_template_by_key('quote_summary');
}




ob_start();
/* copied from quote_admin_edit.php
todo: move this into a separate method or something so they can both share updates easier
*/
$rows = array();
// we hide quote tax if there is none
$hide_tax = true;
foreach($quote['taxes'] as $quote_tax){
    if($quote_tax['percent']>0){
        $hide_tax=false;
        break;
    }
}
if($quote['total_sub_amount_unbillable']){
    $rows[]=array(
        'label'=>_l('Sub Total:'),
        'value'=>'<span class="currency">'.dollar($quote['total_sub_amount']+$quote['total_sub_amount_unbillable'],true,$quote['currency_id']).'</span>'
    );
    $rows[]=array(
        'label'=>_l('Unbillable:'),
        'value'=>'<span class="currency">'.dollar($quote['total_sub_amount_unbillable'],true,$quote['currency_id']).'</span>'
    );
}
if(isset($quote['discount_type'])){
    if($quote['discount_type']==_DISCOUNT_TYPE_BEFORE_TAX){
        $rows[]=array(
            'label'=>_l('Sub Total:'),
            'value'=>'<span class="currency">'.dollar($quote['total_sub_amount']+$quote['discount_amount'],true,$quote['currency_id']).'</span>'
        );
        if($quote['discount_amount']>0){
            $rows[]=array(
                'label'=> htmlspecialchars(_l($quote['discount_description'])),
                'value'=> '<span class="currency">'.dollar($quote['discount_amount'],true,$quote['currency_id']).'</span>'
            );
           /* $rows[]=array(
                'label'=>_l('Summe Total:'),
                'value'=>'<span class="currency">'.dollar($quote['total_sub_amount'],true,$quote['currency_id']).'</span>'
            );*/
        }
        if(!$hide_tax){
            foreach($quote['taxes'] as $quote_tax){
                $rows[]=array(
                    'label'=>$quote_tax['name'].' '.number_out($quote_tax['percent'], module_config::c('tax_trim_decimal', 1), module_config::c('tax_decimal_places',module_config::c('currency_decimal_places',2))).'%',
                    'value'=>'<span class="currency">'.dollar($quote_tax['amount'],true,$quote['currency_id']).'</span>',
                    'extra'=>$quote_tax['name'] . ' = '.$quote_tax['rate'].'%',
                );
            }
        }

    }else if($quote['discount_type']==_DISCOUNT_TYPE_AFTER_TAX){
        $rows[]=array(
            'label'=>_l('Sub Total:'),
            'value'=>'<span class="currency">'.dollar($quote['total_sub_amount'],true,$quote['currency_id']).'</span>'
        );
        if(!$hide_tax){
            foreach($quote['taxes'] as $quote_tax){
                $rows[]=array(
                    'label'=>$quote_tax['name'].' '.number_out($quote_tax['percent'], module_config::c('tax_trim_decimal', 1), module_config::c('tax_decimal_places',module_config::c('currency_decimal_places',2))).'%',
                    'value'=>'<span class="currency">'.dollar($quote_tax['amount'],true,$quote['currency_id']).'</span>',
                    'extra'=>$quote_tax['name'] . ' = '.$quote_tax['percent'].'%',
               );
            }
            /*$rows[]=array(
                'label'=>_l('Summe Total:'),
                'value'=>'<span class="currency">'.dollar($quote['total_sub_amount']+$quote['total_tax'],true,$quote['currency_id']).'</span>',
            );*/
        }
        if($quote['discount_amount']>0){ //if(($discounts_allowed || $quote['discount_amount']>0) &&  (!($quote_locked && module_security::is_page_editable()) || $quote['discount_amount']>0)){
            $rows[]=array(
                'label'=> htmlspecialchars(_l($quote['discount_description'])),
                'value'=> '<span class="currency">'.dollar($quote['discount_amount'],true,$quote['currency_id']).'</span>'
            );
        }
    }
}else{
    if(!$hide_tax){
        $rows[]=array(
            'label'=>_l('Sub Total:'),
            'value'=>'<span class="currency">'.dollar($quote['total_sub_amount'],true,$quote['currency_id']).'</span>',
        );
        foreach($quote['taxes'] as $quote_tax){
            $rows[]=array(
                'label'=>$quote_tax['name'].' '.$quote_tax['percent'].'%',
                'value'=>'<span class="currency">'.dollar($quote_tax['amount'],true,$quote['currency_id']).'</span>',
                'extra'=>$quote_tax['name'] . ' = '.$quote_tax['percent'].'%',
            );
        }
    }
}

$rows[]=array(
    'label'=>_l('Summe Total:'),
    'value'=>'<span class="currency" style="text-decoration: underline; font-weight: bold;">'.dollar($quote['total_amount'],true,$quote['currency_id']).'</span>',
);

foreach($rows as $row){ ?>

<tr >

    <td width="5.5%" >
        &nbsp;
    </td>
    <td width="46.5%" style="font-family: arial,helvetica,sans-serif; font-size: 11px;text-align: left;">
        <?php echo $row['label'];?>
    </td>
    <td width="10%" colspan="<?php echo $colspan;?>">
        &nbsp;
    </td>
    <td width="14%" colspan="<?php echo $colspan;?>">
        &nbsp;
    </td>
    <td width="14%" style="font-family: arial; font-size: 11px; text-align: right;">
        <?php echo $row['value'];?>
    </td>
</tr>
<?php }

$replace['quote_summary'] = ob_get_clean();




$replace['ITEM_ROW_CONTENT'] = $all_item_row_html;
$t->assign_values($replace);
echo $t->render();

if(isset($row_replace) && count($row_replace)){
    module_template::add_tags('task__summary',$row_replace);
}