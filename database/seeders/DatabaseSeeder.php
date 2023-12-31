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
use Illuminate\Support\Str;

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
            'nik' => '1234567890123456',
            'role' => 'admin',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        
        User::factory(100)->create();
        Post::factory(30)->create();
        Suggestion::factory(20)->create();
        Booking::factory(500)->create();
        Booking::factory()->TodayFactory()->count(100)->create();
    }
}
