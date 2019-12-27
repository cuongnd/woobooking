<?php
namespace WooBooking\CMS\OpenSource\WordPress\ECommerce;
use WooBooking\CMS\OpenSource\WordPress\ECommerce\WooCommerce\WooCommerce;
class ECommerce
{
    public static $instance=null;
    public static function getInstance(){
        if(!self::$instance){
            $using_ECommerce="WooCommerce";
            //set system using WooCommerce
            WooBookingImport('WooBooking.opensource.WordPress.ECommerce.WooCommerce.WooCommerce');
            self::$instance=new WooCommerce();
            self::$instance->run();
        }
        return self::$instance;
    }
}