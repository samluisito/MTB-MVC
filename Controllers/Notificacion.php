<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Librerias\Core\Controllers;
use DateTime;

class Notificacion extends Controllers
{
    public function __construct()
    {
        parent::__construct();
    }

    public function notificacion()
    {
        // Método vacío, posiblemente para una futura vista de notificaciones.
    }

    public function set_notificacion($tipo, $id_tipo, $leido = null)
    {
        return $this->model->insertNotificacion($tipo, $id_tipo, $leido);
    }

    public function getNotificacionesNoLeidasMenu()
    {
        $fecha1 = new DateTime(); //fecha inicial
        $response = $this->model->selectNotificacionesNoLeidas();
        $notificaciones = ['conteototal' => 0];

        foreach ($response as $notificacion) {
            $notificaciones['conteototal'] += $notificacion['cantidad'];
            $notificacion['fecha'] = diferencia_entre_fechas($fecha1, $notificacion['datecreated']);

            if ($notificacion['tipo'] === 'pedido') {
                $notificaciones['pedido'] = $notificacion;
            }
            if ($notificacion['tipo'] === 'contacto') {
                $notificaciones['contacto'] = $notificacion;
            }
        }
        return $notificaciones;
    }

    public function getIdNotificacionesTipo($tipo, $id_tipo)
    {
        return $this->model->selectNotificacion($tipo, $id_tipo);
    }

    public function updateNotificacionID($tipo, $id_tipo, $leido)
    {
        return $this->model->updateNotificacion($tipo, $id_tipo, $leido);
    }
}