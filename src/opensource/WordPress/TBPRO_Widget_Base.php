<?php
namespace WooBooking\CMS\OpenSource\Wordpress;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use TourOperatorPro\Factory;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
abstract class TBPRO_Widget_Base extends Widget_Base  {

    /**
     * Retrieve the widget name.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        $class_call= get_called_class();
        $class_call=strtolower($class_call);
        $class_call=str_replace("widget","",$class_call);
        return  $class_call;
    }
    /**
     * Retrieve the widget title.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        $class_call= get_called_class();
        $class_call=strtolower($class_call);
        $class_call=str_replace("widget","",$class_call);
        $class_call=str_replace("_"," ",$class_call);
        return __( $class_call, 'touroperatorpro' );
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-posts-ticker';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return [ 'general' ];
    }

    /**
     * Retrieve the list of scripts the widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.0.0
     *
     * @access public
     *
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends() {
        return [ 'elementor-hello-world' ];
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function _register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __( 'Content', 'elementor-hello-world' ),
            ]
        );




        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Style', 'elementor-hello-world' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function render() {
        $id=$this->get_id();

        $input=Factory::getInput();
        $input->set('open_source_client_id',$id);
        $block_name=$this->get_name();
        $input->set('type',$block_name);
        $data=$input->getData();
        ob_start();
        ?>
        [touroperatorpro_elementor_block block_name="<?php echo $block_name ?>" open_source_client_id="<?php echo $id ?>"]
        <?php
        $content=ob_get_clean();
        echo $content;
        //echo json_encode($data);

    }

    /**
     * Render the widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     *
     * @access protected
     */
    protected function _content_template() {
        ?>
        <div class="title">
            {{{ settings.title }}}
        </div>
        <?php
    }
}
