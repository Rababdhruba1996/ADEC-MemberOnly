<?php 
/*
Template Name: Sort User Template
*/
get_header(); ?>

<!-- wp:forminator/forms {"module_id":"913"} -->
<?php echo do_shortcode('[forminator_form id="913"]'); 
?>
<!-- /wp:forminator/forms -->

<?php
if (!function_exists('wp_generate_password')) {
    require_once ABSPATH . 'wp-includes/pluggable.php';
  }

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
    }
    if ($object->meta_key === 'consent-1') {
      $consent = $object->meta_value;
    }
}
   $allowed_domains = ['gmail.com', 'hotmail.com'];
  
if (in_array(substr(strrchr($email, "@"), 1), $allowed_domains)) { 
    $query = $wpdb->prepare(
        "INSERT INTO ".$wpdb->prefix."user_list (first_name, last_name, user_id, email, office_name, department_name, phone_number, consent)
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
     }
?>
<?php get_footer(); ?>