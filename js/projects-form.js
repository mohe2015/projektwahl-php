var form = $("#form-supervisors");
var dialog = $("#dialog-supervisors");
dialogPolyfill.registerDialog(dialog);
var button = $('#show-supervisors-dialog');
var input = $('#search-supervisors');
button.style = "";

// TODO implement escape
dialog.addEventListener('close', function onClose(e) {
  e.preventDefault();
  $('body').classList.remove('modal-open');
});

$('#save-supervisors').addEventListener('click', function(event) {
  event.preventDefault();
  button.innerText = $$('li input:checked').map(function (x) { x.parentNode.innerText }).join("; ") || "Keine";
  dialog.close();
});

var supervisors = $$('li input[type="checkbox"]');
supervisors.forEach(function (e) {
  e.addEventListener('change', function (event) {
    $('.' + this.id).selected = this.checked;
  });
});

function update(query) {
  var supervisors = $$('li input[type="checkbox"]');
  var query = query.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
  supervisors.forEach(function(e) {
    var string = e.id.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
    if (string.toLowerCase().indexOf(query.toLowerCase()) === -1) {
      e.parentElement.hidden = true;
    } else {
      e.parentElement.hidden = false;
    }
  });
  supervisors.sort(function (a, b) {
    return b.checked - a.checked;
  });
  var ul = $('ul[class="dropdown"]');
  ul.innerHTML = null;
  supervisors.forEach(function (e) { ul.append(e.parentNode) });
}

button.addEventListener('click', function (event) {
  event.preventDefault();
  document.querySelector('body').classList.add('modal-open');
  dialog.show();
});

input.addEventListener('input', function(event) {
  update(event.target.value);
});

update("");

// Hide the other one if javascript loaded
$('#select-supervisors').hidden = true;
