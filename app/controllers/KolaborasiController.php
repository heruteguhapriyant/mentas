<?php

/**
 * KolaborasiController - Halaman Kolaborasi
 * Menampilkan daftar komunitas/kolaborator Mentas
 * Uses DB data with hardcoded fallback
 */
class KolaborasiController extends Controller
{
    /**
     * Fallback kolaborasi data - used when DB table doesn't exist yet
     */
    private $fallbackData = [
        [
            'id' => 1,
            'title' => 'Serah',
            'slug' => 'serah',
            'description' => 'Mitra Dewan Kesenian Kudus dalam platform Serah, Ruang literasi seni dan pertunjukan untuk mewacanakan pergerakan kesenian dan Kebudayaan.',
            'cover_image' => 'uploads/ekosistem/serah.jpg',
            'social_media' => '{"instagram":"https://instagram.com/serah.id"}',
            'is_active' => 1,
            'contributor_names' => null
        ],
        [
            'id' => 2,
            'title' => 'Theatre Jamming',
            'slug' => 'theatre-jamming',
            'description' => 'Ruang Eksperimentasi Teater bersama Komunitas Seni Samar dan beberapa kelompok teater pelajar seperti Teater Dejavu dan Teater Sage. Theatre Jamming adalah komunitas teater yang bergerak dalam eksperimentasi dan pengembangan seni pertunjukandan sastra.',
            'cover_image' => 'uploads/ekosistem/theatre-jamming.jpg',
            'social_media' => '{"instagram":"https://instagram.com/theatrejamming"}',
            'is_active' => 1,
            'contributor_names' => null
        ],
        [
            'id' => 3,
            'title' => 'Maossae',
            'slug' => 'maossae',
            'description' => 'Tempat bertemu, berdiskusi dan berdialog dengan pemikiran kritis di Kabupaten Kudus.',
            'cover_image' => 'uploads/ekosistem/maossae.jpg',
            'social_media' => '{"instagram":"https://instagram.com/maossae"}',
            'is_active' => 1,
            'contributor_names' => null
        ],
        [
            'id' => 4,
            'title' => 'Panggung III',
            'slug' => 'teater-iii',
            'description' => 'Upoaya kolabarit bersama kelompok kesenian, yang utamanya di ruang teater. Menjadi semacam kerja pengetahuan dan kritik pasca pementesan teater.',
            'cover_image' => 'uploads/ekosistem/teater-iii.jpg',
            'social_media' => '{"instagram":"https://instagram.com/teater.iii"}',
            'is_active' => 1,
            'contributor_names' => null
        ]
    ];

    /**
     * Get kolaborasi data - from DB or fallback
     */
    private function getData()
    {
        try {
            $collabModel = new Collaboration();
            $data = $collabModel->getActive();
            return !empty($data) ? $data : $this->fallbackData;
        } catch (\Exception $e) {
            return $this->fallbackData;
        }
    }

    /**
     * Index - Tampilkan semua kolaborasi
     */
    public function index()
    {
        return $this->view('kolaborasi/index', [
            'kolaborasi' => $this->getData()
        ]);
    }

    /**
     * Detail - Tampilkan detail satu kolaborasi
     */
    public function detail($slug)
    {
        $item = null;
        
        try {
            $collabModel = new Collaboration();
            $item = $collabModel->findBySlug($slug);
            if ($item) {
                $item['contributors'] = $collabModel->getContributors($item['id']);
            }
        } catch (\Exception $e) {
            // Fallback to hardcoded
        }

        // Fallback to hardcoded data
        if (!$item) {
            foreach ($this->fallbackData as $eco) {
                if ($eco['slug'] === $slug) {
                    $item = $eco;
                    break;
                }
            }
        }

        if (!$item) {
            return $this->view('errors/404');
        }

        return $this->view('kolaborasi/detail', [
            'item' => $item,
            'allKolaborasi' => $this->getData()
        ]);
    }
}
