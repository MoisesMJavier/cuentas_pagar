
	
	//$.fn.datepicker.defaults.dates = "es";
	
	function formatMoney( n ) {
	  var c = isNaN(c = Math.abs(c)) ? 2 : c,
		d = d == undefined ? "." : d,
		t = t == undefined ? "," : t,
		s = n < 0 ? "-" : "",
		i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))),
		j = (j = i.length) > 3 ? j % 3 : 0;

	  return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	};

	$(document).ajaxStart( $.blockUI ).ajaxStop( $.unblockUI )/*.ajaxError( function( event, jqxhr, settings, thrownError ){
		
		location.reload();
		
		console.log( event );
		alert( jqxhr.url );
				console.log( jqxhr );
						console.log( settings );
								console.log( thrownError );
		
	})*/;

	var lenguaje = {
        "decimal":        "",
        "emptyTable":     "No hay ningún dato.",
        "info":           "Mostrando _START_ de _END_. _TOTAL_ datos.",
        "infoEmpty":      "Mostrando 0 de 0. 0 entries",
        "infoFiltered":   "(filtered from _MAX_ total entries)",
        "infoPostFix":    "",
        "thousands":      ",",
        "lengthMenu":     "Mostrando _MENU_ entries",
        "loadingRecords": "...",
        "processing":     "Espere un momento...",
        "search":         "Buscar:",
        "zeroRecords":    "No se encontraron coincidencias.",
        "paginate": {
			"first":      "PRIMERO",
            "last":       "ULTIMO",
            "next":       "SIGUIENTE",
            "previous":   "ANTERIOR"
        },
        "aria": {
			"sortAscending":  ": activate to sort column ascending",
            "sortDescending": ": activate to sort column descending"
        }
    };
	
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust();
        //.responsive.recalc();
    });

    //DESACTIVAMOS EL SCROLL EN EL INPUT NUMBER PARA NO INCREMENTAR EL NUMERO
    $( document ).on('focus', 'input[type=number]', function (e) {
        $(this).on('wheel.disableScroll', function (e) {
            e.preventDefault()
        });
    });

    $( document ).on('blur', 'input[type=number]', function (e) {
        $(this).off('wheel.disableScroll')
    });

    jQuery.extend(jQuery.validator.messages, {
        required: "Requerido",
        remote: "Please fix this field.",
        email: "Correo inválido",
        url: "Please enter a valid URL.",
        date: "Fecha inválida",
        dateISO: "Please enter a valid date (ISO).",
        number: "Numero inválido",
        digits: "Please enter only digits.",
        creditcard: "Please enter a valid credit card number.",
        equalTo: "Please enter the same value again.",
        accept: "Please enter a value with a valid extension.",
        maxlength: jQuery.validator.format("No mas de {0} dígitos."),
        minlength: jQuery.validator.format("Al menos {0} dígitos."),
        rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
        range: jQuery.validator.format("Please enter a value between {0} and {1}."),
        max: jQuery.validator.format("Menor a {0}"),
        min: jQuery.validator.format("Mayor a {0}")
    });
    
    $('.faq').popover();
        
        
        /*$(".lista_empresa").ready( function(){
            $(".lista_empresa").append('<option value="">Seleccione una opción</option>');
            $.getJSON( url + "Listas_select/lista_empresas").done( function( data ){
                $.each( data, function( i, v){
                    $(".lista_empresa").append('<option value="'+v.idempresa+'" data-value="'+v.rfc+'">'+v.nombre+'</option>');
                });
            });
        });*/
/*
        $(".lista_provedores").ready( function(){
            $(".lista_provedores").append('<option value="">Seleccione una opción</option>');
            $.getJSON( url + "Listas_select/lista_provedores").done( function( data ){
                $.each( data, function( i, v){
                    $(".lista_provedores").append('<option value="'+v.idproveedor+'" data-value="'+v.rfc+'">'+v.nombre+'</option>');
                });
            });
        });
        
		
        $(".lista_autocompletar_proyectos").ready( function(){
            var proyectos = []
            $.getJSON( url + "Listas_select/lista_proyectos").done( function( data ){
                $.each( data, function( i, v){
                    proyectos.push( v.proyecto );
                });

                $(".lista_autocompletar_proyectos").autocomplete({
                    source: proyectos
                });
            });
        });

        $(".lista_proyectos").ready( function(){
            var proyectos = []
            $.getJSON( url + "Listas_select/lista_proyectos").done( function( data ){
                $(".lista_proyectos").append('<option value="">Seleccione una opción</option>');
                $.each( data, function( i, v){
                    $(".lista_proyectos").append('<option value="'+v.proyecto+'">'+v.proyecto+'</option>');
                });
            });
        });
        */

    $(document).on("click", ".consultar_modal", function(){

        $( this ).children(".badge").remove();
        
        var idsol = ($(this).val()!="") ? $(this).val() :$("#idsol").val();
        var tipov = ($(this).attr("data-value")!="") ?  $(this).attr("data-value") :$("input:radio[name=idopt]:checked").val();
       if(idsol != ""){
        $.get( url + "Consultar/solicitud/" + idsol + "/" + tipov, "html" ).done( function( data ){
            if(data){
             $("#consultar_modal .modal-content").html( data );
             $("#consultar_modal").modal();
            }else{
             alert("No se encontro ninguna solicitud");
            }
         });
       } 
      return false;
       /* $.get( url + "Consultar/solicitud/" + $(this).val() + "/" + $(this).attr("data-value"), "html" ).done( function( data ){
            $("#consultar_modal .modal-content").html( data );
            $("#consultar_modal").modal();
        });*/
    });
    
    $(".duplicidad_usuario").change( function(){
        var elemento = $(this);
        $.post( url + "Consultar/usuario_disponible", { usuario : $( this ).val() } ).done( function( data ){
            if( data == 1 ){
                elemento.val('');
                $("#duplicidad .modal-body").html("<p class='text-center'>Este usuario ya existe intente con otro diferente.</p>");
                $("#duplicidad").modal();
            }
            
        }).fail( function (){
            alert("ERROR");
        });
    });

    $(".duplicidad_correo").change( function(){
        var elemento = $(this);
        $.post( url + "Consultar/correo_disponible", { correo : $( this ).val() } ).done( function( data ){

            if( data == 1 ){
                elemento.val('');
                $("#duplicidad .modal-body").html("<p class='text-center'>Este correo ya existe intente con otro diferente.</p>");
                $("#duplicidad").modal();
            }
            

        }).fail( function (){
            alert("ERROR");
        });
    });

    $(".solo_letras_numeros").keypress( function ( key ) {
        if ( 
            (key.charCode < 97 || key.charCode > 122)//letras mayusculas
            && (key.charCode < 65 || key.charCode > 90) //letras minusculas
            && (key.charCode < 47 || key.charCode > 57 ) //numeros del 0 - 9
            && (key.charCode != 45) //retroceso 
        )
            return false;
    });
	
	//SIEMPRE EL FORMATO DE FECHA DE YYYY-MM-DD
	function formato_fechaymd( fecha ){
		if( fecha ){
			var meses = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
			return fecha.substring(8,10)+"/"+(parseInt(fecha.substring(5,7))!=0? meses[ parseInt( fecha.substring(5,7) - 1 ) ]:"00")+"/"+fecha.substring(0,4);
		}else{
			return '-';
		}
    }

    //SIEMPRE EL FORMATO DE FECHA DE DD-MM-YYYY
    function dateToDMY( date ) {
        var d = date.getDate();
        var m = date.getMonth() + 1; //Month from 0 to 11
        var y = date.getFullYear();
        var short = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"]
        if(date)
            return ''  + (d <= 9 ? '0' + d : d) + '/' + short[date.getMonth()] + '/' + y + ( date.length == 19 ? date.substr( date.length - 9 ) : "" ) ;
        else
            return '--/--/--';
    }
    
    $(document).ajaxError(function myErrorHandler(event, xhr, ajaxOptions, thrownError) {
        swal({
            title: "¡ERROR!",
            text: "Ha ocurrido un error en el proceso",
            icon: "error",
            button: "Aceptar",
        });
    });
    
    function alert(msj){
        msj+="";
        swal({
            //title:  msj.includes("error")?"¡ERROR!":"¡ÉXITO!",
            text: msj,
            icon: msj.includes("error")?"error":"info",
            button: "Aceptar",closeOnClickOutside: false,
        });
    }

    function enviar_post2(callback,d,url) {
        $.ajax({
            url: url,
            data: d,
            dataType: "json",
            type: "POST",
            processData: false,
            contentType: false,
            success: function (resp) {
              callback(resp);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
              callback({respuesta:false,msj:textStatus})
            }
        });
    }

    function enviar_post_64( callback, d, url ) {
        $.ajax({
            url: url,
            data: d,
            dataType: "json",
            type: "POST",
            processData: false,
            contentType: 'application/json',
            data: btoa( JSON.stringify( d ) ),
            success: function (resp) {
              callback(resp);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
              callback({respuesta:false,msj:textStatus})
            }
        });
    }

    /*
    $('select').on('select2:close', function (evt) {
        var uldiv = $(this).siblings('span.select2').find('ul')
        var count = $(this).select2('data').length
        if(count==0){
            uldiv.html("")
        }
        else{
            if( count > 1 ){
                uldiv.html("<li style='line-height: 24px; padding: 4px;'>"+count+" seleccionados</li>")
            }
        }
    });
    */
    
    function obtenerTiposMonedas() {
        return [
            {id: 1, codigo: "MXN", nombre: 'Peso mexicano', simbolo: '$'},
            {id: 2, codigo: "USD", nombre: 'Dólar Estadounidense', simbolo: 'US$'},
            {id: 3, codigo: "EUR", nombre: 'Euro', simbolo: '€'}
        ]
    }