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
						<h3>HISTORIAL VIÁTICOS  <small><i class="far fa-question-circle faq" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Autorizaciones" data-content="Estas son todas las solicitudes de viáticos, cajas chicas cerradas en transito o ya pagadas." data-placement="right"></i></small></h3>
					</div>
					<div class="box-body">
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" data-value="viaticos_pagadas" href="#caja_viaticos">VIÁTICOS PAGADOS</a></li>
								<li><a data-toggle="tab" data-value="viaticos_en_transito" href="#caja_chicacaja_viaticos">VIÁTICOS EN TRÁNSITO</a></li>
								<li><a data-toggle="tab" data-value="viaticos_cerrados" href="#caja_viaticos_cerrados">VIÁTICOS CERRADOS</a></li>
							</ul>
						</div>
						<div class="tab-content">
							<div id="caja_viaticos" class="tab-pane fade in active">
								<div class="row">
									<form id="formulario_repviaticos" autocomplete="off" action="<?= site_url("Historial/reporte_viaticos") ?>" method="post">
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
									<table class="table table-responsive table-bordered table-striped table-hover" id="tabla_autorizaciones_viaticos">
										<thead class="thead-dark">
										<tr>
											<th style="font-size: .8em"></th>
											<th style="font-size: .8em"># PAGO</th>
											<th style="font-size: .8em">RESPONSABLE</th>
											<th style="font-size: .8em">PERTENECE REEMBOLSO</th>
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

<div class="modal fade" id="reporte_contabilidad" role="dialog">
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

	var tabla_nueva_viaticos;
	var viaticos_pagados = true;
	var viat_pagadas = [];
	var valor_input = Array( $('#tabla_autorizaciones_viaticos th').length );
	btn_remover = false;
	var proyectos = [];
	var id_empresa ;

	$('[data-toggle="tab"]').click( function(e) {

		viaticos_pagados = false;

		switch( $(this).data('value') ){
			case 'viaticos_pagadas':
				viaticos_pagados = true;
				btn_remover = false;
				tabla_nueva_viaticos.ajax.url( url +"Historial/tabla_viaticos" ).load();
				tabla_nueva_viaticos.button().add( 2, {
					text: '<i class="fas fa-file-excel"></i> DESGLOSE CONTABILIDAD',
					action: function(){
						$("#reporte_contabilidad").modal();
					},
					attr: {
						class: 'btn btn-primary',
					}
				});
				break;
			case 'viaticos_en_transito':
				tabla_nueva_viaticos.ajax.url( url +"Historial/tabla_viaticos_transito" ).load();
				break;
			case 'viaticos_cerrados':
				tabla_nueva_viaticos.ajax.url( url +"Historial/viaticos_cerrados" ).load();
				break;
		}
	});

	$('.fechas_filtro').datepicker({
		format: 'dd/mm/yyyy',
		endDate: '-0d',
		orientation: 'bottom auto',
        autoclose: true
	});

	$("#tabla_autorizaciones_viaticos").ready( function () {

		$('#tabla_autorizaciones_viaticos thead tr:eq(0) th').each( function (i) {
			if( i != 0 ){
				var title = $(this).text();
				var vari = (((title.replace(" ","")).replace("É","E")).substring(0,6)).toLowerCase();
				$(this).html( '<input type="text" style="font-size: .9em; width: 100%;" class="form-control" data-value="'+title+'" placeholder="'+title+'" />' );
				$('input', this).on('keyup change', function() {

					valor_input[i] = this.value;

					if (tabla_nueva_viaticos.column(i).search() !== this.value ) {
						tabla_nueva_viaticos
							.column(i)
							.search( this.value )
							.draw();
					}
				});
			}

		});

		$('#tabla_autorizaciones_viaticos').on('xhr.dt', function ( e, settings, json, xhr ) {

			if( json.permiso_desglose === false && !btn_remover ){
				tabla_nueva_viaticos.button( '2' ).remove();
				btn_remover = true;
			}else{
				if( viaticos_pagados ){
					$("#depto_cch, #resp_cch, #proy_cch, #empresa_cch").html("<option value=''>Seleccione una opción</option>");

					$.each( json.data, function( i, v ){
						if( !viat_pagadas[ v.nomdepto ] ){
							viat_pagadas[ v.nomdepto ] = [];
						}
						if( !viat_pagadas[ v.nomdepto ][ v.responsable ] ){
							viat_pagadas[ v.nomdepto ][ v.responsable ] = [];
						}
						viat_pagadas[ v.nomdepto ][ v.responsable ].push( { "idempresa" : v.idempresa, "empresa" : v.abrev, "cantidad" : v.cantidad,"idpago" : v.idpago, "fecha_operacion" : v.fecha } );
					});
					$.each( Object.keys( viat_pagadas ).sort(), function( i, v ){
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
			$.each( Object.keys( viat_pagadas[ $(this).val() ] ).sort(), function( i, v ){
				$("#resp_cch").append("<option value='"+v+"'>"+v+"</option>");
			});
		});

		$("#resp_cch").change( function(){
			$("#reeb_cch").html("<option value=''>Seleccione una opción</option>");
			$.each( viat_pagadas[ $( "#depto_cch" ).val() ][ $(this).val() ], function( i, v ){
				$("#reeb_cch").append( '<option value="'+v.idpago+'">#'+v.idpago+" | "+formato_fechaymd(v.fecha_operacion)+", "+v.empresa+" $ "+formatMoney(v.cantidad)+'</option>' )
			});
		});

		$("#reeb_cch").change(function(){
			$("#proy_cch").empty();
			$("#proy_cch").html("<option value=''>Seleccione una opción</option>");
			id_pago=$(this).val();
			$.each(viat_pagadas[ $( "#depto_cch" ).val() ][ $("#resp_cch").val() ], function(i,v){
				if(v.idpago==id_pago){
					id_empresa = v.idempresa;
				}
			});
			$.each(proyectos.filter(proyecto => proyecto["idempresa"]==id_empresa ), function( i, v ){
				$("#proy_cch").append("<option value='"+v.nproyecto_neo+"'>"+v.nproyecto_neo+"</option>"); });
		});

		$("#fpago_cch").change( function(){

			$.each( viat_pagadas[ $( "#depto_cch" ).val() ][ $(this).val() ], function( i, v ){
				if( (v.fecha_operacion).substring( 0, 7 ) == $("#fpago_cch").val() ){

				}
			});
		});

		tabla_nueva_viaticos = $('#tabla_autorizaciones_viaticos').DataTable({
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
						},
						columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
					}
				},
				{
					text: '<i class="fas fa-file-excel"></i> DESGLOSADO',
					action: function(){
						$("#elementos_hidden").html("");
						$('#tabla_autorizaciones_viaticos thead tr:eq(0) input').each( function (i) {
							if( valor_input[i+1] )
								$("#elementos_hidden").append( '<input type="hidden" name="'+$(this).data('value')+'" value="'+valor_input[i+1]+'">' )
						});
						$("#elementos_hidden").append( '<input type="hidden" name="tipo_reporte" value="'+$("ul.nav-tabs li.active a[data-toggle='tab']").data('value')+'">' );

						if( $("#formulario_repviaticos").valid() ){
							$("#formulario_repviaticos").submit();
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
                    "width": "15%",
                    "data": function( d ){
                        return '<p style="font-size: .9em">'+(d.nombre_reembolso_cch ? d.nombre_reembolso_cch : 'NA')+'</p>';
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
			"ajax" : url +"Historial/tabla_viaticos"
		});

		$('#fecInicial').change( function() {
			tabla_nueva_viaticos.draw();
		});

		$('#fecFinal').change( function() {
			tabla_nueva_viaticos.draw();
		});

		$('#tabla_autorizaciones_viaticos tbody').on('click', 'td.details-control', function () {

			var tr = $(this).closest('tr');
			var row = tabla_nueva_viaticos.row( tr );

			if ( row.child.isShown() ) {

				row.child.hide();
				tr.removeClass('shown');

				$(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
			}
			else {

				if( row.data().solicitudes == null || row.data().solicitudes == "null" ){
					$.post( url + "Historial/carga_cajas_chicas" , { "idcajachicas" : row.data().idsolicitud } ).done( function( data ){

						row.data().solicitudes = JSON.parse( data );

						tabla_nueva_viaticos.row( tr ).data( row.data() );

						row = tabla_nueva_viaticos.row( tr );

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
			var solicitudes = '<div class="container" style="width: 100%; padding-top: 10px; padding-bottom: 10px; border-left: 1px solid #D2D6DE; border-right: 1px solid #D2D6DE; border-bottom: 1px solid #D2D6DE; border-radius: 0 0 5px 5px;">';
            $.each( data, function( i, v){
                //i es el indice y v son los valores de cada fila
                solicitudes += `<div class="row" style="padding-right: 10px; padding-left: 10px;">
                    <div class="row">
                        <div class="col-md-12">
                            <p><b>NO. SOLICITUD:</b> ${v.idsolicitud} </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <p><b>PROYECTO:</b> ${v.proyecto} </p>
                            <p><b>PROVEEDOR:</b> ${v.nombre_proveedor} </p>
                            <p><b>CANTIDAD:</b> ${formatMoney(v.cantidad) + ' ' + v.moneda} </p>

							<p><b>PAÍS:</b> ${v.pais ? v.pais : 'NA'}</p>
							<p><b>NÚMERO DE COLABORADORES:</b> ${v.colabs ? v.colabs : 'NA'}</p>
                        </div>
                        <div class="col-md-4">
                            <p><b>FECHA FACT:</b> ${v.fecelab} </p>
                            <p><b>FECHA AUT:</b> ${v.fecautorizacion} </p>
                            <p><b>TIPO SOLICITUD:</b> ${v.tipo_sol} </p>

							<p><b>ZONA:</b> ${v.zona ? v.zona : 'NA'}</p>
							<p><b>TIPO INSUMO:</b> ${v.tipo_insumo ? v.tipo_insumo : 'NA'}</p>
                        </div>
                        <div class="col-md-4">
                        	<p><b>CAPTURISTA:</b> ${v.nombre_capturista} </p>
                            <p><b>FOLIO:</b> ${v.folio} </p>
                            <p><b>FOLIO FISCAL:</b> ${v.ffiscal} </p>
							<p><b>ESTADO:</b> ${v.estado ? v.estado : 'NA'}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p><b>JUSTIFICACIÓN:</b> ${v.justificacion} </p>
                            <button type="button" class="btn btn-primary consultar_modal notification" value="${v.idsolicitud}" data-value="SOL" title="Ver Solicitud">
                                <i class="fas fa-eye"></i> VER SOLICITUD 
                                ${(v.visto == 0 ? '<span class="badge">!</span>' : '')}
                            </button>
                        </div>
                    </div>
                </div>
                    ${(i < data.length - 1 ? '<hr style="border-top: 1px solid #BEBEBE; margin-top: 10px; margin-bottom: 10px;">' : '')}`;
            });
            solicitudes += '</div>';
            return solicitudes;
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

		var iStartDateCol = 5;
		var iEndDateCol = 5;

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
		tabla_nueva_viaticos.columns.adjust();
	});

</script>
<?php
require("footer.php");
?>
