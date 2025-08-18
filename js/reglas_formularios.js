		$(document).ready( function(){
			jQuery.extend(jQuery.validator.messages, {
                required: "Requerido",
                remote: "Please fix this field.",
                email: "Correo invalido",
                url: "Please enter a valid URL.",
                date: "Fecha invalida",
                dateISO: "Please enter a valid date (ISO).",
                number: "Numero invalido",
                digits: "Please enter only digits.",
                creditcard: "Please enter a valid credit card number.",
                equalTo: "Please enter the same value again.",
                accept: "Please enter a value with a valid extension.",
                maxlength: jQuery.validator.format("No mas de {0} digitos."),
                minlength: jQuery.validator.format("Al menos {0} digitos."),
                rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
                range: jQuery.validator.format("Please enter a value between {0} and {1}."),
                max: jQuery.validator.format("Menor a {0}"),
                min: jQuery.validator.format("Mayor a {0}")
            });
            
            jQuery.validator.addClassRules("relojes_horario", {
                required: true
            });

            $.validator.addMethod('menos_que', function(value, element, param) {
                return this.optional(element) || Date.parse("01 Jan 1970 "+value+" GMT") <= Date.parse("01 Jan 1970 "+$(param).val()+" GMT");
            }, "Menor a la SALIDA");

            $.validator.addMethod('mayor_que', function(value, element, param) {
                return this.optional(element) || Date.parse("01 Jan 1970 "+value+" GMT") >= Date.parse("01 Jan 1970 "+$(param).val()+" GMT");
            }, "Mayor a la ENTRADA");
            

            //REGLAS DEL FORMULARIO CORRESPONDIENTE
            $("#formulario_horarios").validate({
                focusInvalid: false,
                focusCleanup: true,
                rules: {
                    nombre_horario: "required",
					dias_laborar: {
						required: true,
						min : 1
					},
                    dias_laborales: {
                        require_from_group: [3, ".dias_laborales"]
                    },
                    /*
                    horario_semanal: {
                        require_from_group: [1, ".activar_horario"]
                    },
                    horario_sabatino: {
                        require_from_group: [1, ".activar_horario"]
                    },
                    horario_dominical: {
                        require_from_group: [1, ".activar_horario"]
                    },*/
                    hora_entrada_lv: {
                        required: true,
                        menos_que: "#hora_salida_lv"
                    },
                    hora_salida_lv: {
                        required: true,
                        mayor_que: "#hora_entrada_lv"
                    },
                    hora_entrada_sa: {
                        required: true,
                        menos_que: "#hora_salida_sa"
                    },
                    hora_salida_sa: {
                        required: true,
                        mayor_que: "#hora_entrada_sa"
                    },
                    hora_entrada_do: {
                        required: true,
                        menos_que: "#hora_salida_do"
                    },
                    hora_salida_do: {
                        required: true,
                        mayor_que: "#hora_entrada_do"
                    },
                    actividad_horario: "required"
                }
            });
            
            //REGLAS DEL FORMULARIO CORRESPONDIENTE
            $("#formulario_usuario").validate({
                focusInvalid: false,
                focusCleanup: true,
                rules: {
					numero_empledo_usuario: "required",
                    nombre_usuario: "required",
                    apellido_paterno_usuario: "required",
                    email_usuario: {
                        required: true,
                        email: true
                    },
                    usuario: "required",
                    password: "required",
                    email_usuario: {
                        required: true,
                        email: true
                    },
                    rol_usuario: "required",
                    area_correspondiente: "required",
                    estatus_usuario: "required"
                }
            });
			
			$("#formulario_razon_social").validate({
                focusInvalid: false,
                focusCleanup: true,
                rules: {
                    nombre_razon_social: "required",
                    rfc_social: {
						required: true,
                        minlength: 10,
                        maxlength: 13
                    },
					registro_patronal: {
						required: true,
                        minlength: 10,
                        maxlength: 13
                    },
					notario_publico: "required",
					notaria: "required",
                    representate_legal: "required",
					domicilio_fiscal: "required",
					num_escritura: "required",
					tomo_vol: "required",
                    fecha_incio_ope: "required",
					ciudad_emision: "required"
                }
            });

            $("#formulario_candidato").validate({
                focusInvalid: false,
                focusCleanup: true,
                rules: {
                    nombre_usuario: "required",
                    apellido_paterno_usuario: "required",
                    sexo_candidato: "required",
                    fecha_nacimiento: "required",
					escolaridad_candidato: "required",
					estado_civil: "required",
                    telefono_uno: "required",
                    telefono_uno_numero: {
                        required: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    telefono_dos_numero: {
                        minlength: 10,
                        maxlength: 10
                    },
                    email_personal: {
                        required: true,
                        email: true
                    },
                    medio_reclutamiento: "required",
                    otro_medio: "required"
                }
            });

            //REGLAS DEL FORMULARIO CORRESPONDIENTE
            $("#formulario_sucursal").validate({
                focusInvalid: false,
                focusCleanup: true,
                rules: {
                    sede_correspondiente: "required",
                    nombre_sucursal: "required",
                    actividad_sucursal: "required"
                }
            });
            
            //REGLAS DEL FORMULARIO CORRESPONDIENTE
            $("#formulario_sede").validate({
                focusInvalid: false,
                focusCleanup: true,
                rules: {
                    editar_sede: "required",
                    nombre_sede: "required"
                }
            });
            
            //REGLAS DEL FORMULARIO CORRESPONDIENTE
            $("#formulario_puesto").validate({
                focusInvalid: false,
                focusCleanup: true,
                rules: {
                    departamento_puesto: "required",
                    numero_vacantes: {
                        required : true,
                        min : 1
                    },
                    tipo_puesto: "required",
                    sueldo_puesto:{
                        required : true,
                        number: true,
                        min: 2686.14
                    },
                    nombre_puesto: "required",
                    estatus_vacantes: "required"
                }
            });

            //REGLAS DEL FORMULARIO CORRESPONDIENTE
            $("#formulario_area").validate({
                focusInvalid: false,
                focusCleanup: true,
                rules: {
                    nombre_area: "required",
                    editar_area: "required"
                }
            });

            //REGLAS DEL FORMULARIO CORRESPONDIENTE
            $("#formulario_subarea").validate({
                focusInvalid: false,
                focusCleanup: true,
                rules: {
                    nombre_subarea: "required",
                    editar_area: "required",
                    actividad_subarea: "required"
                }
            });

            $("#formulario_login").validate({
                focusInvalid: false,
                focusCleanup: true,
                rules: {
                    login_usuario: "required",
                    login_password: "required",
                }
            });
            
            //REGLAS DEL FORMULARIO CORRESPONDIENTE
            $("#formulario_solicitud").validate({
                focusInvalid: false,
                focusCleanup: true,
                rules: {
                    solicitud_puesto: "required",
                    solicitud_sede: "required",
                    solicitud_sucursal: "required",
                    solicitud_subarea: "required",
                    solicitud_sexo: "required",
                    solicitud_experiencia: "required",
                    solicitud_minima: "required",
                    solicitud_maxima: "required",
                    solicitud_estudios: "required",
                    solicitud_contrato: "required",
                    solicitud_vacantes: {
                        required : true,
                        number : true,
                        min: 1,
                        max: 99
                    },
                    solicitud_salario: {
                        required : true,
                        number: true,
                        min: 2686.14
                    },
                    solicitud_contratacion: "required",
                    solicitud_descripcion: "required",
                    horario_vacante: "required",
                    dias_laborales_vacante: "required"
                }
            });
			
			$("#formulario_contratacion").validate({
                focusInvalid: false,
                focusCleanup: true,
                rules: {
                    curp_contrato: {
                        required : true,
                        minlength: 18,
						maxlength: 18
                    },
                    rfc_contrato: {
                        required : true,
                        minlength: 13,
                        maxlength: 13
                    },
                    nss_contrato: {
                        required : true,
                        minlength: 11,
                        maxlength: 11
                    },
                    estado_contrato: "required",
                    municipio_contrato: "required",
                    colonia_contrato: "required",
                    direccion_contrato: "required",
                    cp_contrato: "required",
                    fecha_contrato: "required",
                    razon_social_contrato: "required",
					registro_patronal: "required",
                    salario_semanal_contrato: {
                        required : true,
                        number: true,
                        min: 626.76
                    },
                    salario_mensual_contrato: {
                        required : true,
                        number: true,
                        min: 2686.14
                    },
					salario_tabulado : {
						required : true,
                        number: true,
                        min: 88.40
					}
                }
            });
			
			$("#formulario_editar_contrato").validate({
                focusInvalid: false,
                focusCleanup: true,
                rules: {
					nombre_usuario: "required",
                    apellido_paterno_usuario: "required",
                    fecha_nacimiento: "required",
                    email_personal: "required",
                    telefono_uno: "required",
                    telefono_uno_numero: {
                        required : true,
                        minlength: 10,
						maxlength: 10
                    },
                    telefono_dos_numero: {
                        minlength: 10,
						maxlength: 10
                    },
                    curp_contrato: {
                        required : true,
                        minlength: 18,
						maxlength: 18
                    },
                    rfc_contrato: {
                        required : true,
                        minlength: 13,
                        maxlength: 13
                    },
                    nss_contrato: {
                        required : true,
                        minlength: 11,
                        maxlength: 11
                    },
                    estado_civil: "required",
                    estado_contrato: "required",
                    municipio_contrato: "required",
                    colonia_contrato: "required",
                    direccion_contrato: "required",
                    cp_contrato: "required",
                    fecha_contrato: "required",
                    razon_social_contrato: "required",
					puesto_contrato: "required",
                    salario_semanal_contrato: {
                        required : true,
                        number: true,
                        min: 626.76
                    },
                    salario_mensual_contrato: {
                        required : true,
                        number: true,
                        min: 2686.14
                    }
                }
            });
			
			$("#formulario_acta_administrativa").validate( {
				focusInvalid: false,
                focusCleanup: true,
                rules: {
					primer_testigo_acta : "required",
					segundo_testigo_acta : "required",
					injuria_acta : "required",
					defensa_acta : "required"
				}
			});
			
			$("#formulario_acta_echos").validate( {
				focusInvalid: false,
                focusCleanup: true,
                rules: {
					jefe_inmediato_echo : "required",
					puesto_jefe_inmediato_echo : "required",
					injuria_acta : "required"
				}
			});
			
			$("#formulario_carta_amonestacion").validate( {
				focusInvalid: false,
                focusCleanup: true,
                rules: {
					estado_contrato : "required",
					municipio_contrato : "required",
					falta_carta : "required",
					acciones_carta : "required",
					recursos_humanos : "required",
					director_carta : "required"
				}
			});
			
			$("#formulario_contratacion_honorarios").validate( {
				focusInvalid: false,
                focusCleanup: true,
                rules: {
					nombre_usuario : "required",
					apellido_paterno_usuario : "required",
					fecha_nacimiento : "required",
					sexo_candidato : "required",
					escolaridad_candidato : "required",
					email_personal : {
                        required: true,
                        email: true
                    },
					telefono_uno : "required",
					telefono_uno_numero : "required",
					curp_contrato: {
                        required : true,
                        minlength: 18,
						maxlength: 18
                    },
                    rfc_contrato: {
                        required : true,
                        minlength: 13,
                        maxlength: 13
                    },
                    nss_contrato: {
                        required : true,
                        minlength: 11,
                        maxlength: 11
                    },
					estado_contrato : "required",
					municipio_contrato : "required",
					colonia_contrato : "required",
					direccion_contrato : "required",
					cp_contrato : "required",
					fecha_contrato : "required",
					duracion_contrato : "required",
					razon_social_contrato : "required",
					tipo_contrato_honorarios : "required",
					tipo_nomina_honorarios : "required",
					puesto_contrato : "required",
					tarea_realizar : "required",
					salario_semanal_contrato : "required",
					salario_mensual_contrato : "required",
					salario_tabulado : "required"
				}
			});
			
			$("#formulario_prestamos").validate( {
				focusInvalid: false,
                focusCleanup: true,
                rules: {
					cantidad_solicitada : {
						required: true,
						min: 1
					},
					numero_semanas : "required",
					descuento_semana : "required",
					motivo_prestamo : "required",
					puntualidad_observaciones : "required",
					asistencia_observaciones : "required",
					objetivos_observaciones : "required",
					actitud_observaciones : "required",
				}
			});
			
			$("#frmReporte").validate( {
				focusInvalid: false,
                focusCleanup: true,
                rules: {
					tipoRepo : "required",
					fecInicial : "required",
					fecFinal : "required"
				}
			});
			
			$("#formulario_gastos").validate( {
				focusInvalid: false,
                focusCleanup: true,
                rules: {
					solicitud_gasto : "required",
					concepto_gasto : "required",
					proveedor : "required",
					razonSoc : "required",
					foliofac : "required",
					rfcprov : {
                        required : true,
                        maxlength: 13
                    },
					costo : "required",
					costo_iva : "required",
					descripcion_gasto : "required"
				}
			});
			
			$("#formulario_vacaciones").validate( {
				focusInvalid: false,
                focusCleanup: true,
                rules: {
					dias_disfrutar : {
						required: true,
						min: 1
					},
					fechas_vacaciones : "required"
				}
			});
			
			$("#form_descuentos").validate( {
				focusInvalid: false,
                focusCleanup: true,
                rules: {
					tipo_descuento : "required",
					cantidad_descontar : "required",
					fecha_inicio : "required",
					num_semanas : "required"
				}
			});
			
			$("#formulario_reporte_incidencia").validate( {
				focusInvalid: false,
                focusCleanup: true,
                rules: {
					numero_semana : "required",
					year_semana : "required"
				}
			});
			
			$("#frmNewCurso").validate( {
				focusInvalid: false,
                focusCleanup: true,
                rules: {
					nombre : "required"
				}
			});
			
			$("#frmNewCurso").validate( {
				focusInvalid: false,
                focusCleanup: true,
                rules: {
					nombreIns : "required",
					procedencia : "required",
					empresa : "required",
					correo : "required",
					telcont : "required"
				}
			});
		});