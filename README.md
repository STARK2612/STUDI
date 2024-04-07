# projet_zooarcadia_studi

Pour déployer votre application web PHP localement avec XAMPP, suivez ces étapes :

1-Installer XAMPP :

Téléchargez et installez XAMPP à partir du site officiel : https://www.apachefriends.org/index.html
Suivez les instructions d'installation pour votre système d'exploitation.
Configurer XAMPP :

2-Lancez XAMPP.
Démarrez les services Apache et MySQL en cliquant sur les boutons "Start" correspondants dans la fenêtre de contrôle de XAMPP.
Télécharger le code source de l'application :

3-Téléchargez le code source de votre application depuis GitHub : https://github.com/STARK2612/STUDI.git
Vous pouvez le télécharger en tant qu'archive ZIP et extraire les fichiers ou utiliser Git si vous avez Git installé sur votre système.
Placer le code source dans le répertoire htdocs de XAMPP. Lancez Visual Studio Code. Ouvrez le terminal intégré à VSCode en appuyant sur Ctrl + Shift + ù (ou View > Terminal` dans la barre de menus). Utilisez la commande cd pour naviguer vers le répertoire où vous souhaitez cloner le dépôt. Utilisez la commande suivante pour cloner le dépôt GitHub :'git clone https://github.com/STARK2612/STUDI.git'. Utilisez la commande cd pour accéder au répertoire que vous venez de cloner :'cd STUDI'. Maintenant, vous avez téléchargé le code source de votre application depuis GitHub et vous êtes prêt à continuer avec les étapes suivantes, comme placer le code source dans le répertoire htdocs de XAMPP et configurer votre application comme indiqué précédemment.

4-Naviguez jusqu'au répertoire d'installation de XAMPP sur votre système.
Trouvez le répertoire "htdocs". Par défaut, il est situé dans le répertoire d'installation de XAMPP.
Copiez les fichiers de votre application dans ce répertoire. Cela rendra votre application accessible via http://localhost/STUDI/index.php.
Configurer la base de données :

5-Assurez-vous que MySQL est en cours d'exécution dans XAMPP.
Ouvrez votre navigateur et accédez à http://localhost/phpmyadmin/.
Créez une nouvelle base de données et notez son nom.
Importez le fichier SQL fourni avec votre application dans cette base de données.
Modifier la configuration de l'application :

6-Renommez le fichier de configuration fourni (s'il en existe un, comme config.php) pour qu'il corresponde à vos paramètres de configuration locaux. Assurez-vous que les paramètres de connexion à la base de données correspondent à ceux de votre environnement local (nom d'utilisateur, mot de passe, nom de la base de données, etc.).
Accéder à votre application :

7-Ouvrez votre navigateur et accédez à http://localhost/STUDI/index.php. Remplacez "STUDI" par le nom du répertoire où vous avez placé votre application.