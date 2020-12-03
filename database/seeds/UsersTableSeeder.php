<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User([
            'first_name' => 'Vadecom',
            'last_name' => 'Admin',
            'email' => 'admin@vadecom.net',
            'password' => 'VP@zzw00rd',
            'status' => 'active',
            'role' => 'admin',
        ]);

        $user->save();
    }
}
