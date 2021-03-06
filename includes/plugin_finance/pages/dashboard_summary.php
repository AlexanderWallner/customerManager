<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55
  * Package Date: 2015-02-04 02:18:43 
  * IP Address: 185.17.207.18
  */ if ( module_config::c('dashboard_income_summary',1) && $this->can_i('view','Dashboard Finance Summary')) {

    // todo: work out what data this current customer can view.
    $result = module_finance::get_dashboard_data();

    hook_handle_callback('layout_column_thirds','start');
    foreach($result as $r){
        hook_handle_callback('layout_column_thirds','col_start');
        extract($r);

        ob_start();
        ?>
            <div class="content_box_wheader">
            <table class="tableclass tbl_fixed tableclass_rows finance_summary" width="100%">
                <thead>
                <tr>
                    <th width="10%" class=""> <?php _e(ucwords($col1));?> </th>
                    <th width="14%" class=""> <?php _e('Hours');?> </th>
                    <th width="10%" class=""> <?php _e('Invoiced');?> </th>
                    <th width="10%" class=""> <?php _e('Income');?> </th>
                    <?php if(module_finance::is_expense_enabled()){ ?>
                        <th width="10%" class=""> <?php _e('Expense');?> </th>
                    <?php } ?>
                    <?php if(class_exists('module_envato',false) && module_config::c('envato_include_in_dashbaord',1)){ ?>
                        <th width="10%" class=""> <?php _e('Envato');?> </th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php
                $c = 0;
                foreach($data as $key => $row){
                    ?>
                    <tr class="<?php
                        echo $c++%2 ? 'odd' : 'even';
                        if(isset($row['highlight'])){
                            echo ' highlight';
                        }
                        ?>">
                        <td><?php echo $row[$col1]; ?></td>
                        <td><?php echo (isset($row['hours_link'])) ? $row['hours_link'] : $row['hours'];?></td>
                        <td><?php echo (isset($row['amount_invoiced_link'])) ? $row['amount_invoiced_link'] : $row['amount_invoiced'];?></td>
                        <td><?php echo (isset($row['amount_paid_link'])) ? $row['amount_paid_link'] : $row['amount_paid'];?></td>
                        <?php if(module_finance::is_expense_enabled()){ ?>
                            <td><?php echo (isset($row['amount_spent_link'])) ? $row['amount_spent_link'] : $row['amount_spent'];?></td>
                        <?php } ?>
                        <?php if(class_exists('module_envato',false) && module_config::c('envato_include_in_dashbaord',1)){ ?>
                        <td><?php echo (isset($row['envato_earnings_link'])) ? $row['envato_earnings_link'] : $row['envato_earnings'];?></td>
                        <?php } ?> 
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            </div>

        <?php

        $fieldset_data = array(
            'heading' => array('title'=>$table_name,'type'=>'h3'),
            'elements_before' => ob_get_clean(),
        );
        echo module_form::generate_fieldset($fieldset_data);
        unset($fieldset_data);

        hook_handle_callback('layout_column_thirds','col_end');
    }
    hook_handle_callback('layout_column_thirds','end');
	if(get_display_mode()!='mobile'){ ?>
	<script type="text/javascript">
	$(function() {
	    $("#summary-form").dialog({
	        autoOpen: false,
	        height: 550,
	        width: 750,
	        modal: true,
	        buttons: {
	            '<?php _e('Close');?>': function() {
	                $(this).dialog('close');
	            }
	        },
	        close: function() {
	            // reset contents
	            $(this).html('');
	        }
	    });

	    $('.summary_popup')
	        .click(function() {
	            $('#summary-form')
	                    .load($(this).attr('href'))
	                    .dialog('open');
	            return false;
	        });

	});
	</script>
	<div id="summary-form" class="dialog-form"></div>
	<?php }

}
