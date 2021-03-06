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

/**
 * @function
 * @template {HTMLElement} E
 * @param {string} id
 * @returns {E}
 */
export const getElementById = (id) => {
  const element = /** @type {E | null} */ (document.getElementById(id))
  if (element) {
    return element
  } else {
    throw new Error('getElementById could not find element with id: ' + id)
  }
}

export const getCookies = () => {
  return Object.fromEntries(document.cookie.split(/; */).map(c => {
    const [key, v] = c.split('=', 2)
    return [key, decodeURIComponent(v)]
  }))
}
