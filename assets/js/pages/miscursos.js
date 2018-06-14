$(function() {
    $('.Sinput').select2();
    function tablaCursos(){
        $.extend( $.fn.dataTable.defaults, {
            autoWidth: false,
            columnDefs: [{
                orderable: false,
                width: '100px',
                targets: [ 5 ]
            }],
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            language: {
                search: '<span>Buscar:</span> _INPUT_',
                searchPlaceholder: 'Escribe para Buscar...',
                lengthMenu: '<span>Ver:</span> _MENU_',
                paginate: { 'first': 'primero', 'ultimo': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
            },
            drawCallback: function () {
                $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
            },
            preDrawCallback: function() {
                $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
            }
        });
        $('.datatable-column-search-selects tfoot td').not(':last-child').each(function () {
            var title = $('.datatable-column-search-selects thead th').eq($(this).index()).text();
            $(this).html('<input type="text" class="form-control input-sm" placeholder="'+title+'" />');
        });
        var table = $('.datatable-column-search-selects').DataTable({
            stateSave: true,
            dom: '<"datatable-header dt-buttons-right"fB><"datatable-scroll"tS><"datatable-footer"pl>',
            ordering: false,
            buttons: {            
                dom: {
                    button: {
                        className: 'btn btn-default'
                    }
                },
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            }
        });
        table.columns().every( function () {
            var that = this;
            $('input', this.footer()).on('keyup change', function () {
                that.search(this.value).draw();
            });
        });
        $('.dataTables_length select').select2({
            minimumResultsForSearch: Infinity,
            width: 'auto'
        });
        $('.filter-select').select2();
    }

    tablaCursos();

    function post_data(codigo, action){
        $.post( "core.php?l=refreshMisCursos", { codigo: codigo, action:action }, function( data ) {  
            $("#resultado1").html(data.html);
            tablaCursos();
       }, "json");
    }

    $("#asignarme").on('hidden.bs.modal', function () {
        var perfil = JSON.parse(Cookies.get('perfil'));
        var action = 'ver';
        var block = '#miscursos';
        post_data(perfil.codigo,action);
    });

    $( "#bCursos" ).submit(function(e) {
        e.preventDefault();
        $("#rbusqueda").show();
        $.post( "core.php?l=bCurso",$("#bCursos").serialize(), function( data ) {
            $("#rbusqueda").hide();
            $("#rbusqueda").html(data.html);
            $(".asignar").attr('data-seccion',data.seccion);
            $(".asignar").attr('data-maestro',data.docente);
            $("#rbusqueda").show('slow');
        }, "json")
        .fail(function() {
            $("#rbusqueda").hide('fast');
            swal('Ocurrió un error en el scripts o servidor, no hay datos para este grado. ');
        });
    });

    $(document).on('click', '.asignar', function (e) {
        e.preventDefault();
        var docente     =       $("#codigo").attr('code');
        var datos       =       $(this).attr('data-datos');
        var id          =       $(this).parent().parent();
        var este        =       $(this);
        $.post( "core.php?l=Asignarme",{docente: docente,datos:datos}, function( data ) {
            if(data.error_code == 1){
                swal("Oh noes!", data.error, "error");
            }else if(data.error_code == 0){
                swal({title: "Buen Trabajo!",text: data.error,type: "success",timer: 2000,showConfirmButton: false});
                id.removeClass( "fila" ).addClass( "success" );
                este.removeClass( "asignar label-info" ).addClass( "desasignar label-danger" );
                este.html('Desasignar');
            }
        }, "json")
        .fail(function() {
            $("#rbusqueda").hide();
            swal("Oh noes!",'Ocurrió un error en el scripts o servidor, no hay datos para este grado. ', "error");
        });
    });

    $(document).on('click', '.desasignar', function (e) {
        e.preventDefault();
        var docente     =       $("#codigo").attr('code');
        var datos       =       $(this).attr('data-datos');
        var id          =       $(this).parent().parent();
        var este        =       $(this);
        $.post( "core.php?l=Desasignarme",{docente: docente,datos:datos}, function( data ) {
            if(data.error_code == 1){
                swal("Oh noes!", data.error, "error");
            }else if(data.error_code == 0){
                swal({title: "Deleted!",text: data.error,type: "warning",timer: 1000,showConfirmButton: false});
                id.removeClass( "success" ).addClass( "fila" );
                este.removeClass( "desasignar label-danger" ).addClass( "asignar label-info" );
                este.html('Asignarme');
            }
        }, "json")
        .fail(function() {
            $("#rbusqueda").hide();
            swal("Oh noes!",'Ocurrió un error en el scripts o servidor, no hay datos para este grado. ', "error");
        });
    });

    $(document).on("click", ".edit, .crear", function (e) {
        e.preventDefault();
        if(cargarcookie() == 0){
            swal("Error!", "Debe seleccionar unidad a trabajar!", "error");
        }else{
            var datos = $(this).attr('data-crono');
            var docente = $("#codigo").attr('code');
            var clase = $(this).attr("class");
            var unidad = cargarcookie();
            var docente_t =   Cookies.get("perfil");
            $.post( "core.php?l=cronograma", {datos:datos,docente:docente,unidad:unidad,clase:clase,docente_t:docente_t}, function(data) {
                $('#cronoNuevo').html(data.html);
                $('#miscursos').hide('slow');
                $('#cronograma').show('slow');
                $('.pie').show();
                $('#borrar').hide();
                if(data.estado == 2){
                    $('.adjuntar').hide();
                    $('.guardarG').hide();
                }else if(data.estado == 0 || data.estado == 3 || data.estado == 4){
                    if(data.estado == 3 || data.estado == 4){
                        $('#borrar').show();
                    }
                    $('.adjuntar').show();
                    $('.guardarG').show();
                    
                }else if(data.estado == 1){
                    $('.pie').hide();
                }

                $('#curso').html('<span class="label border-left-info label-striped">'+data.curso_t + ' | '+data.grado+ ' | sección: '+data.seccion+
                    ' | Código: '+data.codigo+'</span>');
            }, "json");
        }
    });

    $(document).on("click", ".print", function (e) {
        e.preventDefault();
        $("#printArea").printArea();
    });

    $(document).on("click", ".guardar", function (e) {
        e.preventDefault();
        var estado = $(this).attr('estado');
        $('#estado').val(estado);
        var campos = {};
        var vacio = 0;
        $('input').each(function() {
            campos[$(this).attr('id')] = $(this).val();
        });
        for (var i=1; i <= campos['fila']; i++) {
            if(campos['C'+i] == ""){
                vacio++;
            }
        }
        if(campos['f1'] == ""){
            vacio++;
        }        
        if(vacio == 0){
            $.post( "core.php?l=SaveIndividualCrono",{datos: campos}, function( data ) {
                if(data.alert=="0"){
                    swal("Mensaje",data.msg,data.alert);
                }else if(data.alert > "0"){
                    $("#id").val(data.idD);
                    swal("Mensaje",data.msg,data.alert);
                }
            }, "json");
        }else{
            swal("Error","Es necesario que asigne al menos la primera fila del cronograma.","error");
        }
    }); 

    $(document).on("click", ".cancelar", function (e) {
        e.preventDefault();
        var perfil = JSON.parse(Cookies.get('perfil'));
        var action = 'ver';
        post_data(perfil.codigo,action);
        var ver = $(this).attr('data-show');
        var ocultar = $(this).attr('data-hide');
        $('#'+ver).show('slow');
        $('#'+ocultar).hide('slow');
    });

    $("#Clonar").on('hidden.bs.modal', function () {
        $('#Copciones').show();
        $('#cursosA').hide();
    });

    $("#fullzona").on('hidden.bs.modal', function () {
        jQuery(this).removeData('bs.modal');
        jQuery(this).find('.modal-content').empty();
    });


    $(document).on("click", ".borrar", function (e) {
        e.preventDefault();
        var idK =   $(this).attr('idK');
        var idD =   $(this).attr('idD');


        swal({ 
         title: "¿Seguro que desea Borrar?", 
         text: "No podrá deshacer este paso...", 
         type: "warning", 
         showCancelButton: true,
         cancelButtonText: "Cancelar",
         confirmButtonColor: "#DD6B55", 
         confirmButtonText: "Borrar!", 
         closeOnConfirm: false }, 
         function(){ 
             

        $.post( "core.php?l=borrarCrono",{idD:idD, idK:idK,data: 'key'}, function( data ) {
            if(data.msg=="1"){
                var perfil = JSON.parse(Cookies.get('perfil'));
                var action = 'ver';
                post_data(perfil.codigo,action);
                $('#cronograma').hide();
                $('#miscursos').show();
                 swal("¡Hecho!", 
                 "cronograma Borrado.", 
                 "success"); 
            }else{
                swal("Error!", 
                 "Algo raro paso, no fue posible borrar este cronograma.", 
                 "alert"); 
            }

        }, "json");


         });
    });

    $(document).on("click", ".clonar", function (e) {
        e.preventDefault();
        var codigo = $('#codigo').attr('code');

        $.post( "core.php?l=clonar",{codigo: codigo, tipo:'clonar'}, function( data ) {
            if(data){

                $('#miscursose').html(data.html);
                $('#Copciones').hide('fast');
                $('#cursosA').show('fast');
            }

        }, "json");





    });


    $( "#clon" ).submit(function(e) {
        e.preventDefault();
        $.post( "core.php?l=clon",$("#bCursos").serialize(), function( data ) {
            $("#rbusqueda").hide();
            $("#rbusqueda").html(data.html);
            $(".asignar").attr('data-seccion',data.seccion);
            $(".asignar").attr('data-maestro',data.docente);
            $("#rbusqueda").show('slow');
        }, "json")
        .fail(function() {
            $("#rbusqueda").hide('fast');
            swal('Ocurrió un error en el scripts o servidor, no hay datos para este grado. ');
        });
    });









    $(document).on("click", ".enlazar", function (e) {
        e.preventDefault();
        $('#Copciones').hide('fast');
        $('#cursosA').show('fast');

    });    







    
});






    $(document).keydown(
        function(e){
            var datos = $(".move:focus").focus().attr('data-id');
            if (typeof datos !== 'undefined') {
                datos = datos.split('|');
                var id = parseInt(datos['0']);
                var li = parseInt(datos['1']);
                if (e.keyCode == 107 ) {
                    e.preventDefault();
                    var sig = (id+1);
                    $("#C"+sig).focus();
                }else if (e.keyCode == 13 ) {      
                    var sig = (id+li);
                    $("#C"+sig).focus();
                }else if (e.keyCode == 109) {
                    e.preventDefault();
                    var sig = (id-1);
                    $("#C"+sig).focus();
                }else if (e.keyCode == 40) {      
                    var sig = (id+li);
                    $("#C"+sig).focus();
                }else if (e.keyCode == 38) {      
                    var sig = (id-li);
                    $("#C"+sig).focus();
           
                }
            }
        }
    );