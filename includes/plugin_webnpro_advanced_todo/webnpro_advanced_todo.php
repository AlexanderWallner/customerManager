<?php

/*
 *  Module: webNpro Advanced Todo v2.0
 *  Copyright: Kőrösi Zoltán | webNpro - hosting and design | http://webnpro.com | info@webnpro.com
 *  Contact / Feature request: info@webnpro.com (Hungarian / English)
 *  License: Please check CodeCanyon.net for license details.
 *  More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/
 *
 *  Changelog: v
 *
 *  v2.1    -   01/03/2016  -   New feature: Archive / Restore todos
 *                              New feature: Archived todos page
 *                              New feature: todo can be assigned to all staff members (every staff member can see it)
 *                              Improved design: changed todo icons
 *                              Feature fix: The comment area saves line breaks too
 *  v2.0.4  -   01/04/2015  -   Design fix: fix the width of the todo details fields
 *                              BUGFIX: fix connection errors to the license api
 *  v2.0.3  -   12/23/2014  -   BUGFIX: dashboard widgets
 *  v2.0.2  -   11/23/2014  -   BUGFIX: show assigned todos for more than one user
 *  v2.0.1  -   11/20/2014  -   BUGFIX: permission problems on dashboard widget
 *  v2.0    -   09/29/2014  -   New feature: long descriptions for todos
 *                              New feature: info line for the todos details
 *                              New feature: todo can be assigned to domain (only the assigned staff of the assigned customer or the contact of the assigned customer can see it)
 *                              New feature: todo can be assigned to job (only the assigned staff or the contact of the jobs customer can see it)
 *                              New feature: todo can be assigned to website (only the assigned staff or customers contact can see it)
 *                              New feature: todo can be assigned to contact (only the assigned staff or contact can see it)
 *                              New feature: todo can be assigned to customer (only the assigned staff or contact can see it)
 *                              New feature: todo can be assigned to user (only the assigned user can see it)
 *                              New feature: todo can be assigned to staff (only the assigned staff can see it)
 *                              New feature: todo can be assigned to the administrator (only the administrator can see it)
 *                              New feature: Administrator can see and modify every todos
 *                              New feature: envato license validation with connection error checking to the license api
 *                              Improved feature: WhiteLabel theme dashboard widget - it's full functional from now
 *                              Improved feature: webNpro upgrade function with connection error checking to the upgrade server
 *                              Improved feature: "Read documentation" function
 *                              Improved feature: include all strings to the new UCM translation system
 *                              Feature fix: js_combine function compatibility problem is solved
 *                              Feature fix: css_combine function compatibility problem is solved
 *                              Developer info: improved documentation in the code
 *  v1.1.2	-	06/27/2014	-	BUGFIX: undefined index on line 65
 *  v1.1.1	-	06/11/2014	-	Code optimization for better performance
 *  v1.1.0	-	06/01/2014	-	BUGFIX: jQuery fix (Dashboard alert, add new todo coloring, etc.)
 * 								Small update function changes
 *  v1.0.9	-	03/25/2014	-	Fixes by dtbaker | THX!
 * 								Demo mode included
 *  v1.0.8	-	03/24/2014	-	BUGFIX: The "Calendar" problem in Metis theme is solved
 *  v1.0.7	-	03/23/2014	-	Todos colouring
 * 								Read documentation / Help buttons
 * 								Automatically upgrade function
 *  v1.0.6	-	03/11/2014	-	BUGFIX: fix the url in js/webnpro_advanced_todo.js
 *  v1.0.5	-	03/03/2014	-	Show info line on todo page and hide it on dashboard
 * 								Add todo owner to the info line if user has All todo permission
 *  v1.0.4	-	03/02/2014	-	Done / Undone function
 *  v1.0.3	-	02/23/2014	-	Todo menu | Filter by todo owner
 *  							Dashboard alert message if the plugin installation isn't complete
 *  v1.0.2	-	02/20/2014	- 	Todo menu | Own todos only
 *  v1.0.1	-	02/19/2014	- 	Mobile view | Todo sort, edit and delete disabled
 *  v1.0	-	02/13/2014	-	First release | Dashboard todos | WhiteLabel bug fix
 *
 */

define('_TODO_ACCESS_NONE', "Can't access todos");
define('_TODO_ACCESS_ALL', 'All todos in system');
define('_TODO_ACCESS_ASSIGNED_TO_ME', 'Only todos I am assigned to');
define('_TODO_ACCESS_ASSIGNED_ITEMS', 'Todos from assigned items I have access to');

/**
 * webNpro Advanced Todo module class
 */
class module_webnpro_advanced_todo extends module_base {

    public $links;
    public $customer_types;
    public $customer_id;

    /**
     * Standard UCM function for permissions checking
     *
     * @param string $actions
     * @param string $name
     * @param string $category
     * @param string $module
     * @return boolean
     */
    public static function can_i($actions, $name = false, $category = false, $module = false) {
        if (!$module)
            $module = __CLASS__;
        return parent::can_i($actions, $name, $category, $module);
        /* END public static function can_i($actions, $name = false, $category = false, $module = false) */
    }

    /**
     * Give back the class name
     *
     * @return string The class name
     */
    public static function get_class() {
        return __CLASS__;
        /* END public static function get_class() */
    }

    /**
     * Gives back true if the plugin is installed (the database table is exists)
     * @return boolean
     */
    public function is_installed() {
//$exists = mysql_query("SELECT 1 FROM `" . _DB_PREFIX . "advanced_todo` LIMIT 0");
        if (mysql_num_rows(mysql_query("SHOW TABLES LIKE '" . _DB_PREFIX . "advanced_todo'"))) {
//if ($exists)
            return true;
        } else {
            return false;
        }
        /* END public function is_installed() */
    }

    /**
     * Standard UCM function with the base module datas
     */
    public function init() {
        $this->links = array();
        $this->module_name = "webnpro_advanced_todo";
        $this->module_position = 1;
        $this->version = '2.1';

// Include todoclass.php
        require_once(dirname(__FILE__) . '/todoclass.php');

// Include the css files
        module_config::register_css('webnpro_advanced_todo', 'font-awesome.min.css', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css', 100);
        module_config::register_css('webnpro_advanced_todo', 'webnpro_advanced_todo.css');
        module_config::register_css('webnpro_advanced_todo', 'colpick.css');

// Include the js files if the active module is not the calendar
        if (isset($_REQUEST['m'])) {
            $active_module_name = $_REQUEST['m'][0];
        } else {
            $active_module_name = '';
        }

        if ($active_module_name != 'calendar') {
            module_config::register_js('webnpro_advanced_todo', 'jquery.ui.position.js');
            module_config::register_js('webnpro_advanced_todo', 'colpick.js');
        }

// Use the layout_column_half hook to insert the todo widget to the owner pages
        hook_add('layout_column_half', 'module_webnpro_advanced_todo::hook_add_todos');

        $_SESSION['is_widget'] = '0';
        /* END public function init() */
    }

    /**
     * Standard UCM function to generate the menu items
     *
     * @global $load_modules
     */
    public function pre_menu() {
        global $load_modules;

// Include the menu items only if the plugin is installed.
        if (module_webnpro_advanced_todo::is_installed()) {
            if (module_webnpro_advanced_todo::get_todo_access_permissions() != _TODO_ACCESS_NONE) {
// Menu => Todos
                $this->links[] = array(
                    "name" => "Todos",
                    "p" => "todos",
                    'icon_name' => 'flag',
                );
            }
// Menu => Settings / Advanced Todo
            if ($this->can_i('edit', 'webNpro Advanced Todo Settings', 'Config')) {
                $this->links[] = array(
                    "name" => "Advanced Todo",
                    "p" => "settings",
                    'holder_module' => 'config',
                    'holder_module_page' => 'config_admin',
                    'menu_include_parent' => 0,
                );
            }
        } else {
            $this->links[] = array(
                "name" => "Todos",
                "p" => "not_installed",
                'icon_name' => 'flag',
            );
        }
        /* END public function pre_menu() */
    }

    /**
     * Standard UCM function to create the database table for the plugin
     * @return string The SQL command
     */
    public function get_install_sql() {
        return "
	CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX . "advanced_todo` (
		`id` int(11) unsigned NOT NULL auto_increment,
		`parent_todo_id` int(11) unsigned NOT NULL default '0',
		`position` int(8) unsigned NOT NULL default '0',
		`todo_text` varchar(255) collate utf8_unicode_ci NOT NULL default '',
        `todo_details` text collate utf8_unicode_ci default '',
		`done` TINYINT( 1 ) NOT NULL DEFAULT  '0',
		`archive` TINYINT( 1 ) NOT NULL DEFAULT  '0',
		`dt_added` timestamp NOT NULL default CURRENT_TIMESTAMP,
		`customer_id` int(11) NOT NULL default '0',
		`owner_id` int(11) NOT NULL,
		`owner_table` varchar(80) NOT NULL,
		`due_time` int(11) NOT NULL,
		`dashboard_alert` TINYINT( 1 ) NOT NULL DEFAULT  '0',
		`email_reminder` TINYINT( 1 ) NOT NULL DEFAULT  '0',
		`user_id` int(11) NOT NULL DEFAULT  '0',
		`public` TINYINT( 1 ) NOT NULL DEFAULT  '0',
		`date_created` datetime NOT NULL,
		`date_updated` datetime NOT NULL,
		`create_user_id` int(11) NOT NULL,
		`update_user_id` int(11) NULL,
		`color` varchar(10) NOT NULL,
		PRIMARY KEY  (`id`),
		KEY `position` (`position`),
		KEY `todo_text` (`todo_text`),
		KEY `owner_id` (`owner_id`),
		KEY `owner_table` (`owner_table`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
	";
        /* END public function get_install_sql() */
    }

    /**
     * Standard UCM function to upgrade the database
     * @return string The SQL command
     */
    function get_upgrade_sql() {

        // We have new permissions <- TODO: I'm not sure it's the best place...
        $workaround = module_security::can_user_with_options(module_security::get_loggedin_id(), 'Todo Access', array(
            _TODO_ACCESS_NONE,
            _TODO_ACCESS_ALL,
            _TODO_ACCESS_ASSIGNED_TO_ME,
            _TODO_ACCESS_ASSIGNED_ITEMS,
        ));

        $sql = "SHOW COLUMNS from `" . _DB_PREFIX . "advanced_todo` where field='todo_details';";
        $columns = qa($sql);
        $result = (count($columns) > 0) ? "" : "ALTER TABLE `" . _DB_PREFIX . "advanced_todo` ADD `todo_details` text collate utf8_unicode_ci default '';";
        $sql = "SHOW COLUMNS from `" . _DB_PREFIX . "advanced_todo` where field='archive';";
        $columns = qa($sql);
        $result = (count($columns) > 0) ? "" : "ALTER TABLE `" . _DB_PREFIX . "advanced_todo` ADD `archive` TINYINT( 1 ) NOT NULL DEFAULT  '0';";
        $result .= "UPDATE `" . _DB_PREFIX . "advanced_todo` SET owner_table='user', owner_id='" . module_security::get_loggedin_id() . "', date_updated='" . date("Y-m-d H:i:s", time()) . "', update_user_id='" . module_security::get_loggedin_id() . "' WHERE owner_id='';";
        return $result;
    }

    /**
     * Get the todo access permissions of the logged in user
     * @return string The access type
     */
    public static function get_todo_access_permissions() {
        if (class_exists('module_security', false)) {
            if (module_security::get_loggedin_id() == '1') {
                return _TODO_ACCESS_ALL;
            } else {
                return module_security::can_user_with_options(module_security::get_loggedin_id(), 'Todo Access', array(
                            _TODO_ACCESS_NONE,
                            _TODO_ACCESS_ALL,
                            _TODO_ACCESS_ASSIGNED_TO_ME,
                            _TODO_ACCESS_ASSIGNED_ITEMS,
                ));
            }
        } else {
            return _TODO_ACCESS_ALL; // default to all permissions.
        }
    }

    /**
     * Standard UCM function to handle hooks
     *
     * @param string $hook
     * @param boolean $mod
     * @return string
     */
    public function handle_hook($hook, $mod = false) {
        switch ($hook) {
            case 'dashboard_widgets':
                if (module_webnpro_advanced_todo::is_installed() &&
                        (module_webnpro_advanced_todo::get_todo_access_permissions() != _TODO_ACCESS_NONE)) {
                    if (get_display_mode() != 'mobile') {
                        include_once(dirname(__FILE__) . '/pages/dashboard_widgets.php');
                        return $todowidgets;
                    }
                }
                break;
        }
        /* END public function handle_hook($hook, $mod = false) */
    }

    /**
     * Insert the todo plugin to the edit pages
     * @param type $callback_name
     * @param type $data
     */
    public static function hook_add_todos($callback_name, $data) {
        if (isset($_REQUEST['m']) && ($data == 2) &&
                (module_webnpro_advanced_todo::get_todo_access_permissions() != _TODO_ACCESS_NONE) &&
                (module_webnpro_advanced_todo::get_todo_access_permissions() != _TODO_ACCESS_ASSIGNED_TO_ME)) {
            $active_module_name = isset($_REQUEST['m'][1]) ? $_REQUEST['m'][1] : $_REQUEST['m'][0];
            switch ($active_module_name) {
                case 'user':
                    $_SESSION['active_page'] = 'user.user_admin';
                    $_SESSION['owner_table'] = 'user';
                    $_SESSION['owner_id'] = $_GET['user_id'];
                    include(dirname(__FILE__) . '/pages/todos_widget.php');
                    break;
                case 'customer':
                    $_SESSION['active_page'] = 'customer.customer_admin_open';
                    $_SESSION['owner_table'] = 'customer';
                    $_SESSION['owner_id'] = $_GET['customer_id'];
                    include(dirname(__FILE__) . '/pages/todos_widget.php');
                    break;
                case 'contact':
                    $_SESSION['active_page'] = 'user.contact_admin';
                    $_SESSION['owner_table'] = 'customer';
                    $_SESSION['owner_id'] = $_GET['customer_id'];
                    include(dirname(__FILE__) . '/pages/todos_widget.php');
                    break;
                case 'website':
                    $_SESSION['active_page'] = 'website.website_admin';
                    $_SESSION['owner_table'] = 'website';
                    $_SESSION['owner_id'] = $_GET['website_id'];
                    include(dirname(__FILE__) . '/pages/todos_widget.php');
                    break;
                case 'job':
                    $_SESSION['active_page'] = 'job.job_admin';
                    $_SESSION['owner_table'] = 'job';
                    $_SESSION['owner_id'] = $_GET['job_id'];
                    include(dirname(__FILE__) . '/pages/todos_widget.php');
                    break;
                case 'domain':
                    $_SESSION['active_page'] = 'domain.domain_admin';
                    $_SESSION['owner_table'] = 'domain';
                    $_SESSION['owner_id'] = $_GET['domain_id'];
                    include(dirname(__FILE__) . '/pages/todos_widget.php');
                    break;
            }
        }
    }

    /**
     * Filter the todos set
     *
     * @param array $todos
     * @return array
     */
    public static function filter_todos($todos) {

        $access_todos = module_webnpro_advanced_todo::get_todo_access_permissions();

        if ($access_todos == _TODO_ACCESS_NONE) {
            return array();
        }
        $loggedin_id = module_security::get_loggedin_id();
        $loggedin_is_staff = module_user::is_staff_member($loggedin_id);
        if (($loggedin_id == 1) || ($access_todos == _TODO_ACCESS_ALL)) {
// The logged in user is the Administrator or has access to all todos
            $result = $todos;
        } else {
// The logged is user is not the Administrator
            if (is_array($todos)) {
                $todoids = array();
                foreach ($todos as $todoitem) {
                    if ($access_todos == _TODO_ACCESS_ASSIGNED_TO_ME) {
                        if ((($todoitem['owner_table'] == 'user') && (($todoitem['owner_id'] == $loggedin_id) || (($loggedin_is_staff) && ($todoitem['owner_id'] == '99999999')))))
                        {
                            if (!in_array($todoitem['id'], $todoids)) {
                                $result[] = $todoitem;
                                $todoids[] = $todoitem['id'];
                            }
                        }
                    } else {
                        switch ($todoitem['owner_table']) {
                            case 'user' :
                                if (($todoitem['owner_id'] == $loggedin_id) || (($loggedin_is_staff) && ($todoitem['owner_id'] == '99999999'))) {
                                    if (!in_array($todoitem['id'], $todoids)) {
                                        $result[] = $todoitem;
                                        $todoids[] = $todoitem['id'];
                                    }
                                }
                                $user = get_single('user', 'user_id', $todoitem['owner_id']);
                                $customer_rels = get_multiple('customer_user_rel', array('customer_id' => $user['customer_id']));
                                foreach ($customer_rels as $customer_rel) {
                                    if ($customer_rel['user_id'] == $loggedin_id) {
                                        if (!in_array($todoitem['id'], $todoids)) {
                                            $result[] = $todoitem;
                                            $todoids[] = $todoitem['id'];
                                        }
                                    }
                                }
                                break;
                            case 'customer' :
                                $customer_rels = get_multiple('customer_user_rel', array('customer_id' => $todoitem['owner_id']));
                                foreach ($customer_rels as $customer_rel) {
                                    if (($customer_rel['user_id'] == $loggedin_id)) {
                                        if (!in_array($todoitem['id'], $todoids)) {
                                            $result[] = $todoitem;
                                            $todoids[] = $todoitem['id'];
                                        }
                                    }
                                    $contact_rels = get_multiple('user', array('customer_id' => $customer_rel['customer_id']));
                                    foreach ($contact_rels as $contact_rel) {
                                        if (($contact_rel['user_id'] == $loggedin_id)) {
                                            if (!in_array($todoitem['id'], $todoids)) {
                                                $result[] = $todoitem;
                                                $todoids[] = $todoitem['id'];
                                            }
                                        }
                                    }
                                }
                                break;
                            case 'website' :
                                $website_rel = get_single('website', 'website_id', $todoitem['owner_id']);
                                $customer_rels = get_multiple('customer_user_rel', array('customer_id' => $website_rel['customer_id']));
                                foreach ($customer_rels as $customer_rel) {
                                    if (($customer_rel['user_id'] == $loggedin_id)) {
                                        if (!in_array($todoitem['id'], $todoids)) {
                                            $result[] = $todoitem;
                                            $todoids[] = $todoitem['id'];
                                        }
                                    }
                                    $contact_rels = get_multiple('user', array('customer_id' => $customer_rel['customer_id']));
                                    foreach ($contact_rels as $contact_rel) {
                                        if (($contact_rel['user_id'] == $loggedin_id)) {
                                            if (!in_array($todoitem['id'], $todoids)) {
                                                $result[] = $todoitem;
                                                $todoids[] = $todoitem['id'];
                                            }
                                        }
                                    }
                                }

                                break;
                            case 'job' :
                                $job_rel = get_single('job', 'job_id', $todoitem['owner_id']);
                                if ($job_rel['user_id'] == $loggedin_id) {
                                    if (!in_array($todoitem['id'], $todoids)) {
                                        $result[] = $todoitem;
                                        $todoids[] = $todoitem['id'];
                                    }
                                }
                                $customer_rel1s = get_multiple('customer_user_rel', array('customer_id' => $job_rel['customer_id']));
                                foreach ($customer_rel1s as $customer_rel1) {
                                    $user_rel1s = get_multiple('user', array('user_id' => $customer_rel1['user_id']));
                                    foreach ($user_rel1s as $user_rel1) {
                                        if ($user_rel1['user_id'] == $loggedin_id) {
                                            if (!in_array($todoitem['id'], $todoids)) {
                                                $result[] = $todoitem;
                                                $todoids[] = $todoitem['id'];
                                            }
                                        }
                                    }
                                    $contact_rels1 = get_multiple('user', array('customer_id' => $customer_rel1['customer_id']));
                                    foreach ($contact_rel1s as $contact_rel1) {
                                        if (($contact_rel1['user_id'] == $loggedin_id)) {
                                            if (!in_array($todoitem['id'], $todoids)) {
                                                $result[] = $todoitem;
                                                $todoids[] = $todoitem['id'];
                                            }
                                        }
                                    }
                                }
                                $website_rel = get_single('website', 'website_id', $job_rel['website_id']);
                                $customer_rel2s = get_multiple('customer_user_rel', array('customer_id' => $job_rel['customer_id']));
                                foreach ($customer_rel2s as $customer_rel2) {
                                    $user_rel2s = get_multiple('user', array('user_id' => $customer_rel2['user_id']));
                                    foreach ($user_rel2s as $user_rel2) {
                                        if ($user_rel2['user_id'] == $loggedin_id) {
                                            if (!in_array($todoitem['id'], $todoids)) {
                                                $result[] = $todoitem;
                                                $todoids[] = $todoitem['id'];
                                            }
                                        }
                                    }
                                    $contact_rels2 = get_multiple('user', array('customer_id' => $customer_rel2['customer_id']));
                                    foreach ($contact_rel2s as $contact_rel2) {
                                        if (($contact_rel2['user_id'] == $loggedin_id)) {
                                            if (!in_array($todoitem['id'], $todoids)) {
                                                $result[] = $todoitem;
                                                $todoids[] = $todoitem['id'];
                                            }
                                        }
                                    }
                                }

                                break;
                            case 'domain':
                                $domain_rel = get_single('domain', 'domain_id', $todoitem['owner_id']);

                                $customer_rel1s = get_multiple('customer_user_rel', array('customer_id' => $domain_rel['customer_id']));
                                foreach ($customer_rel1s as $customer_rel1) {
                                    $user_rel1s = get_multiple('user', array('user_id' => $customer_rel1['user_id']));
                                    foreach ($user_rel1s as $user_rel1) {
                                        if ($user_rel1['user_id'] == $loggedin_id) {
                                            if (!in_array($todoitem['id'], $todoids)) {
                                                $result[] = $todoitem;
                                                $todoids[] = $todoitem['id'];
                                            }
                                        }
                                    }
                                    $contact_rels1 = get_multiple('user', array('customer_id' => $customer_rel1['customer_id']));
                                    foreach ($contact_rel1s as $contact_rel1) {
                                        if (($contact_rel1['user_id'] == $loggedin_id)) {
                                            if (!in_array($todoitem['id'], $todoids)) {
                                                $result[] = $todoitem;
                                                $todoids[] = $todoitem['id'];
                                            }
                                        }
                                    }
                                }
                                $website_rel = get_single('website', 'website_id', $domain_rel['website_id']);
                                $customer_rel2s = get_multiple('customer_user_rel', array('customer_id' => $job_rel['customer_id']));
                                foreach ($customer_rel2s as $customer_rel2) {
                                    $user_rel2s = get_multiple('user', array('user_id' => $customer_rel2['user_id']));
                                    foreach ($user_rel2s as $user_rel2) {
                                        if ($user_rel2['user_id'] == $loggedin_id) {
                                            if (!in_array($todoitem['id'], $todoids)) {
                                                $result[] = $todoitem;
                                                $todoids[] = $todoitem['id'];
                                            }
                                        }
                                    }
                                    $contact_rels2 = get_multiple('user', array('customer_id' => $customer_rel2['customer_id']));
                                    foreach ($contact_rel2s as $contact_rel2) {
                                        if (($contact_rel2['user_id'] == $loggedin_id)) {
                                            if (!in_array($todoitem['id'], $todoids)) {
                                                $result[] = $todoitem;
                                                $todoids[] = $todoitem['id'];
                                            }
                                        }
                                    }
                                }
                                break;
                        }
                    }
                }
            }
        }
        return $result;
        /* END public static function filter_todos($todos) */
    }

    /**
     * Get todos from the database
     *
     * @param array $search
     * @param array $return_options
     * @return array
     */
    public static function get_todos($search = array(), $return_options = array()) {
        $sql = "SELECT *";
        $from = "FROM `" . _DB_PREFIX . "advanced_todo`";
        $where = " WHERE 1 ";

        if (isset($search['todo_id'])) {
            $where .= " AND ( ";
            $where .= "todo_id LIKE '%" . $search['todo_id'] . "%' ";
            $where .= ' ) ';
        }

        if ((!isset($search['archive'])) || ((isset($search['archive'])) && ($search['archive'] == '0'))) {
            $where .= " AND ( ";
            $where .= "archive <> 1";
            $where .= ' ) ';
        } else {
            $where .= " AND ( ";
            $where .= "archive = 1";
            $where .= ' ) ';
        }

        if (isset($search['active_page']) && $search['active_page'] <> 'todo.main') {
            if (isset($search['owner_table'])) {
                $where .= " AND ( ";
                $where .= "owner_table LIKE '%" . $search['owner_table'] . "%' ";
                $where .= ' ) ';
            }

            if (isset($search['owner_id'])) {
                $where .= " AND ( ";
                $where .= "owner_id LIKE '%" . $search['owner_id'] . "%' ";
                $where .= ' ) ';
            }
        }

        if (isset($search['generic']) && $search['generic']) {
            $str = mysql_real_escape_string($search['generic']);
            $where .= " AND ( ";
            $where .= " todo_text LIKE '%$str%' ";
            $where .= ' ) ';
        }

        $group_order = "ORDER BY `position` ASC";

        $sql = $sql . $from . $where . $group_order;
        $result = qa($sql);
        $result = module_webnpro_advanced_todo::filter_todos($result);

        return $result;
        /* END public static function get_todos($search = array(), $return_options = array()) */
    }

    /* END class module_webnpro_advanced_todo */
}
