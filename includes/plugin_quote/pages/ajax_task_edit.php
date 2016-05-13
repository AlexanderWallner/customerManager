<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55, 92d36d3b-0276-47ef-93d8-00df5dc17d5e, 3dac064b-a466-4931-93f0-51c086616894, 0b59530f-164b-4ec2-975b-6a3038423838, 7c3ba354-e0b5-4f02-8a97-854fab16f4b1, c1fe4c24-77fe-44eb-a399-426ebdf540b9, ef74c493-5050-4b88-9be1-92e54c0e34aa, a59dba67-f1e1-4085-943c-c3ded9fc5667, c46a4f2a-d54d-4778-9c2d-c61f41691e34, 68ccf1c5-a309-443a-b04e-69d266cb348f
  * Package Date: 2015-02-04 02:18:43 
  * IP Address: 109.43.2.121
  */

$task_decimal_places = module_config::c('task_amount_decimal_places',-1);
if($task_decimal_places < 0){
    $task_decimal_places = false; // use default currency dec places.
}
$task_decimal_places_trim = module_config::c('task_amount_decimal_places_trim',0);


$colspan = 2;
?><tr class="task_editting task_row_<?php echo $quote_task_id;?>">
    <?php if($show_task_numbers){ ?>
        <td rowspan="2" valign="top" style="padding:0.3em 0;">
            <input type="text" name="quote_task[<?php echo $quote_task_id;?>][task_order]" value="<?php echo $task_data['task_order'];?>" size="3" class="edit_task_order">
        </td>
    <?php } ?>
    <td>
        <?php if($task_editable && module_quote::can_i('delete','Quote Tasks')){ ?>
        <a href="#" onclick="if(confirm('<?php _e('Delete Task?');?>')){$(this).parent().find('input').val('<?php echo _TASK_DELETE_KEY;?>'); $('#quote_task_form')[0].submit();} return false;" class="delete ui-state-default ui-corner-all ui-icon ui-icon-trash" style="display:inline-block; float:right;">[x]</a>
        <?php } ?>
        <input type="text" class="edit_task_description" name="quote_task[<?php echo $quote_task_id;?>][description]" value="<?php echo htmlspecialchars($task_data['description']);?>" id="task_desc_<?php echo $quote_task_id;?>" tabindex="10"><?php
                                if(class_exists('module_product',false)){
                                    module_product::print_quote_task_dropdown($quote_task_id,$task_data);
                                } ?>
    </td>
    <td>

        <?php
        if($task_data['hours']!=0){
            if($task_data['manual_task_type'] == _TASK_TYPE_HOURS_AMOUNT && function_exists('decimal_time_out')){
	            $hours_value = decimal_time_out($task_data['hours']);
            }else {
	            $hours_value = number_out( $task_data['hours'], true );
            }
        }else{
            $hours_value = false;
        }
        if($task_editable){ ?>

            <?php if($task_data['hours'] == 0 && $task_data['manual_task_type']==_TASK_TYPE_AMOUNT_ONLY){
                // no hour input
            }else if($task_data['manual_task_type']==_TASK_TYPE_QTY_AMOUNT){ ?>
                <input type="text" name="quote_task[<?php echo $quote_task_id;?>][hours]" value="<?php echo $hours_value;?>" size="3" style="width:30px;" tabindex="12">
            <?php }else{
             ?>
                <input type="text" name="quote_task[<?php echo $quote_task_id;?>][hours]" value="<?php echo $hours_value;?>" size="3" style="width:30px;"  onchange="setamount(this.value,'<?php echo $quote_task_id;?>');" onkeyup="setamount(this.value,'<?php echo $quote_task_id;?>');" tabindex="12">
            <?php
            } ?>

        <?php }else{
            if($task_data['hours'] == 0 && $task_data['manual_task_type']==_TASK_TYPE_AMOUNT_ONLY){
                // no hour input
            }else{
                echo $hours_value;
            }
        } ?>
    </td>
    <td nowrap="">
        <?php if($task_editable){ ?>
            <?php echo currency('<input type="text" name="quote_task['.$quote_task_id.'][amount]" value="'.($task_data['amount'] != 0 ? number_out($task_data['amount'],$task_decimal_places_trim,$task_decimal_places) : number_out($task_data['hours']*$quote['hourly_rate'],$task_decimal_places_trim,$task_decimal_places)).'" id="'.$quote_task_id.'taskamount" class="currency" tabindex="13">');?>
        <?php }else{ ?>
            <?php echo $task_data['amount'] != 0 ? dollar($task_data['amount'],true,$quote['currency_id'],$task_decimal_places_trim,$task_decimal_places) : dollar($task_data['hours']*$quote['hourly_rate'],true,$quote['currency_id'],$task_decimal_places_trim,$task_decimal_places);?>
        <?php } ?>
    </td>
    <?php if(module_config::c('quote_allow_staff_assignment',1)){
    $colspan++; ?>
        <td>
            <?php echo print_select_box($staff_member_rel,'quote_task['.$quote_task_id.'][user_id]',
        isset($staff_member_rel[$task_data['user_id']]) ? $task_data['user_id'] : false, 'quote_task_staff_list', ''); ?>
        </td>
    <?php } ?>
    <td></td>
</tr>
<tr class="task_editting task_row_<?php echo $quote_task_id;?>">
    <td>
       <textarea name="quote_task[<?php echo $quote_task_id;?>][long_description]" class="edit_task_long_description" tabindex="11" id="task_long_desc_<?php echo $quote_task_id;?>"><?php echo htmlspecialchars($task_data['long_description']);?></textarea>
        <?php
          if(function_exists('hook_handle_callback'))hook_handle_callback('quote_task_after',$task_data['quote_id'],$task_data['quote_task_id'],$quote,$task_data);
        ?>
    </td>
    <td colspan="<?php echo $colspan;?>" valign="top">

        <!--div>
        <?php _e('Task Type:'); ?> <?php
            $types = module_quote::get_task_types();
            $types['-1'] = _l('Default (%s)',$types[$quote['default_task_type']]);
            module_form::generate_form_element(array(
                'type' => 'select',
                'name' => 'quote_task['.$quote_task_id.'][manual_task_type]',
                'id' => 'manual_task_type_'.$quote_task_id,
                'options' => $types,
                'blank' => false,
                'value' => $task_data['manual_task_type_real'],
            ));
            ?>
        </div-->

    </td>
    <td class="edit_task_options">
        <div>
        <?php if($task_editable){ ?>
            <input type="hidden" name="quote_task[<?php echo $quote_task_id;?>][billable_t]" value="1">
            <input type="checkbox" name="quote_task[<?php echo $quote_task_id;?>][billable]" value="1" id="billable_t_<?php echo $quote_task_id;?>" <?php echo $task_data['billable'] ? ' checked':'';?> tabindex="17"> <label for="billable_t_<?php echo $quote_task_id;?>"><?php _e('Task is billable');?></label> <br/>
            <input type="hidden" name="quote_task[<?php echo $quote_task_id;?>][taxable_t]" value="1">
            <input type="checkbox" name="quote_task[<?php echo $quote_task_id;?>][taxable]" value="1" id="taxable_t_<?php echo $quote_task_id;?>" <?php echo $task_data['taxable'] ? ' checked':'';?> tabindex="17"> <label for="taxable_t_<?php echo $quote_task_id;?>"><?php _e('Task is taxable');?></label>
        <?php }else{
            if($task_data['billable']){
                _e('Task is billable');
            }else{
                _e('Task not billable');
            }
            echo '<br/>';
            if($task_data['taxable']){
                _e('Task is taxable');
            }else{
                _e('Task not taxable');
            }
        }
        ?>
        </div>


        <div class="edit_task_button">
            <button type="submit" name="ts" class="save_task small_button btn btn-xs btn-success" tabindex="20" style="float:left;"><?php _e('Save');?></button>
        <a href="#" class="delete ui-state-default ui-corner-all ui-icon ui-icon-arrowreturn-1-w" style="float:right;" title="<?php _e('Cancel');?>" onclick="refresh_task_preview(<?php echo $quote_task_id;?>,false); return false;">cancel</a>
        </div>

    </td>
</tr>