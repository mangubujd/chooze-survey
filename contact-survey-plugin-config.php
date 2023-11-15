<?php
// Ajoutez un menu de configuration dans l'interface d'administration de WordPress.
function contact_survey_plugin_menu() {
    add_menu_page('Chooze Survey Plugin Settings', 'Chooze Survey', 'manage_options', 'contact-survey-settings', 'contact_survey_settings_page');
}

add_action('admin_menu', 'contact_survey_plugin_menu');

// Page de configuration du plugin.
function contact_survey_settings_page() {
    if (isset($_POST['update_settings'])) {
        update_option('twilio_account_sid', sanitize_text_field($_POST['twilio_account_sid']));
        update_option('twilio_auth_token', sanitize_text_field($_POST['twilio_auth_token']));
        update_option('twilio_phone_number', sanitize_text_field($_POST['twilio_phone_number']));
        update_option('contact_email', sanitize_email($_POST['contact_email'])); // Nouveau champ "Email de contact"
        echo '<div id="message" class="updated"><p>Paramètres mis à jour avec succès</p></div>';
    }

    $twilio_account_sid = get_option('twilio_account_sid', '');
    $twilio_auth_token = get_option('twilio_auth_token', '');
    $twilio_phone_number = get_option('twilio_phone_number', '');
    $contact_email = get_option('contact_email', ''); // Récupération de la nouvelle option "Email de contact"

    ?>
    <div class="wrap">
        <h2>Paramètres du plugin Contact Survey</h2>
        <form method="post">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">SID du compte Twilio :</th>
                    <td><input type="text" name="twilio_account_sid" value="<?php echo esc_attr($twilio_account_sid); ?>"></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Jeton d'authentification Twilio :</th>
                    <td><input type="text" name="twilio_auth_token" value="<?php echo esc_attr($twilio_auth_token); ?>"></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Numéro Twilio :</th>
                    <td><input type="text" name="twilio_phone_number" value="<?php echo esc_attr($twilio_phone_number); ?>"></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Email de contact :</th>
                    <td><input type="email" name="contact_email" value="<?php echo esc_attr($contact_email); ?>"></td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="update_settings" class="button-primary" value="Mettre à jour les paramètres">
            </p>
        </form>
    </div>
    <?php
}

function enqueue_plugin_styles() {
    wp_register_style('contact-survey-css', plugins_url('src/dist/css/contact-survey.css', __FILE__));
    wp_enqueue_style('contact-survey-css');
}

add_action('wp_enqueue_scripts', 'enqueue_plugin_styles');

function enqueue_plugin_scripts() {
    wp_register_script('contact-survey-js', plugins_url('src/dist/js/contact-survey.js', __FILE__), array('jquery'), null, true);
    wp_enqueue_script('contact-survey-js');
}

add_action('wp_enqueue_scripts', 'enqueue_plugin_scripts');


