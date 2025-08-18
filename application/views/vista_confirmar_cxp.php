<?php
require("head.php");
require("menu_navegador.php");
?> 
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col">
          <div class="box">
            <div class="box-header with-border">
              <h3>PAGOS AUTORIZADOS</h3>
            </div>
            <div class="box-body">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a id="profile-tab" data-toggle="tab" data-url="Cuentasxp/ver_datos_finales" href="#pagos_autoriza_dg_trans" role="tab" aria-controls="#home" aria-selected="true">CONFIRMAR PAGOS GENERALES</a></li>
                  <li><a id="profile-tab" data-toggle="tab" data-url="Cuentasxp/ver_datos_finales_ch" href="#pagos_autoriza_dg_cheques" role="tab" aria-controls="pagos_autoriza_dg_cheques" aria-selected="false">CONFIRMAR PAGOS CAJA CHICA </a></li>
                  <li><a id="profile-tab" data-toggle="tab" data-url="Cuentasxp/ver_datos_finales_tdc" href="#pagos_autoriza_dg_cheques" role="tab" aria-controls="pagos_autoriza_dg_cheques" aria-selected="false">CONFIRMAR PAGOS TARJETAS DE CREDITO </a></li>
                </ul>
              </div>
              <div class="tab-content">
                <div class="active tab-pane" id="pagos_autoriza_dg_trans">
                  <div class="row">
                    <div class="col-lg-12">
                        <h4><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_last" id="myText_last"></label></h4>
                        <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_pagadas2" name="tabla_pagadas2">
                          <thead class="thead-dark">
                            <tr>
                              <th style="font-size: .8em"># SOLICITUD</th>
                              <th style="font-size: .8em"># PAGO</th>
                              <th style="font-size: .8em">PROVEEDOR</th>
                              <th style="font-size: .8em">FOLIO FACT</th>
                              <th style="font-size: .8em">CANTIDAD</th>
                              <th style="font-size: .8em">DEPARTAMENTO</th>
                              <th style="font-size: .8em">EMPRESA</th>
                              <th style="font-size: .8em">METODO PAGO</th>
                              <th style="font-size: .8em">FECHA DISPERSIÓN</th>
                              <th style="font-size: .8em"></th> 
                            </tr>
                          </thead>
                        </table>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="pagos_autoriza_dg_cheques">
                  <div class="row">
                    <div class="col-lg-12">
                      <h4><label>&nbsp;Total: $<input style="border-bottom: none; border-top: none; border-right: none;  border-left: none; background: white;" disabled="disabled" readonly="readonly" type="text"  name="myText_3_last" id="myText_3_last"></label></h4>
                      <table class="table table-responsive table-bordered table-striped table-hover"  id="tabla_pagadas3" name="tabla_pagadas3">
                        <thead class="thead-dark">
                          <tr>
                            <th style="font-size: .8em">ID PAGO</th>                <!-- COLUMNA[ 0 ] -->
                            <th style="font-size: .8em">RESPONSABLE</th>            <!-- COLUMNA[ 1 ] -->
                            <th style="font-size: .8em">RESPONSABLE REEMBOLSO</th>  <!-- COLUMNA[ 2 ] --> <!-- INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
                            <th style="font-size: .8em">CANTIDAD</th>               <!-- COLUMNA[ 3 ] -->
                            <th style="font-size: .8em">DEPARTAMENTO</th>           <!-- COLUMNA[ 4 ] -->
                            <th style="font-size: .8em">EMPRESA</th>                <!-- COLUMNA[ 5 ] -->
                            <th style="font-size: .8em">MET. PAGO SOLICITADO</th>   <!-- COLUMNA[ 6 ] -->
                            <th style="font-size: .8em">FECHA DISPERSIÓN</th>       <!-- COLUMNA[ 7 ] -->
                            <th style="font-size: .8em"></th>                       <!-- COLUMNA[ 8 ] -->
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                </div> <!--End solicitudes finalizadas-->
                <div class="tab-pane fade" id="pagos_devoluciones_parciales">
								<div class="row">
									<div class="col-lg-12">
										<h4>DEVOLUCIONES EN PARCIALIDADES
										  <table class="table table-responsive table-bordered table-striped table-hover" id="tabla_pagadas5" name="tabla_devoluciones_parciales">
											  <thead class="thead-dark">
                          <tr>
                            <th style="font-size: .8em">#</th>
                            <th style="font-size: .8em">PERIODOS</th>
                            <th style="font-size: .8em">PROVEEDOR</th>
                            <th style="font-size: .8em">TOTAL CUOTA</th>
                            <th style="font-size: .8em">FORMA PAGO</th>
                            <th style="font-size: .8em">CUOTA</th>
                            <th style="font-size: .8em">TOTAL PARCIAL</th>
                            <th style="font-size: .8em">TOTAL PAGADO</th>
                            <th style="font-size: .8em"># PAGO</th>
                            <th style="font-size: .8em">FECHA CAPTURA</th>
                            <th style="font-size: .8em">FEC TERMINO</th>
                            <th style="font-size: .8em">PRX FECHA</th>
                            <th style="font-size: .8em">EMPRESA</th> 
                            <th style="font-size: .8em">ESTATUS</th> 
                            <th style="font-size: .8em"></th> 
                          </tr>
											  </thead>
										  </table>
									  </div>
								  </div>
							  </div>  
              </div>
            </div><!--End tab content-->
          </div><!--end box-->
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade modal-alertas" id="myModalpoliza" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header"  style="background: #00A65A; color: #fff;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">CONFIRMAR PAGO</h4>
        </div>  
        <form method="post" id="infopago_polizad">
          <div class="modal-body"></div>
          <div class="modal-footer"></div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade modal-alertas" id="myModalpoliza_ch" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header"  style="background: #00A65A; color: #fff;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">CONFIRMAR PAGO</h4>
        </div>  
        <form method="post" id="infopago_poliza_chica">
          <div class="modal-body"></div>
          <div class="modal-footer"></div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade modal-alertas" id="myModalcomentario1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header"  style="background: #DD4B39; color: #fff;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Motivo de rechazo</h4>
        </div>  
        <form method="post" id="infosol1">
          <div class="modal-body">   
          </div>
          <div class="modal-footer">
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade modal-alertas" id="myModalcomentario3" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header"  style="background: #DD4B39; color: #fff;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Motivo de rechazo</h4>
        </div>  
        <form method="post" id="infosol3">
          <div class="modal-body">   
          </div>
          <div class="modal-footer">
          </div>
        </form>
      </div>
    </div>
  </div>
<script>

  var tota2=0;
  let activeTab;  /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
  $('[data-toggle="tab"]').click( function(e) {
      activeTab = $(this).data('value');
      switch( $(this).data('url') ){
        case 'Cuentasxp/ver_datos_finales':
          tabla_4.ajax.url( url +"Cuentasxp/ver_datos_finales" ).load();
          break;
        case 'Cuentasxp/ver_datos_finales_ch':
          tabla_3.ajax.url( url +"Cuentasxp/ver_datos_finales_ch" ).load();
          activeTab = 'cch'; /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
          tabla_3.column(2).visible(true); /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
          break;
        case 'Cuentasxp/ver_datos_finales_tdc':
          tabla_3.ajax.url( url +"Cuentasxp/ver_datos_finales_tdc" ).load();
          activeTab = 'tdc'; /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
          tabla_3.column(2).visible(false); /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        break;
      }
  });

  $('select#cuenta_valor').on('change',function(){
    // $(".cuenta_valor").html("");
    var valor = $(this).val();

    pag = document.getElementById("numpago").value;
    // alert(pag);
    if (valor !="ECHQ" ) {
      $.getJSON( url + "Cuentasxp/lista_cta"+"/"+pag).done( function( data ){
        $.each( data, function( i, v){
          document.getElementById("serie_cheque").value = v.serie;
        });
      });
    }
  });

  $("#tabla_pagadas2").ready( function () {
    $('#tabla_pagadas2 thead tr:eq(0) th').each( function (i) {
      if( i != 9 ){
        var title = $(this).text();
        $(this).html( '<input type="text" style="width:100%;" placeholder="'+title+'" />' );
        $('input', this).on('keyup change', function() {
          if (tabla_4.column(i).search() !== this.value ) {
            tabla_4
            .column(i)
            .search( this.value )
            .draw();

            var total = 0;
            var index = tabla_4.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tabla_4.rows( index ).data();

            $.each(data, function(i, v){
              total += parseFloat(v.CA);
            });
            var to1 = formatMoney(total);
            document.getElementById("myText_last").value = formatMoney(total);
          }
        });
      }
    });

    $('#tabla_pagadas2').on('xhr.dt', function ( e, settings, json, xhr ) {
      var total = 0;
      $.each(json.data, function(i, v){
        total += parseFloat(v.CA);
      });
      var to = formatMoney(total);
      document.getElementById("myText_last").value = to;
    });

    tabla_4 = $("#tabla_pagadas2").DataTable({
      dom: 'Brtip',
        "buttons": [
          {
            extend: 'excelHtml5',             
            text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
            messageTop: "Lista de solicitudes por autorizar",
            attr: {
              class: 'btn btn-success'       
            },
            exportOptions: {
              columns: [0, 1, 2, 3, 4, 5, 6, 7],
              format: {
                header: function (data, columnIdx) { 
                  data = data.replace( '<input type="text" style="width:100%;" placeholder="', '' );
                  data = data.replace( '">', '' )
                  return data;
                }
              }
            } 
          }],
          "language":lenguaje,
          "processing": false,
          "pageLength": 10,
          "bAutoWidth": false,
          "bLengthChange": false,
          "ordering": false,
          "bInfo": false,
          "searching": true,
          "scrollX": true,
          "columns": [
            {
              "width": "7%",
              "data": function( d ){
                return "<p style='font-size: .8em'>"+d.idsolicitud+"</p>";
              }
            },
            {
              "width": "7%",
              "data": function( d ){
                return "<p style='font-size: .8em'>"+d.idpago+"</p>";
              }
            },
            {
              "width": "15%",
              "data": function( d ){
                return '<p style="font-size: .7em">'+d.nombre+'</p>' + (
                        d.esParcialidad == 'S'
                            ? "<br><small class='label pull-center bg-blue'>PARCIALIDADES</small>" 
                            : d.programado && d.programado > 0 
                                ? "<br><small class='label pull-center bg-blue'>PROGRAMADO</small>" 
                                : "" )
              }
            },
            {
              "width": "4%",
              "data": function( d ){
                return "<p style='font-size: .8em'>"+d.folio+"</p>";
              }
            },
            {
              "width": "10%",
              "data": function( d ){
                return "<p style='font-size: .8em'>$ "+formatMoney( d.CA )+"</p>";
              }
            },
            {  
              "width": "12%",
              "data": function( d ){
                return "<p style='font-size: .8em'>"+d.nomdepto+"</p>";
              }
            },
            { 
              "width": "5%",
              "data": function( d ){
                return "<p style='font-size: .8em'>"+d.abrev+"</p>";
              }
            },
            {
              "width": "8%", 
              "data": function( d ){
                return "<p style='font-size: .8em'>"+d.metoPago+"</p>"+( d.prioridad == 1 ? "<small class='label pull-center bg-red'>URGENTE</small>" : '' );
              }
            },
            { 
              "width": "13%",
              "data": function( d ){
                return "<p style='font-size: .8em'>"+d.fechaDis+"</p>";
              }
            },  
            { 
              "orderable": false,
              "data": function( data ){
                opciones = '<div class="btn-group-vertical" role="group">';
                opciones += '<button style="margin-right: 5px;" type="button" class="notification btn btn-primary btn-sm consultar_modal" style="" value="'+data.idsolicitud+'" data-value="BAS" title="Ver Solicitud"><i class="far fa-eye"></i>'+(data.visto == 0 ? '</i><span class="badge">!</span>' : '')+'</button>';
                opciones += '<button type="button" style="margin-right: 5px;" class="btn btn-success btn-sm" onClick="terminar('+data.idpago+","+data.idempresa+","+data.excp+')" title="Confirmar pago" ><i class="fas fa-check"></i></button>';
                opciones += '<button type="button" style="margin-right: 5px;" class="btn btn-danger btn-sm" onClick="regresar_dp('+data.idpago+')" title="Regresar de nuevo a pago en CXP"><i class="fas fa-close"></i></button>';

                return opciones + '</div>';
              }
            }
          ],
          "ajax": {
            "url": url + "Cuentasxp/ver_datos_finales",
            "type": "POST",
            cache: false,
          }
        });
      }); 

  $("#tabla_pagadas3").ready( function () {
    $('#tabla_pagadas3 thead tr:eq(0) th').each( function (i) {
      if( i!=8){ /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        var title = $(this).text();
        $(this).html( '<input type="text" style="width:100%;" placeholder="'+title+'" />' );
        $('input', this).on('keyup change', function() {
          if (tabla_3.column(i).search() !== this.value ) {
            tabla_3
            .column(i)
            .search( this.value )
            .draw();

            var total = 0;
            var index = tabla_3.rows( { selected: true, search: 'applied' } ).indexes();
            var data = tabla_3.rows( index ).data();

            $.each(data, function(i, v){
              total += parseFloat(v.cantidad);
            });
            var to1 = formatMoney(total);
            document.getElementById("myText_3_last").value = formatMoney(total);
          }
        });
      }
    });

    $('#tabla_pagadas3').on('xhr.dt', function ( e, settings, json, xhr ) {
      var total = 0;
      $.each(json.data, function(i, v){
        total += parseFloat(v.cantidad);
      });
      var to = formatMoney(total);
      document.getElementById("myText_3_last").value = to;
    });

    tabla_3 = $("#tabla_pagadas3").DataTable({
      dom: 'Brtip',
      "buttons": [
        {
          extend: 'excelHtml5',             
          text: '<i class="fas fa-file-excel"></i> EXPORTAR EXCEL',
          messageTop: "Lista de solicitudes por autorizar",
          attr: {
            class: 'btn btn-success'       
          },
          exportOptions: {
            columns: function(idx, data, node){ // INICIO 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com>
                if (activeTab !== 'tdc'){
                    if($.inArray(idx, [0, 1, 2, 3, 4, 5, 6]) > -1) return true;
                }else{
                    if($.inArray(idx, [0, 1, 3, 4, 5, 6]) > -1) return true;
                }
            }, /** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            format: {
              header: function (data, columnIdx) { 
                data = data.replace( '<input type="text" style="width:100%;" placeholder="', '' );
                data = data.replace( '">', '' )
                return data;
              }
            }
          } 
        }
      ],
      "language":lenguaje,
      "processing": false,
      "pageLength": 50,
      "bAutoWidth": false,
      "bLengthChange": false,
      "ordering": false,
      "bInfo": false,
      "searching": true,
      "scrollX":true,
      "columns": [
        {
          "width": "5%", /** FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
          "data": function( d ){
            return '<p style="font-size: .8em">'+d.idpago+'</p>';
          }
        },
        {
          "width": "18%",
          "data": function( d ){
            return '<p style="font-size: .8em">'+d.responsable+'</p>';
          }
        },
        {/** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
            "width": "18%",
            "data" : function( d ){
                return '<p style="font-size: .8em">'+(d.nombre_reembolso_cch ? d.nombre_reembolso_cch : 'NA' )+'</p>'
            }
        }, /** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
        {
          "width": "12%",
          "data": function( d ){
            return '<p style="font-size: .8em">$ '+formatMoney( d.cantidad )+'</p>';
          }
        },
        {  
          "width": "12%",
          "data": function( d ){
            return '<p style="font-size: .8em">'+d.nomdepto+'</p>';
          }
        },
        { 
          "width": "10%",
          "data": function( d ){
            return '<p style="font-size: .8em">'+d.abrev+'</p>';
          }
        },
        {
          "width": "14%",
          "data": function( d ){
            return '<p style="font-size: .8em">'+d.tipoPago+'</p>';
          }
        },
        {
          "width": "15%",
          "data": function( d ){
            return '<p style="font-size: .8em">'+formato_fechaymd(d.fechaDis)+'</p>';
          }
        },  
        { 
          "orderable": false,
          "data": function( data ){
            opciones = '<div class="btn-group-vertical" role="group">';
            opciones += '<button  style="margin-right: 5px;"  type="button" class="btn btn-success btn-sm" onClick="terminar_ch('+data.idpago+","+data.idempresa+')" title="Confirmar pago" ><i class="fas fa-check"></i></button>';
            opciones += '<button type="button"  style="margin-right: 5px;"  class="btn btn-danger btn-sm" onClick="regresar_cpx_ch('+data.idpago+')" title="Regresar pago a CXP nuevamente"><i class="fas fa-close"></i></button>';

            return opciones + '</div>';
          }
        }
      ]
    });
  }); 

  function regresar_dp(idpago){
    idapagorechazo = idpago;
    link_post2 = "Cuentasxp/datos_para_rechazo2";
    $("#myModalcomentario1 .modal-footer").html("");
    $("#myModalcomentario1 .modal-body").html("");
    $("#myModalcomentario1 ").modal();
    $("#myModalcomentario1 .modal-body").append("<div class='form-group col-lg-12'><input type='text' class='form-control' placeholder='Describe motivo de rechazo.' name='observacion' id='observacion' required></div>");
    $("#myModalcomentario1 .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>RECHAZAR</button><button type='button' class='btn btn-danger' onclick='cancelarr2()'>SALIR</button></div>");
  }

  function regresar_cpx_ch(idpago){
    idapagorechazo3 = idpago;
    link_post3 = "Cuentasxp/datos_para_rechazo3";
    $("#myModalcomentario3 .modal-footer").html("");
    $("#myModalcomentario3 .modal-body").html("");
    $("#myModalcomentario3 ").modal();
    // $("#myModalcomentario3 .modal-body").append("<div class='form-group col-lg-12'><input type='text' class='form-control' placeholder='Describe motivo de rechazo.' name='observacion' id='observacion' required></div>");
    $("#myModalcomentario3 .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>RECHAZAR</button><button type='button' class='btn btn-danger' onclick='cancelarr3()'>SALIR</button></div>");
  }

  var link_post;

  $("#infopago_polizad").submit( function(e) {
    e.preventDefault();
  }).validate({
    submitHandler: function( form ) {

      var data = new FormData( $(form)[0] );
      $.ajax({
        url: url + link_post,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        method: 'POST',
        type: 'POST', // For jQuery < 1.9
        success: function(data){
          if(data){
            $("#myModalpoliza").modal('toggle' );

            tabla_4.clear();
            tabla_4.rows.add( data.data );
            tabla_4.draw();

          }else{
            alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
          }
        },error: function( ){
          
        }
      });
    }
  });

  var link_post;

  $("#infopago_poliza_chica").submit( function(e) {
    e.preventDefault();
  }).validate({
    submitHandler: function( form ) {

      var data = new FormData( $(form)[0] );

      $.ajax({
        url: url + link_post_ch,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        method: 'POST',
        type: 'POST', // For jQuery < 1.9
        success: function(data){
          if(data){
            $("#myModalpoliza_ch").modal('toggle' );
            tabla_3.ajax.reload();
          }else{
            alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
          }
        },error: function( ){
          
        }
      });
    }
  });

  function cancelarr2(){
    $("#myModalcomentario1").modal('toggle');
  } 

  function cancelarr3(){
    $("#myModalcomentario3").modal('toggle');
  } 

  var idapagorechazo;
  var link_post2;

  $("#infosol1").submit( function(e) {
    e.preventDefault();
  }).validate({
    submitHandler: function( form ) {

      var data = new FormData( $(form)[0] );

      data.append("idpago", idapagorechazo);

      $.ajax({
        url: url + link_post2,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        method: 'POST',
        type: 'POST', // For jQuery < 1.9
        success: function(data){
          if( data.resultado ){
            $("#myModalcomentario1").modal( 'toggle' );
            tabla_4.ajax.reload();
          }else{
            alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
          }
        },error: function( ){
          
        }
      });
    }
  });

  var idapagorechazo3;
  var link_post3;

  $("#infosol3").submit( function(e) {
    e.preventDefault();
  }).validate({
    submitHandler: function( form ) {

      var data = new FormData( $(form)[0] );

      data.append("idpago", idapagorechazo3);

      $.ajax({
        url: url + link_post3,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        method: 'POST',
        type: 'POST', // For jQuery < 1.9
        success: function(data){
          if( data.resultado ){
            $("#myModalcomentario3").modal( 'toggle' );
            tabla_3.ajax.reload();
          }else{
            alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
          }
        },error: function( ){
          
        }
      });
    }
  });

  $(document).on("change", "#tipoPago_chica", function(){
    if( $(this).val() == "" || $(this).val() == "ECHQ" ){
      $("select[name='idcuentas']").hide();
      $("label[name='lab2_CH']").hide();
      $("input[name='idcheque']").show();
      $("label[name='lab3_CH']").show();

    }

    if( $(this).val() == "TEA" || $(this).val() == "DOMIC" ){
      $("select[name='idcuentas']").show();
      $("label[name='lab2_CH']").show();
      $("input[name='idcheque']").hide();
      $("label[name='lab3_CH']").hide();
    }

  });

  //UNA VEZ SELECCIONADA LA FORMA DE PAGO SE MUESTRAN LAS CORRESPONDIENTES OPCIONES
  $(document).on("change", "#formaPago", function(){

  /*EN CASO DE NO SELECCIONAR ALGO SE OCULTAN LAS SIGUIENTES OPCIONES*/
    if( $(this).val() == "" ){
      $("label[name='lab3']").hide();
      $("input[name='idcheque_general']").hide();
      $("input[name='fecha_pago']").hide();
      $("label[name='lab2']").hide();
      $("button[name='btn1']").hide();
      $("button[name='btn2']").hide();
      $("select[name='idcuenta']").hide();
      document.querySelector("#esFactura") !== null ? $("div[id='esFactura']").hide() : null;
    }

    /*EN CASO DE SER TRANFERENCIA SE OCULTAN / MUESTRAN LAS SIGUIENTES OPCIONES*/
    if( $(this).val() == "TEA" || $(this).val() == "DOMIC" || $(this).val() == "MAN" ){
      $("label[name='lab3']").hide();
      $("input[name='idcheque_general']").hide();
      $("input[name='fecha_pago']").show();
      $("label[name='lab2']").show();
      $("button[name='btn1']").hide();
      $("button[name='btn2']").hide();
      $("select[name='idcuenta']").hide();
      document.querySelector("#esFactura") !== null ? $("div[id='esFactura']").show() : null;
    }
    
    /*EN CASO DE SER CHEQUE SE OCULTAN / MUESTRAN LAS SIGUIENTES OPCIONES*/
    if( $(this).val() == "ECHQ" || $(this).val() == "EFEC" ){
      $("label[name='lab3']").show();
      $("input[name='idcheque_general']").show();
      $("input[name='fecha_pago']").show();
      $("label[name='lab2']").show();
      $("button[name='btn1']").show();
      $("button[name='btn2']").show();
      $("select[name='idcuenta']").show();
      document.querySelector("#esFactura") !== null ? $("div[id='esFactura']").show() : null;
    }
  });

  //AL DAR CLICK EN CONFIRMAR PAGO SE MUESTRA EL SIGUIENTE MODAL CON LAS CORRESPONDIENTES OPCIONES PARA OPERAR
  function terminar(idpago, idempresa, exepcionfact) {
    /**************************************************************************************************
     * Author: DANTE ALDAIR GUERRERO ALDANA
     * Descripción: Se agregó una función para confirmar si la solicitud requiere una factura. 
     *              Esta funcionalidad solo se aplica a las solicitudes con proveedores que 
     *              tengan un tipo de facturación igual a 2 (excp).
     *              El elemento generado basado en lo anterior se almacenará en una variable llamada "elem_fact".
     * @param {number}  exepcionfact
     * @var   {string}  elem_fact
    **************************************************************************************************/
    /*************************************************************************************************/
    let elem_fact = '';
    exepcionfact === 1
      ? elem_fact = ` <div class="col-lg-12" style="padding: 10px 50px;" id="esFactura">
                        <div class="col-lg-12" style="text-align: center;">
                          <label>¿TENDRÁ FACTURA?</label>
                        </div>
                        <div class="col-lg-6" style="text-align: center;">
                          <label>
                            SÍ
                            <input type="radio" name="tendraFactura" id="tendraFactura" value="1" checked>
                          </label>
                        </div>
                        <div class="col-lg-6" style="text-align: center;">
                          <label>
                            NO
                            <input type="radio" name="tendraFactura" id="tendraFactura" value="0">
                          </label>
                        </div>
                      </div>`
        : ``;
    link_post = "Cuentasxp/datos_para_poliza/"+idpago;

    $("#myModalpoliza .modal-footer").html("");
    $("#myModalpoliza .modal-body").html("");
    $("#myModalpoliza ").modal();

    $("#myModalpoliza .modal-body").append("<p class='text-danger'><u>Registra los datos solicitados</u></p>");
    $("#myModalpoliza .modal-body").append("<div class='form-group col-lg-12'><label id='lab1' name='lab1'>FORMA DE PAGO</label><select class='form-control' name='formaPago' id='formaPago' required><option value=''>SELECCIONA</option><option value='TEA'>TRANSFERENCIA</option><option value='ECHQ'>CHEQUE</option><option value='MAN'>MANUAL</option><option value='EFEC'>EFECTIVO</option><option value='DOMIC'>DOMICILIARIO</option></select></div>");
    $("#myModalpoliza .modal-body").append("<div class='form-group col-lg-12'><label id='lab2' name='lab2'>FECHA DE OPERACIÓN</label><input type='date' class='form-control' name='fecha_pago' id='fecha_pago' required></div>");
    $("#myModalpoliza .modal-body").append(elem_fact);
    $("#myModalpoliza .modal-body").append(`<div class='col-lg-12' id="esFactura"><input type ='hidden' name='excp' id='excp' value="${exepcionfact}"></div>`);
    $("#myModalpoliza .modal-body").append("<div class='form-group col-lg-12 serie_3'><label id='lab3' name='lab3'>NO. SERIE</label><input type ='text' class='form-control' name='idcheque_general' id='idcheque_general' required></div>");
    $("#myModalpoliza .modal-body").append("<div class='form-group col-lg-12 serie_3'><input type ='hidden' name='valor_c' id='valor_c'></div>");
    $("#myModalpoliza .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>ACEPTAR</button><button type='button' class='btn btn-danger' class='close' data-dismiss='modal'> CANCELAR </button> </div>");

    $('select#idcuenta').on('change',function(){
      $(".idcuenta").html("");
      var valor = $(this).val();
      $.getJSON( url + "Cuentasxp/getConsecutivo_chica"+"/"+valor).done( function( data ){
        $.each( data, function( i, v){
          document.getElementById("idcheque_general").value = v.serie;
          document.getElementById("valor_c").value = valor;
        });
      });
    });

    $("label[name='lab3']").hide();
    $("input[name='idcheque_general']").hide();
    $("input[name='fecha_pago']").hide();
    $("select[name='idcuenta']").hide();
    $("label[name='lab2']").hide();
    $("div[id='esFactura']").hide();

  }

  function terminar_ch(idpago, idempresa) {

    link_post_ch = "Cuentasxp/datos_para_chch/"+idpago;
    $("#myModalpoliza_ch .modal-footer").html("");
    $("#myModalpoliza_ch .modal-body").html("");
    $("#myModalpoliza_ch ").modal();

    $("#myModalpoliza_ch .modal-body").append("<p class='text-danger'><u>Registra los datos solicitados</u></p>");
    $("#myModalpoliza_ch .modal-body").append("<div class='form-group col-lg-12'><label id='lab1' name='lab1'>FORMA DE PAGO</label><select class='form-control' name='formaPago' id='formaPago' required><option value=''>SELECCIONA</option><option value='TEA'>TRANSFERENCIA</option><option value='ECHQ'>CHEQUE</option><option value='MAN'>MANUAL</option><option value='EFEC'>EFECTIVO</option><option value='DOMIC'>DOMICILIARIO</option></select></div>");
    $("#myModalpoliza_ch .modal-body").append("<div class='form-group col-lg-12'><label id='lab2' name='lab2'>FECHA DE OPERACIÓN</label><input type='date' class='form-control' name='fecha_pago' id='fecha_pago' required></div>");
    //$("#myModalpoliza_ch .modal-body").append("<div class='form-group col-lg-12'><select style='width: 100%;' id='idcuenta' name='idcuenta'  class='form-control lista_prov_ch' required><option value='' selected=''> -SELECCIONA OPCIÓN- </option></select></div>");
    $("#myModalpoliza_ch .modal-body").append("<div class='form-group col-lg-12 serie_3'><label id='lab3' name='lab3'>NO. SERIE</label><input type ='text' class='form-control' name='idcheque_general' id='idcheque_general' required></div>");
    $("#myModalpoliza_ch .modal-body").append("<div class='form-group col-lg-12 serie_3'><input type ='hidden' name='valor_c' id='valor_c'></div>");
    $("#myModalpoliza_ch .modal-footer").append("<div class='btn-group'><button type='submit' class='btn btn-success'>ACEPTAR</button><button type='button' class='btn btn-danger' class='close' data-dismiss='modal'> CANCELAR </button> </div>");

    $('select#idcuenta').on('change',function(){
      $(".idcuenta").html("");
      var valor = $(this).val();
      $.getJSON( url + "Cuentasxp/getConsecutivo_chica"+"/"+valor).done( function( data ){
        $.each( data, function( i, v){
          document.getElementById("idcheque_general").value = v.serie;
          document.getElementById("valor_c").value = valor;
        });
      });
    });

    $("label[name='lab3']").hide();
    $("input[name='idcheque_general']").hide();
    $("input[name='fecha_pago']").hide();
    $("select[name='idcuenta']").hide();
    $("label[name='lab2']").hide();

  }

  $(window).resize(function(){
    tabla_4.columns.adjust();
  });

</script>
<?php
  require("footer.php");
?>