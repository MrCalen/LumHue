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
          'email' => 'dagues@yaka.epita.fr',
          'meethue_token' => 'alhzT29HZFptWEFtTTlNaTVGYlYvUHRCVHJHYjNPUWF3S2NSTjY0Znc3ND0%3D'
        ]);
    }
}
