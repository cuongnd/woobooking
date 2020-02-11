<?php


use WooBooking\CMS\OpenSource\WordPress\gutembergBlock;

class Shortcode
{
    public static $instance=null;
    public static function getInstance(){
        if (!isset(self::$instance))
        {
            self::$instance=  new Shortcode();
        }
        return self::$instance;
    }
    public function __construct()
    {

    }
}