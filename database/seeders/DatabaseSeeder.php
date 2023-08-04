<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Post;
use App\Models\User;
use App\Models\Blacklist;
use App\Models\Suggestion;
use App\Models\Booking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'administrator@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);
        DB::table('users')->insert([
            'name' => 'Cipta Dwipajaya',
            'email' => 'ciptacoding@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);
        
        User::factory(30)->create();
        Post::factory(30)->create();
        Suggestion::factory(20)->create();
        Booking::factory(40)->create();
    }
}
