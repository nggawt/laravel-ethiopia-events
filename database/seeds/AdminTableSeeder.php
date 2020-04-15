<?php

use Illuminate\Database\Seeder;
use App\Admin;

class AdminTableSeeder extends Seeder
{
	
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        // DB::table('admins')->truncate();

       $admin = Admin::create([
            "name" => "ngga",
            "email" => "admin@gmail.com",
            "password" => bcrypt("qweqwe")
        ]);

        $admin->roles()->attach(1);

        $author = Admin::create([
            "name" => "buzzi",
            "email" => "author@gmail.com",
            "password" => bcrypt("qweqwe")
        ]);
        $author->roles()->attach(2);

        $writer = Admin::create([
            "name" => "nati",
            "email" => "writer@gmail.com",
            "password" => bcrypt("qweqwe")
        ]);
        $writer->roles()->attach(3);
    }
}
