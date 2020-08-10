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

/**
 * @typedef {Object} Route
 * @property {string} path
 * @property {function(): Promise<void>} render
 */

export class Router {
  /**
     * @param {Route[]} routes
     */
  constructor (routes) {
    this.routes = routes

    window.addEventListener('popstate', (event) => {
      this.render()
    })

    document.addEventListener('click', (event) => {
      console.log(event)

      if (event.target instanceof Element) {
        let a = event.target.closest("a")

        if (a) {
          event.preventDefault()
          this.navigate(a.href)
        }
      }
    }, {
      capture: true,

    })
  }

    render = async () => {
      const matchedRoute = this.routes.find((route) => route.path === document.location.pathname)
      if (matchedRoute) {
        await matchedRoute.render()
      } else {
        // 404
        alert('404 Not Found')
      }
    }

    /**
     * @param {string} url
     */
    navigate = (url) => {
      history.pushState(null, document.title, url)
      this.render()
    }
}
