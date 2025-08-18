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
							<h3>HISTORIAL REEMBOLSOS  <small><i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Estas son todas las solicitudes de caja chica, cajas chicas cerradas en transito o ya pagadas." data-placement="right"></i></small></h3>
						</div>
						<div class="box-body">
							<div class="nav-tabs-custom">
								<ul class="nav nav-tabs">
									<li class="active"><a data-toggle="tab" data-value="pagadas" href="#caja_chica">REEMBOLSOS PAGADOS</a></li>
									<li><a data-toggle="tab" data-value="en_transito" href="#caja_chica">REEMBOLSOS EN TRÁNSITO</a></li>
									<li><a data-toggle="tab" data-value="cch_cerradas" href="#cch_cerradas">REEMBOLSOS CERRADOS</a></li>
								</ul>
							</div>
							<div class="tab-content">
								<div id="caja_chica" class="tab-pane fade in active">
									<div class="row">
										<form id="formulario_reportecch" autocomplete="off" action="<?= site_url("Historial/reporte_cajachica") ?>" method="post">
											<div class="col-lg-3">
												<div class="input-group">
													<span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
													<input class="form-control fechas_filtro from" type="text" id="fecInicial" name="fechaInicial" maxlength="10" required/>
												</div>
											</div>
											<div class="col-lg-3">
												<div class="input-group">
													<span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
													<input class="form-control fechas_filtro to" type="text" id="fecFinal" name="fechaFinal" maxlength="10" required/>
												</div>
											</div>
											<div id="elementos_hidden"></div>
										</form>
										<table class="table table-responsive table-bordered table-striped table-hover" id="tabla_autorizaciones_reembolso">
											<thead class="thead-dark">
											<tr>
												<th style="font-size: .8em"></th>
												<th style="font-size: .8em"># PAGO</th>
												<th style="font-size: .8em">RESPONSABLE</th>
												<th style="font-size: .8em">EMPRESA</th>
												<th style="font-size: .8em">FECHA</th>
												<th style="font-size: .8em">TOTAL</th>
												<th style="font-size: .8em">MÉTODO DE PAGO</th>
												<th style="font-size: .8em">FECHA AUT.</th>
												<th style="font-size: .8em">DEPARTAMENTO</th>
											</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
						</div><!--End tab content-->
					</div><!--end box-->
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="reporte_contabilidad" role="dialog">//
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="exampleModalLabel">CAJA CHICA CONTABILIDAD</h4>
				</div>
				<div class="modal-body">
					<form method="post" action="<?= site_url("Reportes/reporte_desglosado_CT")?>">
						<div class="row">
							<div class="col-lg-12 form-group">
								<label>DEPARTAMENTO</label>
								<select class="form-control" id="depto_cch"></select>
							</div>
							<div class="col-lg-12 form-group">
								<label>RESPONSABLE CAJA CHICA</label>
								<select class="form-control" id="resp_cch"></select>
							</div>
							<div class="col-lg-12 form-group">
								<label>REEMBOLSOS HECHOS</label>
								<select class="form-control" id="reeb_cch" name="reeb_cch"></select>
							</div>
							<div class="col-lg-12 form-group">
								<label>PROYECTO</label>
								<select class="form-control" id="proy_cch" name="proy_cch">
								</select>
							</div>
							<div class="col-lg-12 form-group">
								<button type="submit" class="btn btn-block btn-danger">DESCARGAR</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<script>

		var tabla_nueva_reembolsos;
		var reembolsos_pagados = true;
		var reem_pagadas = [];
		var valor_input = Array( $('#tabla_autorizaciones_reembolso th').length );
		btn_remover = false;
		var proyectos = [];
		var id_empresa ;

		$('[data-toggle="tab"]').click( function(e) {

			reembolsos_pagados = false;

			switch( $(this).data('value') ){
				case 'pagadas':
					reembolsos_pagados = true;
					btn_remover = false;
					tabla_nueva_reembolsos.ajax.url( url +"Historial/tabla_reembolsos" ).load();
					tabla_nueva_reembolsos.button().add( 2, {
						text: '<i class="fas fa-file-excel"></i> DESGLOSE CONTABILIDAD',
						action: function(){
							$("#reporte_contabilidad").modal();
						},
						attr: {
							class: 'btn btn-primary',
						}
					});
					break;
				case 'en_transito':
					tabla_nueva_reembolsos.ajax.url( url +"Historial/tabla_reembolsos_transito" ).load();
					break;
				case 'cch_cerradas':
					tabla_nueva_reembolsos.ajax.url( url +"Historial/reembolsos_cerrados" ).load();
					break;
			}
		});

		$('.fechas_filtro').datepicker({
			format: 'dd/mm/yyyy',
			endDate: '-0d'
		});

		$("#tabla_autorizaciones_reembolso").ready( function () {

			$('#tabla_autorizaciones_reembolso thead tr:eq(0) th').each( function (i) {
				if( i != 0 ){
					var title = $(this).text();
					var vari = (((title.replace(" ","")).replace("É","E")).substring(0,6)).toLowerCase();
					$(this).html( '<input type="text" style="font-size: .9em; width: 100%;" class="form-control" data-value="'+title+'" placeholder="'+title+'" />' );
					$('input', this).on('keyup change', function() {

						valor_input[i] = this.value;

						if (tabla_nueva_reembolsos.column(i).search() !== this.value ) {
							tabla_nueva_reembolsos
								.column(i)
								.search( this.value )
								.draw();
						}
					});
				}

			});

			$('#tabla_autorizaciones_reembolso').on('xhr.dt', function ( e, settings, json, xhr ) {

				console.log( reembolsos_pagados )

				if( json.permiso_desglose === false && !btn_remover ){
					tabla_nueva_reembolsos.button( '2' ).remove();
					btn_remover = true;
				}else{
					if( reembolsos_pagados ){
						$("#depto_cch, #resp_cch, #proy_cch, #empresa_cch").html("<option value=''>Seleccione una opción</option>");

						$.each( json.data, function( i, v ){
							if( !reem_pagadas[ v.nomdepto ] ){
								reem_pagadas[ v.nomdepto ] = [];
							}
							if( !reem_pagadas[ v.nomdepto ][ v.responsable ] ){
								reem_pagadas[ v.nomdepto ][ v.responsable ] = [];
							}
							reem_pagadas[ v.nomdepto ][ v.responsable ].push( { "idempresa" : v.idempresa, "empresa" : v.abrev, "cantidad" : v.cantidad,"idpago" : v.idpago, "fecha_operacion" : v.fecha } );
						});
						$.each( Object.keys( reem_pagadas ).sort(), function( i, v ){
							$("#depto_cch").append("<option value='"+v+"'>"+v+"</option>");
						});
						proyectos = json.proyectos;
						// $.each( json.proyectos, function( i, v ){
						//   $("#proy_cch").append("<option value='"+v.nproyecto_neo+"'>"+v.nproyecto_neo+"</option>");
						//});

					}
				}
			});

			$("#depto_cch").change( function(){
				$("#resp_cch").html("<option value=''>Seleccione una opción</option>");
				$.each( Object.keys( reem_pagadas[ $(this).val() ] ).sort(), function( i, v ){
					$("#resp_cch").append("<option value='"+v+"'>"+v+"</option>");
				});
			});

			$("#resp_cch").change( function(){
				$("#reeb_cch").html("<option value=''>Seleccione una opción</option>");
				$.each( reem_pagadas[ $( "#depto_cch" ).val() ][ $(this).val() ], function( i, v ){
					$("#reeb_cch").append( '<option value="'+v.idpago+'">#'+v.idpago+" | "+formato_fechaymd(v.fecha_operacion)+", "+v.empresa+" $ "+formatMoney(v.cantidad)+'</option>' )
				});
			});

			$("#reeb_cch").change(function(){
				$("#proy_cch").empty();
				$("#proy_cch").html("<option value=''>Seleccione una opción</option>");
				id_pago=$(this).val();
				$.each(reem_pagadas[ $( "#depto_cch" ).val() ][ $("#resp_cch").val() ], function(i,v){
					if(v.idpago==id_pago){
						id_empresa = v.idempresa;
					}
				});
				$.each(proyectos.filter(proyecto => proyecto["idempresa"]==id_empresa ), function( i, v ){
					$("#proy_cch").append("<option value='"+v.nproyecto_neo+"'>"+v.nproyecto_neo+"</option>"); });
			});

			$("#fpago_cch").change( function(){

				$.each( reem_pagadas[ $( "#depto_cch" ).val() ][ $(this).val() ], function( i, v ){
					if( (v.fecha_operacion).substring( 0, 7 ) == $("#fpago_cch").val() ){

					}
				});
			});

			tabla_nueva_reembolsos = $('#tabla_autorizaciones_reembolso').DataTable({
				dom: 'Brtip',
				width: 'auto',
				"buttons": [
					{
						extend: 'excelHtml5',
						text: '<i class="fas fa-file-excel"></i> GENERAL',
						messageTop: "LISTADO DE CAJAS CHICAS",
						attr: {
							class: 'btn btn-warning'
						},
						exportOptions: {
							format:{
								header:  function (data, columnIdx) {
									return data.replace( '<input type="text" style="font-size: .9em; width: 100%;" class="form-control" data-value="', '' ).split('"')[0];
								}
							}
						}
					},
					{
						text: '<i class="fas fa-file-excel"></i> DESGLOSADO',
						action: function(){
							$("#elementos_hidden").html("");
							$('#tabla_autorizaciones_reembolso thead tr:eq(0) input').each( function (i) {
								if( valor_input[i+1] )
									$("#elementos_hidden").append( '<input type="hidden" name="'+$(this).data('value')+'" value="'+valor_input[i+1]+'">' )
							});

							$("#elementos_hidden").append( '<input type="hidden" name="tipo_reporte" value="'+$("ul.nav-tabs li.active a[data-toggle='tab']").data('value')+'">' );

							if( $("#formulario_reportecch").valid() ){
								$("#formulario_reportecch").submit();
							}

						},
						attr: {
							class: 'btn btn-success',
						}
					},
					{
						text: '<i class="fas fa-file-excel"></i> DESGLOSE CONTABILIDAD',
						action: function(){
							$("#reporte_contabilidad").modal();
						},
						attr: {
							class: 'btn btn-primary',
						}
					}
				],
				"language" : lenguaje,
				"processing": false,
				"pageLength": 20,
				"bAutoWidth": false,
				"bLengthChange": false,
				"bInfo": false,
				"scrollX": true,
				responsive: true,
				"columns": [
					{
						"width": "4%",
						"className": 'details-control',
						"orderable": false,
						"data" : null,
						"defaultContent": '<i class="fas animacion fa-caret-right"></i>'
					},
					{
						"width": "15%",
						"data": function( d ){
							return '<p style="font-size: .9em">'+(d.idpago?d.idpago:"N/A")+'</p>';
						}
					},
					{
						"width": "15%",
						"data": function( d ){
							return '<p style="font-size: .9em">'+d.responsable+'</p>';
						}
					},
					{
						"width": "12%",
						"data": function( d ){
							return '<p style="font-size: .9em">'+d.abrev+'</p>';
						}
					},
					{
						"width": "12%",
						"data": function( d ){
							return '<p style="font-size: .9em">'+formato_fechaymd(d.fecha)+'</p>';
						}
					},
					{
						"width": "12%",
						"data": function( d ){
							return '<p style="font-size: .9em">'+formatMoney( d.cantidad )+'</p>';
						}
					},
					{
						"width": "12%",
						"data": function( d ){
							return '<p style="font-size: .9em">'+( d.tipoPago ? d.tipoPago+' '+d.referencia : 'AUN SIN DEFINIR' )+'</p>';
						}
					},
					{
						"width": "12%",
						"data": function( d ){
							return '<p style="font-size: .9em">'+( d.fecha_cobro ? formato_fechaymd(d.fecha_cobro) : '---' )+'</p>';
						}
					},
					{
						"width": "15%",
						"data": function( d ){
							return '<p style="font-size: .9em">'+d.nomdepto+'</p>';
						}
					},


				],
				bSort: false,
				"ajax" : url +"Historial/tabla_reembolsos"
			});

			$('#fecInicial').change( function() {
				tabla_nueva_reembolsos.draw();
			});

			$('#fecFinal').change( function() {
				tabla_nueva_reembolsos.draw();
			});

			$('#tabla_autorizaciones_reembolso tbody').on('click', 'td.details-control', function () {

				var tr = $(this).closest('tr');
				var row = tabla_nueva_reembolsos.row( tr );

				if ( row.child.isShown() ) {

					row.child.hide();
					tr.removeClass('shown');

					$(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
				}
				else {

					if( row.data().solicitudes == null || row.data().solicitudes == "null" ){
						$.post( url + "Historial/carga_cajas_chicas" , { "idcajachicas" : row.data().idsolicitud } ).done( function( data ){

							row.data().solicitudes = JSON.parse( data );

							tabla_nueva_reembolsos.row( tr ).data( row.data() );

							row = tabla_nueva_reembolsos.row( tr );

							row.child( construir_subtablas( row.data().solicitudes ) ).show();
							tr.addClass('shown');
							$(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");

						});
					}else{
						row.child( construir_subtablas( row.data().solicitudes ) ).show();
						tr.addClass('shown');
						$(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
					}

				}
			});

			function construir_subtablas( data ){
				var solicitudes = '<table class="table">';
				$.each( data, function( i, v){
					//i es el indice y v son los valores de cada fila
					solicitudes += '<tr>';
					solicitudes += '<td>'+v.idsolicitud+'.- <b>'+'PROYECTO: '+'</b> '+v.proyecto+'</td>';
					solicitudes += '<td colspan="3">'+'<b>'+'PROVEEDOR '+'</b> '+v.nombre_proveedor+'</td>';
					solicitudes += '<td>'+'<b>'+'CANTIDAD: '+'</b> $ '+formatMoney(v.cantidad)+' '+v.moneda+'</td>';
					solicitudes += '</tr>';
					solicitudes += '<tr>';
					solicitudes += '<td>'+'<b>'+'CAPTURISTA '+'</b> '+v.nombre_capturista+'</td>';
					solicitudes += '<td>'+'<b>'+'FOLIO '+'</b> '+v.folio+'</td>';
					solicitudes += '<td>'+'<b>'+'FOLIO FISCAL'+'</b> '+v.ffiscal+'</td>';
					solicitudes += '<td>'+'<b>'+'FECHA FACT: '+'</b> '+v.fecelab+'</td>';
					solicitudes += '<td>'+'<b>'+'FECHA AUT: '+'</b> '+v.fecautorizacion+'</td>';
					solicitudes += '</tr>';
					solicitudes += '<tr>';
					solicitudes += '<td colspan="5">'+'<b>'+'JUSTIFICACIÓN: '+'</b> '+v.justificacion+'</td>';
					solicitudes += '<td colspan="5"><div class="btn-group-vertical"><button type="button" class="btn btn-primary consultar_modal notification" value="'+v.idsolicitud+'" data-value="SOL" title="Ver Solicitud"><i class="fas fa-eye"></i>'+(v.visto == 0 ? '<span class="badge">!</span>' : '')+'</button></div></td>';

					solicitudes += '</tr>';
				});

				return solicitudes += '</table>';
			}

			$('.dt-buttons').attr('style','float: right !important; margin-right: 15px;');
		});


		$.fn.dataTableExt.afnFiltering.push( function( oSettings, aData, iDataIndex ) {
			var iFini = '';
			var iFinit = '';
			$('.from').each(function(i,v) {
				if($(this).val() !=''){
					iFini = $(this).val();
					//iFinit = $(this).val().toString();
					//iFini = iFini.split('-')[2] + "/" + iFini.split('-')[1] + "/" + iFini.split('-')[0] ;
					return false;
				}
			});

			var iFfin = '';
			var iFfint = '';
			$('.to').each(function(i,v) {
				if($(this).val() !=''){
					iFfin = $(this).val();
					//iFfint = $(this).val().toString();
					//iFfin = iFfint.split('-')[2] + "/" + iFfint.split('-')[1] + "/" + iFfint.split('-')[0] ;
					return false;
				}
			});

			var iStartDateCol = 4;
			var iEndDateCol = 4;

			var meses = ['','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

			var mes1 =  typeof aData[iStartDateCol] === 'undefined' ? '' : (meses.indexOf(aData[iStartDateCol].split('/')[1]).toString()).padStart(2, "0");
			var mes2 =  typeof aData[iEndDateCol] === 'undefined' ? '' : (meses.indexOf(aData[iEndDateCol].split('/')[1]).toString()).padStart(2, "0");

			iFini=iFini.substring(6,10) + iFini.substring(3,5) + iFini.substring(0,2);
			iFfin=iFfin.substring(6,10) + iFfin.substring(3,5) + iFfin.substring(0,2);

			var datofini=aData[iStartDateCol].substring(7,11) + mes1 + aData[iStartDateCol].substring(0,2);
			var datoffin=aData[iEndDateCol].substring(7,11) + mes2 + aData[iEndDateCol].substring(0,2);

			if ( iFini === "" && iFfin === "" )
			{
				return true;
			}
			else if ( iFini <= datofini && iFfin === "")
			{
				return true;
			}
			else if ( iFfin >= datoffin && iFini === "")
			{
				return true;
			}
			else if (iFini <= datofini && iFfin >= datoffin)
			{
				return true;
			}
			return false;
		});

		$(window).resize(function(){
			tabla_nueva_reembolsos.columns.adjust();
		});

	</script>
<?php
require("footer.php");
?>
