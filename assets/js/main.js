class AppSDP {

    constructor(formId) {
        this.formId = formId;
    }

    /** Reglas de validación - sobrescribir en cada subclase */
    getRules() { return {}; }

    /** Mensajes de error - sobrescribir en cada subclase */
    getMensajes() { return {}; }

    /** Configuración visual compartida por todos los formularios */
    _getOpciones() {
        const self = this;
        return {
            rules:    self.getRules(),
            messages: self.getMensajes(),

            errorClass:   'sdp-campo-error',
            validClass:   'sdp-campo-ok',
            errorElement: 'span',

            /* Coloca el mensaje justo debajo del campo */
            errorPlacement(error, element) {
                if (element.attr('type') === 'radio' || element.attr('type') === 'checkbox') {
                    error.insertAfter(element.closest('.grupo-radio, label'));
                } else {
                    error.insertAfter(element);
                }
                error.addClass('sdp-msg-error');
            },

            /* Resalta el campo con error */
            highlight(element) {
                $(element).addClass('sdp-campo-invalido').removeClass('sdp-campo-valido');
            },

            /* Quita el resaltado cuando es válido */
            unhighlight(element) {
                $(element).removeClass('sdp-campo-invalido').addClass('sdp-campo-valido');
            },

            submitHandler(form) {
                form.submit();
            }
        };
    }

    /**
     * Inicializa jQuery Validate sobre el formulario.
     * Si el formulario no existe en el DOM actual, no hace nada.
 */
    init() {
        if ($(this.formId).length === 0) return;
        $(this.formId)
            .attr('novalidate', true)          // desactiva validación nativa del navegador
            .validate(this._getOpciones());
    }
}

/* 
   VALIDADOR LOGIN
*/
class ValidadorLogin extends AppSDP {
    constructor() { super('#loginForm'); }

    getRules() {
        return {
            nombre_usuario: {
                required: true,
                minlength: 3,
                maxlength: 60
            },
            contrasena: {
                required: true,
                minlength: 6
            }
        };
    }

    getMensajes() {
        return {
            nombre_usuario: {
                required:  'El nombre de usuario es obligatorio.',
                minlength: 'El usuario debe tener al menos {0} caracteres.',
                maxlength: 'El usuario no puede superar {0} caracteres.'
            },
            contrasena: {
                required:  'La contraseña es obligatoria.',
                minlength: 'La contraseña debe tener al menos {0} caracteres.'
            }
        };
    }
}

/* 
   VALIDADOR REGISTRO
*/
class ValidadorRegistro extends AppSDP {
    constructor() { super('#registroForm'); }

    getRules() {
        return {
            nombre_completo: {
                required:  true,
                minlength: 3,
                maxlength: 120
            },
            nombre_usuario: {
                required:  true,
                minlength: 3,
                maxlength: 60,
                pattern:   /^[a-zA-Z0-9_.-]+$/
            },
            email: {
                required: true,
                email:    true,
                maxlength: 120
            },
            edad: {
                min: 5,
                max: 120,
                digits: true
            },
            telefono: {
                minlength: 7,
                maxlength: 20
            },
            contrasena: {
                required:  true,
                minlength: 6,
                maxlength: 64
            },
            confirmar: {
                required:  true,
                equalTo:  '#contrasena'
            }
        };
    }

    getMensajes() {
        return {
            nombre_completo: {
                required:  'El nombre completo es obligatorio.',
                minlength: 'Debe tener al menos {0} caracteres.',
                maxlength: 'No puede superar {0} caracteres.'
            },
            nombre_usuario: {
                required:  'El usuario es obligatorio.',
                minlength: 'El usuario debe tener al menos {0} caracteres.',
                maxlength: 'No puede superar {0} caracteres.',
                pattern:   'Solo letras, números, guiones y puntos.'
            },
            email: {
                required:  'El email es obligatorio.',
                email:     'Introduce un email válido.',
                maxlength: 'El email no puede superar {0} caracteres.'
            },
            edad: {
                min:    'La edad mínima es {0} años.',
                max:    'La edad máxima es {0} años.',
                digits: 'Solo números enteros.'
            },
            telefono: {
                minlength: 'El teléfono debe tener al menos {0} dígitos.',
                maxlength: 'No puede superar {0} caracteres.'
            },
            contrasena: {
                required:  'La contraseña es obligatoria.',
                minlength: 'La contraseña debe tener al menos {0} caracteres.'
            },
            confirmar: {
                required: 'Confirma la contraseña.',
                equalTo:  'Las contraseñas no coinciden.'
            }
        };
    }
}

/* 
   VALIDADOR CONTACTO
*/
class ValidadorContacto extends AppSDP {
    constructor() { super('#contactoForm'); }

    getRules() {
        return {
            nombre: {
                required:  true,
                minlength: 2,
                maxlength: 120
            },
            email: {
                required: true,
                email:    true
            },
            asunto: {
                required: true
            },
            mensaje: {
                required:  true,
                minlength: 10,
                maxlength: 2000
            }
        };
    }

    getMensajes() {
        return {
            nombre: {
                required:  'El nombre es obligatorio.',
                minlength: 'El nombre debe tener al menos {0} caracteres.',
                maxlength: 'No puede superar {0} caracteres.'
            },
            email: {
                required: 'El email es obligatorio.',
                email:    'Introduce un email válido.'
            },
            asunto: {
                required: 'Selecciona un asunto.'
            },
            mensaje: {
                required:  'El mensaje es obligatorio.',
                minlength: 'El mensaje debe tener al menos {0} caracteres.',
                maxlength: 'No puede superar {0} caracteres.'
            }
        };
    }
}

/* 
   VALIDADOR PERFIL (configuración)
*/
class ValidadorPerfil extends AppSDP {
    constructor() { super('#perfilForm'); }

    getRules() {
        return {
            nombre_completo: {
                required:  true,
                minlength: 3,
                maxlength: 120
            },
            nombre_usuario: {
                required:  true,
                minlength: 3,
                maxlength: 60
            },
            email: {
                required: true,
                email:    true
            },
            edad: {
                min:    5,
                max:    120,
                digits: true
            },
            telefono: {
                minlength: 7,
                maxlength: 20
            }
        };
    }

    getMensajes() {
        return {
            nombre_completo: {
                required:  'El nombre completo es obligatorio.',
                minlength: 'Debe tener al menos {0} caracteres.'
            },
            nombre_usuario: {
                required:  'El usuario es obligatorio.',
                minlength: 'Debe tener al menos {0} caracteres.'
            },
            email: {
                required: 'El email es obligatorio.',
                email:    'Introduce un email válido.'
            },
            edad: {
                min:    'La edad mínima es {0} años.',
                max:    'La edad máxima es {0} años.',
                digits: 'Solo números enteros.'
            },
            telefono: {
                minlength: 'El teléfono debe tener al menos {0} dígitos.'
            }
        };
    }
}

/* 
   VALIDADOR DIARIO
*/
class ValidadorDiario extends AppSDP {
    constructor() { super('#diarioForm'); }

    getRules() {
        return {
            titulo: {
                required:  true,
                minlength: 3,
                maxlength: 200
            },
            humor: {
                required: true
            },
            contenido: {
                required:  true,
                minlength: 5,
                maxlength: 5000
            }
        };
    }

    getMensajes() {
        return {
            titulo: {
                required:  'El título es obligatorio.',
                minlength: 'El título debe tener al menos {0} caracteres.',
                maxlength: 'No puede superar {0} caracteres.'
            },
            humor: {
                required: 'Selecciona cómo te sientes hoy.'
            },
            contenido: {
                required:  'El contenido es obligatorio.',
                minlength: 'Escribe al menos {0} caracteres.',
                maxlength: 'No puede superar {0} caracteres.'
            }
        };
    }
}

/* 
   VALIDADOR MEDITACIÓN (formulario admin)
*/
class ValidadorMeditacion extends AppSDP {
    constructor() { super('#medForm'); }

    getRules() {
        return {
            titulo: {
                required:  true,
                minlength: 3,
                maxlength: 150
            },
            descripcion: {
                maxlength: 1000
            },
            id_categoria: {
                required: true
            },
            nivel: {
                required: true
            },
            duracion_min: {
                required: true,
                min:      1,
                max:      180,
                digits:   true
            },
            icono: {
                maxlength: 10
            }
        };
    }

    getMensajes() {
        return {
            titulo: {
                required:  'El título es obligatorio.',
                minlength: 'Debe tener al menos {0} caracteres.',
                maxlength: 'No puede superar {0} caracteres.'
            },
            descripcion: {
                maxlength: 'No puede superar {0} caracteres.'
            },
            id_categoria: {
                required: 'Selecciona una categoría.'
            },
            nivel: {
                required: 'Selecciona el nivel.'
            },
            duracion_min: {
                required: 'La duración es obligatoria.',
                min:      'La duración mínima es {0} minuto.',
                max:      'La duración máxima es {0} minutos.',
                digits:   'Introduce un número entero.'
            }
        };
    }
}

/* 
   VALIDADOR CATEGORÍA (inline)
*/
class ValidadorCategoria extends AppSDP {
    constructor() { super('#catForm'); }

    getRules() {
        return {
            nombre: {
                required:  true,
                minlength: 2,
                maxlength: 80
            },
            icono: {
                maxlength: 10
            },
            descripcion: {
                maxlength: 500
            }
        };
    }

    getMensajes() {
        return {
            nombre: {
                required:  'El nombre de la categoría es obligatorio.',
                minlength: 'Debe tener al menos {0} caracteres.',
                maxlength: 'No puede superar {0} caracteres.'
            },
            descripcion: {
                maxlength: 'No puede superar {0} caracteres.'
            }
        };
    }
}

/* 
   VALIDADOR RESPIRACIÓN
*/
class ValidadorRespiracion extends AppSDP {
    constructor() { super('#respForm'); }

    getRules() {
        return {
            nombre: {
                required:  true,
                minlength: 3,
                maxlength: 120
            },
            descripcion: {
                maxlength: 500
            },
            inhala_seg: {
                required: true,
                min:      1,
                max:      60,
                digits:   true
            },
            retiene_seg: {
                min:    0,
                max:    60,
                digits: true
            },
            exhala_seg: {
                required: true,
                min:      1,
                max:      60,
                digits:   true
            },
            retiene2_seg: {
                min:    0,
                max:    60,
                digits: true
            },
            ciclos: {
                required: true,
                min:      1,
                max:      60,
                digits:   true
            }
        };
    }

    getMensajes() {
        return {
            nombre: {
                required:  'El nombre es obligatorio.',
                minlength: 'Debe tener al menos {0} caracteres.'
            },
            inhala_seg: {
                required: 'Indica los segundos de inhalación.',
                min:      'Mínimo {0} segundo.',
                max:      'Máximo {0} segundos.',
                digits:   'Solo números enteros.'
            },
            retiene_seg: {
                min: 'No puede ser negativo.', digits: 'Solo números enteros.'
            },
            exhala_seg: {
                required: 'Indica los segundos de exhalación.',
                min:      'Mínimo {0} segundo.',
                max:      'Máximo {0} segundos.',
                digits:   'Solo números enteros.'
            },
            retiene2_seg: {
                min: 'No puede ser negativo.', digits: 'Solo números enteros.'
            },
            ciclos: {
                required: 'Indica el número de ciclos.',
                min:      'Mínimo {0} ciclo.',
                max:      'Máximo {0} ciclos.',
                digits:   'Solo números enteros.'
            }
        };
    }
}

/* 
   VALIDADOR LOGRO (inline)
*/
class ValidadorLogro extends AppSDP {
    constructor() { super('#logroForm'); }

    getRules() {
        return {
            titulo: {
                required:  true,
                minlength: 3,
                maxlength: 120
            },
            icono: {
                maxlength: 10
            },
            descripcion: {
                maxlength: 500
            },
            condicion_tipo: {
                required: true
            },
            condicion_valor: {
                required: true,
                min:      1,
                max:      9999,
                digits:   true
            }
        };
    }

    getMensajes() {
        return {
            titulo: {
                required:  'El título del logro es obligatorio.',
                minlength: 'Debe tener al menos {0} caracteres.'
            },
            condicion_tipo: {
                required: 'Selecciona el tipo de condición.'
            },
            condicion_valor: {
                required: 'Indica el valor de la condición.',
                min:      'El valor mínimo es {0}.',
                digits:   'Solo números enteros.'
            }
        };
    }
}

/* 
   VALIDADOR RETO (inline)
*/
class ValidadorReto extends AppSDP {
    constructor() { super('#retoForm'); }

    getRules() {
        return {
            titulo: {
                required:  true,
                minlength: 3,
                maxlength: 150
            },
            descripcion: {
                maxlength: 500
            },
            tipo: {
                required: true
            },
            objetivo_valor: {
                required: true,
                min:      1,
                max:      9999,
                digits:   true
            },
            duracion_dias: {
                required: true,
                min:      1,
                max:      365,
                digits:   true
            }
        };
    }

    getMensajes() {
        return {
            titulo: {
                required:  'El título del reto es obligatorio.',
                minlength: 'Debe tener al menos {0} caracteres.'
            },
            tipo: {
                required: 'Selecciona el tipo de reto.'
            },
            objetivo_valor: {
                required: 'Indica el objetivo.',
                min:      'El mínimo es {0}.',
                digits:   'Solo números enteros.'
            },
            duracion_dias: {
                required: 'Indica la duración.',
                min:      'La duración mínima es {0} día.',
                max:      'La duración máxima es {0} días.',
                digits:   'Solo números enteros.'
            }
        };
    }
}

/* 
   VALIDADOR USUARIO ADMIN
*/
class ValidadorUsuarioAdmin extends AppSDP {
    constructor() { super('#usuarioForm'); }

    getRules() {
        const esNuevo = $('#usuarioForm input[name="id_usuario"]').val() === ''
                     || $('#usuarioForm input[name="id_usuario"]').length === 0;
        const rules = {
            nombre_completo: {
                required:  true,
                minlength: 3,
                maxlength: 120
            },
            nombre_usuario: {
                required:  true,
                minlength: 3,
                maxlength: 60
            },
            email: {
                required: true,
                email:    true
            },
            perfil: {
                required: true
            }
        };
        if (esNuevo) {
            rules.contrasena = { required: true, minlength: 6 };
        }
        return rules;
    }

    getMensajes() {
        return {
            nombre_completo: {
                required:  'El nombre completo es obligatorio.',
                minlength: 'Debe tener al menos {0} caracteres.'
            },
            nombre_usuario: {
                required:  'El usuario es obligatorio.',
                minlength: 'Debe tener al menos {0} caracteres.'
            },
            email: {
                required: 'El email es obligatorio.',
                email:    'Introduce un email válido.'
            },
            perfil: {
                required: 'Selecciona un perfil.'
            },
            contrasena: {
                required:  'La contraseña es obligatoria para usuarios nuevos.',
                minlength: 'Debe tener al menos {0} caracteres.'
            }
        };
    }
}

/* 
   INICIALIZACIÓN - document ready
*/
$(function () {

    // Añadir método de validación personalizado: patrón alfanumérico para usuario
    $.validator.addMethod('pattern', function (value, element, param) {
        return this.optional(element) || param.test(value);
    }, 'Formato no válido.');

    // Instanciar y activar todos los validadores
    new ValidadorLogin().init();
    new ValidadorRegistro().init();
    new ValidadorContacto().init();
    new ValidadorPerfil().init();
    new ValidadorDiario().init();
    new ValidadorMeditacion().init();
    new ValidadorCategoria().init();
    new ValidadorRespiracion().init();
    new ValidadorLogro().init();
    new ValidadorReto().init();
    new ValidadorUsuarioAdmin().init();

});
