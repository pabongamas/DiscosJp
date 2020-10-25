<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_user = Role::where('name', 'user')->first();
        $role_admin = Role::where('name', 'admin')->first();
        $user = new User();
        $user->name = 'User';
        $user->fullname = 'jhon jairo pabon gamas';
        $user->email = 'user@example.com';
        $user->birthdate = '1996-07-24';
        $user->gender=1;
        $user->password = bcrypt('secret');
        $user->save();
        $user->roles()->attach($role_user);
        $user = new User();
        $user->name = 'Admin';
        $user->fullname = 'Adminsito polvora mi papa';
        $user->email = 'admin@example.com';
        $user->birthdate = '1996-07-24';
        $user->gender=1;
        $user->password = bcrypt('secret');
        $user->save();
        $user->roles()->attach($role_admin);
    }
}
