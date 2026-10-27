-- =====================================================================
--  RDV Medical : jeu de données d'exemple
-- ---------------------------------------------------------------------
--  À importer APRÈS schema.sql. Équivalent de `php artisan db:seed`.
--    mysql -u root -p prise_rendezvous_medical_db < database/sql/donnees.sql
--
--  Contenu : 3 centres, 5 spécialités, 10 médecins et leurs horaires,
--  3 absences (maladie, congés, autre) et un compte de test :
--    patient@test.com / password
--
--  Les dates d'absence sont calculées par MySQL au moment de l'import
--  (CURDATE()), comme le fait le seeder Laravel : il y a donc toujours
--  des absences à tester, quelle que soit la date d'import.
-- =====================================================================

SET NAMES utf8mb4;

/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
LOCK TABLES `centres` WRITE;
/*!40000 ALTER TABLE `centres` DISABLE KEYS */;
INSERT INTO `centres` (`id`, `nom`, `adresse`, `telephone`, `email`, `created_at`, `updated_at`) VALUES (1,'Centre Médical Pasteur','12 avenue de la République','+224 620 00 00 01','contact@cm-pasteur.test','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `centres` (`id`, `nom`, `adresse`, `telephone`, `email`, `created_at`, `updated_at`) VALUES (2,'Clinique Les Palmiers','45 boulevard du Commerce','+224 620 00 00 02','accueil@clinique-palmiers.test','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `centres` (`id`, `nom`, `adresse`, `telephone`, `email`, `created_at`, `updated_at`) VALUES (3,'Polyclinique Espoir','8 rue de la Santé','+224 620 00 00 03','info@polyclinique-espoir.test','2026-10-27 11:41:49','2026-10-27 11:41:49');
/*!40000 ALTER TABLE `centres` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `specialites` WRITE;
/*!40000 ALTER TABLE `specialites` DISABLE KEYS */;
INSERT INTO `specialites` (`id`, `nom`, `created_at`, `updated_at`) VALUES (1,'Médecine générale','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `specialites` (`id`, `nom`, `created_at`, `updated_at`) VALUES (2,'Cardiologie','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `specialites` (`id`, `nom`, `created_at`, `updated_at`) VALUES (3,'Pédiatrie','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `specialites` (`id`, `nom`, `created_at`, `updated_at`) VALUES (4,'Dermatologie','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `specialites` (`id`, `nom`, `created_at`, `updated_at`) VALUES (5,'Gynécologie','2026-10-27 11:41:49','2026-10-27 11:41:49');
/*!40000 ALTER TABLE `specialites` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `medecins` WRITE;
/*!40000 ALTER TABLE `medecins` DISABLE KEYS */;
INSERT INTO `medecins` (`id`, `nom`, `prenom`, `specialite_id`, `centre_id`, `created_at`, `updated_at`) VALUES (1,'Camara','Mariama',1,1,'2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `medecins` (`id`, `nom`, `prenom`, `specialite_id`, `centre_id`, `created_at`, `updated_at`) VALUES (2,'Diallo','Ibrahima',2,1,'2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `medecins` (`id`, `nom`, `prenom`, `specialite_id`, `centre_id`, `created_at`, `updated_at`) VALUES (3,'Bah','Fatoumata',3,1,'2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `medecins` (`id`, `nom`, `prenom`, `specialite_id`, `centre_id`, `created_at`, `updated_at`) VALUES (4,'Sylla','Mamadou',1,2,'2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `medecins` (`id`, `nom`, `prenom`, `specialite_id`, `centre_id`, `created_at`, `updated_at`) VALUES (5,'Kouyaté','Aïssatou',4,2,'2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `medecins` (`id`, `nom`, `prenom`, `specialite_id`, `centre_id`, `created_at`, `updated_at`) VALUES (6,'Touré','Sékou',2,2,'2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `medecins` (`id`, `nom`, `prenom`, `specialite_id`, `centre_id`, `created_at`, `updated_at`) VALUES (7,'Barry','Hadja',5,2,'2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `medecins` (`id`, `nom`, `prenom`, `specialite_id`, `centre_id`, `created_at`, `updated_at`) VALUES (8,'Condé','Alpha',1,3,'2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `medecins` (`id`, `nom`, `prenom`, `specialite_id`, `centre_id`, `created_at`, `updated_at`) VALUES (9,'Soumah','Kadiatou',3,3,'2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `medecins` (`id`, `nom`, `prenom`, `specialite_id`, `centre_id`, `created_at`, `updated_at`) VALUES (10,'Keïta','Moussa',5,3,'2026-10-27 11:41:49','2026-10-27 11:41:49');
/*!40000 ALTER TABLE `medecins` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `disponibilites` WRITE;
/*!40000 ALTER TABLE `disponibilites` DISABLE KEYS */;
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (1,1,1,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (2,1,1,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (3,1,2,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (4,1,2,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (5,1,3,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (6,1,3,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (7,1,4,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (8,1,4,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (9,1,5,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (10,1,5,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (11,2,1,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (12,2,1,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (13,2,2,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (14,2,2,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (15,2,3,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (16,2,3,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (17,2,4,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (18,2,4,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (19,2,5,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (20,2,5,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (21,3,1,'09:00:00','13:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (22,3,3,'09:00:00','13:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (23,3,5,'09:00:00','13:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (24,4,1,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (25,4,1,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (26,4,2,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (27,4,2,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (28,4,3,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (29,4,3,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (30,4,4,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (31,4,4,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (32,4,5,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (33,4,5,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (34,5,2,'08:30:00','12:30:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (35,5,4,'08:30:00','12:30:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (36,5,6,'08:30:00','12:30:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (37,6,1,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (38,6,1,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (39,6,2,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (40,6,2,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (41,6,3,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (42,6,3,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (43,6,4,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (44,6,4,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (45,6,5,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (46,6,5,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (47,7,1,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (48,7,1,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (49,7,2,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (50,7,2,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (51,7,3,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (52,7,3,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (53,7,4,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (54,7,4,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (55,7,5,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (56,7,5,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (57,8,1,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (58,8,1,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (59,8,2,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (60,8,2,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (61,8,3,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (62,8,3,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (63,8,4,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (64,8,4,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (65,8,5,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (66,8,5,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (67,9,1,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (68,9,1,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (69,9,2,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (70,9,2,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (71,9,3,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (72,9,3,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (73,9,4,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (74,9,4,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (75,9,5,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (76,9,5,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (77,10,1,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (78,10,1,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (79,10,2,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (80,10,2,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (81,10,3,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (82,10,3,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (83,10,4,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (84,10,4,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (85,10,5,'08:00:00','12:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
INSERT INTO `disponibilites` (`id`, `medecin_id`, `jour_semaine`, `heure_debut`, `heure_fin`, `created_at`, `updated_at`) VALUES (86,10,5,'14:00:00','17:00:00','2026-10-27 11:41:49','2026-10-27 11:41:49');
/*!40000 ALTER TABLE `disponibilites` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES (1,'Patient Test','patient@test.com',NULL,'$2y$10$kNNVNOnsW.XSxSUXiqzV5.N/EzHF8M51dD6xgCwaSqXsslXAbQMnO',NULL,'2026-10-27 11:41:49','2026-10-27 11:41:49');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

-- Absences de démonstration, calculées à partir du jour de l'import
-- (WEEKDAY : 0 = lundi ... 6 = dimanche)

-- Dr Diallo (Cardiologie) : maladie, d'aujourd'hui à J+2
INSERT INTO `absences` (`medecin_id`, `date_debut`, `date_fin`, `motif`, `created_at`, `updated_at`)
SELECT `id`, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 2 DAY), 'maladie', NOW(), NOW()
FROM `medecins` WHERE `nom` = 'Diallo';

-- Dr Sylla (Médecine générale) : congés, du lundi au dimanche de la semaine prochaine
INSERT INTO `absences` (`medecin_id`, `date_debut`, `date_fin`, `motif`, `created_at`, `updated_at`)
SELECT `id`,
       DATE_ADD(CURDATE(), INTERVAL 7 - WEEKDAY(CURDATE()) DAY),
       DATE_ADD(CURDATE(), INTERVAL 13 - WEEKDAY(CURDATE()) DAY),
       'conges', NOW(), NOW()
FROM `medecins` WHERE `nom` = 'Sylla';

-- Dr Condé (Médecine générale) : autre, le prochain jour ouvré
INSERT INTO `absences` (`medecin_id`, `date_debut`, `date_fin`, `motif`, `created_at`, `updated_at`)
SELECT `id`, j.jour, j.jour, 'autre', NOW(), NOW()
FROM `medecins`
CROSS JOIN (
    SELECT DATE_ADD(CURDATE(), INTERVAL CASE WEEKDAY(CURDATE()) WHEN 4 THEN 3 WHEN 5 THEN 2 ELSE 1 END DAY) AS jour
) AS j
WHERE `nom` = 'Condé';

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
