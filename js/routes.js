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

/**
 * @type import("./router").Route
 */
const setupRoute = {
  path: '/setup',
  render: async () => {
    const response = await fetch('/api/v1/setup.php', {
      method: 'POST'
    })
    if (response.ok) {
      const html = await response.text()

      let tab = getElementById('route-setup');
      tab.innerHTML = html

      Array.from(getElementById('routes').children).forEach(child => {
        child.classList.add("d-none")
      })
      tab.classList.remove("d-none")
      
    } else {
      alert('Serverfehler: ' + response.status + ' ' + response.statusText)
    }
  }
}

export const routes = [
  setupRoute
]