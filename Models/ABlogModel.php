<?php

declare(strict_types=1);

namespace App\Models;

use App\Librerias\Core\Mysql;

class ABlogModel extends Mysql
{
    public int $intIdEntrada;
    public int $intAutorid;
    public string $strTitulo;
    public string $strUrl;
    public string $strDescripcion;
    public string $strTxt_entrada;
    public string $strTags;
    public int $intCategoriaId;
    public $DateUpdate;
    public int $intStatus;
    public string $strImagen;

    public function __construct()
    {
        parent::__construct();
    }

    public function selectEntradas()
    {
        $sql = "SELECT be.identrada,
                CONCAT(pe.nombres, ' ', pe.apellidos) as autor,
                be.titulo,
                be.descripcion,
                c.nombre as categoria,
                be.status
                FROM blog_entrada be
                INNER JOIN producto_categoria c ON be.categoriaid = c.idcategoria
                INNER JOIN persona pe ON be.autorid = pe.idpersona
                WHERE be.status < 2";
        return $this->select_all($sql);
    }

    public function imgEntrada($identrada)
    {
        $intIdEntrada = $identrada;
        $sql = "SELECT min(id), entradaid, img FROM imagen WHERE entradaid = ?";
        return $this->select($sql, [$intIdEntrada]);
    }

    public function entradaEnUso($idEntrada)
    {
        $this->intIdEntrada = $idEntrada;
        $sql = "SELECT * FROM pedido_detalle WHERE entradaid = ?";
        return $this->select($sql, [$this->intIdEntrada]);
    }

    public function insertEntrada(int $autorid, string $titulo, string $url, string $descripcion, string $txt_texto, string $tags, int $categoriaId, int $status)
    {
        $this->intAutorid = $autorid;
        $this->strTitulo = $titulo;
        $this->strUrl = $url;
        $this->strDescripcion = $descripcion;
        $this->strTxt_entrada = $txt_texto;
        $this->strTags = $tags;
        $this->intCategoriaId = $categoriaId;
        $this->intStatus = $status;

        $sql = "SELECT identrada FROM blog_entrada WHERE url = ?";
        $request = $this->select($sql, [$this->strUrl]);

        if (empty($request)) {
            $query_insert = "INSERT INTO blog_entrada (autorid, titulo, url, descripcion, txt_entrada, tags, categoriaid, status) VALUES (?,?,?,?,?,?,?,?)";
            $arrData = [$this->intAutorid, $this->strTitulo, $this->strUrl, $this->strDescripcion, $this->strTxt_entrada, $this->strTags, $this->intCategoriaId, $this->intStatus];
            return $this->insert($query_insert, $arrData);
        }
        return "exist";
    }

    public function updateEntrada(int $idEntrada, string $titulo, string $url, string $descripcion, string $txt_texto, string $tags, int $categoriaId, int $status)
    {
        $this->intIdEntrada = $idEntrada;
        $this->strTitulo = $titulo;
        $this->strUrl = $url;
        $this->strDescripcion = $descripcion;
        $this->strTxt_entrada = $txt_texto;
        $this->strTags = $tags;
        $this->intCategoriaId = $categoriaId;
        $this->intStatus = $status;

        $sql = "SELECT identrada FROM blog_entrada WHERE identrada != ? AND url = ?";
        $request = $this->select($sql, [$this->intIdEntrada, $this->strUrl]);

        if (empty($request)) {
            $sql = "UPDATE blog_entrada SET titulo = ?, url = ?, descripcion = ?, txt_entrada = ?, tags = ?, categoriaid = ?, dateupdate = NOW(), status = ? WHERE identrada = ?";
            $arrData = [$this->strTitulo, $this->strUrl, $this->strDescripcion, $this->strTxt_entrada, $this->strTags, $this->intCategoriaId, $this->intStatus, $this->intIdEntrada];
            return $this->update($sql, $arrData);
        }
        return 'exist';
    }

    public function insertImage(int $idEntrada, string $imgTitulo)
    {
        $this->intIdEntrada = $idEntrada;
        $this->strImagen = $imgTitulo;
        $sql = 'INSERT INTO imagen(entradaid,img) VALUES (?,?)';
        $arrData = [$this->intIdEntrada, $this->strImagen];
        return $this->insert($sql, $arrData);
    }
}