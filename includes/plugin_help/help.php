<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55, 92d36d3b-0276-47ef-93d8-00df5dc17d5e, 3dac064b-a466-4931-93f0-51c086616894, 0b59530f-164b-4ec2-975b-6a3038423838, 7c3ba354-e0b5-4f02-8a97-854fab16f4b1, c1fe4c24-77fe-44eb-a399-426ebdf540b9, ef74c493-5050-4b88-9be1-92e54c0e34aa, a59dba67-f1e1-4085-943c-c3ded9fc5667, c46a4f2a-d54d-4778-9c2d-c61f41691e34, 68ccf1c5-a309-443a-b04e-69d266cb348f
  * Package Date: 2015-03-08 05:40:21 
  * IP Address: 185.17.207.69
  */



class module_help extends module_base{

	public $links;
	public $help_types;

    public static function can_i($actions,$name=false,$category=false,$module=false){
        if(!$module)$module=__CLASS__;
        return parent::can_i($actions,$name,$category,$module);
    }
	public static function get_class() {
        return __CLASS__;
    }
	public function init(){
		$this->links = array();
		$this->help_types = array();
		$this->module_name = "help";
		$this->module_position = 16;
        $this->version = 2.1;
        //2.1 - 2014-03-14 - initial release of new help system

		if(module_help::is_plugin_enabled() &&
		   (
				(module_config::c('help_only_for_admin',1) && module_security::get_loggedin_id() == 1) ||
				(!module_config::c('help_only_for_admin',1) && module_help::can_i('view','Help'))
			)
		){
			// hook for help icon in top bar
			hook_add('header_buttons','module_help::hook_filter_var_header_buttons');
			hook_add('header_print_js','module_help::header_print_js');
			module_config::register_js('help','help.js');

			if(module_config::can_i('view','Settings')){
                $this->links[] = array(
                    "name"=>"Help",
                    "p"=>"help_settings",
                    'holder_module' => 'config', // which parent module this link will sit under.
                    'holder_module_page' => 'config_admin',  // which page this link will be automatically added to.
                    'menu_include_parent' => 0,
                );
            }

		}

	}


	public static function header_print_js(){
		$pages = isset($_REQUEST['p']) ? (is_array($_REQUEST['p']) ? $_REQUEST['p'] : array($_REQUEST['p'])) : array();
		$modules = isset($_REQUEST['m']) ? (is_array($_REQUEST['m']) ? $_REQUEST['m'] : array($_REQUEST['m'])) : array();
		foreach($pages as $pid=>$p)$pages[$pid] = preg_replace('#[^a-z_]#','',$p);
		foreach($modules as $pid=>$p)$modules[$pid] = preg_replace('#[^a-z_]#','',$p);
		?>
		<script type="text/javascript">
			ucm.help.current_modules = '<?php echo implode('/',$modules);?>';
			ucm.help.current_pages = '<?php echo implode('/',$pages);?>';
			ucm.help.lang.help = '<?php _e('Help'); ?>';
			ucm.help.url_extras = '&codes=<?php echo base64_encode(module_config::c('_installation_code'));?>&host=<?php echo urlencode(htmlspecialchars(full_link('/')));?>';
		</script>
		<?php
	}

	public static function hook_filter_var_header_buttons($callback, $header_buttons){
		$header_buttons['help'] = array(
			'fa-icon' => 'question',
			'title' => 'Help',
			'id' => 'header_help',
		);
		return $header_buttons;
	}



}