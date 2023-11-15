<?php
// Logique pour envoyer des e-mails
function send_contact_email($data) {
    // Assurez-vous d'ajouter votre logique pour envoyer un e-mail avec les informations du contact
    $to = $data['email'];
    $subject = 'Confirmation de contact';
    $message = 'Merci pour votre contact. Voici les détails que nous avons reçus : ' . PHP_EOL . PHP_EOL;
    $message .= 'Prénom : ' . $data['firstname'] . PHP_EOL;
    $message .= 'Nom : ' . $data['name'] . PHP_EOL;
    $message .= 'Email : ' . $data['email'] . PHP_EOL;
    $message .= 'Code postal : ' . $data['zipcode'] . PHP_EOL;
    $message .= 'Numéro de téléphone : ' . $data['phone'] . PHP_EOL;
    $message .= 'Situation familiale : ' . $data['situation'] . PHP_EOL;
    $message .= 'Âge : ' . $data['age'] . PHP_EOL;
    $message .= 'Nombre d\'enfants à charge : ' . $data['children'] . PHP_EOL;
    $message .= 'Revenu mensuel après impôts : ' . $data['revenue'] . PHP_EOL;

    wp_mail($to, $subject, $message);
}