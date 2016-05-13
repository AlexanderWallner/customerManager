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

class module_webnpro_menu_module_hilfe extends module_base {
public $links;
public function init() {
$this->links = array();
$this->module_name = "webnpro_menu_module_hilfe";
$this->module_position = '20';
$this->version = '1.0';
module_config::save_config('_menu_order_webnpro_menu_module_hilfe', '20');
}

public function pre_menu() {
global $load_modules;
$this->links = array(
array(
'name' => 'Hilfe',
 'url' => 'http://helpdesk.waw-it.com',
 'icon_name' => 'bug',
 'order' => '20'
)
);
}
}

