<div class="file_<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55
  * Package Date: 2015-02-04 02:18:43 
  * IP Address: 185.17.207.18
  */ echo $owner_table;?>_<?php echo $owner_id;?>">
    <?php
    foreach($file_items as $file_item){
        $file_item = self::get_file($file_item['file_id']);
        ?>
        
        <div style="width:110px; height:90px; overflow:hidden; ">

            <?php
            // /display a thumb if its supported.
            if(preg_match('/\.(\w\w\w\w?)$/',$file_item['file_name'],$matches)){
                switch(strtolower($matches[1])){
                    case 'jpg':
                    case 'jpeg':
                    case 'gif':
                    case 'png':
                        ?>
                            <img src="<?php echo _BASE_HREF . nl2br(htmlspecialchars($file_item['file_path']));?>" width="100" alt="preview" border="0">
                        <?php
                        break;
                    default:
                        echo 'n/a';
                }
            }
            ?>
        </div>
    <?php
    }
    ?>
</div>

