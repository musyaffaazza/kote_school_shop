<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $users = [
            [
                'id_user' => 1,
                'nama' => 'Admin Kote',
                'email' => 'admin@kote.com',
                'password' => Hash::make('admin123'),
                'no_hp' => '081234567810',
                'nis' => 'ADM0000001',
                'foto_identitas' => 'uploads/ktp/admin.jpg',
                'kelas' => '-',
                'jenis_kelamin' => 'L',
                'role' => 'admin',
                'jabatan' => 'Store Manager',
                'alamat' => 'Bogor',
            ],
            [
                'id_user' => 2,
                'nama' => 'Pramulyaning',
                'email' => 'pramulyaning@gmail.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567806',
                'nis' => '3201010101010006',
                'foto_identitas' => 'uploads/ktp/pram.jpg',
                'kelas' => 'XI RPL 1',
                'jenis_kelamin' => 'L',
                'role' => 'pelanggan',
                'jabatan' => null,
                'alamat' => 'Bogor',
            ],
            [
                'id_user' => 3,
                'nama' => 'Jessica',
                'email' => 'jessica@gmail.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567807',
                'nis' => '3201010101010007',
                'foto_identitas' => 'uploads/ktp/jessica.jpg',
                'kelas' => 'XI RPL 2',
                'jenis_kelamin' => 'P',
                'role' => 'pelanggan',
                'jabatan' => null,
                'alamat' => 'Bogor',
            ],
            [
                'id_user' => 4,
                'nama' => 'Syifanny',
                'email' => 'syifanny@gmail.com',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567808',
                'nis' => '3201010101010008',
                'foto_identitas' => 'uploads/ktp/syifa.jpg',
                'kelas' => 'XII RPL 1',
                'jenis_kelamin' => 'P',
                'role' => 'pelanggan',
                'jabatan' => null,
                'alamat' => 'Bogor',
            ],
            [
                'id_user' => 5,
                'nama' => 'Fio',
                'email' => 'fio@gmail.com',
                'password' => Hash::make('password123'),
                'no_hp' => '0812-3456-7890',
                'nis' => 'KRY0000001',
                'foto_identitas' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&h=150&q=80',
                'kelas' => '-',
                'jenis_kelamin' => 'L',
                'role' => 'karyawan',
                'jabatan' => 'Barista',
                'alamat' => 'Bogor',
            ],
            [
                'id_user' => 6,
                'nama' => 'Azza',
                'email' => 'azza@gmail.com',
                'password' => Hash::make('password123'),
                'no_hp' => '0821-1377-8035',
                'nis' => 'KRY0000002',
                'foto_identitas' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&h=150&q=80',
                'kelas' => '-',
                'jenis_kelamin' => 'P',
                'role' => 'karyawan',
                'jabatan' => 'Kasir',
                'alamat' => 'Bogor',
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }
    }
}
