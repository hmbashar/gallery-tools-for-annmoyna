<?php
namespace GTFA;

/**
 * Class PostType
 * Handles registration and configuration of the Gallery Event custom post type
 *
 * @package Gallery_Tools_For_Annmoyna
 */
class PostType {
    /**
     * Post type name.
     *
     * @var string
     */
    const POST_TYPE = 'gallery_event';

    /**
     * Constructor.
     */
    public function __construct() {
        add_action('init', array($this, 'register_post_type'));
    }

    /**
     * Register the Gallery Event custom post type.
     */
    public function register_post_type() {
        $labels = array(
            'name'               => __('Gallery Events', 'gallery-tools-for-annmoyna'),
            'singular_name'      => __('Gallery Event', 'gallery-tools-for-annmoyna'),
            'menu_name'          => __('Gallery Events', 'gallery-tools-for-annmoyna'),
            'add_new'            => __('Add New', 'gallery-tools-for-annmoyna'),
            'add_new_item'       => __('Add New Gallery Event', 'gallery-tools-for-annmoyna'),
            'edit_item'          => __('Edit Gallery Event', 'gallery-tools-for-annmoyna'),
            'new_item'           => __('New Gallery Event', 'gallery-tools-for-annmoyna'),
            'view_item'          => __('View Gallery Event', 'gallery-tools-for-annmoyna'),
            'search_items'       => __('Search Gallery Events', 'gallery-tools-for-annmoyna'),
            'not_found'          => __('No gallery events found', 'gallery-tools-for-annmoyna'),
            'not_found_in_trash' => __('No gallery events found in trash', 'gallery-tools-for-annmoyna'),
        );

        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array('slug' => 'gallery-event'),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-format-gallery',
            'supports'            => array('title', 'editor', 'thumbnail'),
        );

        register_post_type(self::POST_TYPE, $args);
    }
}