<?php
require("head.php");
require("menu_navegador.php");
?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">
				<div class="box">
					<div class="box-header">
						<h3>DIRECCIÓN GENERAL REEMBOLSOS</h3>
					</div>
					<div class="box-body">
						<?php if ($this->session->userdata('inicio_sesion')["id"] !== '2636'): ?>  <!-- INICIO FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
							<div class="row">
								<div class="col-lg-6 col-md-6">
									<h4>TOTAL POR AUTORIZAR: $<span id="totpagarC" style="font-weight: bold;"></span></h4>
								</div>
								<div class="col-lg-6 col-md-6 text-right">
									<div class="btn-group">  
										<button class="btn btn-info btn-seleccionadas" onclick="autorizarSeleccionadasCajaChica()">AUTORIZAR SELECCIONADAS</button>
									</div>			
								</div>
							</div> 
						<?php endif; ?> <!-- FIN FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
						<hr>
						<table class="table table-responsive table-bordered table-striped table-hover" id="solReembolsos">
							<thead>
							<tr>
								<th></th>
								<th><i style="cursor: pointer;" class="fa fa-check-square seleccionar_todas text-red" title="Seleccionar todas los reembolsos."></i></th> <!-- FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
								<th style="font-size: .9em">RESPONSABLE</th>
								<th style="font-size: .9em">EMPRESA</th>
								<th style="font-size: .9em">FECHA FACTURA</th>
								<th style="font-size: .9em">CANTIDAD</th>
								<th style="font-size: .9em">DEPARTAMENTO</th>
							</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="modalDeclinaPagoCH" class="modal fade modal modal-alertas" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="formularioCJ">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">DECLINAR PAGO REEMBOLSOS</h4>
					</div>
					<div class="modal-body">
						<textarea id="motivoDeclinadaPagoCCH" name="motivoDeclinadaPagoCCH" class="form-control" rows="4" cols="10" placeholder="Razón de la declinación" required></textarea>
					</div>
					<div class="modal-footer">
						<div class="row">
							<div class="col-lg-12">
								<div class="btn-group" role="group" aria-label="Basic example"> <!-- FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> -->
									<button type="submit" id="boton_rechazarCH" class="btn btn-success boton_rechazarCH">ACEPTAR</button>
									<button type="button" class="btn btn-danger" data-dismiss="modal">CANCELAR</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">

		var idSol;
		var tabla_reembolsos;
		var tr;
		var tota2 = 0;
		var idusuario = <?= $this->session->userdata('inicio_sesion')["id"] ?> === 2636; /** FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

		$(".seleccionar_todas").click( function(){

			tota2 = 0;

			$( tabla_reembolsos.$('input[type="checkbox"]')).each( function( i, v ){

				if( !$(this).prop("checked") ){
					$(this).prop("checked", true);
					tota2 += parseFloat( tabla_reembolsos.row( $(this).closest('tr') ).data().Cantidad );
				}else{
					$(this).prop("checked", false);
				}

				$("#totpagarC").html(formatMoney(tota2));
			});
		});

		$("#solReembolsos").ready( function () {

			$('#solReembolsos thead tr:eq(0) th').each( function (i) {
				if( i != 0 && i!=1 && i!=5 && i != 7){
					var title = $(this).text();
					$(this).html( '<input type="text" style="width:100%;" placeholder="'+title+'" />' );

					$( 'input', this ).on( 'keyup change', function () {
						if ( tabla_reembolsos.column(i).search() !== this.value ) {
							tabla_reembolsos
								.column(i)
								.search( this.value )
								.draw();
						}
					} );
				}
			});

			tabla_reembolsos = $('#solReembolsos').DataTable({
				dom: 'rtip',
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
						"width": "1%",
						"className": 'details-control',
						"orderable": false,
						"data" : null,
						"defaultContent": '<i class="fas animacion fa-caret-right"></i>'
					},
					{
						"width": "1%"

					},
					{
						"width": "8%",
						"orderable": false,
						"data": function(d){
							return '<p style="font-size: .8em">'+d.Responsable+'</p>'
						}
					},
					{
						"width": "8%",
						"orderable": false,
						"data": function(d){
							return '<p style="font-size: .8em">'+d.abrev+'</p>'
						}
					},
					{
						"width": "8%",
						"orderable": false,
						"data": function(d){
							return '<p style="font-size: .8em">'+d.FECHAFACP+'</p>'
						}
					},
					{
						"width": "8%",
						"orderable": false,
						"data" : function( d ){
							return "$ " + formatMoney( d.Cantidad )+" MXN";

						}
					},
					{
						"width": "15%",
						"orderable": false,
						"data" : "Departamento"
					}
				],
				columnDefs: [
					{
						"searchable": false,
						"orderable": false,
						"targets": 0
					},
					{
						orderable: false,
						className: 'select-checkbox',
						targets:  1,
						'searchable':false,
						'className': 'dt-body-center',
						'render': function (d, type, full, meta){

							return '<input type="checkbox" name="id[]" style="width:20px;height:20px;">';

						},
						select: {
							style:    'os',
							selector: 'td:first-child'
						},
					}],
				"order": [[2, 'asc']],
				"ajax": url + "DireccionGeneral/tablaSolReemb"


			});

			if (!idusuario) { /** FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
				tabla_reembolsos.column(1).visible(true);
			}else{
				tabla_reembolsos.column(1).visible(false);
			} /** FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

			var detailRows = [];

			$('#solReembolsos tbody').on( 'click', 'tr td.details-control', function (){

				var tr = $(this).closest('tr');
				var row = tabla_reembolsos.row( tr );
				var idx = $.inArray( tr.attr('id'), detailRows );

				if ( row.child.isShown() ) {

					row.child.hide();
					tr.removeClass('shown');

					$(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
				}
				else {
					if( row.data().solicitudes == null || row.data().solicitudes == "null" ){
						$.post( url + "DireccionGeneral/registros_cajaschicas" , { "ID" : row.data()['ID'] } ).done( function( data ){


							row.data().solicitudes = JSON.parse( data ).solicitudes;
							tabla_reembolsos.row( tr ).data( row.data() );

							row = tabla_reembolsos.row( tr );

							row.child( format( row.data() ) ).show();
							tr.addClass('shown');
							$(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");

						});
					}else{
						row.child( format( row.data() ) ).show();
						tr.addClass('shown');
						$(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
					}

					if ( idx === -1 ) {
						detailRows.push( tr.attr('id') );
					}
				}
			});

			var index_cancela;

			$("#formularioCJ").submit( function(e) {
				e.preventDefault();
			}).validate({
				submitHandler: function( form ) {

					$('#modalDeclinaPagoCH').modal('show');

					idSol = $("#boton_rechazarCH").val();

					var observacion = $("textarea#motivoDeclinadaPagoCCH").val();
					$.ajax({
						url : url + "DireccionGeneral/PagoDeclinadoCH",
						type : "POST" ,
						dataType : "json" ,
						data :{ id_sol : idSol , Obervacion : observacion },
						error : function( data ){
						},
						complete: function( data ){
							$("#modalDeclinaPagoCH").modal("toggle");
							var row = tabla_reembolsos.row( tr );
							row.data().solicitudes.splice(index_cancela,1);
							row.child( format( row.data() ) ).show();
							tabla_reembolsos.ajax.reload();

						}
					});
				}
			});

			$('#solReembolsos').on( "click", ".btn-recharzarPagoCH", function( e ){
				index_cancela = $(this).attr("data-value");

				var row = tabla_reembolsos.row( tr );
				console.log( row.data().solicitudes );
				$(".boton_rechazarCH").val( row.data().solicitudes[index_cancela].idsolicitud);
				$("#motivoDeclinadaPagoCCH").val("");
				$('#modalDeclinaPagoCH').modal();
			});

			$('#solReembolsos').on( 'click', 'input', function () {
				tr = $(this).closest('tr');
				var row = tabla_reembolsos.row( tr );

				if($(this).prop("checked")){
					tota2 += parseFloat(row.data().Cantidad);
				}else{
					tota2 -= parseFloat(row.data().Cantidad);
				}

				$("#totpagarC").html(formatMoney(tota2));
			});
		});


		function autorizarSeleccionadasCajaChica(){

			var apagar = [];

			$( tabla_reembolsos.$('input[type="checkbox"]:checked')).each(function(index,va){

				if($(this).is(":checked")){
					tr = $(this).closest('tr');
					var row = tabla_reembolsos.row( tr );
					apagar.push({ idsolicitud : row.data().ID , totalpagar : row.data().Cantidad ,idempresa : row.data().Empresa , nomdepto : row.data().Departamento
						, idresponsable : row.data().IDR });
				}
			});
			if(window.confirm('Se pagará el total autorizado.\nEl total es de $ '+ formatMoney(tota2)+' ¿Estás de acuerdo?')){
				$.post( url + "DireccionGeneral/PagarTotalReembolsos", {jsonApagar : JSON.stringify(apagar)} ).done( function( data ){

					data = JSON.parse( data );

					if( data.resultado ){
						tota2 = 0;
						$("#totpagarC").html(formatMoney(0));
						//tabla_caja_chica.ajax.reload();

						tabla_reembolsos.clear();
						tabla_reembolsos.rows.add( data.data );
						tabla_reembolsos.draw();
					}else{
						alert("Parece que ha ocurrido un problema puede intentar nuevamente.");
					}

				});
			}
		}

		function format ( d ) {

			var solicitudes = '<table id="tblCaja" class="table" style="font-size: .8em">';

			solicitudes += '<tr>';
			solicitudes += '<th><b>'+'PROYECTO'+'</b></th>';
			solicitudes += '<th>'+'<b>'+'ETAPA'+'</b></td>';
			solicitudes += '<th>'+'<b>'+'CONDOMINIO '+'</b></th>';
			solicitudes += '<th>'+'<b>'+'PROVEEDOR '+'</b></th>';
			solicitudes += '<th>'+'<b>'+'FECHA FACTURA'+'</b></th>';
			solicitudes += '<th>'+'<b>'+'CANTIDAD '+'</b></th>';
			solicitudes += '</tr>';

			//i es el indice y v son los valores de cada fila
			$.each( d.solicitudes, function( i, v){
				solicitudes += '<tr id="myTableRow-'+v.idsolicitud+'">';
				solicitudes += '<td>'+(i+1)+'.- '+v.proyecto+'</td>';
				solicitudes += '<td>'+v.etapa+'</td>';
				solicitudes += '<td>'+v.condominio+'</td>';
				solicitudes += '<td>'+v.proveedor+'</td>';
				solicitudes += '<td>'+v.fechafacp+'</td>';
				solicitudes += '<td>'+formatMoney(v.cantidad)+'</td>';

				/** INICIO FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */
				solicitudes += `<td>
									<div class="btn-group-vertical">
										<button id = "verSol" name="verSol" class="btn btn-instagram consultar_modal notification btn-sm" value="${v.idsolicitud}"\n\ data-value="SOL" data-toggle="tooltip" data-placement="bottom" title="Ver Solicitud">
											<i class="fa fa-eye">${v.visto == 0 ? '</i><span class="badge">!</span>' : ''}</i>
										</button>
										${!idusuario ? `
											<button value="${v.idsolicitud}" data-value="${i}" class="btn btn-danger btn-recharzarPagoCH btn-sm" title="Declinar Pago">
												<i class="fas fa-times"></i>
											</button> ` 
										: ''}
									</div>
								</td>
				`;
				/** FIN FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> */

				solicitudes += '<tr>';
				solicitudes += '<td colspan="6"><b>'+'JUSTIFICACIÓN '+'</b>'+v.observacion+'</td>';
				solicitudes += '</tr>';
			});

			return solicitudes+'</table>';
		}

	</script>
<?php
require("footer.php");
?>
