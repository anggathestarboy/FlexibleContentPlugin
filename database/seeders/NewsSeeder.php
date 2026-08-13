<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::first();

        $news = [
            [
                'id' => 'Rilis Fitur Baru di Tahun 2026',
                'en' => 'New Features Released in 2026',
                'image' => 'news/news-1.svg',
            ],
            [
                'id' => 'Kolaborasi dengan Mitra Internasional',
                'en' => 'Partnership with International Partners',
                'image' => 'news/news-2.svg',
            ],
            [
                'id' => 'Tips Meningkatkan Produktivitas Kerja',
                'en' => 'Tips to Boost Work Productivity',
                'image' => 'news/news-3.svg',
            ],
        ];

        foreach ($news as $index => $item) {
            $news = new News();
            $news->setTranslation('title', 'id', $item['id']);
            $news->setTranslation('title', 'en', $item['en']);
            $news->setTranslation('description', 'id', $this->description('id', $index));
            $news->setTranslation('description', 'en', $this->description('en', $index));
            $news->author_id = $author?->id;
            $news->image = $item['image'];
            $news->save();
        }
    }

    private function description(string $locale, int $index): array
    {
        $texts = [
            'id' => [
                'Kami dengan bangga mengumumkan peluncuran serangkaian fitur baru yang dirancang untuk memudahkan pengguna dalam keseharian. Pembaruan ini juga mencakup perbaikan performa dan pengalaman antarmuka yang lebih baik.',
                'Perusahaan kami resmi menjalin kerja sama dengan mitra internasional untuk memperluas jangkauan layanan. Kolaborasi ini diharapkan membawa dampak positif bagi pengguna di berbagai negara.',
                'Produktivitas kerja yang tinggi dimulai dari kebiasaan kecil. Pada artikel ini kami membagikan beberapa tips praktis untuk mengatur waktu, mengurangi distraksi, dan menjaga fokus sepanjang hari.',
            ],
            'en' => [
                'We are proud to announce the launch of a series of new features designed to make everyday tasks easier for our users. This update also includes performance improvements and a better interface experience.',
                'Our company has officially started a collaboration with international partners to expand our service coverage. This partnership is expected to bring positive impact to users around the globe.',
                'High work productivity starts with small habits. In this article we share practical tips for managing time, reducing distractions, and staying focused throughout the day.',
            ],
        ];

        return [
            'type' => 'doc',
            'content' => [
                [
                    'type' => 'paragraph',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => $texts[$locale][$index],
                        ],
                    ],
                ],
            ],
        ];
    }
}
