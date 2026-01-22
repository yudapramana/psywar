<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $galleries = [
            [
                'title' => 'Opening Ceremony',
                'description' => 'Opening ceremony of SYMCARD scientific meeting',
                'image_path' => 'projects/assets/img/events/gallery-1.webp',
                'thumbnail_path' => 'projects/assets/img/events/gallery-1.webp',
                'order' => 1,
            ],
            [
                'title' => 'Scientific Symposium',
                'description' => 'Scientific symposium session with expert speakers',
                'image_path' => 'projects/assets/img/events/gallery-2.webp',
                'thumbnail_path' => 'projects/assets/img/events/gallery-2.webp',
                'order' => 2,
            ],
            [
                'title' => 'Panel Discussion',
                'description' => 'Interactive panel discussion with cardiology experts',
                'image_path' => 'projects/assets/img/events/gallery-3.webp',
                'thumbnail_path' => 'projects/assets/img/events/gallery-3.webp',
                'order' => 3,
            ],
            [
                'title' => 'Hands-on Workshop',
                'description' => 'Hands-on cardiovascular intervention workshop',
                'image_path' => 'projects/assets/img/events/gallery-4.webp',
                'thumbnail_path' => 'projects/assets/img/events/gallery-4.webp',
                'order' => 4,
            ],
            [
                'title' => 'Poster Presentation',
                'description' => 'Poster and abstract presentation session',
                'image_path' => 'projects/assets/img/events/gallery-5.webp',
                'thumbnail_path' => 'projects/assets/img/events/gallery-5.webp',
                'order' => 5,
            ],
            [
                'title' => 'Faculty & Committee',
                'description' => 'Faculty members and organizing committee',
                'image_path' => 'projects/assets/img/events/gallery-6.webp',
                'thumbnail_path' => 'projects/assets/img/events/gallery-6.webp',
                'order' => 6,
            ],
            [
                'title' => 'Networking Session',
                'description' => 'Networking and collaboration session',
                'image_path' => 'projects/assets/img/events/gallery-7.webp',
                'thumbnail_path' => 'projects/assets/img/events/gallery-7.webp',
                'order' => 7,
            ],
            [
                'title' => 'Closing Ceremony',
                'description' => 'Closing ceremony of SYMCARD event',
                'image_path' => 'projects/assets/img/events/gallery-8.webp',
                'thumbnail_path' => 'projects/assets/img/events/gallery-8.webp',
                'order' => 8,
            ],
        ];

        foreach ($galleries as $gallery) {
            Gallery::create([
                'title' => $gallery['title'],
                'description' => $gallery['description'],
                'image_path' => $gallery['image_path'],
                'thumbnail_path' => $gallery['thumbnail_path'],
                'order' => $gallery['order'],
                'is_active' => true,
            ]);
        }
    }
}
