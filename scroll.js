function restoreScroll() {
  window.scrollTo(0, localStorage.getItem(`scroll${location.pathname}`));
}

function updateScroll() {
  localStorage.setItem(`scroll${location.pathname}`, window.scrollY);
}

window.addEventListener('load', function() {
  restoreScroll();
  window.addEventListener('scroll', updateScroll);
});
