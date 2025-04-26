<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdateUserRole extends Command
{
    protected $signature = 'update:user-role {email} {role}';
    protected $description = 'Update a user\'s role';

    public function handle()
    {
        $email = $this->argument('email');
        $role = $this->argument('role');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error('User not found with email: ' . $email);
            return;
        }

        $user->role = $role;
        $user->save();

        $this->info('Successfully updated user ' . $user->name . ' role to: ' . $role);
    }
}
