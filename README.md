# Mini CRM — Gestión de Clientes y Contactos

Prueba técnica Full Stack. Monolito Laravel 9 con componentes Vue 3 montados en Blade.

## Stack

| Capa | Tecnología |
|------|-----------|
| Backend | Laravel 9.x · PHP 8.2 |
| Frontend | Vue 3 · Composition API · Pinia |
| Base de datos | MySQL 8 |
| Autenticación | Laravel Sanctum (sesión web) |
| Estilos | Tailwind CSS 3 |
| Bundler | Vite 5 |

---

## Instalación y puesta en marcha

### Requisitos previos
- PHP 8.2+
- Composer 2.x
- Node.js 18+ y npm
- MySQL 8

### Pasos

```bash
# 1. Clonar el repositorio
git clone <url-repositorio> mini-crm
cd mini-crm

# 2. Instalar dependencias PHP
composer install

# 3. Configurar variables de entorno
cp .env.example .env
php artisan key:generate

# 4. Editar .env con tus credenciales de MySQL
#    DB_DATABASE=mini_crm
#    DB_USERNAME=root
#    DB_PASSWORD=tu_password

# 5. Crear la base de datos
mysql -u root -p -e "CREATE DATABASE mini_crm CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 6. Ejecutar migraciones y seeders
php artisan migrate --seed

# 7. Instalar dependencias JS y compilar assets
npm install
npm run build

# 8. Iniciar el servidor
php artisan serve
```

Accede a `http://localhost:8000`

### Usuario de demostración (creado por el seeder)
| Campo | Valor |
|-------|-------|
| Email | `admin@minicrm.test` |
| Contraseña | `password` |

---

## Despliegue en Railway (gratuito)

[Railway](https://railway.app) despliega el monolito con MySQL en minutos usando el `nixpacks.toml` incluido.

### Pasos

1. Crea una cuenta en [railway.app](https://railway.app) y un nuevo proyecto.
2. **Add service → Database → MySQL** (Railway configura las variables `MYSQLHOST`, `MYSQLDATABASE`, etc. automáticamente).
3. **Add service → GitHub Repo** y selecciona este repositorio.
4. En la pestaña **Variables** del servicio web, añade:

| Variable | Valor |
|----------|-------|
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | *(ejecuta `php artisan key:generate --show` localmente)* |
| `APP_URL` | `https://<tu-app>.up.railway.app` |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | `${{MySQL.MYSQLHOST}}` |
| `DB_PORT` | `${{MySQL.MYSQLPORT}}` |
| `DB_DATABASE` | `${{MySQL.MYSQLDATABASE}}` |
| `DB_USERNAME` | `${{MySQL.MYSQLUSER}}` |
| `DB_PASSWORD` | `${{MySQL.MYSQLPASSWORD}}` |
| `SESSION_DRIVER` | `cookie` |
| `CACHE_DRIVER` | `file` |
| `QUEUE_CONNECTION` | `sync` |

5. Railway detecta el `nixpacks.toml` y ejecuta el build automáticamente. Al iniciar, corre las migraciones y seeders.

---

## Decisiones técnicas y supuestos

### Arquitectura

**Patrón Repository + Service Layer**  
La lógica de acceso a datos está encapsulada en repositorios (`ClientRepository`, `ContactRepository`), y la lógica de negocio en servicios (`ClientService`, `ContactService`). Los controladores solo orquestan la entrada/salida HTTP. Esto cumple el principio de Responsabilidad Única (SRP) y facilita el testing unitario de la lógica de negocio sin depender de la base de datos.

**Inversión de Dependencias (DIP)**  
Los controladores y servicios dependen de interfaces (`ClientRepositoryInterface`, `ContactServiceInterface`), no de implementaciones concretas. El `RepositoryServiceProvider` registra los bindings. Cambiar la implementación (p. ej., a un ORM diferente) no requiere tocar la lógica de negocio.

**Componentes Vue en Blade (sin Vue Router)**  
Cada vista Blade monta un componente Vue en un `div` con `id` específico (`#client-list-app`, `#client-detail-app`, etc.). El routing lo controla Laravel. Se pasan datos del servidor al frontend mediante atributos `data-*` en el HTML (p. ej., `data-client-id`).

**Autenticación**  
Se usa la autenticación por sesión de Laravel (cookies + CSRF) para las rutas web y las rutas API que las mismas vistas consumen. No se emiten tokens Sanctum porque no hay un cliente externo; Vue consume la misma sesión de la página.

### Reglas de negocio implementadas

- **Un contacto primario por cliente**: al marcar un contacto como primario, el servicio elimina el flag `is_primary` de todos los demás contactos del cliente antes de guardarlo.
- **Propietario del registro**: cada cliente y contacto almacena el `user_id` del creador. El servicio lanza `AuthorizationException` si un usuario intenta modificar un registro que no le pertenece.
- **Soft deletes**: clientes y contactos no se borran físicamente; usan `deleted_at` para permitir auditoría futura.
- **Búsqueda**: el listado de clientes filtra por nombre, email y empresa con un scope `search` en el modelo. El filtro por estado usa un scope `byStatus`.

### Supuestos

1. Un usuario solo ve y puede modificar los clientes que él mismo creó (aislamiento por `user_id`). No se implementó un rol de "administrador" que vea todos los clientes.
2. El email es único a nivel global de clientes; el email de contacto es único por cliente (un mismo contacto puede existir en clientes distintos).
3. La paginación del listado de clientes usa 15 registros por página.
4. No se implementó verificación de email ni recuperación de contraseña (fuera del alcance de la prueba).

### Estructura de carpetas relevante

```
app/
├── Contracts/
│   ├── Repositories/   # Interfaces de repositorios
│   └── Services/       # Interfaces de servicios
├── Http/
│   ├── Controllers/
│   │   └── Auth/       # Login, Register, Logout (SRP)
│   ├── Requests/       # Form Requests con validaciones
│   └── Resources/      # API Resources (transformación de datos)
├── Models/             # Client, Contact, User
├── Providers/
│   └── RepositoryServiceProvider.php  # Binding interfaces → implementaciones
├── Repositories/       # Acceso a datos
└── Services/           # Lógica de negocio

resources/
├── js/
│   ├── axios.js        # Instancia axios con CSRF y redirect 401
│   ├── stores/         # Pinia stores (clients.js, contacts.js)
│   └── components/
│       ├── clients/    # ClientList, ClientForm, ClientDetail
│       ├── contacts/   # ContactList, ContactForm
│       └── ui/         # StatusBadge, AlertMessage, ConfirmDialog
└── views/
    ├── layouts/        # app.blade.php, guest.blade.php
    ├── auth/           # login.blade.php, register.blade.php
    └── clients/        # index, create, edit, show
```
