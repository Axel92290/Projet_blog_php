# Installation en Local pour Projet PHP/HTML/CSS

Ce guide vous aidera à mettre en place ce projet PHP/HTML/CSS sur votre ordinateur en utilisant un environnement de développement local. Assurez-vous de suivre ces étapes attentivement.

## Prérequis

Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre système :

1. [Composer](https://getcomposer.org/download/)

## Étapes d'Installation

Suivez ces étapes pour installer le projet localement :

1. **Cloner le Répertoire**

   Clonez ce dépôt GitHub sur votre ordinateur en utilisant la commande suivante dans votre terminal :


   git clone https://github.com/Axel92290/Projet_blog_php
   cd Projet_blog_php

   
2. **Mettre à Jour Composer :**

Avant d'installer les dépendances, assurez-vous de mettre à jour Composer en utilisant la commande suivante :


composer self-update
Installer les Dépendances

3. **Installez les dépendances PHP du projet en utilisant Composer :**


composer install

4. **Configurer MailHog :** 

Pour simuler l'envoi de courriels localement, nous vous recommandons d'installer MailHog. Vous pouvez le télécharger à partir du site officiel de MailHog et suivre les instructions d'installation appropriées pour votre système d'exploitation.

5. **Configurer l'Environnement :** 

Taper:`sudo nano /etc/hosts` et saisir le texte suivant : `127.0.0.1 blog.localhost`

Accéder à votre site en tapant l'URl suivante : http://blog.localhost/

Importer la base de données directement dans sur phpmyadmin

6. **Codacy :** 

Voici le badge certifiant la qualité du code.

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/2e8a09b45dfc4fbbaf6fe153edec760b)](https://app.codacy.com/gh/Axel92290/Projet_blog_php/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

7. **Comptes :** 

Admin :

username : Admin@blog.com

password : Admin123!

User : 

username :  Axel@gmail.com

password : Axel123!
