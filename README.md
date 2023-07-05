# Documentacion de API RESTFUL
## Configuración inicial - Credenciales de base de datos
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


## Configuración inicial - APIKEY
En la ruta **models/conexion.php** dirigirse al metodo **apiKey()**

Allí agregaras la llave segura de la API (Una contraseña creada por el administrador).

Esta llave solo la conocerá el administrador de la API, y es la clave que se usará como autorización en las cabeceras de cada solicitud HTTP.
```php
static public function apikey(){

  return "bz8VVmSR5Pvb589EhAR2YUH25e3VB7";

}
```

## Configuración inicial - Acceso público
En la ruta **models/conexion.php** dirigirse al metodo **publicAccess()**

Allí agreraras dentro de la matriz las tablas (separadas por coma) que serán de dominio público, es decir, la información a la que podran acceder de forma libre los usuarios de la API.
```php
static public function publicAccess(){

    $tables = ["platillos"];

    return $tables;

}
```

## Configuración inicial - Base de datos
Las tablas deben estar escritas en plural y el nombre de las columnas deben terminar con el nombre de la tabla en singular (_sufijo).

Tabla: **categorias**

Columnas: 
  - id_categoria
  - nombre_categoria
  - imagen_categoria

La primera columna debe ser el ID (auto incrementable).

Para relacionar tablas, el número del ID de la tabla relacionada debe estar en una columna de la tabla principal, y dicha columna debe tener el siguiente orden de palabras: 
id_(tabla relacionada en singular)_(tabla principal en singular).

- Tabla principal: *productos*
- Tabla relacionada: **categorias** | Columna: id_**categoria**_*producto*

