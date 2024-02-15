<?php 

require_once 'models/put-model.php';

class PutController{

    /* Peticion PUT para editar datos */
    static public function putData($table, $data, $id, $nameId){

        $response = PutModel::putData($table, $data, $id, $nameId);
        
        $return = new PutController();
        $return -> fncResponse($response);

    }

    /* Peticion PUT para cambiar la contraseña de un usuario */
    static public function putPassword($table, $data, $id, $suffix){

        /* Validar que el usuario exista en BD */
        $response = GetModel::getDataFilter($table,"*","email_empleado", $data['email_empleado'], null, null, null, null);

        if (!empty($response)) {

            $old_password = crypt($data['password_'.$suffix], '$2a$07$borderbytesestadiasimmtjr$');

            if ($old_password == $response[0]->{"password_".$suffix}) {

                $new_password = crypt($data['new_password'], '$2a$07$borderbytesestadiasimmtjr$');

                $nameId = 'id_empleado';

                $data = array(
                    'password_'.$suffix => $new_password
                );

                $response = PutModel::putData($table, $data, $id, $nameId);
        
                $return = new PutController();
                $return -> fncResponse($response);

            }else {
                $json = array(
                    'status' => 404,
                    'results' => 'La contraseña no coincide'
                );

                Conexion::apiRequests($json);
                
                echo json_encode($json, http_response_code($json["status"]));
                
                return;
            }

            

        }else {

            $json = array(
                'status' => 400,
                'results' => "ERROR: Cuenta inexistente"
            );

            Conexion::apiRequests($json);

            echo json_encode($json, http_response_code($json["status"]));

            return;

        }
        

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

            Conexion::apiRequests($json);
            
            echo json_encode($json, http_response_code($json["status"]));
            
            return;

        }else {

            $json = array(
                'status' => 404,
                'results' => 'Not Found',
                'method' => 'put'
            );

            Conexion::apiRequests($json);

            echo json_encode($json, http_response_code($json["status"]));
            
            return;

        }

    }

}