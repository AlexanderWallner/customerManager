<?php

//Ultimate Client Manager - config file

define('_DB_SERVER','81.169.230.122');
define('_DB_NAME','om');
define('_DB_USER','test');
define('_DB_PASS','ale3wu!sbe');
define('_DB_PREFIX','om_');

define('_UCM_VERSION',2);
define('_UCM_FOLDER',preg_replace('#includes$#','',dirname(__FILE__)));
define('_UCM_SECRET','4594c62cf91c20e4e46d88565cac7130'); // change this to something unique

define('_EXTERNAL_TUNNEL','ext.php');
define('_EXTERNAL_TUNNEL_REWRITE','external/');
define('_ENABLE_CACHE',true);
define('_DEBUG_MODE',false);
define('_DEMO_MODE',false);
if(!defined('_REWRITE_LINKS'))define('_REWRITE_LINKS',false);

ini_set('display_errors',false);
ini_set('error_reporting',0);

