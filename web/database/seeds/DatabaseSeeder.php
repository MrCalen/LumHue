<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserTableSeeder::class);
        for ($i = 0; $i < 3; ++$i)
          DB::table('lights')
            ->insert([
              'id' => $i,
              'reachable' => 1,
              'on' => 1,
            ]);
    }
}
