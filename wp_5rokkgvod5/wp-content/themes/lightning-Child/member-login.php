<?php 
/*
Template Name: Member login Template
*/
get_header();
?>
<!-- wp:forminator/forms {"module_id":"404"} -->
<?php echo do_shortcode('[forminator_form id="404"]'); 
?>
<!-- /wp:forminator/forms -->
<?php
get_footer(); 
?>