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
						<h3>REPORTE DE FACTURAS </h3>
						<div class="row">
							<div class="col-lg-12">
								<h4 id="totalxaut"></h4>
							</div>
						</div>
					</div>
					<div class="box-body">
						<div class="col-lg-12">
							<div class="col-lg-3">
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>INICIO</b></span>
									<input class="form-control fechas_filtro from" type="text" id="fecInicial" name="fechaInicial" maxlength="10" autocomplete="off"/>
								</div>
							</div>
							<div class="col-lg-3">
								<div class="input-group">
									<span class="input-group-addon"><i class="fas fa-calendar-alt"></i> <b>FIN</b></span>
									<input class="form-control fechas_filtro to" type="text" id="fecFinal" name="fechaFinal" maxlength="10" autocomplete="off"/>
								</div>
							</div>
							<table class="table table-striped" id="tblReporte">
								<thead class="thead-dark">
								<tr>
									<th style="font-size: .8em" nowrap>EMPRESA</th>					<!--COLUMNA 0 -->
									<th style="font-size: .8em">PROYECTO</th>						<!--COLUMNA 1 -->
									<th style="font-size: .8em">FOLIO FISCAL</th>					<!--COLUMNA 2 -->
									<th style="font-size: .8em">FOLIO</th>							<!--COLUMNA 3 -->
									<th style="font-size: .8em">FECHA DE LA FACTURA</th>			<!--COLUMNA 4 -->
									<th style="font-size: .8em">PROVEEDOR</th>						<!--COLUMNA 5 -->
									<th style="font-size: .8em">SUBTOTAL TOTAL</th>					<!--COLUMNA 6 NO VISIBLE-->
									<th style="font-size: .8em">IVA</th>							<!--COLUMNA 7 NO VISIBLE-->
									<th style="font-size: .8em">PRECIO TOTAL</th>					<!--COLUMNA 8 -->
									<th style="font-size: .8em">MONEDA</th>							<!--COLUMNA 9 -->
									<th style="font-size: .8em">DESCRIPCIÓN DE LA FACTURA</th>		<!--COLUMNA 10 -->
									<th style="font-size: .8em">UUID</th>							<!--COLUMNA 11 -->
									<th style="font-size: .8em">JUSTIFICACION</th>					<!--COLUMNA 12 NO VISIBLE-->
								</tr>
								</thead>
							</table>
						</div>
					</div><!--End tab content-->
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	var rol = `<?= $this->session->userdata("inicio_sesion")['rol'] ?>`;

	var tabla_reporte;

	$("#tblReporte").ready( function () {


		$('#tblReporte thead tr:eq(0) th').each(function (i) {
				var title = $(this).text();
				$(this).html('<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="' + title + '">');

				$('input', this).on('keyup change', function () {
					if (tabla_reporte.column(i).search() !== this.value) {
						tabla_reporte
							.column(i)
							.search(this.value)
							.draw();

					}
					sumar_total();
				});
		});

		tabla_reporte = $('#tblReporte').DataTable({
			dom: 'Brtip',
			"buttons": [
				{
					extend: 'excel',
					text: '<i class="fas fa-file-excel"></i>',
					messageTop: "FACTURAS FALTANTES",
					attr: {
						class: 'btn btn-success'
					},
					exportOptions: {
						format: {
							header: function (data, columnIdx) {
								data = data.replace('<input type="text" class="form-control" style="font-size: .9em; width: 100%;" placeholder="', '');
								data = data.replace('" />', '');
								data = data.replace('">', '');
								return data;
							},
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
						}
					}
				}
			],
			"language": lenguaje,
			"processing": false,
			"pageLength": 10,
			"bAutoWidth": false,
			"bLengthChange": false,
			"bInfo": false,
			"scrollX": true,
			"initComplete": function (settings, json) {
				sumar_total();
				$('.dt-buttons').attr('style', 'float: right !important; margin-right: 15px;');
			},
			"columns": [
				{
					"width": "5%",
					"data": function (d) {

						return '<p style="font-size: .8em">' + d.EMPRESA + '</p>'
					}
				},
				{
					"width": "5%",
					"data": function (d) {

						return '<p style="font-size: .8em">' + d.PROYECTO + '</p>'
					}
				},
				{
					"width": "5%",
					"data": function (d) {

						return '<p style="font-size: .8em">' + d.FOLIO_FISCAL + '</p>'
					}
				},
				{
					"width": "5%",
					"data": function (d) {

						return '<p style="font-size: .8em">' + d.FOLIO + '</p>'
					}
				},
				{
					"width": "5%",
					"data": function (d) {

						return '<p style="font-size: .8em">' + formato_fechaymd(d.FECHAFACTURA) + '</p>'
					}
				},
				{
					"width": "5%",
					"data": function (d) {

						return '<p style="font-size: .8em">' + d.PROVEEDOR + '</p>'
					}
				},
				{
					"width": "5%",
					"data": function (d) {
						if (d.MONEDA == 'MXN') {
							return '<p style="font-size: .8em">' + new Intl.NumberFormat('es-MX',{style:'currency', currency:'MXN', minimumFractionDigits:2}).format(d.SUB_TOTAL) + '</p>'
						}else{
							return '<p style="font-size: .8em">' + d.SUB_TOTAL+ '</p>'
						}
					}
				},
				{
					"width": "5%",
					"data": function (d) {
						if (d.MONEDA == 'MXN') {
							return '<p style="font-size: .8em">' + new Intl.NumberFormat('es-MX',{style:'currency', currency:'MXN', minimumFractionDigits:2}).format(d.IVA) + '</p>'
						}else{
							return '<p style="font-size: .8em">' + d.IVA + '</p>'
						}
					}
				},
				{
					"width": "5%",
					"data": function (d) {
						if (d.MONEDA == 'MXN') {
							return '<p style="font-size: .8em">' + new Intl.NumberFormat('es-MX',{style:'currency', currency:'MXN', minimumFractionDigits:2}).format(d.PRECIO_TOTAL) + '</p>'
						}else{
							return '<p style="font-size: .8em">$' + formatMoney(d.PRECIO_TOTAL) + '</p>'
						}
					}
				},
				{
					"width": "5%",
					"data": function (d) {

						return '<p style="font-size: .8em">' + d.MONEDA + '</p>'
					}
				},
				{
					"width": "5%",
					"data": function (d) {
						let descripcion = d.DESCFACTURA;
						if(descripcion==''){
							descripcion = '---';
						}else{
							descripcion = d.DESCFACTURA;
						}
						return '<p style="font-size: .8em">' + descripcion+ '</p>'
					}
				},
				{
					"width": "5%",
					"data": function (d) {

						return '<p style="font-size: .8em">' + d.UUID + '</p>'
					}
				},
				{
					"width": "5%",
					"data": function (d) {
						return '<p style="font-size: .8em">' + d.JUSTIFICACION + '</p>'
					}
				},
			],
			"columnDefs": [
				{
					"targets": [6, 7, 12],
					"visible": false
            	}
			],
			"ajax": url + "Solicitante/getReporteFinanzas"
		});
	});

	$.fn.dataTableExt.afnFiltering.push(
		function( oSettings, aData, iDataIndex ) {
			if( oSettings.nTable.id == "tblReporte" && ($("#fecInicial").val()!='' || $("#fecFinal").val()!= '' )){
				var mes = new Map([
					["Ene","01"],
					["Feb","02"],
					["Mar","03"],
					["Abr","04"],
					["May","05"],
					["Jun","06"],
					["Jul","07"],
					["Ago","08"],
					["Sep","09"],
					["Oct","10"],
					["Nov","11"],
					["Dic","12"],
				]);


				var iFini = document.getElementById('fecInicial').value;
				var iFfin = document.getElementById('fecFinal').value;
				var iStartDateCol = 4;
				var iEndDateCol = 4;

				iFini=iFini.substring(6,10) + iFini.substring(3,5)+ iFini.substring(0,2);
				iFfin=iFfin.substring(6,10) + iFfin.substring(3,5)+ iFfin.substring(0,2);

				//28/Nov/2019
				//01 2 345 6 7890

				var datofini=aData[iStartDateCol].trim().substring(7,11)+mes.get( aData[iStartDateCol].trim().substring(3,6))+aData[iStartDateCol].trim().substring(0,2);
				var datoffin=aData[iEndDateCol].trim().substring(7,11) + mes.get(aData[iEndDateCol].trim().substring(3,6))+aData[iEndDateCol].trim().substring(0,2);

				//console.log("Fecha sel: "+iFini+" "+iFfin+" Fecha tbl: "+datofini);

				if ( iFini === "" && iFfin === "" ){
					return true;
				}else if ( iFini <= datofini && iFfin === ""){
					return true;
				}else if ( iFfin >= datoffin && iFini === ""){
					return true;
				}else if (iFini <= datofini && iFfin >= datoffin){
					return true;
				}
				return false;
			}else
				return true;
		}
	);

	$('.fechas_filtro').datepicker({
		format: 'dd/mm/yyyy',
		autoclose: true
	});

	$('#fecInicial').change( function() {
		tabla_reporte.draw();
		sumar_total();
	}).on('dp.change', function (selected) {
		end.data("DateTimePicker").minDate(selected.date);
	});

	$('#fecFinal').change( function() {  tabla_reporte.draw(); sumar_total(); } );
	function sumar_total(){
		var total = 0;
		var index = tabla_reporte.rows( { selected: true, search: 'applied' } ).indexes();
		var data = tabla_reporte.rows( index ).data();
		$.each(data, function(i, v){
			total += parseFloat(v.PRECIO_TOTAL.replace(/[^0-9.-]+/g,""));
			//console.log(formatMoney(total.toString()));
		});
		var to1 = formatMoney(total);
		$("#totalxaut").html("<b>Total:</b> $"+to1);
	}
</script>

<?php
require("footer.php");
?>
