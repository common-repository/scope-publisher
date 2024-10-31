<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions
 * @link       https://github.com/avramz
 * @since      1.0.0
 *
 * @package    Scope_Publisher
 * @subpackage Scope_Publisher/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, hooks
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Scope_Publisher
 * @subpackage Scope_Publisher/includes
 * @author     avramz <avramovic.u@gmail.com>
 */
class Scope_Publisher
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Scope_Publisher_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('SCOPE_PUB_VERSION')) {
            $this->version = SCOPE_PUB_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'scope-publisher';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Scope_Publisher_Loader. Orchestrates the hooks of the plugin.
     * - Scope_Publisher_i18n. Defines internationalization functionality.
     * - Scope_Publisher_Admin. Defines all hooks for the admin area.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-scope-publisher-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-scope-publisher-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-scope-publisher-admin.php';

        $this->loader = new Scope_Publisher_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Scope_Publisher_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Scope_Publisher_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the functionality of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_hooks()
    {

        $plugin_admin = new Scope_Publisher_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_styles', $plugin_admin, 'scope_pub_enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'scope_pub_enqueue_scripts');
        $this->loader->add_action('rest_api_init', $plugin_admin, 'scope_pub_enqueue_rest');
        $this->loader->add_action('admin_menu', $plugin_admin, 'scope_pub_create_menu');

        $this->loader->add_action('wp_ajax_register_token', $plugin_admin, 'scope_pub_register_token');
        $this->loader->add_action('wp_ajax_deactivate_box', $plugin_admin, 'scope_pub_deactivate_box');
        $this->loader->add_action('wp_ajax_get_activation_status', $plugin_admin, 'scope_pub_get_activation_status');

        $this->loader->add_action('wp_ajax_select_category', $plugin_admin, 'scope_pub_select_category');
        $this->loader->add_action('wp_ajax_remove_category', $plugin_admin, 'scope_pub_remove_category');

        $this->loader->add_action( 'plugins_loaded', $plugin_admin, 'scope_pub_check_db_update' );
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Scope_Publisher_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
}
