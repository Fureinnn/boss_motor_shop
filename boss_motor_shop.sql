-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2025 at 10:46 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `boss_motor_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(50) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `created_at`, `updated_at`, `status`) VALUES
(7, 5, '2025-07-03 16:36:23', '2025-07-03 16:36:23', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

CREATE TABLE `cart_item` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `added_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `description`, `image_url`, `created_at`) VALUES
(1, 'Mufflers/Pipes', 'High-performance exhaust systems and mufflers', 'https://example.com/images/mufflers_category.jpg', '2025-06-30 17:51:02'),
(2, 'CVT Sets', 'Complete CVT transmission kits for scooters', 'https://example.com/images/cvt_category.jpg', '2025-06-30 17:51:02'),
(3, 'CVT Parts', 'Individual CVT components and accessories', 'https://example.com/images/cvt_parts_category.jpg', '2025-06-30 17:51:02'),
(4, 'Seats', 'Custom and performance motorcycle seats', 'https://example.com/images/seats_category.jpg', '2025-06-30 17:51:02'),
(5, 'Mags', 'Custom wheels and rims for motorcycles', 'https://example.com/images/mags_category.jpg', '2025-06-30 17:51:02');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_number` varchar(100) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `shipping_cost` decimal(10,2) DEFAULT 0.00,
  `order_status` varchar(50) DEFAULT 'pending',
  `payment_status` varchar(50) DEFAULT 'pending',
  `payment_method` varchar(50) NOT NULL,
  `shipping_address` text DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `shipped_date` datetime DEFAULT NULL,
  `delivered_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `sku` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `category_id`, `product_name`, `price`, `stock_quantity`, `sku`, `is_active`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 1, 'M3 Dual Tip Pipe', 8499.00, 10, 'M3-DUAL-001', 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTM1csj9Ne6Toc-bQpTyHRIVUpP0hSBAvddAmm2PtlrrYAsqXhjaBKJ5yvy09TcagYJQx4&usqp=CAU', '2025-06-30 17:51:02', '2025-06-30 17:51:02'),
(2, 1, 'JVT Shark Tip Pipe V4', 6500.00, 15, 'JVT-SHARK-004', 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQy14a87Bz1efZENVixhI-oJ8LoCBRxaDxT1MU1JCtvIGj_ocCeGawuWrIl-tQk-KGMVPY&usqp=CAU', '2025-06-30 17:51:02', '2025-06-30 17:51:02'),
(3, 1, 'Euro Bullet Pipe', 7800.00, 8, 'EURO-BULLET-002', 1, 'https://i.ebayimg.com/images/g/yyoAAOSwjGFjnZGH/s-l400.jpg', '2025-06-30 17:51:02', '2025-06-30 17:51:02'),
(4, 2, 'TSMP CVT Kit', 9500.00, 12, 'TSMP-CVT-125', 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRRRBLlIETfku8sYdGifU8UoSJt8RiH2pLnUQ&s', '2025-06-30 17:51:02', '2025-06-30 17:51:02'),
(5, 2, 'RCB CVT Kit', 11200.00, 5, 'RCB-CVT-155', 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTMhZHOssgXCzXB5JhJkw9YaD3xdLfkoQ5-96jD2zaPkFP-Nup5ZIDFReqHFNNulEVT7Xw&usqp=CAU', '2025-06-30 17:51:02', '2025-06-30 17:51:02'),
(6, 3, 'CRP Clutch Spring', 480.00, 47, 'CRP-CLUTCH-1000', 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT09BcWhOY3U6cqn8zGOAdvB3HwF4cROGKYZA&s', '2025-06-30 17:51:02', '2025-07-02 17:26:04'),
(7, 3, 'High Performance Drive Belt', 950.00, 30, 'HP-BELT-225', 1, 'https://www.recreationrevolution.ca/cdn/shop/products/HPX2251.jpg?v=1699674550', '2025-06-30 17:51:02', '2025-06-30 17:51:02'),
(8, 4, 'NMAX V2 Camel Seat', 2750.00, 7, 'NMAX-SEAT-CML', 1, 'https://motozonemania.com/cdn/shop/products/image_6e47d8e3-29bd-411d-9866-fcd72086e108_2048x.jpg?v=1629613454', '2025-06-30 17:51:02', '2025-06-30 17:51:02'),
(9, 4, 'Honda Click Racing Seat', 2800.00, 10, 'HONDA-SEAT-RACE', 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRKnEtkGTfZGi_93VYk3mvCtOUgV3nsY66ofA&s', '2025-06-30 17:51:02', '2025-06-30 17:51:02'),
(10, 5, 'SP800 (RB6)', 7250.00, 4, 'SP800-RB6', 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRf6MrZrBgF3ecBQRBvZIbq5t9qYsRqvdWMXQ&s', '2025-06-30 17:51:02', '2025-06-30 17:51:02'),
(11, 5, 'CT600 (RCB)', 7500.00, 6, 'CT600-RCB', 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ4CIZjwZ8j5td9cAuBUZP0gV_kIHsyuWF7KA&s', '2025-06-30 17:51:02', '2025-06-30 17:51:02'),
(12, 5, 'Euro Keeway 50 Spokes', 11800.00, 3, 'EURO-KEEWAY-50', 1, 'https://i.ebayimg.com/images/g/mwoAAOSwkuRjKcl~/s-l1200.jpg', '2025-06-30 17:51:02', '2025-06-30 17:51:02'),
(13, 1, 'ARM Exhaust V3', 8300.00, 5, 'TITAN-EX-001', 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQtvb3o2PVNmfwqhY8dbCBkif7w8pyYMgiHGzFAp1HKqqHgCJton9LipW5nNXaLSORFxaA&usqp=CAU', '2025-07-02 18:00:00', '2025-07-03 15:51:29'),
(14, 2, 'JVT CVT Set', 6800.00, 8, 'PERF-VAR-200', 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ_Q0YyeWUriIRvOr_WpPLKAdKhMer7pPH2tg&s', '2025-07-02 18:00:00', '2025-07-03 16:04:26'),
(15, 3, 'TSMP Flyball', 200.00, 25, 'HD-ROLLERS-12G', 1, 'https://down-ph.img.susercontent.com/file/ph-11134207-7r98s-lvssl24gfg8x2a', '2025-07-02 18:00:00', '2025-07-03 15:53:18'),
(16, 4, 'Aerox V2 Indo Concept Seat', 3200.00, 10, 'CF-SIDE-001', 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRkMYAlzQZ2fORFytWpqd_xV-IWkNZqLSRKCOGHnTsC5wQxeGpp9vOTQ8iKvGKZ1Yt4_wM&usqp=CAU', '2025-07-02 18:00:00', '2025-07-03 16:14:03'),
(17, 5, 'RCB SP522 EVO ', 9800.00, 4, 'ALLOY-RIM-17', 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS0k5G0SHhF81Mrkg6YTk0YKgc3QoVIhUa54A&s', '2025-07-02 18:00:00', '2025-07-03 16:10:35'),
(18, 1, 'Akrapovic RES', 11000.00, 7, 'SHORTY-GP-002', 1, 'https://news.webike.net/wp-content/uploads/2021/05/210512_acra_07.jpg', '2025-07-02 18:00:00', '2025-07-03 15:48:17'),
(19, 2, 'CRP CVT Set', 4500.00, 6, 'RACE-CLUTCH-150', 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS1uJ_PY7by0KYn3MLGk3oxd5oFeTZa5IWYCA&s', '2025-07-02 18:00:00', '2025-07-03 16:07:22'),
(20, 3, 'CRP Center Spring', 800.00, 23, 'PERF-AIR-125', 1, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQapTOIIU29i3qX9pXY86To8TqIhrh022TsOg&s', '2025-07-02 18:00:00', '2025-07-03 15:58:37');

-- --------------------------------------------------------

--
-- Table structure for table `product_info`
--

CREATE TABLE `product_info` (
  `product_info_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `detailed_description` text DEFAULT NULL,
  `specifications` text DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `model` varchar(100) DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `dimensions` varchar(100) DEFAULT NULL,
  `features` text DEFAULT NULL,
  `warranty_info` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_info`
--

INSERT INTO `product_info` (`product_info_id`, `product_id`, `detailed_description`, `specifications`, `brand`, `model`, `weight`, `dimensions`, `features`, `warranty_info`) VALUES
(1, 1, 'High-performance dual tip exhaust pipe for Yamaha NMAX V2/Aerox V2', 'Material: Stainless Steel\nTip Diameter: 50mm\nCompatibility: Yamaha NMAX V2, Aerox V2', 'M3', 'Dual Tip', 2.50, 'L: 45cm, W: 15cm', 'Improved exhaust flow\nEnhanced engine sound\nStylish dual tip design', '1-year limited warranty'),
(2, 2, 'Universal shark tip exhaust pipe with V4 technology', 'Material: Carbon Fiber\nTip Diameter: 45mm\nCompatibility: Universal fit', 'JVT', 'Shark Tip V4', 1.80, 'L: 40cm, W: 12cm', 'Lightweight construction\nAggressive sound\nEasy installation', '6-month warranty'),
(3, 3, 'Euro style bullet pipe for Keeway 152', 'Material: Steel\nTip Diameter: 40mm\nCompatibility: Keeway 152', 'Euro', 'Bullet', 2.20, 'L: 38cm, W: 10cm', 'Classic bullet design\nImproved performance\nDurable construction', '1-year warranty'),
(4, 4, 'Complete CVT kit for Honda Click 125/150', 'Includes: Variator, Drive Face, Rollers, Clutch\nCompatibility: Honda Click 125/150', 'TSMP', 'CVT Kit', 3.50, 'N/A', 'Improved acceleration\nBetter fuel efficiency\nHigh-quality components', '6-month warranty'),
(5, 5, 'Performance CVT kit for Yamaha NMAX V2/Aerox V2', 'Includes: Variator, Drive Face, Rollers, Clutch\nCompatibility: Yamaha NMAX V2, Aerox V2', 'RCB', 'CVT Kit', 3.80, 'N/A', 'Enhanced performance\nSmooth acceleration\nDurable construction', '6-month warranty'),
(6, 6, 'Performance clutch spring set with multiple RPM options', 'Spring Rate: 1000RPM, 1200RPM, 1500RPM\nCompatibility: Yamaha NMAX V2, Aerox V2', 'CRP', 'Clutch Spring', 0.20, 'N/A', 'Improved engagement\nBetter acceleration\nMultiple stiffness options', '3-month warranty'),
(7, 7, 'High-performance drive belt for Honda Click 125/150', 'Length: 225mm\nWidth: 20mm\nCompatibility: Honda Click 125/150', 'HP', 'Drive Belt 225', 0.50, 'N/A', 'Durable construction\nImproved power transfer\nLonger lifespan', '6-month warranty'),
(8, 8, 'Custom camel seat for Yamaha NMAX V2', 'Material: Synthetic Leather\nColor: Black/Red\nCompatibility: Yamaha NMAX V2', 'Custom', 'Camel Seat', 1.20, 'L: 65cm, W: 25cm', 'Improved comfort\nStylish design\nBetter grip', '3-month warranty'),
(9, 9, 'Racing style seat for Honda Click 125/150', 'Material: Synthetic Leather\nColor: Black/Blue\nCompatibility: Honda Click 125/150', 'Racing', 'Sport Seat', 1.10, 'L: 60cm, W: 24cm', 'Sporty design\nImproved riding position\nBetter support', '3-month warranty'),
(10, 10, '23\" alloy/CNC wheels for various motorcycle models', 'Size: 23\"\nColor Options: White, Bronze, Black\nCompatibility: Various models', 'SP800', 'RB6', 8.50, '23x2.15', 'Lightweight construction\nStylish design\nDurable', '1-year warranty'),
(11, 11, '17\" alloy wheels for scooters', 'Size: 17\"\nColor Options: Chrome, Black, Red\nCompatibility: Various scooter models', 'CT600', 'RCB', 7.80, '17x1.85', 'Strong and durable\nEye-catching design\nImproved handling', '1-year warranty'),
(12, 12, '17\" lightweight alloy wheels with 50 spokes', 'Size: 17\"\nSpokes: 50\nColor Options: Chrome, Black, White\nCompatibility: Keeway models', 'Euro', 'Keeway 50', 9.00, '17x2.15', 'Classic spoke design\nLightweight\nDurable construction', '1-year warranty'),
(13, 13, 'Premium titanium exhaust system designed for maximum performance gains. The lightweight construction reduces overall bike weight while the tuned expansion chamber improves exhaust flow for increased horsepower. Features a deep, aggressive tone that enhances your riding experience without being overly loud for street use.', 'Material: Aerospace-grade Titanium (Grade 2)\nDiameter: 45mm\nLength: 65cm\nOutlet: Dual 38mm tips\nWeight: 1.8kg\nMax Temperature Rating: 900°C', 'MotoTech', 'Titanium GP Series', 1.80, '65cm (length) x 15cm (width) x 10cm (height)', 'Lightweight titanium construction\nTuned expansion chamber for optimal performance\nDeep aggressive exhaust note\nEuro 4 compliant\nIncludes all mounting hardware\nHeat-resistant ceramic coating', '1-year limited warranty against manufacturing defects. Does not cover damage from accidents or improper installation.'),
(14, 14, 'JVT CVT Sets are high-performance transmission kits designed for Yamaha and Honda scooters like NMAX, Aerox, PCX, and Click. They improve acceleration, throttle response, and overall ride smoothness while maintaining reliability for daily and spirited riding.', 'Material: CNC-machined aluminum and high-grade steel components.\r\nSpring Rates: 1000RPM, 1500RPM, 2000RPM center springs (varies by set).\r\nCompatibility: Yamaha NMAX, Aerox, Honda PCX, Click.\r\nFunction: Enhances CVT responsiveness and acceleration.\r\nColor: Typically red or blue (varies by model).\r\nInstallation: Direct bolt-on, no modifications required.', 'JVT', 'V-Force 200', 0.90, '18cm x 18cm x 8cm', 'Reusable and durable components\r\nImproves throttle response and acceleration\r\nCompatible with stock and aftermarket setups\r\nIncludes variator, rollers, center spring, and clutch parts\r\nHelps maintain consistent performance under load\r\nNo special tools required for installation', '6-month warranty on manufacturing defects. Wear items (rollers, sliding plates) covered for 1 month.'),
(15, 15, 'TSMP Flyballs are a type of motorcycle part, specifically for variator rollers, designed for Yamaha and Honda scooters like NMAX, Aerox, PCX, and Click. They are available in various weights (e.g., 10g, 11g, 12g, 13g, 14g, 15g, 16g, 17g) and are known for improving performance and smooth gear shifting.', 'Material: Felt-covered foam\r\nWeight: 12 grams each\r\nQuantity: 6 flyballs per set\r\nHardness: 85A\r\nTemperature Range: -20°C to 120°C\r\nColor: Pink', 'TSMP', 'Endurance Series', 0.15, '15mm in diameter', 'Lasts 3x longer than OEM flyballs\r\nSmoother power delivery\r\nReduced vibration\r\nCompatible with most variators', '3-month replacement warranty. Does not cover damage from improper installation or contaminated CVT system.'),
(16, 16, 'Indo Concept Seats for Yamaha Aerox V2 are custom premium seats designed for comfort and style, providing improved ergonomics and a sporty look while maintaining durability for daily and performance riding.', 'Material: High-quality synthetic leather with memory foam padding.\r\nColor Options: Black, Black with Red Stitching, Custom Patterns (varies by supplier.\r\nCompatibility: Yamaha Aerox V2 (all year models).\r\nDesign: Contoured shape with anti-slip surface.\r\nWater Resistance: Yes.', 'Xavery', 'Twill Series', 0.40, '65cm x 28cm x 12cm', 'Improves riding comfort for long distances\r\nAnti-slip design enhances rider stability\r\nCustom sporty aesthetic to match Aerox V2 styling\r\nDirect bolt-on replacement, no modifications required\r\nDurable materials resist fading and wear\r\nEasy to clean and maintain', '1-year warranty against delamination or manufacturing defects. Does not cover impact damage or improper installation.'),
(17, 17, 'RCB SP522 EVO is a premium lightweight alloy mag wheel designed for scooters like Yamaha NMAX, Aerox, Honda Click, and PCX. It offers improved handling, stylish aesthetics, and enhanced performance while maintaining durability for daily and spirited riding.', 'Material: CNC-machined high-grade aluminum alloy.\r\nSize: 14-inch (front and rear, varies by model).\r\nSpoke Design: 5-spoke EVO design for rigidity and style.\r\nColor Options: Black, Bronze, Silver (anodized finishes).\r\nCompatibility: Yamaha NMAX, Aerox, Honda Click, PCX.\r\nWeight Reduction: Up to 25% lighter than stock wheels.', 'RCB', 'Racing V5', 3.20, '14-inch diameter x 2.15-inch width (front)\r\n14-inch diameter x 2.50-inch width (rear)', 'Lightweight construction improves handling and acceleration\r\nEnhanced heat dissipation for extended tire life\r\nStylish 5-spoke EVO design with premium finish\r\nDirect bolt-on installation, no modifications required\r\nCompatible with tubeless tires\r\nDurable and corrosion-resistant anodized coating', '2-year structural warranty. Finish warranty for 1 year. Does not cover damage from impacts or improper installation.'),
(18, 18, 'Compact GP-style exhaust system that delivers an aggressive sound while saving weight and space. The shorty design provides a sporty look and improves mid-range power. Made from high-quality 304 stainless steel with a durable ceramic coating for long-lasting performance.', 'Material: 304 Stainless Steel\nDiameter: 50mm\nLength: 40cm\nOutlet: Single 60mm tip\nWeight: 2.1kg\nDB Level: 92dB at 5,000 RPM', 'GP Exhaust', 'Shorty S1', 2.10, '40cm (length) x 15cm (width) x 12cm (height)', 'Aggressive GP-style sound\nMid-range power boost\nDurable ceramic coating\nIncludes mounting bracket\nPre-installed heat shield\nO2 sensor bung included', '1-year warranty on materials and workmanship. Does not cover discoloration from normal use.'),
(19, 19, 'CRP CVT Sets are performance transmission kits designed for Yamaha and Honda scooters like NMAX, Aerox, PCX, and Click. They provide smoother acceleration, consistent shifting, and improved overall performance for daily riding and sporty use', 'Material: High-tensile steel and precision-machined aluminum components.\r\nSpring Rates: 1000RPM, 1500RPM, 2000RPM center springs included.\r\nCompatibility: Yamaha NMAX, Aerox, Honda PCX, Click.\r\nFunction: Enhances CVT responsiveness, acceleration, and durability.\r\nColor: Red and silver (varies by component).\r\nInstallation: Plug-and-play, no modifications required.', 'CRP', 'RacePack 150', 1.50, '18cm x 18cm x 8cm', 'Improves acceleration and throttle response\r\nMaintains consistent gear ratios under load\r\nIncludes variator, rollers, center spring, and clutch components\r\nDurable construction for daily and spirited riding\r\nEasy installation with no special tools required\r\nCompatible with stock and aftermarket setups', '6-month warranty on manufacturing defects. Friction material covered for 1 month of normal use.'),
(20, 20, 'CRP Center Springs are performance motorcycle parts designed for Yamaha and Honda scooters like NMAX, Aerox, PCX, and Click. They are available in various stiffness ratings (e.g., 1000RPM, 1500RPM, 2000RPM) and are known for improving acceleration and maintaining optimal gear ratios during high-speed riding.', 'Material: High-tensile steel\r\nSpring Rates: 1000RPM, 1500RPM, 2000RPM\r\nCompatibility: Yamaha NMAX, Aerox, Honda PCX, Click\r\nFunction: Enhances acceleration and maintains optimal gear ratios\r\nColor: Red\r\nInstallation: Direct replacement, no modifications required\r\n', 'CRP', 'PowerFlow 125', 0.30, '52mm x 52mm x 112mm', 'Improves acceleration and throttle response\r\nMaintains optimal gear ratios\r\nCompatible with stock and aftermarket CVT setups\r\nNo special tools required for installation\r\nDurable high-tensile steel construction\r\nSuitable for daily and performance riding\r\n', '6-month warranty. Cleaning and re-oiling required every 5,000km to maintain warranty.');

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `phone` varchar(11) DEFAULT NULL,
  `street` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `zip` varchar(4) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `fullname`, `gender`, `dob`, `phone`, `street`, `city`, `state`, `zip`, `country`, `username`, `email`, `password`, `created_at`, `updated_at`) VALUES
(5, 'Frein Wilhelm M. Refugio', 'male', '2005-07-11', '09293549452', '97 K7', 'Quezon City', 'Metro Manila', '1104', 'Philippines', 'Furein', 'iyanpogi122@gmail.com', '$2y$10$yq9dQ4bsTMcs2cGQ5uRltOTFEH.rQeyDK/dLMfwHvIDIw356Tv5qW', '2025-07-01 17:00:31', '2025-07-01 17:00:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_info`
--
ALTER TABLE `product_info`
  ADD PRIMARY KEY (`product_info_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`profile_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username_2` (`username`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `product_info`
--
ALTER TABLE `product_info`
  MODIFY `product_info_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `cart_item_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_info`
--
ALTER TABLE `product_info`
  ADD CONSTRAINT `product_info_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
