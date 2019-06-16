function restore() {
  window.scrollTo(0, localStorage.getItem(`scroll${location.pathname}`));
}

function update() {
  localStorage.setItem(`scroll${location.pathname}`, window.scrollY);
}

window.addEventListener('load', function() {
  restore();
  window.addEventListener('scroll', update);
});
