<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Modules\Dashboard\Models\Permission;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        \Schema::disableForeignKeyConstraints();
        Permission::query()->truncate();
        \Schema::enableForeignKeyConstraints();
        foreach (Route::getRoutes() as $index => $route) {
            if (
                !empty($route->getName()) &&
                !Str::contains($route->getName(), ['sanctum', 'ignition']) &&
                !in_array($route->getName(), ['dashboard.', 'vendor.', 'telescope', '', ' ']) &&
                !str_contains($route->getName(), '.edit')
            ) {
                $data[] = [
                    'route' => $route->getName(),
                    'name' => str_replace('.', '_', $route->getName()),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
        Permission::query()->insert($data);
    }
}
