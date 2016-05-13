<?php
/*
 *  Module: webNpro Advanced Todo v2.0
 *  Copyright: Kőrösi Zoltán | webNpro - hosting and design | http://webnpro.com | info@webnpro.com
 *  Contact / Feature request: info@webnpro.com (Hungarian / English)
 *  License: Please check CodeCanyon.net for license details.
 *  More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/
 */

//if ((!module_webnpro_advanced_todo::can_i('view', 'Own todos', 'webNpro Advanced Todo')) && (!module_webnpro_advanced_todo::can_i('view', 'All todos', 'webNpro Advanced Todo'))) {
//    redirect_browser(_BASE_HREF);
//}

include_once dirname(__FILE__) . '/advanced_todo.js.php';

if ($_SESSION['is_widget'] == '0') {
    $_SESSION['active_page'] = 'todo.main';
    $_SESSION['owner_table'] = 'user';
    $_SESSION['owner_id'] = module_security::get_loggedin_id();
} else {
    if (!isset($_SESSION['active_page'])) {
        $_SESSION['active_page'] = 'todo.main';
    }
    if (!isset($_SESSION['owner_table'])) {
        $_SESSION['owner_table'] = 'user';
    }
    if (!isset($_SESSION['owner_id'])) {
        $_SESSION['owner_id'] = module_security::get_loggedin_id();
    }
    $module->page_title = _l("Todos"); //TODO-fontos: Warning: Creating default object from empty value in
}

$header_buttons = array();

$header_buttons[] = array(
    'url' => _BASE_HREF . '?m[0]=webnpro_advanced_todo&p[0]=documentation',
    'title' => 'Help',
    'type' => 'help'
);



if (isset($_REQUEST['archive']) && $_REQUEST['archive'] == '1') {

    $header_buttons[] = array(
        'url' => _BASE_HREF . '?m[0]=webnpro_advanced_todo&p[0]=todos',
        'title' => _l('Active todos'),
        'id' => 'archiveButton',
    );
} else {
    $header_buttons[] = array(
        'url' => _BASE_HREF . '?m[0]=webnpro_advanced_todo&p[0]=todos&archive=1',
        'title' => _l('Archived todos'),
        'id' => 'archiveButton',
    );
    $header_buttons[] = array(
        'url' => '#',
        'title' => 'Add a Todo',
        'type' => 'add',
        'id' => 'addButton',
    );
}



$_SESSION['todo_user_id'] = $user_id; //TODO-fontos: Notice: Undefined variable: user_id in

$search = array(
    'active_page' => $_SESSION['active_page'],
    'owner_table' => $_SESSION['owner_table'],
    'owner_id' => $_SESSION['owner_id'],
    'archive' => $_REQUEST['archive'],
);

$get_todos = module_webnpro_advanced_todo::get_todos($search);

$todos = array();

foreach ($get_todos as $todo) {
    $todos[] = new ToDo($todo);
}
ob_start();
?>
<div id="todomain">
    <ul data-role="listview" data-inset="true" class="todoList">
        <?php if (_DEMO_MODE) { ?>
            <li data-role="divider">DEMO MODE<br><small>Please feel free to test it out by entering or changing any of the data.</small></li>
        <?php
        }
        foreach ($todos as $item) {
            echo $item;
        }
        ?>
    </ul>
</div>
<div id="dialog-confirm" title="Delete TODO Item?">Are you sure you want to delete this TODO item?</div>
<?php
$content = ob_get_clean();

if ($_REQUEST['is_widget'] == '1') { //TODO-fontos: Notice: Undefined index: is_widget in
    $fmain = false;
    $type = 'h3';
} else {
    $fmain = true;
    $type = 'h2';
    // Print the heading with the header buttons
    print_heading(array(
        'type' => 'h2',
        'main' => $fmain,
        'title' => _l('Todos'),
    ));
}

$fieldset_data = array(
    'heading' => array(
        'type' => 'h3',
        'title' => (isset($_REQUEST['archive']) && $_REQUEST['archive'] == '1') ? _l('Archived Todos') : _l('Todos'),
        'button' => $header_buttons,
    ),
    'class' => 'tableclass tableclass_form tableclass_full',
);
$fieldset_data['elements_after'] = $content;



echo module_form::generate_fieldset($fieldset_data);

$_SESSION['is_widget'] = '0';
