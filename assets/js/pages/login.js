$(function() {
	$( "#login" ).submit(function( event ) {
	  event.preventDefault();
	  $("#blogin").attr('disabled','disabled');
		$.post( "core.php?l=login", $( "#login" ).serialize(), function( data ) {
		  if(data.id == 1){
		   	var url = "index.php";
			$(location).attr('href',url);
		  }else if(data.id == 0){
		  	$("[name='pass']").val('');
		  	$("#blogin").removeAttr('disabled','disabled');
		  	swal("Imposible acceder", "...verifique su codigo o contraseña.", "error");
		  }
		}, "json")
		.fail(function() {
			$("#blogin").removeAttr('disabled','disabled');
			$("[name='pass']").val('');
    		swal("Imposible acceder", "...se perdio la conexión con el servidor.", "error");
  		})
	});
});
