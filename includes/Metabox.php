<?php
namespace GTFA;
/**
 * Metabox class for handling custom fields
 *
 * @package Gallery_Tools_For_Annmoyna
 */

if (!defined('ABSPATH')) {
    exit;
}


class Metabox {
    /**
     * Constructor.
     */
    public function __construct() {    
        // Use init hook with priority 99 to ensure post types are registered
        add_action('init', array($this, 'register_meta_box_hook'), 999);
        add_action('save_post', array($this, 'save_meta_box'));
    }

    /**
     * Register meta box hook after checking post type exists
     */
    public function register_meta_box_hook() {
        // Add meta box for gallery_event post type
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
    }

    /**
     * Add meta box
     */
    public function add_meta_box() {
        add_meta_box(
            'gtfa_gallery_details',
            __('Gallery Details', 'gallery-tools-for-annmoyna'),
            array($this, 'render_meta_box'),
            PostType::POST_TYPE,
            'normal',
            'high'
        );
    }

    /**
     * Render meta box
     *
     * @param WP_Post $post Post object.
     */
    public function render_meta_box($post) {
        // Add nonce for security
        wp_nonce_field('gtfa_gallery_details', 'gtfa_gallery_details_nonce');

        // Get saved values
        $date = get_post_meta($post->ID, '_gtfa_gallery_date', true);
        $shortcode = get_post_meta($post->ID, '_gtfa_gallery_shortcode', true);
        $envira_gallery_id = get_post_meta($post->ID, '_gtfa_envira_gallery_id', true);
        ?>
        <div class="gtfa-meta-box-wrap">
            <p>
                <label for="gtfa_gallery_date"><?php esc_html_e('Gallery Date:', 'gallery-tools-for-annmoyna'); ?></label>
                <input type="date" id="gtfa_gallery_date" name="gtfa_gallery_date" value="<?php echo esc_attr($date); ?>" class="widefat">
            </p>
            <!-- <p>
                <label for="gtfa_gallery_shortcode"><?php esc_html_e('Gallery Shortcode:', 'gallery-tools-for-annmoyna'); ?></label>
                <input type="text" id="gtfa_gallery_shortcode" name="gtfa_gallery_shortcode" value="<?php echo esc_attr($shortcode); ?>" class="widefat">
            </p> -->
            <p>
                <label for="gtfa_envira_gallery"><?php esc_html_e('Select Envira Gallery:', 'gallery-tools-for-annmoyna'); ?></label>
                <select id="gtfa_envira_gallery" name="gtfa_envira_gallery" class="widefat">
                    <option value=""><?php esc_html_e('-- Select a Gallery --', 'gallery-tools-for-annmoyna'); ?></option>
                    <?php
                    $envira_galleries = get_posts(array(
                        'post_type' => 'envira',
                        'posts_per_page' => -1,
                        'orderby' => 'title',
                        'order' => 'ASC'
                    ));
                    
                    foreach ($envira_galleries as $gallery) {
                        printf(
                            '<option value="%s" %s>%s</option>',
                            esc_attr($gallery->ID),
                            selected($envira_gallery_id, $gallery->ID, false),
                            esc_html($gallery->post_title)
                        );
                    }
                    ?>
                </select>
            </p>
        </div>
        <?php
    }

    /**
     * Save meta box
     *
     * @param int $post_id Post ID.
     */
    public function save_meta_box($post_id) {
        // Check if our nonce is set
        if (!isset($_POST['gtfa_gallery_details_nonce'])) {
            return;
        }

        // Verify that the nonce is valid
        if (!wp_verify_nonce($_POST['gtfa_gallery_details_nonce'], 'gtfa_gallery_details')) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check the user's permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save the meta field values
        if (isset($_POST['gtfa_gallery_date'])) {
            update_post_meta($post_id, '_gtfa_gallery_date', sanitize_text_field($_POST['gtfa_gallery_date']));
        }

        // if (isset($_POST['gtfa_gallery_shortcode'])) {
        //     update_post_meta($post_id, '_gtfa_gallery_shortcode', sanitize_text_field($_POST['gtfa_gallery_shortcode']));
        // }

        // Save Envira gallery ID
        if (isset($_POST['gtfa_envira_gallery'])) {
            update_post_meta($post_id, '_gtfa_envira_gallery_id', absint($_POST['gtfa_envira_gallery']));
        }
    }
}
