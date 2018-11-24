<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, 25)->create()->each(function ($u) {
          for ($i=0; $i <= 3; $i++) {
            $u->posts()->save(factory(App\Post::class)->make());
          }
        });
    }
}
