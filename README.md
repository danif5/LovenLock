FLOR PROJECT
======

Proyecto Flor

Instalacion
------------

Si usted tiene todo el codigo localmente y todas las librerias descargadas, salte hasta el paso 7.

1. Primero clone este repositorio (git clone https://[su_usuario]@bitbucket.org/nivalwebteam/proyectoflor.git)
2. En la ruta del projecto haga checkout a la rama develop "git checkout develop"
3. Copie app/config/parameters.yml.template a app/config/parameters.yml
4. Edite app/config/parameters.yml de acuerdo con sus configuraciones locales
5. Ejecute "composer install" en la ruta del proyecto
6. Espere unos minutos (horas en conexiones lentas) para que se descargue todo lo que se necesita.
7. Cree la base de datos (php app\console doctrine:database:create).
8. Cree el esquema (php app\console doctrine:schema:update).
9. Adiciones datos de prueba (php app\console doctrine:fixtures:load)
10. Ejecute "php app\console server:run" en la ruta del proyecto
12. Abra un navegador http://locahost:8000/admin/ y autentiquese con usuario root, password root