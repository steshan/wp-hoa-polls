<?php

/*
Plugin Name: Homeowners Association Polls
Plugin URI:
Description: This plugin adds polls support for Homeowners Association
Version: 0.0.3
Text Domain: homeowners-association-polls
Domain Path: /languages/
Author: Ann Tataranovich
License: GPL2
*/
define('WP_HOA_ROOT', __DIR__);
global $hoa_polls_db_version;
$hoa_polls_db_version = '0.0.3';

if(!defined('ABSPATH')) {
    die('You are not allowed to call this page directly.');
}

function wphoa_polls_install()
{
    global $wpdb;
    global $hoa_polls_db_version;
    $answers_table = $wpdb->prefix . 'hoa_answers';
    $polls_table = $wpdb->prefix . 'hoa_polls';
    $questions_table = $wpdb->prefix . 'hoa_questions';
    $rooms_table = $wpdb->prefix . 'hoa_rooms';

    $installed_ver = get_option("hoa_polls_db_version", '0.0.0');

    if ($installed_ver != $hoa_polls_db_version) {
        $answers_table_sql = "CREATE TABLE {$answers_table} (
            id int(10) unsigned NOT NULL AUTO_INCREMENT,
            questionId int(10) unsigned NOT NULL,
            roomNumber int(10) unsigned NOT NULL,
            answer enum('YES','NO','SKIP') NOT NULL,
            PRIMARY KEY  (id),
            KEY questionId (questionId)
            );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($answers_table_sql);

        $polls_table_sql = "CREATE TABLE {$polls_table} (
            id int(10) unsigned NOT NULL AUTO_INCREMENT,
            name text NOT NULL,
            quorum float unsigned NOT NULL,
            read_only int(10) unsigned NOT NULL DEFAULT '0',
            PRIMARY KEY  (id)
            );";

        dbDelta($polls_table_sql);

        $questions_table_sql = "CREATE TABLE {$questions_table} (
              id int(10) unsigned NOT NULL AUTO_INCREMENT,
              pollId int(10) unsigned NOT NULL,
              questionText text NOT NULL,
              PRIMARY KEY  (id),
              KEY pollId (pollId)
            );";

        dbDelta($questions_table_sql);

        $rooms_table_sql = "CREATE TABLE {$rooms_table} (
             id int(10) unsigned NOT NULL AUTO_INCREMENT,
             roomNumber int(10) unsigned NOT NULL,
             totalArea float unsigned NOT NULL,
             PRIMARY KEY  (id),
             UNIQUE KEY number (roomNumber)
            );";

        dbDelta($rooms_table_sql);

        if (version_compare($installed_ver, '0.0.3') == -1){
            $sql = "ALTER TABLE " . $questions_table . " ADD FOREIGN KEY (pollId) REFERENCES " . $polls_table . "(id) ON DELETE CASCADE;";
            $wpdb->query($sql);
            $sql = "ALTER TABLE " . $answers_table . " ADD FOREIGN KEY (questionId) REFERENCES " . $questions_table . "(id) ON DELETE CASCADE;";
            $wpdb->query($sql);
        }

        update_option("hoa_polls_db_version", $hoa_polls_db_version);

    }

}

register_activation_hook(__FILE__,'wphoa_polls_install');
add_action( 'plugins_loaded', 'wphoa_polls_install' );

add_action('admin_menu', 'add_top_menu');

function add_top_menu() {
    add_menu_page('Homeowners', 'Homeowners', 'administrator', 'wp-hoa', 'wp_hoa_topmenu');
    add_submenu_page('wp-hoa', 'Homeowners', 'Polls2015', 'administrator', 'wp-hoa-polls2015', 'wp_hoa_submenu2015');
    add_submenu_page('wp-hoa', 'Homeowners', 'polls2016', 'administrator', 'wp-hoa-polls2016', 'wp_hoa_submenu2016');
}

function wp_hoa_router($controller, $action, $request) {
    require_once WP_HOA_ROOT . '/application/core/autoload.php';
    require_once WP_HOA_ROOT . '/application/core/model.php';
    require_once WP_HOA_ROOT . '/application/core/view.php';
    require_once WP_HOA_ROOT . '/application/core/controller.php';
    require_once WP_HOA_ROOT . '/application/core/route.php';
    Route::start_wp($controller, $action, $request);
}

function wp_hoa_topmenu() {
    wp_hoa_router('Main', 'index', '/');
}

function wp_hoa_submenu2015() {
    wp_hoa_router('poll', 'get', '1');
}

function wp_hoa_submenu2016() {
    wp_hoa_router('poll', 'get', '2');
}

//add_action('admin_menu', 'wp_hoa_router');

function hoa_polls_load_resources() {
    wp_register_style( 'hoa-polls', plugins_url('/css/style.css', __FILE__));
    wp_enqueue_style( 'hoa-polls' );
    wp_register_script( 'hoa-polls-script', plugins_url('/js/script.js', __FILE__));
    wp_enqueue_script( 'hoa-polls-script');
}

add_action('init', 'hoa_polls_load_resources');
