# Profiling


## LightHouse
Se ha realizado el análisis de LightHouse con las opciones de **perfomance** y **desktop** habilitadas para las siguientes páginas de la aplicación:


#### ⛱ Subir imágenes  
[http://192.168.0.126:8080/upload](http://localhost:8080/upload) 

Se realiza el análisis de performance antes de aplicar las mejoras:

* [Upload](profiling/LightHouse_Upload.html)
 
**Mejoras aplicadas**

Los archivos js que se utilizan en la página, pasan a utilizarse con una versión minificada en el caso de la clase personalizada del [customDropzone.min.js](public/assets/js/min/customDropzone.min.js) y se hace uso de CDN  con atributo async en los casos posibles.
 
* [Upload](profiling/LightHouse_Upload_mejoras.html)

  
 
#### 🌍 Galería de imágenes 
[http://192.168.0.126:8080/gallery](http://localhost:8080/gallery)
 
Se realiza el análisis de performance antes de aplicar las mejoras:

* [Galería MySQL](profiling/LightHouse_Gallery_MySql.html)
* [Galería Redis](profiling/LightHouse_Gallery_Redis.html)
 
**Mejoras aplicadas**

Se incluye en [Docker](docker/php-fpm/Dockerfile) la librería de libwebp-dev  para poder procesar las imágenes en  webp.  Se cambia el tipo imagen a procesar por [Claviska](src/ImageRegister/Infrastructure/Service/ClaviskaImageProcessing.php), cambio de imagen  de *.png  a   *.webp para que la carga de imágenes se más rápida y se elimina de las plantillas de Twig los atributos de ancho y alto en los tags de imágenes.

* [Galería MySQL](profiling/LightHouse_Gallery_MySql_mejoras.html)
* [Galería Redis](profiling/LightHouse_Gallery_Redis_mejoras.html)
  
 
   
#### 🎰 Buscador de imágenes

 [http://192.168.0.126:8080/search](http://localhost:8080/search)

Se realiza el análisis de performance antes de aplicar las mejoras:
* [Buscador](profiling/LightHouse_Search_conBusqueda.html)
 

Después de las mejoras realizadas en las plantillas de twig y en el cambio de tipo de imagen de png a webp, los resultados de lighthouse son estos:

* [Buscador](profiling/LightHouse_Search_conBusqueda_mejoras.html)



## Blackfire

 Para consultar las capturas de pantallas ver documento [BlackFire&LightHouse](profiling/BlackFire&LightHouse.docx)