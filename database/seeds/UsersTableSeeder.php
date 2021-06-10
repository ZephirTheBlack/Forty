<?php

use Illuminate\Database\Seeder;
use App\User;
use \App\Models\Status;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        Status::truncate();

        factory(User::class)->create(['name'=>'KevinDev','email'=> 'kcrodsua21@gmail.com']);
        factory(Status::class, 10)->create();
    }
}
