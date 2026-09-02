Aquí tienes un README.md completo y profesional para la API:

---

# API REST - Sistema de Categorías

API RESTful básica desarrollada en PHP puro con arquitectura MVC, diseñada para gestionar categorías con una estructura jerárquica (padre-hijo). Proyecto ideal para evaluar conocimientos de PHP, POO, MySQL y diseño de APIs.

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

| Tecnología | Versión | Propósito |
|------------|---------|-----------|
| **PHP** | 7.4+ | Lenguaje principal |
| **MySQL** | 5.7+ | Base de datos |
| **PDO** | - | Conexión segura a BD |
| **Docker** | 20.10+ | Contenedorización |
| **Apache** | 2.4+ | Servidor web |

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

# 2. Levantar los contenedores
---- pendiente 

# 3. Esperar a que MySQL esté listo (unos 30 segundos)

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

## 🐳 Docker

> **⚠️ IMPORTANTE:** Este proyecto está diseñado para ejecutarse con Docker. La configuración completa se detallará en la siguiente sección.

El proyecto incluye una configuración Docker lista para usar que levanta tres contenedores:



---

## 🔧 Configuración Apache

> **⚠️ NOTA:** Para que la API funcione correctamente con el router (amigable), se requieren configuraciones específicas en Apache. Los detalles completos se encuentran en la siguiente sección.

### Configuración Básica

El proyecto requiere:
1. **RewriteEngine activado** para el enrutamiento
2. **DirectoryIndex configurado** a `index.php`
3. **Archivo .htaccess** para permitir URLs amigables

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
