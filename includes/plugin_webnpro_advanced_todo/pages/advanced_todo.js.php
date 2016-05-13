<?php
/*
 *  Module: webNpro Advanced Todo v2.0
 *  Copyright: Kőrösi Zoltán | webNpro - hosting and design | http://webnpro.com | info@webnpro.com
 *  Contact / Feature request: info@webnpro.com (Hungarian / English)
 *  License: Please check CodeCanyon.net for license details.
 *  More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/
 */
?>
<script type="text/javascript">

    /**
     * The following code is executed once the DOM is loaded
     */
    $(document).ready(init);

    /**
     * Init function
     */
    function init() {
        /**
         * A global variable, holding a jQury object containing the current todo item
         */
        var currentTODO;

        /**
         * Get the current todo id
         */
        $('.todo').on('click', function (e) {
            currentTODO = $(this).closest('.todo');
            currentTODO.data('id', currentTODO.attr('id').replace('todo-', ''));
            e.preventDefault();
        });
        $('.todo a').on('click', function (e) {
            currentTODO = $(this).closest('.todo');
            currentTODO.data('id', currentTODO.attr('id').replace('todo-', ''));
            e.preventDefault();
        });
        $('.todo div').on('click', function (e) {
            currentTODO = $(this).closest('.todo');
            currentTODO.data('id', currentTODO.attr('id').replace('todo-', ''));
            e.preventDefault();
        });

        /**
         * Click on the owner link
         */
        $('.todo span.owner a').on('click', function (e) {
            var href = $(this).attr('href');
            window.location.href = href;
        })

        /**
         * Click on the updated by link
         */
        $('.todo span.updatedby a').on('click', function (e) {
            var href = $(this).attr('href');
            window.location.href = href;
        })

        /**
         * Set the elements width to 115px less than todo items
         * @param string element
         */
        function set_todo_inner_width(element) {
            $('.todo .text').width($('.todo').width() - 115);
        }

        /**
         * Color picker function
         */
        function setcolpick() {
            $(".todo-inner").colpick({
                onChange: function (hsb, hex, rgb, el, bySetColor) {
                    /**
                     * Change the colors - live
                     */
                    $(el).css('border-left-color', '#' + hex);
                    $(el).parent().find('.text').css('color', '#' + hex);
                    $(el).parent().find('.infoline').css('color', '#' + hex);
                    $(el).parent().find('.infoline span.owner a').css('color', '#' + hex);
                    $(el).parent().find('.detailsline').css('border-top-color', '#' + hex);
                    $(el).parent().find('.details').css('border-top-color', '#' + hex);
                },
                onSubmit: function (hsb, hex, rgb, el, bySetColor) {
                    /**
                     * Change the colors and call ajax to save it
                     */
                    $(el).css('border-left-color', '#' + hex);
                    $(el).parent().find('.text').css('color', '#' + hex);
                    $(el).parent().find('.infoline').css('color', '#' + hex);
                    $(el).parent().find('.infoline span.owner a').css('color', '#' + hex);
                    $(el).parent().find('.detailsline').css('border-top-color', '#' + hex);
                    $(el).parent().find('.details').css('border-top-color', '#' + hex);
                    $(el).colpickHide();
                    id = $(el).closest('.todo').attr('id').replace('todo-', '');
                    $.get("<?php echo _BASE_HREF; ?>?m[0]=webnpro_advanced_todo&p[0]=todo_ajax", {'action': 'color', 'id': id, 'color': hex});
                }
            }).keyup(function () {
                $(this).colpickSetColor(this.value);
            });
        }
        ;

        /**
         * Set the width of all .todo .text elements
         */
        set_todo_inner_width('.todo .text');

        /**
         * Sortable function - Ajax call
         */
        $(".todoList").sortable({
            axis: 'y', // Only vertical movements allowed
            containment: 'parent', // Constrained by the window
            update: function () { // The function is called after the todos are rearranged
                // The toArray method returns an array with the ids of the todos
                var arr = $(".todoList").sortable('toArray');
                // Striping the todo- prefix of the ids:
                arr = $.map(arr, function (val, key) {
                    return val.replace('todo-', '');
                });
                // Saving with AJAX
                $.get('<?php echo _BASE_HREF; ?>?m[0]=webnpro_advanced_todo&p[0]=todo_ajax', {'action': 'rearrange', positions: arr});
            },
            /* Opera fix: */
            stop: function (e, ui) {
                ui.item.css({'top': '0', 'left': '0'});
            }
        });

        /**
         * Initialize the color picker
         */
        setcolpick();

        /**
         * Configuring the delete confirmation dialog - Ajax call if delete confirmed
         */
        $("#dialog-confirm").dialog({
            resizable: false,
            height: 130,
            modal: true,
            autoOpen: false,
            buttons: {
                '<?php echo _l('Delete item'); ?>': function () {
                    $.get("<?php echo _BASE_HREF; ?>?m[0]=webnpro_advanced_todo&p[0]=todo_ajax", {"action": "delete", "id": currentTODO.data('id')}, function (msg) {
                        currentTODO.fadeOut('fast');
                    })

                    $(this).dialog('close');
                },
                '<?php echo _l('Cancel'); ?>': function () {
                    $(this).dialog('close');
                }
            }
        });

        /**
         * Listening for a click on a delete button:
         */
        $('.todo a.delete').on('click', function () {
            $("#dialog-confirm").dialog('open');
        });

        /**
         *  When a double click occurs, just simulate a click on the edit button
         */
        $('.todo').on('dblclick', function () {
            $(this).find('a.edit').click();
        });

        /**
         * Listening for a click on enabled more button
         * Show long details only if the todo has long details
         */
        $('.todo a.more').on('click', function () {
            if (currentTODO.find('.details').html() != '') {
                currentTODO.find('.details').toggle('fast');
                currentTODO.find('.detailsline').toggle('fast');
            }
        });

        /**
         * Listening for a click on disabled more button
         * Show long details only if the todo has long details
         */
        $('.todo a.nomore').on('click', function () {
            currentTODO.find('.detailsline').toggle('fast');
            if (currentTODO.find('.details').html() != '') {
                currentTODO.find('.details').toggle('fast');
            } else {
                currentTODO.find('.details').hide('fast');
            }
        });

        /**
         * Listening for a click on a done button - Ajax call
         */
        $('.todo a.done').on('click', function () {
            var is_done = currentTODO.find('.text');
            if (currentTODO.hasClass('done')) {
                currentTODO.removeClass('done');
                currentTODO.addClass('undone');
                $.get("<?php echo _BASE_HREF; ?>?m[0]=webnpro_advanced_todo&p[0]=todo_ajax", {'action': 'done', 'id': currentTODO.data('id'), 'done': '0'});
            } else {
                currentTODO.removeClass('undone');
                currentTODO.addClass('done');
                $.get("<?php echo _BASE_HREF; ?>?m[0]=webnpro_advanced_todo&p[0]=todo_ajax", {'action': 'done', 'id': currentTODO.data('id'), 'done': '1'});
            }
        });

        /**
            * Listening for a click on an archive button - Ajax call
            */
        $('.todo a.archive').on('click', function () {
            var is_done = currentTODO.find('.text');
            currentTODO.fadeOut('fast');
            $.get("<?php echo _BASE_HREF; ?>?m[0]=webnpro_advanced_todo&p[0]=todo_ajax", {'action': 'archive', 'id': currentTODO.data('id'), 'archive': '1'});
        });

        /**
            * Listening for a click on an archive button - Ajax call
            */
        $('.todo a.restore').on('click', function () {
            var is_done = currentTODO.find('.text');
            currentTODO.fadeOut('fast');
            $.get("<?php echo _BASE_HREF; ?>?m[0]=webnpro_advanced_todo&p[0]=todo_ajax", {'action': 'archive', 'id': currentTODO.data('id'), 'archive': '0'});
        });

        /**
         * Listening for a click on a edit button - Ajax call
         */
        $('.todo a.edit').on('click', function () {
            var container = currentTODO.find('.text div');
            var container2 = currentTODO.find('.details');
            var container3 = currentTODO.find('.infoline');
            var origownertable = currentTODO.find('.owner').attr('ownertable');
            var origownertype = currentTODO.find('.owner').attr('ownertype');
            var origownerid = currentTODO.find('.owner').attr('ownerid');
            if (!currentTODO.data('origText')) {
                /**
                 * Saving the current values of the ToDo so we can
                 * restore it later if the user discards the changes:
                 */
                currentTODO.data('origText', container.text());
                currentTODO.data('origDetails', container2.html());
                currentTODO.data('origInfoline', container3.html());
                currentTODO.data('origownertype', origownertype);
                currentTODO.data('origownerid', origownerid);
            } else {
                /**
                 * This will block the edit button if the edit box is already open
                 */
                return false;
            }
            container.removeClass('onerow');
            currentTODO.find('.actions').hide();
            currentTODO.find('.details').show('fast');
            currentTODO.find('.detailsline').show('fast');
            $('<input type="text" class="todoinput">').val(container.text()).appendTo(container.empty());
            currentTODO.find('.todoinput').width($('.todo').width() - 125);
            currentTODO.find('.todoinput').keypress(function (event) {
                if (event.keyCode == 13) {
                    currentTODO.find('a.textedit.saveChanges').click();
                }
            });
            $('<textarea class="editable detailsinput" id="detailsinput">').val(container2.html().replace(/[<]br[^>]*[>]/gi,'')).appendTo(container2.empty());
            currentTODO.find('.detailsinput').width('90%').height('100px');
            currentTODO.find('.infoline').html('<div class="owner_dropdowns"></div>');
            /**
             * Get the owner tables with ajax
             */
            $.get("<?php echo _BASE_HREF; ?>?m[0]=webnpro_advanced_todo&p[0]=todo_ajax", {'action': 'get_owner_tables', 'owner_table': origownertype, 'id': currentTODO.data('id')}, function (msg) {
                currentTODO.find('.owner_dropdowns').html(msg);

                /**
                 * Get the owners with ajax
                 */
                $.get("<?php echo _BASE_HREF; ?>?m[0]=webnpro_advanced_todo&p[0]=todo_ajax", {'action': 'get_owners', 'owner_table': origownertype, 'owner_id': origownerid, 'id': currentTODO.data('id')}, function (owner_ids) {
                    currentTODO.find('.owner_dropdowns').append(' ' + owner_ids);
                });

                /**
                 * Listening for changes of the owner tables and reload them with ajax
                 */
                currentTODO.find('select#owner_tables.owner_tables').change(function () {
                    var owner_table = $(this).val();
                    $.get("<?php echo _BASE_HREF; ?>?m[0]=webnpro_advanced_todo&p[0]=todo_ajax", {'action': 'get_owners', 'owner_table': owner_table, 'id': currentTODO.data('id')}, function (owner_ids) {
                        currentTODO.find('select#owner_ids.owner_ids').html(owner_ids);
                    });
                });
            });

            /**
             * Appending the save and cancel links
             */
            container.append(
                    '<div class="editTodo">' +
                    '<a class="textedit saveChanges" href="#"><?php echo _l('Save'); ?></a> <?php echo _l('or'); ?> <a class="textedit discardChanges" href="#"><?php echo _l('Cancel'); ?></a>' +
                    '</div>'
                    );
        });

        /**
         * Listening for a click on the cancel button - Ajax call
         * Reset todo to the saved version
         */
        $('.todo').on('click', 'a.textedit.discardChanges', function () {
            currentTODO.find('.actions').show();
            currentTODO.find('.details')
                    .html(currentTODO.data('origDetails'))
                    .end()
                    .removeData('origDetails');
            currentTODO.find('.text div').addClass('onerow');
            currentTODO.find('.infoline')
                    .html(currentTODO.data('origInfoline'))
                    .end()
                    .removeData('originfoline');
            currentTODO.find('.text div').addClass('onerow');
            currentTODO.find('.text div')
                    .text(currentTODO.data('origText'))
                    .end()
                    .removeData('origText');
            if (currentTODO.find('.details').html() == '') {
                currentTODO.find('.details').hide('fast');
                currentTODO.find('.detailsline').hide('fast');
                currentTODO.find('.actions a.more').removeClass('more');
                currentTODO.find('.actions a.more').addClass('nomore');
            } else {
                currentTODO.find('.actions a.more').removeClass('nomore');
                currentTODO.find('.actions a.more').addClass('more');
            }

        });

        /**
         * Listening for a click on the save changes button - Ajax call
         * Save the changes with ajax and reload the page
         */
        $('.todo').on('click', 'a.textedit.saveChanges', function () {
            var text = currentTODO.find("input[type=text]").val();
            var details = currentTODO.find(".detailsinput").val();
            var ownertable = currentTODO.find('select#owner_tables.owner_tables').val();
            var ownerid = currentTODO.find('select#owner_ids.owner_ids').val();
            $.get("<?php echo _BASE_HREF; ?>?m[0]=webnpro_advanced_todo&p[0]=todo_ajax", {'action': 'edit', 'id': currentTODO.data('id'), 'text': text, 'details': details, 'owner_table': ownertable, 'owner_id': ownerid}, function (msg) {
                location.reload();
            });
        });

        /**
         * Listening for a click on add button - Ajax call
         * Create a new todo with ajax and reload the page
         */
        var timestamp = 0;
        $('#addButton').on('click', function (e) {
            // Only one todo per 5 seconds is allowed:
            if (Date.now() - timestamp < 5000)
                return false;
            $.get("<?php echo _BASE_HREF; ?>?m[0]=webnpro_advanced_todo&p[0]=todo_ajax", {'action': 'new'}, function (msg) {
                location.reload();
            });
            // Updating the timestamp:
            timestamp = (new Date()).getTime();
            e.preventDefault();
        });
        /* END function init() */
    }
    ;
</script>
