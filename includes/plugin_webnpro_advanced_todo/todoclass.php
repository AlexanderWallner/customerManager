<?php

/*
 *  Module: webNpro Advanced Todo v2.0
 *  Copyright: Kőrösi Zoltán | webNpro - hosting and design | http://webnpro.com | info@webnpro.com
 *  Contact / Feature request: info@webnpro.com (Hungarian / English)
 *  License: Please check CodeCanyon.net for license details.
 *  More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/
 */

/**
 * ToDo class for the Advanced Todo plugin
 */
class ToDo {

    // $data: array with the todos parameters
    private $data;

    /**
     * The constructor
     *
     * @param array $parameters
     */
    public function __construct($parameters) {
        if (is_array($parameters))
            $this->data = $parameters;
        /* END public function __construct($parameters) */
    }

    /**
     * Make html version from the todos
     *
     * @return string
     */
    public function __toString() {
        $content = "";

        // Set defaults
        if (!isset($this->data['bgcolor'])) {
            $this->data['bgcolor'] = '';
        }
        if (!isset($this->data['new'])) {
            $this->data['new'] = false;
        }
        if (!isset($this->data['done'])) {
            $this->data['done'] = false;
        }
        if (!isset($this->data['color'])) {
            $this->data['color'] = '';
        }
        if (!isset($this->data['details'])) {
            $this->data['details'] = '';
        }

        // Is the todo done? $is_done used for the css class
        if ($this->data['done'] == '1') {
            $done = true;
            $is_done = ' done';
        } else {
            $done = false;
            $is_done = ' undone';
        };

        // Is the todo archived? $is_archive used for the css class
        if ($this->data['archive'] == '1') {
            $archive = true;
            $is_archive = ' archive';
            $is_done = '';
        } else {
            $archive = false;
            $is_archive = ' live';
        };

        if (get_display_mode() == 'mobile') {
            // Mobile view
            // TODO TODO-fontos: improve Mobile mode
            $content = '<li id="todo-' . $this->data['id'] . '" class="todo' . $is_done . '" style="color: ' . $this->data['color'] . ';background-color: ' . $this->data['background-color'] . ';">' . ToDo::strip_text($this->data['todo_text']) . '</li>';
        } else {
            // We have all the infos, let's go
            // 1st the <li> - done / undone - background color
            $content = '<li id="todo-' . $this->data['id'] . '" class="todo' . $is_done . $is_archive. '" style="background-color: ' . $this->data['bgcolor'] . ';">';
            // 2nd the todo-inner <div> - todo color rectangle
            $content .= '<div class="todo-inner" id="todo-inner" style="float: left; height: 36px; border-left: 10px solid ' . $this->data['color'] . ';">&nbsp;</div>';
            // 3rd the text <div> - text color - todo text
            $content .= '<div class="text" style="color: ' . $this->data['color'] . ';"><div class="onerow">' . stripslashes(ToDo::strip_text($this->data['todo_text'])) . '</div></div>';

            // We didn't finished we need to make infoline
            $infoline = '';

            $owner = array();
            // If this todo is a new todo assign it to the current user
            if (!$this->data['new']) {
                $owner = module_user::get_user($this->data['user_id']);
            } else {
                $owner = module_user::get_user(module_security::get_loggedin_id());
            }

            $assigned = get_single($this->data['owner_table'], $this->data['owner_table'] . '_id', $this->data['owner_id']);

            $usertype = '';
            switch ($this->data['owner_table']) {
                case 'user':
                    $usertype = ($assigned['customer_id'] > 0) ? 'contact' : 'user';
                    // 99999999 owner_id = all staff members
                    $usertype = (($assigned['is_staff'] == 1) || ($this->data['owner_id'] == '99999999')) ? 'staff' : $usertype;
                    $usertype = ($assigned['user_id'] == 1) ? 'administrator' : $usertype;
                    $assigned['name'] = ($this->data['owner_id'] == '99999999') ? _l('Staff Members') : $assigned['name'];
                    $assigned['last_name'] = ($this->data['owner_id'] == '99999999') ? '' : $assigned['last_name'];
                    $infoline .= _l('Assigned') . ' ' . _l($usertype) . ': <a style="color: ' . $this->data['color'] . ';" href="' . call_user_func_array(array('module_' . $this->data['owner_table'], 'link_open'), array($this->data['owner_id'])) . '">' . $assigned['name'] . ' ' . $assigned['last_name'] . "</a>";
                    break;
                case 'customer':
                    $infoline .= _l('Assigned') . ' ' . _l(($assigned['type'] == 0) ? 'customer' : 'lead') . ': <a style="color: ' . $this->data['color'] . ';" href="' . call_user_func_array(array('module_' . $this->data['owner_table'], 'link_open'), array($this->data['owner_id'])) . '">' . $assigned['customer_name'] . "</a>";
                    break;
                default:
                    $infoline .= _l('Assigned') . ' ' . _l($this->data['owner_table']) . ': <a style="color: ' . $this->data['color'] . ';" href="' . (is_callable(array('module_' . $this->data['owner_table'], 'link_open')) ? call_user_func_array(array('module_' . $this->data['owner_table'], 'link_open'), array($this->data['owner_id'])) : '#' ) . '">' . (isset($assigned['name']) ? $assigned['name'] : 'N/A') . "</a>";
            }

            $update_user = get_single('user', 'user_id', $this->data['update_user_id']);
            $update_user_txt = 'Updated by:';
            $date_updated = $this->data['date_updated'];
            $date_updated_txt = 'Updated at:';
            if (!$update_user) {
                $update_user = get_single('user', 'user_id', $this->data['create_user_id']);
                $update_user_txt = 'Created by:';
                $date_updated = $this->data['date_created'];
                $date_updated_txt = 'Created at:';
            }
            if (!$update_user) {
                $update_user = get_single('user', 'user_id', $this->data['user_id']);
                $update_user_txt = 'Created by:';
                $date_updated = $this->data['date_created'];
                $date_updated_txt = 'Created at:';
            }

            $content .= '<div class="infoline" id="infoline" style="color: ' . $this->data['color'] . ';"><span class="owner" ownertable="' . $this->data["owner_table"] . '" ownerid="' . $this->data['owner_id'] . '" ownertype="' . (($usertype == '') ? $this->data['owner_table'] : $usertype) . '">' . $infoline . '</span>';
            $content .= '</div>';

            $this->data['new'] = false;
            $content .= '<div class="actions">';

            $user_id = module_security::get_loggedin_id();


            $content .= !$archive ? '<a href="#done.undone" class="done fa fa-check-square-o"> Done</a>' : '';
            $content .= !$archive ? '<a href="#edit.todo" class="edit fa fa-pencil-square-o"> Edit</a>' : '';
            $moreclass = (isset($this->data['todo_details']) && $this->data['todo_details'] != '') ? 'more' : 'nomore';
            $content .= '<a href="#todo.details" class="morebtn ' . $moreclass . ' fa fa-file-text-o"> More</a>';
            $archiveclass = (isset($this->data['archive']) && $this->data['archive'] == '1') ? 'restore' : 'archive';
            $content .= '<a href="#archive.todo" class="' . $archiveclass . ' fa fa-file-archive-o"> Archive / Restore</a>';
            $content .= '<a href="#delete.todo" class="delete fa fa-trash-o"> Delete</a>';
            $content .= '	</div>';
            $content .= '<div class="detailsline" style="display: none; margin-top: 10px; padding-top: 5px; border-top: 1px dotted ' . $this->data['color'] . ';">';
            $content .= '<span class="updatedby" style="padding-left:10px;">' . _l($update_user_txt) . ' <a href="' . call_user_func_array(array('module_user', 'link_open'), array($this->data['update_user_id'])) . '">' . $update_user['name'] . ' ' . $update_user['last_name'] . '</a></span>';
            $content .= '<span class="updatedate" style="padding-left:10px;">' . _l($date_updated_txt) . ' ' . print_date($date_updated, true) . '</span>';
            $content .= '</div>';
            $content .= '<div class="details editable" style="display: none; margin-top: 5px; padding-top: 5px; border-top: 1px dotted ' . $this->data['color'] . ';">' . (isset($this->data['todo_details']) ? $this->data['todo_details'] : '') . '</div>';
        }
        return $content;
        /* END public function __toString() */
    }

    /**
     * Change the todo color - Used in ajax call
     * @param integer $id Todo id
     * @param string $color Hex code of the new color
     */
    public static function changecolor($id, $color) {

        if (!_DEMO_MODE) {
            $sql = "UPDATE `" . _DB_PREFIX . "advanced_todo` SET color='#" . $color . "' WHERE id=" . $id;
            query($sql);
        }
        /* END  public static function changecolor($id, $color) */
    }

    /**
     * Todo done / undone function - Used in ajax call
     * @param integer $id Todo id
     * @param boolean $done
     */
    public static function done($id, $done) {
        $date_updated = date("Y-m-d H:i:s", time());
        $update_user_id = module_security::get_loggedin_id();
        if (!_DEMO_MODE) {
            $sql = "UPDATE `" . _DB_PREFIX . "advanced_todo` SET done='" . $done . "', date_updated='" . $date_updated . "', update_user_id='" . $update_user_id . "' WHERE id=" . $id;
            query($sql);
        }
        /* END public static function done($id, $done) */
    }

    /**
     * Todo archive / restore function - Used in ajax call
     * @param integer $id Todo id
     * @param boolean $done
     */
    public static function archive($id, $archive) {
        $date_updated = date("Y-m-d H:i:s", time());
        $update_user_id = module_security::get_loggedin_id();
        if (!_DEMO_MODE) {
            $sql = "UPDATE `" . _DB_PREFIX . "advanced_todo` SET archive='" . $archive . "', date_updated='" . $date_updated . "', update_user_id='" . $update_user_id . "' WHERE id=" . $id;
            query($sql);
        }
        /* END public static function done($id, $done) */
    }

    /**
     * Edit todo - Used in ajax call
     * @param integer $id Todo id
     * @param string $text Short description / Title
     * @param string $details Long description
     * @param string $owner_table
     * @param integer $owner_id
     */
    public static function edit($id, $text, $details, $owner_table, $owner_id) {
        $owner_table = (in_array($owner_table, array('administrator', 'staff', 'user', 'contact'))) ? 'user' : $owner_table;
        $owner_table = (in_array($owner_table, array('lead'))) ? 'customer' : $owner_table;
        $update_user_id = module_security::get_loggedin_id();
        $update_user = get_single('user', 'user_id', $update_user_id);
        $update_user_txt = 'Updated by:';
        $date_updated = date("Y-m-d H:i:s", time());
        $date_updated_txt = 'Updated at:';
        // Remove all html tags from the title
        $text = ToDo::strip_text($text);
        // Remove some html tags from the description
        $details = ToDo::strip_details($details);

        // If demo mode is enabled we won't save
        if (!_DEMO_MODE) {
            if ($owner_id != '') {
                $sql = "UPDATE `" . _DB_PREFIX . "advanced_todo` SET todo_text='" . $text . "', todo_details='" . $details . "', owner_table='" . $owner_table . "', owner_id='" . $owner_id . "', date_updated='" . $date_updated . "', update_user_id='" . $update_user_id . "' WHERE id=" . $id;
                query($sql);
            } else {
                $sql = "UPDATE `" . _DB_PREFIX . "advanced_todo` SET todo_text='" . $text . "', todo_details='" . $details . "', date_updated='" . $date_updated . "', update_user_id='" . $update_user_id . "' WHERE id=" . $id;
                query($sql);
            }
        }

        // Todo recreation with the edited and saved parameters
        $todo = get_single('advanced_todo', 'id', $id);
        $content = new ToDo($todo);
        echo $content;
        exit;
        /* END public static function edit($id, $text, $details, $owner_table, $owner_id) */
    }

    /**
     * Delete todo - Used in ajax call
     * @param integer $id Todo id
     */
    public static function delete($id) {
        // If demo mode is enabled we won't delete the todo
        if (!_DEMO_MODE) {
            delete_from_db('advanced_todo', 'id', $id);
        }
        /* END public static function delete($id) */
    }

    /**
     * Rearrange the todos - Used in ajax call
     * @param integer $key_value Todo ids
     */
    public static function rearrange($key_value) {
        if (!_DEMO_MODE) {
            $updateVals = array();
            foreach ($key_value as $k => $v) {
                $strVals[] = 'WHEN ' . (int) $v . ' THEN ' . ((int) $k + 1) . PHP_EOL;
            }
            $sql = "UPDATE `" . _DB_PREFIX . "advanced_todo` SET position = CASE id
							" . join($strVals) . "
							ELSE position
							END";
            query($sql);
        }
        /* END public static function rearrange($key_value) */
    }

    /**
     * Create a new todo - Used in ajax call
     * @param string $owner_table
     * @param string $owner_id
     */
    public static function createNew($owner_table = '', $owner_id = '') {

        // Get the owner_table from session if it's not defined in parameters
        if (!$owner_table) {
            $owner_table = isset($_SESSION['owner_table']) ? $_SESSION['owner_table'] : 'user';
        }
        // Get the owner_id from session if it's not defined in parameters
        if (!$owner_id) {
            $owner_id = isset($_SESSION['owner_id']) ? $_SESSION['owner_id'] : '';
        }
        if ($owner_table == 'user') {
            if (!$owner_id) {
                $owner_id = module_security::get_loggedin_id();
            }
        } else {
            if (!$owner_id) {
                $owner_table = 'user';
                $owner_id = module_security::get_loggedin_id();
            }
        }
        // Get the current user id
        $user_id = module_security::get_loggedin_id();

        // Default todo text
        $text = _l('New TODO item');

        // Get the position of the new todo
        $posResult = query("SELECT MAX(position)+1 FROM `" . _DB_PREFIX . "advanced_todo`");
        if (mysql_num_rows($posResult))
            list($position) = mysql_fetch_array($posResult);
        if (!$position)
            $position = 1;

        // Set the creation date and time
        $date_created = date("Y-m-d H:i:s", time());

        // If demo mode is enabled, we won't save the new todo in the database
        if (_DEMO_MODE) {
            list($todo_id) = '999';
        } else {
            // Save the new todo to the database
            query("INSERT INTO `" . _DB_PREFIX . "advanced_todo` SET todo_text='" . $text . "', user_id = '" . $user_id . "', create_user_id = '" . $user_id . "', date_created = '" . $date_created . "', owner_table = '" . $owner_table . "', owner_id = '" . $owner_id . "', position = " . $position);
            $new_id = query("SELECT MAX(id) FROM `" . _DB_PREFIX . "advanced_todo`");
            if (mysql_num_rows($new_id))
                list($todo_id) = mysql_fetch_array($new_id);
            if (!$todo_id)
                $todo_id = 1;
        }

        // Print out the new todo
        echo (new ToDo(array(
    'id' => $todo_id,
    'todo_text' => $text,
    'owner_table' => $owner_table,
    'owner_id' => $owner_id,
    'create_user_id' => $user_id,
    'new' => true
        )));

        exit;
        /* END public static function createNew($owner_table = '', $owner_id = '') */
    }

    /**
     * Strinp the html tags from the $text
     * @param string $text
     * @return string
     */
    public static function strip_text($text) {
        if (ini_get('magic_quotes_gpc'))
            $text = stripslashes($text);
        return mysql_real_escape_string(strip_tags($text));
        /* END public static function strip_text($text) */
    }

    /**
     * Strip selected html tags from the $details - NOT IN USE
     * @param string $details
     * @return string
     */
    public static function strip_details($details) {
       $details = nl2br($details);
//        if (ini_get('magic_quotes_gpc'))
//            $details = stripslashes($details);
        $details = str_replace("'","''", strip_tags($details,'<br>'));
        return $details;
        /* END public static function strip_details($details) */
    }

    /**
     * Print the owner tables dropdown - Used in ajax call
     * @param integer $id Todo id
     * @param string $owner_table
     */
    public function get_owner_tables($id, $owner_table) {
        $options = array('administrator', 'user', 'staff', 'customer', 'lead', 'contact', 'website', 'job', 'domain');
        echo _l('Assigned') . ' ';
        echo '<select id="owner_tables" class="owner_tables">';
        echo '<option value="">' . _l('Select one...') . '</option>';
        foreach ($options as $option) {
            echo '<option value="' . $option . '"' . (($owner_table == $option) ? ' selected = "selected"' : '' ) . '>' . _l($option) . '</option>';
        }
        echo '</select>';
        exit;
        /* END public function get_owner_tables($id, $owner_table) */
    }

    /**
     * Print the owners dropdown based on owner table - Used in ajax call
     * @param integer $id Todo id
     * @param string $owner_table
     * @param integer $owner_id
     */
    public function get_owners($id, $owner_table, $owner_id) {
        echo ' : <select id="owner_ids" class="owner_ids"><option value="">--- ' . _l('Select') . ' ' . _l($owner_table) . ' ---</option>';
        switch ($owner_table) {
            case 'customer':
                $customers = module_customer::get_customers(array('type' => '0'));
                foreach ($customers as $customer) {
                    echo '<option value="' . $customer['customer_id'] . '"' . ((count($customers) == 1) || (($owner_id == $customer['customer_id'])) ? ' selected = "selected"' : '' ) . '">' . $customer['customer_name'] . '</option>';
                };
                break;
            case 'lead':
                $customers = module_customer::get_customers(array('type' => '1'));
                foreach ($customers as $customer) {
                    echo '<option value="' . $customer['customer_id'] . '"' . ((count($customers) == 1) || (($owner_id == $customer['customer_id'])) ? ' selected = "selected"' : '' ) . '">' . $customer['customer_name'] . '</option>';
                };
                break;
            case 'administrator':
                $user = module_user::get_user('1');
                if ($user['name'] != "") {
                    echo '<option value="' . $user['user_id'] . '" selected = "selected">' . $user['name'] . ' ' . $user['last_name'] . '</option>';
                }
                break;
            case 'staff':
                $users = module_user::get_users(array('is_staff' => '1'));
                echo '<option value="99999999"' . (($owner_id == '99999999') ? ' selected = "selected"' : '' ) . '">' . _l('Staff Members') . '</option>';
                foreach ($users as $user) {
                    echo '<option value="' . $user['user_id'] . '"' . ((count($users) == 1) || (($owner_id == $user['user_id'])) ? ' selected = "selected"' : '' ) . '">' . $user['name'] . ' ' . $user['last_name'] . '</option>';
                };
                break;
            case 'contact':
                $contacts = module_user::get_contacts();
                foreach ($contacts as $contact) {
                    echo '<option value="' . $contact['user_id'] . '"' . ((count($users) == 1) || (($owner_id == $contact['user_id'])) ? ' selected = "selected"' : '' ) . '">' . $contact['name'] . ' ' . $contact['last_name'] . '</option>';
                };
                break;
            case 'user':
                $users = module_user::get_users(array('customer_id' => '0'));
                foreach ($users as $user) {
                    echo '<option value="' . $user['user_id'] . '"' . ((count($users) == 1) || (($owner_id == $user['user_id'])) ? ' selected = "selected"' : '' ) . '">' . $user['name'] . ' ' . $user['last_name'] . '</option>';
                };
                break;
            default:
                // This is the solution for everything else...
                $items = call_user_func(array('module_' . $owner_table, 'get_' . $owner_table . 's'));
                foreach ($items as $item) {
                    echo '<option value="' . $item[$owner_table . '_id'] . '"' . ((count($items) == 1) || (($owner_id == $item[$owner_table . '_id'])) ? ' selected = "selected"' : '' ) . '">' . $item['name'] . '</option>';
                }
                break;
        }
        echo '</select>';
        exit;
        /* END public function get_owners($id, $owner_table, $owner_id) */
    }

    /* END class ToDo */
}
