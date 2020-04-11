/*
projektwahl-php - manage project selection for many people
Copyright (C) 2019 Moritz Hedtke <Moritz.Hedtke@t-online.de>

projektwahl-php is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

projektwahl-php is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with projektwahl-php.  If not, see <https://www.gnu.org/licenses/>.
*/
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
  var formData = new FormData(document.getElementById('change-password-form'));

  fetch("zxcvbn.php", {
    method: 'POST',
    body: formData,
    redirect: "error",
    credentials: "same-origin"
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
