# Documentacion de API RESTFUL
## Configuracion inicial - Credenciales de base de datos
En la ruta **models/conexion.php** dirigirse al metodo **infoDatabase()**

Allí se configura la conexión a la base de datos, agregando el nombre de la base de datos, el usuario y la contraseña
```php
static public function infoDatabase(){

  $infoDB = array(
      "database" => "border_bytes",
      "user" => "root",
      "pwd" => ""
  );
  
  return $infoDB;

}
```


## Configuracion inicial - APIKEY
En la ruta **models/conexion.php** dirigirse al metodo **apiKey()**

Allí agregaras la llave segura de la API (Una contraseña creada por el administrador).

Esta llave solo la conocerá el administrador de la API, y es la clave que se usará como autorización en las cabeceras de cada solicitud HTTP.
```php
static public function apikey(){

  return "bz8VVmSR5Pvb589EhAR2YUH25e3VB7";

}
```

## Configuracion inicial - Acceso público
En la ruta **models/conexion.php** dirigirse al metodo **publicAccess()**

Allí agreraras dentro de la matriz las tablas (separadas por coma) que serán de dominio público, es decir, la información a la que podran acceder de forma libre los usuarios de la API.
```php
static public function publicAccess(){

    $tables = ["platillos"];

    return $tables;

}
```
