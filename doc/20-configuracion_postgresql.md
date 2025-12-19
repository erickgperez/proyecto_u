# Configuración de PostgreSQL

## Requisitos

- Tener acceso a un usuario con permisos de superusuario

## Instalación

Si no se ha instalado postgresql previamente, ejecutar el siguiente comando:

`sudo apt install postgresql`

## Creación de usuario y base de datos

`sudo -u postgres psql`

De ejemplo utilizaremos:
nombre de base de datos: proyectodb
nombre de usuario: usuariodb
contraseña: claveusuario

```sql
CREATE USER usuariodb WITH PASSWORD 'claveusuario';
CREATE DATABASE proyectodb OWNER usuariodb;
GRANT ALL PRIVILEGES ON DATABASE proyectodb TO usuariodb;
```

Salir de psql con ctrl+d

## Cambiar configuración de posgres para que permita al usuario conectarse con clave localmente

Abrir el archivo pg_hba.conf (asumiendo versión 16 de postgresql)

`sudo nano /etc/postgresql/16/main/pg_hba.conf`

Buscar la línea

```sql
# "local" is for Unix domain socket connections only
local   all             all                         peer
```

Cambiar el final y ponerle "scram-sha-256"

```sql
local   all             all                         scram-sha-256
```

Reiniciar el servicio de postgres

`sudo systemctl restart postgresql`

Probar conectarse (salirse con ctrl+d)

`psql proyectodb -U usuariodb`

Saldrá una pantalla de contraseña, ingresar la contraseña del usuario. Una vez dentro, puede salir presionando ctrl+d

## Verificar el puerto de postgresql

Abrir el archivo /etc/postgresql/16/main/postgresql.conf (cambiar la versión según corresponda, en este ejemplo es 16) y buscar el parámetro PORT, mirar qué valor tiene

## Configurar variables de entorno

Abrir el archivo .env y verificar los valores de acuerdo a lo realizado en los pasos anteriores

```env
DB_CONNECTION = pgsql
DB_HOST=127.0.0.1
DB_PORT=5433
DB_DATABASE=proyectodb
DB_USERNAME=usuariodb
DB_PASSWORD=claveusuario
```
