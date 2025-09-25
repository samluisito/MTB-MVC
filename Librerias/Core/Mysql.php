<?php

declare(strict_types=1);

class Mysql extends Conexion
{
    protected ?mysqli $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect;
    }

    public function select(string $query, ?array $arrayvalues = null): ?array
    {
        if ($arrayvalues === null) {
            $result = $this->conexion->query($query);
            return $result->num_rows === 0 ? null : $result->fetch_assoc();
        }

        $stmt = $this->conexion->prepare($query);
        $stmt->execute($arrayvalues);
        $response = $stmt->get_result();
        return $response->num_rows === 0 ? null : $response->fetch_assoc();
    }

    public function select_column(string $query, ?array $arrayvalues = null): mixed
    {
        if ($arrayvalues === null) {
            $result = $this->conexion->query($query);
            return $result->num_rows === 0 ? null : $result->fetch_column();
        }

        $stmt = $this->conexion->prepare($query);
        $stmt->execute($arrayvalues);
        $response = $stmt->get_result();
        return $response->num_rows === 0 ? null : $response->fetch_column();
    }

    public function select_all(string $query, ?array $arrayvalues = null): array
    {
        if ($arrayvalues === null) {
            $result = $this->conexion->query($query);
            return $result->num_rows === 0 ? [] : (array) $result->fetch_all(MYSQLI_ASSOC);
        }

        $stmt = $this->conexion->prepare($query);
        $stmt->execute($arrayvalues);
        $response = $stmt->get_result();
        return $response->num_rows === 0 ? [] : (array) $response->fetch_all(MYSQLI_ASSOC);
    }

    public function select_all_column(string $query, ?array $arrayvalues = null): array
    {
        if ($arrayvalues === null) {
            $result = $this->conexion->query($query);
            return $result->num_rows === 0 ? [] : $this->fetch_column($result);
        }

        $stmt = $this->conexion->prepare($query);
        $stmt->execute($arrayvalues);
        $response = $stmt->get_result();
        return $response->num_rows === 0 ? [] : $this->fetch_column($response);
    }

    private function fetch_column(mysqli_result $data): array
    {
        $column = [];
        while ($row = $data->fetch_array(MYSQLI_NUM)) {
            $column[] = $row[0];
        }
        return $column;
    }

    public function insert(string $query, array $arrayvalues): int
    {
        $stmt = $this->conexion->prepare($query);
        $stmt->execute($arrayvalues);
        return (int) $this->conexion->insert_id;
    }

    public function update(string $query, array $arrayvalues): int
    {
        $stmt = $this->conexion->prepare($query);
        $stmt->execute($arrayvalues);
        return $stmt->affected_rows;
    }

    public function delete(string $query, ?array $arrayvalues = null): int
    {
        $stmt = $this->conexion->prepare($query);
        if ($arrayvalues !== null) {
            $stmt->execute($arrayvalues);
        } else {
            $stmt->execute();
        }
        return $stmt->affected_rows;
    }
}