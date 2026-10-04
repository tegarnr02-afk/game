<?php

/*
|--------------------------------------------------------------------------
| Ensiklopedia bahan aktif
|--------------------------------------------------------------------------
| Satu entri per bahan aktif. Untuk menambah bahan baru, salin satu blok
| dan ubah isinya. Ikon diambil dari public/images/ingredients/{icon}.
| Informasi bersifat edukasi umum, bukan saran medis.
*/

return [
    'ingredients' => [

        [
            'slug' => 'alpha-arbutin',
            'name' => 'Alpha Arbutin',
            'aka' => 'Turunan hidrokuinon dari tanaman',
            'icon' => 'alpha.webp',
            'tagline' => 'Pemudar noda hitam yang lembut.',
            'categories' => ['Pencerah'],
            'zones' => ['Brightening Zone'],
            'about' => 'Alpha arbutin menghambat enzim tirosinase, yaitu enzim yang membantu kulit membuat melanin. Dengan produksi melanin yang lebih terkontrol, noda gelap tampak memudar perlahan tanpa seiritatif hidrokuinon.',
            'benefits' => [
                'Memudarkan bekas jerawat dan noda hitam',
                'Meratakan warna kulit yang tidak merata',
                'Relatif lembut dan jarang membuat perih',
            ],
            'usage' => ['waktu' => 'Pagi dan atau malam', 'frekuensi' => '1 sampai 2 kali sehari', 'kadar' => '1 sampai 2%'],
            'skin' => 'Semua jenis kulit, termasuk yang cenderung sensitif',
            'pairs' => ['Niacinamide', 'Ceramide', 'Tranexamic Acid'],
            'avoid' => ['Eksfoliasi kuat dalam satu waktu bila kulit sensitif'],
            'cautions' => [
                'Hasil butuh waktu, biasanya 8 sampai 12 minggu pemakaian rutin',
                'Tetap pakai sunscreen agar noda tidak muncul kembali',
            ],
        ],

        [
            'slug' => 'vitamin-c',
            'name' => 'Vitamin C',
            'aka' => 'L-ascorbic acid dan turunannya',
            'icon' => 'vitc.webp',
            'tagline' => 'Antioksidan pencerah andalan di pagi hari.',
            'categories' => ['Pencerah', 'Antioksidan'],
            'zones' => ['Brightening Zone', 'Renew Zone'],
            'about' => 'Vitamin C menetralkan radikal bebas akibat sinar UV dan polusi, membantu produksi kolagen, serta menghambat pembentukan pigmen. Hasilnya kulit tampak lebih cerah dan segar.',
            'benefits' => [
                'Mencerahkan kulit kusam',
                'Melindungi kulit dari kerusakan radikal bebas',
                'Mendukung produksi kolagen',
                'Memperkuat kerja sunscreen di pagi hari',
            ],
            'usage' => ['waktu' => 'Pagi, sebelum sunscreen', 'frekuensi' => 'Sekali sehari', 'kadar' => '5 sampai 20% (L-ascorbic acid)'],
            'skin' => 'Kulit normal, kusam, atau berhiperpigmentasi',
            'pairs' => ['Vitamin E', 'Niacinamide', 'Sunscreen'],
            'avoid' => ['AHA/BHA dan Retinol di waktu yang sama bagi kulit sensitif'],
            'cautions' => [
                'Mudah teroksidasi. Simpan di tempat sejuk dan gelap, buang bila berubah cokelat pekat',
                'Kadar tinggi dan pH rendah bisa terasa perih, mulai dari kadar kecil',
            ],
        ],

        [
            'slug' => 'niacinamide',
            'name' => 'Niacinamide',
            'aka' => 'Vitamin B3',
            'icon' => 'niac.webp',
            'tagline' => 'Serbaguna: barrier kuat, minyak terkontrol.',
            'categories' => ['Pelembap & Barrier', 'Pencerah'],
            'zones' => ['Brightening Zone', 'Renew Zone'],
            'about' => 'Niacinamide memperkuat lapisan pelindung kulit, membantu mengatur produksi minyak, dan mengurangi perpindahan pigmen ke permukaan kulit. Bahan ini cocok dipadukan dengan hampir semua bahan aktif.',
            'benefits' => [
                'Memperkuat skin barrier',
                'Mengontrol minyak dan menyamarkan tampilan pori',
                'Menyamarkan noda dan warna kulit tidak merata',
            ],
            'usage' => ['waktu' => 'Pagi dan atau malam', 'frekuensi' => '1 sampai 2 kali sehari', 'kadar' => '2 sampai 5% (maksimal sekitar 10%)'],
            'skin' => 'Semua jenis kulit, terutama berminyak dan berjerawat',
            'pairs' => ['Alpha Arbutin', 'Vitamin C', 'Ceramide', 'Retinol', 'BHA'],
            'avoid' => [],
            'cautions' => [
                'Kadar tinggi bisa membuat kulit memerah (flushing) pada sebagian orang',
                'Bila kulit terasa tidak nyaman, turunkan kadar atau frekuensinya',
            ],
        ],

        [
            'slug' => 'kojic-acid',
            'name' => 'Kojic Acid',
            'aka' => 'Hasil fermentasi jamur tertentu',
            'icon' => 'kojic.webp',
            'tagline' => 'Pencerah untuk bekas noda yang membandel.',
            'categories' => ['Pencerah'],
            'zones' => ['Brightening Zone'],
            'about' => 'Kojic acid menghambat tirosinase sehingga pembentukan melanin berkurang. Efeknya terlihat pada hiperpigmentasi seperti bekas jerawat dan flek akibat matahari.',
            'benefits' => [
                'Memudarkan hiperpigmentasi',
                'Membantu meratakan warna kulit',
            ],
            'usage' => ['waktu' => 'Malam hari', 'frekuensi' => 'Mulai 2 sampai 3 kali seminggu', 'kadar' => '1 sampai 2%'],
            'skin' => 'Kulit normal sampai berminyak. Kurang cocok untuk kulit sangat sensitif',
            'pairs' => ['Niacinamide', 'Ceramide'],
            'avoid' => ['Eksfoliasi kuat dan Retinol di malam yang sama'],
            'cautions' => [
                'Bisa memicu iritasi atau dermatitis kontak pada sebagian orang, lakukan patch test',
                'Wajib pakai sunscreen setiap hari',
            ],
        ],

        [
            'slug' => 'aha',
            'name' => 'AHA',
            'aka' => 'Alpha hydroxy acid (glycolic, mandelic, dan lainnya)',
            'icon' => 'aha.webp',
            'tagline' => 'Mengangkat sel kulit mati agar kulit tampak cerah.',
            'categories' => ['Eksfoliasi'],
            'zones' => ['Brightening Zone'],
            'about' => 'AHA larut dalam air dan bekerja di permukaan kulit dengan melonggarkan ikatan antar sel kulit mati. Setelah lapisan kusam terangkat, kulit tampak lebih halus dan cerah.',
            'benefits' => [
                'Mengangkat sel kulit mati dan kusam',
                'Menghaluskan tekstur kulit',
                'Membantu produk lain meresap lebih baik',
            ],
            'usage' => ['waktu' => 'Malam hari', 'frekuensi' => '1 sampai 3 kali seminggu', 'kadar' => '5 sampai 10%'],
            'skin' => 'Kulit normal sampai kering, kusam, bertekstur kasar',
            'pairs' => ['Ceramide', 'Pelembap sederhana'],
            'avoid' => ['Retinol, BHA, dan Vitamin C murni di waktu yang sama'],
            'cautions' => [
                'Membuat kulit lebih sensitif terhadap matahari, wajib sunscreen SPF 30+',
                'Hentikan sementara bila kulit perih, mengelupas berlebihan, atau terasa terbakar',
            ],
        ],

        [
            'slug' => 'bha',
            'name' => 'BHA (Salicylic Acid)',
            'aka' => 'Beta hydroxy acid',
            'icon' => 'bha.webp',
            'tagline' => 'Membersihkan pori dari dalam.',
            'categories' => ['Eksfoliasi', 'Anti-jerawat'],
            'zones' => ['Acne Zone', 'Renew Zone'],
            'about' => 'Berbeda dengan AHA, BHA larut dalam minyak sehingga bisa masuk ke dalam pori, melarutkan sebum dan sel kulit mati penyumbat, serta punya efek anti-inflamasi ringan.',
            'benefits' => [
                'Mengurangi komedo dan pori tersumbat',
                'Membantu mencegah jerawat baru',
                'Menenangkan peradangan ringan',
            ],
            'usage' => ['waktu' => 'Pagi atau malam', 'frekuensi' => 'Mulai 2 sampai 3 kali seminggu', 'kadar' => '0,5 sampai 2%'],
            'skin' => 'Kulit berminyak, berkomedo, dan berjerawat',
            'pairs' => ['Niacinamide', 'Ceramide', 'Centella Asiatica'],
            'avoid' => ['AHA, Retinol, dan Benzoyl Peroxide di waktu yang sama'],
            'cautions' => [
                'Bisa mengeringkan, selalu lengkapi dengan pelembap',
                'Bila sedang hamil atau menyusui, konsultasikan dengan dokter terlebih dahulu',
            ],
        ],

        [
            'slug' => 'benzoyl-peroxide',
            'name' => 'Benzoyl Peroxide',
            'aka' => 'BPO',
            'icon' => 'benzo.webp',
            'tagline' => 'Pembasmi bakteri penyebab jerawat meradang.',
            'categories' => ['Anti-jerawat'],
            'zones' => ['Acne Zone'],
            'about' => 'Benzoyl peroxide melepaskan oksigen yang membunuh bakteri penyebab jerawat (C. acnes) dan membantu mengurangi sumbatan pori. Efektif untuk jerawat meradang.',
            'benefits' => [
                'Mengurangi jerawat meradang',
                'Menekan bakteri penyebab jerawat',
                'Membantu mencegah jerawat baru',
            ],
            'usage' => ['waktu' => 'Pagi atau malam, atau sebagai totol jerawat', 'frekuensi' => 'Mulai 1 kali sehari atau selang hari', 'kadar' => '2,5 sampai 5%'],
            'skin' => 'Kulit berminyak dan berjerawat meradang',
            'pairs' => ['Ceramide', 'Pelembap bebas minyak'],
            'avoid' => ['Retinol dan Vitamin C di waktu yang sama', 'BHA dan AHA bila kulit sensitif'],
            'cautions' => [
                'Bisa membuat kulit kering dan mengelupas, mulai dari kadar rendah',
                'Dapat memutihkan handuk, sarung bantal, dan rambut',
            ],
        ],

        [
            'slug' => 'ceramide',
            'name' => 'Ceramide',
            'aka' => 'Lipid alami kulit',
            'icon' => 'cera.webp',
            'tagline' => 'Perekat barrier agar kelembapan terkunci.',
            'categories' => ['Pelembap & Barrier'],
            'zones' => ['Acne Zone'],
            'about' => 'Ceramide adalah lemak alami yang menyusun lapisan pelindung kulit. Saat kadarnya berkurang, kulit mudah kering dan sensitif. Produk ber-ceramide membantu memperbaiki dan menguatkan barrier.',
            'benefits' => [
                'Mengunci kelembapan',
                'Memperbaiki skin barrier yang rusak',
                'Mengurangi kering dan iritasi akibat bahan aktif kuat',
            ],
            'usage' => ['waktu' => 'Pagi dan malam', 'frekuensi' => '1 sampai 2 kali sehari', 'kadar' => 'Mengikuti formulasi produk'],
            'skin' => 'Semua jenis kulit, termasuk berjerawat dan sensitif',
            'pairs' => ['Hampir semua bahan aktif', 'Retinol', 'AHA/BHA', 'Benzoyl Peroxide'],
            'avoid' => [],
            'cautions' => [
                'Sangat aman, tetapi pilih tekstur yang tidak berat bila kulit sangat berminyak',
            ],
        ],

        [
            'slug' => 'azelaic-acid',
            'name' => 'Azelaic Acid',
            'aka' => 'Asam dari biji-bijian seperti gandum',
            'icon' => 'aze.webp',
            'tagline' => 'Lembut tapi multifungsi untuk jerawat dan noda.',
            'categories' => ['Anti-jerawat', 'Pencerah'],
            'zones' => ['Acne Zone'],
            'about' => 'Azelaic acid bersifat antibakteri dan anti-inflamasi, membantu melepas sumbatan pori, serta menghambat tirosinase untuk memudarkan noda. Dikenal juga membantu mengurangi kemerahan.',
            'benefits' => [
                'Mengurangi jerawat dan kemerahan',
                'Memudarkan bekas jerawat',
                'Cukup lembut untuk kulit sensitif',
            ],
            'usage' => ['waktu' => 'Pagi dan atau malam', 'frekuensi' => '1 sampai 2 kali sehari', 'kadar' => 'Sekitar 10% (produk bebas), 15 sampai 20% (resep dokter)'],
            'skin' => 'Kulit berjerawat, kemerahan, dan sensitif',
            'pairs' => ['Niacinamide', 'Ceramide', 'Centella Asiatica'],
            'avoid' => ['Eksfoliasi kuat dalam satu waktu bila kulit sensitif'],
            'cautions' => [
                'Dapat terasa perih atau gatal di awal pemakaian',
                'Bila sedang hamil, konsultasikan dengan dokter',
            ],
        ],

        [
            'slug' => 'centella-asiatica',
            'name' => 'Centella Asiatica (Cica)',
            'aka' => 'Pegagan',
            'icon' => 'cica.webp',
            'tagline' => 'Penenang kulit yang sedang meradang.',
            'categories' => ['Menenangkan'],
            'zones' => ['Acne Zone'],
            'about' => 'Ekstrak pegagan kaya madecassoside dan asiaticoside yang menenangkan kemerahan, membantu pemulihan kulit, dan mendukung skin barrier.',
            'benefits' => [
                'Menenangkan kulit kemerahan dan iritasi',
                'Mempercepat pemulihan kulit',
                'Mendukung barrier kulit',
            ],
            'usage' => ['waktu' => 'Pagi dan atau malam', 'frekuensi' => '1 sampai 2 kali sehari', 'kadar' => 'Mengikuti formulasi produk'],
            'skin' => 'Semua jenis kulit, terutama sensitif dan meradang',
            'pairs' => ['Hampir semua bahan aktif', 'BHA', 'Benzoyl Peroxide', 'Retinol'],
            'avoid' => [],
            'cautions' => [
                'Sangat jarang iritatif, tetapi tetap lakukan patch test pada kulit sangat sensitif',
            ],
        ],

        [
            'slug' => 'peptide',
            'name' => 'Peptide',
            'aka' => 'Rantai pendek asam amino',
            'icon' => 'Peptide.webp',
            'tagline' => 'Sinyal pembangun kolagen untuk kulit kenyal.',
            'categories' => ['Anti-aging'],
            'zones' => ['Aging & Antioxidant Zone'],
            'about' => 'Peptide bekerja seperti pesan singkat ke sel kulit agar memproduksi kolagen dan elastin. Pemakaian rutin membantu kulit tampak lebih kenyal dan garis halus lebih samar.',
            'benefits' => [
                'Membantu kekenyalan dan elastisitas kulit',
                'Menyamarkan garis halus',
                'Lembut dan jarang membuat iritasi',
            ],
            'usage' => ['waktu' => 'Pagi dan atau malam', 'frekuensi' => '1 sampai 2 kali sehari', 'kadar' => 'Mengikuti formulasi produk'],
            'skin' => 'Semua jenis kulit, terutama yang mulai menunjukkan tanda penuaan',
            'pairs' => ['Niacinamide', 'Ceramide', 'Pelembap'],
            'avoid' => ['Jangan dilapis langsung dengan asam ber-pH rendah (AHA/BHA/Vitamin C murni)'],
            'cautions' => [
                'Hasil bertahap, biasanya terlihat setelah 8 sampai 12 minggu',
            ],
        ],

        [
            'slug' => 'bakuchiol',
            'name' => 'Bakuchiol',
            'aka' => 'Alternatif nabati dari retinol',
            'icon' => 'Bakuchiol.webp',
            'tagline' => 'Mirip retinol, tapi jauh lebih ramah.',
            'categories' => ['Anti-aging'],
            'zones' => ['Aging & Antioxidant Zone'],
            'about' => 'Bakuchiol berasal dari tanaman Psoralea corylifolia. Meski bukan retinoid, bahan ini memberi efek serupa pada garis halus dan hiperpigmentasi dengan risiko iritasi yang lebih kecil.',
            'benefits' => [
                'Menyamarkan garis halus',
                'Membantu meratakan warna kulit',
                'Tidak membuat kulit sensitif terhadap matahari seperti retinol',
            ],
            'usage' => ['waktu' => 'Pagi dan atau malam', 'frekuensi' => '1 sampai 2 kali sehari', 'kadar' => '0,5 sampai 1%'],
            'skin' => 'Semua jenis kulit, termasuk yang sensitif terhadap retinol',
            'pairs' => ['Niacinamide', 'Ceramide', 'Peptide', 'Vitamin E'],
            'avoid' => [],
            'cautions' => [
                'Penelitian jangka panjang masih terbatas, hasilnya cenderung lebih lambat dari retinol',
                'Data keamanan saat hamil masih terbatas, konsultasikan dengan dokter',
            ],
        ],

        [
            'slug' => 'vitamin-e',
            'name' => 'Vitamin E',
            'aka' => 'Tocopherol',
            'icon' => 'vite.webp',
            'tagline' => 'Antioksidan pelindung yang melembapkan.',
            'categories' => ['Antioksidan'],
            'zones' => ['Aging & Antioxidant Zone'],
            'about' => 'Vitamin E larut dalam lemak, menangkal radikal bebas, dan membantu melembapkan. Bahan ini bekerja bersama Vitamin C sehingga perlindungan terhadap kerusakan akibat sinar UV menjadi lebih kuat.',
            'benefits' => [
                'Menangkal radikal bebas',
                'Membantu melembapkan kulit',
                'Memperkuat efek Vitamin C',
            ],
            'usage' => ['waktu' => 'Pagi dan atau malam', 'frekuensi' => '1 kali sehari', 'kadar' => 'Sekitar 0,5 sampai 1%'],
            'skin' => 'Kulit normal sampai kering',
            'pairs' => ['Vitamin C', 'Bakuchiol', 'Ceramide'],
            'avoid' => [],
            'cautions' => [
                'Kadar tinggi dapat terasa berat dan berisiko menyumbat pori pada kulit sangat berminyak',
            ],
        ],

        [
            'slug' => 'retinol',
            'name' => 'Retinol',
            'aka' => 'Turunan vitamin A',
            'icon' => 'Retinol.webp',
            'tagline' => 'Bintang anti-aging yang butuh kesabaran.',
            'categories' => ['Anti-aging'],
            'zones' => ['Aging & Antioxidant Zone', 'Renew Zone'],
            'about' => 'Retinol dikonversi kulit menjadi bentuk aktif yang mempercepat pergantian sel, merangsang kolagen, dan membantu menghaluskan tekstur serta garis halus. Efektif juga untuk jerawat dan noda.',
            'benefits' => [
                'Menyamarkan garis halus dan kerutan',
                'Menghaluskan tekstur kulit',
                'Membantu mengurangi jerawat dan noda',
            ],
            'usage' => ['waktu' => 'Hanya malam hari', 'frekuensi' => 'Mulai 2 kali seminggu, naikkan perlahan', 'kadar' => '0,1 sampai 1%'],
            'skin' => 'Kulit normal sampai berminyak. Mulai pelan bila sensitif',
            'pairs' => ['Niacinamide', 'Ceramide', 'Centella Asiatica', 'Peptide'],
            'avoid' => ['AHA, BHA, dan Benzoyl Peroxide di malam yang sama', 'Vitamin C di waktu yang sama'],
            'cautions' => [
                'Awal pemakaian bisa kering dan mengelupas (retinization)',
                'Wajib sunscreen SPF 30+ setiap pagi',
                'Hindari saat hamil dan menyusui, konsultasikan dengan dokter',
            ],
        ],

        [
            'slug' => 'tranexamic-acid',
            'name' => 'Tranexamic Acid',
            'aka' => 'Turunan asam amino lisin',
            'icon' => 'Acid.webp',
            'tagline' => 'Penjinak flek dan melasma.',
            'categories' => ['Pencerah'],
            'zones' => ['Aging & Antioxidant Zone'],
            'about' => 'Tranexamic acid mengganggu sinyal yang memicu sel penghasil pigmen (melanosit), sehingga membantu memudarkan flek, melasma, dan bekas peradangan. Relatif lembut dibanding pencerah lain.',
            'benefits' => [
                'Memudarkan flek dan hiperpigmentasi',
                'Membantu pada melasma',
                'Umumnya ditoleransi dengan baik',
            ],
            'usage' => ['waktu' => 'Pagi dan atau malam', 'frekuensi' => '1 sampai 2 kali sehari', 'kadar' => '2 sampai 5%'],
            'skin' => 'Semua jenis kulit dengan hiperpigmentasi',
            'pairs' => ['Niacinamide', 'Vitamin C', 'Alpha Arbutin'],
            'avoid' => [],
            'cautions' => [
                'Hasil butuh beberapa minggu, kombinasikan dengan sunscreen',
                'Untuk melasma yang berat, periksakan ke dokter kulit',
            ],
        ],

        [
            'slug' => 'lactic-acid',
            'name' => 'Lactic Acid',
            'aka' => 'AHA yang lembut',
            'icon' => 'lactic.webp',
            'tagline' => 'Eksfoliasi sambil melembapkan.',
            'categories' => ['Eksfoliasi'],
            'zones' => ['Renew Zone'],
            'about' => 'Lactic acid termasuk AHA dengan molekul lebih besar sehingga bekerja lebih lembut. Selain mengangkat sel kulit mati, ia juga membantu kulit menahan air.',
            'benefits' => [
                'Mengangkat sel kulit mati dengan lembut',
                'Membantu melembapkan',
                'Menghaluskan tekstur dan mencerahkan',
            ],
            'usage' => ['waktu' => 'Malam hari', 'frekuensi' => 'Mulai 1 sampai 2 kali seminggu', 'kadar' => '5 sampai 10%'],
            'skin' => 'Kulit kering, kusam, dan cenderung sensitif',
            'pairs' => ['Ceramide', 'Niacinamide', 'Centella Asiatica'],
            'avoid' => ['Retinol, BHA, dan Vitamin C murni di waktu yang sama'],
            'cautions' => [
                'Kulit menjadi lebih sensitif terhadap matahari, wajib sunscreen',
                'Hentikan sementara bila terasa perih atau mengelupas berlebihan',
            ],
        ],

    ],
];