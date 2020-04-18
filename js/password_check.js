function checkPasswordEquality(event) {
  if ($('#new_password').value === $('#new_password_repeated').value) {
    $('#new_password_repeated').setCustomValidity('')
  } else {
      console.log("FAIL")
      $('#new_password_repeated').setCustomValidity('Passwörter müssen übereinstimmen!')
  }
}

$('#new_password').addEventListener('input', checkPasswordEquality)
$('#new_password_repeated').addEventListener('input', checkPasswordEquality)

console.log("jo")
