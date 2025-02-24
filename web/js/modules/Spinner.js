/**
 * 
 * Spinner.js
 * 
 * Shows/hides spinners while async work is being completed
 * 
 */

/**
 * Automatically turn on and off spinner icons while an asynchronous function executes
 */
const Spinner = (() => {
    const module = {
        init: function() {
            // disable all spinners on page load in general
            $('.spinner-border, .spinner-grow, .spinner').hide()
        },
        /**
         * Call an async function and show a spinner while waiting for its result
         * @param {*} spinner DOM ID to spinner element
         * @param {*} promisedFunction The async function to execute
         * @param  {...any} params The parameters to the async function, can be undefined
         */
        awaitSpinner: async function (spinner, promisedFunction, ...params) {
            $(spinner).show()
            await promisedFunction(...params)
            $(spinner).hide()
        }
    }

    jQuery(() => module.init())

    return module

})

export default Spinner