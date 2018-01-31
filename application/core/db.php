<?php


class Db
{
    private static $instance = null;

    public static function getInstance()
    {
        global $wpdb;
        self::$instance = $wpdb;
        return self::$instance;
    }

    private function __clone() {}

    private function __construct() {}
}