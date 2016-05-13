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

$options = isset($_REQUEST['options']) ? unserialize(base64_decode($_REQUEST['options'])) : array();

$file_id = (int)$_REQUEST['file_id'];
if($file_id){
	$file = $module->get_file($file_id);
}else{
	$file = array(
		"owner_id" => (int)$_REQUEST['owner_id'],
		"owner_table" => $_REQUEST['owner_table'],
	);
}

?>

<iframe src="about:blank" name="file_popup_iframe<?php echo $file['owner_table'];?>_<?php echo $file['owner_id'];?>" id="file_popup_iframe" style="display:none;"></iframe>
<form action="<?php echo $module->link();?>" method="post" target="file_popup_iframe<?php echo $file['owner_table'];?>_<?php echo $file['owner_id'];?>" enctype="multipart/form-data">
	<input type="hidden" name="_process" value="save_file_popup">
	<input type="hidden" name="_redirect" class="redirect" value="">
	<input type="hidden" name="layout" value="<?php echo (isset($_REQUEST['layout'])) ? $_REQUEST['layout'] : 'gallery';?>">
	<input type="hidden" name="file_id" value="<?php echo $file_id;?>">
	<input type="hidden" name="owner_id" value="<?php echo $file['owner_id'];?>">
	<input type="hidden" name="owner_table" value="<?php echo $file['owner_table'];?>">
    <input type="hidden" name="options" value="<?php echo base64_encode(serialize($options)); ?>" />

	<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableclass">
		<tbody>
		<?php if($file_id && $file_id != 'new'){ ?>
			<tr>
				<th>
					<?php echo _l('Download'); ?>
				</th>
				<td>
					<a href="<?php echo $module->link('',array('_process'=>'download','file_id'=>$file_id),'file',false);?>">Click here to download the file</a>
				</td>
			</tr>
			<tr>
				<th>
					<?php echo _l('Replace'); ?>
				</th>
				<td>
					<input type="file" name="file_upload" />
				</td>
			</tr>
			<tr>
				<th>File Name</th>
				<td>
					<input type="text" name="file_name" value="<?php echo htmlspecialchars($file['file_name']);?>">
				</td>
			</tr>
			<tr>
				<th>
					<?php echo _l('Delete'); ?>
				</th>
				<td>
					<a href="<?php echo $module->link('',array('_process'=>'delete_file_popup','file_id'=>$file_id),'file',false);?>" target="file_popup_iframe<?php echo $file['owner_table'];?>_<?php echo $file['owner_id'];?>" onclick="return confirm('Really delete this file?');">Click here to delete the file</a>
				</td>
			</tr>
		<?php }else{ ?>
			<tr>
				<th>
					<?php echo _l('Upload New File'); ?>
				</th>
				<td>
					<input type="file" name="file_upload" />
				</td>
			</tr>
		<?php }
		
		?>
		</tbody>
	</table>

</form>

<?php
exit;