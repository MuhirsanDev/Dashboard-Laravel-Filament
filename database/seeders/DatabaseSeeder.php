<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $user1 = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admintest@example.com',
        ]);

        $user2 = User::factory()->create([
            'name' => 'Editing',
            'email' => 'edit@example.com',
        ]);

        $role = Role::create(['name' => 'ADMIN']);
        $user1->assignRole($role);

        $role = Role::create(['name' => 'EDITOR']);
        $user2->assignRole($role);
    }
}
