/**
 * ticket-create.js
 * 
 * Page: /ticket/create
 */

jQuery(() => {

    /**
     * autofillTicketFormModule
     * 
     * autofills the ticket form with fields from the ticket draft
     */
    const autofillTicketFormModule = {
        autofill: function () {
            new Promise((resolve, reject) => {
                // check if the hidden json field loaded correctly.
                const tmp = JSON.parse($('#hidden-ticket-draft-data').val())
                if (tmp && Object.keys(tmp).length != 0) {
                    // get the json version of the ticket draft. It is stored as a json string in the hidden input
                    resolve(tmp)
                    return
                }
                reject()
            }).then(async (ticketDraft) => {
                // Autofill the the ticket creation fields with the equivalent ticket draft fields
                // Customer Type. this is a radio button so to set the checked value requires a special method
                if (ticketDraft.customer_type_id) {
                    $(`input:radio[name='Ticket[customer_type_id]']`).filter(`[value='${ticketDraft.customer_type_id}']`).attr('checked', true)
                    // trigger the ticket customer_type_id change to change what <select>'s shown next [Division, Department], [District, Building]
                    $('#ticket-customer_type_id').change()
                }
                // Division id
                if (ticketDraft.division_id) {
                    $('#ticket-division_id').val(ticketDraft.division_id)
                }
                // Deparment id
                if (ticketDraft.department_id) {
                    $('#ticket-department_id').val(ticketDraft.department_id)
                    // need to await... populate the department_building select with the correct department buildings based on the division selection

                    $.when($('#ticket-department_id').change()).then(function() {
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
                    $.when($('#ticket-district_id').change()).then(function() {
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
                if (ticketDraft.job_type_id) {
                    // Job type id. Trigger the job_type_id change function to show the correct job_category set
                    $('#ticket-job_type_id').val(ticketDraft.job_type_id)
                    // need to await... populate the job_cateogry select with the correct job categories based on the job type selection
                    $.when($('#ticket-job_type_id').change()).then(function() {
                        // Job category id
                        if (ticketDraft.job_category_id) {
                            $('#ticket-job_category_id').val(ticketDraft.job_category_id)
                        }
                    })
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
            }).catch(() => {
                console.info('No ticket draft to autofill')
            })
        }
    }

    autofillTicketFormModule.autofill()

})