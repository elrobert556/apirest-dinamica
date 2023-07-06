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

Para realizar autenticaciones con la API RESTFUL, es indispensable que la tabla de autenticación tenga siempre estas 4 columnas:
  - email_sufijo (text)
  - password_sufijo (text)
  - token_sufijo (text)
  - token_exp_sufijo (text)

## Configuración inicial - Autorización
Todas las solicitudes a la API deben llevar en la cabecera (HEADERS) la propiedad **Authorization** con la respectiva **APIKEY** creada por el administrador.

## Solcitudes de tipo GET
### La seleción básica
Consiste simplemente en colocar el nombre de la tabla luego del **ENDPOINT**.
> Si el nombre de la tabla está mal escrito o no existe en la base de datos recibirá como respuesta un status 404.

Ejemplo en servidor: http://apirest.com/empleados

Ejemplo en servidor local (XAMPP): http://localhost/API/ordenes

### Seleccionar un dato específico
Consiste en agregar el parámetro **select** a la **url** y colocal el nombre exacto (sin espacios a los lados) de una columna que exista en la tabla. Puede agregar las columnas que desee separándolas por coma.
> Esto ayuda a optimizar el tiempo de entrega de la información.

Ejemplo en servidor: http://apirest.com/platillos?select=id_platillo,nombre_platillo

Ejemplo en servidor local (XAMPP): http://localhost/API/platillos?select=id_platillo,nombre_platillo

### Filtrar una seleccion con un solo valor
Consiste en agregar los parámetros **linkTo** y **equalTo** a la **url** . En el primer parámetro escribir el nombre de columna donde se desea encontrar la coincidencia, y en el segundo parámetro colocar el valor exacto de lo que se desea filtrar.
> Evita filtrar contenido de textos que contengan alguna coma.

Ejemplo en servidor: http://apirest.com/platillos?select=id_platillo,nombre_platillo,descripcion_platillo&linkTo=nombre_platillo&equalTo=Coconut Cream Pie

Ejemplo en servidor local (XAMPP): http://localhost/API/platillos?select=id_platillo,nombre_platillo,descripcion_platillo&linkTo=nombre_platillo&equalTo=Coconut Cream Pie

### Filtrar una seleccion con varios valores
Consiste en agregar más columnas separadas por comas al parámetro **linkTo** y en agregar más valores separados por el catacter "**|**" en el parámetro **equalTo**.
> Agregar un solo valor por cada columna adicional.

Ejemplo en servidor: http://apirest.com/platillos?select=id_platillo,nombre_platillo,descripcion_platillo&linkTo=nombre_platillo,id_platillo&equalTo=Coconut Cream Pie|1

Ejemplo en servidor local (XAMPP): http://localhost/API/platillos?select=id_platillo,nombre_platillo,descripcion_platillo&linkTo=nombre_platillo,id_platillo&equalTo=Coconut Cream Pie|1

### Ordenar datos
Consiste consiste en agregar los parámetros **orderBy** y **orderMode** e indicar en **orderBy** la columna que ordenará los datos y usar las palabras reservadas **ASC** o **DESC** en el parámetro **orderMode** para indicar el tipo de orden (Ascendente o Descendente).
> El orden se puede aplicar a números, alfabeto o fechas.

Ejemplo en servidor: http://apirest.com/platillos?orderBy=id_platillo&orderMode=DESC

Ejemplo en servidor local (XAMPP): http://localhost/API/platillos?orderBy=id_platillo&orderMode=DESC

### Limitar datos
Consiste en agregar los parámetros **startAt** y **endAt** e indicar en **startAt** la posición inicial desde donde se desea limitar los datos (la primera posición es el 0) y en el parámetro **endAt** indicar la cantidad de registros que se desean seleccionar.
> Se puede combinar el orden y los límites, pero siempre el orden será la prioridad para la API.

Ejemplo en servidor: http://apirest.com/platillos?startAt=0&endAt=5

Ejemplo en servidor local (XAMPP): http://localhost/API/platillos?startAt=0&endAt=5

### Relaciones entre tablas
Consiste en traer información entre varias tablas que estén relacionadas por su ID sin filtrar información.

La estructura de la URL cambia: 
  - Debemos agregar la palabra **relations** luego del ENDPOINT.
  - Agregar el parámetro **rel**: Allí indicar en primer lugar el nombre de la tabla principal y luego separado por comas las tablas relacionadas.
  - Luego agregar el parámetro **type**: Allí agregar los sufijos de las tablas puestas en el parámetro **rel**
> Al no filtrar datos traemos el total de información de todas las tablas en relación de acuerdo a los datos seleccionados.

Ejemplo en servidor: http://apirest.com/relations?rel=subcategorias,categorias&type=subcategoria,categoria

Ejemplo en servidor local (XAMPP): http://localhost/API/relations?rel=subcategorias,categorias&type=subcategoria,categoria

### Busqueda con filtros
Consiste en realizar búsquedas en la base de datos en una sola tabla usando palabras claves y filtrando datos exactos. Usamos el parámetro **linkTo** para el nombre de columnas y agregamos el parámetro **search** para la búsqueda de la palabra clave y datos exactos.
> El primer valor de **linkTo** debe ser siempre la columna en donde se realizará la búsqueda, de igual forma, el primer valor de **search** debe ser la palabra clave. Luego aplicamos los filtros, comas para os vvalores de **linkTo** y "**|**" para los valores de **search**.

Ejemplo en servidor: http://apirest.com/platillos?select=nombre_platillo&linkTo=nombre_platillo,id_subcategoria_platillo&search=vainilla|2

Ejemplo en servidor local (XAMPP): http://localhost/API/platillos?select=nombre_platillo&linkTo=nombre_platillo,id_subcategoria_platillo&search=vainilla|2

### Rangos con filtros
Consiste en seleccionar un rango de datos entre números o fechas en una sola tabla agregando los parámetros **between1** para el rango inicial y **between2** para el rango final, filtramos la información con el parámetro **filterTo** indicando el nombre de columna y el parámetro **inTo** para indicar el valor del filtro.

Ejemplo en servidor: http://apirest.com/platillos?linkTo=precio_platillo&between1=100&between2=150&orderBy=precio_platillo&orderMode=ASC&select=id_platillo,nombre_platillo,precio_platillo,id_categoria_platillo&filterTo=id_categoria_platillo&inTo=4

Ejemplo en servidor local (XAMPP): http://localhost/API/platillos?linkTo=precio_platillo&between1=100&between2=150&orderBy=precio_platillo&orderMode=ASC&select=id_platillo,nombre_platillo,precio_platillo,id_categoria_platillo&filterTo=id_categoria_platillo&inTo=4
