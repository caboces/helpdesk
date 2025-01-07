$(document).ready(function(){

/* ===========================================================================================
                                        JS INDEX
==============================================================================================

|| GENERAL
|| CUSTOMER SELECTION (TICKET FORM)
|| TIME ENTRY MODAL (TICKET FORM)

==============================================================================================
|| GENERAL
=========================================================================================== */

/**
 * Allow secondary pill navigation buttons (found bottom of ticket _form) to fire primary pill's
 * action (secondary "Technicians" button will open Technicians content)
 */
$('#secondary-pill-nav-technicians').click(function(){
    $('#pills-technicians-tab').click();
});

$('#secondary-pill-nav-equipment').click(function(){
    $('#pills-equipment-tab').click();
});

$('#secondary-pill-nav-time-entries').click(function(){
    $('#pills-time-entries-tab').click();
});

/* ===========================================================================================
|| CUSTOMER SELECTION (TICKET FORM)
=========================================================================================== */
/**
 * OnChange: Customer Type Input Radio List
 * When the user selects a new customerTypeId for the ticket, show/hide/clear the second drop down
 * list options accordingly
 * 
 * efox - 4/11/24
 */
$('#ticket-customer_type_id').on('change', function() {
    // find new customer_type_id value
    var selectedCustomerType = $("input:radio[name='Ticket[customer_type_id]']:checked").val();

    // CABOCES selected, show divisions > departments + department buildings
    if (selectedCustomerType == 1) {
        $('.field-ticket-district_id').hide();
        $('.field-ticket-district_building_id').hide();
        $('.field-ticket-department_id').show();
        $('.field-ticket-department_building_id').show();
        // clear district + district building val
        $('#ticket-district_id').val('');
        $('#ticket-district_building_id').val('');
    }
    // DISTRICT or WNYRIC selected, show districts + district buildings
    else if (selectedCustomerType == 2 || selectedCustomerType == 4) {
        $('.field-ticket-district_id').show();
        $('.field-ticket-district_building_id').show();
        $('.field-ticket-department_id').hide();
        $('.field-ticket-department_building_id').hide();
        // clear division > deptartment val
        $('#ticket-division_id').val('');
        $('#ticket-department_id').val('');
        $('#ticket-department_building_id').val('');
    }
    // Nothing selected, clear all subsequent vals
    else {
        $('#ticket-district_id').val('');
        $('#ticket-department_id').val('');
        $('#ticket-division_id').val('');
    }
});

/* ===========================================================================================
|| TIME ENTRY MODAL (TICKET FORM)
=========================================================================================== */

/**
 * Get click of new time-entry button from ticket form.
 * Opens a modal window for creating tech time entries.
 */
$('#time-entry-modal-button').click(function () {
    $('#time-entry-modal').modal('show')
    .find('#time-entry-modal-content')
    .load($(this).attr('value'));
});

/**
 * Close TimeEntry modal instead of redirecting to view-view
 */
$('#confirm-time-entry').submit(function(e) {
    // e.preventDefault();
    $('#time-entry-modal').modal('hide');
});

});

/* ===========================================================================================
|| ASSET MODAL (TICKET FORM)
=========================================================================================== */

/**
 * Get click of new ticket equipment button from ticket form.
 * Opens a modal window for creating ticket equipment entries.
 */
$('#ticket-equipment-modal-button').click(function () {
    $('#asset-modal').modal('show')
    .find('#asset-modal-content')
    .load($(this).attr('value'));
});

/**
 * Close TimeEntry modal instead of redirecting to view-view
 */
$('#confirm-ticket-equipment').submit(function(e) {
    // e.preventDefault();
    $('#ticket-equipment-modal').modal('hide');

});

/* ===========================================================================================
|| TECHNICIANS (TICKET FORM)
=========================================================================================== */
$('#ticket-users').on('select2:unselecting', function(e) {
    e.preventDefault()
    var passed = true
    const tech_id = e.params.args.data.id
    // see if the removed user was the primary tech
    if ($('#ticket-primary_tech_id').val() && tech_id === $('#ticket-primary_tech_id').val()) {
        // if it is, alert it and block
        alert('You cannot delete a tech that is assigned as the primary tech.')
        passed = false
    }
    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;
    
        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');
    
            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
        return false;
    };
    // see if the removed user has a current time entry
    $.ajax({
        url: '/time-entry/check-entries',
        method: 'GET',
        data: { tech_id: tech_id, ticket_id: getUrlParameter('id') },
        success: (res) => {
            if (res.exists) {
                // if they do, alert it and block
                alert('You cannot remove technicians who have had time entries logged for this ticket.')
                passed = false
            }
        },
        error: () => {
            alert('An error occured while trying to fetch the time entires for the deleted technician.')
        }
    }).then(() => {
        // if it pass, then remove!
        if (passed) {
            const index = $('#ticket-users').val().indexOf(tech_id)
            $('#ticket-users').val($('#ticket-users').val().filter(function(e){ return e != tech_id}) )
            $('#ticket-users').trigger('change')
        }
    })
})