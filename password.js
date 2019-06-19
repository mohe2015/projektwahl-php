var strength = {
  0: "Worst",
  1: "Bad",
  2: "Weak",
  3: "Good",
  4: "Strong"
}


var password = document.getElementById('new_password');
var meter = document.getElementById('password-strength-meter');
var feedback = document.getElementById('password-strength-text');

password.addEventListener('input', function() {
  var val = password.value;

  fetch(`/zxcvbn.php?${val}`, {
    method: 'GET',
    redirect: "error"
  })
  .then(status)
  .then(json)
  .then((result) => {
    console.log(result);

    // Update the password strength meter
    meter.value = result.score;

    // Update the text indicator
    if (val !== "") {
      meter.innerText = "Strength: " + strength[result.score];
    } else {
      meter.innerText = "";
    }
    feedback.innerText = result.feedback.suggestions.join('\n') + "\n" + result.feedback.warning;
  })
  .catch((error) => {
    alert(error);
  });
});
