function updateOrderCount() {
  var snackbar = document.querySelector('#snackbar');
  var valid = true;
  for (var i = 1; i <= 5; i++) {
    if (order_count[i] != 1) {
      valid = false;
      break;
    }
  }
  if (valid) {
    snackbar.classList.add('background-success');
    snackbar.innerHTML = "Gültig gewählt - Du kannst Dich nun <a href=\"logout.php\">abmelden</a>";
  } else {
    snackbar.classList.remove('background-success');
    snackbar.innerHTML = "<span class=\"failure\">Ungültig gewählt</span> - ";
    for (var i = 1; i <= 5; i++) {
      var span = document.createElement("span");
      span.innerHTML = order_count[i] + "&times;" + i + ".";
      span.classList.add(order_count[i] == 1 ? "success" : "failure");
      snackbar.appendChild(span);
      if (i != 5) {
        snackbar.appendChild(document.createTextNode(" | "));
      }
    }
  }
}

function is_sorted(arr, func) {
    var len = arr.length - 1;
    for (var i = 0; i < len; ++i) {
        if(func(arr[i], arr[i+1]) > 0) {
            return false;
        }
    }
    return true;
}

function onChoiceSubmit(event) {
  event.preventDefault();

  var oldRank = this.parentNode.querySelector('button[type="submit"]:disabled').getAttribute('data-rank');
  var newRank = this.querySelector('button[type="submit"]').getAttribute('data-rank');

  // disable buttons for updating over network
  this.parentNode.querySelectorAll('button[type="submit"]').forEach(function (e) { e.setAttribute('disabled', null) });

  var that = this;

  fetch("election.php", {
    method: 'POST',
    body: new FormData(this),
    redirect: "error"
  })
  .then(status)
  .then(function (data) {
    console.log(data);
    // reenable buttons (except the newly selected one)
    [...that.parentNode.querySelectorAll('button[type="submit"]')]
      .filter(function (x) { return x.getAttribute('data-rank') != newRank})
      .forEach(function (e) { e.removeAttribute('disabled') });
    // TODO color duplicate votes red
    that.parentNode.parentNode.setAttribute('data-rank', newRank);

    order_count[oldRank]--;
    order_count[newRank]++;
    updateOrderCount();

    var result = [...document.querySelectorAll('tr[data-rank]')];
    if (is_sorted(result, sortProjectRanks)) {
      document.querySelector('.scrolltop').classList.remove('show');
    } else {
      document.querySelector('.scrolltop').classList.add('show');
    }

    document.querySelectorAll('tr[data-rank="' + newRank + '"] button')
    .forEach(function (element) {
      element.classList.remove('background-failure');
      element.classList.remove('background-success');
      if (newRank != 0) {
        element.classList.add(order_count[newRank] == 1 ? 'background-success' : 'background-failure');
      }
    });
      document.querySelectorAll('tr[data-rank="' + oldRank + '"] button')
      .forEach(function (element) {
        element.classList.remove('background-failure');
        element.classList.remove('background-success');
        if (oldRank != 0) {
          element.classList.add(order_count[oldRank] == 1 ? 'background-success' : 'background-failure');
        }
      });
  })
  .catch(function (error) {
    // reenable buttons (except the old selected one)
    [...that.parentNode.querySelectorAll('button[type="submit"]')]
      .filter(function (x) { return x.getAttribute('data-rank') != oldRank })
      .forEach(function (e) { e.removeAttribute('disabled') });
    if (error.response) {
      error.response.text().then(function (data) {
        alert(data);
      })
      .catch(function (error1) {
        alert(error);
      });
    } else {
      alert("Nicht angemeldet!"); // TODO redirect to login if signed out
    }
  });

  return false;
}

// listen on all forms
document.querySelectorAll(".choice-form").forEach(function (e) { e.addEventListener("submit", onChoiceSubmit) });

function sortProjectRanks(a, b) {
  a = parseInt(a.getAttribute('data-rank'));
  b = parseInt(b.getAttribute('data-rank'));
  a = a == 0 ? 100 : a;
  b = b == 0 ? 100 : b;
  a = isNaN(a) ? 100 : a;
  b = isNaN(b) ? 100 : b;
  console.log(a);
  console.log(b);
  return a-b;
}

function scrollToTop(event) {
  window.scroll({top: 0, left: 0, behavior: 'smooth' });

  // get all table rows
  var result = [...document.querySelectorAll('tr[data-rank]')];

  // store old positions
  result.forEach(function (element) { element.oldBoundingBox = element.getBoundingClientRect()});

  // reorder them
  result.sort(sortProjectRanks);

  var container = document.querySelector('tbody');
  result.forEach(function (element) { element.remove() });
  result.forEach(function (element) { container.appendChild(element) });

  // store new positions
  result.forEach(function (element) {
    element.newBoundingBox = element.getBoundingClientRect()
    const deltaY = element.oldBoundingBox.top - element.newBoundingBox.top;

    requestAnimationFrame(function () {
      // Before the DOM paints, Invert it to its old position
      element.style.transform = 'translate(0px, ' + deltaY + 'px)';
      // Ensure it inverts it immediately
      element.style.transition = 'transform 0s';

      requestAnimationFrame(function () {
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


var result = [...document.querySelectorAll('tr[data-rank]')];
var order_count = [0, 0, 0, 0, 0, 0];
result.forEach(function (element) {
  order_count[element.getAttribute('data-rank')]++;
});
console.log(order_count);
