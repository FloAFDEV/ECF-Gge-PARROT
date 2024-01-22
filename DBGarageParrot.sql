-- Suppression de la base de données si elle existe déjà 
DROP DATABASE IF EXISTS DBGarageParrot;

-- Création d'une nouvelle base de données avec le nom voulu 
CREATE DATABASE DBGarageParrot CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Utilisation de la base de données nouvellement créée 
USE DBGarageParrot;

-- Suppression des tables si elles existent déjà 
DROP TABLE IF EXISTS ModelsManufactureYears, CarsEnergy, CarsOptions, Models, Images, MessageAnnonce, Cars, ResetPassword, GarageServices, CarAnnonce, Testimonials, Users, EnergyType, ManufactureYears, Brands, Opening, Garage, Options;

-- Création de la table Options / Relation N.N entre Cars et Options (à travers la table intermédiaire CarsOptions) 
CREATE TABLE Options
(
    Id_Options   INT AUTO_INCREMENT PRIMARY KEY,
    options_name VARCHAR(255)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Création de la table Garage / Relation 1:N entre Garage et Opening & Relation 1:N entre Garage et GarageServices 
-- Relation 1:N entre Garage et CarAnnonce & relation 1:N entre Garage et Users 
CREATE TABLE Garage
(
    Id_Garage   INT AUTO_INCREMENT PRIMARY KEY,
    garageName  VARCHAR(255) NOT NULL,
    address     VARCHAR(255) NOT NULL,
    phoneNumber VARCHAR(20)  NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Création de la table Opening / Relation 1:N entre Garage et Opening 
CREATE TABLE Opening
(
    Id_Opening   INT AUTO_INCREMENT PRIMARY KEY,
    storeStatus  VARCHAR(50),
    dayOfWeek    ENUM ( 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche' ) NOT NULL,
    morningOpen  TIME,
    morningClose TIME,
    eveningOpen  TIME,
    eveningClose TIME,
    Id_Garage    INT                                                                              NOT NULL,
    FOREIGN KEY (Id_Garage) REFERENCES Garage (Id_Garage)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Création de la table Brands (Marques) Relation 1:N entre Brands et Models 
CREATE TABLE Brands
(
    Id_Brand       INT AUTO_INCREMENT PRIMARY KEY,
    brand_name     VARCHAR(255) NOT NULL,
    brand_logo_url VARCHAR(255)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Création de la table ManufactureYears / Relation N.N entre Models et ManufactureYears (à travers la table intermédiaire ModelsManufactureYears) 
CREATE TABLE ManufactureYears
(
    Id_ManufactureYears INT AUTO_INCREMENT PRIMARY KEY,
    manufacture_year    YEAR NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Création de la table EnergyType (Types de carburant) / Relation N.N entre Cars et EnergyType (à travers la table intermédiaire CarsEnergy) 
CREATE TABLE EnergyType
(
    Id_EnergyType INT AUTO_INCREMENT PRIMARY KEY,
    fuel_type     ENUM ('Essence', 'Hybride', 'Electrique', 'Diesel') NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Création de la table Users / Relation 1:N entre Users et Testimonials & Relation 1:N entre Users et ResetPassword 
-- Relation 1:N entre Users et Garage & Relation 1:N entre Users et MessageAnnonce 
CREATE TABLE Users
(
    Id_Users         INT AUTO_INCREMENT PRIMARY KEY,
    email            VARCHAR(320)                 NOT NULL UNIQUE,
    name             VARCHAR(255)                 NOT NULL,
    phone            VARCHAR(20)                  NOT NULL,
    pseudo           VARCHAR(255)                 NOT NULL UNIQUE,
    role             VARCHAR(20) DEFAULT 'client' NOT NULL CHECK ( role IN ('superAdmin', 'admin', 'employe', 'client') ),
    password         VARCHAR(255)                 NOT NULL,
    primaryGarage_Id INT,
    Id_Garage        INT                          NOT NULL,
    FOREIGN KEY (Id_Garage) REFERENCES Garage (Id_Garage)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Création de la table Testimonials / Relation 1:N entre Users et Testimonials 
CREATE TABLE Testimonials
(
    Id_Testimonials INT AUTO_INCREMENT PRIMARY KEY,
    pseudo          VARCHAR(255) NOT NULL,
    userEmail       VARCHAR(320) NOT NULL,
    message         TEXT         NOT NULL,
    valid           BOOLEAN               DEFAULT false,
    note            DECIMAL(3, 1) CHECK ( note >= 0 AND note <= 6 ),
    createdAt       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Id_Users        INT          NOT NULL,
    FOREIGN KEY (Id_Users) REFERENCES Users (Id_Users)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Création de la table CarAnnonce / Relation 1:N entre Cars et CarAnnonce & Relation 1:N entre CarAnnonce et Images 
-- Relation 1:N entre CarAnnonce et MessageAnnonce & Relation 1:N entre Garage et CarAnnonce 
CREATE TABLE CarAnnonce
(
    Id_CarAnnonce INT AUTO_INCREMENT PRIMARY KEY,
    title         VARCHAR(255) NOT NULL,
    createdAt     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    valid         BOOLEAN   DEFAULT TRUE,
    Id_Garage     INT,
    FOREIGN KEY (Id_Garage) REFERENCES Garage (Id_Garage)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Création de la table GarageServices / Relation 1:N entre Garage et GarageServices 
CREATE TABLE GarageServices
(
    Id_GarageService INT AUTO_INCREMENT PRIMARY KEY,
    serviceName      VARCHAR(255) NOT NULL,
    description      TEXT,
    image_url        VARCHAR(255) NOT NULL,
    phoneNumber      VARCHAR(20)  NOT NULL,
    Id_Garage        INT          NOT NULL,
    FOREIGN KEY (Id_Garage) REFERENCES Garage (Id_Garage)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Création de la table ResetPassword / Relation 1:N entre Users et ResetPassword 
CREATE TABLE ResetPassword
(
    Id_ResetPassword INT AUTO_INCREMENT PRIMARY KEY,
    selector         VARCHAR(20)  NOT NULL,
    hashed_token     VARCHAR(100) NOT NULL,
    requested_at     DATETIME     NOT NULL,
    expires_at       DATETIME     NOT NULL,
    user_id          INT          NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (Id_Users)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Création de la table Cars / Relation 1:N entre Models et Cars & Relation N.N entre Cars et EnergyType (à travers la table intermédiaire CarsEnergy) 
-- Relation N.N entre Cars et Options (à travers la table intermédiaire CarsOptions) 
-- Relation 1.N entre Cars et CarAnnonce 
CREATE TABLE Cars
(
    Id_Cars        INT AUTO_INCREMENT PRIMARY KEY,
    mileage        INT,
    registration   VARCHAR(20),
    price          DECIMAL(10, 2) NOT NULL,
    description    TEXT,
    main_image_url VARCHAR(255)   NOT NULL,
    Id_CarAnnonce  INT,
    FOREIGN KEY (Id_CarAnnonce) REFERENCES CarAnnonce (Id_CarAnnonce)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Création de la table MessageAnnonce / Relation 1:N entre CarAnnonce et MessageAnnonce 
-- Relation 1:N entre MessageAnnonce et Users 
CREATE TABLE MessageAnnonce
(
    Id_MessageAnnonce INT AUTO_INCREMENT PRIMARY KEY,
    userName          VARCHAR(255) NOT NULL,
    userEmail         VARCHAR(320) NOT NULL,
    userPhone         VARCHAR(20)  NOT NULL,
    message           TEXT         NOT NULL,
    createdAt         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Id_Users          INT          NOT NULL,
    Id_CarAnnonce     INT          NOT NULL,
    FOREIGN KEY (Id_Users) REFERENCES Users (Id_Users),
    FOREIGN KEY (Id_CarAnnonce) REFERENCES CarAnnonce (Id_CarAnnonce)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Création de la nouvelle table Images / Relation 1:N entre CarAnnonce et Images 
CREATE TABLE Images
(
    Id_Image      INT AUTO_INCREMENT PRIMARY KEY,
    image_url     VARCHAR(255) NOT NULL,
    imageName     VARCHAR(50)  NOT NULL,
    Id_CarAnnonce INT,
    FOREIGN KEY (Id_CarAnnonce) REFERENCES CarAnnonce (Id_CarAnnonce)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Création de la table Models (Modèles) / Relation 1:N entre Models et Cars 
-- Relation 1:N entre Models et Brands 
-- Relation N.N entre Models et ManufactureYears (à travers la table intermédiaire ModelsManufactureYears) 
CREATE TABLE Models
(
    Id_Model       INT AUTO_INCREMENT PRIMARY KEY,
    model_name     VARCHAR(255) NOT NULL,
    category_model VARCHAR(255) NOT NULL,
    Id_Cars        INT,
    Id_Brand       INT,
    FOREIGN KEY (Id_Cars) REFERENCES Cars (Id_Cars),
    FOREIGN KEY (Id_Brand) REFERENCES Brands (Id_Brand)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

-- Création de la table CarsOptions / Relation N.N entre Cars et Options (à travers la table intermédiaire CarsOptions) 
CREATE TABLE CarsOptions
(
    Id_Cars    INT,
    Id_Options INT,
    PRIMARY KEY (Id_Cars, Id_Options),
    FOREIGN KEY (Id_Cars) REFERENCES Cars (Id_Cars),
    FOREIGN KEY (Id_Options) REFERENCES Options (Id_Options)
) ENGINE = InnoDB;

-- Création de la table CarsEnergy / Relation N.N entre Cars et EnergyType (à travers la table intermédiaire CarsEnergy) 
CREATE TABLE CarsEnergy
(
    Id_EnergyType INT,
    Id_Cars       INT,
    PRIMARY KEY (Id_EnergyType, Id_Cars),
    FOREIGN KEY (Id_EnergyType) REFERENCES EnergyType (Id_EnergyType),
    FOREIGN KEY (Id_Cars) REFERENCES Cars (Id_Cars)
) ENGINE = InnoDB;

-- Création de la table ModelsManufactureYears / Relation N.N entre Models et ManufactureYears (à travers la table intermédiaire ModelsManufactureYears) 
CREATE TABLE ModelsManufactureYears
(
    Id_Model            INT,
    Id_ManufactureYears INT,
    PRIMARY KEY (Id_Model, Id_ManufactureYears),
    FOREIGN KEY (Id_Model) REFERENCES Models (Id_Model) ON DELETE CASCADE,
    FOREIGN KEY (Id_ManufactureYears) REFERENCES ManufactureYears (Id_ManufactureYears)
) ENGINE = InnoDB;




-- Modification des tables + ajout de données fictives

    -- Dans EnergyType
INSERT INTO EnergyType (fuel_type) VALUES
('Essence'),
('Hybride'),
('Electrique'),
('Diesel');


-- Dans ManufactureYears
INSERT INTO ManufactureYears (manufacture_year)
VALUES
(2017),
(2018),
(2019),
(2020),
(2021),
(2022),
(2023);

-- Dans Brands ajout de marques
INSERT INTO Brands (brand_name, brand_logo_url)
VALUES ('Peugeot', NULL),
       ('Renault', NULL),
       ('Mercedes-Benz', NULL),
       ('BMW', NULL),
       ('Toyota', NULL),
       ('Honda', NULL),
       ('Volkswagen', NULL),
       ('Audi', NULL),
       ('Ford', NULL),
       ('Nissan', NULL),
       ('Hyundai', NULL),
       ('Lexus', NULL),
       ('Mazda', NULL),
       ('Volvo', NULL),
       ('Tesla', NULL),
       ('Suzuki', NULL),
       ('Kia', NULL),
       ('Seat', NULL),
       ('MG', NULL);

-- Dans Garage
INSERT INTO garage (garageName, address, phoneNumber)
VALUES ('Garage V.PARROT',
        '4 Impasse Paul Mesplé, 31000 TOULOUSE',
        '05 61 41 70 70');

-- Dans CarAnnonce
INSERT INTO CarAnnonce (title, createdAt, valid, Id_Garage)
VALUES
('Annonce 1', '2023-12-01 14:30:00', TRUE, 1),
('Annonce 2', '2023-11-15 09:45:00', TRUE, 1),
('Annonce 3', '2023-10-20 18:20:00', TRUE, 1),
('Annonce 4', '2023-09-05 12:00:00', TRUE, 1),
('Annonce 5', '2023-08-10 21:30:00', TRUE, 1),
('Annonce 6', '2023-07-25 16:15:00', TRUE, 1);


-- Dans Cars
INSERT INTO Cars (mileage, registration, price, description, main_image_url, Id_CarAnnonce)
VALUES
(50000, 'AB123CD', 15000.00, 'Description de la voiture 1', 'image1.jpg', 7),
(60000, 'EF456GH', 18000.00, 'Description de la voiture 2', 'image2.jpg', 8),
(70000, 'IJ789KL', 20000.00, 'Description de la voiture 3', 'image3.jpg', 9),
(80000, 'MN012OP', 22000.00, 'Description de la voiture 4', 'image4.jpg', 10),
(90000, 'QR345ST', 25000.00, 'Description de la voiture 5', 'image5.jpg', 11),
(100000, 'UV678WX', 28000.00, 'Description de la voiture 6', 'image6.jpg', 12);

-- Dans Models
INSERT INTO Models (model_name, category_model, Id_Cars, Id_Brand)
VALUES
('208', 'Citadine', 13, 1),
('3008', 'SUV', 14, 1),
('CLIO', 'Citadine', 15, 2),
('KADJAR', 'SUV', 16, 2),
('GLE', 'SUV', 17, 3),
('GLC', 'SUV', 18, 3);


-- Dans Options
INSERT INTO Options (options_name)
VALUES ('Fermeture centralisée'),
       ('Boîte manuelle'),
       ('Boîte automatique'),
       ('Coupé'),
       ('Blanc'),
       ('Noir'),
       ('Argent'),
       ('Bleu'),
       ('Rouge'),
       ('Marron'),
       ('Climatisation automatique'),
       ('Système audio'),
       ('Toit ouvrant'),
       ('ABS'),
       ('Airbags'),
       ('Contrôle de stabilité ESP'),
       ('Intérieur cuir'),
       ('Intérieur velour'),
       ('Intérieur tissu'),
       ('Système de navigation GPS'),
       ('Attelage de remorque');

-- Dans Opening
INSERT INTO Opening (storeStatus,
                     dayOfWeek,
                     morningOpen,
                     morningClose,
                     eveningOpen,
                     eveningClose,
                     Id_Garage)
VALUES ('Ouvert',
        'Lundi',
        '09:00:00',
        '18:00:00',
        '09:00:00',
        '18:00:00',
        1),
       ('Ouvert',
        'Mardi',
        '09:00:00',
        '18:00:00',
        '09:00:00',
        '18:00:00',
        1),
       ('Ouvert',
        'Mercredi',
        '09:00:00',
        '18:00:00',
        '09:00:00',
        '18:00:00',
        1),
       ('Ouvert',
        'Jeudi',
        '09:00:00',
        '18:00:00',
        '09:00:00',
        '18:00:00',
        1),
       ('Ouvert',
        'Vendredi',
        '09:00:00',
        '18:00:00',
        '09:00:00',
        '18:00:00',
        1),
       ('Ouvert',
        'Samedi',
        '09:00:00',
        '12:00:00',
        NULL,
        NULL,
        1),
       ('Fermé', 'Dimanche', NULL, NULL, NULL, NULL, 1);


-- Dans GarageServices
INSERT INTO GarageServices (serviceName,
                            description,
                            image_url,
                            phoneNumber,
                            Id_Garage)
VALUES ('Réparation mécanique',
        'Service complet de réparation automobile, mécanique et carrosserie',
        'service_image1.jpg',
        '05 61 41 70 71',
        1),
       ('Entretien régulier',
        'Entretien et maintenance préventive',
        'service_image2.jpg',
        '05 61 41 70 72',
        1);

-- Dans ModelsManufactureYears
INSERT INTO ModelsManufactureYears (Id_Model, Id_ManufactureYears)
VALUES
(153, 1),  -- 208 - 2020
(154, 1),  -- 3008 - 2020
(155, 2),  -- CLIO - 2021
(156, 2),  -- KADJAR - 2021
(157, 2),  -- GLE - 2021
(158, 3);  -- GLC - 2022


-- Dans Images
INSERT INTO Images (image_url, imageName, Id_CarAnnonce)
VALUES
('image1.jpg', 'Image 1', 7),
('image2.jpg', 'Image 2', 8),
('image3.jpg', 'Image 3', 9),
('image4.jpg', 'Image 4', 10),
('image5.jpg', 'Image 5', 11),
('image6.jpg', 'Image 6', 12);

-- Dans CarsEnergy
INSERT INTO CarsEnergy (Id_Cars, Id_EnergyType)
VALUES
    (13, 1),
    (14, 2),
    (15, 3),
    (16, 4),
    (17, 1),
    (18, 2);

-- Dans CarsOptions
INSERT INTO CarsOptions (Id_Cars, Id_Options)
VALUES
    -- Voiture avec Id_Cars 13
    (13, 1),  -- Option avec Id_Options 1
    (13, 2),  -- Option avec Id_Options 2
    (13, 3),  -- Option avec Id_Options 3

    -- Voiture avec Id_Cars 14
    (14, 2),  -- Option avec Id_Options 2
    (14, 4),  -- Option avec Id_Options 4
    (14, 5),  -- Option avec Id_Options 5

    -- Voiture avec Id_Cars 15
    (15, 3),  -- Option avec Id_Options 3
    (15, 5),  -- Option avec Id_Options 5
    (15, 6),  -- Option avec Id_Options 6

    -- Ajoutez d'autres associations ici

    -- Voiture avec Id_Cars 18
    (18, 20), -- Option avec Id_Options 20
    (18, 21); -- Option avec Id_Options 21

-- Dans Users (avec rôle 'client')
INSERT INTO Users (email, name, phone, pseudo, role, password, primaryGarage_Id, Id_Garage)
VALUES
    ('chuck@example.com', 'Chuck Norris', '03 68 17 60 33', 'Chuck', 'client', 'password', 1, 1),
    ('neo@example.com', 'Néo', '08 35 43 58 02', 'Néo', 'client', 'password', 1, 1);


-- Dans témoignages (les id sont récupérés dans PHPMyAdmin)
INSERT INTO Testimonials (pseudo, userEmail, message, valid, note, createdAt, Id_Users)
VALUES
    ('Chuck Norris', 'chuck@example.com', 'Les témoignages n\'ont pas besoin d\'être validés, ce sont les témoignages qui valident Chuck Norris.', true, 5.0, '2024-01-20 12:34:56', 3),
    ('Néo', 'neo@example.com', 'La cuillère n\'éxiste pas dans ces témoignages, juste la Matrice.', true, 4.5, '2024-01-20 14:45:30', 4);


-- Sélection des noms de marque et de modèle
-- SELECT Brands.brand_name, Models.model_name
-- FROM Brands
-- INNER JOIN Models ON Brands.Id_Brand = Models.Id_Brand;


-- Sélection des annonces de voiture valides
-- SELECT * FROM CarAnnonce WHERE valid = TRUE;


-- Sélection des informations sur les voitures avec leur annonce associée
-- SELECT Cars.*, CarAnnonce.title
-- FROM Cars
-- JOIN CarAnnonce ON Cars.Id_CarAnnonce = CarAnnonce.Id_CarAnnonce;


-- Sélection des services d'un garage spécifique (Id_Garage = 1)
-- SELECT GarageServices.*
-- FROM GarageServices
-- WHERE GarageServices.Id_Garage = 1;


-- Sélection des images liées à une annonce de voiture spécifique (Id_CarAnnonce = 5)
-- SELECT Images.*
-- FROM Images
-- WHERE Images.Id_CarAnnonce = 5;


-- Sélection des informations détaillées sur les voitures avec leurs marques et annonces associées
-- SELECT
--     Brands.brand_name,
--     Models.model_name,
--     Cars.mileage,
--     Cars.registration,
--     Cars.price,
--     Cars.description AS car_description,
--     Cars.main_image_url,
--     CarAnnonce.title AS car_announcement_title,
--     CarAnnonce.createdAt AS car_announcement_createdAt
-- FROM
--     Brands
--     LEFT JOIN Models ON Brands.Id_Brand = Models.Id_Brand
--     LEFT JOIN Cars ON Models.Id_Cars = Cars.Id_Cars
--     LEFT JOIN CarAnnonce ON Cars.Id_CarAnnonce = CarAnnonce.Id_CarAnnonce;


-- Sélection des identifiants uniques d'annonces de voiture
-- SELECT DISTINCT Id_CarAnnonce FROM Cars;


-- Sélection des informations sur les voitures avec leurs marques associées
-- SELECT
--     Cars.mileage,
--     Cars.registration,
--     Cars.price,
--     Cars.description,
--     Cars.main_image_url,
--     Brands.brand_name,
--     Models.model_name
-- FROM Cars
-- INNER JOIN Models ON Models.Id_Cars = Cars.Id_Cars
-- INNER JOIN Brands ON Models.Id_Brand = Brands.Id_Brand;


-- Sélection de toutes les colonnes de la table Cars
-- SELECT * FROM Cars;
-- SELECT * FROM Models;
-- SELECT * FROM Brands;

-- Sélection des informations détaillées sur les marques, modèles et voitures
-- SELECT
--     B.Id_Brand,
--     B.brand_name,
--     M.Id_Model,
--     M.model_name,
--     M.category_model,
--     C.Id_Cars,
--     C.mileage,
--     C.registration,
--     C.price,
--     C.description,
--     C.main_image_url
-- FROM
--     Models M
-- INNER JOIN Brands B ON M.Id_Brand = B.Id_Brand
-- INNER JOIN Cars C ON M.Id_Cars = C.Id_Cars;

SELECT
    Cars.Id_Cars,
    Cars.mileage,
    Cars.registration,
    Cars.price,
    Cars.description,
    Cars.main_image_url,
    Models.model_name,
    ManufactureYears.manufacture_year,
    EnergyType.fuel_type,
    Brands.brand_name,
    CarAnnonce.title,
    CarAnnonce.createdAt,
    CarAnnonce.valid,
    Garage.garageName
FROM
    Cars
INNER JOIN Models ON Models.Id_Cars = Cars.Id_Cars
INNER JOIN Brands ON Models.Id_Brand = Brands.Id_Brand
INNER JOIN ModelsManufactureYears ON Models.Id_Model = ModelsManufactureYears.Id_Model
INNER JOIN ManufactureYears ON ModelsManufactureYears.Id_ManufactureYears = ManufactureYears.Id_ManufactureYears
INNER JOIN CarsEnergy ON Cars.Id_Cars = CarsEnergy.Id_Cars
INNER JOIN EnergyType ON CarsEnergy.Id_EnergyType = EnergyType.Id_EnergyType
LEFT JOIN CarAnnonce ON Cars.Id_Cars = CarAnnonce.Id_CarAnnonce
LEFT JOIN Garage ON CarAnnonce.Id_Garage = Garage.Id_Garage;

SELECT
    CarAnnonce.title,
    CarAnnonce.createdAt,
    CarAnnonce.valid,
    Garage.garageName
FROM
    CarAnnonce
INNER JOIN
    Garage ON CarAnnonce.Id_Garage = Garage.Id_Garage;


SELECT * FROM ModelsManufactureYears;


SELECT * FROM Cars WHERE Id_Cars = (SELECT Id_Cars FROM CarAnnonce WHERE Id_CarAnnonce = 7);


SELECT
    Cars.*,
    Models.model_name,
    Models.category_model,
    Brands.brand_name,
    Brands.brand_logo_url,
    ManufactureYears.manufacture_year,
    EnergyType.fuel_type,
    CarAnnonce.title AS annonce_title,
    CarAnnonce.createdAt AS annonce_createdAt,
    Garage.garageName AS annonce_garageName
FROM
    Cars
INNER JOIN Models ON Cars.Id_Cars = Models.Id_Cars
INNER JOIN Brands ON Models.Id_Brand = Brands.Id_Brand
INNER JOIN ModelsManufactureYears ON Models.Id_Model = ModelsManufactureYears.Id_Model
INNER JOIN ManufactureYears ON ModelsManufactureYears.Id_ManufactureYears = ManufactureYears.Id_ManufactureYears
INNER JOIN CarsEnergy ON Cars.Id_Cars = CarsEnergy.Id_Cars
INNER JOIN EnergyType ON CarsEnergy.Id_EnergyType = EnergyType.Id_EnergyType
LEFT JOIN CarAnnonce ON Cars.Id_CarAnnonce = CarAnnonce.Id_CarAnnonce
LEFT JOIN Garage ON CarAnnonce.Id_Garage = Garage.Id_Garage;






