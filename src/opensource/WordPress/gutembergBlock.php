<?php


namespace WooBooking\CMS\OpenSource\WordPress;
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
        add_action( 'init', array($this,'mb_block_init') );
        /**
         * Create your Gutemberg category
         *
         * @param $categories
         * @param $post
         * @return array
         */
        add_filter( 'block_categories', array($this,'mb_block_categories'), 10, 2 );
    }
    function mb_block_init()
    {
        /**
         * Check if Gutemberg is active
         */
        if (!function_exists('register_block_type'))
            return;

        /**
         * Register our block editor script
         */
        wp_register_script(
            'mb-simple-block',
            plugins_url( 'gutembergBlock/mb-block.js', __FILE__ ),
            array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' )
        );

        /**
         * Register our block, and explicitly define the attributes we accept
         */
        register_block_type( 'mbideas/mb-simple-block', array(

            /** Define the attributes used in your block */

            'attributes'  => array(
                'mb_title' => array(
                    'type' => 'string'
                ),
                'mb_text' => array(
                    'type' => 'string'
                ),
                'mb_url' => array(
                    'type' => 'string'
                )
            ),

            /** Define the category for your block */
            'category' => 'mb.ideas',

            /** The script name we gave in the wp_register_script() call */
            'editor_script'   => 'mb-simple-block',

            /** The callback called by the javascript file to render the block */
            'render_callback' => 'mb_block_render',
        ) );

    }
    /**
     * Define the server side callback to render your block in the front end
     *
     * @param $attributes
     * @return string
     * @param array $attributes The attributes that were set on the block or shortcode.
     */
    function mb_block_render( $attributes )
    {
        /** @var  $is_in_edit_mode  Check if we are in the editor */
        $is_in_edit_mode = strrpos($_SERVER['REQUEST_URI'], "context=edit");

        /** @var  $UID  Unique ID for the element*/
        $UID = rand(0, 10000);

        /** If we are in the editor */
        if ($is_in_edit_mode) {

            /** If the specific attribute exist (it's not new) */
            if($attributes['mb_text']){
                $content = '<div class="mb-editor-content" id="mb-editor-content_' . $UID . '">';
                $content .= '<h2 class="mb-editor-title"> ' . $attributes['mb_title'] . '</h2>';
                $content .= '<p class="mb-editor-text"> ' . $attributes['mb_text'] . '</p>';
                $content .= '<a class="mb-editor-url" href="' . $attributes['mb_url'] . '"> ' . $attributes['mb_url'] . '</a>';
                $content .= '</div>';

                /** If it's new */
            } else {
                $content = '<div class="mb-editor-content" id="mb-editor-content_' . $UID . '">';
                $content .= '<h2 class="mb-editor-title"> ' . $attributes['mb_title'] . '</h2>';
                $content .= '<p class="mb-editor-text"> Insert your content</p>';
                $content .= '</div>';
            }

            /** If we are in the front end */
        } else {
            $content = '<div class="mb-editor-content" id="mb-editor-content_' . $UID . '" style="background:#f3f3f3; padding:20px">';
            $content .= '<h2 class="mb-editor-title"> ' . $attributes['mb_title'] . '</h2>';
            $content .= '<p class="mb-editor-text"> ' . $attributes['mb_text'] . '</p>';
            $content .= '<a class="mb-editor-url" href="' . $attributes['mb_url'] . '"> ' . $attributes['mb_url'] . '</a>';
            $content .= '</div>';
        }
        return $content;
    }

}