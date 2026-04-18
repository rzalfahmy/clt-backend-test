<?php

namespace App\Services;

use App\Contracts\Repositories\LayerRepositoryInterface;
use App\Contracts\Repositories\LayupRepositoryInterface;
use App\Models\Supplier;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SupplierImportService
{
    public function __construct(
        private readonly LayupRepositoryInterface $layups,
        private readonly LayerRepositoryInterface $layers,
    ) {
    }

    public function parse(string $contents): array
    {
        $decoded = json_decode($contents, true);

        if (! is_array($decoded) || json_last_error() !== JSON_ERROR_NONE) {
            throw ValidationException::withMessages([
                'payload' => 'The import payload must be a valid JSON document.',
            ]);
        }

        $layups = Arr::get($decoded, 'layups');

        if (! is_array($layups)) {
            throw ValidationException::withMessages([
                'payload' => 'The import payload must include a layups array.',
            ]);
        }

        $normalizedLayups = collect($layups)->map(function (array $layup, int $index) {
            if (! isset($layup['name']) || ! is_string($layup['name']) || trim($layup['name']) === '') {
                throw ValidationException::withMessages([
                    'payload' => "Layup at index {$index} must include a name.",
                ]);
            }

            $layers = $layup['layers'] ?? [];

            if (! is_array($layers)) {
                throw ValidationException::withMessages([
                    'payload' => "Layup {$layup['name']} must include a layers array.",
                ]);
            }

            return [
                'name' => trim($layup['name']),
                'layers' => collect($layers)->map(function (array $layer, int $layerIndex) use ($layup) {
                    foreach (['layer_order', 'thickness', 'width', 'angle'] as $field) {
                        if (! array_key_exists($field, $layer)) {
                            throw ValidationException::withMessages([
                                'payload' => "Layer at index {$layerIndex} in layup {$layup['name']} must include {$field}.",
                            ]);
                        }
                    }

                    return [
                        'layer_order' => (int) $layer['layer_order'],
                        'thickness' => (float) $layer['thickness'],
                        'width' => (float) $layer['width'],
                        'angle' => (float) $layer['angle'],
                    ];
                })->sortBy('layer_order')->values()->all(),
            ];
        })->values()->all();

        return [
            'supplier' => Arr::get($decoded, 'supplier', []),
            'layups' => $normalizedLayups,
        ];
    }

    public function analyze(Supplier $supplier, array $payload): array
    {
        $conflicts = [];

        foreach ($payload['layups'] as $layupData) {
            $existingLayup = $this->layups->findByName($supplier, $layupData['name']);

            if (! $existingLayup) {
                continue;
            }

            foreach ($layupData['layers'] as $layerData) {
                $existingLayer = $this->layers->findByOrder($existingLayup, $layerData['layer_order']);

                if (! $existingLayer) {
                    continue;
                }

                $diffFields = collect(['thickness', 'width', 'angle'])
                    ->filter(fn ($field) => (float) $existingLayer->{$field} !== (float) $layerData[$field])
                    ->values()
                    ->all();

                if ($diffFields === []) {
                    continue;
                }

                $conflicts[] = [
                    'key' => $this->conflictKey($layupData['name'], $layerData['layer_order']),
                    'layup_name' => $layupData['name'],
                    'layer_order' => $layerData['layer_order'],
                    'diff_fields' => $diffFields,
                    'existing' => [
                        'thickness' => (float) $existingLayer->thickness,
                        'width' => (float) $existingLayer->width,
                        'angle' => (float) $existingLayer->angle,
                    ],
                    'incoming' => Arr::only($layerData, ['thickness', 'width', 'angle']),
                ];
            }
        }

        return [
            'payload' => $payload,
            'conflicts' => $conflicts,
        ];
    }

    public function import(
        Supplier $supplier,
        array $payload,
        string $strategy = 'overwrite',
        array $decisions = [],
    ): array {
        $analysis = $this->analyze($supplier, $payload);

        if ($strategy === 'reject' && $analysis['conflicts'] !== []) {
            return [
                'status' => 'rejected',
                'created' => 0,
                'updated' => 0,
                'skipped' => 0,
                'conflicts' => $analysis['conflicts'],
            ];
        }

        return DB::transaction(function () use ($supplier, $analysis, $strategy, $decisions) {
            $created = 0;
            $updated = 0;
            $skipped = 0;

            foreach ($analysis['payload']['layups'] as $layupData) {
                $layup = $this->layups->findByName($supplier, $layupData['name']);

                if (! $layup) {
                    $layup = $this->layups->create($supplier, ['name' => $layupData['name']]);
                    $created++;
                }

                foreach ($layupData['layers'] as $layerData) {
                    $existingLayer = $this->layers->findByOrder($layup, $layerData['layer_order']);

                    if (! $existingLayer) {
                        $this->layers->create($layup, $layerData);
                        $created++;

                        continue;
                    }

                    $isConflict = collect(['thickness', 'width', 'angle'])
                        ->contains(fn ($field) => (float) $existingLayer->{$field} !== (float) $layerData[$field]);

                    if (! $isConflict) {
                        continue;
                    }

                    $decision = match ($strategy) {
                        'overwrite' => 'accept_incoming',
                        'skip' => 'keep_existing',
                        'manual' => $decisions[$this->conflictKey($layupData['name'], $layerData['layer_order'])] ?? null,
                        default => null,
                    };

                    if ($decision === 'accept_incoming') {
                        $this->layers->update($existingLayer, $layerData);
                        $updated++;
                        continue;
                    }

                    if ($decision === 'keep_existing') {
                        $skipped++;
                        continue;
                    }

                    throw ValidationException::withMessages([
                        'payload' => 'Every manual conflict must be resolved before the import can be applied.',
                    ]);
                }
            }

            return [
                'status' => 'imported',
                'created' => $created,
                'updated' => $updated,
                'skipped' => $skipped,
                'conflicts' => $analysis['conflicts'],
            ];
        });
    }

    public function conflictKey(string $layupName, int $layerOrder): string
    {
        return $layupName.'#'.$layerOrder;
    }
}
