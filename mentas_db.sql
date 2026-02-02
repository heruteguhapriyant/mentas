-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 02, 2026 at 03:56 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mentas_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('blog','zine','merch') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'blog',
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `type`, `slug`, `description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Berita', 'blog', 'berita', 'Berita terkini seputar seni dan budaya', 1, 1, '2026-01-18 15:11:13', '2026-01-18 15:11:13'),
(2, 'Esai', 'blog', 'esai', 'Esai dan opini tentang kesenian', 1, 2, '2026-01-18 15:11:13', '2026-01-18 15:11:13'),
(3, 'Komunitas', 'blog', 'komunitas', 'Cerita dan kegiatan komunitas seni', 1, 3, '2026-01-18 15:11:13', '2026-01-18 15:11:13'),
(4, 'Tradisi', 'blog', 'tradisi', 'Warisan tradisi dan budaya nusantara', 1, 4, '2026-01-18 15:11:13', '2026-01-18 15:11:13'),
(5, 'Ekosistem', 'blog', 'ekosistem', 'Ekosistem seni dan budaya Indonesia', 1, 5, '2026-01-18 15:11:13', '2026-01-18 15:11:13'),
(7, 'Mitologi', 'blog', 'mitologi-1', 'Berisi tentang mitologi yang ada di seluruh dunia', 1, 6, '2026-01-20 10:37:00', '2026-01-20 10:37:00'),
(8, 'Merchandise', 'merch', 'merchandise', 'Aksesoris dan Pakaian Official', 1, 2, '2026-02-02 14:22:09', '2026-02-02 15:07:02'),
(9, 'Buku', 'merch', 'buku', 'Buku dan Terbitan', 1, 1, '2026-02-02 14:22:09', '2026-02-02 15:07:02'),
(10, 'Esai', 'zine', 'esai-sastra', 'Esai Sastra', 1, 2, '2026-02-02 14:22:09', '2026-02-02 15:07:02'),
(11, 'Prosa', 'zine', 'prosa', 'Prosa Sastra', 1, 3, '2026-02-02 14:22:09', '2026-02-02 15:07:02'),
(12, 'Puisi', 'zine', 'puisi', 'Puisi Sastra', 1, 4, '2026-02-02 14:22:09', '2026-02-02 15:07:02'),
(13, 'Cerpen', 'zine', 'cerpen', 'Cerita Pendek', 1, 1, '2026-02-02 14:22:09', '2026-02-02 15:07:02'),
(14, 'Seni Rupa', 'zine', 'seni-rupa', 'Seni Rupa dan Visual', 1, 5, '2026-02-02 14:22:09', '2026-02-02 15:07:02'),
(15, 'Zine', 'zine', 'zine-digital', 'Zine Digital', 1, 6, '2026-02-02 14:22:09', '2026-02-02 15:07:02');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `post_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `body` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `communities`
--

CREATE TABLE `communities` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `communities`
--

INSERT INTO `communities` (`id`, `name`, `slug`, `description`, `image`, `location`, `contact`, `website`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Teater Djavu SMK Taman Siswa', 'teater-djavu-smk-taman-siswa', 'Komunitas teater pelajar yang aktif dalam berbagai festival dan pentas seni. Fokus pada teater kontemporer dengan sentuhan budaya lokal.', NULL, 'Kudus, Jawa Tengah', '081234567890', NULL, 1, '2024-12-31 17:00:00', '2026-01-18 23:16:33'),
(2, 'Sanggar Widyas Budaya', 'sanggar-widyas-budaya', 'Sanggar seni tari tradisional yang telah berdiri lebih dari 18 tahun. Mengajarkan berbagai tarian Jawa klasik dan kreasi baru.', NULL, 'Semarang, Jawa Tengah', '087654321098', 'https://widyasbudaya.com', 1, '2005-06-14 17:00:00', '2026-01-18 23:16:33'),
(3, 'Komunitas Sastra Kudus', 'komunitas-sastra-kudus', 'Komunitas pegiat sastra yang rutin mengadakan diskusi, bedah buku, dan workshop penulisan. Terbuka untuk semua kalangan.', NULL, 'Kudus, Jawa Tengah', NULL, NULL, 1, '2018-03-19 17:00:00', '2026-01-18 23:16:33'),
(4, 'Sanggar Ngesti Laras Madya', 'sanggar-ngesti-laras-madya', 'Sanggar pelestarian Wayang Klithik Wonosoco yang didirikan oleh Ki Sutikno. Menjadi pusat pembelajaran dan regenerasi dalang wayang klithik.', NULL, 'Desa Wonosoco, Undaan, Kudus', NULL, NULL, 1, '2009-12-31 17:00:00', '2026-01-18 23:16:33');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` text,
  `venue` varchar(255) DEFAULT NULL,
  `venue_address` text,
  `event_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `ticket_price` decimal(12,2) DEFAULT '0.00',
  `ticket_quota` int DEFAULT '0',
  `tickets_sold` int DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `slug`, `description`, `venue`, `venue_address`, `event_date`, `end_date`, `cover_image`, `ticket_price`, `ticket_quota`, `tickets_sold`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Malam Puisi: Rindu yang Tak Sampai', NULL, 'Malam pembacaan puisi bertema cinta dan kerinduan. Bersama 10 penyair muda Nusantara yang akan membawakan karya-karya terbaik mereka. Acara ini GRATIS dan terbuka untuk umum.', 'Taman Budaya Yogyakarta', 'Jl. Sriwedani No.1, Ngupasan, Kec. Gondomanan, Kota Yogyakarta, DIY 55122', '2026-02-14 19:00:00', '2026-02-14 21:30:00', 'uploads/events/697c8485141c0.png', 0.00, 150, 1, 1, '2026-01-30 10:14:29', '2026-01-30 11:09:56'),
(2, 'Workshop Menulis Cerpen bersama Leila S. Chudori', NULL, 'Workshop menulis cerpen intensif selama satu hari penuh bersama penulis senior Leila S. Chudori. Peserta akan belajar teknik plot, karakterisasi, dan editing karya. Termasuk makan siang dan sertifikat.', 'Gedung Komunitas Salihara', 'Jl. Salihara No.16, Pasar Minggu, Jakarta Selatan 12520', '2026-03-08 09:00:00', '2026-03-08 16:00:00', 'uploads/events/697c85a37cf1e.png', 250000.00, 50, 0, 1, '2026-01-30 10:19:15', '2026-01-30 10:34:30'),
(3, 'Festival Mentas 2026: Suara Generasi', NULL, 'Festival tahunan Mentas.id menghadirkan pentas sastra, workshop, diskusi buku, dan pameran karya dari berbagai komunitas sastra Indonesia. 3 hari penuh aktivitas literasi!', 'Taman Ismail Marzuki', 'Jl. Cikini Raya No.73, Cikini, Menteng, Jakarta Pusat 10330', '2026-04-25 10:00:00', '2026-04-27 21:00:00', 'uploads/events/697c85f6a2896.png', 75000.00, 500, 0, 1, '2026-01-30 10:20:38', '2026-01-30 10:33:11'),
(4, 'Webinar: Menulis di Era AI - Ancaman atau Peluang?', NULL, 'Diskusi online membahas dampak AI terhadap dunia penulisan. Narasumber: Ahmad Tohari, Dee Lestari, dan pakar AI dari UI. Link Zoom akan dikirim via email setelah registrasi.', 'Online via Zoom', 'Link akan dikirim ke email terdaftar', '2026-02-28 19:30:00', '2026-02-28 21:30:00', 'uploads/events/697c87b4a7578.png', 0.00, 200, 0, 1, '2026-01-30 10:28:04', '2026-01-30 10:32:37');

-- --------------------------------------------------------

--
-- Table structure for table `payment_settings`
--

CREATE TABLE `payment_settings` (
  `id` int NOT NULL,
  `bank_name` varchar(100) NOT NULL DEFAULT 'Mandiri',
  `account_number` varchar(50) NOT NULL DEFAULT '1840005061294',
  `account_name` varchar(255) NOT NULL DEFAULT 'Abimanyu Ianocta Per',
  `qris_image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payment_settings`
--

INSERT INTO `payment_settings` (`id`, `bank_name`, `account_number`, `account_name`, `qris_image`, `is_active`, `created_at`) VALUES
(1, 'Mandiri', '1840005061294', 'Abimanyu Ianocta Per', NULL, 1, '2026-01-30 04:04:48'),
(2, 'Mandiri', '1840005061294', 'Abimanyu Ianocta Per', NULL, 1, '2026-01-30 04:12:38');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cover_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `author_id` int DEFAULT NULL,
  `status` enum('draft','published') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `views` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `published_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `slug`, `excerpt`, `body`, `cover_image`, `category_id`, `author_id`, `status`, `views`, `created_at`, `updated_at`, `published_at`) VALUES
(1, 'Wayang Klithik Wonosoco, Ki Sutikno, Dalang Terakhir dan Beban Ingatan', 'wayang-klithik-wonosoco-ki-sutikno-dalang-terakhir-dan-beban-ingatan-1', 'ADA saat-saat ketika kebudayaan tidak hadir sebagai perayaan yang riuh, melainkan sebagai bisikan halus yang meminta untuk didengarkan dengan lebih pelan.', 'Oleh Imam Khanafi, penulis esai, tinggal di Kudus\r\n\r\nADA saat-saat ketika kebudayaan tidak hadir sebagai perayaan yang riuh, melainkan sebagai bisikan halus yang meminta untuk didengarkan dengan lebih pelan. Ia hidup dalam jeda, dalam ingatan yang hampir terlewat, dan dalam kesediaan manusia untuk menoleh ke belakang tanpa rasa nostalgia berlebihan. Dari sanalah kesenian tradisi menemukan maknanya yang paling dalam: bukan sekadar warisan masa lalu, tetapi cermin yang mengajukan pertanyaan sunyi kepada kita hari ini, apakah kita masih mau merawat yang rapuh, yang sederhana, dan yang nyaris dilupakan, agar ia tetap bernapas di tengah perubahan zaman.\r\n\r\nDi Desa Wonosoco, Kecamatan Undaan, Kabupaten Kudus, kesenian tidak pernah berdiri sendiri. Ia lahir dari tanah, air, dan doa-doa yang berulang dalam waktu panjang. Wayang Klithik Wonosoco merupakan wayang kayu pipih yang digerakkan dengan tangan dalang bukan sekadar bentuk seni pertunjukan, melainkan bagian dari kosmologi desa. Ia hidup bersama ritual bersih sendang, bersama kisah tentang mata air yang dijaga, dan bersama ingatan kolektif tentang leluhur.\r\n\r\nCerita lisan desa menyebutkan bahwa Wayang Klithik lahir bersamaan dengan pendirian Wonosoco. Dalam kisah itu, Pangeran Kajoran dari Mataram dan Ki Saji bersemedi setelah peperangan. Dari semedi itu, dua mata air yaitu Sendang Dewot dan Sendang Gading yang muncul sebagai penanda kehidupan baru. Air bukan hanya sumber penghidupan, tetapi juga pusat spiritualitas. Di sekitar air itulah, Wayang Klithik tumbuh sebagai medium doa, pengingat, dan penuntun moral.\r\n\r\nPerkiraan sejarah menempatkan Wayang Klithik Wonosoco telah ada sejak abad ke-13, sebuah masa peralihan penting di Jawa ketika Islam mulai menyebar dan bernegosiasi dengan tradisi pra-Islam. Wayang Klithik menjadi ruang temu: antara dakwah dan ritus lama, antara kisah-kisah babad Jawa dan nilai-nilai spiritual yang diwariskan turun-temurun.\r\n\r\nHari ini, Wayang Klithik Wonosoco berada di titik kritis. Dari generasi ke generasi, tradisi ini diwariskan melalui garis dalang. Namun kini, hanya tersisa satu dalang aktif: Ki Sutikno, generasi kedelapan dalang Wayang Klithik Wonosoco.\r\n\r\nLebih dari 30 tahun Ki Sutikno mendalang, belajar langsung dari ayahnya, dan setia membawakan lakon-lakon klasik babad Tanah Jawa. Ia bukan sekadar seniman, tetapi penjaga ingatan. Setiap gerak wayang, setiap dialog, menyimpan pengetahuan yang tidak tertulis di buku.\r\n\r\nDi Wonosoco, wayang kayu itu masih digerakkan. Suaranya mungkin semakin lirih, tetapi selama ia masih dipentaskan, ingatan desa belum sepenuhnya hilang. Wayang Klithik mengingatkan kita bahwa sejarah tidak selalu ditulis di buku, kadang ia hidup di panggung kecil, di tangan seorang dalang, menunggu untuk terus didengarkan.', 'uploads/posts/696e693835e17.jpeg', 4, 1, 'published', 131, '2026-01-09 21:14:00', '2026-01-19 17:26:42', '2026-01-09 21:14:00'),
(2, 'Ruang Tamu yang Tidak Pernah Kosong', 'ruang-tamu-yang-tidak-pernah-kosong', 'Argumentasi Sisi Rekonstruksi dan Drama Trauma Domestik dalam Pementasan Teater Djavu SMK Taman Siswa dalam final Festival Teater Pelajar.', 'Oleh Imam Khanafi, esais, penonton teater\r\n\r\nRuang tamu kerap dipahami sebagai ruang paling netral dalam rumah. Ia bukan kamar tidur yang intim, bukan dapur yang fungsional, bukan halaman yang terbuka. Ruang tamu adalah tempat menerima: tamu, kabar, gosip, konflik, dan sering kali kepura-puraan.\r\n\r\nDalam pementasan Argumentasi Sisi Rekonstruksi karya Diky Soemarno oleh Teater Djavu, ruang tamu tidak lagi menjadi latar pasif, melainkan pusat gravitasi dramatik. Seluruh konflik keluarga patriarkal yang paradoksnya kini berwujud matriarkal dipadatkan ke dalam satu ruang yang seolah tak pernah bernapas lega.\r\n\r\nPementasan yang dilakukan dengan sting panggung di ruang tamu, dengan jam kursi, perabot kuno, dan dialog yang berlangsung di sana, menegaskan satu hal penting: tragedi keluarga tidak pernah benar-benar pergi. Ia hanya berpindah jam, berpindah kursi, berpindah posisi duduk.\r\n\r\nDalam konteks ini, ruang tamu menjadi metafora dari kehidupan keluarga Indonesia pasca-trauma domestik: terlihat rapi di permukaan, namun menyimpan endapan luka yang tak selesai.\r\n\r\nIbu Sastro adalah pusat kontrol ruang tamu itu. Di usia 50-an, ia hadir sebagai figur dominan yang mengatur ritme, jam, dan batasan. Ia bukan sekadar ibu, melainkan penjaga moral, penentu aturan, dan pengingat masa lalu yang tak boleh dilupakan namun juga tak boleh dibicarakan secara jujur.\r\n\r\nJudul Argumentasi Sisi Rekonstruksi menemukan maknanya di sini. Argumentasi bukan sekadar debat verbal, melainkan cara bertahan hidup. Setiap karakter berargumentasi untuk membenarkan posisinya: ibu dengan kontrol, Lusi dengan kepatuhan, Yoni dengan pemberontakan, Bi Iyah dengan keheningan.\r\n\r\nPementasan Teater Djavu di ruang tamu menghapus jarak antara panggung dan kehidupan. Penonton tidak lagi aman sebagai pengamat. Mereka duduk di ruang yang sama, menghirup udara yang sama, dan mungkin mengingat ruang tamu mereka sendiri.\r\n\r\nDan di situlah kekuatan naskah ini bekerja: membuat kita bertanya, trauma apa yang sedang kita rawat diam-diam di ruang tamu rumah kita?', NULL, 2, 1, 'published', 91, '2025-12-25 00:23:00', '2026-01-19 14:37:15', '2025-12-25 00:23:00'),
(3, 'Sanggar Widyas Budaya Gelar Fragmen Ramayana dalam Pentas Tahunan ke-18', 'sanggar-widyas-budaya-gelar-fragmen-ramayana', 'Sanggar Widyas Budaya kembali menggelar pentas tahunan dengan membawakan fragmen Ramayana yang memukau.', 'Sanggar Widyas Budaya kembali menggelar pentas tahunan ke-18 mereka dengan membawakan fragmen Ramayana yang memukau para penonton.\r\n\r\nAcara yang digelar di Gedung Kesenian ini menjadi bukti konsistensi sanggar dalam melestarikan seni tari tradisional Jawa. Puluhan penari dari berbagai usia tampil apik membawakan kisah epik Ramayana.\r\n\r\nKetua Sanggar, Ibu Widyastuti, menyampaikan bahwa pentas tahunan ini merupakan ajang untuk menunjukkan hasil latihan selama setahun penuh. \"Kami berharap generasi muda tetap mencintai seni tradisi kita,\" ujarnya.\r\n\r\nPentas tahun ini menampilkan babak Shinta Obong yang menjadi klimaks dari kisah Ramayana. Para penari senior membawakan karakter utama dengan sangat memukau.', NULL, 1, 1, 'published', 47, '2025-12-29 12:36:00', '2026-01-19 15:53:41', '2025-12-29 12:36:00'),
(4, 'Komunitas Sastra Kudus Gelar Diskusi Bulanan', 'komunitas-sastra-kudus-diskusi-bulanan', 'Komunitas Sastra Kudus mengadakan diskusi bulanan membahas perkembangan sastra kontemporer Indonesia.', 'Komunitas Sastra Kudus menggelar diskusi bulanan mereka dengan tema \"Sastra Kontemporer Indonesia: Tantangan dan Peluang\".\r\n\r\nDiskusi yang berlangsung di Cafe Literasi ini dihadiri oleh puluhan pegiat sastra dari berbagai latar belakang. Narasumber utama, Pak Bambang Suryanto, membahas tentang bagaimana sastra Indonesia bisa tetap relevan di era digital.\r\n\r\n\"Kita perlu memanfaatkan platform digital untuk menyebarkan karya sastra, namun tetap menjaga kualitas dan kedalaman karya,\" ujar Bambang.\r\n\r\nPeserta juga berdiskusi tentang tantangan publikasi buku di era digital dan bagaimana komunitas bisa berperan dalam ekosistem literasi.', NULL, 3, 1, 'published', 35, '2026-01-05 07:00:00', '2026-01-19 14:36:33', '2026-01-05 07:00:00'),
(5, 'Ekosistem Seni Budaya Jawa Tengah: Peta dan Potensi', 'ekosistem-seni-budaya-jawa-tengah', 'Memetakan ekosistem seni budaya di Jawa Tengah dan potensi pengembangannya.', 'Jawa Tengah memiliki ekosistem seni budaya yang sangat kaya dan beragam. Dari wayang kulit hingga batik, dari gamelan hingga tari tradisional, semuanya membentuk identitas budaya yang unik.\r\n\r\nBerdasarkan pemetaan yang dilakukan tim Mentas.id, terdapat lebih dari 500 komunitas seni aktif di seluruh Jawa Tengah. Komunitas-komunitas ini tersebar dari Semarang hingga Solo, dari Kudus hingga Kebumen.\r\n\r\nYang menarik, banyak komunitas yang mulai menggabungkan tradisi dengan inovasi. Misalnya, pertunjukan wayang kontemporer yang menggabungkan teknologi mapping dengan lakon klasik.\r\n\r\nTantangan terbesar adalah regenerasi. Banyak maestro seni yang sudah berusia lanjut namun belum memiliki penerus yang memadai. Ini menjadi PR besar bagi kita semua.', NULL, 5, 1, 'published', 75, '2026-01-12 03:00:00', '2026-01-23 13:51:25', '2026-01-12 03:00:00'),
(6, 'Widyas Budaya 2023', 'widyas-budaya-2023', 'Sanggar Widyas Budaya menggelar pentas tahunan ke-18 di Gedung Serbaguna Desa Gabus, Pati pada Minggu (28/12/2025) malam.', 'Sanggar Widyas Budaya menggelar pentas tahunan ke-18 di Gedung Serbaguna Desa Gabus, Kecamatan Gabus, Kabupaten Pati, Minggu (28/12) malam. Dalam perhelatan ini, mereka menyuguhkan berbagai tarian daerah hingga sendratari kolosal yang mengangkat kisah Ramayana.\r\n\r\nPara seniman muda dengan apik memeragakan berbagai adegan dari epos asal India tersebut. Mulai dari adegan penculikan Sinta hingga peperangan antara Rama melawan Rahwana.\r\n\r\nSelain Sendratari Ramayana, puluhan penari juga bergantian menunjukkan kebolehannya di hadapan ratusan penonton. Penampilan mereka meliputi Tari Jeket Nuswantoro, Golek Manis, Tari Kupu-kupu Manis, Tari Tampak, Tari Lilin, dan sejumlah tarian lainnya. Aksi para seniman cilik hingga remaja ini sukses memukau penonton yang hadir.\r\n\r\nPimpinan Sanggar Tari Widyas Budaya, Hani Indrayani mengaku, pementasan ini dapat terlaksana berkat kerja keras anak didiknya dalam berlatih selama satu tahun terakhir. Ia juga mengapresiasi dukungan para wali murid yang telah mempercayakan pendidikan seni putra-putrinya kepada Widyas Budaya.\r\n\r\n“Ada sekitar 56 anak yang ikut pentas ini dengan sembilan tarian dan satu sendratari Ramayana. Ini untuk ajang kreativitas anak. Selama setahun dia belajar dan pentas ini. Usia mulai 3 tahun sampai anak kuliahan,” ujarnya.\r\n\r\nHani menjelaskan, pentas tahunan ini bertujuan untuk terus menghidupkan semangat generasi penerus agar mencintai dunia tari tradisional. Pihaknya tidak ingin anak-anak melupakan kebudayaan asli Indonesia.\r\n\r\n“Setiap tahun kita punya tema tersendiri. Seperti kemarin tari Nuswantoro, Bhineka Tunggal Ika. Untuk tahun ini Ramayana, terus untuk tahun besok ada lagi. Jadi setiap tahun ada tema-tema sendiri,” terangnya.\r\n\r\nIa menambahkan, selain mengasah skill menari, para anak didik juga diajarkan tata krama dan sopan santun khas adat ketimuran. Menurutnya, hal ini penting agar anak memiliki kepribadian yang baik.\r\n\r\n“Ini kita mengenalkan tari tradisi di daerah. Terutama tari Jawa agar tidak pudar. Tahun besok tari kreasi. Pondasi awal menang tari tradisional. Anak bisa menanam karakter, unggah-ungguh, sopan santun dengan tari,” pungkasnya.', 'uploads/posts/widyas-budaya.jpg', 1, 1, 'published', 5, '2026-01-19 16:03:47', '2026-01-23 13:55:07', NULL),
(8, 'Dialog Asa Jatmiko Dan Perjalanan FTP', 'dialog-asa-jatmiko-dan-perjalanan-ftp', 'Gagasan Festival Teater Pelajar (FTP) di Kudus tidak lahir dari ambisi membuat perhelatan besar, apalagi dari kalkulasi prestise kebudayaan.', 'Gagasan Festival Teater Pelajar (FTP) di Kudus tidak lahir dari ambisi membuat perhelatan besar, apalagi dari kalkulasi prestise kebudayaan. Ia tumbuh dari pengalaman lapangan yang sangat konkret: perjumpaan antara teater dan pelajar di ruang-ruang sekolah. Sekitar tahun 2005–2006, saya menggagas program pentas keliling SMA dan SMP di Kudus dengan satu pesan sederhana: berteater itu mudah dan menyenangkan.\r\n\r\nBersama Teater Djarum, kami memainkan naskah-naskah yang relatif ringan dan komunikatif, komedi yang dekat dengan keseharian remaja seperti Sepasang Mata Indah, Hanya Satu Kali, Senja dengan Dua Kelelawar, hingga Ketika Iblis Menikahi Seorang Perempuan.\r\n\r\nSetelah pentas, kami tidak berhenti di tepuk tangan. Kami duduk, berbincang, dan mendengar. Saat itu, hanya satu dua sekolah yang telah memiliki kelompok teater.\r\n\r\nBeberapa tahun kemudian, dampaknya mulai tampak. Sekolah-sekolah menunjukkan minat untuk membuka ekstrakurikuler teater. Pelajar membentuk kelompok secara mandiri. Teater pelajar bermunculan bukan karena instruksi kurikulum, melainkan karena kebutuhan berekspresi.\r\n\r\nDari situ muncul pertanyaan yang lebih mendasar:\r\n\r\nakan dikemanakan aktivitas ini? Apa muaranya?\r\n\r\nFTP lahir sebagai jawaban atas kegelisahan tersebut. Sejak awal, festival ini tidak dirancang semata sebagai ajang kompetisi, melainkan sebagai ruang temu, ruang belajar, berbagi, dan saling menguatkan antar teater pelajar.\r\n\r\nFTP pertama digelar pada tahun 2007 dengan segala keterbatasannya, seperti panggung tratak ala panggung mantenan, lantai gemlodak, namun dengan kesungguhan penuh.\r\n\r\nDari kesederhanaan itu, FTP kemudian berkembang menjadi agenda tahunan yang relatif mapan.\r\n\r\nDalam perjalanannya, saya sempat merumuskan jargon berteater itu keren. Pada masa itu, “keren” kerap dilekatkan pada gaya hidup kebarat-baratan, pada citra modern yang sering kali menjauh dari konteks lokal.\r\n\r\neater saya tempatkan sebagai ruang alternatif sebagai sikap atas zaman, bahwa pelajar yang berteater adalah pribadi yang memiliki nilai, daya juang, kepekaan sosial, serta tanggung jawab tanpa harus mengorbankan prestasi akademik.\r\n\r\nLambat laun dampak FTP ternyata tidak berhenti pada pelajar.\r\n\r\nDi Kudus, gairah berteater di kalangan seniman lokal ikut tumbuh. Banyak di antara mereka terlibat sebagai pelatih, pendamping, bahkan sutradara teater pelajar.\r\n\r\nProses kreatif kembali hidup. Kompetisi muncul, tetapi dalam arti yang sehat serta saling memacu kualitas, bukan saling menyingkirkan. Hubungan simbiosis pun terjalin antara seniman, sekolah, dan komunitas.\r\n\r\nMenariknya, dalam beberapa kelompok teater pelajar, peran orangtua mulai ikut terlibat. Dari sinilah saya semakin yakin bahwa ekosistem teater yang ideal bertumpu pada tiga pilar: sekolah, siswa, dan orangtua.\r\n\r\nKetika ketiganya berjalan bersama, anak-anak memiliki ruang yang lapang untuk tumbuh, bukan hanya sebagai aktor panggung, tetapi sebagai manusia yang utuh.\r\n\r\nSeiring perubahan zaman, jargon itu kembali saya perbarui. Kini saya lebih memilih mengatakan: berteater itu cerdas. Anak-anak hari ini, pada dasarnya, sudah “keren”.\r\n\r\nMereka menguasai teknologi informasi, media sosial, dan berbagai perangkat digital yang jauh melampaui generasi sebelumnya. Tantangannya bukan lagi soal akses atau gaya, melainkan soal kesadaran.\r\n\r\nDi tengah arus informasi yang cepat dan budaya instan, teater menawarkan sesuatu yang semakin langka: proses. Ia mengajarkan kesabaran, kehadiran, dan relasi antarmanusia.\r\n\r\nTeater melatih kecerdasan intelektual sekaligus emosional dan kemampuan berpikir kritis sekaligus berempati. Dalam pengertian ini, teater bukan sekadar seni pertunjukan, melainkan ruang latihan kewarasan.\r\n\r\nMaka FTP hari ini tidak hanya relevan sebagai festival, tetapi sebagai praktik kebudayaan. Ia menjadi ikhtiar kecil namun konsisten untuk mengingatkan bahwa manusia bukan sekadar pengguna teknologi, melainkan makhluk yang perlu terus belajar memahami diri dan sesamanya.\r\n\r\nBahwa kecerdasan bukan hanya soal cepat dan canggih, tetapi juga soal bijak dan berbudaya.\r\n\r\nDi titik ini, berteater menjadi lebih dari sekadar aktivitas ekstrakurikuler. Menjelma sebagai cara manusia berjumpa dengan dirinya: cerdas otaknya, cerdas emosinya, dan cerdas kemanusiaannya.\r\n\r\nBarangkali di sanalah teater menemukan tugas kulturalnya dalam pendidikan hari ini yang bukan hanya mengisi panggung, tetapi ikut merawat masa depan.', 'uploads/posts/696e6c04dce1a.jpg', 3, 1, 'published', 13, '2026-01-19 17:38:12', '2026-01-24 02:09:12', '2026-01-19 10:38:12'),
(9, 'Seni, Kebiasaan Berpikir dan Mengapa Kita Terlalu Mudah Kaget?', 'seni-kebiasaan-berpikir-dan-mengapa-kita-terlalu-mudah-kaget', 'BEBERAPA hari terakhir, dari panggung teater di Kudus dan Jepara, pameran kolektif di Rembang, Jepara dan Kudus hingga berderet festival kita bisa melihat sebuah pemandangan yang menarik: kebudayaan bergerak begitu subur, tetapi publiknya, bahkan kadang para pengelolanya, tampak terlalu mudah kaget. Perbedaan pendapat dianggap ancaman, kritik dibaca sebagai serangan pribadi, dan komentar tajam ditafsirkan sebagai permusuhan.', 'Oleh Imam Khanafi, Phos Collective, sedang melakukan program yang membuka kembali kesenian sebagai ruang gagasan bagi masa depan Kudus.\r\n\r\nBEBERAPA hari terakhir, dari panggung teater di Kudus dan Jepara, pameran kolektif di Rembang, Jepara dan Kudus hingga berderet festival kita bisa melihat sebuah pemandangan yang menarik: kebudayaan bergerak begitu subur, tetapi publiknya, bahkan kadang para pengelolanya, tampak terlalu mudah kaget. Perbedaan pendapat dianggap ancaman, kritik dibaca sebagai serangan pribadi, dan komentar tajam ditafsirkan sebagai permusuhan.\r\n\r\nPadahal di ruang lain percekcokan menjadi kegiatan pagi, pertengkaran justru dianggap olahraga pikiran. Kawan dekat bisa saling mencela perkara diksi, saling menohok perkara argumen, dan bangun esok hari tanpa dendam yang berarti. Di sana, debat bukan tragedi moral, melainkan cara merawat kewarasan intelektual.\r\n\r\nKerapuhan publik seni di Kudus, Jepara, dan Rembang hari ini lahir bukan dari ruang kosong; ia tumbuh dalam masyarakat yang feodal secara sosial, tetapi dipaksa tampil demokratis. Kita senang pada kata “harmonis” dan “guyub”, tetapi tidak pernah siap menerima gesekan sebagai bagian dari perkembangan gagasan. Seni ingin tampil rapi, padahal ia justru berkembang dari ketegangan.\r\n\r\nSosiolog Randall Collins menulis bahwa konflik adalah energi kebudayaan, ia mencegah stagnasi dan memaksa lahirnya ide baru. Tetapi di era “rezim engagement”, kegagetan berubah menjadi komoditas algoritmik. Reaksi emosional lebih dihargai daripada pemahaman. Akibatnya, pementasan yang keras gagasannya dipuji hanya karena “ramai”, bukan karena ia mengajak kita berpikir.\r\n\r\nFenomena yang muncul belakangan ini menunjukkan ironi: acara budaya berlangsung hampir setiap minggu, tetapi pejabat yang hadir sering tampak seperti tamu yang tidak benar-benar tahu apa yang sedang ditonton. Pameran dianggap seremonial, lokakarya dilihat sebagai pelengkap laporan, dan kritik terhadap minimnya dukungan justru dibaca sebagai ancaman terhadap instansi.\r\n\r\nPadahal di Kudus, Jepara, dan Rembang, pertumbuhan seni hari ini digerakkan oleh energi warga. Komunitas teater bekerja tanpa anggaran, perupa membayar ruang pamer dengan patungan, riset sejarah dilakukan secara mandiri. Kebudayaan berjalan, pemerintah menonton. Poster hadir, pemahaman absen. Dukungan administratif muncul, tetapi visi kebudayaan tak pernah benar-benar dibicarakan.\r\n\r\nDalam sejarah pemikiran, kritik bukanlah barang tabu. Schopenhauer menyebutnya the art of insult, seni memaksa lawan berpikir. Bahkan al-Ghazali, yang sering kita bayangkan lembut, memakai hujjah pedas sebagai alat untuk menyingkap kesalahan. Dialog panas bukan dianggap aib, melainkan metode untuk membersihkan nalar.\r\n\r\nKritik dalam bingkai ini, bukan perilaku primitif. Ia bagian dari mekanisme intelektual untuk menguji keteguhan gagasan: memperkerasnya sampai retak, lalu menemukan bentuk baru. Bahankan hari ini kritik dibaca sebagai kompetisi harga diri, bukan sebagai percakapan gagasan.\r\n\r\nKarena itu, yang kita butuhkan bukan publik seni yang manis, tetapi publik seni yang dewasa. Publik yang mampu merayakan pertengkaran, menjawab kritik dengan argumen, dan tidak mudah tersinggung oleh perbedaan. Sebab kedewasaan budaya lahir bukan dari kehalusan, melainkan dari keberanian menghadapi ketegangan tanpa jatuh pada sentimen personal.\r\n\r\nSeperti kata al-Ghazali, jalan menuju kebenaran sering kali tidak nyaman, dan justru yang perih itulah yang membersihkan. Maka pertanyaannya: apa arti pementasan setiap hari jika kritik dianggap ancaman? Untuk apa pameran bergantian jika gagasan tak boleh disentuh? Kebudayaan tidak tumbuh dari keramaian semata; ia tumbuh dari keberanian berpikir.\r\n\r\nSeni akan mandek jika publiknya rapuh, dan lebih mandek lagi jika pemerintahnya tuli. Yang diperlukan kini adalah keberanian untuk tidak mudah kaget. Sebab kebudayaan hanya dapat maju jika kita berani menatap cermin, meski yang terpampang kadang wajah yang tak ingin kita lihat. Semoga. (*)\r\n', 'uploads/posts/6973826fbee57.jpg', 2, 3, 'published', 26, '2026-01-23 14:15:11', '2026-01-30 04:55:21', '2026-01-23 07:15:11'),
(10, 'Seni sebagai Bahasa Jiwa Manusia', 'seni-sebagai-bahasa-jiwa-manusia', 'Artikel ini membahas seni sebagai bahasa jiwa manusia yang berperan penting dalam mengekspresikan perasaan, membentuk empati, dan merefleksikan realitas sosial. Seni dipandang sebagai cermin zaman sekaligus sarana pencarian makna bagi penciptanya. Pada akhirnya, seni mengajarkan kepekaan dan menghargai ketidaksempurnaan sebagai bagian dari kemanusiaan.', '<p><strong style=\"color: rgb(230, 0, 0);\">Seni</strong> adalah salah satu cara paling jujur manusia berbicara tentang dirinya sendiri. Ia hadir sebelum kata-kata tersusun rapi, sebelum aturan dibuat, dan sebelum logika menjadi alat utama berpikir. Dalam bentuk lukisan, musik, tari, sastra, hingga seni pertunjukan, manusia menuangkan rasa bahagia, marah, rindu, takut ke dalam medium yang mampu melampaui batas bahasa. Seni menjadi bahasa jiwa yang dapat dipahami lintas waktu dan budaya.</p><p>\r\nDalam kehidupan sehari-hari, seni sering kali dianggap sebagai pelengkap atau hiburan semata. Padahal, seni memiliki peran penting dalam membentuk cara manusia memandang dunia. Melalui seni, seseorang belajar merasakan empati, memahami perbedaan, dan melihat realitas dari sudut pandang yang tidak biasa. Sebuah karya seni mampu menggugah kesadaran sosial, mengkritik ketidakadilan, bahkan menjadi suara bagi mereka yang tak mampu bersuara.</p><p>\r\nSeni juga merupakan cermin zaman. Setiap periode sejarah meninggalkan jejaknya melalui karya seni yang lahir pada masanya. Gaya, tema, dan teknik yang digunakan seniman sering kali mencerminkan kondisi sosial, politik, dan budaya yang sedang terjadi. Oleh karena itu, mempelajari seni berarti juga mempelajari perjalanan peradaban manusia, lengkap dengan konflik, perubahan, dan harapan yang menyertainya.</p><p>\r\nBagi penciptanya, seni adalah proses pencarian makna. Seorang seniman tidak hanya menciptakan karya, tetapi juga berdialog dengan dirinya sendiri. Proses kreatif sering kali dipenuhi kegelisahan, percobaan, kegagalan, dan penemuan. Dari proses inilah lahir karya yang autentik karya yang tidak sekadar indah, tetapi memiliki kedalaman dan kejujuran emosional.</p><p>\r\nPada akhirnya, seni mengajarkan bahwa keindahan tidak selalu tentang kesempurnaan. Justru dari ketidaksempurnaan, luka, dan ketidakteraturan, seni menemukan kekuatannya. Ia mengingatkan manusia untuk tetap peka, berani berekspresi, dan menghargai keberagaman cara berpikir serta merasakan. Dalam dunia yang terus bergerak cepat, seni hadir sebagai ruang jeda tempat manusia kembali menyentuh sisi paling manusiawinya.\r\n</p>', 'uploads/posts/69742caa1e3d0.webp', 2, 3, 'published', 14, '2026-01-24 02:21:30', '2026-01-30 12:08:35', '2026-01-23 19:21:30');

-- --------------------------------------------------------

--
-- Table structure for table `post_tags`
--

CREATE TABLE `post_tags` (
  `post_id` int NOT NULL,
  `tag_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `post_tags`
--

INSERT INTO `post_tags` (`post_id`, `tag_id`) VALUES
(1, 2),
(1, 5),
(1, 8),
(1, 9),
(2, 1),
(2, 7),
(2, 8),
(3, 3),
(3, 4),
(3, 10),
(4, 2),
(4, 6),
(4, 9),
(5, 5),
(5, 8),
(5, 9),
(6, 1),
(6, 4),
(6, 10),
(9, 2),
(9, 11),
(9, 12),
(9, 13),
(9, 14),
(9, 15),
(9, 16),
(9, 17),
(9, 18),
(9, 19),
(10, 20),
(10, 21),
(10, 22),
(10, 23),
(10, 24),
(10, 25),
(10, 26),
(10, 27),
(10, 28),
(10, 29),
(10, 30),
(10, 31),
(10, 32),
(10, 33),
(10, 34);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `description` text,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `stock` int DEFAULT '0',
  `cover_image` varchar(255) DEFAULT NULL,
  `images` text,
  `whatsapp_number` varchar(20) DEFAULT '6283895189649',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `category_id`, `description`, `price`, `stock`, `cover_image`, `images`, `whatsapp_number`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Kaos Mentas.id Official – Black Edition', NULL, 8, 'Kaos hitam eksklusif dengan desain logo Mentas.id berwarna merah yang bold dan modern. Terbuat dari bahan katun yang nyaman dipakai sehari-hari, cocok untuk aktivitas santai maupun event komunitas. Desain simpel namun berkarakter, pas buat kamu yang ingin tampil percaya diri dan stylish.', 125000.00, 125, 'uploads/products/697c3486ea6a0.png', NULL, '6283895189649', 1, '2026-01-30 04:33:10', '2026-02-02 14:22:09'),
(2, 'Totebag Mentas.id – Black Canvas', NULL, 8, 'Totebag hitam dengan desain logo Mentas.id berwarna merah yang tegas dan modern. Terbuat dari bahan kanvas yang kuat dan tahan lama, cocok digunakan untuk aktivitas harian seperti kuliah, kerja, atau belanja. Desain minimalis namun tetap standout, pas untuk kamu yang suka gaya simpel dan fungsional.', 75000.00, 130, 'uploads/products/697c3511c574c.png', NULL, '6283895189649', 1, '2026-01-30 04:35:29', '2026-02-02 14:22:09'),
(3, 'Topi Mentas.id – Black Dad Cap', NULL, 8, 'Topi hitam model dad cap dengan bordir logo Mentas.id berwarna merah yang elegan. Dilengkapi strap adjustable di bagian belakang sehingga nyaman dipakai untuk berbagai ukuran kepala. Cocok untuk aktivitas santai, event, maupun daily outfit dengan gaya kasual minimalis.', 50000.00, 135, 'uploads/products/697c36fa11fa2.png', NULL, '6283895189649', 1, '2026-01-30 04:43:38', '2026-02-02 14:22:09'),
(4, 'Hoodie Mentas.id – Black Signature Hoodie', NULL, 8, 'Hoodie hitam premium dengan logo Mentas.id berwarna merah di bagian depan. Menggunakan bahan fleece yang tebal, lembut, dan hangat, nyaman dipakai untuk aktivitas harian maupun cuaca dingin. Desain simpel, modern, dan unisex, cocok untuk gaya streetwear maupun merchandise resmi.', 275000.00, 100, 'uploads/products/697c37152b253.png', NULL, '6283895189649', 1, '2026-01-30 04:44:05', '2026-02-02 14:22:09'),
(5, 'Kaos Mentas.id Official – White Edition', NULL, 8, 'Kaos putih eksklusif dengan desain logo Mentas.id berwarna merah yang bold dan modern. Terbuat dari bahan katun yang nyaman dipakai sehari-hari, cocok untuk aktivitas santai maupun event komunitas. Desain simpel namun berkarakter, pas buat kamu yang ingin tampil percaya diri dan stylish.', 125000.00, 125, 'uploads/products/697c378489702.png', NULL, '6283895189649', 1, '2026-01-30 04:45:56', '2026-02-02 14:22:09'),
(6, 'Seni, Cinta, dan Perasaan', NULL, 9, 'Buku Seni, Cinta, dan Perasaan menyajikan kumpulan tulisan reflektif tentang makna cinta, emosi, dan keindahan perasaan manusia yang dikemas secara puitis dan mendalam. Dibalut dengan ilustrasi lembut bernuansa hangat, buku ini cocok dibaca saat waktu santai, menjadi teman merenung, atau dijadikan hadiah penuh makna bagi orang tersayang.', 89000.00, 10, 'uploads/products/697c422f6aa5f.png', NULL, '6283895189649', 1, '2026-01-30 05:31:27', '2026-02-02 14:22:09'),
(7, 'Seni, Kebiasaan Berpikir dan Mengapa Kita Terlalu Mudah Kaget', NULL, 9, 'Buku ini mengajak pembaca memahami cara kerja pikiran manusia melalui sudut pandang seni dan kebiasaan berpikir sehari-hari. Dengan bahasa yang reflektif dan mudah dipahami, pembaca diajak menyelami alasan mengapa manusia sering bereaksi berlebihan, mudah terkejut, dan kurang siap menghadapi perubahan. Cocok bagi pembaca yang tertarik pada psikologi, filsafat ringan, dan pengembangan diri.', 95000.00, 15, 'uploads/products/697c42b37653d.png', NULL, '6283895189649', 1, '2026-01-30 05:33:39', '2026-02-02 14:22:09'),
(8, 'Seni sebagai Bahasa Jiwa Manusia', NULL, 9, 'Seni sebagai Bahasa Jiwa Manusia membahas seni sebagai medium universal untuk mengekspresikan emosi, nilai, dan pengalaman batin manusia. Buku ini menelusuri bagaimana seni menjadi sarana komunikasi jiwa yang melampaui kata-kata, menjadikannya relevan bagi penikmat seni, penulis, maupun siapa saja yang ingin memahami makna terdalam dari ekspresi manusia.', 92000.00, 12, 'uploads/products/697c42df9f447.png', NULL, '6283895189649', 1, '2026-01-30 05:34:23', '2026-02-02 14:22:09'),
(9, 'Totebag Mentas.id – White Canvas', NULL, 8, 'Totebag putih dengan desain logo Mentas.id berwarna merah yang tegas dan modern. Terbuat dari bahan kanvas yang kuat dan tahan lama, cocok digunakan untuk aktivitas harian seperti kuliah, kerja, atau belanja. Desain minimalis namun tetap standout, pas untuk kamu yang suka gaya simpel dan fungsional.', 75000.00, 105, 'uploads/products/697c43dd3c816.png', NULL, '6283895189649', 1, '2026-01-30 05:38:37', '2026-02-02 14:22:09'),
(10, 'Hoodie Mentas.id – White Signature Hoodie', NULL, 8, 'Hoodie putih premium dengan logo Mentas.id berwarna merah di bagian depan. Menggunakan bahan fleece yang tebal, lembut, dan hangat, nyaman dipakai untuk aktivitas harian maupun cuaca dingin. Desain simpel, modern, dan unisex, cocok untuk gaya streetwear maupun merchandise resmi.', 275000.00, 115, 'uploads/products/697c442fa03ae.png', NULL, '6283895189649', 1, '2026-01-30 05:39:59', '2026-02-02 14:22:09'),
(11, 'Topi Mentas.id – White Dad Cap', NULL, 8, 'Topi putih model dad cap dengan bordir logo Mentas.id berwarna merah yang elegan. Dilengkapi strap adjustable di bagian belakang sehingga nyaman dipakai untuk berbagai ukuran kepala. Cocok untuk aktivitas santai, event, maupun daily outfit dengan gaya kasual minimalis.', 50000.00, 75, 'uploads/products/697c44e8b0a74.png', NULL, '6283895189649', 1, '2026-01-30 05:43:04', '2026-02-02 14:22:09');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`, `slug`, `created_at`) VALUES
(1, 'Seni Rupa', 'seni-rupa', '2026-01-23 13:18:37'),
(2, 'Teater', 'teater', '2026-01-23 13:18:37'),
(3, 'Musik', 'musik', '2026-01-23 13:18:37'),
(4, 'Sastra', 'sastra', '2026-01-23 13:18:37'),
(5, 'Tari', 'tari', '2026-01-23 13:18:37'),
(6, 'Film', 'film', '2026-01-23 13:18:37'),
(7, 'Fotografi', 'fotografi', '2026-01-23 13:18:37'),
(8, 'Budaya Lokal', 'budaya-lokal', '2026-01-23 13:18:37'),
(9, 'Festival', 'festival', '2026-01-23 13:18:37'),
(10, 'Workshop', 'workshop', '2026-01-23 13:18:37'),
(11, 'Adat', 'adat', '2026-01-23 14:15:11'),
(12, 'Kebudayaan', 'kebudayaan', '2026-01-23 14:15:11'),
(13, 'Drama', 'drama', '2026-01-23 14:15:11'),
(14, 'Kudus', 'kudus', '2026-01-23 14:15:11'),
(15, 'Pati', 'pati', '2026-01-23 14:15:11'),
(16, 'Jepara', 'jepara', '2026-01-23 14:15:11'),
(17, 'Rembang', 'rembang', '2026-01-23 14:15:11'),
(18, 'Riset', 'riset', '2026-01-23 14:15:11'),
(19, 'Sejarah', 'sejarah', '2026-01-23 14:15:11'),
(20, 'seni', 'seni', '2026-01-24 02:21:30'),
(21, 'manusia', 'manusia', '2026-01-24 02:21:30'),
(22, 'karya', 'karya', '2026-01-24 02:21:30'),
(23, 'cara', 'cara', '2026-01-24 02:21:30'),
(24, 'sebelum', 'sebelum', '2026-01-24 02:21:30'),
(25, 'mampu', 'mampu', '2026-01-24 02:21:30'),
(26, 'sering', 'sering', '2026-01-24 02:21:30'),
(27, 'kali', 'kali', '2026-01-24 02:21:30'),
(28, 'proses', 'proses', '2026-01-24 02:21:30'),
(29, 'paling', 'paling', '2026-01-24 02:21:30'),
(30, 'dirinya', 'dirinya', '2026-01-24 02:21:30'),
(31, 'sendiri', 'sendiri', '2026-01-24 02:21:30'),
(32, 'hadir', 'hadir', '2026-01-24 02:21:30'),
(33, 'berpikir', 'berpikir', '2026-01-24 02:21:30'),
(34, 'bahasa', 'bahasa', '2026-01-24 02:21:30');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int NOT NULL,
  `ticket_code` varchar(50) NOT NULL,
  `event_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `quantity` int DEFAULT '1',
  `total_price` decimal(12,2) DEFAULT '0.00',
  `payment_proof` varchar(255) DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled','checked_in') DEFAULT 'pending',
  `notes` text,
  `checked_in_at` timestamp NULL DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `ticket_code`, `event_id`, `name`, `email`, `phone`, `quantity`, `total_price`, `payment_proof`, `status`, `notes`, `checked_in_at`, `confirmed_at`, `created_at`, `updated_at`) VALUES
(1, 'PENTAS-461563', 1, 'Daffa Fairuz', 'daffafairuz57@gmail.com', '0873698268', 1, 0.00, NULL, 'confirmed', NULL, NULL, NULL, '2026-01-30 11:09:56', '2026-01-30 11:09:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `social_media` json DEFAULT NULL,
  `qris_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','contributor') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'contributor',
  `status` enum('pending','active','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `bio`, `avatar`, `address`, `social_media`, `qris_image`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin Mentas', 'admin@mentas.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, NULL, NULL, 'admin', 'active', '2026-01-18 15:11:13', '2026-01-18 15:11:13'),
(2, 'Daffa Admin', 'daffa@mentas.id', '$2y$10$iCgumZ0BHVutZRXVW99xZeP1LDf1O19aJpOW44p46RhcZwEsDwy96', '08123456789', 'Super admin', NULL, 'Kudus', '[]', NULL, 'contributor', 'active', '2026-01-19 17:11:39', '2026-01-20 10:35:50'),
(3, 'Alwi Sutanto', 'alwi@mentas.id', '$2y$10$6oXrWXjmm4EyDCka9UG40OpE7LI0hPIGuO3fETtFPOB5sGLzjMNXC', '083895189649', 'Saya adalah seorang penulis yang hidup dari imajinasi, sebuah dunia batin yang tak pernah benar-benar diam. Fantasi bagi saya bukan sekadar pelarian, melainkan cara memahami realitas dengan sudut pandang yang lebih luas dan berlapis. Dari hal-hal kecil yang sering luput dari perhatian, saya merangkai kisah tentang dunia alternatif, tokoh-tokoh dengan luka dan harapan, serta konflik yang kerap mencerminkan pergulatan manusia itu sendiri. Menulis menjadi ruang kebebasan, tempat ide-ide liar bertemu dengan refleksi mendalam, lalu menjelma menjadi cerita yang tidak hanya menghibur, tetapi juga mengajak pembaca berpikir dan merasakan.\r\n\r\nDalam setiap karya, saya berusaha menghadirkan imajinasi yang hidup dan bernapas fantasi yang tidak berdiri sendiri, melainkan berpijak pada emosi, nilai, dan pengalaman manusia. Saya percaya bahwa cerita memiliki kekuatan untuk menjembatani kenyataan dan angan-angan, membuka pintu menuju empati, serta menyalakan keberanian untuk bermimpi lebih jauh. Melalui tulisan, saya terus menantang diri untuk mengeksplorasi batas kreativitas, memperkaya bahasa, dan menyampaikan makna, dengan harapan setiap kisah yang lahir dapat meninggalkan jejak di benak pembaca, bahkan setelah halaman terakhir ditutup.', 'uploads/avatars/params_3_6973a0548e1a2.webp', 'Jalan Kudus Raya, Kec. Baru, Kab. Lama', '{\"twitter\": \"https://x.com/?lang=en-id\", \"website\": \"https://www.google.com/\", \"facebook\": \"https://www.facebook.com/\", \"instagram\": \"https://www.instagram.com/jayidzes/\"}', 'uploads/qris/qris_3_697c15a96b9f7.jpg', 'contributor', 'active', '2026-01-20 10:39:30', '2026-01-30 02:21:29'),
(4, 'Test Contributor', 'test@mentas.id', '$2y$10$ZBXXY8bTpjJwJ2Eaql19bO3AYnPXS.sdtWtNs5qQYj7xlV5Rdi6bi', '08123456789', 'I am a test contributor.', NULL, 'Jakarta', '[]', NULL, 'contributor', 'pending', '2026-01-23 15:53:19', '2026-01-23 15:53:19'),
(5, 'Test Contributor', 'test@example.com', '$2y$10$0PsO6WmAgiGYmFAXVmvO3./Ic/eLYmEIwCn6ncZExtKAkHLvSdiyy', '08123456789', '', NULL, '', '[]', NULL, 'contributor', 'pending', '2026-01-23 16:14:39', '2026-01-23 16:14:39'),
(6, 'Budi Santoso', 'budi.santoso1@example.com', '$2y$10$jK23FzdNb6u60rI2jiKY3eayf3aJotTDy0SSucyEz/29HF8YJPrWm', '08123456780', 'Contributor bio for Budi Santoso. Passionate about arts and culture.', NULL, 'Jakarta, Indonesia', '{\"website\": \"https://example.com/budisantoso\", \"facebook\": \"https://facebook.com/budisantoso\"}', NULL, 'contributor', 'active', '2026-01-24 03:05:27', '2026-01-24 03:05:27'),
(7, 'Siti Aminah', 'siti.aminah2@example.com', '$2y$10$p5pD05XTXwgd47OkgL71MuG.KYTKbihfbgNj/QAYb.CJe4mNL5gUy', '08123456781', 'Contributor bio for Siti Aminah. Passionate about arts and culture.', NULL, 'Jakarta, Indonesia', '{\"facebook\": \"https://facebook.com/sitiaminah\"}', NULL, 'contributor', 'active', '2026-01-24 03:05:27', '2026-01-24 03:05:27'),
(8, 'Rizky Pratama', 'rizky.pratama3@example.com', '$2y$10$FhwfhNLtIe8wyZHIwhAr..SmozmstUZab8.GtSjlv8/U9xc/UN2YK', '08123456782', 'Contributor bio for Rizky Pratama. Passionate about arts and culture.', NULL, 'Jakarta, Indonesia', '{\"website\": \"https://example.com/rizkypratama\"}', NULL, 'contributor', 'active', '2026-01-24 03:05:27', '2026-01-24 03:05:27'),
(9, 'Dewi Lestari', 'dewi.lestari4@example.com', '$2y$10$Hskfb10neDE87DEa7HHgwOVtHZ9vomPjSlJ9PY4Bd0WqFM5Kk0HjG', '08123456783', 'Contributor bio for Dewi Lestari. Passionate about arts and culture.', NULL, 'Jakarta, Indonesia', '{\"twitter\": \"https://twitter.com/dewilestari\", \"website\": \"https://example.com/dewilestari\", \"instagram\": \"https://instagram.com/dewilestari\"}', NULL, 'contributor', 'active', '2026-01-24 03:05:27', '2026-01-24 03:05:27'),
(10, 'Agus Wijaya', 'agus.wijaya5@example.com', '$2y$10$FWR72h0TolwVMxrQYt1zZup7LEcsOEvuW2du6M9agQQUxPmHWm8ia', '08123456784', 'Contributor bio for Agus Wijaya. Passionate about arts and culture.', NULL, 'Jakarta, Indonesia', '{\"website\": \"https://example.com/aguswijaya\", \"facebook\": \"https://facebook.com/aguswijaya\"}', NULL, 'contributor', 'active', '2026-01-24 03:05:27', '2026-01-24 03:05:27'),
(11, 'Rina Kurnia', 'rina.kurnia6@example.com', '$2y$10$5ibEM5yyz1N7bWmrmBLxd.1VTTmvJiSvymsxNlbfeG7P6CjjHnWcC', '08123456785', 'Contributor bio for Rina Kurnia. Passionate about arts and culture.', NULL, 'Jakarta, Indonesia', '{\"website\": \"https://example.com/rinakurnia\", \"facebook\": \"https://facebook.com/rinakurnia\", \"instagram\": \"https://instagram.com/rinakurnia\"}', NULL, 'contributor', 'active', '2026-01-24 03:05:28', '2026-01-24 03:05:28'),
(12, 'Eko Prasetyo', 'eko.prasetyo7@example.com', '$2y$10$IgVfJqHNoATMG/cEl0z3gOnLCg2zETpzDJyKWo0mM1WeDiSDZ51j2', '08123456786', 'Contributor bio for Eko Prasetyo. Passionate about arts and culture.', NULL, 'Jakarta, Indonesia', '{\"twitter\": \"https://twitter.com/ekoprasetyo\", \"website\": \"https://example.com/ekoprasetyo\", \"facebook\": \"https://facebook.com/ekoprasetyo\"}', NULL, 'contributor', 'active', '2026-01-24 03:05:28', '2026-01-24 03:05:28'),
(13, 'Maya Putri', 'maya.putri8@example.com', '$2y$10$AP5iFSr8AsUJbrX1HsTKUezL8/doIn1LWtqcdGBoS5B9Tyy9VDvXy', '08123456787', 'Contributor bio for Maya Putri. Passionate about arts and culture.', NULL, 'Jakarta, Indonesia', '{\"twitter\": \"https://twitter.com/mayaputri\", \"website\": \"https://example.com/mayaputri\", \"instagram\": \"https://instagram.com/mayaputri\"}', NULL, 'contributor', 'active', '2026-01-24 03:05:28', '2026-01-24 03:05:28'),
(14, 'Doni Saputra', 'doni.saputra9@example.com', '$2y$10$MxQXQ1B.PmmcbFhi3eYB1eSLr9usFekVQGbc84cArr4gjIsMY/SA6', '08123456788', 'Contributor bio for Doni Saputra. Passionate about arts and culture.', NULL, 'Jakarta, Indonesia', '{\"website\": \"https://example.com/donisaputra\", \"facebook\": \"https://facebook.com/donisaputra\", \"instagram\": \"https://instagram.com/donisaputra\"}', NULL, 'contributor', 'active', '2026-01-24 03:05:28', '2026-01-24 03:05:28'),
(15, 'Nina Herlina', 'nina.herlina10@example.com', '$2y$10$YgEUkOKpuTVju65FHMi.KukU6gMu0ikhv03RxYOh1KPT1wJ/qqlPe', '08123456789', 'Contributor bio for Nina Herlina. Passionate about arts and culture.', NULL, 'Jakarta, Indonesia', '{\"instagram\": \"https://instagram.com/ninaherlina\"}', NULL, 'contributor', 'active', '2026-01-24 03:05:28', '2026-01-24 03:05:28');

-- --------------------------------------------------------

--
-- Table structure for table `zines`
--

CREATE TABLE `zines` (
  `id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cover_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `category` enum('esai','prosa','puisi','cerpen','rupa','zine') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'esai',
  `pdf_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `author_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zines`
--

INSERT INTO `zines` (`id`, `title`, `slug`, `content`, `cover_image`, `category_id`, `category`, `pdf_file`, `excerpt`, `is_active`, `created_at`, `updated_at`, `author_id`) VALUES
(3, 'Lunturnya Nilai-nilai Kebudayaan Indonesia di Era Globalisasi', 'lunturnya-nilai-nilai-kebudayaan-indonesia-di-era-globalisasi', NULL, 'uploads/zines/697beccc3bf25.jpg', 10, 'esai', 'uploads/zines/pdf/697beccc3d3c5.pdf', 'Globalisasi merupakan suatu fenomena khusus dalam peradaban manusia yang bergerak secara terus-menerus ke dalam masyarakat global dan merupakan bagian dari proses kehidupan manusia', 1, '2026-01-29 23:27:08', '2026-02-02 14:22:09', NULL),
(4, 'Dampak Internet Terhadap Kebudayaan Lokal', 'dampak-internet-terhadap-kebudayaan-lokal', NULL, 'uploads/zines/697beea75e1fb.webp', 10, 'esai', 'uploads/zines/pdf/697beea75e7ee.pdf', 'Indonesia sebagai negara yang kaya suku dan budaya tak dapat terhindar dari dampak globalisasi, hal ini salah satunya dapat dilihat dari pergeseran moral pada budaya berpakaian.', 1, '2026-01-29 23:35:03', '2026-02-02 14:22:09', NULL),
(5, 'Sajak Chairil Anwar', 'sajak-chairil-anwar', NULL, 'uploads/zines/697bf4ddd34e6.webp', 12, 'puisi', 'uploads/zines/pdf/697bf4ddd395f.pdf', 'Kumpulan Sajak karya Chairil Anwar', 1, '2026-01-30 00:01:33', '2026-02-02 14:22:09', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_categories_slug` (`slug`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `communities`
--
ALTER TABLE `communities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_events_is_active` (`is_active`),
  ADD KEY `idx_events_event_date` (`event_date`),
  ADD KEY `idx_events_slug` (`slug`);

--
-- Indexes for table `payment_settings`
--
ALTER TABLE `payment_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_posts_category` (`category_id`),
  ADD KEY `idx_posts_author` (`author_id`),
  ADD KEY `idx_posts_status` (`status`),
  ADD KEY `idx_posts_slug` (`slug`);

--
-- Indexes for table `post_tags`
--
ALTER TABLE `post_tags`
  ADD PRIMARY KEY (`post_id`,`tag_id`),
  ADD KEY `idx_post_tags_post` (`post_id`),
  ADD KEY `idx_post_tags_tag` (`tag_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_products_is_active` (`is_active`),
  ADD KEY `idx_products_slug` (`slug`),
  ADD KEY `fk_products_category` (`category_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_tags_slug` (`slug`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ticket_code` (`ticket_code`),
  ADD KEY `idx_tickets_event_id` (`event_id`),
  ADD KEY `idx_tickets_status` (`status`),
  ADD KEY `idx_tickets_ticket_code` (`ticket_code`),
  ADD KEY `idx_tickets_email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_status` (`status`),
  ADD KEY `idx_users_qris` (`qris_image`);

--
-- Indexes for table `zines`
--
ALTER TABLE `zines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_zines_category` (`category`),
  ADD KEY `fk_zines_category` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `communities`
--
ALTER TABLE `communities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payment_settings`
--
ALTER TABLE `payment_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `zines`
--
ALTER TABLE `zines`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `post_tags`
--
ALTER TABLE `post_tags`
  ADD CONSTRAINT `post_tags_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `zines`
--
ALTER TABLE `zines`
  ADD CONSTRAINT `fk_zines_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
