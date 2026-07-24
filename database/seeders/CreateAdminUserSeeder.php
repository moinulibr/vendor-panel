<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
           'roles.view',
           'roles.create',
           'roles.edit',
           'roles.delete',
           'products.view',
           'products.create',
           'products.edit',
           'products.delete',

           'users.view',
           'users.create',
           'users.edit',
           'users.delete',

           'permissions.view',
           'permissions.create',
           'permissions.edit',
           'permissions.delete',
           'dashboard.view',

        ];
        
        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }


        $user = User::create([
            'name' => 'Super Admin', 
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456')
        ]);
        
        $role = Role::updateOrCreate(['name' => 'Admin'],['guard_name'=>'web']);
         
        $permissions = Permission::pluck('id','id')->all();
       
        $role->syncPermissions($permissions);
         
        $user->assignRole([$role->id]);
    }
}