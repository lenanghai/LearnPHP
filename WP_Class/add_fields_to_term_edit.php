<?php
/**
 *  Create Manufactures taxonomy width fields
 * @author Kai
 */
add_action('init', 'create_manufactures_taxonomies', 10);
add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );

function create_manufactures_taxonomies() {
    // Add new taxonomy, make it hierarchical (like categories)
    $labels = array(
        'name' => __('Manufacture', 'orenmode'),
        'singular_name' => __('Manufacture', 'orenmode'),
        'search_items' => __('Search Manufacture', 'orenmode'),
        'all_items' => __('All Manufacture', 'orenmode'),
        'parent_item' => __('Parent Manufacture', 'orenmode'),
        'parent_item_colon' => __('Parent Manufacture:', 'orenmode'),
        'edit_item' => __('Edit Manufacture', 'orenmode'),
        'update_item' => __('Update Manufacture', 'orenmode'),
        'add_new_item' => __('Add New Manufacture', 'orenmode'),
        'new_item_name' => __('New Manufacture', 'orenmode'),
        'menu_name' => __('Manufactures', 'orenmode'),
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'manufacture'),
    );

    register_taxonomy('manufacture', 'product', $args);
}

function add_category_manufacture_fields() {
    ?>

    <div class="form-field">
        <label><?php _e('Thumbnail', 'woocommerce'); ?></label>
        <div id="product_cat_thumbnail" style="float:left;margin-right:10px;"><img src="<?php echo wc_placeholder_img_src(); ?>" width="30px" height="30px" /></div>
        <div style="line-height:60px;">
            <input type="hidden" id="product_cat_thumbnail_id" name="product_cat_thumbnail_id" />
            <button type="button" class="upload_image_button button"><?php _e('Upload/Add image', LANGUAGE); ?></button>
            <button type="button" class="remove_image_button button"><?php _e('Remove image', LANGUAGE); ?></button>
        </div>
        <script type="text/javascript">

            // Only show the "remove image" button when needed
            if (!jQuery('#product_cat_thumbnail_id').val())
                jQuery('.remove_image_button').hide();

            // Uploading files
            var file_frame;

            jQuery(document).on('click', '.upload_image_button', function(event) {

                event.preventDefault();

                // If the media frame already exists, reopen it.
                if (file_frame) {
                    file_frame.open();
                    return;
                }

                // Create the media frame.
                file_frame = wp.media.frames.downloadable_file = wp.media({
                    title: '<?php _e('Choose an image', LANGUAGE); ?>',
                    button: {
                        text: '<?php _e('Use image', LANGUAGE); ?>',
                    },
                    multiple: false
                });

                // When an image is selected, run a callback.
                file_frame.on('select', function() {
                    attachment = file_frame.state().get('selection').first().toJSON();

                    jQuery('#product_cat_thumbnail_id').val(attachment.id);
                    jQuery('#product_cat_thumbnail img').attr('src', attachment.url);
                    jQuery('.remove_image_button').show();
                });

                // Finally, open the modal.
                file_frame.open();
            });

            jQuery(document).on('click', '.remove_image_button', function(event) {
                jQuery('#product_cat_thumbnail img').attr('src', '<?php echo wc_placeholder_img_src(); ?>');
                jQuery('#product_cat_thumbnail_id').val('');
                jQuery('.remove_image_button').hide();
                return false;
            });

        </script>
        <div class="clear"></div>
    </div>
    <?php
}

add_action('manufacture_add_form_fields', 'add_category_manufacture_fields');

function manufacture_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['thumb'] = __('Image', LANGUAGE);

    unset($columns['cb']);

    return array_merge($new_columns, $columns);
}

add_filter('manage_edit-manufacture_columns', 'manufacture_columns');

function manufacture_custom_column($columns, $column, $id) {
    if ($column == 'thumb') {
        $thumbnail_id = get_term_meta($id, 'awe_thumbnail_id', true);
        if ($thumbnail_id)
            $image = wp_get_attachment_thumb_url($thumbnail_id);
        else
            $image = wc_placeholder_img_src();

        $image = str_replace(' ', '%20', $image);

        $columns .= '<img width="50" src="' . ( $image ) . '"  class="wp-post-image"/>';
    }
    return $columns;
}

add_filter('manage_manufacture_custom_column', 'manufacture_custom_column', 10, 3);



function save_category_fields($term_id, $tt_id, $taxonomy) {

    if (isset($_POST['product_cat_thumbnail_id'])) {
        update_term_meta($term_id, 'awe_thumbnail_id', absint($_POST['product_cat_thumbnail_id']));
    }
}
add_action( 'created_term', 'save_category_fields' , 10, 3 );
add_action('edit_term', 'save_category_fields', 10, 3);

function edit_category_manufacture_fields($term, $taxonomy) {

    $thumbnail_id = get_term_meta($term->term_id, 'awe_thumbnail_id', true);
    $image = "";
    if ($thumbnail_id)
        $image = wp_get_attachment_thumb_url($thumbnail_id);
    else
        $image = wc_placeholder_img_src();
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label><?php _e('Thumbnail', 'woocommerce'); ?></label></th>
        <td>
            <div id="product_cat_thumbnail" style="float:left;margin-right:10px;"><img src="<?php echo $image; ?>" width="60px" height="60px" /></div>
            <div style="line-height:60px;">
                <input type="hidden" id="product_cat_thumbnail_id" name="product_cat_thumbnail_id" value="<?php echo $thumbnail_id ?>" />
                <button type="button" class="upload_image_button button"><?php _e('Upload/Add image', LANGUAGE); ?></button>
                <button type="button" class="remove_image_button button"><?php _e('Remove image', LANGUAGE); ?></button>
            </div>
            <script type="text/javascript">

                // Uploading files
                var file_frame;

                jQuery(document).on('click', '.upload_image_button', function(event) {

                event.preventDefault();

                // If the media frame already exists, reopen it.
                if (file_frame) {
                    file_frame.open();
                    return;
                }

                // Create the media frame.
                file_frame = wp.media.frames.downloadable_file = wp.media({
                    title: '<?php _e('Choose an image', LANGUAGE); ?>',
                    button: {
                        text: '<?php _e('Use image', LANGUAGE); ?>',
                    },
                    multiple: false
                });

                // When an image is selected, run a callback.
                file_frame.on('select', function() {
                    attachment = file_frame.state().get('selection').first().toJSON();

                    jQuery('#product_cat_thumbnail_id').val(attachment.id);
                    jQuery('#product_cat_thumbnail img').attr('src', attachment.url);
                    jQuery('.remove_image_button').show();
                });

                // Finally, open the modal.
                file_frame.open();
            });

                jQuery(document).on('click', '.remove_image_button', function(event) {
                    jQuery('#product_cat_thumbnail img').attr('src', '<?php echo wc_placeholder_img_src(); ?>');
                    jQuery('#product_cat_thumbnail_id').val('');
                    jQuery('.remove_image_button').hide();
                    return false;
                });

            </script>
            <div class="clear"></div>
        </td>
    </tr>
    <?php
}

add_action('manufacture_edit_form_fields', 'edit_category_manufacture_fields', 10, 2);

?>
