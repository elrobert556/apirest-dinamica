<?php 

require_once 'models/conexion.php';
require_once 'controllers/put-controller.php';

/* Actualizar registro */
if (isset($_GET["id"]) && isset($_GET["nameId"])) {
    
    /* Capturar datos del formulario */
    $data = array();

    parse_str(file_get_contents('php://input'), $data);

    $columns = array();

    foreach (array_keys($data) as $key => $value) {
        
        array_push($columns,$value);

    }

    array_push($columns,$_GET["nameId"]);

    $columns = array_unique($columns);
    
    /* Validar la tabla y las columnas */
    if(empty(Conexion::getColumnsData($table,$columns))){

        $json = array(
            'status' => 400,
            'results' => 'ERROR: Los campos no coinciden con la base de datos'
        );
        
        echo json_encode($json, http_response_code($json["status"]));
    
        return;

    }

    /* Solicitar respuesta del controlador para crear datos en la tabla */
    $response = new PutController();
    $response -> putData($table, $data, $_GET["id"], $_GET["nameId"]);

}

/* Ocultar registro con campo status */
if (isset($_GET["deleteId"]) && isset($_GET["nameId"])) {
    
    /* Capturar datos del formulario */
    $data = array();

    parse_str(file_get_contents('php://input'), $data);

    $columns = array();

    foreach (array_keys($data) as $key => $value) {
        
        array_push($columns,$value);

    }

    array_push($columns,$_GET["nameId"]);

    $columns = array_unique($columns);
    
    /* Validar la tabla y las columnas */
    if(empty(Conexion::getColumnsData($table,$columns))){

        $json = array(
            'status' => 400,
            'results' => 'ERROR: Los campos no coinciden con la base de datos'
        );
        
        echo json_encode($json, http_response_code($json["status"]));
    
        return;

    }

    if (isset($_GET["token"])) {

        /* Peticion PUT para usuarios no autorizados */
        if ($_GET["token"] == "no" && isset($_GET["except"])) {

            /* Validar la tabla y las columnas */
            $columns = array($_GET["except"]);

            if(empty(Conexion::getColumnsData($table,$columns))){

                $json = array(
                    'status' => 400,
                    'results' => 'ERROR: Los campos no coinciden con la base de datos'
                );
                
                echo json_encode($json, http_response_code($json["status"]));
            
                return;

            }

            /* Solicitar respuesta del controlador para crear datos en la tabla */
            $response = new PutController();
            $response -> putStatusData($table, $data, $_GET["deleteId"], $_GET["nameId"]);

        /* Peticion PUT para usuarios autorizados */
        }else{

            $table_token = $_GET["table"] ?? "clientes";
            $table_token = $_GET["table"] ?? "clientes";
            $suffix = $_GET["suffix"] ?? "cliente";

            $validate = Conexion::tokenValidate($_GET["token"],$table_token,$suffix);

            if ($validate == "ok") {

                /* Solicitar respuesta del controlador para editar datos en la tabla */
                $response = new PutController();
                $response -> putStatusData($table, $data, $_GET["deleteId"], $_GET["nameId"]);

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