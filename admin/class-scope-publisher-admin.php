<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/avramz
 * @since      1.0.0
 *
 * @package    Scope_Publisher
 * @subpackage Scope_Publisher/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Scope_Publisher
 * @subpackage Scope_Publisher/admin
 * @author     avramz <avramovic.u@gmail.com>
 */
class Scope_Publisher_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Categories list
     */
    protected $categories_list;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function scope_pub_enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/scope-publisher-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function scope_pub_enqueue_scripts()
    {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/scope-publisher-admin.js', array('jquery'), $this->version, false);

    }

    public function scope_pub_enqueue_rest()
    {
        register_rest_route('scope/v1', '/publish', array(
            'methods' => 'POST',
            'callback' => array($this, 'scope_pub_handle_publish'),
            'permission_callback' => '__return_true',
        ));

        register_rest_route('scope/v1', '/status', array(
            'methods' => 'GET',
            'callback' => array($this, 'scope_pub_handle_status'),
            'permission_callback' => '__return_true',
        ));
    }

    public function scope_pub_handle_publish($request)
    {
        $auth = $request->get_header('scope-wp-auth');
        $response = new WP_REST_Response();
        if ($this->scope_pub_check_permission($auth)) {
            $parameters = json_decode($request->get_body(), true);
            $selected_categories = Scope_Publisher_Admin::scope_pub_get_selected_categories($parameters['categorySlugs']);

            $my_post = array(
                'post_title' => $parameters['title'],
                'post_content' => $parameters['content'],
                'post_status' => $parameters['status'],
                'post_category' => $selected_categories
            );

            if ($parameters['curated_urls']) {
                $my_post['meta_input'] = array(
                    'curated_urls' => $parameters['curated_urls']
                );
            }

            if ($parameters['tagSlugs']) {
                $my_post['tags_input'] = $parameters['tagSlugs'];
            }

            $post_id = wp_insert_post($my_post);

            if ($parameters['sticky']) {
                stick_post($post_id);
            }

            if ($parameters['featuredImageUrl']) {
                $res = $this->scope_pub_generate_featured_image($parameters['featuredImageUrl'], $post_id);
                $response->set_data($res);
            }

            $response->set_status(200);
        } else {
            $response->set_status(400);
        }

        return $response;
    }

    public function scope_pub_handle_status()
    {
        $response = new WP_REST_Response();
        $response->set_status(200);

        return $response;
    }

    /**
     * Generate a list of category id's to attach to post
     * @param    array $category_slugs Optional category slug array.
     * @return   array $selected_category_ids
     * @since    1.1.5
     */
    public function scope_pub_get_selected_categories($category_slugs = [])
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'scope_publisher_categories';
        $selected_categories = $wpdb->get_results("SELECT (category_id) FROM $table_name");
        $selected_category_ids = array();

        foreach ($selected_categories as $category) {
            array_push($selected_category_ids, $category->category_id);
        }

        if (!empty($category_slugs)) {
            for($x = 0; $x < count($category_slugs); $x++) {
                $category_from_slug = get_category_by_slug($category_slugs[$x]);

                if ($category_from_slug) {
                    array_push($selected_category_ids, $category_from_slug->term_id);
                }
            }
        }

        return $selected_category_ids;
    }

    public function scope_pub_select_category()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'scope_publisher_categories';
        $category_id = $_POST['category_id'];
        $wpdb->insert(
            $table_name,
            array(
                'category_id' => $category_id,
            )
        );

        echo 'success';

        wp_die();
    }

    public function scope_pub_remove_category()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'scope_publisher_categories';
        $category_id = $_POST['category_id'];
        $wpdb->delete(
            $table_name,
            array(
                'category_id' => $category_id,
            )
        );

        echo 'success';

        wp_die();
    }

    public function scope_pub_create_menu()
    {
        add_menu_page('Scope Publish Config', 'Scope Publisher', 'manage_options', 'scope-publish-menu', array($this, 'scope_pub_open_scope_admin'), 'https://ce850c98c.cloudimg.io/crop/20x20/tpng/https://storage.googleapis.com/static-imgs/wordpress-plugin/scope-publisher.png');
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/scope-publisher-admin.js', array('jquery'), $this->version, false);
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/scope-publisher-admin.css');
    }

    public function scope_pub_open_scope_admin()
    {
        include(plugin_dir_path(__FILE__) . 'partials/scope-publisher-admin-display.php');
    }

    public function scope_pub_register_token()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'scope_publisher';
        $token = $_POST['token'];
        $sql = "TRUNCATE TABLE $table_name";
        $wpdb->query($sql);

        $wpdb->insert(
            $table_name,
            array(
                'code' => sha1($token),
            )
        );

        echo 'success';

        wp_die();
    }

    public function scope_pub_get_activation_status()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'scope_publisher';
        $wpdb->get_results("SELECT * FROM $table_name LIMIT 1");

        echo $wpdb->num_rows > 0 ? 1 : 0;

        wp_die();
    }

    public function scope_pub_check_db_update()
    {
        $installed_db_ver = get_option('scope_db_version');
        $current_db_ver = '1.0.0';

        if (defined('SCOPE_DB_VERSION')) {
            $current_db_ver = SCOPE_DB_VERSION;
        }


        if ($installed_db_ver != $current_db_ver) {
            require_once plugin_dir_path(__FILE__) . '../includes/class-scope-publisher-activator.php';
            Scope_Publisher_Activator::scope_pub_create_categories();
            update_option('scope_db_version', $current_db_ver);
        }
    }

    public function scope_pub_deactivate_box()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'scope_publisher';
        $sql = "TRUNCATE TABLE $table_name";
        $wpdb->query($sql);

        echo 'success';

        wp_die();
    }

    private function scope_pub_check_permission($token)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'scope_publisher';
        $plugin_data = $wpdb->get_row("SELECT * FROM $table_name LIMIT 1");
        return $plugin_data->code == sha1($token) && ($_SERVER['REMOTE_ADDR'] == '34.90.208.200' || $_SERVER['REMOTE_ADDR'] == '34.91.179.232' || $_SERVER['REMOTE_ADDR'] == '34.91.95.122');
    }

    /**
     * Generate featured image for the post
     * @param    string $image_url Image to be uploaded (absolute path).
     * @param    number $post_id ID of the post.
     * @return   string result
     * @since    1.1.4
     */
    private function scope_pub_generate_featured_image($image_url, $post_id)
    {
        require_once(ABSPATH . 'wp-admin' . '/includes/image.php');
        require_once(ABSPATH . 'wp-admin' . '/includes/file.php');
        require_once(ABSPATH . 'wp-admin' . '/includes/media.php');

        $file_array = array();
        $file_array['name'] = basename($image_url);
        $file_array['tmp_name'] = download_url($image_url);

        if (is_wp_error($file_array['tmp_name'])) {
            return 'Error storing temp image';
        }

        $id = media_handle_sideload($file_array, $post_id);

        if (is_wp_error($id)) {
            @unlink($file_array['tmp_name']);
            return 'Error storing image';
        }

        set_post_thumbnail($post_id, $id);
    }
}
