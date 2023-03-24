<?php
/**
 * Plugin Name:Custom account activation
 * Description: Customize the account activation method after member registration
 * Author: Rabab
 */

add_filter(
	'forminator_custom_form_user_registration_before_insert',
	function ( $new_user_data ) {
		add_action( 'user_register', 'wpmudev_forminator_filter_user_register_email', 10, 2 );

		return $new_user_data;
	}
);

function wpmudev_forminator_filter_user_register_email( int $user_id, array $userdata ) {
	remove_action( 'user_register', 'wpmudev_forminator_filter_user_register_email' );
	$user_email = $userdata['user_email'];
	$email_domain = 'gmail.com'; 

	if ( substr( $user_email, -strlen( $email_domain ) ) !== $email_domain ) {
		$new_user_data['ID'] = $user_id;
		$new_user_data['role'] = 'subscriber';

		// Update the user role to subscriber
		wp_update_user( $new_user_data );

		// Send a different email to the user
		// add_filter(
		// 	'wp_mail',
		// 	function ( array $mail_args ) use ( $userdata ) {
		// 		$mail_args['to'] = $userdata['user_email'];
		// 		$mail_args['subject'] = 'アカウントの確認';
		// 		$mail_args['message'] = '当サイトへのご登録ありがとうございます。お客様のアカウントは現在手動で審査中です。確認が完了次第、お知らせいたします。';
		// 		return $mail_args;
		// 	}
		// );

		return;
		remove_action( 'user_register', 'wpmudev_forminator_filter_user_register_email' );
	}	
	else{ 
		add_filter(
		'wp_mail',
		function ( array $mail_args ) use ( $user_id, $userdata ) {
			$needle       = 'Account Activated';
			$filter_email = substr( $mail_args['subject'], - strlen( $needle ) ) === $needle;

			if ( $filter_email ) {
				$userdata['user_id']  = $user_id;
				$mail_args['message'] = wpmudev_forminator_registration_email_template( $userdata );
				$mail_args['headers'] = array( 'Content-Type: text/html; charset=UTF-8' );

				remove_action( 'user_register', 'wpmudev_forminator_filter_user_register_email', 10 );
			}

			return $mail_args;
		}
	);
}
}

function wpmudev_forminator_registration_email_template( array $user_data = array() ) {
	if ( empty( $user_data ) ) {
		return __( 'Hey! You have registered succesfully!' );
	}	
	extract( $user_data );
	$user            = get_user_by( 'id', $user_id );
	$site_name       = get_bloginfo( 'name' );
	$home_url        = home_url();
	$login_page      = 'http://153.127.59.35/';
	$key             = get_password_reset_key( $user );
	$new_password    = wp_generate_password();
	wp_set_password( $new_password, $user_id );
	$pass_reset_link = network_site_url( "wp-login.php?action=rp&key={$key}&login=" . rawurlencode( $user_login ), 'login' );

	$tpl = "{$user_login} 様,

	 <p>{$site_name}のアカウントが有効になりました!以下のログイン情報をご確認ください。</p>
	
	 <p>ログインページ: {$login_page}</p>
	
	 <p>ユーザーID: {$user_login}</p>
	
	 <p>
	 パスワード: {$new_password} <br />
	
	 </p>
	
	 <p>このメッセージは {$site_name} から送信されました</p>";

		do_action( 'retrieve_password_key', $user_login, $key );

	return $tpl;
}

//  <a href=\"{$pass_reset_link}\">{$pass_reset_link}</a>

?>

