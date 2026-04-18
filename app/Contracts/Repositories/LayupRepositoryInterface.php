<?php

namespace App\Contracts\Repositories;

use App\Models\Layup;
use App\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LayupRepositoryInterface
{
    public function paginateWithRelations(int $perPage = 12, ?string $search = null): LengthAwarePaginator;

    public function loadLayers(Layup $layup): Layup;

    public function findByName(Supplier $supplier, string $name): ?Layup;

    public function create(Supplier $supplier, array $attributes): Layup;

    public function update(Layup $layup, array $attributes): Layup;

    public function delete(Layup $layup): void;
}
