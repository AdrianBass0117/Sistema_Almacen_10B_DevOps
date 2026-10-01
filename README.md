# Sistema de Almacen

Aplicación web para administrar un inventario de productos: permite consultar,
crear, actualizar y eliminar productos, guardar imágenes y consultar el
historial de cambios de precio y existencias.

## Tecnologias

- PHP 8.2 o superior y Laravel 12.
- MySQL/MariaDB para el entorno local habitual; PHPUnit usa SQLite en memoria.
- Blade y CSS para las vistas; Vite, Node.js y npm para compilar los recursos.
- Composer y npm para instalar dependencias, con archivos de bloqueo versionados.

## Requisitos previos

- PHP 8.2+ con PDO MySQL habilitado.
- Composer 2.
- MySQL o MariaDB y una base de datos vacia para el proyecto.
- Node.js compatible con Vite 7 y npm.

## Instalación local (PowerShell)

Desde la raíz del repositorio, instalar dependencias, crear la configuración
local de entorno y compilar los recursos:

```powershell
composer install
npm ci
Copy-Item .env.example .env
```

Editar `.env` para configurar la conexión local a la base de datos.

No incluir credenciales reales en `.env.example` ni subirlas al repositorio.

Generar la clave local, preparar la base de datos y compilar los recursos:

```powershell
php artisan key:generate
php artisan migrate
php artisan storage:link
npm run build
```

Iniciar la aplicación:

```powershell
composer run dev
```

El servidor de desarrollo se publica normalmente en `http://localhost:8000`.
Los archivos de imagen se guardan en el disco público de Laravel; el enlace de
almacenamiento permite servirlos desde `public/storage`.

## Pruebas y compilación

```powershell
composer test
npm run build
```

La suite actual contiene pruebas de ejemplo de PHPUnit; aun se deben agregar
pruebas automatizadas especificas para el CRUD, validaciones e historial. El
plan de pruebas y las evidencias se mantienen en la documentación interna del
equipo y no forman parte de este repositorio.

## Estructura relevante

- `app/Http/Controllers`: operaciones de productos e historial.
- `app/Models`: modelos `Producto` y `Registro`.
- `database/migrations`: esquema de productos, historial y tablas de Laravel.
- `database/seeders`: datos de prueba o iniciales, cuando se definan.
- `resources/views`: interfaz Blade del inventario y del historial.
- `routes/web.php`: rutas web.
- `tests`: pruebas PHPUnit.
- `.env.example`: plantilla sin credenciales; los valores locales viven en `.env`.

## Base de datos y migraciones

El proyecto no necesita un archivo `.sql` para crear la estructura de la base
de datos. El esquema se define mediante migraciones de Laravel en
`database/migrations`: cada migración describe los cambios de estructura y
Laravel los aplica en orden, registrando cuáles ya se ejecutaron. Esto permite
crear y actualizar la base de datos de forma versionada y consistente entre
los entornos.

Después de configurar en `.env` la conexión a una base de datos vacía, aplicar
las migraciones:

```powershell
php artisan migrate
```

Para consultar el estado de las migraciones:

```powershell
php artisan migrate:status
```

No usar ni subir volcados `.sql` con datos locales o credenciales como
sustituto de las migraciones. Para agregar datos de ejemplo, usar seeders con
datos ficticios, sin información sensible.

## Flujo de contribución

Configurar el repositorio remoto, los colaboradores y las reglas de revisión al
crear el repositorio en la nube. Usar ramas de trabajo y Pull Requests para
proponer cambios. No subir `.env`, dependencias, logs, archivos generados ni
imágenes de datos locales.
