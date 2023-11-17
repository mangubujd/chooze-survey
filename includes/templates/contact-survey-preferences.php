<?php include __DIR__ . '/contact-survey-header.php'; ?>
<div class="contact-survey-wrapper">
    <div class="contact-survey-container contact-survey-container--2">
        <form id="contact-survey-preferences-form" class="contact-form-survey contact-form-survey--preferences">
            <p class="label is-white">Vous êtes éligibles à plusieurs solutions de réductions d’impôts.</p>
            <h3 class="label">Un de nos experts prendra contact avec vous <br>afin de vous présenter vos résultats.</h3>
            <input type="hidden" name="id" id="id" value=<?php echo $contact_info['ID']; ?>>
            <div class="section-field">
                <label for="preferences">Avez-vous un créneau de préférence ?</label>
                <select name="preference-days" id="preference-days" required>
                    <option value="lundi">Lundi</option>
                    <option value="mardi">Mardi</option>
                    <option value="mercredi">Mercredi</option>
                    <option value="jeudi">Jeudi</option>
                    <option value="vendredi">Vendredi</option>
                    <!--<option value="samedi">Samedi</option>-->
                </select>
                <select name="preference-times" id="preference-times" required>
                    <option value="le matin">Matin</option>
                    <option value="l'après-midi">Après-midi</option>
                </select>
            </div>
            <div class="button-container">
                <input class="submit" type="submit" value="Valider">
            </div>
            <p class="is-white label ignore-button"><span id="ignore" data-id="<?php echo $contact_info['ID']; ?>">Ignorer l'étape</p>
        </form>
    </div>
</div>
<div id="contact-survey-footer"></div>
<div id="loading">
    <div class="loader"></div>
</div>