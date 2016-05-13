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

$group_id = (int)$_REQUEST['group_id'];
$group = array();
if($group_id>0){

    if(class_exists('module_security',false)){
        module_security::check_page(array(
            'category' => 'Group',
            'page_name' => 'Groups',
            'module' => 'group',
            'feature' => 'edit',
        ));
    }
	$group = module_group::get_group($group_id);
}else{
}
if(!$group){
    die('Creating groups this way is disabled');
    $group_id = 'new';
	$group = array(
		'group_id' => 'new',
		'name' => '',
		'default_text' => '',
	);
	module_security::sanatise_data('group',$group);
}
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

    
	<input type="hidden" name="_process" value="save_group" />
	<input type="hidden" name="group_id" value="<?php echo $group_id; ?>" />

        <h3><?php echo _l('Edit group'); ?></h3>

        <table border="0" cellspacing="0" cellpadding="2" class="tableclass tableclass_form">
            <tbody>
            <tr>
                <th class="width2">
                    <?php echo _l('Group Name'); ?>
                </th>
                <td>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($group['name']); ?>" />
                </td>
            </tr>
            <tr>
                <th>
                    <?php echo _l('Available to'); ?>
                </th>
                <td>
                    <?php echo $group['owner_table'];?>
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2">
                    <input type="submit" name="butt_save" id="butt_save" value="<?php echo _l('Save'); ?>" class="submit_button save_button" />
                    <input type="submit" name="butt_del" id="butt_del" value="<?php echo _l('Delete'); ?>" class="submit_button delete_button" />
                    <input type="button" name="cancel" value="<?php echo _l('Cancel'); ?>" onclick="window.location.href='<?php echo $module->link_open(false); ?>';" class="submit_button" />

                </td>
            </tr>
            </tbody>
        </table>

</form>
