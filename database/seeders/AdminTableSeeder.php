<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Admin;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('admins')->truncate();

       \DB::table('roles')->delete();
        \DB::table('permissions')->delete();
        $arr = [
            ['name' => 'View Buyers', 'guard_name' => 'admin'],
            ['name' => 'Add Buyers', 'guard_name' => 'admin'],
            ['name' => 'Update Buyers', 'guard_name' => 'admin'],
            ['name' => 'Delete Buyers', 'guard_name' => 'admin'],

            ['name' => 'View CMS Pages', 'guard_name' => 'admin'],
            ['name' => 'Add CMS Pages', 'guard_name' => 'admin'],
            ['name' => 'Update CMS Pages', 'guard_name' => 'admin'],
            ['name' => 'Delete CMS Pages', 'guard_name' => 'admin'],

            ['name' => 'View Contactus Log', 'guard_name' => 'admin'],
            ['name' => 'Delete Contactus Log', 'guard_name' => 'admin'],
            ['name' => 'Reply Contactus Log', 'guard_name' => 'admin'],

            ['name' => 'View Escrow Products', 'guard_name' => 'admin'],
            ['name' => 'Update Transaction Status', 'guard_name' => 'admin'],
            ['name' => 'View Dispute History', 'guard_name' => 'admin'],
            ['name' => 'Send Dispute Message', 'guard_name' => 'admin'],
            ['name' => 'Dispute Level 2', 'guard_name' => 'admin'],
            ['name' => 'Dispute Level 3', 'guard_name' => 'admin'],
            ['name' => 'View Transactions', 'guard_name' => 'admin'],

            ['name' => 'View Admin Logs', 'guard_name' => 'admin'],

            ['name' => 'View Messages', 'guard_name' => 'admin'],
            ['name' => 'Send Messages', 'guard_name' => 'admin'],

            ['name' => 'View Permissions', 'guard_name' => 'admin'],
            ['name' => 'Add Permissions', 'guard_name' => 'admin'],
            ['name' => 'Edit Permissions', 'guard_name' => 'admin'],
            ['name' => 'Delete Permissions', 'guard_name' => 'admin'],

            ['name' => 'View Reviews', 'guard_name' => 'admin'],
             ['name' => 'Update Reviews Status', 'guard_name' => 'admin'],
             ['name' => 'View Feedback Review', 'guard_name' => 'admin'],

            ['name' => 'View Roles', 'guard_name' => 'admin'],
            ['name' => 'Add Roles', 'guard_name' => 'admin'],
            ['name' => 'Update Roles', 'guard_name' => 'admin'],
            ['name' => 'Delete Roles', 'guard_name' => 'admin'],

            ['name' => 'View Sellers', 'guard_name' => 'admin'],
            ['name' => 'Add Sellers', 'guard_name' => 'admin'],
            ['name' => 'Edit Sellers', 'guard_name' => 'admin'],
            ['name' => 'Delete Sellers', 'guard_name' => 'admin'],
            ['name' => 'Update Sellers', 'guard_name' => 'admin'],
            ['name' => 'Approve ETL Sellers', 'guard_name' => 'admin'],

            ['name' => 'Update Site Settings', 'guard_name' => 'admin'],
            ['name' => 'Update Escrow Settings', 'guard_name' => 'admin'],
            ['name' => 'Update Announcement Settings', 'guard_name' => 'admin'],

            ['name' => 'View Tickets', 'guard_name' => 'admin'],
            ['name' => 'Send Tickets Reply', 'guard_name' => 'admin'],

            ['name' => 'View Templates', 'guard_name' => 'admin'],
            ['name' => 'Add Templates', 'guard_name' => 'admin'],
            ['name' => 'Update Templates', 'guard_name' => 'admin'],
            ['name' => 'Delete Templates', 'guard_name' => 'admin'],

            ['name' => 'View Admin Users', 'guard_name' => 'admin'],
            ['name' => 'Add Admin Users', 'guard_name' => 'admin'],
            ['name' => 'Update Admin Users', 'guard_name' => 'admin'],
            ['name' => 'Delete Admin Users', 'guard_name' => 'admin'],

            ['name' => 'View Faq Categories', 'guard_name' => 'admin'],
            ['name' => 'Add Faq Category', 'guard_name' => 'admin'],
            ['name' => 'Update Faq Category', 'guard_name' => 'admin'],
            ['name' => 'Delete Faq Category', 'guard_name' => 'admin'],

            ['name' => 'View Faqs', 'guard_name' => 'admin'],
            ['name' => 'Add Faq', 'guard_name' => 'admin'],
            ['name' => 'Update Faq', 'guard_name' => 'admin'],
            ['name' => 'Delete Faq', 'guard_name' => 'admin'],

        ];
        \DB::table('permissions')->insert($arr);

        $role = Role::create(['name' => 'super-admin', 'guard_name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        $user = Admin::create([
            'id' => 1,
            'firstname' => 'Escrow',
            'lastname' => 'Admin',
            'email' => 'admin@safeland.com',
            'password' => Hash::make('Admin@123'),
            'is_active' => 1,
        ]);

        $user->assignRole($role);
    }
}
