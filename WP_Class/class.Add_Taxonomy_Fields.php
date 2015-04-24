<?php
/**
 * Add Color Box to Color Attribute ( custom taxonomy )
 * @author Kai Dev
 * @version 1.0
 */

// Check Woocommerce Install
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if (is_plugin_active('woocommerce/woocommerce.php')) {
    include_once( ABSPATH . 'wp-content/plugins/woocommerce/woocommerce.php' );
}

// Main Class
if (!class_exists('Awe_Attribute')) {

    class Awe_Attribute {

        public function __construct() {
            add_action('create_pa_color', array($this, 'save_color_meta'), 10, 2);
            add_action('edited_pa_color', array($this, 'save_color_meta'), 10, 2);     
            add_action('pa_color_add_form_fields', array($this, 'add_color_box'));
            add_action('pa_color_edit_form_fields', array($this, 'edit_color_box'), 10);
            add_action('admin_head-edit-tags.php', array($this, 'set_color_box_script'), 10);
            add_action('wp_enqueue_scripts', array($this, 'set_attribute_script'), 10);
        }

        public function set_color_box_script() {
            wp_enqueue_style( 'wp-color-picker' );
             wp_enqueue_script( 'wp-color-picker');
            ?>
            <script>
                jQuery(document).ready(function () {
                    jQuery("#att_color_box").wpColorPicker();
                });
            </script>
            <?php
        }

        public function set_attribute_script() {
            wp_enqueue_script('awe-custom-attribute', plugins_url('js/attribute.js', __FILE__), array('jquery'));
            wp_localize_script('awe-custom-attribute', 'att', array(
                'url' => admin_url('admin-ajax.php')
            ));
        }

// Add custom fields box
        public function add_color_box() {
            ?>
            <div class="form-field">
                <label for="term_meta[color_box]"><?php _e('Color Box', 'orenmode'); ?></label>
                <input type="text" name="term_meta[color_box]" id="att_color_box" value="" data-default-color="#effeff">
                <p class="description"><?php _e('Pick a color attribute', 'orenmode'); ?></p>
            </div>
            <?php
        }

        public function edit_color_box($term) {
            // put the term ID into a variable
            $t_id = $term->term_id;            
            $term_meta = get_woocommerce_term_meta($t_id, 'hex_color', true);
            ?>
            <tr class="form-field">
                <th scope="row" valign="top"><label for="term_meta[color_box]"><?php _e('Color Box', 'orenmode'); ?></label></th>
                <td>
                    <input type="text" name="term_meta[color_box]" id="att_color_box" value="<?php echo esc_attr($term_meta['color_box']) ? esc_attr($term_meta['color_box']) : ''; ?>">
                    <p class="description"><?php _e('Pick a color attribute', 'orenmode'); ?></p>
                </td>
            </tr>
            <?php
        }

        // Set Hex Color Meta
        function save_color_meta($term_id) {
            if (isset($_POST['term_meta'])) {
                $t_id = $term_id;
                update_woocommerce_term_meta($t_id, 'hex_color', $_POST['term_meta']);
            }
        }

    }

}
