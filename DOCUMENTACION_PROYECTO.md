# 📚 Documentación Técnica del Sistema de Peluquería

Esta documentación detalla el funcionamiento interno del sistema desarrollado con **Laravel 12**, **Tailwind CSS** y **Alpine.js**.

---

## 🏛️ Arquitectura del Sistema (MVC)

El sistema sigue el patrón **Modelo-Vista-Controlador**, asegurando que el diseño, la lógica y los datos estén separados y organizados.

### 1. Modelos (Datos - `app/Models`)
Los modelos representan las tablas de la base de datos y sus relaciones.
- **[User.php](file:///c:/Users/Usuario/salon-belleza/app/Models/User.php):** Gestiona los perfiles y niveles de acceso (admin, editor, usuario).
- **[Servicio.php](file:///c:/Users/Usuario/salon-belleza/app/Models/Servicio.php):** Define el catálogo de tratamientos de belleza. Tiene una relación "Uno a Muchos" con las imágenes.
- **[Banner.php](file:///c:/Users/Usuario/salon-belleza/app/Models/Banner.php):** Controla el contenido dinámico del carrusel de la página de inicio.

### 2. Controladores (Lógica - `app/Http/Controllers`)
Aquí reside la "inteligencia" de la aplicación.
- **[ServicioController.php](file:///c:/Users/Usuario/salon-belleza/app/Http/Controllers/ServicioController.php):** Maneja el catálogo. Incluye lógica avanzada para subir, guardar y eliminar imágenes físicas del servidor.
- **[UsuariosController.php](file:///c:/Users/Usuario/salon-belleza/app/Http/Controllers/UsuariosController.php):** Implementa el CRUD de personal. Restringe acciones críticas (como crear o borrar) según el rol.
- **[BannerController.php](file:///c:/Users/Usuario/salon-belleza/app/Http/Controllers/BannerController.php):** Permite al administrador cambiar el aspecto visual de la Landing Page sin tocar código.

### 3. Vistas (Interfaz - `resources/views`)
Utilizan **Blade** (el motor de plantillas de Laravel) para generar el HTML dinámico.
- **[welcome.blade.php](file:///c:/Users/Usuario/salon-belleza/resources/views/welcome.blade.php):** La cara pública del salón. Muestra banners y servicios mediante componentes modernos.
- **[dashboard.blade.php](file:///c:/Users/Usuario/salon-belleza/resources/views/dashboard.blade.php):** El centro de mando tras el login. Adapta su contenido dinámicamente según quién haya entrado.
- **Layouts (`layouts/`):** Definen la estructura común (cabeceras, menús, fuentes) para no repetir código.

---

## 🔐 Seguridad y Roles

El sistema implementa **Middleware** para proteger las rutas:
- **Admin:** Control total. Puede Crear, Editar y Eliminar todo.
- **Editor:** Puede ver todo y editar información básica de usuarios, pero no puede borrar nada ni gestionar el catálogo completo.
- **Usuario:** Solo lectura. Puede ver la información interna pero no tiene botones de acción.

## 📩 Sistema de Correos
Utilizamos **Mailtrap** como entorno de pruebas (Sandbox). Esto permite que el flujo de recuperación de contraseña sea real y seguro, enviando correos con un diseño profesional de Laravel que pueden ser visualizados en el panel de Mailtrap.

---

## 🚀 Comandos Clave de Mantenimiento
- `php artisan serve`: Enciende el servidor lógico.
- `npm run dev`: Compila y actualiza el diseño visual.
- `php artisan storage:link`: Conecta la carpeta de imágenes con la web.
- `mysqldump`: Genera el backup de la base de datos para compartir con el equipo.

---
*Documentación generada para la entrega final del proyecto de Salón de Belleza.*
