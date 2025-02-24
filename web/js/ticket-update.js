/**
 * ticket-create.js
 * 
 * Executes some stuff on page load
 */

import switchTabPane from "./modules/switchTabPane.js"
import PrimaryTechHandler from "./modules/ticket/PrimaryTechHandler.js"
import TicketModalHandler from "./modules/ticket/TicketModalHandler.js"

jQuery(() => {

    // prepare primary tech module
    const primaryTechHandler = PrimaryTechHandler('update')

    // prepare ticket modals
    const ticketModalHandler = TicketModalHandler()

    ticketModalHandler.loadEvents()
    primaryTechHandler.loadEvents()

    // prepare autopane
    switchTabPane('pills-tab', 'tabPane')

})