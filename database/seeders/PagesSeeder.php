<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Page::create(['title' => 'Welcome', 'content' => 'Welcome to page 1']);
        Page::create(['title' => 'Welcome consultation', 'content' => 'Get consultation']);
    }
}
