<p align="center">


<h2 align="center">Microsites</h2>


</p>



<!-- TABLE OF CONTENTS -->
## Tabla de contenido

* [Sobre el proyecto](#sobre-el-proyecto)
* [Construido con](#construido-con)
* [Prerequisitos](#prerequisitos)
* [Instalación](#instalación)





### Construido con
* [Laravel](https://laravel.com)
* [VueJS](https://vuejs.org/)

### Prerequisitos

* [MySQL](https://www.mysql.com/)
* [PHP](https://www.php.net/)
* [phpMyAdmin](https://www.phpmyadmin.net/) (opcional)
* [Node.js](https://nodejs.org/es/)
* [Composer](https://getcomposer.org/)

### Instalación

1. Clonar el repositorio
   bash


2. Instalar dependencias del backend:
   bash
   $ composer install

3. Generar archivo .env para configuración de las variables de entorno:
   bash
   $ cp .env.example .env


>Ahora debemos configurar la base de datos en phpMyAdmin y en las variables de entorno que se encuentran en el archivo .env generado anteriormente. En este archivo también debemos configurar las credenciales de Mailtrap para probar la funcionalidad de verificación de email del usuario y poner los datos de la pasarela de pagos.

4. Generar la llave de la aplicación:
   bash
   $ php artisan key:generate


5. Migraciones y alimentación de la base de datos:
   bash
   $ php artisan migrate --seed

6. Dependencias del frontend y construcción de assets:
   bash
   $ npm install
   $ npm run dev

- Despliegue:
```bash
$ php artisan serve
