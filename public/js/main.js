jQuery(document).ready(function ($) {
     $(".dropdown-toggle").dropdown();
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
        $('#date_from').datetimepicker({'useCurrent':false});

        $('#date_to').datetimepicker({'useCurrent':false});

    });
  
   $('.export_payments').click(function(){
            $('#service').val('ExportPayments');
            $('.payments_form').submit();
        }) 
        
        
	// add multiple select / deselect functionality
	$("#selectall").click(function () {
            $('.loan_cbx').attr('checked', this.checked);
	});

	// if all checkbox are selected, check the selectall checkbox
	// and viceversa
	$(".loan_cbx").click(function(){

		if($(".loan_cbx").length == $(".loan_cbx:checked").length) {
			$("#selectall").attr("checked", "checked");
		} else {
			$("#selectall").removeAttr("checked");
		}

	});
        
         $(".process_loan").click(function(e){
            var selectedvalue = [];
            if ($(':checkbox:checked').length > 0) {
              $(':checkbox:checked').each(function (i) {
                  selectedvalue[i] = $(this).val();

              });
              $("#service").val($(this).data('service'));
              $(".selected_loans").val(selectedvalue);//this will pass as array and method will be POST
              var form = $(e.target).data('form');
              var submit = true
              if($(this).data('service')!='ExportLoans')
                    submit = confirm($(e.target).data('alert'))
               
              if(submit)
                $('.'+form).submit();
              else
                  return false;
             }else if($(this).data('service')=='ExportLoans'){
                  $("#service").val($(this).data('service'));
                 $('.loans_form').submit();
             }else if(typeof($(this).data('service'))!='undefined'){
                 alert("Please select at least one item from the list")
                 return false;
             }
             
             
        });
        
        $('.service_type').on('click',function(){
            var selected = $('input[name="service_type"]:checked').val();
            if(selected=='service_document'){
                $('.service_file').removeClass('hide')
            }else{
                if(!$('.service_file').hasClass('hide')){
                    $('.service_file').addClass('hide')
                }
            }
        })
       
       


       $('[rel="popover"]').popover({
        container: 'body',
        html: true,
        placement:'bottom',
        content: function () {
            var clone = $($(this).data('popover-content')).clone(true).removeClass('hide');
            return clone;
        }
    }).click(function(e) {
        e.preventDefault();
    });
    
   
    $( "#loan_id_search" ).autocomplete({
      source: function( request, response ) {
        $.ajax({
          url: "/fetch_loans",
          dataType: "json",
          data: {
            q: request.term
          },
          success: function( data ) {
            response( data );
          }
        });
      },
      minLength: 3,
      select: function( event, ui ) {
        $('#loan_id').val(ui.item.id);
      },
      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      }
    });
    $('.reconcile_btn').on('click', function(){
        $('#payment_id').val($(this).data('payment_id'));
    })
    $('#submit_reconcile').on('click', function(){
        var submit = confirm('Are you sure you want to assign this payment?')
        if(submit)
          $('#reconcile_form').submit();
    })
    $('#reconcileModal').on('shown.bs.modal', function (e) {
        // do something...
    })
});


