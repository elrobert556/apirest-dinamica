<?php 

require_once 'conexion.php';
require_once 'get-model.php';

class PutModel{

    /* Peticion PUT para crear datos de forma dinamica */
    static public function putData($table, $data, $id, $nameId){

        /* Validar el ID */
        $response = GetModel::getDataFilter($table, $nameId, $nameId, $id, null, null, null, null);

        if (empty($response)) {

            return null;
            
        }

        /* Actualizar registro */
        $set = "";

        foreach ($data as $key => $value) {
            
            $set .= $key." = :".$key.",";

        }

        $set = substr($set, 0, -1);

        $sql = "UPDATE $table SET $set WHERE $nameId = :$nameId";

        $link = Conexion::conectar();
        $stmt = $link->prepare($sql);

        foreach ($data as $key => $value) {
            
            $stmt->bindParam(":".$key, $data[$key], PDO::PARAM_STR);

        }

        $stmt->bindParam(":".$nameId, $id, PDO::PARAM_STR);

        if($stmt -> execute()){

            $response = array(

                "coment" => "El proceso se ejecuto con exito"

            );

            return $response;

        }else{

            return $link->errorInfo();

        }

    }

    /* Peticion PUT para ocultar un registro de forma dinamica */
    static public function putStatusData($table, $data, $id, $nameId){

        /* Validar el ID */
        $response = GetModel::getDataFilter($table, $nameId, $nameId, $id, null, null, null, null);

        if (empty($response)) {

            return null;
            
        }

        /* Actualizar registro */
        $set = "";

        foreach ($data as $key => $value) {
            
            $set .= $key." = :".$key.",";

        }

        $set = substr($set, 0, -1);

        $sql = "UPDATE $table SET $set WHERE $nameId = :$nameId";

        $link = Conexion::conectar();
        $stmt = $link->prepare($sql);

        foreach ($data as $key => $value) {
            
            $stmt->bindParam(":".$key, $data[$key], PDO::PARAM_STR);

        }

        $stmt->bindParam(":".$nameId, $id, PDO::PARAM_STR);

        if($stmt -> execute()){

            $response = array(

                "coment" => "El proceso se ejecuto con exito"

            );

            return $response;

        }else{

            return $link->errorInfo();

        }

    }

}