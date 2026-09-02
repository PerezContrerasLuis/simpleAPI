# API REST - Sistema de Categorías

API RESTful básica desarrollada en PHP puro con arquitectura MVC.

---

##  Tabla de Contenidos

- [Descripción General](#descripción-general)
- [Características](#características)
- [Tecnologías Utilizadas](#tecnologías-utilizadas)
- [Endpoints Implementados](#endpoints-implementados)
- [Estructura del Proyecto](#estructura-del-proyecto)
- [Base de Datos](#base-de-datos)
- [Configuración del Entorno](#configuración-del-entorno)
- [Instalación y Ejecución](#instalación-y-ejecución)
- [Ejemplos de Uso](#ejemplos-de-uso)
- [Docker](#docker)
- [Configuración Apache](#configuración-apache)


---

## Descripción General

API REST para la gestión de categorías con estructura jerárquica (subcategorías). Implementa los endpoints básicos de consulta (GET) siguiendo el patrón MVC, con conexión a base de datos MySQL mediante PDO y prepared statements para garantizar la seguridad.

**Propósito:** Este proyecto está diseñado como prueba técnica para evaluar conocimientos en:
- PHP Orientado a Objetos
- Patrón de diseño MVC
- Conexión segura a MySQL con PDO
- Diseño de APIs RESTful
- Buenas prácticas de programación

---

## ✨ Características

- **Arquitectura MVC** limpia y escalable
- **Router propio** sin frameworks externos
- **Conexión PDO** con prepared statements (seguridad contra SQL Injection)
- **Variables de entorno** (`.env`) para configuración
-**Manejo de excepciones** con try-catch
- **Códigos HTTP** apropiados (200, 404, 500)
- **Respuestas JSON** consistentes
- **Estructura jerárquica** de categorías (padre-hijo)
- **Información adicional** en consultas individuales:
  - Nombre de categoría padre
  - Conteo de subcategorías
- **Índices optimizados** en la base de datos
- **Extensible** para futuros endpoints (POST, PUT, DELETE)

---

##  Tecnologías Utilizadas

- [Docker](#docker)

---

##  Endpoints Implementados

| Método | Endpoint | Descripción | Códigos HTTP |
|--------|----------|-------------|--------------|
| `GET` | `/api/v1/categories` | Obtener todas las categorías | 200 OK, 500 Error |
| `GET` | `/api/v1/categories/{id}` | Obtener categoría específica por ID | 200 OK, 404 No encontrada, 500 Error |

### Formato de Respuestas

#### GET /api/v1/categories
```json
[
    {
        "id": 1,
        "name": "Electrónica",
        "slug": "electronica",
        "parent_id": null,
        "created_at": "2026-08-01 10:00:00",
        "updated_at": "2026-08-01 10:00:00"
    },
    {
        "id": 2,
        "name": "Smartphones",
        "slug": "smartphones",
        "parent_id": 1,
        "created_at": "2026-08-01 10:30:00",
        "updated_at": "2026-08-01 10:30:00"
    }
]
```

#### GET /api/v1/categories/1
```json
{
    "id": 1,
    "name": "Electrónica",
    "slug": "electronica",
    "parent_id": null,
    "parent_name": null,
    "subcategories_count": 3,
    "created_at": "2026-08-01 10:00:00",
    "updated_at": "2026-08-01 10:00:00"
}
```

#### Error 404 - Categoría no encontrada
```json
{
    "error": "Categoría no encontrada",
    "code": 404
}
```

---

## Estructura del Proyecto

```
api-categorias/
├── index.php                    # Punto de entrada - Router principal
├── .env                         # Variables de entorno
├── .env.example                 # Ejemplo de variables de entorno
├── README.md                    # Documentación del proyecto
├── docker-compose.yml           # Configuración Docker
├── Dockerfile                   # Imagen Docker personalizada
├── config/
│   └── database.php             # Configuración y conexión a BD
├── models/
│   └── Category.php             # Modelo - Lógica de negocio y consultas
├── controllers/
│   └── CategoryController.php   # Controlador - Manejo de peticiones
├── routes/
│   └── api.php                  # Definición de rutas
├── tests/
│   ├── test_categories.sh       # Script de pruebas para endpoints
│   └── test_data.sql            # Datos de prueba para la BD
└── docs/
    ├── postman_collection.json  # Colección de Postman
    └── api_documentation.md     # Documentación detallada de la API
```

### Descripción de Archivos Principales

| Archivo | Descripción |
|---------|-------------|
| **index.php** | Router principal que maneja todas las peticiones entrantes |
| **.env** | Almacena variables de entorno (configuración sensible) |
| **config/database.php** | Clase Database con patrón Singleton para conexión PDO |
| **models/Category.php** | Modelo con métodos CRUD para categorías |
| **controllers/CategoryController.php** | Controlador que procesa peticiones y devuelve JSON |
| **routes/api.php** | Definición de todas las rutas de la API |

---

## Base de Datos

### Esquema de la Tabla `categories`

```sql
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    parent_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_parent_id (parent_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Descripción de Columnas

| Columna | Tipo | Descripción |
|---------|------|-------------|
| `id` | INT | Identificador único autoincrementable |
| `name` | VARCHAR(255) | Nombre de la categoría |
| `slug` | VARCHAR(255) | URL amigable (único) |
| `parent_id` | INT | ID de la categoría padre (NULL si es raíz) |
| `created_at` | TIMESTAMP | Fecha de creación |
| `updated_at` | TIMESTAMP | Fecha de última actualización |

### Relaciones
- **Auto-relación**: `parent_id` → `id` (estructura jerárquica)
- **Eliminación**: ON DELETE SET NULL (si se elimina padre, hijos quedan huérfanos)

### Índices
- `idx_slug`: Búsqueda rápida por slug
- `idx_parent_id`: Búsqueda rápida de subcategorías

### Datos de Prueba Sugeridos
```sql
INSERT INTO categories (name, slug, parent_id) VALUES
('Electrónica', 'electronica', NULL),
('Ropa', 'ropa', NULL),
('Libros', 'libros', NULL),
('Smartphones', 'smartphones', 1),
('Laptops', 'laptops', 1),
('Tablets', 'tablets', 1),
('Camisas', 'camisas', 2),
('Pantalones', 'pantalones', 2),
('Ficción', 'ficcion', 3),
('No Ficción', 'no-ficcion', 3);
```

---

## Configuración del Entorno

### Variables de Entorno (.env)

Crea un archivo `.env` en la raíz del proyecto con la siguiente configuración:

```env
# Aplicación
APP_ENV=development
APP_NAME=ApiProject
APP_URL=http://localhost

# Base de Datos
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=api_db
DB_USERNAME=api_user
DB_PASSWORD=secret
DB_ROOT_PASSWORD=rootsecret
```

### Parámetros de Configuración

| Variable | Descripción | Valor Sugerido |
|----------|-------------|----------------|
| `APP_ENV` | Entorno de ejecución | development / production |
| `APP_NAME` | Nombre de la aplicación | ApiProject |
| `APP_URL` | URL base | http://localhost |
| `DB_HOST` | Host de la BD | mysql |
| `DB_PORT` | Puerto de la BD | 3306 |
| `DB_DATABASE` | Nombre de la BD | api_db |
| `DB_USERNAME` | Usuario de la BD | api_user |
| `DB_PASSWORD` | Contraseña de la BD | secret |
| `DB_ROOT_PASSWORD` | Contraseña root | rootsecret |

---

##  Instalación y Ejecución

### Opción 1: Sin Docker (Servidor Local)

```bash
# 1. Clonar el repositorio
git clone git@github.com:PerezContrerasLuis/simpleAPI.git
cd api-categorias

# 2. Copiar y configurar variables de entorno
cp .env.example .env
# Editar .env con tus credenciales de BD

# 3. Configurar Apache/Nginx apuntando a la raíz del proyecto

# 4. Importar la estructura de la tabla
mysql -u root -p api_db < database/schema.sql

# 5. Probar la API
curl http://localhost/api/v1/categories
```

### Opción 2: Con Docker (Recomendado)

```bash
# 1. Clonar el repositorio
git clone git@github.com:PerezContrerasLuis/simpleAPI.git
cd api-categorias
```
#### 2. Levantar los contenedores
[Docker](#docker)

#### 3. Esperar a que MySQL esté listo (unos 30 segundos)
```
# 4. Probar la API
curl http://localhost/api/v1/categories
```

---

## Ejemplos de Uso

### Listar todas las categorías
```bash
curl http://localhost/api/v1/categories
```

### Obtener categoría específica
```bash
curl http://localhost/api/v1/categories/1
```

### Probar con encabezados
```bash
curl -H "Accept: application/json" http://localhost/api/v1/categories
```

### Probar desde navegador
```
http://localhost/api/v1/categories
http://localhost/api/v1/categories/1
```

---

##  Docker

> **⚠️ IMPORTANTE:** Este proyecto fue implementado con la imagen dokcerLAPM de mattrayner/lamp 

url : https://hub.docker.com/r/mattrayner/lamp

Caracteristicas :
Component	
Apache	    2.4.29
MySQL		5.7.26
PHP	        7.3.6
phpMyAdmin	4.9.0.1

```

docker pull mattrayner/lamp
docker run -i -t --name MyLocalLAMP -p 80:80 -p 3306:3306 -v ${PWD}/app:/app -v ${PWD}/mysql:/var/lib/mysql mattrayner/lamp:latest-1804

docker start MyLocalLAMP

```


---

## 🔧 Configuración Apache

> **⚠️ NOTA:** Para que la API funcione correctamente con el router (amigable), se requieren configuraciones específicas en Apache. Los detalles completos se encuentran en la siguiente sección.

### 1. Acceder al contenedor Docker

Primero ingresamos al contenedor donde está corriendo Apache/PHP:

```bash
docker exec -it MyLocalLAMP bash
```

Una vez dentro, el prompt cambia a algo parecido a:

```
root@7520a7b94dc3:/#
```

A partir de aquí, todos los comandos se ejecutan dentro del contenedor.

---

### 2. Verificar ubicación de la aplicación

Verificamos el contenido del DocumentRoot:

```bash
ls -la /var/www/html
```

Apache tiene configurado:

```
DocumentRoot /var/www/html
```

Al verificar, descubrimos que `/var/www/html` no era un directorio físico, sino un enlace simbólico:

```bash
ls -la /var/www/html
ls -la /var/www/
```

Encontramos:

```
/var/www/html -> /app
```

Estructura:

```
Apache
   │
   ▼
/var/www/html
   │
   └──────► /app
```

Por lo tanto, aunque Apache tiene `DocumentRoot /var/www/html`, la aplicación realmente está en `/app`. Esta información resultó fundamental para solucionar la configuración de `.htaccess`.

---

### 3. Creación de .htaccess

Para utilizar URLs amigables como `/api/v1/categories` donde el archivo real de entrada es `/index.php`, necesitamos que Apache redirija internamente las peticiones a `index.php`.

Verificamos el contenido:

```bash
cat /app/.htaccess
```

El archivo debe contener:

```apache
Options -Indexes

# Pass Authorization header to PHP
<IfModule mod_setenvif.c>
    SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1
</IfModule>

<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^ index.php [QSA,L]
</IfModule>
```

La parte fundamental es:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```

Esto significa: si la URL solicitada no corresponde a un archivo real y tampoco a un directorio real, envíala a `index.php`.

Por ejemplo, `/api/v1/categories` no es un archivo ni un directorio físico, por lo que Apache redirige a `index.php`.

---

### 4. Verificar mod_rewrite

Ejecutamos:

```bash
apache2ctl -M | grep rewrite
```

El resultado debe ser:

```
rewrite_module (shared)
```

Esto confirma que Apache tiene cargado `mod_rewrite`.

---

### 5. Revisar configuración de Apache

Revisamos el VirtualHost activo:

```bash
cat /etc/apache2/sites-enabled/000-default.conf
```

Configuración inicial encontrada:

```apache
<VirtualHost *:80>
    DocumentRoot /var/www/html
    <Directory />
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Order allow,deny
        allow from all
    </Directory>
    ...
</VirtualHost>
```

También verificamos la configuración global de Apache:

```apache
<Directory /var/www/>
    Options Indexes FollowSymLinks
    AllowOverride None
    Require all granted
</Directory>
```

El punto crítico era `AllowOverride None`. Esto significa que Apache no permite que `.htaccess` modifique la configuración del directorio. Por eso nuestro `.htaccess` podía existir y estar perfectamente escrito, pero Apache no lo estaba utilizando.

---

### 6. Agregar configuración específica para /var/www/html

Agregamos dentro del VirtualHost:

```apache
<Directory /var/www/html>
    Options Indexes FollowSymLinks MultiViews
    AllowOverride All
    Require all granted
</Directory>
```

Esto le dice a Apache que para `/var/www/html`, permita que `.htaccess` pueda modificar la configuración. La parte fundamental es `AllowOverride All`.

---

### 7. Configurar el directorio real /app

Aunque ya teníamos la configuración para `/var/www/html`, al hacer:

```bash
curl -i http://localhost/prueba-rewrite
```

seguía devolviendo:

```
HTTP/1.1 404 Not Found
```

con el mensaje "The requested URL was not found on this server." de Apache.

Recordemos que `/var/www/html -> /app`. Por lo tanto, configuramos explícitamente el directorio real:

```apache
<Directory /app>
    Options Indexes FollowSymLinks MultiViews
    AllowOverride All
    Require all granted
</Directory>
```

El VirtualHost terminó teniendo ambos bloques:

```apache
<Directory /var/www/html>
    Options Indexes FollowSymLinks MultiViews
    AllowOverride All
    Require all granted
</Directory>

<Directory /app>
    Options Indexes FollowSymLinks MultiViews
    AllowOverride All
    Require all granted
</Directory>
```

Este fue el cambio que permitió finalmente que Apache procesara nuestro `.htaccess`.

---

### 8. Verificar sintaxis de Apache

Después de modificar la configuración, siempre debemos comprobar primero la sintaxis:

```bash
apache2ctl -t
```

Resultado esperado:

```
Syntax OK
```

Esto significa que Apache puede interpretar correctamente la configuración.

---

### 9. Reiniciar Apache

Después de verificar la sintaxis:

```bash
apache2ctl -t
```

reiniciamos Apache:

```bash
service apache2 restart
```

La finalidad es que Apache vuelva a cargar la configuración modificada.

También podemos comprobar que Apache está funcionando:

```bash
service apache2 status
```

**Importante:** Reiniciar el contenedor:

```bash
docker restart MyLocalLAMP
```

---

### 10. Crear prueba específica para .htaccess

Antes de probar nuestra API, necesitábamos separar dos problemas:

1. ¿Apache está procesando `.htaccess`?
2. ¿Nuestra aplicación PHP funciona correctamente?

Para eso utilizamos una URL ficticia `/prueba-rewrite`:

```
/prueba-rewrite
       ↓
.htaccess
       ↓
index.php
```

Ejecutamos:

```bash
curl -i http://localhost/prueba-rewrite
```

Antes de solucionar Apache obteníamos:

```
HTTP/1.1 404 Not Found
```

con HTML generado por Apache, lo que significaba que la petición no llegaba a `index.php`.

Después de agregar `<Directory /app>` con `AllowOverride All`, la prueba empezó a pasar por `index.php`.

Esta prueba fue especialmente útil porque permitió diagnosticar `.htaccess` sin involucrar todavía la base de datos ni los controladores.

---

### 11. Probar el endpoint final

Una vez solucionados Apache, `.htaccess` y `mod_rewrite`, hicimos la prueba:

```bash
curl -i http://localhost/api/v1/categories
```

Resultado esperado:

```
HTTP/1.1 200 OK
Content-Type: application/json

[
    {
        "id": "1",
        "name": "Electrónica",
        "slug": "electronica",
        "parent_id": null
    },
    {
        "id": "2",
        "name": "Computadoras",
        "slug": "computadoras",
        "parent_id": "1"
    }
]
```

Esto confirmó que toda la aplicación estaba funcionando correctamente.

---

### 12. Flujo final de la petición

Cuando hacemos `curl http://localhost/api/v1/categories`, ocurre el siguiente flujo:

```
┌──────────────────────────────┐
│ curl                         │
│ /api/v1/categories           │
└──────────────┬───────────────┘
               │
               ▼
┌──────────────────────────────┐
│ Apache :80                   │
│ DocumentRoot /var/www/html   │
└──────────────┬───────────────┘
               │
               ▼
       /var/www/html
               │
               │ symlink
               ▼
            /app
               │
               ▼
          .htaccess
               │
               │ mod_rewrite
               ▼
          index.php
               │
               ▼
        routes/api.php
               │
               ▼
     CategoryController
               │
               ▼
        Models/Category
               │
               ▼
           Database
               │
               ▼
        JSON Response
               │
               ▼
          HTTP 200
```

---

### 13. Checklist para reproducir la solución

Cuando montes la aplicación PHP usando la imagen `mattrayner/lamp:latest-1804`, puedes seguir este checklist:

#### Docker
```bash
docker exec -it MyLocalLAMP bash
```

#### Verificar DocumentRoot
```bash
ls -la /var/www/html
```
Comprobar si es un enlace: `/var/www/html -> /app`

#### Verificar aplicación
```bash
ls -la /app
```

#### Verificar .htaccess
```bash
cat /app/.htaccess
```
Debe existir `RewriteEngine On` y las reglas correspondientes.

#### Verificar mod_rewrite
```bash
apache2ctl -M | grep rewrite
```
Debe aparecer: `rewrite_module (shared)`

#### Verificar VirtualHost
```bash
cat /etc/apache2/sites-enabled/000-default.conf
```
Asegurarse de tener:

```apache
<Directory /var/www/html>
    Options Indexes FollowSymLinks MultiViews
    AllowOverride All
    Require all granted
</Directory>

<Directory /app>
    Options Indexes FollowSymLinks MultiViews
    AllowOverride All
    Require all granted
</Directory>
```

#### Verificar sintaxis de Apache
```bash
apache2ctl -t
```
Debe responder: `Syntax OK`

#### Reiniciar Apache
```bash
service apache2 restart
```

#### Reiniciar contenedor
```bash
docker restart MyLocalLAMP
```

#### Probar .htaccess
```bash
curl -i http://localhost/prueba-rewrite
```
La petición debe llegar a `index.php`, no producir el HTML de 404 de Apache.

#### Probar PHP
```bash
curl -i http://localhost/index.php
```
Un JSON 404 de tu aplicación demuestra que `index.php` está siendo ejecutado.

#### Validar controlador PHP
```bash
php -l /app/Controllers/CategoryController.php
```

#### Probar carga del controlador
```bash
php -r "require_once '/app/Controllers/CategoryController.php'; echo 'Controller cargado correctamente';"
```

#### Probar API
```bash
curl -i http://localhost/api/v1/categories
```

Resultado esperado:

```
HTTP/1.1 200 OK
Content-Type: application/json
```

---

## Licencia

Este proyecto está bajo la licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

---

## 👨‍💻 Autor

**Desarrollado para prueba técnica de programador PHP**  
*Junior / Intermedio*

---

## 📞 Contacto

- **Email**: luisperezcontreras@gmail.com
- **Repositorio**: https://github.com/PerezContrerasLuis

---

**Última actualización:** 2 de septiembre del 2026
