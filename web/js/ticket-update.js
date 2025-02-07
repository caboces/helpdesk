import dynamicFormModule from './dynamic-forms.js'

/**
 * ticket-update.js
 * 
 * Loaded in the /update/ticket page.
 */

jQuery(() => {

    /** 
     * Module that switches tab panes depending on the url parameter given.
     */
    const switchTabPaneModule = { 
        init: function() {
            const params = new URLSearchParams(document.location.search)
            const tabLocation = params.get('tabPane')
            if (tabLocation && tabLocation.length > 0) {
                $('#myTabs .nav-link[href="#' + name + '"]').tab('show');
            }
        }
    }

    /**
     * Handles all modals in the ticket update form
     */
    const modalHandlerModule ={
        modals: {
            timeEntry: {
                modal: '#time-entry-modal',
                content: '#time-entry-modal-content',
                button: '.time-entry-modal-button',
                'confirm-button': '.confirm-time-entry'
            },
            asset: {
                modal: '#asset-modal',
                content: '#asset-modal-content',
                button: '.asset-modal-button',
                'confirm-button': '.confirm-asset'
            },
            part: {
                modal: '#part-modal',
                content: '#part-modal-content',
                button: '.part-modal-button',
                'confirm-button': '.confirm-part'
            },
            ticketNote: {
                modal: '#ticket-note-modal',
                content: '#ticket-note-modal-content',
                button: '.ticket-note-button',
                'confirm-button': '.confirm-ticket-note'
            },
        },
        loadEvents: function() {
            if (!this.modals) {
                return
            }
            // initialize each modal
            for (const modalEntry in this.modals) {
                const modal = this.modals[modalEntry]['modal']
                const content = this.modals[modalEntry]['content']
                const button = this.modals[modalEntry]['button']
                const confirmButton = this.modals[modalEntry]['confirm-button']
                $(button).on('click', () => {
                    $(modal).modal('show')
                        .find($(content))
                        .load($(button).attr('value'), function() {
                            // load the dynamic form events after loading the contents of the modal
                            dynamicFormModule.loadEvents()
                        })
                })
                // register the modal closing
                $(confirmButton).submit(function () {
                    $(modal).modal('hide')
                })
            }
        },
    }

    modalHandlerModule.loadEvents()
    switchTabPaneModule.init()
})