<?php 
/** 
  * Copyright: dtbaker 2012
  * Licence: Please check CodeCanyon.net for licence details. 
  * More licence clarification available here:  http://codecanyon.net/wiki/support/legal-terms/licensing-terms/ 
  * Deploy: 7736 9b9da523f8242d524bc0edab9f79a2e4
  * Envato: ef2d6ec4-fa40-41b6-9980-24587c1aaf55, 92d36d3b-0276-47ef-93d8-00df5dc17d5e, 3dac064b-a466-4931-93f0-51c086616894, 0b59530f-164b-4ec2-975b-6a3038423838, 7c3ba354-e0b5-4f02-8a97-854fab16f4b1, c1fe4c24-77fe-44eb-a399-426ebdf540b9, ef74c493-5050-4b88-9be1-92e54c0e34aa, a59dba67-f1e1-4085-943c-c3ded9fc5667, c46a4f2a-d54d-4778-9c2d-c61f41691e34, 68ccf1c5-a309-443a-b04e-69d266cb348f
  * Package Date: 2015-03-08 05:40:21 
  * IP Address: 88.217.180.200
  */


$settings = array(
     array(
        'key'=>'enable_customer_maps',
        'default'=>'1',
         'type'=>'checkbox',
         'description'=>'Enable Customer Maps',
     ),
     array(
        'key'=>'google_maps_api_key',
        'default'=>'AIzaSyDFYt1ozmTn34lp96W0AakC-tSJVzEdXjk',
         'type'=>'text',
         'description'=>'Google Maps API Key',
         'help' => 'This is required to get markers displaying on the map. If markers are not displaying please sign up for your own Google Maps/Geocoding API key and put it here.'
     ),
);
module_config::print_settings_form(
    array(
        'heading' => array(
            'title' => 'Map Settings',
            'type' => 'h2',
            'main' => true,
        ),
        'settings' => $settings,
    )
);
