<?php

declare(strict_types=1);

namespace App\Librerias\Core;

use mysqli;
use mysqli_result;

/**
 * Capa de Abstracción de Base de Datos (MySQL).
 *
 * Esta clase extiende `Conexion` para obtener la conexión a la base de datos
 * y proporciona métodos para realizar operaciones CRUD (Create, Read, Update, Delete).
 * Simplifica la ejecución de consultas preparadas y el manejo de resultados.
 *
 * @version 2.1.0
 * @author Jules
 */
class Mysql extends Conexion
{
    /** @var mysqli La instancia del objeto de conexión mysqli. */
    protected mysqli $conexion;

    /**
     * Constructor de la clase Mysql.
     *
     * Llama al constructor de la clase padre (`Conexion`) para asegurar que la
     * conexión a la base de datos esté inicializada y luego la asigna a una
     * propiedad local para su uso en los métodos de esta clase.
     */
    public function __construct()
    {
        parent::__construct();
        $this->conexion = $this->getConexion();
    }

    /**
     * Ejecuta una consulta SELECT que devuelve una única fila.
     *
     * @param string     $query       La consulta SQL a ejecutar.
     * @param array|null $arrayvalues Los parámetros para la sentencia preparada.
     * @return array|null Un array asociativo con la fila o null si no hay resultados.
     */
    public function select(string $query, ?array $arrayvalues = null): ?array
    {
        if ($arrayvalues === null) {
            $result = $this->conexion->query($query);
            return $result->num_rows === 0 ? null : $result->fetch_assoc();
        }

        $stmt = $this->conexion->prepare($query);
        $stmt->execute($arrayvalues);
        $result = $stmt->get_result();
        return $result->num_rows === 0 ? null : $result->fetch_assoc();
    }

    /**
     * Ejecuta una consulta SELECT que devuelve un único valor de una columna.
     *
     * @param string     $query       La consulta SQL a ejecutar.
     * @param array|null $arrayvalues Los parámetros para la sentencia preparada.
     * @return mixed|null El valor de la primera columna de la primera fila o null.
     */
    public function select_column(string $query, ?array $arrayvalues = null): mixed
    {
        if ($arrayvalues === null) {
            $result = $this->conexion->query($query);
            return $result->num_rows === 0 ? null : $result->fetch_column();
        }

        $stmt = $this->conexion->prepare($query);
        $stmt->execute($arrayvalues);
        $result = $stmt->get_result();
        return $result->num_rows === 0 ? null : $result->fetch_column();
    }

    /**
     * Ejecuta una consulta SELECT que devuelve todas las filas.
     *
     * @param string     $query       La consulta SQL a ejecutar.
     * @param array|null $arrayvalues Los parámetros para la sentencia preparada.
     * @return array Un array de arrays asociativos con los resultados. Vacío si no hay.
     */
    public function select_all(string $query, ?array $arrayvalues = null): array
    {
        if ($arrayvalues === null) {
            $result = $this->conexion->query($query);
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        $stmt = $this->conexion->prepare($query);
        $stmt->execute($arrayvalues);
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Inserta un nuevo registro en la base de datos.
     *
     * @param string $query       La consulta SQL INSERT.
     * @param array  $arrayvalues Los parámetros para la sentencia preparada.
     * @return int El ID del registro insertado.
     */
    public function insert(string $query, array $arrayvalues): int
    {
        $stmt = $this->conexion->prepare($query);
        $stmt->execute($arrayvalues);
        return (int) $stmt->insert_id;
    }

    /**
     * Actualiza registros en la base de datos.
     *
     * @param string $query       La consulta SQL UPDATE.
     * @param array  $arrayvalues Los parámetros para la sentencia preparada.
     * @return int El número de filas afectadas.
     */
    public function update(string $query, array $arrayvalues): int
    {
        $stmt = $this->conexion->prepare($query);
        $stmt->execute($arrayvalues);
        return $stmt->affected_rows;
    }

    /**
     * Elimina registros de la base de datos.
     *
     * @param string     $query       La consulta SQL DELETE.
     * @param array|null $arrayvalues Los parámetros para la sentencia preparada (recomendado).
     * @return int El número de filas afectadas.
     */
    public function delete(string $query, ?array $arrayvalues = null): int
    {
        if ($arrayvalues === null) {
            // ADVERTENCIA: Ejecutar sin parámetros puede ser inseguro si la consulta
            // contiene datos no sanitizados. Se mantiene por compatibilidad.
            $result = $this->conexion->query($query);
            return $this->conexion->affected_rows;
        }

        $stmt = $this->conexion->prepare($query);
        $stmt->execute($arrayvalues);
        return $stmt->affected_rows;
    }

    /**
     * Ejecuta una consulta SELECT que devuelve todos los valores de una única columna.
     *
     * @param string     $query       La consulta SQL a ejecutar.
     * @param array|null $arrayvalues Los parámetros para la sentencia preparada.
     * @return array Un array plano con los valores de la columna. Vacío si no hay resultados.
     */
    public function select_all_column(string $query, ?array $arrayvalues = null): array
    {
        if ($arrayvalues === null) {
            $result = $this->conexion->query($query);
            return $result->num_rows === 0 ? [] : $this->fetch_column($result);
        }

        $stmt = $this->conexion->prepare($query);
        $stmt->execute($arrayvalues);
        $result = $stmt->get_result();
        return $result->num_rows === 0 ? [] : $this->fetch_column($result);
    }

    /**
     * Extrae una única columna de un resultado de consulta.
     *
     * @param mysqli_result $data El objeto de resultado de mysqli.
     * @return array Un array plano con los valores de la primera columna.
     */
    private function fetch_column(mysqli_result $data): array
    {
        // fetch_all(MYSQLI_NUM) devuelve un array de arrays, ej: [['val1'], ['val2']]
        $allRows = $data->fetch_all(MYSQLI_NUM);

        // array_column extrae la primera columna (índice 0) de cada sub-array.
        return array_column($allRows, 0);
    }
}