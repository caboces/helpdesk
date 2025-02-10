/**
 * 
 * ticketdraft-draft.js
 * 
 * Controls ticket draft functionality
 * 
 */


jQuery(() => {

    /**
     * onChangeTicketFieldsModule
     * 
     * makes changes to certain fields in the form depending on the current selection(s)
     * 
     * copied straight from ticket.js
     * 
     */
    const onChangeTicketFieldsModule = {
        loadEvents: function() {
            $('#ticketdraft-customer_type_id').on('change', this.ticketCustomerTypeIdOnChange)
            $('#ticketdraft-department_id').on('change', this.ticketDepartmentIdOnChange)
            $('#ticketdraft-district_id').on('change', this.ticketDistrictIdOnChange)
        },
        ticketCustomerTypeIdOnChange: function () {
            // find new customer_type_id value
            var selectedCustomerType = $("input:radio[name='TicketDraft[customer_type_id]']:checked").val()
        
            // CABOCES selected, show divisions > departments + department buildings
            if (selectedCustomerType == 1) {
                $('.field-ticketdraft-district_id').hide()
                $('.field-ticketdraft-district_building_id').hide()
                $('.field-ticketdraft-department_id').show()
                $('.field-ticketdraft-department_building_id').show()
                // clear district + district building val
                $('#ticketdraft-district_id').val('')
                $('#ticketdraft-district_building_id').val('')
            }
            // DISTRICT or WNYRIC selected, show districts + district buildings
            else if (selectedCustomerType == 2 || selectedCustomerType == 4) {
                $('.field-ticketdraft-district_id').show()
                $('.field-ticketdraft-district_building_id').show()
                $('.field-ticketdraft-department_id').hide()
                $('.field-ticketdraft-department_building_id').hide()
                // clear division > deptartment val
                $('#ticketdraft-division_id').val('')
                $('#ticketdraft-department_id').val('')
                $('#ticketdraft-department_building_id').val('')
            }
            // Nothing selected, clear all subsequent vals
            else {
                $('#ticketdraft-district_id').val('')
                $('#ticketdraft-department_id').val('')
                $('#ticketdraft-division_id').val('')
            }
        },
        ticketDepartmentIdOnChange: async function() {
            // Populate departments depending on the selected division
            return await $.ajax({
                type: 'post',
                url: '/ticket-draft/department-building-dependent-dropdown-query',
                data: { department_search_reference: $('#ticketdraft-department_id').val() },
                dataType: 'json',
                success: function(response) {
                    $("#ticketdraft-division_id").val("");
                    $("#ticketdraft-department_building_id").empty();
                    var count = response.length;
                    if (count === 0) {
                        $("#ticketdraft-department_building_id").empty();
                        $("#ticketdraft-department_building_id").append(`<option value="">Sorry, no buildings available for this department</option>`);
                    } else {
                        $("#ticketdraft-department_building_id").append(`<option value="">Select Department Building</option>`);
                        for (var i = 0; i < count; i++) {
                            var id = response[i]['id'];
                            var name = response[i]['name'];
                            $("#ticketdraft-department_building_id").append(`<option value="${id}">${name}</option>`);
        
                            // this is so redundant...
                            var division = response[i]['division'];
                            $("#ticketdraft-division_id").val(division);
                        }
                    }
                }
            })
        },
        ticketDistrictIdOnChange: async function () {
            // Populate districts based on selection
            return await $.ajax({
                type: 'post',
                url: '/ticket-draft/district-building-dependent-dropdown-query',
                data: { district_search_reference: $('#ticketdraft-district_id').val() },
                dataType: "json",
                success: function(response) {
                    $("#ticketdraft-district_building_id").empty();
                    var count = response.length;
        
                    if (count === 0) {
                        $("#ticketdraft-district_building_id").empty();
                        $("#ticketdraft-district_building_id").append(`<option value="">Sorry, no buildings available for this district</option>`);
                    } else {
                        $("#ticketdraft-district_building_id").append(`<option value="">Select District Building</option>`);
                        for (var i = 0; i < count; i++) {
                            var id = response[i]['id'];
                            var name = response[i]['name'];
                            $("#ticketdraft-district_building_id").append(`<option value="${id}">${name}</option>`);
                        }
                    }
                }
            })
        },
    }

    onChangeTicketFieldsModule.loadEvents()

})