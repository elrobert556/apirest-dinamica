<?php 

require_once 'models/conexion.php';
require_once 'controllers/delete-controller.php';

if (isset($_GET["id"]) && isset($_GET["nameId"])) {

    $columns = array($_GET["nameId"]);
    
    /* Validar la tabla y las columnas */
    if(empty(Conexion::getColumnsData($table,$columns))){

        $json = array(
            'status' => 400,
            'results' => 'ERROR: Los campos no coinciden con la base de datos'
        );
        
        echo json_encode($json, http_response_code($json["status"]));
    
        return;

    }

    /* Peticion DELETE para usuarios autorizados */
    if (isset($_GET["token"])) {

        $table_token = $_GET["table"] ?? "clientes";
        $table_token = $_GET["table"] ?? "clientes";
        $suffix = $_GET["suffix"] ?? "cliente";

        $validate = Conexion::tokenValidate($_GET["token"],$table_token,$suffix);

        if ($validate == "ok") {

            /* Solicitar respuesta del controlador para eliminar datos en la tabla */
            $response = new DeleteController();
            $response -> deleteData($table, $_GET["id"], $_GET["nameId"]);

        }

        if($validate == "expired"){
                
            /* Error si el token ha expirado */
            $json = array(
                'status' => 303,
                'results' => "ERROR: El token ha expirado"
            );

            echo json_encode($json, http_response_code($json["status"]));
        
            return;

        }

        if($validate == "no-auth"){

            /* Error cuando el token no coincide */
            $json = array(
                'status' => 400,
                'results' => "ERROR: El usuario no esta autorizado"
            );

            echo json_encode($json, http_response_code($json["status"]));
        
            return;

        }

    }else{
            
        /* Error cuando no envia token */
        $json = array(
            'status' => 400,
            'results' => "ERROR: Autorizacion requerida"
        );

        echo json_encode($json, http_response_code($json["status"]));
    
        return;

    }

}