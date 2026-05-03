
'use strict';

const Validador = {

    /**
     * Comprueba que el valor no es vacío.
     * @param {string} valor
     * @returns {boolean}
     */
    requerido(valor) {
        return valor !== null && valor !== undefined && String(valor).trim() !== '';
    },

    /**
     * Comprueba que el valor tiene formato de email válido.
     * @param {string} valor
     * @returns {boolean}
     */
    esEmail(valor) {
        if (!valor || String(valor).trim() === '') return false;
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(String(valor).trim());
    },

    /**
     * Comprueba que la cadena tiene al menos `min` caracteres.
     * @param {string} valor
     * @param {number} min
     * @returns {boolean}
     */
    minLong(valor, min) {
        if (!valor) return false;
        return String(valor).trim().length >= min;
    },

    /**
     * Comprueba que la cadena no supera `max` caracteres.
     * @param {string} valor
     * @param {number} max
     * @returns {boolean}
     */
    maxLong(valor, max) {
        if (valor === null || valor === undefined) return true;
        return String(valor).length <= max;
    },

    /**
     * Comprueba que el valor es un número (entero o decimal).
     * @param {*} valor
     * @returns {boolean}
     */
    esNumerico(valor) {
        if (valor === null || valor === undefined || String(valor).trim() === '') return false;
        return !isNaN(Number(valor));
    },

    /**
     * Comprueba que el valor es un entero positivo (> 0).
     * @param {*} valor
     * @returns {boolean}
     */
    enteroPositivo(valor) {
        if (!this.esNumerico(valor)) return false;
        const n = Number(valor);
        return Number.isInteger(n) && n > 0;
    },

    /**
     * Comprueba que el valor está dentro del rango [min, max] (inclusive).
     * @param {number} valor
     * @param {number} min
     * @param {number} max
     * @returns {boolean}
     */
    enRango(valor, min, max) {
        const n = Number(valor);
        return !isNaN(n) && n >= min && n <= max;
    },

    /**
     * Comprueba que las reglas de validación del formulario de contacto
     * tienen los campos mínimos requeridos.
     * @param {Object} rules - Objeto de reglas jQuery Validate
     * @returns {boolean}
     */
    tieneReglasContacto(rules) {
        const camposRequeridos = ['nombre', 'email', 'asunto', 'mensaje'];
        return camposRequeridos.every(campo =>
            rules[campo] && rules[campo].required === true
        );
    }
};

// Exportar para entorno Node.js (Jest)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = Validador;
}
