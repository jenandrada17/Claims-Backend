<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ✅ Create or update a default admin user
        // Using updateOrCreate makes it idempotent — if the admin already exists, it updates instead of duplicating.
        $admin = User::updateOrCreate(
            ['email' => env('DEFAULT_ADMIN_EMAIL', 'admin@claims.com')], // unique identifier
            [
                'name' => env('DEFAULT_ADMIN_NAME', 'System Administrator'),
                'password' => env('DEFAULT_ADMIN_PASSWORD', 'fu2zyHelp75'), // will be auto-hashed if you use the 'hashed' cast
                'is_active' => true,
                'is_protected' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info("✅ Default admin user ready: {$admin->email}");
        $this->command->warn("Password: " . env('DEFAULT_ADMIN_PASSWORD', 'fu2zyHelp75'));

        // Optionally, seed roles, permissions, etc., later here.
        // Example:
        // $this->call([
        //     RolesSeeder::class,
        //     PermissionsSeeder::class,
        // ]);
    }
}
