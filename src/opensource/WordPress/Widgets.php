<?php


namespace WooBooking\CMS\OpenSource\WordPress;


use Exception;
use Factory;
use WoobookingModel;
use WooBookingView;


class Widgets
{
    public static $instance=null;
    public static function getInstance(){
        if (!isset(self::$instance))
        {
            self::$instance=  new Widgets();
        }
        return self::$instance;
    }
    public function __construct()
    {

    }
    public function init()
    {


        add_shortcode("woobooking_elementor_block", array($this, 'woo_booking_render_block_by_elementor_func'));

        // Check if Elementor installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
            return;
        }

        // Check for required Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, WPBOOKINGPRO_MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
            return;
        }

        // Check for required PHP version
        if ( version_compare( PHP_VERSION, WPBOOKINGPRO_MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
            return;
        }

        // Register widget scripts
        add_action( 'elementor/frontend/after_register_scripts', array($this, 'widget_scripts' ) );

        // Register widgets
        add_action( 'elementor/widgets/widgets_registered',  array($this, 'register_widgets' ) );
    }
    /**
     * Admin notice
     *
     * Warning when the site doesn't have Elementor installed or activated.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_missing_main_plugin() {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-hello-world' ),
            '<strong>' . esc_html__( 'Elementor wp booking pro', 'wpbookingpro' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'wpbookingpro' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required Elementor version.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_minimum_elementor_version() {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'wpbookingpro' ),
            '<strong>' . esc_html__( 'Elementor wp booking pro', 'wpbookingpro' ) . '</strong>',
            '<strong>' . esc_html__( 'Elementor', 'wpbookingpro' ) . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 1.0.0
     * @access public
     */
    public function admin_notice_minimum_php_version() {
        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }

        $message = sprintf(
        /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'wpbookingpro' ),
            '<strong>' . esc_html__( 'Elementor wp booking pro', 'wpbookingpro' ) . '</strong>',
            '<strong>' . esc_html__( 'PHP', 'wpbookingpro' ) . '</strong>',
            MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    function woo_booking_render_block_by_elementor_func($atts, $content, $a_view)
    {

        $input=Factory::getInput();
        $data=$input->getData();
        if(isset($data['elementor-preview'])){
            return;
        }
        $open_source_client_id=$atts['open_source_client_id'];
        $block_name=$atts['open_source_client_id'];
        $block_name=$atts['block_name'];
        $modelBlock = WoobookingModel::getInstance('block');
        if ($open_source_client_id != "") {
            $block = $modelBlock->getBlockByOpenSourceId($open_source_client_id);
        } else {
            throw new Exception("can not found open_source_client_id");
        }

        if (!isset($block->id) || !$block->id) {
            $array_block = array(
                'type' => $block_name,
                'open_source_client_id' => $open_source_client_id,
            );
            $block = $modelBlock->save($array_block);
        }
        $input=Factory::getInput();
        $open_source_client_id=$atts['open_source_client_id'];
        $blockModel=WoobookingModel::getInstance('block');
        $block=$blockModel->getBlockByOpenSourceId($open_source_client_id);
        if(!$block){
            return false;
        }
        $wordpress=Factory::getOpenSource();
        $input->set('id',$block->id);
        $view=WooBookingView::getInstance('block');
        $view->view="block";
        $content=  $view->display('preview');
        add_action('wp_footer', array($wordpress, "wp_hook_add_script_footer"));
        return $content;
    }

    /**
     * Register Widgets
     *
     * Register new Elementor widgets.
     *
     * @since 1.2.0
     * @access public
     */
    public function register_widgets() {
        // Its is now safe to include Widgets files
        $client=is_admin()?1:0;
        $wordpress=Factory::getOpenSource();
        $list_block_front_end=$wordpress->get_list_layout_block_frontend();

        foreach($list_block_front_end as $key=> $block){
            $file_widget=WPBOOKINGPRO_ROOT_PATH_PLUGIN."/blocks/block_$key/{$key}Widget.php";
            if(file_exists($file_widget)) {
                require_once $file_widget;
                $class_block_widget = "{$key}Widget";
                \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new $class_block_widget());
            }

        }
    }


    /**
     * widget_scripts
     *
     * Load required plugin core files.
     *
     * @since 1.2.0
     * @access public
     */
    public function widget_scripts() {

        wp_register_script( 'touroperatorpro', plugins_url( 'assets/js/hello-world.js', __FILE__ ), array('jquery' ), false, true );
    }


    /**
     * Include Widgets files
     *
     * Load widgets files
     *
     * @since 1.2.0
     * @access private
     */

}