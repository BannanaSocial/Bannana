$(document).ready(function() {
	$("#fanpages").hide();
	$("#addfanpage").click(function(event) {
		/* Act on the event */
		$("#fanpages").show('slow');
	});

	$(".select-fanpage").click(function(event) {
		var vfbid = $(this).data('fbid');
		var vfbtoken = $(this).data('fbtoken');
		var vname = $(this).data('name');
		/* Act on the event */
		if(confirm("Are you sure you want to select "+vname+"?")){
			$.post('/dashboard/fanpage/add/', {fbid: vfbid, fbtoken: vfbtoken, name:vname}, function(data) {
			  //optional stuff to do after success
			  window.location="/dashboard/";
			});
			
		}
	});
});