<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SubscriberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Subscriber::insert([
            ['name' => 'Nguyễn Văn A', 'email' => 'vana@gmail.com', 'created_at' => now()],
            ['name' => 'Trần Thị B', 'email' => 'thib@gmail.com', 'created_at' => now()],
            ['name' => 'Lê Văn C', 'email' => 'vanc@gmail.com', 'created_at' => now()],
            ['name' => 'Elenora Ankunding Jr.', 'email' => 'garrett.mueller@example.com', 'created_at' => now()],
            ['name' => 'Prof. Zechariah Wolf V', 'email' => 'gulgowski.brady@example.com', 'created_at' => now()],
            ['name' => 'Breanna Abbott V', 'email' => 'idach@example.org', 'created_at' => now()],
            ['name' => 'Pasquale Emard', 'email' => 'lcollins@example.com', 'created_at' => now()],
            ['name' => 'Fred Goodwin', 'email' => 'kuvalis.amber@example.org', 'created_at' => now()],
            ['name' => 'Wilfredo Orn', 'email' => 'doyle.lina@example.com', 'created_at' => now()],
            ['name' => 'Afton Kirlin', 'email' => 'deondre.schinner@example.net', 'created_at' => now()],
            ['name' => 'Prof. Pedro Haag IV', 'email' => 'kemmer.neal@example.net', 'created_at' => now()],
            ['name' => 'Dr. Maude Pfannerstill V', 'email' => 'emiller@example.org', 'created_at' => now()],
            ['name' => 'Jennings Johnston', 'email' => 'dtoy@example.com', 'created_at' => now()],
        ]);
    }
}
