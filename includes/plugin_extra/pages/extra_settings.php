<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55, 92d36d3b-0276-47ef-93d8-00df5dc17d5e, 3dac064b-a466-4931-93f0-51c086616894, 0b59530f-164b-4ec2-975b-6a3038423838, 7c3ba354-e0b5-4f02-8a97-854fab16f4b1, c1fe4c24-77fe-44eb-a399-426ebdf540b9, ef74c493-5050-4b88-9be1-92e54c0e34aa, a59dba67-f1e1-4085-943c-c3ded9fc5667, c46a4f2a-d54d-4778-9c2d-c61f41691e34, 68ccf1c5-a309-443a-b04e-69d266cb348f
  * Package Date: 2015-03-08 05:40:21 
  * IP Address: 185.17.207.91
  */



if(!module_config::can_i('edit','Settings')){
    redirect_browser(_BASE_HREF);
}

if(isset($_REQUEST['extra_default_id']) && $_REQUEST['extra_default_id']){
    $show_other_settings=false;
    $extra_default = module_extra::get_extra_default($_REQUEST['extra_default_id']);

    ?>
     <form action="" method="post">
        <input type="hidden" name="_process" value="save_extra_default">
        <input type="hidden" name="extra_default_id" value="<?php echo (int)$_REQUEST['extra_default_id']; ?>" />
     <?php
     $fieldset_data = array(
            'heading' => array(
            'type' => 'h3',
            'title' => 'Edit Extra Default Field',
        ),
        'class' => 'tableclass tableclass_form tableclass_full',
        'elements' => array(
            array(
                'title' => 'Name/Label',
                'field' => array(
                    'type' => 'text',
                    'name' => 'extra_key',
                    'value' => $extra_default['extra_key'],
                ),
            ),
            array(
                'title' => 'Table',
                'field' => array(
                    'type' => 'html',
                    'value' => $extra_default['owner_table'],
                ),
            ),
            array(
                'title' => 'Visibility',
                'field' => array(
                    'type' => 'select',
                    'name' => 'display_type',
                    'value' => $extra_default['display_type'],
                    'options' => module_extra::get_display_types(),
                    'blank' => false,
                    'help' => 'Default will display the extra field when opening an item (eg: opening a customer). If a user can view the customer they will be able to view the extra field information when viewing the customer. Public In Column means that this extra field will also display in the overall listing (eg: customer listing). More options coming soon (eg: private)',
                ),
            ),
            array(
                'title' => 'Order',
                'field' => array(
                    'type' => 'text',
                    'name' => 'order',
                    'value' => $extra_default['order'],
                ),
            ),
            array(
                'title' => 'Searchable',
                'field' => array(
                    'type' => 'select',
                    'name' => 'searchable',
                    'value' => $extra_default['searchable'],
                    'options' => get_yes_no(),
                    'blank' => false,
                    'help' => 'If this field is searchable it will display in the main search bar. Also quick search will return results matching this field. Note: Making every field searchable will slow down the "Quick Search".',
                ),
            ),
            array(
                'title' => 'Field Type',
                'field' => array(
                    'type' => 'select',
                    'name' => 'field_type',
                    'value' => $extra_default['field_type'],
                    'options' => array(
                        '' => 'Text',
                        'date' => 'Date',
                        'textarea' => 'Text Area',
                        'wysiwyg' => 'Rich Text/WYSIWYG',
                        'checkbox' => 'Tick Box',
                        'select' => 'Dropdown / Select',
                    ),
                    'blank' => false,
                ),
            ),
            array(
                'title' => 'Dropdown Options',
                'field' => array(
                    'type' => 'textarea',
                    'name' => 'options[select]',
                    'value' => isset($extra_default['options']['select']) ? $extra_default['options']['select'] : '',
	                'help' => 'Put the drop down options here, one per line (only valid when Field Type is "Dropdown / Select").'
                ),
            ),
        )
    );
    echo module_form::generate_fieldset($fieldset_data);

     $form_actions = array(
        'class' => 'action_bar action_bar_center',
        'elements' => array(
            array(
                'type' => 'save_button',
                'name' => 'butt_save',
                'value' => _l('Save'),
            ),
            array(
                'type' => 'delete_button',
                'name' => 'butt_del',
                'value' => _l('Delete'),
            ),
        ),
    );
    echo module_form::generate_form_actions($form_actions);

    if($extra_default['owner_table'] && $extra_default['extra_key']) {

        $extra_values       = get_multiple( 'extra', array(
            'owner_table' => $extra_default['owner_table'],
            'extra_key'   => $extra_default['extra_key']
        ), 'extra_id', 'exact', 'owner_id' );
        if($extra_values) {
            $table_manager      = module_theme::new_table_manager();
            $columns            = array();
            $columns['id']      = array(
                'title' => 'ID',
            );
            $columns['name']    = array(
                'title'      => 'Name',
                'cell_class' => 'row_action',
            );
            $columns['extra']   = array(
                'title' => 'Value',
            );
            $columns['created'] = array(
                'title' => 'Created',
            );
            $columns['updated'] = array(
                'title' => 'Updated',
            );
            $table_manager->set_columns( $columns );
            $table_manager->row_callback = function ( $row_data ) {
                // load the full customer data before displaying each row so we have access to more details
                $row_data['id']   = $row_data['owner_table'] . ' #' . $row_data['owner_id'];
                $row_data['name'] = 'N/A';
                if ( is_callable( 'module_' . basename( $row_data['owner_table'] ) . '::link_open' ) && $row_data['owner_id'] ) {
                    eval( "\$row_data['name'] = module_" . basename( $row_data['owner_table'] ) . "::link_open(" . $row_data['owner_id'] . ",true);" );
                }
                $row_data['created'] = '';
                if ( $row_data['create_user_id'] ) {
                    $row_data['created'] .= module_user::link_open( $row_data['create_user_id'], true );
                }
                if ( $row_data['date_created'] ) {
                    $row_data['created'] .= ' on ' . $row_data['date_created'];
                }
                $row_data['updated'] = '';
                if ( $row_data['update_user_id'] ) {
                    $row_data['updated'] .= module_user::link_open( $row_data['update_user_id'], true );
                }
                if ( $row_data['date_updated'] ) {
                    $row_data['updated'] .= ' on ' . $row_data['date_updated'];
                }

                return $row_data;
            };
            $table_manager->set_rows( $extra_values );
            $table_manager->pagination = false;
            $table_manager->print_table();
        }
    }


}else{
    ?>


    <h2>
        <!-- <span class="button">
            <?php echo create_link("Add New Field","add",module_extra::link_open_extra_default('new')); ?>
        </span> -->
        <?php echo _l('Extra Fields'); ?>
    </h2>
    <?php

    $extra_defaults = module_extra::get_defaults();
    $visibility_types = module_extra::get_display_types();
    ?>


    <table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableclass tableclass_rows">
        <thead>
        <tr class="title">
            <th><?php echo _l('Section'); ?></th>
            <th><?php echo _l('Extra Field'); ?></th>
            <th><?php echo _l('Display Type'); ?></th>
            <th><?php echo _l('Order'); ?></th>
            <th><?php echo _l('Searchable'); ?></th>
        </tr>
        </thead>
        <tbody>
            <?php
            $c=0;
            $yn = get_yes_no();
            foreach($extra_defaults as $owner_table => $owner_table_defaults){
                foreach($owner_table_defaults as $owner_table_default){
                ?>
                <tr class="<?php echo ($c++%2)?"odd":"even"; ?>">
                    <td>
                        <?php echo htmlspecialchars($owner_table);?>
                    </td>
                    <td class="row_action" nowrap="">
                        <?php echo module_extra::link_open_extra_default($owner_table_default['extra_default_id'],true);?>
                    </td>
                    <td>
                        <?php echo isset($visibility_types[$owner_table_default['display_type']]) ? $visibility_types[$owner_table_default['display_type']] : 'N/A'; ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($owner_table_default['order']); ?>
                    </td>
                    <td>
                        <?php echo isset($owner_table_default['searchable']) ? htmlspecialchars($yn[$owner_table_default['searchable']]) : ''; ?>
                    </td>
                </tr>
            <?php }
            } ?>
        </tbody>
    </table>

<?php } ?>