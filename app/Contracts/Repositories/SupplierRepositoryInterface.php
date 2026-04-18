<?php

namespace App\Contracts\Repositories;

use App\Models\Supplier;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SupplierRepositoryInterface
{
    public function paginateWithHierarchy(int $perPage = 10, ?string $search = null): LengthAwarePaginator;

    public function loadHierarchy(Supplier $supplier): Supplier;

    public function create(array $attributes): Supplier;

    public function update(Supplier $supplier, array $attributes): Supplier;

    public function delete(Supplier $supplier): void;

    public function summary(): array;
}
