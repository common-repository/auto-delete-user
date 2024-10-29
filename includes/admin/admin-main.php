<?php
// Prevent direct file access
if( ! defined( 'ABSPATH' ) ) {
	die();
}
class HSADUadminMain{
	private static $instance;
	private $file = ''; 
	private $showmsg ='test';
	public static function instance(){
		 if ( ! isset( self::$instance ) ) {
             self::$instance = new self;
		}
			return self::$instance;		
	}
	
	public function __construct(){
		add_action('admin_menu', array( $this , 'create_admin_menu'));
		add_action('admin_enqueue_scripts',array($this,'CustomScripts'));
		add_action( 'wp_ajax_hsautodeleteuser', array($this,'hsautodeleteuser') );
		add_action( 'wp_ajax_nopriv_hsautodeleteuser',  array($this,'hsautodeleteuser') );
		add_action( 'wp_ajax_hsdeleteuser', array($this,'hsdeleteuser') );
		add_action( 'wp_ajax_nopriv_hsdeleteuser',  array($this,'hsdeleteuser') );
		
	}
	
	public function create_admin_menu(){
				add_submenu_page('users.php','Auto Delete User','Auto Delete User','manage_options','hsadu', array($this,'addoptionspage'));
	}

	public function CustomScripts(){
		wp_localize_script( 'autodeleteuser', 'autodeleteuser_ajax', array('ajax_url' => admin_url( 'admin-ajax.php' ) ));
		wp_enqueue_script( 'hsautodeleteuser_script',plugin_dir_url(__FILE__).'js/autodeleteuser.js');	
		wp_register_style( 'autodeletecss',plugin_dir_url(__FILE__).'css/styles.css');
		wp_enqueue_style( 'autodeletecss' );
	}
	public function hsdeleteuser(){
		global $wpdb;
		$getdeluser = stripslashes_deep($_REQUEST['getdeluser']);
		$table_namedel = $wpdb->prefix . 'hs_autodeleteuser';
		$custom_querydel = $wpdb->delete( $table_namedel, array( 'userrole' => $getdeluser ) );
		if($custom_querydel){
			 echo "User Deleted !!";
		}
		else{
			echo "User Not Existed !!";
		}
	die();
	}
	public function hsautodeleteuser(){
		global $wpdb;
		$getajaxdays = stripslashes_deep($_REQUEST['getdays']);
		$getajaxuserrole = stripslashes_deep($_REQUEST['getuserrole']);
		$table_name = $wpdb->prefix . 'hs_autodeleteuser';
		$custom_query = "SELECT * FROM ".$table_name." where userrole = '".$getajaxuserrole."'";
		$checkdata = $wpdb->get_results($custom_query);
		 if($checkdata != NULL){
			 echo "User Role already exists !!!";
		}
		else{
			$result = $wpdb->insert( 
			$table_name, 
				array( 
					'userrole' => $getajaxuserrole,
					'days' => $getajaxdays
				) 
			);
			echo "User Role Added ";
		} 
		 
		die();
	}

	public function addoptionspage(){

		?><div class="wrap">
			<h1 class="hsusertitle">Auto Delete User</h1>
			<p></p>
				<div class='hsinner_wrapers'>
					<div class='hs_getuser'>
						<span>Select Role:</span> 
						<select class='hsselectrole' id='hsselectrole' required>
						<option value='Select User Role' >Select User Role</option>
					<?php
							$geteditable = get_editable_roles();
							foreach ( $geteditable as $role_name => $role_info): ?>
							<?php if( $role_name=='administrator')
								continue;
							?>
							<option value=<?php echo $role_name ?> ><?php echo $role_info['name'] ; ?></option>
					  <?php endforeach; ?>
						</select>
					</div>
					<div class='hsselectdays'>
					<span>Days: </span>
					<input type='text' name="hsdays" id='hsdays' required>		
					</div>
					<div id='hssaveuser'>Save User</div>
					<div id='hsajaxauto_load' style='display:none'><img src="<?php echo  plugin_dir_url( __FILE__ ). 'images/ajax_loading.gif' ;?>"></div>
				</div>

		</div> <!-- wrap ends here-->
		<br><br><br>
		<?php
			global $wpdb;
			$table_name = $wpdb->prefix . 'hs_autodeleteuser';
			$i=0;
			$getcontent = $wpdb->get_results( "SELECT id,userrole,days FROM $table_name ");
			echo "<div class='hsshowuser'>";
			echo "<h1 class='hsusertitle'>User Role </h1>";
			echo "<div class='hstoptitles'><span class='hsuserstitle'>User Role</span>";
			echo "<span class='hsuserstitledays'>Days</span>";
			echo "<span class='hsuserstitledelete'>Action</span></div>";
			foreach($getcontent as $values[$i]){
				echo "<div class='hsshowuserinner'>";
				echo "<span class='hsusers'>";
				echo $values[$i]->userrole ;
				echo "</span>";
				echo "<span class='hsusersdays'>";
				echo $values[$i]->days ;
				echo "</span>";
				echo "<span id=".$values[$i]->id." class='hsdeleteuserbtn' data-user=".$values[$i]->userrole.">";
				echo "Delete";
				echo "</span>";
				echo "</div>";
				$i++;
			}
			echo "</div>";
		
		?>
		<div class='showuserroles'></div>
		<?php
		}
}
$HSADUadminMain = HSADUadminMain::instance();
