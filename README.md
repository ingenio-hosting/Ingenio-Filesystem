## LIBRERIA FILESYSTEM EN PHP Y JAVASCRIPT
##### CREATE BY INGENIO HOSTING
##### TONI DOMENECH & MARC SANCHEZ
### VERSION 1.0

#### DOCUMENTACIÓN
##### 1-INSTALCIÓN
```sh
composer require ingenio/filesystem:dev-principal
```
##### 2-CONFIGURACIÓN
###### Para poder utilizar la clase:

```php
<?php
 use Php\Filesystem\Filesystem\Filesystem;
 $files = new Filesystem();
?>
```

## Incluir CSS y JS en tu proyecto

Para utilizar los estilos y scripts de esta librería, incluye lo siguiente en tu archivo HTML:

```html
<link rel="stylesheet" href="vendor/ingenio/filesystem/assets/css/filesystem.css">
<script src="vendor/ingenio/filesystem/assets/js/filesystem.js"></script>

```


### Conclusión:
Si tu librería requiere estilos y scripts esenciales para funcionar correctamente, el método 1 (publicación de assets) es la mejor opción. Si los CSS/JS son opcionales o personalizables, el método 2 puede ser más flexible, permitiendo a los usuarios gestionar su inclusión como prefieran.

