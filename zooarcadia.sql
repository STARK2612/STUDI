-- Création de la base de données "zooarcadia"
CREATE DATABASE IF NOT EXISTS zooarcadia;

-- Sélection de la base de données "zooarcadia"
USE zooarcadia;

-- Création de la table "animal"
CREATE TABLE IF NOT EXISTS animal (
    animal_id INT AUTO_INCREMENT PRIMARY KEY,
    date_nour DATE,
    etat VARCHAR(255),
    habitat_id INT,
    heure_nour TIME,
    image_id INT,
    nour VARCHAR(255),
    prenom VARCHAR(255),
    qte_nour INT,
    race_id INT,
    rapport_veterinaire_id INT,
    FOREIGN KEY (race_id) REFERENCES race(race_id),
    FOREIGN KEY (rapport_veterinaire_id) REFERENCES rapport_veterinaire(rapport_veterinaire_id)
);

-- Création de la table "avis"
CREATE TABLE IF NOT EXISTS avis (
    avis_id INT AUTO_INCREMENT PRIMARY KEY,
    commentaire VARCHAR(255),
    isVisible TINYINT,
    pseudo VARCHAR(255)
);

-- Création de la table "habitat"
CREATE TABLE IF NOT EXISTS habitat (
    habitat_id INT AUTO_INCREMENT PRIMARY KEY,
    commentaire_habitat VARCHAR(255),
    description VARCHAR(255),
    image_id INT,
    nom VARCHAR(255)
);

-- Création de la table "image"
CREATE TABLE IF NOT EXISTS image (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    image_data MEDIUMBLOB,
    image_type VARCHAR(255)
);

-- Création de la table "race"
CREATE TABLE IF NOT EXISTS race (
    race_id INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(255)
);

-- Création de la table "rapport_veterinaire"
CREATE TABLE IF NOT EXISTS rapport_veterinaire (
    rapport_veterinaire_id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE,
    detail VARCHAR(255)
);

-- Création de la table "role"
CREATE TABLE IF NOT EXISTS role (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(255)
);

-- Création de la table "service"
CREATE TABLE IF NOT EXISTS service (
    service_id INT AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(255),
    image_id INT,
    nom VARCHAR(255)
);

-- Création de la table "stat"
CREATE TABLE IF NOT EXISTS stat (
    stat_id INT AUTO_INCREMENT PRIMARY KEY,
    animal_id INT,
    counter INT,
    date DATE,
    FOREIGN KEY (animal_id) REFERENCES animal(animal_id)
);

-- Création de la table "utilisateur"
CREATE TABLE IF NOT EXISTS utilisateur (
    username VARCHAR(255) PRIMARY KEY,
    nom VARCHAR(255),
    password VARCHAR(255),
    prenom VARCHAR(255),
    role_id INT,
    FOREIGN KEY (role_id) REFERENCES role(role_id)
);

-- Intégration des données dans la table "race"
INSERT INTO zooarcadia.race (label) VALUES 
('Lion'), 
('Zèbre'), 
('Éléphant'), 
('Girafe'), 
('Gazelle'), 
('Guépard'), 
('Capucin'), 
('Perroquet'), 
('Ours Noir'), 
('Tigre'), 
('Panthère'), 
('Jaguar'), 
('Crocodile'), 
('Tortue'), 
('Serpent'), 
('Grenouille'), 
('Alligator'), 
('Ragondin');

-- Intégration des données dans la table "role"
INSERT INTO zooarcadia.role (label) VALUES 
('Administrateur'), 
('Employé'),
('Vétérinaire');

-- Intégration des données dans la table "utilisateur"
INSERT INTO zooarcadia.utilisateur (username, nom, password, prenom, role_id) VALUES 
('admin@arcadiazoo.com', 'GENTIL', 'G3nt1lJ0sé', 'José', 1), 
('employe@arcadiazoo.com', 'BARROS', 'Employé', 'Charles', 2),
('veto@arcadiazoo.com', 'PROTANA', 'Vétérinaire', 'Marie', 3);

-- Intégration des données dans la table "habitat"
INSERT INTO zooarcadia.habitat (image_id, commentaire_habitat, description, nom) VALUES 
(20, 'L''habitat de la savane est bien aménagé, offrant des espaces ouverts et des abris appropriés pour les animaux. La végétation est bien entretenue, fournissant une atmosphère naturelle qui favorise le comportement naturel des espèces qui y habitent. Les points d''eau sont adéquats pour les besoins en hydratation des animaux, ce qui contribue à leur bien-être général.', 'La savane est un paysage caractérisé par de vastes plaines herbeuses parsemées d''arbres dispersés, principalement trouvée dans les régions tropicales et subtropicales. Les températures sont généralement chaudes, avec des saisons sèches et humides distinctes. La savane abrite une diversité d''animaux, y compris des herbivores comme les zèbres et les girafes, ainsi que des prédateurs tels que les lions et les léopards.', 'Savane'),
(2, 'L''habitat de la jungle est dense et luxuriant, recréant efficacement l''environnement naturel des animaux de la jungle. La végétation offre de nombreuses cachettes et structures pour permettre aux animaux de se cacher, grimper et explorer. Les conditions d''humidité sont bien contrôlées pour imiter au mieux le climat de la jungle, favorisant ainsi le comportement naturel des espèces qui y résident.', 'La jungle est une forêt dense et luxuriante, souvent trouvée dans les climats tropicaux et équatoriaux, caractérisée par une végétation dense, des arbres immenses et une humidité élevée. Les canopées des arbres créent un environnement sombre et ombragé en dessous, où une multitude d''espèces végétales et animales prospèrent. Les singes, les oiseaux tropicaux colorés et une variété d''insectes sont des habitants communs de la jungle.', 'Jungle'),
(17, 'L''habitat du marais est soigneusement conçu pour refléter les caractéristiques naturelles d''un marais, avec des zones aquatiques et terrestres appropriées. La végétation aquatique est bien entretenue, offrant des refuges et des habitats pour une variété d''espèces, des oiseaux aquatiques aux reptiles. Les conditions de l''eau sont surveillées de près pour assurer une qualité adéquate, soutenant ainsi la santé et le bien-être des animaux qui y vivent.', 'Les marais sont des zones humides caractérisées par une végétation aquatique dense, des eaux stagnantes ou peu profondes, et une variété d''espèces animales adaptées à ce milieu particulier. Les marais peuvent varier en taille et en complexité, allant des petits marais côtiers aux vastes zones de marécages intérieurs. Ils abritent souvent des oiseaux aquatiques, des reptiles comme les crocodiles et les alligators, ainsi que divers amphibiens et poissons adaptés à la vie dans l''eau stagnante.', 'Marais');

-- Intégration des données dans la table "animal"
INSERT INTO zooarcadia.animal (image_id, date_nour, etat, habitat_id, heure_nour, nour, prenom, qte_nour, race_id, rapport_veterinaire_id) VALUES 
(16, '2024-04-02', 'En bonne santé', 1, '09:00:00', 'Viande', 'Simba', 200, 1, 1),
(1, '2024-04-03', 'En bonne santé', 1, '11:00:00', 'Herbe fraîche', 'Zara', 150, 2, 2),
(6, '2024-04-01', 'En bonne santé', 1, '08:30:00', 'Fruits', 'Rocco', 100, 3, 3),
(9, '2024-03-31', 'Blessé', 1, '07:45:00', 'Herbe séchée', 'Leo', 120, 4, 4),
(8, '2024-04-05', 'En bonne santé', 1, '10:00:00', 'Granulés', 'Mia', 80, 5, 5),
(10, '2024-04-04', 'En bonne santé', 1, '12:00:00', 'Poisson', 'Kibo', 60, 6, 6),
(22, '2024-04-02', 'En bonne santé', 2, '09:30:00', 'Bananes', 'Coco', 100, 7, 7),
(3, '2024-04-01', 'En bonne santé', 2, '11:30:00', 'Insectes', 'Kali', 80, 8, 8),
(25, '2024-03-30', 'En bonne santé', 2, '10:00:00', 'Nectar', 'Baloo', 120, 9, 9),
(23, '2024-04-03', 'Blessé', 2, '08:45:00', 'Baies', 'Mowgli', 150, 10, 10),
(18, '2024-04-05', 'En bonne santé', 2, '12:00:00', 'Rongeurs', 'Nala', 70, 11, 11),
(12, '2024-04-04', 'En bonne santé', 2, '10:45:00', 'Fruits de la passion', 'Rio', 90, 12, 12),
(27, '2024-04-02', 'En bonne santé', 3, '08:00:00', 'Poissons', 'Croky', 100, 13, 13),
(14, '2024-04-01', 'En bonne santé', 3, '09:30:00', 'Vers de terre', 'Lily', 80, 14, 14),
(21, '2024-03-30', 'Blessé', 3, '11:00:00', 'Grenouilles', 'Spike', 120, 15, 15),
(13, '2024-04-03', 'En bonne santé', 3, '10:15:00', 'Insectes aquatiques', 'Splash', 150, 16, 16),
(26, '2024-04-05', 'En bonne santé', 3, '12:30:00', 'Crustacés', 'Rocky', 70, 17, 17),
(15, '2024-04-04', 'En bonne santé', 3, '11:45:00', 'Mollusques', 'Tico', 90, 18, 18);

-- Intégration des données dans la table "rapport_veterinaire"
INSERT INTO zooarcadia.rapport_veterinaire (date, detail) VALUES 
('2024-04-02', 'Simba est en bonne santé. Aucun problème de santé détecté lors de la visite.'),
('2024-04-03', 'Zara est en bonne santé. Aucun problème de santé détecté lors de la visite.'),
('2024-04-01', 'Rocco est en bonne santé. Aucun problème de santé détecté lors de la visite.'),
('2024-03-31', 'Leo est blessé. Une blessure mineure a été observée sur sa patte droite. Traitement administré.'),
('2024-04-05', 'Mia est en bonne santé. Aucun problème de santé détecté lors de la visite.'),
('2024-04-04', 'Kibo est en bonne santé. Aucun problème de santé détecté lors de la visite.'),
('2024-04-02', 'Coco est en bonne santé. Aucun problème de santé détecté lors de la visite.'),
('2024-04-01', 'Kali est en bonne santé. Aucun problème de santé détecté lors de la visite.'),
('2024-03-30', 'Baloo est en bonne santé. Aucun problème de santé détecté lors de la visite.'),
('2024-04-03', 'Mowgli est blessé. Une coupure légère a été observée sur son dos. Traitement administré.'),
('2024-04-05', 'Nala est en bonne santé. Aucun problème de santé détecté lors de la visite.'),
('2024-04-04', 'Rio est en bonne santé. Aucun problème de santé détecté lors de la visite.'),
('2024-04-02', 'Croky est en bonne santé. Aucun problème de santé détecté lors de la visite.'),
('2024-04-01', 'Lily est en bonne santé. Aucun problème de santé détecté lors de la visite.'),
('2024-03-30', 'Spike est blessé. Une plaie a été observée sur son abdomen. Traitement administré.'),
('2024-04-03', 'Splash est en bonne santé. Aucun problème de santé détecté lors de la visite.'),
('2024-04-05', 'Rocky est en bonne santé. Aucun problème de santé détecté lors de la visite.'),
('2024-04-04', 'Tico est en bonne santé. Aucun problème de santé détecté lors de la visite.');

-- Intégration des données dans la table "service"
INSERT INTO zooarcadia.service (image_id, description, nom) VALUES 
(19, 'Profitez de délicieuses options culinaires qui raviront vos papilles tout en vous offrant l''énergie nécessaire pour explorer le zoo.', 'Restauration'),
(11, 'Plongez dans le monde fascinant de nos habitants à fourrure, à plumes et à écailles avec nos visites guidées gratuites. Nos guides expérimentés vous emmèneront à travers les habitats pour vous permettre de découvrir les secrets de chaque espèce.', 'Visite des habitats avec un guide (gratuit)'),
(24, 'Montez à bord de notre petit train et laissez-vous transporter à travers les merveilles du zoo. Profitez d''une expérience panoramique relaxante tout en explorant les différents habitats et en observant nos animaux dans leur environnement naturel.', 'Visite du zoo en petit train');

-- Intégration des données dans la table "avis"
INSERT INTO zooarcadia.avis (commentaire, isVisible, pseudo) VALUES 
('Le zoo Arcadia offre une expérience exceptionnelle pour toute la famille. La visite guidée était informative et captivante, et le petit train était un moyen fantastique de découvrir tous les habitats. Les repas proposés étaient délicieux, et j''ai particulièrement aimé observer les pandas roux dans leur habitat naturel !', 0, 'AventureFan'),
('Mon expérience au zoo Arcadia a été absolument fantastique ! Les guides étaient très compétents et passionnés, ce qui a rendu la visite des habitats encore plus enrichissante. J''ai adoré le petit train, c''était une façon unique de voir tous les animaux et de profiter de la journée. Je recommande vivement cette expérience à tous les amoureux de la nature !', 0, 'Explorateur123'),
('Une journée au zoo Arcadia est une journée bien dépensée ! La restauration était excellente, avec une variété d''options pour satisfaire tous les goûts. La visite des habitats avec un guide était très instructive, et j''ai été impressionné par l''engagement du zoo envers la conservation. Le petit train était un ajout amusant, et j''ai eu la chance de voir les lions dans leur habitat naturel, ce qui a été une expérience inoubliable !', 0, 'NatureEnthusiast'),
('Le zoo Arcadia est un véritable joyau pour les amoureux des animaux. La visite guidée était très intéressante, et j''ai beaucoup apprécié en apprendre davantage sur les différentes espèces. Le petit train était une façon relaxante de parcourir le zoo, et j''ai été émerveillé par la variété des habitats et des animaux. Je reviendrai certainement pour une autre visite !', 0, 'WildlifeWatcher'),
('Le zoo Arcadia est l''endroit idéal pour une journée en famille. La visite des habitats avec un guide était éducative et divertissante, et le petit train a rendu la visite encore plus spéciale. Les options de restauration étaient délicieuses, et j''ai adoré observer les singes dans leur habitat naturel !', 0, 'FamilleHeureuse'),
('Ma visite au zoo Arcadia a été une expérience incroyable ! Les guides étaient passionnés et compétents, et j''ai beaucoup appris sur la conservation des espèces. Le petit train était un moyen pratique de se déplacer dans le zoo, et j''ai été impressionné par la diversité des animaux et des habitats. Je recommande vivement cette destination à tous les amoureux de la nature !', 0, 'NaturExplorer'),
('Le zoo Arcadia offre une expérience immersive dans le monde de la faune. La visite guidée était instructive et interactive, et le petit train était une manière agréable de découvrir tous les animaux. Les options de restauration étaient délicieuses, et j''ai particulièrement apprécié observer les girafes dans leur habitat naturel !', 0, 'WildlifeAdventurer');

-- Insérer les données des images dans la table "image"
INSERT INTO image (image_id, image_data, image_type) VALUES
(1, LOAD_FILE('front/img/Zèbre.jpg'), 'jpg'),
(2, LOAD_FILE('front/img/jungle.jpeg'), 'jpeg'),
(3, LOAD_FILE('front/img/perroquet_ara.jpeg'), 'jpeg'),
(4, LOAD_FILE('front/img/default.jpg'), 'jpg'),
(5, LOAD_FILE('front/img/defaultsmall.jpg'), 'jpg'),
(6, LOAD_FILE('front/img/Éléphant.jpg'), 'jpg'),
(7, LOAD_FILE('front/img/exemple.jpg'), 'jpg'),
(8, LOAD_FILE('front/img/Gazelle.jpg'), 'jpg'),
(9, LOAD_FILE('front/img/Girafe.jpg'), 'jpg'),
(10, LOAD_FILE('front/img/Guépard.jpg'), 'jpg'),
(11, LOAD_FILE('front/img/guide.jpg'), 'jpg'),
(12, LOAD_FILE('front/img/Jaguar.jpg'), 'jpg'),
(13, LOAD_FILE('front/img/La grenouille_verte.jpg'), 'jpg'),
(14, LOAD_FILE('front/img/La_tortue_de_Floride.jpg'), 'jpg'),
(15, LOAD_FILE('front/img/Le_ragondin.jpg'), 'jpg'),
(16, LOAD_FILE('front/img/Lion.jpg'), 'jpg'),
(17, LOAD_FILE('front/img/marais.jpg'), 'jpg'),
(18, LOAD_FILE('front/img/panthere_noir.jpg'), 'jpg'),
(19, LOAD_FILE('front/img/restauration.jpg'), 'jpg'),
(20, LOAD_FILE('front/img/savane.jpg'), 'jpg'),
(21, LOAD_FILE('front/img/serpent_des_marais.jpg'), 'jpg'),
(22, LOAD_FILE('front/img/singe_capucin.jpg'), 'jpg'),
(23, LOAD_FILE('front/img/tigre.jpg'), 'jpg'),
(24, LOAD_FILE('front/img/train.jpg'), 'jpg'),
(25, LOAD_FILE('front/img/ours_noir.jpeg'), 'jpeg'),
(26, LOAD_FILE('front/img/alligator_du_mississipi.jpg'), 'jpg'),
(27, LOAD_FILE('front/img/crocodile_du_nil.jpg'), 'jpg');



-- Intégration des données dans la table "stat"
INSERT INTO zooarcadia.stat (animal_id, counter, date) VALUES 
(1, 20, '2023-06-15'),
(1, 30, '2023-08-20'),
(1, 40, '2023-10-10'),
(1, 50, '2023-12-25'),
(1, 51, '2024-01-05'),
(1, 45, '2024-03-07'),
(1, 35, '2024-05-15'),
(1, 25, '2024-07-20'),
(1, 15, '2024-09-10'),
(1, 5, '2024-11-25'),
(2, 10, '2023-06-20'),
(2, 20, '2023-08-25'),
(2, 30, '2023-10-15'),
(2, 40, '2023-12-30'),
(2, 50, '2024-01-10'),
(2, 51, '2024-03-12'),
(2, 45, '2024-05-20'),
(2, 35, '2024-07-25'),
(2, 25, '2024-09-15'),
(2, 15, '2024-11-30'),
(3, 10, '2023-06-25'),
(3, 20, '2023-08-30'),
(3, 30, '2023-10-20'),
(3, 40, '2023-12-31'),
(3, 50, '2024-01-15'),
(3, 51, '2024-03-17'),
(3, 45, '2024-05-25'),
(3, 35, '2024-07-30'),
(3, 25, '2024-09-20'),
(3, 15, '2024-12-05'),
(4, 10, '2023-07-01'),
(4, 20, '2023-09-05'),
(4, 30, '2023-10-25'),
(4, 40, '2024-01-01'),
(4, 50, '2024-01-20'),
(4, 51, '2024-03-20'),
(4, 45, '2024-06-05'),
(4, 35, '2024-08-01'),
(4, 25, '2024-09-25'),
(4, 15, '2024-12-10'),
(5, 10, '2023-07-05'),
(5, 20, '2023-09-10'),
(5, 30, '2023-11-01'),
(5, 40, '2024-01-05'),
(5, 50, '2024-01-25'),
(5, 51, '2024-03-25'),
(5, 45, '2024-06-10'),
(5, 35, '2024-08-05'),
(5, 25, '2024-10-01'),
(5, 15, '2024-12-15'),
(6, 10, '2023-07-10'),
(6, 20, '2023-09-15'),
(6, 30, '2023-11-05'),
(6, 40, '2024-01-10'),
(6, 50, '2024-02-01'),
(6, 51, '2024-04-01'),
(6, 45, '2024-06-15'),
(6, 35, '2024-08-10'),
(6, 25, '2024-10-05'),
(6, 15, '2024-12-20'),
(7, 10, '2023-07-15'),
(7, 20, '2023-09-20'),
(7, 30, '2023-11-10'),
(7, 40, '2024-01-15'),
(7, 50, '2024-02-05'),
(7, 51, '2024-04-05'),
(7, 45, '2024-06-20'),
(7, 35, '2024-08-15'),
(7, 25, '2024-10-10'),
(7, 15, '2024-12-25'),
(8, 10, '2023-07-20'),
(8, 20, '2023-09-25'),
(8, 30, '2023-11-15'),
(8, 40, '2024-01-20'),
(8, 50, '2024-02-10'),
(8, 51, '2024-04-10'),
(8, 45, '2024-06-25'),
(8, 35, '2024-08-20'),
(8, 25, '2024-10-15'),
(8, 15, '2025-01-01'),
(9, 10, '2023-07-25'),
(9, 20, '2023-10-01'),
(9, 30, '2023-11-20'),
(9, 40, '2024-01-25'),
(9, 50, '2024-02-15'),
(9, 51, '2024-04-15'),
(9, 45, '2024-07-01'),
(9, 35, '2024-08-25'),
(9, 25, '2024-10-20'),
(9, 15, '2025-01-05'),
(10, 10, '2023-07-30'),
(10, 20, '2023-10-05'),
(10, 30, '2023-11-25'),
(10, 40, '2024-02-01'),
(10, 50, '2024-02-20'),
(10, 51, '2024-04-20'),
(10, 45, '2024-07-05'),
(10, 35, '2024-09-01'),
(10, 25, '2024-10-25'),
(10, 15, '2025-01-10'),
(11, 10, '2023-08-05'),
(11, 20, '2023-10-10'),
(11, 30, '2023-12-01'),
(11, 40, '2024-02-05'),
(11, 50, '2024-02-25'),
(11, 51, '2024-04-25'),
(11, 45, '2024-07-10'),
(11, 35, '2024-09-05'),
(11, 25, '2024-11-01'),
(11, 15, '2025-01-15'),
(12, 10, '2023-08-10'),
(12, 20, '2023-10-15'),
(12, 30, '2023-12-05'),
(12, 40, '2024-02-10'),
(12, 50, '2024-03-01'),
(12, 51, '2024-05-01'),
(12, 45, '2024-07-15'),
(12, 35, '2024-09-10'),
(12, 25, '2024-11-05'),
(12, 15, '2025-01-20'),
(13, 10, '2023-08-15'),
(13, 20, '2023-10-20'),
(13, 30, '2023-12-10'),
(13, 40, '2024-02-15'),
(13, 50, '2024-03-05'),
(13, 51, '2024-05-05'),
(13, 45, '2024-07-20'),
(13, 35, '2024-09-15'),
(13, 25, '2024-11-10'),
(13, 15, '2025-01-25'),
(14, 10, '2023-08-20'),
(14, 20, '2023-10-25'),
(14, 30, '2023-12-15'),
(14, 40, '2024-02-20'),
(14, 50, '2024-03-10'),
(14, 51, '2024-05-10'),
(14, 45, '2024-07-25'),
(14, 35, '2024-09-20'),
(14, 25, '2024-11-15'),
(14, 15, '2025-02-01'),
(15, 10, '2023-08-25'),
(15, 20, '2023-10-30'),
(15, 30, '2023-12-20'),
(15, 40, '2024-02-25'),
(15, 50, '2024-03-15'),
(15, 51, '2024-05-15'),
(15, 45, '2024-08-01'),
(15, 35, '2024-09-25'),
(15, 25, '2024-11-20'),
(15, 15, '2025-02-05'),
(16, 10, '2023-08-30'),
(16, 20, '2023-11-05'),
(16, 30, '2023-12-25'),
(16, 40, '2024-03-01'),
(16, 50, '2024-03-20'),
(16, 51, '2024-05-20'),
(16, 45, '2024-08-05'),
(16, 35, '2024-10-01'),
(16, 25, '2024-11-25'),
(16, 15, '2025-02-10'),
(17, 10, '2023-09-05'),
(17, 20, '2023-11-10'),
(17, 30, '2024-01-01'),
(17, 40, '2024-03-05'),
(17, 50, '2024-03-25'),
(17, 51, '2024-05-25'),
(17, 45, '2024-08-10'),
(17, 35, '2024-10-05'),
(17, 25, '2024-12-01'),
(17, 15, '2025-02-15'),
(18, 10, '2023-09-10'),
(18, 20, '2023-11-15'),
(18, 30, '2024-01-05'),
(18, 40, '2024-03-10'),
(18, 50, '2024-04-01'),
(18, 51, '2024-06-01'),
(18, 45, '2024-08-15'),
(18, 35, '2024-10-10'),
(18, 25, '2024-12-05'),
(18, 15, '2025-02-20');

