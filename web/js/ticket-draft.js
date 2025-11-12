/**
 * ticket-draft.js
 * 
 * Prepares onchange stuff for the ticket draft form
 */

import ChangeTicketDraftFieldsHandler from "./modules/ticket-draft/ChangeTicketDraftFieldsHandler.js"

jQuery(() => {
    const onChangeModule = ChangeTicketDraftFieldsHandler()

    // prepare
    onChangeModule.loadEvents()
})