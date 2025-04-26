<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListUsers extends Command
{
    protected $signature = 'list:users';
    protected $description = 'List all users in the database';

    public function handle()
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->info('No users found in the database.');
            $this->info('You need to create a user first through the registration form.');
        } else {
            $this->info('Found ' . $users->count() . ' users:');
            foreach ($users as $user) {
                $this->info('- ' . $user->name . ' (' . $user->email . ') - Role: ' . $user->role);
            }
        }
    }
}
