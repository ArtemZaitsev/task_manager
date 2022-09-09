<?php

namespace App\Lib\Grid;

use App\Lib\Grid\Field\Field;
use App\Lib\Grid\Field\FieldSet;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class AbstractGrid
{
    /** @var GridColumn[] */
    protected array $columns;
    protected string $gridName;
    protected FieldSet $fieldSet;

    public function __construct(string $gridName)
    {
        $this->gridName = $gridName;
        $this->columns = $this->buildColumns();
        $this->fieldSet = new FieldSet(array_map(
            fn(GridColumn $column) => new Field($column->getName(), $column->getLabel(), $column->isDisplayDefault()),
            $this->columns),
            $this->gridName
        );
        // $this->fieldSet->load();

    }

    protected abstract function buildColumns(): array;

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

    public function getFields(): array
    {
        $this->fieldSet->load();
        return $this->fieldSet->getFields();
    }

    public function saveFields(array $fields): void
    {
       $this->fieldSet->save($fields);
    }

    public function needDisplay(string $fieldName): bool {
        return $this->fieldSet->needDisplay($fieldName);
    }

    private function findColumn(string $columnName): ?GridColumn
    {
        foreach ($this->columns as $column) {
            if ($column->getName() === $columnName) {
                return $column;
            }
        }

        return null;
    }
}
