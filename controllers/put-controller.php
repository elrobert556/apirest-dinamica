<?php 

require_once 'models/put-model.php';

class PutController{

    /* Peticion PUT para editar datos */
    static public function putData($table, $data, $id, $nameId){

        $response = PutModel::putData($table, $data, $id, $nameId);
        
        $return = new PutController();
        $return -> fncResponse($response);

    }

    /* Peticion PUT para ocultar un registro */
    static public function putStatusData($table, $data, $deleteId, $nameId){

        $response = PutModel::putStatusData($table, $data, $deleteId, $nameId);
        
        $return = new PutController();
        $return -> fncResponse($response);

    }

    /* Respuesta del controlador */
    public function fncResponse($response){

        if (!empty($response)) {

            $json = array(
                'status' => 200,
                'results' => $response
            );
            
            echo json_encode($json, http_response_code($json["status"]));
            
            return;

        }else {

            $json = array(
                'status' => 404,
                'results' => 'Not Found',
                'method' => 'put'
            );

            echo json_encode($json, http_response_code($json["status"]));
            
            return;

        }

    }

}