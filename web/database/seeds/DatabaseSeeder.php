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
          'password' => \Hash::make('coucou'),
          'email' => 'dagues_p@yaka.epita.fr',
          'meethue_token' => 'WGF4TXNzVUtJWXRrVGFSQXhlcWNrenhobk16UkIvRGgwNDJ6RmJydVhsWT0%3D'
        ]);
    }
}
