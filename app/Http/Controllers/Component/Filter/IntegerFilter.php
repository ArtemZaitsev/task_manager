<?php

namespace App\Http\Controllers\Component\Filter;

use App\Http\Controllers\Component\Filter\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class IntegerFilter implements Filter
{
    public function __construct(
        private string  $fieldName,
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


    public function template(): string
    {
        return 'component.filters.integer_filter';
    }

    public function apply(Builder $query, mixed $data): void
    {
        if (empty($data)) {
            return;
        }
        $query->where($this->fieldName, $data);
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
        return !empty($filterData);
    }

    public function templateData(Request $request): array
    {
        if (!$request->query->has('filters')) {
            return ['value' => ''];
        }
        $filters = $request->query->get('filters');
        if (!isset($filters[$this->name])) {
            return ['value' => ''];
        }
        return ['value' => $filters[$this->name]];
    }
}

