
<?php
/**
 * ValidadorPHPTest.php
 *
 * Pruebas Unitarias PHPUnit para la clase ValidadorPHP.
 * Proyecto: SDP - Sistema de Desarrollo Personal
 * Herramienta: PHPUnit ^10
 * Ejecutar: vendor\bin\phpunit --testdox
 */

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../util/ValidadorPHP.php';

class ValidadorPHPTest extends TestCase
{
    private ValidadorPHP $v;

    protected function setUp(): void
    {
        $this->v = new ValidadorPHP();
    }

    // -----------------------------------------------------------------------
    // PRUEBA 1 - requerido(): campo vacío genera error
    // -----------------------------------------------------------------------
    /** @test */
    public function test_requerido_campo_vacio_genera_error(): void
    {
        $this->v->requerido('', 'Nombre');

        $this->assertTrue(
            $this->v->hayErrores(),
            'Se esperaba al menos un error al pasar un campo vacío.'
        );
        $this->assertStringContainsString(
            'Nombre',
            $this->v->getErrores()[0],
            'El mensaje de error debe mencionar el nombre del campo.'
        );
    }

    // -----------------------------------------------------------------------
    // PRUEBA 2 - requerido(): campo con valor válido NO genera error
    // -----------------------------------------------------------------------
    /** @test */
    public function test_requerido_campo_con_valor_no_genera_error(): void
    {
        $this->v->requerido('Julian', 'Nombre');

        $this->assertFalse(
            $this->v->hayErrores(),
            'No debería haber errores cuando el campo tiene valor.'
        );
    }

    // -----------------------------------------------------------------------
    // PRUEBA 3 - email(): dirección inválida genera error
    // -----------------------------------------------------------------------
    /** @test */
    public function test_email_invalido_genera_error(): void
    {
        $this->v->email('esto-no-es-un-email', 'Email');

        $this->assertTrue(
            $this->v->hayErrores(),
            'Un email mal formado debe producir error.'
        );
    }

    // -----------------------------------------------------------------------
    // PRUEBA 4 - email(): dirección válida NO genera error
    // -----------------------------------------------------------------------
    /** @test */
    public function test_email_valido_no_genera_error(): void
    {
        $this->v->email('usuario@example.com', 'Email');

        $this->assertFalse(
            $this->v->hayErrores(),
            'Un email correcto no debe producir error.'
        );
    }

    // -----------------------------------------------------------------------
    // PRUEBA 5 - entero(): texto no numérico genera error
    // -----------------------------------------------------------------------
    /** @test */
    public function test_entero_con_letras_genera_error(): void
    {
        $this->v->entero('abc123', 'Edad');

        $this->assertTrue(
            $this->v->hayErrores(),
            'Un valor con letras no debe pasar la validación de entero.'
        );
    }

    // -----------------------------------------------------------------------
    // PRUEBA 6 - entero(): número válido NO genera error
    // -----------------------------------------------------------------------
    /** @test */
    public function test_entero_valido_no_genera_error(): void
    {
        $this->v->entero('25', 'Edad');

        $this->assertFalse(
            $this->v->hayErrores(),
            'Un número entero en string no debe producir error.'
        );
    }

    // -----------------------------------------------------------------------
    // PRUEBA 7 - minLong(): cadena corta genera error
    // -----------------------------------------------------------------------
    /** @test */
    public function test_minLong_cadena_corta_genera_error(): void
    {
        $this->v->minLong('a', 3, 'Nombre de usuario');

        $this->assertTrue(
            $this->v->hayErrores(),
            'Una cadena más corta que el mínimo debe generar error.'
        );
    }

    // -----------------------------------------------------------------------
    // PRUEBA 8 - maxLong(): cadena demasiado larga genera error
    // -----------------------------------------------------------------------
    /** @test */
    public function test_maxLong_cadena_larga_genera_error(): void
    {
        $this->v->maxLong(str_repeat('x', 101), 100, 'Nombre completo');

        $this->assertTrue(
            $this->v->hayErrores(),
            'Una cadena que supera el máximo debe generar error.'
        );
    }

    // -----------------------------------------------------------------------
    // PRUEBA 9 - rango(): valor fuera de rango genera error
    // -----------------------------------------------------------------------
    /** @test */
    public function test_rango_valor_fuera_genera_error(): void
    {
        $this->v->rango(150, 1, 120, 'Edad');

        $this->assertTrue(
            $this->v->hayErrores(),
            'Un valor fuera del rango permitido debe generar error.'
        );
    }

    // -----------------------------------------------------------------------
    // PRUEBA 10 - enteroPositivo(): número negativo genera error
    // -----------------------------------------------------------------------
    /** @test */
    public function test_enteroPositivo_negativo_genera_error(): void
    {
        $this->v->enteroPositivo('-5', 'Duración');

        $this->assertTrue(
            $this->v->hayErrores(),
            'Un entero negativo no debe pasar la validación de enteroPositivo.'
        );
    }

    // -----------------------------------------------------------------------
    // PRUEBA 11 - limpiar(): reinicia los errores acumulados
    // -----------------------------------------------------------------------
    /** @test */
    public function test_limpiar_reinicia_errores(): void
    {
        $this->v->requerido('', 'Campo');
        $this->assertTrue($this->v->hayErrores(), 'Debe haber errores antes de limpiar.');

        $this->v->limpiar();

        $this->assertFalse(
            $this->v->hayErrores(),
            'Después de limpiar() no debe haber errores.'
        );
        $this->assertEmpty(
            $this->v->getErrores(),
            'El array de errores debe quedar vacío tras limpiar().'
        );
    }

    // -----------------------------------------------------------------------
    // PRUEBA 12 - encadenamiento: múltiples reglas acumulan errores
    // -----------------------------------------------------------------------
    /** @test */
    public function test_encadenamiento_acumula_multiples_errores(): void
    {
        $this->v
            ->requerido('', 'Usuario')
            ->email('no-email', 'Correo')
            ->entero('abc', 'Edad');

        $this->assertCount(
            3,
            $this->v->getErrores(),
            'Con tres validaciones fallidas deben acumularse exactamente 3 errores.'
        );
    }
}
