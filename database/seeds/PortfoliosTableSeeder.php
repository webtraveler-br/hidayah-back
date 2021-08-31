<?php

use Illuminate\Database\Seeder;
use App\Portfolio;

class PortfoliosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      factory(Portfolio::class, 10)->create();
    }
}
