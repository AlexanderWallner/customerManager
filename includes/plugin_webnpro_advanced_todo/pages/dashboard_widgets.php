<?php
/*
 *  Module: webNpro Advanced Todo v2.0
 *  Copyright: Kőrösi Zoltán | webNpro - hosting and design | http://webnpro.com | info@webnpro.com
 *  Contact / Feature request: info@webnpro.com (Hungarian / English)
 *  License: Please check CodeCanyon.net for license details.
 *  More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/
 */

// Including the js file
include_once dirname(__FILE__) . '/advanced_todo.js.php';


// We set the user_id to the logged in user id and save it in session variable
$_SESSION['active_page'] = 'home';
$_SESSION['owner_table'] = 'user';
$_SESSION['owner_id'] = module_security::get_loggedin_id();

$get_todos = module_webnpro_advanced_todo::get_todos();

foreach ($get_todos as $todo) {
    $todos[] = new ToDo($todo);
}

ob_start();
?>
<div id="todomain">

    <ul class="todoList" data-role="listview" data-inset="true">

        <?php if (_DEMO_MODE) { ?>
            <li data-role="divider">DEMO MODE<br><small>Please feel free to test it out by entering or changing any of the data.</small></li>
        <?php } ?>
        <?php
        foreach ($todos as $item) {
            echo $item;
        }
        ?>

    </ul>
    <?php // if (module_webnpro_advanced_todo::can_i('create', 'Own todos', 'webNpro Advanced Todo')) {  ?>
    <center>
        <span class="button">
            <a href="#" id="addButton" class="uibutton">
                <img src="<?php echo module_config::get_setting('system_base_dir'); ?>images/add.png" width="10" height="10" alt="add" border="0" />
                <span><?php echo _l('Add a Todo'); ?></span>
            </a>
        </span>
    </center>
    <?php //}  ?>

</div>

<div id="dialog-confirm" title="<?php echo _l('Delete TODO Item?'); ?>"><?php echo _l('Are you sure you want to delete this TODO item?'); ?></div>
<?php
$content = ob_get_clean();
$todowidgets[] = array(
    'id' => 'advanced_todo',
    'title' => _l("Todos"),
    'icon' => 'flag',
    'content' => $content,
);
?>