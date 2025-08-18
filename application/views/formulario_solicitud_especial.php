<?php
    require("head.php");
    require("menu_navegador.php");
?>
<div class="container-fluid">
    <div class="row justify-content-lg-center">
        <div class="col-lg-12">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-lg-12">
                            <h3 class="text-right"><strong>SOLICITUD DE PERSONAL</strong></h3>
                        </div>
                    </div>
                </div>
                <div class="box-body">
					<div class="row">
						<div class="col-lg-10 col-lg-offset-1">
							<form id="formulario_solicitud" action="<?= $urlaction ?>" method="post">
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<h5><span>Departamento</span></h5>
											<select class="form-control lista_dinamica_sede" id="solicitud_area" name="solicitud_area">
												<option value="">Seleccione un departamento</option>
												<?php
													if($lista_area->num_rows() > 0){
														foreach($lista_area->result() as $row_area){
															echo isset($informacion_solicitud) && $informacion_solicitud->idarea == $row_area->idarea ? "<option value='$row_area->idarea' selected>$row_area->nom_area</option>" : "<option value='$row_area->idarea'>$row_area->nom_area</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<h5><span>SEDE</span></h5>
											<select class="form-control lista_dinamica_sede" id="solicitud_sede" name="solicitud_sede">
												<option value="">Seleccione una sede</option>
												<?php
													if($lista_sede->num_rows() > 0){
														foreach($lista_sede->result() as $row_sede){
															echo isset($informacion_solicitud) && $informacion_solicitud->idsede == $row_sede->idsede ? "<option value='$row_sede->idsede' selected>$row_sede->nom_sede</option>" : "<option value='$row_sede->idsede'>$row_sede->nom_sede</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<h5><span>PLAZA</span></h5>
											<select class="form-control lista_dinamica_sucursal" id="solicitud_sucursal" name="solicitud_sucursal">
												<option value="">Seleccioné una plaza</option>
												<?php
													if(isset($lista_sucursales) && $lista_sucursales->num_rows() > 0){
														foreach($lista_sucursales->result() as $row_sucursal){
															echo isset($informacion_solicitud) && $informacion_solicitud->idsucursal == $row_sucursal->idsucursal ? "<option value='$row_sucursal->idsucursal' selected>$row_sucursal->nom_oficina</option>" : "<option value='$row_sucursal->idsucursal'>$row_sucursal->nom_oficina</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<h5><span>PUESTO SOLICITADO</span></h5>
											<select class="form-control" id="solicitud_puesto" name="solicitud_puesto">
												<option value="">Seleccioné un puesto</option>
												<?php
													if(isset($lista_puesto) && $lista_puesto->num_rows() > 0){
														foreach($lista_puesto->result() as $row_puesto){
															echo isset($informacion_solicitud) && $informacion_solicitud->idpuesto == $row_puesto->idpuesto ? "<option value='$row_puesto->idpuesto' selected>$row_puesto->nom_puesto</option>" : "<option value='$row_puesto->idpuesto'>$row_puesto->nom_puesto</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<h5><span>DESCRIPCIÓN DE TAREAS</span></h5>
											<textarea class="form-control" name="solicitud_descripcion" id="solicitud_descripcion" cols="30" rows="5" maxlength="300" placeholder="Actividades, conocimientos o habilidades primordiales para esta vacante."><?= isset($informacion_solicitud) ? $informacion_solicitud->descripcion_puesto : "" ?></textarea>
										</div>
									</div>
								</div>
								<h5><span>REQUISITOS</span></h5>
								<div class="row">
									<div class="col-lg-2">
										<div class="form-group">
											<label>Sexo</label>
											<select class="form-control" id="solicitud_sexo" name="solicitud_sexo">
												<option value="">Sexo</option>
												<?php
													foreach(array(array("M", "Masculino"), array("F", "Femenino"), array("I", "Indistinto")) as $row_sexo ){
														echo isset($informacion_solicitud) && $row_sexo[0] == $informacion_solicitud->sexo ? "<option value='$row_sexo[0]' selected>$row_sexo[1]</option>" : "<option value='$row_sexo[0]'>$row_sexo[1]</option>";
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-lg-2">
										<div class="form-group">
											<label>EXPERIENCIA</label>
											<select class="form-control" id="solicitud_experiencia" name="solicitud_experiencia">
												<option value="">Exp.</option>
												<?php
													
													foreach(array("Sin exp.", "< 1 año") as $row_experiencia){
														echo isset($informacion_solicitud) && $informacion_solicitud->experiencia == $row_experiencia ? "<option value='$row_experiencia' selected>$row_experiencia</option>" : "<option value='$row_experiencia'>$row_experiencia</option>";
													}

													for($i=1; $i < 11; $i++){
														echo isset($informacion_solicitud) && $informacion_solicitud->experiencia == $i ? "<option value='$i' selected>$i año(s)</option>" : "<option value='$i'>$i año(s)</option>";
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label>EDAD</label>
											<div class="row">
											<div class="col-lg-6">
												<select class="form-control" id="solicitud_minima" name="solicitud_minima">
													<option value="">Min</option>
													<?php
														for($i=18; $i < 26; $i++){
															echo isset($informacion_solicitud) && $informacion_solicitud->edad_min == $i ? "<option value='$i' selected>$i años</option>" : "<option value='$i'>$i años</option>";
														}
													?>
												</select>
											</div>
											<div class="col-lg-6">
												<select class="form-control" id="solicitud_maxima" name="solicitud_maxima">
													<option value="">Max</option>
													<?php
														for($i=26; $i < 61; $i++){
															echo isset($informacion_solicitud) && $informacion_solicitud->edad_max == $i ? "<option value='$i' selected>$i años</option>" : "<option value='$i'>$i años</option>";
														}
													?>
												</select>
											</div>
											</div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label>ESTUDIOS</label>
											<select class="form-control" id="solicitud_estudios" name="solicitud_estudios">
												<option value="">Nivel de estudios</option>
												<?php
													foreach(array("PRIMARIA", "SECUNDARIA", "PREPARATORIA", "TSU", "LICENCIATURA", "POSTGRADO") as $row_estudio){
														echo isset($informacion_solicitud) && $informacion_solicitud->nivel_estudio == $row_estudio ? "<option value='$row_estudio' selected>$row_estudio</option>" : "<option value='$row_estudio'>$row_estudio</option>" ;
													}
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<h5><span>TIPO DE CONTRATO</span></h5>
											<select class="form-control" id="solicitud_contrato" name="solicitud_contrato">
												<option value="">Tipo de contrato</option>
												<?php
													foreach(array("Temporal", "Proyecto", "Indeterminado") as $row_contrato){
														echo isset($informacion_solicitud) && $informacion_solicitud->tipo_contrato == $row_contrato ? "<option value='$row_contrato' selected>$row_contrato</option>" : "<option value='$row_contrato'>$row_contrato</option>" ;
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<h5><span>SALARIO MENSUAL</span></h5>
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input class="form-control agregar_decimales" type="number" placeholder="Salario mensual" id="solicitud_salario" name="solicitud_salario" value="<?= isset($informacion_solicitud) ? $informacion_solicitud->salario_mensual : "" ?>" readonly>
											</div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<h5><span>NÚMERO DE VACANTES</span></h5>
											<input class="form-control" id="solicitud_vacantes" name="solicitud_vacantes" value="<?= isset($informacion_solicitud) ? $informacion_solicitud->num_vacantes : "" ?>">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<h5><span>SOLICITANTE DE VACANTE</span></h5>
											<select id="solicitante_vacante" name="solicitante_vacante" class="form-control" required>
												<option value='<?= isset($informacion_solicitud) ? $informacion_solicitud->idusuario : "" ?>'><?= isset($informacion_solicitud) ? $informacion_solicitud->nombre_usuario : "Seleccione una opción" ?></option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<h5><span>HORARIO</span> <span id="ayuda_horario"><i  class="fas fa-exclamation"></i></span></h5>
											<select id="horario_vacante" name="horario_vacante" class="form-control">
												<option value="">Seleccione un horario</option>
												<?php
													if($lista_horario->num_rows() > 0){
														foreach($lista_horario->result() as $row_horario){
															echo "<option value='$row_horario->idhorario'>$row_horario->nombre_horario</option>";
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<h5><span>DÍAS DE TRABAJO</span></h5>
											<input class="form-control" type="number" placeholder="Total de días a trabajar" id="dias_laborales_vacante" name="dias_laborales_vacante" readonly>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<h5><span>CONFIDENCIALIDAD</span></h5>
											<label class="radio-inline"><input type="radio" name="solicitud_confidencial" value="0" checked>NO</label>
											<label class="radio-inline"><input type="radio" name="solicitud_confidencial" value="1">SI</label>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<button class="btn btn-primary btn-block" type="submit">GUARDAR</button>
										</div>
									</div>
								</div>
							</form>
						</div>        
					</div>
                </div>        
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="explicacion_horario">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Desglose de horario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<script>

	$("#solicitud_area").change( function(){
		$.post( url + "Solicitud_personal/puestos_area", { idarea : $("#solicitud_area").val() }, function (data){

			data = JSON.parse( data );
			$("#solicitud_puesto").html("<option value=''>Seleccione una opción</option>");
			$.each( data.puestos, function( index, value ){
				$("#solicitud_puesto").append("<option value='"+value.idpuesto+"'>"+value.nom_puesto+"</option>");
			});
			puesto = data.puestos;
		});


		
	});


	$("#solicitud_puesto").change( function(){
    	$.post( url + "Solicitud_personal/vacantes_disponibles_especiales", { idpuesto : $("#solicitud_puesto").val() }, function (data){
			data = JSON.parse( data );
			$("#solicitud_salario").val( data[0].salario_base );

			//$("#solicitante_vacante").html('<option value="">Seleccione una opción</option>');
			$.each( data[1], function( i, v){
				$("#solicitante_vacante").append('<option value="'+v.idusuario+'">'+v.nombre_persona+' '+v.apellido_paterno_persona+'</option>');
			});

        });
    });

</script>
<?php
    require("footer.php");
?>