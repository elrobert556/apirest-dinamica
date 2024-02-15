<?php 

require_once 'models/delete-model.php';

class DeleteController{

    /* Peticion DELETE para eliminar datos */
    static public function deleteData($table, $id, $nameId){

        $response = DeleteModel::deleteData($table, $id, $nameId);
        
        $return = new DeleteController();
        $return -> fncResponse($response);

    }

    /* Respuesta del controlador */
    public function fncResponse($response){

        $log = new Conexion();

        if (!empty($response)) {

            $json = array(
                'status' => 200,
                'results' => $response
            );

            $log -> apiRequests($json);
            
            echo json_encode($json, http_response_code($json["status"]));
            
            return;

        }else {

            $json = array(
                'status' => 404,
                'results' => 'Not Found',
                'method' => 'delete'
            );

            $log -> apiRequests($json);

            echo json_encode($json, http_response_code($json["status"]));
            
            return;

        }

    }

}