-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 03 juin 2025 à 10:33
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `lendigodb`
--

-- --------------------------------------------------------

--
-- Structure de la table `agency`
--

CREATE TABLE `agency` (
  `agency_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `contact_email` varchar(150) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `agency_city` varchar(150) NOT NULL,
  `location` varchar(255) NOT NULL,
  `rating` decimal(3,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `agency`
--

INSERT INTO `agency` (`agency_id`, `name`, `contact_email`, `phone_number`, `agency_city`, `location`, `rating`) VALUES
(1, 'Agence Ahmed', 'ahmedAgency@gmail.com', '06123456789', 'Tangier', 'tangier-mghogha-morocco', 0.00),
(2, 'Elite Voyages', 'contact@elitevoyages.com', '06543219876', 'Casablanca', 'casablanca-anfa-morocco', 0.00),
(3, 'Sahara Travel', 'info@saharatravel.ma', '06234567890', 'Marrakech', 'marrakech-medina-morocco', 0.00),
(4, 'Atlas Explorer', 'support@atlasexplorer.com', '06789123456', 'Fes', 'fes-ville-nouvelle-morocco', 0.00),
(5, 'Atlas Voyages', 'contact@atlasvoyages.ma', '+212522220101', 'Casablanca', '18, Rue Sebta, Quartier Gauthier, Casablanca', 4.75),
(6, 'Maroc Horizon Tours', 'info@marochorizontours.com', '+212661234567', 'Marrakech', 'Rue Ibn Aïcha, Gueliz, Marrakech', 4.60);

-- --------------------------------------------------------

--
-- Structure de la table `agency_review`
--

CREATE TABLE `agency_review` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `agency_id` int(11) NOT NULL,
  `rating` decimal(3,2) NOT NULL CHECK (`rating` >= 0 and `rating` <= 5),
  `review_text` text NOT NULL,
  `created_at` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `agency_review`
--

INSERT INTO `agency_review` (`review_id`, `user_id`, `agency_id`, `rating`, `review_text`, `created_at`) VALUES
(1, 1, 4, 5.00, 'Outstanding customer service and smooth process.', '2025-06-02'),
(2, 2, 6, 4.00, 'Reliable agency, car was in good shape.', '2025-06-02'),
(3, 4, 5, 3.00, 'Decent overall, but pick-up was delayed.', '2025-06-02'),
(4, 5, 1, 2.00, 'Not very friendly staff and long wait times.', '2025-06-02'),
(5, 1, 3, 4.00, 'Good experience, would rent again.', '2025-06-02'),
(6, 4, 2, 5.00, 'Amazing agency! Very professional and helpful.', '2025-06-02'),
(7, 5, 15, 1.00, 'Bad experience. The brakes were worn out.', '2025-06-02'),
(8, 1, 30, 5.00, 'Excellent service and the car was very clean!', '2025-06-07'),
(9, 2, 30, 4.00, 'The car was in good condition, but pick-up took some time.', '2025-06-07'),
(10, 4, 30, 3.00, 'It was okay, but the AC wasn’t working properly.', '2025-06-07');

-- --------------------------------------------------------

--
-- Structure de la table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `status` varchar(15) DEFAULT NULL CHECK (`status` in ('waiting','reserved','canceled','completed')),
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `car`
--

CREATE TABLE `car` (
  `car_id` int(11) NOT NULL,
  `agency_id` int(11) NOT NULL,
  `car_name` varchar(150) NOT NULL,
  `car_rating` decimal(3,2) NOT NULL,
  `description` text NOT NULL,
  `model` varchar(100) NOT NULL,
  `places` int(11) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `price_per_day` decimal(10,2) NOT NULL,
  `car_type` varchar(150) NOT NULL,
  `availability_status` enum('available','booked') DEFAULT 'available',
  `image_url` varchar(255) NOT NULL,
  `car_fuel` enum('Diesel','Gasoline','Hybrid','Electrical') DEFAULT NULL,
  `kilometers` int(11) NOT NULL,
  `isAutomatic` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `car`
--

INSERT INTO `car` (`car_id`, `agency_id`, `car_name`, `car_rating`, `description`, `model`, `places`, `brand`, `price_per_day`, `car_type`, `availability_status`, `image_url`, `car_fuel`, `kilometers`, `isAutomatic`) VALUES
(1, 1, 'Dacia Logan', 3.50, 'Compact and efficient sedan', '2017', 5, 'Dacia', 250.00, 'Sedan', 'available', 'https://www.km77.com/images/medium/7/5/7/1/dacia-logan-lateral-frontal.327571.jpg', 'Gasoline', 65000, 1),
(2, 2, 'Volkswagen Golf', 3.50, 'Reliable family hatchback', '2019', 5, 'Volkswagen', 370.00, 'Hatchback', 'available', 'https://media.ed.edmunds-media.com/volkswagen/golf/2019/oem/2019_volkswagen_golf_4dr-hatchback_14t-s_fq_oem_1_1600.jpg', 'Diesel', 45000, 1),
(3, 3, 'Renault Zoe', 3.50, 'Electric compact city car', '2021', 5, 'Renault', 500.00, 'Electric / Hybrid', 'booked', 'https://www.larevueautomobile.com/images/fiche-technique/2021/Renault/ZOE/Renault_ZOE_MD_0.jpg', 'Electrical', 15000, 1),
(4, 4, 'Tesla Model 3', 3.50, 'Luxurious electric sedan', '2022', 5, 'Tesla', 950.00, 'Electric / Hybrid', 'available', 'https://www.greencarguide.co.uk/wp-content/uploads/2022/05/Tesla-Model-3-Long-Range-001-low-res.jpeg', 'Electrical', 10000, 1),
(5, 1, 'BMW 5 Series', 3.50, 'Premium German luxury', '2021', 5, 'BMW', 1100.00, 'Luxury', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2021-bmw-540i-xdrive-370-edit-1608066218.jpg?crop=0.579xw:0.487xh;0.0785xw,0.472xh&resize=1200:*', 'Diesel', 20000, 1),
(6, 2, 'Mercedes E-Class', 3.50, 'Elegant and modern', '2022', 5, 'Mercedes', 1200.00, 'Luxury', 'booked', 'https://images.prismic.io/carwow/8038ff9f-476b-402e-8ac3-13731b4e2dce_Mercedes+E-Class+Front+%C2%BE+moving.jpg', 'Hybrid', 18000, 1),
(7, 3, 'Kia Sportage', 3.50, 'Spacious family SUV', '2020', 5, 'Kia', 430.00, 'SUV', 'available', 'https://www.mcgrathautoblog.com/wp-content/uploads/2020/01/2020-Kia-Sportage-980x550.jpg', 'Gasoline', 30000, 1),
(8, 4, 'Toyota Highlander', 3.50, 'Large hybrid SUV', '2021', 7, 'Toyota', 800.00, 'Electric / Hybrid', 'booked', 'https://ecoloauto.com/?attachment_id=43316', 'Hybrid', 23000, 1),
(9, 1, 'Fiat Panda', 3.50, 'Economic city car', '2018', 4, 'Fiat', 220.00, 'Hatchback', 'available', 'https://assets.choosemycar.com/vehicles/large/6682787_303_4288_vehicle-6682787-001-20250126-072502-9e4bc7a1286cdf8e777cea0f839ceebd2c82bf014ea281f0509ae6d43bb5ccb1.jpg', 'Gasoline', 55000, 0),
(10, 2, 'Peugeot 208', 3.50, 'Strong and compact', '2020', 5, 'Peugeot', 300.00, 'Hatchback', 'available', 'https://images.caradisiac.com/logos-ref/gamme/gamme--peugeot-208/S8-gamme--peugeot-208.jpg', 'Gasoline', 25000, 0),
(11, 3, 'BMW i3', 3.50, 'Electric hatchback', '2021', 4, 'BMW', 620.00, 'Electric / Hybrid', 'booked', 'https://cloud.leparking.fr/2024/07/05/03/32/bmw-i3-2021-bmw-i3-electric-for-sale_9121684224.jpg', 'Electrical', 17000, 1),
(12, 4, 'Hyundai Tucson', 3.50, 'Spacious SUV', '2019', 5, 'Hyundai', 490.00, 'SUV', 'available', 'https://media.ed.edmunds-media.com/hyundai/tucson/2019/oem/2019_hyundai_tucson_4dr-suv_ultimate_fq_oem_1_1600.jpg', 'Diesel', 41000, 1),
(13, 1, 'Audi A4', 3.50, 'Elegant compact sedan', '2021', 5, 'Audi', 950.00, 'Luxury', 'available', 'https://images.carexpert.com.au/resize/800/-/app/uploads/2021/04/2021-Audi-A4-Avant-45-TFSI-quattro-S-line-HERO.jpg', 'Gasoline', 20000, 1),
(14, 2, 'Toyota Corolla', 3.50, 'Reliable sedan', '2020', 5, 'Toyota', 400.00, 'Sedan', 'available', 'https://ddc1.s3.amazonaws.com/Nji8QtRthmrSoFn9ByAh5tZj/CDy2BvBgoiXPo024/Vm3pWw%3D%3D/BzK1Evh8oCjcrViyPyM5/secc2.jpg', 'Hybrid', 30000, 1),
(15, 3, 'Range Rover Velar', 3.50, 'Powerful SUV', '2022', 5, 'Range Rover', 1250.00, 'Luxury', 'booked', 'https://www.carscoops.com/wp-content/uploads/2021/08/2022-Land-Rover-Range-Rover-Velar-1.jpg', 'Diesel', 18000, 1),
(16, 4, 'Renault Scenic', 3.50, 'Minivan for family trips', '2019', 7, 'Renault', 350.00, 'Van / Minivan', 'available', 'https://img.autoabc.lv/Renault-Scenic/Renault-Scenic_2016_Minivens_2361420130.jpg', 'Diesel', 38000, 0),
(17, 1, 'Tesla Model Y', 3.50, 'Electric futuristic SUV', '2023', 5, 'Tesla', 1100.00, 'Electric / Hybrid', 'available', 'https://autoimage.capitalone.com/cms/Auto/assets/images/2622-hero-tesla-model-y-review-and-test-drive.jpg', 'Electrical', 8000, 1),
(18, 2, 'Honda Jazz', 3.50, 'Hybrid city car', '2021', 5, 'Honda', 360.00, 'Electric / Hybrid', 'available', 'https://carsguide-res.cloudinary.com/image/upload/c_fit,h_841,w_1490,f_auto,t_cg_base/v1/editorial/story/hero_image/2018-honda-jazz-vti-s-hatchback-white-mitchell-tulk-1001x565-(1).jpg', 'Hybrid', 27000, 1),
(19, 3, 'Nissan Juke', 3.50, 'Compact stylish SUV', '2020', 5, 'Nissan', 410.00, 'SUV', 'booked', 'https://media.drive.com.au/obj/tx_q:50,rs:auto:1920:1080:1/caradvice/private/mpddqshdikj8aohv76lb', 'Gasoline', 32000, 1),
(20, 4, 'Audi A6', 3.50, 'Executive luxury sedan', '2022', 5, 'Audi', 1050.00, 'Luxury', 'available', 'https://media.ed.edmunds-media.com/audi/a6/2022/oem/2022_audi_a6_sedan_prestige_fq_oem_1_1600.jpg', 'Hybrid', 14000, 1),
(21, 1, 'Volkswagen Tiguan', 3.50, 'Spacious SUV', '2021', 5, 'Volkswagen', 520.00, 'SUV', 'available', 'https://ecoloauto.com/36734-2/2021-volkswagen-tiguan-30/', 'Diesel', 24000, 1),
(22, 2, 'Mercedes EQC', 3.50, 'Electric luxury SUV', '2022', 5, 'Mercedes', 1150.00, 'Electric / Hybrid', 'available', 'https://media.drive.com.au/obj/tx_q:50,rs:auto:1920:1080:1/driveau/upload/cms/uploads/nmyj3fbw4eo87l8tultu', 'Electrical', 12000, 1),
(23, 3, 'Toyota RAV4', 3.50, 'Hybrid crossover', '2021', 5, 'Toyota', 540.00, 'Electric / Hybrid', 'booked', 'https://images.cars.com/cldstatic/wp-content/uploads/toyota-rav4-prime-2021-01-angle--exterior--front--grey.jpg', 'Hybrid', 20000, 1),
(24, 4, 'BMW X5', 3.50, 'Luxurious SUV', '2022', 5, 'BMW', 1300.00, 'Luxury', 'available', 'https://www.edmunds.com/assets/m/bmw/x5-m/2021/oem/2021_bmw_x5-m_4dr-suv_base_fq_oem_1_600.jpg', 'Diesel', 17000, 1),
(25, 1, 'Ford Fiesta', 3.50, 'Compact hatchback', '2018', 5, 'Ford', 270.00, 'Hatchback', 'available', 'https://cdn.abcmoteur.fr/wp-content/uploads/2018/09/14-8-e1535990091532-1.jpg', 'Gasoline', 48000, 0),
(26, 2, 'Opel Zafira', 3.50, 'Practical minivan', '2019', 7, 'Opel', 400.00, 'Van / Minivan', 'booked', 'https://www.largus.fr/images/styles/max_1300x1300/public/images/opel-505549-redimensionner.jpg?itok=OL8GT025', 'Diesel', 36000, 0),
(27, 3, 'Hyundai Ioniq', 3.50, 'Efficient hybrid sedan', '2020', 5, 'Hyundai', 490.00, 'Electric / Hybrid', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2020-hyundai-ioniq-hybrid-102-1590604533.jpg', 'Hybrid', 22000, 1),
(28, 4, 'Mercedes CLA', 3.50, 'Stylish coupe', '2021', 4, 'Mercedes', 990.00, 'Coupe', 'available', 'https://di-uploads-pod3.dealerinspire.com/fletcherjonesmbnewport/uploads/2024/07/Newport-CLA-1024x683.png', 'Gasoline', 19000, 1),
(29, 1, 'Ford Puma', 3.50, 'Compact crossover', '2021', 5, 'Ford', 440.00, 'SUV', 'booked', 'https://images.caradisiac.com/logos/4/4/3/9/264439/S8-essai-ford-puma-ecoboost-125-dct-7-2021-la-seule-offre-avec-une-boite-automatique-187678.jpg', 'Gasoline', 21000, 1),
(30, 2, 'Audi Q3', 3.50, 'Luxury compact SUV', '2021', 5, 'Audi', 890.00, 'Luxury', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2021-audi-q3-mmp-1-1590773404.jpg', 'Gasoline', 23000, 1),
(31, 3, 'Citroën ë-Berlingo', 3.50, 'Mini electric van', '2022', 5, 'Citroën', 490.00, 'Van / Minivan', 'available', 'https://journalauto.com/wp-content/uploads/2021/12/Citroen.jpg', 'Electrical', 15000, 1),
(32, 4, 'Lexus ES', 3.50, 'Hybrid sedan', '2021', 5, 'Lexus', 950.00, 'Electric / Hybrid', 'booked', 'https://gofatherhood.com/wp-content/uploads/2021/07/2021-lexus-9012a-es-awd-9.jpg', 'Hybrid', 17000, 1),
(33, 1, 'Porsche Taycan', 3.50, 'Electric performance', '2022', 4, 'Porsche', 2000.00, 'Luxury', 'available', 'https://ev-database.org/img/auto/Porsche_Taycan_GTS/Porsche_Taycan_GTS-01@2x.jpg', 'Electrical', 12000, 1),
(34, 2, 'BMW 4 Series', 3.50, 'Premium coupe', '2021', 4, 'BMW', 1250.00, 'Coupe', 'available', 'https://www.inquirer.com/resizer/9QiQt9p0eLpPy2TaxMylqO3sI-U=/arc-anglerfish-arc2-prod-pmn/public/PX33263A5VEWZCAC7NXXII5TRY.jpg', 'Gasoline', 15000, 1),
(35, 3, 'Ford Explorer Hybrid', 3.50, 'Large hybrid SUV', '2022', 7, 'Ford', 980.00, 'Electric / Hybrid', 'booked', 'https://i.gaw.to/content/photos/53/99/539909-ford-explorer-hybride-2022-de-montreal-a-new-york-a-son-volant.jpeg', 'Hybrid', 16000, 1),
(36, 4, 'Renault Captur', 3.50, 'Compact crossover', '2020', 5, 'Renault', 350.00, 'SUV', 'available', 'https://www.automobile-magazine.fr/asset/cms/167640/config/116450/all-new-renault-captur-arctic-white-010.jpg', 'Gasoline', 35000, 0),
(37, 1, 'Mazda CX-5', 3.50, 'Compact SUV with great handling', '2022', 5, 'Mazda', 480.00, 'SUV', 'available', 'https://s3.wheelsage.org/format/picture/picture-thumb-medium/m/mazda/cx-5_skyactiv-g_newground/mazda_cx-5_skyactiv-g_newground_912_09ad060312f40e0f.jpg', 'Gasoline', 22000, 1),
(38, 2, 'Subaru Outback', 3.50, 'Reliable and rugged wagon', '2021', 5, 'Subaru', 460.00, 'SUV', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2021-suabru-outback-mmp-1-1598639461.jpg?crop=0.801xw:0.675xh;0.0673xw,0.159xh&resize=2048:*', 'Gasoline', 25000, 1),
(39, 3, 'Volkswagen Passat', 3.50, 'Comfortable midsize sedan', '2020', 5, 'Volkswagen', 420.00, 'Sedan', 'available', 'https://images.cars.com/cldstatic/wp-content/uploads/volkswagen-passat-2020-01-angle--blue--exterior--front--mountains.jpg', 'Diesel', 28000, 1),
(40, 4, 'Honda Civic', 3.50, 'Efficient compact car', '2021', 5, 'Honda', 390.00, 'Sedan', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2021-honda-civic-mmp-1-1595005323.jpg?crop=0.753xw:0.635xh;0.143xw,0.365xh&resize=2048:*', 'Gasoline', 23000, 1),
(41, 1, 'Chevrolet Malibu', 3.50, 'Stylish midsize sedan', '2019', 5, 'Chevrolet', 370.00, 'Sedan', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2018-chevrolet-malibu-rs-1544730044.jpg', 'Gasoline', 35000, 0),
(42, 2, 'Hyundai Elantra', 3.50, 'Affordable compact sedan', '2022', 5, 'Hyundai', 360.00, 'Sedan', 'available', 'https://www.hyundainews.com/assets/images/thumbnail-true/46447-Large444082021Hyundai1188.jpg', 'Gasoline', 21000, 1),
(43, 3, 'Kia Sorento', 3.50, 'Mid-size SUV with 7 seats', '2021', 7, 'Kia', 520.00, 'SUV', 'available', 'https://d2v1gjawtegg5z.cloudfront.net/posts/preview_images/000/012/739/original/2021-Kia-Sorento.png?1711460732', 'Gasoline', 19000, 1),
(44, 4, 'Ford Escape', 3.50, 'Compact SUV with good tech', '2020', 5, 'Ford', 470.00, 'SUV', 'available', 'https://www.autohebdo.net/editorial/media/172296/2020-ford-escape-titanium-hybrid-01-dr-fr.jpg?width=1920&height=1080&v=1da45cc870752e0', 'Gasoline', 24000, 1),
(45, 1, 'Jeep Wrangler', 3.50, 'Iconic off-road SUV', '2019', 5, 'Jeep', 550.00, 'SUV', 'booked', 'https://www.cnet.com/a/img/resize/b865ee39c551f2affdbc5d86d46360cfeec716b4/hub/2019/11/18/20d90d41-927e-4d11-ad35-ac9e5f17cd0b/ogi-jeep-wrangler-rubicon-etorque-2019-8507.png?auto=webp&fit=crop&height=675&width=1200', 'Gasoline', 30000, 0),
(46, 2, 'Nissan Altima', 3.50, 'Comfortable midsize sedan', '2021', 5, 'Nissan', 400.00, 'Sedan', 'available', 'https://media.ed.edmunds-media.com/nissan/altima/2020/oem/2020_nissan_altima_sedan_25-platinum_fq_oem_1_1600.jpg', 'Gasoline', 22000, 1),
(47, 3, 'Tesla Model S', 3.50, 'Luxury electric sedan', '2022', 5, 'Tesla', 1500.00, 'Electric / Hybrid', 'available', 'https://www.thedrive.com/wp-content/uploads/images-by-url-td/content/2021/07/tesla-plaid-lead.jpg?quality=85', 'Electrical', 10000, 1),
(48, 4, 'Volkswagen Atlas', 3.50, 'Full-size SUV', '2021', 7, 'Volkswagen', 600.00, 'SUV', 'available', 'https://media.ed.edmunds-media.com/volkswagen/atlas/2021/oem/2021_volkswagen_atlas_4dr-suv_v6-sel-premium-r-line-4motion_fq_oem_1_1600.jpg', 'Gasoline', 20000, 1),
(49, 1, 'Audi Q5', 3.50, 'Luxury compact SUV', '2020', 5, 'Audi', 900.00, 'Luxury', 'available', 'https://i.gaw.to/vehicles/photos/40/19/401938-2020-audi-q5.jpg?640x400', 'Gasoline', 18000, 1),
(50, 2, 'BMW X3', 3.50, 'Sporty luxury SUV', '2021', 5, 'BMW', 950.00, 'Luxury', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2021-bmw-x3-m-mm-1-1604358685.jpg', 'Diesel', 16000, 1),
(51, 3, 'Mercedes GLC', 3.50, 'Luxury compact SUV', '2022', 5, 'Mercedes', 1100.00, 'Luxury', 'available', 'https://dealerinspire-image-library-prod.s3.us-east-1.amazonaws.com/images/mJFlPQS3nlg1UQswtgZZ0PhfoDEscVNEUpULtizM.jpg', 'Hybrid', 12000, 1),
(52, 4, 'Toyota Camry', 3.50, 'Reliable midsize sedan', '2021', 5, 'Toyota', 450.00, 'Sedan', 'available', 'https://i.gaw.to/content/photos/43/32/433242-toyota-camry-2021-nouvelle-version-hybride-et-plus-de-securite.jpeg', 'Hybrid', 25000, 1),
(53, 1, 'Honda Accord', 3.50, 'Popular midsize sedan', '2022', 5, 'Honda', 480.00, 'Sedan', 'available', 'https://i.gaw.to/content/photos/52/32/523292-honda-accord-hybride-2022-l-hybride-pseudo-sportive.jpeg', 'Gasoline', 18000, 1),
(54, 2, 'Mazda3', 3.50, 'Sporty compact sedan', '2021', 5, 'Mazda', 400.00, 'Sedan', 'available', 'https://i.gaw.to/content/photos/43/21/432153-une-mazda3-turbo-s-en-vient-bel-et-bien-pour-2021.jpeg', 'Gasoline', 20000, 1),
(55, 3, 'Subaru Forester', 3.50, 'Practical SUV', '2020', 5, 'Subaru', 480.00, 'SUV', 'available', 'https://media.drive.com.au/obj/tx_q:70,rs:auto:1920:1080:1/caradvice/private/joieqgia7azuisrmjc6c', 'Gasoline', 22000, 1),
(56, 4, 'Chevrolet Equinox', 3.50, 'Compact SUV', '2019', 5, 'Chevrolet', 450.00, 'SUV', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2018-chevrolet-equinox-mmp-1-1557249905.jpg', 'Gasoline', 27000, 0),
(57, 1, 'Hyundai Santa Fe', 3.50, 'Mid-size SUV', '2021', 7, 'Hyundai', 540.00, 'SUV', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2021-hyundai-santa-fe-mmp-1-1617287216.jpg', 'Gasoline', 21000, 1),
(58, 2, 'Kia Forte', 3.50, 'Compact sedan', '2022', 5, 'Kia', 370.00, 'Sedan', 'available', 'https://www.autotrader.ca/editorial/media/s2sjylme/2022-kia-forte-gt-line-02-jm.jpg?v=1d86a7bc50aca50', 'Gasoline', 17000, 1),
(59, 3, 'Ford Explorer', 3.50, 'Full-size SUV', '2020', 7, 'Ford', 650.00, 'SUV', 'available', 'https://media.drive.com.au/obj/tx_q:50,rs:auto:1920:1080:1/caradvice/private/1c5122a569cc911603ddb444eff3ca89', 'Gasoline', 28000, 1),
(60, 4, 'Volkswagen Jetta', 3.50, 'Compact sedan', '2021', 5, 'Volkswagen', 390.00, 'Sedan', 'available', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRDKpQb6hczNhPsLjuFJwNq0CHrNrnBLDXeZA&s', 'Gasoline', 23000, 1),
(61, 1, 'Nissan Rogue', 3.50, 'Popular compact SUV', '2021', 5, 'Nissan', 480.00, 'SUV', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2021-nissan-rogue-107-1591910983.jpg?crop=0.779xw:0.656xh;0.149xw,0.344xh&resize=2048:*', 'Gasoline', 21000, 1),
(62, 5, 'Toyota Corolla 2024', 3.50, 'Comfortable sedan car', '2024', 5, 'Toyota', 350.00, 'Sedan', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2024-toyota-gr-corolla-premium-exterior-107-662035ecce76c.jpg?crop=0.673xw:0.566xh;0.157xw,0.269xh&resize=1200:*', 'Gasoline', 0, 1),
(63, 6, 'Renault Clio 2023', 3.50, 'Compact and efficient', '2023', 5, 'Renault', 300.00, 'Hatchback', 'available', 'https://media.autoexpress.co.uk/image/private/s--X-WVjvBW--/f_auto,t_content-image-full-desktop@1/v1698161627/carbuyer/2023/10/Renault%20Clio%20UK%20review-2.jpg', 'Gasoline', 0, 1),
(64, 6, 'Peugeot 3008 2022', 3.50, 'Stylish SUV', '2022', 5, 'Peugeot', 450.00, 'SUV', 'available', 'https://static.moniteurautomobile.be/imgcontrol/images_tmp/clients/moniteur/c680-d465/content/medias/images/cars/peugeot/3008/peugeot--3008--2022/peugeot--3008--2022-m-1.jpg', 'Diesel', 0, 1),
(65, 6, 'Dacia Duster 2021', 3.50, 'Affordable SUV', '2021', 5, 'Dacia', 280.00, 'SUV', 'available', 'https://im.qccdn.fr/node/actualite-dacia-duster-2021-premieres-impressions-94060/thumbnail_800x480px-138098.jpg', 'Gasoline', 0, 1),
(66, 5, 'Hyundai Tucson 2020', 3.50, 'Reliable and spacious', '2020', 5, 'Hyundai', 400.00, 'SUV', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2019-hyundai-tucson-1544723862.jpg', 'Gasoline', 0, 1),
(67, 5, 'Volkswagen Golf 2019', 3.50, 'Compact hatchback', '2019', 5, 'Volkswagen', 320.00, 'Hatchback', 'available', 'https://cdn05.carsforsale.com/00b62721569e1bf2a9b4d719f7d493b49a/1280x960/2015-volkswagen-golf-tdi-s-4dr-hatchback-6a.jpg', 'Gasoline', 0, 1),
(68, 6, 'Ford Focus 2018', 3.50, 'Popular compact car', '2018', 5, 'Ford', 310.00, 'Sedan', 'available', 'https://carsguide-res.cloudinary.com/image/upload/c_fit,h_841,w_1490,f_auto,t_cg_base/v1/editorial/2018-ford-focus-trend-hatch-red-mitchell-tulk-1200x800-(1).jpg', 'Gasoline', 0, 1),
(69, 5, 'Kia Sportage 2017', 3.50, 'Compact SUV', '2017', 5, 'Kia', 350.00, 'SUV', 'available', 'https://www.cnet.com/a/img/resize/d0a679fdf33d19508ebd5ab3642dbb1fa21358df/hub/2016/03/07/cffb8a84-2b9c-4160-983f-f3ff09ea53e3/2017-kia-sportage-sx-1.jpg?auto=webp&width=1200', 'Gasoline', 0, 1),
(70, 5, 'Mazda CX-5 2016', 3.50, 'SUV with great handling', '2016', 5, 'Mazda', 370.00, 'SUV', 'available', 'https://media.ed.edmunds-media.com/mazda/cx-5/2016/oem/2016_mazda_cx-5_4dr-suv_grand-touring_fq_oem_1_1600.jpg', 'Gasoline', 0, 1),
(71, 6, 'Nissan Qashqai 2015', 3.50, 'Popular crossover', '2015', 5, 'Nissan', 340.00, 'SUV', 'available', 'https://editorial.pxcrush.net/carsales/general/editorial/ge4743318632277466917.jpg?width=1024&height=682', 'Gasoline', 0, 1),
(72, 6, 'Citroen C3 2014', 3.50, 'Compact city car', '2014', 5, 'Citroen', 280.00, 'Hatchback', 'available', 'https://static.moniteurautomobile.be/imgcontrol/images_tmp/clients/moniteur/c680-d465/content/medias/images/cars/citroen/c3/citroen--c3--2014/citroen--c3--2014-t-1.jpg', 'Gasoline', 0, 1),
(73, 5, 'Honda Civic 2013', 3.50, 'Reliable sedan', '2013', 5, 'Honda', 330.00, 'Sedan', 'available', 'https://i.gaw.to/content/photos/11/26/112636_2013_Honda_Civic.jpg', 'Gasoline', 0, 1),
(74, 5, 'Opel Astra 2012', 3.50, 'Popular compact car', '2012', 5, 'Opel', 290.00, 'Hatchback', 'available', 'https://www.automoli.com/common/vehicles/_assets/img/gallery/f107/opel-astra-j-facelift-2012.jpg', 'Gasoline', 0, 1),
(75, 5, 'BMW X1 2011', 3.50, 'Luxury compact SUV', '2011', 5, 'BMW', 600.00, 'SUV', 'available', 'https://hips.hearstapps.com/hmg-prod/amv-prod-cad-assets/images/09q3/267589/2011-bmw-x1-review-car-and-driver-photo-290114-s-original.jpg', 'Diesel', 0, 1),
(76, 6, 'Mercedes A-Class 2010', 3.50, 'Premium hatchback', '2010', 5, 'Mercedes', 650.00, 'Hatchback', 'available', 'https://dealercontrol.co.za/cars-for-sale/ATN/1812/MERCEDES-BENZ-A180_ELEGANCE_A_T-77959.jpg', 'Gasoline', 0, 1),
(77, 6, 'Audi A3 2009', 3.50, 'Compact premium car', '2009', 5, 'Audi', 620.00, 'Hatchback', 'available', 'https://www.edmunds.com/assets/m/audi/a3/2009/oem/2009_audi_a3_wagon_20t-quattro_fq_oem_1_500.jpg', 'Gasoline', 0, 1),
(78, 6, 'Toyota RAV4 2008', 3.50, 'Reliable SUV', '2008', 5, 'Toyota', 400.00, 'SUV', 'available', 'https://i.gaw.to/vehicles/photos/00/22/002280_2008_Toyota_RAV4.jpg?640x400', 'Gasoline', 0, 1),
(79, 5, 'Renault Megane 2007', 3.50, 'Compact sedan', '2007', 5, 'Renault', 280.00, 'Sedan', 'available', 'https://images.caradisiac.com/logos-ref/modele/modele--renault-megane-2/S8-modele--renault-megane-2.jpg', 'Gasoline', 0, 1),
(80, 6, 'Peugeot 208 2006', 3.50, 'Compact hatchback', '2006', 5, 'Peugeot', 270.00, 'Hatchback', 'available', 'https://cdn-s-www.ledauphine.com/images/0DD70676-DA38-4A6F-ABC8-2D5E7025EA9E/NW_raw/la-207-qui-peinera-a-succeder-a-la-206-photo-peugeot-1581096286.jpg', 'Gasoline', 0, 1),
(81, 5, 'Dacia Logan 2025', 3.50, 'Affordable sedan', '2025', 5, 'Dacia', 250.00, 'Sedan', 'available', 'https://www.321rentacar.ro/images/cars/dacia-logan-2025.png', 'Gasoline', 0, 1),
(82, 5, 'Hyundai Elantra 2004', 3.50, 'Compact sedan', '2004', 5, 'Hyundai', 300.00, 'Sedan', 'available', 'https://file.kelleybluebookimages.com/kbb/base/house/2004/2004-Hyundai-Elantra-FrontSide_HYELAGLS041_505x309.jpg', 'Gasoline', 0, 1),
(83, 6, 'Volkswagen Passat 2015', 3.50, 'Mid-size sedan', '2015', 5, 'Volkswagen', 350.00, 'Sedan', 'available', 'https://hips.hearstapps.com/hmg-prod/amv-prod-cad-assets/images/14q4/638369/2015-volkswagen-passat-euro-spec-first-drive-review-car-and-driver-photo-640296-s-original.jpg?fill=1:1&resize=1200:*', 'Diesel', 0, 1),
(84, 5, 'Ford Fiesta 2002', 3.50, 'Small hatchback', '2002', 5, 'Ford', 260.00, 'Hatchback', 'available', 'https://img.autoabc.lv/ford-fiesta/ford-fiesta_2002_Hecbeks_15113121456_3.jpg', 'Gasoline', 0, 1),
(85, 6, 'Kia Rio 2001', 3.50, 'Compact car', '2001', 5, 'Kia', 270.00, 'Hatchback', 'available', 'https://www.edmunds.com/assets/m/kia/rio/2001/oem/2001_kia_rio_sedan_base_fq_oem_1_500.jpg', 'Gasoline', 0, 1),
(86, 6, 'Mazda 3 2000', 3.50, 'Reliable compact', '2000', 5, 'Mazda', 280.00, 'Sedan', 'available', 'https://upload.wikimedia.org/wikipedia/commons/6/6d/Mazda_3.jpg', 'Gasoline', 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `car_review`
--

CREATE TABLE `car_review` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `rating` decimal(3,2) NOT NULL CHECK (`rating` >= 0 and `rating` <= 5),
  `review_text` text NOT NULL,
  `created_at` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `car_review`
--

INSERT INTO `car_review` (`review_id`, `user_id`, `car_id`, `rating`, `review_text`, `created_at`) VALUES
(1, 1, 30, 5.00, 'Excellent service and the car was very clean!', '2025-06-02'),
(2, 2, 32, 4.00, 'The car was in good condition, but pick-up took some time.', '2025-06-02'),
(3, 4, 19, 3.00, 'It was okay, but the AC wasn’t working properly.', '2025-06-02'),
(4, 5, 3, 2.00, 'The car was not as described. Needs maintenance.', '2025-06-02'),
(5, 1, 5, 5.00, 'Very smooth ride. I’ll definitely book again.', '2025-06-02'),
(6, 2, 67, 4.00, 'Customer support was responsive. Car was fine.', '2025-06-02'),
(7, 5, 15, 1.00, 'Bad experience. The brakes were worn out.', '2025-06-02');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `registration_date` date DEFAULT curdate(),
  `user_type` enum('customer','admin') NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `phone_number`, `registration_date`, `user_type`) VALUES
(1, 'john_doe', 'john@example.com', '$2y$10$O7mU5UeDpYFeMuEwAkL95e4E5WuTIerPZ1EG5lhqvWTB8gGZ6n34G', '0612345678', '2025-06-02', 'customer'),
(2, 'agency_procar', 'procar@example.com', '$2y$10$O7mU5UeDpYFeMuEwAkL95e4E5WuTIerPZ1EG5lhqvWTB8gGZ6n34G', '0623456789', '2025-06-02', ''),
(3, 'meedafilal', 'meedaf11@gmail.com', '$2y$10$O7mU5UeDpYFeMuEwAkL95e4E5WuTIerPZ1EG5lhqvWTB8gGZ6n34G', '0682564814', '2025-06-02', 'admin'),
(4, 'fatima_rider', 'fatima@example.com', '$2y$10$O7mU5UeDpYFeMuEwAkL95e4E5WuTIerPZ1EG5lhqvWTB8gGZ6n34G', '0645678901', '2025-06-02', 'customer'),
(5, 'rent4u_agency', 'rent4u@example.com', '$2y$10$O7mU5UeDpYFeMuEwAkL95e4E5WuTIerPZ1EG5lhqvWTB8gGZ6n34G', '0656789012', '2025-06-02', '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `agency`
--
ALTER TABLE `agency`
  ADD PRIMARY KEY (`agency_id`);

--
-- Index pour la table `agency_review`
--
ALTER TABLE `agency_review`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `fk_agencyreview_user` (`user_id`),
  ADD KEY `fk_agencyreview_agency` (`agency_id`);

--
-- Index pour la table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Index pour la table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`car_id`),
  ADD KEY `agency_id` (`agency_id`);

--
-- Index pour la table `car_review`
--
ALTER TABLE `car_review`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `fk_carreview_user` (`user_id`),
  ADD KEY `fk_carreview_car` (`car_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `agency`
--
ALTER TABLE `agency`
  MODIFY `agency_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `agency_review`
--
ALTER TABLE `agency_review`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `car`
--
ALTER TABLE `car`
  MODIFY `car_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT pour la table `car_review`
--
ALTER TABLE `car_review`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `agency_review`
--
ALTER TABLE `agency_review`
  ADD CONSTRAINT `fk_agencyreview_agency` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`agency_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_agencyreview_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `car_id` FOREIGN KEY (`car_id`) REFERENCES `car` (`car_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `car`
--
ALTER TABLE `car`
  ADD CONSTRAINT `agency_id` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`agency_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `car_review`
--
ALTER TABLE `car_review`
  ADD CONSTRAINT `fk_carreview_car` FOREIGN KEY (`car_id`) REFERENCES `car` (`car_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_carreview_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
