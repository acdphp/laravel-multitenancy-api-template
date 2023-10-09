<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Tests\DataResponses\UserResponse;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function test_register(): void
    {
        $response = $this->post('/api/auth/register', [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@email.com',
            'password' => 'password',
            'company' => [
                'name' => 'Your Company',
            ],
        ])->assertCreated();

        // Should create a company
        $companyId = $response->json('data.company.id');
        $this->assertDatabaseHas('companies', [
            'id' => $companyId,
            'name' => 'Your Company',
        ]);

        // Should create a user with a company and a role
        $userId = $response->json('data.id');
        $this->assertDatabaseHas('users', [
            'id' => $userId,
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@email.com',
            'role' => config('auth.default_registration_role')->value,
            'company_id' => $companyId,
        ]);

        // Response should be using user resource
        $user = User::find($userId);
        $response->assertJson(
            $this->wrappedData(UserResponse::resource($user))
        );
    }
}
