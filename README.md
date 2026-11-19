# 🎢 Parque Diver

Aplicación web desarrollada en **Laravel** para la administración de un parque de atracciones: gestión de sedes y atracciones (con foto), panel con estadísticas, reporte en PDF y una **API REST** protegida con tokens.

El proyecto sigue el patrón **MVC**, usa **PostgreSQL** como base de datos (alojada en Neon), se publica en **Render** y su código vive en **GitHub**.

**Sitio en producción:** https://parque-diver.onrender.com

> ⚠️ En el plan gratuito de Render la aplicación se suspende tras un periodo sin visitas; la primera carga puede tardar hasta un minuto.

---

## ✨ Funcionalidades

- Registro, inicio y cierre de sesión (Laravel Breeze + Blade).
- Contraseñas almacenadas con hash **bcrypt** (nunca en texto plano).
- Recuperación de contraseña por correo con token de un solo uso (vence a los 60 minutos), enviado mediante **Resend**.
- **CRUD de Sedes** (crear, listar, ver, editar, eliminar).
- **CRUD de Atracciones** con subida de imagen opcional (JPG, PNG o WEBP, máx. 2 MB).
- Listados con búsqueda, ordenamiento y paginación en el navegador (**DataTables**).
- **Panel (dashboard)** con gráfico de barras de atracciones por sede (**Chart.js**) y datos del usuario activo.
- **Reporte en PDF** con las atracciones ordenadas por sede (**dompdf**).
- Edición de perfil y eliminación de cuenta.
- **API REST con Sanctum**: login por token, rutas públicas de lectura y rutas protegidas de escritura.
- **Consola API**: pantalla dentro de la aplicación para probar la API (GET, POST, PUT, DELETE) con `fetch`.
- Auditoría automática de creación y edición (trait `Auditable`).

---

## 🧰 Stack tecnológico

| Capa | Tecnología |
|---|---|
| Backend | Laravel (starter kit con Breeze, Blade) sobre PHP 8.3 |
| Base de datos | PostgreSQL en Neon (en la nube) |
| Frontend | Blade, Vite, DataTables y Chart.js (vía CDN) |
| PDF | dompdf |
| API | Laravel Sanctum (tokens) |
| Correo | Resend (API) |
| Contenedores | Docker (PHP 8.3 + Apache) |
| Despliegue | Render |
| Control de versiones | GitHub |

---

## 🏗️ Arquitectura (MVC)

Laravel separa el código en tres capas:

- **Modelo** (`app/Models`): representa una tabla y sus relaciones (ej. `Sede`, `Atraccion`).
- **Vista** (`resources/views`): el HTML que ve el usuario, escrito en Blade.
- **Controlador** (`app/Http/Controllers`): recibe la petición, valida, consulta al modelo y devuelve una vista o JSON.

Las **rutas** (`routes/web.php` para las páginas y `routes/api.php` para la API) indican qué controlador atiende cada dirección.

**Flujo de ejemplo — abrir `/sedes`:**

1. El navegador solicita `/sedes`.
2. La ruta está dentro del grupo con middleware `auth`: si no hay sesión, redirige al login.
3. Se ejecuta `SedeController@index`, que consulta las sedes con Eloquent.
4. El controlador entrega la vista `sedes/index.blade.php`, que hereda de `x-app-layout`.
5. DataTables convierte la tabla en un listado con búsqueda y orden.

---

## ✅ Requisitos

- PHP **8.3** con la extensión `pdo_pgsql`
- [Composer](https://getcomposer.org/)
- Node.js y npm
- Una base de datos PostgreSQL (local o una cuenta gratuita en [Neon](https://neon.tech/))

---

## 🚀 Instalación desde cero

```bash
# 1. Clonar el repositorio
git clone <URL_DEL_REPOSITORIO>
cd parque-diver

# 2. Dependencias de PHP y de Node
composer install
npm install

# 3. Configuración
cp .env.example .env
php artisan key:generate

# 4. Configurar la conexión a PostgreSQL en .env (ver sección siguiente)

# 5. Crear las tablas y el enlace de imágenes
php artisan migrate
php artisan storage:link
```

> El archivo `.env` **no se sube a GitHub** porque contiene claves y contraseñas.

---

## ▶️ Ejecución en local

Se necesitan **dos terminales** abiertas en la carpeta del proyecto:

```bash
# Terminal 1 — servidor de Laravel
php artisan serve

# Terminal 2 — compilación de estilos y JS con Vite
npm run dev
```

Luego abrir: http://localhost:8000

No hace falta levantar una base de datos local si se apunta a Neon, ya que está en internet.

---

## 🔐 Variables de entorno principales

| Variable | Descripción |
|---|---|
| `APP_KEY` | Clave de la aplicación (`php artisan key:generate`) |
| `APP_URL` | URL base de la aplicación |
| `DB_CONNECTION` | `pgsql` |
| `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Credenciales de PostgreSQL (Neon) |
| `DB_PREFIX` | `lv_` — prefijo de todas las tablas de la aplicación |
| `SESSION_DRIVER` | `database` |
| `MAIL_MAILER` | `resend` |
| `RESEND_API_KEY` | Clave de la API de Resend |
| `MAIL_FROM_ADDRESS` | Remitente de los correos |

En Render estas claves se cargan como **variables de entorno** en el panel del servicio; nada secreto está en el repositorio.

---

## 🗄️ Base de datos

La estructura **no se crea a mano**: está descrita en archivos de migración (`database/migrations`) que Laravel ejecuta con `php artisan migrate`. Así la estructura queda versionada en GitHub y se puede recrear en cualquier entorno.

**Tablas del parque:** `sedes`, `atracciones`, `titulares`, `visitantes`, `entradas`, además de `users` y las tablas del sistema (sesiones, caché, trabajos y tokens de la API).

- Todas las tablas llevan el prefijo **`lv_`** (configurable con `DB_PREFIX`).
- Las claves primarias de las tablas del parque son **UUID**.
- Los modelos usan el trait `HasUuids` y declaran `$fillable` para proteger la asignación masiva.
- `Titular`, `Visitante` y `Entrada` usan el trait **`Auditable`**, que llena automáticamente `id_usuario_creacion` e `id_usuario_edita` con el usuario autenticado.

**Relaciones principales:**

- `Sede` **hasMany** `Atraccion` · `Atraccion` **belongsTo** `Sede`
- `Titular` **hasMany** `Visitante` y **hasMany** `Entrada`

**Integridad:** si se intenta eliminar una sede con atracciones, el controlador lo verifica y muestra un aviso (la API responde `409`); además, la llave foránea impide dejar atracciones huérfanas.

---

## 🔌 API REST

Autenticación mediante **Sanctum**: el login devuelve un token que el cliente envía en la cabecera `Authorization: Bearer <token>`. Laravel guarda solo una versión cifrada del token.

### Endpoints

| Método | Ruta | Acceso | Descripción |
|---|---|---|---|
| `POST` | `/api/login` | Público | Valida credenciales y devuelve un token (límite: 5 intentos/minuto) |
| `GET` | `/api/atracciones` | Público | Lista las atracciones |
| `GET` | `/api/atracciones/{id}` | Público | Detalle de una atracción |
| `POST` | `/api/atracciones` | Token | Crea una atracción |
| `PUT` | `/api/atracciones/{id}` | Token | Actualiza una atracción |
| `DELETE` | `/api/atracciones/{id}` | Token | Elimina una atracción |
| CRUD | `/api/sedes` | Token | Todas las operaciones sobre sedes |
| `GET` | `/api/me` | Token | Datos del usuario autenticado |
| `POST` | `/api/logout` | Token | Revoca el token actual |

### Cabeceras habituales

| Cabecera | Uso |
|---|---|
| `Accept: application/json` | Pedir la respuesta en JSON |
| `Content-Type: application/json` | Indicar que el cuerpo enviado es JSON |
| `Authorization: Bearer <token>` | Enviar el token en rutas protegidas |

### Ejemplo de uso

```bash
# 1. Obtener un token
curl -X POST https://parque-diver.onrender.com/api/login \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"email":"usuario@example.com","password":"tu_contraseña"}'

# 2. Consumir una ruta protegida
curl https://parque-diver.onrender.com/api/me \
  -H "Accept: application/json" \
  -H "Authorization: Bearer <TOKEN>"
```

### Códigos de respuesta

| Código | Significado |
|---|---|
| `200` | Operación correcta |
| `201` | Recurso creado |
| `401` | Token ausente o inválido |
| `409` | Conflicto (ej. eliminar una sede con atracciones) |
| `422` | Los datos no pasan la validación |

> Los tokens de Sanctum **no expiran por defecto**; pueden revocarse con `POST /api/logout` o configurarse con expiración.

---

## 🖥️ Consola API (dentro de la app)

Pantalla incluida en la aplicación que usa `fetch` de JavaScript para llamar a la propia API con GET, POST, PUT y DELETE:

- Obtiene el token con el login y lo guarda en `sessionStorage` (se borra al cerrar la pestaña).
- Envía el token en la cabecera `Authorization`.
- Muestra la petición enviada (con el token oculto) y la respuesta con su código HTTP.

Se implementó desde el navegador y no desde PHP porque `artisan serve` atiende una petición a la vez y el servidor no puede llamarse a sí mismo.

---

## 📁 Estructura del proyecto (archivos clave)

| Archivo | Para qué sirve |
|---|---|
| `routes/web.php` | Rutas de las páginas y su protección |
| `routes/api.php` | Rutas de la API (públicas y con token) |
| `app/Http/Controllers/SedeController.php` | CRUD de sedes |
| `app/Http/Controllers/AtraccionController.php` | CRUD de atracciones e imágenes |
| `app/Http/Controllers/DashboardController.php` | Datos del panel y la gráfica |
| `app/Http/Controllers/ReporteController.php` | Genera el PDF |
| `app/Http/Controllers/Api/` | Login, sedes y atracciones de la API |
| `app/Models/` | Modelos y relaciones |
| `app/Models/Concerns/Auditable.php` | Auditoría automática |
| `database/migrations/` | Estructura de la base de datos |
| `resources/views/layouts/` | Plantillas maestras (app y guest) |
| `resources/views/welcome.blade.php` | Landing page |
| `resources/views/auth/` | Login y registro |
| `resources/views/sedes/`, `resources/views/atracciones/` | Pantallas de los CRUD |
| `resources/views/consola-api.blade.php` | Consola API |
| `resources/views/reportes/atracciones.blade.php` | Plantilla del PDF |
| `Dockerfile`, `start.sh` | Publicación en Render |
| `.env` (solo local) | Claves y configuración; **no se sube a GitHub** |

