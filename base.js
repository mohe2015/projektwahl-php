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
