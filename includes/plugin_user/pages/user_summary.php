<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableclass tableclass_form">
	<tbody>
		<tr>
			<th>
				<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55
  * Package Date: 2015-02-04 02:18:43 
  * IP Address: 185.17.207.18
  */ echo _l('Contact Name'); ?>
			</th>
			<td>
				<?php echo $user_data['name'];?>
				<a href="<?php echo $plugins['user']->link_open($user_id);?>">&raquo;</a>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo _l('Phone'); ?>
			</th>
			<td>
				<?php echo $user_data['phone'];?>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo _l('Mobile'); ?>
			</th>
			<td>
				<?php echo $user_data['mobile'];?>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo _l('Fax'); ?>
			</th>
			<td>
				<?php echo $user_data['fax'];?>
			</td>
		</tr>
		<tr>
			<th>
				<?php echo _l('Email'); ?>
			</th>
			<td>
				<a href="mailto:<?php echo $user_data['email'];?>"><?php echo $user_data['email'];?></a>
			</td>
		</tr>
		<tr>
			<th>

			</th>
			<td>
                <a href="<?php echo $plugins['user']->login_link($user_id);?>">Login as this Administrator &raquo;</a>
			</td>
		</tr>
	</tbody>
</table>