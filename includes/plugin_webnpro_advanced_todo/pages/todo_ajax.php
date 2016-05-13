<?php

/*
 *  Module: webNpro Advanced Todo v2.0
 *  Copyright: Kőrösi Zoltán | webNpro - hosting and design | http://webnpro.com | info@webnpro.com
 *  Contact / Feature request: info@webnpro.com (Hungarian / English)
 *  License: Please check CodeCanyon.net for license details.
 *  More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/
 */

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if (isset($_GET['owner_table'])) {
    $owner_table = $_GET['owner_table'];
    $owner_id = isset($_GET['owner_id']) ? $_GET['owner_id'] : '';
} else {
    $owner_table = isset($_SESSION['owner_table']) ? $_SESSION['owner_table'] : 'user';
    $owner_id = isset($_SESSION['owner_id']) ? $_SESSION['owner_id'] : '';
}

if (isset($_SESSION['active_page'])) {
    try {

        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'delete':
                    ToDo::delete($id);
                    break;

                case 'rearrange':
                    ToDo::rearrange($_GET['positions']);
                    break;

                case 'edit':
                    ToDo::edit($id, $_GET['text'], $_GET['details'], $owner_table, $owner_id);
                    break;

                case 'new':
                    ToDo::createNew($owner_table, $owner_id);
                    break;

                case 'done':
                    ToDo::done($id, (int) $_GET['done']);
                    break;

                case 'archive':
                    ToDo::archive($id, (int) $_GET['archive']);
                    break;

                case 'color':
                    ToDo::changecolor($id, $_GET['color']);
                    break;

                case 'get_owner_tables':
                    ToDo::get_owner_tables($id, $owner_table);
                    break;

                case 'get_owners':
                    ToDo::get_owners($id, $owner_table, $owner_id);
                    break;

                default:
                    echo 'ERROR';
                    break;
            }
        }
    } catch (Exception $e) {
        die("ERROR");
    }
}
?>