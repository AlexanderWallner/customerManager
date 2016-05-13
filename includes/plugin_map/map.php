<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55, 92d36d3b-0276-47ef-93d8-00df5dc17d5e, 3dac064b-a466-4931-93f0-51c086616894, 0b59530f-164b-4ec2-975b-6a3038423838, 7c3ba354-e0b5-4f02-8a97-854fab16f4b1, c1fe4c24-77fe-44eb-a399-426ebdf540b9, ef74c493-5050-4b88-9be1-92e54c0e34aa, a59dba67-f1e1-4085-943c-c3ded9fc5667, c46a4f2a-d54d-4778-9c2d-c61f41691e34, 68ccf1c5-a309-443a-b04e-69d266cb348f
  * Package Date: 2015-03-08 05:40:21 
  * IP Address: 88.217.180.200
  */



class module_map extends module_base{

	public $links;
	public $map_types;

    public static function can_i($actions,$name=false,$category=false,$module=false){
        if(!$module)$module=__CLASS__;
        return parent::can_i($actions,$name,$category,$module);
    }
	public static function get_class() {
        return __CLASS__;
    }
	public function init(){
		$this->links = array();
		$this->map_types = array();
		$this->module_name = "map";
		$this->module_position = 14;
        $this->version = 2.21;
        //2.21 - 2015-09-10 - map marker fix
        //2.2 - 2015-09-09 - map marker fix
        //2.1 - 2015-06-10 - initial release

		// the link within Admin > Settings > Maps.
        if(module_security::has_feature_access(array(
				'name' => 'Settings',
				'module' => 'config',
				'category' => 'Config',
				'view' => 1,
				'description' => 'view',
		))){
            $this->links[] = array(
                "name"=>"Maps",
                "p"=>"map_settings",
                'holder_module' => 'config', // which parent module this link will sit under.
                'holder_module_page' => 'config_admin',  // which page this link will be automatically added to.
                'menu_include_parent' => 0,
            );
        }


        if($this->can_i('view','Maps') && module_config::c('enable_customer_maps',1) && module_map::is_plugin_enabled()){

            // only display if a customer has been created.
            if(isset($_REQUEST['customer_id']) && $_REQUEST['customer_id'] && $_REQUEST['customer_id']!='new'){
                // how many maps?
                $name = 'Maps';
                $this->links[] = array(
                    "name"=>$name,
                    "p"=>"map_admin",
                    'args'=>array('map_id'=>false),
                    'holder_module' => 'customer', // which parent module this link will sit under.
                    'holder_module_page' => 'customer_admin_open',  // which page this link will be automatically added to.
                    'menu_include_parent' => 0,
                    'icon_name' => 'globe',
                );
            }
            $this->links[] = array(
                "name"=>'Maps',
                "p"=>"map_admin",
                'args'=>array('map_id'=>false),
                'icon_name' => 'globe',
            );

        }
		
	}

    public function process()
    {
        if (isset($_REQUEST['_process']) && $_REQUEST['_process'] == 'ajax_save_map_coords'){

            $address_id = (int)$_REQUEST['address_id'];
            if($address_id && !empty($_REQUEST['address_hash']) && !empty($_REQUEST['lat']) && !empty($_REQUEST['lng'])){
                // existing?
                $existing = get_single('map','address_id',$address_id);
                update_insert('map_id',$existing ? $existing['map_id'] : false, 'map', array(
                    'address_hash' => $_REQUEST['address_hash'],
                    'address_id' => $_REQUEST['address_id'],
                    'lat' => $_REQUEST['lat'],
                    'lng' => $_REQUEST['lng'],
                ));
            }
            echo 'Done';
            exit;
        }
    }
    public function get_install_sql(){
        ob_start();
        ?>

CREATE TABLE `<?php echo _DB_PREFIX; ?>map` (
  `map_id` int(11) NOT NULL auto_increment,
  `address_hash` varchar(255) NOT NULL DEFAULT '',
  `address_id` int(11) NOT NULL DEFAULT  '0',
  `lat` varchar(255) NOT NULL DEFAULT  '',
  `lng` varchar(255) NOT NULL DEFAULT  '',
  `date_created` date NULL,
  `date_updated` date NULL,
  PRIMARY KEY  (`map_id`),
  KEY (`address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    <?php

        return ob_get_clean();
    }

}