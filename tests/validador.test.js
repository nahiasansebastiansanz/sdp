/**
 * validador.test.js
 *
 * Pruebas Unitarias Jest para el módulo Validador (assets/js/validador.js)
 * Proyecto: SDP - Sistema de Desarrollo Personal
 * Herramienta: Jest ^29
 * Ejecutar: npm test
 */

const Validador = require('../assets/js/validador');

// ===========
// BLOQUE 1 - requerido()
// ===========
describe('Validador.requerido()', () => {

    // PRUEBA JS-1
    test('devuelve false para cadena vacía', () => {
        expect(Validador.requerido('')).toBe(false);
    });

    // PRUEBA JS-2
    test('devuelve false para cadena solo con espacios', () => {
        expect(Validador.requerido('   ')).toBe(false);
    });

    // PRUEBA JS-3
    test('devuelve false para null', () => {
        expect(Validador.requerido(null)).toBe(false);
    });

    // PRUEBA JS-4
    test('devuelve true para una cadena con contenido', () => {
        expect(Validador.requerido('Maria QA')).toBe(true);
    });
});

// ===========
// BLOQUE 2 - esEmail()
// ===========
describe('Validador.esEmail()', () => {

    // PRUEBA JS-5
    test('devuelve false para email sin arroba', () => {
        expect(Validador.esEmail('usuarioexample.com')).toBe(false);
    });

    // PRUEBA JS-6
    test('devuelve false para email sin dominio', () => {
        expect(Validador.esEmail('usuario@')).toBe(false);
    });

    // PRUEBA JS-7
    test('devuelve false para cadena vacía', () => {
        expect(Validador.esEmail('')).toBe(false);
    });

    // PRUEBA JS-8
    test('devuelve true para email válido', () => {
        expect(Validador.esEmail('maria.qa@sdp.com')).toBe(true);
    });

    // PRUEBA JS-9
    test('devuelve true para email con subdominio', () => {
        expect(Validador.esEmail('dev@mail.sdp.es')).toBe(true);
    });
});

// ===========
// BLOQUE 3 - minLong() / maxLong()
// ===========
describe('Validador.minLong()', () => {

    // PRUEBA JS-10
    test('devuelve false si la cadena es más corta que el mínimo', () => {
        expect(Validador.minLong('ab', 3)).toBe(false);
    });

    // PRUEBA JS-11
    test('devuelve true si la cadena cumple la longitud mínima', () => {
        expect(Validador.minLong('abc', 3)).toBe(true);
    });
});

describe('Validador.maxLong()', () => {

    // PRUEBA JS-12
    test('devuelve false si la cadena supera el máximo', () => {
        expect(Validador.maxLong('a'.repeat(101), 100)).toBe(false);
    });

    // PRUEBA JS-13
    test('devuelve true si la cadena está dentro del máximo', () => {
        expect(Validador.maxLong('Hola mundo', 100)).toBe(true);
    });
});

// ===========
// BLOQUE 4 - esNumerico() / enteroPositivo()
// ===========
describe('Validador.esNumerico()', () => {

    // PRUEBA JS-14
    test('devuelve false para texto no numérico', () => {
        expect(Validador.esNumerico('abc')).toBe(false);
    });

    // PRUEBA JS-15
    test('devuelve true para número como string', () => {
        expect(Validador.esNumerico('42')).toBe(true);
    });

    // PRUEBA JS-16
    test('devuelve true para número decimal', () => {
        expect(Validador.esNumerico('3.14')).toBe(true);
    });
});

describe('Validador.enteroPositivo()', () => {

    // PRUEBA JS-17
    test('devuelve false para número negativo', () => {
        expect(Validador.enteroPositivo(-5)).toBe(false);
    });

    // PRUEBA JS-18
    test('devuelve false para cero', () => {
        expect(Validador.enteroPositivo(0)).toBe(false);
    });

    // PRUEBA JS-19
    test('devuelve true para entero positivo', () => {
        expect(Validador.enteroPositivo(1)).toBe(true);
    });

    // PRUEBA JS-20
    test('devuelve false para decimal positivo', () => {
        expect(Validador.enteroPositivo(1.5)).toBe(false);
    });
});

// ===========
// BLOQUE 5 - enRango()
// ===========
describe('Validador.enRango()', () => {

    // PRUEBA JS-21
    test('devuelve false si el valor está por encima del rango', () => {
        expect(Validador.enRango(150, 1, 120)).toBe(false);
    });

    // PRUEBA JS-22
    test('devuelve false si el valor está por debajo del rango', () => {
        expect(Validador.enRango(0, 1, 120)).toBe(false);
    });

    // PRUEBA JS-23
    test('devuelve true para valor en el límite inferior', () => {
        expect(Validador.enRango(1, 1, 120)).toBe(true);
    });

    // PRUEBA JS-24
    test('devuelve true para valor en el límite superior', () => {
        expect(Validador.enRango(120, 1, 120)).toBe(true);
    });
});

// ===========
// BLOQUE 6 - tieneReglasContacto()
// ===========
describe('Validador.tieneReglasContacto()', () => {

    // PRUEBA JS-25
    test('devuelve true cuando todas las reglas requeridas están definidas', () => {
        const rules = {
            nombre:  { required: true, minlength: 2 },
            email:   { required: true, email: true },
            asunto:  { required: true },
            mensaje: { required: true, minlength: 10 }
        };
        expect(Validador.tieneReglasContacto(rules)).toBe(true);
    });

    // PRUEBA JS-26
    test('devuelve false cuando falta el campo email en las reglas', () => {
        const rules = {
            nombre:  { required: true },
            asunto:  { required: true },
            mensaje: { required: true }
        };
        expect(Validador.tieneReglasContacto(rules)).toBe(false);
    });

    // PRUEBA JS-27
    test('devuelve false cuando un campo tiene required: false', () => {
        const rules = {
            nombre:  { required: false },
            email:   { required: true, email: true },
            asunto:  { required: true },
            mensaje: { required: true }
        };
        expect(Validador.tieneReglasContacto(rules)).toBe(false);
    });
});
