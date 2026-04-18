<?php

namespace Tests\Unit;

use App\Models\Layer;
use App\Models\Layup;
use App\Models\Supplier;
use App\Services\SupplierImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierImportServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_analyze_detects_layer_level_conflicts(): void
    {
        $supplier = Supplier::factory()->create();
        $layup = Layup::factory()->for($supplier)->create(['name' => 'Layup A']);
        Layer::factory()->for($layup)->create([
            'layer_order' => 1,
            'thickness' => 2,
            'width' => 30,
            'angle' => 0,
        ]);

        $service = app(SupplierImportService::class);

        $analysis = $service->analyze($supplier, [
            'layups' => [
                [
                    'name' => 'Layup A',
                    'layers' => [
                        ['layer_order' => 1, 'thickness' => 3, 'width' => 30, 'angle' => 45],
                    ],
                ],
            ],
        ]);

        $this->assertCount(1, $analysis['conflicts']);
        $this->assertSame('Layup A', $analysis['conflicts'][0]['layup_name']);
        $this->assertSame(['thickness', 'angle'], $analysis['conflicts'][0]['diff_fields']);
    }

    public function test_skip_strategy_preserves_existing_values(): void
    {
        $supplier = Supplier::factory()->create();
        $layup = Layup::factory()->for($supplier)->create(['name' => 'Layup A']);
        $layer = Layer::factory()->for($layup)->create([
            'layer_order' => 1,
            'thickness' => 2,
            'width' => 30,
            'angle' => 0,
        ]);

        $service = app(SupplierImportService::class);

        $result = $service->import($supplier, [
            'layups' => [
                [
                    'name' => 'Layup A',
                    'layers' => [
                        ['layer_order' => 1, 'thickness' => 9, 'width' => 90, 'angle' => 90],
                    ],
                ],
            ],
        ], 'skip');

        $this->assertSame('imported', $result['status']);
        $this->assertSame(1, $result['skipped']);
        $this->assertDatabaseHas('layers', [
            'id' => $layer->id,
            'thickness' => 2.0,
            'width' => 30.0,
            'angle' => 0.0,
        ]);
    }
}
