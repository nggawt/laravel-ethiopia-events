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
        $this->call(UsersTableSeeder::class);
        $this->call(CustomersTableSeeder::class);
        $this->call(GalleryTableSeeder::class);
        $this->call(ArticleTableSeeder::class);
        
        $this->call(RolesTableSeeder::class);
        $this->call(AdminsRolesTableSeeder::class);
        $this->call(AdminTableSeeder::class);

        factory(App\Event::class, 50)->create();
    }
}
