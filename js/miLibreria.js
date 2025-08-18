(function(global){
    const MyLib = {

        montoTotalSolicitudParcialidad: function (cantidad, numeroPagos, montoPorParcialidad, programado, fecelab){
            let montoTotal = parseFloat(cantidad);
            let parcialidades = numeroPagos ? parseFloat(numeroPagos) : 0;
            let montoParcialidad = montoPorParcialidad ? parseFloat(montoPorParcialidad) : 0;
            let periodo = programado ? parseFloat(programado) : 0;            
            
            const fechaInicio = moment(fecelab);
            let fechaProxPag = fechaInicio.clone();
            let montoAcumulado = 0;
            let pagosParcialidades = [];
            
            switch (periodo) {
                case 7: // CASO DE ACUERDO A SOLICITUD PROGRAMADA EN MODALIDAD DE SEMANALIDADES.
                    for (let numPago = 1; numPago <= parcialidades; numPago++) {
                        if (numPago == parcialidades) {
                            montoParcialidad = montoTotal - montoAcumulado;
                        }
                        montoAcumulado += montoParcialidad;
                        pagosParcialidades[numPago] = formatMoney(montoParcialidad);
                        fechaProxPag = fechaInicio.clone().add(numPago, 'weeks');
                    }
                    
                    break;
                case 8:
                    for (let numPago = 1; numPago <= parcialidades; numPago++) {
                        if (numPago == parcialidades) {
                            montoParcialidad = montoTotal - montoAcumulado;
                        }
                        montoAcumulado += montoParcialidad;
                        pagosParcialidades[numPago] = formatMoney(montoParcialidad);
                        fechaProxPag = fechaInicio.clone().add((numPago*15), 'days');
                    }
                    
                    break;
                case 1:
                    for (let numPago = 1; numPago <= parcialidades; numPago++) {

                        if (numPago == parcialidades) {
                            montoParcialidad = montoTotal - montoAcumulado;
                        }
                        montoAcumulado += montoParcialidad;
                        pagosParcialidades[numPago] = formatMoney(montoParcialidad);
                        fechaProxPag = fechaInicio.clone().add(numPago, 'months');
                    }
                    break;
                case 2:
                    for (let numPago = 1; numPago <= parcialidades; numPago++) {
                        if (numPago == parcialidades) {
                            montoParcialidad = montoTotal - montoAcumulado;
                        }
                        montoAcumulado += montoParcialidad;
                        pagosParcialidades[numPago] = formatMoney(montoParcialidad);
                        fechaProxPag = fechaInicio.clone().add((numPago * 2), 'months');
                    }
                    
                    break;
                case 3:
                    for (let numPago = 1; numPago <= parcialidades; numPago++) {
                        if (numPago == parcialidades) {
                            montoParcialidad = montoTotal - montoAcumulado;
                        }
                        montoAcumulado += montoParcialidad;
                        pagosParcialidades[numPago] = formatMoney(montoParcialidad);
                        fechaProxPag = fechaInicio.clone().add((numPago * 3), 'months');
                    }
                    
                    break;
                case 4:
                    for (let numPago = 1; numPago <= parcialidades; numPago++) {
                        if (numPago == parcialidades) {
                            montoParcialidad = montoTotal - montoAcumulado;
                        }
                        montoAcumulado += montoParcialidad;
                        pagosParcialidades[numPago] = formatMoney(montoParcialidad);
                        fechaProxPag = fechaInicio.clone().add((numPago * 4), 'months');
                    }
                    
                    break;
                case 6:
                    for (let numPago = 1; numPago <= parcialidades; numPago++) {
                        if (numPago == parcialidades) {
                            montoParcialidad = montoTotal - montoAcumulado;
                        }
                        montoAcumulado += montoParcialidad;
                        pagosParcialidades[numPago] = formatMoney(montoParcialidad);
                        fechaProxPag = fechaInicio.clone().add((numPago * 6), 'months');
                    }
                    break;
            }
            return {'tabla_pagos': pagosParcialidades, 'montoTotalPagar': montoAcumulado};
        }
    }
    // Expón globalmente
    global.MyLib = MyLib;
})(window);