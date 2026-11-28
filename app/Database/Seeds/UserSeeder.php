<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        $users = [
            [
                'nama'      => 'Pelapor',
                'email'     => 'pelapor@gmail.com',
                'nim'       => 'F1G125061',
                'password'  => 'password123',
                'role'      => 'pelapor',
                'is_active' => 1,
            ],
            [
                'nama'      => 'Petugas Kebersihan',
                'email'     => 'petugas@gmail.com',
                'nim'       => 'F1G125000',
                'password'  => 'password123',
                'role'      => 'petugas',
                'is_active' => 1,
            ],
            [
                'nama'      => 'Administrator',
                'email'     => 'admin@gmail.com',
                'nim'       => 'F1G125005',
                'password'  => 'password123',
                'role'      => 'admin',
                'is_active' => 1,
            ],
        ];

        foreach ($users as $user) {
            $email = $user['email'];

            // Mengubah password menjadi hash
            $user['password'] = password_hash(
                $user['password'],
                PASSWORD_DEFAULT
            );

            $user['updated_at'] = date('Y-m-d H:i:s');

            // Memeriksa apakah email sudah terdaftar
            $existingUser = $db->table('users')
                ->where('email', $email)
                ->get()
                ->getRowArray();

            if ($existingUser) {
                // Memperbarui akun yang sudah ada
                $db->table('users')
                    ->where('email', $email)
                    ->update($user);
            } else {
                // Membuat akun baru
                $user['created_at'] = date('Y-m-d H:i:s');

                $db->table('users')->insert($user);
            }
        }
    }
}