<?php
/**
 * Plugin Name: Logged In Access Control
 * Description: Restrict access to logged-in users, except for the home page.
 * Version: 1.0
 * Author: Rabab
 */

function logged_in_access_control() {
    // Check if the user is not logged in and not on the home page
    if (!is_user_logged_in() && !is_home() && !is_front_page()) {
        // Redirect to the home page or any other desired URL
        wp_redirect(home_url());
        exit;
    }
}

// Hook into the 'template_redirect' action
add_action('template_redirect', 'logged_in_access_control');