<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55, 7c3ba354-e0b5-4f02-8a97-854fab16f4b1
  * Package Date: 2015-02-04 02:18:43 
  * IP Address: 185.17.207.18
  */

if(module_security::is_logged_in()) {
	switch ( $display_mode ) {
		case 'iframe':
			?>
			</div> <!-- /#content -->
			</section><!-- /.content -->
			</aside><!-- /.right-side -->
			</div><!-- ./wrapper -->

			</body>
			</html>
			<?php
			module_debug::push_to_parent();
			break;
		case 'ajax':

			break;
		case 'normal':
		default:
			/*
			<div id="footer">
				<p>&copy; <?php echo module_config::s('admin_system_name','Ultimate Client Manager'); ?>
				  - <?php echo date("Y"); ?>
				  - <?php _e('Version:');?> <?php echo module_config::current_version(); ?>
				  - <?php _e('Time:');?> <?php echo round(microtime(true)-$start_time,5);?>
				</p>
			</div>
	*/
			?>


				</div> <!-- /#content -->
				</section><!-- /.content -->
				</aside><!-- /.right-side -->
				</div><!-- ./wrapper -->
<script>
        jQuery(document).ready(function() {       
           // initiate layout and plugins
          Metronic.init(); // init metronic core components
          //Layout.init(); // init current layout
          //QuickSidebar.init(); // init quick sidebar
          //Demo.init(); // init demo features
          ComponentsDropdowns.init();
          consoleChat('username');// username not necessarily, default name Guest
        });   
    </script>


				</body>
				</html>
			<?php
			break;
	}
}else{
	?>
	</body></html>
	<?php
}
