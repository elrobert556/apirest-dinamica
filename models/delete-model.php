<?php 

require_once 'conexion.php';
require_once 'get-model.php';

class DeleteModel{

    /* Peticion DELETE para eliminar datos de forma dinamica */
    static public function deleteData($table, $id, $nameId){

        /* Validar el ID */
        $response = GetModel::getDataFilter($table, $nameId, $nameId, $id, null, null, null, null);

        if (empty($response)) {

            return null;
            
        }

        /* Eliminar registro */

        $sql = "DELETE FROM $table WHERE $nameId = :$nameId";

        $link = Conexion::conectar();
        $stmt = $link->prepare($sql);

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