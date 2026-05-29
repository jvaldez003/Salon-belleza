-- AppSalon - Script SQL completo
-- Generado: 2026-05-29 16:19:07
-- Base de datos: appsalon

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
CREATE DATABASE IF NOT EXISTS `appsalon` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `appsalon`;

-- Estructura: banners
DROP TABLE IF EXISTS `banners`;
CREATE TABLE `banners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `subtitulo` text DEFAULT NULL,
  `imagen_url` varchar(255) NOT NULL,
  `texto_boton` varchar(255) DEFAULT NULL,
  `link_boton` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos: banners
INSERT INTO `banners` (`id`, `titulo`, `subtitulo`, `imagen_url`, `texto_boton`, `link_boton`, `activo`, `created_at`, `updated_at`) VALUES (1, 'Realza tu belleza', 'Realza tu belleza y resalta tu mejor versión. En nuestro salón de belleza te ofrecemos servicios profesionales de estética, cuidado capilar, manicure, pedicure, maquillaje y mucho más, en un espacio diseñado para tu bienestar, comodidad y confianza.', 'banners/EN3YKTgDNmZZ9h5RaadYELT5CYOaBzP2EpY5ksGp.jpg', NULL, NULL, 1, '2026-04-24 14:07:42', '2026-04-24 17:27:43');
INSERT INTO `banners` (`id`, `titulo`, `subtitulo`, `imagen_url`, `texto_boton`, `link_boton`, `activo`, `created_at`, `updated_at`) VALUES (2, 'Belleza, Estilo y Confianza', 'Transformamos tu imagen con servicios profesionales de belleza, cuidado personal y bienestar. Vive una experiencia única en un espacio pensado para resaltar tu esencia y hacerte sentir increíble cada día.', 'banners/jmwpxdcfE2unqBzHLfbRn4PdGlYeiP3kIFITPbvx.jpg', NULL, NULL, 1, '2026-04-24 14:30:38', '2026-04-24 14:33:53');

-- Estructura: cita_servicio
DROP TABLE IF EXISTS `cita_servicio`;
CREATE TABLE `cita_servicio` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cita_id` bigint(20) unsigned NOT NULL,
  `servicio_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cita_servicio_cita_id_foreign` (`cita_id`),
  KEY `cita_servicio_servicio_id_foreign` (`servicio_id`),
  CONSTRAINT `cita_servicio_cita_id_foreign` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cita_servicio_servicio_id_foreign` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos: cita_servicio
INSERT INTO `cita_servicio` (`id`, `cita_id`, `servicio_id`) VALUES (1, 1, 1);
INSERT INTO `cita_servicio` (`id`, `cita_id`, `servicio_id`) VALUES (2, 1, 2);
INSERT INTO `cita_servicio` (`id`, `cita_id`, `servicio_id`) VALUES (3, 1, 3);
INSERT INTO `cita_servicio` (`id`, `cita_id`, `servicio_id`) VALUES (4, 2, 2);
INSERT INTO `cita_servicio` (`id`, `cita_id`, `servicio_id`) VALUES (5, 3, 1);
INSERT INTO `cita_servicio` (`id`, `cita_id`, `servicio_id`) VALUES (6, 3, 2);
INSERT INTO `cita_servicio` (`id`, `cita_id`, `servicio_id`) VALUES (7, 4, 16);

-- Estructura: citas
DROP TABLE IF EXISTS `citas`;
CREATE TABLE `citas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `total` decimal(8,2) NOT NULL DEFAULT 0.00,
  `estado` enum('pendiente','confirmada','completada','cancelada') NOT NULL DEFAULT 'pendiente',
  `notas` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `citas_user_id_foreign` (`user_id`),
  KEY `citas_fecha_hora_index` (`fecha`,`hora`),
  KEY `citas_estado_index` (`estado`),
  CONSTRAINT `citas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos: citas
INSERT INTO `citas` (`id`, `fecha`, `hora`, `user_id`, `total`, `estado`, `notas`, `created_at`, `updated_at`) VALUES (1, '2026-05-30', '10:00:00', 5, '145000.00', 'confirmada', NULL, '2026-05-29 14:54:36', '2026-05-29 14:54:36');
INSERT INTO `citas` (`id`, `fecha`, `hora`, `user_id`, `total`, `estado`, `notas`, `created_at`, `updated_at`) VALUES (2, '2026-05-30', '13:00:00', 8, '70000.00', 'completada', NULL, '2026-05-29 15:30:31', '2026-05-29 15:56:15');
INSERT INTO `citas` (`id`, `fecha`, `hora`, `user_id`, `total`, `estado`, `notas`, `created_at`, `updated_at`) VALUES (3, '2026-05-28', '11:00:00', 5, '120000.00', 'completada', NULL, '2026-05-29 15:41:54', '2026-05-29 15:41:54');
INSERT INTO `citas` (`id`, `fecha`, `hora`, `user_id`, `total`, `estado`, `notas`, `created_at`, `updated_at`) VALUES (4, '2026-05-29', '16:00:00', 8, '120.00', 'cancelada', NULL, '2026-05-29 15:47:01', '2026-05-29 15:55:27');

-- Estructura: failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estructura: migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos: migrations
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2, '2014_10_12_100000_create_password_reset_tokens_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5, '2026_04_11_002029_add_role_to_users_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6, '2026_04_24_122527_create_servicios_table', 2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7, '2026_04_24_125304_update_precio_column_in_servicios_table', 3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8, '2026_04_24_125707_add_description_and_images_to_servicios', 4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9, '2026_04_24_135437_create_banners_table', 5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10, '2026_05_29_000001_add_citas_module_tables', 6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11, '2026_05_29_120000_create_resenas_table', 7);

-- Estructura: password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos: password_reset_tokens
INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES ('crisvalmu95@gmail.com', '$2y$12$f.E23ExSm2TBpzMmkXn0zuHmZ/kaoOQAtXjyyVVBx0a0lRif6CdXq', '2026-04-24 18:37:26');

-- Estructura: personal_access_tokens
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estructura: resenas
DROP TABLE IF EXISTS `resenas`;
CREATE TABLE `resenas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cita_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `calificacion` tinyint(3) unsigned NOT NULL,
  `comentario` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `resenas_cita_id_unique` (`cita_id`),
  KEY `resenas_user_id_foreign` (`user_id`),
  CONSTRAINT `resenas_cita_id_foreign` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `resenas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos: resenas
INSERT INTO `resenas` (`id`, `cita_id`, `user_id`, `calificacion`, `comentario`, `created_at`, `updated_at`) VALUES (1, 3, 5, 5, 'Excelente atención, muy profesionales.', '2026-05-29 15:41:54', '2026-05-29 15:41:54');
INSERT INTO `resenas` (`id`, `cita_id`, `user_id`, `calificacion`, `comentario`, `created_at`, `updated_at`) VALUES (2, 2, 8, 5, 'Exclente servicio', '2026-05-29 15:58:55', '2026-05-29 15:58:55');

-- Estructura: servicio_imagenes
DROP TABLE IF EXISTS `servicio_imagenes`;
CREATE TABLE `servicio_imagenes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `servicio_id` bigint(20) unsigned NOT NULL,
  `url` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `servicio_imagenes_servicio_id_foreign` (`servicio_id`),
  CONSTRAINT `servicio_imagenes_servicio_id_foreign` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos: servicio_imagenes
INSERT INTO `servicio_imagenes` (`id`, `servicio_id`, `url`, `created_at`, `updated_at`) VALUES (8, 2, 'servicios/Wjo3GXLiP1Il7ds3Zoclb7Jp7UZwbI0Aj3ocqjDt.jpg', '2026-04-24 17:30:14', '2026-04-24 17:30:14');
INSERT INTO `servicio_imagenes` (`id`, `servicio_id`, `url`, `created_at`, `updated_at`) VALUES (9, 3, 'servicios/yuCBJKvVn3zQxOjgCKgm1gA6MomTBdDWwncRseQH.jpg', '2026-04-24 17:31:08', '2026-04-24 17:31:08');
INSERT INTO `servicio_imagenes` (`id`, `servicio_id`, `url`, `created_at`, `updated_at`) VALUES (10, 4, 'servicios/qiLbbAfgqQSJuIQ2fKC9FPDxgS6dF4gqI0QFfoq8.jpg', '2026-04-24 17:34:13', '2026-04-24 17:34:13');
INSERT INTO `servicio_imagenes` (`id`, `servicio_id`, `url`, `created_at`, `updated_at`) VALUES (11, 1, 'servicios/nNf3UeasZLoTFkOmlBFepFLC0DWIOLz2gXtoShhI.jpg', '2026-04-24 17:35:22', '2026-04-24 17:35:22');
INSERT INTO `servicio_imagenes` (`id`, `servicio_id`, `url`, `created_at`, `updated_at`) VALUES (12, 6, 'servicios/8L9Q7CnaaaoQy33cymNSyiU5AQ3SoycXsJl7LfGB.jpg', '2026-04-24 17:38:30', '2026-04-24 17:38:30');
INSERT INTO `servicio_imagenes` (`id`, `servicio_id`, `url`, `created_at`, `updated_at`) VALUES (13, 7, 'servicios/dgx14S6WcjZk5H8cTk8JSHhs93bdZ9JTYxvm2trC.jpg', '2026-04-24 17:39:52', '2026-04-24 17:39:52');
INSERT INTO `servicio_imagenes` (`id`, `servicio_id`, `url`, `created_at`, `updated_at`) VALUES (14, 8, 'servicios/T6TTbNXhBQe3aIITzHDr8exs4Uk4PIWrZb14TMLK.jpg', '2026-04-24 17:41:01', '2026-04-24 17:41:01');
INSERT INTO `servicio_imagenes` (`id`, `servicio_id`, `url`, `created_at`, `updated_at`) VALUES (15, 12, 'servicios/F2hh2UAS3U2kZMoFtMidO5CbE04ptj8iGPFqKu3S.jpg', '2026-04-24 17:43:13', '2026-04-24 17:43:13');
INSERT INTO `servicio_imagenes` (`id`, `servicio_id`, `url`, `created_at`, `updated_at`) VALUES (16, 13, 'servicios/jW6YmTj4v0EkpOV1irP6XBRKmnWAb05dyY05fZlL.png', '2026-04-24 17:53:43', '2026-04-24 17:53:43');

-- Estructura: servicios
DROP TABLE IF EXISTS `servicios`;
CREATE TABLE `servicios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `duracion` smallint(5) unsigned NOT NULL DEFAULT 60,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos: servicios
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (1, 'Corte de Cabello Hombre', NULL, '50000.00', 60, 1);
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (2, 'Corte de Cabello Mujer', NULL, '70000.00', 60, 1);
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (3, 'Corte de Cabello Niño', NULL, '25000.00', 60, 1);
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (4, 'Peinado Mujer', NULL, '120000.00', 60, 1);
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (6, 'Tinte (Color)', NULL, '100000.00', 60, 1);
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (7, 'Uñas de Acrílico', NULL, '80000.00', 60, 1);
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (8, 'Uñas de Gel', NULL, '90000.00', 60, 1);
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (12, 'Tratamiento Capilar', NULL, '110000.00', 60, 1);
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (13, 'Maquillaje', NULL, '150000.00', 60, 1);
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (14, 'Ejemplo', 'AHSDIKHYIAUWDNF', '50000.00', 60, 1);
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (15, 'Corte de Cabello Hombre', NULL, '80.00', 60, 1);
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (16, 'Corte de Cabello Mujer', NULL, '120.00', 60, 1);
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (17, 'Corte de Cabello Niño', NULL, '60.00', 60, 1);
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (18, 'Peinado Mujer', NULL, '80.00', 60, 1);
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (19, 'Peinado Hombre', NULL, '60.00', 60, 1);
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (20, 'Tinte (Color)', NULL, '300.00', 60, 1);
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (21, 'Uñas de Acrílico', NULL, '400.00', 60, 1);
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (22, 'Uñas de Gel', NULL, '400.00', 60, 1);
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (23, 'Manicura', NULL, '100.00', 60, 1);
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (24, 'Pedicura', NULL, '150.00', 60, 1);
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (25, 'Maquillaje', NULL, '300.00', 60, 1);
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `activo`) VALUES (26, 'Tratamiento Capilar', NULL, '150.00', 60, 1);

-- Estructura: users
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'usuario',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos: users
INSERT INTO `users` (`id`, `name`, `email`, `telefono`, `role`, `activo`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES (1, 'Juan Felipe Valdez', 'juanf28valdez@gmail.com', NULL, 'admin', 1, '2026-05-29 15:35:35', '$2y$12$QT0yPjEWWYNvaBzR0ww/PuU7gpyoZJsun9Am/4pS8Ed/408Zc.lBu', 'LU62RPvUTS8PPkfr6Qv0WWZR2Wyg5Yw34l3WqxnxSmhRc3EXh0yD4Ug6xBqr', '2026-04-24 12:19:16', '2026-05-29 15:35:35');
INSERT INTO `users` (`id`, `name`, `email`, `telefono`, `role`, `activo`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES (2, 'Administrador', 'admin@ejemplo.com', NULL, 'admin', 1, '2026-05-29 15:35:35', '$2y$12$arGfJ9FAD6pSeKKOFn32H.2MP.WOvLYvmSYkVVgRu3J9Fu3cnmqZ.', NULL, '2026-04-24 12:20:05', '2026-05-29 15:35:35');
INSERT INTO `users` (`id`, `name`, `email`, `telefono`, `role`, `activo`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES (3, 'Editor', 'editor@ejemplo.com', NULL, 'editor', 1, '2026-05-29 15:35:35', '$2y$12$snl1inWg0qPOXzacaBk7IeN22RUMfhabTZrK.N1SMPu.qPXLg6O1y', NULL, '2026-04-24 12:20:05', '2026-05-29 15:35:35');
INSERT INTO `users` (`id`, `name`, `email`, `telefono`, `role`, `activo`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES (4, 'Laura Cristina Valdez', 'crisvalmu95@gmail.com', NULL, 'usuario', 1, '2026-05-29 15:35:35', '$2y$12$og5BTlpZnK53hOuepzHp5ushl2AaQqnOlF6Hf5VAuXR84IaCfHXcG', NULL, '2026-04-24 18:28:06', '2026-05-29 15:35:35');
INSERT INTO `users` (`id`, `name`, `email`, `telefono`, `role`, `activo`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES (5, 'Cliente Demo', 'cliente@ejemplo.com', '3001234567', 'usuario', 1, '2026-05-29 15:35:35', '$2y$12$qe7r2q/hhILKSOijOZwUq.WHycZgggTueiaw4OS880V/piXVjtj5q', NULL, '2026-05-29 14:54:36', '2026-05-29 15:35:35');
INSERT INTO `users` (`id`, `name`, `email`, `telefono`, `role`, `activo`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES (8, 'Maria Barrera', 'maochebarrera@gmail.com', NULL, 'usuario', 1, '2026-05-29 15:26:23', '$2y$12$1I0pWDZE4QI4C/xUdoCoIOJg9MXA1wLIkcoQBErp1uYNgCbiS5qYK', NULL, '2026-05-29 15:25:18', '2026-05-29 15:26:23');

SET FOREIGN_KEY_CHECKS = 1;
-- Fin del script