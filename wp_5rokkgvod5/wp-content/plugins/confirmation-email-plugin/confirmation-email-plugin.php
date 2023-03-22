<?php
/**
 * Plugin Name: Sort User Plugin
 * Plugin URI:  https://example.com
 * Description: A custom plugin to sort user information in database according to their email address.
 * Version:     1.0.0
 * Author:      Rabab
 * Author URI:  https://example.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: sort-user-plugin
 */

 if (!function_exists('wp_generate_password')) {
  require_once ABSPATH . 'wp-includes/pluggable.php';
}

//  // Define the custom database table for storing user data
//  function create_user_list_table() {
//   global $wpdb;
//   $table_name_1 = $wpdb->prefix . 'user_list';
//   $charset_collate = $wpdb->get_charset_collate();
//   $sql = "CREATE TABLE $table_name_1 (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//   user_id VARCHAR(50) NOT NULL UNIQUE,
//   user_password VARCHAR(50) NOT NULL UNIQUE,
//   first_name VARCHAR(50) NOT NULL,
//   last_name VARCHAR(50) NOT NULL,
//   email VARCHAR(100) NOT NULL UNIQUE,
//   office_name VARCHAR(100) NOT NULL,
//   department_name VARCHAR(100) NOT NULL,
//   phone_number VARCHAR(20) NOT NULL,
//   consent VARCHAR(3) NOT NULL
//   ) $charset_collate;";
//   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
//   dbDelta( $sql );
// }
// register_activation_hook( __FILE__, 'create_user_list_table' );

// // Define the custom database table for storing register request data
// function create_registration_request_table() {
//   global $wpdb;
//   $table_name_2 = $wpdb->prefix . 'register_request';
//   $charset_collate = $wpdb->get_charset_collate();
//   $sql = "CREATE TABLE $table_name_2 (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//   user_id VARCHAR(50) NOT NULL UNIQUE,
//   first_name VARCHAR(50) NOT NULL,
//   last_name VARCHAR(50) NOT NULL,
//   email VARCHAR(100) NOT NULL UNIQUE,
//   office_name VARCHAR(100) NOT NULL,
//   department_name VARCHAR(100) NOT NULL,
//   phone_number VARCHAR(20) NOT NULL,
//   consent VARCHAR(3) NOT NULL
//   ) $charset_collate;";
//   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
//   dbDelta( $sql );
// }
// register_activation_hook( __FILE__, 'create_registration_request_table' );
 
 // Process new form submissions

function process_new_registration() {
   global $wpdb;
   $table_name_1 = $wpdb->prefix . 'user_list';
   $table_name_2 = $wpdb->prefix . 'register_request';
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
        echo $email;
    }
    if ($object->meta_key === 'name-1') {
        $first_name = $object->meta_value;
    }
    if ($object->meta_key === 'name-2') {
        $last_name = $object->meta_value;
    }
    if ($object->meta_key === 'text-4') {
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
      echo $phone_number;
    }
    if ($object->meta_key === 'consent-1') {
      $consent = $object->meta_value;
    }
}
   $allowed_domains = ['gmail.com', 'hotmail.com'];
  //  $email_list=$wpdb->get_results("SELECT meta_value
  //    FROM ".$wpdb->prefix."frmt_form_entry_meta
  //    WHERE meta_key='email-1' ");
  //  foreach ($email_list as $object) {
  //    $email = $object->meta_value;
  //    $domain = explode('@', $email)[1];
if (in_array(substr(strrchr($email, "@"), 1), $allowed_domains)) {
    echo("mail allowed");
    $query = $wpdb->prepare(
        "INSERT INTO  $table_name_2 (user_id, first_name, last_name, email, office_name, department_name, phone_number, consent)
        VALUES ('$user_id','$first_name', '$last_name', '$email', '$office_name', '$department_name', '$phone_number', '$consent')");
    $wpdb->query($query);
    //$user_id = wp_generate_password(12, false);
    //$password = wp_generate_password();
    //send_confirmation_email($email, $user_id, $password);
     }else {
      //echo("mail not allowed");
      // $test = $wpdb->get_results("SELECT * FROM $table_name_2");
      // foreach ($test as $registration){echo $registration->first_name; }
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
      //send_notification_email($email);
     }
}
add_shortcode('sort_user', 'process_new_registration');  
?>