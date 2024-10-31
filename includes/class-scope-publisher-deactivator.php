<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/avramz
 * @since      1.0.0
 *
 * @package    Scope_Publisher
 * @subpackage Scope_Publisher/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Scope_Publisher
 * @subpackage Scope_Publisher/includes
 * @author     avramz <avramovic.u@gmail.com>
 */
class Scope_Publisher_Deactivator {

	/**
	 * Deactivate
	 *
	 * Remove scope_publisher table from the db.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'scope_publisher';
		$category_table_name = $wpdb->prefix . 'scope_publisher_categories';
		$sql1 = "DROP TABLE IF EXISTS $table_name; ";
		$sql2 = "DROP TABLE IF EXISTS $category_table_name; ";

		$wpdb->query($sql1);
		$wpdb->query($sql2);

		delete_option('scope_db_version');
	}

}
