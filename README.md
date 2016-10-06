Symfony
=======

A Symfony project created on July 26, 2016, 3:37 pm.





///   INSTRUCTIONS POUR CORRECTION VIA OPENCLASSROOMS   \\\





Hello tout le monde !


Voici mon projet et la marche à suivre -au cas où- .. :

!!! Adaptez les commandes en fonction de votre environnement évidemment !!!

  1.   Télécharger le fichier .zip
  2.   Dézipper le fichier .zip
  3.   >>>    chmod 755 ProjectSymfony
  4.   >>>    cd ProjectSymfony
  5.   >>>    composer update                      (si composer est installé en global)
        >>>    php composer.phar update       (si composer est installé en local)

        (Une erreur apparaît mais c'est normal, pas encore de droit d'écriture dans le dossier "var")

  6.   Vérifier les paramètres de connexion à votre base de données via config.yml / parameters.yml
  7.   >>>    php bin/console doctrine:fixtures:load          (pour charger les données via fixtures)
  8.   >>>    php bin/console cache:clear
  9.   >>>    sudo chmod -R 777 var/
10.   Le projet est accessible

J'espère que le projet tournera correctement sur votre machine ! :-)

Merci et excellente continuation à vous tous !

Laurent