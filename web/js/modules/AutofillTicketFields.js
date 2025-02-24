/**
 * AutofillTicketFields.js
 * 
 * Handles ticket field autofill
 * 
 */

import ChangeTicketFieldsHandler from "./ticket/ChangeTicketFieldsHandler.js"

/**
 * Handle ticket autofill when given a ticketDraft object
 */
const AutofillTicketFields = (() => {

    // reference to the onChangeTicketFieldsModule so this module can call the changes to the fields as it manually sets the values
    const onChangeModule = ChangeTicketFieldsHandler()

    const module = {
        autofill: async function (ticketDraft) {
            // Autofill the the ticket creation fields with the equivalent ticket draft fields
            // Customer Type. this is a radio button so to set the checked value requires a special method
            if (ticketDraft.customer_type_id) {
                $(`input:radio[name='Ticket[customer_type_id]']`)
                    .filter(`[value='${ticketDraft.customer_type_id}']`)
                    .attr('checked', true)
                // trigger the ticket customer_type_id change to change what <select>'s shown next [Division, Department], [District, Building]
                onChangeModule.ticketCustomerTypeIdOnChange()
            }
            // Division id
            if (ticketDraft.division_id) {
                $('#ticket-division_id').val(ticketDraft.division_id)
            }
            // Deparment id
            if (ticketDraft.department_id) {
                $('#ticket-department_id').val(ticketDraft.department_id)
                // need to await... populate the department_building select with the correct department buildings based on the division selection
                $.when(onChangeModule.ticketDepartmentIdOnChange()).then(function() {
                    // Department building id
                    if (ticketDraft.department_building_id) {
                        $('#ticket-department_building_id').val(ticketDraft.department_building_id)
                    }
                })
            }
            // District id
            if (ticketDraft.district_id) {
                $('#ticket-district_id').val(ticketDraft.district_id)
                // need to await... populate the district_building select with the correct district buildings based on the district selection
                $.when(onChangeModule.ticketDistrictIdOnChange()).then(function() {
                    if (ticketDraft.district_building_id) {
                        $('#ticket-district_building_id').val(ticketDraft.district_building_id)
                    }
                })
            }
            // Requestor (e vs o spelling mistake, oh well.)
            if (ticketDraft.requestor) {
                $('#ticket-requester').val(ticketDraft.requestor)
            }
            // Location
            if (ticketDraft.location) {
                $('#ticket-location').val(ticketDraft.location)
            }
            // Summary
            if (ticketDraft.summary) {
                $('#ticket-summary').val(ticketDraft.summary)
            }
            // Description
            if (ticketDraft.description) {
                $('#ticket-description').val(ticketDraft.description)
            }
            // Email
            if (ticketDraft.email) {
                $('#ticket-requester_email').val(ticketDraft.email)
            }
            // Phone
            if (ticketDraft.phone) {
                $('#ticket-requester_phone').val(ticketDraft.phone)
            }
        }
    }

    return module


})

export default AutofillTicketFields