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
   * @param {any|null} state
   */
  render = async (router, state) => {
    for (const route of this.routes) {
      try {
        await route.render(router, state)
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
   * @param {any|null} state
   */
  render = async (router, state) => {
    if (this.path !== document.location.pathname) {
      throw new RouteNotMatchingError('path ' + document.location.pathname + ' does not match ' + this.path)
    }
    await this.route.render(router, state)
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
   * @param {any|null} state
   */
   render = async (router, state) => {
     if ('username' in getCookies()) {
       Array.from(document.getElementsByClassName('hide-logged-out')).forEach(element => element.classList.remove('d-none'))
     } else {
       Array.from(document.getElementsByClassName('hide-logged-out')).forEach(element => {
         element.classList.add('d-none')
       })
     }
     await this.route.render(router, state)
   }
}

let routesElement = getElementById('routes')

const setupRoute = new PathRoute(
  '/setup',
  new class extends Route {

    /**
     * @param {Router} router
     * @param {any|null} state
     */
    render = async (router, state) => {
      var clone = /** @type DocumentFragment */ (/** @type HTMLTemplateElement */ (getElementById('route-loading')).content.cloneNode(true));
      routesElement.children[0].replaceWith(clone)
      
      const response = await fetch('/api/v1/setup.php', {
        method: 'POST'
      })
      if (response.ok) {
        const html = await response.text()

        var clone = /** @type DocumentFragment */ (/** @type HTMLTemplateElement */ (getElementById('route-setup')).content.cloneNode(true));
        /** @type Element */ (clone.firstElementChild).innerHTML = html
        routesElement.children[0].replaceWith(clone)
      } else {
        alert('Serverfehler: ' + response.status + ' ' + response.statusText)
      }
    }
  }()
)

const indexRoute = new PathRoute(
  '/',
  new class extends Route {

    /**
     * @param {Router} router
     * @param {any|null} state
     */
    render = async (router, state) => {
      // TODO FIXME fetch election status

      var clone = /** @type DocumentFragment */ (/** @type HTMLTemplateElement */ (getElementById('route-index')).content.cloneNode(true));
      routesElement.children[0].replaceWith(clone)
    }
  }()
)

const projectsRoute = new PathRoute(
  '/projects',
  new class extends Route {

    /**
     * @param {Router} router
     * @param {any|null} state
     */
    render = async (router, state) => {
      var clone = /** @type DocumentFragment */ (/** @type HTMLTemplateElement */ (getElementById('route-projects')).content.cloneNode(true));
      routesElement.children[0].replaceWith(clone)
    }
  }()
)

const usersRoute = new PathRoute(
  '/users',
  new class extends Route {

    /**
     * @param {Router} router
     * @param {any|null} state
     */
    render = async (router, state) => {
      var clone = /** @type DocumentFragment */ (/** @type HTMLTemplateElement */ (getElementById('route-users')).content.cloneNode(true));
      routesElement.children[0].replaceWith(clone)
    }
  }()
)

const updatePasswordRoute = new PathRoute(
  '/update-password',
  new class extends Route {

    /**
     * @param {Router} router
     * @param {any|null} state
     */
    render = async (router, state) => {
      console.log("render")
      var clone = /** @type DocumentFragment */ (/** @type HTMLTemplateElement */ (getElementById('route-update-password')).content.cloneNode(true));
      
      const usernameInput = /** @type HTMLInputElement  */ (clone.getElementById('update-password-username'))
      usernameInput.value = getCookies().username

      let oldPasswordField = /** @type HTMLInputElement */ (clone.getElementById('update-password-old-password'))
      let newPassword = /** @type HTMLInputElement */ (clone.getElementById('update-password-new-password'))
      let newPasswordRepeated = /** @type HTMLInputElement */ (clone.getElementById('update-password-new-password-repeated'))
      
      if (state && "oldPassword" in state) {
        oldPasswordField.value = state.oldPassword
      }

      let updatePasswordForm = /** @type HTMLFormElement */ (clone.getElementById('update-password-form'))
      setupForm(router, updatePasswordForm, '/api/v1/update-password.php', json => {
        oldPasswordField.value = ""
        newPassword.value = ""
        newPasswordRepeated.value = ""
        router.navigate(json.redirect, null)
      }, ["new-password", "new-password-repeated"])
      
      /**
       * @param {Event} event
       */
      const onPasswordChange = event => {
        console.log("password-input")
        
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

      routesElement.children[0].replaceWith(clone)
    }
  }()
)


const loginRoute = new PathRoute(
  '/login',
  new class extends Route {
    /**
     * @param {Router} router
     * @param {any|null} state
     */
    render = async (router, state) => {
      var clone = /** @type DocumentFragment */ (/** @type HTMLTemplateElement */ (getElementById('route-login')).content.cloneNode(true));
      let loginPasswordField = /** @type HTMLInputElement */ (clone.getElementById('login-password'))
      let loginForm = /** @type HTMLFormElement */ (clone.getElementById('login-form'))

      setupForm(router, loginForm, '/api/v1/login.php', json => {
        if (json.redirect === "/update-password") {
          router.navigate(json.redirect, {
            "oldPassword": loginPasswordField.value,
          })
        }
      }, [])

      routesElement.children[0].replaceWith(clone)
    }
  }()
)

const addProjectRoute = new PathRoute(
  '/add-project',
  new class extends Route {
    /**
     * @param {Router} router
     * @param {any|null} state
     */
    render = async (router, state) => {
      var clone = /** @type DocumentFragment */ (/** @type HTMLTemplateElement */ (getElementById('route-add-project')).content.cloneNode(true));
      let addProjectForm = /** @type HTMLFormElement */ (clone.getElementById('add-project-form'))

      setupForm(router, addProjectForm, '/api/v1/add-project.php', json => {
        router.navigate(json.redirect, null)
      }, [])

      routesElement.children[0].replaceWith(clone)
    }
  }()
)

const addUserRoute = new PathRoute(
  '/add-user',
  new class extends Route {
    /**
     * @param {Router} router
     * @param {any|null} state
     */
    render = async (router, state) => {
      var clone = /** @type DocumentFragment */ (/** @type HTMLTemplateElement */ (getElementById('route-add-user')).content.cloneNode(true));
      let addUserForm = /** @type HTMLFormElement */ (clone.getElementById('add-user-form'))

      setupForm(router, addUserForm, '/api/v1/add-user.php', json => {
        router.navigate(json.redirect, null)
      }, [])

      routesElement.children[0].replaceWith(clone)
    }
  }()
)

const notFoundRoute = new class extends Route {

  /**
   * @param {Router} router
   * @param {any|null} state
   */
  render = async (router, state) => {
    var clone = /** @type DocumentFragment */ (/** @type HTMLTemplateElement */ (getElementById('route-notfound')).content.cloneNode(true));
    routesElement.children[0].replaceWith(clone)
  }
}()

export const rootRoute = new CookieRoute(new Routes([indexRoute, setupRoute, loginRoute, updatePasswordRoute, projectsRoute, addProjectRoute, usersRoute, addUserRoute, notFoundRoute]))
