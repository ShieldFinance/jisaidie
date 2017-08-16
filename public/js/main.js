jQuery(document).ready(function ($) {
    $('#search').multiselect({
        search: {
            left: '<input autocomplete="off" type="text" name="q" class="form-control" placeholder="Search..." />',
            right: '<input autocomplete="off" type="text" name="q" class="form-control" placeholder="Search..." />',
        },
        fireSearch: function (value) {
            return value.length > 1;
        }
    });

   

    $("#filter_popover").popover({
        html: true
    }).on('shown.bs.popover', function () {
        //datepicker 
        $('#date_from').datetimepicker();

        $('#date_to').datetimepicker();

    });
});


