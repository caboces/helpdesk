/**
 * switchTabPane.js
 * 
 * switch tab panes based on url query param, if it exists
 */
const switchTabPane = ((tabsId, tabPaneQueryParameterName) => { 
    const params = new URLSearchParams(document.location.search)
    const tabLocation = params.get(tabPaneQueryParameterName)
    if (tabLocation && tabLocation.length > 0) {
        $(`#${tabsId} #${tabLocation}`).tab('show');
    }
})

export default switchTabPane