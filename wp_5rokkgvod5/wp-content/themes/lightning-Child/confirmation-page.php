<?php 
/*
Template Name: Confirmation Page Template
*/
get_header(); ?>

<div class="page-content">

<?php

if (!function_exists('wp_generate_password')) {
  require_once ABSPATH . 'wp-includes/pluggable.php';
}

global $wpdb;
$entry_id = $wpdb->get_var( $wpdb->prepare( 
    "SELECT  MAX(entry_id) FROM wp_frmt_form_entry WHERE form_id = %d", 913 
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
    }
    if ($object->meta_key === 'consent-1') {
      $consent = $object->meta_value;
    }
}
$allowed_domains = ['gmail.com', 'hotmail.com'];
if (in_array(substr(strrchr($email, "@"), 1), $allowed_domains)) {
  //echo("mail allowed");
  $query = $wpdb->prepare(
      "INSERT INTO  wp_user_list (user_id, first_name, last_name, email, office_name, department_name, phone_number, consent)
      VALUES ('$user_id','$first_name', '$last_name', '$email', '$office_name', '$department_name', '$phone_number', '$consent')");
  $wpdb->query($query);
   }else {
    $query = $wpdb->prepare(
      "INSERT INTO  wp_register_request (user_id, first_name, last_name, email, office_name, department_name, phone_number, consent)
      VALUES ('$user_id','$first_name', '$last_name', '$email', '$office_name', '$department_name', '$phone_number', '$consent')");
    $wpdb->query($query);
    
   }
if ($results): ?>
    <h2>登録確認</h2>
    <p>ご登録ありがとうございます。ご登録内容は以下の通りです。</p>
    <ul>
        <!-- <li>ユーザーID: <?php echo $user_id; ?></li> -->
        <li>名: <?php echo $first_name; ?></li>
        <li>姓: <?php echo $last_name; ?></li>
        <li>ユーザーID: <?php echo $user_id; ?></li>
        <li>メール: <?php echo $email; ?></li>
        <li>所属官庁／自治体名: <?php echo $office_name; ?></li>
        <li>所属部署名: <?php echo $department_name; ?></li>
        <li>電話番号: <?php echo $phone_number; ?></li>
    </ul>
    <p>上記内容をご確認の上、登録ボタンをクリックしてください。</p>
    
    <!-- <button type="button" class="btn btn-primary mb-3" onclick="showMessage()">登録</button> -->
    <a href="http://153.127.59.35/login/register/members/reg-com-msg/" class="button-link">
    登録
</a>

<style>
.page-content {
  margin: 50px;
}
</style>

<style>
.button-link {
  display: inline-block;
  padding: 7px 30px;
  background-color: #17A8E3;
  color: #FFFFFF;
  border-radius: 2.5px;
  text-decoration: none;
  margin-bottom: 100px;
}
.button-link:hover{
  background-color:#008FCA;
  color: #FFFFFF;
  text-decoration: none;
}
</style>
    <!-- <script>
function showMessage() {
  <p>登録申請ありがとうございます。まもなく、登録確認の詳細がメールで通知されます。</p>;
}
</script> -->
<?php else: ?>
    <p>Sorry, the registration details for this user could not be found.</p>
<?php endif; ?>

</div>

<?php 
get_footer(); ?>

