# 👀 Práctica Registro de Imágenes

## Índice

* [🎉 Introducción](#-introduccion)
* [🔗 Puesta en marcha](#-inicializacion)
* [📊 Infraestructura](#-infraestructura)
* [💻 Casos de uso](#-casos-de-uso)
    * [⛱ Subir imágenes](#-subir-imagenes)
    * [🔗 Procesar](#-procesar)
    * [🏪 Guardar](#-guardar)
    * [🎰 Buscar](#-buscar)
* [🚀 Arquitectura](#-arquitectura)
* [🤔 Consideraciones](#-consideraciones)

## 🎉 Introducción

Práctica final de Rendimiento en Aplicaciones Web, esta práctica consiste en crear una aplicación para el almacenamiento, visualización y tratamiento de imágenes.

Esta práctica tiene como base el repositorio de [MPWAR-Rendimiento-Docker](https://github.com/rubencougil/mpwar-rendimiento-docker) y [Symfony Skeleton](https://symfony.com/doc/4.1/setup.html), se utilizan las herramientas como: 
 Blackfire, Redis, RabbitMq, Elastic Search y Kibana, además de las librerías como [Dropzone](https://www.dropzonejs.com/) y [Claviska Simple Image](https://packagist.org/packages/claviska/simpleimage) para el desarrollo de algunas funcionalidades. 

Para más info ver [composer](composer.json)

## 🔗 Puesta en marcha

Previamente se ha de tener instalado [Docker](https://www.docker.com/get-started)

Para inicializar el proyecto se ha de levantar el entorno siguiendo los siguientes pasos:

    * Para levantar el servidor y todos los servicios   
    sudo docker-compose up -d
    
    * Para entrar al contenedor de PHP
    sudo docker exec -it mpwarrendimientoweb_php_1  bash

Una vez se haya levantado el entorno es necesario ejecutar el script de nuestro esquema de datos: 
    [ImageRegister.sql](var/mysql/db/ImageRegister.sql)   

## 📊 Infraestructura

Los ficheros de configuración de los contenedores, se encuentran en:
 
 * PHP, MySql y Nginx
 
    ```scala
    $ tree docker -d  var/mysql -d  etc/php -d    
    src
    .
   docker
   ├── nginx
   └── php-fpm
   var/mysql
   ├── db
   ├── mysql
   └── performance_schema
   etc/php
   └── php.ini
    ```
 
## 💻 Casos de uso 

### ⛱️Subir imágenes

El formulario para la [subida de imágenes](http://localhost:8080/upload) espera un tag, una descripción y una o más imágenes para subirlas al servidor y las guarda en la [carpeta de Upload](public/assets/uploads). 

### 🔗 Procesar

De la subida de imágenes se realiza unas transformaciones de manera concurrente con [RabbitMq](http://localhost:15672/), el [productor](src/Shared/Infrastructure/RabbitMq/RabbitMqProducer.php) 
envia mensajes al [consumidor](src/Shared/Infrastructure/RabbitMq/RabbitMqConsumer.php) para que este se encargue de llamar a [Claviska](src/ImageRegister/Infrastructure/Service/ClaviskaImageProcessing.php)
para que realice el procesado de filtros: sepia, blanco y negro, oscurecido verde, imagen invertida e imagen con bordes en negro y las guarde en la carpeta de [imágenes transformadas](public/assets/img).

### 🏪 Guardar

### 🎰 Buscar


## 🚀 Arquitectura
Esta practica sigue el patrón de Arquitectura Hexagonal, para ello se ha estructurado de la siguiente  manera:

```scala
$ tree

src
.
├── ImageRegister
│   ├── Application
│   │   └── ImageProcessed
│   │       ├── ImageProcessed.php
│   │       └── ImageProcessedRequest.php
│   ├── Domain
│   │   ├── ImageRegisterRepository.php
│   │   ├── Model
│   │   │   ├── Aggregate
│   │   │   │   └── ImageRegister.php
│   │   │   └── ValueObject
│   │   │       ├── ImageId.php
│   │   │       └── Image.php
│   │   └── Service
│   │       └── ImageProcess.php
│   └── Infrastructure
│       ├── Controller
│       │   └── uploadImagesController.php
│       ├── Form
│       │   └── ImageType.php
│       ├── Persistence
│       │   ├── MysqlDatabase.php
│       │   └── Repository
│       └── Service
│           └── ClaviskaImageProcessing.php
├── Kernel.php
└── Shared
    └── Infrastructure
        └── RabbitMq
            ├── RabbitMqConsumer.php
            └── RabbitMqProducer.php
```

## 🤔 Consideraciones

*  Se ha utilizado [Symfony 4.4.10](https://symfony.com/doc/4.4/index.html) 
         
