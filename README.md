<h2 align="center">Telegram Notify</h2>
<h4 align="center">hecho con <a href="https://laravel.com" target="_blank">Laravel</a></h4>

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
- composer install (antes de este paso borre el composer.lock).
- php artisan migrate.
- php artisan db:seed --class=InitialDataSeeder. El usuario por defecto es admintelnotify@example.com con passwd admin*2023
- .

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
