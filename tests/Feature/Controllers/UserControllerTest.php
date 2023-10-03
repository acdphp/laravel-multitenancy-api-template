<?php

namespace Feature\Controllers;

use App\Enums\Role;
use App\Http\Middleware\RoleAuthorization;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\DataResponses\UserResponse;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    private User $user1;

    private User $user2;

    public function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([RoleAuthorization::class]);

        $company = Company::factory()->create([
            'name' => 'My Company',
        ]);
        \Tenancy::setTenant($company);

        $this->user1 = User::factory()->create([
            'email' => 'admin@email.com',
            'role' => Role::ADMIN,
        ]);

        $this->user2 = User::factory()->create([
            'email' => 'staff@email.com',
            'role' => Role::STAFF,
        ]);
    }

    public function test_index(): void
    {
        $this->actingAs($this->user2)
            ->get('/api/users')
            ->assertOk()
            ->assertJsonStructure(
                $this->paginatedStructure(UserResponse::resource($this->user2))
            );
    }

    public function test_show(): void
    {
        $this->actingAs($this->user1)
            ->get('/api/users/'.$this->user1->id)
            ->assertOk()
            ->assertJson(
                $this->wrappedData(UserResponse::resource($this->user1))
            );
    }

    public function test_update_by_admin(): void
    {
        $this->actingAs($this->user1)
            ->put('/api/users/'.$this->user2->id, [
                'firstname' => 'New name',
                'lastname' => 'New lastname',
                'email' => 'new@email.com',
                'role' => $this->user2->role->value,
            ])
            ->assertOk()
            ->assertJson(
                $this->wrappedData(UserResponse::resource($this->user2, [
                    'firstname' => 'New name',
                    'lastname' => 'New lastname',
                    'email' => 'new@email.com',
                ]))
            );
    }

    public function test_destroy(): void
    {
        $this->actingAs($this->user1)
            ->delete('/api/users/'.$this->user2->id)
            ->assertNoContent();
    }

    public function test_restore(): void
    {
        $this->user2->delete();

        $this->actingAs($this->user1)
            ->post('/api/users/'.$this->user2->id.'/restore')
            ->assertOk()
            ->assertJson(
                $this->wrappedData(UserResponse::resource($this->user2))
            );
    }

    public function test_store_avatar(): void
    {
        Storage::fake();

        $this->actingAs($this->user1)
            ->post('/api/users/'.$this->user2->id.'/avatar', [
                'image' => UploadedFile::fake()->image('avatar.jpg'),
            ])
            ->assertNoContent();

        Storage::disk()->assertExists($this->user2->fresh()->avatar);
    }

    public function test_mine(): void
    {
        $this->actingAs($this->user2)
            ->get('/api/users/mine')
            ->assertJson(
                $this->wrappedData(UserResponse::resource($this->user2))
            );
    }

    public function test_update_mine(): void
    {
        $this->actingAs($this->user2)
            ->put('/api/users/mine', [
                'firstname' => 'New name',
                'lastname' => 'New lastname',
                'email' => 'new@email.com',
            ])
            ->assertOk()
            ->assertJson(
                $this->wrappedData(UserResponse::resource($this->user2, [
                    'firstname' => 'New name',
                    'lastname' => 'New lastname',
                    'email' => 'new@email.com',
                ]))
            );
    }

    public function test_store_avatar_mine(): void
    {
        Storage::fake();

        $this->actingAs($this->user2)
            ->post('/api/users/mine/avatar', [
                'image' => UploadedFile::fake()->image('avatar.jpg'),
            ])
            ->assertNoContent();

        Storage::disk()->assertExists($this->user2->fresh()->avatar);
    }
}
