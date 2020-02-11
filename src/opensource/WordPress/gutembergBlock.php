<?php


namespace WooBooking\CMS\OpenSource\WordPress;

use Factory;
use WooBooking\CMS\Utilities\Utility;

class gutembergBlock
{
    public static $instance=null;
    public static function getInstance(){
        if (!isset(self::$instance))
        {
            self::$instance=  new gutembergBlock();
        }
        return self::$instance;
    }
    public function __construct()
    {

    }
    public function init(){
        add_action( 'enqueue_block_editor_assets',array($this,'mdlr_editable_block_example_backend_enqueue') );
        add_filter('block_categories', array($this, 'woobooking_block_category'), 10, 2);

    }
    function mdlr_editable_block_example_backend_enqueue() {
        $open_source=Factory::getOpenSource();
        $list_view = $open_source->get_list_layout_block_frontend();

        wp_enqueue_script(
            'backend-list-block', // Unique handle.
            plugins_url( 'gutembergBlock/mb-block.js', __FILE__ ), // Block.js: We register the block here.
            array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
            filemtime( plugin_dir_path( __FILE__ ) . 'gutembergBlock/mb-block.js' ) // filemtime — Gets file modification time.
        );
        $api_task="/wp-json/".$open_source::$namespace.$open_source->get_api_task();
        $api_task_frontend="/wp-json/".$open_source::$namespace.$open_source->get_api_task_frontend();
        wp_localize_script('backend-list-block', 'list_view', $list_view);
        wp_localize_script('backend-list-block', 'root_url', Factory::getRootUrl());
        wp_localize_script('backend-list-block', 'root_url_plugin', Factory::getRootUrlPlugin());
        wp_localize_script('backend-list-block', 'api_task', $api_task);
        wp_localize_script('backend-list-block', 'api_task_frontend', $api_task_frontend);
        wp_enqueue_script('less-init', Factory::getRootUrlPlugin() .'resources/js/less/less.min.js' );

    }
    function woobooking_block_category($categories, $post)
    {
        return array_merge(
            $categories,
            array(
                array(
                    'slug' => 'woobooking-block',
                    'title' => __('Woo booking block', 'woobooking-block'),
                ),
            )
        );
    }

}