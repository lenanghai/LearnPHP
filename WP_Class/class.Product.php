<?php
/**
 * Generate Support Widget
 * @author Kai
 * @version 1.0
 * @since 4/2015
 * @package WP_Widget
 */

// Register Widget
add_action('widgets_init', create_function('', "register_widget('Kai_Support_Online');"));

class Kai_Support_Online extends WP_Widget {
	function Kai_Support_Online() {
		$widget_ops = array( 'classname' => 'support-online-widget', 'description' => __('Thêm nick yahoo, skype hỗ trợ trên website', 'genesis') );
		$control_ops = array( 'width' => 505, 'height' => 250, 'id_base' => 'support-online' );
		$this->WP_Widget( 'support-online', __('RT - Hỗ trợ trực tuyến', 'genesis'), $widget_ops, $control_ops );
	}

	function widget($args, $instance) {
		extract($args);
		$instance = wp_parse_args( (array)$instance, array(
			'title' => '',
            'number_supporter' => 1,
            'tel' => '',
			'hotline' => '',
            'email' => '',
            'link_1' => '',
            'link_1_text' => '',
            'link_2' => '',
            'link_2_text' => ''
		) );

			echo $before_widget;
			if (!empty($instance['title']))
				echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title;
            echo '<div class = "wrap">';
            ?>

            <div id="supporter-info">

			<?php
            if(!empty($instance['tel']) || !empty($instance['hotline']) || !empty($instance['email']) ) {
                echo '<div class="hotline-area">';
                echo !empty($instance['tel']) ? '<span>' . __('Hotline: ', 'genesis') . '</span><span  class="tel">'.$instance['tel'].'</span>&nbsp;&nbsp;' : '';
                echo !empty($instance['hotline']) ? '<span>' . __('Hotline: ', 'genesis').'</span><span class="hotline">'.$instance['hotline'].'</span>' : '';
                echo !empty($instance['email']) ? __('Email: ', 'genesis').'<span>'.$instance['email'].'</span>' : '';
                echo '</div>';
            }
            ?>

				<?php
				for($i = 1;$i<=$instance['number_supporter'];$i++){
				?>
					<div id="support-<?php echo $i; ?>" class="supporter">
					<div class="info">
				<?php if(!empty($instance['supporter_'.$i.'_name'])) : ?><span class="name"><?php echo $instance['supporter_'.$i.'_name']; ?></span><br /><?php endif; ?>
				<?php if(!empty($instance['supporter_'.$i.'_phone'])) : ?><span class="phone"><?php echo $instance['supporter_'.$i.'_phone']; ?></span><?php endif; ?>
					</div>
				<div class="online">
					<?php if(!empty($instance['supporter_'.$i.'_yahoo'])) : ?><p class="yahoo"><a href="<?php echo 'ymsgr:sendim?' . $instance['supporter_'.$i.'_yahoo']; ?>"><img src="<?php echo 'http://opi.yahoo.com/online?u='.$instance['supporter_'.$i.'_yahoo'].'&amp;m=g&amp;t='.$instance['supporter_'.$i.'_yahoo_status']; ?>" alt="<?php _e('Hỗ trợ trực tuyến','genesis'); ?>" /></a></p><?php endif; ?>

					<?php if(!empty($instance['supporter_'.$i.'_skype'])) : ?><p class="skype"><a href="<?php echo 'skype:'.$instance['supporter_'.$i.'_skype'].'?chat'; ?>"><img src="http://mystatus.skype.com/smallclassic/<?php echo $instance['supporter_'.$i.'_skype']; ?>" style="border: none;" width="114" height="20" alt="My status" /></a></p><?php endif; ?>
				</div>

					</div>

				<?php
				}
				?>
			</div>

			<?php
            echo '<div class="clear"></div>';
            echo '</div><!-- end .wrap -->';
			echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		return $new_instance;
	}

	function form($instance) {

		$instance = wp_parse_args( (array)$instance, array(
			'title' => '',
            'number_supporter' => 1,
            'tel' => '',
			'hotline' => '',
            'email' => '',
            'link_1' => '',
            'link_1_text' => '',
            'link_2' => '',
            'link_2_text' => ''
		) );

?>


		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Tiêu đề', 'genesis'); ?>:</label>
		<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:95%;" /></p>

        <hr />

        <p><label for="<?php echo $this->get_field_id('number_supporter'); ?>"><?php _e('Số hỗ trợ viên', 'genesis'); ?>:</label>
		<input type="text" id="<?php echo $this->get_field_id('number_supporter'); ?>" name="<?php echo $this->get_field_name('number_supporter'); ?>" value="<?php echo esc_attr( $instance['number_supporter'] ); ?>" style="width:20%;" />
        <input type="submit" name="savewidget" id="savewidget" class="button-primary widget-control-save" value="Lưu" />
        <img alt="" style="padding-bottom: 4px;" title="" class="ajax-feedback " src="<?php echo home_url('/'); ?>wp-admin/images/wpspin_light.gif"></p>

        <?php for($i = 1;$i<=$instance['number_supporter'];$i++){   ?>
            <div style="width: 47%; padding: 5px; margin: 5px 0; background: #eee; <?php echo $i % 2 ==0 ? 'float: right;' : 'float: left;'; ?>"  >
            <strong><?php _e('Hỗ trợ viên ', 'genesis'); echo $i; ?></strong>
    		<p><label for="<?php echo $this->get_field_id('supporter_'.$i.'_name'); ?>"><?php _e('Tên', 'genesis'); ?>:</label>
    		<input type="text" id="<?php echo $this->get_field_id('supporter_'.$i.'_name'); ?>" name="<?php echo $this->get_field_name('supporter_'.$i.'_name'); ?>" value="<?php echo esc_attr( $instance['supporter_'.$i.'_name'] ); ?>" style="width:95%;" /></p>

    		<p><label for="<?php echo $this->get_field_id('supporter_'.$i.'_phone'); ?>"><?php _e('Điện thoại', 'genesis'); ?>:</label>
    		<input type="text" id="<?php echo $this->get_field_id('supporter_'.$i.'_phone'); ?>" name="<?php echo $this->get_field_name('supporter_'.$i.'_phone'); ?>" value="<?php echo esc_attr( $instance['supporter_'.$i.'_phone'] ); ?>" style="width:95%;" /></p>

            <p><label for="<?php echo $this->get_field_id('supporter_'.$i.'_yahoo'); ?>"><?php _e('Yahoo ID(không gồm @yahoo.com)', 'genesis'); ?>:</label>
    		<input type="text" id="<?php echo $this->get_field_id('supporter_'.$i.'_yahoo'); ?>" name="<?php echo $this->get_field_name('supporter_'.$i.'_yahoo'); ?>" value="<?php echo esc_attr( $instance['supporter_'.$i.'_yahoo'] ); ?>" style="width:95%;" /></p>

			<p><label for="<?php echo $this->get_field_id('supporter_'.$i.'_yahoo_status'); ?>"><?php _e('Yahoo Icon', 'genesis'); ?>:</label>
    		<select name="<?php echo $this->get_field_name('supporter_'.$i.'_yahoo_status'); ?>" id="<?php echo $this->get_field_id('supporter_'.$i.'_yahoo_status'); ?>">
				<?php  for($j = 1; $j < 23; $j++ ) : ?>
					<option value="<?php echo $j; ?>" <?php checked($j, $instance['supporter_'.$i.'_yahoo_status']); ?>><?php echo $j; ?></option>
				<?php endfor; ?>
			</select>
			</p>

            <p><label for="<?php echo $this->get_field_id('supporter_'.$i.'_skype'); ?>"><?php _e('Skype ID ', 'genesis'); ?>:</label>
    		<input type="text" id="<?php echo $this->get_field_id('supporter_'.$i.'_skype'); ?>" name="<?php echo $this->get_field_name('supporter_'.$i.'_skype'); ?>" value="<?php echo esc_attr( $instance['supporter_'.$i.'_skype'] ); ?>" style="width:95%;" /></p>
            </div>
		<?php } ?>


		<div class="clear"></div>

	<?php
	}
}
