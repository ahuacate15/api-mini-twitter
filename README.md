<img src=".github/logo_mini_twitter.jpeg" height="110" />

## Acerca del proyecto
API rest utilizada en la aplicación movil de [mini twitter](https://github.com/ahuacate15/mini-twitter), basada en el curso de android del maestro **@miguelcamposdev**, desarrollada para fines didacticos.

## Características técnicas
* Desarrollada en php
* Base de datos escrita en MySQL
* Enturamiento con fast route
* Implementación de JWT
* Implementación de pruebas unitarias

## Instalación
Descarga las dependencias las proyecto ejecutando el comando ``composer install`` desde la carpeta raiz del proyecto.

Ejecuta el script ubicado en el archivo **doc/db.sql** para crear la base de datos de la API.

Debes incluir las credenciales de tu servidor MySQL en el archivo **src/includes/connection.php**
```php
class Connection {

    private const HOST = "YOUR_IP";
    private const DB = "mini_twitter";
    private const USER = "username";
    private const PASSWORD = "password";
    ...
}
```

Asegurate de brindar los permisos necesarios a la carpeta **uploads** para poder subir imagenes desde la aplicación móvil, puedes tomar como referencias los siguientes comandos:
```bash
chown -R www-data:www-data uploads/
chmod +w uploads/
```

Asegurate que el proyecto se encuentra en orden, corriendo las pruebas unitarias con el comando ``./vendor/bin/phpunit --configuration test/phpunit.xml test/``
