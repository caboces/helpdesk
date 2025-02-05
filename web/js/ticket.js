/**
 * ticket.js
 * 
 * general ticket stuff
 */

jQuery(() => {
    /**
     * onChangeTicketFieldsModule
     * 
     * makes changes to certain fields in the form depending on the current selection(s)
     */
    onChangeTicketFieldsModule = {
        loadEvents: function() {
            $('#ticket-customer_type_id').on('change', this.ticketCustomerTypeIdOnChange)
            $('#ticket-department_id').on('change', this.ticketDepartmentIdOnChange)
            $('#ticket-district_id').on('change', this.ticketDistrictIdOnChange)
            $('#ticket-job_type_id').on('change', this.ticketJobTypeIdOnChange)
        },
        ticketCustomerTypeIdOnChange: function () {
            // find new customer_type_id value
            var selectedCustomerType = $("input:radio[name='Ticket[customer_type_id]']:checked").val()
        
            // CABOCES selected, show divisions > departments + department buildings
            if (selectedCustomerType == 1) {
                $('.field-ticket-district_id').hide()
                $('.field-ticket-district_building_id').hide()
                $('.field-ticket-department_id').show()
                $('.field-ticket-department_building_id').show()
                // clear district + district building val
                $('#ticket-district_id').val('')
                $('#ticket-district_building_id').val('')
            }
            // DISTRICT or WNYRIC selected, show districts + district buildings
            else if (selectedCustomerType == 2 || selectedCustomerType == 4) {
                $('.field-ticket-district_id').show()
                $('.field-ticket-district_building_id').show()
                $('.field-ticket-department_id').hide()
                $('.field-ticket-department_building_id').hide()
                // clear division > deptartment val
                $('#ticket-division_id').val('')
                $('#ticket-department_id').val('')
                $('#ticket-department_building_id').val('')
            }
            // Nothing selected, clear all subsequent vals
            else {
                $('#ticket-district_id').val('')
                $('#ticket-department_id').val('')
                $('#ticket-division_id').val('')
            }
        },
        ticketDepartmentIdOnChange: async function() {
            // Populate departments depending on the selected division
            return await $.ajax({
                type: 'post',
                url: '/ticket/department-building-dependent-dropdown-query',
                data: { department_search_reference: $('#ticket-department_id').val() },
                dataType: 'json',
                success: function(response) {
                    $("#ticket-division_id").val("");
                    $("#ticket-department_building_id").empty();
                    var count = response.length;
                    if (count === 0) {
                        $("#ticket-department_building_id").empty();
                        $("#ticket-department_building_id").append(`<option value="">Sorry, no buildings available for this department</option>`);
                    } else {
                        $("#ticket-department_building_id").append(`<option value="">Select Department Building</option>`);
                        for (var i = 0; i < count; i++) {
                            var id = response[i]['id'];
                            var name = response[i]['name'];
                            $("#ticket-department_building_id").append(`<option value="${id}">${name}</option>`);
        
                            // this is so redundant...
                            var division = response[i]['division'];
                            $("#ticket-division_id").val(division);
                        }
                    }
                }
            })
        },
        ticketDistrictIdOnChange: async function () {
            // Populate districts based on selection
            return await $.ajax({
                type: 'post',
                url: '/ticket/district-building-dependent-dropdown-query',
                data: { district_search_reference: $('#ticket-district_id').val() },
                dataType: "json",
                success: function(response) {
                    $("#ticket-district_building_id").empty();
                    var count = response.length;
        
                    if (count === 0) {
                        $("#ticket-district_building_id").empty();
                        $("#ticket-district_building_id").append(`<option value="">Sorry, no buildings available for this district</option>`);
                    } else {
                        $("#ticket-district_building_id").append(`<option value="">Select District Building</option>`);
                        for (var i = 0; i < count; i++) {
                            var id = response[i]['id'];
                            var name = response[i]['name'];
                            $("#ticket-district_building_id").append(`<option value="${id}">${name}</option>`);
                        }
                    }
                }
            })
        },
        ticketJobTypeIdOnChange: async function () {
            // Populate job type id
            return await $.ajax({
                type: 'post',
                url: '/ticket/job-category-dependent-dropdown-query',
                data: { job_category_search_reference: $('#ticket-job_type_id').val() },
                dataType: 'json',
                success: function(response) {
                    // clear the current job_category selection
                    $("#ticket-job_category_id").empty();
                    var count = response.length;
        
                    if (count === 0) {
                        $("#ticket-job_category_id").empty();
                        $("#ticket-job_category_id").empty().append(`<option value="">Sorry, no categories available for this type</option>`);
                    } else {
                        $("#ticket-job_category_id").append(`<option value="">Select Category</option>`);
                        for (var i = 0; i < count; i++) {
                            var id = response[i]['id'];
                            var name = response[i]['name'];
                            $("#ticket-job_category_id").append(`<option value="${id}">${name}</option>`);
                        }
                    }
                }
            })
        }
    }

    onChangeTicketFieldsModule.loadEvents()

})
