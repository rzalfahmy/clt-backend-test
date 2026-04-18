<?php

namespace App\Contracts\Repositories;

use App\Models\Layer;
use App\Models\Layup;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LayerRepositoryInterface
{
    public function paginateWithRelations(int $perPage = 20, ?string $search = null): LengthAwarePaginator;

    public function findByOrder(Layup $layup, int $layerOrder): ?Layer;

    public function create(Layup $layup, array $attributes): Layer;

    public function update(Layer $layer, array $attributes): Layer;

    public function delete(Layer $layer): void;
}
