<?php

namespace App\Repositories\Eloquent;

use App\Contracts\Repositories\LayerRepositoryInterface;
use App\Models\Layer;
use App\Models\Layup;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LayerRepository implements LayerRepositoryInterface
{
    public function paginateWithRelations(int $perPage = 20, ?string $search = null): LengthAwarePaginator
    {
        return Layer::query()
            ->with('layup.supplier')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('layup', fn ($layupQuery) => $layupQuery->where('name', 'like', '%'.$search.'%'))
                    ->orWhereHas('layup.supplier', fn ($supplierQuery) => $supplierQuery->where('name', 'like', '%'.$search.'%'));
            })
            ->latest()
            ->paginate($perPage);
    }

    public function findByOrder(Layup $layup, int $layerOrder): ?Layer
    {
        return $layup->layers()->where('layer_order', $layerOrder)->first();
    }

    public function create(Layup $layup, array $attributes): Layer
    {
        return $layup->layers()->create($attributes);
    }

    public function update(Layer $layer, array $attributes): Layer
    {
        $layer->update($attributes);

        return $layer->refresh();
    }

    public function delete(Layer $layer): void
    {
        $layer->delete();
    }
}
