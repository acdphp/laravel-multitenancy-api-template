<?php

namespace Feature\Controllers;

use App\Enums\Role;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\DataResponses\UserResponse;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    private User $admin;

    private User $staff;

    private Company $company;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = Company::factory()->create([
            'name' => 'My Company',
        ]);
        \Tenancy::setTenant($this->company);

        $this->admin = User::factory()->create([
            'email' => 'admin@email.com',
            'role' => Role::ADMIN,
        ]);

        $this->staff = User::factory()->create([
            'email' => 'staff@email.com',
            'role' => Role::STAFF,
        ]);
    }

    public function test_index(): void
    {
        $this->actingAs($this->staff)
            ->get('/api/users')
            ->assertOk()
            ->assertJsonStructure(
                $this->paginatedStructure(UserResponse::resource($this->staff))
            );
    }

    public function test_show(): void
    {
        $this->actingAs($this->admin)
            ->get('/api/users/'.$this->admin->id)
            ->assertOk()
            ->assertJson(
                $this->wrappedData(UserResponse::resource($this->admin))
            );
    }

    public function test_update_by_admin(): void
    {
        $this->actingAs($this->admin)
            ->put('/api/users/'.$this->staff->id, [
                'firstname' => 'New name',
                'lastname' => 'New lastname',
                'email' => 'new@email.com',
                'role' => $this->staff->role->value,
            ])
            ->assertOk()
            ->assertJson(
                $this->wrappedData(UserResponse::resource($this->staff, [
                    'firstname' => 'New name',
                    'lastname' => 'New lastname',
                    'email' => 'new@email.com',
                ]))
            );
    }

    public function test_destroy(): void
    {
        $this->actingAs($this->admin)
            ->delete('/api/users/'.$this->staff->id)
            ->assertNoContent();
    }

    public function test_restore(): void
    {
        $this->staff->delete();

        $this->actingAs($this->admin)
            ->post('/api/users/'.$this->staff->id.'/restore')
            ->assertOk()
            ->assertJson(
                $this->wrappedData(UserResponse::resource($this->staff))
            );
    }

    public function test_store_avatar(): void
    {
        Storage::fake();

        $this->actingAs($this->admin)
            ->post('/api/users/'.$this->staff->id.'/avatar', [
                'image' => UploadedFile::fake()->image('avatar.jpg'),
            ])
            ->assertNoContent();

        Storage::disk()->assertExists($this->staff->fresh()->avatar);
    }

    public function test_mine(): void
    {
        $this->actingAs($this->staff)
            ->get('/api/users/mine')
            ->assertJson(
                $this->wrappedData(UserResponse::resource($this->staff))
            );
    }

    public function test_update_mine(): void
    {
        $this->actingAs($this->staff)
            ->put('/api/users/mine', [
                'firstname' => 'New name',
                'lastname' => 'New lastname',
                'email' => 'new@email.com',
            ])
            ->assertOk()
            ->assertJson(
                $this->wrappedData(UserResponse::resource($this->staff, [
                    'firstname' => 'New name',
                    'lastname' => 'New lastname',
                    'email' => 'new@email.com',
                ]))
            );
    }

    public function test_store_avatar_mine(): void
    {
        Storage::fake();

        $this->actingAs($this->staff)
            ->post('/api/users/mine/avatar', [
                'image' => UploadedFile::fake()->image('avatar.jpg'),
            ])
            ->assertNoContent();

        Storage::disk()->assertExists($this->staff->fresh()->avatar);
    }
}
