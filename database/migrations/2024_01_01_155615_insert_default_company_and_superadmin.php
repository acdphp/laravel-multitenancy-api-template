<?php

use App\Enums\Role;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $company = Company::factory()->create([
            'name' => config('app.name'),
        ]);

        User::factory()->create([
            'firstname' => 'Super',
            'lastname' => 'Admin',
            'email' => 'superadmin@email.com',
            'password' => Hash::make('a_little_harder_password'),
            'company_id' => $company->id,
            'role' => Role::SUPER_ADMIN,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
