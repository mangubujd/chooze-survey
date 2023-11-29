<?php
function handle_contact_validation($request) {
    // Récupérez les données soumises depuis la requête
    $data = $request->get_json_params();
    // Initialisez un tableau pour stocker les erreurs de validation
    $errors = array();
    // Vérifiez les données et effectuez les validations nécessaires
    if (
        empty($data['firstname']) || 
        empty($data['name']) || 
        empty($data['email']) ||
        empty($data['zipcode']) ||
        empty($data['phone']) ||
        empty($data['situation']) ||
        empty($data['age']) ||
        empty($data['children']) ||
        empty($data['revenue'])
        ) {
        //$errors['message'] = $data;
        // ERROR: Les informations sont manquantes
        $errors['message'] = 'Veuillez renseignez les informations obligatoires';
    }
    // Effectuez d'autres validations si nécessaire, en utilisant les valeurs fournies
    if (empty($errors)) {
        // Vérifiez si le contact existe déjà en fonction de l'adresse e-mail ou du numéro de téléphone
        $existing_contact_id = check_existing_contact($data['email'], $data['phone']);

        if ($existing_contact_id) {
            // Le contact existe déjà, mise à jour du contact
            // return rest_ensure_response(array('message' => 'Ce contact est déjà enregistré.'));
        }

        // Les données sont valides, enregistrez-les dans le Custom Post Type "Contact Survey"
        // ou mettre à jour le contact si existant
        $contact = create_contact_entry($data, $existing_contact_id);

        if ($contact) {
            // Le contact a été enregistré avec succès
            // Envoi du code de validation SMS
            $timestamp = time();
            $waitingTime = 0;
            $sendCode = false;
            if ( $timestamp - $contact['last_updated'] <= 120000 && $contact['isNew'] ) {
                $sendCode = true;
                send_sms_code($contact['phone'], $contact['code']);
            } else {
                $waitingTime = $timestamp - $contact['last_updated'];
            }
            $message = $existing_contact_id ? 'Contact modifié avec succès.' : 'Contact enregistré avec succès.';
            return rest_ensure_response(array('success' => true, 'message' => $message, 'ID' => base64_encode($contact['ID']), 'time' => $waitingTime, 'send_code' => $sendCode, 'existing' => $existing_contact_id));
        } else {
            // ERROR: Une erreur s'est produite lors de l'enregistrement
            return rest_ensure_response(array('message' => "Erreur lors de l'enregistrement de vos informations."));
        }
    } else {
        // ERROR: Il y a des erreurs de validation, renvoyez un message d'erreur
        return rest_ensure_response($errors);
    }
}

function handle_contact_validation_sms($request) {
     // Récupérez les données soumises depuis la requête
     $data = $request->get_json_params();
     // Initialisez un tableau pour stocker les erreurs de validation
     $errors = array();
     // Vérifiez les données et effectuez les validations nécessaires
     if (
        $data['code-1'] == '' ||
        $data['code-2'] == '' ||
        $data['code-3'] == '' ||
        $data['code-4'] == '' ||
        empty($data['id'])
         ) {
         // ERROR: Les informations sont manquantes
         $errors['message'] = 'Veuillez renseignez un code';
     }
     // Effectuez d'autres validations si nécessaire, en utilisant les valeurs fournies
     if (empty($errors)) {
        $code = get_post_meta($data['id'], 'code-sms', true);
        $sendingCode = esc_html($data['code-1']).esc_html($data['code-2']).esc_html($data['code-3']).esc_html($data['code-4']);
        $contactID = null;
        if ( $code == $sendingCode ) {
            $contactID = update_post_meta($data['id'], 'validate-sms', true);
        }
         if ($contactID) {
             $message = 'Code enregistré avec succès.';
             return rest_ensure_response(array('success' => true, 'message' => $message, 'ID' => $data['id']));
         } else {
             // ERROR: Une erreur s'est produite lors de l'enregistrement
             return rest_ensure_response(array('message' => "Erreur lors de l'enregistrement du code."));
         }
     } else {
         // ERROR: Il y a des erreurs de validation, renvoyez un message d'erreur
         return rest_ensure_response($errors);
     }
}

function handle_resend_code_sms($request) {
    // Récupérez les données soumises depuis la requête
    $data = $request->get_json_params();
    // Initialisez un tableau pour stocker les erreurs de validation
    $errors = array();
    // Vérifiez les données et effectuez les validations nécessaires
    if (
        empty($data['resend-id'])
        ) {
        // ERROR: Le numéro est manquant
        $errors['message'] = 'Erreur lors de la récupération des informations';
    }
     // Effectuez d'autres validations si nécessaire, en utilisant les valeurs fournies
     if (empty($errors)) {
        $code = get_post_meta($data['resend-id'], 'code-sms', true);
        $phone = get_post_meta($data['resend-id'], 'phone', true);
        $sendSMS = send_sms_code($phone , $code);
        if ($sendSMS) {
            $message = 'Code renvoyé avec succès.';
            return rest_ensure_response(array('success' => true, 'message' => $sendSMS, 'phone' => $data['phone']));
        }
        else {
             // ERROR: Erreur lors de l'envoi
            $errors['message'] = 'Erreur lors de l\'envoi du code';
            return rest_ensure_response($errors);
        }
     } else {
         // ERROR: Il y a des erreurs de validation, renvoyez un message d'erreur
         return rest_ensure_response($errors);
     }
}

function handle_validate_preferences($request) {
    // Récupérez les données soumises depuis la requête
    $data = $request->get_json_params();
    // Initialisez un tableau pour stocker les erreurs de validation
    $errors = array();
    // Vérifiez les données et effectuez les validations nécessaires
    if (
        empty($data['id']) || 
        empty($data['preference-days']) || 
        empty($data['preference-times'])
        ) {
        // ERROR: Les informations sont manquantes
        $errors['message'] = 'Erreur lors de la récupération des informations';
    }
     // Effectuez d'autres validations si nécessaire, en utilisant les valeurs fournies
     if (empty($errors)) {
        $contact = get_post($data['id']);
        if ($contact->ID) {
            update_post_meta($contact->ID, 'preference-days', $data['preference-days']);
            update_post_meta($contact->ID, 'preference-times', $data['preference-times']);
            return rest_ensure_response(array('success' => true, 'ID' => $contact->ID));
        } else {
            // ERROR: Erreur lors de la récupération du contact
           $errors['message'] = 'Erreur lors la récupération du contact';
           return rest_ensure_response($errors);
       }

     } else {
         // ERROR: Il y a des erreurs de validation, renvoyez un message d'erreur
         return rest_ensure_response($errors);
     }
}

function generate_random_sms_code() {
    return str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
}

function get_contact_info($contact_id) {
    // Récupérez les informations de contact en fonction de l'ID spécifié
    // Vous pouvez utiliser les fonctions de WordPress pour cela
    $contact_post = get_post($contact_id);

    if ($contact_post && $contact_post->post_type === 'contact-survey') {
        $contact_info = array(
            'ID' => $contact_post->ID, 
            'phone' => get_post_meta($contact_id, 'phone', true), // Récupérez le champ "phone"
            'validate-sms' => get_post_meta($contact_id, 'validate-sms', true), // Récupérez le champ "validate-sms"
            'last_updated' => get_post_modified_time('U', false, $contact_id), // Récupérez la dernière validation 
            'code-sms' => get_post_meta($contact_id, 'code-sms', true), // Récupérez le champ "code-sms"
            'preference-days' => get_post_meta($contact_id, 'preference-days', true), // Récupérez le champ "preference-days"
            'preference-times' => get_post_meta($contact_id, 'preference-times', true), // Récupérez le champ "preference-times"
        );

        return $contact_info;
    } else {
        // Le contact avec l'ID spécifié n'existe pas ou n'est pas du bon type
        return false;
    }
}

function check_existing_contact($email, $phone) {
    // Effectuez une recherche pour trouver un contact existant en fonction de l'adresse e-mail ou du numéro de téléphone
    // Utilisez les fonctions de WordPress pour cela (par exemple, WP_Query)

    // Exemple de recherche par e-mail :
    $existing_contact_query = new WP_Query(array(
        'post_type' => 'contact-survey',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'email',
                'value' => $email,
                'compare' => '=',
            ),
            array(
                'key' => 'phone',
                'value' => $phone,
                'compare' => '=',
            ),
        ),
    ));

    if ($existing_contact_query->have_posts()) {
        $existing_contact = $existing_contact_query->posts[0]; // Prenez le premier résultat trouvé
        return $existing_contact->ID;
    }

    return 0; // Aucun contact existant n'a été trouvé
}

function create_contact_entry($data, $isUpdatePostID = false) {
    // Créez un tableau de données pour la nouvelle entrée du Custom Post Type
    $contact_data = array(
        'post_type' => 'contact-survey', // Type de publication personnalisée
        'post_title' => sanitize_text_field($data['firstname'] . ' ' . $data['name']), // Titre du contact
        'post_status' => 'publish', // Statut de publication
    );

    // Insérez la nouvelle entrée dans le Custom Post Type
    $contact_id = $isUpdatePostID ? wp_update_post(array_merge($contact_data, array('ID' => $isUpdatePostID))) : wp_insert_post($contact_data);
    if ($contact_id) {
        // Enregistrez les autres champs personnalisés dans le Custom Post Type
        update_post_meta($contact_id, 'email', sanitize_email($data['email']));
        update_post_meta($contact_id, 'situation', $data['situation']);
        update_post_meta($contact_id, 'phone', $data['phone']);
        update_post_meta($contact_id, 'zipcode', $data['zipcode']);
        update_post_meta($contact_id, 'age', $data['age']);
        update_post_meta($contact_id, 'revenue', $data['revenue']);
        update_post_meta($contact_id, 'children', $data['children']);
        $code = $isUpdatePostID ? get_post_meta($contact_id, 'code-sms', true) : generate_random_sms_code();
        if ( !$isUpdatePostID ) {
            // Mettez à jour le champ "validate-sms" avec la valeur par défaut (false)
            update_post_meta($contact_id, 'validate-sms', false);
            // Ajoutez le code de validation SMS
            update_post_meta($contact_id, 'code-sms', $code);
            // Suppression de ces préférences si déjà renseignés
            update_post_meta($contact_id, 'preference-days', false);
            update_post_meta($contact_id, 'preference-times', false);
        }
        // Current Time
        $time = get_post_modified_time('U', false, $contact_id);
        return array('ID' => $contact_id, 'last_updated' => $time, 'code' => $code, 'phone' => $data['phone'], 'isNew' => $isUpdatePostID ? false : true); // Renvoyez l'ID du contact créé
    } else {
        return false; // Renvoyez false en cas d'échec
    }
}

// Logique pour gérer le formulaire de validation SMS
// Incluez ici le code pour générer et envoyer le code SMS, gérer la soumission et mettre à jour "validate-sms".

require __DIR__ . '/twilio-php-main/src/Twilio/autoload.php';

use Twilio\Rest\Client;

function send_sms_code($to, $sms_code) {
    $twilio_account_sid = get_option('twilio_account_sid', ''); // Récupérer depuis les options
    $twilio_auth_token = get_option('twilio_auth_token', ''); // Récupérer depuis les options
    $twilio_phone_number = get_option('twilio_phone_number', ''); // Récupérer depuis les options

    $client = new Client($twilio_account_sid, $twilio_auth_token);

    $message = $client->messages->create(
        $to,
        array(
            'from' => $twilio_phone_number,
            'body' => 'Votre code Chooze pour la validation SMS est' . $sms_code
        )
    );

    return array($to, $sms_code, $twilio_account_sid, $twilio_auth_token,  $twilio_phone_number, $client );

    return $message;
}