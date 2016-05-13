<table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableclass tableclass_form">
	<tbody>
		<tr>
			<th class="width1">
				<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55
  * Package Date: 2015-02-04 02:18:43 
  * IP Address: 185.17.207.18
  */ echo _l('Customer Name'); ?>
			</th>
			<td>
				<?php echo $customer_data['customer_name'];?>
				<a href="<?php echo module_customer::link_open($customer_id);?>">&raquo;</a>
			</td>
		</tr>
	</tbody>
</table>