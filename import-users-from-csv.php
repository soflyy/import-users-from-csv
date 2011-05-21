<?php
/**
 * @package Import_Users_from_CSV
 * @version 0.2
 */
/*
Plugin Name: Import Users from CSV
Plugin URI: http://intside.com/
Description: Import Users data and metadata from csv file.
Author: Intside
Version: 0.2
Author URI: http://intside.com/
*/

if ( ! function_exists( 'str_getcsv' ) ) {
	function str_getcsv( $input, $delimiter=',', $enclosure='"', $escape=null, $eol=null ) {
		$temp = fopen( "php://memory", "rw" );
		fwrite( $temp, $input );
		fseek( $temp, 0 );
		$r = array();
		while ( ( $data = fgetcsv( $temp, 4096, $delimiter, $enclosure ) ) !== false ) {
			$r[] = $data;
		}
		fclose( $temp );
		if ( 1 === count( $r ) )
			$r = $r[0];
		return $r;
	}
}

/**
 * Main plugin class
 *
 * @since 0.1
 **/
class IS_IU_Import_Users {

	/**
	 * Class contructor
	 *
	 * @since 0.1
	 **/
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );
		add_action( 'admin_init', array( $this, 'process_csv' ) );
	}

	/**
	 * Add administration menus
	 *
	 * @since 0.1
	 **/
	public function add_admin_pages() {
		add_users_page( __( 'Import From CSV' ), __( 'Import From CSV' ), 'create_users', 'import-users-from-csv', array( $this, 'users_page' ) );
	}

	/**
	 * Process content of CSV file
	 *
	 * @since 0.1
	 **/
	public function process_csv() {
		if ( isset( $_POST['_wpnonce-is-iu-import-users-users-page_import'] ) ) {
			check_admin_referer( 'is-iu-import-users-users-page_import', '_wpnonce-is-iu-import-users-users-page_import' );

			if ( isset( $_FILES['users_csv']['tmp_name'] ) ) {
				ini_set( 'auto_detect_line_endings', true );

				$rows = file( $_FILES['users_csv']['tmp_name'] );
				if ( ! $rows )
					wp_redirect( add_query_arg( 'import', 'data', wp_get_referer() ) );

				$password_nag 		= isset( $_GET['password_nag'] ) ? $_GET['password_nag'] : false;
				$notification_email = isset( $_GET['notification_email'] ) ? $_GET['notification_email'] : false;
				$errors = $user_ids = array();
				$headers            = str_getcsv( $rows[0] );
				$rows               = array_slice( $rows, 1 );
				$userdata_fields    = array(
					'ID', 'user_login', 'user_pass',
					'user_email', 'user_url', 'user_nicename',
					'display_name', 'user_registered', 'first_name',
					'last_name', 'nickname', 'description',
					'rich_editing', 'comment_shortcuts', 'admin_color',
					'use_ssl', 'show_admin_bar_front', 'show_admin_bar_admin',
					'role'
				);

				do_action( 'is_iu_pre_users_import', $headers, $rows );

				foreach ( $rows as $rkey => $row ) {
					$columns = str_getcsv( $row );
					$userdata = $usermeta = array();

					foreach ( $columns as $ckey => $column ) {
						$column_name = $headers[$ckey];
						$column = trim( $column );

						if ( in_array( $column_name, $userdata_fields ) ) {
							$userdata[$column_name] = $column;
						} else {
							$usermeta[$column_name] = $column;
						}
					}

					$userdata = apply_filters( 'is_iu_import_userdata', $userdata, $usermeta );
					$usermeta = apply_filters( 'is_iu_import_usermeta', $usermeta, $userdata );

					if ( empty( $userdata['user_pass'] ) )
						$userdata['user_pass'] = wp_generate_password( 12, false );

					if ( empty( $userdata['ID'] ) )
						$user_id = wp_insert_user( $userdata );
					else
						$user_id = wp_update_user( $userdata );

					if ( is_wp_error( $user_id ) ) {
						$errors[$rkey] = $user_id;
					} else {
						foreach ( $usermeta as $metakey => $metavalue ) {
							update_user_meta( $user_id, $metakey, $metavalue );
						}

						if ( empty( $userdata['ID'] ) ) {
							if ( $password_nag )
								update_user_option( $user_id, 'default_password_nag', true, true );

							if ( $notification_email )
								wp_new_user_notification( $user_id, $user_pass );
						}

						$user_ids[] = $user_id;
					}
				}

				do_action( 'is_iu_post_users_import', $user_ids, $errors );

				$this->log_errors( $errors );

				if ( $errors && ! $user_ids ) {
					wp_redirect( add_query_arg( 'import', 'fail', wp_get_referer() ) );
				} elseif ( $errors ) {
					wp_redirect( add_query_arg( 'import', 'errors', wp_get_referer() ) );
				} else {
					wp_redirect( add_query_arg( 'import', 'success', wp_get_referer() ) );
				}
			} else {
				wp_redirect( add_query_arg( 'import', 'file', wp_get_referer() ) );
			}
		}
	}

	/**
	 * Content of the settings page
	 *
	 * @since 0.1
	 **/
	public function users_page() {
		if ( ! current_user_can( 'manage_options' ) )
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
?>

<div class="wrap">
	<h2><?php _e( 'Import users from a CSV file' ); ?></h2>
	<?php
	$error_log_file = plugin_dir_url( __FILE__ ) . 'errors.log';

	if ( ! file_exists( $error_log_file ) )
		@fopen( $error_log_file, 'x' );

	if ( isset( $_GET['import'] ) ) {
		if ( file_exists( $error_log_file ) )
			$error_log_msg = ", please <a href='$error_log'>check error log</a>";

		switch ( $_GET['import'] ) {
			case 'file':
				echo '<div class="error"><p><strong>Error during file upload.</strong></p></div>';
				break;
			case 'data':
				echo '<div class="error"><p><strong>Cannot extract data from uploaded file or no file was uploaded.</strong></p></div>';
				break;
			case 'fail':
				echo "<div class='error'><p><strong>No user was successfully imported$error_log_msg.</strong></p></div>";
				break;
			case 'errors':
				echo "<div class='error'><p><strong>Some users were successfully imported but some were not$error_log_msg.</strong></p></div>";
				break;
			case 'success':
				echo '<div class="updated"><p><strong>Users import was successful.</strong></p></div>';
				break;
			default:
				break;
		}
	}
	?>
	<form method="post" action="" enctype="multipart/form-data">
		<?php wp_nonce_field( 'is-iu-import-users-users-page_import', '_wpnonce-is-iu-import-users-users-page_import' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for"users_csv"><?php _e( 'CSV file' ); ?></label></th>
				<td><input type="file" id="users_csv" name="users_csv" value="" class="all-options" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Notification email' ); ?></th>
				<td><fieldset>
					<legend class="screen-reader-text"><span><?php _e( 'Notification email' ); ?></span></legend>
					<label for="notification_email">
						<input id="notification_email" name="notification_email" type="checkbox" value="1" />
						Send to new users
					</label>
				</fieldset></td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e( 'Password nag' ); ?></th>
				<td><fieldset>
					<legend class="screen-reader-text"><span><?php _e( 'Password nag' ); ?></span></legend>
					<label for="password_nag">
						<input id="password_nag" name="password_nag" type="checkbox" value="1" />
						Show password nag on new users signon
					</label>
				</fieldset></td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e( 'Upload' ); ?>" />
		</p>
	</form>
<?php
		if ( ! file_exists( $error_log_file ) )
			echo '<p>Notice: please make the plugin directory writable so that you can see the error log.</p>';
	}

	/**
	 * Log errors to a file
	 *
	 * @since 0.2
	 **/
	public function log_errors( $errors ) {
		if ( empty( $errors ) )
			return;

		$log = @fopen( plugin_dir_path( __FILE__ ) . 'errors.log', 'a' );
		@fwrite( $log, 'BEGIN ' . date( 'YmdHis', time() ) . "\n" );

		foreach ( $errors as $key => $error ) {
			$line = $key + 1;
			@fwrite( $log, "[Line $line] $error\n" );
		}

		@fclose( $log );
	}
}

new IS_IU_Import_Users;