<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55
  * Package Date: 2015-02-04 02:18:43 
  * IP Address: 185.17.207.18
  */
if(!$ticket_safe)die('failed');
$ticket_id = (int)$_REQUEST['ticket_id'];
$ticket = module_ticket::get_ticket($ticket_id);

print_heading(_l('Notify Staff About Ticket: %s',module_ticket::ticket_number($ticket['ticket_id'])));


// template for sending emails.
// are we sending the paid one? or the dueone.
$template = module_template::get_template_by_key('ticket_email_notify');
$ticket['ticket_number'] = module_ticket::ticket_number($ticket['ticket_id']);
$ticket['from_name'] = module_security::get_loggedin_name();
$ticket['ticket_url'] = module_ticket::link_open($ticket_id);
$ticket['ticket_subject'] = $ticket['subject'];

// sending to the staff member.
$to = module_user::get_user($ticket['assigned_user_id']);
$ticket['staff_name'] = $to['name'].' '.$to['last_name'];
$to = array($to);

$template->assign_values($ticket);


module_email::print_compose(
    array(
        'to'=>$to,
        'bcc'=>module_config::c('admin_email_address',''),
        'content' => $template->render('html'),
        'subject' => $template->replace_description(),
        'success_url'=>module_ticket::link_open($ticket_id),
        'cancel_url'=>module_ticket::link_open($ticket_id),
    )
);
?>