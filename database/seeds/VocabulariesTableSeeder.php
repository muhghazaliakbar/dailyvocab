<?php

use Illuminate\Database\Seeder;

class VocabulariesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\User::all() as $user) {
            factory(\App\Vocabulary::class, 5)->create(['user_id' => $user->id]);
        }
    }
}
