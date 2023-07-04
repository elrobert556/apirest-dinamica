<?php 

require_once 'models/conexion.php';
require_once 'controllers/post-controller.php';

if (isset($_POST)) {

    $columns = array();

    foreach (array_keys($_POST) as $key => $value) {
        
        array_push($columns,$value);

    }
    //echo '<pre>'; print_r(Conexion::getColumnsData($table,$columns)); echo '</pre>';

    /* Validar la tabla y las columnas */
    if(empty(Conexion::getColumnsData($table,$columns))){

        $json = array(
            'status' => 400,
            'results' => 'ERROR: Los campos no coinciden con la base de datos'
        );
        
        echo json_encode($json, http_response_code($json["status"]));
    
        return;

    }
    
    $response = new PostController();

    /* Peticion POST para el registro de usuario */
    if (isset($_GET['register']) && $_GET['register'] == true) {

        $suffix = $_GET['suffix'] ?? "cliente";
        $response -> postRegister($table, $_POST, $suffix);

    /* Peticion POST para el login de usuario */
    }else if (isset($_GET['login']) && $_GET['login'] == true) {
        
        $suffix = $_GET['suffix'] ?? "cliente";
        $response -> postLogin($table, $_POST, $suffix);

    }else{

        if (isset($_GET["token"])) {

            /* Peticion POST para usuarios no autorizados */
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
                $response -> postData($table, $_POST);

            /* Peticion POST para usuarios autorizados */
            }else{

                $table_token = $_GET["table"] ?? "clientes";
                $suffix = $_GET["suffix"] ?? "cliente";

                $validate = Conexion::tokenValidate($_GET["token"],$table_token,$suffix);

                if ($validate == "ok") {
                    
                    /* Solicitar respuesta del controlador para crear datos en la tabla */
                    $response -> postData($table, $_POST);

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
            
            /* Error cuando el token no se encuentra */
            $json = array(
                'status' => 400,
                'results' => "ERROR: Autorizacion requerida"
            );

            echo json_encode($json, http_response_code($json["status"]));
        
            return;

        }

    }


}