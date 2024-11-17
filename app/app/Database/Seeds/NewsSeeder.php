<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class NewsSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();

        $news1 = [
            'title' => $faker->sentence(6),
            'slug' => 'news-1',
            'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.',
            'picture' => 'news1.jpg',
            'category_id' => 1,
            'user_id' => 1,
        ];

        $news2 = [
            'title' => $faker->sentence(6),
            'slug' => 'news-2',
            'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.',
            'picture' => 'news2.jpg',
            'category_id' => 1,
            'user_id' => 1,
        ];
        
        $news3 = [
            'title' => $faker->sentence(6),
            'slug' => 'news-3',
            'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.',
            'picture' => 'news3.jpg',
            'category_id' => 1,
            'user_id' => 1,
        ];

        $news4 = [
            'title' => $faker->sentence(6),
            'slug' => 'news-4',
            'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum.',
            'picture' => 'news4.jpg',
            'category_id' => 1,
            'user_id' => 1,
        ];

        $this->db->table('news')->insert($news1);
        $this->db->table('news')->insert($news2);
        $this->db->table('news')->insert($news3);
        $this->db->table('news')->insert($news4);
    }
}
