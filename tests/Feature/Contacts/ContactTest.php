<?php

namespace Tests\Feature\Contacts;

use App\Models\Client;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    private User   $user;
    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user   = User::factory()->create();
        $this->client = Client::factory()->create(['user_id' => $this->user->id]);
    }

    // ── List ─────────────────────────────────────────────────────────────────

    public function test_can_list_contacts_for_a_client(): void
    {
        Contact::factory(3)->create([
            'client_id' => $this->client->id,
            'user_id'   => $this->user->id,
        ]);

        $this->actingAs($this->user)
            ->getJson("/api/clients/{$this->client->id}/contacts")
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    // ── Create ───────────────────────────────────────────────────────────────

    public function test_can_create_a_contact(): void
    {
        $this->actingAs($this->user)
            ->postJson("/api/clients/{$this->client->id}/contacts", [
                'name'  => 'Ana Gómez',
                'email' => 'ana@empresa.com',
            ])
            ->assertCreated()
            ->assertJsonPath('data.name', 'Ana Gómez');

        $this->assertDatabaseHas('contacts', [
            'name'      => 'Ana Gómez',
            'client_id' => $this->client->id,
            'user_id'   => $this->user->id,
        ]);
    }

    public function test_create_requires_name(): void
    {
        $this->actingAs($this->user)
            ->postJson("/api/clients/{$this->client->id}/contacts", [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('name');
    }

    // ── Primary contact rule ─────────────────────────────────────────────────

    public function test_only_one_primary_contact_per_client(): void
    {
        // Create first primary
        $first = Contact::factory()->create([
            'client_id'  => $this->client->id,
            'user_id'    => $this->user->id,
            'is_primary' => true,
        ]);

        // Create second primary — should demote the first
        $this->actingAs($this->user)
            ->postJson("/api/clients/{$this->client->id}/contacts", [
                'name'       => 'Segundo Primario',
                'email'      => 'segundo-primario@empresa.com',
                'is_primary' => true,
            ])
            ->assertCreated();

        // First contact should no longer be primary
        $this->assertDatabaseHas('contacts', ['id' => $first->id, 'is_primary' => false]);

        // Only one primary should exist
        $primaryCount = Contact::where('client_id', $this->client->id)
            ->where('is_primary', true)
            ->count();

        $this->assertEquals(1, $primaryCount);
    }

    public function test_primary_contact_on_one_client_does_not_affect_another(): void
    {
        $otherClient = Client::factory()->create(['user_id' => $this->user->id]);

        $primaryOnOther = Contact::factory()->create([
            'client_id'  => $otherClient->id,
            'user_id'    => $this->user->id,
            'is_primary' => true,
        ]);

        // Setting a primary on our client should not affect the other client
        $this->actingAs($this->user)
            ->postJson("/api/clients/{$this->client->id}/contacts", [
                'name'       => 'Primario en cliente 1',
                'email'      => 'primario-cliente1@empresa.com',
                'is_primary' => true,
            ])
            ->assertCreated();

        $this->assertDatabaseHas('contacts', ['id' => $primaryOnOther->id, 'is_primary' => true]);
    }

    // ── Update ───────────────────────────────────────────────────────────────

    public function test_can_update_a_contact(): void
    {
        $contact = Contact::factory()->create([
            'client_id' => $this->client->id,
            'user_id'   => $this->user->id,
        ]);

        $this->actingAs($this->user)
            ->putJson("/api/clients/{$this->client->id}/contacts/{$contact->id}", [
                'name'  => 'Nombre Actualizado',
                'email' => $contact->email,
            ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Nombre Actualizado');
    }

    // ── Delete ───────────────────────────────────────────────────────────────

    public function test_can_delete_a_contact(): void
    {
        $contact = Contact::factory()->create([
            'client_id' => $this->client->id,
            'user_id'   => $this->user->id,
        ]);

        $this->actingAs($this->user)
            ->deleteJson("/api/clients/{$this->client->id}/contacts/{$contact->id}")
            ->assertOk();

        $this->assertSoftDeleted('contacts', ['id' => $contact->id]);
    }

    public function test_user_cannot_manage_contacts_of_another_users_client(): void
    {
        $otherUser   = User::factory()->create();
        $otherClient = Client::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($this->user)
            ->postJson("/api/clients/{$otherClient->id}/contacts", [
                'name'  => 'Intruso',
                'email' => 'intruso@hack.com',
            ])
            ->assertForbidden();
    }

    // ── Authorization ────────────────────────────────────────────────────────

    public function test_guests_cannot_access_contacts(): void
    {
        $this->getJson("/api/clients/{$this->client->id}/contacts")
            ->assertUnauthorized();
    }
}
