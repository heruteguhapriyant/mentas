<?php

return [
    'content_types' => [
        [
            'name' => 'Blog',
            'slug' => 'blog',
            'template' => 'article',
            'categories' => [
                ['name' => 'Berita', 'slug' => 'berita'],
                ['name' => 'Esai', 'slug' => 'esai'],
                ['name' => 'Komunitas', 'slug' => 'komunitas'],
                ['name' => 'Tradisi', 'slug' => 'tradisi'],
                ['name' => 'Ekosistem', 'slug' => 'ekosistem'],
            ]
        ],
    ],

    'contents' => [
        [
            'title' => 'Judul Artikel Pertama',
            'slug' => 'judul-artikel-pertama',
            'author' => 'Admin Mentas',
            'category' => 'berita',
            'excerpt' => 'Ini adalah ringkasan artikel pertama...',
            'body' => 'ISI ARTIKEL PANJANG DI SINI'
        ],
        [
            'title' => 'Esai Tentang Tradisi',
            'slug' => 'esai-tentang-tradisi',
            'author' => 'Kontributor',
            'category' => 'esai',
            'excerpt' => 'Esai panjang tentang tradisi...',
            'body' => 'ISI ESAI PANJANG...'
        ],
    ]
];
