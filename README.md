# Ruleta

Ejercicio de la Ruleta de Apuestas.

Este proyecto fue realizado en PHP utilizando Symfony y en Javascript.

# Guia de Instalación y Montaje:

-Se debe de tener un entorno web instalado en el equipo donde incluya Apache2, PHP 7.0 y MySQL o tener instalado XAMPP (Windows) o LAMPP (Linux).

-Si se está usando Linux o Unix se deben de dar permisos 777 a las carpetas app/cache y app/logs del proyecto.

-La base de datos se anexa en la raíz del proyecto como un archivo .SQL lista para ser importada.

-Para conectar con la base de datos debemos modificar el archivo parameters.yml que se encuentra en la carpeta app/config del proyecto y modificar los datos de conexión a la BD.

NOTA: El enlace de ingreso si está montando en servidor local es http://localhost/ProyectoRuleta/app_dev.php 

# Recomendaciones

-En caso de clonar el proyecto al equipo y estar ubicado en el directorio app/config y el archivo de conexión se llame parameters.yml.dist, debemos de renombrarlo a parameters.yml y modificar los datos de conexión a la BD.

-En caso de tener problemas de cache con el proyecto, ubicarse en la raíz del proyecto y ejecutar el comando para eliminar cache que es:

    - Entorno de Desarrollo: php app/console cache:clear --env dev
    
    - Entorno de Producción: php app/console cache:clear --env prod
    
Posiblemente se necesiten permisos root para ejecutar estas acciones por lo tanto debemos de volver a dar permisos a las carpetas de cache y logs indicadas anteriormente.