# Instalación y configuración del proyecto

## Prerequisito

Para comenzar, asegúrese de tener [Docker instalado](http://www.docker.com/) en su sistema.

## Clonar el repositorio

`git clone git@github.com:erickgperez/proyecto_u.git`

Despues de clonar entrar al directorio en que se clonó el proyecto
`cd proyecto_u`

## Certificados

crear un certificado autofirmado para el servidor nginx (solo para desarrollo, para producción use un certificado real)

`mkdir ./ssl/certs`

`mkdir ./ssl/private`

`openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout ./ssl/private/localhost.key -out ./ssl/certs/localhost.crt -config ./ssl/localhost.conf`

## Copiar el archivo de variables de entorno

`cp .env.example .env`

## Configurar la conexión a la base de datos

Abrir/crear el archivo `.env` y adecuar los valores

```
DB_CONNECTION=pgsql
DB_HOST=db #para uso con docker
DB_PORT=5432
DB_DATABASE=usuariodb
DB_USERNAME=proyectodb
DB_PASSWORD=clavedb
```

## Contenedor docker

Active los contenedores para el servidor web, ejecutando
`docker compose up -d --build`

## Instalar las dependencias

`docker compose run --rm composer install`

`docker compose run --rm npm install`

## Generar la llave del proyecto

`docker compose run --rm artisan key:generate`

## Cargar la estructura de la base de datos

`docker compose run --rm artisan migrate:fresh --seed --force`

## Acceder

Entrar a <http://localhost:8080>

Producción
`docker compose run --rm vite npm run build`

## Configuración extra

Definir el nombre de la aplicación en el archivo .env

```
APP_NAME='Nombre Aplicación'
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
