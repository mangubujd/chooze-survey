document.addEventListener("DOMContentLoaded", function () {
  // Steps
  const steps = document.querySelectorAll(".step");
  const backButton = document.querySelector(".js-back");
  const progressBar = document.querySelector(".js-progress");
  const nextButton = document.getElementById("next-button");
  let currentStep = 1;
  const displayStep = () => {
    steps.forEach((step) => {
      step.style.display = step.dataset.step == currentStep ? "block" : "none";
    });
    if (currentStep == 1) {
      backButton.classList.remove("visible");
    } else {
      backButton.classList.add("visible");
    }
    // Progress
    progressBar.style.transform = `translateX(-${
      (currentStep / steps.length) * -100 + 100
    }%)`;
    // TRACKING
    const events = {
      event: "click",
      label: `Etape : ${currentStep}`,
    };
    window.dataLayer && window.dataLayer.push(events);
    console.log("ℹ️ [TRACKING] - ", events);
  };
  const goToNextStep = () => {
    if (currentStep + 1 <= steps.length) {
      currentStep++;
      displayStep();
    }
  };

  const goToPrevStep = () => {
    if (currentStep - 1 > 0) {
      currentStep--;
    }
    displayStep();
  };

  backButton && backButton.addEventListener("click", goToPrevStep);

  steps &&
    steps.forEach((step) => {
      const numberStep = step.dataset.step;
      const inputs = step.querySelectorAll('input[type="radio"]');
      inputs.forEach((input) => {
        input.addEventListener("click", (e) => {
          if (e.target.checked) {
            goToNextStep();
          }
        });
      });
    });

  // Code Input
  const codes = document.querySelectorAll(".password");

  let currentCodeNumber = 0;
  codes &&
    codes.forEach((code, index) => {
      code.addEventListener("keyup", (e) => {
        if (e.target.value !== "") {
          if (currentCodeNumber + 1 <= codes.length - 1) {
            currentCodeNumber++;
            codes[currentCodeNumber].focus();
          }
        }
      });
      code.addEventListener("focus", () => {
        currentCodeNumber = index;
      });
    });
  // Phone input
  const phone = document.querySelector(".phone");
  phone &&
    phone.addEventListener("change", (e) => {
      e.target.value = e.target.value.replaceAll(" ", "").replace("+33", "0");
    });

  // Identifiez les éléments du formulaire
  const formStep = document.getElementById("contact-survey-form");
  const formValidationStep = document.getElementById(
    "contact-survey-validation-form"
  );
  const formPreferencesStep = document.getElementById(
    "contact-survey-preferences-form"
  );
  const loader = document.getElementById("loading");
  let isLoad = false;
  const isLoading = () => {
    loader.style.opacity = "1";
    loader.style.visibility = "visible";
    isLoad = true;
  };
  const isNotLoading = () => {
    loader.style.opacity = "0";
    loader.style.visibility = "hidden";
    isLoad = false;
  };
  // Gestion de la soumission des formulaires
  formStep &&
    formStep.addEventListener("change", (e) => {
      let missingKey = false;
      const formData = new FormData(formStep);
      const jsonData = {};
      formData.forEach((value, key) => {
        jsonData[key] = value;
        if (!value || value == "") {
          missingKey = true;
        }
      });
      if (!missingKey && Object.keys(jsonData).length == 10) {
        nextButton.classList.remove("disabled");
      } else {
        nextButton.classList.add("disabled");
      }
    });
  // Formulaire 1: Informations avec étape
  formStep &&
    formStep.addEventListener("submit", function (e) {
      e.preventDefault();
      if (isLoad) return;
      isLoading();
      const formData = new FormData(formStep);
      // Convertissez l'objet FormData en un objet JSON
      const jsonData = {};
      formData.forEach((value, key) => {
        if (key == "phone") {
          jsonData[key] = value.replace("0", "+33");
        } else {
          jsonData[key] = value;
        }
      });

      // Convertissez l'objet JSON en chaîne JSON
      const jsonPayload = JSON.stringify(jsonData);

      // Envoyez les données du formulaire à votre API
      fetch("/wp-json/contact-survey/v1/validate-contact", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: jsonPayload, // Utilisez la chaîne JSON comme corps de la requête
      })
        .then((response) => response.json())
        .then((data) => {
          //console.log({ data });
          isNotLoading();
          if (data.success) {
            if (data.ID) {
              window.location.href = "?id=" + data.ID;
            }
          } else if (data.message) {
            console.log("❌ Une erreur est survenue: ", data);
          } else {
            console.log("❌ Erreur lors de la soumission du formulaire.");
          }
        });
    });
  // Formulaire 2: Validation SMS
  formValidationStep &&
    formValidationStep.addEventListener("submit", function (e) {
      e.preventDefault();
      if (isLoad) return;
      isLoading();
      const formData = new FormData(formValidationStep);
      // Convertissez l'objet FormData en un objet JSON
      const jsonData = {};
      formData.forEach((value, key) => {
        jsonData[key] = value;
      });
      // Convertissez l'objet JSON en chaîne JSON
      const jsonPayload = JSON.stringify(jsonData);
      // Envoyez les données du formulaire à votre API
      fetch("/wp-json/contact-survey/v1/validate-contact-sms", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: jsonPayload, // Utilisez la chaîne JSON comme corps de la requête
      })
        .then((response) => response.json())
        .then((data) => {
          //console.log({ data });
          isNotLoading();
          if (data.success) {
            if (data.ID) {
              window.location.reload();
            }
          } else if (data.message) {
            console.log("❌ Une erreur est survenue: ", data);
          } else {
            console.log("❌ Erreur lors de la soumission du formulaire.");
          }
        });
    });
  // Bouton: Resend Code SMS
  var resendSMSBtn = document.getElementById("resend-sms-button");
  resendSMSBtn &&
    resendSMSBtn.addEventListener("click", function (e) {
      e.preventDefault();
      if (isLoad) return;
      isLoading();
      // Envoyez les données du formulaire à votre API
      fetch("/wp-json/contact-survey/v1/resend-code-sms", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          "resend-id": resendSMSBtn.dataset.id,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          // console.log({
          //   data,
          // });
          isNotLoading();
          if (data.success) {
            if (data.ID) {
              window.location.reload();
            }
          } else if (data.message) {
            console.log("❌ Une erreur est survenue: ", data);
          } else {
            console.log("❌ Erreur lors de la soumission du formulaire.");
          }
        });
    });
  // Formulaire 3: Validation Préférences
  const onSubmitPref = (jsonPayload) => {
    // Envoyez les données du formulaire à votre API
    fetch("/wp-json/contact-survey/v1/validate-preferences", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: jsonPayload, // Utilisez la chaîne JSON comme corps de la requête
    })
      .then((response) => response.json())
      .then((data) => {
        //console.log({ data });
        isNotLoading();
        if (data.success) {
          if (data.ID) {
            window.location.reload();
          }
        } else if (data.message) {
          console.log("❌ Une erreur est survenue: ", data);
        } else {
          console.log("❌ Erreur lors de la soumission du formulaire.");
        }
      });
  };
  formPreferencesStep &&
    formPreferencesStep.addEventListener("submit", function (e) {
      e.preventDefault();
      if (isLoad) return;
      isLoading();
      const formData = new FormData(formPreferencesStep);
      // Convertissez l'objet FormData en un objet JSON
      const jsonData = {};
      formData.forEach((value, key) => {
        jsonData[key] = value;
      });
      // Convertissez l'objet JSON en chaîne JSON
      const jsonPayload = JSON.stringify(jsonData);
      onSubmitPref(jsonPayload);
      return;
    });
  // Ignore button
  const ignore = document.querySelector("#ignore");
  ignore &&
    ignore.addEventListener("click", () => {
      if (isLoad) return;
      isLoading();
      onSubmitPref(
        JSON.stringify({
          id: ignore.dataset.id,
          "preference-days": "-",
          "preference-times": "-",
        })
      );
      return false;
    });
});
