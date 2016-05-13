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
$calendar_id = isset($_GET['calendar_id']) ? (int)$_GET['calendar_id'] : 0;

/// sync with aurora
sync_event_from_aurora($calendar_id);

$calendar = module_calendar::get_calendar($calendar_id);
if(!$calendar_id || !isset($calendar['calendar_id']) || $calendar['calendar_id'] != $calendar_id){
    $calendar_id = $calendar['calendar_id'] = 0;
    if(isset($_REQUEST['customer_id']) && $_REQUEST['customer_id']){
        $calendar['customer_id'] = (int)$_REQUEST['customer_id'];
    }
    // if the user only has access to a single customer, add that customer id in here by default.

    if(isset($_POST['start_date_time']) && $_POST['start_date_time']){
        $start_time = js2PhpTime($_POST['start_date_time']);
        $calendar['start'] = $start_time;
    }
    if(isset($_POST['end_date_time']) && $_POST['end_date_time']){
        $end_time = js2PhpTime($_POST['end_date_time']);
        $calendar['end'] = $end_time;
    }
    if(isset($_POST['is_all_day']) && $_POST['is_all_day']){
        $calendar['is_all_day'] = $_POST['is_all_day'];
    }
    if(isset($_POST['title']) && $_POST['title']){
        $calendar['subject'] = $_POST['title'];
    }
}
if(
    ($calendar_id && module_calendar::can_i('edit','Calendar')) ||
    (!$calendar_id && module_calendar::can_i('create','Calendar'))
){
    // perms are good to go!
}else{
    die('Permission denied');
}
?>
<form action="" method="post" id="calendar_event_form">
<?php
module_form::set_required(array(
    'fields' => array(
        'subject' => 'Subject',
        'start' => 'Start Date',
        'end' => 'End Date',
    ))
);

$customer_list = array();
$customers = module_customer::get_customers();
foreach($customers as $customer){
    $customer_list[$customer['customer_id']] = $customer['customer_name'];
}
$staff_members = module_user::get_staff_members();
$staff_member_rel = array();
foreach($staff_members as $staff_member){
    $staff_member_rel[$staff_member['user_id']] = $staff_member['name'];
}
if(!isset($calendar['staff_ids']) || !is_array($calendar['staff_ids']) || !count($calendar['staff_ids'])){
    $calendar['staff_ids']= array(false);
}

$jobs = module_job::get_jobs();
$jobs_list = array();
$tasks_list = array();
$jobs_customers = array();
$tasks_jobs = array();
$tasks_arts = array();

foreach($jobs as $job){
    $jobs_list[$job['job_id']] = $job['name'];
	$jobs_customers[$job['job_id']] = $job['customer_id'];
	$tasks = module_job::get_tasks($job['job_id']);
	foreach($tasks as $task){ 
		$tasks_list[$task['task_id']] = $task['description'];
		$tasks_jobs[$task['task_id']] = $task['job_id'];
		$product = module_product::get_product($task['product_id']);
		$tasks_arts[$task['task_id']] = $product['task_art'];
	}
}




// output our event information using the standard UCM form processor:
$fieldset_data = array(
    'heading' => false,
    'class' => 'tableclass tableclass_form tableclass_full',
    'elements' => array(
		array(
			'title' => 'Task Art',
			'fields' => array(
				array(
					'type' => 'select',
					'name' => 'task_art',
					'id' => 'task_art_id',
					'options' => array('M'=> 'M','D' => 'D' ,'I' => 'I'),
					'value' => isset($calendar['task_art']) ? $calendar['task_art'] : 'M',
					'blank' => false,
				),
				'&nbsp;&nbsp;Auto subject&nbsp;<input type="checkbox" id="auto_subject_checkbox">'
			)
		),
        array(
            'title' => _l('Subject'),
            'fields' => array(
				array(
					'<div id="calendarcolor" style="float:right"></div><input id="colorvalue" name="color" type="hidden" value="'.(isset($calendar['color']) ? htmlspecialchars($calendar['color']) : '').'" />',
                    'type' => 'text',
					'style' => 'width:90%;',
                    'name' => "subject",
					'id' => 'subject_id',
                    'value' => isset($calendar['subject']) ? $calendar['subject'] : '',
                ),

            )
        ),
        array(
            'title' => _l('Start'),
            'fields' => array(
                array(
                    'type' => 'date',
                    'name' => "start",
                    'value' => isset($calendar['start']) ? print_date($calendar['start']) : '',
                ),
                '<span class="calendar_time">@</span>',
                array(
                    'type' => 'time',
                    'name' => "start_time",
                    'value' => isset($calendar['start']) ? date('H:i',$calendar['start']) : '',
                    'class' => 'time_field24 no_permissions',
                    //'class' => 'calendar_time',
                ),
                array(
                    'type' => 'check',
                    'id' => "is_all_day",
                    'value' => 1,
                    'name' => "is_all_day",
                    'checked' => isset($calendar['is_all_day']) && $calendar['is_all_day'] ? true : false,
                    'label' => _l('All Day Event'),
                ),
            ),
        ),
        array(
            'title' => _l('End'),
            'fields' => array(
                array(
                    'type' => 'date',
                    'name' => "end",
                    'value' => isset($calendar['end']) ? print_date($calendar['end']) : '',
                ),
                '<span class="calendar_time">@</span>',
                array(
                    'type' => 'time',
                    'name' => "end_time",
                    'value' => isset($calendar['end']) ? date('H:i',$calendar['end']) : '',
                    'class' => 'time_field24 no_permissions',
                ),
            ),
        ),
        array(
            'title' => _l('Customer'),
            'fields' => array(
                array(
                    'class' => 'dropdown-select2',
                    'type' => 'select',
                    'name' => 'customer_id',
					'id' => 'customer_id_select',
                    'options' => $customer_list,
                    'value' => isset($calendar['customer_id']) ? $calendar['customer_id'] : 0,

                ),
                (isset($calendar['customer_id']) && $calendar['customer_id'] ? '<a href="'.module_customer::link_open($calendar['customer_id'],false).'" target="_blank">'._l('Open').'</a>' : ''),
            ),
        ),
        array(
            'title' => _l('Order'),
            'fields' => array(
                array(
                    'type' => 'select',
                    'name' => 'job_id',
					'id' => 'job_id_select',
                    'options' => $jobs_list,
                    'value' => isset($calendar['job_id']) ? $calendar['job_id'] : 0,
                ),
                (isset($calendar['job_id']) && $calendar['job_id'] ? '<a href="'.module_job::link_open($calendar['job_id'],false).'" target="_blank">'._l('Open').'</a>' : ''),
            ),
        ),
        array(
            'title' => _l('Task'),
            'fields' => array(
                '<div id="tasks_ids_holder" style="float:left; ">',
                array(
                    'type' => 'select',
                    'name' => 'tasks_ids[]',
					'id' => 'tasks_ids_select',
					'style' => 'width:90%;',
                    'options' => $tasks_list,
                    'multiple' => 'tasks_ids_holder',
                    'values' => explode(',', $calendar['tasks_ids']),
                ),
                '</div>',
                //_hr('Assign a staff member to this calendar event. Click the plus sign to add more staff members.'),
            )
        ),
		array(
			'title' => 'Doctor',
			'field' => array(
				'type' => 'select',
				'name' => 'doctor',
				'id' => 'doctor_select_id',
				'value' => isset($calendar['doctor_id']) ? $calendar['doctor_id'] : 0,
				'options' => module_job::get_doctors(),
				'allow_new' => true,
			),
		),
		array(
			'title' => 'Auto',
			'field' => array(
				'type' => 'select',
				'name' => 'auto',
				'value' => isset($calendar['auto']) ? $calendar['auto'] : "",
				'options' =>get_autos(),
				'allow_new' => true,
			),
		),
        array(
            'title' => module_config::c('customer_staff_name','Staff'),
            'fields' => array(
                '<div id="staff_ids_holder" style="float:left;">',
                array(
                    'type' => 'select',
                    'name' => 'staff_ids[]',
					'id' => 'staff_ids_select_id',
                    'options' => $staff_member_rel,
                    'multiple' => 'staff_ids_holder',
                    'values' => $calendar['staff_ids'],
                ),
                '</div>',
                _hr('Assign a staff member to this calendar event. Click the plus sign to add more staff members.'),
            )
        ),
        array(
            'title' => _l('Description'),
            'field' => array(
                'type' => 'textarea',
                'name' => "description",
                'value' => isset($calendar['description']) ? $calendar['description'] : '',
            ),
        ),
    )
);
echo module_form::generate_fieldset($fieldset_data);
/*
$form_actions = array(
    'class' => 'action_bar action_bar_center',
    'elements' => array(
        array(
            'type' => 'save_button',
            'name' => 'butt_save',
            'value' => _l('Save'),
        ),
        array(
            'ignore' => !(module_calendar::can_i('delete','Calendar') && $calendar_id > 0),
            'type' => 'delete_button',
            'name' => 'butt_del',
            'value' => _l('Delete'),
        ),
        array(
            'type' => 'button',
            'name' => 'cancel',
            'value' => _l('Cancel'),
            'class' => 'submit_button',
            'onclick' => "alert('Close Modal');",
        ),
    ),
);
echo module_form::generate_form_actions($form_actions);*/
?>
</form>

<?php
$base_path = _BASE_HREF.'includes/plugin_calendar/wdCalendar/';
?>
<link href="<?php echo $base_path;?>css/colorselect.css" rel="stylesheet" />
<script src="<?php echo $base_path;?>src/Plugins/Common.js" type="text/javascript"></script>
<script src="<?php echo $base_path;?>src/Plugins/jquery.colorselect.js" type="text/javascript"></script>
<script type="text/javascript">
	
	var jobs_customers = <?= json_encode($jobs_customers);?>;
	var tasks_jobs = <?= json_encode($tasks_jobs);?>;
	var tasks_arts = <?= json_encode($tasks_arts);?>;
	
	var jobs_list  = $('#job_id_select').html();
	var tasks_list  = $('#tasks_ids_select').html();
	var first_job_element = $('#job_id_select option:first')
	var first_task_element = $('#tasks_ids_select option:first')
	
	
	function set_subject() {
		var stuffs = $('#staff_ids_holder option[value != ""]:selected').map(function() { return $(this).html(); }).get();
		var subject = "" + $('#task_art_id option:selected').val() +
					" " + ($('#customer_id_select option[value != ""]:selected').length ? $('#customer_id_select option[value != ""]:selected').html() : "") +
					(  $('#doctor_select_id option[value != ""]:selected').length ? " beim " + $('#doctor_select_id option[value != ""]:selected').html() : " " )+
					( stuffs.length ? " mit " + stuffs.join(',') : "");
		$('#subject_id').val(subject);
		
	}
	
		
	function set_jobs() {
		$('#job_id_select').html(jobs_list);
		$('#job_id_select option').each(function() {
			var current_customer_id = $('#customer_id_select option:selected').val();
			if(jobs_customers[ $(this).val() ] != current_customer_id ) $(this).remove();
		});
		$("#job_id_select").prepend( first_job_element );
	}
	
	function set_tasks() {
		$('#tasks_ids_select').html(tasks_list);		
		$('#tasks_ids_select option').each(function() {
			var current_job_id = $('#job_id_select option:selected').val();
			if(tasks_jobs[ $(this).val() ] != current_job_id ) $(this).remove();
		});
		$("#tasks_ids_select").prepend( first_task_element );
	
	}
	
	set_jobs();
	set_tasks();
		
	$('#customer_id_select').change(function() {		
		set_jobs();
		set_tasks();
		$('#job_id_select [value = ""]').attr("selected", "selected");
		$('#tasks_ids_select [value = ""]').attr("selected", "selected");
	});
        
	$('#job_id_select').change(function() {		
		set_tasks();
		$('#tasks_ids_select [value = ""]').attr("selected", "selected");
	});
        
    $('#tasks_ids_select').change(function() {		
		// set task art
		$("#task_art_id option[value='"+tasks_arts[$('#tasks_ids_select option:selected').val()]+"']").attr("selected", "");
	});
    
	$('#calendar_event_form').click(function() {		
		if($('#auto_subject_checkbox:checked').length != 0) set_subject();
	});
        
    
	
	

	var cv =$("#colorvalue").val() ;
	if(cv=="")
	{
		cv="-1";
	}
	$("#calendarcolor").colorselect({ title: "Color", index: cv, hiddenid: "colorvalue" });
    function toggle_is_all_day(){
		if($('#is_all_day')[0].checked){
            $('.calendar_time').hide();
        }else{
            $('.calendar_time').show();
        }
    }
    $(function(){
        $('#is_all_day').change(toggle_is_all_day);
        toggle_is_all_day();
    
	
	});
</script>
