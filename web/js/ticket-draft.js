jQuery(() => {
    function reCaptchaOnSubmit(token) {
        $('#ticket-draft-form').submit()
    }
    /**
     * OnChange: Customer Type Input Radio List
     * When the user selects a new customerTypeId for the ticket, show/hide/clear the second drop down
     * list options accordingly
     * 
     * efox - 4/11/24 - ctrl+c ctrl+v by tristen 1/24/2025
     */
    $('#ticketdraft-customer_type_id').on('change', function() {
        // find new customer_type_id value
        var selectedCustomerType = $("input:radio[name='TicketDraft[customer_type_id]']:checked").val();

        // CABOCES selected, show divisions > departments + department buildings
        if (selectedCustomerType == 1) {
            $('.field-ticketdraft-district_id').hide();
            $('.field-ticketdraft-district_building_id').hide();
            $('.field-ticketdraft-department_id').show();
            $('.field-ticketdraft-department_building_id').show();
            // clear district + district building val
            $('#ticketdraft-district_id').val('');
            $('#ticketdraft-district_building_id').val('');
        }
        // DISTRICT or WNYRIC selected, show districts + district buildings
        else if (selectedCustomerType == 2 || selectedCustomerType == 4) {
            $('.field-ticketdraft-district_id').show();
            $('.field-ticketdraft-district_building_id').show();
            $('.field-ticketdraft-department_id').hide();
            $('.field-ticketdraft-department_building_id').hide();
            // clear division > deptartment val
            $('#ticketdraft-division_id').val('');
            $('#ticketdraft-department_id').val('');
            $('#ticketdraft-department_building_id').val('');
        }
        // Nothing selected, clear all subsequent vals
        else {
            $('#ticketdraft-district_id').val('');
            $('#ticketdraft-department_id').val('');
            $('#ticketdraft-division_id').val('');
        }
    });
})