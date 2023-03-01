<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Pavel Zarubin',
            'email' => 'pavel@orendat.ru',
            'password' => Hash::make('admin'),
            'nick' => 'admin',
            'sudo' => true,
            'photo' => 'avatars/1/285870852_1097458310848547_5299414829263004546_n.jpg'
        ]);
    }
}
