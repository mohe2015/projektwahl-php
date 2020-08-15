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
// @ts-check

import { getElementById, getCookies } from './utils.js'
import { Route, Router } from './router.js'
import { setupForm } from './form.js'
import { router } from './index.js'

class RouteNotMatchingError extends Error {
  /**
   *
   * @param {string} message
   */
  constructor (message) {
    super(message)
    this.name = 'RouteNotMatchingError'
  }
}

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

  /**
   * @param {Router} router
   */
  render = async (router) => {
    for (const route of this.routes) {
      try {
        await route.render(router)
        return
      } catch (error) {
        if (error instanceof RouteNotMatchingError) {
          // just not matching
        } else {
          throw error
        }
      }
    }
    throw new RouteNotMatchingError('no matching route found')
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

  /**
   * @param {Router} router
   */
  render = async (router) => {
    if (this.path !== document.location.pathname) {
      throw new RouteNotMatchingError('path ' + document.location.pathname + ' does not match ' + this.path)
    }
    await this.route.render(router)
  }
}

class CookieRoute extends Route {
  /**
   * @type {Route}
   */
  route

  /**
   * @param {Route} route
   */
  constructor (route) {
    super()
    this.route = route
  }

   /**
   * @param {Router} router
   */
   render = async (router) => {
     if ('username' in getCookies()) {
       Array.from(document.getElementsByClassName('hide-logged-out')).forEach(element => element.classList.remove('d-none'))
     } else {
       Array.from(document.getElementsByClassName('hide-logged-out')).forEach(element => {
         element.classList.add('d-none')
       })
     }
     await this.route.render(router)
   }
}

let routesElement = getElementById('routes')

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

const updatePasswordRoute = new PathRoute(
  '/update-password',
  new class extends Route {
    render = async () => {
      const usernameInput = /** @type HTMLInputElement  */ (getElementById('update-password-username'))
      usernameInput.value = getCookies().username





      let oldPasswordField = /** @type HTMLInputElement */ (getElementById('update-password-old-password'))
      let newPassword = /** @type HTMLInputElement */ (getElementById('update-password-new-password'))
      let newPasswordRepeated = /** @type HTMLInputElement */ (getElementById('update-password-new-password-repeated'))
      
      let updatePasswordForm = /** @type HTMLFormElement */ (getElementById('update-password-form'))
      setupForm(updatePasswordForm, '/api/v1/update-password.php', json => {
        oldPasswordField.value = ""
        newPassword.value = ""
        newPasswordRepeated.value = ""
        router.navigate(json.redirect)
      }, ["new-password", "new-password-repeated"])
      
      /**
       * @param {Event} event
       */
      const onPasswordChange = event => {
        if (newPassword.value !== newPasswordRepeated.value) {
          newPasswordRepeated.setCustomValidity("Passwörter stimmen nicht überein")
        } else {
          newPasswordRepeated.setCustomValidity('')
        }
        // validate form
        updatePasswordForm.checkValidity()
        updatePasswordForm.classList.add('was-validated')
      }
      
      newPassword.addEventListener('input', onPasswordChange)
      newPasswordRepeated.addEventListener('input', onPasswordChange)











      Array.from(getElementById('routes').children).forEach(child => child.classList.add('d-none'))
      getElementById('route-update-password').classList.remove('d-none')
    }
  }()
)


const loginRoute = new PathRoute(
  '/login',
  new class extends Route {
    /**
     * @param {Router} router
     */
    render = async (router) => {
      var clone = /** @type DocumentFragment */ (/** @type HTMLTemplateElement */ (getElementById('route-login')).content.cloneNode(true));

      let loginPasswordField = /** @type HTMLInputElement */ (clone.getElementById('login-password'))
      let loginForm = /** @type HTMLFormElement */ (clone.getElementById('login-form'))

      setupForm(loginForm, '/api/v1/login.php', json => {
        if (json.redirect === "/update-password") {
          oldPasswordField.value = loginPasswordField.value
        }
        loginPasswordField.value = ""
        router.navigate(json.redirect)
      }, [])

      routesElement.children[0].replaceWith(clone)
    }
  }()
)

const notFoundRoute = new class extends Route {
  render = async () => {
    Array.from(getElementById('routes').children).forEach(child => child.classList.add('d-none'))
    getElementById('route-notfound').classList.remove('d-none')
  }
}()

export const rootRoute = new CookieRoute(new Routes([indexRoute, setupRoute, loginRoute, updatePasswordRoute, notFoundRoute]))
