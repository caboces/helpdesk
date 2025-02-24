/**
 * ticket-create.js
 * 
 * Executes some stuff on page load
 */

import Spinner from "./modules/Spinner.js"
import AutofillTicketFields from "./modules/AutofillTicketFields.js"
import switchTabPane from "./modules/switchTabPane.js"
import PrimaryTechHandler from "./modules/ticket/PrimaryTechHandler.js"
import TicketModalHandler from "./modules/ticket/TicketModalHandler.js"

// id to the spinner element
const autofillSpinner = '#general-spinner'

jQuery(() => {

    // prepare autofill spinner
    const spinner = Spinner()

    // prepare autofill module
    const autofillModule = AutofillTicketFields()

    // prepare primary tech module
    const primaryTechHandler = PrimaryTechHandler('create')

    // prepare ticket modals
    const ticketModalHandler = TicketModalHandler()

    ticketModalHandler.loadEvents()
    primaryTechHandler.loadEvents()

    // see if we need to autofill the fields
    const ticketDraft = JSON.parse($('#hidden-ticket-draft-data').val())
    if (ticketDraft && Object.keys(ticketDraft).length != 0) {
        // load spinner and wait for autofill to complete
        spinner.awaitSpinner(autofillSpinner, autofillModule.autofill, ticketDraft)
    } else {
        console.info('No ticket draft detected - will not autofill fields')
    }

    // prepare autopane
    switchTabPane('#pills-tab', 'tabPane')

})