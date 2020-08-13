/*
SPDX-FileCopyrightText: 2020 Moritz Hedtke <Moritz.Hedtke@t-online.de>
SPDX-License-Identifier: AGPL-3.0-or-later

Diese Software kann eine Projektwahl verwalten, wie sie beispielsweise für eine Projektwoche benötigt wird. 
Copyright (C) 2020 Moritz Hedtke <Moritz.Hedtke@t-online.de>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published
by the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/
var form = $("#form-supervisors");
var dialog = $("#dialog-supervisors");
var myModal = new bootstrap.Modal(dialog, {show: false})
var button = $('#show-supervisors-dialog');
var input = $('#search-supervisors');
button.style = "";

var supervisors = $$('li input[type="checkbox"]');
supervisors.forEach(function (e) {
  e.addEventListener('change', function (event) {
    $('.' + e.id).selected = e.checked;
    update(input.value);
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

  button.innerText = $$('li input:checked + label').map(function (x) { return x.innerText }).join("; ") || "Keine";
}

button.addEventListener('click', function (event) {
  event.preventDefault();
  myModal.show();
});

input.addEventListener('input', function(event) {
  update(event.target.value);
});

update("");

// Hide the other one if javascript loaded
$('#select-supervisors').hidden = true;
