<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      User::create([
          'name' => 'seeder funcionando brabo',
          'email' => 'funcionamesmo@email.com',
          'password' => bcrypt('deucerto')
      ]);
    }
}
