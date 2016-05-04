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
      DB::table('users')
        ->insert([
          'name' => 'root',
          'password' => \Hash::make(env("DB_PWD", "coucou")),
          'email' => 'dagues_p@yaka.epita.fr',
          'meethue_token' => env('DB_MEETHUE', "coucou"),
        ]);
    }
}
