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

import { Collapse } from './bootstrap.esm.js'
import { getElementById } from './utils.js'

export class Route {
  /**
   * @param {Router} router
   * @returns {Promise<void>}
   */
  render = (router) => {
    throw new Error('Route is abstract.')
  }
}

export class Router {
  /**
   * @param {Route} route
   */
  constructor (route) {
    this.route = route
    this.navbar = new Collapse(getElementById('navbarSupportedContent'), {
      toggle: false
    })

    window.addEventListener('popstate', async (event) => {
      await this.route.render(this)
    })

    document.addEventListener('click', (event) => {
      if (event.target instanceof Element) {
        const a = event.target.closest('a')

        if (a) {
          if (a.hostname === location.hostname) {
            event.preventDefault()
            // @ts-expect-error
            this.navbar.hide()
            this.navigate(a.href)
          }
        }
      }
    }, {
      capture: true
    })
  }

  /**
   * @param {string} url
   */
  navigate = async (url) => {
    history.pushState(null, document.title, url)
    await this.route.render(this)
  }
}
