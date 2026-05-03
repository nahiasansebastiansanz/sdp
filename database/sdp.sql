-- 
--  Base de datos – MySQL/MariaDB
-- 

SET NAMES utf8mb4;
SET foreign_key_checks = 0;

-- Crear/usar BD
CREATE DATABASE IF NOT EXISTS sdp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sdp;

--  1. usuarios 
CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario      INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario  VARCHAR(60)  NOT NULL UNIQUE,
    contrasena_hash VARCHAR(64)  NOT NULL,
    nombre_completo VARCHAR(120) NOT NULL,
    email           VARCHAR(120) NOT NULL,
    edad            TINYINT UNSIGNED,
    genero          ENUM('Hombre','Mujer','Otro'),
    telefono        VARCHAR(20),
    perfil          ENUM('usuario','admin') NOT NULL DEFAULT 'usuario',
    fecha_alta      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--  2. categorias 
CREATE TABLE IF NOT EXISTS categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre       VARCHAR(80) NOT NULL UNIQUE,
    descripcion  TEXT,
    icono        VARCHAR(10) DEFAULT '🏷️'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--  3. meditaciones 
CREATE TABLE IF NOT EXISTS meditaciones (
    id_meditacion INT AUTO_INCREMENT PRIMARY KEY,
    titulo        VARCHAR(150) NOT NULL,
    descripcion   TEXT,
    id_categoria  INT,
    nivel         ENUM('principiante','intermedio','avanzado') NOT NULL DEFAULT 'principiante',
    duracion_min  TINYINT UNSIGNED NOT NULL DEFAULT 10,
    icono         VARCHAR(10) DEFAULT '🧘',
    archivo_audio VARCHAR(200),
    instrucciones TEXT,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--  4. respiraciones 
CREATE TABLE IF NOT EXISTS respiraciones (
    id_respiracion INT AUTO_INCREMENT PRIMARY KEY,
    nombre         VARCHAR(120) NOT NULL UNIQUE,
    descripcion    TEXT,
    inhala_seg     TINYINT UNSIGNED NOT NULL DEFAULT 4,
    retiene_seg    TINYINT UNSIGNED NOT NULL DEFAULT 0,
    exhala_seg     TINYINT UNSIGNED NOT NULL DEFAULT 4,
    retiene2_seg   TINYINT UNSIGNED NOT NULL DEFAULT 0,
    ciclos         TINYINT UNSIGNED NOT NULL DEFAULT 8
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--  5. sesiones 
CREATE TABLE IF NOT EXISTS sesiones (
    id_sesion    INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario   INT NOT NULL,
    tipo         ENUM('libre','guiada','respiracion') NOT NULL,
    duracion_min TINYINT UNSIGNED NOT NULL,
    id_meditacion INT,
    con_gong     TINYINT(1) NOT NULL DEFAULT 0,
    fecha_sesion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario)   REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_meditacion) REFERENCES meditaciones(id_meditacion) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--  6. diario 
CREATE TABLE IF NOT EXISTS diario (
    id_entrada    INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario    INT NOT NULL,
    titulo        VARCHAR(200) NOT NULL,
    contenido     TEXT NOT NULL,
    humor         ENUM('bien','neutral','mal') NOT NULL DEFAULT 'neutral',
    fecha_entrada DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--  7. favoritos 
CREATE TABLE IF NOT EXISTS favoritos (
    id_usuario    INT NOT NULL,
    id_meditacion INT NOT NULL,
    fecha_guardado DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_usuario, id_meditacion),
    FOREIGN KEY (id_usuario)   REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_meditacion) REFERENCES meditaciones(id_meditacion) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--  8. logros 
CREATE TABLE IF NOT EXISTS logros (
    id_logro        INT AUTO_INCREMENT PRIMARY KEY,
    titulo          VARCHAR(120) NOT NULL,
    descripcion     TEXT,
    icono           VARCHAR(10) DEFAULT '🏅',
    condicion_tipo  ENUM('sesiones','minutos','racha') NOT NULL,
    condicion_valor SMALLINT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--  9. usuario_logro 
CREATE TABLE IF NOT EXISTS usuario_logro (
    id_usuario      INT NOT NULL,
    id_logro        INT NOT NULL,
    fecha_obtencion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_usuario, id_logro),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_logro)   REFERENCES logros(id_logro) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--  10. retos 
CREATE TABLE IF NOT EXISTS retos (
    id_reto        INT AUTO_INCREMENT PRIMARY KEY,
    titulo         VARCHAR(150) NOT NULL,
    descripcion    TEXT,
    tipo           ENUM('racha','minutos','sesiones','tipos') NOT NULL,
    objetivo_valor SMALLINT UNSIGNED NOT NULL,
    duracion_dias  TINYINT UNSIGNED NOT NULL DEFAULT 7,
    activo         TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--  11. usuario_reto 
CREATE TABLE IF NOT EXISTS usuario_reto (
    id_usuario   INT NOT NULL,
    id_reto      INT NOT NULL,
    progreso     SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    completado   TINYINT(1) NOT NULL DEFAULT 0,
    fecha_inicio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_usuario, id_reto),
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_reto)    REFERENCES retos(id_reto) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--  12. mensajes_contacto 
CREATE TABLE IF NOT EXISTS mensajes_contacto (
    id_mensaje   INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario   INT NULL,
    nombre       VARCHAR(120) NOT NULL,
    email        VARCHAR(120) NOT NULL,
    asunto       ENUM('soporte','sugerencia','colaboracion','otro') NOT NULL,
    mensaje      TEXT NOT NULL,
    fecha_envio  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET foreign_key_checks = 1;

-- 
--  DATOS DE PRUEBA
-- 

-- Usuarios  (contraseñas: admin123 / aitor123 / nahia123 / michael123 / user123)
-- Contraseñas (SHA-256 real):
--   admin    → admin123
--   aitor    → aitor123
--   nahia    → nahia123
--   michael  → michael123
--   usuario1 → user123
INSERT INTO usuarios (nombre_usuario, contrasena_hash, nombre_completo, email, perfil) VALUES
('admin',    '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'Administrador SDP',          'admin@sdp.com',   'admin'),
('aitor',    'e0ccaedce47b096ef6fcb63a2c9952c5e1babe89d67ebf6f4a44e7d06b9e905b', 'Sánchez Fernández Aitor',    'aitor@sdp.com',   'usuario'),
('nahia',    '9e06a40a83f65bf67a9a9e396808aa20d0c14b472756b05cfeb6abd0bf55a50d', 'San Sebastián Sanz Nahia',   'nahia@sdp.com',   'usuario'),
('michael',  '95bfb24de17d285d734b9eaa9109bfe922adc85f20d2e5e66a78bddb4a4ebddb', 'Kaberdin Michael',           'michael@sdp.com', 'usuario'),
('usuario1', 'e606e38b0d8c19b24cf0ee3808183162ea7cd63ff7912dbb22b5e803286b4446', 'Usuario Demo 1',             'user1@sdp.com',   'usuario');

-- Categorías
INSERT INTO categorias (nombre, descripcion, icono) VALUES
('Relajación',     'Para soltar tensiones del día',                       '🌿'),
('Sueño',          'Preparar el cuerpo y la mente para dormir',           '🌙'),
('Concentración',  'Mejora el foco y la claridad mental',                 '🎯'),
('Ansiedad',       'Técnicas para reducir el estrés y la ansiedad',       '💆'),
('Energía',        'Actívate y empieza el día con vitalidad',             '☀️');

-- Meditaciones
INSERT INTO meditaciones (titulo, descripcion, id_categoria, nivel, duracion_min, icono, instrucciones) VALUES
('Calma profunda',           'Una guía serena para soltar tensiones del día.',                    1, 'principiante', 10, '🌿', 'Siéntate cómodamente. Cierra los ojos. Respira profundo...'),
('Relajación muscular',      'Relajación progresiva de grupos musculares de pies a cabeza.',      1, 'intermedio',   15, '🌿', 'Empieza tensando los pies 5 segundos. Suelta. Sube...'),
('Dulce descanso',           'Meditación nocturna para preparar la mente antes de dormir.',       2, 'principiante', 20, '🌙', 'Túmbate. Cierra los ojos. Imagina un cielo estrellado...'),
('Sueño profundo',           'Body scan y visualización guiada para conciliar el sueño.',         2, 'avanzado',     30, '🌙', 'Escanea tu cuerpo desde la cabeza hasta los pies...'),
('Foco total',               'Atención plena para mantener la concentración en el trabajo.',      3, 'intermedio',   10, '🎯', 'Lleva tu atención a la respiración. Cuando la mente divague...'),
('Calma ante la ansiedad',   'Respiración y anclaje al presente para los momentos difíciles.',    4, 'principiante',  8, '💆', 'Siente los pies en el suelo. Nombra 5 cosas que ves...'),
('Mañana con energía',       'Meditación activa para empezar el día con claridad y vitalidad.',   5, 'principiante', 10, '☀️', 'Siéntate erguido. Visualiza cómo será tu día ideal...');

-- Respiraciones
INSERT INTO respiraciones (nombre, descripcion, inhala_seg, retiene_seg, exhala_seg, retiene2_seg, ciclos) VALUES
('Respiración 4-7-8',        'Técnica del Dr. Weil para reducir la ansiedad y mejorar el sueño.',      4, 7, 8, 0, 4),
('Respiración cuadrada',     'Box breathing. 4 fases iguales. Usada por las fuerzas especiales.',      4, 4, 4, 4, 6),
('Método Wim Hof',           '30 respiraciones profundas rápidas seguidas de retención. Energizante.', 2, 0, 2, 0, 30),
('Respiración diafragmática','Respiración abdominal lenta y profunda. Base de toda práctica.',         5, 0, 6, 0, 8),
('Nadi Shodhana',            'Respiración alterna de fosas nasales del yoga. Equilibra hemisferios.',  4, 4, 4, 0, 8),
('Respiración 2-1-4-1',      'Variante suave con retención corta. Ideal para principiantes.',          2, 1, 4, 1, 8);

-- Logros
INSERT INTO logros (titulo, descripcion, icono, condicion_tipo, condicion_valor) VALUES
('Primer paso',       'Completaste tu primera sesión de meditación.',          '🌱', 'sesiones', 1),
('Tres días',         'Racha de 3 días meditando.',                            '🔥', 'racha',    3),
('10 sesiones',       'Has completado 10 sesiones en total.',                  '⭐', 'sesiones', 10),
('30 min acumulados', 'Has acumulado 30 minutos meditando.',                   '🌊', 'minutos',  30),
('7 días seguidos',   'Racha de 7 días consecutivos.',                         '🏅', 'racha',    7),
('100 minutos',       'Has acumulado 100 minutos meditando.',                  '💎', 'minutos',  100),
('Mes completo',      'Racha de 30 días. Eres constante.',                     '🏆', 'racha',    30),
('50 sesiones',       'Has completado 50 sesiones. ¡Meditación como hábito!',  '🎯', 'sesiones', 50);

-- Retos
INSERT INTO retos (titulo, descripcion, tipo, objetivo_valor, duracion_dias, activo) VALUES
('Semana de calma',       'Medita al menos 5 días de los próximos 7.',           'racha',    5,  7,  1),
('30 minutos esta semana','Acumula 30 minutos de meditación en 7 días.',         'minutos',  30, 7,  1),
('Explorador de técnicas','Prueba los tres tipos de meditación esta semana.',    'tipos',    3,  7,  1),
('Mes zen',               'Mantén una racha de 20 días en este mes.',            'racha',    20, 30, 1);

-- Sesiones de prueba para aitor (id_usuario = 2)
INSERT INTO sesiones (id_usuario, tipo, duracion_min, id_meditacion, con_gong, fecha_sesion) VALUES
(2, 'libre',       14, NULL, 1, DATE_SUB(NOW(), INTERVAL 0 DAY)),
(2, 'guiada',      20, 3,    0, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(2, 'respiracion',  8, NULL, 0, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(2, 'libre',       10, NULL, 1, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(2, 'guiada',      15, 2,    0, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(2, 'respiracion',  6, NULL, 0, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(2, 'libre',       20, NULL, 1, DATE_SUB(NOW(), INTERVAL 6 DAY));

-- Entradas diario para aitor
INSERT INTO diario (id_usuario, titulo, contenido, humor, fecha_entrada) VALUES
(2, 'Calma después de la tormenta', 'Hoy he meditado 15 minutos antes del trabajo y he notado una diferencia enorme en mi humor.', 'bien',    DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(2, 'Difícil concentrarse',         'La sesión de hoy fue complicada, la mente no paraba de vagar. Pero al menos lo intenté.',      'neutral', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(2, '7 días consecutivos 🔥',        'Hoy se cumplen 7 días seguidos meditando. Nunca pensé que llegaría hasta aquí.',               'bien',    DATE_SUB(NOW(), INTERVAL 3 DAY));

-- Logros obtenidos por aitor
INSERT INTO usuario_logro (id_usuario, id_logro, fecha_obtencion) VALUES
(2, 1, DATE_SUB(NOW(), INTERVAL 6 DAY)),
(2, 2, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(2, 3, DATE_SUB(NOW(), INTERVAL 2 DAY));

-- Reto aceptado por aitor
INSERT INTO usuario_reto (id_usuario, id_reto, progreso, completado) VALUES
(2, 1, 4, 0),
(2, 2, 22, 0);

-- Favorito de aitor
INSERT INTO favoritos (id_usuario, id_meditacion) VALUES
(2, 1),
(2, 3),
(2, 6);

-- Mensajes de contacto de prueba
INSERT INTO mensajes_contacto (id_usuario, nombre, email, asunto, mensaje, fecha_envio) VALUES
(2, 'Sánchez Fernández Aitor', 'aitor@sdp.com', 'sugerencia', 'Me gustaría que añadierais más meditaciones para el sueño. La de "Dulce descanso" me funciona muy bien.', DATE_SUB(NOW(), INTERVAL 2 DAY)),
(NULL, 'Visitante Anónimo', 'anonimo@ejemplo.com', 'soporte', 'No consigo recuperar mi contraseña. ¿Podéis ayudarme?', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(3, 'San Sebastián Sanz Nahia', 'nahia@sdp.com', 'colaboracion', 'Hola, soy instructora de yoga y me encantaría colaborar con el proyecto. ¿Cómo puedo contactar?', NOW());
