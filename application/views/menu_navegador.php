<body class="hold-transition skin-green sidebar-mini fixed" onload="<?= isset($this->session->userdata('inicio_sesion')['pass']) && $this->session->userdata('inicio_sesion')['pass'] == 1 ? 'checkpass('.$this->session->userdata('inicio_sesion')['pass'].')' : '' ?>">
  <div class="wrapper">
		<header class="main-header">
      <a href="/" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>CPP</b> Maderas</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>CPP</b> Maderas</span>
      </a>
			<nav class="navbar navbar-static-top">
				<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button"><span class="sr-only">Toggle navigation</span></a>
				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
            <li><a href="#" class="dropdown-toggle"><span class="hidden-xs">BIENVENIDO <?= $this->session->userdata("inicio_sesion")['apellidos'] ?>, <?= $this->session->userdata("inicio_sesion")['nombres'] ?></span></a></li>
						<li><a href="<?=site_url("Home/cerrar_sesion")?>"><i class="fas fa-sign-out-alt"></i></a></li>
					</ul>
				</div>
			</nav>
		</header>
		<aside class="main-sidebar">
			<section class="sidebar">
        <img src="<?= base_url("img/logo_blanco_cdm.png")?>" class="img-responsive" alt="Responsive image">
				<ul class="sidebar-menu" data-widget="tree">
          <li class="header"><h5>MENÚ DE NAVEGACIÓN</h5></li>
          <li><a href="<?=site_url("Home")?>"><i class='fas fa-home'></i> <span>Inicio</span><span class='pull-right-container'></span></a></li>
          <?php

                        use Monolog\Handler\IFTTTHandler;

            $idusuarios_nopermitidos = ['2579', '2582', '2583', '1868', '2665', '2685'];


            if( isset( $this->session->userdata('inicio_sesion')["im"] ) && $this->session->userdata('inicio_sesion')["im"] == 3 ){
              echo "<li><a href='".site_url("Alta_proveedores")."'><i class='fas fa-money-check'></i> <span>Alta proveedores</span><span class='pull-right-container'></span></a></li>";
            }

            if( (isset( $this->session->userdata('inicio_sesion')["im"] ) && $this->session->userdata('inicio_sesion')["im"] == 2) 
                    || ($this->session->userdata('inicio_sesion')["rol"]=="FP" && $this->session->userdata('inicio_sesion')["depto"]=="CONTABILIDAD") ){
              echo "<li><a href='".site_url("Complementos_cxp")."'><i class='fas fa-file'></i> <span>Facturas faltantes</span><span class='pull-right-container'></span></a></li>";
            }

            if( isset( $this->session->userdata('inicio_sesion')["im"] ) && $this->session->userdata('inicio_sesion')["im"] == 2 && $this->session->userdata('inicio_sesion')["depto"] == 'CONTABILIDAD' ){
              echo "<li><a href='".site_url("Impuestos")."'><i class='fas fa-money-bill-wave'></i> <span>Impuestos</span><span class='pull-right-container'></span></a></li>";
            }

            if( isset( $this->session->userdata('inicio_sesion')["im"] ) && $this->session->userdata('inicio_sesion')["im"] == 1 ){
              echo "<li><a href='".site_url("Impuestos")."'><i class='fas fa-money-bill-wave'></i> <span>Impuestos</span><span class='pull-right-container'></span></a></li>";
            }
            if( isset( $this->session->userdata('inicio_sesion')["im"] ) && $this->session->userdata('inicio_sesion')["im"] == 4 ){
                echo "<li><a href='".site_url("Contratos")."'><i class='fas fa-file-contract'></i> <span>Contratos</span><span class='pull-right-container'></span></a></li>";
            }

            if( in_array( $this->session->userdata("inicio_sesion")['id'], [ 94, 93, 1853 ] ) /*in_array($this->session->userdata("inicio_sesion")['rol'], array( 'CC' ) )*/ ){
              echo"<li class='treeview'><a href='#'>";
              echo "<i class='fas fa-cogs'></i> <span>Catálogos</span><span class='pull-right-container'>";
              echo "<i class='fa fa-angle-left pull-right'></i></span>";
              echo "</a><ul class='treeview-menu'>";
              echo "<li><a href='".site_url("Catalogos/Proyectos")."'><i class='fas fa-circle'></i> <span>Proyectos</span><span class='pull-right-container'></span></a></li>";
              echo "<li><a href='".site_url("Catalogos/Proveedores")."'><i class='fas fa-circle'></i> <span>Proveedores</span><span class='pull-right-container'></span></a></li>";
              echo "</ul></li>"; 
            }
            
            switch ($this->session->userdata('inicio_sesion')["rol"]) {
              case 'SO':
                if(in_array( $this->session->userdata('inicio_sesion')["id"], $idusuarios_nopermitidos ) ){
                  echo "<li><a href='".site_url("Usuarios_cxp")."'><i class='fas fa-user-plus'></i> <span>Usuarios</span><span class='pull-right-container'></span></a></li>";
                 
                }else{
                  echo "<li><a href='".site_url("Usuarios_cxp")."'><i class='fas fa-user-plus'></i> <span>Usuarios</span><span class='pull-right-container'></span></a></li>";
                  echo "<li><a href='".site_url("Provedores_cxp")."'><i class='fas fa-building'></i> <span>Proveedores</span><span class='pull-right-container'></span></a></li>";
                  
                  echo "<li><a href='".site_url("Provedores_cxp/datosfiscales_prov")."'><i class='fas fa-building'></i> <span>Inf. Fiscal Prov.</span><span class='pull-right-container'></span></a></li>";


                  echo "<li><a href='".site_url("Provedores_cxp/ProductosXprov/")."'><i class=\"fas fa-concierge-bell\"></i> <span>Productos - Proveedor</span><span class='pull-right-container'></span></a></li>";
                  echo "<li><a href='".site_url("TipoCambio")."'><i class='fas fa-coins'></i> <span>Monedas</span><span class='pull-right-container'></span></a></li>";
                  echo "<li><a href='".site_url("Cajas_ch")."'><i class='fa fa-briefcase'></i> <span>Cajas chicas</span><span class='pull-right-container'></span></a></li>";

                  echo"<li class='treeview'><a href='#'>";
                  echo "<i class='fas fa-search'></i> <span>Buscar solicitud</span><span class='pull-right-container'>";
                  echo "<i class='fa fa-angle-left pull-right'></i></span>";
                  echo "</a><ul class='treeview-menu'>";
                  echo"<li><form action='#'><div class='input-group'><input id='idsol' type='text' class='form-control' required>";
                  echo"<div class='input-group-btn'><button class='btn btn-default consultar_modal' value='' data-value=''><i class='fas fa-search'></i></button></div></div></form></li>";
                  echo"<li><a href='#'><label class='radio-inline'><input type='radio' name='idopt' value='SOL' checked># Solicitud</label><label class='radio-inline'><input type='radio' name='idopt' value='FACU'># UUID</label></a></li>";
                  echo "</ul></li>";
                }

                /* TESTING -> s1dt@cm.com - SISTEMAS DEMO DE CATALOGOS CRUD PROYECTOS_OFICINAS*/
                if(in_array( $this->session->userdata("inicio_sesion")['id'], [2588])){ 
                  echo"<li class='treeview'><a href='#'>";
                  echo "<i class='fas fa-toolbox'></i> <span>Catálogos</span><span class='pull-right-container'>";
                  echo "<i class='fa fa-angle-left pull-right'></i></span>";
                  echo "</a><ul class='treeview-menu'>";
                  echo "<li><a href='".site_url("Departamento_sedes")."'><i class='fas fa-circle'></i> <span>Departamento - Sedes</span><span class='pull-right-container'></span></a></li>";
                  echo "</ul></li>";
                }
              break;
              case 'CX':
              case 'CC':
                echo"<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-hand-holding-usd'></i> <span>Devoluciones / Traspasos</span><span class='pull-right-container'>";
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo "<li><a href='".site_url("Devoluciones_Traspasos/Devolucion")."'><i class='fas fa-circle'></i> <span>Solicitudes de devoluciones</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Devoluciones_Traspasos/Histdevolucionesytraspasos")."'><i class='fas fa-circle'></i> <span>Histórico</span><span class='pull-right-container'></span></a></li>";
                echo "</ul></li>";
              case 'CT':
                echo "<li><a href='".site_url("Contabilidad")."'><i class='fas fa-calculator'></i> <span>Contabilidad</span><span class='pull-right-container'></span></a></li>";
                
                echo"<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-file'></i> <span>Facturas</span><span class='pull-right-container'>";
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo "<li><a href='#' data-toggle='modal' data-target='#modal_reporte_falta_provision'><i class='fas fa-circle'></i> <span>Relación sin Provisión</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Complementos_cxp")."'><i class='fas fa-circle'></i> <span>Facturas faltantes</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Complementos_cxp/gastos_comprobados")."'><i class='fas fa-circle'></i> <span>Facturas comprobadas</span><span class='pull-right-container'></span></a></li>";  
                echo "<li><a href='".site_url("Descargar_XML")."'><i class='fas fa-circle'></i> <span>Descarga masiva</span><span class='pull-right-container'></span></a></li>";
                echo "</ul></li>";

                echo"<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-search'></i> <span>Buscar solicitud</span><span class='pull-right-container'>";
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo"<li><form action='#'><div class='input-group'><input id='idsol' type='text' class='form-control' required>";
                echo"<div class='input-group-btn'><button class='btn btn-default consultar_modal' value='' data-value=''><i class='fas fa-search'></i></button></div></div></form></li>";
                echo"<li><a href='#'><label class='radio-inline'><input type='radio' name='idopt' value='SOL' checked># Solicitud</label><label class='radio-inline'><input type='radio' name='idopt' value='FACU'># UUID</label></a></li>";
                echo "</ul></li>";

                echo"<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-list-ul'></i> <span>Historial</span><span class='pull-right-container'>";
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo "<li><a href='".site_url("Historial")."'><i class='fas fa-circle'></i> <span>Solicitudes</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial/historial_pagos")."'><i class='fas fa-circle'></i> <span>Pagos</span><span class='pull-right-container'></span></a></li>";  
                echo "<li><a href='".site_url("Historial/historialcch")."'><i class='fas fa-circle'></i> <span>Cajas chicas</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial/historialtdc")."'><i class='fas fa-circle'></i> <span>T. Crédito</span><span class='pull-right-container'></span></a></li>"; 
                echo "<li><a href='".site_url("Historial_cheques")."'><i class='fas fa-circle'></i> <span>Cheques</span><span class='pull-right-container'></span></a></li>";
                if ($this->session->userdata("inicio_sesion")['rol'] == 'CT' || in_array( $this->session->userdata("inicio_sesion")['id'], [94, 160, 323, 2483, 90, 3082]) ) {
                  echo "<li><a href='".site_url("Historial/historialViaticos")."'><i class='fas fa-circle'></i> <span>Viáticos</span><span class='pull-right-container'></span></a></li>";
                }
                if (in_array($this->session->userdata("inicio_sesion")['rol'], ['CT', 'CC', 'CX']))
                  echo "<li><a href='".site_url("Historial/historial_pagos_parcialidades")."'><i class='fas fa-circle'></i> <span>Parcialidades</span><span class='pull-right-container'></span></a></li>"; 
                echo "</ul></li>";
              break;
              case 'AS':
              case 'DA':
                //if( in_array( $this->session->userdata("inicio_sesion")['id'], array( 46, 67 ) ) ){
                if( in_array( $this->session->userdata("inicio_sesion")['depto'], ['POST VENTA', 'CONTRALORIA' ] ) ){
                  echo"<li class='treeview'><a href='#'>";
                  echo "<i class='fas fa-hand-holding-usd'></i> <span>Dev. y Traspasos</span><span class='pull-right-container'>";
                  echo "<i class='fa fa-angle-left pull-right'></i></span>";
                  echo "</a><ul class='treeview-menu'>";
                  echo "<li><a href='".site_url("Devoluciones_Traspasos/Devolucion")."'><i class='fas fa-circle'></i> <span>Devoluciones</span><span class='pull-right-container'></span></a></li>";
                  echo "<li><a href='".site_url("Devoluciones_Traspasos/Histdevolucionesytraspasos")."'><i class='fas fa-circle'></i> <span>Histórico</span><span class='pull-right-container'></span></a></li>";
                  echo "</ul></li>";
                }
                if( in_array( $this->session->userdata("inicio_sesion")['depto'], ['TECNOLOGIA DE LA INFORMACION'] ) && $this->session->userdata('inicio_sesion')["rol"] == 'DA' ){
                  echo "<li><a href='".site_url("Usuarios_cxp")."'><i class='fas fa-user-plus'></i> <span>Usuarios</span><span class='pull-right-container'></span></a></li>";
                  echo "<li><a href='".site_url("Movimientos_BB")."'><i class='fa fa-piggy-bank'></i> <span>Mov. Banbajio</span><span class='pull-right-container'></span></a></li>";
                  echo "<li><a href='".site_url("Historial/historial_pagos")."'><i class='fas fa-receipt'></i> <span>Pagos generales</span><span class='pull-right-container'></span></a></li>";
                }
                if( in_array($this->session->userdata("inicio_sesion")['depto'], ["COMPRAS", "CI-COMPRAS"]) ){
                  echo "<li><a href='".site_url("Indicadores")."'><i class='fas fa-toolbox'></i> <span>Pagado Proveedor</span><span class='pull-right-container'></span></a></li>";
                }
                if($this->session->userdata('inicio_sesion')["id"] == 25){
                echo"<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-user-check'></i> <span>Dirección general</span><span class='pull-right-container'>";
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo "<li><a href='".site_url("DireccionGeneral/index_p")."'><i class='fas fa-circle'></i> <span>Autorizaciones todos</span><span class='pull-right-container'></span></a></li></li>";
                echo "<li><a href='".site_url("Historial/historialDATI")."'><i class='fas fa-circle'></i> <span>Historial solicitudes</span><span class='pull-right-container'></span></a></ul></li>";
                }
                if( in_array( $this->session->userdata("inicio_sesion")['id'], [2259]) ){ /** INICIO FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                  echo "<li><a href='".site_url("Cajas_ch")."'><i class='fa fa-briefcase'></i> <span>Cajas chicas</span><span class='pull-right-container'></span></a></li>";
                }/** FIN FECHA: 29-ABRIL-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                /** INICIO FECHA: 10-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                if( in_array( $this->session->userdata("inicio_sesion")['id'], [ 99 ] ) ){
                  echo"<li class='treeview'><a href='#'>";
                  echo "<i class='fas fa-cogs'></i> <span>Catálogos</span><span class='pull-right-container'>";
                  echo "<i class='fa fa-angle-left pull-right'></i></span>";
                  echo "</a><ul class='treeview-menu'>";
                  echo "<li><a href='".site_url("Provedores_cxp")."'><i class='fas fa-circle'></i> <span>Proveedores</span><span class='pull-right-container'></span></a></li>";
                  echo "</ul></li>";
                }/** FIN FECHA: 10-MARZO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                
              case 'CJ':
              case 'CA':
                if( $this->session->userdata("inicio_sesion")['id'] == 1844){
                  echo "<li><a href='".site_url("Indicadores")."'><i class='fas fa-toolbox'></i> <span>Pagado Proveedor</span><span class='pull-right-container'></span></a></li>";
                }
                if( in_array( $this->session->userdata("inicio_sesion")['id'], array( 174 ) ) ){
                  echo "<li><a href='".site_url("Contraloria")."'><i class='fas fa-hand-holding-usd'></i> <span>Devoluciones</span><span class='pull-right-container'></span></a></li>";
                }
                echo "<li><a href='".site_url("Complementos_cxp")."'><i class='fas fa-file'></i> <span>Facturas faltantes</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Solicitante")."'><i class='far fa-edit'></i> <span>Solicitudes de pago</span><span class='pull-right-container'></span></a></li>";
                if( in_array( $this->session->userdata("inicio_sesion")['id'], array( 8 ) ) ){
                  echo "<li><a href='".site_url("Solicitante/reporteFinanzas")."'><i class='fas fa-hand-holding-usd'></i> <span>Reporte Finanzas</span><span class='pull-right-container'></span></a></li>";
                }
                /** INICIO FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> 
                 * 2409	- AS - cap.finanza LUGO SOSA	JUAN MANUEL
                 * 2681	- CA - VA.MONCADA MONCADA GUERRA VALERIA EDITH
                 * **/
                if( in_array( $this->session->userdata("inicio_sesion")['id'], [ 2681, 2409, 3227 ] ) ){
                  echo"<li class='treeview'><a href='#'>";
                  echo "<i class='fas fa-receipt'></i> <span>Cuentas por pagar</span><span class='pull-right-container'>";
                  echo "<i class='fa fa-angle-left pull-right'></i></span>";
                  echo "</a><ul class='treeview-menu'>";
                  echo "<li><a href='".site_url("Cuentasxp/nuevas")."'><i class='fas fa-circle'></i> <span>Solicitudes nuevas</span><span class='pull-right-container'></span></a></li>";
                  echo "<li><a href='".site_url("Historial/p_programados")."'><i class='fas fa-circle'></i> <span>Historial Pagos Programados</span><span class='pull-right-container'></span></a></li>";
                  echo "</ul></li>";
                }/** FIN FECHA: 17-JUNIO-2025 | PANEL CP A USUARIOS ESPECIFICOS | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
              break;
              case 'CP': //
                //echo "<li><a href='".site_url("Importador_prestamos")."'><i class='fas fa-file-upload'></i> <span>Importador</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Solicitante")."'><i class='far fa-edit'></i> <span>Solicitudes de pago</span><span class='pull-right-container'></span></a></li>";
                echo"<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-toolbox'></i> <span>Catálogos</span><span class='pull-right-container'>";
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo "<li><a href='".site_url("Bancos_cxp")."'><i class='fas fa-circle'></i> <span>Bancos</span><span class='pull-right-container'></span></a></li>";  
                echo "<li><a href='".site_url("Provedores_cxp")."'><i class='fas fa-circle'></i> <span>Proveedores</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("tarjetas_credito")."'><i class='fas fa-circle'></i> <span>Tarjetas de Crédito</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Provedores_cxp/datosfiscales_prov")."'><i class='fas fa-circle'></i> <span>Datos Fiscales Proveedores</span><span class='pull-right-container'></span></a></li>";
                echo "</ul></li>";

                if( in_array( $this->session->userdata("inicio_sesion")['depto'], ["CONTROL INTERNO", "CI-COMPRAS", "ADMINISTRACION"] ) ){/** FECHA: 21-JUNIO-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
                  echo "<li><a href='".site_url("Cajas_ch")."'><i class='fa fa-briefcase'></i> <span>Cajas chicas</span><span class='pull-right-container'></span></a></li>";
                  /*echo "<li><a href='".site_url("Usuarios_cxp")."'><i class='fas fa-user-plus'></i> <span>Usuarios</span><span class='pull-right-container'></span></a></li>";*/
                }
                if( in_array( $this->session->userdata("inicio_sesion")['depto'], ["CONTROL INTERNO", "CI-COMPRAS"] ) ){
                  echo "<li><a href='".site_url("Usuarios_cxp")."'><i class='fas fa-user-plus'></i> <span>Usuarios</span><span class='pull-right-container'></span></a></li>";
                }

                echo"<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-receipt'></i> <span>Cuentas por pagar</span><span class='pull-right-container'>"; 
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo "<li><a href='".site_url("Cuentasxp/nuevas")."'><i class='fas fa-circle'></i> <span>Solicitudes nuevas</span><span class='pull-right-container'></span></a></li>";                
                echo "<li><a href='".site_url("Cuentasxp")."'><i class='fas fa-circle'></i> <span>Pagos</span><span class='pull-right-container'></span></a></li>";  
                echo "<li><a href='".site_url("Cuentasxp/vista_confirmar")."'><i class='fas fa-circle'></i> <span>Confirmación de pagos</span><span class='pull-right-container'></span></a></li>";
                echo "</ul></li>";
                echo"<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-file'></i> <span>Facturas</span><span class='pull-right-container'>";
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo "<li><a href='#' data-toggle='modal' data-target='#modal_reporte_falta_provision'><i class='fas fa-circle'></i> <span>Relación sin provisión</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Complementos_cxp/etiquetas_facturas")."'><i class='fas fa-circle'></i><span> Clasificación Facturas</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Complementos_cxp")."'><i class='fas fa-circle'></i> <span>Facturas faltantes</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Complementos_cxp/gastos_comprobados")."'><i class='fas fa-circle'></i> <span>Facturas comprobadas</span><span class='pull-right-container'></span></a></li>";  
                echo "<li><a href='".site_url("Descargar_XML")."'><i class='fas fa-circle'></i> <span>Descarga masiva</span><span class='pull-right-container'></span></a></li>";
                echo "</ul></li>";

                echo"<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-list-ul'></i> <span>Historial</span><span class='pull-right-container'>";
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo "<li><a href='".site_url("Historial")."'><i class='fas fa-circle'></i> <span>Solicitudes</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial/historial_pagos")."'><i class='fas fa-circle'></i> <span>Pagos</span><span class='pull-right-container'></span></a></li>";  
                echo "<li><a href='".site_url("Historial/historialcch")."'><i class='fas fa-circle'></i> <span>Cajas chicas</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial/historialtdc")."'><i class='fas fa-circle'></i> <span>T. Crédito</span><span class='pull-right-container'></span></a></li>"; 
                echo "<li><a href='".site_url("Historial_cheques")."'><i class='fas fa-circle'></i> <span>Cheques</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial/historial_autorizacion")."'><i class='fas fa-circle'></i> <span>Autorizaciones</span><span class='pull-right-container'></span></a></li>"; 
                echo "<li><a href='".site_url("Historial/factorajes")."'><i class='fas fa-circle'></i> <span>Factorajes</span><span class='pull-right-container'></span></a></li>"; 
                echo "<li><a href='".site_url("Historial/p_programados")."'><i class='fas fa-circle'></i> <span>Programados</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial/historialreembolsos")."'><i class='fas fa-circle'></i> <span>Reembolsos</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial/historialViaticos")."'><i class='fas fa-circle'></i> <span>Viáticos</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial/historial_pagos_parcialidades")."'><i class='fas fa-circle'></i> <span>Parcialidades</span><span class='pull-right-container'></span></a></li>";  /**  Visualización dentro del menú para el historial de las devoluciones por parcialidades | FECHA: 16-Mayo-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
                echo "</ul></li>";

                echo"<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-search'></i> <span>Buscar solicitud</span><span class='pull-right-container'>";
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo"<li><form action='#'><div class='input-group'><input id='idsol' type='text' class='form-control' required>";
                echo"<div class='input-group-btn'><button class='btn btn-default consultar_modal' value='' data-value=''><i class='fas fa-search'></i></button></div></div></form></li>";
                echo"<li><a href='#'><label class='radio-inline'><input type='radio' name='idopt' value='SOL' checked># Solicitud</label><label class='radio-inline'><input type='radio' name='idopt' value='FACU'># UUID</label></a></li>";
                echo "</ul></li>";
              break;
              case 'DG':
                echo"<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-user-check'></i> <span>Dirección general</span><span class='pull-right-container'>";
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo "<li><a href='".site_url("DireccionGeneral")."'><i class='fas fa-circle'></i> <span>Autorizaciones de compra</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("DireccionGeneral/index_p")."'><i class='fas fa-circle'></i> <span>Autorizaciones todos</span><span class='pull-right-container'></span></a></li>";                
                echo "<li><a href='".site_url("DireccionGeneral/tarjetas_credito")."'><i class='fas fa-circle'></i> <span>Tarjetas de Crédito</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("DireccionGeneral/OtrosGastos")."'><i class='fas fa-circle'></i> <span>Otros</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("DireccionGeneral/CajaChica")."'><i class='fas fa-circle'></i> <span>Cajas chicas</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("DireccionGeneral/Reembolsos")."'><i class='fas fa-circle'></i> <span>Reembolsos</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("DireccionGeneral/viaticos")."'><i class='fas fa-circle'></i> <span>Viáticos</span><span class='pull-right-container'></span></a></li>";
                echo "</ul></li>";
                echo "<li><a href='".site_url("Ventas")."' target='popup' onclick='window.open('".site_url("Ventas")."','popup'); return false;'><i class='fas fa-chart-area'></i> <span>Dashboard Comercial</span><span class='pull-right-container'></span></a></li>";
                echo"<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-list-ul'></i> <span>Historial</span><span class='pull-right-container'>";
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo "<li><a href='".site_url("Historial")."'><i class='fas fa-circle'></i> <span>Solicitudes</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial/historial_pagos")."'><i class='fas fa-circle'></i> <span>Pagos</span><span class='pull-right-container'></span></a></li>";  
                echo "<li><a href='".site_url("Historial/historialcch")."'><i class='fas fa-circle'></i> <span>Cajas chicas</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial/historialtdc")."'><i class='fas fa-circle'></i> <span>T. Crédito</span><span class='pull-right-container'></span></a></li>";  
                echo "<li><a href='".site_url("Historial/p_programados")."'><i class='fas fa-circle'></i> <span>Programados</span><span class='pull-right-container'></span></a></li>"; 
                echo "</ul></li>";
              break;
              case 'DP':
                echo "<li><a href='".site_url("Movimientos_BB")."'><i class='fa fa-piggy-bank'></i> <span>Mov. Banbajio</span><span class='pull-right-container'></span></a></li>";
                echo"<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-user-check'></i> <span>Dirección general</span><span class='pull-right-container'>";
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo "<li><a href='".site_url("DireccionGeneral")."'><i class='fas fa-circle'></i> <span>Autorizaciones de compra</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("DireccionGeneral/index_p")."'><i class='fas fa-circle'></i> <span>Autorizaciones de pago</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("DireccionGeneral/tarjetas_credito")."'><i class='fas fa-circle'></i> <span>Tarjetas de Crédito</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("DireccionGeneral/OtrosGastos")."'><i class='fas fa-circle'></i> <span>Otros</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("DireccionGeneral/CajaChica")."'><i class='fas fa-circle'></i> <span>Cajas chicas</span><span class='pull-right-container'></span></a></li>";
                echo "</ul></li>";
                echo "<li><a href='".site_url("Dispersion")."'><i class='fas fa-money-check-alt'></i> <span>Dispersión de pagos</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Saldos")."'><i class='fa fa-folder'></i> <span>Efectivo</span><span class='pull-right-container'></span></a></li>";
                echo"<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-list-ul'></i> <span>Historial</span><span class='pull-right-container'>";
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo "<li><a href='".site_url("Historial/historial_autorizacion")."'><i class='fas fa-circle'></i> <span>Autorizaciones</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial/historialcch")."'><i class='fas fa-circle'></i> <span>Cajas chicas</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial/historialtdc")."'><i class='fas fa-circle'></i> <span>T. Crédito</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial_cheques")."'><i class='fas fa-circle'></i> <span>Cheques</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial/historial_pagos")."'><i class='fas fa-circle'></i> <span>Pagos</span><span class='pull-right-container'></span></a></li>"; 
                echo "<li><a href='".site_url("Historial")."'><i class='fas fa-circle'></i> <span>Solicitudes</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial/p_programados")."'><i class='fas fa-circle'></i> <span>Programados</span><span class='pull-right-container'></span></a></li>"; 
                echo "</ul></li>";
              break;
              case 'SB':
                echo"<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-user-check'></i> <span>Dirección general</span><span class='pull-right-container'>";
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo "<li><a href='".site_url("DireccionGeneral")."'><i class='fas fa-circle'></i> <span>Pagos proveedor</span><span class='pull-right-container'></span></a></li>";
                //echo "<li><a href='".site_url("DireccionGeneral/Factoraje")."'><i class='fas fa-circle'></i> <span>Pagos Factoraje</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("DireccionGeneral/OtrosGastos")."'><i class='fas fa-circle'></i> <span>Otros</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("DireccionGeneral/CajaChica")."'><i class='fas fa-circle'></i> <span>Cajas chicas</span><span class='pull-right-container'></span></a></li>";
                echo "</ul></li>"; 
                //echo "<li><a href='".site_url("Capturistas")."'><i class='fas fa-user-plus'></i> <span>Agregar Capturista</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Solicitante")."'><i class='far fa-edit'></i> <span>Solicitudes de pago</span><span class='pull-right-container'></span></a></li>";
              break;
              case 'CI':
                if( in_array( $this->session->userdata("inicio_sesion")['depto'], ['CONTRALORIA' ] ) && in_array($this->session->userdata('inicio_sesion')["id"], array('2815', '2514', '2613', '2109', '2513', '2892', '3177'))){
                  echo "<li><a href='".site_url("Complementos_cxp")."'><i class='fas fa-file'></i> <span>Facturas faltantes</span><span class='pull-right-container'></span></a></li>";
                  echo "<li><a href='".site_url("Solicitante")."'><i class='far fa-edit'></i> <span>Solicitudes de pago</span><span class='pull-right-container'></span></a></li>";
                }
                if( in_array( $this->session->userdata("inicio_sesion")['depto'], ['CONTRALORIA' ] ) && in_array($this->session->userdata('inicio_sesion')["id"], array('2668', '2707')) ){
                  echo"<li class='treeview'><a href='#'>";
                  echo "<i class='fas fa-hand-holding-usd'></i> <span>Dev. y Traspasos</span><span class='pull-right-container'>";
                  echo "<i class='fa fa-angle-left pull-right'></i></span>";
                  echo "</a><ul class='treeview-menu'>";
                  echo "<li><a href='".site_url("Devoluciones_Traspasos/Devolucion")."'><i class='fas fa-circle'></i> <span>Devoluciones</span><span class='pull-right-container'></span></a></li>";
                  echo "<li><a href='".site_url("Devoluciones_Traspasos/Histdevolucionesytraspasos")."'><i class='fas fa-circle'></i> <span>Histórico</span><span class='pull-right-container'></span></a></li>";
                  echo "</ul></li>";

                  echo "<li><a href='".site_url("Complementos_cxp")."'><i class='fas fa-file'></i> <span>Facturas faltantes</span><span class='pull-right-container'></span></a></li>";
                  echo "<li><a href='".site_url("Solicitante")."'><i class='far fa-edit'></i> <span>Solicitudes de pago</span><span class='pull-right-container'></span></a></li>";
                }else{
                  echo "<li><a href='".site_url("Devoluciones_Traspasos/Devolucion")."'><i class='fas fa-hand-holding-usd'></i> <span>Devoluciones</span><span class='pull-right-container'></span></a></li>";
                  // echo "<li><a href='".site_url("Contraloria")."'><i class='fas fa-hand-holding-usd'></i> <span>Devoluciones</span><span class='pull-right-container'></span></a></li>";
                }
                echo "<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-list-ul'></i> <span>Historial</span><span class='pull-right-container'>";
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo "<li><a href='".site_url("Historial")."'><i class='fas fa-list-ul'></i> <span>Historial</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial/historial_pagos_parcialidades")."'><i class='fas fa-circle'></i> <span>Parcialidades</span><span class='pull-right-container'></span></a></li>"; 
                echo "</ul></li>";
                break;
              case 'SU':
                echo "<li><a href='".site_url("Movimientos_BB")."'><i class='fa fa-piggy-bank'></i> <span>Mov. Banbajio</span><span class='pull-right-container'></span></a></li>";
                echo"<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-user-check'></i> <span>Dirección general</span><span class='pull-right-container'>";
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo "<li><a href='".site_url("DireccionGeneral")."'><i class='fas fa-circle'></i> <span>Autorizaciones de compra</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("DireccionGeneral/index_p")."'><i class='fas fa-circle'></i> <span>Autorizaciones de pago</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("DireccionGeneral/tarjetas_credito")."'><i class='fas fa-circle'></i> <span>Tarjetas de Crédito</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Devoluciones_Traspasos/Procesos")."'><i class='fas fa-circle'></i> <span>Devoluciones y traspasos</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("DireccionGeneral/OtrosGastos")."'><i class='fas fa-circle'></i> <span>Otros</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("DireccionGeneral/CajaChica")."'><i class='fas fa-circle'></i> <span>Cajas chicas</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("DireccionGeneral/Reembolsos")."'><i class='fas fa-circle'></i> <span>Reembolsos</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("DireccionGeneral/viaticos")."'><i class='fas fa-circle'></i> <span>Viáticos</span><span class='pull-right-container'></span></a></li>";
                echo "</ul></li>";
                echo "<li><a href='".site_url("Dispersion")."'><i class='fas fa-money-check-alt'></i> <span>Dispersión de pagos</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Saldos")."'><i class='fa fa-folder'></i> <span>Efectivo</span><span class='pull-right-container'></span></a></li>";

                if($this->session->userdata('inicio_sesion')["id"]=='257'){ 
                  echo"<li class='treeview'><a href='#'>";
                  echo "<i class='fas fa-toolbox'></i> <span>Catálogos</span><span class='pull-right-container'>";
                  echo "<i class='fa fa-angle-left pull-right'></i></span>";
                  echo "</a><ul class='treeview-menu'>";
                  echo "<li><a href='".site_url("Bancos_cxp")."'><i class='fas fa-circle'></i> <span>Bancos</span><span class='pull-right-container'></span></a></li>";  
                  echo "<li><a href='".site_url("Provedores_cxp")."'><i class='fas fa-circle'></i> <span>Proveedores</span><span class='pull-right-container'></span></a></li>";
                  echo "<li><a href='".site_url("tarjetas_credito")."'><i class='fas fa-circle'></i> <span>Tarjetas de Crédito</span><span class='pull-right-container'></span></a></li>";
                  echo "<li><a href='".site_url("Provedores_cxp/datosfiscales_prov")."'><i class='fas fa-circle'></i> <span>Datos Fiscales Proveedores</span><span class='pull-right-container'></span></a></li>";
                  echo "</ul></li>";

                  echo"<li class='treeview'><a href='#'>";
                  echo "<i class='fas fa-receipt'></i> <span>Cuentas por pagar</span><span class='pull-right-container'>";
                  echo "<i class='fa fa-angle-left pull-right'></i></span>";
                  echo "</a><ul class='treeview-menu'>";
                  echo "<li><a href='".site_url("Cuentasxp/nuevas")."'><i class='fas fa-circle'></i> <span>Solicitudes nuevas</span><span class='pull-right-container'></span></a></li>"; 
                 
                  echo "<li><a href='".site_url("Cuentasxp")."'><i class='fas fa-circle'></i> <span>Pagos</span><span class='pull-right-container'></span></a></li>";  
                  echo "<li><a href='".site_url("Cuentasxp/vista_confirmar")."'><i class='fas fa-circle'></i> <span>Confirmación de pagos</span><span class='pull-right-container'></span></a></li>";
                  echo "</ul></li>";
                  echo "<li><a href='".site_url("Cajas_ch")."'><i class='fa fa-briefcase'></i> <span>Cajas chicas</span><span class='pull-right-container'></span></a></li>";
                }

                echo "<li><a href='".site_url("Solicitante")."'><i class='far fa-edit'></i> <span>Solicitudes de pago</span><span class='pull-right-container'></span></a></li>";
                echo"<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-list-ul'></i> <span>Historial</span><span class='pull-right-container'>";
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo "<li><a href='".site_url("Historial/historial_autorizacion")."'><i class='fas fa-circle'></i> <span>Autorizaciones</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial/historialcch")."'><i class='fas fa-circle'></i> <span>Cajas Chicas Pagadas</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial/historialtdc")."'><i class='fas fa-circle'></i> <span>T. Crédito</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial_cheques")."'><i class='fas fa-circle'></i> <span>Cheques</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial/historial_pagos")."'><i class='fas fa-circle'></i> <span>Pagos</span><span class='pull-right-container'></span></a></li>"; 
                echo "<li><a href='".site_url("Historial")."'><i class='fas fa-circle'></i> <span>Solicitudes</span><span class='pull-right-container'></span></a></li>";  
                echo "<li><a href='".site_url("Historial/factorajes")."'><i class='fas fa-circle'></i> <span>Factorajes</span><span class='pull-right-container'></span></a></li>"; 
                echo "<li><a href='".site_url("Historial/p_programados")."'><i class='fas fa-circle'></i> <span>Programados</span><span class='pull-right-container'></span></a></li>"; 
                if (in_array( $this->session->userdata("inicio_sesion")['id'], [257]) ) {
                  echo "<li><a href='".site_url("Historial/historialViaticos")."'><i class='fas fa-circle'></i> <span>Viáticos</span><span class='pull-right-container'></span></a></li>";
                }
                echo "<li><a href='".site_url("Historial/historial_pagos_parcialidades")."'><i class='fas fa-circle'></i> <span>Parcialidades</span><span class='pull-right-container'></span></a></li>"; 
                echo "</ul></li>";
                echo "<li><a href='".site_url("Complementos_cxp")."'><i class='fas fa-file'></i> <span>Facturas Faltantes</span><span class='pull-right-container'></span></a></li>";

                echo"<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-search'></i> <span>Buscar solicitud</span><span class='pull-right-container'>";
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo"<li><form action='#'><div class='input-group'><input id='idsol' type='text' class='form-control' required>";
                echo"<div class='input-group-btn'><button class='btn btn-default consultar_modal' value='' data-value=''><i class='fas fa-search'></i></button></div></div></form></li>";
                echo"<li><a href='#'><label class='radio-inline'><input type='radio' name='idopt' value='SOL' checked># Solicitud</label><label class='radio-inline'><input type='radio' name='idopt' value='FACU'># UUID</label></a></li>";
                echo "</ul></li>";

                break;

              case 'CS':
                echo "<li><a href='".site_url("Invitado/bloquear_proveedores")."'><i class='fas fa-money-check'></i> <span>Bloquear Proveedores</span><span class='pull-right-container'></span></a></li>";
                echo"<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-list-ul'></i> <span>Historial</span><span class='pull-right-container'>";
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo "<li><a href='".site_url("Invitado")."'><i class='fas fa-circle'></i> <span>Solicitudes</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Invitado/historial_pagos")."'><i class='fas fa-circle'></i> <span>Pagos</span><span class='pull-right-container'></span></a></li>";  
                echo "<li><a href='".site_url("Invitado/historialcch")."'><i class='fas fa-circle'></i> <span>Cajas Chicas Pagadas</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial/historialtdc")."'><i class='fas fa-circle'></i> <span>T. Crédito</span><span class='pull-right-container'></span></a></li>"; 
                echo "<li><a href='".site_url("Invitado/factorajes")."'><i class='fas fa-circle'></i> <span>Factorajes</span><span class='pull-right-container'></span></a></li>"; 
                echo "<li><a href='".site_url("Invitado/p_programados")."'><i class='fas fa-circle'></i> <span>Programados</span><span class='pull-right-container'></span></a></li>";  
                echo "</ul></li>";
                break;
              case 'CE':
                /*
                echo"<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-hand-holding-usd'></i> <span>Dev. y Traspasos</span><span class='pull-right-container'>";
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo "<li><a href='".site_url("Devoluciones_Traspasos/Devolucion")."'><i class='fas fa-circle'></i> <span>Devoluciones</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Devoluciones_Traspasos/Histdevolucionesytraspasos")."'><i class='fas fa-circle'></i> <span>Histórico</span><span class='pull-right-container'></span></a></li>";
                echo "</ul></li>";
                */  
              case 'AD':
              case 'PV':
              case 'SPV':
              case 'GAD':
              case 'CAD':
              case 'CPV':
              case 'AOO':
              case 'COO':
              case 'IOO':
              case 'SAC':
              case 'PVM':
              	if($this->session->userdata('inicio_sesion')["id"]=='1835'){
                  echo"<li class='treeview'><a href='#'>";
                  echo "<i class='fas fa-user-check'></i> <span>Dirección general</span><span class='pull-right-container'>";
                  echo "<i class='fa fa-angle-left pull-right'></i></span>";
                  echo "</a><ul class='treeview-menu'>";
                  echo "<li><a href='".site_url("Devoluciones_Traspasos/Procesos")."'><i class='fas fa-circle'></i> <span>Devoluciones y traspasos</span><span class='pull-right-container'></span></a></li>";
                  echo "</ul></li>";
                }
                echo "<li><a href='".site_url("Devoluciones_Traspasos/Devolucion")."'><i class='fas fa-hand-holding-usd'></i> <span>Devoluciones</span><span class='pull-right-container'></span></a></li>";
                break;
              case 'DIO':
                echo "<li><a href='".site_url("Devoluciones_Traspasos/Devolucion")."'><i class='fas fa-hand-holding-usd'></i> <span>Captura Devoluciones</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Devoluciones_Traspasos/Procesos")."'><i class='fas fa-receipt'></i> <span>Autorizar Devoluciones</span><span class='pull-right-container'></span></a></li>";
                break;
            }

            if( in_array( $this->session->userdata('inicio_sesion')["rol"], array( 'FP' ) ) && in_array( $this->session->userdata("inicio_sesion")['depto'], array( 'CONTABILIDAD', 'CAPITAL HUMANO' ) ) ){
              if( in_array( $this->session->userdata('inicio_sesion')["rol"], array( 'FP' ) ) )
                echo "<li><a href='".site_url("Solicitante")."'><i class='far fa-edit'></i> <span>Solicitudes de pago</span><span class='pull-right-container'></span></a></li>";
              echo "<li><a href='".site_url("Solicitante/otros")."'><i class='fas fa-file-invoice'></i> <span>Otras solicitudes</span><span class='pull-right-container'></span></a></li>";
            }
            //MENU HISTORIAL SIMPLE Se agrego CS
            if( !in_array( $this->session->userdata('inicio_sesion')["rol"], array( 'CI', 'CP', 'DG', 'DP', 'CT', 'SU', 'CC', 'CX', 'CS', 'FP', 'AD','CAD', 'PV','SPV','CPV', 'GAD', 'DIO', 'SAC', 'IOO', 'COO', 'AOO') )){
              if(!in_array( $this->session->userdata('inicio_sesion')["id"], $idusuarios_nopermitidos)){ /**  /**  Visualización dentro del menú para el historial de las devoluciones por parcialidades | FECHA: 16-Mayo-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
                if( in_array( $this->session->userdata("inicio_sesion")['depto'], ['POST VENTA', 'CONTRALORIA'] ) ){
                  echo "<li class='treeview'><a href='#'>";
                  echo "<i class='fas fa-list-ul'></i> <span>Historial</span><span class='pull-right-container'>";
                  echo "<i class='fa fa-angle-left pull-right'></i></span>";
                  echo "</a><ul class='treeview-menu'>";
                  echo "<li><a href='".site_url("Historial")."'><i class='fas fa-list-ul'></i> <span>Historial</span><span class='pull-right-container'></span></a></li>";
                  echo "<li><a href='".site_url("Historial/historial_pagos_parcialidades")."'><i class='fas fa-circle'></i> <span>Parcialidades</span><span class='pull-right-container'></span></a></li>"; 
                  echo "</ul></li>";
                }
                else if(in_array( $this->session->userdata("inicio_sesion")['id'], [2483])){
                  echo "<li class='treeview'><a href='#'>";
                  echo "<i class='fas fa-list-ul'></i> <span>Historial</span><span class='pull-right-container'>";
                  echo "<i class='fa fa-angle-left pull-right'></i></span>";
                  echo "</a><ul class='treeview-menu'>";
                  echo "<li><a href='".site_url("Historial")."'><i class='fas fa-list-ul'></i> <span>Historial</span><span class='pull-right-container'></span></a></li>";
                  echo "<li><a href='".site_url("Historial/historialViaticos")."'><i class='fas fa-circle'></i> <span>Viáticos</span><span class='pull-right-container'></span></a></li>"; 
                  echo "</ul></li>";
                }
                else if($this->session->userdata("inicio_sesion")['rol'] == 'CE'){
                  echo "<li class='treeview'><a href='#'>";
                  echo "<i class='fas fa-list-ul'></i> <span>Historial</span><span class='pull-right-container'>";
                  echo "<i class='fa fa-angle-left pull-right'></i></span>";
                  echo "</a><ul class='treeview-menu'>";
                  echo "<li><a href='".site_url("Historial")."'><i class='fas fa-list-ul'></i> <span>Historial</span><span class='pull-right-container'></span></a></li>";
                  echo "<li><a href='".site_url("Historial/historial_pagos_parcialidades")."'><i class='fas fa-circle'></i> <span>Parcialidades</span><span class='pull-right-container'></span></a></li>";  /**  FIN visualización dentro del menú para el historial de las devoluciones por parcialidades | FECHA: 16-Mayo-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
                  echo "</ul></li>";
                }
                else if($this->session->userdata("inicio_sesion")['id'] == 302){ //@uthor Efrain Martinez Muñoz | Fecha: 29/10/2024
                  echo"<li class='treeview'><a href='#'>";                                                                                     //Se creo esta condición para permitir que en el panel de 
                  echo "<i class='fas fa-list-ul'></i> <span>Historial</span><span class='pull-right-container'>";                             //Liliana Chan en el apartado de historial se muestren dos menus     
                  echo "<i class='fa fa-angle-left pull-right'></i></span>";                                                                   //"Solicitudes" y "Dev. y Trasp."
                  echo "</a><ul class='treeview-menu'>";
                  echo "<li><a href='".site_url("Historial")."'><i class='fas fa-circle'></i> <span>Solicitudes</span><span class='pull-right-container'></span></a></li>";
                  echo "<li><a href='".site_url("Devoluciones_Traspasos/Histdevolucionesytraspasos")."'><i class='fas fa-hand-holding-usd'></i></i> <span>Dev. y Trasp.</span><span class='pull-right- container'></span></a></li>";
                  echo "</ul></li>";
                }
                else
                  echo "<li><a href='".site_url("Historial")."'><i class='fas fa-list-ul'></i> <span>Historial</span><span class='pull-right-container'></span></a></li>";
              }
            }elseif( in_array( $this->session->userdata('inicio_sesion')["rol"], array( 'FP', 'AD' ,'PV','SPV','CAD','CPV','GAD','DIO', 'SAC', 'IOO', 'COO', 'AOO') ) ){
              echo "<li class='treeview'><a href='#'>";
              echo "<i class='fas fa-list-ul'></i> <span>Historial</span><span class='pull-right-container'>";
              echo "<i class='fa fa-angle-left pull-right'></i></span>";
              echo "</a><ul class='treeview-menu'>";
              echo "<li><a href='".site_url("Historial")."'><i class='fas fa-circle'></i> <span>Solicitudes</span><span class='pull-right-container'></span></a></li>";
              echo "<li><a href='".site_url("Historial/historial_pagos")."'><i class='fas fa-circle'></i> <span>Pagos</span><span class='pull-right-container'></span></a></li>"; 
              echo "<li><a href='".site_url("Historial/historial_pagos_parcialidades")."'><i class='fas fa-circle'></i> <span>Parcialidades</span><span class='pull-right-container'></span></a></li>";  /**  FIN visualización dentro del menú para el historial de las devoluciones por parcialidades | FECHA: 16-Mayo-2024 | @author Lalo Silva <programador.analista38@ciudadmaderas.com>  **/
              echo "</ul></li>";
            }
            
            if((in_array($this->session->userdata('inicio_sesion')["rol"], array('DA')) && in_array($this->session->userdata('inicio_sesion')["id"], array(1868, 2665))) || ($this->session->userdata('inicio_sesion')["id"] == 2685)){
              echo"<li class='treeview'><a href='#'>";
                echo "<i class='fas fa-list-ul'></i> <span>Historial</span><span class='pull-right-container'>";
                echo "<i class='fa fa-angle-left pull-right'></i></span>";
                echo "</a><ul class='treeview-menu'>";
                echo "<li><a href='".site_url("Historial")."'><i class='fas fa-circle'></i> <span>Solicitudes</span><span class='pull-right-container'></span></a></li>";
                echo "<li><a href='".site_url("Historial/p_programados")."'><i class='fas fa-circle'></i> <span>Programados</span><span class='pull-right-container'></span></a></li>";
                echo "</ul></li>";
            }

            if( ( in_array( $this->session->userdata('inicio_sesion')["rol"], array( 'DA', 'PV','SPV','CPV') ) && 
                    in_array($this->session->userdata("inicio_sesion")['depto'], array("CONSTRUCCION") )  ) || 
                ( in_array( $this->session->userdata('inicio_sesion')["rol"], array( 'AS' ) ) && 
                    in_array( $this->session->userdata("inicio_sesion")['id'], array( '99', '51' ) ) ) ||
                (in_array( $this->session->userdata('inicio_sesion')["rol"], array( 'CA' ) ) && 
                    in_array( $this->session->userdata("inicio_sesion")['id'], array( '1844' ) )) ){
              echo "<li><a href='".site_url("Contratos")."'><i class='fas fa-file-contract'></i> <span>Contratos</span><span class='pull-right-container'></span></a></li>";
            }
            if( $this->session->userdata("inicio_sesion")['depto'] == 'COMPRAS'  ){
              if( in_array( $this->session->userdata("inicio_sesion")['id'], array( 177, 1844, 1855 ) ) ) {
                echo "<li><a href='".site_url("DireccionGeneral/Compras_autorizadas")."'> <i class=\"fas fa-money-bill\"></i> <span>Compras autorizadas</span><span class='pull-right-container'></span></a></li>";
                //echo "<li><a href='".site_url("DireccionGeneral/Compras")."'><i class='fas fa-credit-card'></i> <span>Autorizaciones de compra</span><span class='pull-right-container'></span></a></li>";
              }
              /*if( in_array( $this->session->userdata('inicio_sesion')["rol"], array( 'DA' ) ) || $this->session->userdata('inicio_sesion')["im"] == 9 )
                echo "<li><a href='".site_url("Provedores_cxp/ProductosXprov/")."'><i class=\"fas fa-concierge-bell\"></i> <span>Productos - Proveedor</span><span class='pull-right-container'></span></a></li>";*/
            }
            if( $this->session->userdata("inicio_sesion")['depto'] == 'CI-COMPRAS' &&  in_array($this->session->userdata("inicio_sesion")['id'], array(2259)) ){
                echo "<li><a href='".site_url("DireccionGeneral/Compras_autorizadas")."'> <i class=\"fas fa-money-bill\"></i> <span>Compras autorizadas</span><span class='pull-right-container'></span></a></li>";
            }

            /** INICIO FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
            if (in_array( $this->session->userdata("inicio_sesion")['id'], array( '2636' ) )) { 
              echo "<hr>";
              echo"<li class='treeview'><a href='#'>";
              echo "<i class='fas fa-user-check'></i> <span>Dirección general</span><span class='pull-right-container'>";
              echo "<i class='fa fa-angle-left pull-right'></i></span>";
              echo "</a><ul class='treeview-menu'>";
              echo "<li><a href='".site_url("DireccionGeneral")."'><i class='fas fa-circle'></i> <span>Autorizaciones de compra</span><span class='pull-right-container'></span></a></li>";
              echo "<li><a href='".site_url("DireccionGeneral/index_p")."'><i class='fas fa-circle'></i> <span>Autorizaciones todos</span><span class='pull-right-container'></span></a></li>";                
              echo "<li><a href='".site_url("DireccionGeneral/tarjetas_credito")."'><i class='fas fa-circle'></i> <span>Tarjetas de Crédito</span><span class='pull-right-container'></span></a></li>";
              echo "<li><a href='".site_url("DireccionGeneral/OtrosGastos")."'><i class='fas fa-circle'></i> <span>Otros</span><span class='pull-right-container'></span></a></li>";
              echo "<li><a href='".site_url("DireccionGeneral/CajaChica")."'><i class='fas fa-circle'></i> <span>Cajas chicas</span><span class='pull-right-container'></span></a></li>";
              echo "<li><a href='".site_url("DireccionGeneral/Reembolsos")."'><i class='fas fa-circle'></i> <span>Reembolsos</span><span class='pull-right-container'></span></a></li>";
              echo "<li><a href='".site_url("DireccionGeneral/viaticos")."'><i class='fas fa-circle'></i> <span>Viáticos</span><span class='pull-right-container'></span></a></li>";
              echo "</ul></li>";
              echo "<li><a href='".site_url("Ventas")."' target='popup' onclick='window.open('".site_url("Ventas")."','popup'); return false;'><i class='fas fa-chart-area'></i> <span>Dashboard Comercial</span><span class='pull-right-container'></span></a></li>";
              echo "<hr>";
            }
            /** FIN FECHA: 17-DICIEMBRE-2024 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

            echo "<li><a href='#' id='cambiar_password'><i class='fas fa-key'></i> <span>Cambiar contraseña</span><span class='pull-right-container'></span></a></li>";
          ?>
          <li><a href="<?=site_url("Home/cerrar_sesion")?>"><i class='fas fa-sign-out-alt'></i> <span>Cerrar sesión</span><span class='pull-right-container'></span></a></li>
      </ul>
    </section>
  </aside>
  <div class="content-wrapper seccion-principal">
    <section class="content">
                

