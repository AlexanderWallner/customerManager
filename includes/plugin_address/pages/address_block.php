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
$fields = array(
    "line_1" => array(module_config::c('address_1',"Line 1"),30),
    "line_2" => array(module_config::c('address_2',"Line 2"),30),
    "suburb" => array(module_config::c('address_3',"Suburb"),30),
    "state" => array(module_config::c('address_4',"State"),20),
    "region" => array(module_config::c('address_5',"Region"),20),
    "post_code" => array(module_config::c('address_6',"Post Code"),10),
    "country" => array(module_config::c('address_7',"Country"),20),
);

if(isset($display_pretty) && $display_pretty){
    $fieldset_data = array(
        'heading' => array(
            'type' => 'h3',
            'title' => 'Address',
        ),
        'elements' => array(),
    );
    foreach($fields as $key=>$val){
        $fieldset_data['elements'][$key] = array(
            'title' => $val[0],
            'field' => array(
                'type' => 'text',
                'name' => 'address['.$address_hash.']['.$key.']',
                'value' => isset($address[$key]) ? $address[$key] : '',
                'size' => $val[1],
            )
        );
    }
    echo module_form::generate_fieldset($fieldset_data);
    unset($fieldset_data);
}else{
    // oldschool table layout:

    ?>
    <table width="100%" border="0" cellspacing="0" cellpadding="2" class="tableclass tableclass_form">
        <tbody>
        <?php
        foreach($fields as $key=>$val){
        ?>
            <tr>
                <th class="width1">
                    <?php echo _l($val[0]); ?>
                </th>
                <td>
                    <?php
                    // quick added hack for 'region' to display as a drop down
                    if($key=='region_id' || $key=='state_id' || $key=='country_id'){
                        echo print_select_box(get_col_vals('address',$key),'address['.$address_hash.']['.$key.']',(isset($address[$key]) ? $address[$key] : ''),'',false,false,true);
                    }else{
                        ?>
                        <input type="text" name="address[<?php echo $address_hash;?>][<?php echo $key;?>]" value="<?php echo isset($address[$key]) ? htmlspecialchars($address[$key]) : ''; ?>" size="<?php echo $val[1];?>" />
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <?php
}
?>
<input type="hidden" name="address[<?php echo $address_hash;?>][address_id]" value="<?php echo $address_id;?>">
<input type="hidden" name="address[<?php echo $address_hash;?>][owner_id]" value="<?php echo $owner_id;?>">
<input type="hidden" name="address[<?php echo $address_hash;?>][owner_table]" value="<?php echo $owner_table;?>">
<input type="hidden" name="address[<?php echo $address_hash;?>][address_type]" value="<?php echo $address_type;?>">