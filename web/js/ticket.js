/**
 * ticket.js
 * 
 * general ticket stuff
 */

jQuery(() => {

    /**
     * getUrlParameter
     * 
     * Function that helps resolve the url parameter in the current window
     */
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

    /**
     * primaryTechModule
     * 
     * handles what happens when different techs are selected and what should be shown in the primary tech select options
     */
    primaryTechModule = {
        loadEvents: function() {
            $('#ticket-users').on('select2:unselecting', this.removeTechFromTechField);
        },
        removeTechFromTechField: async function(e) {
            // disable the unselect element for now as we validate
            e.preventDefault()
            var passed = true
            const tech_id = e.params.args.data.id
            // see if the removed user was the primary tech of the ticket internally, stop if it was
            await $.ajax({
                // SEE: TicketController.php/actionGetPrimaryTech
                url: '/ticket/get-primary-tech',
                method: 'GET',
                data: { ticket_id: getUrlParameter('id') },
                success: function (res) {
                    // tech_id is a string
                    if (res.primaryTech && res.primaryTech.id == tech_id) {
                        // if it is, alert it and block
                        alert('You cannot delete a tech that is assigned as the current primary tech.')
                        passed = false
                    }
                },
                error: () => {
                    alert('An error occured while trying to fetch the primary tech of this ticket.')
                }
            })
            // see if the removed user has a current time entry
            await $.ajax({
                // TODO: maybe instead of 'alert(...)' we use a modal popup, red text appear/disppear, or something else, since the alerts can be disabled. we can do this later or until important
                // SEE: TimeEntryController.php/actionCheckEntries
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
            })
            // if it pass, then remove!
            if (passed) {
                // filter by removing the one that was meant to be selected
                $('#ticket-users').val($('#ticket-users').val().filter(function(e){ return e != tech_id}) )
                $('#ticket-users').trigger('change')
            }
        }
    }

    onChangeTicketFieldsModule.loadEvents()
    primaryTechModule.loadEvents()

})
