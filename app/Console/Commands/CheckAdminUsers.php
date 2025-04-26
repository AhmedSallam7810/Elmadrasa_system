<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckAdminUsers extends Command
{
    protected $signature = 'check:admin-users';
    protected $description = 'Check for admin users in the database';

    public function handle()
    {
        $adminUsers = User::where('role', 'admin')->get();

        if ($adminUsers->isEmpty()) {
            $this->info('No admin users found in the database.');
            $this->info('To create an admin user, you can:');
            $this->info('1. Use the registration form and set role to "admin"');
            $this->info('2. Update an existing user using tinker:');
            $this->info('   php artisan tinker');
            $this->info('   $user = App\Models\User::find(1);');
            $this->info('   $user->role = "admin";');
            $this->info('   $user->save();');
        } else {
            $this->info('Found ' . $adminUsers->count() . ' admin users:');
            foreach ($adminUsers as $user) {
                $this->info('- ' . $user->name . ' (' . $user->email . ')');
            }
        }
    }
}
