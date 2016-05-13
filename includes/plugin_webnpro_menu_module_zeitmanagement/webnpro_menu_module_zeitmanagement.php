<?php

/*
 *  Module: webNpro Menu Editor v1.0
 *  Copyright: Kőrösi Zoltán | webNpro - hosting and design | http://webnpro.com | info@webnpro.com
 *  Contact / Feature request: info@webnpro.com (Hungarian / English)
 *  License: Please check CodeCanyon.net for license details.
 *  More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/
 *
 *  THIS IS A CUSTOM MENU ITEM MODULE CREATED BY THE MENU EDITOR PLUGIN
 */

class module_webnpro_menu_module_zeitmanagement extends module_base {
public $links;
public function init() {
$this->links = array();
$this->module_name = "webnpro_menu_module_zeitmanagement";
$this->module_position = '15';
$this->version = '1.0';
module_config::save_config('_menu_order_webnpro_menu_module_zeitmanagement', '15');
}

public function pre_menu() {
global $load_modules;
$this->links = array(
array(
'name' => 'Zeitmanagement',
 'url' => 'http://waw-it.com/tm',
 'icon_name' => 'tachometer',
 'order' => '15'
)
);
}
}

