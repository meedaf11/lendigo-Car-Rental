-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 19 juin 2025 à 13:44
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
  `agency_owner_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `contact_email` varchar(150) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `agency_city` varchar(150) NOT NULL,
  `location` varchar(255) NOT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `solde` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','blocked') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `agency`
--

INSERT INTO `agency` (`agency_id`, `name`, `agency_owner_id`, `description`, `image`, `contact_email`, `phone_number`, `agency_city`, `location`, `rating`, `solde`, `created_at`, `status`) VALUES
(1, 'Agence Ahmed', 1, 'Agence Ahmed is a locally-rooted car rental agency based in the vibrant city of Tangier. With a focus on reliability, affordability, and customer satisfaction, Agence Ahmed has become a trusted name among both locals and tourists. The agency offers a carefully maintained fleet of compact, mid-size, and SUV vehicles to cater to a wide variety of travel needs, whether for short city commutes or long-distance excursions. Their team prides itself on a personalized, client-first approach, providing recommendations and support tailored to each renter’s specific itinerary. With convenient booking options and flexible rental terms, Agence Ahmed ensures that every customer experiences the freedom and comfort of exploring Morocco at their own pace.', 'https://amtartv.com/wp-content/uploads/2025/06/agency_ahmed.png', 'ahmedAgency@gmail.com', '06123456789', 'Tangier', 'tangier-mghogha-morocco', 0.00, 1702.00, '2025-06-18 09:36:06', 'active'),
(2, 'Elite Voyages', 2, 'Elite Voyages is a premier car rental and travel service provider headquartered in Casablanca. Renowned for its high standards of service and luxury-oriented approach, Elite Voyages caters to business professionals, international tourists, and event organizers seeking sophistication, punctuality, and comfort. Their fleet includes top-tier sedans, executive vehicles, and luxury SUVs—all meticulously maintained to guarantee safety and prestige. Beyond car rental, Elite Voyages also offers chauffeur-driven services and curated travel experiences across Morocco, blending elegance with convenience. With a multilingual team and 24/7 customer support, Elite Voyages stands out as a symbol of professionalism and elite mobility solutions.', 'http://amtartv.com/wp-content/uploads/2025/06/Elite-Voyages.png', 'contact@elitevoyages.com', '06543219876', 'Casablanca', 'casablanca-anfa-morocco', 0.00, 0.00, '2025-06-18 09:36:06', 'active'),
(3, 'Sahara Travel', 3, 'Based in the heart of Marrakech, Sahara Travel is a car rental agency that specializes in connecting travelers with the breathtaking landscapes of Morocco. Whether you\'re planning a desert trek, a mountain getaway, or a coastal adventure, Sahara Travel provides the perfect vehicle—ranging from rugged 4x4s ideal for off-road terrain to comfortable vans for group trips. The agency’s local expertise is one of its strongest assets, offering not just vehicles but also guidance on the best routes, hidden gems, and cultural experiences. Sahara Travel is committed to providing authentic, adventure-ready services with a strong focus on customer care, safety, and enriching journeys through the diverse Moroccan landscape.', 'http://amtartv.com/wp-content/uploads/2025/06/Sahara-Travel.png', 'info@saharatravel.ma', '06234567890', 'Marrakech', 'marrakech-medina-morocco', 0.00, 0.00, '2025-06-18 09:36:06', 'active'),
(4, 'Atlas Explorer', 4, 'Atlas Explorer is a dynamic car rental and tourism agency based in Fes, offering both transportation solutions and immersive travel experiences throughout Morocco. Ideal for solo travelers, families, and guided groups, the agency combines a diverse fleet with a passion for exploration. From compact city cars to powerful all-terrain vehicles, each rental is backed by attentive service and local insight. Atlas Explorer also partners with experienced guides to offer excursions across the Atlas Mountains, ancient medinas, and UNESCO World Heritage sites. Their mission is to empower travelers to navigate Morocco with confidence and curiosity, with flexible rental options and multilingual assistance always available.', 'http://amtartv.com/wp-content/uploads/2025/06/Atlas-Explorer.png', 'support@atlasexplorer.com', '06789123456', 'Fes', 'fes-ville-nouvelle-morocco', 0.00, 0.00, '2025-06-18 09:36:06', 'active'),
(5, 'Atlas Voyages', 5, 'Atlas Voyages is one of Morocco’s most respected travel and transportation brands, with over five decades of excellence in the tourism industry. Headquartered in the prestigious Gauthier district of Casablanca, Atlas Voyages offers an extensive selection of car rental options, ranging from economical compacts to luxury and executive models. The agency is well-known for its impeccable customer service, nationwide reach, and integrated travel planning—including hotel reservations, flight bookings, and tailored tour packages. Whether you\'re a leisure traveler seeking a relaxed vacation or a corporate client needing reliable logistics, Atlas Voyages delivers superior service that reflects its legacy of trust, innovation, and quality.', 'http://amtartv.com/wp-content/uploads/2025/06/Atlas-Voyages.png', 'contact@atlasvoyages.ma', '+212522220101', 'Casablanca', '18, Rue Sebta, Quartier Gauthier, Casablanca', 4.75, 0.00, '2025-06-18 09:36:06', 'active'),
(6, 'Maroc Horizon Tours', 3, 'Maroc Horizon Tours is a Marrakech-based travel agency that offers a holistic car rental and tourism experience designed to showcase the wonders of Morocco. With a client-centered philosophy, the company provides a wide selection of vehicles suitable for every type of traveler—from solo adventurers and families to small groups and professional delegations. In addition to self-drive rentals, Maroc Horizon Tours also offers guided tours, airport transfers, and customized itineraries that explore Morocco\'s rich cultural tapestry. With a seasoned staff, deep regional knowledge, and a commitment to excellence, the agency stands as a gateway to both discovery and comfort. Every trip booked through Maroc Horizon Tours is not just a rental—it’s a personalized journey into the heart of Moroccan heritage and hospitality.', 'http://amtartv.com/wp-content/uploads/2025/06/Maroc-Horizon-Tours.png', 'info@marochorizontours.com', '+212661234567', 'Marrakech', 'Rue Ibn Aïcha, Gueliz, Marrakech', 4.60, 0.00, '2025-06-18 09:36:06', 'active'),
(7, 'Afilal Agency', 1, '2525252525', 'https://0a411afb0c598242cc95-1df470064133d6bc5c471837468f475c.ssl.cf3.rackcdn.com/publish/wp-content/uploads/2021/01/leeds-dealers-9-580x387.jpg', 'support@afagency.com', '+21255443322', 'Tangier', 'Solicode Tanger', 0.00, 35.00, '2025-06-18 09:36:06', 'active'),
(8, 'Meed Agency', 1, 'uyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyfddddddddddddddddd', 'https://0a411afb0c598242cc95-1df470064133d6bc5c471837468f475c.ssl.cf3.rackcdn.com/publish/wp-content/uploads/2021/01/leeds-dealers-9-580x387.jpg', 'support@meedagency.com', '+21255443322', 'Tangier', 'Solicode Tanger', 0.00, 50.00, '2025-06-18 09:36:06', 'blocked');

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
(7, 1, 1, 4.50, 'Very professional service, clean cars and friendly staff.', '2025-06-15'),
(8, 7, 2, 3.00, 'Decent experience, but the car was not very clean.', '2025-06-15'),
(9, 1, 3, 5.00, 'Amazing desert tour vehicle! Highly recommended.', '2025-06-15'),
(10, 1, 4, 2.50, 'Had issues with the booking system and delays.', '2025-06-15'),
(11, 1, 5, 4.00, 'Trusted agency with smooth check-in process.', '2025-06-15'),
(12, 1, 6, 3.50, 'The car was good, but the price felt high.', '2025-06-15'),
(13, 2, 1, 2.00, 'Not satisfied. Car had technical problems.', '2025-06-15'),
(14, 2, 2, 4.50, 'Fantastic team and luxury vehicles.', '2025-06-15'),
(15, 2, 3, 4.00, 'Helpful staff and well-maintained cars.', '2025-06-15'),
(16, 2, 4, 3.50, 'Good service, a bit slow at pickup.', '2025-06-15'),
(17, 2, 5, 5.00, 'Flawless experience from booking to return.', '2025-06-15'),
(18, 2, 6, 2.50, 'Car worked fine but poor customer service.', '2025-06-15'),
(19, 3, 1, 4.00, 'Good experience overall. Would rent again.', '2025-06-15'),
(20, 3, 2, 2.00, 'Car was okay but customer service needs improvement.', '2025-06-15'),
(21, 3, 3, 3.00, 'Average experience.', '2025-06-15'),
(22, 3, 4, 4.50, 'Super clean vehicles and polite team.', '2025-06-15'),
(23, 7, 5, 3.50, 'Satisfactory, nothing exceptional.', '2025-06-15'),
(24, 3, 6, 5.00, 'Top-tier experience in Marrakech!', '2025-06-15'),
(25, 4, 1, 1.50, 'Bad brakes and noisy engine. Disappointed.', '2025-06-15'),
(26, 4, 2, 4.00, 'Excellent cars and helpful staff.', '2025-06-15'),
(27, 4, 3, 3.50, 'Pretty good, though a bit pricey.', '2025-06-15'),
(28, 4, 4, 4.00, 'Reliable and punctual.', '2025-06-15'),
(29, 4, 5, 2.00, 'Expected more based on reviews.', '2025-06-15'),
(30, 4, 6, 3.00, 'Decent but room for improvement.', '2025-06-15'),
(31, 7, 1, 5.00, 'Perfect experience! Will book again.', '2025-06-15'),
(32, 5, 2, 3.50, 'Good vehicles but slow processing.', '2025-06-15'),
(33, 5, 3, 4.50, 'Loved the desert-ready SUV!', '2025-06-15'),
(34, 5, 4, 2.00, 'Long wait times. Not impressed.', '2025-06-15'),
(35, 5, 5, 4.00, 'Great customer service.', '2025-06-15'),
(36, 5, 6, 1.50, 'Had mechanical issues and no support.', '2025-06-15'),
(37, 7, 2, 4.25, 'Its Very Nice Service and Good Cars Its Recomended From me', '2025-06-16');

-- --------------------------------------------------------

--
-- Structure de la table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `status` varchar(15) DEFAULT 'waiting' CHECK (`status` in ('waiting','reserved','canceled','completed')),
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `booking`
--

INSERT INTO `booking` (`booking_id`, `user_id`, `car_id`, `booking_date`, `status`, `start_date`, `end_date`, `total_price`) VALUES
(1, 7, 87, '0000-00-00', 'waiting', '2025-06-19', '2025-06-27', 0.00),
(2, 7, 87, '2025-06-17', 'completed', '2025-06-28', '2025-07-04', 10500.00),
(8, 7, 1, '2025-06-17', 'reserved', '2025-06-18', '2025-06-26', 2250.00),
(9, 1, 1, '2025-06-19', 'waiting', '2025-06-27', '2025-07-11', 3750.00);

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
  `isAutomatic` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('active','blocked') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `car`
--

INSERT INTO `car` (`car_id`, `agency_id`, `car_name`, `car_rating`, `description`, `model`, `places`, `brand`, `price_per_day`, `car_type`, `availability_status`, `image_url`, `car_fuel`, `kilometers`, `isAutomatic`, `status`) VALUES
(1, 1, 'Dacia Logan', 3.50, 'Compact and efficient sedan', '2017', 5, 'Dacia', 250.00, 'Sedan', 'available', 'https://promotionaumaroc.com/wp-content/uploads/2016/12/dacia-logan-maroc-voiture-neuve-promotion-2017.jpg', 'Gasoline', 65100, 1, 'blocked'),
(2, 2, 'Volkswagen Golf', 3.50, 'Reliable family hatchback', '2019', 5, 'Volkswagen', 370.00, 'Hatchback', 'available', 'https://media.ed.edmunds-media.com/volkswagen/golf/2019/oem/2019_volkswagen_golf_4dr-hatchback_14t-s_fq_oem_1_1600.jpg', 'Diesel', 45000, 1, 'active'),
(3, 3, 'Renault Zoe', 3.50, 'Electric compact city car', '2021', 5, 'Renault', 500.00, 'Electric / Hybrid', 'booked', 'https://www.larevueautomobile.com/images/fiche-technique/2021/Renault/ZOE/Renault_ZOE_MD_0.jpg', 'Electrical', 15000, 1, 'active'),
(4, 4, 'Tesla Model 3', 3.50, 'Luxurious electric sedan', '2022', 5, 'Tesla', 950.00, 'Electric / Hybrid', 'available', 'https://www.greencarguide.co.uk/wp-content/uploads/2022/05/Tesla-Model-3-Long-Range-001-low-res.jpeg', 'Electrical', 10000, 1, 'active'),
(5, 1, 'BMW 5 Series', 3.50, 'Premium German luxury', '2021', 5, 'BMW', 1100.00, 'Luxury', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2021-bmw-540i-xdrive-370-edit-1608066218.jpg?crop=0.579xw:0.487xh;0.0785xw,0.472xh&resize=1200:*', 'Diesel', 20000, 1, 'active'),
(6, 2, 'Mercedes E-Class', 3.50, 'Elegant and modern', '2022', 5, 'Mercedes', 1200.00, 'Luxury', 'booked', 'https://images.prismic.io/carwow/8038ff9f-476b-402e-8ac3-13731b4e2dce_Mercedes+E-Class+Front+%C2%BE+moving.jpg', 'Hybrid', 18000, 1, 'active'),
(7, 3, 'Kia Sportage', 3.50, 'Spacious family SUV', '2020', 5, 'Kia', 430.00, 'SUV', 'available', 'https://www.mcgrathautoblog.com/wp-content/uploads/2020/01/2020-Kia-Sportage-980x550.jpg', 'Gasoline', 30000, 1, 'active'),
(8, 4, 'Toyota Highlander', 3.50, 'Large hybrid SUV', '2021', 7, 'Toyota', 800.00, 'Electric / Hybrid', 'booked', 'https://ecoloauto.com/?attachment_id=43316', 'Hybrid', 23000, 1, 'active'),
(9, 1, 'Fiat Panda', 3.50, 'Economic city car', '2018', 4, 'Fiat', 220.00, 'Hatchback', 'available', 'https://assets.choosemycar.com/vehicles/large/6682787_303_4288_vehicle-6682787-001-20250126-072502-9e4bc7a1286cdf8e777cea0f839ceebd2c82bf014ea281f0509ae6d43bb5ccb1.jpg', 'Gasoline', 55000, 0, 'active'),
(10, 2, 'Peugeot 208', 3.50, 'Strong and compact', '2020', 5, 'Peugeot', 300.00, 'Hatchback', 'available', 'https://images.caradisiac.com/logos-ref/gamme/gamme--peugeot-208/S8-gamme--peugeot-208.jpg', 'Gasoline', 25000, 0, 'active'),
(11, 3, 'BMW i3', 3.50, 'Electric hatchback', '2021', 4, 'BMW', 620.00, 'Electric / Hybrid', 'booked', 'https://cloud.leparking.fr/2024/07/05/03/32/bmw-i3-2021-bmw-i3-electric-for-sale_9121684224.jpg', 'Electrical', 17000, 1, 'active'),
(12, 4, 'Hyundai Tucson', 3.50, 'Spacious SUV', '2019', 5, 'Hyundai', 490.00, 'SUV', 'available', 'https://media.ed.edmunds-media.com/hyundai/tucson/2019/oem/2019_hyundai_tucson_4dr-suv_ultimate_fq_oem_1_1600.jpg', 'Diesel', 41000, 1, 'active'),
(13, 1, 'Audi A4', 3.50, 'Elegant compact sedan', '2021', 5, 'Audi', 950.00, 'Luxury', 'available', 'https://images.carexpert.com.au/resize/800/-/app/uploads/2021/04/2021-Audi-A4-Avant-45-TFSI-quattro-S-line-HERO.jpg', 'Gasoline', 20000, 1, 'active'),
(14, 2, 'Toyota Corolla', 3.50, 'Reliable sedan', '2020', 5, 'Toyota', 400.00, 'Sedan', 'available', 'https://ddc1.s3.amazonaws.com/Nji8QtRthmrSoFn9ByAh5tZj/CDy2BvBgoiXPo024/Vm3pWw%3D%3D/BzK1Evh8oCjcrViyPyM5/secc2.jpg', 'Hybrid', 30000, 1, 'active'),
(15, 3, 'Range Rover Velar', 3.50, 'Powerful SUV', '2022', 5, 'Range Rover', 1250.00, 'Luxury', 'booked', 'https://www.carscoops.com/wp-content/uploads/2021/08/2022-Land-Rover-Range-Rover-Velar-1.jpg', 'Diesel', 18000, 1, 'active'),
(16, 4, 'Renault Scenic', 3.50, 'Minivan for family trips', '2019', 7, 'Renault', 350.00, 'Van / Minivan', 'available', 'https://img.autoabc.lv/Renault-Scenic/Renault-Scenic_2016_Minivens_2361420130.jpg', 'Diesel', 38000, 0, 'active'),
(17, 1, 'Tesla Model Y', 3.50, 'Electric futuristic SUV', '2023', 5, 'Tesla', 1100.00, 'Electric / Hybrid', 'available', 'https://autoimage.capitalone.com/cms/Auto/assets/images/2622-hero-tesla-model-y-review-and-test-drive.jpg', 'Electrical', 8000, 1, 'active'),
(18, 2, 'Honda Jazz', 3.50, 'Hybrid city car', '2021', 5, 'Honda', 360.00, 'Electric / Hybrid', 'available', 'https://carsguide-res.cloudinary.com/image/upload/c_fit,h_841,w_1490,f_auto,t_cg_base/v1/editorial/story/hero_image/2018-honda-jazz-vti-s-hatchback-white-mitchell-tulk-1001x565-(1).jpg', 'Hybrid', 27000, 1, 'active'),
(19, 3, 'Nissan Juke', 3.50, 'Compact stylish SUV', '2020', 5, 'Nissan', 410.00, 'SUV', 'booked', 'https://media.drive.com.au/obj/tx_q:50,rs:auto:1920:1080:1/caradvice/private/mpddqshdikj8aohv76lb', 'Gasoline', 32000, 1, 'active'),
(20, 4, 'Audi A6', 3.50, 'Executive luxury sedan', '2022', 5, 'Audi', 1050.00, 'Luxury', 'available', 'https://media.ed.edmunds-media.com/audi/a6/2022/oem/2022_audi_a6_sedan_prestige_fq_oem_1_1600.jpg', 'Hybrid', 14000, 1, 'active'),
(21, 1, 'Volkswagen Tiguan', 3.50, 'Spacious SUV', '2021', 5, 'Volkswagen', 520.00, 'SUV', 'available', 'https://ecoloauto.com/36734-2/2021-volkswagen-tiguan-30/', 'Diesel', 24000, 1, 'active'),
(22, 2, 'Mercedes EQC', 3.50, 'Electric luxury SUV', '2022', 5, 'Mercedes', 1150.00, 'Electric / Hybrid', 'available', 'https://media.drive.com.au/obj/tx_q:50,rs:auto:1920:1080:1/driveau/upload/cms/uploads/nmyj3fbw4eo87l8tultu', 'Electrical', 12000, 1, 'active'),
(23, 3, 'Toyota RAV4', 3.50, 'Hybrid crossover', '2021', 5, 'Toyota', 540.00, 'Electric / Hybrid', 'booked', 'https://images.cars.com/cldstatic/wp-content/uploads/toyota-rav4-prime-2021-01-angle--exterior--front--grey.jpg', 'Hybrid', 20000, 1, 'active'),
(24, 4, 'BMW X5', 3.50, 'Luxurious SUV', '2022', 5, 'BMW', 1300.00, 'Luxury', 'available', 'https://www.edmunds.com/assets/m/bmw/x5-m/2021/oem/2021_bmw_x5-m_4dr-suv_base_fq_oem_1_600.jpg', 'Diesel', 17000, 1, 'active'),
(25, 1, 'Ford Fiesta', 3.50, 'Compact  hatchback hatchback', '2018', 5, 'Ford', 270.00, 'Hatchback', 'available', 'https://cdn.abcmoteur.fr/wp-content/uploads/2018/09/14-8-e1535990091532-1.jpg', 'Gasoline', 48000, 0, 'active'),
(26, 2, 'Opel Zafira', 3.50, 'Practical minivan', '2019', 7, 'Opel', 400.00, 'Van / Minivan', 'booked', 'https://www.largus.fr/images/styles/max_1300x1300/public/images/opel-505549-redimensionner.jpg?itok=OL8GT025', 'Diesel', 36000, 0, 'active'),
(27, 3, 'Hyundai Ioniq', 3.50, 'Efficient hybrid sedan', '2020', 5, 'Hyundai', 490.00, 'Electric / Hybrid', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2020-hyundai-ioniq-hybrid-102-1590604533.jpg', 'Hybrid', 22000, 1, 'active'),
(28, 4, 'Mercedes CLA', 3.50, 'Stylish coupe', '2021', 4, 'Mercedes', 990.00, 'Coupe', 'available', 'https://di-uploads-pod3.dealerinspire.com/fletcherjonesmbnewport/uploads/2024/07/Newport-CLA-1024x683.png', 'Gasoline', 19000, 1, 'active'),
(29, 1, 'Ford Puma', 3.50, 'Compact crossover', '2021', 5, 'Ford', 440.00, 'SUV', 'booked', 'https://images.caradisiac.com/logos/4/4/3/9/264439/S8-essai-ford-puma-ecoboost-125-dct-7-2021-la-seule-offre-avec-une-boite-automatique-187678.jpg', 'Gasoline', 21000, 1, 'active'),
(30, 2, 'Audi Q3', 3.50, 'Luxury compact SUV', '2021', 5, 'Audi', 890.00, 'Luxury', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2021-audi-q3-mmp-1-1590773404.jpg', 'Gasoline', 23000, 1, 'active'),
(31, 3, 'Citroën ë-Berlingo', 3.50, 'Mini electric van', '2022', 5, 'Citroën', 490.00, 'Van / Minivan', 'available', 'https://journalauto.com/wp-content/uploads/2021/12/Citroen.jpg', 'Electrical', 15000, 1, 'active'),
(32, 4, 'Lexus ES', 3.50, 'Hybrid sedan', '2021', 5, 'Lexus', 950.00, 'Electric / Hybrid', 'booked', 'https://gofatherhood.com/wp-content/uploads/2021/07/2021-lexus-9012a-es-awd-9.jpg', 'Hybrid', 17000, 1, 'active'),
(33, 1, 'Porsche Taycan', 3.50, 'Electric performance', '2022', 4, 'Porsche', 2000.00, 'Luxury', 'available', 'https://ev-database.org/img/auto/Porsche_Taycan_GTS/Porsche_Taycan_GTS-01@2x.jpg', 'Electrical', 12000, 1, 'active'),
(34, 2, 'BMW 4 Series', 3.50, 'Premium coupe', '2021', 4, 'BMW', 1250.00, 'Coupe', 'available', 'https://www.inquirer.com/resizer/9QiQt9p0eLpPy2TaxMylqO3sI-U=/arc-anglerfish-arc2-prod-pmn/public/PX33263A5VEWZCAC7NXXII5TRY.jpg', 'Gasoline', 15000, 1, 'active'),
(35, 3, 'Ford Explorer Hybrid', 3.50, 'Large hybrid SUV', '2022', 7, 'Ford', 980.00, 'Electric / Hybrid', 'booked', 'https://i.gaw.to/content/photos/53/99/539909-ford-explorer-hybride-2022-de-montreal-a-new-york-a-son-volant.jpeg', 'Hybrid', 16000, 1, 'active'),
(36, 4, 'Renault Captur', 3.50, 'Compact crossover', '2020', 5, 'Renault', 350.00, 'SUV', 'available', 'https://www.automobile-magazine.fr/asset/cms/167640/config/116450/all-new-renault-captur-arctic-white-010.jpg', 'Gasoline', 35000, 0, 'active'),
(38, 2, 'Subaru Outback', 3.50, 'Reliable and rugged wagon', '2021', 5, 'Subaru', 460.00, 'SUV', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2021-suabru-outback-mmp-1-1598639461.jpg?crop=0.801xw:0.675xh;0.0673xw,0.159xh&resize=2048:*', 'Gasoline', 25000, 1, 'active'),
(39, 3, 'Volkswagen Passat', 3.50, 'Comfortable midsize sedan', '2020', 5, 'Volkswagen', 420.00, 'Sedan', 'available', 'https://images.cars.com/cldstatic/wp-content/uploads/volkswagen-passat-2020-01-angle--blue--exterior--front--mountains.jpg', 'Diesel', 28000, 1, 'active'),
(40, 4, 'Honda Civic', 3.50, 'Efficient compact car', '2021', 5, 'Honda', 390.00, 'Sedan', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2021-honda-civic-mmp-1-1595005323.jpg?crop=0.753xw:0.635xh;0.143xw,0.365xh&resize=2048:*', 'Gasoline', 23000, 1, 'active'),
(41, 1, 'Chevrolet Malibu', 3.50, 'Stylish midsize sedan', '2019', 5, 'Chevrolet', 370.00, 'Sedan', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2018-chevrolet-malibu-rs-1544730044.jpg', 'Gasoline', 35000, 0, 'active'),
(42, 2, 'Hyundai Elantra', 3.50, 'Affordable compact sedan', '2022', 5, 'Hyundai', 360.00, 'Sedan', 'available', 'https://www.hyundainews.com/assets/images/thumbnail-true/46447-Large444082021Hyundai1188.jpg', 'Gasoline', 21000, 1, 'active'),
(43, 3, 'Kia Sorento', 3.50, 'Mid-size SUV with 7 seats', '2021', 7, 'Kia', 520.00, 'SUV', 'available', 'https://d2v1gjawtegg5z.cloudfront.net/posts/preview_images/000/012/739/original/2021-Kia-Sorento.png?1711460732', 'Gasoline', 19000, 1, 'active'),
(44, 4, 'Ford Escape', 3.50, 'Compact SUV with good tech', '2020', 5, 'Ford', 470.00, 'SUV', 'available', 'https://www.autohebdo.net/editorial/media/172296/2020-ford-escape-titanium-hybrid-01-dr-fr.jpg?width=1920&height=1080&v=1da45cc870752e0', 'Gasoline', 24000, 1, 'active'),
(45, 1, 'Jeep Wrangler', 3.50, 'Iconic off-road SUV', '2019', 5, 'Jeep', 550.00, 'SUV', 'booked', 'https://www.cnet.com/a/img/resize/b865ee39c551f2affdbc5d86d46360cfeec716b4/hub/2019/11/18/20d90d41-927e-4d11-ad35-ac9e5f17cd0b/ogi-jeep-wrangler-rubicon-etorque-2019-8507.png?auto=webp&fit=crop&height=675&width=1200', 'Gasoline', 30000, 0, 'active'),
(46, 2, 'Nissan Altima', 3.50, 'Comfortable midsize sedan', '2021', 5, 'Nissan', 400.00, 'Sedan', 'available', 'https://media.ed.edmunds-media.com/nissan/altima/2020/oem/2020_nissan_altima_sedan_25-platinum_fq_oem_1_1600.jpg', 'Gasoline', 22000, 1, 'active'),
(47, 3, 'Tesla Model S', 3.50, 'Luxury electric sedan', '2022', 5, 'Tesla', 1500.00, 'Electric / Hybrid', 'available', 'https://www.thedrive.com/wp-content/uploads/images-by-url-td/content/2021/07/tesla-plaid-lead.jpg?quality=85', 'Electrical', 10000, 1, 'active'),
(48, 4, 'Volkswagen Atlas', 3.50, 'Full-size SUV', '2021', 7, 'Volkswagen', 600.00, 'SUV', 'available', 'https://media.ed.edmunds-media.com/volkswagen/atlas/2021/oem/2021_volkswagen_atlas_4dr-suv_v6-sel-premium-r-line-4motion_fq_oem_1_1600.jpg', 'Gasoline', 20000, 1, 'active'),
(49, 1, 'Audi Q5', 3.50, 'Luxury compact SUV', '2020', 5, 'Audi', 900.00, 'Luxury', 'available', 'https://i.gaw.to/vehicles/photos/40/19/401938-2020-audi-q5.jpg?640x400', 'Gasoline', 18000, 1, 'active'),
(50, 2, 'BMW X3', 3.50, 'Sporty luxury SUV', '2021', 5, 'BMW', 950.00, 'Luxury', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2021-bmw-x3-m-mm-1-1604358685.jpg', 'Diesel', 16000, 1, 'active'),
(51, 3, 'Mercedes GLC', 3.50, 'Luxury compact SUV', '2022', 5, 'Mercedes', 1100.00, 'Luxury', 'available', 'https://dealerinspire-image-library-prod.s3.us-east-1.amazonaws.com/images/mJFlPQS3nlg1UQswtgZZ0PhfoDEscVNEUpULtizM.jpg', 'Hybrid', 12000, 1, 'active'),
(52, 4, 'Toyota Camry', 3.50, 'Reliable midsize sedan', '2021', 5, 'Toyota', 450.00, 'Sedan', 'available', 'https://i.gaw.to/content/photos/43/32/433242-toyota-camry-2021-nouvelle-version-hybride-et-plus-de-securite.jpeg', 'Hybrid', 25000, 1, 'active'),
(53, 1, 'Honda Accord', 3.50, 'Popular midsize sedan', '2022', 5, 'Honda', 480.00, 'Sedan', 'available', 'https://i.gaw.to/content/photos/52/32/523292-honda-accord-hybride-2022-l-hybride-pseudo-sportive.jpeg', 'Gasoline', 18000, 1, 'active'),
(54, 2, 'Mazda3', 3.50, 'Sporty compact sedan', '2021', 5, 'Mazda', 400.00, 'Sedan', 'available', 'https://i.gaw.to/content/photos/43/21/432153-une-mazda3-turbo-s-en-vient-bel-et-bien-pour-2021.jpeg', 'Gasoline', 20000, 1, 'active'),
(55, 3, 'Subaru Forester', 3.50, 'Practical SUV', '2020', 5, 'Subaru', 480.00, 'SUV', 'available', 'https://media.drive.com.au/obj/tx_q:70,rs:auto:1920:1080:1/caradvice/private/joieqgia7azuisrmjc6c', 'Gasoline', 22000, 1, 'active'),
(56, 4, 'Chevrolet Equinox', 3.50, 'Compact SUV', '2019', 5, 'Chevrolet', 450.00, 'SUV', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2018-chevrolet-equinox-mmp-1-1557249905.jpg', 'Gasoline', 27000, 0, 'active'),
(57, 1, 'Hyundai Santa Fe', 3.50, 'Mid-size SUV', '2021', 7, 'Hyundai', 540.00, 'SUV', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2021-hyundai-santa-fe-mmp-1-1617287216.jpg', 'Gasoline', 21000, 1, 'active'),
(58, 2, 'Kia Forte', 3.50, 'Compact sedan', '2022', 5, 'Kia', 370.00, 'Sedan', 'available', 'https://www.autotrader.ca/editorial/media/s2sjylme/2022-kia-forte-gt-line-02-jm.jpg?v=1d86a7bc50aca50', 'Gasoline', 17000, 1, 'active'),
(59, 3, 'Ford Explorer', 3.50, 'Full-size SUV', '2020', 7, 'Ford', 650.00, 'SUV', 'available', 'https://media.drive.com.au/obj/tx_q:50,rs:auto:1920:1080:1/caradvice/private/1c5122a569cc911603ddb444eff3ca89', 'Gasoline', 28000, 1, 'active'),
(60, 4, 'Volkswagen Jetta', 3.50, 'Compact sedan', '2021', 5, 'Volkswagen', 390.00, 'Sedan', 'available', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRDKpQb6hczNhPsLjuFJwNq0CHrNrnBLDXeZA&s', 'Gasoline', 23000, 1, 'active'),
(61, 1, 'Nissan Rogue', 3.50, 'Popular compact SUV', '2021', 5, 'Nissan', 480.00, 'SUV', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2021-nissan-rogue-107-1591910983.jpg?crop=0.779xw:0.656xh;0.149xw,0.344xh&resize=2048:*', 'Gasoline', 21000, 1, 'active'),
(62, 5, 'Toyota Corolla', 3.50, 'Comfortable sedan car', '2024', 5, 'Toyota', 350.00, 'Sedan', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2024-toyota-gr-corolla-premium-exterior-107-662035ecce76c.jpg?crop=0.673xw:0.566xh;0.157xw,0.269xh&resize=1200:*', 'Gasoline', 0, 1, 'active'),
(63, 6, 'Renault Clio ', 3.50, 'Compact and efficient', '2023', 5, 'Renault', 300.00, 'Hatchback', 'available', 'https://media.autoexpress.co.uk/image/private/s--X-WVjvBW--/f_auto,t_content-image-full-desktop@1/v1698161627/carbuyer/2023/10/Renault%20Clio%20UK%20review-2.jpg', 'Gasoline', 0, 1, 'active'),
(64, 6, 'Peugeot 3008', 3.50, 'Stylish SUV', '2022', 5, 'Peugeot', 450.00, 'SUV', 'available', 'https://static.moniteurautomobile.be/imgcontrol/images_tmp/clients/moniteur/c680-d465/content/medias/images/cars/peugeot/3008/peugeot--3008--2022/peugeot--3008--2022-m-1.jpg', 'Diesel', 0, 1, 'active'),
(65, 6, 'Dacia Duster ', 3.50, 'Affordable SUV', '2021', 5, 'Dacia', 280.00, 'SUV', 'available', 'https://im.qccdn.fr/node/actualite-dacia-duster-2021-premieres-impressions-94060/thumbnail_800x480px-138098.jpg', 'Gasoline', 0, 1, 'active'),
(66, 5, 'Hyundai Tucson ', 3.50, 'Reliable and spacious', '2020', 5, 'Hyundai', 400.00, 'SUV', 'available', 'https://hips.hearstapps.com/hmg-prod/images/2019-hyundai-tucson-1544723862.jpg', 'Gasoline', 0, 1, 'active'),
(67, 5, 'Volkswagen Golf', 3.50, 'Compact hatchback', '2019', 5, 'Volkswagen', 320.00, 'Hatchback', 'available', 'https://cdn05.carsforsale.com/00b62721569e1bf2a9b4d719f7d493b49a/1280x960/2015-volkswagen-golf-tdi-s-4dr-hatchback-6a.jpg', 'Gasoline', 0, 1, 'active'),
(68, 6, 'Ford Focus', 3.50, 'Popular compact car', '2018', 5, 'Ford', 310.00, 'Sedan', 'available', 'https://carsguide-res.cloudinary.com/image/upload/c_fit,h_841,w_1490,f_auto,t_cg_base/v1/editorial/2018-ford-focus-trend-hatch-red-mitchell-tulk-1200x800-(1).jpg', 'Gasoline', 0, 1, 'active'),
(69, 5, 'Kia Sportage', 3.50, 'Compact SUV', '2017', 5, 'Kia', 350.00, 'SUV', 'available', 'https://www.cnet.com/a/img/resize/d0a679fdf33d19508ebd5ab3642dbb1fa21358df/hub/2016/03/07/cffb8a84-2b9c-4160-983f-f3ff09ea53e3/2017-kia-sportage-sx-1.jpg?auto=webp&width=1200', 'Gasoline', 0, 1, 'active'),
(70, 5, 'Mazda CX-5', 3.50, 'SUV with great handling', '2016', 5, 'Mazda', 370.00, 'SUV', 'available', 'https://media.ed.edmunds-media.com/mazda/cx-5/2016/oem/2016_mazda_cx-5_4dr-suv_grand-touring_fq_oem_1_1600.jpg', 'Gasoline', 0, 1, 'active'),
(71, 6, 'Nissan Qashqai', 3.50, 'Popular crossover', '2015', 5, 'Nissan', 340.00, 'SUV', 'available', 'https://editorial.pxcrush.net/carsales/general/editorial/ge4743318632277466917.jpg?width=1024&height=682', 'Gasoline', 0, 1, 'active'),
(72, 6, 'Citroen C3', 3.50, 'Compact city car', '2014', 5, 'Citroen', 280.00, 'Hatchback', 'available', 'https://static.moniteurautomobile.be/imgcontrol/images_tmp/clients/moniteur/c680-d465/content/medias/images/cars/citroen/c3/citroen--c3--2014/citroen--c3--2014-t-1.jpg', 'Gasoline', 0, 1, 'active'),
(73, 5, 'Honda Civic', 3.50, 'Reliable sedan', '2013', 5, 'Honda', 330.00, 'Sedan', 'available', 'https://i.gaw.to/content/photos/11/26/112636_2013_Honda_Civic.jpg', 'Gasoline', 0, 1, 'active'),
(74, 5, 'Opel Astra', 3.50, 'Popular compact car', '2012', 5, 'Opel', 290.00, 'Hatchback', 'available', 'https://www.automoli.com/common/vehicles/_assets/img/gallery/f107/opel-astra-j-facelift-2012.jpg', 'Gasoline', 0, 1, 'active'),
(75, 5, 'BMW X1 2011', 3.50, 'Luxury compact SUV', '2011', 5, 'BMW', 600.00, 'SUV', 'available', 'https://hips.hearstapps.com/hmg-prod/amv-prod-cad-assets/images/09q3/267589/2011-bmw-x1-review-car-and-driver-photo-290114-s-original.jpg', 'Diesel', 0, 1, 'active'),
(76, 6, 'Mercedes A-Class 2010', 3.50, 'Premium hatchback', '2010', 5, 'Mercedes', 650.00, 'Hatchback', 'available', 'https://dealercontrol.co.za/cars-for-sale/ATN/1812/MERCEDES-BENZ-A180_ELEGANCE_A_T-77959.jpg', 'Gasoline', 0, 1, 'active'),
(77, 6, 'Audi A3 2009', 3.50, 'Compact premium car', '2009', 5, 'Audi', 620.00, 'Hatchback', 'available', 'https://www.edmunds.com/assets/m/audi/a3/2009/oem/2009_audi_a3_wagon_20t-quattro_fq_oem_1_500.jpg', 'Gasoline', 0, 1, 'active'),
(78, 6, 'Toyota RAV4 2008', 3.50, 'Reliable SUV', '2008', 5, 'Toyota', 400.00, 'SUV', 'available', 'https://i.gaw.to/vehicles/photos/00/22/002280_2008_Toyota_RAV4.jpg?640x400', 'Gasoline', 0, 1, 'active'),
(79, 5, 'Renault Megane 2007', 3.50, 'Compact sedan', '2007', 5, 'Renault', 280.00, 'Sedan', 'available', 'https://images.caradisiac.com/logos-ref/modele/modele--renault-megane-2/S8-modele--renault-megane-2.jpg', 'Gasoline', 0, 1, 'active'),
(80, 6, 'Peugeot 208 2006', 3.50, 'Compact hatchback', '2006', 5, 'Peugeot', 270.00, 'Hatchback', 'available', 'https://cdn-s-www.ledauphine.com/images/0DD70676-DA38-4A6F-ABC8-2D5E7025EA9E/NW_raw/la-207-qui-peinera-a-succeder-a-la-206-photo-peugeot-1581096286.jpg', 'Gasoline', 0, 1, 'active'),
(81, 5, 'Dacia Logan 2025', 3.50, 'Affordable sedan', '2025', 5, 'Dacia', 250.00, 'Sedan', 'available', 'https://www.321rentacar.ro/images/cars/dacia-logan-2025.png', 'Gasoline', 0, 1, 'active'),
(82, 5, 'Hyundai Elantra 2004', 3.50, 'Compact sedan', '2004', 5, 'Hyundai', 300.00, 'Sedan', 'available', 'https://file.kelleybluebookimages.com/kbb/base/house/2004/2004-Hyundai-Elantra-FrontSide_HYELAGLS041_505x309.jpg', 'Gasoline', 0, 1, 'active'),
(83, 6, 'Volkswagen Passat 2015', 3.50, 'Mid-size sedan', '2015', 5, 'Volkswagen', 350.00, 'Sedan', 'available', 'https://hips.hearstapps.com/hmg-prod/amv-prod-cad-assets/images/14q4/638369/2015-volkswagen-passat-euro-spec-first-drive-review-car-and-driver-photo-640296-s-original.jpg?fill=1:1&resize=1200:*', 'Diesel', 0, 1, 'active'),
(84, 5, 'Ford Fiesta 2002', 3.50, 'Small hatchback', '2002', 5, 'Ford', 260.00, 'Hatchback', 'available', 'https://img.autoabc.lv/ford-fiesta/ford-fiesta_2002_Hecbeks_15113121456_3.jpg', 'Gasoline', 0, 1, 'active'),
(85, 6, 'Kia Rio 2001', 3.50, 'Compact car', '2001', 5, 'Kia', 270.00, 'Hatchback', 'available', 'https://www.edmunds.com/assets/m/kia/rio/2001/oem/2001_kia_rio_sedan_base_fq_oem_1_500.jpg', 'Gasoline', 0, 1, 'active'),
(86, 6, 'Mazda 3 2000', 3.50, 'Reliable compact', '2000', 5, 'Mazda', 280.00, 'Sedan', 'available', 'https://upload.wikimedia.org/wikipedia/commons/6/6d/Mazda_3.jpg', 'Gasoline', 0, 1, 'active'),
(87, 1, 'Audi RS6', 4.90, 'The 2023 Audi RS6 Avant is a high-performance luxury sports wagon that combines power, practicality, and cutting-edge technology. Powered by a 4.0-liter twin-turbocharged V8 engine delivering 591 horsepower and 590 lb-ft of torque, it accelerates from 0 to 60 mph in just 3.5 seconds. Standard features include Audi’s quattro all-wheel drive, adaptive air suspension, and an 8-speed automatic transmission. The interior offers premium leather seating for 5, a panoramic sunroof, virtual cockpit, advanced driver-assist systems, and a high-resolution infotainment system with Apple CarPlay and Android Auto. With a sleek and aggressive design, 21-inch alloy wheels, and a spacious cargo area, the RS6 Avant is perfect for both spirited driving and daily comfort.', '2025', 5, 'Audi', 1500.00, 'Luxury', 'available', 'https://content.homenetiol.com/2000292/2143540/0x0/988af5705b81486088f2f4b9ce7764ad.jpg', 'Gasoline', 17503, 1, 'active'),
(89, 7, 'Dacia Logan', 0.00, 'hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh', '2025', 5, 'Dacia', 350.00, 'Sedan', 'available', 'https://dacia.press/wp-content/uploads/2024/10/Dacia-Logan-2025.jpg', 'Diesel', 54345, 0, 'active'),
(90, 8, 'Dacia Logan', 0.00, 'uyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyfddddddddddddddddd', '2025', 5, 'Dacia', 350.00, 'Sedan', 'available', 'https://dacia.press/wp-content/uploads/2024/10/Dacia-Logan-2025.jpg', 'Diesel', 43567, 0, 'active'),
(92, 1, 'Toyota Corolla ', 0.00, 'Reliable and fuel-efficient sedan. Smooth ride, great for city driving and long trips.', '2022', 5, 'Toyota ', 350.00, 'Sedan', 'available', 'https://www.autotrader.com/wp-content/uploads/2021/08/2022-toyota-corolla-hatchback-front-right.jpg?quality=75', 'Gasoline', 45862, 1, 'active');

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
(1, 7, 30, 5.00, 'Excellent service and the car was very clean!', '2025-06-02'),
(2, 2, 32, 4.00, 'The car was in good condition, but pick-up took some time.', '2025-06-02'),
(3, 4, 19, 3.00, 'It was okay, but the AC wasn’t working properly.', '2025-06-02'),
(4, 5, 3, 2.00, 'The car was not as described. Needs maintenance.', '2025-06-02'),
(5, 1, 5, 5.00, 'Very smooth ride. I’ll definitely book again.', '2025-06-02'),
(6, 2, 67, 4.00, 'Customer support was responsive. Car was fine.', '2025-06-02'),
(7, 5, 15, 1.00, 'Bad experience. The brakes were worn out.', '2025-06-02'),
(8, 1, 30, 5.00, 'Excellent service and the car was very clean!', '2025-06-07'),
(9, 2, 30, 4.00, 'The car was in good condition, but pick-up took some time.', '2025-06-07'),
(10, 4, 30, 3.00, 'It was okay, but the AC wasn’t working properly.', '2025-06-07'),
(11, 1, 87, 4.90, 'Absolutely thrilling to drive. The RS6 blends luxury and performance perfectly. Handles like a sports car but has space for the whole family.', '2025-06-10'),
(12, 2, 87, 4.70, 'The acceleration is insane, and the comfort is unmatched. Perfect for road trips and daily driving.', '2025-06-10'),
(13, 7, 87, 5.00, 'Dream car! Audi nailed it with the RS6. Interior tech, power, and looks — all top-notch.', '2025-06-10'),
(14, 4, 87, 4.80, 'Very refined yet aggressive. The V8 engine sounds incredible. A proper performance wagon.', '2025-06-10'),
(15, 5, 87, 4.60, 'Love everything except the fuel consumption. Still, it’s worth it for the power and style.', '2025-06-10'),
(16, 7, 50, 4.75, 'Very Nice Car and comfortable', '2025-06-16'),
(17, 7, 1, 1.75, 'Nice car', '2025-06-17'),
(18, 1, 13, 4.25, 'hhhhhhhhhddddd dddddddddddddddddd', '2025-06-18');

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `answer` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','answered') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `full_name`, `email`, `subject`, `message`, `answer`, `submitted_at`, `status`) VALUES
(1, 7, 'Afilal Mohamed', 'meedaf12@gmail.com', 'I need To test contact', 'How Are You', 'gggggggggggggggggggg', '2025-06-16 21:55:21', 'answered');

-- --------------------------------------------------------

--
-- Structure de la table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `agency_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'manual',
  `payment_status` enum('pending','completed','failed','refunded') DEFAULT 'completed',
  `payment_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `payments`
--

INSERT INTO `payments` (`id`, `agency_id`, `amount`, `payment_method`, `payment_status`, `payment_date`) VALUES
(1, 7, 35.00, 'card', 'completed', '2025-06-17 13:17:19'),
(2, 8, 50.00, 'card', 'completed', '2025-06-17 13:46:27'),
(3, 1, 708.00, 'stripe', 'completed', '2025-06-19 01:28:39'),
(7, 1, 494.00, 'stripe', 'completed', '2025-06-19 01:38:48'),
(8, 1, 500.00, 'stripe', 'completed', '2025-06-19 01:52:30');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(55) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `registration_date` date DEFAULT curdate(),
  `user_type` enum('customer','admin') NOT NULL DEFAULT 'customer',
  `status` enum('active','blocked') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `username`, `email`, `password`, `phone_number`, `registration_date`, `user_type`, `status`) VALUES
(1, 'John Doe', 'john_doe', 'john@example.com', '$2y$10$3/sEOtNHAn63Wa6Sdeqrperug0u4QzkBLTEwWACWDC8Qy9cJGJCZC', '0612345678', '2025-06-02', 'customer', 'active'),
(2, '', 'agency_procar', 'procar@example.com', '$2y$10$O7mU5UeDpYFeMuEwAkL95e4E5WuTIerPZ1EG5lhqvWTB8gGZ6n34G', '0623456789', '2025-06-02', '', 'active'),
(3, '', 'meedafilal', 'meedaf11@gmail.com', '$2y$10$O7mU5UeDpYFeMuEwAkL95e4E5WuTIerPZ1EG5lhqvWTB8gGZ6n34G', '0682564814', '2025-06-02', 'admin', 'active'),
(4, '', 'fatima_rider', 'fatima@example.com', '$2y$10$O7mU5UeDpYFeMuEwAkL95e4E5WuTIerPZ1EG5lhqvWTB8gGZ6n34G', '0645678901', '2025-06-02', 'customer', 'active'),
(5, '', 'rent4u_agency', 'rent4u@example.com', '$2y$10$O7mU5UeDpYFeMuEwAkL95e4E5WuTIerPZ1EG5lhqvWTB8gGZ6n34G', '0656789012', '2025-06-02', '', 'active'),
(7, 'Afilal Mohamed', 'afmeed', 'meedaf12@gmail.com', '$2y$10$3/sEOtNHAn63Wa6Sdeqrperug0u4QzkBLTEwWACWDC8Qy9cJGJCZC', '0625242621', '2025-06-15', 'customer', 'active');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `agency`
--
ALTER TABLE `agency`
  ADD PRIMARY KEY (`agency_id`),
  ADD KEY `fk_agency_owner` (`agency_owner_id`);

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
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `agency_id` (`agency_id`);

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
  MODIFY `agency_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `agency_review`
--
ALTER TABLE `agency_review`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `car`
--
ALTER TABLE `car`
  MODIFY `car_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT pour la table `car_review`
--
ALTER TABLE `car_review`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `agency`
--
ALTER TABLE `agency`
  ADD CONSTRAINT `fk_agency_owner` FOREIGN KEY (`agency_owner_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`agency_id`) REFERENCES `agency` (`agency_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
