<?php

class Conexion {

    static public function infoDatabase(){

        $infoDB = array(
            "database" => "border_bytes",
            "user" => "root",
            "pwd" => ""
        );

        return $infoDB;

    }

    /* APIKEY */
    static public function apikey(){

        return "bz8VVmSR5Pvb589EhAR2YUH25e3VB7";

    }

    /* Acceso publico */
    static public function publicAccess(){

        $tables = ["platillos"];

        return $tables;

    }
    
    static public function conectar(){

        try {
            
            $link = new PDO(
                "mysql:host=localhost;dbname=".Conexion::infoDatabase()["database"],
                Conexion::infoDatabase()["user"],
                Conexion::infoDatabase()["pwd"]
            );

            $link->exec("set names utf8");
            
        } catch (PDOException $e) {
            
            die("Error: ".$e->getMessage());

        }

        return $link;

    }

    /* Validar existencia de una tabla en la base de datos */
    static public function getColumnsData($table,$columns){

        /* Traer nombre de base de datos */
        $database = Conexion::infoDatabase()["database"];

        /* Traer todas las columnas de una tabla */
        $validate = Conexion::conectar()
        ->query("SELECT COLUMN_NAME AS item from information_schema.columns WHERE table_schema = '$database' AND table_name = '$table'")
        ->fetchAll(PDO::FETCH_OBJ);

        /* Validar si la tabla existe */
        if (empty($validate)) {

            return null;

        }else{

            /* Seleccion de cclumnas globales */
            if ($columns[0] == "*") {
                
                array_shift($columns);

            }

            /* Validar existencia de columnas */
            $sum = 0;

            foreach ($validate as $key => $value) {

                $sum += in_array($value->item, $columns);

            }

            return $sum == count($columns) ? $validate : null;

        }

    }

    /* Generar Token de Autenticacion */
    static public function jwt($id, $email){

        $time = time();

        $token = array(

            "iat" => $time,//Tiempo que inicia el token
            "exp" => $time + (60*60*24),//Tiempo en que expirara el token (1 dia)
            "data" => [

                "id" => $id,
                "email" => $email

            ]

        );

        return $token;

    }

    /* Validar Token de seguridad */
    static public function tokenValidate($token,$table,$suffix){

        $user = GetModel::getDataFilter($table, "token_exp_".$suffix, "token_".$suffix, $token, null, null, null, null);

        if (!empty($user)) {
            
            /* Validar que el token no haya expirado */
            $time = time();

            if ($user[0]->{"token_exp_".$suffix} > $time) {
                
                return "ok";

            }else{

                return "expired";

            }

        }else{

            return "no-auth";
                
        }

    }

}
