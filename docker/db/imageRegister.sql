CREATE TABLE `ImageRegister` (
  `idImage` CHAR(36) NOT NULL,
  `imageName` VARCHAR(255) NOT NULL,
  `imagePath` VARCHAR(255) NOT NULL,
  `imageExt`  VARCHAR(255) NULL,
  `imageFilter`  VARCHAR(255) NULL,
  `tags`  VARCHAR(255) NULL,
  `description`  VARCHAR(255) NULL,
  PRIMARY KEY (`idImage`)
) ENGINE=INNODB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;