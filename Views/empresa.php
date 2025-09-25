<?php

require_once '../controllers/Empleado.php';
require_once '../controllers/Cliente.php';


$objempleado = new Empleado(1234, "Andres Perez", 25);
$objempleado->setPuesto('Admin');

echo $objempleado->getDatosPersonales();
echo "Puesto: ".$objempleado->getPuesto();
echo "<br><hr>";


$objempleado = new Cliente(2345, "Andres Perez", 24);
$objempleado->setCredito(1000);

echo $objempleado->getDatosPersonales();
echo "Creditos: ".$objempleado->getCredito();
echo "<br><hr>";