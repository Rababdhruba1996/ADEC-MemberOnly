jQuery(document).ready(function($) {
    // Show/hide the popup when the "privacy policy" link is clicked
    $('.forminator-checkbox-consent-label a:contains("privacy policy")').click(function(e) {
        e.preventDefault();
        $('#privacy-policy-popup').fadeIn();
    });
    $('#privacy-policy-popup').click(function() {
        $(this).fadeOut();
    });
});
