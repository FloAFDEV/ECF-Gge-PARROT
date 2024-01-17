-- Suppression de la base de données si elle existe déjà
DROP DATABASE IF EXISTS DBGarageParrot;
-- Création d'une nouvelle base de données avec le nom voulu
CREATE DATABASE DBGarageParrot CHARACTER
SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- Utilisation de la base de données nouvellement créée
USE DBGarageParrot;
-- Suppression des tables si elles existent déjà
DROP TABLE IF EXISTS ModelsManufactureYears,
CarsEnergy,
CarsOptions,
Models,
Images,
MessageAnnonce,
Cars,
ResetPassword,
GarageServices,
CarAnnonce,
Testimonials,
Users,
EnergyType,
ManufactureYears,
Brands,
Opening,
Garage,
Options;
-- Création de la table Options / Relation N.N entre Cars et Options (à travers la table intermédiaire CarsOptions)
CREATE TABLE Options (
    Id_Options INT AUTO_INCREMENT PRIMARY KEY,
    options_name VARCHAR (255)
) DEFAULT CHARACTER
    SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;
-- Création de la table Garage / Relation 1:N entre Garage et Opening & Relation 1:N entre Garage et GarageServices
-- Relation 1:N entre Garage et CarAnnonce & relation 1:N entre Garage et Users
CREATE TABLE Garage (
    Id_Garage INT AUTO_INCREMENT PRIMARY KEY,
    garageName VARCHAR (255) NOT NULL,
    address VARCHAR (255) NOT NULL,
    phoneNumber VARCHAR (20) NOT NULL
) DEFAULT CHARACTER
        SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;
-- Création de la table Opening / Relation 1:N entre Garage et Opening
CREATE TABLE Opening (
    Id_Opening INT AUTO_INCREMENT PRIMARY KEY,
    storeStatus VARCHAR (50),
    dayOfWeek ENUM (
        'Lundi',
        'Mardi',
        'Mercredi',
        'Jeudi',
        'Vendredi',
        'Samedi',
        'Dimanche'
    ) NOT NULL,
    morningOpen TIME,
    morningClose TIME,
    eveningOpen TIME,
    eveningClose TIME,
    Id_Garage INT NOT NULL,
    FOREIGN KEY (Id_Garage) REFERENCES Garage (Id_Garage)
) DEFAULT CHARACTER
            SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;
-- Création de la table Brands (Marques) Relation 1:N entre Brands et Models
CREATE TABLE Brands (
    Id_Brand INT AUTO_INCREMENT PRIMARY KEY,
    brand_name VARCHAR (255) NOT NULL,
    brand_logo_url VARCHAR (255)
) DEFAULT CHARACTER
                SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;
-- Création de la table ManufactureYears / Relation N.N entre Models et ManufactureYears (à travers la table intermédiaire ModelsManufactureYears)
CREATE TABLE ManufactureYears (
    Id_ManufactureYears INT AUTO_INCREMENT PRIMARY KEY,
    manufacture_year YEAR NOT NULL
) DEFAULT CHARACTER
                    SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;
-- Création de la table EnergyType (Types de carburant) / Relation N.N entre Cars et EnergyType (à travers la table intermédiaire CarsEnergy)
CREATE TABLE EnergyType (
    Id_EnergyType INT AUTO_INCREMENT PRIMARY KEY,
    fuel_type ENUM ('Essence', 'Hybride', 'Electrique', 'Diesel') NOT NULL
) DEFAULT CHARACTER
                        SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;
-- Création de la table Users / Relation 1:N entre Users et Testimonials & Relation 1:N entre Users et ResetPassword
-- Relation 1:N entre Users et Garage & Relation 1:N entre Users et MessageAnnonce
CREATE TABLE Users (
    Id_Users INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR (320) NOT NULL UNIQUE,
    name VARCHAR (255) NOT NULL,
    phone VARCHAR (20) NOT NULL,
    pseudo VARCHAR (255) NOT NULL UNIQUE,
    role VARCHAR (20) DEFAULT 'client' NOT NULL CHECK (
        role IN ('superAdmin', 'admin', 'employe', 'client')
    ),
    password VARCHAR (255) NOT NULL,
    primaryGarage_Id INT,
    Id_Garage INT NOT NULL,
    FOREIGN KEY (Id_Garage) REFERENCES Garage (Id_Garage)
) DEFAULT CHARACTER
                            SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;
-- Création de la table Testimonials / Relation 1:N entre Users et Testimonials
CREATE TABLE Testimonials (
    Id_Testimonials INT AUTO_INCREMENT PRIMARY KEY,
    pseudo VARCHAR (255) NOT NULL,
    userEmail VARCHAR (320) NOT NULL,
    message TEXT NOT NULL,
    valid BOOLEAN DEFAULT false,
    note DECIMAL (3, 1) CHECK (
        note >= 0
        AND note <= 6
    ),
    createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Id_Users INT NOT NULL,
    FOREIGN KEY (Id_Users) REFERENCES Users (Id_Users)
) DEFAULT CHARACTER
                                SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;
-- Création de la table CarAnnonce / Relation 1:N entre Cars et CarAnnonce & Relation 1:N entre CarAnnonce et Images
-- Relation 1:N entre CarAnnonce et MessageAnnonce & Relation 1:N entre Garage et CarAnnonce
CREATE TABLE CarAnnonce (
    Id_CarAnnonce INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR (255) NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    valid BOOLEAN DEFAULT TRUE,
    Id_Garage INT,
    FOREIGN KEY (Id_Garage) REFERENCES Garage (Id_Garage)
) DEFAULT CHARACTER
                                    SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;
-- Création de la table GarageServices / Relation 1:N entre Garage et GarageServices
CREATE TABLE GarageServices (
    Id_GarageService INT AUTO_INCREMENT PRIMARY KEY,
    serviceName VARCHAR (255) NOT NULL,
    description TEXT,
    image_url VARCHAR (255) NOT NULL,
    phoneNumber VARCHAR (20) NOT NULL,
    Id_Garage INT NOT NULL,
    FOREIGN KEY (Id_Garage) REFERENCES Garage (Id_Garage)
) DEFAULT CHARACTER
                                        SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;
-- Création de la table ResetPassword / Relation 1:N entre Users et ResetPassword
CREATE TABLE ResetPassword (
    Id_ResetPassword INT AUTO_INCREMENT PRIMARY KEY,
    selector VARCHAR (20) NOT NULL,
    hashed_token VARCHAR (100) NOT NULL,
    requested_at DATETIME NOT NULL,
    expires_at DATETIME NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES Users (Id_Users)
) DEFAULT CHARACTER
                                            SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;
-- Création de la table Cars / Relation 1:N entre Models et Cars & Relation N.N entre Cars et EnergyType (à travers la table intermédiaire CarsEnergy)
-- Relation N.N entre Cars et Options (à travers la table intermédiaire CarsOptions)
-- Relation 1.N entre Cars et CarAnnonce
CREATE TABLE Cars (
    Id_Cars INT AUTO_INCREMENT PRIMARY KEY,
    mileage INT,
    registration VARCHAR (20),
    price DECIMAL (10, 2) NOT NULL,
    description TEXT,
    main_image_url VARCHAR (255) NOT NULL,
    Id_CarAnnonce INT,
    FOREIGN KEY (Id_CarAnnonce) REFERENCES CarAnnonce (Id_CarAnnonce)
) DEFAULT CHARACTER
                                                SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;
-- Création de la table MessageAnnonce / Relation 1:N entre CarAnnonce et MessageAnnonce
-- Relation 1:N entre MessageAnnonce et Users
CREATE TABLE MessageAnnonce (
    Id_MessageAnnonce INT AUTO_INCREMENT PRIMARY KEY,
    userName VARCHAR (255) NOT NULL,
    userEmail VARCHAR (320) NOT NULL,
    userPhone VARCHAR (20) NOT NULL,
    message TEXT NOT NULL,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Id_Users INT NOT NULL,
    Id_CarAnnonce INT NOT NULL,
    FOREIGN KEY (Id_Users) REFERENCES Users (Id_Users),
    FOREIGN KEY (Id_CarAnnonce) REFERENCES CarAnnonce (Id_CarAnnonce)
) DEFAULT CHARACTER
                                                    SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;
-- Création de la nouvelle table Images / Relation 1:N entre CarAnnonce et Images
CREATE TABLE Images (
    Id_Image INT AUTO_INCREMENT PRIMARY KEY,
    image_url VARCHAR (255) NOT NULL,
    imageName VARCHAR (50) NOT NULL,
    Id_CarAnnonce INT,
    FOREIGN KEY (Id_CarAnnonce) REFERENCES CarAnnonce (Id_CarAnnonce)
) DEFAULT CHARACTER
                                                        SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;
-- Création de la table Models (Modèles) / Relation 1:N entre Models et Cars
-- Relation 1:N entre Models et Brands
-- Relation N.N entre Models et ManufactureYears (à travers la table intermédiaire ModelsManufactureYears)
CREATE TABLE Models (
    Id_Model INT AUTO_INCREMENT PRIMARY KEY,
    model_name VARCHAR (255) NOT NULL,
    category_model VARCHAR (255) NOT NULL,
    Id_Cars INT,
    Id_Brand INT,
    FOREIGN KEY (Id_Cars) REFERENCES Cars (Id_Cars),
    FOREIGN KEY (Id_Brand) REFERENCES Brands (Id_Brand)
) DEFAULT CHARACTER
                                                            SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;
-- Création de la table CarsOptions / Relation N.N entre Cars et Options (à travers la table intermédiaire CarsOptions)
CREATE TABLE CarsOptions (
    Id_Cars INT,
    Id_Options INT,
    PRIMARY KEY (Id_Cars, Id_Options),
    FOREIGN KEY (Id_Cars) REFERENCES Cars (Id_Cars),
    FOREIGN KEY (Id_Options) REFERENCES Options (Id_Options)
) ENGINE = InnoDB;
-- Création de la table CarsEnergy / Relation N.N entre Cars et EnergyType (à travers la table intermédiaire CarsEnergy)
CREATE TABLE CarsEnergy (
    Id_EnergyType INT,
    Id_Cars INT,
    PRIMARY KEY (Id_EnergyType, Id_Cars),
    FOREIGN KEY (Id_EnergyType) REFERENCES EnergyType (Id_EnergyType),
    FOREIGN KEY (Id_Cars) REFERENCES Cars (Id_Cars)
) ENGINE = InnoDB;
-- Création de la table ModelsManufactureYears / Relation N.N entre Models et ManufactureYears (à travers la table intermédiaire ModelsManufactureYears)
CREATE TABLE ModelsManufactureYears (
    Id_Model INT,
    Id_ManufactureYears INT,
    PRIMARY KEY (Id_Model, Id_ManufactureYears),
    FOREIGN KEY (Id_Model) REFERENCES Models (Id_Model) ON DELETE CASCADE,
    FOREIGN KEY (Id_ManufactureYears) REFERENCES ManufactureYears (Id_ManufactureYears)
) ENGINE = InnoDB;
-- Modification des tables + ajout de données fictives
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
-- Dans Garage
INSERT INTO garage (garageName, address, phoneNumber)
VALUES (
        'Garage V.PARROT',
        '4 Impasse Paul Mesplé, 31000 TOULOUSE',
        '05 61 41 70 70'
    );
-- Dans Opening
INSERT INTO Opening (
        storeStatus,
        dayOfWeek,
        morningOpen,
        morningClose,
        eveningOpen,
        eveningClose,
        Id_Garage
    )
VALUES (
        'Ouvert',
        'Lundi',
        '09:00:00',
        '18:00:00',
        '09:00:00',
        '18:00:00',
        1
    ),
    (
        'Ouvert',
        'Mardi',
        '09:00:00',
        '18:00:00',
        '09:00:00',
        '18:00:00',
        1
    ),
    (
        'Ouvert',
        'Mercredi',
        '09:00:00',
        '18:00:00',
        '09:00:00',
        '18:00:00',
        1
    ),
    (
        'Ouvert',
        'Jeudi',
        '09:00:00',
        '18:00:00',
        '09:00:00',
        '18:00:00',
        1
    ),
    (
        'Ouvert',
        'Vendredi',
        '09:00:00',
        '18:00:00',
        '09:00:00',
        '18:00:00',
        1
    ),
    (
        'Ouvert',
        'Samedi',
        '09:00:00',
        '12:00:00',
        NULL,
        NULL,
        1
    ),
    ('Fermé', 'Dimanche', NULL, NULL, NULL, NULL, 1);
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
-- Dans ManufactureYears
INSERT INTO ManufactureYears (manufacture_year)
VALUES (2020),
    (2021),
    (2020),
    (2021),
    (2021),
    (2022),
    (2020),
    (2021),
    (2020),
    (2021),
    (2020),
    (2021),
    (2020),
    (2021),
    (2020),
    (2021),
    (2020),
    (2021),
    (2020),
    (2021),
    (2020),
    (2021),
    (2020),
    (2021),
    (2020),
    (2021),
    (2020),
    (2021),
    (2020),
    (2021),
    (2020),
    (2021),
    (2020),
    (2021),
    (2020),
    (2021),
    (2020),
    (2021),
    (2020),
    (2021),
    (2020),
    (2021);
-- Dans EnergyType
INSERT INTO EnergyType (fuel_type)
VALUES ('Diesel'),
    ('Hybride'),
    ('Hybride'),
    ('Electrique'),
    ('Hybride'),
    ('Hybride'),
    ('Hybride'),
    ('Essence'),
    ('Electrique'),
    ('Hybride'),
    ('Hybride'),
    ('Hybride'),
    ('Hybride'),
    ('Essence'),
    ('Essence'),
    ('Hybride'),
    ('Hybride'),
    ('Hybride'),
    ('Electrique'),
    ('Hybride'),
    ('Hybride'),
    ('Electrique'),
    ('Electrique'),
    ('Essence'),
    ('Hybride'),
    ('Hybride'),
    ('Essence'),
    ('Essence'),
    ('Hybride');
-- Dans Users
ALTER TABLE Users
MODIFY primaryGarage_Id INT NULL;
-- Dans GarageServices
INSERT INTO GarageServices (
        serviceName,
        description,
        image_url,
        phoneNumber,
        Id_Garage
    )
VALUES (
        'Réparation mécanique',
        'Service complet de réparation automobile',
        'service_image1.jpg',
        '05 61 41 70 71',
        1
    ),
    (
        'Entretien régulier',
        'Entretien et maintenance préventive',
        'service_image2.jpg',
        '05 61 41 70 72',
        1
    );
-- Dans Models
INSERT INTO Models (Id_Brand, model_name, category_model)
VALUES (1, '208', 'Citadine'),
    (1, '3008', 'SUV'),
    (2, 'CLIO', 'Citadine'),
    (2, 'KADJAR', 'SUV'),
    (3, 'GLE', 'SUV'),
    (3, 'GLC', 'SUV'),
    (4, 'SERIE 3', 'Berline'),
    (4, 'SERIE 7', 'Berline'),
    (5, 'COROLLA', 'Berline'),
    (5, 'RAV4', 'SUV'),
    (6, 'CR-V', 'SUV'),
    (6, 'CIVIC', 'Berline'),
    (7, 'GOLF', 'Compacte'),
    (7, 'TIGUAN', 'SUV'),
    (8, 'A4', 'Berline'),
    (8, 'Q5', 'SUV'),
    (9, 'MALIBU', 'Berline'),
    (9, 'EQUINOX', 'SUV'),
    (10, 'FOCUS', 'Berline'),
    (10, 'ESCAPE', 'SUV'),
    (11, 'ALTIMA', 'Berline'),
    (11, 'ROGUE', 'SUV'),
    (12, 'KONA', 'SUV'),
    (12, 'TUCSON', 'SUV'),
    (13, 'IS', 'Berline'),
    (13, 'RX', 'SUV'),
    (14, 'MAZDA3', 'Berline'),
    (14, 'CX-5', 'SUV'),
    (15, 'S60', 'Berline'),
    (15, 'XC90', 'SUV'),
    (16, 'MODEL 3', 'Berline'),
    (16, 'MODEL Y', 'SUV'),
    (17, 'SWIFT', 'Citadine'),
    (17, 'VITARA', 'SUV'),
    (18, 'CEED', 'Compacte'),
    (18, 'SPORTAGE', 'SUV'),
    (19, 'IBIZA', 'Citadine'),
    (19, 'ATECA', 'SUV');