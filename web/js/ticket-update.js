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

        const params = new URLSearchParams(document.location.search)
        const tabLocation = params.get('tabPane')
        if (tabLocation && tabLocation.length > 0) {
            $('#myTabs .nav-link[href="#' + name + '"]').tab('show');
        }
    }
})