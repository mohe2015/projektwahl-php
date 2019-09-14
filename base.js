const $ = (selector, context = document) => context.querySelector(selector)

const $$ = function $$ (selector, context = document) {
  const elements = Array.from(context.querySelectorAll(selector))

  elements.html = function(newHtml) {
    this.forEach(element => {
      element.innerHTML = newHtml
    })
    return this
  };

  elements.css = function (newCss) {
    this.forEach(element => {
      Object.assign(element.style, newCss)
    })
    return this
  };
  elements.on = function (event, handler, options) {
    this.forEach(element => {
      element.addEventListener(event, handler, options)
    })
    return this
  };
  return elements;
}

function status(response) {
  if (response.status == 200) {
    return Promise.resolve(response)
  } else {
    let error = new Error(response.statusText);
    error.response = response;
    return Promise.reject(error)
  }
}

function json(response) {
  return response.json();
}
