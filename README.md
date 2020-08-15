# API mini twitter

## Configuración de apache

## Pruebas unitarias
Los archivos son almacenados en la carpeta **test/** en donde se maneja la misma estructura de carpetas que **src**.

Al incluir una prueba unitaria incluir tu **archivo.php** ha **test/bootstrap.php**. para ejecutar el script de prueba escribe el siguiente comando ``./vendor/bin/phpunit --configuration test/phpunit.xml test/`` en la carpeta raiz del proyecto

## Tabla de rutas
### Autenticación de usuario

| Método | Punto de acceso | Descripción |
| --- | --- | --- |
| POST | ``/auth/login`` | autorizar inicios de sesión |
| POST | [``/auth/signup``](#authsignup) | registrar un usuario en la aplicación |


## Detalle de las rutas
### /auth/signup
| Parametro | Requerido | Descripción | Restricción |
| --- | --- | --- | --- |
| user_name | SI | nombre de usuario | 35 caracteres |
| email | SI | correo electrónico valido |  256 caracteres |
| password | SI | contraseña | 64 caracteres |

| Codigo HTTP | Mensaje |
| --- | --- |
| 201 | usuario registrado |
| 400 | faltan parámetros |
| 403 | el usuario o correo están en uso |
| 500 | error al registrar usuario |

### Usuarios
