<?php

use Illuminate\Database\Seeder;
use App\AdminRole;

class AdminsRolesTableSeeder extends Seeder
{
	
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // DB::table('admin_roles')->truncate();
        // admin_id', 'role_id'
        AdminRole::create([
            "admin_id" => "1",
            "role_id" => "1",
        ]);

        AdminRole::create([
            
            "admin_id" => "2",
            "role_id" => "2",
        ]);

        AdminRole::create([
            
            "admin_id" => "3",
            "role_id" => "3",
        ]);
    }
}
