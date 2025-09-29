<?php

declare(strict_types=1);

$bd_id = $_SESSION['base']['idcte'];
define('FILE_SISTEM_CLIENTE', $bd_id . '/');

// Variables Constantes que se usan en todo el proyectodep
//DELIMITADORES DE DECIMAL Y MILLAR EJEMPLO : 24,135.00

//DATOS ENCRIPTAT Y DESENCRIPTAR 
const KEY = 'mitiendabit';
const METHODENCRIPT = 'AES-128-ECB';
const ESTADOS_PEDIDOS = array('Completo', 'Aprobado', 'Cancelado', 'Reembolsado', 'Pendiente', 'Entregado');

const DIR_MEDIA = BASE_URL . '/Assets/'; //retorna la URL base del sistema + la carpeta Assets donde sen encuentras los archivos media
const DIR_IMAGEN = BASE_URL . '/uploads/' . FILE_SISTEM_CLIENTE;
//retorna la URL base del sistema + la carpeta Assets donde sen encuentras los archivos media

const CONTROLADOR_DB = 'MySQLi';
//const CONTROLADOR_DB = 'PDO';
//seteamos la zona horaria para todos

