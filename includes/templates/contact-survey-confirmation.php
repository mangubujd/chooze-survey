<?php include __DIR__ . '/contact-survey-header.php'; ?>
<div class="contact-survey-wrapper">
    <div class="contact-survey-container contact-survey-container--2">
        <div class="contact-form-survey contact-form-survey--confirmation">
            <h2 class="label">Félicitation !</h2>
            <?php if ($contact_info['preference-days'] == '-') : ?>
            <p class="label">Un experts prendra contact avec vous dans les plus brefs délais <br>afin de vous présenter vos résultats.</p>
            <?php else : ?>
            <p class="label">Un experts prendra contact avec vous <?php echo $contact_info['preference-days']; ?>,<br>
            <?php echo $contact_info['preference-times']; ?> afin de vous présenter vos résultats.</p>
            <?php endif; ?>
            <div class="button-container">
                <a class="submit" href="/">Revenir à la page d'accueil</a>
            </div>
        </div>
    </div>
</div>
<div id="contact-survey-footer"></div>
