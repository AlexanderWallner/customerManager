<div class="blob">
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

    $step = (isset($_REQUEST['step'])) ? (int)$_REQUEST['step'] : 0;

    //print_heading('Setup Wizard (step '.$step.' of 4)');?>

    
    <p>
        <?php echo _l('Hello, Welcome to the setup wizard. You are currently on step %s of 5.',$step); ?>
    </p>

    <?php
    include('setup'.$step.'.php');
    ?>
      
</div>


