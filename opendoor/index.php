<?php
/*
Plugin Name: Open Door
Plugin URI: 
Description: Open booking door
Version: 2.0
Author: KSA
Author URI: 
*/



require_once('function.php');

wp_enqueue_style( "front-css", plugins_url( 'css/style.css', __FILE__ ) );

if(is_front_page()){
    //echo '<h1>URA!!!</h1>';
}

global $wpdb;
$tableOpenDoor = $wpdb->prefix . "opendoor";
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

define(OP_NAME, 'Open door');
const OP_TIME_HOUR = 'op_time_hour';


/**
 * creating the table 'opendoor' in database
 */
create_bd_opendoor();

/**
 * creating the plugin page in admin panel
 */
add_action("admin_menu", "opendoor_settings_menu");
function opendoor_settings_menu(){
    add_options_page(
        'Settings ' . 'OpenDoor',
        'Open Door',
        8,
        'opendoor',
        'render_opendoor_settings_page'
    );
}
function render_opendoor_settings_page(){
    include 'admin_settings.php';
}




/**
 * short code of the plugin
 */
function open_door(){
    ob_start();
    include_once('open_door_2.php');
    $content_1 = ob_get_contents();
    ob_end_clean();
    return $content_1;
    //return "Lorem ipsum dolor sit amet";
}
add_shortcode('open-door', 'open_door');

