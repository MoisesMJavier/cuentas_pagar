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
            <h3>ADMINISTRACIÓN</h3>
          </div>
          <div class="box-body">
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a id="autorizaciones-tab" data-toggle="tab" href="#facturas_aturizar" role="tab" aria-controls="#home" aria-selected="true">BANCOS</a></li>
                <li><a id="profile-tab" data-toggle="tab" href="#empresas_cm" role="tab" aria-controls="empresas_cm" aria-selected="false">EMPRESAS</a></li>
                <li><a id="profile-tab" data-toggle="tab" href="#cuentas_cm" role="tab" aria-controls="cuentas_cm" aria-selected="false">CUENTAS</a></li>
                <li><a id="profile-tab" data-toggle="tab" href="#seriech_cm" role="tab" aria-controls="seriech_cm" aria-selected="false">SERIE DE CHEQUES</a></li>
              </ul>
            </div>
            <div class="tab-content">
              <div class="active tab-pane" id="facturas_aturizar">
                <div class="row">
                  <div class="col-lg-4">
                    <h4>&nbsp;AÑADIR BANCO <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Permite agregar un banco más a la lista disponible." data-placement="right"></i> </h4>
                    <form id="fom1" method="post" action="#">
                      <div class="row">                    
                        <div class="col-lg-12 form-group">
                          <label>NOMBRE DEL BANCO:</label>
                          <input type="text" name="nombreBanco" id="nombreBanco" class="form-control nombreBanco" placeholder="Digitar nombre del banco" required>
                        </div>
                        <div class="col-lg-12 form-group">
                          <label>CLAVE DEL BANCO:</label>
                          <input type="text" name="clvbanco" id="clvbanco" class="form-control clvbanco" placeholder="Digitar clave del banco" required>
                        </div>
                        <div class="col-lg-12">
                          <input type="submit" class="btn btn-success btn-block" value="GUARDAR DATOS">
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="col-lg-8">
                    <table class="table table-striped"  id="tabla_bancos">
                      <thead class="thead-dark">
                        <tr>
                          <th>ID</th>
                          <th>CLABE</th>
                          <th>NOMBRE</th>
                          <th>TIPO</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="cuentas_cm">
                <div class="row">
                  <div class="col-lg-3">
                    <h4>&nbsp;CUENTAS <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Permite agregar una cuenta más a la lista de cuentas disponibles" data-placement="right"></i> </h4>
                    <form id="fom2" method="post" action="#">
                      <div class="row">
                        <div class="col-lg-12 form-group">
                          <label>Nombre:</label>
                          <input type="text" name="nombreCta" name="nombreCta" class="form-control" placeholder="Digite nombre de la cuenta" required>
                        </div>
                        <div class="col-lg-12 form-group">
                          <label>Tipo de cuenta:</label>
                          <select class="form-control" name="tipocta" required="required">
                          <option value="">Seleccione una opción</option>
                          <option value="1">Cuenta mismo banco - 01</option>
                          <option value="3">Tarjeta de débito - 03</option>
                          <option value="40">CLABE - 40</option></select>
                        </div>
                        <div class="col-lg-12 form-group">
                          <label>No. de cuenta:</label>
                          <input type="number" name="nodecta" onKeyPress="if(this.value.length==20) return false;" class="form-control nodecta" placeholder="Digite numero de cuenta" required>
                        </div>
                        <div class="col-lg-12 form-group">
                          <label>Banco:</label>
                          <select id="idbanco" name="idbanco" class="cargar_lista_bancos form-control" required="required"></select>
                        </div>
                        <div class="col-lg-12 form-group">
                          <label>Empresa:</label>
                          <select id="idempresa" name="idempresa"class="form-control lista_empresa" required="required"></select>
                        </div>
                        <div class="col-lg-12 form-group">
                          <input type="submit" class="btn btn-success btn-block" value="GUARDAR DATOS">
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="col-lg-9">
                    <table class="table table-striped" id="tabla_cuentas">
                      <thead class="thead-dark">
                        <tr>
                          <th>ID</th>
                          <th>NOMBRE</th>
                          <th>TIPO DE CUENTA</th>
                          <th>NO. DE CUENTA</th>
                          <th>BANCO</th>
                          <th>EMPRESA</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="empresas_cm">
                <div class="row">
                  <div class="col-lg-4">
                    <h4>&nbsp;EMPRESAS <i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Permite agregar una empresa más a la lista de empresas disponibles" data-placement="right"></i> </h4>
                    <form id="fom3" method="post" action="#">
                      <div class="row">
                        <div class="col-lg-12 form-group">
                          <label>NOMBRE DE EMPRESA:</label>
                          <input type="text" name="nombreEmpresa" class="form-control" placeholder="Digite nombre de la empresa"  onKeyPress="if(this.value.length==100) return false;" required="required">
                        </div>
                        <div class="col-lg-12 form-group">
                          <label>RFC:</label>
                          <input type="text" name="rfc" onKeyPress="if(this.value.length==150) return false;" class="form-control rfc" placeholder="Digite RFC" required="required">
                        </div>

                        <div class="col-lg-12 form-group">
                          <label>ABREVIATURA:</label>
                          <input type="text" name="abrev" onKeyPress="if(this.value.length==150) return false;" class="form-control" placeholder="Digite abreviatura" required="required">
                        </div>

                        <div class="col-lg-12 form-group">
                          <label>RÉGIMEN FISCAL:</label>
                          <select name="rf_empresa" class="form-control" placeholder="Digite régimen fiscal" required="required"></select>
                        </div>

                        <div class="col-lg-12 form-group">
                          <label>CODIGO POSTAL:</label>
                          <input type="text" name="cp_empresa" onKeyPress="if(this.value.length==150) return false;" class="form-control" placeholder="Digite codigo postal" required="required">
                        </div>
                        
                        <div class="col-lg-12 form-group">
                          <input type="submit" class="btn btn-success btn-block" value="GUARDAR DATOS">
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="col-lg-8">
                    <table class="table table-striped" id="tabla_empresas">
                      <thead class="thead-dark">
                        <tr>
                          <th>ID</th>
                          <th>NOMBRE</th>
                          <th>RFC</th>
                          <th>ABREVIATURA</th>
                          <th></th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="seriech_cm">
                <div class="row">
                  <div class="col-lg-4">
                    <h4>&nbsp;SERIE DE CHEQUES<i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Permite agregar la relación de cada serie de cheques por empresa y por cuenta" data-placement="right"></i> </h4>
                    <form id="fom4" method="post" action="#">
                      <div class="row">
                        <div class="col-lg-12 form-group">
                          <label>Seleccione empresa:</label>
                          <select id="idEmp" name="idEmp"class="form-control lista_empresa" required="required"></select>
                        </div>
                        <div class="col-lg-12 form-group">
                          <label>Seleccione cuenta de empresa:</label> 
                          <select id="cuenta_valor" name="cuenta_valor" class=" form-control lista_cuentas" required="required"></select>
                        </div>
                        <div class="col-lg-12 form-group">
                          <label>No. de serie:</label>
                          <input type="number" name="serie" id="serie" onKeyPress="if(this.value.length==20) return false;" class="form-control" placeholder="Digite numero de serie" required="required">
                        </div>
                        <div class="col-lg-12 form-group">
                          <input type="submit" class="btn btn-success btn-block" value="GUARDAR DATOS">
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="col-lg-8">
                    <table class="table table-striped" id="tabla_serie_cheques">
                      <thead class="thead-dark">
                        <tr>
                          <th>ID</th>
                          <th>EMPRESA</th>
                          <th>CUENTA</th>
                          <th># SERIE INICIAL</th>
                          <th># SERIE ACTUAL</th>
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

<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <!-- <h4 class="modal-title">Datos del proveedor .</h4> -->
      </div>
      <div class="modal-body"></div>

      <div class="modal-footer">

      </div>
    </div>

  </div>
</div>








<div class="modal fade" id="modalUpdate" role="dialog">
    <div class="modal-dialog">
               <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="exampleModalLabel">INFORMACIÓN DEL PROVEEDOR</h4>
            </div>
            <div class="modal-body">
                <form id="updatebanco_form">
                    <p>Información del banco. (<span class="text-danger">*</span>)</p>
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label>BANCO<span class="text-danger">*</span></label>
                            <input type="text" class="form-control nombreBanco" id="nombreBanco" name="nombreBanco" required>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>CLAVE</label>
                            <input type="text" class="form-control clvbanco" id="clvbanco" name="clvbanco">
                        </div>
                    </div>
                   
                    <div class="row">
                        <div class="col-lg-4 col-lg-offset-4">
                            <button class="btn btn-success btn-block">GUARDAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

      </div>
    </div>










<div class="modal fade" id="modalUpdateEmpresa" role="dialog">
    <div class="modal-dialog">
               <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="exampleModalLabel">INFORMACIÓN DE EMPRESA</h4>
            </div>
            <div class="modal-body">
                <form id="updatempresa_form">
                    <p>Información de empresa registrada. (<span class="text-danger">*</span>)</p>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <label>EMPRESA:<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombreEmpresa" name="nombreEmpresa" required>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>RFC:</label>
                            <input type="text" class="form-control rfc" id="rfc" name="rfc">
                        </div>

                        <div class="col-lg-6 form-group">
                            <label>CLAVE:</label>
                            <input type="text" class="form-control" id="abrev" name="abrev">
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>RÉGIMEN FISCAL:</label>
                            <select class="form-control" name="rf_empresa"></select>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>CODIGO POSTAL:</label>
                            <input type="text" class="form-control"  name="cp_empresa">
                        </div>
                    </div>
                   
                    <div class="row">
                        <div class="col-lg-4 col-lg-offset-4">
                            <button class="btn btn-success btn-block">GUARDAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

      </div>
    </div>
<script type="text/javascript">


var lista_regf = [];
 
    $('#fom1').submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {

            // $("#depto").prop("disabled", false);

            var data = new FormData( $(form)[0] );
            $.ajax({
                url : url + "Bancos_cxp/registrar_nuevo_banco",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST',
                success: function(data){
                    // $("#depto").prop("disabled", true);
                    if( !data[0] ){
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }else{
                        $("#fom1 .form-control").val('');
                    }
                    table_proceso.ajax.reload();
                    table_proceso2.ajax.reload();
                    table_proceso3.ajax.reload();
                    table_proceso4.ajax.reload();
                },error: function( ){
                    
                }
            });
        }
    });




$('#fom2').submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
 
            var data = new FormData( $(form)[0] );
            $.ajax({
                url : url + "Bancos_cxp/registrar_nuevo_cuenta",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST',
                success: function(data){
                     if( !data[0] ){
                      alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }else{
                        $("#fom2 .form-control").val('');
                    }
                     table_proceso.ajax.reload();
                    table_proceso2.ajax.reload();
                    table_proceso3.ajax.reload();
                    table_proceso4.ajax.reload();
                },error: function( ){
                    
                }
            });
        }
    });




    $('#fom3').submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
            var data = new FormData( $(form)[0] );
            $.ajax({
                url : url + "Bancos_cxp/registrar_nuevo_empresa",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST',
                success: function(data){
                     if( !data[0] ){
                      alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }else{
                      alert("GUARDADO CON ÉXITO");
                        $("#fom3 .form-control").val('');
                    }
                    table_proceso.ajax.reload();
                    table_proceso2.ajax.reload();
                    table_proceso3.ajax.reload();
                    table_proceso4.ajax.reload();
                },error: function( ){
                    
                }
            });
        }
    });

    $('#fom4').submit(function(e) {
        e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
 
            var data = new FormData( $(form)[0] );
            $.ajax({
                url : url + "Bancos_cxp/registrar_nuevo_serie",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                method: 'POST',
                type: 'POST',
                success: function(data){
                     if( !data[0] ){
                      alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }else{
                        $("#fom4 .form-control").val('');
                    }
                     table_proceso.ajax.reload();
                    table_proceso2.ajax.reload();
                    table_proceso3.ajax.reload();
                    table_proceso4.ajax.reload();
                },error: function( ){
                    
                }
            });
        }
    });





  $(document).ready(function () {
    $("form").validate();
    cargar_lista_bancos();

    function cargar_lista_bancos(){
      $.getJSON(url+"Bancos_cxp/verbancos").done(function( data ){
        $.each(data['data'], function (ind, val) {
          $("#idbanco").append("<option value='"+val.idbanco+"'>"+val.nombre+"</option>");
        });
      });
    }


    $.getJSON( url + "Listas_select/cat_regimenfiscal").done(function( data ){
      
      $("select[name='rf_empresa']").append('<option value="">Seleccione una opción</option>');
        $.each(data, function (ind, val) {

        $("select[name='rf_empresa']").append("<option value='"+val.codrf+"'>"+val.codrf+" - "+val.descrf+"</option>");
        });
        
         lista_regf = data;
   });

  });



  $('select#idEmp').on('change',function(){
   $(".lista_cuentas").html("");
   var valor = $(this).val();
   $.getJSON(url + "Bancos_cxp/vercuentas"+"/"+valor).done(function( data ){
    $("#cuenta_valor").append("<option value=''> -Selecciona opción- </option>");
    $.each(data['data'], function (ind, val) {
      $("#cuenta_valor").append("<option value='"+val.idcuenta+"'>"+val.nodecta+" - "+val.nombre+"</option>");
    });
  });

 });

</script>
<script type="text/javascript">

  $('#tabla_bancos thead tr:eq(0) th').each( function (i) {
    if( i < $('#tabla_bancos thead tr:eq(0) th').length -1 ){
      var title = $(this).text();
      $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="'+title+'" />' );

      $( 'input', this ).on( 'keyup change', function () {
          if ( table_proceso.column(i).search() !== this.value ) {
            table_proceso
                  .column(i)
                  .search( this.value )
                  .draw();
          }
      });
    }
  });

  $('#tabla_cuentas thead tr:eq(0) th').each( function (i) {
    var title = $(this).text();
    $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="'+title+'" />' );

    $( 'input', this ).on( 'keyup change', function () {
        if ( table_proceso2.column(i).search() !== this.value ) {
          table_proceso2
                .column(i)
                .search( this.value )
                .draw();
        }
    });
  });

  $('#tabla_empresas thead tr:eq(0) th').each( function (i) {
    if( i < $('#tabla_empresas thead tr:eq(0) th').length -1 ){
      var title = $(this).text();
      $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="'+title+'" />' );

      $( 'input', this ).on( 'keyup change', function () {
          if ( table_proceso3.column(i).search() !== this.value ) {
            table_proceso3
                  .column(i)
                  .search( this.value )
                  .draw();
          }
      });
    }
  });


  $('#tabla_serie_cheques thead tr:eq(0) th').each( function (i) {
    var title = $(this).text();
    $(this).html( '<input type="text" style="font-size: .9em; width: 100%;" placeholder="'+title+'" />' );

    $( 'input', this ).on( 'keyup change', function () {
        if ( table_proceso4.column(i).search() !== this.value ) {
          table_proceso4
                .column(i)
                .search( this.value )
                .draw();
        }
    });
  });

  var table_proceso;

  $("#tabla_bancos").ready( function () {

    table_proceso = $('#tabla_bancos').DataTable({
      "language" : lenguaje,
      "processing": false,
      "pageLength": 5,
      "bAutoWidth": false,
      "bLengthChange": false,
      "bInfo": false,
      "searching": true,
      "columns": [
        { "data": "idbanco" },
        { 
          "data": function( data ){
              return data.clvbanco.length > 3 ? (data.clvbanco).substr(-3) : data.clvbanco;
          }
        
        },
        { "data": "nombre" },
        { 
          "data": function(d){
            return d.estatus == 1 ? 'NACIONAL' : 'EXTRANJERO';
          } 
        },
        {
          "orderable": false,
          "data": function( data ){
            opciones = '<div class="btn-group-vertical" role="group">';
            opciones += '<button type="button" class="btn btn-warning btn-sm btn-editar"><i class="fas fa-pencil-alt"></i></button>';
            return opciones + '</div>';
          } 
        }
      ],
        "ajax": {
        "url": url + "Bancos_cxp/ver_datos_banco",
        "type": "POST",
          cache: false,
        }
    });
  }); 

  $("#tabla_bancos").on( "click", ".btn-editar", function(){

    tr = $(this).closest('tr');
    var row = table_proceso.row( tr );

    idbanco = row.data().idbanco

    $("input[name='nombreBanco']").val( row.data().nombre );
    $("input[name='clvbanco']").val( row.data().clvbanco );    
    $("#idbanco").prop("disabled", row.data().idbanco ? false : true);

    link_post = 'Bancos_cxp/updateBanco';

    $("#modalUpdate").modal();

  });

  var table_proceso2;

$("#tabla_cuentas").ready( function () {

  table_proceso2 = $('#tabla_cuentas').DataTable({
    "language" : lenguaje,
    "processing": false,
    "pageLength": 5,
    "bAutoWidth": false,
    "bLengthChange": false,
    "bInfo": false,
    "searching": true,

    "columns": [
    { "data": "idcuenta" },
    { "data": "nomcue" },
    { "data": "tipocta" },
    { "data": "nodecta" },
    { "data": "nomban" },
    { "data": "nomemp" }
    ],
    "ajax": {
      "url": url + "Bancos_cxp/ver_datos_cuentas",
      "type": "POST",
      cache: false,
    }
  });
}); 





var table_proceso3;

$("#tabla_empresas").ready( function () {

  table_proceso3 = $('#tabla_empresas').DataTable({
    "language" : lenguaje,
    "processing": false,
    "pageLength": 5,
    "bAutoWidth": false,
    "bLengthChange": false,
    "bInfo": false,
    "searching": true,

    "columns": [
    { "data": "idempresa" },
    { "data": "nombre" },
    { "data": "rfc" } ,
    { "data": "abrev" }  ,

     {
        "orderable": false,
        "data": function( data ){

          opciones = '<div class="btn-group-vertical" role="group">';
          opciones += '<button type="button" class="btn btn-warning btn-sm btn-editar"><i class="fas fa-pencil-alt"></i></button>';
          return opciones + '</div>';
       } 
   }



    ],
    "ajax": {
      "url": url + "Bancos_cxp/ver_datos_empresas",
      "type": "POST",
      cache: false,
    }
  });
}); 






 $("#tabla_empresas").on( "click", ".btn-editar", function(){

        tr = $(this).closest('tr');
        var row = table_proceso3.row( tr );

        idempresa = row.data().idempresa

        $("input[name='nombreEmpresa']").val( row.data().nombre );
        $("input[name='rfc']").val( row.data().rfc );
        $("input[name='abrev']").val( row.data().abrev );

        $("select[name='rf_empresa']").append('<option value="">Seleccione una opción</option>');
        $.each(lista_regf, function (ind, val) {
        $("select[name='rf_empresa']").append("<option value='"+val.codrf+"'>"+val.codrf+" - "+val.descrf+"</option>");
        });

        $("select[name='rf_empresa'] option[value='"+row.data().rf_empresa+"']").prop("selected", true);   
        $("input[name='cp_empresa']").val( row.data().cp_empresa );
        $("#idempresa").prop("disabled", row.data().idempresa ? false : true);

       link_post = 'Bancos_cxp/updateEmpresa';

        $("#modalUpdateEmpresa").modal();

    });

var table_proceso4;

$("#tabla_serie_cheques").ready( function () {

  table_proceso4 = $('#tabla_serie_cheques').DataTable({
    "language" : lenguaje,
    "processing": false,
    "pageLength": 5,
    "bAutoWidth": false,
    "bLengthChange": false,
    "bInfo": false,
    "searching": true,

    "columns": [
    { "data": "idNum" },
    { "data": "empresaCheque" },
    { "data": "numeroCta" },
    { "data": "serieInicial" },
    { "data": "serie" },


    ],
    "ajax": {
      "url": url + "Bancos_cxp/ver_datos_cheques_serie",
      "type": "POST",
      cache: false,
    }
  });
}); 





function acepta(idp) {
    // alert(idp); 
    $("#myModal .modal-title").html("");
    $("#myModal .modal-body").html("");
    $("#myModal .modal-footer").html("");
    $.get(url+"Bancos_cxp/ver_datosprovs2/"+idp, function(dato){
      data = JSON.parse(dato);
      if (data!="") {




        $.each(data, function(index, value){

         $('#myModal').modal({backdrop: 'static', keyboard: false});
         $("#myModal").modal();

         $("#myModal .modal-header").append("<h4 class='modal-title'>Datos de <b>"+ value.nomp +"</b></h4>");
         $("#myModal .modal-body").append("<h5><b>RFC:</b> "+value.rfc+"</h5>");
         $("#myModal .modal-body").append("<h5><b>Dirección:</b> "+value.domicilio+"</h5>");
         $("#myModal .modal-body").append("<h5><b>Contacto:</b> "+value.contacto+"</h5>");
         $("#myModal .modal-body").append("<h5><b>Email:</b> "+value.email+"</h5>");
         $("#myModal .modal-body").append("<h5><b>Teléfono:</b> "+value.tels+"</h5><br>");
         if (value.tipocta==1) {
          $("#myModal .modal-body").append("<h5><b>Tipo de cuenta:</b> Cuenta en Banco del Bajio</h5>");
        }
        if (value.tipocta==2) {
          $("#myModal .modal-body").append("<h5><b>Tipo de cuenta:</b> Tarjeta de débito</h5>");
        }
        if (value.tipocta==3) {
          $("#myModal .modal-body").append("<h5><b>Tipo de cuenta:</b> CLABE</h5>");
        }

        $("#myModal .modal-body").append("<h5><b>Banco:</b> "+value.nomba+"</h5>");
        $("#myModal .modal-body").append("<h5><b>Clabe:</b> "+value.clabe+"</h5>");
        $("#myModal .modal-body").append("<h5><b>No.Cuenta:</b> "+value.cuenta+"</h5>");
        $("#myModal .modal-body").append("<h5><b>Plaza:</b> "+value.nomplaza+"</h5>");
        $("#myModal .modal-body").append("<h5><b>Sucursal:</b> "+value.sucursal+"</h5>");
        $("#myModal .modal-body").append("<h5><b>Fecha de registro:</b> "+value.fecadd+"</h5>");
        $("#myModal .modal-footer").append("<input type='button' class='btn btn-danger' value='Cerrar'onclick='cancela()'>");


      });

      }

    });   
  }       



  function cancela(){
   $("#myModal").modal('toggle');
 } 






 $(".nodecta").change( function(){
  var elemento = $(this);
  $.post( url + "Consultar/cuenta_disponible", { cuenta : $( this ).val() } ).done( function( data ){
    if( data == 1 ){
      elemento.val('');
      $("#duplicidad .modal-body").html("<p class='text-center'>Este número de cuenta ya está registrado.</p>");
      $("#duplicidad").modal();
    }
  }).fail( function (){
    alert("ERROR");
  });
});





 $(".rfc").change( function(){
  var elemento = $(this);
  $.post( url + "Consultar/rfc_disponible", { rfc : $( this ).val() } ).done( function( data ){
    if( data == 1 ){
      elemento.val('');
      $("#duplicidad .modal-body").html("<p class='text-center'>Este RFC ya está registrado con una empresa.</p>");
      $("#duplicidad").modal();
    }
  }).fail( function (){
    alert("ERROR");
  });
});



 $(".nombreBanco").change( function(){
  var elemento = $(this);
  $.post( url + "Consultar/nombreBanco_disponible", { nombreBanco : $( this ).val() } ).done( function( data ){
    if( data == 1 ){
      elemento.val('');
      $("#duplicidad .modal-body").html("<p class='text-center'>Este banco ya está registrado.</p>");
      $("#duplicidad").modal();
    }
  }).fail( function (){
    alert("ERROR");
  });
});






 $(".clvbanco").change( function(){
  var elemento = $(this);
  $.post( url + "Consultar/clvbanco_disponible", { clvbanco : $( this ).val() } ).done( function( data ){
    if( data == 1 ){
      elemento.val('');
      $("#duplicidad .modal-body").html("<p class='text-center'>Esta clave ya esta asignada a un banco.</p>");
      $("#duplicidad").modal();
    }
  }).fail( function (){
    alert("ERROR");
  });
});





 $(".lista_cuentas").change( function(){
  var elemento = $(this);
  var emp = document.getElementById("idEmp").value;
  $.post( url + "Consultar/cta_disponible", { cta : $( this ).val(), emp: emp } ).done( function( data ){
    if( data == 1 ){
      elemento.val('');
      $("#duplicidad .modal-body").html("<p class='text-center'>Esta combinación de empresa con esta cuenta ya existe.</p>");
      $("#duplicidad").modal();
    }
  }).fail( function (){
    alert("ERROR");
  });
});


 $('#updatebanco_form').submit(function(e) {
         e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
           var data = new FormData( $(form)[0] );
           data.append("idbanco", idbanco);

            $.ajax({
                url : url + link_post,
                     data: data,
                     cache: false,
                     contentType: false,
                     processData: false,
                     dataType: 'json',
                     method: 'POST',
                     type: 'POST',
                success: function(data){

                    if( !data[0] ){
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                    $("#modalUpdate").modal( 'toggle' );
                      table_proceso.ajax.reload();
                    table_proceso2.ajax.reload();
                    table_proceso3.ajax.reload();
                    table_proceso4.ajax.reload();
                    
                },error: function( ){
                    
                }
           });
         }
    });







    $('#updatempresa_form').submit(function(e) {
         e.preventDefault();
    }).validate({
        submitHandler: function( form ) {
           var data = new FormData( $(form)[0] );
           data.append("idempresa", idempresa);

            $.ajax({
                url : url + link_post,
                     data: data,
                     cache: false,
                     contentType: false,
                     processData: false,
                     dataType: 'json',
                     method: 'POST',
                     type: 'POST',
                success: function(data){

                    if( !data[0] ){
                        alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
                    }
                    $("#modalUpdateEmpresa").modal( 'toggle' );
                      table_proceso.ajax.reload();
                    table_proceso2.ajax.reload();
                    table_proceso3.ajax.reload();
                    table_proceso4.ajax.reload();
                    
                },error: function( ){
                    
                }
           });
         }
    });

$(".lista_empresa").ready( function(){
    $(".lista_empresa").append('<option value="">Seleccione una opción</option>');
    $.getJSON( url + "Listas_select/lista_empresas").done( function( data ){
        $.each( data, function( i, v){
            $(".lista_empresa").append('<option value="'+v.idempresa+'" data-value="'+v.rfc+'">'+v.nombre+'</option>');
        });
    });
});


</script>
<?php
require("footer.php");
?>

