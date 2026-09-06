<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminDataTable
{
    /**
     * @param  array<string, string>  $orderColumns
     * @param  array<int, string>  $searchColumns
     * @param  callable(mixed, int): array<string, mixed>  $map
     * @param  array<string, array<int, string>>  $relationSearch
     */
    public static function of(
        Request $request,
        Builder $query,
        array $orderColumns,
        array $searchColumns,
        callable $map,
        array $relationSearch = []
    ): JsonResponse {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);

        if ($length < 1 || $length > 100) {
            $length = 10;
        }

        $recordsTotal = (clone $query)->toBase()->getCountForPagination();

        $search = trim((string) $request->input('search.value', ''));
        if ($search !== '' && ($searchColumns || $relationSearch)) {
            $like = '%'.$search.'%';

            $query->where(function (Builder $inner) use ($searchColumns, $relationSearch, $like) {
                $applied = false;

                foreach ($searchColumns as $column) {
                    if (! $applied) {
                        $inner->where($column, 'like', $like);
                        $applied = true;
                    } else {
                        $inner->orWhere($column, 'like', $like);
                    }
                }

                foreach ($relationSearch as $relation => $columns) {
                    $callback = function (Builder $rel) use ($columns, $like) {
                        $rel->where(function (Builder $relInner) use ($columns, $like) {
                            foreach ($columns as $index => $column) {
                                if ($index === 0) {
                                    $relInner->where($column, 'like', $like);
                                } else {
                                    $relInner->orWhere($column, 'like', $like);
                                }
                            }
                        });
                    };

                    if (! $applied) {
                        $inner->whereHas($relation, $callback);
                        $applied = true;
                    } else {
                        $inner->orWhereHas($relation, $callback);
                    }
                }
            });
        }

        $recordsFiltered = (clone $query)->toBase()->getCountForPagination();

        $order = $request->input('order.0', []);
        $orderColumnIndex = (int) ($order['column'] ?? -1);
        $orderDir = strtolower((string) ($order['dir'] ?? 'desc')) === 'asc' ? 'asc' : 'desc';
        $columns = $request->input('columns', []);
        $dataKey = is_array($columns) ? ($columns[$orderColumnIndex]['data'] ?? null) : null;

        if (is_string($dataKey) && isset($orderColumns[$dataKey])) {
            $query->orderBy($orderColumns[$dataKey], $orderDir);
        } else {
            $query->orderBy($query->getModel()->getQualifiedKeyName(), 'desc');
        }

        $rows = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($rows as $index => $row) {
            $data[] = $map($row, $start + $index + 1);
        }

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public static function statusToggle(string $action, bool $active, array $extra = []): string
    {
        return view('admin.partials.status-toggle', array_merge([
            'action' => $action,
            'active' => $active,
        ], $extra))->render();
    }

    public static function actions(string $editUrl, string $deleteUrl, string $confirmTitle, string $confirmText): string
    {
        return view('admin.partials.listing-actions', compact(
            'editUrl',
            'deleteUrl',
            'confirmTitle',
            'confirmText'
        ))->render();
    }
}
