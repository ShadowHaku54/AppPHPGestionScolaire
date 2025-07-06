
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/*!40000 DROP DATABASE IF EXISTS `ism_db`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `ism_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `ism_db`;
DROP TABLE IF EXISTS `annee_scolaire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `annee_scolaire` (
  `id_annee_scolaire` int NOT NULL AUTO_INCREMENT,
  `debut` int NOT NULL,
  `fin` int NOT NULL,
  `date_definition` date NOT NULL,
  PRIMARY KEY (`id_annee_scolaire`),
  UNIQUE KEY `debut` (`debut`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `annee_scolaire` WRITE;
/*!40000 ALTER TABLE `annee_scolaire` DISABLE KEYS */;
INSERT INTO `annee_scolaire` VALUES (1,2024,2025,'2024-06-28');
/*!40000 ALTER TABLE `annee_scolaire` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `classe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classe` (
  `id_classe` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) NOT NULL,
  `niveau` enum('L1','L2','L3','M1','M2') NOT NULL,
  `filiere` enum('GLRS','TTL','IAGE','MAIE','ETSE') NOT NULL,
  `debut_inscription` date NOT NULL,
  `fin_inscription` date NOT NULL,
  `id_annee_scolaire` int NOT NULL,
  PRIMARY KEY (`id_classe`),
  UNIQUE KEY `libelle` (`libelle`,`id_annee_scolaire`),
  KEY `id_annee_scolaire` (`id_annee_scolaire`),
  CONSTRAINT `classe_ibfk_1` FOREIGN KEY (`id_annee_scolaire`) REFERENCES `annee_scolaire` (`id_annee_scolaire`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `classe` WRITE;
/*!40000 ALTER TABLE `classe` DISABLE KEYS */;
INSERT INTO `classe` VALUES (11,'GLRSA','L2','GLRS','2025-09-01','2025-12-31',1),(12,'T','L1','TTL','2025-09-01','2025-12-31',1),(13,'I','L1','IAGE','2025-09-01','2025-12-31',1),(14,'M','L1','MAIE','2025-09-01','2025-12-31',1),(15,'E','L1','ETSE','2025-09-01','2025-12-31',1),(16,'GLRSB','L2','GLRS','2025-09-01','2025-12-31',1),(17,'TTL','L2','TTL','2025-09-01','2025-12-31',1),(18,'IAGE','L2','IAGE','2025-09-01','2025-12-31',1),(19,'MAIE','L2','MAIE','2025-09-01','2025-12-31',1),(20,'ETSE','L2','ETSE','2025-09-01','2025-12-31',1),(21,'dcece','L1','GLRS','2025-07-05','2025-08-04',1),(22,'test','L1','GLRS','2025-07-05','2025-08-04',1),(23,'abc','L3','ETSE','2025-07-05','2025-08-04',1),(24,'test2','L1','GLRS','2025-07-05','2025-08-04',1),(25,'ddez','L1','GLRS','2025-07-05','2025-08-04',1);
/*!40000 ALTER TABLE `classe` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `demande`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demande` (
  `id_demande` int NOT NULL AUTO_INCREMENT,
  `date_demande` date NOT NULL,
  `id_annee_scolaire` int NOT NULL,
  `mat_etudiant` varchar(30) NOT NULL,
  `id_inscription` int NOT NULL,
  `type` enum('ANNULATION','SUSPENSION') NOT NULL,
  `motif` varchar(255) NOT NULL,
  `statut` enum('EN_ATTENTE','ACCEPTEE','REFUSE') NOT NULL DEFAULT 'EN_ATTENTE',
  PRIMARY KEY (`id_demande`),
  KEY `id_annee_scolaire` (`id_annee_scolaire`),
  KEY `mat_etudiant` (`mat_etudiant`),
  KEY `id_inscription` (`id_inscription`),
  CONSTRAINT `demande_ibfk_1` FOREIGN KEY (`id_annee_scolaire`) REFERENCES `annee_scolaire` (`id_annee_scolaire`),
  CONSTRAINT `demande_ibfk_2` FOREIGN KEY (`mat_etudiant`) REFERENCES `utilisateur` (`mat_etudiant`),
  CONSTRAINT `demande_ibfk_3` FOREIGN KEY (`id_inscription`) REFERENCES `inscription` (`id_inscription`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `demande` WRITE;
/*!40000 ALTER TABLE `demande` DISABLE KEYS */;
INSERT INTO `demande` VALUES (1,'2025-10-01',1,'ISM-2425/DK-1',9,'ANNULATION','Annulation du module Algorithmique','EN_ATTENTE'),(2,'2025-10-02',1,'ISM-2425/DK-2',10,'SUSPENSION','Suspension temporaire pour raisons médicales','EN_ATTENTE'),(3,'2025-10-03',1,'ISM-2425/DK-3',11,'ANNULATION','Annulation des TP Réseaux I','EN_ATTENTE'),(4,'2025-10-04',1,'ISM-2425/DK-4',12,'SUSPENSION','Suspension pour concours externe','EN_ATTENTE'),(5,'2025-10-05',1,'ISM-2425/DK-5',13,'ANNULATION','Annulation dossier pédagogique','EN_ATTENTE'),(6,'2025-10-01',1,'ISM-2425/DK-9',17,'ANNULATION','Je souhaite annuler le module « Programmation Orientée Objet » car je dois changer de filière.','EN_ATTENTE'),(7,'2025-10-02',1,'ISM-2425/DK-9',17,'SUSPENSION','Absence prolongée pour cause de maladie. Je sollicite une suspension temporaire de mon inscription.','EN_ATTENTE'),(8,'2025-10-03',1,'ISM-2425/DK-10',18,'ANNULATION','Je demande l’annulation de mon inscription au TP Réseaux car je n’ai pas validé le module prérequis.','EN_ATTENTE'),(9,'2025-10-04',1,'ISM-2425/DK-10',18,'SUSPENSION','Participation à un concours externe. Je sollicite une suspension pour la période du concours.','EN_ATTENTE'),(10,'2025-10-05',1,'ISM-2425/DK-11',19,'ANNULATION','Annulation du stage académique pour raisons familiales graves.','EN_ATTENTE'),(11,'2025-10-06',1,'ISM-2425/DK-11',19,'SUSPENSION','Je dois effectuer un voyage à l’étranger pour raisons médicales, je demande une suspension.','EN_ATTENTE'),(12,'2025-10-07',1,'ISM-2425/DK-12',20,'ANNULATION','Je souhaite annuler mon inscription à la session d’examens pour cause de non-préparation.','EN_ATTENTE'),(13,'2025-10-08',1,'ISM-2425/DK-12',20,'SUSPENSION','Problème administratif non résolu : je demande la suspension de ma scolarité le temps de régulariser ma situation.','EN_ATTENTE'),(14,'2025-10-09',1,'ISM-2425/DK-13',21,'ANNULATION','Erreur lors du choix des unités d’enseignement : demande d’annulation d’unité.','EN_ATTENTE'),(15,'2025-10-10',1,'ISM-2425/DK-13',21,'SUSPENSION','Problème familial nécessitant un déplacement de longue durée.','EN_ATTENTE'),(16,'2025-10-11',1,'ISM-2425/DK-14',22,'ANNULATION','Je souhaite annuler mon inscription car j’ai été accepté dans une autre université.','EN_ATTENTE'),(17,'2025-10-12',1,'ISM-2425/DK-14',22,'SUSPENSION','Naissance d’un enfant : je sollicite une suspension temporaire.','EN_ATTENTE'),(18,'2025-10-13',1,'ISM-2425/DK-15',23,'ANNULATION','Annulation de module demandé suite à une erreur administrative lors de l’inscription.','EN_ATTENTE'),(19,'2025-10-14',1,'ISM-2425/DK-15',23,'SUSPENSION','Travail à temps partiel incompatible avec les horaires de cours. Suspension demandée.','EN_ATTENTE'),(20,'2025-10-15',1,'ISM-2425/DK-16',24,'ANNULATION','Annulation d’inscription demandée pour réorientation professionnelle.','ACCEPTEE'),(21,'2025-10-16',1,'ISM-2425/DK-16',24,'SUSPENSION','Je dois effectuer un stage professionnel obligatoire : suspension temporaire.','EN_ATTENTE'),(22,'2025-10-17',1,'ISM-2425/DK-17',25,'ANNULATION','Je souhaite annuler mon inscription à ce module car je vais refaire une année.','EN_ATTENTE'),(23,'2025-10-18',1,'ISM-2425/DK-17',25,'SUSPENSION','Problème financier temporaire. Demande de suspension pour un semestre.','EN_ATTENTE'),(24,'2025-10-19',1,'ISM-2425/DK-18',26,'ANNULATION','Annulation pour cause d’incompatibilité avec un nouvel emploi.','REFUSE'),(25,'2025-10-20',1,'ISM-2425/DK-18',26,'SUSPENSION','Situation familiale urgente nécessitant la suspension immédiate de la scolarité.','EN_ATTENTE'),(26,'2025-07-02',1,'ISM-2425/DK-18',26,'SUSPENSION','je ne me sens pas. je voudrais une pause','EN_ATTENTE');
/*!40000 ALTER TABLE `demande` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `dispense`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dispense` (
  `id_dispense` int NOT NULL AUTO_INCREMENT,
  `id_professeur` int NOT NULL,
  `id_module` int NOT NULL,
  PRIMARY KEY (`id_dispense`),
  UNIQUE KEY `id_professeur` (`id_professeur`,`id_module`),
  KEY `id_module` (`id_module`),
  CONSTRAINT `dispense_ibfk_1` FOREIGN KEY (`id_professeur`) REFERENCES `professeur` (`id_professeur`),
  CONSTRAINT `dispense_ibfk_2` FOREIGN KEY (`id_module`) REFERENCES `module` (`id_module`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `dispense` WRITE;
/*!40000 ALTER TABLE `dispense` DISABLE KEYS */;
INSERT INTO `dispense` VALUES (1,1,1),(2,1,2),(3,2,3),(4,2,4),(5,3,5),(6,3,6),(7,4,7),(8,4,8),(9,5,9),(10,5,10),(11,6,1),(12,7,2),(13,8,3),(14,9,4),(15,10,5);
/*!40000 ALTER TABLE `dispense` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `inscription`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inscription` (
  `id_inscription` int NOT NULL AUTO_INCREMENT,
  `date_inscription` date NOT NULL,
  `id_annee_scolaire` int NOT NULL,
  `mat_etudiant` varchar(30) NOT NULL,
  `id_classe` int NOT NULL,
  `statut` enum('VALIDEE','SUSPENDUE','ANNULEE') NOT NULL DEFAULT 'VALIDEE',
  `type` enum('INSCRIPTION','REINSCRIPTION') NOT NULL,
  PRIMARY KEY (`id_inscription`),
  KEY `id_annee_scolaire` (`id_annee_scolaire`),
  KEY `mat_etudiant` (`mat_etudiant`),
  KEY `id_classe` (`id_classe`),
  CONSTRAINT `inscription_ibfk_1` FOREIGN KEY (`id_annee_scolaire`) REFERENCES `annee_scolaire` (`id_annee_scolaire`),
  CONSTRAINT `inscription_ibfk_2` FOREIGN KEY (`mat_etudiant`) REFERENCES `utilisateur` (`mat_etudiant`),
  CONSTRAINT `inscription_ibfk_3` FOREIGN KEY (`id_classe`) REFERENCES `classe` (`id_classe`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `inscription` WRITE;
/*!40000 ALTER TABLE `inscription` DISABLE KEYS */;
INSERT INTO `inscription` VALUES (9,'2025-09-01',1,'ISM-2425/DK-1',11,'VALIDEE','INSCRIPTION'),(10,'2025-09-01',1,'ISM-2425/DK-2',11,'VALIDEE','INSCRIPTION'),(11,'2025-09-01',1,'ISM-2425/DK-3',12,'VALIDEE','INSCRIPTION'),(12,'2025-09-01',1,'ISM-2425/DK-4',12,'VALIDEE','INSCRIPTION'),(13,'2025-09-01',1,'ISM-2425/DK-5',13,'VALIDEE','INSCRIPTION'),(14,'2025-09-01',1,'ISM-2425/DK-6',13,'VALIDEE','INSCRIPTION'),(15,'2025-09-01',1,'ISM-2425/DK-7',14,'VALIDEE','INSCRIPTION'),(16,'2025-09-01',1,'ISM-2425/DK-8',14,'VALIDEE','INSCRIPTION'),(17,'2025-09-01',1,'ISM-2425/DK-9',11,'VALIDEE','INSCRIPTION'),(18,'2025-09-01',1,'ISM-2425/DK-10',12,'VALIDEE','INSCRIPTION'),(19,'2025-09-01',1,'ISM-2425/DK-11',13,'VALIDEE','INSCRIPTION'),(20,'2025-09-01',1,'ISM-2425/DK-12',14,'VALIDEE','INSCRIPTION'),(21,'2025-09-01',1,'ISM-2425/DK-13',11,'VALIDEE','INSCRIPTION'),(22,'2025-09-01',1,'ISM-2425/DK-14',12,'VALIDEE','INSCRIPTION'),(23,'2025-09-01',1,'ISM-2425/DK-15',13,'VALIDEE','INSCRIPTION'),(24,'2025-09-01',1,'ISM-2425/DK-16',14,'ANNULEE','INSCRIPTION'),(25,'2025-09-01',1,'ISM-2425/DK-17',11,'VALIDEE','INSCRIPTION'),(26,'2025-09-01',1,'ISM-2425/DK-18',12,'VALIDEE','INSCRIPTION');
/*!40000 ALTER TABLE `inscription` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `module` (
  `id_module` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `nb_heure` int NOT NULL,
  PRIMARY KEY (`id_module`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `module` WRITE;
/*!40000 ALTER TABLE `module` DISABLE KEYS */;
INSERT INTO `module` VALUES (1,'Algorithmique',45),(2,'Structures de données',45),(3,'Bases de données I',30),(4,'Réseaux I',30),(5,'Génie logiciel I',30),(6,'Systèmes d’exploitation',45),(7,'Web I (HTML/CSS/JS)',30),(8,'PHP & MySQL',30),(9,'Mathématiques discrètes',45),(10,'Anglais technique',30),(11,'test',14);
/*!40000 ALTER TABLE `module` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `professeur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `professeur` (
  `id_professeur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `grade` enum('ASSISTANT','MAITRE_CONFERENCE','PROF_TITULAIRE','PROF_TITULAIRE_PLUS','PROF_EMERITE','STATUT_TEMPORAIRE','AUTRE') NOT NULL,
  PRIMARY KEY (`id_professeur`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `professeur` WRITE;
/*!40000 ALTER TABLE `professeur` DISABLE KEYS */;
INSERT INTO `professeur` VALUES (1,'Traoré','Salif','ASSISTANT'),(2,'Ouédraogo','Aïssata','MAITRE_CONFERENCE'),(3,'Diallo','Mamadou','ASSISTANT'),(4,'Sow','Fatou','PROF_TITULAIRE'),(5,'Ndiaye','Abdou','MAITRE_CONFERENCE'),(6,'Coulibaly','Aminata','ASSISTANT'),(7,'Kaboré','Issa','PROF_TITULAIRE'),(8,'Ba','Cheikh','ASSISTANT'),(9,'Sanogo','Mariam','MAITRE_CONFERENCE'),(10,'Camara','Ibrahima','PROF_TITULAIRE'),(16,'test','test','ASSISTANT');
/*!40000 ALTER TABLE `professeur` ENABLE KEYS */;
UNLOCK TABLES;
DROP TABLE IF EXISTS `utilisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utilisateur` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email_of_school` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('RP','AC','ETU') NOT NULL,
  `mat_etudiant` varchar(30) DEFAULT NULL,
  `personal_email` varchar(100) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `sexe` enum('M','F') DEFAULT NULL,
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `email_of_school` (`email_of_school`),
  UNIQUE KEY `mat_etudiant` (`mat_etudiant`),
  UNIQUE KEY `personal_email` (`personal_email`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

LOCK TABLES `utilisateur` WRITE;
/*!40000 ALTER TABLE `utilisateur` DISABLE KEYS */;
INSERT INTO `utilisateur` VALUES (1,'Diallo','Abdoulaye','abdoulaye-diallo@ism.rp.sn','pass1234','RP',NULL,NULL,NULL,NULL),(2,'Sow','Aminata','aminata-sow@ism.ac.sn','pass1234','AC',NULL,NULL,NULL,NULL),(3,'Zongo','Fatimata','fatimata-zongo@ism.etu.sn','pass1234','ETU','ISM-2425/DK-1','fatimata.zongo@gmail.com','Dakar Liberté 6','F'),(4,'Kone','Ismael','ismael-kone@ism.etu.sn','pass1234','ETU','ISM-2425/DK-2','ismael.kone@gmail.com','Dakar Yoff','M'),(5,'Sanou','Mariam','mariam-sanou@ism.etu.sn','pass1234','ETU','ISM-2425/DK-3','mariam.sanou@gmail.com','Ouakam','F'),(6,'Cissé','Moussa','moussa-cisse@ism.etu.sn','pass1234','ETU','ISM-2425/DK-4','moussa.cisse@gmail.com','Pikine','M'),(7,'Traoré','Awa','awa-traore@ism.etu.sn','pass1234','ETU','ISM-2425/DK-5','awa.traore@gmail.com','Medina','F'),(8,'Diop','Mohamed','mohamed-diop@ism.etu.sn','pass1234','ETU','ISM-2425/DK-6','mohamed.diop@gmail.com','Grand-Yoff','M'),(9,'Touré','Khady','khady-toure@ism.etu.sn','pass1234','ETU','ISM-2425/DK-7','khady.toure@gmail.com','Fann','F'),(10,'Konaté','Boubacar','boubacar-konate@ism.etu.sn','pass1234','ETU','ISM-2425/DK-8','boubacar.konate@gmail.com','Liberté 4','M'),(11,'Ouattara','Yacouba','yacouba-ouattara@ism.etu.sn','pass1234','ETU','ISM-2425/DK-9','yacouba.ouattara@gmail.com','Liberté 5','M'),(12,'Kabore','Nadia','nadia-kabore@ism.etu.sn','pass1234','ETU','ISM-2425/DK-10','nadia.kabore@gmail.com','Fann','F'),(13,'Ouédraogo','Oumar','oumar-ouedraogo@ism.etu.sn','pass1234','ETU','ISM-2425/DK-11','oumar.ouedraogo@gmail.com','Ouakam','M'),(14,'Sangaré','Fatou','fatou-sangare@ism.etu.sn','pass1234','ETU','ISM-2425/DK-12','fatou.sangare@gmail.com','Yoff','F'),(15,'Bamba','Adama','adama-bamba@ism.etu.sn','pass1234','ETU','ISM-2425/DK-13','adama.bamba@gmail.com','Medina','M'),(16,'Zoungrana','Aïcha','aicha-zoungrana@ism.etu.sn','pass1234','ETU','ISM-2425/DK-14','aicha.zoungrana@gmail.com','Liberté 6','F'),(17,'Compaoré','Souleymane','souleymane-compaore@ism.etu.sn','pass1234','ETU','ISM-2425/DK-15','souleymane.compaore@gmail.com','Grand-Yoff','M'),(18,'Savadogo','Binta','binta-savadogo@ism.etu.sn','pass1234','ETU','ISM-2425/DK-16','binta.savadogo@gmail.com','Liberté 4','F'),(19,'Dabiré','Serge','serge-dabire@ism.etu.sn','pass1234','ETU','ISM-2425/DK-17','serge.dabire@gmail.com','Dakar Plateau','M'),(20,'Ouattara','Mariam','mariam-ouattara@ism.etu.sn','pass1234','ETU','ISM-2425/DK-18','mariam.ouattara@gmail.com','Liberté 3','F');
/*!40000 ALTER TABLE `utilisateur` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

