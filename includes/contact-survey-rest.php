<?php
function register_validation_rest_route() {
    register_rest_route('contact-survey/v1', '/validate-contact', array(
        'methods' => 'POST',
        'callback' => 'handle_contact_validation',
    ));
    register_rest_route('contact-survey/v1', '/validate-contact-sms', array(
        'methods' => 'POST',
        'callback' => 'handle_contact_validation_sms',
    ));
    register_rest_route('contact-survey/v1', '/resend-code-sms', array(
        'methods' => 'POST',
        'callback' => 'handle_resend_code_sms',
    ));
    register_rest_route('contact-survey/v1', '/validate-preferences', array(
        'methods' => 'POST',
        'callback' => 'handle_validate_preferences',
    ));
}

add_action('rest_api_init', 'register_validation_rest_route');