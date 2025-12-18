# Instalación de composer

## Requisitos

Verificar que se tengan los siguientes paquetes instalados

```bash
sudo apt update
sudo apt install curl php-cli php-mbstring git unzip
```

## Instalación

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

## Verificar instalación

`composer --version`
