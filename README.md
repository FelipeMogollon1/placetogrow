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

- [MySQL](https://www.mysql.com/) o cualquier otro sistema de gestión de bases de datos compatible para almacenar los datos de la aplicación.
- [PHP](https://www.php.net/) (versión : 8.2 o superior)
- [Laravel](https://laravel.com/docs/11.x) (versión : 11.0 o superior)
- [Node.js](https://nodejs.org/) (versión LTS recomendada)
- [Composer](https://getcomposer.org/)
- Entorno de desarrollo local configurado (Apache, Nginx, etc.)

### Instalación

#### Clonar el repositorio

1. **Configurar el entorno:**
    - Asegúrese de tener PHP 8.2, Node.js, git, npm y Composer instalados en su máquina.
    - Edite la conexión de la base de datos en el archivo .env.
   
2. **Clone the Repository:**
    ```bash
    git clone https://github.com/FelipeMogollon1/placetogrow.git
    cd placetogrow
    ```
3. **Copy .env File:**
   ```bash
   cp .env.example .env
   ```

4. **Install PHP Dependencies:**
   ```bash
   composer install
   ```

5. **Install Node.js Dependencies:**
   ```bash
   npm install
   ```

6. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

7. **Run Migrations and Seeders:**
   ```bash
   php artisan migrate --seed
   ```

8. **Link Storage:**
   ```bash
   php artisan storage:link
   ```

9. **Compile Assets:**
   ```bash
   npm run dev
   ```

10. **Start the Application:**
    ```bash
    php artisan serve
    ```
## Configuración

1. Configurar la base de datos en phpMyAdmin y en el archivo .env generado anteriormente.

2. Configurar las credenciales de Mailtrap en el archivo .env para probar la funcionalidad de verificación de email del usuario.

3. Configurar los datos de la pasarela de pagos en el archivo .env. Las variables necesarias para la pasarela de pagos PlacetoPay son:
   ```env
   PLACETOPAY_LOGIN=
   PLACETOPAY_TRANKEY=
   PLACETOPAY_BASE_URL=

4. El usuario super administrator por defecto es:

   Email: sa@microsites.com
   Contraseña: password


### Modelo Entidad Relación
#### [MER](https://lucid.app/lucidchart/10fb95bf-0255-4128-a140-e57546d8fb4d/edit?invitationId=inv_ac912b73-c96f-4386-acb9-28abf2053deb) : El proyecto Microsites emplea un modelo Entidad-Relación (MER) para representar la estructura de datos y las relaciones entre las entidades del sistema.

