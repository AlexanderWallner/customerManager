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

define('_CALENDAR_ACCESS_ALL','All calendar entries in system'); // do not change string
define('_CALENDAR_ACCESS_ASSIGNED','Only from Customers or assigned items'); // do not change string

define("OM_SYNC_TBLNAME", "om_aurora_calendar_sync");

class module_calendar extends module_base{
	
	var $links;

    public static function can_i($actions,$name=false,$category=false,$module=false){
        if(!$module)$module=__CLASS__;
        return parent::can_i($actions,$name,$category,$module);
    }
	public static function get_class() {
        return __CLASS__;
    }
	function init(){
		$this->links = array();
		$this->module_name = "calendar";
		$this->module_position = 4.1;

        $this->version = 2.226;
        // 2.226 - 2015-01-28 - 12/24 hour fix, missing image and save event bug fix
        // 2.225 - 2014-12-23 - 12/24 hour time fix on month view
        // 2.224 - 2014-10-07 - calendar_default_view setting week/month/day
        // 2.223 - 2014-09-16 - calendar_start_hour and calendar_end_hour added
        // 2.222 - 2014-09-02 - calendar_hour_format 12 or 24 setting fixed
        // 2.221 - 2014-08-18 - calendar_hour_format 12 or 24 setting added
        // 2.22 - 2014-07-18 - customer default fix
        // 2.219 - 2014-07-16 - create/edit/delete permission fix
        // 2.218 - 2014-07-15 - calendar ajax tweak
        // 2.217 - 2014-07-13 - new calendar features + permissions
        // 2.216 - 2014-07-13 - new calendar permissions
        // 2.215 - 2014-07-05 - calendar translation improvements
        // 2.214 - 2014-03-26 - displaying jobs in calendar
        // 2.213 - 2014-03-25 - fix for Customer calendar
        // 2.212 - 2014-03-19 - fix for Customer calendar
        // 2.21 - 2014-03-17 - basic Customer Calendar feature added
        // 2.132 - 2013-07-29 - new _UCM_SECRET hash in config.php
        // 2.131 - 2013-07-02 - language translation fix
        // 2.13 - buffering fix
        // 2.12 - permission fix
        // 2.11 - date format fix in cal export
        // 2.1 - initial

	}

    public function pre_menu(){

        if(module_security::has_feature_access(array(
				'name' => 'Settings',
				'module' => 'config',
				'category' => 'Config',
				'view' => 1,
				'description' => 'view',
		))){
			$this->links[] = array(
				"name"=>"GoogleCal",
				"p"=>"calendar_settings",
				"args"=>array('calendar_id'=>false),
				'holder_module' => 'config', // which parent module this link will sit under.
				'holder_module_page' => 'config_admin',  // which page this link will be automatically added to.
				'menu_include_parent' => 0,
			);
        }

        if($this->can_i('view','Calendar')){
            $this->links['calendar'] = array(
                "name"=>'Calendar',
                "p"=>"calendar_admin",
                'icon_name' => 'calendar',
            );
            // only display if a customer has been created.
            if(isset($_REQUEST['customer_id']) && (int)$_REQUEST['customer_id']>0){
                $link_name = _l('Calendar');

                $this->links['calendar_customer'] = array(
                    "name"=>$link_name,
                    "p"=>"calendar_admin",
                    'args'=>array('calendar_id'=>false),
                    'holder_module' => 'customer', // which parent module this link will sit under.
                    'holder_module_page' => 'customer_admin_open',  // which page this link will be automatically added to.
                    'menu_include_parent' => 0,
                    'icon_name' => 'calendar',
                );
            }
        }
    }


    public function process(){

        if("ajax_calendar" == $_REQUEST['_process'] && module_calendar::can_i('view','Calendar')){
            // ajax functions from wdCalendar. copied from the datafeed.php sample files.
            header('Content-type: text/javascript');
            $ret = array();
            $method = isset($_REQUEST['method']) ? $_REQUEST['method'] : false;
            switch($method){
                case "quick_add":
                    if(module_calendar::can_i('create','Calendar')){
                        $ret = addCalendar($_POST["CalendarStartTime"], $_POST["CalendarEndTime"], $_POST["CalendarTitle"], $_POST["IsAllDayEvent"]);
                    }
                    break;
                case "list":
                    $ret = listCalendar($_POST["showdate"], $_POST["viewtype"]);
                    break;
                case "quick_update":
                    if(module_calendar::can_i('edit','Calendar')){
                        $ret = updateCalendar($_POST["calendarId"], $_POST["CalendarStartTime"], $_POST["CalendarEndTime"]);
                    }
                    break;
                case "quick_remove":
                    if(module_calendar::can_i('delete','Calendar')){
                        $ret = removeCalendar( $_POST["calendarId"]);
                    }
                    break;
            }
            echo json_encode($ret);
            exit;
        }
        if("save_calendar_entry" == $_REQUEST['_process']){
            header('Content-type: text/javascript');
            $calendar_id = isset($_REQUEST['calendar_id']) ? (int)$_REQUEST['calendar_id'] : 0;
            $response = array();
            if(
                ($calendar_id && module_calendar::can_i('edit','Calendar')) ||
                (!$calendar_id && module_calendar::can_i('create','Calendar'))
            ){
                $data = $_REQUEST;
                if(isset($data['start'])){
                    $start_time = $data['start'];
                    if(isset($data['start_time']) && (!isset($data['is_all_day']) || !$data['is_all_day'])){
	                    $data['is_all_day'] = 0;
                        $time_hack = $data['start_time'];
                        $time_hack = str_ireplace('am','',$time_hack);
                        $time_hack = str_ireplace('pm','',$time_hack);
                        $bits = explode(':',$time_hack);
                        if(strpos($data['start_time'],'pm')){
                            $bits[0] += 12;
                        }
                        // add the time if it exists
                        $start_time .= ' '.implode(':',$bits).':00';
                        $data['start'] = strtotime(input_date($start_time,true));
                    }else{
                        $data['start'] = strtotime(input_date($start_time));

                    }
                }
                if(isset($data['end'])){
                    $end_time = $data['end'];
                    if(isset($data['end_time']) && (!isset($data['is_all_day']) || !$data['is_all_day'])){
	                    $data['is_all_day'] = 0;
                        $time_hack = $data['end_time'];
                        $time_hack = str_ireplace('am','',$time_hack);
                        $time_hack = str_ireplace('pm','',$time_hack);
                        $bits = explode(':',$time_hack);
                        if(strpos($data['end_time'],'pm')){
                            if($bits[0] < 12)$bits[0] += 12;
                        }
                        // add the time if it exists
                        $end_time .= ' '.implode(':',$bits).':00';
                        //echo $end_time;
                        $data['end'] = strtotime(input_date($end_time,true));
                    }else{
                        $data['end'] = strtotime(input_date($end_time));

                    }
                }
                if(!$data['start'] || !$data['end']){
                    $response['message'] = 'Missing Date';
                }else{
                    //print_r($_REQUEST); print_r($data); exit;
					
					//tasks ids from array to string
					$data['tasks_ids'] = implode(",", $_REQUEST['tasks_ids']);
					//get doctor id
					
					if(isset($_REQUEST['doctor'])) $data['doctor_id'] = module_job::add_doctor_or_get_id($_REQUEST['doctor']);
				
					$calendar_id = update_insert('calendar_id',$calendar_id,'calendar',$data);
                    if($calendar_id){
                        // create aurora event
						create_update_aurora_event($calendar_id, $data);
						
						
						// save staff members.
                        $staff_ids = isset($_REQUEST['staff_ids']) && is_array($_REQUEST['staff_ids']) ? $_REQUEST['staff_ids'] : array();
                        delete_from_db('calendar_user_rel','calendar_id',$calendar_id);
                        foreach($staff_ids as $staff_id){
                            if((int)$staff_id > 0){
                                $sql = "INSERT INTO `"._DB_PREFIX."calendar_user_rel` SET calendar_id = ".(int)$calendar_id.", user_id = ".(int)$staff_id;
                                query($sql);
                            }
                        }
                        $response['calendar_id'] = $calendar_id;
                        $response['message'] = 'Success';
                    }else{
                        $response['message'] = 'Error Saving';
                    }
                }
            }else{
                $response['message'] = 'Access Denied';
            }
            echo json_encode($response);
            exit;
        }
    }

    public static function link_calendar($calendar_type,$options=array(),$h=false){
        if($h){
            return md5('s3cret7hash for calendar '._UCM_SECRET.' '.$calendar_type.serialize($options));
        }
        return full_link(_EXTERNAL_TUNNEL_REWRITE.'m.calendar/h.ical/i.'.$calendar_type.'/o.'.base64_encode(serialize($options)).'/hash.'.self::link_calendar($calendar_type,$options,true).'/cal.ics');
    }
    public static function link_calendar_ajax_functions($h=false){
        if($h){
            return md5('s3cret7hash for ajax calendar '._UCM_SECRET);
        }
        return full_link(_EXTERNAL_TUNNEL.'?m=calendar&h=ajax&hash='.self::link_calendar_ajax_functions(true).'');
    }

     public function external_hook($hook){
        switch($hook){
            case 'ical':
                $calendar_type = (isset($_REQUEST['i'])) ? $_REQUEST['i'] : false;
                $options = (isset($_REQUEST['hash'])) ? (array)unserialize(base64_decode($_REQUEST['o'])) : array();
                $hash = (isset($_REQUEST['hash'])) ? trim($_REQUEST['hash']) : false;
                if($calendar_type && $hash){
                    $correct_hash = $this->link_calendar($calendar_type,$options,true);
                    if($correct_hash == $hash){

                        if(ob_get_level())ob_end_clean();
                        include('pages/ical_'.basename($calendar_type).'.php');
                        exit;

                    }
                }
                break;
        }


     }
    
    
    public static function get_calendar_data_access() {
        if(class_exists('module_security',false)){
            return module_security::can_user_with_options(module_security::get_loggedin_id(),'Calendar Data Access',array(
                                                                                                   _CALENDAR_ACCESS_ALL,
                                                                                                   _CALENDAR_ACCESS_ASSIGNED,
                                                                                                                       ));
        }else{
            return true;
        }
    }


    public static function get_calendar($calendar_id){
        $calendar = get_single('calendar','calendar_id',$calendar_id);
        $calendar['staff_ids'] = array();
        if($calendar_id>0){
            $s = get_multiple('calendar_user_rel',array('calendar_id'=>$calendar_id));
            foreach($s as $user){
                $calendar['staff_ids'][] = $user['user_id'];
            }
        }
        return $calendar;
    }

    public function get_upgrade_sql(){
        $sql = '';


        if(!self::db_table_exists('calendar_user_rel')){
            $sql .= 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX.'calendar_user_rel` (
  `calendar_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`calendar_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
        }



        return $sql;
    }


    public function get_install_sql(){
        ob_start();
        ?>

CREATE TABLE IF NOT EXISTS `<?php echo _DB_PREFIX; ?>calendar` (
  `calendar_id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL DEFAULT '',
  `start` int(11) NOT NULL DEFAULT '0',
  `end` int(11) NOT NULL DEFAULT '0',
  `is_all_day` tinyint(1) NOT NULL DEFAULT '0',
  `color` varchar(50) NOT NULL DEFAULT '',
  `recurring_rule` varchar(500) NOT NULL DEFAULT '',
  `customer_id` int(11) NOT NULL DEFAULT '0',
  `job_id` int(11) NOT NULL DEFAULT '0',
  `tasks_ids` varchar(50) NOT NULL DEFAULT '0',
  `task_art` varchar(5) NOT NULL DEFAULT 'M',  
  `doctor_id` int(11) NOT NULL DEFAULT '0',
  `auto` varchar(50) NOT NULL DEFAULT '',
  `quote_id` int(11) NOT NULL DEFAULT '0',
  `invoice_id` int(11) NOT NULL DEFAULT '0',
  `website_id` int(11) NOT NULL DEFAULT '0',
  `create_user_id` int(11) NOT NULL DEFAULT '0',
  `update_user_id` int(11) NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `date_updated` date NOT NULL,
  PRIMARY KEY (`calendar_id`),
        KEY `customer_id` (`customer_id`),
        KEY `invoice_id` (`invoice_id`),
        KEY `job_id` (`job_id`),
        KEY `website_id` (`website_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `<?php echo _DB_PREFIX; ?>calendar_user_rel` (
  `calendar_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`calendar_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


    <?php

        return ob_get_clean();
    }


}


function js2PhpTime($jsdate){
    $ret = '';
  if(preg_match('@(\d+)/(\d+)/(\d+)\s+(\d+):(\d+)@', $jsdate, $matches)==1){
    $ret = mktime($matches[4], $matches[5], 0, $matches[1], $matches[2], $matches[3]);
    //echo $matches[4] ."-". $matches[5] ."-". 0  ."-". $matches[1] ."-". $matches[2] ."-". $matches[3];
  }else if(preg_match('@(\d+)/(\d+)/(\d+)@', $jsdate, $matches)==1){
    $ret = mktime(0, 0, 0, $matches[1], $matches[2], $matches[3]);
    //echo 0 ."-". 0 ."-". 0 ."-". $matches[1] ."-". $matches[2] ."-". $matches[3];
  }
  return $ret;
}

function php2JsTime($phpDate){
    //echo $phpDate;
    //return "/Date(" . $phpDate*1000 . ")/";
    return date("m/d/Y H:i", $phpDate);
}

function php2MySqlTime($phpDate){
    return date("Y-m-d H:i:s", $phpDate);
}

function mySql2PhpTime($sqlDate){
    $arr = date_parse($sqlDate);
    return mktime($arr["hour"],$arr["minute"],$arr["second"],$arr["month"],$arr["day"],$arr["year"]);

}

// wdCalendar functions, modified to work with UCM database format
function addCalendar($st, $et, $sub, $ade){
  $ret = array();
  try{
      $customer_data = isset($_REQUEST['customer_id']) && (int)$_REQUEST['customer_id'] > 0 ? module_customer::get_customer($_REQUEST['customer_id']) : false;
      $calendar_id = update_insert('calendar_id',false,'calendar',array(
          'subject' => $sub,
          'start' => js2PhpTime($st),
          'end' => js2PhpTime($et),
          'is_all_day' => $ade,
          'customer_id' => $customer_data && isset($customer_data['customer_id']) ? (int)$customer_data['customer_id'] : 0,
      ));
      if($calendar_id){
          $ret['IsSuccess'] = true;
          $ret['Msg'] = _l('add success');
          $ret['Data'] = $calendar_id;
      }else{
          $ret['IsSuccess'] = false;
          $ret['Msg'] = _l('add failed');
      }
  }catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}


function listCalendarByRange($sd, $ed){
  $ret = array();
  $ret['events'] = array();
  $ret["issort"] =true;
  $ret["start"] = php2JsTime($sd);
  $ret["end"] = php2JsTime($ed);
  $ret['error'] = null;

      $calendar_data_access = module_calendar::get_calendar_data_access();

    // hook into things like jobs and stuff who want to return calendar entries.
    $hook_results = hook_handle_callback('calendar_events',$sd,$ed);
    if(is_array($hook_results) && count($hook_results)){
        foreach($hook_results as $hook_result){
            if(is_array($hook_result)){
                foreach($hook_result as $result){
                    // format our hook results to match our bad (indexed) array,
                    // will update that array in the future
                    /*$calendar_events[] = array(
                        'subject' => $job['name'],
                        'customer_id' => $job['customer_id'],
                        'start_time' => $job['date_start'],
                        'user_id' => $job['user_id'],
                        'description' => 'Test Description',
                        'link' => module_job::link_open($job['job_id'],true,$job),
                    );*/
                    $staff_names = array();
                    $staff_names_str = array();
                    $staff_str = "";
					if(isset($result['staff_ids']) && count($result['staff_ids'])){
                        switch($calendar_data_access){
                            case _CALENDAR_ACCESS_ALL:

                                break;
                            case _CALENDAR_ACCESS_ASSIGNED:
                            default:
                                $current_user = module_security::get_loggedin_id();
                                if(!in_array($current_user,$result['staff_ids'])){
                                    continue 2;
                                }
                                break;
                        }
						foreach($result['staff_ids'] as $staff_id){
                            $staff_names[] = module_user::link_open($staff_id,true);
							$user = module_user::get_user($staff_id);
							$staff_names_str[] = $user['name'];
						}
						$staff_str = " mit ";		
					}
					
                    $staff_names = implode(', ',$staff_names);
                    $staff_str .= implode(', ',$staff_names_str);
									

                    $result[0] = false; // no calendar ID at the moment
                    $result[1] = $result['subject'].$staff_str;
                    $result[2] = php2JsTime($result['start_time']);
                    $result[3] = php2JsTime(isset($result['end_time']) ? $result['end_time'] : $result['start_time']);
                    $result[4] = !isset($result['all_day']) || $result['all_day'];
                    $result[5] = 0;
                    $result[6] = 0;
                    $result[7] = 0;//col
                    $result[8] = 2;
                    $result[9] = 0;
                    $result[10] = 0;
                    $result[13] = $result['customer_id'];
                    $result[12] = $result['link'];
                    $result[14] = isset($_REQUEST['customer_id']) && $_REQUEST['customer_id'] != $result['customer_id'] ? 'chip-fade' : '';
                    $result['staff'] = $staff_names;

                    $ret['events'][] = $result;
                }
            }
        }
    }
	
/*	try	{
		$sql = "select * from `"._DB_PREFIX."calendar` where `start` >= '".mysql_real_escape_string($sd)."' AND `start` <= '". mysql_real_escape_string($ed)."'";
		$rows = qa($sql);

		foreach($rows as $row){
			sync_event_from_aurora($row['calendar_id']);
		}
	}
	catch(Exception $e)	{
		$ret['error'] = $e->getMessage();
	}
	*/
	
	sync_events_from_aurora(mysql_real_escape_string($sd),mysql_real_escape_string($ed));
	
  try{
    $sql = "select * from `"._DB_PREFIX."calendar` where `start` >= '".mysql_real_escape_string($sd)."' AND `start` <= '". mysql_real_escape_string($ed)."'";
//  echo $sql;
  $rows = qa($sql);
    foreach($rows as $row){
      //$ret['events'][] = $row;
      //$attends = $row->AttendeeNames;
      //if($row->OtherAttendee){
      //  $attends .= $row->OtherAttendee;
      //}
      //echo $row->StartTime;
        $more_than_1_day = date('Ymd',$row['start']) == date('Ymd',$row['end']) ? 0 : 1;
        $customer_name = $customer_link = '';
        if($row['customer_id'] > 0){
            $customer_data = module_customer::get_customer($row['customer_id'],true,true);
            if(!$customer_data || $customer_data['customer_id'] != $row['customer_id']){
                $row['customer_id'] = 0;
            }else{
                switch($calendar_data_access){
                    case _CALENDAR_ACCESS_ALL:

                        break;
                    case _CALENDAR_ACCESS_ASSIGNED:
                    default:
                        if(isset($customer_data['_no_access'])){
                            continue 2;
                        }
                        break;
                }
                $customer_name = $customer_data['customer_name'];
                $customer_link = module_customer::link_open($row['customer_id'],true,$customer_data);
            }
        }

        $calendar_event = module_calendar::get_calendar($row['calendar_id']);
        $staff_names = array();
        if(count($calendar_event['staff_ids'])){
            switch($calendar_data_access){
                case _CALENDAR_ACCESS_ALL:

                    break;
                case _CALENDAR_ACCESS_ASSIGNED:
                default:
                    $current_user = module_security::get_loggedin_id();
                    if(!in_array($current_user,$calendar_event['staff_ids'])){
                        continue 2;
                    }
                    break;
            }
            foreach($calendar_event['staff_ids'] as $staff_id){
                $staff_names[] = module_user::link_open($staff_id,true);
            }
        }
        $staff_names = implode(', ',$staff_names);


      $ret['events'][] = array(
        0=>$row['calendar_id'],
        1=>$row['subject'],
        2=>php2JsTime($row['start']),
        3=>php2JsTime($row['end']),
        4=>$row['is_all_day'],
        5=>$more_than_1_day, //more than one day event
        //$row->InstanceType,
        6=>0,//Recurring event,
        7=>$row['color'],
        8=>1,//editable ( 0 not editable or clickable, 1 editable, 2 clickable but not editable - from hooks)
        9=>'',//location
        10=>'',//$attends
        11=>$customer_name,//customer name
        12=>$customer_link,
        13=>$row['customer_id'],
        14=>isset($_REQUEST['customer_id']) && $_REQUEST['customer_id'] != $row['customer_id'] ? 'chip-fade' : '', // should we fade this element out ?
        'staff'=>$staff_names,
      );
    }
	}catch(Exception $e){
     $ret['error'] = $e->getMessage();
  }

    // build bubble content based on event data:
    foreach($ret['events'] as $event_id => $event){
        if(!isset($event['bubble'])){
            $ret['events'][$event_id]['bubble'] = '<div id="bbit-cs-buddle" style="z-index: 1080; width: 400px;visibility:hidden;" class="bubble"><table class="bubble-table" cellSpacing="0" cellPadding="0"><tbody><tr><td class="bubble-cell-side"><div id="tl1" class="bubble-corner"><div class="bubble-sprite bubble-tl"></div></div><td class="bubble-cell-main"><div class="bubble-top"></div><td class="bubble-cell-side"><div id="tr1" class="bubble-corner"><div class="bubble-sprite bubble-tr"></div></div>  <tr><td class="bubble-mid" colSpan="3"><div style="overflow: hidden" id="bubbleContent1"><div><div></div><div class="cb-root"><table class="cb-table" cellSpacing="0" cellPadding="0"><tbody>' .
                        '<tr>' .
                        '<td class="cb-value"><div class="textbox-fill-wrapper"><div class="textbox-fill-mid"><div id="bbit-cs-what" title="'
                    	. htmlspecialchars(_l('View Details')) . '" class="textbox-fill-div lk" style="cursor:pointer;">'.htmlspecialchars($event[1]).'</div></div></div></td></tr><tr><td class=cb-value><div id="bbit-cs-buddle-timeshow"></div></td>' .
                        '</tr>' .
                        '<tr><td class=cb-value><div id="bbit-cs-customer-link">'._l('Customer: %s',$event[12] ? $event[12] : _l('N/A')).'</div></td></tr>' .
                (isset($event['other_details']) && strlen($event['other_details']) ? '<tr><td class=cb-value><div id="bbit-cs-customer-link">'.$event['other_details'].'</div></td></tr>' : '' ) .
                        '<tr><td class=cb-value><div id="bbit-cs-staff-link">'._l('Staff: %s',$event['staff'] ? $event['staff'] : _l('N/A')).'</div></td></tr>' .
                        '</tbody></table>' .
                ($event[8] == 1 ?
                        '<div class="bbit-cs-split"><input id="bbit-cs-id" type="hidden" value=""/>' .
                        (module_calendar::can_i('delete','Calendar') ? '[ <span id="bbit-cs-delete" class="lk">'.htmlspecialchars(_l('Delete')).'</span> ]&nbsp;':'') .
                        (module_calendar::can_i('edit','Calendar') ? ' <span id="bbit-cs-editLink" class="lk">'.htmlspecialchars(_l('Edit Event')).' </span>':'') .
                '</div> '  : '' ) .
                '</div></div></div><tr><td><div id="bl1" class="bubble-corner"><div class="bubble-sprite bubble-bl"></div></div><td><div class="bubble-bottom"></div><td><div id="br1" class="bubble-corner"><div class="bubble-sprite bubble-br"></div></div></tr></tbody></table><div id="bubbleClose2" class="bubble-closebutton"></div><div id="prong1" class="prong"><div class=bubble-sprite></div></div></div>';
        }
    }

  return $ret;
}

function listCalendar($day, $type){
  $phpTime = js2PhpTime($day);
  //echo $phpTime . "+" . $type;
  switch($type){
    case "month":
      $st = mktime(0, 0, 0, date("m", $phpTime), 1, date("Y", $phpTime));
      $et = mktime(0, 0, -1, date("m", $phpTime)+1, 1, date("Y", $phpTime));
      break;
    case "week":
      //suppose first day of a week is monday
      $monday  =  date("d", $phpTime) - date('N', $phpTime) + 1;
      //echo date('N', $phpTime);
      $st = mktime(0,0,0,date("m", $phpTime), $monday, date("Y", $phpTime));
      $et = mktime(0,0,-1,date("m", $phpTime), $monday+7, date("Y", $phpTime));
      break;
    case "day":
      $st = mktime(0, 0, 0, date("m", $phpTime), date("d", $phpTime), date("Y", $phpTime));
      $et = mktime(0, 0, -1, date("m", $phpTime), date("d", $phpTime)+1, date("Y", $phpTime));
      break;
  }
  //echo $st . "--" . $et;
  return listCalendarByRange($st, $et);
}

function updateCalendar($id, $st, $et){
  //// move aurora event 
  move_aurora_event($id, js2PhpTime($st), js2PhpTime($et));
  
  
  
  $ret = array();
  try{
      $calendar_id = update_insert('calendar_id',$id,'calendar',array(
          'start' => js2PhpTime($st),
          'end' => js2PhpTime($et),
      ));
      if($calendar_id){
          $ret['IsSuccess'] = true;
          $ret['Msg'] = _l('Change success');
          $ret['Data'] = $calendar_id;
      }else{
          $ret['IsSuccess'] = false;
          $ret['Msg'] = _l('Change failed');
      }
	}catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  return $ret;
}


function removeCalendar($id){
  $ret = array();
  try{
     if(!delete_from_db('calendar','calendar_id',$id)){
         $ret['IsSuccess'] = false;
          $ret['Msg'] = mysql_error();
        }else{
          $ret['IsSuccess'] = true;
          $ret['Msg'] = 'Succefully';
         }
	}catch(Exception $e){
     $ret['IsSuccess'] = false;
     $ret['Msg'] = $e->getMessage();
  }
  delete_aurora_event($id);
  
  return $ret;
}

function get_autos() {
	$sql = "SELECT `auto` FROM `"._DB_PREFIX."calendar` GROUP BY `auto` ORDER BY `auto`";
	$autos = array();
	foreach(qa($sql) as $r){
		$autos[$r['auto']] = $r['auto'];
	}
	return $autos;
}

function create_update_aurora_event($om_id, $data ) {
	$time_dif_create = 7200;
	$time_dif_modify = 7200;
	
	include_once __DIR__.'/../../../wm/libraries/afterlogic/api.php';

	$oApiUsersManager = CApi::Manager('users');
	$oAccount = $oApiUsersManager->GetAccountOnLogin("info@kliniken-allianz.de");
	$oApiCalendarManager = CApi::Manager('calendar');
	$oCalendar = $oApiCalendarManager->GetDefaultCalendar($oAccount);
		
	$cEvent = new CEvent();

	$cEvent->IdCalendar = $oCalendar['Id'];
	$cEvent->AllDay = (bool)$data['is_all_day'];
	$cEvent->Name = $data['subject'];
	$cEvent->Description = $data['description'];
	$cEvent->Location = $data['location'];

	$query = "SELECT aurora_uid FROM ".OM_SYNC_TBLNAME." WHERE om_id='".$om_id."'";
	$sqlresult = mysql_query($query) or die("Query load failed : " . mysql_error());
	$line = mysql_fetch_array($sqlresult, MYSQL_ASSOC);
	$aurora_uid = $line['aurora_uid'];		
	
	if($aurora_uid) {
		$cEvent->Start = date("Ymd\THis\Z", $data['start'] - $time_dif_modify);
		$cEvent->End = date("Ymd\THis\Z", $data['end'] - $time_dif_modify);
		
		$cEvent->Id = $aurora_uid;
		$oApiCalendarManager->UpdateEvent($oAccount,$cEvent);
	}
	else {
		$cEvent->Start = date("Ymd\THis\Z", $data['start'] - $time_dif_create);
		$cEvent->End = date("Ymd\THis\Z", $data['end'] - $time_dif_create);
				
		$aurora_uid = $oApiCalendarManager->CreateEvent($oAccount,$cEvent);
		$query = "INSERT INTO ".OM_SYNC_TBLNAME.
				" (om_id, aurora_uid) VALUES ( '".$om_id."' ,'".$aurora_uid."'); ";
		$sqlresult = mysql_query($query) or die("Query load failed : " . mysql_error());			
	}	
}

function move_aurora_event($om_id, $start, $end ) {
	$time_dif_modify = 7200;
	
	include_once __DIR__.'/../../../wm/libraries/afterlogic/api.php';

	$oApiUsersManager = CApi::Manager('users');
	$oAccount = $oApiUsersManager->GetAccountOnLogin("info@kliniken-allianz.de");
	$oApiCalendarManager = CApi::Manager('calendar');
	$oCalendar = $oApiCalendarManager->GetDefaultCalendar($oAccount);
		
		
	

	$query = "SELECT aurora_uid FROM ".OM_SYNC_TBLNAME." WHERE om_id='".$om_id."'";
	$sqlresult = mysql_query($query) or die("Query load failed : " . mysql_error());
	$line = mysql_fetch_array($sqlresult, MYSQL_ASSOC);
	$aurora_uid = $line['aurora_uid'];		
	
	if($aurora_uid) {
		$event = $oApiCalendarManager->GetEvent($oAccount,$oCalendar['Id'],$aurora_uid);
		
		$cEvent = new CEvent();

		$cEvent->IdCalendar = $oCalendar['Id'];
		//$cEvent->AllDay = $event[0]['allDay'];
		//$cEvent->Name = $event[0]['subject'];
		//$cEvent->Description = $event[0]['description'];
		//$cEvent->Location = $event[0]['location'];
		
		$cEvent->Start = date("Ymd\THis\Z", $start - $time_dif_modify);
		$cEvent->End = date("Ymd\THis\Z", $end - $time_dif_modify);
		
		$cEvent->Id = $aurora_uid;
		$oApiCalendarManager->UpdateEvent($oAccount,$cEvent);
	}
	
}




function delete_aurora_event($om_id) {
	include_once __DIR__.'/../../../wm/libraries/afterlogic/api.php';

	$oApiUsersManager = CApi::Manager('users');
	$oAccount = $oApiUsersManager->GetAccountOnLogin("info@kliniken-allianz.de");
	$oApiCalendarManager = CApi::Manager('calendar');
	$oCalendar = $oApiCalendarManager->GetDefaultCalendar($oAccount);
		
	$query = "SELECT aurora_uid FROM ".OM_SYNC_TBLNAME." WHERE om_id='".$om_id."'";
	$sqlresult = mysql_query($query) or die("Query load failed : " . mysql_error());
	$line = mysql_fetch_array($sqlresult, MYSQL_ASSOC);
	$aurora_uid = $line['aurora_uid'];		
	
	
	
	if($aurora_uid) {
		$oApiCalendarManager->DeleteEvent($oAccount,$oCalendar['Id'],$aurora_uid);
	
		$querysync = "DELETE FROM `".OM_SYNC_TBLNAME."` WHERE  `om_id`='".$om_id."'";
		$sqlresult = mysql_query($querysync) or die("Query load failed : " . mysql_error());
	
	}
	
}

function sync_event_from_aurora($om_id) {
	$time_dif = 0;
	
	include_once __DIR__.'/../../../wm/libraries/afterlogic/api.php';

	$oApiUsersManager = CApi::Manager('users');
	$oAccount = $oApiUsersManager->GetAccountOnLogin("info@kliniken-allianz.de");
	$oApiCalendarManager = CApi::Manager('calendar');
	$oCalendar = $oApiCalendarManager->GetDefaultCalendar($oAccount);	
	
	$query = "SELECT aurora_uid FROM ".OM_SYNC_TBLNAME." WHERE om_id='".$om_id."'";
	$sqlresult = mysql_query($query) or die("Query load failed : " . mysql_error());
	$line = mysql_fetch_array($sqlresult, MYSQL_ASSOC);
	$aurora_uid = $line['aurora_uid'];		
	
	if($aurora_uid) {
		$event = $oApiCalendarManager->GetEvent($oAccount,$oCalendar['Id'],$aurora_uid);
	
		$query = "UPDATE "._DB_PREFIX."calendar SET ".
			"`subject` = '".$event[0]['subject']."',". //  subject, 
			"`description` = '".$event[0]['description']."',". //	description,
			"`location` = '".$event[0]['location']."',". //	location,
			"`start` = '". strtotime($event[0]['start'])."',". //	start,
			"`end` = '". strtotime($event[0]['end'])."',". //	end,
			"`is_all_day` = '".(int)($event[0]['allDay'])."',". //	is_all_day,
			"`date_updated` =  CURDATE() ". //	date_updated			
			" WHERE calendar_id='".$om_id."';";
		$sqlresult = mysql_query($query) or die("Query load failed : " . mysql_error());
	}
	
	return $aurora_uid;
}

function sync_events_from_aurora($start, $end) {
	include_once __DIR__.'/../../../wm/libraries/afterlogic/api.php';
	
	if (class_exists('CApi') && CApi::IsValid())	{
		
		$oApiUsersManager = CApi::Manager('users');
		$oAccount = $oApiUsersManager->GetAccountOnLogin("info@kliniken-allianz.de");
		$oApiCalendarManager = CApi::Manager('calendar');
		$oCalendar = $oApiCalendarManager->GetDefaultCalendar($oAccount);

		$oCalIds = array();
		$oCalIds[] = $oCalendar['Id'];

		$oEvents = array();
		$oEvents = $oApiCalendarManager->GetEvents($oAccount,$oCalIds, $start, $end);

		$existed_events = array();

		$query = "SELECT * FROM ".OM_SYNC_TBLNAME." ";
		$sqlresult_sync = mysql_query($query) or die("Query load failed : " . mysql_error());
		while( $line = mysql_fetch_array($sqlresult_sync, MYSQL_ASSOC) ) {
			$om_id = $line['om_id'];
			$aurora_uid = $line['aurora_uid'];
			
			$event = $oApiCalendarManager->GetEvent($oAccount,$oCalendar['Id'],$aurora_uid);

			if( !$event) {		
				// delete sync
				$querysync = "DELETE FROM `".OM_SYNC_TBLNAME."` WHERE  `om_id`='".$om_id."'";
				$sqlresult = mysql_query($querysync) or die("Query DELETE failed : " . mysql_error());
				//delete event
				$query = "DELETE FROM `"._DB_PREFIX."calendar` WHERE  `calendar_id`='".$om_id."';";
				$sqlresult = mysql_query($query) or die("Query DELETE failed : " . mysql_error());
				//delete staff
				$query = "DELETE FROM `"._DB_PREFIX."calendar_user_rel` WHERE  `calendar_id`='".$om_id."';";
				$sqlresult = mysql_query($query) or die("Query DELETE failed : " . mysql_error());

			} else {
				$existed_events[$line['om_id']] = $line['aurora_uid'];
			}
		}

		if ($oEvents) {

			foreach ($oEvents as $oEvent) {
				$om_id = array_search($oEvent["uid"], $existed_events);
				if($om_id) {
					$query = "UPDATE "._DB_PREFIX."calendar SET ".
						"`subject` = '".$oEvent['subject']."',". //  subject, 
						"`description` = '".$oEvent['description']."',". //	description,
						"`location` = '".$oEvent['location']."',". //	location,
						"`start` = '". strtotime($oEvent['start'])."',". //	start,
						"`end` = '". strtotime($oEvent['end'])."',". //	end,
						"`is_all_day` = '".(int)($oEvent['allDay'])."',". //	is_all_day,
						"`date_updated` =  CURDATE() ". //	date_updated			
						" WHERE calendar_id='".$om_id."';";
					$sqlresult = mysql_query($query) or die("Query UPDATE calendar  failed : " . mysql_error());		
				} else {
					$query = "INSERT INTO "._DB_PREFIX."calendar ".
								" (subject, description, location, start, end, is_all_day,date_created) VALUES (".
								"'".$oEvent['subject']."',". //  subject, 
								"'".$oEvent['description']."',". //	description,
								"'".$oEvent['location']."',". //	location,
								"'".strtotime($oEvent['start'])."',". //	start,
								"'".strtotime($oEvent['end'])."',". //	end,
								"'".(int)($oEvent['allDay'])."',". //	is_all_day,
								" CURDATE() ". //	date_created							
								");";
							$sqlresult = mysql_query($query) or die("Query INSERT calendar  failed : " . mysql_error());
							
							$new_om_id = mysql_insert_id();
							
							$query = "INSERT INTO ".OM_SYNC_TBLNAME.
										" (om_id, aurora_uid) VALUES ( '".$new_om_id."' ,'".$oEvent["uid"]."'); ";
							$sqlresult = mysql_query($query) or die("Query INSERT OM_SYNC_TBLNAME failed : " . mysql_error());		
									
				}			   
			}
		}
	}
	else	{
		echo 'AfterLogic API isn\'t available';
	}
		
}



