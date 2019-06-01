function onChoiceSubmit(event) {
  event.preventDefault();

  let oldRank = this.parentNode.querySelector('button[type="submit"]:disabled').getAttribute('data-rank');
  let newRank = this.querySelector('button[type="submit"]').getAttribute('data-rank');

  // disable buttons for updating over network
  this.parentNode.querySelectorAll('button[type="submit"]').forEach(e => e.setAttribute('disabled', null));

  fetch("/election.php", {
    method: 'POST',
    body: new FormData(this),
    redirect: "error"
  }).then((data) => {
    //console.log(data);
    // reenable buttons (except the newly selected one)
    [...this.parentNode.querySelectorAll('button[type="submit"]')]
      .filter(x => x.getAttribute('data-rank') != newRank)
      .forEach(e => e.removeAttribute('disabled'));
    // TODO color duplicate votes red
    this.parentNode.parentNode.setAttribute('data-rank', newRank);
  },
  (error) => {
    alert(error); // TODO redirect to login if signed out
    // reenable buttons (except the old selected one)
    [...this.parentNode.querySelectorAll('button[type="submit"]')]
      .filter(x => x.getAttribute('data-rank') != oldRank)
      .forEach(e => e.removeAttribute('disabled'));
  });

  return false;
}

// listen on all forms
document.querySelectorAll(".choice-form").forEach(e => e.addEventListener("submit", onChoiceSubmit));

function scrollToTop(event) {
  console.log(event);
  window.scroll({top: 0, left: 0, behavior: 'smooth' });

  let result = [...document.querySelectorAll('tr')];
  result.sort(function(a, b) {
    a = parseInt(a.getAttribute('data-rank'));
    b = parseInt(b.getAttribute('data-rank'));
    a = a == 0 ? 100 : a;
    b = b == 0 ? 100 : b;
    return a-b;
  });
  console.log(result);

  let container = document.querySelector('tbody');
  while (container.firstChild) {
    container.firstChild.remove();
  }

  result.forEach(element => container.appendChild(element));
}

document.querySelector("#scroll").addEventListener("click", scrollToTop);
