<?php
require("head.php");
require("menu_navegador.php");
?>
<style>
	@import url("https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap");

	*,
	*:after,
	*:before {
		box-sizing: border-box;
	}

	body {
		font-family: "Inter", sans-serif;
	}

	.font-format {
		font-family: "Inter", sans-serif;
	}

	.font-format textarea {

		border: 1px solid #484a8c;
		margin-top: 10px;
		margin-bottom: 10px;
		max-width: 100%;
		max-height: 150px;
		font-size: 16px;
		padding: 10px;
		border-radius: 5px;
		resize: none;

	}

	.font-format input {
		border: 1px solid #484a8c;
		font-size: 14px;
		border-radius: 5px;


	}

	.font-format select {
		border: 1px solid #484a8c;
		font-size: 14px;
		border-radius: 5px;


	}


	.fancy-button {
		border-radius: 5px;
		background-color: green;
		border: none;
		color: white;
		text-align: center;
		font-size: 18px;
		font-weight: bold;
		transition: all 0.5s;
		cursor: pointer;
		align: center;
		margin-top: 6px;
		min-height: 40px;


	}

	.message-balloon {
		font-size: 15px;
		border: 1px solid #484a8c;
		background-color: #e6e6fc;
		border-radius: 5px;
		padding: 10px;
		margin-bottom: 14px;
		margin-left: 0px;
		margin-right: 10px;
		overflow-wrap: break-word;
	}

	.message-balloon label {

		font-weight: bold;

	}

	.own-messages {
		border-color: #488c53;
		background-color: #e1fce5;
		margin-left: 10px;
		margin-right: 0px;
	}

	/* Clear floats */
	.container::after {
		content: "";
		clear: both;
		display: table;
	}

	/* Style images */
	.container img {
		float: left;
		max-width: 60px;
		width: 100%;
		margin-right: 20px;
		border-radius: 50%;
	}

	/* Style the right image */
	.container img.right {
		float: right;
		margin-left: 20px;
		margin-right: 0;
	}

	/* Style time text */
	.time-right {
		float: right;
		color: #aaa;
	}

	/* Style time text */
	.time-left {
		float: left;
		color: #999;
	}

	.cxp-form {
		visibility: visible;
		opacity: 1;
		height: 0px;
		width: 100%;
		transition: all .7s;
		transition-delay: 0s;
		overflow: hidden;
		margin-top: 8px;
		/* padding: 8px 12px; */
		border: 1px solid #fff;
		border-radius: 3px;
	}

	.checkbox-group {
		display: flex;
		flex-wrap: wrap;
		justify-content: center;
		width: 100%;
		margin-left: auto;
		margin-right: auto;
		/* max-width: 600px; */
		user-select: none;

		/* background-color: black ;  */
		&>* {
			margin: .5rem 0.5rem;
		}
	}

	.checkbox-group-legend {
		font-size: 1.5rem;
		font-weight: 700;
		color: #9c9c9c;
		text-align: center;
		line-height: 1.125;
		margin-bottom: 1.25rem;
	}

	.checkbox-input {
		// Code to hide the input
		clip: rect(0 0 0 0);
		clip-path: inset(100%);
		height: 1px;
		overflow: hidden;
		position: absolute;
		white-space: nowrap;
		width: 1px;

		&:checked+.checkbox-tile {
			border-color: #2260ff;
			box-shadow: 0 5px 10px rgba(#000, 0.1);
			color: #2260ff;

			&:before {
				transform: scale(1);
				opacity: 1;
				background-color: #2260ff;
				border-color: #2260ff;
			}

			.checkbox-icon,
			.checkbox-label {
				color: #2260ff;
			}
		}

		&:focus+.checkbox-tile {
			border-color: #2260ff;
			box-shadow: 0 5px 10px rgba(#000, 0.1), 0 0 0 4px #b5c9fc;

			&:before {
				transform: scale(1);
				opacity: 1;
			}
		}
	}

	.checkbox-tile {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		width: 20rem;
		height: 12rem;
		border-radius: 0.5rem;
		border: 2px solid #b5bfd9;
		background-color: #fff;
		box-shadow: 0 5px 10px rgba(#000, 0.1);
		transition: 0.15s ease;
		cursor: pointer;
		position: relative;

		&:before {
			content: "";
			position: absolute;
			display: block;
			width: 1.25rem;
			height: 1.25rem;
			border: 2px solid #b5bfd9;
			background-color: #fff;
			border-radius: 50%;
			top: 0.25rem;
			left: 0.25rem;
			opacity: 0;
			transform: scale(0);
			transition: 0.25s ease;
			background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='192' height='192' fill='%23FFFFFF' viewBox='0 0 256 256'%3E%3Crect width='256' height='256' fill='none'%3E%3C/rect%3E%3Cpolyline points='216 72.005 104 184 48 128.005' fill='none' stroke='%23FFFFFF' stroke-linecap='round' stroke-linejoin='round' stroke-width='32'%3E%3C/polyline%3E%3C/svg%3E");
			background-size: 12px;
			background-repeat: no-repeat;
			background-position: 50% 50%;
		}

		&:hover {
			border-color: #2260ff;

			&:before {
				transform: scale(1);
				opacity: 1;
			}
		}
	}

	.checkbox-icon {
		transition: .375s ease;
		color: #494949;

		svg {
			width: 3rem;
			height: 3rem;
		}
	}

	.checkbox-label {
		color: #707070;
		transition: .375s ease;
		text-align: center;
	}

	/* check box buttons */

	#output {
		width: 95%;
		margin: 0 auto;
		padding: 1em;
	}

	#output>li {
		width: 100%;
		list-style-type: "\1F4DD";
		line-height: 2em;
		border-bottom: 1px solid #ccc;
	}

	#output>li span {
		float: right;
	}


	.drop-container {
		position: relative;
		display: flex;
		gap: 10px;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		height: auto;
		padding: 20px;
		border-radius: 10px;
		border: 2px dashed #555;
		color: #444;
		cursor: pointer;
		transition: background .2s ease-in-out, border .2s ease-in-out;
	}

	.drop-container:hover,
	.drop-container.drag-active {
		background: #eee;
		border-color: #111;
	}

	.drop-container:hover .drop-title,
	.drop-container.drag-active .drop-title {
		color: #222;
	}

	.drop-title {
		color: #444;
		font-size: 20px;
		font-weight: bold;
		text-align: center;
		transition: color .2s ease-in-out;
	}

	input[type=file] {
		width: 350px;
		max-width: 100%;
		color: #444;
		padding: 5px;
		background: #fff;
		border-radius: 10px;
		border: 1px solid #555;
	}

	input[type=file]::file-selector-button {
		margin-right: 20px;
		border: none;
		background: #084cdf;
		padding: 10px 20px;
		border-radius: 10px;
		color: #fff;
		cursor: pointer;
		transition: background .2s ease-in-out;
	}

	input[type=file]::file-selector-button:hover {
		background: #0d45a5;
	}

	.input_group_fileselector {

		width: 100%;
		/* list-style-type: disc; */
		line-height: 1em;
		border-radius: 7px;
		font-size: 14px;
		font-weight: Bold;
		font-family: 'Trebuchet MS', sans-serif;
		border: 1px solid black;
		padding: 3px 5px 3px 5px;


	}

	/* file selector end */
	.add-button {
		border-radius: 4px;
		border: none;
		color: #FFFFFF;
		text-align: center;
		font-size: 17px;
		font-weight: bold;
		width: 23%;
		height: 35px;
		transition: all 0.5s;
		cursor: pointer;
		align: right;
		margin-right: 10px;
	}

	.add-button span {
		cursor: pointer;
		display: inline-block;
		position: relative;
		transition: 0.5s;
	}

	.add-button span:after {
		font-family: "Font Awesome 5 Free";
		content: "\f0fe";
		position: absolute;
		opacity: 0;
		top: 0;
		right: -20px;
		transition: 0.5s;
	}

	.add-button:hover span {
		padding-right: 25px;
	}

	.add-button:hover span:after {
		opacity: 1;
		right: 0;
	}

	.multiple_emails-container {
		border: 1px solid #000000;
		border-radius: 5px;
		box-shadow: inset 0 3px 3px rgba(0, 0, 0, .075);
		padding: 0;
		margin: 0;
		cursor: text;
		width: 100%;

	}

	.multiple_emails-container input {

		width: 100%;
		border: 0;
		background-color: transparent;
		outline: none;
		margin-bottom: 30px;
		padding-left: 5px;
		font-size: 13px;
		font-weight: Bold;
		font-family: 'Trebuchet MS', sans-serif;

	}

	.multiple_emails-container input {
		border: 0 !important;
		font-size: 13px;
		font-weight: Bold;
		font-family: 'Trebuchet MS', sans-serif;
		background-color: transparent;

	}

	.multiple_emails-container input.multiple_emails-error {
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px red !important;
		outline: thin auto red !important;
	}

	.multiple_emails-container ul {
		list-style-type: none;
		padding-left: 0;
	}

	.multiple_emails-email {
		margin: 3px 5px 3px 5px;
		padding: 3px 5px 3px 5px;
		border: 1px #ccc solid;
		border-radius: 15px;
		background: #e3e3ff;
		font-size: 13px;
		font-weight: Bold;
		font-family: 'Trebuchet MS', sans-serif;
	}

	.multiple_emails-close {
		float: left;
		margin: 0 10px;
		color: blue;

	}

	.designed-button {
		border-radius: 4px;
		border: none;
		color: #FFFFFF;
		text-align: center;
		font-size: 14px;
		font-weight: bold;
		width: 95%;
		height: 35px;
		transition: all 0.5s;
		cursor: pointer;
		margin: auto;
	}
</style>

<div class="content" style=" padding: 0px;">
	<div class="container-fluid">
		<div class="box">
			<div class="box-body">
				<div class="box-header with-border" style="align: right; ">
					<h3 style="align: right; ">SOLICITUDES DE AUTORIZACIÓN </h3>
					<div class="" style="text-align: right; align: right;   
						/* display: flex;
  flex-wrap: wrap;  */
  ">

						<input type="email" id="email" pattern=".+@globex\.com" size="30" required />

						<button class="add-button abrir_nueva_solicitud" style=" background-color: #0f5691;"><span>Nueva solicitud </span></button>

					</div>

				</div>

				<div class="col-lg-12">
					<!-- <table class="table" id="tabla_autorizaciones" style data-paging="false" data-searching="false" data-ordering="false"> -->
					<table class="table" id="tabla_autorizaciones" data-ordering="false">
						<thead class="thead-dark">
							<tr>
								<th style="font-size: .8em; color: white;">id</th>
								<th style="font-size: 5px; font-weight: normal;"> </th>
								<!-- <th style="font-size: .8em"></th> -->
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


<div id="modal_formulario_solicitud_aut" class="modal fade" role="dialog">
	<div id="modal-content-scroll" class="modal-dialog" style=" width: 90%; border-radius: 15px;">
		<div class="modal-content" style=" width: 90%; ">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title" id="exampleModalLabel">NUEVA SOLICITUD</h4>
			</div>
			<div class="modal-body">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#generar_solicitud" role="tab" aria-controls="generar_solicitud" aria-selected="false">GENERAR SOLICITUD</a></li>
					</ul>
				</div>
				<div class="tab-content">
					<div class="active tab-pane" id="generar_solicitud">
						<div class="row">
							<div class="col-lg-12">
								<form id="formulario_autorizacion" method="post">
									<!-- Nombre y tipo  -->
									<div class="row">
										<div class="col-lg-6 form-group">
											<label for="descr">Nombre de la solicitud</label>
											<input class="form-control" id="titulo" name="titulo" placeholder="Nombre de solicitud">
										</div>
										<div class="col-lg-6 form-group">
											<label for="tipo">Tipo<span class="text-danger">*</span> <i class="far fa-question-circle faq text-danger" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Seleccione una opción" data-content="A continuación se muestra solo los tipos de solicitud a crear." data-placement="right"></i></label>
											<select name="tipo" class="form-control select2 select2-hidden-accessible lstado_tipo_autorizacion" style="width: 100%;" id="tipo" required></select>
											<input type="hidden" name="idtipo" class="form-control" id="idtipo">
										</div>
									</div>
									<!-- Input de correos  -->

									<div class="row">
										<br>
										<div class="col-lg-12">
											<div class="input-group">
												<div class="form-floating">

													<label>Personas que seran informadas por correo electrónico sobre el estado de esta solicitud(Inserta email y presiona enter): </label>

													<div class="input-group input-group-md">

														<input type="email" id="example_emailBS" name="example_emailBS" value='[]' placeholder="Inserta email y presiona enter.">


													</div>
													<br>

													<!-- <label for="motivo">Involucrados</label> -->
													<label for="motivo">Colaboradores que podran ver y editar esta solicitud:</label>
													<select name="involucrados" class="form-control select2 select2-hidden-accessible listado_involucrados" style="width: 100%;" id="involucrados"></select>

												</div>
												<br>

												<button type="button" id="add" onclick="agregar()" class="btn btn-primary" style="width: 100%;">Agregar involucrado</button>
											</div>
										</div>
									</div>
									<!-- Involucrados  -->
									<div class="row">
										<div class="row justify-content-center">
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
												<div class="table-responsive">
													<table id='tbl_motiv' class="table table-bordered table-striped">
														<thead>
															<tr>
																<th class="text-center">Involucrados:</th>
															</tr>
														</thead>
														<tbody id="tbodyInv">
														</tbody>
													</table>
												</div>
											</div>
											<div class="col-xxl-3 col-md-6 col-sm-8 col-12 d-flex justify-content-center flex-column gap-2">
												<!-- <button type="button" class="btn btn-primary" id="btnsubformcolab">Enviar</button> -->
											</div>
										</div>
									</div>
									<!-- Involucrados listado -->
									<!-- <div class="row">
                                        <br>
                                        <div class="col-lg-12">
                                            <div class="input-group">
                                                <div class="form-floating">
                                                    
                                                    <label for="motivo">Revisores que validaran y autorizaran esta solicitud:</label>
                                                    <select name="autorizadores" class="form-control select2 select2-hidden-accessible listado_autorizados" style="width: 100%;" id="autorizadores"></select>
                                                </div>
												<br>

                                                <button type="button" id="add1" onclick="agregarAutorizadores()" class="btn btn-primary" style="width: 100%;">Agregar revisor</button>
                                            </div>
                                        </div>
                                    </div> -->
									<!-- Revisores -->
									<!-- <div class="row">
                                        <div class="row justify-content-center">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="table-responsive">
                                                    <table id='tbl_aut' class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Autorizadores:</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tbodyAut">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-xxl-3 col-md-6 col-sm-8 col-12 d-flex justify-content-center flex-column gap-2">
                                               <button type="button" class="btn btn-primary" id="btnsubformcolab">Enviar</button> 
                                            </div>
                                        </div>
                                    </div> -->
									<!-- observaciones -->
									<div class="row">
										<div class="col-lg-12">
											<div class="form-group">
												<label for="descr">Observaciones</label>
												<textarea rows="10" class="form-control" id="descr" name="descr" placeholder="Descripción" required></textarea>
											</div>
										</div>
									</div>
									<!-- checkbox firmas  -->

									<div class="row">

										<br>
										<br>
										<fieldset class="checkbox-group">
											<legend class="checkbox-group-legend">Selecciona que firmas seran requeridas: </legend>

											<div class="checkbox" style="margin-top: -5px;">
												<label class="checkbox-wrapper">
													<input type="checkbox" class="checkbox-input" id="darequired" name="darequired" />
													<span class="checkbox-tile">
														<span class="checkbox-icon">
															<i class='fas fa-signature'></i>
															<!-- <svg fill="#000000" height="800px" width="800px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
																	 viewBox="0 0 512 512" xml:space="preserve">
																<g>
																	<g>
																		<path d="M501.801,179.563h-33.023c3.145-11.057,1.345-23.229-5.394-33.004l13.011-13.011c1.912-1.913,2.987-4.507,2.987-7.212
																			c0-2.214-0.73-4.348-2.041-6.1l22.875-22.875c1.912-1.912,2.987-4.507,2.987-7.212s-1.075-5.298-2.987-7.212l-18.371-18.371
																			c-1.912-1.912-4.507-2.987-7.212-2.987s-5.3,1.075-7.212,2.987l-22.875,22.875c-3.995-2.993-9.681-2.685-13.313,0.946
																			l-91.176,91.176H10.199C4.566,179.564,0,184.131,0,189.763v250.458c0,5.632,4.566,10.199,10.199,10.199h491.602
																			c5.633,0,10.199-4.567,10.199-10.199V189.762C512,184.129,507.434,179.563,501.801,179.563z M474.633,86.203l3.947,3.946
																			l-15.581,15.58l-3.946-3.946L474.633,86.203z M438.445,110.023l16.313,16.313L280.372,300.721l-16.313-16.313L438.445,110.023z
																			 M446.481,179.563H430.38l18.053-18.053C450.886,167.374,450.231,174.228,446.481,179.563z M265.948,315.145l-39.423,39.422
																			c-1.874,1.875-4.365,2.908-7.016,2.908c-2.651,0-5.142-1.033-7.017-2.909l-2.28-2.279c-3.869-3.869-3.869-10.164,0-14.032
																			l39.423-39.423L265.948,315.145z M491.602,430.021H20.398V199.962H319.66L195.789,323.833c-9.713,9.714-11.437,24.428-5.19,35.927
																			l-8.165,8.165H64.534c-5.633,0-10.199,4.567-10.199,10.199s4.566,10.199,10.199,10.199h122.124c3.018,0,5.721-1.318,7.588-3.4
																			l10.766-10.729c4.397,2.401,9.348,3.68,14.497,3.68c8.099,0,15.713-3.155,21.44-8.881l169.031-169.031h16.524l-34.789,34.789
																			c-3.983,3.983-3.983,10.441,0,14.425c1.992,1.991,4.602,2.987,7.212,2.987c2.61,0,5.221-0.996,7.212-2.987l49.214-49.214h36.248
																			V430.021z"/>
																	</g>
																</g>
																<g>
																	<g>
																		<path d="M408.138,367.924h-74.516c-5.633,0-10.199,4.567-10.199,10.199s4.566,10.199,10.199,10.199h74.516
																			c5.633,0,10.199-4.567,10.199-10.199S413.77,367.924,408.138,367.924z"/>
																	</g>
																</g>
																<g>
																	<g>
																		<path d="M451.605,367.924h-6.209c-5.633,0-10.199,4.567-10.199,10.199s4.566,10.199,10.199,10.199h6.209
																			c5.633,0,10.199-4.567,10.199-10.199S457.238,367.924,451.605,367.924z"/>
																	</g>
																</g>
																<g>
																	<g>
																		<path d="M186.659,231.311H64.534c-5.633,0-10.199,4.567-10.199,10.199c0,5.632,4.566,10.199,10.199,10.199h122.124
																			c5.633,0,10.199-4.567,10.199-10.199C196.858,235.878,192.292,231.311,186.659,231.311z"/>
																	</g>
																</g>
																<g>
																	<g>
																		<path d="M186.659,275.814H64.534c-5.633,0-10.199,4.567-10.199,10.199s4.566,10.199,10.199,10.199h122.124
																			c5.633,0,10.199-4.567,10.199-10.199S192.292,275.814,186.659,275.814z"/>
																	</g>
																</g>
																</svg> -->
														</span>
														<span class="checkbox-label">Requiere firma por dirección de área</span>
													</span>
												</label>
											</div>
											<div class="checkbox">
												<label class="checkbox-wrapper">
													<input type="checkbox" class="checkbox-input" id="dgrequired" name="dgrequired" />
													<span class="checkbox-tile">
														<span class="checkbox-icon">
															<i class="fa-solid fa-file-signature"></i>
															<!-- <i class="fa-solid fa-file-signature"></i> -->
															<!-- <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
																	 viewBox="0 0 512 512" xml:space="preserve">
																<rect x="10.199" y="189.765" style="fill:#FFD890;" width="491.602" height="250.462"/>
																<path style="fill:#BDD169;" d="M280.373,315.145l-30.736-30.736l-46.635,46.635c-7.858,7.858-7.858,20.598,0,28.457l2.281,2.281
																	c7.858,7.858,20.598,7.858,28.457,0L280.373,315.145z"/>
																<rect x="337.679" y="71.86" transform="matrix(-0.7071 -0.7071 0.7071 -0.7071 468.34 604.7255)" style="fill:#E6E6E6;" width="43.468" height="267.013"/>
																<rect x="455.817" y="74.742" transform="matrix(-0.7071 -0.7071 0.7071 -0.7071 732.4503 495.3077)" style="fill:#BDD169;" width="25.979" height="42.432"/>
																<g> -->
															<!-- <path style="fill:#4C1D1D;" d="M501.801,179.563h-33.023c3.145-11.057,1.345-23.229-5.394-33.004l13.011-13.011
																		c1.912-1.913,2.987-4.507,2.987-7.212c0-2.214-0.73-4.348-2.041-6.1l22.875-22.875c1.912-1.912,2.987-4.507,2.987-7.212
																		c0-2.705-1.075-5.298-2.987-7.212l-18.371-18.371c-1.912-1.912-4.507-2.987-7.212-2.987s-5.3,1.075-7.212,2.987l-22.875,22.875
																		c-3.995-2.993-9.681-2.685-13.313,0.946l-91.176,91.176H10.199C4.566,179.564,0,184.131,0,189.763v250.458
																		c0,5.632,4.566,10.199,10.199,10.199h491.602c5.633,0,10.199-4.567,10.199-10.199V189.762
																		C512,184.129,507.434,179.563,501.801,179.563z M446.481,179.563H430.38l18.053-18.053
																		C450.886,167.374,450.231,174.228,446.481,179.563z M474.633,86.203l3.947,3.946l-15.581,15.58l-3.946-3.946L474.633,86.203z
																		 M438.445,110.023l16.313,16.313L280.372,300.721l-16.313-16.313L438.445,110.023z M210.213,352.288
																		c-3.869-3.869-3.869-10.163,0-14.032l39.423-39.423l16.313,16.313l-39.423,39.422c-1.874,1.875-4.365,2.908-7.016,2.908
																		s-5.142-1.033-7.017-2.909L210.213,352.288z M409.981,199.962h16.524l-34.789,34.789c-3.983,3.983-3.983,10.441,0,14.425
																		c1.992,1.991,4.602,2.987,7.212,2.987s5.221-0.996,7.212-2.987l49.214-49.214h36.248v230.059H20.398V199.962H319.66
																		L195.789,323.833c-9.713,9.714-11.437,24.428-5.19,35.927l-8.165,8.165H64.534c-5.633,0-10.199,4.567-10.199,10.199
																		c0,5.632,4.566,10.199,10.199,10.199h122.124c3.018,0,5.721-1.318,7.588-3.4l10.766-10.729c4.397,2.401,9.348,3.68,14.497,3.68
																		c8.099,0,15.713-3.155,21.44-8.881L409.981,199.962z"/>
																	<path style="fill:#4C1D1D;" d="M408.138,367.924h-74.516c-5.633,0-10.199,4.567-10.199,10.199c0,5.632,4.566,10.199,10.199,10.199
																		h74.516c5.633,0,10.199-4.567,10.199-10.199C418.338,372.491,413.77,367.924,408.138,367.924z"/>
																	<path style="fill:#4C1D1D;" d="M451.605,367.924h-6.209c-5.633,0-10.199,4.567-10.199,10.199c0,5.632,4.566,10.199,10.199,10.199
																		h6.209c5.633,0,10.199-4.567,10.199-10.199C461.805,372.491,457.238,367.924,451.605,367.924z"/>
																	<path style="fill:#4C1D1D;" d="M64.534,251.709h122.124c5.633,0,10.199-4.567,10.199-10.199s-4.566-10.199-10.199-10.199H64.534
																		c-5.633,0-10.199,4.567-10.199,10.199S58.901,251.709,64.534,251.709z"/>
																	<path style="fill:#4C1D1D;" d="M64.534,296.212h122.124c5.633,0,10.199-4.567,10.199-10.199c0-5.632-4.566-10.199-10.199-10.199
																		H64.534c-5.633,0-10.199,4.567-10.199,10.199C54.335,291.645,58.901,296.212,64.534,296.212z"/> -->
															<!-- </g>
																</svg>																</span> -->
															<span class="checkbox-label">Requiere firma por direccion general</span>
														</span>
												</label>
											</div>

										</fieldset>
									</div>
									<!-- input de documentos  -->
									<div class="row">
										<br>
										<br>
										<div class="col-lg-12">
											<div class="form-group">
												<h5><b>CARGA LOS DOCUMENTOS PARA ESTA SOLICITUD EN ESTA SECCIÓN: </b></h5>



												<label for="images" class="drop-container" id="dropcontainer">
													<span class="drop-title">Arrastra tus archivos aqui</span>

													o

													<input type="file" id="dropedFilesInput" name="dropedFilesInput[]" multiple="multiple" required />
													<span style="cursor: pointer; cursor: hand;" onclick="cleanInputs($('#dropedFilesInput'))"><i class="fa fa-trash"></i> Limpiar lista de archivos</span>

													<ul id="output"> </ul>
												</label>

											</div>
										</div>
									</div>
									<!-- checkbox cxp   -->
									<div class="row">
										<br>
										<br>
										<fieldset class="checkbox-group">
											<legend class="checkbox-group-legend">Solicitud en cxp </legend>

											<div class="checkbox" style="margin-top: -5px;">
												<label class="checkbox-wrapper">
													<input type="checkbox" class="checkbox-input inputcxp" id="cxprequired" name="cxprequired" />
													<span class="checkbox-tile">
														<span class="checkbox-icon">
															<i class="fas fa-file-contract"></i>
															<!-- <svg width="800px" height="800px" viewBox="0 0 1024 1024" class="icon"  version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M776.577087 687.485754h-142.616987l35.111076-26.332283c9.69517-7.263442 11.655911-21.019342 4.381205-30.714512-7.253204-9.652167-20.986578-11.655911-30.714512-4.381205l-111.159134 83.368838 111.159134 83.368839a21.822068 21.822068 0 0 0 13.14464 4.392468c6.674708 0 13.262387-3.031724 17.569872-8.773673 7.273681-9.69517 5.313965-23.451069-4.381205-30.714512l-35.111076-26.332283h142.616987c42.337658 0 76.791399 34.453741 76.7914 76.791399s-34.453741 76.791399-76.7914 76.7914H590.083543c-12.116659 0-21.940839 9.82418-21.940839 21.940838s9.82418 21.940839 21.940839 21.940839h186.493544c66.539236 0 120.672053-54.132817 120.672053-120.672053s-54.132817-120.674101-120.672053-120.6741z" fill="#22C67F" /><path d="M173.2158 863.008367V160.915865c0-12.095157 9.845681-21.940839 21.940838-21.940838h570.450542c12.095157 0 21.940839 9.845681 21.940839 21.940838v416.867743H831.428672V160.915865c0-36.295711-29.525781-65.821492-65.821492-65.821492H195.156638c-36.295711 0-65.821492 29.524757-65.821492 65.821492v702.092502c0 36.295711 29.524757 65.821492 65.821492 65.821492h263.284945v-43.880653H195.156638c-12.095157 0-21.940839-9.845681-21.940838-21.940839z" fill="#22C67F" /><path d="M239.037292 270.618011h285.224759v43.880653H239.037292zM655.905035 248.677172h87.761306v87.761307h-87.761306zM239.037292 490.022302h504.629049v43.880653H239.037292zM239.037292 709.425568h241.344105V753.306222H239.037292z" fill="#74E8AE" /></svg> -->
														</span>
														<span class="checkbox-label">Llenar pre-solicitud de cxp para esta solicitud de autorización.</span>
													</span>




												</label>
											</div>
										</fieldset>
									</div>
									<!-- formulario cxp -->
									<div class="row">
										<br>
										<br>
										<div id="cxp-form" class="cxp-form col-lg-12 form-group">

											<div class="col-lg-6 column">
												<div class="col-lg-12 form-group">
													<label for="proveedorA">Proveedor<span class="text-danger">*</span> <i class="far fa-question-circle faq text-danger" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Tipo" data-content="A continuación se muestra solo los proveedores que no requieren factura o que se puede agregar posterior al pago." data-placement="right"></i></label>
													<select name="proveedorA" class="form-control select2 select2-hidden-accessible listaProveedores" style="width: 100%;" id="proveedorA"></select>
													<input type="hidden" name="idProveedor" class="form-control" id="tipoPagoProv">
												</div>
												<div class="col-lg-12 form-group">
													<label for="tipoFCXP">Tipo de pago<span class="text-danger">*</span> <i class="far fa-question-circle faq text-danger" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Tipo" data-content="A continuación se muestra solo los proveedores que no requieren factura o que se puede agregar posterior al pago." data-placement="right"></i></label>
													<select name="tipoFCXP" class="form-control select2 select2-hidden-accessible listaFormPago" style="width: 100%;" id="tipoFCXP"></select>
													<input type="hidden" name="tipoPagoProv" class="form-control" id="tipoPagoProv">
												</div>
											</div>
											<div class="col-lg-6 column">
												<div class="col-lg-12 form-group">
													<label for="descr">Total</label>
													<input class="form-control" id="cantidad" name="cantidad" placeholder="Cantidad de la solicitud">
												</div>
												<div class="col-lg-12 form-group">
													<label for="idEmpresaA">Empresa<span class="text-danger">*</span> <i class="far fa-question-circle faq text-danger" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Tipo" data-content="A continuación se muestra solo los proveedores que no requieren factura o que se puede agregar posterior al pago." data-placement="right"></i></label>
													<select name="idEmpresaA" class="form-control select2 select2-hidden-accessible listadoEmpresa" style="width: 100%;" id="idEmpresaA"></select>
													<!-- <input type="hidden" name="idEmpresaA" class="form-control" id="idEmpresaA"> -->
												</div>
											</div>

											<div class="col-lg-12 column form-group">

												<div class="col-lg-12 form-group">
													<label for="descr">RFC</label>
													<input class="form-control" id="rfc" name="rfc" placeholder="Numero de registro federal de contrubuyentes" readonly>
												</div>

												<div class="col-lg-12 form-group">
													<label for="descr">CLABE</label>
													<input class="form-control" id="clabe" name="clabe" placeholder="CLABE interbancaria" readonly>
												</div>

												<div class="col-lg-12 form-group">
													<label for="descr">Email</label>
													<input class="form-control" id="emailcxpf" name="emailcxpf" placeholder="Email" readonly>
													<input class="form-control" id="idbanco" name="idbanco" placeholder="text" type="hidden">
												</div>

											</div>
										</div>
									</div>


							</div>
							<div class="row">
								<br>
								<br>
								<div class="col-lg-12 form-group">
									<button type="submit" class="btn designed-button btn-success btn-block" id="btnGuardarSol">GUARDAR SOLICITUD</button>
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
</div>

<div id="modal_visor_solicitud_aut" class="modal fade font-format" role="dialog" style=" width: 100%; height: 100%; overflow-y: clip;">

	<div style=" display: flex; height: 100%; overflow-y: hidden;  border-radius: 10px;">

		<div id="modal-visor" class="modal-dialog" style=" width: 70%;  overflow-x: clip; max-height: 95%; margin-left:4%;  background-color: white; overflow-y: clip; overflow-x: clip;  border-radius: 10px; ">
			<div class="modal-content" style=" width: 100%;   background-color: white; border-radius: 10px; max-height: 100%; margin-left:4%; background-color: white; overflow-y: scroll; overflow-x: clip; ">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="modalTitleVisor">Nombre de ejemplo de la solicitud.</h4>
				</div>
				<div class="modal-body" style="">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#generar_solicitud" role="tab" aria-controls="generar_solicitud" aria-selected="false">DATOS DE LA SOLICITUD</a></li>
						</ul>
					</div>
					<div class="tab-content">
						<div class="active tab-pane" id="generar_solicitud">
							<div class="row">
								<div class="col-lg-12">
									<form id="formulario_actualizar_autorizacion" method="post">

										<div class=" col-lg-12 form-group">

											<div class="col-lg-6">

												<!-- Nombre y tipo  -->
												<div class="row">

													<div class="col-lg-6 form-group">
														<label style=" text-alignment: left;">Nombre de el solicitante:</label>
														<br>
														<label id="solicitanteSol" name="solicitanteSol" style=" font-size: 23px; font-weight: normal;"></label>
													</div>

													<div class="col-lg-6 form-group">
														<label style=" text-alignment: left;">Fecha de la solicitud:</label>
														<br>
														<label id="fechaSol" name="fechaSol" style=" font-size: 15px; font-weight: normal;"></label>
													</div>
												</div>
												<br>

												<div class="row">

													<div class="col-lg-6 form-group">
														<label for="descr">Titulo de la solicitud</label>
														<input class="form-control" id="tituloVisor" name="tituloVisor" placeholder="Nombre de solicitud">
													</div>

													<div class="col-lg-6 form-group">
														<label for="tipoVisor">Tipo<span class="text-danger">*</span> <i class="far fa-question-circle faq text-danger" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Tipo" data-content="A continuación se muestra solo los proveedores que no requieren factura o que se puede agregar posterior al pago." data-placement="right"></i></label>
														<select class="form-control select2 select2-hidden-accessible lstado_tipo_autorizacionVisor" style="width: 100%;" id="tipoVisor" name="tipoVisor" required></select>
														<input type="hidden" name="idtipoVisor" class="form-control" id="idtipoVisor">
													</div>
												</div>

												<!-- observaciones -->
												<div class="row">
													<div class="col-lg-12">
														<div class="form-group">
															<label for="descr">Observaciones</label>
															<textarea rows="10" class="form-control" id="descrVisor" name="descrVisor" placeholder="Descripción" required></textarea>
														</div>
													</div>
												</div>

												<!-- checkbox firmas  -->

												<div class="row">
													<br>
													<br>
													<fieldset class="checkbox-group">
														<legend class="checkbox-group-legend">Firmas requeridas: </legend>

														<div class="checkbox" style="margin-top: -5px;">
															<label class="checkbox-wrapper">
																<input type="checkbox" class="checkbox-input" id="darequiredVisor" name="darequiredVisor" />
																<span class="checkbox-tile">
																	<span class="checkbox-icon">
																		<i class='fas fa-signature'></i>
																		<!-- <svg fill="#000000" height="800px" width="800px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
																						 viewBox="0 0 512 512" xml:space="preserve">
																					<g>
																						<g>
																							<path d="M501.801,179.563h-33.023c3.145-11.057,1.345-23.229-5.394-33.004l13.011-13.011c1.912-1.913,2.987-4.507,2.987-7.212
																								c0-2.214-0.73-4.348-2.041-6.1l22.875-22.875c1.912-1.912,2.987-4.507,2.987-7.212s-1.075-5.298-2.987-7.212l-18.371-18.371
																								c-1.912-1.912-4.507-2.987-7.212-2.987s-5.3,1.075-7.212,2.987l-22.875,22.875c-3.995-2.993-9.681-2.685-13.313,0.946
																								l-91.176,91.176H10.199C4.566,179.564,0,184.131,0,189.763v250.458c0,5.632,4.566,10.199,10.199,10.199h491.602
																								c5.633,0,10.199-4.567,10.199-10.199V189.762C512,184.129,507.434,179.563,501.801,179.563z M474.633,86.203l3.947,3.946
																								l-15.581,15.58l-3.946-3.946L474.633,86.203z M438.445,110.023l16.313,16.313L280.372,300.721l-16.313-16.313L438.445,110.023z
																								 M446.481,179.563H430.38l18.053-18.053C450.886,167.374,450.231,174.228,446.481,179.563z M265.948,315.145l-39.423,39.422
																								c-1.874,1.875-4.365,2.908-7.016,2.908c-2.651,0-5.142-1.033-7.017-2.909l-2.28-2.279c-3.869-3.869-3.869-10.164,0-14.032
																								l39.423-39.423L265.948,315.145z M491.602,430.021H20.398V199.962H319.66L195.789,323.833c-9.713,9.714-11.437,24.428-5.19,35.927
																								l-8.165,8.165H64.534c-5.633,0-10.199,4.567-10.199,10.199s4.566,10.199,10.199,10.199h122.124c3.018,0,5.721-1.318,7.588-3.4
																								l10.766-10.729c4.397,2.401,9.348,3.68,14.497,3.68c8.099,0,15.713-3.155,21.44-8.881l169.031-169.031h16.524l-34.789,34.789
																								c-3.983,3.983-3.983,10.441,0,14.425c1.992,1.991,4.602,2.987,7.212,2.987c2.61,0,5.221-0.996,7.212-2.987l49.214-49.214h36.248
																								V430.021z"/>
																						</g>
																					</g>
																					<g>
																						<g>
																							<path d="M408.138,367.924h-74.516c-5.633,0-10.199,4.567-10.199,10.199s4.566,10.199,10.199,10.199h74.516
																								c5.633,0,10.199-4.567,10.199-10.199S413.77,367.924,408.138,367.924z"/>
																						</g>
																					</g>
																					<g>
																						<g>
																							<path d="M451.605,367.924h-6.209c-5.633,0-10.199,4.567-10.199,10.199s4.566,10.199,10.199,10.199h6.209
																								c5.633,0,10.199-4.567,10.199-10.199S457.238,367.924,451.605,367.924z"/>
																						</g>
																					</g>
																					<g>
																						<g>
																							<path d="M186.659,231.311H64.534c-5.633,0-10.199,4.567-10.199,10.199c0,5.632,4.566,10.199,10.199,10.199h122.124
																								c5.633,0,10.199-4.567,10.199-10.199C196.858,235.878,192.292,231.311,186.659,231.311z"/>
																						</g>
																					</g>
																					<g>
																						<g>
																							<path d="M186.659,275.814H64.534c-5.633,0-10.199,4.567-10.199,10.199s4.566,10.199,10.199,10.199h122.124
																								c5.633,0,10.199-4.567,10.199-10.199S192.292,275.814,186.659,275.814z"/>
																						</g>
																					</g>
																					</svg> -->
																	</span>
																	<span class="checkbox-label">Requiere firma por dirección de area</span>
																</span>
															</label>
														</div>
														<div class="checkbox">
															<label class="checkbox-wrapper">
																<input type="checkbox" class="checkbox-input" id="dgrequiredVisor" name="dgrequiredVisor" />
																<span class="checkbox-tile">
																	<span class="checkbox-icon">
																		<i class='fas fa-signature'></i>
																		<!-- <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
																						 viewBox="0 0 512 512" xml:space="preserve">
																					<rect x="10.199" y="189.765" style="fill:#FFD890;" width="491.602" height="250.462"/>
																					<path style="fill:#BDD169;" d="M280.373,315.145l-30.736-30.736l-46.635,46.635c-7.858,7.858-7.858,20.598,0,28.457l2.281,2.281
																						c7.858,7.858,20.598,7.858,28.457,0L280.373,315.145z"/>
																					<rect x="337.679" y="71.86" transform="matrix(-0.7071 -0.7071 0.7071 -0.7071 468.34 604.7255)" style="fill:#E6E6E6;" width="43.468" height="267.013"/>
																					<rect x="455.817" y="74.742" transform="matrix(-0.7071 -0.7071 0.7071 -0.7071 732.4503 495.3077)" style="fill:#BDD169;" width="25.979" height="42.432"/>
																					<g>
																						<path style="fill:#4C1D1D;" d="M501.801,179.563h-33.023c3.145-11.057,1.345-23.229-5.394-33.004l13.011-13.011
																							c1.912-1.913,2.987-4.507,2.987-7.212c0-2.214-0.73-4.348-2.041-6.1l22.875-22.875c1.912-1.912,2.987-4.507,2.987-7.212
																							c0-2.705-1.075-5.298-2.987-7.212l-18.371-18.371c-1.912-1.912-4.507-2.987-7.212-2.987s-5.3,1.075-7.212,2.987l-22.875,22.875
																							c-3.995-2.993-9.681-2.685-13.313,0.946l-91.176,91.176H10.199C4.566,179.564,0,184.131,0,189.763v250.458
																							c0,5.632,4.566,10.199,10.199,10.199h491.602c5.633,0,10.199-4.567,10.199-10.199V189.762
																							C512,184.129,507.434,179.563,501.801,179.563z M446.481,179.563H430.38l18.053-18.053
																							C450.886,167.374,450.231,174.228,446.481,179.563z M474.633,86.203l3.947,3.946l-15.581,15.58l-3.946-3.946L474.633,86.203z
																							 M438.445,110.023l16.313,16.313L280.372,300.721l-16.313-16.313L438.445,110.023z M210.213,352.288
																							c-3.869-3.869-3.869-10.163,0-14.032l39.423-39.423l16.313,16.313l-39.423,39.422c-1.874,1.875-4.365,2.908-7.016,2.908
																							s-5.142-1.033-7.017-2.909L210.213,352.288z M409.981,199.962h16.524l-34.789,34.789c-3.983,3.983-3.983,10.441,0,14.425
																							c1.992,1.991,4.602,2.987,7.212,2.987s5.221-0.996,7.212-2.987l49.214-49.214h36.248v230.059H20.398V199.962H319.66
																							L195.789,323.833c-9.713,9.714-11.437,24.428-5.19,35.927l-8.165,8.165H64.534c-5.633,0-10.199,4.567-10.199,10.199
																							c0,5.632,4.566,10.199,10.199,10.199h122.124c3.018,0,5.721-1.318,7.588-3.4l10.766-10.729c4.397,2.401,9.348,3.68,14.497,3.68
																							c8.099,0,15.713-3.155,21.44-8.881L409.981,199.962z"/>
																						<path style="fill:#4C1D1D;" d="M408.138,367.924h-74.516c-5.633,0-10.199,4.567-10.199,10.199c0,5.632,4.566,10.199,10.199,10.199
																							h74.516c5.633,0,10.199-4.567,10.199-10.199C418.338,372.491,413.77,367.924,408.138,367.924z"/>
																						<path style="fill:#4C1D1D;" d="M451.605,367.924h-6.209c-5.633,0-10.199,4.567-10.199,10.199c0,5.632,4.566,10.199,10.199,10.199
																							h6.209c5.633,0,10.199-4.567,10.199-10.199C461.805,372.491,457.238,367.924,451.605,367.924z"/>
																						<path style="fill:#4C1D1D;" d="M64.534,251.709h122.124c5.633,0,10.199-4.567,10.199-10.199s-4.566-10.199-10.199-10.199H64.534
																							c-5.633,0-10.199,4.567-10.199,10.199S58.901,251.709,64.534,251.709z"/>
																						<path style="fill:#4C1D1D;" d="M64.534,296.212h122.124c5.633,0,10.199-4.567,10.199-10.199c0-5.632-4.566-10.199-10.199-10.199
																							H64.534c-5.633,0-10.199,4.567-10.199,10.199C54.335,291.645,58.901,296.212,64.534,296.212z"/>
																					</g>
																					</svg>	 -->
																	</span>
																	<span class="checkbox-label">Requiere firma por direccion general</span>
																</span>
															</label>
														</div>

													</fieldset>
												</div>

											</div>

											<div class="col-lg-6">
												<!-- Input de correos  -->

												<div class="row">
													<!-- <br> -->
													<div class="col-lg-12">
														<div class="input-group">
															<div class="form-floating">

																<label>Informar estado de solicitud a (Inserta email y presiona enter): </label>

																<div class="input-group input-group-md">

																	<input type="email" id="example_emailBSVisor" name="example_emailBSVisor" value='[]' placeholder="Inserta email y presiona enter.">


																</div>

																<br>

																<!-- <label for="motivo">Involucrados</label> -->
																<label for="motivo">Agregar personas que podrán ver/modificar esta solicitud.</label>
																<br>
																<select name="involucradosVisor" class="form-control select2 select2-hidden-accessible listado_involucradosVisor" style="width: 100%;" id="involucradosVisor"></select>

															</div>
															<br>

															<button type="button" id="addVisor" onclick="agregarVisor()" class="btn btn-primary" style="width: 100%;">Agregar involucrado</button>
														</div>
													</div>
												</div>
												<!-- Involucrados y autorizaciones  -->
												<div class="row">
													<div class="row justify-content-center">
														<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
															<div class="table-responsive">
																<table id='tbl_motivVisor' class="table table-bordered table-striped">
																	<thead>
																		<tr>
																			<th class="text-center">Involucrados:</th>
																		</tr>
																	</thead>
																	<tbody id="tbodyInvVisor">
																	</tbody>
																</table>
															</div>
														</div>
														<div class="col-xxl-3 col-md-6 col-sm-8 col-12 d-flex justify-content-center flex-column gap-2">
															<!-- <button type="button" class="btn btn-primary" id="btnsubformcolab">Enviar</button> -->
														</div>
													</div>
												</div>
												<!-- Involucrados listado -->
												<!-- <div class="row">
													<br>
													<div class="col-lg-12">
														<div class="input-group">
															<div class="form-floating"> -->
												<!-- //<label for="motivo">Autorización</label> -->
												<!-- <label for="motivo">Agregar personas que autorizaran esta solicitud.</label>
																<br>

																<select name="autorizadoresVisor" class="form-control select2 select2-hidden-accessible listado_autorizadosVisor" style="width: 100%;" id="autorizadoresVisor"></select>
															</div>
															<br>

															<button type="button" id="add1Visor" onclick="agregarAutorizadoresVisor()" class="btn btn-primary" style="width: 100%;">Agregar revisor</button>
														</div>
													</div>
												</div> -->

												<!-- Revisores -->
												<!-- <div class="row">
													<div class="row justify-content-center">
														<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
															<div class="table-responsive">
																<table id='tbl_autVisor' class="table table-bordered table-striped">
																	<thead>
																		<tr>
																			<th class="text-center">Autorizadores:</th>
																		</tr>
																	</thead>
																	<tbody id="tbodyAutVisor">
																	</tbody>
																</table>
															</div>
														</div>
														<div class="col-xxl-3 col-md-6 col-sm-8 col-12 d-flex justify-content-center flex-column gap-2"> -->
												<!-- <button type="button" class="btn btn-primary" id="btnsubformcolabVisor">Enviar</button> -->
												<!-- </div>
													</div>
												</div> -->

											</div>
										</div>



										<div class=" col-lg-12 form-group" style="background-color: white; border: 1px solid black; border-radius: 15px;">
											<div class="col-lg-6">
												<!-- input de documentos en servidor  -->
												<div class="row">

													<div class="col-lg-12">
														<div class="form-group">
															<!-- <h5><b>ARCHIVOS DE ESTA SOLICITUD: </b></h5> -->



															<!-- Revisores -->
															<div class="row">
																<div class="row justify-content-center">
																	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
																		<br>

																		<div class="table-responsive" style=" border-radius: 10px;">
																			<table id='tbl_documents_visor' class="table table-bordered">
																				<thead>
																					<tr>
																						<th class="text-center">Archivos cargados:</th>
																					</tr>
																				</thead>
																				<tbody id="tbodyDocumentsVisor">
																				</tbody>
																			</table>
																		</div>
																		<br>
																		<div class="table-responsive" style=" border-radius: 10px;">
																			<table id='tbl_documents_visor_to_eliminate' class="table table-bordered">
																				<thead>
																					<tr>
																						<th class="text-center">Archivos que seran eliminados al guardar esta solicitud:</th>
																					</tr>
																				</thead>
																				<tbody id="tbodyDocumentsVisorToEliminate">
																				</tbody>
																			</table>
																		</div>
																		<br>

																	</div>
																	<div class="col-xxl-3 col-md-6 col-sm-8 col-12 d-flex justify-content-center flex-column gap-2">
																		<!-- <button type="button" class="btn btn-primary" id="btnsubformcolabVisor">Enviar</button> -->
																	</div>
																</div>
															</div>

														</div>
													</div>
												</div>



											</div>

											<div class="col-lg-6">
												<!-- input de documentos  -->
												<div class="row">

													<div class="col-lg-12">
														<div class="form-group">

															<h5><b>USA ESTA SECCIÓN PARA CARGAR NUEVOS DOCUMENTOS A ESTA SOLICITUD: </b></h5>



															<label for="images" class="drop-container" id="dropcontainerVisor">
																<span class="drop-title">Arrastra tus archivos aqui</span>

																o

																<input type="file" id="dropedFilesInputVisor" name="dropedFilesInputVisor[]" multiple="multiple" />
																<span style="cursor: pointer; cursor: hand;" onclick="cleanInputsVisor($('#dropedFilesInputVisor'))"><i class="fa fa-trash"></i> Limpiar lista de archivos</span>

																<ul id="outputVisor"> </ul>
															</label>

														</div>
													</div>
												</div>

											</div>


										</div>
										<!-- <div id="dialog-confirm" title="Empty the recycle bin?">
															  <p>
															    <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
															    These items will be permanently deleted and cannot be recovered. Are you sure?
															  </p>
															</div><div id="dialog-confirm" title="Empty the recycle bin?">
															  <p>
															    <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
															    These items will be permanently deleted and cannot be recovered. Are you sure?
															  </p>
															</div> -->

										<div class=" col-lg-12 form-group">

											<div class="col-lg-12">
												<!-- checkbox cxp   -->
												<div class="row">
													<br>
													<br>
													<fieldset class="checkbox-group">
														<legend class="checkbox-group-legend">Solicitud en cxp </legend>

														<div class="checkbox" style="margin-top: -5px;">
															<label class="checkbox-wrapper">
																<input type="checkbox" class="checkbox-input inputcxp" id="cxprequiredVisor" name="cxprequiredVisor" />
																<span class="checkbox-tile">

																	<span class="checkbox-icon">
																		<i class="fas fa-file-archive"></i>
																		<!-- <svg width="800px" height="800px" viewBox="0 0 1024 1024" class="icon"  version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M776.577087 687.485754h-142.616987l35.111076-26.332283c9.69517-7.263442 11.655911-21.019342 4.381205-30.714512-7.253204-9.652167-20.986578-11.655911-30.714512-4.381205l-111.159134 83.368838 111.159134 83.368839a21.822068 21.822068 0 0 0 13.14464 4.392468c6.674708 0 13.262387-3.031724 17.569872-8.773673 7.273681-9.69517 5.313965-23.451069-4.381205-30.714512l-35.111076-26.332283h142.616987c42.337658 0 76.791399 34.453741 76.7914 76.791399s-34.453741 76.791399-76.7914 76.7914H590.083543c-12.116659 0-21.940839 9.82418-21.940839 21.940838s9.82418 21.940839 21.940839 21.940839h186.493544c66.539236 0 120.672053-54.132817 120.672053-120.672053s-54.132817-120.674101-120.672053-120.6741z" fill="#22C67F" /><path d="M173.2158 863.008367V160.915865c0-12.095157 9.845681-21.940839 21.940838-21.940838h570.450542c12.095157 0 21.940839 9.845681 21.940839 21.940838v416.867743H831.428672V160.915865c0-36.295711-29.525781-65.821492-65.821492-65.821492H195.156638c-36.295711 0-65.821492 29.524757-65.821492 65.821492v702.092502c0 36.295711 29.524757 65.821492 65.821492 65.821492h263.284945v-43.880653H195.156638c-12.095157 0-21.940839-9.845681-21.940838-21.940839z" fill="#22C67F" /><path d="M239.037292 270.618011h285.224759v43.880653H239.037292zM655.905035 248.677172h87.761306v87.761307h-87.761306zM239.037292 490.022302h504.629049v43.880653H239.037292zM239.037292 709.425568h241.344105V753.306222H239.037292z" fill="#74E8AE" /></svg> -->
																	</span>
																	<span class="checkbox-label">Llenar pre-solicitud de cxp para esta solicitud de autorización.</span>

																</span>
															</label>
														</div>
													</fieldset>
												</div>
												<div class="row">
													<br>
													<br>
													<div id="cxp-formV" class="cxp-form col-lg-12 form-group">

														<div class="col-lg-6 column">
															<div class="col-lg-12 form-group">
																<label for="proveedorAV">Proveedor<span class="text-danger">*</span> <i class="far fa-question-circle faq text-danger" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Tipo" data-content="A continuación se muestra solo los proveedores que no requieren factura o que se puede agregar posterior al pago." data-placement="right"></i></label>
																<select name="proveedorAV" class="form-control select2 select2-hidden-accessible listaProveedores" style="width: 100%;" id="proveedorAV"></select>
																<input type="hidden" name="idProveedor" class="form-control" id="tipoPagoProv">
															</div>
															<div class="col-lg-12 form-group">
																<label for="tipoFCXPV">Tipo de pago<span class="text-danger">*</span> <i class="far fa-question-circle faq text-danger" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Tipo" data-content="A continuación se muestra solo los proveedores que no requieren factura o que se puede agregar posterior al pago." data-placement="right"></i></label>
																<select name="tipoFCXPV" class="form-control select2 select2-hidden-accessible listaFormPago" style="width: 100%;" id="tipoFCXPV"></select>
																<input type="hidden" name="tipoPagoProv" class="form-control" id="tipoPagoProv">
															</div>
														</div>
														<div class="col-lg-6 column">
															<div class="col-lg-12 form-group">
																<label for="descr">Total</label>
																<input class="form-control" id="cantidadV" name="cantidadV" placeholder="Cantidad de la solicitud">
															</div>
															<div class="col-lg-12 form-group">
																<label for="idEmpresaV">Empresa<span class="text-danger">*</span> <i class="far fa-question-circle faq text-danger" tabindex="0" data-container="body" data-trigger="focus" data-toggle="popover" title="Tipo" data-content="A continuación se muestra solo los proveedores que no requieren factura o que se puede agregar posterior al pago." data-placement="right"></i></label>
																<select name="idEmpresaV" class="form-control select2 select2-hidden-accessible listadoEmpresa" style="width: 100%;" id="idEmpresaV"></select>
																<!-- <input type="hidden" name="idEmpresaV" class="form-control" id="idEmpresaV"> -->
															</div>
														</div>
														<div class="col-lg-12 column form-group">



															<div class="col-lg-12 form-group">
																<label for="descr">RFC</label>
																<input class="form-control" id="rfcV" name="rfcV" placeholder="Numero de registro federal de contrubuyentes" readonly>
															</div>

															<div class="col-lg-12 form-group">
																<label for="descr">CLABE</label>
																<input class="form-control" id="clabeV" name="clabeV" placeholder="CLABE interbancaria" readonly>
															</div>

															<div class="col-lg-12 form-group">
																<label for="descr">Email</label>
																<input class="form-control" id="emailcxpfV" name="emailcxpfV" placeholder="Email" readonly>
																
															</div>
															<input class="form-control" id="idbancoV" name="idbancoV" placeholder="text" type="hidden">

														</div>
													</div>
												</div>

											</div>

										</div>

										<div class="row">
											<br>
											<br>
											<input id="user-comment-helper" style="visibility: collapse;"></input>
											<div class="col-lg-12 form-group">
												<button type="submit" class="fancy-button" id="btnGuardarSolVisor" style="border-bottom: 15px; height:50px; width:100%;">GUARDAR CAMBIOS</button>
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

		<div id="modal-visor-comments" class="modal-dialog " style=" flex-direction: column; width: 25%;   overflow-x: clip; overflow-y: clip;  border-radius: 10px; ">
			<div class="modal-content row" role="document" style=" width: 100%;  overflow-x: clip; overflow-y: clip; background-color: white; border-radius: 10px;">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="modalTitleVisor">Comentarios de esta solicitud.</h4>
				</div>

				<div id="chat-section" class="modal-body" style="overflow-y: scroll; overflow-x: clip; max-height: 400px; ">
					<div class="container-fluid">
						<div id="comments-container" class="container" style="width: 100%; padding: 5px; overflow-x: clip;">
							<div id="empty-comments-message" class="row message-balloon" style="border-color:gray; background-color: #ccc; ">
								<label>Sin comentarios registrados.</label>
								<!-- <p>${item.descripcion}</p>
								  		<span class="time-right">${item.fechaCreacion}</span> -->
							</div>


						</div>
					</div>
				</div>

				<div class="modal-footer">

					<!-- <form> -->
					<textarea rows="10" class="col-lg-12" id="user-comment" name="user-comment" placeholder="Escribe tus comentarios aqui." required></textarea>
					<br>
					<br>
					<br>
					<!-- </form> -->
					<label>Este comentario se agregara a la solicitud al momento de enviár una revisión.</label>

					<!-- <button id="addCommentButton" class= "col-lg-12 fancy-button"  value="">Guardar comentario</button> -->


				</div>

			</div>

		</div>

	</div>


</div>


<div class="modal fade" id="modalAut" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<form id="formulario_enviar_aut" method="post">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h5 class="modal-title" id="exampleModalLongTitle">Autorizar</h5>
				</div>
				<div class="modal-body">
					<div class="row">
						<br>
						<div class="col-lg-12">
							<div class="input-group">
								<div class="form-floating">
									<label for="motivo">Enviar a:</label>
									<select name="enviarA" class="form-control select2 select2-hidden-accessible listadoAreas" style="width: 100%;" id="enviarA" required></select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary">Envíar</button>
				</div>
			</form>
		</div>
	</div>
</div>


<script>
	var rol = `<?= $this->session->userdata("inicio_sesion")['rol'] ?>`;

	function plantilla(titulo, recomendaciones, descripcion, estatus_color, estatus, color_boton, idsolicitud, visto, fecha, nombre, departamento, data) {

		var mensaje = `
			<style> 

					@media screen and (max-width: 300px) {
					  .cancelbtn, .signupbtn {
					     width: 100%;
					  }
					}
				
					.row-card-aut {  
					  display: flex;
					  flex-wrap: wrap;
					  border:1px solid gray; 
					  border-radius: 15px; 
					  background-color: white; 
					  overflow: hidden; 
					  height: 200px; 
					  padding: 5px 15px;
					
					}
				
					.top {
						display: flex;
						flex: 100%;
					  	--background-color: red;
					  	margin: 0px;
					
					}
				
					.side {
					  flex: 75%;
					  --background-color: purple;
					}
				
					/* Main column */
					.main {
					  flex: 25%;
					  --background-color: green;
					  padding: 5px;
					}
				
					.fakeimg {
					  background-color: #ccc;
					  width: 100%;
					}
				
					.title {
					  color: black;
					  font-size: 19px;
					  font-weight: bold;
					  --background-color: green;
					  margin: 0px;
					
					}
				
					.subtitle {
					  color: grey;
					  font-size: 12px;
					  --background-color: red;
					  margin: 0px;
					
					}
					.date-etiqueta {
					  color: #ccc;
					  font-size: 12px;
					  margin-bottom: 2px;
					  word-wrap: break-word; 
					  word-break: break-all; 
					  overflow: hidden; 
					  text-overflow: ellipsis;
					  text-align: right;
					
					}
					.folio-etiqueta {
					  color: #ccc;
					  font-size: 17px;
					  margin-bottom: 2px;
					  word-wrap: break-word; 
					  word-break: break-all; 
					  overflow: hidden; 
					  text-overflow: ellipsis;
					  text-align: left;
					
					}
				
					.button-container {
						height: 30px; 
						width: 100%; 
						background-color: 
						white; 
						text-align: left;
						padding: 0px 0px;
					
					}
				
					.tag-cloud {
					  display: inline-block;
					  color: white;
					  padding: 3px 5px;
					  border-radius: 5px;
					  margin-left: 0px;
					  font-size: 12px;
					  font-weight: Bold;
					  font-family: 'Trebuchet MS', sans-serif;
					  align; left;
					}
				
					.animated-button {
					  border-radius: 4px;
					  background-color: ` + color_boton + `;
					  border: none;
					  color: #FFFFFF;
					  text-align: center;
					  font-size: 12px;
					  font-weight: bold;
					  width: 23%;
					  height: 30px;
					  transition: all 0.5s;
					  cursor: pointer;
					  margin: auto;
					  margin-right: 5px;
					}
				
					.animated-button span {
					  cursor: pointer;
					  display: inline-block;
					  position: relative;
					  transition: 0.5s;
					}
				
					.animated-button span:after {
					  font-family: "Font Awesome 5 Free";
					  content: "\\f06e";
					  position: absolute;
					  opacity: 0;
					  top: 0;
					  right: -20px;
					  transition: 0.5s;
					}
				
					.cancel-btnad span:after {
					  font-family: "Font Awesome 5 Free";
					  content: "\\f410";
					  position: absolute;
					  opacity: 0;
					  top: 0;
					  right: -20px;
					  transition: 0.5s;
					}
				
					.inspect-doc-btnad span:after {
					  font-family: "Font Awesome 5 Free";
					  content: "\\f06e";
					  position: absolute;
					  opacity: 0;
					  top: 0;
					  right: -10px;
					  transition: 0.5s;
					}
				
					.aprove-btnad span:after {
					  font-family: "Font Awesome 5 Free";
					  content: "\\f00c";
					  position: absolute;
					  opacity: 0;
					  top: 0;
					  right: -20px;
					  transition: 0.5s;
					}
				
					.reject-btnad span:after {
					  font-family: "Font Awesome 5 Free";
					  content: "\\f057";
					  position: absolute;
					  opacity: 0;
					  top: 0;
					  right: -20px;
					  transition: 0.5s;
					}
				
					.animated-button:hover span {
					  padding-right: 25px;
					}
				
					.animated-button:hover span:after {
					  opacity: 1;
					  right: 0;
					}
				
					.general-button {
					  border-radius: 12px;
					  background-color: white;
					  border: none;
					  color: gray;
					  text-align: center;
					  font-size: 12px;
					  font-weight: bold;
					  width: auto;
					  height: 20px;
					  transition: all 0.5s;
					  cursor: pointer;
					  align: right;
					  margin-left: 10px;
					  margin-top: 6px;
					  margin-right: 10px; !Important
					}
				
					.general-button:hover {
					  background-color: #ccc;
					  color: white;
					}
				
					.chip {
					  display: inline-block;
					  padding: 0 0px;
					  width: 20px;
					  height: 20px;
					  font-size: 16px;
					  line-height: 25px;
					  border-radius: 20px;
					  background-color: #f1f1f1;
					  margin-right: 0px;
					  margin-top: 0px;
					}
				
			</style>
				
			<div class="row-card-aut ">
						<div class="top" style=" height: 25px; padding: 2px; margin-top: 5px;" >
				
							<span class="tag-cloud" style="  background-color: ` + estatus_color + `; " >` + estatus + `</span>  
							<span class="tag-cloud" style="  background-color: #02bd53; margin-left: 5px;" >Con solicitud a CXP </span> 
				
						<div class="chip" style = "margin-left: auto;">
				
							<i class='fas fa-user-circle' style='font-size:25px; color: #ffc869;'></i>
				
						</div> 
				
							<button class="general-button">
						<span> Solicita: ` + nombre + ` </span>
							</button>
				
							<div class="chip">
				
								<i class='fas fa-building' style='font-size:20px; color: black; '></i>
				
							</div> 
				
							<button class= "general-button">
				
							<span> Departamento:  ` + departamento + `  </span>
				
								</button>
				
								<button class="general-button" style = "margin-left: 5px; visibility: hidden; width: 24%;" >
								<span>Rechazar solicitud </span>
								</button>
				
							</div>
				
						<div class="side" style="height: 165px; text-align: left;">
							<p class="folio-etiqueta"> Numero de solicitud: #` + idsolicitud + `</p>
							<p class="title">` + titulo + `</p>
							<p class="subtitle" style=" color: black; word-wrap: break-word; word-break: break-all; overflow: hidden; text-overflow: ellipsis; height: 65px;"> Descripcion: ` + descripcion + `</p>
				
    							<div class="button-container">
								`;
		if (data.idEstatus != "8") {
			mensaje += `
											<button class="animated-button cancel-btnad cancelarSol" style="background-color: #ccc;" value="` + idsolicitud + `"><span>Cancelar solicitud </span></button>
						
											<button class="animated-button verSolicitud " style="background-color:#1287b5;" value="` + idsolicitud + `" data-value=` + data + `><span>Revisar solicitud </span></button>
												`;
			if (rol == 'AS' && (data.idEstatus == "1" || data.idEstatus == "2" || data.idEstatus == "7")) {
				mensaje += `<button class="animated-button aprove-btnad autorizarSolicitud notification" style="background-color: green;" value="` + idsolicitud + `" data-value=3 ><span>Registrar Vo.Bo revisión</span></button>`;
			}
			if (rol == 'DA' && data.requiereDA == 1 && (data.idEstatus == "3")) {
				mensaje += `<button class="animated-button aprove-btnad autorizarSolicitud notification" style="background-color: green;" value="` + idsolicitud + `" data-value=4><span> Aprobar solicitud </span></button>
																	<button class="animated-button reject-btnad rechazarAutorizacion" style="background-color: red;" value="` + idsolicitud + `"><span>Rechazar solicitud </span></button>`;
			}
			if (rol == 'DG' && (data.requiereDG == "1" || data.idEstatus > "4")) {
				mensaje += `<button class="animated-button aprove-btnad autorizarSolicitud notification" style="background-color: green;" value="` + idsolicitud + `" data-value=5 ><span>Aprobar solicitud</span></button>
													<button class="animated-button reject-btnad rechazarAutorizacion" style="background-color: red;" value="` + idsolicitud + `"><span>Rechazar solicitud </span></button>`;
			}
		}

		mensaje += `
										</div>
				
  						</div>	
  						<div class="main">
						  	<div class="fakeimg" style="height:90%;">
								<button class="animated-button inspect-doc-btnad downloadFile" style="background-color: transparent; height: 100%; width: 100%;   color: gray;
    					 		margin-left: 0px; font-size: 35px;" value="` + idsolicitud + `"><span style="text-align:center; background-color: red;"> </span></button>
							</div>
						<div style="height:15%;">
						<p class="date-etiqueta"> Fecha de solicitud: ` + fecha + `</p>
				
						</div>
				
				
   					</div>
  			</div>
			`;
		return mensaje;
	}

	var table_autorizar;
	var selectedIdAutorizacion = 0;

	$("#tabla_autorizaciones").ready(function() {

		var color_etiqueta = "#CCCCCC"
		var texto_etiqueta = "Now"
		var color_boton = "#CCCCCC"

		table_autorizar = $('#tabla_autorizaciones').DataTable({

			"orderable": false,
			"language": lenguaje,
			"processing": false,
			"pageLength": 10,
			"bAutoWidth": false,
			"bLengthChange": false,
			"bInfo": false,
			"searching": true,
			// "scrollY": '60vh',
			"scrollX": false,
			"paging": true,
			"columns": [{
					"width": "1%",
					"orderable": false,
					"data": function(d) {
						return d.idAutorizacion
					}
				},
				{
					"width": "99%",
					"orderable": false,
					"searching": true,

					"data": function(d) {
						//titulo, recomendaciones, descripcion, estatus_color, estatus
						switch (d.idEstatus) {
							case "1":
								color_etiqueta = "#ffa50a" //naranja
								texto_etiqueta = "Pendiente"
								color_boton = "#1287b5"

								break;

							case "2":
								color_etiqueta = "#04ba16" //verde
								texto_etiqueta = "Autorizada"
								color_boton = "#1287b5"

								break;

							case "3":
								// color_etiqueta = "#c90616" //rojo
								color_etiqueta = "#04ba16" //VERDE
								texto_etiqueta = "Vo.Bo involucrados"
								color_boton = "white"

								break;

							case "4":
								color_etiqueta = "#6b484b" //vino
								texto_etiqueta = "Aprobada DA"
								color_boton = "white"

								break;

							case "5":
								color_etiqueta = "#155ed4" //azul
								texto_etiqueta = "Aprobada DG"
								color_boton = "#1287b5"

								break;

							case "6":
								color_etiqueta = "#486b50" //verde militar
								texto_etiqueta = "Pendiente de solicitud en CXP"
								color_boton = "#1287b5"

								break;

							case "7":
								color_etiqueta = "#c90616" //ROJO
								texto_etiqueta = "Rezada luego de aprobación"
								color_boton = "#1287b5"

								break;

							default:
								color_etiqueta = "#CCCCCC"
								texto_etiqueta = "Error"
								color_boton = "#CCCCCC"

								break;
						}

						return plantilla(d.titulo, d.recomendaciones, d.descripcion_au, color_etiqueta, texto_etiqueta, color_boton, d.idAutorizacion, d.visto, d.fecha, d.nombres + ' ' + d.apellidos, d.depto, d);

					}


				}




			],
			"columnDefs": [{
				target: 2,
				visible: false,
				searchable: false
			}, ],
			"ajax": {
				"url": url + "Solicitudes_autorizacion/getAutorizaciones",
				"type": "POST",
				"data": function(d) {
					d.opcion = $("#pbuscar").val()

				}
			}
		});


	});






	var limite_cajachica = 0;
	var data_usuario = <?php echo json_encode($this->session->userdata("inicio_sesion")); ?>;

	// Para añadir
	var rowIdx = 0;
	var arr = [];
	var arra = [];
	let myset = new Set();
	var colCount = $("#tbody tr").length;
	var size = 0;
	let now, intervalrel = null;
	let idSolicitud;
	var proveedoresList;
	//Fin añadir


	//Funcionalida de botones en involucrados
	function agregar() {
		//fixme

		var e = document.getElementById("involucrados");
		var elemento = e.target;
		var value = e.value;
		var txt = e.options[e.selectedIndex].text;

		var dataID = $(e).find(':selected').attr('data-id');
		if (value != "") {
			// document.getElementById("error").style.display = "none";
			$("#tbl_motiv").show();
			var text = e.options[e.selectedIndex].text;
			var option = document.createElement("option");
			myset.add(text);
			Array.from(myset);
			size = size + 1;

			$('#btnsubformcolab').show();
			// if (size == 3) {
			// $("#add").attr('disabled', 'disabled');
			// }


			let descmot = "";

			$("#involucrados option[value=" + value + "]").each(function() {
				$(this).attr('hidden', 'hidden');
				descmot = $("#involucrados").find("option:selected").attr("title");
			});

			$('#tbodyInv').append(`<tr id="R${value}">
                                    <td class="row-index" style="border: none;">
                                        <p class="w-100"> ${text}&nbsp;</p>
                                        <input type="hidden" name="usuario[]" value="${value}" />
                                        <input type="hidden" name="texto[]" value="${text}" />
                                        <input type="hidden" name="tipo[]" value="1" />
                                    </td>
                                    <td class="text-center" style="border: none;">
                                        <button class="btn btn-danger remove btn-sm" id="${value}" type="button">Quitar</button>
                                    </td>
                                </tr>`);

			$("#involucrados").val("").change();
		} else {
			// document.getElementById("error").style.display = "block";
		}
		const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
		const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
	}

	// 	$(function() {
	//     $("#dialog-confirm").dialog({
	//       resizable: false,
	//       height:140,
	//       modal: true,
	//       buttons: {
	//         "Delete all items": function() {
	//           $(this).dialog("close");
	//         },
	//         Cancel: function() {
	//           $(this).dialog("close");
	//         }
	//       }
	//     });
	//   });


	//Funcionalida de botones en involucrados
	function agregarVisor() {

		var e = document.getElementById("involucradosVisor");
		var elemento = e.target;
		var value = e.value;
		var txt = e.options[e.selectedIndex].text;

		var dataID = $(e).find(':selected').attr('data-id');
		if (value != "") {
			// document.getElementById("error").style.display = "none";
			$("#tbl_motivVisor").show();
			var text = e.options[e.selectedIndex].text;
			var option = document.createElement("option");
			myset.add(text);
			Array.from(myset);
			size = size + 1;

			$('#btnsubformcolab').show();
			// if (size == 3) {
			// $("#add").attr('disabled', 'disabled');
			// }


			let descmot = "";

			$("#involucradosVisor option[value=" + value + "]").each(function() {
				$(this).attr('hidden', 'hidden');
				descmot = $("#involucradosVisor").find("option:selected").attr("title");
			});

			$('#tbodyInvVisor').append(`<tr id="R${value}">
									<td class="row-index" style="border: none;">
										<p class="w-100"> ${text}&nbsp;</p>
										<input type="hidden" name="usuarioVisor[]" value="${value}" />
										<input type="hidden" name="textoVisor[]" value="${text}" />
										<input type="hidden" name="tipoVisor[]" value="1" />
									</td>
									<td class="text-center" style="border: none;">
										<button class="btn btn-danger remove btn-sm" id="${value}" type="button">Quitar</button>
									</td>
								</tr>`);

			$("#involucradosVisor").val("").change();
		} else {
			// document.getElementById("error").style.display = "block";
		}
		const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
		const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
	}

	function agregarAutorizadores() {

		var e = document.getElementById("autorizadores");
		var elemento = e.target;
		var value = e.value;
		var txt = e.options[e.selectedIndex].text;

		var dataID = $(e).find(':selected').attr('data-id');

		if (value != "") {
			// document.getElementById("error").style.display = "none";
			$("#tbl_aut").show();
			var text = e.options[e.selectedIndex].text;
			var option = document.createElement("option");
			myset.add(text);
			Array.from(myset);
			size = size + 1;

			// $('#btnsubformcolab').show();
			// if (size == 3) {
			// $("#add").attr('disabled', 'disabled');
			// }


			let descmot = "";

			$("#autorizadores option[value=" + value + "]").each(function() {
				$(this).attr('hidden', 'hidden');
				descmot = $("#autorizadores").find("option:selected").attr("title");
			});

			$('#tbodyAut').append(`<tr id="R${value}">
                                    <td class="row-index" style="border: none;">
                                        <p class="w-100"> ${text}&nbsp;</p>
                                        <input type="hidden" name="usuarioAut[]" value="${value}" />
                                        <input type="hidden" name="textoAut[]" value="${text}" />
                                        <input type="hidden" name="tipoAut[]" value="2" />
                                    </td>
                                    <td class="text-center" style="border: none;">
                                        <button class="btn btn-danger removeAut btn-sm" id="${value}" type="button">Quitar</button>
                                    </td>
                                </tr>`);

			$("#autorizadores").val("").change();
		} else {
			// document.getElementById("error").style.display = "block";
		}
		const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
		const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
	}

	function agregarAutorizadoresVisor() {

		var e = document.getElementById("autorizadoresVisor");
		var elemento = e.target;
		var value = e.value;
		var txt = e.options[e.selectedIndex].text;

		var dataID = $(e).find(':selected').attr('data-id');
		if (value != "") {
			// document.getElementById("error").style.display = "none";
			$("#tbl_autVisor").show();
			var text = e.options[e.selectedIndex].text;
			var option = document.createElement("option");
			myset.add(text);
			Array.from(myset);
			size = size + 1;

			// $('#btnsubformcolab').show();
			// if (size == 3) {
			// $("#add").attr('disabled', 'disabled');
			// }


			let descmot = "";

			$("#autorizadoresVisor option[value=" + value + "]").each(function() {
				$(this).attr('hidden', 'hidden');
				descmot = $("#autorizadoresVisor").find("option:selected").attr("title");
			});

			$('#tbodyAutVisor').append(`<tr id="R${value}">
										<td class="row-index" style="border: none;">
											<p class="w-100"> ${text}&nbsp;</p>
											<input type="hidden" name="usuarioAutVisor[]" value="${value}" />
											<input type="hidden" name="textoAutVisor[]" value="${text}" />
											<input type="hidden" name="tipoAutVisor[]" value="2" />
										</td>
										<td class="text-center" style="border: none;">
											<button class="btn btn-danger removeVisor btn-sm" id="${value}" type="button">Quitar</button>
										</td>
									</tr>`);

			$("#autorizadoresVisor").val("").change();
		} else {
			// document.getElementById("error").style.display = "block";
		}
		const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
		const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
	}

	$('#tbodyInv').on('click', '.remove', function() {
		size = size - 1
		if (size == 0) {
			$("#tbl_motiv").hide();
			$('#btnsubformcolab').hide();
		}
		$("#add").removeAttr('disabled');
		var id = $(this).attr('id');
		$("#involucrados option[value=" + id + "]").removeAttr("hidden");
		$(this).closest('tr').remove();
		document.getElementById('#tbody').removeChild(document.getElementById("R" + id + ""));
	});

	$('#tbodyAut').on('click', '.removeAut', function() {
		size = size - 1
		if (size == 0) {
			$("#tbl_motiv").hide();
			$('#btnsubformcolab').hide();
		}
		$("#add1").removeAttr('disabled');
		var id = $(this).attr('id');
		$("#involucrados option[value=" + id + "]").removeAttr("hidden");
		$(this).closest('tr').remove();
		document.getElementById('#tbody').removeChild(document.getElementById("R" + id + ""));
	});

	$('#tbodyInvVisor').on('click', '.removeVisor', function() {
		size = size - 1
		if (size == 0) {
			$("#tbl_motivVisor").hide();
			$('#btnsubformcolab').hide();
		}
		$("#add").removeAttr('disabled');
		var id = $(this).attr('id');
		$("#involucradosVisor option[value=" + id + "]").removeAttr("hidden");
		$(this).closest('tr').remove();
		document.getElementById('#tbody').removeChild(document.getElementById("R" + id + ""));
	});

	$('#tbodyDocumentsVisor').on('click', '.removeDocumentVisor', function() {
		// console.log($(this).attr('id'));
		size = size - 1
		$("#tbodyDocumentsVisorToEliminate").show();

		if (size == 0) {
			//  $("#tbodyDocumentsVisorToEliminate").hide();
			$('#btnsubformcolab').hide();
		}

		$("#add").removeAttr('disabled');
		var id = $(this).attr('id');
		console.dir("im here")

		$("#tbodyDocumentsVisor option[value=" + id + "]").removeAttr("hidden");
		var text = ""
		$('#tbodyDocumentsVisor tr td input').each(function() {

			if ($(this)[0]["previousElementSibling"].value == id && $(this)[0]["name"] == "textoDocVisor[]") {

				text = $(this)[0]["value"]

			}


		});

		$(this).closest('tr').remove();

		let descmot = "";

		$("#tbodyDocumentsVisorToEliminate option[value=" + id + "]").each(function() {

			$(this).attr('hidden', 'hidden');
			descmot = $("#tbl_documents_visor_to_eliminate").find("option:selected").attr("title");
		});

		$('#tbodyDocumentsVisorToEliminate').append(`<tr id="R${id}">

											<td class="row-index" style="border: none;">
												<p class="w-100"> ${text}&nbsp;</p>
												<input type="hidden" name="usuarioDocToEliminateVisor[]" value="${id}" />
												<input type="hidden" name="textoDocToEliminateVisor[]" value="${text}" />
												<input type="hidden" name="tipoDocToEliminateVisor[]" value="2" />
											</td>

											<td class="text-center" style="border: none;">
											</td>

										</tr>`);

		$("#tbodyDocumentsVisorToEliminate").val("").change();

		// <button class="btn btn-primary removeDocumentVisor btn-sm" id="${id}" type="button">Cancelar eliminación.</button>

		// document.getElementById('#tbody').removeChild(document.getElementById("R" + id + ""));


	});

	$('#tbodyAutVisor').on('click', '.removeAutVisor', function() {
		size = size - 1
		if (size == 0) {
			$("#tbl_motivVisor").hide();
			$('#btnsubformcolab').hide();
		}
		$("#add1").removeAttr('disabled');
		var id = $(this).attr('id');
		$("#involucradosVisor option[value=" + id + "]").removeAttr("hidden");
		$(this).closest('tr').remove();
		document.getElementById('#tbody').removeChild(document.getElementById("R" + id + ""));
	});

	var today = new Date();
	var yesterday = new Date(today);
	yesterday.setDate(today.getDate() - 40);
	var fecha_minima = yesterday.toISOString().substr(0, 10);

	$(document).ready(function() {

		$.post(url + "Solicitudes_autorizacion/getInformacion", {}).done(function(data) {
			data = JSON.parse(data);
			$('.listaFormPago').append('<option value=" ">SELECCIONA UNA OPCIÓN</option>');
			$.each(data.data.formaPago, function(i, item) {
				$('.listaFormPago').append('<option value="' + item.valor + '" ">' + item.descripcion + '</option>');
			});
			$('.listadoEmpresa').append('<option value=" ">SELECCIONA UNA OPCIÓN</option>');
			$.each(data.data.empresas, function(i, item) {
				$('.listadoEmpresa').append('<option value="' + item.idempresa + '" ">' + item.nombre + '</option>');

			});
			proveedoresList = data.data?.proveedores;
			$('.listaProveedores').append('<option value=" ">SELECCIONA UNA OPCIÓN</option>');
			$.each(data.data.proveedores, function(i, item) {
				$('.listaProveedores').append('<option value="' + item.idproveedor + '" data-value="' + item.rfc + '" ">' + item.nombre + '</option>');

			});
		});



		$.getJSON(url + "Solicitudes_autorizacion/listadoTiposAutorizaciones").done(function(data) {
			var id = $(".lstado_tipo_autorizacion").val();
			$(".lstado_tipo_autorizacion").empty().append('<option value="" selected>Seleccione un tipo</option>');

			$.each(data.listadoTiposAutorizaciones, function(i, item) {
				$('.lstado_tipo_autorizacion').append('<option value="' + item.idtipo + '" ">' + item.descripcion + '</option>');
			});

			$(".listado_involucrados").empty().append('<option value="" selected>Seleccione un colaborador</option>');

			$.each(data.listadoInvolucrados, function(i, item) {
				$('.listado_involucrados').append('<option value="' + item.idusuario + '" ">' + item.nombreCompleto + ' - ' + item.rol_descripcion + '</option>');
			});

			$(".listado_autorizados").empty().append('<option value="" selected>Seleccione un revisor</option>');

			$.each(data.listadoInvolucrados, function(i, item) {
				$('.listado_autorizados').append('<option value="' + item.idusuario + '" ">' + item.nombreCompleto + ' - ' + item.rol_descripcion + '</option>');
			});


			var id = $(".lstado_tipo_autorizacionVisor").val();
			$(".lstado_tipo_autorizacionVisor").empty().append('<option value="" selected>Seleccione un tipo</option>');

			$.each(data.listadoTiposAutorizaciones, function(i, item) {
				$('.lstado_tipo_autorizacionVisor').append('<option value="' + item.idtipo + '" ">' + item.descripcion + '</option>');
			});
			$(".listado_involucradosVisor").empty().append('<option value="" selected>Seleccione colaborador.</option>');

			$.each(data.listadoInvolucrados, function(i, item) {
				$('.listado_involucradosVisor').append('<option value="' + item.idusuario + '" ">' + item.nombreCompleto + ' - ' + item.rol_descripcion + '</option>');
			});

			$(".listado_autorizadosVisor").empty().append('<option value="" selected>Seleccione un revisor.</option>');

			$.each(data.listadoInvolucrados, function(i, item) {
				$('.listado_autorizadosVisor').append('<option value="' + item.idusuario + '" ">' + item.nombreCompleto + ' - ' + item.rol_descripcion + '</option>');
			});


			$('.select2').select2();

		});

	});

	// $(document).ready(function(){

	// });
	$("#cxprequired").change(function() {

		if (this.checked) {

			document.getElementById("cxp-form").style.height = "500px";
			document.getElementById("cxp-form").style.border = "1px solid #ccc;";
			var scroll = $('#modal_formulario_solicitud_aut');
			scroll.animate({
				scrollTop: scroll.prop("scrollHeight") + 50
			});

		} else {

			document.getElementById("cxp-form").style.height = "0px";
			document.getElementById("cxp-form").style.border = "1px solid #fff;";

		}
	});
	$("#cxprequiredVisor").change(function() {
		console.log('asdahsdas');
		console.log(this.checked);
		if (this.checked) {

			document.getElementById("cxp-formV").style.height = "500px";
			document.getElementById("cxp-formV").style.border = "1px solid #ccc;";
			var scroll = $('#modal_visor_solicitud_aut');
			scroll.animate({
				scrollTop: scroll.prop("scrollHeight") + 50
			});

		} else {
				$('#proveedorAV').val(''); // Select the option with a value of '1'
				$('#proveedorAV').trigger('change');
				$('#tipoFCXPV').val(""); // Select the option with a value of '1'
				$('#tipoFCXPV').trigger('change');
				$('#idEmpresaV').val(""); // Select the option with a value of '1'
				$('#idEmpresaV').trigger('change');
				
				$("#cantidadV").val("");
				$("#rfcV").val("");
				$("#clabeV").val("");
				$("#emailcxpfV").val("");
			
			document.getElementById("cxp-formV").style.height = "0px";
			document.getElementById("cxp-formV").style.border = "1px solid #fff;";

		}
	});

	$(document).on("click", ".abrir_nueva_solicitud", function() {
		// resear_formulario();
		// recargar_provedores();
		link_post = "Solicitudes_autorizacion/guardar_solicitud";
		$("#modal_formulario_solicitud_aut").modal({
			backdrop: 'static',
			keyboard: false
		});
	});

	$("#recargar_formulario_solicitud").click(function() {
		resear_formulario();
		recargar_provedores();
	});

	$("#proveedorA").change(function() {
		var infoProv = proveedoresList.find((element) => element.idproveedor == $(this).val());
		$("#rfc").val(infoProv.rfc);
		$("#clabe").val(infoProv.cuenta);
		$("#emailcxpf").val(infoProv.email);
		$("#idbanco").val(infoProv.idbanco);
	});

	$("#proveedorAV").change(function() {
		var infoProvS = proveedoresList.find((element) => element.idproveedor == $(this).val());
		if($(this).val()!=null){
			$("#rfcV").val(infoProvS.rfc);
			$("#clabeV").val(infoProvS.cuenta);
			$("#emailcxpfV").val(infoProvS.email);
			$("#idbancoV").val(infoProvS.idbanco);
		}
		// 

		
		
	});

	var justificacion_globla = "";

	var table_autorizar;

	$("#tabla_autorizaciones").ready(function() {

		$('#tabla_autorizaciones').on('xhr.dt', function(e, settings, json, xhr) {
			table_autorizar.button(1).enable(parseInt(json.por_autorizar) > 0);

			var total = 0;

			$.each(json.data, function(i, v) {
				total += parseFloat(v.cantidad);
			});

			var to = formatMoney(total);
			$("#myText_1").html("$" + to);
			/*
			document.getElementById("myText_1").value = to;
			*/
		});

		$('#tabla_autorizaciones thead tr:eq(0) th').each(function(i) {
			if (i > 0 && i < $('#tabla_autorizaciones thead tr:eq(0) th').length - 1) {
				var title = $(this).text();
				$(this).html('<input type="text" style="font-size: .9em; width: 100%;" placeholder="' + title + '" />');

				$('textSearch').on('keyup change', function() {
					if (table_autorizar.column(i).search() !== this.value) {
						table_autorizar
							.column(i)
							.search(this.value)
							.draw();
						var total = 0;
						var index = table_autorizar.rows({
							selected: true,
							search: 'applied'
						}).indexes();
						var data = table_autorizar.rows(index).data();
						$.each(data, function(i, v) {
							total += parseFloat(v.cantidad);
						});
						var to1 = formatMoney(total);
						$("#myText_1").html("$" + to1);
					}
				});
			}
		});

		$("#pbuscar").change(function() {
			table_autorizar.search($('#search').val(), false, true).draw();

			table_autorizar.ajax.reload();
		});

		// $('#pbuscar').on( 'keyup', function () {
		// 	table_autorizar.search( this.value ).draw();
		// } );
		// $('#search').keyup(function(){
		// 	table_autorizar.search( $(this).val() ).draw();
		// 	})

		$('#search').keyup(function() {
			table_autorizar.search($('#search').val(), false, true).draw();
		});

		// $("#tabla_autorizaciones").DataTable().rows().every(function() {
		//     var tr = $(this.node());
		//     this.child(format(tr.data('child-value'))).show();
		//     tr.addClass('shown');
		// });

		// $('#fecInicial').change(function() {
		//     table_proceso.draw();
		//     var total = 0;
		//     var index = table_proceso.rows({
		//         selected: true,
		//         search: 'applied'
		//     }).indexes();
		//     var data = table_proceso.rows(index).data();
		//     $.each(data, function(i, v) {
		//         total += parseFloat(v.cantidad);
		//     });
		//     var to1 = formatMoney(total);
		//     document.getElementById("myText_2").value = to1;

		// });

		// $('#fecFinal').change(function() {
		//     table_proceso.draw();
		//     var total = 0;
		//     var index = table_proceso.rows({
		//         selected: true,
		//         search: 'applied'
		//     }).indexes();
		//     var data = table_proceso.rows(index).data();
		//     $.each(data, function(i, v) {
		//         total += parseFloat(v.cantidad);
		//     });
		//     var to1 = formatMoney(total);
		//     document.getElementById("myText_2").value = to1;
		// });

		// $('#tabla_autorizaciones tbody').on('click', 'td.details-control', function() {
		//     var tr = $(this).closest('tr');
		//     var row = table_autorizar.row(tr);

		//     if (row.child.isShown()) {
		//         row.child.hide();
		//         tr.removeClass('shown');
		//         $(this).parent().find('.animacion').removeClass("fa-caret-down").addClass("fa-caret-right");
		//     } else {

		//         var informacion_adicional = '<table class="table text-justify">' +
		//             '<tr>' +
		//             '<td><strong>JUSTIFICACIÓN: </strong>' + row.data().justificacion + '</td>' +
		//             '</tr>' +
		//             '</table>';

		//         row.child(informacion_adicional).show();
		//         tr.addClass('shown');
		//         $(this).parent().find('.animacion').removeClass("fa-caret-right").addClass("fa-caret-down");
		//     }
		// });

		// $('#tabla_autorizaciones').on("click", ".editar_factura", function() {

		//     var tr = $(this).closest('tr');
		//     var row = table_autorizar.row(tr);

		//     link_post = "Solicitante/editar_solicitud";

		//     //var limite_edicion = row.data().idetapa > 1 ? 2 : 1;
		//     var ideditar = $(this).val();

		//     $.post(url + "Solicitante/informacion_solicitud", {
		//         idsolicitud: ideditar
		//     }).done(function(data) {

		//         data = JSON.parse(data);
		//         //alert(JSON.stringify(data));
		//         if (data.resultado) {

		//             idsolicitud = ideditar;

		//             if (data.info_solicitud[0].programado != 'NULL' && data.info_solicitud[0].programado != null) {




		//                 resear_formulario_programado();
		//                 recargar_provedores();

		//                 setTimeout(() => {
		//                     funcion2();
		//                 }, 5000)


		//                 function funcion2() {

		//                     $("#fechapr").val(data.info_solicitud[0].fecelab);
		//                     $("#fecha_finalpr").val(data.info_solicitud[0].fecha_fin);
		//                     $("#totalpr").val(data.info_solicitud[0].cantidad);
		//                     $("#monedapr").html("");
		//                     $("#monedapr").append('<option value="' + data.info_solicitud[0].moneda + '" data-value="' + data.info_solicitud[0].moneda + '">' + data.info_solicitud[0].moneda + '</option>');
		//                     $("#empresapr option[data-value='" + data.info_solicitud[0].rfc_empresas + "']").prop("selected", true);
		//                     // $("#proveedor_programado option[data-value='"+data.info_solicitud[0].idProveedor+"']").prop("selected", true);
		//                     // $("input[name='referencia_pagopr']").val( data.info_solicitud[0].ref_bancaria );
		//                     $("#referencia_pagopr").val(data.info_solicitud[0].ref_bancaria);
		//                     $("#solobspr").val(data.info_solicitud[0].justificacion);
		//                     $("#orden_compra").val(data.info_solicitud[0].orden_compra);
		//                     $("#crecibo").val(data.info_solicitud[0].crecibo);
		//                     justificacion_globla = data.info_solicitud[0].justificacion
		//                     $("#proyectopr option[value='" + data.info_solicitud[0].proyecto + "']").prop("selected", true).trigger('change');

		//                     $("#forma_pagopr option[value='" + data.info_solicitud[0].metoPago + "']").prop("selected", true);
		//                     $("#metodo_pago option[value='" + data.info_solicitud[0].programado + "']").prop("selected", true);
		//                     $("#proveedor_programado option[value='" + data.info_solicitud[0].idProveedor + "']").prop("selected", true);
		//                     $("#proveedor_programado").val(data.info_solicitud[0].idProveedor);
		//                     uno = data.info_solicitud[0].nombre_proveedor;
		//                     dos = data.info_solicitud[0].idProveedor;
		//                     $('#proveedor_programado').select2();
		//                     $("#modal_solicitud_programado").modal({
		//                         backdrop: 'static',
		//                         keyboard: false
		//                     });
		//                 }



		//             } else {
		//                 resear_formulario();

		//                 if (data.xml) {
		//                     cargar_info_xml(data.xml);
		//                     $("#fecha").prop('disabled', true);
		//                     $("#folio").prop('disabled', true);

		//                     $("#empresa").prop('disabled', true);
		//                     $("#proveedor").prop('disabled', true);
		//                     //$("#moneda").prop('disabled', true );

		//                     //$("#forma_pago").prop('disabled', true );
		//                     $("#total").prop('disabled', true);
		//                     $("input[type=radio][name=servicio1]").prop('disabled', true);
		//                     $("#tentra_factura").prop('disabled', true);
		//                     //$("input[name='tentra_factura']").prop('disabled', true );
		//                 } else {
		//                     $("#fecha").val(data.info_solicitud[0].fecelab);
		//                     $("#folio").val(data.info_solicitud[0].folio);

		//                     $("#subtotal").val("");
		//                     $("#iva").val("");
		//                     $("#total").val(data.info_solicitud[0].cantidad);
		//                     $("#metpag").val("");
		//                     $("#moneda").val(data.info_solicitud[0].moneda);

		//                     //$("input[name='caja_chica']").prop('disabled', ( data.info_solicitud[0].cantidad > 2000 ? true : false ) );x
		//                     $("input[name='tentra_factura']").prop('disabled', !depto_excep.includes(data.info_solicitud[0].nomdepto));
		//                     $("input[type=radio][name=servicio1]").prop('disabled', false);

		//                 }

		//                 $("#descr").append(data.info_solicitud[0].descripcion);
		//                 $("input[name='referencia_pago']").val(data.info_solicitud[0].ref_bancaria);
		//                 $("input[name='tentra_factura']").prop("checked", (data.info_solicitud[0].tendrafac == 1 ? true : false));
		//                 $("input[name='prioridad']").prop("checked", (data.info_solicitud[0].prioridad ? true : false));

		//                 $("#solobs").val(data.info_solicitud[0].justificacion);
		//                 $("#orden_compra").val(data.info_solicitud[0].orden_compra);
		//                 $("#crecibo").val(data.info_solicitud[0].crecibo);
		//                 $("#requisicion").val(data.info_solicitud[0].requisicion);

		//                 $("#etapa").val(data.info_solicitud[0].etapa).trigger('change');
		//                 $("#condominio").val(data.info_solicitud[0].condominio).trigger('change');

		//                 justificacion_globla = data.info_solicitud[0].justificacion

		//                 $("#proyecto option[value='" + data.info_solicitud[0].proyecto + "']").prop("selected", true).trigger('change');
		//                 $("#homoclave option[value='" + data.info_solicitud[0].homoclave + "']").prop("selected", true);
		//                 $("#forma_pago").val(data.info_solicitud[0].metoPago);

		//                 /**********************COLOCAMOS EL PROVEEDOR SELECCIONADO*****************************/

		//                 $(".lstado_tipo_autorizacion").html('');
		//                 $(".lstado_tipo_autorizacion").append('<option value="">Seleccione una opción</option>');

		//                 var listado_proveedores = [];
		//                 $.each(data.proveedores_todos, function(i, v) {
		//                     //ENCASO QUE SEA PAGO A PROVEEDOR
		//                     if ((data.info_solicitud[0].caja_chica == 0 || data.info_solicitud[0].caja_chica == null) && !(v.nombrep.includes('GASTO NO DEDUCIBLE')) && !(v.nombrep.includes('INVOICE'))) {
		//                         listado_proveedores.push({
		//                             value: v.idproveedor,
		//                             excp: v.excp,
		//                             label: v.nombrep + " - " + v.nom_banco,
		//                             rfc: v.rfc,
		//                             tinsumo: v.tinsumo
		//                         });
		//                     }

		//                     //EN CASO QUE SEA UN COMPROBANTE DE TDC Y NO TENGA FACTURA
		//                     if (data.xml == null && data.info_solicitud[0].caja_chica == 2 && (v.nombrep.includes('COMPROBANTE NO FISCAL') || v.nombrep.includes('INVOICE') || (v.nombrep.includes('GASTO NO DEDUCIBLE') && depto == 'ADMINISTRACION'))) {
		//                         listado_proveedores.push({
		//                             value: v.idproveedor,
		//                             excp: v.excp,
		//                             label: v.nombrep + " - " + v.nom_banco,
		//                             rfc: v.rfc,
		//                             tinsumo: v.tinsumo
		//                         });
		//                     }

		//                     //EN CASO QUE CUENTE CON XML EL REGISTRO
		//                     if (data.xml && data.info_solicitud[0].idProveedor == v.idproveedor) {
		//                         listado_proveedores.push({
		//                             value: v.idproveedor,
		//                             excp: v.excp,
		//                             label: v.nombrep + " - " + v.nom_banco,
		//                             rfc: v.rfc,
		//                             tinsumo: v.tinsumo
		//                         });
		//                     }

		//                     //EN CASO QUE SEA UN GASTO DE CAJA CHICA Y NO CUENTE CON CFDI
		//                     if (data.xml == null && data.info_solicitud[0].caja_chica == 1)
		//                         listado_proveedores.push({
		//                             value: v.idproveedor,
		//                             excp: v.excp,
		//                             label: v.nombrep + " - " + v.nom_banco,
		//                             rfc: v.rfc,
		//                             tinsumo: v.tinsumo
		//                         });
		//                 });

		//                 AutocompleteProveedor(listado_proveedores);

		//                 $("#proveedor option[value='" + data.info_solicitud[0].idProveedor + "']").prop("selected", true);
		//                 $("#idproveedor").val(data.info_solicitud[0].idProveedor);
		//                 /************************************************************************************/

		//                 /**REINICIAMOS TODAS LAS EMPRESAS Y SELECCIONAMOS LA PREDETERMINADA**/
		//                 //REINICIAMOS EL CATALAGO DE EMPRESAS
		//                 $("#empresa").html('');
		//                 $("#empresa").append('<option value="">Seleccione una opción</option>');


		//                 $.each(data.empresas, function(i, v) {
		//                     $("#empresa").append('<option value="' + v.idempresa + '" data-value="' + v.rfc + '">' + v.nombre + '</option>');
		//                 });

		//                 //SELECCIONAMOS LA EMPRESA DETERMINADA EN EL REGISTRO
		//                 $("#empresa option[data-value='" + data.info_solicitud[0].rfc_empresas + "']").prop("selected", true);

		//                 //RETIRAMOS LAS EMPRESAS QUE NO FUERON SELECCIONADAS EN CASO DE TENER UN XML
		//                 if (data.xml) {
		//                     $("#empresa option").each(function() {
		//                         if ($(this).data('value') != data.info_solicitud[0].rfc_empresas) {
		//                             $(this).remove();
		//                         }
		//                     });
		//                 }
		//                 /************************************************************************************/

		//                 /**DETERMINAMOS QUE TIPO DE GASTO ES**/
		//                 if (data.info_solicitud[0].caja_chica && data.info_solicitud[0].caja_chica > 0) {
		//                     //PROCEDIMIENTOS PARA HABILITAR EL RESPONSABLE DE CAJA CHICA
		//                     $("#responsable_cc").html('<option value="">Selecciones una opción</option>');
		//                     if (data.info_solicitud[0].caja_chica == 1 || data.info_solicitud[0].caja_chica == '1') {
		//                         $.each(data.listado_responsable, function(i, v) {
		//                             $("#responsable_cc").append('<option value="' + v.idusuario + '">' + v.nombres + " " + v.apellidos + '</option>');
		//                         });

		//                         $("#insumo").prop("disabled", false);
		//                         $("#insumo").val(data.info_solicitud[0].servicio).trigger('change');

		//                         $("input[type=hidden][name='servicio']").val('9').prop('disabled', false);
		//                         $(".caja_chica_label").prop('checked', true);

		//                     }
		//                     if (data.info_solicitud[0].caja_chica == 2 || data.info_solicitud[0].caja_chica == '2') {
		//                         $.each(data.listado_responsable, function(i, v) {
		//                             $("#responsable_cc").append('<option value="' + v.idusuario + '" data-rfcempresa="' + v.rfc + '" data-tempresa="' + v.idempresa + '">' + v.nresponsable + '</option>');
		//                         });

		//                         $("#insumo").prop("disabled", true);
		//                         $("input[type=radio][name=servicio1][value='11']").prop("checked", true);
		//                         $("input[type=hidden][name='servicio']").val('11').prop('disabled', false);
		//                     }

		//                     $("#responsable_cc").prop('disabled', false).val(data.info_solicitud[0].idResponsable);
		//                 } else {
		//                     $("input[type=hidden][name='servicio']").val('').prop('disabled', true);
		//                     if (data.info_solicitud[0].metoPago == "INTERCAMBIO")
		//                         $("input[type=radio][name=servicio1][value='10']").prop("checked", true).change();
		//                     else {
		//                         $("input[type=radio][name=servicio1][value='1']").prop("checked", (data.info_solicitud[0].servicio == 1 ? true : false));
		//                         $("input[type=radio][name=servicio1][value='0']").prop("checked", (data.info_solicitud[0].servicio == null || data.info_solicitud[0].servicio == 0 ? true : false));
		//                     }
		//                 }
		//                 /************************************************************************************/

		//                 $("#listado_proyectos1").val(data.info_solicitud[0].proyecto).change();
		//                 uno = data.info_solicitud[0].nombre_proveedor;
		//                 dos = data.info_solicitud[0].idProveedor;
		//                 //SOLO PARA CONSTRUCCION
		//                 /*AL CARGAR LA INFO DE LA SOLICITUD SE EJECUTA Y VERIFICA SI HAY ALGUN CONTRATO PARA LA SOLICITITUD*/

		//                 if ((depto == 'CONSTRUCCION' || depto == 'JARDINERIA') && data.info_contrato.length > 0) {
		//                     $("#cproveedor").html('<option value="" data-restante="">Seleccione una opción</option>');

		//                     $.each(data.contratos_prov, function(i, v) {
		//                         if (v.idproveedor == data.info_solicitud[0].idProveedor)
		//                             $("#cproveedor").append('<option value="' + v.idcontrato + '" data-restante="' + parseFloat(v.cantidad - v.consumido).toFixed(2) + '">' + v.nproveedor + ' ' + v.ncontrato + ' DISP: $ ' + formatMoney(v.cantidad - v.consumido) + '</option>');
		//                     });

		//                     $("#cproveedor").prop("required", ($('#cproveedor > option').length > 1));
		//                     $("#cproveedor").prop("disabled", !($('#cproveedor > option').length > 1));

		//                     if (!$("#cproveedor").prop("disabled")) {
		//                         $("#cproveedor option[value='" + data.info_contrato[0].idcontrato + "']").prop("selected", true);
		//                     }
		//                 }

		//                 $("#_aut_aut").modal({
		//                     backdrop: 'static',
		//                     keyboard: false
		//                 });

		//             }
		//         } else {
		//             alert("Algo salio mal, recargue su página.")
		//         }
		//     });
		// });

		// $('#tabla_autorizaciones').on("click", ".borrar_solicitud", function() {
		//     $('#modal-alert').modal('toggle');
		//     $('.datos_modal_alert').val($(this).val());
		// });


		var selectedRowData;
		var $input = $('<input type="text" class="multiple_emails-input text-left" />');
		var $inputVisor = $('<input type="text" class="multiple_emails-input text-left" />');


		$(function() {
			//To render the input device to multiple email input using BootStrap icon
			$('#example_emailBS').multiple_emails({
				position: "bottom"
			});
			//OR $('#example_emailBS').multiple_emails("Bootstrap");

			//Shows the value of the input device, which is in JSON format
			$('#current_emailsBS').text($('#example_emailBS').val());
			$('#example_emailBS').change(function() {
				$('#current_emailsBS').text($(this).val());
			});
		});

		$(function() {
			//To render the input device to multiple email input using BootStrap icon
			$('#example_emailBSVisor').multiple_emails_visor({
				position: "bottom"
			});
			//OR 
			// $('#example_emailBSVisor').multiple_emails("Bootstrap");

			//Shows the value of the input device, which is in JSON format
			$('#current_emailsBSVisor').text($('#example_emailBSVisor').val());
			$('#example_emailBSVisor').change(function() {
				$('#current_emailsBSVisor').text($(this).val());
			});
		});



		// //Plug-in function for the bootstrap version of the multiple email
		// $(function() {
		// 	//To render the input device to multiple email input using a simple hyperlink text
		// 	$('#example_emailB').multiple_emails({theme: "Basic"});

		// 	//Shows the value of the input device, which is in JSON format
		// 	$('#current_emailsB').text($('#example_emailB').val());
		// 	$('#example_emailB').change( function(){
		// 		$('#current_emailsB').text($(this).val());
		// 	});
		// });

		(function($) {

			$.fn.multiple_emails = function(options) {

				// Default options
				var defaults = {
					checkDupEmail: true,
					theme: "Bootstrap",
					position: "top"
				};

				// Merge send options with defaults
				var settings = $.extend({}, defaults, options);

				var deleteIconHTML = "";

				if (settings.theme.toLowerCase() == "Bootstrap".toLowerCase()) {
					deleteIconHTML = '<a href="#" class="multiple_emails-close" title="Remove"><span class="glyphicon glyphicon-trash"></span></a>';
				} else if (settings.theme.toLowerCase() == "SemanticUI".toLowerCase() || settings.theme.toLowerCase() == "Semantic-UI".toLowerCase() || settings.theme.toLowerCase() == "Semantic UI".toLowerCase()) {
					deleteIconHTML = '<a href="#" class="multiple_emails-close" title="Remove"><i class="remove icon"></i></a>';
				} else if (settings.theme.toLowerCase() == "Basic".toLowerCase()) {
					//Default which you should use if you don't use Bootstrap, SemanticUI, or other CSS frameworks
					deleteIconHTML = '<a href="#" class="multiple_emails-close" title="Remove"><i class="basicdeleteicon">Remove</i></a>';
				}

				return this.each(function() {
					//$orig refers to the input HTML node
					var $orig = $(this);

					var $list = $('<ul class="multiple_emails-ul" />'); // create html elements - list of email addresses as unordered list

					if ($(this).val() != '' && IsJsonString($(this).val())) {
						$.each(jQuery.parseJSON($(this).val()), function(index, val) {
							$list.append($('<li class="multiple_emails-email"><span class="email_name" data-email="' + val.toLowerCase() + '">' + val + '</span></li>')
								.prepend($(deleteIconHTML)
									.click(function(e) {
										$(this).parent().remove();
										refresh_emails();
										e.preventDefault();
									})
								)
							);
						});
					}



					//  

					var $container = $('<div class="multiple_emails-container" />').click(function() {
						$input.focus();
					}); // container div

					// insert elements into DOM
					if (settings.position.toLowerCase() === "top") {
						$container.append($list).append($input).insertAfter($(this));
					} else {
						$container.append($input).append($list).insertBefore($(this));
					}

					$input.on('keyup', function(e) { // input
						$(this).removeClass('multiple_emails-error');

						var input_length = $(this).val().length;

						var keynum;

						if (window.event) { // IE					
							keynum = e.keyCode;
						} else if (e.which) { // Netscape/Firefox/Opera					
							keynum = e.which;
						}






						//  if (selectedRowData.length > 0) {

						//  $.each(selectedRowData, function(i, item) {
						// 	 display_email_from_string(item.email, settings.checkDupEmail);
						//  });


						//  // selectedRowData = selectedRowData[{}]


						// }



						//if(event.which == 8 && input_length == 0) { $list.find('li').last().remove(); } //Removes last item on backspace with no input

						// Supported key press is tab, enter, space or comma, there is no support for semi-colon since the keyCode differs in various browsers

						if (keynum == 9 || keynum == 32 || keynum == 188) {
							display_email($(this), settings.checkDupEmail);
						} else if (keynum == 13) {


							display_email($(this), settings.checkDupEmail);

							//Prevents enter key default
							//This is to prevent the form from submitting with  the submit button
							//when you press enter in the email textbox
							e.preventDefault();
						}



					}).on('blur', function(event) {

						if ($(this).val() != '') {
							display_email($(this), settings.checkDupEmail);
						}

					});

					/*
					t is the text input device.
					Value of the input could be a long line of copy-pasted emails, not just a single email.
					As such, the string is tokenized, with each token validated individually.
					
					If the dupEmailCheck variable is set to true, scans for duplicate emails, and invalidates input if found.
					Otherwise allows emails to have duplicated values if false.
					*/

					function display_email(t, dupEmailCheck) {
						//Remove space, comma and semi-colon from beginning and end of string
						//Does not remove inside the string as the email will need to be tokenized using space, comma and semi-colon
						var arr = t.val().trim().replace(/^,|,$/g, '').replace(/^;|;$/g, '');
						//Remove the double quote
						arr = arr.replace(/"/g, "");
						//Split the string into an array, with the space, comma, and semi-colon as the separator
						arr = arr.split(/[\s,;]+/);

						var errorEmails = new Array(); //New array to contain the errors

						var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);

						for (var i = 0; i < arr.length; i++) {
							//Check if the email is already added, only if dupEmailCheck is set to true
							if (dupEmailCheck === true && $orig.val().indexOf(arr[i]) != -1) {
								if (arr[i] && arr[i].length > 0) {
									new function() {
										var existingElement = $list.find('.email_name[data-email=' + arr[i].toLowerCase().replace('.', '\\.').replace('@', '\\@') + ']');
										existingElement.css('font-weight', 'bold');
										setTimeout(function() {
											existingElement.css('font-weight', '');
										}, 1500);
									}(); // Use a IIFE function to create a new scope so existingElement won't be overriden
								}
							} else if (pattern.test(arr[i]) == true) {
								$list.append($('<li class="multiple_emails-email"><span class="email_name" data-email="' + arr[i].toLowerCase() + '">' + arr[i] + '</span></li>')
									.prepend($(deleteIconHTML)
										.click(function(e) {
											$(this).parent().remove();
											refresh_emails();
											e.preventDefault();
										})
									)
								);
							} else
								errorEmails.push(arr[i]);
						}

						if (errorEmails.length > 0)
							t.val(errorEmails.join("; ")).addClass('multiple_emails-error');
						else
							t.val("");
						refresh_emails();
					}

					function display_email_from_string(string, dupEmailCheck) {

						//Remove space, comma and semi-colon from beginning and end of string
						//Does not remove inside the string as the email will need to be tokenized using space, comma and semi-colon
						var arr = string.trim().replace(/^,|,$/g, '').replace(/^;|;$/g, '');
						//Remove the double quote
						arr = arr.replace(/"/g, "");
						//Split the string into an array, with the space, comma, and semi-colon as the separator
						arr = arr.split(/[\s,;]+/);

						var errorEmails = new Array(); //New array to contain the errors

						var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);

						for (var i = 0; i < arr.length; i++) {
							//Check if the email is already added, only if dupEmailCheck is set to true
							if (dupEmailCheck === true && $orig.val().indexOf(arr[i]) != -1) {
								console.dir($list)
								if (arr[i] && arr[i].length > 0) {
									new function() {
										var existingElement = $list.find('.email_name[data-email=' + arr[i].toLowerCase().replace('.', '\\.').replace('@', '\\@') + ']');
										existingElement.css('font-weight', 'bold');
										setTimeout(function() {
											existingElement.css('font-weight', '');
										}, 1500);
									}(); // Use a IIFE function to create a new scope so existingElement won't be overriden
								}
								console.dir($list)

							} else if (pattern.test(arr[i]) == true) {
								$list.append($('<li class="multiple_emails-email"><span class="email_name" data-email="' + arr[i].toLowerCase() + '">' + arr[i] + '</span></li>')
									.prepend($(deleteIconHTML)
										.click(function(e) {
											$(this).parent().remove();
											refresh_emails();
											e.preventDefault();
										})
									)
								);
							} else
								errorEmails.push(arr[i]);
						}

						//  if(errorEmails.length > 0)
						// 	//  t.val(errorEmails.join("; ")).addClass('multiple_emails-error');
						//  else
						//  t.val("");

						refresh_emails();

					}

					function refresh_emails() {
						var emails = new Array();
						var container = $orig.siblings('.multiple_emails-container');
						container.find('.multiple_emails-email span.email_name').each(function() {
							emails.push($(this).html());
						});
						$orig.val(JSON.stringify(emails)).trigger('change');
					}

					function IsJsonString(str) {

						try {
							JSON.parse(str);
						} catch (e) {
							return false;
						}
						return true;

					}

					return $(this).hide();

				});

			};

		})(jQuery);

		(function($) {

			$.fn.multiple_emails_visor = function(options) {

				// Default options
				var defaults = {
					checkDupEmail: true,
					theme: "Bootstrap",
					position: "top"
				};

				// Merge send options with defaults
				var settings = $.extend({}, defaults, options);

				var deleteIconHTML = "";

				if (settings.theme.toLowerCase() == "Bootstrap".toLowerCase()) {
					deleteIconHTML = '<a href="#" class="multiple_emails-close" title="Remove"><span class="glyphicon glyphicon-trash"></span></a>';
				} else if (settings.theme.toLowerCase() == "SemanticUI".toLowerCase() || settings.theme.toLowerCase() == "Semantic-UI".toLowerCase() || settings.theme.toLowerCase() == "Semantic UI".toLowerCase()) {
					deleteIconHTML = '<a href="#" class="multiple_emails-close" title="Remove"><i class="remove icon"></i></a>';
				} else if (settings.theme.toLowerCase() == "Basic".toLowerCase()) {
					//Default which you should use if you don't use Bootstrap, SemanticUI, or other CSS frameworks
					deleteIconHTML = '<a href="#" class="multiple_emails-close" title="Remove"><i class="basicdeleteicon">Remove</i></a>';
				}

				return this.each(function() {
					//$orig refers to the input HTML node
					var $orig = $(this);

					var $listVisor = $('<ul class="multiple_emails-ul" />'); // create html elements - list of email addresses as unordered list

					if ($(this).val() != '' && IsJsonString($(this).val())) {
						$.each(jQuery.parseJSON($(this).val()), function(index, val) {
							$listVisor.append($('<li class="multiple_emails-email"><span class="email_name" data-email="' + val.toLowerCase() + '">' + val + '</span></li>')
								.prepend($(deleteIconHTML)
									.click(function(e) {
										$(this).parent().remove();
										refresh_emails();
										e.preventDefault();
									})
								)
							);
						});
					}



					//  

					var $container = $('<div class="multiple_emails-container" />').click(function() {
						$inputVisor.focus();
					}); // container div

					// insert elements into DOM
					if (settings.position.toLowerCase() === "top") {
						$container.append($listVisor).append($inputVisor).insertAfter($(this));
					} else {
						$container.append($inputVisor).append($listVisor).insertBefore($(this));
					}

					$inputVisor.on('keyup', function(e) { // input
						console.dir("input happening")

						$(this).removeClass('multiple_emails-error');

						var input_length = $(this).val().length;

						var keynum;

						if (window.event) { // IE					
							keynum = e.keyCode;
						} else if (e.which) { // Netscape/Firefox/Opera					
							keynum = e.which;
						}







						//if(event.which == 8 && input_length == 0) { $listVisor.find('li').last().remove(); } //Removes last item on backspace with no input

						// Supported key press is tab, enter, space or comma, there is no support for semi-colon since the keyCode differs in various browsers

						console.dir(keynum)

						if (keynum == 9 || keynum == 32 || keynum == 188) {
							console.log("key 2")
							display_email($(this), settings.checkDupEmail);
							//   e.preventDefault();

						} else if (keynum == 13) {

							console.log("key 13")

							display_email($(this), settings.checkDupEmail);

							//Prevents enter key default
							//This is to prevent the form from submitting with  the submit button
							//when you press enter in the email textbox
							//   e.preventDefault();
						}

						if (selectedRowData.length > 0) {

							$.each(selectedRowData, function(i, item) {
								// var defaultss = {
								// 	 checkDupEmail: false,
								// 	 theme: "Bootstrap",
								// 	 position: "top"
								//  };
								//  var settingss = $.extend( {}, defaultss, options );
								display_email_from_string(item.email, settings.checkDupEmail);
							});

						}



					}).on('blur', function(event) {
						console.log("blur 3")

						if ($(this).val() != '') {
							display_email($(this), settings.checkDupEmail);
						}

					});

					/*
					t is the text input device.
					Value of the input could be a long line of copy-pasted emails, not just a single email.
					As such, the string is tokenized, with each token validated individually.
					
					If the dupEmailCheck variable is set to true, scans for duplicate emails, and invalidates input if found.
					Otherwise allows emails to have duplicated values if false.
					*/

					function display_email(t, dupEmailCheck) {
						//Remove space, comma and semi-colon from beginning and end of string
						//Does not remove inside the string as the email will need to be tokenized using space, comma and semi-colon
						var arr = t.val().trim().replace(/^,|,$/g, '').replace(/^;|;$/g, '');
						//Remove the double quote
						arr = arr.replace(/"/g, "");
						//Split the string into an array, with the space, comma, and semi-colon as the separator
						arr = arr.split(/[\s,;]+/);

						var errorEmails = new Array(); //New array to contain the errors

						var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);

						for (var i = 0; i < arr.length; i++) {
							//Check if the email is already added, only if dupEmailCheck is set to true
							if (dupEmailCheck === true && $orig.val().indexOf(arr[i]) != -1) {
								if (arr[i] && arr[i].length > 0) {
									console.log($listVisor)
									new function() {

										each$listVisor
										var existingElement = $listVisor.find('.email_name[data-email=' + arr[i].toLowerCase().replace('.', '\\.').replace('@', '\\@') + ']');
										existingElement.css('font-weight', 'bold');
										setTimeout(function() {
											existingElement.css('font-weight', '');
										}, 1500);
									}(); // Use a IIFE function to create a new scope so existingElement won't be overriden

									console.log($listVisor)

								}
							} else if (pattern.test(arr[i]) == true) {
								$listVisor.append($('<li class="multiple_emails-email"><span class="email_name" data-email="' + arr[i].toLowerCase() + '">' + arr[i] + '</span></li>')
									.prepend($(deleteIconHTML)
										.click(function(e) {
											$(this).parent().remove();
											refresh_emails();
											e.preventDefault();
										})
									)
								);
							} else
								errorEmails.push(arr[i]);
						}

						if (errorEmails.length > 0)
							t.val(errorEmails.join("; ")).addClass('multiple_emails-error');
						else
							t.val("");
						refresh_emails();
					}

					function display_email_from_string(string, dupEmailCheck) {

						//Remove space, comma and semi-colon from beginning and end of string
						//Does not remove inside the string as the email will need to be tokenized using space, comma and semi-colon
						var arr = string.trim().replace(/^,|,$/g, '').replace(/^;|;$/g, '');
						//Remove the double quote
						arr = arr.replace(/"/g, "");
						//Split the string into an array, with the space, comma, and semi-colon as the separator
						arr = arr.split(/[\s,;]+/);

						var errorEmails = new Array(); //New array to contain the errors

						var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
						console.log("before for 1")

						for (var i = 0; i < arr.length; i++) {

							console.log("in for")

							//Check if the email is already added, only if dupEmailCheck is set to true
							if (dupEmailCheck === true && $orig.val().indexOf(arr[i]) != -1) {
								console.log("in if")

								if (arr[i] && arr[i].length > 0) {
									console.log("listVisor")

									console.log($listVisor)

									new function() {
										console.log("comprobando")
										console.log("$llistVisorist")
										console.log(".email_name[data-email=" + arr[i].toLowerCase().replace('.', '\\.').replace('@', '\\@') + ']')

										var existingElement = $listVisor.find(".email_name[data-email='" + arr[i].toLowerCase().replace('.', '\\.').replace('@', '\\@') + "']");
										existingElement.css('font-weight', 'bold');
										setTimeout(function() {
											existingElement.css('font-weight', '');
										}, 1500);
									}(); // Use a IIFE function to create a new scope so existingElement won't be overriden
									console.log($listVisor)

								}
							} else if (pattern.test(arr[i]) == true) {
								$listVisor.append($('<li class="multiple_emails-email"><span class="email_name" data-email="' + arr[i].toLowerCase() + '">' + arr[i] + '</span></li>')
									.prepend($(deleteIconHTML)
										.click(function(e) {


											$(this).parent().remove();
											refresh_emails();
											e.preventDefault();

										})
									)
								);
							} else
								errorEmails.push(arr[i]);
						}

						//  if(errorEmails.length > 0)
						// 	//  t.val(errorEmails.join("; ")).addClass('multiple_emails-error');
						//  else
						//  t.val("");

						refresh_emails();

					}

					function refresh_emails() {
						var emails = new Array();
						var container = $orig.siblings('.multiple_emails-container');
						container.find('.multiple_emails-email span.email_name').each(function() {
							emails.push($(this).html());
						});
						$orig.val(JSON.stringify(emails)).trigger('change');
					}

					function IsJsonString(str) {

						try {
							JSON.parse(str);
						} catch (e) {
							return false;
						}
						return true;

					}

					return $(this).hide();

				});

			};

		})(jQuery);

		$(".close").click(function() {
			console.log("cerrando modal")
			$(".multiple_emails-ul").empty()
		})
		$("#addCommentButton").click(function(event) {

			event.preventDefault(); // cancel default behavior

			var texto = $('#user-comment').val();
			var d = $.datepicker.formatDate('yy-m-d H:i:s', new Date());

			$('#comments-container').append(`<div class="row message-balloon own-messages">
										<label >TÚ</label>
								  		<p >${texto}</p>
								  		<span class="time-right">${d}</span>
										</div>`);
			var scroll = $('#chat-section');

			scroll.animate({
				scrollTop: scroll.prop("scrollHeight") + 50
			});

		});

		$('#user-comment').val('').change();

		function resetearFormularioVisor() {

			//resear_formulario();
			// $("#modal_visor_solicitud_aut").modal('toggle');
			// $("#tipoVisor").empty().append('<option value="" selected>Seleccione una opción</option>');

			$("#tbl_motivVisor tr>td").remove();
			$("#tbl_autVisor tr>td").remove();
			$("#tbl_documents_visor tr>td").remove();

			document.getElementById("tituloVisor").value = "";
			document.getElementById("descrVisor").value = "";
			document.getElementById("user-comment").value = "";
			// $("#involucradosVisor").empty().append('<option value="" selected>Seleccione una opción</option>');
			$("#autorizadosVisor").empty().append('<option value="" selected>Seleccione una opción</option>');
			document.getElementById("dropedFilesInputVisor").value = "";
			var $list = $('<ul class="multiple_emails-ul" />');

			table_autorizar.ajax.reload();
			const boxes = document.querySelectorAll('.message-balloon');

			boxes.forEach(box => {
				box.remove();
			});

		}

		$('#tabla_autorizaciones').on("click", ".verSolicitud", function(e) {
			resetearFormularioVisor()

			let data = table_autorizar.row(e.target.closest('tr')).data();

			selectedIdAutorizacion = $(this).val()
			$.post(url + "Solicitudes_autorizacion/getInvolucradosByAut", {
				idSolicitud: $(this).val(),
			}).done(function(data) {

				data = JSON.parse(data);

				$.each(data.data, function(i, item) {
					if (item.tipo == 1) {
						$('#tbodyInvVisor').append(`<tr id="R${item.idUsuario}">
                                    <td class="row-index" style="border: none;">
                                        <p class="w-100">${item.nombres+" "+item.apellidos+"-"+item.depto }&nbsp;</p>
                                        <input type="hidden" name="usuarioVisor[]" value="${item.idUsuario}" />
                                        <input type="hidden" name="textoVisor[]" value="${item.nombres+" "+item.apellidos+"-"+item.depto }" />
                                        <input type="hidden" name="tipoVisor[]" value="1" />
                                    </td>
                                    <td class="text-center" style="border: none;">
                                        <button class="btn btn-danger removeVisor btn-sm" id="${item.idUsuario}" type="button">Quitar</button>
                                    </td>
                                </tr>`);


					} else {
						$('#tbodyAutVisor').append(`<tr id="R${item.idUsuario}">
                                    <td class="row-index" style="border: none;">
                                        <p class="w-100">${item.nombres+" "+item.apellidos+"-"+item.depto }&nbsp;</p>
                                        <input type="hidden" name="usuarioAutVisor[]" value="${item.idUsuario}" />
                                        <input type="hidden" name="textoAutVisor[]" value="${item.nombres+" "+item.apellidos+"-"+item.depto }" />
                                        <input type="hidden" name="tipoAutVisor[]" value="1" />
                                    </td>
                                    <td class="text-center" style="border: none;">
                                        <button class="btn btn-danger removeAutVisor btn-sm" id="${item.idUsuario}" type="button">Quitar</button>
                                    </td>
                                </tr>`);

					}

				});

			});



			$.post(url + "Solicitudes_autorizacion/getEmailsByAut", {
				idSolicitud: $(this).val(),
			}).done(function(data) {

				data = JSON.parse(data);

				selectedRowData = data.data

				$inputVisor.keyup();

			});


			$.post(url + "Solicitudes_autorizacion/getDocumentsByAut", {

				idSolicitud: $(this).val()

			}).done(function(data) {

				// console.log("Documentos");

				data = JSON.parse(data);

				// console.log(data.data);

				$.each(data.data, function(i, item) {

					// console.log(item);

					// document.getElementById("error").style.display = "none";
					$("#tbl_documents_visor").show();
					var text = item.ruta;
					var option = document.createElement("option");
					myset.add(text);
					Array.from(myset);
					size = size + 1;

					// $('#btnsubformcolab').show();
					// if (size == 3) {
					// $("#add").attr('disabled', 'disabled');
					// }


					let descmot = "";

					$("#tbodyDocumentsVisor option[value=" + item.idDocumento + "]").each(function() {
						$(this).attr('hidden', 'hidden');
						descmot = $("#tbl_documents_visor").find("option:selected").attr("title");
					});

					$('#tbodyDocumentsVisor').append(`<tr id="R${item.idDocumento}">
										<td class="row-index" style="border: none;">
											<p class="w-100"> ${text}&nbsp;</p>
											<input type="hidden" name="usuarioDocVisor[]" value="${item.idDocumento}" />
											<input type="hidden" name="textoDocVisor[]" value="${text}" />
											<input type="hidden" name="tipoDocVisor[]" value="2" />
										</td>
										<td class="text-center" style="border: none;">
											<button class="btn btn-danger removeDocumentVisor btn-sm" id="${item.idDocumento}" type="button">Quitar de esta solicitud.</button>
										</td>
									</tr>`);

					$("#tbodyDocumentsVisor").val("").change();

				});


			});

			$.post(url + "Solicitudes_autorizacion/getCommentsByAut", {

				idSolicitud: $(this).val()

			}).done(function(data) {
				// console.log("Comentarios");
				// console.log(data);
				data = JSON.parse(data);
				// console.log(data.data);
				// console.log(data.data.length);
				if (data.data.length > 0) {
					$('#empty-comments-message').remove();
					$.each(data.data, function(i, item) {
						// console.log(item);
						var idUser = `<?= $this->session->userdata('inicio_sesion')['id'] ?>`;
						if (idUser != item.idUsuario) {

							$('#comments-container').append(`<div class="row message-balloon">
									<label >${item.Nombres + ' ' + item.Apellidos}</label>
									  <p>${item.descripcion}</p>
									  <span class="time-right">${item.fechaCreacion}</span>
								</div>`);
							// $("#comments-container").val("").change();
						} else {

							$('#comments-container').append(`<div class="row message-balloon own-messages">
									<label >TÚ</label>
									  <p>${item.descripcion}</p>
									  <span class="time-right">${item.fechaCreacion}</span>
									</div>`);

							// $("#comments-container").val("").change();

						}
						var scroll = $('#chat-section');

						scroll.animate({
							scrollTop: scroll.prop("scrollHeight") + 50
						});

					});
				} else {

					$('#comments-container').append(`<div id="empty-comments-message" class="row message-balloon" style="border-color:gray; background-color: #ccc; ">
										<label >Sin comentarios registrados.</label>
								  	
										</div>`);


				}




			});


			$("#tituloVisor").val(data["titulo"]);

			$date = new Date(data["fecha"])
			$.datepicker.setDefaults($.datepicker.regional["es"]);
			$("#fechaSol").text($.datepicker.formatDate('dd-mm-yy', new Date($date)));
			$("#modalTitleVisor").text("Solicitud de autorización numero #" + data["idAutorizacion"] + ":  " + data["titulo"]);
			$("#solicitanteSol").text(data["nombres"] + " " + data["apellidos"]);
			$('#tipoVisor').val(data["tipoSol"]); // Select the option with a value of '1'
			$('#tipoVisor').trigger('change');
			$("#descrVisor").val(data["descripcion_au"]);
			if (data["idEstatus"] == "3") {
				$("#btnGuardarSolVisor").attr('disabled', true);
			} else {
				$("#btnGuardarSolVisor").attr('disabled', false);
			}

			if (data["requiereDA"] == 1) {

				$("#darequiredVisor").attr('checked', true).trigger("change");

			}

			if (data["requiereDG"] == 1) {

				$("#dgrequiredVisor").attr('checked', true).trigger("change");

			}

			if (data["requiereSolicitudCXP"] == 1) {
				var infoProvV = proveedoresList.find((element) => element.idproveedor == data["idProveedor"]);
				$('#proveedorAV').val(data["idProveedor"]); // Select the option with a value of '1'
				$('#proveedorAV').trigger('change');
				$('#tipoFCXPV').val(data["idPago"]); // Select the option with a value of '1'
				$('#tipoFCXPV').trigger('change');
				$('#idEmpresaV').val(data["idEmpresa"]); // Select the option with a value of '1'
				$('#idEmpresaV').trigger('change');
				$("#cxprequiredVisor").attr('checked', true).trigger("change");
				$("#cantidadV").val(data["total"]);
				if(infoProvV){
					$("#rfcV").val(infoProvV.rfc);
					$("#clabeV").val(infoProvV.cuenta);
					$("#emailcxpfV").val(infoProvV.email);
				}
				
			}

			$("#modal_visor_solicitud_aut").modal({
				backdrop: 'static',
				keyboard: false
			});

		});


		$('#tabla_autorizaciones').on("click", ".enviar_a_dg", function() {

			var tr = $(this).closest('tr');
			var row = table_autorizar.row(tr).data();

			$.post(url + "Solicitante/enviar_a_dg", {
				idsolicitud: $(this).val(),
				departamento: row.nomdepto
			}).done(function(data) {
				data = JSON.parse(data);
				if (data.resultado) {

					row.etapa = data.solicitudes_proceso.netapa;
					table_autorizar.row(tr).data(row).draw();
					table_autorizar.ajax.reload();
					//table_autorizar.ajax.reload(null,false);
					//table_proceso.ajax.reload(null,false);
				} else {
					alert("Algo salio mal, recargue su página.")
				}
			});
		});

		$('#tabla_autorizaciones').on("click", ".reenviar_factura", function() {
			$.post(url + "Solicitante/reenviar_factura", {
				idsolicitud: $(this).val()
			}).done(function(data) {
				data = JSON.parse(data);
				if (data.resultado) {
					table_autorizar.ajax.reload(null, false);
					table_proceso.ajax.reload(null, false);
				} else {
					alert("Algo salio mal, recargue su página.")
				}
			});
		});
		$('.autorizarSolicitudDG').on("click", function() {

			$.post(url + "Solicitudes_autorizacion/autorizacionDG", {
				idsolicitud: $('.autorizarSolicitudDG').val()
			}).done(function(data) {
				data = JSON.parse(data);
				if (data.resultado) {
					table_autorizar.ajax.reload(null, false);
				} else {
					alert("Algo salio mal, recargue su página.")
				}
			});
		});

		$('#tabla_autorizaciones').on("click", ".downloadFile", function() {
			link_post = "Solicitudes_autorizacion/traerArchivo";
			idSolicitud = $(this).val();
			$.post(url + link_post, {
				idSolicitud: $(this).val()
			}).done(function(data) {
				data = JSON.parse(data);
				window.open(data.archivo, "Autorizaciones");
			});

		});
		$('#tabla_autorizaciones').on("click", ".autorizarSolicitud", function() {
			link_post = "Solicitudes_autorizacion/autorizarSolicitud";
			idSolicitud = $(this).val();
			$folio = $(this).val()

			$.post(url + "Solicitudes_autorizacion/autorizarSolicitud", {
				idAutorizacion: $(this).val(),
				idTipo: $(this).attr("data-value")
			}).done(function(data) {
				data = JSON.parse(data);
				if (data.estatus) {
					alert("La solicitud numero: #" + $folio + " ha sido autorizada.")
					table_autorizar.ajax.reload();
					//table_autorizar.ajax.reload(null,false);
					//table_proceso.ajax.reload(null,false);
				} else {
					alert("Algo salio mal, recargue su página.")
				}

				// var id = $(".listadoAreas").val();
				// $(".listadoAreas").empty().append('<option value="" selected>Seleccione un colaborador. </option>');
				// data = JSON.parse(data);
				// $.each(data.listadoAreasAut, function(i, item) {
				//     $('.listadoAreas').append('<option value="' + item.idusuario + '">' + item.nombreCompleto + ' - ' + item.rol_descripcion + '</option>');
				// });
				// $("#modalAut").modal();
			});

		});

		$('#tabla_autorizaciones').on("click", ".rechazarAutorizacion", function() {
			console.log("rechazar solicitud")
			$folio = $(this).val()
			$.post(url + "Solicitudes_autorizacion/rechazarSolicitud", {
				idAutorizacion: $(this).val(),
				idTipo: 1
			}).done(function(data) {
				console.log("data")
				data = JSON.parse(data);
				console.log(data);
				console.log($folio)

				if (data.estatus) {
					alert("La solicitud numero: #" + $folio + " ha sido rechazada.")
					table_autorizar.ajax.reload();
					//table_autorizar.ajax.reload(null,false);
					//table_proceso.ajax.reload(null,false);
				} else {
					alert("Algo salio mal, recargue su página.")
				}
				// var id = $(".listadoAreas").val();
				// $(".listadoAreas").empty().append('<option value="" selected>Seleccione un director. </option>');
				// data = JSON.parse(data);
				// $.each(data.listadoAreasAut, function(i, item) {
				//     $('.listadoAreas').append('<option value="' + item.idusuario + '">' + item.nombreCompleto + ' - ' + item.rol_descripcion + '</option>');
				// });
				// $("#modalAut").modal();
			});


		});

		$('#tabla_autorizaciones').on("click", ".cancelarSol", function() {
			// link_post = "Solicitudes_autorizacion/guardar_solicitud";
			console.log("cancelando solicitud")
			$folio = $(this).val()
			$.post(url + "Solicitudes_autorizacion/cancelarSolicitud", {
				idAutorizacion: $(this).val(),
				idTipo: 1
			}).done(function(data) {
				console.log("data")
				data = JSON.parse(data);
				console.log(data);
				console.log($folio)

				if (data.estatus) {
					alert("La solicitud numero: #" + $folio + " ha sido cancelada correctamente.")
					table_autorizar.ajax.reload();
					//table_autorizar.ajax.reload(null,false);
					//table_proceso.ajax.reload(null,false);
				} else {
					alert("Algo salio mal, recargue su página.")
				}
				// var id = $(".listadoAreas").val();
				// $(".listadoAreas").empty().append('<option value="" selected>Seleccione un director. </option>');
				// data = JSON.parse(data);
				// $.each(data.listadoAreasAut, function(i, item) {
				//     $('.listadoAreas').append('<option value="' + item.idusuario + '">' + item.nombreCompleto + ' - ' + item.rol_descripcion + '</option>');
				// });
				// $("#modalAut").modal();
			});

		});


	});

	$('#tabla_autorizaciones').on("click", ".rechazada_da", function() {
		$.post(url + "Solicitante/rechazada_da", {
			idsolicitud: $(this).val()
		}).done(function(data) {
			data = JSON.parse(data);
			if (data.resultado) {
				table_autorizar.ajax.reload(null, false);
			} else {
				alert("Algo salio mal, recargue su página.")
			}
		});
	});

	$('#tabla_autorizaciones').on("click", ".congelar_solicitud", function() {
		$.post(url + "Solicitante/congelar_solicitud", {
			idsolicitud: $(this).val()
		}).done(function(data) {
			data = JSON.parse(data);
			if (data.resultado) {
				table_autorizar.ajax.reload(null, false);
			} else {
				alert("Algo salio mal, recargue su página.")
			}
		});
	});

	$('#tabla_autorizaciones').on("click", ".liberar_solicitud", function() {
		$.post(url + "Solicitante/liberar_solicitud", {
			idsolicitud: $(this).val()
		}).done(function(data) {
			data = JSON.parse(data);
			if (data.resultado) {
				table_autorizar.ajax.reload(null, false);
			} else {
				alert("Algo salio mal, recargue su página.")
			}
		});
	});

	$('#formulario_autorizacion').submit(function(e) {
		e.preventDefault();
	}).validate({
		submitHandler: function(form) {

			var data = new FormData($(form)[0]);
			data.append("fileSol", 1);
			console.dir(form)
			$.ajax({
				url: url + 'Solicitudes_autorizacion/guardarSolicitud',
				data: data,
				cache: false,
				contentType: false,
				processData: false,
				dataType: 'json',
				method: 'POST',
				type: 'POST', // For jQuery < 1.9
				success: function(data) {
					if (data.estatus == 1) {
						resetearFormulario();
						
					} else {
						alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
					}
				},
				error: function() {
					alert("Algo salio mal, recargue su página.");
				}
			});
		}

	});

	$('#formulario_actualizar_autorizacion').submit(function(e) {
		e.preventDefault();
	}).validate({
		submitHandler: function(form) {

			if (confirm('¿Deseas guardar los cambios que realizaste a esta solicitud?\nAsegurate de agregar los comentarios pertinentes para el revisor para el solicitante antes de continuar.')) {

				// $("#delete-button").click(function(){

				// 		if(confirm("Are you sure you want to delete this?")){
				// 		$("#delete-button").attr("href", "query.php?ACTION=delete&ID='1'");
				// 		}
				// 		else{
				// 			return false;
				// 		}

				// });

				// return
				// console.log(this)
				// var idAutorizacion = this
				$('#user-comment-helper').val($('#user-comment').val());
				// console.dir($('#user-comment'));
				var data = new FormData($(form)[0]);
				data.append("fileSol", 1);
				// console.log($(form))
				console.log(form)
				data.append('idAutorizacion', selectedIdAutorizacion);
				data.append('comment', $('#user-comment-helper').val());
				data.append('filesToDelete', $('#tbodyDocumentsVisor').val());

				console.dir(data);

				$.ajax({
					url: url + 'Solicitudes_autorizacion/actualizarSolicitud',
					data: data,
					cache: false,
					contentType: false,
					processData: false,
					dataType: 'json',
					method: 'POST',
					type: 'POST', // For jQuery < 1.9
					success: function(data) {
						// console.dir(data)
						if (data.estatus == 1) {
							$("#tbl_motivVisor tr>td").remove();
							$("#tbl_autVisor tr>td").remove();
							$("#tbl_documents_visor tr>td").remove();

							document.getElementById("tituloVisor").value = "";
							document.getElementById("descrVisor").value = "";
							document.getElementById("user-comment").value = "";
							// $("#involucradosVisor").empty().append('<option value="" selected>Seleccione una opción</option>');
							$("#autorizadosVisor").empty().append('<option value="" selected>Seleccione una opción</option>');
							document.getElementById("dropedFilesInputVisor").value = "";
							$('<ul class="multiple_emails-ul" />');
							cleanInputsVisor("#dropedFilesInputVisor");
							table_autorizar.ajax.reload();
							document.querySelectorAll('.message-balloon');
							const boxes = document.querySelectorAll('.message-balloon');
							if (boxes) {
								boxes.forEach(box => {
									box.remove();
								});
							}


							$("#modal_visor_solicitud_aut").modal('toggle');
						} else {
							alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
						}
					},
					error: function() {
						alert("Algo salio mal, recargue su página.");
					}
				});
			}
		}
	});

	$('#formulario_enviar_aut').submit(function(e) {
		e.preventDefault();
		console.log(e)
	}).validate({
		submitHandler: function(form) {
			var data = new FormData($(form)[0]);
			data.append("idSolicitud", idSolicitud);
			$.ajax({
				url: url + 'Solicitudes_autorizacion/enviarAutStep',
				data: data,
				cache: false,
				contentType: false,
				processData: false,
				dataType: 'json',
				method: 'POST',
				type: 'POST', // For jQuery < 1.9
				success: function(data) {
					if (data.estatus == 1) {
						//resear_formulario();
						// $("#formulario_enviar_aut").modal('toggle');
						table_autorizar.ajax.reload();
					} else {
						alert("NO SE HA PODIDO COMPLETAR LA SOLICITUD");
					}
				},
				error: function() {
					alert("Algo salio mal, recargue su página.");
				}
			});
		}

	});

	$('input:file[multiple]').change(
		function(e) {
			// console.log("im here osi osi");
			// console.log(e);
			// console.log(this.id);
			if (this.id == "dropedFilesInputVisor") {
				renderizarListaVisor(e.currentTarget.files);

			} else {

				renderizarLista(e.currentTarget.files);

			}


		}

	);

	const dropContainer = document.getElementById("dropcontainer")


	dropContainer.addEventListener("dragover", (e) => {
		// prevent default to allow drop
		e.preventDefault()
	}, false)

	dropContainer.addEventListener("dragenter", () => {
		dropContainer.classList.add("drag-active")
	})

	dropContainer.addEventListener("dragleave", () => {
		dropContainer.classList.remove("drag-active")
	})

	var fileInput = document.getElementById("dropedFilesInput")

	dropContainer.addEventListener("drop", (e) => {

		e.preventDefault()

		fileInput = document.getElementById("dropedFilesInput")
		dropContainer.classList.remove("drag-active")

		// console.log(fileInput.files);
		// console.log(e.dataTransfer.files);

		// fileInput.files = e.dataTransfer.files


		//Create a new DataTransfer object

		var dataTransfer = new DataTransfer

		//Add new files from the event's DataTransfer
		for (let i = 0; i < e.dataTransfer.files.length; i++)
			dataTransfer.items.add(e.dataTransfer.files[i])

		//Add existing files from the input element
		for (let i = 0; i < fileInput.files.length; i++)
			dataTransfer.items.add(fileInput.files[i])

		//Assign the files to the input element
		fileInput.files = dataTransfer.files

		renderizarLista(fileInput.files)

	});


	const dropcontainerVisor = document.getElementById("dropcontainerVisor")


	dropcontainerVisor.addEventListener("dragover", (e) => {
		// prevent default to allow drop
		e.preventDefault()
	}, false)

	dropcontainerVisor.addEventListener("dragenter", () => {
		dropcontainerVisor.classList.add("drag-active")
	})

	dropcontainerVisor.addEventListener("dragleave", () => {
		dropcontainerVisor.classList.remove("drag-active")
	})

	var fileInput = document.getElementById("dropedFilesInputVisor")

	dropcontainerVisor.addEventListener("drop", (e) => {

		e.preventDefault()

		fileInput = document.getElementById("dropedFilesInputVisor")
		dropcontainerVisor.classList.remove("drag-active")

		// console.log(fileInput.files);
		// console.log(e.dataTransfer.files);

		// fileInput.files = e.dataTransfer.files


		//Create a new DataTransfer object

		var dataTransfer = new DataTransfer

		//Add new files from the event's DataTransfer
		for (let i = 0; i < e.dataTransfer.files.length; i++)
			dataTransfer.items.add(e.dataTransfer.files[i])

		//Add existing files from the input element
		for (let i = 0; i < fileInput.files.length; i++)
			dataTransfer.items.add(fileInput.files[i])

		//Assign the files to the input element
		fileInput.files = dataTransfer.files

		renderizarListaVisor(fileInput.files)

	});

	function resetearFormulario() {
		//resear_formulario();

		$('#tipo').val('');
		$('#tipo').trigger('change');
		// $("#tipo").empty().append('<option value="" selected>Seleccione una opción</option>');
		// $("#tipo").val("");
		// darequired
		$("#tbl_motiv tr>td").remove();
		$("#tbl_aut tr>td").remove();
		document.getElementById("titulo").value = "";
		document.getElementById("descr").value = "";
		$("#involucrados").empty().append('<option value="" selected>Seleccione una opción</option>');
		$("#autorizados").empty().append('<option value="" selected>Seleccione una opción</option>');
		document.getElementById("dropedFilesInput").value = "";
		document.getElementById("dropedFilesInputVisor").value = "";
		// $("#dgrequiredVisor").val(false);
		// $("#darequired").val(false);
		$('#darequired').attr('value', 'false');
		$('#dgrequiredVisor').attr('value', 'false');
		$("#modal_formulario_solicitud_aut").modal('toggle');
		$('#proveedorAV').val(''); // Select the option with a value of '1'
				$('#proveedorAV').trigger('change');
				$('#tipoFCXPV').val(""); // Select the option with a value of '1'
				$('#tipoFCXPV').trigger('change');
				$('#idEmpresaV').val(""); // Select the option with a value of '1'
				$('#idEmpresaV').trigger('change');
				
				$("#cantidadV").val("");
				$("#rfcV").val("");
				$("#clabeV").val("");
				$("#emailcxpfV").val("");
		table_autorizar.ajax.reload();
	}

	function renderizarLista(incomingfiles) {

		var filesto = incomingfiles
		// console.log(incomingfiles);
		var numFiles = incomingfiles.length;
		// console.log(numFiles);

		$('#output').empty();
		$('#output li').empty();
		$('#output li span').empty();

		for (i = 0; i < numFiles; i++) {
			console.log(filesto[i].name.lastIndexOf('.xlsm'));

			if (filesto[i].name.lastIndexOf('.docx') != -1 && filesto[i].name.lastIndexOf('.xlsx') != -1 && filesto[i].name.lastIndexOf('.xlsm') != -1) {
				alert("Please note: only excel file formats are allowed. Please download the provided upload template, see the link below.");
				this.value = '';
				return;
			}

			fileSize = parseInt(filesto[i].size, 10) / 1024;
			filesize = Math.round(fileSize);
			// console.log(i);

			$('<li />').text('    ' + filesto[i].name).appendTo($('#output')).val(i).index(i);
			$('<span />').addClass('filesize').text('(' + filesize + ' kb)').appendTo($('#output li:last'));
			$('<span />').addClass('fa fa-trash')
				.text('  Eliminar ')
				.appendTo($('#output span:last'))
				.css({
					'cursor': 'alias',
					'align': 'bottom',
					'font-size': '14px',
					'font-weight': 'Bold',
					'height': '15px',
					'margin-left': '30px',
					'margin-top': '10px'
				})
				.click(function() {

					var str = $(this).parents("li").index();
					// console.log(document.getElementById("dropedFilesInput").files);

					const dt = new DataTransfer()
					// const input = document.getElementById('files')
					// const { files } = input

					for (let i = 0; i < filesto.length; i++) {
						const file = filesto[i]
						// console.log(file)
						// console.log(file.name)

						if ($(this).closest("li").val() !== i)
							dt.items.add(file) // here you exclude the file. thus removing it.
					}

					fileInput = document.getElementById("dropedFilesInput")
					document.getElementById("dropedFilesInput").files = dt.files // Assign the updates list
					filesto = dt.files
					// console.log('aqui')
					// console.log($(this).closest( "li" ).index())
					// console.log($(this).closest( "li" ).val())
					// console.log(filesto)
					// console.log(fileInput.files)
					//   alert(str);
					$(this).closest("li").hide();
					renderizarLista(fileInput.files)

				});
		}
	}

	function renderizarListaVisor(incomingfiles) {

		var filesto = incomingfiles
		// console.log(incomingfiles);
		var numFiles = incomingfiles.length;
		// console.log(numFiles);

		$('#outputVisor').empty();
		$('#outputVisor li').empty();
		$('#outputVisor li span').empty();

		for (i = 0; i < numFiles; i++) {
			console.log(filesto[i].name.lastIndexOf('.xlsm'));

			if (filesto[i].name.lastIndexOf('.docx') != -1 && filesto[i].name.lastIndexOf('.xlsx') != -1 && filesto[i].name.lastIndexOf('.xlsm') != -1) {
				alert("Please note: only excel file formats are allowed. Please download the provided upload template, see the link below.");
				this.value = '';
				return;
			}

			fileSize = parseInt(filesto[i].size, 10) / 1024;
			filesize = Math.round(fileSize);
			// console.log(i);

			$('<li />').text('    ' + filesto[i].name).appendTo($('#outputVisor')).val(i).index(i);
			$('<span />').addClass('filesize').text('(' + filesize + ' kb)').appendTo($('#outputVisor li:last'));
			$('<span />').addClass('fa fa-trash')
				.text('  Eliminar ')
				.appendTo($('#outputVisor span:last'))
				.css({
					'cursor': 'alias',
					'align': 'bottom',
					'font-size': '14px',
					'font-weight': 'Bold',
					'height': '15px',
					'margin-left': '30px',
					'margin-top': '10px'
				})
				.click(function() {

					var str = $(this).parents("li").index();
					// console.log(document.getElementById("dropedFilesInput").files);

					const dt = new DataTransfer()
					// const input = document.getElementById('files')
					// const { files } = input

					for (let i = 0; i < filesto.length; i++) {
						const file = filesto[i]
						// console.log(file)
						// console.log(file.name)

						if ($(this).closest("li").val() !== i)
							dt.items.add(file) // here you exclude the file. thus removing it.
					}

					fileInput = document.getElementById("dropedFilesInputVisor")
					document.getElementById("dropedFilesInputVisor").files = dt.files // Assign the updates list
					filesto = dt.files
					// console.log('aqui')
					// console.log($(this).closest( "li" ).index())
					// console.log($(this).closest( "li" ).val())
					// console.log(filesto)
					// console.log(fileInput.files)
					//   alert(str);
					$(this).closest("li").hide();
					renderizarListaVisor(fileInput.files)

				});
		}
	}

	$(document).on("keydown", "form", function(event) {
		return event.key != "Enter";
	});

	function cleanInputs(fileEle) {

		const fileInput = document.getElementById("dropedFilesInput");
		console.log(fileInput.files);

		$('#dropedFilesInput').val(null);
		console.log(fileInput.files);

		$('#output').empty();
		$('#output li').empty();
		$('#output li span').empty();

	}

	function cleanInputsVisor(fileEle) {

		const fileInput = document.getElementById("dropedFilesInputVisor");
		console.log(fileInput.files);

		$('#dropedFilesInputVisor').val(null);
		console.log(fileInput.files);

		$('#outputVisor').empty();
		$('#outputVisor li').empty();
		$('#outputVisor li span').empty();

	}
</script>
<?php
require("footer.php");
?>