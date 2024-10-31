<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/avramz
 * @since      1.0.0
 *
 * @package    Scope_Publisher
 * @subpackage Scope_Publisher/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Scope_Publisher
 * @subpackage Scope_Publisher/includes
 * @author     avramz <avramovic.u@gmail.com>
 */
class Scope_Publisher_Activator
{
    /**
     * Activate
     *
     * Create scope_publisher table in the db which will store the neccessary data to be in sync with Scope
     * @since    1.0.0
     */
    public static function activate()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'scope_publisher';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			code varchar(55) DEFAULT '' NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        Scope_Publisher_Activator::scope_pub_create_categories();
        add_option('scope_db_version', SCOPE_DB_VERSION);
    }

    /**
     * Create categories relation table which will store selected categories in which the posts can be published via Scope.
     * @since 1.1.0
     */
    public static function scope_pub_create_categories()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'scope_publisher_categories';
        $terms_table_name = $wpdb->prefix . 'terms';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			category_id bigint(20) UNSIGNED NOT NULL,
			PRIMARY KEY  (id),
			FOREIGN KEY (category_id) REFERENCES $terms_table_name(term_id) 
			ON DELETE CASCADE
            ON UPDATE CASCADE
		) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
