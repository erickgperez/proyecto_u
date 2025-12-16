# Instalación y configuración del proyecto

## Instalación de código fuente

### Clonar el repositorio

`git clone https://github.com/erickgperez/proyecto_u.git`

Despues de clonar entrar al directorio en que se clonó el proyecto
`cd proyectou`

### Instalar dependencias php

`composer install`

### Instalar dependencias js

`npm install && npm run build`

## Configuración inicial

### Copiar el archivo de variables de entorno

`cp .env.example .env`

### Generar la llave del proyecto

`php artisan key:generate`

### Configurar la conexión a la base de datos

Abrir el archivo `.env` y adecuar los valores

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=usuariodb
DB_USERNAME=proyectodb
DB_PASSWORD=clavedb
```

### Cargar la estructura de la base de datos

`php artisan migrate:fresh --seed --force`

### Iniciar el servidor de desarrollo

`composer run dev`

Cargar el sitio en el navegador, verificar el puerto en que se ejecutó, por ejemplo: <http://127.0.0.1:8000>

## Configuración extra

Definir el nombre de la aplicación en el archivo .env

```
APP_NAME='Nombre Aplicación'
```

En el archivo .env, adecuar las siguientes variables de entorno con su correspondiente valor

```
APP_URL=http://localhost
APP_LOCALE=es

```

Configurar los parámetros para el envío de correos

```
MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="info@sistec.edu.sv"
MAIL_FROM_NAME="${APP_NAME}"
```

Verificar que se están leyendo los trabajos de las colas, se recomienda configurar alguna utilidad
como `supervisor` para que verifique que siempre se estén leyendo las colas

```
php artisan queue:work
```

Verificar en la configuración de PHP php.ini, que los valores de upload_max_filesize y post_max_size no sean muy bajos. Se recomiendan valores de 20MB.
