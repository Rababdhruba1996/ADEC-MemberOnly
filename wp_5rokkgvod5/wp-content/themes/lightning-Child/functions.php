<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
  wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
  wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style')
);
}

add_action( 'forminator_custom_form_submit_after', 'custom_account_activation', 10, 4 );

  function create_user_list_table() {
    global $wpdb;
    $table_name_1 = $wpdb->prefix . 'user_list';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name_1 (
      id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(50) NOT NULL UNIQUE,
    user_password VARCHAR(50) NOT NULL UNIQUE,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    office_name VARCHAR(100) NOT NULL,
    department_name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    consent VARCHAR(3) NOT NULL
    ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
  }
  register_activation_hook( __FILE__, 'create_user_list_table' );
  
  // Define the custom database table for storing register request data
  function create_registration_request_table() {
    global $wpdb;
    $table_name_2 = $wpdb->prefix . 'register_request';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name_2 (
      id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    office_name VARCHAR(100) NOT NULL,
    department_name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    consent VARCHAR(3) NOT NULL
    ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
  }
  register_activation_hook( __FILE__, 'create_registration_request_table' );
   
   // Process new form submissions
   function custom_account_activation( $form_id, $entry_id, $form_type, $entry_data ) {
     global $wpdb;
     $table_name_1 = $wpdb->prefix . 'user_list';
     $table_name_2 = $wpdb->prefix . 'registration_request';
     global $wpdb;
    $entry_id = $wpdb->get_var( $wpdb->prepare( 
       "SELECT  MAX(entry_id) FROM ".$wpdb->prefix."frmt_form_entry WHERE form_id = %d", 913 
    ) );
     $results = $wpdb->get_results(
      $wpdb->prepare(
          "SELECT * FROM ".$wpdb->prefix."frmt_form_entry_meta
          WHERE entry_id = %d",
          $entry_id
      )
  );
  foreach ($results as $object) {
      if ($object->meta_key === 'email-1') {
          $email = $object->meta_value;
          //echo $email;
      }
      if ($object->meta_key === 'name-1') {
          $first_name = $object->meta_value;
      }
      if ($object->meta_key === 'name-2') {
          $last_name = $object->meta_value;
      }
      if ($object->meta_key === 'test-4') {
        $user_id = $object->meta_value;
    }
      if ($object->meta_key === 'text-2') {
        $office_name = $object->meta_value;
      }
      if ($object->meta_key === 'text-3') {
        $department_name = $object->meta_value;
      }
      if ($object->meta_key === 'phone-1') {
        $phone_number = $object->meta_value;
        //echo $phone_number;
      }
      if ($object->meta_key === 'consent-1') {
        $consent = $object->meta_value;
      }
  }

//Check the verified domain
$allowed_domains = ['gmail.com', 'hotmail.com'];
if (in_array(substr(strrchr($email, "@"), 1), $allowed_domains)) {
  $query = $wpdb->prepare(
      "INSERT INTO ".$wpdb->prefix."user_list (first_name, last_name, user_id, user email, office_name, department_name, phone_number, consent)
      VALUES (%s, %s, %s, %s, %s, %s, %s)",
      $first_name,
      $last_name,
      $user_id,
      $email,
      $office_name,
      $department_name,
      $phone_number,
      $consent
  );
  $wpdb->query($query);
  //$user_id = wp_generate_password(12, false);
  //$password = wp_generate_password();
  $pass_array = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT * FROM ".$wpdb->prefix."users
        WHERE user_nickname = %s",
        $user_id
    )
);
foreach ($pass_array as $object) {
      $password = $object->user_pass;
}
  send_confirmation_email($email, $user_id, $password);
   }else {
    $query = $wpdb->prepare(
      "INSERT INTO ".$wpdb->prefix."register_request (first_name, last_name, user_id, email, office_name, department_name, phone_number, consent)
      VALUES (%s, %s, %s, %s, %s, %s, %s)",
      $first_name,
      $last_name,
      $user_id,
      $email,
      $office_name,
      $department_name,
      $phone_number,
      $consent
    );
    $wpdb->query($query);
    send_notification_email($email);
   }
 }
//  //Send account activation email 
//  function account_activation_email($email, $user_id, $activation_url) {
  
//   $to = $email;
//   $subject = 'Activate your account';
//   $body = 'Click this link to activate your account: ' . $activation_url;
//   $headers = array('Content-Type: text/html; charset=UTF-8');
//   wp_mail( $to, $subject, $body, $headers );
//  }
 //Send confirmation email to the registered user
 function send_confirmation_email($email, $user_id, $password) {
  $to = $email;
  $subject = "確認メール";
  $login_link = "http://153.127.59.35/";
  $message = "ご登録いただきありがとうございます。 ADEC自治体会員限定のアカウントが有効になりました。以下のログイン情報をご確認ください。\n
  ログインページ: $login_link\n
  ユーザーID: $user_id\n
  パスワード: $password\n";
  $headers = array('Content-Type: text/plain');
  wp_mail($to, $subject, $message, $headers);
}
// Send registration request email to the admin page
function send_notification_email($email) {
  $to = $email;//'rababshayradhruba@gmail.com';//get_option('admin_email');
  $subject = "Pending Verification Email";
  $message = "A new registration with the following email address is waiting for manual verification: $email";
  $headers = array('Content-Type: text/plain');
  wp_mail($to, $subject, $message, $headers);
  }

function remove_home_breadcrumb( $breadcrumb ) {
    if( count($breadcrumb) > 1 ) {
        array_shift($breadcrumb);
    }
    return $breadcrumb;
}
add_filter( 'lightning_breadcrumb_lists', 'remove_home_breadcrumb' );

if ( ! current_user_can( 'edit_posts' ) ) {
  show_admin_bar( false );
}

?>