-- Base de datos del Sistema de Eventos CANACO
-- Versión: 1.0.0

CREATE DATABASE IF NOT EXISTS `canaco_eventos` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `canaco_eventos`;

-- Tabla de usuarios del sistema (SuperAdmin, Gestores)
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('superadmin','gestor') NOT NULL DEFAULT 'gestor',
  `telefono` varchar(20) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de eventos
CREATE TABLE `eventos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) NOT NULL,
  `descripcion` text NOT NULL,
  `fecha_evento` datetime NOT NULL,
  `ubicacion` varchar(300) NOT NULL,
  `cupo_maximo` int(11) NOT NULL DEFAULT 0,
  `costo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tipo_publico` enum('todos','empresas','invitados') NOT NULL DEFAULT 'todos',
  `imagen_banner` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `estado` enum('borrador','publicado','cerrado','cancelado') NOT NULL DEFAULT 'borrador',
  `campos_adicionales` json DEFAULT NULL,
  `usuario_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `usuario_id` (`usuario_id`),
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de empresas registradas
CREATE TABLE `empresas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rfc` varchar(13) NOT NULL,
  `razon_social` varchar(200) NOT NULL,
  `nombre_comercial` varchar(200) DEFAULT NULL,
  `direccion_fiscal` text DEFAULT NULL,
  `direccion_comercial` text DEFAULT NULL,
  `telefono_oficina` varchar(20) DEFAULT NULL,
  `giro_comercial` varchar(100) DEFAULT NULL,
  `numero_afiliacion` varchar(50) DEFAULT NULL,
  `consejero_camara` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rfc` (`rfc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de representantes de empresas
CREATE TABLE `representantes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empresa_id` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `puesto` varchar(100) DEFAULT NULL,
  `es_contacto_principal` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `empresa_id` (`empresa_id`),
  FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de invitados generales
CREATE TABLE `invitados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `telefono` varchar(20) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `ocupacion` varchar(100) DEFAULT NULL,
  `cargo_gubernamental` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `telefono` (`telefono`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de registros a eventos
CREATE TABLE `registros_eventos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evento_id` int(11) NOT NULL,
  `codigo_unico` varchar(20) NOT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `tipo_registrante` enum('empresa','invitado') NOT NULL,
  `empresa_id` int(11) DEFAULT NULL,
  `representante_id` int(11) DEFAULT NULL,
  `invitado_id` int(11) DEFAULT NULL,
  `estado` enum('registrado','confirmado','asistio','no_asistio','cancelado') NOT NULL DEFAULT 'registrado',
  `fecha_asistencia` timestamp NULL DEFAULT NULL,
  `validado_por` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo_unico` (`codigo_unico`),
  KEY `evento_id` (`evento_id`),
  KEY `empresa_id` (`empresa_id`),
  KEY `representante_id` (`representante_id`),
  KEY `invitado_id` (`invitado_id`),
  KEY `validado_por` (`validado_por`),
  FOREIGN KEY (`evento_id`) REFERENCES `eventos` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`representante_id`) REFERENCES `representantes` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`invitado_id`) REFERENCES `invitados` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`validado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de log de actividades
CREATE TABLE `log_actividades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `accion` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `tabla_afectada` varchar(50) DEFAULT NULL,
  `registro_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de configuraciones del sistema
CREATE TABLE `configuraciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(50) NOT NULL,
  `valor` text NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clave` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Datos de ejemplo

-- Insertar usuario SuperAdmin por defecto
INSERT INTO `usuarios` (`nombre`, `email`, `password`, `rol`) VALUES
('Administrador', 'admin@canaco.org.mx', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'superadmin'),
('Gestor de Eventos', 'gestor@canaco.org.mx', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'gestor');

-- Insertar configuraciones básicas
INSERT INTO `configuraciones` (`clave`, `valor`, `descripcion`) VALUES
('nombre_organizacion', 'Cámara de Comercio de Querétaro', 'Nombre de la organización'),
('direccion_organizacion', 'Querétaro, México', 'Dirección de la organización'),
('email_organizacion', 'contacto@canaco.org.mx', 'Email principal de la organización'),
('telefono_organizacion', '+52 442 123 4567', 'Teléfono de la organización'),
('limite_registros_por_rfc', '5', 'Límite de registros por RFC'),
('limite_registros_por_telefono', '3', 'Límite de registros por teléfono');

-- Insertar eventos de ejemplo
INSERT INTO `eventos` (`titulo`, `descripcion`, `fecha_evento`, `ubicacion`, `cupo_maximo`, `costo`, `slug`, `estado`, `usuario_id`) VALUES
('Segundo evento de prueba', 'La segunda descripción del segundo evento de prueba', '2026-11-11 09:49:23', 'B Josefa Vergara y Hernandez, Av. de las Artes, Paseo de las Artes 1531, 76090 Santiago de Querétaro, Qro.', 50000, 0.00, 'segundo-evento-prueba', 'publicado', 1),
('Foro de Innovación Empresarial', 'Encuentro de negocios y presentación de la plataforma tecnológica para empresas del estado', '2024-12-15 10:00:00', 'Centro de Convenciones de Querétaro', 200, 500.00, 'foro-innovacion-empresarial', 'publicado', 1);

-- Insertar empresas de ejemplo
INSERT INTO `empresas` (`rfc`, `razon_social`, `nombre_comercial`, `direccion_fiscal`, `telefono_oficina`, `giro_comercial`) VALUES
('ABC123456789', 'Empresa de Ejemplo S.A. de C.V.', 'Ejemplo Corp', 'Av. Principal 123, Querétaro, Qro.', '442-123-4567', 'Tecnología'),
('DEF987654321', 'Comercializadora del Bajío S.C.', 'Bajío Comercial', 'Blvd. Bernardo Quintana 456, Querétaro, Qro.', '442-987-6543', 'Comercio');

-- Insertar representantes de ejemplo
INSERT INTO `representantes` (`empresa_id`, `nombre_completo`, `email`, `telefono`, `puesto`, `es_contacto_principal`) VALUES
(1, 'Juan Pérez García', 'juan.perez@ejemplo.com', '442-111-2222', 'Director General', 1),
(2, 'María González López', 'maria.gonzalez@bajio.com', '442-333-4444', 'Gerente de Ventas', 1);

-- Insertar invitados de ejemplo
INSERT INTO `invitados` (`telefono`, `nombre_completo`, `email`, `fecha_nacimiento`, `ocupacion`) VALUES
('4421234567', 'Ana Martínez Rodríguez', 'ana.martinez@gmail.com', '1985-03-15', 'Empresaria'),
('4429876543', 'Carlos Hernández Silva', 'carlos.hernandez@hotmail.com', '1978-08-22', 'Consultor');