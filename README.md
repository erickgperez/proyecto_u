# Instalación y configuración del proyecto

## Instalación de código fuente

### Clonar el repositorio

`git clone git@github.com:erickgperez/proyectou.git`

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
`DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=usuariodb
DB_USERNAME=proyectodb
DB_PASSWORD=clavedb`

### Cargar la estructura de la base de datos

`php artisan migrate`

### Iniciar el servidor de desarrollo

`composer run dev`

Cargar el sitio en el navegador, verificar el puerto en que se ejecutó, por ejemplo: <http://127.0.0.1:8000>
