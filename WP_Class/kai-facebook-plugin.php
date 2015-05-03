<?php
/* -----------------------------------------------------------------------------------

  Plugin Name: Kai Facebook Like Box
  Description: A widget for displaying Facebook Like Box.
  Version: 1.1

  ----------------------------------------------------------------------------------- */
add_action('widgets_init', 'facebook_like_load_widgets');

function facebook_like_load_widgets() {
    register_widget('Facebook_Like_Widget');
}

class Facebook_Like_Widget extends WP_Widget {

    function Facebook_Like_Widget() {
        $widget_ops = array('classname' => 'facebook_like', 'description' => __('Kai - Facebook Like Box.', 'kai'));

        $control_ops = array('id_base' => 'facebook-like-widget');

        $this->WP_Widget('facebook-like-widget', __('Kai - Facebook Like Box', 'kai'), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);

        $title = apply_filters('widget_title', $instance['title']);
        $page_url = $instance['page_url'];
        $width = $instance['width'];
        $show_faces = isset($instance['show_faces']) ? 'true' : 'false';

        $height = '65';

        if ($show_faces == 'true') {
            $height = '239';
        }

        if ($show_header == 'true') {
            $height = '264';
        }

        if ($show_stream == 'true') {
            $height = '600';
        }

        echo $before_widget;

        if ($title) {
            echo $before_title . $after_title;
        }

        if ($page_url):
            ?>
            <div id="fb-root"></div>
            <script>(function (d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id))
                        return;
                    js = d.createElement(s);
                    js.id = id;
                    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3&appId=1510075135913200";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>
            <div class="fb-page" data-href="<?php echo $page_url; ?>" data-width="<?php echo $width; ?>" data-hide-cover="false" data-show-facepile="<?php echo $show_faces; ?>" data-show-posts="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="<?php echo $page_url; ?>"><a href="<?php echo $page_url; ?>"><?php echo $title; ?></a></blockquote></div></div>
        <?php
        endif;

        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);
        $instance['page_url'] = $new_instance['page_url'];
        $instance['width'] = $new_instance['width'];
        $instance['show_faces'] = $new_instance['show_faces'];


        return $instance;
    }

    function form($instance) {
        $defaults = array('title' => __('Tìm chúng tôi trên Facebook', 'kai'), 'page_url' => '', 'width' => '275', 'color_scheme' => 'light', 'show_faces' => 'on', 'show_stream' => false, 'show_header' => false);
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Tiêu đề', 'kai'); ?>:</label>
            <input type="text" class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('page_url'); ?>"><?php _e('Địa chỉ Facebook', 'kai'); ?>:</label>
            <input type="text" class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('page_url'); ?>" name="<?php echo $this->get_field_name('page_url'); ?>" value="<?php echo $instance['page_url']; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Chiều rộng', 'kai'); ?>:</label>
            <input type="text" class="widefat" style="width: 40px;" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" value="<?php echo $instance['width']; ?>" />
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['show_faces'], 'on'); ?> id="<?php echo $this->get_field_id('show_faces'); ?>" name="<?php echo $this->get_field_name('show_faces'); ?>" /> 
            <label for="<?php echo $this->get_field_id('show_faces'); ?>"><?php _e('Hiển thị avatar', 'mythemeshop'); ?></label>
        </p>


        <?php
    }

}
?>
