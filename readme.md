#Pour lancer le conteneur :
`docker-compose up -d`

#Pour arrêter les conteneurs :
`docker-compose down`

#Pour supprimer tous les conteneurs :
`docker system prune -a`

#Configurer son Vhost en local :

Taper:`sudo nano /etc/hosts` et saisir le texte suivant : `127.0.0.1 blog.localhost`

Pour accéder à phpMyAdmin :

http://localhost:8080/

Accéder à votre site en tapant l'URl suivante : http://blog.localhost/

composer dump-autoload
