# AppSalon - Sistema de Gestión de Citas para Salón de Belleza

Aplicación web full-stack (Laravel + MySQL + Blade) para que clientes reserven citas y administradores gestionen servicios, usuarios, agenda y reportes.

## Tecnologías

- Laravel 10
- PHP 8.1+
- MySQL
- Laravel Breeze (autenticación)
- Laravel Sanctum (API)
- Tailwind CSS
- Chart.js (reportes)

## Funcionalidades implementadas

### Entrega intermedia
- Base de datos relacional: `users`, `servicios`, `citas`, `cita_servicio`
- Autenticación: registro, login/logout, recuperación de contraseña, verificación de email
- CRUD de servicios (solo admin)
- Catálogo público de servicios
- Roles: `admin`, `editor`, `usuario`
- Interfaz responsive con mensajes flash

### Entrega final
- **Citas:** reserva con calendario interactivo (FullCalendar), múltiples servicios, validación de disponibilidad, cancelación/edición, email de confirmación
- **Admin:** dashboard con citas del día e ingresos, agenda diaria/semanal, activar/desactivar usuarios, filtros
- **API REST:** `GET /api/servicios`, `GET/POST/PUT` citas con Sanctum
- **Reportes:** por período, ingresos por servicio, exportación CSV y PDF, gráficas Chart.js
- **Script SQL:** `database/appsalon_completo.sql` (regenerar con `php artisan db:export-sql`)
- **Documentación:** [docs/MANUAL_TECNICO.md](docs/MANUAL_TECNICO.md), [docs/API.md](docs/API.md)

## Base de datos (SQL)

Importar en phpMyAdmin o MySQL:

```bash
mysql -u root -p < database/appsalon_completo.sql
```

O usar migraciones:

```bash
php artisan migrate:fresh --seed
php artisan db:export-sql   # genera/actualiza el .sql
```

## Instalación

```bash
git clone <tu-repositorio>
cd Salon-belleza
composer install
npm install && npm run build
cp .env.example .env
# Configura DB_DATABASE, DB_USERNAME, DB_PASSWORD
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

## Correo electrónico (verificación y recuperación de contraseña)

En desarrollo local, los correos se guardan en el log (`storage/logs/laravel.log`) con `MAIL_MAILER=log`.

Flujos disponibles:
- **Registro:** tras crear cuenta → pantalla de verificación → enlace en el log/correo
- **Olvidé mi contraseña:** `/forgot-password` → enlace de restablecimiento por correo

Para enviar correos reales, configura SMTP en `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-correo@gmail.com
MAIL_PASSWORD=tu-contraseña-de-aplicacion
MAIL_ENCRYPTION=tls
APP_URL=http://127.0.0.1:8000
```

## Credenciales de prueba

| Rol | Email | Contraseña |
|-----|-------|------------|
| Administrador | admin@ejemplo.com | admin123 |
| Editor | editor@ejemplo.com | editor123 |
| Cliente | cliente@ejemplo.com | cliente123 |

## API

Ver documentación completa en [docs/API.md](docs/API.md).

## Horarios del salón

Edita `config/salon.php` o variables `.env`:

- `SALON_HORA_APERTURA=09:00`
- `SALON_HORA_CIERRE=18:00`
- `SALON_INTERVALO=30`

## Estructura principal

```
app/Http/Controllers/CitaController.php
app/Http/Controllers/ReporteController.php
app/Http/Controllers/Api/
app/Models/Cita.php
app/Services/CitaDisponibilidadService.php
resources/views/citas/
resources/views/reportes/
routes/api.php
```

## Licencia

MIT (Laravel framework).
