<?php

// Creating the widget
class wpb_widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(

// Base ID of your widget
            'wpb_widget',

// Widget name will appear in UI
            esc_attr('WPBeginner Widget', 'wpb_widget_domain'),

// Widget description
            array('description' => esc_attr('Sample widget based on WPBeginner Tutorial', 'wpb_widget_domain'),)
        );
    }

// Creating widget front-end

    public function widget($args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);

// before and after widget arguments are defined by themes
        echo ($args['before_widget']);
        if (!empty($title))
            echo ($args['before_title'] . $title . $args['after_title']);

// This is where you run the code and display the output
        esc_attr_e('Hello, World!', 'wpb_widget_domain');
        echo ($args['after_widget']);

    }

// Widget Backend 
    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = esc_attr('New title', 'wpb_widget_domain');
        }
// Widget admin form
        ?>
        <p>
            <label for="<?php echo ($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:','wpbookingpro'); ?></label>
            <input class="widefat" id="<?php echo ($this->get_field_id('title')); ?>"
                   name="<?php echo ($this->get_field_name('title')); ?>" type="text"
                   value="<?php esc_html_e($title); ?>"/>
        </p>
        <?php
    }

// Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
} // Class wpb_widget ends here