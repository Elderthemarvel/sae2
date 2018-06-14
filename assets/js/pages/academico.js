$(function() {


    var maxField = 25;
    var wrapper = $('.field_wrapper');
    var fieldHTML = (name) => `
		<div class="input-group">
			<input name=${name} type="text" class="form-control">
			<span inp='${name}' style="cursor: pointer;" class="input-group-addon bg-success add_button"><i class="icon-add"></i></span>
			<span style="cursor: pointer;" class="input-group-addon bg-pink remove_button"><i class="icon-subtract"></i></span>
		</div>`; 


    $(wrapper).on('click', '.add_button', function(e){
 	var x = 0; 
		$( "input[name*='jornada']" ).each(function(){
				x++;
		});
    	let name = $(this).attr('inp');
        if(x < maxField){
            x++;
            $(this).parent().parent('div').append(fieldHTML(name));
        }
    });

    $(wrapper).on('click', '.remove_button', function(e){
    e.preventDefault();   	
	 	var x = 0; 
		$( "input[name*='jornada']" ).each(function(){
				x++;
		});
    	if(x!=1){
	        $(this).parent('div').remove();
	        x--;
    	}
    });
    
    $(document).on("click",".btn_jornada", function() {
		$.post( "core.php?l=save_jornada", $( "#jornadas" ).serialize() , function( data ) {
		  if(!data.error){
		  	console.log('Correcto...');
		  }
		}, "json");
    });

    $(document).on("click",".btn_nivel", function() {
		$.post( "core.php?l=save_nivel", $( "#niveles" ).serialize() , function( data ) {
		  if(!data.error){
		  	console.log('Correcto...');
		  }
		}, "json");

    });

    $(document).on("click",".btn_seccion", function() {
		$.post( "core.php?l=save_seccion", $( "#secciones" ).serialize() , function( data ) {
		  if(!data.error){
		  	console.log('Correcto...');
		  }
		}, "json");

    });

    $(document).on("click",".btn_carrera", function() {
		$.post( "core.php?l=save_carrera", $( "#carreras" ).serialize() , function( data ) {
		  if(!data.error){
		  	console.log('Correcto...');
		  }
		}, "json");

    });

 


    $(document).on( "click",".bg-teal", function() {
    	let cuantos = $('#cuantos').val();
    	$('#tabla').html('Vamos bien'+cuantos);
    });














});
