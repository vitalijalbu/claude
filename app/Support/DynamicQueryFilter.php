<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DynamicQueryFilter
{
    protected Builder $query;

    protected Request $request;

    protected array $filterable;

    public function __construct(Builder $query, Request $request, array $filterable)
    {
        $this->query = $query;
        $this->request = $request;
        $this->filterable = $filterable;
    }

    public function apply(): Builder
    {
        foreach ($this->filterable as $field => $options) {
            $value = $this->request->input($field);

            if (is_null($value) || $value === '') {
                continue;
            }

            // supporto a campi virtuali via 'expression'
            $column = $options['expression'] ?? $options['column'] ?? $field;
            $raw = isset($options['expression']); // flag per usare whereRaw
            $relation = $options['relation'] ?? null;

            if (is_array($value)) {
                foreach ($value as $operator => $val) {
                    $method = $this->mapOperatorToMethod($operator);
                    $this->applyFieldFilter($column, $val, $method, $relation, $raw);
                }
            } else {
                // fallback legacy: uguaglianza semplice
                $this->applyFieldFilter($column, $value, 'where', $relation, $raw);
            }
        }

        return $this->query;
    }

    // metodo adattato per usare orderByRaw quando $column Ã¨ una expression
    public function applySort(?string $sortKey = null, ?string $sortDirection = 'asc'): Builder
    {
        $sortKey = $sortKey ?? 'created_at';
        $sortDirection = mb_strtolower($sortDirection ?? 'asc');

        $sortField = $this->filterable[$sortKey] ?? null;

        if (! $sortField) {
            return $this->query->orderBy($sortKey, $sortDirection);
        }

        if (isset($sortField['expression'])) {
            return $this->query->orderByRaw($sortField['expression'] . ' ' . $sortDirection);
        }

        // Caso: sort su colonna di relazione
        if (isset($sortField['relation'])) {
            // Supporta una relazione profonda es: 'supplier.company'
            $relations = explode('.', $sortField['relation']);
            $base = $this->query->getModel()->getTable();
            $aliasCount = 0;

            foreach ($relations as $i => $relation) {
                $related = $this->query->getModel()->{$relation}()->getRelated();
                $foreignKey = $this->query->getModel()->{$relation}()->getQualifiedForeignKeyName();
                $ownerKey = $this->query->getModel()->{$relation}()->getQualifiedOwnerKeyName();

                // Calcola alias per evitare collisioni
                $alias = 't' . (++$aliasCount);

                $this->query->leftJoin($related->getTable() . ' as ' . $alias, $foreignKey, '=', $ownerKey);
                $base = $alias;
            }

            return $this->query
                ->orderBy("{$base}.{$sortField['column']}", $sortDirection)
                ->select($this->query->getModel()->getTable() . '.*');
        }

        // Caso semplice: colonna diretta
        return $this->query->orderBy($sortField['column'] ?? $sortKey, $sortDirection);
    }

    public function applyWithSort(): Builder
    {
        $this->apply();

        $sortKey = $this->request->input('sort_key', 'created_at');
        $sortDirection = $this->request->input('sort_direction', 'desc');

        return $this->applySort($sortKey, $sortDirection);
    }

    // aggiunto parametro $raw per gestire filtri su expression (campi concatenati)
    protected function applyFieldFilter(string $column, $value, string $method, ?string $relation, bool $raw = false): void
    {
        if ($relation) {
            $this->query->whereHas($relation, function ($q) use ($column, $value, $method, $raw) {
                $this->applyFilter($q, $method, $column, $value, $raw);
            });
        } else {
            $this->applyFilter($this->query, $method, $column, $value, $raw);
        }
    }

    // metodo adattato per usare whereRaw quando $column Ã¨ una expression
    protected function applyFilter(Builder $q, string $method, string $column, $value, bool $raw = false): void
    {
        switch ($method) {
            case 'whereIn':
                $values = is_array($value) ? $value : explode(',', (string) $value);
                $values = array_filter(array_map('trim', $values));
                if (! empty($values)) {
                    if ($raw) {
                        $placeholders = implode(',', array_fill(0, count($values), '?'));
                        $q->whereRaw("{$column} IN ({$placeholders})", $values);
                    } else {
                        $q->whereIn($column, $values);
                    }
                }
                break;

            case 'whereLike':
                $values = is_array($value) ? $value : explode(',', (string) $value);
                $values = array_filter(array_map('trim', $values));
                if (! empty($values)) {
                    $q->where(function ($query) use ($column, $values, $raw) {
                        foreach ($values as $item) {
                            if ($raw) {
                                $query->orWhereRaw("{$column} LIKE ?", ['%' . $item . '%']);
                            } else {
                                $query->orWhere($column, 'like', '%' . $item . '%');
                            }
                        }
                    });
                }
                break;

            case 'whereBetween':
                if (is_array($value) && count($value) === 2) {
                    $q->whereBetween($column, $value);
                }
                break;

            case 'where':
            default:
                if ($raw) {
                    $q->whereRaw("{$column} = ?", [$value]);
                } else {
                    $q->where($column, $value);
                }
                break;
        }
    }

    protected function mapOperatorToMethod(string $operator): string
    {
        return match (mb_strtolower($operator)) {
            'in' => 'whereIn',
            'like', 'contains' => 'whereLike',
            'between' => 'whereBetween',
            'eq' => 'where',
            default => 'where',
        };
    }
}
