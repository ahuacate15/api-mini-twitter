# API mini twitter

## Configuración de apache

## Pruebas unitarias
Los archivos son almacenados en la carpeta **test/** en donde se maneja la misma estructura de carpetas que **src**.

Al incluir una prueba unitaria incluir tu **archivo.php** ha **test/bootstrap.php**. para ejecutar el script de prueba escribe el siguiente comando ``./vendor/bin/phpunit --configuration test/phpunit.xml test/`` en la carpeta raiz del proyecto

## Tabla de rutas
### Autenticación de usuario

| Método | Punto de acceso | Descripción |
| --- | --- | --- |
| POST | [``/auth/login``](#authlogin) | autorizar inicios de sesión |
| POST | [``/auth/signup``](#authsignup) | registrar un usuario en la aplicación |


## Detalle de las rutas
### /auth/login
| Parametro | Requerido | Descripción | Restricción |
| --- | --- | --- | --- |
| key | SI | nombre de usuario o correo electrónico | |
| password | SI | contraseña | 64 caracteres |

Petición HTTP
```curl
curl --location --request POST '127.0.0.1/api-mini-twitter/auth/login' \
--form 'key=carlos.menjivar@gmail.com' \
--form 'password=12345'
```

Código de estado http **200**
```javascript
{
    "message": "inicio de sesion correcto",
    "userName": "carlos.menjivar",
    "email": "carlos.menjivar@gmail.com",
    "jwt": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBUEktTUlOSS1UV0lUVEVSIiwiaWF0IjoxNTk3NTg1MDQ4LCJleHAiOjE2MDYyMjUwNDgsImRhdGEiOnsidXNlck5hbWUiOiJjYXJsb3MubWVuaml2YXIiLCJlbWFpbCI6ImNhcmxvcy5tZW5qaXZhckBnbWFpbC5jb20ifX0.rU_Nr3W7yNO6Y_jmC7ti5CV_F9GoS1MIWUheUfqmiUM"
}
```

Codigo de estado http **401**
```javascript
{
    "message": "Credenciales incorrectas"
}
```

Codigo de estado http **404**
```javascript
{
    "message": "El usuario no existe"
}
```

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
