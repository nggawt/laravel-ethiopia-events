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
       $permissins = json_encode(array(
            'create' => true,
            'update' => true, 
            'delete' => true
        ));

       Role::create([
            
            "name" => "Admin",
            "slug" => "admin",
            "permissions" => $permissins
        ]);

        $permissins = json_encode(array(
            'create' => true, 
            'update' => true
        ));

        Role::create([
            
            "name" => "Author",
            "slug" => "author",
            "permissions" => $permissins
        ]);
        
        $permissins = json_encode(array('update' => true));
        
        Role::create([
            
            "name" => "Editor",
            "slug" => "editor",
            "permissions" => $permissins
        ]);
    }
}
