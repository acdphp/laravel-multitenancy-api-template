<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 50; $i++) {
            $company = Company::factory()->create();

            foreach (Role::casesExcept(Role::SUPER_ADMIN) as $role) {
                User::factory()
                    ->create([
                        'company_id' => $company->id,
                        'role' => $role,
                    ]);
            }
        }
    }
}
