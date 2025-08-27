-- Add consejero_camara field to empresas table
-- This field tracks if the company representative is a CANACO counselor

USE `canaco_eventos`;

ALTER TABLE `empresas` ADD COLUMN `consejero_camara` TINYINT(1) NOT NULL DEFAULT 0 AFTER `numero_afiliacion`;