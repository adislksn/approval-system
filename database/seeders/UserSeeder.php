<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'servicekendaraandcmarunda@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Bayu Sudarmaji',
                'email' => 'bayu.sudarmaji@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Giri Fahmi',
                'email' => 'giri.fahmi@gmail.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Eric Bastian',
                'email' => 'eric.bastian@gmail.com',
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($users as $user) {
            $user = User::create($user);
            if ($user->name == 'Admin') {
                $user->assignRole('super_admin');
            } else {
                $user->assignRole('approver');
            }
        }
    }
}
