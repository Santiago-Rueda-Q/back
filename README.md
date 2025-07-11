# 📚 Laravel API - Gestión de Autores y Libros

Este repositorio corresponde al backend de una API RESTful desarrollada en Laravel para gestionar autores y sus libros con sistema de autenticación completo.

## 🔧 Requisitos del Sistema

* PHP >= 8.1
* Composer >= 2.0
* Laravel >= 10
* PGSQL >= PGAdmin4
* Node.js >= 18

---

## 🚀 Instalación y Configuración

### 1. Clonar el repositorio
```bash
git clone [<url-del-repositorio>](https://github.com/Santiago-Rueda-Q/laravel_back_libros_autores.git)
cd laravel-api-autores-libros
```

### 2. Instalar dependencias
```bash
composer install
npm i
```

### 3. Configurar ambiente
```bash
cp .env.example .env
```

### 4. Configurar base de datos
Editar el archivo `.env` con tus credenciales de base de datos:
```env
DB_CONNECTION=pgsql o mysql // esto es con postgresql puede usar mysql
DB_HOST=127.0.0.1 //su host
DB_PORT=5432
DB_DATABASE=nombre_base_datos //nombre de su base
DB_USERNAME=usuario
DB_PASSWORD=contraseña
```

### 5. Ejecutar migraciones y seeders
```bash
php artisan migrate:fresh --seed
```

### 6. Instalar Laravel Sanctum (si no está instalado)
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 7. Iniciar servidor de desarrollo
```bash
php artisan serve
```

---

## 📁 Estructura del Proyecto

### 📌 Controladores

#### **`AuthorsController`**

Ubicación: `App\Http\Controllers\AuthorsController`

* `index()` → Retorna todos los autores con sus libros relacionados.
* `store(Request $request)` → Valida y crea un nuevo autor.
* `show($id)` → Muestra un autor con sus libros.
* `update(Request $request, $id)` → Valida y actualiza un autor existente.
* `destroy($id)` → Elimina un autor (y sus libros por cascada).

#### **`BooksController`**

Ubicación: `App\Http\Controllers\BooksController`

* `index()` → Retorna todos los libros con su autor asociado.
* `store(Request $request)` → Valida y crea un nuevo libro.
* `show($id)` → Muestra un libro con su autor.
* `update(Request $request, $id)` → Valida y actualiza un libro existente.
* `destroy($id)` → Elimina un libro.
  
#### **`AuthController`**

Ubicación: `App\Http\Controllers\AuthController`

* `register(Request $request)` → Registra un nuevo usuario y retorna token.
* `login(Request $request)` → Autentica un usuario y retorna token.
* `logout(Request $request)` → Invalida el token de sesión.

---

## 🧩 Modelos Eloquent

### **`User`**

Ubicación: `App\Models\User`

```php
protected $fillable = ['name', 'email', 'password'];
protected $hidden = ['password', 'remember_token'];
protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
];
```

### **`Author`**

Ubicación: `App\Models\Author`

```php
protected $table = 'autores';
protected $fillable = ['nombre', 'email', 'biografia'];

public function libros() {
    return $this->hasMany(Book::class, 'autor_id');
}
```

### **`Book`**

Ubicación: `App\Models\Book`

```php
protected $table = 'libros';
protected $fillable = ['titulo', 'sinopsis', 'autor_id'];

public function autor() {
    return $this->belongsTo(Author::class, 'autor_id');
}
```

---

## 🗃 Migraciones de Base de Datos

### 📌 Tabla: `users`

Ubicación: `database/migrations/xxxx_xx_xx_create_users_table.php`

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
});
```

### 📌 Tabla: `password_reset_tokens`

Ubicación: `database/migrations/xxxx_xx_xx_create_password_reset_tokens_table.php`

```php
Schema::create('password_reset_tokens', function (Blueprint $table) {
    $table->string('email')->primary();
    $table->string('token');
    $table->timestamp('created_at')->nullable();
});
```

### 📌 Tabla: `failed_jobs`

Ubicación: `database/migrations/xxxx_xx_xx_create_failed_jobs_table.php`

```php
Schema::create('failed_jobs', function (Blueprint $table) {
    $table->id();
    $table->string('uuid')->unique();
    $table->text('connection');
    $table->text('queue');
    $table->longText('payload');
    $table->longText('exception');
    $table->timestamp('failed_at')->useCurrent();
});
```

### 📌 Tabla: `personal_access_tokens`

Ubicación: `database/migrations/xxxx_xx_xx_create_personal_access_tokens_table.php`

```php
Schema::create('personal_access_tokens', function (Blueprint $table) {
    $table->id();
    $table->morphs('tokenable');
    $table->string('name');
    $table->string('token', 64)->unique();
    $table->text('abilities')->nullable();
    $table->timestamp('last_used_at')->nullable();
    $table->timestamp('expires_at')->nullable();
    $table->timestamps();
});
```

### 📌 Tabla: `autores`

Ubicación: `database/migrations/xxxx_xx_xx_create_autores_table.php`

```php
Schema::create('autores', function (Blueprint $table) {
    $table->id();
    $table->string('nombre');
    $table->string('email')->unique();
    $table->text('biografia')->nullable();
    $table->timestamps();
});
```

### 📌 Tabla: `libros`

Ubicación: `database/migrations/xxxx_xx_xx_create_libros_table.php`

```php
Schema::create('libros', function (Blueprint $table) {
    $table->id();
    $table->string('titulo');
    $table->text('sinopsis')->nullable();
    $table->unsignedBigInteger('autor_id');
    $table->timestamps();

    $table->foreign('autor_id')
        ->references('id')
        ->on('autores')
        ->onDelete('cascade');
});
```

---

## 🔗 Relaciones

* **Un usuario puede autenticarse y gestionar el sistema.**
* **Un autor puede tener muchos libros.**
* **Cada libro pertenece a un autor.**
* La eliminación de un autor conlleva la eliminación de todos sus libros por `ON DELETE CASCADE`.

---

## 📬 Rutas API

Estas rutas están definidas en `routes/api.php` y permiten interactuar con los controladores del sistema.

### 🔒 Autenticación

* `POST /api/register` → Registrar nuevo usuario
* `POST /api/login` → Iniciar sesión
* `POST /api/logout` → Cerrar sesión (requiere autenticación Sanctum)

### 🔹 Autores

* `GET /api/authors` → Obtener todos los autores
* `POST /api/authors/store` → Crear un nuevo autor
* `GET /api/authors/{id}` → Mostrar un autor por ID
* `PUT /api/authors/update/{id}` → Actualizar un autor existente
* `DELETE /api/authors/delete/{id}` → Eliminar un autor por ID

### 🔹 Libros

* `GET /api/books` → Obtener todos los libros
* `POST /api/books/store` → Crear un nuevo libro
* `GET /api/books/{id}` → Mostrar un libro por ID
* `PUT /api/books/{id}` → Actualizar un libro por ID
* `DELETE /api/books/{id}` → Eliminar un libro por ID

---

## 🌱 Seeders y Factories

Laravel utiliza *Factories* y *Seeders* para poblar la base de datos de prueba con datos realistas.

### 🔧 Factories

#### `AuthorFactory`

Ubicación: `database/factories/AuthorFactory.php`

```php
public function definition(): array
{
    return [
        'nombre' => $this->faker->name(),
        'email' => $this->faker->unique()->safeEmail(),
        'biografia' => $this->faker->paragraph(),
    ];
}
```

#### `BookFactory`

Ubicación: `database/factories/BookFactory.php`

```php
public function definition(): array
{
    return [
        'titulo' => $this->faker->sentence(),
        'sinopsis' => $this->faker->paragraph(),
        'autor_id' => Author::inRandomOrder()->first()->id,
    ];
}
```

### 🌱 Seeders

#### `UserSeeder`

```php
public function run(): void
{
    User::factory()->count(5)->create();
}
```

#### `AuthorSeeder`

```php
public function run(): void
{
    Author::factory()->count(10)->create();
}
```

#### `BookSeeder`

```php
public function run(): void
{
    Book::factory()->count(20)->create();
}
```

#### `DatabaseSeeder`

```php
public function run(): void
{
    $this->call([
        UserSeeder::class,
        AuthorSeeder::class,
        BookSeeder::class,
    ]);
}
```

---

## 🔮 Pruebas Automatizadas

### 📁 Estructura de Tests

```
└── 📁tests
    └── 📁Feature
        └── AuthorTest.php
        └── AuthTest.php
        └── BookTest.php
    └── 📁Unit
        └── AuthorTest.php
        └── BookTest.php
        └── ExampleTest.php
    └── CreatesApplication.php
    └── TestCase.php
```

### 🔹 Feature Tests

Pruebas de integración que verifican el funcionamiento completo de los endpoints:

* **AuthorTest.php** → Pruebas CRUD completas para autores
* **AuthTest.php** → Pruebas de registro, login y logout
* **BookTest.php** → Pruebas CRUD completas para libros

### 🔹 Unit Tests

Pruebas unitarias que verifican componentes individuales:

* **AuthorTest.php** → Validación de restricciones en modelo Author
* **BookTest.php** → Validación de restricciones en modelo Book

### 🧪 Comandos para Ejecutar Pruebas

```bash
# Ejecutar todas las pruebas
php artisan test

# Ejecutar pruebas con cobertura detallada
php artisan test --coverage

# Ejecutar solo pruebas unitarias
php artisan test --testsuite=Unit

# Ejecutar solo pruebas de característica
php artisan test --testsuite=Feature

# Ejecutar una prueba específica
php artisan test --filter AuthorTest

# Ejecutar pruebas en paralelo (más rápido)
php artisan test --parallel

# Crear una nueva prueba unitaria
php artisan make:test NombrePruebaTest --unit

# Crear una nueva prueba de característica
php artisan make:test NombrePruebaTest

# Ejecutar pruebas con información detallada
php artisan test --verbose
```

---

## ▶️ Comandos Útiles de Desarrollo

### 🗃 Base de Datos

```bash
# Ejecutar migraciones
php artisan migrate

# Ejecutar migraciones con datos de prueba
php artisan migrate --seed

# Revertir la última migración
php artisan migrate:rollback

# Revertir varias migraciones
php artisan migrate:rollback --step=3

# Reiniciar base de datos completamente
php artisan migrate:fresh --seed

# Ver estado de migraciones
php artisan migrate:status

# Crear nueva migración
php artisan make:migration create_nombre_tabla

# Crear modelo con migración y factory
php artisan make:model NombreModelo -mf
```

### 🏭 Factories y Seeders

```bash
# Crear factory
php artisan make:factory NombreFactory

# Crear seeder
php artisan make:seeder NombreSeeder

# Ejecutar seeders específicos
php artisan db:seed --class=NombreSeeder

# Ejecutar todos los seeders
php artisan db:seed
```

### 🎛 Controladores y Rutas

```bash
# Crear controlador
php artisan make:controller NombreController

# Crear controlador con recursos
php artisan make:controller NombreController --resource

# Crear controlador API
php artisan make:controller Api/NombreController --api

# Ver todas las rutas
php artisan route:list

# Ver rutas API específicamente
php artisan route:list --path=api

# Limpiar caché de rutas
php artisan route:clear
```

### 🔧 Optimización y Caché

```bash
# Limpiar todos los cachés
php artisan optimize:clear

# Crear caché de configuración
php artisan config:cache

# Limpiar caché de configuración
php artisan config:clear

# Crear caché de rutas
php artisan route:cache

# Crear caché de vistas
php artisan view:cache

# Limpiar caché de vistas
php artisan view:clear
```

### 🔑 Sanctum y Autenticación

```bash
# Instalar Sanctum
composer require laravel/sanctum

# Publicar configuración de Sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Crear tokens de acceso personal
php artisan sanctum:prune-expired
```

### 🧹 Mantenimiento

```bash
# Limpiar logs
php artisan log:clear

# Ver información del sistema
php artisan about
```

---

## 📊 Ejemplo de Uso de la API

### Autenticación

```bash
# Registrar usuario
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Juan Pérez","email":"juan@example.com","password":"password123","password_confirmation":"password123"}'

# Iniciar sesión
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"juan@example.com","password":"password123"}'
```

### Gestión de Autores

```bash
# Crear autor (requiere token)
curl -X POST http://localhost:8000/api/authors/store \
  -H "Authorization: Bearer tu_token_aqui" \
  -H "Content-Type: application/json" \
  -d '{"nombre":"Gabriel García Márquez","email":"gabo@example.com","biografia":"Escritor colombiano"}'

# Obtener todos los autores
curl -X GET http://localhost:8000/api/authors \
  -H "Authorization: Bearer tu_token_aqui"
```

---

## 🛡️ Seguridad

* Autenticación mediante Laravel Sanctum
* Validación de datos en todas las peticiones
* Protección CSRF habilitada
* Sanitización de inputs
* Rate limiting en rutas API

---

## 📝 Logs y Debugging

```bash
# Ver logs en tiempo real
tail -f storage/logs/laravel.log

# Activar modo debug (solo desarrollo)
# En .env: APP_DEBUG=true

# Generar log personalizado
Log::info('Mensaje de información');
Log::error('Mensaje de error');
```

---

---

## ✍️ Autor

**Santiago Rueda Quintero** - Backend API para gestión de libros y autores con sistema de autenticación completo.


---

