<?php

namespace Tests\Feature\Exports;

use App\Models\Client;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();

        Client::factory(5)->create(['user_id' => $this->user->id])->each(function (Client $client) {
            Contact::factory()->create([
                'client_id'  => $client->id,
                'user_id'    => $this->user->id,
                'is_primary' => true,
            ]);
        });
    }

    // ── Excel ────────────────────────────────────────────────────────────────

    public function test_authenticated_user_can_download_excel(): void
    {
        Excel::fake();

        $this->actingAs($this->user)
            ->get('/clients/export/excel')
            ->assertOk();

        Excel::assertDownloaded('clientes_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function test_excel_respects_status_filter(): void
    {
        Excel::fake();

        Client::factory(3)->activo()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->get('/clients/export/excel?status=activo')
            ->assertOk();

        Excel::assertDownloaded('clientes_' . now()->format('Y-m-d') . '.xlsx');
    }

    public function test_excel_respects_search_filter(): void
    {
        Excel::fake();

        Client::factory()->create(['user_id' => $this->user->id, 'name' => 'Cliente Único XYZ']);

        $this->actingAs($this->user)
            ->get('/clients/export/excel?search=Único+XYZ')
            ->assertOk();

        Excel::assertDownloaded('clientes_' . now()->format('Y-m-d') . '.xlsx');
    }

    // ── PDF ──────────────────────────────────────────────────────────────────

    public function test_authenticated_user_can_download_pdf(): void
    {
        $this->actingAs($this->user)
            ->get('/clients/export/pdf')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_pdf_filename_includes_date(): void
    {
        $response = $this->actingAs($this->user)
            ->get('/clients/export/pdf');

        $disposition = $response->headers->get('content-disposition');
        $this->assertStringContainsString('clientes_' . now()->format('Y-m-d'), $disposition);
    }

    // ── Authorization ────────────────────────────────────────────────────────

    public function test_guests_cannot_export(): void
    {
        $this->get('/clients/export/excel')->assertRedirect('/login');
        $this->get('/clients/export/pdf')->assertRedirect('/login');
    }
}
