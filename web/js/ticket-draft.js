/**
 * ticket-draft.js
 * 
 * Prepares onchange stuff for the ticket draft form
 */

import ChangeTicketDraftFieldsHandler from "./modules/ticket-draft/ChangeTicketDraftFieldsHandler"

jQuery(() => {
    const onChangeModule = ChangeTicketDraftFieldsHandler()

    // prepare
    onChangeModule.loadEvents()
})