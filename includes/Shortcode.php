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
                <style>
                    .gtfa-gallery-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                        gap: 20px;
                        padding: 20px 0;
                    }
                    .gtfa-gallery-item {
                        background: #fff;
                        border: 1px solid #ddd;
                        padding: 15px;
                        border-radius: 5px;
                    }
                    .gtfa-gallery-title {
                        font-size: 1.2em;
                        margin-bottom: 10px;
                        color: #333;
                    }
                    .gtfa-gallery-date {
                        color: #666;
                        font-size: 0.9em;
                        margin-bottom: 10px;
                    }
                    .gtfa-gallery-shortcode {
                        margin-top: 15px;
                    }
                </style>
                <?php
                while ($query->have_posts()) : $query->the_post();
                    $title = get_post_meta(get_the_ID(), '_gtfa_gallery_title', true);
                    $date = get_post_meta(get_the_ID(), '_gtfa_gallery_date', true);
                    $shortcode = get_post_meta(get_the_ID(), '_gtfa_gallery_shortcode', true);
                    ?>
                    <div class="gtfa-gallery-item">
                        <h3 class="gtfa-gallery-title"><?php echo esc_html($title); ?></h3>
                        <?php if ($date) : ?>
                            <div class="gtfa-gallery-date">
                                <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($date))); ?>
                            </div>
                        <?php endif; ?>
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