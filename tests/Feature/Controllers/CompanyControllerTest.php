<?php

namespace Tests\Feature\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\DataResponses\CompanyResponse;
use Tests\TestCase;

class CompanyControllerTest extends TestCase
{
    private User $user;

    private Company $company;

    public function setUp(): void
    {
        parent::setUp();

        $this->company = Company::factory()->create([
            'name' => 'My Company',
        ]);

        $this->user = User::factory()->create([
            'email' => 'user@email.com',
        ]);
    }

    public function test_index(): void
    {
        $response = $this->actingAs($this->user)
            ->get('/api/companies')
            ->assertOk()
            ->assertJsonStructure(
                $this->paginatedStructure(CompanyResponse::resource($this->company))
            );
    }

    public function test_show(): void
    {
        $this->actingAs($this->user)
            ->get('/api/companies/'.$this->company->id)
            ->assertOk()
            ->assertJson(
                $this->wrappedData(CompanyResponse::resource($this->company))
            );
    }

    public function test_update(): void
    {
        $this->actingAs($this->user)
            ->put('/api/companies/'.$this->company->id, [
                'name' => 'New name',
            ])
            ->assertOk()
            ->assertJson(
                $this->wrappedData(CompanyResponse::resource($this->company, [
                    'name' => 'New name',
                ]))
            );
    }

    public function test_destroy(): void
    {
        $this->actingAs($this->user)
            ->delete('/api/companies/'.$this->company->id)
            ->assertNoContent();
    }

    public function test_restore(): void
    {
        $this->actingAs($this->user)
            ->post('/api/companies/'.$this->company->id.'/restore')
            ->assertOk()
            ->assertJson(
                $this->wrappedData(CompanyResponse::resource($this->company))
            );
    }

    public function test_store_logo(): void
    {
        Storage::fake();

        $this->actingAs($this->user)
            ->post('/api/companies/'.$this->company->id.'/logo', [
                'image' => UploadedFile::fake()->image('logo.jpg'),
            ])
            ->assertNoContent();

        Storage::disk()->assertExists($this->company->fresh()->logo);
    }

    public function test_mine(): void
    {
        $this->actingAs($this->user)
            ->get('/api/companies/mine')
            ->assertJson(
                $this->wrappedData(CompanyResponse::resource($this->user->company))
            );
    }

    public function test_update_mine(): void
    {
        $this->actingAs($this->user)
            ->put('/api/companies/mine', [
                'name' => 'New name',
            ])
            ->assertOk()
            ->assertJson(
                $this->wrappedData(CompanyResponse::resource($this->user->company, [
                    'name' => 'New name',
                ]))
            );
    }

    public function test_store_logo_mine(): void
    {
        Storage::fake();

        $this->actingAs($this->user)
            ->post('/api/companies/mine/logo', [
                'image' => UploadedFile::fake()->image('logo.jpg'),
            ])
            ->assertNoContent();

        Storage::disk()->assertExists($this->user->company->fresh()->logo);
    }

    public function test_switch(): void
    {
        $this->actingAs($this->user)
            ->post('/api/companies/'.$this->company->id.'/switch')
            ->assertOk()
            ->assertJson(
                $this->wrappedData(CompanyResponse::resource($this->company))
            );
    }
}
