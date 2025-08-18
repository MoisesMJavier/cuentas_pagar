<?php
    function get_vacaciones_correspondientes($fecha_ingreso){

        $tiempo_servicio = (new DateTime(date("Y-m-d")))->diff( new DateTime($fecha_ingreso) )->y;

        switch( $tiempo_servicio ){
            case '0':
                return 0;
                break;
            case '1':
                return 6;
                break;
            case '2':
                return 8;
                break;
            case '3':
                return 10;
                break;
            case '4':
                return 12;
                break;
            case '5':
                return 14;
            default:
                return ($tiempo_servicio - 5) * 2 + 14;
        }
    }

    function antiguedad($fecha_ingreso){
        return (new DateTime(date("Y-m-d")))->diff( new DateTime($fecha_ingreso) )->y;
    }

    function intervalo_fechas($fecha_ingreso, $rango){
        return array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre")[date("n", strtotime($fecha_ingreso." +$rango years"))].' del '.date("Y", strtotime($fecha_ingreso." +$rango years"))." a ".array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre")[date("n", strtotime($fecha_ingreso." +".($rango + 1)." years"))].' del '.date("Y", strtotime($fecha_ingreso." +".($rango + 1)." years"));
    }

    function fechapivote($fecha_ingreso, $rango){
        return date("Y-m-d", strtotime($fecha_ingreso." +$rango years"));
    }
?>