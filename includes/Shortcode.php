<?php
namespace GTFA;

/**
 * Class Shortcode
 * Handles registration and rendering of shortcodes
 *
 * @package Gallery_Tools_For_Annmoyna
 */

if (!defined('ABSPATH')) {
    exit;
}

class Shortcode
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        add_shortcode('gallery_events', array($this, 'render_gallery_events'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    }

    /**
     * Enqueue required styles
     */
    public function enqueue_styles()
    {
        wp_enqueue_style('gtfa-styles', GTFA_PLUGIN_URL . 'assets/css/style.css', array(), GTFA_VERSION);
    }

    /**
     * Render gallery events grid
     *
     * @param array $atts Shortcode attributes
     * @return string
     */
    public function render_gallery_events($atts)
    {
        $atts = shortcode_atts(array(
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
            'link_text' => esc_html__('Click to View Photos', 'gallery-tools-for-annmoyna'),
            'post_ids' => '',
            'title_color' => '',
            'title_font_size' => '',
            'item_bg_color' => '',
            'date_color' => '',
            'date_font_size' => ''
        ), $atts);

        $query_args = array(
            'post_type' => PostType::POST_TYPE,
            'posts_per_page' => $atts['posts_per_page'],
            'orderby' => $atts['orderby'],
            'order' => $atts['order']
        );

        // Add post ID filtering if specified
        if (!empty($atts['post_ids'])) {
            $post_ids = array_map('trim', explode(',', $atts['post_ids']));
            $query_args['post__in'] = $post_ids;
        }

        $query = new \WP_Query($query_args);

        ob_start();

        if ($query->have_posts()):
            // Only output styles if custom values are provided
            $custom_styles = array();
            if (!empty($atts['item_bg_color'])) {
                $custom_styles[] = '.gtfa-gallery-item { background: ' . esc_attr($atts['item_bg_color']) . '; }';
            }
            if (!empty($atts['title_color']) || !empty($atts['title_font_size'])) {
                $title_style = '.gtfa-gallery-item-normal .gtfa-gallery-title {';
                if (!empty($atts['title_color'])) {
                    $title_style .= ' color: ' . esc_attr($atts['title_color']) . ';';
                }
                if (!empty($atts['title_font_size'])) {
                    $title_style .= ' font-size: ' . esc_attr($atts['title_font_size']) . ';';
                }
                $title_style .= ' }';
                $custom_styles[] = $title_style;
            }
            
            if (!empty($atts['date_color']) || !empty($atts['date_font_size'])) {
                $date_style = '.gtfa-gallery-item-normal .gtfa-gallery-date {';
                if (!empty($atts['date_color'])) {
                    $date_style .= ' color: ' . esc_attr($atts['date_color']) . ';';
                }
                if (!empty($atts['date_font_size'])) {
                    $date_style .= ' font-size: ' . esc_attr($atts['date_font_size']) . ';';
                }
                $date_style .= ' }';
                $custom_styles[] = $date_style;
            }
            
            if (!empty($custom_styles)) :
            ?>
            <style>
                <?php echo implode("\n                ", $custom_styles); ?>
            </style>
            <?php endif; ?>
            <div class="gtfa-gallery-grid">
                <?php
                while ($query->have_posts()):
                    $query->the_post();
                    $date = get_post_meta(get_the_ID(), '_gtfa_gallery_date', true);
                    $gallery_id = get_post_meta(get_the_ID(), '_gtfa_envira_gallery_id', true);

                    // Format date with ordinal suffix only if date exists
                    $formatted_date = '';
                    if ($date) {
                        $timestamp = strtotime($date);
                        $day = date('j', $timestamp);
                        $suffix = 'th';
                        if ($day % 10 == 1 && $day != 11)
                            $suffix = 'st';
                        if ($day % 10 == 2 && $day != 12)
                            $suffix = 'nd';
                        if ($day % 10 == 3 && $day != 13)
                            $suffix = 'rd';
                        $formatted_date = date('F ', $timestamp) . $day . $suffix . date(' Y', $timestamp);
                    }
                    ?>
                    <!--Single Item-->
                    <div class="gtfa-gallery-item">
                        <!--Normal Text-->
                        <div class="gtfa-gallery-item-normal">
                            <?php if ($gallery_id):
                                $thumbnail = get_the_post_thumbnail_url($gallery_id, 'full');
                                if ($thumbnail): ?>
                                    <div class="gtfa-gallery-thumbnail">
                                        <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                    </div>
                                <?php endif;
                            endif; ?>
                            <h3 class="gtfa-gallery-title"><?php echo esc_html(get_the_title()); ?></h3>
                            <?php if (!empty($formatted_date)): ?>
                                <div class="gtfa-gallery-date">
                                    <?php echo esc_html($formatted_date); ?>
                                </div>
                            <?php endif; ?>
                        </div><!--/ Normal Text-->
                        <!--Hover -->
                        <div class="gtfa-gallery-hover">
                            <?php if ($gallery_id): ?>
                                <div class="gtfa-gallery-shortcode">
                                    <?php echo do_shortcode('[envira-link id="' . $gallery_id . '"]' . esc_html($atts['link_text']) . '[/envira-link]'); ?>
                                </div>
                            <?php endif; ?>
                        </div><!-- Hover -->
                    </div><!--/ Single Item-->
                    <?php
                endwhile;
                ?>
            </div>
            <?php
        endif;

        wp_reset_postdata();

        return ob_get_clean();
    }
}