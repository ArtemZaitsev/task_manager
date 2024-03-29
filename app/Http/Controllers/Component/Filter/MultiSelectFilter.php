<?php

namespace App\Http\Controllers\Component\Filter;

use App\Http\Controllers\Component\Filter\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MultiSelectFilter implements Filter
{
    public function __construct(
        private string  $fieldName,
        private array   $filterData,
        private ?string $name = null,
    )
    {
        if ($this->name === null) {
            $this->name = $this->fieldName;
        }
    }

    public function name(): string
    {
        return $this->name;
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function getFilterData(): array
    {
        return $this->filterData;
    }


    public function template(): string
    {
        return 'lib.filters.multi_select_filter';
    }

    public function apply(Builder $query, mixed $data): void
    {
        if (empty($data)) {
            return;
        }
        $data = array_map(fn($item) => (int)$item, $data);

        if (in_array(0, $data)) {
            $data = array_filter($data, fn(int $value) => $value !== 0);
            $query->where(function ($subQuery) use ($data) {
                $subQuery->whereIn($this->fieldName, $data)
                    ->orWhereNull($this->fieldName);
            });
        } else {
            $query->whereIn($this->fieldName, $data);
        }
    }

    public function isEnable(): bool
    {
        $request = request();
        if (!$request->query->has('filters')) {
            return false;
        }
        $filters = $request->query->get('filters');
        if (!isset($filters[$this->name])) {
            return false;
        }
        $filterData = $filters[$this->name];
        return count($filterData) > 0;
    }


    public function templateData(Request $request): array
    {
        if (!$request->query->has('filters')) {
            return ['value' => []];
        }
        $filters = $request->query->get('filters');
        if (!isset($filters[$this->name])) {
            return ['value' => []];
        }
        return ['value' => $filters[$this->name]];
    }

}

