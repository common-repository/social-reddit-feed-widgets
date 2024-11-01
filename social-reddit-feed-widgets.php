<?php
/*
 Plugin Name:Social Reddit Feed Widgets
 Plugin URI: https://eventscalendartemplates.com/
 Description:Social Reddit Feed Widgets
 Version:1.0
 License:GPL2
 Author:Cool Plugins
 Author URI:https://coolplugins.net/
 License URI:https://www.gnu.org/licenses/gpl-2.0.html
 Domain Path:/languages
 Text Domain:srfw
*/

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
if (!defined('SRFW_VERSION_CURRENT')) {
    define('SRFW_VERSION_CURRENT', '1.0');
}


/*** Defined constent for later use */
define('SRFW_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SRFW_PLUGIN_DIR', plugin_dir_path(__FILE__));
if (!class_exists('SocialRedditFeedWidgets')) {
    class SocialRedditFeedWidgets
    {
        public function __construct()
        {
            register_activation_hook(__FILE__, array($this, 'srfw_activate'));
            add_action('widgets_init', array($this, 'srfw_register_widget'));
            add_action('wp_enqueue_scripts', array($this, 'srfw_register_assets'));
        }
        function srfw_register_widget()
        {
            require_once(SRFW_PLUGIN_DIR . '/reddit-feed-widget/widget.php');
            register_widget('reddit_feed_widget');
        }
      
        public function srfw_register_assets()
        {
            wp_register_style('srfw-feed-styles', SRFW_PLUGIN_URL . 'assets/css/srfw-style.css', null, null, 'all');
            wp_register_style('srfw-custom-icon', SRFW_PLUGIN_URL . 'assets/css/srfw-custom-icon.css', null, null, 'all');
        }
        public function srfw_activate()
        {
            update_option('SRFW_Version', SRFW_VERSION_CURRENT);
        }
    }
}
new SocialRedditFeedWidgets();
