<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        foreach (range(1, 10) as $item) {
            User::create([
                'name' => 'Superadmin' . $item,
                'email' => 'admin' . $item . '@info.com',
                'phone' => 12345678 . ($item - 1),
                'identity_number' => 12345678 . ($item - 1),
                'user_type' => User::SUPERADMIN,
                'phone_verified_at' => now(),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'password' => '123456789',
            ]);
        }
    }
}
