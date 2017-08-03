
$(function() {
    
  getRegistrationChart();
});


function getRegistrationChart() {
   
   
        organization=1; //organizattion id
		year=2017; //data year
		
		$.ajax({
		     url: './userRegistration',
	          type: "POST",
	          cache: false,
			  data: {
			  organization: organization,
			  year: year
			  },
			  dataType: "json",
			  beforeSend: function( data ) {
				
			  },
			  success: function (data) {
			  // alert('am getting here');
               
			   plotDrillDownChart("user_reg_container",data.series,data.drilldown)
	     	  }
		})
   
};
 
    
function plotDrillDownChart(container,series,drilldown){
	
	
Highcharts.chart(container, {
        chart: {
            type: 'column'
        },
        title: {
             style: { "display": "none"}
        },
        xAxis: {
           type:'category',
            crosshair: true
        },
        yAxis: {
            title: {
                text: 'Users'
            }
        },
        colors:['#602D91'],
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.0f} Users</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series:series,
        drilldown: drilldown
    });
}
$(document).ready(function() {
    
    
     //pending event triggers i.e on modal shown, on dropdown change and on year change
	 $('#modal-advances-pending-month').on('shown.bs.modal', function (e) {
		getData(2,'container_pending',"Monthly Pending Loans","#filter_company_pending","#pending-year");
		
	 });
	 $( "#filter_company_pending" ).change(function() {
		getData(2,'container_pending',"Monthly Pendind Loans","#filter_company_pending","#pending-year");
		
	 });
	 $( "#pending-year" ).change(function() {
		getData(2,'container_pending',"Monthly Pending Loans","#filter_company_pending","#pending-year");
		
	 });
	//datepicker plugin

	for (i = new Date().getFullYear(); i > 2013; i--)
	{
		$('#pending-year').append($('<option />').val(i).html(i));
	}
	
	//disbursal event triggers i.e on modal shown, on dropdown change and on year change
	 $('#modal-advances-disbursed-month').on('shown.bs.modal', function (e) {
		getData(5,'container_disbursed',"Monthly loan disbursals","#filter_company_disbursed","#disbursed-year");
		
	 });
	 $( "#filter_company_disbursed" ).change(function() {
		getData(5,'container_disbursed',"Monthly loan disbursals","#filter_company_disbursed","#disbursed-year");
		
	 });
	 $( "#disbursed-year" ).change(function() {
		getData(5,'container_disbursed',"Monthly loan disbursals","#filter_company_disbursed","#disbursed-year");
		
	 });
	//datepicker plugin

	for (i = new Date().getFullYear(); i > 2013; i--)
	{
		$('#disbursed-year').append($('<option />').val(i).html(i));
	}
    
    
     //serviced event triggers i.e on modal shown, on dropdown change and on year change
	 $('#modal-advances-serviced-month').on('shown.bs.modal', function (e) {
		getData(6,'container_serviced',"Monthly Serviced Loans","#filter_company_serviced","#serviced-year");
		
	 });
	 $( "#filter_company_serviced" ).change(function() {
		getData(6,'container_serviced',"Monthly Serviced Loans","#filter_company_serviced","#serviced-year");
		
	 });
	 $( "#serviced-year" ).change(function() {
		getData(6,'container_serviced',"Monthly Serviced Loans","#filter_company_serviced","#serviced-year");
		
	 });
	//datepicker plugin

	for (i = new Date().getFullYear(); i > 2013; i--)
	{
		$('#serviced-year').append($('<option />').val(i).html(i));
	}
    
    
    
     //serviced event triggers i.e on modal shown, on dropdown change and on year change
	 $('#modal-advances-declined-month').on('shown.bs.modal', function (e) {
		getData(3,'container_declined',"Monthly Declined Loans","#filter_company_declined","#declined-year");
		
	 });
	 $( "#filter_company_declined" ).change(function() {
		getData(3,'container_declined',"Monthly Declined Loans","#filter_company_declined","#declined-year");
		
	 });
	 $( "#declined-year" ).change(function() {
		getData(3,'container_declined',"Monthly Declined Loans","#filter_company_declined","#declined-year");
		
	 });
	//datepicker plugin

	for (i = new Date().getFullYear(); i > 2013; i--)
	{
		$('#declined-year').append($('<option />').val(i).html(i));
	}
    
    
    //serviced event triggers i.e on modal shown, on dropdown change and on year change
	 $('#modal-advances-locked-month').on('shown.bs.modal', function (e) {
		getData(7,'container_locked',"Monthly Locked Loans","#filter_company_locked","#locked-year");
		
	 });
	 $( "#filter_company_locked" ).change(function() {
		getData(7,'container_locked',"Monthly Locked Loans","#filter_company_locked","#locked-year");
		
	 });
	 $( "#locked-year" ).change(function() {
		getData(7,'container_locked',"Monthly Locked Loans","#filter_company_locked","#locked-year");
		
	 });
	//datepicker plugin

	for (i = new Date().getFullYear(); i > 2013; i--)
	{
		$('#locked-year').append($('<option />').val(i).html(i));
	}
    
    
	 
    
    
});
function showPendingAdvances(){
		$('#modal-advances-pending-month').modal();
		  
}
function showDisbursedAdvances(){
		$('#modal-advances-disbursed-month').modal();
		  
}
function showServicedAdvances(){
		$('#modal-advances-serviced-month').modal();
		  
}
function showDeclinedAdvances(){
		$('#modal-advances-declined-month').modal();
		  
}
function showLockedAdvances(){
		$('#modal-advances-locked-month').modal();
		  
}
function getData(type,container,title,organization_filter,year_filter){
		organization=$(organization_filter+" option:selected" ).val();//company id
		year=$(year_filter+" option:selected" ).val();//company id//data year
		source=$( organization_filter+" option:selected" ).text();;//company name
		$.ajax({
		      url: './loanData',
	          type: "POST",
	          cache: false,
			  data: {
			  type: type,
			  organization: organization,
			  year: year
			  },
			  dataType: "json",
			  beforeSend: function( data ) {
				
			  },
			  success: function (data) {
			    data = $.parseJSON(JSON.stringify(data));
				var series = [];
				$.each(data, function (i,v)
				{
				  series.push(v);
				});
			   plotData(container,title,source,series)
	     	  }
		})
}



function plotData(container,title,source,series){
	
	
Highcharts.chart(container, {
        chart: {
            type: 'column'
        },
        title: {
            text:title
        },
        subtitle: {
            text: 'Source: '+source
        },
        
        xAxis: {
            
            categories: [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Amount (KES)'
            }
        },
        colors:['#8DC853'],
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} KES</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Month',
            data:series

        }]
    });
}
