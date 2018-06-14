    $(function(){
        
        $(".file-styled").uniform({
            fileButtonClass: 'action btn btn-default'
        });

        $('#tblarchivos').DataTable();

        Dropzone.autoDiscover = false;
        $("#files_multiple").dropzone({
            paramName: "file",
            dictDefaultMessage: 'Arrastre sus archivos <span>o CLICK AQUÍ</span>',
            maxFilesize: 5,
            acceptedFiles: ".xlsx,.xls,.docx,.doc,.pptx,.ppt,.pdf",
            init: function() {
            this.on("success", function(file) {
                var perfil  = JSON.parse(Cookies.get('perfil'));
                var action  = 'borrar';
                var block   = '#archivos';
                post_data(perfil.codigo,action,block);
            });
        }});

        $(document).on( "click",".editar", function() {
            var idb         =   $(this).attr('idb');
            var titulo      =   $(this).attr('titulo');
            var anterior    =   $(this).attr('anterior');
            $.post( "core.php?l=editFileForm", { codigo:idb,titulo:titulo,anterior:anterior}, function(data){
                $('#mensaje').html(''); 
                $("#editFileForm").html(data.html);
                $("#boton").html('<button type="button" class="btn btn-warning"> Actualizar</button>');
                $('#editar').modal(); 
                var fileExtension = "";
                $('input[name=archivo]').change(function(){
                    var file = $("#imagen")[0].files[0];
                    var fileName = file.name;
                    fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
                    var fileSize = file.size;
                    var fileType = file.type;
                    $('#mensaje').html("<span class='info'>Archivo para subir: "+fileName+", peso total: "+fileSize+" bytes.</span>");
                });
                $(".file-styled").uniform({
                    fileButtonClass: 'action btn btn-default'
                });
           }, "json");
        });

        $('#boton').click(function(){
            var formData = new FormData($(".formulario")[0]);
            var message = ""; 
            $.ajax({
                url: 'core.php?l=updatefile',  
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function(){
                    $('#mensaje').html("<span class='before'>Subiendo, por favor espere...</span>");        
                },
                success: function(data){
                    $('#mensaje').html("<span class='success'>El archivo ha subido correctamente.</span>");
                },
                error: function(){
                    $('#mensaje').html("<span class='error'>Ha ocurrido un error.</span>");
                }
            });
        });

        $("#editar").on('hidden.bs.modal', function () {
            var perfil  = JSON.parse(Cookies.get('perfil'));
            var action  = 'borrar';
            var block   = '#archivos';
            post_data(perfil.codigo,action,block);
            $('#tblarchivos').DataTable();
        });

    });

    function post_data(codigo, action, block){
        $(block).block({
            message: '<i class="icon-spinner2 spinner"></i>',
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait',
                'box-shadow': '0 0 0 1px #ddd'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'none'
            }
        });
        if(action === undefined){
            window.setTimeout(function () {
               $(block).unblock();
            }, 2000);
        }else{
            $.post( "core.php?l=verarchivos", { codigo: codigo, action:action }, function( data ) {  
                $("#resultado1").html(data.html);
                window.setTimeout(function () {
                    $('#tblarchivos').DataTable();
                   $(block).unblock();
                }, 500);
           }, "json");
        }
    }