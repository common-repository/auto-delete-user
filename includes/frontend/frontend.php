<?php
// Prevent direct file access
if( ! defined( 'ABSPATH' ) ) {
	die();
}
class HSADUfrontend{
	private static $instance;
	
	public static function instance(){
		 if ( ! isset( self::$instance ) ) {
             self::$instance = new self;
		}
			return self::$instance;		
	}
	public function __construct(){
		
		add_action('init', array($this,'register_autodelete_fxn'));
	}
	public function register_autodelete_fxn(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'hs_autodeleteuser';
		$getcontent = $wpdb->get_results( "SELECT * FROM $table_name ");
		$i=0;
		foreach($getcontent as $values[$i]){
			$getuserrole = $values[$i]->userrole;
			$getuserdays = $values[$i]->days;
			$query = "SELECT $wpdb->users.ID FROM $wpdb->users LEFT JOIN $wpdb->usermeta ON $wpdb->users.ID = $wpdb->usermeta.user_id WHERE meta_key LIKE 'wp_capabilities' AND meta_value LIKE '%$getuserrole%' AND DATEDIFF(CURDATE(), $wpdb->users.user_registered) >= $getuserdays";
			print_r($query);
			$oldUsers = $wpdb->get_results($query,ARRAY_N);
				if ( $oldUsers ) {
					foreach ( $oldUsers as $user_id ) {
						require_once(ABSPATH.'wp-admin/includes/user.php');
							wp_delete_user( $user_id[0] );
					}
				}
				$i++;
				echo '<br>';
			}
}

	
}

return $HSADUfrontend = HSADUfrontend::instance();
