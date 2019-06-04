function status(response) {
  if (response.status >= 200 && response.status < 300) {
    return Promise.resolve(response)
  } else {
    return Promise.reject(new Error(response.statusText))
  }
}

function updateOrderCount() {
  var snackbar = document.querySelector('#snackbar');
  let valid = true;
  for (let i = 1; i <= 5; i++) {
    if (order_count[i] != 1) {
      valid = false;
      break;
    }
  }
  if (valid) {
    snackbar.innerHTML = "<span class=\"success\">Gültig</span> - ";
  } else {
    snackbar.innerHTML = "<span class=\"failure\">Ungültig</span> - ";
  }
  for (let i = 1; i <= 5; i++) {
    let span = document.createElement("span");
    span.innerHTML = order_count[i] + "&times;" + i + ".";
    span.classList.add(order_count[i] == 1 ? "success" : "failure");
    snackbar.appendChild(span);
    if (i != 5) {
      snackbar.appendChild(document.createTextNode(" | "));
    }
  }
}

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
  })
  .then(status)
  .then((data) => {
    console.log(data);
    // reenable buttons (except the newly selected one)
    [...this.parentNode.querySelectorAll('button[type="submit"]')]
      .filter(x => x.getAttribute('data-rank') != newRank)
      .forEach(e => e.removeAttribute('disabled'));
    // TODO color duplicate votes red
    this.parentNode.parentNode.setAttribute('data-rank', newRank);

    order_count[oldRank]--;
    order_count[newRank]++;
    updateOrderCount();
    document.querySelector('.scrolltop').classList.add('show');
    if (order_count[newRank] == 1) {
      var element = this.querySelector('button[type="submit"]');
      element.classList.remove('background-failure');
      element.classList.add('background-success');
    } else {
      var element = this.querySelector('button[type="submit"]');
      element.classList.remove('background-success');
      element.classList.add('background-failure');
    }
  })
  .catch((error) => {
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
  window.scroll({top: 0, left: 0, behavior: 'smooth' });

  // get all table rows
  let result = [...document.querySelectorAll('tr[data-rank]')];

  // store old positions
  result.forEach(element => element.oldBoundingBox = element.getBoundingClientRect());

  // reorder them
  result.sort(function(a, b) {
    a = parseInt(a.getAttribute('data-rank'));
    b = parseInt(b.getAttribute('data-rank'));
    a = a == 0 ? 100 : a;
    b = b == 0 ? 100 : b;
    a = isNaN(a) ? 100 : a;
    b = isNaN(b) ? 100 : b;
    console.log(a);
    console.log(b);
    return a-b;
  });

  let container = document.querySelector('tbody');
  result.forEach(element => element.remove());
  result.forEach(element => container.appendChild(element));

  // store new positions
  result.forEach(element => {
    element.newBoundingBox = element.getBoundingClientRect()
    const deltaY = element.oldBoundingBox.top - element.newBoundingBox.top;

    requestAnimationFrame( () => {
      // Before the DOM paints, Invert it to its old position
      element.style.transform = `translate(0px, ${deltaY}px)`;
      // Ensure it inverts it immediately
      element.style.transition = 'transform 0s';

      requestAnimationFrame( () => {
        // In order to get the animation to play, we'll need to wait for
        // the 'invert' animation frame to finish, so that its inverted
        // position has propagated to the DOM.
        //
        // Then, we just remove the transform, reverting it to its natural
        // state, and apply a transition so it does so smoothly.
        element.style.transform  = '';
        element.style.transition = 'transform 500ms';
      });
    });
  });

  document.querySelector('.scrolltop').classList.remove('show');
}

document.querySelector("#scroll").addEventListener("click", scrollToTop);


let result = [...document.querySelectorAll('tr[data-rank]')];
var order_count = [0, 0, 0, 0, 0, 0];
result.forEach(element => {
  order_count[element.getAttribute('data-rank')]++;
});
console.log(order_count);
