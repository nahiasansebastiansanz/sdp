<?php
class ValidadorPHP {

    /** @var string[] Lista de mensajes de error acumulados */
    private array $errores = [];

    /*  Reglas  */

    /** Campo obligatorio (no vacío tras trim) */
    public function requerido($valor, string $label): self {
        if ($valor === null || trim((string)$valor) === '') {
            $this->errores[] = "«{$label}» es obligatorio.";
        }
        return $this;
    }

    /** Longitud mínima */
    public function minLong($valor, int $min, string $label): self {
        if ($valor !== null && mb_strlen(trim((string)$valor)) > 0
            && mb_strlen(trim((string)$valor)) < $min) {
            $this->errores[] = "«{$label}» debe tener al menos {$min} caracteres.";
        }
        return $this;
    }

    /** Longitud máxima */
    public function maxLong($valor, int $max, string $label): self {
        if ($valor !== null && mb_strlen((string)$valor) > $max) {
            $this->errores[] = "«{$label}» no puede superar {$max} caracteres.";
        }
        return $this;
    }

    /** Formato email */
    public function email($valor, string $label): self {
        if ($valor !== null && trim((string)$valor) !== ''
            && !filter_var(trim((string)$valor), FILTER_VALIDATE_EMAIL)) {
            $this->errores[] = "«{$label}» no tiene un formato de email válido.";
        }
        return $this;
    }

    /** Solo entero */
    public function entero($valor, string $label): self {
        if ($valor !== null && trim((string)$valor) !== ''
            && !ctype_digit(ltrim((string)$valor, '-'))) {
            $this->errores[] = "«{$label}» debe ser un número entero.";
        }
        return $this;
    }

    /** Rango numérico (inclusive) */
    public function rango($valor, int $min, int $max, string $label): self {
        if ($valor !== null && trim((string)$valor) !== '') {
            $n = (int)$valor;
            if ($n < $min || $n > $max) {
                $this->errores[] = "«{$label}» debe estar entre {$min} y {$max}.";
            }
        }
        return $this;
    }

    /** Valor mínimo */
    public function min($valor, int $min, string $label): self {
        if ($valor !== null && trim((string)$valor) !== '' && (int)$valor < $min) {
            $this->errores[] = "«{$label}» debe ser al menos {$min}.";
        }
        return $this;
    }

    /** Valor debe estar en una lista de valores permitidos */
    public function enLista($valor, array $lista, string $label): self {
        if ($valor !== null && trim((string)$valor) !== ''
            && !in_array($valor, $lista, true)) {
            $this->errores[] = "«{$label}» tiene un valor no permitido.";
        }
        return $this;
    }

    /** Solo caracteres alfanuméricos + _ . - */
    public function alfanumerico($valor, string $label): self {
        if ($valor !== null && trim((string)$valor) !== ''
            && !preg_match('/^[a-zA-Z0-9_.\-]+$/', (string)$valor)) {
            $this->errores[] = "«{$label}» solo puede contener letras, números, guiones y puntos.";
        }
        return $this;
    }

    /** Entero positivo (>0) */
    public function enteroPositivo($valor, string $label): self {
        $this->entero($valor, $label);
        if (!$this->hayErrores() && $valor !== null && trim((string)$valor) !== ''
            && (int)$valor < 1) {
            $this->errores[] = "«{$label}» debe ser un número positivo.";
        }
        return $this;
    }

    /*  Estado  */

    public function hayErrores(): bool {
        return !empty($this->errores);
    }

    /** @return string[] */
    public function getErrores(): array {
        return $this->errores;
    }

    public function limpiar(): self {
        $this->errores = [];
        return $this;
    }

    /*  Redirección con errores  */

    /**
     * Guarda los errores en sesión y redirige.
     * @param string $url  Ruta destino (relativa al controller)
     */
    public function redirigirConErrores(string $url): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['errores_php'] = $this->errores;
        header("Location: {$url}");
        exit();
    }
}
?>
