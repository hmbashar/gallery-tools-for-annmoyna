<?php
/**
 * Plugin Name: Gallery Tools for Annmoyna
 * Plugin URI: https://github.com/hmbashar/gallery-tools-for-event
 * Description: A gallery tools plugin for managing Envira gallery with additional metabox features.
 * Version: 1.0.0
 * Author: Md Abul Bashar
 * Author URI: https://hmbashar.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: gallery-tools-for-annmoyna
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main plugin class
 */
class Gallery_Tools_For_Annmoyna {
    /**
     * Plugin instance.
     *
     * @var Gallery_Tools_For_Annmoyna
     */
    private static $instance = null;

    /**
     * Get plugin instance.
     *
     * @return Gallery_Tools_For_Annmoyna
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
        $this->init();
    }

    /**
     * Define plugin constants.
     */
    private function define_constants() {
        define('GTFA_VERSION', '1.0.0');
        define('GTFA_PLUGIN_DIR', plugin_dir_path(__FILE__));
        define('GTFA_PLUGIN_URL', plugin_dir_url(__FILE__));
    }

    /**
     * Include required files.
     */
    private function includes() {
        // Load Composer's autoloader
        require_once GTFA_PLUGIN_DIR . 'vendor/autoload.php';
    }

    /**
     * Initialize plugin components.
     */
    private function init() {
        // Initialize PostType, Metabox and Shortcode
        new \GTFA\PostType();
        new \GTFA\Metabox();
        new \GTFA\Shortcode();
    }

    /**
     * Initialize hooks.
     */
    private function init_hooks() {
        add_action('plugins_loaded', array($this, 'load_textdomain'));
    }

    /**
     * Load plugin text domain.
     */
    public function load_textdomain() {
        load_plugin_textdomain('gallery-tools-for-annmoyna', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
}

// Initialize the plugin
function gallery_tools_for_annmoyna() {
    return Gallery_Tools_For_Annmoyna::get_instance();
}

// Start the plugin
gallery_tools_for_annmoyna();