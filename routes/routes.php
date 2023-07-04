<?php
//echo '<pre>'; print_r($_SERVER['REQUEST_URI']); echo '</pre>';
//return;
require_once 'models/conexion.php';
require_once 'controllers/get-controller.php';

$routesArray = explode("/", $_SERVER['REQUEST_URI']);
$routesArray = array_filter($routesArray);

if (count($routesArray) == 1) {

    $json = array(
        'status' => 404,
        'results' => 'Not Found'
    );
    
    echo json_encode($json, http_response_code($json["status"]));

    return;

}

if (count($routesArray) == 2 && isset($_SERVER['REQUEST_METHOD'])) {

    
    $table = explode("?", $routesArray[2])[0];

    /* Validar llave secreta*/
    if (!isset(getallheaders()["Authorization"]) || getallheaders()["Authorization"] != Conexion::apikey()) {

        if (in_array($table, Conexion::publicAccess()) == 0) {

            $json = array(
                'status' => 400,
                'results' => 'No esta autorizado para hacer esta peticion'
            );
            
            echo json_encode($json, http_response_code($json["status"]));
        
            return;
            
        }else{

            /* Acceso publico */
            $response = new GetController();
            $response -> getData($table, "*", null, null, null, null);

            return;

        }
        
    }

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        
        include 'services/get.php';

    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        include 'services/post.php';

    }

    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        
        include 'services/put.php';

    }

    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        
        include 'services/delete.php';

    }

}