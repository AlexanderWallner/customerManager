<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55, 92d36d3b-0276-47ef-93d8-00df5dc17d5e, 3dac064b-a466-4931-93f0-51c086616894, 0b59530f-164b-4ec2-975b-6a3038423838, 7c3ba354-e0b5-4f02-8a97-854fab16f4b1, c1fe4c24-77fe-44eb-a399-426ebdf540b9, ef74c493-5050-4b88-9be1-92e54c0e34aa, a59dba67-f1e1-4085-943c-c3ded9fc5667
  * Package Date: 2015-02-04 02:18:43 
  * IP Address: 88.217.180.119
  */

switch($display_mode){
    case 'mobile':
        if(class_exists('module_mobile',false)){
            module_mobile::render_start($page_title,$page);
        }
        break;
    case 'ajax':

        break;
    case 'iframe':
    case 'normal':
    default:

        $header_logo = module_theme::get_config('theme_logo',_BASE_HREF.'images/logo.png');

        ?>

        <!DOCTYPE html>
        <html dir="<?php echo module_config::c('text_direction','ltr');?>">
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?php echo $page_title; ?></title>

        <!-- Apple iOS and Android stuff -->
        <meta name="apple-mobile-web-app-capable" content="no">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link rel="apple-touch-icon-precomposed" href="<?php echo htmlspecialchars($header_logo);?>">

        <!-- Apple iOS and Android stuff - don't remove! -->
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no,maximum-scale=1">


        <?php $header_favicon = module_theme::get_config('theme_favicon','');
            if($header_favicon){ ?>
                <link rel="icon" href="<?php echo htmlspecialchars($header_favicon);?>">
        <?php } ?>

        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=PT+Sans:regular,bold&subset=latin,latin-ext">
        <?php module_config::print_css(_SCRIPT_VERSION);?>



        <script language="javascript" type="text/javascript">
            // by dtbaker.
            var ajax_search_ini = '';
            var ajax_search_xhr = false;
            var ajax_search_url = '<?php echo _BASE_HREF;?>ajax.php';
        </script>

        <script type="text/javascript" src="<?php echo _BASE_HREF;?>js/jquery-1.8.3.min.js?ver=<?php echo _SCRIPT_VERSION;?>"></script>
        <script type="text/javascript" src="<?php echo _BASE_HREF;?>js/jquery-ui-1.9.2.custom.min.js?ver=<?php echo _SCRIPT_VERSION;?>"></script>
        <script type="text/javascript" src="<?php echo _BASE_HREF;?>js/timepicker.js?ver=<?php echo _SCRIPT_VERSION;?>"></script>
        <script type="text/javascript" src="<?php echo _BASE_HREF;?>js/cookie.js?ver=<?php echo _SCRIPT_VERSION;?>"></script>
        <script type="text/javascript" src="<?php echo _BASE_HREF;?>js/javascript.js?ver=<?php echo _SCRIPT_VERSION;?>"></script>
        <?php module_config::print_js(_SCRIPT_VERSION);?>


        <!--
        Author: David Baker (dtbaker.com.au)
        10/May/2010
        -->
        <script type="text/javascript">
            $(function(){
                ucm.init_interface();
                // calendar defaults
                <?php
                switch(strtolower(module_config::s('date_format','d/m/Y'))){
                    case 'd/m/y':
                        $js_cal_format = 'dd/mm/yy';
                        break;
                    case 'y/m/d':
                        $js_cal_format = 'yy/mm/dd';
                        break;
                    case 'm/d/y':
                        $js_cal_format = 'mm/dd/yy';
                        break;
                    default:
                        $js_cal_format = 'yy-mm-dd';
                }
                ?>
                $.datepicker.regional['ucmcal'] = {
                    closeText: '<?php echo addcslashes(_l('Done'),"'");?>',
                    prevText: '<?php echo addcslashes(_l('Prev'),"'");?>',
                    nextText: '<?php echo addcslashes(_l('Next'),"'");?>',
                    currentText: '<?php echo addcslashes(_l('Today'),"'");?>',
                    monthNames: ['<?php echo addcslashes(_l('January'),"'");?>','<?php echo addcslashes(_l('February'),"'");?>','<?php echo addcslashes(_l('March'),"'");?>','<?php echo addcslashes(_l('April'),"'");?>','<?php echo addcslashes(_l('May'),"'");?>','<?php echo addcslashes(_l('June'),"'");?>', '<?php echo addcslashes(_l('July'),"'");?>','<?php echo addcslashes(_l('August'),"'");?>','<?php echo addcslashes(_l('September'),"'");?>','<?php echo addcslashes(_l('October'),"'");?>','<?php echo addcslashes(_l('November'),"'");?>','<?php echo addcslashes(_l('December'),"'");?>'],
                    monthNamesShort: ['<?php echo addcslashes(_l('Jan'),"'");?>', '<?php echo addcslashes(_l('Feb'),"'");?>', '<?php echo addcslashes(_l('Mar'),"'");?>', '<?php echo addcslashes(_l('Apr'),"'");?>', '<?php echo addcslashes(_l('May'),"'");?>', '<?php echo addcslashes(_l('Jun'),"'");?>', '<?php echo addcslashes(_l('Jul'),"'");?>', '<?php echo addcslashes(_l('Aug'),"'");?>', '<?php echo addcslashes(_l('Sep'),"'");?>', '<?php echo addcslashes(_l('Oct'),"'");?>', '<?php echo addcslashes(_l('Nov'),"'");?>', '<?php echo addcslashes(_l('Dec'),"'");?>'],
                    dayNames: ['<?php echo addcslashes(_l('Sunday'),"'");?>', '<?php echo addcslashes(_l('Monday'),"'");?>', '<?php echo addcslashes(_l('Tuesday'),"'");?>', '<?php echo addcslashes(_l('Wednesday'),"'");?>', '<?php echo addcslashes(_l('Thursday'),"'");?>', '<?php echo addcslashes(_l('Friday'),"'");?>', '<?php echo addcslashes(_l('Saturday'),"'");?>'],
                    dayNamesShort: ['<?php echo addcslashes(_l('Sun'),"'");?>', '<?php echo addcslashes(_l('Mon'),"'");?>', '<?php echo addcslashes(_l('Tue'),"'");?>', '<?php echo addcslashes(_l('Wed'),"'");?>', '<?php echo addcslashes(_l('Thu'),"'");?>', '<?php echo addcslashes(_l('Fri'),"'");?>', '<?php echo addcslashes(_l('Sat'),"'");?>'],
                    dayNamesMin: ['<?php echo addcslashes(_l('Su'),"'");?>','<?php echo addcslashes(_l('Mo'),"'");?>','<?php echo addcslashes(_l('Tu'),"'");?>','<?php echo addcslashes(_l('We'),"'");?>','<?php echo addcslashes(_l('Th'),"'");?>','<?php echo addcslashes(_l('Fr'),"'");?>','<?php echo addcslashes(_l('Sa'),"'");?>'],
                    weekHeader: '<?php echo addcslashes(_l('Wk'),"'");?>',
                    dateFormat: '<?php echo $js_cal_format;?>',
                    firstDay: <?php echo module_config::c('calendar_first_day_of_week','1');?>,
                    yearRange: '<?php echo module_config::c('calendar_year_range','-90:+3');?>'
                };
                $.datepicker.setDefaults($.datepicker.regional['ucmcal']);
            });
        </script>


        </head>
        <body id="<?php echo isset($page_unique_id) ? $page_unique_id : 'page';?>" <?php if($display_mode=='iframe') echo ' style="background:#FFF;"';?>>

<?php if($display_mode=='iframe'){ ?>

    <section id="iframe">

<?php }else{ ?>

        <?php if(_DEBUG_MODE){
            module_debug::print_heading();
        } ?>
        <div id="pageoptions">
			<ul>
            <?php if (module_security::getcred()){ ?>
                <li><a href="<?php echo _BASE_HREF;?>index.php?_logout=true"><?php _e('Logout');?></a></li>
                <li><?php echo module_user::link_open($_SESSION['_user_id'],true);?></li>
                <li><?php echo _l('%s %s%s of %s %s',_l(date('l')),date('j'),_l(date('S')),_l(date('F')),date('Y')); ?></li>
                    <?php if(_DEMO_MODE){ ?>
                    <li><a href="#" onclick="$('#pageoptions').animate({marginTop:'30px'},200); $('#profile_info').show(); return false;">Change Theme</a></li>
                    <?php } ?>
                <?php
                }
                ?>
			</ul>
        </div>
        <?php if(_DEMO_MODE){ ?>
		<div id="profile_info" style=" width: 152px;
		display: none;
margin-left: 488px;
margin-top: -51px;
position: relative;
z-index: 1000;
float: left;"></div>
        <?php } ?>

    <!-- start header -->
    <header>
        <div id="header_logo">
            <?php if($header_logo){ ?>
                <a href="<?php echo _BASE_HREF;?>"><img src="<?php echo htmlspecialchars($header_logo);?>" border="0" title="<?php echo htmlspecialchars(module_config::s('header_title','UCM'));?>"></a>
            <?php }else{ ?>
                <a href="<?php echo _BASE_HREF;?>"><?php echo module_config::s('header_title','UCM');?></a>
            <?php } ?>
            <?php if(_DEMO_MODE){ ?>
                <a href="http://themeforest.net/item/ucm-theme-white-label/4120556?ref=dtbaker" target="_blank"><img src="http://ultimateclientmanager.com/webimages/logo_whitelabel.png" border="0"></a>
            <?php } ?>
		</div>
        <div id="header_menu_holder">
            <?php if(module_security::getcred() && module_security::can_user(module_security::get_loggedin_id(),'Show Quick Search') && $display_mode != 'mobile'){
                if(module_config::c('global_search_focus',1)==1){
                        module_form::set_default_field('ajax_search_text');
                    }
                ?>
                <div id="searchbox">
                    <form id="searchform" autocomplete="off">
                        <input type="search" name="query" id="ajax_search_text" placeholder="<?php _e('Quick Search:'); ?>" class="">
                    </form>
                </div>
            <?php } ?>
            <div class="headernavwrap">
                <?php if(isset($header_submenu_content))echo $header_submenu_content;?>
            </div>
            <div class="clear"></div>
        </div>
        <div id="ajax_search_result"></div>
        <div class="clear"></div>
    </header>

    <!-- end header -->

        <nav>
            <?php
            $menu_include_parent=false;
            $show_quick_search=true;
            if(is_file('design_menu.php')){
                //include("design_menu.php");
                include(module_theme::include_ucm("design_menu.php"));
            }
            ?>
		</nav>
<?php  } // iframe. ?>

<section id="content">
    <?php
    $show_messages = false;
    //print_header_message() // << fail
    if(isset($_SESSION['_message']) && count($_SESSION['_message'])){
        $show_messages = true;
        ?>
        <div class="alert success message_click_fade">
                <?php
                $x=1;
                foreach($_SESSION['_message'] as $msg){
                    if(count($_SESSION['_message'])>1){
                        echo "<strong>#$x</strong> ";
                        $x++;
                    }
                    echo nl2br(($msg))."<br>";
                }
                ?>
        </div>
		<?php
        $_SESSION['_message'] = array();
	}
	if(isset($_SESSION['_errors']) && count($_SESSION['_errors'])){
        $show_messages = true;
        foreach($_SESSION['_errors'] as $msg){
            ?>
            <div class="alert warning message_click_fade">
                <?php echo nl2br($msg)."<br>"; ?>
            </div>
    		<?php
        }
        $_SESSION['_errors'] = array();
	}
    if($show_messages){
    ?>
    <script type="text/javascript">
        $(function(){
            $('.message_click_fade').click(function(){$(this).fadeOut('fast');});
        });
    </script>
    <?php
    }
}

?>