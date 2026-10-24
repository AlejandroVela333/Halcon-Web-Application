<?php

use App\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'Administrator' => 'Registers users and assigns roles.',
            'Sales' => 'Registers customer orders.',
            'Purchasing' => 'Manages purchases of missing materials.',
            'Warehouse' => 'Checks stock and prepares orders.',
            'Route' => 'Manages delivery and photographic evidence.',
        ];

        foreach ($roles as $name => $description) {
            Role::updateOrCreate(['name' => $name], ['description' => $description]);
        }
    }
}
