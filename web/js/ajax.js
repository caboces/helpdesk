/* ===========================================================================================
                                        AJAX JS INDEX
==============================================================================================

|| GENERAL

==============================================================================================
|| GENERAL
=========================================================================================== */


// ajax for ticket grids so changing the filters doesn't reload the page and bump users
// out of their current tab
$.pjax.reload({container: '#grid-all'});
$.pjax.reload({container: '#grid-assignments'});
$.pjax.reload({container: '#grid-resolved-closed'});

// ajax for the other grids that only require active/inactive filters
$.pjax.reload({container: '#grid-active'});
$.pjax.reload({container: '#grid-inactive'});

// tech time entries grid in ticket form
$.pjax.reload({container: '#tech-time-entries'});