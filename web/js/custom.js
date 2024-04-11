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

    // CABOCES selected, show divisions > departments
    if (selectedCustomerType == 1) {
        $('.field-ticket-district_id').hide();
        $('.field-ticket-department_id').show();
        // clear district val
        $('#ticket-district_id').val('');
    }
    // DISTRICT or WNYRIC selected, show districts
    else if (selectedCustomerType == 2 || selectedCustomerType == 4) {
        $('.field-ticket-district_id').show();
        $('.field-ticket-department_id').hide();
        // clear division > deptartment val
        $('#ticket-department_id').val('');
    }
    // Nothing selected, clear all subsequent vals
    else {
        $('#ticket-district_id').val('');
        $('#ticket-department_id').val('');
    }
});

/**
 * OnChange: District Input Drop Down List
 * When the user selects a new district, populate the third building drop down list using using
 * the district_id as a filter.
 * 
 * efox - 4/11/24
 */
$('#ticket-district_id').on('change', function() {
    var url = $(this).data('url');
    $.ajax({
        type: 'POST',
        url: url,
        data: {search_reference: $(this).val()},
        dataType: 'json',
        success: function(response) {
            console.log('working so far!');
        }
    });
});

});