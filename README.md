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

### Tweets

| Método | Punto de acceso | Autenticación | Descripción |
| --- | --- | --- | --- |
| GET | [``/tweet/all``](#tweetall) | SI | lista de tweets |

## Detalle de las rutas
### /auth/login
| Parametro | Requerido | Descripción | Restricción |
| --- | --- | --- | --- |
| key | SI | nombre de usuario o correo electrónico | |
| password | SI | contraseña | 64 caracteres |

**Petición HTTP**
```curl
curl --location --request POST '127.0.0.1/api-mini-twitter/auth/login' \
--form 'key=carlos.menjivar@gmail.com' \
--form 'password=12345'
```

**Estado 200 *CREATED***
```javascript
{
    "message": "inicio de sesion correcto",
    "user_name": "carlos.menjivar",
    "email": "carlos.menjivar@gmail.com",
    "jwt": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBUEktTUlOSS1UV0lUVEVSIiwiaWF0IjoxNTk3NTg1MDQ4LCJleHAiOjE2MDYyMjUwNDgsImRhdGEiOnsidXNlck5hbWUiOiJjYXJsb3MubWVuaml2YXIiLCJlbWFpbCI6ImNhcmxvcy5tZW5qaXZhckBnbWFpbC5jb20ifX0.rU_Nr3W7yNO6Y_jmC7ti5CV_F9GoS1MIWUheUfqmiUM"
}
```

**Estado 401 *UNAUTHORIZED***
```javascript
{ "message": "credenciales incorrectas" }
```

**Estado 404 *NOT FOUND***
```javascript
{ "message": "el usuario no existe" }
```

### /auth/signup
| Parámetro | Requerido | Descripción | Restricción |
| --- | --- | --- | --- |
| user_name | SI | nombre de usuario | 35 caracteres |
| email | SI | correo electrónico valido |  256 caracteres |
| password | SI | contraseña | 64 caracteres |

**Petición HTTP**
```curl
curl --location --request POST '127.0.0.1/api-mini-twitter/auth/signup' \
--form 'user_name=mario.fuentes' \
--form 'email=mario.fuentes@gmail.com' \
--form 'password=12345'
```

**Estado 201 *CREATED***
```javascript
{
    "id_user": "91",
    "user_name": "mario.fuentes",
    "email": "mario.fuentes@gmail.com",
    "role": "USER",
    "created_date": "2020-08-23 21:25:52",
    "jwt": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJBUEktTUlOSS1UV0lUVEVSIiwiaWF0IjoxNTk4MjM5NTUyLCJleHAiOjE2MDY4Nzk1NTIsImRhdGEiOnsidXNlck5hbWUiOiJtYXJpby5mdWVudGVzc3NzcyIsImVtYWlsIjoibWFyaW8uZnVlbnRlc3Nzc3NAZ21haWwuY29tIn19.CL3bGnoarDyn2fGPWTvTiYqulp6SiYe19hlrsqGrwIQ"
}
```

**Estado 400 *BAD REQUEST***
```javascript
{ "message" : "falta el usuario" }
```
```javascript
{ "message" : "falta el correo" }
```
```javascript
{ "message" : "falta el password" }
```
```javascript
{ "message" : "el formato de correo es incorrecto" }
```

**Estado 403 *FORBIDDEN***
```javascript
{ "message" : "el usuario o correo están en uso" }
```

**Estado 500 *INTERNAL SERVER ERROR***
```javascript
{ "message" : "error al registrar usuario" }
```

### /tweet
| Parámetro | Requerido | Descripción | Restricción |
| --- | --- | --- | --- |
| message | SI | contenido del tweet | 256 caracteres |

**Petición HTTP**
```curl
curl --location --request POST 'http://127.0.0.1/api-mini-twitter/tweet' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJBUEktTUlOSS1UV0lUVEVSIiwiaWF0IjoxNjAxNzQyNjc2LCJleHAiOjE2MTAzODI2NzYsImRhdGEiOnsiaWRVc2VyIjoiNzgiLCJ1c2VyTmFtZSI6ImNhcmxvcy5tZW5qaXZhciIsImVtYWlsIjoiY2FybG9zLm1lbmppdmFyQGdtYWlsLmNvbSJ9fQ.LDgCJdRXUMr4mYMS16ihL1TiFELcuoPjovwTNLOk2T94kCR-LdXC4D6TvOHePffbgU-QEymDpNiPDjsiJsyGIg' \
--form 'message=El amor se conmovía en los corazones de las jóvenes hijas de los brahmanes cuando Siddhartha pasaba por las calles de la ciudad, con la frente  luminosa, con los ojos reales, con las estrechas caderas.'
```

**Estado 200 *OK***
```javascript
{ "message": "tweet creado" }
```

**Estado 400 *BAD REQUEST***
```javascript
{ "message": "tu tweet no puede tener mas de 256 caracteres" }
```

ocurre cuando el idUser del token es incorrecto
```javascript
{ "message": "parece que tu usuario no existe" }
```

**Estado 500  *INTERNAL SERVER ERROR***
```javascript
{ "message": "error al registrar usuario"}
```

### /tweet/all

**Petición http**
```curl
curl --location --request GET 'http://127.0.0.1/api-mini-twitter/tweet/all' \
--header 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJBUEktTUlOSS1UV0lUVEVSIiwiaWF0IjoxNjAxNDMwNzc2LCJleHAiOjE2MTAwNzA3NzYsImRhdGEiOnsidXNlck5hbWUiOiJjYXJsb3MubWVuaml2YXIiLCJlbWFpbCI6ImNhcmxvcy5tZW5qaXZhckBnbWFpbC5jb20ifX0.k5oh8ZSHoFnganPSIvXM_mzU6YGzVnk7X3kAbZMQNYfYxX8rJwRLk7WWO9N-kPwN_cPWzlzL66Fr7Dsng8kPPA'
```

**Estado 200 *OK***
```javascript
[
    {
        "id_tweet": "4",
        "created_date": "2020-09-28 20:21:48",
        "message": "La filosofía de Heidegger es una filosofía característicamente filológica o lingüística, en el sentido de que sus filosofemas consisten en considerable proporción en hacer explícito el sentido que encuéntrase implícito en las expresiones.",
        "id_user": "78",
        "user_name": "carlos.menjivar",
        "count_likes": "1"
    }
]
```

**Estado 401 *UNAUTHORIZED***
```javascript
{ "message": "acceso denegado" }
```
