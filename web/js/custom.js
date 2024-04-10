$(document).ready(function(){

/* ===========================================================================================
                                        JS INDEX
==============================================================================================

|| GENERAL

==============================================================================================
|| GENERAL
=========================================================================================== */

/**
 * When the user selects a new customerTypeId for the ticket, show/hide the second drop down list
 * options accordingly
 */
$('#ticket-customer_type_id').on('change', function() {
    // value of customerType select input
    var selectedCustomerType = this.value;

    // CABOCES selected, show divisions > departments
    if (selectedCustomerType == 1) {
        document.querySelector('#ticket-district_id').parentElement.style.display = 'none';
        document.querySelector('#ticket-department_id').parentElement.style.display = 'block';
    }
    // DISTRICT or WNYRIC selected, show districts
    if (selectedCustomerType == 2 || selectedCustomerType == 4) {
        document.querySelector('#ticket-district_id').parentElement.style.display = 'block';
        document.querySelector('#ticket-department_id').parentElement.style.display = 'none';
    }
});

});