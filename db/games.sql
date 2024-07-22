-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2024 at 05:41 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `games`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `idCart` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `amount_sum` int(11) NOT NULL,
  `price_sum` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`idCart`, `idUser`, `amount_sum`, `price_sum`) VALUES
(2, 10, 0, 0),
(3, 6, 1, 1000),
(4, 7, 1, 1000),
(7, 2, 3, 2560),
(11, 11, 0, 0),
(13, 12, 0, 0),
(15, 13, 0, 0),
(17, 14, 0, 0),
(19, 9, 0, 0),
(20, 1, 0, 0),
(23, 15, 0, 0),
(24, 16, 0, 0),
(29, 17, 3, 3332),
(36, 18, 0, 0),
(70, 0, 0, 0),
(71, 0, 0, 0),
(72, 0, 0, 0),
(73, 0, 0, 0),
(74, 0, 0, 0),
(75, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cartproduct`
--

CREATE TABLE `cartproduct` (
  `idCP` int(11) NOT NULL,
  `idProduct` int(11) NOT NULL,
  `idCart` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cartproduct`
--

INSERT INTO `cartproduct` (`idCP`, `idProduct`, `idCart`, `amount`) VALUES
(8, 3, 3, 1),
(26, 3, 4, 1),
(38, 2, 1, 1),
(39, 3, 1, 2),
(41, 3, 6, 2),
(42, 2, 6, 1),
(47, 1, 8, 1),
(49, 3, 9, 1),
(108, 2, 14, 1),
(231, 3, 28, 1),
(434, 7, 29, 1),
(435, 6, 29, 1),
(436, 8, 29, 1),
(458, 8, 7, 1),
(459, 9, 7, 1),
(460, 11, 7, 1),
(474, 9, 66, 2);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `idCat` int(11) NOT NULL,
  `idPlatform` int(11) NOT NULL,
  `genre` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`idCat`, `idPlatform`, `genre`, `url`) VALUES
(1, 1, 'Akční', 'pc-akcni'),
(2, 1, 'RPG', 'pc-rpg'),
(3, 1, 'Indie', 'pc-indie'),
(4, 1, 'Adventura', 'pc-adventura'),
(5, 1, 'Online & MMORPG', 'pc-online-a-mmorpg'),
(6, 1, 'Závody', 'pc-zavody'),
(7, 1, 'Strategie', 'pc-strategie'),
(8, 1, 'Simulace', 'pc-simulace'),
(9, 1, 'Dobrodružné', 'pc-dobrodruzne'),
(12, 2, 'Akční', 'xbox-akcni'),
(13, 2, 'RPG', 'xbox-rpg'),
(14, 2, 'Adventura', 'xbox-adventura'),
(15, 2, 'Závody', 'xbox-zavody'),
(16, 2, 'Simulace', 'xbox-simulace'),
(17, 2, 'Dobrodružné', 'xbox-dobrodruzne'),
(18, 3, 'Akční', 'ps-akcni'),
(19, 3, 'RPG', 'ps-rpg'),
(20, 3, 'Adventura', 'ps-adventura'),
(21, 3, 'Závody', 'ps-zavody'),
(22, 3, 'Simulace', 'ps-simulace'),
(23, 3, 'Dobrodružné', 'ps-dobrodruzne'),
(24, 4, 'Akční', 'switch-akcni'),
(25, 4, 'RPG', 'switch-rpg'),
(26, 4, 'Adventura', 'switch-adventura'),
(27, 4, 'Závody', 'switch-zavody'),
(28, 4, 'Simulace', 'switch-simulace'),
(29, 4, 'Dobrodružné', 'switch-dobrodruzne');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `idComment` int(11) NOT NULL,
  `idProduct` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `text` varchar(5000) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `user` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`idComment`, `idProduct`, `idUser`, `text`, `date`, `user`) VALUES
(1, 1, 16, 'Fakt dobrá hra.', '2022-12-29 18:34:32', 'Honza Feigh'),
(2, 4, 16, '10/10', '2022-12-29 18:39:35', 'Honza Feigh'),
(3, 1, 2, 'Nejlepší.', '2022-12-29 18:46:07', 'Petr Novotný'),
(6, 2, 2, 'Easy.', '2022-12-29 19:15:58', 'Petr Novotný'),
(11, 9, 17, 'test', '2022-12-29 22:01:24', 'Martina Holá');

-- --------------------------------------------------------

--
-- Table structure for table `orderproduct`
--

CREATE TABLE `orderproduct` (
  `idOP` int(11) NOT NULL,
  `idProduct` int(11) NOT NULL,
  `idOrder` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orderproduct`
--

INSERT INTO `orderproduct` (`idOP`, `idProduct`, `idOrder`, `amount`) VALUES
(1, 2, 3, 1),
(2, 3, 3, 2),
(3, 1, 3, 1),
(4, 1, 4, 1),
(5, 2, 4, 2),
(6, 3, 4, 1),
(7, 1, 5, 1),
(8, 2, 6, 5),
(9, 4, 7, 3),
(10, 3, 8, 1),
(11, 2, 8, 2),
(12, 4, 9, 1),
(13, 2, 10, 1),
(14, 4, 10, 1),
(15, 3, 10, 2),
(16, 1, 11, 1),
(17, 3, 11, 1),
(18, 2, 12, 5),
(19, 1, 13, 1),
(20, 3, 13, 1),
(21, 2, 14, 1),
(22, 4, 14, 2),
(23, 3, 15, 2),
(24, 1, 15, 2),
(25, 1, 16, 1),
(26, 3, 16, 1),
(27, 3, 17, 1),
(28, 2, 17, 1),
(29, 4, 17, 2),
(30, 3, 18, 2),
(31, 4, 19, 3),
(32, 3, 20, 1),
(33, 2, 20, 3),
(34, 2, 21, 1),
(35, 3, 21, 1),
(36, 4, 21, 1),
(37, 3, 22, 1),
(38, 4, 22, 1),
(39, 3, 23, 1),
(40, 2, 23, 1),
(41, 1, 23, 1),
(42, 2, 24, 2),
(43, 4, 24, 1),
(44, 3, 25, 2),
(45, 1, 26, 1),
(46, 4, 27, 2),
(47, 2, 28, 2),
(48, 4, 28, 1),
(49, 2, 29, 1),
(50, 3, 29, 1),
(51, 1, 30, 1),
(52, 4, 30, 1),
(53, 4, 31, 1),
(54, 1, 31, 1),
(55, 3, 32, 1),
(56, 1, 32, 1),
(57, 1, 33, 1),
(58, 8, 34, 1),
(59, 6, 34, 1),
(60, 2, 34, 1),
(61, 1, 35, 1),
(62, 2, 35, 1),
(63, 3, 35, 1),
(64, 9, 36, 1),
(65, 9, 37, 1),
(66, 8, 37, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `idOrder` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `transport` varchar(255) NOT NULL,
  `payment` varchar(255) NOT NULL,
  `price_sum` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`idOrder`, `idUser`, `address`, `transport`, `payment`, `price_sum`, `created_at`, `status`) VALUES
(3, 2, 'Ulice: Ztracená 202, Město: Ohrobec, PSČ: 14000', 'Osobní odběr', 'Na dobírku', 3902, '2022-12-20 00:00:00', 'Vyřízeno'),
(4, 11, 'Ulice: Pekař 303, Město: Praha, PSČ: 13400', 'Osobní odběr', 'Na dobírku', 4091, '2022-12-20 00:00:00', 'Vyřizuje se'),
(5, 11, 'Ulice: Pekař 303, Město: Praha, PSČ: 13400', 'Osobní odběr', 'Na dobírku', 713, '2022-12-20 00:00:00', 'Vyřizuje se'),
(6, 11, 'Ulice: Pekař 303, Město: Praha, PSČ: 13400', 'Osobní odběr', 'Na dobírku', 5945, '2022-12-20 00:00:00', 'Vyřizuje se'),
(7, 11, 'Ulice: Pekař 303, Město: Praha, PSČ: 13400', 'Osobní odběr', 'Na dobírku', 3357, '2022-12-20 00:00:00', 'Vyřizuje se'),
(8, 2, 'Ulice: Ztracená 202, Město: Ohrobec, PSČ: 14000', 'Osobní odběr', 'Na dobírku', 3378, '2022-12-20 18:27:35', 'Vyřizuje se'),
(9, 2, 'Ulice: Ztracená 202, Město: Ohrobec, PSČ: 14000', 'Osobní odběr', 'Na dobírku', 1119, '2022-12-20 18:29:36', 'Vyřizuje se'),
(10, 10, 'Ulice: Jasnova 404, Město: Brno, PSČ: 10200', 'Osobní odběr', 'Na dobírku', 4308, '2022-12-20 19:40:15', 'Vyřizuje se'),
(11, 10, 'Ulice: Jasnova 404, Město: Brno, PSČ: 10200', 'Osobní odběr', 'Na dobírku', 1713, '2022-12-20 19:49:56', 'Vyřizuje se'),
(12, 10, 'Ulice: Jasnova 404, Město: Brno, PSČ: 10200', 'Osobní odběr', 'Na dobírku', 5945, '2022-12-20 19:53:47', 'Vyřizuje se'),
(13, 10, 'Ulice: Jasnova 404, Město: Brno, PSČ: 10200', 'Osobní odběr', 'Na dobírku', 1713, '2022-12-20 20:00:48', 'Vyřizuje se'),
(14, 12, 'Ulice: Street 323, Město: Praha, PSČ: 19022', 'Osobní odběr', 'Na dobírku', 3427, '2022-12-21 16:31:51', 'Vyřizuje se'),
(15, 12, 'Ulice: Street 323, Město: Praha, PSČ: 19022', 'Osobní odběr', 'Na dobírku', 3426, '2022-12-21 16:40:14', 'Vyřizuje se'),
(16, 12, 'Ulice: Street 323, Město: Praha, PSČ: 19022', 'Osobní odběr', 'Na dobírku', 1713, '2022-12-21 16:48:02', 'Vyřizuje se'),
(17, 2, 'Ulice: Ztracená 202, Město: Ohrobec, PSČ: 14000', 'Osobní odběr', 'Na dobírku', 4427, '2022-12-21 17:38:19', 'Vyřizuje se'),
(18, 2, 'Ulice: Ztracená 202, Město: Ohrobec, PSČ: 14000', 'Osobní odběr', 'Na dobírku', 2000, '2022-12-21 17:39:16', 'Vyřizuje se'),
(19, 2, 'Ulice: Ztracená 202, Město: Ohrobec, PSČ: 14000', 'Osobní odběr', 'Na dobírku', 3357, '2022-12-21 18:18:29', 'Vyřizuje se'),
(20, 2, 'Ulice: Ztracená 202, Město: Ohrobec, PSČ: 14000', 'Osobní odběr', 'Na dobírku', 4567, '2022-12-21 19:04:49', 'Vyřizuje se'),
(21, 2, 'Ulice: Ztracená 202, Město: Ohrobec, PSČ: 14000', 'Osobní odběr', 'Na dobírku', 3308, '2022-12-21 22:01:30', 'Vyřizuje se'),
(22, 13, 'Ulice: Grey 59, Město: New Orleans, PSČ: 00000', 'Osobní odběr', 'Na dobírku', 2119, '2022-12-22 20:23:44', 'Expedováno'),
(23, 14, 'Ulice: Street 323, Město: Brno, PSČ: 12333', 'Osobní odběr', 'Na dobírku', 2902, '2022-12-23 18:39:07', 'Storno'),
(24, 2, 'Ulice: Ztracená 202, Město: Ohrobec, PSČ: 14000', 'Osobní odběr', 'Na dobírku', 3497, '2022-12-28 19:07:32', 'Reklamace'),
(25, 15, 'Ulice: Random 707, Město: Plzeň, PSČ: 13200', 'Osobní odběr', 'Na dobírku', 2000, '2022-12-29 16:47:28', 'Vyřizuje se'),
(26, 16, 'Ulice: Unknown 000, Město: Brno, PSČ: 98700', 'Osobní odběr', 'Na dobírku', 713, '2022-12-29 16:50:56', 'Vyřizuje se'),
(27, 16, 'Ulice: Unknown 000, Město: Brno, PSČ: 98700', 'Osobní odběr', 'Na dobírku', 2238, '2022-12-29 17:48:54', 'Expedováno'),
(28, 2, 'Ulice: Ztracená 202, Město: Ohrobec, PSČ: 14000', 'Osobní odběr', 'Na dobírku', 3497, '2023-03-08 17:10:31', 'Vyřizuje se'),
(29, 2, 'Ulice: Ztracená 202, Město: Ohrobec, PSČ: 14000', 'Kurýr', 'Bankovním převodem', 2388, '2023-03-08 22:46:05', 'Vyřizuje se'),
(30, 2, 'Ulice: Ztracená 202, Město: Ohrobec, PSČ: 14000', 'Osobní odběr', 'Na dobírku', 1832, '2023-03-08 22:48:49', 'Vyřízeno'),
(31, 2, 'Ulice: Ztracená 202, Město: Ohrobec, PSČ: 14000', 'Česká pošta', 'Na dobírku', 1961, '2023-03-08 22:55:34', 'Reklamace'),
(32, 2, 'Ulice: Ztracená 202, Město: Ohrobec, PSČ: 14000', 'Česká pošta', 'Bankovním převodem', 1842, '2023-03-09 19:23:27', 'Expedováno'),
(33, 18, 'Ulice: Dolská 656, Město: Praha, PSČ: 12110', 'Osobní odběr', 'Na dobírku', 713, '2023-03-12 13:11:17', 'Vyřízeno'),
(34, 2, 'Ulice: Ztracená 202, Město: Ohrobec, PSČ: 14000', 'Osobní odběr', 'Na dobírku', 3451, '2023-04-06 21:46:14', 'Vyřízeno'),
(35, 17, 'Ulice: Adamova 23, Město: Praha, PSČ: 12100', 'Česká pošta', 'Bankovním převodem', 3031, '2023-04-22 20:20:06', 'Vyřizuje se'),
(36, 17, 'Ulice: Adamova 23, Město: Praha, PSČ: 12100', 'Kurýr', 'Na dobírku', 735, '2023-04-22 22:01:09', 'Vyřízeno'),
(37, 17, 'Ulice: Pavlíkova 404, Město: Brno, PSČ: 12312', 'Česká pošta', 'Bankovním převodem', 1796, '2023-04-24 15:52:55', 'Storno');

-- --------------------------------------------------------

--
-- Table structure for table `platform`
--

CREATE TABLE `platform` (
  `idPlatform` int(11) NOT NULL,
  `platformType` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `platform`
--

INSERT INTO `platform` (`idPlatform`, `platformType`) VALUES
(1, 'PC'),
(2, 'XBOX'),
(3, 'PLAYSTATION'),
(4, 'SWITCH');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `idProduct` int(11) NOT NULL,
  `idPlatform` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `genre` varchar(255) NOT NULL,
  `game_type` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `description` varchar(5000) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`idProduct`, `idPlatform`, `url`, `name`, `genre`, `game_type`, `price`, `stock`, `description`, `image`) VALUES
(1, 1, 'elder-scrolls-v-skyrim-pc', 'The Elder Scrolls V: Skyrim', 'RPG', 'Single-Player', 599, 5, 'The Elder Scrolls V: Skyrim nás zavádí do provincie Skyrim, která je součástí obrovského světa Tamriel.\r\n<br><br>\r\nRozkládá se na severu a její panoráma tvoří převážně hory, sníh a rozvaliny starých pevností. To ale není pro seveřany žádný problém. Horší je, že byl starý král zavražděn a nezanechal po sobě žádného dědice. Skyrim se tak ocitl ve spárech občanské války. A ke všemu se vrátili Draci. Prastará stvoření, která ze země vyhnal esoterický kruh elitních bojovníků, zvaný The Blades. Řád po celá staletí chránil rodovou linii Septimů a zajištoval mír a pokoj ve světě. Draci byli dlouho pryč, ale teď jsou zpět, protože The Blades už téměř neexistují. Ve starých knihách se píše, že draky vyhnali takzvaní Dovakiin - zrozenci z draka, později přejmenovaní na řád The Blades. Hra není přímým pokračovatelem Oblivionu, ale lehce na něj poukazuje. Uběhlo 200 let od doby, kdy se znovunalezený dědic obětoval, aby zavřel brány Oblivionu.\r\n<br><br>\r\nPo smrti posledního Septima se The Blades stáhli do ústraní a byli pomalu likvidování nebo umírali stářím. Bez smyslu neměli důvod k existenci, a tak zanikli. Co nás čeká? Obrovský svět, volnost a desítky hodin zábavy. Jak jsme u série The Elder Scrolls zvyklí. Vývojáři se pustili do úplně nového enginu, který zobrazuje dynamická světla a stíny s větší realističností. Umí vypočítávat realistické sněžení a chytře kombinovat různé druhy počasí. AI, přepracované chování umělé inteligence, které umožní lépe nastavit charaktery postav a vytvářet vztahy mezi nimi. Vylepšené animace.\r\n<br><br>\r\nVše je vypracováno s pomocí technologie Havok, což dovoluje Bethesdě tvořit realistické reakce postav. Konverzovat tak můžete s NPC rovnou za chůze Propracovanější soubojový systém. S nepřáteli se budete bít obouruč: mohou to být dva meče, zbraň a štít, kouzlo a štít, či například dvě kouzla. Vše půjde patřičně kombinovat. Další krok k realističnosti hry. Mechanismy rozhovorů jsou od základu pozměněny. Např,: v průběhu rozhovoru bude postava, s kterou právě hovoříte, pokračovat v činnosti, kterou až dosud dělala. Již žádné strnulé pohledy do prázdna. Draci nejsou symbolem hry náhodně. Budou jednou z hlavních součástí dějové linie i volně pohybující se stvoření po provincii. Pokud draka zvládnete a zabijete, tak se jeho duše stane součástí vaší postavy a odemkne část příkazu v dračím jazyce. Tyto příkazy pak bude možné použít např.: k odehnání nepřítele, nebo se budete umět teleportovat z místa na místo.', 'PC\\1.png'),
(2, 1, 'ds-remaster-pc', 'Dark Souls: REMASTERED', 'Akční, RPG', 'Single-Player', 999, 8, 'Je zpět a krásnější než dříve!\r\n<br><br>\r\nZažijte znovu kritiky oceňovanou, žánr definující hru, která to všechno začala. Vraťte se do Lordranu v této nádherně přepracované hře s oslňujícími detaily. Dark Souls: Remastered zahrnuje obsah původní hry a DLC Artorias of the Abyss.\r\n<br><br>\r\nPřipravte se zemřít! (a to ne jen jednou)', 'PC\\2.png'),
(3, 1, 'red-dead-redemption-2-pc', 'Red Dead Redemption 2', 'Adventura', 'Single-Player, Online', 840, 4, 'Hra Red Dead Redemption 2 představuje velmi očekávané pokračování westernové akční videohry Red Dead Redemption z roku 2010 a podílejí se na ní vývojáři prvního dílu a hry Grand Theft Auto V. Dle slov autorů půjde o \"epický příběh života v nekompromisním srdci Ameriky\". Hra nabídne obrovský otevřený a atmosférický svět a zcela nový online multiplayer. Amerika, rok 1899. Konec éry divokého západu začal a muži zákona loví poslední zbývající psanecké gangy. Ti, kteří se nevzdají nebo nepodlehnou, jsou zabiti. Poté, co se ve městě Blackwater ošklivě zvrtne jedna vloupačka, Arthur Morgan a gang Van der Linde jsou nuceni prchnout. S hromadou federálních agentů a těch nejlepších lovců odměn ve státě v patách musí gang loupit, krást a probojovat se drsným americkým vnitrozemím, aby vůbec přežil. Jelikož kvůli vnitřním neshodám hrozí, že se gang zcela rozpadne, Arthur se musí rozhodnout mezi vlastními ideály a věrností ke gangu, který ho vychoval.', 'PC\\3.png'),
(4, 1, 'elden-ring-pc', 'Elden Ring', 'Akční, RPG', 'Single-Player', 940, 0, 'Ponořte se do napínavého dobrodružství a rozhodněte o osudu obrovského světa plného intrik a síly. Bojujte proti impozantním nepřátelům díky charakteristickému způsobu boje od FromSoftware a objevte širokou škálu vynalézavých strategií v akčním RPG Elden Ring.\r\n<br><br>\r\nZ PERA MISTRA\r\n<br><br>\r\nNový svět plný fantastických příběhů utkal Hidetaka Miyazaki - tvůrce kritiky uznávané série Dark Souls a George R.R. Martin - autor nejprodávanější fantasy série Píseň ledu a ohně. Hráči se vydají na cestu skrze propracovaný svět skrápěný krví a podvodem, který jim do cesty přinese různé postavy s vlastní jedinečnou motivací pomáhat nebo bránit pokroku hráče a další hrůzostrašné tvory. Během svých dobrodružství hráči zvolí osud této prokleté země tím, že odhalí její tajemství a mýty.\r\n<br><br>\r\nMONUMENTÁLNÍ SVĚT\r\n<br><br>\r\nSvět Elden Ring je velice rozsáhlý a nabídne přirozené počasí se střídáním dne a noci. Cestu můžete zvolit pěšky nebo na koni, sami či online s přáteli po travnatých pláních, dusivých bažinách a krásných lesích. K vystoupání budou lákat velkolepé spirálovité hory případně dechberoucí hrady, které ve hrách od FromSoftware nemají obdoby.\r\n<br><br>\r\nVLASTNÍ CESTOU\r\n<br><br>\r\nVydejte se hrou podle svého vlastního uvážení a to díky široké škále zbraní, magických schopností a dovedností, které se nacházejí po celém světě a lákají hráče sledovat dříve neprozkoumané oblasti. Zvolit si můžete přímý divoký boj nebo tajné bojové systémy hry, díky nimž získáte převahu.', 'PC\\4.png'),
(6, 1, 'sekiro-pc', 'Sekiro: Shadows Die Twice', 'Akční', 'Single-Player', 950, 19, 'Nezemřete jen tak. V Sekiro: Shadows Die Twice od tvůrců z FromSoftware, stojících za takovými peckami jako je Bloodborne a série Dark Souls se dočkáte temného fantasy se zbrusu novou hratelností.', 'PC\\6.jpg'),
(7, 1, 'ds2-pc', 'Dark Souls 2', 'Akční, RPG', 'Single-Player', 899, 12, 'Nástupce úspěšného titulu Dark Souls přichází. Nový díl nabízí několik podstatných vylepšení a samozřejmě nový příběh. Co se však nemění je unikátní herní styl, charakteristická náročnost stylu Dark Souls, výzva a intenzivně emocionální odměny. Dark Souls II nabízí navíc také vylepšený multiplayer, rozšířené možnosti přizpůsobení a nový engine.', 'PC\\7.jpg'),
(8, 1, 'ds3-pc', 'Dark Souls 3', 'Akční, RPG', 'Single-Player', 950, 16, 'Čeká vás temné a pochmurné dobrodružství zasazené do rozsáhlého pokřiveného světa plného příšer, zákeřných pastí a skrytých tajemství.\r\n<br><br>\r\nHra DARK SOULS III, jež vzniká v dílně věhlasného japonského studia FromSoftware pod taktovkou Hidetaky Miyazakiho, je závěrečnou kapitolou slavné série DARK SOULS, která je charakteristická svým soubojovým systémem využívajícím sečné zbraně i magii a nesmírně zábavnými prvky žánru akčních RPG. Ve snaze najít způsob, jak přežít blížící se apokalypsu, budou hráči prozkoumávat velké množství rozmanitých lokací ve vzájemně propojeném RPG světě. „Značka DARK SOULS se díky úžasné práci našich skvělých partnerů z FromSoftware i našich interních týmů z BANDAI NAMCO Entertainment stala ohromným fenoménem,\" řekl Herve Hoerdt, viceprezident pro marketing a digitální obsah z BANDAI NAMCO Entertainment Europe. „Především však máme nesmírné štěstí na věrnou a milující komunitu, která nás ohromně podporuje, a jsme rádi, že jí můžeme tuto poslední epizodu věnovat!\"', 'PC\\8.jpg'),
(9, 1, 'hollow-knight-pc', 'Hollow Knight', 'Indie', 'Single-Player', 450, 58, 'Vydejte se do krásného,zapomenutého světa hmyzu a hrdinů.\r\n<br><br>\r\nPod opuštěným městem Dirtmouth spí starobylé, zapomenuté království. Mnoha toto království láká a vydávají se do podzemí aby našli slávu, bohatství, nebo odpovědi na staré tajemství. Jak záhadný Hollow Knight budete procházet hlubiny, zkoumat tajemství bojovat se zlem.', 'PC\\9.png'),
(10, 2, 'dead-island-2-xbox', 'Dead Island 2', 'Akční, RPG, Adventura', 'Single-Player', 1350, 20, 'Dead Island 2 je pokračování úspěšné a cenami ověnčené hry Dead Island. Připravte se na rozsáhlé prostředí, mnoho zbraní a opravdu, opravdu hodně zombíků. Takže si připravte zbraně a připravte se zombíky udělat na 1000 a jeden způsob. Hra obsahuje nový příběh, zároveň je postavena na upraveném enginu pro více detailů, krev a další věci, které patří k apokalypse jsou samozřejmostí! Apokalypsa právě začala!!', 'XBOX\\10.jpg'),
(11, 3, 'bloodborne-ps', 'Bloodborne', 'Akční, RPG', 'Single-Player', 750, 20, 'Příběh nás zavede do starodávného a zdánlivě opuštěného města Yharnam, které vždy vynikalo na poli medicíny. Díky tomu mnoho poutníků hledalo svou záchranu právě za jeho branami, kde se měl nacházet lék na všechny nemoci.\r\n<br><br>\r\nNáš hrdina patří mezi ně, avšak ihned po příjezdu zjišťuje, že vše není tak, jak se zdá. Město je prokleto, v ulicích číhá smrt na každém kroku a vy se tak musíte vypořádat nejen se šíleným obyvatelstvem, ale život Vám znepříjemní i hrůzné existence vytažené přímo z nočních můr.\r\n<br><br>\r\nPostavte se svému strachu při hledání odpovědí v prastarém městě, které bylo prokleto zvláštní endemickou nemocí šířící se ulicemi jako oheň. Nebezpečí je všudypřítomný stín plížící se v tomto hrůzném světě a vy musíte odhalit jeho nejtemnější tajemství, chcete-li přežít.', 'PLAYSTATION\\11.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `idUser` int(11) NOT NULL,
  `role` varchar(30) NOT NULL DEFAULT 'user',
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `psc` varchar(5) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`idUser`, `role`, `username`, `password`, `name`, `surname`, `address`, `city`, `psc`, `phone`, `email`) VALUES
(1, 'admin', 'admin', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', '', '', '', '', '', '', ''),
(2, 'user', 'petr', '3667791642e38a8ab09987a0b5e051bdbb374937fd775cac9a8322c61a3ec0a2', 'Petr', 'Novotný', 'Ztracená 202', 'Ohrobec', '14000', '123444666', 'petr@google.com'),
(6, 'user', 'martin', '8d165104a19897911bb577a6471afa235209d1bcdcea66fa9eb97e6faa585690', 'Martin', 'Doležal', 'Pavlíkova 404', 'Praha', '12311', '123123123', 'martin.dolezal@seznam.cz'),
(7, 'user', 'novak', '9b9bec04b6f60189b3c85d3ae71444d21fc388b2d1e40c934411aadfd98cfb5d', 'Novák', 'Jan', 'Svatá 666', 'Brno', '13000', '434213566', 'novak.jan@gmail.com'),
(9, 'user', 'petr1', 'f23dfe8462b2106738f76c77612c1cc569482442a1c00c5746c1bdbf2684c9d7', 'Petr', 'Smolek', 'Pavlíkova 404', 'Praha', '12000', '123123123', 'petr@seznam.cz'),
(10, 'user', 'marek', '7ecf389caab8829bcec99c8a11583cf8bfb01d7aca3544b3a41bac948bb118e5', 'Marek', 'Nový', 'Jasnova 404', 'Brno', '10200', '333000999', 'marek@seznam.cz'),
(11, 'user', 'martin1', '50e021c2b1fd01e32b8413878f2ef41f672683e1a7d77bc4eacc5200be1f61a1', 'Martin', 'Zbranek', 'Pekař 303', 'Praha', '13400', '987456234', 'martin.zbranek@gmail.com'),
(12, 'user', 'tomas', '342cbbacba50e2130ee6d8a5d1e3d4e88ce729b10cb4561ac4967a01a7340dec', 'Tomáš', 'Hvizda', 'Street 323', 'Praha', '19022', '756894035', 'tomas@gmail.com'),
(13, 'user', 'lukas', 'f13a830707df2a612a14d7063450391aba7d28479128eeecbdb068e7d4b69edb', 'Lukáš', 'Zabraty', 'Grey 59', 'New Orleans', '00000', '987878903', 'zabraty@gmail.com'),
(14, 'user', 'pepa1', 'b2b8834987bb3387cc8c911afdaabfc7d9e078a7316649e50f9017193ade233b', 'Pepa', 'Maršík', 'Street 323', 'Brno', '12333', '987456232', 'pepa@gmail.com'),
(15, 'user', 'honza1', '89db4115826b8d6764dc818580606abb8bc9331604e412e02e841a572cf05d88', 'Honza', 'Kodet', 'Random 707', 'Plzeň', '13200', '342987653', 'kodet@gmail.com'),
(16, 'user', 'honza2', '228bb90d062ac997f939ae00966bf96a7eab85a0a7e0e6f592ecaad89c6f6e02', 'Honza', 'Feigh', 'Unknown 000', 'Brno', '98700', '234786597', 'feigh@seznam.cz'),
(17, 'user', 'martina', 'bba06720436527ec8713861c75d04f389735c4025c32171ee2cac2bdd127b0b5', 'Martina', 'Holá', 'Adamova 23', 'Praha', '12100', '567890876', 'martina@gmail.com'),
(18, 'user', 'jindra', '380d42ebe44f23ceb3b2ccf91fb4e9310311c82264add4717b8aa8197fc2b125', 'Jindra', 'Široký', 'Dolská 656', 'Praha', '12110', '769533285', 'jindra@seznam.cz');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`idCart`);

--
-- Indexes for table `cartproduct`
--
ALTER TABLE `cartproduct`
  ADD PRIMARY KEY (`idCP`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`idCat`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`idComment`);

--
-- Indexes for table `orderproduct`
--
ALTER TABLE `orderproduct`
  ADD PRIMARY KEY (`idOP`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`idOrder`);

--
-- Indexes for table `platform`
--
ALTER TABLE `platform`
  ADD PRIMARY KEY (`idPlatform`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`idProduct`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `idCart` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `cartproduct`
--
ALTER TABLE `cartproduct`
  MODIFY `idCP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=484;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `idCat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `idComment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orderproduct`
--
ALTER TABLE `orderproduct`
  MODIFY `idOP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `idOrder` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `platform`
--
ALTER TABLE `platform`
  MODIFY `idPlatform` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `idProduct` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
