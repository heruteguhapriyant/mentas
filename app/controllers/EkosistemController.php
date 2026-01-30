<?php

/**
 * EkosistemController - Halaman Ekosistem (Static View)
 * Menampilkan daftar komunitas/kolaborator Mentas
 */
class EkosistemController extends Controller
{
    /**
     * Ekosistem data - 4 komunitas utama
     */
    private $ekosistemData = [
        [
            'id' => 1,
            'name' => 'Serah',
            'slug' => 'serah',
            'tagline' => 'Keintiman Dalam Wacana Gerak dan Kebudayaan',
            'description' => 'Serah adalah wadah kolaborasi yang fokus pada eksplorasi gerak tubuh dan kebudayaan. Melalui berbagai kegiatan dan pertunjukan, Serah menghadirkan dialog antara tradisi dan kontemporer dalam bentuk seni gerak.',
            'image' => 'uploads/ekosistem/serah.jpg',
            'instagram' => 'https://instagram.com/serah.id',
            'color' => '#e63946'
        ],
        [
            'id' => 2,
            'name' => 'Theatre Jamming',
            'slug' => 'theatre-jamming',
            'tagline' => 'Ruang Eksperimentasi Teater',
            'description' => 'Theatre Jamming adalah komunitas teater yang bergerak dalam eksperimentasi dan pengembangan seni pertunjukan. Fokus pada kolaborasi lintas disiplin dan pengembangan aktor muda.',
            'image' => 'uploads/ekosistem/theatre-jamming.jpg',
            'instagram' => 'https://instagram.com/theatrejamming',
            'color' => '#2a9d8f'
        ],
        [
            'id' => 3,
            'name' => 'Maossae',
            'slug' => 'maossae',
            'tagline' => 'Seni Rupa dan Visual Kontemporer',
            'description' => 'Maossae adalah kolektif seni rupa yang mengeksplorasi medium visual dalam berbagai bentuk. Dari lukisan, instalasi, hingga seni digital, Maossae terus bereksperimen dengan bahasa visual.',
            'image' => 'uploads/ekosistem/maossae.jpg',
            'instagram' => 'https://instagram.com/maossae',
            'color' => '#f4a261'
        ],
        [
            'id' => 4,
            'name' => 'Teater III',
            'slug' => 'teater-iii',
            'tagline' => 'Komunitas Teater Kampus',
            'description' => 'Teater III adalah komunitas teater yang berbasis di kampus, aktif dalam pengembangan seni peran dan produksi pertunjukan. Menjadi wadah bagi mahasiswa untuk mengeksplorasi dunia teater.',
            'image' => 'uploads/ekosistem/teater-iii.jpg',
            'instagram' => 'https://instagram.com/teater.iii',
            'color' => '#6c5ce7'
        ]
    ];

    /**
     * Index - Tampilkan semua ekosistem
     */
    public function index()
    {
        return $this->view('ekosistem/index', [
            'ekosistem' => $this->ekosistemData
        ]);
    }

    /**
     * Detail - Tampilkan detail satu ekosistem
     */
    public function detail($slug)
    {
        $item = null;
        foreach ($this->ekosistemData as $eco) {
            if ($eco['slug'] === $slug) {
                $item = $eco;
                break;
            }
        }

        if (!$item) {
            return $this->view('errors/404');
        }

        return $this->view('ekosistem/detail', [
            'item' => $item,
            'allEkosistem' => $this->ekosistemData
        ]);
    }
}
