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
switch($display_mode){
    case 'iframe':
        ?>
         </div> <!-- end .inner -->
         </div> <!-- end .outer -->
         </div> <!-- end .content -->
        </body>
        </html>
        <?php
        module_debug::push_to_parent();
        break;
    case 'ajax':

        break;
    case 'normal':
    default:
        ?>

         </div> <!-- end .inner -->
         </div> <!-- end .outer -->
         </div> <!-- end .content -->

        </div>
        <!-- /#wrap -->


        <div id="footer">
            <p>&copy; <?php echo module_config::s('admin_system_name','Ultimate Client Manager'); ?>
              - <?php echo date("Y"); ?>
              - Version: <?php echo module_config::current_version(); ?>
              - Time: <?php echo round(microtime(true)-$start_time,5);?>
            </p>
        </div>

        </body>
        </html>
        <?php
        break;
}