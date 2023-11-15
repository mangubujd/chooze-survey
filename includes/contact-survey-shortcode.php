<?php
// Fonction pour générer le contenu du shortcode
function contact_survey_shortcode($atts) {
    // Récupérez l'ID encodé en base64 à partir de l'URL
    if (isset($_GET['id'])) {
        $encoded_id = sanitize_text_field($_GET['id']);
        $contact_id = base64_decode($encoded_id);
        $contact_id = is_numeric($contact_id) ? intval($contact_id) : 0;
         // Utilisez la fonction get_contact_info avec l'ID spécifié
        $contact_info = get_contact_info($contact_id);
        // Récupérez les valeurs des champs
        $validate_sms = isset($contact_info['validate-sms']) ? $contact_info['validate-sms'] : false;
        $preference_days = isset($contact_info['preference-days']) ? $contact_info['preference-days'] : '';
        $preference_times = isset($contact_info['preference-times']) ? $contact_info['preference-times'] : '';
    }
    // Condition pour afficher les différents templates
    if (!$contact_info) {
        // Affichez le template par défaut si aucune information de contact n'est disponible
        ob_start();
        include(plugin_dir_path(__FILE__) . 'templates/contact-survey-form.php');
        return ob_get_clean();
    } elseif (!$validate_sms) {
        // Affichez le template "contact-survey-validation.php" si validate-sms est false
        ob_start();
        include(plugin_dir_path(__FILE__) . 'templates/contact-survey-validation.php');
        return ob_get_clean();
    } elseif ($validate_sms && empty($preference_days) && empty($preference_times)) {
        // Affichez le template "contact-survey-preferences.php" si validate-sms est true, mais les préférences ne sont pas renseignées
        ob_start();
        include(plugin_dir_path(__FILE__) . 'templates/contact-survey-preferences.php');
        return ob_get_clean();
    } else {
        // Affichez le template "contact-survey-confirmation.php" si toutes les conditions sont remplies
        ob_start();
        include(plugin_dir_path(__FILE__) . 'templates/contact-survey-confirmation.php');
        return ob_get_clean();
    }
}

add_shortcode('contact_survey', 'contact_survey_shortcode');
