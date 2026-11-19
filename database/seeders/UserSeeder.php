<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Librarian / Admin
        User::firstOrCreate(
            ['email' => 'librarian@example.com'],
            [
                'name' => 'Librarian',
                'username' => 'librarian',
                'role' => 'librarian',
                'grade' => 'staff',
                'password' => Hash::make('password'),
            ]
        );

        // Borrower sample
        User::firstOrCreate(
            ['email' => 'borrower@example.com'],
            [
                'name' => 'Borrower User',
                'username' => 'borrower',
                'role' => 'borrower',
                'grade' => 'bachelor',
                'password' => Hash::make('password'),
            ]
        );

        // Additional random users
        User::factory(10)->create();
    }
}
