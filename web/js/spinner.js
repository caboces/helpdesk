
const spinnerModule = (() => {
    const module = {
        init: function() {
            // disable all spinners on page load
            $('.spinner-border, .spinner-grow').hide()
        },
        awaitSpinner: async function (spinner, promisedFunction, ...params) {
            $(spinner).show()
            await promisedFunction(...params)
            $(spinner).hide()
        }
    }

    jQuery(() => {
        module.init()
    })

    return module

})

export default spinnerModule