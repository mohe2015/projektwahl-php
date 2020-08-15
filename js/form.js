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
import {} from './utils.js'

/**
 * Synchronize internal validation state with bootstrap validation
 * @param {HTMLInputElement} element
 */
export const onInvalid = (element) => {
  const parentElement = element.parentElement

  if (!parentElement) {
    throw new Error('Could not find parent of input')
  }

  const invalidFeedback = /** @type {HTMLElement | null} */ (parentElement.querySelector('.invalid-feedback'))

  if (!invalidFeedback) {
    throw new Error('Could not find feedback element')
  }

  invalidFeedback.innerText = element.validationMessage
}

/**
 * This callback is displayed as a global member.
 * @callback jsonCallback
 * @param {any} data
 */

/**
 *
 * @param {HTMLFormElement} form
 * @param {string} url
 * @param {jsonCallback} callback
 * @param {string[]} dontResetValidation
 */
export const setupForm = (router, form, url, callback, dontResetValidation) => {
  const alert = form.querySelector('.alert')

  for (const element of form.elements) {
    if (element.getAttribute("data-keep-disabled")) continue;
    element.addEventListener('invalid', event => {
      console.log('oninvalid')
      onInvalid(/** @type HTMLInputElement */ (event.target))
    })
  }

  // validate on load
  form.checkValidity()
  form.classList.add('was-validated')

  // on any input change
  form.addEventListener('input', event => {
    console.log("input")

    // reset server side validation
    for (const element of form.elements) {
      if (dontResetValidation.includes(element.name)) {
        continue;
      }
      if (element.getAttribute("data-keep-disabled")) continue;
      const element1 = /** @type HTMLInputElement */ (element)
      element1.setCustomValidity('')
    }
    // validate form
    form.checkValidity()
    form.classList.add('was-validated')
  })

  form.addEventListener('submit', async event => {
    event.preventDefault()
    console.log("submit")

    const formData = new FormData(form)

    // validate before submitting
    const valid = form.checkValidity()
    form.classList.add('was-validated')

    // prevent submitting when form is invalid
    if (!valid) {
      event.stopPropagation()
      return false
    }

    // prevent user from changing while the request is in progress
    for (const element of form.elements) {
      const element1 = /** @type HTMLInputElement */ (element)
      if (element.getAttribute("data-keep-disabled")) continue;
      element1.disabled = true
    }

    try {
      const response = await fetch(url, {
        method: 'POST',
        body: formData
      })
      if (response.ok) {
        const json = await response.json()

        for (const element of form.elements) {
          const element1 = /** @type HTMLInputElement */ (element)
          if (element.getAttribute("data-keep-disabled")) continue;
          element1.disabled = false
        }

        let params = new URLSearchParams(location.search);

        if (json.custom) {
          callback(json)
        } else if (json.errors) {
          // set server side validation results
          for (const element of form.elements) {
            const element1 = /** @type HTMLInputElement */ (element)
            if (element.getAttribute("data-keep-disabled")) continue;
            if (element1.name in json.errors) {
              element1.setCustomValidity(json.errors[element1.name])
            }
          }
          form.checkValidity()
          alert.classList.add('d-none')
        } else if (json.redirect) {
          let state;
          if (json.alert) {
            state = { alert: json.alert }
          } else {
            state = null
          }
          if (json.redirect_back) {
            router.navigate(json.redirect + "?redirect=" + window.location.href, state)
          } else {
            router.navigate(json.redirect, state)
          }
        } else if (json.alert) {
          alert.innerText = json.alert
          alert.classList.remove('d-none')
        } else if (params.has("redirect")) {
          let url = new URL(/** @type string */ (params.get("redirect")), window.location.origin)
          if (url.origin === window.location.origin) {
            router.navigate(url.href, null)
          } else {
            alert("BAD HACKER!!!")
          }
        }  
      } else {
        alert.innerText = 'Serverfehler: ' + response.status + ' ' + response.statusText
        alert.classList.remove('d-none')
      }
    } catch (error) {
      if (error instanceof TypeError) {
        console.log(error)
        alert.innerText = 'Prüfe deine Verbindung. Eventuell ist der Server auch offline. Details: ' + error.message
        alert.classList.remove('d-none')
      } else {
        console.log(error)
        alert.innerText = 'Unbekannter Fehler: ' + error
        alert.classList.remove('d-none')
      }
    } finally {
      for (const element of form.elements) {
        const element1 = /** @type HTMLInputElement */ (element)
        if (element.getAttribute("data-keep-disabled")) continue;
        element1.disabled = false
      }
      return false
    }
  })
}
