-------------------
#exemple de base de donnée :
=> Créer une nouvelle base de données  
CREATE DATABASE bibliotheque;  

=>Utiliser la base de données  
USE bibliotheque;  

=> Créer la table auteurs  
CREATE TABLE auteurs (  
    id INT AUTO_INCREMENT PRIMARY KEY,  
    nom VARCHAR(100) NOT NULL,  
    bio TEXT  
);  

=> Créer la table livres  
CREATE TABLE livres (  
    id INT AUTO_INCREMENT PRIMARY KEY,  
    titre VARCHAR(255) NOT NULL,  
    description TEXT,  
    auteur_id INT,  
    FOREIGN KEY (auteur_id) REFERENCES auteurs(id)  
);

=> Insretion des données :
-> Pour les auteurs  
INSERT INTO auteurs (nom, bio) VALUES  
('Victor Hugo', 'Un des écrivains les plus célèbres du XIXe siècle, connu pour ses œuvres comme Les Misérables.'),  
('Marcel Proust', 'Romancier français, surtout connu pour son œuvre À la recherche du temps perdu.'),  
('George Orwell', 'Écrivain et journaliste anglais, célèbre pour ses livres 1984 et La Ferme des animaux.');  

-> Pour les livres  
INSERT INTO livres (titre, description, auteur_id) VALUES  
('Les Misérables', 'Roman sur la misère et la rédemption.', 1),  
('Notre-Dame de Paris', 'L’histoire tragique de Quasimodo et Esmeralda.', 1),  
('À la recherche du temps perdu', 'Livre monumental sur la mémoire et le temps.', 2),  
('1984', 'Roman dystopique sur la surveillance et la totalitarisme.', 3),  
('La Ferme des animaux', 'Fable satirique sur le communisme et la corruption.', 3);

