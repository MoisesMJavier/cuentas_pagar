<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<head>
    <html lang="es_mx">

    <title>CUENTAS POR PAGAR</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta http-equiv="Expires" content="0">
	<meta http-equiv="Last-Modified" content="0">
	<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
	<meta http-equiv="Pragma" content="no-cache">
    
    <!--link rel="icon" type="image/png" href="<?= base_url('img/favicon.png');?>"-->
    
    <link rel="stylesheet" type="text/css" href="<?= base_url("css/bootstrap.css")?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url("css/bootstrap.min.css")?>">
    <!-- <link rel="stylesheet" type="text/css" href="<?= base_url("css/font-awesome.min.css")?>"> -->
    <link rel="stylesheet" type="text/css" href="<?= base_url("css/all.css")?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url("css/ionicons.min.css")?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url("css/AdminLTE.min.css")?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url("css/skins/_all-skins.min.css")?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url("css/morris.css")?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url("css/jquery-jvectormap.css")?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url("css/jquery-ui.min.css")?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url("css/bootstrap-datepicker.min.css")?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url("css/bootstrap-datetimepicker.min.css")?>">    
    <link rel="stylesheet" type="text/css" href="<?= base_url("css/daterangepicker.css")?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url("css/bootstrap-timepicker.min.css")?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url("css/bootstrap3-wysihtml5.min.css")?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url("css/dataTables.bootstrap.min.css")?>">
    <!-- <link rel="stylesheet" type="text/css" href="<?= base_url("css/pace.min.css")?>"> -->
    <link rel="stylesheet" type="text/css" href="<?= base_url("css/utilidades.css")?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url("css/select2.min.css")?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url("css/bootstrap-multiselect.css")?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!--**LIBRERIAS AGREGADAS EL DIA 05/MARZO/2024 POR DANTE ALDAIR**-->
    <!--**********************************************************************-->
    <link rel="stylesheet" type="text/css" href="<?= base_url("css/bootstrap-selectpicker.min.css")?>">
    <!--**********************************************************************-->
    
    <script type="text/javascript" src="<?= base_url("js/src/elem-cxp.js")?>"></script>
    <script type="text/javascript" src="<?= base_url("js/url.js")?>"></script>
	<script type="text/javascript" src="<?= base_url("js/jquery.js");?>"></script>
	<script type="text/javascript" src="<?= base_url("js/jquery-ui.min.js")?>"></script>
	<script type="text/javascript" src="<?= base_url("js/bootstrap.min.js")?>"></script>
	<script type="text/javascript" src="<?= base_url("js/raphael.min.js")?>"></script>
	<script type="text/javascript" src="<?= base_url("js/morris.min.js")?>"></script>
	<script type="text/javascript" src="<?= base_url("js/jquery.sparkline.min.js")?>"></script>
	<script type="text/javascript" src="<?= base_url("js/jquery-jvectormap-1.2.2.min.js")?>"></script>
	<script type="text/javascript" src="<?= base_url("js/jquery-jvectormap-world-mill-en.js")?>"></script>
	<script type="text/javascript" src="<?= base_url("js/jquery.knob.min.js")?>"></script>
	<script type="text/javascript" src="<?= base_url("js/min/moment.min.js")?>"></script>
    <script type="text/javascript" src="<?= base_url("js/es.js")?>"></script>
	<script type="text/javascript" src="<?= base_url("js/daterangepicker.js")?>"></script>
	<script type="text/javascript" src="<?= base_url("js/bootstrap-datepicker.js")?>"></script>
    <script type="text/javascript" src="<?= base_url("js/bootstrap-datepicker.es.min.js")?>"></script>
    <script type="text/javascript" src="<?= base_url("js/bootstrap-timepicker.min.js")?>"></script>      
	<script type="text/javascript" src="<?= base_url("js/bootstrap3-wysihtml5.all.min.js")?>"></script>
	<script type="text/javascript" src="<?= base_url("js/jquery.slimscroll.min.js")?>"></script>
	<script type="text/javascript" src="<?= base_url("js/fastclick.js")?>"></script>
	<script type="text/javascript" src="<?= base_url("js/adminlte.min.js")?>"></script>
	<!--script type="text/javascript" src="<?= base_url("js/dashboard.js")?>"></script-->
    <script type="text/javascript" src="<?= base_url("js/pace.min.js")?>"></script>
	<script type="text/javascript" src="<?= base_url("js/demo.js")?>"></script>
	<script type="text/javascript" src="<?= base_url("js/jquery.inputmask.js")?>"></script>
    <script type="text/javascript" src="<?= base_url("js/jquery.inputmask.date.extensions.js")?>"></script>
    <script type="text/javascript" src="<?= base_url("js/jquery.inputmask.extensions.js")?>"></script>
    <script type="text/javascript" src="<?= base_url("js/jquery.blockUI.js")?>"></script>
    <script type="text/javascript" src="<?= base_url("js/Chart.js")?>"></script>
    <script type="text/javascript" src="<?= base_url("js/bootstrap-multiselect.js")?>"></script>

    <!--PACK COMPLETO DE DATATABLES EN WEB SITE TODO JUNTO PARA QUE FUNCIONE CORRECTAMENTE-->
    <script type="text/javascript" src="<?= base_url("js/datatables.min.js")?>"></script>
    <!-- <script type="text/javascript" src="https://editor.datatables.net/extensions/Editor/js/dataTables.editor.min.js"></script> -->
    <script type="text/javascript" src="<?= base_url("js/pdfmake.min.js")?>"></script>
    <script type="text/javascript" src="<?= base_url("js/vfs_fonts.js")?>"></script>
    <script type="text/javascript" src="<?= base_url("js/buttons.print.min.js")?>"></script>
    <!---->


    <script type="text/javascript" src="<?= base_url("js/jquery.validate.js");?>"></script>
    <script type="text/javascript" src="<?= base_url("js/bootstrap-datetimepicker.min.js")?>"></script>
    <script type="text/javascript" src="<?= base_url("js/notify.js");?>"></script>

    <!--AUTOCOMPLE SELECT-->
    <script type="text/javascript" src="<?= base_url("js/select2.full.min.js")?>"></script>

    <!--ALERTAS PERSONALIZADAS-->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    
    <!--MIS SCRIPS PARA LA PAGINA-->
    <script src="<?= base_url("js/jquery.maskMoney.js")?>"></script>
    
    <script type="text/javascript">
        var tabla_devoluciones;
    </script>

    <!--**LIBRERIAS AGREGADAS EL DIA 23/ENERO/2024 POR DANTE ALDAIR**-->
    <!--**********************************************************************-->
    
    <!-- SELECT PICKER -->
    <script type="text/javascript" src="<?= base_url("js/bootstrap-selectpicker.min.js")?>"></script>
    <!--**********************************************************************-->
    
    <!-- SheetJS (xlsx) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <!--**PROVISIONAL A SELECT2**-->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script src="<?= base_url("js/miLibreria.js")?>"></script>

    <!-- SweetAlert -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

    <!-- Toastr -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> -->
    <script src="https://unpkg.com/dropbox@2.5.12/dist/Dropbox-sdk.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.10.2/lottie.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tsparticles@2.12.0/tsparticles.bundle.min.js"></script>
    <!--**********************************************************************-->

    <style>
        /*AGREGA ESTILO AL CHECKBOX EN LAS TABLAS*/
        table input[type=checkbox]{
            width:20px;
            height:20px;
        }

        .chat{
            overflow-y: scroll;
            height: 30em;
            resize: none;
        }

        .solmsn{
            margin:1em auto;
            padding: 0.5em 0.5em 1em 0.5em;
            height: 10em;
            overflow: auto;
            box-shadow:inset 0px 1px 1px #95a5a6;
            border-radius: 5px;
            font-size: 12px;
            border: 1px solid #bdc3c7;
        }

        /** INICIO FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
        .msnmio {
            background-color: #555555; /* Mismo color de fondo que .msndemas */
            color: #ffffff;
            float: right; /* Alineado a la derecha */
            padding: 1em; /* Igual que .msndemas */
            margin: 0.5em 0 0 50%; /* Ajuste para alineación opuesta */
            border-radius: 3px;
            min-width: 50%;
            right: 0;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
            position: relative;
            word-wrap: break-word;
        }

        .msndemas{
            background-color: #2c3e50;
            color: #ffffff;
            float: left;
            padding: 1em;
            margin: 0.5em 50% 0 0;
            border-radius: 3px;
            min-width: 50%;
            left: 0;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
            position: relative;
            word-wrap: break-word;
        }
        .smspan {
            font-size: 11px;
            display: block;
            color: #cccccc;
        }

        .fchp {
            font-size: 10px;
            text-align: right;
            color: #ffffff;
            margin: 2px 0 0 0;
            font-weight: bold;
            padding: 0;
        }
        /** FIN FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/

        .td-nowrap{
            white-space: nowrap;
        }
        .select2 {
            width:100%!important;
        }
        
        .modal-dialog {
            height: 100vh !important;
            display: flex;
        }
        
        .modal-content {
            margin: auto !important;
            height: fit-content !important;
        }

        <?php
            
            if( $this->session->userdata("inicio_sesion")["rol"] ){

                switch( $this->session->userdata("inicio_sesion")["rol"] ){
                    case 'DA':
                    case 'AS':
                    case 'CJ':
                    case 'CA':
                        $color = '#4B702D';
                        $respaldo = '#33703F';
                        break;
                    case 'CP':
                        $color = '#9B4F0F';
                        $respaldo = '#845713';
                         break;
                    case 'CT':
                    case 'CE':
                    case 'CC':
                    case 'CX':
                        $color = '#C99E10';
                        $respaldo = '#B29E17';
                        break;
                    case 'DP':
                        $color = '#8D230F';
                        $respaldo = '#182835';
                        break;
                    case 'CI':
                        $color = '#588AC6';
                        $respaldo = '#182835';
                        break;
                    default:
                        $color = '#1E434C';
                        $respaldo = '#763112';
                        break;
                }

                echo ".skin-green .main-header .navbar { background-color: $color; }";
                echo ".skin-green .main-header .navbar { background-color: $color; }";
                echo ".skin-green .main-header .navbar .sidebar-toggle:hover { background-color: $color;}";
                echo ".skin-green .main-header .logo { background-color: $color;}";
                echo ".skin-green .main-header .logo:hover { background-color: $color;}";                
            }
            
        ?>
    
    /** INICIO FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    .observacion {
        padding: 10px;
        border-radius: 3px;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        word-wrap: break-word;
        margin-bottom: 0.5em;
        background-color: #2c3e50;
    }

    .observacion:last-child {
        margin-bottom: 0;
    }
    /** FIN FECHA: 27-FEBRERO-2025 | @author Angel Victoriano <programador.analista30@ciudadmaderas.com> **/
    </style>
</head>
