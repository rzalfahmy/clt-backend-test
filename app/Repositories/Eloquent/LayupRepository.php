<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\LayupRepositoryInterface;
use App\Models\Layup;
use App\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LayupRepository implements LayupRepositoryInterface
{
    public function paginateWithRelations(int $perPage = 12, ?string $search = null): LengthAwarePaginator
    {
        return Layup::query()
            ->with('supplier')
            ->withCount('layers')
            ->when($search, fn ($query) => $query->where('name', 'like', '%'.$search.'%'))
            ->latest()
            ->paginate($perPage);
    }

    public function loadLayers(Layup $layup): Layup
    {
        return $layup->load([
            'supplier',
            'layers' => fn ($query) => $query->orderBy('layer_order'),
        ]);
    }

    public function findByName(Supplier $supplier, string $name): ?Layup
    {
        return $supplier->layups()->where('name', $name)->first();
    }

    public function create(Supplier $supplier, array $attributes): Layup
    {
        return $supplier->layups()->create($attributes);
    }

    public function update(Layup $layup, array $attributes): Layup
    {
        $layup->update($attributes);

        return $layup->refresh();
    }

    public function delete(Layup $layup): void
    {
        $layup->delete();
    }
}
