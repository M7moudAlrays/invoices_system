<?php

namespace Database\Seeders;

use App\Models\user;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class CreateAdminUserSeeder extends Seeder
{
/**
* Run the database seeds.
*
* @return void
*/
public function run()
{
$user = user::create([
'name' => 'M.M.Alrays',
'email' => 'M.alrays@yahoo.com',
'password' => bcrypt('12345') ,
'roles_name' => ["owner"],
'Status' => 'مفعل'
]);
$role = Role::create(['name' => 'owner']);
$permissions = Permission::pluck('id','id')->all();

$role->syncPermissions($permissions);
$user->assignRole([$role->id]);
}
}