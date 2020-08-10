# API mini twitter

## Pruebas unitarias
Los archivos son almacenados en la carpeta **test/** en donde se maneja la misma estructura de carpetas que **src**.

Al incluir una prueba unitaria incluir tu **archivo.php** ha **test/bootstrap.php**. para ejecutar el script de prueba escribe el siguiente comando ``./vendor/bin/phpunit --configuration test/phpunit.xml test/`` en la carpeta raiz del proyecto

## Tabla de rutas
### Autenticación de usuario

| Método | Punto de acceso | Descripción |
| --- | --- | --- |
| POST | ``/auth/login`` | Autorizar inicios de sesión |
| POST | ``/auth/signup`` | Registrar un usuario en la aplicación |

### Usuarios
