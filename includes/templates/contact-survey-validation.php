<?php include __DIR__ . '/contact-survey-header.php'; ?>
<div class="contact-survey-wrapper">
    <div class="contact-survey-container">
        <div class="contact-survey-nav">
            <div class="back">
                <a href="<?php echo get_permalink(); ?>">
                    <span class="icon icon--back">Back</span>
                </a>
            </div>
            <div class="confidential">
                <span class="icon icon--lock"></span>
                <span class="text">Vos données sont confidentielles</span>
            </div>
        </div>
    <?php
    // Vérifiez si le champ "validate-sms" est à true
    $validate_sms = isset($contact_info['validate-sms']) && $contact_info['validate-sms'] === 'true';
    $timestamp = time();
    $lastSendingTime =  $timestamp - $contact_info['last_updated'];
    $debug = $_GET['debug'];
    if (!$validate_sms) {
        // Affichez le formulaire de validation SMS
         ?>
        <form id="contact-survey-validation-form" class="contact-form-survey contact-form-survey--validation">
            <h3 class="label">Renseignez le code qui vous a été envoyé</h3>
            <p class="info">Si vous n’avez pas reçu le code, <span id="resend-sms-button" data-id="<?php echo $contact_info['ID']; ?>">cliquez ici</span> pour en recevoir un nouveau</p>
            <input type="hidden" name="id" id="id" value=<?php echo $contact_info['ID']; ?>>
            <div class="code-field">
                <input class="password" type="password" name="code-1" id="code-1" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
                <input class="password" type="password" name="code-2" id="code-2" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
                <input class="password" type="password" name="code-3" id="code-3" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
                <input class="password" type="password" name="code-4" id="code-4" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
            </div>
            <div class="button-container">
                <button class="submit" type="submit" id="validate-sms-button">Valider</button>
            </div>
        </form>
        
        <?php if ($debug) : ?>
         <div class="debug">
            Numéro: <?php echo $contact_info['phone']; ?><br>
            Code: <?php echo $contact_info['code-sms']; ?><br>
            Last Updated: <?php echo date('d-m-Y H:i:s', $contact_info['last_updated']); ?><br>
            Time Left: <?php echo date("i:s", $lastSendingTime); ?><br>
        </div>
        <?php endif; ?>

        <?php
    } else {
       // Affichez un message de validation
       echo '<h3 class="label">Votre code a bien été validé !</h3>';
       echo '<p>Merci de recharger votre page !</p>';
    }
?>
    </div>
</div>
<div id="contact-survey-footer"></div>
<div id="loading">
    <div class="loader"></div>
</div>