-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 19, 2026 at 02:44 PM
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
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Berita', 'berita', 'Berita terkini seputar seni dan budaya', 1, 1, '2026-01-18 15:11:13', '2026-01-18 15:11:13'),
(2, 'Esai', 'esai', 'Esai dan opini tentang kesenian', 1, 2, '2026-01-18 15:11:13', '2026-01-18 15:11:13'),
(3, 'Komunitas', 'komunitas', 'Cerita dan kegiatan komunitas seni', 1, 3, '2026-01-18 15:11:13', '2026-01-18 15:11:13'),
(4, 'Tradisi', 'tradisi', 'Warisan tradisi dan budaya nusantara', 1, 4, '2026-01-18 15:11:13', '2026-01-18 15:11:13'),
(5, 'Ekosistem', 'ekosistem', 'Ekosistem seni dan budaya Indonesia', 1, 5, '2026-01-18 15:11:13', '2026-01-18 15:11:13');

-- --------------------------------------------------------

--
-- Table structure for table `communities`
--

CREATE TABLE `communities` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `body` longtext COLLATE utf8mb4_unicode_ci,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `author_id` int DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `views` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `published_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `slug`, `excerpt`, `body`, `cover_image`, `category_id`, `author_id`, `status`, `views`, `created_at`, `updated_at`, `published_at`) VALUES
(1, 'Wayang Klithik Wonosoco, Ki Sutikno, Dalang Terakhir dan Beban Ingatan', 'wayang-klithik-wonosoco-ki-sutikno-dalang-terakhir-dan-beban-ingatan', 'ADA saat-saat ketika kebudayaan tidak hadir sebagai perayaan yang riuh, melainkan sebagai bisikan halus yang meminta untuk didengarkan dengan lebih pelan.', 'Oleh Imam Khanafi, penulis esai, tinggal di Kudus\r\n\r\nADA saat-saat ketika kebudayaan tidak hadir sebagai perayaan yang riuh, melainkan sebagai bisikan halus yang meminta untuk didengarkan dengan lebih pelan. Ia hidup dalam jeda, dalam ingatan yang hampir terlewat, dan dalam kesediaan manusia untuk menoleh ke belakang tanpa rasa nostalgia berlebihan. Dari sanalah kesenian tradisi menemukan maknanya yang paling dalam: bukan sekadar warisan masa lalu, tetapi cermin yang mengajukan pertanyaan sunyi kepada kita hari ini, apakah kita masih mau merawat yang rapuh, yang sederhana, dan yang nyaris dilupakan, agar ia tetap bernapas di tengah perubahan zaman.\r\n\r\nDi Desa Wonosoco, Kecamatan Undaan, Kabupaten Kudus, kesenian tidak pernah berdiri sendiri. Ia lahir dari tanah, air, dan doa-doa yang berulang dalam waktu panjang. Wayang Klithik Wonosoco merupakan wayang kayu pipih yang digerakkan dengan tangan dalang bukan sekadar bentuk seni pertunjukan, melainkan bagian dari kosmologi desa. Ia hidup bersama ritual bersih sendang, bersama kisah tentang mata air yang dijaga, dan bersama ingatan kolektif tentang leluhur.\r\n\r\nCerita lisan desa menyebutkan bahwa Wayang Klithik lahir bersamaan dengan pendirian Wonosoco. Dalam kisah itu, Pangeran Kajoran dari Mataram dan Ki Saji bersemedi setelah peperangan. Dari semedi itu, dua mata air yaitu Sendang Dewot dan Sendang Gading yang muncul sebagai penanda kehidupan baru. Air bukan hanya sumber penghidupan, tetapi juga pusat spiritualitas. Di sekitar air itulah, Wayang Klithik tumbuh sebagai medium doa, pengingat, dan penuntun moral.\r\n\r\nPerkiraan sejarah menempatkan Wayang Klithik Wonosoco telah ada sejak abad ke-13, sebuah masa peralihan penting di Jawa ketika Islam mulai menyebar dan bernegosiasi dengan tradisi pra-Islam. Wayang Klithik menjadi ruang temu: antara dakwah dan ritus lama, antara kisah-kisah babad Jawa dan nilai-nilai spiritual yang diwariskan turun-temurun.\r\n\r\nHari ini, Wayang Klithik Wonosoco berada di titik kritis. Dari generasi ke generasi, tradisi ini diwariskan melalui garis dalang. Namun kini, hanya tersisa satu dalang aktif: Ki Sutikno, generasi kedelapan dalang Wayang Klithik Wonosoco.\r\n\r\nLebih dari 30 tahun Ki Sutikno mendalang, belajar langsung dari ayahnya, dan setia membawakan lakon-lakon klasik babad Tanah Jawa. Ia bukan sekadar seniman, tetapi penjaga ingatan. Setiap gerak wayang, setiap dialog, menyimpan pengetahuan yang tidak tertulis di buku.\r\n\r\nDi Wonosoco, wayang kayu itu masih digerakkan. Suaranya mungkin semakin lirih, tetapi selama ia masih dipentaskan, ingatan desa belum sepenuhnya hilang. Wayang Klithik mengingatkan kita bahwa sejarah tidak selalu ditulis di buku, kadang ia hidup di panggung kecil, di tangan seorang dalang, menunggu untuk terus didengarkan.', NULL, 4, 1, 'published', 130, '2026-01-09 21:14:00', '2026-01-18 23:38:40', '2026-01-09 21:14:00'),
(2, 'Ruang Tamu yang Tidak Pernah Kosong', 'ruang-tamu-yang-tidak-pernah-kosong', 'Argumentasi Sisi Rekonstruksi dan Drama Trauma Domestik dalam Pementasan Teater Djavu SMK Taman Siswa dalam final Festival Teater Pelajar.', 'Oleh Imam Khanafi, esais, penonton teater\r\n\r\nRuang tamu kerap dipahami sebagai ruang paling netral dalam rumah. Ia bukan kamar tidur yang intim, bukan dapur yang fungsional, bukan halaman yang terbuka. Ruang tamu adalah tempat menerima: tamu, kabar, gosip, konflik, dan sering kali kepura-puraan.\r\n\r\nDalam pementasan Argumentasi Sisi Rekonstruksi karya Diky Soemarno oleh Teater Djavu, ruang tamu tidak lagi menjadi latar pasif, melainkan pusat gravitasi dramatik. Seluruh konflik keluarga patriarkal yang paradoksnya kini berwujud matriarkal dipadatkan ke dalam satu ruang yang seolah tak pernah bernapas lega.\r\n\r\nPementasan yang dilakukan dengan sting panggung di ruang tamu, dengan jam kursi, perabot kuno, dan dialog yang berlangsung di sana, menegaskan satu hal penting: tragedi keluarga tidak pernah benar-benar pergi. Ia hanya berpindah jam, berpindah kursi, berpindah posisi duduk.\r\n\r\nDalam konteks ini, ruang tamu menjadi metafora dari kehidupan keluarga Indonesia pasca-trauma domestik: terlihat rapi di permukaan, namun menyimpan endapan luka yang tak selesai.\r\n\r\nIbu Sastro adalah pusat kontrol ruang tamu itu. Di usia 50-an, ia hadir sebagai figur dominan yang mengatur ritme, jam, dan batasan. Ia bukan sekadar ibu, melainkan penjaga moral, penentu aturan, dan pengingat masa lalu yang tak boleh dilupakan namun juga tak boleh dibicarakan secara jujur.\r\n\r\nJudul Argumentasi Sisi Rekonstruksi menemukan maknanya di sini. Argumentasi bukan sekadar debat verbal, melainkan cara bertahan hidup. Setiap karakter berargumentasi untuk membenarkan posisinya: ibu dengan kontrol, Lusi dengan kepatuhan, Yoni dengan pemberontakan, Bi Iyah dengan keheningan.\r\n\r\nPementasan Teater Djavu di ruang tamu menghapus jarak antara panggung dan kehidupan. Penonton tidak lagi aman sebagai pengamat. Mereka duduk di ruang yang sama, menghirup udara yang sama, dan mungkin mengingat ruang tamu mereka sendiri.\r\n\r\nDan di situlah kekuatan naskah ini bekerja: membuat kita bertanya, trauma apa yang sedang kita rawat diam-diam di ruang tamu rumah kita?', NULL, 2, 1, 'published', 91, '2025-12-25 00:23:00', '2026-01-19 14:37:15', '2025-12-25 00:23:00'),
(3, 'Sanggar Widyas Budaya Gelar Fragmen Ramayana dalam Pentas Tahunan ke-18', 'sanggar-widyas-budaya-gelar-fragmen-ramayana', 'Sanggar Widyas Budaya kembali menggelar pentas tahunan dengan membawakan fragmen Ramayana yang memukau.', 'Sanggar Widyas Budaya kembali menggelar pentas tahunan ke-18 mereka dengan membawakan fragmen Ramayana yang memukau para penonton.\r\n\r\nAcara yang digelar di Gedung Kesenian ini menjadi bukti konsistensi sanggar dalam melestarikan seni tari tradisional Jawa. Puluhan penari dari berbagai usia tampil apik membawakan kisah epik Ramayana.\r\n\r\nKetua Sanggar, Ibu Widyastuti, menyampaikan bahwa pentas tahunan ini merupakan ajang untuk menunjukkan hasil latihan selama setahun penuh. \"Kami berharap generasi muda tetap mencintai seni tradisi kita,\" ujarnya.\r\n\r\nPentas tahun ini menampilkan babak Shinta Obong yang menjadi klimaks dari kisah Ramayana. Para penari senior membawakan karakter utama dengan sangat memukau.', NULL, 1, 1, 'published', 45, '2025-12-29 12:36:00', '2026-01-18 23:16:33', '2025-12-29 12:36:00'),
(4, 'Komunitas Sastra Kudus Gelar Diskusi Bulanan', 'komunitas-sastra-kudus-diskusi-bulanan', 'Komunitas Sastra Kudus mengadakan diskusi bulanan membahas perkembangan sastra kontemporer Indonesia.', 'Komunitas Sastra Kudus menggelar diskusi bulanan mereka dengan tema \"Sastra Kontemporer Indonesia: Tantangan dan Peluang\".\r\n\r\nDiskusi yang berlangsung di Cafe Literasi ini dihadiri oleh puluhan pegiat sastra dari berbagai latar belakang. Narasumber utama, Pak Bambang Suryanto, membahas tentang bagaimana sastra Indonesia bisa tetap relevan di era digital.\r\n\r\n\"Kita perlu memanfaatkan platform digital untuk menyebarkan karya sastra, namun tetap menjaga kualitas dan kedalaman karya,\" ujar Bambang.\r\n\r\nPeserta juga berdiskusi tentang tantangan publikasi buku di era digital dan bagaimana komunitas bisa berperan dalam ekosistem literasi.', NULL, 3, 1, 'published', 35, '2026-01-05 07:00:00', '2026-01-19 14:36:33', '2026-01-05 07:00:00'),
(5, 'Ekosistem Seni Budaya Jawa Tengah: Peta dan Potensi', 'ekosistem-seni-budaya-jawa-tengah', 'Memetakan ekosistem seni budaya di Jawa Tengah dan potensi pengembangannya.', 'Jawa Tengah memiliki ekosistem seni budaya yang sangat kaya dan beragam. Dari wayang kulit hingga batik, dari gamelan hingga tari tradisional, semuanya membentuk identitas budaya yang unik.\r\n\r\nBerdasarkan pemetaan yang dilakukan tim Mentas.id, terdapat lebih dari 500 komunitas seni aktif di seluruh Jawa Tengah. Komunitas-komunitas ini tersebar dari Semarang hingga Solo, dari Kudus hingga Kebumen.\r\n\r\nYang menarik, banyak komunitas yang mulai menggabungkan tradisi dengan inovasi. Misalnya, pertunjukan wayang kontemporer yang menggabungkan teknologi mapping dengan lakon klasik.\r\n\r\nTantangan terbesar adalah regenerasi. Banyak maestro seni yang sudah berusia lanjut namun belum memiliki penerus yang memadai. Ini menjadi PR besar bagi kita semua.', NULL, 5, 1, 'published', 69, '2026-01-12 03:00:00', '2026-01-19 14:37:08', '2026-01-12 03:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `social_media` json DEFAULT NULL,
  `role` enum('admin','contributor') COLLATE utf8mb4_unicode_ci DEFAULT 'contributor',
  `status` enum('pending','active','rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `bio`, `avatar`, `address`, `social_media`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin Mentas', 'admin@mentas.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, NULL, NULL, NULL, 'admin', 'active', '2026-01-18 15:11:13', '2026-01-18 15:11:13');

-- --------------------------------------------------------

--
-- Table structure for table `zines`
--

CREATE TABLE `zines` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zines`
--

INSERT INTO `zines` (`id`, `title`, `slug`, `content`, `cover_image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Zine Sastra Vol. 1: Puisi Tanah Air', 'zine-sastra-vol-1-puisi-tanah-air', 'Kumpulan puisi dari berbagai penyair Indonesia yang mengangkat tema tanah air dan kebangsaan.\r\n\r\n---\r\n\r\nTANAH AIRKU\r\noleh Penyair Muda\r\n\r\nTanah airku, tanah tumpah darahku\r\nDi sini aku berdiri, di sini aku bermimpi\r\nLaut biru membentang, gunung menjulang\r\nSemua adalah rumahku\r\n\r\n---\r\n\r\nNUSANTARA\r\noleh Sastrawan Kudus\r\n\r\nDari Sabang sampai Merauke\r\nKita adalah satu\r\nBerbeda namun bersatu\r\nItulah Indonesia\r\n\r\n---\r\n\r\nZine ini diterbitkan sebagai bentuk apresiasi terhadap karya-karya sastra Indonesia.', NULL, 1, '2025-11-30 17:00:00', '2026-01-18 23:16:33'),
(2, 'Zine Sastra Vol. 2: Cerita Pendek Pilihan', 'zine-sastra-vol-2-cerita-pendek-pilihan', 'Kumpulan cerpen terbaik dari kontributor Mentas.id.\r\n\r\n---\r\n\r\nHUJAN DI BULAN DESEMBER\r\noleh Kontributor Anonim\r\n\r\nHujan turun deras di penghujung tahun. Aku duduk di teras rumah nenek, menyeruput kopi yang sudah dingin. Ingatan membawaku kembali ke masa kecil, ketika hujan adalah teman bermain, bukan musuh yang harus dihindari.\r\n\r\nNenek selalu bilang, hujan adalah berkah. Tapi hari ini, hujan terasa seperti tangisan langit yang tak berujung...\r\n\r\n---\r\n\r\nZine ini memuat karya-karya terpilih dari open submission.', NULL, 1, '2025-12-31 17:00:00', '2026-01-18 23:16:33');

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
-- Indexes for table `communities`
--
ALTER TABLE `communities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

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
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_status` (`status`);

--
-- Indexes for table `zines`
--
ALTER TABLE `zines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `communities`
--
ALTER TABLE `communities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `zines`
--
ALTER TABLE `zines`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
