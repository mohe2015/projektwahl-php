var strength = {
  0: "Extrem Schwach",
  1: "Schwach",
  2: "Okay",
  3: "Stark",
  4: "Sehr stark"
}

var password = document.getElementById('new_password');
var meter = document.getElementById('password-strength-meter');
var feedback = document.getElementById('password-strength-text');
var show_password = document.getElementById('show-password');

show_password.addEventListener('click', function() {
  if (password.type == "password") {
    password.type = "text";
  } else {
    password.type = "password";
  }
});

function checkPassword() {
  var val = password.value;

  fetch(`/zxcvbn.php?${val}`, {
    method: 'GET',
    redirect: "error"
  })
  .then(status)
  .then(json)
  .then(function (result) {

    // Update the password strength meter
    meter.value = result.score;

    // Update the text indicator
    if (val !== "") {
      meter.innerText = "Strength: " + strength[result.score];
    } else {
      meter.innerText = "";
    }
    feedback.innerHTML = "Verwende Passwörter aus zufälligen Wörten, da du dir sie leichter merken kannst. Oder verwende am Besten einen Passwort-Manager z.B. <a href=\"https://bitwarden.com/\" target=\"_blank\" rel=\"noopener noreferrer\">Bitwarden</a>. " + result.feedback.suggestions.join('\n') + "\n" + result.feedback.warning;
  })
  .catch(function (error) {
    alert(error);
  });
}

password.addEventListener('input', checkPassword);
checkPassword();
