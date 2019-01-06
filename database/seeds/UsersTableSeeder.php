<?php

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
        $users = [
            'ghali@dailyvocab.test',
            'ai@dailyvocab.test',
        ];

        foreach ($users as $user) {
            factory(\App\User::class)->create(['email' => $user]);
        }
    }
}
