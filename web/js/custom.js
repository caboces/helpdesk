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
    .load($(this).attr('value'), function() {
        // we need to do it here because even after jquery has loaded the page, the asset modal contents have not been loaded yet until we click on the modal button.
        for (const $elem of $('.expanding-input-section').toArray()) {
            updateRmvBtnState($elem)
        }
    })
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
 * Get click of new asset button from ticket form.
 * Opens a modal window for creating asset entries.
 */
$('.asset-modal-button').click(function () {
    $('#asset-modal').modal('show')
    .find('#asset-modal-content')
    .load($(this).attr('value'), function() {
        // we need to do it here because even after jquery has loaded the page, the asset modal contents have not been loaded yet until we click on the modal button.
        for (const $elem of $('.expanding-input-section').toArray()) {
            updateRmvBtnState($elem)
        }
    })
});

/**
 * Close asset modal instead of redirecting to view-view
 */
$('#confirm-asset').submit(function(e) {
    // e.preventDefault();
    $('#asset-modal').modal('hide');
});

/* ===========================================================================================
|| EXPANDABLE INPUT FORMS (TIME ENTRY, ASSETS, PARTS)
=========================================================================================== */

// Expects the following format:

{/* 
    // ... indicates anything you want
<div id="..." class="expanding-input-section ...">
    <div class="duplicate-input-group ...">
        ... // your inputs

        // these are the add/remove buttons, they can be in their own div if required
        <?= Html::button('Remove', ['class' => 'modal-button-remove ...']); ?>
        <?= Html::button('Add', ['class' => 'modal-button-add ...']); ?>
    </div>
</div> 
*/}

/**
 * Add the parent element and put it below
 */
$(document).on('click', '.modal-button-add',  function(e) {
    const $parent = $(this).closest('.duplicate-input-group')
    const $clone = $parent.clone()
    // append the clone
    $section = $parent.closest('.expanding-input-section')
    $section.append($clone)
    // update input numbers.
    // copying the <select> elements wont select the same <option>s, but this is okay especially for the TimeEntry form.
    $clone.find('input, select, textarea').each(function() {
        const name = $(this).attr('name')
        // match a string like "[123]", where 123 is any number
        const match = name.match(/\[(\d+)\]/)
        if (match) {
            const newIndex = parseInt(match[1], 10) + 1;
            $(this).attr('name', name.replace(/\[\d+\]/, `[${newIndex}]`))
        }
    })
    updateRmvBtnState($section)
})

/**
 * Remove the parent element/entry
 */
$(document).on('click', '.modal-button-remove', function(e) {
    const $parent = $(this).closest('.duplicate-input-group')
    $section = $parent.closest('.expanding-input-section')
    if ($section.find('.duplicate-input-group').length > 1) {
        $parent.remove()
    } else {
        alert('Cannot remove the last entry!')
    }
    updateRmvBtnState($section)
})

/**
 * Disable removing the entry if there is only one
 */
function updateRmvBtnState(section) {
    if ($(section).find('.duplicate-input-group').length <= 1) {
        // disable remove button if there is only 1 element
        $(section).find('.modal-button-remove').prop('disabled', true)
    } else {
        // otherwise, keep enabled
        $(section).find('.modal-button-remove').prop('disabled', false)
    }
}

// update the remove buttons at page load to be disabled
// $(document).ready(function () {
//     for (const $elem of $('.expanding-input-section').toArray()) {
//         updateRmvBtnState($elem)
//     }
// })


/* ===========================================================================================
|| PART MODAL (TICKET FORM)
=========================================================================================== */

/**
 * Get click of new asset button from ticket form.
 * Opens a modal window for creating asset entries.
 */
$('.part-modal-button').click(function () {
    $('#part-modal').modal('show')
    .find('#part-modal-content')
    .load($(this).attr('value'), function() {
        // we need to do it here because even after jquery has loaded the page, the part modal contents have not been loaded yet until we click on the modal button.
        for (const $elem of $('.expanding-input-section').toArray()) {
            updateRmvBtnState($elem)
        }
    });
});

/**
 * Close TimeEntry modal instead of redirecting to view-view
 */
$('#confirm-part').submit(function(e) {
    // e.preventDefault();
    $('#asset-part').modal('hide');
});


/* ===========================================================================================
|| TICKET NOTE MODAL (TICKET FORM)
=========================================================================================== */

/**
 * Get click of new ticket note button from ticket form.
 * Opens a modal window for creating ticket note entries.
 */
$('.ticket-note-modal-button').click(function () {
    $('#ticket-note-modal').modal('show')
    .find('#ticket-note-modal-content')
    .load($(this).attr('value'), function() {
        // we need to do it here because even after jquery has loaded the page, the asset modal contents have not been loaded yet until we click on the modal button.
        for (const $elem of $('.expanding-input-section').toArray()) {
            updateRmvBtnState($elem)
        }
    })
});

/**
 * Close ticket note modal instead of redirecting to view-view
 */
$('#confirm-ticket-note').submit(function(e) {
    // e.preventDefault();
    $('#ticket-note-modal').modal('hide');
});

/* ===========================================================================================
|| TECHNICIANS (TICKET FORM)
=========================================================================================== */

$('#ticket-users').on('select2:unselecting', function(e) {
    // disable the unselect element for now as we validate
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
        // SEE: TimeEntryController.php/actionCheckEntries

        // TODO: maybe instead of 'alert(...)' we use a modal popup, red text appear/disppear, or something else, since the alerts can be disabled. we can do this later or until important
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
            // filter by removing the one that was meant to be selected
            $('#ticket-users').val($('#ticket-users').val().filter(function(e){ return e != tech_id}) )
            $('#ticket-users').trigger('change')
        }
    })
})