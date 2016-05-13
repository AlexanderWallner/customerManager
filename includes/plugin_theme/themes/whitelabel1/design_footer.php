<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55, 92d36d3b-0276-47ef-93d8-00df5dc17d5e, 3dac064b-a466-4931-93f0-51c086616894, 0b59530f-164b-4ec2-975b-6a3038423838, 7c3ba354-e0b5-4f02-8a97-854fab16f4b1, c1fe4c24-77fe-44eb-a399-426ebdf540b9, ef74c493-5050-4b88-9be1-92e54c0e34aa, a59dba67-f1e1-4085-943c-c3ded9fc5667
  * Package Date: 2015-02-04 02:18:43 
  * IP Address: 88.217.180.119
  */
switch($display_mode){
    case 'iframe':
        ?>
         </section>
         </section>
        </body>
        </html>
        <?php
        module_debug::push_to_parent();
        break;
    case 'ajax':

        break;
    case 'mobile':
        if(class_exists('module_mobile',false)){
            module_mobile::render_stop($page_title,$page);
        }
        break;
    case 'normal':
    default:
        ?>
        </section>
                <footer>&copy; <?php echo module_config::s('admin_system_name','Ultimate Client Manager'); ?>
              - <?php echo date("Y"); ?>
              - Version: <?php echo module_config::current_version(); ?>
              - Time: <?php echo round(microtime(true)-$start_time,5);?>
              <?php if(class_exists('module_mobile',false) && module_config::c('mobile_link_in_footer',1)){ ?>
           <!-- - <a href="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); echo strpos($_SERVER['REQUEST_URI'],'?')===false ? '?' : '&'; ?>display_mode=mobile"><?php _e('Switch to Mobile Site');?></a> -->
            <?php } ?></footer>
        </body>
        </html>
        <?php
        break;
}