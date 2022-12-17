-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2021 at 09:05 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.4.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `manage_expenses`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(64) NOT NULL,
  `uuid` varchar(48) NOT NULL,
  `account_name` varchar(128) NOT NULL,
  `user_uuid` varchar(48) NOT NULL,
  `friend_uuid` varchar(48) NOT NULL,
  `created_dt` datetime NOT NULL,
  `modified_dt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `account_transactions`
--

CREATE TABLE `account_transactions` (
  `id` bigint(64) NOT NULL,
  `account_uuid` varchar(48) NOT NULL,
  `user_uuid` varchar(48) NOT NULL,
  `type` varchar(8) NOT NULL,
  `title` varchar(512) NOT NULL,
  `amount` int(11) NOT NULL,
  `date` date NOT NULL,
  `created_dt` datetime NOT NULL,
  `modified_dt` datetime NOT NULL,
  `account_id` bigint(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contact_forms`
--

CREATE TABLE `contact_forms` (
  `id` bigint(64) NOT NULL,
  `name` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_dt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` bigint(64) NOT NULL,
  `code` varchar(8) NOT NULL,
  `name` varchar(64) NOT NULL,
  `num` varchar(8) NOT NULL,
  `alpha3` varchar(8) NOT NULL,
  `currency_code` varchar(8) DEFAULT NULL,
  `currency` varchar(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `code`, `name`, `num`, `alpha3`, `currency_code`, `currency`) VALUES
(1, 'IN', 'India', '3564', 'IND', 'INR', '₹'),
(2, 'AF', 'Afghanistan', '004', 'AFG', 'AFN', '؋	'),
(3, 'AX', 'Aland Islands', '248', 'ALA', 'EUR', '€	'),
(4, 'AL', 'Albania', '008', 'ALB', 'ALL', 'Lek	'),
(5, 'DZ', 'Algeria', '012', 'DZA', 'DZD', 'دج '),
(6, 'AS', 'American Samoa', '016', 'ASM', 'USD', '$	'),
(7, 'AD', 'Andorra', '20', 'AND', 'EUR', '€	'),
(8, 'AO', 'Angola', '024', 'AGO', 'AOA', 'Kz'),
(9, 'AI', 'Anguilla', '660', 'AIA', 'XCD', '‎$'),
(10, 'AQ', 'Antarctica', '010', 'ATA', '', ''),
(11, 'AG', 'Antigua and Barbuda', '28', 'ATG', 'XCD', '‎$'),
(12, 'AR', 'Argentina', '032', 'ARG', 'ARS', '$'),
(13, 'AM', 'Armenia', '051', 'ARM', 'AMD', '֏'),
(14, 'AW', 'Aruba', '533', 'ABW', ' AWG', ' ƒ'),
(15, 'AU', 'Australia', '036', 'AUS', '‎AUD', 'AU$'),
(16, 'AT', 'Austria', '040', 'AUT', 'EUR', '€'),
(17, 'AZ', 'Azerbaijan', '031', 'AZE', 'AZN', '₼'),
(18, 'BS', 'Bahamas', '044', 'BHS', 'BSD', 'B$'),
(19, 'BH', 'Bahrain', '048', 'BHR', 'BHD', 'BD '),
(20, 'BD', 'Bangladesh', '050', 'GD', ' ‎BDT', '‎৳'),
(21, 'BB', 'Barbados', '052', 'BRB', 'BBD', 'Bds$'),
(22, 'BY', 'Belarus', '112', 'BLR', 'BYN', 'Br'),
(23, 'BE', 'Belgium', '056', 'BEL', 'EUR', '€'),
(24, 'BZ', 'Belize', '084', 'BLZ', 'BZD', 'BZ$'),
(25, 'BJ', 'Benin', '204', 'BEN', 'XOF', 'CFA'),
(26, 'BM', 'Bermuda', '060', 'BMU', 'BMD', ' $'),
(27, 'BT', 'Bhutan', '064', 'BTN', 'BTN', 'Nu'),
(28, 'BO', 'Bolivia', '068', 'BOL', 'BOB', 'Bs'),
(29, 'BA', 'Bosnia and Herzegovina', '070', 'BIH', 'BAM', '‎KM'),
(30, 'BW', 'Botswana', '072', 'BWA', '‎BWP', '‎P'),
(31, 'BB', 'Bouvet Island', '074', 'BVT', 'NOK', 'kr'),
(32, 'BR', 'Brazil', '076', 'BRA', 'BRL', 'R$'),
(33, 'VG', 'British Virgin Islands', '092', 'VGB', 'USD', '	$'),
(34, 'IO', 'British Indian Ocean Territory', '086', 'IOT', 'GBP', '£'),
(35, 'BN', 'Brunei Darussalam', '096', 'BRN', 'BND', 'B$'),
(36, 'BG', 'Bulgaria', '100', 'BGR', 'BGL ', 'лв'),
(37, 'BF', 'Burkina Faso', '854', 'BFA', '', ''),
(38, 'BI', 'Burundi', '108', 'BDI', 'BIF', 'FBu'),
(39, 'KH', 'Cambodia', '116', 'KHM', 'KHR', '៛'),
(40, 'CM', 'Cameroon', '120', 'CMR', ' ‎XAF', 'FCFA'),
(41, 'CA', 'Canada', '124', 'CAN', 'CAD', 'CA$'),
(42, 'CV', 'Cape Verde', '132', 'CPV', 'CVE', 'Esc'),
(43, 'KY', 'Cayman Islands', '136', 'CYM', 'KYD', '‎$'),
(44, 'CF', 'Central African Republic', '140', 'CAF', 'XAF', 'FCFA'),
(45, 'TD', 'Chad', '148', 'TCD', 'XAF', 'FCFA'),
(46, 'CL', 'Chile', '152', 'CHL', '‎CLP', '$'),
(47, 'CN', 'China', '156', 'CHN', 'CNY', '¥'),
(48, 'HK', 'Hong Kong, SAR China', '344', 'HKG', 'HKD', 'HK$'),
(49, 'MO', 'Macao, SAR China', '446', 'MAC', 'MOP', 'MOP$'),
(50, 'CX', 'Christmas Island', '162', 'CXR', 'AUD', 'A$'),
(51, 'CC', 'Cocos (Keeling) Islands', '166', 'CCK', 'AUD.', '$'),
(52, 'CO', 'Colombia', '170', 'COL', 'COP', '$'),
(53, 'KM', 'Comoros', '174', 'COM', 'KMF', 'CF'),
(54, 'CG', 'Congo (Brazzaville)', '178', 'COG', 'CDF', 'FC'),
(55, 'CD', 'Congo, (Kinshasa)', '180', 'COD', 'CDF', 'FC'),
(56, 'CK', 'Cook Islands', '184', 'COK', 'NZD', 'NZ$'),
(57, 'CR', 'Costa Rica', '188', 'CRI', 'CRC', '₡'),
(58, 'CI', 'Côte dIvoire', '384', 'CIV', '', ''),
(59, 'HR', 'Croatia', '191', 'HRV', 'HRK', 'kn'),
(60, 'CU', 'Cuba', '192', 'CUB', 'CUP', '₱'),
(61, 'CY', 'Cyprus', '196', 'CYP', 'EUR', '€'),
(62, 'CZ', 'Czech Republic', '203', 'CZE', 'CZK', 'Kč'),
(63, 'DK', 'Denmark', '208', 'DNK', 'DKK', 'kr'),
(64, 'DJ', 'Djibouti', '262', 'DJI', 'DJF', 'Fdj'),
(65, 'DM', 'Dominica', '212', 'DMA', 'DOP', ' $'),
(66, 'DO', 'Dominican Republic', '214', 'DOM', 'DOP', ' $'),
(67, 'EC', 'Ecuador	', '218', 'ECU', 'USD', '$'),
(68, 'EG', 'Egypt', '818', 'EGY', 'EGP', 'E£'),
(69, 'SV', 'El Salvador', '222', 'SLV', 'SVC', '₡'),
(70, 'GQ', 'Equatorial Guinea', '226', 'GNQ', 'GQE', 'FG '),
(71, 'ER', 'Eritrea', '232', 'ERI', 'ERN', 'Nkf '),
(72, 'EE', 'Estonia', '233', 'EST', 'EEK', 'kr'),
(73, 'ET', 'Ethiopia', '231', 'ETH', 'ETB', 'Br '),
(74, 'FK', 'Falkland Islands (Malvinas)', '238', 'FLK', 'FKP', '£'),
(75, 'FO', 'Faroe Islands', '234', 'FRO', 'DKK', 'kr'),
(76, 'FJ', 'Fiji', '242', 'FJI', 'FJD', 'FJ$'),
(77, 'FI', 'Finland', '246', 'FIN', 'FIM', 'mk'),
(78, 'FR', 'France', '250', 'FRA', 'EUR', '€'),
(79, 'GF', 'French Guiana', '254', 'GUF', 'GNF', '€'),
(80, 'PF', 'French Polynesia', '258', 'PYF', 'XPF', '₣'),
(81, 'TF', 'French Southern Territories', '260', 'ATF', 'EUR', '	€'),
(82, 'GA', 'Gabon', '266', 'GAB', '', ''),
(83, 'GM', 'Gambia', '270', 'GMB', 'GMD', 'D'),
(84, 'GE', 'Georgia', '268', 'GEO', 'GEL', 'ლ'),
(85, 'DE', 'Germany', '276', 'DEU', 'EUR', '€'),
(86, 'GH', 'Ghana', '288', 'GHA', 'GHS', 'GH₵'),
(87, 'GI', 'Gibraltar', '292', 'GIB', 'GIP', '£'),
(88, 'GR', 'Greece', '300', 'GRC', 'EUR', '€'),
(89, 'GL', 'Greenland', '304', 'GRL', 'DKK', 'kr'),
(90, 'GD', 'Grenada', '308', 'GRD', 'XCD', '$'),
(91, 'GP', 'Guadeloupe', '312', 'GLP', 'EUR', '€'),
(92, 'GU', 'Guam', '316', 'GUM', 'USD', 'US$'),
(93, 'GT', 'Guatemala', '320', 'GTM', 'GTQ', 'Q'),
(94, 'GG', 'Guernsey', '831', 'GGY', 'GGP', '£'),
(95, 'GN', 'Guinea', '324', 'GIN', 'GNF', 'FG '),
(96, 'GW', 'Guinea-Bissau', '624', 'GNB', '', ''),
(97, 'GY', 'Guyana', '328', 'GUY', 'GYD', 'GY$'),
(98, 'HT', 'Haiti', '332', 'HTI', 'HTG', 'G'),
(99, 'HM', 'Heard and Mcdonald Islands', '334', 'HMD', 'AUD', 'AU$'),
(100, 'VA', 'Holy See (Vatican City State)', '336', 'VAT', 'EUR', '€'),
(101, 'HN', 'Honduras', '340', 'HND', 'HNL', 'L'),
(102, 'HU', 'Hungary', '348', 'HUN', 'HUF', 'Ft'),
(103, 'IS', 'Iceland', '352', 'ISL', 'ISK', 'kr'),
(104, 'ID', 'Indonesia', '360', 'IDN', 'IDR', 'Rp'),
(105, 'IR', 'Iran, Islamic Republic of', '364', 'IRN', 'IRR', ' ﷼'),
(106, 'IQ', 'Iraq', '368', 'IRQ', 'IQD', 'د.ع'),
(107, 'IE', 'Ireland', '372', 'IRL', 'IEP', ' £'),
(108, 'IM', 'Isle of Man', '833', 'IMN', 'GBP', ' £'),
(109, 'IL', 'Israel', '376', 'ISR', 'ILS', '₪'),
(110, 'IT', 'Italy', '380', 'ITA', 'ITL', ' ₤ '),
(111, 'JM', 'Jamaica', '388', 'JAM', 'JMD', ' $'),
(112, 'JP', 'Japan', '392', 'JPN', 'JPY', ' ¥ '),
(113, 'JE', 'Jersey', '832', 'JEY', 'JEP', '₤'),
(114, 'JO', 'Jordan', '400', 'JOR', 'JOD', ' د.أ'),
(115, 'KZ', 'Kazakhstan', '398', 'KAZ', 'KZT', '₸'),
(116, 'KE', 'Kenya', '404', 'KEN', 'KES', 'KSh'),
(117, 'KI', 'Kiribati', '296', 'KIR', 'AUD', ' AU$'),
(118, 'KP', 'Korea (North)', '408', 'PRK', 'KPW', ' ₩'),
(119, 'KR', 'Korea (South)', '410', 'KOR', 'KRW', '₩'),
(120, 'KW', 'Kuwait', '414', 'KWT', 'KWD', 'د.ك '),
(121, 'KG', 'Kyrgyzstan', '417', 'KGZ', 'KGS', 'лв'),
(122, 'LA', 'Lao PDR', '418', 'LAO', 'LAK', ' ₭N'),
(123, 'LV', 'Latvia', '428', 'LVA', 'LVL', ' Ls'),
(124, 'LB', 'Lebanon', '422', 'LBN', 'LBP', 'LL'),
(125, 'LS', 'Lesotho', '426', 'LSO', 'LSL', 'L'),
(126, 'LR', 'Liberia', '430', 'LBR', 'LRD', 'LD$'),
(127, 'LY', 'Libya', '434', 'LBY', 'LYD', 'LD'),
(128, 'LI', 'Liechtenstein', '438', 'LIE', 'CHF', 'Fr'),
(129, 'LT', 'Lithuania', '440', 'LTU', 'LTL', ' Lt'),
(130, 'LU', 'Luxembourg', '442', 'LUX', 'LUF', ' F'),
(131, 'MK', 'Macedonia, Republic of', '807', 'MKD', 'MKD', 'den'),
(132, 'MG', 'Madagascar', '450', 'MDG', 'MGA', 'Ar'),
(133, 'MW', 'Malawi', '454', 'MWI', 'MWK', 'K'),
(134, 'MY', 'Malaysia', '458', 'MYS', 'MYR', 'RM'),
(135, 'MV', 'Maldives', '462', 'MDV', 'MVR', 'Rf'),
(136, 'ML', 'Mali', '466', 'MLI', 'MLF', 'MAF'),
(137, 'MT', 'Malta', '470', 'MLT', 'MTL', '₤'),
(138, 'MH', 'Marshall Islands', '584', 'MHL', 'USD', '$'),
(139, 'MQ', 'Martinique', '474', 'MTQ', 'EUR', '€'),
(140, 'MR', 'Mauritania', '478', 'MRT', 'MRU', 'UM'),
(141, 'MU', 'Mauritius', '480', 'MUS', 'MUR', 'Rs'),
(142, 'YT', 'Mayotte', '175', 'MYT', 'EUR', '€'),
(143, 'MX', 'Mexico', '484', 'MEX', 'MXN', 'Mex$'),
(144, 'FM', 'Micronesia, Federated States of', '583', 'FSM', 'USD', '$'),
(145, 'MD', 'Moldova', '498', 'MDA', 'MDL ', 'L'),
(146, 'MC', 'Monaco', '492', 'MCO', 'MCF', 'fr'),
(147, 'MN', 'Mongolia', '496', 'MNG', 'MNT', ' ₮'),
(148, 'ME', 'Montenegro', '499', 'MNE', 'EUR', '€'),
(149, 'MS', 'Montserrat', '500', 'MSR', 'XCD', '$'),
(150, 'MA', 'Morocco', '504', 'MAR', 'MAD', 'DH'),
(151, 'MZ', 'Mozambique', '508', 'MOZ', 'MZN', 'MT'),
(152, 'MM', 'Myanmar', '104', 'MMR', 'MMK', 'K'),
(153, 'NA', 'Namibia', '516', 'NAM', 'NAD', '$'),
(154, 'NR', 'Nauru', '520', 'NRU', 'AUD', 'AU$'),
(155, 'NP', 'Nepal', '524', 'NPL', 'NPR', '₨'),
(156, 'NL', 'Netherlands', '528', 'NLD', 'NLG', 'fl'),
(157, 'AN', 'Netherlands Antilles', '530', 'ANT', 'ANG', 'NAƒ'),
(158, 'NC', 'New Caledonia', '540', 'NCL', 'XPF', ' F'),
(159, 'NZ', 'New Zealand', '554', 'NZL', 'NZD', 'NZ$'),
(160, 'NI', 'Nicaragua', '558', 'NIC', 'NIO', 'C$'),
(161, 'NE', 'Niger', '562', 'NER', 'NGN', '₦'),
(162, 'NG', 'Nigeria', '566', 'NGA', 'NGN', '₦'),
(163, 'NU', 'Niue', '570', 'NIU', 'NZD', '$'),
(164, 'NF', 'Norfolk Island', '574', 'NFK', 'AUD', 'AU$'),
(165, 'MP', 'Northern Mariana Islands', '580', 'MNP', 'USD', '$'),
(166, 'NO', 'Norway', '578', 'NOR', 'NOK', 'kr'),
(167, 'OM', 'Oman', '512', 'OMN', 'OMR', 'ر.ع.'),
(168, 'PK', 'Pakistan', '586', 'PAK', 'PKR', 'Rs'),
(169, 'PW', 'Palau', '585', 'PLW', 'USD', '$'),
(170, 'PS', 'Palestinian Territory', '275', 'PSE', 'ILS', '₪'),
(171, 'PA', 'Panama', '591', 'PAN', 'PAB', 'B/'),
(172, 'PG', 'Papua New Guinea', '598', 'PNG', 'PGK', 'K'),
(173, 'PY', 'Paraguay', '600', 'PRY', 'PYG', '₲'),
(174, 'PE', 'Peru', '604', 'PER', 'PEN', 'S/'),
(175, 'PH', 'Philippines', '608', 'PHL', 'PHP', '₱'),
(176, 'PN', 'Pitcairn', '612', 'PCN', 'NZD ', 'NZ$'),
(177, 'PL', 'Poland', '616', 'POL', 'PLN', 'zł'),
(178, 'PT', 'Portugal', '620', 'PRT', 'PTE', '$'),
(179, 'PR', 'Puerto Rico', '630', 'PRI', 'USD', '$'),
(180, 'QA', 'Qatar', '634', 'QAT', 'QAR', 'QR '),
(181, 'RE', 'Réunion', '638', 'REU', 'EUR', '€'),
(182, 'RO', 'Romania', '642', 'ROU', 'RON', 'lei'),
(183, 'RU', 'Russian Federation', '643', 'RUS', 'RUB', ' ₽'),
(184, 'RW', 'Rwanda', '646', 'RWA', 'RWF', 'R₣'),
(185, 'BL', 'Saint-Barthélemy', '652', 'BLM', 'EUR', '€'),
(186, 'SH', 'Saint Helena', '654', 'SHN', 'SHP ', '£'),
(187, 'KN', 'Saint Kitts and Nevis', '659', 'KNA', 'XCD ', '$'),
(188, 'LC', 'Saint Lucia', '662', 'LCA', 'XCD ', '$'),
(189, 'MF', 'Saint-Martin (French part)', '663', 'MAF', 'EUR', '€'),
(190, 'PM', 'Saint Pierre and Miquelon', '666', 'SPM', 'EUR', '€'),
(191, 'VC', 'Saint Vincent and Grenadines', '670', 'VCT', 'XCD', '$'),
(192, 'WS', 'Samoa', '882', 'WSM', 'WST', 'WS$'),
(193, 'SM', 'San Marino', '674', 'SMR', 'EUR ', '€'),
(194, 'ST', 'Sao Tome and Principe', '678', 'STP', 'STN', 'Db'),
(195, 'SA', 'Saudi Arabia', '682', 'SAU', 'SAR', '﷼'),
(196, 'SN', 'Senegal', '686', 'SEN', '', ''),
(197, 'RS', 'Serbia', '688', 'SRB', 'RSD', 'din '),
(198, 'SC', 'Seychelles', '690', 'SYC', 'SCR', 'SCR'),
(199, 'SL', 'Sierra Leone', '694', 'SLE', 'SLL', 'Le'),
(200, 'SG', 'Singapore', '702', 'SGP', 'SGD', 'S$'),
(201, 'SK', 'Slovakia', '703', 'SVK', 'EUR', '€'),
(202, 'SI', 'Slovenia', '705', 'SVN', 'EUR', '€'),
(203, 'SB', 'Solomon Islands', '090', 'SLB', 'SBD', 'SI$'),
(204, 'SO', 'Somalia', '706', 'SOM', '', NULL),
(205, 'ZA', 'South Africa', '710', 'ZAF', 'ZAR', 'R'),
(206, 'GS', 'South Georgia and the South Sandwich Islands', '239', 'SGS', 'GBP', '£ '),
(207, 'SS', 'South Sudan', '728', 'SSD', 'SDG', '£SD'),
(208, 'ES', 'Spain', '724', 'ESP', 'ESP', 'Pta'),
(209, 'LK', 'Sri Lanka', '144', 'LKA', 'LKR', 'රු'),
(210, 'SD', 'Sudan', '736', 'SDN', 'SDG', '£SD'),
(211, 'SR', 'Suriname', '740', 'SUR', 'SRD', '$'),
(212, 'SJ', 'Svalbard and Jan Mayen Islands', '744', 'SJM', '', ''),
(213, 'SZ', 'Swaziland', '748', 'SWZ', 'SZL', 'E'),
(214, 'SE', 'Sweden', '752', 'SWE', 'SEK', 'kr'),
(215, 'CH', 'Switzerland', '756', 'CHE', 'CHF', 'CHF'),
(216, 'SY', 'Syrian Arab Republic (Syria)', '760', 'SYR', 'SYP', 'LS '),
(217, 'TW', 'Taiwan, Republic of China', '158', 'TWN', 'TWD', 'NT$'),
(218, 'TJ', 'Tajikistan', '762', 'TJK', 'TJS', 'SM'),
(219, 'TZ', 'Tanzania, United Republic of', '834', 'TZA', 'TZS', 'TSh'),
(220, 'TH', 'Thailand', '764', 'THA', 'THB', '฿'),
(221, 'TL', 'Timor-Leste', '626', 'TLS', 'USD', '$'),
(222, 'TG', 'Togo', '768', 'TGO', '', ''),
(223, 'TK', 'Tokelau', '772', 'TKL', 'NZD', ' $'),
(224, 'TO', 'Tonga', '776', 'TON', 'TOP', 'T$'),
(225, 'TT', 'Trinidad and Tobago', '780', 'TTO', 'TTD', 'TT$'),
(226, 'TN', 'Tunisia', '788', 'TUN', 'TND', 'TD'),
(227, 'TR', 'Turkey', '792', 'TUR', 'TRY', '₺'),
(228, 'TM', 'Turkmenistan', '795', 'TKM', 'TMT', 'T'),
(229, 'TC', 'Turks and Caicos Islands', '796', 'TCA', 'USD ', '$'),
(230, 'TV', 'Tuvalu', '798', 'TUV', 'TVD', '$'),
(231, 'UG', 'Uganda', '800', 'UGA', 'UGX', 'USh'),
(232, 'UA', 'Ukraine', '804', 'UKR', 'UAH', '₴'),
(233, 'AE', 'United Arab Emirates', '784', 'ARE', 'AED', 'د.إ'),
(234, 'GB', 'United Kingdom', '826', 'GBR', 'GBP', '£'),
(235, 'US', 'United States of America', '840', 'USA', 'USD', '$'),
(236, 'UM', 'US Minor Outlying Islands', '581', 'UMI', 'USD', '$'),
(237, 'UY', 'Uruguay', '858', 'URY', 'UYU', '$U'),
(238, 'UZ', 'Uzbekistan', '860', 'UZB', 'UZS', 'лв'),
(239, 'VU', 'Vanuatu', '548', 'VUT', 'VUV', 'VT'),
(240, 'VE', 'Yemen', '887', 'YEM', 'YER', ' ر.ي'),
(241, 'VN', 'Venezuela (Bolivarian Republic)', '862', 'VEN', 'VEF', 'Bs.S'),
(242, 'VI', 'Viet Nam', '704', 'VNM', 'VND', '₫'),
(243, 'WF', 'Virgin Islands, US', '850', 'VIR', 'USD', '$'),
(244, 'EH', 'Wallis and Futuna Islands', '876', 'WLF', 'CFP ', ' ₣'),
(245, 'YE', 'Western Sahara', '732', 'ESH', 'MAD', 'EH'),
(246, 'ZM', 'Zambia', '894', 'ZMB', 'ZMW', 'ZK'),
(247, 'ZW', 'Zimbabwe', '716', 'ZWE', 'ZWD', 'Z$'),
(248, 'LKM', '', '', '', '', ''),
(249, 'LKLK', 'test', '1', '', '', ''),
(250, 'LKRR', 'test', '', 'lkm', '', ''),
(251, 'LKRRTT', 'test', '', '', '', ''),
(252, 'IA', 'india', '5', 'iii', '', ''),
(253, 'COUN', 'name', '5', '555', '', ''),
(254, 'CAD', 'cadminoum', '5', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(64) NOT NULL,
  `uuid` varchar(48) NOT NULL,
  `user_uuid` varchar(48) NOT NULL,
  `name` varchar(128) NOT NULL,
  `type` varchar(12) NOT NULL,
  `mode` varchar(18) NOT NULL,
  `budget` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `add_to_personal` tinyint(4) NOT NULL DEFAULT 0,
  `planned_at` date NOT NULL,
  `closed_at` date DEFAULT NULL,
  `created_dt` datetime DEFAULT NULL,
  `modified_dt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `event_members`
--

CREATE TABLE `event_members` (
  `id` bigint(64) NOT NULL,
  `event_id` bigint(64) DEFAULT NULL,
  `event_uuid` varchar(48) NOT NULL,
  `user_uuid` varchar(48) NOT NULL,
  `role` varchar(128) NOT NULL,
  `status` varchar(18) NOT NULL,
  `add_to_personal` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `id` bigint(64) NOT NULL,
  `user_uuid` varchar(48) NOT NULL,
  `friend_uuid` varchar(48) NOT NULL,
  `status` varchar(12) NOT NULL,
  `created_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `group_event_expenses`
--

CREATE TABLE `group_event_expenses` (
  `id` bigint(64) NOT NULL,
  `event_id` bigint(64) DEFAULT NULL,
  `event_uuid` varchar(48) NOT NULL,
  `user_uuid` varchar(48) NOT NULL,
  `title` varchar(255) NOT NULL,
  `value` int(11) NOT NULL,
  `split` tinyint(4) NOT NULL DEFAULT 1,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `group_event_payments`
--

CREATE TABLE `group_event_payments` (
  `id` bigint(64) NOT NULL,
  `event_id` bigint(64) DEFAULT NULL,
  `event_uuid` varchar(48) NOT NULL,
  `user_uuid` varchar(48) NOT NULL,
  `friend_uuid` varchar(48) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` varchar(12) NOT NULL,
  `created_dt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `individual_event_expenses`
--

CREATE TABLE `individual_event_expenses` (
  `id` bigint(64) NOT NULL,
  `event_id` bigint(64) DEFAULT NULL,
  `event_uuid` varchar(48) NOT NULL,
  `title` varchar(255) NOT NULL,
  `value` int(11) NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(64) NOT NULL,
  `uuid` varchar(48) NOT NULL,
  `sender_uuid` varchar(48) DEFAULT NULL,
  `user_uuid` varchar(48) NOT NULL,
  `activity_type` varchar(48) NOT NULL,
  `source_url` varchar(128) NOT NULL,
  `title` varchar(128) NOT NULL,
  `message` varchar(255) NOT NULL,
  `notification_sent` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_dt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `personal`
--

CREATE TABLE `personal` (
  `id` bigint(64) NOT NULL,
  `uuid` varchar(48) NOT NULL,
  `user_uuid` varchar(48) NOT NULL,
  `param_uuid` varchar(48) NOT NULL,
  `type` varchar(18) NOT NULL,
  `title` varchar(255) NOT NULL,
  `value` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `month_text` varchar(18) NOT NULL,
  `day` int(11) NOT NULL,
  `day_text` varchar(18) NOT NULL,
  `date` date NOT NULL,
  `created_dt` datetime DEFAULT NULL,
  `modified_dt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `responsibilities_personal`
--

CREATE TABLE `responsibilities_personal` (
  `id` bigint(64) NOT NULL,
  `uuid` varchar(48) NOT NULL,
  `user_uuid` varchar(48) NOT NULL,
  `type` varchar(18) NOT NULL,
  `title` varchar(255) NOT NULL,
  `value` int(11) NOT NULL,
  `created_dt` datetime NOT NULL,
  `modified_dt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `schema_version`
--

CREATE TABLE `schema_version` (
  `id` bigint(20) NOT NULL,
  `modified_dt` datetime DEFAULT NULL,
  `version` varchar(128) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `schema_version`
--

INSERT INTO `schema_version` (`id`, `modified_dt`, `version`) VALUES
(1, '2021-07-09 00:00:00', '1');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(64) NOT NULL,
  `uuid` varchar(48) NOT NULL,
  `fcm_token` varchar(128) NOT NULL,
  `country_id` int(11) NOT NULL,
  `code` varchar(128) NOT NULL,
  `name` varchar(128) NOT NULL,
  `email` varchar(128) DEFAULT NULL,
  `mobile` varchar(18) NOT NULL,
  `pw_seed` varchar(64) NOT NULL,
  `pw_hash` varchar(255) NOT NULL,
  `pw_algo` varchar(128) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `role` varchar(18) NOT NULL DEFAULT 'USERS',
  `pin` varchar(4) DEFAULT NULL,
  `account_type` varchar(18) NOT NULL DEFAULT 'BASIC',
  `feature_personal` tinyint(4) NOT NULL DEFAULT 1,
  `feature_events` tinyint(4) NOT NULL DEFAULT 1,
  `feature_accounts` tinyint(4) NOT NULL DEFAULT 1,
  `security_personal` tinyint(4) NOT NULL DEFAULT 0,
  `security_events` tinyint(4) NOT NULL DEFAULT 0,
  `security_accounts` tinyint(4) NOT NULL DEFAULT 0,
  `security_profile` tinyint(4) NOT NULL DEFAULT 0,
  `security_friends` tinyint(4) NOT NULL DEFAULT 0,
  `verified` tinyint(4) NOT NULL DEFAULT 1,
  `otp` varchar(8) NOT NULL,
  `jwt_token` varchar(128) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_dt` datetime DEFAULT NULL,
  `modified_dt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_uuid` (`uuid`) USING BTREE,
  ADD KEY `indexing_user_uuid` (`user_uuid`) USING BTREE,
  ADD KEY `indexing_friend_uuid` (`friend_uuid`) USING BTREE,
  ADD KEY `compound_indexing_account_user` (`account_name`,`user_uuid`) USING BTREE,
  ADD KEY `compound_indexing_user_friend` (`user_uuid`,`friend_uuid`) USING BTREE;

--
-- Indexes for table `account_transactions`
--
ALTER TABLE `account_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indexing_account_uuid` (`account_uuid`) USING BTREE,
  ADD KEY `compound_indexing_account_user` (`account_uuid`,`user_uuid`) USING BTREE,
  ADD KEY `indexing_user_uuid` (`user_uuid`) USING BTREE,
  ADD KEY `fk_account_id` (`account_id`);

--
-- Indexes for table `contact_forms`
--
ALTER TABLE `contact_forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_uuid` (`uuid`),
  ADD KEY `indexing_user_uuid` (`user_uuid`),
  ADD KEY `indexing_mode` (`mode`) USING BTREE,
  ADD KEY `compound_indexing_user_mode` (`user_uuid`,`mode`) USING BTREE;

--
-- Indexes for table `event_members`
--
ALTER TABLE `event_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indexing_user_uuid` (`user_uuid`),
  ADD KEY `indexing_event_uuid` (`event_uuid`),
  ADD KEY `compound_indexing_event_user` (`event_uuid`,`user_uuid`) USING BTREE,
  ADD KEY `fk_event_members_event_id` (`event_id`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indexing_user_uuid` (`user_uuid`) USING BTREE,
  ADD KEY `indexing_friend_uuid` (`friend_uuid`) USING BTREE,
  ADD KEY `compound_indexing_user_friend` (`user_uuid`,`friend_uuid`) USING BTREE;

--
-- Indexes for table `group_event_expenses`
--
ALTER TABLE `group_event_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indexing_event_uuid` (`event_uuid`),
  ADD KEY `indexing_user_uuid` (`user_uuid`),
  ADD KEY `compound_indexing_event_user` (`event_uuid`,`user_uuid`) USING BTREE,
  ADD KEY `fk_group_event_expenses_event_id` (`event_id`) USING BTREE,
  ADD KEY `compound_indexing_event_user_split` (`event_uuid`,`user_uuid`,`split`) USING BTREE;

--
-- Indexes for table `group_event_payments`
--
ALTER TABLE `group_event_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indexing_event_uuid` (`event_uuid`),
  ADD KEY `indexing_user_uuid` (`user_uuid`),
  ADD KEY `indexing_friend_uuid` (`friend_uuid`),
  ADD KEY `compound_indexing_event_user` (`event_uuid`,`user_uuid`) USING BTREE,
  ADD KEY `compound_indexing_event_user_friend` (`event_uuid`,`user_uuid`,`friend_uuid`) USING BTREE,
  ADD KEY `compound_indexing_event_friend` (`event_uuid`,`friend_uuid`) USING BTREE,
  ADD KEY `fk_group_event_payments_event_id` (`event_id`) USING BTREE;

--
-- Indexes for table `individual_event_expenses`
--
ALTER TABLE `individual_event_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `indexing_event_uuid` (`event_uuid`),
  ADD KEY `compound_indexing_event_date` (`event_uuid`,`date`) USING BTREE,
  ADD KEY `indexing_date` (`date`) USING BTREE,
  ADD KEY `fk_individual_event_expenses_event_id` (`event_id`) USING BTREE;

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_uuid` (`uuid`) USING BTREE,
  ADD KEY `indexing_sender_uuid` (`sender_uuid`) USING BTREE,
  ADD KEY `indexing_notification_sent` (`notification_sent`) USING BTREE,
  ADD KEY `indexing_status` (`status`) USING BTREE,
  ADD KEY `indexing_user_uuid` (`user_uuid`) USING BTREE;

--
-- Indexes for table `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_uuid` (`uuid`),
  ADD KEY `indexing_user_uuid` (`user_uuid`),
  ADD KEY `compound_indexing_user_year` (`user_uuid`,`year`) USING BTREE,
  ADD KEY `compound_indexing_user_type_year_month` (`user_uuid`,`type`,`year`,`month`) USING BTREE,
  ADD KEY `compound_indexing_user_type_date` (`user_uuid`,`type`,`date`) USING BTREE,
  ADD KEY `compound_indexing_user_year_month_day` (`user_uuid`,`year`,`month`,`day`) USING BTREE,
  ADD KEY `compound_indexing_user_year_month` (`user_uuid`,`year`,`month`) USING BTREE;

--
-- Indexes for table `responsibilities_personal`
--
ALTER TABLE `responsibilities_personal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_uuid` (`uuid`) USING BTREE,
  ADD KEY `indexing_user_uuid` (`user_uuid`) USING BTREE;

--
-- Indexes for table `schema_version`
--
ALTER TABLE `schema_version`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_uuid` (`uuid`),
  ADD UNIQUE KEY `unique_mobile` (`mobile`) USING BTREE,
  ADD UNIQUE KEY `unique_email` (`email`) USING BTREE,
  ADD KEY `indexing_country_id` (`country_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(64) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_transactions`
--
ALTER TABLE `account_transactions`
  MODIFY `id` bigint(64) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_forms`
--
ALTER TABLE `contact_forms`
  MODIFY `id` bigint(64) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` bigint(64) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=255;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(64) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_members`
--
ALTER TABLE `event_members`
  MODIFY `id` bigint(64) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
  MODIFY `id` bigint(64) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_event_expenses`
--
ALTER TABLE `group_event_expenses`
  MODIFY `id` bigint(64) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `group_event_payments`
--
ALTER TABLE `group_event_payments`
  MODIFY `id` bigint(64) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `individual_event_expenses`
--
ALTER TABLE `individual_event_expenses`
  MODIFY `id` bigint(64) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(64) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal`
--
ALTER TABLE `personal`
  MODIFY `id` bigint(64) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `responsibilities_personal`
--
ALTER TABLE `responsibilities_personal`
  MODIFY `id` bigint(64) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schema_version`
--
ALTER TABLE `schema_version`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(64) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account_transactions`
--
ALTER TABLE `account_transactions`
  ADD CONSTRAINT `fk_account_id` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `event_members`
--
ALTER TABLE `event_members`
  ADD CONSTRAINT `fk_event_members_event_id` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `group_event_expenses`
--
ALTER TABLE `group_event_expenses`
  ADD CONSTRAINT `fk_gee_event_id` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `group_event_payments`
--
ALTER TABLE `group_event_payments`
  ADD CONSTRAINT `fk_qevents_id` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `individual_event_expenses`
--
ALTER TABLE `individual_event_expenses`
  ADD CONSTRAINT `fk_events_id` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
