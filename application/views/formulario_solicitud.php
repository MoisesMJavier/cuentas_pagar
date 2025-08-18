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
							<p><span class="text-danger"><b>AVISO:</b></span> En el caso que no pueda seleccionar la <b>VACANTE</b> que desea cubrir en su <b>DEPARTAMENTO</b> notifique a Recursos Humanos de este problema.</p>
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
											<h5><span>SEDE</span></h5>
											<select class="form-control" id="solicitud_sede" name="solicitud_sede">
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
									<div class="col-lg-6">
										<div class="form-group">
											<h5><span>PLAZA</span></h5>
											<select class="form-control" id="solicitud_sucursal" name="solicitud_sucursal">
												<option value="">Seleccione una plaza</option>
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
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<h5><span>PUESTO SOLICITADO</span></h5>
											<select class="form-control" id="solicitud_puesto" name="solicitud_puesto">
												<option value="">Seleccione un puesto</option>
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
											<label>SEXO</label>
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
									<div class="col-lg-6">
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
									<div class="col-lg-6">
										<div class="form-group">
											<h5><span>SALARIO MENSUAL</span></h5>
											<div class="input-group">
												<span class="input-group-addon">$</span>
												<input class="form-control agregar_decimales" type="number" placeholder="Salario mensual" id="solicitud_salario" name="solicitud_salario" value="<?= isset($informacion_solicitud) ? $informacion_solicitud->salario_mensual : "" ?>" readonly>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-4">
										<div class="form-group">
											<h5><span>NÚMERO DE VACANTES</span></h5>
											<select class="form-control" id="solicitud_vacantes" name="solicitud_vacantes">
												<option value=''>Seleccione una opción</option>
												<?php
													if(isset($informacion_solicitud)){
														if($this->session->userdata("inicio_sesion")["rol"] == 'DR'){
															echo "<option value='".$informacion_solicitud->num_vacantes."' selected>".$informacion_solicitud->num_vacantes."</option>";
														}else{
															foreach($lista_puesto->result() as $row_puesto){
																if($informacion_solicitud->idpuesto == $row_puesto->idpuesto){
																	for($i = 1; $i <= $row_puesto->vacantes; $i++){
																		echo $informacion_solicitud->num_vacantes == $i ? "<option value='$informacion_solicitud->num_vacantes' selected>$informacion_solicitud->num_vacantes</option>" : "<option value='$i'>$i</option>";
																	}
																}
															}
														}
														
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<h5><span>HORARIO</span> <span id="ayuda_horario"><i  class="fas fa-exclamation"></i></span></h5>
											<select id="horario_vacante" name="horario_vacante" class="form-control">
												<option value="">Seleccione un horario</option>
												<?php
													if($lista_horario->num_rows() > 0){
														foreach($lista_horario->result() as $row_horario){
															if( isset($informacion_solicitud) && $informacion_solicitud->idhorario == $row_horario->idhorario ){
																echo "<option value='$row_horario->idhorario' selected>$row_horario->nombre_horario</option>";
																$dias_trabajo = $row_horario->dias_trabajo;
															}else{
																echo "<option value='$row_horario->idhorario'>$row_horario->nombre_horario</option>";
															}	 
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<h5><span>DÍAS DE TRABAJO</span></h5>
											
											<input class="form-control" type="number" placeholder="Total de días a trabajar" id="dias_laborales_vacante" name="dias_laborales_vacante" value="<?= isset($dias_trabajo) ? $dias_trabajo : "" ?>" readonly>
											
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

	var puesto;

	$("#solicitud_sede").change( function(){
    	$.post( url + "Solicitud_personal/vacantes_disponibles", { idsede : $("#solicitud_sede").val() }, function (data){

			data = JSON.parse( data );
            
            $("#solicitud_sucursal").html("<option value=''>Seleccione una opción</option>");
			$.each( data.sucursales, function( index, value ){
				$("#solicitud_sucursal").append("<option value='"+value.idsucursal+"'>"+value.nom_oficina+"</option>");
			});

        });
    });

	$("#solicitud_sucursal").change( function(){
		$.post( url + "Solicitud_personal/vacantes_disponibles_sucursal", { idsucursal : $("#solicitud_sucursal").val() }, function (data){

			data = JSON.parse( data );
			$("#solicitud_puesto").html("<option value=''>Seleccione una opción</option>");
			$.each( data.puestos, function( index, value ){
				$("#solicitud_puesto").append("<option value='"+value.idpuesto+"'>"+value.nom_puesto+"</option>");
			});
			puesto = data.puestos;
		});
	});

     $("#solicitud_puesto").change( function(){
		
			if( $(this).val() ){
				valor = $(this).val();
				$("#solicitud_vacantes").html("<option value=''>Seleccione una opción</option>");
				$.each( puesto, function( index, value ){
					if( value.idpuesto == valor ){
						$("#solicitud_salario").val( value.salario_base );
						for(var i = 1; i <= value.vacantes_libres; i++){
							$("#solicitud_vacantes").append("<option value='"+i+"'>"+i+"</option>");
						}
						return false;
					}
				});
			 }   
		
    });
</script>
<?php
    require("footer.php");
?>