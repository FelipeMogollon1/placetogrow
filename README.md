# Micrositios placetogrow



## Tabla de contenido

- [Acerca del proyecto](#Acerca-del-proyecto)
- [Construido con](#construido-con)
- [Prerequisitos](#prerequisitos)
- [Instalación](#instalación)
- [Modelo Entidad Relación](#modelo-entidad-relación)

### Acerca del proyecto

Microsites es una plataforma basada en Laravel diseñada para empoderar a los administradores con la capacidad de crear y administrar microsites para diversos tipos de pago, incluyendo facturas, suscripciones y donaciones. La plataforma prioriza una experiencia de usuario segura y directa, asegurando un procesamiento de pagos y una gestión de datos sin problemas.

### Construido con

- [Laravel](https://laravel.com): Un robusto framework PHP para el desarrollo de aplicaciones web.
- [Inertia.js](https://inertiajs.com): Un framework JavaScript para la construcción de aplicaciones modernas de una sola página.
- [Vue.js](https://vuejs.org): Un framework JavaScript progresivo para la construcción de interfaces de usuario.
- [Tailwind CSS](https://tailwindcss.com): Un framework CSS de primera utilidad para el prototipado rápido y el estilo.

### Prerequisitos
Para ejecutar este proyecto de forma eficaz, necesitarás los siguientes prerrequisitos:

- [MySQL](https://www.mysql.com/) 
- [PHP](https://www.php.net/) (versión : 8.2 o superior)
- [Laravel](https://laravel.com/docs/11.x) (versión : 11.0 o superior)
- [Node.js](https://nodejs.org/) (versión LTS recomendada)
- [Composer](https://getcomposer.org/)
- Entorno de desarrollo local configurado (Apache, Nginx, etc.)

### Habilitar Extensiones en PHP
Si estás usando PHP en tu entorno local, asegúrate de que las extensiones `gd` y `zip` estén habilitadas en tu servidor.
Puedes hacerlo editando tu archivo `php.ini`:

1. Abre tu archivo php.ini (puedes ubicarlo ejecutando `php --ini` en tu terminal).
2. Descomenta (quita el `;` al inicio de la línea) las siguientes líneas:
    ```bash
    extension=gd
    extension=zip
    ```
3.   Guarda los cambios y reinicia tu servidor PHP.

En distribuciones como Ubuntu o Debian, puedes instalar estas extensiones con:
```bash
sudo apt-get install php-gd php-zip
```

### Instalación

#### Clonar el repositorio

1. **Configurar el entorno:**
   Asegúrate de tener las siguientes herramientas instaladas en tu máquina:
    - PHP 8.2
    - Node.js
    - git 
    - npm
    - Composer
    - MySQL
   

2. **Clonar el Repositorio:**
    ```bash
    git clone https://github.com/FelipeMogollon1/placetogrow.git
    cd placetogrow
    ```
3. **Copiar el archivo `.env` de ejemplo**
   ```bash
   cp .env.example .env
   ```

4. **Instalar Dependencias de PHP:**
   Antes de ejecutar composer install, asegúrate de que las extensiones `gd` y `zip` están habilitadas.
      ```bash
      composer install
      ```

5. **Instalar Dependencias de Node.js:**
   ```bash
   npm install
   ```

6. **Generar la Clave de Aplicación:**
   ```bash
   php artisan key:generate
   ```

7. **Configurar la Base de Datos**

   - Crear una Base de Datos en MySQL:  Crea una base de datos en MySQL para el proyecto y agregar el nombre de la base de datos en `DB_DATABASE`.
   - Actualizar el Archivo `.env`:  Configura los detalles de tu base de datos en el archivo `.env` Asegúrate de que `DB_CONNECTION` esté configurado como mysql.
    ```
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=db_microsites
        DB_USERNAME=root
        DB_PASSWORD=       
    ```
8. **Ejecutar Migraciones y Seeders:**
   ```bash
   php artisan migrate --seed
   ```

9. **Vincular el Almacenamiento:**
   ```bash
   php artisan storage:link
   ```

10. **Inicia el servidor de desarrollo de Laravel:**
    ```bash
    php artisan serve
    ```

11. **Compilar los Activos:**
   ```bash
   npm run dev
   ```


## Configuración

1. Configurar la base de datos en phpMyAdmin y en el archivo `.env` generado anteriormente.
2. Configurar las credenciales de Mailtrap en el archivo `.env` para probar la funcionalidad de verificación de email del usuario.
3. Configurar los datos de la pasarela de pagos en el archivo `.env`. Las variables necesarias para la pasarela de pagos PlacetoPay son:
  
    ```env
       PLACETOPAY_LOGIN=
       PLACETOPAY_TRANKEY=
       PLACETOPAY_BASE_URL=
       PLACETOPAY_TIME_OUT=
    ```

4. El usuario super administrador por defecto es:
    ```env
    Email: sa@microsites.com
    Contraseña: password
    ```
5. El usuario administrador por defecto es:
    ```env
    Email: admin@microsites.com
    Contraseña: password
    ```


### Modelo Entidad Relación
#### [MER](https://lucid.app/lucidchart/10fb95bf-0255-4128-a140-e57546d8fb4d/edit?invitationId=inv_ac912b73-c96f-4386-acb9-28abf2053deb) : El proyecto Microsites emplea un modelo Entidad-Relación (MER) para representar la estructura de datos y las relaciones entre las entidades del sistema.

