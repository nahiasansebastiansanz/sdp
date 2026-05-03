# 🧘 SDP — Sensación de Paz

> Aplicación web de meditación, respiración y desarrollo personal desarrollada como proyecto intermodular del ciclo **DAW (Desarrollo de Aplicaciones Web)** — curso 2025-2026.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB-4479A1?logo=mysql&logoColor=white)
![jQuery](https://img.shields.io/badge/jQuery-3.7.1-0769AD?logo=jquery&logoColor=white)
![PHPUnit](https://img.shields.io/badge/PHPUnit-9-6FA035?logo=php&logoColor=white)
![Jest](https://img.shields.io/badge/Jest-29-C21325?logo=jest&logoColor=white)
![License](https://img.shields.io/badge/license-Académico-blue)

---

## 📖 Descripción

**SDP (Sensación de Paz)** es una plataforma web pensada para ayudar a los usuarios a establecer una rutina diaria de bienestar mental mediante **meditaciones guiadas y libres**, **ejercicios de respiración personalizables**, un **diario emocional**, **estadísticas de progreso** y un sistema de **logros y retos** que fomenta la constancia.

La aplicación está construida con una **arquitectura PHP MVC propia** (sin frameworks), con validación en dos capas (cliente y servidor), conexión segura a base de datos mediante *prepared statements* y un panel de administración completo para gestionar contenidos.

---

## ✨ Características

### Para el usuario
- 🔐 **Registro y login** con hash SHA-256 y control de sesión (timeout de 1800 s)
- 🧘 **Meditaciones libres** (cronómetro con gong opcional) y **guiadas** (con audio e instrucciones por niveles)
- 🌬️ **Respiraciones** configurables por ciclos (inhala / retiene / exhala / retiene2)
- 📔 **Diario emocional** con registro de humor (bien / neutral / mal)
- ⭐ **Favoritos** sobre meditaciones y respiraciones
- 🏆 **Logros y retos** desbloqueables según la actividad del usuario
- 📊 **Estadísticas** de sesiones e historial visualizadas con Chart.js
- 📨 **Formulario de contacto** integrado con **osTicket** vía API

### Para el administrador
- Gestión CRUD de usuarios, categorías, meditaciones, respiraciones, retos y logros
- Bandeja de mensajes de contacto recibidos
- Control de roles (`usuario` / `admin`)

---

## 🧰 Stack tecnológico

| Capa | Tecnología |
|------|-----------|
| Backend | PHP 8.x (MVC propio, OOP) |
| Base de datos | MySQL / MariaDB |
| Frontend | HTML5, CSS3, JavaScript (ES6+) |
| Librerías JS | jQuery 3.7.1, jQuery Validate 1.19.5, Chart.js |
| Validación servidor | `ValidadorPHP` (utilidad encadenable propia) |
| Tests PHP | PHPUnit 9 |
| Tests JS | Jest 29 |
| Integración tickets | osTicket (API HTTP) |
| Servidor local | XAMPP (Apache + MySQL) |

---

## 📂 Estructura del proyecto

```
SDP/
├── assets/
│   ├── css/styles.css              # Hoja de estilos global
│   └── js/
│       ├── main.js                 # Validación cliente OOP (jQuery Validate)
│       └── validador.js            # Reglas reutilizables
├── config/
│   └── conf.ini                    # Credenciales BD + osTicket (NO subir a Git)
├── controller/                     # Controladores MVC
│   ├── UsuarioController.php
│   ├── MeditacionController.php
│   ├── RespiracionController.php
│   ├── DiarioController.php
│   ├── SesionController.php
│   ├── CategoriaController.php
│   ├── LogroController.php
│   └── RetoController.php
├── dao/                            # Acceso a datos (mysqli + prepared statements)
│   ├── SDPBD.php                   # Singleton de conexión
│   └── *DAO.php
├── model/                          # Modelos POPO con getters/setters
├── view/                           # Vistas
│   ├── admin/                      # Panel de administración
│   ├── meditacion/                 # Vistas de meditación libre/guiada
│   └── statics/                    # Cabecera y pie compartidos
├── util/
│   ├── ValidadorPHP.php            # Validador encadenable servidor
│   └── OsTicketClient.php          # Cliente API osTicket
├── database/
│   └── sdp.sql                     # Esquema + datos de prueba
├── tests/
│   ├── ValidadorPHPTest.php        # Tests PHPUnit
│   └── validador.test.js           # Tests Jest
├── index.php                       # Front controller
├── .htaccess                       # Protección de archivos sensibles
├── composer.json
├── package.json
└── phpunit.xml
```

---

## 🚀 Instalación

### Requisitos previos
- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL/MariaDB) o equivalente
- PHP **≥ 8.0**
- Composer
- Node.js + npm (solo si vas a ejecutar los tests JS)

### Pasos

1. **Clona el repositorio** dentro de la carpeta `htdocs` de XAMPP:
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/<usuario>/SDP.git
   cd SDP
   ```

2. **Instala dependencias**:
   ```bash
   composer install
   npm install
   ```

3. **Importa la base de datos**:
   - Arranca Apache y MySQL desde el panel de XAMPP.
   - Abre [phpMyAdmin](http://localhost/phpmyadmin) e importa `database/sdp.sql`.
   - Esto crea la BD `sdp` con sus tablas y datos de prueba.

4. **Configura las credenciales** en `config/conf.ini`:
   ```ini
   server = localhost
   user   = root
   pasw   = ""
   bd     = sdp

   osticket_enabled  = 0
   osticket_url      = "http://127.0.0.1/osTicket/upload/api/http.php/tickets.json"
   osticket_api_key  = "TU_API_KEY"
   osticket_topic_id = 1
   ```

5. **Accede a la aplicación**:
   ```
   https://sensaciondepaz.page.gd/sdp/view/loginView.php
   ```

---

## 👥 Credenciales de prueba

| Rol | Usuario | Contraseña |
|-----|---------|-----------|
| Administrador | `admin` | *(ver `database/sdp.sql`)* |
| Usuario | `demo` | *(ver `database/sdp.sql`)* |

> Las contraseñas se almacenan con **SHA-256** en el campo `contrasena_hash`.

---

## 🧪 Tests

### Backend (PHPUnit)
```bash
./vendor/bin/phpunit
```

### Frontend (Jest)
```bash
npm test
npm run test:coverage   # con cobertura
```

La matriz de casos de prueba está documentada en `MatrizDeCasosDePruebas.xlsx`.

---

## 🗃️ Modelo de datos

Tablas principales:

- `usuarios` — datos de cuenta y perfil
- `categorias` — clasificación de meditaciones
- `meditaciones` — guiadas (audio + instrucciones)
- `respiraciones` — patrones configurables
- `sesiones` — registro de prácticas realizadas
- `diario` — entradas con humor
- `favoritos` — meditaciones/respiraciones marcadas
- `logros` + `usuario_logro` — sistema de logros
- `retos` + `usuario_reto` — retos activos por usuario
- `mensajes_contacto` — buzón del formulario público

Esquema completo en [`database/sdp.sql`](database/sdp.sql).

---

## ♿ Accesibilidad y validación

El proyecto se ha auditado con:
- **axe DevTools** y **WAVE** (accesibilidad WCAG)
- **Lighthouse** (rendimiento + accesibilidad)
- **W3C Validator** (HTML / CSS)

---

## 🔒 Buenas prácticas aplicadas

- Prepared statements en todas las consultas SQL
- Validación en doble capa (cliente + servidor)
- Hash SHA-256 para contraseñas
- Timeout de sesión de 1800 s
- `.htaccess` que bloquea acceso directo a `.ini`, `.sql` y `.log`
- Separación estricta de responsabilidades MVC

---

## 📝 .gitignore recomendado

```gitignore
# Dependencias
/vendor/
/node_modules/

# Configuración con credenciales
/config/conf.ini

# PHPUnit
.phpunit.result.cache
/phpunit.xml.bak

# Sistema
.DS_Store
Thumbs.db

# IDE
.vscode/
.idea/
```

> ⚠️ **Importante**: `config/conf.ini` contiene credenciales y **no debe subirse al repositorio**. Crea un `config/conf.ini.example` con valores ficticios para que otros desarrolladores sepan cómo configurarlo.

---

## 👨‍💻 Equipo

| Rol | Integrante |
|-----|-----------|
| Project Lead / QA | **Michael Kaberdin** |
| UI/UX y Frontend | **Aitor Sánchez Fernández** |
| Backend / Base de datos | **Nahia San Sebastián Sanz** |

**Tutor**: Javier Martín Martín

---

## 📄 Licencia

Proyecto académico desarrollado en el marco del módulo **Proyecto Intermodular DAW** (curso 2025-2026). Uso educativo.

---
