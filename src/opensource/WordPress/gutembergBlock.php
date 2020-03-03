<?php


namespace WooBooking\CMS\OpenSource\WordPress;

use Exception;
use Factory;
use WooBooking\CMS\Utilities\Utility;
use woobooking_controller;
use WoobookingModel;
use WooBookingView;

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
    public function render_gutenberg_dynamic($atts){
        $input=Factory::getInput();
        $open_source_client_id=$atts['open_source_client_id'];
        $blockModel=WoobookingModel::getInstance('block');
        $block=$blockModel->getBlockByOpenSourceId($open_source_client_id);
        if(!$block){
            return false;
        }
        $input->set('id',$block->id);
        $view=WooBookingView::getInstance('block');
        return $view->display('preview');
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
        wp_localize_script('backend-list-block', 'wpbookingpro_root_url', Factory::getRootUrl());
        wp_localize_script('backend-list-block', 'wpbookingpro_root_url_plugin', Factory::getRootUrlPlugin());
        wp_localize_script('backend-list-block', 'wpbookingpro_api_task', $api_task);
        wp_localize_script('backend-list-block', 'wpbookingpro_api_task_frontend', $api_task_frontend);
        wp_enqueue_script('less-init', Factory::getRootUrlPlugin() .'resources/js/less/less.min.js' );
        wp_enqueue_script('jquery-confirm-script', Factory::getRootUrlPlugin() .'resources/js/jquery-confirm-master/dist/jquery-confirm.min.js' );
        wp_enqueue_style('jquery-confirm-css', Factory::getRootUrlPlugin() .'resources/js/jquery-confirm-master/dist/jquery-confirm.min.css' );
        wp_enqueue_script('jquery-serializeObject', Factory::getRootUrlPlugin() .'admin/resources/js/form-serializeObject/jquery.serializeObject.js' );
        wp_enqueue_script('jquery-serializeToJSON', Factory::getRootUrlPlugin() .'admin/resources/js/form-serializeObject/jquery.serializeToJSON.js' );

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