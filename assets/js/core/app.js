
    $(window).on('load', function() {
        $('body').removeClass('no-transitions');
    });

    function cargarcookie(){
        if(Cookies.get('UNIDAD')){
            var unidad = Cookies.get('UNIDAD');
        }else{
            var unidad = 0;
        }
        if(unidad == 1){
            $('#verUNIDAD').html(unidad+"ra. Unidad");
            $('#verUNIDAD1').attr('code',unidad);
        }else if(unidad == 2){
            $('#verUNIDAD').html(unidad+"da. Unidad");
            $('#verUNIDAD1').attr('code',unidad);
        }else if(unidad == 3){
            $('#verUNIDAD').html(unidad+"ra. Unidad");
            $('#verUNIDAD1').attr('code',unidad);
        }else if(unidad == 4){
            $('#verUNIDAD').html(unidad+"ta. Unidad");
            $('#verUNIDAD1').attr('code',unidad);
        }
        desbloquea(unidad);
        return unidad;
    }
        
    function bimestre(){
        if(Cookies.get('UNIDAD')){
            var unidad = Cookies.get('UNIDAD');
        }else{
            var unidad = 0;
        }
        if(unidad == 1){
            $('#verUNIDAD').html(unidad+"ra. Unidad");
            $('#verUNIDAD1').html(unidad);
        }else if(unidad == 2){
            $('#verUNIDAD').html(unidad+"da. Unidad");
            $('#verUNIDAD1').html(unidad);
        }else if(unidad == 3){
            $('#verUNIDAD').html(unidad+"ra. Unidad");
            $('#verUNIDAD1').html(unidad);
        }else if(unidad == 4){
            $('#verUNIDAD').html(unidad+"ta. Unidad");
            $('#verUNIDAD1').html(unidad);
        }
        desbloquea(unidad);
        swal("Usted seleccionó la unidad numero: "+unidad);
        setTimeout( location.reload(), 2000 );
    }
       
    function desbloquea(unidad){
        if(unidad>0){
            $(".bloqueo").show();
            $(".msg").html("");

        }else{
            $(".bloqueo").hide();
            $(".msg").html('<b>Debe seleccionar unidad a trabajar</b>');
        }
    }

    $(function() {

        $('body').addClass('no-transitions');

        cargarcookie();

        $('#ver').click(function(){
            var pathname = window.location.pathname;
            alert(window.location);
        });

        $('.cosa').click(function(){
            $('.cosa').removeClass( "active" );
           $(this).addClass( "active" );
        });        
        
        $('.unidad').click(function( event ){
            event.preventDefault();
            var unidad = $(this).attr('unidad');
            Cookies.set('UNIDAD', unidad);
            bimestre();
        });

        function containerHeight() {
            var availableHeight = $(window).height() - $('.page-container').offset().top - $('.navbar-fixed-bottom').outerHeight();

            $('.page-container').attr('style', 'min-height:' + availableHeight + 'px');
        }

        containerHeight();

        $('.panel-footer').has('> .heading-elements:not(.not-collapsible)').prepend('<a class="heading-elements-toggle"><i class="icon-more"></i></a>');
        $('.page-title, .panel-title').parent().has('> .heading-elements:not(.not-collapsible)').children('.page-title, .panel-title').append('<a class="heading-elements-toggle"><i class="icon-more"></i></a>');
        $('.page-title .heading-elements-toggle, .panel-title .heading-elements-toggle').on('click', function() {
            $(this).parent().parent().toggleClass('has-visible-elements').children('.heading-elements').toggleClass('visible-elements');
        });
        $('.panel-footer .heading-elements-toggle').on('click', function() {
            $(this).parent().toggleClass('has-visible-elements').children('.heading-elements').toggleClass('visible-elements');
        });

        // Add control button toggler to breadcrumbs if has elements
        $('.breadcrumb-line').has('.breadcrumb-elements').prepend('<a class="breadcrumb-elements-toggle"><i class="icon-menu-open"></i></a>');

        // Toggle visible state of breadcrumb elements
        $('.breadcrumb-elements-toggle').on('click', function() {
            $(this).parent().children('.breadcrumb-elements').toggleClass('visible-elements');
        });

        // Prevent dropdown from closing on click
        $(document).on('click', '.dropdown-content', function (e) {
            e.stopPropagation();
        });

        // Disabled links
        $('.navbar-nav .disabled a').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
        });

        // Show tabs inside dropdowns
        $('.dropdown-content a[data-toggle="tab"]').on('click', function (e) {
            $(this).tab('show');
        });


        // Panels
        $('.panel [data-action=reload]').click(function (e) {
            e.preventDefault();
            var perfil = JSON.parse(Cookies.get('perfil'));
            var action = $(this).attr('source');
            var block = $(this).parent().parent().parent().parent().parent();
            post_data(codigo,action,block);
        });

        $(document).on('click touchstart', '.borrar', function(event){
            event.preventDefault();
            var perfil  = JSON.parse(Cookies.get('perfil'));
            var action  = $(this).attr('source');
            var id      = $(this).attr('idb');
            var block   = '#archivos';

            if(action == 'del_file'){
                $.post( "core.php?l=borrar_archivo", { id: id }, function( data ) {  
                    post_data(perfil.codigo,action,block); 
                    window.setTimeout(function () {
                       $(block).unblock();
                       $('#tblarchivos').DataTable();
                    }, 500);
               }, "json");
            }
        });

        $(document).on('click touchstart', '.verDOC', function(event){
            event.preventDefault();
            var url = $(this).attr('rel');
            $('#iframe').attr('src', url);
            $("#modal_remote").modal();
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
                $.post( "core.php?l=verarchivos", { codigo: perfil.codigo, action:action }, function( data ) {  
                    $("#resultado1").html(data.html);
                    window.setTimeout(function () {
                       $(block).unblock();
                    }, 500);
               }, "json");
            }
        }

        $('.category-title [data-action=reload]').click(function (e) {
            e.preventDefault();
            var block = $(this).parent().parent().parent().parent();
            $(block).block({ 
                message: '<i class="icon-spinner2 spinner"></i>',
                overlayCSS: {
                    backgroundColor: '#000',
                    opacity: 0.5,
                    cursor: 'wait',
                    'box-shadow': '0 0 0 1px #000'
                },
                css: {
                    border: 0,
                    padding: 0,
                    backgroundColor: 'none',
                    color: '#fff'
                }
            });

            // For demo purposes
            window.setTimeout(function () {
               $(block).unblock();
            }, 2000); 
        }); 

        $('.sidebar-default .category-title [data-action=reload]').click(function (e) {
            e.preventDefault();
            var block = $(this).parent().parent().parent().parent();
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

            // For demo purposes
            window.setTimeout(function () {
               $(block).unblock();
            }, 2000); 
        }); 

        // Hide if collapsed by default
        $('.category-collapsed').children('.category-content').hide();

        // Rotate icon if collapsed by default
        $('.category-collapsed').find('[data-action=collapse]').addClass('rotate-180');

        // Collapse on click
        $('.category-title [data-action=collapse]').click(function (e) {
            e.preventDefault();
            var $categoryCollapse = $(this).parent().parent().parent().nextAll();
            $(this).parents('.category-title').toggleClass('category-collapsed');
            $(this).toggleClass('rotate-180');
            containerHeight(); // adjust page height
            $categoryCollapse.slideToggle(150);
        });

        // Hide if collapsed by default
        $('.panel-collapsed').children('.panel-heading').nextAll().hide();

        // Rotate icon if collapsed by default
        $('.panel-collapsed').find('[data-action=collapse]').addClass('rotate-180');

        // Collapse on click
        $('.panel [data-action=collapse]').click(function (e) {
            e.preventDefault();
            var $panelCollapse = $(this).parent().parent().parent().parent().nextAll();
            $(this).parents('.panel').toggleClass('panel-collapsed');
            $(this).toggleClass('rotate-180');
            containerHeight(); // recalculate page height
            $panelCollapse.slideToggle(150);
        });

        // Panels
        $('.panel [data-action=close]').click(function (e) {
            e.preventDefault();
            var $panelClose = $(this).parent().parent().parent().parent().parent();
            containerHeight(); // recalculate page height
            $panelClose.slideUp(150, function() {
                $(this).remove();
            });
        });

        // Sidebar categories
        $('.category-title [data-action=close]').click(function (e) {
            e.preventDefault();
            var $categoryClose = $(this).parent().parent().parent().parent();
            containerHeight(); // recalculate page height
            $categoryClose.slideUp(150, function() {
                $(this).remove();
            });
        });

        // Add 'active' class to parent list item in all levels
        $('.navigation').find('li.active').parents('li').addClass('active');

        // Hide all nested lists
        $('.navigation').find('li').not('.active, .category-title').has('ul').children('ul').addClass('hidden-ul');

        // Highlight children links
        $('.navigation').find('li').has('ul').children('a').addClass('has-ul');

        // Add active state to all dropdown parent levels
        $('.dropdown-menu:not(.dropdown-content), .dropdown-menu:not(.dropdown-content) .dropdown-submenu').has('li.active').addClass('active').parents('.navbar-nav .dropdown:not(.language-switch), .navbar-nav .dropup:not(.language-switch)').addClass('active');

        // Left sidebar
        $('.navigation-main > .navigation-header > i').tooltip({
            placement: 'right',
            container: 'body'
        });

        // Main navigation
        $('.navigation-main').find('li').has('ul').children('a').on('click', function (e) {
            e.preventDefault();
            $(this).parent('li').not('.disabled').not($('.sidebar-xs').not('.sidebar-xs-indicator').find('.navigation-main').children('li')).toggleClass('active').children('ul').slideToggle(250);
            if ($('.navigation-main').hasClass('navigation-accordion')) {
                $(this).parent('li').not('.disabled').not($('.sidebar-xs').not('.sidebar-xs-indicator').find('.navigation-main').children('li')).siblings(':has(.has-ul)').removeClass('active').children('ul').slideUp(250);
            }
        });
     
        // Alternate navigation
        $('.navigation-alt').find('li').has('ul').children('a').on('click', function (e) {
            e.preventDefault();

            // Collapsible
            $(this).parent('li').not('.disabled').toggleClass('active').children('ul').slideToggle(200);

            // Accordion
            if ($('.navigation-alt').hasClass('navigation-accordion')) {
                $(this).parent('li').not('.disabled').siblings(':has(.has-ul)').removeClass('active').children('ul').slideUp(200);
            }
        }); 

        // Toggle mini sidebar
        $('.sidebar-main-toggle').on('click', function (e) {
            e.preventDefault();
            // Toggle min sidebar class
            $('body').toggleClass('sidebar-xs');
        });

        // Disable click in disabled navigation items
        $(document).on('click', '.navigation .disabled a', function (e) {
            e.preventDefault();
        });

        // Adjust page height on sidebar control button click
        $(document).on('click', '.sidebar-control', function (e) {
            containerHeight();
        });

        // Hide main sidebar in Dual Sidebar
        $(document).on('click', '.sidebar-main-hide', function (e) {
            e.preventDefault();
            $('body').toggleClass('sidebar-main-hidden');
        });

        // Toggle second sidebar in Dual Sidebar
        $(document).on('click', '.sidebar-secondary-hide', function (e) {
            e.preventDefault();
            $('body').toggleClass('sidebar-secondary-hidden');
        });

        // Hide all sidebars
        $(document).on('click', '.sidebar-all-hide', function (e) {
            e.preventDefault();

            $('body').toggleClass('sidebar-all-hidden');
        });

        // Collapse main sidebar if opposite sidebar is visible
        $(document).on('click', '.sidebar-opposite-toggle', function (e) {
            e.preventDefault();

            // Opposite sidebar visibility
            $('body').toggleClass('sidebar-opposite-visible');

            // If visible
            if ($('body').hasClass('sidebar-opposite-visible')) {

                // Make main sidebar mini
                $('body').addClass('sidebar-xs');

                // Hide children lists
                $('.navigation-main').children('li').children('ul').css('display', '');
            }
            else {

                // Make main sidebar default
                $('body').removeClass('sidebar-xs');
            }
        });

        // Hide main sidebar if opposite sidebar is shown
        $(document).on('click', '.sidebar-opposite-main-hide', function (e) {
            e.preventDefault();

            // Opposite sidebar visibility
            $('body').toggleClass('sidebar-opposite-visible');
            
            // If visible
            if ($('body').hasClass('sidebar-opposite-visible')) {

                // Hide main sidebar
                $('body').addClass('sidebar-main-hidden');
            }
            else {

                // Show main sidebar
                $('body').removeClass('sidebar-main-hidden');
            }
        });

        // Hide secondary sidebar if opposite sidebar is shown
        $(document).on('click', '.sidebar-opposite-secondary-hide', function (e) {
            e.preventDefault();

            // Opposite sidebar visibility
            $('body').toggleClass('sidebar-opposite-visible');

            // If visible
            if ($('body').hasClass('sidebar-opposite-visible')) {

                // Hide secondary
                $('body').addClass('sidebar-secondary-hidden');

            }
            else {

                // Show secondary
                $('body').removeClass('sidebar-secondary-hidden');
            }
        });

        // Hide all sidebars if opposite sidebar is shown
        $(document).on('click', '.sidebar-opposite-hide', function (e) {
            e.preventDefault();

            // Toggle sidebars visibility
            $('body').toggleClass('sidebar-all-hidden');

            // If hidden
            if ($('body').hasClass('sidebar-all-hidden')) {

                // Show opposite
                $('body').addClass('sidebar-opposite-visible');

                // Hide children lists
                $('.navigation-main').children('li').children('ul').css('display', '');
            }
            else {

                // Hide opposite
                $('body').removeClass('sidebar-opposite-visible');
            }
        });

        // Keep the width of the main sidebar if opposite sidebar is visible
        $(document).on('click', '.sidebar-opposite-fix', function (e) {
            e.preventDefault();

            // Toggle opposite sidebar visibility
            $('body').toggleClass('sidebar-opposite-visible');
        });

        // Toggle main sidebar
        $('.sidebar-mobile-main-toggle').on('click', function (e) {
            e.preventDefault();
            $('body').toggleClass('sidebar-mobile-main').removeClass('sidebar-mobile-secondary sidebar-mobile-opposite');
        });

        // Toggle secondary sidebar
        $('.sidebar-mobile-secondary-toggle').on('click', function (e) {
            e.preventDefault();
            $('body').toggleClass('sidebar-mobile-secondary').removeClass('sidebar-mobile-main sidebar-mobile-opposite');
        });

        // Toggle opposite sidebar
        $('.sidebar-mobile-opposite-toggle').on('click', function (e) {
            e.preventDefault();
            $('body').toggleClass('sidebar-mobile-opposite').removeClass('sidebar-mobile-main sidebar-mobile-secondary');
        });

        $(window).on('resize', function() {
            setTimeout(function() {
                containerHeight();
                
                if($(window).width() <= 768) {

                    // Add mini sidebar indicator
                    $('body').addClass('sidebar-xs-indicator');

                    // Place right sidebar before content
                    $('.sidebar-opposite').prependTo('.page-content');

                    // Add mouse events for dropdown submenus
                    $('.dropdown-submenu').on('mouseenter', function() {
                        $(this).children('.dropdown-menu').addClass('show');
                    }).on('mouseleave', function() {
                        $(this).children('.dropdown-menu').removeClass('show');
                    });
                }
                else {

                    // Remove mini sidebar indicator
                    $('body').removeClass('sidebar-xs-indicator');

                    // Revert back right sidebar
                    $('.sidebar-opposite').insertAfter('.content-wrapper');

                    // Remove all mobile sidebar classes
                    $('body').removeClass('sidebar-mobile-main sidebar-mobile-secondary sidebar-mobile-opposite');

                    // Remove visibility of heading elements on desktop
                    $('.page-header-content, .panel-heading, .panel-footer').removeClass('has-visible-elements');
                    $('.heading-elements').removeClass('visible-elements');

                    // Disable appearance of dropdown submenus
                    $('.dropdown-submenu').children('.dropdown-menu').removeClass('show');
                }
            }, 100);
        }).resize();

        $( document ).ajaxStart(function() {
            $.blockUI({
                message: '<i class="icon-spinner4 spinner"></i><br />SAE SYSTEM',
                overlayCSS: {
                    backgroundColor: '#1b2024',
                    opacity: 0.8,
                    zIndex: 1200,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    color: '#fff',
                    padding: 0,
                    zIndex: 1201,
                    backgroundColor: 'transparent'
                }
            });
        }).ajaxStop($.unblockUI);

        var perfil = JSON.parse(Cookies.get('perfil'));
        $("#codigo").attr("code",perfil.codigo);
    });
