import {} from './utils.js'

/**
 * Synchronize internal validation state with bootstrap validation
 * @param {HTMLInputElement} element
 */
export const onInvalid = (element) => {
    let parentElement = element.parentElement;
  
    if (!parentElement) {
      throw new Error("Could not find parent of input");
    }
  
    let invalidFeedback = /** @type {HTMLElement | null} */ (parentElement.querySelector('.invalid-feedback'))
  
    if (!invalidFeedback) {
      throw new Error("Could not find feedback element")
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
 */
export const setupForm = (form, url, callback) => {
    let alert = form.querySelector('.alert')

    for (let element of form.elements) {
        element.addEventListener('invalid', event => {
          console.log("oninvalid")
          onInvalid(/** @type HTMLInputElement */ (event.target));
        })
      }

      // validate on load
      form.checkValidity()
      form.classList.add('was-validated')

      // on any input change
      form.addEventListener('input', event => {
          // reset server side validation
        for (let element of form.elements) {
          let element1 = /** @type HTMLInputElement */ (element)
          element1.setCustomValidity('')
        }
        // validate form
        form.checkValidity()
        form.classList.add('was-validated')
      })

      form.addEventListener('submit', async event => {
        event.preventDefault()

        let formData = new FormData(form)

        // validate before submitting
        let valid = form.checkValidity();
        form.classList.add('was-validated')

        // prevent submitting when form is invalid
        if (!valid) {
          event.stopPropagation()
          return;
        }

        // prevent user from changing while the request is in progress
        for (let element of form.elements) {
          let element1 = /** @type HTMLInputElement */ (element);
          element1.disabled = true;
        }

        try {
          const response = await fetch(url, {
            method: 'POST',
            body: formData,
          })
          if (response.ok) {
            const json = await response.json()
    
            for (let element of form.elements) {
              let element1 = /** @type HTMLInputElement */ (element);
              element1.disabled = false;
            }
    
            if (json.errors) {
                // set server side validation results
                for (let element of form.elements) {
                    let element1 = /** @type HTMLInputElement */ (element)
                    if (element1.name in json.errors) {
                        element1.setCustomValidity(json.errors[element1.name])
                    }
                }
                form.checkValidity();
            } else {
              callback(json)
            }

            alert.classList.add('d-none')
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
          for (let element of form.elements) {
            let element1 = /** @type HTMLInputElement */ (element);
            element1.disabled = false;
          }
        }
      })
}