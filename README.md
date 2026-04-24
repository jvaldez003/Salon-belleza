# Sistema de Gestión de Citas y Servicios 

Este sistema esta diseñado para optimizar la administración de un salón de belleza mediante la digitalización de sus procesos operativos y de atención al cliente. El sistema permite a los usuarios registrarse, autenticarse de forma segura y gestionar la reserva de citas en línea, seleccionando fecha, hora y múltiples servicios según su necesidad.

Por otra parte, los administradores cuentan con herramientas especializadas para la gestión integral del negocio, incluyendo el manejo de servicios, control de citas, administración de clientes y visualización de reportes básicos con indicadores clave. Además, la plataforma incorpora funcionalidades de seguridad como validación de correo electrónico, recuperación de contraseñas, confirmación de cuentas mediante token y gestión de sesiones.

## Tecnologías Utilizadas
*   **Framework:** Laravel 11 / 13
*   **Lenguaje:** PHP 8.3
*   **Base de Datos:** MySQL
*   **Estilos:** Tailwind CSS (Laravel Breeze)
*   **Frontend:** Blade Templating Engine

## Funcionalidades Implementadas
1.  **Capa de Datos:**
    *   Estructura relacional completa (`usuarios`, `servicios`, `citas`, `citasServicios`).
    *   Integridad referencial mediante claves foráneas.
2.  **Autenticación y Seguridad:**
    *   Sistema de Login/Logout funcional.
    *   Registro de usuarios con campos personalizados (Nombre, Apellido, Teléfono).
    *   Hash de contraseñas mediante Bcrypt.
    *   Protección de rutas mediante Middleware de Roles (`admin`, `editor`, `usuario`).
3.  **Gestión de Servicios:**
    *   Visualización pública de servicios para usuarios autenticados.
    *   **CRUD administrativo:** Solo administradores pueden crear, editar o eliminar servicios.
    *   Validación de datos tanto en cliente como en servidor.
4.  **Interfaz de Usuario:**
    *   Diseño responsive y moderno.
    *   Mensajes de estado (éxito/error) mediante notificaciones flash.

## Instrucciones de Instalación

1.  **Clonar el repositorio:**
    ```bash
    git clone https://github.com/innmajo/Sistema_Citas.git
    cd Sistema_Citas
    ```

2.  **Instalar dependencias:**
    ```bash
    composer install
    npm install
    npm run build
    ```

3.  **Configurar el entorno:**
    *   Copia el archivo `.env.example` a `.env`.
    *   Configura tus credenciales de base de datos en el archivo `.env`.

4.  **Generar clave y ejecutar migraciones:**
    ```bash
    php artisan key:generate
    php artisan migrate:fresh --seed
    ```

5.  **Iniciar servidor:**
    ```bash
    php artisan serve
    ```

## Credenciales de Usuarios de Prueba

| Rol | Correo | Contraseña |
| :--- | :--- | :--- |
| **Administrador** | `admin@ejemplo.com` | `admin123` |
| **Usuario** | `editor@ejemplo.com` | `editor123` |

## Licencia

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
