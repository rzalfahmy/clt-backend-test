<?php

namespace Tests\Feature;

use App\Models\Layer;
use App\Models\Layup;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierImportExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_supplier_export_includes_nested_layups_and_layers(): void
    {
        $user = User::factory()->create();
        $supplier = Supplier::factory()->create(['name' => 'Exportable Supplier']);
        $layup = Layup::factory()->for($supplier)->create(['name' => 'Layup Alpha']);
        Layer::factory()->for($layup)->create([
            'layer_order' => 1,
            'thickness' => 2.5,
            'width' => 40,
            'angle' => 0,
        ]);

        $response = $this->actingAs($user)->get(route('suppliers.export', $supplier));

        $response->assertOk();
        $payload = json_decode($response->streamedContent(), true);

        $this->assertSame('Exportable Supplier', $payload['supplier']['name']);
        $this->assertCount(1, $payload['layups']);
        $this->assertSame('Layup Alpha', $payload['layups'][0]['name']);
        $this->assertSame(1, $payload['layups'][0]['layers'][0]['layer_order']);
    }

    public function test_import_can_overwrite_conflicts_and_create_missing_records(): void
    {
        $user = User::factory()->create();
        $supplier = Supplier::factory()->create();
        $layup = Layup::factory()->for($supplier)->create(['name' => 'Layup Alpha']);
        Layer::factory()->for($layup)->create([
            'layer_order' => 1,
            'thickness' => 2,
            'width' => 30,
            'angle' => 0,
        ]);

        $payload = [
            'layups' => [
                [
                    'name' => 'Layup Alpha',
                    'layers' => [
                        ['layer_order' => 1, 'thickness' => 3, 'width' => 50, 'angle' => 45],
                        ['layer_order' => 2, 'thickness' => 1.5, 'width' => 22, 'angle' => -45],
                    ],
                ],
                [
                    'name' => 'Layup Beta',
                    'layers' => [
                        ['layer_order' => 1, 'thickness' => 1.8, 'width' => 25, 'angle' => 90],
                    ],
                ],
            ],
        ];

        $this->actingAs($user)->post(route('suppliers.import', $supplier), [
            'strategy' => 'overwrite',
            'payload' => json_encode($payload),
        ])->assertRedirect(route('suppliers.show', $supplier));

        $this->assertDatabaseHas('layers', [
            'layup_id' => $layup->id,
            'layer_order' => 1,
            'thickness' => 3.0,
            'width' => 50.0,
            'angle' => 45.0,
        ]);

        $this->assertDatabaseHas('layers', [
            'layup_id' => $layup->id,
            'layer_order' => 2,
        ]);

        $this->assertDatabaseHas('layups', [
            'supplier_id' => $supplier->id,
            'name' => 'Layup Beta',
        ]);
    }

    public function test_import_can_be_rejected_with_conflict_report(): void
    {
        $user = User::factory()->create();
        $supplier = Supplier::factory()->create();
        $layup = Layup::factory()->for($supplier)->create(['name' => 'Layup Alpha']);
        Layer::factory()->for($layup)->create([
            'layer_order' => 1,
            'thickness' => 2,
            'width' => 30,
            'angle' => 0,
        ]);

        $payload = [
            'layups' => [
                [
                    'name' => 'Layup Alpha',
                    'layers' => [
                        ['layer_order' => 1, 'thickness' => 9, 'width' => 99, 'angle' => 90],
                    ],
                ],
            ],
        ];

        $response = $this->actingAs($user)->post(route('suppliers.import', $supplier), [
            'strategy' => 'reject',
            'payload' => json_encode($payload),
        ]);

        $response
            ->assertRedirect(route('suppliers.show', $supplier))
            ->assertSessionHas('conflict_report');

        $this->assertDatabaseHas('layers', [
            'layup_id' => $layup->id,
            'layer_order' => 1,
            'thickness' => 2.0,
            'width' => 30.0,
            'angle' => 0.0,
        ]);
    }

    public function test_manual_conflict_resolution_flow_applies_selected_decision(): void
    {
        $user = User::factory()->create();
        $supplier = Supplier::factory()->create();
        $layup = Layup::factory()->for($supplier)->create(['name' => 'Layup Alpha']);
        $layer = Layer::factory()->for($layup)->create([
            'layer_order' => 1,
            'thickness' => 2,
            'width' => 30,
            'angle' => 0,
        ]);

        $payload = [
            'layups' => [
                [
                    'name' => 'Layup Alpha',
                    'layers' => [
                        ['layer_order' => 1, 'thickness' => 4, 'width' => 60, 'angle' => 45],
                    ],
                ],
            ],
        ];

        $response = $this->actingAs($user)->post(route('suppliers.import', $supplier), [
            'strategy' => 'manual',
            'payload' => json_encode($payload),
        ]);

        $response->assertRedirect();

        $location = $response->headers->get('Location');
        $path = parse_url($location, PHP_URL_PATH);

        preg_match('#/suppliers/\d+/imports/([^/]+)/conflicts/(\d+)#', $path, $matches);

        $this->assertNotEmpty($matches[1] ?? null);

        $token = $matches[1];
        $index = (int) ($matches[2] ?? 0);

        $this->get($location)
            ->assertOk()
            ->assertSee('Manual Conflict Resolution');

        $this->post(route('suppliers.import-conflicts.resolve', [$supplier, $token, $index]), [
            'decision' => 'accept_incoming',
        ])->assertRedirect(route('suppliers.show', $supplier));

        $this->assertDatabaseHas('layers', [
            'id' => $layer->id,
            'thickness' => 4.0,
            'width' => 60.0,
            'angle' => 45.0,
        ]);
    }
}
