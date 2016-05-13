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
if(!module_data::can_i('edit',_MODULE_DATA_NAME)){
	die("access denied");
}
$data_types = $module->get_data_types();
$menu_locations = module_data::get_menu_locations();

$header_buttons = array();
if(module_data::can_i('create',_MODULE_DATA_NAME)){
    $header_buttons[] = array(
        'url' => module_data::link_open_data_type('new'),
        'title' => "Create New "._MODULE_DATA_NAME,
    );
}

print_heading(array(
    'main' => true,
    'type' => 'h2',
    'title' => _MODULE_DATA_NAME,
    'button' => $header_buttons,
));
?>


<table class="tableclass tableclass_rows tableclass_full">
	<thead>
	<tr class="title">
    	<th><?php echo _l('Data Type'); ?></th>
    	<th><?php echo _l('Menu Location'); ?></th>
    	<th><?php echo _l('Records'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php 
    $c=0;
	foreach($data_types as $data){ 
		$data_type = $module->get_data_type($data['data_type_id']);
		?>
        <tr class="<?php echo ($c++%2)?"odd":"even"; ?>">
            <td class="row_action"><a href="<?php echo module_data::link_open_data_type($data_type['data_type_id']); ?>"><?php echo htmlspecialchars($data_type['data_type_name']); ?></a></td>
            <td><?php echo isset($menu_locations[$data['data_type_menu']]) ? htmlspecialchars($menu_locations[$data['data_type_menu']]) : _l('N/A');?></td>
            <td><a href="<?php echo $module->link('admin_data',array('data_type_id'=>$data_type['data_type_id'],'view_all'=>1)); ?>"><?php echo $data_type['count'];?> - view all</a></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
