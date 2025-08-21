# Sistema de Eventos CANACO

Sistema completo de gestión y registro de eventos para la Cámara de Comercio de Querétaro, desarrollado en PHP puro con arquitectura MVC.

## Características

- **Dashboard administrativo** con métricas en tiempo real
- **Gestión completa de eventos** (crear, editar, publicar, cerrar)
- **Páginas públicas de registro** para empresas e invitados
- **Sistema de roles** (SuperAdmin y Gestor de Eventos)
- **Generación de códigos QR** para validación de asistencia
- **Pre-carga de datos** basada en RFC o teléfono
- **Reportes y exportación** de listas de asistentes
- **Diseño responsivo** con Bootstrap 5

## Tecnologías

- **Backend:** PHP 7+ (sin framework)
- **Base de datos:** MySQL 5.7+
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5
- **Autenticación:** Sesiones PHP con password_hash()
- **URLs amigables:** mod_rewrite de Apache

## Instalación

### Requisitos

- Apache 2.4+ con mod_rewrite habilitado
- PHP 7.4+ con extensiones: PDO, PDO_MySQL, mbstring, openssl
- MySQL 5.7+ o MariaDB 10.2+

### Pasos de instalación

1. **Clonar o descargar el proyecto**
   ```bash
   git clone [URL_DEL_REPOSITORIO] camaradecomercio
   cd camaradecomercio
   ```

2. **Configurar la base de datos**
   - Crear una base de datos MySQL
   - Importar el archivo `database/schema.sql`
   ```sql
   mysql -u root -p < database/schema.sql
   ```

3. **Configurar la aplicación**
   - Editar `config/config.php` con las credenciales de tu base de datos:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'canaco_eventos');
   define('DB_USER', 'tu_usuario');
   define('DB_PASS', 'tu_contraseña');
   ```
   
   - Ajustar la URL base según tu instalación:
   ```php
   define('BASE_URL', '/camaradecomercio/'); // Para subdirectorio
   // O
   define('BASE_URL', '/'); // Para dominio raíz
   ```

4. **Configurar permisos**
   ```bash
   chmod 755 uploads/
   chmod 644 .htaccess
   ```

5. **Configurar Apache**
   - Asegurar que mod_rewrite esté habilitado
   - Permitir .htaccess en el directorio de instalación

### Configuración para servidor de producción

1. **Deshabilitar errores de PHP**
   ```php
   // En config/config.php
   ini_set('display_errors', 0);
   error_reporting(0);
   ```

2. **Configurar email SMTP**
   ```php
   define('MAIL_HOST', 'smtp.tu-servidor.com');
   define('MAIL_PORT', 587);
   define('MAIL_USER', 'tu-email@dominio.com');
   define('MAIL_PASS', 'tu-contraseña');
   ```

## Uso

### Usuarios por defecto

El sistema incluye usuarios de prueba:

- **SuperAdmin**
  - Email: admin@canaco.org.mx
  - Contraseña: password

- **Gestor de Eventos**
  - Email: gestor@canaco.org.mx  
  - Contraseña: password

### Funcionalidades principales

1. **Dashboard:** Métricas de eventos, asistentes y actividad
2. **Gestión de eventos:** CRUD completo con estados (borrador, publicado, cerrado, cancelado)
3. **Páginas públicas:** Registro automático con pre-carga de datos
4. **Validación QR:** Códigos únicos para cada asistente
5. **Reportes:** Exportación de listas en CSV/PDF

### URLs del sistema

- `/` - Redirección al login o dashboard
- `/login` - Página de inicio de sesión
- `/dashboard` - Panel principal administrativo
- `/eventos` - Gestión de eventos
- `/evento/[slug]` - Página pública del evento
- `/registro/empresa/[slug]` - Formulario de registro para empresas
- `/registro/invitado/[slug]` - Formulario de registro para invitados

## Estructura del proyecto

```
camaradecomercio/
├── app/
│   ├── controllers/     # Controladores MVC
│   ├── models/          # Modelos de datos
│   ├── views/           # Vistas/templates
│   ├── core/            # Clases base del sistema
│   └── helpers/         # Funciones auxiliares
├── assets/
│   ├── css/             # Hojas de estilo
│   ├── js/              # JavaScript personalizado
│   └── images/          # Imágenes del sistema
├── config/
│   └── config.php       # Configuración principal
├── database/
│   └── schema.sql       # Esquema de base de datos
├── uploads/             # Archivos subidos
├── vendor/              # Librerías externas
├── .htaccess            # Configuración Apache
├── index.php            # Punto de entrada
└── README.md            # Este archivo
```

## Desarrollo

### Arquitectura MVC

El sistema sigue el patrón Modelo-Vista-Controlador:

- **Controladores:** Manejan la lógica de negocio y peticiones HTTP
- **Modelos:** Interactúan con la base de datos (pendiente implementación)
- **Vistas:** Templates HTML con PHP embebido

### Agregar nuevas rutas

Editar `index.php` para agregar nuevas rutas:

```php
$router->add('nueva-ruta', 'NuevoController@metodo');
$router->add('ruta-con-parametro/(\d+)', 'Controller@metodo');
```

### Crear nuevos controladores

Extender de `BaseController` y colocar en `app/controllers/`:

```php
<?php
class NuevoController extends BaseController {
    public function metodo() {
        $this->requireAuth(); // Si requiere autenticación
        $this->view('vista', $datos);
    }
}
?>
```

## Solución de problemas

### Error 500 - Internal Server Error
- Verificar que mod_rewrite esté habilitado
- Revisar permisos de archivos y directorios
- Comprobar logs de Apache y PHP

### No se cargan los estilos/JS
- Verificar la configuración de BASE_URL
- Comprobar permisos de la carpeta assets/

### Error de conexión a base de datos
- Verificar credenciales en config/config.php
- Asegurar que la base de datos existe
- Comprobar que el usuario tenga permisos

## Contacto y soporte

Para soporte técnico o consultas sobre el sistema, contactar a:

- Email: desarrollo@canaco.org.mx
- Teléfono: +52 442 123 4567

## Licencia

Sistema desarrollado exclusivamente para la Cámara de Comercio de Querétaro.
Todos los derechos reservados © 2024
