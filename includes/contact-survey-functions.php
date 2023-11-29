<?php

function register_contact_survey_custom_post_type() {
    // Assurez-vous d'ajouter la logique pour créer le custom post type "Contact Survey"
    register_post_type('contact-survey', array(
        'labels' => array(
            'name' => 'Contact Surveys',
            'singular_name' => 'Contact Survey',
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title')
    ));
}

add_action('init', function() {
    if (post_type_exists('contact-survey')) {
        return;
    }
    register_contact_survey_custom_post_type();
});

add_action('init', 'register_contact_survey_custom_post_type');


// Ajoutez cette fonction à votre plugin pour afficher les métadonnées personnalisées
function add_contact_survey_meta_box() {
    add_meta_box(
        'contact-survey-meta-box',
        'Informations de Contact',
        'display_contact_survey_meta_box',
        'contact-survey', // Type de post
        'normal', // Emplacement de la méta-box (normal, side, advanced)
        'high' // Priorité de la méta-box (high, core, default, low)
    );
}

add_action('add_meta_boxes', 'add_contact_survey_meta_box');

function display_value($value) {
    return $value ? esc_html($value) : 'Non renseigné(e)';
}

// Callback pour afficher les métadonnées personnalisées
function display_contact_survey_meta_box($post) {
    // Récupérez les métadonnées personnalisées
    $email = get_post_meta($post->ID, 'email', true);
    $phone = get_post_meta($post->ID, 'phone', true);
    $age = get_post_meta($post->ID, 'age', true);
    $revenue = get_post_meta($post->ID, 'revenue', true);
    $children = get_post_meta($post->ID, 'children', true);
    $zipcode = get_post_meta($post->ID, 'zipcode', true);
    $validate_sms = get_post_meta($post->ID, 'validate-sms', true);
    $code_sms = get_post_meta($post->ID, 'code-sms', true);
    $days = get_post_meta($post->ID, 'preference-days', true);
    $times = get_post_meta($post->ID, 'preference-times', true);

    // Affichez les métadonnées dans l'interface WordPress
    ?>
    <table class="form-table">
        <tr>
            <th><label for="email">Email</label></th>
            <td><?php echo display_value($email); ?></td>
        </tr>
        <tr>
            <th><label for="phone">Phone</label></th>
            <td><?php echo display_value($phone); ?></td>
        </tr>
        <tr>
            <th><label for="phone">Age</label></th>
            <td><?php echo display_value($age); ?></td>
        </tr>
        <tr>
            <th><label for="phone">Children</label></th>
            <td><?php echo display_value($children); ?></td>
        </tr>
        <tr>
            <th><label for="phone">Revenue</label></th>
            <td><?php echo display_value($revenue); ?></td>
        </tr>
        <tr>
            <th><label for="phone">Zipcode</label></th>
            <td><?php echo display_value($zipcode); ?></td>
        </tr>
        <tr>
            <th><label for="validate-sms">Validation SMS</label></th>
            <td><?php echo $validate_sms == true ? 'Oui' : 'Non'; ?></td>
        </tr>
        <tr>
            <th><label for="phone">Code SMS</label></th>
            <td><?php echo display_value($code_sms); ?></td>
        </tr>
        <tr>
            <th><label for="days">Préférence de jour:</label></th>
            <td><?php echo display_value($days); ?></td>
        </tr>
        <tr>
            <th><label for="times">Préférence de créneau</label></th>
            <td><?php echo display_value($times); ?></td>
        </tr>
    </table>
    <?php
}

// Enregistrez les modifications des métadonnées personnalisées
function save_contact_survey_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
    if ($post_id && get_post_type($post_id) == 'contact-survey') {
        // Mettez à jour les métadonnées personnalisées
        if (isset($_POST['email'])) {
            update_post_meta($post_id, 'email', sanitize_email($_POST['email']));
        }
        if (isset($_POST['phone'])) {
            update_post_meta($post_id, 'phone', $_POST['phone']);
        }
        if (isset($_POST['zipcode'])) {
            update_post_meta($post_id, 'zipcode', $_POST['zipcode']);
        }
        if (isset($_POST['age'])) {
            update_post_meta($post_id, 'age', $_POST['age']);
        }
        if (isset($_POST['revenue'])) {
            update_post_meta($post_id, 'revenue', $_POST['revenue']);
        }
        if (isset($_POST['chidren'])) {
            update_post_meta($post_id, 'children', $_POST['children']);
        }
        if (isset($_POST['validate-sms'])) {
            update_post_meta($post_id, 'validate-sms', $_POST['validate-sms'] == true);
        }
        if (isset($_POST['code-sms'])) {
            update_post_meta($post_id, 'code-sms', $_POST['code-sms']);
        }
        if (isset($_POST['preference-days'])) {
            update_post_meta($post_id, 'preference-days', $_POST['preference-days']);
        }
        if (isset($_POST['preference-times'])) {
            update_post_meta($post_id, 'preference-times', $_POST['preference-times']);
        }
    }
}

add_action('save_post', 'save_contact_survey_meta');
