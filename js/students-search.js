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


document.getElementById('search').addEventListener('input', function(event) {
  update(event.target.value);
});

