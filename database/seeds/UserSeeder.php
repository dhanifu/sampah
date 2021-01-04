<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Si Admin',
            'email' => 'siadmin@sampah.com',
            'username' => 'siadmin',
            'password' => bcrypt('12345678'),
            'created_by' => Str::uuid()
        ]);
        $admin->assignRole('admin');

        $operator = User::create([
            'name' => 'Si Operator',
            'email' => 'sioperator@sampah.com',
            'username' => 'sioperator',
            'password' => bcrypt('12345678'),
            'created_by' => Str::uuid()
        ]);
        $operator->assignRole('operator');
    }
}
