# 🎥 👀 Práctica Registro de Imágenes

## Índice

* [🎉 Introducción](#-introduccion)
* [🐳 Puesta en marcha](#-inicializacion)
* [📊 Infraestructura](#-infraestructura)
* [💻 Casos de uso](#-casos-de-uso)
    * [⛱ Subir imágenes](#-subir-imagenes)
    * [🔗 Procesar imágenes](#-procesar-imagenes)
    * [🏪 Guardar post](#-guardar-post)
    * [🌍 Galería de imágenes](#-galeria-imagenes)
    * [🎰 Buscar](#-buscar)
* [🚀 Arquitectura](#-arquitectura)
* [🤔 Consideraciones](#-consideraciones)

## 🎉 Introducción

Práctica final de Rendimiento en Aplicaciones Web, esta práctica consiste en crear una aplicación para el almacenamiento, visualización y tratamiento de imágenes.

Esta práctica tiene como base el repositorio de [MPWAR-Rendimiento-Docker](https://github.com/rubencougil/mpwar-rendimiento-docker) y [Symfony Skeleton](https://symfony.com/doc/4.1/setup.html), se utilizan las herramientas como: 
 Blackfire, Redis, RabbitMq, Elastic Search y Kibana, además de las librerías como [Dropzone](https://www.dropzonejs.com/) y [Claviska Simple Image](https://packagist.org/packages/claviska/simpleimage) para el desarrollo de algunas funcionalidades. 

Para más info ver [composer](composer.json)

## 🐳 Puesta en marcha

Previamente se ha de tener instalado [Docker](https://www.docker.com/get-started)

Para inicializar el proyecto se ha de levantar el entorno siguiendo los siguientes pasos:

    * Para levantar el servidor y todos los servicios   
        sudo docker-compose up -d
    
    * Para entrar al contenedor de PHP
        sudo docker exec -it mpwarrendimientoweb_php_1 bash
    
    * Para entrar al contenedor de RabbitMq
        sudo docker exec -it mpwarrendimientoweb_rabbitmq_1 bash
        
    * Para entrar al contenedor de Redis
        sudo docker exec -it mpwarrendimientoweb_redis_1 bash
        redis-cli
    
        

Una vez se haya levantado el entorno es necesario ejecutar el script de nuestro esquema de datos: 
    [ImageRegister.sql](docker/db/ImageRegister.sql)   

## 📊 Infraestructura

Los ficheros de configuración de los contenedores, se encuentran en:
 
 * PHP, MySql y Nginx
 
    ```scala
    $ tree docker -d  var/mysql -d  etc/php -d    
    src
    .
   docker
   ├── db
   ├── nginx
   └── php-fpm
   var/mysql   
   ├── mysql
   └── performance_schema
   etc/php
   └── php.ini
    ```
 
## 💻 Casos de uso 

### ⛱ Subir imágenes  

El formulario para la [subida de imágenes](http://localhost:8080/upload) espera un tag, una descripción y una o más imágenes para subirlas al servidor y las guarda en la [carpeta de Upload](public/assets/uploads). 

### 🔗 Procesar imágenes

De la subida de imágenes se realiza unas transformaciones de manera concurrente con [RabbitMq](http://localhost:15672/), el [productor](src/Shared/Infrastructure/RabbitMq/RabbitMqProducer.php) 
envia mensajes al [consumidor](src/Shared/Infrastructure/RabbitMq/RabbitMqConsumer.php) para que este se encargue de llamar a [Claviska](src/ImageRegister/Infrastructure/Service/ClaviskaImageProcessing.php)
para que realice el procesado de filtros:

    * Sepia
    * Blanco y negro
    * Cítrico
    * Imagen con bordes en negro e 
    * Imagen invertida  
 
 y las guarde en la carpeta de [imágenes transformadas](public/assets/img).

### 🏪 Guardar post

La información de nuestro agregado [registro de imágenes](src/ImageRegister/Domain/Model/Aggregate/ImageRegister.php) (imagen original, sus transformaciones, tag y descripción) la guardamos en [MySql](src/ImageRegister/Infrastructure/Persistence/Repository/MySQLImageRegisterRepository.php).

### 🌍 Galería de imágenes

En la [Galería de imágenes](http://localhost:8080/gallery) podemos ver todas las imágenes, sus transformaciones y la información de cada uno de ellas que se han subido a nuestra web. En nuestro caso de uso
de [Ver imágenes](src/ImageRegister/Application/ViewImage/ViewImage.php) se utiliza [Redis](src/ImageRegister/Infrastructure/Persistence/Repository/RedisCacheRepository.php) y [MySql](src/ImageRegister/Infrastructure/Persistence/Repository/MySQLImageRegisterRepository.php). 
    
### 🎰 Buscar


### Mapa Web

Estos son los diferentes enlaces que hay definidos en nuestra aplicación web:  

 * ⛱ Subir imágenes  [http://localhost:8080/upload](http://localhost:8080/upload) 
 * 🌍 Galería de imágenes [http://localhost:8080/gallery](http://localhost:8080/gallery)

## 🚀 Arquitectura
Esta practica sigue el patrón de Arquitectura Hexagonal, para ello se ha estructurado de la siguiente  manera:

```scala
$ tree

src
.
├── ImageRegister
│   ├── Application
│   │   ├── ImageProcessed
│   │   │   ├── ImageProcessed.php
│   │   │   └── ImageProcessedRequest.php
│   │   ├── SaveImage
│   │   │   ├── SaveImage.php
│   │   │   └── SaveImageRequest.php
│   │   └── ViewImage
│   │       └── ViewImage.php
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
│       │   ├── GalleryImagesController.php
│       │   └── uploadImagesController.php
│       ├── Form
│       │   └── ImageType.php
│       ├── Persistence
│       │   ├── MysqlDatabase.php
│       │   ├── RedisCache.php
│       │   └── Repository
│       │       ├── MySQLImageRegisterRepository.php
│       │       └── RedisCacheRepository.php
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
         
