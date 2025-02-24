/**
 * 
 * TicketModalHandler.js
 * 
 * Handles all modals in the ticket forms
 * 
 */

import DynamicForm from "../DynamicForm.js"

/**
 * Modal Handler Module
 * 
 * Handles all modals in the ticket forms
 */
const TicketModalHandler = (() => {

    // get a copy of the dynamic form module
    const dynamicForm = DynamicForm()

    const module = {
        modals: {
            timeEntry: {
                modal: '#time-entry-modal',
                button: '.time-entry-modal-button',
                content: '#time-entry-modal-content',
                'submit-button': '.submit-time-entry-modal'
            },
            asset: {
                modal: '#asset-modal',
                button: '.asset-modal-button',
                content: '#asset-modal-content',
                'submit-button': '.submit-asset-modal'
            },
            part: {
                modal: '#part-modal',
                button: '.part-modal-button',
                content: '#part-modal-content',
                'submit-button': '.submit-part-modal'
            },
            ticketNote: {
                modal: '#ticket-note-modal',
                button: '.ticket-note-button',
                content: '#ticket-note-modal-content',
                'submit-button': '.submit-ticket-note-modal'
            },
            // add more if required
        },
        loadEvents: function() {
            if (!this.modals) {
                return
            }
            // initialize each modal
            for (const modalEntry in this.modals) {
                const modal = this.modals[modalEntry]['modal']
                const button = this.modals[modalEntry]['button']
                const content = this.modals[modalEntry]['content']
                const submitButton = this.modals[modalEntry]['submit-button']
                $(button).on('click', () => {
                    $(modal).modal('show')
                        .find($(content))
                        .load($(button).attr('value'), function() {
                            // load the events for the forms
                            // since most of them are dynamic
                            dynamicForm.loadEvents()
                        })
                })
                // register the modal closing
                $(submitButton).submit(function () {
                    $(modal).modal('hide')
                })
            }
        },
    }

    jQuery(() => module.loadEvents())

    return module

})

export default TicketModalHandler