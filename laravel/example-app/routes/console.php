<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:create {email} {name} {--password=}', function () {
    $email = (string) $this->argument('email');
    $name = (string) $this->argument('name');
    $password = (string) ($this->option('password') ?: 'admin12345');

    $user = User::query()->updateOrCreate(
        ['email' => $email],
        [
            'name' => $name,
            'password' => $password,
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
            'api_token' => null,
        ],
    );

    $this->info('Admin user is ready.');
    $this->line("Name: {$user->name}");
    $this->line("Email: {$user->email}");
    $this->line("Role: {$user->role}");
    $this->line("Password: {$password}");
})->purpose('Create or update an admin user');
