<h2 align="center">Telegram Notify</h2>
<h6 align="center">Hecho con <a href="https://laravel.com" target="_blank">Laravel</a></h6>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Instalación del proyecto


Cree su entorno de producción a partir de una pila LAMP. Luego de configurado el entorno de producción copie la carpeta del proyecto en su directorio de Virtual Hosts y añada a la ruta de su configuración del VirtualHost de Apache2 la ruta a la carpeta public del proyecto, tenga instalado en su servidor composer y siga los siguientes pasos:
- Tener instalado y configurado mod_rewrite de Apache2, poner las politicas correspondientes en su configuración de VirtualHost.
- Instalar los siguientes extenciones de PHP:
  * PHP >= 8.1
  * Ctype PHP Extension
  * cURL PHP Extension
  * DOM PHP Extension
  * Fileinfo PHP Extension
  * Filter PHP Extension
  * Hash PHP Extension
  * Mbstring PHP Extension
  * OpenSSL PHP Extension
  * PCRE PHP Extension
  * PDO PHP Extension
  * Session PHP Extension
  * Tokenizer PHP Extension
  * XML PHP Extension
  * INTL PHP Extension
  * ZIP PHP Extension
  * GD PHP Extension
  * JSON PHP Extension      
- Colóquese en la raíz del proyecto.
- Ejecutar composer install --optimize-autoloader --no-dev (antes de este paso borre el composer.lock).
- Realizar las configuraciones pertinentes en el archivo .env, de no existir copiar del archivo de ejemplo el contenido (.env.example). Asegurarse de que las variables APP_ENV=production y APP_DEBUG=false
- Si no tiene una llave en APP_KEY ejecute: php artisan key:generate
- Ejecutar php artisan migrate.
- Ejecutar php artisan db:seed --class=InitialDataSeeder. El usuario por defecto es admintelnotify@example.com con passwd admin*2023
- En este momento puede verificar que la aplicación se ejecuta.
- Configure cron en su sistema: * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
- Instale supervisor para el control de la ejecucion de tareas(Queue): sudo apt-get install supervisor
- Cree un fichero de configuración para su proyecto en /etc/supervisor/conf.d/config-name.conf
- Edite el fichero con la siguiente información:  
[program:name-ofworker]  
process_name=%(program_name)s_%(process_num)02d  
command=php /var/www/app.com/artisan queue:work sqs --sleep=3 --tries=3 --max-time=3600  
autostart=true  
autorestart=true  
stopasgroup=true  
killasgroup=true  
user=forge  
numprocs=1  
redirect_stderr=true  
stdout_logfile=/home/forge/app.com/worker.log  
stopwaitsecs=3600  
  * numprocs son la cantidad de queues similtaneas ejecutandose y stopwaitsecs debe tener un valor en segundos suficiente para la ejecución de la queue mas larga  

-  Cargue la configuración de supervisor e inicie el proceso con los siguientes comandos:
  * sudo supervisorctl reread
  * sudo supervisorctl update
  * sudo supervisorctl start name-ofworker:*
- Genere la documentación de la API: php artisan scribe:generate (Esto debe hacerlo cada vez que haya cambios en el código de la API)
- Deje optimizado el sistema para producción con siguiente comando: php artisan optimize (Esto debe hacerlo cada vez que haya cambios en el código)

Creo que hasta aquí esta bien ☕  

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
