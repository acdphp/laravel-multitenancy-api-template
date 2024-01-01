<?php

namespace Tests\Feature\Middlewares;

use Acdphp\Multitenancy\Facades\Tenancy;
use App\Enums\Role;
use App\Models\Company;
use App\Models\User;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Tenancy::setTenantIdResolver(static fn () => Company::factory()->create()->id);
    }

    /**
     * @dataProvider provideRoles
     */
    public function test_role_authorization(Role $testRole): void
    {
        // Define dummy route with role authorization middleware to test
        \Route::middleware('role:'.$testRole->nameLower())
            ->get('/_test_route', fn () => 'ok');

        foreach (Role::cases() as $role) {
            // Create user role
            $user = User::factory()->create(['role' => $role]);

            // Call with specific user role
            $response = $this->actingAs($user)
                ->get('/_test_route');

            // Test responses
            if ($role === Role::SUPER_ADMIN || $role === $testRole) {
                $response->assertOk();
            } else {
                $response->assertForbidden();
            }
        }
    }

    public static function provideRoles(): array
    {
        return array_map(static fn ($case) => [$case], Role::casesNameLowerKeyed());
    }
}
