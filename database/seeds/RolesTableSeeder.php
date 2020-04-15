<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder
{
	
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // DB::table('roles')->truncate();
       $permissins = json_encode(array('create', 'update', 'delete'));
       Role::create([
            
            "name" => "Admin",
            "slug" => "admin",
            "permissions" => $permissins
        ]);

        $permissins = json_encode(array('create', 'update'));
        Role::create([
            
            "name" => "Author",
            "slug" => "author",
            "permissions" => $permissins
        ]);
        
        $permissins = json_encode(array('update'));
        Role::create([
            
            "name" => "Writer",
            "slug" => "writer",
            "permissions" => $permissins
        ]);
    }
}
