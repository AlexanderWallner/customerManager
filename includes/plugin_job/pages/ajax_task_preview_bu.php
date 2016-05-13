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

$hours_prefix = '';
$show_split_hours = false;
$task_decimal_places = module_config::c('task_amount_decimal_places',-1);
if($task_decimal_places < 0){
    $task_decimal_places = false; // use default currency dec places.
}
$task_decimal_places_trim = module_config::c('task_amount_decimal_places_trim',0);

if(module_job::job_task_has_split_hours($job_id,$job,$task_id,$task_data)){
    if($task_data['staff_split']){
        // has saved this task - using database detauls

    }else{
        // use defaults above.
        $task_data['staff_hours'] = $task_data['hours'];
        $task_data['staff_amount'] = $task_data['amount'];

    }
    if(module_job::can_i('view','Job Split Pricing')){
        $show_split_hours = true;
        // do we show the staff_ settings or default them to the job settings?
    }else{
        $hours_prefix = 'staff_';
    }
}

?>
<tr class="task_row_<?php echo $task_id;?> task_preview<?php echo $percentage>=1 ?' tasks_completed':'';?> <?php echo ($task_editable) ? ' task_editable' : '';?>" rel="<?php echo $task_id;?>">
        <?php if($show_task_numbers){ ?>
            <td valign="top" class="task_order task_drag_handle"><?php echo $task_data['task_order'];?></td>
        <?php } ?>
        <td valign="top">
            <?php
            if($task_data['approval_required'] == 1){
                echo '<span style="font-style: italic;" class="error_text">'._l('(approval required)').'</span> ';
            }else if($task_data['approval_required'] == 2){
                echo '<span style="font-style: italic;" class="error_text">'._l('(task rejected)').'</span> ';
            }
            if($task_editable){ // $task_editable ?>
                <a href="#" onclick="edittask(<?php echo $task_id;?>,0); return false;" class="<?php
                            // set color
                            if($percentage==1){
                                echo 'success_text';
                            }else if($percentage!=1 && $task_due_time < time()){
                                echo 'error_text';
                            }
                            ?>"><?php echo (!trim($task_data['description'])) ? 'N/A' : htmlspecialchars($task_data['description']);?></a>
<?php }else{ ?>
                    <span class="<?php
                            // set color
                            if($percentage==1){
                                echo 'success_text';
                            }else if($percentage!=1 && $task_due_time < time()){
                                echo 'error_text';
                            }
                            ?>"><?php echo (!trim($task_data['description'])) ? 'N/A' : htmlspecialchars($task_data['description']);?></span>
<?php }

               /*  <div style="z-index: 5; position: relative; min-height:18px; margin-bottom: -18px;"></div>
            <div class="task_percentage task_width"> */
           /* if(module_config::c('job_task_percentage',1) && ($percentage==1 || $task_data['hours']>0)){
                // work out the percentage.


                ?>
                    <div class="task_percentage_label task_width"><?php echo $percentage*100;?>%</div>
                    <div class="task_percentage_bar task_width" style="width:<?php echo round($percentage * $width);?>px;"></div>
                    <?php <div class="task_description">
                        <a href="#" onclick="edittask(<?php echo $task_id;?>,0); return false;" class="<?php
                            // set color
                            if($percentage==1){
                                echo 'success_text';
                            }else if($percentage!=1 && $task_due_time < time()){
                                echo 'error_text';
                            }
                            ?>"><?php echo (!trim($task_data['description'])) ? 'N/A' : htmlspecialchars($task_data['description']);?></a>
                    </div> ?>
            <?php }else{ ?>

            <?php } */
            /*</div>*/

            if(isset($task_data['long_description']) && $task_data['long_description'] != ''){ ?>
                <a href="#" class="task_toggle_long_description">&raquo;</a>
                <div class="task_long_description" <?php if(module_config::c('job_tasks_show_long_desc',0)){ ?> style="display:block;" <?php } ?>><?php echo forum_text(trim($task_data['long_description']));?></div>
            <?php }else{ ?>
                &nbsp;
            <?php }
            if(function_exists('hook_handle_callback') && $task_data['task_id'])hook_handle_callback('job_task_after',$task_data['job_id'],$task_data['task_id'],$job,$task_data);
            ?>
        </td>
        <td valign="top" class="task_drag_handle">
            <?php
            if($task_data[$hours_prefix.'hours'] == 0 && $task_data['manual_task_type'] == _TASK_TYPE_AMOUNT_ONLY){
            // only amount, no hours or qty
            }else{
                // are the logged hours different to the billed hours?
                // are we completed too?
	            if($task_data[$hours_prefix.'hours']!=0){
		            if($task_data['manual_task_type'] == _TASK_TYPE_HOURS_AMOUNT && function_exists('decimal_time_out')){
			            $hours_value = decimal_time_out($task_data[$hours_prefix.'hours']);
		            }else {
			            $hours_value = number_out( $task_data[$hours_prefix.'hours'], true );
		            }
	            }else{
		            $hours_value = false;
	            }
                if($percentage == 1 && $task_data['completed'] < $task_data[$hours_prefix.'hours']){
                    echo '<span class="success_text">';
                    echo $hours_value !== false ? $hours_value : '-';
                    echo '</span>';
                }else if($percentage == 1 && $task_data['completed'] > $task_data[$hours_prefix.'hours']){
                    echo '<span class="error_text">';
                    echo $hours_value !== false ? $hours_value : '-';
                    echo '</span>';
                }else{
                    echo $hours_value !== false ? $hours_value : '-';
                }
            }
            if($show_split_hours){
                echo '<br/>';
                if($task_data['staff_hours'] == 0 && $task_data['manual_task_type'] == _TASK_TYPE_AMOUNT_ONLY){
            // only amount, no hours or qty
                }else{
                    // are the logged hours different to the billed hours?
                    // are we completed too?
                    if($task_data['staff_hours']!=0){
                        if($task_data['manual_task_type'] == _TASK_TYPE_HOURS_AMOUNT && function_exists('decimal_time_out')){
                            $hours_value = decimal_time_out($task_data['staff_hours']);
                        }else {
                            $hours_value = number_out( $task_data['staff_hours'], true );
                        }
                    }else{
                        $hours_value = false;
                    }
                    if($percentage == 1 && $task_data['completed'] < $task_data['staff_hours']){
                        echo '<span class="">';
                        echo $hours_value !== false ? $hours_value : '-';
                        echo '</span>';
                    }else if($percentage == 1 && $task_data['completed'] > $task_data['staff_hours']){
                        echo '<span class="">';
                        echo $hours_value !== false ? $hours_value : '-';
                        echo '</span>';
                    }else{
                        echo $hours_value !== false ? $hours_value : '-';
                    }
                }
            }
            ?>
        </td>
        <?php if(module_invoice::can_i('view','Invoices')){ ?>
        <td valign="top" class="task_drag_handle">
            <span class="currency <?php echo $task_data['billable'] ? 'success_text' : 'error_text';?>">
            <?php
            echo $task_data[$hours_prefix.'amount'] != 0 ? dollar($task_data[$hours_prefix.'amount'],true,$job['currency_id'],$task_decimal_places_trim,$task_decimal_places) : dollar($task_data[$hours_prefix.'hours']*$job[$hours_prefix.'hourly_rate'],true,$job['currency_id']);?>
                <?php if($task_data['manual_task_type'] == _TASK_TYPE_QTY_AMOUNT || $task_data['manual_task_type'] == _TASK_TYPE_HOURS_AMOUNT){
                    $full_amount = $task_data[$hours_prefix.'hours'] * $task_data[$hours_prefix.'amount'];
                    if($full_amount != $task_data[$hours_prefix.'amount']){
                        echo '<br/>('.dollar($full_amount,true,$job['currency_id']).')';
                    }
                } ?>
            </span>
            <?php 
            if($show_split_hours){
                echo '<br/><span class="currency">';
                echo $task_data['staff_amount'] != 0 ? dollar($task_data['staff_amount'],true,$job['currency_id']) : dollar($task_data['staff_hours']*$job['staff_hourly_rate'],true,$job['currency_id']);?>
                <?php if($task_data['manual_task_type'] == _TASK_TYPE_QTY_AMOUNT){
                    $full_amount = $task_data['staff_hours'] * $task_data['staff_amount'];
                    if($full_amount != $task_data['staff_amount']){
                        echo '<br/>('.dollar($full_amount,true,$job['currency_id']).')';
                    }
                }
                echo '</span>';
            } ?>
        </td>
        <?php } ?>
        <?php if(module_config::c('job_show_due_date',1)){ ?>
        <td valign="top" class="task_drag_handle">
            <?php
            if($task_data['date_due'] && $task_data['date_due'] != '0000-00-00'){

                if($percentage!=1 && $task_due_time < time()){
                    echo '<span class="error_text">';
                    echo print_date($task_data['date_due']);
                    echo '</span>';
                }else{
                    echo print_date($task_data['date_due']);
                }
            }
            ?>
        </td>
        <?php } ?>
    <?php if(module_config::c('job_show_time_start',1) && !isset($options['from_quote'])){ ?>
        <td valign="top" class="task_drag_handle">
            <?php
            if(isset($task_data['time_start']) && $task_data['time_start'] && ($task_data['time_start'] != '00:00:00' || (isset($task_data['time_done']) && $task_data['time_done'] && $task_data['time_done'] != '00:00:00')) ){
                echo substr($task_data['time_start'], 0, 5);
            }
            ?>
        </td>
    <?php } ?>
    <?php if(module_config::c('job_show_time_done',1) && !isset($options['from_quote'])){ ?>
        <td valign="top" class="task_drag_handle">
            <?php
            if(isset($task_data['time_done']) && $task_data['time_done'] && $task_data['time_done'] != '00:00:00'){
                echo substr($task_data['time_done'], 0, 5);
            }
            ?>
        </td>
    <?php } ?>
        <?php if(module_config::c('job_show_done_date',1) && !isset($options['from_quote'])){ ?>
        <td valign="top" class="task_drag_handle">
            <?php
            if(isset($task_data['date_done']) && $task_data['date_done'] && $task_data['date_done'] != '0000-00-00'){
                echo print_date($task_data['date_done']);
            }
            ?>
        </td>
        <?php } ?>

        <?php if(module_config::c('job_allow_staff_assignment',1)){ ?>
            <td valign="top" class="task_drag_handle">
                <?php echo isset($staff_member_rel[$task_data['user_id']]) ? $staff_member_rel[$task_data['user_id']] : ''; ?>
            </td>
        <?php } ?>
        <td valign="top">
           <span class="<?php echo $percentage >= 1 ? 'success_text' : 'error_text';?><?php echo $task_editable ? ' task_percentage_toggle' : '';?>" data-task-id="<?php echo $task_id;?>">
                <?php echo $percentage*100;?>%
            </span>
        </td>
        <?php if(class_exists('module_signature',false) && module_signature::signature_enabled($job_id)){ ?>
            <td> <?php module_signature::signature_job_task_link($job_id,$task_id);?> </td>
        <?php } ?>
        <td align="center" valign="top">
            <?php if($task_data['invoiced'] && $task_data['invoice_id']){
                if(module_invoice::can_i('view','Invoices')){
                    //$invoice = module_invoice::get_invoice($task_data['invoice_id']);
                    echo module_invoice::link_open($task_data['invoice_id'],true);
                }
                /*echo " ";
                echo '<span class="';
                if($invoice['total_amount_due']>0){
                    echo 'error_text';
                }else{
                    echo 'success_text';
                }
                echo '">';
                if($invoice['total_amount_due']>0){
                    echo dollar($invoice['total_amount_due'],true,$job['currency_id']);
                    echo ' '._l('due');
                }else{
                    echo _l('All paid');
                }
                echo '</span>';*/
            }else if($task_editable){ ?>
                <?php if(module_config::c('job_task_edit_icon',0)){ // old icon:  ?>
                <a href="#" class="ui-state-default ui-corner-all ui-icon ui-icon-<?php echo $percentage == 1 ? 'pencil' : 'check';?>" title="<?php _e( $percentage == 1 ? 'Edit' : 'Complete');?>" onclick="edittask(<?php echo $task_id;?>,<?php echo ($task_data['hours']!=0?($task_data['hours']-$task_data['completed']):1);?>); return false;"><?php _e('Edit');?></a>
                <?php }else{ ?>
                    <input type="button" name="edit" value="<?php _e('Edit');?>" class="small_button" onclick="edittask(<?php echo $task_id;?>,<?php echo ($task_data['hours']!=0?($task_data['hours']-$task_data['completed']):1);?>); return false;">
                <?php } ?>

            <?php } ?>
        </td>
    </tr>
