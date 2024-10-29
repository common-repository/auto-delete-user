<?php
/**
 * Plugin Name: Auto Delete User
 * Plugin URI: https://wordpress.org/plugins/auto-delete-user/
 * Description: Auto Delete User plugin will delete the users automaticly after specific period of time. <strong>Please see the setting in Users -> Auto Delete User</strong>.
 * Version: 1.0
 * Author: Harpreet Singh
 * Author URI: https://github.com/harry005/
 * License: GNU General Public License v3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
*/
if( ! defined( 'ABSPATH' ) ) {
	die();
}

if (!class_exists( 'HSAutoDeleteUser' ) ) :
final class HSAutoDeleteUser{
	protected static $_instance = null; 
	  /**
			Getting the instance of class
     */
	 public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
	static function hs_autodeleteuser_install() {
		global $hs_autodeleteuser_db_version;
		$hs_autodeleteuser_db_version='1.0';
		global $wpdb;
		$table_name = $wpdb->prefix . 'hs_autodeleteuser';
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			userrole longtext NOT NULL,
			days longtext NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		add_option( 'hs_autodeleteuser_db_version', $hs_autodeleteuser_db_version ); 
	}

	
	
	public function __construct(){
			 if ( is_admin() ) {
					include_once( 'includes/admin/admin-main.php' );
			}
			else{
					include_once('includes/frontend/frontend.php');
			}
			register_activation_hook( __FILE__, array( $this , 'hs_autodeleteuser_install') );
	}
}
endif;
$HSAutoDeleteUser = HSAutoDeleteUser::instance();
