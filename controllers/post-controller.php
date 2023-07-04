<?php 

require_once 'models/get-model.php';
require_once 'models/post-model.php';
require_once 'models/put-model.php';
require_once 'models/conexion.php';

require_once "vendor/autoload.php";
use Firebase\JWT\JWT;

class PostController{

    /* Peticion POST para crear datos */
    static public function postData($table, $data){

        $response = PostModel::postData($table, $data);
        
        $return = new PostController();
        $return -> fncResponse($response, null,null);

    }

    /* Peticion POST para registrar usuario */
    static public function postRegister($table, $data, $suffix){

        if (isset($data['password_'.$suffix]) && isset($data['password_'.$suffix]) != null) {
            
            $crypt = crypt($data['password_'.$suffix], '$2a$07$borderbytesestadiasimmtjr$');

            $data['password_'.$suffix] = $crypt;
            
            $response = PostModel::postData($table, $data);
            
            $return = new PostController();
            $return -> fncResponse($response, null,$suffix);

        }else {

            /* Registro de usuarios desde app externas */
            $response = PostModel::postData($table, $data);
            
            if (isset($response["coment"]) && $response["coment"] == "El proceso se ejecuto con exito") {

                /* Validar que el usuario exista en BD */
                $response = GetModel::getDataFilter($table, "*", "email_".$suffix, $data['email_'.$suffix], null, null, null, null);

                if (!empty($response)) {

                    $token = Conexion::jwt($response[0]->{"id_".$suffix},$response[0]->{"email_".$suffix});

                    $jwt = JWT::encode($token, "bcv904ej439rg879435nwefi0");

                    /* Actualizar bd con token de usuario */
                    $data = array(

                        "token_".$suffix => $jwt,
                        "token_exp_".$suffix => $token["exp"]

                    );

                    $update = PutModel::putData($table,$data,$response[0]->{"id_".$suffix},"id_".$suffix);

                    if (isset($update["coment"]) && $update["coment"] == "El proceso se ejecuto con exito") {
                        
                        $response[0]->{"token_".$suffix} = $jwt;
                        $response[0]->{"token_exp_".$suffix} = $token["exp"];

                        $return = new PostController();
                        $return -> fncResponse($response, null,$suffix);

                    }

                }

            }

        }

    }

    /* Peticion POST para login de usuario */
    static public function postLogin($table, $data, $suffix){

        /* Validar que el usuario exista en BD */
        $response = GetModel::getDataFilter($table, "*", "email_".$suffix, $data['email_'.$suffix], null, null, null, null);

        if (!empty($response)) {

            if ($response[0]->{"password_".$suffix} != null) {

                /* Encriptar la contraseña */
                $crypt = crypt($data['password_'.$suffix], '$2a$07$borderbytesestadiasimmtjr$');
                if($response[0]->{"password_".$suffix} == $crypt){

                    $token = Conexion::jwt($response[0]->{"id_".$suffix},$response[0]->{"email_".$suffix});

                    $jwt = JWT::encode($token, "bcv904ej439rg879435nwefi0");

                    /* Actualizar bd con token de usuario */
                    $data = array(

                        "token_".$suffix => $jwt,
                        "token_exp_".$suffix => $token["exp"]

                    );

                    $update = PutModel::putData($table,$data,$response[0]->{"id_".$suffix},"id_".$suffix);

                    if (isset($update["coment"]) && $update["coment"] == "El proceso se ejecuto con exito") {
                        
                        $response[0]->{"token_".$suffix} = $jwt;
                        $response[0]->{"token_exp_".$suffix} = $token["exp"];

                        $return = new PostController();
                        $return -> fncResponse($response, null,$suffix);

                    }

                }else{

                    $response = null;
                    $return = new PostController();
                    $return -> fncResponse($response, "Contraseña incorrecta",$suffix);

                }

            }else {

                /* Actualizar el token para usuarios logueados desde app externas */
                $token = Conexion::jwt($response[0]->{"id_".$suffix},$response[0]->{"email_".$suffix});

                $jwt = JWT::encode($token, "bcv904ej439rg879435nwefi0");

                $data = array(

                    "token_".$suffix => $jwt,
                    "token_exp_".$suffix => $token["exp"]

                );

                $update = PutModel::putData($table,$data,$response[0]->{"id_".$suffix},"id_".$suffix);

                if (isset($update["coment"]) && $update["coment"] == "El proceso se ejecuto con exito") {
                    
                    $response[0]->{"token_".$suffix} = $jwt;
                    $response[0]->{"token_exp_".$suffix} = $token["exp"];

                    $return = new PostController();
                    $return -> fncResponse($response, null,$suffix);

                }

            }

        }else{

            $response = null;
            $return = new PostController();
            $return -> fncResponse($response, "Email incorrecto",$suffix);

        }

    }

    /* Respuesta del controlador */
    public function fncResponse($response, $error,$suffix){

        if (!empty($response)) {

            /* Quitar la contraseña de la respuesta */
            if (isset($response[0]->{"password_".$suffix})) {
                
                unset($response[0]->{"password_".$suffix});

            }

            $json = array(
                'status' => 200,
                'results' => $response
            );
            
            echo json_encode($json, http_response_code($json["status"]));
            
            return;

        }else {

            if ($error != null) {

                $json = array(
                    'status' => 404,
                    'results' => $error
                );

            }else{

                $json = array(
                    'status' => 404,
                    'results' => 'Not Found',
                    'method' => 'POST'
                );
    
            }

            echo json_encode($json, http_response_code($json["status"]));
            
            return;

        }

    }

}