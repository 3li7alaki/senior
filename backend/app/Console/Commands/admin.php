<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class admin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user with email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::query()->create([
            'name' => 'admin',
            'email' => 'admin@tafaol.com',
            'password' => Hash::make('Tafaol@Admin0004'),
            'type' => 'super_admin',
            'is_active' => true,
        ]);
        $this->info('Admin created successfully.');
    }
}
