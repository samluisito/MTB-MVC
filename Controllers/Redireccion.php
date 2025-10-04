<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;

class Redireccion extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    public function redireccion()
    {
        $this->views->getView("Redireccion");
    }
}