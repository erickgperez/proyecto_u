# Configuración de PostgreSQL

## Requisitos

- Tener el sistema base instalado con un usuario al que se le dará permisos de superusuario

## Entrar como root

Abrir una terminal y ejecutar el siguiente comando:

`sudo su -`

## Asignar permisos de superusuario

Asumiendo que el nombre del usuario es "administrador"

`usermod -aG sudo administrador`

Ya tenemos listo el usuario "administrador" ya es parte del grupo "sudo" y puede ejecutar comandos con permisos de superusuario
