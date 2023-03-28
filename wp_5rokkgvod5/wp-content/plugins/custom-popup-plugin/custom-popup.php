<?php
/**
 * Plugin Name: Custom Popup Plugin
 * Description: A popup plugin to display privacy policy details on a consent field click of a Forminator registration form
 * Version: 1.0
 * Author: Rabab
 */

 function privacy_policy_popup_scripts() {
    // Enqueue required CSS file
    wp_enqueue_style( 'privacy-policy-popup', plugins_url( 'css/privacy-policy-popup.css', __FILE__ ), array(), '1.0.0', 'all' );
 
    // Enqueue required JavaScript file
    wp_enqueue_script( 'privacy-policy-popup', plugins_url( 'js/privacy-policy-popup.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'privacy_policy_popup_scripts' );

function privacy_policy_popup_shortcode() {
    // Define the popup content
    $popup_content = '<div class="privacy-policy-popup-content">Your privacy policy details go here.</div>';
    return $popup_content;
}
add_shortcode( 'privacy_policy_popup', 'privacy_policy_popup_shortcode' );

function privacy_policy_popup_footer() {
    // Add the popup HTML to the footer
    echo '<div id="privacy-policy-popup" class="privacy-policy-popup">' . do_shortcode( '[privacy_policy_popup]' ) . '</div>';
}
add_action( 'wp_footer', 'privacy_policy_popup_footer' );

