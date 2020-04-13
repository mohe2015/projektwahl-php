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

const $ = function (selector, context = document) { return context.querySelector(selector) }

const $$ = function $$ (selector, context = document) {
  const elements = Array.from(context.querySelectorAll(selector))

  elements.html = function(newHtml) {
    elements.forEach(function (element) {
      element.innerHTML = newHtml
    })
    return elements
  };

  elements.css = function (newCss) {
    elements.forEach(function (element) {
      Object.assign(element.style, newCss)
    })
    return elements
  };

  elements.on = function (event, handler, options) {
    elements.forEach(function (element) {
      element.addEventListener(event, handler, options)
    })
    return elements
  };

  return elements;
}

function status(response) {
  if (response.status == 200) {
    return Promise.resolve(response)
  } else {
    var error = new Error(response.statusText);
    error.response = response;
    return Promise.reject(error)
  }
}

function json(response) {
  return response.json();
}

window.addEventListener("load", function () {
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })
})
