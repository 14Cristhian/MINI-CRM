<?php

namespace Tests\Feature\Clients;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // ── List ─────────────────────────────────────────────────────────────────

    public function test_authenticated_user_can_list_clients(): void
    {
        Client::factory(3)->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->getJson('/api/clients')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(3, 'data');
    }

    public function test_list_is_paginated(): void
    {
        Client::factory(15)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/clients?page=1')
            ->assertOk();

        $this->assertLessThanOrEqual(10, count($response->json('data')));
        $this->assertArrayHasKey('meta', $response->json());
    }

    public function test_list_can_be_filtered_by_status(): void
    {
        Client::factory(2)->activo()->create(['user_id' => $this->user->id]);
        Client::factory(1)->inactivo()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->getJson('/api/clients?status=activo')
            ->assertOk();

        $data = collect($response->json('data'));
        $this->assertTrue($data->every(fn ($c) => $c['status'] === 'activo'));
    }

    public function test_list_can_be_searched(): void
    {
        Client::factory()->create(['user_id' => $this->user->id, 'name' => 'Juan Pérez Especial']);
        Client::factory()->create(['user_id' => $this->user->id, 'name' => 'María López']);

        $response = $this->actingAs($this->user)
            ->getJson('/api/clients?search=Especial')
            ->assertOk();

        $this->assertCount(1, $response->json('data'));
        $this->assertStringContainsString('Especial', $response->json('data.0.name'));
    }

    public function test_list_can_be_sorted(): void
    {
        Client::factory()->create(['user_id' => $this->user->id, 'name' => 'Zorro Corp']);
        Client::factory()->create(['user_id' => $this->user->id, 'name' => 'Alfa Inc']);

        $response = $this->actingAs($this->user)
            ->getJson('/api/clients?sort_by=name&sort_dir=asc')
            ->assertOk();

        $names = collect($response->json('data'))->pluck('name')->values();
        $this->assertEquals('Alfa Inc', $names[0]);
    }

    // ── Create ───────────────────────────────────────────────────────────────

    public function test_user_can_create_a_client(): void
    {
        $payload = [
            'name'    => 'Nuevo Cliente',
            'email'   => 'nuevo@empresa.com',
            'company' => 'Empresa Test S.A.S',
            'status'  => 'prospecto',
        ];

        $this->actingAs($this->user)
            ->postJson('/api/clients', $payload)
            ->assertCreated()
            ->assertJsonPath('data.name', 'Nuevo Cliente');

        $this->assertDatabaseHas('clients', [
            'email'   => 'nuevo@empresa.com',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_create_requires_name_company_and_status(): void
    {
        $this->actingAs($this->user)
            ->postJson('/api/clients', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'company', 'status']);
    }

    public function test_create_rejects_invalid_status(): void
    {
        $this->actingAs($this->user)
            ->postJson('/api/clients', [
                'name'    => 'Test',
                'company' => 'Empresa',
                'status'  => 'invalido',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('status');
    }

    public function test_create_associates_client_with_authenticated_user(): void
    {
        $this->actingAs($this->user)
            ->postJson('/api/clients', [
                'name'    => 'Test',
                'email'   => 'test-assoc@empresa.com',
                'company' => 'Empresa',
                'status'  => 'activo',
            ])
            ->assertCreated();

        $this->assertDatabaseHas('clients', ['name' => 'Test', 'user_id' => $this->user->id]);
    }

    // ── Update ───────────────────────────────────────────────────────────────

    public function test_user_can_update_own_client(): void
    {
        $client = Client::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->putJson("/api/clients/{$client->id}", [
                'name'    => 'Nombre Actualizado',
                'email'   => $client->email,
                'company' => $client->company,
                'status'  => $client->status,
            ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Nombre Actualizado');
    }

    public function test_user_cannot_update_another_users_client(): void
    {
        $otherUser = User::factory()->create();
        $client    = Client::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($this->user)
            ->putJson("/api/clients/{$client->id}", [
                'name'    => 'Hack',
                'email'   => 'hack@test.com',
                'company' => 'X',
                'status'  => 'activo',
            ])
            ->assertForbidden();
    }

    // ── Delete ───────────────────────────────────────────────────────────────

    public function test_user_can_delete_own_client(): void
    {
        $client = Client::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->deleteJson("/api/clients/{$client->id}")
            ->assertOk();

        $this->assertSoftDeleted('clients', ['id' => $client->id]);
    }

    public function test_user_cannot_delete_another_users_client(): void
    {
        $otherUser = User::factory()->create();
        $client    = Client::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($this->user)
            ->deleteJson("/api/clients/{$client->id}")
            ->assertForbidden();
    }
}
