# Micrositios placetogrow

presentado por [Felipe Mogollon](https://github.com/felipemogollon1)

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
4. Configuración de Credenciales de Correo Electrónico: 

    Para habilitar la funcionalidad de envío de correos electrónicos, es necesario configurar las credenciales en el archivo `.env`. A continuación, se muestra un ejemplo de configuración:

    ```env
    MAIL_MAILER=log
    MAIL_HOST=127.0.0.1
    MAIL_PORT=2525
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS="noreply@microsites.com"
    MAIL_FROM_NAME="${APP_NAME}"
    ```
5. Configuración de la URL de la Aplicación: 
    
    Asegúrate de que la URL de tu aplicación esté configurada correctamente en el archivo `.env`. La configuración debe reflejar la dirección y el puerto en el que está ejecutándose tu aplicación. Por ejemplo:
    ```env
    APP_URL=http://localhost:8000
    ```   
6. Configuración de Notificaciones:

    Las siguientes variables en el archivo `.env` controlan el comportamiento de las notificaciones relacionadas con la expiración de facturas y suscripciones en frecuencia de dias de antelación:

    ```env
    NOTIFICATION_DAYS_BEFORE_INVOICE_EXPIRATION=
    NOTIFICATION_DAYS_BEFORE_SUBSCRIPTION_EXPIRATION=
    NOTIFICATION_DAYS_BEFORE_SUBSCRIPTION_COLLECTION=
    ``` 
    
7. El usuario super administrador por defecto es:
    ```env
    Email: sa@microsites.com
    Contraseña: password
    ```
8. El usuario administrador por defecto es:
    ```env
    Email: admin@microsites.com
    Contraseña: password
    ```

## Ejecución de Jobs y Schedules
Para asegurar el correcto funcionamiento de la aplicación, es importante que los jobs y schedules de Laravel se ejecuten de manera regular. Esto puede hacerse de la siguiente manera:

1. Ejecutar Schedules de Laravel:
    ```bash
    php artisan schedule:work
    ```

2. Ejecutar los Jobs :
    ```env
    php artisan queue:work
    ```
    
3. Para verificar si los jobs se están ejecutando correctamente, puedes usar el siguiente comando:
    ```env
    php artisan queue:failed
    ```
## Modelo Entidad Relación y documentación
[Documentación](https://www.canva.com/design/DAGUJn961W0/WOd7A5bI82FrQ8HP057Qig/view?embed) 

Para acceder a la documentación completa, por favor ingresa al enlace en el título. Aquí encontrarás explicaciones detalladas sobre el funcionamiento del sistema, la estructura de los datos, y las relaciones entre las diferentes partes del proyecto Microsites.



[Modelo Entidad Relación](https://lucid.app/lucidchart/10fb95bf-0255-4128-a140-e57546d8fb4d/edit?invitationId=inv_ac912b73-c96f-4386-acb9-28abf2053deb) : 

Para acceder al Modelo Entidad-Relación, por favor ingresa al enlace en el título. El proyecto Microsites utiliza un Modelo Entidad-Relación (MER) para representar cómo están organizados los datos en la base de datos y cómo se conectan las entidades. Esto garantiza una estructura clara y facilita la gestión de la información.
