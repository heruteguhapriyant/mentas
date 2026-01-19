-- ===================================
-- SAMPLE DATA FROM MENTAS.ID
-- Run this AFTER schema.sql
-- ===================================

-- Sample Posts (from mentas.id)
INSERT INTO posts (title, slug, excerpt, body, category_id, author_id, status, views, published_at, created_at) VALUES

-- POST 1: Wayang Klithik (Category: Tradisi id=4)
(
    'Wayang Klithik Wonosoco, Ki Sutikno, Dalang Terakhir dan Beban Ingatan',
    'wayang-klithik-wonosoco-ki-sutikno-dalang-terakhir-dan-beban-ingatan',
    'ADA saat-saat ketika kebudayaan tidak hadir sebagai perayaan yang riuh, melainkan sebagai bisikan halus yang meminta untuk didengarkan dengan lebih pelan.',
    'Oleh Imam Khanafi, penulis esai, tinggal di Kudus

ADA saat-saat ketika kebudayaan tidak hadir sebagai perayaan yang riuh, melainkan sebagai bisikan halus yang meminta untuk didengarkan dengan lebih pelan. Ia hidup dalam jeda, dalam ingatan yang hampir terlewat, dan dalam kesediaan manusia untuk menoleh ke belakang tanpa rasa nostalgia berlebihan. Dari sanalah kesenian tradisi menemukan maknanya yang paling dalam: bukan sekadar warisan masa lalu, tetapi cermin yang mengajukan pertanyaan sunyi kepada kita hari ini, apakah kita masih mau merawat yang rapuh, yang sederhana, dan yang nyaris dilupakan, agar ia tetap bernapas di tengah perubahan zaman.

Di Desa Wonosoco, Kecamatan Undaan, Kabupaten Kudus, kesenian tidak pernah berdiri sendiri. Ia lahir dari tanah, air, dan doa-doa yang berulang dalam waktu panjang. Wayang Klithik Wonosoco merupakan wayang kayu pipih yang digerakkan dengan tangan dalang bukan sekadar bentuk seni pertunjukan, melainkan bagian dari kosmologi desa. Ia hidup bersama ritual bersih sendang, bersama kisah tentang mata air yang dijaga, dan bersama ingatan kolektif tentang leluhur.

Cerita lisan desa menyebutkan bahwa Wayang Klithik lahir bersamaan dengan pendirian Wonosoco. Dalam kisah itu, Pangeran Kajoran dari Mataram dan Ki Saji bersemedi setelah peperangan. Dari semedi itu, dua mata air yaitu Sendang Dewot dan Sendang Gading yang muncul sebagai penanda kehidupan baru. Air bukan hanya sumber penghidupan, tetapi juga pusat spiritualitas. Di sekitar air itulah, Wayang Klithik tumbuh sebagai medium doa, pengingat, dan penuntun moral.

Perkiraan sejarah menempatkan Wayang Klithik Wonosoco telah ada sejak abad ke-13, sebuah masa peralihan penting di Jawa ketika Islam mulai menyebar dan bernegosiasi dengan tradisi pra-Islam. Wayang Klithik menjadi ruang temu: antara dakwah dan ritus lama, antara kisah-kisah babad Jawa dan nilai-nilai spiritual yang diwariskan turun-temurun.

Hari ini, Wayang Klithik Wonosoco berada di titik kritis. Dari generasi ke generasi, tradisi ini diwariskan melalui garis dalang. Namun kini, hanya tersisa satu dalang aktif: Ki Sutikno, generasi kedelapan dalang Wayang Klithik Wonosoco.

Lebih dari 30 tahun Ki Sutikno mendalang, belajar langsung dari ayahnya, dan setia membawakan lakon-lakon klasik babad Tanah Jawa. Ia bukan sekadar seniman, tetapi penjaga ingatan. Setiap gerak wayang, setiap dialog, menyimpan pengetahuan yang tidak tertulis di buku.

Di Wonosoco, wayang kayu itu masih digerakkan. Suaranya mungkin semakin lirih, tetapi selama ia masih dipentaskan, ingatan desa belum sepenuhnya hilang. Wayang Klithik mengingatkan kita bahwa sejarah tidak selalu ditulis di buku, kadang ia hidup di panggung kecil, di tangan seorang dalang, menunggu untuk terus didengarkan.',
    4, -- Tradisi
    1, -- Admin
    'published',
    125,
    '2026-01-10 04:14:00',
    '2026-01-10 04:14:00'
),

-- POST 2: Ruang Tamu (Category: Esai id=2)
(
    'Ruang Tamu yang Tidak Pernah Kosong',
    'ruang-tamu-yang-tidak-pernah-kosong',
    'Argumentasi Sisi Rekonstruksi dan Drama Trauma Domestik dalam Pementasan Teater Djavu SMK Taman Siswa dalam final Festival Teater Pelajar.',
    'Oleh Imam Khanafi, esais, penonton teater

Ruang tamu kerap dipahami sebagai ruang paling netral dalam rumah. Ia bukan kamar tidur yang intim, bukan dapur yang fungsional, bukan halaman yang terbuka. Ruang tamu adalah tempat menerima: tamu, kabar, gosip, konflik, dan sering kali kepura-puraan.

Dalam pementasan Argumentasi Sisi Rekonstruksi karya Diky Soemarno oleh Teater Djavu, ruang tamu tidak lagi menjadi latar pasif, melainkan pusat gravitasi dramatik. Seluruh konflik keluarga patriarkal yang paradoksnya kini berwujud matriarkal dipadatkan ke dalam satu ruang yang seolah tak pernah bernapas lega.

Pementasan yang dilakukan dengan sting panggung di ruang tamu, dengan jam kursi, perabot kuno, dan dialog yang berlangsung di sana, menegaskan satu hal penting: tragedi keluarga tidak pernah benar-benar pergi. Ia hanya berpindah jam, berpindah kursi, berpindah posisi duduk.

Dalam konteks ini, ruang tamu menjadi metafora dari kehidupan keluarga Indonesia pasca-trauma domestik: terlihat rapi di permukaan, namun menyimpan endapan luka yang tak selesai.

Ibu Sastro adalah pusat kontrol ruang tamu itu. Di usia 50-an, ia hadir sebagai figur dominan yang mengatur ritme, jam, dan batasan. Ia bukan sekadar ibu, melainkan penjaga moral, penentu aturan, dan pengingat masa lalu yang tak boleh dilupakan namun juga tak boleh dibicarakan secara jujur.

Judul Argumentasi Sisi Rekonstruksi menemukan maknanya di sini. Argumentasi bukan sekadar debat verbal, melainkan cara bertahan hidup. Setiap karakter berargumentasi untuk membenarkan posisinya: ibu dengan kontrol, Lusi dengan kepatuhan, Yoni dengan pemberontakan, Bi Iyah dengan keheningan.

Pementasan Teater Djavu di ruang tamu menghapus jarak antara panggung dan kehidupan. Penonton tidak lagi aman sebagai pengamat. Mereka duduk di ruang yang sama, menghirup udara yang sama, dan mungkin mengingat ruang tamu mereka sendiri.

Dan di situlah kekuatan naskah ini bekerja: membuat kita bertanya, trauma apa yang sedang kita rawat diam-diam di ruang tamu rumah kita?',
    2, -- Esai
    1, -- Admin
    'published',
    89,
    '2025-12-25 07:23:00',
    '2025-12-25 07:23:00'
),

-- POST 3: Sample Berita
(
    'Sanggar Widyas Budaya Gelar Fragmen Ramayana dalam Pentas Tahunan ke-18',
    'sanggar-widyas-budaya-gelar-fragmen-ramayana',
    'Sanggar Widyas Budaya kembali menggelar pentas tahunan dengan membawakan fragmen Ramayana yang memukau.',
    'Sanggar Widyas Budaya kembali menggelar pentas tahunan ke-18 mereka dengan membawakan fragmen Ramayana yang memukau para penonton.

Acara yang digelar di Gedung Kesenian ini menjadi bukti konsistensi sanggar dalam melestarikan seni tari tradisional Jawa. Puluhan penari dari berbagai usia tampil apik membawakan kisah epik Ramayana.

Ketua Sanggar, Ibu Widyastuti, menyampaikan bahwa pentas tahunan ini merupakan ajang untuk menunjukkan hasil latihan selama setahun penuh. "Kami berharap generasi muda tetap mencintai seni tradisi kita," ujarnya.

Pentas tahun ini menampilkan babak Shinta Obong yang menjadi klimaks dari kisah Ramayana. Para penari senior membawakan karakter utama dengan sangat memukau.',
    1, -- Berita
    1, -- Admin
    'published',
    45,
    '2025-12-29 19:36:00',
    '2025-12-29 19:36:00'
),

-- POST 4: Sample Komunitas
(
    'Komunitas Sastra Kudus Gelar Diskusi Bulanan',
    'komunitas-sastra-kudus-diskusi-bulanan',
    'Komunitas Sastra Kudus mengadakan diskusi bulanan membahas perkembangan sastra kontemporer Indonesia.',
    'Komunitas Sastra Kudus menggelar diskusi bulanan mereka dengan tema "Sastra Kontemporer Indonesia: Tantangan dan Peluang".

Diskusi yang berlangsung di Cafe Literasi ini dihadiri oleh puluhan pegiat sastra dari berbagai latar belakang. Narasumber utama, Pak Bambang Suryanto, membahas tentang bagaimana sastra Indonesia bisa tetap relevan di era digital.

"Kita perlu memanfaatkan platform digital untuk menyebarkan karya sastra, namun tetap menjaga kualitas dan kedalaman karya," ujar Bambang.

Peserta juga berdiskusi tentang tantangan publikasi buku di era digital dan bagaimana komunitas bisa berperan dalam ekosistem literasi.',
    3, -- Komunitas
    1, -- Admin
    'published',
    32,
    '2026-01-05 14:00:00',
    '2026-01-05 14:00:00'
),

-- POST 5: Sample Ekosistem
(
    'Ekosistem Seni Budaya Jawa Tengah: Peta dan Potensi',
    'ekosistem-seni-budaya-jawa-tengah',
    'Memetakan ekosistem seni budaya di Jawa Tengah dan potensi pengembangannya.',
    'Jawa Tengah memiliki ekosistem seni budaya yang sangat kaya dan beragam. Dari wayang kulit hingga batik, dari gamelan hingga tari tradisional, semuanya membentuk identitas budaya yang unik.

Berdasarkan pemetaan yang dilakukan tim Mentas.id, terdapat lebih dari 500 komunitas seni aktif di seluruh Jawa Tengah. Komunitas-komunitas ini tersebar dari Semarang hingga Solo, dari Kudus hingga Kebumen.

Yang menarik, banyak komunitas yang mulai menggabungkan tradisi dengan inovasi. Misalnya, pertunjukan wayang kontemporer yang menggabungkan teknologi mapping dengan lakon klasik.

Tantangan terbesar adalah regenerasi. Banyak maestro seni yang sudah berusia lanjut namun belum memiliki penerus yang memadai. Ini menjadi PR besar bagi kita semua.',
    5, -- Ekosistem
    1, -- Admin
    'published',
    67,
    '2026-01-12 10:00:00',
    '2026-01-12 10:00:00'
);

-- Sample Zines (Bulletin Sastra)
INSERT INTO zines (title, slug, content, cover_image, created_at) VALUES
(
    'Zine Sastra Vol. 1: Puisi Tanah Air',
    'zine-sastra-vol-1-puisi-tanah-air',
    'Kumpulan puisi dari berbagai penyair Indonesia yang mengangkat tema tanah air dan kebangsaan.

---

TANAH AIRKU
oleh Penyair Muda

Tanah airku, tanah tumpah darahku
Di sini aku berdiri, di sini aku bermimpi
Laut biru membentang, gunung menjulang
Semua adalah rumahku

---

NUSANTARA
oleh Sastrawan Kudus

Dari Sabang sampai Merauke
Kita adalah satu
Berbeda namun bersatu
Itulah Indonesia

---

Zine ini diterbitkan sebagai bentuk apresiasi terhadap karya-karya sastra Indonesia.',
    NULL,
    '2025-12-01 00:00:00'
),
(
    'Zine Sastra Vol. 2: Cerita Pendek Pilihan',
    'zine-sastra-vol-2-cerita-pendek-pilihan',
    'Kumpulan cerpen terbaik dari kontributor Mentas.id.

---

HUJAN DI BULAN DESEMBER
oleh Kontributor Anonim

Hujan turun deras di penghujung tahun. Aku duduk di teras rumah nenek, menyeruput kopi yang sudah dingin. Ingatan membawaku kembali ke masa kecil, ketika hujan adalah teman bermain, bukan musuh yang harus dihindari.

Nenek selalu bilang, hujan adalah berkah. Tapi hari ini, hujan terasa seperti tangisan langit yang tak berujung...

---

Zine ini memuat karya-karya terpilih dari open submission.',
    NULL,
    '2026-01-01 00:00:00'
);

-- Sample Communities (Katalog)
INSERT INTO communities (name, slug, description, location, contact, website, image, created_at) VALUES
(
    'Teater Djavu SMK Taman Siswa',
    'teater-djavu-smk-taman-siswa',
    'Komunitas teater pelajar yang aktif dalam berbagai festival dan pentas seni. Fokus pada teater kontemporer dengan sentuhan budaya lokal.',
    'Kudus, Jawa Tengah',
    '081234567890',
    NULL,
    NULL,
    '2025-01-01 00:00:00'
),
(
    'Sanggar Widyas Budaya',
    'sanggar-widyas-budaya',
    'Sanggar seni tari tradisional yang telah berdiri lebih dari 18 tahun. Mengajarkan berbagai tarian Jawa klasik dan kreasi baru.',
    'Semarang, Jawa Tengah',
    '087654321098',
    'https://widyasbudaya.com',
    NULL,
    '2005-06-15 00:00:00'
),
(
    'Komunitas Sastra Kudus',
    'komunitas-sastra-kudus',
    'Komunitas pegiat sastra yang rutin mengadakan diskusi, bedah buku, dan workshop penulisan. Terbuka untuk semua kalangan.',
    'Kudus, Jawa Tengah',
    NULL,
    NULL,
    NULL,
    '2018-03-20 00:00:00'
),
(
    'Sanggar Ngesti Laras Madya',
    'sanggar-ngesti-laras-madya',
    'Sanggar pelestarian Wayang Klithik Wonosoco yang didirikan oleh Ki Sutikno. Menjadi pusat pembelajaran dan regenerasi dalang wayang klithik.',
    'Desa Wonosoco, Undaan, Kudus',
    NULL,
    NULL,
    NULL,
    '2010-01-01 00:00:00'
);
