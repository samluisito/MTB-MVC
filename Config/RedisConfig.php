<?php

declare(strict_types=1);

/**
 * Configuración del Servidor Redis
 *
 * Define los detalles de conexión para el servidor Redis utilizado para el almacenamiento en caché.
 */

// El hostname o la dirección IP de tu servidor Redis.
define('REDIS_HOST', '127.0.0.1');

// El puerto en el que tu servidor Redis está escuchando.
define('REDIS_PORT', 6379);

// Opcional: Descomenta y establece la contraseña si tu servidor Redis requiere autenticación.
// define('REDIS_PASSWORD', 'tu_contraseña_de_redis');