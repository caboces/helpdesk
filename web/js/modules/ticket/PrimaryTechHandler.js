/**
 * 
 * PrimaryTechHandler.js
 * 
 * handles what happens when different techs are selected and what should be shown in the primary tech select options
 * 
 */

import getUrlParameter from '../getUrlParameter.js';
import Spinner from '../Spinner.js'
/**
 * Primary Tech Module
 * 
 * handles what happens when different techs are selected and what should be shown in the primary tech select options
 * 
 * `ticketMethod` is either 'create' or 'update'
 * 
 */
const PrimaryTechHandler = ((ticketMethod) => {

    // create spinner module
    const spinner = Spinner()

    const module = {
        spinners: {
            techs: '#spinner-technicians',
        },
        errorDialog: $('#error-technician'),
        loadEvents: function() {
            $('#error-technician').hide()
            $('#ticket-users').on('select2:unselecting', (event) => {
                this.removeTechFromTechField(event)
            });
            $('#ticket-primary_tech_id').on('click', function () { 
                module.onPrimaryTechSelect(this) 
            });
        },
        onPrimaryTechSelect: function(theSelect) {
            // clear dropdown of selection
            const selected = $(theSelect).find(":selected")
            $(theSelect).children().not($(selected)).remove()
            var ids = $(`#ticket-users`).val()
            ids.forEach((elem) => {
                // dont add the same element twice
                if (elem == $(selected).val()) {
                    return;
                }
                $(theSelect).append($("<option>", {
                    text: $(`#ticket-users`).find(`option[value="${elem}"]`).text(),
                    value: elem 
                }))
            })
        },
        /**
         * Removes a tech from the list of technicians.
         * Will prevent this action if: 
         * 1. the tech to remove is the current primary tech of the ticket (in the database), or 
         * 2. the tech has a greater than zero sum of time_entries logged in the ticket
         * @param {*} e 
         */
        removeTechFromTechField: async function(e) {
            $('#error-technician').hide()
            // disable the unselect element for now as we validate the tech
            e.preventDefault()
            var passed = true
            const tech_id = e.params.args.data.id
            // see if the removed user was the primary tech of the ticket internally, stop if it was
            const ticket_id = getUrlParameter('id')
            // only make the tech removal check if the ticket id is null; 
            // check if we are creating a ticket since theres no reason to stop the user from adding/removing techs if theres no ticket to validate from
            if (ticket_id && ticketMethod === 'update') {
                // activate the spinner and make the database checks
                await spinner.awaitSpinner(module.spinners.techs, async () => {
                    await $.ajax({
                        // SEE: TicketController.php/actionGetPrimaryTech
                        url: '/ticket/get-primary-tech',
                        method: 'GET',
                        data: { ticket_id: ticket_id },
                        success: function (res) {
                            // tech_id is a string
                            if (res.primaryTech && res.primaryTech.id == tech_id) {
                                // if it is, alert it and block
                                passed = false
                            }
                        },
                        error: () => {
                            alert('An error occured while trying to fetch the primary tech of this ticket.')
                        }
                    }())
                    // see if the removed user has a current time entry
                    await $.ajax({
                        // TODO: maybe instead of 'alert(...)' we use a modal popup, red text appear/disppear, or something else, since the alerts can be disabled. we can do this later or until important
                        // SEE: TimeEntryController.php/actionCheckEntries
                        url: '/time-entry/check-entries',
                        method: 'GET',
                        data: { tech_id: tech_id, ticket_id: getUrlParameter('id') },
                        success: (res) => {
                            if (res.exists) {
                                // if they do, alert it and block
                                passed = false
                            }
                        },
                        error: () => {
                            alert('An error occured while trying to fetch the time entires for the deleted technician.')
                        }
                    }())
                })
            }
            // if it pass, then remove! (will always pass in /ticket/create form)
            if (passed) {
                // filter by removing the one that was meant to be selected
                $('#ticket-users').val($('#ticket-users').val().filter(function(e){ return e != tech_id}) )
                $('#ticket-users').trigger('change')
            } else {
                $(module.errorDialog).show()
            }
        }
    }

    jQuery(() => module.loadEvents())

    return module
})

export default PrimaryTechHandler