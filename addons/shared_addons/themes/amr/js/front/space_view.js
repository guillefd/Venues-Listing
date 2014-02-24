

$(document).ready(function(){ 

	//galeries
	$('.carousel').carousel({
		interval: false
	});

	//tooltip
	$('span.label.label-success.facility').tooltip({
		container: 'body'
	});
	$('span.label.label-default.facility').tooltip({
		container: 'body'
	});
	$('span.service-icon-included').tooltip({
		container: 'body'
	});	

	//tooltip
	$('span.label.usetype').tooltip();

	$('.amr-tooltip').tooltip({
		container: 'body'
	});



});			