<?php

declare(strict_types=1);

require_once __DIR__ . '/../../Config/RedisConfig.php';

/**
 * Clase Singleton para manejar las operaciones de caché con Redis.
 */
class RedisCache
{
    private static ?self $instance = null;
    private ?Redis $redis;

    /**
     * El constructor es privado para prevenir la instanciación directa.
     * Intenta conectarse al servidor Redis.
     */
    private function __construct()
    {
        try {
            $this->redis = new Redis();
            $this->redis->connect(REDIS_HOST, REDIS_PORT);

            if (defined('REDIS_PASSWORD')) {
                $this->redis->auth(REDIS_PASSWORD);
            }
        } catch (RedisException $e) {
            // Si la conexión falla, se registra el error y se establece redis a null
            // para que la aplicación pueda continuar funcionando sin caché.
            error_log('Error en la conexión con Redis: ' . $e->getMessage());
            $this->redis = null;
        }
    }

    /**
     * Obtiene la instancia única de RedisCache.
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Obtiene un valor de la caché.
     *
     * @param string $key La clave del item a obtener.
     * @return mixed El valor de la caché, o false si no se encuentra o Redis no está disponible.
     */
    public function get(string $key)
    {
        if (!$this->isConnected()) {
            return false;
        }

        $value = $this->redis->get($key);
        // El valor se almacena serializado, por lo que se deserializa al recuperarlo.
        return $value ? unserialize($value) : false;
    }

    /**
     * Almacena un valor en la caché.
     *
     * @param string $key   La clave bajo la cual se almacenará el valor.
     * @param mixed  $value El valor a almacenar.
     * @param int    $ttl   El tiempo de vida para el item en la caché (en segundos).
     * @return bool True si se guardó correctamente, false en caso contrario.
     */
    public function set(string $key, $value, int $ttl = 3600): bool
    {
        if (!$this->isConnected()) {
            return false;
        }

        // Serializa el valor para permitir el almacenamiento de arrays y objetos.
        return $this->redis->setex($key, $ttl, serialize($value));
    }

    /**
     * Elimina una clave de la caché.
     *
     * @param string $key La clave a eliminar.
     * @return int El número de claves que fueron eliminadas.
     */
    public function del(string $key): int
    {
        if (!$this->isConnected()) {
            return 0;
        }

        return $this->redis->del($key);
    }

    /**
     * Verifica si la conexión con Redis está activa.
     */
    public function isConnected(): bool
    {
        // Usar PING para verificar una conexión activa.
        return $this->redis !== null && $this->redis->ping() !== false;
    }

    // Prevenir la clonación y deserialización para forzar el patrón Singleton.
    private function __clone() {}
    public function __wakeup() {}
}