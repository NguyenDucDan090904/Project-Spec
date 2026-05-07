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
        ]);
    }
}
