# Configuración de PostgreSQL

## Requisitos

- Tener acceso a un usuario con permisos de superusuario

## Instalación

Si no se ha instalado postgresql previamente, ejecutar el siguiente comando:

`sudo apt install postgresql postgresql-contrib`

## Creación de usuario y base de datos

`sudo -u postgres psql`

```sql
CREATE USER usuariodb WITH PASSWORD 'claveusuario';
CREATE DATABASE proyectodb OWNER usuariodb;
GRANT ALL PRIVILEGES ON DATABASE proyectodb TO usuariodb;
```

## Cambiar configuración de posgres para que permita al usuario conectarse con clave localmente

Como root abrir el archivo pg_hba.conf (asumiendo versión 16 de postgresql)

`sudo nano /etc/postgresql/16/main/pg_hba.conf`

Buscar la línea

`# "local" is for Unix domain socket connections only
local   all             all                         peer`

Cambiar el final y ponerle "md5"

`local   all             all                         md5`

Reiniciar el servicio de postgres

`sudo /etc/init.d/postgresql restart`

Probar conectarse (salirse con ctrl+d)

`psql template1 -U usuariodb`

## Abrir el archivo /etc/postgresql/16/main/postgresql.conf y buscar el parámetro PORT, mirar qué valor tiene

## Configurar Laravel, abrir el archivo .env y verificar los valores de acuerdo a lo realizado en los pasos anteriores

DB_CONNECTION = pgsql
DB_HOST=127.0.0.1
DB_PORT=5433
DB_DATABASE=proyectodb
DB_USERNAME=usuariodb
DB_PASSWORD=claveusuario
