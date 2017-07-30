jQuery(document).ready(function($) {
        $('#search').multiselect({
            search: {
                left: '<input autocomplete="off" type="text" name="q" class="form-control" placeholder="Search..." />',
                right: '<input autocomplete="off" type="text" name="q" class="form-control" placeholder="Search..." />',
            },
            fireSearch: function(value) {
                return value.length > 1;
            }
        });
    });
