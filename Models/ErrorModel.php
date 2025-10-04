<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

/**
 * Modelo para el controlador de errores.
 * Actualmente no tiene métodos específicos.
 */
class ErrorModel extends Mysql
{
    public function __construct()
    {
        parent::__construct();
    }
}