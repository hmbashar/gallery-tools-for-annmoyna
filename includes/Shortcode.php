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

class Shortcode {
    /**
     * Constructor.
     */
    public function __construct() {
        add_shortcode('gallery_events', array($this, 'render_gallery_events'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
    }

    /**
     * Enqueue required styles
     */
    public function enqueue_styles() {
        wp_enqueue_style('gtfa-styles', GTFA_PLUGIN_URL . 'assets/css/style.css', array(), GTFA_VERSION);
    }

    /**
     * Render gallery events grid
     *
     * @param array $atts Shortcode attributes
     * @return string
     */
    public function render_gallery_events($atts) {
        $atts = shortcode_atts(array(
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC'
        ), $atts);

        $query = new \WP_Query(array(
            'post_type' => PostType::POST_TYPE,
            'posts_per_page' => $atts['posts_per_page'],
            'orderby' => $atts['orderby'],
            'order' => $atts['order']
        ));

        ob_start();

        if ($query->have_posts()) :
            ?>
            <div class="gtfa-gallery-grid">
                <?php
                while ($query->have_posts()) : $query->the_post();                    
                    $date = get_post_meta(get_the_ID(), '_gtfa_gallery_date', true);
                    $shortcode = get_post_meta(get_the_ID(), '_gtfa_gallery_shortcode', true);
                    $gallery_id = get_post_meta(get_the_ID(), '_gtfa_envira_gallery_id', true);

                    // Format date with ordinal suffix
                    if ($date) {
                        $timestamp = strtotime($date);
                        $day = date('j', $timestamp);
                        $suffix = 'th';
                        if ($day % 10 == 1 && $day != 11) $suffix = 'st';
                        if ($day % 10 == 2 && $day != 12) $suffix = 'nd';
                        if ($day % 10 == 3 && $day != 13) $suffix = 'rd';
                        $formatted_date = date('F ', $timestamp) . $day . $suffix . date(' Y', $timestamp);
                    }
                    ?>
                    <div class="gtfa-gallery-item">
                        <?php if ($gallery_id) :
                            $thumbnail = get_the_post_thumbnail_url($gallery_id, 'medium');
                            if ($thumbnail) : ?>
                                <div class="gtfa-gallery-thumbnail">
                                    <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                </div>
                            <?php endif;
                        endif; ?>
                        <h3 class="gtfa-gallery-title"><?php the_title(); ?></h3>
                        <?php if ($date) : ?>
                            <div class="gtfa-gallery-date">
                                <?php echo esc_html($formatted_date); ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($gallery_id) :?>
                            <div class="gtfa-gallery-shortcode">   
                                <?php echo do_shortcode('[envira-link id="' . $gallery_id . '"]' . get_the_title() . '[/envira-link]');?>
                            </div>
                        <?php endif;?>
                        <?php if ($shortcode) : ?>
                            <div class="gtfa-gallery-shortcode">
                                <?php echo do_shortcode($shortcode); ?>
                            </div>
                        <?php endif; ?>
                    </div>
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