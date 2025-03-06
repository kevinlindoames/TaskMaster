# TaskMaster - Aplicación de Gestión de Tareas

TaskMaster es una aplicación completa de gestión de tareas desarrollada con Laravel (backend) y Angular (frontend). Permite a los usuarios registrarse, iniciar sesión y administrar sus tareas personales a través de una interfaz intuitiva y moderna.

## Características principales

- **Autenticación completa**: Registro, inicio de sesión y cierre de sesión de usuarios
- **Gestión de tareas**: Crear, listar, actualizar y eliminar tareas (CRUD completo)
- **Protección de rutas**: Acceso restringido a usuarios autenticados
- **API RESTful**: Backend desarrollado con Laravel y Laravel Sanctum
- **Interfaz interactiva**: Frontend desarrollado con Angular 19
- **Dockerizado**: Configuración lista para desarrollo y producción
- **Documentación API**: Integración con Swagger para documentación automática

## Requisitos previos

- Docker y Docker Compose
- Git

## Configuración e instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/taskmaster.git
cd taskmaster
```

### 2. Iniciar los contenedores Docker

```bash
docker-compose build
docker-compose up -d
```

### 3. Configurar el backend

```bash
# Migrar la base de datos
docker-compose exec backend php artisan migrate --seed

# Generar documentación Swagger
docker-compose exec backend php artisan l5-swagger:generate
```

### 4. Acceder a la aplicación

- **Frontend**: http://localhost:4300
- **API Backend**: http://localhost:8000/api
- **Documentación API**: http://localhost:8000/api/documentation

## Desarrollo local

### Ejecutar el frontend en modo desarrollo

```bash
cd frontend
npm install
npm start
```

El frontend estará disponible en http://localhost:4200.

### Ejecutar pruebas

#### Pruebas del backend

```bash
# Crear base de datos de pruebas
docker-compose exec db mysql -u root -psecretpassword -e "CREATE DATABASE IF NOT EXISTS taskmaster_testing; GRANT ALL PRIVILEGES ON taskmaster_testing.* TO 'taskmaster'@'%';"

# Ejecutar pruebas
docker-compose exec backend php artisan test --env=testing
```

#### Pruebas del frontend

```bash
cd frontend
npm test
```

## Documentación de la API

La documentación de la API está disponible en http://localhost:8000/api/documentation.

### Endpoints principales

- **POST /api/register**: Registro de usuario
- **POST /api/login**: Inicio de sesión
- **POST /api/logout**: Cierre de sesión (requiere autenticación)
- **GET /api/tasks**: Listar tareas (requiere autenticación)
- **POST /api/tasks**: Crear tarea (requiere autenticación)
- **GET /api/tasks/{id}**: Ver detalles de una tarea (requiere autenticación)
- **PUT /api/tasks/{id}**: Actualizar tarea (requiere autenticación)
- **DELETE /api/tasks/{id}**: Eliminar tarea (requiere autenticación)

## Estructura de datos

### Usuario (User)

```json
{
  "id": 1,
  "name": "Nombre Usuario",
  "email": "usuario@ejemplo.com",
  "created_at": "2025-03-01T12:00:00Z",
  "updated_at": "2025-03-01T12:00:00Z"
}
```

### Tarea (Task)

```json
{
  "id": 1,
  "title": "Título de la tarea",
  "description": "Descripción detallada de la tarea",
  "due_date": "2025-03-15",
  "status": "pending", // "pending" o "completed"
  "user_id": 1,
  "created_at": "2025-03-01T12:00:00Z",
  "updated_at": "2025-03-01T12:00:00Z"
}
```

## Solución de problemas comunes

### Error de conexión a la base de datos

Asegúrate de que el contenedor de MySQL está en ejecución:

```bash
docker-compose ps
```

### Error "Not Found" en el frontend

Si ves la página predeterminada de Nginx en lugar de la aplicación Angular, modifica el archivo `nginx.conf` para apuntar al directorio correcto:

```
server {
    listen 80;
    server_name localhost;
    root /usr/share/nginx/html/browser;  # Asegúrate de que esta ruta sea correcta
    index index.html;
    location / {
        try_files $uri $uri/ /index.html;
    }
}
```

### Errores en las pruebas del frontend

Si encuentras errores de HttpClient, asegúrate de incluir HttpClientTestingModule en la configuración de tus pruebas:

```typescript
TestBed.configureTestingModule({
  imports: [HttpClientTestingModule],
  // ...
});
```

## Comandos útiles de Docker

```bash
# Ver logs
docker-compose logs -f

# Reiniciar contenedores
docker-compose restart

# Detener todos los contenedores
docker-compose down

# Eliminar todo (contenedores, imágenes, volúmenes)
docker-compose down --rmi all --volumes
```

## Tecnologías utilizadas

- **Backend**:

  - Laravel 10
  - PHP 8.2
  - MySQL 8.0
  - Laravel Sanctum (autenticación)
  - L5-Swagger (documentación API)

- **Frontend**:

  - Angular 19
  - TypeScript
  - Bootstrap 5
  - RxJS

- **Infraestructura**:
  - Docker
  - Nginx
  - Docker Compose

## Contribución

1. Haz un fork del repositorio
2. Crea una rama para tu característica (`git checkout -b feature/nueva-caracteristica`)
3. Realiza cambios y commit (`git commit -am 'Añadir nueva característica'`)
4. Push a la rama (`git push origin feature/nueva-caracteristica`)
5. Crea un Pull Request

## Licencia

Este proyecto está licenciado bajo la Licencia MIT - ver el archivo LICENSE para más detalles.

---

Desarrollado como parte del reto Full Stack para Agencia Gato.
