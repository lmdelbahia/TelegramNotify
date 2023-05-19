<?php

namespace Database\Seeders;

use App\Helpers\FieldsOptions\RoleFieldOptions;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrador del Sistema',
            'email' => 'admintelnotify@example.com',
            'password' => Hash::make('admin*2023'),
            'role' => RoleFieldOptions::ADMIN->value
        ]);
    }
}
