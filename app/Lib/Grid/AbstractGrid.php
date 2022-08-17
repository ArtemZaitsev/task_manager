<?php

namespace App\Lib\Grid;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class AbstractGrid
{
    /** @var GridColumn[] */
    protected array $columns;
    protected string $gridName;

    public function __construct(string $gridName)
    {
        $this->gridName = $gridName;
    }

    /**
     * @return GridColumn[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    protected function applyFilters(Builder $query, Request $request): void
    {
        if (!$request->has('filters')) {
            return;
        }

        $filters = [];
        foreach ($this->columns as $column) {
            /** @var GridColumn $column */
            $filter = $column->getFilter();
            if ($filter !== null) {
                if (isset($filters[$filter->name()])) {
                    throw new \LogicException(sprintf('Filter with name %s exist', $filter->name()));
                }
                $filters[$filter->name()] = $filter;
            }
        }

        $filtersData = $request->get('filters');
        foreach ($filtersData as $filterName => $filterData) {
            if (!isset($filters[$filterName])) {
                throw new \LogicException(sprintf('Filter %s not exist', $filterName));
            }
            $filter = $filters[$filterName];
            $filter->apply($query, $filterData);
        }
    }

    protected function applySort(Builder $query, Request $request): void
    {
        if (!$request->query->has('sort')) {
            $query->orderBy('id', 'desc');
            return;
        }
        $orderField = $request->query->get('sort');
        if (empty($orderField)) {
            return;
        }
        $orderDirection = $request->query->get('sort_direction') ?? 'ASC';
        $query->orderBy($orderField, $orderDirection);
    }

    public function needDisplay(string $columnName): bool {
        $column = $this->findColumn($columnName);
        if($column === null) {
            throw new \LogicException(sprintf('Column name=%s not exist', $columnName));
        }

        $fields = session()->get($this->gridName, null);
        if($fields === null) {
            return $column->isDisplayDefault();
        }
        return in_array($columnName, $fields);
    }

    public function saveFields(array $fields): void {
        session()->put($this->gridName, $fields);
    }

    private function findColumn(string $columnName): ?GridColumn
    {
        foreach ($this->columns as $column){
            if($column->getName() === $columnName) {
                return $column;
            }
        }

        return null;
    }
}
