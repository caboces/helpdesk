$(document).ready(function(){

/* ===========================================================================================
                                        JS INDEX
==============================================================================================

|| GENERAL

==============================================================================================
|| GENERAL
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

// modal window for tech time entries
$('#modalButton').click(function () {
    $('#modal').modal('show').find('#modalContent').load($(this).attr('value'));
});

});