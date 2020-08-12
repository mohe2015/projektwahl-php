/*
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
// @ts-check

import { getElementById } from './utils.js'
import { Route } from './router.js'

class Routes extends Route {
  /**
   * @type {Route[]}
   */
  routes;

  /**
   * @param {Route[]} routes
   */
  constructor (routes) {
    super()
    this.routes = routes
  }

  render = async () => {
    for (const route of this.routes) {
      try {
        await route.render()
        return
      } catch (e) {

      }
    }
    throw new Error('no matching route found')
  }
}

class PathRoute extends Route {
  /**
   * @type {string}
   */
  path;

  /**
   * @type {Route}
   */
  route;

  /**
   * @param {string} path
   * @param {Route} route
   */
  constructor (path, route) {
    super()
    this.path = path
    this.route = route
  }

  render = async () => {
    if (this.path !== document.location.pathname) {
      throw new Error('path ' + document.location.pathname + ' does not match ' + this.path)
    }
    await this.route.render()
  }
}

/**
 * @type import("./router").Route
 */
const setupRoute = new PathRoute(
  '/setup',
  new class extends Route {
    render = async () => {
      const response = await fetch('/api/v1/setup.php', {
        method: 'POST'
      })
      if (response.ok) {
        const html = await response.text()

        const tab = getElementById('route-setup')
        tab.innerHTML = html

        Array.from(getElementById('routes').children).forEach(child => child.classList.add('d-none'))
        tab.classList.remove('d-none')
      } else {
        alert('Serverfehler: ' + response.status + ' ' + response.statusText)
      }
    }
  }()
)

const indexRoute = new PathRoute(
  '/',
  new class extends Route {
    render = async () => {
      // TODO FIXME fetch election status

      Array.from(getElementById('routes').children).forEach(child => child.classList.add('d-none'))
      getElementById('route-index').classList.remove('d-none')
    }
  }()
)

const loginRoute = new PathRoute(
  '/login',
  new class extends Route {

    /**
     * @param {HTMLInputElement} element
     */
    onInvalid = (element) => {
        let parentElement = element.parentElement;

        if (!parentElement) {
          throw new Error("Could not find parent of input");
        }

        let invalidFeedback = /** @type {HTMLElement | null} */ (parentElement.querySelector('.invalid-feedback'))

        if (!invalidFeedback) {
          throw new Error("Could not find feedback element")
        }

        console.log("jo: " + element.validationMessage)
        invalidFeedback.innerText = element.validationMessage
    }
    
    /**
     * @param {HTMLFormElement} form
     * @param {FormData} formData
     */
    asyncSubmit = async (form, formData) => {
      try {
        const response = await fetch('/api/v1/login.php', {
          method: 'POST',
          body: formData,
        })
        if (response.ok) {
          const json = await response.json()
  
          // THIS FIXED IT!!!
          for (let element of form.elements) {
            let element1 = /** @type HTMLInputElement */ (element);
            element1.disabled = false;
          }

          console.log(json)

          if (json.errors) {
            if (json.errors.username) {
              let usernameField = /** @type HTMLInputElement */ (getElementById("login-username"))
              usernameField.setCustomValidity(json.errors.username)
              
              form.checkValidity();
            }

            if (json.errors.password) {
              let passwordField = /** @type HTMLInputElement */ (getElementById("login-password"))
              passwordField.setCustomValidity(json.errors.password)
              console.log(json.errors.password)

              form.checkValidity();
            }
          }
        } else {
          alert('Serverfehler: ' + response.status + ' ' + response.statusText)
        }

      } finally {
        for (let element of form.elements) {
          let element1 = /** @type HTMLInputElement */ (element);
          element1.disabled = false;
        }
      }
    }

    render = async () => {
      Array.from(getElementById('routes').children).forEach(child => child.classList.add('d-none'))
      getElementById('route-login').classList.remove('d-none')

      /** @type HTMLFormElement */
      const form = getElementById('login-form')

      for (let element of form.elements) {
        element.addEventListener('invalid', event => {
          console.log("oninvalid")
          this.onInvalid(/** @type HTMLInputElement */ (event.target));
        })

      }

      form.addEventListener('input', event => {
        console.log("form input")
        for (let element of form.elements) {
          let element1 = /** @type HTMLInputElement */ (element)
          element1.setCustomValidity('')
        }
        form.checkValidity()
      })

      // TODO FIXME this wil create multiple listeners when opening the page multiple times
      form.addEventListener('submit', event => {
        console.log("onsubmit")
        event.preventDefault()

        let formData = new FormData(form)

        let valid = form.checkValidity();

        form.classList.add('was-validated')

        if (!valid) {
          event.stopPropagation()
          return;
        }

        for (let element of form.elements) {
          let element1 = /** @type HTMLInputElement */ (element);
          element1.disabled = true;
        }

        this.asyncSubmit(form, formData)
      })
    }
  }()
)

export const rootRoute = new Routes([indexRoute, setupRoute, loginRoute])
