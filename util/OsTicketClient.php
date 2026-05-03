<?php
/**
 * Cliente para crear tickets en osTicket vía su API REST.
 *
 * Documentación oficial:
 *   https://docs.osticket.com/en/latest/Developer%20Documentation/API/API.html
 *
 * Configuración en config/conf.ini:
 *   osticket_enabled   = 1                                    (0 para desactivar)
 *   osticket_url       = "http://host/osTicket/upload/api/http.php/tickets.json"
 *   osticket_api_key   = "CLAVE_API_GENERADA_EN_OSTICKET"
 *   osticket_topic_id  = 1
 */
class OsTicketClient {

    /** Devuelve el array de configuración de osTicket. */
    private static function config(): array {
        $config = parse_ini_file(__DIR__ . '/../config/conf.ini');
        return [
            'enabled'  => (int)($config['osticket_enabled']  ?? 0) === 1,
            'url'      => $config['osticket_url']      ?? '',
            'api_key'  => $config['osticket_api_key']  ?? '',
            'topic_id' => (int)($config['osticket_topic_id'] ?? 0),
        ];
    }

    /** ¿Está la integración configurada y activa? */
    public static function estaActivo(): bool {
        $c = self::config();
        return $c['enabled'] && $c['url'] !== '' && $c['api_key'] !== '';
    }

    /**
     * Crea un ticket en osTicket. Devuelve el número/ID del ticket en caso de éxito,
     * o false si falla (errores HTTP, conexión, etc.).
     */
    public static function crearTicket(string $nombre, string $email, string $asunto, string $mensaje) {
        $c = self::config();
        if (!$c['enabled'] || $c['url'] === '' || $c['api_key'] === '') {
            return false;
        }

        $payload = [
            'alert'       => true,
            'autorespond' => true,
            'source'      => 'API',
            'name'        => $nombre,
            'email'       => $email,
            'subject'     => $asunto,
            'message'     => $mensaje,
            'ip'          => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            'topicId'     => $c['topic_id'],
        ];

        $ch = curl_init($c['url']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Expect:',
                'X-API-Key: ' . $c['api_key'],
            ],
            CURLOPT_TIMEOUT        => 8,
            CURLOPT_CONNECTTIMEOUT => 4,
        ]);

        $respuesta = curl_exec($ch);
        $estado    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errCurl   = curl_error($ch);
        curl_close($ch);

        if ($estado === 201) {
            return trim((string)$respuesta);
        }

        error_log("[OsTicketClient] Falló creación de ticket. HTTP {$estado}. cURL: {$errCurl}. Resp: {$respuesta}");
        return false;
    }
}
