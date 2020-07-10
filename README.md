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
    * [🎰 Buscador de imágenes](#-buscador-imagenes)
* [👀 Mapa Web](#mapa-web)    
* [🚀 Arquitectura](#-arquitectura)
* [🤔 Consideraciones](#-consideraciones)

## 🎉 Introducción

Práctica final de Rendimiento en Aplicaciones Web, esta práctica consiste en una aplicación web de almacenamiento, visualización y tratamiento de imágenes de tipo **PNG.**

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

*   Para crear la tabla de **imageRegister** en MySQL  [ImageRegister.sql](docker/db/ImageRegister.sql)
        

        
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

El formulario para la [subida de imágenes](src/ImageRegister/Infrastructure/Controller/uploadImagesController.php) espera un tag, una descripción y una o más imágenes para subirlas al servidor y las guarda en la [carpeta de Upload](public/assets/uploads). 

### 🔗 Procesar imágenes

De la subida de imágenes se realiza unas [transformaciones](src/ImageRegister/Application/ImageProcessed/ImageProcessed.php) de manera concurrente con [RabbitMq](http://localhost:15672/), el [productor](src/Shared/Infrastructure/RabbitMq/RabbitMqProducer.php) 
envia mensajes al [consumidor](src/Shared/Infrastructure/RabbitMq/RabbitMqConsumer.php) para que este se encargue de llamar a [Claviska](src/ImageRegister/Infrastructure/Service/ClaviskaImageProcessing.php)
para que realice el procesado de filtros:

    * Sepia
    * Blanco y negro
    * Cítrico
    * Imagen con bordes en negro  
    * Imagen invertida  
  
 y las guarde en la carpeta de [imágenes transformadas](public/assets/img).
 
 Para que el [consumidor](src/Shared/Infrastructure/RabbitMq/RabbitMqConsumer.php) pueda recepcionar los mensajes  y así se realice la transformación, se ha de seguir los siguientes pasos:
 
    * Entrar al contenedor de PHP
        sudo docker exec -it mpwarrendimientoweb_php_1 bash
        
    * Ejecutar el siguiente comando de bin/console de Symfony
        bin/console rabbitmq:consumer image_processing
        
        
### 🏪 Guardar post

La información de nuestro agregado [registro de imágenes](src/ImageRegister/Domain/Model/Aggregate/ImageRegister.php) (imagen original, sus transformaciones, tags y descripción) la guardamos en [MySql](src/ImageRegister/Infrastructure/Persistence/Repository/MySQLImageRegisterRepository.php)
y en [ElasticSearch](src/ImageRegister/Infrastructure/Persistence/Repository/ElasticSearchRepository.php), de tal manera la aplicación guardará por cada post 6 imágenes, con los valores de filtros de:

    * sin filtro (refiérese a la imagen original)
    * sepia
    * blanco y negro
    * citrico
    * con bordes  
    * invertida  
    
### 🌍 Galería de imágenes

En la [Galería de imágenes](src/ImageRegister/Infrastructure/Controller/GalleryImagesController.php) podemos ver todas las imágenes, sus transformaciones y la información de cada uno de ellas. En nuestro caso de uso de [Ver imágenes](src/ImageRegister/Application/ViewImage/ViewImage.php)  visualizamos la información en [Redis](src/ImageRegister/Infrastructure/Persistence/Repository/RedisCacheRepository.php) previamente se haya cargado de [MySql](src/ImageRegister/Infrastructure/Persistence/Repository/MySQLImageRegisterRepository.php).

Para consultar la información almacenada en Redis, se ha de seguir los siguientes pasos: 

    * Para entrar al contenedor de Redis
        sudo docker exec -it mpwarrendimientoweb_redis_1 bash
        redis-cli
        get images
    
### 🎰 Buscador de imágenes

Para consultar la información almacenada en la aplicación vamos al [buscador](rc/ImageRegister/Infrastructure/Controller/SearchImagesCcontroller.php) que tiene como motor de búsqueda [ElasticSearch](http://localhost:9200/)
con frontal gráfico de [Kibana](http://localhost:5601/app/kibana). 

*   Para visualizar los datos en Kibana se ha de importar el index pattern **images** [Image.json](docker/db/images.json)


## 👀 Mapa Web
Estos son los diferentes enlaces que hay definidos en nuestra aplicación web:  

 * ⛱ Subir imágenes  [http://localhost:8080/upload](http://localhost:8080/upload) 
 * 🌍 Galería de imágenes [http://localhost:8080/gallery](http://localhost:8080/gallery)
 * 🎰 Buscador de imágenes [http://localhost:8080/search](http://localhost:8080/search)



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
│   │   ├── SearchImage
│   │   │   ├── SearchImage.php
│   │   │   └── SearchImageRequest.php
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
│       │   ├── SearchImagesController.php
│       │   └── uploadImagesController.php
│       ├── Form
│       │   └── ImageType.php
│       ├── Persistence
│       │   ├── ElasticSearch.php
│       │   ├── MysqlDatabase.php
│       │   ├── RedisCache.php
│       │   └── Repository
│       │       ├── ElasticSearchRepository.php
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

*  Se ha utilizado:
    *   [Symfony 4.4.10](https://symfony.com/doc/4.4/index.html)
    *   [ElasticSearch 6.8](https://www.elastic.co/guide/en/elastic-stack/6.8/elastic-stack.html) 
        
