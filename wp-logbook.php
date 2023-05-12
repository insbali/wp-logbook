<?php

/**
 * Plugin Name:       WP Logbook
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Monitoring log files is a tedious work and your operation team also need to be aware of your application's health status.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            SolvrTech Indonesia
 * Author URI:        https://www.solvrtech.id/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * 
 * @package WPLogbook
 * 
 */

if (!defined("ABSPATH")) {
    exit;
}

define("WP_LOGBOOK_URL", plugin_dir_url(__FILE__));
define("WP_LOGBOOK_PATH", plugin_dir_path(__FILE__));
define("WP_LOGBOOK_ROOT", __FILE__);
define("WP_LOGBOOK_BASENAME", plugin_basename(__FILE__));

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

register_activation_hook(__FILE__, 'wp_logbook_plugin_activation');
function wp_logbook_plugin_activation()
{
    Solvrtech\WPlogbook\Activation::plugin_activate();
}

register_deactivation_hook(__FILE__, 'wp_logbook_plugin_deactivation');
function wp_logbook_plugin_deactivation()
{
    Solvrtech\WPlogbook\Deactivation::plugin_deactivate();
}

if (class_exists("Solvrtech\WPlogbook\Init")) {
    Solvrtech\WPlogbook\Init::register_services();
}
