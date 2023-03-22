<?php 
/*
Template Name: Pending User View Template
*/
get_header();?>
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
   // echo '<td>';
   // echo '<button class="approve-registration" data-id="' . $registration->id . '"> Approve</button>';
   // echo '</td>';
   // echo '<td>';
   // echo '<button class="deny-registration" data-id="' . $registration->id . '"> Deny</button>';
   // echo '</td>';
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