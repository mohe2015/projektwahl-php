var input = $('#search');

function update(query) {
  var students = $$('tr');
  var query = query.normalize('NFD').replace(/[\u0300-\u036f]/g, "");
  students.forEach(function (e) {
    var string = e.id.replace("-", " ").normalize('NFD').replace(/[\u0300-\u036f]/g, "");
    if (string.toLowerCase().indexOf(query.toLowerCase()) === -1) {
      e.hidden = true;
    } else {
      e.hidden = false;
    }
  });
}

input.addEventListener('input', function(event) {
  update(event.target.value);
});
