<?php
	require("head.php");
	require("menu_navegador.php");
?>
 <style>
	.grupo-inputs {
		border: 1px solid #ccc;
		padding: 10px;
		margin-bottom: 10px;
		border-radius: 3px;
	}
</style>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div class="box">
                    <div class="box-header with-border">
                        <h3>ALTA DE PROYECTO/DEPARTAMENTO - OFICINAS/SEDES</h3>
						<div class="box-body">
							<div class="nav-tabs-custom">
								<ul class="nav nav-tabs">
									<li class="active"><a id="tabla_departamento_oficinas-tab" data-toggle="tab" href="#departamento_oficinas" role="tab" aria-controls="#home" aria-selected="true">PROYECTO/DEPARTAMENTO</a></li>
									<li><a id="tabla_oficinas_sedes-tab" data-toggle="tab" href="#oficinas_sedes" role="tab" aria-controls="#oficinas_sedes" aria-selected="true">OFICINAS/SEDES</a></li>
								</ul>
							</div>
							<div class="tab-content">
								<div class="active tab-pane" id="departamento_oficinas">
									<div class="box-body">
										<div class="row">
											<div class="col-lg-12">
												<table class="table table-striped" id="tabla_departamento_oficinas" name="tabla_departamento_oficinas">
													<thead class="thead-dark">
														<tr>
															<th>#</th>
															<th>PROYECTO/DEPARTAMENTO</th>
															<th>ESTATUS</th>
															<th></th>
														</tr>
													</thead>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="oficinas_sedes">
									<div class="box-body">
										<div class="row">
											<div class="col-lg-12">
												<table class="table table-striped" id="tabla_oficinas_sedes" name="tabla_oficinas_sedes">
													<thead class="thead-dark">
														<tr>
															<th>#</th>
															<th>OFICINAS/SEDES</th>
															<th>ESTADO</th>
															<th>ESTADO_ID</th>
															<th>ESTATUS</th>
															<th></th>
														</tr>
													</thead>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="info_proyectoModal" role="dialog">
    <div class="modal-dialog">
               <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="exampleModalLabel">INFORMACIÓN DEL PROYECTO/DEPARTAMENTO</h4>
            </div>
            <div class="modal-body">
                <form id="proyecto_oficina_form">
                    <div id="updateInfoProyecto">
                        <div class="row">
                            <div class="col-lg-12 form-group">
                                <label><b>NOMBRE PROYECTO/DEPARTAMENTO</b><span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="proyecto_departamento" name="proyecto_departamento">
                            </div>
                            <div class="col-lg-12 form-group">
								<label><b>OFICINAS(S)/SEDE(S)</b><span class="text-danger">*</span></label>
								<select id="oficinas" class="form-control oficinas" name="oficinas[]" multiple="multiple"></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 form-group">
                            <h5><b>ESTATUS DEL PROYECTO/DEPARTAMENTO</b><span class="text-danger">*</span></h5>
                            <label class="radio-inline"><input type="radio" name="estatus" value="1" checked required>ACTIVO</label>
                            <label class="radio-inline"><input type="radio" name="estatus" value="0" >INHABILITADO</label>
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

<div class="modal fade" id="info_AgregarOficinas" role="dialog">
    <div class="modal-dialog">
		<div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="exampleModalLabel">AGREGAR OFICINA/SEDE (S)</h4>
            </div>
            <div class="modal-body">
				<form id="form-oficina-sede">
					<div id="divOficinaSede">
						<div class="grupo-inputs form-group">
							<div class="col-lg-12 form-group">
								<label><b>NOMBRE OFICINA/SEDE</b></label>
								<input type="text" class="form-control" name="oficina_sede_0">
								<div class="text-danger" style="display: none;"><b>*</b>Este campo es obligatorio.</div>
							</div>
							<div class="col-lg-12 form-group">
								<label><b>ESTADO</b></label>
								<select class="form-control" name="estados_0" id="estados_0"></select>
								<div class="text-danger" style="display: none;"><b>*</b>Este campo es obligatorio.</div>
							</div>
							<div class="col-lg-12 form-group" id="estatus_oficina_sede">
								<h5><b>ESTATUS DEL OFICINA/SEDE</b><span class="text-danger">*</span></h5>
								<label class="radio-inline"><input type="radio" name="estatus" value="1" checked>ACTIVO</label>
								<label class="radio-inline"><input type="radio" name="estatus" value="0">INHABILITADO</label>
								<div class="text-danger" style="display: none;"><b>*</b>Este campo es obligatorio.</div>
							</div>
							<button type="button" class="btn btn-danger btn-sm remove-button" onclick="quitarGrupo(this)" id="quitar">QUITAR <i class="fa fa-trash" aria-hidden="true"></i></button>
						</div>
					</div>
					<button type="button" class="btn btn-warning" onclick="agregarGrupo()" id="agregar">AGREGAR OTRO REGISTRO</button>
					<button class="btn btn-success">GUARDAR</button>
				</form>
            </div>
        </div>
    </div>
</div>

<script>
	$( document ).ready(function(){
		$( "#oficinas" ).select2({
			allowClear: true,
			placeholder: "---Seleccione una opción---",
			enableFiltering: true,
			buttonWidth:"100%"
		});
	});
			
	$("#tabla_departamento_oficinas").ready( function () {
		
		$('#tabla_departamento_oficinas thead tr:eq(0) th').each( function (i) {
			if( i < $('#tabla_departamento_oficinas thead tr:eq(0) th').length - 1 ){
				var title = $(this).text();
				$(this).html( '<input type="text" style="font-size: .8em; width: 100%;" class="form-control" placeholder="'+title+'" />' );

				$( 'input', this ).on( 'keyup change', function () {
					if ( tabla_departamento_oficinas.column(i).search() !== this.value ) {
						tabla_departamento_oficinas
							.column(i)
							.search( this.value )
							.draw();
					}
				} );
			}
		});

		tabla_departamento_oficinas = $('#tabla_departamento_oficinas').DataTable({
			dom: 'Brtip',
			"buttons": [
				{
					extend: 'excelHtml5',             
					text: '<i class="fas fa-plus"></i> NUEVO PROYECTO/DEPARTAMENTO',
					messageTop: "Nuevo Proyecto",
					attr: {
						class: 'btn btn-success btn-registrar',
					},action : function(e){
						accion('0,',"registrar");
					}
				},
			],
			"language" : lenguaje,
			"processing": false,
			"pageLength": 10,
			"bAutoWidth": false,
			"bLengthChange": false,
			"bInfo": false,
			"searching": true,
			"scrollX":true,
			"columns": [
				{ 
					"data": function( d ){
						return '<p style="font-size: .8em">'+d.idProyectos+'</p>';
					} 
				},
				{ 
					"data": function( d ){
						return '<p style="font-size: .8em">'+(d.nombre_proyecto.toUpperCase())+'</p>';
					} 
				},
				{
					"data": function( d ){
						return '<p style="font-size: .8em">'+(d.estatus == 1 ? "<label class='label pull-center bg-green'>ACTIVO</label>" : "<label class='label pull-center bg-red'>DESACTIVADO</label>")+'</p>';
					}
				},
				{
					"data": function( d ){
						opciones = '<div class="btn-group-vertical" role="group">';
							opciones += '<button type="button" class="btn btn-warning btn-editar_d btn-sm" title="Editar Proyecto"><i class="fas fa-user-edit"></i></button>';
						return opciones + '</div>';
					}
				},
			],
			"columnDefs": [
                {
                    "targets": [ 3 ],
                    "visible": true,
                    "searchable": false,
					"orderable": false
                }
            ],
			"ajax": {
				"url": url + "Departamento_sedes/tablaProyectoDepartamentos",
				"type": "POST",
				cache: false,
			}
		});

		function accion(datos, accion) {
			datos = datos.split(",");
			$("#idregistro").val($.trim(datos[0]));
			document.getElementById("updateInfoProyecto").style.display = 'block'
				$("#info_proyectoModal").modal();
				$("#accion").val(accion);
				if (accion == "registrar") {
					$("#info_proyectoModal .modal-title").text("NUEVO REGISTRO");
					limpia_form();
				} else if (accion == "editar") {
					$("#info_proyectoModal .modal-title").text("EDITANDO REGISTRO #" + datos[0]);
				}
		};

		var $oficinasSelect = $("#oficinas");
		var $infoProyectoModal = $("#info_proyectoModal");
		var $proyectoOficinaFormInputs = $("#proyecto_oficina_form input.form-control, #proyecto_oficina_form select");
		var idproyecto;
		var $infoAgregarOficinas = $("#info_AgregarOficinas");

		function limpia_form() {
			$oficinasSelect.val("").trigger('change');
			$proyectoOficinaFormInputs.val("").prop( "required" ,true);
			idproyecto = '';
			$("input[name='estatus'][value='1']").prop("checked", true);
		}

		function cargarOpcionesOficinas() {
			$.ajax({
				url: url + 'Listas_select/obtenerOficinas',
				type: 'POST',
				dataType: 'json',
				success: function(data) {
					$.each(data.todasLasOficinas, function(i, v) {
						$oficinasSelect.append('<option value="' + v.idOficina + '">' + v.oficina + '</option>');
					});
				},
				error: function(xhr, status, error) {
					console.error(error);
				}
			});
		}

		
		$("#tabla_departamento_oficinas").on("click", ".btn-editar_d", function() {
			limpia_form();
			var row = tabla_departamento_oficinas.row($(this).closest('tr'));
			
			var rowData = row.data();
			idproyecto = rowData.idProyectos;
			accion(idproyecto + ",", "editar");
			$("input[name='proyecto_departamento']").val(rowData.nombre_proyecto);
			$("input[name='estatus'][value='"+rowData.estatus+"']").prop( "checked", true );

			// Mostrar modal
			$infoProyectoModal.modal();

			// Obtener las oficinas
			$.ajax({
				url: url + 'Listas_select/obtenerOficinas',
				type: 'POST',
				dataType: 'json',
				data: {
					idProyecto: idproyecto
				},
				success: function(data) {
					var options = data.todasLasOficinas.map(function(oficina) {
						return '<option value="' + oficina.idOficina + '">' + oficina.oficina + '</option>';
					}).join('');
					$oficinasSelect.html(options);
					var selectedValues = data.oficinasProyecto.map(function(x) {
						return x.idOficina;
					});
					$oficinasSelect.val(selectedValues).trigger('change');
				},
				error: function(xhr, status, error) {
					console.error(error);
				}
			});
		});

		$("#proyecto_oficina_form").on("click", ".btn-agregar-oficina", function(){
			$infoAgregarOficinas.modal();
		});
		

		$('.btn-registrar').ready(function() {
			limpia_form();
			cargarOpcionesOficinas();
		});

		$('#proyecto_oficina_form').submit(function(e) {
			e.preventDefault();
		}).validate({
			submitHandler: function( form ) {
				$("#info_proyectoModal .form-control").prop( "disabled", false );

				var data = new FormData( $(form)[0] );
				data.append('idproyecto', idproyecto);

				link_post = 'Departamento_sedes/createUpdate_proyecto_departamento';

				var resultado = enviar_post(data,link_post);

				if( !resultado ){
					alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
				}else{
					$("#info_proyectoModal").modal( 'toggle' );
					tabla_departamento_oficinas.ajax.reload();
				}
			}
		});
	});


	// /**OFICINAS
	$("#tabla_oficinas_sedes").ready( function () {
		$("#estados_0").select2({
			allowClear: true,
			placeholder: "---Seleccione una opción---",
			width: "100%"
		});

		var $estatus_oficina_sede = $("#estatus_oficina_sede").hide();
		var $agregar = $("#agregar").show();
		var $quitar = $("#quitar").show();
		var $estadosSelect = $("#estados_0");
		var estadosOptions = [];
		var idOficina;

		function cargarEstados() {
			$.ajax({
				url: url + 'Listas_select/lista_estados',
				type: 'POST',
				dataType: 'json',
				success: function (data) {
					if (data.lista_estados) {
						estadosOptions = data.lista_estados.map(v => `<option value="${v.id_estado}">${v.estado}</option>`);
						$estadosSelect.append('<option value="">---Seleccione una Opción---</option>' + estadosOptions.join(''));
					} else {
						console.error('Formato de datos incorrecto:', data);
					}
				},
				error: function (xhr, status, error) {
					console.error('Error en la petición AJAX:', error);
				}
			});
		}

		cargarEstados();

		function inicializarTabla() {
			$('#tabla_oficinas_sedes thead tr:eq(0) th').each(function (i) {
				if (i < $('#tabla_oficinas_sedes thead tr:eq(0) th').length - 1) {
					var title = $(this).text();
					$(this).html('<input type="text" style="font-size: .8em; width: 100%;" class="form-control" placeholder="' + title + '" />');

					$('input', this).on('keyup change', function () {
						if (tabla_oficinas_sedes.column(i).search() !== this.value) {
							tabla_oficinas_sedes
								.column(i)
								.search(this.value)
								.draw();
						}
					});
				}
			});

			var tabla_oficinas_sedes = $('#tabla_oficinas_sedes').DataTable({
				dom: 'Brtip',
				buttons: [
					{
						extend: 'excelHtml5',
						text: '<i class="fas fa-plus"></i> NUEVO OFICINAS/SEDES',
						messageTop: "Nuevo Proyecto",
						attr: {
							class: 'btn btn-success btn-registrar-o',
						},
						action: function () {
							accion('0,', "registrar");
						}
					},
				],
				language: lenguaje,
				processing: false,
				pageLength: 10,
				autoWidth: false,
				lengthChange: false,
				info: false,
				searching: true,
				scrollX: true,
				columns: [
					{
						data: d => `<p style="font-size: .8em">${d.idOficina}</p>`
					},{
						data: d => `<p style="font-size: .8em">${d.nombre_oficina.toUpperCase()}</p>`
					},{
						data: d => `<p style="font-size: .8em">${d.estado ? d.estado.toUpperCase() : 'NA'}</p>`
					},{
						data: d => `<p style="font-size: .8em">${d.id_estado}</p>`
					},{
						data: d => `<p style="font-size: .8em">${d.estatus == 1 ? "<label class='label pull-center bg-green'>ACTIVO</label>" : "<label class='label pull-center bg-red'>DESACTIVADO</label>"}</p>`
					},{
						data: function () {
							return '<div class="btn-group-vertical" role="group"><button type="button" class="btn btn-warning btn-sm btn-editar_o" title="Editar Proyecto"><i class="fas fa-user-edit"></i></button></div>';
						}
					}
				],

				columnDefs: [
					{ targets: 3, visible: false },
					{ targets: 5, orderable: false }
				],
				ajax: {
					url: url + "Departamento_sedes/tablaOficinaSedes",
					type: "POST",
					cache: false,
				}
			});

			return tabla_oficinas_sedes;
		}

		var tabla_oficinas_sedes = inicializarTabla();
		var $infoAgregarOficinas = $("#info_AgregarOficinas");

		function accion(datos, accion) {
			datos = datos.split(",");
			$("#idregistro").val($.trim(datos[0]));
			$("#accion").val(accion);
			if (accion === "registrar") {
				$estatus_oficina_sede.hide();
				$agregar.show();
				$quitar.show();
				$("#info_AgregarOficinas .modal-title").text("NUEVO REGISTRO");
				limpia_form();
			} else if (accion === "editar") {
				$agregar.hide();
				$quitar.hide();
				$estatus_oficina_sede.show();
				$("#info_AgregarOficinas .modal-title").text("EDITANDO REGISTRO #" + datos[0]);

				$(".grupo-inputs").css({
					'border': 'none'
				});
			}
			$("#info_AgregarOficinas").modal();
		}

		function limpia_form() {
			const divOficinaSede = document.getElementById('divOficinaSede');
			const formGroups = divOficinaSede.getElementsByClassName('grupo-inputs');

			while (formGroups.length > 1) {
				formGroups[formGroups.length - 1].remove();
			}

			const grupoInicial = formGroups[0];
			$(grupoInicial).find('input[type="text"], select').val('');
			$(grupoInicial).find('input[type="radio"]').prop('checked', false);

			$(grupoInicial).find('input[type="text"]').css('border', ''); 
			$(grupoInicial).find('.text-danger').css('display', 'none');

			const selectEstados = $(grupoInicial).find('select');
			selectEstados.empty().append('<option value="">---Seleccione una Opción---</option>' + estadosOptions.join(''));

			$(grupoInicial).find('input[type="text"]').attr('name', 'oficina_sede_0');
			$(grupoInicial).find('select').attr('name', 'estados_0');
		}


		var contadorGrupo = 1;

		window.agregarGrupo = function () {
			const divOficinaSede = document.getElementById('divOficinaSede');
			const grupoNuevo = document.createElement('div');
			grupoNuevo.className = 'grupo-inputs form-group';
			grupoNuevo.id = 'grupo' + contadorGrupo;
			grupoNuevo.innerHTML = `
				<div class="col-lg-12 form-group">
					<label><b>NOMBRE OFICINA/SEDE</b></label>
					<input type="text" class="form-control" name="oficina_sede_${contadorGrupo}">
					<div class="text-danger" style="display: none;"><b>*</b>Este campo es obligatorio.</div>
				</div>
				<div class="col-lg-12 form-group">
					<label><b>ESTADO</b></label>
					<select class="form-control" name="estados_${contadorGrupo}"></select>
				</div>
				<button type="button" class="btn btn-danger btn-sm remove-button" onclick="quitarGrupo(this)">QUITAR <i class="fa fa-trash" aria-hidden="true"></i></button>
			`;
			divOficinaSede.appendChild(grupoNuevo);

			$(grupoNuevo).find('select').append('<option value="">---Seleccione una Opción---</option>' + estadosOptions.join(''));
			$(grupoNuevo).find('select').select2({
				allowClear: true,
				placeholder: "---Seleccione una opción---",
				enableFiltering: true,
				buttonWidth: "100%"
			});

			contadorGrupo++;
		}

		window.quitarGrupo = function (button) { //Quita [*] de grupo
			const formGroups = document.getElementsByClassName('grupo-inputs');
			if (formGroups.length > 1) {
				button.closest('.grupo-inputs').remove();
			} else {
				alert("REQUIRE AL MENOS UNA OFICINA PARA REGISTRAR.");
			}
		}

		function validateForm() {
			const groups = document.getElementsByClassName('grupo-inputs');
			let valid = true;

			Array.from(groups).forEach(group => {
				const inputs = group.querySelectorAll('input[type="text"]');
				inputs.forEach(input => {
					const errorMessage = input.nextElementSibling;
					if (!input.value.trim()) {
						errorMessage.style.display = 'block';
						input.style.border = '1px solid red';  // Agrega borde rojo
						valid = false;
					} else {
						errorMessage.style.display = 'none';
						input.style.border = '';  // Quita el borde si el campo no está vacío
					}
				});
			});

			return valid;
		}

		$('#form-oficina-sede').submit(function (e) {
			e.preventDefault();

			if (!validateForm()) {
				alert('POR FAVOR, COMPLETE TODOS LOS CAMPOS ANTES DE ENVIAR.');
				return;
			}

			if (idOficina) {
				var data = new FormData($(this)[0]);
				data.append('idOficina', idOficina);

				link_post = 'Departamento_sedes/createUpdate_oficina_sede';

				var resultado = enviar_post(data,link_post);

				if( !resultado ){
					alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
				}else{
					$("#info_AgregarOficinas").modal( 'toggle' );
					tabla_oficinas_sedes.ajax.reload();
				}
			}else{
				var formData = new FormData();
	
				const divOficinaSede = document.getElementById('divOficinaSede');
				const groups = divOficinaSede.getElementsByClassName('grupo-inputs');
	
				for (let i = 0, j = 0; i < groups.length; i++) {
					if ($(groups[i]).find(`input[name^="oficina_sede_"]`).length) {
						var nombreOficinaSede = $(groups[i]).find(`input[name^="oficina_sede_"]`).val();
						var estado = $(groups[i]).find(`select[name^="estados_"]`).val();

						formData.append('oficinas_sedes[' + j + '][nombre]', nombreOficinaSede);
						formData.append('oficinas_sedes[' + j + '][estado]', estado);
						j++;
					}
				}
	
				$.ajax({
					url: url + 'Departamento_sedes/createUpdate_oficina_sede',
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function (r) {
						if (!r) {
							alert("ERROR EN EL REGISTRO DE OFICINAS/SEDES.")
						}else{
							$("#info_AgregarOficinas").modal( 'toggle' );
							tabla_oficinas_sedes.ajax.reload();
						}
					},
					error: function (xhr, status, error) {
						console.error(error);
					}
				});
			}
		});

		//EDITAR
		$("#tabla_oficinas_sedes").on("click", ".btn-editar_o", function() {
			limpia_form();
			var row = tabla_oficinas_sedes.row($(this).closest('tr')).data();
			idOficina = row.idOficina;
			
			accion(idOficina + ",", "editar");

			$("input[name='oficina_sede_0']").val(row.nombre_oficina);
			$("select[name='estados_0']").val(row.id_estado).trigger('change');
			$("input[name='estatus'][value='" + row.estatus + "']").prop("checked", true);
		});
	});


</script>

<?php
require("footer.php");
?>
