<?php include __DIR__ . '/contact-survey-header.php'; ?>
<div class="contact-survey-wrapper">
    <div class="contact-survey-nav">
        <div class="js-back">
            <span class="icon icon--back">Back</span>
        </div>
        <div class="confidential">
            <span class="icon icon--lock"></span>
            <span class="text">Vos données sont confidentielles</span>
        </div>
    </div>
    <div class="contact-survey-container">
        <form id="contact-survey-form" class="contact-form-survey">
            <div class="step" data-step="1">
                <!-- Situation familiale -->
                <span class="step-info">Étape 1 sur 4</span>
                <label class="question" for="situation">Quel est votre situation familiale ?</label>
                <input type="radio" name="situation" value="Marié(e) / Pacsé(e)" id="married"><label for="married">Marié(e) / Pacsé(e)</label>
                <input type="radio" name="situation" value="Célibataire / Union Libre" id="single"><label for="single">Célibataire / Union Libre</label>
                <input type="radio" name="situation" value="Divorcé(e)" id="divorced"><label for="divorced">Divorcé(e)</label>
                <input type="radio" name="situation" value="Veuf(ve)" id="veuf"><label for="veuf">Veuf(ve)</label>
            </div>

            <div class="step" data-step="2">
                <!-- Âge -->
                <span class="step-info">Étape 2 sur 4</span>
                <label class="question" for="age">Quel est votre âge ?</label>
                <input type="radio" name="age" value="18 - 25 ans" id="18"><label for="18">18 - 25 ans</label>
                <input type="radio" name="age" value="26 - 40 ans" id="26"><label for="26">26 - 40 ans</label>
                <input type="radio" name="age" value="41 - 55 ans" id="41"><label for="41">41 - 55 ans</label>
                <input type="radio" name="age" value="+ 55 ans" id="55"><label for="55">+ 55 ans</label>
            </div>

            <div class="step" data-step="3">
                <!-- Nombre d'enfants à charge -->
                <span class="step-info">Étape 3 sur 4</span>
                <label class="question" for="children">Nombre d'enfants à charge ?</label>
                <input type="radio" name="children" value="0" id="child-0"><label for="child-0">0</label>
                <input type="radio" name="children" value="1" id="child-1"><label for="child-1">1</label>
                <input type="radio" name="children" value="2" id="child-2"><label for="child-2">2</label>
                <input type="radio" name="children" value="+3" id="child-3"><label for="child-3">+ 3</label>
            </div>

            <div class="step" data-step="4">
                <!-- Revenu mensuel après impôts -->
                <span class="step-info">Étape 4 sur 4</span>
                <label class="question" for="revenue">Quel est votre revenu mensuel avant impôts ?</label>
                <input class="unfill" type="radio" name="revenue" value="0€ - 2500€" id="revenue-0"><label for="revenue-0">0 € - 2 500 €</label>
                <input class="unfill"type="radio" name="revenue" value="2500€ - 4000€" id="revenue-2500"><label for="revenue-2500">2 500 € - 4 000 €</label>
                <input class="unfill"type="radio" name="revenue" value="4000€ - 8000€" id="revenue-4000"><label for="revenue-4000">4 000 € - 8 000 €</label>
                <input class="unfill" type="radio" name="revenue" value="+8000€" id="revenue-8000"><label for="revenue-8000">+ 8 000 €</label>
            </div>
            <div class="step content" data-step="5">
                <!-- Informations -->
                <h3 class="label">Votre résultat est prêt !</h3>
                <div class="input-section">
                    <div class="input-field input-field--2">
                        <label for="firstname">Prénom</label>
                        <input type="text" name="firstname" placeholder="Jean" required>
                    </div>
                    <div class="input-field input-field--2">
                        <label for="name">Nom</label>
                        <input type="text" name="name" placeholder="Dupont" required>
                    </div>
                </div>
                <div class="input-section">
                    <div class="input-field input-field--2">
                        <label for="email">Email</label>
                        <input type="email" name="email" placeholder="jean.dupont@gmail.com" pattern="^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$" required>
                    </div>
                    <div class="input-field input-field--2">
                        <label for="zipcode">Code postal</label>
                        <input type="text" name="zipcode" placeholder="75000" required>
                    </div>
                </div>
                <div class="input-section input-section--image">
                    <div class="input-field">
                        <label for="phone">Numéro de téléphone</label>
                        <input class="phone" type="tel" name="phone" placeholder="0600000000" pattern="^\d{2}\d{2}\d{2}\d{2}\d{2}$" required>
                    </div>
                    <div class="rgpd"></div>
                </div>
                
                <input type="hidden" name="validate-sms" value="false">

                <p class="label label--small">Pour des raisons de confidentialités, un code sms va vous être envoyé</p>
                
                <button class="submit disabled" type="submit" id="next-button">Accéder à mon résultat</button>

                <p class="info">
                "En cliquant sur "Accéder à mon résultat", je reconnais avoir pris connaissance et accepté les conditions générales
                Les données personnelles communiquées sont uniquement utilisés pour permettre l'utilisation des services Chooze.
                Pour plus d'informations veuillez consulter notre politique de confidentialité
                </p>
            </div>
        </form>
        <div class="step-progress">
            <div class="step-bar js-progress"></div>
        </div>
    </div>
</div>
<div id="contact-survey-footer"></div>
<div id="loading">
    <div class="loader"></div>
</div>
