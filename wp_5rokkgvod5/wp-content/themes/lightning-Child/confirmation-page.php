<?php get_header();

global $wpdb;
$user_id = $_GET['user_id']; // assuming you passed the user_id as a parameter in the URL
$query = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."user_list WHERE user_id = %s", $user_id);
$result = $wpdb->get_row($query);
if ($result): ?>
    <h2>Registration Confirmation</h2>
    <p>Thank you for registering. Your registration details are as follows:</p>
    <ul>
        <li>First Name: <?php echo $result->first_name; ?></li>
        <li>Last Name: <?php echo $result->last_name; ?></li>
        <li>Email: <?php echo $result->email; ?></li>
        <li>Office Name: <?php echo $result->office_name; ?></li>
        <li>Department Name: <?php echo $result->department_name; ?></li>
        <li>Phone Number: <?php echo $result->phone_number; ?></li>
    </ul>
<?php else: ?>
    <p>Sorry, the registration details for this user could not be found.</p>
<?php endif; ?>

<?php get_footer(); ?>


