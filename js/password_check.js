function checkPasswordEquality(target) {
  if ($('#new_password').value !== $('#new_password_repeated').value) {
    console.log("invalid pw")
    $('#new_password').setCustomValidity('Passwörter müssen übereinstimmen!')
    $('#new_password_repeated').setCustomValidity('Passwörter müssen übereinstimmen!')
  } else {
    console.log("valid pw")
    $('#new_password').setCustomValidity('')
    $('#new_password_repeated').setCustomValidity('')
  }
  onInvalid($('#new_password'))
  onInvalid($('#new_password_repeated'))
}

// this only gets called on form submit
function onInvalid(target) {
  console.log("invalid")
  if (target.validity.valueMissing) {
    target.setCustomValidity("Pflichtfeld!");
  }
  target.parentElement.querySelector('.invalid-feedback').innerText = target.validationMessage
}

$('#new_password').addEventListener('input', e => checkPasswordEquality(e.target))
$('#new_password_repeated').addEventListener('input', e => checkPasswordEquality(e.target))

$('#new_password').addEventListener('invalid', e => onInvalid(e.target))
$('#new_password_repeated').addEventListener('invalid', e => onInvalid(e.target))
