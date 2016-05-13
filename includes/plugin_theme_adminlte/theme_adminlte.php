<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55, 92d36d3b-0276-47ef-93d8-00df5dc17d5e, 3dac064b-a466-4931-93f0-51c086616894, 0b59530f-164b-4ec2-975b-6a3038423838, 7c3ba354-e0b5-4f02-8a97-854fab16f4b1, c1fe4c24-77fe-44eb-a399-426ebdf540b9, ef74c493-5050-4b88-9be1-92e54c0e34aa, a59dba67-f1e1-4085-943c-c3ded9fc5667, c46a4f2a-d54d-4778-9c2d-c61f41691e34
  * Package Date: 2015-02-04 02:18:43 
  * IP Address: 185.17.207.77
  */



class module_theme_adminlte extends module_base{

    public static function can_i($actions,$name=false,$category=false,$module=false){
        if(!$module)$module=__CLASS__;
        return parent::can_i($actions,$name,$category,$module);
    }
	public static function get_class() {
        return __CLASS__;
    }
	public function init(){
		$this->module_name = "theme_adminlte";
		$this->module_position = 0;

        $this->version = 2.131;
        //2.131 - 2015-02-08 - job discussion improvement (thanks w3corner!)
        //2.13 - 2015-01-26 - dashboard widgets save position
        //2.129 - 2015-01-21 - leads dashboard link fix
        //2.128 - 2014-12-17 - signup form on login
        //2.127 - 2014-11-26 - improved form framework
        //2.126 - 2014-11-19 - content padding fix
        //2.125 - 2014-11-05 - welcome_message_role_X template support
        //2.124 - 2014-10-13 - date and encrypt field fixes
        //2.123 - 2014-09-09 - job task message saving fix
        //2.122 - 2014-08-20 - css fixes
        //2.121 - 2014-08-18 - fix for quick pin menu item
        //2.12 - 2014-08-18 - missing javascript file
        //2.11 - 2014-08-14 - dashboard widget permissions
        //2.1 - 2014-07-31 - initial release

        hook_add('get_themes','module_theme_adminlte::hook_get_themes');
        if(module_theme::get_current_theme() == 'theme_adminlte'){
            hook_add('get_table_manager','module_theme_adminlte::hook_get_table_manager');
        }
    }

    public static function hook_get_themes(){
        return array(
            'id' => 'theme_adminlte',
            'name' => _l('AdminLTE'),
            'base_dir' => 'includes/plugin_theme_adminlte/',
            'init_file' => 'includes/plugin_theme_adminlte/init.php', // this starts the magic
        );
    }
    public static function hook_get_table_manager(){
        require_once(module_theme::include_ucm('includes/plugin_theme_adminlte/class.table_manager.php'));
        return new ucm_adminlte_table_manager();
    }
}


