<?php

namespace Tests\Unit;

use App\Contracts\Repositories\SupplierRepositoryInterface;
use App\Models\Layer;
use App\Models\Layup;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_summary_counts_the_hierarchy(): void
    {
        $supplier = Supplier::factory()->create();
        $layup = Layup::factory()->for($supplier)->create();
        Layer::factory()->for($layup)->count(2)->create();

        $repository = app(SupplierRepositoryInterface::class);
        $summary = $repository->summary();

        $this->assertSame(1, $summary['suppliers']);
        $this->assertSame(1, $summary['layups']);
        $this->assertSame(2, $summary['layers']);
    }
}
