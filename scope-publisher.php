<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://github.com/avramz
 * @since             1.0.0
 * @package           Scope_Publisher
 *
 * @wordpress-plugin
 * Plugin Name:       Scope Publisher
 * Description:       Scope publisher plugin. Required for curators who want to publish their articles from Scope.
 * Version:           1.1.12
 * Author:            Team Scope
 * Author URI:        https://thescope.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       scope-publisher
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Current plugin version.
 */
define('SCOPE_PUB_VERSION', '1.1.12');

/**
 * Current DB version
 */
define('SCOPE_DB_VERSION', '1.1.1');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-scope-publisher-activator.php
 */
function activate_scope_publisher()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-scope-publisher-activator.php';
    Scope_Publisher_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-scope-publisher-deactivator.php
 */
function deactivate_scope_publisher()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-scope-publisher-deactivator.php';
    Scope_Publisher_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_scope_publisher');
register_deactivation_hook(__FILE__, 'deactivate_scope_publisher');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-scope-publisher.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_scope_publisher()
{

    $plugin = new Scope_Publisher();
    $plugin->run();

}

run_scope_publisher();
