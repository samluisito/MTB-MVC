<?php
declare(strict_types=1);

class CalendarModel extends Mysql {

    private $intIdUser;
    private $strIdentificacion;
    private $strNombre;
    private $strApellido;
    private $strEmail;
    private $intTelefono;
    private $intTipoRolId;
    private $intStatus;
    private $strPassword;
    private $strtoken;
    private $strNit;
    private $strNombreFiscal;
    private $strDireccionFiscal;

    public function __construct() {
//echo 'mensaje desde el modelo home';
        parent::__construct();
    }

    
    
    public function insertUsuario(string $identificacion,
            string $nombre, string $Apellido, int $telefono,
            string $email, string $password, int $idtporol,
            int $status) {

        $this->strIdentificacion = $identificacion;
        $this->strNombre = $nombre;
        $this->strApellido = $Apellido;
        $this->strEmail = $email;
        $this->intTelefono = $telefono;
        $this->intTipoRolId = $idtporol;
        $this->intStatus = $status;
        $this->strPassword = $password;
        $return = 0;


        //consultamos la existencia de una identificacion o imail duplicado
        $sql = "SELECT * FROM persona WHERE 
           email_user = '{$this->strEmail}'
           or identificacion ='{$this->strIdentificacion}'";

        $request = $this->select_all($sql);

        if (empty($request)) {
            // si la consulta es nul  entonce insertamos el Usuario
            $query_insert = "INSERT INTO persona (
                	identificacion,
                        nombres, apellidos,
                        telefono, email_user,
                        password,
                        rolid, status) VALUES (?,?,?,?,?,?,?,?)";

            $arrData = array(
                $this->strIdentificacion,
                $this->strNombre,
                $this->strApellido,
                $this->intTelefono,
                $this->strEmail,
                $this->strPassword,
                $this->intTipoRolId,
                $this->intStatus);

            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function selectUsuarios() {
//EXTRAE ROLES
        $sql = "SELECT a.idpersona, a.identificacion,
                       a.nombres, a.apellidos,
                       a.telefono, a.email_user,
                       a.status, b.nombrerol
                FROM persona a INNER JOIN rol b
                ON a.rolid = b.idrol
                WHERE  a.status != 0";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectUser(int $idUser) {
//EXTRAE EXTRAE UN ROL, PARAMETRO DE ENTRADA EL ID A BUSCAR, DEVUELVE UN ARRAY CON LOS DATOS DEL ROL
        $this->intIdUser = $idUser;
        $sql = "SELECT a.idpersona, a.identificacion,
                       a.nombres, a.apellidos,
                       a.telefono, a.email_user,
                       a.nit, a.nombrefiscal,
                       a.direccionfiscal,
                       b.idrol, b.nombrerol,
                       a.status, DATE_FORMAT(a.datecreated, '%d-%m-%Y') as fechaRegistro 
                FROM persona a 
                INNER JOIN rol b
                ON a.rolid = b.idrol
                WHERE a.idpersona = '{$this->intIdUser}' ";
        $request = $this->select($sql);
        return $request;
    }

    public function updateUsuario(int $idUser, string $identificacion,
            string $nombre, string $Apellido, int $telefono, string $email,
            string $password, int $idtporol, int $status) {

        $this->intIdUser = $idUser;
        $this->strIdentificacion = $identificacion;
        $this->strNombre = $nombre;
        $this->strApellido = $Apellido;
        $this->strEmail = $email;
        $this->intTelefono = $telefono;
        $this->intTipoRolId = $idtporol;
        $this->intStatus = $status;
        $this->strPassword = $password;

        $return = 0;

        //consultamos la existencia de una identificacion o imail duplicado
        $sql = "SELECT * FROM persona 
            WHERE  email_user = '{$this->strEmail}' AND idpersona != '{$this->intIdUser}'
            or identificacion ='{$this->strIdentificacion}'AND idpersona != '{$this->intIdUser}'";

        $request = $this->select_all($sql);



        if (empty($request)) {
            if ($this->strPassword != "") {
                $sql_update = "UPDATE persona SET 
                	identificacion = ?,
                        nombres = ?, apellidos = ?,
                        telefono = ?, email_user = ?,
                        password = ?,
                        rolid = ?, status = ? WHERE idpersona = '{$this->intIdUser}'";

                $arrData = array(
                    $this->strIdentificacion,
                    $this->strNombre,
                    $this->strApellido,
                    $this->intTelefono,
                    $this->strEmail,
                    $this->strPassword,
                    $this->intTipoRolId,
                    $this->intStatus);
            } else {
                $sql_update = "UPDATE persona SET 
                	identificacion = ?,
                        nombres = ?, apellidos = ?,
                        telefono = ?, email_user = ?,
                        rolid = ?, status = ? WHERE idpersona = '{$this->intIdUser}'";

                $arrData = array(
                    $this->strIdentificacion,
                    $this->strNombre,
                    $this->strApellido,
                    $this->intTelefono,
                    $this->strEmail,
                    //$this->strPassword,
                    $this->intTipoRolId,
                    $this->intStatus);
            }

            $request_update = $this->update($sql_update, $arrData);
            //$return = $request_insert;
        } else {
            return "exist";
        }
        return $request_update;
    }

    public function deleteUser($idPersona) {

        $this->intIdUser = $idPersona;
        $sql = "UPDATE persona SET status = ? WHERE idpersona = $this->intIdUser";
        $arrData = array(0);
        $request = $this->update($sql, $arrData);
        return $request;
    }

    public function updatePerfil(int $idUser, string $identificacion,
            string $nombre, string $Apellido,
            int $telefono, string $password
    ) {

        $this->intIdUser = $idUser;
        $this->strIdentificacion = $identificacion;
        $this->strNombre = $nombre;
        $this->strApellido = $Apellido;
        $this->intTelefono = $telefono;
        $this->strPassword = $password;

        if ($this->strPassword != "") {
            $sql_update = "UPDATE persona SET 
                	identificacion = ?,
                        nombres = ?, apellidos = ?,
                        telefono = ?,password = ?
                        WHERE idpersona = '{$this->intIdUser}'";

            $arrData = array(
                $this->strIdentificacion,
                $this->strNombre,
                $this->strApellido,
                $this->intTelefono,
                $this->strPassword,
            );
        } else {
            $sql_update = "UPDATE persona SET 
                	identificacion = ?,
                        nombres = ?, apellidos = ?,
                        telefono = ?
                        WHERE idpersona = '{$this->intIdUser}'";

            $arrData = array(
                $this->strIdentificacion,
                $this->strNombre,
                $this->strApellido,
                $this->intTelefono,
            );
        }

        $request_update = $this->update($sql_update, $arrData);
        return $request_update;
    }

    public function updateDataFiscal(int $intIdUser,
            string $strNit,
            string $strNombreFiscal,
            string $strDireccionFiscal) {

        $this->intIdUser = $intIdUser;
        $this->strNit = $strNit;
        $this->strNombreFiscal = $strNombreFiscal;
        $this->strDireccionFiscal = $strDireccionFiscal;

        $sql_update = "UPDATE persona SET 
                	nit = ?,
                        nombrefiscal = ?, direccionfiscal = ?
                        WHERE idpersona = '{$this->intIdUser}'";

        $arrData = array(
        $this->strNit,
        $this->strNombreFiscal,
        $this->strDireccionFiscal);
        
        $request_update = $this->update($sql_update, $arrData);
        return $request_update;
    }

}
