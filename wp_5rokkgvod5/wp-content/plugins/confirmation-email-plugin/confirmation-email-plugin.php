<?php
/**
 * Plugin Name: Custom Registration Form
 * Plugin URI:  https://example.com
 * Description: A custom plugin to handle registration form submissions and perform actions based on the email domain.
 * Version:     1.0.0
 * Author:      Rabab
 * Author URI:  https://example.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: custom-registration-plugin
 */

 if (!function_exists('wp_generate_password')) {
  require_once ABSPATH . 'wp-includes/pluggable.php';
}

 // Define the custom database table for storing user data
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

function process_new_registration($entry_id) {
   global $wpdb;
   $table_name_1 = $wpdb->prefix . 'user_list';
   $table_name_2 = $wpdb->prefix . 'registration_request';
   global $wpdb;
  $entry_id = $wpdb->get_var( $wpdb->prepare( 
     "SELECT MAX(entry_id) FROM wp_frmt_form_entry WHERE form_id = %d", 5 
  ) );
   $results = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT * FROM wp_frmt_form_entry_meta
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
    if ($object->meta_key === 'text-1') {
      $office_name = $object->meta_value;
    }
    if ($object->meta_key === 'text-2') {
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
    // $first_name = $form_data['first_name'];
    // $last_name = $form_data['last_name'];
    // $email = $form_data['email'];
    // $office_name = $form_data['office_name'];
    // $department_name = $form_data['department_name'];
    // $phone_number = $form_data['phone_number'];
    // $consent = $form_data['consent'];
    $query = $wpdb->prepare(
        "INSERT INTO ".$wpdb->prefix."user_list (first_name, last_name, email, office_name, department_name, phone_number, consent)
        VALUES (%s, %s, %s, %s, %s, %s, %s)",
        $first_name,
        $last_name,
        $email,
        $office_name,
        $department_name,
        $phone_number,
        $consent
    );
    $wpdb->query($query);
    $user_id = wp_generate_password(12, false);
    $password = wp_generate_password();
    send_confirmation_email($email, $user_id, $password);
     }else {
      $query = $wpdb->prepare(
        "INSERT INTO ".$wpdb->prefix."register_request (first_name, last_name, email, office_name, department_name, phone_number, consent)
        VALUES (%s, %s, %s, %s, %s, %s, %s)",
        $first_name,
        $last_name,
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
add_shortcode('email_confirmaton', 'process_new_registration');  
 
 // Send confirmation email to the registered user
 function send_confirmation_email($email, $user_id, $password) {
   $to = $email;
   $subject = "Confirmation Email";
   $message = "Your User ID is: $user_id\nYour password is: $password\n";
   $headers = array('Content-Type: text/plain');
   wp_mail($to, $subject, $message, $headers);
 }
 
 // Send registration request email to the admin page
 function send_notification_email($email) {
   $to = 'rababshayradhruba@gmail.com';//get_option('admin_email');
   $subject = "Pending Verification Email";
   $message = "A new registration with the following email address is waiting for manual verification: $email";
   $headers = array('Content-Type: text/plain');
   wp_mail($to, $subject, $message, $headers);
   }
   
   // Show pending registration requests in a Wordpress post
   function display_pending_registrations_post() {
   global $wpdb;
   $table_name = $wpdb->prefix . 'register_request';
   $pending_registrations = $wpdb->get_results("SELECT * FROM $table_name");//WHERE status='pending'
   if (!empty($pending_registrations)) {
   echo '<h2>Pending Registrations</h2>';
   echo '<table>';
   echo '<tr>';
   echo '<th>ID</th>';
   echo '<th>Email</th>';
   echo '<th></th>';
   echo '<th></th>';
   echo '</tr>';
   foreach ($pending_registrations as $registration) {
   echo '<tr>';
   echo '<td>' . $registration->id . '</td>';
   echo '<td>' . $registration->email . '</td>';
   echo '<td>';
   echo '<button class="approve-registration" data-id="' . $registration->id . '"> Approve</button>';
   echo '</td>';
   echo '<td>';
   echo '<button class="deny-registration" data-id="' . $registration->id . '"> Deny</button>';
   echo '</td>';
   echo '</tr>';
   }
   echo '</table>';
   } else {
   echo '<p>No pending registrations at this time.</p>';
   }
   }
   
   // Add the shortcode for the pending registration requests
add_shortcode('pending_registrations', 'display_pending_registrations_post');