<?php 
/*
Template Name: Pending User View Template
*/
get_header();

// Load WordPress functions
require_once( ABSPATH . 'wp-includes/pluggable.php' );

$table_name = $wpdb->prefix . 'register_request';
if(isset($_POST['approve_id'])) {
  // Get the user's email address from the database
  $registration_id = intval($_POST['approve_id']);
  $registration = $wpdb->get_row("SELECT * FROM $table_name WHERE id = $registration_id");
  $to = $registration->email;

  // Send the approval email
  $user            = get_user_by( 'id', $user_id );
	$site_name       = get_bloginfo( 'name' );
  $login_page      = 'http://153.127.59.35/';
	$key             = get_password_reset_key( $user );
	$new_password    = wp_generate_password();
	wp_set_password( $new_password, $user_id );
  $subject = '登録承認';
  $message = "{$user_login} 様,

  <p>{$site_name}のアカウントが有効になりました!以下のログイン情報をご確認ください。</p>
 
  <p>ログインページ: {$login_page}</p>
 
  <p>ユーザーID: {$user_login}</p>
 
  <p>
  パスワード: {$new_password} <br />
 
  </p>
 
  <p>このメッセージは {$site_name} から送信されました</p>";
  do_action( 'retrieve_password_key', $user_login, $key );
  wp_mail($to, $subject, $message);
  
  // Remove the registration request from the database
  $wpdb->delete($table_name, array('id' => $registration_id));
}

if(isset($_POST['deny_id'])) {
  // Get the user's email address from the database
  $registration_id = intval($_POST['deny_id']);
  $registration = $wpdb->get_row("SELECT * FROM $table_name WHERE id = $registration_id");
  $to = $registration->email;

  // Send the denial email
  $subject = '登録拒否';
  $message = '申し訳ありませんが、登録は拒否されました。さらなる検証をお待ちください。';
  wp_mail($to, $subject, $message);
  
  // Remove the registration request from the database
  $wpdb->delete($table_name, array('id' => $registration_id));
}
?>

<div class="page-content">

<?php
global $wpdb;
   $table_name = $wpdb->prefix . 'register_request';
   $pending_registrations = $wpdb->get_results("SELECT * FROM $table_name");
   if (!empty($pending_registrations)) {
   echo '<h2>保留中のユーザー</h2>';
   echo '<table>';
   echo '<tr>';
   echo '<th>お名前</th>';
   echo '<th>ユーザーID</th>';
   echo '<th>メール</th>';
   echo '<th>所属官庁／自治体名</th>';
   echo '<th>所属部署名</th>';
   echo '<th>電話番号</th>';
   // echo '<th></th>';
   // echo '<th></th>';
   echo '</tr>';
   foreach ($pending_registrations as $registration) {
   echo '<tr>';
   echo '<td>' . $registration->first_name . $registration->last_name .'</td>';
   echo '<td>' . $registration->user_id . '</td>';
   echo '<td>' . $registration->email . '</td>';
   echo '<td>' . $registration->office_name . '</td>';
   echo '<td>' . $registration->department_name . '</td>';
   echo '<td>' . $registration->phone_number . '</td>';
   echo '<td>';
   echo '<form method="post">';
      echo '<input type="hidden" name="approve_id" value="' . $registration->id . '">';
      echo '<button type="submit" class="approve-registration"> Approve</button>';
      echo '</form>';
      echo '</td>';
      echo '<td>';
      echo '<form method="post">';
      echo '<input type="hidden" name="deny_id" value="' . $registration->id . '">';
      echo '<button type="submit" class="deny-registration"> Deny</button>';
      echo '</form>';
      echo '</td>';
   echo '</tr>';
   }
   echo '</table>';
   } else {
   echo '<p>No pending registrations at this time.</p>';
   }
   ?>
</div>


<style>
.page-content {
  margin: 50px;
  padding: 100px 100px;
}
</style>
<?php get_footer(); ?>