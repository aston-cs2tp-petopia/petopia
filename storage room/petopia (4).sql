-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2024 at 04:45 PM
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
-- Database: `petopia`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Admin_ID` int(10) NOT NULL,
  `First_Name` varchar(30) NOT NULL,
  `Last_Name` varchar(30) NOT NULL,
  `Contact_Email` varchar(60) NOT NULL,
  `Phone_Number` varchar(30) NOT NULL,
  `Home_Address` varchar(60) NOT NULL,
  `Postcode` varchar(10) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Password` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `basket`
--

CREATE TABLE `basket` (
  `Customer_ID` int(11) NOT NULL,
  `Product_ID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `basket`
--

INSERT INTO `basket` (`Customer_ID`, `Product_ID`, `Quantity`, `Subtotal`) VALUES
(2, 18, 1, 700);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `Category_ID` int(10) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Description` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`Category_ID`, `Name`, `Description`) VALUES
(1, 'Senior', 'This pet is between 8-20 years old.'),
(2, 'Adult', 'This pet is between 2-8 years old.'),
(3, 'Infant', 'This pet is between 0-1 years old.'),
(4, 'Greyhound', 'Step into the world of unparalleled elegance with our Greyhounds. Radiating grace and agility, these sleek companions embody beauty and brains in one remarkable package. Discover the unmatched loyalty and gentle nature of these magnificent creatures. Elevate your life with the perfect blend of sophi'),
(5, 'Dog', 'Explore our range of fun loving dogs'),
(6, 'Cat', 'Explore our range of cuddly cats'),
(7, 'Male', 'These pets are male'),
(8, 'Female', 'These pets are female'),
(9, 'Golden Retriever', 'Golden Retrievers: the embodiment of love, loyalty, and endless joy! With their golden coats and gentle hearts, these dogs are the ultimate companions, offering unwavering devotion and boundless affection. Whether as playful pals or supportive service dogs, they bring happiness to every home. Their friendly nature and wagging tails make them perfect for families or anyone craving a faithful and loving furry friend. Do not miss out on the opportunity to add the warmth of a Golden Retriever to you'),
(10, 'Maine Coon', 'Maine Coons: Majestic, magnificent, and oh-so-affectionate! These gentle giants boast luxurious coats, captivating personalities, and an undeniable charm. Known as the gentle giants of the cat world, they are the perfect combination of grace and playfulness. Maine Coons bring elegance and love into any home, making them the ideal companions for those seeking a regal feline friend. Do not miss the chance to welcome the grandeur of a Maine Coon into your life!'),
(11, 'Beagle', 'Beagles: Small, spirited, and endlessly lovable! These pups are the epitome of charm with their wagging tails and soulful eyes. Known for their playful nature and unwavering loyalty, Beagles make perfect companions for families or anyone seeking a bundle of joy. Do not miss out on the chance to bring the joy of a Beagle into your home!'),
(12, 'Siamese', 'Siamese Cats: Elegance, intelligence, and endless affection! With their striking looks and loving personalities, Siamese cats captivate hearts effortlessly. These feline companions are known for their loyalty and vocal nature, making them perfect for those seeking a devoted and communicative pet. Do not miss the chance to invite the sophistication of a Siamese cat into your life!'),
(13, 'Bengal', 'Bengal Cats: Exotic, playful, and utterly captivating! With their wild looks and friendly personalities, Bengals are the ultimate companions for those seeking a touch of the jungle at home. These stunning felines bring energy, intelligence, and a whole lot of love into your life. Do not miss out on the chance to experience the thrill of owning a Bengal cat!'),
(14, 'Border Collie', 'Border Collies: Brilliant, energetic, and endlessly devoted! These intelligent dogs are the perfect companions for active lifestyles. Known for their keen intelligence and unwavering loyalty, Border Collies make exceptional partners for those seeking a smart and dedicated furry friend. Do not miss out on the opportunity to welcome the boundless energy of a Border Collie into your life!'),
(15, 'Chihuahua', 'Chihuahuas: Small, sassy, and full of love! These pint-sized pups are big on personality, offering bundles of affection in a tiny package. Known for their spunky nature and unwavering loyalty, Chihuahuas make adorable companions for anyone seeking a playful and devoted furry friend. Do not miss out on the chance to add a dash of sass and love to your life with a Chihuahua!'),
(16, 'Great Dane', 'Great Danes: Majestic, gentle giants! These magnificent dogs combine grace and size effortlessly. Known for their calm demeanor and affectionate nature, Great Danes make impressive yet loving companions for families or individuals seeking a loyal and regal furry friend. Do not miss the opportunity to bring the grandeur of a Great Dane into your home!'),
(17, 'Tabby', 'Tabby Cats: Classic, charming, and full of character! These iconic felines boast beautiful coats and winning personalities. Known for their playful antics and loving nature, tabby cats make delightful companions for anyone seeking a loving and adaptable pet. Do not miss the chance to add the timeless charm of a tabby cat to your life!'),
(18, 'Mixed Breed', 'Mixed Breeds: Unique, versatile, and full of surprises! These one-of-a-kind companions offer the best of various breeds in one delightful package. Known for their individuality and diverse traits, mixed-breed pets make fantastic, adaptable additions to any home. Do not miss out on the opportunity to welcome a wonderfully unique and loving mixed-breed pet into your life!');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `Customer_ID` int(10) NOT NULL,
  `First_Name` varchar(30) NOT NULL,
  `Last_Name` varchar(30) NOT NULL,
  `Contact_Email` varchar(60) NOT NULL,
  `Phone_Number` varchar(30) NOT NULL,
  `Home_Address` varchar(60) NOT NULL,
  `Postcode` varchar(10) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Password` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`Customer_ID`, `First_Name`, `Last_Name`, `Contact_Email`, `Phone_Number`, `Home_Address`, `Postcode`, `Username`, `Password`) VALUES
(1, 'Beckie', 'Jones', 'Mcflurryorfruitbag@gmail.com', '07768527200', '14 Caughall road', 'CH12 1LE', 'BeckieJ30', 'Higuys300!');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `Orders_ID` int(10) NOT NULL,
  `Customer_ID` int(10) NOT NULL,
  `Order_Date` date NOT NULL,
  `Total_Amount` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ordersdetails`
--

CREATE TABLE `ordersdetails` (
  `OrdersDetails_ID` int(10) NOT NULL,
  `Orders_ID` int(10) NOT NULL,
  `Product_ID` int(10) NOT NULL,
  `Quantity` int(20) NOT NULL,
  `Subtotal` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `Product_ID` int(10) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Price` int(20) NOT NULL,
  `Num_In_Stock` int(10) NOT NULL,
  `Description` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`Product_ID`, `Name`, `Price`, `Num_In_Stock`, `Description`) VALUES
(1, 'Larry', 300, 1, 'An intelligent 11 year old rescue male race dog called Larry. He has a good temperament, and is very good with kids as well as other pets. He has a short gray coat that requires minimal grooming, and lovely brown eyes. He enjoys short bursts of energy, so a short walk every day will suffice. Larry will thrive in any environment where there is enough space for him! '),
(2, 'Max', 680, 1, 'Introducing Max, a 2-year-old Golden Retriever! He is the embodiment of playful energy and unwavering loyalty. With a shimmering golden coat and a heart full of love, Max is the perfect companion for anyone seeking a devoted four-legged friend. Ready to bring endless joy into your life, he is waiting to become a cherished member of your family.'),
(3, 'Luna', 450, 1, 'Meet Luna, a 3-year-old Maine Coon! With her regal grace and captivating green eyes, she brings elegance to any home. Playful and affectionate, Luna is ready to be the sophisticated companion that you have been dreaming of. She is not just a cat; she is a charming addition waiting to grace your life with love and beauty.'),
(4, 'Cooper', 650, 1, 'Meet Cooper, a 6-month-old playful and adorable Beagle puppy! With his floppy ears and soulful eyes, Cooper is the definition of cuteness. Full of energy and curiosity, he is eager to explore the world by your side. Cooper is an affectionate companion ready to bring laughter and warmth into your home. Do not miss the chance to welcome this charming Beagle pup into your family!'),
(5, 'Sophie', 450, 1, '\r\nPresenting Sophie, a 9-year-old serene and loving Siamese cat! With her striking blue eyes and elegant demeanor, Sophie exudes grace and wisdom. This mature beauty enjoys peaceful moments by the window and gentle affection on her terms. Her calming presence and gentle nature make her an ideal companion for a quiet, loving home. Sophie is not just a cat; she is a sophisticated feline ready to bring tranquility and companionship into your life.'),
(6, 'Polo', 580, 1, '\r\nMeet Polo, a 4-month-old playful and enchanting Bengal kitten! With her striking leopard-like spots and lively demeanor, Polo is a bundle of feline energy. She is ready to pounce and play, bringing excitement and endless entertainment to your home. Polo is an affectionate companion waiting to become the heart-stealer of your household. Do not miss the chance to welcome this spirited Bengal kitten into your life!\r\n'),
(7, 'Marco', 580, 1, '\r\nSay hello to Marco, a 5-month-old male Bengal kitten! With his mesmerizing rosettes and playful charm, Marco is the epitome of feline grace and energy. He is an adventurous spirit, always ready to explore and engage in playful antics. Marco is an affectionate little guy, eager to bring joy and liveliness into your home. Do not miss out on the chance to make Marco the newest and most beloved member of your family!\r\n\r\n'),
(8, 'Rocky', 400, 1, 'Meet Rocky, a 2-year-old Border Collie! With his striking black and white coat and intelligent gaze, Rocky is a bundle of energy and smarts. Eager to learn and play, he is a quick learner and loves agility games. Rocky is not just a dog; he is a devoted companion waiting to bring enthusiasm and loyalty into your life. Do not miss out on the chance to make Rocky a cherished member of your family!\r\n\r\n'),
(9, 'Whiskers', 400, 1, 'Introducing Whiskers, a 4-month-old playful and enchanting Siamese kitten! With stunning blue eyes and a sleek coat, Whiskers embodies elegance and charm. He is full of mischievous energy, ready to explore and cuddle up for warm naps. Whiskers is an affectionate little companion waiting to fill your home with joy and delightful kitten antics. Do not miss the chance to bring this captivating Siamese kitten into your life!\r\n'),
(10, 'Chloe', 660, 1, 'Introducing Chloe, a 1-year-old charming and petite white Chihuahua! With her adorable button nose and sparkling personality, Chloe is the epitome of cuteness. Despite her small size, she has a big heart filled with love and loyalty. Chloe is an affectionate companion ready to bring bundles of joy and affection into your life. Do not miss the opportunity to welcome this delightful white Chihuahua into your home!\r\n'),
(11, 'Scooby doo', 800, 1, 'Meet Scooby doo, a 3-year-old magnificent and friendly brown Great Dane! With his towering stature and gentle disposition, Scooby doo embodies both grace and warmth. Despite his impressive size, he is a big softie at heart, loving nothing more than cuddles and companionship. Scooby doo is a loyal companion waiting to fill your home with love and his majestic presence. Do not miss out on the chance to make Scooby doo a cherished member of your family!\r\n'),
(12, 'Scrappy doo', 890, 1, 'Introducing Scrappy doo, a 1-year-old spirited and handsome brown Great Dane! With his youthful exuberance and striking presence, Scrappy doo is a bundle of energy and affection. Despite his size, he is full of playful antics and endless enthusiasm. Scrappy doo is an affectionate companion ready to bring joy and liveliness into your home. Don not miss the chance to welcome this delightful young Great Dane into your life!\r\n'),
(13, 'Snoopy', 500, 1, 'Introducing Snoopy, a 4-year-old charming Beagle with a distinctive white coat and adorable black ears! With his classic beagle looks and heart-melting brown eyes, Snoopy is the epitome of cuteness and charm. He is a playful and affectionate companion, always ready for a fun adventure or a cuddle on the couch. Snoopy is the perfect addition to your family, bringing love, loyalty, and endless tail wags into your home. Do not miss out on the opportunity to make Snoopy your beloved furry friend!\r\n'),
(14, 'Garfield', 450, 1, '\r\nIntroducing Garfield, a 5-year-old lovably chubby tabby cat! With his iconic orange coat and a talent for leisure, Garfield embodies the art of relaxation. His laid-back demeanor and penchant for naps make him the ultimate expert in the fine art of coziness. Garfield is a delightful companion, adding a touch of charm and tranquility to any home. If you are seeking a furry friend who appreciates the joys of a lazy day, Garfield is the purrfect match for you!'),
(15, 'Sunny', 1050, 1, 'Meet Sunny, an adorable 3-month-old Golden Retriever puppy! With her fluffy golden coat and boundless enthusiasm, Sunny is the epitome of playful charm and friendliness. She is a bundle of joy, always ready to wag her tail and offer unconditional love. Sunny is an intelligent, affectionate companion ready to bring warmth and happiness into your life. Do not miss out on the chance to make Sunny a cherished member of your family!'),
(16, 'Bailey', 1000, 1, '\r\nIntroducing Bailey, a 4-month-old Golden Retriever puppy! With her shimmering coat and wagging tail, Bailey radiates boundless energy and affection. She is a playful sweetheart, ready to charm her way into your heart with her warm, loving nature. Bailey is more than a puppy; she is a devoted companion eager to bring joy and laughter into your home. Don not miss the opportunity to make Bailey the treasured furry member of your family!'),
(17, 'Bailey', 1000, 1, '\r\nIntroducing Bailey, a 4-month-old Golden Retriever puppy! With her shimmering coat and wagging tail, Bailey radiates boundless energy and affection. She is a playful sweetheart, ready to charm her way into your heart with her warm, loving nature. Bailey is more than a puppy; she is a devoted companion eager to bring joy and laughter into your home. Do not miss the opportunity to make Bailey the treasured furry member of your family!'),
(18, 'Herbert', 700, 1, '\r\nIntroducing Herbert, a wise and gentle 8-year-old Golden Retriever! With his distinguished golden coat and soulful eyes, Herbert exudes years of loyalty and companionship. He is a seasoned expert in love and affection, offering a heart full of warmth to those around him. Herbert is not just an older dog; he is a cherished friend ready to fill your days with love, comfort, and unwavering devotion. Do not miss out on the chance to welcome this wise and loving Golden Retriever into your home!'),
(19, 'Monkey', 350, 1, '\r\nIntroducing Monkey, a friendly and lively 3-year-old mixed-breed dog with a rich brown coat! With his playful nature and adorable antics, Monkey embodies joy and energy. His endearing personality and warm, expressive eyes make him an instant charmer. Monkey is a lovable companion ready to swing into your heart and become your trusted furry friend. Do not miss out on the opportunity to make Monkey a delightful addition to your family!');

-- --------------------------------------------------------

--
-- Table structure for table `productcategory`
--

CREATE TABLE `productcategory` (
  `Category_ID` int(10) NOT NULL,
  `Product_ID` int(10) NOT NULL,
  `prodcat_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productcategory`
--

INSERT INTO `productcategory` (`Category_ID`, `Product_ID`, `prodcat_ID`) VALUES
(4, 1, 1),
(2, 1, 2),
(5, 1, 3),
(7, 1, 4),
(2, 2, 5),
(5, 2, 6),
(7, 2, 7),
(9, 2, 8),
(2, 3, 9),
(6, 3, 10),
(10, 3, 11),
(8, 3, 12),
(3, 4, 13),
(5, 4, 14),
(7, 4, 15),
(11, 4, 16),
(1, 5, 17),
(6, 5, 18),
(8, 5, 19),
(12, 5, 20),
(3, 6, 21),
(6, 6, 22),
(8, 6, 23),
(13, 6, 24),
(3, 7, 25),
(6, 7, 26),
(7, 7, 27),
(13, 7, 28),
(2, 8, 29),
(5, 8, 30),
(7, 8, 31),
(14, 8, 32),
(3, 9, 33),
(6, 9, 34),
(8, 9, 35),
(12, 9, 36),
(3, 10, 37),
(5, 10, 38),
(8, 10, 39),
(15, 10, 40),
(2, 11, 41),
(5, 11, 42),
(7, 11, 43),
(16, 11, 44),
(3, 12, 45),
(5, 12, 46),
(7, 12, 47),
(16, 12, 48),
(2, 13, 49),
(5, 13, 50),
(7, 13, 51),
(11, 13, 53);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Admin_ID`),
  ADD UNIQUE KEY `Contact_Email` (`Contact_Email`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `basket`
--
ALTER TABLE `basket`
  ADD KEY `Customer_ID` (`Customer_ID`),
  ADD KEY `Products_ID` (`Product_ID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`Category_ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Customer_ID`),
  ADD UNIQUE KEY `Contact_Email` (`Contact_Email`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`Orders_ID`),
  ADD KEY `Customer_ID` (`Customer_ID`);

--
-- Indexes for table `ordersdetails`
--
ALTER TABLE `ordersdetails`
  ADD PRIMARY KEY (`OrdersDetails_ID`),
  ADD KEY `Orders_ID` (`Orders_ID`),
  ADD KEY `Product_ID` (`Product_ID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`Product_ID`);

--
-- Indexes for table `productcategory`
--
ALTER TABLE `productcategory`
  ADD PRIMARY KEY (`prodcat_ID`),
  ADD KEY `Category_ID` (`Category_ID`),
  ADD KEY `Product_ID` (`Product_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `Admin_ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `Category_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `Customer_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `Orders_ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ordersdetails`
--
ALTER TABLE `ordersdetails`
  MODIFY `OrdersDetails_ID` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `Product_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `productcategory`
--
ALTER TABLE `productcategory`
  MODIFY `prodcat_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`Customer_ID`) REFERENCES `customer` (`Customer_ID`);

--
-- Constraints for table `ordersdetails`
--
ALTER TABLE `ordersdetails`
  ADD CONSTRAINT `ordersdetails_ibfk_1` FOREIGN KEY (`Orders_ID`) REFERENCES `orders` (`Orders_ID`),
  ADD CONSTRAINT `ordersdetails_ibfk_2` FOREIGN KEY (`Product_ID`) REFERENCES `product` (`Product_ID`);

--
-- Constraints for table `productcategory`
--
ALTER TABLE `productcategory`
  ADD CONSTRAINT `productcategory_ibfk_1` FOREIGN KEY (`Category_ID`) REFERENCES `category` (`Category_ID`),
  ADD CONSTRAINT `productcategory_ibfk_2` FOREIGN KEY (`Product_ID`) REFERENCES `product` (`Product_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
