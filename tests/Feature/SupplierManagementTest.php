<?php

namespace Tests\Feature;

use App\Models\Layer;
use App\Models\Layup;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_manage_supplier_hierarchy(): void
    {
        $user = User::factory()->create();

        $supplierResponse = $this->actingAs($user)->post(route('suppliers.store'), [
            'name' => 'PT Kayu Jaya',
        ]);

        $supplier = Supplier::firstOrFail();

        $supplierResponse
            ->assertRedirect(route('suppliers.show', $supplier))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('suppliers', ['name' => 'PT Kayu Jaya']);

        $layupResponse = $this->post(route('suppliers.layups.store', $supplier), [
            'name' => 'Outer Skin',
        ]);

        $layup = Layup::firstOrFail();

        $layupResponse
            ->assertRedirect(route('suppliers.show', $supplier))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('layups', [
            'supplier_id' => $supplier->id,
            'name' => 'Outer Skin',
        ]);

        $layerResponse = $this->post(route('suppliers.layups.layers.store', [$supplier, $layup]), [
            'layer_order' => 1,
            'thickness' => 2.4,
            'width' => 48,
            'angle' => 0,
        ]);

        $layer = Layer::firstOrFail();

        $layerResponse
            ->assertRedirect(route('suppliers.show', $supplier))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('layers', [
            'layup_id' => $layup->id,
            'layer_order' => 1,
            'thickness' => 2.4,
            'width' => 48,
            'angle' => 0,
        ]);

        $this->put(route('suppliers.update', $supplier), ['name' => 'PT Kayu Makmur'])
            ->assertRedirect(route('suppliers.show', $supplier));

        $this->put(route('suppliers.layups.update', [$supplier, $layup]), ['name' => 'Inner Core'])
            ->assertRedirect(route('suppliers.show', $supplier));

        $this->put(route('suppliers.layups.layers.update', [$supplier, $layup, $layer]), [
            'layer_order' => 1,
            'thickness' => 3.1,
            'width' => 52,
            'angle' => 45,
        ])->assertRedirect(route('suppliers.show', $supplier));

        $this->assertDatabaseHas('suppliers', ['id' => $supplier->id, 'name' => 'PT Kayu Makmur']);
        $this->assertDatabaseHas('layups', ['id' => $layup->id, 'name' => 'Inner Core']);
        $this->assertDatabaseHas('layers', [
            'id' => $layer->id,
            'thickness' => 3.1,
            'width' => 52,
            'angle' => 45,
        ]);

        $this->delete(route('suppliers.layups.layers.destroy', [$supplier, $layup, $layer]))
            ->assertRedirect(route('suppliers.show', $supplier));

        $this->assertDatabaseMissing('layers', ['id' => $layer->id]);

        $this->delete(route('suppliers.layups.destroy', [$supplier, $layup]))
            ->assertRedirect(route('suppliers.show', $supplier));

        $this->assertDatabaseMissing('layups', ['id' => $layup->id]);

        $this->delete(route('suppliers.destroy', $supplier))
            ->assertRedirect(route('suppliers.index'));

        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
    }
}
